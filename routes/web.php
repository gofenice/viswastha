<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CalculationController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\AppController;
use App\Http\Controllers\CommissionController;
use App\Http\Controllers\ShopController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [AdminController::class, 'login'])->name('/');
Route::get('/adminlogin', [AdminController::class, 'adminlogin'])->name('adminlogin');
Route::get('/login', [AdminController::class, 'login'])->name('login');

Route::post('/loginProcess', [AuthController::class, 'loginProcess'])->name('loginProcess');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/forgot_password', [AuthController::class, 'forgotpassword'])->name('forgot_password');
Route::post('/recover_password', [AuthController::class, 'recoverpassword'])->name('recover_password');
Route::get('/new_register', [AuthController::class, 'newregister'])->name('new_register');

Route::post('/fetch-sponsor-name', [AuthController::class, 'fetchSponsorName']);
Route::post('/fetch-parent-info', [AuthController::class, 'fetchParentInfo']);
Route::post('/user_register', [AdminController::class, 'user_register'])->name('user_register');

Route::get('/terms_loginpage', [AuthController::class, 'terms_loginpage'])->name('terms_loginpage');
Route::get('/app_privacy', [AppController::class, 'app_privacy'])->name('app_privacy');

Route::get('/shop_ownerReg', [AuthController::class, 'shop_ownerReg'])->name('shop_ownerReg');
Route::post('/shopuserstore', [AppController::class, 'shopuserstore'])->name('shopuserstore');
Route::post('/get-localbodies', [AdminController::class, 'getLocalBodies'])->name('get_localbodies');
Route::get('/get-districts', [AuthController::class, 'getDistricts'])->name('get.districts');

Route::get('/register_wpan', [AuthController::class, 'register_wpan'])->name('register_wpan');
Route::post('/register_wpan', [AdminController::class, 'store_user_wpan'])->name('register.store.wpan');

Route::get('/free-registration', [AuthController::class, 'register_wpan_rr'])->name('free-registration');
Route::post('/register_wpan_rr', [AdminController::class, 'store_user_wpan_rr'])->name('register.store.wpan_rr');

Route::get('/register', [AuthController::class, 'public_register'])->name('public-register');

