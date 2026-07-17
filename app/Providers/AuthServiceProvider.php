<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use View, Auth, DB;
use App\Admin;
use App\Merchant;
use App\User;
use App\Cart;
use App\Product;
use App\ProductImage;
use App\Category;
use App\WebsiteSetting;
use App\Permission;
use App\Favourite;
use App\Promotion;
use App\SettingBanner;
use App\TopupTransaction;
use App\Transaction;
use App\WithdrawalTransaction;
use App\Brand;
use App\AgentLevel;
use App\AffiliateCommission;
use App\BankAccount;
use App\PaymentBank;
use App\Corporate;
use App\AgentPrice;
use App\Agent;
use App\SettingWebsiteMessage;
use App\SettingHeader;
use App\SettingPaymentGateway;
use App\SettingColour;

use App\Http\Controllers\GlobalController;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        View::composer('*', function($view)
        {

            if(Auth::guard('merchant')->check()){
                $userGuardRole = "merchant";
            }elseif(Auth::guard('web')->check()){
                $userGuardRole = "web";
            }elseif(Auth::guard('admin')->check()){
                $userGuardRole = "admin";
            }elseif(Auth::guard('corporate')->check()){
                $userGuardRole = "corporate";
            }elseif(Auth::guard('agent')->check()){
                $userGuardRole = "agent";
            }else{
                $userGuardRole = "";
            }

            $new_guest = "";
            if(empty($_COOKIE['new_guest']) && empty($userGuardRole)){
                // setcookie('new_guest', strtotime(date('Y-m-d H:i:s')).rand(100000, 999999), time() + (86400 * 30), "/");
            }

            if(!empty($_COOKIE['new_guest'])){
                $new_guest = $_COOKIE['new_guest'];
            }else{
                $new_guest = setcookie('new_guest', strtotime(date('Y-m-d H:i:s')).rand(100000, 999999), time() + (86400 * 30), "/");
	       }

            if(!empty(Auth::guard('admin')->user()->code)){
                $HeaderBuyerCode = Auth::guard('admin')->user()->code;
                $HeaderBuyerLvl = Auth::guard('admin')->user()->lvl;
            }elseif(!empty(Auth::guard('merchant')->user()->code)){
                $HeaderBuyerCode = Auth::guard('merchant')->user()->code;
                $HeaderBuyerLvl = Auth::guard('merchant')->user()->lvl;
            }elseif(!empty(Auth::guard('agent')->user()->code)){
                $HeaderBuyerCode = Auth::guard('agent')->user()->code;
                $HeaderBuyerLvl = Auth::guard('agent')->user()->lvl;
            }elseif(!empty(Auth::guard('web')->user()->code)){
                $HeaderBuyerCode = Auth::guard('web')->user()->code;
                $HeaderBuyerLvl = Auth::guard('web')->user()->lvl;
            }elseif(!empty(Auth::guard('corporate')->user()->code)){
                $HeaderBuyerCode = Auth::guard('corporate')->user()->code;
                $HeaderBuyerLvl = Auth::guard('corporate')->user()->lvl;
            }else{
                $HeaderBuyerCode = $new_guest;
                $HeaderBuyerLvl = "";
            }

            $localCheck = $_SERVER["SERVER_NAME"];
            $explo_check = explode('.', $localCheck);
            if($explo_check[0] == 'demoaccount'){
                include('../resources/views/language/language.blade.php');
                $productionURL = "https://newseller.vesson.my";
            }elseif($localCheck != '127.0.0.1'){
                include('resources/views/language/language.blade.php');
                $productionURL = "https://newseller.vesson.my";
            }else{
                include('../resources/views/language/language.blade.php');
                $productionURL = "http://".$localCheck.":8000";
            }

            if(isset($_COOKIE['global_language']) && !empty($_COOKIE['global_language'])){
                if($_COOKIE['global_language'] == '1'){
                    $lang = $chn;
                    $Blang = $backchinese;
                }else{
                    $lang = $eng;
                    $Blang = $backeng;
                }
            }else{
                $lang = $eng;
                $Blang = $backeng;
            }

            if(isset($_COOKIE['backend_global_language']) && !empty($_COOKIE['backend_global_language'])){
                if($_COOKIE['backend_global_language'] == '1'){
                    $backendlang = $backchinese;
                }else{
                    $backendlang = $backeng;
                }
            }else{
                $backendlang = $backeng;
            }
            
            $currency_code = "RM";

            $totalCart = 0;
            $totalCartMall = 0;
            $totalWish = 0;
            $tpq = 0;
            $bank_required = 0;
            $getUserDetails = "";
            $website_messages = [];
            if (!empty($userGuardRole) || !empty($new_guest)){

                $getUserDetails = GlobalController::getUserDetails($HeaderBuyerCode);

                $cart = Cart::select(DB::raw('SUM(qty) AS totalCart'))
                            ->where('user_id', $HeaderBuyerCode)
                            ->whereNull('mall')
                            ->first();

                $totalCart = $cart->totalCart;

                $cart_mall = Cart::select(DB::raw('SUM(qty) AS totalCart'))
                            ->where('user_id', $HeaderBuyerCode)
                            ->where('mall', '1')
                            ->first();

                $totalCartMall = $cart_mall->totalCart;

                $checkCommission = AffiliateCommission::where('user_id', $HeaderBuyerCode)->where('status', '1')->where('type', '!=', '55')->first();
                if(!empty($checkCommission->id)){
                    $checkBank = BankAccount::where('user_id', $HeaderBuyerCode)->where('default_banks', '1')->where('status', '1')->first();

                    if(empty($checkBank->id)){
                        $bank_required = 1;
                    }
                }
            }
            
            $top_categories = Category::where('status', '1')->take(10)->get();

            if(isset($_COOKIE['vmerchant']) && !empty($_COOKIE['vmerchant'])){
                $merchant = Merchant::where(DB::raw('md5(id)'), $_COOKIE['vmerchant'])->first();
                if(!empty($merchant->id)){
                    $get_authorise_status = GlobalController::check_autorize_status(md5($merchant->id));
                    if($get_authorise_status['status'] == 1){
                        $web_setting = $merchant;
                    }else{
                        $web_setting = WebsiteSetting::find(1);
                    }
                }else{
                    $web_setting = WebsiteSetting::find(1);
                }
            }else{
                $web_setting = WebsiteSetting::find(1);
            }

            $admin = Admin::where('id', '1')->first();

            $pls = Permission::get();

            $permission = [];
            if(!$pls->isEmpty()){
                foreach($pls as $pl){
                    $permission[$pl->permission_lvl][$pl->page] = $pl->status;
                }
            }

            $authorised_merchant_code = NULL;
            if(isset($_COOKIE['vmerchant']) && !empty($_COOKIE['vmerchant'])){
                $merchant = Merchant::where(DB::raw('md5(id)'), $_COOKIE['vmerchant'])->first();
                if(!empty($merchant->id)){
                    $get_authorise_status = GlobalController::check_autorize_status(md5($merchant->id));
                    if($get_authorise_status['status'] == 1){
                        $authorised_merchant_code = $get_authorise_status['result']['code'];
                    }
                }
            }

            $pending_agent = Agent::where('status', '99');
            if (!empty($authorised_merchant_code)) {
                $pending_agent = $pending_agent->where('dual_master_id', $authorised_merchant_code);
            }
            $pending_agent = $pending_agent->get();
            $total_pending = count($pending_agent);

            $pending_merchant = Merchant::where('status', '99')->get();
            $total_merchant_pending = count($pending_merchant);

            $pending_member = User::where('status', '99');
            if (!empty($authorised_merchant_code)) {
                $pending_member = $pending_member->where('dual_master_id', $authorised_merchant_code);
            }
            $pending_member = $pending_member->get();
            $total_member_pending = count($pending_member);

            $allPendingTopup = TopupTransaction::where('status', '99');
            if(Auth::guard('merchant')->check()){
            $allPendingTopup = $allPendingTopup->where('merchant_id', Auth::guard('merchant')->user()->code);
            }
            $allPendingTopup = $allPendingTopup->get();

            $allPendingTrans = Transaction::where('transactions.status', '98');
            if(Auth::guard('merchant')->check()){
            $allPendingTrans = $allPendingTrans->join('transaction_details as d', 'd.transaction_id', 'transactions.id')
                                               ->where('d.merchant_id', Auth::guard('merchant')->user()->code)
                                               ->groupBy('transactions.id');
            }
            $allPendingTrans = $allPendingTrans->get();

            $allPendingWith  = WithdrawalTransaction::where('status', '99');
            if(Auth::guard('merchant')->check()){
            $allPendingWith = $allPendingWith->where('merchant_id', Auth::guard('merchant')->user()->code);
            }
            $allPendingWith = $allPendingWith->get();

            $banks = PaymentBank::where('status', '1')->get();


            $loop_start_dates = GlobalController::loop_start_dates();
            $loop_monthly = GlobalController::loop_monthly();

            $global_bank_holder_name = "Bank Holder Name";
            $global_bank_name = "Bank Name";
            $global_bank_account = "Bank Account";

            $website_setting = WebsiteSetting::find(1);

            $categories_home = Category::select('categories.*', 'i.image', 's.*')
                                        ->join('category_images as i', 'categories.id', 'i.category_id')
                                        ->leftJoin('sub_categories as s', 'categories.id', 's.category_id')
                                        ->where('categories.status', '1')
                                        ->where('s.status', '1')
                                        ->groupBy('categories.id')
                                        ->orderByDesc('categories.created_at')
                                        ->get();

            $website_messages = SettingWebsiteMessage::where('status', '1')->get();

            $setting_header = SettingHeader::find(1);

            $senangpay_setting = SettingPaymentGateway::get_senangpay();
            $revpay_setting = SettingPaymentGateway::get_revpay();
            $surepay_setting = SettingPaymentGateway::get_surepay();
            $gkash_setting = SettingPaymentGateway::get_gkash();

            $setting_colour = SettingColour::find(1);

            $button_colour = NULL;
            if (!empty($setting_colour->button_colour)) {
                $button_colour = $setting_colour->button_colour;
            }

            $text_colour = NULL;
            if (!empty($setting_colour->text_colour)) {
                $text_colour = $setting_colour->text_colour;
            }

            $hover_colour = NULL;
            if (!empty($setting_colour->hover_colour)) {
                $hover_colour = $setting_colour->hover_colour;
            }

            $header_announcement_text_colour = NULL;
            if (!empty($setting_colour->header_announcement_text_colour)) {
                $header_announcement_text_colour = $setting_colour->header_announcement_text_colour;
            }

            $header_announcement_background_colour = NULL;
            if (!empty($setting_colour->header_announcement_background_colour)) {
                $header_announcement_background_colour = $setting_colour->header_announcement_background_colour;
            }

            $header_background_colour = NULL;
            if (!empty($setting_colour->header_background_colour)) {
                $header_background_colour = $setting_colour->header_background_colour;
            }
            
            $footer_trademark_text_colour = NULL;
            if (!empty($setting_colour->footer_trademark_text_colour)) {
                $footer_trademark_text_colour = $setting_colour->footer_trademark_text_colour;
            }

            $footer_trademark_background_colour = NULL;
            if (!empty($setting_colour->footer_trademark_background_colour)) {
                $footer_trademark_background_colour = $setting_colour->footer_trademark_background_colour;
            }

            $footer_background_colour = NULL;
            if (!empty($setting_colour->footer_background_colour)) {
                $footer_background_colour = $setting_colour->footer_background_colour;
            }

            $header_text_colour = NULL;
            if (!empty($setting_colour->header_text_colour)) {
                $header_text_colour = $setting_colour->header_text_colour;
            }

            $header_text_hover_colour = NULL;
            if (!empty($setting_colour->header_text_hover_colour)) {
                $header_text_hover_colour = $setting_colour->header_text_hover_colour;
            }

            $footer_text_colour = NULL;
            if (!empty($setting_colour->footer_text_colour)) {
                $footer_text_colour = $setting_colour->footer_text_colour;
            }

            $footer_text_hover_colour = NULL;
            if (!empty($setting_colour->footer_text_hover_colour)) {
                $footer_text_hover_colour = $setting_colour->footer_text_hover_colour;
            }

            $data = array(
                'totalCart' => $totalCart,
                'totalCartMall' => $totalCartMall,
                'userGuardRole' =>$userGuardRole,
                'website_logo' => $web_setting->website_logo,
                'website_name' => $web_setting->website_name,
                'ecommerce_logo' => !empty($web_setting) ? $web_setting->ecommerce_logo : '',
                'web_setting' => $web_setting,
                'permission' => compact('permission'),
                'admin' => $admin,
                'total_pending' => $total_pending,
                'total_member_pending' => $total_member_pending,
                'total_merchant_pending' => $total_merchant_pending,
                'new_guest'=>$new_guest,
                'allPendingTopup'=>count($allPendingTopup),
                'allPendingTrans'=>count($allPendingTrans),
                'allPendingWith'=>count($allPendingWith),
                'totalPendingTrans'=>(count($allPendingTopup) + count($allPendingTrans) + count($allPendingWith)),
                'bank_required'=>$bank_required,
                'lang'=> compact('lang'),
                'backendlang'=>compact('backendlang'),
                'Blang'=> compact('Blang'),
                'productionURL' => $productionURL,
                'currency_code' => $currency_code,
                'getUserDetails' => $getUserDetails,
                'banks' => $banks,
                'loop_start_dates'=>$loop_start_dates,
                'loop_monthly'=>$loop_monthly,
                'explo_check'=>$explo_check,
                'global_bank_holder_name'=>$global_bank_holder_name,
                'global_bank_name'=>$global_bank_name,
                'global_bank_account'=>$global_bank_account,
                'website_setting'=>$website_setting,
                'website_messages'=>$website_messages,
                'categories_home'=>$categories_home,
                'HeaderBuyerCode'=>$HeaderBuyerCode,
                'company_registration_no' => $web_setting->company_registration_no,
                'website_messages'=>$website_messages,
                'setting_header'=>$setting_header,
                'senangpay_setting'=>$senangpay_setting,
                'revpay_setting'=>$revpay_setting,
                'surepay_setting'=>$surepay_setting,
                'gkash_setting'=>$gkash_setting,
                'button_colour'=>$button_colour,
                'text_colour'=>$text_colour,
                'hover_colour'=>$hover_colour,
                'header_announcement_background_colour'=>$header_announcement_background_colour,
                'header_announcement_text_colour'=>$header_announcement_text_colour,
                'header_background_colour'=>$header_background_colour,
                'header_text_colour'=>$header_text_colour,
                'header_text_hover_colour'=>$header_text_hover_colour,
                'footer_trademark_background_colour'=>$footer_trademark_background_colour,
                'footer_trademark_text_colour'=>$footer_trademark_text_colour,
                'footer_background_colour'=>$footer_background_colour,
                'footer_text_colour'=>$footer_text_colour,
                'footer_text_hover_colour'=>$footer_text_hover_colour
            );
            view()->share('data', $data);
        });
    }
}
