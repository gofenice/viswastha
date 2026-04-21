<?php

namespace App\Http\Controllers;

use App\Models\AdminWallet;
use App\Models\HolidayPackageBooking;
use App\Models\OfflineProductBill;
use App\Models\OnlineOrder;
use App\Models\Package;
use App\Models\Product;
use App\Models\ProductDeliveryDetail;
use App\Models\ReferralIncome;
use App\Models\SponsorLevel;
use App\Models\User;
use App\Models\UserPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class PartnerController extends Controller
{
    public function partner()
    {
        $user = Auth::user();

        if ($user->id == 1298) {
            $controlId = 3;
        } elseif ($user->id == 1299) {
            $controlId = 1;
        } else {
            $controlId = 2;
        }

        $allorders = ProductDeliveryDetail::whereHas('product', function ($query) use ($controlId) {
            $query->where('product_control', $controlId);
        })->count();
        $neworders = ProductDeliveryDetail::where('status', 0)->whereHas('product', function ($query) use ($controlId) {
            $query->where('product_control', $controlId);
        })->count();
        $confirmedorders = ProductDeliveryDetail::where('status', 1)->whereHas('product', function ($query) use ($controlId) {
            $query->where('product_control', $controlId);
        })->count();
        $completedorders = ProductDeliveryDetail::where('status', 2)->whereHas('product', function ($query) use ($controlId) {
            $query->where('product_control', $controlId);
        })->count();

        // dd($neworders);

        return view('Delivey_partner.partner_home', compact('allorders', 'neworders', 'confirmedorders', 'completedorders'));
    }

    public function partner_orders()
    {
        $user = Auth::user();


        if ($user->role == 'gst') {
            $allorders = ProductDeliveryDetail::all();
            $controlId = 0;
            $holiday_bookings = HolidayPackageBooking::latest()->get();
        } else {
            if ($user->id == 1298) {
                $controlId = 3;
                $holiday_bookings = null;
            } elseif ($user->id == 1299) {
                $controlId = 1;
                $holiday_bookings = HolidayPackageBooking::latest()->get();
            } else {
                $controlId = 2;
                $holiday_bookings = null;
            }

            $allorders = ProductDeliveryDetail::whereHas('product', function ($query) use ($controlId) {
                $query->where('product_control', $controlId);
            })->get();
        }

        return view('Delivey_partner.partner_orders', compact('allorders', 'controlId', 'holiday_bookings'));
    }

    public function approveproduct(Request $request)
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

            return redirect()->route('partner_orders')->with('success', 'Product status updated successfully and email sent.');
        }

        return redirect()->route('partner_orders')->with('error', 'Product details not found.');
    }
    public function partnerstatus(Request $request)
    {
        $request->validate([
            'orderId' => 'required|exists:product_delivery_details,id',
            'status' => 'required',
            'delivery_type' => 'required|in:courier,office_pickup',
        ]);

        $deliveryDetail = ProductDeliveryDetail::findOrFail($request->orderId);
        $deliveryDetail->status = $request->status;
        $deliveryDetail->delivery_type = $request->delivery_type;
        $deliveryDetail->save();

        return redirect()->route('partner_orders')->with('success', 'Status and Delivery Type updated successfully.');
    }

    public function holidaystatus(Request $request)
    {
        $request->validate([
            'orderId' => 'required|exists:product_delivery_details,id',
            'status' => 'required',
            'activeDate' => 'required',
        ]);

        $deliveryDetail = ProductDeliveryDetail::findOrFail($request->orderId);
        $deliveryDetail->status = $request->status;
        $deliveryDetail->date = $request->activeDate;
        $deliveryDetail->save();

        return redirect()->route('partner_orders')->with('success', 'Status and Delivery Type updated successfully.');
    }

    public function user_package()
    {
        $userpackages = UserPackage::latest()->get();
        return view('Delivey_partner.user_packages', compact('userpackages'));
    }
    public function user_referrals()
    {
        $userreferrals = ReferralIncome::latest()->get();
        return view('Delivey_partner.user_referrals', compact('userreferrals'));
    }
    public function user_levels()
    {
        $userlevels = SponsorLevel::latest()->get();
        return view('Delivey_partner.user_levels', compact('userlevels'));
    }
    public function admin_TDS()
    {
        $adminwalltes = AdminWallet::where('type', 3)->latest()->get();
        return view('Delivey_partner.admin_TDS', compact('adminwalltes'));
    }

    public function holiday_bk()
    {
        $holidays = HolidayPackageBooking::latest()->get();

        return view('Delivey_partner.holiday_bk', compact('holidays'));
    }

    public function repurchasedb()
    {
        $allorders = OfflineProductBill::all()->count();
        $shopapprovelpd = OfflineProductBill::where('status', 1)->count();
        $shopapproved = OfflineProductBill::where('status', 2)->count();
        $confirmed = OfflineProductBill::where('status', 5)->count();


        return view('Delivey_partner.repurchasedb', compact('allorders', 'shopapprovelpd', 'shopapproved', 'confirmed'));
    }
    public function offlinePurchase_listpr()
    {
        $purchaseList = OfflineProductBill::latest()->get();

        return view('Delivey_partner.offline_purchase_list', compact('purchaseList'));
    }

    public function onlinePurchase_listpr()
    {
        $purchaseList = OnlineOrder::latest()->get();

        return view('Delivey_partner.online_purchase_list', compact('purchaseList'));
    }

    public function approveHolidayBooking(Request $request)
    {
        $bookingId = $request->input('bookingId');
        $status = $request->input('status');

        $booking = HolidayPackageBooking::find($bookingId);

        if ($booking) {
            $booking->status = $status;
            $booking->save();

            return redirect()->route('partner_orders')->with('success', 'Booking confirmed successfully.');
        }

        return redirect()->route('partner_orders')->with('error', 'Booking not found.');
    }

    public function activateHolidayBooking(Request $request)
    {
        $request->validate([
            'bookingId' => 'required|exists:holiday_package_bookings,id',
            'status' => 'required',
            'activeDate' => 'required',
        ]);

        $booking = HolidayPackageBooking::findOrFail($request->bookingId);
        $booking->status = $request->status;
        $booking->date = $request->activeDate;
        $booking->save();

        return redirect()->route('partner_orders')->with('success', 'Booking activated successfully.');
    }
}
