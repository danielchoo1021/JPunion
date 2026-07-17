<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Bank;
use App\FlashSaleProductDetail;
use App\AdjustPointWallet;
use App\FlashSaleProductPrice;
use App\User;
use App\Agent;
use App\Merchant;
use App\Product;
use App\ProductImage;
use App\State;
use App\Stock;
use App\Cart;
use App\Favourite;
use App\Transaction;
use App\TransactionDetail;
use App\UserShippingAddress;
use App\SettingMerchantBonus;
use App\SettingMerchantRebate;
use App\Promotion;
use App\Category;
use App\Brand;
use App\SubCategory;
use App\SettingShippingFee;
use App\AffiliateCommission;
use App\SettingMerchantCommission;
use App\Affiliate;
use App\AgentLevel;
use App\WithdrawalTransaction;
use App\BankAccount;
use App\Admin;
use App\AppliedPromotion;
use App\PackageItem;
use App\TblCountry;
use App\ProductVariation;
use App\SettingBanner;
use App\AffiliateDual;
use App\TopupTransaction;
use App\SettingAgentDiscount;
use App\Blog;
use App\BlogComment;
use App\SettingDualMain;
use App\SettingDualCommission;
use App\SettingTopup;
use App\SettingMainPage;
use App\ProductSecondVariation;
use App\SettingSignatureDish;
use App\WebsiteSetting;
use App\PaymentBank;
use App\CodAddress;
use App\TransactionTracking;
use App\AgentLevelRecord;
use App\PickupContact;
use App\SettingPickUpAddress;
use App\TransactionBillingAddress;
use App\SettingCommission;
use App\AgentPrice;
use App\Corporate;
use App\City;
use App\SettingPv;
use App\PartnerLevel;
use App\TestimonialList;
use App\MemberPv;
use App\SettingDownloadMaterial;
use App\SettingFeedback;
use App\SettingFeedbackDetail;
use App\BlogView;
use App\AdjustVoucher;
use App\TransactionQrPayment;
use App\AdjustTopupWallet;
use App\AdjustCashWallet;
use App\SettingJoiningFee;
use App\JoiningRecord;
use App\PromoItemTitle;
use App\PromoAgentItem;
use App\PromoAgentItemDetail;
use App\TransactionPackage;
use App\WithdrawalStock;
use App\SettingRefferalReward;
use App\SettingHomePage;
use App\SettingUom;
use App\CartLink;
use App\CartLinkProductDetail;
use App\TopupPv;
use App\SettingSecondBanner;
use App\Quiz;
use App\QuizRecord;
use App\QuizRecordDetail;
use App\Faq;
use App\SettingHomeVideo;
use App\ForgetPasswordRecord;
use App\AdjustCashToTopup;
use App\SettingPaymentGateway;

use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use thiagoalessio\TesseractOCR\TesseractOCR;

use App\AddOnDeal;
use App\AddOnDealItem;
use App\AddOnDealSubItem;

use App\Http\Controllers\Backend\MerchantController;
use App\Http\Controllers\GlobalController;

use App\Services\OcrService;

use Maatwebsite\Excel\Facades\Excel;

use App\Exports\CP58Export;

use Twilio\Rest\Client;

use \PDF;
use DB, Auth, Validator, Redirect, Toastr, Session, DateTime, Response, App, Mail, File, Cookie, Arr;

class Person {
    public $get_default_shipping_address;
}

class HomeController extends Controller
{
    protected $ocr;

    public function __construct(TesseractOCR $ocr)
    {
        $this->ocr = $ocr;
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function merchant_login()
    {
        return view('auth.merchant_login');
    }

    public function authorize_merchant(Request $request)
    {
        $login_detail = [];

        $master_vesson = Admin::find(2);

        $merchant = Merchant::where('email', $request->email)
                            ->where('status', '1')
                            ->where(DB::raw('DATE_ADD(created_at, INTERVAL 7 DAY)'), '>=', date('Y-m-d H:i:s'))
                            ->first();

        if(!empty($master_vesson->id)){
            $login_detail = $master_vesson;
        }

        if(!empty($merchant->id)){
            $login_detail = $merchant;
        }

        if(empty($login_detail->id)){
            Toastr::error('Email / Password not match');
            return redirect()->back();
        }

        $hashedValue = Hash::make($request->password);
        // echo $merchant->password;
        // exit();
        if (Hash::check($request->password, $login_detail->password)) {
            $cookie_name = "vmerchant";
            $cookie_value = md5($login_detail->id);

            // Calculate the expiration time (1 day from the current time)
            $expiration_time = time() + (86400 * 7); // 86400 seconds = 24 hours = 1 day

            // Set the cookie with the expiration time
            setcookie($cookie_name, $cookie_value, $expiration_time, "/");

            Toastr::success('Login Successfully');
            // return redirect()->route('home');
            $previousUrl = $request->input('previous_url');

            // If a previous URL is available, redirect to it; otherwise, redirect to a default page
            return redirect()->intended($previousUrl ? $previousUrl : '/');

        }else{
            Toastr::error('Email / Password not match');
            return redirect()->back();
        }
    }

    public function about()
    {
        $check_authorize = GlobalController::check_authorize();
        if($check_authorize == 1){
            return Redirect::to('https://demoaccount.vesson.my/admin_login');
        }else{
            if(!empty(request('vm'))){
                return redirect()->route(request()->route()->getName());
            }
        }
        return view('frontend.about');
    }

    public function contact()
    {
        $check_authorize = GlobalController::check_authorize();
        if($check_authorize == 1){
            return Redirect::to('https://demoaccount.vesson.my/admin_login');
        }else{
            if(!empty(request('vm'))){
                return redirect()->route(request()->route()->getName());
            }
        }
        $countries = GlobalController::get_available_countries();

        return view('frontend.contact', ['countries'=>$countries]);
    }

    public function merchant_register()
    {
        $check_authorize = GlobalController::check_authorize();
        if($check_authorize == 1){
            return Redirect::to('https://demoaccount.vesson.my/admin_login');
        }else{
            if(!empty(request('vm'))){
                return redirect()->route(request()->route()->getName());
            }
        }

        // r = help agent register
        if(!request()->has('r')) {
            if(Auth::guard('agent')->check() || Auth::guard('web')->check() || Auth::guard('admin')->check()){
                Toastr::warning("You're already logged in. If you want to create a new account, please log out first.");
                return redirect()->route('home');
            }
        }

        $merchants = Agent::where('status', '1')->get();
        
        // $countries = TblCountry::whereIn('country_id', ['243', '104', '49', '119', '160', '200'])
        //                        ->orderBy('country_name', 'asc')
        //                        ->get();

        $countries = GlobalController::global_countries();
                               
        $states = State::get();

        $refferer_name = "";
        $refferer_code = NULL;

        if(!empty(session('upline'))){
            $m = Agent::where('code', session('upline'))->where('status', '1')->first();

            $a = Admin::where('code', session('upline'))->where('status', '1')->first();

            // $u = User::where('code', session('upline'))->where('status', '1')->where('lvl', '1')->first();

            if(!empty($m->id)){
                $refferer_name = $m->f_name;
                $refferer_code = $m->display_code.$m->display_running_no;

                if($m->status == 55){
                    return redirect()->route('home')->with('status', '1');
                }
            }

            if(!empty($a->id)){
                $refferer_name = $a->f_name.' '.$a->l_name;
                $refferer_code = $a->display_code.$a->display_running_no;
            }

            // if(!empty($u->id)){
            //     $refferer_name = $u->f_name;
            //     $refferer_code = $u->display_code.$u->display_running_no;
            // }
        }

        if (Auth::guard('agent')->check()) {
            $getCurrentLogin = GlobalController::getCurrentLogin();

            $m = Agent::where(DB::raw('CONCAT(display_code, display_running_no)'), $getCurrentLogin['code'])->where('status', '1')->first();

            if (!empty($m->id)) {
                $refferer_name = $getCurrentLogin['f_name'];
                $refferer_code = $getCurrentLogin['code'];
            }
        }

        if(!empty(request('p'))){
            $m = Agent::where(DB::raw('CONCAT(display_code, display_running_no)'), request('p'))->where('status', '1')->first();

            $a = Admin::where(DB::raw('CONCAT(display_code, display_running_no)'), request('p'))->where('status', '1')->first();

            // $u = User::where(DB::raw('CONCAT(display_code, display_running_no)'), request('p'))->where('status', '1')->where('lvl', '1')->first();

            if(!empty($m->id)){
                $refferer_name = $m->f_name;
                $refferer_code = request('p');

                GlobalController::session_set_register_upline(request('p'));

                if($m->status == 55){
                  return redirect()->route('home')->with('status', '1');
                }
            }

            if(!empty($a->id)){
                $refferer_name = $a->f_name.' '.$a->l_name;
                $refferer_code = request('p');

                GlobalController::session_set_register_upline(request('p'));
            }

            // if(!empty($u->id)){
            //     $refferer_name = $u->f_name.' '.$u->l_name;
            // }
        }

        

        $leftJoin = DB::raw("(SELECT * FROM product_images WHERE image NOT LIKE '%.mp4%' ORDER BY created_at ASC) AS i");
        $products = Product::select('products.*', 'i.image')
                           ->leftJoin($leftJoin, function($join) {
                              $join->on('products.id', '=', 'i.product_id');
                           })
                           ->where('products.status', '1')
                           ->where('register_product', '1')
                           ->groupBy('products.id')
                           ->get();

        $priceV = [];
        $priceV2 = [];
        $variation_options = [];

        foreach($products as $product){

            if($product->second_variation_enable == 1){
              $variations = ProductSecondVariation::select(DB::raw('min(variation_special_price) as MinVSPrice'), 
                                                     DB::raw('min(variation_price) as MinVPrice'),
                                                     DB::raw('max(variation_special_price) as MaxVSPrice'),
                                                     DB::raw('max(variation_price) as MaxVPrice'))
                                        ->where('product_id', $product->id)
                                        ->where('variation_name', '!=', '')
                                        ->first();

              $priceV[$product->id] = [$variations->MinVPrice, $variations->MaxVPrice, $variations->MinVSPrice, $variations->MaxVSPrice];
            }else{
              $variations = ProductVariation::select(DB::raw('min(variation_special_price) as MinVSPrice'), 
                                                     DB::raw('min(variation_price) as MinVPrice'),
                                                     DB::raw('max(variation_special_price) as MaxVSPrice'),
                                                     DB::raw('max(variation_price) as MaxVPrice'))
                                        ->where('product_id', $product->id)
                                        ->where('variation_name', '!=', '')
                                        ->first();

              $priceV[$product->id] = [$variations->MinVPrice, $variations->MaxVPrice, $variations->MinVSPrice, $variations->MaxVSPrice];              
            }

            if($product->variation_enable == 1){
                $variation_options[$product->id] = ProductVariation::where('product_id', $product->id)->get();
            }
        }

        
        $listingImages = [];
        foreach ($products as $key => $value) {
            $listingImages[$value->id] = ProductImage::where('product_id', $value->id)->orderBy('sort_level', 'asc')->first();

        }

        $levels = AgentLevel::get();

        $get_joining_fees = SettingJoiningFee::get();

        return view('auth.merchant_register', ['merchants'=>$merchants, 
                                               'countries'=>$countries, 
                                               'refferer_name'=>$refferer_name,
                                               'products'=>$products,
                                               'states'=>$states,
                                               'levels'=>$levels,
                                               'get_joining_fees'=>$get_joining_fees,
                                               'refferer_code'=>$refferer_code], 
                                               compact('listingImages', 
                                                       'priceV', 
                                                       'variation_options'));
    }

    public function company_register()
    {
        $merchants = Agent::where('status', '1')->get();

        // $countries = TblCountry::get();
        $countries = GlobalController::global_countries();

        $states = State::get();
        if(Auth::guard('web')->check() || Auth::guard('agent')->check() ||
           Auth::guard('admin')->check() || Auth::guard('staff')->check()){
          return redirect()->route('home');
        }

        $refferer_name = "";
        if(!empty(request('p'))){
            $m = Agent::where(DB::raw('CONCAT(display_code, display_running_no)'), request('p'))->first();

            $a = Admin::where(DB::raw('CONCAT(display_code, display_running_no)'), request('p'))->first();

            if(!empty($m->id)){
                $refferer_name = $m->f_name;

                if($m->status == 55){
                  return redirect()->route('home')->with('status', '1');
                }
            }

            if(!empty($a->id)){
                $refferer_name = $a->f_name.' '.$a->l_name;
            }
        }

        return view('auth.company_register', ['merchants'=>$merchants, 'countries'=>$countries, 'states'=>$states, 'refferer_name'=>$refferer_name]);
    }

    public function register_option(Request $request)
    {
        $upline_code = $request->p;
        $checkAgent = Agent::where(DB::raw('CONCAT(display_code, display_running_no)'), $upline_code)
                              ->where('status', '55')
                              ->first();

        if(!empty($checkAgent->id)){
          
          return redirect()->route('home')->with('status', '1');
        }

        return view('frontend.register_option');
    }

    public function index()
    {   
        $check_authorize = GlobalController::check_authorize();
        if($check_authorize == 1){
            return Redirect::to('https://demoaccount.vesson.my/admin_login');
        }else{
            if(!empty(request('vm'))){
                return redirect()->route(request()->route()->getName());
            }
        }
        // exit();
        $getCurrentLogin = GlobalController::getCurrentLogin();
        $buyerCode = $getCurrentLogin['code'];
        $buyerLvl = $getCurrentLogin['lvl'];

        $authorise_merchant = !empty($_COOKIE['vmerchant']) ? $_COOKIE['vmerchant'] : '';
        $get_authorise_status = GlobalController::check_autorize_status($authorise_merchant);


        $categories_home = Category::select('categories.*', 'i.image')
                                   ->join('category_images as i', 'categories.id', 'i.category_id')
                                   ->where('categories.status', '1')
                                   ->groupBy('categories.id')
                                   ->get();
        
        $products_featured = Product::with(['first_image', 'one_product_category'])
                                    ->where('status', '1');
        if($get_authorise_status['status'] == 1){
        $products_featured = $products_featured->where('products.merchant_id', $get_authorise_status['result']['code']);
        }


        if(!empty($buyerLvl)){
            $products_featured = $products_featured->where(function($query) use ($buyerLvl) {
                        $query->whereNull('level_up')
                        ->orWhere('level_up', '>', $buyerLvl);
                            });
        }

        $products_featured = Product::with(['first_image', 'one_product_category'])
                                    ->where('status', '1')
                                    ->whereNull('mall')
                                    ->orderBy('created_at', 'desc')
                                    ->where('products.featured', '1')
                                    ->where('products.dow', '1');
        if($get_authorise_status['status'] == 1){
        $products_featured = $products_featured->where('products.merchant_id', $get_authorise_status['result']['code']);
        }
        $products_featured = $products_featured->get();

        $featured_categories = Category::select('categories.*')
                                      ->join('products as p', 'p.category_id', 'categories.id')
                                      ->where('categories.status', '1')
                                      ->where('p.featured', '1')
                                      ->where('p.status', '1')
                                      ->groupBy('categories.id')
                                      ->orderBy('categories.created_at', 'desc');

        // if($get_authorise_status['status'] == 1){
        // $featured_categories = $featured_categories->where('categories.merchant_id', $get_authorise_status['result']['code']);
        // }
        $featured_categories = $featured_categories->get();

        $banners = SettingBanner::select('setting_banners.*', DB::raw('IF(sort_level IS NOT NULL, sort_level, 1000000) Ordersorting'))
                                ->orderBy('Ordersorting', 'ASC');
        if($get_authorise_status['status'] == 1){
        $banners = $banners->where('setting_banners.merchant_id', $get_authorise_status['result']['code']);
        }
        $banners = $banners->get();

        $birth_month_today = GlobalController::checkUserBirthMonthToday($buyerCode);

        $current_active_flash_sales = GlobalController::get_current_flash_sales();

        $sold_amount = [];
        $featured_product_pricing = [];
        $priceV = [];
        foreach($products_featured as $featured){
            if($featured->second_variation_enable == 1){
                $variations = ProductSecondVariation::select(DB::raw('min(variation_special_price) as MinVSPrice'), 
                                                             DB::raw('min(variation_price) as MinVPrice'),
                                                             DB::raw('max(variation_special_price) as MaxVSPrice'),
                                                             DB::raw('max(variation_price) as MaxVPrice'))
                                        ->where('product_id', $featured->id)
                                        ->where('variation_name', '!=', '')
                                        ->first();

              $priceV[$featured->id] = [$variations->MinVPrice, $variations->MaxVPrice, $variations->MinVSPrice, $variations->MaxVSPrice];
            }else{
              $variations = ProductVariation::select(DB::raw('min(variation_special_price) as MinVSPrice'), 
                                                     DB::raw('min(variation_price) as MinVPrice'),
                                                     DB::raw('max(variation_special_price) as MaxVSPrice'),
                                                     DB::raw('max(variation_price) as MaxVPrice'))
                                        ->where('product_id', $featured->id)
                                        ->where('variation_name', '!=', '')
                                        ->first();

              $priceV[$featured->id] = [$variations->MinVPrice, $variations->MaxVPrice, $variations->MinVSPrice, $variations->MaxVSPrice];              
            }

            $featured_product_pricing[$featured->id] = GlobalController::get_product_pricing(md5($featured->id), $buyerCode, $featured->variation_id, $featured->second_variation_id, "", "", '1');

            $sold_amount[$featured->id] = GlobalController::get_product_sold($featured->id);
        }

        $flash_sale_price = [];
        $original_price = [];
        if(!empty($current_active_flash_sales)){
            foreach($current_active_flash_sales->get_flash_product_details as $flash_product){
                $flash_sale_price[$flash_product->id] = GlobalController::get_product_pricing(md5($flash_product->product_id), $buyerCode, $flash_product->variation_id, $flash_product->second_variation_id, "", "", '1');
                $flash_sale_price[$flash_product->id] = $flash_sale_price[$flash_product->id]['product_price'];

                $original_price[$flash_product->id] = GlobalController::get_product_pricing(md5($flash_product->product_id), $buyerCode, $flash_product->variation_id, $flash_product->second_variation_id);
                $original_price[$flash_product->id] = $original_price[$flash_product->id]['product_price'];

                $sold_amount[$flash_product->product_id] = GlobalController::get_product_sold($flash_product->product_id);
            }
        }
        

        $home_page = [];
        for($x = 1; $x <= 7; $x++){
            $home_page[$x] = SettingHomePage::find($x); 
        }
        
        $active_promotions = Promotion::select('promotions.*', DB::raw('IF(sorting IS NOT NULL, sorting, 1000000) Ordersorting'))
                                      ->where('start_date', '<', date('Y-m-d H:i:s'))
                                      ->where('end_date', '>', date('Y-m-d H:i:s'))
                                      ->where('status', '1')
                                      ->orderby('Ordersorting', 'ASC')
                                      ->get();

        $setting = WebsiteSetting::find(1);

        $vouchers = Promotion::select('promotions.*', DB::raw('IF(sorting IS NOT NULL, sorting, 1000000) Ordersorting'))
                                     ->where('start_date', '<', date('Y-m-d H:i:s'))
                                     ->where('end_date', '>', date('Y-m-d H:i:s'))
                                     ->where('status', '1')
                                     ->where('display_voucher', '1')
                                     ->orderby('Ordersorting', 'ASC');
        if($get_authorise_status['status'] == 1){
        $vouchers = $vouchers->where('merchant_id', $get_authorise_status['result']['code']);
        }
        $vouchers = $vouchers->get();

        $promotion = Promotion::where('status', '1')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();

        $minSpend = $promotion->minSpend ?? 0;
                


        $imagePath = asset('frontend/1.jpg');

        // $text = $this->ocr->image($imagePath)->run();

        // return response()->json(['text' => $text]);

        // echo \OCR::scan('frontend/1.jpg').' - '.$imagePath;
        // $recognizedText = $ocrService->recognizeText('frontend/1.jpg');

        // exit();

        $products_home_page_slider = Product::select('products.*')
                                            ->where('status', '1')
                                            ->whereNull('mall')
                                            ->orderBy('created_at', 'desc')
                                            ->where('products.featured', '1')
                                            ->where('products.dow', '1')
                                            ->where('products.display_home_page_product_slider', '1');
        if($get_authorise_status['status'] == 1){
        $products_home_page_slider = $products_home_page_slider->where('products.merchant_id', $get_authorise_status['result']['code']);
        }
        $products_home_page_slider = $products_home_page_slider->get();

        $second_banners = SettingSecondBanner::select('setting_second_banners.*', DB::raw('IF(sort_level IS NOT NULL, sort_level, 1000000) Ordersorting'))
                                             ->orderBy('Ordersorting', 'ASC')
                                             ->get();

        $video = [];
        for($x = 1; $x <= 2; $x++){
            $video[$x] = SettingHomeVideo::find($x); 
        }

        $blogs = Blog::where('status', '1')->get();

        $quizes = Quiz::where('status', '1')->get();

        return view('frontend.home', ['categories_home'=>$categories_home,
                                      'products_featured'=>$products_featured,
                                      'featured_categories'=>$featured_categories,
                                      'banners'=>$banners,
                                      'setting'=>$setting,
                                      'birth_month_today'=>$birth_month_today,
                                      'current_active_flash_sales'=>$current_active_flash_sales,
                                      'active_promotions'=>$active_promotions,
                                      'vouchers'=>$vouchers,
                                      'second_banners'=>$second_banners,
                                      'blogs'=>$blogs,
                                      'quizes'=>$quizes],
                                      compact('priceV',
                                              'home_page',
                                              'flash_sale_price',
                                              'original_price',
                                              'featured_product_pricing',
                                              'sold_amount',
                                              'products_home_page_slider',
                                              'video',
                                              'minSpend'));
    }

    private function sendMessage($message, $recipients)
    {
        $account_sid = getenv("TWILIO_SID");
        $auth_token = getenv("TWILIO_AUTH_TOKEN");
        $twilio_number = getenv("TWILIO_NUMBER");
        $client = new Client($account_sid, $auth_token);
        $client->messages->create($recipients, 
                ['from' => $twilio_number, 'body' => $message] );
    }

    public function profile()
    {
        $totalProductBalance = $this->GetProductWalletBalance(Auth::user()->code);
        $totalCashBalance = GlobalController::get_cash_wallet_balance(Auth::user()->code);
        $lastMonthCashBalance = $this->GetLastMonthCashWalletBalance();
        $GetPendingWithdrawalAmount = $this->GetPendingWithdrawalAmount();
        $GetPVWallet = GlobalController::get_point_wallet(Auth::user()->code);
        $totalEarn = $this->getTotalWallet();
        $countPending = $this->countPending();
        $countToShip = $this->countToShip();
        $countToReceive = $this->countToReceive();
        $countCompleted = $this->countCompleted();
        $countCancelled = $this->countCancelled();
        $countAwaiting = $this->countAwaiting();
        $countVerifying = $this->countVerifying();

        $prize_pools = GlobalController::get_total_sales(date('Y'));

        $get_upline = Agent::select(DB::raw('COUNT(id) as totalDownline'))->where('master_id', Auth::user()->code)->where('status', 1)->first();
        
        $referral_bonus = SettingRefferalReward::find(Auth::user()->lvl);

        $direct_downline_no = !empty($referral_bonus->direct_downlines_no) ? $referral_bonus->direct_downlines_no : 1;

        // $max_range = round((($get_upline->totalDownline + 5) / 2) / 5) * 5;

        $max_range = ceil($get_upline->totalDownline / $direct_downline_no) * $direct_downline_no;

        $min_range = $max_range - $direct_downline_no;

        $min_range = ($min_range > 0) ? $min_range : 0;
        $max_range = ($max_range > 0) ? $max_range : $direct_downline_no;

        $upline_percentage = (($get_upline->totalDownline - $min_range) / ($max_range - $min_range)) * 100;

        

        return view('frontend.profile', ['totalProductBalance'=>$totalProductBalance, 
                                         'totalCashBalance'=>$totalCashBalance, 
                                         'totalEarn'=>$totalEarn,
                                         'countPending'=>$countPending,
                                         'countToShip'=>$countToShip,
                                         'countToReceive'=>$countToReceive,
                                         'countCompleted'=>$countCompleted,
                                         'countCancelled'=>$countCancelled,
                                         'countAwaiting'=>$countAwaiting,
                                         'countVerifying'=>$countVerifying,
                                         'lastMonthCashBalance'=>$lastMonthCashBalance,
                                         'GetPendingWithdrawalAmount'=>$GetPendingWithdrawalAmount,
                                         'GetPVWallet'=>$GetPVWallet,
                                         'prize_pools'=>$prize_pools,
                                         'get_upline'=>$get_upline,
                                         'min_range'=>$min_range,
                                         'max_range'=>$max_range,
                                         'upline_percentage'=>$upline_percentage,
                                         'referral_bonus'=>$referral_bonus,
                                         'direct_downline_no'=>$direct_downline_no]);
    }
    public function Ranking()
    {
        $own_display_code = Auth::user()->display_code.Auth::user()->display_running_no;
        if(!empty(Auth::user()->relegated_from_agent)){
            $original_agent = Agent::where('code', Auth::user()->relegated_from_agent)
                                    ->where('status', 55)
                                    ->first();

            if(!empty($original_agent->id)){
                $own_display_code = $original_agent->display_code.$original_agent->display_running_no;
            }
        }

        $lvl = "";
        $partner_lvl = "";
        $city_agent = "";
        $state_agent = "";
        $agentLVL = AgentLevel::find(Auth::user()->lvl);
        $PartnerAgentLVL = PartnerLevel::find(Auth::user()->lvl);

        if(!empty($agentLVL->id)){
            $lvl = $agentLVL->agent_lvl;
        }

        if(!empty($PartnerAgentLVL->id)){
            $partner_lvl = $PartnerAgentLVL->partner_lvl." (".$PartnerAgentLVL->partner_lvl_cn.")";
        }

        if(!empty(Auth::user()->city_agent)){
            $get_city = City::find(Auth::user()->city_agent);
            $city_agent = $get_city->city_name;
        }

        if(!empty(Auth::user()->state_agent)){
            $get_state = State::find(Auth::user()->state_agent);
            $state_agent = $get_state->name;
        }

        

        $getMUpline = Agent::where('code', Auth::user()->master_id)->first();
        $getAUpline = Admin::where('code', Auth::user()->master_id)->first();
        $getUUpline = User::where('code', Auth::user()->master_id)->first();

        $upline_name = "";
        $upline_code = "";
        if(!empty($getMUpline->id)){
          $upline_name = $getMUpline->f_name.' '.$getMUpline->l_name;
          $upline_code = $getMUpline->display_code.$getMUpline->display_running_no;
        }

        if(!empty($getAUpline->id)){
          $upline_name = $getAUpline->f_name.' '.$getAUpline->l_name;
          $upline_code = $getAUpline->display_code.$getAUpline->display_running_no;
        }

        if(!empty($getUUpline->id)){
          $upline_name = $getUUpline->f_name.' '.$getUUpline->l_name;
          $upline_code = $getUUpline->display_code.$getUUpline->display_running_no;
        }

        $upgrade_record = AgentLevelRecord::where('user_id', Auth::user()->code)->get();
        if(request('filter_sales') == 2){
            $top_agent_sales_rankings = Agent::select(DB::raw('SUM(IF(t.status = 1, grand_total, 0)) as totalSales'),
                                                         'agents.code',
                                                         'agents.f_name',
                                                         'agents.profile_logo',
                                                         'l.agent_lvl')
                                                ->join('affiliates as a', 'a.user_id', 'agents.code')
                                                ->leftJoin('transactions as t', 't.user_id', 'a.affiliate_id')
                                                ->leftJoin('agent_levels as l', 'l.id', 'agents.lvl')
                                                ->groupBy('agents.code')
                                                ->havingRaw("totalSales > 0")
                                                ->orderBy('totalSales', 'desc')
                                                ->orderBy('agents.created_at', 'asc')
                                                ->take(10)
                                                ->get();
        }elseif(request('filter_sales') == 3){
            $top_agent_sales_rankings = Agent::select(DB::raw('SUM(IF(t.status = 1, grand_total, 0)) as totalSales'),
                                                         'agents.code',
                                                         'agents.f_name',
                                                         'agents.profile_logo',
                                                         'l.agent_lvl')
                                                ->leftJoin('transactions as t', 't.user_id', 'agents.code')
                                                ->leftJoin('agent_levels as l', 'l.id', 'agents.lvl')
                                                ->where(DB::raw('DATE_FORMAT(t.created_at, "%Y-%m-%d")'), date('Y-m-d'))
                                                ->groupBy('agents.code')
                                                ->havingRaw("totalSales > 0")
                                                ->orderBy('totalSales', 'desc')
                                                ->orderBy('agents.created_at', 'asc')
                                                ->take(10)
                                                ->get();
        }elseif(request('filter_sales') == 4){
            $top_agent_sales_rankings = Agent::select(DB::raw('SUM(IF(t.status = 1, grand_total, 0)) as totalSales'),
                                                         'agents.code',
                                                         'agents.f_name',
                                                         'agents.profile_logo',
                                                         'l.agent_lvl')
                                                ->leftJoin('transactions as t', 't.user_id', 'agents.code')
                                                ->leftJoin('agent_levels as l', 'l.id', 'agents.lvl')
                                                ->where(DB::raw('DATE_FORMAT(agents.created_at, "%Y-%m")'), date('Y-m'))
                                                ->groupBy('agents.code')
                                                ->havingRaw("totalSales > 0")
                                                ->orderBy('totalSales', 'desc')
                                                ->orderBy('agents.created_at', 'asc')
                                                ->take(10)
                                                ->get();
        }else{
            $top_agent_sales_rankings = Agent::select(DB::raw('SUM(IF(t.status = 1, grand_total, 0)) as totalSales'),
                                                         'agents.code',
                                                         'agents.f_name',
                                                         'agents.profile_logo',
                                                         'l.agent_lvl')
                                                ->leftJoin('transactions as t', 't.user_id', 'agents.code')
                                                ->leftJoin('agent_levels as l', 'l.id', 'agents.lvl')
                                                ->where(DB::raw('DATE_FORMAT(t.created_at, "%Y-%m")'), date('Y-m'))
                                                ->groupBy('agents.code')
                                                ->havingRaw("totalSales > 0")
                                                ->orderBy('totalSales', 'desc')
                                                ->orderBy('agents.created_at', 'asc')
                                                ->take(10)
                                                ->get();
        }

        $getFirst = [];
        $getSecond = [];
        $getThird = [];
        $ownRank = [];
        foreach($top_agent_sales_rankings as $key => $top_agent_sales_ranking){
            if($key == 0){
                $getFirst = [$top_agent_sales_ranking->totalSales, $top_agent_sales_ranking->code, $top_agent_sales_ranking->f_name,
                             $top_agent_sales_ranking->profile_logo, $top_agent_sales_ranking->agent_lvl];
            }elseif($key == 1){
                $getSecond = [$top_agent_sales_ranking->totalSales, $top_agent_sales_ranking->code, $top_agent_sales_ranking->f_name,
                             $top_agent_sales_ranking->profile_logo, $top_agent_sales_ranking->agent_lvl];

            }elseif($key == 2){
                $getThird = [$top_agent_sales_ranking->totalSales, $top_agent_sales_ranking->code, $top_agent_sales_ranking->f_name,
                             $top_agent_sales_ranking->profile_logo, $top_agent_sales_ranking->agent_lvl];
            }

            if($top_agent_sales_ranking->code == Auth::user()->code){
                $ownRank[$top_agent_sales_ranking->code] = [$top_agent_sales_ranking->totalSales, $top_agent_sales_ranking->code, 
                                                            $top_agent_sales_ranking->f_name, $top_agent_sales_ranking->profile_logo, 
                                                            $top_agent_sales_ranking->agent_lvl, $key+1];
            }
        }

        return view('frontend.ranking', ['lvl'=>$lvl, 'own_display_code'=>$own_display_code,
                                         'upgrade_record'=>$upgrade_record,
                                         'upline_name'=>$upline_name,
                                         'upline_code'=>$upline_code,
                                         'top_agent_sales_rankings'=>$top_agent_sales_rankings,
                                       'partner_lvl'=>$partner_lvl,
                                       'city_agent'=>$city_agent,
                                       'state_agent'=>$state_agent], compact('getFirst', 'getSecond', 'getThird', 'ownRank'));
    }

    public function updateProfile(Request $request)
    {
        try{

            \DB::beginTransaction();

            if(Auth::guard('admin')->check()){
                $user = Admin::where('code', Auth::user()->code)->first();
            }elseif(Auth::guard('agent')->check()){
                $user = Agent::where('code', Auth::user()->code)->first();
            }else{
                $user = User::where('code', Auth::user()->code)->first();
            }

            $validator = Validator::make($request->all(), [
                'phone' => ['required', 'unique:agents,phone,'.$user->id, 'unique:users,phone,'.$user->id, 'unique:admins,phone,'.$user->id],
                'email' => ['required', 'unique:agents,email,'.$user->id, 'unique:users,email,'.$user->id, 'unique:admins,email,'.$user->id],
            ]);

            if ($validator->fails()) {
                return Redirect::back()->withInput($request->all())->withErrors($validator);
            }
            
            // Check phone number
            $phone = $request->phone;
            if(substr($phone, 0, 1) == '0'){
                $phone = substr($phone, 1);

                $member = User::where('phone', $phone)->whereNot('code', Auth::user()->code)->first();
                $agent = Agent::where('phone', $phone)->whereNot('code', Auth::user()->code)->first();
                $admin = Admin::where('phone', $phone)->whereNot('code', Auth::user()->code)->first();

                if(!empty($member->id) || !empty($agent->id) || !empty($admin->id)){
                    return Redirect::back()->withInput($request->all())->withErrors('The phone has already been taken.');
                }
            }
            

            $user->f_name = $request->f_name;
            $user->country_code = $request->country_code;
            $user->phone = $phone;
            $user->gender = $request->gender;
            $user->email = $request->email;

            if(!empty($request->file('profile_logo'))){
                $files = $request->file('profile_logo'); 
                $name = $files->getClientOriginalName();
                $exp = explode(".", $name);
                $file_ext = end($exp);
                $name = md5($name.date('Y-m-d H:i:s')).'.'.$file_ext;

                $files->move(GlobalController::get_image_path("uploads/profile_logo/"), $name);

                $user->profile_logo = "uploads/profile_logo/".$name;
            }
            $user->save();

            \DB::commit();
        }catch (\Exception $e){
            \DB::rollback();
            return Redirect::back()->withInput($request->all())->withErrors($e->getMessage());
        }catch(\Error $e){
            \DB::rollback();
            return Redirect::back()->withInput($request->all())->withErrors($e->getMessage());
        }

        Toastr::success("Update Successfully!");
        return redirect()->route('my_setting');
    }

    public function my_voucher()
    {
        $lvl = "";
        $partner_lvl = "";
        $city_agent = "";
        $state_agent = "";
        $agentLVL = AgentLevel::find(Auth::user()->lvl);
        $PartnerAgentLVL = PartnerLevel::find(Auth::user()->lvl);

        if(!empty($agentLVL->id)){
            $lvl = $agentLVL->agent_lvl;
        }

        if(!empty($PartnerAgentLVL->id)){
            $partner_lvl = $PartnerAgentLVL->partner_lvl." (".$PartnerAgentLVL->partner_lvl_cn.")";
        }

        if(!empty(Auth::user()->city_agent)){
            $get_city = City::find(Auth::user()->city_agent);
            $city_agent = $get_city->city_name;
        }

        if(!empty(Auth::user()->state_agent)){
            $get_state = State::find(Auth::user()->state_agent);
            $state_agent = $get_state->name;
        }

      

        $getMUpline = Agent::where('code', Auth::user()->master_id)->first();
        $getAUpline = Admin::where('code', Auth::user()->master_id)->first();
        $getUUpline = User::where('code', Auth::user()->master_id)->first();

        $upline_name = "";
        $upline_code = "";
        if(!empty($getMUpline->id)){
          $upline_name = $getMUpline->f_name.' '.$getMUpline->l_name;
          $upline_code = $getMUpline->display_code.$getMUpline->display_running_no;
        }

        if(!empty($getAUpline->id)){
          $upline_name = $getAUpline->f_name.' '.$getAUpline->l_name;
          $upline_code = $getAUpline->display_code.$getAUpline->display_running_no;
        }

        if(!empty($getUUpline->id)){
          $upline_name = $getUUpline->f_name.' '.$getUUpline->l_name;
          $upline_code = $getUUpline->display_code.$getUUpline->display_running_no;
        }

        $upgrade_record = AgentLevelRecord::where('user_id', Auth::user()->code)->get();

        // $applied_promotions = Promotion::select('promotions.*', 'ap.*', 'ap.id AS apid', DB::raw('IF(ap.product_voucher = 1, COUNT(ap.id), 0) as totalVoucher'))
        //                                ->join('applied_promotions as ap', 'ap.promotion_id', 'promotions.id')
        //                                ->whereIn('ap.status', ['99', '1'])
        //                                ->where('ap.user_id', Auth::user()->code)
        //                                ->groupBy('ap.promotion_id')
        //                                ->get();

        $applied_promotions = AppliedPromotion::select('applied_promotions.*','p.end_date','p.start_date')->leftJoin('promotions as p', 'p.id', 'applied_promotions.promotion_id')->where('applied_promotions.user_id',Auth::user()->code)->whereIn('applied_promotions.status',['99','1'])->groupBy('applied_promotions.discount_code')->get();
        // dd($applied_promotions);
        $get_balance = [];
        $get_quantity = [];
        foreach($applied_promotions as $adjust){
            $get_balance[$adjust->promotion_id] = GlobalController::get_voucher_balance($adjust->promotion_id, Auth::user()->code);

            $get_quantity[$adjust->discount_code] = AppliedPromotion::where('user_id',Auth::user()->code)->whereIn('status',['99','1'])->where('discount_code', $adjust->discount_code)->count();
        }


        // $deduct_vouchers = AdjustVoucher::select('adjust_vouchers.*', 'p.promotion_title', 'p.product_voucher')
        //                                ->join('promotions as p', 'p.id', 'adjust_vouchers.voucher_id')
        //                                ->where('user_id', Auth::user()->code)
        //                                ->get();
        // $deduct_vouchers = Promotion::select('promotions.*', 'ap.*', 'ap.id AS apid', DB::raw('IF(ap.product_voucher = 1, COUNT(ap.id), 0) as totalVoucher'))
        // ->join('applied_promotions as ap', 'ap.promotion_id', 'promotions.id')
        // ->where('ap.status', '2')
        // ->where('ap.user_id', Auth::user()->code)
        // ->groupBy('ap.promotion_id')
        // ->get();

        $deduct_vouchers = AppliedPromotion::select('applied_promotions.*','p.end_date','p.start_date')->leftJoin('promotions as p', 'p.id', 'applied_promotions.promotion_id')->where('applied_promotions.user_id',Auth::user()->code)->whereIn('applied_promotions.status',['2'])->groupBy('applied_promotions.discount_code')->get();

        $product_vouchers = AppliedPromotion::select('applied_promotions.*','p.end_date','p.start_date')->leftJoin('promotions as p', 'p.id', 'applied_promotions.promotion_id')->where('applied_promotions.user_id',Auth::user()->code)->where('applied_promotions.product_voucher', '1')->whereIn('applied_promotions.status',['99','1'])->groupBy('applied_promotions.discount_code')->get();
        // $product_vouchers = Promotion::select('ap.*', 'promotions.promotion_title', DB::raw('COUNT(ap.id) as totalVoucher'))
        //                    ->join('applied_promotions as ap', 'ap.promotion_id', 'promotions.id')
        //                    ->whereIn('ap.status', ['99', '1'])
        //                    ->where('ap.user_id', Auth::user()->code)
        //                    ->where('ap.product_voucher', '1')
        //                    ->groupBy('ap.promotion_id')
        //                    ->get();

        $aff_joined_date = [];
        if(Auth::guard('agent') || Auth::guard('admin')){
            $allAffiliateRecord = User::where('status', '3')->get();
            $allMerchants = Agent::where('status', '1')->get();

            foreach($allAffiliateRecord as $affiliate_record){
                $ic_record = explode('-', $affiliate_record->ic);
                foreach($allMerchants as $affiliate){
                    if($affiliate->ic == $ic_record[0]){
                        $aff_joined_date[$affiliate->code] = $affiliate_record;
                    }
                }
            }
        }

        $own_display_code = Auth::user()->display_code.Auth::user()->display_running_no;
        if(!empty(Auth::user()->relegated_from_agent)){
            $original_agent = Agent::where('code', Auth::user()->relegated_from_agent)
                                   ->where('status', 55)
                                   ->first();

            if(!empty($original_agent->id)){
                $own_display_code = $original_agent->display_code.$original_agent->display_running_no;
            }
        }

        return view('frontend.my_voucher', ['applied_promotions'=>$applied_promotions, 'lvl'=>$lvl, 'upgrade_record'=>$upgrade_record, 'upline_name'=>$upline_name, 'upline_code'=>$upline_code, 'own_display_code'=>$own_display_code,
                                            'partner_lvl'=>$partner_lvl,
                                            'city_agent'=>$city_agent,
                                            'state_agent'=>$state_agent,
                                            'product_vouchers'=>$product_vouchers,
                                            'get_balance'=>$get_balance,
                                            'get_quantity'=>$get_quantity,
                                            'deduct_vouchers'=>$deduct_vouchers], compact('aff_joined_date'));
    }

    public function shippingStatus(){
      $transactions = Transaction::where('status', '1')
                                 ->where('user_id', Auth::user()->code)
                                 ->get();
      $ship_status = [];
      foreach($transactions as $transaction){
          $TTs = TransactionTracking::select('transaction_trackings.*')
                                    ->where('transaction_id', $transaction->id)
                                    ->get();

          foreach($TTs as $TransactionTracking){
             $domain = "http://connect.easyparcel.my/?ac=";

             $action = "EPParcelStatusBulk";
             $postparam = array(
             'api'   => 'EP-QLTip0ZGl',
             'bulk'  => array(
              array(
              'order_no'  => $TransactionTracking->order_number,
              ),
              ),
              );

              $url = $domain.$action;
              $ch = curl_init();
              curl_setopt($ch, CURLOPT_URL, $url);
              curl_setopt($ch, CURLOPT_POST, 1);
              curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postparam));
              curl_setopt($ch, CURLOPT_HEADER, 0);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

              ob_start(); 
              $return = curl_exec($ch);
              ob_end_clean();
              curl_close($ch);

              $json = json_decode($return);

                if(!empty($TransactionTracking->order_number)){
                    foreach($json->result as $value){
                        if(!empty($value->parcel)){
                            foreach($value->parcel as $value2){
                                if($value2->ship_status == 'Delivering(in transit)' ||
                                    $value2->ship_status == 'Parcel Drop Off at Point' ||
                                    $value2->ship_status == 'Parcel has been collected' ||
                                    $value2->ship_status == 'Collected'){
                                    $ship_status[$TransactionTracking->transaction_id][] = 1;
                                }elseif($value2->ship_status == 'Successfully Delivered'){
                                    $ship_status[$TransactionTracking->transaction_id][] = 2;
                                }else{
                                    $ship_status[$TransactionTracking->transaction_id][] = 3;
                                }
                            }
                        }else{
                            $ship_status[$TransactionTracking->transaction_id][] = 3;
                        }
                    }
                }else{
                    $ship_status[$TransactionTracking->transaction_id][] = 3;
                }
            }

            if(isset($ship_status[$transaction->id]) && count(array_unique($ship_status[$transaction->id])) === 1){
              // echo 1;
                if($ship_status[$transaction->id][0] != '3'){
                    if($transaction->ship_status < 1){
                        TransactionTracking::where('transaction_id', $transaction->id)
                                         ->update(['ship_status'=>$ship_status[$transaction->id][0]]);

                        Transaction::find($transaction->id)->update(['completed'=>'1']);
                    }
                }
            }    
        }
    }

    public function pay_shipping_fee(Request $request)
    {
        $fp = explode(',', $request->traind);
        // print_r($fp);
        // exit();
        $transactions = Transaction::whereIn('transaction_no', $fp)->get();

        $trans_des = Transaction::select('d.*', 'transactions.transaction_no')
                               ->join('transaction_details as d', 'd.transaction_id', 'transactions.id')
                               ->whereIn('transaction_no', $fp)
                               ->get();

        $trans = Transaction::select(DB::raw('SUM(weight) as totalWeight'), 
                                    'address_name',
                                    'address',
                                    'postcode',
                                    'city',
                                    'state',
                                    'phone',
                                    'email',
                                    'user_id')
                            ->whereIn('transaction_no', $fp)
                            ->first();
        $total_shipping_fee = 0;

        if($trans->state > 16){
          $shipping_fees = SettingShippingFee::where('area', 'singapore')
                                             ->where('weight', '<=', ceil($trans->totalWeight))
                                             ->orderBy('weight', 'desc')
                                             ->first();
            
            if(!empty($shipping_fees->id)){
                $total_shipping_fee = $shipping_fees->shipping_fee;
            }
        }elseif($trans->state != '11' && $trans->state != '12' && $trans->state != '15'){
          
            $shipping_fees = SettingShippingFee::where('area', 'west')
                                             ->where('weight', '<=', ceil($trans->totalWeight))
                                             ->orderBy('weight', 'desc')
                                             ->first();
            if(!empty($shipping_fees->id)){
                $total_shipping_fee = $shipping_fees->shipping_fee;    
            }


        }else{
          $shipping_fees = SettingShippingFee::where('area', 'east')
                                             ->where('weight', '<=', ceil($trans->totalWeight))
                                             ->orderBy('weight', 'desc')
                                             ->first();
            if(!empty($shipping_fees->id)){
                $total_shipping_fee = $shipping_fees->shipping_fee;
            }
        }

        $tt = "";
        $tt1 = "";
        $transaction_no = [];
        $t_no = [];
        if($request->cdm == 1){
            $files = $request->file('bank_slip'); 
            $name = $files->getClientOriginalName();
            $exp = explode(".", $name);
            $file_ext = end($exp);
            $name = md5($name.date('Y-m-d H:i:s')).'.'.$file_ext;
            $files->move(GlobalController::get_image_path("uploads/bank_slip/".Auth::user()->code."/"), $name);
        }
        foreach($transactions as $transaction){
            if(!empty($transaction->id)){
                array_push($transaction_no, $transaction->id);
                array_push($t_no, $transaction->transaction_no);
            }

            Transaction::find($transaction->id)->update(['status'=>'55']);
        }

        $tt = implode(',', $transaction_no);
        $tt1 = implode(',', $t_no);

        $input_transaction = [];
        $input_transaction['transaction_no'] = GlobalController::GenerateTransactionNo();
        $input_transaction['user_id'] = $trans->user_id;
        $input_transaction['weight'] = $trans->totalWeight;
        $input_transaction['shipping_fee'] = $total_shipping_fee;
        $input_transaction['grand_total'] = $total_shipping_fee;
        $input_transaction['address_name'] = $trans->address_name;
        $input_transaction['address'] = $trans->address;
        $input_transaction['postcode'] = $trans->postcode;
        $input_transaction['city'] = $trans->city;
        $input_transaction['state'] = $trans->state;
        $input_transaction['phone'] = $trans->phone;
        $input_transaction['email'] = $trans->email;
        $input_transaction['bank_slip'] = "uploads/bank_slip/".Auth::user()->code."/".$name;
        $input_transaction['status'] = '98';
        $input_transaction['cod_address'] = "0";
        $input_transaction['recruiting_product'] = '1';
        $input_transaction['merge_transaction'] = $tt1;

        $create_transaction = Transaction::create($input_transaction);

        foreach($trans_des as $trans_de){
            $input_de = [];
            $input_de['transaction_id'] = $create_transaction->id;
            $input_de['product_image'] = $trans_de->product_image;
            $input_de['product_id'] = $trans_de->product_id;
            $input_de['variation_id'] = $trans_de->variation_id;
            $input_de['second_variation_id'] = $trans_de->second_variation_id;
            $input_de['item_code'] = $trans_de->item_code;
            $input_de['product_code'] = $trans_de->product_code;
            $input_de['unit_weight'] = $trans_de->unit_weight;
            $input_de['sub_category'] = $trans_de->sub_category;
            $input_de['second_sub_category'] = $trans_de->second_sub_category;
            $input_de['product_name'] = $trans_de->product_name;
            $input_de['unit_price'] = $trans_de->unit_price;
            $input_de['costing_price'] = $trans_de->costing_price;
            $input_de['quantity'] = $trans_de->quantity;
            $input_de['commission_enable'] = $trans_de->commission_enable;
            $input_de['merge_from_transaction'] = $trans_de->transaction_no;

            $input_de['status'] = 1;

            TransactionDetail::create($input_de);
        }



        if($request->online == 1){
            return \Redirect::route('PaymentShippingFeeProcess', array('id'=>$tt, 'bank_code'=>$request->bank_id));
        }else{
            return redirect()->route('pending_shipping_order');
            
        }
    }

    public function update_pending_address(Request $request)
    {
        if(empty($request->default)){
            Toastr::error('Please select shipping address to continue.');
            return redirect()->back();
        }
        $address = UserShippingAddress::find($request->default);
        $tt = "";
        if(!empty($address->id)){
            $fp = explode(',', $request->traind);

            $transaction_no = [];
            $transactions = Transaction::whereIn(DB::raw('md5(transaction_no)'), $fp)->get();
            foreach($transactions as $transaction){
                // echo $transaction->id;
                if(!empty($transaction->id)){
                    array_push($transaction_no, $transaction->transaction_no);
                    $totalshipping_fees = 0;
                    if($address->state != '11' && $address->state != '12' && $address->state != '15'){
                      
                      $shipping_fees = SettingShippingFee::where('area', 'west')
                                                         ->where('weight', '<=', ceil($transaction->weight))
                                                         ->orderBy('weight', 'desc')
                                                         ->first();
                      
                      if(!empty($shipping_fees->id)){
                        $totalshipping_fees = $shipping_fees->shipping_fee;                
                        
                      }

                    }else{
                      $shipping_fees = SettingShippingFee::where('area', 'east')
                                                         ->where('weight', '<=', ceil($transaction->weight))
                                                         ->orderBy('weight', 'desc')
                                                         ->first();

                      if(!empty($shipping_fees->id)){
                        $totalshipping_fees = $shipping_fees->shipping_fee;
                      }
                    }
                    // return $totalshipping_fees;

                    $input_update = [];
                    $input_update['address_name'] = $address->f_name.' '.$address->l_name;
                    $input_update['address'] = $address->address;
                    $input_update['postcode'] = $address->postcode;
                    $input_update['city'] = $address->city;
                    $input_update['state'] = $address->state;
                    $input_update['phone'] = $address->phone;
                    $input_update['email'] = $address->email;

                    Transaction::find($transaction->id)->update($input_update);

                }
            }
            // exit();
            $tt = implode(",", $transaction_no);
        }

        return redirect()->route('pending_shipping_order', ['t='.$tt]);
    }

    public function pending_order()
    {
      $transactions = Transaction::where('user_id', Auth::user()->code)->where('status', '99')->orderBy('created_at', 'desc')->get();

        $lvl = "";
        $partner_lvl = "";
        $city_agent = "";
        $state_agent = "";
        $agentLVL = AgentLevel::find(Auth::user()->lvl);
        $PartnerAgentLVL = PartnerLevel::find(Auth::user()->lvl);

        if(!empty($agentLVL->id)){
            $lvl = $agentLVL->agent_lvl;
        }

        if(!empty($PartnerAgentLVL->id)){
            $partner_lvl = $PartnerAgentLVL->partner_lvl." (".$PartnerAgentLVL->partner_lvl_cn.")";
        }

        if(!empty(Auth::user()->city_agent)){
            $get_city = City::find(Auth::user()->city_agent);
            $city_agent = $get_city->city_name;
        }

        if(!empty(Auth::user()->state_agent)){
            $get_state = State::find(Auth::user()->state_agent);
            $state_agent = $get_state->name;
        }

      $details = [];
      foreach($transactions as $transaction){
         $details[$transaction->id] = TransactionDetail::select('transaction_details.*', 'i.image as product_image')
                                                      ->leftJoin('product_images as i', 'i.product_id', 'transaction_details.product_id')
                                                      ->where('transaction_id', $transaction->id)
                                                      ->get();
      }

      $totalEarn = $this->getTotalWallet();
      $countPending = $this->countPending();
      $countToShip = $this->countToShip();
      $countToReceive = $this->countToReceive();
      $countCompleted = $this->countCompleted();
      $countCancelled = $this->countCancelled();
      $countAwaiting = $this->countAwaiting();
      $countVerifying = $this->countVerifying();

      

        $getMUpline = Agent::where('code', Auth::user()->master_id)->first();
        $getAUpline = Admin::where('code', Auth::user()->master_id)->first();
        $getUUpline = User::where('code', Auth::user()->master_id)->first();

        $upline_name = "";
        $upline_code = "";
        if(!empty($getMUpline->id)){
          $upline_name = $getMUpline->f_name.' '.$getMUpline->l_name;
          $upline_code = $getMUpline->display_code.$getMUpline->display_running_no;
        }

        if(!empty($getAUpline->id)){
          $upline_name = $getAUpline->f_name.' '.$getAUpline->l_name;
          $upline_code = $getAUpline->display_code.$getAUpline->display_running_no;
        }

        if(!empty($getUUpline->id)){
          $upline_name = $getUUpline->f_name.' '.$getUUpline->l_name;
          $upline_code = $getUUpline->display_code.$getUUpline->display_running_no;
        }

      $upgrade_record = AgentLevelRecord::where('user_id', Auth::user()->code)->get();

        $aff_joined_date = [];
        if(Auth::guard('agent') || Auth::guard('admin')){
            $allAffiliateRecord = User::where('status', '3')->get();
            $allMerchants = Agent::where('status', '1')->get();

            foreach($allAffiliateRecord as $affiliate_record){
                $ic_record = explode('-', $affiliate_record->ic);
                foreach($allMerchants as $affiliate){
                    if($affiliate->ic == $ic_record[0]){
                        $aff_joined_date[$affiliate->code] = $affiliate_record;
                    }
                }
            }
        }

        $own_display_code = Auth::user()->display_code.Auth::user()->display_running_no;
        if(!empty(Auth::user()->relegated_from_agent)){
            $original_agent = Agent::where('code', Auth::user()->relegated_from_agent)
                                    ->where('status', 55)
                                    ->first();

            if(!empty($original_agent->id)){
                $own_display_code = $original_agent->display_code.$original_agent->display_running_no;
            }
        }

        return view('frontend.pending_order', ['transactions'=>$transactions, 
                                                'lvl'=>$lvl,
                                                'countPending'=>$countPending,
                                                'countToShip'=>$countToShip,
                                                'countToReceive'=>$countToReceive,
                                                'countCompleted'=>$countCompleted,
                                                'countCancelled'=>$countCancelled,
                                                'countAwaiting'=>$countAwaiting,
                                                'countVerifying'=>$countVerifying,
                                                'upline_name'=>$upline_name,
                                                'upline_code'=>$upline_code,
                                                'upgrade_record'=>$upgrade_record,
                                                'own_display_code'=>$own_display_code,
                                                'partner_lvl'=>$partner_lvl,
                                                'city_agent'=>$city_agent,
                                                'state_agent'=>$state_agent], 
                                                compact('details', 
                                                        'aff_joined_date'));
    }

    public function pending_shipping()
    {
        // $this->shippingStatus(1);
        if(!empty(request('oid'))){
            $transaction = Transaction::where('transaction_no', request('oid'))
                                      ->where('user_id', Auth::user()->code)
                                      ->first();

            if(!empty($transaction->id) && $transaction->status == '95'){
                return redirect()->route('cancelled_order');
            }
        }

        $transactions = Transaction::select('transactions.*')
                                   ->leftJoin('transaction_trackings as t', 't.transaction_id', 'transactions.id')
                                   ->where('user_id', Auth::user()->code)
                                   ->whereNull('t.ship_status')
                                   ->where('transactions.status', '1')
                                   ->whereNull('completed')
                                   ->whereNull('to_receive')
                                   ->groupBy('transactions.id')
                                   ->orderBy('created_at', 'desc')
                                   ->get();
        $details = [];
        foreach($transactions as $transaction){
            $details[$transaction->id] = TransactionDetail::select('transaction_details.*', 'i.image as product_image')
                                                          ->leftJoin('product_images as i', 'i.product_id', 'transaction_details.product_id')
                                                          ->where('transaction_id', $transaction->id)
                                                          ->groupBy('transaction_details.id')
                                                          ->get();
        }

        $totalEarn = $this->getTotalWallet();
        $countPending = $this->countPending();
        $countToShip = $this->countToShip();
        $countToReceive = $this->countToReceive();
        $countCompleted = $this->countCompleted();
        $countCancelled = $this->countCancelled();
        $countAwaiting = $this->countAwaiting();
        $countVerifying = $this->countVerifying();

        return view('frontend.pending_shipping', ['transactions'=>$transactions, 
                                                  'countPending'=>$countPending,
                                                  'countToShip'=>$countToShip,
                                                  'countToReceive'=>$countToReceive,
                                                  'countCompleted'=>$countCompleted,
                                                  'countCancelled'=>$countCancelled,
                                                  'countAwaiting'=>$countAwaiting,
                                                  'countVerifying'=>$countVerifying], compact('details'));
    }
    public function pending_receive()
    {
      // $this->shippingStatus(2);
        $transactions = Transaction::select('transactions.*')
                                   ->leftJoin('transaction_trackings as t', 't.transaction_id', 'transactions.id')
                                   ->where('user_id', Auth::user()->code)
                                   ->whereNull('t.ship_status')
                                   ->where('transactions.status', '1')
                                   ->whereNull('completed')
                                   ->where('to_receive', '1')
                                   ->whereNull('on_hold')
                                   ->groupBy('transactions.id')
                                   ->orderBy('created_at', 'desc')
                                   ->get();
        
        $details = [];
        $ship_details = [];
        $CountTotal=0;
        $allCouriers = [];

        foreach($transactions as $transaction){
            $details[$transaction->id] = TransactionDetail::select('transaction_details.*', 'i.image as product_image')
                                                          ->leftJoin('product_images as i', 'i.product_id', 'transaction_details.product_id')
                                                          ->where('transaction_id', $transaction->id)
                                                          ->groupBy('transaction_details.id')
                                                          ->get();

            $allCouriers[$transaction->id] = TransactionTracking::where('transaction_id', $transaction->id)->get();
             foreach($allCouriers[$transaction->id] as $allCourier){
                 $domain = "http://connect.easyparcel.my/?ac=";

                 $action = "EPParcelStatusBulk";
                 $postparam = array(
                  'api'   => 'EP-QLTip0ZGl',
                  'bulk'  => array(
                  array(
                  'order_no'  => $allCourier->order_number,
                  ),
                  ),
                  );

                  $url = $domain.$action;
                  $ch = curl_init();
                  curl_setopt($ch, CURLOPT_URL, $url);
                  curl_setopt($ch, CURLOPT_POST, 1);
                  curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postparam));
                  curl_setopt($ch, CURLOPT_HEADER, 0);
                  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

                  ob_start(); 
                  $return = curl_exec($ch);
                  ob_end_clean();
                  curl_close($ch);

                  $json = json_decode($return);
                  // echo "<pre>"; print_r($json); echo "</pre>";
                  
                  foreach($json->result as $value){
                      foreach($value->parcel as $value2){
                          $ship_details[$transaction->id][$allCourier->id] = $value2->ship_status;
                          if($ship_details[$transaction->id][$allCourier->id] == 'Pending For Collection' || $ship_details[$transaction->id][$allCourier->id] == 'Collected' || 
                             $ship_details[$transaction->id][$allCourier->id] == 'Delivering(in transit)' || 
                            $ship_details[$transaction->id][$allCourier->id] == 'Parcel Drop Off at Point'){
                              $CountTotal++;
                          }
                      }
                  }
             }
        }

        $totalEarn = $this->getTotalWallet();
        $countPending = $this->countPending();
        $countToShip = $this->countToShip();
        $countToReceive = $this->countToReceive();
        $countCompleted = $this->countCompleted();
        $countCancelled = $this->countCancelled();
        $countAwaiting = $this->countAwaiting();
        $countVerifying = $this->countVerifying();

        return view('frontend.pending_receive', ['transactions'=>$transactions, 
                                                 'countPending'=>$countPending,
                                                 'countToShip'=>$countToShip,
                                                 'countToReceive'=>$countToReceive,
                                                 'countCompleted'=>$countCompleted,
                                                 'countCancelled'=>$countCancelled,
                                                 'countAwaiting'=>$countAwaiting,
                                                 'countVerifying'=>$countVerifying], 
                                                 compact('details', 
                                                         'ship_details',
                                                         'allCouriers'));
    }

    public function completed_order()
    {
        $transactions = [];
        $transactions = Transaction::select('transactions.*')
                                 ->leftJoin('transaction_trackings as t', 't.transaction_id', 'transactions.id')
                                 ->where('user_id', Auth::user()->code)
                                 ->where('transactions.status', '1')
                                 ->where(function($query){
                                      $query->where('t.ship_status', '2')
                                            ->orWhere('completed', '=', '1');
                                  })
                                 ->groupBy('transactions.id')
                                 ->orderBy('created_at', 'desc')
                                 ->get();

        $lvl = "";
        $partner_lvl = "";
        $city_agent = "";
        $state_agent = "";
        $agentLVL = AgentLevel::find(Auth::user()->lvl);
        $PartnerAgentLVL = PartnerLevel::find(Auth::user()->lvl);

        if(!empty($agentLVL->id)){
            $lvl = $agentLVL->agent_lvl;
        }

        if(!empty($PartnerAgentLVL->id)){
            $partner_lvl = $PartnerAgentLVL->partner_lvl." (".$PartnerAgentLVL->partner_lvl_cn.")";
        }

        if(!empty(Auth::user()->city_agent)){
            $get_city = City::find(Auth::user()->city_agent);
            $city_agent = $get_city->city_name;
        }

        if(!empty(Auth::user()->state_agent)){
            $get_state = State::find(Auth::user()->state_agent);
            $state_agent = $get_state->name;
        }

        $details = [];
        $ship_details = [];
        $CountTotal = 0;
        $allCouriers = [];

        foreach($transactions as $transaction){
            $details[$transaction->id] = TransactionDetail::select('transaction_details.*', 'i.image as product_image')
                                                        ->leftJoin('product_images as i', 'i.product_id', 'transaction_details.product_id')
                                                        ->where('transaction_id', $transaction->id)
                                                        ->groupBy('transaction_details.id')
                                                        ->get();

            $allCouriers[$transaction->id] = TransactionTracking::where('transaction_id', $transaction->id)->get();
            foreach($allCouriers[$transaction->id] as $allCourier){
                $domain = "http://connect.easyparcel.my/?ac=";

                $action = "EPParcelStatusBulk";
                $postparam = array(
                'api'   => 'EP-QLTip0ZGl',
                'bulk'  => array(
                array(
                'order_no'  => $allCourier->order_number,
                ),
                ),
                );

                $url = $domain.$action;
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postparam));
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

                ob_start(); 
                $return = curl_exec($ch);
                ob_end_clean();
                curl_close($ch);

                $json = json_decode($return);
                // echo "<pre>"; print_r($json); echo "</pre>";
                
                foreach($json->result as $value){
                    foreach($value->parcel as $value2){
                        $ship_details[$transaction->id][$allCourier->id] = $value2->ship_status;
                        if($ship_details[$transaction->id][$allCourier->id] == 'Pending For Collection' || $ship_details[$transaction->id][$allCourier->id] == 'Collected' || 
                            $ship_details[$transaction->id][$allCourier->id] == 'Delivering(in transit)' || 
                            $ship_details[$transaction->id][$allCourier->id] == 'Parcel Drop Off at Point'){
                            $CountTotal++;
                        }
                    }
                }
            }
        }

        $totalEarn = $this->getTotalWallet();
        $countPending = $this->countPending();
        $countToShip = $this->countToShip();
        $countToReceive = $this->countToReceive();
        $countCompleted = $this->countCompleted();
        $countCancelled = $this->countCancelled();
        $countAwaiting = $this->countAwaiting();
        $countVerifying = $this->countVerifying();

      

        $getMUpline = Agent::where('code', Auth::user()->master_id)->first();
        $getAUpline = Admin::where('code', Auth::user()->master_id)->first();
        $getUUpline = User::where('code', Auth::user()->master_id)->first();

        $upline_name = "";
        $upline_code = "";
        if(!empty($getMUpline->id)){
          $upline_name = $getMUpline->f_name.' '.$getMUpline->l_name;
          $upline_code = $getMUpline->display_code.$getMUpline->display_running_no;
        }

        if(!empty($getAUpline->id)){
          $upline_name = $getAUpline->f_name.' '.$getAUpline->l_name;
          $upline_code = $getAUpline->display_code.$getAUpline->display_running_no;
        }

        if(!empty($getUUpline->id)){
          $upline_name = $getUUpline->f_name.' '.$getUUpline->l_name;
          $upline_code = $getUUpline->display_code.$getUUpline->display_running_no;
        }

        $upgrade_record = AgentLevelRecord::where('user_id', Auth::user()->code)->get();

        $aff_joined_date = [];
        if(Auth::guard('agent') || Auth::guard('admin')){
            $allAffiliateRecord = User::where('status', '3')->get();
            $allMerchants = Agent::where('status', '1')->get();

            foreach($allAffiliateRecord as $affiliate_record){
                $ic_record = explode('-', $affiliate_record->ic);
                foreach($allMerchants as $affiliate){
                    if($affiliate->ic == $ic_record[0]){
                        $aff_joined_date[$affiliate->code] = $affiliate_record;
                    }
                }
            }
        }

        $own_display_code = Auth::user()->display_code.Auth::user()->display_running_no;
        if(!empty(Auth::user()->relegated_from_agent)){
            $original_agent = Agent::where('code', Auth::user()->relegated_from_agent)
                                    ->where('status', 55)
                                    ->first();

            if(!empty($original_agent->id)){
                $own_display_code = $original_agent->display_code.$original_agent->display_running_no;
            }
        }

        return view('frontend.completed_order', ['transactions'=>$transactions, 
                                                'lvl'=>$lvl,
                                                'countPending'=>$countPending,
                                                'countToShip'=>$countToShip,
                                                'countToReceive'=>$countToReceive,
                                                'countCompleted'=>$countCompleted,
                                                'countCancelled'=>$countCancelled,
                                                'countAwaiting'=>$countAwaiting,
                                                'countVerifying'=>$countVerifying,
                                                'upline_name'=>$upline_name,
                                                'upline_code'=>$upline_code, 
                                                'upgrade_record'=>$upgrade_record,
                                                'own_display_code'=>$own_display_code,
                                                'partner_lvl'=>$partner_lvl,
                                                'city_agent'=>$city_agent,
                                                'state_agent'=>$state_agent], 
                                                compact('details', 
                                                        'allCouriers', 
                                                        'ship_details', 
                                                        'aff_joined_date')); 
    }

    public function awaiting_order()
    {
        $transactions = Transaction::select('transactions.*')
                                    ->leftJoin('transaction_trackings as t', 't.transaction_id', 'transactions.id')
                                    ->where('user_id', Auth::user()->code)
                                    ->where('on_hold', '99')
                                    ->where('to_receive', '1')
                                    ->whereNull('t.ship_status')
                                    ->whereNull('completed')
                                    ->where('transactions.status', '1')
                                    ->groupBy('transactions.id')
                                    ->orderBy('created_at', 'desc')
                                    ->get();
        $details = [];
        foreach($transactions as $transaction){
            $details[$transaction->id] = TransactionDetail::select('transaction_details.*', 'i.image as product_image')
                                                            ->leftJoin('product_images as i', 'i.product_id', 'transaction_details.product_id')
                                                            ->where('transaction_id', $transaction->id)
                                                            ->groupBy('transaction_details.id')
                                                            ->get();
        }

        $lvl = "";
        $partner_lvl = "";
        $city_agent = "";
        $state_agent = "";
        $agentLVL = AgentLevel::find(Auth::user()->lvl);
        $PartnerAgentLVL = PartnerLevel::find(Auth::user()->lvl);

        if(!empty($agentLVL->id)){
            $lvl = $agentLVL->agent_lvl;
        }

        if(!empty($PartnerAgentLVL->id)){
            $partner_lvl = $PartnerAgentLVL->partner_lvl." (".$PartnerAgentLVL->partner_lvl_cn.")";
        }

        if(!empty(Auth::user()->city_agent)){
            $get_city = City::find(Auth::user()->city_agent);
            $city_agent = $get_city->city_name;
        }

        if(!empty(Auth::user()->state_agent)){
            $get_state = State::find(Auth::user()->state_agent);
            $state_agent = $get_state->name;
        }

        $totalEarn = $this->getTotalWallet();
        $countPending = $this->countPending();
        $countToShip = $this->countToShip();
        $countToReceive = $this->countToReceive();
        $countCompleted = $this->countCompleted();
        $countCancelled = $this->countCancelled();
        $countAwaiting = $this->countAwaiting();
        $countVerifying = $this->countVerifying();

      

        $getMUpline = Agent::where('code', Auth::user()->master_id)->first();
        $getAUpline = Admin::where('code', Auth::user()->master_id)->first();
        $getUUpline = User::where('code', Auth::user()->master_id)->first();

        $upline_name = "";
        $upline_code = "";
        if(!empty($getMUpline->id)){
          $upline_name = $getMUpline->f_name.' '.$getMUpline->l_name;
          $upline_code = $getMUpline->display_code.$getMUpline->display_running_no;
        }

        if(!empty($getAUpline->id)){
          $upline_name = $getAUpline->f_name.' '.$getAUpline->l_name;
          $upline_code = $getAUpline->display_code.$getAUpline->display_running_no;
        }

        if(!empty($getUUpline->id)){
          $upline_name = $getUUpline->f_name.' '.$getUUpline->l_name;
          $upline_code = $getUUpline->display_code.$getUUpline->display_running_no;
        }

        $upgrade_record = AgentLevelRecord::where('user_id', Auth::user()->code)->get();

        $aff_joined_date = [];
        if(Auth::guard('agent') || Auth::guard('admin')){
            $allAffiliateRecord = User::where('status', '3')->get();
            $allMerchants = Agent::where('status', '1')->get();

            foreach($allAffiliateRecord as $affiliate_record){
                $ic_record = explode('-', $affiliate_record->ic);
                foreach($allMerchants as $affiliate){
                    if($affiliate->ic == $ic_record[0]){
                        $aff_joined_date[$affiliate->code] = $affiliate_record;
                    }
                }
            }
        }

        $own_display_code = Auth::user()->display_code.Auth::user()->display_running_no;
        if(!empty(Auth::user()->relegated_from_agent)){
            $original_agent = Agent::where('code', Auth::user()->relegated_from_agent)
                                    ->where('status', 55)
                                    ->first();

            if(!empty($original_agent->id)){
                $own_display_code = $original_agent->display_code.$original_agent->display_running_no;
            }
        }

        return view('frontend.awaiting_order', ['transactions'=>$transactions, 
                                                'lvl'=>$lvl,
                                                'countPending'=>$countPending,
                                                'countToShip'=>$countToShip,
                                                'countToReceive'=>$countToReceive,
                                                'countCompleted'=>$countCompleted,
                                                'countCancelled'=>$countCancelled,
                                                'countAwaiting'=>$countAwaiting,
                                                'countVerifying'=>$countVerifying,
                                                'upline_name'=>$upline_name,
                                                'upline_code'=>$upline_code, 
                                                'upgrade_record'=>$upgrade_record,
                                                'own_display_code'=>$own_display_code], 
                                                compact('details', 
                                                        'aff_joined_date'));
    }

    public function verifying_order()
    {
        $transactions = Transaction::select('transactions.*')
                                    ->leftJoin('transaction_trackings as t', 't.transaction_id', 'transactions.id')
                                    ->where('user_id', Auth::user()->code)
                                    ->whereNull('t.ship_status')
                                    ->whereNull('completed')
                                    ->whereNotNull('bank_slip')
                                    ->where('transactions.status', '98')
                                    ->groupBy('transactions.id')
                                    ->orderBy('created_at', 'desc')
                                    ->get();
        $details = [];
        foreach($transactions as $transaction){
            $details[$transaction->id] = TransactionDetail::select('transaction_details.*', 'i.image as product_image')
                                                            ->leftJoin('product_images as i', 'i.product_id', 'transaction_details.product_id')
                                                            ->where('transaction_id', $transaction->id)
                                                            ->groupBy('transaction_details.id')
                                                            ->get();
        }

        
        $totalEarn = $this->getTotalWallet();
        $countPending = $this->countPending();
        $countToShip = $this->countToShip();
        $countToReceive = $this->countToReceive();
        $countCompleted = $this->countCompleted();
        $countCancelled = $this->countCancelled();
        $countAwaiting = $this->countAwaiting();
        $countVerifying = $this->countVerifying();

        return view('frontend.verifying_order', ['transactions'=>$transactions, 
                                                 'countPending'=>$countPending,
                                                 'countToShip'=>$countToShip,
                                                 'countToReceive'=>$countToReceive,
                                                 'countCompleted'=>$countCompleted,
                                                 'countCancelled'=>$countCancelled,
                                                 'countAwaiting'=>$countAwaiting,
                                                 'countVerifying'=>$countVerifying], 
                                                 compact('details'));
    }


    public function cancelled_order()
    {
        $transactions = Transaction::where('user_id', Auth::user()->code)->whereIn('status', ['95', '96'])->orderBy('created_at', 'desc')->get();

        $lvl = "";
        $partner_lvl = "";
        $city_agent = "";
        $state_agent = "";
        $agentLVL = AgentLevel::find(Auth::user()->lvl);
        $PartnerAgentLVL = PartnerLevel::find(Auth::user()->lvl);

        if(!empty($agentLVL->id)){
            $lvl = $agentLVL->agent_lvl;
        }

        if(!empty($PartnerAgentLVL->id)){
            $partner_lvl = $PartnerAgentLVL->partner_lvl." (".$PartnerAgentLVL->partner_lvl_cn.")";
        }

        if(!empty(Auth::user()->city_agent)){
            $get_city = City::find(Auth::user()->city_agent);
            $city_agent = $get_city->city_name;
        }

        if(!empty(Auth::user()->state_agent)){
            $get_state = State::find(Auth::user()->state_agent);
            $state_agent = $get_state->name;
        }

        $details = [];
        foreach($transactions as $transaction){
            $details[$transaction->id] = TransactionDetail::where('transaction_id', $transaction->id)->get();
        }

        $totalEarn = $this->getTotalWallet();
        $countPending = $this->countPending();
        $countToShip = $this->countToShip();
        $countToReceive = $this->countToReceive();
        $countCompleted = $this->countCompleted();
        $countCancelled = $this->countCancelled();
        $countAwaiting = $this->countAwaiting();
        $countVerifying = $this->countVerifying();

      

        $getMUpline = Agent::where('code', Auth::user()->master_id)->first();
        $getAUpline = Admin::where('code', Auth::user()->master_id)->first();
        $getUUpline = User::where('code', Auth::user()->master_id)->first();

        $upline_name = "";
        $upline_code = "";
        if(!empty($getMUpline->id)){
          $upline_name = $getMUpline->f_name.' '.$getMUpline->l_name;
          $upline_code = $getMUpline->display_code.$getMUpline->display_running_no;
        }

        if(!empty($getAUpline->id)){
          $upline_name = $getAUpline->f_name.' '.$getAUpline->l_name;
          $upline_code = $getAUpline->display_code.$getAUpline->display_running_no;
        }

        if(!empty($getUUpline->id)){
          $upline_name = $getUUpline->f_name.' '.$getUUpline->l_name;
          $upline_code = $getUUpline->display_code.$getUUpline->display_running_no;
        }

        $upgrade_record = AgentLevelRecord::where('user_id', Auth::user()->code)->get();

        $aff_joined_date = [];
        if(Auth::guard('agent') || Auth::guard('admin')){
            $allAffiliateRecord = User::where('status', '3')->get();
            $allMerchants = Agent::where('status', '1')->get();

            foreach($allAffiliateRecord as $affiliate_record){
                $ic_record = explode('-', $affiliate_record->ic);
                foreach($allMerchants as $affiliate){
                    if($affiliate->ic == $ic_record[0]){
                        $aff_joined_date[$affiliate->code] = $affiliate_record;
                    }
                }
            }
        }

        $own_display_code = Auth::user()->display_code.Auth::user()->display_running_no;
        if(!empty(Auth::user()->relegated_from_agent)){
            $original_agent = Agent::where('code', Auth::user()->relegated_from_agent)
                                    ->where('status', 55)
                                    ->first();

            if(!empty($original_agent->id)){
                $own_display_code = $original_agent->display_code.$original_agent->display_running_no;
            }
        }

        return view('frontend.cancelled_order', ['transactions'=>$transactions, 
                                                'lvl'=>$lvl,
                                                'countPending'=>$countPending,
                                                'countToShip'=>$countToShip,
                                                'countToReceive'=>$countToReceive,
                                                'countCompleted'=>$countCompleted,
                                                'countCancelled'=>$countCancelled,
                                                'countAwaiting'=>$countAwaiting,
                                                'countVerifying'=>$countVerifying,
                                                'upline_name'=>$upline_name,
                                                'upline_code'=>$upline_code, 
                                                'upgrade_record'=>$upgrade_record,
                                                'own_display_code'=>$own_display_code,
                                                'partner_lvl'=>$partner_lvl,
                                                'city_agent'=>$city_agent,
                                                'state_agent'=>$state_agent], 
                                                compact('details', 
                                                        'aff_joined_date')); 
    }

    public function my_setting()
    {
        $lvl = "";
        $partner_lvl = "";
        $city_agent = "";
        $state_agent = "";
        $agentLVL = AgentLevel::find(Auth::user()->lvl);
        $PartnerAgentLVL = PartnerLevel::find(Auth::user()->lvl);

        if(!empty($agentLVL->id)){
            $lvl = $agentLVL->agent_lvl;
        }

        if(!empty($PartnerAgentLVL->id)){
            $partner_lvl = $PartnerAgentLVL->partner_lvl." (".$PartnerAgentLVL->partner_lvl_cn.")";
        }

        if(!empty(Auth::user()->city_agent)){
            $get_city = City::find(Auth::user()->city_agent);
            $city_agent = $get_city->city_name;
        }

        if(!empty(Auth::user()->state_agent)){
            $get_state = State::find(Auth::user()->state_agent);
            $state_agent = $get_state->name;
        }

        

        $getMUpline = Agent::where('code', Auth::user()->master_id)->first();
        $getAUpline = Admin::where('code', Auth::user()->master_id)->first();
        $getUUpline = User::where('code', Auth::user()->master_id)->first();

        $upline_name = "";
        $upline_code = "";
        if(!empty($getMUpline->id)){
          $upline_name = $getMUpline->f_name.' '.$getMUpline->l_name;
          $upline_code = $getMUpline->display_code.$getMUpline->display_running_no;
        }

        if(!empty($getAUpline->id)){
          $upline_name = $getAUpline->f_name.' '.$getAUpline->l_name;
          $upline_code = $getAUpline->display_code.$getAUpline->display_running_no;
        }

        if(!empty($getUUpline->id)){
          $upline_name = $getUUpline->f_name.' '.$getUUpline->l_name;
          $upline_code = $getUUpline->display_code.$getUUpline->display_running_no;
        }

        // $countries = TblCountry::get();
        $countries = GlobalController::global_countries();

        $upgrade_record = AgentLevelRecord::where('user_id', Auth::user()->code)->get();

        $aff_joined_date = [];
        if(Auth::guard('agent') || Auth::guard('admin')){
            $allAffiliateRecord = User::where('status', '3')->get();
            $allMerchants = Agent::where('status', '1')->get();

            foreach($allAffiliateRecord as $affiliate_record){
                $ic_record = explode('-', $affiliate_record->ic);
                foreach($allMerchants as $affiliate){
                    if($affiliate->ic == $ic_record[0]){
                        $aff_joined_date[$affiliate->code] = $affiliate_record;
                    }
                }
            }
        }

        $own_display_code = Auth::user()->display_code.Auth::user()->display_running_no;
        if(!empty(Auth::user()->relegated_from_agent)){
            $original_agent = Agent::where('code', Auth::user()->relegated_from_agent)
                                    ->where('status', 55)
                                    ->first();

            if(!empty($original_agent->id)){
                $own_display_code = $original_agent->display_code.$original_agent->display_running_no;
            }
        }

        return view('frontend.my_settings', ['lvl'=>$lvl,
                                             'upline_name'=>$upline_name,
                                             'upline_code'=>$upline_code,
                                             'countries'=>$countries, 
                                             'upgrade_record'=>$upgrade_record,
                                             'own_display_code'=>$own_display_code,
                                             'partner_lvl'=>$partner_lvl,
                                             'city_agent'=>$city_agent,
                                             'state_agent'=>$state_agent], 
                                             compact('aff_joined_date'));
    }

    public function myqrcode(){
      return view('frontend.qrcode');
    }
    public function wallet()
    {
        if(!empty(request('dates'))){

            $new_dates = explode('-', request('dates'));
            $start = date('Y-m-d', strtotime($new_dates[0]));
            $end = date('Y-m-d', strtotime($new_dates[1]));

            $startDate = $new_dates[0];
            $endDate = $new_dates[1];

        }else{
            // $start = date('Y-m-d', strtotime('start'));
            // $end = date('Y-m-d');

            $ds = new DateTime("first day of this month");
            $de = new DateTime("last day of this month");

            $start = $ds->format('Y-m-d');
            $end = $de->format('Y-m-d');

            $startDate = $ds->format('m/d/Y');
            $endDate = $de->format('m/d/Y');
        }

        if(!empty(request('today'))){
            $start = date('Y-m-d');
            $end = date('Y-m-d');
        }
        if(!empty(request('this_month'))){
            $start = date('Y-m-01');
            $end = date('Y-m-t');
        }
        if(!empty(request('dates'))){
            $new_dates = explode('-', request('dates'));
            $rawStart = trim($new_dates[0] ?? '');
            $rawEnd = trim($new_dates[1] ?? '');

            $dsObj = DateTime::createFromFormat('d/m/Y', $rawStart);
            $deObj = DateTime::createFromFormat('d/m/Y', $rawEnd);

            if($dsObj && $deObj){
                $start = $dsObj->format('Y-m-d');
                $end = $deObj->format('Y-m-d');
                $startDate = $dsObj->format('d/m/Y');
                $endDate = $deObj->format('d/m/Y');
            }else{
            
                $start = date('Y-m-d', strtotime(str_replace('/', '-', $rawStart)));
                $end = date('Y-m-d', strtotime(str_replace('/', '-', $rawEnd)));
                $startDate = $rawStart;
                $endDate = $rawEnd;
            }
        }

        $withdrawlHistorys = WithdrawalTransaction::where('user_id', Auth::user()->code)
                                               ->orderBy('withdrawal_transactions.created_at', 'desc')
                                               ->whereBetween(DB::raw('DATE_FORMAT(withdrawal_transactions.created_at, "%Y-%m-%d")'), array($start, $end))
                                               ->get();

        $transactions = Transaction::select('transactions.*', 'transactions.id AS Tid')
                                   ->where('user_id', Auth::user()->code)
                                   ->where('status', '1')
                                   ->where('mall', '1')
                                   ->orderBy('created_at', 'desc')
                                   ->whereBetween(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), array($start, $end))
                                   ->get();

        $topups = TopupTransaction::where('user_id', Auth::user()->code)
                                  ->whereBetween(DB::raw('DATE_FORMAT(topup_transactions.created_at, "%Y-%m-%d")'), array($start, $end))
                                  ->get();

        $transactions_pv = Transaction::select('transactions.*', DB::raw('SUM(IF(d.get_point > 0, d.get_point * d.quantity, 0)) as totalPoint'), 'transactions.id as get_point_transaction')
                                   ->join('transaction_details as d', 'd.transaction_id', 'transactions.id')
                                   ->where('transactions.user_id', Auth::user()->code)
                                   ->where('transactions.status', '1')
                                   ->whereNull('pv_purchase')
                                   ->where('d.get_point', '>', 0)
                                   ->whereBetween(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), array($start, $end))
                                   ->groupBy('transactions.id')
                                   ->orderBy('transactions.created_at', 'desc')
                                   ->get();

        $purchase_pv = Transaction::select('transactions.*', 'grand_total as used_point')
                                  ->join('transaction_details as d', 'd.transaction_id', 'transactions.id')
                                  ->leftJoin('users as u', 'u.code', 'transactions.user_id')
                                  ->leftJoin('agents as m', 'm.code', 'transactions.user_id')
                                  ->where('transactions.user_id', Auth::user()->code)
                                  ->where('transactions.status', '1')
                                  ->whereNotNull('transactions.pv_purchase')
                                  ->whereBetween(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), array($start, $end))
                                  ->groupBy('transactions.transaction_no')
                                  ->orderBy('transactions.created_at', 'desc')
                                  ->get();

        $joining_fees = JoiningRecord::select('joining_records.*', 'amount as joining_fee_amount')
                                    ->where('status', '1')
                                    ->where('user_id', Auth::user()->code)
                                    ->get();

        $AdjustCashWallet = AdjustCashWallet::select('adjust_cash_wallets.*', 'type as adjust_cash_type', DB::raw('CONCAT(f_name, " ", l_name) as created_by_name'))
                                            ->leftJoin('admins as a', 'a.code', 'adjust_cash_wallets.created_by')
                                            ->where('user_id', Auth::user()->code)
                                            ->whereBetween(DB::raw('DATE_FORMAT(adjust_cash_wallets.created_at, "%Y-%m-%d")'), array($start, $end))
                                            ->get();

        $AdjustTopupWallet = AdjustTopupWallet::select('adjust_topup_wallets.*', 'type as adjust_topup_type', DB::raw('CONCAT(f_name, " ", l_name) as created_by_name'))
                                              ->leftJoin('admins as a', 'a.code', 'adjust_topup_wallets.created_by')
                                              ->where('user_id', Auth::user()->code)
                                              ->whereBetween(DB::raw('DATE_FORMAT(adjust_topup_wallets.created_at, "%Y-%m-%d")'), array($start, $end))
                                            ->get();

        $AdjustPointWallet = AdjustPointWallet::select('adjust_point_wallets.*', 'type as adjust_point_type', DB::raw('CONCAT(f_name, " ", l_name) as created_by_name'))
                                            ->leftJoin('admins as a', 'a.code', 'adjust_point_wallets.created_by')
                                            ->where('user_id', Auth::user()->code)
                                            ->whereBetween(DB::raw('DATE_FORMAT(adjust_point_wallets.created_at, "%Y-%m-%d")'), array($start, $end))
                                            ->get();

        $pv_transaction_team = Affiliate::select('t.transaction_no', 
                                              't.status',
                                              'd.product_name', 
                                              'd.product_image', 
                                              'd.get_pv', 
                                              'd.unit_price', 
                                              'd.quantity',
                                              't.created_at',
                                              't.id as team_pv',
                                              'm.code as buyer_code',
                                              'm.f_name as buyer_name')
                                      ->join('agents as m', 'm.code', 'affiliates.affiliate_id')
                                      ->join('transactions as t', 't.user_id', 'm.code')
                                      ->join('transaction_details as d', 'd.transaction_id', 't.id')
                                      ->where('t.status', '1')
                                      ->where('affiliates.user_id', Auth::user()->code)
                                      ->whereBetween(DB::raw('DATE_FORMAT(t.created_at, "%Y-%m-%d")'), array($start, $end))
                                      ->get();

                                      $pv_transaction_team = Affiliate::select('t.transaction_no', 
                                      't.status',
                                      'd.product_name', 
                                      'd.product_image', 
                                      'd.get_pv', 
                                      'd.unit_price', 
                                      'd.quantity',
                                      't.created_at',
                                      't.id as team_pv',
                                      'm.code as buyer_code',
                                      'm.f_name as buyer_name')
                              ->join('agents as m', 'm.code', 'affiliates.affiliate_id')
                              ->join('transactions as t', 't.user_id', 'm.code')
                              ->join('transaction_details as d', 'd.transaction_id', 't.id')
                              ->where('t.status', '1')
                              ->where('affiliates.user_id', Auth::user()->code)
                              ->whereBetween(DB::raw('DATE_FORMAT(t.created_at, "%Y-%m-%d")'), array($start, $end))
                              ->get();


     $pv_transaction_team_cus = Affiliate::select('t.transaction_no', 
                                                  't.status',
                                                  'd.product_name', 
                                                  'd.product_image', 
                                                  'd.get_pv', 
                                                  'd.unit_price', 
                                                  'd.quantity',
                                                  't.created_at',
                                                  't.id as team_cus_pv',
                                                  'u.code as buyer_code',
                                                  'u.f_name as buyer_name',
                                                  'm.code as upline_code',
                                                  'm.f_name as upline_name')
                                        ->join('agents as m', 'm.code', 'affiliates.affiliate_id')
                                        ->join('users as u', 'u.master_id', 'm.code')
                                        ->join('transactions as t', 't.user_id', 'u.code')
                                        ->join('transaction_details as d', 'd.transaction_id', 't.id')
                                        ->where('t.status', '1')
                                        ->where('affiliates.user_id', Auth::user()->code)
                                        ->whereBetween(DB::raw('DATE_FORMAT(t.created_at, "%Y-%m-%d")'), array($start, $end))
                                        ->get();

     $pv_transaction_my_cus = Transaction::select('transactions.transaction_no', 
                                                  'transactions.status',
                                                  'd.product_name', 
                                                  'd.product_image', 
                                                  'd.get_pv', 
                                                  'd.unit_price', 
                                                  'd.quantity',
                                                  'transactions.created_at',
                                                  'transactions.id as my_cus_pv',
                                                  'u.code as buyer_code',
                                                  'u.f_name as buyer_name')
                                         ->join('transaction_details as d', 'd.transaction_id', 'transactions.id')
                                         ->join('users as u', 'transactions.user_id', 'u.code')
                                         ->where('u.master_id', Auth::user()->code)
                                         ->where('transactions.status', '1')
                                         ->whereBetween(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), array($start, $end))
                                         ->get();

        $transfer_cash_to_topup = AdjustCashToTopup::select('adjust_cash_to_topup.*', 'adjust_cash_to_topup.amount as transfer_amount', 'transfer_to_agent.f_name as transfer_to_agent_name')
                                                    ->leftJoin('agents as transfer_to_agent', 'transfer_to_agent.code', 'adjust_cash_to_topup.user_id')
                                                    ->where('user_by', Auth::user()->code)
                                                    ->get();

        $transfer_topup_from_cash = AdjustCashToTopup::select('adjust_cash_to_topup.*', 'adjust_cash_to_topup.amount as transfer_amount', 'transfer_from_agent.f_name as transfer_from_agent_name')
                                                    ->leftJoin('agents as transfer_from_agent', 'transfer_from_agent.code', 'adjust_cash_to_topup.user_by')
                                                    ->where('user_id', Auth::user()->code)
                                                    ->get();
        
        $topup_pv = TopupPv::where('user_id', Auth::user()->code)->get();

        $purchaseDetail = [];
        foreach($transactions as $transaction){
          $purchaseDetail[$transaction->id] = TransactionDetail::where('transaction_id', $transaction->id)->get();
        }

                $commissions = AffiliateCommission::select('affiliate_commissions.*',
                                                           't.shipping_fee',
                                                           't.processing_fee',
                                                           't.discount',
                                                            DB::raw('t.grand_total AS Gtotal'),
                                                            DB::raw('COALESCE(m.f_name, a.f_name) as username'),
                                                            DB::raw('COALESCE(mt.f_name, ut.f_name) as buyer'))
                                                            ->leftJoin('transactions AS t', 't.transaction_no', 'affiliate_commissions.transaction_no')
                                                            ->leftJoin('agents as m', 'm.code', 'affiliate_commissions.user_id')
                                                            ->leftJoin('admins as a', 'a.code', 'affiliate_commissions.user_id')
                                                            ->leftJoin('agents as mt', 'mt.code', 't.user_id')
                                                            ->leftJoin('users as ut', 'ut.code', 't.user_id')
                                                            ->where('affiliate_commissions.user_id', Auth::user()->code)
                                                            ->whereBetween(DB::raw('DATE_FORMAT(affiliate_commissions.created_at, "%Y-%m-%d")'), [$start, $end])
                                                            ->orderBy('affiliate_commissions.created_at', 'desc')
                                                            ->get();

        $all = $commissions->concat($withdrawlHistorys);
        // $all = $all->concat($topups);
        $all = $all->concat($transactions);
        $all = $all->concat($joining_fees);
        $all = $all->concat($AdjustCashWallet);
        $all = $all->concat($transfer_cash_to_topup);
        // $all = $all->concat($AdjustTopupWallet);

        $all_topup = $topups->concat($AdjustTopupWallet);
        $all_topup = $all_topup->concat($transfer_topup_from_cash);

        $all_pv = $transactions_pv->concat($pv_transaction_team);
        $all_pv = $all_pv->concat($pv_transaction_team_cus);
        $all_pv = $all_pv->concat($pv_transaction_my_cus);
        $all_pv = $all_pv->concat($topup_pv);
        $all_pv = $all_pv->concat($AdjustPointWallet);
        $all_pv = $all_pv->concat($purchase_pv);
        $columns = [
            'wallet_withdrawal', 'wallet_comm', 'today', 'this_month', 'this_year'
        ];

        foreach($columns as $column){
            if(request()->has($column) && !empty(request($column))){
                if($column == 'wallet_withdrawal'){
                    if(request($column) == '1'){
                        $all = $withdrawlHistorys;
                    }elseif (request($column) == '2') {
                        $all = $topups;
                    }elseif (request($column) == '3') {
                        $all = $withdrawlHistorys->concat($topups);
                    }else{

                    }
                }elseif($column == 'wallet_comm'){
                    if(request($column) == '1'){
                        $all = AffiliateCommission::select('affiliate_commissions.*', 't.shipping_fee', 't.processing_fee', 't.discount', 
                                                   't.grand_total AS Gtotal',
                                                   DB::raw('COALESCE(m.f_name, a.f_name) as username'),
                                                   DB::raw('COALESCE(mt.f_name, ut.f_name) as buyer'))
                                          ->leftJoin('transactions AS t', 't.transaction_no', 'affiliate_commissions.transaction_no')
                                          ->leftJoin('agents as m', 'm.code', 'affiliate_commissions.user_id')
                                          ->leftJoin('admins as a', 'a.code', 'affiliate_commissions.user_id')
                                          ->leftJoin('agents as mt', 'mt.code', 't.user_id')
                                          ->leftJoin('users as ut', 'ut.code', 't.user_id')
                                          ->where('affiliate_commissions.user_id', Auth::user()->code)
                                          ->where('comm_desc', 'like', '%Distribution%')
                                          ->whereBetween(DB::raw('DATE_FORMAT(affiliate_commissions.created_at, "%Y-%m-%d")'), array($start, $end))
                                          ->orderBy('affiliate_commissions.created_at', 'desc')
                                          ->get();

                    }elseif(request($column) == '2'){
                        $all = AffiliateCommission::select('affiliate_commissions.*', 't.shipping_fee', 't.processing_fee', 't.discount', 
                                                   't.grand_total AS Gtotal',
                                                   DB::raw('COALESCE(m.f_name, a.f_name) as username'),
                                                   DB::raw('COALESCE(mt.f_name, ut.f_name) as buyer'))
                                          ->leftJoin('transactions AS t', 't.transaction_no', 'affiliate_commissions.transaction_no')
                                          ->leftJoin('agents as m', 'm.code', 'affiliate_commissions.user_id')
                                          ->leftJoin('admins as a', 'a.code', 'affiliate_commissions.user_id')
                                          ->leftJoin('agents as mt', 'mt.code', 't.user_id')
                                          ->leftJoin('users as ut', 'ut.code', 't.user_id')
                                          ->where('affiliate_commissions.user_id', Auth::user()->code)
                                          ->where('comm_desc', 'like', '%Wholesale%')
                                          ->whereBetween(DB::raw('DATE_FORMAT(affiliate_commissions.created_at, "%Y-%m-%d")'), array($start, $end))
                                          ->orderBy('affiliate_commissions.created_at', 'desc')
                                          ->get();

                    }elseif(request($column) == '3'){
                        $all = AffiliateCommission::select('affiliate_commissions.*', 't.shipping_fee', 't.processing_fee', 't.discount', 
                                                   't.grand_total AS Gtotal',
                                                   DB::raw('COALESCE(m.f_name, a.f_name) as username'),
                                                   DB::raw('COALESCE(mt.f_name, ut.f_name) as buyer'))
                                          ->leftJoin('transactions AS t', 't.transaction_no', 'affiliate_commissions.transaction_no')
                                          ->leftJoin('agents as m', 'm.code', 'affiliate_commissions.user_id')
                                          ->leftJoin('admins as a', 'a.code', 'affiliate_commissions.user_id')
                                          ->leftJoin('agents as mt', 'mt.code', 't.user_id')
                                          ->leftJoin('users as ut', 'ut.code', 't.user_id')
                                          ->where('affiliate_commissions.user_id', Auth::user()->code)
                                          ->where('comm_desc', 'like', '%Sales%')
                                          ->whereBetween(DB::raw('DATE_FORMAT(affiliate_commissions.created_at, "%Y-%m-%d")'), array($start, $end))
                                          ->orderBy('affiliate_commissions.created_at', 'desc')
                                          ->get();

                    }elseif (request($column) == '4') {
                        $all = $commissions;
                    }elseif(request($column) == '5'){
                        $all = AffiliateCommission::select('affiliate_commissions.*', 't.shipping_fee', 't.processing_fee', 't.discount', 
                                                   't.grand_total AS Gtotal',
                                                   DB::raw('COALESCE(m.f_name, a.f_name) as username'),
                                                   DB::raw('COALESCE(mt.f_name, ut.f_name) as buyer'))
                                          ->leftJoin('transactions AS t', 't.transaction_no', 'affiliate_commissions.transaction_no')
                                          ->leftJoin('agents as m', 'm.code', 'affiliate_commissions.user_id')
                                          ->leftJoin('admins as a', 'a.code', 'affiliate_commissions.user_id')
                                          ->leftJoin('agents as mt', 'mt.code', 't.user_id')
                                          ->leftJoin('users as ut', 'ut.code', 't.user_id')
                                          ->where('affiliate_commissions.user_id', Auth::user()->code)
                                          ->where('comm_desc', 'like', '%Introduction%')
                                          ->whereBetween(DB::raw('DATE_FORMAT(affiliate_commissions.created_at, "%Y-%m-%d")'), array($start, $end))
                                          ->orderBy('affiliate_commissions.created_at', 'desc')
                                          ->get();
                    }else{
                        
                    }
                }elseif($column == 'today'){
                  
                }elseif($column == 'this_month'){
                  
                }elseif($column == 'this_year'){
                  
                }else{
                  $all = $all->where($column, 'like', "%".request($column)."%");
                }

                $queries[$column] = request($column);
            }
        }

        $all = array_reverse(Arr::sort($all, function ($value) {
            return $value['created_at'];
        }));

        $all_topup = array_reverse(Arr::sort($all_topup, function ($value) {
            return $value['created_at'];
        }));

        $banksDefault = BankAccount::where('user_id', Auth::user()->code)
                                   ->where('default_banks', '1')
                                   ->first();
                                   
        $banks = BankAccount::where('user_id', Auth::user()->code)
                            ->where('status', '!=', '3')
                            ->orderBy('created_at', 'desc')
                            ->get();

        $direct_downlines = Affiliate::select('a.*')
                                    ->join('agents as a', 'a.code', 'affiliates.affiliate_id')
                                    ->where('affiliates.sort_level', '1')
                                    ->where('affiliates.user_id', Auth::user()->code)
                                    ->get();

        $website_setting = WebsiteSetting::first();

        $CashWallet = $this->GetCashWalletBalance(Auth::user()->code);
        $GetPVWallet = GlobalController::get_point_wallet(Auth::user()->code);
        $get_topup_wallet_balance = GlobalController::get_topup_wallet_balance(Auth::user()->code);

        return view('frontend.wallet', ['all'=>$all, 
                                        'CashWallet'=>$CashWallet, 
                                        'withdrawlHistorys'=>$withdrawlHistorys,
                                        'banks'=>$banks, 
                                        'banksDefault'=>$banksDefault,
                                        'start'=>$start, 
                                        'end'=>$end,
                                        'startDate'=>$startDate,
                                        'website_setting'=>$website_setting, 
                                        'endDate'=>$endDate,
                                        'GetPVWallet'=>$GetPVWallet,
                                        'all_pv'=>$all_pv,
                                        'get_topup_wallet_balance'=>$get_topup_wallet_balance,
                                        'all_topup'=>$all_topup,
                                        'direct_downlines'=>$direct_downlines], 
                                        compact('purchaseDetail'));
    }

    public function sales()
    {
        if(!empty(request('dates'))){
            $new_dates = explode('-', request('dates'));
            $rawStart = trim($new_dates[0] ?? '');
            $rawEnd = trim($new_dates[1] ?? '');

            $startObj = DateTime::createFromFormat('d/m/Y', $rawStart);
            $endObj = DateTime::createFromFormat('d/m/Y', $rawEnd);

            if($startObj && $endObj){
                $start = $startObj->format('Y-m-d');
                $end = $endObj->format('Y-m-d');
                $startDate = $startObj->format('d/m/Y');
                $endDate = $endObj->format('d/m/Y');
            }else{
                $start = date('Y-m-d', strtotime(str_replace('/', '-', $rawStart)));
                $end = date('Y-m-d', strtotime(str_replace('/', '-', $rawEnd)));
                $startDate = $rawStart;
                $endDate = $rawEnd;
            }
        }else{
            $ds = new DateTime("first day of this month");
            $de = new DateTime("last day of this month");
            $start = $ds->format('Y-m-d');
            $end = $de->format('Y-m-d');
            $startDate = $ds->format('d/m/Y');
            $endDate = $de->format('d/m/Y');
        }

        $perPage = request('per_page', 10);

                $transactions = Transaction::select('transactions.*')
                                                                     ->leftJoin('users as u', 'u.code', 'transactions.user_id')
                                                                     ->leftJoin('agents as m', 'm.code', 'transactions.user_id');

                if(!request()->filled('transaction_no')){
                    $transactions = $transactions->where(function($q){
                                $q->where('transactions.user_id', Auth::user()->code)
                                  ->orWhere('u.master_id', Auth::user()->code)
                                  ->orWhere('m.master_id', Auth::user()->code);
                    });
                }

                if(!request()->filled('transaction_no')){
                    $transactions = $transactions->whereBetween(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), [$start, $end]);
                }

                if(request()->filled('status')){
                    $status = request('status');
                    if(strpos($status, ',') !== false){
                        $statuses = array_filter(array_map('intval', explode(',', $status)));
                        if(!empty($statuses)){
                            $transactions = $transactions->whereIn('transactions.status', $statuses);
                        }
                    }else{
                        $transactions = $transactions->where('transactions.status', $status);
                    }
                }
                if(request()->filled('user')){
                    $transactions = $transactions->where('transactions.user_id', request('user'));
                }
                if(request()->filled('transaction_no')){
                    $transactions = $transactions->where('transactions.transaction_no', 'like', '%'.request('transaction_no').'%');
                }

                $transactions = $transactions->orderBy('transactions.created_at', 'desc')
                                                                         ->paginate($perPage)
                                                                         ->appends(request()->all());

        $details = [];
        foreach($transactions as $transaction){
            $details[$transaction->id] = TransactionDetail::select('transaction_details.*', 'i.image as product_image')
                                                          ->leftJoin('product_images as i', 'i.product_id', 'transaction_details.product_id')
                                                          ->where('transaction_id', $transaction->id)
                                                          ->get();
        }

        return view('frontend.sales', [
            'transactions' => $transactions,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ], compact('details'));
    }


    public function MyAffiliate($code)
    {
        $agent = Agent::where('code', $code)->first();
        $admin = Admin::where('code', $code)->first();
        $user = User::where('code', $code)->first();
        $corporate = Corporate::where('code', $code)->first();
        
        
        
        if(!empty($agent->id)){
            $id = $agent->code;
            $name = $agent->f_name.' '.$agent->l_name;
            $phone = $agent->phone;
            $lvl = $agent->lvl;
            $permission_lvl = $agent->permission_lvl;
            $profile_logo = $agent->profile_logo;
            $upline = $agent->master_id;
            $display_code = $agent->display_code.$agent->display_running_no;
        }elseif(!empty($user->id)){
            $id = $user->code;
            $name = $user->f_name.' '.$user->l_name;
            $phone = $user->phone;
            $lvl = $user->lvl;
            $permission_lvl = $user->permission_lvl;
            $profile_logo = $user->profile_logo;
            $upline = $user->master_id;
            $display_code = $user->display_code.$user->display_running_no;
        }elseif(!empty($corporate->id)){
            $id = $corporate->code;
            $name = $corporate->f_name.' '.$corporate->l_name;
            $phone = $corporate->phone;
            $lvl = $corporate->lvl;
            $permission_lvl = $corporate->permission_lvl;
            $profile_logo = $corporate->profile_logo;
            $upline = $corporate->master_id;
            $display_code = $corporate->display_code.$corporate->display_running_no;
        }else{
            $id = $admin->code;
            $name = $admin->f_name.' '.$admin->l_name;
            $phone = $admin->phone;
            $profile_logo = $admin->profile_logo;
            $lvl = 2;
            $permission_lvl = $admin->permission_lvl;
            $upline = "";
            $display_code = $admin->display_code.$admin->display_running_no;
        }
        
        $affiliates = Affiliate::select('m.*', 'affiliates.sort_level', 'l.agent_lvl as l_agent_lvl', 'l.agent_lvl_cn as l_agent_lvl_cn',
                                        DB::raw('COALESCE(m.f_name, u.f_name) as downline_name'),
                                        DB::raw('COALESCE(m.code, u.code) as downline_code'),
                                        DB::raw('COALESCE(m.master_id, u.master_id) as downline_master_id'),
                                        DB::raw('COALESCE(m.email, u.email) as downline_email'),
                                        DB::raw('COALESCE(m.country_code, u.country_code) as downline_country_code'),
                                        DB::raw('COALESCE(m.phone, u.phone) as downline_phone'),
                                        DB::raw('COALESCE(m.gender, u.gender) as downline_gender'),
                                        DB::raw('COALESCE(m.created_at, u.created_at) as downline_created_at'),
                                        'u.code as member_code',
                                        'm.code as agent_code')
                               ->leftjoin('agents as m', 'm.code', 'affiliates.affiliate_id')
                               ->leftjoin('users as u', 'u.code', 'affiliates.affiliate_id')
                               ->leftJoin('agents as um', 'um.code', 'm.master_id')
                               ->leftJoin('admins as ua', 'ua.code', 'm.master_id')
                               ->leftJoin('agent_levels as l', 'l.id', 'm.lvl')
                               ->where('affiliates.user_id', $code)
                               ->where('l.status', '1')
                               ->where(DB::raw('COALESCE(m.status, u.status)'), '1')
                               ->where('affiliates.sort_level', '<=', '3');

        if(!empty(request('name'))){
            $affiliates = $affiliates->where(DB::raw("CONCAT(m.f_name, m.l_name)"), 'like', '%'.request('name').'%');
        }

        if(!empty(request('generation'))){
            $affiliates = $affiliates->where('sort_level', request('generation'));
        }

        $affiliates = $affiliates->get();

        $OwnAffiliate = $this->GetOwnTotalAffiliates($code);
        $totalAgent = $this->totalAgent($code);

        $totalMonthYearAgent = $this->totalMonthYearAgent($code);
        $OwnAffiliate = $this->GetOwnTotalAffiliates($code);

        $TotalSales = $this->TotalSales($code);
        $monthTotalCustomerPurchase = $this->monthTotalCustomerPurchase($code);
        $yearTotalCustomerPurchase = $this->yearTotalCustomerPurchase($code);
        $AllTotalSales = $this->AllTotalSales($code);

        $PersonalDailySales = $this->PersonalDailySales($code);
        $PersonalMonthlySales = $this->PersonalMonthlySales($code);
        $PersonalYearlySales = $this->PersonalYearlySales($code);
        $PersonalTotalSales = $this->PersonalTotalSales($code);

        $GroupDailySales = $this->GroupDailySales($code);
        $GroupMonthlySales = $this->GroupMonthlySales($code);
        $GroupYearlySales = $this->GroupYearlySales($code);
        $GroupTotalSales = $this->GroupTotalSales($code);

        $PersonalDailyComm = $this->PersonalDailyComm($code);
        $PersonalMonthlyComm = $this->PersonalMonthlyComm($code);
        $PersonalYearlyComm = $this->PersonalYearlyComm($code);
        $PersonalTotalComm = $this->PersonalTotalComm($code);

        $GroupDailyComm = $this->GroupDailyComm($code);
        $GroupMonthlyComm = $this->GroupMonthlyComm($code);
        $GroupYearlyComm = $this->GroupYearlyComm($code);
        $GroupTotalComm = $this->GroupTotalComm($code);

        $DailyComm = $this->GroupDailyComm($code) + $this->PersonalDailyComm($code);
        $MonthlyComm = $this->GroupMonthlyComm($code) + $this->PersonalMonthlyComm($code);
        $YearlyComm = $this->GroupYearlyComm($code) + $this->PersonalYearlyComm($code);
        $TotalComm = $this->GroupTotalComm($code) + $this->PersonalTotalComm($code);

        $OwnTotalAffiliate = $this->GetSelectedUserTotalAffiliates($code);
        $OwnMonthlyTotalAffiliate = $this->GetSelectedUserMonthlyTotalAffiliates($code);
        $GetSelectedUserDailyTotalAffiliates = $this->GetSelectedUserDailyTotalAffiliates($code);
        $TotalAffiliates = [];
        $TodayTotalAffiliates = [];
        $AffiliateDirectUpline = [];
        $firstUpgrade = [];
        $zongdaiupdate = [];
        $get_monthly_user_accumulated_sales = [];
        $get_user_accumulated_sales = [];

        foreach($affiliates as $affiliate){
            $AffiliateDirectUpline[$affiliate->downline_code] = Agent::where('code', $affiliate->downline_master_id)->first();
            if(empty($AffiliateDirectUpline[$affiliate->downline_code])){
              $AffiliateDirectUpline[$affiliate->downline_code] = Admin::where('code', $affiliate->downline_master_id)->first();
            }
            if(empty($AffiliateDirectUpline[$affiliate->downline_code])){
              $AffiliateDirectUpline[$affiliate->downline_code] = User::where('code', $affiliate->downline_master_id)->first();
            }
            $TotalAffiliates[$affiliate->code] = $this->GetTotalAffiliates($affiliate->code);
            $TodayTotalAffiliates[$affiliate->code] = $this->GetTodayTotalAffiliates($affiliate->code);

            $get_monthly_user_accumulated_sales[$affiliate->code] = GlobalController::get_user_accumulated_sales($affiliate->code, date('Y-m'));
            $get_user_accumulated_sales[$affiliate->code] = GlobalController::get_user_accumulated_sales($affiliate->code);
        }
        

        $agent1 = Agent::where('code', $code)->first();
        $admin1 = Admin::where('code', $code)->first();
        $user1 = User::where('code', $code)->first();
        $corporate1 = Corporate::where('code', $code)->first();
        $currentUser = "";

        if(!empty($admin1->id)){
            $currentUser = $admin1;
        }elseif(!empty($agent1->id)){
            $currentUser = $agent1;
        }elseif(!empty($user1->id)){
            $currentUser = $user1;
        }elseif(!empty($corporate1->id)){
            $currentUser = $corporate1;
        }

        $lvl = "";
        $partner_lvl = "";
        $city_agent = "";
        $state_agent = "";
        $agentLVL = AgentLevel::find(Auth::user()->lvl);
        $PartnerAgentLVL = PartnerLevel::find(Auth::user()->lvl);

        if(!empty($agentLVL->id)){
            $lvl = $agentLVL->agent_lvl;
        }

        if(!empty($PartnerAgentLVL->id)){
            $partner_lvl = $PartnerAgentLVL->partner_lvl." (".$PartnerAgentLVL->partner_lvl_cn.")";
        }

        if(!empty(Auth::user()->city_agent)){
            $get_city = City::find(Auth::user()->city_agent);
            $city_agent = $get_city->city_name;
        }

        if(!empty(Auth::user()->state_agent)){
            $get_state = State::find(Auth::user()->state_agent);
            $state_agent = $get_state->name;
        }

        $getMUpline = Agent::where('code', $currentUser->master_id)->first();
        $getAUpline = Admin::where('code', $currentUser->master_id)->first();
        $getUUpline = User::where('code', $currentUser->master_id)->first();

        $upline_name = "";
        $upline_code = "";
        if(!empty($getMUpline->id)){
          $upline_name = $getMUpline->f_name.' '.$getMUpline->l_name;
          $upline_code = $getMUpline->display_code.$getMUpline->display_running_no;
        }

        if(!empty($getAUpline->id)){
          $upline_name = $getAUpline->f_name.' '.$getAUpline->l_name;
          $upline_code = $getAUpline->display_code.$getAUpline->display_running_no;
        }

        if(!empty($getUUpline->id)){
          $upline_name = $getUUpline->f_name.' '.$getUUpline->l_name;
          $upline_code = $getUUpline->display_code.$getUUpline->display_running_no;
        }

        $own_display_code = Auth::user()->display_code.Auth::user()->display_running_no;

        $agent = Agent::where('code', $code)->first();
        $member = User::where('code', $code)->first();
        $admin = Admin::where('code', $code)->first();

        if(!empty($agent->id)){
          $user = $agent;
        }

        if(!empty($member->id)){
          $user = $member;
        }

        if(!empty($admin->id)){
          $user = $admin;
        }

        $agentD = Agent::where('master_id', $code)
                             ->where('status', '1')
                             ->get();
        

        $mdd = [];
        $mddd = [];
        $mdddd = [];

        foreach($agentD as $agentdv){
            $mdd[$agentdv->code] = Agent::where('master_id', $agentdv->code)->where('status', '1')->get();

            foreach($mdd[$agentdv->code] as $mddv){
                $mddd[$mddv->code] = Agent::where('master_id', $mddv->code)->where('status', '1')->get();

                foreach($mddd[$mddv->code] as $mdddv){
                    $mdddd[$mdddv->code] = Agent::where('master_id', $mdddv->code)->where('status', '1')->get();
                }
            }
        }

        // exit();

        $upgrade_record = AgentLevelRecord::where('user_id', $currentUser->code)->get();
        $allAffiliateRecord = User::where('status', '3')->get();
        $allMerchants = Agent::where('status', '1')->get();

        $aff_joined_date = [];
        foreach($allAffiliateRecord as $affiliate_record){
            $ic_record = explode('-', $affiliate_record->ic);
            foreach($allMerchants as $affiliate){
                if($affiliate->ic == $ic_record[0]){
                    $aff_joined_date[$affiliate->code] = $affiliate_record;
                }
            }
        }

        return view('frontend.my_affiliates', ['affiliates'=>$affiliates, 
                                                 'OwnTotalAffiliate'=>$OwnTotalAffiliate, 
                                                 'OwnMonthlyTotalAffiliate'=>$OwnMonthlyTotalAffiliate, 
                                                 'GetSelectedUserDailyTotalAffiliates'=>$GetSelectedUserDailyTotalAffiliates,
                                                 'name'=>$name,
                                                 'code'=>$code,
                                                 'lvl'=>$lvl,
                                                 'upline'=>$upline,
                                                 'phone'=>$phone,
                                                 'display_code'=>$display_code,
                                                 'profile_logo'=>$profile_logo,
                                                 'OwnAffiliate'=>$OwnAffiliate,
                                                 'permission_lvl'=>$permission_lvl,
                                                 'upline_name'=>$upline_name,
                                                 'upline_code'=>$upline_code,
                                                 'totalAgent'=>$totalAgent,
                                                 'totalMonthYearAgent'=>$totalMonthYearAgent,
                                                 'TotalSales'=>$TotalSales,
                                                 'monthTotalCustomerPurchase'=>$monthTotalCustomerPurchase,
                                                 'yearTotalCustomerPurchase'=>$yearTotalCustomerPurchase,
                                                 'AllTotalSales'=>$AllTotalSales,
                                                 'PersonalDailySales'=>$PersonalDailySales,
                                                 'PersonalMonthlySales'=>$PersonalMonthlySales,
                                                 'PersonalYearlySales'=>$PersonalYearlySales,
                                                 'PersonalTotalSales'=>$PersonalTotalSales,
                                                 'GroupDailySales'=>$GroupDailySales,
                                                 'GroupMonthlySales'=>$GroupMonthlySales,
                                                 'GroupYearlySales'=>$GroupYearlySales,
                                                 'GroupTotalSales'=>$GroupTotalSales,
                                                 'user'=>$user,
                                                 'merchantD'=>$agentD,
                                                 'PersonalDailyComm'=>$PersonalDailyComm,
                                                 'PersonalMonthlyComm'=>$PersonalMonthlyComm,
                                                 'PersonalYearlyComm'=>$PersonalYearlyComm,
                                                 'PersonalTotalComm'=>$PersonalTotalComm, 
                                                 'upgrade_record'=>$upgrade_record,
                                                 'GroupDailyComm'=>$GroupDailyComm,
                                                 'GroupMonthlyComm'=>$GroupMonthlyComm,
                                                 'GroupYearlyComm'=>$GroupYearlyComm,
                                                 'GroupTotalComm'=>$GroupTotalComm,
                                                 'DailyComm'=>$DailyComm,
                                                 'MonthlyComm'=>$MonthlyComm,
                                                 'YearlyComm'=>$YearlyComm,
                                                 'TotalComm'=>$TotalComm,
                                                 'partner_lvl'=>$partner_lvl,
                                                 'city_agent'=>$city_agent,
                                                 'state_agent'=>$state_agent,
                                                 'own_display_code'=>$own_display_code],
                                                 compact('TotalAffiliates', 'TodayTotalAffiliates', 'AffiliateDirectUpline', 'firstUpgrade', 
                                                         'zongdaiupdate', 'mdd', 'mddd', 'aff_joined_date',
                                                         'mdddd',
                                                         'get_user_accumulated_sales',
                                                         'get_monthly_user_accumulated_sales'));
    }
    public function MyCustomer($code)
    {
        $merchant = Agent::where('code', $code)->first();
        $admin = Admin::where('code', $code)->first();
        $user = User::where('code', $code)->first();
        
        
        if(!empty($merchant->id)){
            $id = $merchant->code;
            $name = $merchant->f_name.' '.$merchant->l_name;
            $phone = $merchant->phone;
            $lvl = $merchant->lvl;
            $permission_lvl = $merchant->permission_lvl;
            $profile_logo = $merchant->profile_logo;
            $upline = $merchant->master_id;
            $display_code = $merchant->display_code.$merchant->display_running_no;
        }elseif(!empty($user->id)){
            $id = $user->code;
            $name = $user->f_name.' '.$user->l_name;
            $phone = $user->phone;
            $lvl = $user->lvl;
            $permission_lvl = $user->permission_lvl;
            $profile_logo = $user->profile_logo;
            $upline = $user->master_id;
            $display_code = $user->display_code.$user->display_running_no;
        }else{
            $id = $admin->code;
            $name = $admin->f_name.' '.$admin->l_name;
            $phone = $admin->phone;
            $profile_logo = $admin->profile_logo;
            $lvl = 2;
            $permission_lvl = $admin->permission_lvl;
            $upline = "";
            $display_code = $admin->display_code.$admin->display_running_no;
        }
        
        $affiliate = User::select('users.*',
                                        DB::raw('COALESCE(CONCAT(um.display_code, um.display_running_no), CONCAT(ua.display_code, ua.display_running_no)) as upline_code'))
                         ->leftJoin('users as um', 'um.code', 'users.master_id')
                         ->leftJoin('admins as ua', 'ua.code', 'users.master_id')
                         ->where('users.master_id', $code)
                         ->where('users.status', '1');

        $affiliates1 = Affiliate::select('u.*', 'm.f_name as m_name', 'affiliates.sort_level',
                                        DB::raw('COALESCE(CONCAT(um.display_code, um.display_running_no), CONCAT(ua.display_code, ua.display_running_no)) as upline_code'))
                                ->join('users as m', 'm.code', 'affiliates.affiliate_id')
                                ->join('users as u', 'u.master_id', 'm.code')
                                ->leftJoin('users as um', 'um.code', 'u.master_id')
                                ->leftJoin('admins as ua', 'ua.code', 'u.master_id')
                                ->where('affiliates.user_id', $code)
                                ->where('affiliates.sort_level', '<=', '2');

        $upgraded_affiliates = Agent::where('master_id', $code)
                                       ->where('status', '1');

        if(!empty(request('name'))){
            $search = request('name');
            // Normalize collation to avoid "Illegal mix of collations" by applying a uniform collation to the concatenated columns
            $affiliate = $affiliate->whereRaw("CONCAT(users.f_name, users.l_name) COLLATE utf8mb4_unicode_ci LIKE ?", ['%'.$search.'%']);
            $upgraded_affiliates = $upgraded_affiliates->whereRaw("CONCAT(agents.f_name, agents.l_name) COLLATE utf8mb4_unicode_ci LIKE ?", ['%'.$search.'%']);
        }

        if(!empty(request('generation')) && request('generation') == '1'){
            
        }elseif(!empty(request('generation')) && request('generation') == '2'){
            $affiliates1 = $affiliates1->where('sort_level', 1);
        }elseif(!empty(request('generation')) && request('generation') == '3'){
            $affiliates1 = $affiliates1->where('sort_level', 2);
        }

        $upgraded_affiliates = $upgraded_affiliates->get();
        $affiliate = $affiliate->get();
        $affiliates1 = $affiliates1->get();
        // foreach($affiliates1 as $asd){
        //     echo $asd->f_name.' '.$asd->master_id.' '.$asd->m_name;
        //     echo "<br>";
        // }
        // exit();
        if(!empty(request('generation')) && request('generation') > 1){
          $affiliates = $affiliates1;
        }elseif(!empty(request('generation')) && request('generation') == 1){
          $affiliates = $affiliate;
        }else{
          $affiliates = $affiliate->concat($affiliates1);
        }
        // $affiliates = $affiliate->concat($upgraded_affiliates);

        $totalCustomers = $this->totalCustomerGeneration($code);

        $totalCustomer = $this->totalCustomer($code);
        $TodayNewCustomer = $this->TodayNewCustomer($code);
        $TotalSales = $this->TotalSales($code);

        $monthTotalCustomer = $this->monthTotalCustomer($code);
        $yearTotalCustomer = $this->yearTotalCustomer($code);

        $monthTotalCustomerPurchase = $this->monthTotalCustomerPurchase($code);
        $yearTotalCustomerPurchase = $this->yearTotalCustomerPurchase($code);

        $TotalAffiliates = [];
        $TodayTotalAffiliates = [];


        $customerTotalTodaySales = [];
        foreach($affiliates as $affiliate){
            $customerTotalTodaySales[$affiliate->code] = $this->customerTotalTodaySales($affiliate->code);
        }

        $agent = Agent::where('code', $code)->first();
        $member = User::where('code', $code)->first();
        $admin = Admin::where('code', $code)->first();

        if(!empty($agent->id)){
          $user = $agent;
        }

        if(!empty($member->id)){
          $user = $member;
        }

        if(!empty($admin->id)){
          $user = $admin;
        }

        $userD = User::where('master_id', $code)
                     ->where('status', '1')
                     ->get();
        // $downAgent = Agent::where('master_id', $code)
        //                   ->where('status', '1')
        //                   ->get();

        // $userD = $userD->concat($downAgent);
        

        $mdd = [];
        $mddd = [];
        $mddd1 = [];
        $mddd2 = [];
        $mddd3 = [];

        foreach($userD as $userdv){
            $mdd[$userdv->code] = User::where('master_id', $userdv->code)->where('status', '1')->get();
            // if(empty($mdd[$userdv->code])){
            //   $mdd[$userdv->code] = Agent::where('master_id', $userdv->code)->where('status', '1')->get();
            // }

            foreach($mdd[$userdv->code] as $mddv){
                $mddd[$mddv->code] = User::where('master_id', $mddv->code)->where('status', '1')->get();
                // if(empty($mdd[$mddv->code])){
                //   $mddd[$mddv->code] = Agent::where('master_id', $mddv->code)->where('status', '1')->get();
                // }

                foreach($mddd[$mddv->code] as $mdddv){
                    $mddd1[$mdddv->code] = User::where('master_id', $mdddv->code)->where('status', '1')->get();
                    // if(empty($mdd[$mdddv->code])){
                    //   $mddd[$mdddv->code] = Agent::where('master_id', $mdddv->code)->where('status', '1')->get();
                    // }

                    foreach($mddd1[$mdddv->code] as $mddddv){
                        $mddd2[$mddddv->code] = User::where('master_id', $mddddv->code)->where('status', '1')->get();
                        // if(empty($mdd[$mddddv->code])){
                        //   $mddd[$mddddv->code] = Agent::where('master_id', $mddddv->code)->where('status', '1')->get();
                        // }

                        foreach($mddd2[$mddddv->code] as $mdddddv){
                            $mddd3[$mdddddv->code] = User::where('master_id', $mdddddv->code)->where('status', '1')->get();
                            // if(empty($mdd[$mdddddv->code])){
                            //   $mddd[$mdddddv->code] = Agent::where('master_id', $mdddddv->code)->where('status', '1')->get();
                            // }
                        }
                    }
                }
            }
        }

        $upgrade_record = AgentLevelRecord::where('user_id', Auth::user()->code)->get();

        $aff_joined_date = [];
        if(Auth::guard('agent') || Auth::guard('admin')){
            $allAffiliateRecord = User::where('status', '3')->get();
            $allMerchants = Agent::where('status', '1')->get();

            foreach($allAffiliateRecord as $affiliate_record){
                $ic_record = explode('-', $affiliate_record->ic);
                foreach($allMerchants as $affiliate){
                    if($affiliate->ic == $ic_record[0]){
                        $aff_joined_date[$affiliate->code] = $affiliate_record;
                    }
                }
            }
        }

        return view('frontend.my_customers', ['affiliates'=>$affiliates, 
                                               'totalCustomer'=>$totalCustomer, 
                                               'TodayNewCustomer'=>$TodayNewCustomer, 
                                               'TotalSales'=>$TotalSales,
                                               'name'=>$name,
                                               'code'=>$code,
                                               'upline'=>$upline,
                                               'phone'=>$phone,
                                               'display_code'=>$display_code,
                                               'profile_logo'=>$profile_logo,
                                               'permission_lvl'=>$permission_lvl,
                                               'totalCustomers'=>$totalCustomers,
                                               'monthTotalCustomer'=>$monthTotalCustomer,
                                               'yearTotalCustomer'=>$yearTotalCustomer,
                                               'monthTotalCustomerPurchase'=>$monthTotalCustomerPurchase,
                                               'yearTotalCustomerPurchase'=>$yearTotalCustomerPurchase,
                                               'user'=>$user,
                                               'userD'=>$userD, 
                                               'upgrade_record'=>$upgrade_record],
                                               compact('customerTotalTodaySales', 
                                                       'mdd', 
                                                       'mddd', 
                                                       'mddd1', 
                                                       'mddd2', 
                                                       'mddd3', 
                                                       'aff_joined_date'));
    }

    public function Material()
    {
        $materials = SettingDownloadMaterial::get();
        return view('frontend.material', ['materials'=>$materials]);
    }

    public function MyCustomerTransaction($code)
    {
        $user = User::where('code', $code) //User code is not the same transaction user id
                    ->first();

        if(empty($user)){
            $user = Agent::where('code', $code) //User code is not the same transaction user id
                            ->first();
            if(empty($user)){
                $user = Admin::where('code', $code) //User code is not the same transaction user id
                                ->first();
            }
        }

            
        $id = $user->code;
        $name = $user->f_name.' '.$user->l_name;
        $phone = $user->phone;
        $lvl = $user->lvl;
        $permission_lvl = $user->permission_lvl;
        $profile_logo = $user->profile_logo;
        $upline = $user->master_id;
        
        if(!empty(request('dates'))){

          $new_dates = explode('-', request('dates'));
          $start = date('Y-m-d', strtotime($new_dates[0]));
          $end = date('Y-m-d', strtotime($new_dates[1]));

          $startDate = $new_dates[0];
          $endDate = $new_dates[1];

        }else{

          $ds = new DateTime("first day of this month");
          $de = new DateTime("last day of this month");

          $start = $ds->format('Y-m-d');
          $end = $de->format('Y-m-d');

          $startDate = $ds->format('m/d/Y');
          $endDate = $de->format('m/d/Y');
        }        

        $transactions = Transaction::select('transactions.*')
                                   ->where('user_id', $user->code)
                                   ->where('transactions.status', '1')
                                   ->get();

        $details = [];
        foreach($transactions as $transaction){
            $details[$transaction->id] = TransactionDetail::select('transaction_details.*', 'i.image as product_image')
                                                          ->leftJoin('product_images as i', 'i.product_id', 'transaction_details.product_id')
                                                          ->where('transaction_id', $transaction->id)
                                                          ->get();
        }

        $lvl = "";
        $partner_lvl = "";
        $city_agent = "";
        $state_agent = "";
        $agentLVL = AgentLevel::find(Auth::user()->lvl);
        $PartnerAgentLVL = PartnerLevel::find(Auth::user()->lvl);

        if(!empty($agentLVL->id)){
            $lvl = $agentLVL->agent_lvl;
        }

        if(!empty($PartnerAgentLVL->id)){
            $partner_lvl = $PartnerAgentLVL->partner_lvl." (".$PartnerAgentLVL->partner_lvl_cn.")";
        }

        if(!empty(Auth::user()->city_agent)){
            $get_city = City::find(Auth::user()->city_agent);
            $city_agent = $get_city->city_name;
        }

        if(!empty(Auth::user()->state_agent)){
            $get_state = State::find(Auth::user()->state_agent);
            $state_agent = $get_state->name;
        }

        

        $getMUpline = Agent::where('code', Auth::user()->master_id)->first();
        $getAUpline = Admin::where('code', Auth::user()->master_id)->first();
        $getUUpline = User::where('code', Auth::user()->master_id)->first();

        $upline_name = "";
        $upline_code = "";
        if(!empty($getMUpline->id)){
          $upline_name = $getMUpline->f_name.' '.$getMUpline->l_name;
          $upline_code = $getMUpline->display_code.$getMUpline->display_running_no;
        }

        if(!empty($getAUpline->id)){
          $upline_name = $getAUpline->f_name.' '.$getAUpline->l_name;
          $upline_code = $getAUpline->display_code.$getAUpline->display_running_no;
        }

        if(!empty($getUUpline->id)){
          $upline_name = $getUUpline->f_name.' '.$getUUpline->l_name;
          $upline_code = $getUUpline->display_code.$getUUpline->display_running_no;
        }

        $upgrade_record = AgentLevelRecord::where('user_id', Auth::user()->code)->get();

        $aff_joined_date = [];
        if(Auth::guard('agent') || Auth::guard('admin')){
            $allAffiliateRecord = User::where('status', '3')->get();
            $allMerchants = Agent::where('status', '1')->get();

            foreach($allAffiliateRecord as $affiliate_record){
                $ic_record = explode('-', $affiliate_record->ic);
                foreach($allMerchants as $affiliate){
                    if($affiliate->ic == $ic_record[0]){
                        $aff_joined_date[$affiliate->code] = $affiliate_record;
                    }
                }
            }
        }

        $own_display_code = Auth::user()->display_code.Auth::user()->display_running_no;
        if(!empty(Auth::user()->relegated_from_agent)){
            $original_agent = Agent::where('code', Auth::user()->relegated_from_agent)
                                    ->where('status', 55)
                                    ->first();

            if(!empty($original_agent->id)){
                $own_display_code = $original_agent->display_code.$original_agent->display_running_no;
            }
        }

        return view('frontend.my_customer_transactions', ['transactions'=>$transactions, 
                                                          'user'=>$user,
                                                          'endDate'=>$endDate, 
                                                          'startDate'=>$startDate,
                                                          'lvl'=>$lvl, 
                                                          'upline_name'=>$upline_name, 
                                                          'upline_code'=>$upline_code,
                                                          'upgrade_record'=>$upgrade_record,
                                                          'own_display_code'=>$own_display_code,
                                                          'partner_lvl'=>$partner_lvl,
                                                          'city_agent'=>$city_agent,
                                                          'state_agent'=>$state_agent],
                                                          compact('details', 
                                                                  'aff_joined_date'));
    }

    public function totalCustomer($code)
    {
      $affiliate = User::select(DB::raw('COUNT(id) AS TotalUserCustomer'))
                              ->where('master_id', $code)
                              ->where('status', '1')
                              ->first();

      // $upgraded_affiliate = Agent::select(DB::raw('COUNT(id) AS TotalAgentCustomer'))
      //                               ->where('master_id', $code)
      //                               ->where('status', '1')
      //                               ->first();

      $TotalCustomer = $affiliate->TotalUserCustomer;

      return  $TotalCustomer;
    }

    public function totalCustomerGeneration($code)
    {
        $affiliates_1 = Affiliate::where('user_id', $code)
                                 ->where('sort_level', '1')
                                 ->get();

        $affiliates_2 = Affiliate::where('user_id', $code)
                                 ->where('sort_level', '2')
                                 ->get();

        $affiliates_3 = Affiliate::where('user_id', $code)
                                 ->where('sort_level', '3')
                                 ->get();

        $firstGeneration = 0;
        $secondGeneration = 0;
        $thirdGeneration = 0;
        foreach($affiliates_1 as $affiliate_1){
            $users = User::select(DB::raw('COUNT(id) as totalFUsers'))
                         ->where('master_id', $affiliate_1->affiliate_id)
                         ->where('users.status', '1')
                         ->first();
            $firstGeneration += $users->totalFUsers;
        }
        foreach($affiliates_2 as $affiliate_2){
            $users = User::select(DB::raw('COUNT(id) as totalFUsers'))
                         ->where('master_id', $affiliate_2->affiliate_id)
                         ->where('users.status', '1')
                         ->first();
            $secondGeneration += $users->totalFUsers;
        }
        foreach($affiliates_3 as $affiliate_3){
            $users = User::select(DB::raw('COUNT(id) as totalFUsers'))
                         ->where('master_id', $affiliate_3->affiliate_id)
                         ->where('users.status', '1')
                         ->first();
            $thirdGeneration += $users->totalFUsers;
        }

        return array($firstGeneration, $secondGeneration, $thirdGeneration);
    }

    public function monthTotalCustomer($code)
    {

        $affiliate = User::select(DB::raw('COUNT(id) AS TotalUserCustomer'))
                              ->where('master_id', $code)
                              ->where('status', '1')
                              ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), date('Y-m'))
                              ->first();

        $affiliates_1 = Affiliate::where('user_id', $code)
                                 ->where('sort_level', '1')
                                 ->get();

        $affiliates_2 = Affiliate::where('user_id', $code)
                                 ->where('sort_level', '2')
                                 ->get();

        $affiliates_3 = Affiliate::where('user_id', $code)
                                 ->where('sort_level', '3')
                                 ->get();

        $firstGeneration = 0;
        $secondGeneration = 0;
        $thirdGeneration = 0;
        foreach($affiliates_1 as $affiliate_1){
            $users = User::select(DB::raw('COUNT(id) as totalFUsers'))
                         ->where('master_id', $affiliate_1->affiliate_id)
                         ->where('users.status', '1')
                         ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), date('Y-m'))
                         ->first();
            $firstGeneration += $users->totalFUsers;
        }
        foreach($affiliates_2 as $affiliate_2){
            $users = User::select(DB::raw('COUNT(id) as totalFUsers'))
                         ->where('master_id', $affiliate_2->affiliate_id)
                         ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), date('Y-m'))
                         ->where('users.status', '1')
                         ->first();
            $secondGeneration += $users->totalFUsers;
        }
        foreach($affiliates_3 as $affiliate_3){
            $users = User::select(DB::raw('COUNT(id) as totalFUsers'))
                         ->where('master_id', $affiliate_3->affiliate_id)
                         ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), date('Y-m'))
                         ->where('users.status', '1')
                         ->first();
            $thirdGeneration += $users->totalFUsers;
        }

        return $affiliate->TotalUserCustomer + $firstGeneration + $secondGeneration + $thirdGeneration;
    }

    public function yearTotalCustomer($code)
    {
        $affiliate = User::select(DB::raw('COUNT(id) AS TotalUserCustomer'))
                              ->where('master_id', $code)
                              ->where('status', '1')
                              ->where(DB::raw('DATE_FORMAT(created_at, "%Y")'), date('Y'))
                              ->first();
        $affiliates_1 = Affiliate::where('user_id', $code)
                                 ->where('sort_level', '1')
                                 ->get();

        $affiliates_2 = Affiliate::where('user_id', $code)
                                 ->where('sort_level', '2')
                                 ->get();

        $affiliates_3 = Affiliate::where('user_id', $code)
                                 ->where('sort_level', '3')
                                 ->get();

        $firstGeneration = 0;
        $secondGeneration = 0;
        $thirdGeneration = 0;
        foreach($affiliates_1 as $affiliate_1){
            $users = User::select(DB::raw('COUNT(id) as totalFUsers'))
                         ->where('master_id', $affiliate_1->affiliate_id)
                         ->where('users.status', '1')
                         ->where(DB::raw('DATE_FORMAT(created_at, "%Y")'), date('Y'))
                         ->first();
            $firstGeneration += $users->totalFUsers;
        }
        foreach($affiliates_2 as $affiliate_2){
            $users = User::select(DB::raw('COUNT(id) as totalFUsers'))
                         ->where('master_id', $affiliate_2->affiliate_id)
                         ->where(DB::raw('DATE_FORMAT(created_at, "%Y")'), date('Y'))
                         ->where('users.status', '1')
                         ->first();
            $secondGeneration += $users->totalFUsers;
        }
        foreach($affiliates_3 as $affiliate_3){
            $users = User::select(DB::raw('COUNT(id) as totalFUsers'))
                         ->where('master_id', $affiliate_3->affiliate_id)
                         ->where(DB::raw('DATE_FORMAT(created_at, "%Y")'), date('Y'))
                         ->where('users.status', '1')
                         ->first();
            $thirdGeneration += $users->totalFUsers;
        }

        return $affiliate->TotalUserCustomer + $firstGeneration + $secondGeneration + $thirdGeneration;
    }

    public function monthTotalCustomerPurchase($code)
    {
        // $users = User::where('master_id', $code)->where('status', '1')->get();

        // $purchases = 0;
        // foreach($users as $user){
        //     $transaction = Transaction::select(DB::raw('SUM(grand_total) as totalPurchase'))
        //                               ->where('user_id', $user->code)
        //                               ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), date('Y-m'))
        //                               ->first();

        //     $purchases += $transaction->totalPurchase;
        // }

        // return $purchases;

      $ownTransaction = Transaction::select(DB::raw('SUM(grand_total - processing_fee - shipping_fee) as totalPurchase'))
                                   ->where('user_id', $code)
                                   ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), date('Y-m'))
                                   ->where('status', '1')
                                   ->first();

      $affiliates = Affiliate::select(DB::raw('SUM(grand_total - processing_fee - shipping_fee) as totalPurchase'))
                             ->join('transactions as t', 't.user_id', 'affiliates.affiliate_id')
                             ->where('affiliates.user_id', $code)
                             ->where(DB::raw('DATE_FORMAT(t.created_at, "%Y-%m")'), date('Y-m'))
                             ->where('affiliates.sort_level', '<=', '3')
                             ->where('t.status', '1')
                             ->first();

      $totalPurchase = $ownTransaction->totalPurchase + $affiliates->totalPurchase;

      return  !empty($totalPurchase) ? $totalPurchase : '0.00';
    }

    public function yearTotalCustomerPurchase($code)
    {
        // $users = User::where('master_id', $code)->where('status', '1')->get();

        // $purchases = 0;
        // foreach($users as $user){
        //     $transaction = Transaction::select(DB::raw('SUM(grand_total) as totalPurchase'))
        //                               ->where('user_id', $user->code)
        //                               ->where(DB::raw('DATE_FORMAT(created_at, "%Y")'), date('Y'))
        //                               ->first();

        //     $purchases += $transaction->totalPurchase;
        // }

        // return $purchases;

        $ownTransaction = Transaction::select(DB::raw('SUM(grand_total - processing_fee - shipping_fee) as totalPurchase'))
                                   ->where('user_id', $code)
                                   ->where(DB::raw('DATE_FORMAT(created_at, "%Y")'), date('Y'))
                                   ->where('status', '1')
                                   ->first();

        $affiliates = Affiliate::select(DB::raw('SUM(grand_total - processing_fee - shipping_fee) as totalPurchase'))
                             ->join('transactions as t', 't.user_id', 'affiliates.affiliate_id')
                             ->where('affiliates.user_id', $code)
                             ->where(DB::raw('DATE_FORMAT(t.created_at, "%Y")'), date('Y'))
                             ->where('affiliates.sort_level', '<=', '3')
                             ->where('t.status', '1')
                             ->first();

        $totalPurchase = $ownTransaction->totalPurchase + $affiliates->totalPurchase;

        return  !empty($totalPurchase) ? $totalPurchase : '0.00';
    }

    public function TodayNewCustomer($code)
    {
      $affiliate = User::select(DB::raw('COUNT(id) AS TotalCustomer'))
                              ->where('master_id', $code)
                              ->where('status', '1')
                              ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), date('Y-m-d'))
                              ->first();

      $affiliates_1 = Affiliate::where('user_id', $code)
                                 ->where('sort_level', '1')
                                 ->get();

        $affiliates_2 = Affiliate::where('user_id', $code)
                                 ->where('sort_level', '2')
                                 ->get();

        $affiliates_3 = Affiliate::where('user_id', $code)
                                 ->where('sort_level', '3')
                                 ->get();

        $firstGeneration = 0;
        $secondGeneration = 0;
        $thirdGeneration = 0;
        foreach($affiliates_1 as $affiliate_1){
            $users = User::select(DB::raw('COUNT(id) as totalFUsers'))
                         ->where('master_id', $affiliate_1->affiliate_id)
                         ->where('users.status', '1')
                         ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), date('Y-m-d'))
                         ->first();
            $firstGeneration += $users->totalFUsers;
        }
        foreach($affiliates_2 as $affiliate_2){
            $users = User::select(DB::raw('COUNT(id) as totalFUsers'))
                         ->where('master_id', $affiliate_2->affiliate_id)
                         ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), date('Y-m-d'))
                         ->where('users.status', '1')
                         ->first();
            $secondGeneration += $users->totalFUsers;
        }
        foreach($affiliates_3 as $affiliate_3){
            $users = User::select(DB::raw('COUNT(id) as totalFUsers'))
                         ->where('master_id', $affiliate_3->affiliate_id)
                         ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), date('Y-m-d'))
                         ->where('users.status', '1')
                         ->first();
            $thirdGeneration += $users->totalFUsers;
        }

      return  $affiliate->TotalCustomer + $firstGeneration + $secondGeneration + $thirdGeneration;
    }

    public function PersonalDailyComm($code)
    {
      $affiliates = AffiliateCommission::select(DB::raw('SUM(comm_amount) as totalComm'))
                             ->where('affiliate_commissions.user_id', $code)
                             ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), date('Y-m-d'))
                             ->where('status', '1')
                             ->first();

      $totalComm = $affiliates->totalComm;

      return  !empty($totalComm) ? $totalComm : '0.00';
    }

    public function PersonalMonthlyComm($code)
    {
      $affiliates = AffiliateCommission::select(DB::raw('SUM(comm_amount) as totalComm'))
                             ->where('affiliate_commissions.user_id', $code)
                             ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), date('Y-m'))
                             ->where('status', '1')
                             ->first();

      $totalComm = $affiliates->totalComm;

      return  !empty($totalComm) ? $totalComm : '0.00';
    }

    public function PersonalYearlyComm($code)
    {
      $affiliates = AffiliateCommission::select(DB::raw('SUM(comm_amount) as totalComm'))
                             ->where('affiliate_commissions.user_id', $code)
                             ->where(DB::raw('DATE_FORMAT(created_at, "%Y")'), date('Y'))
                             ->where('status', '1')
                             ->first();

      $totalComm = $affiliates->totalComm;

      return  !empty($totalComm) ? $totalComm : '0.00';
    }

    public function PersonalTotalComm($code)
    {
      $affiliates = AffiliateCommission::select(DB::raw('SUM(comm_amount) as totalComm'))
                             ->where('affiliate_commissions.user_id', $code)
                             ->where('status', '1')
                             ->first();

      $totalComm = $affiliates->totalComm;

      return  !empty($totalComm) ? $totalComm : '0.00';
    }

    public function GroupDailyComm($code)
    {
      $allAff = Affiliate::where('user_id', $code)
                         ->where('status', '1')
                         ->get();

      $myAff = [];
      foreach ($allAff as $key => $Aff) {
        array_push($myAff, $Aff->affiliate_id);
      }

      $affiliates = AffiliateCommission::select(DB::raw('SUM(comm_amount) as totalComm'))
                             ->whereIn('affiliate_commissions.user_id', $myAff)
                             ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), date('Y-m-d'))
                             ->where('status', '1')
                             ->first();

      $totalComm = $affiliates->totalComm;

      return  !empty($totalComm) ? $totalComm : '0.00';
    }

    public function GroupMonthlyComm($code)
    {
      $allAff = Affiliate::where('user_id', $code)
                         ->where('status', '1')
                         ->get();

      $myAff = [];
      foreach ($allAff as $key => $Aff) {
        array_push($myAff, $Aff->affiliate_id);
      }

      $affiliates = AffiliateCommission::select(DB::raw('SUM(comm_amount) as totalComm'))
                             ->whereIn('affiliate_commissions.user_id', $myAff)
                             ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), date('Y-m'))
                             ->where('status', '1')
                             ->first();

      $totalComm = $affiliates->totalComm;

      return  !empty($totalComm) ? $totalComm : '0.00';
    }

    public function GroupYearlyComm($code)
    {
      $allAff = Affiliate::where('user_id', $code)
                         ->where('status', '1')
                         ->get();

      $myAff = [];
      foreach ($allAff as $key => $Aff) {
        array_push($myAff, $Aff->affiliate_id);
      }

      $affiliates = AffiliateCommission::select(DB::raw('SUM(comm_amount) as totalComm'))
                             ->whereIn('affiliate_commissions.user_id', $myAff)
                             ->where(DB::raw('DATE_FORMAT(created_at, "%Y")'), date('Y'))
                             ->where('status', '1')
                             ->first();

      $totalComm = $affiliates->totalComm;

      return  !empty($totalComm) ? $totalComm : '0.00';
    }

    public function GroupTotalComm($code)
    {
      $allAff = Affiliate::where('user_id', $code)
                         ->where('status', '1')
                         ->get();

      $myAff = [];
      foreach ($allAff as $key => $Aff) {
        array_push($myAff, $Aff->affiliate_id);
      }

      $affiliates = AffiliateCommission::select(DB::raw('SUM(comm_amount) as totalComm'))
                             ->whereIn('affiliate_commissions.user_id', $myAff)
                             ->where('status', '1')
                             ->first();

      $totalComm = $affiliates->totalComm;

      return  !empty($totalComm) ? $totalComm : '0.00';
    }
    public function GroupDailySales($code)
    {
      $affiliates = Affiliate::select(DB::raw('SUM(grand_total - processing_fee - shipping_fee) as totalPurchase'))
                             ->join('transactions as t', 't.user_id', 'affiliates.affiliate_id')
                             ->where('affiliates.user_id', $code)
                             ->where('affiliates.sort_level', '<=', '3')
                             ->where(DB::raw('DATE_FORMAT(t.created_at, "%Y-%m-%d")'), date('Y-m-d'))
                             ->where('t.status', '1')
                             ->first();

      $totalPurchase = $affiliates->totalPurchase;

      return  !empty($totalPurchase) ? $totalPurchase : '0.00';
    }

    public function GroupMonthlySales($code)
    {
      $affiliates = Affiliate::select(DB::raw('SUM(grand_total - processing_fee - shipping_fee) as totalPurchase'))
                             ->join('transactions as t', 't.user_id', 'affiliates.affiliate_id')
                             ->where('affiliates.user_id', $code)
                             ->where('affiliates.sort_level', '<=', '3')
                             ->where(DB::raw('DATE_FORMAT(t.created_at, "%Y-%m")'), date('Y-m'))
                             ->where('t.status', '1')
                             ->first();

      $totalPurchase = $affiliates->totalPurchase;

      return  !empty($totalPurchase) ? $totalPurchase : '0.00';
    }

    public function GroupYearlySales($code)
    {
      $affiliates = Affiliate::select(DB::raw('SUM(grand_total - processing_fee - shipping_fee) as totalPurchase'))
                             ->join('transactions as t', 't.user_id', 'affiliates.affiliate_id')
                             ->where('affiliates.user_id', $code)
                             ->where('affiliates.sort_level', '<=', '3')
                             ->where(DB::raw('DATE_FORMAT(t.created_at, "%Y")'), date('Y'))
                             ->where('t.status', '1')
                             ->first();

      $totalPurchase = $affiliates->totalPurchase;

      return  !empty($totalPurchase) ? $totalPurchase : '0.00';
    }

    public function GroupTotalSales($code)
    {
      $affiliates = Affiliate::select(DB::raw('SUM(grand_total - processing_fee - shipping_fee) as totalPurchase'))
                             ->join('transactions as t', 't.user_id', 'affiliates.affiliate_id')
                             ->where('affiliates.user_id', $code)
                             ->where('affiliates.sort_level', '<=', '3')
                             ->where('t.status', '1')
                             ->first();

      $totalPurchase = $affiliates->totalPurchase;

      return  !empty($totalPurchase) ? $totalPurchase : '0.00';
    }

    public function PersonalDailySales($code)
    {
      $ownTransaction = Transaction::select(DB::raw('SUM(grand_total - processing_fee - shipping_fee) as totalPurchase'))
                                   ->where('user_id', $code)
                                   ->where('status', '1')
                                   ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), date('Y-m-d'))
                                   ->first();

      $totalPurchase = $ownTransaction->totalPurchase;

      return !empty($totalPurchase) ? $totalPurchase : '0.00';
    }

    public function PersonalMonthlySales($code)
    {
      $ownTransaction = Transaction::select(DB::raw('SUM(grand_total - processing_fee - shipping_fee) as totalPurchase'))
                                   ->where('user_id', $code)
                                   ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), date('Y-m'))
                                   ->where('status', '1')
                                   ->first();

      $totalPurchase = $ownTransaction->totalPurchase;

      return  !empty($totalPurchase) ? $totalPurchase : '0.00';
    }

    public function PersonalYearlySales($code)
    {
        $ownTransaction = Transaction::select(DB::raw('SUM(grand_total - processing_fee - shipping_fee) as totalPurchase'))
                                   ->where('user_id', $code)
                                   ->where(DB::raw('DATE_FORMAT(created_at, "%Y")'), date('Y'))
                                   ->where('status', '1')
                                   ->first();

        $totalPurchase = $ownTransaction->totalPurchase;

        return  !empty($totalPurchase) ? $totalPurchase : '0.00';
    }

    public function PersonalTotalSales($code)
    {
        $ownTransaction = Transaction::select(DB::raw('SUM(grand_total - processing_fee - shipping_fee) as totalPurchase'))
                                   ->where('user_id', $code)
                                   ->where('status', '1')
                                   ->first();

        $totalPurchase = $ownTransaction->totalPurchase;

        return  !empty($totalPurchase) ? $totalPurchase : '0.00';
    }

    public function TotalSales($code)
    {
      $ownTransaction = Transaction::select(DB::raw('SUM(grand_total - processing_fee - shipping_fee) as totalPurchase'))
                                   ->where('user_id', $code)
                                   ->where('status', '1')
                                   ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), date('Y-m-d'))
                                   ->first();

      $affiliates = Affiliate::select(DB::raw('SUM(grand_total - processing_fee - shipping_fee) as totalPurchase'))
                             ->join('transactions as t', 't.user_id', 'affiliates.affiliate_id')
                             ->where('affiliates.user_id', $code)
                             ->where('affiliates.sort_level', '<=', '3')
                             ->where(DB::raw('DATE_FORMAT(t.created_at, "%Y-%m-%d")'), date('Y-m-d'))
                             ->where('t.status', '1')
                             ->first();

      $totalPurchase = $ownTransaction->totalPurchase + $affiliates->totalPurchase;

      return  !empty($totalPurchase) ? $totalPurchase : '0.00';
    }

    public function AllTotalSales($code)
    {
      $ownTransaction = Transaction::select(DB::raw('SUM(grand_total - processing_fee - shipping_fee) as totalPurchase'))
                                   ->where('user_id', $code)
                                   ->where('status', '1')
                                   ->first();

      $affiliates = Affiliate::select(DB::raw('SUM(grand_total - processing_fee - shipping_fee) as totalPurchase'))
                             ->join('transactions as t', 't.user_id', 'affiliates.affiliate_id')
                             ->where('affiliates.user_id', $code)
                             ->where('affiliates.sort_level', '<=', '3')
                             ->where('t.status', '1')
                             ->first();

      $totalPurchase = $ownTransaction->totalPurchase + $affiliates->totalPurchase;

      return  !empty($totalPurchase) ? $totalPurchase : '0.00';
    }

    public function customerTotalTodaySales($code)
    {
        $transaction = Transaction::select(DB::raw('SUM(grand_total - processing_fee - shipping_fee) as totalPurchase'))
                                ->where('user_id', $code)
                                ->where('status', '1')
                                ->first();

        return  !empty($transaction->totalPurchase) ? number_format($transaction->totalPurchase, 2) : '0.00';
    }

    public function GetOwnTotalAffiliates($code)
    {
      $affiliate1 = Affiliate::select(DB::raw('COUNT(affiliates.id) AS TotalAffiliates'))
                            ->join('agents as m', 'm.code', 'affiliates.affiliate_id')
                            ->where('user_id', $code)
                            ->where('affiliates.sort_level', '1')
                            ->where('m.status', '1')
                            ->first();

      $affiliate2 = Affiliate::select(DB::raw('COUNT(affiliates.id) AS TotalAffiliates'))
                            ->join('agents as m', 'm.code', 'affiliates.affiliate_id')
                            ->where('user_id', $code)
                            ->where('affiliates.sort_level', '2')
                            ->where('m.status', '1')
                            ->first();
                            
      $affiliate3 = Affiliate::select(DB::raw('COUNT(affiliates.id) AS TotalAffiliates'))
                            ->join('agents as m', 'm.code', 'affiliates.affiliate_id')
                            ->where('user_id', $code)
                            ->where('affiliates.sort_level', '3')
                            ->where('m.status', '1')
                            ->first();
                            
      $affiliate_2_1 = Affiliate::select(DB::raw('COUNT(affiliates.id) AS TotalAffiliates'))
                                ->join('agents as m', 'm.code', 'affiliates.affiliate_id')
                                ->where('user_id', $code)
                                ->where('m.lvl', '2')
                                ->where('affiliates.sort_level', '1')
                                ->where('m.status', '1')
                                ->first();
                            
      $affiliate_2_2 = Affiliate::select(DB::raw('COUNT(affiliates.id) AS TotalAffiliates'))
                                ->join('agents as m', 'm.code', 'affiliates.affiliate_id')
                                ->where('user_id', $code)
                                ->where('m.lvl', '2')
                                ->where('affiliates.sort_level', '2')
                                ->where('m.status', '1')
                                ->first();
                            
      $affiliate_2_3 = Affiliate::select(DB::raw('COUNT(affiliates.id) AS TotalAffiliates'))
                                ->join('agents as m', 'm.code', 'affiliates.affiliate_id')
                                ->where('user_id', $code)
                                ->where('m.lvl', '2')
                                ->where('affiliates.sort_level', '3')
                                ->where('m.status', '1')
                                ->first();

      $affiliate_3_1 = Affiliate::select(DB::raw('COUNT(affiliates.id) AS TotalAffiliates'))
                                ->join('agents as m', 'm.code', 'affiliates.affiliate_id')
                                ->where('user_id', $code)
                                ->where('affiliates.sort_level', '1')
                                ->where('m.status', '1')
                                ->first();

      $affiliate_3_2 = Affiliate::select(DB::raw('COUNT(affiliates.id) AS TotalAffiliates'))
                                ->join('agents as m', 'm.code', 'affiliates.affiliate_id')
                                ->where('user_id', $code)
                                ->where('affiliates.sort_level', '2')
                                ->where('m.status', '1')
                                ->first();

      $affiliate_3_3 = Affiliate::select(DB::raw('COUNT(affiliates.id) AS TotalAffiliates'))
                                ->join('agents as m', 'm.code', 'affiliates.affiliate_id')
                                ->where('user_id', $code)
                                ->where('affiliates.sort_level', '3')
                                ->where('m.status', '1')
                                ->first();

      return array($affiliate1->TotalAffiliates, $affiliate2->TotalAffiliates, $affiliate3->TotalAffiliates,
                   $affiliate_2_1->TotalAffiliates, $affiliate_2_2->TotalAffiliates, $affiliate_2_3->TotalAffiliates,
                  $affiliate_3_1->TotalAffiliates, $affiliate_3_2->TotalAffiliates, $affiliate_3_3->TotalAffiliates);
    }

    public function totalAgent($code)
    {
        $affiliate_1 = Affiliate::select(DB::raw('COUNT(affiliates.id) AS TotalAffiliates'))
                                ->join('agents as m', 'm.code', 'affiliates.affiliate_id')
                                ->where('user_id', $code)
                                ->where('m.lvl', '1')
                                ->where('affiliates.sort_level', '<=', '3')
                                ->where('m.status', '1')
                                ->first();

        $affiliate_2 = Affiliate::select(DB::raw('COUNT(affiliates.id) AS TotalAffiliates'))
                                ->join('agents as m', 'm.code', 'affiliates.affiliate_id')
                                ->where('user_id', $code)
                                ->where('m.lvl', '2')
                                ->where('affiliates.sort_level', '<=', '3')
                                ->where('m.status', '1')
                                ->first();

        return array($affiliate_1->TotalAffiliates, $affiliate_2->TotalAffiliates);
    }

    public function totalMonthYearAgent($code)
    {
        $affiliate_month_1 = Affiliate::select(DB::raw('COUNT(affiliates.id) AS TotalAffiliates'))
                                ->join('agents as m', 'm.code', 'affiliates.affiliate_id')
                                ->where('user_id', $code)
                                ->where('m.lvl', '1')
                                ->where('affiliates.sort_level', '<=', '3')
                                ->where('m.status', '1')
                                ->where(DB::raw('DATE_FORMAT(m.created_at, "%Y-%m")'), date('Y-m'))
                                ->first();

        $affiliate_year_1 = Affiliate::select(DB::raw('COUNT(affiliates.id) AS TotalAffiliates'))
                                ->join('agents as m', 'm.code', 'affiliates.affiliate_id')
                                ->where('user_id', $code)
                                ->where('m.lvl', '1')
                                ->where('affiliates.sort_level', '<=', '3')
                                ->where('m.status', '1')
                                ->where(DB::raw('DATE_FORMAT(m.created_at, "%Y")'), date('Y'))
                                ->first();


        $affiliate_day_2 = Affiliate::select(DB::raw('COUNT(affiliates.id) AS TotalAffiliates'))
                                ->join('agents as m', 'm.code', 'affiliates.affiliate_id')
                                ->where('user_id', $code)
                                ->where('m.lvl', '2')
                                ->where('affiliates.sort_level', '<=', '3')
                                ->where(DB::raw('DATE_FORMAT(m.created_at, "%Y-%m-%d")'), date('Y-m-d'))
                                ->first();

        $affiliate_month_2 = Affiliate::select(DB::raw('COUNT(affiliates.id) AS TotalAffiliates'))
                                ->join('agents as m', 'm.code', 'affiliates.affiliate_id')
                                ->where('user_id', $code)
                                ->where('m.lvl', '2')
                                ->where('affiliates.sort_level', '<=', '3')
                                ->where(DB::raw('DATE_FORMAT(m.created_at, "%Y-%m")'), date('Y-m'))
                                ->first();

        $affiliate_year_2 = Affiliate::select(DB::raw('COUNT(affiliates.id) AS TotalAffiliates'))
                                ->join('agents as m', 'm.code', 'affiliates.affiliate_id')
                                ->where('user_id', $code)
                                ->where('m.lvl', '2')
                                ->where('affiliates.sort_level', '<=', '3')
                                ->where(DB::raw('DATE_FORMAT(m.created_at, "%Y")'), date('Y'))
                                ->first();


        return array($affiliate_month_1->TotalAffiliates, $affiliate_year_1->TotalAffiliates,
                     $affiliate_month_2->TotalAffiliates, $affiliate_year_2->TotalAffiliates,
                     $affiliate_day_2->TotalAffiliates);
    }

    public function GetTotalAffiliates($code)
    {
        $affiliate = Agent::select(DB::raw('COUNT(id) AS TotalAffiliates'))
                              ->where('master_id', $code)
                              ->where('status', '1')
                              ->first();

        $affiliate2 = Agent::select(DB::raw('COUNT(d.id) AS TotalAffiliates2'))
                              ->join('agents AS d', 'd.master_id', 'agents.code')
                              ->where('agents.master_id', $code)
                              ->where('agents.status', '1')
                              ->where('d.status', '1')
                              ->first();

        return  $affiliate->TotalAffiliates + $affiliate2->TotalAffiliates2;
    }

    public function GetTodayTotalAffiliates($code)
    {
        $affiliate = Agent::select(DB::raw('COUNT(id) AS TotalAffiliates'))
                              ->where('master_id', $code)
                              ->where('status', '1')
                              ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), date('Y-m-d'))
                              ->first();

        $affiliate2 = Agent::select(DB::raw('COUNT(d.id) AS TotalAffiliates2'))
                              ->join('agents AS d', 'd.master_id', 'agents.code')
                              ->where('agents.master_id', $code)
                              ->where('agents.status', '1')
                              ->where('d.status', '1')
                              ->where(DB::raw('DATE_FORMAT(agents.created_at, "%Y-%m-%d")'), date('Y-m-d'))
                              ->first();

        return  $affiliate->TotalAffiliates + $affiliate2->TotalAffiliates2;
    }

    public function GetSelectedUserTotalAffiliates($code)
    {
        $affiliate = Agent::select(DB::raw('COUNT(id) AS TotalAffiliates'))
                              ->where('master_id', $code)
                              ->where('status', '1')
                              ->first();

        $affiliate2 = Affiliate::select(DB::raw('COUNT(m.id) AS TotalAffiliates2'))
                              ->join('agents as m', 'm.code', 'affiliates.affiliate_id')
                              ->where('m.status', '1')
                              ->where('affiliates.user_id', $code)
                              ->whereIn('sort_level', ['2', '3'])
                              ->first();

        return  $affiliate->TotalAffiliates + $affiliate2->TotalAffiliates2;
    }

    public function GetSelectedUserMonthlyTotalAffiliates($code)
    {
        $affiliate = User::select(DB::raw('COUNT(id) AS TotalAffiliates'))
                              ->where('master_id', $code)
                              ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), date('Y-m'))
                              ->first();

        $affiliate2 = User::select(DB::raw('COUNT(d.id) AS TotalAffiliates2'))
                              ->join('users AS d', 'd.master_id', 'users.code')
                              ->where('users.master_id', $code)
                              ->where(DB::raw('DATE_FORMAT(users.created_at, "%Y-%m")'), date('Y-m'))
                              ->first();

        return  $affiliate->TotalAffiliates + $affiliate2->TotalAffiliates2;
    }

    public function GetSelectedUserDailyTotalAffiliates($code)
    {
        $affiliate = Agent::select(DB::raw('COUNT(id) AS TotalAffiliates'))
                              ->where('master_id', $code)
                              ->where('status', '1')
                              ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), date('Y-m-d'))
                              ->first();

        $affiliate2 = Affiliate::select(DB::raw('COUNT(m.id) AS TotalAffiliates2'))
                              ->join('agents as m', 'm.code', 'affiliates.affiliate_id')
                              ->where('m.status', '1')
                              ->where('affiliates.user_id', $code)
                              ->where(DB::raw('DATE_FORMAT(m.created_at, "%Y-%m-%d")'), date('Y-m-d'))
                              ->whereIn('sort_level', ['2', '3'])
                              ->first();
        

        return  $affiliate->TotalAffiliates + $affiliate2->TotalAffiliates2;
    }

    public function bank_account()
    {
        $lvl = "";
        $partner_lvl = "";
        $city_agent = "";
        $state_agent = "";
        $agentLVL = AgentLevel::find(Auth::user()->lvl);
        $PartnerAgentLVL = PartnerLevel::find(Auth::user()->lvl);

        if(!empty($agentLVL->id)){
            $lvl = $agentLVL->agent_lvl;
        }

        if(!empty($PartnerAgentLVL->id)){
            $partner_lvl = $PartnerAgentLVL->partner_lvl." (".$PartnerAgentLVL->partner_lvl_cn.")";
        }

        if(!empty(Auth::user()->city_agent)){
            $get_city = City::find(Auth::user()->city_agent);
            $city_agent = $get_city->city_name;
        }

        if(!empty(Auth::user()->state_agent)){
            $get_state = State::find(Auth::user()->state_agent);
            $state_agent = $get_state->name;
        }

        

        $getMUpline = Agent::where('code', Auth::user()->master_id)->first();
        $getAUpline = Admin::where('code', Auth::user()->master_id)->first();
        $getUUpline = User::where('code', Auth::user()->master_id)->first();

        $upline_name = "";
        $upline_code = "";
        if(!empty($getMUpline->id)){
          $upline_name = $getMUpline->f_name.' '.$getMUpline->l_name;
          $upline_code = $getMUpline->display_code.$getMUpline->display_running_no;
        }

        if(!empty($getAUpline->id)){
          $upline_name = $getAUpline->f_name.' '.$getAUpline->l_name;
          $upline_code = $getAUpline->display_code.$getAUpline->display_running_no;
        }

        if(!empty($getUUpline->id)){
          $upline_name = $getUUpline->f_name.' '.$getUUpline->l_name;
          $upline_code = $getUUpline->display_code.$getUUpline->display_running_no;
        }

        $upgrade_record = AgentLevelRecord::where('user_id', Auth::user()->code)->get();

        $banks = PaymentBank::where('status', '1')->get();

        $aff_joined_date = [];
        if(Auth::guard('agent') || Auth::guard('admin')){
            $allAffiliateRecord = User::where('status', '3')->get();
            $allMerchants = Agent::where('status', '1')->get();

            foreach($allAffiliateRecord as $affiliate_record){
                $ic_record = explode('-', $affiliate_record->ic);
                foreach($allMerchants as $affiliate){
                    if($affiliate->ic == $ic_record[0]){
                        $aff_joined_date[$affiliate->code] = $affiliate_record;
                    }
                }
            }
        }

        $own_display_code = Auth::user()->display_code.Auth::user()->display_running_no;
        if(!empty(Auth::user()->relegated_from_agent)){
            $original_agent = Agent::where('code', Auth::user()->relegated_from_agent)
                                    ->where('status', 55)
                                    ->first();

            if(!empty($original_agent->id)){
                $own_display_code = $original_agent->display_code.$original_agent->display_running_no;
            }
        }

        return view('frontend.bank_account', ['lvl'=>$lvl,
                                              'upline_name'=>$upline_name,
                                              'upline_code'=>$upline_code,
                                              'banks'=>$banks, 
                                              'upgrade_record'=>$upgrade_record,
                                              'own_display_code'=>$own_display_code,
                                              'partner_lvl'=>$partner_lvl,
                                              'city_agent'=>$city_agent,
                                              'state_agent'=>$state_agent], 
                                              compact('aff_joined_date'));
    }

    public function bank_account_edit($id)
    {
      $bank = BankAccount::find($id);
      if(empty($bank->id) || $bank->user_id != Auth::user()->code){
        abort(404);
      }

        $lvl = "";
        $partner_lvl = "";
        $city_agent = "";
        $state_agent = "";
        $agentLVL = AgentLevel::find(Auth::user()->lvl);
        $PartnerAgentLVL = PartnerLevel::find(Auth::user()->lvl);

        if(!empty($agentLVL->id)){
            $lvl = $agentLVL->agent_lvl;
        }

        if(!empty($PartnerAgentLVL->id)){
            $partner_lvl = $PartnerAgentLVL->partner_lvl." (".$PartnerAgentLVL->partner_lvl_cn.")";
        }

        if(!empty(Auth::user()->city_agent)){
            $get_city = City::find(Auth::user()->city_agent);
            $city_agent = $get_city->city_name;
        }

        if(!empty(Auth::user()->state_agent)){
            $get_state = State::find(Auth::user()->state_agent);
            $state_agent = $get_state->name;
        }

      $banks = PaymentBank::where('status', '1')->get();

      $upgrade_record = AgentLevelRecord::where('user_id', Auth::user()->code)->get();

      $own_display_code = Auth::user()->display_code.Auth::user()->display_running_no;
        if(!empty(Auth::user()->relegated_from_agent)){
            $original_agent = Agent::where('code', Auth::user()->relegated_from_agent)
                                    ->where('status', 55)
                                    ->first();

            if(!empty($original_agent->id)){
                $own_display_code = $original_agent->display_code.$original_agent->display_running_no;
            }
        }

      return view('frontend.bank_account', ['bank'=>$bank, 
                                            'lvl'=>$lvl, 
                                            'banks'=>$banks, 
                                            'upgrade_record'=>$upgrade_record,
                                            'own_display_code'=>$own_display_code,
                                            'partner_lvl'=>$partner_lvl,
                                            'city_agent'=>$city_agent,
                                            'state_agent'=>$state_agent]);
    }

    public function bank_account_delete($id)
    {
        $bank = BankAccount::find($id);
        if(empty($bank->id) || $bank->user_id != Auth::user()->code){
          abort(404);
        }

        if($bank->default_banks != '1'){
          $bank = $bank->update(['status'=>'3']);
        }else{
          $other_bank_acc = BankAccount::where('status', '1')
                                      ->where('id', '!=', $id)
                                      ->where('user_id', Auth::user()->code)
                                      ->first();

          if(!empty($other_bank_acc)){
            $other_bank_acc = $other_bank_acc->update(['default_banks'=>'1']);
            $bank = $bank->update(['status'=>'3', 'default_banks'=>null]);
          }else{
            Toastr::info('Before deleting a bank account, please create a new bank account!');
            return redirect()->route('wallet');
          }
        }

        Toastr::info('Deleted');
        return redirect()->route('wallet');
    }

    public function bank_account_save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bank_name' => 'required',
            'bank_account' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }

        $input = $request->all();
        $input['user_id'] = Auth::user()->code;
        $count = BankAccount::select(DB::raw('COUNT(id) AS totalBanks'))
                            ->where('user_id', Auth::user()->code)
                            ->first();
        if(empty($count->totalBanks)){
          $input['default_banks'] = '1';
        }

        if(!empty($request->bid)){
          $bank_acc = BankAccount::find($request->bid);
          $bank_acc = $bank_acc->update($input);
        }else{
          $bank_acc = BankAccount::create($input);          
        }


        Toastr::success("Saved");
        return redirect()->route('wallet');  
    }

    public function save_wallet(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }

        $amount = preg_replace("/[^0-9\.]/", '', $request->amount);
        
        if(number_format($amount, 2, "", ".") <= 0){
            return Redirect::back()->withInput($request->all())->withErrors('Please key in correct amount');
        }
        // return (float)$amount.' - '.(float)$request->wallet_balance;
        if(floatval($this->GetCashWalletBalance(Auth::user()->code)) < floatval($amount)){
            // return 123;
            return Redirect::back()->withInput($request->all())->withErrors('Insufficient balance');
        }

        if($amount < 100){
            // return 123;
            return Redirect::back()->withInput($request->all())->withErrors('Minimum Withdrawal Amount as RM 100.00');
        }

        $check = WithdrawalTransaction::where('user_id', Auth::user()->code)
                                      ->where('status', '99')
                                      ->first();
        if(!empty($check->id)){
            return Redirect::back()->withInput($request->all())->withErrors('You have a new withdrawal request that has not been approved / rejected by the film, please wait for the administrator to review');
        }

        $website_setting = WebsiteSetting::find(1);

        $charges = 1;
        if(!empty($website_setting->withdrawal_charges)){
            $charges = $website_setting->withdrawal_charges;
        }

        $input = $request->all();
        $input['status'] = '99';
        $defaultBank = BankAccount::where('default_banks', '1')
                                  ->where('user_id', Auth::user()->code)
                                  ->first();
        if(!empty($defaultBank->id)){
            $input['bank_name'] = $defaultBank->bank_name;
            $input['bank_holder_name'] = Auth::user()->f_name.' '.Auth::user()->l_name;
            $input['bank_account'] = $defaultBank->bank_account;
        }
        $input['user_id'] = Auth::user()->code;
        $input['amount'] = $amount;
        $input['actual_amount'] = $amount - ($amount * $charges /100);
        $input['company_charges'] = $charges;
        $input['withdrawal_no'] = $this->GenerateWithdrawalTransactionNo();

        $authorise_merchant = !empty($_COOKIE['vmerchant']) ? $_COOKIE['vmerchant'] : '';
        $get_authorise_status = GlobalController::check_autorize_status($authorise_merchant);
        if($get_authorise_status['status'] == 1){
        $input['merchant_id'] = !empty($get_authorise_status['result']['code']) ? $get_authorise_status['result']['code'] : '';
        }

        $withdrawal = WithdrawalTransaction::create($input);
      
        Toastr::success("Withdrawal Submited, Waiting Admin For Approval");
        return redirect()->route('wallet');
    }

    public static function GetProductWalletBalance($buyerCode)
    {
        
        $balance = TopupTransaction::select(DB::raw('SUM(amount) as totalBalance'))
                                      ->where('user_id', $buyerCode)
                                      ->where('status', '1')
                                      ->first();

        $transaction = Transaction::select(DB::raw('SUM(grand_total) as totalPurchase'))
                                  ->where('user_id', $buyerCode)
                                  ->where('status', '1')
                                  ->where('mall', '2')
                                  ->first();

        $qr_payment = TransactionQrPayment::select(DB::raw('SUM(amount) AS totalPayment'))
                                          ->where('user_id', $buyerCode)
                                          ->where('status', '1')
                                          ->first();

        $adjustIn = AdjustTopupWallet::select(DB::raw('SUM(amount) as totalAdjustIn'))
                                    ->where('user_id', $buyerCode)
                                    ->where('type', '1')
                                    ->first();

        $adjustOut = AdjustTopupWallet::select(DB::raw('SUM(amount) as totalAdjustOut'))
                                    ->where('user_id', $buyerCode)
                                    ->where('type', '2')
                                    ->first();

        $joining_fee = JoiningRecord::select(DB::raw('SUM(amount + IF(bonus_amount > 0, bonus_amount, 0)) as totalJoiningFee'))
                                        ->where('status', 1)
                                        ->where('user_id', $buyerCode)
                                        ->first();

        $topup_bonus = AffiliateCommission::select(DB::raw('SUM(comm_amount) as totalBalance'))
                                          ->where('user_id', $buyerCode)
                                          ->where('status', '1')
                                          ->where('type', '55')
                                          ->first();
        
        $totalBalance = $balance->totalBalance - $transaction->totalPurchase - $qr_payment->totalPayment + $adjustIn->totalAdjustIn - $adjustOut->totalAdjustOut + $joining_fee->totalJoiningFee + $topup_bonus->totalBalance;
        

        return $totalBalance;
    }


    public static function GetCashWalletBalance($buyerCode)
    {
        
         $balance = AffiliateCommission::select(DB::raw('SUM(comm_amount) as totalBalance'))
                                      ->where('user_id', $buyerCode)
                                      ->where('status', '1')
                                      ->first();

        $withdrawal = WithdrawalTransaction::select(DB::raw('SUM(amount) as totalWithdrawal'))
                                             ->where('user_id', $buyerCode)
                                             ->whereIn('status', ['1', '99'])
                                             ->first();

        $transaction = Transaction::select(DB::raw('SUM(grand_total) as totalPurchase'))
                                  ->where('user_id', $buyerCode)
                                  ->where('status', '1')
                                  ->where('mall', '1')
                                  ->first();

        $adjustIn = AdjustCashWallet::select(DB::raw('SUM(amount) as totalAdjustIn'))
                                    ->where('user_id', $buyerCode)
                                    ->where('type', '1')
                                    ->first();

        $adjustOut = AdjustCashWallet::select(DB::raw('SUM(amount) as totalAdjustOut'))
                                    ->where('user_id', $buyerCode)
                                    ->where('type', '2')
                                    ->first();

        $transfer_cash_to_topup = AdjustCashToTopup::select(DB::raw('SUM(amount) as totalBalance'))
                                                   ->where('user_by', $buyerCode)
                                                   ->first();

        $totalBalance = 0;
        
        $totalBalance = $balance->totalBalance - $withdrawal->totalWithdrawal - $transaction->totalPurchase + $adjustIn->totalAdjustIn - $adjustOut->totalAdjustOut - $transfer_cash_to_topup->totalBalance;
        

        return $totalBalance;   
    }

    public function GetPVWallet($buyerCode)
    {
        $transaction = Transaction::select(DB::raw('SUM(d.get_point * d.quantity) as totalPoint'))
                                  ->join('transaction_details as d', 'd.transaction_id', 'transactions.id')
                                  ->where('transactions.user_id', $buyerCode)
                                  ->where('transactions.status', '1')
                                  ->first();

        $transaction_purchase = Transaction::select(DB::raw('SUM(grand_total) as totalPoint'))
                                  ->where('transactions.user_id', $buyerCode)
                                  ->where('transactions.status', '1')
                                  ->where('transactions.pv_purchase', '1')
                                  ->first();

        return $transaction->totalPoint - $transaction_purchase->totalPoint;
    }

    public function GetLastMonthCashWalletBalance()
    {
        if(!empty(Auth::guard('admin')->check())){
            $buyerCode = Auth::guard('admin')->user()->code;
        }elseif(!empty(Auth::guard('agent')->check())){
            $buyerCode = Auth::guard('agent')->user()->code;
        }elseif(!empty(Auth::guard('web')->check())){
            $buyerCode = Auth::guard('web')->user()->code;
        }else{
          if(empty($_COOKIE['new_guest'])){
            $buyerCode = setcookie('new_guest', strtotime(date('Y-m-d H:i:s')).rand(100000, 999999), time() + (86400 * 30), "/");
          }else{
            $buyerCode = $_COOKIE['new_guest'];
          }
        }
        
        // $balance = AffiliateCommission::select(DB::raw('SUM(comm_amount) as totalBalance'))
        //                               ->where('user_id', $buyerCode)
        //                               ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), date("Y-m" ,strtotime("last month")))
        //                               ->where('status', '1')
        //                               ->first();

        $withdrawal = WithdrawalTransaction::select(DB::raw('SUM(amount) as totalWithdrawal'))
                                             ->where('user_id', $buyerCode)
                                             ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), date("Y-m"))
                                             ->where('status', '1')
                                             ->first();

        $totalBalance = 0;
        
        $totalBalance = $withdrawal->totalWithdrawal;
        

        return $totalBalance;
    }
    public function GetPendingWithdrawalAmount()
    {
        if(!empty(Auth::guard('admin')->check())){
            $buyerCode = Auth::guard('admin')->user()->code;
        }elseif(!empty(Auth::guard('agent')->check())){
            $buyerCode = Auth::guard('agent')->user()->code;
        }elseif(!empty(Auth::guard('web')->check())){
            $buyerCode = Auth::guard('web')->user()->code;
        }else{
          if(empty($_COOKIE['new_guest'])){
            $buyerCode = setcookie('new_guest', strtotime(date('Y-m-d H:i:s')).rand(100000, 999999), time() + (86400 * 30), "/");
          }else{
            $buyerCode = $_COOKIE['new_guest'];
          }
        }

        $withdrawal = WithdrawalTransaction::select(DB::raw('SUM(amount) as totalWithdrawal'))
                                             ->where('user_id', $buyerCode)
                                             ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), date("Y-m"))
                                             ->where('status', '99')
                                             ->first();

        $totalBalance = 0;
        
        $totalBalance = $withdrawal->totalWithdrawal;
        

        return $totalBalance;                
    }

    public function getTotalWallet()
    {
        $total = AffiliateCommission::select(DB::raw('SUM(comm_amount) as totalBalance'))
                                      ->where('user_id', Auth::user()->code)
                                      ->where('status', '1')
                                      ->first();
                                      
        $topup = TopupTransaction::select(DB::raw('SUM(amount) as TotalTopup'))
                                 ->where('user_id', Auth::user()->code)
                                 ->where('status', '1')
                                 ->first();

        return  $total->totalBalance + $topup->TotalTopup;
    }

    public function order_list()
    {
        $lvl = "";
        $partner_lvl = "";
        $city_agent = "";
        $state_agent = "";
        $agentLVL = AgentLevel::find(Auth::user()->lvl);
        $PartnerAgentLVL = PartnerLevel::find(Auth::user()->lvl);

        if(!empty($agentLVL->id)){
            $lvl = $agentLVL->agent_lvl;
        }

        if(!empty($PartnerAgentLVL->id)){
            $partner_lvl = $PartnerAgentLVL->partner_lvl." (".$PartnerAgentLVL->partner_lvl_cn.")";
        }

        if(!empty(Auth::user()->city_agent)){
            $get_city = City::find(Auth::user()->city_agent);
            $city_agent = $get_city->city_name;
        }

        if(!empty(Auth::user()->state_agent)){
            $get_state = State::find(Auth::user()->state_agent);
            $state_agent = $get_state->name;
        }

        $transactions = Transaction::where('user_id', Auth::user()->code)->orderBy('created_at', 'desc')->get();
        $details = [];
        foreach($transactions as $transaction){
           $details[$transaction->id] = TransactionDetail::where('transaction_id', $transaction->id)->get();
        }

        return view('frontend.order', ['transactions'=>$transactions, 'lvl'=>$lvl], compact('details'));
    }

    public function order_detail($no)
    {
        $transaction = Transaction::select('transactions.*',
                                           's.name as state_name', 'cod.address as cod_address_name', 'cod.address_desc', 'pc.f_name AS pickup_f_name', 'c.city_name',
                                           'pc.email as pickup_email', 'pc.phone as pickup_phone',
                                           'tc.country_name')
                                  ->leftJoin('states as s', 's.id', 'transactions.state')
                                  ->leftJoin('tbl_countries as tc', 'tc.country_id', 'transactions.country')
                                  ->leftJoin('cities as c', 'c.id', 'transactions.city')
                                  ->leftJoin('pickup_contacts as pc', 'pc.transaction_id', 'transactions.id')
                                  ->leftJoin('cod_addresses as cod', 'cod.id', 'transactions.cod_address')
                                  ->where('transaction_no', $no)
                                  ->first();

        if(empty($transaction->id)){
            abort(404);
        }

        $details = TransactionDetail::select('transaction_details.*', 'i.image as product_image')
                                    ->leftJoin('product_images as i', 'i.product_id', 'transaction_details.product_id')
                                    ->where('transaction_id', $transaction->id)
                                    ->groupBy('transaction_details.id')
                                    ->get();

        return view('frontend.order_detail', ['transaction'=>$transaction, 'details'=>$details]);
    }

    public function wish_list()
    {
        $leftJoin = DB::raw("(SELECT * FROM product_images ORDER BY created_at ASC) AS i");
  
        $favourites = Favourite::select('p.*', 'i.image', DB::raw('COALESCE(special_price, price) AS actual_price'))
                               ->join('products AS p', 'p.id', 'favourites.product_id')
                               ->leftJoin($leftJoin, function($join) {
                                  $join->on('p.id', '=', 'i.product_id');
                               })
                               ->where('user_id', Auth::user()->code)
                               ->groupBy('p.id')
                               ->get();
        $stockBalance = [];
        foreach($favourites as $favourite){
            $stockBalance[$favourite->id] = $this->BalanceQuantity($favourite->id);
        }

        $lvl = "";
        $partner_lvl = "";
        $city_agent = "";
        $state_agent = "";
        $agentLVL = AgentLevel::find(Auth::user()->lvl);
        $PartnerAgentLVL = PartnerLevel::find(Auth::user()->lvl);

        if(!empty($agentLVL->id)){
            $lvl = $agentLVL->agent_lvl;
        }

        if(!empty($PartnerAgentLVL->id)){
            $partner_lvl = $PartnerAgentLVL->partner_lvl." (".$PartnerAgentLVL->partner_lvl_cn.")";
        }

        if(!empty(Auth::user()->city_agent)){
            $get_city = City::find(Auth::user()->city_agent);
            $city_agent = $get_city->city_name;
        }

        if(!empty(Auth::user()->state_agent)){
            $get_state = State::find(Auth::user()->state_agent);
            $state_agent = $get_state->name;
        }

        

        $getMUpline = Agent::where('code', Auth::user()->master_id)->first();
        $getAUpline = Admin::where('code', Auth::user()->master_id)->first();
        $getUUpline = User::where('code', Auth::user()->master_id)->first();

        $upline_name = "";
        $upline_code = "";
        if(!empty($getMUpline->id)){
          $upline_name = $getMUpline->f_name.' '.$getMUpline->l_name;
          $upline_code = $getMUpline->display_code.$getMUpline->display_running_no;
        }

        if(!empty($getAUpline->id)){
          $upline_name = $getAUpline->f_name.' '.$getAUpline->l_name;
          $upline_code = $getAUpline->display_code.$getAUpline->display_running_no;
        }

        if(!empty($getUUpline->id)){
          $upline_name = $getUUpline->f_name.' '.$getUUpline->l_name;
          $upline_code = $getUUpline->display_code.$getUUpline->display_running_no;
        }

        $upgrade_record = AgentLevelRecord::where('user_id', Auth::user()->code)->get();

        $aff_joined_date = [];
        if(Auth::guard('agent') || Auth::guard('admin')){
            $allAffiliateRecord = User::where('status', '3')->get();
            $allMerchants = Agent::where('status', '1')->get();

            foreach($allAffiliateRecord as $affiliate_record){
                $ic_record = explode('-', $affiliate_record->ic);
                foreach($allMerchants as $affiliate){
                    if($affiliate->ic == $ic_record[0]){
                        $aff_joined_date[$affiliate->code] = $affiliate_record;
                    }
                }
            }
        }

        $own_display_code = Auth::user()->display_code.Auth::user()->display_running_no;
        if(!empty(Auth::user()->relegated_from_agent)){
            $original_agent = Agent::where('code', Auth::user()->relegated_from_agent)
                                    ->where('status', 55)
                                    ->first();

            if(!empty($original_agent->id)){
                $own_display_code = $original_agent->display_code.$original_agent->display_running_no;
            }
        }

        return view('frontend.wish_list', ['favourites'=>$favourites, 
                                           'lvl'=>$lvl,
                                           'upline_name'=>$upline_name,
                                           'upline_code'=>$upline_code, 
                                           'upgrade_record'=>$upgrade_record, 
                                           'own_display_code'=>$own_display_code,
                                           'partner_lvl'=>$partner_lvl,
                                           'city_agent'=>$city_agent,
                                           'state_agent'=>$state_agent], 
                                           compact('stockBalance', 
                                                   'aff_joined_date'));
    }

    public function changePassword()
    {
        $getMUpline = Agent::where('code', Auth::user()->master_id)->first();
        $getAUpline = Admin::where('code', Auth::user()->master_id)->first();
        $getUUpline = User::where('code', Auth::user()->master_id)->first();

        $upline_name = "";
        $upline_code = "";
        if(!empty($getMUpline->id)){
          $upline_name = $getMUpline->f_name.' '.$getMUpline->l_name;
          $upline_code = $getMUpline->display_code.$getMUpline->display_running_no;
        }

        if(!empty($getAUpline->id)){
          $upline_name = $getAUpline->f_name.' '.$getAUpline->l_name;
          $upline_code = $getAUpline->display_code.$getAUpline->display_running_no;
        }

        if(!empty($getUUpline->id)){
          $upline_name = $getUUpline->f_name.' '.$getUUpline->l_name;
          $upline_code = $getUUpline->display_code.$getUUpline->display_running_no;
        }

        $lvl = "";
        $partner_lvl = "";
        $city_agent = "";
        $state_agent = "";
        $agentLVL = AgentLevel::find(Auth::user()->lvl);
        $PartnerAgentLVL = PartnerLevel::find(Auth::user()->lvl);

        if(!empty($agentLVL->id)){
            $lvl = $agentLVL->agent_lvl;
        }

        if(!empty($PartnerAgentLVL->id)){
            $partner_lvl = $PartnerAgentLVL->partner_lvl." (".$PartnerAgentLVL->partner_lvl_cn.")";
        }

        if(!empty(Auth::user()->city_agent)){
            $get_city = City::find(Auth::user()->city_agent);
            $city_agent = $get_city->city_name;
        }

        if(!empty(Auth::user()->state_agent)){
            $get_state = State::find(Auth::user()->state_agent);
            $state_agent = $get_state->name;
        }

        $upgrade_record = AgentLevelRecord::where('user_id', Auth::user()->code)->get();

        $aff_joined_date = [];
        if(Auth::guard('agent') || Auth::guard('admin')){
            $allAffiliateRecord = User::where('status', '3')->get();
            $allMerchants = Agent::where('status', '1')->get();

            foreach($allAffiliateRecord as $affiliate_record){
                $ic_record = explode('-', $affiliate_record->ic);
                foreach($allMerchants as $affiliate){
                    if($affiliate->ic == $ic_record[0]){
                        $aff_joined_date[$affiliate->code] = $affiliate_record;
                    }
                }
            }
        }

        $own_display_code = Auth::user()->display_code.Auth::user()->display_running_no;
        if(!empty(Auth::user()->relegated_from_agent)){
            $original_agent = Agent::where('code', Auth::user()->relegated_from_agent)
                                    ->where('status', 55)
                                    ->first();

            if(!empty($original_agent->id)){
                $own_display_code = $original_agent->display_code.$original_agent->display_running_no;
            }
        }

        return view('frontend.change_password', ['upline_code'=>$upline_code,
                                                 'upline_name'=>$upline_name,
                                                 'lvl'=>$lvl, 
                                                 'upgrade_record'=>$upgrade_record,
                                                 'own_display_code'=>$own_display_code,
                                                 'partner_lvl'=>$partner_lvl,
                                                 'city_agent'=>$city_agent,
                                                 'state_agent'=>$state_agent], 
                                                 compact('aff_joined_date'));
    }

    public function updateNewPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }

        
        if (!Hash::check($request->old_password, Auth::user()->password)) {
            return Redirect::back()->withInput($request->all())->withErrors(['The current password does not match']);
        }

        if($request->new_password != $request->password_confirmation){
            return Redirect::back()->withInput($request->all())->withErrors(['New password confirmation does not match']);
        }
        
        if(Auth::guard('admin')->check()){
          $update = Admin::where('code', Auth::user()->code)->first();
        }elseif(Auth::guard('agent')->check()){
          $update = Agent::where('code', Auth::user()->code)->first();
        }elseif(Auth::guard('corporate')->check()){
          $update = Corporate::where('code', Auth::user()->code)->first();
        }else{
          $update = User::where('code', Auth::user()->code)->first();
        }
        $update = $update->update(['password'=>Hash::make($request->new_password)]);

        Toastr::success("Password changed successfully");
        return redirect()->route('changePassword');
    }

    public function listing()
    {
        $check_authorize = GlobalController::check_authorize();
        if($check_authorize == 1){
            return Redirect::to('https://demoaccount.vesson.my/admin_login');
        }else{
            if(!empty(request('vm'))){
                return redirect()->route(request()->route()->getName());
            }
        }
        
        $getCurrentLogin = GlobalController::getCurrentLogin();
        $buyerCode = $getCurrentLogin['code'];
        $buyerLvl = $getCurrentLogin['lvl'];

        // dd($buyerLvl);

        $leftJoin = DB::raw("(SELECT * FROM product_images ORDER BY sort_level ASC) AS i");
        $products = Product::with(['get_agent_min_max_price_product' => function ($query) use ($buyerLvl) {
                                        $query->where('agent_lvl_id', $buyerLvl);
                                    }
                                 ])
                            ->with(['get_agent_min_max_birthday_price_product' => function ($query) use ($buyerLvl) {
                                    $query->where('agent_lvl_id', $buyerLvl);
                                }
                             ])
                           ->where('products.status', '1')
                           ->where('products.dow', '1')
                           ->whereNull('mall')
                           ->groupBy('products.id')
                           ->orderBy(DB::raw('IF(sorting > 0, sorting, 1000000)'), 'asc');

        $authorise_merchant = !empty($_COOKIE['vmerchant']) ? $_COOKIE['vmerchant'] : '';
        $get_authorise_status = GlobalController::check_autorize_status($authorise_merchant);
        if($get_authorise_status['status'] == 1){
        $products = $products->where('merchant_id', $get_authorise_status['result']['code']);
        }

        if(Auth::guard('agent')->check() || Auth::guard('admin')->check()){
            $products = $products->where('agent_only', '1');
            $products = $products->where(function($query) use ($buyerLvl){
                                        $query->where(DB::raw('IF(level_up > 0, level_up, 0)'), '>', $buyerLvl)
                                            ->orWhereNull('level_up')
                                            ->orWhere('level_up', '0');
                                });
            $products = $products->whereHas('get_agent_min_max_price_product', function($query) use($buyerLvl) {
                                $query->where('agent_lvl_id', $buyerLvl);
                        });

        }elseif(Auth::guard('web')->check()){
            $products = $products->where('customer_only', '1');
            $products = $products->where(function($query) {
                                        $query->whereHas('get_variation_min_max_price_product', function($query1){
                                            $query1->where('variation_price', '>', 0);
                                        })
                                        ->orWhereHas('get_second_variation_min_max_price_product', function($query2){
                                            $query2->where('variation_price', '>', 0);
                                        })
                                        ->orWhere('price', '>', 0);
                                 });
        }else{
            $products = $products->where('customer_only', '1');
            $products = $products->where(function($query) {
                                        $query->whereHas('get_retail_variation_min_max_price_product', function($query1){
                                            $query1->where('variation_retail_price', '>', 0);
                                        })
                                        ->orWhereHas('get_retail_second_variation_min_max_price_product', function($query2){
                                            $query2->where('variation_retail_price', '>', 0);
                                        })
                                        ->orWhere('retail_price', '>', 0);
                                 });
        }

        
        $per_page = 12;
        if(!empty(request('per_page'))){
            $per_page = request('per_page');
        }

        $queries = [];
        $columns = [
           'result', 'brand', 'category', 'subcategory', 'subsubcategory', 'from', 'to', 'per_page'
        ];

        foreach($columns as $column){
            if(request()->has($column) && !empty(request($column))){
                
                if($column == 'category'){
                    $products = $products->whereHas('one_product_category', function($query) use ($column){
                                                        $query->where('category_name', request($column));
                                                  });
                }elseif($column == 'subcategory'){
                    $products = $products->whereHas('one_product_subcategory', function($query) use ($column){
                                                        $query->where('sub_category_name', request($column));
                                                  });
                }elseif($column == 'brand'){
                    $products = $products->whereHas('one_product_brand', function($query) use ($column){
                                                        $query->where('brand_name', request($column));
                                                  });
                }elseif($column == 'from' || $column == 'to'){
                  $from = preg_replace("/[^0-9\.]/", '', request('from'));
                  $to = preg_replace("/[^0-9\.]/", '', request('to'));
                  if(Auth::guard('agent')->check() || Auth::guard('admin')->check()){
                      if(!empty(request('from')) && empty(request('to'))){

                          $products = $products->where(DB::raw('COALESCE(agent_special_price, agent_price)'), '>=', $from);
                      }elseif(empty(request('from')) && !empty(request('to'))){
                          $products = $products->where(DB::raw('COALESCE(agent_special_price, agent_price)'), '<=', $to);
                      }else{
                          $products = $products->where(DB::raw('COALESCE(agent_special_price, agent_price)'), '>=', $from)
                                               ->where(DB::raw('COALESCE(agent_special_price, agent_price)'), '<=', $to);
                      }
                  }else{
                      if(!empty(request('from')) && empty(request('to'))){
                          $products = $products->where(DB::raw('COALESCE(special_price, price)'), '>=', $from);
                      }elseif(empty(request('from')) && !empty(request('to'))){
                          $products = $products->where(DB::raw('COALESCE(special_price, price)'), '<=', $to);
                      }else{
                          $products = $products->where(DB::raw('COALESCE(special_price, price)'), '>=', $from)
                                               ->where(DB::raw('COALESCE(special_price, price)'), '<=', $to);
                      }                    
                  }

                }elseif($column == 'result'){
                    $products = $products->where(function($query) use ($column) {
                                             $query->where('products.product_name', 'like', '%'.request($column).'%')
                                                   ->orWhere('products.product_name_cn', 'like', '%'.request($column).'%')
                                                   ->orWhere('products.product_code', 'like', '%'.request($column).'%');
                                         });

                }elseif($column == 'per_page'){
                    $products = $products->paginate($per_page);
                }else{
                    $products = $products->where('products.product_name', 'like', '%'.request($column).'%');
                }
                
                $queries[$column] = request($column);

            }
        }

        if(!empty(request('per_page'))){
            $products = $products->appends($queries);        
        }else{
            $products = $products->paginate($per_page)->appends($queries);
        }
        $p = $products;
        
        $count_p = count($p);

        $categories = Category::select('categories.*', DB::raw('IF(sorting IS NOT NULL, sorting, 1000000) Ordersorting'))
                              ->where('status', '1')
                              ->orderBy('Ordersorting', 'asc');
        if($get_authorise_status['status'] == 1){
        $categories = $categories->where('merchant_id', $get_authorise_status['result']['code']);
        }
        $categories = $categories->get();
        
        $brands = Brand::where('status', '1');
        if($get_authorise_status['status'] == 1){
        $brands = $brands->where('merchant_id', $get_authorise_status['result']['code']);
        }
        $brands = $brands->get();

        $UOMs = SettingUom::select('setting_uoms.*');
        if($get_authorise_status['status'] == 1){
        $UOMs = $UOMs->where('merchant_id', $get_authorise_status['result']['code']);
        }
        $UOMs = $UOMs->get();

        $get_pricing = [];
        $sold_amount = [];
        $original_pricing = [];
        foreach($products as $product){
            $get_pricing[$product->id] = GlobalController::get_product_pricing(md5($product->id), $buyerCode, 0, 0, "", "", '1');
            
            $sold_amount[$product->id] = GlobalController::get_product_sold($product->id);

            $original_pricing[$product->id] = GlobalController::get_product_pricing(md5($product->id), $buyerCode);
        }

        return view('frontend.listing', ['products'=>$products, 
                                         'categories'=>$categories, 
                                         'brands'=>$brands, 
                                         'count_p'=>$count_p,
                                         'UOMs'=>$UOMs],
                                         compact('get_pricing',
                                                 'sold_amount',
                                                 'original_pricing'));
    }

    public function PointMall()
    {
        $check_authorize = GlobalController::check_authorize();
        if($check_authorize == 1){
            return Redirect::to('https://demoaccount.vesson.my/admin_login');
        }else{
            if(!empty(request('vm'))){
                return redirect()->route(request()->route()->getName());
            }
        }
        if(!Auth::guard('agent')->check() && !Auth::guard('web')->check() && !Auth::guard('admin')->check()){
            return redirect()->route('home');
        }

        $getCurrentLogin = GlobalController::getCurrentLogin();
        $buyerCode = $getCurrentLogin['code'];
        $buyerLvl = $getCurrentLogin['lvl'];

        $leftJoin = DB::raw("(SELECT * FROM product_images ORDER BY sort_level ASC) AS i");
        $products = Product::with(['get_agent_min_max_price_product' => function ($query) use ($buyerLvl) {
                                        $query->where('agent_lvl_id', $buyerLvl);
                                    }
                                 ])
                            ->with(['get_agent_min_max_birthday_price_product' => function ($query) use ($buyerLvl) {
                                    $query->where('agent_lvl_id', $buyerLvl);
                                }
                             ])
                           ->where('products.status', '1')
                           ->where('products.dow', '1')
                           ->where('mall', '1')
                           ->groupBy('products.id');

        if(Auth::guard('agent')->check() || Auth::guard('admin')->check()){
        $products = $products->where('agent_only', '1');
        $products = $products->where(function($query) use ($buyerLvl){
                                    $query->where(DB::raw('IF(level_up > 0, level_up, 0)'), '>', $buyerLvl)
                                          ->orWhereNull('level_up')
                                          ->orWhere('level_up', '0');
                             });
        $products = $products->whereHas('get_agent_min_max_price_product', function($query) use($buyerLvl) {
                            $query->where('agent_lvl_id', $buyerLvl);
                       });

        }elseif(Auth::guard('web')->check()){
            $products = $products->where('customer_only', '1');
            $products = $products->where(function($query) {
                                        $query->whereHas('get_variation_min_max_price_product', function($query1){
                                            $query1->where('variation_price', '>', 0);
                                        })
                                        ->orWhereHas('get_second_variation_min_max_price_product', function($query2){
                                            $query2->where('variation_price', '>', 0);
                                        })
                                        ->orWhere('price', '>', 0);
                                 });
        }else{
            $products = $products->where('customer_only', '1');
            $products = $products->where(function($query) {
                                        $query->whereHas('get_retail_variation_min_max_price_product', function($query1){
                                            $query1->where('variation_retail_price', '>', 0);
                                        })
                                        ->orWhereHas('get_retail_second_variation_min_max_price_product', function($query2){
                                            $query2->where('variation_retail_price', '>', 0);
                                        })
                                        ->orWhere('retail_price', '>', 0);
                                 });
        }

        $per_page = 12;
        if(!empty(request('per_page'))){
            $per_page = request('per_page');
        }

        $queries = [];
        $columns = [
           'result', 'brand', 'category', 'subcategory', 'subsubcategory', 'from', 'to', 'per_page'
        ];
        // return htmlspecialchars(request('category'));
        foreach($columns as $column){
            if(request()->has($column) && !empty(request($column))){
                
                if($column == 'category'){
                    $products = $products->whereHas('one_product_category', function($query) use ($column){
                                                        $query->where('category_name', request($column));
                                                  });
                }elseif($column == 'subcategory'){
                    $products = $products->whereHas('one_product_subcategory', function($query) use ($column){
                                                        $query->where('sub_category_name', request($column));
                                                  });
                }elseif($column == 'brand'){
                    $products = $products->whereHas('one_product_brand', function($query) use ($column){
                                                        $query->where('brand_name', request($column));
                                                  });
                }elseif($column == 'from' || $column == 'to'){
                  $from = preg_replace("/[^0-9\.]/", '', request('from'));
                  $to = preg_replace("/[^0-9\.]/", '', request('to'));
                  if(Auth::guard('agent')->check() || Auth::guard('admin')->check()){
                      if(!empty(request('from')) && empty(request('to'))){

                          $products = $products->where(DB::raw('COALESCE(agent_special_price, agent_price)'), '>=', $from);
                      }elseif(empty(request('from')) && !empty(request('to'))){
                          $products = $products->where(DB::raw('COALESCE(agent_special_price, agent_price)'), '<=', $to);
                      }else{
                          $products = $products->where(DB::raw('COALESCE(agent_special_price, agent_price)'), '>=', $from)
                                               ->where(DB::raw('COALESCE(agent_special_price, agent_price)'), '<=', $to);
                      }
                  }else{
                      if(!empty(request('from')) && empty(request('to'))){
                          $products = $products->where(DB::raw('COALESCE(special_price, price)'), '>=', $from);
                      }elseif(empty(request('from')) && !empty(request('to'))){
                          $products = $products->where(DB::raw('COALESCE(special_price, price)'), '<=', $to);
                      }else{
                          $products = $products->where(DB::raw('COALESCE(special_price, price)'), '>=', $from)
                                               ->where(DB::raw('COALESCE(special_price, price)'), '<=', $to);
                      }                    
                  }

                }elseif($column == 'result'){
                    $products = $products->where(function($query) use ($column) {
                                             $query->where('products.product_name', 'like', '%'.request($column).'%')
                                                   ->orWhere('products.product_name_cn', 'like', '%'.request($column).'%')
                                                   ->orWhere('products.product_code', 'like', '%'.request($column).'%');
                                         });

                }elseif($column == 'per_page'){
                    $products = $products->paginate($per_page);
                }else{
                    $products = $products->where('products.product_name', 'like', '%'.request($column).'%');
                }
                
                $queries[$column] = request($column);

            }
        }

        if(!empty(request('per_page'))){
            $products = $products->appends($queries);        
        }else{
            $products = $products->paginate($per_page)->appends($queries);
        }
        $p = $products;
        
        $count_p = count($p);

        $categories = Category::select('categories.*', DB::raw('IF(sorting IS NOT NULL, sorting, 1000000) Ordersorting'))
                              ->where('status', '1')
                              ->orderBy('Ordersorting', 'asc')
                              ->get();
        
        $brands = Brand::where('status', '1')->get();

        $get_pricing = [];
        $sold_amount = [];
        $original_pricing = [];
        foreach($products as $product){
            $get_pricing[$product->id] = GlobalController::get_product_pricing(md5($product->id), $buyerCode, 0, 0, "", "", '1');
            $sold_amount[$product->id] = GlobalController::get_product_sold($product->id);

            $original_pricing[$product->id] = GlobalController::get_product_pricing(md5($product->id), $buyerCode);
        }

        return view('frontend.listing_mall', ['products'=>$products, 
                                         'categories'=>$categories, 
                                         'brands'=>$brands, 
                                         'count_p'=>$count_p],
                                         compact('sold_amount',
                                                 'get_pricing',
                                                'original_pricing'));
    }
    public function promotion_listing()
    {

        if(Auth::guard('web')->check()){
          $user = Auth::guard('web')->check();
          $userCode = Auth::guard('web')->user()->code;
          $buyerLvl = Auth::guard('web')->user()->lvl;
        }elseif (Auth::guard('agent')->check()) {
          $user = Auth::guard('agent')->check();
          $userCode = Auth::guard('agent')->user()->code;
          $buyerLvl = Auth::guard('agent')->user()->lvl;
        }elseif (Auth::guard('admin')->check()) {
          $user = Auth::guard('admin')->check();
          $userCode = Auth::guard('admin')->user()->code;
          $buyerLvl = Auth::guard('admin')->user()->lvl;
        }else{
          $user = "";
          $userCode = "";
          $buyerLvl = "";
        }

        $titles = PromoItemTitle::where('date_from', '<=', date('Y-m-d H:i:s'))
                                ->where('date_end', '>=', date('Y-m-d H:i:s'))
                                ->where('status', '1')
                                ->get();

        $promo_items = [];
        $PromoImages = [];
        $pricing = [];
        $priceV = [];
        $sec_var_price_range = [];
        $sec_var_price_range_customer = [];
        foreach($titles as $title){
            $promo_items[$title->id] = PromoAgentItem::select('p.*', 'paid.price as pai_price',
                                                              'paid.special_price as pai_special_price',
                                                              'b.brand_name', 'promo_agent_items.id as pai_id',
                                                              'psv.variation_name as psv_variation_name', 
                                                              'psv.id as psv_id', 
                                                              'pv.variation_name as pv_variation_name', 
                                                              'pv.id as pv_id')
                                                     ->join('products AS p', 'p.id', 'promo_agent_items.product_id')
                                                     ->leftJoin('promo_agent_item_details as paid', 'paid.promo_item_id', 'promo_agent_items.id')
                                                     ->leftJoin('product_variations AS pv', 'pv.id', 'paid.variation_id')
                                                     ->leftJoin('product_second_variations AS psv', 'psv.id', 'paid.second_variation_id')
                                                     ->leftJoin('brands AS b', 'b.id', 'p.brand_id')
                                                     ->where('title_id', $title->id)
                                                     ->where('p.status', '1')
                                                     ->groupBy('promo_agent_items.id')
                                                     ->get();

            foreach($promo_items[$title->id] as $promo_item){

                $PromoImages[$promo_item->id] = ProductImage::where('product_id', $promo_item->id)->orderBy('sort_level', 'asc')->first();

                if(!empty($buyerLvl)){
                    // $variations = PromoAgentPrice::select(DB::raw('max(price) as MaxVAPrice'),
                    //                                               DB::raw('min(price) as MinVAPrice'))
                    //                                      ->where('item_id', $promo_item->pai_id)
                    //                                      ->where('agent_lvl_id', $buyerLvl)
                    //                                      ->first();

                    $variations = PromoAgentItemDetail::select(DB::raw('max(COALESCE(special_price, price)) as MaxVAPrice'),
                                                                  DB::raw('min(COALESCE(special_price, price)) as MinVAPrice'))
                                                      ->where('agent_lvl_id', $buyerLvl)
                                                      ->where('promo_item_id', $promo_item->pai_id)
                                                      ->where('status', '1')
                                                      ->first();

                    $pricing[$promo_item->pai_id] = [$variations->MaxVAPrice, $variations->MinVAPrice];


                    $variationRange = PromoAgentItemDetail::select(DB::raw('max(special_price) as MaxSPrice'),
                                                              DB::raw('min(special_price) as MinSPrice'),
                                                                  DB::raw('min(price) as MinPrice'),
                                                              DB::raw('max(price) as MaxPrice'))
                                                      ->where('agent_lvl_id', $buyerLvl)
                                                      ->where('promo_item_id', $promo_item->pai_id)
                                                      ->where('status', '1')
                                                      ->first();

                    $sec_var_price_range[$promo_item->pai_id] = [$variationRange->MinSPrice, $variationRange->MaxSPrice, $variationRange->MinPrice, $variationRange->MaxPrice];

                    // echo $buyerLvl.' - '.$promo_item->pai_id;
                    // echo "<br>";
                }

                $variationCustomerRange = PromoAgentItemDetail::select(DB::raw('max(special_price) as MaxSPrice'),
                                                          DB::raw('min(special_price) as MinSPrice'),
                                                              DB::raw('min(price) as MinPrice'),
                                                          DB::raw('max(price) as MaxPrice'))
                                                  ->whereNull('agent_lvl_id')
                                                  ->where('promo_item_id', $promo_item->pai_id)
                                                  ->where('status', '1')
                                                  ->first();
                // echo $promo_item->pai_id;
                                                  
                $sec_var_price_range_customer[$promo_item->pai_id] = [$variationCustomerRange->MinSPrice, $variationCustomerRange->MaxSPrice, $variationCustomerRange->MinPrice, $variationCustomerRange->MaxPrice];

                if($promo_item->second_variation_enable == 1){
                  $variations = ProductSecondVariation::select(DB::raw('min(variation_special_price) as MinVSPrice'), 
                                                         DB::raw('min(variation_price) as MinVPrice'),
                                                         DB::raw('max(variation_special_price) as MaxVSPrice'),
                                                         DB::raw('max(variation_price) as MaxVPrice'))
                                            ->where('product_id', $promo_item->id)
                                            ->where('variation_name', '!=', '')
                                            ->first();

                  $priceV[$promo_item->pai_id] = [$variations->MinVPrice, $variations->MaxVPrice];
                }else{
                  $variations = ProductVariation::select(DB::raw('min(variation_special_price) as MinVSPrice'), 
                                                         DB::raw('min(variation_price) as MinVPrice'),
                                                         DB::raw('max(variation_special_price) as MaxVSPrice'),
                                                         DB::raw('max(variation_price) as MaxVPrice'))
                                            ->where('product_id', $promo_item->id)
                                            ->where('variation_name', '!=', '')
                                            ->first();

                  $priceV[$promo_item->pai_id] = [$variations->MinVPrice, $variations->MaxVPrice];              
                }
            }
        }
        // exit();
        // PromoAgentItem
        // PromoAgentPrice

        return view('frontend.promo_listing', ['titles'=>$titles,
                                               'promo_items'=>$promo_items],
                                        compact('PromoImages', 'pricing', 'priceV', 'sec_var_price_range', 'sec_var_price_range_customer'));
    }

    public function details($id)
    {
        $check_authorize = GlobalController::check_authorize();
        if($check_authorize == 1){
            return Redirect::to('https://demoaccount.vesson.my/admin_login');
        }else{
            if(!empty(request('vm'))){
                return redirect()->route(request()->route()->getName());
            }
        }

        $authorise_merchant = !empty($_COOKIE['vmerchant']) ? $_COOKIE['vmerchant'] : '';
        $get_authorise_status = GlobalController::check_autorize_status($authorise_merchant);

        if(!empty(request('afapi'))){
            GlobalController::session_set_register_upline(request('afapi'));
        }

        if(Auth::guard('web')->check()){

            $user = Auth::guard('web')->check();
            $userCode = Auth::guard('web')->user()->code;
            $buyerLvl = Auth::guard('web')->user()->lvl;

        }elseif (Auth::guard('merchant')->check()) {

            $user = Auth::guard('merchant')->check();
            $userCode = Auth::guard('merchant')->user()->code;
            $buyerLvl = Auth::guard('merchant')->user()->lvl;

        }elseif (Auth::guard('admin')->check()) {

            $user = Auth::guard('admin')->check();
            $userCode = Auth::guard('admin')->user()->code;
            $buyerLvl = Auth::guard('admin')->user()->lvl;

        }elseif (Auth::guard('agent')->check()) {

            $user = Auth::guard('agent')->check();
            $userCode = Auth::guard('agent')->user()->code;
            $buyerLvl = Auth::guard('agent')->user()->lvl;

        }else{

            $user = "";
            $userCode = "";
            $buyerLvl = "";

        }

        $product = Product::with(['get_agent_min_max_birthday_price_product' => function ($query) use ($buyerLvl) {
                                        $query->where('agent_lvl_id', $buyerLvl);
                                    }
                                 ])
                          ->where(DB::raw('md5(products.id)'), $id)
                          ->where('products.dow', '1')
                          ->where('products.status', '1');


        if (Auth::guard('agent')->check()) {
            $product = $product->where('agent_only', '1');
            $product = $product->whereHas('get_agent_min_max_price_product', function($query) use($buyerLvl) {
                            $query->where('agent_lvl_id', $buyerLvl);
                        });
        }

        if($get_authorise_status['status'] == 1){
            $product = $product->where('merchant_id', $get_authorise_status['result']['code']);
        }
        $product = $product->whereNull('mall');

        $product = $product->first();

        if(empty($product)){
            return redirect()->route('home');
        }

        $get_product_pricing = GlobalController::get_product_pricing($id, $userCode, '0', '0', "", "", '1');

        $get_original_product_pricing = GlobalController::get_product_pricing($id, $userCode, '0', '0');

        $stockBalance = GlobalController::balance_quantity($product->id);

        $vStock = [];
        $svStock = [];
        foreach($product->get_variations as $variation){
            $vStock[$variation->id] = GlobalController::variation_balance_quantity($variation->id);
        }
        foreach($product->get_second_variations as $second_variation){
            $svStock[$second_variation->id] = GlobalController::second_variation_balance_quantity($second_variation->id);
        }

        $leftJoin = DB::raw("(SELECT * FROM product_images ORDER BY created_at ASC) AS i");
        $Ppackages = PackageItem::select('p.product_name', 'package_items.*', 'i.image')
                                ->join('products AS p', 'p.id', 'package_items.products')
                                ->leftJoin($leftJoin, function($join) {
                                    $join->on('p.id', '=', 'i.product_id');
                                })
                                ->where(DB::raw('md5(package_items.product_id)'), $id)
                                ->groupBy('package_items.products')
                                ->get();

        $Vpackages = PackageItem::select('p.promotion_title', 'package_items.*', 'p.image')
                                ->join('promotions AS p', 'p.id', 'package_items.voucher_id')
                                ->where(DB::raw('md5(package_items.product_id)'), $id)
                                ->groupBy('package_items.voucher_id')
                                ->get();

        $addondealItem = AddonDealItem::select('add_on_deal_items.*','p.id as pid', 'p.product_name', 'p.product_name_cn', 'p.variation_enable', 'p.second_variation_enable', 'aod.end_date')
                                ->leftjoin('products as p','add_on_deal_items.product_id','p.id')
                                ->leftJoin('add_on_deals as aod', 'aod.id', 'add_on_deal_items.add_on_id')
                                ->where('add_on_deal_items.status','1')
                                ->where('aod.status', '1')
                                ->where(DB::raw('md5(add_on_deal_items.product_id)'), $id)
                                // ->where('aod.end_date', '<', date('m/d/Y h:i:A'))
                                ->where('aod.end_date', '>', date('Y-m-d H:i:s'))
                                ->first();

         $vouchers = Promotion::all();

        // dd($addondealItem);
        $addonDeal = '';
        $subItem = [];
        $subItem_variation = [];
        $subItem_second_variation = [];
        $subItem_image = [];
        $addondealItem_variation = [];
        $item_price = [];
        $main_item_price = 0;
        $sub_item_price = [];
        $original_sub_item_price = [];

        $variations = NULL;
        $second_variations = NULL;
        $variation_price = NULL;

        if(!empty($addondealItem->id)){
            $addondealItem_variation[$addondealItem->id] = ProductVariation::where('product_id',$addondealItem->product_id)->first();
            $addonDeal = AddonDeal::where('id',$addondealItem->add_on_id)->where('status','1')->first();

            $main_item_price = GlobalController::get_product_pricing(md5($addondealItem->product_id), $userCode, $addondealItem->variation_id, $addondealItem->second_variation_id);

            $subItem = AddonDealSubItem::select('add_on_deal_sub_items.*',
                                                'p.id as pid', 
                                                'p.product_name',
                                                'p.product_name_cn', 
                                                'pv.variation_name', 
                                                'psv.variation_name as second_variation_name')
                                      ->leftjoin('products as p','add_on_deal_sub_items.product_id','p.id')
                                      ->leftJoin('product_variations as pv', 'add_on_deal_sub_items.variation_id', 'pv.id')
                                      ->leftJoin('product_second_variations as psv', 'add_on_deal_sub_items.second_variation_id', 'psv.id')
                                      ->where('add_on_deal_sub_items.add_on_id',$addondealItem->add_on_id)
                                      ->where('add_on_deal_sub_items.status','1')
                                      ->get();
            // dd($subItem);
            foreach ($subItem as $key => $items) {
                if(!empty($items->get_product_det->first_image->image)){
                    $subItem_image[$items->id] = $items->get_product_det->first_image->image;
                }

                $subItem_variation[$items->id] = ProductVariation::find($items->variation_id);

                $item_price[$items->id] = GlobalController::get_product_pricing(md5($items->pid), $userCode, $items->variation_id, $items->second_variation_id)['product_price'];

                $subItem_second_variation[$items->id] = ProductSecondVariation::find($items->second_variation_id);

                $sub_item_price[$items->id] = GlobalController::get_add_on_sub_item_price($userCode, $items->add_on_id, $items->product_id, $items->variation_id, $items->second_variation_id);

                $original_sub_item_price[$items->id] = GlobalController::get_product_pricing(md5($items->product_id), $userCode, $items->variation_id, $items->second_variation_id)['product_price'];
            }
        }

        // dd($addonDeal);

        $flash_sale_active = GlobalController::check_if_product_has_flash_sale_active($product->id);

        $sold_qty = GlobalController::get_product_sold($product->id);

        $variation_qty = [];
        $second_variation_qty = [];
        foreach($product->get_variations as $variationKey => $variation){
            $variation_qty[$variation->id] = GlobalController::get_product_sold($product->id, $variation->id);

            foreach($variation->get_second_variations as $second_variationKey => $second_variation){
                $second_variation_qty[$second_variation->id] = GlobalController::get_product_sold($product->id, $variation->id, $second_variation->id);
            }
        }

        $variation_qty = json_encode($variation_qty);
        $second_variation_qty = json_encode($second_variation_qty);

        $favourite = Favourite::where('product_id', $product->id)->first();
        
        return view('frontend.details', ['product'=>$product,
                                         'get_product_pricing'=>$get_product_pricing,
                                         'stockBalance'=>$stockBalance,
                                         'Ppackages'=>$Ppackages,
                                         'Vpackages'=>$Vpackages,
                                         'addondealItem'=>$addondealItem,
                                         'addonDeal'=>$addonDeal,
                                         'subItem'=>$subItem,
                                         'main_item_price'=>$main_item_price,
                                         'flash_sale_active'=>$flash_sale_active,
                                         'get_original_product_pricing'=>$get_original_product_pricing,
                                         'sold_qty'=>$sold_qty,
                                         'variations'=>$variations,
                                         'second_variations'=>$second_variations,
                                         'variation_price'=>$variation_price,
                                         'vouchers'=>$vouchers,
                                         'favourite'=>$favourite],
                                         compact('vStock', 'svStock',
                                          'subItem_variation',
                                          'subItem_second_variation',
                                          'subItem_image',
                                          'addondealItem_variation',
                                          'item_price',
                                          'sub_item_price',
                                          'original_sub_item_price',
                                          'variation_qty',
                                          'second_variation_qty'));
    }

    public function details_mall($id)
    {
        $check_authorize = GlobalController::check_authorize();
        if($check_authorize == 1){
            return Redirect::to('https://demoaccount.vesson.my/admin_login');
        }else{
            if(!empty(request('vm'))){
                return redirect()->route(request()->route()->getName());
            }
        }
        if(!Auth::guard('agent')->check() && !Auth::guard('web')->check() && !Auth::guard('admin')->check()){
            return redirect()->route('home');
        }

        $getCurrentLogin = GlobalController::getCurrentLogin();
        $buyerCode = $getCurrentLogin['code'];
        $buyerLvl = $getCurrentLogin['lvl'];

        $product = Product::with(['get_agent_min_max_price_product' => function ($query) use ($buyerLvl) {
                                        $query->where('agent_lvl_id', $buyerLvl);
                                    }
                                 ])
                            ->with(['get_agent_min_max_birthday_price_product' => function ($query) use ($buyerLvl) {
                                    $query->where('agent_lvl_id', $buyerLvl);
                                }
                             ])
                          ->where(DB::raw('md5(products.id)'), $id)
                          ->where('products.dow', '1')
                          ->where('products.status', '1');
        if(Auth::guard('agent')->check()){
        $product = $product->where('agent_only', '1');
        }elseif(Auth::guard('corporate')->check()){
        $product = $product->where('corporate_only', '1');
        }elseif(Auth::guard('web')->check()){
        $product = $product->where('customer_only', '1');
        }elseif(Auth::guard('admin')->check()){
        }else{
        $product = $product->where('customer_only', '1');
        }
        $product = $product->first();
        if(empty($product)){
            return redirect()->route('home');
        }

        if($product->variation_enable == 1){
            if($product->second_variation_enable == 1){
                $stockBalance = $this->ProductVariationAllBalanceQuantity($product->id, 2);
    
            }else{
                $stockBalance = $this->ProductVariationAllBalanceQuantity($product->id, 1);
            }
        }else{
            $stockBalance = $this->BalanceQuantity($product->id);          
        }

        $pricing = [];
        if(!empty($buyerLvl)){
            $variations = AgentPrice::select(DB::raw('max(price) as MaxVAPrice'),
                                             DB::raw('min(price) as MinVAPrice'),
                                             DB::raw('max(special_price) as MaxVASPrice'),
                                             DB::raw('min(special_price) as MinVASPrice'))
                                                 ->where('product_id', $product->id)
                                                 ->where('agent_lvl_id', $buyerLvl)
                                                 ->first();
            $pricing[$product->id] = [$variations->MaxVAPrice, $variations->MinVAPrice, $variations->MaxVASPrice, $variations->MinVASPrice];
        }

        $variation_price = "";
        if($product->variation_enable == 1){
            if($product->second_variation_enable == 1){
              $variation_price = ProductSecondVariation::select(DB::raw("max(IF(variation_special_price != '0', variation_special_price, variation_price)) AS maxVPrice"),
                DB::raw("max(IF(variation_west_special_price != '0', variation_west_special_price, variation_west_price)) AS maxWVPrice"),
                    DB::raw("min(IF(variation_west_special_price != '0', variation_west_special_price, variation_west_price)) AS minWVPrice"),
                                                     DB::raw("max(IF(variation_agent_special_price != '0', variation_agent_special_price, variation_agent_price)) AS maxVAPrice"),
                                                     DB::raw("min(IF(variation_special_price != '0', variation_special_price, variation_price)) AS minVPrice"),
                                                     DB::raw("min(IF(variation_agent_special_price != '0', variation_agent_special_price, variation_agent_price)) AS minVAPrice"),
                                                     DB::raw("min(IF(variation_corporate_special_price != '0', variation_corporate_special_price, variation_corporate_price)) AS minVCPrice"),
                                                     DB::raw("max(IF(variation_corporate_special_price != '0', variation_corporate_special_price, variation_corporate_price)) AS maxVCPrice"),
                                                     
                                                     DB::raw('min(variation_agent_special_price) as MinVASPrice'), 
                                                     DB::raw('min(variation_agent_price) as MinVAPrice'),
                                                     DB::raw('max(variation_agent_special_price) as MaxVASPrice'),
                                                     DB::raw('max(variation_agent_price) as MaxVAPrice'),

                                                     DB::raw('min(variation_special_price) as MinVSPrice'), 
                                                     DB::raw('min(variation_price) as MinVPrice'),
                                                     DB::raw('max(variation_special_price) as MaxVSPrice'),
                                                     DB::raw('max(variation_price) as MaxVPrice'),

                                                     DB::raw('min(variation_corporate_special_price) as MinVSPrice'), 
                                                     DB::raw('min(variation_corporate_price) as MinVPrice'),
                                                     DB::raw('max(variation_corporate_special_price) as MaxVSPrice'),
                                                     DB::raw('max(variation_corporate_price) as MaxVPrice'))
                                           ->where('product_id', $product->id)
                                           ->where('variation_name', '!=', '')
                                           ->first();
            }else{
              $variation_price = ProductVariation::select(
                    DB::raw("max(IF(variation_special_price != '0', variation_special_price, variation_price)) AS maxVPrice"),
                    DB::raw("max(IF(variation_west_special_price != '0', variation_west_special_price, variation_west_price)) AS maxWVPrice"),
                    DB::raw("min(IF(variation_west_special_price != '0', variation_west_special_price, variation_west_price)) AS minWVPrice"),
                                                     DB::raw("max(IF(variation_agent_special_price != '0', variation_agent_special_price, variation_agent_price)) AS maxVAPrice"),
                                                     DB::raw("min(IF(variation_special_price != '0', variation_special_price, variation_price)) AS minVPrice"),
                                                     DB::raw("min(IF(variation_agent_special_price != '0', variation_agent_special_price, variation_agent_price)) AS minVAPrice"),
                                                     DB::raw("min(IF(variation_corporate_special_price != '0', variation_corporate_special_price, variation_corporate_price)) AS minVCPrice"),
                                                     DB::raw("max(IF(variation_corporate_special_price != '0', variation_corporate_special_price, variation_corporate_price)) AS maxVCPrice"),
                                                     
                                                     DB::raw('min(variation_agent_special_price) as MinVASPrice'), 
                                                     DB::raw('min(variation_agent_price) as MinVAPrice'),
                                                     DB::raw('max(variation_agent_special_price) as MaxVASPrice'),
                                                     DB::raw('max(variation_agent_price) as MaxVAPrice'),

                                                     DB::raw('min(variation_special_price) as MinVSPrice'), 
                                                     DB::raw('min(variation_price) as MinVPrice'),
                                                     DB::raw('max(variation_special_price) as MaxVSPrice'),
                                                     DB::raw('max(variation_price) as MaxVPrice'),

                                                     DB::raw('min(variation_corporate_special_price) as MinVSPrice'), 
                                                     DB::raw('min(variation_corporate_price) as MinVPrice'),
                                                     DB::raw('max(variation_corporate_special_price) as MaxVSPrice'),
                                                     DB::raw('max(variation_corporate_price) as MaxVPrice'))
                                           ->where('product_id', $product->id)
                                           ->where('variation_name', '!=', '')
                                           ->first();
            }
        }

        $images = ProductImage::where('product_id', $product->id)
                              ->where('status', '1')
                              ->orderBy('sort_level', 'asc')
                              ->get();

        $leftJoin = DB::raw("(SELECT * FROM product_images ORDER BY created_at ASC) AS i");
        $products = Product::select('products.*', 'i.image', 'b.brand_name')
                           ->join('product_images as i', 'products.id', 'i.product_id')
                           ->leftJoin('brands as b', 'products.brand_id', 'b.id')
                           ->where(DB::raw('md5(products.id)'), '<>', $id)
                           ->where('products.status', '1')
                           ->groupBy('products.id')
                           ->take(8)
                           ->get();
        $priceV = [];
        foreach($products as $related_product){

          $variations = ProductVariation::select(DB::raw("max(IF(variation_special_price != '0', variation_special_price, variation_price)) AS maxVPrice"),
                                                   DB::raw("max(IF(variation_agent_special_price != '0', variation_agent_special_price, variation_agent_price)) AS maxVAPrice"),
                                                   DB::raw("min(IF(variation_special_price != '0', variation_special_price, variation_price)) AS minVPrice"),
                                                   DB::raw("min(IF(variation_agent_special_price != '0', variation_agent_special_price, variation_agent_price)) AS minVAPrice"),
                                                   
                                                   DB::raw('min(variation_agent_special_price) as MinVASPrice'), 
                                                   DB::raw('min(variation_agent_price) as MinVAPrice'),
                                                   DB::raw('max(variation_agent_special_price) as MaxVASPrice'),
                                                   DB::raw('max(variation_agent_price) as MaxVAPrice'),

                                                   DB::raw('min(variation_special_price) as MinVSPrice'), 
                                                   DB::raw('min(variation_price) as MinVPrice'),
                                                   DB::raw('max(variation_special_price) as MaxVSPrice'),
                                                   DB::raw('max(variation_price) as MaxVPrice'))
                                          ->where('product_id', $related_product->id)
                                          ->where('variation_name', '!=', '')
                                          ->first();
            $priceV[$related_product->id] = [$variations->maxVPrice, $variations->minVPrice, $variations->maxVAPrice, $variations->minVAPrice,
                                     $variations->MinVASPrice, $variations->MinVAPrice, $variations->MaxVASPrice, $variations->MaxVAPrice, 
                                     $variations->MinVSPrice, $variations->MinVPrice, $variations->MaxVSPrice, $variations->MaxVPrice];
        }
        $sub_category_id = [];
        if(!empty($product->sub_category_id)){
           $sub_category_id = SubCategory::whereIn('id', explode(",", $product->sub_category_id))->get();
        }

        $Pimage = ProductImage::where(DB::raw('md5(product_id)'), $id)->orderBy('sort_level', 'asc')->first();

        $Ppackages = PackageItem::select('p.product_name', 'package_items.*', 'i.image')
                                ->join('products AS p', 'p.id', 'package_items.products')
                                ->leftJoin($leftJoin, function($join) {
                                    $join->on('p.id', '=', 'i.product_id');
                                })
                                ->where(DB::raw('md5(package_items.product_id)'), $id)
                                ->groupBy('package_items.products')
                                ->get();

        $variations = ProductVariation::where(DB::raw('md5(product_id)'), $id)->where('variation_name', '!=', '')->get();

        $second_variations = ProductSecondVariation::where(DB::raw('md5(product_id)'), $id)
                                                   ->where('variation_name', '!=', '')
                                                   ->groupBy('variation_name')
                                                   ->get();

        $vStock = [];
        $svStock = [];
        foreach($product->get_variations as $variation){
            $vStock[$variation->id] = GlobalController::variation_balance_quantity($variation->id);
        }
        foreach($product->get_second_variations as $second_variation){
            $svStock[$second_variation->id] = GlobalController::second_variation_balance_quantity($second_variation->id);
        }

        $get_product_pricing = GlobalController::get_product_pricing($id, $buyerCode, '0', '0', "", "", '1');

        $Vpackages = PackageItem::select('p.promotion_title', 'package_items.*', 'p.image')
                                ->join('promotions AS p', 'p.id', 'package_items.voucher_id')
                                ->where(DB::raw('md5(package_items.product_id)'), $id)
                                ->groupBy('package_items.voucher_id')
                                ->get();

         $variation_qty = [];
         $second_variation_qty = [];
        foreach($product->get_variations as $variationKey => $variation){
            $variation_qty[$variation->id] = GlobalController::get_product_sold($product->id, $variation->id);
                        
                foreach($variation->get_second_variations as $second_variationKey => $second_variation){
                     $second_variation_qty[$second_variation->id] = GlobalController::get_product_sold($product->id, $variation->id, $second_variation->id);
            }
         }

         $variation_qty = json_encode($variation_qty);
         $second_variation_qty = json_encode($second_variation_qty);

         $addondealItem = AddonDealItem::select('add_on_deal_items.*','p.id as pid', 'p.product_name', 'p.product_name_cn', 'p.variation_enable', 'p.second_variation_enable', 'aod.end_date')
                                ->leftjoin('products as p','add_on_deal_items.product_id','p.id')
                                ->leftJoin('add_on_deals as aod', 'aod.id', 'add_on_deal_items.add_on_id')
                                ->where('add_on_deal_items.status','1')
                                ->where('aod.status', '1')
                                ->where(DB::raw('md5(add_on_deal_items.product_id)'), $id)
                                // ->where('aod.end_date', '<', date('m/d/Y h:i:A'))
                                ->where('aod.end_date', '>', date('Y-m-d H:i:s'))
                                ->first();

        // dd($addondealItem);
        $addonDeal = '';
        $subItem = [];
        $subItem_variation = [];
        $subItem_second_variation = [];
        $subItem_image = [];
        $addondealItem_variation = [];
        $item_price = [];
        $main_item_price = 0;
        $sub_item_price = [];
        $original_sub_item_price = [];

        $variations = NULL;
        $second_variations = NULL;
        $variation_price = NULL;

        if(!empty($addondealItem->id)){
            $addondealItem_variation[$addondealItem->id] = ProductVariation::where('product_id',$addondealItem->product_id)->first();
            $addonDeal = AddonDeal::where('id',$addondealItem->add_on_id)->where('status','1')->first();

            $main_item_price = GlobalController::get_product_pricing(md5($addondealItem->product_id), $userCode, $addondealItem->variation_id, $addondealItem->second_variation_id);

            $subItem = AddonDealSubItem::select('add_on_deal_sub_items.*',
                                                'p.id as pid', 
                                                'p.product_name',
                                                'p.product_name_cn', 
                                                'pv.variation_name', 
                                                'psv.variation_name as second_variation_name')
                                      ->leftjoin('products as p','add_on_deal_sub_items.product_id','p.id')
                                      ->leftJoin('product_variations as pv', 'add_on_deal_sub_items.variation_id', 'pv.id')
                                      ->leftJoin('product_second_variations as psv', 'add_on_deal_sub_items.second_variation_id', 'psv.id')
                                      ->where('add_on_deal_sub_items.add_on_id',$addondealItem->add_on_id)
                                      ->where('add_on_deal_sub_items.status','1')
                                      ->get();
            // dd($subItem);
            foreach ($subItem as $key => $items) {
                if(!empty($items->get_product_det->first_image->image)){
                    $subItem_image[$items->id] = $items->get_product_det->first_image->image;
                }

                $subItem_variation[$items->id] = ProductVariation::find($items->variation_id);

                $item_price[$items->id] = GlobalController::get_product_pricing(md5($items->pid), $userCode, $items->variation_id, $items->second_variation_id)['product_price'];

                $subItem_second_variation[$items->id] = ProductSecondVariation::find($items->second_variation_id);

                $sub_item_price[$items->id] = GlobalController::get_add_on_sub_item_price($userCode, $items->add_on_id, $items->product_id, $items->variation_id, $items->second_variation_id);

                $original_sub_item_price[$items->id] = GlobalController::get_product_pricing(md5($items->product_id), $userCode, $items->variation_id, $items->second_variation_id)['product_price'];
            }
        }

        $favourite = Favourite::where('product_id', $product->id)->first();

        return view('frontend.details_mall', ['product'=>$product, 
                                            'stockBalance'=>$stockBalance, 
                                            'images'=>$images, 
                                            'products'=>$products, 
                                            'Pimage'=>$Pimage, 
                                            'Ppackages'=>$Ppackages,
                                            'sub_category_id'=>$sub_category_id, 
                                            'variations'=>$variations,
                                            'second_variations'=>$second_variations,
                                            'variation_price'=>$variation_price,
                                            'get_product_pricing'=>$get_product_pricing,
                                            'Vpackages'=>$Vpackages,
                                            'addondealItem'=>$addondealItem,
                                            'addonDeal'=>$addonDeal,
                                            'subItem'=>$subItem,
                                            'main_item_price'=>$main_item_price,
                                            'favourite'=>$favourite], 
                                            compact('vStock','priceV', 'svStock', 'pricing', 'variation_qty', 'second_variation_qty',
                                                    'subItem_variation',
                                                    'subItem_second_variation',
                                                    'subItem_image',
                                                    'addondealItem_variation',
                                                    'item_price',
                                                    'sub_item_price',
                                                    'original_sub_item_price'));
    }
    public function checkout()
    {
        $check_authorize = GlobalController::check_authorize();
        if($check_authorize == 1){
            return Redirect::to('https://demoaccount.vesson.my/admin_login');
        }else{
            if(!empty(request('vm'))){
                return redirect()->route(request()->route()->getName());
            }
        }
        $getCurrentLogin = GlobalController::getCurrentLogin();
        $buyerCode = $getCurrentLogin['code'];
        $buyerLvl = $getCurrentLogin['lvl'];

        $getUserDetails = GlobalController::getUserDetails($buyerCode);

        if(empty($getUserDetails->id)){
            $get_guest_shipping_address = UserShippingAddress::where('user_id', $buyerCode)->where('default', '1')->first();
            
            if(!empty($get_guest_shipping_address->id)){
                $getUserDetails = new Person();
                $getUserDetails->get_default_shipping_address = $get_guest_shipping_address;
            }
        }
        
        $update_flash_cart = GlobalController::update_flash_sale_product_status($buyerCode);


        $carts = Cart::with(['get_product_det.get_agent_price_product' => function ($query) use ($buyerLvl) {
                                    $query->where('agent_lvl_id', $buyerLvl);
                                }
                            ])
                     ->with(['get_fv_det.get_agent_price_variation' => function ($query_two) use ($buyerLvl) {
                                    $query_two->where('agent_lvl_id', $buyerLvl);
                                }
                            ])
                     ->with(['get_sv_det.get_agent_price_second_variation' => function ($query_three) use ($buyerLvl) {
                                    $query_three->where('agent_lvl_id', $buyerLvl);
                                }
                            ])
                     ->with(['get_promo_items' => function ($query_four) use ($buyerLvl) {
                                    $query_four->where('agent_lvl_id', $buyerLvl);
                                }
                            ])
                     ->where('carts.user_id', $buyerCode)
                     // ->where('carts.status', '1')
                     ->whereNull('carts.mall')
                     ->groupBy('carts.id')
                     ->get();

        $default_shipping_address = NULL;
        $cart_link_modified_price = NULL;
        $cart_link = NULL;
        $cart_link_remaining_qty = 0;
        if(!empty(request('cl'))){
            $cart_link = CartLink::where(DB::raw('md5(id)'), request('cl'))
                             ->first();

            $cart_link_remaining_qty = GlobalController::checkCartLinkQuantity($cart_link->id);

            if(!empty($cart_link->id) && $cart_link->status == '1'){
                $carts = CartLinkProductDetail::with(['get_product_det.get_agent_price_product' => function ($query) use ($buyerLvl) {
                                        $query->where('agent_lvl_id', $buyerLvl);
                                    }
                                ])
                                 ->with(['get_fv_det.get_agent_price_variation' => function ($query_two) use ($buyerLvl) {
                                                $query_two->where('agent_lvl_id', $buyerLvl);
                                            }
                                        ])
                                 ->with(['get_sv_det.get_agent_price_second_variation' => function ($query_three) use ($buyerLvl) {
                                                $query_three->where('agent_lvl_id', $buyerLvl);
                                            }
                                        ])
                                 ->with(['get_promo_items' => function ($query_four) use ($buyerLvl) {
                                                $query_four->where('agent_lvl_id', $buyerLvl);
                                            }
                                        ])
                                 ->where('cart_link_product_details.cart_link_id', $cart_link->id)
                                 ->groupBy('cart_link_product_details.id')
                                 ->get();

                if(!empty($cart_link->price)){
                    $cart_link_modified_price = $cart_link->price;
                }
            }

            $default_shipping_address = UserShippingAddress::where('user_id', $buyerCode)
                                                           ->first();

            $get_cart_details = GlobalController::get_cart_link_cart_details($cart_link->id, $buyerCode, $buyerLvl);
        }else{
            if(!empty($getUserDetails->get_default_shipping_address->id)){
                $default_shipping_address = $getUserDetails->get_default_shipping_address;
            }

            $get_cart_details = GlobalController::get_cart_details($buyerCode, $buyerLvl);
        }

        // if(empty($getUserDetails->id) && empty(request('cl'))){
        //     return redirect()->route('login');
        // }

        if($carts->isEmpty()){
            // Toastr::info("Cart is empty");
            if(isset($_COOKIE['global_language']) && !empty($_COOKIE['global_language'])){
                if($_COOKIE['global_language'] == '1'){
                    Toastr::info("购物车已空");
                }else{
                    Toastr::info("Cart Is Empty");
                }
            }else{
                Toastr::info("Cart Is Empty");

            }
            return redirect()->route('home');
        }

        $totalshipping_fees = 0;



        $totalshipping_fees = (!empty($totalshipping_fees)) ? $totalshipping_fees : 0;

        $get_cash_wallet_balance = GlobalController::get_cash_wallet_balance($buyerCode);

        $get_topup_wallet_balance = GlobalController::get_topup_wallet_balance($buyerCode);

        $countries = GlobalController::global_countries();

        $CodAddresses = CodAddress::get();

        $states = State::get();

        // exit();
        $product_pv = [];
        $get_pricing = [];
        $sub_item = [];
        $sub_item_price = [];
        $get_original_pricing = [];
        $flash_sale_active = [];
        $remaining_flash_sales_limit = [];
        
        foreach($carts as $cart){
            $product_pv[$cart->id] = $this->getProductPV($buyerCode, $cart->product_id, $cart->sub_category_id, $cart->second_sub_category_id);
            if(!empty($cart->flash_sale_product_id)){
                $get_pricing[$cart->id] = GlobalController::get_product_pricing(md5($cart->product_id), $buyerCode, $cart->sub_category_id, $cart->second_sub_category_id, "", "", '1');
            }else{
                $get_pricing[$cart->id] = GlobalController::get_product_pricing(md5($cart->product_id), $buyerCode, $cart->sub_category_id, $cart->second_sub_category_id, "", "", '1');
            }

            if(!empty($cart->add_on_id)){
                  $sub_item_price[$cart->id] = GlobalController::get_add_on_sub_item_price($buyerCode, $cart->add_on_id, $cart->product_id, $cart->sub_category_id, $cart->second_sub_category_id);
            }

            $flash_sale_active[$cart->id] = GlobalController::check_if_product_has_flash_sale_active($cart->product_id);

            if($flash_sale_active[$cart->id]){
                $get_original_pricing[$cart->id] = GlobalController::get_product_pricing(md5($cart->product_id), $buyerCode, $cart->sub_category_id, $cart->second_sub_category_id);
            }

            // $active_flash_sale = GlobalController::get_current_flash_sales();

            // if(!empty($active_flash_sale)){
            //     $current_active_flash_sale_product = FlashSaleProductDetail::where('flash_sale_id', $active_flash_sale->id)
            //                                                                 ->where('product_id', $cart->product_id);
                
            //     if(!empty($cart->sub_category_id)){
            //         $current_active_flash_sale_product = $current_active_flash_sale_product->where('variation_id', $cart->sub_category_id);
            //     }else{
            //         $current_active_flash_sale_product = $current_active_flash_sale_product->whereNull('variation_id');
            //     }

            //     if(!empty($cart->second_sub_category_id)){
            //         $current_active_flash_sale_product = $current_active_flash_sale_product->where('second_variation_id', $cart->second_sub_category_id);
            //     }else{
            //         $current_active_flash_sale_product = $current_active_flash_sale_product->whereNull('second_variation_id');
            //     }

            //     $current_active_flash_sale_product = $current_active_flash_sale_product->where('status', '1')
            //                                                                             ->first();

            //     if(!empty($current_active_flash_sale_product->id)){
            //         $sub_category_id = !empty($cart->sub_category_id) ? $cart->sub_category_id : null;
            //         $second_sub_category_id = !empty($cart->second_sub_category_id) ? $cart->second_sub_category_id : null;
            //         $balanceLimit = GlobalController::getFlashSalePurchaseLimit($cart->product_id, $sub_category_id, $second_sub_category_id, $buyerCode);
            
            //         if($balanceLimit == 'not enough stock'){
            //             $update = Cart::find($cart->id)->delete();
            //         }
            //     }
            // }
            $active_flash_sale = GlobalController::get_current_flash_sales();
            if(!empty($active_flash_sale)){
                $get_limit = GlobalController::get_current_flash_sale_product_detail($cart->product_id, $cart->sub_category_id, $cart->second_sub_category_id);

                if(!empty($get_limit)){
                    $price_ids = FlashSaleProductPrice::join('flash_sale_product_details as fd', 'fd.id', 'flash_sale_product_prices.flash_sale_product_detail_id')
                                            ->where('flash_sale_product_prices.flash_sale_product_detail_id', $get_limit->id)
                                            ->pluck('flash_sale_product_prices.id');

                    $transactions = Transaction::join('transaction_details as d', 'd.transaction_id', 'transactions.id')
                                            ->whereIn('transactions.status', ['1', '98'])
                                            ->whereBetween('transactions.created_at', [$active_flash_sale->start, $active_flash_sale->end])
                                            ->whereIn('d.flash_sale_product_price_id', $price_ids)
                                            ->sum('d.quantity');

                    $remaining_flash_sales_limit[$cart->id] = $get_limit->qty - $transactions;
                }
            }
        }

        // $vouchers = Promotion::where('status', '1')
        //                      ->where('start_date', '<=', date('Y-m-d H:i:s'))
        //                      ->where('end_date', '>=', date('Y-m-d H:i:s'))
        //                      ->get();

        $vouchers = AppliedPromotion::select('applied_promotions.*','p.end_date','p.start_date')->leftJoin('promotions as p', 'p.id', 'applied_promotions.promotion_id')->where('p.start_date', '<=', now())
                                ->where('p.end_date', '>=', now())->where('applied_promotions.user_id',$buyerCode)->whereIn('p.status',['1'])->groupBy('applied_promotions.discount_code')->get();
    
        $get_quantity = [];
        foreach($vouchers as $adjust){
            $get_quantity[$adjust->discount_code] = AppliedPromotion::where('user_id',$buyerCode)->whereIn('status',['99','1'])->where('discount_code', $adjust->discount_code)->count();
        }

        $applied_voucher = NULL;
        if (!empty($getUserDetails->id)) {
            $applied_voucher = $getUserDetails->get_applied_voucher;
        } else {
            $applied_voucher = AppliedPromotion::where('user_id', $buyerCode)
                                               ->where('status', '1')
                                               ->orderBy('created_at', 'desc')
                                               ->first();
        }

            $minSpend = 0;
            if (!empty($applied_voucher) && !empty($applied_voucher->get_voucher_detail)) {
                
                $minSpend = $applied_voucher->get_voucher_detail->minSpend ?? 0;
            }

        return view('frontend.checkout', ['carts'=>$carts,
                                          'getUserDetails'=>$getUserDetails,
                                          'get_cash_wallet_balance'=>$get_cash_wallet_balance,
                                          'get_topup_wallet_balance'=>$get_topup_wallet_balance,
                                          'countries'=>$countries,
                                          'CodAddresses'=>$CodAddresses,
                                          'totalshipping_fees'=>$totalshipping_fees,
                                          'states'=>$states,
                                          'get_cart_details'=>$get_cart_details,
                                          'get_quantity'=>$get_quantity,
                                          'default_shipping_address'=>$default_shipping_address,
                                          'cart_link_modified_price'=>$cart_link_modified_price,
                                          'cart_link'=>$cart_link,
                                          'cart_link_remaining_qty'=>$cart_link_remaining_qty],
                                          compact('product_pv',
                                                  'get_pricing',
                                                  'sub_item',
                                                  'remaining_flash_sales_limit',
                                                  'sub_item_price',
                                                  'get_original_pricing',
                                                  'flash_sale_active',
                                                  'vouchers',
                                                  'applied_voucher',
                                                  'minSpend'));
    }

    public function checkout_mall()
    {
        $check_authorize = GlobalController::check_authorize();
        if($check_authorize == 1){
            return Redirect::to('https://demoaccount.vesson.my/admin_login');
        }else{
            if(!empty(request('vm'))){
                return redirect()->route(request()->route()->getName());
            }
        }
        $getCurrentLogin = GlobalController::getCurrentLogin();
        $buyerCode = $getCurrentLogin['code'];
        $buyerLvl = $getCurrentLogin['lvl'];

        $getUserDetails = GlobalController::getUserDetails($buyerCode);

        $carts = Cart::with(['get_product_det.get_agent_price_product' => function ($query) use ($buyerLvl) {
                                    $query->where('agent_lvl_id', $buyerLvl);
                                }
                            ])
                     ->with(['get_fv_det.get_agent_price_variation' => function ($query_two) use ($buyerLvl) {
                                    $query_two->where('agent_lvl_id', $buyerLvl);
                                }
                            ])
                     ->with(['get_sv_det.get_agent_price_second_variation' => function ($query_three) use ($buyerLvl) {
                                    $query_three->where('agent_lvl_id', $buyerLvl);
                                }
                            ])
                     ->where('carts.user_id', $buyerCode)
                     ->where('carts.status', '1')
                     ->where('carts.mall', '1')
                     ->groupBy('carts.id')
                     ->get();

        if($carts->isEmpty()){
            Toastr::info("Cart is empty");
            return redirect()->route('home');
        }

        $totalshipping_fees = 0;



        $totalshipping_fees = (!empty($totalshipping_fees)) ? $totalshipping_fees : 0;

        $totalBalance = $this->GetCashWalletBalance($buyerCode);

        $GetProductWalletBalance = $this->GetProductWalletBalance($buyerCode);

        $GetPVWallet = GlobalController::get_point_wallet($buyerCode);

        // $countries = TblCountry::get();
        $countries = GlobalController::global_countries();

        $CodAddresses = CodAddress::get();

        $states = State::get();

        $get_cart_details = GlobalController::get_cart_details_mall($buyerCode, $buyerLvl);

        // Basic language data for the view
        $data = [
            'lang' => [
                'lang' => [
                    'recipient_address' => 'Recipient Address',
                    'billing_checkbox' => 'Billing address same as shipping address',
                    'country' => 'Country',
                    'select_country' => 'Select Country'
                ]
            ],
            'userGuardRole' => 'web'
        ];

        $product_pv = [];
        $get_pricing = [];
        foreach($carts as $cart){
            $product_pv[$cart->id] = $this->getProductPV($buyerCode, $cart->product_id, $cart->sub_category_id, $cart->second_sub_category_id);
            $get_pricing[$cart->id] = GlobalController::get_product_pricing(md5($cart->product_id), $buyerCode, $cart->sub_category_id , $cart->second_sub_category_id);
        }
        // exit();

        return view('frontend.checkout_mall', compact('carts',
                                                    'getUserDetails',
                                                    'totalBalance',
                                                    'GetProductWalletBalance',
                                                    'countries',
                                                    'CodAddresses',
                                                    'totalshipping_fees',
                                                    'states',
                                                    'get_cart_details',
                                                    'GetPVWallet',
                                                    'product_pv',
                                                    'get_pricing'));
    }
    public function placeOrder(Request $request){
        try{
            $getCurrentLogin = GlobalController::getCurrentLogin();
            $buyerCode = $getCurrentLogin['code'];
            $buyerLvl = $getCurrentLogin['lvl'];

            $getUserDetails = GlobalController::getUserDetails($buyerCode);
            
            if(empty($getUserDetails->id)){
                $get_guest_shipping_address = UserShippingAddress::where('user_id', $buyerCode)->where('default', '1')->first();
                
                if(!empty($get_guest_shipping_address->id)){
                    $getUserDetails = new Person();
                    $getUserDetails->get_default_shipping_address = $get_guest_shipping_address;
                }
            }

            if(!empty($getUserDetails->get_default_shipping_address->id)){
                $shipping_address = $getUserDetails->get_default_shipping_address;
            }else{
                return Redirect::back()->withInput($request->all())->withErrors("Please select your default shipping address.");
            }

            $same_bill_address = (!empty($request->same_billing_address) && $request->same_billing_address != '') ? 0 : 1;

            if($same_bill_address == 1){
                $validator = Validator::make($request->all(), [
                    'f_name_bill' => 'required',
                    'l_name_bill' => 'required',
                    'email_bill' => 'required',
                    'country_code_bill' => 'required',
                    'phone_bill' => 'required',
                    'address_bill' => 'required',
                    'state_bill' => 'required',
                    'city_bill' => 'required',
                    'postcode_bill' => 'required',
                    'country_bill' => 'required',
                ]);

                if ($validator->fails()) {
                    return Redirect::back()->withInput($request->all())->withErrors("Please ensure every details in billing address is filled correctly.");
                }
            }
            
            $cod = isset($request->cod) ? 1 : 0;
            $cod_address = "";
            if(!empty($cod)){
                $cod_address = $request->cod_address;
                $selfPickup = 1;
            }else{
                $selfPickup = NULL;
            }

            $cart_link = NULL;
            if(!empty($request->cart_link)){
                $cart_link = CartLink::where(DB::raw('md5(id)'), $request->cart_link)
                             ->first();

                $cart_link_remaining_qty = GlobalController::checkCartLinkQuantity($cart_link->id);

                if($cart_link_remaining_qty <= 0){
                    Toastr::error('Cart Link Quantity Exceeded');
                    return redirect()->route('home');
                }

                if(!empty($cart_link->id) && $cart_link->status == '1'){
                    $carts = CartLinkProductDetail::with(['get_product_det.get_agent_price_product' => function ($query) use ($buyerLvl) {
                                            $query->where('agent_lvl_id', $buyerLvl);
                                        }
                                    ])
                                     ->with(['get_fv_det.get_agent_price_variation' => function ($query_two) use ($buyerLvl) {
                                                    $query_two->where('agent_lvl_id', $buyerLvl);
                                                }
                                            ])
                                     ->with(['get_sv_det.get_agent_price_second_variation' => function ($query_three) use ($buyerLvl) {
                                                    $query_three->where('agent_lvl_id', $buyerLvl);
                                                }
                                            ])
                                     ->with(['get_promo_items' => function ($query_four) use ($buyerLvl) {
                                                    $query_four->where('agent_lvl_id', $buyerLvl);
                                                }
                                            ])
                                     ->where('cart_link_product_details.cart_link_id', $cart_link->id)
                                     ->groupBy('cart_link_product_details.id')
                                     ->get();

                    $get_cart_details = GlobalController::get_cart_link_cart_details($cart_link->id, $buyerCode, $buyerLvl, 0, $cod);
                }
            }else{
                $carts = Cart::with(['get_product_det.get_agent_price_product' => function ($query) use ($buyerLvl) {
                                            $query->where('agent_lvl_id', $buyerLvl);
                                        }
                                    ])
                             ->with(['get_fv_det.get_agent_price_variation' => function ($query_two) use ($buyerLvl) {
                                            $query_two->where('agent_lvl_id', $buyerLvl);
                                        }
                                    ])
                             ->with(['get_sv_det.get_agent_price_second_variation' => function ($query_three) use ($buyerLvl) {
                                            $query_three->where('agent_lvl_id', $buyerLvl);
                                        }
                                    ])
                             ->with(['get_promo_items' => function ($query_four) use ($buyerLvl) {
                                            $query_four->where('agent_lvl_id', $buyerLvl);
                                        }
                                    ])
                             ->where('carts.user_id', $buyerCode)
                             ->where('carts.status', '1')
                             ->whereNull('mall')
                             ->groupBy('carts.id')
                             ->get();
                $get_cart_details = GlobalController::get_cart_details($buyerCode, $buyerLvl, 0, $cod);
            }

            $get_cash_wallet_balance = GlobalController::get_cash_wallet_balance($buyerCode);
            $get_topup_wallet_balance = GlobalController::get_topup_wallet_balance($buyerCode);

            // $carts = Cart::where('carts.user_id', $buyerCode)
            //     ->where('carts.status', '1')
            //     ->whereNull('carts.mall')
            //     ->groupBy('carts.id')
            //     ->get();

            if(!$carts->isEmpty()){
                foreach($carts as $cart){
                    $active_flash_sale = GlobalController::get_current_flash_sales();
            
                    if(!empty($active_flash_sale)){
                        $current_active_flash_sale_product = FlashSaleProductDetail::where('flash_sale_id', $active_flash_sale->id)
                                                                                    ->where('product_id', $cart->product_id);
                        
                        if(!empty($cart->sub_category_id)){
                            $current_active_flash_sale_product = $current_active_flash_sale_product->where('variation_id', $cart->sub_category_id);
                        }else{
                            $current_active_flash_sale_product = $current_active_flash_sale_product->whereNull('variation_id');
                        }

                        if(!empty($cart->second_sub_category_id)){
                            $current_active_flash_sale_product = $current_active_flash_sale_product->where('second_variation_id', $cart->second_sub_category_id);
                        }else{
                            $current_active_flash_sale_product = $current_active_flash_sale_product->whereNull('second_variation_id');
                        }

                        $current_active_flash_sale_product = $current_active_flash_sale_product->where('status', '1')
                                                                                            ->first();

                        if(!empty($current_active_flash_sale_product->id)){
                            $sub_category_id = !empty($cart->sub_category_id) ? $cart->sub_category_id : null;
                            $second_sub_category_id = !empty($cart->second_sub_category_id) ? $cart->second_sub_category_id : null;
                            $balanceLimit = GlobalController::getFlashSalePurchaseLimit($cart->product_id, $sub_category_id, $second_sub_category_id, $buyerCode);
                            
                            if($balanceLimit == 'not enough stock'){
                                $update = Cart::find($cart->id)->delete();
                            }
                        }
                    }
                }
            }

            \DB::beginTransaction();

            $transaction = new Transaction();
            $transaction->transaction_no = GlobalController::GenerateTransactionNo();
            $transaction->user_id = $buyerCode;
            $transaction->weight = $get_cart_details['total_weight'];
            $transaction->sub_total = $get_cart_details['sub_total'];

            $transaction->discount_code = $get_cart_details['discount_code'];
            $transaction->discount_type = $get_cart_details['discount_type'];
            $transaction->discount_amount = $get_cart_details['discount_amount'];
            $transaction->discount = $get_cart_details['total_discount'];

            $transaction->shipping_fee = $get_cart_details['total_shipping_fee'];
            $transaction->grand_total = $get_cart_details['grand_total'];
            $transaction->address_name = $shipping_address->f_name;
            $transaction->address = $shipping_address->address;
            $transaction->postcode = $shipping_address->postcode;
            $transaction->city = $shipping_address->city;
            $transaction->state = $shipping_address->state;
            $transaction->country_code = $shipping_address->country_code;
            $transaction->phone = $shipping_address->phone;
            $transaction->email = $shipping_address->email;
            $transaction->country = $shipping_address->country;
            $transaction->store_stock = isset($request->store_stock) ? 1 : 0;
            $transaction->cart_link_id = !empty($cart_link->id) ? $cart_link->id : NULL;
            $transaction->cod_address = $cod_address;
            $transaction->self_pick = $selfPickup; 
            $transaction->different_billing_address = $same_bill_address;

            if($request->online == 1){
                $transaction->status = 95;

                if ($request->payment_gateway_setting_id == 2) { // Rev Pay
                    $transaction->revpay_payment_id = $request->payment_id;
                }

                $transaction->payment_gateway_setting_id = $request->payment_gateway_setting_id;
            }elseif($request->cdm == 1){
                $files = $request->file('bank_slip'); 
                $name = $files->getClientOriginalName();
                $exp = explode(".", $name);
                $file_ext = end($exp);
                $name = md5($name.date('Y-m-d H:i:s')).'.'.$file_ext;
                $files->move(GlobalController::get_image_path("uploads/bank_slip/".$buyerCode."/"), $name);

                $transaction->status = 98;
                $transaction->bank_slip = "uploads/bank_slip/".$buyerCode."/".$name;
            }elseif($request->cash_wallet == 1){
                if(number_format($get_cash_wallet_balance, 2, ".", '') < number_format($transaction->grand_total, 2, ".", '')){
                    throw new \Exception('Insufficient Cash Wallet Balance');
                }
                $transaction->status = 1;
                $transaction->mall = 1;
            }elseif($request->topup_wallet == 1){
                if(number_format($get_topup_wallet_balance, 2, ".", '') < number_format($transaction->grand_total, 2, ".", '')){
                    throw new \Exception('Insufficient Topup Wallet Balance '.number_format($get_topup_wallet_balance, 2, "", '.').' - '.number_format($transaction->grand_total, 2, "", '.'));
                }
                $transaction->status = 1;
                $transaction->mall = 2;
            }else{
                throw new \Exception("Error Payment Method!");
            }

            $transaction->save();

            // $GetCashWalletBalance = $this->GetCashWalletBalance($buyerCode);

            // if(floatval($GetCashWalletBalance) < floatval($transaction->grand_total)){
            //     throw new \Exception("Insufficient Wallet Balance");
            // }

            $item_weight = 0;
            $product_price = 0;
            $costing_price = 0;
            foreach($carts as $cart){
                
                $flash_sale_product_price_id = NULL;

                if(!empty($cart->add_on_id) && empty($cart->main_add_on)){
                    $product_price = GlobalController::get_add_on_sub_item_price($buyerCode, $cart->add_on_id, $cart->product_id, $cart->sub_category_id, $cart->second_sub_category_id);
                }else{
                    if(!empty($cart->flash_sale_product_id)){
                        $product_price = GlobalController::get_product_pricing(md5($cart->product_id), $buyerCode, $cart->sub_category_id, $cart->second_sub_category_id, "", "", '1');
                    }else{
                        $product_price = GlobalController::get_product_pricing(md5($cart->product_id), $buyerCode, $cart->sub_category_id, $cart->second_sub_category_id, "", "", '1');
                    }
                    
                    if(!empty($product_price['flash_sale_product_price_id'])){
                        $flash_sale_product_price_id = $product_price['flash_sale_product_price_id'];

                        $get_flash_sale_product_price = FlashSaleProductPrice::find($product_price['flash_sale_product_price_id']);

                        if(!empty($get_flash_sale_product_price->id) && !empty($get_flash_sale_product_price->get_flash_sale_product_detail->qty)){
                                                
                            $totalFlashSalePurchased = TransactionDetail::select(DB::raw('SUM(quantity) as totalPurchased'))
                                                                        ->join('transactions as t', 't.id', 'transaction_details.transaction_id')
                                                                        ->whereIn('t.status', ['98', '1'])
                                                                        ->where('flash_sale_product_price_id', $product_price['flash_sale_product_price_id'])
                                                                        ->where('t.user_id', $buyerCode)
                                                                        ->first();

                            $current_purchased = $totalFlashSalePurchased->totalPurchased + $cart->qty;

                            if($current_purchased > $get_flash_sale_product_price->get_flash_sale_product_detail->qty){

                                return Redirect::back()->withErrors("Flash Sale Product ".$cart->get_product_det->product_name.' Exceed Flash Sale Purchase Limit');
                                // throw new \Exception("Flash Sale Product ".$cart->get_product_det->product_name.' Exceed Flash Sale Purchase Limit');
                            }
                        }
                    }

                    $product_price = $product_price['product_price'];
                }

                if($cart->get_product_det->variation_enable == 1){
                    if($cart->get_product_det->second_variation_enable == 1){
                        $costing_price = $cart->get_sv_det->variation_costing_price;
                    }else{
                        $costing_price = $cart->get_fv_det->variation_costing_price;
                    }
                }else{
                    $costing_price = $cart->get_product_det->costing_price;
                }

                if($cart->get_product_det->variation_enable == 1){
                    if($cart->get_product_det->second_variation_enable == 1){
                        $get_point = $cart->get_sv_det->variation_get_point;
                    }else{
                        $get_point = $cart->get_fv_det->variation_get_point;
                    }
                }else{
                    $get_point = $cart->get_product_det->get_point;
                }

                if($cart->get_product_det->variation_enable == '1'){
                    if($cart->get_product_det->second_variation_enable == '1'){
                        $item_weight = $cart->get_sv_det->variation_weight;
                    }else{
                        $item_weight = $cart->get_fv_det->variation_weight;
                    }
                }else{
                    $item_weight = $cart->get_product_det->weight;
                }

                $product_pv = $this->getProductPV($cart->user_id, $cart->product_id, $cart->sub_category_id, $cart->second_sub_category_id);
                $store = 0;
                if(!empty($request->store_in_stock)){
                    if(in_array($cart->product_id, $request->store_in_stock)){
                        $store = 1;
                    }
                }
                
                $transactions_details = new TransactionDetail();
                $transactions_details->transaction_id = $transaction->id;
                $transactions_details->product_image = !empty($cart->get_product_det->first_image->image) ? $cart->get_product_det->first_image->image : '';
                $transactions_details->product_id = $cart->product_id;
                $transactions_details->variation_id = $cart->sub_category_id;
                $transactions_details->second_variation_id = $cart->second_sub_category_id;
                $transactions_details->item_code = $cart->get_product_det->item_code;
                $transactions_details->product_code = $cart->get_product_det->product_code;
                $transactions_details->unit_weight = $item_weight;
                $transactions_details->sub_category = !empty($cart->get_fv_det->variation_name) ? $cart->get_fv_det->variation_name : '';
                $transactions_details->second_sub_category = !empty($cart->get_sv_det->variation_name) ? $cart->get_sv_det->variation_name : '';
                $transactions_details->product_name = $cart->get_product_det->product_name;
                $transactions_details->is_birthday = $cart->is_birthday;
                $transactions_details->unit_price = $product_price;
                $transactions_details->costing_price = $costing_price;
                $transactions_details->quantity = $cart->qty;
                $transactions_details->get_point = $get_point;
                $transactions_details->voucher_id = $cart->get_product_det->voucher_id;
                $transactions_details->promo_item_id = $cart->promo;
                $transactions_details->get_pv = $product_pv;
                $transactions_details->add_on_id = !empty($cart->add_on_id) ? $cart->add_on_id : '';
                $transactions_details->main_add_on = !empty($cart->main_add_on) ? '1' : '0';
                $transactions_details->flash_sale_product_price_id = $flash_sale_product_price_id;
                $transactions_details->level_up = $cart->get_product_det->level_up;

                //Authorise Merchant
                $authorise_merchant = !empty($_COOKIE['vmerchant']) ? $_COOKIE['vmerchant'] : '';
                $get_authorise_status = GlobalController::check_autorize_status($authorise_merchant);
                if($get_authorise_status['status'] == 1){
                $transactions_details->merchant_id = !empty($get_authorise_status['result']['code']) ? $get_authorise_status['result']['code'] : '';
                }

                $transactions_details->store_in_stock = $store;

                
                $transactions_details->save();

                if(!empty($cart->get_product_det->packages)){
                    foreach($cart->get_product_det->get_product_packages as $package){
                        $transactions_packages = new TransactionPackage();
                        $transactions_packages->detail_id = $transactions_details->id;
                        $transactions_packages->product_id = $package->products;
                        $transactions_packages->variation_id = $package->variation_id;
                        $transactions_packages->second_variation_id = $package->second_variation_id;
                        $transactions_packages->voucher_id = $package->voucher_id;
                        $transactions_packages->quantity = $package->qty;

                        $transactions_packages->save();
                    }
                }
            }

            if($same_bill_address == 1){
                $billing_address = new TransactionBillingAddress();
                $billing_address->transaction_id = $transaction->id;;
                $billing_address->address_name = $request->f_name_bill . ' ' . $request->l_name_bill;
                $billing_address->email = $request->email_bill;
                $billing_address->country_code = $request->country_code_bill;
                $billing_address->phone = $request->phone_bill;
                $billing_address->address = $request->address_bill;
                $billing_address->state = $request->state_bill;
                $billing_address->city = $request->city_bill;
                $billing_address->postcode = $request->postcode_bill;
                $billing_address->country = $request->country_bill;
                $billing_address->save();
            }

            if($transaction->status == 1){
                if(!empty($cart->get_product_det->voucher_id)){
                    $input_voucher = new AppliedPromotion();
                    $input_voucher->promotion_id = $cart->get_product_det->voucher_id;
                    $input_voucher->user_id = $buyerCode;
                    $input_voucher->transaction_id = $transaction->id;
                    $input_voucher->status = 99;
                    
                    $input_voucher->save();
                }
            }
            // exit();
            $delete_cart = Cart::where('user_id', $buyerCode)->whereNull('mall')->delete();

            $update_voucher = AppliedPromotion::where('status', '1')->where('user_id', $buyerCode)->first();
            if(!empty($update_voucher->id)){
                $update_voucher->status = 2;
                $update_voucher->save();
            }

            if($transaction->mall == '1' || $transaction->mall == '2'){
            	$isMember = User::where('code', $transaction->user_id)->first();
                $isAgent = Agent::where('code', $transaction->user_id)->first();

                $transaction_voucher_assign = GlobalController::transaction_voucher_assign($transaction->id);
                if($transaction_voucher_assign != 'ok'){
                    throw new \Exception($transaction_voucher_assign);
                }

                $upgrade_agent_with_package = GlobalController::upgrade_agent_with_package($transaction->transaction_no);
                if($upgrade_agent_with_package != 'ok'){
                    throw new \Exception($upgrade_agent_with_package);
                }

                $rebate_commission = GlobalController::rebate_commission($transaction->user_id, $transaction->transaction_no);
                if($rebate_commission != 'ok'){
                    throw new \Exception($rebate_commission);
                }

                $heirarchy_commission = GlobalController::heirarchy_commission($transaction->user_id, $transaction->transaction_no);
                if($heirarchy_commission != 'ok'){
                    throw new \Exception($heirarchy_commission);
                }

                $purchase_from_customer_deduct_stock_commission = GlobalController::purchase_from_customer_deduct_stock_commission($transaction->transaction_no);
                if($purchase_from_customer_deduct_stock_commission != 'ok'){
                    throw new \Exception($purchase_from_customer_deduct_stock_commission);
                }
            }

            // send to buyer
            if(!empty($transaction->transaction_no)){
                $send_order_notification = GlobalController::send_order_notification($transaction->transaction_no);
                if($send_order_notification !== 'ok'){
                    throw new \Exception($send_order_notification);
                }
            }

            \DB::commit();


            try {
                $first_admin = Admin::first();
                $transaction = Transaction::find($transaction->id);
                $transaction_details = TransactionDetail::where('transaction_id', $transaction->id)
                                                        ->get();

                $buyer_details = GlobalController::getUserDetails($transaction->user_id);

                if(!empty($buyer_details->master_id)){
                    $upline_details = GlobalController::getUserDetails($buyer_details->master_id);
                }else{
                    $upline_details = NULL;
                }

                $to_email = !empty($first_admin->contact_email) ? $first_admin->contact_email : 'sonezack5577@gmail.com';

                Mail::to($to_email)->send(new \App\Mail\NewOrderNotification($transaction, $transaction_details, $buyer_details, $upline_details));

            } catch (\Exception $e) {

            }

            Toastr::success('Place Order Successfully');
            if(!empty(Auth::guard('web')->check()) || !empty(Auth::guard('agent')->check()) || !empty(Auth::guard('admin')->check())){
                if($request->cdm == 1){
                    return redirect()->route('verifying_order');
                }else{
                    return redirect()->route('pending_shipping');
                }
            }else{
                return redirect()->route('home');
            }

            if ($request->online == 1) {
                if ($request->payment_gateway_setting_id == 1) { // Senang Pay
                    return \Redirect::route('SenangPay_PaymentProcess', array('transactions'=>md5($transaction->id)));
                }

                if ($request->payment_gateway_setting_id == 2) { //Rev Pay
                    return \Redirect::route('RevPay_PaymentProcess', array('transactions'=>md5($transaction->id), 'payment_id'=>$request->payment_id));
                }

                if ($request->payment_gateway_setting_id == 3) { // Sure Pay
                    return \Redirect::route('SurePay_PaymentProcess', array('transactions'=>md5($transaction->id)));
                }

                if ($request->payment_gateway_setting_id == 4) { // G Kash
                    return \Redirect::route('GKash_PaymentProcess', array('transactions'=>md5($transaction->id)));
                }
            }

        } catch (\Exception $e){
            \DB::rollback();
            return Redirect::back()->withInput($request->all())->withErrors($e->getMessage().' '.$e->getLine());
        }
    }


    public function placeOrderMall(Request $request)
    {
        try{

            if(!empty(Auth::guard('admin')->check())){
                $buyerCode = Auth::guard('admin')->user()->code;
                $buyerLvl = Auth::guard('admin')->user()->lvl;
                $buyerFileLvl = Auth::guard('admin')->user()->file_member;
                $buyerAgentLvl = Auth::guard('admin')->user()->agent_lvl;
            }elseif(!empty(Auth::guard('agent')->check())){

                $buyerCode = Auth::guard('agent')->user()->code;
                $buyerLvl = Auth::guard('agent')->user()->lvl;
                $buyerFileLvl = Auth::guard('agent')->user()->file_member;
                $buyerAgentLvl = Auth::guard('agent')->user()->agent_lvl;

            }elseif(!empty(Auth::guard('web')->check())){

                $buyerCode = Auth::guard('web')->user()->code;
                $buyerLvl = Auth::guard('web')->user()->lvl;
                $buyerFileLvl = Auth::guard('web')->user()->file_member;
                $buyerAgentLvl = Auth::guard('web')->user()->agent_lvl;

            }elseif(!empty(Auth::guard('corporate')->check())){

                $buyerCode = Auth::guard('corporate')->user()->code;
                $buyerLvl = Auth::guard('corporate')->user()->lvl;
                $buyerFileLvl = Auth::guard('corporate')->user()->file_member;
                $buyerAgentLvl = Auth::guard('corporate')->user()->agent_lvl;

            }else{
                if(empty($_COOKIE['new_guest'])){
                    $buyerCode = setcookie('new_guest', strtotime(date('Y-m-d H:i:s')).rand(100000, 999999), time() + (86400 * 30), "/");
                }else{
                    $buyerCode = $_COOKIE['new_guest'];
                }
                $buyerLvl = "";
                $buyerFileLvl = "";
                $buyerAgentLvl = "";
            }

            $GetPVWallet = GlobalController::get_point_wallet($buyerCode);

            $getUserDetails = GlobalController::getUserDetails($buyerCode);

            if(!empty($getUserDetails->get_default_shipping_address->id)){
                $shipping_address = $getUserDetails->get_default_shipping_address;
            }else{
                return Redirect::back()->withInput($request->all())->withErrors("Please select your default shipping address.");
            }

            $same_bill_address = (!empty($request->same_billing_address) && $request->same_billing_address != '') ? 0 : 1;

            if($same_bill_address == 1){
                $validator = Validator::make($request->all(), [
                      'f_name_bill' => 'required',
                      'l_name_bill' => 'required',
                      'email_bill' => 'required',
                      'country_code_bill' => 'required',
                      'phone_bill' => 'required',
                      'address_bill' => 'required',
                      'state_bill' => 'required',
                      'city_bill' => 'required',
                      'postcode_bill' => 'required',
                      'country_bill' => 'required',
                ]);

                if ($validator->fails()) {
                    return Redirect::back()->withInput($request->all())->withErrors("Please ensure every details in billing address is filled correctly.");
                }
            }

            $carts = Cart::with(['get_product_det.get_agent_price_product' => function ($query) use ($buyerLvl) {
                                        $query->where('agent_lvl_id', $buyerLvl);
                                    }
                                ])
                         ->with(['get_fv_det.get_agent_price_variation' => function ($query_two) use ($buyerLvl) {
                                        $query_two->where('agent_lvl_id', $buyerLvl);
                                    }
                                ])
                         ->with(['get_sv_det.get_agent_price_second_variation' => function ($query_three) use ($buyerLvl) {
                                        $query_three->where('agent_lvl_id', $buyerLvl);
                                    }
                                ])
                         ->where('carts.user_id', $buyerCode)
                         ->where('carts.status', '1')
                         ->whereNotNull('mall')
                         ->groupBy('carts.id')
                         ->get();
            

            $get_cart_details = GlobalController::get_cart_details_mall($buyerCode, $buyerLvl);

            \DB::beginTransaction();

            $transaction = new Transaction();
            $transaction->transaction_no = GlobalController::GenerateTransactionNo();
            $transaction->user_id = $buyerCode;
            $transaction->pv_purchase = 1;
            $transaction->weight = $get_cart_details['total_weight'];
            $transaction->sub_total = $get_cart_details['sub_total'];
            $transaction->shipping_fee = $get_cart_details['total_shipping_fee'];
            $transaction->grand_total = $get_cart_details['grand_total'];
            $transaction->address_name = $shipping_address->f_name;
            $transaction->address = $shipping_address->address;
            $transaction->postcode = $shipping_address->postcode;
            $transaction->city = $shipping_address->city;
            $transaction->state = $shipping_address->state;
            $transaction->country_code = $shipping_address->country_code;
            $transaction->phone = $shipping_address->phone;
            $transaction->email = $shipping_address->email;
            $transaction->country = $shipping_address->country;
            $transaction->different_billing_address = $same_bill_address;

            $transaction->save();

            // echo $GetPVWallet.' - '.$transaction->grand_total;
            // exit();
            if(floatval($GetPVWallet) < floatval($transaction->grand_total)){
                throw new \Exception("Insufficient Wallet Balance");
            }

            $item_weight = 0;
            $product_price = 0;
            $costing_price = 0;
            foreach($carts as $cart){
                
                // if($cart->get_product_det->variation_enable == 1){
                //     if($cart->get_product_det->second_variation_enable == 1){
                //         $product_price = !empty($cart->get_sv_det->variation_special_price) ? $cart->get_sv_det->variation_special_price : $cart->get_sv_det->variation_price;
                //     }else{
                //         $product_price = !empty($cart->get_fv_det->variation_special_price) ? $cart->get_fv_det->variation_special_price : $cart->get_fv_det->variation_price;
                //     }
                // }else{
                //     $product_price = !empty($cart->get_product_det->special_price) ? $cart->get_product_det->special_price : $cart->get_product_det->price;
                // }

                if(!empty($cart->add_on_id) && empty($cart->main_add_on)){
                    $product_price = GlobalController::get_add_on_sub_item_price($buyerCode, $cart->add_on_id, $cart->product_id, $cart->sub_category_id, $cart->second_sub_category_id);
                }else{
                    if(!empty($cart->flash_sale_product_id)){
                        $product_price = GlobalController::get_product_pricing(md5($cart->product_id), $buyerCode, $cart->sub_category_id, $cart->second_sub_category_id, "", "", '1');
                    }else{
                        $product_price = GlobalController::get_product_pricing(md5($cart->product_id), $buyerCode, $cart->sub_category_id, $cart->second_sub_category_id, "", "", '1');
                    }
                    
                    if(!empty($product_price['flash_sale_product_price_id'])){
                        $flash_sale_product_price_id = $product_price['flash_sale_product_price_id'];

                        $get_flash_sale_product_price = FlashSaleProductPrice::find($product_price['flash_sale_product_price_id']);

                        if(!empty($get_flash_sale_product_price->id) && !empty($get_flash_sale_product_price->get_flash_sale_product_detail->qty)){
                                                
                            $totalFlashSalePurchased = TransactionDetail::select(DB::raw('SUM(quantity) as totalPurchased'))
                                                                        ->join('transactions as t', 't.id', 'transaction_details.transaction_id')
                                                                        ->whereIn('t.status', ['98', '1'])
                                                                        ->where('flash_sale_product_price_id', $product_price['flash_sale_product_price_id'])
                                                                        ->where('t.user_id', $buyerCode)
                                                                        ->first();

                            $current_purchased = $totalFlashSalePurchased->totalPurchased + $cart->qty;

                            if($current_purchased > $get_flash_sale_product_price->get_flash_sale_product_detail->qty){

                                return Redirect::back()->withErrors("Flash Sale Product ".$cart->get_product_det->product_name.' Exceed Flash Sale Purchase Limit');
                                // throw new \Exception("Flash Sale Product ".$cart->get_product_det->product_name.' Exceed Flash Sale Purchase Limit');
                            }
                        }
                    }

                    $product_price = $product_price['product_price'];
                }

                if($cart->get_product_det->variation_enable == 1){
                    if($cart->get_product_det->second_variation_enable == 1){
                        $costing_price = $cart->get_sv_det->variation_costing_price;
                    }else{
                        $costing_price = $cart->get_fv_det->variation_costing_price;
                    }
                }else{
                    $costing_price = $cart->get_product_det->costing_price;
                }

                if($cart->get_product_det->variation_enable == 1){
                    if($cart->get_product_det->second_variation_enable == 1){
                        $get_point = $cart->get_sv_det->variation_get_point;
                    }else{
                        $get_point = $cart->get_fv_det->variation_get_point;
                    }
                }else{
                    $get_point = $cart->get_product_det->get_point;
                }

                if($cart->get_product_det->variation_enable == '1'){
                    if($cart->get_product_det->second_variation_enable == '1'){
                        $item_weight = $cart->get_sv_det->variation_weight;
                    }else{
                        $item_weight = $cart->get_fv_det->variation_weight;
                    }
                }else{
                    $item_weight = $cart->get_product_det->weight;
                }
                
                $transactions_details = new TransactionDetail();
                $transactions_details->transaction_id = $transaction->id;
                $transactions_details->product_image = !empty($cart->get_product_det->first_image->image) ? $cart->get_product_det->first_image->image : '';
                $transactions_details->product_id = $cart->product_id;
                $transactions_details->variation_id = $cart->sub_category_id;
                $transactions_details->second_variation_id = $cart->second_sub_category_id;
                $transactions_details->item_code = $cart->get_product_det->item_code;
                $transactions_details->product_code = $cart->get_product_det->product_code;
                $transactions_details->unit_weight = $item_weight;
                $transactions_details->sub_category = !empty($cart->get_fv_det->variation_name) ? $cart->get_fv_det->variation_name : '';
                $transactions_details->second_sub_category = !empty($cart->get_sv_det->variation_name) ? $cart->get_sv_det->variation_name : '';
                $transactions_details->product_name = $cart->get_product_det->product_name;
                $transactions_details->unit_price = $product_price;
                $transactions_details->costing_price = $costing_price;
                $transactions_details->quantity = $cart->qty;
                $transactions_details->voucher_id = $cart->get_product_det->voucher_id;
                
                $transactions_details->save();
            }

            if($same_bill_address == 1){
                $billing_address = new TransactionBillingAddress();
                $billing_address->transaction_id = $transaction->id;;
                $billing_address->address_name = $request->f_name_bill . ' ' . $request->l_name_bill;
                $billing_address->email = $request->email_bill;
                $billing_address->country_code = $request->country_code_bill;
                $billing_address->phone = $request->phone_bill;
                $billing_address->address = $request->address_bill;
                $billing_address->state = $request->state_bill;
                $billing_address->city = $request->city_bill;
                $billing_address->postcode = $request->postcode_bill;
                $billing_address->country = $request->country_bill;
                $billing_address->save();
            }

            if(!empty($cart->get_product_det->voucher_id)){
                $input_voucher = new AppliedPromotion();
                $input_voucher->promotion_id = $cart->get_product_det->voucher_id;
                $input_voucher->user_id = $buyerCode;
                $input_voucher->transaction_id = $transaction->id;
                $input_voucher->status = 99;
                
                $input_voucher->save();
            }
            
            $delete_cart = Cart::where('user_id', $buyerCode)->whereNotNull('mall')->delete();

            // send to buyer
            if(!empty($transaction->transaction_no)){
                $send_order_notification = GlobalController::send_order_notification($transaction->transaction_no);
                if($send_order_notification !== 'ok'){
                    throw new \Exception($send_order_notification);
                }
            }

            \DB::commit();

            Toastr::success('Place Order Successfully');
            if(!empty(Auth::guard('web')->check()) || !empty(Auth::guard('agent')->check()) || !empty(Auth::guard('admin')->check())){
                if($request->cdm == 1){
                    return redirect()->route('verifying_order');
                }else{
                    return redirect()->route('pending_shipping');
                }
            }else{
                return redirect()->route('home');
            }

        } catch (\Exception $e){
            \DB::rollback();
            return Redirect::back()->withInput($request->all())->withErrors($e->getMessage());
        }
    }

    public function PaymentProcess($transaction)
    {
        $transactions = Transaction::where(DB::raw('md5(id)'), $transaction)->first();

        return view('frontend.payment_processing', ['transactions'=>$transactions]);
    }

    public function SenangPay_PaymentProcess($transaction)
    {
        $transactions = Transaction::where(DB::raw('md5(id)'), $transaction)->first();

        return view('frontend.senangpay_payment_processing', ['transactions'=>$transactions]);
    }

    public function RevPay_PaymentProcess($transaction, $payment_id)
    {
        $transactions = Transaction::where(DB::raw('md5(id)'), $transaction)->first();

        $state = State::find($transactions->state);

        return view('frontend.revpay_payment_processing', ['transactions'=>$transactions, 'payment_id'=>$payment_id, 'state'=>$state]);
    }

    public function SurePay_PaymentProcess($transaction)
    {
        $transactions = Transaction::where(DB::raw('md5(id)'), $transaction)->first();

        return view('frontend.surepay_payment_processing', ['transactions'=>$transactions]);
    }

    public function GKash_PaymentProcess($transaction)
    {
        $transactions = Transaction::where(DB::raw('md5(id)'), $transaction)->first();

        return view('frontend.gkash_payment_processing', ['transactions'=>$transactions]);
    }

    public function TopupPaymentProcess($user_id, $amount)
    {
        return view('frontend.topup_payment_processing', ['user_id'=>$user_id, 'amount'=>$amount]);
    }

    public function Payment_Error()
    {
        return view('frontend.payment_error');
    }

    public function payment_successfully(Request $request)
    {
        // $json = $request->json()->all();

        // if($json['status'] == '4'){
        //     $select = Transaction::where('transaction_no', $json['order']['orderReferenceId'])->first();
        // }
    }
    public function senangpay_payment_successfully(Request $request)
    {
        try{
            \DB::beginTransaction();

            if($request->status_id == '1'){
                $transaction = Transaction::where('transaction_no', $request->order_id)->first();

                if($transaction->status != '1'){
                    $isMember = User::where('code', $transaction->user_id)->first();
                    $isAgent = Agent::where('code', $transaction->user_id)->first();
                    $get_merchant_register = Agent::where('register_transaction', $transaction->transaction_no)->first();

                    if (!empty($get_merchant_register->id)) {
                        $get_merchant_register->status = 1;
                        $get_merchant_register->verify_status = 1;
                        $get_merchant_register->save();

                        $add_affiliates = GlobalController::add_affiliates($get_merchant_register->code, $get_merchant_register->master_id);
                        if ($add_affiliates != 'ok') {
                            throw new \Exception($add_affiliates);
                            // $Generate_Refferal_Reward = $this->Generate_Refferal_Reward($get_merchant->master_id);
                        }
                    }
        
                    $transaction_voucher_assign = GlobalController::transaction_voucher_assign($transaction->id);
                    if($transaction_voucher_assign != 'ok'){
                        throw new \Exception($transaction_voucher_assign);
                    }

                    if(!empty($isMember->id) || !empty($isAgent->id)){
                        $upgrade_agent_with_package = GlobalController::upgrade_agent_with_package($transaction->transaction_no);
                        if($upgrade_agent_with_package != 'ok'){
                            throw new \Exception($upgrade_agent_with_package);
                        }
                    }

                    $rebate_commission = GlobalController::rebate_commission($transaction->user_id, $transaction->transaction_no);
                    if($rebate_commission != 'ok'){
                        throw new \Exception($rebate_commission);
                    }

                    $heirarchy_commission = GlobalController::heirarchy_commission($transaction->user_id, $transaction->transaction_no);
                    if($heirarchy_commission != 'ok'){
                        throw new \Exception($heirarchy_commission);
                    }

                    $purchase_from_customer_deduct_stock_commission = GlobalController::purchase_from_customer_deduct_stock_commission($transaction->transaction_no);
                    if($purchase_from_customer_deduct_stock_commission != 'ok'){
                        throw new \Exception($purchase_from_customer_deduct_stock_commission);
                    }

                    $transaction->status = 1;
                    $transaction->save();
                }
            }

            \DB::commit();
        }catch (\Exception $e){
            \DB::rollback();
            return $e->getMessage().' - '.$e->getLine();
        }catch(\Error $e){
            \DB::rollback();
            return $e->getMessage().' - '.$e->getLine();
        }

        return "OK";
    }

    public function revpay_payment_successfully(Request $request)
    {
        try{
            \DB::beginTransaction();

            if($_POST['Response_Code'] == '00'){
                $select = Transaction::where('transaction_no', $_POST['Reference_Number'])->first();
                $transaction = Transaction::find($select->id);

                if($transaction->status != '1'){
                    $isMember = User::where('code', $transaction->user_id)->first();
                    $isAgent = Agent::where('code', $transaction->user_id)->first();
                    $get_merchant_register = Agent::where('register_transaction', $transaction->transaction_no)->first();

                    if (!empty($get_merchant_register->id)) {
                        $get_merchant_register->status = 1;
                        $get_merchant_register->verify_status = 1;
                        $get_merchant_register->save();

                        $add_affiliates = GlobalController::add_affiliates($get_merchant_register->code, $get_merchant_register->master_id);
                        if ($add_affiliates != 'ok') {
                            throw new \Exception($add_affiliates);
                            // $Generate_Refferal_Reward = $this->Generate_Refferal_Reward($get_merchant->master_id);
                        }
                    }
        
                    $transaction_voucher_assign = GlobalController::transaction_voucher_assign($transaction->id);
                    if($transaction_voucher_assign != 'ok'){
                        throw new \Exception($transaction_voucher_assign);
                    }

                    if(!empty($isMember->id) || !empty($isAgent->id)){
                        $upgrade_agent_with_package = GlobalController::upgrade_agent_with_package($transaction->transaction_no);
                        if($upgrade_agent_with_package != 'ok'){
                            throw new \Exception($upgrade_agent_with_package);
                        }
                    }

                    $rebate_commission = GlobalController::rebate_commission($transaction->user_id, $transaction->transaction_no);
                    if($rebate_commission != 'ok'){
                        throw new \Exception($rebate_commission);
                    }

                    $heirarchy_commission = GlobalController::heirarchy_commission($transaction->user_id, $transaction->transaction_no);
                    if($heirarchy_commission != 'ok'){
                        throw new \Exception($heirarchy_commission);
                    }

                    $purchase_from_customer_deduct_stock_commission = GlobalController::purchase_from_customer_deduct_stock_commission($transaction->transaction_no);
                    if($purchase_from_customer_deduct_stock_commission != 'ok'){
                        throw new \Exception($purchase_from_customer_deduct_stock_commission);
                    }

                    $transaction->status = 1;
                    $transaction->save();
                }
            }

            $merchant_id = $_POST['Revpay_Merchant_ID'];
            $payment_id = $_POST['Payment_ID'];
            $transaction_id = $_POST['Transaction_ID'];
            $reference_number = $_POST['Reference_Number'];
            // $bank_reference_number = $_POST['Bank_Reference_Number'];
            $amount = $_POST['Amount'];
            $currency = $_POST['Currency'];
            $transaction_description = $_POST['Transaction_Description'];
            $response_code = $_POST['Response_Code'];
            $key_index = $_POST['Key_Index'];
            $signature = $_POST['Signature'];
            $request_datetime = $_POST['Request_Datetime'];
            $response_datetime = $_POST['Response_Datetime'];
            // Validate the response parameters from revPAY
            if (
            !empty($merchant_id) &&
            !empty($payment_id) &&
            !empty($transaction_id) &&
            !empty($reference_number) &&
            !empty($amount) &&
            !empty($currency) &&
            !empty($transaction_description) &&
            !empty($response_code) &&
            !empty($key_index) &&
            !empty($signature) &&
            !empty($request_datetime) &&
            !empty($response_datetime)) {
            // TO DO <Add your programming code here>
            // Return string OK if no missing parameter value found.
                echo 'OK';
            } else {
            // If found missing parameter
                echo 'Not OK';
            }

            
            \DB::commit();
        }catch (\Exception $e){
            \DB::rollback();
            return $e->getMessage().' - '.$e->getLine();
        }catch(\Error $e){
            \DB::rollback();
            return $e->getMessage().' - '.$e->getLine();
        }

        return "OK";
    }

    public function surepay_payment_successfully(Request $request)
    {
        try {
            \DB::beginTransaction();

            if($request->status == '1'){
                $transaction = Transaction::where('transaction_no', $request->refid)->first();
                $amount = $transaction->grand_total - $transaction->shipping_fee - $transaction->processing_fee + $transaction->discount;

                if ($transaction->status != '1') {
                    $isMember = User::where('code', $transaction->user_id)->first();
                    $isAgent = Agent::where('code', $transaction->user_id)->first();
                    $get_merchant_register = Agent::where('register_transaction', $transaction->transaction_no)->first();

                    if (!empty($get_merchant_register->id)) {
                        $get_merchant_register->status = 1;
                        $get_merchant_register->verify_status = 1;
                        $get_merchant_register->save();

                        $add_affiliates = GlobalController::add_affiliates($get_merchant_register->code, $get_merchant_register->master_id);
                        if ($add_affiliates != 'ok') {
                            throw new \Exception($add_affiliates);
                            // $Generate_Refferal_Reward = $this->Generate_Refferal_Reward($get_merchant->master_id);
                        }
                    }

                    $transaction_voucher_assign = GlobalController::transaction_voucher_assign($transaction->id);
                    if ($transaction_voucher_assign != 'ok') {
                        throw new \Exception($transaction_voucher_assign);
                    }

                    if (
                        !empty($isMember->id) ||
                        !empty($isAgent->id)
                    ) {
                        $upgrade_agent_with_package = GlobalController::upgrade_agent_with_package($transaction->transaction_no);
                        if ($upgrade_agent_with_package != 'ok') {
                        throw new \Exception($upgrade_agent_with_package);
                        }
                    }

                    if (empty($transaction->commission_disabled)) {
                        $rebate_commission = GlobalController::rebate_commission($transaction->user_id, $transaction->transaction_no);
                        if ($rebate_commission != 'ok') {
                        throw new \Exception($rebate_commission);
                        }

                        $heirarchy_commission = GlobalController::heirarchy_commission($transaction->user_id, $transaction->transaction_no);
                        if ($heirarchy_commission != 'ok') {
                        throw new \Exception($heirarchy_commission);
                        }
                    }

                    $purchase_from_customer_deduct_stock_commission = GlobalController::purchase_from_customer_deduct_stock_commission($transaction->transaction_no);
                    if ($purchase_from_customer_deduct_stock_commission != 'ok') {
                        throw new \Exception($purchase_from_customer_deduct_stock_commission);
                    }

                    $transaction->status = 1;
                    $transaction->save();
                }
            }
        
            \DB::commit();
        }catch (\Exception $e){
            \DB::rollback();
            return $e->getMessage().' - '.$e->getLine();
        }catch(\Error $e){
            \DB::rollback();
            return $e->getMessage().' - '.$e->getLine();
        }

        return "OK";
    }

    public function gkash_payment_successfully(Request $request)
    {
        try {
            \DB::beginTransaction();

            // Log::info($request->status);

            echo "OK";

            if($request->status == '88 - Transferred'){
                $select = Transaction::where('transaction_no', $request->cartid)->first();
                $transaction = Transaction::find($select->id);
                if ($transaction->status != '1') {
                    $isMember = User::where('code', $transaction->user_id)->first();
                    $isAgent = Agent::where('code', $transaction->user_id)->first();
                    $get_merchant_register = Agent::where('register_transaction', $transaction->transaction_no)->first();

                    if (!empty($get_merchant_register->id)) {
                        $get_merchant_register->status = 1;
                        $get_merchant_register->verify_status = 1;
                        $get_merchant_register->save();

                        $add_affiliates = GlobalController::add_affiliates($get_merchant_register->code, $get_merchant_register->master_id);
                        if ($add_affiliates != 'ok') {
                            throw new \Exception($add_affiliates);
                            // $Generate_Refferal_Reward = $this->Generate_Refferal_Reward($get_merchant->master_id);
                        }
                    }

                    $transaction_voucher_assign = GlobalController::transaction_voucher_assign($transaction->id);
                    if ($transaction_voucher_assign != 'ok') {
                        throw new \Exception($transaction_voucher_assign);
                    }

                    if (
                        !empty($isMember->id) ||
                        !empty($isAgent->id)
                    ) {
                        $upgrade_agent_with_package = GlobalController::upgrade_agent_with_package($transaction->transaction_no);
                        if ($upgrade_agent_with_package != 'ok') {
                        throw new \Exception($upgrade_agent_with_package);
                        }
                    }

                    if (empty($transaction->commission_disabled)) {
                        $rebate_commission = GlobalController::rebate_commission($transaction->user_id, $transaction->transaction_no);
                        if ($rebate_commission != 'ok') {
                        throw new \Exception($rebate_commission);
                        }

                        $heirarchy_commission = GlobalController::heirarchy_commission($transaction->user_id, $transaction->transaction_no);
                        if ($heirarchy_commission != 'ok') {
                        throw new \Exception($heirarchy_commission);
                        }
                    }

                    $purchase_from_customer_deduct_stock_commission = GlobalController::purchase_from_customer_deduct_stock_commission($transaction->transaction_no);
                    if ($purchase_from_customer_deduct_stock_commission != 'ok') {
                        throw new \Exception($purchase_from_customer_deduct_stock_commission);
                    }

                    $transaction->status = 1;
                    $transaction->gkash_payment_method = $request->PaymentType;
                    $transaction->save();
                }
            }

            \DB::commit();
        }catch (\Exception $e){
            \DB::rollback();
            return $e->getMessage().' - '.$e->getLine();
        }catch(\Error $e){
            \DB::rollback();
            return $e->getMessage().' - '.$e->getLine();
        }

        return "OK";
    }

    public function gkash_payment_status(Request $request)
    {
        $transaction = Transaction::where('transaction_no', $request->cartid)->first();

        $is_successful = 0;
        if($request->status == '88 - Transferred'){
            $is_successful = 1; 
        }

        return view('frontend.gkash_payment_status', compact('is_successful', 'transaction'));
    }

    public function mall()
    {
        if(Auth::guard('web')->check()){
          $user = Auth::guard('web')->check();
          $userCode = Auth::guard('web')->user()->code;
          $buyerLvl = Auth::guard('web')->user()->lvl;
        }elseif (Auth::guard('agent')->check()) {
          $user = Auth::guard('agent')->check();
          $userCode = Auth::guard('agent')->user()->code;
          $buyerLvl = Auth::guard('agent')->user()->lvl;
        }elseif (Auth::guard('admin')->check()) {
          $user = Auth::guard('admin')->check();
          $userCode = Auth::guard('admin')->user()->code;
          $buyerLvl = Auth::guard('admin')->user()->lvl;
        }else{
          $user = "";
          $userCode = "";
          $buyerLvl = "";
        }

        $leftJoin = DB::raw("(SELECT * FROM product_images ORDER BY sort_level ASC) AS i");
        $products = Product::with(['get_agent_min_max_price_product' => function ($query) use ($buyerLvl) {
                                        $query->where('agent_lvl_id', $buyerLvl);
                                    }
                                 ])
                            ->with(['get_agent_min_max_birthday_price_product' => function ($query) use ($buyerLvl) {
                                    $query->where('agent_lvl_id', $buyerLvl);
                                }
                             ])
                           ->where('products.status', '1')
                           ->whereNull('mall')
                           ->groupBy('products.id');

        if(Auth::guard('agent')->check() || Auth::guard('admin')->check()){
        $products = $products->where('agent_only', '1');
        }elseif(Auth::guard('web')->check()){
        $products = $products->where('customer_only', '1');
        }else{
        $products = $products->where('customer_only', '1');
        }
        $per_page = 12;
        if(!empty(request('per_page'))){
            $per_page = request('per_page');
        }

        $queries = [];
        $columns = [
           'result', 'brand', 'category', 'subcategory', 'subsubcategory', 'from', 'to', 'per_page'
        ];
        // return htmlspecialchars(request('category'));
        foreach($columns as $column){
            if(request()->has($column) && !empty(request($column))){
                
                if($column == 'category'){
                  $products = $products->where('c.category_name', request($column));
                }elseif($column == 'subcategory'){
                  $products = $products->where('sc.sub_category_name', request($column));
                }elseif($column == 'subsubcategory'){
                  $products = $products->where('ssc.sub_sub_category_name', request($column));
                }elseif($column == 'brand'){
                  $products = $products->where('b.brand_name', request($column));
                }elseif($column == 'from' || $column == 'to'){
                  $from = preg_replace("/[^0-9\.]/", '', request('from'));
                  $to = preg_replace("/[^0-9\.]/", '', request('to'));
                  if(Auth::guard('agent')->check() || Auth::guard('admin')->check()){
                      if(!empty(request('from')) && empty(request('to'))){

                          $products = $products->where(DB::raw('COALESCE(agent_special_price, agent_price)'), '>=', $from);
                      }elseif(empty(request('from')) && !empty(request('to'))){
                          $products = $products->where(DB::raw('COALESCE(agent_special_price, agent_price)'), '<=', $to);
                      }else{
                          $products = $products->where(DB::raw('COALESCE(agent_special_price, agent_price)'), '>=', $from)
                                               ->where(DB::raw('COALESCE(agent_special_price, agent_price)'), '<=', $to);
                      }
                  }else{
                      if(!empty(request('from')) && empty(request('to'))){
                          $products = $products->where(DB::raw('COALESCE(special_price, price)'), '>=', $from);
                      }elseif(empty(request('from')) && !empty(request('to'))){
                          $products = $products->where(DB::raw('COALESCE(special_price, price)'), '<=', $to);
                      }else{
                          $products = $products->where(DB::raw('COALESCE(special_price, price)'), '>=', $from)
                                               ->where(DB::raw('COALESCE(special_price, price)'), '<=', $to);
                      }                    
                  }

                }elseif($column == 'per_page'){
                  $products = $products->paginate($per_page);
                }else{
                  $products = $products->where('products.product_name', 'like', '%'.request($column).'%');
                }
                
                $queries[$column] = request($column);

            }
        }

        if(!empty(request('per_page'))){
            $products = $products->appends($queries);        
        }else{
            $products = $products->paginate($per_page)->appends($queries);
        }
        $p = $products;
        
        $count_p = count($p);

        $categories = Category::select('categories.*', DB::raw('IF(sorting IS NOT NULL, sorting, 1000000) Ordersorting'))
                              ->where('status', '1')
                              ->orderBy('Ordersorting', 'asc')
                              ->get();
        
        $brands = Brand::where('status', '1')->get();

        return view('frontend.mall', ['products'=>$products, 
                                         'categories'=>$categories, 
                                         'brands'=>$brands, 
                                         'count_p'=>$count_p]);
    }

    public static function BalanceQuantity($id)
    {
        $stockBalance = Stock::select(DB::raw('SUM(IF(type = "Increase", quantity, NULL)) AS totalStockIn'),
                                      DB::raw('SUM(IF(type = "Decrease", quantity, NULL)) AS totalStockOut'))
                                ->where('product_id', $id)
                                ->WhereNull('packages_id')
                                ->first();

        $cart = Cart::select(DB::raw('SUM(qty) AS InCart'))
                    ->where('status', '1')
                    ->where('product_id', $id);
                    

        if(Auth::check()){
          $cart = $cart->where('user_id', '<>', Auth::user()->code);
        }

        $cart = $cart->first();

        $transaction = TransactionDetail::select(DB::raw('SUM(quantity) AS TransCart'))
                                        ->join('transactions AS t', 't.id', 'transaction_details.transaction_id')
                                        ->where('t.status', '1')
                                        ->where('product_id', $id)
                                        ->first();

        return $stockBalance->totalStockIn - $stockBalance->totalStockOut - $transaction->TransCart;
    }

    public static function getState($id)
    {
        $state = State::find($id);
        return $state->name;
    }

    public static function GenerateWithdrawalTransactionNo()
    {
      $transaction = WithdrawalTransaction::select(DB::raw('COUNT(id) AS TotalTransaction'))
                                          ->first();
      $TotalTransaction = $transaction->TotalTransaction + 1;

      if(strlen($TotalTransaction) == 1){
          $wtNo = 'W'.strtotime(date('Y-m-d H:i:s'))."0000".$TotalTransaction;
      }elseif(strlen($TotalTransaction) == 2){
          $wtNo = 'W'.strtotime(date('Y-m-d H:i:s'))."000".$TotalTransaction;
      }elseif(strlen($TotalTransaction) == 3){
          $wtNo = 'W'.strtotime(date('Y-m-d H:i:s'))."00".$TotalTransaction;
      }elseif(strlen($TotalTransaction) == 4){
          $wtNo = 'W'.strtotime(date('Y-m-d H:i:s'))."0".$TotalTransaction;
      }else{
          $wtNo = 'W'.strtotime(date('Y-m-d H:i:s')).$TotalTransaction;
      }
      return $wtNo;
    }

    public function countPendingShipping()
    {
        $transactions = Transaction::where('status', '99')->where('recruiting_product', '1')->where('user_id', Auth::user()->code)->get();

      return count($transactions);
    }

    public function countPending()
    {
      $transactions = Transaction::where('status', '99')->where('user_id', Auth::user()->code)->where('viewed', '99')->get();

      return count($transactions);
    }


    public function countToShip()
    {
      $transaction2 = Transaction::where('user_id', Auth::user()->code)
                                  ->where('viewed', '99')
                                  ->where('status', '1')
                                  ->whereNull('to_receive')
                                  ->whereNull('completed')
                                  ->orderBy('created_at', 'desc')
                                  ->get();

      $total = count($transaction2);

      return $total;
    }

    public function countToReceive()
    {
      $transactions = Transaction::select('transactions.*')
                                 ->leftJoin('transaction_trackings as t', 't.transaction_id', 'transactions.id')
                                 ->where('user_id', Auth::user()->code)
                                 ->where('viewed', '99')
                                 ->whereNull('t.ship_status')
                                 ->where('transactions.status', '1')
                                 ->whereNull('completed')
                                 ->where('to_receive', '1')
                                 ->whereNull('on_hold')
                                 ->groupBy('transactions.id')
                                 ->orderBy('created_at', 'desc')
                                 ->get();

      return count($transactions);
    }

    public function countCompleted()
    {
      $transactions = Transaction::select('transactions.*')
                                 ->leftJoin('transaction_trackings as t', 't.transaction_id', 'transactions.id')
                                 ->where('user_id', Auth::user()->code)
                                 ->where('viewed', '99')
                                 ->where('transactions.status', '1')
                                 ->where(function($query){
                                      $query->where('t.ship_status', '2')
                                            ->orWhere('completed', '=', '1');
                                  })
                                 ->groupBy('transactions.id')
                                 ->orderBy('created_at', 'desc')
                                 ->get();

      return count($transactions);
    }

    public function countCancelled()
    {
      $transactions = Transaction::whereIn('status', ['95', '96'])->where('user_id', Auth::user()->code)->where('viewed', '99')->get();

      return count($transactions);
    }

    public function countVerifying()
    {
      $transactions = Transaction::where('status', '98')->whereNotNull('bank_slip')->where('user_id', Auth::user()->code)->where('viewed', '99')->get();

      return count($transactions);
    }

    public function countAwaiting()
    {
      $transactions = Transaction::where('on_hold', '99')->where('user_id', Auth::user()->code)->where('viewed', '99')
                                 ->where('to_receive', '1')
                                 ->where('status', '1')
                                 ->get();

      return count($transactions);
    }

    public function VerifyAccount($user_id)
    {
        $update = User::where(DB::raw('md5(code)'), $user_id)->first();
        if($update->status != 1){
          $update = $update->update(['status' => '1']); 
          return redirect()->route('verify_success');
        }else{
          return redirect()->route('home');
        }

    }


    public function verify_success()
    {
      return view('frontend.verify_success');   
    }

    public function ProductVariationAllBalanceQuantity($id, $type)
    {
        if($type == 1){
            $quantityAmount = ProductVariation::select(DB::raw('SUM(variation_stock) AS totalStock'))
                                              ->where('product_id', $id)
                                              ->first();

        }else{
            $quantityAmount = ProductSecondVariation::select(DB::raw('SUM(variation_stock) AS totalStock'))
                                                    ->where('product_id', $id)
                                                    ->first();
        }


        $cart = Cart::select(DB::raw('SUM(qty) AS InCart'))
                    ->where('status', '1')
                    ->where('product_id', $id)
                    ->first();

        $transaction = TransactionDetail::select(DB::raw('SUM(quantity) AS TransCart'))
                                        ->join('transactions AS t', 't.id', 'transaction_details.transaction_id')
                                        ->where('t.status', '1')
                                        ->where('product_id', $id)
                                        ->first();


        return $quantityAmount->totalStock - $transaction->TransCart;
    }

    public function VariationBalanceQuantity($id)
    {
        $quantityAmount = ProductVariation::find($id);

        $cart = Cart::select(DB::raw('SUM(qty) AS InCart'))
                    ->where('status', '1')
                    ->where('sub_category_id', $id)
                    ->first();

        $transaction = TransactionDetail::select(DB::raw('SUM(quantity) AS TransCart'))
                                        ->join('transactions AS t', 't.id', 'transaction_details.transaction_id')
                                        ->where('t.status', '1')
                                        ->where('variation_id', $id)
                                        ->first();

        return $quantityAmount->variation_stock - $transaction->TransCart;
    }

    public function SecondVariationBalanceQuantity($id)
    {
        $quantityAmount = ProductSecondVariation::find($id);

        $cart = Cart::select(DB::raw('SUM(qty) AS InCart'))
                    ->where('status', '1')
                    ->where('sub_category_id', $id)
                    ->first();

        $transaction = TransactionDetail::select(DB::raw('SUM(quantity) AS TransCart'))
                                        ->join('transactions AS t', 't.id', 'transaction_details.transaction_id')
                                        ->where('t.status', '1')
                                        ->where('second_variation_id', $id)
                                        ->first();

        return $quantityAmount->variation_stock  - $transaction->TransCart;
    }

    public function submit_topup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'topup_amount' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }

        try{
            \DB::beginTransaction();

            $amount = preg_replace("/[^0-9\.]/", '', $request->topup_amount);
            
            if(floatval($amount) <= 0){
                throw new \Exception('Please key in correct amount');
            }

            $input_topup = new TopupTransaction();

            if(empty($request->file('bank_slip'))){
                throw new \Exception('Please upload bank slip to continue');
            }
            $files = $request->file('bank_slip'); 
            $name = $files->getClientOriginalName();
            $exp = explode(".", $name);
            $file_ext = end($exp);
            $name = md5($name.date('Y-m-d H:i:s')).'.'.$file_ext;
            $files->move(GlobalController::get_image_path("uploads/bank_slip/".Auth::user()->code."/"), $name);
          
            $input_topup->topup_payment_method = $request->selected_payment_method;
            $input_topup->topup_no = GlobalController::GenerateTopupNo();
            $input_topup->user_id = Auth::user()->code;
            $input_topup->amount = $amount;
            $input_topup->actual_amount = $amount;
            $input_topup->amount_desc = "RM ".$amount;
            $input_topup->bank_slip = "uploads/bank_slip/".Auth::user()->code."/".$name;
            $input_topup->status = "99";

            $authorise_merchant = !empty($_COOKIE['vmerchant']) ? $_COOKIE['vmerchant'] : '';
            $get_authorise_status = GlobalController::check_autorize_status($authorise_merchant);
            if($get_authorise_status['status'] == 1){
            $input_topup->merchant_id = !empty($get_authorise_status['result']['code']) ? $get_authorise_status['result']['code'] : '';
            }
            $input_topup->save();

            \DB::commit();
        }catch (\Exception $e){
            \DB::rollback();
            Toastr::error($e->getMessage());
            return redirect()->back();
        }catch(\Error $e){
            \DB::rollback();
            Toastr::error($e->getMessage());
            return redirect()->back();
        }

        Toastr::success("Topup submitted! Please wait Admin for Verify");
        return redirect()->route('wallet');
    }

    public function logistic_tracking($transaction_no)
    {
        $transaction = TransactionTracking::where('order_number', $transaction_no)->first();

        if(empty($transaction->id)){
          abort(404);
        }



        $domain = "http://connect.easyparcel.my/?ac=";

        $action = "EPTrackingBulk";
        $postparam = array(
        'api'   => 'EP-QLTip0ZGl',
        'bulk'  => array(
        array(
        'awb_no'    => $transaction->tracking_no,
        ),
        ),
        );

        $url = $domain.$action;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postparam));
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        ob_start(); 
        $return = curl_exec($ch);
        ob_end_clean();
        curl_close($ch);

        $json = json_decode($return, true);
        // echo "<pre>"; print_r($json); echo "</pre>";
        // exit();

        return view('frontend.logistic_tracking_details', ['transaction'=>$transaction, 'results'=>$json]);
    }
    public function add_new_address(Request $request)
    {
        try{
            \DB::beginTransaction();

            if(!empty(Auth::guard('admin')->check())){
                $buyerCode = Auth::guard('admin')->user()->code;
            }elseif(!empty(Auth::guard('merchant')->check())){
                $buyerCode = Auth::guard('merchant')->user()->code;
            }elseif(!empty(Auth::guard('web')->check())){
                $buyerCode = Auth::guard('web')->user()->code;
            }elseif(!empty(Auth::guard('corporate')->check())){
                $buyerCode = Auth::guard('corporate')->user()->code;
            }elseif(!empty(Auth::guard('agent')->check())){
                $buyerCode = Auth::guard('agent')->user()->code;
            }else{
                if(empty($_COOKIE['new_guest'])){
                    $buyerCode = setcookie('new_guest', strtotime(date('Y-m-d H:i:s')).rand(100000, 999999), time() + (86400 * 30), "/");
                }else{
                    $buyerCode = $_COOKIE['new_guest'];
                }
            }

            $validator = Validator::make($request->all(), [
                'f_name' => 'required',
                'email' => 'required',
                'country_code' => 'required',
                'phone' => 'required',
                'address' => 'required',
                'state' => 'required',
                'city' => 'required',
                'postcode' => 'required',
                'country' => 'required',
            ]);

            if ($validator->fails()) {
                return Redirect::back()->withInput($request->all())->withErrors($validator);
            }

            
            $addresses = UserShippingAddress::where('user_id', $buyerCode)->get();
            foreach($addresses as $address){
                $address->default = NULL;
                $address->save();
            }

            // Check phone number
            $phone = $request->phone;
            if(substr($phone, 0, 1) == '0'){
                $phone = substr($phone, 1);
            }

            $insert = new UserShippingAddress();
            $insert->default = '1';
            $insert->user_id = $buyerCode;
            $insert->f_name = $request->f_name;
            $insert->email = $request->email;
            $insert->country_code = $request->country_code;
            $insert->phone = $phone;
            $insert->address = $request->address;
            $insert->state = $request->state;
            $insert->city = $request->city;
            $insert->postcode = $request->postcode;
            $insert->country = $request->country;
            $insert->save();

            \DB::commit();
        }catch (\Exception $e){
            \DB::rollback();
            return Redirect::back()->withInput($request->all())->withErrors($e->getMessage());
        }catch(\Error $e){
            \DB::rollback();
            return Redirect::back()->withInput($request->all())->withErrors($e->getMessage());
        }

        return redirect()->back();
    }

    public function sendEmailNotification($to, $from, $name, $subject, $transaction_no)
    {
      $transaction = Transaction::where('transaction_no', $transaction_no)->first();
      $details = TransactionDetail::where('transaction_id', $transaction->id)->get();

      $headers = "From: $from";
      $headers = "From: " . $from . "\r\n";
      $headers .= "Reply-To: ". $from . "\r\n";
      $headers .= "MIME-Version: 1.0\r\n";
      // $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
      $headers .= "Content-Type: text/html; charset=UTF-8";
      $headers .= '<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">';

      // $subject = "Testing.";


      $link = 'www.zstore.com';

      $body = "<!DOCTYPE html><html lang='en'><head><meta charset='UTF-8'><title>Express Mail</title></head><body>";
      $body .= "<table style='width: 100%;'>";
      $body .= "<thead style='text-align: center;'><tr><td style='border:none;' colspan='2'>";

      $body .= "</td></tr></thead><tbody><tr>";
      $body .= "<td style='border:none;'><strong>Thank you for your order!</strong></td></tr>";
      $body .= "<tr>
                  <td style='border:none;'>
                    <strong>Order Confirmation</strong>
                  </td>
                </tr>";
      $body .= "<tr><td></td></tr>
                <tr><td></td></tr>
                <tr>
                  <td>
                    <td colspan='2'>Product Details</td>
                    <td>Unit Price</td>
                    <td>Quantity</td>
                  </td>
                </tr>";
      foreach($details as $detail){
      $sub_category = (!empty($detail->sub_category)) ? '<br> Variation: '.$detail->sub_category : '';
      $body .= "<tr>
                  <td><img src='".asset($detail->product_image)."'></td>
                  <td>".$detail->product_name.$sub_category."</td>
                  <td>".$detail->unit_price."</td>
                  <td>x".$detail->quantity."</td>
                </tr>";  
      }
      $body .= "<tr><td></td></tr>";
      $body .= "<tr><td></td></tr>";
      $body .= "<tr><td>Regards,</td></tr>";
      $body .= "<tr><td>Kim e-Biz</td></tr>";
      $body .= "<tr><td></td></tr>";
      // $body .= "<tr><td colspan='2' style='border:none;'>{$cmessage}</td></tr>";
      $body .= "</tbody></table>";
      $body .= "</body></html>";

      $send = mail($to, $subject, $body, $headers);
    }

    public function update_address(Request $request)
    {
        try{
            \DB::beginTransaction();

            if(!empty(Auth::guard('admin')->check())){
                $buyerCode = Auth::guard('admin')->user()->code;
            }elseif(!empty(Auth::guard('merchant')->check())){
                $buyerCode = Auth::guard('merchant')->user()->code;
            }elseif(!empty(Auth::guard('web')->check())){
                $buyerCode = Auth::guard('web')->user()->code;
            }elseif(!empty(Auth::guard('corporate')->check())){
                $buyerCode = Auth::guard('corporate')->user()->code;
            }elseif(!empty(Auth::guard('agent')->check())){
                $buyerCode = Auth::guard('agent')->user()->code;
            }else{
                if(empty($_COOKIE['new_guest'])){
                    $buyerCode = setcookie('new_guest', strtotime(date('Y-m-d H:i:s')).rand(100000, 999999), time() + (86400 * 30), "/");
                }else{
                    $buyerCode = $_COOKIE['new_guest'];
                }
            }

            $validator = Validator::make($request->all(), [
                'f_name' => 'required',
                'email' => 'required',
                'country_code' => 'required',
                'phone' => 'required',
                'address' => 'required',
                'state' => 'required',
                'city' => 'required',
                'postcode' => 'required',
                'country' => 'required',
            ]);

            if ($validator->fails()) {
                return Redirect::back()->withInput($request->all())->withErrors($validator);
            }


            $address = UserShippingAddress::find($request->default);
            if(!empty($address->id)){
                $default = UserShippingAddress::where('user_id', $buyerCode)
                                              ->where('default', '1')
                                              ->first();

                if($default->id != $address->id){
                    $updates = UserShippingAddress::where('user_id', $buyerCode)->get();
                    foreach($updates as $update){
                        $update->default = NULL;
                        $update->save();
                    }

                    $address->default = '1';
                    $address->save();
                }
            }
            
            \DB::commit();
        }catch (\Exception $e){
            \DB::rollback();
            return Redirect::back()->withInput($request->all())->withErrors($e->getMessage());
        }catch(\Error $e){
            \DB::rollback();
            return Redirect::back()->withInput($request->all())->withErrors($e->getMessage());
        }

        return redirect()->back();
    }

    public function resetPasswordAction($code, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => ['required', 'min:6', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }

        $merchant = Agent::where(DB::raw('md5(code)'), $code)->first();
        $customer = User::where(DB::raw('md5(code)'), $code)->first();

        if(empty($merchant->id) && empty($customer->id)){
            return redirect()->route('home');
        }

        if(!empty($merchant->id)){
            Agent::where(DB::raw('md5(code)'), $code)->update(['password'=>Hash::make($request->password)]);
        }

        if(!empty($customer->id)){
            User::where(DB::raw('md5(code)'), $code)->update(['password'=>Hash::make($request->password)]);
        }

        Toastr::success('Password reset successfully. You may try login now');
        return redirect()->route('login');
    }

    public function contact_us_send(Request $request)
    {
        $website_setting = WebsiteSetting::find(1);
        $to = $website_setting->contact_email;
        $cfrom = $request->email;
        $name = $request->name;
        $subject = "An Email Enquiry Has Been Received.";
        $cmessage = $request->message;
        $country_code = $request->country_code;
        $phone = $request->phone;

        Mail::to($to)->send(new \App\Mail\WebsiteEnquiry('New Enquiry', $name, $cfrom, $cmessage, $country_code, $phone));

        Toastr::success("Your inquiry has been submitted and will be answered as soon as possible! Thank you for contacting us.");
        return redirect()->back();
    }

    public function customer_invoice($transaction_no)
    {
        $transaction = Transaction::select('transactions.*',
                                           'ca.address as ca_address', 'ca.address_desc as ca_address_desc')
                                  ->leftJoin('cod_addresses AS ca', 'ca.id', 'transactions.cod_address')
                                  ->where('transactions.transaction_no', $transaction_no)
                                  ->first();

        if(empty($transaction->id)){
            abort(404);
        }

        $bank_online = Bank::find($transaction->bank_id);
        $bank_cdm = Bank::where('bank_code', $transaction->cdm_bank_id)->first();

        $details = TransactionDetail::select('transaction_details.*', 'transaction_details.quantity as t_qty', 'u.uom_name', 'u.uom_en', 'p.packages')
                                    ->join('products AS p', 'p.id', 'transaction_details.product_id')
                                    ->leftJoin('setting_uoms AS u', 'u.id', 'p.product_type')
                                    ->where('transaction_id', $transaction->id)
                                    ->get();

        $setting_pickup = SettingPickUpAddress::first();

        $pickup_state = State::find($setting_pickup->state);
        $delivery_state = State::find($transaction->state);

        $delivery_country = TblCountry::where('country_id', $transaction->country)->first();

        $bill_address = TransactionBillingAddress::select('transaction_billing_addresses.*', 's.name as NameOfState', 'tc.country_name')
                                                 ->leftJoin('states as s', 's.id', 'transaction_billing_addresses.state')
                                                 ->leftJoin('tbl_countries as tc', 'tc.country_id', 'transaction_billing_addresses.country')
                                                 ->where('transaction_id', $transaction->id)
                                                 ->first();

        return view('frontend.invoice', ['transaction'=>$transaction, 
                                         'details'=>$details, 
                                         'setting_pickup'=>$setting_pickup, 
                                         'pickup_state'=>$pickup_state, 
                                         'delivery_state'=>$delivery_state,
                                         'delivery_country'=>$delivery_country,
                                         'bill_address'=>$bill_address]);
    }

    public function download_invoice($transaction_no)
    {
        $transaction = Transaction::select('transactions.*',
                                           'ca.address as ca_address', 'ca.address_desc as ca_address_desc')
                                  ->leftJoin('cod_addresses AS ca', 'ca.id', 'transactions.cod_address')
                                  ->where('transactions.transaction_no', $transaction_no)
                                  ->first();

        if(empty($transaction->id)){
            abort(404);
        }

        $bank_online = Bank::find($transaction->bank_id);
        $bank_cdm = Bank::where('bank_code', $transaction->cdm_bank_id)->first();

        $details = TransactionDetail::select('transaction_details.*', 'transaction_details.quantity as t_qty', 'u.uom_name', 'u.uom_en', 'p.packages')
                                    ->join('products AS p', 'p.id', 'transaction_details.product_id')
                                    ->leftJoin('setting_uoms AS u', 'u.id', 'p.product_type')
                                    ->where('transaction_id', $transaction->id)
                                    ->get();

        $admins = Admin::first();

        $cod_address = CodAddress::first();

        $setting_pickup = SettingPickUpAddress::first();

        $pickup_state = State::find($setting_pickup->state);

        $delivery_state = State::find($transaction->state);

        $delivery_country = TblCountry::where('country_id', $transaction->country)->first();

        $bill_address = TransactionBillingAddress::select('transaction_billing_addresses.*', 's.name as NameOfState', 'tc.country_name')
                                                 ->leftJoin('states as s', 's.id', 'transaction_billing_addresses.state')
                                                 ->leftJoin('tbl_countries as tc', 'tc.country_id', 'transaction_billing_addresses.country')
                                                 ->where('transaction_id', $transaction->id)
                                                 ->first();

        $detail = [
            'transaction' => $transaction,
            'details' => $details,
        ];

        $datas['admin'] = $admins;
        $datas['web_setting'] = WebsiteSetting::first();
        $datas['bank_required'] = '0';
        if(Auth::guard('agent')->check()){
            $datas['userGuardRole'] = "agent";
        }elseif(Auth::guard('web')->check()){
            $datas['userGuardRole'] = "web";
        }elseif(Auth::guard('admin')->check()){
            $datas['userGuardRole'] = "admin";
        }else{
            $datas['userGuardRole'] = "";
        }

        $pdf = \PDF::loadView('invoice', ['transaction'=>$transaction, 'details'=>$details, 'cod_address'=>$cod_address, 'delivery_state'=>$delivery_state, 'setting_pickup'=>$setting_pickup, 'pickup_state'=>$pickup_state, 'delivery_country'=>$delivery_country, 'bill_address'=>$bill_address], compact('detail', 'datas'));

        $invoice_name = !empty($datas['web_setting']->invoice_name) ? $datas['web_setting']->invoice_name : $datas['admin']->website_name;
        return $pdf->download($invoice_name." ".$transaction_no.'.pdf');

        // return view('frontend.invoice', ['transaction'=>$transaction, 'details'=>$details]);

        // return view('invoice', ['transaction'=>$transaction, 'details'=>$details, 'cod_address'=>$cod_address, 'delivery_state'=>$delivery_state, 'setting_pickup'=>$setting_pickup, 'pickup_state'=>$pickup_state, 'delivery_country'=>$delivery_country, 'bill_address'=>$bill_address], compact('detail', 'datas'));
    }

    public function sendAccountPassword($to, $from, $subject, $memberCode)
    {
      
      $headers = "From: $from";
      $headers = "From: " . $from . "\r\n";
      $headers .= "Reply-To: ". $from . "\r\n";
      $headers .= "MIME-Version: 1.0\r\n";
      // $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
      $headers .= "Content-Type: text/html; charset=UTF-8";
      $headers .= '<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">';

      // $subject = "Testing."

      $body = "<!DOCTYPE html><html lang='en'><head><meta charset='UTF-8'><title>Express Mail</title></head><body>";
      $body .= "<table style='width: 100%;'>";
      $body .= "<thead style='text-align: center;'><tr><td style='border:none;' colspan='2'>";

      $body .= "</td></tr></thead><tbody><tr>";
      $body .= "<td style='border:none;'><strong>Click on the link below to change your password.</strong></td></tr>";
      $body .= "<tr>
                  <td style='border:none;'>
                    <a href='".route('ForgetPassword', $memberCode)."'>".route('ForgetPassword', $memberCode)."</a>
                  </td>
                </tr>";
      $body .= "<tr><td></td></tr>
                <tr><td></td></tr>
                <tr>
                </tr>";
      $body .= "<tr><td></td></tr>";
      $body .= "<tr><td></td></tr>";
      $body .= "<tr><td>Best Regards,</td></tr>";
      $body .= "<tr><td>Glamnate</td></tr>";
      $body .= "<tr><td></td></tr>";
      // $body .= "<tr><td colspan='2' style='border:none;'>{$cmessage}</td></tr>";
      $body .= "</tbody></table>";
      $body .= "</body></html>";

      $send = mail($to, $subject, $body, $headers);
    }

    public static function getProductPV($user_id, $product_id, $variation_id, $second_variation_id)
    {
        $product = Product::find($product_id);
        $second_variation = ProductSecondVariation::find($second_variation_id);
        $variation = ProductVariation::find($variation_id);

        if(!empty($second_variation->id)){
            return $second_variation->variation_get_pv;

        }elseif(!empty($variation->id)){
            return $variation->variation_get_pv;

        }else{
            return $product->get_pv;
        }
    }

    public static function getProductPVRange($user_id, $product_id)
    {
        $product = Product::find($product_id);

        if($product->second_variation_enable == 1){
            $all_second_variations = ProductSecondVariation::select(DB::raw('min(variation_get_pv) as MinGetPV'),
                                                                    DB::raw('max(variation_get_pv) as MaxGetPV'))
                                                           ->where('product_id', $product->id)
                                                           ->where('status', '1')
                                                           ->first();

            return array($all_second_variations->MinGetPV, $all_second_variations->MaxGetPV);

        }elseif($product->variation_enable == 1){
            $all_variations = ProductVariation::select(DB::raw('min(variation_get_pv) as MinGetPV'),
                                                       DB::raw('max(variation_get_pv) as MaxGetPV'))
                                                           ->where('product_id', $product->id)
                                                           ->where('status', '1')
                                                           ->first();

            return array($all_variations->MinGetPV, $all_variations->MaxGetPV);
        }else{
            return array($product->get_pv);
        }
    }

    public function deal_cart(Request $request)
    {
        if(!empty(Auth::guard('admin')->check())){
            $BuyerCode = Auth::guard('admin')->user()->code;
        }elseif(!empty(Auth::guard('agent')->check())){
            $BuyerCode = Auth::guard('agent')->user()->code;
        }elseif(!empty(Auth::guard('web')->check())){
            $BuyerCode = Auth::guard('web')->user()->code;
        }else{
            if(empty($_COOKIE['new_guest'])){
                $BuyerCode = setcookie('new_guest', strtotime(date('Y-m-d H:i:s')).rand(100000, 999999), time() + (86400 * 30), "/");
            }else{
                $BuyerCode = $_COOKIE['new_guest'];
            }
        }


        DB::beginTransaction();

        try {
            if (!empty($request->product_id)) {
                $cart = new Cart;
                $cart->product_id = $request->product_id;
                $cart->user_id = $BuyerCode;
                $cart->sub_category_id = !empty($request->addon_variation) ? $request->addon_variation : '';
                $cart->second_sub_category_id = !empty($request->addon_second_variation) ? $request->addon_second_variation : '';
                $cart->qty = '1';
                $cart->status = '1';
                $cart->main_add_on = '1';
                $cart->add_on_id = $request->add_on_id;

                $cart->save();
            }

            if(!empty($request->addon)){
                $subItem = AddonDealSubItem::where('id',$request->addon)->first();
                if(!empty($subItem->id)){
                  $carts = new Cart;
                  $carts->user_id = $BuyerCode;
                  $carts->product_id = $subItem->product_id;
                  $carts->sub_category_id = $subItem->variation_id;
                  $carts->second_sub_category_id = $subItem->second_variation_id;
                  $carts->status = '1';
                  $carts->qty = $request->qty;
                  $carts->add_on_id = $request->add_on_id;

                  $carts->save();
                }
            }
            DB::commit();
            Toastr::success('Item has been added to cart');
            return redirect()->route('checkout');
        } catch (\Throwable $th) {
            $message = $th->getMessage();
            DB::rollback();
            Toastr::error($message);
            return redirect()->back();
        }
    }
    public function rev_payment_successfully(Request $request)
    {
        // $json = $request->json()->all();

        // if($json['Response_Code'] == '00'){
        if($_POST['Response_Code'] == '00'){
            // $select = Transaction::where('transaction_no', $json['Reference_Number'])->first();
            $select = Transaction::where('transaction_no', $_POST['Reference_Number'])->first();
            $transaction = Transaction::find($select->id);
            $details = TransactionDetail::where('transaction_id', $select->id)->get();

            $isMember = User::where('code', $select->user_id)->first();
            $isAgent = Agent::where('code', $select->user_id)->first();

            if(!empty($select->register_product)){
                Agent::where('status', '99')->where('code', $select->user_id)->update(['status'=>'1']);

                $get_merchant = Agent::where('code', $select->user_id)->first();

                if($get_merchant->master_id == 'AD000001'){
                      $affiliate = Affiliate::create(['affiliate_id' => $get_merchant->code,
                                                      'user_id' => 'AD000001',
                                                      'sort_level' => '1']);
                }else{
                    //downline
                    $create = Affiliate::create(['affiliate_id'=>$get_merchant->code,
                                                 'user_id'=>$get_merchant->master_id,
                                                 'sort_level' => '1']);

                    $getAff = Affiliate::where('affiliate_id', $get_merchant->master_id)->orderBy('id', 'asc')->get();
                    $affiliate = [];
                    $sort_level = 2;
                    foreach($getAff as $aff){

                        $affiliate[] = [
                                        'affiliate_id' => $get_merchant->code,
                                        'user_id' => $aff->user_id,
                                        'sort_level' => $sort_level++,
                                        'status' => '1',
                                        'created_at' => date('Y-m-d H:i:s'),
                                        'updated_at' => date('Y-m-d H:i:s'),
                                       ];
                    }
                    $insert = Affiliate::insert($affiliate);

                    $Generate_Refferal_Reward = $this->Generate_Refferal_Reward($get_merchant->master_id);
                }
            }

            if(!empty($isMember->id)){
                $upline = Agent::where('code', $isMember->master_id)->where('status', '1')->first();
                if(!empty($upline->id)){
                    $rebate_amount = 0;
                    $product_name = "";
                    foreach($details as $detail){
                        $pv_amount = $detail->unit_price * $detail->quantity;

                        if($detail->get_product_details->packages == 1){
                            $setting_merchant_rebate = SettingPackageRebate::where('point_target', '<=', $detail->unit_price)
                                                                            ->orderBy('point_target', 'desc')
                                                                            ->first();

                            if(!empty($detail->second_variation_id)){
                                $product_name = $detail->product_name.' - '.$detail->sub_category.' - '.$detail->second_sub_category;
                            }elseif(!empty($detail->variation_id)){
                                $product_name = $detail->product_name.' - '.$detail->sub_category;
                            }else{
                                $product_name = $detail->product_name;
                            }
                        }else{
                            $setting_merchant_rebate = SettingMerchantRebate::where('point_target', '<=', $detail->unit_price)
                                                                            ->orderBy('point_target', 'desc')
                                                                            ->first();

                            if(!empty($detail->second_variation_id)){
                                $product_name = $detail->product_name.' - '.$detail->sub_category.' - '.$detail->second_sub_category;
                            }elseif(!empty($detail->variation_id)){
                                $product_name = $detail->product_name.' - '.$detail->sub_category;
                            }else{
                                $product_name = $detail->product_name;
                            }
                        }

                        if(!empty($setting_merchant_rebate->id)){

                            if($setting_merchant_rebate->type == 'Percentage'){
                                $rebate_amount = $pv_amount * $setting_merchant_rebate->amount / 100;
                            }else{
                                $rebate_amount = $setting_merchant_rebate->amount;
                            }

                            if($rebate_amount > 0){
                                $rebate_amount = $rebate_amount * $detail->quantity;
                                $rebate = [];
                                $rebate['type'] = 1;
                                $rebate['user_id'] = $upline->code;
                                $rebate['user_by'] = $isMember->code;
                                $rebate['transaction_no'] = $select->transaction_no;
                                $rebate['product_name'] = $product_name;
                                $rebate['product_image'] = $detail->product_image;
                                $rebate['product_qty'] = $detail->quantity;
                                $rebate['product_amount'] = $detail->unit_price;
                                $rebate['comm_pa_type'] = $setting_merchant_rebate->type;
                                $rebate['comm_pa'] = $setting_merchant_rebate->amount;
                                $rebate['comm_amount'] = $rebate_amount;
                                $rebate['comm_desc'] = "Order Rebate Commission";
                                $rebate['status'] = 1;

                                AffiliateCommission::create($rebate);
                            }
                        }

                        $setting_upline_bonuses = SettingUplineBonus::where('point_target', '<=', $pv_amount)
                                                                    ->orderBy('point_target', 'desc')
                                                                    ->first();

                        if(!empty($setting_upline_bonuses->id)){
                            if($setting_upline_bonuses->type == 'Percentage'){
                                $upline_rebate_amount = $rebate_amount * $setting_upline_bonuses->amount / 100;
                            }else{
                                $upline_rebate_amount = $setting_upline_bonuses->amount;
                            }

                            $rebate_upline = [];
                            $rebate_upline['type'] = 1;
                            $rebate_upline['user_id'] = $upline->master_id;
                            $rebate_upline['user_by'] = $upline->code;
                            $rebate_upline['transaction_no'] = $select->transaction_no;
                            $rebate_upline['product_name'] = $product_name;
                            $rebate_upline['product_qty'] = $detail->quantity;
                            $rebate_upline['product_image'] = $detail->product_image;
                            $rebate_upline['product_amount'] = $rebate_amount;
                            $rebate_upline['comm_pa_type'] = $setting_upline_bonuses->type;
                            $rebate_upline['comm_pa'] = $setting_upline_bonuses->amount;
                            $rebate_upline['comm_amount'] = $upline_rebate_amount;
                            $rebate_upline['comm_desc'] = "Direct Downline Order Rebate Commission";
                            $rebate_upline['status'] = 1;

                            AffiliateCommission::create($rebate_upline);
                        }
                    }
                }
            }

            if(!empty($isAgent->id)){
                $rebate_amount = 0;
                $product_name = "";
                foreach($details as $detail){
                    // $pv_amount = $detail->unit_price * $detail->quantity;
                    // if(!empty($website_setting->rm_to_point)){
                    //     $pv_amount = $pv_amount * $website_setting->rm_to_point;
                    // }
                    $pv_amount = $detail->unit_price * $detail->quantity;

                    if($detail->get_product_details->packages == 1){
                        $setting_merchant_rebate = SettingPackageRebate::where('point_target', '<=', $detail->unit_price)
                                                                        ->orderBy('point_target', 'desc')
                                                                        ->first();

                        if(!empty($detail->second_variation_id)){
                            $product_name = $detail->product_name.' - '.$detail->sub_category.' - '.$detail->second_sub_category;
                        }elseif(!empty($detail->variation_id)){
                            $product_name = $detail->product_name.' - '.$detail->sub_category;
                        }else{
                            $product_name = $detail->product_name;
                        }
                    }else{
                        $setting_merchant_rebate = SettingMerchantRebate::where('point_target', '<=', $detail->unit_price)
                                                                        ->orderBy('point_target', 'desc')
                                                                        ->first();

                        if(!empty($detail->second_variation_id)){
                            $product_name = $detail->product_name.' - '.$detail->sub_category.' - '.$detail->second_sub_category;
                        }elseif(!empty($detail->variation_id)){
                            $product_name = $detail->product_name.' - '.$detail->sub_category;
                        }else{
                            $product_name = $detail->product_name;
                        }
                    }
                    
                    if(!empty($setting_merchant_rebate->id)){

                        if($setting_merchant_rebate->type == 'Percentage'){
                            $rebate_amount = $pv_amount * $setting_merchant_rebate->amount / 100;
                        }else{
                            $rebate_amount = $setting_merchant_rebate->amount;
                        }

                        if($rebate_amount > 0){
                            $rebate_amount = $rebate_amount * $detail->quantity;
                            
                            $rebate = [];
                            $rebate['type'] = 1;
                            $rebate['user_id'] = $isAgent->code;
                            $rebate['transaction_no'] = $select->transaction_no;
                            $rebate['product_name'] = $product_name;
                            $rebate['product_amount'] = $detail->unit_price;
                            $rebate['product_qty'] = $detail->quantity;
                            $rebate['product_image'] = $detail->product_image;
                            $rebate['comm_pa_type'] = $setting_merchant_rebate->type;
                            $rebate['comm_pa'] = $setting_merchant_rebate->amount;
                            $rebate['comm_amount'] = $rebate_amount;
                            $rebate['comm_desc'] = "Order Rebate Commission";
                            $rebate['status'] = 1;

                            AffiliateCommission::create($rebate);
                        }
                    }

                    $setting_upline_bonuses = SettingUplineBonus::where('point_target', '<=', $pv_amount)
                                                                ->orderBy('point_target', 'desc')
                                                                ->first();

                    if(!empty($setting_upline_bonuses->id)){
                        if($setting_upline_bonuses->type == 'Percentage'){
                            $upline_rebate_amount = $rebate_amount * $setting_upline_bonuses->amount / 100;
                        }else{
                            $upline_rebate_amount = $setting_upline_bonuses->amount;
                        }

                        $rebate_upline = [];
                        $rebate_upline['type'] = 1;
                        $rebate_upline['user_id'] = $isAgent->master_id;
                        $rebate_upline['user_by'] = $isAgent->code;
                        $rebate_upline['transaction_no'] = $select->transaction_no;
                        $rebate_upline['product_name'] = $product_name;
                        $rebate_upline['product_amount'] = $rebate_amount;
                        $rebate_upline['product_qty'] = $detail->quantity;
                        $rebate_upline['product_image'] = $detail->product_image;
                        $rebate_upline['comm_pa_type'] = $setting_upline_bonuses->type;
                        $rebate_upline['comm_pa'] = $setting_upline_bonuses->amount;
                        $rebate_upline['comm_amount'] = $upline_rebate_amount;
                        $rebate_upline['comm_desc'] = "Direct Downline Order Rebate Commission";
                        $rebate_upline['status'] = 1;

                        AffiliateCommission::create($rebate_upline);
                    }
                }
            }

            $get_packages = TransactionDetail::with(['get_packages'])->where('transaction_id', $select->id)->get();

            foreach($get_packages as $get_package){
                foreach($get_package->get_packages as $package){
                    if(!empty($package->voucher_id)){
                        for($v=0; $v<$package->quantity; $v++){
                            $input_voucher = [];
                            $input_voucher['promotion_id'] = $package->voucher_id;                              
                            $input_voucher['user_id'] = $select->user_id;
                            $input_voucher['transaction_id'] = $select->transaction_no;
                            $input_voucher['status'] = 99;
                            
                            AppliedPromotion::create($input_voucher);
                        }
                    }
                }
            }

            if($select->store_stock == 1){
                $transaction = $transaction->update(['status'=>'1',
                                                     'to_receive'=>'1',
                                                     'completed'=>'1']);
            }else{
                $transaction = $transaction->update(['status'=>'1']);
            }
        }

        $merchant_id = $_POST['Revpay_Merchant_ID'];
        $payment_id = $_POST['Payment_ID'];
        $transaction_id = $_POST['Transaction_ID'];
        $reference_number = $_POST['Reference_Number'];
        // $bank_reference_number = $_POST['Bank_Reference_Number'];
        $amount = $_POST['Amount'];
        $currency = $_POST['Currency'];
        $transaction_description = $_POST['Transaction_Description'];
        $response_code = $_POST['Response_Code'];
        $key_index = $_POST['Key_Index'];
        $signature = $_POST['Signature'];
        $request_datetime = $_POST['Request_Datetime'];
        $response_datetime = $_POST['Response_Datetime'];
        // Validate the response parameters from revPAY
        if (
        !empty($merchant_id) &&
        !empty($payment_id) &&
        !empty($transaction_id) &&
        !empty($reference_number) &&
        !empty($amount) &&
        !empty($currency) &&
        !empty($transaction_description) &&
        !empty($response_code) &&
        !empty($key_index) &&
        !empty($signature) &&
        !empty($request_datetime) &&
        !empty($response_datetime)) {
        // TO DO <Add your programming code here>
        // Return string OK if no missing parameter value found.
            echo 'OK';
        } else {
        // If found missing parameter
            echo 'Not OK';
        }
    }

    public function Generate_Refferal_Reward($master_id)
    {
        $get_upline = Agent::select(DB::raw('COUNT(id) as totalDownline'))->where('master_id', $master_id)->where('status', '1')->first();

        // if($get_upline->totalDownline == 5){
        // if($get_upline->totalDownline % 5 == 0){
        //     $input_referral = [];
        //     $input_referral['type'] = 55;
        //     $input_referral['user_id'] = $master_id;
        //     $input_referral['product_amount'] = 100;
        //     $input_referral['comm_pa_type'] = "Amount";
        //     $input_referral['comm_pa'] = 100;
        //     $input_referral['comm_amount'] = 100;
        //     $input_referral['comm_desc'] = "Referral New 5 Agent Bonus";

        //     AffiliateCommission::create($input_referral);
        // }  
    
    }

    public function my_stock()
    {   
        $my_stocks = Transaction::select(DB::raw('IF(p.product_name IS NOT NULL, p.product_name, td.product_name) as product_name'), 
                                         DB::raw('IF(v.variation_name IS NOT NULL, v.variation_name, td.sub_category) as sub_category'), 
                                         DB::raw('IF(sv.variation_name IS NOT NULL, sv.variation_name, td.second_sub_category) as second_sub_category'),
                                         'pi.image as product_image',
                                         DB::raw('IF(p.id IS NOT NULL, p.id, td.product_id) as product_id'),
                                         DB::raw('IF(v.id IS NOT NULL, v.id, td.variation_id) as variation_id'),
                                         DB::raw('IF(sv.id IS NOT NULL, sv.id, td.second_variation_id) as second_variation_id'),
                                         DB::raw('IF(p.id IS NOT NULL, p.weight, td.unit_weight) as weight_of_product'),
                                         DB::raw('IF(v.id IS NOT NULL, v.variation_weight, td.unit_weight) as weight_of_variation'),
                                         DB::raw('IF(sv.id IS NOT NULL, sv.variation_weight, td.unit_weight) as weight_of_s_variation'))
                                ->join('transaction_details as td', 'td.transaction_id', 'transactions.id')
                                ->leftJoin('transaction_packages as tp', 'tp.detail_id', 'td.id')
                                ->leftJoin('products as p', 'p.id', 'tp.product_id')
                                ->leftJoin('product_images as pi', 'pi.product_id', 'p.id')
                                ->leftJoin('product_variations as v', 'v.id', 'tp.variation_id')
                                ->leftJoin('product_second_variations as sv', 'sv.id', 'tp.second_variation_id')
                                ->where('transactions.status', '1')
                                ->where('transactions.user_id', Auth::user()->code)
                                ->where('td.store_in_stock', '1')
                                ->groupBy(DB::raw('IF(tp.second_variation_id IS NOT NULL, tp.second_variation_id, td.second_variation_id)'))
                                ->groupBy(DB::raw('IF(tp.variation_id IS NOT NULL, tp.variation_id, td.variation_id)'))
                                ->groupBy(DB::raw('IF(tp.product_id IS NOT NULL, tp.product_id, td.product_id)'))
                                ->get();

        $productStockBalance = [];
        foreach($my_stocks as $stock){
            $productStockBalance[$stock->product_id][$stock->variation_id][$stock->second_variation_id] = GlobalController::get_own_store_stock_balance(Auth::user()->code, $stock->product_id, $stock->variation_id, $stock->second_variation_id);
        }

        // $countries = $address_countries = TblCountry::get();
        $countries = GlobalController::global_countries();

        $states = State::get();

        $cod_addresses = CodAddress::get();

        return view('frontend.my_stock', ['my_stocks'=>$my_stocks,
                                          'countries'=>$countries,
                                          'states'=>$states,
                                          'cod_addresses'=>$cod_addresses],
                                          compact('productStockBalance'));
    }

    public function MyStocksHistory()
    {
        $my_stocks = Transaction::select('d.product_name', 
                                         'd.sub_category', 
                                         'd.second_sub_category',
                                         'd.product_image',
                                         'd.product_id',
                                         'd.variation_id',
                                         'd.second_variation_id',
                                         DB::raw('IF(dp.quantity > 0, (dp.quantity * d.quantity), d.quantity) as quantity'), 
                                         'd.id as d_id',
                                         'transactions.created_at',
                                         'transactions.transaction_no',
                                         'transactions.status')
                               ->join('transaction_details as d', 'd.transaction_id', 'transactions.id')
                               ->leftJoin('transaction_packages as dp', 'dp.detail_id', 'd.id')
                               ->join('products as p', 'p.id', 'd.product_id')
                               ->leftJoin('product_variations as v', 'v.id', 'd.variation_id')
                               ->leftJoin('product_second_variations as sv', 'sv.id', 'd.second_variation_id')
                               ->where('transactions.status', '1')
                               ->where('d.store_in_stock', '1')
                               ->where('transactions.user_id', Auth::user()->code)
                               ->where(DB::raw('IF(dp.product_id > 0, dp.product_id, d.product_id)'), request('pid'));
        if(!empty(request('vid'))){
        $my_stocks = $my_stocks->where(DB::raw('IF(dp.variation_id > 0, dp.variation_id, d.variation_id)'), request('vid'));
        }
        if(!empty(request('svid'))){
        $my_stocks = $my_stocks->where(DB::raw('IF(dp.second_variation_id > 0, dp.second_variation_id, d.second_variation_id)'), request('svid'));
        }
        $my_stocks = $my_stocks->get();

        $deduct_from_customer = Transaction::select('d.product_name', 
                                                    'd.sub_category', 
                                                    'd.second_sub_category',
                                                    'd.product_image',
                                                    'd.product_id',
                                                    'd.variation_id',
                                                    'd.second_variation_id',
                                                    DB::raw('IF(dp.quantity > 0, (dp.quantity * d.quantity), d.quantity) as quantity'), 
                                                    'd.id as customer_purchase_d_id',
                                                    'transactions.created_at',
                                                    'transactions.transaction_no',
                                                    'transactions.status',
                                                    'transactions.id as customer_purchase_id',
                                                    'transactions.user_id as buyer_code')
                                           ->join('transaction_details as d', 'd.transaction_id', 'transactions.id')
                                           ->join('products as p', 'p.id', 'd.product_id')
                                           ->leftJoin('users as u', 'u.code', 'transactions.user_id')
                                           ->leftJoin('agents as m', 'm.code', 'transactions.user_id')
                                           ->leftJoin('product_variations as v', 'v.id', 'd.variation_id')
                                           ->leftJoin('product_second_variations as sv', 'sv.id', 'd.second_variation_id')
                                           ->leftJoin('transaction_packages as dp', 'dp.detail_id', 'd.id')
                                           ->whereIn('transactions.status', ['1', '98'])
                                           ->where('d.deduct_qty', '1')
                                           ->where(DB::raw('COALESCE(m.master_id, u.master_id)'), Auth::user()->code)
                                           ->where(DB::raw('IF(dp.product_id > 0, dp.product_id, d.product_id)'), request('pid'));
        if(!empty(request('vid'))){
        $deduct_from_customer = $deduct_from_customer->where(DB::raw('IF(dp.variation_id > 0, dp.variation_id, d.variation_id)'), request('vid'));
        }
        if(!empty(request('svid'))){
        $deduct_from_customer = $deduct_from_customer->where(DB::raw('IF(dp.second_variation_id > 0, dp.second_variation_id, d.second_variation_id)'), request('svid'));
        }
        $deduct_from_customer = $deduct_from_customer->get();

        $deduct_from_own = Transaction::select('d.product_name', 
                                                    'd.sub_category', 
                                                    'd.second_sub_category',
                                                    'd.product_image',
                                                    'd.product_id',
                                                    'd.variation_id',
                                                    'd.second_variation_id',
                                                    DB::raw('IF(dp.quantity > 0, (dp.quantity * d.quantity), d.quantity) as quantity'), 
                                                    'd.id as customer_purchase_d_id',
                                                    'transactions.created_at',
                                                    'transactions.transaction_no',
                                                    'transactions.status',
                                                    'transactions.id as customer_purchase_id',
                                                    'transactions.user_id as buyer_code')
                                           ->join('transaction_details as d', 'd.transaction_id', 'transactions.id')
                                           ->join('products as p', 'p.id', 'd.product_id')
                                           ->leftJoin('users as u', 'u.code', 'transactions.user_id')
                                           ->leftJoin('agents as m', 'm.code', 'transactions.user_id')
                                           ->leftJoin('product_variations as v', 'v.id', 'd.variation_id')
                                           ->leftJoin('product_second_variations as sv', 'sv.id', 'd.second_variation_id')
                                           ->leftJoin('transaction_packages as dp', 'dp.detail_id', 'd.id')
                                           ->whereIn('transactions.status', ['1', '98'])
                                           ->where('d.deduct_qty', '1')
                                           ->where('transactions.user_id', Auth::user()->code)
                                           ->where(DB::raw('IF(dp.product_id > 0, dp.product_id, d.product_id)'), request('pid'));
        if(!empty(request('vid'))){
        $deduct_from_own = $deduct_from_own->where(DB::raw('IF(dp.variation_id > 0, dp.variation_id, d.variation_id)'), request('vid'));
        }
        if(!empty(request('svid'))){
        $deduct_from_own = $deduct_from_own->where(DB::raw('IF(dp.second_variation_id > 0, dp.second_variation_id, d.second_variation_id)'), request('svid'));
        }
        $deduct_from_own = $deduct_from_own->get();

        $withdrawal_stock = WithdrawalStock::select('withdrawal_stocks.*', 'withdrawal_stocks.id as ws_id', 'sd.quantity')
                                           ->join('withdrawal_stock_details as sd', 'sd.withdrawal_stock_id', 'withdrawal_stocks.id')
                                           ->where('sd.product_id', request('pid'))
                                           ->where('withdrawal_stocks.user_id', Auth::user()->code)
                                           ->whereIn('withdrawal_stocks.status', ['1', '99']);

        if(!empty(request('vid'))){
        $withdrawal_stock = $withdrawal_stock->where('sd.variation_id', request('vid'));
        }
        if(!empty(request('svid'))){
        $withdrawal_stock = $withdrawal_stock->where('sd.second_variation_id', request('svid'));
        }
        $withdrawal_stock = $withdrawal_stock->get();

        $all = $my_stocks->concat($withdrawal_stock);
        $all = $all->concat($deduct_from_customer);
        $all = $all->concat($deduct_from_own);
        $all = array_reverse(Arr::sort($all, function ($value) {
            return $value['created_at'];
        }));

        $product = Product::find(request('pid'));

        $variation="";
        if(!empty(request('vid'))){
        $variation = ProductVariation::find(request('vid'));
        }

        $second_variation="";
        if(!empty(request('svid'))){
        $second_variation = ProductSecondVariation::find(request('svid'));
        }

        $ship_details = [];
        foreach($all as $transaction){
            $domain = "http://connect.easyparcel.my/?ac=";

             $action = "EPParcelStatusBulk";
             $postparam = array(
             'api'   => 'EP-7IsBYfF76',
             'bulk'  => array(
              array(
              'order_no'  => $transaction->order_number,
              ),
              ),
              );

              $url = $domain.$action;
              $ch = curl_init();
              curl_setopt($ch, CURLOPT_URL, $url);
              curl_setopt($ch, CURLOPT_POST, 1);
              curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postparam));
              curl_setopt($ch, CURLOPT_HEADER, 0);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

              ob_start(); 
              $return = curl_exec($ch);
              ob_end_clean();
              curl_close($ch);

              $json = json_decode($return);
              
              foreach($json->result as $value){
                  foreach($value->parcel as $value2){
                      $ship_details[$transaction->transaction_no] = $value2->ship_status;
                  }
              }
        }

        return view('frontend.my_stock_history', ['all'=>$all, 
                                                  'product'=>$product,
                                                  'variation'=>$variation,
                                                  'second_variation'=>$second_variation],
                                                  compact('ship_details'));
    }

    public function QrPayment($code)
    {
        $merchant = Agent::where('code', $code)->first();
        if(empty($merchant->id)){
            return redirect()->route('home');
        }


        $getCurrentLogin = GlobalController::getCurrentLogin();
        $buyerCode = $getCurrentLogin['code'];
        $buyerLvl = $getCurrentLogin['lvl'];

        $getUserDetails = GlobalController::getUserDetails($buyerCode);

        $get_topup_wallet_balance = 0;
        if(!empty($getUserDetails->id)){
            $get_topup_wallet_balance = GlobalController::get_topup_wallet_balance($getUserDetails->code);
        }else{
            return redirect()->route('login');
        }

        return view('frontend.qr_payment', ['merchant'=>$merchant], compact('get_topup_wallet_balance'));
    }

    public function QrPaymentSubmit(Request $request, $code)
    {
        $merchant = Admin::where(DB::raw('md5(code)'), $code)->first();

        if(empty($merchant->id)){
            if(isset($_COOKIE['global_language']) && !empty($_COOKIE['global_language'])){
                    if($_COOKIE['global_language'] == '1'){
                      Toastr::error('商户无效');
                    }else{
                      Toastr::error('Merchant Invalid');
                    }
            }else{
              Toastr::error('Merchant Invalid');
            }
            return redirect()->back();
        }

        $TopupWallet = $this->GetProductWalletBalance($code);
        $amount = preg_replace("/[^0-9\.]/", '', $request->payment_amount);
        
        if(floatval($amount) <= 0){
            if(isset($_COOKIE['global_language']) && !empty($_COOKIE['global_language'])){
                    if($_COOKIE['global_language'] == '1'){
                      Toastr::error('请输入正确的金额');
                    }else{
                      Toastr::error('Please key in correct amount');
                    }
            }else{
              Toastr::error('Please key in correct amount');
            }
            return redirect()->back();
        }

        if(number_format($TopupWallet, 2, "", ".") < number_format($amount, 2, "", ".")){
            if(isset($_COOKIE['global_language']) && !empty($_COOKIE['global_language'])){
                    if($_COOKIE['global_language'] == '1'){
                      Toastr::error('余额不足');
                    }else{
                      Toastr::error('Insufficient Balance');
                    }
            }else{
              Toastr::error('Insufficient Balance');
            }
            return redirect()->back();
        }

        $input = [];
        $input['payment_no'] = $this->PaymentNo();
        $input['merchant_id'] = $merchant->code;
        $input['user_id'] = Auth::user()->code;
        $input['amount'] = $amount;

        TransactionQrPayment::create($input);

        if(isset($_COOKIE['global_language']) && !empty($_COOKIE['global_language'])){
                if($_COOKIE['global_language'] == '1'){
                  Toastr::success('付款成功！');
                }else{
                  Toastr::success('Payment Successfully!');
                }
        }else{
          Toastr::success('Payment Successfully!');
        }
        return redirect()->back();
    }

    public function PaymentNo()
    {
        $TransactionQrPayment = TransactionQrPayment::get();
        $count_payment = count($TransactionQrPayment) + 1;

        if(strlen($count_payment) == 1){
            $return_no = strtotime(date('Y-m-d H:i:s'))."0000".$count_payment;
        }elseif(strlen($count_payment) == 2){
            $return_no = strtotime(date('Y-m-d H:i:s'))."000".$count_payment;
        }elseif(strlen($count_payment) == 3){
            $return_no = strtotime(date('Y-m-d H:i:s'))."00".$count_payment;
        }elseif(strlen($count_payment) == 4){
            $return_no = strtotime(date('Y-m-d H:i:s'))."0".$count_payment;
        }else{
            $return_no = strtotime(date('Y-m-d H:i:s')).$count_payment;
        }

        return $return_no;
    }

    public function blogs()
    {
        $blogs = Blog::where('status', '1')->orderBy('blog_date', 'desc')->get();
       
        $blogs_events = Blog::where('status', '1')->where('type', '1')->get();

        $blogs_news = Blog::where('status', '1')->where('type', '2')->get();

        return view('frontend.blogs', ['blogs'=>$blogs,
                                       'blogs_events'=>$blogs_events,
                                       'blogs_news'=>$blogs_news]);
    }

    public function blog_details($id)
    {
        if(!empty(Auth::guard('admin')->check())){
            $buyerCode = Auth::guard('admin')->user()->code;
        }elseif(!empty(Auth::guard('agent')->check())){
            $buyerCode = Auth::guard('agent')->user()->code;
        }elseif(!empty(Auth::guard('web')->check())){
            $buyerCode = Auth::guard('web')->user()->code;
        }else{
            if(empty($_COOKIE['new_guest'])){
                $buyerCode = setcookie('new_guest', strtotime(date('Y-m-d H:i:s')).rand(100000, 999999), time() + (86400 * 30), "/");
            }else{
                $buyerCode = $_COOKIE['new_guest'];
            }
        }


        $checkViews = BlogView::where('user_id', $buyerCode)
                              ->where(DB::raw('md5(blog_id)'), $id)
                              ->first();

        if(empty($checkViews->id)){
            $input_view = [];
            $input_view['user_id'] = $buyerCode;
            $input_view['blog_id'] = $id;

            BlogView::create($input_view);
        }

        $blog = Blog::where(DB::raw('md5(blogs.id)'), $id)->first();
        
        $comments = BlogComment::select('blog_comments.comment', 'blog_comments.created_at',
                                        DB::raw('CONCAT(u.f_name, " ", u.l_name) AS u_name'),
                                        DB::raw('CONCAT(a.f_name, " ", a.l_name) AS a_name'),
                                        DB::raw('CONCAT(m.f_name, " ", m.l_name) AS m_name'))
                               ->leftJoin('users as u', 'u.code', 'blog_comments.user_id')
                               ->leftJoin('admins as a', 'a.code', 'blog_comments.user_id')
                               ->leftJoin('agents as m', 'm.code', 'blog_comments.user_id')
                               ->where(DB::raw('md5(blog_comments.id)'), $id)
                               ->where('blog_comments.status', '1')
                               ->orderBy('blog_comments.created_at', 'desc')
                               ->get();

        if(empty($blog->id)){
          abort(404);
        }

        if(!empty(Auth::guard('admin')->check())){
            $buyerCode = Auth::guard('admin')->user()->code;
        }elseif(!empty(Auth::guard('agent')->check())){
            $buyerCode = Auth::guard('agent')->user()->code;
        }elseif(!empty(Auth::guard('web')->check())){
            $buyerCode = Auth::guard('web')->user()->code;
        }else{
          if(empty($_COOKIE['new_guest'])){
            $buyerCode = setcookie('new_guest', strtotime(date('Y-m-d H:i:s')).rand(100000, 999999), time() + (86400 * 30), "/");
          }else{
            $buyerCode = $_COOKIE['new_guest'];
          }
        }

        $leftJoin = DB::raw("(SELECT * FROM product_images ORDER BY created_at ASC) AS i");
        $carts = Cart::select('carts.id AS cid', 'p.*', 'i.image', 'carts.qty', DB::raw('COALESCE(special_price, price) AS actual_price'),
                              DB::raw('COALESCE(agent_special_price, agent_price) AS agent_actual_price'),
                              'scl.variation_name', 'scl.variation_price', 'scl.variation_special_price', 'scl.variation_agent_price', 'scl.variation_agent_special_price', 'scl.variation_weight',
                              'p.weight', 'p.special_price', 'p.price', 'p.agent_special_price', 'p.agent_price', 'variation_enable', 'carts.product_id', 'p.product_name', 'carts.second_sub_category_id', 'carts.sub_category_id',
                              'p.second_variation_enable',
                              'sscl.variation_name as second_variation_name',
                              'sscl.variation_price as second_variation_price', 
                              'sscl.variation_special_price as second_variation_special_price', 
                              'sscl.variation_agent_price as second_variation_agent_price', 
                              'sscl.variation_agent_special_price as second_variation_agent_special_price', 
                              'sscl.variation_weight as second_variation_weight')
                     ->join('products AS p', 'p.id', 'carts.product_id')
                     ->leftJoin('product_variations AS scl', 'scl.id', 'carts.sub_category_id')
                     ->leftJoin('product_second_variations AS sscl', 'sscl.id', 'carts.second_sub_category_id')
                     ->leftJoin($leftJoin, function($join) {
                        $join->on('p.id', '=', 'i.product_id');
                     })
                     ->where('carts.status', '1')
                     ->where('carts.user_id', $buyerCode)
                     ->groupBy('carts.id')
                     ->get();

        return view('frontend.blog_details', ['blog'=>$blog, 'comments'=>$comments, 'carts'=>$carts]);
    }

    public function blog_comment(Request $request, $id)
    {
        $blog = Blog::find($id);
        BlogComment::create([
                              'blog_id'=>$blog->id,
                              'user_id'=>Auth::user()->code,
                              'comment'=>$request->comment,
                            ]);

        if(isset($_COOKIE['global_language']) && !empty($_COOKIE['global_language'])){
            if($_COOKIE['global_language'] == '1'){
                Toastr::success("评论成功！");
            }else{
                Toastr::success("Comment Successful!");
            }
        }else{
            Toastr::success("Comment Successful!");
        }
        
        return redirect()->back();
    }

    public function quiz()
    {
        $quizes = Quiz::where('status', '1')->get();

        return view('frontend.quiz', ['quizes'=>$quizes]);
    }
    public function submit_quiz(Request $request)
    {
        $getCurrentLogin = GlobalController::getCurrentLogin();
        $buyerCode = $getCurrentLogin['code'];
        $buyerLvl = $getCurrentLogin['lvl'];

        $getUserDetails = GlobalController::getUserDetails($buyerCode);

        if(empty($getUserDetails)){
            $validator = Validator::make($request->all(), [
                'code' => 'required',
                'f_name' => 'required',
                'phone' => 'required',
            ]);

            if($validator->fails()){
                return Redirect::back()->withInput($request->all())->withErrors($validator);
            }

            $code = $request->code;
            $f_name = $request->f_name ?? NULL;
            $phone = $request->phone;
        }else{
            $code = $getUserDetails['code'];
            $f_name = $getUserDetails['f_name'] ?? NULL;
            $phone = $getUserDetails['phone'];
        }


        try {
            \DB::beginTransaction();

            $quiz_record = new QuizRecord();
            $quiz_record->code = $code;
            $quiz_record->f_name = $f_name;
            $quiz_record->phone = $phone;
            $quiz_record->save();

            foreach($request->answer_amount as $i => $answerId){
                if(!empty($answerId)){
                    $detail = new QuizRecordDetail();
                    $detail->record_id = $quiz_record->id;
                    $detail->quiz_id = $request->tid[$i];
                    $detail->answer_id = $answerId;
                    $detail->save();
                }
            }

            \DB::commit();
        } catch (\Exception $e){
            \DB::rollback();
            return Redirect::back();
        } catch (\Error $e){
            \DB::rollback();
            return Redirect::back();
        }

        return redirect()->route('quiz_result', [$quiz_record->id]);
    }

    public function quiz_result($id)
    {
        $quiz_record = QuizRecord::find($id);

        return view('frontend.quiz_result', ['quiz_record'=>$quiz_record]);
    }

    public function faqs()
    {
        if(!empty(Auth::guard('admin')->check())){
            $buyerCode = Auth::guard('admin')->user()->code;
        }elseif(!empty(Auth::guard('agent')->check())){
            $buyerCode = Auth::guard('agent')->user()->code;
        }elseif(!empty(Auth::guard('web')->check())){
            $buyerCode = Auth::guard('web')->user()->code;
        }else{
            if(empty($_COOKIE['new_guest'])){
                $buyerCode = setcookie('new_guest', strtotime(date('Y-m-d H:i:s')).rand(100000, 999999), time() + (86400 * 30), "/");
            }else{
                $buyerCode = $_COOKIE['new_guest'];
            }
        }

        $type_one = Faq::where('type', '1')->where('status', '1')->get();
        $type_two = Faq::where('type', '2')->where('status', '1')->get();
        $type_three = Faq::where('type', '3')->where('status', '1')->get();

        return view('frontend.faqs', ['type_one'=>$type_one,
                                      'type_two'=>$type_two,
                                      'type_three'=>$type_three]);
    }

    public function SendForgotPasswordLink(Request $request)
    {
        try{

            \DB::beginTransaction();

            $website_setting = WebsiteSetting::find(1);
            $phone = $request->forget_phone[0] === '0' ? substr($request->forget_phone, 1) : $request->forget_phone;
            $account = User::where('phone', $phone)->whereStatus(1)->first() ?? 
                       Agent::where('phone', $phone)->whereStatus(1)->first();

            if(empty($account)){
                Toastr::error($_COOKIE['global_language'] ?? '' == '1' ? '此手机号在我们的系统中不存在' : 'This phone does not exists in our system');
                return redirect()->route('login');
            }

            // send phone
            if($account->phone[0] === '0'){
                $send_phone = $account->country_code.$account->phone;
            }else{
                $send_phone = $account->country_code.'0'.$account->phone;
            }

            // message
            $language = $_COOKIE['global_language'] ?? '';
            $resetLink = route('ForgetPassword', md5($account->code));
            if($language === '1'){
                $message = "来自 " . $website_setting->website_name . "\n"
                           . "密码重置请求。如果您没有请求此操作，可以忽略此消息。\n"
                           . "请点击下面的链接重置您的密码：\n\n"
                           . $resetLink . "\n\n"
                           . "谢谢您.";

            }else{
                $message = "From ".$website_setting->website_name."\n"
                           . "Password Reset Request. If you didn't request this, you can ignore this message.\n"
                           . "Kindly click the link below to reset your password:\n\n"
                           . $resetLink . "\n\n"
                           . "Thank You.";
            }


            $params=array(
                'token' => 'bibn00stpx5dw8h7',
                'to' => $send_phone,
                'body' => $message
            );
            $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => "https://api.ultramsg.com/instance66054/messages/chat",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_SSL_VERIFYHOST => 0,
              CURLOPT_SSL_VERIFYPEER => 0,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "POST",
              CURLOPT_POSTFIELDS => http_build_query($params),
              CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded"
              ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            // forget password log
            $check = ForgetPasswordRecord::where('link', $resetLink)
                                         ->whereNull('link_used')
                                         ->whereStatus(1)
                                         ->first();
            if(empty($check)){
                $insert = new ForgetPasswordRecord();
                $insert->code = $account->code;
                $insert->link = $resetLink;
                $insert->save();
            }

            \DB::commit();

            Toastr::success($_COOKIE['global_language'] ?? '' == '1' ? '请查看您的 WhatsApp 以获取重置密码的请求' : 'Please check your WhatsApp for the password reset request');
            return redirect()->route('login');

        }catch (\Exception $e){
            \DB::rollback();
            return $e->getMessage();
        }catch(\Error $e){
            \DB::rollback();
            return $e->getMessage();
        }
    }

   public function ForgetPassword($code)
    {
        $account = User::where(DB::raw('md5(users.code)'), $code)->first()?? 
                   Agent::where(DB::raw('md5(agents.code)'), $code)->first();

        if(!$account || !$account->id){
            return redirect()->route('home');
        }

        $valid_link = ForgetPasswordRecord::where('code', $account->code)
                                          ->where('status', '1')
                                          ->whereNull('link_used')
                                          ->first();

        if(!$valid_link){
            return redirect()->route('home');
        }

        return view('frontend.forget_password', compact('account'));
    }

    public function resetPassword(Request $request)
    {
        try{

            \DB::beginTransaction();

            if(!$request->new_password){
                Toastr::error($_COOKIE['global_language'] ?? '' == '1' ? '请键入您的新密码' : 'Please key in your new password');
                return redirect()->back();
            }

            if($request->new_password !== $request->confirm_new_password){
                Toastr::error($_COOKIE['global_language'] ?? '' == '1' ? '密码不匹配！' : 'Password Does Not Match!');
                return redirect()->back();
            }

            $account = Agent::where(DB::raw('md5(id)'), $request->aid)->first() ?? 
                       User::where(DB::raw('md5(id)'), $request->aid)->first();

            if(!$account){
                return redirect()->route('home');
            }
                
            $account->password = Hash::make($request->new_password);
            $account->save();

            // forget password log
            $update = ForgetPasswordRecord::where('code', $account->code)
                                          ->whereNull('link_used')
                                          ->whereStatus(1)
                                          ->first();
            $update->link_used = '1';
            $update->save();

            \DB::commit();

            Toastr::success($_COOKIE['global_language'] ?? '' == '1' ? '密码修改成功' : 'Password Changed Successfully');
            return redirect()->route('login');

        }catch (\Exception $e){
            \DB::rollback();
            return $e->getMessage();
        }catch(\Error $e){
            \DB::rollback();
            return $e->getMessage();
        }
    }

    public function transfer_cash_to_topup(Request $request)
    {
        try {
            \DB::beginTransaction();

            $current_agent = Agent::where('code', Auth::user()->code)->first();

            $GetCashWalletBalance = GlobalController::get_cash_wallet_balance($current_agent->code);

            $GetTopupWalletBalance = GlobalController::get_topup_wallet_balance($current_agent->code);

            $validator = Validator::make($request->all(), [
                'adjust_amount' => 'required',
            ]);

            if ($validator->fails()) {
                return Redirect::back()->withInput($request->all())->withErrors($validator);
            }

            if($GetCashWalletBalance < $request->adjust_amount){
                Toastr::error('Amount Exceed! Amount More Than Cash Balance.');
                return Redirect::back()->withInput($request->all())->withErrors('Amount Exceed! Amount More Than Cash Balance.');
            }

            $insert = new AdjustCashToTopup();
            $insert->user_id = $request->user_id;
            $insert->user_by = $current_agent->code;
            $insert->amount = preg_replace("/[^0-9\.]/", '', $request->adjust_amount);
            $insert->remark = $request->remark;
            
            $insert->save();

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            Toastr::error($e->getMessage().' '.$e->getLine());
            return Redirect::back()->withInput($request->all())->withErrors($e->getMessage().' '.$e->getLine());
        } catch (\Error $e) {
            \DB::rollback();
            Toastr::error($e->getMessage().' '.$e->getLine());
            return Redirect::back()->withInput($request->all())->withErrors($e->getMessage().' '.$e->getLine());
        }

        Toastr::success("Transfer Submitted Successfully");
        return redirect()->route('wallet');
    }
}