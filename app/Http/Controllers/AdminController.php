<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\Package;
use App\Models\Product;
use App\Models\Message;
use App\Models\BankTransactionDetail;
use App\Models\PairMatch;
use App\Models\PairMatchIncome;
use App\Models\RankIncome;
use App\Models\PinGeneration;
use App\Models\ReferralIncome;
use App\Models\UserPackage;
use App\Models\WithdrawalRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\CalculationController;
use App\Models\BinaryTreeSetting;
use App\Models\AdminWallet;
use App\Models\Announcement;
use App\Models\BasicRank;
use App\Models\BasicRankAchieve;
use App\Models\BasicRankIncome;
use App\Models\BasicUserRankIncome;
use App\Models\BoardIncomeWallet;
use App\Models\BoardMember;
use App\Models\BoardUser;
use App\Models\BoardUserWallet;
use App\Models\BonusWallet;
use App\Models\ChildMotherPayment;
use App\Models\CompanyRankIncome;
use App\Models\District;
use App\Models\DonationWallet;
use App\Models\ExecutiveIncomeWallet;
use App\Models\ExecutiveUser;
use App\Models\ExecutiveUserWallet;
use App\Models\Franchisee;
use App\Models\HolidayPackageBooking;
use App\Models\IncentiveIncomeWallet;
use App\Models\IncentiveUser;
use App\Models\IncentiveUserWallet;
use App\Models\LocalBody;
use App\Models\LocalBodyType;
use App\Models\OfflineProductBill;
use App\Models\PinTransferDetail;
use App\Models\PrivilegeIncomeWallet;
use App\Models\PrivilegeUser;
use App\Models\PrivilegeUserWallet;
use App\Models\ProductDeliveryDetail;
use App\Models\RepurchaseWallet;
use App\Models\RoyaltyIncomeWallet;
use App\Models\RoyaltyUserWallet;
use App\Models\Shop;
use App\Models\ShopCoupon;
use App\Models\State;
use App\Models\TrashMoney;
use App\Models\UserBankingDetail;
use App\Models\UserRankHistory;
use App\Models\WalletTransactionDetail;
use App\Models\ShopReceipt;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    /** Date from which binary tree packages count (skip sunflower-era activations) */
    private string $binaryStartDate = '2026-04-20';


    public function login()
    {
        return view('Admin/login');
        // return view('Admin/maintenance');
    }
    public function adminlogin()
    {
        return view('Admin/login');
    }
    public function admin()
    {
        $user = Auth::user();
        $sponsor = User::find($user->sponsor_id);
        $rankName = $user->rank->rank_name ?? 'No Rank';

        //current rank income
        $rank = $user->rank_id;
        $rankCount = User::where('rank_id', $rank)->count();
        $rankIncome = DB::table('company_rank_incomes')
            ->where('rank_id', $rank)
            ->where('is_redeemed', 0)
            ->sum('amount');
        $individualShare = $rankCount > 0 ? floor($rankIncome / $rankCount) : 0;

        $announcement = Announcement::first();

        $basicRank = BasicRankAchieve::where('user_id', $user->id)->where('rank_status', 1)->get();
        $getbasicRank = BasicRankAchieve::where('user_id', $user->id)
            ->where('rank_status', 1)
            ->first();
        $basicRankIncome = BasicRankIncome::where('rank_id', $getbasicRank->basic_rank_id ?? 1)
            ->where('is_redeemed', 0)
            ->sum('amount');
        $basicRankCount = BasicRankAchieve::where('basic_rank_id', $getbasicRank->basic_rank_id ?? 1)->where('rank_status', 1)->count();
        $basicIndividualShare = $basicRankCount > 0 ? floor($basicRankIncome / $basicRankCount) : 0;

        return view('Admin.admin_home', compact('user', 'sponsor', 'rankName', 'individualShare', 'announcement', 'basicRank', 'basicIndividualShare'));
    }
    // vidya
    public function view_profile()
    {
        $user = Auth::user();
        $userPackages = UserPackage::with('package')->where('user_id', $user->id)->get();
        $rankName = $user->rank->rank_name ?? 'No Rank';
        $basicRank = BasicRankAchieve::where('user_id', $user->id)->where('status', 1)->get();

        return view('Admin/edit_profile', compact('user', 'userPackages', 'rankName', 'basicRank'));
    }
    // vidya end
    public function change_password()
    {
        return view('Admin/change_password');
    }
    public function edit_bank_details()
    {
        return view('Admin/edit_bank_details');
    }
    public function achiever_details()
    {
        return view('Admin/achiever_details');
    }
    public function transfer_pin()
    {
        return view('Admin/transfer_pin');
    }
    public function pin_transfer_details()
    {
        return view('Admin/pin_transfer_details');
    }
    public function request_pin()
    {
        return view('Admin/request_pin');
    }
    public function pin_request_details()
    {
        return view('Admin/pin_request_details');
    }
    public function binary_income_details()
    {
        $user = auth()->user();
        if (in_array($user->role, ['admin', 'superadmin'])) {
            return $this->adminBinaryIncome(request());
        }
        return $this->binaryIncomeUser();
    }

    public function adminBinaryIncome(Request $request)
    {
        $fromDate = $request->get('from_date', \Carbon\Carbon::today()->toDateString());
        $toDate   = $request->get('to_date',   \Carbon\Carbon::today()->toDateString());
        $userId   = $request->get('user_id');

        $query = \App\Models\BinaryPairLog::with('user', 'package')
            ->whereBetween('calc_date', [$fromDate, $toDate])
            ->where('income', '>', 0)
            ->orderBy('calc_date', 'desc')
            ->orderBy('user_id');

        if ($userId) {
            $query->where('user_id', $userId);
        }

        $logs = $query->get();

        // Latest carry-forward per user per package
        $packageStatus = \DB::table('binary_pair_logs as bpl')
            ->join('packages as p', 'p.id', '=', 'bpl.package_id')
            ->join('users as u', 'u.id', '=', 'bpl.user_id')
            ->whereIn('bpl.id', function ($q) {
                $q->selectRaw('MAX(id)')
                  ->from('binary_pair_logs')
                  ->groupBy('user_id', 'package_id');
            })
            ->select(
                'u.id as user_id', 'u.name', 'u.connection',
                'p.name as package_name',
                'bpl.carry_out_left', 'bpl.carry_out_right',
                'bpl.capped_pairs', 'bpl.income as last_income',
                'bpl.created_at as last_run'
            )
            ->orderBy('u.connection')
            ->get();

        // Per-user wallet summary with income split
        $userSummary = \DB::table('binary_wallets as bw')
            ->join('users as u', 'u.id', '=', 'bw.user_id')
            ->leftJoinSub(
                \DB::table('binary_transactions')
                    ->select('user_id', \DB::raw('SUM(amount) as pair_total'))
                    ->where('type', 'binary_pair')
                    ->groupBy('user_id'),
                'pt', 'pt.user_id', '=', 'bw.user_id'
            )
            ->leftJoinSub(
                \DB::table('binary_transactions')
                    ->select('user_id', \DB::raw('SUM(amount) as sponsor_total'))
                    ->whereIn('type', ['binary_sponsor', 'prime_sponsor'])
                    ->groupBy('user_id'),
                'st', 'st.user_id', '=', 'bw.user_id'
            )
            ->select(
                'u.id', 'u.name', 'u.connection',
                'bw.balance', 'bw.carry_forward_left', 'bw.carry_forward_right',
                \DB::raw('COALESCE(pt.pair_total, 0) as pair_income'),
                \DB::raw('COALESCE(st.sponsor_total, 0) as sponsor_income')
            )
            ->where(function ($q) {
                $q->where('bw.total_earned', '>', 0)
                  ->orWhere('bw.carry_forward_left', '>', 0)
                  ->orWhere('bw.carry_forward_right', '>', 0);
            })
            ->orderBy('u.connection')
            ->get();

        return view('Admin/binary_income_details', compact('logs', 'fromDate', 'toDate', 'userId', 'packageStatus', 'userSummary'));
    }

    public function adminBinaryIncomePopup(Request $request)
    {
        $log = \App\Models\BinaryPairLog::with('package')
            ->where('user_id', $request->user_id)
            ->where('calc_date', $request->date)
            ->when($request->package_id, fn($q) => $q->where('package_id', $request->package_id))
            ->first();

        if (!$log) {
            return response()->json(['error' => 'No record found'], 404);
        }

        $wallet = \App\Models\BinaryWallet::where('user_id', $log->user_id)->first();

        // Determine window boundaries
        $prevLog = \App\Models\BinaryPairLog::where('user_id', $log->user_id)
            ->where('package_id', $log->package_id)
            ->where('id', '<', $log->id)
            ->orderByDesc('id')->first();
        $since = $prevLog ? $prevLog->created_at->toDateTimeString() : '2026-04-20 00:00:00';
        $until = $log->created_at->toDateTimeString();

        // Children IDs
        $leftChildId  = DB::table('users')->where('parent_id', $log->user_id)->where('position', 'left')->value('id');
        $rightChildId = DB::table('users')->where('parent_id', $log->user_id)->where('position', 'right')->value('id');

        // Prime packages that feed this package
        $primePackageIds = DB::table('packages')
            ->where('auto_upgrade_to_package_id', $log->package_id)
            ->where('status', 1)
            ->pluck('id')->toArray();
        $hasPrime = !empty($primePackageIds);

        $countActivations = function ($childId, $pkgIds) use ($since, $until) {
            if (!$childId || empty($pkgIds)) return 0;
            $result = DB::select("
                WITH RECURSIVE subtree AS (
                    SELECT id FROM users WHERE id = ?
                    UNION ALL SELECT u.id FROM users u INNER JOIN subtree s ON u.parent_id = s.id
                )
                SELECT COUNT(*) AS cnt FROM user_packages up
                WHERE up.user_id IN (SELECT id FROM subtree)
                  AND up.package_id IN (" . implode(',', $pkgIds) . ")
                  AND up.status = 1 AND up.created_at > ? AND up.created_at <= ?
            ", [$childId, $since, $until]);
            return (int) ($result[0]->cnt ?? 0);
        };

        $leftPremium  = $countActivations($leftChildId,  [$log->package_id]);
        $rightPremium = $countActivations($rightChildId, [$log->package_id]);
        $leftPrime    = $hasPrime ? $countActivations($leftChildId,  $primePackageIds) : 0;
        $rightPrime   = $hasPrime ? $countActivations($rightChildId, $primePackageIds) : 0;

        $primeCarryInLeft  = $prevLog ? (int)($prevLog->prime_carry_out_left  ?? 0) : 0;
        $primeCarryInRight = $prevLog ? (int)($prevLog->prime_carry_out_right ?? 0) : 0;

        return response()->json([
            'log'               => array_merge($log->toArray(), ['is_first_run' => !$prevLog]),
            'wallet'            => $wallet,
            'has_prime'         => $hasPrime,
            'left_premium'      => $leftPremium,
            'right_premium'     => $rightPremium,
            'left_prime'        => $leftPrime,
            'right_prime'       => $rightPrime,
            'prime_carry_in_left'  => $primeCarryInLeft,
            'prime_carry_in_right' => $primeCarryInRight,
        ]);
    }

    public function binaryLegVolumeDetail(Request $request)
    {
        $userId      = (int) $request->input('user_id');
        $side        = $request->input('side');          // 'left' or 'right'
        $packageCode = $request->input('package_code'); // 'basic_package' or 'premium_package'

        if (!$userId || !in_array($side, ['left', 'right']) || !$packageCode) {
            return response()->json(['error' => 'Invalid parameters'], 422);
        }

        $child = User::where('parent_id', $userId)->where('position', $side)->value('id');
        if (!$child) {
            return response()->json(['rows' => [], 'total_bv' => 0]);
        }

        $rows = \DB::select("
            WITH RECURSIVE subtree AS (
                SELECT id FROM users WHERE id = ?
                UNION ALL
                SELECT u.id FROM users u
                INNER JOIN subtree s ON u.parent_id = s.id
            )
            SELECT u.id, u.name, u.connection, p.name AS package_name,
                   p.binary_commission AS bv, up.created_at AS activated_at
            FROM user_packages up
            JOIN packages p ON p.id = up.package_id
            JOIN users u    ON u.id = up.user_id
            WHERE up.user_id IN (SELECT id FROM subtree)
              AND up.status = 1
              AND up.created_at >= ?
              AND p.package_code = ?
            ORDER BY up.created_at DESC
        ", [$child, $this->binaryStartDate, $packageCode]);

        $totalBv = array_sum(array_column($rows, 'bv'));

        return response()->json([
            'rows'     => $rows,
            'total_bv' => $totalBv,
        ]);
    }

    public function runBinaryIncome(Request $request)
    {
        $exitCode = \Illuminate\Support\Facades\Artisan::call('binary:calculate');
        $output   = \Illuminate\Support\Facades\Artisan::output();

        return redirect()->route('admin.binary_income')
            ->with('run_result', [
                'status' => $exitCode === 0 ? 'success' : 'error',
                'output' => trim($output),
            ]);
    }

    public function clearBinaryWallets(Request $request)
    {
        \DB::table('binary_pair_logs')->truncate();
        \DB::table('binary_transactions')->truncate();
        \DB::table('binary_wallets')->update([
            'balance'             => 0,
            'total_earned'        => 0,
            'total_withdrawn'     => 0,
            'carry_forward_left'  => 0,
            'carry_forward_right' => 0,
        ]);

        return redirect()->route('admin.binary_income')
            ->with('run_result', [
                'date'   => null,
                'status' => 'success',
                'output' => 'All binary wallets, transactions and pair logs cleared.',
            ]);
    }

    public function binaryIncomeUser()
    {
        $userId    = auth()->id();
        $yesterday = \Carbon\Carbon::yesterday()->toDateString();

        $pairLogs = \App\Models\BinaryPairLog::with('package')
            ->where('user_id', $userId)
            ->orderBy('calc_date', 'desc')
            ->get();

        $referralTransactions = \App\Models\BinaryTransaction::where('user_id', $userId)
            ->whereIn('type', ['binary_sponsor', 'prime_sponsor'])
            ->orderBy('created_at', 'desc')
            ->get();

        $wallet           = \App\Models\BinaryWallet::where('user_id', $userId)->first();
        $pairIncomeTotal  = $pairLogs->sum('income');
        $sponsorIncomeTotal = $referralTransactions->sum('amount');

        return view('Admin/binary_income_user', compact(
            'pairLogs', 'referralTransactions', 'wallet', 'yesterday',
            'pairIncomeTotal', 'sponsorIncomeTotal'
        ));
    }

    public function binaryIncomePairs(int $id)
    {
        $log = \App\Models\BinaryPairLog::with('package')->findOrFail($id);

        // Time window: previous log → this log
        $prevLog = \App\Models\BinaryPairLog::where('user_id', $log->user_id)
            ->where('package_id', $log->package_id)
            ->where('id', '<', $log->id)
            ->orderByDesc('id')
            ->first();

        $since = $prevLog
            ? $prevLog->created_at->toDateTimeString()
            : '2026-04-20 00:00:00';
        $until = $log->created_at->toDateTimeString();

        $leftChildId  = DB::table('users')->where('parent_id', $log->user_id)->where('position', 'left')->value('id');
        $rightChildId = DB::table('users')->where('parent_id', $log->user_id)->where('position', 'right')->value('id');

        $leftUsers  = $leftChildId  ? $this->legActivationUsers($leftChildId,  $log->package_id, $since, $until) : [];
        $rightUsers = $rightChildId ? $this->legActivationUsers($rightChildId, $log->package_id, $since, $until) : [];

        // Carry-in users were activated in the PREVIOUS window — fetch and prepend them
        // so the popup shows who is behind the carry-in count (not a blank cell).
        if ($log->carry_in_left > 0 || $log->carry_in_right > 0) {
            $prevPrevLog = $prevLog
                ? \App\Models\BinaryPairLog::where('user_id', $log->user_id)
                    ->where('package_id', $log->package_id)
                    ->where('id', '<', $prevLog->id)
                    ->orderByDesc('id')
                    ->first()
                : null;

            $prevSince = $prevPrevLog
                ? $prevPrevLog->created_at->toDateTimeString()
                : '2026-04-20 00:00:00';

            if ($log->carry_in_left > 0 && $leftChildId) {
                $prevLeft = $this->legActivationUsers($leftChildId, $log->package_id, $prevSince, $since);
                // The carry-in users are the tail of the previous window (excess beyond matched count)
                $ciLeft = array_slice($prevLeft, -(int) $log->carry_in_left);
                foreach ($ciLeft as $u) { $u->carry_in = true; }
                $leftUsers = array_merge($ciLeft, $leftUsers);
            }
            if ($log->carry_in_right > 0 && $rightChildId) {
                $prevRight = $this->legActivationUsers($rightChildId, $log->package_id, $prevSince, $since);
                $ciRight = array_slice($prevRight, -(int) $log->carry_in_right);
                foreach ($ciRight as $u) { $u->carry_in = true; }
                $rightUsers = array_merge($ciRight, $rightUsers);
            }
        }

        $capped       = (int) $log->capped_pairs;
        $isFirstRun   = !$prevLog;
        $leftPrimary  = $isFirstRun && ($log->total_left >= $log->total_right);
        $rightPrimary = $isFirstRun && ($log->total_right > $log->total_left);

        // Primary side (first run): index 0 = matched, index 1 = first_sale, 2..capped = matched
        // Secondary / subsequent runs: index < capped = matched, rest = carry or flushed
        foreach ($leftUsers as $i => $u) {
            if ($leftPrimary) {
                if ($i === 1) $u->status = 'first_sale';
                elseif ($i <= $capped) $u->status = 'matched';
                else $u->status = $log->carry_out_left > 0 ? 'carry' : 'flushed';
            } else {
                $u->status = $i < $capped ? 'matched' : ($log->carry_out_left > 0 ? 'carry' : 'flushed');
            }
        }
        foreach ($rightUsers as $i => $u) {
            if ($rightPrimary) {
                if ($i === 1) $u->status = 'first_sale';
                elseif ($i <= $capped) $u->status = 'matched';
                else $u->status = $log->carry_out_right > 0 ? 'carry' : 'flushed';
            } else {
                $u->status = $i < $capped ? 'matched' : ($log->carry_out_right > 0 ? 'carry' : 'flushed');
            }
        }

        // Prime packages that feed into this premium
        $primePackageIds = DB::table('packages')
            ->where('auto_upgrade_to_package_id', $log->package_id)
            ->where('status', 1)
            ->pluck('id')
            ->toArray();

        $leftPrimeUsers  = [];
        $rightPrimeUsers = [];

        if (!empty($primePackageIds)) {
            if ($leftChildId) {
                $leftPrimeUsers  = $this->legActivationUsersByIds($leftChildId,  $primePackageIds, $since, $until);
            }
            if ($rightChildId) {
                $rightPrimeUsers = $this->legActivationUsersByIds($rightChildId, $primePackageIds, $since, $until);
            }
        }

        return response()->json([
            'log'        => [
                'date'                 => \Carbon\Carbon::parse($log->calc_date)->format('d M Y'),
                'package'              => $log->package->name ?? $log->package_type,
                'package_code'         => $log->package_type,
                'capped'               => $capped,
                'income'               => number_format($log->income, 2),
                'carry_in_left'        => $log->carry_in_left,
                'carry_in_right'       => $log->carry_in_right,
                'total_left'           => $log->total_left,
                'total_right'          => $log->total_right,
                'carry_out_left'        => $log->carry_out_left,
                'carry_out_right'       => $log->carry_out_right,
                'prime_carry_out_left'  => $log->prime_carry_out_left  ?? 0,
                'prime_carry_out_right' => $log->prime_carry_out_right ?? 0,
                'is_first_run'         => !$prevLog,
            ],
            'left'            => $leftUsers,
            'right'           => $rightUsers,
            'left_prime'      => $leftPrimeUsers,
            'right_prime'     => $rightPrimeUsers,
            'has_prime'       => !empty($primePackageIds),
        ]);
    }

    private function legActivationUsers(int $childId, int $packageId, string $since, string $until): array
    {
        return DB::select("
            WITH RECURSIVE subtree AS (
                SELECT id FROM users WHERE id = ?
                UNION ALL
                SELECT u.id FROM users u
                INNER JOIN subtree s ON u.parent_id = s.id
            )
            SELECT u.id, u.name, u.connection, up.created_at AS activated_at
            FROM user_packages up
            JOIN users u ON u.id = up.user_id
            WHERE up.user_id IN (SELECT id FROM subtree)
              AND up.package_id = ?
              AND up.status = 1
              AND up.created_at > ?
              AND up.created_at <= ?
            ORDER BY up.created_at
        ", [$childId, $packageId, $since, $until]);
    }

    private function legActivationUsersByIds(int $childId, array $packageIds, string $since, string $until): array
    {
        $placeholders = implode(',', array_fill(0, count($packageIds), '?'));
        return DB::select("
            WITH RECURSIVE subtree AS (
                SELECT id FROM users WHERE id = ?
                UNION ALL
                SELECT u.id FROM users u
                INNER JOIN subtree s ON u.parent_id = s.id
            )
            SELECT u.id, u.name, u.connection, up.created_at AS activated_at,
                   p.name AS package_name
            FROM user_packages up
            JOIN users u ON u.id = up.user_id
            JOIN packages p ON p.id = up.package_id
            WHERE up.user_id IN (SELECT id FROM subtree)
              AND up.package_id IN ({$placeholders})
              AND up.status = 1
              AND up.created_at > ?
              AND up.created_at <= ?
            ORDER BY up.created_at
        ", array_merge([$childId], $packageIds, [$since, $until]));
    }

    public function directy_income_details()
    {
        return view('Admin/directy_income_details');
    }
    public function royalty_income_details()
    {
        return view('Admin/royalty_income_details');
    }

    //vidya
    public function package()
    {
        $packages = Package::all();
        return view('Admin/packages', compact('packages'));
    }
    public function add_package(Request $request)
    {
        $validated = $request->validate([
            'packageName'        => ['required', 'string', 'max:255'],
            'packageAmount'      => ['required', 'numeric', 'regex:/^\d+(\.\d{1,2})?$/'],
            'binary_commission'  => ['required', 'numeric', 'min:0'],
            'sponsor_commission' => ['required', 'numeric', 'min:0'],
            'daily_pair_cap'     => ['required', 'integer', 'min:0'],
            'status'             => 'required|boolean',
            'packageCategory'    => 'required',
            'packageCat'         => 'required',
        ]);
        Package::create([
            'name'                        => $validated['packageName'],
            'amount'                      => $validated['packageAmount'],
            'binary_commission'           => $validated['binary_commission'],
            'sponsor_commission'          => $validated['sponsor_commission'],
            'sponsor_eligible_package_ids'  => array_filter(array_map('intval', $request->input('sponsor_eligible_package_ids', []))),
            'auto_upgrade_count'            => $request->input('auto_upgrade_count') ?: null,
            'auto_upgrade_to_package_id'    => $request->input('auto_upgrade_to_package_id') ?: null,
            'daily_pair_cap'                => $validated['daily_pair_cap'],
            'package_code'                  => $validated['packageCategory'],
            'package_cat'                   => $validated['packageCat'],
            'status'                        => $validated['status'],
            'color'                         => $request->input('color') ?: '#6c757d',
        ]);
        return redirect()->route('package')->with('success', 'Added Package Successfully.');
    }

    public function edit_package(Request $request)
    {
        $validated = $request->validate([
            'name'               => 'required|string|max:255',
            'amount'             => 'required|numeric',
            'binary_commission'  => 'required|numeric|min:0',
            'sponsor_commission' => 'required|numeric|min:0',
            'daily_pair_cap'     => 'required|integer|min:0',
            'status'             => 'required|boolean',
        ]);
        $package = Package::findOrFail($request->input('id'));
        $package->update([
            'name'                        => $validated['name'],
            'amount'                      => $validated['amount'],
            'binary_commission'           => $validated['binary_commission'],
            'sponsor_commission'          => $validated['sponsor_commission'],
            'sponsor_eligible_package_ids'  => array_filter(array_map('intval', $request->input('sponsor_eligible_package_ids', []))),
            'auto_upgrade_count'            => $request->input('auto_upgrade_count') ?: null,
            'auto_upgrade_to_package_id'    => $request->input('auto_upgrade_to_package_id') ?: null,
            'daily_pair_cap'                => $validated['daily_pair_cap'],
            'status'                        => $validated['status'],
            'color'                         => $request->input('color') ?: '#6c757d',
        ]);
        return redirect()->route('package')->with('successchange', 'Package updated successfully.');
    }
    public function delete_package(Request $request)
    {
        $packageId = $request->input('id');
        $package = Package::findOrFail($packageId);
        $package->delete();
        return redirect()->route('package')->with('success', 'Package deleted successfully.');
    }

    public function edit_profile(Request $request)
    {
        $user = auth()->user();

        $validator = Validator::make(
            $request->all(),
            [
                'user_name' => ['required', 'string', 'max:255'],
                'mobile' => ['required', 'string', 'regex:/^\d{10}$/'],
                'address' => 'required|string|max:255',
                'email' => ['required', 'string', 'email', 'max:255', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
                'old_password' => 'nullable|string|min:4',
                'password' => [
                    'nullable',
                    'string',
                    'min:8',
                    'confirmed',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\W).+$/'
                ],
                'profile_image' => 'nullable|image|max:2048',
            ],
            [
                'password.required' => 'The password field is required.',
                'password.min' => 'The password must be at least 8 characters.',
                'password.confirmed' => 'The password confirmation does not match.',
                'password.regex' => 'The password must include at least one uppercase letter, one lowercase letter, and one special character.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user->name = $request->user_name;
        $user->phone_no = $request->mobile;
        $user->address = $request->address;
        $user->email = $request->email;

        // Handle profile image upload and conversion
        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            if (in_array($file->getClientOriginalExtension(), ['jpeg', 'jpg', 'png'])) {
                $imageName = time() . '-' . pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '.webp';
                $image = imagecreatefromstring(file_get_contents($file->getRealPath()));
                if ($image !== false) {
                    imagewebp($image, public_path('assets/Profile/' . $imageName), 80);
                    imagedestroy($image);

                    // Delete the old image if necessary
                    if ($user->user_image && file_exists(public_path($user->user_image))) {
                        unlink(public_path($user->user_image));
                    }

                    $user->user_image = 'assets/Profile/' . $imageName;
                }
            }
        }

        // Update password if provided
        if ($request->filled('old_password') && $request->filled('password')) {
            if (!Hash::check($request->old_password, $user->password)) {
                return redirect()->back()->with('error', 'The old password does not match our records.');
            }
            $user->password = Hash::make($request->password);
        }

        $user->save();
        return redirect()->route('view_profile')->with('success', 'Profile updated successfully.');
    }


    public function support_view_admin(Request $request, $userId = null)
    {
        $loggedUser = auth()->user();
        $users = User::whereIn('id', function ($query) use ($loggedUser) {
            $query->select('msg_from_id')
                ->from('messages')
                ->where('msg_to_id', $loggedUser->id);
        })
            ->with(['latestMessage' => function ($query) use ($loggedUser) {
                $query->where(function ($subQuery) use ($loggedUser) {
                    $subQuery->where('msg_from_id', $loggedUser->id)
                        ->orWhere('msg_to_id', $loggedUser->id);
                })->latest('created_at');
            }])
            ->get()
            ->sortByDesc(function ($user) {
                return $user->latestMessage?->created_at;
            });

        $unreadMessages = Message::where('msg_is_read', 1)
            ->where('msg_to_id', $loggedUser->id)
            ->get();

        $unreadMessageCounts = $unreadMessages->groupBy('msg_from_id')->map(function ($messages) {
            return $messages->count();
        });

        $messages = [];
        $selectedUser = null;

        if ($userId) {
            $adminImage = $loggedUser->user_image;
            $messages = Message::where(function ($query) use ($loggedUser, $userId) {
                $query->where('msg_from_id', $loggedUser->id)
                    ->where('msg_to_id', $userId);
            })
                ->orWhere(function ($query) use ($loggedUser, $userId) {
                    $query->where('msg_from_id', $userId)
                        ->where('msg_to_id', $loggedUser->id);
                })
                ->orderBy('created_at', 'asc')
                ->get();

            $selectedUser = User::find($userId);
        } else {
            $adminImage = $loggedUser->user_image;
            $selectedUser = $users->first();
            if ($selectedUser) {
                $messages = Message::where(function ($query) use ($loggedUser, $selectedUser) {
                    $query->where('msg_from_id', $loggedUser->id)
                        ->where('msg_to_id', $selectedUser->id);
                })
                    ->orWhere(function ($query) use ($loggedUser, $selectedUser) {
                        $query->where('msg_from_id', $selectedUser->id)
                            ->where('msg_to_id', $loggedUser->id);
                    })
                    ->orderBy('created_at', 'asc')
                    ->get();
            }
        }

        return view('Admin/support_view_admin', compact('users', 'messages', 'selectedUser', 'loggedUser', 'unreadMessageCounts', 'adminImage'));
    }



    public function send_message_admin(Request $request, $userId)
    {
        $request->validate([
            'message' => 'required|string|max:500',
        ]);
        $loggedUser = auth()->id();
        Message::where('msg_from_id', $userId)
            ->where('msg_is_read', 1)
            ->update(['msg_is_read' => 0]);

        Message::create([
            'msg_from_id' => $loggedUser,
            'msg_to_id' => $userId,
            'message' => $request->message,
            'msg_is_read' => false,
        ]);
        return redirect()->route('support_view_admin', ['userId' => $userId])
            ->with('success', 'Message sent successfully.');
    }

    public function support_view()
    {
        $loggedUserId = auth()->id();
        $user = Auth::user();
        $superadmin = User::where('role', 'superadmin')->first();
        $user_image = $user->user_image;
        $superadmin_image = $superadmin->user_image;
        $images = [
            'user' => $user_image,
            'superadmin' => $superadmin_image,
        ];
        $messages = Message::where(function ($query) use ($loggedUserId) {
            $query->where('msg_from_id', $loggedUserId)
                ->orWhere('msg_to_id', $loggedUserId);
        })
            ->orderBy('created_at', 'asc')
            ->get();
        return view('Admin/support_view', compact('messages', 'images', 'loggedUserId'));
    }

    public function send_message(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:5000',
        ]);
        $toId = $this->getRecipientId();
        Message::create([
            'msg_from_id' => Auth::id(),
            'msg_to_id' => $toId,
            'message' => $validated['message'],
            'msg_is_read' => 1,
            'msg_edited'  => false,
            'msg_status' => 'active',
        ]);
        return redirect()->route('support_view')->with('success', 'Message Sended successfully.');
    }

    private function getRecipientId()
    {
        $superadmin = User::where('role', 'superadmin')->first();
        $toId = $superadmin->id;
        return $toId;
    }

    public function add_wallet()
    {
        //#### The join_amount field in the Users table acts as a pin wallet.---------
        $user = Auth::user();
        if ($user->role === 'superadmin') {
            $bankTransactionDetails = BankTransactionDetail::latest()->get();
            $userIds = BankTransactionDetail::pluck('user_id')->toArray();
            $totalIncomes = User::whereIn('id', $userIds)
                ->get(['id', 'join_amount'])
                ->mapWithKeys(function ($user) {
                    return [$user->id => $user->join_amount];
                })
                ->toArray();
            $reciepts = [
                'bankTransactionDetails' => $bankTransactionDetails,
                'totalIncomes' => $totalIncomes,
            ];
        } else {
            $bankTransactionDetails = BankTransactionDetail::where('user_id', $user->id)->get();
            $userIds = BankTransactionDetail::pluck('user_id')->toArray();
            $totalIncomes = User::whereIn('id', $userIds)
                ->get(['id', 'join_amount'])
                ->mapWithKeys(function ($user) {
                    return [$user->id => $user->join_amount];
                })
                ->toArray();
            $reciepts = [
                'bankTransactionDetails' => $bankTransactionDetails,
                'totalIncomes' => $totalIncomes,
            ];
        }
        return view('Admin/add_wallet', compact(('reciepts')));
    }
    public function get_user_name(Request $request)
    {
        $id = $request->input('userId');
        $user = User::where('connection', $id)->first();
        if ($user) {
            return response()->json(['name' => $user->name], 200);
        } else {
            return response()->json(['error' => 'User not founded'], 404);
        }
    }
    public function update_wallet(Request $request)
    {
        $validated = $request->validate([
            'userId' => 'required|exists:users,connection',
            'accName' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1',
            'dOfSend' => 'required|date',
            'transaction_id' => 'required|string',
            'receipt_image' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ]);
        $imagePath = null;
        $id = $request->input('userId');
        $user = User::where('connection', $id)->first();
        $userId = $user->id;
        if ($request->hasFile('receipt_image')) {
            $file = $request->file('receipt_image');
            if (in_array($file->getClientOriginalExtension(), ['jpeg', 'jpg', 'png'])) {
                $imageName = time() . '-' . pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '.webp';
                $image = imagecreatefromstring(file_get_contents($file->getRealPath()));
                if ($image !== false) {
                    imagewebp($image, public_path('assets/Bank_receipts/' . $imageName), 80);
                    imagedestroy($image);
                    $imagePath = 'assets/Bank_receipts/' . $imageName;
                }
            }
        }
        BankTransactionDetail::create([
            'user_id' => $userId,
            'acc_holder_name' => $request->accName,
            'amount' => $request->amount,
            'date_of_send' => $request->dOfSend,
            'transaction_id' => $request->transaction_id,
            'image' => $imagePath,
            'status' => 'pending',
        ]);
        return redirect()->route('add_wallet')->with('success', 'Message Sended successfully.');
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'reciept_id' => 'required|exists:bank_transaction_details,id',
            'status' => 'required|in:pending,completed,failed',
        ]);
        $reciept = BankTransactionDetail::find($request->reciept_id);
        $reciept->status = $request->status;
        if ($request->status === 'completed') {
            $user = User::find($reciept->user_id);

            // The join_amount field in the Users table acts as a pin wallet.
            if ($user) {
                $user->join_amount += $reciept->amount;
                $user->save();
            }
        }
        $reciept->save();
        return redirect()->back()->with('success', 'Reciept Status Updated.');
    }
    //------------------end --------------------------------------------

    // ----------------------------Sunflower Tree View----------------------------------



    public function sunflower(Request $request, $id = null)
    {
        $loggedUser = auth()->user();
        $superadmin = $loggedUser->role === 'superadmin' ? 996 : null;

        $inputId = $request->input('user_code');
        $errorMessage = null;

        if ($inputId) {
            $user = User::where('connection', $inputId)->first();
            $inputId = $user ? $user->id : null;
            $errorMessage = $user ? null : 'User not found.';
        }

        $rootUserId = $id ?? $inputId ?? $superadmin ?? auth()->id();

        $user = User::find($rootUserId);
        $downlines = $user->downlines;

        $rootUser = User::with(['leftChild', 'rightChild'])
            ->find($rootUserId);

        $sponsor = $rootUser->sponsor;

        $packageid = 13;

        $packages = Package::where('status', 1)->get();

        $available_packages = [
            'basic' => Package::where(['package_code' => 'basic_package'])->get()->toArray(),
            'premium' => Package::where(['package_code' => 'premium_package'])->get()->toArray(),
        ];

        $levelpremium = DB::table('sponsor_levels')
            ->where('sponsor_id', $rootUser->id)
            ->where('is_redeemed', 0)
            ->where('package_category', 'premium_package')
            ->sum('amount');

        $levelbasic = DB::table('sponsor_levels')
            ->where('sponsor_id', $rootUser->id)
            ->where('is_redeemed', 0)
            ->where('package_category', 'basic_package')
            ->sum('amount');

        $royaltyIncome = DB::table('royalty_user_wallets')
            ->where('user_id', $rootUser->id)
            ->where('status', 1)
            ->sum('amount');

        $rankincome = RankIncome::where('user_id', $rootUser->id)
            ->where('status', 1)
            ->sum('amount');

        $referrelpremium = ReferralIncome::where('sponsor_id', $rootUser->id)
            ->where('status', 1)
            ->where('package_category', 'premium_package')
            ->sum('income');

        $referrelbasic = ReferralIncome::where('sponsor_id', $rootUser->id)
            ->where('status', 1)
            ->where('package_category', 'basic_package')
            ->sum('income');

        $bonus = BonusWallet::where('user_id', $rootUser->id)
            ->where('is_redeemed', 0)
            ->sum('amount');


        $PrivilegeIncome = PrivilegeUserWallet::where('user_id', $rootUser->id)->where('status', 1)->sum('amount');
        $BoardIncome = BoardUserWallet::where('user_id', $rootUser->id)->where('status', 1)->sum('amount');
        $ExecutiveIncome = ExecutiveUserWallet::where('user_id', $rootUser->id)->where('status', 1)->sum('amount');
        $IncentiveIncome = IncentiveUserWallet::where('user_id', $rootUser->id)->where('status', 1)->sum('amount');

        $basicRankincome = BasicUserRankIncome::where('user_id', $rootUser->id)->where('status', 1)->sum('amount');

        $totals = RepurchaseWallet::where('user_id', $rootUser->id)
            ->where('is_redeemed', 0)
            ->whereIn('amount_type', [
                'Repurchase Income',
                'Self Purchase Income',
                'Franchisee Share Income'
            ])
            ->selectRaw('amount_type, SUM(amount) as total')
            ->groupBy('amount_type')
            ->pluck('total', 'amount_type');

        $repurchaseTotal = $totals['Repurchase Income'] ?? 0;
        $selfpurchaseTotal = $totals['Self Purchase Income'] ?? 0;
        $franchiseeTotal = $totals['Franchisee Share Income'] ?? 0;


        $downlineSummary = $this->calculateDownlineSummary($rootUser);

        return view('Admin/sunflower_tree', compact(
            'downlines',
            'user',
            'levelpremium',
            'rankincome',
            'referrelpremium',
            'sponsor',
            'packages',
            'available_packages',
            'loggedUser',
            'royaltyIncome',
            'levelbasic',
            'referrelbasic',
            'bonus',
            'PrivilegeIncome',
            'BoardIncome',
            'ExecutiveIncome',
            'IncentiveIncome',
            'basicRankincome',
            'repurchaseTotal',
            'selfpurchaseTotal',
            'franchiseeTotal'
        ));
    }

    private function calculateDownlineSummary($user)
    {
        // Fetch referral incomes for the user's downlines
        $referralIncomes = DB::table('referral_incomes')
            ->join('users', 'referral_incomes.user_id', '=', 'users.id')
            ->join('packages', 'referral_incomes.package_id', '=', 'packages.id')
            ->where('referral_incomes.sponsor_id', $user->id)
            ->select('users.name as user_name', 'packages.name as package_name', 'referral_incomes.income')
            ->get();

        $basicCount = 0;
        $basicTotal = 0;
        $premiumCount = 0;
        $premiumTotal = 0;
        $basicUsers = [];
        $premiumUsers = [];

        foreach ($referralIncomes as $income) {
            if (stripos($income->package_name, 'basic') !== false) {
                $basicCount++;
                $basicTotal += $income->income;
                $basicUsers[] = $income->user_name;
            } elseif (stripos($income->package_name, 'premium') !== false) {
                $premiumCount++;
                $premiumTotal += $income->income;
                $premiumUsers[] = $income->user_name;
            }
        }

        return [
            'basic' => [
                'count' => $basicCount,
                'total' => $basicTotal,
                'users' => $basicUsers,
            ],
            'premium' => [
                'count' => $premiumCount,
                'total' => $premiumTotal,
                'users' => $premiumUsers,
            ],
            'subtotal' => $basicTotal + $premiumTotal,
        ];
    }


    // ----------------------------USER START----------------------------------

    public function store_user(Request $request)
    {
        $name = $request->input('name');
        $cleanedName = preg_replace('/\s+/', '', $name);
        $firstTwoLetters = strtoupper(substr($cleanedName, 0, 2));
        $lastUser = User::latest('id')->first();
        $uniqueId = $lastUser->id + 1;
        $user_code = 'V' . $firstTwoLetters . $uniqueId;

        $request->merge(['connection' => $user_code]);


        $validator = Validator::make(
            $request->all(),
            [
                'sponsor_id' => 'required|exists:users,connection',
                'name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
                'email' => 'required|string|email|max:255',
                'phone_no' => 'required|string|max:10',
                'pan_card_no' => 'nullable|regex:/^[A-Z]{5}[0-9]{4}[A-Z]$/',
                'address' => 'required|string',
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'confirmed',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\W).+$/'
                ],
                'connection' => 'required|string|max:255|unique:users,connection',
                'pincode' => 'required|regex:/^[1-9][0-9]{5}$/',
            ],
            [
                'password.required' => 'The password field is required.',
                'password.min' => 'The password must be at least 8 characters.',
                'password.confirmed' => 'The password confirmation does not match.',
                'password.regex' => 'The password must include at least one uppercase letter, one lowercase letter, and one special character.',
                'pincode.required' => 'PIN code is required.',
                'pincode.regex' => 'Enter a valid 6-digit PIN code.Starting with 1',
            ]
        );

        if ($validator->fails()) {
            return json_encode(['status' => 'validation', 'errors' => $validator->errors()]);
        }

        $sponsor = DB::table('users')->where('connection', $request->sponsor_id)->first();
        $currenrsponsor = $sponsor->id;

        // Use binary tree placement if provided, otherwise fall back to legacy default
        if ($request->filled('parent_id') && is_numeric($request->parent_id)) {
            $binaryParent = User::find((int) $request->parent_id);
            $parent_id    = $binaryParent ? $binaryParent->id : 996;
            $position     = in_array($request->position, ['left', 'right']) ? $request->position : 'left';
            $level        = ($binaryParent->level ?? 0) + 1;
        } else {
            $parent_id = 996;
            $position  = 'left';
            $level     = 1;
        }

        // No PAN → Child ID (mother_id=0), cannot earn income
        if (!$request->filled('pan_card_no')) {
            $motherid = 0;
        } else {
            $panCardExists = DB::table('users')->where('pan_card_no', $request->pan_card_no)->exists();

            if ($panCardExists) {

                $existingUser = DB::table('users')
                    ->where('pan_card_no', $request->pan_card_no)
                    ->first();

                if (strtolower($existingUser->name) !== strtolower($request->name)) {
                    return json_encode([
                        'status' => 'error',
                        'message' => 'The name does not match the existing record for this PAN card.',
                        'correct_name' => $existingUser->name
                    ]);
                }

                // New accounts with same PAN must be placed within the Mother ID's binary subtree
                $motherUser = DB::table('users')
                    ->where('pan_card_no', $request->pan_card_no)
                    ->where('mother_id', 1)
                    ->first();

                if ($motherUser) {
                    $inSubtree = DB::select("
                        WITH RECURSIVE subtree AS (
                            SELECT id FROM users WHERE id = ?
                            UNION ALL
                            SELECT u.id FROM users u
                            INNER JOIN subtree s ON u.parent_id = s.id
                        )
                        SELECT COUNT(*) as cnt FROM subtree WHERE id = ?
                    ", [$motherUser->id, $parent_id]);

                    if (($inSubtree[0]->cnt ?? 0) == 0) {
                        return json_encode([
                            'status' => 'error',
                            'message' => 'This user must be placed within the Mother ID\'s binary tree.',
                        ]);
                    }
                }

                $motherId1Exists = DB::table('users')
                    ->where('pan_card_no', $request->pan_card_no)
                    ->where('mother_id', 1)
                    ->exists();

                $motherId2Exists = DB::table('users')
                    ->where('pan_card_no', $request->pan_card_no)
                    ->where('mother_id', 2)
                    ->exists();

                $motherId3Exists = DB::table('users')
                    ->where('pan_card_no', $request->pan_card_no)
                    ->where('mother_id', 3)
                    ->exists();

                if ($motherId1Exists && $motherId2Exists && $motherId3Exists) {
                    $motherid = 0;
                } elseif ($motherId1Exists && $motherId2Exists) {
                    $motherid = 3;
                } elseif ($motherId1Exists) {
                    $motherid = 2;
                } else {
                    $motherid = 1;
                }
            } else {
                $motherid = 1;
            }
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_no' => $request->phone_no,
            'pan_card_no' => $request->pan_card_no,
            'pincode' => $request->pincode,
            'address' => $request->address,
            'password' => Hash::make($request->password),
            'sponsor_id' => $currenrsponsor,
            'rank_id' => 1,
            'parent_id' => $parent_id,
            'position' => $position,
            'level' => $level,
            'connection' => $request->connection,
            'total_income' => 0,
            'role' => 'user',
            'mother_id' => $motherid,
            'is_pair_matched' => '0',
        ]);

        if ($motherid = 1) {

            // Register the same user as a PrestaShop customer (non-blocking)
            $this->registerUserOnPrestaShop($request->name, $request->email, $request->password, $request->connection);
        }

        return json_encode([
            'status' => 'success',
            'message' => 'User added successfully',
            'connection' => $request->connection,
            'password' => $request->password
        ]);
    }


    public function user_register(Request $request)
    {
        $name = $request->input('name');
        $cleanedName = preg_replace('/\s+/', '', $name);
        $firstTwoLetters = strtoupper(substr($cleanedName, 0, 2));
        $lastUser = User::latest('id')->first();
        $uniqueId = $lastUser->id + 1;
        $user_code = 'V' . $firstTwoLetters . $uniqueId;

        $request->merge(['user_code' => $user_code]);
        // Validation rules
        $validated = $request->validate(
            [
                'sponsor_id' => 'required|exists:users,connection',
                'name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
                'email' => 'required|email',
                'phone_no' => 'required|regex:/^[0-9]{10}$/',
                'pan_card_no' => 'nullable|regex:/^[A-Z]{5}[0-9]{4}[A-Z]$/',
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'confirmed',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\W).+$/'
                ],
                'address' => 'required|string|max:500',
                'package_id' => 'nullable|exists:packages,id',
                'pin_id' => 'nullable|string|max:50',
                'pin_password' => 'nullable|string|max:50',
                'user_code' => 'required|string|max:255|unique:users,connection',
                'pincode' => 'required|regex:/^[1-9][0-9]{5}$/',
            ],
            [
                'pincode.required' => 'PIN code is required.',
                'pincode.regex' => 'Enter a valid 6-digit PIN code.Starting with 1',
                'password.required' => 'The password field is required.',
                'password.min' => 'The password must be at least 8 characters.',
                'password.confirmed' => 'The password confirmation does not match.',
                'password.regex' => 'The password must include at least one uppercase letter, one lowercase letter, and one special character.',
            ]
        );


        $sponsor = DB::table('users')->where('connection', $request->sponsor_id)->first();
        $sponsorHasPackages = User::find($sponsor->id)->userPackages()->exists();

        if (!$sponsorHasPackages) {
            return redirect()->route('/')->with('error', "The sponsor does not have any active packages.");
        }

        if ($request->package_id) {

            if ($request->pin_id) {
                $pin = PinGeneration::where('unique_id', $request->pin_id)->first();

                if ($pin->package_id != $request->package_id) {

                    return redirect()->back()->with('error', 'Your package and pin is not matching.');
                }
                if (!$pin) {
                    return redirect()->back()->with('error', 'Invalid pin.');
                }

                if ($pin->password !== $request->pin_password) {
                    return redirect()->back()->with('error', 'Incorrect password.');
                }

                if ($pin->used != '0') {
                    return redirect()->back()->with('error', 'This pin has already been redeemed.');
                }

                $pin->used = 2;
                $pin->password = '';
                $pin->status = 'Redeemed';
                $pin->updated_at = now();
                $pin->save();
            } else {
                return redirect()->back()->with('error', 'Invalid pin.');
            }
        }

        // $parent = User::where('connection', $request->parent_id)->first();
        $sponsor = User::where('connection', $request->sponsor_id)->first();
        $currenrsponsor = $sponsor->id;
        $level = 1;

        // No PAN → Child ID (mother_id=0), cannot earn income
        if (!$request->filled('pan_card_no')) {
            $motherid = 0;
        } else {
            $panCardExists = DB::table('users')->where('pan_card_no', $request->pan_card_no)->exists();

            if ($panCardExists) {

                $existingUser = DB::table('users')
                    ->where('pan_card_no', $request->pan_card_no)
                    ->first();

                if (strtolower($existingUser->name) !== strtolower($request->name)) {
                    return redirect()->back()->with('error', "The name does not match the existing record for this PAN card.");
                }

                // New accounts with same PAN must be placed within the Mother ID's binary subtree
                $motherUser = DB::table('users')
                    ->where('pan_card_no', $request->pan_card_no)
                    ->where('mother_id', 1)
                    ->first();

                if ($motherUser) {
                    // user_register always places at parent_id=996, so subtree check against 996
                    $checkParent = 996;
                    $inSubtree = DB::select("
                        WITH RECURSIVE subtree AS (
                            SELECT id FROM users WHERE id = ?
                            UNION ALL
                            SELECT u.id FROM users u
                            INNER JOIN subtree s ON u.parent_id = s.id
                        )
                        SELECT COUNT(*) as cnt FROM subtree WHERE id = ?
                    ", [$motherUser->id, $checkParent]);

                    if (($inSubtree[0]->cnt ?? 0) == 0) {
                        return redirect()->back()->with('error', 'This user must be placed within the Mother ID\'s binary tree.');
                    }
                }

                $motherId1Exists = DB::table('users')
                    ->where('pan_card_no', $request->pan_card_no)
                    ->where('mother_id', 1)
                    ->exists();

                $motherId2Exists = DB::table('users')
                    ->where('pan_card_no', $request->pan_card_no)
                    ->where('mother_id', 2)
                    ->exists();

                $motherId3Exists = DB::table('users')
                    ->where('pan_card_no', $request->pan_card_no)
                    ->where('mother_id', 3)
                    ->exists();

                if ($motherId1Exists && $motherId2Exists && $motherId3Exists) {
                    $motherid = 0;
                } elseif ($motherId1Exists && $motherId2Exists) {
                    $motherid = 3;
                } elseif ($motherId1Exists) {
                    $motherid = 2;
                } else {
                    $motherid = 1;
                }
            } else {
                $motherid = 1;
            }
        }


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_no' => $request->phone_no,
            'pan_card_no' => $request->pan_card_no,
            'pincode' => $request->pincode,
            'address' => $request->address,
            'password' => Hash::make($request->password),
            'rank_id' => 1,
            'sponsor_id' => $currenrsponsor,
            'parent_id' => 996,
            'position' => 'left',
            'level' => $level,
            'connection' => $request->user_code,
            'total_income' => 0,
            'role' => 'user',
            'mother_id' => $motherid,
            'is_pair_matched' => '0',
        ]);

        if ($request->package_id) {
            UserPackage::create([
                'user_id' => $user->id,
                'package_id' => $request->package_id,
                'pin_id' => $pin->id,
                'add_by' => $user->id,
                'status' => 1,
            ]);

            $package = Package::find($request->package_id);

            if ($package->package_code === 'premium_package') {
                $this->companyRankIncome($user->id, $request->package_id);
            }


            $this->updateSponsorLevels($user->id, $request->package_id);
            $referralIncome = $this->addReferralIncome($user->id, $request->package_id);
            $this->royaltyIncomeAdd($user->id, $request->package_id);
            $this->privilegeIncomeAdd($user->id, $request->package_id);
            $this->boardIncomeAdd($user->id, $request->package_id);
            $this->executiveIncomeAdd($user->id, $request->package_id);
            $this->creditBinarySponsorIncome($user->id, $request->package_id);
            $this->checkAutoUpgrade($user->id, $request->package_id);
        }
        return redirect()->route('/')->with('successs', "User Name : <strong>{$request->user_code}</strong><br> Password : <strong>{$request->password}</strong>");
    }

    // -----------------user END-------------

    public function product_view()
    {
        $packages = Package::all();
        $products = Product::with('package')->get();
        return view('Admin/product_view', compact('packages', 'products'));
    }

    public function add_product(Request $request)
    {
        $request->validate([
            'productName' => 'required|string|max:255',
            'package_id' => 'required|exists:packages,id',
            'product_image' => 'required|file|max:2048|mimes:jpeg,jpg,png',
            'product_control' => 'required|string',
            'product_description' => 'required|string',
        ]);

        $imagePath = null;
        if ($request->hasFile('product_image')) {
            $file = $request->file('product_image');
            if (in_array($file->getClientOriginalExtension(), ['jpeg', 'jpg', 'png'])) {
                $imageName = time() . '-' . pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '.webp';
                $image = imagecreatefromstring(file_get_contents($file->getRealPath()));
                if ($image !== false) {
                    imagewebp($image, public_path('assets/Product/' . $imageName), 80);
                    imagedestroy($image);
                    $imagePath = 'assets/Product/' . $imageName;
                }
            }
        }

        Product::create([
            'product_name' => $request->input('productName'),
            'product_code' => 'PRD-' . strtoupper(substr(uniqid(), -4)),
            'package_id' => $request->input('package_id'),
            'product_image' => $imagePath,
            'product_control' => $request['product_control'],
            'product_description' => $request['product_description'],
            'product_status' => 1,
        ]);

        return redirect()->route('product_view')->with('success', 'Product added successfully!');
    }

    public function delete_product(Request $request)
    {
        $productId = $request->input('product_id');
        $product = Product::findOrFail($productId);

        $imageName = $product->product_image;
        $imagePath = public_path($imageName);
        if (file_exists($imagePath) && is_file($imagePath)) {
            unlink($imagePath);
        }

        $product->delete();
        return redirect()->route('product_view')->with('success', 'Product deleted successfully.');
    }

    public function edit_product(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|integer|exists:products,id',
            'name' => 'required|string|max:255',
            'package' => 'required|string|max:255',
            'product_image_old' => 'required|string|max:255',
            'status' => 'required|boolean',
            'product_image' => 'nullable|image|max:2048',
            'product_control' => 'required|string',
            'product_description' => 'required|string',
        ]);

        $product = Product::findOrFail($validatedData['id']);

        if ($request->hasFile('product_image')) {
            $oldImagePath = public_path($validatedData['product_image_old']);

            if (file_exists($oldImagePath) && is_file($oldImagePath)) {
                unlink($oldImagePath);
            }

            $file = $request->file('product_image');
            if (in_array($file->getClientOriginalExtension(), ['jpeg', 'jpg', 'png'])) {
                $imageName = time() . '-' . pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '.webp';
                $image = imagecreatefromstring(file_get_contents($file->getRealPath()));
                if ($image !== false) {
                    imagewebp($image, public_path('assets/Product/' . $imageName), 80);
                    imagedestroy($image);
                    $imagePath = 'assets/Product/' . $imageName;
                }
            }
        }
        $product->product_name = $validatedData['name'];
        $product->package_id = $validatedData['package'];
        $product->product_status = $validatedData['status'];
        $product->product_control = $validatedData['product_control'];
        $product->product_description = $validatedData['product_description'];
        if (isset($imagePath)) {
            $product->product_image = $imagePath;
        }

        $product->save();

        return redirect()->back()->with('success', 'Product updated successfully!');
    }

    public function redeem_pin_view()
    {
        $user = auth()->user();
        $pins = PinGeneration::where('transfer_to', $user->id)->get();

        return view('Admin/redeem_pin_view', compact('pins'));
    }

    public function redeemPin(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'pin_id' => 'required|exists:pin_generations,unique_id',
            'password' => 'required',
        ]);

        $pin = PinGeneration::where('unique_id', $request->pin_id)->first();

        if (!$pin) {
            return redirect()->back()->with('error', 'Invalid pin.');
        }

        if ($pin->password !== $request->password) {
            return redirect()->back()->with('error', 'Incorrect password.');
        }

        if ($pin->status === 'Redeemed') {
            return redirect()->back()->with('error', 'This pin has already been redeemed.');
        }

        $pin->transfer_to = $user->id;
        $pin->status = 'Redeemed';
        $pin->save();

        return redirect()->back()->with('success', 'Pin redeemed successfully!');
    }

    public function getUserName(Request $request)
    {
        $user = User::where('connection', $request->userid)->first();

        if ($user) {
            return response()->json([
                'success' => true,
                'name' => $user->name,
                'user_id' => $user->id,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'User not found.',
            ]);
        }
    }
    public function redeemPinParent(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required',
            'user_id' => 'required|exists:users,id',
        ]);

        $pin = PinGeneration::find($validated['id']);
        $user = User::find($validated['user_id']);

        if (!$pin || !$user) {
            return back()->with('error', 'Invalid Pin or User.');
        }

        $pin->transfer_to = $user->id;
        $pin->status = 'transferred';
        $pin->save();

        return back()->with('success', 'Pin successfully transfer to ' . $user->name);
    }

    public function pair_match_company()
    {
        $user = auth()->user();
        $userId = $user->id;

        $pairMatches = PairMatch::join('pair_match_incomes', 'pair_matchs.id', '=', 'pair_match_incomes.pair_match_id')
            ->where('pair_match_incomes.user_id', $userId)
            ->select('pair_matchs.*', 'pair_match_incomes.user_id')
            ->get();
        return view('Admin/pair_match_company', compact('pairMatches'));
    }

    public function getAvailablePins(Request $request)
    {
        $packageId  = $request->input('package_id');
        $authUser   = auth()->user();
        $isAdmin    = in_array($authUser->role, ['admin', 'superadmin']);

        if ($isAdmin && $request->filled('user_id') && $request->filled('target_user_id')) {
            // Verify the requested pin owner is the target user or one of their ancestors
            $pinOwnerId   = (int) $request->input('user_id');
            $targetUserId = (int) $request->input('target_user_id');

            $allowed = [];
            $node = User::find($targetUserId);
            while ($node) {
                $allowed[] = $node->id;
                $node = $node->parent_id ? User::find($node->parent_id) : null;
            }

            if (!in_array($pinOwnerId, $allowed)) {
                return response()->json(['pins' => [], 'products' => [], 'error' => 'Pin owner not allowed for this user.']);
            }
        } else {
            // Regular user or fallback: use their own pins
            $pinOwnerId = $authUser->id;
        }

        $pins = PinGeneration::where('package_id', $packageId)
            ->where('used', '0')
            ->where('user_id', $pinOwnerId)
            ->get(['id', 'unique_id'])
            ->map(fn($p) => ['id' => $p->id, 'unique_id' => $p->unique_id]);

        $products = Product::where('package_id', $packageId)
            ->where('product_status', 1)
            ->get(['id', 'product_name']);

        return response()->json(['pins' => $pins, 'products' => $products]);
    }

    public function updatePin(Request $request)
    {
        // Validate the request
        $request->validate([
            'package_id' => 'required|exists:packages,id',
            'pin_id'     => 'required|exists:pin_generations,id',
            'userid'     => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
        ]);

        $pin = PinGeneration::where('id', $request->pin_id)
            ->where('package_id', $request->package_id)
            ->where('used', '0') // Ensure the pin is unused
            ->first();

        $latestPin = PinTransferDetail::where('pin_id', $pin->id)
            ->latest('id')
            ->first();

        if ($latestPin) {
            $latestPin->update([
                'used' => 1,
                'status' => 'redeemed'
            ]);
        }

        if ($pin->status == 'transferred') {
            $pin->used = 2;
        } elseif ($pin->status == 'pending') {
            $pin->used = 1;
        } else {
            $pin->used = 1;
        }

        $pin->password = '';
        $pin->status = 'Redeemed';
        $pin->updated_at = now();
        $pin->save();

        $loggedUser = auth()->user();

        UserPackage::create([
            'user_id' => $request->userid,
            'package_id' => $request->package_id,
            'pin_id' => $request->pin_id,
            'add_by' => $loggedUser->id,
            'status' => 1,
        ]);

        $package = Package::find($request->package_id);

        if ($package->package_code === 'premium_package' && $package->package_cat === 0) {

            $this->companyRankIncome($request->userid, $request->package_id);
        } elseif ($package->package_code === 'basic_package' && $package->package_cat === 0) {

            $this->companyRankIncomeBasic($request->userid, $request->package_id);
        } elseif ($package->package_code === 'premium_package' && $package->package_cat === 1) {

            $this->companyRankIncomeSuperPremium($request->userid, $request->package_id);
            $this->privilegeIncomeAdd($request->userid, $request->package_id);
            $this->boardIncomeAdd($request->userid, $request->package_id);
            $this->executiveIncomeAdd($request->userid, $request->package_id);
        }

        $this->updateSponsorLevels($request->userid, $request->package_id);
        $referralIncome = $this->addReferralIncome($request->userid, $request->package_id);
        $this->royaltyIncomeAdd($request->userid, $request->package_id);
        // Credit sponsor commission if configured on the package (dynamic, package-code-agnostic)
        $this->creditBinarySponsorIncome($request->userid, $request->package_id);
        // Auto-upgrade if user has reached the threshold for this package
        $this->checkAutoUpgrade($request->userid, $request->package_id);

        // $calculationController = new CalculationController();
        // $calculationController->rank_income();

        //product Order Process --------------------------------------------------------------------

        $user = User::find($request->userid);

        $address = $user->address . ' Pin No:' . $user->pincode;
        $phone_no = $user->phone_no;
        $email = $user->email;


        // Retrieve product, package, and user details
        $product = Product::find($request->product_id);
        $package = Package::find($product->package_id);

        $productImage = asset($product->product_image);

        $productAmt = $package->amount;


        if ($product->id === 4) {

            // $rate = 0.06;
            // $rateper = 112;
            // $per = '6%';
            // $totalper = '12%';

            $rate = 0.025;       // 2.5% for CGST and SGST each
            $rateper = 105;       // Total = 100 + 5% GST
            $per = '2.5%';        // Each GST
            $totalper = '5%';     // Total GST


            $product_price = ($productAmt * 100) / $rateper;
            $product_price = round($product_price, 2);

            $cgst = round($product_price * $rate, 2);
            $sgst = round($product_price * $rate, 2);

            $GST = $cgst + $sgst;

            $totalprdamt = round($product_price + $GST);

            $lastInsertId = HolidayPackageBooking::latest()->value('id');
            $receipt_number = $lastInsertId + 1;

            // Generate PDF Invoice
            $pdf = app('dompdf.wrapper');
            $pdf->loadView('holidayInvoice', compact('user', 'product', 'package', 'address', 'phone_no', 'email', 'product_price', 'receipt_number', 'cgst', 'sgst', 'totalprdamt', 'per', 'totalper'));


            $invoicePath = 'assets/invoices/'; // Relative path
            $invoiceFileName = 'invoice_' . time() . '.pdf';

            $fullInvoicePath = public_path($invoicePath . $invoiceFileName); // Correct full path

            // Ensure the directory exists
            if (!File::exists(public_path($invoicePath))) {
                File::makeDirectory(public_path($invoicePath), 0775, true, true);
            }

            // Save PDF
            $pdf->save($fullInvoicePath);


            HolidayPackageBooking::create([
                'user_id' => $request->userid,
                'product_id' => $request->product_id,
                'package_id' => $product->package_id,
                'address' => $address,
                'phone_no' => $phone_no,
                'email' => $email,
                'date' =>  now(),
                'status' => 0,
                'invoice_path' => $invoicePath . $invoiceFileName,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Email details
            $data = [
                'user_name' => $user->name,
                'product_name' => $product->product_name,
                'package_name' => $package->name,
                'address' => $address,
                'phone_no' => $phone_no,
                'product_image' => $productImage,
            ];

            // User email
            Mail::send('emails.product_delivery', $data, function ($message) use ($email, $invoiceFileName, $fullInvoicePath) {
                $message->to($email)
                    ->subject('Your Product Delivery Details')
                    ->attach($fullInvoicePath, [
                        'as' => $invoiceFileName,
                        'mime' => 'application/pdf',
                    ]);
            });
        } else {

            $rate = 0.09;
            $rateper = 118;
            $per = '9%';
            $totalper = '18%';


            $product_price = ($productAmt * 100) / $rateper;
            $product_price = round($product_price, 2);

            $cgst = round($product_price * $rate, 2);
            $sgst = round($product_price * $rate, 2);

            $GST = $cgst + $sgst;

            $totalprdamt = round($product_price + $GST);

            $lastInsertId = ProductDeliveryDetail::latest()->value('id');
            $receipt_number = $lastInsertId + 1;

            // Generate PDF Invoice
            $pdf = app('dompdf.wrapper');
            $pdf->loadView('invoice', compact('user', 'product', 'package', 'address', 'phone_no', 'email', 'product_price', 'receipt_number', 'cgst', 'sgst', 'totalprdamt', 'per', 'totalper'));

            $invoicePath = 'assets/invoices/'; // Relative path
            $invoiceFileName = 'invoice_' . time() . '.pdf';

            $fullInvoicePath = public_path($invoicePath . $invoiceFileName); // Correct full path

            // Ensure the directory exists
            if (!File::exists(public_path($invoicePath))) {
                File::makeDirectory(public_path($invoicePath), 0775, true, true);
            }

            // Save PDF
            $pdf->save($fullInvoicePath);


            ProductDeliveryDetail::create([
                'user_id' => $request->userid,
                'product_id' => $request->product_id,
                'package_id' => $product->package_id,
                'address' => $address,
                'phone_no' => $phone_no,
                'email' => $email,
                'date' =>  now(),
                'status' => 0,
                'invoice_path' => $invoicePath . $invoiceFileName,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Email details
            $data = [
                'user_name' => $user->name,
                'product_name' => $product->product_name,
                'package_name' => $package->name,
                'address' => $address,
                'phone_no' => $phone_no,
                'product_image' => $productImage,
            ];

            // User email
            try {
                Mail::send('emails.product_delivery', $data, function ($message) use ($email, $invoiceFileName, $fullInvoicePath) {
                    $message->to($email)
                        ->subject('Your Product Delivery Details')
                        ->attach($fullInvoicePath, [
                            'as' => $invoiceFileName,
                            'mime' => 'application/pdf',
                        ]);
                });
            } catch (\Exception $e) {
                \Log::warning('Package activation email failed (user): ' . $e->getMessage());
            }
        }

        if ($product->id == 4) {

            $deliveryPartnerEmail = "impactvagamon@gmail.com";
        } elseif ($product->id == 5) {

            $deliveryPartnerEmail = "impactpromotionspl@gmail.com";
        } else {

            $deliveryPartnerEmail = "impactdq@gmail.com";
        }


        $deliveryData = [
            'user_name' => $user->name,
            'product_name' => $product->product_name,
            'package_name' => $package->name,
            'address' => $address,
            'phone_no' => $phone_no,
            'product_image' => $productImage,
            'user_email' => $email,
        ];

        // Send email to delivery partner
        try {
            Mail::send('emails.delivery_partner_notification', $deliveryData, function ($message) use ($deliveryPartnerEmail, $invoiceFileName, $fullInvoicePath) {
                $message->to($deliveryPartnerEmail)
                    ->subject('New Product Delivery Request')
                    ->attach($fullInvoicePath, [
                        'as' => $invoiceFileName,
                        'mime' => 'application/pdf',
                    ]);
            });
        } catch (\Exception $e) {
            \Log::warning('Package activation email failed (delivery partner): ' . $e->getMessage());
        }

        // Redirect back to binary tree if request came from there, otherwise sunflower
        if (str_contains(request()->headers->get('referer', ''), 'binary-tree')) {
            $nodeId = $request->input('node_id');
            $redirectUrl = route('admin.binary_tree') . ($nodeId ? '?node_id=' . (int) $nodeId : '');
            return redirect($redirectUrl)->with('success', 'Package activated successfully.');
        }

        return redirect()->route('sunflower')->with('success', 'Package activation and product ordering were completed successfully.');

        // return redirect()->route('sunflower')->with('success', 'Pin updated successfully.');
    }

    /**
     * Updates sponsor levels and assigns level-based commissions to sponsors in the hierarchy.
     *
     * This method iterates through the sponsor chain for the given user, assigns commissions
     * based on the user's sponsor level, and skips sponsors based on specific conditions.
     *
     * The conditions to skip a sponsor are:
     * - The sponsor's `mother_id` is 0.
     * - The sponsor has already been credited for this user.
     * - The sponsor's PAN card number does not match the user's PAN card number.
     *
     * The method updates the sponsor's total income and inserts the sponsor level information
     * into the `sponsor_levels` table.
     *
     * @param int $userId     The ID of the user whose sponsor levels are being updated.
     * @param int $package_id The package ID associated with the user.
     *
     * @return void
     */
    private function updateSponsorLevels($userId, $package_id)
    {
        $user = User::find($userId);
        $package = Package::find($package_id);

        $sponsorLevel = 1;
        $sponsorChain = [];

        $alreadyCreditedUsers = [];

        while ($user && $user->sponsor_id) {
            $sponsor = User::find($user->sponsor_id);
            $nextUser = $sponsor;

            // Find the next eligible sponsor based on the conditions
            while ($sponsor && ($sponsor->mother_id == 0  || in_array($sponsor->id, $alreadyCreditedUsers) || $sponsor->pan_card_no != $nextUser->pan_card_no)) {
                $sponsor = User::find($sponsor->sponsor_id);
            }

            // If a valid sponsor is found and they haven't been credited yet. Here we don't want to check pan card again.
            if ($sponsor && !in_array($sponsor->id, $alreadyCreditedUsers)) {

                //new logic for finding sponsor package
                $sponsorPackages = UserPackage::where('user_id', $sponsor->id)
                    ->where('status', 1)
                    ->pluck('package_id');

                $sponsorPackageCodes = Package::whereIn('id', $sponsorPackages)->pluck('package_code')->toArray();

                if (!in_array($package->package_code, $sponsorPackageCodes)) {
                    // Skip this sponsor if their package codes do NOT match
                    $user = $nextUser;
                    continue;
                }

                if ($package->package_code === 'basic_package') {
                    $amount = $this->getAmountByLevelBasic($sponsorLevel);
                } elseif ($package->package_code === 'premium_package' && $package->package_cat === 0) {
                    $amount = $this->getAmountByLevelPremium($sponsorLevel);
                } elseif ($package->package_code === 'premium_package' && $package->package_cat === 1) {
                    $amount = $this->getAmountByLevelSuperPremium($sponsorLevel);
                }

                $alreadyCreditedUsers[] = $sponsor->id;
                // echo "sponsor id: " . $sponsor->id . " - Level: " . $sponsorLevel . "<br>";
                // print_r($alreadyCreditedUsers);
                // echo "<br>";

                $sponsorChain[] = [
                    'user_id' => $userId,
                    'sponsor_id' => $sponsor->id,
                    'package_id' => $package_id,
                    'package_category' => $package->package_code,
                    'sponsor_level' => $sponsorLevel,
                    'amount' => $amount,
                    'is_redeemed' => 0,
                    'status' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                // Stop this process. It is now being handled manually.
                // User::where('id', $sponsor->id)->increment('total_income', $amount);

                $sponsorLevel++;
            }

            $user = $nextUser;
        }
        // die();
        if (!empty($sponsorChain)) {
            DB::table('sponsor_levels')->insert($sponsorChain);
        }
    }

    private function getAmountByLevelBasic($level)
    {
        if ($level == 1) return 75;
        if ($level == 2) return 50;
        if ($level == 3) return 20;
        if ($level == 4) return 15;
        if ($level == 5) return 10;
        if ($level == 6) return 9;
        if ($level == 7) return 8;
        if ($level == 8) return 7;
        if ($level == 9) return 6;
        if ($level == 10) return 5;
        if ($level >= 11 && $level <= 30) return 2;
        if ($level >= 31 && $level <= 50) return 1;

        return 0;
    }
    private function getAmountByLevelPremium($level)
    {
        if ($level == 1) return 375;
        if ($level == 2) return 250;
        if ($level == 3) return 100;
        if ($level == 4) return 75;
        if ($level == 5) return 50;
        if ($level == 6) return 45;
        if ($level == 7) return 40;
        if ($level == 8) return 35;
        if ($level == 9) return 30;
        if ($level == 10) return 25;
        if ($level >= 11 && $level <= 30) return 10;
        if ($level >= 31 && $level <= 50) return 5;
        if ($level >= 51 && $level <= 100) return 2;
        if ($level >= 101 && $level <= 202) return 1;

        return 0;
    }

    private function getAmountByLevelSuperPremium($level)
    {
        if ($level == 1) return 750;
        if ($level == 2) return 500;
        if ($level == 3) return 200;
        if ($level == 4) return 150;
        if ($level == 5) return 100;
        if ($level == 6) return 100;
        if ($level == 7) return 100;
        if ($level == 8) return 100;
        if ($level == 9) return 100;
        if ($level == 10) return 100;
        if ($level >= 11 && $level <= 30) return 20;
        if ($level >= 31 && $level <= 50) return 10;
        // if ($level >= 51 && $level <= 100) return 5;

        return 0;  // Default amount for levels beyond 100
    }

    private function companyRankIncome($userId, $package_id)
    {
        $is_redeemed = 0;
        $status = 0;


        $ranks = [
            2 => 50,  // Silver
            3 => 45,   // Gold
            4 => 40,   // Platinum
            5 => 35,   // Sapphire
            6 => 30,   // Pearl
            7 => 25,   // Ruby
            8 => 20,   // Diamond
            9 => 15,   // Emerald
            10 => 10,  // Crown
            11 => 5,  // ⁠Royal crown
            12 => 5,  // Ambassador
            13 => 5,  // Royal Crown Ambassador
        ];

        // Loop through the ranks array and create records
        foreach ($ranks as $rank_id => $amount) {
            CompanyRankIncome::create([
                'rank_id' => $rank_id,
                'amount' => $amount,
                'user_id' => $userId,
                'package_id' => $package_id,
                'is_redeemed' => $is_redeemed,
                'status' => $status,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function companyRankIncomeSuperPremium($userId, $package_id)
    {
        $is_redeemed = 0;
        $status = 0;


        $ranks = [
            2 => 100,  // Gold
            3 => 100,   // Platinum
            4 => 100,   // Pearl
            5 => 70,   // Ruby
            6 => 60,   // Diamond
            7 => 50,   // Double Diamond
            8 => 40,   // Emerald
            9 => 30,   // Crown
            10 => 20,  // Royal Crown
            11 => 10,  // Manager
            12 => 10,  // Ambassador
            13 => 10,  // Ro
        ];

        // Loop through the ranks array and create records
        foreach ($ranks as $rank_id => $amount) {
            CompanyRankIncome::create([
                'rank_id' => $rank_id,
                'amount' => $amount,
                'user_id' => $userId,
                'package_id' => $package_id,
                'is_redeemed' => $is_redeemed,
                'status' => $status,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
    private function addReferralIncome($id, $package_id)
    {
        $user = User::findOrFail($id);
        $package = Package::find($package_id);

        $sponsorPackages = UserPackage::where('user_id', $user->sponsor_id)
            ->where('status', 1)
            ->pluck('package_id');

        $sponsorPackageCodes = Package::whereIn('id', $sponsorPackages)->pluck('package_code')->toArray();

        if (!in_array($package->package_code, $sponsorPackageCodes)) {
            return null; // Do not proceed if user hasn't taken the package
        }


        $income = 0;
        if ($package->package_code === 'basic_package') {
            $income = 50;
        } elseif ($package->package_code === 'premium_package' && $package->package_cat === 0) {
            $income = 250;
        } elseif ($package->package_code === 'premium_package' && $package->package_cat === 1) {
            $income = 500;
        }

        if ($user && ($income > 0)) {
            $referralIncome = ReferralIncome::create([
                'sponsor_id' => $user->sponsor_id,
                'user_id' => $user->id,
                'package_id' => $package_id,
                'package_category' => $package->package_code,
                'income' => $income,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return $referralIncome;
        }
        return null;
    }

    private function royaltyIncomeAdd($userId, $package_id)
    {
        $package = Package::find($package_id);

        if ($package->package_code === 'basic_package') {
            $amount = 10;
        } elseif ($package->package_code === 'premium_package' && $package->package_cat === 0) {
            $amount = 50;
        } elseif ($package->package_code === 'premium_package' && $package->package_cat === 1) {
            $amount = 100;
        } else {
            return; // Package has no royalty income configured
        }

        RoyaltyIncomeWallet::create([
            'user_id' => $userId,
            'package_id' => $package_id,
            'amount' => $amount,
            'is_redeemed' => 0,
            'status' => 0,
        ]);
    }

    private function companyRankIncomeBasic($userId, $package_id)
    {
        $is_redeemed = 0;
        $status = 0;

        $ranks = [
            2 => 10,  // 1star
            3 => 10,   // 2star
            4 => 10,   // 3star
            5 => 10,   // 4star
            6 => 10,   // 5star
        ];

        // Loop through the ranks array and create records
        foreach ($ranks as $rank_id => $amount) {
            BasicRankIncome::create([
                'rank_id' => $rank_id,
                'amount' => $amount,
                'user_id' => $userId,
                'package_id' => $package_id,
                'is_redeemed' => $is_redeemed,
                'status' => $status,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function motheridlist()
    {
        $loggedInUser = auth()->user();

        $users = DB::table('users')
            ->where('pan_card_no', $loggedInUser->pan_card_no)
            ->get();


        foreach ($users as $user) {
            $totalLevelIncome = DB::table('sponsor_levels')
                ->where('sponsor_id', $user->id)
                ->where('is_redeemed', 0)
                ->sum('amount');

            $totalReferralIncome = DB::table('referral_incomes')
                ->where('sponsor_id', $user->id)
                ->where('status', 1)
                ->sum('income');

            $totalRankIncome = DB::table('rank_incomes')
                ->where('user_id', $user->id)
                ->where('status', 1)
                ->sum('amount');

            $totalRoyaltyIncome = DB::table('royalty_user_wallets')
                ->where('user_id', $user->id)
                ->where('status', 1)
                ->sum('amount');

            $bonus = BonusWallet::where('user_id', $user->id)
                ->where('is_redeemed', 0)
                ->sum('amount');

            // Calculate total income
            $user->total_income = $totalLevelIncome + $totalReferralIncome + $totalRankIncome + $totalRoyaltyIncome + $bonus;
        }

        return view('Admin/motheridlist', compact('users', 'loggedInUser'));
    }

    public function addincome($userId)
    {
        $user = DB::table('users')->where('id', $userId)->first();

        $userpan = $user->pan_card_no;

        $childUsers = DB::table('users')
            ->where('pan_card_no', $userpan)
            ->where('id', '!=', $userId)
            ->get();

        foreach ($childUsers as $child) {

            $basicLevelIncome = DB::table('sponsor_levels')
                ->where('sponsor_id', $child->id)
                ->where('is_redeemed', 0)
                ->where('package_category', 'basic_package')
                ->sum('amount');

            $premiumLevelIncome = DB::table('sponsor_levels')
                ->where('sponsor_id', $child->id)
                ->where('is_redeemed', 0)
                ->where('package_category', 'premium_package')
                ->sum('amount');

            $basicReferralIncome = DB::table('referral_incomes')
                ->where('sponsor_id', $child->id)
                ->where('status', 1)
                ->where('package_category', 'basic_package')
                ->sum('income');

            $premiumReferralIncome = DB::table('referral_incomes')
                ->where('sponsor_id', $child->id)
                ->where('status', 1)
                ->where('package_category', 'premium_package')
                ->sum('income');

            $totalRankIncome = DB::table('rank_incomes')
                ->where('user_id', $child->id)
                ->where('status', 1)
                ->sum('amount');

            $totaRoyaltyIncome = DB::table('royalty_user_wallets')
                ->where('user_id', $child->id)
                ->where('status', 1)
                ->sum('amount');

            $bonus = BonusWallet::where('user_id', $child->id)
                ->where('is_redeemed', 0)
                ->sum('amount');

            $privilege = PrivilegeUserWallet::where('user_id', $child->id)
                ->where('status', 1)
                ->sum('amount');

            $executive = ExecutiveUserWallet::where('user_id', $child->id)
                ->where('status', 1)
                ->sum('amount');

            $board = BoardUserWallet::where('user_id', $child->id)
                ->where('status', 1)
                ->sum('amount');

            $incentive = IncentiveUserWallet::where('user_id', $child->id)
                ->where('status', 1)
                ->sum('amount');

            $totalBasicRankIncome = BasicUserRankIncome::where('user_id', $child->id)
                ->where('status', 1)
                ->sum('amount');


            // Level income start

            if ($basicLevelIncome > 0) {
                DB::table('sponsor_levels')->insert([
                    'user_id' => $child->id,
                    'sponsor_id' => $user->id,
                    'package_id' => 1,
                    'package_category' => 'basic_package',
                    'sponsor_level' => 0,
                    'amount' => $basicLevelIncome,
                    'is_redeemed' => 0,
                    'status' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                DB::table('child_mother_payments')->insert([
                    'child_id' => $child->id,
                    'mother_id' => $user->id,
                    'amount' => $basicLevelIncome,
                    'type' => 1, // basic level income
                    'status' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Update the existing records
                DB::table('sponsor_levels')
                    ->where('sponsor_id', $child->id)
                    ->where('package_category', 'basic_package')
                    ->update(['is_redeemed' => 1]);
            }

            if ($premiumLevelIncome > 0) {
                DB::table('sponsor_levels')->insert([
                    'user_id' => $child->id,
                    'sponsor_id' => $user->id,
                    'package_id' => 2,
                    'package_category' => 'premium_package',
                    'sponsor_level' => 0,
                    'amount' => $premiumLevelIncome,
                    'is_redeemed' => 0,
                    'status' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                DB::table('child_mother_payments')->insert([
                    'child_id' => $child->id,
                    'mother_id' => $user->id,
                    'amount' => $premiumLevelIncome,
                    'type' => 2, // premium level income
                    'status' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Update the existing records
                DB::table('sponsor_levels')
                    ->where('sponsor_id', $child->id)
                    ->where('package_category', 'premium_package')
                    ->update(['is_redeemed' => 1]);
            }

            // level income End

            // Referal income Start
            if ($basicReferralIncome > 0) {
                ReferralIncome::create([
                    'sponsor_id' => $user->id,
                    'user_id' => $child->id,
                    'package_id' => 1,
                    'package_category' => 'basic_package',
                    'income' => $basicReferralIncome,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                DB::table('child_mother_payments')->insert([
                    'child_id' => $child->id,
                    'mother_id' => $user->id,
                    'amount' => $basicReferralIncome,
                    'type' => 3, // basic referal income
                    'status' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Update the existing records
                DB::table('referral_incomes')
                    ->where('sponsor_id', $child->id)
                    ->where('package_category', 'basic_package')
                    ->update(['status' => 0]);
            }

            if ($premiumReferralIncome > 0) {
                ReferralIncome::create([
                    'sponsor_id' => $user->id,
                    'user_id' => $child->id,
                    'package_id' => 2,
                    'package_category' => 'premium_package',
                    'income' => $premiumReferralIncome,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                DB::table('child_mother_payments')->insert([
                    'child_id' => $child->id,
                    'mother_id' => $user->id,
                    'amount' => $premiumReferralIncome,
                    'type' => 4, // premium referal income
                    'status' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Update the existing records
                DB::table('referral_incomes')
                    ->where('sponsor_id', $child->id)
                    ->where('package_category', 'premium_package')
                    ->update(['status' => 0]);
            }

            // End

            if ($totalRankIncome > 0) {
                RankIncome::create([
                    'user_id' => $user->id,
                    'rank_id' => 1,
                    'amount' => $totalRankIncome,
                    'status' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                DB::table('child_mother_payments')->insert([
                    'child_id' => $child->id,
                    'mother_id' => $user->id,
                    'amount' => $totalRankIncome,
                    'type' => 5, //rank income
                    'status' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Update the existing records
                DB::table('rank_incomes')
                    ->where('user_id', $child->id)
                    ->update(['status' => 0]);
            }

            if ($totaRoyaltyIncome > 0) {
                RoyaltyUserWallet::create([
                    'user_id' => $user->id,
                    'amount' => $totaRoyaltyIncome,
                    'status' => 1
                ]);

                DB::table('child_mother_payments')->insert([
                    'child_id' => $child->id,
                    'mother_id' => $user->id,
                    'amount' => $totaRoyaltyIncome,
                    'type' => 6, // royalty income
                    'status' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Update the existing records
                DB::table('royalty_user_wallets')
                    ->where('user_id', $child->id)
                    ->update(['status' => 0]);
            }

            if ($bonus > 0) {
                BonusWallet::create([
                    'user_id' => $user->id,
                    'amount' => $bonus,
                    'type' => 2,
                    'is_redeemed' => 0,
                    'status' => 0,
                ]);

                DB::table('child_mother_payments')->insert([
                    'child_id' => $child->id,
                    'mother_id' => $user->id,
                    'amount' => $bonus,
                    'type' => 7, // bonus income
                    'status' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Update the existing records
                DB::table('bonus_wallet')
                    ->where('user_id', $child->id)
                    ->update(['is_redeemed' => 1]);
            }

            if ($privilege > 0) {


                PrivilegeUserWallet::create([
                    'user_id' => $user->id,
                    'amount' => $privilege,
                    'status' => 1
                ]);

                DB::table('child_mother_payments')->insert([
                    'child_id' => $child->id,
                    'mother_id' => $user->id,
                    'amount' => $privilege,
                    'type' => 8, // privilege income
                    'status' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Update the existing records
                PrivilegeUserWallet::where('user_id', $child->id)
                    ->update(['status' => 0]);
            }

            if ($executive > 0) {
                ExecutiveUserWallet::create([
                    'user_id' => $user->id,
                    'amount' => $executive,
                    'status' => 1
                ]);

                DB::table('child_mother_payments')->insert([
                    'child_id' => $child->id,
                    'mother_id' => $user->id,
                    'amount' => $executive,
                    'type' => 9, // executive income
                    'status' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Update the existing records
                ExecutiveUserWallet::where('user_id', $child->id)
                    ->update(['status' => 0]);
            }
            if ($board > 0) {
                BoardUserWallet::create([
                    'user_id' => $user->id,
                    'amount' => $board,
                    'status' => 1
                ]);

                DB::table('child_mother_payments')->insert([
                    'child_id' => $child->id,
                    'mother_id' => $user->id,
                    'amount' => $board,
                    'type' => 10, // board income
                    'status' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Update the existing records
                BoardUserWallet::where('user_id', $child->id)
                    ->update(['status' => 0]);
            }
            if ($incentive > 0) {
                IncentiveUserWallet::create([
                    'user_id' => $user->id,
                    'amount' => $incentive,
                    'status' => 1
                ]);

                DB::table('child_mother_payments')->insert([
                    'child_id' => $child->id,
                    'mother_id' => $user->id,
                    'amount' => $incentive,
                    'type' => 11, // incentive income
                    'status' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Update the existing records
                IncentiveUserWallet::where('user_id', $child->id)
                    ->update(['status' => 0]);
            }

            if ($totalBasicRankIncome > 0) {
                BasicUserRankIncome::create([
                    'user_id' => $user->id,
                    'rank_id' => 1,
                    'amount' => $totalRankIncome,
                    'status' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                DB::table('child_mother_payments')->insert([
                    'child_id' => $child->id,
                    'mother_id' => $user->id,
                    'amount' => $totalRankIncome,
                    'type' => 12, // basic rank income
                    'status' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Update the existing records
                BasicUserRankIncome::where('user_id', $child->id)
                    ->update(['status' => 0]);
            }
        }

        return redirect()->route('motheridlist')->with('success', 'Income add successful');
    }

    public function childToMotherIncome_list()
    {
        $userId = auth()->id();
        $childToMotherIncome_list = ChildMotherPayment::where('mother_id', $userId)->get();

        return view('Admin.childToMotherIncome_list', compact('childToMotherIncome_list'));
    }

    public function transferToWallet()
    {
        $userId = auth()->id();

        $user = User::find($userId);

        $levelpremium = DB::table('sponsor_levels')
            ->where('sponsor_id', $user->id)
            ->where('package_category', 'premium_package')
            ->where('is_redeemed', 0)
            ->sum('amount');

        $levelbasic = DB::table('sponsor_levels')
            ->where('sponsor_id', $user->id)
            ->where('package_category', 'basic_package')
            ->where('is_redeemed', 0)
            ->sum('amount');

        $royaltyIncome = DB::table('royalty_user_wallets')
            ->where('user_id', $user->id)
            ->where('status', 1)
            ->sum('amount');

        $rankincome = RankIncome::where('user_id', $user->id)
            ->where('status', 1)
            ->sum('amount');

        $referrelpremium = ReferralIncome::where('sponsor_id', $user->id)
            ->where('status', 1)
            ->where('package_category', 'premium_package')
            ->sum('income');

        $referrelbasic = ReferralIncome::where('sponsor_id', $user->id)
            ->where('status', 1)
            ->where('package_category', 'basic_package')
            ->sum('income');

        $bonus = BonusWallet::where('user_id', $user->id)
            ->where('is_redeemed', 0)
            ->sum('amount');

        $repurchaseTotal = RepurchaseWallet::where('user_id', $user->id)
            ->where('is_redeemed', 0)
            ->sum('amount');

        $privilegeTotal = PrivilegeUserWallet::where('user_id', $user->id)
            ->where('status', 1)
            ->sum('amount');

        $boardTotal = BoardUserWallet::where('user_id', $user->id)
            ->where('status', 1)
            ->sum('amount');

        $executiveTotal = ExecutiveUserWallet::where('user_id', $user->id)
            ->where('status', 1)
            ->sum('amount');

        $incentiveTotal = IncentiveUserWallet::where('user_id', $user->id)
            ->where('status', 1)
            ->sum('amount');

        $basicRankIncome = BasicUserRankIncome::where('user_id', $user->id)
            ->where('status', 1)
            ->sum('amount');


        $WalletTransactionDetail = WalletTransactionDetail::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();


        return view('Admin.transferToWallet', compact(
            'user',
            'levelpremium',
            'royaltyIncome',
            'rankincome',
            'referrelpremium',
            'WalletTransactionDetail',
            'levelbasic',
            'referrelbasic',
            'bonus',
            'repurchaseTotal',
            'privilegeTotal',
            'boardTotal',
            'executiveTotal',
            'incentiveTotal',
            'basicRankIncome'
        ));
    }

    public function withdrawal_view()
    {
        $loggedInUser = auth()->user();
        $userId = $loggedInUser->id;
        $userdata = DB::table('users')
            ->where('id', $userId)
            ->first();

        if ($loggedInUser->role === 'superadmin') {
            $requests = WithdrawalRequest::with('user')->orderBy('created_at', 'desc')->get();
        } else {
            $requests = WithdrawalRequest::with('user')
                ->where('user_id', $loggedInUser->id)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        $lastWithdrawal = DB::table('withdrawal_requests')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->first();

        $canWithdraw = true;
        $nextWithdrawalDate = null;

        if ($lastWithdrawal) {
            $lastWithdrawalDate = Carbon::parse($lastWithdrawal->updated_at);
            $nextWithdrawalDate = $lastWithdrawalDate->addDays(7);

            $canWithdraw = Carbon::now()->gte($nextWithdrawalDate);
        }
        $userBankDetails = UserBankingDetail::where('user_id', auth()->id())->first();

        return view('Admin/withdrawal_view', compact('userdata', 'requests', 'canWithdraw', 'nextWithdrawalDate', 'userBankDetails', 'lastWithdrawal'));
    }

    public function withdrawRequest(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:500|max:70000',
            'id' => 'required|exists:users,id',
        ], [
            'amount.min' => 'The withdrawal amount must be at least 500.',
            'amount.max' => 'The withdrawal amount must not exceed 70,000.',
        ]);

        $donate = $request->input('donate');

        // Get the logged-in user
        $user = Auth::user();

        // Check if the user has enough balance
        if ($user->total_income < $request->amount) {
            return back()->with('error', 'You do not have enough balance for this withdrawal.');
        }

        if ($donate == 1) {

            $withdrawalAmt = $request->amount;
            //Admin Feee
            $adminFee = ($user->id == 1) ? 0 : 0.05;
            $adminFeeDeduction = $withdrawalAmt * $adminFee;
            // $adminFeeDeductionAmt = $withdrawalAmt - $adminFeeDeduction;

            // Donation
            $donationDeduction = $withdrawalAmt - 50;
            // $donationDeduction = $adminFeeDeductionAmt - 50;

            //TDS Fee(GST)
            $TDSfee = ($user->id == 1) ? 0.02 : 0.02;
            // $deduction = $donationDeduction * $TDSfee;
            $deduction = $withdrawalAmt * $TDSfee;

            $rawValue = $adminFeeDeduction + $deduction + 50;
            $decimalPart = $rawValue - floor($rawValue);

            if ($decimalPart == 0.5) {
                $totaldeduction = floor($rawValue) + 0.5;
            } else {
                $totaldeduction = round($rawValue);
            }

            $finalAmount = $request->amount - $totaldeduction;
            $donation = 1;
        } else {

            $withdrawalAmt = $request->amount;
            //Admin fee
            $adminFee = ($user->id == 1) ? 0 : 0.05;
            $adminFeeDeduction = $request->amount * $adminFee;
            // $adminFeeDeductionAmt = $request->amount - $adminFeeDeduction;

            // TDS fee(GST)
            $TDSfee = ($user->id == 1) ? 0.02 : 0.02;
            // $deduction = $adminFeeDeductionAmt * $TDSfee;
            $deduction = $withdrawalAmt * $TDSfee;

            $rawValue = $adminFeeDeduction + $deduction;
            $decimalPart = $rawValue - floor($rawValue);

            if ($decimalPart == 0.5) {
                $totaldeduction = floor($rawValue) + 0.5;
            } else {
                $totaldeduction = round($rawValue);
            }

            $finalAmount = $request->amount - $totaldeduction;
            $donation = 0;
        }

        DB::table('withdrawal_requests')->insert([
            'user_id' => $user->id,
            'amount' => $request->amount,
            'deduction_amount' => $totaldeduction,
            'balance_amount' => $finalAmount,
            'status' => 'pending',
            'donation' => $donation,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Your withdrawal request has been submitted successfully.');
    }

    public function approve($requestId)
    {
        $withdrawalRequest = DB::table('withdrawal_requests')->find($requestId);

        if (!$withdrawalRequest) {
            return back()->with('error', 'Request not found.');
        }


        if ($withdrawalRequest->donation == 1) {

            $withdrawalAmt = $withdrawalRequest->amount;
            //Admin Feee
            $adminFee = ($withdrawalRequest->user_id == 1) ? 0 : 0.05;
            $adminFeeDeduction = $withdrawalAmt * $adminFee;
            // $adminFeeDeductionAmt = $withdrawalAmt - $adminFeeDeduction;

            // Donation
            // $donationDeduction = $adminFeeDeductionAmt - 50;
            $donationDeduction = $withdrawalAmt - 50;

            //TDS Fee(GST)
            $TDSfee = ($withdrawalRequest->user_id == 1) ? 0.02 : 0.02;
            // $TDSfeeDeduction = $donationDeduction * $TDSfee;
            $TDSfeeDeduction = $withdrawalAmt * $TDSfee;

            $rawValue = $adminFeeDeduction + $TDSfeeDeduction + 50;
            $decimalPart = $rawValue - floor($rawValue);

            if ($decimalPart == 0.5) {
                $totaldeduction = floor($rawValue) + 0.5;
            } else {
                $totaldeduction = round($rawValue);
            }

            $finalAmount = $withdrawalRequest->amount - $totaldeduction;

            DonationWallet::create([
                'user_id' => $withdrawalRequest->user_id,
                'amount' => 50,
                'type' => 1,
                'status' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {

            $withdrawalAmt = $withdrawalRequest->amount;
            //Admin fee
            $adminFee = ($withdrawalRequest->user_id == 1) ? 0 : 0.05;
            $adminFeeDeduction = $withdrawalRequest->amount * $adminFee;
            // $adminFeeDeductionAmt = $withdrawalRequest->amount - $adminFeeDeduction;

            // TDS fee(GST)
            $TDSfee = ($withdrawalRequest->user_id == 1) ? 0.02 : 0.02;
            // $TDSfeeDeduction = $adminFeeDeductionAmt * $TDSfee;
            $TDSfeeDeduction = $withdrawalAmt * $TDSfee;

            $rawValue = $adminFeeDeduction + $TDSfeeDeduction;
            $decimalPart = $rawValue - floor($rawValue);

            if ($decimalPart == 0.5) {
                $totaldeduction = floor($rawValue) + 0.5;
            } else {
                $totaldeduction = round($rawValue);
            }

            $finalAmount = $withdrawalRequest->amount - $totaldeduction;
        }

        $adminAmt = $adminFeeDeduction + $TDSfeeDeduction;

        User::where('id', 1)->increment('total_income', $adminAmt);

        // Admin Fee
        if ($withdrawalRequest->user_id != 1) {
            AdminWallet::create([
                'admin_id' => 1,
                'from_user_id' => $withdrawalRequest->user_id,
                'amount' => $adminFeeDeduction,
                'type' => 2,
                'status' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        //  TDS fee
        AdminWallet::create([
            'admin_id' => 1,
            'from_user_id' => $withdrawalRequest->user_id,
            'amount' => $TDSfeeDeduction,
            'type' => 3,
            'status' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Admin Wallet
        if ($withdrawalRequest->user_id == 1) {
            AdminWallet::create([
                'admin_id' => 1,
                'from_user_id' => $withdrawalRequest->user_id,
                'amount' => $withdrawalRequest->amount,
                'type' => 6,
                'status' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }


        // Update the request status to approved
        DB::table('withdrawal_requests')
            ->where('id', $requestId)
            ->update(['status' => 'approved', 'updated_at' => now()]);

        DB::table('users')->where('id', $withdrawalRequest->user_id)->decrement('total_income', $withdrawalRequest->amount);

        // Optionally, update user's total_income or perform other actions (like transferring funds)

        return back()->with('success', 'Withdrawal request approved.');
    }


    public function reject($requestId)
    {
        DB::table('withdrawal_requests')->where('id', $requestId)->update(['status' => 'rejected']);

        return back()->with('success', 'Withdrawal request rejected.');
    }

    public function userlist()
    {
        $loggedUser = auth()->user();
        // $users = User::where('id', '!=', $loggedUser->id)->get();

        // $users = User::where('users.id', '!=', $loggedUser->id)
        //     ->where('role', 'user')
        //     ->leftJoin('user_packages', 'users.id', '=', 'user_packages.user_id')
        //     ->leftJoin('packages', 'user_packages.package_id', '=', 'packages.id') // Join packages table
        //     ->select(
        //         'users.*',
        //         'packages.name as package_name',
        //         DB::raw('IF(user_packages.id IS NOT NULL, "Active", "Inactive") as package_status')
        //     )
        //     ->get();
        $users = User::where('role', 'user')->get();


        return view('Admin/user_list', compact('users'));
    }

    public function assignPanCard(Request $request)
    {
        $request->validate([
            'user_id'    => 'required|exists:users,id',
            'pan_card_no'=> 'required|regex:/^[A-Z]{5}[0-9]{4}[A-Z]$/',
        ]);

        $user = User::findOrFail($request->user_id);

        if ($user->pan_card_no && strtoupper($user->pan_card_no) !== 'STORE') {
            return response()->json(['status' => 'error', 'message' => 'User already has a PAN card.']);
        }

        $pan     = strtoupper($request->pan_card_no);
        $existing = DB::table('users')->where('pan_card_no', $pan)->first();

        if ($existing) {
            // Name must match
            if (strtolower($existing->name) !== strtolower($user->name)) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Name mismatch. PAN belongs to "' . $existing->name . '" but this user is "' . $user->name . '".',
                ]);
            }

            // Assign privilege or child slot
            $slot2 = DB::table('users')->where('pan_card_no', $pan)->where('mother_id', 2)->exists();
            $slot3 = DB::table('users')->where('pan_card_no', $pan)->where('mother_id', 3)->exists();

            if (!$slot2) {
                $motherid = 2;
            } elseif (!$slot3) {
                $motherid = 3;
            } else {
                $motherid = 0; // Child ID
            }
        } else {
            // New PAN → Mother ID
            $motherid = 1;
        }

        $user->pan_card_no = $pan;
        $user->mother_id   = $motherid;
        $user->save();

        $label = match($motherid) {
            1 => 'Mother ID',
            2 => 'Privilege 1',
            3 => 'Privilege 2',
            default => 'Child ID',
        };

        return response()->json(['status' => 'success', 'label' => $label, 'mother_id' => $motherid]);
    }

    public function childrenByPan(int $id)
    {
        $user = User::findOrFail($id);

        $hasPan = $user->pan_card_no && strtoupper($user->pan_card_no) !== 'STORE';

        $base = DB::table('users')
            ->where('name', $user->name)
            ->where('id', '!=', $id);
        if ($hasPan) $base->where('pan_card_no', $user->pan_card_no);

        $allOthers    = (clone $base)->get(['id', 'name', 'connection', 'mother_id']);
        $childrenOnly = $allOthers->where('mother_id', 0)->values();

        return response()->json([
            'children'    => $childrenOnly,
            'has_others'  => $allOthers->isNotEmpty(),
            'can_swap'    => $childrenOnly->isNotEmpty(),
        ]);
    }

    public function changeAccountType(Request $request)
    {
        $user = User::findOrFail($request->user_id);

        if ($user->mother_id == 1) {
            return response()->json(['status' => 'error', 'message' => 'Mother ID cannot be changed.']);
        }

        $pan = $user->pan_card_no;

        if ($user->mother_id == 0) {
            // Child → promote to Privilege: find lowest free slot
            $slot2Taken = DB::table('users')->where('pan_card_no', $pan)->where('mother_id', 2)->exists();
            $slot3Taken = DB::table('users')->where('pan_card_no', $pan)->where('mother_id', 3)->exists();

            if (!$slot2Taken) {
                $user->mother_id = 2;
            } elseif (!$slot3Taken) {
                $user->mother_id = 3;
            } else {
                return response()->json(['status' => 'error', 'message' => 'Both Privilege slots are taken. Demote one first.']);
            }
            $user->save();
        } elseif ($request->swap_with_id) {
            // Privilege ↔ Child swap
            $child = User::findOrFail($request->swap_with_id);
            if ($child->pan_card_no !== $pan || $child->mother_id != 0) {
                return response()->json(['status' => 'error', 'message' => 'Invalid swap target.']);
            }
            $privilegeSlot  = $user->mother_id;
            $user->mother_id  = 0;
            $child->mother_id = $privilegeSlot;
            $user->save();
            $child->save();
        } else {
            // Privilege → Child (no swap)
            $user->mother_id = 0;
            $user->save();
        }

        $label = match($user->mother_id) {
            1 => 'Mother ID', 2 => 'Privilege 1', 3 => 'Privilege 2', default => 'Child ID',
        };

        return response()->json(['status' => 'success', 'new_type' => $user->mother_id, 'label' => $label]);
    }

    public function getUserDetails($id)
    {
        $user = User::find($id); // Assuming you have a User model
        if ($user) {
            return response()->json(['status' => 'success', 'data' => $user]);
        }
        return response()->json(['status' => 'error', 'message' => 'User not found']);
    }

    public function update(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'id' => 'required|exists:users,id',
                'name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
                'pan_card_no' => 'nullable|regex:/^[A-Z]{5}[0-9]{4}[A-Z]$/',
                'email' => 'required|email',
                'phone_no' => 'required|regex:/^[0-9]{10}$/',
                'pincode' => 'required|regex:/^[1-9][0-9]{5}$/',
                'address' => 'required|string',
            ],
            [
                'name.required' => 'Name is required.',
                'name.regex' => 'Name must only contain letters and spaces.',
                'phone_no.regex' => 'Phone number must be exactly 10 digits.',
                'pan_card_no.regex' => 'PAN card number must follow the format: 5 letters, 4 digits, 1 letter.',
                'pincode.regex' => 'Pincode must be 6 digits and cannot start with 0.',
            ]
        );

        if ($validator->fails()) {
            return json_encode(['status' => 'validation', 'errors' => $validator->errors()]);
        }

        $user = User::findOrFail($request->id);
        $user->update($request->only(['name', 'pan_card_no', 'email', 'phone_no', 'pincode', 'address']));

        return json_encode(['status' => 'success', 'message' => 'user added successfully']);
    }

    public function companyRank_income()
    {
        $rankIncomes = CompanyRankIncome::with('rank:id,rank_name')
            ->select(
                'rank_id',
                DB::raw('SUM(CASE WHEN is_redeemed = 1 THEN amount ELSE 0 END) as redeemed_amount'), // Sum for redeemed amounts
                DB::raw('SUM(CASE WHEN is_redeemed = 0 THEN amount ELSE 0 END) as pending_amount'), // Sum for pending amounts
                DB::raw('SUM(amount) as total_amount') // Total amount (all)
            )
            ->groupBy('rank_id')
            ->orderBy('rank_id')
            ->get();

        return view('Admin.companyrank_incomes', compact('rankIncomes'));
    }

    public function rankTotal($rank_id)
    {
        $rankIncomeDetails = CompanyRankIncome::where('rank_id', $rank_id)->get();

        return view('Admin.companyRank_details', compact('rankIncomeDetails'));
    }
    public function rankRedeemed($rank_id)
    {
        $rankIncomes = RankIncome::where('rank_id', $rank_id)
            ->get();

        return view('Admin.userRank_income', compact('rankIncomes'));
    }
    public function rankPending($rank_id)
    {
        $rankIncomeDetails = CompanyRankIncome::where('rank_id', $rank_id)
            ->where('is_redeemed', 0)
            ->get();

        return view('Admin.companyRank_details', compact('rankIncomeDetails'));
    }

    public function redeemToUser(Request $request)
    {
        $rank_id = $request->input('rank_id');

        $userCount = User::where('rank_id', $rank_id)->where('rank_status', 1)->count();
        $rankIncomeTotal = CompanyRankIncome::where('rank_id', $rank_id)
            ->where('is_redeemed', 0)
            ->sum('amount');

        if ($userCount == 0 || $rankIncomeTotal == 0) {
            return redirect()->route('companyRank_income')->with('error', 'No users found or no rank income available to distribute.');
        }


        $baseAmountPerUser = ($userCount > 0) ? floor($rankIncomeTotal / $userCount) : 0;

        $paidtotal = $baseAmountPerUser * $userCount;
        $unpaid = $rankIncomeTotal - $paidtotal;

        if ($unpaid > 0) {
            AdminWallet::create([
                'admin_id' => 1,
                'amount' => $unpaid,
                'type' => 5, //rank balance
                'status' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            User::where('id', 1)->increment('total_income', $unpaid);
        }

        // $users = User::where('rank_id', $rank_id)->get();
        $users = User::where('rank_id', $rank_id)
            ->where('rank_status', 1)
            ->get();

        foreach ($users as $user) {

            RankIncome::create([
                'user_id' => $user->id,
                'rank_id' => $rank_id,
                'amount' => $baseAmountPerUser,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Stop this process. It is now being handled manually.
            // User::where('id', $user->id)->increment('total_income', $baseAmountPerUser);
        }

        CompanyRankIncome::where('rank_id', $rank_id)
            ->where('is_redeemed', 0)
            ->update(['is_redeemed' => 1]);

        $rankIncomes = RankIncome::where('rank_id', $rank_id)
            ->get();

        session()->flash('success', 'User rank income add successfully.');
        return view('Admin.userRank_income', compact('rankIncomes'));
    }

    public function redeemToCompany(Request $request)
    {
        $rank_id = $request->input('rankC_id');

        $rankIncomeTotal = CompanyRankIncome::where('rank_id', $rank_id)
            ->where('is_redeemed', 0)
            ->sum('amount');

        if ($rankIncomeTotal > 0) {
            RankIncome::create([
                'user_id' => 1,
                'rank_id' => $rank_id,
                'amount' => $rankIncomeTotal,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            AdminWallet::create([
                'admin_id' => 1,
                'amount' => $rankIncomeTotal,
                'type' => 1,
                'status' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            User::where('id', 1)->increment('total_income', $rankIncomeTotal);

            CompanyRankIncome::where('rank_id', $rank_id)
                ->where('is_redeemed', 0)
                ->update(['is_redeemed' => 1]);

            $rankIncomes = RankIncome::where('rank_id', $rank_id)->get();

            session()->flash('success', 'Company rank income add successfully.');
            return view('Admin.userRank_income', compact('rankIncomes'));
        } else {
            return redirect()->route('companyRank_income')->with('error', 'no rank income available to distribute.');
        }
    }
    public function adminWallet()
    {
        $adminWallet = User::where(['id' => 1])->first();
        $adminAmountList = AdminWallet::orderBy('id', 'desc')->get();

        return view('Admin.adminWallet', compact('adminWallet', 'adminAmountList'));
    }

    public function adminToRoyalty(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1',
        ]);

        // Get the logged-in user
        $user = Auth::user();

        // Check if the user has enough balance
        if ($user->total_income < $request->amount) {
            return back()->with('error', 'You do not have enough balance for this withdrawal.');
        }

        DB::table('users')->where('id', $request->id)->decrement('total_income', $request->amount);

        RoyaltyIncomeWallet::create([
            'user_id' => $request->id,
            'package_id' => Null,
            'amount' => $request->amount,
            'is_redeemed' => 0,
            'status' => 0,
        ]);

        //withdrawal Admin Amt
        AdminWallet::create([
            'admin_id' => 1,
            'from_user_id' => $request->id,
            'amount' => $request->amount,
            'type' => 4,
            'status' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('adminWallet')->with('success', 'Amount added successfully!');
    }


    public function save_user_product(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'user_id' => 'required|exists:users,id',
            'package_id' => 'required|exists:packages,id',
        ]);
        $address = $request->input('delivery_address');
        $phone_no = $request->input('phone_number');
        $email = $request->input('email');


        // Retrieve product, package, and user details
        $product = Product::find($request->product_id);
        $package = Package::find($product->package_id);
        $user = User::find($request->user_id);

        $productImage = asset($product->product_image);

        $productAmt = $package->amount;

        if ($product->id === 4) {
            $rate = 0.06;
            $rateper = 112;
            $per = '6%';
            $totalper = '12%';
        } else {
            $rate = 0.09;
            $rateper = 118;
            $per = '9%';
            $totalper = '18%';
        }

        $product_price = ($productAmt * 100) / $rateper;
        $product_price = round($product_price, 2);

        $cgst = round($product_price * $rate, 2);
        $sgst = round($product_price * $rate, 2);

        $GST = $cgst + $sgst;

        $totalprdamt = round($product_price + $GST);

        $lastInsertId = ProductDeliveryDetail::latest()->value('id');
        $receipt_number = $lastInsertId + 1;

        // Generate PDF Invoice
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('invoice', compact('user', 'product', 'package', 'address', 'phone_no', 'email', 'product_price', 'receipt_number', 'cgst', 'sgst', 'totalprdamt', 'per', 'totalper'));

        $invoicePath = 'assets/invoices/'; // Relative path
        $invoiceFileName = 'invoice_' . time() . '.pdf';

        $fullInvoicePath = public_path($invoicePath . $invoiceFileName); // Correct full path

        // Ensure the directory exists
        if (!File::exists(public_path($invoicePath))) {
            File::makeDirectory(public_path($invoicePath), 0775, true, true);
        }

        // Save PDF
        $pdf->save($fullInvoicePath);


        ProductDeliveryDetail::create([
            'user_id' => $request->user_id,
            'product_id' => $request->product_id,
            'package_id' => $product->package_id,
            'address' => $address,
            'phone_no' => $phone_no,
            'email' => $email,
            'date' =>  now(),
            'status' => 0,
            'invoice_path' => $invoicePath . $invoiceFileName,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Email details
        $data = [
            'user_name' => $user->name,
            'product_name' => $product->product_name,
            'package_name' => $package->name,
            'address' => $address,
            'phone_no' => $phone_no,
            'product_image' => $productImage,
        ];

        // User email
        Mail::send('emails.product_delivery', $data, function ($message) use ($email, $invoiceFileName, $fullInvoicePath) {
            $message->to($email)
                ->subject('Your Product Delivery Details')
                ->attach($fullInvoicePath, [
                    'as' => $invoiceFileName,
                    'mime' => 'application/pdf',
                ]);
        });

        // $deliveryPartnerEmail = "alangofenice@gmail.com";

        if ($product->id == 4) {

            $deliveryPartnerEmail = "impactvagamon@gmail.com";
        } elseif ($product->id == 5) {

            $deliveryPartnerEmail = "impactpromotionspl@gmail.com";
        } else {

            $deliveryPartnerEmail = "impactdq@gmail.com";
        }

        $deliveryData = [
            'user_name' => $user->name,
            'product_name' => $product->product_name,
            'package_name' => $package->name,
            'address' => $address,
            'phone_no' => $phone_no,
            'product_image' => $productImage,
            'user_email' => $email,
        ];

        // Send email to delivery partner
        Mail::send('emails.delivery_partner_notification', $deliveryData, function ($message) use ($deliveryPartnerEmail, $invoiceFileName, $fullInvoicePath) {
            $message->to($deliveryPartnerEmail)
                ->subject('New Product Delivery Request')
                ->attach($fullInvoicePath, [
                    'as' => $invoiceFileName,
                    'mime' => 'application/pdf',
                ]);
        });

        return redirect()->route('view_order')->with('success', 'Your product selection is successful');
    }
    public function user_product_list()
    {
        $userProductList = ProductDeliveryDetail::all();

        return view('Admin.user_product_list', compact('userProductList'));
    }

    public function levelIncomeTransfer(Request $request)
    {
        $userId = $request->input('user_id');
        $loggedUser = auth()->id();

        if ($userId != $loggedUser) {
            return redirect()->route('transferToWallet')->with('error', 'You cannot do this process.');
        }

        $totalLevelIncome = DB::table('sponsor_levels')
            ->where('sponsor_id', $userId)
            ->where('is_redeemed', 0)
            ->where('package_category', 'premium_package')
            ->sum('amount');

        if ($totalLevelIncome > 0) {

            User::where('id', $userId)->increment('total_income', $totalLevelIncome);

            WalletTransactionDetail::create([
                'user_id' => $userId,
                'type' => 1,
                'amount' => $totalLevelIncome,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('sponsor_levels')
                ->where('sponsor_id', $userId)
                ->where('package_category', 'premium_package')
                ->update(['is_redeemed' => 1]);
        }

        return redirect()->route('transferToWallet')->with('success', 'Level income transaction successfully completed.');
    }

    public function referralIncomeTransfer(Request $request)
    {
        $userId = $request->input('user_id');
        $loggedUser = auth()->id();

        if ($userId != $loggedUser) {
            return redirect()->route('transferToWallet')->with('error', 'You cannot do this process.');
        }

        $totalreferralIncome = DB::table('referral_incomes')
            ->where('sponsor_id', $userId)
            ->where('package_category', 'premium_package')
            ->where('status', 1)
            ->sum('income');

        if ($totalreferralIncome > 0) {

            User::where('id', $userId)->increment('total_income', $totalreferralIncome);

            WalletTransactionDetail::create([
                'user_id' => $userId,
                'type' => 2,
                'amount' => $totalreferralIncome,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('referral_incomes')
                ->where('sponsor_id', $userId)
                ->where('package_category', 'premium_package')
                ->update(['status' => 0]);
        }

        return redirect()->route('transferToWallet')->with('success', 'Referral income transaction successfully completed.');
    }

    public function rankIncomeTransfer(Request $request)
    {
        $userId = $request->input('user_id');
        $loggedUser = auth()->id();

        if ($userId != $loggedUser) {
            return redirect()->route('transferToWallet')->with('error', 'You cannot do this process.');
        }

        $totalRankIncome = DB::table('rank_incomes')
            ->where('user_id', $userId)
            ->where('status', 1)
            ->sum('amount');

        if ($totalRankIncome > 0) {

            User::where('id', $userId)->increment('total_income', $totalRankIncome);

            WalletTransactionDetail::create([
                'user_id' => $userId,
                'type' => 3,
                'amount' => $totalRankIncome,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('rank_incomes')
                ->where('user_id', $userId)
                ->update(['status' => 0]);
        }

        return redirect()->route('transferToWallet')->with('success', 'Rank income transaction successfully completed.');
    }
    public function royaltyIncomeTransfer(Request $request)
    {
        $userId = $request->input('user_id');
        $loggedUser = auth()->id();

        if ($userId != $loggedUser) {
            return redirect()->route('transferToWallet')->with('error', 'You cannot do this process.');
        }

        $totaRoyaltyIncome = DB::table('royalty_user_wallets')
            ->where('user_id', $userId)
            ->where('status', 1)
            ->sum('amount');

        if ($totaRoyaltyIncome > 0) {

            User::where('id', $userId)->increment('total_income', $totaRoyaltyIncome);

            WalletTransactionDetail::create([
                'user_id' => $userId,
                'type' => 4,
                'amount' => $totaRoyaltyIncome,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('royalty_user_wallets')
                ->where('user_id', $userId)
                ->update(['status' => 0]);
        }

        return redirect()->route('transferToWallet')->with('success', 'Royalty income transaction successfully completed.');
    }

    public function donationWallet()
    {
        $donationCurrentwallet = DonationWallet::where('status', 0)->sum('amount');
        $donationRedeemedwallet = DonationWallet::where('status', 1)->sum('amount');
        $donationwallet = DonationWallet::sum('amount');
        $donationlist = DonationWallet::orderBy('created_at', 'desc')->get();

        return view('Admin.donation_wallet', compact('donationwallet', 'donationlist', 'donationCurrentwallet', 'donationRedeemedwallet'));
    }
    public function user_donation()
    {
        $userId = auth()->id();
        $donationlist = DonationWallet::where('user_id', $userId)->get();

        return view('Admin.user_donation', compact('donationlist'));
    }

    public function yourAccount()
    {
        $userId = auth()->id();

        $user = User::find($userId);


        $totallevelbasic = DB::table('sponsor_levels')->where('sponsor_id', $userId)->where('package_category', 'basic_package')->sum('amount');
        $totallevelpremium = DB::table('sponsor_levels')->where('sponsor_id', $userId)->where('package_category', 'premium_package')->sum('amount');
        $totalrefferalincomebasic = ReferralIncome::where(['sponsor_id' => $userId])->where('package_category', 'basic_package')->sum('income');
        $totalrefferalincomepremium = ReferralIncome::where(['sponsor_id' => $userId])->where('package_category', 'premium_package')->sum('income');
        $BonusWallet = BonusWallet::where('user_id', $userId)->sum('amount');

        // $totallevelpremium = DB::table('sponsor_levels')->where('sponsor_id', $userId)->sum('amount');
        $totalrankincome = RankIncome::where(['user_id' => $userId])->sum('amount');
        // $totalrefferalincome = ReferralIncome::where(['sponsor_id' => $userId])->sum('income');
        $totalRoyaltyIncome = RoyaltyUserWallet::where('user_id', $userId)->sum('amount');

        $totalPrivilegeIncome = PrivilegeUserWallet::where('user_id', $userId)->sum('amount');
        $totalBoardIncome = BoardUserWallet::where('user_id', $userId)->sum('amount');
        $totalExecutiveIncome = ExecutiveUserWallet::where('user_id', $userId)->sum('amount');
        $totalIncentiveIncome = IncentiveUserWallet::where('user_id', $userId)->sum('amount');

        $withdrawals = WithdrawalRequest::where('user_id', $userId)
            ->where('status', 'approved')
            ->sum('amount');

        $transferIncome = ChildMotherPayment::where('child_id', $userId)->sum('amount');

        $admintransfreAMt = AdminWallet::where('from_user_id', $userId)->where('type', 9)->sum('amount');

        $totalBasicrankincome = BasicUserRankIncome::where(['user_id' => $userId])->sum('amount');

        $totals = RepurchaseWallet::where('user_id', $userId)
            ->where('is_redeemed', 0)
            ->whereIn('amount_type', [
                'Repurchase Income',
                'Self Purchase Income',
                'Franchisee Share Income'
            ])
            ->selectRaw('amount_type, SUM(amount) as total')
            ->groupBy('amount_type')
            ->pluck('total', 'amount_type');

        $repurchaseTotal = $totals['Repurchase Income'] ?? 0;
        $selfpurchaseTotal = $totals['Self Purchase Income'] ?? 0;
        $franchiseeTotal = $totals['Franchisee Share Income'] ?? 0;

        $totalwithdrawal = $withdrawals + $transferIncome;

        // $totalearnings = $user->total_income + $withdrawals + $transferIncome;
        $totalearnings = $admintransfreAMt + $totallevelbasic + $totallevelpremium + $totalrefferalincomebasic + $totalrefferalincomepremium + $BonusWallet + $totalrankincome + $totalRoyaltyIncome + $transferIncome + $totalPrivilegeIncome + $totalBoardIncome + $totalExecutiveIncome + $totalIncentiveIncome + $totalBasicrankincome + $repurchaseTotal + $franchiseeTotal + $selfpurchaseTotal;


        // dd($totalRoyaltyIncome);

        return view('Admin.yourAccount', compact('user', 'totallevelpremium', 'totallevelbasic', 'totalrankincome', 'totalrefferalincomebasic', 'totalrefferalincomepremium', 'totalRoyaltyIncome', 'totalearnings', 'totalwithdrawal', 'BonusWallet', 'totalPrivilegeIncome', 'totalBoardIncome', 'totalExecutiveIncome', 'totalIncentiveIncome', 'totalBasicrankincome', 'franchiseeTotal', 'selfpurchaseTotal', 'repurchaseTotal'));
    }

    public function donationTransfer(Request $request)
    {
        $userId = $request->input('userId');

        $donationwallet = DonationWallet::where('status', 0)->sum('amount');

        if ($donationwallet > 0) {

            User::where('id', $userId)->increment('total_income', $donationwallet);

            // Donation to admin wallet
            AdminWallet::create([
                'admin_id' => 1,
                'from_user_id' => 1,
                'amount' => $donationwallet,
                'type' => 7,
                'status' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);


            DB::table('donation_wallets')
                ->update(['status' => 1]);

            return redirect()->route('donationWallet')->with('success', 'Donation transfer completed successfully.');
        } else {

            return redirect()->route('donationWallet')->with('error', 'Your account has insufficient balance.');
        }
    }

    public function getUserSponsor($id)
    {
        $user = User::find($id);

        $sponsor = User::find($user->sponsor_id);

        if (!$user) {
            return response()->json(['status' => false, 'message' => 'User not found']);
        }
        $data = [
            'user_id' => $user->id,
            'sponsorName' => $sponsor->name,
            'sponsorId' => $sponsor->connection,
        ];

        return response()->json([
            'status' => 'success',
            'data' =>  $data,
        ]);
    }

    public function change_sponsor(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'user_id' => 'required|exists:users,id',
                'sponsor_id' => 'required|exists:users,connection',
            ]
        );


        $user = User::findOrFail($request->user_id);
        $userSponsor = User::where('connection', $request->sponsor_id)->first();

        $currentUser = $user;
        while ($currentUser && $currentUser->sponsor_id) {
            $sponsor = User::find($currentUser->sponsor_id);

            if (!$sponsor) {
                break;
            }

            $sponsor->is_pair_matched = 1;
            $sponsor->save();

            $currentUser = $sponsor;
        }


        $user->position = 'changed';
        $user->sponsor_id = $userSponsor->id;
        $user->save();

        return redirect()->route('userlist')->with('success', 'Sponsor Changed successfully');
    }

    public function holiday_package_list()
    {
        $bookingList = ProductDeliveryDetail::whereHas('product', function ($query) {
            $query->where('product_control', 1);
        })->get();

        return view('Admin.holiday_package_list', compact('bookingList'));
    }

    public function approvepackageAdmin(Request $request)
    {
        $orderId = $request->input('productListId');
        $status = $request->input('status');

        $productlist = ProductDeliveryDetail::where('id', $orderId)->first();

        if ($productlist) {
            // Update the status
            $productlist->status = $status;
            $productlist->save();

            // Fetch user, product, and package details
            $user = User::find($productlist->user_id);
            $product = Product::find($productlist->product_id);
            $package = Package::find($productlist->package_id);

            if ($user && $product && $package) {
                $email = $user->email;
                $productImage = asset($product->product_image);

                // Prepare email data
                $emailData = [
                    'user_name' => $user->name,
                    'product_name' => $product->product_name,
                    'package_name' => $package->name,
                    'address' => $productlist->address,
                    'phone_no' => $productlist->phone_no,
                    'product_image' => $productImage,
                    'status' => $status == 1 ? 'Confirmed' : 'Pending', // Customize status message
                ];

                // Send email to user
                Mail::send('emails.order_confirmation', $emailData, function ($message) use ($email) {
                    $message->to($email)
                        ->subject('Your Order Status Update');
                });
            }

            return redirect()->route('holiday_package_list')->with('success', 'Product status updated successfully and email sent.');
        }

        return redirect()->route('holiday_package_list')->with('error', 'Product details not found.');
    }
    public function Adminstatus(Request $request)
    {
        $orderId = $request->input('orderId');
        $status = $request->input('status');

        $productlist = ProductDeliveryDetail::where('id', $orderId)->first();

        if ($productlist) {
            // Update the status
            $productlist->status = $status;
            $productlist->save();

            return redirect()->route('holiday_package_list')->with('success', 'Product status updated successfully.');
        }

        return redirect()->route('holiday_package_list')->with('error', 'Product details not found.');
    }

    public function adminchangePassword(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'new_password' => 'required|string|min:6|confirmed',
        ]);


        $user = User::find($request->user_id);
        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Password updated successfully!');
    }

    public function basicLevelIncomeTransfer(Request $request)
    {
        $userId = $request->input('user_id');
        $loggedUser = auth()->id();

        if ($userId != $loggedUser) {
            return redirect()->route('transferToWallet')->with('error', 'You cannot do this process.');
        }

        $totalLevelIncome = DB::table('sponsor_levels')
            ->where('sponsor_id', $userId)
            ->where('package_category', 'basic_package')
            ->where('is_redeemed', 0)
            ->sum('amount');

        if ($totalLevelIncome > 0) {

            User::where('id', $userId)->increment('total_income', $totalLevelIncome);

            WalletTransactionDetail::create([
                'user_id' => $userId,
                'type' => 1,
                'amount' => $totalLevelIncome,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('sponsor_levels')
                ->where('sponsor_id', $userId)
                ->where('package_category', 'basic_package')
                ->update(['is_redeemed' => 1]);
        }

        return redirect()->route('transferToWallet')->with('success', 'Level income transaction successfully completed.');
    }

    public function basicReferralIncomeTransfer(Request $request)
    {
        $userId = $request->input('user_id');
        $loggedUser = auth()->id();

        if ($userId != $loggedUser) {
            return redirect()->route('transferToWallet')->with('error', 'You cannot do this process.');
        }

        $totalreferralIncome = DB::table('referral_incomes')
            ->where('sponsor_id', $userId)
            ->where('package_category', 'basic_package')
            ->where('status', 1)
            ->sum('income');

        if ($totalreferralIncome > 0) {

            User::where('id', $userId)->increment('total_income', $totalreferralIncome);

            WalletTransactionDetail::create([
                'user_id' => $userId,
                'type' => 2,
                'amount' => $totalreferralIncome,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('referral_incomes')
                ->where('sponsor_id', $userId)
                ->where('package_category', 'basic_package')
                ->update(['status' => 0]);
        }

        return redirect()->route('transferToWallet')->with('success', 'Referral income transaction successfully completed.');
    }

    public function adminToBonus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1',
        ]);

        // Get the logged-in user
        $user = Auth::user();

        // Check if the user has enough balance
        if ($user->total_income < $request->amount) {
            return back()->with('error', 'You do not have enough balance for this Process.');
        }

        DB::table('users')->where('id', $request->id)->decrement('total_income', $request->amount);

        BonusWallet::create([
            'user_id' => $request->id,
            'amount' => $request->amount,
            'type' => 0,
            'is_redeemed' => 0,
            'status' => 0,
        ]);

        //withdrawal Admin Amt
        AdminWallet::create([
            'admin_id' => 1,
            'from_user_id' => $request->id,
            'amount' => $request->amount,
            'type' => 8,
            'status' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('adminWallet')->with('success', 'Amount added successfully!');
    }

    public function bonus_users()
    {
        $packages = Package::where('package_code', 'premium_package')->pluck('id');
        $bonususers = UserPackage::whereIn('package_id', $packages)->get();

        return view('Admin.bonus_users', compact('bonususers'));
    }

    public function bonus_wallet()
    {
        $bonusWalletTotal = BonusWallet::where('user_id', 1)->sum('amount');
        $bonusWalletActive = BonusWallet::Where('is_redeemed', 0)->where('user_id', 1)->sum('amount');
        $bonusWalletInactive = BonusWallet::Where('is_redeemed', 1)->where('user_id', 1)->sum('amount');

        $bonusWallets = BonusWallet::orderBy('id', 'desc')->get();


        return view('Admin.bonus_wallet', compact('bonusWallets', 'bonusWalletTotal', 'bonusWalletActive', 'bonusWalletInactive'));
    }

    public function redeemBonusUsers()
    {
        $packages = Package::where('package_code', 'premium_package')->pluck('id');
        $activeUsers = UserPackage::whereIn('package_id', $packages)->get();

        $bonusWalletActive = BonusWallet::Where('is_redeemed', 0)->where('user_id', 1)->sum('amount');


        if ($activeUsers->count() == 0 || $bonusWalletActive == 0) {
            return response()->json(['message' => 'No active users or no funds available'], 400);
        }

        $splitAmount = $bonusWalletActive / $activeUsers->count();

        foreach ($activeUsers as $user) {

            BonusWallet::create([
                'user_id' => $user->user_id,
                'amount' => $splitAmount,
                'type' => 1,
                'is_redeemed' => 0,
                'status' => 0,
            ]);
        }

        BonusWallet::where('user_id', 1)->where('is_redeemed', 0)->update(['is_redeemed' => 1]);

        return redirect()->route('bonus_wallet')->with('success', 'Amount successfully added to premium Users.');
    }

    public function bonusIncomeTransfer(Request $request)
    {
        $userId = $request->input('user_id');
        $loggedUser = auth()->id();

        if ($userId != $loggedUser) {
            return redirect()->route('transferToWallet')->with('error', 'You cannot do this process.');
        }

        $bonus = BonusWallet::where('user_id', $userId)
            ->where('is_redeemed', 0)
            ->sum('amount');

        if ($bonus > 0) {

            User::where('id', $userId)->increment('total_income', $bonus);

            WalletTransactionDetail::create([
                'user_id' => $userId,
                'type' => 5,
                'amount' => $bonus,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('bonus_wallet')
                ->where('user_id', $userId)
                ->update(['is_redeemed' => 1]);
        }

        return redirect()->route('transferToWallet')->with('success', 'Bonus income transaction successfully completed.');
    }

    public function adminctrashMoney(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'trahing_amount' => 'required|numeric|min:0.01',
        ]);

        $user = User::find($request->user_id);

        if ($user->total_income < $request->trahing_amount) {
            return back()->with('error', 'User does not have enough balance.');
        }

        DB::transaction(function () use ($user, $request) {
            // Deduct balance
            $user->total_income -= $request->trahing_amount;
            $user->save();

            // Log in trash_money
            TrashMoney::create([
                'user_id' => $user->id,
                'amount' => $request->trahing_amount,
                'reason' => 'Admin trashed the amount',
                'trashed_by' => auth()->id(),
            ]);
        });

        return redirect()->back()->with('success', 'Money trashed successfully.');
    }

    public function trash_wallet()
    {
        $trashWalletTotal = TrashMoney::sum('amount');

        $trashWallets = TrashMoney::orderBy('id', 'desc')->get();


        return view('Admin.trash_wallet', compact('trashWalletTotal', 'trashWallets'));
    }

    public function admininctoUser(Request $request)
    {
        $request->validate([
            'userId' => 'required|exists:users,connection',
            'amount' => 'required|numeric|min:1',
        ]);

        // Get the logged-in user
        $user = User::where('connection', $request->userId)->first();
        $admin = User::where('id', 1)->first();

        // Check if the user has enough balance
        if ($admin->total_income < $request->amount) {
            return back()->with('error', 'You do not have enough balance for this Transfer.');
        }

        DB::table('users')->where('id', 1)->decrement('total_income', $request->amount);

        User::where('id', $user->id)->increment('total_income', $request->amount);

        //withdrawal Admin Amt
        AdminWallet::create([
            'admin_id' => 1,
            'from_user_id' => $user->id,
            'amount' => $request->amount,
            'type' => 9, // Transfer to user
            'status' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('adminWallet')->with('success', 'Amount added successfully!');
    }
    public function loginAsUser(User $user)
    {
        // Save the original admin ID to session so you can switch back later
        session(['impersonate' => auth()->id()]);

        // Log in as the user
        Auth::login($user);

        return redirect('/adminhome'); // Or the user's dashboard
    }

    public function adminToRank(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1',
            'rank_id' => 'required|exists:ranks,id',
        ]);

        // Get the logged-in user
        $user = Auth::user();

        // Check if the user has enough balance
        if ($user->total_income < $request->amount) {
            return back()->with('error', 'You do not have enough balance for this Process.');
        }

        DB::table('users')->where('id', $request->id)->decrement('total_income', $request->amount);

        CompanyRankIncome::create([
            'rank_id' => $request->rank_id,
            'amount' => $request->amount,
            'user_id' => $request->id,
            'package_id' => 1,
            'is_redeemed' => 0,
            'status' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        //withdrawal Admin Amt
        AdminWallet::create([
            'admin_id' => 1,
            'from_user_id' => $request->id,
            'amount' => $request->amount,
            'type' => 10,
            'status' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('adminWallet')->with('success', 'Amount added successfully!');
    }

    public function repurchaseIncomeTransfer(Request $request)
    {
        $userId = $request->input('user_id');
        $loggedUser = auth()->id();

        if ($userId != $loggedUser) {
            return redirect()->route('transferToWallet')->with('error', 'You cannot do this process.');
        }

        $bonus = RepurchaseWallet::where('user_id', $userId)
            ->where('is_redeemed', 0)
            ->sum('amount');
        if ($bonus > 0) {

            User::where('id', $userId)->increment('total_income', $bonus);

            WalletTransactionDetail::create([
                'user_id' => $userId,
                'type' => 6,
                'amount' => $bonus,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('repurchase_wallet')
                ->where('user_id', $userId)
                ->update(['is_redeemed' => 1]);
        }

        return redirect()->route('transferToWallet')->with('success', 'Bonus income transaction successfully completed.');
    }

    public function announcementupdate(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Ensure the folder exists
        if (!Storage::disk('public')->exists('announcements')) {
            Storage::disk('public')->makeDirectory('announcements');
        }

        // Save file
        $path = $request->file('image')->store('announcements', 'public');

        $announcement = Announcement::firstOrCreate([]);
        $announcement->update(['image' => $path]);

        return back()->with('success', 'Announcement updated successfully!');
    }

    public function addManuallyadmin(Request $request)
    {
        $request->validate([
            'admin_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1',
        ]);

        AdminWallet::create([
            'admin_id' => 1,
            'from_user_id' => $request->admin_id,
            'amount' => $request->amount,
            'type' => 11, // Manually add to wallet
            'status' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->where('id', $request->admin_id)->increment('total_income', $request->amount);

        return redirect()->route('adminWallet')->with('success', 'Amount added successfully!');
    }

    //-----------------------Update on 25/08/2025-----------------------

    public function privilege_users()
    {
        $privilegeusers = PrivilegeUser::all();
        return view('Admin.privilege_users', compact('privilegeusers'));
    }

    public function add_privilege_user(Request $request)
    {
        $request->validate([
            'userId' => 'required|exists:users,connection',
        ]);

        $user = User::where('connection', $request->userId)->first();

        $existUser = PrivilegeUser::where('user_id', $user->id)->first();

        if ($existUser) {
            return redirect()->route('privilege_users')->with('error', 'Already Exist.');
        }

        // Ensure user exists
        if (!$user) {
            return redirect()->route('privilege_users')->with('error', 'User not found.');
        }

        PrivilegeUser::create([
            'user_id' => $user->id,
            'status' => 1,
        ]);

        return redirect()->route('privilege_users')->with('success', 'User successfully added to royalty income.');
    }

    public function edit_privilege_user(Request $request)
    {
        $id = $request->input('id');
        $status = $request->input('status');

        $royaltyUser = PrivilegeUser::find($id);

        $royaltyUser->status = $status;
        $royaltyUser->save();

        return redirect()->route('privilege_users')->with('success', 'User status successfully changed.');
    }

    public function privilege_wallet()
    {
        $privilegeWalletTotal = PrivilegeIncomeWallet::sum('amount');
        $privilegeWalletActive = PrivilegeIncomeWallet::Where('is_redeemed', 0)->sum('amount');
        $privilegeWalletInactive = PrivilegeIncomeWallet::Where('is_redeemed', 1)->sum('amount');

        $privilegeWallets = PrivilegeIncomeWallet::orderBy('id', 'desc')->get();


        return view('Admin.privilege_wallet', compact('privilegeWallets', 'privilegeWalletTotal', 'privilegeWalletActive', 'privilegeWalletInactive'));
    }

    public function privilegeUsersAmtList()
    {
        $privilegeUsersAmtList = PrivilegeUserWallet::all();

        return view('Admin.privilegeUsersAmtList', compact('privilegeUsersAmtList'));
    }

    public function redeemPrivilegeUsers()
    {
        $privilegeWalletActive = PrivilegeIncomeWallet::where('is_redeemed', 0)->sum('amount');

        $activeUsers = PrivilegeUser::where('status', 1)->get();

        if ($activeUsers->count() == 0 || $privilegeWalletActive == 0) {
            // return response()->json(['message' => 'No active users or no funds available'], 400);
            return redirect()->route('privilege_wallet')->with('error', 'No active users or no funds available.');
        }

        $splitAmount = $privilegeWalletActive / $activeUsers->count();

        foreach ($activeUsers as $user) {

            // Stop this process. It is now being handled manually.
            // User::where('id', $user->user_id)->increment('total_income', $splitAmount);

            PrivilegeUserWallet::create([
                'user_id' => $user->user_id,
                'amount' => $splitAmount,
                'status' => 1
            ]);
        }

        PrivilegeIncomeWallet::where('is_redeemed', 0)->update(['is_redeemed' => 1]);

        return redirect()->route('privilegeUsersAmtList')->with('success', 'Amount successfully added to privilege Users.');
    }

    public function adminToPrivilege(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1',
        ]);

        // Get the logged-in user
        $user = Auth::user();

        // Check if the user has enough balance
        if ($user->total_income < $request->amount) {
            return back()->with('error', 'You do not have enough balance for this withdrawal.');
        }

        DB::table('users')->where('id', $request->id)->decrement('total_income', $request->amount);

        PrivilegeIncomeWallet::create([
            'user_id' => $request->id,
            'package_id' => Null,
            'amount' => $request->amount,
            'is_redeemed' => 0,
            'status' => 0,
        ]);

        //withdrawal Admin Amt
        AdminWallet::create([
            'admin_id' => 1,
            'from_user_id' => $request->id,
            'amount' => $request->amount,
            'type' => 12,  // add to privilege income
            'status' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('adminWallet')->with('success', 'Amount added successfully!');
    }

    private function privilegeIncomeAdd($userId, $package_id)
    {
        $package = Package::find($package_id);

        if ($package->package_code === 'premium_package' && $package->package_cat === 1) {
            $amount = 50;
        }

        PrivilegeIncomeWallet::create([
            'user_id' => $userId,
            'package_id' => $package_id,
            'amount' => $amount,
            'is_redeemed' => 0,
            'status' => 0,
        ]);
    }

    //---------------------- Board Income---------------------------

    public function board_users()
    {
        $boardusers = BoardUser::all();
        return view('Admin.board_users', compact('boardusers'));
    }

    public function add_board_user(Request $request)
    {
        $request->validate([
            'userId' => 'required|exists:users,connection',
        ]);

        $user = User::where('connection', $request->userId)->first();

        $existUser = BoardUser::where('user_id', $user->id)->first();

        if ($existUser) {
            return redirect()->route('board_users')->with('error', 'Already Exist.');
        }

        if (!$user) {
            return redirect()->route('board_users')->with('error', 'User not found.');
        }

        BoardUser::create([
            'user_id' => $user->id,
            'status' => 1,
        ]);

        return redirect()->route('board_users')->with('success', 'User successfully added to board income.');
    }

    public function edit_board_user(Request $request)
    {
        $id = $request->input('id');
        $status = $request->input('status');

        $boardUser = BoardUser::find($id);

        $boardUser->status = $status;
        $boardUser->save();

        return redirect()->route('board_users')->with('success', 'User status successfully changed.');
    }

    public function board_wallet()
    {
        $boardWalletTotal = BoardIncomeWallet::sum('amount');
        $boardWalletActive = BoardIncomeWallet::where('is_redeemed', 0)->sum('amount');
        $boardWalletInactive = BoardIncomeWallet::where('is_redeemed', 1)->sum('amount');

        $boardWallets = BoardIncomeWallet::orderBy('id', 'desc')->get();

        return view('Admin.board_wallet', compact('boardWallets', 'boardWalletTotal', 'boardWalletActive', 'boardWalletInactive'));
    }

    public function boardUsersAmtList()
    {
        $boardUsersAmtList = BoardUserWallet::all();

        return view('Admin.boardUsersAmtList', compact('boardUsersAmtList'));
    }

    public function redeemBoardUsers()
    {
        $boardWalletActive = BoardIncomeWallet::where('is_redeemed', 0)->sum('amount');

        $activeUsers = BoardUser::where('status', 1)->get();

        if ($activeUsers->count() == 0 || $boardWalletActive == 0) {
            return redirect()->route('board_wallet')->with('error', 'No active users or no funds available.');
        }

        $splitAmount = $boardWalletActive / $activeUsers->count();

        foreach ($activeUsers as $user) {
            BoardUserWallet::create([
                'user_id' => $user->user_id,
                'amount' => $splitAmount,
                'status' => 1
            ]);
        }

        BoardIncomeWallet::where('is_redeemed', 0)->update(['is_redeemed' => 1]);

        return redirect()->route('boardUsersAmtList')->with('success', 'Amount successfully added to Board Users.');
    }

    public function adminToBoard(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1',
        ]);

        $user = Auth::user();

        if ($user->total_income < $request->amount) {
            return back()->with('error', 'You do not have enough balance for this withdrawal.');
        }

        DB::table('users')->where('id', $request->id)->decrement('total_income', $request->amount);

        BoardIncomeWallet::create([
            'user_id' => $request->id,
            'package_id' => null,
            'amount' => $request->amount,
            'is_redeemed' => 0,
            'status' => 0,
        ]);

        AdminWallet::create([
            'admin_id' => 1,
            'from_user_id' => $request->id,
            'amount' => $request->amount,
            'type' => 13,  // add to board income
            'status' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('adminWallet')->with('success', 'Amount added successfully!');
    }

    private function boardIncomeAdd($userId, $package_id)
    {
        $package = Package::find($package_id);

        if ($package->package_code === 'premium_package' && $package->package_cat === 1) {
            $amount = 50;
        }

        BoardIncomeWallet::create([
            'user_id' => $userId,
            'package_id' => $package_id,
            'amount' => $amount,
            'is_redeemed' => 0,
            'status' => 0,
        ]);
    }

    // ------------------- Executive Income --------------------------

    public function executive_users()
    {
        $executiveUsers = ExecutiveUser::all();
        return view('Admin.executive_users', compact('executiveUsers'));
    }

    public function add_executive_user(Request $request)
    {
        $request->validate([
            'userId' => 'required|exists:users,connection',
        ]);

        $user = User::where('connection', $request->userId)->first();

        $existUser = ExecutiveUser::where('user_id', $user->id)->first();

        if ($existUser) {
            return redirect()->route('executive_users')->with('error', 'Already Exist.');
        }

        if (!$user) {
            return redirect()->route('executive_users')->with('error', 'User not found.');
        }

        ExecutiveUser::create([
            'user_id' => $user->id,
            'status'  => 1,
        ]);

        return redirect()->route('executive_users')->with('success', 'User successfully added to executive income.');
    }

    public function edit_executive_user(Request $request)
    {
        $id     = $request->input('id');
        $status = $request->input('status');

        $executiveUser = ExecutiveUser::find($id);

        $executiveUser->status = $status;
        $executiveUser->save();

        return redirect()->route('executive_users')->with('success', 'User status successfully changed.');
    }

    public function executive_wallet()
    {
        $executiveWalletTotal    = ExecutiveIncomeWallet::sum('amount');
        $executiveWalletActive   = ExecutiveIncomeWallet::where('is_redeemed', 0)->sum('amount');
        $executiveWalletInactive = ExecutiveIncomeWallet::where('is_redeemed', 1)->sum('amount');

        $executiveWallets = ExecutiveIncomeWallet::orderBy('id', 'desc')->get();

        return view('Admin.executive_wallet', compact('executiveWallets', 'executiveWalletTotal', 'executiveWalletActive', 'executiveWalletInactive'));
    }

    public function executiveUsersAmtList()
    {
        $executiveUsersAmtList = ExecutiveUserWallet::all();

        return view('Admin.executiveUsersAmtList', compact('executiveUsersAmtList'));
    }

    public function redeemExecutiveUsers()
    {
        $executiveWalletActive = ExecutiveIncomeWallet::where('is_redeemed', 0)->sum('amount');

        $activeUsers = ExecutiveUser::where('status', 1)->get();

        if ($activeUsers->count() == 0 || $executiveWalletActive == 0) {
            return redirect()->route('executive_wallet')->with('error', 'No active users or no funds available.');
        }

        $splitAmount = $executiveWalletActive / $activeUsers->count();

        foreach ($activeUsers as $user) {
            ExecutiveUserWallet::create([
                'user_id' => $user->user_id,
                'amount'  => $splitAmount,
                'status'  => 1
            ]);
        }

        ExecutiveIncomeWallet::where('is_redeemed', 0)->update(['is_redeemed' => 1]);

        return redirect()->route('executiveUsersAmtList')->with('success', 'Amount successfully added to Executive Users.');
    }

    public function adminToExecutive(Request $request)
    {
        $request->validate([
            'id'     => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1',
        ]);

        $user = Auth::user();

        if ($user->total_income < $request->amount) {
            return back()->with('error', 'You do not have enough balance for this withdrawal.');
        }

        DB::table('users')->where('id', $request->id)->decrement('total_income', $request->amount);

        ExecutiveIncomeWallet::create([
            'user_id'     => $request->id,
            'package_id'  => null,
            'amount'      => $request->amount,
            'is_redeemed' => 0,
            'status'      => 0,
        ]);

        AdminWallet::create([
            'admin_id'     => 1,
            'from_user_id' => $request->id,
            'amount'       => $request->amount,
            'type'         => 14, // different type code for executive income
            'status'       => 0,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        return redirect()->route('adminWallet')->with('success', 'Amount added successfully!');
    }

    private function executiveIncomeAdd($userId, $package_id)
    {
        $package = Package::find($package_id);

        if ($package->package_code === 'premium_package' && $package->package_cat === 1) {
            $amount = 50;
        }

        ExecutiveIncomeWallet::create([
            'user_id'     => $userId,
            'package_id'  => $package_id,
            'amount'      => $amount,
            'is_redeemed' => 0,
            'status'      => 0,
        ]);
    }

    // ---------------- Incentive Income----------------

    public function incentive_users()
    {
        $incentiveUsers = IncentiveUser::all();
        return view('Admin.incentive_users', compact('incentiveUsers'));
    }

    public function add_incentive_user(Request $request)
    {
        $request->validate([
            'userId' => 'required|exists:users,connection',
        ]);

        $user = User::where('connection', $request->userId)->first();

        $existUser = IncentiveUser::where('user_id', $user->id)->first();

        if ($existUser) {
            return redirect()->route('incentive_users')->with('error', 'Already Exist.');
        }

        if (!$user) {
            return redirect()->route('incentive_users')->with('error', 'User not found.');
        }

        IncentiveUser::create([
            'user_id' => $user->id,
            'status'  => 1,
        ]);

        return redirect()->route('incentive_users')->with('success', 'User successfully added to incentive income.');
    }

    public function edit_incentive_user(Request $request)
    {
        $id     = $request->input('id');
        $status = $request->input('status');

        $incentiveUser = IncentiveUser::find($id);

        $incentiveUser->status = $status;
        $incentiveUser->save();

        return redirect()->route('incentive_users')->with('success', 'User status successfully changed.');
    }

    public function incentive_wallet()
    {
        $incentiveWalletTotal    = IncentiveIncomeWallet::sum('amount');
        $incentiveWalletActive   = IncentiveIncomeWallet::where('is_redeemed', 0)->sum('amount');
        $incentiveWalletInactive = IncentiveIncomeWallet::where('is_redeemed', 1)->sum('amount');

        $incentiveWallets = IncentiveIncomeWallet::orderBy('id', 'desc')->get();

        return view('Admin.incentive_wallet', compact('incentiveWallets', 'incentiveWalletTotal', 'incentiveWalletActive', 'incentiveWalletInactive'));
    }

    public function incentiveUsersAmtList()
    {
        $incentiveUsersAmtList = IncentiveUserWallet::all();

        return view('Admin.incentiveUsersAmtList', compact('incentiveUsersAmtList'));
    }

    public function redeemIncentiveUsers()
    {
        $incentiveWalletActive = IncentiveIncomeWallet::where('is_redeemed', 0)->sum('amount');

        $activeUsers = IncentiveUser::where('status', 1)->get();

        if ($activeUsers->count() == 0 || $incentiveWalletActive == 0) {
            return redirect()->route('incentive_wallet')->with('error', 'No active users or no funds available.');
        }

        $splitAmount = $incentiveWalletActive / $activeUsers->count();

        foreach ($activeUsers as $user) {
            IncentiveUserWallet::create([
                'user_id' => $user->user_id,
                'amount'  => $splitAmount,
                'status'  => 1
            ]);
        }

        IncentiveIncomeWallet::where('is_redeemed', 0)->update(['is_redeemed' => 1]);

        return redirect()->route('incentiveUsersAmtList')->with('success', 'Amount successfully added to Incentive Users.');
    }

    public function adminToIncentive(Request $request)
    {
        $request->validate([
            'id'     => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1',
        ]);

        $user = Auth::user();

        if ($user->total_income < $request->amount) {
            return back()->with('error', 'You do not have enough balance for this withdrawal.');
        }

        DB::table('users')->where('id', $request->id)->decrement('total_income', $request->amount);

        IncentiveIncomeWallet::create([
            'user_id'     => $request->id,
            'package_id'  => null,
            'amount'      => $request->amount,
            'is_redeemed' => 0,
            'status'      => 0,
        ]);

        AdminWallet::create([
            'admin_id'     => 1,
            'from_user_id' => $request->id,
            'amount'       => $request->amount,
            'type'         => 15, // type code for incentive income
            'status'       => 0,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        return redirect()->route('adminWallet')->with('success', 'Amount added successfully!');
    }

    public function privilegeIncomeTransfer(Request $request)
    {
        $userId = $request->input('user_id');
        $loggedUser = auth()->id();

        if ($userId != $loggedUser) {
            return redirect()->route('transferToWallet')->with('error', 'You cannot do this process.');
        }

        $privilege = PrivilegeUserWallet::where('user_id', $userId)
            ->where('status', 1)
            ->sum('amount');

        if ($privilege > 0) {

            User::where('id', $userId)->increment('total_income', $privilege);

            WalletTransactionDetail::create([
                'user_id' => $userId,
                'type' => 7, // Privilege income
                'amount' => $privilege,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            PrivilegeUserWallet::where('user_id', $userId)
                ->update(['status' => 0]);
        }

        return redirect()->route('transferToWallet')->with('success', 'Privilege income transaction successfully completed.');
    }

    public function boardIncomeTransfer(Request $request)
    {
        $userId = $request->input('user_id');
        $loggedUser = auth()->id();

        if ($userId != $loggedUser) {
            return redirect()->route('transferToWallet')->with('error', 'You cannot do this process.');
        }

        $board = BoardUserWallet::where('user_id', $userId)
            ->where('status', 1)
            ->sum('amount');

        if ($board > 0) {

            User::where('id', $userId)->increment('total_income', $board);

            WalletTransactionDetail::create([
                'user_id' => $userId,
                'type' => 8, // Board income
                'amount' => $board,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            BoardUserWallet::where('user_id', $userId)
                ->update(['status' => 0]);
        }

        return redirect()->route('transferToWallet')->with('success', 'Board income transaction successfully completed.');
    }

    public function executiveIncomeTransfer(Request $request)
    {
        $userId = $request->input('user_id');
        $loggedUser = auth()->id();

        if ($userId != $loggedUser) {
            return redirect()->route('transferToWallet')->with('error', 'You cannot do this process.');
        }

        $executive = ExecutiveUserWallet::where('user_id', $userId)
            ->where('status', 1)
            ->sum('amount');

        if ($executive > 0) {

            User::where('id', $userId)->increment('total_income', $executive);

            WalletTransactionDetail::create([
                'user_id' => $userId,
                'type' => 9, // executive income
                'amount' => $executive,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            ExecutiveUserWallet::where('user_id', $userId)
                ->update(['status' => 0]);
        }

        return redirect()->route('transferToWallet')->with('success', 'Executive income transaction successfully completed.');
    }

    public function incentiveIncomeTransfer(Request $request)
    {
        $userId = $request->input('user_id');
        $loggedUser = auth()->id();

        if ($userId != $loggedUser) {
            return redirect()->route('transferToWallet')->with('error', 'You cannot do this process.');
        }

        $incentive = IncentiveUserWallet::where('user_id', $userId)
            ->where('status', 1)
            ->sum('amount');

        if ($incentive > 0) {

            User::where('id', $userId)->increment('total_income', $incentive);

            WalletTransactionDetail::create([
                'user_id' => $userId,
                'type' => 10, // incentive income
                'amount' => $incentive,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            IncentiveUserWallet::where('user_id', $userId)
                ->update(['status' => 0]);
        }

        return redirect()->route('transferToWallet')->with('success', 'Incentive income transaction successfully completed.');
    }

    public function privilege_user_wallet()
    {
        $user = auth()->user();

        if ($user->role === 'superadmin') {
            $users = PrivilegeUserWallet::all();
            $totalAmount = $users->sum('amount');
        } else {
            $users = PrivilegeUserWallet::where('user_id', $user->id)->get();
            $totalAmount = $users->sum('amount');
        }
        return view('Admin.privilege_user_incentive', compact('users', 'totalAmount'));
    }
    public function board_user_wallet()
    {
        $user = auth()->user();

        if ($user->role === 'superadmin') {
            $users = BoardUserWallet::all();
            $totalAmount = $users->sum('amount');
        } else {
            $users = BoardUserWallet::where('user_id', $user->id)->get();
            $totalAmount = $users->sum('amount');
        }
        return view('Admin.board_user_incentive', compact('users', 'totalAmount'));
    }
    public function executive_user_wallet()
    {
        $user = auth()->user();

        if ($user->role === 'superadmin') {
            $users = ExecutiveUserWallet::all();
            $totalAmount = $users->sum('amount');
        } else {
            $users = ExecutiveUserWallet::where('user_id', $user->id)->get();
            $totalAmount = $users->sum('amount');
        }
        return view('Admin.executive_user_incentive', compact('users', 'totalAmount'));
    }
    public function incentive_user_wallet()
    {
        $user = auth()->user();

        if ($user->role === 'superadmin') {
            $users = IncentiveUserWallet::all();
            $totalAmount = $users->sum('amount');
        } else {
            $users = IncentiveUserWallet::where('user_id', $user->id)->get();
            $totalAmount = $users->sum('amount');
        }
        return view('Admin.incentive_user_incentive', compact('users', 'totalAmount'));
    }

    public function bonus_user_wallet()
    {
        $user = auth()->user();

        if ($user->role === 'superadmin') {
            $users = BonusWallet::where('user_id', '!=', 1)->get();
            $totalAmount = $users->sum('amount');
        } else {
            $users = BonusWallet::where('user_id', $user->id)->get();
            $totalAmount = $users->sum('amount');
        }
        return view('Admin.special_user_incentive', compact('users', 'totalAmount'));
    }

    //----------------End 25/08/2025------------------

    public function redeemBoardCompany(Request $request)
    {
        $boardIncomeTotal = BoardIncomeWallet::where('is_redeemed', 0)
            ->sum('amount');

        if ($boardIncomeTotal > 0) {
            BoardIncomeWallet::create([
                'user_id' => 1,
                'package_id' => null,
                'amount' => $boardIncomeTotal,
                'is_redeemed' => 1,
                'status' => 0,
            ]);

            AdminWallet::create([
                'admin_id' => 1,
                'from_user_id' => 1,
                'amount' => $boardIncomeTotal,
                'type' => 16,
                'status' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            User::where('id', 1)->increment('total_income', $boardIncomeTotal);

            BoardIncomeWallet::where('is_redeemed', 0)->update(['is_redeemed' => 1]);

            return redirect()->route('board_wallet')->with('success', 'Company board income add successfully.');
        } else {
            return redirect()->route('board_wallet')->with('error', 'no board income available to distribute.');
        }
    }
    public function redeemExecutiveCompany(Request $request)
    {
        $executiveIncomeTotal = ExecutiveIncomeWallet::where('is_redeemed', 0)
            ->sum('amount');

        if ($executiveIncomeTotal > 0) {
            ExecutiveIncomeWallet::create([
                'user_id' => 1,
                'package_id' => null,
                'amount' => $executiveIncomeTotal,
                'is_redeemed' => 1,
                'status' => 0,
            ]);

            AdminWallet::create([
                'admin_id' => 1,
                'from_user_id' => 1,
                'amount' => $executiveIncomeTotal,
                'type' => 17,
                'status' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            User::where('id', 1)->increment('total_income', $executiveIncomeTotal);

            ExecutiveIncomeWallet::where('is_redeemed', 0)->update(['is_redeemed' => 1]);

            return redirect()->route('board_wallet')->with('success', 'Company Executive income add successfully.');
        } else {
            return redirect()->route('board_wallet')->with('error', 'no Executive income available to distribute.');
        }
    }

    public function redeemPrivilegeCompany(Request $request)
    {
        $privilegeIncomeTotal = PrivilegeIncomeWallet::where('is_redeemed', 0)
            ->sum('amount');

        if ($privilegeIncomeTotal > 0) {
            PrivilegeIncomeWallet::create([
                'user_id' => 1,
                'package_id' => null,
                'amount' => $privilegeIncomeTotal,
                'is_redeemed' => 1,
                'status' => 0,
            ]);

            AdminWallet::create([
                'admin_id' => 1,
                'from_user_id' => 1,
                'amount' => $privilegeIncomeTotal,
                'type' => 18,
                'status' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            User::where('id', 1)->increment('total_income', $privilegeIncomeTotal);

            PrivilegeIncomeWallet::where('is_redeemed', 0)->update(['is_redeemed' => 1]);

            return redirect()->route('privilege_wallet')->with('success', 'Company Privilege income add successfully.');
        } else {
            return redirect()->route('privilege_wallet')->with('error', 'no Privilege income available to distribute.');
        }
    }

    public function rank_histories()
    {
        $rankHistories = UserRankHistory::where('status', 1)->latest()->get();
        return view('Admin.rank_histories', compact('rankHistories'));
    }

    public function premium_rank_list()
    {
        $pre_rank_list = User::where('role', 'user')->where('rank_id', '>=', 2)->latest()->get();
        return view('Admin.premium_rank_list', compact('pre_rank_list'));
    }

    public function edit_user_rankStatus(Request $request)
    {
        $id = $request->input('id');
        $status = $request->input('status');

        $User = User::find($id);

        $User->rank_status = $status;
        $User->save();

        return redirect()->route('premium_rank_list')->with('success', 'User status successfully changed.');
    }

    //basic rank changes

    public function basicCompanyRank_income()
    {
        $rankIncomes = BasicRankIncome::with('rank:id,name')
            ->select(
                'rank_id',
                DB::raw('SUM(CASE WHEN is_redeemed = 1 THEN amount ELSE 0 END) as redeemed_amount'), // Sum for redeemed amounts
                DB::raw('SUM(CASE WHEN is_redeemed = 0 THEN amount ELSE 0 END) as pending_amount'), // Sum for pending amounts
                DB::raw('SUM(amount) as total_amount') // Total amount (all)
            )
            ->groupBy('rank_id')
            ->orderBy('rank_id')
            ->get();

        return view('BasicRank.basicCompanyrank_incomes', compact('rankIncomes'));
    }

    public function basicRedeemToCompany(Request $request)
    {
        $rank_id = $request->input('rankC_id');

        $rankIncomeTotal = BasicRankIncome::where('rank_id', $rank_id)
            ->where('is_redeemed', 0)
            ->sum('amount');

        if ($rankIncomeTotal > 0) {
            BasicUserRankIncome::create([
                'user_id' => 1,
                'rank_id' => $rank_id,
                'amount' => $rankIncomeTotal,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            AdminWallet::create([
                'admin_id' => 1,
                'amount' => $rankIncomeTotal,
                'type' => 19, //basic rank income
                'status' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            User::where('id', 1)->increment('total_income', $rankIncomeTotal);

            BasicRankIncome::where('rank_id', $rank_id)
                ->where('is_redeemed', 0)
                ->update(['is_redeemed' => 1]);

            $rankIncomes = BasicUserRankIncome::where('rank_id', $rank_id)->get();

            session()->flash('success', 'Company rank income add successfully.');
            return view('BasicRank.basic_UserRank_income', compact('rankIncomes'));
        } else {
            return redirect()->route('basicCompanyrank_incomes')->with('error', 'no rank income available to distribute.');
        }
    }
    public function basicRedeemToUser(Request $request)
    {
        $rank_id = $request->input('rank_id');

        $userCount = BasicRankAchieve::where('basic_rank_id', $rank_id)->where('rank_status', 1)->count();
        $rankIncomeTotal = BasicRankIncome::where('rank_id', $rank_id)
            ->where('is_redeemed', 0)
            ->sum('amount');

        if ($userCount == 0 || $rankIncomeTotal == 0) {
            return redirect()->route('basicCompanyrank_incomes')->with('error', 'No users found or no rank income available to distribute.');
        }


        $baseAmountPerUser = ($userCount > 0) ? floor($rankIncomeTotal / $userCount) : 0;

        $paidtotal = $baseAmountPerUser * $userCount;
        $unpaid = $rankIncomeTotal - $paidtotal;

        if ($unpaid > 0) {
            AdminWallet::create([
                'admin_id' => 1,
                'amount' => $unpaid,
                'type' => 20, //basic rank balance
                'status' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            User::where('id', 1)->increment('total_income', $unpaid);
        }

        $users = BasicRankAchieve::where('basic_rank_id', $rank_id)
            ->where('rank_status', 1)
            ->get();

        foreach ($users as $user) {

            BasicUserRankIncome::create([
                'user_id' => $user->user_id,
                'rank_id' => $rank_id,
                'amount' => $baseAmountPerUser,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Stop this process. It is now being handled manually.
            // User::where('id', $user->id)->increment('total_income', $baseAmountPerUser);
        }

        BasicRankIncome::where('rank_id', $rank_id)
            ->where('is_redeemed', 0)
            ->update(['is_redeemed' => 1]);

        $rankIncomes = BasicUserRankIncome::where('rank_id', $rank_id)
            ->get();

        session()->flash('success', 'User rank income add successfully.');
        return view('BasicRank.basic_UserRank_income', compact('rankIncomes'));
    }

    public function basicrankTotal($rank_id)
    {
        $rankIncomeDetails = BasicRankIncome::where('rank_id', $rank_id)->get();

        return view('BasicRank.basicCompanyRank_details', compact('rankIncomeDetails'));
    }
    public function basicrankRedeemed($rank_id)
    {
        $rankIncomes = BasicUserRankIncome::where('rank_id', $rank_id)
            ->get();

        return view('BasicRank.basic_UserRank_income', compact('rankIncomes'));
    }
    public function basicrankPending($rank_id)
    {
        $rankIncomeDetails = BasicRankIncome::where('rank_id', $rank_id)
            ->where('is_redeemed', 0)
            ->get();

        return view('BasicRank.basicCompanyRank_details', compact('rankIncomeDetails'));
    }

    public function BasicRank_details()
    {
        $userCounts = DB::table('basic_rank_achieves')
            ->select('basic_rank_id', DB::raw('COUNT(*) as user_count'))
            ->groupBy('basic_rank_id')
            ->get()
            ->pluck('user_count', 'basic_rank_id')
            ->toArray();

        $allRanks = [6, 5, 4, 3, 2];

        $ranks = DB::table('basic_ranks')
            ->whereIn('id', $allRanks)
            ->pluck('name', 'id')
            ->toArray();

        $rankData = array_map(function ($rankId) use ($ranks, $userCounts) {
            return [
                'id' => $rankId,
                'name' => $ranks[$rankId] ?? 'Unknown Rank',
                'user_count' => $userCounts[$rankId] ?? 0, // User Count
            ];
        }, $allRanks);

        return view('BasicRank.basic_rank_details', compact('rankData'));
    }

    public function basicRankIncomeTransfer(Request $request)
    {
        $userId = $request->input('user_id');
        $loggedUser = auth()->id();

        if ($userId != $loggedUser) {
            return redirect()->route('transferToWallet')->with('error', 'You cannot do this process.');
        }

        $totalRankIncome = DB::table('basic_user_rank_incomes')
            ->where('user_id', $userId)
            ->where('status', 1)
            ->sum('amount');

        if ($totalRankIncome > 0) {

            User::where('id', $userId)->increment('total_income', $totalRankIncome);

            WalletTransactionDetail::create([
                'user_id' => $userId,
                'type' => 11, //basic rank income
                'amount' => $totalRankIncome,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('basic_user_rank_incomes')
                ->where('user_id', $userId)
                ->update(['status' => 0]);
        }

        return redirect()->route('transferToWallet')->with('success', 'Basic Rank income transaction successfully completed.');
    }

    public function edit_user_basicRankStatus(Request $request)
    {
        $id = $request->input('id');
        $status = $request->input('status');

        $User = BasicRankAchieve::find($id);

        $User->rank_status = $status;
        $User->save();

        return redirect()->route('basicRank_income')->with('success', 'User status successfully changed.');
    }

    public function adminToBasicRank(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1',
            'basic_rank_id' => 'required|exists:basic_ranks,id',
        ]);

        // Get the logged-in user
        $user = Auth::user();

        // Check if the user has enough balance
        if ($user->total_income < $request->amount) {
            return back()->with('error', 'You do not have enough balance for this Process.');
        }

        DB::table('users')->where('id', $request->id)->decrement('total_income', $request->amount);

        BasicRankIncome::create([
            'rank_id' => $request->basic_rank_id,
            'amount' => $request->amount,
            'user_id' => $request->id,
            'package_id' => 1,
            'is_redeemed' => 0,
            'status' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        //withdrawal Admin Amt
        AdminWallet::create([
            'admin_id' => 1,
            'from_user_id' => $request->id,
            'amount' => $request->amount,
            'type' => 21, // pay to basic Rank
            'status' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('adminWallet')->with('success', 'Amount added successfully!');
    }

    public function getUsersBybasicRank($rank)
    {
        $users = BasicRankAchieve::where('basic_rank_id', $rank)->get();
        $rank = BasicRank::where('id', $rank)->first();
        return view('BasicRank/basicRank_user', compact('users', 'rank'));
    }

    public function offlinePurchase_list()
    {
        $purchaseList = OfflineProductBill::latest()->get();

        return view('Admin.offline_purchase_list', compact('purchaseList'));
    }

    public function repurchase_wallet()
    {
        $repurchaseWalletTotal = RepurchaseWallet::Where('user_id', 1)->sum('amount');
        $repurchaseWalletActive = RepurchaseWallet::Where('user_id', 1)->Where('is_redeemed', 0)->sum('amount');
        $repurchaseWalletInactive = RepurchaseWallet::Where('user_id', 1)->Where('is_redeemed', 1)->sum('amount');

        $repurchaseData = RepurchaseWallet::latest()->get();
        return view('Admin.repurchase_wallet', compact('repurchaseWalletTotal', 'repurchaseWalletActive', 'repurchaseWalletInactive', 'repurchaseData'));
    }

    public function getLocalBodies(Request $request)
    {
        $localBodies = LocalBody::where('district_id', $request->district_id)
            ->where('lbt_id', $request->type_id)
            ->select('id', 'name') // adjust if your column names differ
            ->get();

        return response()->json($localBodies);
    }

    public function shopCoupn_list()
    {
        $shopCoupn_list = ShopCoupon::latest()->get();

        return view('Admin.shopCoupn_list', compact('shopCoupn_list'));
    }

    public function shop_list(Request $request)
    {
        $query = Shop::query();

        // Filter by state
        if ($request->filled('state_id')) {
            $query->where('state_id', $request->state_id);
        }

        // Filter by district
        if ($request->filled('district_id')) {
            $query->where('district_id', $request->district_id);
        }

        $shops = $query->get();
        $states = State::all();
        $districts = [];

        // Load districts if a state is selected
        if ($request->filled('state_id')) {
            $districts = District::where('state_id', $request->state_id)->get();
        }

        return view('Admin.shoplist', compact('shops', 'states', 'districts'));
    }

    public function franchisee()
    {
        $district = District::get();
        $localbodytype = LocalBodyType::where('lbt_code', 1)->get();
        $franchisee = Franchisee::all();
        $usedLocalBodies = Franchisee::pluck('lb_id')->toArray();
        return view('Admin.franchisee', compact('district', 'localbodytype', 'franchisee', 'usedLocalBodies'));
    }

    public function add_franchisee(Request $request)
    {
        $request->validate([
            'district' => 'required|integer',
            'localbodytype' => 'required|integer',
            'localbody' => 'required|integer',
            'userId' => 'required|exists:users,connection',
        ]);

        $user  = User::where('connection', $request->userId)->first();;

        Franchisee::create([
            'lbt_id' => $request->localbodytype,
            'lb_id' => $request->localbody,
            'user_id' => $user->id,
            'status' => 1, // assuming default status
        ]);

        return redirect()->back()->with('success', 'Franchisee added successfully.');
    }

    public function shopReceipt_list()
    {
        $shopReceipt_list = ShopReceipt::latest()->get();

        return view('Admin.shop_receipt_accept', compact('shopReceipt_list'));
    }

    public function shopReceiptApprove(Request $request)
    {
        $receiptId = $request->input('receiptId');
        $status = $request->input('status');
        $note = $request->input('reject_note');

        // Find the receipt record
        $shopReceipt = ShopReceipt::find($receiptId);

        if (!$shopReceipt) {
            return response()->json(['error' => 'Receipt not found'], 404);
        }

        // Only process coupon logic if status = approved
        if ($status == '1') {

            // Find existing coupon for this shop
            $shopCoupon = ShopCoupon::where('shop_id', $shopReceipt->shop_id)->first();

            // dd($shopCoupon);

            if ($shopCoupon) {
                // ✅ Update existing coupon
                $balanceAmount = $shopReceipt->amount + $shopCoupon->balance;


                $shopCoupon->amount = $shopReceipt->amount;
                $shopCoupon->balance = $balanceAmount;
                $shopCoupon->recharge_count = ($shopCoupon->recharge_count ?? 0) + 1;
                $shopCoupon->last_recharged_at = now();
                $shopCoupon->save();
            } else {
                // ✅ Create new coupon
                ShopCoupon::create([
                    'shop_id' => $shopReceipt->shop_id,
                    'coupon_code' => 'CPN-' . strtoupper(uniqid()),
                    'amount' => $shopReceipt->amount,
                    'balance' => $shopReceipt->amount,
                    'recharge_count' => 1,
                    'status' => 1,
                    'last_recharged_at' => now(),
                ]);
            }
        }

        // ✅ Update receipt status and note
        $shopReceipt->status = $status;
        $shopReceipt->remarks = $note;
        $shopReceipt->save();

        return redirect()->route('shopReceipt_list')->with('success', 'Receipt processed successfully!');
    }

    public function franchisee_income_list()
    {

        $user = auth()->user();

        if ($user->role === 'superadmin') {
            $users = RepurchaseWallet::where('amount_type', 'Franchisee Share Income')->where('status', '!=', 2)->latest()->get();
            $totalAmount = $users->sum('amount');
        } else {
            $users = RepurchaseWallet::where('user_id', $user->id)->where('amount_type', 'Franchisee Share Income')->where('status', '!=', 2)->latest()->get();
            $totalAmount = $users->sum('amount');
        }
        return view('Admin.franchisee_income_list', compact('users', 'totalAmount'));
    }

    public function selfpurchase_income_list()
    {
        $user = auth()->user();

        if ($user->role === 'superadmin') {
            $users = RepurchaseWallet::where('amount_type', 'Self Purchase Income')->where('status', '!=', 2)->latest()->get();
            $totalAmount = $users->sum('amount');
        } else {
            $users = RepurchaseWallet::where('user_id', $user->id)->where('amount_type', 'Self Purchase Income')->where('status', '!=', 2)->latest()->get();
            $totalAmount = $users->sum('amount');
        }
        return view('Admin.selfpurchase_income_list', compact('users', 'totalAmount'));
    }

    public function repurchase_list()
    {
        $user = auth()->user();

        if ($user->role === 'superadmin') {
            $users = RepurchaseWallet::where('amount_type', 'Repurchase Income')->where('status', '!=', 2)->latest()->get();
            $totalAmount = $users->sum('amount');
        } else {
            $users = RepurchaseWallet::where('user_id', $user->id)->where('amount_type', 'Repurchase Income')->where('status', '!=', 2)->latest()->get();
            $totalAmount = $users->sum('amount');
        }
        return view('Admin.repurchase_list', compact('users', 'totalAmount'));
    }

    public function redeemRepurchase()
    {
        $userId = 1;
        $loggedUser = auth()->id();
        if ($userId != $loggedUser) {
            return redirect()->route('repurchase_wallet')->with('error', 'You cannot do this process.');
        }

        $bonus = RepurchaseWallet::where('user_id', $userId)
            ->where('is_redeemed', 0)
            ->sum('amount');

        $groupedRepurchases = RepurchaseWallet::select('product_ordered_user_id', DB::raw('SUM(amount) as total_amount'))
            ->where('user_id', $userId)
            ->where('is_redeemed', 0)
            ->groupBy('product_ordered_user_id')
            ->get();
        if ($bonus > 0) {
            foreach ($groupedRepurchases as $repurchase) {
                AdminWallet::create([
                    'admin_id' => 1,
                    'from_user_id' => $repurchase->product_ordered_user_id,
                    'amount' => $repurchase->total_amount,
                    'type' => 22,
                    'status' => 0,
                ]);
            }
            User::where('id', $userId)->increment('total_income', $bonus);
            DB::table('repurchase_wallet')
                ->where('user_id', $userId)
                ->update(['is_redeemed' => 1]);
        }
        return redirect()->route('repurchase_wallet')->with('success', 'Bonus income transaction successfully completed.');
    }

    public function userRegistrationForm()
    {
        return view('Admin/user_registration_form');
    }

    public function store_user_simple(Request $request)
    {
        $name = $request->input('name');
        $cleanedName = preg_replace('/\s+/', '', $name);
        $firstTwoLetters = strtoupper(substr($cleanedName, 0, 2));
        $lastUser = User::latest('id')->first();
        $uniqueId = $lastUser->id + 1;
        $user_code = 'V' . $firstTwoLetters . $uniqueId;

        $request->merge(['connection' => $user_code]);

        $validator = Validator::make(
            $request->all(),
            [
                'sponsor_id' => 'required|exists:users,connection',
                'name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
                'email' => 'required|string|email|max:255',
                'phone_no' => 'required|string|max:10',
                'address' => 'required|string',
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'confirmed',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\W).+$/'
                ],
                'connection' => 'required|string|max:255|unique:users,connection',
                'pincode' => 'required|regex:/^[1-9][0-9]{5}$/',
            ],
            [
                'password.required' => 'The password field is required.',
                'password.min' => 'The password must be at least 8 characters.',
                'password.confirmed' => 'The password confirmation does not match.',
                'password.regex' => 'The password must include at least one uppercase letter, one lowercase letter, and one special character.',
                'pincode.required' => 'PIN code is required.',
                'pincode.regex' => 'Enter a valid 6-digit PIN code.Starting with 1',
            ]
        );

        if ($validator->fails()) {
            return json_encode(['status' => 'validation', 'errors' => $validator->errors()]);
        }

        $sponsor = DB::table('users')->where('connection', $request->sponsor_id)->first();
        $currenrsponsor = $sponsor->id;
        $sponsorHasPackages = User::find($sponsor->id)->userPackages()->exists();

        if (!$sponsorHasPackages) {
            return json_encode(['status' => 'error', 'message' => 'The sponsor does not have any active packages.']);
        }

        $parent_id = 996;
        $position = 'left';
        $level = 1;

        // Set PAN card to 'STORE' for simple registration
        $panCardNo = 'STORE';

        // Always set mother_id to 1 for simple registration (no PAN card branching logic)
        $motherid = 1;

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_no' => $request->phone_no,
            'pan_card_no' => $panCardNo, // Auto-generated PAN
            'pincode' => $request->pincode,
            'address' => $request->address,
            'password' => Hash::make($request->password),
            'sponsor_id' => $currenrsponsor,
            'rank_id' => 1,
            'parent_id' => $parent_id,
            'position' => $position,
            'level' => $level,
            'connection' => $request->connection,
            'total_income' => 0,
            'role' => 'user',
            'mother_id' => $motherid,
            'is_pair_matched' => '0',
        ]);


        // Register the same user as a PrestaShop customer (non-blocking)
        $this->registerUserOnPrestaShop($request->name, $request->email, $request->password, $request->connection);

        return json_encode([
            'status' => 'success',
            'message' => 'User added successfully',
            'connection' => $request->connection,
            'password' => $request->password
        ]);
    }

    public function kycVerification()
    {
        $user = auth()->user();

        // Check if user needs KYC
        if ($user->pan_card_no != 'STORE' || $user->mother_id != 1) {
            return redirect()->route('adminhome')->with('info', 'KYC already completed.');
        }

        return view('Admin/kyc_verification', compact('user'));
    }

    public function updateKyc(Request $request)
    {
        $user = auth()->user();

        // Verify user is eligible for KYC update
        if ($user->pan_card_no != 'STORE' || $user->mother_id != 1) {
            return json_encode(['status' => 'error', 'message' => 'KYC already completed or not eligible.']);
        }

        $validator = Validator::make(
            $request->all(),
            [
                'pan_card_no' => 'required|regex:/^[A-Z]{5}[0-9]{4}[A-Z]$/',
            ],
            [
                'pan_card_no.required' => 'PAN card number is required.',
                'pan_card_no.regex' => 'PAN card must be in format: ABCDE1234F',
            ]
        );

        if ($validator->fails()) {
            return json_encode(['status' => 'validation', 'errors' => $validator->errors()]);
        }

        $panCardNo = strtoupper($request->pan_card_no);

        // Check if PAN already exists
        $panCardExists = DB::table('users')->where('pan_card_no', $panCardNo)->exists();

        if ($panCardExists) {
            // Check if PAN belongs to sponsor's branch
            $sponsor = User::find($user->sponsor_id);
            $branchExists = false;

            while ($sponsor) {
                if ($sponsor->pan_card_no == $panCardNo) {
                    $branchExists = true;
                    break;
                }
                if ($sponsor->sponsor_id !== null) {
                    $sponsor = User::find($sponsor->sponsor_id);
                } else {
                    break;
                }
            }

            if (!$branchExists) {
                return json_encode([
                    'status' => 'error',
                    'message' => 'This PAN card cannot be used here. It must belong to your sponsor\'s branch.',
                ]);
            }

            $existingUser = DB::table('users')->where('pan_card_no', $panCardNo)->first();

            if (strtolower($existingUser->name) !== strtolower($user->name)) {
                return json_encode([
                    'status' => 'error',
                    'message' => 'The name does not match the existing record for this PAN card.',
                    'correct_name' => $existingUser->name
                ]);
            }

            // Determine mother_id based on existing PAN users
            $motherId1Exists = DB::table('users')->where('pan_card_no', $panCardNo)->where('mother_id', 1)->exists();
            $motherId2Exists = DB::table('users')->where('pan_card_no', $panCardNo)->where('mother_id', 2)->exists();
            $motherId3Exists = DB::table('users')->where('pan_card_no', $panCardNo)->where('mother_id', 3)->exists();

            if ($motherId1Exists && $motherId2Exists && $motherId3Exists) {
                $motherid = 0;
            } elseif ($motherId1Exists && $motherId2Exists) {
                $motherid = 3;
            } elseif ($motherId1Exists) {
                $motherid = 2;
            } else {
                $motherid = 1;
            }
        } else {
            $motherid = 1;
        }

        // Update user's PAN and mother_id
        $user->pan_card_no = $panCardNo;
        $user->mother_id = $motherid;
        $user->save();

        return json_encode([
            'status' => 'success',
            'message' => 'KYC verification completed successfully! You can now access all features.'
        ]);
    }

    public function validateUser(Request $request)
    {
        $request->validate([
            'connection' => 'required',
            'api_token' => 'required'
        ]);

        $validToken = 'my_secure_token';

        if ($request->api_token !== $validToken) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user  = User::where('connection', $request->connection)->first();

        if ($user) {
            return response()->json([
                'valid' => true,
                'name' => $user->name  // Return user name
            ]);
        } else {
            return response()->json([
                'valid' => false,
                'message' => 'User not found'
            ]);
        }
    }

    // online purchase
    public function franchisee_income_list_online()
    {

        $user = auth()->user();

        if ($user->role === 'superadmin') {
            $users = RepurchaseWallet::where('amount_type', 'Franchisee Share Income')->where('status', 2)->latest()->get();
            $totalAmount = $users->sum('amount');
        } else {
            $users = RepurchaseWallet::where('user_id', $user->id)->where('amount_type', 'Franchisee Share Income')->where('status', 2)->latest()->get();
            $totalAmount = $users->sum('amount');
        }
        return view('Admin.franchisee_income_list_online', compact('users', 'totalAmount'));
    }

    public function selfpurchase_income_list_online()
    {
        $user = auth()->user();

        if ($user->role === 'superadmin') {
            $users = RepurchaseWallet::where('amount_type', 'Self Purchase Income')->where('status', 2)->latest()->get();
            $totalAmount = $users->sum('amount');
        } else {
            $users = RepurchaseWallet::where('user_id', $user->id)->where('amount_type', 'Self Purchase Income')->where('status', 2)->latest()->get();
            $totalAmount = $users->sum('amount');
        }
        return view('Admin.selfpurchase_income_list_online', compact('users', 'totalAmount'));
    }

    public function repurchase_list_online()
    {
        $user = auth()->user();

        if ($user->role === 'superadmin') {
            $users = RepurchaseWallet::where('amount_type', 'Repurchase Income')->where('status', 2)->latest()->get();
            $totalAmount = $users->sum('amount');
        } else {
            $users = RepurchaseWallet::where('user_id', $user->id)->where('amount_type', 'Repurchase Income')->where('status', 2)->latest()->get();
            $totalAmount = $users->sum('amount');
        }
        return view('Admin.repurchase_list_online', compact('users', 'totalAmount'));
    }

    public function gst_tcs_list()
    {
        $adminAmountList = AdminWallet::whereIn('type', [23, 24])->orderBy('id', 'desc')->get();

        return view('Admin.gst_tcs_list', compact('adminAmountList'));
    }

    public function store_user_wpan(Request $request)
    {
        $name = $request->input('name');
        $cleanedName = preg_replace('/\s+/', '', $name);
        $firstTwoLetters = strtoupper(substr($cleanedName, 0, 2));
        $lastUser = User::latest('id')->first();
        $uniqueId = $lastUser->id + 1;
        $user_code = 'V' . $firstTwoLetters . $uniqueId;

        $request->merge(['connection' => $user_code]);

        $validator = Validator::make(
            $request->all(),
            [
                'sponsor_id' => 'required|exists:users,connection',
                'name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
                'email' => 'required|string|email|max:255',
                'phone_no' => 'required|string|max:10',
                'address' => 'required|string',
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'confirmed',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\W).+$/'
                ],
                'connection' => 'required|string|max:255|unique:users,connection',
                'pincode' => 'required|regex:/^[1-9][0-9]{5}$/',
            ],
            [
                'password.required' => 'The password field is required.',
                'password.min' => 'The password must be at least 8 characters.',
                'password.confirmed' => 'The password confirmation does not match.',
                'password.regex' => 'The password must include at least one uppercase letter, one lowercase letter, and one special character.',
                'pincode.required' => 'PIN code is required.',
                'pincode.regex' => 'Enter a valid 6-digit PIN code.Starting with 1',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['status' => 'validation', 'errors' => $validator->errors()]);
        }

        $sponsor = DB::table('users')->where('connection', $request->sponsor_id)->first();
        $currenrsponsor = $sponsor->id;

        // Use binary tree placement if provided, otherwise fall back to legacy default
        if ($request->filled('parent_id') && is_numeric($request->parent_id)) {
            $binaryParent = User::find((int) $request->parent_id);
            $parent_id    = $binaryParent ? $binaryParent->id : 996;
            $position     = in_array($request->position, ['left', 'right']) ? $request->position : 'left';
            $level        = ($binaryParent->level ?? 0) + 1;
        } else {
            $parent_id = 996;
            $position  = 'left';
            $level     = 1;
        }

        // Set PAN card to 'STORE' for simple registration
        $panCardNo = 'STORE';

        // Always set mother_id to 1 for simple registration (no PAN card branching logic)
        $motherid = 1;

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_no' => $request->phone_no,
            'pan_card_no' => $panCardNo, // Auto-generated PAN
            'pincode' => $request->pincode,
            'address' => $request->address,
            'password' => Hash::make($request->password),
            'sponsor_id' => $currenrsponsor,
            'rank_id' => 1,
            'parent_id' => $parent_id,
            'position' => $position,
            'level' => $level,
            'connection' => $request->connection,
            'total_income' => 0,
            'role' => 'user',
            'mother_id' => $motherid,
            'is_pair_matched' => '0',
        ]);

        // Register the same user as a PrestaShop customer (non-blocking)
        $this->registerUserOnPrestaShop($request->name, $request->email, $request->password, $request->connection);

        return response()->json([
            'status' => 'success',
            'message' => 'User added successfully',
            'connection' => $request->connection,
            'password' => $request->password
        ]);
    }

    public function store_user_wpan_rr(Request $request)
    {
        $name = $request->input('name');
        $cleanedName = preg_replace('/\s+/', '', $name);
        $firstTwoLetters = strtoupper(substr($cleanedName, 0, 2));
        $lastUser = User::latest('id')->first();
        $uniqueId = $lastUser->id + 1;
        $user_code = 'V' . $firstTwoLetters . $uniqueId;

        $request->merge(['connection' => $user_code]);

        $validator = Validator::make(
            $request->all(),
            [
                'sponsor_id' => 'nullable|exists:users,connection',
                'name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
                'email' => 'required|string|email|max:255',
                'phone_no' => 'required|string|max:10',
                'address' => 'required|string',
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'confirmed',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\W).+$/'
                ],
                'connection' => 'required|string|max:255|unique:users,connection',
                'pincode' => 'required|regex:/^[1-9][0-9]{5}$/',
            ],
            [
                'password.required' => 'The password field is required.',
                'password.min' => 'The password must be at least 8 characters.',
                'password.confirmed' => 'The password confirmation does not match.',
                'password.regex' => 'The password must include at least one uppercase letter, one lowercase letter, and one special character.',
                'pincode.required' => 'PIN code is required.',
                'pincode.regex' => 'Enter a valid 6-digit PIN code.Starting with 1',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['status' => 'validation', 'errors' => $validator->errors()]);
        }

        if ($request->sponsor_id) {
            $sponsor = DB::table('users')->where('connection', $request->sponsor_id)->first();
            $currenrsponsor = $sponsor->id;
            $sponsorId = null;
        } else {

            // Automatic Round-Robin Board Member Assignment
            $boardMembers = DB::table('board_members')
                ->where('status', 1)
                ->orderBy('id', 'asc')
                ->pluck('user_id')
                ->toArray();

            // Default to a fallback (e.g., admin) if no board members found
            $defaultSponsorId = 996;
            $sponsorId = $defaultSponsorId;

            if (!empty($boardMembers)) {
                // Find the last registered user who was assigned a board member via the new system
                $lastUserWithBoardSponsor = User::whereNotNull('assigned_board_member_id')
                    ->whereIn('assigned_board_member_id', $boardMembers)
                    ->latest('id')
                    ->first();

                // Fallback: If no one has the new column set yet, look at sponsor_id (legacy/initial check)
                if (!$lastUserWithBoardSponsor) {
                    $lastUserWithBoardSponsor = User::whereIn('sponsor_id', $boardMembers)
                        ->latest('id')
                        ->first();
                    // If found here, we use their sponsor_id as the "last used board member"
                    if ($lastUserWithBoardSponsor) {
                        $lastSponsorId = $lastUserWithBoardSponsor->sponsor_id;
                    }
                } else {
                    $lastSponsorId = $lastUserWithBoardSponsor->assigned_board_member_id;
                }

                if (isset($lastSponsorId)) {
                    $lastIndex = array_search($lastSponsorId, $boardMembers);

                    if ($lastIndex !== false) {
                        $nextIndex = ($lastIndex + 1) % count($boardMembers);
                        $sponsorId = $boardMembers[$nextIndex];
                    } else {
                        $sponsorId = $boardMembers[0];
                    }
                } else {
                    $sponsorId = $boardMembers[0];
                }
            }

            $currenrsponsor = $sponsorId;
        }

        $parent_id = 996;
        $position = 'left';
        $level = 1;

        // Set PAN card to 'STORE' for simple registration
        $panCardNo = 'STORE';

        // Always set mother_id to 1 for simple registration (no PAN card branching logic)
        $motherid = 1;

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_no' => $request->phone_no,
            'pan_card_no' => $panCardNo, // Auto-generated PAN
            'pincode' => $request->pincode,
            'address' => $request->address,
            'password' => Hash::make($request->password),
            'sponsor_id' => $currenrsponsor,
            'rank_id' => 1,
            'parent_id' => $parent_id,
            'position' => $position,
            'level' => $level,
            'connection' => $request->connection,
            'total_income' => 0,
            'role' => 'user',
            'mother_id' => $motherid,
            'is_pair_matched' => '0',
            'assigned_board_member_id' => $sponsorId,
        ]);

        // Register the same user as a PrestaShop customer (non-blocking)
        $this->registerUserOnPrestaShop($request->name, $request->email, $request->password, $request->connection);

        return response()->json([
            'status' => 'success',
            'message' => 'User added successfully',
            'connection' => $request->connection,
            'password' => $request->password
        ]);
    }

    public function view_board_members()
    {
        $boardMembers = \App\Models\BoardMember::with('user')->get();
        return view('Admin.board_members_list', compact('boardMembers'));
    }

    public function store_board_member(Request $request)
    {
        $request->validate([
            'connection' => 'required|exists:users,connection',
        ]);

        $user = User::where('connection', $request->connection)->first();

        // Check if already exists
        $exists = BoardMember::where('user_id', $user->id)->exists();
        if ($exists) {
            return redirect()->back()->with('error', 'User is already a board member.');
        }

        \App\Models\BoardMember::create([
            'user_id' => $user->id,
            'status' => 1,
        ]);

        return redirect()->back()->with('success', 'Board Member added successfully.');
    }

    public function delete_board_member($id)
    {
        $member = BoardMember::find($id);
        if ($member) {
            $member->delete();
            return redirect()->back()->with('success', 'Board Member removed successfully.');
        }
        return redirect()->back()->with('error', 'Member not found.');
    }

    // ── User Binary Tree (post-migration) ────────────────────────────────────────

    public function userBinaryTree(Request $request)
    {
        $settings = BinaryTreeSetting::current();

        if (!$settings->migration_complete) {
            return redirect()->route('sunflower')->with('info', 'Binary tree is not yet available.');
        }

        $me          = auth()->user();
        $nodeId      = $request->query('node_id');
        $currentNode = $nodeId ? User::find($nodeId) : $me;

        // Users can only browse within their own subtree
        if ($currentNode && $currentNode->id !== $me->id) {
            // Verify currentNode is a descendant of the logged-in user
            $node = $currentNode;
            $isDescendant = false;
            while ($node && $node->parent_id) {
                if ($node->parent_id == $me->id) { $isDescendant = true; break; }
                $node = User::find($node->parent_id);
                if ($node && $node->id == $me->id) { $isDescendant = true; break; }
            }
            if (!$isDescendant) $currentNode = $me;
        }

        $binaryTree = $this->buildBinaryTreeData($currentNode, 4);
        $parentNode = ($currentNode && $currentNode->id !== $me->id && $currentNode->parent_id)
            ? User::find($currentNode->parent_id)
            : null;

        $packages = \App\Models\Package::where('status', 1)->orderBy('name')->get();

        return view('Admin.binary_tree_user', compact(
            'currentNode', 'binaryTree', 'parentNode', 'packages', 'me'
        ));
    }

    // ── Binary Tree Admin Migration ──────────────────────────────────────────────

    public function binaryTreeAdmin(Request $request)
    {
        $settings   = BinaryTreeSetting::current();
        $globalRoot = $settings->root_user_id ? User::find($settings->root_user_id) : null;

        // Allow drilling into a sub-node via ?node_id=X
        $nodeId      = $request->query('node_id');
        $currentNode = $nodeId ? User::find($nodeId) : $globalRoot;

        $binaryTree  = null;
        $parentNode  = null;

        if ($currentNode) {
            $binaryTree = $this->buildBinaryTreeData($currentNode, 4);
            // Back navigation: go up to this node's parent in the tree
            $parentNode = $currentNode->parent_id ? User::find($currentNode->parent_id) : null;
        }

        $allUsers = User::select('id', 'name', 'connection', 'user_image')
            ->where('role', 'user')
            ->orderBy('name')
            ->get();

        $isGlobalRoot = $globalRoot && $currentNode && $globalRoot->id === $currentNode->id;

        $packages = \App\Models\Package::orderBy('name')->get();

        return view('Admin.binary_tree_admin', compact(
            'settings', 'globalRoot', 'currentNode', 'binaryTree',
            'allUsers', 'parentNode', 'isGlobalRoot', 'packages'
        ));
    }

    public function setBinaryRoot(Request $request)
    {
        $request->validate(['user_id' => 'required|exists:users,id']);

        $user = User::find($request->user_id);

        // Clear existing root placement
        User::where('parent_id', null)->where('position', null)->update([]);

        $settings = BinaryTreeSetting::current();
        $settings->root_user_id = $user->id;
        $settings->save();

        // Root user has no parent
        $user->parent_id = null;
        $user->position = null;
        $user->save();

        return response()->json(['status' => 'success', 'message' => $user->name . ' set as root.']);
    }

    public function transferUserToTree(Request $request)
    {
        $request->validate([
            'user_id'   => 'required|exists:users,id',
            'parent_id' => 'required|exists:users,id',
            'position'  => 'required|in:left,right',
        ]);

        $settings = BinaryTreeSetting::current();
        if (!$settings->root_user_id) {
            return response()->json(['status' => 'error', 'message' => 'Set a root user first.']);
        }

        $parent = User::find($request->parent_id);
        $child  = $parent->position === 'left' ? $parent->leftChild : null;

        // Check if position is already occupied
        $occupied = ($request->position === 'left')
            ? $parent->leftChild()->exists()
            : $parent->rightChild()->exists();

        if ($occupied) {
            return response()->json(['status' => 'error', 'message' => 'That position is already filled.']);
        }

        $user = User::find($request->user_id);

        // PAN card subtree check: if user has a PAN, new position must be under their Mother ID
        if ($user->pan_card_no && $user->mother_id != 1) {
            $conflict = $this->checkMotherSubtreeConflict($user, $request->parent_id);
            if ($conflict) return response()->json(['status' => 'error', 'message' => $conflict]);
        }

        // Remove from old position if already placed
        $user->parent_id = $request->parent_id;
        $user->position  = $request->position;
        $user->level     = ($parent->level ?? 0) + 1;
        $user->save();

        return response()->json(['status' => 'success', 'message' => $user->name . ' placed successfully. No points added.']);
    }

    public function removeFromBinaryTree(Request $request)
    {
        $request->validate(['user_id' => 'required|exists:users,id']);

        $settings = BinaryTreeSetting::current();
        if ($request->user_id == $settings->root_user_id) {
            return response()->json(['status' => 'error', 'message' => 'Cannot remove the root user. Change root first.']);
        }

        $user = User::find($request->user_id);

        // Block deletion if user has children in the tree
        $childCount = User::where('parent_id', $user->id)->count();
        if ($childCount > 0) {
            return response()->json([
                'status'  => 'error',
                'message' => $user->name . ' has ' . $childCount . ' child(ren) in the tree. Remove or reassign them first.',
            ]);
        }

        // Re-attach children to null (orphan them) so they can be re-placed
        User::where('parent_id', $user->id)->update(['parent_id' => null, 'position' => null, 'level' => 0]);

        $user->parent_id = null;
        $user->position  = null;
        $user->level     = 0;
        $user->save();

        return response()->json(['status' => 'success', 'message' => $user->name . ' removed from tree.']);
    }

    /**
     * Check which slots (left/right) are available under a target user.
     * Also validates that the target is not a descendant of the user being moved.
     */
    public function getPinOwners(Request $request)
    {
        $user = User::find((int) $request->user_id);
        if (!$user) return response()->json([]);

        // Walk up the binary tree parent chain
        $owners = [];
        $current = $user;
        while ($current) {
            $owners[] = [
                'id'   => $current->id,
                'label' => $current->connection . ' — ' . $current->name . ($current->id === $user->id ? ' (Self)' : ''),
            ];
            $current = $current->parent_id ? User::find($current->parent_id) : null;
        }

        return response()->json($owners);
    }

    public function checkTargetSlots(Request $request)
    {
        $targetId  = $request->get('target_id');
        $movingId  = $request->get('moving_id');

        $target = User::find($targetId);
        if (!$target) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        // Prevent moving into own subtree (cycle check)
        if ($movingId) {
            $ancestor = $target;
            while ($ancestor) {
                if ((string)$ancestor->id === (string)$movingId) {
                    return response()->json(['error' => 'Cannot move a user into their own subtree.'], 422);
                }
                $ancestor = $ancestor->parent_id ? User::find($ancestor->parent_id) : null;
            }
        }

        $leftTaken  = User::where('parent_id', $targetId)->where('position', 'left')->exists();
        $rightTaken = User::where('parent_id', $targetId)->where('position', 'right')->exists();

        return response()->json([
            'left'  => !$leftTaken,
            'right' => !$rightTaken,
        ]);
    }

    /**
     * Move a user (and their entire subtree) to a new position in the tree.
     * NOTE: Sponsor income recalculation for point-bearing users is deferred
     * to Phase 2 when the points engine is active.
     */
    public function moveUserInTree(Request $request)
    {
        $request->validate([
            'user_id'          => 'required|exists:users,id',
            'target_parent_id' => 'required|exists:users,id',
            'position'         => 'required|in:left,right',
        ]);

        $user         = User::find($request->user_id);
        $targetParent = User::find($request->target_parent_id);
        $settings     = BinaryTreeSetting::current();

        // Cannot move the root user
        if ((string)$user->id === (string)$settings->root_user_id) {
            return response()->json(['status' => 'error', 'message' => 'Cannot move the root user.']);
        }

        // Cannot move to self
        if ((string)$user->id === (string)$targetParent->id) {
            return response()->json(['status' => 'error', 'message' => 'Cannot move a user under themselves.']);
        }

        // Cycle check: target must not be inside the user's own subtree
        $ancestor = $targetParent;
        while ($ancestor) {
            if ((string)$ancestor->id === (string)$user->id) {
                return response()->json(['status' => 'error', 'message' => 'Cannot move a user into their own subtree.']);
            }
            $ancestor = $ancestor->parent_id ? User::find($ancestor->parent_id) : null;
        }

        // Check target slot is free
        $slotTaken = User::where('parent_id', $targetParent->id)
                         ->where('position', $request->position)
                         ->exists();
        if ($slotTaken) {
            return response()->json(['status' => 'error', 'message' => 'That position is already occupied.']);
        }

        // PAN card subtree check: if user has a PAN, new position must be under their Mother ID
        if ($user->pan_card_no && $user->mother_id != 1) {
            $conflict = $this->checkMotherSubtreeConflict($user, $targetParent->id);
            if ($conflict) return response()->json(['status' => 'error', 'message' => $conflict]);
        }

        // Perform the move: update the user's parent and position
        $user->parent_id = $targetParent->id;
        $user->position  = $request->position;
        $user->level     = ($targetParent->level ?? 0) + 1;
        $user->save();

        // Recursively fix levels for the entire moved subtree
        $this->recalculateLevels($user);

        return response()->json(['status' => 'success', 'message' => $user->name . ' and their subtree moved successfully.']);
    }

    /**
     * Check if $targetParentId is within the Mother ID's binary subtree for $user's PAN card.
     * Returns null if OK, or an error message string describing the conflict.
     */
    private function checkMotherSubtreeConflict(User $user, int $targetParentId): ?string
    {
        $motherUser = DB::table('users')
            ->where('pan_card_no', $user->pan_card_no)
            ->where('mother_id', 1)
            ->first();

        if (!$motherUser) return null;

        $inSubtree = DB::select("
            WITH RECURSIVE subtree AS (
                SELECT id FROM users WHERE id = ?
                UNION ALL
                SELECT u.id FROM users u
                INNER JOIN subtree s ON u.parent_id = s.id
            )
            SELECT COUNT(*) as cnt FROM subtree WHERE id = ?
        ", [$motherUser->id, $targetParentId]);

        if (($inSubtree[0]->cnt ?? 0) == 0) {
            $accountType = match($user->mother_id) {
                2 => 'Privilege 1',
                3 => 'Privilege 2',
                default => 'Child ID',
            };
            return "{$user->name} ({$user->connection}) is a {$accountType} — must stay within Mother ID {$motherUser->connection}'s tree. Moving outside is not allowed.";
        }

        return null;
    }

    /**
     * PSV — package value of the direct child on one side only (1 level).
     * Only counts packages activated on or after the binary launch date.
     */
    private function legDirectVolume(int $userId, string $side): float
    {
        $child = User::where('parent_id', $userId)->where('position', $side)->value('id');
        if (!$child) return 0;

        return (float) \DB::table('user_packages')
            ->join('packages', 'packages.id', '=', 'user_packages.package_id')
            ->where('user_packages.user_id', $child)
            ->where('user_packages.status', 1)
            ->where('user_packages.created_at', '>=', $this->binaryStartDate)
            ->sum('packages.amount');
    }

    /**
     * BV for one leg = SUM of packages.binary_commission for all activations in the subtree.
     * Only counts packages on/after binary launch date.
     */
    private function legVolumeByPackageCode(int $userId, string $side, string $packageCode): float
    {
        $child = User::where('parent_id', $userId)->where('position', $side)->value('id');
        if (!$child) return 0;

        $result = \DB::select("
            WITH RECURSIVE subtree AS (
                SELECT id FROM users WHERE id = ?
                UNION ALL
                SELECT u.id FROM users u
                INNER JOIN subtree s ON u.parent_id = s.id
            )
            SELECT COALESCE(SUM(p.binary_commission), 0) AS total
            FROM user_packages up
            JOIN packages p ON p.id = up.package_id
            WHERE up.user_id IN (SELECT id FROM subtree)
              AND up.status = 1
              AND up.created_at >= ?
              AND p.package_code = ?
        ", [$child, $this->binaryStartDate, $packageCode]);

        return (float) ($result[0]->total ?? 0);
    }

    /**
     * BSV — total package value of the entire subtree on one side using recursive CTE.
     * Only counts packages activated on or after the binary launch date.
     */
    private function legTotalVolume(int $userId, string $side): float
    {
        $child = User::where('parent_id', $userId)->where('position', $side)->value('id');
        if (!$child) return 0;

        $result = \DB::select("
            WITH RECURSIVE subtree AS (
                SELECT id FROM users WHERE id = ?
                UNION ALL
                SELECT u.id FROM users u
                INNER JOIN subtree s ON u.parent_id = s.id
            )
            SELECT COALESCE(SUM(p.amount), 0) AS total
            FROM user_packages up
            JOIN packages p ON p.id = up.package_id
            WHERE up.user_id IN (SELECT id FROM subtree)
              AND up.status = 1
              AND up.created_at >= ?
        ", [$child, $this->binaryStartDate]);

        return (float) ($result[0]->total ?? 0);
    }

    /**
     * Recursively update the level of all descendants after a subtree move.
     */
    private function recalculateLevels(User $parent): void
    {
        $children = User::where('parent_id', $parent->id)->get();
        foreach ($children as $child) {
            $child->level = ($parent->level ?? 0) + 1;
            $child->save();
            $this->recalculateLevels($child);
        }
    }

    /**
     * Credit immediate binary sponsor income to the direct parent
     * when a package is activated.
     *
     *   Basic   → ₹200 to direct sponsor
     *   Premium → ₹1,000 to direct sponsor
     *   Prime   → ₹500 to direct sponsor  (type = prime_sponsor)
     *
     * Points engine note (Phase 2): when pair-matching income is active,
     * moving a user will transfer this sponsor income from old parent
     * to new parent.
     */
    private function creditBinarySponsorIncome(int $activatedUserId, int $packageId): void
    {
        $activatedUser = User::find($activatedUserId);
        if (!$activatedUser || !$activatedUser->parent_id) return;

        $package = \App\Models\Package::find($packageId);
        if (!$package) return;

        $sponsorId = $activatedUser->sponsor_id;
        if (!$sponsorId) return;

        $amount = (float) $package->sponsor_commission;
        if ($amount <= 0) return;

        // Sponsor must have a valid PAN (mother_id != 0) to receive referral income
        $sponsor = User::find($sponsorId);
        if (!$sponsor || $sponsor->mother_id == 0) return;

        // Sponsor must hold an active package of the same type OR any cross-eligible package
        $eligibleIds = array_merge([$packageId], $package->sponsor_eligible_package_ids ?? []);
        $sponsorHasPackage = \App\Models\UserPackage::where('user_id', $sponsorId)
            ->whereIn('package_id', $eligibleIds)
            ->where('status', 1)
            ->exists();
        if (!$sponsorHasPackage) return;

        $type  = in_array($package->package_code, ['prime_package']) ? 'prime_sponsor' : 'binary_sponsor';
        $label = 'Sponsor commission — ' . $package->name . ' activated by ' . $activatedUser->name;

        \App\Models\BinaryTransaction::credit(
            $sponsorId,
            $type,
            $amount,
            $label,
            [
                'from_user_id' => $activatedUserId,
                'package_id'   => $packageId,
            ]
        );
    }

    /**
     * Check if user has reached the auto-upgrade threshold for the given package.
     * If so, deactivate those packages and activate the target upgrade package.
     */
    private function checkAutoUpgrade(int $userId, int $packageId): void
    {
        $package = \App\Models\Package::find($packageId);
        if (!$package || !$package->auto_upgrade_count || !$package->auto_upgrade_to_package_id) return;

        $activeCount = \App\Models\UserPackage::where('user_id', $userId)
            ->where('package_id', $packageId)
            ->where('status', 1)
            ->count();

        if ($activeCount < $package->auto_upgrade_count) return;

        // Deactivate all current packages of this type, recording why
        \App\Models\UserPackage::where('user_id', $userId)
            ->where('package_id', $packageId)
            ->where('status', 1)
            ->update([
                'status'              => 0,
                'deactivation_reason' => 'auto_upgrade_to_' . $package->auto_upgrade_to_package_id,
            ]);

        // Activate the upgrade package, recording what it was upgraded from
        \App\Models\UserPackage::create([
            'user_id'                  => $userId,
            'package_id'               => $package->auto_upgrade_to_package_id,
            'pin_id'                   => null,
            'add_by'                   => $userId,
            'status'                   => 1,
            'upgraded_from_package_id' => $packageId,
        ]);

        // Credit sponsor income for the upgrade package as well
        $this->creditBinarySponsorIncome($userId, $package->auto_upgrade_to_package_id);
    }

    public function getUserPackageDetails(Request $request)
    {
        $user = User::find($request->get('user_id'));
        if (!$user) return response()->json([]);

        $packages = \App\Models\UserPackage::where('user_packages.user_id', $user->id)
            ->where('user_packages.status', 1)
            ->join('packages', 'packages.id', '=', 'user_packages.package_id')
            ->select('packages.name', 'packages.package_code', 'packages.amount', 'user_packages.created_at')
            ->orderBy('user_packages.created_at', 'desc')
            ->get()
            ->map(function ($p) {
                return [
                    'name'         => $p->name,
                    'package_code' => $p->package_code,
                    'amount'       => number_format($p->amount, 2),
                    'activated_on' => \Carbon\Carbon::parse($p->created_at)->format('d M Y'),
                ];
            });

        return response()->json($packages);
    }

    public function searchUsersForTransfer(Request $request)
    {
        $query    = $request->get('q', '');
        $treeOnly = $request->boolean('tree_only');

        $builder = User::select('id', 'name', 'connection', 'user_image')
            ->where('role', 'user')
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('connection', 'like', "%{$query}%");
            });

        if ($treeOnly) {
            $rootId = DB::table('binary_settings')->value('root_user_id');
            $builder->where(function ($q) use ($rootId) {
                $q->whereNotNull('parent_id');
                if ($rootId) $q->orWhere('id', $rootId);
            });
        }

        $users = $builder->limit(20)->get()->map(function ($u) {
            return [
                'id'         => $u->id,
                'name'       => $u->name,
                'connection' => $u->connection,
                'image'      => $u->user_image ? asset($u->user_image) : asset('assets/dist/img/images.jpg'),
            ];
        });

        return response()->json($users);
    }

    public function quickTestUser(Request $request)
    {
        $parentId = (int) $request->parent_id;
        $position = in_array($request->position, ['left', 'right']) ? $request->position : 'left';

        $binaryParent = User::find($parentId);
        if (!$binaryParent) {
            return response()->json(['status' => 'error', 'message' => 'Parent not found.']);
        }

        // Check slot is free
        if (User::where('parent_id', $parentId)->where('position', $position)->exists()) {
            return response()->json(['status' => 'error', 'message' => 'Slot already occupied.']);
        }

        $names     = ['Arun','Biju','Celin','Devi','Ebin','Faisal','Geetha','Hari','Indu','Joji','Kavya','Lijo','Meera','Nisha','Omar'];
        $surnames  = ['Kumar','Thomas','Jose','Nair','George','Mathew','Varghese','Pillai','Menon','Das'];
        $firstName = $names[array_rand($names)];
        $lastName  = $surnames[array_rand($surnames)];
        $fullName  = $firstName . ' ' . $lastName;

        $lastUser   = User::latest('id')->first();
        $uniqueId   = $lastUser->id + 1;
        $initials   = strtoupper(substr(preg_replace('/\s+/', '', $fullName), 0, 2));
        $connection = 'V' . $initials . $uniqueId;
        $password   = 'Test@' . rand(1000, 9999);

        // Default sponsor: root user or parent's sponsor
        $sponsorId = $binaryParent->sponsor_id ?? $binaryParent->id;

        $user = User::create([
            'name'          => $fullName,
            'email'         => strtolower($firstName) . $uniqueId . '@test.com',
            'phone_no'      => '9' . rand(100000000, 999999999),
            'pan_card_no'   => 'STORE',
            'pincode'       => '682001',
            'address'       => 'Test Address',
            'password'      => Hash::make($password),
            'sponsor_id'    => $sponsorId,
            'rank_id'       => 1,
            'parent_id'     => $parentId,
            'position'      => $position,
            'level'         => ($binaryParent->level ?? 0) + 1,
            'connection'    => $connection,
            'total_income'  => 0,
            'role'          => 'user',
            'mother_id'     => 1,
            'is_pair_matched' => '0',
        ]);

        return response()->json([
            'status'     => 'success',
            'connection' => $connection,
            'password'   => $password,
            'user_id'    => $user->id,
        ]);
    }

    public function completeMigration(Request $request)
    {
        $settings = BinaryTreeSetting::current();
        if (!$settings->root_user_id) {
            return response()->json(['status' => 'error', 'message' => 'Set a root user before completing migration.']);
        }

        $settings->migration_complete = true;
        $settings->save();

        return response()->json(['status' => 'success', 'message' => 'Migration marked as complete. New registrations are now open.']);
    }

    private function buildBinaryTreeData(User $user, int $depth): array
    {
        if ($depth === 0) {
            return ['user' => null, 'left' => null, 'right' => null];
        }

        $left  = $user->leftChild()->first();
        $right = $user->rightChild()->first();

        $user->left_count  = $user->leftDownlineCount();
        $user->right_count = $user->rightDownlineCount();
        // Flag: this node has children that are hidden because depth limit reached
        $user->has_more = ($depth === 1) && ($left || $right);

        // Resolve active packages (ordered by amount desc so first = highest)
        $activePackages = \App\Models\UserPackage::where('user_packages.user_id', $user->id)
            ->where('user_packages.status', 1)
            ->join('packages', 'packages.id', '=', 'user_packages.package_id')
            ->select('packages.package_code', 'packages.amount', 'packages.color')
            ->orderBy('packages.amount', 'desc')
            ->get();

        $codes      = $activePackages->pluck('package_code')->toArray();
        $topPackage = $activePackages->first();

        $hasBasic   = in_array('basic_package',   $codes);
        $hasPremium = in_array('premium_package', $codes);
        $user->left_basic_vol    = $hasBasic   ? $this->legVolumeByPackageCode($user->id, 'left',  'basic_package')   : 0;
        $user->left_premium_vol  = $hasPremium ? $this->legVolumeByPackageCode($user->id, 'left',  'premium_package') : 0;
        $user->right_basic_vol   = $hasBasic   ? $this->legVolumeByPackageCode($user->id, 'right', 'basic_package')   : 0;
        $user->right_premium_vol = $hasPremium ? $this->legVolumeByPackageCode($user->id, 'right', 'premium_package') : 0;

        // Color from highest-amount active package; null if no package
        $user->package_color = $topPackage ? ($topPackage->color ?: '#6c757d') : null;

        // Keep package_type for backward-compat (openPackageModal activation check)
        if (in_array('premium_package', $codes)) {
            $user->package_type = 'premium';
        } elseif (in_array('basic_package', $codes)) {
            $user->package_type = 'basic';
        } elseif (in_array('prime_package', $codes)) {
            $user->package_type = 'prime';
        } else {
            $user->package_type = null;
        }

        return [
            'user'  => $user,
            'left'  => ($depth > 1 && $left)  ? $this->buildBinaryTreeData($left,  $depth - 1) : null,
            'right' => ($depth > 1 && $right) ? $this->buildBinaryTreeData($right, $depth - 1) : null,
        ];
    }

       // ── Private Helpers ───────────────────────────────────────────────────────

    /**
     * Register a newly created local user as a customer in PrestaShop
     * via the WebService REST API.
     *
     * @param  string  $name           Full name of the user
     * @param  string  $email          Email address
     * @param  string  $plainPassword  Plain-text password (md5-hashed before sending)
     * @param  string  $connection     User id
     */
    private function registerUserOnPrestaShop(string $name, string $email, string $plainPassword, string $connection): void
    {
        try {
            $psUrl    = 'https://myvstore.in/api/customers';
            $psApiKey = 'LMT8DLMLBKEEX2A3JDH9J24R75RDQ8ZT';

            // Split full name into firstname / lastname
            $nameParts = explode(' ', trim($name), 2);
            $firstName = $nameParts[0];
            $lastName  = isset($nameParts[1]) ? $nameParts[1] : $nameParts[0];

            $psXml =
                '<?xml version="1.0" encoding="UTF-8"?>' . "\n" .
                '<prestashop xmlns:xlink="http://www.w3.org/1999/xlink">' . "\n" .
                '  <customer>' . "\n" .
                '    <firstname><![CDATA[' . $firstName . ']]></firstname>' . "\n" .
                '    <lastname><![CDATA['  . $lastName  . ']]></lastname>'  . "\n" .
                '    <email><![CDATA['    . $email      . ']]></email>'     . "\n" .
                '    <passwd><![CDATA['   . $plainPassword   . ']]></passwd>'    . "\n" .
                '    <user_id><![CDATA['  . $connection  . ']]></user_id>'  . "\n" .
                '    <active>1</active>'  . "\n" .
                '    <id_default_group>3</id_default_group>' . "\n" .
                '  </customer>' . "\n" .
                '</prestashop>';

            \Log::info('[PrestaShop] Attempting customer registration', [
                'email'      => $email,
                'connection' => $connection,
                'xml'        => $psXml,
            ]);

            $ch = curl_init($psUrl);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST           => true,
                CURLOPT_POSTFIELDS     => $psXml,
                CURLOPT_USERPWD        => $psApiKey . ':',
                CURLOPT_HTTPHEADER     => ['Content-Type: application/xml'],
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_TIMEOUT        => 15,
            ]);

            $response   = curl_exec($ch);
            $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError  = curl_error($ch);
            curl_close($ch);

            if ($curlError) {
                \Log::error('[PrestaShop] cURL error for ' . $email . ': ' . $curlError);
                return;
            }

            if ($httpStatus === 201) {
                \Log::info('[PrestaShop] Customer created successfully', [
                    'email'       => $email,
                    'http_status' => $httpStatus,
                ]);
            } else {
                \Log::warning('[PrestaShop] Customer registration failed', [
                    'email'       => $email,
                    'http_status' => $httpStatus,
                    'response'    => $response,
                ]);
            }
        } catch (\Exception $e) {
            // Non-blocking: local user is already created; just log the warning.
            \Log::warning('[PrestaShop] Exception during customer registration for ' . $email . ': ' . $e->getMessage());
        }
    }

    public function sponsorIncomeDetails(Request $request)
    {
        $user = auth()->user();

        $query = \App\Models\BinaryTransaction::with(['user', 'fromUser', 'package'])
            ->whereIn('type', ['binary_sponsor', 'prime_sponsor'])
            ->orderBy('created_at', 'desc');

        if ($user->role !== 'superadmin') {
            $query->where('user_id', $user->id);
        }

        // Optional date filters
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $transactions = $query->get();
        $totalAmount  = $transactions->sum('amount');

        return view('Admin.sponsor_income_details', compact('transactions', 'totalAmount'));
    }
}
