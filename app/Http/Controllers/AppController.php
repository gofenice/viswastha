<?php

namespace App\Http\Controllers;

use App\Models\AppInterface;
use App\Models\CategoryPercentage;
use App\Models\District;
use App\Models\FcmToken;
use App\Models\Franchisee;
use App\Models\LocalBody;
use App\Models\LocalBodyType;
use App\Models\OfflineProductBill;
use App\Models\RepurchaseWallet;
use App\Models\Shop;
use App\Models\ShopCoupon;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Services\FcmService;

class AppController extends Controller
{
    public function applogin(Request $request)
    {
        $identifier = $request->email;
        $user = null;

        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            // If it's a valid email, query by email
            $user = User::where('email', $identifier)->where('role', '!=', 'superadmin')->first();
        } else {
            // If it's not an email, treat it as connection ID
            $user = User::where('connection', $identifier)->where('role', '!=', 'superadmin')->first();
        }

        // $user = User::where('connection', $request->email)->where('role', '!=', 'superadmin')->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['status' => false, 'message' => 'Invalid credentials'], 401);
        }

        if (!in_array($user->role, ['user', 'shop'])) {
            return response()->json(['status' => false, 'message' => 'Access denied for this role'], 403);
        }


        $sponsor = User::find($user->sponsor_id);

        return response()->json([
            'status' => true,
            'message' => 'Login successful',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone_no,
                'connection' => $user->connection,
                'pan_card_no' => $user->pan_card_no,
                'address' => $user->address,
                'pincode' => $user->pincode,
                'role' => $user->role,
                'sponsor' => $sponsor ? [
                    'id' => $sponsor->connection,
                    'name' => $sponsor->name,
                    'email' => $sponsor->email,
                ] : null
            ]
        ]);
    }
    // public function shops()
    // {
    //     $shops = Shop::all(['id', 'name']); // or whatever columns you need
    //     return response()->json($shops);
    // }

    public function genofflinebill(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|string|max:255',
            'shop_id' => 'required|exists:shops,id',
            'purchase_date' => 'required|date',
            'product_count' => 'required|integer',
            'amount' => 'required|numeric',
            'image' => 'nullable|image|max:2048', // Optional image
            'category_id' => 'required',
            'local_body_type_id' => 'required|exists:local_body_types,id',
            'local_body_id' => 'required|exists:local_bodies,id',
        ], [
            'image.max' => 'Your image size is bigger than our 2MB requirement.',
        ]);

        $user = User::where('connection', $request->user_id)->first();

        if (!$user) {
            return response()->json(['status' => false, 'message' => 'User not found'], 404);
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('orders', 'public');
        }

        $order = OfflineProductBill::create([
            'user_id' => $user->id,
            'shop_id' => $validated['shop_id'],
            'purchase_date' => $validated['purchase_date'],
            'product_count' => $validated['product_count'],
            'total' => $validated['amount'],
            'image_path' => $imagePath,
            'status' => 1,
            'category_id' => $validated['category_id'],
            'lbt_id' => $validated['local_body_type_id'],
            'lb_id' => $validated['local_body_id'],
        ]);

        $shop = Shop::where('id', $order->shop_id)->first();

        if (!$shop) {
            return response()->json(['status' => false, 'message' => 'Shop not found'], 404);
        }

        $token = FcmToken::where('user_id', $shop->owner_id)->value('token');

        if ($token) {
            try {
                $fcm = new FcmService();
                $fcm->sendNotification(
                    $token,
                    'New Order Bill Received🚚',
                    'You have received a new order bill from ' . $user->name . '!🧾',
                    ['order_id' => $order->id]
                );
            } catch (\Exception $e) {
                // Optionally handle silently or report somewhere else
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Order created and notification process completed.',
        ]);
    }

    public function orderslist(Request $request)
    {
        $userId = $request->input('user_id');

        $orders = OfflineProductBill::with('shop')
            ->where('user_id', $userId)
            ->get();

        $orders = $orders->map(function ($order) {
            return [
                'id' => $order->id,
                'product_count' => $order->product_count,
                'total' => $order->total,
                'purchase_date' => Carbon::parse($order->purchase_date)->format('d M Y'),
                'image_url' => $order->image_path ? asset('storage/' . $order->image_path) : null,
                'shop_name' => $order->shop ? $order->shop->name : 'N/A',
                'product_name' => 'Order #' . $order->id,
                'status' => $order->status,
            ];
        });

        return response()->json($orders);
    }

    public function getWalletSummary(Request $request)
    {
        $userId = $request->input('user_id');

        $walletEntries = RepurchaseWallet::where('user_id', $userId)
            // ->where('status', 1) // Only approved entries
            ->orderByDesc('created_at')
            ->get();

        $transactions = $walletEntries->map(function ($entry) {
            return [
                'amount' => $entry->amount,
                'created_at' => Carbon::parse($entry->created_at)->format('d M Y • h:i A'),
                'type' => $entry->amount_type,
            ];
        });

        $totalBalance = $walletEntries->where('is_redeemed', 0)->sum('amount');

        return response()->json([
            'balance' => $totalBalance,
            'transactions' => $transactions,
            'userid' => $userId,
        ]);
    }

    public function getShopOrders(Request $request)
    {
        $userId = $request->query('user_id');
        $shopid = Shop::where('owner_id', $userId)->first();
        $orders = OfflineProductBill::where('shop_id', $shopid->id)
            ->with('shop')
            ->with('user')
            ->with('category')
            ->orderBy('purchase_date', 'desc')
            ->get();

        $orders = $orders->map(function ($order) {
            return [
                'id' => $order->id,
                'product_count' => $order->product_count,
                'total' => $order->total,
                'purchase_date' => Carbon::parse($order->purchase_date)->format('d M Y'),
                'image_url' => $order->image_path
                    ? asset('storage/orders/' . basename($order->image_path))
                    : null,
                'shop_name' => $order->shop?->name ?? 'N/A',
                'user' => $order->user,
                'orderuser' => $order->user?->id,
                'category' => $order->category?->name,
                'percentage' => $order->category?->percentage,
                'status' => $order->status,
            ];
        });

        return response()->json($orders);
    }

    public function getOrderSummary($user_id)
    {
        // Fetch the shop based on the owner ID
        $shop = Shop::where('owner_id', $user_id)->first();

        if (!$shop) {
            return response()->json([
                'status' => false,
                'message' => 'Shop not found for this user.',
            ], 404);
        }

        // Get the shop ID
        $shopId = $shop->id;

        // Count different statuses
        $newOrders = OfflineProductBill::where('shop_id', $shopId)->where('status', 1)->count();
        $confirmedOrders = OfflineProductBill::where('shop_id', $shopId)->where('status', 2)->count();
        $rejectedOrders = OfflineProductBill::where('shop_id', $shopId)->where('status', 3)->count();
        $totalOrders = OfflineProductBill::where('shop_id', $shopId)->count();

        // Build summary array
        $summary = [
            'new' => $newOrders,
            'confirmed' => $confirmedOrders,
            'rejected' => $rejectedOrders,
            'total' => $totalOrders,
        ];

        return response()->json([
            'status' => true,
            'data' => $summary,
        ]);
    }

    public function addcategoryper()
    {
        $categoryList = CategoryPercentage::all();

        return view('Admin.addcategoryper', compact('categoryList'));
    }

    public function add_category(Request $request)
    {
        $validated = $request->validate([
            'categoryName' => ['required', 'string', 'max:255', 'regex:/^[^0-9]*$/'],
            'percentage' => ['required', 'numeric', 'regex:/^\d+(\.\d{1,2})?$/'],
            'status' => 'required|boolean',
        ]);
        CategoryPercentage::create([
            'name' => $validated['categoryName'],
            'percentage' => $validated['percentage'],
            'status' => $validated['status'],
        ]);
        return redirect()->route('addcategoryper')->with('success', 'Added Category Successfully.');
    }
    public function categories()
    {
        $categories = CategoryPercentage::all(['id', 'name']); // or whatever columns you need
        return response()->json($categories);
    }


    public function repurchaseCommission(Request $request)
    {

        return response()->json([
            'status' => 'success',
            'message' => 'Bill Approved successfully.',
        ]);
    }


    public function adminbillapprove(Request $request)
    {
        $orderId = $request->input('order_id');
        $amount = $request->input('amount');
        $userid = $request->input('user_id');
        $AmountPercent = $request->input('percentage');
        $shopid = $request->input('shop_id');
        // $shopid = 2;


        $user = User::where('id', $userid)->first();

        $CommissionAmount = ($amount * $AmountPercent) / 100;

        $offlineProduct = OfflineProductBill::find($orderId);

        if (!$offlineProduct) {
            return redirect()->route('offlinePurchase_listpr')->with('error', 'Order not found');
        }
        if ($offlineProduct->total != $amount) {
            return redirect()->route('offlinePurchase_listpr')->with('error', 'Amount mismatch with the bill total');
        }

        if ($offlineProduct->shop_id != $shopid) {
            return redirect()->route('offlinePurchase_listpr')->with('error', 'Shop ID mismatch');
        }

        $shopCoupon = ShopCoupon::where('shop_id', $shopid)->first();

        if (!$shopCoupon) {
            return redirect()->route('offlinePurchase_listpr')->with('error', 'No coupon found for this shop');
        }


        if ($shopCoupon->balance >= $CommissionAmount) {

            // ✅ Deduct commission amount from coupon balance
            $shopCoupon->balance -= $CommissionAmount;
            $shopCoupon->save();

            $offlineProduct->admin_status = 1;
            $offlineProduct->save();
        } else {
            return redirect()->route('offlinePurchase_listpr')->with('error', 'Insufficient coupon balance to deduct commission.');
        }


        $lbId = $offlineProduct->lb_id; // assuming this is the correct field

        $franchisee = Franchisee::where('lb_id', $lbId)->first();

        if ($franchisee) {
            $franchiseeUser = User::find($franchisee->user_id);

            if ($franchiseeUser) {
                $companyShare = ($CommissionAmount * 40) / 100;
                $franchiseeShare = ($CommissionAmount * 10) / 100;

                RepurchaseWallet::create([
                    'user_id'                  => $franchiseeUser->id,
                    'product_ordered_user_id' => $user->id,
                    'amount'                  => $franchiseeShare,
                    'order_id'                => $orderId,
                    'status'                  => 1,
                    'is_redeemed'             => 0,
                    'amount_type'             => 'Franchisee Share Income',
                    'commission_amount'       => $CommissionAmount,
                    'commission_percentage'   => $AmountPercent,
                    'shop_id'                 => $shopid,
                ]);
            } else {
                return response()->json(['error' => 'Franchisee user not found'], 404);
            }
        } else {
            $companyShare = ($CommissionAmount * 50) / 100;
        }

        // 1. Distribute company and user shares
        $userShare = ($CommissionAmount * 20) / 100;
        $sponsorPool = ($CommissionAmount * 30) / 100;
        $distributedSponsorAmount = 0;

        // Create wallet entry for company (40%)
        RepurchaseWallet::create([
            'user_id'     => 1,
            'product_ordered_user_id' => $user->id,
            'amount'      => $companyShare,
            'order_id'    => $orderId,
            'status'      => 1, // come from app
            'is_redeemed' => 0,
            'amount_type' => 'Company Share Income',
            'commission_amount'       => $CommissionAmount,
            'commission_percentage'   => $AmountPercent,
            'shop_id'                 => $shopid,
        ]);


        if ($user->mother_id != 1) {
            $parentuser = User::where('pan_card_no', $user->pan_card_no)->where('mother_id', 1)->first();

            $parentUser = $parentuser->id;
            $orderUser = $user->id;
            $currentUser = $parentuser;
        } else {
            $parentUser = $user->id;
            $orderUser = $user->id;
            $currentUser = $user;
        }


        // Create wallet entry for user (30%)
        RepurchaseWallet::create([
            'user_id'     => $parentUser,
            'product_ordered_user_id' => $orderUser,
            'amount'      => $userShare,
            'order_id'    => $orderId,
            'status'      => 1, // come from app
            'is_redeemed' => 0,
            'amount_type' => 'Self Purchase Income',
            'commission_amount'       => $CommissionAmount,
            'commission_percentage'   => $AmountPercent,
            'shop_id'                 => $shopid,
        ]);

        // 2. Sponsor Distribution
        $sponsorPercentages = [
            1 => 12,
            2 => 5,
            3 => 3,
            4 => 3,
            5 => 2,
            6 => 2,
            7 => 1,
            8 => 1,
            9 => 0.5,
            10 => 0.5
        ];

        // $currentUser = $user;
        $level = 1;

        while ($currentUser->sponsor_id && $level <= 10) {
            $sponsor = User::find($currentUser->sponsor_id);

            if (!$sponsor) {
                break;
            }

            if ($sponsor->mother_id != 1) {
                $alternateSponsor = User::where('pan_card_no', $sponsor->pan_card_no)
                    ->where('mother_id', 1)
                    ->first();

                if ($alternateSponsor) {
                    $sponsor = $alternateSponsor;
                }
            }

            $percent = $sponsorPercentages[$level] ?? 0;
            $share = ($sponsorPool * $percent) / 100;

            if ($share > 0) {
                RepurchaseWallet::create([
                    'user_id'     => $sponsor->id,
                    'product_ordered_user_id' => $user->id,
                    'amount'      => $share,
                    'order_id'    => $orderId,
                    'status'      => 0,
                    'is_redeemed' => 0,
                    'amount_type' => 'Repurchase Income',
                    'sponsor_level' => $level,
                    'percentage' => $percent,
                    'commission_amount'       => $CommissionAmount,
                    'commission_percentage'   => $AmountPercent,
                    'shop_id'                 => $shopid,
                ]);

                $distributedSponsorAmount += $share;
            }

            $currentUser = $sponsor;
            $level++;
        }

        // 3. Unused sponsor pool → back to company
        $remainingSponsorAmount = $sponsorPool - $distributedSponsorAmount;

        if ($remainingSponsorAmount > 0) {
            $companyWallet = RepurchaseWallet::where('user_id', 1)
                ->where('order_id', $orderId)
                ->first();

            if ($companyWallet) {
                // $originalAmount = $companyWallet->amount;
                $companyWallet->amount += $remainingSponsorAmount;
                $companyWallet->save();
            } else {
                RepurchaseWallet::create([
                    'user_id'     => 0,
                    'product_ordered_user_id' => $user->id,
                    'amount'      => $remainingSponsorAmount,
                    'order_id'    => $orderId,
                    'status'      => 0,
                    'is_redeemed' => 0,
                ]);
            }
        }


        $token = FcmToken::where('user_id', $userid)->value('token');

        if (!empty($token)) {
            $fcm = new FcmService();

            $fcm->sendNotification(
                $token,
                'Repurchase Bonus Received 🎉',
                'Your repurchase commission has been credited!',
                [
                    'type' => 'repurchase_commission',
                    'user_id' => $user->id,
                    'amount' => $userShare,
                    'order_id' => $orderId,
                ]
            );
        }

        return redirect()->route('offlinePurchase_listpr')->with('success', 'Repurchase commission distributed successfully.');
    }

    public function appdashboard()
    {
        $banners = AppInterface::where('status', 1)->get();
        $products = AppInterface::where('status', 2)->get();
        return view('Admin/appdashboard', compact('banners', 'products'));
    }

    public function uploadBanner(Request $request)
    {
        $request->validate([
            'banner_image' => 'nullable|image',
            'text' => 'nullable|string',
        ]);

        $existingBanner = AppInterface::first(); // Only one banner

        if ($existingBanner) {
            // If a new image is uploaded, replace the old one
            if ($request->hasFile('banner_image')) {
                $imagePath = $request->file('banner_image')->store('banners', 'public');
                $existingBanner->image = $imagePath;
            }

            $existingBanner->text = $request->text;
            $existingBanner->save();
        } else {
            // Create new banner
            $imagePath = $request->file('banner_image')->store('banners', 'public');
            AppInterface::create([
                'image' => $imagePath,
                'text' => $request->text,
                'status' => 1,
            ]);
        }

        return back()->with('success', 'Banner saved successfully.');
    }

    public function addProduct(Request $request)
    {
        $imagePath = $request->file('product_image')->store('products', 'public');
        AppInterface::create([
            'name' => $request->product_name,
            'price' => $request->product_price,
            'image' => $imagePath,
            'status' => 2,
        ]);
        return back()->with('success', 'Product added successfully.');
    }

    public function getBanner()
    {
        $banner = AppInterface::where('status', 1)->get();

        $banner = $banner->map(function ($banner) {
            return [
                'image_url' => $banner->image
                    ? asset('storage/banners/' . basename($banner->image))
                    : null,
                'text' => $banner->text,
            ];
        });
        return response()->json($banner);
    }
    public function getCategories()
    {
        $categories = CategoryPercentage::all(['name']);
        return response()->json($categories);
    }
    public function getProducts()
    {
        $product = AppInterface::where('status', 2)->get();

        $product = $product->map(function ($product) {
            return [
                'image_url' => $product->image
                    ? asset('storage/products/' . basename($product->image))
                    : null,
                'name' => $product->name,
                'price' => $product->price,
            ];
        });
        return response()->json($product);
    }

    public function deleteProduct($id)
    {
        $product = AppInterface::findOrFail($id);
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();

        return back()->with('success', 'Product deleted');
    }

    public function shopuserstore(Request $request)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            // User section
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone_no' => 'required|digits:10',
            'pincode' => 'required|digits:6',
            'password' => 'required|confirmed|min:6',
            'address' => 'nullable|string|max:500',

            // Shop section (use unique field names if not done yet)
            'shop_name' => 'required|string|max:255',
            'shop_email' => 'required|email',
            'shop_phone_no' => 'required|digits:10',
            'shop_gst' => 'required|string|max:20',
            'shop_address' => 'nullable|string|max:500',
            'district' => 'required|exists:districts,district_id',
            'localbodytype' => 'required|exists:local_body_types,id',
            'localbody' => 'required|exists:local_bodies,id',
            'shop_img' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }


        // Ensure the folder exists
        if (!Storage::disk('public')->exists('shopProfile')) {
            Storage::disk('public')->makeDirectory('shopProfile');
        }
        $path = $request->file('shop_img')->store('shopProfile', 'public');

        // Save user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'pan_card_no' => 'TEST',
            'phone_no' => $request->phone_no,
            'pincode' => $request->pincode,
            'password' => Hash::make($request->password),
            'address' => $request->address,
            'rank_id' => 1,
            'role' => 'shop',
        ]);

        // Save shop
        Shop::create([
            'owner_id' => $user->id,
            'name' => $request->shop_name,
            'email' => $request->shop_email,
            'phone' => $request->shop_phone_no,
            'gst_number' => $request->shop_gst,
            'address' => $request->shop_address,
            'state_id' => 1,
            'district_id' => $request->district,
            'lbt_id' => $request->localbodytype,
            'lb_id' => $request->localbody,
            'shop_profile' => $path,
            'status' => $request->status,
        ]);

        return redirect()->route('/')->with('success', 'Shop registered successfully!');
    }


    // OTT ---------------------------

    public function activate(Request $request)
    {
        $userId = $request->userId;

        // Prepare data
        $data = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => 'admin@123',
            'password_confirmation' => 'admin@123',
        ];

        // Make the API request
        $response = Http::post('https://phpstack-1377804-5514324.cloudwaysapps.com/api/register-user', $data);

        // Handle response
        if ($response->ok() && $response->json('status') === true) {
            User::where('id', $userId)->update(['package_id' => 3]);
            return back()->with('success', 'OTT activated successfully!');
        } else {
            return back()->with('error', 'Activation failed: ' . json_encode($response->json()));
        }
    }

    public function districts()
    {
        $district = District::all(['district_id', 'district_name']); // or whatever columns you need
        return response()->json($district);
    }

    public function localbodytypes()
    {
        $localbodytypes = LocalBodyType::where('lbt_code', 1)->get(['id', 'name']); // or whatever columns you need
        return response()->json($localbodytypes);
    }

    public function getLocalBodies(Request $request)
    {
        $request->validate([
            'district_id' => 'required|integer',
            'type_id' => 'required|integer',
        ]);

        $bodies = LocalBody::where('district_id', $request->district_id)
            ->where('lbt_id', $request->type_id)
            ->select('id', 'name')
            ->get();

        return response()->json($bodies);
    }
    public function shopsByLocalBody(Request $request)
    {
        $request->validate([
            'local_body_id' => 'required|integer|exists:local_bodies,id',
        ]);

        $shops = Shop::where('lb_id', $request->local_body_id)
            ->get(['id', 'name']);

        return response()->json($shops);
    }

    public function storeToken(Request $request)
    {
        $userId = $request->input('user_id');
        $token = $request->input('token');
        $status = 1;

        Log::info('Token Request', $request->all());

        // Optional: validate
        if (!$userId || !$token) {
            return response()->json(['success' => false, 'message' => 'Missing parameters'], 400);
        }

        // Example: Save in tokens table
        DB::table('fcm_tokens')->updateOrInsert(
            ['user_id' => $userId],
            ['token' => $token, 'status' => $status, 'updated_at' => now()]
        );

        return response()->json(['success' => true, 'message' => 'Token stored']);
    }

    public function removeToken(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'token' => 'required|string',
        ]);

        FcmToken::where('user_id', $request->user_id)
            ->where('token', $request->token)
            ->delete();

        return response()->json(['status' => true, 'message' => 'Token removed']);
    }

    public function app_privacy()
    {
        return view('Admin.appprivacy');
    }
}
