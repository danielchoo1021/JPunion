<?php

use App\Http\Controllers\GlobalController;

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

// Route::get('/', function () {
//     return view('frontend.welcome');
// })->name('home-page');



Auth::routes();
Route::get('/maintain', function () {
    return view('maintain');
})->name('maintain');

Route::get('/ReturnPolicy', function () {
	echo GlobalController::check_authorize();
    return view('frontend.return_policy');
})->name('return_policy');

Route::get('/Terms', function () {
	echo GlobalController::check_authorize();
    return view('frontend.terms_and_condition');
})->name('tnc');

Route::get('/PrivacyPolicy', function () {
	echo GlobalController::check_authorize();
    return view('frontend.privacy_policy');
})->name('privacy_policy');

Route::get('/ShippingPolicy', function () {
	echo GlobalController::check_authorize();
    return view('frontend.shipping_n_return');
})->name('shipping_policy');



Route::get('/merchant_login', 'HomeController@merchant_login')->name('merchant_login');
Route::post('/authorize_merchant', 'HomeController@authorize_merchant')->name('authorize_merchant');

Route::get('/LoadTotalPV', 'AjaxController@LoadTotalPV')->name('LoadTotalPV');
Route::get('/LoadMonthlyPV', 'AjaxController@LoadMonthlyPV')->name('LoadMonthlyPV');

Route::get('/LoadPersonalTotalPV', 'AjaxController@LoadPersonalTotalPV')->name('LoadPersonalTotalPV');
Route::get('/LoadPersonalMonthlyPV', 'AjaxController@LoadPersonalMonthlyPV')->name('LoadPersonalMonthlyPV');
Route::get('/LoadPersonalLastMonthPV', 'AjaxController@LoadPersonalLastMonthPV')->name('LoadPersonalLastMonthPV');

Route::get('/getTeamBonusTier', 'AjaxController@getTeamBonusTier')->name('getTeamBonusTier');

Route::get('/Menu', 'HomeController@menu')->name('menu');
Route::get('/merchant_register', 'HomeController@merchant_register')->name('merchant_register');
Route::get('/register_option', 'HomeController@register_option')->name('register_option');
Route::get('/company_register', 'HomeController@company_register')->name('company_register');
Route::get('/', 'HomeController@index')->name('home');
Route::get('/ECommerce', 'HomeController@listing')->name('listing');
Route::get('/PointMall', 'HomeController@PointMall')->name('PointMall');
Route::get('/Mall', 'HomeController@mall')->name('mall');
Route::get('/Details/{id}', 'HomeController@details')->name('details');
Route::get('/MallDetails/{id}', 'HomeController@details_mall')->name('details_mall');
Route::get('/DownloadInvoice/{transaction_no}', 'HomeController@download_invoice')->name('download_invoice');
Route::get('/Mall', 'HomeController@Mall')->name('Mall');


Route::get('/Promotion_Listing', 'HomeController@promotion_listing')->name('promotion_listing');
Route::get('/Promotion_Details/{name}/{id}', 'HomeController@promo_details')->name('promo_details');

Route::get('/OurLatest', 'HomeController@our_latest')->name('our_latest');
Route::get('/OurLatestDetail/{id}', 'HomeController@blog_details')->name('blog_details');

Route::get('test_auto_withdrawal', 'GlobalController@test_auto_withdrawal')->name('test_auto_withdrawal');

Route::get('/Feedback/{id}', 'HomeController@feedback')->name('feedback');

Route::post('/SendForgotPasswordLink', 'HomeController@SendForgotPasswordLink')->name('SendForgotPasswordLink');
Route::get('/ForgetPassword/{code}', 'HomeController@ForgetPassword')->name('ForgetPassword');
Route::post('/resetPassword/', 'HomeController@resetPassword')->name('resetPassword');

Route::post('/GetRegisterPayment', 'AjaxController@GetRegisterPayment')->name('GetRegisterPayment');


Route::post('/getVerifyCode', 'AjaxController@getVerifyCode')->name('getVerifyCode');
Route::post('/resetVerifyCode', 'AjaxController@resetVerifyCode')->name('resetVerifyCode');
Route::post('/CheckLogin', 'AjaxController@CheckLogin')->name('CheckLogin');
Route::post('/checkAccountFrozen', 'AjaxController@checkAccountFrozen')->name('checkAccountFrozen');

Route::get('/auth/redirect/{provider}', 'GoogleSocialController@redirect');
Route::get('/callback/{provider}', 'GoogleSocialController@callback');
Route::get('/About', 'HomeController@about')->name('about');
Route::get('/faqs', 'HomeController@faqs')->name('faqs');
Route::get('/Contact', 'HomeController@Contact')->name('Contact');
Route::get('/VerifyAccount/{user_id}', 'HomeController@VerifyAccount')->name('VerifyAccount');
Route::get('/verify_success', 'HomeController@verify_success')->name('verify_success');
Route::get('/Blog', 'HomeController@blogs')->name('blogs');

Route::post('/contact_us_send', 'HomeController@contact_us_send')->name('contact_us_send');
Route::post('/join_us_send', 'HomeController@join_us_send')->name('join_us_send');
Route::post('/frontUpdatePassword', 'AjaxController@frontUpdatePassword')->name('frontUpdatePassword');
Route::post('/getUplineDetail', 'AjaxController@getUplineDetail')->name('getUplineDetail');
Route::post('/getStatePrice', 'AjaxController@getStatePrice')->name('getStatePrice');
Route::post('/getCities', 'AjaxController@getCities')->name('getCities');
Route::post('/getBillCities', 'AjaxController@getBillCities')->name('getBillCities');
Route::post('/getCartQuantity', 'AjaxController@getCartQuantity')->name('getCartQuantity');
Route::get('/getOrderNotification', 'AjaxController@getOrderNotification')->name('getOrderNotification');
Route::post('/getFlashSaleDetail', 'AjaxController@getFlashSaleDetail')->name('getFlashSaleDetail');

Route::post('/deal_cart', 'HomeController@deal_cart')->name('deal_cart');

Route::get('/add_share_cart_link', 'AjaxController@add_share_cart_link')->name('add_share_cart_link');
Route::get('/Subcategory', 'HomeController@Subcategory')->name('Subcategory');
Route::get('/QrPayment/{code}', 'HomeController@QrPayment')->name('QrPayment');
Route::post('/QrPaymentSubmit/{code}', 'HomeController@QrPaymentSubmit')->name('QrPaymentSubmit');

Route::get('/testSubmitDocument', 'Backend\BrandController@testSubmitDocument')->name('testSubmitDocument');

Route::get('/Quiz', 'HomeController@quiz')->name('quiz');
Route::post('/submit_quiz', 'HomeController@submit_quiz')->name('submit_quiz');
Route::get('/quiz_result/{id}', 'HomeController@quiz_result')->name('quiz_result');

Route::post('/getVariationimage', 'AjaxController@getVariationimage')->name('getVariationimage');