Route::middleware('auth')->group(function () {

    Route::get('/adminhome', [AdminController::class, 'admin'])->name('adminhome');
    Route::get('/view_profile', [AdminController::class, 'view_profile'])->name('view_profile');
    Route::get('/change_password', [AdminController::class, 'change_password'])->name('change_password');
    Route::get('/achiever_details', [AdminController::class, 'achiever_details'])->name('achiever_details');
    Route::get('/transfer_pin', [AdminController::class, 'transfer_pin'])->name('transfer_pin');
    Route::get('/pin_transfer_details', [AdminController::class, 'pin_transfer_details'])->name('pin_transfer_details');
    Route::get('/request_pin', [AdminController::class, 'request_pin'])->name('request_pin');
    Route::get('/pin_request_details', [AdminController::class, 'pin_request_details'])->name('pin_request_details');
    Route::get('/binary_income_details', [AdminController::class, 'binary_income_details'])->name('binary_income_details');

    // User binary tree
    Route::get('/my-tree', [AdminController::class, 'userBinaryTree'])->name('user.binary_tree');
    Route::get('/my-tree/search-users', [AdminController::class, 'userBinaryTreeSearch'])->name('user.binary_tree.search_users');

    // Binary Tree Admin Migration
    Route::get('/admin/binary-tree', [AdminController::class, 'binaryTreeAdmin'])->name('admin.binary_tree');
    Route::post('/admin/binary-tree/set-root', [AdminController::class, 'setBinaryRoot'])->name('admin.binary_tree.set_root');
    Route::post('/admin/binary-tree/transfer-user', [AdminController::class, 'transferUserToTree'])->name('admin.binary_tree.transfer_user');
    Route::post('/admin/binary-tree/remove-user', [AdminController::class, 'removeFromBinaryTree'])->name('admin.binary_tree.remove_user');
    Route::get('/admin/binary-tree/search-users', [AdminController::class, 'searchUsersForTransfer'])->name('admin.binary_tree.search_users');
    Route::get('/admin/binary-tree/check-slots', [AdminController::class, 'checkTargetSlots'])->name('admin.binary_tree.check_slots');
    Route::get('/admin/binary-tree/pin-owners', [AdminController::class, 'getPinOwners'])->name('admin.binary_tree.pin_owners');
    Route::get('/admin/binary-tree/user-packages', [AdminController::class, 'getUserPackageDetails'])->name('admin.binary_tree.user_packages');
    Route::post('/admin/binary-tree/move-user', [AdminController::class, 'moveUserInTree'])->name('admin.binary_tree.move_user');
    Route::post('/admin/binary-tree/complete-migration', [AdminController::class, 'completeMigration'])->name('admin.binary_tree.complete_migration');
    Route::post('/admin/binary-tree/quick-user', [AdminController::class, 'quickTestUser'])->name('admin.binary_tree.quick_user');
    Route::get('/admin/binary-tree/leg-volume-detail', [AdminController::class, 'binaryLegVolumeDetail'])->name('admin.binary_tree.leg_volume_detail');
    Route::get('/admin/binary-income', [AdminController::class, 'adminBinaryIncome'])->name('admin.binary_income');
    Route::get('/admin/binary-income/popup', [AdminController::class, 'adminBinaryIncomePopup'])->name('admin.binary_income.popup');
    Route::get('/admin/binary-income/run', [AdminController::class, 'runBinaryIncome'])->name('admin.binary_income.run');
    Route::get('/admin/binary-income/log/{id}/pairs', [AdminController::class, 'binaryIncomePairs'])->name('admin.binary_income.pairs');
    Route::post('/admin/binary-income/clear-wallets', [AdminController::class, 'clearBinaryWallets'])->name('admin.binary_income.clear_wallets');
    Route::post('/admin/user/change-account-type', [AdminController::class, 'changeAccountType'])->name('admin.user.change_account_type');
    Route::get('/admin/user/{id}/children-by-pan', [AdminController::class, 'childrenByPan'])->name('admin.user.children_by_pan');
    Route::post('/admin/user/assign-pan', [AdminController::class, 'assignPanCard'])->name('admin.user.assign_pan');
    Route::get('/directy_income_details', [AdminController::class, 'directy_income_details'])->name('directy_income_details');
    Route::get('/royalty_income_details', [AdminController::class, 'royalty_income_details'])->name('royalty_income_details');
    Route::get('/sponsor_income_details', [AdminController::class, 'sponsorIncomeDetails'])->name('sponsor_income_details');
    Route::get('/package', [AdminController::class, 'package'])->name('package');
    Route::get('/view_sponsor', [AuthController::class, 'view_sponsor'])->name('view_sponsor');
    Route::get('/view_sponsor_superadmin', [AuthController::class, 'view_sponsor_superadmin'])->name('view_sponsor_superadmin');
    Route::get('/product_view', [AdminController::class, 'product_view'])->name('product_view');

    Route::get('/support_view', [AdminController::class, 'support_view'])->name('support_view');
    Route::get('/support_view_admin/{userId?}', [AdminController::class, 'support_view_admin'])->name('support_view_admin');
    Route::post('/support_view_admin/{userId}', [AdminController::class, 'send_message_admin'])->name('send_message_admin');
    Route::post('/send_message', [AdminController::class, 'send_message'])->name('send_message');

    Route::get('/add_wallet', [AdminController::class, 'add_wallet'])->name('add_wallet');
    Route::post('/get_user_name', [AdminController::class, 'get_user_name'])->name('get_user_name');
    Route::post('/update_wallet', [AdminController::class, 'update_wallet'])->name('update_wallet');
    Route::post('/update_status', [AdminController::class, 'updateStatus'])->name('updateStatus');
    Route::get('/generate_pin', [AuthController::class, 'generate_pin'])->name('generate_pin');
    Route::post('/get_package', [AuthController::class, 'get_package'])->name('get_package');
    Route::post('/add_pin', [AuthController::class, 'add_pin'])->name('add_pin');
    Route::get('/view_pin', [AuthController::class, 'view_pin'])->name('view_pin');
    Route::post('/search_pin', [AuthController::class, 'search_pin'])->name('search_pin');
    Route::post('/unassignpin', [AuthController::class, 'unassignpin'])->name('unassignpin');
    Route::get('/rank_details', [AuthController::class, 'rank_details'])->name('rank_details');
    Route::get('/admin/rank-details/{rank}/users', [AuthController::class, 'getUsersByRank'])->name('getUsersByRank');
    Route::get('/rank_tree', [AuthController::class, 'rank_tree'])->name('rank_tree');

    Route::get('/edit_bank_details', [AuthController::class, 'edit_bank_details'])->name('edit_bank_details');
    Route::post('/bank_details_update', [AuthController::class, 'bank_details_update'])->name('bank_details_update');
    Route::get('/our_achiever', [AuthController::class, 'our_achiever'])->name('our_achiever');
    Route::post('/our_achiever', [AuthController::class, 'our_achiever_list'])->name('our_achiever_list');

    Route::post('/add_package', [AdminController::class, 'add_package'])->name('add_package');
    Route::post('/edit_package', [AdminController::class, 'edit_package'])->name('edit_package');
    Route::post('/delete_package', [AdminController::class, 'delete_package'])->name('delete_package');
    Route::post('/register', [AdminController::class, 'store_user'])->name('register.store');
    Route::post('/add-user', [AdminController::class, 'addUser'])->name('addUser');
    Route::post('/add_product', [AdminController::class, 'add_product'])->name('add_product');
    Route::post('/delete_product', [AdminController::class, 'delete_product'])->name('delete_product');
    Route::post('/edit_product', [AdminController::class, 'edit_product'])->name('edit_product');

    Route::get('/redeem_pin_view', [AdminController::class, 'redeem_pin_view'])->name('redeem_pin_view');
    Route::post('/redeem-pin', [AdminController::class, 'redeemPin'])->name('redeem.pin');
    Route::get('/getUserName', [AdminController::class, 'getUserName'])->name('getUserName');
    Route::post('/redeem_pin_parent', [AdminController::class, 'redeemPinParent'])->name('redeem_pin_parent');
    Route::get('/pair_match_company', [AdminController::class, 'pair_match_company'])->name('pair_match_company');

    Route::post('/change_password_process', [AuthController::class, 'change_password_process'])->name('change_password_process');
    Route::post('/edit_profile', [AdminController::class, 'edit_profile'])->name('edit_profile');

    Route::get('/referral_income', [CalculationController::class, 'admin_referral_income'])->name('referral_income');
    Route::get('/pairmatch_income', [CalculationController::class, 'pairmatch_income'])->name('pairmatch_income');
    Route::get('/rank_income', [CalculationController::class, 'rank_income'])->name('rank_income');
    Route::get('/tempMatchPair', [AdminController::class, 'tempMatchPair'])->name('tempMatchPair');


    Route::get('/motheridlist', [AdminController::class, 'motheridlist'])->name('motheridlist');
    Route::post('/admin/addincome/{userId}', [AdminController::class, 'addincome'])->name('admin.addincome');

    Route::get('/get-available-pins', [AdminController::class, 'getAvailablePins'])->name('getAvailablePins');
    Route::post('/update_pin', [AdminController::class, 'updatePin'])->name('update_pin');

    Route::get('/withdrawal_view', [AdminController::class, 'withdrawal_view'])->name('withdrawal_view');
    Route::post('/withdraw/request', [AdminController::class, 'withdrawRequest'])->name('withdraw.request');
    Route::post('/admin/withdrawals/approve/{requestId}', [AdminController::class, 'approve'])->name('admin.withdraw.approve');
    Route::post('/admin/withdrawals/reject/{requestId}', [AdminController::class, 'reject'])->name('admin.withdraw.reject');

    Route::get('/levelincomelist', [CalculationController::class, 'levelincomelist'])->name('levelincomelist');
    Route::get('/pairmatch', [CalculationController::class, 'testisMatchingPair'])->name('pairmatch');

    Route::get('/sunflower', [AdminController::class, 'sunflower'])->name('sunflower');
    Route::get('/sunflower/{id?}', [AdminController::class, 'sunflower'])->name('sunflower')->middleware('auth');

    Route::get('/userlist', [AdminController::class, 'userlist'])->name('userlist');
    Route::post('/user/update', [AdminController::class, 'update'])->name('userUpdate');
    Route::get('/user/details/{id}', [AdminController::class, 'getUserDetails'])->name('getUserDetails');
    Route::get('/admin/user/check-mother-id-change', [AdminController::class, 'checkMotherIdChange'])->name('admin.user.check_mother_id_change');
    Route::get('companyRank_income', [AdminController::class, 'companyRank_income'])->name('companyRank_income');


    Route::get('/admin/rank/{rank_id}/total', [AdminController::class, 'rankTotal'])->name('rank.total');
    Route::get('/admin/rank/{rank_id}/redeemed', [AdminController::class, 'rankRedeemed'])->name('rank.redeemed');
    Route::get('/admin/rank/{rank_id}/pending', [AdminController::class, 'rankPending'])->name('rank.pending');

    Route::post('redeemToUser', [AdminController::class, 'redeemToUser'])->name('redeemToUser');
    Route::post('redeemToCompany', [AdminController::class, 'redeemToCompany'])->name('redeemToCompany');

    Route::get('/user_details/{rank}', [AuthController::class, 'userDetails'])->name('user_details');

    Route::post('/bulk-transfer', [AuthController::class, 'bulkTransfer'])->name('bulk.transfer');
    Route::get('/get_transferUser', [AuthController::class, 'gettransferUserName'])->name('get_transferUser');

    Route::get('/bank_detail_list', [AuthController::class, 'bankDetailList'])->name('bank_detail_list');
    Route::post('/approvebank', [AuthController::class, 'approvebank'])->name('approvebank');

    Route::post('/pin-details', [AuthController::class, 'showDetails'])->name('pin.details');
    Route::get('/pin_history', [AuthController::class, 'pinHistory'])->name('pin_history');

    Route::get('/terms', [AuthController::class, 'terms_Conditions'])->name('terms');

    Route::get('/royalty_users', [AuthController::class, 'royalty_users'])->name('royalty_users');
    Route::post('/add_royalty_user', [AuthController::class, 'add_royalty_user'])->name('add_royalty_user');
    Route::get('/royalty_wallet', [AuthController::class, 'royalty_wallet'])->name('royalty_wallet');
    Route::post('/redeemRoyaltyUsers', [AuthController::class, 'redeemRoyaltyUsers'])->name('redeemRoyaltyUsers');
    Route::get('/royaltyUsersAmtList', [AuthController::class, 'royaltyUsersAmtList'])->name('royaltyUsersAmtList');
    Route::post('/edit_royalty_user', [AuthController::class, 'edit_royalty_user'])->name('edit_royalty_user');

    Route::get('/adminWallet', [AdminController::class, 'adminWallet'])->name('adminWallet');
    Route::post('/adminToRoyalty', [AdminController::class, 'adminToRoyalty'])->name('adminToRoyalty');

    Route::post('/save_user_product', [AdminController::class, 'save_user_product'])->name('save_user_product');
    Route::get('/user_product_list', [AdminController::class, 'user_product_list'])->name('user_product_list');
    Route::get('/view_products', [AuthController::class, 'viewProducts'])->name('view_products');
    Route::get('/order_product', [AuthController::class, 'orderProduct'])->name('order_product');
    Route::get('/view_order', [AuthController::class, 'view_Order'])->name('view_order');
    Route::get('/partner', [PartnerController::class, 'partner'])->name('partner');
    Route::get('/partner_orders', [PartnerController::class, 'partner_orders'])->name('partner_orders');
    Route::post('/approveproduct', [PartnerController::class, 'approveproduct'])->name('approveproduct');
    Route::post('/partnerstatus', [PartnerController::class, 'partnerstatus'])->name('partnerstatus');

    Route::delete('/user/{id}', [AdminController::class, 'deleteUser'])->name('deleteUser');

    Route::get('/statement', [CalculationController::class, 'statement'])->name('statement');

    Route::get('/rank_income_list', [CalculationController::class, 'rank_income_list'])->name('rank_income_list');
    Route::get('/royalty_user_wallet', [AuthController::class, 'royalty_user_wallet'])->name('royalty_user_wallet');


    Route::get('/childToMotherIncome_list', [AdminController::class, 'childToMotherIncome_list'])->name('childToMotherIncome_list');
    Route::get('/transferToWallet', [AdminController::class, 'transferToWallet'])->name('transferToWallet');

    Route::post('/levelIncomeTransfer', [AdminController::class, 'levelIncomeTransfer'])->name('levelIncomeTransfer');
    Route::post('/rankIncomeTransfer', [AdminController::class, 'rankIncomeTransfer'])->name('rankIncomeTransfer');
    Route::post('/referralIncomeTransfer', [AdminController::class, 'referralIncomeTransfer'])->name('referralIncomeTransfer');
    Route::post('/royaltyIncomeTransfer', [AdminController::class, 'royaltyIncomeTransfer'])->name('royaltyIncomeTransfer');

    Route::get('/donationWallet', [AdminController::class, 'donationWallet'])->name('donationWallet');
    Route::get('/user_donation', [AdminController::class, 'user_donation'])->name('user_donation');
    Route::get('/your_account', [AdminController::class, 'yourAccount'])->name('your_account');
    Route::post('/donationTransfer', [AdminController::class, 'donationTransfer'])->name('donationTransfer');

    Route::get('/getUserSponsor/{id}', [AdminController::class, 'getUserSponsor'])->name('getUserSponsor');
    Route::post('/change_sponsor', [AdminController::class, 'change_sponsor'])->name('change_sponsor');


    Route::get('/holiday_package_list', [AdminController::class, 'holiday_package_list'])->name('holiday_package_list');
    Route::post('/approvepackageAdmin', [AdminController::class, 'approvepackageAdmin'])->name('approvepackageAdmin');
    Route::post('/Adminstatus', [AdminController::class, 'Adminstatus'])->name('Adminstatus');

    Route::post('/adminchangePassword', [AdminController::class, 'adminchangePassword'])->name('adminchangePassword');


    Route::post('/basicLevelIncomeTransfer', [AdminController::class, 'basicLevelIncomeTransfer'])->name('basicLevelIncomeTransfer');
    Route::post('/basicReferralIncomeTransfer', [AdminController::class, 'basicReferralIncomeTransfer'])->name('basicReferralIncomeTransfer');

    Route::post('/adminToBonus', [AdminController::class, 'adminToBonus'])->name('adminToBonus');
    Route::post('/redeemBonusUsers', [AdminController::class, 'redeemBonusUsers'])->name('redeemBonusUsers');
    Route::post('/bonusIncomeTransfer', [AdminController::class, 'bonusIncomeTransfer'])->name('bonusIncomeTransfer');
    Route::get('/bonus_users', [AdminController::class, 'bonus_users'])->name('bonus_users');
    Route::get('/bonus_wallet', [AdminController::class, 'bonus_wallet'])->name('bonus_wallet');

    Route::post('/adminctrashMoney', [AdminController::class, 'adminctrashMoney'])->name('adminctrashMoney');
    Route::get('/trash_wallet', [AdminController::class, 'trash_wallet'])->name('trash_wallet');
    Route::get('/levelincomelistbasic', [CalculationController::class, 'levelincomelistbasic'])->name('levelincomelistbasic');

    Route::post('/admininctoUser', [AdminController::class, 'admininctoUser'])->name('admininctoUser');

    Route::get('/admin/login-as/{user}', [\App\Http\Controllers\AdminController::class, 'loginAsUser'])
        ->name('admin.login-as-user')
        ->middleware(['auth', 'is_admin']);

    Route::get('/admin/impersonate-leave', function () {
        if (session()->has('impersonate')) {
            \Illuminate\Support\Facades\Auth::loginUsingId(session('impersonate'));
            session()->forget('impersonate');
            return redirect('/userlist');
        }
        return redirect('/');
    })->name('admin.impersonate-leave')->middleware('auth');


    Route::post('/holidaystatus', [PartnerController::class, 'holidaystatus'])->name('holidaystatus');

    Route::get('/user_package', [PartnerController::class, 'user_package'])->name('user_package');
    Route::get('/user_referrals', [PartnerController::class, 'user_referrals'])->name('user_referrals');
    Route::get('/user_levels', [PartnerController::class, 'user_levels'])->name('user_levels');
    Route::get('/admin_TDS', [PartnerController::class, 'admin_TDS'])->name('admin_TDS');

    Route::get('/franchisee', [AdminController::class, 'franchisee'])->name('franchisee');
    Route::post('/add_franchisee', [AdminController::class, 'add_franchisee'])->name('add_franchisee');
    Route::post('/adminToRank', [AdminController::class, 'adminToRank'])->name('adminToRank');

    Route::post('/repurchaseIncomeTransfer', [AdminController::class, 'repurchaseIncomeTransfer'])->name('repurchaseIncomeTransfer');
    Route::get('/addcategoryper', [AppController::class, 'addcategoryper'])->name('addcategoryper');
    Route::post('/add_category', [AppController::class, 'add_category'])->name('add_category');
    Route::get('/repurchase_wallet', [AdminController::class, 'repurchase_wallet'])->name('repurchase_wallet');
    Route::get('/shop_list', [AdminController::class, 'shop_list'])->name('shop_list');
    Route::get('/offlinePurchase_list', [AdminController::class, 'offlinePurchase_list'])->name('offlinePurchase_list');
    // Route::get('/franchisee', [AdminController::class, 'franchisee'])->name('franchisee');

    Route::post('/announcement/update', [AdminController::class, 'announcementupdate'])->name('announcement.update');
    Route::post('/addManuallyadmin', [AdminController::class, 'addManuallyadmin'])->name('addManuallyadmin');

    // Privilege Routes
    Route::get('/privilege_users', [AdminController::class, 'privilege_users'])->name('privilege_users');
    Route::post('/add_privilege_user', [AdminController::class, 'add_privilege_user'])->name('add_privilege_user');
    Route::post('/edit_privilege_user', [AdminController::class, 'edit_privilege_user'])->name('edit_privilege_user');
    Route::get('/privilege_wallet', [AdminController::class, 'privilege_wallet'])->name('privilege_wallet');
    Route::get('/privilegeUsersAmtList', [AdminController::class, 'privilegeUsersAmtList'])->name('privilegeUsersAmtList');
    Route::post('/redeemPrivilegeUsers', [AdminController::class, 'redeemPrivilegeUsers'])->name('redeemPrivilegeUsers');
    Route::post('/adminToPrivilege', [AdminController::class, 'adminToPrivilege'])->name('adminToPrivilege');

    // Board Routes
    Route::get('/board_users', [AdminController::class, 'board_users'])->name('board_users');
    Route::post('/add_board_user', [AdminController::class, 'add_board_user'])->name('add_board_user');
    Route::post('/edit_board_user', [AdminController::class, 'edit_board_user'])->name('edit_board_user');
    Route::get('/board_wallet', [AdminController::class, 'board_wallet'])->name('board_wallet');
    Route::get('/boardUsersAmtList', [AdminController::class, 'boardUsersAmtList'])->name('boardUsersAmtList');
    Route::post('/redeemBoardUsers', [AdminController::class, 'redeemBoardUsers'])->name('redeemBoardUsers');
    Route::post('/adminToBoard', [AdminController::class, 'adminToBoard'])->name('adminToBoard');

    // Executive Routes
    Route::get('/executive_users', [AdminController::class, 'executive_users'])->name('executive_users');
    Route::post('/add_executive_user', [AdminController::class, 'add_executive_user'])->name('add_executive_user');
    Route::post('/edit_executive_user', [AdminController::class, 'edit_executive_user'])->name('edit_executive_user');
    Route::get('/executive_wallt', [AdminController::class, 'executive_wallet'])->name('executive_wallt');
    Route::get('/executiveUsersAmtList', [AdminController::class, 'executiveUsersAmtList'])->name('executiveUsersAmtList');
    Route::post('/redeemExecutiveUsers', [AdminController::class, 'redeemExecutiveUsers'])->name('redeemExecutiveUsers');
    Route::post('/adminToExecutive', [AdminController::class, 'adminToExecutive'])->name('adminToExecutive');

    // Incentive Routes
    Route::get('/incentive_users', [AdminController::class, 'incentive_users'])->name('incentive_users');
    Route::post('/add_incentive_user', [AdminController::class, 'add_incentive_user'])->name('add_incentive_user');
    Route::post('/edit_incentive_user', [AdminController::class, 'edit_incentive_user'])->name('edit_incentive_user');
    Route::get('/incentive_wallet', [AdminController::class, 'incentive_wallet'])->name('incentive_wallet');
    Route::get('/incentiveUsersAmtList', [AdminController::class, 'incentiveUsersAmtList'])->name('incentiveUsersAmtList');
    Route::post('/redeemIncentiveUsers', [AdminController::class, 'redeemIncentiveUsers'])->name('redeemIncentiveUsers');
    Route::post('/adminToIncentive', [AdminController::class, 'adminToIncentive'])->name('adminToIncentive');
    Route::post('/privilegeIncomeTransfer', [AdminController::class, 'privilegeIncomeTransfer'])->name('privilegeIncomeTransfer');
    Route::post('/boardIncomeTransfer', [AdminController::class, 'boardIncomeTransfer'])->name('boardIncomeTransfer');
    Route::post('/executiveIncomeTransfer', [AdminController::class, 'executiveIncomeTransfer'])->name('executiveIncomeTransfer');
    Route::post('/incentiveIncomeTransfer', [AdminController::class, 'incentiveIncomeTransfer'])->name('incentiveIncomeTransfer');
    Route::get('/privilege_user_wallet', [AdminController::class, 'privilege_user_wallet'])->name('privilege_user_wallet');
    Route::get('/board_user_wallet', [AdminController::class, 'board_user_wallet'])->name('board_user_wallet');
    Route::get('/executive_user_wallet', [AdminController::class, 'executive_user_wallet'])->name('executive_user_wallet');
    Route::get('/incentive_user_wallet', [AdminController::class, 'incentive_user_wallet'])->name('incentive_user_wallet');
    Route::get('/bonus_user_wallet', [AdminController::class, 'bonus_user_wallet'])->name('bonus_user_wallet');
    Route::post('/redeemBoardCompany', [AdminController::class, 'redeemBoardCompany'])->name('redeemBoardCompany');
    Route::post('/redeemExecutiveCompany', [AdminController::class, 'redeemExecutiveCompany'])->name('redeemExecutiveCompany');
    Route::get('/repurchase_income_list', [AdminController::class, 'repurchase_income_list'])->name('repurchase_income_list');
    Route::post('/redeemPrivilegeCompany', [AdminController::class, 'redeemPrivilegeCompany'])->name('redeemPrivilegeCompany');
    Route::get('/rank_histories', [AdminController::class, 'rank_histories'])->name('rank_histories');
    Route::get('/premium_rank_list', [AdminController::class, 'premium_rank_list'])->name('premium_rank_list');
    Route::post('/edit_user_rankStatus', [AdminController::class, 'edit_user_rankStatus'])->name('edit_user_rankStatus');

    //Basic Rank
    Route::get('/basicRank_income', [CalculationController::class, 'basicRank_income'])->name('basicRank_income');
    Route::get('basicCompanyRank_income', [AdminController::class, 'basicCompanyRank_income'])->name('basicCompanyRank_income');
    Route::post('basicRedeemToCompany', [AdminController::class, 'basicRedeemToCompany'])->name('basicRedeemToCompany');
    Route::post('/basicRedeemToUser', [AdminController::class, 'basicRedeemToUser'])->name('basicRedeemToUser');
    Route::get('/admin/rank/{rank_id}/basictotal', [AdminController::class, 'basicrankTotal'])->name('rank.basictotal');
    Route::get('/admin/rank/{rank_id}/basicredeemed', [AdminController::class, 'basicrankRedeemed'])->name('rank.basicredeemed');
    Route::get('/admin/rank/{rank_id}/basicpending', [AdminController::class, 'basicrankPending'])->name('rank.basicpending');
    Route::get('/BasicRank_details', [AdminController::class, 'BasicRank_details'])->name('BasicRank_details');
    Route::post('/basicRankIncomeTransfer', [AdminController::class, 'basicRankIncomeTransfer'])->name('basicRankIncomeTransfer');
    Route::post('/edit_user_basicRankStatus', [AdminController::class, 'edit_user_basicRankStatus'])->name('edit_user_basicRankStatus');
    Route::post('/adminToBasicRank', [AdminController::class, 'adminToBasicRank'])->name('adminToBasicRank');
    Route::get('/admin/basic-rank-details/{rank}/users', [AdminController::class, 'getUsersBybasicRank'])->name('getUsersBybasicRank');

    Route::post('/adminbillapprove', [AppController::class, 'adminbillapprove'])->name('adminbillapprove');
    Route::post('/redeemRepurchase', [AdminController::class, 'redeemRepurchase'])->name('redeemRepurchase');
    Route::get('/shopCoupn_list', [AdminController::class, 'shopCoupn_list'])->name('shopCoupn_list');

    Route::get('/holiday_bk', [PartnerController::class, 'holiday_bk'])->name('holiday_bk');

    Route::get('/shopReceipt_list', [AdminController::class, 'shopReceipt_list'])->name('shopReceipt_list');
    Route::post('/shopReceiptApprove', [AdminController::class, 'shopReceiptApprove'])->name('shopReceiptApprove');
    Route::get('/shop_dashboard', [ShopController::class, 'shop_dashboard'])->name('shop_dashboard');
    Route::get('/shop_receipt', [ShopController::class, 'shop_receipt'])->name('shop_receipt');
    Route::post('/add_shop_receipt', [ShopController::class, 'add_shop_receipt'])->name('add_shop_receipt');
    Route::get('/repurchasedb', [PartnerController::class, 'repurchasedb'])->name('repurchasedb');
    Route::get('/offlinePurchase_listpr', [PartnerController::class, 'offlinePurchase_listpr'])->name('offlinePurchase_listpr');

    Route::get('/franchisee_income_list', [AdminController::class, 'franchisee_income_list'])->name('franchisee_income_list');
    Route::get('/selfpurchase_income_list', [AdminController::class, 'selfpurchase_income_list'])->name('selfpurchase_income_list');
    Route::get('/repurchase_list', [AdminController::class, 'repurchase_list'])->name('repurchase_list');

    Route::get('/shop_transfer_list', [ShopController::class, 'shop_transfer_list'])->name('shop_transfer_list');

    Route::get('/basicRank_incomeList', [CalculationController::class, 'basicRank_incomeList'])->name('basicRank_incomeList'); //not in dev

    Route::get('/user_registration_form', [AdminController::class, 'userRegistrationForm'])->name('user_registration_form');
    Route::post('/register_simple', [AdminController::class, 'store_user_simple'])->name('register.store.simple');
    // KYC Verification Routes
    Route::get('/kyc_verification', [AdminController::class, 'kycVerification'])->name('kyc_verification');
    Route::post('/update_kyc', [AdminController::class, 'updateKyc'])->name('update_kyc');

    // Commission Routes
    Route::get('/onlinePurchase_listpr', [PartnerController::class, 'onlinePurchase_listpr'])->name('onlinePurchase_listpr');
    Route::post('/commission/approve', [CommissionController::class, 'approveBill'])->name('commission.approve');
    Route::post('/commission/reject', [CommissionController::class, 'rejectBill'])->name('commission.reject');

    Route::get('/franchisee_income_list_online', [AdminController::class, 'franchisee_income_list_online'])->name('franchisee_income_list_online');
    Route::get('/selfpurchase_income_list_online', [AdminController::class, 'selfpurchase_income_list_online'])->name('selfpurchase_income_list_online');
    Route::get('/repurchase_list_online', [AdminController::class, 'repurchase_list_online'])->name('repurchase_list_online');
    Route::get('/gst_tcs_list', [AdminController::class, 'gst_tcs_list'])->name('gst_tcs_list');

    Route::post('/approveHolidayBooking', [PartnerController::class, 'approveHolidayBooking'])->name('approveHolidayBooking');
    Route::post('/activateHolidayBooking', [PartnerController::class, 'activateHolidayBooking'])->name('activateHolidayBooking');

    // New Board Member Management Routes
    Route::get('/view_board_members', [AdminController::class, 'view_board_members'])->name('view_board_members');
    Route::post('/store_board_member', [AdminController::class, 'store_board_member'])->name('store_board_member');
    Route::get('/delete_board_member/{id}', [AdminController::class, 'delete_board_member'])->name('delete_board_member');
    Route::get('/board-members/placement-path', [AdminController::class, 'boardMemberPlacementPath'])->name('board_members.placement_path');
});
