<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AppController;
use App\Http\Controllers\CommissionController;
use App\Models\OfflineProductBill;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/validate-user', [AdminController::class, 'validateUser']);

Route::post('/applogin', [AppController::class, 'applogin']);

Route::post('/receive-amount', [CommissionController::class, 'receiveAmount']);

Route::get('/shops', [AppController::class, 'shops']);
Route::get('/categories', [AppController::class, 'categories']);
Route::post('/genofflinebill', [AppController::class, 'genofflinebill']);
Route::get('/orderslist', [AppController::class, 'orderslist']);
Route::get('/wallet-summary', [AppController::class, 'getWalletSummary']);
Route::get('/shop-orders', [AppController::class, 'getShopOrders']);
Route::post('/update-order-status', function (Request $request) {
    $order = OfflineProductBill::find($request->order_id);
    if ($order) {
        $order->status = $request->status;
        $order->save();
        return response()->json(['message' => 'Order updated']);
    }
    return response()->json(['error' => 'Order not found'], 404);
});
Route::get('/shop/orders-summary/{user_id}', [AppController::class, 'getOrderSummary']);

Route::post('/repurchaseCommission', [AppController::class, 'repurchaseCommission']);

Route::prefix('home')->group(function () {
    Route::get('/banner', [AppController::class, 'getBanner']);
    Route::get('/categories', [AppController::class, 'getCategories']);
    Route::get('/products', [AppController::class, 'getProducts']);
});

Route::get('/districts', [AppController::class, 'districts']);
Route::get('/localbodytypes', [AppController::class, 'localbodytypes']);
Route::post('/get_localbodies', [AppController::class, 'getLocalBodies']);
Route::post('/shops_by_localbody', [AppController::class, 'shopsByLocalBody']);
Route::post('/store-token', [AppController::class, 'storeToken']);
Route::post('/remove-fcm-token', [AppController::class, 'removeToken']);
Route::get('/app-version', function () {
    return response()->json([
        'latest_version' => '1.0.0',
        'force_update' => true, // optional
      ]);
});
