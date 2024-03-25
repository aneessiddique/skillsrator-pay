<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\gatewayController;
use App\Http\Controllers\kuickpayController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Account\AccountHomeController;
use App\Http\Controllers\Account\ManualInvoicesController;
use App\Http\Controllers\Account\TransactionsController;
use App\Http\Controllers\Admin\GatewaysController;
use App\Http\Controllers\Admin\PermissionsController;
use App\Http\Controllers\Admin\ApiKeysController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\UsersController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/clear-cache', function() {
    \Artisan::call('cache:clear');
    \Artisan::call('optimize');
    \Artisan::call('config:cache');
    return "Cache is cleared";
});

Route::get('/', function () {
    return redirect()->away('https://skillsrator.com/');
});

Route::get('/invoice/payment/thankyou', [ManualInvoicesController::class,'manual_payment_thankyou']);
Route::get('/invoice/payment/{id}', [ManualInvoicesController::class,'manual_payment_transaction']);

Route::post('/', [gatewayController::class,'index']);

Route::get('/kuickpay/{id}', [gatewayController::class,'kuickpay_create_token']);
Route::get('/kuickpay/voucher/{id}', [gatewayController::class,'kuickpay_voucher']);

Route::get('/kuickpaycard/{id}', [gatewayController::class,'kuickpay_create_card']);
Route::get('/kuickpay-success/{id}', [gatewayController::class,'kuickpay_card_success']);
Route::get('/kuickpay-failure/{id}', [gatewayController::class,'kuickpay_card_failure']);
Route::get('/kuickpay-ipn/{id}', [gatewayController::class,'kuickpay_card_IPN']);

// save deposit slip
Route::post('/deposit_slip_save/{id}', [gatewayController::class,'deposit_slip_save'])->name('deposit_slip_save');

Route::group(['prefix' => 'payment/ec/admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth', 'CheckAdmin']], function () {
    Route::get('/', [HomeController::class,'index'])->name('admhome');
    // Gateways
    Route::delete('gateways/destroy', [GatewaysController::class, 'massDestroy'])->name('gateways.massDestroy');
    Route::resource('gateways', '\App\Http\Controllers\Admin\GatewaysController');

    // Api Keys
    Route::delete('apikeys/destroy', [ApiKeysController::class, 'massDestroy'])->name('apikeys.massDestroy');
    Route::resource('apikeys', '\App\Http\Controllers\Admin\ApiKeysController');

    // IPN Logs
    Route::resource('ipnlogs', '\App\Http\Controllers\Admin\IPNLogsController');

    // Permissions
    Route::delete('permissions/destroy', [PermissionsController::class, 'massDestroy'])->name('permissions.massDestroy');
    Route::resource('permissions', '\App\Http\Controllers\Admin\PermissionsController');

    // Roles
    Route::delete('roles/destroy', [RolesController::class, 'massDestroy'])->name('roles.massDestroy');
    Route::resource('roles', '\App\Http\Controllers\Admin\RolesController');

    // Users
    Route::delete('users/destroy', [UsersController::class, 'massDestroy'])->name('users.massDestroy');
    Route::resource('users', '\App\Http\Controllers\Admin\UsersController');

});

Route::group(['prefix' => 'payment/skillsrator/account', 'as' => 'account.', 'namespace' => 'Account', 'middleware' => ['auth']], function () {
    Route::get('/', [AccountHomeController::class,'index'])->name('acchome');
    Route::get('transaction/update_dollar_rate', [TransactionsController::class, 'update_dollar_rate'])->name('transaction.update_dollar_rate');
    Route::get('transaction/{id}', [TransactionsController::class, 'show_by_orderid'])->name('transaction.show_by_orderid');
    // Transactions
    Route::delete('transactions/destroy', [TransactionsController::class, 'massDestroy'])->name('transactions.massDestroy');
    Route::post('transactions/refund', [TransactionsController::class, 'refund'])->name('transactions.refund');
    Route::post('transactions/txnReconcile', [TransactionsController::class, 'txnReconcile'])->name('transactions.txnReconcile');
    Route::post('transactions/triggerManualIPN', [TransactionsController::class, 'trigger_manual_ipn'])->name('transactions.triggerManualIPN');
    Route::resource('transactions', '\App\Http\Controllers\Account\TransactionsController');

    // Kuickpay transactions
    Route::get('kuickpay/transactions', [TransactionsController::class, 'kuickpay_index'])->name('transaction.kuickpay_index');

    // Deposit Slip transactions
    Route::get('deposit_slips', [TransactionsController::class, 'deposit_slip_transaction_index'])->name('transactions.deposit_slip_transaction_index');
    Route::get('deposit_slip_approve/{id}', [TransactionsController::class, 'deposit_slip_transaction_approve'])->name('transactions.deposit_slip_transaction_approve');
    Route::post('deposit_slip_reject', [TransactionsController::class, 'deposit_slip_transaction_reject'])->name('transactions.deposit_slip_transaction_reject');
    Route::get('deposit_slip/{id}', [TransactionsController::class, 'deposit_slip_transaction_show'])->name('transactions.deposit_slip_transaction_show');

    Route::resource('depositSlipFields', '\App\Http\Controllers\Account\TransactionDepositSlipFieldsController');
    Route::resource('depositSlipFieldsData', '\App\Http\Controllers\Account\TransactionDepositSlipFieldsDataController');

    // Refund transaction Requests
    Route::get('refund_requests', [TransactionsController::class, 'transaction_refund_request_index'])->name('transactions.transaction_refund_request_index');
    Route::post('refund_request_approve', [TransactionsController::class, 'transaction_refund_request_approve'])->name('transactions.refund_request_transaction_approve');
    Route::post('refund_request_reject', [TransactionsController::class, 'transaction_refund_request_reject'])->name('transactions.refund_request_transaction_reject');

    Route::resource('manual_invoices', '\App\Http\Controllers\Account\ManualInvoicesController');

});
require __DIR__.'/auth.php';
// require __DIR__.'/kuickpay.php';
