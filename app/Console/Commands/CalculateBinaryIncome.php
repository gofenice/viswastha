<?php

namespace App\Console\Commands;

use App\Models\BinaryPairLog;
use App\Models\BinaryTransaction;
use App\Models\BinaryWallet;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * Binary income calculation — processes all unmatched activations up to now.
 *
 * Each package type is matched independently (basic vs basic, premium vs premium).
 * "New" = activations since the last run for this user+package (or since binary launch).
 * Carry-forward from the previous run is added to new activations before matching.
 *
 * Run anytime: php artisan binary:calculate
 */
class CalculateBinaryIncome extends Command
{
    protected $signature   = 'binary:calculate';
    protected $description = 'Process all unmatched binary activations up to now, per package';

    private string $binaryStartDate = '2026-04-20 00:00:00';

    public function handle(): int
    {
        $this->info('Calculating binary income — processing all unmatched activations...');

        $packages = DB::table('packages')
            ->where('binary_commission', '>', 0)
            ->where('daily_pair_cap', '>', 0)
            ->where('status', 1)
            ->get();

        if ($packages->isEmpty()) {
            $this->info('No packages with binary commission configured.');
            return self::SUCCESS;
        }

        foreach ($packages as $package) {
            $this->info("Package: {$package->name} (BV:₹{$package->binary_commission} Cap:{$package->daily_pair_cap})");

            // mother_id = 0 means Child ID — excluded from binary pair income
            $eligibleIds = DB::table('user_packages')
                ->join('users', 'users.id', '=', 'user_packages.user_id')
                ->where('user_packages.package_id', $package->id)
                ->where('user_packages.status', 1)
                ->where('users.mother_id', '!=', 0)
                ->pluck('user_packages.user_id')
                ->unique()
                ->toArray();

            foreach ($eligibleIds as $userId) {
                $this->processUserPackage((int) $userId, $package);
            }
        }

        $this->info('Done.');
        return self::SUCCESS;
    }

