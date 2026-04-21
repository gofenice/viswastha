<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\HolidayPackageBooking;
use App\Models\LocalBodyType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Message;
use App\Models\Package;
use App\Models\PinGeneration;
use App\Models\PinTransferDetail;
use App\Models\UserBankingDetail;
use App\Models\UserPackage;
use App\Models\Rank;
use App\Models\RoyaltyIncomeUser;
use App\Models\RoyaltyIncomeWallet;
use App\Models\RoyaltyUserWallet;
use App\Models\Product;
use App\Models\ProductDeliveryDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function loginprocess(Request $request)
    {

        $request->validate([
            'identifier' => 'required', // No need to validate type, as we'll handle it based on input
            'password' => 'required',
        ], [
            'identifier.required' => 'Must enter your email or connection ID',
            'password.required' => 'Must enter your password',
        ]);

        $identifier = $request->input('identifier');
        $user = null;

        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            // If it's a valid email, query by email
            $user = User::where('email', $identifier)->first();
        } else {
            // If it's not an email, treat it as connection ID
            $user = User::where('connection', $identifier)->first();
        }
        // If user is found and password matches
        if ($user && Hash::check($request->input('password'), $user->password)) {
            Auth::login($user);  // Log the user in

            // Check if user needs KYC verification
            if ($user->role == 'user' && $user->pan_card_no == 'STORE' && $user->mother_id == 1) {
                return redirect()->route('kyc_verification')->with('info', 'Please complete your KYC verification to access all features.');
            }

            if ($user->role == 'user') {
                return redirect()->route('adminhome')->with('success', 'Login successfully.');
            } else if (in_array($user->role, ['superadmin', 'admin'])) {
                return redirect()->route('adminhome')->with('success', 'Login successfully.');
            } elseif ($user->role == 'partner') {
                return redirect()->route('partner')->with('success', 'Login successfully.');
            } elseif ($user->role == 'gst') {
                return redirect()->route('partner')->with('success', 'Login successfully.');
            } elseif ($user->role == 'repurchase') {
                return redirect()->route('repurchasedb')->with('success', 'Login successfully.');
            } elseif ($user->role == 'shop') {
                return redirect()->route('shop_dashboard')->with('success', 'Login successfully.');
            } else {
                return redirect()->route('/')->with('error', 'User not found.');
            }
        } else {
            return redirect()->route('/')->with('error', 'Check your Username and Password');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('/')->with('success', 'Logout successfully.');
    }

    public function change_password_process(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'old_password' => 'required',
            'password' => 'required|min:4|confirmed',
        ]);

        // Check if the old password matches the current password
        if (!Hash::check($request->old_password, $user->password)) {
            return redirect()->back()->withErrors(['old_password' => 'The provided old password does not match your current password.']);
        }

        // Update the password
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('/')->with('successchange', 'Password updated successfully.');
    }

    public function generate_pin()
    {
        $user = Auth::user();
        $packages = Package::where('status', 1)->get();
        // The join_amount field in the Users table acts as a pin wallet.
        $walletBalance = $user->join_amount;
        return view('Admin/generate_pin', compact('packages', 'walletBalance'));
    }

    public function get_package(Request $request)
    {
        $packageId = $request->input('packageId');
        $package = Package::find($packageId);
        if ($package) {
            return response()->json(['success' => true, 'amount' => $package->amount]);
        } else {
            return response()->json(['success' => false, 'message' => 'Package not found.']);
        }
    }

    public function add_pin(Request $request)
    {
        $request->validate([
            'package_id' => 'required|exists:packages,id',
            'numOfPins' => 'required|integer|min:1',
            'totalCost' => 'required|numeric|min:0',
        ]);
        $user = auth()->user();
        $totalCost = $request->input('totalCost');

        // The join_amount field in the Users table acts as a pin wallet.

        if ($user->join_amount < $totalCost) {
            return redirect()->route('generate_pin')->with('error', 'Insufficient wallet balance.');
        }
        $user->join_amount -= $totalCost;
        $user->save();

        for ($i = 0; $i < $request->input('numOfPins'); $i++) {
            $uniqueId = $this->generateUniqueId();

            $password = $this->generatePassword();

            $pin = PinGeneration::create([
                'user_id' => $user->id,
                'package_id' => $request->input('package_id'),
                'unique_id' => 'PIN' . $uniqueId,
                'password' => $password,
                'pin_amount' => $totalCost / $request->input('numOfPins'),
                'status' => 'pending',
                'used' => 0,
            ]);

            // Create the entry in pin_transfer_details table
            PinTransferDetail::create([
                'from_user_id' => null,
                'to_user_id' => $user->id,
                'pin_id' => $pin->id,
                'used' => 0,
                'status' => 'pending',
            ]);
        }
        return redirect()->route('generate_pin')->with('success', 'Pins generated successfully!');
    }
    private function generateUniqueId()
    {
        do {
            $uniqueId = rand(1000000000, 9999999999);
        } while (PinGeneration::where('unique_id', $uniqueId)->exists());

        return $uniqueId;
    }
    private function generatePassword()
    {
        do {
            $password = rand(1000, 9999);
        } while (PinGeneration::where('password', $password)->exists());

        return $password;
    }

    public function view_pin()
    {
        $user = auth()->user();
        if ($user->role == 'superadmin') {
            // $pins = PinGeneration::orderBy('status', 'asc')->get();
            $pins = PinTransferDetail::with('userPackages')
                ->orderByRaw("FIELD(status, 'pending', 'transferred', 'redeemed')")
                ->get();
        } else {

            $pins = PinTransferDetail::where('to_user_id', $user->id)
                // ->orWhere('from_user_id', $user->id)
                ->with('userPackages')
                ->orderByRaw("FIELD(status, 'pending', 'transferred', 'redeemed')")
                ->get();
        }

        $avaliblepins = PinGeneration::where('user_id', $user->id)->where('used', 0)->where('status', '!=', 'redeemed')->get();

        $activePins = PinGeneration::where('used', 0)
            ->count();

        $inactivePins =  PinGeneration::where('used', '!=', 0)
            ->count();

        $totalPins = PinGeneration::all()->count();

        $prepackages = Package::where('package_code', 'premium_package')->pluck('id');
        $baispackages = Package::where('package_code', 'basic_package')->pluck('id');
        $prepincount = PinGeneration::whereIn('package_id', $prepackages)->count();
        $basicpincount = PinGeneration::whereIn('package_id', $baispackages)->count();
        $prepincountused = PinGeneration::whereIn('package_id', $prepackages)->where('used', '!=', 0)->count();
        $basicpincountused = PinGeneration::whereIn('package_id', $baispackages)->where('used', '!=', 0)->count();


        return view('Admin/view_pin', compact('pins', 'avaliblepins', 'inactivePins', 'activePins', 'totalPins', 'prepincount', 'basicpincount', 'prepincountused', 'basicpincountused'));
    }

    public function search_pin(Request $request)
    {
        $user = auth()->user();

        $pinNumber = $request->input('pinNumber');
        $fromDate = $request->input('fromDate');
        $toDate = $request->input('toDate');
        $status = $request->input('status');
        $userid = $request->input('userid');

        $userdts = User::where('connection', $userid)->first(); // Find user by connection

        $query = PinTransferDetail::query();

        // Filter by user (from_user_id or to_user_id)
        // $query->where(function ($q) use ($user) {
        //     $q->where('from_user_id', $user->id)
        //         ->orWhere('to_user_id', $user->id);
        // });

        // Ensure $userdts is not null before filtering by to_user_id
        if (!empty($userid) && $userdts) {
            $query->where('to_user_id', $userdts->id);
        }

        // Filter by date range
        if ($fromDate && $toDate) {
            $startDate = Carbon::createFromFormat('Y-m-d', $fromDate)->startOfDay();
            $endDate = Carbon::createFromFormat('Y-m-d', $toDate)->endOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        // Filter by status
        if ($status && $status !== 'all') {
            if ($status === 'unused') {
                $query->where('used', 0)->where('status', 'pending')
                    ->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END");
            } elseif ($status === 'transferred') {
                $query->where('status', 'transferred');
            } elseif ($status === 'premium') {
                $premiumPackageId = Package::where('package_code', 'premium_package')->value('id');
                $query->whereHas('pin', function ($q) use ($premiumPackageId) {
                    $q->where('package_id', $premiumPackageId);
                });
            } elseif ($status === 'basic') {
                $premiumPackageId = Package::where('package_code', 'basic_package')->value('id');
                $query->whereHas('pin', function ($q) use ($premiumPackageId) {
                    $q->where('package_id', $premiumPackageId);
                });
            } else {
                $query->where('used', $status === 'unused' ? 0 : 1);
            }
        }

        $pins = $query->get();

        // Fetch available pins (unused, not redeemed)
        $avaliblepins = PinGeneration::where('user_id', $user->id)
            ->where('used', 0)
            ->where('status', '!=', 'redeemed')
            ->get();

        $activePins = PinGeneration::where('used', 0)
            ->count();

        $inactivePins =  PinGeneration::where('used', '!=', 0)
            ->count();

        $totalPins = PinGeneration::all()->count();


        return view('Admin.view_pin', compact('pins', 'avaliblepins', 'inactivePins', 'activePins', 'totalPins'));
    }

    public function forgotpassword()
    {
        return view('Password/forgot_password');
    }

    public function recoverPassword(Request $request)
    {
        $request->validate([
            'user_id' => 'required|string',
            'email' => 'required|email',
        ]);

        $user = DB::table('users')
            ->where('connection', $request->user_id)
            ->where('email', $request->email)
            ->first();
        if ($user) {
            $newPassword = rand(1000, 9999);

            DB::table('users')->where('email', $request->email)->where('connection', $request->user_id)->update([
                'password' => Hash::make($newPassword),
            ]);

            Mail::send('emails.forgot_password', ['password' => $newPassword], function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('Your New Password');
            });

            return redirect()->route('/')->with('success', 'A new password has been sent to your email.');
        } else {
            return redirect()->route('forgot_password')->with('error', 'Invalid User ID or Email. Please verify your credentials.');
        }
    }

    public function newregister()
    {

        $packages = Package::all();
        return view('Admin/new_register', compact('packages'));
    }

    public function fetchSponsorName(Request $request)
    {
        $sponsor = User::where('connection', $request->sponsor_id)->first();

        if ($sponsor) {
            return response()->json(['success' => true, 'name' => $sponsor->name]);
        }
        return response()->json(['success' => false, 'message' => 'Sponsor not found.']);
    }


    public function fetchParentInfo(Request $request)
    {
        $parent = User::where('connection', $request->parent_id)->first();

        if ($parent) {
            $user_code = 'VM' . strtoupper(substr(uniqid(), -6));
            $positions = [];

            // Check left and right availability
            if (User::where('parent_id', $parent->id)->where('position', 'left')->doesntExist()) {
                $positions[] = 'left';
            }
            if (User::where('parent_id', $parent->id)->where('position', 'right')->doesntExist()) {
                $positions[] = 'right';
            }

            if (!empty($positions)) {
                return response()->json(['success' => true, 'name' => $parent->name, 'positions' => $positions, 'user_code' => $user_code]);
            }
            return response()->json(['success' => false, 'message' => 'No positions available.']);
        }
        return response()->json(['success' => false, 'message' => 'Parent not found.']);
    }

    public function unassignpin(Request $request)
    {
        $pin = PinGeneration::where('id', $request->un_pinid)->first();

        if ($pin) {
            $pin->update([
                'transfer_to' => null,
                'status' => 'pending',
            ]);
            return redirect()->route('view_pin')->with('success', 'Pin unassigned successfully.');
        } else {
            return redirect()->route('view_pin')->with('error', 'Pin not found');
        }
    }

    public function rank_details()
    {
        $userCounts = DB::table('users')
            ->select('rank_id', DB::raw('COUNT(*) as user_count'))
            ->groupBy('rank_id')
            ->get()
            ->pluck('user_count', 'rank_id')
            ->toArray();

        $allRanks = [13, 12, 11, 10, 9, 8, 7, 6, 5, 4, 3, 2];

        $ranks = DB::table('ranks')
            ->whereIn('id', $allRanks)
            ->pluck('rank_name', 'id')
            ->toArray();

        $rankData = array_map(function ($rankId) use ($ranks, $userCounts) {
            return [
                'id' => $rankId,
                'rank_name' => $ranks[$rankId] ?? 'Unknown Rank',
                'user_count' => $userCounts[$rankId] ?? 0, // User Count
            ];
        }, $allRanks);

        return view('Admin/rank_details', compact('rankData'));
    }

    public function getUsersByRank($rank)
    {
        $users = User::where('rank_id', $rank)->get();
        $rank = Rank::where('id', $rank)->first();
        return view('Admin/rank_users', compact('users', 'rank'));
    }

    public function rank_tree()
    {
        $currentUser = auth()->user();

        $rankIds = [
            'Gold' => 2,
            'Platinum' => 3,
            'Pearl' => 4,
            'Ruby' => 5,
            'Diamond' => 6,
            'DoubleDiamond' => 7,
            'Emerald' => 8,
            'Crown' => 9,
            'RoyalCrown' => 10,
            'Manager' => 11,
            'Ambassador' => 12,
            'RoyalCrownAmbassador' => 13,
        ];

        $rankCounts = User::where('sponsor_id', $currentUser->id)
            ->whereIn('rank_id', array_values($rankIds))
            ->selectRaw('rank_id, COUNT(*) as count')
            ->groupBy('rank_id')
            ->pluck('count', 'rank_id')
            ->toArray();

        // Map counts to rank names
        $totalRanks = array_map(fn($id) => $rankCounts[$id] ?? 0, $rankIds);


        return view('Admin/rank_tree', compact('currentUser', 'totalRanks'));
    }

    public function userDetails($rank)
    {
        $currentUser = auth()->user();

        $rankUsers = User::where('sponsor_id', $currentUser->id)
            ->where('rank_id', $rank)
            ->get();

        $rank = Rank::where('id', $rank)->first();

        return view('Admin.user_rank_details', compact('rankUsers', 'rank'));
    }

    public function view_sponsor()
    {
        if (auth()->user()->role === 'superadmin') {
            $sponsors = User::with(['downlines.userPackages.package'])->whereHas('downlines')->get();
        } else {
            $sponsors = User::with(['downlines.userPackages.package'])
                ->where('id', auth()->id())
                ->whereHas('downlines')
                ->get();
        }

        return view('Admin/view_sponsor', compact('sponsors'));
    }
    public function view_sponsor_superadmin()
    {
        // $sponsors = User::whereHas('downlines')->get();
        $sponsors = User::whereHas('downlines')->with(['userPackages.package', 'downlines.userPackages.package'])->get();
        return view('Admin/view_sponser_superadmin', compact('sponsors'));
    }
    public function edit_bank_details()
    {
        $userBankDetails = UserBankingDetail::where('user_id', auth()->id())->first();
        return view('Admin/edit_bank_details', compact('userBankDetails'));
    }

    public function bank_details_update(Request $request)
    {
        $validated = $request->validate([
            'ifs_code' => [
                'required',
                'regex:/^[A-Z]{4}0[A-Z0-9]{6}$/',
            ],
            'bank_name' => 'required|regex:/^[A-Za-z\s]+$/|max:100',
            'branch_name' => 'required|regex:/^[A-Za-z\s]+$/|max:100',
            'account_number' => 'required|numeric|digits_between:6,20',
            'account_holder_name' => 'required|regex:/^[A-Za-z\s]+$/|max:100',
            'passbook_img' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'pancard_img' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ], [
            'ifs_code.regex' => 'The IFSC Code must be in the format: 4 uppercase letters, followed by 0, and 6 alphanumeric characters.',
            'bank_name.regex' => 'The Bank Name must only contain letters and spaces.',
            'branch_name.regex' => 'The Branch Name must only contain letters and spaces.',
            'account_number.numeric' => 'The Account Number must only contain numbers.',
            'account_holder_name.regex' => 'The Account Holder Name must only contain letters and spaces.',
        ]);

        $user_id = auth()->id();
        $imagePathPass = null;
        $imagePathPan = null;
        if ($request->hasFile('passbook_img')) {
            $file = $request->file('passbook_img');
            if (in_array($file->getClientOriginalExtension(), ['jpeg', 'jpg', 'png'])) {
                $imageName = time() . '-' . pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '.webp';
                $image = imagecreatefromstring(file_get_contents($file->getRealPath()));
                if ($image !== false) {
                    imagewebp($image, public_path('assets/Bank_details/' . $imageName), 80);
                    imagedestroy($image);
                    $imagePathPass = 'assets/Bank_details/' . $imageName;
                }
            }
        }
        if ($request->hasFile('pancard_img')) {
            $file = $request->file('pancard_img');
            if (in_array($file->getClientOriginalExtension(), ['jpeg', 'jpg', 'png'])) {
                $imageName = time() . '-' . pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '.webp';
                $image = imagecreatefromstring(file_get_contents($file->getRealPath()));
                if ($image !== false) {
                    imagewebp($image, public_path('assets/Bank_details/' . $imageName), 80);
                    imagedestroy($image);
                    $imagePathPan = 'assets/Bank_details/' . $imageName;
                }
            }
        }

        // Update if record exists, otherwise create a new one
        UserBankingDetail::updateOrCreate(
            ['user_id' => $user_id], // Condition to check if record exists
            [
                'ifs_code' => $validated['ifs_code'],
                'bank_name' => $validated['bank_name'],
                'branch_name' => $validated['branch_name'],
                'account_number' => $validated['account_number'],
                'account_holder_name' => $validated['account_holder_name'],
                'status' => 1,
                'bank_passbook_image' => $imagePathPass,
                'pancard_image' => $imagePathPan,
            ]
        );

        return back()->with('success', 'Bank details saved successfully!');
    }

    public function bankDetailList()
    {
        $banklists = UserBankingDetail::latest()->get();

        return view('Admin.bank_detail_list', compact('banklists'));
    }

    public function approvebank(Request $request)
    {
        $bankdtId = $request->input('bankdtId');
        $status = $request->input('status');
        $note = $request->input('reject_note');

        $banklists = UserBankingDetail::where('id', $bankdtId)->first();

        if ($banklists) {
            $banklists->status = $status;
            $banklists->note = $note;
            $banklists->save();

            return redirect()->route('bank_detail_list')->with('success', 'Bank status updated successfully.');
        }

        return redirect()->route('bank_detail_list')->with('error', 'Bank details not found.');
    }

    // public function our_achiever()
    // {
    //     $packages = Package::all();
    //     $date = Carbon::today()->toDateString();
    //     $packageId = 13;
    //     $users = User::whereHas('userPackages', function ($query) use ($date, $packageId) {
    //         $query->whereDate('created_at', $date)
    //             ->where('package_id', $packageId);
    //     })->select('id', 'name', 'user_image', 'connection')->get();
    //     return view('Admin/our_achiever', compact('users', 'packages'));
    // }

    public function our_achiever_list(Request $request)
    {
        $packages = Package::all();
        $request->validate([
            'date' => 'required|date',
            'package' => 'required'
        ]);

        $date = $request->input('date');
        $packageId = $request->input('package');

        $users = User::whereHas('userPackages', function ($query) use ($date, $packageId) {
            $query->whereDate('created_at', $date);
            if ($packageId != 'all') {
                $query->where('package_id', $packageId);
            }
        })->select('id', 'name', 'user_image')->get();

        return view('Admin/our_achiever', compact('users', 'packages'));
    }
    public function bulkTransfer(Request $request)
    {
        $validated = $request->validate([
            'selected_pins' => 'required|array',  // Ensure an array of pin IDs is provided
            'selected_pins.*' => 'exists:pin_generations,id',  // Validate each pin exists
            'transferuser_id' => 'required|exists:users,id', // Validate the target user
        ]);

        $user = User::find($validated['transferuser_id']);

        if (!$user) {
            return redirect()->route('view_pin')->with('error', 'Invalid User.');
        }

        $pins = PinGeneration::whereIn('id', $validated['selected_pins'])->get();

        if ($pins->isEmpty()) {
            return redirect()->route('view_pin')->with('error', 'No valid pins selected.');
        }

        foreach ($pins as $pin) {

            $existingTransfer = DB::table('pin_transfer_details')
                ->where('pin_id', $pin->id)
                ->first();

            if ($existingTransfer) {
                // Update existing transfer status
                DB::table('pin_transfer_details')
                    ->where('pin_id', $pin->id)
                    ->update([
                        'status' => 'transferred',
                        'updated_at' => now(),
                    ]);
            }

            DB::table('pin_transfer_details')->insert([
                'from_user_id' => auth()->id(),
                'to_user_id' => $user->id,
                'pin_id' => $pin->id,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);


            $pin->user_id = $user->id;
            $pin->status = 'transferred';
            $pin->save();
        }

        return redirect()->route('view_pin')->with('success', count($pins) . ' Pins successfully transferred to ' . $user->name . '(' . $user->connection . ')');
    }

    public function gettransferUserName(Request $request)
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

    public function showDetails(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'pin_id' => 'required|exists:pin_transfer_details,pin_id',
        ]);

        $user = User::findOrFail($request->user_id);

        $pins = PinTransferDetail::where(function ($query) use ($request) {
            $query->where('from_user_id', '>=', $request->user_id)
                ->orWhere('to_user_id', '>=', $request->user_id);
        })
            ->where('pin_id', $request->pin_id)
            ->get();

        return view('Admin.pin_transfer_detail', compact('user', 'pins'));
    }

    public function pinHistory(Request $request)
    {
        $rootUserId = $id ?? $inputId ?? $superadmin ?? auth()->id();

        $pins = PinTransferDetail::where('to_user_id', $rootUserId)
            // ->orWhere('from_user_id', $user->id)
            ->with('userPackages')
            ->orderByRaw("FIELD(status, 'pending', 'transferred', 'redeemed')")
            ->get();

        return view('Admin.pin_history', compact('pins'));
    }

    public function royalty_users()
    {
        $royaltyusers = RoyaltyIncomeUser::all();
        return view('Admin.royalty_users', compact('royaltyusers'));
    }

    public function add_royalty_user(Request $request)
    {
        $request->validate([
            'userId' => 'required|exists:users,connection',
        ]);

        $user = User::where('connection', $request->userId)->first();

        $existUser = RoyaltyIncomeUser::where('user_id', $user->id)->first();

        if ($existUser) {
            return redirect()->route('royalty_users')->with('error', 'Already Exist.');
        }

        // Ensure user exists
        if (!$user) {
            return redirect()->route('royalty_users')->with('error', 'User not found.');
        }

        RoyaltyIncomeUser::create([
            'user_id' => $user->id,
            'status' => 1,
        ]);

        return redirect()->route('royalty_users')->with('success', 'User successfully added to royalty income.');
    }
    public function royalty_wallet()
    {
        $royaltyWalletTotal = RoyaltyIncomeWallet::sum('amount');
        $royaltyWalletActive = RoyaltyIncomeWallet::Where('is_redeemed', 0)->sum('amount');
        $royaltyWalletInactive = RoyaltyIncomeWallet::Where('is_redeemed', 1)->sum('amount');

        $royaltyWallets = RoyaltyIncomeWallet::orderBy('id', 'desc')->get();


        return view('Admin.royalty_wallet', compact('royaltyWallets', 'royaltyWalletTotal', 'royaltyWalletActive', 'royaltyWalletInactive'));
    }
    public function redeemRoyaltyUsers()
    {
        $royaltyWalletActive = RoyaltyIncomeWallet::where('is_redeemed', 0)->sum('amount');

        $activeUsers = RoyaltyIncomeUser::where('status', 1)->get();

        if ($activeUsers->count() == 0 || $royaltyWalletActive == 0) {
            return response()->json(['message' => 'No active users or no funds available'], 400);
        }

        $splitAmount = $royaltyWalletActive / $activeUsers->count();

        foreach ($activeUsers as $user) {

            // Stop this process. It is now being handled manually.
            // User::where('id', $user->user_id)->increment('total_income', $splitAmount);

            RoyaltyUserWallet::create([
                'user_id' => $user->user_id,
                'amount' => $splitAmount,
                'status' => 1
            ]);
        }

        RoyaltyIncomeWallet::where('is_redeemed', 0)->update(['is_redeemed' => 1]);

        return redirect()->route('royaltyUsersAmtList')->with('success', 'Amount successfully added to royalty Users.');
    }

    public function royaltyUsersAmtList()
    {
        $royaltyUsersAmtList = RoyaltyUserWallet::all();

        return view('Admin.royaltyUsersAmtList', compact('royaltyUsersAmtList'));
    }

    public function edit_royalty_user(Request $request)
    {
        $id = $request->input('id');
        $status = $request->input('status');

        $royaltyUser = RoyaltyIncomeUser::find($id);

        $royaltyUser->status = $status;
        $royaltyUser->save();

        return redirect()->route('royalty_users')->with('success', 'User status successfully changed.');
    }

    public function terms_Conditions()
    {
        return view('Admin/Terms_and_Conditions');
    }
    public function terms_loginpage()
    {
        return view('Admin/terms_loginpage');
    }
    public function viewProducts()
    {
        $premiumPackageIds = Package::where('package_code', 'premium_package')->pluck('id');
        $basicPackageIds = Package::where('package_code', 'basic_package')->pluck('id');

        $packagesPremium = Product::whereIn('package_id', $premiumPackageIds)->get();
        $packagesBasic = Product::whereIn('package_id', $basicPackageIds)->get();
        // dd($basicPackageIds);

        return view('Admin/view_products', compact('packagesPremium', 'packagesBasic'));
    }

    public function orderProduct()
    {
        $user = Auth::user();

        $userPackages = UserPackage::with('package')->where('user_id', $user->id)->get();
        $packageIds = $userPackages->pluck('package_id');

        $orderedProductIds = ProductDeliveryDetail::where('user_id', $user->id)->pluck('package_id');
        $orderedProductIdsHl = HolidayPackageBooking::where('user_id', $user->id)->pluck('package_id');

        $userProducts = Product::whereIn('package_id', $packageIds)
            ->whereNotIn('package_id', $orderedProductIds)
            ->whereNotIn('package_id', $orderedProductIdsHl)
            ->where('product_status', 1)
            ->get();

        return view('Admin/order_product', compact('userProducts', 'packageIds', 'user'));
    }
    public function view_Order()
    {
        $user = Auth::user();

        $userPackages = UserPackage::with('package')->where('user_id', $user->id)->get();
        $packageIds = $userPackages->pluck('package_id');
        $userProducts = Product::whereIn('package_id', $packageIds)->get();

        $getproducts = ProductDeliveryDetail::where('user_id', $user->id)->get();
        $holidayBookings = HolidayPackageBooking::where('user_id', $user->id)->get();
        // dd($holidayBookings);

        return view('Admin/view_order', compact('userProducts', 'packageIds', 'getproducts', 'user', 'holidayBookings'));
    }

    public function royalty_user_wallet()
    {
        $user = auth()->user();

        if ($user->role === 'superadmin') {
            $users = RoyaltyUserWallet::all();
            $totalAmount = $users->sum('amount');
        } else {
            $users = RoyaltyUserWallet::where('user_id', $user->id)->get();
            $totalAmount = $users->sum('amount');
        }
        return view('Admin.royalty_user_incentive', compact('users', 'totalAmount'));
    }

    public function shop_ownerReg()
    {
        $districts = District::get();
        $localbodytypes = LocalBodyType::where('lbt_code', 1)->get();

        return view('Admin/shop_ownerReg', compact('localbodytypes', 'districts'));
    }
    public function getDistricts(Request $request)
    {
        $districts = District::where('state_id', $request->state_id)->where('status', 1)->get();
        return response()->json($districts);
    }

    public function register_wpan()
    {
        return view('Admin/register_wpan');
    }

    public function register_wpan_rr()
    {
        return view('Admin/register_wpan_rr');
    }
}
