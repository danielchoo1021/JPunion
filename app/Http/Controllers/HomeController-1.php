<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use App\Bank;
use App\User;
use App\Merchant;
use App\Product;
use App\ProductImage;
use App\State;
use App\Stock;
use App\Cart;
use App\Favourite;
use App\Transaction;
use App\TransactionDetail;
use App\TransactionBillingAddress;
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
use App\AgentPrice;
use App\SettingJoiningFee;
use App\PromoItemTitle;
use App\PromoAgentItem;
use App\PromoAgentItemDetail;
use App\PromoAgentPrice;
use App\TestimonialList;
use App\ProductVideo;
use App\AdjustCashWallet;
use App\SubSubCategory;
use App\AdjustTopupWallet;
use App\SettingTeamDividend;
use App\TransactionPackage;
use App\AdjustIncentiveWallet;
use App\Announcement;

use Twilio\Rest\Client;

use DB, Auth, Validator, Redirect, Toastr, Session, DateTime, Mail;

class HomeController extends Controller
{
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
    public function blogs()
    {
        $blogs = Blog::where('status', '1')->get();
        return view('frontend.blogs', ['blogs'=>$blogs]);
    }

    public function blog_details($id)
    {
        $blog = Blog::find($id);
        $comments = BlogComment::select('blog_comments.comment', 'blog_comments.created_at',
                                        DB::raw('CONCAT(u.f_name, " ", u.l_name) AS u_name'),
                                        DB::raw('CONCAT(a.f_name, " ", a.l_name) AS a_name'),
                                        DB::raw('CONCAT(m.f_name, " ", m.l_name) AS m_name'))
                               ->leftJoin('users as u', 'u.code', 'blog_comments.user_id')
                               ->leftJoin('admins as a', 'a.code', 'blog_comments.user_id')
                               ->leftJoin('merchants as m', 'm.code', 'blog_comments.user_id')
                               ->where('blog_comments.blog_id', $id)
                               ->where('blog_comments.status', '1')
                               ->orderBy('blog_comments.created_at', 'desc')
                               ->get();

        if(empty($blog->id)){
          abort(404);
        }

        return view('frontend.blog_details', ['blog'=>$blog, 'comments'=>$comments]);
    }

    public function blog_comment(Request $request, $id)
    {
        $blog = Blog::find($id);
        BlogComment::create([
                              'blog_id'=>$blog->id,
                              'user_id'=>Auth::user()->code,
                              'comment'=>$request->comment,
                            ]);

        Toastr::success("Comment Successful!");
        return redirect()->back();
    }

    public function about()
    {
        return view('frontend.about');
    }

    public function faqs()
    {
        return view('frontend.faqs');
    }

    public function contact()
    {
        return view('frontend.contact');
    }

