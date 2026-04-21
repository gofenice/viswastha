<?php

namespace App\Console\Commands;

use App\Models\BinaryPairLog;
use App\Models\BinaryTransaction;
use App\Models\BinaryWallet;
use App\Models\User;
use App\Models\UserPackage;
use App\Models\Package;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CalculateBinaryIncome extends Command
{
    protected $signature   = 'binary:calculate {date? : Y-m-d, defaults to yesterday}';
    protected $description = 'Calculate daily binary pair-match income for all eligible users';

    // Package rules: [pair_rate, daily_cap_pairs]
    const RULES = [
        'basic'   => ['rate' => 200,   'cap' => 25],
        'premium' => ['rate' => 1000,  'cap' => 10],
    ];

    public function handle(): int
    {
        $date = $this->argument('date')
            ? Carbon::parse($this->argument('date'))->toDateString()
            : Carbon::yesterday()->toDateString();

        $this->info("Calculating binary income for {$date}");

        // All users with an active binary-eligible package
        User::whereNotNull('parent_id')   // must be in the tree
            ->whereIn('id', function ($q) {
                $q->select('user_id')
                  ->from('user_packages')
                  ->join('packages', 'packages.id', '=', 'user_packages.package_id')
                  ->whereIn('packages.package_code', ['basic_package', 'premium_package', 'prime_package'])
                  ->where('user_packages.status', 1);
            })
            ->select('id')
            ->chunk(200, function ($users) use ($date) {
                foreach ($users as $user) {
                    $this->processUser($user->id, $date);
                }
            });

        $this->info('Done.');
        return self::SUCCESS;
    }

    private function processUser(int $userId, string $date): void
    {
        $packageType = $this->resolvePackageType($userId);
        if (!$packageType) return; // Prime-only (1 prime) — no binary income

        // Skip if already calculated for this date (UNIQUE constraint is the guard)
        if (BinaryPairLog::where('user_id', $userId)->where('calc_date', $date)->exists()) {
            return;
        }

        $rules = self::RULES[$packageType];

        // Count new activations in each leg for this date using recursive CTE
        $newLeft  = $this->legActivations($userId, 'left',  $date);
        $newRight = $this->legActivations($userId, 'right', $date);

        // Get carry-in from yesterday's log
        $yesterday = Carbon::parse($date)->subDay()->toDateString();
        $prev = BinaryPairLog::where('user_id', $userId)->where('calc_date', $yesterday)->first();
        $carryLeft  = $prev ? $prev->carry_out_left  : 0;
        $carryRight = $prev ? $prev->carry_out_right : 0;

        $totalLeft  = $newLeft  + $carryLeft;
        $totalRight = $newRight + $carryRight;

        $matched = min($totalLeft, $totalRight);
        $capped  = min($matched, $rules['cap']);
        $income  = $capped * $rules['rate'];

        // Carry-forward: excess from stronger leg
        // Flush: excess from weaker leg (discarded)
        if ($totalLeft >= $totalRight) {
            $carryOutLeft   = $totalLeft - $capped;
            $carryOutRight  = 0;
            $flushedLeft    = 0;
            $flushedRight   = $totalRight - $capped;
        } else {
            $carryOutLeft   = 0;
            $carryOutRight  = $totalRight - $capped;
            $flushedLeft    = $totalLeft - $capped;
            $flushedRight   = 0;
        }

        DB::transaction(function () use (
            $userId, $date, $packageType, $rules,
            $newLeft, $newRight, $carryLeft, $carryRight,
            $totalLeft, $totalRight, $matched, $capped, $income,
            $carryOutLeft, $carryOutRight, $flushedLeft, $flushedRight
        ) {
            BinaryPairLog::create([
                'user_id'       => $userId,
                'calc_date'     => $date,
                'package_type'  => $packageType,
                'new_left'      => $newLeft,
                'new_right'     => $newRight,
                'carry_in_left' => $carryLeft,
                'carry_in_right'=> $carryRight,
                'total_left'    => $totalLeft,
                'total_right'   => $totalRight,
                'matched_pairs' => $matched,
                'capped_pairs'  => $capped,
                'income'        => $income,
                'carry_out_left'    => $carryOutLeft,
                'carry_out_right'   => $carryOutRight,
                'flushed_left'      => $flushedLeft,
                'flushed_right'     => $flushedRight,
            ]);

            if ($income > 0) {
                BinaryTransaction::credit(
                    $userId,
                    'binary_pair',
                    $income,
                    "Binary pair income ({$capped} pairs × ₹{$rules['rate']})",
                    [
                        'calc_date' => $date,
                        'meta'      => [
                            'package_type'  => $packageType,
                            'pairs'         => $capped,
                            'rate'          => $rules['rate'],
                            'total_left'    => $totalLeft,
                            'total_right'   => $totalRight,
                            'carry_forward' => max($carryOutLeft, $carryOutRight),
                            'flushed'       => max($flushedLeft, $flushedRight),
                        ],
                    ]
                );
            }

            // Update wallet carry-forward for next day
            $wallet = BinaryWallet::forUser($userId);
            $wallet->carry_forward_left  = $carryOutLeft;
            $wallet->carry_forward_right = $carryOutRight;
            $wallet->save();
        });
    }

    /**
     * Count package activations in one leg's subtree for a given date.
     * Uses a recursive CTE for efficiency — walks the whole subtree in SQL.
     */
    private function legActivations(int $userId, string $side, string $date): int
    {
        // Find the direct child on this side
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
              AND up.status = 1
              AND DATE(up.created_at) = ?
        ", [$child, $date]);

        return (int) ($result[0]->cnt ?? 0);
    }

    /**
     * Resolve the effective package type for binary income:
     *  - basic_package           → basic
     *  - premium_package         → premium
     *  - prime_package × 2       → premium
     *  - prime_package × 1       → null (no binary income)
     */
    private function resolvePackageType(int $userId): ?string
    {
        $packages = UserPackage::where('user_id', $userId)
            ->where('status', 1)
            ->join('packages', 'packages.id', '=', 'user_packages.package_id')
            ->pluck('packages.package_code')
            ->toArray();

        if (in_array('premium_package', $packages)) return 'premium';
        if (in_array('basic_package',   $packages)) return 'basic';

        // 2× prime = premium eligibility
        $primeCount = array_count_values($packages)['prime_package'] ?? 0;
        if ($primeCount >= 2) return 'premium';

        return null; // prime × 1 — no binary income
    }
}