Route::group(['middleware' => 'auth:web,merchant,agent,admin,corporate'], function () {
	Route::get('/sales', 'HomeController@sales')->name('sales');
	
	Route::get('/MyStocks', 'HomeController@my_stock')->name('my_stock');
	Route::get('/MyStocksHistory', 'HomeController@MyStocksHistory')->name('MyStocksHistory');
	Route::post('/SubmitWithdrawalStock', 'AjaxController@SubmitWithdrawalStock')->name('SubmitWithdrawalStock');
	
	Route::get('/DownloadCertificate/{type}', 'HomeController@download_certificate')->name('download_certificate');
	Route::get('/DownloadMyCertificate', 'HomeController@download_my_certificate')->name('download_my_certificate');

	Route::get('/Cart', 'HomeController@cart')->name('cart');
	Route::get('/Material', 'HomeController@Material')->name('Material');
	Route::get('/Profile', 'HomeController@profile')->name('profile');
	Route::get('/Ranking', 'HomeController@Ranking')->name('Ranking');
	Route::get('/MyVoucher', 'HomeController@my_voucher')->name('my_voucher');
	Route::get('/MyWallet', 'HomeController@wallet')->name('wallet');
	Route::get('/MyOrder', 'HomeController@order_list')->name('order_list');
	Route::get('/MyWishList', 'HomeController@wish_list')->name('wish_list');
	Route::get('/OrderDetails/{no}', 'HomeController@order_detail')->name('order_detail');
	Route::get('/ChangePassword', 'HomeController@changePassword')->name('changePassword');
	Route::get('downloadCP58', 'HomeController@downloadCP58')->name('downloadCP58');
	Route::get('downloadMaterial', 'HomeController@downloadMaterial')->name('downloadMaterial');
	Route::get('downloadEach', 'HomeController@downloadEach')->name('downloadEach');
	

	Route::get('/BankAccount', 'HomeController@bank_account')->name('bank_account');
	Route::get('/BankAccount/{id}', 'HomeController@bank_account_edit')->name('bank_account_edit');
	Route::get('/BankAccount/{id}/delete', 'HomeController@bank_account_delete')->name('bank_account_delete');
	Route::get('/PendingOrder', 'HomeController@pending_order')->name('pending_order');
	Route::get('/PendingShippingOrder', 'HomeController@pending_shipping_order')->name('pending_shipping_order');
	Route::get('/PendingShipping', 'HomeController@pending_shipping')->name('pending_shipping');
	Route::get('/PendingReceive', 'HomeController@pending_receive')->name('pending_receive');
	Route::get('/CompletedOrder', 'HomeController@completed_order')->name('completed_order');
	Route::get('/CancelledOrder', 'HomeController@cancelled_order')->name('cancelled_order');
	Route::get('/AwaitingOrder', 'HomeController@awaiting_order')->name('awaiting_order');
	Route::get('/VerifyingOrder', 'HomeController@verifying_order')->name('verifying_order');

	Route::post('/pay_shipping_fee', 'HomeController@pay_shipping_fee')->name('pay_shipping_fee');
	Route::post('/update_pending_address', 'HomeController@update_pending_address')->name('update_pending_address');
	Route::post('/setPickup', 'AjaxController@setPickup')->name('setPickup');

	Route::post('/BankAccount', 'HomeController@bank_account_save')->name('bank_account_save');
	Route::get('/LogisticTracking/{transaction_no}', 'HomeController@logistic_tracking')->name('logistic_tracking');
	
	Route::post('/save_wallet', 'HomeController@save_wallet')->name('save_wallet');
	Route::post('/transfer_cash_to_topup', 'HomeController@transfer_cash_to_topup')->name('transfer_cash_to_topup');
	
	Route::post('/Profile', 'HomeController@updateProfile')->name('profile');
	Route::post('/updateNewPassword', 'HomeController@updateNewPassword')->name('updateNewPassword');
	Route::resource('AddressBook', 'UserShippingAddressController', ['as'=> 'AddressBook']);

	Route::get('/MyQRcode', 'HomeController@myqrcode')->name('myqrcode');

	Route::get('MyAffiliate/{code}', 'HomeController@MyAffiliate')->name('MyAffiliate');
	Route::get('MyCustomer/{code}', 'HomeController@MyCustomer')->name('MyCustomer');
	Route::get('MyCustomerTransaction/{code}', 'HomeController@MyCustomerTransaction')->name('MyCustomerTransaction');

	//Frontend
	
	Route::post('/SelectCart', 'AjaxController@SelectCart')->name('SelectCart');
	Route::post('/changeDefaultAddress', 'AjaxController@changeDefaultAddress')->name('changeDefaultAddress');
	Route::post('/deleteAddress', 'AjaxController@deleteAddress')->name('deleteAddress');
	Route::post('/Favourite', 'AjaxController@add_wish')->name('Favourite');
	Route::post('/add_to_wish', 'AjaxController@add_to_wish')->name('add_to_wish');
	Route::post('/remove_wish', 'AjaxController@remove_wish')->name('remove_wish');
	Route::post('/Repayment', 'AjaxController@Repayment')->name('Repayment');
	Route::post('/cdmRepayment', 'AjaxController@cdmRepayment')->name('cdmRepayment');
	Route::post('/setBankDefault', 'AjaxController@setBankDefault')->name('setBankDefault');
	Route::post('/viewTransaction', 'AjaxController@viewTransaction')->name('viewTransaction');
	Route::post('/SetWithdrawalType', 'AjaxController@SetWithdrawalType')->name('SetWithdrawalType');

	Route::get('/MySetting', 'HomeController@my_setting')->name('my_setting');
	Route::post('/blog_comment/{id}', 'HomeController@blog_comment')->name('blog_comment');

	
	Route::post('/submit_topup', 'HomeController@submit_topup')->name('submit_topup');
	Route::get('/CreateCartLink', 'AjaxController@CreateCartLink')->name('CreateCartLink');
	Route::post('/getCartAmount', 'AjaxController@getCartAmount')->name('getCartAmount');

	Route::get('customer_invoice/{transaction_no}', 'HomeController@customer_invoice')->name('customer_invoice');
	Route::post('/update_address', 'HomeController@update_address')->name('update_address');
	Route::get('/get_shipping_address', 'AjaxController@get_shipping_address')->name('get_shipping_address');
	Route::post('/get_shipping_fee', 'AjaxController@get_shipping_fee')->name('get_shipping_fee');
	Route::post('/add_new_shipping_address', 'AjaxController@add_new_shipping_address')->name('add_new_shipping_address');

	Route::post('/displayPv', 'AjaxController@displayPv')->name('displayPv');
});
Route::post('/getShippingFee', 'AjaxController@getShippingFee')->name('getShippingFee');
Route::post('/ForgetPasswordEmail', 'AjaxController@ForgetPasswordEmail')->name('ForgetPasswordEmail');

// Route::get('/resetPassword/{code}', 'HomeController@resetPassword')->name('resetPassword');
Route::post('/resetPasswordAction/{code}', 'HomeController@resetPasswordAction')->name('resetPasswordAction');
Route::get('/Checkout', 'HomeController@checkout')->name('checkout');
Route::get('/CheckoutMall', 'HomeController@checkout_mall')->name('checkout_mall');

Route::post('/getTopupPackages', 'AjaxController@getTopupPackages')->name('getTopupPackages');


Route::post('/ProceedCartLink', 'AjaxController@ProceedCartLink')->name('ProceedCartLink');
Route::post('/add_new_address', 'HomeController@add_new_address')->name('add_new_address');

Route::get('/updateCart', 'AjaxController@updateCart')->name('updateCart');
Route::get('/CountCart', 'AjaxController@CountCart')->name('CountCart');
Route::get('/CountCartMall', 'AjaxController@CountCartMall')->name('CountCartMall');
Route::post('/AddToCart', 'AjaxController@AddToCart')->name('AddToCart');
Route::post('/ApplyPromo', 'AjaxController@ApplyPromo')->name('ApplyPromo');
Route::post('/removePromotion', 'AjaxController@removePromotion')->name('removePromotion');
Route::get('/setNewGuest', 'AjaxController@setNewGuest')->name('setNewGuest');
Route::post('/placeOrder', 'HomeController@placeOrder')->name('placeOrder');
Route::post('/placeOrderMall', 'HomeController@placeOrderMall')->name('placeOrderMall');
Route::post('/repayment', 'HomeController@repayment')->name('repayment');
Route::post('/getVariation', 'AjaxController@getVariation')->name('getVariation');
Route::post('/getSecondVariation', 'AjaxController@getSecondVariation')->name('getSecondVariation');
Route::post('/getSecondVariationList', 'AjaxController@getSecondVariationList')->name('getSecondVariationList');
Route::post('/getAddressDetails', 'AjaxController@getAddressDetails')->name('getAddressDetails');
Route::post('/getReferrerDetails', 'AjaxController@getReferrerDetails')->name('getReferrerDetails');
Route::post('/getVariationPromotion', 'AjaxController@getVariationPromotion')->name('getVariationPromotion');
Route::post('/getSecondVariationPromotion', 'AjaxController@getSecondVariationPromotion')->name('getSecondVariationPromotion');

Route::post('/getVariationDropdown', 'AjaxController@getVariationDropdown')->name('getVariationDropdown');
Route::post('/getSecondVariationDropdown', 'AjaxController@getSecondVariationDropdown')->name('getSecondVariationDropdown');
Route::post('getVariationAndStock', 'Backend\AjaxController@getVariationAndStock')->name('getVariationAndStock');

Route::post('/deleteCart', 'AjaxController@deleteCart')->name('deleteCart');
Route::post('/updateQuantity', 'AjaxController@updateQuantity')->name('updateQuantity');
Route::post('/checkAvailablePackage', 'AjaxController@checkAvailablePackage')->name('checkAvailablePackage');
Route::get('/PaymentProcess/{transactions}', 'HomeController@PaymentProcess')->name('PaymentProcess');


Route::get('/SenangPay_PaymentProcess/{transactions}', 'HomeController@SenangPay_PaymentProcess')->name('SenangPay_PaymentProcess');
Route::get('/RevPay_PaymentProcess/{transactions}/{payment_id}', 'HomeController@RevPay_PaymentProcess')->name('RevPay_PaymentProcess');
Route::get('/SurePay_PaymentProcess/{transactions}', 'HomeController@SurePay_PaymentProcess')->name('SurePay_PaymentProcess');
Route::get('/GKash_PaymentProcess/{transactions}', 'HomeController@GKash_PaymentProcess')->name('GKash_PaymentProcess');

Route::get('/TopupPaymentProcess/{user_id}/{amount}', 'HomeController@TopupPaymentProcess')->name('TopupPaymentProcess');

