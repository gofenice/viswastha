<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\RepurchaseWallet;
use App\Models\ShopCoupon;
use App\Models\ShopReceipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ShopController extends Controller
{
    public function shop_dashboard()
    {
        $user = auth()->user();
        $shopdetail = Shop::where('owner_id', $user->id)->first();

        $shopCoupon = ShopCoupon::where('shop_id', $shopdetail->id)->first();

        return view('Shop.shop_home', compact('shopCoupon'));
    }

    public function shop_receipt()
    {
        $user = auth()->user();
        $shopdetail = Shop::where('owner_id', $user->id)->first();

        if ($user->role === 'superadmin') {
            $receipts = ShopReceipt::with(['user', 'shop'])->latest()->get();
        } else {
            $receipts = ShopReceipt::where('user_id', $user->id)->latest()->get();
        }
        // dd($receipts);
        return view('Shop.shop_receipt', compact('user', 'shopdetail', 'receipts'));
    }

    public function add_shop_receipt(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'shop_id' => 'required',
            'accName' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1',
            'dOfSend' => 'required|date',
            'transaction_id' => 'required|string|max:255|unique:shop_receipts,transaction_id',
            'receipt_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if (!Storage::disk('public')->exists('shop_receipts')) {
            Storage::disk('public')->makeDirectory('shop_receipts');
        }
        $path = $request->file('receipt_image')->store('shop_receipts', 'public');

        ShopReceipt::create([
            'user_id' => $request->user_id,
            'shop_id' => $request->shop_id,
            'acc_holder_name' => $request->accName,
            'amount' => $request->amount,
            'date_of_send' => $request->dOfSend,
            'transaction_id' => $request->transaction_id,
            'image' => 'storage/' . $path,
            'status' => 0, // pending
        ]);

        return redirect()->back()->with('success', 'Receipt uploaded successfully. Waiting for admin approval!');
    }

    public function shop_transfer_list()
    {
        $user = auth()->user();
        $shopdetail = Shop::where('owner_id', $user->id)->first();

        // $transfer_list = RepurchaseWallet::where('shop_id', $shopdetail->id)->where('amount_type', 'Company Share Income')->get();

        $transfer_list = RepurchaseWallet::where('shop_id', $shopdetail->id)
            ->where('amount_type', 'Company Share Income')
            ->with('orderBill')
            ->get();

        return view('Shop.shop_transfer_list', compact('transfer_list'));
    }
}
