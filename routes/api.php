<?php

use Illuminate\Http\Request;

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

Route::post('/payment_successfully', 'HomeController@payment_successfully')->name('payment_successfully');
Route::post('/topup_payment_successfully', 'HomeController@topup_payment_successfully')->name('topup_payment_successfully');

Route::post('/senangpay_payment_successfully', 'HomeController@senangpay_payment_successfully')->name('senangpay_payment_successfully');
Route::post('/revpay_payment_successfully', 'HomeController@revpay_payment_successfully')->name('revpay_payment_successfully');
Route::post('/surepay_payment_successfully', 'HomeController@surepay_payment_successfully')->name('surepay_payment_successfully');
Route::post('/gkash_payment_successfully', 'HomeController@gkash_payment_successfully')->name('gkash_payment_successfully');
Route::post('/gkash_payment_status', 'HomeController@gkash_payment_status')->name('gkash_payment_status');

Route::post('/request_transaction', 'APIController@request_transaction')->name('request_transaction');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// get access token
Route::post('/v1/auth', 'APIController@authorizeAccount')->name('authorize_account');
// refresh access token
Route::post('/v1/auth/refresh_token', 'APIController@refreshAccessToken')->name('refresh_access_token');

// product api
Route::post('/v1/products/get', 'APIController@getProductListing')->name('get_product_listing');
Route::post('/v1/transaction/add', 'APIController@createTransaction')->name('api_add_transaction');
Route::post('/v1/transaction/updateStatus', 'APIController@updateTransactionStatus')->name('api_update_transaction_status');

Route::post('/v1/transactions/get', 'APIController@getTransactionListing')->name('get_transaction_listing');
Route::post('/v1/transaction/package/get/{transaction_no}', 'APIController@getTransactionPackage')->name('get_transaction_package');

// Polled by the local print agent (php artisan print:agent) running on the
// machine with the printers attached - see config/printing.php 'mode'.
Route::middleware('print.agent.token')->prefix('print-agent')->group(function () {
    Route::get('/queue', 'API\PrintAgentController@queue')->name('print_agent.queue');
    Route::get('/pdf/{transactionId}/{documentType}', 'API\PrintAgentController@pdf')->name('print_agent.pdf');
    Route::post('/ack/{transactionId}/{documentType}', 'API\PrintAgentController@ack')->name('print_agent.ack');
    Route::post('/skip-existing', 'API\PrintAgentController@skipExisting')->name('print_agent.skip_existing');
});

