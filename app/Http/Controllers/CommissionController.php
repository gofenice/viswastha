<?php

namespace App\Http\Controllers;

use App\Models\AdminWallet;
use App\Models\OnlineOrder;
use App\Models\OrderProduct;
use App\Models\RepurchaseWallet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CommissionController extends Controller
{
    public function receiveAmount(Request $request)
    {
        $orderId = $request->input('order_id');
        $amount = $request->input('amount');
        $connection = $request->input('user_id');
        $categories = $request->input('categories');
        $AmountPercent = $request->input('percentage');
        // $fanchaseCode = $request->input('fanchase_code');
        $products  = $request->input('products'); // 👈 HERE

        $category = is_array($categories) ? $categories[0] : $categories;

        $user = User::where('connection', $connection)->first();


        // Fetch user by connection
        $user = User::where('connection', $connection)->first();

        // If user not found, return error
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'User not found'], 404);
        }

        // Create online order record first
        $onlineOrder = OnlineOrder::create([
            'order_id'        => $orderId,
            'amount'          => $amount,
            'user_id'         => $user->id,                    // FIXED
            'category'        => $category,
            'percentage'      => $AmountPercent,
            'franchisee_code' => $franchisee->id ?? null,      // FIXED
            'is_approve'      => 0,
            'status'          => 0,
        ]);

        // Now save products using the online order ID
        foreach ($products as $product) {
            $name            = $product['name'] ?? null;
            $quantity        = $product['quantity'] ?? 0;
            $unitPrice       = $product['unit_price_tax_excl'] ?? 0;
            $totalPrice      = $product['total_price_tax_excl'] ?? 0;
            $taxAmount       = $product['tax_amount'] ?? 0;
            $franchiseeCode  = $product['franchise_code'] ?? null;

            // Look up franchisee user if franchise_code is provided
            $franchiseeUserId = null;
            if (!empty($franchiseeCode)) {
                $franchiseeUser = User::where('connection', $franchiseeCode)->first();
                $franchiseeUserId = $franchiseeUser ? $franchiseeUser->id : null;
            }

            // Save product to database
            OrderProduct::create([
                'online_order_id'      => $onlineOrder->id,
                'user_id'              => $user->id,
                'name'                 => $name,
                'quantity'             => $quantity,
                'unit_price_tax_excl'  => $unitPrice,
                'total_price_tax_excl' => $totalPrice,
                'tax_amount'           => $taxAmount,
                'franchise_code'       => $franchiseeUserId,
                'is_approve'           => 0,
                'status'               => 0,
            ]);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Online order saved successfully.'
        ]);
    }

    /**
     * Approve online purchase bill and process commission
     */
    public function approveBill(Request $request)
    {
        try {
            $orderId = $request->input('order_id');
            $amount = $request->input('amount');
            $userId = $request->input('user_id');
            $percentage = $request->input('percentage');
            $franchiseeCode = $request->input('franchisee_code');

            // Find the online order
            $onlineOrder = OnlineOrder::find($orderId);

            if (!$onlineOrder) {
                return redirect()->back()->with('error', 'Order not found');
            }

            // Check if already approved
            if ($onlineOrder->is_approve == 1) {
                return redirect()->back()->with('error', 'Bill already approved');
            }

            // Get user
            $user = User::find($userId);
            if (!$user) {
                return redirect()->back()->with('error', 'User not found');
            }

            // Use the existing commission distribution logic
            $this->distributeCommission($user, $onlineOrder->order_id, $amount, $percentage, $franchiseeCode);

            // Update online order status
            $onlineOrder->is_approve = 1;
            $onlineOrder->status = 1;
            $onlineOrder->save();

            // Update all related order products
            OrderProduct::where('online_order_id', $orderId)->update([
                'is_approve' => 1,
                'status'     => 1,
            ]);

            return redirect()->back()->with('success', 'Bill approved successfully and commission distributed');
        } catch (\Exception $e) {
            Log::error('Error approving bill: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to approve bill: ' . $e->getMessage());
        }
    }

    /**
     * Reject online purchase bill
     */
    public function rejectBill(Request $request)
    {
        try {
            $orderId = $request->input('order_id');
            // $reason = $request->input('reason');

            // Find the online order
            $onlineOrder = OnlineOrder::find($orderId);

            if (!$onlineOrder) {
                return redirect()->back()->with('error', 'Order not found');
            }

            // Check if already processed
            if ($onlineOrder->is_approve != 0) {
                return redirect()->back()->with('error', 'Bill already processed');
            }

            // Update online order status
            $onlineOrder->is_approve = 2;
            $onlineOrder->status = 2;
            // $onlineOrder->rejection_reason = $reason;
            $onlineOrder->save();

            // Update all related order products
            OrderProduct::where('online_order_id', $orderId)->update([
                'is_approve' => 2,
                'status'     => 2,
            ]);

            return redirect()->back()->with('success', 'Bill rejected successfully');
        } catch (\Exception $e) {
            Log::error('Error rejecting bill: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to reject bill: ' . $e->getMessage());
        }
    }

    /**
     * Private helper function to distribute commission per product
     */
    private function distributeCommission($user, $orderId, $totalAmount, $AmountPercent, $franchiseeCode = null)
    {
        // Get the online order
        $onlineOrder = OnlineOrder::where('order_id', $orderId)->first();

        if (!$onlineOrder) {
            Log::error("Online order not found for order_id: $orderId");
            return;
        }

        // Get all products for this order
        $orderProducts = OrderProduct::where('online_order_id', $onlineOrder->id)->get();

        if ($orderProducts->isEmpty()) {
            Log::warning("No products found for online_order_id: {$onlineOrder->id}");
            // Fallback to old method using total amount
            $this->distributeCommissionForAmount($user, $orderId, $totalAmount, $AmountPercent, $franchiseeCode);
            return;
        }

        // Process commission for each product
        foreach ($orderProducts as $product) {
            $productAmount = $product->total_price_tax_excl;
            $productFranchiseeCode = $product->franchise_code;

            // Distribute commission for this product
            $this->distributeCommissionForAmount($user, $orderId, $productAmount, $AmountPercent, $productFranchiseeCode);
        }
    }

    /**
     * Distribute commission for a specific amount (can be product or total)
     */
    private function distributeCommissionForAmount($user, $orderId, $amount, $AmountPercent, $franchiseeCode = null)
    {
        $CommissionAmount = ($amount * $AmountPercent) / 100;

        if ($CommissionAmount > 0) {
            $tcsamt = ($amount * 1) / 100;
            $gstamt = ($CommissionAmount * 18) / 100;

            RepurchaseWallet::create([
                'user_id'                 => 1,
                'product_ordered_user_id' => $user->id,
                'amount'                  => $tcsamt,
                'order_id'                => $orderId,
                'status'                  => 2, //online order
                'is_redeemed'             => 1,
                'amount_type'             => 'TCS Amount',
            ]);

            AdminWallet::create([
                'admin_id'     => 1,
                'from_user_id' => $user->id,
                'amount'       => $tcsamt,
                'type'         => 23,
                'status'       => 0,
            ]);

            RepurchaseWallet::create([
                'user_id'                 => 1,
                'product_ordered_user_id' => $user->id,
                'amount'                  => $gstamt,
                'order_id'                => $orderId,
                'status'                  => 2, //online order
                'is_redeemed'             => 1,
                'amount_type'             => 'GST Amount',
            ]);

            AdminWallet::create([
                'admin_id'     => 1,
                'from_user_id' => $user->id,
                'amount'       => $gstamt,
                'type'         => 24,
                'status'       => 0,
            ]);
        }

        // Handle franchisee share
        if (!empty($franchiseeCode) && $franchiseeCode != 0) {
            $franchiseeUser = User::find($franchiseeCode);

            if ($franchiseeUser) {
                $companyShare = ($CommissionAmount * 40) / 100;
                $franchiseeShare = ($CommissionAmount * 10) / 100;

                RepurchaseWallet::create([
                    'user_id'                 => $franchiseeUser->id,
                    'product_ordered_user_id' => $user->id,
                    'amount'                  => $franchiseeShare,
                    'order_id'                => $orderId,
                    'status'                  => 2, //online order
                    'is_redeemed'             => 0,
                    'amount_type'             => 'Franchisee Share Income',
                ]);
            } else {
                $companyShare = ($CommissionAmount * 50) / 100;
            }
        } else {
            $companyShare = ($CommissionAmount * 50) / 100;
        }

        $userShare = ($CommissionAmount * 20) / 100;
        $sponsorPool = ($CommissionAmount * 30) / 100;
        $distributedSponsorAmount = 0;

        RepurchaseWallet::create([
            'user_id'                 => 1,
            'product_ordered_user_id' => $user->id,
            'amount'                  => $companyShare,
            'order_id'                => $orderId,
            'status'                  => 2, //online order
            'is_redeemed'             => 0,
            'amount_type'             => 'Company Share Income',
        ]);

        // Find parent user
        if ($user->mother_id != 1) {
            $parentuser = User::where('pan_card_no', $user->pan_card_no)
                ->where('mother_id', 1)
                ->first();

            $parentUser = $parentuser->id;
            $orderUser = $user->id;
            $currentUser = $parentuser;
        } else {
            $parentUser = $user->id;
            $orderUser = $user->id;
            $currentUser = $user;
        }

        RepurchaseWallet::create([
            'user_id'                 => $parentUser,
            'product_ordered_user_id' => $orderUser,
            'amount'                  => $userShare,
            'order_id'                => $orderId,
            'status'                  => 2, //online order
            'is_redeemed'             => 0,
            'amount_type'             => 'Self Purchase Income',
        ]);

        // Sponsor distribution
        $sponsorPercentages = [
            1  => 12,
            2  => 5,
            3  => 3,
            4  => 3,
            5  => 2,
            6  => 2,
            7  => 1,
            8  => 1,
            9  => 0.5,
            10 => 0.5
        ];

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

            if ($share > 0.00) {
                RepurchaseWallet::create([
                    'user_id'                 => $sponsor->id,
                    'product_ordered_user_id' => $user->id,
                    'amount'                  => $share,
                    'order_id'                => $orderId,
                    'status'                  => 2, //online order
                    'is_redeemed'             => 0,
                    'amount_type'             => 'Repurchase Income',
                    'sponsor_level'           => $level,
                    'commission_amount'       => $CommissionAmount,
                    'commission_percentage'   => $AmountPercent,
                ]);

                $distributedSponsorAmount += $share;
            }

            $currentUser = $sponsor;
            $level++;
        }

        // Remaining sponsor amount goes to company
        $remainingSponsorAmount = $sponsorPool - $distributedSponsorAmount;

        if ($remainingSponsorAmount > 0) {
            $companyWallet = RepurchaseWallet::where('user_id', 1)
                ->where('order_id', $orderId)
                ->where('amount_type', 'Company Share Income')
                ->first();

            if ($companyWallet) {
                $companyWallet->amount += $remainingSponsorAmount;
                $companyWallet->save();
            } else {
                RepurchaseWallet::create([
                    'user_id'                 => 1,
                    'product_ordered_user_id' => $user->id,
                    'amount'                  => $remainingSponsorAmount,
                    'order_id'                => $orderId,
                    'status'                  => 2, //online order
                    'is_redeemed'             => 0,
                    'amount_type'             => 'Company Share Income',
                ]);
            }
        }
    }
}