Route::get('/Payment_Error/', 'HomeController@Payment_Error')->name('Payment_Error');
Route::post('/guestAgent/', 'AjaxController@guestAgent')->name('guestAgent');

Route::get('/Confirmation_message/', 'AjaxController@Confirmation_message')->name('Confirmation_message');


Route::get('/admin_login', 'Auth\AdminLoginController@ShowAdminLogin')->name('admin_login');

Route::post('/admin_login', 'Auth\AdminLoginController@login')->name('admin_login');
Route::post('/admin_logout', 'Auth\AdminLoginController@admin_logout')->name('admin_logout');

Route::group(['middleware' => 'auth:admin,merchant,staff'], function () {
	Route::post('/sendTransactionEinvoice','Backend\AjaxController@sendTransactionEinvoice')->name('sendTransactionEinvoice');

	Route::get('/transactions/create_point', 'Backend\TransactionController@create_transaction_points')->name('create_transaction_points');
	Route::post('get_remaining_points', 'Backend\AjaxController@get_remaining_points')->name('get_remaining_points');

	Route::post('/get_transaction_detail','Backend\AjaxController@get_transaction_detail')->name('get_transaction_detail');
	Route::get('/get_agent_address/{code}', 'Backend\AjaxController@get_agent_address')->name('get_agent_address');
	Route::post('/getShippingAddress', 'Backend\AjaxController@getShippingAddress')->name('getShippingAddress');
	
	Route::post('/SortBanner', 'Backend\AjaxController@SortBanner')->name('SortBanner');
	Route::get('qr_transactions_list','Backend\TransactionController@qr_transactions_list')->name('qr_transactions_list'); 
	Route::get('view_qr_transactions/{id}','Backend\TransactionController@view_qr_transactions')->name('view_qr_transactions');
	
	Route::get('products/{id}/stock', 'Backend\ProductController@stock')->name('stock');
	Route::post('products/{id}/stock', 'Backend\ProductController@Submitstock')->name('stock');

	Route::get('products/{id}/edit/edit_variation', 'Backend\ProductController@edit_variation')->name('edit_variation');
	Route::post('products/{id}/edit/edit_variation', 'Backend\ProductController@save_edit_variation')->name('save_edit_variation');

	Route::get('products/packages/add', 'Backend\ProductController@packages_add')->name('packages_add');
	Route::post('products/packages/add', 'Backend\ProductController@packages_add_save')->name('packages_add_save');

	Route::get('products/packages/{id}/edit', 'Backend\ProductController@packages_edit')->name('packages_edit');
	Route::post('products/packages/{id}/edit', 'Backend\ProductController@packages_edit_save')->name('packages_edit_save');

	Route::get('products/packages_list/', 'Backend\ProductController@packages_list')->name('packages_list');

	Route::get('products/promotion_item_list/', 'Backend\ProductController@promotion_item_list')->name('promotion_item_list');

	Route::get('products/promotion_item_list/create', 'Backend\ProductController@promotion_item_add')->name('promotion_item_add');
	Route::post('products/promotion_item_list/create', 'Backend\ProductController@promotion_item_add_save')->name('promotion_item_add_save');

	Route::get('products/promotion_item_list/{id}/edit', 'Backend\ProductController@promotion_item_edit')->name('promotion_item_edit');

	Route::post('products/promotion_item_list/{id}/edit', 'Backend\ProductController@promotion_item_edit_save')->name('promotion_item_edit_save');

	Route::get('products/point_product_list/', 'Backend\ProductController@point_product_list')->name('point_product_list');

	Route::get('products/point_product_list/create', 'Backend\ProductController@point_product_add')->name('point_product_add');
	Route::post('products/point_product_list/create', 'Backend\ProductController@point_product_add_save')->name('point_product_add_save');

	Route::get('products/point_product_list/{id}/edit', 'Backend\ProductController@point_product_edit')->name('point_product_edit');
	Route::post('products/point_product_list/{id}/edit', 'Backend\ProductController@point_product_edit_save')->name('point_product_edit_save');

	Route::get('products/{id}/sold_quantity', 'Backend\ProductController@sold_quantity')->name('sold_quantity');
	Route::post('products/{id}/submit_sold_quantity', 'Backend\ProductController@submit_sold_quantity')->name('submit_sold_quantity');

	Route::get('quiz_records_index', 'Backend\QuizController@quiz_records_index')->name('quiz_records_index');
	Route::get('quiz_records_view/{id}', 'Backend\QuizController@quiz_records_view')->name('quiz_records_view');

	Route::resource('dashboards', 'Backend\DashboardController', ['as'=> 'dashboard']);
	Route::resource('admins', 'Backend\AdminController', ['as'=> 'admin']);
	Route::resource('agents', 'Backend\AgentController', ['as'=> 'agent']);
	Route::resource('merchants', 'Backend\MerchantController', ['as'=> 'merchant']);
	Route::resource('staffs', 'Backend\StaffController', ['as'=> 'staff']);

	Route::resource('feedbacks', 'Backend\FeedbackController', ['as'=> 'feedback']);

	Route::resource('products', 'Backend\ProductController', ['as'=> 'product']);
	Route::resource('point_malls', 'Backend\PointMallController', ['as'=> 'point_mall']);

	Route::resource('categories', 'Backend\CategoryController', ['as'=> 'category']);
	Route::resource('brands', 'Backend\BrandController', ['as'=> 'brand']);
	Route::resource('promotions', 'Backend\PromotionController', ['as'=> 'promotion']);
	Route::resource('newsletters', 'Backend\NewsletterController', ['as'=> 'newsletter']);
	Route::resource('transactions', 'Backend\TransactionController', ['as'=> 'transaction']);
	Route::resource('sub_categories', 'Backend\SubCategoryController', ['as'=> 'sub_category']);
	Route::resource('user_permissions', 'Backend\UserPermissionController', ['as'=> 'user_permission']);
	Route::resource('members', 'Backend\MemberController', ['as'=> 'member']);
	Route::resource('corporates', 'Backend\CorporateController', ['as'=> 'corporate']);
	Route::resource('payment_banks', 'Backend\PaymentBankController', ['as'=> 'payment_bank']);
	Route::resource('bundles', 'Backend\BundleController', ['as'=> 'bundle']);
	Route::resource('banks', 'Backend\BankController', ['as'=> 'bank']);
	Route::resource('flash_sales', 'Backend\FlashSaleController', ['as'=> 'flash_sale']);
	Route::resource('cart_links', 'Backend\CartLinkController', ['as' => 'cart_link']);
	
	Route::resource('quizs', 'Backend\QuizController', ['as'=> 'quiz']);
	Route::resource('blogs', 'Backend\BlogController', ['as'=> 'blog']);
	Route::resource('setting_all_faqs', 'Backend\FAQsController', ['as'=> 'setting_all_faq']);

	Route::post('getProducts', 'Backend\AjaxController@getProducts')->name('getProducts');
	Route::post('/actionProduct/', 'Backend\AjaxController@actionProduct')->name('actionProduct');
	Route::post('sortingPackagesProduct/', 'Backend\AjaxController@sortingPackagesProduct')->name('sortingPackagesProduct');
	
	Route::post('getOption', 'Backend\AjaxController@getOption')->name('getOption');
	Route::post('getOptionPricing', 'Backend\AjaxController@getOptionPricing')->name('getOptionPricing');
	Route::post('getOptionDetail', 'Backend\AjaxController@getOptionDetail')->name('getOptionDetail');
	Route::post('getSecondOptionDetail', 'Backend\AjaxController@getSecondOptionDetail')->name('getSecondOptionDetail');

	Route::post('getVariationStock', 'Backend\AjaxController@getVariationStock')->name('getVariationStock');
	Route::post('getTransactionVariation', 'Backend\AjaxController@getTransactionVariation')->name('getTransactionVariation');
	
	Route::post('getTransactionSecondVariation', 'Backend\AjaxController@getTransactionSecondVariation')->name('getTransactionSecondVariation');
	Route::post('getSecondVariationStock', 'Backend\AjaxController@getSecondVariationStock')->name('getSecondVariationStock');

	Route::post('add_permission_level', 'Backend\UserPermissionController@add_permission_level')->name('add_permission_level');
	// Route::resource('affiliates', 'Backend\AffiliateController', ['as'=> 'affiliate']);

	Route::get('newsletter_history', 'Backend\NewsletterController@newsletter_history')->name('newsletter_history');
	Route::get('member_list', 'Backend\NewsletterController@member_list')->name('member_list');

	Route::get('setting_sales_pop_up', 'Backend\SettingController@setting_sales_pop_up')->name('setting_sales_pop_up');
	Route::post('setting_sales_pop_up', 'Backend\SettingController@save_setting_sales_pop_up')->name('setting_sales_pop_up');

	Route::get('setting_retail_commissions', 'Backend\SettingController@setting_retail_commissions')->name('setting_retail_commissions');
	Route::post('setting_retail_commissions', 'Backend\SettingController@save_setting_retail_commissions')->name('setting_retail_commissions');

	Route::get('setting_loo_bonus', 'Backend\SettingController@setting_loo_bonus')->name('setting_loo_bonus');
	Route::post('setting_loo_bonus', 'Backend\SettingController@save_setting_loo_bonus')->name('setting_loo_bonus');

	Route::get('setting_lol_bonus', 'Backend\SettingController@setting_lol_bonus')->name('setting_lol_bonus');
	Route::post('setting_lol_bonus', 'Backend\SettingController@save_setting_lol_bonus')->name('setting_lol_bonus');

	Route::get('setting_faqs', 'Backend\AdminController@setting_faqs')->name('setting_faqs');
	Route::post('setting_faqs', 'Backend\AdminController@save_setting_faqs')->name('setting_faqs');

	Route::get('setting_instagram', 'Backend\SettingController@setting_testimonial')->name('setting_testimonial');

	Route::get('setting_topup_amount', 'Backend\SettingController@setting_topup_amount')->name('setting_topup_amount');
	Route::post('setting_topup_amount', 'Backend\SettingController@save_setting_topup_amount')->name('setting_topup_amount');

	Route::get('setting_home_page', 'Backend\SettingController@setting_home_page')->name('setting_home_page');
	Route::post('save_setting_home_page', 'Backend\SettingController@save_setting_home_page')->name('save_setting_home_page');

	Route::post('save_qr_type', 'Backend\AjaxController@save_qr_type')->name('save_qr_type');

	Route::get('agent_wallet', 'Backend\AgentController@agent_wallet')->name('agent_wallet');
	Route::get('member_wallet', 'Backend\MemberController@member_wallet')->name('member_wallet');

	Route::get('pending_merchant', 'Backend\MerchantController@pending_merchant')->name('pending_merchant');
	Route::get('pending_agent', 'Backend\AgentController@pending_agent')->name('pending_agent');
	// Route::get('pending_member', 'Backend\MemberController@pending_member')->name('pending_member');

	Route::get('verify_merchant', 'Backend\MerchantController@verify_merchant')->name('verify_merchant');
	Route::get('pending_member', 'Backend\MemberController@pending_member')->name('pending_member');
	Route::get('pending_corporate', 'Backend\CorporateController@pending_corporate')->name('pending_corporate');

	Route::get('agents/{id}/adjustCash', 'Backend\AgentController@AdjustCash')->name('adjustCash');
	Route::post('agents/{id}/adjustCash', 'Backend\AgentController@SubmitAdjustCash')->name('adjustCash');

	Route::get('agents/{id}/adjustPoint', 'Backend\AgentController@adjustPoint')->name('adjustPoint');
	Route::post('agents/{id}/adjustPoint', 'Backend\AgentController@SubmitAdjustPoint')->name('adjustPoint');

	Route::get('agents/{id}/adjustTopup', 'Backend\AgentController@AdjustTopup')->name('AdjustTopup');
	Route::post('agents/{id}/adjustTopup', 'Backend\AgentController@SubmitAdjustTopup')->name('AdjustTopup');

	Route::get('agents/{id}/TransferCashToTopup', 'Backend\AgentController@TransferCashToTopup')->name('TransferCashToTopup');
	Route::post('agents/{id}/SubmitTransferCashToTopup', 'Backend\AgentController@SubmitTransferCashToTopup')->name('SubmitTransferCashToTopup');

	Route::get('members/{id}/adjustMemberCash', 'Backend\MemberController@AdjustMemberCash')->name('adjustMemberCash');
	Route::post('members/{id}/adjustMemberCash', 'Backend\MemberController@SubmitAdjustMemberCash')->name('adjustMemberCash');

	Route::get('members/{id}/adjustMemberPoint', 'Backend\MemberController@adjustMemberPoint')->name('adjustMemberPoint');
	Route::post('members/{id}/adjustMemberPoint', 'Backend\MemberController@SubmitAdjustMemberPoint')->name('adjustMemberPoint');

	Route::get('members/{id}/adjustMemberTopup', 'Backend\MemberController@AdjustMemberTopup')->name('AdjustMemberTopup');
	Route::post('members/{id}/adjustMemberTopup', 'Backend\MemberController@SubmitAdjustMemberTopup')->name('AdjustMemberTopup');
	
	Route::get('agents/{id}/adjustVoucher', 'Backend\MerchantController@AdjustVoucher')->name('adjustVoucher');
	Route::post('agents/{id}/adjustVoucher', 'Backend\MerchantController@SubmitAdjustVoucher')->name('adjustVoucher');

	Route::get('affiliates/{code}', 'Backend\AffiliateController@affiliates')->name('affiliates');
	Route::get('withdrawal_list', 'Backend\TransactionController@withdrawal_list')->name('withdrawal_list');
	Route::get('print_withdrawal_list', 'Backend\TransactionController@print_withdrawal_list')->name('print_withdrawal_list');

	Route::get('topup_list', 'Backend\TransactionController@topup_list')->name('topup_list');

	Route::get('exportTopupList', 'Backend\TransactionController@exportTopupList')->name('exportTopupList');

	Route::get('join_list', 'Backend\TransactionController@join_list')->name('join_list');

	Route::get('setting_agent_level', 'Backend\SettingController@setting_agent_level')->name('setting_agent_level');
	Route::post('setting_agent_level_save', 'Backend\SettingController@setting_agent_level_save')->name('setting_agent_level_save');

	Route::get('setting_partner_level', 'Backend\SettingController@setting_partner_level')->name('setting_partner_level');
	Route::post('setting_partner_level_save', 'Backend\SettingController@setting_partner_level_save')->name('setting_partner_level_save');

	Route::get('setting_area_agent_level', 'Backend\SettingController@setting_area_agent_level')->name('setting_area_agent_level');
	Route::post('setting_area_agent_level_save', 'Backend\SettingController@setting_area_agent_level_save')->name('setting_area_agent_level_save');

	Route::get('setting_merchant_bonus', 'Backend\SettingController@setting_merchant_bonus')->name('setting_merchant_bonus');
	Route::post('setting_merchant_bonus', 'Backend\SettingController@save_setting_merchant_bonus')->name('setting_merchant_bonus');

	Route::get('setting_merchant_commission', 'Backend\SettingController@setting_merchant_commission')->name('setting_merchant_commission');
	Route::post('save_setting_merchant_commission', 'Backend\SettingController@save_setting_merchant_commission')->name('save_setting_merchant_commission');

	Route::get('setting_commission', 'Backend\SettingController@setting_commission')->name('setting_commission');
	Route::post('save_setting_commission', 'Backend\SettingController@save_setting_commission')->name('save_setting_commission');

	Route::get('setting_partner_commission', 'Backend\SettingController@setting_partner_commission')->name('setting_partner_commission');
	Route::post('save_setting_partner_commission', 'Backend\SettingController@save_setting_partner_commission')->name('save_setting_partner_commission');

	Route::get('setting_area_agent_subsidy', 'Backend\SettingController@setting_area_agent_subsidy')->name('setting_area_agent_subsidy');
	Route::post('save_setting_area_agent_subsidy', 'Backend\SettingController@save_setting_area_agent_subsidy')->name('save_setting_area_agent_subsidy');

	Route::get('setting_performance_dividend', 'Backend\SettingController@setting_performance_dividend')->name('setting_performance_dividend');
	Route::post('save_setting_performance_dividend', 'Backend\SettingController@save_setting_performance_dividend')->name('save_setting_performance_dividend');

	Route::get('setting_team_dividend', 'Backend\SettingController@setting_team_dividend')->name('setting_team_dividend');
	Route::post('save_setting_team_dividend', 'Backend\SettingController@save_setting_team_dividend')->name('save_setting_team_dividend');

	Route::get('setting_prize_pool', 'Backend\SettingController@setting_prize_pool')->name('setting_prize_pool');
	Route::post('save_setting_prize_pool', 'Backend\SettingController@save_setting_prize_pool')->name('save_setting_prize_pool');

	Route::get('setting_pv', 'Backend\SettingController@setting_pv')->name('setting_pv');
	Route::post('save_setting_pv', 'Backend\SettingController@save_setting_pv')->name('save_setting_pv');

	Route::get('setting_comm_ranking', 'Backend\SettingController@setting_comm_ranking')->name('setting_comm_ranking');
	Route::post('save_setting_comm_ranking', 'Backend\SettingController@save_setting_comm_ranking')->name('save_setting_comm_ranking');

	Route::get('setting_customer_level', 'Backend\SettingController@setting_customer_level')->name('setting_customer_level');
	Route::post('save_setting_customer_level', 'Backend\SettingController@save_setting_customer_level')->name('save_setting_customer_level');

	Route::get('setting_joining_fee', 'Backend\SettingController@setting_joining_fee')->name('setting_joining_fee');
	Route::post('save_setting_joining_fee', 'Backend\SettingController@save_setting_joining_fee')->name('save_setting_joining_fee');

	Route::get('setting_agent_rebate', 'Backend\SettingController@setting_agent_rebate')->name('setting_agent_rebate');
	Route::post('save_setting_agent_rebate', 'Backend\SettingController@save_setting_agent_rebate')->name('save_setting_agent_rebate');

	Route::get('setting_recommend_bonus', 'Backend\SettingController@setting_recommend_bonus')->name('setting_recommend_bonus');
	Route::post('save_setting_recommend_bonus', 'Backend\SettingController@save_setting_recommend_bonus')->name('save_setting_recommend_bonus');

	Route::get('setting_dual_commission', 'Backend\SettingController@setting_dual_commission')->name('setting_dual_commission');
	Route::post('save_setting_dual_commission', 'Backend\SettingController@save_setting_dual_commission')->name('save_setting_dual_commission');

	Route::get('setting_shipping_fee', 'Backend\SettingController@setting_shipping_fee')->name('setting_shipping_fee');
	Route::post('save_setting_shipping_fee', 'Backend\SettingController@save_setting_shipping_fee')->name('save_setting_shipping_fee');

	Route::get('setting_agent_package', 'Backend\SettingController@setting_agent_package')->name('setting_agent_package');
	Route::post('save_setting_agent_package', 'Backend\SettingController@save_setting_agent_package')->name('save_setting_agent_package');

	Route::get('setting_customer_package', 'Backend\SettingController@setting_customer_package')->name('setting_customer_package');
	Route::post('save_setting_customer_package', 'Backend\SettingController@save_setting_customer_package')->name('save_setting_customer_package');

	Route::get('setting_website_images', 'Backend\SettingController@setting_website_images')->name('setting_website_images');
	Route::post('save_setting_website_images', 'Backend\SettingController@save_setting_website_images')->name('save_setting_website_images');

	Route::get('setting_agent_monthly_sales_bonus', 'Backend\SettingController@setting_agent_monthly_sales_bonus')->name('setting_agent_monthly_sales_bonus');
	Route::post('save_setting_agent_monthly_sales_bonus', 'Backend\SettingController@save_setting_agent_monthly_sales_bonus')->name('save_setting_agent_monthly_sales_bonus');

	Route::get('setting_downline_bonus', 'Backend\SettingController@setting_downline_bonus')->name('setting_downline_bonus');
	Route::post('save_setting_downline_bonus', 'Backend\SettingController@save_setting_downline_bonus')->name('save_setting_downline_bonus');

	Route::post('saveNewPassword/{id}', 'Backend\MerchantController@saveNewPassword')->name('saveNewPassword');
	Route::post('saveAgentNewPassword/{id}', 'Backend\AgentController@saveAgentNewPassword')->name('saveAgentNewPassword');

	Route::post('saveNewStaffPassword/{id}', 'Backend\StaffController@saveNewStaffPassword')->name('saveNewStaffPassword');
	Route::post('saveMemberNewPassword/{id}', 'Backend\MemberController@saveMemberNewPassword')->name('saveMemberNewPassword');
	Route::post('saveCorporateNewPassword/{id}', 'Backend\CorporateController@saveCorporateNewPassword')->name('saveCorporateNewPassword');
	Route::post('changeAgentAccountPersonal/{id}', 'Backend\MerchantController@changeAgentAccountPersonal')->name('changeAgentAccountPersonal');
	Route::post('changeAgentAccountCompany/{id}', 'Backend\MerchantController@changeAgentAccountCompany')->name('changeAgentAccountCompany');
	Route::post('changeUserAccountPersonal/{id}', 'Backend\MemberController@changeUserAccountPersonal')->name('changeUserAccountPersonal');
	Route::post('changeUserAccountCompany/{id}', 'Backend\MemberController@changeUserAccountCompany')->name('changeUserAccountCompany');

	Route::post('/uploadBankSlip', 'Backend\TransactionController@uploadBankSlip')->name('uploadBankSlip');
	Route::post('addBankAccount/{id}', 'Backend\MerchantController@addBankAccount')->name('addBankAccount');

	Route::get('agent_stock_report', 'Backend\ReportController@agent_stock_report')->name('agent_stock_report');
	Route::get('print_agent_stock_report', 'Backend\ReportController@print_agent_stock_report')->name('print_agent_stock_report');
	Route::get('stock_report', 'Backend\ReportController@stock_report')->name('stock_report');
	Route::get('sales_report', 'Backend\ReportController@sales_report')->name('sales_report');
	Route::get('print_sales_report', 'Backend\ReportController@print_sales_report')->name('print_sales_report');
	Route::get('order_report', 'Backend\ReportController@order_report')->name('order_report');
	Route::get('print_order_report', 'Backend\ReportController@print_order_report')->name('print_order_report');
	Route::get('point_order_report', 'Backend\ReportController@point_order_report')->name('point_order_report');
	Route::get('print_point_order_report', 'Backend\ReportController@print_point_order_report')->name('print_point_order_report');
	Route::get('commission_report', 'Backend\ReportController@commission_report')->name('commission_report');
	Route::get('print_commission_report', 'Backend\ReportController@print_commission_report')->name('print_commission_report');
	Route::get('team_reward_report', 'Backend\ReportController@team_reward_report')->name('team_reward_report');
	Route::get('team_reward_report_detail/{code}', 'Backend\ReportController@team_reward_report_detail')->name('team_reward_report_detail');
	Route::get('topup_wallet_report', 'Backend\ReportController@topup_wallet_report')->name('topup_wallet_report');
	Route::get('topup_wallet_report_detail/{code}', 'Backend\ReportController@topup_wallet_report_detail')->name('topup_wallet_report_detail');
	Route::get('cash_wallet_report', 'Backend\ReportController@cash_wallet_report')->name('cash_wallet_report');
	Route::get('cash_wallet_report_detail/{code}', 'Backend\ReportController@cash_wallet_report_detail')->name('cash_wallet_report_detail');
	Route::get('stock_report_details/{product_id}', 'Backend\ReportController@stock_report_details')->name('stock_report_details');
	Route::get('on_hold_report', 'Backend\ReportController@on_hold_report')->name('on_hold_report');
	Route::get('payment_method_report', 'Backend\ReportController@payment_method_report')->name('payment_method_report');
	Route::get('point_report', 'Backend\ReportController@point_report')->name('point_report');
	Route::get('point_report/point_report_details/{id}', 'Backend\ReportController@point_report_details')->name('point_report_details');

	Route::get('agent_sales_report','Backend\ReportController@agent_sales_report')->name('agent_sales_report');
	Route::get('agent_sales_report_detail/{code}','Backend\ReportController@agent_sales_report_detail')->name('agent_sales_report_detail');
	Route::get('print_agent_sales_report', 'Backend\ReportController@print_agent_sales_report')->name('print_agent_sales_report');
	Route::get('print_agent_sales_report_detail/{code}', 'Backend\ReportController@print_agent_sales_report_detail')->name('print_agent_sales_report_detail');

	Route::get('redemption_report', 'Backend\ReportController@redemption_report')->name('redemption_report');
	Route::get('print_redemption_report', 'Backend\ReportController@print_redemption_report')->name('print_redemption_report');

	Route::get('setting_new_customer_promotions', 'Backend\PromotionController@setting_new_customer_promotions')->name('setting_new_customer_promotions');
	Route::post('setting_new_customer_promotion_save', 'Backend\PromotionController@setting_new_customer_promotion_save')->name('setting_new_customer_promotion_save');
	Route::get('new_customer_promotions', 'Backend\PromotionController@new_customer_promotions')->name('new_customer_promotions');

	Route::get('setting_website_messages', 'Backend\SettingController@setting_website_messages')->name('setting_website_messages');
	Route::post('save_setting_website_messages', 'Backend\SettingController@save_setting_website_messages')->name('save_setting_website_messages');

	Route::get('setting_header', 'Backend\SettingController@setting_header')->name('setting_header');
	Route::post('save_setting_header', 'Backend\SettingController@save_setting_header')->name('save_setting_header');

	Route::get('setting_home_video', 'Backend\SettingController@setting_home_video')->name('setting_home_video');
	Route::post('save_setting_home_video', 'Backend\SettingController@save_setting_home_video')->name('save_setting_home_video');

	Route::get('setting_faqs', 'Backend\AdminController@setting_faqs')->name('setting_faqs');
	Route::post('setting_faqs', 'Backend\AdminController@save_setting_faqs')->name('setting_faqs');

	Route::get('setting_home_overview', 'Backend\SettingController@setting_home_overview')->name('setting_home_overview');
	Route::post('save_setting_home_overview', 'Backend\SettingController@save_setting_home_overview')->name('save_setting_home_overview');

	Route::get('setting_featured_product_title', 'Backend\SettingController@setting_featured_product_title')->name('setting_featured_product_title');
	Route::post('save_setting_featured_product_title', 'Backend\SettingController@save_setting_featured_product_title')->name('save_setting_featured_product_title');

	Route::get('setting_website_countries', 'Backend\SettingController@setting_website_countries')->name('setting_website_countries');
	Route::post('save_setting_website_countries', 'Backend\SettingController@save_setting_website_countries')->name('save_setting_website_countries');

	Route::get('sales_report/sales_report_details/{product_id}/{pricing_type}', 'Backend\ReportController@sales_report_details')->name('sales_report_details');
	Route::get('print_sales_report_details', 'Backend\ReportController@print_sales_report_details')->name('print_sales_report_details');

	//export
	Route::get('exportSalesDetails', 'Backend\ReportController@exportSalesDetails')->name('exportSalesDetails');
	Route::get('ExportRedemtion', 'Backend\ReportController@ExportRedemtion')->name('ExportRedemtion');
	Route::get('exportOrder', 'Backend\ReportController@exportOrder')->name('exportOrder');
	Route::get('exportPointOrder', 'Backend\ReportController@exportPointOrder')->name('exportPointOrder');
	Route::get('exportSales', 'Backend\ReportController@exportSales')->name('exportSales');
	Route::get('exportAgentStockReport', 'Backend\ReportController@exportAgentStockReport')->name('exportAgentStockReport');
	Route::get('exportCommissionReport', 'Backend\ReportController@exportCommissionReport')->name('exportCommissionReport');
	Route::get('exportTopupWalletReport', 'Backend\ReportController@exportTopupWalletReport')->name('exportTopupWalletReport');
	Route::get('exportCashWalletReport', 'Backend\ReportController@exportCashWalletReport')->name('exportCashWalletReport');
	Route::get('exportAgentReport', 'Backend\ReportController@exportAgentReport')->name('exportAgentReport');
	Route::get('exportAgentList', 'Backend\AgentController@exportAgentList')->name('exportAgentList');
	Route::get('exportWithdrawalReport', 'Backend\TransactionController@exportWithdrawalReport')->name('exportWithdrawalReport');
	Route::get('exportTransaction', 'Backend\TransactionController@exportTransaction')->name('exportTransaction');
	Route::get('ExportStockDetailsReport', 'Backend\ReportController@ExportStockDetailsReport')->name('exportStockDetailsReport');
	Route::get('ExportStockReport', 'Backend\ReportController@ExportStockReport')->name('exportStockReport');

	Route::get('ExportMerchant', 'Backend\MerchantController@ExportMerchant')->name('ExportMerchant');

	Route::get('tree/{agent_code}', 'Backend\AgentController@tree')->name('tree');
	Route::get('tree_details/{agent_code}/{g}', 'Backend\AgentController@tree_details')->name('tree_details');

	Route::post('/getBankDetails', 'AjaxController@getBankDetails')->name('getBankDetails');

	Route::get('setting_uom', 'Backend\SettingController@setting_uom')->name('setting_uom');
	Route::post('setting_uom_save', 'Backend\SettingController@setting_uom_save')->name('setting_uom_save');

	Route::post('CKEditorUploadImage', 'Backend\AjaxController@CKEditorUploadImage')->name('CKEditorUploadImage');

	Route::get('transaction_invoice/{transaction_no}', 'Backend\TransactionController@transaction_invoice')->name('transaction_invoice');

	Route::get('topup_invoice/{topup_no}', 'Backend\TransactionController@topup_invoice')->name('topup_invoice');

  Route::get('setting_einvoice', 'Backend\SettingController@setting_einvoice')->name('setting_einvoice');
  Route::post('setting_einvoice', 'Backend\SettingController@setting_einvoice_save')->name('setting_einvoice_save');

	Route::get('setting_banner', 'Backend\SettingController@setting_banner')->name('setting_banner');
	Route::get('setting_signature_dish', 'Backend\SettingController@setting_signature_dish')->name('setting_signature_dish');
	Route::get('setting_material', 'Backend\SettingController@setting_material')->name('setting_material');

	Route::get('setting_pick_up_address', 'Backend\SettingController@setting_pick_up_address')->name('setting_pick_up_address');
	Route::post('save_setting_pick_up_address', 'Backend\SettingController@save_setting_pick_up_address')->name('save_setting_pick_up_address');
	
	Route::get('setting_auto_withdrawal', 'Backend\SettingController@setting_auto_withdrawal')->name('setting_auto_withdrawal');
	Route::post('save_setting_auto_withdrawal', 'Backend\SettingController@save_setting_auto_withdrawal')->name('save_setting_auto_withdrawal');
	
	Route::post('add_awb_no', 'Backend\TransactionController@add_awb_no')->name('add_awb_no');
	Route::get('shipping_details/{transaction_no}/{row}', 'Backend\TransactionController@shipping_details')->name('shipping_details');

	Route::get('setting_main_page', 'Backend\SettingController@setting_main_page')->name('setting_main_page');
	Route::post('save_setting_main_page', 'Backend\SettingController@save_setting_main_page')->name('save_setting_main_page');

	Route::get('setting_cod_address', 'Backend\SettingController@setting_cod_address')->name('setting_cod_address');
	Route::post('save_setting_cod_address', 'Backend\SettingController@save_setting_cod_address')->name('save_setting_cod_address');

	Route::get('website_setting', 'Backend\SettingController@website_setting')->name('website_setting');
	Route::post('save_website_setting', 'Backend\SettingController@save_website_setting')->name('save_website_setting');

	Route::post('/setAdminBankDefault', 'Backend\AjaxController@setAdminBankDefault')->name('setAdminBankDefault');
	Route::post('/change_transaction_action/', 'Backend\AjaxController@change_transaction_action')->name('change_transaction_action');
	Route::post('/reprint_invoice/', 'Backend\AjaxController@reprint_invoice')->name('reprint_invoice');

	Route::get('setting_merchant_same_level', 'Backend\SettingController@setting_merchant_same_level')->name('setting_merchant_same_level');
	Route::post('save_setting_merchant_same_level', 'Backend\SettingController@save_setting_merchant_same_level')->name('save_setting_merchant_same_level');

	Route::get('withdrawal_stocks', 'Backend\TransactionController@withdrawal_stocks')->name('withdrawal_stocks');

	Route::post('/change_withdrawal_stock/', 'Backend\AjaxController@change_withdrawal_stock')->name('change_withdrawal_stock');
	
	Route::post('/ApproveRejectMerchant/', 'Backend\AjaxController@ApproveRejectMerchant')->name('ApproveRejectMerchant');
	Route::post('/ApproveRejectUser/', 'Backend\AjaxController@ApproveRejectUser')->name('ApproveRejectUser');

	Route::get('add_on_deal','Backend\PromotionController@add_on_deal')->name('add_on_deal');
	Route::get('add_on_deal_create','Backend\PromotionController@add_on_deal_create')->name('add_on_deal_create');
	Route::get('add_on_deal_edit/{id}','Backend\PromotionController@add_on_deal_edit')->name('add_on_deal_edit');
	Route::post('update_deal','Backend\PromotionController@update_deal')->name('update_deal');
	Route::post('add_on_deal_save','Backend\PromotionController@add_on_deal_save')->name('add_on_deal_save');
	Route::post('product_listing','Backend\AjaxController@product_listing')->name('product_listing');
	Route::post('update_all_sub_item','Backend\AjaxController@update_all_sub_item')->name('update_all_sub_item');
	Route::post('add_on_product_listing','Backend\AjaxController@add_on_product_listing')->name('add_on_product_listing');
	Route::post('save_add_on_deal_item','Backend\AjaxController@save_add_on_deal_item')->name('save_add_on_deal_item');
	Route::get('display_deal_item','Backend\AjaxController@display_deal_item')->name('display_deal_item');
	Route::post('save_sub_item_deal','Backend\AjaxController@save_sub_item_deal')->name('save_sub_item_deal');
	Route::get('display_sub_item','Backend\AjaxController@display_sub_item')->name('display_sub_item');
	Route::post('update_selected_sub_item','Backend\AjaxController@update_selected_sub_item')->name('update_selected_sub_item');
	Route::get('remove_sub_items/{id}','Backend\AjaxController@remove_sub_items')->name('remove_sub_items');
	Route::get('remove_items/{id}','Backend\AjaxController@remove_items')->name('remove_items');

	Route::get('setting_birthday_popup', 'Backend\SettingController@setting_birthday_popup')->name('setting_birthday_popup');
	Route::post('save_setting_birthday_popup', 'Backend\SettingController@save_setting_birthday_popup')->name('save_setting_birthday_popup');

	Route::get('flash_sale_product_listing', 'Backend\AjaxController@flash_sale_product_listing')->name('flash_sale_product_listing');
	Route::post('save_flash_product','Backend\AjaxController@save_flash_product')->name('save_flash_product');
	Route::get('display_flash_products','Backend\AjaxController@display_flash_products')->name('display_flash_products');
	Route::post('update_selected_flash_sale_product','Backend\AjaxController@update_selected_flash_sale_product')->name('update_selected_flash_sale_product');
	Route::post('update_all_flash_sale_product','Backend\AjaxController@update_all_flash_sale_product')->name('update_all_flash_sale_product');
	Route::post('update_flash_product_details', 'Backend\AjaxController@update_flash_product_details')->name('update_flash_product_details');
	Route::post('change_flash_sale_product_status', 'Backend\AjaxController@change_flash_sale_product_status')->name('change_flash_sale_product_status');

	Route::post('get_cart_link_variation', 'Backend\AjaxController@get_cart_link_variation')->name('get_cart_link_variation');
	Route::post('get_cart_link_second_variation', 'Backend\AjaxController@get_cart_link_second_variation')->name('get_cart_link_second_variation');
	Route::post('get_cart_link_product_price', 'Backend\AjaxController@get_cart_link_product_price')->name('get_cart_link_product_price');
	
	Route::post('delete_packages', 'Backend\AjaxController@delete_packages')->name('delete_packages');

	Route::get('/cashier_screen', 'Backend\CafeController@cashier_screen')->name('cashier_screen');

	Route::post('/ChooseCategory', 'Backend\AjaxController@ChooseCategory')->name('ChooseCategory');
	Route::post('/ChooseSubCategory', 'Backend\AjaxController@ChooseSubCategory')->name('ChooseSubCategory');
	Route::post('/ChooseItem', 'Backend\AjaxController@ChooseItem')->name('ChooseItem');
	Route::post('/GetProductVariation', 'Backend\AjaxController@GetProductVariation')->name('GetProductVariation');
	Route::post('/CountCashierCart', 'Backend\AjaxController@CountCashierCart')->name('CountCashierCart');
	Route::post('/cashier_checkout', 'Backend\AjaxController@cashier_checkout')->name('cashier_checkout');
	Route::post('/cashier_pay', 'Backend\AjaxController@cashier_pay')->name('cashier_pay');
	Route::post('/checkTableAvailable', 'Backend\AjaxController@checkTableAvailable')->name('checkTableAvailable');
	Route::post('/GetTableHistory', 'Backend\AjaxController@GetTableHistory')->name('GetTableHistory');
	Route::post('/GenerateQR', 'Backend\AjaxController@GenerateQR')->name('GenerateQR');
	Route::get('/print_qr/{id}/{tid}', 'Backend\CafeController@print_qr')->name('print_qr');
	Route::post('/checkOrderAmount', 'Backend\AjaxController@checkOrderAmount')->name('checkOrderAmount');
	Route::post('/SearchItems', 'Backend\AjaxController@SearchItems')->name('SearchItems');
	Route::get('/print_receipt/{transaction_no}', 'Backend\CafeController@print_receipt')->name('print_receipt');
	Route::post('/PrintTransaction', 'Backend\AjaxController@PrintTransaction')->name('PrintTransaction');
	Route::post('/CancelOrder', 'Backend\AjaxController@CancelOrder')->name('CancelOrder');
	Route::post('/GetTransaction', 'Backend\AjaxController@GetTransaction')->name('GetTransaction');
	Route::get('/saveTransaction/', 'Backend\AjaxController@saveTransaction')->name('saveTransaction');
	Route::post('/SearchTableHistory', 'Backend\AjaxController@SearchTableHistory')->name('SearchTableHistory');
	Route::post('/GetOrders', 'Backend\AjaxController@GetOrders')->name('GetOrders');
	Route::post('/GetAvailableTable', 'Backend\AjaxController@GetAvailableTable')->name('GetAvailableTable');
	Route::post('/GetTransactionList', 'Backend\AjaxController@GetTransactionList')->name('GetTransactionList');
	Route::post('/SearchTransaction', 'Backend\AjaxController@SearchTransaction')->name('SearchTransaction');
	Route::post('/RefundTransaction', 'Backend\AjaxController@RefundTransaction')->name('RefundTransaction');
	Route::post('/CombineReceipt', 'Backend\AjaxController@CombineReceipt')->name('CombineReceipt');
	Route::post('/CombineReceiptSubmit', 'Backend\AjaxController@CombineReceiptSubmit')->name('CombineReceiptSubmit');
	Route::post('/TransferTable', 'Backend\AjaxController@TransferTable')->name('TransferTable');
	Route::get('/getDeliveryOrder', 'Backend\AjaxController@getDeliveryOrder')->name('getDeliveryOrder');
	Route::post('/CompleteDelivery', 'Backend\AjaxController@CompleteDelivery')->name('CompleteDelivery');

	Route::post('/get_member_wallet', 'Backend\AjaxController@get_member_wallet')->name('get_member_wallet');

	Route::post('/getBackendSecondVariationList', 'Backend\AjaxController@getBackendSecondVariationList')->name('getBackendSecondVariationList');
	Route::post('/refresh_carts', 'Backend\AjaxController@refresh_carts')->name('refresh_carts');
	
	Route::get('setting_second_banner', 'Backend\SettingController@setting_second_banner')->name('setting_second_banner');

	Route::post('/update_remark/{tid}', 'Backend\TransactionController@update_remark')->name('update_remark');

	//Ajax
	//Backend
	Route::post('/UploadMaterial/{id}', 'Backend\AjaxController@UploadMaterial')->name('UploadMaterial');
	Route::get('/LoadMaterialImage/{id}', 'Backend\AjaxController@LoadMaterialImage')->name('LoadMaterialImage');
	Route::get('/DeleteMaterialImage/{id}', 'Backend\AjaxController@DeleteMaterialImage')->name('DeleteMaterialImage');
	Route::post('/uploadImage/{id}', 'Backend\AjaxController@uploadImage')->name('uploadImage');
	Route::get('/LoadImage/{id}', 'Backend\AjaxController@LoadImage')->name('LoadImage');
	Route::get('/DeleteImage/{id}', 'Backend\AjaxController@DeleteImage')->name('DeleteImage');
	Route::post('/SortImage', 'Backend\AjaxController@SortImage')->name('SortImage');
	Route::post('/deleteVariation', 'Backend\AjaxController@deleteVariation')->name('deleteVariation');
	Route::post('/deleteSecondVariation', 'Backend\AjaxController@deleteSecondVariation')->name('deleteSecondVariation');

	Route::post('/uploadFeedbackImage/{id}', 'Backend\AjaxController@uploadFeedbackImage')->name('uploadFeedbackImage');
	Route::get('/LoadFeedbackImage/{id}', 'Backend\AjaxController@LoadFeedbackImage')->name('LoadFeedbackImage');
	Route::get('/DeleteFeedBackImage/{id}', 'Backend\AjaxController@DeleteFeedBackImage')->name('DeleteFeedBackImage');

	Route::post('/uploadCategoryImage/{id}', 'Backend\AjaxController@uploadCategoryImage')->name('uploadCategoryImage');
	Route::get('/LoadCategoryImage/{id}', 'Backend\AjaxController@LoadCategoryImage')->name('LoadCategoryImage');
	Route::get('/DeleteCategoryImage/{id}', 'Backend\AjaxController@DeleteCategoryImage')->name('DeleteCategoryImage');

	Route::post('/ApproveRejectMember/', 'Backend\AjaxController@ApproveRejectMember')->name('ApproveRejectMember');
	Route::post('/ApproveRejectCorporate/', 'Backend\AjaxController@ApproveRejectCorporate')->name('ApproveRejectCorporate');
	Route::post('/VerifyMerchant/', 'Backend\AjaxController@VerifyMerchant')->name('VerifyMerchant');
	Route::post('/deleteAgentBonus/', 'Backend\AjaxController@deleteAgentBonus')->name('deleteAgentBonus');

	Route::post('/SetPermission/', 'Backend\AjaxController@SetPermission')->name('SetPermission');
	Route::post('/UnsetPermission/', 'Backend\AjaxController@UnsetPermission')->name('UnsetPermission');
	Route::get('/GetPermission/', 'Backend\AjaxController@GetPermission')->name('GetPermission');

	Route::post('/change_withdrawal_transaction_action/', 'Backend\AjaxController@change_withdrawal_transaction_action')->name('change_withdrawal_transaction_action');

	Route::post('/DeleteUOM', 'Backend\AjaxController@DeleteUOM')->name('DeleteUOM');

	Route::post('/getItemCode', 'Backend\AjaxController@getItemCode')->name('getItemCode');
	Route::post('/getSubItemCode', 'Backend\AjaxController@getSubItemCode')->name('getSubItemCode');

	Route::post('/AgentStatus', 'Backend\AjaxController@AgentStatus')->name('AgentStatus');
	Route::post('/MerchantStatus', 'Backend\AjaxController@MerchantStatus')->name('MerchantStatus');
	Route::post('/UserStatus', 'Backend\AjaxController@UserStatus')->name('UserStatus');
	Route::post('/CorporateStatus', 'Backend\AjaxController@CorporateStatus')->name('CorporateStatus');
	Route::post('/StaffStatus', 'Backend\AjaxController@StaffStatus')->name('StaffStatus');
	Route::post('/ProductStatus', 'Backend\AjaxController@ProductStatus')->name('ProductStatus');
	Route::post('/BundleStatus', 'Backend\AjaxController@BundleStatus')->name('BundleStatus');
	Route::post('/CategoryStatus', 'Backend\AjaxController@CategoryStatus')->name('CategoryStatus');
	Route::post('/SubCategoryStatus', 'Backend\AjaxController@SubCategoryStatus')->name('SubCategoryStatus');
	Route::post('/BrandStatus', 'Backend\AjaxController@BrandStatus')->name('BrandStatus');
	Route::post('/PromotionStatus', 'Backend\AjaxController@PromotionStatus')->name('PromotionStatus');
	Route::post('/setFeatured', 'Backend\AjaxController@setFeatured')->name('setFeatured');
	Route::post('/setBirthdayPromotion', 'Backend\AjaxController@setBirthdayPromotion')->name('setBirthdayPromotion');
	Route::post('/updatePassword', 'Backend\AjaxController@updatePassword')->name('updatePassword');
	Route::post('/updateMemberPassword', 'Backend\AjaxController@updateMemberPassword')->name('updateMemberPassword');
	Route::post('/updateReceiveNewsletter', 'Backend\AjaxController@updateReceiveNewsletter')->name('updateReceiveNewsletter');
	Route::post('/FeedbackStatus', 'Backend\AjaxController@FeedbackStatus')->name('FeedbackStatus');
	Route::post('/PromotionItemStatus', 'Backend\AjaxController@PromotionItemStatus')->name('PromotionItemStatus');
	Route::post('/AddonDealStatus', 'Backend\AjaxController@AddonDealStatus')->name('AddonDealStatus');
	Route::post('/FlashSaleStatus', 'Backend\AjaxController@FlashSaleStatus')->name('FlashSaleStatus');
	Route::post('/CartLinkStatus', 'Backend\AjaxController@CartLinkStatus')->name('CartLinkStatus');

	Route::post('/QuizStatus', 'Backend\AjaxController@QuizStatus')->name('QuizStatus');
	Route::post('/BlogStatus', 'Backend\AjaxController@BlogStatus')->name('BlogStatus');
	Route::post('/FAQsStatus', 'Backend\AjaxController@FAQsStatus')->name('FAQsStatus');

	Route::post('/uploadBannerImage/', 'Backend\AjaxController@uploadBannerImage')->name('uploadBannerImage');
	Route::get('/LoadBannerImage', 'Backend\AjaxController@LoadBannerImage')->name('LoadBannerImage');
	Route::get('/DeleteBannerImage/{id}', 'Backend\AjaxController@DeleteBannerImage')->name('DeleteBannerImage');
	Route::post('/changeBannerUrl', 'Backend\AjaxController@changeBannerUrl')->name('changeBannerUrl');

	Route::post('/uploadTestimonialImage/', 'Backend\AjaxController@uploadTestimonialImage')->name('uploadTestimonialImage');
	Route::get('/LoadTestimonialImage', 'Backend\AjaxController@LoadTestimonialImage')->name('LoadTestimonialImage');
	Route::get('/DeleteTestimonialImage/{id}', 'Backend\AjaxController@DeleteTestimonialImage')->name('DeleteTestimonialImage');

	Route::post('/uploadSignatureDishImage/', 'Backend\AjaxController@uploadSignatureDishImage')->name('uploadSignatureDishImage');
	Route::get('/LoadSignatureDishImage', 'Backend\AjaxController@LoadSignatureDishImage')->name('LoadSignatureDishImage');
	Route::get('/DeleteSignatureDishImage/{id}', 'Backend\AjaxController@DeleteSignatureDishImage')->name('DeleteSignatureDishImage');

	Route::post('/uploadMainPageImage/', 'Backend\AjaxController@uploadMainPageImage')->name('uploadMainPageImage');
	Route::get('/LoadMainPageImage', 'Backend\AjaxController@LoadMainPageImage')->name('LoadMainPageImage');
	Route::get('/DeleteMainPageImage/{id}', 'Backend\AjaxController@DeleteMainPageImage')->name('DeleteMainPageImage');

	Route::post('/GetSubCategory', 'Backend\AjaxController@GetSubCategory')->name('GetSubCategory');
	Route::post('/change_topup_action', 'Backend\AjaxController@change_topup_action')->name('change_topup_action');

	Route::post('DeleteShipping', 'Backend\AjaxController@DeleteShipping')->name('DeleteShipping');

	Route::post('DeleteTeamBonus', 'Backend\AjaxController@DeleteTeamBonus')->name('DeleteTeamBonus');
	Route::post('DeleteTopupBonus', 'Backend\AjaxController@DeleteTopupBonus')->name('DeleteTopupBonus');
	Route::post('DeleteCodAddress', 'Backend\AjaxController@DeleteCodAddress')->name('DeleteCodAddress');

	Route::post('DeleteQRType', 'Backend\AjaxController@DeleteQRType')->name('DeleteQRType');

	Route::post('/courier_service_list', 'Backend\AjaxController@courier_service_list')->name('courier_service_list');
	Route::post('/courier_make_order', 'Backend\AjaxController@courier_make_order')->name('courier_make_order');
	Route::post('/get_tracking_number', 'Backend\AjaxController@get_tracking_number')->name('get_tracking_number');

	Route::post('/updateTitle', 'Backend\AjaxController@updateTitle')->name('updateTitle');
	Route::post('/updateDescription', 'Backend\AjaxController@updateDescription')->name('updateDescription');
	Route::post('/SortMainImage', 'Backend\AjaxController@SortMainImage')->name('SortMainImage');
	Route::post('/sortingProduct', 'Backend\AjaxController@sortingProduct')->name('sortingProduct');
	Route::post('/sortingPromoProduct', 'Backend\AjaxController@sortingPromoProduct')->name('sortingPromoProduct');

	Route::post('/BankStatus', 'Backend\AjaxController@BankStatus')->name('BankStatus');
	Route::post('/PaymentBankStatus', 'Backend\AjaxController@PaymentBankStatus')->name('PaymentBankStatus');
	Route::post('/ApproveAllWithdrawal', 'Backend\AjaxController@ApproveAllWithdrawal')->name('ApproveAllWithdrawal');
	Route::post('/deleteSalesPopup', 'Backend\AjaxController@deleteSalesPopup')->name('deleteSalesPopup');

	Route::post('/MemberUpgrade', 'Backend\AjaxController@MemberUpgrade')->name('MemberUpgrade');

	Route::post('/DeletePromoDetail', 'Backend\AjaxController@DeletePromoDetail')->name('DeletePromoDetail');

	Route::post('/run_cron_job', 'Backend\SettingController@run_cron_job')->name('run_cron_job');

	Route::post('/get_merchant_expired_date', 'Backend\AjaxController@get_merchant_expired_date')->name('get_merchant_expired_date');
	Route::post('/get_merchant_expired_date_with_date', 'Backend\AjaxController@get_merchant_expired_date_with_date')->name('get_merchant_expired_date_with_date');

	Route::get('setting_payment_gateway', 'Backend\SettingController@setting_payment_gateway')->name('setting_payment_gateway');
	Route::post('save_setting_payment_gateway', 'Backend\SettingController@save_setting_payment_gateway')->name('save_setting_payment_gateway');
	
	Route::get('setting_colour', 'Backend\SettingController@setting_colour')->name('setting_colour');
	Route::post('save_setting_colour', 'Backend\SettingController@save_setting_colour')->name('save_setting_colour');


	Route::post('/DeleteSettingWebsiteMessage', 'Backend\AjaxController@DeleteSettingWebsiteMessage')->name('DeleteSettingWebsiteMessage');

	Route::post('/uploadSecondBannerImage/', 'Backend\AjaxController@uploadSecondBannerImage')->name('uploadSecondBannerImage');
	Route::get('/LoadSecondBannerImage', 'Backend\AjaxController@LoadSecondBannerImage')->name('LoadSecondBannerImage');
	Route::get('/DeleteSecondBannerImage/{id}', 'Backend\AjaxController@DeleteSecondBannerImage')->name('DeleteSecondBannerImage');
	Route::post('/changeSecondBannerUrl', 'Backend\AjaxController@changeSecondBannerUrl')->name('changeSecondBannerUrl');
	Route::post('/SortSecondBanner', 'Backend\AjaxController@SortSecondBanner')->name('SortSecondBanner');
});


