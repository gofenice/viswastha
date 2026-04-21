<?php

namespace App\Http\Controllers;

use App\Models\BasicRank;
use App\Models\BasicRankAchieve;
use App\Models\BasicUserRankIncome;
use App\Models\BonusWallet;
use App\Models\ChildMotherPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Package;
use App\Models\PairMatch;
use App\Models\PairMatchIncome;
use App\Models\PinGeneration;
use App\Models\RankIncome;
use App\Models\Product;
use App\Models\UserPackage;
use App\Models\ReferralIncome;
use App\Models\Rank;
use App\Models\RoyaltyUserWallet;
use App\Models\SponsorLevel;
use App\Models\UserRankHistory;
use App\Models\WithdrawalRequest;
use Illuminate\Support\Facades\DB;

class CalculationController extends Controller
{
    public function admin_referral_income()
    {
        $user = auth()->user();

        if ($user->role === 'superadmin') {
            $sponsors = ReferralIncome::all();
            $totalAmount = ReferralIncome::sum('income');
        } else {
            $sponsors = ReferralIncome::where('sponsor_id', $user->id)->get();
            $totalAmount = ReferralIncome::where('sponsor_id', $user->id)->sum('income');
        }

        return view('Admin.admin_referral_income', compact('sponsors', 'totalAmount'));
    }


    public function pairmatch_income()
    {
        $loggedUser = auth()->user();
        $pairMatches = [];
        $totalAmount = 0;

        if ($loggedUser->role === 'superadmin') {
            $pairMatches = PairMatch::all();
            $totalAmount = PairMatchIncome::sum('income');
        } else {
            $pairMatches = PairMatch::where([
                'sponsor_id' => $loggedUser->id,
                'status' => '1'
            ])->get();
        }


        foreach ($pairMatches as $pairMatch) {
            $totalAmount += PairMatchIncome::where([
                'pair_match_id' => $pairMatch->id,
                'user_id' => $loggedUser->id,
                'status' => '1'
            ])->sum('income');
        }

        return view('Admin/pairmatch_income', compact('pairMatches', 'totalAmount'));
    }
    // private function getParentHierarchy($user)
    // {
    //     $hierarchy = [];
    //     while ($user && $user->parent_id) {
    //         $parent = User::find($user->parent_id);
    //         if ($parent) {
    //             $hierarchy[] = [
    //                 'id' => $parent->id,
    //                 'name' => $parent->name,
    //                 'parent_id' => $parent->parent_id,
    //                 'position' => $parent->position,
    //             ];
    //             $user = $parent;
    //         } else {
    //             break;
    //         }
    //     }
    //     return array_reverse($hierarchy);
    // }

    public function rank_income()
    {
        $users = User::all();

        foreach ($users as $user) {
            $newRank = $this->calculateRankForUser($user->id);
        }

        $users = User::where('rank_id', '>', 1)
            ->join('ranks', 'users.rank_id', '=', 'ranks.id')
            ->select('users.*', 'ranks.rank_name as rank_name')
            ->orderBy('users.id', 'asc')
            ->get();

        return view('Admin/rank_income', compact('users'));
    }