    private function processUserPackage(int $userId, object $package): void
    {
        // Last run for this user+package — used as the "since" cutoff
        $lastLog    = BinaryPairLog::where('user_id', $userId)
                        ->where('package_id', $package->id)
                        ->orderByDesc('id')
                        ->first();

        $since      = $lastLog ? $lastLog->created_at->toDateTimeString() : $this->binaryStartDate;
        $carryLeft  = $lastLog ? $lastLog->carry_out_left  : 0;
        $carryRight = $lastLog ? $lastLog->carry_out_right : 0;

        // Count activations in each leg SINCE the last run
        $newLeft  = $this->legActivationsSince($userId, 'left',  $since, $package->id);
        $newRight = $this->legActivationsSince($userId, 'right', $since, $package->id);

        // Nothing new to process
        if ($newLeft === 0 && $newRight === 0) {
            return;
        }

        $totalLeft  = $newLeft  + $carryLeft;
        $totalRight = $newRight + $carryRight;

        // First income unlock: need at least 3 total lifetime activations across both legs
        $lifetimeLeft  = $this->legTotalCount($userId, 'left',  $package->id);
        $lifetimeRight = $this->legTotalCount($userId, 'right', $package->id);
        if (($lifetimeLeft + $lifetimeRight) < 3) {
            return;
        }

        $rate   = (float) $package->binary_commission;
        $dayCap = (int)   $package->daily_pair_cap;

        if ($rate <= 0 || $dayCap <= 0) {
            return; // Package has no binary income (e.g. Prime) — skip entirely
        }

        if (!$lastLog) {
            // ── First-run "2:1" unlock rule ────────────────────────────────────────
            // Primary side (more activations, or LEFT if equal) contributes 2.
            // Secondary side contributes 1. Those 3 are consumed for the first income.
            // Remaining activations pair normally (1:1).
            $leftIsPrimary  = $totalLeft >= $totalRight;
            $primaryTotal   = $leftIsPrimary ? $totalLeft  : $totalRight;
            $secondaryTotal = $leftIsPrimary ? $totalRight : $totalLeft;

            if ($primaryTotal >= 2 && $secondaryTotal >= 1) {
                $primaryEff     = $primaryTotal   - 2;
                $secondaryEff   = $secondaryTotal - 1;
                $normalPossible = min($primaryEff, $secondaryEff);
                $matched        = 1 + $normalPossible;
                $capped         = min($matched, $dayCap);
                $income         = $capped * $rate;
                $normalCapped   = max(0, $capped - 1);

                $primaryRem   = max(0, $primaryEff   - $normalCapped);
                $secondaryRem = max(0, $secondaryEff - $normalCapped);

                // Stronger remaining carries; weaker flushes
                if ($primaryRem >= $secondaryRem) {
                    [$carryPrimary, $carrySecondary, $flushPrimary, $flushSecondary] =
                        [$primaryRem, 0, 0, $secondaryRem];
                } else {
                    [$carryPrimary, $carrySecondary, $flushPrimary, $flushSecondary] =
                        [0, $secondaryRem, $primaryRem, 0];
                }

                [$carryOutLeft,  $carryOutRight,  $flushedLeft,  $flushedRight] = $leftIsPrimary
                    ? [$carryPrimary, $carrySecondary, $flushPrimary,   $flushSecondary]
                    : [$carrySecondary, $carryPrimary, $flushSecondary, $flushPrimary];
            } else {
                // One side is empty — can't unlock yet; hold all activations
                $matched = $capped = 0;
                $income  = 0;
                $carryOutLeft  = $totalLeft;
                $carryOutRight = $totalRight;
                $flushedLeft   = $flushedRight = 0;
            }
        } else {
            // ── Subsequent runs: standard 1:1 pair matching ────────────────────────
            $matched = min($totalLeft, $totalRight);
            $capped  = min($matched, $dayCap);
            $income  = $capped * $rate;

            // Stronger leg carries forward; weaker leg excess flushed
            if ($totalLeft >= $totalRight) {
                $carryOutLeft  = $totalLeft - $capped;
                $carryOutRight = 0;
                $flushedLeft   = 0;
                $flushedRight  = max(0, $totalRight - $capped);
            } else {
                $carryOutLeft  = 0;
                $carryOutRight = $totalRight - $capped;
                $flushedLeft   = max(0, $totalLeft - $capped);
                $flushedRight  = 0;
            }
        }

        DB::transaction(function () use (
            $userId, $package, $newLeft, $newRight, $carryLeft, $carryRight,
            $totalLeft, $totalRight, $matched, $capped, $income, $rate,
            $carryOutLeft, $carryOutRight, $flushedLeft, $flushedRight
        ) {
            BinaryPairLog::create([
                'user_id'         => $userId,
                'package_id'      => $package->id,
                'package_type'    => $package->package_code,
                'calc_date'       => now()->toDateString(),
                'new_left'        => $newLeft,
                'new_right'       => $newRight,
                'carry_in_left'   => $carryLeft,
                'carry_in_right'  => $carryRight,
                'total_left'      => $totalLeft,
                'total_right'     => $totalRight,
                'matched_pairs'   => $matched,
                'capped_pairs'    => $capped,
                'income'          => $income,
                'carry_out_left'  => $carryOutLeft,
                'carry_out_right' => $carryOutRight,
                'flushed_left'    => $flushedLeft,
                'flushed_right'   => $flushedRight,
            ]);

            if ($income > 0) {
                BinaryTransaction::credit(
                    $userId,
                    'binary_pair',
                    $income,
                    "Binary pair income — {$package->name} ({$capped} pairs × ₹{$rate})",
                    [
                        'package_id' => $package->id,
                        'meta' => [
                            'package'       => $package->name,
                            'pairs'         => $capped,
                            'rate'          => $rate,
                            'total_left'    => $totalLeft,
                            'total_right'   => $totalRight,
                            'carry_forward' => max($carryOutLeft, $carryOutRight),
                            'flushed'       => max($flushedLeft, $flushedRight),
                        ],
                    ]
                );
            }

            // Update wallet carry-forward display (sum across all packages)
            $wallet = BinaryWallet::forUser($userId);
            $wallet->carry_forward_left  = DB::table('binary_pair_logs')
                ->where('user_id', $userId)
                ->whereIn('id', function ($q) use ($userId) {
                    $q->selectRaw('MAX(id)')
                      ->from('binary_pair_logs')
                      ->where('user_id', $userId)
                      ->groupBy('package_id');
                })
                ->sum('carry_out_left');
            $wallet->carry_forward_right = DB::table('binary_pair_logs')
                ->where('user_id', $userId)
                ->whereIn('id', function ($q) use ($userId) {
                    $q->selectRaw('MAX(id)')
                      ->from('binary_pair_logs')
                      ->where('user_id', $userId)
                      ->groupBy('package_id');
                })
                ->sum('carry_out_right');
            $wallet->save();
        });
    }

    /**
     * Count activations of a specific package in one leg SINCE a given timestamp.
     */
    private function legActivationsSince(int $userId, string $side, string $since, int $packageId): int
    {
        $child = User::where('parent_id', $userId)->where('position', $side)->value('id');
        if (!$child) return 0;

        $result = DB::select("
            WITH RECURSIVE subtree AS (
                SELECT id FROM users WHERE id = ?
                UNION ALL
                SELECT u.id FROM users u
                INNER JOIN subtree s ON u.parent_id = s.id
            )
            SELECT COUNT(*) AS cnt
            FROM user_packages up
            WHERE up.user_id IN (SELECT id FROM subtree)
              AND up.package_id = ?
              AND up.status = 1
              AND up.created_at > ?
        ", [$child, $packageId, $since]);

        return (int) ($result[0]->cnt ?? 0);
    }

    /**
     * Count total lifetime activations of a specific package in one leg (for unlock check).
     */
    private function legTotalCount(int $userId, string $side, int $packageId): int
    {
        $child = User::where('parent_id', $userId)->where('position', $side)->value('id');
        if (!$child) return 0;

        $result = DB::select("
            WITH RECURSIVE subtree AS (
                SELECT id FROM users WHERE id = ?
                UNION ALL
                SELECT u.id FROM users u
                INNER JOIN subtree s ON u.parent_id = s.id
            )
            SELECT COUNT(*) AS cnt
            FROM user_packages up
            WHERE up.user_id IN (SELECT id FROM subtree)
              AND up.package_id = ?
              AND up.status = 1
              AND up.created_at >= ?
        ", [$child, $packageId, $this->binaryStartDate]);

        return (int) ($result[0]->cnt ?? 0);
    }
}