    public function merchant_register()
    {
        $merchants = Merchant::where('status', '1')->get();
        // $countries = TblCountry::get();
        $countries = GlobalController::global_countries();

        $upline = Merchant::where('status', '1')->get();
        $uplineAdmin = Admin::get();

        $get_register_products = Product::select('products.*',
                                                 'v.id as v_id',
                                                 'v.variation_name as v_variation_name',
                                                 'sv.id as sv_id',
                                                 'sv.variation_name as sv_variation_name',
                                                 'svv.variation_name as svv_variation_name',
                                                 DB::raw('IF(ap.special_price != 0, ap.special_price, ap.price) as normal_price'),
                                                 DB::raw('IF(apv.special_price != 0, apv.special_price, apv.price) as v_price'),
                                                 DB::raw('IF(apsv.special_price != 0, apsv.special_price, apsv.price) as sv_price'))
                                        ->leftJoin('product_variations as v', 'v.product_id', 'products.id')
                                        ->leftJoin('product_second_variations as sv', 'sv.product_id', 'products.id')
                                        ->leftJoin('product_variations as svv', 'svv.id', 'sv.variation_id')
                                        ->leftjoin('agent_prices as ap', 'ap.product_id', 'products.id')
                                        ->leftjoin('agent_prices as apv', 'apv.variation_id', 'v.id')
                                        ->leftjoin('agent_prices as apsv', 'apsv.second_variation_id', 'sv.id')
                                        ->where('products.status', '1')
                                        ->where(DB::raw('CASE
                                                                WHEN apsv.agent_lvl_id != 0 THEN apsv.agent_lvl_id
                                                                WHEN apv.agent_lvl_id != 0 THEN apv.agent_lvl_id
                                                                ELSE ap.agent_lvl_id
                                                           END'), 1)
                                        ->whereNotNull('upgrade_agent')
                                        ->groupBy(DB::raw('CASE
                                                                WHEN apsv.id != 0 THEN apsv.id
                                                                WHEN apv.id != 0 THEN apv.id
                                                                ELSE ap.id
                                                           END'))
                                        ->get();

        $upline = $upline->concat($uplineAdmin);
        $states = State::get();

        return view('auth.merchant_register', ['merchants'=>$merchants, 'countries'=>$countries, 'upline'=>$upline,
                                               'get_register_products'=>$get_register_products,
                                               'states'=>$states]);
    }

    public function index()
    {
        if(!empty(Auth::guard('admin')->check())){
            $buyerLvl = Auth::guard('admin')->user()->lvl;
        }elseif(!empty(Auth::guard('merchant')->check())){
            $buyerLvl = Auth::guard('merchant')->user()->lvl;
        }elseif(!empty(Auth::guard('web')->check())){
            $buyerLvl = Auth::guard('web')->user()->lvl;
        }else{
            $buyerLvl = "";
        }

        $leftJoin = DB::raw("(SELECT * FROM product_images WHERE image NOT LIKE '%.mp4%' ORDER BY created_at ASC) AS i");
        $leftJoin2 = DB::raw("(SELECT * FROM category_images ORDER BY created_at ASC) AS i");

        $products_top = Product::select('products.*', 'i.image')
                           ->leftJoin($leftJoin, function($join) {
                                $join->on('products.id', '=', 'i.product_id');
                           })
                           ->where('products.status', '1')
                           ->where('products.category_id', '1')
                           ->whereNull('mall')
                           ->groupBy('products.id')
                           ->get();


        $products_featured = Product::select('products.*', 'i.image', 'c.category_name', 'brand_name',
                                             DB::raw('(CASE WHEN agent_special_price != 0  THEN agent_special_price ELSE agent_price END) AS agent_actual_price'),
                                             DB::raw('(CASE WHEN customer_special_price != 0  THEN customer_special_price ELSE customer_price END) AS retail_price'), 
                                             DB::raw('IF(products.sorting > 0, products.sorting, 1000000) Ordersorting'))
                                   ->leftJoin($leftJoin, function($join) {
                                        $join->on('products.id', '=', 'i.product_id');
                                   })
                                   ->join('categories as c', 'c.id', 'products.category_id')
                                   ->leftJoin('brands as b', 'b.id', 'products.brand_id')
                                   ->where('products.status', '1')
                                   ->where('products.featured', '1')
                                   ->whereNull('mall')
                                   ->groupBy('products.id')
                                   ->orderBy('Ordersorting', 'asc')
                                   ->take(12);
        if(Auth::guard('merchant')->check() || Auth::guard('admin')->check()){
        $products_featured = $products_featured->where('agent_only', '1');
        }elseif(Auth::guard('web')->check()){
        $products_featured = $products_featured->where('customer_only', '1');
        }
        $products_featured = $products_featured->get();


        $products_latest = Product::select('products.*', 'i.image', 'c.category_name',
                                             DB::raw('(CASE WHEN agent_special_price != 0  THEN agent_special_price ELSE agent_price END) AS agent_actual_price'),
                                             DB::raw('(CASE WHEN customer_special_price != 0  THEN customer_special_price ELSE customer_price END) AS retail_price'))
                                   ->leftJoin($leftJoin, function($join) {
                                        $join->on('products.id', '=', 'i.product_id');
                                   })
                                   ->join('categories as c', 'c.id', 'products.category_id')
                                   ->where('products.status', '1')
                                   ->whereNull('mall')
                                   ->groupBy('products.id')
                                   ->orderBy('products.created_at', 'desc')
                                   ->take(12);
        if(Auth::guard('merchant')->check() || Auth::guard('admin')->check()){
        $products_latest = $products_latest->where('agent_only', '1');
        }elseif(Auth::guard('web')->check()){
        $products_latest = $products_latest->where('customer_only', '1');
        }
        $products_latest = $products_latest->get();

        $favourite = [];
        $priceV = [];
        $listingImages = [];
        $featured_image = [];
        $pricing = [];

        foreach ($products_featured as $key => $value) {
            $favourite[$value->id] = Favourite::where('product_id', $value->id)->exists();
            
            if(!empty($buyerLvl)){
                $variations = AgentPrice::select(DB::raw('max(price) as MaxVAPrice'),
                                                 DB::raw('min(price) as MinVAPrice'),
                                                 DB::raw('max(special_price) as MaxVASPrice'),
                                                 DB::raw('min(special_price) as MinVASPrice'))
                                                     ->where('product_id', $value->id)
                                                     ->where('agent_lvl_id', $buyerLvl)
                                                     ->first();
                $pricing[$value->id] = [$variations->MaxVAPrice, $variations->MinVAPrice, $variations->MaxVASPrice, $variations->MinVASPrice];
            }

            if($value->second_variation_enable == 1){
              $variations = ProductSecondVariation::select(DB::raw('min(variation_special_price) as MinVSPrice'), 
                                                           DB::raw('min(variation_price) as MinVPrice'),
                                                           DB::raw('max(variation_special_price) as MaxVSPrice'),
                                                           DB::raw('max(variation_price) as MaxVPrice'),

                                                           DB::raw('min(variation_drop_shipper_special_price) as MinDVSPrice'), 
                                                           DB::raw('min(variation_drop_shipper_price) as MinDVPrice'),
                                                           DB::raw('max(variation_drop_shipper_special_price) as MaxDVSPrice'),
                                                           DB::raw('max(variation_drop_shipper_price) as MaxDVPrice'))
                                        ->where('product_id', $value->id)
                                        ->where('variation_name', '!=', '')
                                        ->first();

              $priceV[$value->id] = [$variations->MinVPrice, $variations->MaxVPrice, 
                                       $variations->MinVSPrice, $variations->MaxVSPrice, 
                                       $variations->MinDVPrice, $variations->MaxDVPrice, 
                                       $variations->MinDVSPrice, $variations->MaxDVSPrice];
            }else{
              $variations = ProductVariation::select(DB::raw('min(variation_special_price) as MinVSPrice'), 
                                                     DB::raw('min(variation_price) as MinVPrice'),
                                                     DB::raw('max(variation_special_price) as MaxVSPrice'),
                                                     DB::raw('max(variation_price) as MaxVPrice'),

                                                     DB::raw('min(variation_drop_shipper_special_price) as MinDVSPrice'), 
                                                     DB::raw('min(variation_drop_shipper_price) as MinDVPrice'),
                                                     DB::raw('max(variation_drop_shipper_special_price) as MaxDVSPrice'),
                                                     DB::raw('max(variation_drop_shipper_price) as MaxDVPrice'))
                                        ->where('product_id', $value->id)
                                        ->where('variation_name', '!=', '')
                                        ->first();

              $priceV[$value->id] = [$variations->MinVPrice, $variations->MaxVPrice, 
                                       $variations->MinVSPrice, $variations->MaxVSPrice,
                                       $variations->MinDVPrice, $variations->MaxDVPrice, 
                                       $variations->MinDVSPrice, $variations->MaxDVSPrice];              
            }


            $listingImages[$value->id] = ProductImage::where('product_id', $value->id)->orderBy('sort_level', 'asc')->first();
            if(!empty($listingImages->id)){
              $featured_image[$value->id] = ProductImage::where('image', '!=', $listingImages[$value->id]->image)
                                                        ->where('product_id', $value->id)
                                                        ->first();
              
            }
        }

        $priceV_latest = [];
        $listingImages_latest = [];
        $featured_image_latest = [];
        $pricing_latest = [];
        foreach($products_latest as $latest){
            if(!empty($buyerLvl)){
                $variations = AgentPrice::select(DB::raw('max(price) as MaxVAPrice'),
                                                 DB::raw('min(price) as MinVAPrice'),
                                                 DB::raw('max(special_price) as MaxVASPrice'),
                                                 DB::raw('min(special_price) as MinVASPrice'))
                                                     ->where('product_id', $latest->id)
                                                     ->where('agent_lvl_id', $buyerLvl)
                                                     ->first();
                $pricing[$latest->id] = [$variations->MaxVAPrice, $variations->MinVAPrice, $variations->MaxVASPrice, $variations->MinVASPrice];
            }

            if($latest->second_variation_enable == 1){
              $variations = ProductSecondVariation::select(DB::raw('min(variation_special_price) as MinVSPrice'), 
                                                     DB::raw('min(variation_price) as MinVPrice'),
                                                     DB::raw('max(variation_special_price) as MaxVSPrice'),
                                                     DB::raw('max(variation_price) as MaxVPrice'))
                                        ->where('product_id', $latest->id)
                                        ->where('variation_name', '!=', '')
                                        ->first();

              $priceV_latest[$latest->id] = [$variations->MinVPrice, $variations->MaxVPrice];
            }else{
              $variations = ProductVariation::select(DB::raw('min(variation_special_price) as MinVSPrice'), 
                                                     DB::raw('min(variation_price) as MinVPrice'),
                                                     DB::raw('max(variation_special_price) as MaxVSPrice'),
                                                     DB::raw('max(variation_price) as MaxVPrice'))
                                        ->where('product_id', $latest->id)
                                        ->where('variation_name', '!=', '')
                                        ->first();

              $priceV_latest[$latest->id] = [$variations->MinVPrice, $variations->MaxVPrice];              
            }


            $listingImages_latest[$latest->id] = ProductImage::where('product_id', $latest->id)->orderBy('sort_level', 'asc')->first();
        }

        $featured_categories = Category::select('categories.*')
                              ->join('products as p', 'p.category_id', 'categories.id')
                              ->where('categories.status', '1')
                              ->where('p.featured', '1')
                              ->where('p.status', '1')
                              ->groupBy('categories.id')
                              ->orderBy('categories.created_at', 'desc')
                              ->get();

        $new_categories = Category::select('categories.*')
                              ->join('products as p', 'p.category_id', 'categories.id')
                              ->where('categories.status', '1')
                              ->groupBy('categories.id')
                              ->orderBy('categories.created_at', 'desc')
                              ->get();


        $categories = Category::select('categories.*', 'i.image')
                              ->join('category_images as i', 'categories.id', 'i.category_id')
                              ->where('categories.status', '1')
                              ->groupBy('categories.id')
                              ->orderBy('categories.sorting', 'asc')
                              ->get();

        $promotions = Promotion::where('status', '1')->get();

        // $this->sendMessage('User registration successful!!', '+60174194868');
        // $leftJoin = DB::raw("(SELECT * FROM product_images ORDER BY created_at ASC) AS i");
        $thisweek = Transaction::select('i.image', 'p.*', DB::raw('SUM(d.quantity) AS totalBuy'))
                               ->join('transaction_details AS d', 'd.transaction_id', 'transactions.id')
                               ->join('products AS p', 'p.id', 'd.product_id')
                               ->leftJoin($leftJoin, function($join) {
                                  $join->on('p.id', '=', 'i.product_id');
                               })
                               ->where('transactions.status', '1')
                               ->groupBy('p.id')
                               ->orderBy('transactions.created_at', 'desc')
                               ->take(3)
                               ->get();
        $BalanceQuantity = [];
        foreach($thisweek as $value){
            $BalanceQuantity[$value->id] = $this->BalanceQuantity($value->id);
        }

        $banners = SettingBanner::get();

        $brands = Brand::where('status', '1')->get();

        $signature_dishes = SettingSignatureDish::get();

        $SettingMainPage = SettingMainPage::orderBy('sort_level', 'asc')->get();

        $testimonials = TestimonialList::get();

        $active_announcement = 0;
        $all_announcement = Announcement::where('status', '1')
                                        ->where('announcement_enable', '1')
                                        ->get();

        if(!$all_announcement->isEmpty()){
            $active_announcement = 1;
        }

        return view('frontend.home', ['products_top'=>$products_top, 'products_featured'=>$products_featured, 
                                      'promotions'=>$promotions, 'featured_categories'=>$featured_categories,
                                      'thisweek'=>$thisweek, 'categories'=>$categories, 'products_latest'=>$products_latest, 
                                      'new_categories'=>$new_categories, 'banners'=>$banners, 'brands'=>$brands,
                                      'SettingMainPage'=>$SettingMainPage, 'signature_dishes'=>$signature_dishes,
                                      'testimonials'=>$testimonials, 'active_announcement'=>$active_announcement, 
                                      'all_announcement'=>$all_announcement],
                                     compact('favourite', 'BalanceQuantity', 'priceV', 'featured_image', 'listingImages',
                                             'pricing', 'priceV_latest', 'listingImages_latest', 'pricing_latest'));
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

      $lvl = "";
      $agentLVL = AgentLevel::find(Auth::user()->lvl);
      if(!empty($agentLVL->id)){
        $lvl = $agentLVL->agent_lvl;
      }

      $totalProductBalance = $this->GetProductWalletBalance();
      $totalCashBalance = $this->GetCashWalletBalance();
      $totalEarn = $this->getTotalWallet();
      $PointWallet = $this->GetPointWalletBalance();
      $TotalStock = $this->TotalStock();
      $countPending = $this->countPending();
      $countToShip = $this->countToShip();
      $countToReceive = $this->countToReceive();
      $countCompleted = $this->countCompleted();
      $countCancelled = $this->countCancelled();

      $getMUpline = Merchant::where('code', Auth::user()->master_id)->first();
      $getAUpline = Admin::where('code', Auth::user()->master_id)->first();

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

      
      $thisMonth = date("Y-m", strtotime("previous month"));
      $thisMonth = date("Y-m");
      $get_team_reward = $this->get_team_reward(Auth::user()->code, $thisMonth);
      $get_total_team_reward = $this->get_total_team_reward(Auth::user()->code);

      $get_own_reward = $this->get_own_reward(Auth::user()->code, $thisMonth);
      $get_total_own_reward = $this->get_total_own_reward(Auth::user()->code);

      $SettingTeamDividend = SettingTeamDividend::where('target', '<=', $get_team_reward)
                                                ->orderBy('target', 'desc')
                                                ->first();

        $get_join_month = date('n', strtotime(Auth::user()->created_at));
        $st_quarter = $get_join_month + 3;
        $nd_quarter = $st_quarter + 3;
        $rd_quarter = $nd_quarter + 3;
        $th_quarter = $rd_quarter + 3;

        $st_quarter_two = ($st_quarter > 12) ?  $st_quarter - 12 : $st_quarter;
        $nd_quarter_two = ($nd_quarter > 12) ?  $nd_quarter - 12 : $nd_quarter;
        $rd_quarter_two = ($rd_quarter > 12) ?  $rd_quarter - 12 : $rd_quarter;
        $th_quarter_two = ($th_quarter > 12) ?  $th_quarter - 12 : $th_quarter;

        $current_month = date('n');

        $date_range = "";
        // if($current_month >= $st_quarter && $current_month < $nd_quarter){
        //     if($nd_quarter > 12){
        //         $date_range = date('Y/m', strtotime(date('Y-'.$st_quarter_two))).' - '.date('Y/m', strtotime("+1 year".date('Y-'.$nd_quarter_two)));
        //     }else{
        //         $date_range = date('Y/m', strtotime(date('Y-'.$st_quarter_two))).' - '.date('Y/m', strtotime(date('Y-'.$nd_quarter_two)));
        //     }
        // }elseif($current_month >= $nd_quarter && $current_month < $rd_quarter){
        //     if($rd_quarter > 12){
        //         $date_range = date('Y/m', strtotime(date('Y-'.$nd_quarter_two))).' - '.date('Y/m', strtotime("+1 year".date('Y-'.$rd_quarter_two)));
        //     }else{
        //         $date_range = date('Y/m', strtotime(date('Y-'.$nd_quarter_two))).' - '.date('Y/m', strtotime(date('Y-'.$rd_quarter_two)));
        //     }
        // }elseif($current_month >= $rd_quarter && $current_month < $th_quarter){
        //     if($th_quarter > 12){
        //         $date_range = date('Y/m', strtotime(date('Y-'.$rd_quarter_two))).' - '.date('Y/m', strtotime("+1 year". date('Y-'.$th_quarter_two)));
        //     }else{
        //         $date_range = date('Y/m', strtotime(date('Y-'.$rd_quarter_two))).' - '.date('Y/m', strtotime(date('Y-'.$th_quarter_two)));
        //     }
        // }

        // echo $current_month.' - '.$st_quarter.' - '.$nd_quarter.' - '.$rd_quarter.' - '.$th_quarter;
        // exit();

        return view('frontend.profile', ['lvl'=>$lvl, 
                                       'totalProductBalance'=>$totalProductBalance, 
                                       'totalCashBalance'=>$totalCashBalance, 
                                       'totalEarn'=>$totalEarn,
                                       'countPending'=>$countPending,
                                       'countToShip'=>$countToShip,
                                       'countToReceive'=>$countToReceive,
                                       'countCompleted'=>$countCompleted,
                                       'countCancelled'=>$countCancelled,
                                       'upline_name'=>$upline_name,
                                       'upline_code'=>$upline_code,
                                       'PointWallet'=>$PointWallet,
                                       'get_team_reward'=>$get_team_reward,
                                       'SettingTeamDividend'=>$SettingTeamDividend,
                                       'date_range'=>$date_range,
                                       'get_own_reward'=>$get_own_reward,
                                       'get_total_team_reward'=>$get_total_team_reward,
                                       'get_total_own_reward'=>$get_total_own_reward,
                                       'TotalStock'=>$TotalStock]);
    }

    public function get_team_reward($code, $thisMonth)
    {
        $transaction = Transaction::select(DB::raw('SUM(grand_total - shipping_fee) as ownTotal'))
                                  ->where('status', '1')
                                  ->where('user_id', $code)
                                  ->where('mall', '!=', '1')
                                  ->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m")'), $thisMonth)
                                  ->first();

        $downlines_agent = Merchant::where('status', '1')
                                   ->where('master_id', $code)
                                   ->get();

        $total_downlines_agent_sales = 0;
        foreach($downlines_agent as $downline_agent){
            $get_downline = Transaction::select(DB::raw('SUM(grand_total - shipping_fee) as downlineAgentTotal'))
                                      ->where('status', '1')
                                      ->where('user_id', $downline_agent->code)
                                      ->where('mall', '!=', '1')
                                      ->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m")'), $thisMonth)
                                      ->first();

            $total_downlines_agent_sales += $get_downline->downlineAgentTotal;
        }

        $downlines_member = User::where('status', '1')
                                ->where('master_id', $code)
                                ->get();

        $total_downlines_member_sales = 0;
        foreach($downlines_member as $downline_member){
            $get_downline_m = Transaction::select(DB::raw('SUM(grand_total - shipping_fee) as downlineUserTotal'))
                                      ->where('status', '1')
                                      ->where('user_id', $downline_member->code)
                                      ->where('mall', '!=', '1')
                                      ->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m")'), $thisMonth)
                                      ->first();

            $total_downlines_member_sales += $get_downline_m->downlineUserTotal;
        }

        $totalSales = $total_downlines_agent_sales + $total_downlines_member_sales;

        return $totalSales;
    }

    public function get_total_team_reward($code)
    {
        $transaction = Transaction::select(DB::raw('SUM(grand_total - shipping_fee) as ownTotal'))
                                  ->where('status', '1')
                                  ->where('user_id', $code)
                                  ->where('mall', '!=', '1')
                                  ->first();

        $downlines_agent = Merchant::where('status', '1')
                                   ->where('master_id', $code)
                                   ->get();

        $total_downlines_agent_sales = 0;
        foreach($downlines_agent as $downline_agent){
            $get_downline = Transaction::select(DB::raw('SUM(grand_total - shipping_fee) as downlineAgentTotal'))
                                      ->where('status', '1')
                                      ->where('user_id', $downline_agent->code)
                                      ->where('mall', '!=', '1')
                                      ->first();

            $total_downlines_agent_sales += $get_downline->downlineAgentTotal;
        }

        $downlines_member = User::where('status', '1')
                                ->where('master_id', $code)
                                ->get();

        $total_downlines_member_sales = 0;
        foreach($downlines_member as $downline_member){
            $get_downline_m = Transaction::select(DB::raw('SUM(grand_total - shipping_fee) as downlineUserTotal'))
                                      ->where('status', '1')
                                      ->where('user_id', $downline_member->code)
                                      ->where('mall', '!=', '1')
                                      ->first();

            $total_downlines_member_sales += $get_downline_m->downlineUserTotal;
        }

        $totalSales = $total_downlines_agent_sales + $total_downlines_member_sales;

        return $totalSales;
    }

    public function get_own_reward($code, $thisMonth)
    {
        $transaction = Transaction::select(DB::raw('SUM(sub_total - shipping_fee) as ownTotal'))
                                  ->where('status', '1')
                                  ->where('user_id', $code)
                                  ->where('mall', '!=', '1')
                                  ->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m")'), $thisMonth)
                                  ->first();


        $customers = User::where('master_id', $code)->where('status', '1')->get();

        $customer_total = 0;

        foreach($customers as $customer){
            $ctransaction = Transaction::select(DB::raw('SUM(sub_total - shipping_fee) as ownTotal'))
                                      ->where('status', '1')
                                      ->where('user_id', $customer->code)
                                      ->where('mall', '!=', '1')
                                      ->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m")'), $thisMonth)
                                      ->first();

            $customer_total += $ctransaction->ownTotal;
        }

        $totalSales = $transaction->ownTotal;

        return $totalSales;
    }

    public function get_total_own_reward($code)
    {
        $transaction = Transaction::select(DB::raw('SUM(sub_total - shipping_fee) as ownTotal'))
                                  ->where('status', '1')
                                  ->where('user_id', $code)
                                  ->where('mall', '!=', '1')
                                  ->first();


        $customers = User::where('master_id', $code)->where('status', '1')->get();

        $customer_total = 0;

        foreach($customers as $customer){
            $ctransaction = Transaction::select(DB::raw('SUM(grand_total - shipping_fee) as ownTotal'))
                                      ->where('status', '1')
                                      ->where('user_id', $customer->code)
                                      ->where('mall', '!=', '1')
                                      ->first();

            $customer_total += $ctransaction->ownTotal;
        }

        $totalSales = $transaction->ownTotal;

        return $totalSales;
    }

    public function updateProfile(Request $request)
    {
      // $validator = Validator::make($request->all(), [
      //     'f_name' => 'required',
      // ]);

      // if ($validator->fails()) {
      //     return Redirect::back()->withInput($request->all())->withErrors($validator);
      // }

      $input = $request->all();
      if(Auth::guard('admin')->check()){
        $user = Admin::where('code', Auth::user()->code)->first();
      }elseif(Auth::guard('merchant')->check()){
        $user = Merchant::where('code', Auth::user()->code)->first();
      }else{
        $user = User::where('code', Auth::user()->code)->first();
      }

      $input = Input::except('email');
      if(!empty($request->file('profile_logo'))){
          $files = $request->file('profile_logo'); 
          $name = $files->getClientOriginalName();
          $exp = explode(".", $name);
          $file_ext = end($exp);
          $name = md5($name.date('Y-m-d H:i:s')).'.'.$file_ext;

          $files->move(GlobalController::get_image_path("uploads/profile_logo/"), $name);
          $input['profile_logo'] = "uploads/profile_logo/".$name;
          
      }
      $user = $user->update($input);

      Toastr::success("Account Setting Updated!");
      return redirect()->route('profile');
    }

    public function my_voucher()
    {
        $applied_promotions = Promotion::select('promotions.*', 'ap.*', 'ap.id AS apid')
                                       ->join('applied_promotions as ap', 'ap.promotion_id', 'promotions.id')
                                       ->where('ap.status', '99')
                                       ->where('start_date', '<=', date('Y-m-d H:i:s'))
                                       ->where('end_date', '>=', date('Y-m-d H:i:s'))
                                       ->where('ap.user_id', Auth::user()->code)
                                       ->get();

        return view('frontend.my_voucher', ['applied_promotions'=>$applied_promotions]);
    }

    public function pending_order()
    {
      $transactions = Transaction::where('user_id', Auth::user()->code)->where('status', '99')->orderBy('created_at', 'desc')->get();

      $lvl = "";
      $agentLVL = AgentLevel::find(Auth::user()->lvl);
      if(!empty($agentLVL->id)){
        $lvl = $agentLVL->agent_lvl;
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

      $getMUpline = Merchant::where('code', Auth::user()->master_id)->first();
      $getAUpline = Admin::where('code', Auth::user()->master_id)->first();

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

      return view('frontend.pending_order', ['transactions'=>$transactions, 'lvl'=>$lvl,
                                             'countPending'=>$countPending,
                                             'countToShip'=>$countToShip,
                                             'countToReceive'=>$countToReceive,
                                             'countCompleted'=>$countCompleted,
                                             'countCancelled'=>$countCancelled,
                                             'upline_name'=>$upline_name,
                                             'upline_code'=>$upline_code], compact('details'));
    }

    public function pending_shipping()
    {
        if(!empty(request('oid'))){
          $transaction = Transaction::where('transaction_no', request('oid'))
                                    ->where('user_id', Auth::user()->code)
                                    ->first();

          if(!empty($transaction->id) && $transaction->status == '99'){
              return redirect()->route('pending_order');
          }
      }

      $transactions = Transaction::select('transactions.*', 'm.username as m_username')
                                 ->leftJoin('merchants as m', 'm.code', 'transactions.created_by')
                                 ->where('transactions.user_id', Auth::user()->code)
                                 ->whereIn('transactions.status', ['98', '1'])
                                 ->whereNull('transactions.to_receive')
                                 ->whereNull('transactions.tracking_no')
                                 ->orderBy('transactions.created_at', 'desc')
                                 ->get();


      
      $transactions2 = Transaction::where('user_id', Auth::user()->code)
                                  ->whereIn('status', ['98', '1'])
                                  ->orderBy('created_at', 'desc')
                                  ->get();

      $details = [];
      $ship_details = [];
      $CountTotal=0;

      foreach($transactions as $transaction){
         $details[$transaction->id] = TransactionDetail::where('transaction_id', $transaction->id)->get();

         $domain = "http://connect.easyparcel.my/?ac=";

         $action = "EPParcelStatusBulk";
         $postparam = array(
         'api'   => 'EP-vGlDwpuFK',
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
                  $ship_details[$transaction->id] = $value2->ship_status;
                  if($ship_details[$transaction->id] == 'Schedule In Arrangement' ||
                     $ship_details[$transaction->id] == 'Pending for Drop Off'){
                    $CountTotal++;
                  }
              }
          }
      }

      
      $lvl = "";
      $agentLVL = AgentLevel::find(Auth::user()->lvl);
      if(!empty($agentLVL->id)){
        $lvl = $agentLVL->agent_lvl;
      }

      $totalEarn = $this->getTotalWallet();
      $countPending = $this->countPending();
      $countToShip = $this->countToShip();
      $countToReceive = $this->countToReceive();
      $countCompleted = $this->countCompleted();
      $countCancelled = $this->countCancelled();

      $getMUpline = Merchant::where('code', Auth::user()->master_id)->first();
      $getAUpline = Admin::where('code', Auth::user()->master_id)->first();

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

      return view('frontend.pending_shipping', ['transactions'=>$transactions, 
                                                'lvl'=>$lvl,
                                                'countPending'=>$countPending,
                                                'countToShip'=>$countToShip,
                                                'countToReceive'=>$countToReceive,
                                                'countCompleted'=>$countCompleted,
                                                'countCancelled'=>$countCancelled,
                                                'upline_name'=>$upline_name,
                                                'upline_code'=>$upline_code,
                                                'CountTotal'=>$CountTotal], compact('details', 'ship_details'));
    }

    public function pending_receive()
    {
      $transactions = Transaction::select('transactions.*', 'm.username as m_username')
                                 ->leftJoin('merchants as m', 'm.code', 'transactions.created_by')
                                 ->where('user_id', Auth::user()->code)
                                 ->where('transactions.status', '1')
                                 ->where('to_receive', 1)
                                 ->whereNull('completed')
                                 ->orderBy('transactions.created_at', 'desc')
                                 ->get();

      $transactions2 = Transaction::select('transactions.*', 'm.username as m_username')
                                  ->leftJoin('merchants as m', 'm.code', 'transactions.created_by')
                                  ->where('user_id', Auth::user()->code)
                                  ->where('transactions.status', '1')
                                   ->whereNull('completed')
                                   ->orderBy('transactions.created_at', 'desc')
                                   ->get();
      $lvl = "";
      $agentLVL = AgentLevel::find(Auth::user()->lvl);
      if(!empty($agentLVL->id)){
        $lvl = $agentLVL->agent_lvl;
      }

      $details = [];
      $ship_details = [];
      $CountTotal=0;

      foreach($transactions2 as $transaction){
         $details[$transaction->id] = TransactionDetail::where('transaction_id', $transaction->id)->get();

         $domain = "http://connect.easyparcel.my/?ac=";

         $action = "EPParcelStatusBulk";
         $postparam = array(
          'api'   => 'EP-vGlDwpuFK',
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
          // echo "<pre>"; print_r($json); echo "</pre>";
          
          foreach($json->result as $value){
              foreach($value->parcel as $value2){
                  $ship_details[$transaction->id] = $value2->ship_status;

                  if($ship_details[$transaction->id] == 'Pending For Collection' || $ship_details[$transaction->id] == 'Collected' || 
                     $ship_details[$transaction->id] == 'Delivering(in transit)' || 
                    $ship_details[$transaction->id] == 'Parcel Drop Off at Point'){
                      $CountTotal++;
                  }
              }
          }
      }
      // exit();

      $totalEarn = $this->getTotalWallet();
      $countPending = $this->countPending();
      $countToShip = $this->countToShip();
      $countToReceive = $this->countToReceive();
      $countCompleted = $this->countCompleted();
      $countCancelled = $this->countCancelled();

      $getMUpline = Merchant::where('code', Auth::user()->master_id)->first();
      $getAUpline = Admin::where('code', Auth::user()->master_id)->first();

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

      if($CountTotal > 0){
        $transactions = $transactions2;
      }

      return view('frontend.pending_receive', ['transactions'=>$transactions, 
                                               'lvl'=>$lvl,
                                               'countPending'=>$countPending,
                                               'countToShip'=>$countToShip,
                                               'countToReceive'=>$countToReceive,
                                               'countCompleted'=>$countCompleted,
                                               'countCancelled'=>$countCancelled,
                                               'upline_name'=>$upline_name,
                                               'upline_code'=>$upline_code,
                                               'CountTotal'=>$CountTotal], 
                                               compact('details', 'ship_details'));
    }

    public function completed_order()
    {
      $transactions = Transaction::select('transactions.*', 'm.username as m_username')
                                  ->leftJoin('merchants as m', 'm.code', 'transactions.created_by')
                                  ->where('user_id', Auth::user()->code)
                                 ->where(function($query){
                                    $query->where('transactions.status', '1')
                                          ->orWhere('transactions.completed', '1');
                                })
                                 ->orderBy('transactions.created_at', 'desc')->get();

      $lvl = "";
      $agentLVL = AgentLevel::find(Auth::user()->lvl);
      if(!empty($agentLVL->id)){
        $lvl = $agentLVL->agent_lvl;
      }

      $details = [];
      $ship_details = [];
      $CountTotal = 0;
      foreach($transactions as $transaction){
         $details[$transaction->id] = TransactionDetail::where('transaction_id', $transaction->id)->get();

         $domain = "http://connect.easyparcel.my/?ac=";

         $action = "EPParcelStatusBulk";
         $postparam = array(
         'api'   => 'EP-vGlDwpuFK',
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
                  $ship_details[$transaction->id] = $value2->ship_status;

                  if($ship_details[$transaction->id] == 'Successfully Delivered'){
                    $CountTotal++;

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

      $getMUpline = Merchant::where('code', Auth::user()->master_id)->first();
      $getAUpline = Admin::where('code', Auth::user()->master_id)->first();

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

      return view('frontend.completed_order', ['transactions'=>$transactions, 'lvl'=>$lvl,
                                               'countPending'=>$countPending,
                                               'countToShip'=>$countToShip,
                                               'countToReceive'=>$countToReceive,
                                               'countCompleted'=>$countCompleted,
                                               'countCancelled'=>$countCancelled,
                                               'upline_name'=>$upline_name,
                                               'upline_code'=>$upline_code, 'CountTotal'=>$CountTotal], 
                                               compact('details', 'ship_details')); 
    }


    public function cancelled_order()
    {
      $transactions = Transaction::select('transactions.*', 'm.username as m_username')
                                  ->leftJoin('merchants as m', 'm.code', 'transactions.created_by')
                                  ->where('user_id', Auth::user()->code)
                                  ->whereIn('transactions.status', ['95', '96'])
                                  ->orderBy('transactions.created_at', 'desc')
                                  ->get();

      $lvl = "";
      $agentLVL = AgentLevel::find(Auth::user()->lvl);
      if(!empty($agentLVL->id)){
        $lvl = $agentLVL->agent_lvl;
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

      $getMUpline = Merchant::where('code', Auth::user()->master_id)->first();
      $getAUpline = Admin::where('code', Auth::user()->master_id)->first();

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

      return view('frontend.cancelled_order', ['transactions'=>$transactions, 'lvl'=>$lvl,
                                               'countPending'=>$countPending,
                                               'countToShip'=>$countToShip,
                                               'countToReceive'=>$countToReceive,
                                               'countCompleted'=>$countCompleted,
                                               'countCancelled'=>$countCancelled,
                                               'upline_name'=>$upline_name,
                                               'upline_code'=>$upline_code], compact('details')); 
    }

    public function my_setting()
    {
        $lvl = "";
        $agentLVL = AgentLevel::find(Auth::user()->lvl);
        if(!empty($agentLVL->id)){
          $lvl = $agentLVL->agent_lvl;
        }

        $getMUpline = Merchant::where('code', Auth::user()->master_id)->first();
        $getAUpline = Admin::where('code', Auth::user()->master_id)->first();

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

        // $countries = TblCountry::get();
        $countries = GlobalController::global_countries();

        return view('frontend.my_settings', ['lvl'=>$lvl,
                                              'upline_name'=>$upline_name,
                                              'upline_code'=>$upline_code,
                                              'countries'=>$countries]);
    }

    public function myqrcode(){

        $affiliate_topups = AgentLevel::get();

        return view('frontend.qrcode', ['affiliate_topups'=>$affiliate_topups]);
    }

    public function wallet()
    {
        $ProductWallet = $this->GetProductWalletBalance();
        $CashWallet = $this->GetCashWalletBalance();
        $PointWallet = $this->GetPointWalletBalance();
        
        $commissions = AffiliateCommission::select('affiliate_commissions.*', 't.shipping_fee', 't.processing_fee', 't.discount', 
                                                   't.grand_total AS Gtotal', 't.id as ctid',
                                                   DB::raw('COALESCE(CONCAT(m.f_name, m.l_name), CONCAT(a.f_name, a.l_name)) as username'),
                                                   DB::raw('COALESCE(mr.f_name, ur.f_name) as received_by'),
                                                   'l.agent_lvl as buyer_lvl_name',
                                                   'lr.agent_lvl as refferer_lvl_name')
                                          ->leftJoin('transactions AS t', 't.transaction_no', 'affiliate_commissions.transaction_no')
                                          ->leftJoin('merchants as m', 'm.code', 't.user_id')
                                          ->leftJoin('admins as a', 'a.code', 't.user_id')
                                          ->leftJoin('merchants as mr', 'mr.code', 'affiliate_commissions.user_by')
                                          ->leftJoin('users as ur', 'ur.code', 'affiliate_commissions.user_by')
                                          ->leftJoin('agent_levels as l', 'l.id', 'affiliate_commissions.buyer_lvl')
                                          ->leftJoin('agent_levels as lr', 'lr.id', 'affiliate_commissions.refferer_lvl')
                                          ->where('affiliate_commissions.user_id', Auth::user()->code)
                                          ->where('affiliate_commissions.comm_amount', '>', '0')
                                          ->orderBy('affiliate_commissions.created_at', 'desc')
                                          ->get();

        $withdrawlHistorys = WithdrawalTransaction::where('user_id', Auth::user()->code)
                                               ->orderBy('withdrawal_transactions.created_at', 'desc')
                                               ->get();

        $PVtransactions = Transaction::select('transactions.*', 'transactions.id AS TPVid', DB::raw('SUM((unit_price * quantity) * (IF(rm_to_point > 0, rm_to_point, 1))) as totalTransaction'))
                                   ->join('transaction_details as d', 'd.transaction_id', 'transactions.id')
                                   ->where('transactions.user_id', Auth::user()->code)
                                   ->where('transactions.status', '1')
                                   ->where('transactions.mall', '!=', '1')
                                   ->groupBy('transactions.id')
                                   ->orderBy('transactions.created_at', 'desc')
                                   ->get();

        $transactions = Transaction::select('transactions.*', 'transactions.id AS Tid', 'grand_total as grand_total_mall')
                                   ->where('user_id', Auth::user()->code)
                                   ->where('status', '1')
                                   ->where('mall', '1')
                                   ->whereNull('created_by')
                                   ->orderBy('created_at', 'desc')
                                   ->get();

        $transactions_wallet = Transaction::select('transactions.*', 'transactions.id AS Twid', 'grand_total as grand_total_wallet')
                                   ->where('user_id', Auth::user()->code)
                                   ->where('status', '1')
                                   ->where('mall', '2')
                                   ->orderBy('created_at', 'desc')
                                   ->get();

        $transaction_deduct = Transaction::select('transactions.*', 'transactions.id AS Tdid', 'grand_total as grand_total_deduct')
                                   ->where('created_by', Auth::user()->code)
                                   ->where('status', '1')
                                   ->where('mall', '1')
                                   ->orderBy('created_at', 'desc')
                                   ->get();

        $upline_trans = Transaction::select('transactions.*', 'transactions.id as UPTid', 'grand_total as grand_total_upline')
                                   ->where('user_id', Auth::user()->code)
                                   ->where('status', '1')
                                   ->whereNotNull('created_by')
                                   ->get();

        $topups = TopupTransaction::where('user_id', Auth::user()->code)
                                  ->get();

        $AdjustCashWallet = AdjustCashWallet::select('adjust_cash_wallets.*', 'type as adjust_cash_type', DB::raw('CONCAT(f_name, " ", l_name) as created_by_name'))
                                            ->leftJoin('admins as a', 'a.code', 'adjust_cash_wallets.created_by')
                                            ->where('user_id', Auth::user()->code)
                                            ->get();

        $AdjustTopupWallet = AdjustTopupWallet::select('adjust_topup_wallets.*', 'type as adjust_topup_type', DB::raw('CONCAT(f_name, " ", l_name) as created_by_name'))
                                              ->leftJoin('admins as a', 'a.code', 'adjust_topup_wallets.created_by')
                                              ->where('user_id', Auth::user()->code)
                                              ->get();

        $AdjustIncentiveWallet = AdjustIncentiveWallet::select('adjust_incentive_wallets.*', 'type as adjust_incentive_type', DB::raw('CONCAT(f_name, " ", l_name) as created_by_name'))
                                              ->leftJoin('admins as a', 'a.code', 'adjust_incentive_wallets.created_by')
                                              ->where('user_id', Auth::user()->code)
                                              ->get();

        $purchaseDetail = [];
        foreach($transactions as $transaction){
          $purchaseDetail[$transaction->id] = TransactionDetail::where('transaction_id', $transaction->id)->get();
        }

        $purchaseDetailMall = [];
        foreach($PVtransactions as $PVtransaction){
          $purchaseDetailMall[$PVtransaction->id] = TransactionDetail::where('transaction_id', $PVtransaction->id)->get();
        }

        $purchaseDeductDetail = [];
        foreach($transaction_deduct as $transaction_de){
          $purchaseDeductDetail[$transaction_de->id] = TransactionDetail::where('transaction_id', $transaction_de->id)->get();
        }

        $CommissionPurchaseDeductDetail = [];
        foreach($commissions as $commission){
          $CommissionPurchaseDeductDetail[$commission->ctid] = TransactionDetail::where('transaction_id', $commission->ctid)
                                                                              ->get();
        }

        $WalletPaymentDetails = [];
        foreach($transactions_wallet as $wallet){
          $WalletPaymentDetails[$wallet->Twid] = TransactionDetail::where('transaction_id', $wallet->Twid)->get();
        }

        $uplinePurchaseDetail = [];
        foreach ($upline_trans as $key => $upline_tran) {
          $uplinePurchaseDetail[$upline_tran->UPTid] = TransactionDetail::where('transaction_id', $upline_tran->UPTid)
                                                                        ->get();
        }

        $all = $commissions->concat($withdrawlHistorys);
        $all = $all->concat($topups);
        $all = $all->concat($transactions);
        $all = $all->concat($AdjustCashWallet);
        $all = $all->concat($AdjustTopupWallet);
        $all = $all->concat($transaction_deduct);
        $all = $all->concat($AdjustIncentiveWallet);
        $all = $all->concat($PVtransactions);
        $all = $all->concat($transactions_wallet);
        $all = $all->concat($upline_trans);

        $all = array_reverse(array_sort($all, function ($value) {
            return $value['created_at'];
        }));

        $banksDefault = BankAccount::where('user_id', Auth::user()->code)
                                   ->where('default_banks', '1')
                                   ->first();
                                   
        $banks = BankAccount::where('user_id', Auth::user()->code)
                            ->orderBy('created_at', 'desc')
                            ->get();


        $lvl = "";
        $agentLVL = AgentLevel::find(Auth::user()->lvl);
        if(!empty($agentLVL->id)){
          $lvl = $agentLVL->agent_lvl;
        }

        $tuPackages = SettingTopup::get();

        $getMUpline = Merchant::where('code', Auth::user()->master_id)->first();
        $getAUpline = Admin::where('code', Auth::user()->master_id)->first();

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

        $getTopupAmountLimit = AgentLevel::find(Auth::user()->lvl);

        return view('frontend.wallet', ['all'=>$all, 'ProductWallet'=>$ProductWallet, 'CashWallet'=>$CashWallet, 
                                        'withdrawlHistorys'=>$withdrawlHistorys,
                                        'banks'=>$banks, 'banksDefault'=>$banksDefault, 'lvl'=>$lvl,
                                        'tuPackages'=>$tuPackages, 'upline_name'=>$upline_name, 'upline_code'=>$upline_code,
                                        'PointWallet'=>$PointWallet,
                                        'getTopupAmountLimit'=>$getTopupAmountLimit], 
                                        compact('purchaseDetail', 'purchaseDeductDetail', 'purchaseDetailMall', 'CommissionPurchaseDeductDetail',
                                                'WalletPaymentDetails', 'uplinePurchaseDetail'));
    }


    public function MyAffiliate($code)
    {
        $merchant = Merchant::where('code', $code)->first();
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
        
        $affiliates = Affiliate::select('m.*', 'affiliates.sort_level', 'mu.username as upline_username', 'l.agent_lvl as agent_lvl_display')
                               ->join('merchants as m', 'm.code', 'affiliates.affiliate_id')
                               ->leftJoin('agent_levels as l', 'l.id', 'm.lvl')
                               ->leftJoin('merchants as mu', 'mu.code', 'm.master_id')
                               ->where('affiliates.user_id', $code)
                               ->where('affiliates.sort_level', '<=', '1')
                               ->where('m.status', '!=', '3');

        if(!empty(request('name'))){
            $affiliates = $affiliates->where(DB::raw("CONCAT(f_name, l_name)"), 'like', '%'.request('name').'%');
        }

        if(!empty(request('generation'))){
            $affiliates = $affiliates->where('sort_level', request('generation'));
        }

        $affiliates = $affiliates->get();

        $OwnAffiliate = $this->GetOwnTotalAffiliates($code);
        $OwnTotalAffiliate = $this->GetTotalAffiliates($code);
        $OwnMonthlyTotalAffiliate = $this->GetSelectedUserMonthlyTotalAffiliates($code);
        $GetSelectedUserDailyTotalAffiliates = $this->GetSelectedUserDailyTotalAffiliates($code);
        $TotalAffiliates = [];
        $TodayTotalAffiliates = [];


        
        foreach($affiliates as $affiliate){
            $TotalAffiliates[$affiliate->code] = $this->GetTotalAffiliates($affiliate->code);
            $TodayTotalAffiliates[$affiliate->code] = $this->GetTodayTotalAffiliates($affiliate->code);
        }

        $lvl = "";
        $currentViewed = "";

        $currentMerchant = Merchant::where('code', $code)->first();
        $currentAdmin = Admin::where('code', $code)->first();
        $currentUser = User::where('code', $code)->first();

        if(!empty($currentAdmin->id)){
          $currentViewed = $currentAdmin;
        }elseif(!empty($currentMerchant->id)){
          $currentViewed = $currentMerchant;
        }elseif(!empty($currentUser->id)){
          $currentViewed = $currentUser;
        }

        if(!empty($currentViewed->lvl)){
          $agentLVL = AgentLevel::find($currentViewed->lvl);
          if(!empty($agentLVL->id)){
            $lvl = $agentLVL->agent_lvl;
          }
        }

        $getMUpline = Merchant::where('code', $currentViewed->master_id)->first();
        $getAUpline = Admin::where('code', $currentViewed->master_id)->first();

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

        return view('frontend.my_affiliates', ['affiliates'=>$affiliates, 'OwnTotalAffiliate'=>$OwnTotalAffiliate, 
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
                                                 'upline_code'=>$upline_code],
                                                 compact('TotalAffiliates', 'TodayTotalAffiliates'));
    }

    public function MyCustomer($code)
    {
        $merchant = Merchant::where('code', $code)->first();
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
        
        $affiliates = User::where('master_id', $code)
                          ->where('status', '1');

        $affiliates1 = Affiliate::select('u.*', 'm.f_name as m_name', 'affiliates.sort_level')
                                ->join('merchants as m', 'm.code', 'affiliates.affiliate_id')
                                ->join('users as u', 'u.master_id', 'm.code')
                                ->where('affiliates.user_id', $code)
                                ->where('affiliates.sort_level', '<=', '1')
                                ->where('u.status', '1');

        $upgraded_affiliates = Merchant::where('master_id', $code)
                                       ->where('status', '1');

        if(!empty(request('name'))){
            $affiliate = $affilate->where(DB::raw("CONCAT(f_name, l_name)"), 'like', '%'.request('name').'%');
            $upgraded_affiliates = $upgraded_affiliates->where(DB::raw("CONCAT(f_name, l_name)"), 'like', '%'.request('name').'%');
        }

        $upgraded_affiliates = $upgraded_affiliates->get();
        $affiliates = $affiliates->get();
        $affiliates1 = $affiliates1->get();
        // foreach($affiliates1 as $asd){
        //     echo $asd->f_name.' '.$asd->master_id.' '.$asd->m_name;
        //     echo "<br>";
        // }
        // exit();
        // $affiliates = $affiliate->concat($affiliates1);
        // $affiliates = $affiliate->concat($upgraded_affiliates);

        $totalCustomer = $this->totalCustomer($code);
        $TodayNewCustomer = $this->TodayNewCustomer($code);
        $TotalSales = $this->TotalSales($code);

        $TotalAffiliates = [];
        $TodayTotalAffiliates = [];


        $customerTotalTodaySales = [];
        foreach($affiliates as $affiliate){
            $customerTotalTodaySales[$affiliate->code] = $this->customerTotalTodaySales($affiliate->code);
        }

        $lvl = "";
        $agentLVL = AgentLevel::find(Auth::user()->lvl);
        if(!empty($agentLVL->id)){
          $lvl = $agentLVL->agent_lvl;
        }

        $getMUpline = Merchant::where('code', Auth::user()->master_id)->first();
        $getAUpline = Admin::where('code', Auth::user()->master_id)->first();

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

        return view('frontend.my_customers', ['affiliates'=>$affiliates, 
                                               'totalCustomer'=>$totalCustomer, 
                                               'TodayNewCustomer'=>$TodayNewCustomer, 
                                               'TotalSales'=>$TotalSales,
                                               'name'=>$name,
                                               'code'=>$code,
                                               'lvl'=>$lvl,
                                               'upline'=>$upline,
                                               'phone'=>$phone,
                                               'display_code'=>$display_code,
                                               'profile_logo'=>$profile_logo,
                                               'permission_lvl'=>$permission_lvl,
                                               'upline_name'=>$upline_name,
                                               'upline_code'=>$upline_code],
                                               compact('customerTotalTodaySales'));
    }

    public function MyCustomerTransaction($code)
    {
        $user = User::where('code', $code) //User code is not the same transaction user id
                    ->where('master_id', Auth::user()->code)
                    ->first();

        if(empty($user)){
            $user = Merchant::where('code', $code) //User code is not the same transaction user id
                            ->where('master_id', Auth::user()->code)
                            ->first();
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

        $transactions = Transaction::select('transactions.*', 'm.username as m_username')
                                   ->leftJoin('merchants as m', 'm.code', 'transactions.created_by')
                                   ->where('user_id', $user->code)
                                   ->where('transactions.status', '!=', '55')
                                   ->orderBy('transactions.created_at', 'desc')
                                   ->get();

        $details = [];
        foreach($transactions as $transaction){
            $details[$transaction->id] = TransactionDetail::where('transaction_id', $transaction->id)->get();
        }

        $lvl = "";
        $agentLVL = AgentLevel::find(Auth::user()->lvl);
        if(!empty($agentLVL->id)){
          $lvl = $agentLVL->agent_lvl;
        }

        $getMUpline = Merchant::where('code', Auth::user()->master_id)->first();
        $getAUpline = Admin::where('code', Auth::user()->master_id)->first();

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

        return view('frontend.my_customer_transactions', ['transactions'=>$transactions, 'user'=>$user,
                                                          'endDate'=>$endDate, 'startDate'=>$startDate,
                                                          'lvl'=>$lvl, 'upline_name'=>$upline_name, 
                                                          'upline_code'=>$upline_code],
                                                          compact('details'));
    }

    public function totalCustomer($code)
    {
      $affiliate = User::select(DB::raw('COUNT(id) AS TotalUserCustomer'))
                              ->where('master_id', $code)
                              ->where('status', '1')
                              ->first();

      // $upgraded_affiliate = Merchant::select(DB::raw('COUNT(id) AS TotalAgentCustomer'))
      //                               ->where('master_id', $code)
      //                               ->where('status', '1')
      //                               ->first();

      $TotalCustomer = $affiliate->TotalUserCustomer;

      return  $TotalCustomer;
    }

    public function TodayNewCustomer($code)
    {
      $affiliate = User::select(DB::raw('COUNT(id) AS TotalCustomer'))
                              ->where('master_id', $code)
                              ->where('status', '1')
                              ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), date('Y-m-d'))
                              ->first();

      return  $affiliate->TotalCustomer;
    }

    public function TotalSales($code)
    {
      $transaction = Transaction::select(DB::raw('SUM(grand_total - processing_fee - shipping_fee) as totalPurchase'))
                                ->join('users as u', 'u.code', 'transactions.user_id')
                                ->where('u.master_id', $code)
                                ->first();

      return  !empty($transaction->totalPurchase) ? number_format($transaction->totalPurchase, 2) : '0.00';
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
      $affiliate = Merchant::select(DB::raw('COUNT(id) AS TotalAffiliates'))
                              ->where('master_id', $code)
                              ->where('status', '1')
                              ->first();

      return  $affiliate->TotalAffiliates;
    }

    public function GetTotalAffiliates($code)
    {
        $affiliate = Merchant::select(DB::raw('COUNT(id) AS TotalAffiliates'))
                              ->where('master_id', $code)
                              ->where('merchants.status', '1')
                              ->first();

        $affiliate2 = Merchant::select(DB::raw('COUNT(d.id) AS TotalAffiliates2'))
                              ->join('merchants AS d', 'd.master_id', 'merchants.code')
                              ->where('merchants.master_id', $code)
                              ->where('merchants.status', '1')
                              ->first();

        return  $affiliate->TotalAffiliates + $affiliate2->TotalAffiliates2;
    }

    public function GetTodayTotalAffiliates($code)
    {
        $affiliate = Merchant::select(DB::raw('COUNT(id) AS TotalAffiliates'))
                              ->where('master_id', $code)
                              ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), date('Y-m-d'))
                              ->first();

        $affiliate2 = Merchant::select(DB::raw('COUNT(d.id) AS TotalAffiliates2'))
                              ->join('merchants AS d', 'd.master_id', 'merchants.code')
                              ->where('merchants.master_id', $code)
                              ->where(DB::raw('DATE_FORMAT(merchants.created_at, "%Y-%m-%d")'), date('Y-m-d'))
                              ->first();

        return  $affiliate->TotalAffiliates + $affiliate2->TotalAffiliates2;
    }

    public function GetSelectedUserTotalAffiliates($code)
    {
        $affiliate = User::select(DB::raw('COUNT(id) AS TotalAffiliates'))
                              ->where('master_id', $code)
                              ->first();


        $affiliate2 = User::select(DB::raw('COUNT(d.id) AS TotalAffiliates2'))
                              ->join('users AS d', 'd.master_id', 'users.code')
                              ->where('users.master_id', $code)
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
        $affiliate = Merchant::select(DB::raw('COUNT(id) AS TotalAffiliates'))
                              ->where('master_id', $code)
                              ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), date('Y-m-d'))
                              ->where('merchants.status', '1')
                              ->first();

        $affiliate2 = Merchant::select(DB::raw('COUNT(d.id) AS TotalAffiliates2'))
                              ->join('merchants AS d', 'd.master_id', 'merchants.code')
                              ->where('merchants.master_id', $code)
                              ->where('merchants.status', '1')
                              ->where(DB::raw('DATE_FORMAT(merchants.created_at, "%Y-%m-%d")'), date('Y-m-d'))
                              ->first();

        return  $affiliate->TotalAffiliates + $affiliate2->TotalAffiliates2;
    }

    public function bank_account()
    {
        $lvl = "";
        $agentLVL = AgentLevel::find(Auth::user()->lvl);
        if(!empty($agentLVL->id)){
          $lvl = $agentLVL->agent_lvl;
        }

        $getMUpline = Merchant::where('code', Auth::user()->master_id)->first();
        $getAUpline = Admin::where('code', Auth::user()->master_id)->first();

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

        $banks = PaymentBank::where('status', '1')->get();

        return view('frontend.bank_account', ['lvl'=>$lvl,
                                              'upline_name'=>$upline_name,
                                              'upline_code'=>$upline_code,
                                              'banks'=>$banks]);
    }

    public function bank_account_edit($id)
    {
      $bank = BankAccount::find($id);
      if(empty($bank->id) || $bank->user_id != Auth::user()->code){
        abort(404);
      }

      $agentLVL = AgentLevel::find(Auth::user()->lvl);
      if(!empty($agentLVL->id)){
        $lvl = $agentLVL->agent_lvl;
      }

      $banks = PaymentBank::where('status', '1')->get();

      return view('frontend.bank_account', ['bank'=>$bank, 'lvl'=>$lvl, 'banks'=>$banks]);
    }

    public function bank_account_save(Request $request)
    {
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


        Toastr::success("Bank Account Updated");
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
        
      if(floatval($amount) <= 0){
          return Redirect::back()->withInput($request->all())->withErrors('Please key in correct amount');
      }

      if(floatval($amount) < 50){
          return Redirect::back()->withInput($request->all())->withErrors('Minimum amount RM 50.00.');
      }

      // return (float)$amount.' - '.(float)$request->wallet_balance;
      if(floatval($this->GetCashWalletBalance()) < floatval($amount)){
          // return 123;
          return Redirect::back()->withInput($request->all())->withErrors('Insufficient balance');
      }

      $check = WithdrawalTransaction::where('user_id', Auth::user()->code)
                                      ->where('status', '99')
                                      ->first();
        if(!empty($check->id)){
            return Redirect::back()->withInput($request->all())->withErrors('You have a new withdrawal request that has not been approved / rejected by the film, please wait for the administrator to review');
        }


      $input = $request->all();
      $input['status'] = '99';
      $defaultBank = BankAccount::where('default_banks', '1')
                                  ->where('user_id', Auth::user()->code)
                                  ->first();
      if(!empty($defaultBank->id)){
        $input['bank_name'] = $defaultBank->bank_name;
        $input['bank_holder_name'] = $defaultBank->bank_holder_name;
        $input['bank_account'] = $defaultBank->bank_account;
      }
      $input['user_id'] = Auth::user()->code;
      $input['amount'] = $amount;
      $input['withdrawal_no'] = $this->GenerateWithdrawalTransactionNo();

      $withdrawal = WithdrawalTransaction::create($input);
      
      Toastr::success("Withdrawal Submited, Waiting Admin For Approval");
      return redirect()->route('wallet');
    }

    public function GetProductWalletBalance()
    {
        if(!empty(Auth::guard('admin')->check())){
            $buyerCode = Auth::guard('admin')->user()->code;
        }elseif(!empty(Auth::guard('merchant')->check())){
            $buyerCode = Auth::guard('merchant')->user()->code;
        }elseif(!empty(Auth::guard('web')->check())){
            $buyerCode = Auth::guard('web')->user()->code;
        }else{
          if(empty($_COOKIE['new_guest'])){
            $buyerCode = setcookie('new_guest', strtotime(date('Y-m-d H:i:s')).rand(100000, 999999), time() + (86400 * 30), "/");
          }else{
            $buyerCode = $_COOKIE['new_guest'];
          }
        }
        
        $topup = TopupTransaction::select(DB::raw('SUM(amount) as totalTopup'))
                                  ->where('user_id', $buyerCode)
                                  ->where('status', '1')
                                  ->first();

        $transaction = Transaction::select(DB::raw('SUM(grand_total) as TotalTransaction'))
                                  ->where('user_id', $buyerCode)
                                  ->where('status', '1')
                                  ->where('mall', '1')
                                  ->whereNull('created_by')
                                  ->first();

        $transaction_deduct = Transaction::select(DB::raw('SUM(COALESCE(deduct_amount, grand_total)) as TotalTransaction'))
                                         ->where('created_by', $buyerCode)
                                         ->where('status', '1')
                                         ->where('deduct_wallet', '1')
                                         ->first();

        $adjustIn = AdjustTopupWallet::select(DB::raw('SUM(amount) as totalAdjustIn'))
                                    ->where('user_id', $buyerCode)
                                    ->where('type', '1')
                                    ->first();

        $adjustOut = AdjustTopupWallet::select(DB::raw('SUM(amount) as totalAdjustOut'))
                                    ->where('user_id', $buyerCode)
                                    ->where('type', '2')
                                    ->first();
                                  
        $totalBalance = 0;
        
        $totalBalance = $topup->totalTopup - $transaction->TotalTransaction + $adjustIn->totalAdjustIn - $adjustOut->totalAdjustOut - $transaction_deduct->TotalTransaction;


        

        return $totalBalance;
    }


    public function GetCashWalletBalance()
    {
        if(!empty(Auth::guard('admin')->check())){
            $buyerCode = Auth::guard('admin')->user()->code;
        }elseif(!empty(Auth::guard('merchant')->check())){
            $buyerCode = Auth::guard('merchant')->user()->code;
        }elseif(!empty(Auth::guard('web')->check())){
            $buyerCode = Auth::guard('web')->user()->code;
        }else{
          if(empty($_COOKIE['new_guest'])){
            $buyerCode = setcookie('new_guest', strtotime(date('Y-m-d H:i:s')).rand(100000, 999999), time() + (86400 * 30), "/");
          }else{
            $buyerCode = $_COOKIE['new_guest'];
          }
        }
        
        $balance = AffiliateCommission::select(DB::raw('SUM(comm_amount) as totalBalance'))
                                      ->where('user_id', $buyerCode)
                                      ->where('status', '1')
                                      ->first();

        $withdrawal = WithdrawalTransaction::select(DB::raw('SUM(amount) as totalWithdrawal'))
                                             ->where('user_id', $buyerCode)
                                             ->whereIn('status',['1','99'])
                                             ->first();

        $adjustIn = AdjustCashWallet::select(DB::raw('SUM(amount) as totalAdjustIn'))
                                    ->where('user_id', $buyerCode)
                                    ->where('type', '1')
                                    ->first();

        $adjustOut = AdjustCashWallet::select(DB::raw('SUM(amount) as totalAdjustOut'))
                                    ->where('user_id', $buyerCode)
                                    ->where('type', '2')
                                    ->first();

        $transaction = Transaction::select(DB::raw('SUM(grand_total) as totalTransaction'))
                                  ->where('user_id', $buyerCode)
                                  ->where('status', '1')
                                  ->where('mall', '2')
                                  ->first();
        
        $adjustIncentiveIn = AdjustIncentiveWallet::select(DB::raw('SUM(amount) as totalAdjustIn'))
                                                ->where('user_id', $buyerCode)
                                                ->where('type', '1')
                                                ->first();

        $adjustIncentiveOut = AdjustIncentiveWallet::select(DB::raw('SUM(amount) as totalAdjustOut'))
                                    ->where('user_id', $buyerCode)
                                    ->where('type', '2')
                                    ->first();

        $transaction_deduct = Transaction::select(DB::raw('SUM(grand_total) as totalTransaction'))
                                   ->where('created_by', $buyerCode)
                                   ->where('status', '1')
                                   ->where('mall', '1')
                                   ->first();


        $totalBalance = 0;
        
        $totalBalance = $balance->totalBalance - $withdrawal->totalWithdrawal + $adjustIn->totalAdjustIn - $adjustOut->totalAdjustOut - $transaction->totalTransaction + $adjustIncentiveIn->totalAdjustIn - $adjustIncentiveOut->totalAdjustOut - $transaction_deduct->totalTransaction;
        

        return $totalBalance;
    }

    public function GetPointWalletBalance()
    {
        if(!empty(Auth::guard('admin')->check())){
            $buyerCode = Auth::guard('admin')->user()->code;
        }elseif(!empty(Auth::guard('merchant')->check())){
            $buyerCode = Auth::guard('merchant')->user()->code;
        }elseif(!empty(Auth::guard('web')->check())){
            $buyerCode = Auth::guard('web')->user()->code;
        }else{
          if(empty($_COOKIE['new_guest'])){
            $buyerCode = setcookie('new_guest', strtotime(date('Y-m-d H:i:s')).rand(100000, 999999), time() + (86400 * 30), "/");
          }else{
            $buyerCode = $_COOKIE['new_guest'];
          }
        }

        $transaction = Transaction::select(DB::raw('SUM((unit_price * quantity) * (IF(rm_to_point > 0, rm_to_point, 1))) as totalTransaction'))
                                  ->join('transaction_details as d', 'd.transaction_id', 'transactions.id')
                                  ->where('user_id', $buyerCode)
                                  ->where('transactions.status', '1')
                                  ->where(function($query){
                                      $query->whereNull('mall')
                                            ->orWhere('mall', '0');
                                  })
                                  ->first();

        $upline_trans = Transaction::select(DB::raw('SUM(grand_total) as totalTransaction'))
                                         ->where('user_id', $buyerCode)
                                         ->where('transactions.status', '1')
                                         ->whereNotNull('created_by')
                                         ->first();
        
        $transaction_deduct = Transaction::select(DB::raw('SUM(grand_total) as totalTransaction'))
                                         // ->join('transaction_details as d', 'd.transaction_id', 'transactions.id')
                                         ->where('user_id', $buyerCode)
                                         ->where('transactions.status', '1')
                                         ->where('mall', '1')
                                         ->whereNull('created_by')
                                         ->first();

        $totalBalance = 0;
        
        $totalBalance = $transaction->totalTransaction - $transaction_deduct->totalTransaction + $upline_trans->totalTransaction;
        

        return $totalBalance;
    }

    public function TotalStock()
    {
        if(!empty(Auth::guard('admin')->check())){
            $buyerCode = Auth::guard('admin')->user()->code;
        }elseif(!empty(Auth::guard('merchant')->check())){
            $buyerCode = Auth::guard('merchant')->user()->code;
        }elseif(!empty(Auth::guard('web')->check())){
            $buyerCode = Auth::guard('web')->user()->code;
        }else{
          if(empty($_COOKIE['new_guest'])){
            $buyerCode = setcookie('new_guest', strtotime(date('Y-m-d H:i:s')).rand(100000, 999999), time() + (86400 * 30), "/");
          }else{
            $buyerCode = $_COOKIE['new_guest'];
          }
        }

        $owntransaction = Transaction::select(DB::raw('SUM(IF(p.packages = 1, (tp.quantity * d.quantity), d.quantity) ) as totalQuantity'))
                                     ->join('transaction_details as d', 'd.transaction_id', 'transactions.id')
                                     ->join('products as p', 'p.id', 'd.product_id')
                                     ->leftJoin('transaction_packages as tp', 'tp.detail_id', 'd.id')
                                     ->where('transactions.status', '1')
                                     ->where('transactions.user_id', $buyerCode)
                                     ->where('transactions.mall', '!=', '1')
                                     ->first();

        $own_sales = $owntransaction->totalQuantity;

        $downlines = Merchant::where('master_id', $buyerCode)
                             ->where('status', '1')
                             ->get();

        $downlines_sales = 0;
        foreach($downlines as $downline){

            $downtransaction = Transaction::select(DB::raw('SUM(IF(p.packages = 1, (tp.quantity * d.quantity), d.quantity) ) as totalQuantity'))
                                     ->join('transaction_details as d', 'd.transaction_id', 'transactions.id')
                                     ->join('products as p', 'p.id', 'd.product_id')
                                     ->leftJoin('transaction_packages as tp', 'tp.detail_id', 'd.id')
                                     ->where('transactions.status', '1')
                                     ->where('transactions.user_id', $downline->code)
                                     ->where('transactions.mall', '!=', '1')
                                     ->first();

            $downlines_sales += $downtransaction->totalQuantity;
        }

        $users = User::where('master_id', $buyerCode)
                         ->where('status', '1')
                         ->get();

        $customer_sales = 0;
        foreach($users as $user){

            $custransaction = Transaction::select(DB::raw('SUM(IF(p.packages = 1, (tp.quantity * d.quantity), d.quantity) ) as totalQuantity'))
                                     ->join('transaction_details as d', 'd.transaction_id', 'transactions.id')
                                     ->join('products as p', 'p.id', 'd.product_id')
                                     ->leftJoin('transaction_packages as tp', 'tp.detail_id', 'd.id')
                                     ->where('transactions.status', '1')
                                     ->where('transactions.user_id', $user->code)
                                     ->first();

            $customer_sales += $custransaction->totalQuantity;
        }

        $total_sales = $own_sales + $downlines_sales + $customer_sales;

        return $total_sales;

    }

    public function getTotalWallet()
    {
        $total = AffiliateCommission::select(DB::raw('SUM(comm_amount) as totalBalance'))
                                      ->where('user_id', Auth::user()->code)
                                      ->where('status', '1')
                                      ->first();
                                      
        $adjustIn = AdjustCashWallet::select(DB::raw('SUM(amount) as totalAdjustIn'))
                                    ->where('user_id', Auth::user()->code)
                                    ->where('type', '1')
                                    ->first();

        $adjustIncentiveIn = AdjustIncentiveWallet::select(DB::raw('SUM(amount) as totalAdjustIn'))
                                                ->where('user_id', Auth::user()->code)
                                                ->where('type', '1')
                                                ->first();

        return  $total->totalBalance + $adjustIn->totalAdjustIn + $adjustIncentiveIn->totalAdjustIn;
    }

    public function order_list()
    {
        $lvl = "";
        $agentLVL = AgentLevel::find(Auth::user()->lvl);
        if(!empty($agentLVL->id)){
          $lvl = $agentLVL->agent_lvl;
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

        $lvl = "";
        $agentLVL = AgentLevel::find(Auth::user()->lvl);
        if(!empty($agentLVL->id)){
          $lvl = $agentLVL->agent_lvl;
        }
        
        $transaction = Transaction::select('transactions.*', 'p.amount_type', 'p.amount AS discount_amount', 's.name as state_name')
                                  ->leftJoin('promotions AS p', 'p.id', 'transactions.discount_code')
                                  ->leftJoin('states as s', 's.id', 'transactions.state')
                                  ->where('transaction_no', $no)
                                  ->first();
        if(empty($transaction->id)){
          abort(404);
        }

        $details = TransactionDetail::where('transaction_id', $transaction->id)->get();

        return view('frontend.order_detail', ['transaction'=>$transaction, 'details'=>$details, 'lvl'=>$lvl]);
    }

    public function wish_list()
    {
        $leftJoin = DB::raw("(SELECT * FROM product_images ORDER BY created_at ASC) AS i");
  
        $favourites = Favourite::select('p.*', 'i.image')
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
        $agentLVL = AgentLevel::find(Auth::user()->lvl);
        if(!empty($agentLVL->id)){
          $lvl = $agentLVL->agent_lvl;
        }

        $getMUpline = Merchant::where('code', Auth::user()->master_id)->first();
        $getAUpline = Admin::where('code', Auth::user()->master_id)->first();

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

        return view('frontend.wish_list', ['favourites'=>$favourites, 'lvl'=>$lvl,
                                           'upline_name'=>$upline_name,
                                           'upline_code'=>$upline_code], compact('stockBalance'));
    }

    public function changePassword()
    {
        $getMUpline = Merchant::where('code', Auth::user()->master_id)->first();
        $getAUpline = Admin::where('code', Auth::user()->master_id)->first();

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

        $lvl = "";
        $agentLVL = AgentLevel::find(Auth::user()->lvl);
        if(!empty($agentLVL->id)){
          $lvl = $agentLVL->agent_lvl;
        }
        return view('frontend.change_password', ['upline_code'=>$upline_code,
                                                 'upline_name'=>$upline_name,
                                                 'lvl'=>$lvl]);
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
            return Redirect::back()->withInput($request->all())->withErrors(['Current Password Not Match']);
        }

        if($request->new_password != $request->password_confirmation){
            return Redirect::back()->withInput($request->all())->withErrors(['The new password confirmation does not match.']);
        }
        
        if(Auth::guard('admin')->check()){
          $update = Admin::where('code', Auth::user()->code)->first();
        }elseif(Auth::guard('merchant')->check()){
          $update = Merchant::where('code', Auth::user()->code)->first();
        }else{
          $update = User::where('code', Auth::user()->code)->first();
        }
        $update = $update->update(['password'=>Hash::make($request->new_password)]);

        Toastr::success("Password Changed Successfully!");
        return redirect()->route('changePassword');
    }

    public function listing()
    {

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
        }elseif (Auth::guard('dropshipper')->check()) {
          $user = Auth::guard('dropshipper')->check();
          $userCode = Auth::guard('dropshipper')->user()->code;
          $buyerLvl = Auth::guard('dropshipper')->user()->lvl;
        }else{
          $user = "";
          $userCode = "";
          $buyerLvl = "";
        }

        $leftJoin = DB::raw("(SELECT * FROM product_images ORDER BY sort_level ASC) AS i");
        $products = Product::select('products.*', 'c.category_name', 'b.brand_name',
                                    DB::raw('(CASE WHEN agent_special_price != 0  THEN agent_special_price ELSE agent_price END) AS agent_actual_price'),
                                    DB::raw('(CASE WHEN customer_special_price != 0  THEN customer_special_price ELSE customer_price END) AS retail_price'), DB::raw('IF(products.sorting > 0, products.sorting, 1000000) Ordersorting'))
                           ->leftJoin('categories AS c', 'c.id', 'products.category_id')
                           ->leftJoin('sub_categories AS sc', 'sc.id', 'products.sub_category_id')
                           ->leftJoin('sub_sub_categories as ssc', 'ssc.id', 'products.sub_sub_category_id')
                           ->leftJoin('brands AS b', 'b.id', 'products.brand_id')
                           ->where('products.status', '1')
                           ->whereNull('mall')
                           ->groupBy('products.id')
                           ->orderBy('Ordersorting', 'asc');

        if(Auth::guard('merchant')->check() || Auth::guard('admin')->check()){
        $products = $products->where('agent_only', '1');
        if(Auth::guard('merchant')->check() && !empty(Auth::guard('merchant')->user()->lvl)){
        $products = $products->where(DB::raw('IF(upgrade_agent > 0, products.upgrade_agent, products.id)'), ">", DB::raw('IF(upgrade_agent > 0, '.Auth::guard('merchant')->user()->lvl.',0)'));
        }
        }elseif(Auth::guard('web')->check()){
        $products = $products->where('customer_only', '1');
        }elseif(Auth::guard('dropshipper')->check()){
        $products = $products->where('drop_shipper_only', '1');
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
                  if(Auth::guard('merchant')->check() || Auth::guard('admin')->check()){
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
                          $products = $products->where(DB::raw('COALESCE(customer_special_price, customer_price)'), '>=', $from);
                      }elseif(empty(request('from')) && !empty(request('to'))){
                          $products = $products->where(DB::raw('COALESCE(customer_special_price, customer_price)'), '<=', $to);
                      }else{
                          $products = $products->where(DB::raw('COALESCE(customer_special_price, customer_price)'), '>=', $from)
                                               ->where(DB::raw('COALESCE(customer_special_price, customer_price)'), '<=', $to);
                      }                    
                  }

                }elseif($column == 'per_page'){
                  $products = $products->paginate($per_page);
                }else{
                  // $products = $products->WhereRaw("MATCH(products.product_name) AGAINST('".request($column)."*' IN BOOLEAN MODE)")
                  //                      ->orWhereRaw("MATCH(b.brand_name) AGAINST('".request($column)."*' IN BOOLEAN MODE)")
                  //                      ->orWhereRaw("MATCH(c.category_name) AGAINST('".request($column)."*' IN BOOLEAN MODE)");
                  $products = $products->where('products.product_name', 'like', '%'.request($column).'%');
                }
                
                $queries[$column] = request($column);

            }
        }
        // echo $products = $products->toSql();

        // exit();
        // $products = $products->toSql();
        // echo $products;

        // exit();
        if(!empty(request('per_page'))){
            $products = $products->appends($queries);        
        }else{
            $products = $products->paginate($per_page)->appends($queries);
        }
        $p = $products;
        
        $count_p = count($p);
        $priceV = [];
        $priceV2 = [];
        $featured_image = [];
        $pricing = [];
        foreach($products as $product){

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

            if($product->second_variation_enable == 1){
              $variations = ProductSecondVariation::select(DB::raw('min(variation_special_price) as MinVSPrice'), 
                                                           DB::raw('min(variation_price) as MinVPrice'),
                                                           DB::raw('max(variation_special_price) as MaxVSPrice'),
                                                           DB::raw('max(variation_price) as MaxVPrice'),

                                                           DB::raw('min(variation_drop_shipper_special_price) as MinDVSPrice'), 
                                                           DB::raw('min(variation_drop_shipper_price) as MinDVPrice'),
                                                           DB::raw('max(variation_drop_shipper_special_price) as MaxDVSPrice'),
                                                           DB::raw('max(variation_drop_shipper_price) as MaxDVPrice'))
                                        ->where('product_id', $product->id)
                                        ->where('variation_name', '!=', '')
                                        ->first();

              $priceV[$product->id] = [$variations->MinVPrice, $variations->MaxVPrice, 
                                       $variations->MinVSPrice, $variations->MaxVSPrice, 
                                       $variations->MinDVPrice, $variations->MaxDVPrice, 
                                       $variations->MinDVSPrice, $variations->MaxDVSPrice];
            }else{
              $variations = ProductVariation::select(DB::raw('min(variation_special_price) as MinVSPrice'), 
                                                     DB::raw('min(variation_price) as MinVPrice'),
                                                     DB::raw('max(variation_special_price) as MaxVSPrice'),
                                                     DB::raw('max(variation_price) as MaxVPrice'),

                                                     DB::raw('min(variation_drop_shipper_special_price) as MinDVSPrice'), 
                                                     DB::raw('min(variation_drop_shipper_price) as MinDVPrice'),
                                                     DB::raw('max(variation_drop_shipper_special_price) as MaxDVSPrice'),
                                                     DB::raw('max(variation_drop_shipper_price) as MaxDVPrice'))
                                        ->where('product_id', $product->id)
                                        ->where('variation_name', '!=', '')
                                        ->first();

              $priceV[$product->id] = [$variations->MinVPrice, $variations->MaxVPrice, 
                                       $variations->MinVSPrice, $variations->MaxVSPrice,
                                       $variations->MinDVPrice, $variations->MaxDVPrice, 
                                       $variations->MinDVSPrice, $variations->MaxDVSPrice];              
            }


            $variations2 = ProductSecondVariation::select(DB::raw("max(IF(variation_special_price != '0', variation_special_price, variation_price)) AS maxVPrice"),
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
                                          ->where('product_id', $product->id)
                                          ->where('variation_name', '!=', '')
                                          ->first();
            $priceV2[$product->id] = [$variations2->maxVPrice, $variations2->minVPrice, $variations2->maxVAPrice, $variations2->minVAPrice,
                                     $variations2->MinVASPrice, $variations2->MinVAPrice, $variations2->MaxVASPrice, $variations2->MaxVAPrice, 
                                     $variations2->MinVSPrice, $variations2->MinVPrice, $variations2->MaxVSPrice, $variations2->MaxVPrice];
        }
        // $categories = [];
        // $brands = [];
        // foreach($products as $product){
        //   $categories[] = $product->category_name;
        //   $brands[] = $product->brand_name;
        // }

        $categories = Category::select('categories.*', DB::raw('IF(sorting > 0, sorting, 1000000) Ordersorting'))->where('status', '1')->orderBy('Ordersorting', 'asc')->get();
        $sub_categories = [];
        $sub_sub_categories = [];
        foreach($categories as $category){
            $sub_categories[$category->id] = SubCategory::select('sub_categories.*', DB::raw('IF(sorting > 0, sorting, 1000000) Ordersorting'))->where('category_id', $category->id)->where('status', '1')->orderBy('Ordersorting', 'asc')->get();

            foreach ($sub_categories[$category->id] as $sub_category) {
              $sub_sub_categories[$sub_category->id] = SubSubCategory::select('sub_sub_categories.*', DB::raw('IF(sorting > 0, sorting, 1000000) Ordersorting'))->where('sub_category_id', $sub_category->id)->where('status', '1')->orderBy('Ordersorting', 'asc')->get();
            }
        }

        $get_sub_categories = "";
        if(!empty(request('category'))){
            $get_sub_categories = SubCategory::select('sub_categories.*')
                                             ->join('categories as c', 'c.id', 'sub_categories.category_id')
                                             ->where('category_name', request('category'))
                                             ->where('sub_categories.status', '1')
                                             ->get();
        }

        $get_sub_sub_categories = "";
        if(!empty(request('subcategory')) && !empty(request('category'))){
            $get_sub_sub_categories = SubSubCategory::select('sub_sub_categories.*')
                                             ->join('sub_categories as sc', 'sc.id', 'sub_sub_categories.sub_category_id')
                                             ->join('categories as c', 'c.id', 'sub_sub_categories.category_id')
                                             ->where('category_name', request('category'))
                                             ->where('sub_category_name', request('subcategory'))
                                             ->where('sub_sub_categories.status', '1')
                                             ->get();
        }

        $brands = Brand::where('status', '1')->get();

        $favourite = [];
        $listingImages = [];
        foreach ($products as $key => $value) {
            if(!empty($userCode)){
              $favourite[$value->id] = Favourite::where('product_id', $value->id)->where('user_id', $userCode)->exists();
            }

            $listingImages[$value->id] = ProductImage::where('product_id', $value->id)->orderBy('sort_level', 'asc')->first();

        }

        $sp_products = Product::select('products.*', 'i.image',
                                       DB::raw('(CASE WHEN agent_special_price != 0  THEN agent_special_price ELSE agent_price END) AS agent_actual_price'),
                                       DB::raw('(CASE WHEN customer_special_price != 0  THEN customer_special_price ELSE customer_price END) AS retail_price'))
                              ->leftJoin($leftJoin, function($join) {
                                  $join->on('products.id', '=', 'i.product_id');
                              })
                              ->where(function($query){
                                  $query->where('agent_special_price', '!=', '0')
                                        ->orWhere('customer_special_price', '!=', '0');
                              })
                              ->where('products.status', '1')
                              ->get();

        $MaxMinPrice = Product::select(DB::raw('max(COALESCE(customer_special_price, customer_price)) AS max_price'),
                                       DB::raw('min(COALESCE(customer_special_price, customer_price)) AS min_price'))
                              ->where('status', '1')
                              ->first();
        $brand_banner_image = "";
        if(!empty(request('brand'))){
          $brand_banner_image = Brand::where('brand_name', 'like', '%'.request('brand').'%')->where('status', '1')->first();
        }

        $agent_levels = AgentLevel::get();
        $agent_pricing = AgentPrice::get();

        return view('frontend.listing', ['products'=>$products, 'categories'=>$categories, 'brands'=>$brands, 'count_p'=>$count_p, 
                                         'sp_products'=>$sp_products, 'MaxMinPrice'=>$MaxMinPrice, 'brand_banner_image'=>$brand_banner_image, 'agent_levels'=>$agent_levels, 'agent_pricing'=>$agent_pricing,
                                         'get_sub_categories'=>$get_sub_categories,
                                         'get_sub_sub_categories'=>$get_sub_sub_categories],
                                        compact('favourite', 'priceV', 'sub_categories', 'listingImages', 'featured_image',
                                                'priceV2', 'pricing', 'sub_sub_categories', ));
    }

    public function PointMall()
    {
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
        }else{
          $user = "";
          $userCode = "";
          $buyerLvl = "";
        }

        $leftJoin = DB::raw("(SELECT * FROM product_images ORDER BY sort_level ASC) AS i");
        $products = Product::select('products.*', 'c.category_name', 'b.brand_name',
                                    DB::raw('(CASE WHEN agent_special_price != 0  THEN agent_special_price ELSE agent_price END) AS agent_actual_price'),
                                    DB::raw('(CASE WHEN customer_special_price != 0  THEN customer_special_price ELSE customer_price END) AS retail_price'), DB::raw('IF(products.sorting > 0, products.sorting, 1000000) Ordersorting'))
                           ->leftJoin('categories AS c', 'c.id', 'products.category_id')
                           ->leftJoin('sub_categories AS sc', 'sc.id', 'products.sub_category_id')
                           ->leftJoin('sub_sub_categories as ssc', 'ssc.id', 'products.sub_sub_category_id')
                           ->leftJoin('brands AS b', 'b.id', 'products.brand_id')
                           ->where('products.status', '1')
                           ->where('mall', '1')
                           ->groupBy('products.id')
                           ->orderBy('Ordersorting', 'asc');

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
                  if(Auth::guard('merchant')->check() || Auth::guard('admin')->check()){
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
                          $products = $products->where(DB::raw('COALESCE(customer_special_price, customer_price)'), '>=', $from);
                      }elseif(empty(request('from')) && !empty(request('to'))){
                          $products = $products->where(DB::raw('COALESCE(customer_special_price, customer_price)'), '<=', $to);
                      }else{
                          $products = $products->where(DB::raw('COALESCE(customer_special_price, customer_price)'), '>=', $from)
                                               ->where(DB::raw('COALESCE(customer_special_price, customer_price)'), '<=', $to);
                      }                    
                  }

                }elseif($column == 'per_page'){
                  $products = $products->paginate($per_page);
                }else{
                  // $products = $products->WhereRaw("MATCH(products.product_name) AGAINST('".request($column)."*' IN BOOLEAN MODE)")
                  //                      ->orWhereRaw("MATCH(b.brand_name) AGAINST('".request($column)."*' IN BOOLEAN MODE)")
                  //                      ->orWhereRaw("MATCH(c.category_name) AGAINST('".request($column)."*' IN BOOLEAN MODE)");
                  $products = $products->where('products.product_name', 'like', '%'.request($column).'%');
                }
                
                $queries[$column] = request($column);

            }
        }
        // echo $products = $products->toSql();

        // exit();

        if(!empty(request('per_page'))){
            $products = $products->appends($queries);        
        }else{
            $products = $products->paginate($per_page)->appends($queries);
        }
        $p = $products;
        
        $count_p = count($p);
        $priceV = [];
        $priceV2 = [];
        $featured_image = [];
        $pricing = [];
        foreach($products as $product){

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

            if($product->second_variation_enable == 1){
              $variations = ProductSecondVariation::select(DB::raw('min(variation_special_price) as MinVSPrice'), 
                                                     DB::raw('min(variation_price) as MinVPrice'),
                                                     DB::raw('max(variation_special_price) as MaxVSPrice'),
                                                     DB::raw('max(variation_price) as MaxVPrice'))
                                        ->where('product_id', $product->id)
                                        ->where('variation_name', '!=', '')
                                        ->first();

              $priceV[$product->id] = [$variations->MinVPrice, $variations->MaxVPrice];
            }else{
              $variations = ProductVariation::select(DB::raw('min(variation_special_price) as MinVSPrice'), 
                                                     DB::raw('min(variation_price) as MinVPrice'),
                                                     DB::raw('max(variation_special_price) as MaxVSPrice'),
                                                     DB::raw('max(variation_price) as MaxVPrice'))
                                        ->where('product_id', $product->id)
                                        ->where('variation_name', '!=', '')
                                        ->first();

              $priceV[$product->id] = [$variations->MinVPrice, $variations->MaxVPrice];              
            }


            $variations2 = ProductSecondVariation::select(DB::raw("max(IF(variation_special_price != '0', variation_special_price, variation_price)) AS maxVPrice"),
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
                                          ->where('product_id', $product->id)
                                          ->where('variation_name', '!=', '')
                                          ->first();
            $priceV2[$product->id] = [$variations2->maxVPrice, $variations2->minVPrice, $variations2->maxVAPrice, $variations2->minVAPrice,
                                     $variations2->MinVASPrice, $variations2->MinVAPrice, $variations2->MaxVASPrice, $variations2->MaxVAPrice, 
                                     $variations2->MinVSPrice, $variations2->MinVPrice, $variations2->MaxVSPrice, $variations2->MaxVPrice];
        }
        // $categories = [];
        // $brands = [];
        // foreach($products as $product){
        //   $categories[] = $product->category_name;
        //   $brands[] = $product->brand_name;
        // }

        $categories = Category::select('categories.*', DB::raw('IF(sorting > 0, sorting, 1000000) Ordersorting'))->where('status', '1')->orderBy('Ordersorting', 'asc')->get();
        $sub_categories = [];
        $sub_sub_categories = [];
        foreach($categories as $category){
            $sub_categories[$category->id] = SubCategory::select('sub_categories.*', DB::raw('IF(sorting > 0, sorting, 1000000) Ordersorting'))->where('category_id', $category->id)->where('status', '1')->orderBy('Ordersorting', 'asc')->get();

            foreach ($sub_categories[$category->id] as $sub_category) {
              $sub_sub_categories[$sub_category->id] = SubSubCategory::select('sub_sub_categories.*', DB::raw('IF(sorting > 0, sorting, 1000000) Ordersorting'))->where('sub_category_id', $sub_category->id)->where('status', '1')->orderBy('Ordersorting', 'asc')->get();
            }
        }

        $get_sub_categories = "";
        if(!empty(request('category'))){
            $get_sub_categories = SubCategory::select('sub_categories.*')
                                             ->join('categories as c', 'c.id', 'sub_categories.category_id')
                                             ->where('category_name', request('category'))
                                             ->where('sub_categories.status', '1')
                                             ->get();
        }

        $get_sub_sub_categories = "";
        if(!empty(request('subcategory')) && !empty(request('category'))){
            $get_sub_sub_categories = SubSubCategory::select('sub_sub_categories.*')
                                             ->join('sub_categories as sc', 'sc.id', 'sub_sub_categories.sub_category_id')
                                             ->join('categories as c', 'c.id', 'sub_sub_categories.category_id')
                                             ->where('category_name', request('category'))
                                             ->where('sub_category_name', request('subcategory'))
                                             ->where('sub_sub_categories.status', '1')
                                             ->get();
        }

        $brands = Brand::where('status', '1')->get();

        $favourite = [];
        $listingImages = [];
        foreach ($products as $key => $value) {
            if(!empty($userCode)){
              $favourite[$value->id] = Favourite::where('product_id', $value->id)->where('user_id', $userCode)->exists();
            }

            $listingImages[$value->id] = ProductImage::where('product_id', $value->id)->orderBy('sort_level', 'asc')->first();

        }

        $sp_products = Product::select('products.*', 'i.image',
                                       DB::raw('(CASE WHEN agent_special_price != 0  THEN agent_special_price ELSE agent_price END) AS agent_actual_price'),
                                       DB::raw('(CASE WHEN customer_special_price != 0  THEN customer_special_price ELSE customer_price END) AS retail_price'))
                              ->leftJoin($leftJoin, function($join) {
                                  $join->on('products.id', '=', 'i.product_id');
                              })
                              ->where(function($query){
                                  $query->where('agent_special_price', '!=', '0')
                                        ->orWhere('customer_special_price', '!=', '0');
                              })
                              ->where('products.status', '1')
                              ->get();

        $MaxMinPrice = Product::select(DB::raw('max(COALESCE(customer_special_price, customer_price)) AS max_price'),
                                       DB::raw('min(COALESCE(customer_special_price, customer_price)) AS min_price'))
                              ->where('status', '1')
                              ->first();
        $brand_banner_image = "";
        if(!empty(request('brand'))){
          $brand_banner_image = Brand::where('brand_name', 'like', '%'.request('brand').'%')->where('status', '1')->first();
        }

        $agent_levels = AgentLevel::get();
        $agent_pricing = AgentPrice::get();

        return view('frontend.listing_mall', ['products'=>$products, 'categories'=>$categories, 'brands'=>$brands, 'count_p'=>$count_p, 
                                         'sp_products'=>$sp_products, 'MaxMinPrice'=>$MaxMinPrice, 'brand_banner_image'=>$brand_banner_image, 'agent_levels'=>$agent_levels, 'agent_pricing'=>$agent_pricing,
                                         'get_sub_categories'=>$get_sub_categories,
                                         'get_sub_sub_categories'=>$get_sub_sub_categories],
                                        compact('favourite', 'priceV', 'sub_categories', 'listingImages', 'featured_image',
                                                'priceV2', 'pricing', 'sub_sub_categories', ));
    }

    public function promotion_listing()
    {

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
                }

                $variationCustomerRange = PromoAgentItemDetail::select(DB::raw('max(special_price) as MaxSPrice'),
                                                          DB::raw('min(special_price) as MinSPrice'),
                                                              DB::raw('min(price) as MinPrice'),
                                                          DB::raw('max(price) as MaxPrice'))
                                                  ->whereNull('agent_lvl_id')
                                                  ->where('promo_item_id', $promo_item->pai_id)
                                                  ->where('status', '1')
                                                  ->first();
                                                  
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

    public function promotion()
    {

        if(Auth::guard('web')->check()){
          $user = Auth::guard('web')->check();
          $userCode = Auth::guard('web')->user()->code;
        }elseif (Auth::guard('merchant')->check()) {
          $user = Auth::guard('merchant')->check();
          $userCode = Auth::guard('merchant')->user()->code;
        }elseif (Auth::guard('admin')->check()) {
          $user = Auth::guard('admin')->check();
          $userCode = Auth::guard('admin')->user()->code;
        }else{
          $user = "";
          $userCode = "";
        }

        $leftJoin = DB::raw("(SELECT * FROM product_images ORDER BY sort_level ASC) AS i");
        $products = Product::select('products.*', 'c.category_name', 'b.brand_name',
                                    DB::raw('(CASE WHEN agent_special_price != 0  THEN agent_special_price ELSE agent_price END) AS agent_actual_price'),
                                    DB::raw('(CASE WHEN special_price != 0  THEN special_price ELSE price END) AS retail_price'))
                           ->leftJoin('categories AS c', 'c.id', 'products.category_id')
                           ->leftJoin('sub_categories AS sc', 'sc.id', 'products.sub_category_id')
                           ->leftJoin('brands AS b', 'b.id', 'products.brand_id')
                           ->where('products.status', '1')
                           ->whereNull('mall')
                           ->groupBy('products.id')
                           ->orderBy('products.product_name', 'asc');

        $queries = [];
        $columns = [
           'result', 'brand', 'category', 'subcategory', 'from', 'to'
        ];
        // return htmlspecialchars(request('category'));
        foreach($columns as $column){
            if(request()->has($column) && !empty(request($column))){
                
                if($column == 'category'){
                  $products = $products->where('c.category_name', request($column));
                }elseif($column == 'subcategory'){
                  $products = $products->where('sc.sub_category_name', request($column));
                }elseif($column == 'brand'){
                  $products = $products->where('b.brand_name', request($column));
                }elseif($column == 'from' || $column == 'to'){
                  $from = preg_replace("/[^0-9\.]/", '', request('from'));
                  $to = preg_replace("/[^0-9\.]/", '', request('to'));
                  if(Auth::guard('merchant')->check() || Auth::guard('admin')->check()){
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

                }else{
                  // $products = $products->WhereRaw("MATCH(products.product_name) AGAINST('".request($column)."*' IN BOOLEAN MODE)")
                  //                      ->orWhereRaw("MATCH(b.brand_name) AGAINST('".request($column)."*' IN BOOLEAN MODE)")
                  //                      ->orWhereRaw("MATCH(c.category_name) AGAINST('".request($column)."*' IN BOOLEAN MODE)");
                  $products = $products->where('products.product_name', 'like', '%'.request($column).'%');
                }
                
                $queries[$column] = request($column);

            }
        }
        // echo $products = $products->toSql();

        // exit();

        $p = $products->get();
        $count_p = count($p);
        $products = $products->paginate(24)->appends($queries);
        $priceV = [];
        $priceV2 = [];
        $featured_image = [];
        $pricing = [];
        foreach($products as $product){

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
                                          ->where('product_id', $product->id)
                                          ->where('variation_name', '!=', '')
                                          ->first();
            $priceV[$product->id] = [$variations->maxVPrice, $variations->minVPrice, $variations->maxVAPrice, $variations->minVAPrice,
                                     $variations->MinVASPrice, $variations->MinVAPrice, $variations->MaxVASPrice, $variations->MaxVAPrice, 
                                     $variations->MinVSPrice, $variations->MinVPrice, $variations->MaxVSPrice, $variations->MaxVPrice];


            $variations2 = ProductSecondVariation::select(DB::raw("max(IF(variation_special_price != '0', variation_special_price, variation_price)) AS maxVPrice"),
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
                                          ->where('product_id', $product->id)
                                          ->where('variation_name', '!=', '')
                                          ->first();
            $priceV2[$product->id] = [$variations2->maxVPrice, $variations2->minVPrice, $variations2->maxVAPrice, $variations2->minVAPrice,
                                     $variations2->MinVASPrice, $variations2->MinVAPrice, $variations2->MaxVASPrice, $variations2->MaxVAPrice, 
                                     $variations2->MinVSPrice, $variations2->MinVPrice, $variations2->MaxVSPrice, $variations2->MaxVPrice];
        }
        // $categories = [];
        // $brands = [];
        // foreach($products as $product){
        //   $categories[] = $product->category_name;
        //   $brands[] = $product->brand_name;
        // }

        $categories = Category::where('status', '1')->get();
        $sub_categories = [];
        foreach($categories as $category){
            $sub_categories[$category->id] = SubCategory::where('category_id', $category->id)->where('status', '1')->get();
        }

        $brands = Brand::where('status', '1')->get();

        $favourite = [];
        $listingImages = [];
        foreach ($products as $key => $value) {
            if(!empty($userCode)){
              $favourite[$value->id] = Favourite::where('product_id', $value->id)->where('user_id', $userCode)->exists();
            }

            $listingImages[$value->id] = ProductImage::where('product_id', $value->id)->orderBy('sort_level', 'asc')->first();

        }

        $sp_products = Product::select('products.*', 'i.image',
                                       DB::raw('(CASE WHEN agent_special_price != 0  THEN agent_special_price ELSE agent_price END) AS agent_actual_price'),
                                       DB::raw('(CASE WHEN special_price != 0  THEN special_price ELSE price END) AS retail_price'))
                              ->leftJoin($leftJoin, function($join) {
                                  $join->on('products.id', '=', 'i.product_id');
                              })
                              ->where(function($query){
                                  $query->where('agent_special_price', '!=', '0')
                                        ->orWhere('special_price', '!=', '0');
                              })
                              ->where('products.status', '1')
                              ->get();

        $MaxMinPrice = Product::select(DB::raw('max(COALESCE(special_price, price)) AS max_price'),
                                       DB::raw('min(COALESCE(special_price, price)) AS min_price'))
                              ->where('status', '1')
                              ->first();
        $brand_banner_image = "";
        if(!empty(request('brand'))){
          $brand_banner_image = Brand::where('brand_name', 'like', '%'.request('brand').'%')->where('status', '1')->first();
        }

        return view('frontend.promotion', ['products'=>$products, 'categories'=>$categories, 'brands'=>$brands, 'count_p'=>$count_p, 
                                         'sp_products'=>$sp_products, 'MaxMinPrice'=>$MaxMinPrice, 'brand_banner_image'=>$brand_banner_image],
                                        compact('favourite', 'priceV', 'sub_categories', 'listingImages', 'featured_image',
                                                'priceV2', 'pricing'));
    }

    public function details($name, $id)
    {
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

        }else{

          $user = "";
          $userCode = "";
          $buyerLvl = "";

        }

        $product = Product::select('products.*', 'u.uom_name', 'b.brand_name', 'c.category_name')
                          ->leftJoin('setting_uoms AS u', 'u.id', 'products.product_type')
                          ->leftJoin('brands AS b', 'b.id', 'products.brand_id')
                          ->leftJoin('categories AS c', 'c.id', 'products.category_id')
                          ->where(DB::raw('md5(products.id)'), $id);

        if(Auth::guard('merchant')->check() && !empty(Auth::guard('merchant')->user()->lvl)){
        $product = $product->where(DB::raw('IF(upgrade_agent > 0, products.upgrade_agent, products.id)'), ">", DB::raw('IF(upgrade_agent > 0, '.Auth::guard('merchant')->user()->lvl.',0)'));
        }

        $product = $product->first();

        if(empty($product)){
            return redirect()->route('home');
        }

        // if($product->packages == 1 || $product->variation_enable == 1){
        //   $stockBalance = 1000000000;
        // }else{
          $stockBalance = $this->BalanceQuantity($product->id);          
        // }

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

        $priceV = [];
        if($product->second_variation_enable == 1){
          $variations = ProductSecondVariation::select(DB::raw('min(variation_special_price) as MinVSPrice'), 
                                                 DB::raw('min(variation_price) as MinVPrice'),
                                                 DB::raw('max(variation_special_price) as MaxVSPrice'),
                                                 DB::raw('max(variation_price) as MaxVPrice'),

                                                 DB::raw('min(variation_drop_shipper_special_price) as MinDVSPrice'), 
                                                 DB::raw('min(variation_drop_shipper_price) as MinDVPrice'),
                                                 DB::raw('max(variation_drop_shipper_special_price) as MaxDVSPrice'),
                                                 DB::raw('max(variation_drop_shipper_price) as MaxDVPrice'))
                                    ->where('product_id', $product->id)
                                    ->where('variation_name', '!=', '')
                                    ->first();

          $priceV[$product->id] = [$variations->MinVPrice, $variations->MaxVPrice, 
                                   $variations->MaxVSPrice, $variations->MinVSPrice, 
                                   $variations->MinDVPrice, $variations->MaxDVPrice, 
                                   $variations->MinDVSPrice, $variations->MaxDVSPrice];
        }else{
          $variations = ProductVariation::select(DB::raw('min(variation_special_price) as MinVSPrice'), 
                                                 DB::raw('min(variation_price) as MinVPrice'),
                                                 DB::raw('max(variation_special_price) as MaxVSPrice'),
                                                 DB::raw('max(variation_price) as MaxVPrice'),

                                                 DB::raw('min(variation_drop_shipper_special_price) as MinDVSPrice'), 
                                                 DB::raw('min(variation_drop_shipper_price) as MinDVPrice'),
                                                 DB::raw('max(variation_drop_shipper_special_price) as MaxDVSPrice'),
                                                 DB::raw('max(variation_drop_shipper_price) as MaxDVPrice'))
                                    ->where('product_id', $product->id)
                                    ->where('variation_name', '!=', '')
                                    ->first();

          $priceV[$product->id] = [$variations->MinVPrice, $variations->MaxVPrice, 
                                   $variations->MaxVSPrice, $variations->MinVSPrice, 
                                   $variations->MinDVPrice, $variations->MaxDVPrice, 
                                   $variations->MinDVSPrice, $variations->MaxDVSPrice];              
        }

        $images = ProductImage::where('product_id', $product->id)
                              ->where('status', '1')
                              ->orderBy('sort_level', 'asc')
                              ->get();

        $videos = ProductVideo::where('product_id', $product->id)
                              ->where('status', '1')
                              ->orderBy('sort_level', 'asc')
                              ->get();

        $favourite = [];
        if($user){
          $favourite = Favourite::where('user_id', $userCode)
                                ->where('product_id', $product->id)
                                ->first();
        }

        $leftJoin = DB::raw("(SELECT * FROM product_images ORDER BY created_at ASC) AS i");
        

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
        foreach($variations as $variation){
            $vStock[$variation->id] = $this->VariationBalanceQuantity($variation->id);
        }

        foreach($second_variations as $second_variation){
          $svStock[$second_variation->id] = $this->SecondVariationBalanceQuantity($second_variation->id);
        }

        return view('frontend.details', ['product'=>$product, 'stockBalance'=>$stockBalance, 'images'=>$images, 
                                         'favourite'=>$favourite, 'Pimage'=>$Pimage, 'Ppackages'=>$Ppackages,
                                         'variations'=>$variations,
                                         'second_variations'=>$second_variations,
                                         'videos'=>$videos], 
                                         compact('vStock','priceV', 'svStock', 'pricing'));
    }

    public function details_mall($name, $id)
    {
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

        }else{

          $user = "";
          $userCode = "";
          $buyerLvl = "";

        }

        $product = Product::select('products.*', 'u.uom_name', 'b.brand_name', 'c.category_name')
                          ->leftJoin('setting_uoms AS u', 'u.id', 'products.product_type')
                          ->leftJoin('brands AS b', 'b.id', 'products.brand_id')
                          ->leftJoin('categories AS c', 'c.id', 'products.category_id')
                          ->where(DB::raw('md5(products.id)'), $id)
                          ->first();
        if(empty($product)){
            return redirect()->route('home');
        }

        // if($product->packages == 1 || $product->variation_enable == 1){
        //   $stockBalance = 1000000000;
        // }else{
          $stockBalance = $this->BalanceQuantity($product->id);          
        // }

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

        $priceV = [];
        if($product->second_variation_enable == 1){
          $variations = ProductSecondVariation::select(DB::raw('min(variation_special_price) as MinVSPrice'), 
                                                 DB::raw('min(variation_price) as MinVPrice'),
                                                 DB::raw('max(variation_special_price) as MaxVSPrice'),
                                                 DB::raw('max(variation_price) as MaxVPrice'))
                                    ->where('product_id', $product->id)
                                    ->where('variation_name', '!=', '')
                                    ->first();

          $priceV[$product->id] = [$variations->MinVPrice, $variations->MaxVPrice];
        }else{
          $variations = ProductVariation::select(DB::raw('min(variation_special_price) as MinVSPrice'), 
                                                 DB::raw('min(variation_price) as MinVPrice'),
                                                 DB::raw('max(variation_special_price) as MaxVSPrice'),
                                                 DB::raw('max(variation_price) as MaxVPrice'))
                                    ->where('product_id', $product->id)
                                    ->where('variation_name', '!=', '')
                                    ->first();

          $priceV[$product->id] = [$variations->MinVPrice, $variations->MaxVPrice];              
        }

        $images = ProductImage::where('product_id', $product->id)
                              ->where('status', '1')
                              ->orderBy('sort_level', 'asc')
                              ->get();

        $videos = ProductVideo::where('product_id', $product->id)
                              ->where('status', '1')
                              ->orderBy('sort_level', 'asc')
                              ->get();

        $favourite = [];
        if($user){
          $favourite = Favourite::where('user_id', $userCode)
                                ->where('product_id', $product->id)
                                ->first();
        }

        $leftJoin = DB::raw("(SELECT * FROM product_images ORDER BY created_at ASC) AS i");
        

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
        foreach($variations as $variation){
            $vStock[$variation->id] = $this->VariationBalanceQuantity($variation->id);
        }

        foreach($second_variations as $second_variation){
          $svStock[$second_variation->id] = $this->SecondVariationBalanceQuantity($second_variation->id);
        }

        return view('frontend.details_mall', ['product'=>$product, 'stockBalance'=>$stockBalance, 'images'=>$images, 
                                         'favourite'=>$favourite, 'Pimage'=>$Pimage, 'Ppackages'=>$Ppackages,
                                         'variations'=>$variations,
                                         'second_variations'=>$second_variations,
                                         'videos'=>$videos], 
                                         compact('vStock','priceV', 'svStock', 'pricing'));
    }

    public function promo_details($name, $id)
    {
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

        }else{

          $user = "";
          $userCode = "";
          $buyerLvl = "";

        }

        

        $title = PromoAgentItem::select('p.*', 'paid.price as pai_price',
                                      'paid.special_price as pai_special_price',
                                      'b.brand_name', 'promo_agent_items.id as pai_id',
                                      'psv.variation_name as psv_variation_name', 
                                      'psv.id as psv_id',
                                      'pv.variation_name as pv_variation_name', 
                                      'pv.id as pv_id')
                             ->join('promo_item_titles as t', 't.id', 'promo_agent_items.title_id')
                             ->join('products AS p', 'p.id', 'promo_agent_items.product_id')
                             ->leftJoin('promo_agent_item_details as paid', 'paid.promo_item_id', 'promo_agent_items.id')
                             ->leftJoin('product_variations AS pv', 'pv.id', 'paid.variation_id')
                             ->leftJoin('product_second_variations AS psv', 'psv.id', 'paid.second_variation_id')
                             ->leftJoin('brands AS b', 'b.id', 'p.brand_id')
                             ->where(DB::raw('md5(promo_agent_items.id)'), $id)
                             ->where('t.date_from', '<=', date('Y-m-d H:i:s'))
                             ->where('t.date_end', '>=', date('Y-m-d H:i:s'));
        if(!empty($buyerLvl)){
        $title = $title->where('paid.agent_lvl_id', $buyerLvl);
        }
        $title = $title->first();

        if(empty($title->id)){
            abort(404);
        }                       


        $product = Product::select('products.*', 'u.uom_name', 'b.brand_name', 'c.category_name')
                          ->leftJoin('setting_uoms AS u', 'u.id', 'products.product_type')
                          ->leftJoin('brands AS b', 'b.id', 'products.brand_id')
                          ->leftJoin('categories AS c', 'c.id', 'products.category_id')
                          ->where('products.id', $title->id)
                          ->first();
        if(empty($product)){
            return redirect()->route('home');
        }

        // if($product->packages == 1 || $product->variation_enable == 1){
        //   $stockBalance = 1000000000;
        // }else{
          $stockBalance = $this->BalanceQuantity($product->id);          
        // }

        $pricingRange = [];
        if(!empty($buyerLvl)){
            $variations = PromoAgentItemDetail::select(DB::raw('max(COALESCE(price)) as MaxVAPrice'),
                                                                  DB::raw('min(COALESCE(special_price, price)) as MinVAPrice'))
                                                      ->where('promo_item_id', $title->pai_id)
                                                      ->where('status', '1')
                                                      ->first();

            $pricingRange[$title->pai_id] = [$variations->MaxVAPrice, $variations->MinVAPrice];
        }

        $sec_var_price_range = [];
        $pricing = [];
        if(!empty($buyerLvl)){
            // $variations = PromoAgentPrice::select('promo_agent_prices.*')
            //                         ->leftJoin('promo_agent_items', 'promo_agent_items.id', 'promo_agent_prices.item_id')
            //                         ->where('agent_lvl_id', $buyerLvl)
            //                         ->where('promo_agent_items.product_id', $product->id)
            //                         ->first();

            $variations = PromoAgentItemDetail::select('promo_agent_item_details.*')
                                              ->leftJoin('promo_agent_items', 'promo_agent_items.id', 'promo_agent_item_details.promo_item_id')
                                              ->where('agent_lvl_id', $buyerLvl)
                                              ->where('promo_agent_items.product_id', $product->id)
                                              ->where('promo_agent_item_details.status', '1')
                                              ->first();
            // echo $product->id;
            $pricing[$title->id] = [$variations->price];

            $variationRange = PromoAgentItemDetail::select(DB::raw('max(special_price) as MaxSPrice'),
                                                              DB::raw('min(special_price) as MinSPrice'),
                                                                  DB::raw('min(price) as MinPrice'),
                                                              DB::raw('max(price) as MaxPrice'))
                                                      ->where('agent_lvl_id', $buyerLvl)
                                                      ->where('promo_item_id', $title->pai_id)
                                                      ->where('status', '1')
                                                      ->first();
                                                      
            $sec_var_price_range[$title->pai_id] = [$variationRange->MinSPrice, $variationRange->MaxSPrice, $variationRange->MinPrice, $variationRange->MaxPrice];
        }

        $variationCustomerRange = PromoAgentItemDetail::select(DB::raw('max(special_price) as MaxSPrice'),
                                                          DB::raw('min(special_price) as MinSPrice'),
                                                              DB::raw('min(price) as MinPrice'),
                                                          DB::raw('max(price) as MaxPrice'))
                                                  ->whereNull('agent_lvl_id')
                                                  ->where('promo_item_id', $title->pai_id)
                                                  ->where('status', '1')
                                                  ->first();
                                                  
        $sec_var_price_range_customer[$title->pai_id] = [$variationCustomerRange->MinSPrice, $variationCustomerRange->MaxSPrice, $variationCustomerRange->MinPrice, $variationCustomerRange->MaxPrice];

        // exit();

        $images = ProductImage::where('product_id', $product->id)
                              ->where('status', '1')
                              ->orderBy('sort_level', 'asc')
                              ->get();

        $favourite = [];
        if($user){
          $favourite = Favourite::where('user_id', $userCode)
                                ->where('product_id', $product->id)
                                ->first();
        }

        $leftJoin = DB::raw("(SELECT * FROM product_images ORDER BY created_at ASC) AS i");
        

        $Pimage = ProductImage::where('product_id', $product->id)->orderBy('sort_level', 'asc')->first();

        $Ppackages = PackageItem::select('p.product_name', 'package_items.*', 'i.image')
                                ->join('products AS p', 'p.id', 'package_items.products')
                                ->leftJoin($leftJoin, function($join) {
                                    $join->on('p.id', '=', 'i.product_id');
                                })
                                ->where('package_items.product_id', $product->id)
                                ->groupBy('package_items.products')
                                ->get();

        $variations = ProductVariation::where('product_id', $product->id)->where('variation_name', '!=', '')->get();

        $second_variations = ProductSecondVariation::where('product_id', $product->id)
                                                   ->where('variation_name', '!=', '')
                                                   ->groupBy('variation_name')
                                                   ->get();

        $vStock = [];
        $svStock = [];
        foreach($variations as $variation){
            $vStock[$variation->id] = $this->VariationBalanceQuantity($variation->id);
        }

        foreach($second_variations as $second_variation){
          $svStock[$second_variation->id] = $this->SecondVariationBalanceQuantity($second_variation->id);
        }

        $videos = ProductVideo::where('product_id', $product->id)
                              ->where('status', '1')
                              ->orderBy('sort_level', 'asc')
                              ->get();

        return view('frontend.promo_details', ['product'=>$product, 'stockBalance'=>$stockBalance, 'images'=>$images, 
                                         'favourite'=>$favourite, 'Pimage'=>$Pimage, 'Ppackages'=>$Ppackages,
                                         'variations'=>$variations, 'videos' => $videos,
                                         'second_variations'=>$second_variations, 'title'=>$title], 
                                         compact('vStock', 'svStock', 'pricing', 'pricingRange', 'sec_var_price_range', 'sec_var_price_range_customer'));
    }

    public function cart()
    {
        $leftJoin = DB::raw("(SELECT * FROM product_images ORDER BY created_at ASC) AS i");

        $carts = Cart::select('carts.id AS cid', 'p.*', 'i.image', 'carts.qty', 'p.weight', 
                              DB::raw('COALESCE(special_price, price) AS actual_price'), 
                              DB::raw('COALESCE(agent_special_price, agent_price) AS agent_actual_price'), 
                              'scl.sub_category_name AS l_sub_name', 'scr.sub_category_name AS r_sub_name')
                     ->join('products AS p', 'p.id', 'carts.product_id')
                     ->leftJoin('sub_categories AS scl', 'scl.id', 'carts.sub_category_id')
                     ->leftJoin('sub_categories AS scr', 'scr.id', 'carts.second_sub_category_id')
                     ->leftJoin($leftJoin, function($join) {
                        $join->on('p.id', '=', 'i.product_id');
                     })
                     ->where('carts.status', '1')
                     ->where('carts.user_id', Auth::user()->code)
                     ->groupBy('carts.id');
        if(!empty(request('m')) && request('m') == '1'){
          $carts = $carts->where('p.mall', '1');
        }else{
          $carts = $carts->whereNULL('p.mall');
        }

        $carts = $carts->get();
        $leftJoin = DB::raw("(SELECT * FROM product_images ORDER BY created_at ASC) AS i");
        $products = Product::select('products.*', 'i.image')
                           ->leftJoin($leftJoin, function($join) {
                                $join->on('products.id', '=', 'i.product_id');
                           })
                           ->where('products.status', '1')
                           ->groupBy('products.id')
                           ->take(16)
                           ->get();

        if(!$carts->isEmpty()){
            foreach($carts as $key => $cart){
                $stockBalance[$cart->cid] = $this->BalanceQuantity($cart->id);
            }

            return view('frontend.cart', ['carts'=>$carts, 'products'=>$products], compact('stockBalance'));
        }else{
            $stockBalance = 0;
            return view('frontend.cart', ['carts'=>$carts, 'products'=>$products], compact('stockBalance'));
        }
    }

    public function checkout()
    {
        if(!empty(Auth::guard('admin')->check())){
            $buyerCode = Auth::guard('admin')->user()->code;
            $buyerLvl = Auth::guard('admin')->user()->lvl;
        }elseif(!empty(Auth::guard('merchant')->check())){
            $buyerCode = Auth::guard('merchant')->user()->code;
            $buyerLvl = Auth::guard('merchant')->user()->lvl;
        }elseif(!empty(Auth::guard('web')->check())){
            $buyerCode = Auth::guard('web')->user()->code;
            $buyerLvl = Auth::guard('web')->user()->lvl;
        }elseif(!empty(Auth::guard('dropshipper')->check())){
            $buyerCode = Auth::guard('dropshipper')->user()->code;
            $buyerLvl = Auth::guard('dropshipper')->user()->lvl;
        }else{
            $buyerCode = "";
            $buyerLvl = "";
        }

        $leftJoin = DB::raw("(SELECT * FROM product_images ORDER BY sort_level ASC) AS i");
        $carts = Cart::select('carts.id AS cid', 'carts.promo_price_id', 'carts.is_free', 'carts.promo', 'p.*', 'i.image', 'carts.qty', DB::raw('COALESCE(customer_special_price, customer_price) AS actual_price'),
                              DB::raw('COALESCE(agent_special_price, agent_price) AS agent_actual_price'),
                              'scl.variation_name', 'scl.variation_price', 'scl.variation_special_price', 'scl.variation_agent_price', 'scl.variation_agent_special_price', 'scl.variation_weight',
                              'p.weight', 'p.customer_special_price', 'p.customer_price', 'p.agent_special_price', 
                              'p.agent_price', 'variation_enable',
                              'p.second_variation_enable',
                              'sscl.variation_name as second_variation_name',
                              'sscl.variation_price as second_variation_price', 
                              'sscl.variation_special_price as second_variation_special_price', 
                              'sscl.variation_agent_price as second_variation_agent_price', 
                              'sscl.variation_agent_special_price as second_variation_agent_special_price', 
                              'sscl.variation_weight as second_variation_weight',
                              'carts.sub_category_id as c_sub_category_id',
                              'carts.second_sub_category_id as c_second_sub_category_id')
                     ->join('products AS p', 'p.id', 'carts.product_id')
                     ->leftJoin('product_variations AS scl', 'scl.id', 'carts.sub_category_id')
                     ->leftJoin('product_second_variations AS sscl', 'sscl.id', 'carts.second_sub_category_id')
                     ->leftJoin($leftJoin, function($join) {
                        $join->on('p.id', '=', 'i.product_id');
                     })
                     ->where('carts.status', '1')
                     ->where('carts.user_id', $buyerCode)
                     ->whereNull('carts.mall')
                     ->groupBy('carts.id');
        $carts = $carts->get();
        $states = State::get();

        if($carts->isEmpty()){
          Toastr::info("Cart is empty");
          return redirect()->route('home');
        }

        $weight = 0;
        $pricing = [];
        $priceV = [];
        $pai = [];
        $listingImages = [];
        $customer_price_V = [];
        $customer_price_non_V = [];
        
       
        foreach($carts as $cart){
            if(!empty($buyerLvl)){
                $variations = AgentPrice::where('agent_lvl_id', $buyerLvl);

                if(!empty($cart->promo)){
                  $variations = PromoAgentItemDetail::where('agent_lvl_id', $buyerLvl)
                                                    ->where('promo_item_id', $cart->promo);
                }

                if($cart->variation_enable == 1 && $cart->second_variation_enable == 1){
                $variations = $variations->where('second_variation_id', $cart->c_second_sub_category_id);
                }elseif($cart->variation_enable == 1 && empty($cart->second_variation_enable)){
                $variations = $variations->where('variation_id', $cart->c_sub_category_id);
                }else{
                    if(!empty($cart->promo)){
                        $variations = $variations->where('promo_item_id', $cart->promo);
                    }else{
                        $variations = $variations->where('product_id', $cart->id);
                    }
                }
                $variations = $variations->first();

                if(!empty($variations->special_price)){
                    $pricing[$cart->cid] = $variations->special_price;
                }else{
                    $pricing[$cart->cid] = $variations->price;
                }

                if(!empty($cart->promo_price_id)){
                  $pap = PromoAgentPrice::where('id', $cart->promo_price_id)->first();

                  $pricing[$cart->cid] = $pap->price;
                }
            }
            $pai[$cart->cid] = PromoAgentItem::find($cart->promo);

            if($cart->second_variation_enable == 1){
              $variations = ProductSecondVariation::where('id', $cart->c_second_sub_category_id)
                                                  ->where('variation_name', '!=', '')
                                                  ->first();

              $priceV[$cart->cid] = [$variations->variation_price, $variations->variation_special_price,
                                     $variations->variation_drop_shipper_price, $variations->variation_drop_shipper_special_price];

              if(!empty($cart->promo)){
                $variations = PromoAgentItemDetail::where('second_variation_id', $cart->c_second_sub_category_id)
                                                  ->where('status', '1')
                                                  ->first();

                $priceV[$cart->cid] = [$variations->price, $variations->special_price];

                $customer_variation_price_range = PromoAgentItemDetail::where('second_variation_id', $cart->c_second_sub_category_id)
                                                                      ->whereNull('agent_lvl_id')
                                                                      ->where('promo_item_id', $cart->promo)
                                                                      ->where('status', '1')
                                                                      ->first();

                $customer_price_V[$cart->cid] = [$customer_variation_price_range->price, $customer_variation_price_range->special_price];
              }
            }else{
              if($cart->variation_enable == 1){
                  $variations = ProductVariation::where('id', $cart->c_sub_category_id)
                                                ->where('variation_name', '!=', '')
                                                ->first();

                  $priceV[$cart->cid] = [$variations->variation_price, $variations->variation_special_price,
                                         $variations->variation_drop_shipper_price, $variations->variation_drop_shipper_special_price];

                  if(!empty($cart->promo)){
                    $variations = PromoAgentItemDetail::where('variation_id', $cart->c_sub_category_id)
                                                      ->where('status', '1')
                                                      ->first();

                    $priceV[$cart->cid] = [$variations->price, $variations->special_price];

                    $customer_variation_price_range = PromoAgentItemDetail::where('variation_id', $cart->c_sub_category_id)
                                                                      ->whereNull('agent_lvl_id')
                                                                      ->where('promo_item_id', $cart->promo)
                                                                      ->where('status', '1')
                                                                      ->first();

                    $customer_price_V[$cart->cid] = [$customer_variation_price_range->price, $customer_variation_price_range->special_price];
                  }
              }else{
                  if(!empty($cart->promo)){
                    $customer_non_variation_price_range = PromoAgentItemDetail::where('promo_item_id', $cart->promo)
                                                                        ->whereNull('agent_lvl_id')
                                                                        ->where('status', '1')
                                                                        ->first();

                      $customer_price_non_V[$cart->cid] = [$customer_non_variation_price_range->price, $customer_non_variation_price_range->special_price];
                  }
              }
            }

            if($cart->variation_enable == '1'){
              if(!empty($cart->variation_weight)){
                  $weight += $cart->variation_weight * $cart->qty;
              }
              if($cart->second_variation_enable == 1){
                  $weight += $cart->second_variation_weight * $cart->qty; 
              }
            }else{
              $weight += $cart->weight * $cart->qty;
            }

            $listingImages[$cart->id] = ProductImage::where('product_id', $cart->id)->orderBy('sort_level', 'asc')->first();

        }

      

        // exit();
        $ownShippingAddress = UserShippingAddress::select('user_shipping_addresses.*', 's.name as state_name')
                                                 ->join('states as s', 's.id', 'user_shipping_addresses.state')
                                                 ->where('user_id', $buyerCode)
                                                 ->get();

        $shipping_address = UserShippingAddress::select('user_shipping_addresses.*', 'name')
                                               ->join('states AS s', 's.id', 'user_shipping_addresses.state')
                                               ->where('user_id', $buyerCode)
                                               ->where('default', '1')
                                               ->first();
        $totalshipping_fees = 0;

        if(!empty($shipping_address)){
            if($shipping_address->state > 16){
              $shipping_fees = SettingShippingFee::where('area', 'sg')
                                                 ->where('weight', '<=', ceil($weight))
                                                 ->orderBy('weight', 'desc')
                                                 ->first();
              if(!empty($shipping_fees->id)){
                $totalshipping_fees = $shipping_fees->shipping_fee;                
              }
            }elseif($shipping_address->state != '11' && $shipping_address->state != '12' && $shipping_address->state != '15'){
              
              $shipping_fees = SettingShippingFee::where('area', 'west')
                                                 ->where('weight', '<=', ceil($weight))
                                                 ->orderBy('weight', 'desc')
                                                 ->first();
              if(!empty($shipping_fees->id)){
                $totalshipping_fees = $shipping_fees->shipping_fee;                
                
              }

            }else{
              $shipping_fees = SettingShippingFee::where('area', 'east')
                                                 ->where('weight', '<=', ceil($weight))
                                                 ->orderBy('weight', 'desc')
                                                 ->first();
              if(!empty($shipping_fees->id)){
                $totalshipping_fees = $shipping_fees->shipping_fee;                
              }
            }
        }

        $totalshipping_fees = ($totalshipping_fees > 0) ? $totalshipping_fees : 0;

        $totalBalance = $this->GetProductWalletBalance();

        $banks = Bank::orderBy('id', 'asc')->get();

        $checkAppliedPromo = AppliedPromotion::select('applied_promotions.*', 'p.start_date', 'p.end_date', 'p.discount_code', 'p.amount_type', 'p.amount','p.products')
                                             ->join('promotions AS p', 'p.id', 'applied_promotions.promotion_id')
                                             ->where('applied_promotions.user_id', $buyerCode)
                                             ->where('applied_promotions.status', '1')
                                             ->where('p.status', '1')
                                             ->first();
        // return 123;
        $applied_promo = [];
        if(!empty($checkAppliedPromo->id)){
            if(date('Y-m-d H:i:s') > $checkAppliedPromo->end_date){
                $checkAppliedPromo = "";
            }
            
            if (!empty($checkAppliedPromo->products)) {
                $check_product = Cart::whereIn('product_id',explode(',',$checkAppliedPromo->products))
                ->where('user_id',$buyerCode)
                ->get();
              if (!$check_product->isEmpty()) {
                  foreach($carts as $cart){

                  $applied_promo[$cart->id] = Promotion::whereRaw('FIND_IN_SET(?,products)',[$cart->id])
                                          ->where('id',$checkAppliedPromo->promotion_id)->first();
                  }
            
              }else{
                AppliedPromotion::find($checkAppliedPromo->id)->delete();
                $checkAppliedPromo = "";
              }
            }
        }
        // print_r($applied_promo);
        // exit();
     
        $getClaimedPromos = AppliedPromotion::select('applied_promotions.*', 'p.start_date', 'p.end_date', 'p.discount_code', 'p.amount_type', 'p.amount', 'p.image', 'p.promotion_title', 
                                                     'applied_promotions.id as apid')
                                            ->join('promotions AS p', 'p.id', 'applied_promotions.promotion_id')
                                            ->where('applied_promotions.user_id', $buyerCode)
                                            ->where('applied_promotions.status', '99')
                                            ->where('p.status', '1')
                                            ->get();
        $agentDiscount = 0;
        $agentDiscountType = "";
        if(Auth::guard('merchant')->check()){
            $settings = SettingAgentDiscount::find(1);
            if(!empty($settings->id)){
                $agentDiscount = $settings->amount;
                $agentDiscountType = $settings->type;
            }
        }

        // $countries = TblCountry::orderBy('country_name', 'asc')->get();
        $countries = GlobalController::global_countries();

        $CodAddresses = CodAddress::where('address', '!=', '')->get();

        $cashWalletBalance = $this->GetCashWalletBalance();

        $productWalletBalance = $this->GetProductWalletBalance();

        $totalProductBalance = $this->GetCashWalletBalance();

        return view('frontend.checkout', ['carts'=>$carts, 'states'=>$states, 'shipping_address'=>$shipping_address, 
                                          'totalBalance'=>$totalBalance, 'banks'=>$banks, 'totalshipping_fees'=>$totalshipping_fees, 'cashWalletBalance'=>$cashWalletBalance,
                                          'checkAppliedPromo'=>$checkAppliedPromo, 'agentDiscount'=>$agentDiscount, 
                                          'agentDiscountType'=>$agentDiscountType,'getClaimedPromos'=>$getClaimedPromos,
                                          'countries'=>$countries, 'ownShippingAddress'=>$ownShippingAddress,
                                          'CodAddresses'=>$CodAddresses, 'productWalletBalance'=>$productWalletBalance, 'totalProductBalance'=>$totalProductBalance], compact('pricing', 'priceV', 'pai', 'listingImages', 'applied_promo' ,'customer_price_V', 'customer_price_non_V'));
    }

    public function Mall()
    {
        if(!empty(Auth::guard('admin')->check())){
            $buyerCode = Auth::guard('admin')->user()->code;
            $buyerLvl = Auth::guard('admin')->user()->lvl;
        }elseif(!empty(Auth::guard('merchant')->check())){
            $buyerCode = Auth::guard('merchant')->user()->code;
            $buyerLvl = Auth::guard('merchant')->user()->lvl;
        }elseif(!empty(Auth::guard('web')->check())){
            $buyerCode = Auth::guard('web')->user()->code;
            $buyerLvl = Auth::guard('web')->user()->lvl;
        }else{
            $buyerCode = "";
            $buyerLvl = "";
        }

        $leftJoin = DB::raw("(SELECT * FROM product_images ORDER BY sort_level ASC) AS i");
        $carts = Cart::select('carts.id AS cid', 'carts.promo_price_id', 'carts.is_free', 'carts.promo', 'p.*', 'i.image', 'carts.qty', DB::raw('COALESCE(customer_special_price, customer_price) AS actual_price'),
                              DB::raw('COALESCE(agent_special_price, agent_price) AS agent_actual_price'),
                              'scl.variation_name', 'scl.variation_price', 'scl.variation_special_price', 'scl.variation_agent_price', 'scl.variation_agent_special_price', 'scl.variation_weight',
                              'p.weight', 'p.customer_special_price', 'p.customer_price', 'p.agent_special_price', 
                              'p.agent_price', 'variation_enable',
                              'p.second_variation_enable',
                              'sscl.variation_name as second_variation_name',
                              'sscl.variation_price as second_variation_price', 
                              'sscl.variation_special_price as second_variation_special_price', 
                              'sscl.variation_agent_price as second_variation_agent_price', 
                              'sscl.variation_agent_special_price as second_variation_agent_special_price', 
                              'sscl.variation_weight as second_variation_weight',
                              'carts.sub_category_id as c_sub_category_id',
                              'carts.second_sub_category_id as c_second_sub_category_id')
                     ->join('products AS p', 'p.id', 'carts.product_id')
                     ->leftJoin('product_variations AS scl', 'scl.id', 'carts.sub_category_id')
                     ->leftJoin('product_second_variations AS sscl', 'sscl.id', 'carts.second_sub_category_id')
                     ->leftJoin($leftJoin, function($join) {
                        $join->on('p.id', '=', 'i.product_id');
                     })
                     ->where('carts.status', '1')
                     ->where('carts.user_id', $buyerCode)
                     ->where('carts.mall', '1')
                     ->groupBy('carts.id');
        $carts = $carts->get();
        $states = State::get();

        if($carts->isEmpty()){
          Toastr::info("Cart is empty");
          return redirect()->route('home');
        }

        $weight = 0;
        $pricing = [];
        $priceV = [];
        $pai = [];
        $listingImages = [];
        $customer_price_V = [];
        $customer_price_non_V = [];
        
        foreach($carts as $cart){
            if(!empty($buyerLvl)){
                $variations = AgentPrice::where('agent_lvl_id', $buyerLvl);

                if(!empty($cart->promo)){
                  $variations = PromoAgentItemDetail::where('agent_lvl_id', $buyerLvl)
                                                    ->where('promo_item_id', $cart->promo);
                }

                if($cart->variation_enable == 1 && $cart->second_variation_enable == 1){
                $variations = $variations->where('second_variation_id', $cart->c_second_sub_category_id);
                }elseif($cart->variation_enable == 1 && empty($cart->second_variation_enable)){
                $variations = $variations->where('variation_id', $cart->c_sub_category_id);
                }else{
                    if(!empty($cart->promo)){
                        $variations = $variations->where('promo_item_id', $cart->promo);
                    }else{
                        $variations = $variations->where('product_id', $cart->id);
                    }
                }
                $variations = $variations->first();

                if(!empty($variations->special_price)){
                    $pricing[$cart->cid] = $variations->special_price;
                }else{
                    $pricing[$cart->cid] = $variations->price;
                }

                if(!empty($cart->promo_price_id)){
                  $pap = PromoAgentPrice::where('id', $cart->promo_price_id)->first();

                  $pricing[$cart->cid] = $pap->price;
                }
            }
            $pai[$cart->cid] = PromoAgentItem::find($cart->promo);

            if($cart->second_variation_enable == 1){
              $variations = ProductSecondVariation::where('id', $cart->c_second_sub_category_id)
                                                  ->where('variation_name', '!=', '')
                                                  ->first();

              $priceV[$cart->cid] = [$variations->variation_price, $variations->variation_special_price];

              if(!empty($cart->promo)){
                $variations = PromoAgentItemDetail::where('second_variation_id', $cart->c_second_sub_category_id)
                                                  ->where('status', '1')
                                                  ->first();

                $priceV[$cart->cid] = [$variations->price, $variations->special_price];

                $customer_variation_price_range = PromoAgentItemDetail::where('second_variation_id', $cart->c_second_sub_category_id)
                                                                      ->whereNull('agent_lvl_id')
                                                                      ->where('promo_item_id', $cart->promo)
                                                                      ->where('status', '1')
                                                                      ->first();

                $customer_price_V[$cart->cid] = [$customer_variation_price_range->price, $customer_variation_price_range->special_price];
              }
            }else{
              if($cart->variation_enable == 1){
                  $variations = ProductVariation::where('id', $cart->c_sub_category_id)
                                                ->where('variation_name', '!=', '')
                                                ->first();

                  $priceV[$cart->cid] = [$variations->variation_price, $variations->variation_special_price];

                  if(!empty($cart->promo)){
                    $variations = PromoAgentItemDetail::where('variation_id', $cart->c_sub_category_id)
                                                      ->where('status', '1')
                                                      ->first();

                    $priceV[$cart->cid] = [$variations->price, $variations->special_price];

                    $customer_variation_price_range = PromoAgentItemDetail::where('variation_id', $cart->c_sub_category_id)
                                                                      ->whereNull('agent_lvl_id')
                                                                      ->where('promo_item_id', $cart->promo)
                                                                      ->where('status', '1')
                                                                      ->first();

                    $customer_price_V[$cart->cid] = [$customer_variation_price_range->price, $customer_variation_price_range->special_price];
                  }
              }else{
                  if(!empty($cart->promo)){
                    $customer_non_variation_price_range = PromoAgentItemDetail::where('promo_item_id', $cart->promo)
                                                                        ->whereNull('agent_lvl_id')
                                                                        ->where('status', '1')
                                                                        ->first();

                      $customer_price_non_V[$cart->cid] = [$customer_non_variation_price_range->price, $customer_non_variation_price_range->special_price];
                  }
              }
            }

            if($cart->variation_enable == '1'){
              if(!empty($cart->variation_weight)){
                  $weight += $cart->variation_weight * $cart->qty;
              }
              if($cart->second_variation_enable == 1){
                  $weight += $cart->second_variation_weight * $cart->qty; 
              }
            }else{
              $weight += $cart->weight * $cart->qty;
            }

            $listingImages[$cart->id] = ProductImage::where('product_id', $cart->id)->orderBy('sort_level', 'asc')->first();

        }
        // exit();
        $ownShippingAddress = UserShippingAddress::select('user_shipping_addresses.*', 's.name as state_name')
                                                 ->join('states as s', 's.id', 'user_shipping_addresses.state')
                                                 ->where('user_id', $buyerCode)
                                                 ->get();

        $shipping_address = UserShippingAddress::select('user_shipping_addresses.*', 'name')
                                               ->join('states AS s', 's.id', 'user_shipping_addresses.state')
                                               ->where('user_id', $buyerCode)
                                               ->where('default', '1')
                                               ->first();
        $totalshipping_fees = 0;

        if(!empty($shipping_address)){
            if($shipping_address->state > 16){
              $shipping_fees = SettingShippingFee::where('area', 'sg')
                                                 ->where('weight', '<=', ceil($weight))
                                                 ->orderBy('weight', 'desc')
                                                 ->first();
              if(!empty($shipping_fees->id)){
                $totalshipping_fees = $shipping_fees->shipping_fee;                
              }
            }elseif($shipping_address->state != '11' && $shipping_address->state != '12' && $shipping_address->state != '15'){
              
              $shipping_fees = SettingShippingFee::where('area', 'west')
                                                 ->where('weight', '<=', ceil($weight))
                                                 ->orderBy('weight', 'desc')
                                                 ->first();
              if(!empty($shipping_fees->id)){
                $totalshipping_fees = $shipping_fees->shipping_fee;                
                
              }

            }else{
              $shipping_fees = SettingShippingFee::where('area', 'east')
                                                 ->where('weight', '<=', ceil($weight))
                                                 ->orderBy('weight', 'desc')
                                                 ->first();
              if(!empty($shipping_fees->id)){
                $totalshipping_fees = $shipping_fees->shipping_fee;                
              }
            }
        }
        // echo $weight;
        $totalshipping_fees = 0;
        // exit();

        
        

        $totalBalance = $this->GetProductWalletBalance();

        $banks = Bank::orderBy('id', 'asc')->get();

        // $checkAppliedPromo = AppliedPromotion::select('applied_promotions.*', 'p.start_date', 'p.end_date', 'p.discount_code', 'p.amount_type', 'p.amount')
        //                                      ->join('promotions AS p', 'p.id', 'applied_promotions.promotion_id')
        //                                      ->where('applied_promotions.user_id', $buyerCode)
        //                                      ->where('applied_promotions.status', '1')
        //                                      ->where('p.status', '1')
        //                                      ->first();
        // // return 123;
        // if(!empty($checkAppliedPromo->id)){
        //     if(date('Y-m-d H:i:s') > $checkAppliedPromo->end_date){
        //     }
        // }
        $checkAppliedPromo = "";

        $getClaimedPromos = AppliedPromotion::select('applied_promotions.*', 'p.start_date', 'p.end_date', 'p.discount_code', 'p.amount_type', 'p.amount', 'p.image', 'p.promotion_title', 
                                                     'applied_promotions.id as apid')
                                            ->join('promotions AS p', 'p.id', 'applied_promotions.promotion_id')
                                            ->where('applied_promotions.user_id', $buyerCode)
                                            ->where('applied_promotions.status', '99')
                                            ->where('p.status', '1')
                                            ->get();
        $agentDiscount = 0;
        $agentDiscountType = "";
        if(Auth::guard('merchant')->check()){
            $settings = SettingAgentDiscount::find(1);
            if(!empty($settings->id)){
                $agentDiscount = $settings->amount;
                $agentDiscountType = $settings->type;
            }
        }

        $countries = TblCountry::orderBy('country_name', 'asc')
                              ->get();

        $CodAddresses = CodAddress::where('address', '!=', '')->get();

        $cashWalletBalance = $this->GetProductWalletBalance();

        $productWalletBalance = $this->GetCashWalletBalance();

        $totalProductBalance = $this->GetCashWalletBalance();

        $PointWallet = $this->GetPointWalletBalance();

        return view('frontend.checkout_mall', ['carts'=>$carts, 'states'=>$states, 'shipping_address'=>$shipping_address, 
                                          'totalBalance'=>$totalBalance, 'banks'=>$banks, 'totalshipping_fees'=>$totalshipping_fees, 'cashWalletBalance'=>$cashWalletBalance,
                                          'checkAppliedPromo'=>$checkAppliedPromo, 'agentDiscount'=>$agentDiscount, 
                                          'agentDiscountType'=>$agentDiscountType,'getClaimedPromos'=>$getClaimedPromos,
                                          'countries'=>$countries, 'ownShippingAddress'=>$ownShippingAddress,
                                          'CodAddresses'=>$CodAddresses, 'productWalletBalance'=>$productWalletBalance, 'totalProductBalance'=>$totalProductBalance,
                                          'PointWallet'=>$PointWallet], compact('pricing', 'priceV', 'pai', 'listingImages', 'customer_price_V', 'customer_price_non_V'));
    }


    public function placeOrder(Request $request){
        if(!empty(Auth::guard('admin')->check())){
            $buyerCode = Auth::guard('admin')->user()->code;
            $buyerLvl = Auth::guard('admin')->user()->lvl;
        }elseif(!empty(Auth::guard('merchant')->check())){
            $buyerCode = Auth::guard('merchant')->user()->code;
            $buyerLvl = Auth::guard('merchant')->user()->lvl;
        }elseif(!empty(Auth::guard('web')->check())){
            $buyerCode = Auth::guard('web')->user()->code;
            $buyerLvl = Auth::guard('web')->user()->lvl;
        }elseif(!empty(Auth::guard('dropshipper')->check())){
            $buyerCode = Auth::guard('dropshipper')->user()->code;
            $buyerLvl = Auth::guard('dropshipper')->user()->lvl;
        }else{
          if(empty($_COOKIE['new_guest'])){
            $buyerCode = setcookie('new_guest', strtotime(date('Y-m-d H:i:s')).rand(100000, 999999), time() + (86400 * 30), "/");
          }else{
            $buyerCode = $_COOKIE['new_guest'];
          }
          $buyerLvl = "";
        }

        $website_setting = WebsiteSetting::find(1);

        if(!isset($request->customer_address)){
          if(empty($request->billing_details_im)){
              $validator = Validator::make($request->all(), [
                  'f_name' => 'required',
                  'l_name' => 'required',
                  'email' => 'required',
                  'phone' => 'required',
                  'address' => 'required',
                  'city' => 'required',
                  'postcode' => 'required',
              ]);

              if ($validator->fails()) {
                  return Redirect::back()->withInput($request->all())->withErrors($validator);
              }

              $input = $request->all();
              $input['user_id'] = $buyerCode;
              $input['default'] = '1';
              $input['state'] = $request->state;
              $create_shipping_address = UserShippingAddress::create($input);
          }

          $shipping_address = UserShippingAddress::where('user_id', $buyerCode)
                                                 ->where('default', '1')
                                                 ->first();          
        }else{
          $validator = Validator::make($request->all(), [
                  'c_f_name' => 'required',
                  'c_l_name' => 'required',
                  'c_address' => 'required',
                  'c_postcode' => 'required',
                  'c_city' => 'required',
                  'c_state' => 'required',
                  'c_phone' => 'required',
              ]);

              if ($validator->fails()) {
                  return Redirect::back()->withInput($request->all())->withErrors('Please fill in customer shipping address');
              }
        }

        // $same_bill_address = (!empty($request->same_billing_address) && $request->same_billing_address != '') ? 1 : 0;

        // if($same_bill_address == 1){

        // }else{
        //   $validator = Validator::make($request->all(), [
        //           'f_name_bill' => 'required',
        //           'l_name_bill' => 'required',
        //           'email_bill' => 'required',
        //           'phone_bill' => 'required',
        //           'address_bill' => 'required',
        //           'city_bill' => 'required',
        //           'state_bill' => 'required',
        //           'postcode_bill' => 'required',
        //       ]);

        //       if ($validator->fails()) {
        //           return Redirect::back()->withInput($request->all())->withErrors("Please ensure every details in billing address is filled correctly.");
        //       }
        // }

        $selected_cart = [];
        foreach($request->selected_cart as $key => $value){
            $selected_cart[] = [$value];
        }

        $leftJoin = DB::raw("(SELECT * FROM product_images ORDER BY created_at ASC) AS i");
        $carts = Cart::select('carts.*', 'p.product_name', 'weight', 
                              'i.image', 'p.item_code', 'p.product_code', 
                              'scl.variation_name', 'scl.variation_price', 'scl.variation_special_price', 'scl.variation_agent_price', 
                              'scl.variation_agent_special_price', 'scl.variation_weight', 
                              'p.product_comm_type', 'p.product_comm_amount', 'own_product_comm_type', 'own_product_comm_amount', 
                              'in_product_comm_type', 'in_product_comm_amount', 'p.variation_enable', 'scl.id as vid',
                              'p.second_variation_enable', 'costing_price as p_costing_price',
                              'sscl.id as svid', 
                              'sscl.variation_name as second_variation_name',
                              'sscl.variation_price as second_variation_price', 
                              'sscl.variation_special_price as second_variation_special_price', 
                              'sscl.variation_agent_price as second_variation_agent_price', 
                              'sscl.variation_agent_special_price as second_variation_agent_special_price', 
                              'sscl.variation_weight as second_variation_weight',
                              'agent_get_point',
                              'p.upgrade_agent',
                              'p.customer_special_price',
                              'p.customer_price',
                              'p.get_pv',
                              'p.drop_shipper_special_price',
                              'p.drop_shipper_price',
                              'scl.variation_get_pv',
                              'sscl.variation_get_pv as second_variation_get_pv')
                     ->join('products AS p', 'p.id', 'carts.product_id')
                     ->leftJoin('product_variations AS scl', 'scl.id', 'carts.sub_category_id')
                     ->leftJoin('product_second_variations AS sscl', 'sscl.id', 'carts.second_sub_category_id')
                     ->leftJoin($leftJoin, function($join) {
                        $join->on('p.id', '=', 'i.product_id');
                     })
                     ->where('carts.status', '1')
                     ->where('carts.user_id', $buyerCode)
                     ->whereNull('carts.mall')
                     ->whereIn(DB::raw("md5(carts.id)"), $selected_cart)
                     ->groupBy('carts.id')
                     ->get();
        $totalAmount = 0;
        $totalWeight = 0;
        $totalPFee = 0;
        $paid = "";

        foreach ($carts as $cart) {
          $product = Product::find($cart->product_id);
          if($product->variation_enable == '1'){
              if($product->second_variation_enable == '1'){
                $BalanceQty = $this->SecondVariationBalanceQuantity($cart->second_sub_category_id);
              }else{
                $BalanceQty = $this->VariationBalanceQuantity($cart->sub_category_id);
              }
          }else{
            // if($product->packages == 1){
              $BalanceQty = "1000000000";
            // }else{
              $BalanceQty = $this->BalanceQuantity($cart->product_id);
            // }
          }

          if($BalanceQty < $request->quantity){
            Toastr::info("Quantity ". $request->quantity ." Exceed ". $BalanceQty ." Error for product: ".$product->product_name);
            return Redirect::back();
          }
        }

        foreach($carts as $cart){
          if(!empty($cart->promo)){
              $pai = PromoAgentItem::find($cart->promo);
              if(Auth::guard('merchant')->check() || Auth::guard('admin')->check()){


                if(!empty($cart->second_sub_category_id) && $cart->second_sub_category_id != 'undefined'){
                    $paid = PromoAgentItemDetail::where('second_variation_id', $cart->second_sub_category_id)
                                                ->where('agent_lvl_id', $buyerLvl)
                                                ->where('promo_item_id', $cart->promo)
                                                ->where('status', '1')
                                                ->first();
                }else{
                  if(!empty($cart->sub_category_id) && $cart->sub_category_id != 'undefined'){
                    $paid = PromoAgentItemDetail::where('variation_id', $cart->sub_category_id)
                                                ->where('agent_lvl_id', $buyerLvl)
                                                ->where('promo_item_id', $cart->promo)
                                                ->where('status', '1')
                                                ->first();
                  }else{
                    $paid = PromoAgentItemDetail::where('agent_lvl_id', $buyerLvl)
                                                ->where('promo_item_id', $cart->promo)
                                                ->where('status', '1')
                                                ->first();
                  }
                }

                if(!empty($paid->special_price)){
                    $totalAmount += $paid->special_price * $cart->qty;
                }else{
                    $totalAmount += $paid->price * $cart->qty;
                }
            }else{
                if(!empty($cart->second_sub_category_id) && $cart->second_sub_category_id != 'undefined'){
                    $paid = PromoAgentItemDetail::where('second_variation_id', $cart->second_sub_category_id)
                                                ->where('agent_lvl_id', $buyerLvl)
                                                ->where('promo_item_id', $cart->promo)
                                                ->where('status', '1')
                                                ->first();
                }else{
                  if(!empty($cart->sub_category_id)  && $cart->sub_category_id != 'undefined'){
                    $paid = PromoAgentItemDetail::where('variation_id', $cart->sub_category_id)
                                                ->where('agent_lvl_id', $buyerLvl)
                                                ->where('promo_item_id', $cart->promo)
                                                ->where('status', '1')
                                                ->first();
                  }else{
                    $paid = PromoAgentItemDetail::where('agent_lvl_id', $buyerLvl)
                                                ->where('promo_item_id', $cart->promo)
                                                ->where('status', '1')
                                                ->first();
                  }
                }

                if(!empty($paid->special_price)){
                    $totalAmount += $paid->special_price * $cart->qty;
                }else{
                    $totalAmount += $paid->price * $cart->qty;
                }
            }
          }else{
              if(Auth::guard('merchant')->check() || Auth::guard('admin')->check()){
                  if(!empty($buyerLvl)){
                      if($cart->is_free == 1){
                          $totalAmount += 0;
                      }else{
                          $variations = AgentPrice::where('agent_lvl_id', $buyerLvl);
                          if($cart->variation_enable == 1 && $cart->second_variation_enable == 1){
                              $variations = $variations->where('second_variation_id', $cart->svid);
                          }elseif($cart->variation_enable == 1 && empty($cart->second_variation_enable)){
                              $variations = $variations->where('variation_id', $cart->vid);
                          }else{
                             $variations = $variations->where('product_id', $cart->product_id);
                          }
                          $variations = $variations->first();
                          if(!empty($variations->special_price)){
                              $totalAmount += $variations->special_price * $cart->qty;
                          }else{
                              $totalAmount += $variations->price * $cart->qty;
                          }
                      }
                  }
              }elseif(Auth::guard('dropshipper')->check()){
                      if($cart->variation_enable == '1'){
                        if($cart->second_variation_enable == 1){
                            $variations = ProductSecondVariation::find($cart->svid);
                            if(!empty($variations->variation_drop_shipper_special_price)){
                                $totalAmount += $variations->variation_drop_shipper_special_price  * $cart->qty;
                            }else{
                                $totalAmount += $variations->variation_drop_shipper_price  * $cart->qty;
                            }
                        }else{
                            $variations = ProductVariation::find($cart->vid);

                            if(!empty($variations->variation_drop_shipper_special_price)){
                                $totalAmount += $variations->variation_drop_shipper_special_price  * $cart->qty;
                            }else{
                                $totalAmount += $variations->variation_drop_shipper_price  * $cart->qty;
                            }
                        }
                      }else{
                            if(!empty($cart->drop_shipper_special_price)){
                                $totalAmount += $cart->drop_shipper_special_price  * $cart->qty;
                            }else{
                                $totalAmount += $cart->drop_shipper_price  * $cart->qty;
                            }
                      }
              }else{
                  if($cart->is_free == 1){
                      $totalAmount += 0;
                  }else{
                      if($cart->variation_enable == '1'){
                        if($cart->second_variation_enable == 1){
                            $variations = ProductSecondVariation::find($cart->svid);
                            if(!empty($variations->variation_special_price)){
                                $totalAmount += $variations->variation_special_price  * $cart->qty;
                                
                            }else{
                                $totalAmount += $variations->variation_price  * $cart->qty;
                            }
                        }else{
                            $variations = ProductVariation::find($cart->vid);

                            if(!empty($variations->variation_special_price)){
                                $totalAmount += $variations->variation_special_price  * $cart->qty;
                            }else{
                                $totalAmount += $variations->variation_price  * $cart->qty;
                            }
                        }
                      }else{
                          if(!empty($cart->customer_special_price)){
                              $totalAmount += $cart->customer_special_price  * $cart->qty;
                          }else{
                              $totalAmount += $cart->customer_price  * $cart->qty;
                          }
                      }
                  }
              }
          }
          
          if($cart->variation_enable == '1'){
            if($cart->second_variation_enable == '1'){
              $totalWeight += $cart->second_variation_weight * $cart->qty;
            }else{
              $totalWeight += $cart->variation_weight * $cart->qty;
            }
          }else{
            $totalWeight += $cart->weight * $cart->qty;
          }
          // $totalAmount += $cart->totalSum;
        }
        // echo $totalAmount;
        // exit();
        if($carts->isEmpty()){
          Toastr::info("Cart is empty, please re-order/re-payment.");
          return redirect()->route('home');
        }

        $cod = (!empty($request->cod) && $request->cod != '') ? 1 : 0;

        if($cod == 1){
          $totalAmount = $totalAmount;
        }else{
          $totalAmount = $totalAmount + $request->hidden_shipping_amount;
        }

        $input = $request->all();
        if(!empty($request->hidden_discount) && $request->hidden_discount != '0'){
          $totalAmount = $totalAmount - $request->hidden_discount;
          $updateAppliedDiscount = AppliedPromotion::where('status', '1')
                                                   ->where('promotion_id', $request->discount_code)
                                                   ->where('user_id', $buyerCode)
                                                   ->update(['status'=>'2']);

          $input['discount'] = $request->hidden_discount;
          $input['discount_code'] = $request->discount_code;
        }elseif(!empty($request->discount_code) && $request->discount_code != '0'){
          $updateAppliedPromo = AppliedPromotion::where('status', '1')
                                                   ->where('promotion_id', $request->discount_code)
                                                   ->where('user_id', $buyerCode)
                                                   ->update(['status'=>'2']);
        }
        
        if(Auth::guard('merchant')->check() && !empty($request->hidden_ad_discount) && $request->hidden_ad_discount != '0'){
          
          $totalAmount = $totalAmount - $request->hidden_ad_discount;
          $input['ad_discount'] = $request->hidden_ad_discount;
        }

        if(empty($request->mall) && $request->mall != '1' && $request->cdm != '1' && $request->wallet != '1'){
          // $totalPFee = $totalAmount * 1.6 / 100;
          // $totalAmount = $totalAmount + ($totalAmount * 1.6 / 100);          
        }

        if($totalAmount <= 0){
            $totalAmount = $request->hidden_shipping_amount;
        }

        // return $totalAmount;
        

        $guest_agent = "";
        $guest_agent_type = "";
        
        if(!empty(Session::get('guest_agent'))){
          $m = Merchant::where('code', Session::get('guest_agent'))->first();
          if(!empty($m->id)){
            $guest_agent = $m->code;
            $guest_agent_type = $m->agent_type;
          }
          $input['guest_agent'] = $guest_agent;
        }
        $input['weight'] = $totalWeight;
        $input['transaction_no'] = $this->GenerateTransactionNo();
        $input['sub_total'] = $request->sub_total;
        $input['shipping_fee'] = !empty($request->hidden_shipping_amount) ? $request->hidden_shipping_amount : 0;
        $input['grand_total'] = number_format($totalAmount, 2, '.', '');
        $input['rm_to_point'] = $website_setting->point_to_rm;
        $input['user_id'] = $buyerCode;
        $input['address_name'] = $shipping_address->f_name.' '.$shipping_address->l_name;
        $input['address'] = $shipping_address->address;
        $input['country_code'] = $shipping_address->country_code;
        $input['postcode'] = $shipping_address->postcode;
        $input['city'] = $shipping_address->city;
        $input['state'] = $shipping_address->state;
        $input['phone'] = $shipping_address->phone;
        $input['email'] = $shipping_address->email;
        $input['cod_address'] = "";
        $input['mall'] = "";
        // if($same_bill_address != 1){
        //   $input['different_billing_address'] = '1';
        // }
        

        if(empty($request->mall) && $request->mall != '1'){
          if($request->cdm == 1){
              $files = $request->file('bank_slip'); 
              $name = $files->getClientOriginalName();
              $exp = explode(".", $name);
              $file_ext = end($exp);
              $name = md5($name.date('Y-m-d H:i:s')).'.'.$file_ext;
              $files->move(GlobalController::get_image_path("uploads/bank_slip/".$buyerCode."/"), $name);

              $input['cdm_bank_id'] = $request->cdm_bank_id;
              $input['bank_slip'] = "uploads/bank_slip/".$buyerCode."/".$name;
              $input['status'] = '98';
          }elseif($request->topup_wallet == 1){
              $input['mall'] = '1';
              $input['status'] = '1';
          }elseif($request->cash_wallet == 1){
              $input['mall'] = '2';
              $input['status'] = '1';
          }else{
              $input['processing_fee'] = !empty($totalPFee) ? number_format($totalPFee, 2) : 0;
              $input['bank_id'] = $request->bank_id;
              $input['status'] = '99';
          }
        }else{
          $input['status'] = '1';
        }

        if($cod == 1){
            $input['processing_fee'] = "0";
            $input['shipping_fee'] = 0;
            $input['cod_address'] = $request->cod_address;
        }

        $transaction = Transaction::create($input);

        // if($same_bill_address != 1){
        //     $bill_input = $request->all();
        //     $bill_input['transaction_id'] = $transaction->id;
        //     $bill_input['address_name'] = $request->f_name_bill.' '.$request->l_name_bill;
        //     $bill_input['address'] = $request->address_bill;
        //     $bill_input['postcode'] = $request->postcode_bill;
        //     $bill_input['city'] = $request->city_bill;
        //     $bill_input['state'] = $request->state_bill;
        //     $bill_input['country_code'] = $request->country_code_bill;
        //     $bill_input['phone'] = $request->phone_bill;
        //     $bill_input['email'] = $request->email_bill;

        //     $bill_address = TransactionBillingAddress::create($bill_input);
        // }

        $items = [];
        $own_product_comm_type = "";
        $own_product_comm_amount = 0;
        $get_point = 0;
        $actual_weight = 0;
        $total_get_pv = 0;
        foreach($carts as $cart){
          
          if(Auth::guard('merchant')->check() || Auth::guard('admin')->check()){
              if(!empty($buyerLvl)){
                  $variations = AgentPrice::where('agent_lvl_id', $buyerLvl);
                  if($cart->variation_enable == 1 && $cart->second_variation_enable == 1){
                  $variations = $variations->where('second_variation_id', $cart->svid);
                  }elseif($cart->variation_enable == 1 && empty($cart->second_variation_enable)){
                  $variations = $variations->where('variation_id', $cart->vid);
                  }else{
                  $variations = $variations->where('product_id', $cart->product_id);
                  }
                  $variations = $variations->first();
                  if(!empty($variations->special_price)){
                      $actual_price = $variations->special_price;
                  }else{
                      $actual_price = $variations->price;
                  }

                  if($cart->is_free == 1){
                    $actual_price = 0;
                  }
              }
          }elseif(Auth::guard('dropshipper')->check()){
                if($cart->variation_enable == '1'){
                    if($cart->second_variation_enable == 1){
                        $variations = ProductSecondVariation::find($cart->svid);
                        if(!empty($variations->variation_drop_shipper_special_price)){
                            $actual_price = $variations->variation_drop_shipper_special_price;
                        }else{
                            $actual_price = $variations->variation_drop_shipper_price;
                        }

                        if($cart->is_free == 1){
                          $actual_price = 0;
                        }
                        $total_get_pv = $cart->second_variation_get_pv;
                    }else{
                        $variations = ProductVariation::find($cart->vid);
                        if(!empty($variations->variation_drop_shipper_special_price)){
                            $actual_price = $variations->variation_drop_shipper_special_price;
                        }else{
                            $actual_price = $variations->variation_drop_shipper_price;
                        }
                    }
                  }else{
                      if(!empty($cart->drop_shipper_special_price)){
                        $actual_price = $cart->drop_shipper_special_price;
                      }else{
                        $actual_price = $cart->drop_shipper_price;
                      }

                      if($cart->is_free == 1){
                        $actual_price = 0;
                      }

                      $total_get_pv = $cart->get_pv;
                  }
          }else{
              if($cart->variation_enable == '1'){
                if($cart->second_variation_enable == 1){
                    $variations = ProductSecondVariation::find($cart->svid);
                    if(!empty($variations->variation_special_price)){
                        $actual_price = $variations->variation_special_price;
                    }else{
                        $actual_price = $variations->variation_price;
                    }

                    if($cart->is_free == 1){
                      $actual_price = 0;
                    }
                    $total_get_pv = $cart->second_variation_get_pv;
                }else{
                    $variations = ProductVariation::find($cart->vid);
                    if(!empty($variations->variation_special_price)){
                        $actual_price = $variations->variation_special_price;
                    }else{
                        $actual_price = $variations->variation_price;
                    }

                    if($cart->is_free == 1){
                      $actual_price = 0;
                    }

                    $total_get_pv = $cart->variation_get_pv;
                }
              }else{
                  if(!empty($cart->customer_special_price)){
                    $actual_price = $cart->customer_special_price;
                  }else{
                    $actual_price = $cart->customer_price;
                  }

                  if($cart->is_free == 1){
                    $actual_price = 0;
                  }

                  $total_get_pv = $cart->get_pv;
              }
          }

          if($cart->variation_enable == '1'){
            if($cart->second_variation_enable == '1'){
              $actual_weight = $cart->second_variation_weight;
            }else{
              $actual_weight = $cart->variation_weight;
            }
          }else{
            $actual_weight = $cart->weight;
          }

          
          $items[] = ['transaction_id'=>$transaction->id,
                      'product_id'=>$cart->product_id,
                      'item_code'=>$cart->item_code,
                      'product_code'=>$cart->product_code,
                      'unit_weight'=>$actual_weight,
                      'product_image'=>$cart->image,
                      'product_name'=>$cart->product_name,
                      'unit_price'=>$actual_price,
                      'upgrade_agent'=>$cart->upgrade_agent,
                      'quantity'=>$cart->qty,
                      'sub_category'=>$cart->variation_name,
                      'second_sub_category'=>$cart->second_variation_name,
                      'variation_id'=>$cart->vid,
                      'second_variation_id'=>$cart->svid,
                      'total_amount'=>$cart->totalSum,
                      'get_pv'=>$total_get_pv,
                      'status'=>'1',
                      'created_at'=>date('Y-m-d H:i:s'),
                      'updated_at'=>date('Y-m-d H:i:s')]; 
          
        }

        $t_detail = TransactionDetail::insert($items);

        $get_details = TransactionDetail::where('transaction_id', $transaction->id)->get();

        foreach($get_details as $get_detail){
            $PackageItems = PackageItem::where('product_id', $get_detail->product_id)
                                       ->get();
                                       
            foreach($PackageItems as $PackageItem){
                $input_transaction_packages = [];
                $input_transaction_packages['detail_id'] = $get_detail->id;
                $input_transaction_packages['product_id'] = $PackageItem->products;
                $input_transaction_packages['variation_id'] = $PackageItem->variation_id;
                $input_transaction_packages['second_variation_id'] = $PackageItem->second_variation_id;
                $input_transaction_packages['quantity']  = $PackageItem->qty;

                TransactionPackage::create($input_transaction_packages);                            
            }
        }

        $bank = Bank::find($request->bank_id);

        if($request->cash_wallet == 1){
            $isMerchant = Merchant::where('code', $transaction->user_id)
                                      ->where('status', '1')
                                      ->first();

            $isUser = User::where('code', $transaction->user_id)
                              ->where('status', '1')
                              ->first();

            if(!empty($isMerchant->code)){
                $user_details = $isMerchant;
            }

            if(!empty($isUser->code)){
                $user_details = $isUser;
            }

            $details = TransactionDetail::where('transaction_id', $transaction->id)->get();
            foreach($details as $detail){
                $get_upline = Merchant::where('code', $user_details->master_id)
                                      ->where('status', '1')
                                      ->first();
                if(!empty($get_upline->id)){

                    $get_upline_price = AgentPrice::where('product_id', $detail->product_id)
                                                  ->where('agent_lvl_id', $get_upline->lvl);
                    if(!empty($detail->second_variation_id)){
                    $get_upline_price = $get_upline_price->where('second_variation_id', $detail->second_variation_id);
                    }
                    if(!empty($detail->variation_id)){
                    $get_upline_price = $get_upline_price->where('variation_id', $detail->variation_id);
                    }
                    $get_upline_price = $get_upline_price->first();
                    if(!empty($get_upline_price->id)){
                        $upline_price = !empty($get_upline_price->special_price) ? $get_upline_price->special_price : $get_upline_price->price;

                        $upline_price = $upline_price * $detail->quantity;

                        $ori_price = $detail->unit_price * $detail->quantity;

                        $totalSpread = $ori_price - $upline_price;

                        if($totalSpread > 0 && $upline_price > 0){
                            if(!empty($detail->second_variation_id)){
                                $product_name = $detail->product_name.' - '.$detail->sub_category.' - '.$detail->second_sub_category;
                            }elseif(!empty($detail->variation_id)){
                                $product_name = $detail->product_name.' - '.$detail->sub_category;
                            }else{
                                $product_name = $detail->product_name;
                            }
                            $input_spread = [];
                            $input_spread['type'] = 1;
                            $input_spread['user_id'] = $get_upline->code;
                            $input_spread['user_by'] = $transaction->user_id;
                            $input_spread['transaction_no'] = $transaction->transaction_no;
                            $input_spread['product_name'] = $product_name;
                            $input_spread['product_qty'] = $detail->quantity;
                            $input_spread['product_amount'] = $ori_price;
                            $input_spread['comm_pa_type'] = "Amount";
                            $input_spread['comm_pa'] = $totalSpread;
                            $input_spread['comm_amount'] = $totalSpread;
                            $input_spread['comm_desc'] = "Downline Spread Bonus";

                            AffiliateCommission::create($input_spread);
                        }
                    }
                }
            }
            $this->AgentUpgrade();
        }

        $delete_cart = Cart::whereIn(DB::raw("md5(carts.id)"), $selected_cart)->delete();

        if((!empty($request->mall) && $request->mall == '1') || !empty($request->cdm) && $request->cdm == '1'){
            Toastr::success('Order Successfully');
            return \Redirect::route('pending_shipping');
        }elseif($request->topup_wallet == 1 || $request->cash_wallet == 1){
          Toastr::success('Your order has been placed successfully');
          return \Redirect::route('pending_shipping');
        }else{
          // $this->guestPlacedOrderMessage($shipping_address->phone, $transaction->transaction_no, $transaction->grand_total);
          // return \Redirect::route('PaymentProcess', array('transactions'=>md5($transaction->id), 'bank_code'=>$bank->bank_code));
            $timestamp = round(microtime(true) * 1000);
              //$nonce_string = md5(rand(1,99999999));
              $strsTime = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
              $nonce_string = substr(str_shuffle($strsTime), mt_rand(0, strlen($strsTime) - 11), 40);
              $client_id = '16413654247489118352';
              $client_secret = '8df12710-adde-5649-85a1-3f97e90b2871';
              $orderId = $transaction->transaction_no;
              $amount = str_replace(',', '', $transaction->grand_total);

              $payload = [
                  "title"=>"New Order #".$orderId,
                  "description"=>"New Order #".$orderId,
                  "currency"=>"MYR",
                  "amount"=>floatval(number_format($amount * 100, 2, '.', '')),
                  "redirectUrl" => "https://miekobeauty.com/PendingShipping/?oid=".$orderId,
                  "callbackUrl" => "https://miekobeauty.com/api/payment_successfully",
                  "orderReferenceNo" => $orderId,
              ];

              ksort($payload);
              $json_encoded_payload = json_encode($payload, JSON_UNESCAPED_SLASHES);
              
              $base64_json_encoded_payload = base64_encode($json_encoded_payload);
              
              $final_array = [
                  'data='.$base64_json_encoded_payload,
                  'timestamp='.$timestamp,
                  'nonce='.$nonce_string
              ];

              $final_string = implode("&", $final_array);
              $hash_data = hash_hmac('sha512', $final_string, $client_secret, true);
              $base64 = base64_encode($hash_data);
              
              $url = 'https://api.premierpay.com.my/premierpay/api/merchant/order/store/'.$client_id.'/online';
              
              // echo "===== Signing request begin =====<br>";
              // echo "[Before] => ". $final_string . "<br>";
              // echo "[After] => ". $base64 . "<br>";
              // echo "===== Signing request end =====<br><br><br>";

              // echo "===== Request begin =====<br>";
              // echo "[URL] => ". $url . "<br>";
              // echo "[Signature] => ". $base64 . "<br>";
              // echo "[Timestamp] => ". $timestamp . "<br>";
              // echo "[Nonce] => ". $nonce_string . "<br>";
              // echo "[Body] => ".print_r($json_encoded_payload,true)."<br>";
              // echo "===== Request end =====<br><br><br>";

              // echo "===== Response begin =====<br>";
              $ch = curl_init();
              curl_setopt($ch, CURLOPT_URL, $url);
              curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
              curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
              curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
              curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                  'Content-Type: application/json',
                  'x-signature: '.$base64,
                  'x-timestamp: '.$timestamp,
                  'x-nonce: '.$nonce_string
              )); 
              curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
              curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
              curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
              curl_setopt($ch, CURLOPT_POST, 1);
              curl_setopt($ch, CURLOPT_POSTFIELDS, $json_encoded_payload);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

              curl_setopt($ch, CURLOPT_CONNECTTIMEOUT , 60); // 在尝试连接时等待的秒数
              curl_setopt($ch, CURLOPT_TIMEOUT, 60); // 最大执行时间

              $result = curl_exec($ch);
              // echo $result . "<br>";
              // echo "===== Response end =====<br>";

              $json = json_decode($result);
              return Redirect::to($json->data);
        }
    }

    public function placeOrderMall(Request $request)
    {
        if(!empty(Auth::guard('admin')->check())){
            $buyerCode = Auth::guard('admin')->user()->code;
            $buyerLvl = Auth::guard('admin')->user()->lvl;
        }elseif(!empty(Auth::guard('merchant')->check())){
            $buyerCode = Auth::guard('merchant')->user()->code;
            $buyerLvl = Auth::guard('merchant')->user()->lvl;
        }elseif(!empty(Auth::guard('web')->check())){
            $buyerCode = Auth::guard('web')->user()->code;
            $buyerLvl = Auth::guard('web')->user()->lvl;
        }else{
          if(empty($_COOKIE['new_guest'])){
            $buyerCode = setcookie('new_guest', strtotime(date('Y-m-d H:i:s')).rand(100000, 999999), time() + (86400 * 30), "/");
          }else{
            $buyerCode = $_COOKIE['new_guest'];
          }
          $buyerLvl = "";
        }

        if(!isset($request->customer_address)){
          if(empty($request->billing_details_im)){
              $validator = Validator::make($request->all(), [
                  'f_name' => 'required',
                  'l_name' => 'required',
                  'email' => 'required',
                  'phone' => 'required',
                  'address' => 'required',
                  'city' => 'required',
                  'postcode' => 'required',
              ]);

              if ($validator->fails()) {
                  return Redirect::back()->withInput($request->all())->withErrors($validator);
              }

              $input = $request->all();
              $input['user_id'] = $buyerCode;
              $input['default'] = '1';
              $input['state'] = $request->state;
              $create_shipping_address = UserShippingAddress::create($input);
          }

          $shipping_address = UserShippingAddress::where('user_id', $buyerCode)
                                                 ->where('default', '1')
                                                 ->first();          
        }else{
          $validator = Validator::make($request->all(), [
                  'c_f_name' => 'required',
                  'c_l_name' => 'required',
                  'c_address' => 'required',
                  'c_postcode' => 'required',
                  'c_city' => 'required',
                  'c_state' => 'required',
                  'c_phone' => 'required',
              ]);

              if ($validator->fails()) {
                  return Redirect::back()->withInput($request->all())->withErrors('Please fill in customer shipping address');
              }
        }

        // $same_bill_address = (!empty($request->same_billing_address) && $request->same_billing_address != '') ? 1 : 0;

        // if($same_bill_address == 1){

        // }else{
        //   $validator = Validator::make($request->all(), [
        //           'f_name_bill' => 'required',
        //           'l_name_bill' => 'required',
        //           'email_bill' => 'required',
        //           'phone_bill' => 'required',
        //           'address_bill' => 'required',
        //           'city_bill' => 'required',
        //           'state_bill' => 'required',
        //           'postcode_bill' => 'required',
        //       ]);

        //       if ($validator->fails()) {
        //           return Redirect::back()->withInput($request->all())->withErrors("Please ensure every details in billing address is filled correctly.");
        //       }
        // }

        $selected_cart = [];
        foreach($request->selected_cart as $key => $value){
            $selected_cart[] = [$value];
        }

        $leftJoin = DB::raw("(SELECT * FROM product_images ORDER BY created_at ASC) AS i");
        $carts = Cart::select('carts.*', 'p.product_name', 'weight', 
                              'i.image', 'p.item_code', 'p.product_code', 
                              'scl.variation_name', 'scl.variation_price', 'scl.variation_special_price', 'scl.variation_agent_price', 
                              'scl.variation_agent_special_price', 'scl.variation_weight', 
                              'p.product_comm_type', 'p.product_comm_amount', 'own_product_comm_type', 'own_product_comm_amount', 
                              'in_product_comm_type', 'in_product_comm_amount', 'p.variation_enable', 'scl.id as vid',
                              'p.second_variation_enable', 'costing_price as p_costing_price',
                              'sscl.id as svid', 
                              'sscl.variation_name as second_variation_name',
                              'sscl.variation_price as second_variation_price', 
                              'sscl.variation_special_price as second_variation_special_price', 
                              'sscl.variation_agent_price as second_variation_agent_price', 
                              'sscl.variation_agent_special_price as second_variation_agent_special_price', 
                              'sscl.variation_weight as second_variation_weight',
                              'agent_get_point',
                              'p.upgrade_agent',
                              'p.customer_special_price',
                              'p.customer_price',
                              'p.get_pv',
                              'scl.variation_get_pv',
                              'sscl.variation_get_pv as second_variation_get_pv')
                     ->join('products AS p', 'p.id', 'carts.product_id')
                     ->leftJoin('product_variations AS scl', 'scl.id', 'carts.sub_category_id')
                     ->leftJoin('product_second_variations AS sscl', 'sscl.id', 'carts.second_sub_category_id')
                     ->leftJoin($leftJoin, function($join) {
                        $join->on('p.id', '=', 'i.product_id');
                     })
                     ->where('carts.status', '1')
                     ->where('carts.user_id', $buyerCode)
                     ->where('carts.mall', '1')
                     ->whereIn(DB::raw("md5(carts.id)"), $selected_cart)
                     ->groupBy('carts.id')
                     ->get();
        $totalAmount = 0;
        $totalWeight = 0;
        $totalPFee = 0;
        $paid = "";

        foreach ($carts as $cart) {
          $product = Product::find($cart->product_id);
          if($product->variation_enable == '1'){
              if($product->second_variation_enable == '1'){
                $BalanceQty = $this->SecondVariationBalanceQuantity($cart->second_sub_category_id);
              }else{
                $BalanceQty = $this->VariationBalanceQuantity($cart->sub_category_id);
              }
          }else{
            // if($product->packages == 1){
            //   $BalanceQty = "1000000000";
            // }else{
              $BalanceQty = $this->BalanceQuantity($cart->product_id);
            // }
          }

          if($BalanceQty < $request->quantity){
            Toastr::info("Quantity ". $request->quantity ." Exceed ". $BalanceQty ." Error for product: ".$product->product_name);
            return Redirect::back();
          }
        }

        foreach($carts as $cart){
          
          if(Auth::guard('merchant')->check() || Auth::guard('admin')->check()){
              if(!empty($buyerLvl)){
                  if($cart->is_free == 1){
                      $totalAmount += 0;
                  }else{
                      $variations = AgentPrice::where('agent_lvl_id', $buyerLvl);
                      if($cart->variation_enable == 1 && $cart->second_variation_enable == 1){
                          $variations = $variations->where('second_variation_id', $cart->svid);
                      }elseif($cart->variation_enable == 1 && empty($cart->second_variation_enable)){
                          $variations = $variations->where('variation_id', $cart->vid);
                      }else{
                         $variations = $variations->where('product_id', $cart->product_id);
                      }
                      $variations = $variations->first();
                      if(!empty($variations->special_price)){
                          $totalAmount += $variations->special_price * $cart->qty;
                      }else{
                          $totalAmount += $variations->price * $cart->qty;
                      }
                  }
              }
          }else{
              if($cart->is_free == 1){
                  $totalAmount += 0;
              }else{
                  if($cart->variation_enable == '1'){
                    if($cart->second_variation_enable == 1){
                        $variations = ProductSecondVariation::find($cart->svid);
                        if(!empty($variations->variation_special_price)){
                            $totalAmount += $variations->variation_special_price  * $cart->qty;
                            
                        }else{
                            $totalAmount += $variations->variation_price  * $cart->qty;
                            // echo $variations->product_id;
                            "<br>";
                        }
                    }else{
                        $variations = ProductVariation::find($cart->vid);

                        if(!empty($variations->variation_special_price)){
                            $totalAmount += $variations->variation_special_price  * $cart->qty;
                        }else{
                            $totalAmount += $variations->variation_price  * $cart->qty;
                        }
                    }
                  }else{
                      if(!empty($cart->customer_special_price)){
                          $totalAmount += $cart->customer_special_price  * $cart->qty;
                      }else{
                          $totalAmount += $cart->customer_price  * $cart->qty;
                      }
                  }
              }
          }
          
          if($cart->variation_enable == '1'){
            if($cart->second_variation_enable == '1'){
              $totalWeight += $cart->second_variation_weight * $cart->qty;
            }else{
              $totalWeight += $cart->variation_weight * $cart->qty;
            }
          }else{
            $totalWeight += $cart->weight * $cart->qty;
          }
          // $totalAmount += $cart->totalSum;
        }
        // echo $totalAmount;
        // exit();
        if($carts->isEmpty()){
          Toastr::info("Cart is empty, please re-order/re-payment.");
          return redirect()->route('home');
        }

        $cod = (!empty($request->cod) && $request->cod != '') ? 1 : 0;

        if($cod == 1){
          $totalAmount = $totalAmount;
        }else{
          $totalAmount = $totalAmount + $request->hidden_shipping_amount;
        }

        $PointWallet = $this->GetPointWalletBalance();

        if($PointWallet < $totalAmount){
            Toastr::danger("Insufficient Balance");
            return Redirect::back();
        }

        // return $totalAmount;

        $input = $request->all();
        if(!empty($request->hidden_discount) && $request->hidden_discount != '0'){
          $totalAmount = $totalAmount - $request->hidden_discount;
          $updateAppliedDiscount = AppliedPromotion::where('status', '1')
                                                   ->where('promotion_id', $request->discount_code)
                                                   ->where('user_id', $buyerCode)
                                                   ->update(['status'=>'2']);

          $input['discount'] = $request->hidden_discount;
          $input['discount_code'] = $request->discount_code;
        }elseif(!empty($request->discount_code) && $request->discount_code != '0'){
          $updateAppliedPromo = AppliedPromotion::where('status', '1')
                                                   ->where('promotion_id', $request->discount_code)
                                                   ->where('user_id', $buyerCode)
                                                   ->update(['status'=>'2']);
        }
        
        if(Auth::guard('merchant')->check() && !empty($request->hidden_ad_discount) && $request->hidden_ad_discount != '0'){
          
          $totalAmount = $totalAmount - $request->hidden_ad_discount;
          $input['ad_discount'] = $request->hidden_ad_discount;
        }

        if(empty($request->mall) && $request->mall != '1' && $request->cdm != '1' && $request->wallet != '1'){
          // $totalPFee = $totalAmount * 1.6 / 100;
          // $totalAmount = $totalAmount + ($totalAmount * 1.6 / 100);          
        }

        if($totalAmount <= 0){
            $totalAmount = $request->hidden_shipping_amount;
        }

        // return $totalAmount;
        

        $guest_agent = "";
        $guest_agent_type = "";
        
        if(!empty(Session::get('guest_agent'))){
          $m = Merchant::where('code', Session::get('guest_agent'))->first();
          if(!empty($m->id)){
            $guest_agent = $m->code;
            $guest_agent_type = $m->agent_type;
          }
          $input['guest_agent'] = $guest_agent;
        }
        $input['weight'] = $totalWeight;
        $input['transaction_no'] = $this->GenerateTransactionNo();
        $input['sub_total'] = $request->sub_total;
        $input['shipping_fee'] = !empty($request->hidden_shipping_amount) ? $request->hidden_shipping_amount : 0;
        $input['grand_total'] = number_format($totalAmount, 2, '.', '');
        $input['user_id'] = $buyerCode;
        $input['address_name'] = $shipping_address->f_name.' '.$shipping_address->l_name;
        $input['address'] = $shipping_address->address;
        $input['country_code'] = $shipping_address->country_code;
        $input['postcode'] = $shipping_address->postcode;
        $input['city'] = $shipping_address->city;
        $input['state'] = $shipping_address->state;
        $input['phone'] = $shipping_address->phone;
        $input['email'] = $shipping_address->email;
        $input['cod_address'] = "";
        $input['mall'] = '1';
        $input['status'] = '1';
        // if($same_bill_address != 1){
        //   $input['different_billing_address'] = '1';
        // }

        if($cod == 1){
            $input['processing_fee'] = "0";
            $input['shipping_fee'] = 0;
            $input['cod_address'] = $request->cod_address;
        }

        $transaction = Transaction::create($input);

        // if($same_bill_address != 1){
        //     $bill_input = $request->all();
        //     $bill_input['transaction_id'] = $transaction->id;
        //     $bill_input['address_name'] = $request->f_name_bill.' '.$request->l_name_bill;
        //     $bill_input['address'] = $request->address_bill;
        //     $bill_input['postcode'] = $request->postcode_bill;
        //     $bill_input['city'] = $request->city_bill;
        //     $bill_input['state'] = $request->state_bill;
        //     $bill_input['country_code'] = $request->country_code_bill;
        //     $bill_input['phone'] = $request->phone_bill;
        //     $bill_input['email'] = $request->email_bill;

        //     $bill_address = TransactionBillingAddress::create($bill_input);
        // }

        $items = [];
        $own_product_comm_type = "";
        $own_product_comm_amount = 0;
        $get_point = 0;
        $actual_weight = 0;
        $total_get_pv = 0;
        foreach($carts as $cart){
          
          if(Auth::guard('merchant')->check() || Auth::guard('admin')->check()){
              if(!empty($buyerLvl)){
                  $variations = AgentPrice::where('agent_lvl_id', $buyerLvl);
                  if($cart->variation_enable == 1 && $cart->second_variation_enable == 1){
                  $variations = $variations->where('second_variation_id', $cart->svid);
                  }elseif($cart->variation_enable == 1 && empty($cart->second_variation_enable)){
                  $variations = $variations->where('variation_id', $cart->vid);
                  }else{
                  $variations = $variations->where('product_id', $cart->product_id);
                  }
                  $variations = $variations->first();
                  if(!empty($variations->special_price)){
                      $actual_price = $variations->special_price;
                  }else{
                      $actual_price = $variations->price;
                  }

                  if($cart->is_free == 1){
                    $actual_price = 0;
                  }
              }
          }else{
              if($cart->variation_enable == '1'){
                if($cart->second_variation_enable == 1){
                    $variations = ProductSecondVariation::find($cart->svid);
                    if(!empty($variations->variation_special_price)){
                        $actual_price = $variations->variation_special_price;
                    }else{
                        $actual_price = $variations->variation_price;
                    }

                    if($cart->is_free == 1){
                      $actual_price = 0;
                    }
                    $total_get_pv = $cart->second_variation_get_pv;
                }else{
                    $variations = ProductVariation::find($cart->vid);
                    if(!empty($variations->variation_special_price)){
                        $actual_price = $variations->variation_special_price;
                    }else{
                        $actual_price = $variations->variation_price;
                    }

                    if($cart->is_free == 1){
                      $actual_price = 0;
                    }

                    $total_get_pv = $cart->variation_get_pv;
                }
              }else{
                  if(!empty($cart->customer_special_price)){
                    $actual_price = $cart->customer_special_price;
                  }else{
                    $actual_price = $cart->customer_price;
                  }

                  if($cart->is_free == 1){
                    $actual_price = 0;
                  }

                  $total_get_pv = $cart->get_pv;
              }
          }

          if($cart->variation_enable == '1'){
            if($cart->second_variation_enable == '1'){
              $actual_weight = $cart->second_variation_weight;
            }else{
              $actual_weight = $cart->variation_weight;
            }
          }else{
            $actual_weight = $cart->weight;
          }

          
          $items[] = ['transaction_id'=>$transaction->id,
                      'product_id'=>$cart->product_id,
                      'item_code'=>$cart->item_code,
                      'product_code'=>$cart->product_code,
                      'unit_weight'=>$actual_weight,
                      'product_image'=>$cart->image,
                      'product_name'=>$cart->product_name,
                      'unit_price'=>$actual_price,
                      'upgrade_agent'=>$cart->upgrade_agent,
                      'quantity'=>$cart->qty,
                      'sub_category'=>$cart->variation_name,
                      'second_sub_category'=>$cart->second_variation_name,
                      'variation_id'=>$cart->vid,
                      'second_variation_id'=>$cart->svid,
                      'total_amount'=>$cart->totalSum,
                      'get_pv'=>$total_get_pv,
                      'status'=>'1',
                      'created_at'=>date('Y-m-d H:i:s'),
                      'updated_at'=>date('Y-m-d H:i:s')]; 
          
        }

        $t_detail = TransactionDetail::insert($items);

        $bank = Bank::find($request->bank_id);

        $delete_cart = Cart::whereIn(DB::raw("md5(carts.id)"), $selected_cart)->delete();

        if((!empty($request->mall) && $request->mall == '1') || !empty($request->cdm) && $request->cdm == '1'){
          if(Auth::guard('web')->check() || Auth::guard('admin')->check() || Auth::guard('merchant')->check()){
            Toastr::success('Order Successfully');
            return \Redirect::route('pending_shipping');
          }
        }elseif($request->wallet == 1 || $request->cash_wallet == 1){
          Toastr::success('Your order has been placed successfully');
          return \Redirect::route('pending_shipping');
        }else{
          // $this->guestPlacedOrderMessage($shipping_address->phone, $transaction->transaction_no, $transaction->grand_total);
          return \Redirect::route('PaymentProcess', array('transactions'=>md5($transaction->id), 'bank_code'=>$bank->bank_code));
        }
    }

    public function getPurchaseAmount($code)
    {
        $transaction = Transaction::select(DB::raw('SUM(grand_total) as totalPurchase'))
                                  ->where('user_id', $code)
                                  ->where('status', '1')
                                  ->first();

        return !empty($transaction->totalPurchase) ? $transaction->totalPurchase : '0';
    }

    public function getAgentLevelPricing($user_id, $product_id)
    {
      $agent = Merchant::where('code', $user_id)
                       ->first();
      $agent_levels = AgentLevel::where('id', $agent->lvl)
                                ->first();
      $agent_pricing = AgentPrice::select('agent_prices.*')
                                 ->join('merchants as m,', 'm.code', $user_id)
                                 ->join('agent_levels as al', 'id', 'm.level')
                                 ->where('product_id', $product_id)
                                 ->where('agent_lvl_id', 'al->id');

      $result = $agent_pricing->price;
      return $result;

    }

    public function guestPlacedOrderMessage($phone, $transaction_no, $grand_total)
    {
        $destination = urlencode($phone);
        $message = "Hwajing: Thanks for purchasing on our website. \nYour order has been placed. \nOrder No: #".$transaction_no."\nRM ".$grand_total;
        $message = html_entity_decode($message, ENT_QUOTES, 'utf-8'); 
        $message = urlencode($message);
          
        $username = urlencode("hwajing2020");
        $password = urlencode("hwajing20201234");
        $sender_id = urlencode("66300");
        $type = "1";

        $fp = "https://www.isms.com.my/isms_send_all.php";
        $fp .= "?un=$username&pwd=$password&dstno=$destination&msg=$message&type=$type&sendid=$sender_id&agreedterm=YES";
        //echo $fp;
          
        $http = curl_init($fp);

        curl_setopt($http, CURLOPT_RETURNTRANSFER, TRUE);
        $http_result = curl_exec($http);
        $http_status = curl_getinfo($http, CURLINFO_HTTP_CODE);
        curl_close($http);
    }

    public function PaymentProcess($transaction, $bank_code)
    {
        $transactions = Transaction::where(DB::raw('md5(id)'), $transaction)->first();

        return view('frontend.payment_processing', ['transactions'=>$transactions, 'bank_code'=>$bank_code]);
    }

    public function TopupPaymentProcess($user_id, $amount, $bank_code, $transaction_no)
    {
        $bank = Bank::find($bank_code);

        return view('frontend.topup_payment_processing', ['user_id'=>$user_id, 'amount'=>$amount, 'bank_code'=>$bank->bank_code, 'transaction_no'=>$transaction_no]);
    }

    public function Payment_Error()
    {
        return view('frontend.payment_error');
    }

    public function payment_successfully(Request $request)
    {
        $json = $request->json()->all();
        
        if($json['status'] == '4'){
            $select = Transaction::where('transaction_no', $json['order']['orderReferenceNo'])->first();
            $transaction = Transaction::find($select->id);
            $details = TransactionDetail::where('transaction_id', $select->id)->get();
            $amount = $select->grand_total - $select->shipping_fee - $select->processing_fee;
            
            $affs = Affiliate::select(DB::raw('coalesce(m.lvl, a.lvl) as upline_lvl'), 
                                      'affiliates.user_id')
                             ->leftJoin('merchants as m', 'm.code', 'affiliates.user_id')
                             ->leftJoin('admins as a', 'a.code', 'affiliates.user_id')
                             ->where('affiliate_id', $select->user_id)
                             ->orderBy('sort_level', 'asc')
                             ->take(1)
                             ->get();



            $isMerchant = Merchant::where('code', $select->user_id)
                                  ->where('status', '1')
                                  ->first();

            $isUser = User::where('code', $select->user_id)
                          ->where('status', '1')
                          ->first();

            $details = TransactionDetail::where('transaction_id', $select->id)->get();

            if(!empty($isMerchant->id)){
                foreach($details as $detail){
                    $get_upline = Merchant::where('code', $isMerchant->master_id)
                                          ->where('status', '1')
                                          ->first();
                    if(!empty($get_upline->id)){
                        if($get_upline->lvl > $isMerchant->lvl){

                            $get_upline_price = AgentPrice::where('product_id', $detail->product_id)
                                                          ->where('agent_lvl_id', $get_upline->lvl);
                            if(!empty($detail->second_variation_id)){
                            $get_upline_price = $get_upline_price->where('second_variation_id', $detail->second_variation_id);
                            }
                            if(!empty($detail->variation_id)){
                            $get_upline_price = $get_upline_price->where('variation_id', $detail->variation_id);
                            }
                            $get_upline_price = $get_upline_price->first();

                            $upline_price = !empty($get_upline_price->special_price) ? $get_upline_price->special_price : $get_upline_price->price;

                            $upline_price = $upline_price * $detail->quantity;

                            $ori_price = $detail->unit_price * $detail->quantity;

                            $totalSpread = $ori_price - $upline_price;

                            if($totalSpread > 0 && $upline_price > 0){
                                if(!empty($detail->second_variation_id)){
                                    $product_name = $detail->product_name.' - '.$detail->sub_category.' - '.$detail->second_sub_category;
                                }elseif(!empty($detail->variation_id)){
                                    $product_name = $detail->product_name.' - '.$detail->sub_category;
                                }else{
                                    $product_name = $detail->product_name;
                                }
                                $input_spread = [];
                                $input_spread['type'] = 1;
                                $input_spread['user_id'] = $get_upline->code;
                                $input_spread['transaction_no'] = $select->transaction_no;
                                $input_spread['product_name'] = $product_name;
                                $input_spread['product_qty'] = $detail->quantity;
                                $input_spread['product_amount'] = $ori_price;
                                $input_spread['comm_pa_type'] = "Amount";
                                $input_spread['comm_pa'] = $totalSpread;
                                $input_spread['comm_amount'] = $totalSpread;
                                $input_spread['comm_desc'] = "Downline Spread Bonus";

                                AffiliateCommission::create($input_spread);
                            }
                        }
                    }
                }

                $upline = Merchant::where('code', $isMerchant->master_id)->first();

                if(!empty($upline->id)){
                    if($upline->lvl == $isMerchant->lvl){
                        if($isMerchant->lvl == 1){
                            $input_comm = [];
                            $input_comm['type'] = '6';
                            $input_comm['user_id'] = $upline->code;
                            $input_comm['user_by'] = $isMerchant->code;
                            $input_comm['product_amount'] = $amount;
                            $input_comm['transaction_no'] = $select->transaction_no;
                            $input_comm['comm_pa_type'] = "Percentage";
                            $input_comm['comm_pa'] = "4";
                            $input_comm['comm_amount'] = $amount * 4 / 100;
                            $input_comm['comm_desc'] = "1st Downline Purchase Bonus";
                            $input_comm['status'] = "1";

                            AffiliateCommission::create($input_comm);
                        }

                        if($isMerchant->lvl == 2){
                            $input_comm = [];
                            $input_comm['type'] = '6';
                            $input_comm['user_id'] = $upline->code;
                            $input_comm['user_by'] = $isMerchant->code;
                            $input_comm['product_amount'] = $amount;
                            $input_comm['transaction_no'] = $select->transaction_no;
                            $input_comm['comm_pa_type'] = "Percentage";
                            $input_comm['comm_pa'] = "5";
                            $input_comm['comm_amount'] = $amount * 5 / 100;
                            $input_comm['comm_desc'] = "1st Downline Purchase Bonus";
                            $input_comm['status'] = "1";

                            AffiliateCommission::create($input_comm);
                        }

                        if($isMerchant->lvl == 3){
                            $input_comm = [];
                            $input_comm['type'] = '6';
                            $input_comm['user_id'] = $upline->code;
                            $input_comm['user_by'] = $isMerchant->code;
                            $input_comm['product_amount'] = $amount;
                            $input_comm['transaction_no'] = $select->transaction_no;
                            $input_comm['comm_pa_type'] = "Percentage";
                            $input_comm['comm_pa'] = "6";
                            $input_comm['comm_amount'] = $amount * 6 / 100;
                            $input_comm['comm_desc'] = "1st Downline Purchase Bonus";
                            $input_comm['status'] = "1";

                            AffiliateCommission::create($input_comm);
                        }

                        if($isMerchant->lvl == 4){
                            $input_comm = [];
                            $input_comm['type'] = '6';
                            $input_comm['user_id'] = $upline->code;
                            $input_comm['user_by'] = $isMerchant->code;
                            $input_comm['product_amount'] = $amount;
                            $input_comm['transaction_no'] = $select->transaction_no;
                            $input_comm['comm_pa_type'] = "Percentage";
                            $input_comm['comm_pa'] = "7";
                            $input_comm['comm_amount'] = $amount * 7 / 100;
                            $input_comm['comm_desc'] = "1st Downline Purchase Bonus";
                            $input_comm['status'] = "1";

                            AffiliateCommission::create($input_comm);

                            $two_upline = Merchant::where('code', $upline->master_id)->first();

                            if(!empty($two_upline->id) && $two_upline->lvl == 4){
                                $input_comm_two = [];
                                $input_comm_two['type'] = '6';
                                $input_comm_two['user_id'] = $two_upline->code;
                                $input_comm_two['user_by'] = $isMerchant->code;
                                $input_comm_two['product_amount'] = $amount;
                                $input_comm_two['transaction_no'] = $select->transaction_no;
                                $input_comm_two['comm_pa_type'] = "Percentage";
                                $input_comm_two['comm_pa'] = "2";
                                $input_comm_two['comm_amount'] = $amount * 2 / 100;
                                $input_comm_two['comm_desc'] = "2nd Downline Purchase Bonus";
                                $input_comm_two['status'] = "1";

                                AffiliateCommission::create($input_comm_two);
                            }
                        }
                    }
                }
            }

            if(!empty($isUser->id)){
                foreach($details as $detail){
                    $get_upline = Merchant::where('code', $isUser->master_id)
                                          ->where('status', '1')
                                          ->first();
                    if(!empty($get_upline->id)){

                        $get_upline_price = AgentPrice::where('product_id', $detail->product_id)
                                                      ->where('agent_lvl_id', $get_upline->lvl);
                        if(!empty($detail->second_variation_id)){
                        $get_upline_price = $get_upline_price->where('second_variation_id', $detail->second_variation_id);
                        }
                        if(!empty($detail->variation_id)){
                        $get_upline_price = $get_upline_price->where('variation_id', $detail->variation_id);
                        }
                        $get_upline_price = $get_upline_price->first();

                        $upline_price = !empty($get_upline_price->special_price) ? $get_upline_price->special_price : $get_upline_price->price;

                        $upline_price = $upline_price * $detail->quantity;

                        $ori_price = $detail->unit_price * $detail->quantity;

                        $totalSpread = $ori_price - $upline_price;
                        // echo $upline_price.' - '.$ori_price;
                        // echo "\n";
                        if($totalSpread > 0 && $upline_price > 0){
                            if(!empty($detail->second_variation_id)){
                                $product_name = $detail->product_name.' - '.$detail->sub_category.' - '.$detail->second_sub_category;
                            }elseif(!empty($detail->variation_id)){
                                $product_name = $detail->product_name.' - '.$detail->sub_category;
                            }else{
                                $product_name = $detail->product_name;
                            }
                            $input_spread = [];
                            $input_spread['type'] = 1;
                            $input_spread['user_id'] = $get_upline->code;
                            $input_spread['transaction_no'] = $select->transaction_no;
                            $input_spread['product_name'] = $product_name;
                            $input_spread['product_qty'] = $detail->quantity;
                            $input_spread['product_amount'] = $ori_price;
                            $input_spread['comm_pa_type'] = "Amount";
                            $input_spread['comm_pa'] = $totalSpread;
                            $input_spread['comm_amount'] = $totalSpread;
                            $input_spread['comm_desc'] = "Downline Spread Bonus";

                            AffiliateCommission::create($input_spread);
                        }
                    }
                }

                // exit();
            }

            $transaction = $transaction->update(['status'=>'1']);
        }
    }

    public function topup_payment_successfully(Request $request)
    {
        if($request->status == '1'){
            $agent = Merchant::where('code', $request->customer)->first();
            $pcs = SettingJoiningFee::where('amount', $request->amount)->first();

            if(!empty($agent->id)){
                $input_topup = [];
                $input_topup['topup_no'] = $this->GenerateTopupNo();
                $input_topup['user_id'] = $agent->code;
                $input_topup['amount'] = $request->amount;
                $input_topup['package_id'] = $pcs->id;
                $input_topup['topup_payment_method'] = '1';
                $input_topup['amount_desc'] = "Joining Fee";

                $createTopup = TopupTransaction::create($input_topup);

                $update = Merchant::find($agent->id)->update(['status'=>'1']);

                $checkPromos = AgentLevel::where('agent_promo_requirement', '<=' ,$request->amount)
                                         ->orderBy('agent_promo_requirement', 'desc')
                                         ->first();
                
                if(!empty($checkPromos->id)){
                    if($agent->lvl < $checkPromos->id){
                        $update_lvl = Merchant::find($agent->id)->update(['lvl'=>$checkPromos->id]);
                    }
                }
            }


        }
    }

    public function GenerateTopupNo()
    {
      $topup = TopupTransaction::select(DB::raw('COUNT(id) AS TotalTopup'))->first();
      $TotalTopup = $topup->TotalTopup + 1;

      if(strlen($TotalTopup) == 1){
          $TNo = 'T'.strtotime(date('Y-m-d H:i:s'))."0000".$TotalTopup;
      }elseif(strlen($TotalTopup) == 2){
          $TNo = 'T'.strtotime(date('Y-m-d H:i:s'))."000".$TotalTopup;
      }elseif(strlen($TotalTopup) == 3){
          $TNo = 'T'.strtotime(date('Y-m-d H:i:s'))."00".$TotalTopup;
      }elseif(strlen($TotalTopup) == 4){
          $TNo = 'T'.strtotime(date('Y-m-d H:i:s'))."0".$TotalTopup;
      }else{
          $TNo = 'T'.strtotime(date('Y-m-d H:i:s')).$TotalTopup;
      }
      return $TNo;
    }

    public function GetGenerationCommision($level, $agent_lvl)
    {
        $comm = SettingMerchantCommission::where('level', $level)
                                         ->where('agent_lvl', $agent_lvl)
                                         ->first();
        if(!empty($comm->comm_amount)){
            return array($comm->comm_type, $comm->comm_amount);          
        }else{
          return array(0, 0);
        }
        
    }

    public static function BalanceQuantity($id)
    {
        $stockBalance = Stock::select(DB::raw('SUM(IF(type = "Increase", quantity, NULL)) AS totalStockIn'),
                                      DB::raw('SUM(IF(type = "Decrease", quantity, NULL)) AS totalStockOut'))
                                ->where('product_id', $id)
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
                                        ->whereIn('t.status', ['1', '98', '99', '97'])
                                        ->where('product_id', $id)
                                        ->first();


        return $stockBalance->totalStockIn - $stockBalance->totalStockOut - $cart->InCart - $transaction->TransCart;
    }

    public static function GenerateTransactionNo()
    {
      $transaction = Transaction::select(DB::raw('COUNT(id) AS TotalTransaction'))
                                ->first();
      $TotalTransaction = $transaction->TotalTransaction + 1;
      if(strlen($TotalTransaction) == 1){
          $tNo = strtotime(date('Y-m-d H:i:s'))."0000".$TotalTransaction;
      }elseif(strlen($TotalTransaction) == 2){
          $tNo = strtotime(date('Y-m-d H:i:s'))."000".$TotalTransaction;
      }elseif(strlen($TotalTransaction) == 3){
          $tNo = strtotime(date('Y-m-d H:i:s'))."00".$TotalTransaction;
      }elseif(strlen($TotalTransaction) == 4){
          $tNo = strtotime(date('Y-m-d H:i:s'))."0".$TotalTransaction;
      }else{
          $tNo = strtotime(date('Y-m-d H:i:s')).$TotalTransaction;
      }
      return $tNo;
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

    public function countPending()
    {
      $transactions = Transaction::where('status', '99')->where('user_id', Auth::user()->code)->get();

      return count($transactions);
    }


    public function countToShip()
    {
      $transaction2 = Transaction::where('user_id', Auth::user()->code)
                                  ->whereIn('status', ['98', '1'])
                                  ->whereNull('tracking_no')
                                  ->whereNull('to_receive')
                                  ->orderBy('created_at', 'desc')
                                  ->get();

      $transactions = Transaction::whereIn('status', ['98', '1'])->where('user_id', Auth::user()->code)->get();

      $details = [];
      $ship_details = [];
      $CountTotal=0;

      foreach($transactions as $transaction){
        if(!empty($transaction->order_number)){
           $details[$transaction->id] = TransactionDetail::where('transaction_id', $transaction->id)->get();

           $domain = "http://connect.easyparcel.my/?ac=";

           $action = "EPParcelStatusBulk";
           $postparam = array(
           'api'   => 'EP-vGlDwpuFK',
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
                    $ship_details[$transaction->id] = $value2->ship_status;
                    if($ship_details[$transaction->id] == 'Schedule In Arrangement' || 
                       $ship_details[$transaction->id] == 'Pending for Drop Off'){
                      $CountTotal++;
                    }
                }
            }
         }
      }

      $total = count($transaction2) + $CountTotal;
      return $total;
    }

    public function countToReceive()
    {
      $transactions = Transaction::where('status', '1')
                                 ->whereNull('completed')
                                 ->where('user_id', Auth::user()->code)
                                 ->get();


      $transactions2 = Transaction::where('status', '1')
                                 ->where('to_receive', '1')
                                 ->whereNull('completed')
                                 ->where('user_id', Auth::user()->code)
                                 ->get();

      $details = [];
      $ship_details = [];
      $CountTotal=0;
      foreach($transactions as $transaction){
         $details[$transaction->id] = TransactionDetail::where('transaction_id', $transaction->id)->get();

         $domain = "http://connect.easyparcel.my/?ac=";

         $action = "EPParcelStatusBulk";
         $postparam = array(
         'api'   => 'EP-vGlDwpuFK',
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
                  $ship_details[$transaction->id] = $value2->ship_status;
                  if($ship_details[$transaction->id] == 'Pending For Collection' || $ship_details[$transaction->id] == 'Collected' || $ship_details[$transaction->id] == 'Delivering(in transit)' || 
                    $ship_details[$transaction->id] == 'Parcel Drop Off at Point'){
                      $CountTotal++;
                  }
              }
          }
      }

      return $CountTotal + count($transactions2);
    }

    public function countCompleted()
    {
      $transactions = Transaction::where('user_id', Auth::user()->code)
                                 ->where('status', '1')
                                 ->get();

      $transactions2 = Transaction::where('user_id', Auth::user()->code)
                                 ->where('status', '1')
                                 ->Where('to_receive', '1')
                                 ->Where('completed', '1')
                                 ->get();

      $CountTotal = 0;
      foreach($transactions as $transaction){
         $details[$transaction->id] = TransactionDetail::where('transaction_id', $transaction->id)->get();

         $domain = "http://connect.easyparcel.my/?ac=";

         $action = "EPParcelStatusBulk";
         $postparam = array(
         'api'   => 'EP-vGlDwpuFK',
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
                  $ship_details[$transaction->id] = $value2->ship_status;

                  if($ship_details[$transaction->id] == 'Successfully Delivered'){
                    $CountTotal++;

                  }
              }
          }
      }

      return $CountTotal + count($transactions2);
    }

    public function countCancelled()
    {
      $transactions = Transaction::whereIn('status', ['95', '96'])->where('user_id', Auth::user()->code)->get();

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

    public function VariationBalanceQuantity($id)
    {
        $quantityAmount = ProductVariation::find($id);

        $cart = Cart::select(DB::raw('SUM(qty) AS InCart'))
                    ->where('status', '1')
                    ->where('sub_category_id', $id)
                    ->first();

        $transaction = TransactionDetail::select(DB::raw('SUM(quantity) AS TransCart'))
                                        ->join('transactions AS t', 't.id', 'transaction_details.transaction_id')
                                        ->whereIn('t.status', ['1', '97', '98', '99'])
                                        ->where('variation_id', $id)
                                        ->first();

        return $quantityAmount->variation_stock - $cart->InCart - $transaction->TransCart;
    }

    public function SecondVariationBalanceQuantity($id)
    {
        $quantityAmount = ProductSecondVariation::find($id);

        $cart = Cart::select(DB::raw('SUM(qty) AS InCart'))
                    ->where('status', '1')
                    ->where('second_sub_category_id', $id)
                    ->first();

        $transaction = TransactionDetail::select(DB::raw('SUM(quantity) AS TransCart'))
                                        ->join('transactions AS t', 't.id', 'transaction_details.transaction_id')
                                        ->whereIn('t.status', ['1', '97', '98', '99'])
                                        ->where('second_variation_id', $id)
                                        ->first();

        return $quantityAmount->variation_stock - $cart->InCart - $transaction->TransCart;
    }

    public function submit_topup(Request $request)
    {
      $getTopupAmountLimit = AgentLevel::find(Auth::user()->lvl);
      $amount = preg_replace("/[^0-9\.]/", '', $request->topup_amount_val);

      if($getTopupAmountLimit->minimum_purchase > 0){
          if(floatval($amount) < $getTopupAmountLimit->minimum_purchase){
              Toastr::error('Minimum Topup Amount: '.$getTopupAmountLimit->minimum_purchase);
              return redirect()->back();
          }
      }



      $input_topup = [];
      if($request->selected_payment_method == '2'){
          $files = $request->file('bank_slip'); 
          $name = $files->getClientOriginalName();
          $exp = explode(".", $name);
          $file_ext = end($exp);
          $name = md5($name.date('Y-m-d H:i:s')).'.'.$file_ext;
          $files->move(GlobalController::get_image_path("uploads/bank_slip/".Auth::user()->code."/"), $name);
          
          $input_topup['topup_payment_method'] = $request->selected_payment_method;
          $input_topup['topup_no'] = $this->GenerateTopupNo();
          $input_topup['user_id'] = Auth::user()->code;
          $input_topup['user_lvl'] = Auth::user()->lvl;
          $input_topup['amount'] = $amount;
          $input_topup['actual_amount'] = $amount;
          $input_topup['amount_desc'] = "Topup: RM ".$amount;
          $input_topup['bank_slip'] = "uploads/bank_slip/".Auth::user()->code."/".$name;
          $input_topup['status'] = "99";

          $createTopup = TopupTransaction::create($input_topup);

          Toastr::success("Topup submitted! Please wait Admin for Verify");
          return redirect()->route('wallet');

      }else{
          $input_topup['topup_payment_method'] = $request->selected_payment_method;
          $input_topup['topup_no'] = $this->GenerateTopupNo();
          $input_topup['user_id'] = Auth::user()->code;
          $input_topup['user_lvl'] = Auth::user()->lvl;
          $input_topup['amount'] = $amount;
          $input_topup['actual_amount'] = $amount;
          $input_topup['amount_desc'] = "Topup: RM ".$amount;
          $input_topup['bank_id'] = $request->bank_id;
          $input_topup['status'] = "55";

          $createTopup = TopupTransaction::create($input_topup);

          return \Redirect::route('TopupPaymentProcess', array('user_id'=>Auth::user()->code, 'amount'=>$amount, 'bank_code'=>$request->bank_id,
                                                                         'transaction_no'=>$createTopup->topup_no));
      }
    }

    public function logistic_tracking($transaction_no)
    {
        $transaction = Transaction::where('transaction_no', $transaction_no)->first();

        if(empty($transaction->id)){
          abort(404);
        }



        $domain = "http://connect.easyparcel.my/?ac=";

        $action = "EPTrackingBulk";
        $postparam = array(
        'api'   => 'EP-vGlDwpuFK',
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
        if(!empty(Auth::guard('admin')->check())){
            $buyerCode = Auth::guard('admin')->user()->code;
        }elseif(!empty(Auth::guard('merchant')->check())){
            $buyerCode = Auth::guard('merchant')->user()->code;
        }elseif(!empty(Auth::guard('web')->check())){
            $buyerCode = Auth::guard('web')->user()->code;
        }elseif(!empty(Auth::guard('dropshipper')->check())){
            $buyerCode = Auth::guard('dropshipper')->user()->code;
        }else{
          if(empty($_COOKIE['new_guest'])){
            $buyerCode = setcookie('new_guest', strtotime(date('Y-m-d H:i:s')).rand(100000, 999999), time() + (86400 * 30), "/");
          }else{
            $buyerCode = $_COOKIE['new_guest'];
          }
        }

        $validator = Validator::make($request->all(), [
            'f_name' => 'required',
            'country_code' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'address' => 'required',
            'state' => 'required',
            'city' => 'required',
            'postcode' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }

        $update = UserShippingAddress::where('user_id', $buyerCode)->update(['default'=>null]);
        
        $input = $request->all();
        $input['default'] = 1;
        $input['user_id'] = $buyerCode;

        $create = UserShippingAddress::create($input);

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
                    <strong>Order Confirmation (".$transaction_no.")</strong>
                  </td>
                </tr>";
      $body .= "<tr><td></td></tr>
                <tr><td></td></tr>
                <tr>
                  <td>
                    <td>Product Details</td>
                    <td>Unit Price</td>
                    <td>Quantity</td>
                  </td>
                </tr>";
      foreach($details as $detail){
      $sub_category = (!empty($detail->sub_category)) ? '<br> Variation: '.$detail->sub_category : '';
      $body .= "<tr>";
          if(!empty($detail->product_image)){
      $body .=   "<td><img src='".url($detail->product_image)."'></td>";
          }else{
      $body .=   "<td></td>";
          }
      $body .=   "<td>".$detail->product_name.$sub_category."</td>
                  <td>".$detail->unit_price."</td>
                  <td>x".$detail->quantity."</td>
                </tr>";  
      }
      $body .= "<tr><td></td></tr>";
      $body .= "<tr><td></td></tr>";
      $body .= "<tr><td>Regards,</td></tr>";
      $body .= "<tr><td>Serendipity</td></tr>";
      $body .= "<tr><td></td></tr>";
      // $body .= "<tr><td colspan='2' style='border:none;'>{$cmessage}</td></tr>";
      $body .= "</tbody></table>";
      $body .= "</body></html>";

      $send = mail($to, $subject, $body, $headers);
    }

    public function sendAdminOrderNotification($to, $from, $subject, $transaction_no)
    {
      $transaction = Transaction::where('transaction_no', $transaction_no)->first();
      $state = State::find($transaction->state);
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
      $body .= "<td style='border:none;'><strong>Dear Admin,</strong></td></tr>";
      $body .= "<tr>
                  <td style='border:none;'>
                    <strong>New Order(". $transaction->transaction_no .") Placed From Customer(". $transaction->user_id .")</strong>
                  </td>
                </tr>";
      $body .= "<tr><td>Name: ". $transaction->address_name ."</td></tr>
                <tr><td>Address: ". $transaction->address .", ". $transaction->postcode ." ". $transaction->city .", ". $state->name ."</td></tr>
                <tr>
                  <td>
                    <td>Product Details</td>
                    <td>Unit Price</td>
                    <td>Quantity</td>
                  </td>
                </tr>";
      foreach($details as $detail){
      $sub_category = (!empty($detail->sub_category)) ? '<br> Variation: '.$detail->sub_category : '';
      $body .= "<tr>";
        if(!empty($detail->product_image)){
        $body .=  "<td><img src='".url($detail->product_image)."'></td>";
        }else{
        $body .=  "<td>Product Image Not Available</td>";
        }
      $body .=   "<td>".$detail->product_name.$sub_category."</td>
                  <td>".$detail->unit_price."</td>
                  <td>x".$detail->quantity."</td>
                </tr>";  
      }
      $body .= "<tr><td></td></tr>";
      $body .= "<tr><td></td></tr>";
      $body .= "<tr><td>Regards,</td></tr>";
      $body .= "<tr><td>Serendipity</td></tr>";
      $body .= "<tr><td></td></tr>";
      // $body .= "<tr><td colspan='2' style='border:none;'>{$cmessage}</td></tr>";
      $body .= "</tbody></table>";
      $body .= "</body></html>";

      $send = mail($to, $subject, $body, $headers);
    }

    public function update_address(Request $request)
    {
        $address = UserShippingAddress::find($request->default);
        
        if(!empty($address->id)){
          $update = UserShippingAddress::where('user_id', Auth::user()->code)->update(['default'=>null]);

          $address = $address->update(['default'=>'1']);
        }

        return redirect()->back();
    }

    public function Menu()
    {
        $website_setting = WebsiteSetting::find(1);
        $xml = '<iframe src="'.url($website_setting->menu).'" width="100%" style="height:100%"></iframe>';
        return response($xml,200);
        
    }

    public function resetPassword(Request $request)
    {
        if(empty($request->new_password)){
            Toastr::error('Please key in your new password');
            return redirect()->back();
        }

        if(empty($request->confirm_new_password) || $request->confirm_new_password != $request->new_password){
            Toastr::error('Password Does Not Match!');
            return redirect()->back();
        }



        $user = User::where('code', $request->code)->first();
        $agent = Merchant::where('code', $request->code)->first();
        if(empty($user->id)){
          if(empty($agent->id)){
            return redirect()->route('home');
          }
        }

        if(!empty($user)){
        $update = User::find($user->id);
        $update = $update->update(['password'=>Hash::make($request->new_password)]);
        $update2 = User::find($user->id);
        $update2 = $update2->update(['secondary_password'=>Hash::make($request->new_secondary_password)]);
        $update3 = User::find($user->id);
        $update3 = $update3->update(['reset_password'=>'0']);
        Toastr::success('Password Changed Successfully');
        return redirect()->route('login');
      }elseif(!empty($agent)){
        $update = Merchant::find($agent->id);
        $update = $update->update(['password'=>Hash::make($request->new_password)]);
        $update2 = Merchant::find($agent->id);
        $update2 = $update2->update(['secondary_password'=>Hash::make($request->new_secondary_password)]);
        $update3 = Merchant::find($agent->id);
        $update3 = $update3->update(['reset_password'=>'0']);
        Toastr::success('Password Changed Successfully');
        return redirect()->route('login');
      }
    }

    public function resetPasswordAction($code, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => ['required', 'min:6', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }

        $merchant = Merchant::where(DB::raw('md5(code)'), $code)->update(['password'=>Hash::make($request->password)]);

        Toastr::success('Password reset successfully. You may try login now');
        return redirect()->route('login');
    }

    public function contact_us_send(Request $request)
    {
        if(!empty($_REQUEST['email'])){
            $from_email = $_REQUEST['email'];
        }else{
            $from_email = 'email_not_filled@email.com';
        }

        // $to = "enquiry@lysatechnology.com";
        $admin = Admin::find(1);
        $to = $admin->contact_email;
        $from = $from_email;
        $name = $_REQUEST['name'];
        $subject = "New enquiry from DabeBeaute";
        $cmessage = $_REQUEST['message'];
        $phone = $_REQUEST['phone'];

        $headers = "From: $from";
        $headers = "From: " . $from . "\r\n";
        $headers .= "Reply-To: ". $from . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

        $logo = 'img/08eadd25-de75-4569-b158-2967e520de88.jpg';
        $link = '#';

        $body = "<!DOCTYPE html><html lang='en'><head><meta charset='UTF-8'><title>Express Mail</title></head><body>";
        $body .= "<table style='width: 100%;'>";
        $body .= "<thead style='text-align: center;'><tr><td style='border:none;' colspan='2'>";
        $body .= "<a href='{$link}'><img src='{$logo}' alt=''></a><br><br>";
        $body .= "</td></tr></thead><tbody><tr>";
        $body .= "<td style='border:none;'><strong>Name:</strong> {$name}</td>";
        $body .= "<td style='border:none;'><strong>Email:</strong> {$from}</td>";
        $body .= "</tr>";
        $body .= "<tr><td style='border:none;'><strong>Phone:</strong> {$phone}</td></tr>";
        $body .= "<tr><td style='border:none;'><strong>Subject:</strong> {$subject}</td></tr>";
        $body .= "<tr><td></td></tr>";
        $body .= "<tr><td colspan='2' style='border:none;'>{$cmessage}</td></tr>";
        $body .= "</tbody></table>";
        $body .= "</body></html>";

        $send = mail($to, $subject, $body, $headers);

        Toastr::success("Your inquiry has been submitted and will be answered as soon as possible! Thank you for contacting us.");
        return redirect()->back();
    }

    public function SendForgotPasswordLink(Request $request)
    {
        $agent = Merchant::where('email', $request->email)->first();
        $user = User::where('email', $request->email)->first();

        if(!empty($user)){
        $updateUser = User::where('email', $request->email)->update(['reset_password'=>'1']);
        }

        if(!empty($agent)){
          $updateAgent = Merchant::where('email', $request->email)->update(['reset_password'=>'1']);
        }

        if(empty($user)){
          if(empty($agent)){
            Toastr::error('This email does not exists in our system');
            return redirect()->route('login');
          }
        }

        if(!empty($user) && $user->status == '99'){
            Toastr::error('This account has not been activated by admin');
            return redirect()->route('login');   
        }

        if(!empty($user)){
          $this->sendAccountPassword($request->email, "noreply@dabebeaute.com.my", "Change Password", $user->code);
          // Mail::to($request->email)->send(new \App\Mail\NewAccountPasswordEN('Account Notification', $user->code, $user->f_name));
          Toastr::success('An email has been sent to your email address. Please click the link in the email to change your password');
          return redirect()->route('login');
        }elseif(!empty($agent)){
          $this->sendAccountPassword($request->email, "noreply@dabebeaute.com.my", "Change Password", $agent->code);
          // Mail::to($request->email)->send(new \App\Mail\NewAccountPasswordEN('Account Notification', $agent->code, $agent->f_name));
          Toastr::success('An email has been sent to your email address. Please click the link in the email to change your password');
          return redirect()->route('login');
        }
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
      $body .= "<tr><td>DabeBeaute</td></tr>";
      $body .= "<tr><td></td></tr>";
      // $body .= "<tr><td colspan='2' style='border:none;'>{$cmessage}</td></tr>";
      $body .= "</tbody></table>";
      $body .= "</body></html>";

      $send = mail($to, $subject, $body, $headers);
    }

    public function ForgetPassword($code)
    {
        $customer = User::where('code', $code)->first();
        $agent = Merchant::where('code', $code)->first();

        if(!empty($user)){
          $user = $customer;
        }elseif(!empty($agent)){
          $user = $agent;
        }else{
          return redirect()->route('home');
        }

        if(empty($user->id)){
          if(empty($agent->id)){
            return redirect()->route('home');
          }
        }

        if(!empty($user)){
          if($user->reset_password == '0'){
            return redirect()->route('home');
          }
        }  

        return view('frontend.forget_password', ['user'=>$user]);
    }

    public function invoice($transaction_no)
    {
      $transaction = Transaction::select('transactions.*', 'p.amount_type', 'p.amount AS discount_amount', 'p.discount_code', 
                                          'tba.address_name as bill_name', 'tba.address as bill_address', 'tba.postcode as bill_postcode', 'tba.city as bill_city', 'tba.state as bill_state')
                                  ->leftJoin('promotions AS p', 'p.id', 'transactions.discount_code')
                                  ->leftJoin('transaction_billing_addresses as tba', 'tba.transaction_id', 'transactions.id')
                                  ->where('transactions.transaction_no', $transaction_no)
                                  ->first();

        if(empty($transaction->id)){
            abort(404);
        }

        $bank_online = Bank::find($transaction->bank_id);
        $bank_cdm = Bank::where('bank_code', $transaction->cdm_bank_id)->first();

        $details = TransactionDetail::select('transaction_details.*', 'transaction_details.quantity as t_qty', 'u.uom_name', 'p.packages')
                                    ->join('products AS p', 'p.id', 'transaction_details.product_id')
                                    ->leftJoin('setting_uoms AS u', 'u.id', 'p.product_type')
                                    ->where('transaction_id', $transaction->id)
                                    ->get();

        return view('frontend.invoice', ['transaction'=>$transaction, 'details'=>$details]);
    }

    public function createTransaction()
    {
        $lvl = "";
      $agentLVL = AgentLevel::find(Auth::user()->lvl);
      if(!empty($agentLVL->id)){
        $lvl = $agentLVL->agent_lvl;
      }

        $downlines = User::where('master_id', Auth::user()->code)
                             ->where('status', '1')
                             ->get();

        $products = Product::select('products.*')
                                   ->where('products.status', '1')
                                   ->whereNull('products.mall')
                                   ->whereNull('products.upgrade_agent')
                                   ->get();

        $getMUpline = Merchant::where('code', Auth::user()->master_id)->first();
        $getAUpline = Admin::where('code', Auth::user()->master_id)->first();

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

        $totalProductBalance = $this->GetProductWalletBalance();

        $states = State::get();
        $totalCashBalance = $this->GetCashWalletBalance();

        return view('frontend.create_transaction', ['lvl'=>$lvl, 'downlines'=>$downlines, 'products'=>$products,
                                                    'upline_name'=>$upline_name,
                                                    'upline_code'=>$upline_code,
                                                    'totalProductBalance'=>$totalProductBalance,
                                                    'totalCashBalance'=>$totalCashBalance,
                                                    'states'=>$states]);
    }

    public function SaveTransaction(Request $request)
    {
        $totalCashBalance = $this->GetCashWalletBalance();

        $validator = Validator::make($request->all(), [
            'merchants' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }

        $GetProductWalletBalance = $this->GetProductWalletBalance($request->merchants);

        if($request->totalDeduct > $GetProductWalletBalance){
            return Redirect::back()->withInput($request->all())->withErrors("Insufficient balance");
        }
        //Check Stock Balance
        $array = [];
        $v_array = [];
        $v_two_array = [];
        $totalProductQty = [];
        $totalVariationQty = [];
        $totalSecondVariationQty = [];
        $b = 0;
        for($a=0; $a<count($request->product_id); $a++){
            $b++;

            $check_product_detail = Product::find($request->product_id[$a]);
            
            if(empty($request->product_id[$a])){
                return Redirect::back()->withInput($request->all())->withErrors("Please select at least one product.");
            }

            if(!empty($request->product_id[$a]) && empty($request->quantity[$a])){
                return Redirect::back()->withInput($request->all())->withErrors("Please fill in quantity");
            }
            if(!empty($request->product_id[$a]) && !empty($request->quantity[$a])){
                if(!empty($request['product_second_variation_option'.$b])){
                    if(!empty($v_two_array)){
                        if(in_array($request['product_second_variation_option'.$b], $v_two_array)){
                            $totalSecondVariationQty[$request['product_second_variation_option'.$b]] = $totalSecondVariationQty[$request['product_second_variation_option'.$b]] + $request->quantity[$a];
                        }else{
                            array_push($v_two_array, $request['product_second_variation_option'.$b]);
                            $totalSecondVariationQty[$request['product_second_variation_option'.$b]] = $request->quantity[$a];  
                        }
                    }else{
                        array_push($v_two_array, $request['product_second_variation_option'.$b]);
                        $totalSecondVariationQty[$request['product_second_variation_option'.$b]] = $request->quantity[$a];
                    }
                }elseif(!empty($request['product_variation'.$b])){
                    if(!empty($v_array)){
                        if(in_array($request['product_variation'.$b], $v_array)){
                            $totalVariationQty[$request['product_variation'.$b]] = $totalVariationQty[$request['product_variation'.$b]] + $request->quantity[$a];
                        }else{
                            array_push($v_array, $request['product_variation'.$b]);
                            $totalVariationQty[$request['product_variation'.$b]] = $request->quantity[$a];  
                        }
                    }else{
                        array_push($v_array, $request['product_variation'.$b]);
                        $totalVariationQty[$request['product_variation'.$b]] = $request->quantity[$a];
                    }
                }else{
                    if(!empty($array)){
                        if(in_array($request->product_id[$a], $array)){
                            $totalProductQty[$request->product_id[$a]] = $totalProductQty[$request->product_id[$a]] + $request->quantity[$a];
                        }else{
                            array_push($array, $request->product_id[$a]);
                            $totalProductQty[$request->product_id[$a]] = $request->quantity[$a];  
                        }
                    }else{
                        array_push($array, $request->product_id[$a]);
                        $totalProductQty[$request->product_id[$a]] = $request->quantity[$a];
                    }
                }
            }
        }

        $exceedBalanceProduct = [];
        foreach($array as $product_id){
            if($check_product_detail->packages != '1'){
                $checkStock = $this->BalanceQuantity($product_id);
                if($totalProductQty[$product_id] > $checkStock){
                    $exceedBalanceProduct[] = $product_id;
                }
            }
        }

        // exit();

        $exceedBalanceVariation = [];
        foreach($v_array as $variation_id){
            
            $checkVStock = $this->VariationBalanceQuantity($variation_id);
            if($totalVariationQty[$variation_id] > $checkVStock){
                $exceedBalanceVariation[] = $variation_id;
            }
        }

        $exceedBalanceSecondVariation = [];
        foreach($v_two_array as $second_variation_id){
            
            $checkVStock = $this->SecondVariationBalanceQuantity($second_variation_id);
            if($totalSecondVariationQty[$second_variation_id] > $checkVStock){
                $exceedBalanceSecondVariation[] = $second_variation_id;
            }
        }

        // exit();

        if(!empty($exceedBalanceProduct)){
            $getProduct = Product::whereIn('id', $exceedBalanceProduct)->get();
            $totalProd = [];
            foreach($getProduct as $prod){
                $totalProd[] = $prod->product_name;
            }

            $im = implode(", ", $totalProd);

            return Redirect::back()->withInput($request->all())->withErrors($im." Stock Balance not enough");
        }



        if(!empty($exceedBalanceVariation)){
            $getProduct = ProductVariation::whereIn('id', $exceedBalanceVariation)->get();
            $totalVarian = [];
            foreach($getProduct as $prod){
                $totalVarian[] = $prod->variation_name;
            }

            $imv = implode(", ", $totalVarian);

            return Redirect::back()->withInput($request->all())->withErrors($imv." Stock Balance not enough");
        }



        if(!empty($exceedBalanceSecondVariation)){
            $getProduct = ProductSecondVariation::whereIn('id', $exceedBalanceSecondVariation)->get();
            $totalSecondVarian = [];
            foreach($getProduct as $prod){
                $totalSecondVarian[] = $prod->variation_name;
            }

            $imv_two = implode(", ", $totalSecondVarian);

            return Redirect::back()->withInput($request->all())->withErrors($imv_two." Stock Balance not enough");
        }

        $use_new = isset($request->use_new) ? '1' : '0';
        if($use_new == 1){
            $user_shipping = UserShippingAddress::where('user_id', $request->merchants)
                                                ->where('default', '1')
                                                ->first();
            if(!empty($user_shipping->id)){
                UserShippingAddress::where('user_id', $user_shipping->user_id)->update(['default'=>null]);
            }

            $validator = Validator::make($request->all(), [
                'f_name' => 'required',
                'phone' => 'required',
                'address' => 'required',
                'city' => 'required',
                'postcode' => 'required',
                'state' => 'required',
            ]);

            if ($validator->fails()) {
                return Redirect::back()->withInput($request->all())->withErrors($validator);
            }

            $input = $request->all();
            $input['user_id'] = $request->merchants;
            $input['default'] = '1';
            $create_shipping_address = UserShippingAddress::create($input);
        }

        $shipping_address = UserShippingAddress::where('user_id', $request->merchants)
                                            ->where('default', '1')
                                            ->first();

        $input = $request->all();
        $input['user_id'] = $request->merchants;
        $input['transaction_no'] = $this->GenerateTransactionNo();
        $input['created_by'] = Auth::user()->code;
        $input['deduct_wallet'] = 1;
        $input['address_name'] = $shipping_address->f_name;
        $input['address'] = $shipping_address->address;
        $input['postcode'] = $shipping_address->postcode;
        $input['city'] = $shipping_address->city;
        $input['state'] = $shipping_address->state;
        $input['phone'] = $shipping_address->phone;
        $input['email'] = $shipping_address->email;
        if($request->cdm == 1){
            $files = $request->file('bank_slip'); 
            $name = $files->getClientOriginalName();
            $exp = explode(".", $name);
            $file_ext = end($exp);
            $name = md5($name.date('Y-m-d H:i:s')).'.'.$file_ext;
            $files->move(GlobalController::get_image_path("uploads/bank_slip/".$request->merchants."/"), $name);

            $input['cdm_bank_id'] = $request->cdm_bank_id;
            $input['bank_slip'] = "uploads/bank_slip/".$request->merchants."/".$name;
            $input['status'] = '98';
            $input['cod_address'] = "0";
        }

        if($request->cash_wallet == 1){
            $input['mall'] = '1';
            $input['status'] = '1';
            $input['cod_address'] = "0";
        }

        $transaction = Transaction::create($input);

        $num = 0;
        $grand_total = 0;
        $deduct_total = 0;
        $weight_total = 0;
        foreach($request->product_id as $key => $value){
            $num++;
            $product_detail = Product::select('i.image', 'products.*')
                                     ->leftJoin('product_images as i', 'i.product_id', 'products.id')
                                     ->where('products.id', $value)
                                     ->first();

            if(!empty($request['product_variation'.$num])){
               $variation_detail = ProductVariation::find($request['product_variation'.$num]);
            }

            if(!empty($request['second_variation_option'.$num])){
               $second_variation_detail = ProductSecondVariation::find($request['second_variation_option'.$num]);
            }

            $input_detail = [];
            $input_detail['transaction_id'] = $transaction->id;
            $input_detail['product_id'] = $value;

            if(!empty($second_variation_detail->id)){
                $input_detail['variation_id'] = $variation_detail->id;
                $input_detail['second_variation_id'] = $second_variation_detail->id;
                $input_detail['unit_weight'] = $second_variation_detail->variation_weight;
                $input_detail['unit_price'] = (!empty($second_variation_detail->variation_special_price)) ? $second_variation_detail->variation_special_price : $second_variation_detail->variation_price;
                $input_detail['sub_category'] = $second_variation_detail->variation_name;
                $input_detail['get_pv'] = $second_variation_detail->variation_get_pv;

                $grand_total += (!empty($second_variation_detail->variation_special_price)) ? $second_variation_detail->variation_special_price * $request->quantity[$key] : $second_variation_detail->variation_price * $request->quantity[$key];
                $weight_total += $second_variation_detail->variation_weight * $request->quantity[$key];
            }elseif(!empty($variation_detail->id)){
                $input_detail['variation_id'] = $variation_detail->id;
                $input_detail['unit_weight'] = $variation_detail->variation_weight;
                $input_detail['unit_price'] = (!empty($variation_detail->variation_special_price)) ? $variation_detail->variation_special_price : $variation_detail->variation_price;
                $input_detail['sub_category'] = $variation_detail->variation_name;
                $input_detail['get_pv'] = $variation_detail->variation_get_pv;

                $grand_total += (!empty($variation_detail->variation_special_price)) ? $variation_detail->variation_special_price * $request->quantity[$key] : $variation_detail->variation_price * $request->quantity[$key];
                $weight_total += $variation_detail->variation_weight * $request->quantity[$key];


            }else{
              $input_detail['unit_weight'] = $product_detail->weight;
              $input_detail['unit_price'] = (!empty($product_detail->customer_special_price)) ? $product_detail->customer_special_price : $product_detail->customer_price;
              $input_detail['get_pv'] = $product_detail->get_pv;

              $grand_total += (!empty($product_detail->customer_special_price)) ? $product_detail->customer_special_price * $request->quantity[$key] : $product_detail->customer_price * $request->quantity[$key];
              $weight_total += $product_detail->weight * $request->quantity[$key];
            }

            $input_detail['product_name'] = $product_detail->product_name;
            $input_detail['item_code'] = $product_detail->item_code;
            $input_detail['product_code'] = $product_detail->product_code;
            $input_detail['quantity'] = $request->quantity[$key];
            $input_detail['product_image'] = $product_detail->image;
            
            $agent_price_variations = AgentPrice::where('agent_lvl_id', Auth::user()->lvl);
            if(!empty($second_variation_detail->id)){
            $agent_price_variations = $agent_price_variations->where('second_variation_id', $second_variation_detail->id);
            }elseif(!empty($variation_detail->id)){
            $agent_price_variations = $agent_price_variations->where('variation_id', $variation_detail->id);
            }else{
            $agent_price_variations = $agent_price_variations->where('product_id', $product_detail->id);
            }
            $agent_price_variations = $agent_price_variations->first();
          
            if(!empty($agent_price_variations->special_price)){
                $deduct_total += $agent_price_variations->special_price * $request->quantity[$key];
                $input_detail['deduct_unit_price'] = $agent_price_variations->special_price;
            }else{
                $deduct_total += $agent_price_variations->price * $request->quantity[$key];
                $input_detail['deduct_unit_price'] = $agent_price_variations->price;
            }
            

            $detail = TransactionDetail::create($input_detail);

        }

        if($deduct_total > 0){
            $deduct_total = $deduct_total;
        }else{
            $deduct_total = $grand_total;
        }

        $totalshipping_fees = 0;

        if(!empty($shipping_address)){
            if($shipping_address->state > 16){
                $shipping_fees = SettingShippingFee::where('area', 'sg')
                                                   ->where('weight', '<=', ceil($weight_total))
                                                   ->orderBy('weight', 'desc')
                                                   ->first();
                if(!empty($shipping_fees->id)){
                    $totalshipping_fees = $shipping_fees->shipping_fee;                
                }
            }elseif($shipping_address->state != '11' && $shipping_address->state != '12' && $shipping_address->state != '15'){
              
                $shipping_fees = SettingShippingFee::where('area', 'west')
                                                 ->where('weight', '<=', ceil($weight_total))
                                                 ->orderBy('weight', 'desc')
                                                 ->first();
                if(!empty($shipping_fees->id)){
                    $totalshipping_fees = $shipping_fees->shipping_fee;
                }

            }else{
                $shipping_fees = SettingShippingFee::where('area', 'east')
                                                   ->where('weight', '<=', ceil($weight_total))
                                                   ->orderBy('weight', 'desc')
                                                   ->first();
                if(!empty($shipping_fees->id)){
                    $totalshipping_fees = $shipping_fees->shipping_fee;                
                }
            }
        }

        $update = Transaction::find($transaction->id);

        $details = TransactionDetail::where('transaction_id', $transaction->id)->get();

        // foreach($details as $detail){
        //     $get_upline = Merchant::where('code', Auth::user()->code)
        //                           ->where('status', '1')
        //                           ->first();
        //     if(!empty($get_upline->id)){

        //         $get_upline_price = AgentPrice::where('product_id', $detail->product_id)
        //                                       ->where('agent_lvl_id', $get_upline->lvl);
        //         if(!empty($detail->second_variation_id)){
        //         $get_upline_price = $get_upline_price->where('second_variation_id', $detail->second_variation_id);
        //         }
        //         if(!empty($detail->variation_id)){
        //         $get_upline_price = $get_upline_price->where('variation_id', $detail->variation_id);
        //         }
        //         $get_upline_price = $get_upline_price->first();

        //         $upline_price = !empty($get_upline_price->special_price) ? $get_upline_price->special_price : $get_upline_price->price;

        //         $upline_price = $upline_price * $detail->quantity;

        //         $ori_price = $detail->unit_price * $detail->quantity;

        //         $totalSpread = $ori_price - $upline_price;
        //         // echo $upline_price.' - '.$ori_price;
        //         // echo "\n";
        //         if($totalSpread > 0 && $upline_price > 0){
        //             if(!empty($detail->second_variation_id)){
        //                 $product_name = $detail->product_name.' - '.$detail->sub_category.' - '.$detail->second_sub_category;
        //             }elseif(!empty($detail->variation_id)){
        //                 $product_name = $detail->product_name.' - '.$detail->sub_category;
        //             }else{
        //                 $product_name = $detail->product_name;
        //             }
        //             $input_spread = [];
        //             $input_spread['type'] = 1;
        //             $input_spread['user_id'] = $get_upline->code;
        //             $input_spread['transaction_no'] = $transaction->transaction_no;
        //             $input_spread['product_name'] = $product_name;
        //             $input_spread['product_qty'] = $detail->quantity;
        //             $input_spread['product_amount'] = $ori_price;
        //             $input_spread['comm_pa_type'] = "Amount";
        //             $input_spread['comm_pa'] = $totalSpread;
        //             $input_spread['comm_amount'] = $totalSpread;
        //             $input_spread['comm_desc'] = "Downline Spread Bonus";

        //             AffiliateCommission::create($input_spread);
        //         }
        //     }
        // }

        $update = $update->update(['weight'=>$weight_total,
                                   'sub_total'=>$grand_total,
                                   'shipping_fee'=>$totalshipping_fees,
                                   'grand_total'=>$grand_total+$totalshipping_fees,
                                   'deduct_amount'=>$deduct_total+$totalshipping_fees]);


        $get_details = TransactionDetail::where('transaction_id', $transaction->id)->get();

        foreach($get_details as $get_detail){
            $PackageItems = PackageItem::where('product_id', $get_detail->product_id)
                                       ->get();
                                       
            foreach($PackageItems as $PackageItem){
                $input_transaction_packages = [];
                $input_transaction_packages['detail_id'] = $get_detail->id;
                $input_transaction_packages['product_id'] = $PackageItem->products;
                $input_transaction_packages['variation_id'] = $PackageItem->variation_id;
                $input_transaction_packages['second_variation_id'] = $PackageItem->second_variation_id;
                $input_transaction_packages['quantity']  = $PackageItem->qty;

                TransactionPackage::create($input_transaction_packages);                            
            }
        }

        if($request->cash_wallet == 1){
            $isMerchant = Merchant::where('code', $transaction->user_id)
                                      ->where('status', '1')
                                      ->first();

            $isUser = User::where('code', $transaction->user_id)
                              ->where('status', '1')
                              ->first();

            $details = TransactionDetail::where('transaction_id', $transaction->id)->get();
            foreach($details as $detail){
                $get_upline = Merchant::where('code', $isUser->master_id)
                                      ->where('status', '1')
                                      ->first();
                if(!empty($get_upline->id)){

                    $get_upline_price = AgentPrice::where('product_id', $detail->product_id)
                                                  ->where('agent_lvl_id', $get_upline->lvl);
                    if(!empty($detail->second_variation_id)){
                    $get_upline_price = $get_upline_price->where('second_variation_id', $detail->second_variation_id);
                    }
                    if(!empty($detail->variation_id)){
                    $get_upline_price = $get_upline_price->where('variation_id', $detail->variation_id);
                    }
                    $get_upline_price = $get_upline_price->first();
                    if(!empty($get_upline_price->id)){
                        $upline_price = !empty($get_upline_price->special_price) ? $get_upline_price->special_price : $get_upline_price->price;

                        $upline_price = $upline_price * $detail->quantity;

                        $ori_price = $detail->unit_price * $detail->quantity;

                        $totalSpread = $ori_price - $upline_price;

                        if($totalSpread > 0 && $upline_price > 0){
                            if(!empty($detail->second_variation_id)){
                                $product_name = $detail->product_name.' - '.$detail->sub_category.' - '.$detail->second_sub_category;
                            }elseif(!empty($detail->variation_id)){
                                $product_name = $detail->product_name.' - '.$detail->sub_category;
                            }else{
                                $product_name = $detail->product_name;
                            }
                            $input_spread = [];
                            $input_spread['type'] = 1;
                            $input_spread['user_id'] = $get_upline->code;
                            $input_spread['user_by'] = $transaction->user_id;
                            $input_spread['transaction_no'] = $transaction->transaction_no;
                            $input_spread['product_name'] = $product_name;
                            $input_spread['product_qty'] = $detail->quantity;
                            $input_spread['product_amount'] = $ori_price;
                            $input_spread['comm_pa_type'] = "Amount";
                            $input_spread['comm_pa'] = $totalSpread;
                            $input_spread['comm_amount'] = $totalSpread;
                            $input_spread['comm_desc'] = "Downline Spread Bonus";

                            AffiliateCommission::create($input_spread);
                        }
                    }
                }
            }
        }

        $this->AgentUpgrade();


        Toastr::success("Transaction Create Successfully!");
        return redirect()->route('profile');
    }

    public function createAgentTransaction()
    {
        $lvl = "";
        $agentLVL = AgentLevel::find(Auth::user()->lvl);
        if(!empty($agentLVL->id)){
            $lvl = $agentLVL->agent_lvl;
        }

        $downlines = Merchant::where('master_id', Auth::user()->code)
                             ->where('status', '1')
                             ->get();

        $products = Product::select('products.*')
                           ->where('products.status', '1')
                           ->whereNull('products.upgrade_agent')
                           ->whereNull('products.mall')
                           ->get();

        $getMUpline = Merchant::where('code', Auth::user()->master_id)->first();
        $getAUpline = Admin::where('code', Auth::user()->master_id)->first();

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

        $GetCashWalletBalance = $this->GetCashWalletBalance();

        $states = State::get();

        $totalCashBalance = $this->GetCashWalletBalance();

        return view('frontend.create_agent_transaction', ['lvl'=>$lvl, 'downlines'=>$downlines, 'products'=>$products,
                                                          'upline_name'=>$upline_name,
                                                          'upline_code'=>$upline_code,
                                                          'GetCashWalletBalance'=>$GetCashWalletBalance,
                                                          'totalCashBalance'=>$totalCashBalance,
                                                          'states'=>$states]);
    }

    public function SaveAgentTransaction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'merchants' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }

        // $GetProductWalletBalance = $this->GetProductWalletBalance($request->merchants);

        // if($request->totalDeduct > $GetProductWalletBalance){
        //     return Redirect::back()->withInput($request->all())->withErrors("Insufficient balance");
        // }

        //Check Stock Balance
        $array = [];
        $v_array = [];
        $v_two_array = [];
        $totalProductQty = [];
        $totalVariationQty = [];
        $totalSecondVariationQty = [];

        $b = 0;
        for($a=0; $a<count($request->product_id); $a++){
            $b++;
            $check_product_detail = Product::find($request->product_id[$a]);
            if(empty($request->product_id[$a])){
                return Redirect::back()->withInput($request->all())->withErrors("Please select at least one product.");
            }

            if(!empty($request->product_id[$a]) && empty($request->quantity[$a])){
                return Redirect::back()->withInput($request->all())->withErrors("Please fill in quantity");
            }
            if(!empty($request->product_id[$a]) && !empty($request->quantity[$a])){
                if(!empty($request['product_second_variation_option'.$b])){
                    if(!empty($v_two_array)){
                        if(in_array($request['product_second_variation_option'.$b], $v_two_array)){
                            $totalSecondVariationQty[$request['product_second_variation_option'.$b]] = $totalSecondVariationQty[$request['product_second_variation_option'.$b]] + $request->quantity[$a];
                        }else{
                            array_push($v_two_array, $request['product_second_variation_option'.$b]);
                            $totalSecondVariationQty[$request['product_second_variation_option'.$b]] = $request->quantity[$a];  
                        }
                    }else{
                        array_push($v_two_array, $request['product_second_variation_option'.$b]);
                        $totalSecondVariationQty[$request['product_second_variation_option'.$b]] = $request->quantity[$a];
                    }
                }elseif(!empty($request['product_variation'.$b])){
                    if(!empty($v_array)){
                        if(in_array($request['product_variation'.$b], $v_array)){
                            $totalVariationQty[$request['product_variation'.$b]] = $totalVariationQty[$request['product_variation'.$b]] + $request->quantity[$a];
                        }else{
                            array_push($v_array, $request['product_variation'.$b]);
                            $totalVariationQty[$request['product_variation'.$b]] = $request->quantity[$a];  
                        }
                    }else{
                        array_push($v_array, $request['product_variation'.$b]);
                        $totalVariationQty[$request['product_variation'.$b]] = $request->quantity[$a];
                    }
                }else{
                    if(!empty($array)){
                        if(in_array($request->product_id[$a], $array)){
                            $totalProductQty[$request->product_id[$a]] = $totalProductQty[$request->product_id[$a]] + $request->quantity[$a];
                        }else{
                            array_push($array, $request->product_id[$a]);
                            $totalProductQty[$request->product_id[$a]] = $request->quantity[$a];  
                        }
                    }else{
                        array_push($array, $request->product_id[$a]);
                        $totalProductQty[$request->product_id[$a]] = $request->quantity[$a];
                    }
                }
            }
        }

        $exceedBalanceProduct = [];
        foreach($array as $product_id){
            if($check_product_detail->packages != '1'){
                $checkStock = $this->BalanceQuantity($product_id);
                if($totalProductQty[$product_id] > $checkStock){
                    $exceedBalanceProduct[] = $product_id;
                }
            }
        }

        // exit();

        $exceedBalanceVariation = [];
        foreach($v_array as $variation_id){
            
            $checkVStock = $this->VariationBalanceQuantity($variation_id);
            if($totalVariationQty[$variation_id] > $checkVStock){
                $exceedBalanceVariation[] = $variation_id;
            }
        }
        // print_r($v_two_array);
        // exit();
        $exceedBalanceSecondVariation = [];
        foreach($v_two_array as $second_variation_id){
            
            $checkVStock = $this->SecondVariationBalanceQuantity($second_variation_id);
            if($totalSecondVariationQty[$second_variation_id] > $checkVStock){
                $exceedBalanceSecondVariation[] = $second_variation_id;
            }
        }



        if(!empty($exceedBalanceProduct)){
            $getProduct = Product::whereIn('id', $exceedBalanceProduct)->get();
            $totalProd = [];
            foreach($getProduct as $prod){
                $totalProd[] = $prod->product_name;
            }

            $im = implode(", ", $totalProd);

            return Redirect::back()->withInput($request->all())->withErrors($im." Stock Balance not enough");
        }

        // print_r($exceedBalanceVariation);
        // exit();


        if(!empty($exceedBalanceVariation)){
            $getProduct = ProductVariation::whereIn('id', $exceedBalanceVariation)->get();
            $totalVarian = [];
            foreach($getProduct as $prod){
                $totalVarian[] = $prod->variation_name;
            }

            $imv = implode(", ", $totalVarian);

            return Redirect::back()->withInput($request->all())->withErrors($imv." Stock Balance not enough");
        }


        if(!empty($exceedBalanceSecondVariation)){
            $getProduct = ProductSecondVariation::whereIn('id', $exceedBalanceSecondVariation)->get();
            $totalSecondVarian = [];
            foreach($getProduct as $prod){
                $totalSecondVarian[] = $prod->variation_name;
            }

            $imv_two = implode(", ", $totalSecondVarian);

            return Redirect::back()->withInput($request->all())->withErrors($imv_two." Stock Balance not enough");
        }


        $use_new = isset($request->use_new) ? '1' : '0';
        if($use_new == 1){
            $user_shipping = UserShippingAddress::where('user_id', $request->merchants)
                                                ->where('default', '1')
                                                ->first();
            if(!empty($user_shipping->id)){
                UserShippingAddress::where('user_id', $user_shipping->user_id)->update(['default'=>null]);
            }

            $validator = Validator::make($request->all(), [
                'f_name' => 'required',
                'phone' => 'required',
                'address' => 'required',
                'city' => 'required',
                'postcode' => 'required',
                'state' => 'required',
            ]);

            if ($validator->fails()) {
                return Redirect::back()->withInput($request->all())->withErrors($validator);
            }

            $input = $request->all();
            $input['user_id'] = $request->merchants;
            $input['default'] = '1';
            $create_shipping_address = UserShippingAddress::create($input);
        }

        $shipping_address = UserShippingAddress::where('user_id', $request->merchants)
                                            ->where('default', '1')
                                            ->first();

        $input = $request->all();
        $input['user_id'] = $request->merchants;
        $input['transaction_no'] = $this->GenerateTransactionNo();
        $input['created_by'] = Auth::user()->code;
        $input['deduct_wallet'] = 1;
        $input['address_name'] = $shipping_address->f_name;
        $input['address'] = $shipping_address->address;
        $input['postcode'] = $shipping_address->postcode;
        $input['city'] = $shipping_address->city;
        $input['state'] = $shipping_address->state;
        $input['phone'] = $shipping_address->phone;
        $input['email'] = $shipping_address->email;
        if($request->cdm == 1){
            $files = $request->file('bank_slip'); 
            $name = $files->getClientOriginalName();
            $exp = explode(".", $name);
            $file_ext = end($exp);
            $name = md5($name.date('Y-m-d H:i:s')).'.'.$file_ext;
            $files->move(GlobalController::get_image_path("uploads/bank_slip/".$request->merchants."/"), $name);

            $input['cdm_bank_id'] = $request->cdm_bank_id;
            $input['bank_slip'] = "uploads/bank_slip/".$request->merchants."/".$name;
            $input['status'] = '98';
            $input['cod_address'] = "0";
        }elseif($request->cash_wallet == 1){
            $input['mall'] = '1';
            $input['status'] = '1';
            $input['cod_address'] = "0";
        }

        $transaction = Transaction::create($input);

        $get_merchant = Merchant::where('code', $request->merchants)->first();

        $num = 0;
        $grand_total = 0;
        $deduct_total = 0;
        $weight_total = 0;
        foreach($request->product_id as $key => $value){
            $num++;
            $product_detail = Product::select('i.image', 'products.*')
                                     ->leftJoin('product_images as i', 'i.product_id', 'products.id')
                                     ->where('products.id', $value)
                                     ->first();

            $agent_pricing = AgentPrice::where('product_id', $value)
                                       ->where('agent_lvl_id', $get_merchant->lvl);
            if(!empty($request['product_variation'.$num])){
               $variation_detail = ProductVariation::find($request['product_variation'.$num]);
               $agent_pricing = $agent_pricing->where('variation_id', $request['product_variation'.$num]);
            }

            if(!empty($request['second_variation_option'.$num])){
               $second_variation_detail = ProductSecondVariation::find($request['second_variation_option'.$num]);
               $agent_pricing = $agent_pricing->where('variation_id', $request['second_variation_option'.$num]);
            }

            $agent_pricing = $agent_pricing->first();

            $input_detail = [];
            $input_detail['transaction_id'] = $transaction->id;
            $input_detail['product_id'] = $value;

            if(!empty($second_variation_detail->id)){
                $input_detail['variation_id'] = $variation_detail->id;
                $input_detail['second_variation_id'] = $second_variation_detail->id;
                $input_detail['unit_weight'] = $second_variation_detail->variation_weight;
                $input_detail['unit_price'] = (!empty($agent_pricing->special_price)) ? $agent_pricing->special_price : $agent_pricing->price;
                $input_detail['sub_category'] = $second_variation_detail->variation_name;
                $input_detail['get_pv'] = $second_variation_detail->variation_get_pv;

                $grand_total += (!empty($agent_pricing->special_price)) ? $agent_pricing->special_price * $request->quantity[$key] : $agent_pricing->price * $request->quantity[$key];
                $weight_total += $variation_detail->variation_weight * $request->quantity[$key];
            }elseif(!empty($variation_detail->id)){
                $input_detail['variation_id'] = $variation_detail->id;
                $input_detail['unit_weight'] = $variation_detail->variation_weight;
                $input_detail['unit_price'] = (!empty($agent_pricing->special_price)) ? $agent_pricing->special_price : $agent_pricing->price;
                $input_detail['sub_category'] = $variation_detail->variation_name;
                $input_detail['get_pv'] = $variation_detail->variation_get_pv;

                $grand_total += (!empty($agent_pricing->special_price)) ? $agent_pricing->special_price * $request->quantity[$key] : $agent_pricing->price * $request->quantity[$key];
                $weight_total += $variation_detail->variation_weight * $request->quantity[$key];


            }else{
              $input_detail['unit_weight'] = $product_detail->weight;
              $input_detail['unit_price'] = (!empty($agent_pricing->special_price)) ? $agent_pricing->special_price : $agent_pricing->price;
              $input_detail['get_pv'] = $product_detail->get_pv;

              $grand_total += (!empty($agent_pricing->special_price)) ? $agent_pricing->special_price * $request->quantity[$key] : $agent_pricing->price * $request->quantity[$key];
              $weight_total += $product_detail->weight * $request->quantity[$key];
            }

            $input_detail['product_name'] = $product_detail->product_name;
            $input_detail['item_code'] = $product_detail->item_code;
            $input_detail['product_code'] = $product_detail->product_code;
            $input_detail['quantity'] = $request->quantity[$key];
            $input_detail['product_image'] = $product_detail->image;
            
            

            $detail = TransactionDetail::create($input_detail);

        }

        $totalshipping_fees = 0;

        if(!empty($shipping_address)){
            if($shipping_address->state > 16){
                $shipping_fees = SettingShippingFee::where('area', 'sg')
                                                   ->where('weight', '<=', ceil($weight_total))
                                                   ->orderBy('weight', 'desc')
                                                   ->first();
                if(!empty($shipping_fees->id)){
                    $totalshipping_fees = $shipping_fees->shipping_fee;                
                }
            }elseif($shipping_address->state != '11' && $shipping_address->state != '12' && $shipping_address->state != '15'){
              
                $shipping_fees = SettingShippingFee::where('area', 'west')
                                                 ->where('weight', '<=', ceil($weight_total))
                                                 ->orderBy('weight', 'desc')
                                                 ->first();
                if(!empty($shipping_fees->id)){
                    $totalshipping_fees = $shipping_fees->shipping_fee;
                }

            }else{
                $shipping_fees = SettingShippingFee::where('area', 'east')
                                                   ->where('weight', '<=', ceil($weight_total))
                                                   ->orderBy('weight', 'desc')
                                                   ->first();
                if(!empty($shipping_fees->id)){
                    $totalshipping_fees = $shipping_fees->shipping_fee;                
                }
            }
        }

        $update = Transaction::find($transaction->id);

        $details = TransactionDetail::where('transaction_id', $transaction->id)->get();

        // foreach($details as $detail){
        //     $get_upline = Merchant::where('code', Auth::user()->code)
        //                           ->where('status', '1')
        //                           ->first();
        //     if(!empty($get_upline->id)){

        //         $get_upline_price = AgentPrice::where('product_id', $detail->product_id)
        //                                       ->where('agent_lvl_id', $get_upline->lvl);
        //         if(!empty($detail->second_variation_id)){
        //         $get_upline_price = $get_upline_price->where('second_variation_id', $detail->second_variation_id);
        //         }
        //         if(!empty($detail->variation_id)){
        //         $get_upline_price = $get_upline_price->where('variation_id', $detail->variation_id);
        //         }
        //         $get_upline_price = $get_upline_price->first();

        //         $upline_price = !empty($get_upline_price->special_price) ? $get_upline_price->special_price : $get_upline_price->price;

        //         $upline_price = $upline_price * $detail->quantity;

        //         $ori_price = $detail->unit_price * $detail->quantity;

        //         $totalSpread = $ori_price - $upline_price;
        //         // echo $upline_price.' - '.$ori_price;
        //         // echo "\n";
        //         if($totalSpread > 0 && $upline_price > 0){
        //             if(!empty($detail->second_variation_id)){
        //                 $product_name = $detail->product_name.' - '.$detail->sub_category.' - '.$detail->second_sub_category;
        //             }elseif(!empty($detail->variation_id)){
        //                 $product_name = $detail->product_name.' - '.$detail->sub_category;
        //             }else{
        //                 $product_name = $detail->product_name;
        //             }
        //             $input_spread = [];
        //             $input_spread['type'] = 1;
        //             $input_spread['user_id'] = $get_upline->code;
        //             $input_spread['transaction_no'] = $transaction->transaction_no;
        //             $input_spread['product_name'] = $product_name;
        //             $input_spread['product_qty'] = $detail->quantity;
        //             $input_spread['product_amount'] = $ori_price;
        //             $input_spread['comm_pa_type'] = "Amount";
        //             $input_spread['comm_pa'] = $totalSpread;
        //             $input_spread['comm_amount'] = $totalSpread;
        //             $input_spread['comm_desc'] = "Downline Spread Bonus";

        //             AffiliateCommission::create($input_spread);
        //         }
        //     }
        // }

        $update = $update->update(['weight'=>$weight_total,
                                   'sub_total'=>$grand_total,
                                   'shipping_fee'=>$totalshipping_fees,
                                   'grand_total'=>$grand_total+$totalshipping_fees]);

        


        $get_details = TransactionDetail::where('transaction_id', $transaction->id)->get();

        foreach($get_details as $get_detail){
            $PackageItems = PackageItem::where('product_id', $get_detail->product_id)
                                       ->get();
                                       
            foreach($PackageItems as $PackageItem){
                $input_transaction_packages = [];
                $input_transaction_packages['detail_id'] = $get_detail->id;
                $input_transaction_packages['product_id'] = $PackageItem->products;
                $input_transaction_packages['variation_id'] = $PackageItem->variation_id;
                $input_transaction_packages['second_variation_id'] = $PackageItem->second_variation_id;
                $input_transaction_packages['quantity']  = $PackageItem->qty;

                TransactionPackage::create($input_transaction_packages);                            
            }
        }

        if($request->cash_wallet == 1){
            $isMerchant = Merchant::where('code', $transaction->user_id)
                                      ->where('status', '1')
                                      ->first();

            $isUser = User::where('code', $transaction->user_id)
                              ->where('status', '1')
                              ->first();

            $details = TransactionDetail::where('transaction_id', $transaction->id)->get();
            foreach($details as $detail){
                $get_upline = Merchant::where('code', $isMerchant->master_id)
                                      ->where('status', '1')
                                      ->first();
                if(!empty($get_upline->id)){
                    if($get_upline->lvl > $isMerchant->lvl){

                        $get_upline_price = AgentPrice::where('product_id', $detail->product_id)
                                                      ->where('agent_lvl_id', $get_upline->lvl);
                        if(!empty($detail->second_variation_id)){
                        $get_upline_price = $get_upline_price->where('second_variation_id', $detail->second_variation_id);
                        }
                        if(!empty($detail->variation_id)){
                        $get_upline_price = $get_upline_price->where('variation_id', $detail->variation_id);
                        }
                        $get_upline_price = $get_upline_price->first();

                        $upline_price = !empty($get_upline_price->special_price) ? $get_upline_price->special_price : $get_upline_price->price;

                        $upline_price = $upline_price * $detail->quantity;

                        $ori_price = $detail->unit_price * $detail->quantity;

                        $totalSpread = $ori_price - $upline_price;

                        if($totalSpread > 0 && $upline_price > 0){
                            if(!empty($detail->second_variation_id)){
                                $product_name = $detail->product_name.' - '.$detail->sub_category.' - '.$detail->second_sub_category;
                            }elseif(!empty($detail->variation_id)){
                                $product_name = $detail->product_name.' - '.$detail->sub_category;
                            }else{
                                $product_name = $detail->product_name;
                            }
                            $input_spread = [];
                            $input_spread['type'] = 1;
                            $input_spread['user_id'] = $get_upline->code;
                            $input_spread['user_by'] = $transaction->user_id;
                            $input_spread['buyer_lvl'] = $isMerchant->lvl;
                            $input_spread['refferer_lvl'] = $get_upline->lvl;
                            $input_spread['transaction_no'] = $transaction->transaction_no;
                            $input_spread['product_name'] = $product_name;
                            $input_spread['product_qty'] = $detail->quantity;
                            $input_spread['product_amount'] = $ori_price;
                            $input_spread['comm_pa_type'] = "Amount";
                            $input_spread['comm_pa'] = $totalSpread;
                            $input_spread['comm_amount'] = $totalSpread;
                            $input_spread['comm_desc'] = "Downline Spread Bonus";

                            AffiliateCommission::create($input_spread);
                        }
                    }
                }
            }
        }

        $this->AgentUpgrade();

        Toastr::success("Transaction Create Successfully!");
        return redirect()->route('profile');
    }

    public function DownlineTransaction()
    {
        
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

        $transactions = Transaction::select('transactions.*', DB::raw('COALESCE(m.username, u.username) as m_username'))
                                   ->leftJoin('merchants as m', 'm.code', 'transactions.user_id')
                                   ->leftJoin('users as u', 'u.code', 'transactions.user_id')
                                   ->where('transactions.status', '!=', '55')
                                   ->where('transactions.created_by', Auth::user()->code)
                                   ->orderBy('transactions.created_at', 'desc')
                                   ->get();

        $details = [];
        foreach($transactions as $transaction){
            $details[$transaction->id] = TransactionDetail::where('transaction_id', $transaction->id)->get();
        }

        $lvl = "";
        $agentLVL = AgentLevel::find(Auth::user()->lvl);
        if(!empty($agentLVL->id)){
          $lvl = $agentLVL->agent_lvl;
        }

        $getMUpline = Merchant::where('code', Auth::user()->master_id)->first();
        $getAUpline = Admin::where('code', Auth::user()->master_id)->first();

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

        return view('frontend.downline_orders', ['transactions'=>$transactions,
                                                          'endDate'=>$endDate, 'startDate'=>$startDate,
                                                          'lvl'=>$lvl, 'upline_name'=>$upline_name, 
                                                          'upline_code'=>$upline_code],
                                                          compact('details'));
    }

    public function AgentUpgrade()
    {
        $merchants = Merchant::where('status', '1')
                             ->get();

        foreach($merchants as $merchant){
            $get_sales = $this->get_sales($merchant->code);
            $agent_levels = AgentLevel::where('minimum_purchase', '<=', $get_sales)
                                      ->orderBy('minimum_purchase', 'desc')
                                      ->first();

            if(!empty($agent_levels->id)){
                if($agent_levels->id == 3 && $merchant->lvl == 2){
                    if($merchant->lvl < $agent_levels->id){
                        Merchant::where('code', $merchant->code)->update(['lvl'=>$agent_levels->id]);
                    }
                }elseif($agent_levels->id == 4 && $merchant->lvl == 3){
                    if($merchant->lvl < $agent_levels->id){
                        Merchant::where('code', $merchant->code)->update(['lvl'=>$agent_levels->id]);
                    }                  
                }
            }
        }
    }

    public function get_sales($code)
    {
        $total_sales = 0;
        $own_sales = 0;

        $owntransaction = Transaction::select(DB::raw('SUM(IF(p.packages = 1, (tp.quantity * d.quantity), d.quantity) ) as totalQuantity'))
                                     ->join('transaction_details as d', 'd.transaction_id', 'transactions.id')
                                     ->join('products as p', 'p.id', 'd.product_id')
                                     ->leftJoin('transaction_packages as tp', 'tp.detail_id', 'd.id')
                                     ->where('transactions.status', '1')
                                     ->where('transactions.user_id', $code)
                                     ->first();

        $own_sales = $owntransaction->totalQuantity;

        $downlines = Merchant::where('master_id', $code)
                             ->where('status', '1')
                             ->get();

        $downlines_sales = 0;
        foreach($downlines as $downline){

            $downtransaction = Transaction::select(DB::raw('SUM(IF(p.packages = 1, (tp.quantity * d.quantity), d.quantity) ) as totalQuantity'))
                                     ->join('transaction_details as d', 'd.transaction_id', 'transactions.id')
                                     ->join('products as p', 'p.id', 'd.product_id')
                                     ->leftJoin('transaction_packages as tp', 'tp.detail_id', 'd.id')
                                     ->where('transactions.status', '1')
                                     ->where('transactions.user_id', $downline->code)
                                     ->first();

            $downlines_sales += $downtransaction->totalQuantity;
        }

        $users = User::where('master_id', $code)
                         ->where('status', '1')
                         ->get();

        $customer_sales = 0;
        foreach($users as $user){

            $custransaction = Transaction::select(DB::raw('SUM(IF(p.packages = 1, (tp.quantity * d.quantity), d.quantity) ) as totalQuantity'))
                                     ->join('transaction_details as d', 'd.transaction_id', 'transactions.id')
                                     ->join('products as p', 'p.id', 'd.product_id')
                                     ->leftJoin('transaction_packages as tp', 'tp.detail_id', 'd.id')
                                     ->where('transactions.status', '1')
                                     ->where('transactions.user_id', $user->code)
                                     ->first();

            $customer_sales += $custransaction->totalQuantity;
        }

        $total_sales = $own_sales + $downlines_sales + $customer_sales;

        return $total_sales;
    }
}