    private function calculateRankForUser($personId)
    {
        $person = User::find($personId);

        if (!$person) {
            return null; // User not found
        }

        $person_rank = $person->rank_id;

        $packages = Package::where('package_code', 'premium_package')->pluck('id');
        // Common counts
        $directCount = User::where('sponsor_id', $person->id)
            ->whereHas('userPackages', function ($query) use ($packages) {
                $query->whereIn('package_id', $packages);
            })
            ->count();
        $totalRanks = [
            'Gold' => 0,
            'Platinum' => 0,
            'Pearl' => 0,
            'Ruby' => 0,
            'Diamond' => 0,
            'DoubleDiamond' => 0,
            'Emerald' => 0,
            'Crown' => 0,
            'RoyalCrown' => 0,
            'Manager' => 0,
            'Ambassador' => 0,
            'RoyalCrownAmbassador' => 0,
        ];

        $downlineUsers = [$person->id];

        while (!empty($downlineUsers)) {
            $directSponsored = User::whereIn('sponsor_id', $downlineUsers)->get();

            foreach ($totalRanks as $rank => $count) {
                $rankId = array_search($rank, array_keys($totalRanks)) + 2;
                $rankCount = $directSponsored->where('rank_id', $rankId)->count();
                $totalRanks[$rank] += $rankCount;
            }

            $downlineUsers = $directSponsored->pluck('id')->toArray();
        }

        $goldCount = $totalRanks['Gold'];
        $platinumCount = $totalRanks['Platinum'];
        $pearlCount = $totalRanks['Pearl'];
        $rubyCount = $totalRanks['Ruby'];
        $diamondCount = $totalRanks['Diamond'];
        $doubleDiamondCount = $totalRanks['DoubleDiamond'];
        $emeraldCount = $totalRanks['Emerald'];
        $crownCount = $totalRanks['Crown'];
        $royalCrownCount = $totalRanks['RoyalCrown'];
        $managerCount = $totalRanks['Manager'];
        $ambassadorCount = $totalRanks['Ambassador'];
        $royalCrownAmbassadorCount = $totalRanks['RoyalCrownAmbassador'];


        $rankCounts = [
            'Gold' => User::where('sponsor_id', $person->id)->where('rank_id', 2)->count(),
            'Platinum' => User::where('sponsor_id', $person->id)->where('rank_id', 3)->count(),
            'Pearl' => User::where('sponsor_id', $person->id)->where('rank_id', 4)->count(),
            'Ruby' => User::where('sponsor_id', $person->id)->where('rank_id', 5)->count(),
            'Diamond' => User::where('sponsor_id', $person->id)->where('rank_id', 6)->count(),
            'DoubleDiamond' => User::where('sponsor_id', $person->id)->where('rank_id', 7)->count(),
            'Emerald' => User::where('sponsor_id', $person->id)->where('rank_id', 8)->count(),
            'Crown' => User::where('sponsor_id', $person->id)->where('rank_id', 9)->count(),
            'RoyalCrown' => User::where('sponsor_id', $person->id)->where('rank_id', 10)->count(),
            'Manager' => User::where('sponsor_id', $person->id)->where('rank_id', 11)->count(),
            'Ambassador' => User::where('sponsor_id', $person->id)->where('rank_id', 12)->count(),
            'RoyalCrownAmbassador' => User::where('sponsor_id', $person->id)->where('rank_id', 13)->count(),
        ];

        // Rank promotion logic
        if ($person_rank) {
            if ($directCount >= 3 && $person_rank < 2) { //Gold
                $person->update(['rank_id' => 2]);

                $originalRank = $this->updateRankAndLog($person, $person_rank, 2);
            }
            if ($rankCounts['Gold'] >= 3 && $directCount >= 10 && $person_rank < 3) { //Platinum
                $person->update(['rank_id' => 3]);

                $originalRank = $this->updateRankAndLog($person, $person_rank, 3);
            }
            if ($rankCounts['Platinum'] >= 2 && $rankCounts['Gold'] >= 2 && $person_rank < 4 || $rankCounts['Pearl'] >= 2  && $person_rank < 4 || $rankCounts['Platinum'] >= 1 && $rankCounts['Gold'] >= 2 && $rankCounts['Pearl'] >= 1  && $person_rank < 4) { //Pearl
                $person->update(['rank_id' => 4]);

                $originalRank = $this->updateRankAndLog($person, $person_rank, 4);
            }
            if ($rankCounts['Pearl'] >= 4 && $directCount >= 14 && $person_rank < 5 || $rankCounts['Ruby'] >= 1 && $directCount >= 14) { //Ruby
                $person->update(['rank_id' => 5]);

                $originalRank = $this->updateRankAndLog($person, $person_rank, 5);
            }
            if ($rankCounts['Ruby'] >= 2 && $rankCounts['Gold'] >= 4 && $person_rank < 6) { //Diamond
                $person->update(['rank_id' => 6]);

                $originalRank = $this->updateRankAndLog($person, $person_rank, 6);
            }
            if ($rankCounts['Diamond'] >= 3 && $person_rank < 7) { //Double Diamond
                $person->update(['rank_id' => 7]);

                $originalRank = $this->updateRankAndLog($person, $person_rank, 7);
            }
            if ($rankCounts['DoubleDiamond'] >= 2 && $person_rank < 8) { //Emerald
                $person->update(['rank_id' => 8]);

                $originalRank = $this->updateRankAndLog($person, $person_rank, 8);
            }
            if ($person_rank == 8) {
                if ($emeraldCount >= 3 && $directCount >= 24 && $person_rank < 9) { //Crown
                    $person->update(['rank_id' => 9]);

                    $originalRank = $this->updateRankAndLog($person, $person_rank, 9);
                }
                if (($rankCounts['Crown'] >= 2 || ($rankCounts['Crown'] >= 1 && $rankCounts['Emerald'] >= 2)) && $person_rank < 10) { //Royal Crown
                    $person->update(['rank_id' => 10]);


                    $originalRank = $this->updateRankAndLog($person, $person_rank, 10);
                }
                if ($person_rank == 10) {
                    if (($rankCounts['RoyalCrown'] >= 3 && $directCount >= 34) || ($crownCount >= 4 && $directCount >= 34) && $person_rank < 11) { //Manager
                        $person->update(['rank_id' => 11]);

                        $originalRank = $this->updateRankAndLog($person, $person_rank, 11);
                    }
                    if ($rankCounts['Manager'] >= 2 && $person_rank < 12) { //Ambassador
                        $person->update(['rank_id' => 12]);


                        $originalRank = $this->updateRankAndLog($person, $person_rank, 12);
                    }
                    if ($person_rank == 12) {
                        if ($rankCounts['Ambassador'] >= 2 && $rankCounts['Manager'] >= 1 && $ambassadorCount >= 1) { // Royal Crown Ambassador
                            $person->update(['rank_id' => 13]);

                            $originalRank = $this->updateRankAndLog($person, $person_rank, 13);
                        }
                    }
                }
            }
        }

        return $person; // Return updated user
    }

