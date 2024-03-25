<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Account\TransactionsController;
use App\Http\Controllers\kuickpayController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['api', 'CheckApiToken']], function() {
    Route::post('delete/transaction/{id}', [TransactionsController::class, 'txnDelete']);
    Route::post('expiry/transaction/{id}', [TransactionsController::class, 'txnExpire']);
    Route::post('expire/voucher/{voucher}', [TransactionsController::class, 'voucherExpire']);
    Route::post('transaction/{id}', [TransactionsController::class, 'txnStatus']);
    Route::post('vouchers/create', [TransactionsController::class, 'createMultipleTxn']);
    Route::post('transactions/refund', [TransactionsController::class, 'refund']);
});

Route::group(['prefix' => 'kuickpay', 'middleware' => ['CheckApiToken']], function () {
    Route::post('/echoa', [kuickpayController::class,'echoa']);
    Route::post('/billInquiry', [kuickpayController::class,'billInquiry']);
    Route::post('/billPayment', [kuickpayController::class,'billPayment']);
});

Route::group(['prefix' => 'kuickpay2', 'middleware' => ['CheckApiToken']], function () {
    Route::post('/echoa', [kuickpayController::class,'echoa']);
    Route::post('/billInquiry', [kuickpayController::class,'billInquiry']);
    Route::post('/billPayment', [kuickpayController::class,'billPayment']);
    Route::post('vouchers/create', [TransactionsController::class, 'createMultipleTxn']);
});