    private function updateRankAndLog(User $person, $oldRank, $newRank)
    {
        if ($oldRank < $newRank) { // Only if promotion
            $person->update(['rank_id' => $newRank]);

            UserRankHistory::create([
                'user_id'     => $person->id,
                'rank_id' => $newRank,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Update oldRank so multiple promotions in same call are tracked properly
            return $newRank;
        }
        return $oldRank;
    }


    public function levelincomelist()
    {
        $loggedUser = auth()->user();

        if ($loggedUser->role === 'superadmin') {
            $levelincomes = SponsorLevel::where('package_category', 'premium_package')->get();
            $totalAmount = SponsorLevel::where('package_category', 'premium_package')->sum('amount');
        } else {
            $levelincomes = SponsorLevel::where([
                'sponsor_id' => $loggedUser->id,
                'package_category' => 'premium_package'
            ])->get();
            $totalAmount = SponsorLevel::where('sponsor_id', $loggedUser->id)->where('package_category', 'premium_package')->sum('amount');
        }

        return view('Admin/levelincomelist', compact('levelincomes', 'totalAmount'));
    }

    public function statement(Request $request, $id = null)
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

        $sponsorId = $id ?? $inputId ?? $superadmin ?? auth()->id();

        // $sponsorId = Auth::id(); // Logged-in sponsor's ID
        $user = User::find($sponsorId);


        $totallevelbasic = DB::table('sponsor_levels')->where('sponsor_id', $sponsorId)->where('package_category', 'basic_package')->sum('amount');
        $totallevelpremium = DB::table('sponsor_levels')->where('sponsor_id', $sponsorId)->where('package_category', 'premium_package')->sum('amount');
        $totalrankincome = RankIncome::where(['user_id' => $sponsorId])->sum('amount');
        $totalrefferalincomebasic = ReferralIncome::where(['sponsor_id' => $sponsorId])->where('package_category', 'basic_package')->sum('income');
        $totalrefferalincomepremium = ReferralIncome::where(['sponsor_id' => $sponsorId])->where('package_category', 'premium_package')->sum('income');
        $totalRoyaltyIncome = RoyaltyUserWallet::where('user_id', $sponsorId)->sum('amount');
        $BonusWallet = BonusWallet::where('user_id', $sponsorId)->sum('amount');

        // Fetch basic level incomes
        $levelIncomesBasic = SponsorLevel::where('sponsor_id', $sponsorId)
            ->where('package_category', 'basic_package')
            ->with('user')
            ->select('id', 'user_id', 'amount', 'created_at', DB::raw("'Basci Level Income' as type"))
            ->get();

        // Fetch premium level incomes
        $levelIncomesPremium = SponsorLevel::where('sponsor_id', $sponsorId)
            ->where('package_category', 'premium_package')
            ->with('user')
            ->select('id', 'user_id', 'amount', 'created_at', DB::raw("'Premium Level Income' as type"))
            ->get();

        // Fetch basic referral incomes
        $referralIncomesBasic = ReferralIncome::where('sponsor_id', $sponsorId)
            ->where('package_category', 'basic_package')
            ->with('user')
            ->select('id', 'user_id', 'income as amount', 'created_at', DB::raw("'Basic Sponsor Income' as type"))
            ->get();

        // Fetch premium referral incomes
        $referralIncomesPremium = ReferralIncome::where('sponsor_id', $sponsorId)
            ->where('package_category', 'premium_package')
            ->with('user')
            ->select('id', 'user_id', 'income as amount', 'created_at', DB::raw("'Premium Sponsor Income' as type"))
            ->get();

        // Fetch rank incomes
        $rankIncomes = RankIncome::where('user_id', $sponsorId)
            ->with(['user', 'rank'])
            ->select('id', 'user_id', 'amount', 'created_at', DB::raw("'Rank Income' as type"))
            ->get();

        // Fetch withdrawal requests (successful only)
        $withdrawals = WithdrawalRequest::where('user_id', $sponsorId)
            ->where('status', 'approved') // Successful withdrawals only
            ->with('user')
            ->select('id', 'user_id', 'amount', 'created_at', DB::raw("'Withdrawal' as type"))
            ->get();

        // Fetch child-to-mother transactions
        $childMotherTransactions = collect(); // Default empty collection

        if ($user->mother_id == 1) {
            // User is a mother, fetch incoming transactions
            $childMotherTransactions = ChildMotherPayment::where('mother_id', $sponsorId)
                ->with('child')
                ->select('id', 'child_id as user_id', 'amount', 'created_at', DB::raw("'Income from Child' as type"))
                ->get();
        } else {
            // User is a child, fetch outgoing transactions
            $childMotherTransactions = ChildMotherPayment::where('child_id', $sponsorId)
                ->with('mother')
                ->select('id', 'mother_id as user_id', 'amount', 'created_at', DB::raw("'Payment to Mother' as type"))
                ->get()
                ->map(function ($transaction) {
                    // Convert to a negative amount since it's an outgoing transaction
                    $transaction->amount = -$transaction->amount;
                    return $transaction;
                });
        }

        // Fetch royalty wallet transactions
        $royaltyWalletTransactions = RoyaltyUserWallet::where('user_id', $sponsorId)
            ->select('id', 'user_id', 'amount', 'created_at', DB::raw("'Royalty Wallet' as type"))
            ->get();

        // Fetch bonus Income
        $bonusIncome = BonusWallet::where('user_id', $sponsorId)
            ->select('id', 'user_id', 'amount', 'created_at', DB::raw("'Bonus Income' as type"))
            ->get();

        // Merge and sort all data by date descending
        $bankStatement = $levelIncomesBasic
            ->merge($levelIncomesPremium)
            ->merge($referralIncomesBasic)
            ->merge($referralIncomesPremium)
            ->merge($rankIncomes)
            ->merge($withdrawals)
            ->merge($childMotherTransactions)
            ->merge($royaltyWalletTransactions)
            ->merge($bonusIncome)
            ->sortByDesc('created_at')
            ->values();

        // dd($levelIncomes);

        return view('Admin/statement', compact('bankStatement', 'user', 'totallevelbasic', 'totallevelpremium', 'totalrankincome', 'totalrefferalincomebasic', 'totalrefferalincomepremium', 'totalRoyaltyIncome', 'BonusWallet'));
    }

    public function rank_income_list()
    {
        $user = auth()->user();

        if ($user->role === 'superadmin') {
            $ranks = RankIncome::all();
            $totalAmount = RankIncome::sum('amount');
        } else {
            $ranks = RankIncome::where('user_id', $user->id)->get();
            $totalAmount = $ranks->sum('amount');
        }

        return view('Admin.rank_income_list', compact('ranks', 'totalAmount'));
    }

    public function basicRank_incomeList()
    {
        $user = auth()->user();

        if ($user->role === 'superadmin') {
            $ranks = BasicUserRankIncome::all();
            $totalAmount = BasicUserRankIncome::sum('amount');
        } else {
            $ranks = BasicUserRankIncome::where('user_id', $user->id)->get();
            $totalAmount = $ranks->sum('amount');
        }

        return view('BasicRank.basicRank_incomeList', compact('ranks', 'totalAmount'));
    }

    public function levelincomelistbasic()
    {
        $loggedUser = auth()->user();

        if ($loggedUser->role === 'superadmin') {
            $levelincomes = SponsorLevel::where('package_category', 'basic_package')->get();
            $totalAmount = SponsorLevel::where('package_category', 'basic_package')->sum('amount');
        } else {
            $levelincomes = SponsorLevel::where([
                'sponsor_id' => $loggedUser->id,
                'package_category' => 'basic_package'
            ])->get();
            $totalAmount = SponsorLevel::where('sponsor_id', $loggedUser->id)->where('package_category', 'basic_package')->sum('amount');
        }

        return view('Admin/levelincomebasic', compact('levelincomes', 'totalAmount'));
    }

    public function basicRank_income()
    {
        $users = User::all();

        foreach ($users as $user) {
            $this->BasicCalculateRankForUser($user->id);
        }

        $basicRank = BasicRankAchieve::where('rank_status', 1)->get();

        return view('BasicRank/basicRank_income', compact('basicRank'));
    }

    private function BasicCalculateRankForUser($userId)
    {
        $person = User::find($userId);

        if (!$person) {
            return;
        }

        // ✅ get basic package IDs
        $packages = Package::where('package_code', 'basic_package')->pluck('id');

        // ✅ direct users with basic package
        $directUsers = User::where('sponsor_id', $person->id)
            ->whereHas('userPackages', function ($query) use ($packages) {
                $query->whereIn('package_id', $packages);
            })
            ->get();

        // Condition 1: 10 directs → Star 1
        if ($directUsers->count() >= 10) {
            $this->assignRank($person->id, 1, $person->sponsor_id);
        }

        // Condition 2: 10 directs with Star 1 → Star 2
        $star1Directs = BasicRankAchieve::where('sponsor_id', $person->id)
            ->where('basic_rank_id', 2)
            ->count();

        if ($star1Directs >= 10) {
            $this->assignRank($person->id, 2, $person->sponsor_id);
        }

        // Condition 3: 10 directs with Star 2 → Star 3
        $star3Directs = BasicRankAchieve::where('sponsor_id', $person->id)
            ->where('basic_rank_id', 3)
            ->count();

        if ($star3Directs >= 10) {
            $this->assignRank($person->id, 3, $person->sponsor_id);
        }

        // Condition 4: 10 directs with Star 3 → Star 4
        $star4Directs = BasicRankAchieve::where('sponsor_id', $person->id)
            ->where('basic_rank_id', 4)
            ->count();

        if ($star4Directs >= 10) {
            $this->assignRank($person->id, 4, $person->sponsor_id);
        }

        // Condition 5: If sponsor is Star 4 → this sponsor achieves Star 5
        $sponsor = User::find($person->sponsor_id);

        if ($sponsor) {
            $sponsorRank = BasicRankAchieve::where('sponsor_id', $sponsor->id)
                ->where('basic_rank_id', 5)
                ->exists();

            if ($sponsorRank) {
                $this->assignRank($sponsor->id, 5, $sponsor->sponsor_id);
            }
        }
    }

    /**
     * Assign rank to user if not already achieved
     */
    private function assignRank($userId, $rankLevel, $sponsorId = null)
    {
        $rank = BasicRank::where('level', $rankLevel)->first();

        if (!$rank) {
            return;
        }

        // Check if the user already has this rank
        $already = BasicRankAchieve::where('user_id', $userId)
            ->where('basic_rank_id', $rank->id)
            ->exists();

        if (!$already) {
            // ✅ Only deactivate old ranks when a new one is achieved
            BasicRankAchieve::where('user_id', $userId)
                ->where('rank_status', 1)
                ->update(['rank_status' => 0]);

            // Create new rank achievement
            BasicRankAchieve::create([
                'basic_rank_id' => $rank->id,
                'user_id' => $userId,
                'sponsor_id' => $sponsorId,
                'status' => 1, // active
            ]);
        }
    }
}
