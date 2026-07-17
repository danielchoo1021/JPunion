<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Merchant;
use App\User;
use App\Admin;
use App\Agent;
use App\Affiliate;
use App\AgentLevel;
use App\AgentLevelRecord;
use App\AdjustPointWallet;
use App\BankAccount;
use App\SettingShippingFee;
use App\Product;
use App\Stock;
use App\Cart;
use App\TransactionDetail;
use App\ProductVariation;
use App\ProductSecondVariation;
use App\Category;
use App\PackageItem;
use App\Promotion;

use App\Transaction;
use App\SettingPerformanceMain;
use App\SettingPerformanceDividend;
use App\AffiliateCommission;
use App\WebsiteSetting;
use App\SettingPrizePool;
use App\SettingTeamDividend;
use App\SettingRefferalReward;
use App\SettingMerchantRebate;
use App\SettingMerchantCommission;
use App\TopupTransaction;
use App\AdjustTopupWallet;

use App\AddOnDealSubItem;
use App\AppliedPromotion;
use App\TblCountry;
use App\WithdrawalTransaction;
use App\AdjustCashWallet;
use App\UserShippingAddress;
use App\FlashSale;
use App\FlashSaleProductDetail;
use App\FlashSaleProductPrice;
use App\WithdrawalStockDetail;
use App\CartLink;
use App\CartLinkProductDetail;
use App\TopupPv;
use App\SettingTopup;
use App\AdjustVoucher;
use App\AdjustCashToTopup;

use App\SettingPrizePoolCondition;
use App\SoldQuantityAdjustment;
use DB, Auth, Toastr,DateTime;

class GlobalController extends Controller
{
    public static function MerchantCode()
    {
        $merchant_code = "M";
        $user = Merchant::select(DB::raw("COUNT(id) AS totalUser"))->first();
        $totalCount = $user->totalUser + 1;

        if(strlen($totalCount) == '1'){
            $member_id = $merchant_code."00000".$totalCount;
        }elseif(strlen($totalCount) == '2'){
            $member_id = $merchant_code."0000".$totalCount;
        }elseif(strlen($totalCount) == '3'){
            $member_id = $merchant_code."000".$totalCount;
        }elseif(strlen($totalCount) == '4'){
            $member_id = $merchant_code."00".$totalCount;
        }elseif(strlen($totalCount) == '5'){
            $member_id = $merchant_code."0".$totalCount;
        }else{
            $member_id = $merchant_code."".$totalCount;
        }

        return $member_id;
    }

    public static function MerchantDisplayCode()
    {
        $merchant_code = "M";
        $user = Merchant::select(DB::raw("COUNT(id) AS totalUser"))->where('display_code', $merchant_code)->first();
        
        $totalCount = $user->totalUser + 1;

        if(strlen($totalCount) == '1'){
            $member_id = "00000".$totalCount;
        }elseif(strlen($totalCount) == '2'){
            $member_id = "0000".$totalCount;
        }elseif(strlen($totalCount) == '3'){
            $member_id = "000".$totalCount;
        }elseif(strlen($totalCount) == '4'){
            $member_id = "00".$totalCount;
        }elseif(strlen($totalCount) == '5'){
            $member_id = "0".$totalCount;
        }else{
            $member_id = $totalCount;
        }

        return array($merchant_code, $member_id);
    }

    public static function AgentCode()
    {
        $agent_code = "A";
        $user = Agent::select(DB::raw("COUNT(id) AS totalUser"))->first();
        $totalCount = $user->totalUser + 1;

        if(strlen($totalCount) == '1'){
            $member_id = $agent_code."00000".$totalCount;
        }elseif(strlen($totalCount) == '2'){
            $member_id = $agent_code."0000".$totalCount;
        }elseif(strlen($totalCount) == '3'){
            $member_id = $agent_code."000".$totalCount;
        }elseif(strlen($totalCount) == '4'){
            $member_id = $agent_code."00".$totalCount;
        }elseif(strlen($totalCount) == '5'){
            $member_id = $agent_code."0".$totalCount;
        }else{
            $member_id = $agent_code."".$totalCount;
        }

        return $member_id;
    }

    public static function AgentDisplayCode()
    {
        $agent_code = "A";
        $user = Agent::select(DB::raw("COUNT(id) AS totalUser"))->where('display_code', $agent_code)->first();
        
        $totalCount = $user->totalUser + 1;

        if(strlen($totalCount) == '1'){
            $member_id = "00000".$totalCount;
        }elseif(strlen($totalCount) == '2'){
            $member_id = "0000".$totalCount;
        }elseif(strlen($totalCount) == '3'){
            $member_id = "000".$totalCount;
        }elseif(strlen($totalCount) == '4'){
            $member_id = "00".$totalCount;
        }elseif(strlen($totalCount) == '5'){
            $member_id = "0".$totalCount;
        }else{
            $member_id = $totalCount;
        }

        return array($agent_code, $member_id);
    }

    public static function MemberCode()
    {
        $agent_code = "Mb";

        $user = User::select(DB::raw("COUNT(id) AS totalUser"))->first();
        $totalCount = $user->totalUser + 1;

        if(strlen($totalCount) == '1'){
            $member_id = $agent_code."00000".$totalCount;
        }elseif(strlen($totalCount) == '2'){
            $member_id = $agent_code."0000".$totalCount;
        }elseif(strlen($totalCount) == '3'){
            $member_id = $agent_code."000".$totalCount;
        }elseif(strlen($totalCount) == '4'){
            $member_id = $agent_code."00".$totalCount;
        }elseif(strlen($totalCount) == '5'){
            $member_id = $agent_code."0".$totalCount;
        }else{
            $member_id = $agent_code.$totalCount;
        }

        return $member_id;
    }

    public static function MemberDisplayCode()
    {
        $agent_code = "Mb";
        $user = User::select(DB::raw("COUNT(id) AS totalUser"))->where('display_code', $agent_code)->first();
        
        $totalCount = $user->totalUser + 1;

        if(strlen($totalCount) == '1'){
            $member_id = "00000".$totalCount;
        }elseif(strlen($totalCount) == '2'){
            $member_id = "0000".$totalCount;
        }elseif(strlen($totalCount) == '3'){
            $member_id = "000".$totalCount;
        }elseif(strlen($totalCount) == '4'){
            $member_id = "00".$totalCount;
        }elseif(strlen($totalCount) == '5'){
            $member_id = "0".$totalCount;
        }else{
            $member_id = $totalCount;
        }

        return array($agent_code, $member_id);
    }

    //Add Affiliate Table
    public static function add_affiliates($code, $master_id)
    {
        try{
            \DB::beginTransaction();

            if(!empty($master_id)){
                $checkmAff = Agent::where('code', $master_id)->where('status', '1')->first();
                $checkaAff = Admin::where('code', $master_id)->where('status', '1')->first();
                $checkuAff = User::where('code', $master_id)->where('status', '1')->first();
                
                if(empty($checkmAff->id) && empty($checkaAff->id) && empty($checkuAff->id)){
                    throw new \Exception("Referral Code Not Exists");
                }
            }else{
                $master_id = "AD000001";
            }

            $affiliate = new Affiliate();

            $affiliate->affiliate_id = $code;
            $affiliate->user_id = $master_id;
            $affiliate->sort_level = 1;
        
            $affiliate->save();

            $get_upline_groups = Affiliate::where('affiliate_id', $master_id)->orderBy('sort_level', 'asc')->get();

            $sort_level = 2;

            foreach($get_upline_groups as $get_upline_group){
                $affiliate_upline = new Affiliate();
                $affiliate_upline->affiliate_id = $code;
                $affiliate_upline->user_id = $get_upline_group->user_id;
                $affiliate_upline->sort_level = $sort_level;
                $sort_level++;
                $affiliate_upline->save();
            }

            \DB::commit();

            return "ok";
        }catch (\Exception $e){
            \DB::rollback();
            return $e->getMessage().' - '.$e->getLine();
        }catch(\Error $e){
            \DB::rollback();
            return $e->getMessage().' - '.$e->getLine();
        }
    }

    public static function product_item_code($category_id, $product_id = NULL)
    {

        // $category = Category::find($category_id);
        // if(empty($category->id)){
        //     return null;
        // }

        // $product = Product::select(DB::raw('COUNT(id) AS TotalCount'))
        //                   ->where('category_id', $category_id)
        //                   ->whereNotNull('item_code')
        //                   ->first();

        // $totalCount = $product->TotalCount+1;

        // $cat_code = !empty($category->code) ? $category->code : '';

        // if(strlen($totalCount) == 1){
        //     $code = $cat_code."-00".$totalCount;
        // }elseif(strlen($totalCount) == 2){
        //     $code = $cat_code."-0".$totalCount;
        // }else{
        //     $code = $cat_code.'-'.$totalCount;
        // }

        // return $code;


        $category = Category::find($category_id);
        if(empty($category->id)){
            return null;
        }

        $cat_code = !empty($category->code) ? $category->code : '';

        if (!empty($product_id)) {
            $count_previous_product_id = Product::select(DB::raw('COUNT(id) AS TotalCount'))
                                                ->where('category_id', $category_id)
                                                ->whereNotNull('item_code')
                                                ->where('id', '<', $product_id)
                                                ->first();

            $previous_count = $count_previous_product_id->TotalCount + 1;

            if(strlen($previous_count) == 1){
                $code = $cat_code."-00".$previous_count;
            }elseif(strlen($previous_count) == 2){
                $code = $cat_code."-0".$previous_count;
            }else{
                $code = $cat_code.'-'.$previous_count;
            }

            $check_if_product_exists_with_code = Product::where('item_code', $code)->first();

            if (empty($check_if_product_exists_with_code->id)) {
                return $code;
            }

            $current_product = Product::find($product_id);

            if ($code == $current_product->item_code) {
                return $code;
            }
        }

        $product = Product::select(DB::raw('COUNT(id) AS TotalCount'))
                          ->where('category_id', $category_id)
                          ->whereNotNull('item_code')
                          ->first();

        $totalCount = $product->TotalCount+1;

        if(strlen($totalCount) == 1){
            $code = $cat_code."-00".$totalCount;
        }elseif(strlen($totalCount) == 2){
            $code = $cat_code."-0".$totalCount;
        }else{
            $code = $cat_code.'-'.$totalCount;
        }

        return $code;
    }

    public static function getCurrentLogin()
    {
        if(!empty(Auth::guard('admin')->check())){
            $buyerCode = Auth::guard('admin')->user()->code;
        }elseif(!empty(Auth::guard('merchant')->check())){
            $buyerCode = Auth::guard('merchant')->user()->code;
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

        $getUserDetails = GlobalController::getUserDetails($buyerCode);

        $user = [];
        if(!empty($getUserDetails->id)){
            $user = $getUserDetails;
        }else{
            $user = ['code'=>$buyerCode, 'lvl'=>0];
        }

        return $user;
    }

    public static function getUserDetails($code)
    {
        $user = User::whereCode($code)->whereStatus(1)->first();
        if(empty($user->id)){
            $user = Merchant::whereCode($code)->whereStatus(1)->first();
            if(empty($user->id)){
                $user = Admin::whereCode($code)->whereStatus(1)->first();     
                if(empty($user->id)){
                    $user = Agent::whereCode($code)->whereStatus(1)->first();     
                }   
            }   
        }

        return $user;
    }

    public static function get_product_pricing($product_id, $code = "", $variation_id = 0, $second_variation_id = 0, $add_on_id = NULL, $buyer_level = NULL, $flash_sale_override_price = NULL)
    {
        //Check User Type / Level

        $user = User::whereCode($code)->whereStatus(1)->first();
        $agent = Agent::whereCode($code)->whereStatus(1)->first();

        $buyerLvl = 0;
        if(!empty($agent->id)){
            $buyerLvl = $agent->lvl;
        }

        $selected_product = Product::where(DB::raw('md5(products.id)'), $product_id)->first();

        $product = Product::where(DB::raw('md5(products.id)'), $product_id)
                          ->where('products.dow', '1')
                          ->where('products.status', '1');

        $user_birthday_promotion = NULL;
        if(!empty($agent->id) || !empty($buyer_level)){
            if(!empty($buyer_level)){
                $buyerLvl = $buyer_level;
            }

            $product = $product->where('agent_only', '1');
            if(!empty($agent->id)){
                $user_birthday_promotion = GlobalController::checkUserBirthMonthToday($agent->code);

                if (!empty($agent->dob)) {
                    $dob = DateTime::createFromFormat('d/m/Y', $agent->dob);

                    $birthdayMonth = $dob->format('m');
                    $currentYear = date('Y');

                    $hasBirthdayTransaction = TransactionDetail::leftJoin('transactions as t', 't.id', 'transaction_details.transaction_id')
                    ->where('t.user_id', $agent->code)
                    ->where('transaction_details.is_birthday', 1)
                    ->whereIn('t.status',[1,98])
                    ->whereMonth('t.created_at', $birthdayMonth)
                    ->whereYear('t.created_at', $currentYear)
                    ->exists();

                    if($hasBirthdayTransaction){
                        $user_birthday_promotion = null;
                    }
                }
            }

            if(!empty($user_birthday_promotion) && $user_birthday_promotion && $selected_product->birthday_promotion == '1'){
                $product = $product->with('get_agent_min_max_birthday_price_product', function($query) use($buyerLvl) {
                                $query->where('agent_lvl_id', $buyerLvl);
                           });

                if(!empty($variation_id)){
                    $product = $product->with(['get_agent_price_product' => function($gv) use($variation_id, $second_variation_id, $buyerLvl) {
                                                    $gv->where('agent_lvl_id', $buyerLvl);
                                                    $gv->where('variation_id', $variation_id);
                                                    if(!empty($second_variation_id)){
                                                        $gv->where('second_variation_id', $second_variation_id);
                                                    }
                                             }]);
                }else{
                    $product = $product->with(['get_agent_price_product' => function($qr) use($buyerLvl) {
                                                    $qr->where('agent_lvl_id', $buyerLvl);
                                             }]);
                }
            }else{
                $product = $product->with('get_agent_min_max_price_product', function($query) use($buyerLvl, $selected_product) {
                                $query->where('agent_lvl_id', $buyerLvl);
                                if(!empty($selected_product->variation_enable)){
                                    $query->whereNotNull('variation_id');
                                }else{
                                    $query->whereNull('variation_id');
                                }
                           });

                if(!empty($variation_id)){
                    $product = $product->with(['get_agent_price_product' => function($gv) use($variation_id, $second_variation_id, $buyerLvl) {
                                                    $gv->where('agent_lvl_id', $buyerLvl);
                                                    $gv->where('variation_id', $variation_id);
                                                    if(!empty($second_variation_id)){
                                                        $gv->where('second_variation_id', $second_variation_id);
                                                    }
                                             }]);
                }else{
                    $product = $product->with(['get_agent_price_product' => function($qr) use($buyerLvl) {
                                                    $qr->where('agent_lvl_id', $buyerLvl);
                                             }]);
                }
            }
        }else{

            if(!empty($variation_id)){
                $product = $product->with('get_variations_price', function($gv) use($variation_id) {
                                $gv->where('id', $variation_id);
                           });
            }
            if(!empty($second_variation_id)){
                $product = $product->with('get_second_variations_price', function($gsv) use($second_variation_id) {
                                $gsv->where('id', $second_variation_id);
                           });
            }

            if(!empty($user->id)){
                $user_birthday_promotion = GlobalController::checkUserBirthMonthToday($user->code);

                if (!empty($user->dob)) {
                    $dob = DateTime::createFromFormat('d/m/Y', $user->dob);

                    $birthdayMonth = $dob->format('m');
                    $currentYear = date('Y');

                    $hasBirthdayTransaction = TransactionDetail::leftJoin('transactions as t', 't.id', 'transaction_details.transaction_id')
                    ->where('t.user_id', $user->code)
                    ->where('transaction_details.is_birthday', 1)
                    ->whereIn('t.status',[1,98])
                    ->whereMonth('t.created_at', $birthdayMonth)
                    ->whereYear('t.created_at', $currentYear)
                    ->exists();

                    if($hasBirthdayTransaction){
                        $user_birthday_promotion = null;
                    }
                }
            }
        }

        $product = $product->first();


        $active_flash_sale = GlobalController::get_current_flash_sales();
        $flash_price = NULL;
        $flash_sale_product_price_id = NULL;
        if(!empty($active_flash_sale->id)){
            $current_active_flash_sale_product = FlashSaleProductDetail::where('flash_sale_id', $active_flash_sale->id)
                                                                       ->where(DB::raw('md5(product_id)'), $product_id);

            if(!empty($variation_id)){
                $current_active_flash_sale_product = $current_active_flash_sale_product->where('variation_id', $variation_id);
            }else{
                $current_active_flash_sale_product = $current_active_flash_sale_product->whereNull('variation_id');
            }

            if(!empty($second_variation_id)){
                $current_active_flash_sale_product = $current_active_flash_sale_product->where('second_variation_id', $second_variation_id);
            }else{
                $current_active_flash_sale_product = $current_active_flash_sale_product->whereNull('second_variation_id');
            }

            $current_active_flash_sale_product = $current_active_flash_sale_product->where('status', '1')
                                                                                   ->first();

            if(!empty($current_active_flash_sale_product->id)){
                if(!empty($agent->id)){
                    $get_flash_sale_price = FlashSaleProductPrice::where('flash_sale_product_detail_id', $current_active_flash_sale_product->id)
                                                             ->where('status', '1')
                                                             ->where('agent_lvl_id', $agent->lvl)
                                                             ->first();
                }else{
                    $get_flash_sale_price = FlashSaleProductPrice::where('flash_sale_product_detail_id', $current_active_flash_sale_product->id)
                                                             ->where('status', '1')
                                                             ->whereNull('agent_lvl_id')
                                                             ->first();
                }

                if(!empty($get_flash_sale_price->id)){
                    $flash_price = $get_flash_sale_price->price;
                }
            }
        }


        $product_price_range = 0;
        $product_special_range = 0;

        $product_price = 0;
        $product_special_price = 0;

        $upper_price_range = 0;
        $lower_price_range = 0;

        $upper_special_price_range = 0;
        $lower_special_price_range = 0;

        if(!empty($product->id)){
            if(!empty($agent->id) || !empty($buyer_level)){
                if(!empty($user_birthday_promotion) && $user_birthday_promotion && $product->birthday_promotion == '1'){

                    if($product->get_agent_min_max_birthday_price_product->min_price == $product->get_agent_min_max_birthday_price_product->max_price){
                        $product_price_range = number_format($product->get_agent_min_max_birthday_price_product->min_price, 2);



                        $lower_price_range = $product->get_agent_min_max_birthday_price_product->min_price;
                        $upper_price_range = $product->get_agent_min_max_birthday_price_product->max_price;
                    }else{
                        $product_price_range = number_format($product->get_agent_min_max_birthday_price_product->min_price, 2).' - '.number_format($product->get_agent_min_max_birthday_price_product->max_price, 2);



                        $lower_price_range = $product->get_agent_min_max_birthday_price_product->min_price;
                        $upper_price_range = $product->get_agent_min_max_birthday_price_product->max_price;
                    }

                    // $product_price = !empty($product->get_agent_price_product->birthday_special_price) ? $product->get_agent_price_product->birthday_special_price : $product->get_agent_price_product->birthday_price;
                    if(!empty($product->get_agent_price_product->birthday_special_price)){
                        $product_price = $product->get_agent_price_product->birthday_special_price;
                    }elseif(!empty($product->get_agent_price_product->birthday_price)){
                        $product_price = $product->get_agent_price_product->birthday_price;
                    }elseif(!empty($product->get_agent_price_product->special_price)){
                        $product_price = $product->get_agent_price_product->special_price;
                    }elseif(!empty($product->get_agent_price_product->price)){
                        $product_price = $product->get_agent_price_product->price;
                    }else{
                        $product_price = 0;
                    }
                    
                    //Special Pricing Slash Display
                    if(!empty($product->get_agent_min_max_birthday_price_product->min_special_price)){
                        if($product->get_agent_min_max_birthday_price_product->min_special_price == $product->get_agent_min_max_birthday_price_product->max_special_price){
                            $product_special_range = number_format($product->get_agent_min_max_birthday_price_product->min_normal_price, 2);



                            $lower_special_price_range = $product->get_agent_min_max_birthday_price_product->min_special_price;
                            $upper_special_price_range = $product->get_agent_min_max_birthday_price_product->max_special_price;
                        }else{
                            $product_special_range = number_format($product->get_agent_min_max_birthday_price_product->min_normal_price, 2).' - '.number_format($product->get_agent_min_max_birthday_price_product->max_normal_price, 2);



                            $lower_special_price_range = $product->get_agent_min_max_birthday_price_product->min_normal_price;
                            $upper_special_price_range = $product->get_agent_min_max_birthday_price_product->max_normal_price;
                        }
                    }

                    if(!empty($product->get_agent_price_product->birthday_special_price)){
                        $product_special_price = number_format($product->get_agent_price_product->birthday_price, 2);
                    }
                   
                }else{
                    if(!empty($product->get_agent_min_max_price_product->min_price) && !empty($product->get_agent_min_max_price_product->max_price)){
                        if($product->get_agent_min_max_price_product->min_price == $product->get_agent_min_max_price_product->max_price){

                            $product_price_range = number_format($product->get_agent_min_max_price_product->min_price, 2);



                            $lower_price_range = $product->get_agent_min_max_price_product->min_price;
                            $upper_price_range = $product->get_agent_min_max_price_product->max_price;
                        }else{
                            $product_price_range = number_format($product->get_agent_min_max_price_product->min_price, 2).' - '.number_format($product->get_agent_min_max_price_product->max_price, 2);



                            $lower_price_range = $product->get_agent_min_max_price_product->min_price;
                            $upper_price_range = $product->get_agent_min_max_price_product->max_price;

                            //error
                        }
                    }else{
                        $lower_price_range = 0.00;
                        $upper_price_range = 0.00;

                        if(!empty($product->get_agent_min_max_price_product->min_price)){
                            $lower_price_range = $product->get_agent_min_max_price_product->min_price;
                        }

                        if(!empty($product->get_agent_min_max_price_product->max_price)){
                            $upper_price_range = $product->get_agent_min_max_price_product->max_price;
                        }

                        if($lower_price_range == $upper_price_range){
                            $product_price_range = number_format($lower_price_range, 2);
                        }else{
                            $product_price_range = number_format($lower_price_range, 2).' - '.number_format($upper_price_range, 2);
                        }
                    }

                    // $product_price = !empty($product->get_agent_price_product->special_price) ? $product->get_agent_price_product->special_price : $product->get_agent_price_product->price;
                    if(!empty($product->get_agent_price_product->special_price)){
                        $product_price = $product->get_agent_price_product->special_price;
                    }elseif(!empty($product->get_agent_price_product->price)){
                        $product_price = $product->get_agent_price_product->price;
                    }else{
                        $product_price = 0;
                    }

                    //Special Pricing Slash Display
                    if(!empty($product->get_agent_min_max_price_product->min_special_price)){
                        if($product->get_agent_min_max_price_product->min_special_price == $product->get_agent_min_max_price_product->max_special_price){
                            $product_special_range = number_format($product->get_agent_min_max_price_product->min_normal_price, 2);



                            $lower_special_price_range = $product->get_agent_min_max_price_product->min_special_price;
                            $upper_special_price_range = $product->get_agent_min_max_price_product->max_special_price;
                        }else{
                            if(!empty($product->get_agent_min_max_price_product->min_normal_price)){ //added checking
                                $product_special_range = number_format($product->get_agent_min_max_price_product->min_normal_price, 2).' - '.number_format($product->get_agent_min_max_price_product->max_normal_price, 2);



                                $lower_special_price_range = $product->get_agent_min_max_price_product->min_normal_price;
                                $upper_special_price_range = $product->get_agent_min_max_price_product->max_normal_price;
                            }
                        }
                    }

                    if(!empty($product->get_agent_price_product->special_price)){
                        $product_special_price = number_format($product->get_agent_price_product->price, 2);
                    }

                }
            }elseif(!empty($user->id)){
                if($product->variation_enable == 1){
                    if($product->second_variation_enable == 1){
                        if(!empty($user_birthday_promotion) && $user_birthday_promotion && $product->birthday_promotion == '1'){

                            if($product->get_second_variations_min_max_birthday_price->min_price == $product->get_second_variations_min_max_birthday_price->max_price){
                                $product_price_range = number_format($product->get_second_variations_min_max_birthday_price->min_price, 2);



                                $lower_price_range = $product->get_second_variations_min_max_birthday_price->min_price;
                                $upper_price_range = $product->get_second_variations_min_max_birthday_price->max_price;
                            }else{
                                $product_price_range = number_format($product->get_second_variations_min_max_birthday_price->min_price, 2).' - '.number_format($product->get_second_variations_min_max_birthday_price->max_price, 2);



                                $lower_price_range = $product->get_second_variations_min_max_birthday_price->min_price;
                                $upper_price_range = $product->get_second_variations_min_max_birthday_price->max_price;
                            }

                            // $product_price = !empty($product->get_second_variations_price->variation_birthday_special_price) ? $product->get_second_variations_price->variation_birthday_special_price : $product->get_second_variations_price->variation_birthday_price;
                            if(!empty($product->get_second_variations_price->variation_birthday_special_price)){
                                $product_price = $product->get_second_variations_price->variation_birthday_special_price;
                            }elseif(!empty($product->get_second_variations_price->variation_birthday_price)){
                                $product_price = $product->get_second_variations_price->variation_birthday_price;
                            }elseif(!empty($product->get_second_variations_price->variation_special_price)){
                                $product_price = $product->get_second_variations_price->variation_special_price;
                            }elseif(!empty($product->get_second_variations_price->variation_price)){
                                $product_price = $product->get_second_variations_price->variation_price;
                            }else{
                                $product_price = 0;
                            }

                            //Special Pricing Slash Display
                            if(!empty($product->get_second_variations_min_max_birthday_price->min_price)){
                                if($product->get_second_variations_min_max_birthday_price->min_special_price == $product->get_second_variations_min_max_birthday_price->max_special_price){
                                    $product_special_range = number_format($product->get_second_variations_min_max_birthday_price->min_normal_price, 2);



                                    $lower_special_price_range = $product->get_second_variations_min_max_birthday_price->min_special_price;
                                    $upper_special_price_range = $product->get_second_variations_min_max_birthday_price->max_special_price;
                                }else{
                                    $product_special_range = number_format($product->get_second_variations_min_max_birthday_price->min_normal_price, 2).' - '.number_format($product->get_second_variations_min_max_birthday_price->max_normal_price, 2);



                                    $lower_special_price_range = $product->get_second_variations_min_max_birthday_price->min_normal_price;
                                    $upper_special_price_range = $product->get_second_variations_min_max_birthday_price->max_normal_price;
                                }
                            }

                            if(!empty($product->get_second_variations_price->variation_birthday_special_price)){
                                $product_special_price = number_format($product->get_second_variations_price->variation_birthday_price, 2);
                            }

                        }else{

                            if($product->get_second_variations_min_max_price->min_price == $product->get_second_variations_min_max_price->max_price){
                                $product_price_range = number_format($product->get_second_variations_min_max_price->min_price, 2);



                                $lower_price_range = $product->get_second_variations_min_max_price->min_price;
                                $upper_price_range = $product->get_second_variations_min_max_price->max_price;
                            }else{
                                $product_price_range = number_format($product->get_second_variations_min_max_price->min_price, 2).' - '.number_format($product->get_second_variations_min_max_price->max_price, 2);



                                $lower_price_range = $product->get_second_variations_min_max_price->min_price;
                                $upper_price_range = $product->get_second_variations_min_max_price->max_price;
                            }

                            // $product_price = !empty($product->get_second_variations_price->variation_special_price) ? $product->get_second_variations_price->variation_special_price : $product->get_second_variations_price->variation_price;
                            if(!empty($product->get_second_variations_price->variation_special_price)){
                                $product_price = $product->get_second_variations_price->variation_special_price;
                            }elseif(!empty($product->get_second_variations_price->variation_price)){
                                $product_price = $product->get_second_variations_price->variation_price;
                            }else{
                                $product_price = 0;
                            }


                            //Special Pricing Slash Display
                            // if(!empty($product->get_second_variations_min_max_price->min_price)){
                            if(!empty($product->get_second_variations_min_max_price->min_special_price)){
                                if($product->get_second_variations_min_max_price->min_special_price == $product->get_second_variations_min_max_price->max_special_price){
                                    // $product_special_range = number_format($product->get_second_variations_min_max_price->min_normal_price, 2);
                                    $product_special_range = number_format($product->get_second_variations_min_max_price->min_special_price, 2);



                                    $lower_special_price_range = $product->get_second_variations_min_max_price->min_special_price;
                                    $upper_special_price_range = $product->get_second_variations_min_max_price->max_special_price;


                                }else{
                                    // $product_special_range = number_format($product->get_second_variations_min_max_price->min_normal_price, 2).' - '.number_format($product->get_second_variations_min_max_price->max_normal_price, 2);
                                    $product_special_range = number_format($product->get_second_variations_min_max_price->min_special_price, 2).' - '.number_format($product->get_second_variations_min_max_price->max_special_price, 2);


                                    $lower_special_price_range = $product->get_second_variations_min_max_price->min_normal_price;
                                    $upper_special_price_range = $product->get_second_variations_min_max_price->max_normal_price;
                                }
                            }

                            if(!empty($product->get_second_variations_price->variation_special_price)){
                                $product_special_price = number_format($product->get_second_variations_price->variation_price, 2);
                            }
                        }

                    }else{

                        if(!empty($user_birthday_promotion) && $user_birthday_promotion && $product->birthday_promotion == '1'){
                            
                            if($product->get_variations_min_max_birthday_price->min_price == $product->get_variations_min_max_birthday_price->max_price){
                                $product_price_range = number_format($product->get_variations_min_max_birthday_price->min_price, 2);



                                $lower_price_range = $product->get_variations_min_max_birthday_price->min_price;
                                $upper_price_range = $product->get_variations_min_max_birthday_price->max_price;
                            }else{
                                $product_price_range = number_format($product->get_variations_min_max_birthday_price->min_price, 2).' - '.number_format($product->get_variations_min_max_birthday_price->max_price, 2);



                                $lower_price_range = $product->get_variations_min_max_birthday_price->min_price;
                                $upper_price_range = $product->get_variations_min_max_birthday_price->max_price;
                            }

                            // $product_price = !empty($product->get_variations_price->variation_birthday_special_price) ? $product->get_variations_price->variation_birthday_special_price : $product->get_variations_price->variation_birthday_price;
                            if(!empty($product->get_variations_price->variation_birthday_special_price)){
                                $product_price = $product->get_variations_price->variation_birthday_special_price;
                            }elseif(!empty($product->get_variations_price->variation_birthday_price)){
                                $product_price = $product->get_variations_price->variation_birthday_price;
                            }elseif(!empty($product->get_variations_price->variation_special_price)){
                                $product_price = $product->get_variations_price->variation_special_price;
                            }elseif(!empty($product->get_variations_price->variation_price)){
                                $product_price = $product->get_variations_price->variation_price;
                            }else{
                                $product_price = 0;
                            }

                            //Special Pricing Slash Display
                            if(!empty($product->get_variations_min_max_birthday_price->min_price)){
                                if($product->get_variations_min_max_birthday_price->min_special_price == $product->get_variations_min_max_birthday_price->max_special_price){
                                    $product_special_range = number_format($product->get_variations_min_max_birthday_price->min_normal_price, 2);



                                    $lower_special_price_range = $product->get_variations_min_max_birthday_price->min_special_price;
                                    $upper_special_price_range = $product->get_variations_min_max_birthday_price->max_special_price;
                                }else{
                                    $product_special_range = number_format($product->get_variations_min_max_birthday_price->min_normal_price, 2).' - '.number_format($product->get_variations_min_max_birthday_price->max_normal_price, 2);



                                    $lower_special_price_range = $product->get_variations_min_max_birthday_price->min_normal_price;
                                    $upper_special_price_range = $product->get_variations_min_max_birthday_price->max_normal_price;
                                }
                            }

                            if(!empty($product->get_variations_price->variation_birthday_special_price)){
                                $product_special_price = number_format($product->get_variations_price->variation_birthday_price, 2);
                            }

                        }else{

                            if(!empty($product->get_variations_min_max_price->min_price) && $product->get_variations_min_max_price->min_price == $product->get_variations_min_max_price->max_price){
                                $product_price_range = number_format($product->get_variations_min_max_price->min_price, 2);



                                $lower_price_range = $product->get_variations_min_max_price->min_price;
                                $upper_price_range = $product->get_variations_min_max_price->max_price;
                            }else{
                                $product_price_range = number_format($product->get_variations_min_max_price->min_price, 2).' - '.number_format($product->get_variations_min_max_price->max_price, 2);



                                $lower_price_range = $product->get_variations_min_max_price->min_price;
                                $upper_price_range = $product->get_variations_min_max_price->max_price;
                            }

                            // $product_price = !empty($product->get_variations_price->variation_special_price) ? $product->get_variations_price->variation_special_price : $product->get_variations_price->variation_price;
                            if(!empty($product->get_variations_price->variation_special_price)){
                                $product_price = $product->get_variations_price->variation_special_price;
                            }elseif(!empty($product->get_variations_price->variation_price)){
                                $product_price = $product->get_variations_price->variation_price;
                            }else{
                                $product_price = 0;
                            }

                            //Special Pricing Slash Display
                            // if(!empty($product->get_variations_min_max_price->min_price)){
                            if(!empty($product->get_variations_min_max_price->min_special_price)){
                                if($product->get_variations_min_max_price->min_special_price == $product->get_variations_min_max_price->max_special_price){
                                    // $product_special_range = number_format($product->get_variations_min_max_price->min_normal_price, 2);
                                    $product_special_range = number_format($product->get_variations_min_max_price->min_special_price, 2);



                                    $lower_special_price_range = $product->get_variations_min_max_price->min_special_price;
                                    $upper_special_price_range = $product->get_variations_min_max_price->max_special_price;
                                }else{
                                    // $product_special_range = number_format($product->get_variations_min_max_price->min_normal_price, 2).' - '.number_format($product->get_variations_min_max_price->max_normal_price, 2);
                                    $product_special_range = number_format($product->get_variations_min_max_price->min_special_price, 2).' - '.number_format($product->get_variations_min_max_price->max_special_price, 2);


                                    $lower_special_price_range = $product->get_variations_min_max_price->min_normal_price;
                                    $upper_special_price_range = $product->get_variations_min_max_price->max_normal_price;
                                }
                            }

                            if(!empty($product->get_variations_price->variation_special_price)){
                                $product_special_price = number_format($product->get_variations_price->variation_price, 2);
                            }


                        }
                    }
                }else{
                    if(!empty($user_birthday_promotion) && $user_birthday_promotion && $product->birthday_promotion == '1'){

                        $product_price_range = !empty($product->birthday_special_price) ? number_format($product->birthday_special_price, 2) : number_format($product->birthday_price, 2);



                        $lower_price_range = $product_price_range;
                        $upper_price_range = $product_price_range;

                        // $product_price = !empty($product->birthday_special_price) ? $product->birthday_special_price : $product->birthday_price;
                        if(!empty($product->birthday_special_price)){
                            $product_price = $product->birthday_special_price;
                        }elseif(!empty($product->birthday_price)){
                            $product_price = $product->birthday_price;
                        }elseif(!empty($product->special_price)){
                            $product_price = $product->special_price;
                        }elseif(!empty($product->price)){
                            $product_price = $product->price;
                        }else{
                            $product_price = 0;
                        }

                        if(!empty($product->birthday_special_price)){
                            $product_special_range = number_format($product->birthday_price, 2);
                            $product_special_price = number_format($product->birthday_price, 2);


                            $lower_special_price_range = $product_special_range;
                            $upper_special_price_range = $product_special_range;
                        }

                    }else{

                        $product_price_range = !empty($product->special_price) ? number_format($product->special_price, 2) : number_format($product->price, 2);



                        $lower_price_range = $product_price_range;
                        $upper_price_range = $product_price_range;

                        // $product_price = !empty($product->special_price) ? $product->special_price : $product->price;
                        if(!empty($product->special_price)){
                            $product_price = $product->special_price;
                        }elseif(!empty($product->price)){
                            $product_price = $product->price;
                        }else{
                            $product_price = 0;
                        }

                        if(!empty($product->special_price)){
                            $product_special_range = number_format($product->price, 2);
                            $product_special_price = number_format($product->price, 2);



                            $lower_special_price_range = $product_special_range;
                            $upper_special_price_range = $product_special_range;
                        }

                    }
                }
            }else{                
                if($product->variation_enable == 1){
                    if($product->second_variation_enable == 1){
                        if($product->get_second_variations_min_max_retail_price->min_price == $product->get_second_variations_min_max_retail_price->max_price){
                            $product_price_range = number_format($product->get_second_variations_min_max_retail_price->min_price, 2);



                            $lower_price_range = $product->get_second_variations_min_max_retail_price->min_price;
                            $upper_price_range = $product->get_second_variations_min_max_retail_price->max_price;
                        }else{
                            $product_price_range = number_format($product->get_second_variations_min_max_retail_price->min_price, 2).' - '.number_format($product->get_second_variations_min_max_retail_price->max_price, 2);



                            $lower_price_range = $product->get_second_variations_min_max_retail_price->min_price;
                            $upper_price_range = $product->get_second_variations_min_max_retail_price->max_price;
                        }

                        // $product_price = !empty($product->get_second_variations_price->variation_retail_special_price) ? $product->get_second_variations_price->variation_retail_special_price : $product->get_second_variations_price->variation_price;
                        if(!empty($product->get_second_variations_price->variation_retail_special_price)){
                            $product_price = $product->get_second_variations_price->variation_retail_special_price;
                        }elseif(!empty($product->get_second_variations_price->variation_retail_price)){
                            $product_price = $product->get_second_variations_price->variation_retail_price;
                        }else{
                            $product_price = 0;
                        }


                        //Special Pricing Slash Display
                        // if(!empty($product->get_second_variations_min_max_retail_price->min_price)){
                        if(!empty($product->get_second_variations_min_max_retail_price->min_special_price)){
                            if($product->get_second_variations_min_max_retail_price->min_special_price == $product->get_second_variations_min_max_retail_price->max_special_price){
                                // $product_special_range = number_format($product->get_second_variations_min_max_retail_price->min_normal_price, 2);
                                $product_special_range = number_format($product->get_second_variations_min_max_retail_price->min_special_price, 2);



                                $lower_special_price_range = $product->get_second_variations_min_max_retail_price->min_special_price;
                                $upper_special_price_range = $product->get_second_variations_min_max_retail_price->max_special_price;


                            }else{
                                // $product_special_range = number_format($product->get_second_variations_min_max_retail_price->min_normal_price, 2).' - '.number_format($product->get_second_variations_min_max_retail_price->max_normal_price, 2);
                                $product_special_range = number_format($product->get_second_variations_min_max_retail_price->min_special_price, 2).' - '.number_format($product->get_second_variations_min_max_retail_price->max_special_price, 2);


                                $lower_special_price_range = $product->get_second_variations_min_max_retail_price->min_normal_price;
                                $upper_special_price_range = $product->get_second_variations_min_max_retail_price->max_normal_price;
                            }
                        }

                        if(!empty($product->get_second_variations_price->variation_retail_special_price)){
                            $product_special_price = number_format($product->get_second_variations_price->variation_retail_price, 2);
                        }

                    }else{

                        if(!empty($product->get_variations_min_max_retail_price->min_price) && $product->get_variations_min_max_retail_price->min_price == $product->get_variations_min_max_retail_price->max_price){
                            $product_price_range = number_format($product->get_variations_min_max_retail_price->min_price, 2);



                            $lower_price_range = $product->get_variations_min_max_retail_price->min_price;
                            $upper_price_range = $product->get_variations_min_max_retail_price->max_price;
                        }else{
                            $product_price_range = number_format($product->get_variations_min_max_retail_price->min_price, 2).' - '.number_format($product->get_variations_min_max_retail_price->max_price, 2);



                            $lower_price_range = $product->get_variations_min_max_retail_price->min_price;
                            $upper_price_range = $product->get_variations_min_max_retail_price->max_price;
                        }

                        // $product_price = !empty($product->get_variations_price->variation_retail_special_price) ? $product->get_variations_price->variation_retail_special_price : $product->get_variations_price->variation_retail_price;
                        if(!empty($product->get_variations_price->variation_retail_special_price)){
                            $product_price = $product->get_variations_price->variation_retail_special_price;
                        }elseif(!empty($product->get_variations_price->variation_retail_price)){
                            $product_price = $product->get_variations_price->variation_retail_price;
                        }else{
                            $product_price = 0;
                        }

                        //Special Pricing Slash Display
                        // if(!empty($product->get_variations_min_max_retail_price->min_price)){
                        if(!empty($product->get_variations_min_max_retail_price->min_special_price)){
                            if($product->get_variations_min_max_retail_price->min_special_price == $product->get_variations_min_max_retail_price->max_special_price){
                                // $product_special_range = number_format($product->get_variations_min_max_retail_price->min_normal_price, 2);
                                $product_special_range = number_format($product->get_variations_min_max_retail_price->min_special_price, 2);



                                $lower_special_price_range = $product->get_variations_min_max_retail_price->min_special_price;
                                $upper_special_price_range = $product->get_variations_min_max_retail_price->max_special_price;
                            }else{
                                // $product_special_range = number_format($product->get_variations_min_max_retail_price->min_normal_price, 2).' - '.number_format($product->get_variations_min_max_retail_price->max_normal_price, 2);
                                $product_special_range = number_format($product->get_variations_min_max_retail_price->min_special_price, 2).' - '.number_format($product->get_variations_min_max_retail_price->max_special_price, 2);


                                $lower_special_price_range = $product->get_variations_min_max_retail_price->min_normal_price;
                                $upper_special_price_range = $product->get_variations_min_max_retail_price->max_normal_price;
                            }
                        }

                        if(!empty($product->get_variations_price->variation_retail_special_price)){
                            $product_special_price = number_format($product->get_variations_price->variation_retail_price, 2);
                        }
                    }
                }else{
                    $product_price_range = !empty($product->retail_special_price) ? number_format($product->retail_special_price, 2) : number_format($product->retail_price, 2);



                    $lower_price_range = $product_price_range;
                    $upper_price_range = $product_price_range;

                    if(!empty($product->retail_special_price)){
                        $product_price = $product->retail_special_price;
                    }elseif(!empty($product->retail_price)){
                        $product_price = $product->retail_price;
                    }else{
                        $product_price = 0;
                    }

                    if(!empty($product->retail_special_price)){
                        $product_special_range = number_format($product->retail_price, 2);
                        $product_special_price = number_format($product->retail_price, 2);



                        $lower_special_price_range = $product_special_range;
                        $upper_special_price_range = $product_special_range;
                    }
                }
            }
        }

        if(!empty($flash_price) && !empty($flash_sale_override_price)){
            $product_price = $flash_price;

            if($lower_price_range == $upper_price_range){
                $product_price_range = number_format($flash_price, 2);
            }else{
                if($lower_price_range > $flash_price){
                    $product_price_range = number_format($flash_price, 2).' - '.number_format($upper_price_range, 2);
                }elseif($upper_price_range < $flash_price){
                    $product_price_range = number_format($lower_price_range, 2).' - '.number_format($flash_price, 2);
                }
            }

            $flash_sale_product_price_id = $get_flash_sale_price->id;
        }

        return array('product_price_range'=>$product_price_range,
                     'product_price'=>$product_price,
                     'product_special_range'=>$product_special_range,
                     'product_special_price'=>$product_special_price,
                     'code'=>$code,
                     'flash_sale_product_price_id'=>$flash_sale_product_price_id);
    }

    public static function get_cart_details($code, $lvl, $store = 0, $cod = 0)
    {
        $website_setting = WebsiteSetting::find(1);
        $getUserDetails = GlobalController::getUserDetails($code);

        $carts = Cart::with(['get_product_det.get_agent_price_product' => function ($query) use ($lvl) {
                                    $query->where('agent_lvl_id', $lvl);
                                }
                            ])
                     ->with(['get_fv_det.get_agent_price_variation' => function ($query_two) use ($lvl) {
                                    $query_two->where('agent_lvl_id', $lvl);
                                }
                            ])
                     ->with(['get_sv_det.get_agent_price_second_variation' => function ($query_three) use ($lvl) {
                                    $query_three->where('agent_lvl_id', $lvl);
                                }
                            ])
                     ->with(['get_promo_items' => function ($query_four) use ($lvl) {
                                    $query_four->where('agent_lvl_id', $lvl);
                                }
                            ])
                     ->where('carts.user_id', $code)
                     ->where('carts.status', '1')
                     ->whereNull('carts.mall')
                     ->groupBy('carts.id')
                     ->get();

        $sub_total = 0;
        $totalWeight = 0;

        foreach($carts as $cart){
            
            if(!empty($cart->add_on_id) && empty($cart->main_add_on)){
                $product_price = GlobalController::get_add_on_sub_item_price($code, $cart->add_on_id, $cart->product_id, $cart->sub_category_id, $cart->second_sub_category_id);

                $sub_total += $product_price * $cart->qty;
            }else{
                if(!empty($cart->flash_sale_product_id)){
                    $product_price = GlobalController::get_product_pricing(md5($cart->product_id), $code, $cart->sub_category_id, $cart->second_sub_category_id, "", "", '1');
                }else{
                    $product_price = GlobalController::get_product_pricing(md5($cart->product_id), $code, $cart->sub_category_id, $cart->second_sub_category_id, "", "", '1');
                }

                $sub_total += $product_price['product_price'] * $cart->qty;
            }
            
            if($cart->get_product_det->variation_enable == '1'){
                if($cart->get_product_det->second_variation_enable == '1'){
                    if(!empty($cart->get_sv_det->variation_weight)){
                        $totalWeight += $cart->get_sv_det->variation_weight * $cart->qty;
                    }
                }else{
                    if(!empty($cart->get_fv_det->variation_weight)){
                        $totalWeight += $cart->get_fv_det->variation_weight * $cart->qty;
                    }
                }
            }else{
                if(!empty($cart->get_product_det->weight)){
                    $totalWeight += $cart->get_product_det->weight * $cart->qty;
                }
            }
        }

        $all_shipping_fee = 0;

        $check_order_shipping_fee = 0;

        $get_states = "";

        $get_countries = "";

        $shipping_weight = 0;
        
        if(!empty($getUserDetails->get_default_shipping_address->state)){
            $get_states = $getUserDetails->get_default_shipping_address->state;
        }
        
        if(!empty($getUserDetails->get_default_shipping_address->country)){
            $get_countries = $getUserDetails->get_default_shipping_address->country;
        }

        foreach ($carts as $cart) {
            if($cart->get_product_det->variation_enable == '1'){
                if($cart->get_product_det->second_variation_enable == '1'){
                    if(!empty($cart->get_sv_det->variation_weight)){
                        // $shipping_weight += $cart->get_sv_det->variation_weight * $cart->qty;

                        if($get_countries == '160'){
                            if($get_states != '11' && $get_states != '12' && $get_states != '15'){
                                if($cart->get_product_det->free_shipping != '1'){
                                    $shipping_weight += $cart->get_sv_det->variation_weight * $cart->qty;
                                }
                            }else{
                                if($cart->get_product_det->free_east_shipping != '1'){
                                    $shipping_weight += $cart->get_sv_det->variation_weight * $cart->qty;
                                }
                            }
                        }else{
                            if($cart->get_product_det->free_singapore_shipping != '1' && $get_countries != '200'){
                                $shipping_weight += $cart->get_sv_det->variation_weight * $cart->qty;
                            }
                        }
                    }
                }else{
                    if(!empty($cart->get_fv_det->variation_weight)){
                        // $shipping_weight += $cart->get_fv_det->variation_weight * $cart->qty;

                        if($get_countries == '160'){
                            if($get_states != '11' && $get_states != '12' && $get_states != '15'){
                                if($cart->get_product_det->free_shipping != '1'){
                                    $shipping_weight += $cart->get_fv_det->variation_weight * $cart->qty;
                                }
                            }else{
                                if($cart->get_product_det->free_east_shipping != '1'){
                                    $shipping_weight += $cart->get_fv_det->variation_weight * $cart->qty;
                                }
                            }
                        }else{
                            if($cart->get_product_det->free_singapore_shipping != '1' && $get_countries != '200'){
                                $shipping_weight += $cart->get_fv_det->variation_weight * $cart->qty;
                            }
                        }
                    }
                }
            }else{
                if(!empty($cart->get_product_det->weight)){
                    // $shipping_weight += $cart->get_product_det->weight * $cart->qty;

                    if($get_countries == '160'){
                        if($get_states != '11' && $get_states != '12' && $get_states != '15'){
                            if($cart->get_product_det->free_shipping != '1'){
                                $shipping_weight += $cart->get_product_det->weight * $cart->qty;
                            }
                        }else{
                            if($cart->get_product_det->free_east_shipping != '1'){
                                $shipping_weight += $cart->get_product_det->weight * $cart->qty;
                            }
                        }
                    }else{
                        if($cart->get_product_det->free_singapore_shipping != '1' && $get_countries != '200'){
                            $shipping_weight += $cart->get_product_det->weight * $cart->qty;
                        }
                    }
                }
            }
        }

        if($get_countries == 160){
            if($get_states != '11' && $get_states != '12' && $get_states != '15'){
                if ($website_setting->type_set_shipping_fee == '1') { // Weight Based
                    $shipping_fees = SettingShippingFee::where('area', 'west')
                                    ->where('weight', '<=', ceil($shipping_weight))
                                    ->orderBy('weight', 'desc')
                                    ->first();
                } elseif ($website_setting->type_set_shipping_fee == '2') { // Price Based
                    $shipping_fees = SettingShippingFee::where('area', 'west')
                                    ->where('weight', '<=', ceil($sub_total))
                                    ->orderBy('weight', 'desc')
                                    ->first();                             
                }

                if(!empty($shipping_fees->id)){
                    $all_shipping_fee = $shipping_fees->shipping_fee;
                }
            }else{
                if ($website_setting->type_set_shipping_fee == '1') { // Weight Based
                    $shipping_fees = SettingShippingFee::where('area', 'east')
                                    ->where('weight', '<=', ceil($shipping_weight))
                                    ->orderBy('weight', 'desc')
                                    ->first();
                } elseif ($website_setting->type_set_shipping_fee == '2') { // Price Based
                    $shipping_fees = SettingShippingFee::where('area', 'east')
                                    ->where('weight', '<=', ceil($sub_total))
                                    ->orderBy('weight', 'desc')
                                    ->first();                             
                }

                if(!empty($shipping_fees->id)){
                    $all_shipping_fee = $shipping_fees->shipping_fee;
                }
            }
        }else{
            if ($website_setting->type_set_shipping_fee == '1') { // Weight Based
                $shipping_fees = SettingShippingFee::where('country_id', $get_countries)
                                    ->where('weight', '<=', ceil($shipping_weight))
                                    ->orderBy('weight', 'desc')
                                    ->first();
            } elseif ($website_setting->type_set_shipping_fee == '2') { // Price Based
                $shipping_fees = SettingShippingFee::where('country_id', $get_countries)
                                    ->where('weight', '<=', ceil($sub_total))
                                    ->orderBy('weight', 'desc')
                                    ->first();                             
            }

            if(!empty($shipping_fees->id)){
                $all_shipping_fee = $shipping_fees->shipping_fee;
            }
        }

        if($get_countries == 160){
            if($get_states != '11' && $get_states != '12' && $get_states != '15'){
                if ($website_setting->type_set_shipping_fee == '1') { // Weight Based
                    $shipping_fees = SettingShippingFee::where('area', 'west')
                                    ->where('weight', '<=', ceil($totalWeight))
                                    ->orderBy('weight', 'desc')
                                    ->first();
                } elseif ($website_setting->type_set_shipping_fee == '2') { // Price Based
                    $shipping_fees = SettingShippingFee::where('area', 'west')
                                    ->where('weight', '<=', ceil($sub_total))
                                    ->orderBy('weight', 'desc')
                                    ->first();                             
                }

                if(!empty($shipping_fees->id)){
                    $check_order_shipping_fee = $shipping_fees->shipping_fee;
                }
            }else{
                if ($website_setting->type_set_shipping_fee == '1') { // Weight Based
                    $shipping_fees = SettingShippingFee::where('area', 'east')
                                    ->where('weight', '<=', ceil($totalWeight))
                                    ->orderBy('weight', 'desc')
                                    ->first();
                } elseif ($website_setting->type_set_shipping_fee == '2') { // Price Based
                    $shipping_fees = SettingShippingFee::where('area', 'east')
                                    ->where('weight', '<=', ceil($sub_total))
                                    ->orderBy('weight', 'desc')
                                    ->first();                             
                }

                if(!empty($shipping_fees->id)){
                    $check_order_shipping_fee = $shipping_fees->shipping_fee;
                }
            }
        }else{
            if ($website_setting->type_set_shipping_fee == '1') { // Weight Based
                $shipping_fees = SettingShippingFee::where('country_id', $get_countries)
                                    ->where('weight', '<=', ceil($totalWeight))
                                    ->orderBy('weight', 'desc')
                                    ->first();
            } elseif ($website_setting->type_set_shipping_fee == '2') { // Price Based
                $shipping_fees = SettingShippingFee::where('country_id', $get_countries)
                                    ->where('weight', '<=', ceil($sub_total))
                                    ->orderBy('weight', 'desc')
                                    ->first();                             
            }

            if(!empty($shipping_fees->id)){
                $check_order_shipping_fee = $shipping_fees->shipping_fee;
            }
        }

        if($store == 1 ||
           $cod == 1){
            $all_shipping_fee = 0;
        }


        $web_setting = WebsiteSetting::find(1);

        if(!empty($web_setting->free_shipping_amount) && $sub_total >= $web_setting->free_shipping_amount){
            $all_shipping_fee = 0;
        }


        $totalDiscount = 0;
        $discount_code = NULL;
        $discount_type = NULL;
        $discount_amount = NULL;

        if(!empty($getUserDetails->get_applied_voucher->get_voucher_detail->id)){
          
            $minSpend = $getUserDetails->get_applied_voucher->get_voucher_detail->minSpend ?? 0;
            
            if($minSpend > 0 && $sub_total < $minSpend){
              
                $appliedVoucher = $getUserDetails->get_applied_voucher;
                if($appliedVoucher){
                    $appliedVoucher->delete();
                }
              
                $totalDiscount = 0;
                $discount_code = NULL;
                $discount_type = NULL;
                $discount_amount = NULL;
            } else {
               
                $discount_code = $getUserDetails->get_applied_voucher->get_voucher_detail->discount_code;
                $discount_type = $getUserDetails->get_applied_voucher->get_voucher_detail->amount_type;
                $discount_amount = $getUserDetails->get_applied_voucher->get_voucher_detail->amount;

                if($getUserDetails->get_applied_voucher->get_voucher_detail->free_shipping == '1'){
                    $totalDiscount = $all_shipping_fee;
                }else{
                    if($getUserDetails->get_applied_voucher->get_voucher_detail->amount_type == 'Percentage'){
                        $totalDiscount = (float) $sub_total * $getUserDetails->get_applied_voucher->get_voucher_detail->amount / 100;
                    }else{
                        $totalDiscount = $getUserDetails->get_applied_voucher->get_voucher_detail->amount;
                    }
                }

                $maxCapped = $getUserDetails->get_applied_voucher->get_voucher_detail->maxCapped ?? null;
                if(!empty($maxCapped) && (float)$maxCapped > 0){
                    if($totalDiscount > (float)$maxCapped){
                        $totalDiscount = (float)$maxCapped;
                     }
                }
            }
        }

    
        if (!empty($website_setting->free_shipping_threshold) && $sub_total >= $website_setting->free_shipping_threshold) {
            $totalshipping_fees = 0; 
        } else {
            $totalshipping_fees = $all_shipping_fee;
        }

        $totalAmount = $sub_total + $totalshipping_fees - $totalDiscount;

        if ($totalAmount < 0) {
            $totalAmount = 0;
        }

        return array('sub_total'=>$sub_total, 
                     'total_weight'=>$totalWeight, 
                     'total_discount'=>$totalDiscount, 
                     'total_shipping_fee'=>$totalshipping_fees, 
                     'grand_total'=>$totalAmount,
                     'discount_code'=>$discount_code,
                     'check_order_shipping_fee'=>$check_order_shipping_fee,
                     'discount_type'=>$discount_type,
                     'discount_amount'=>$discount_amount);
    }

    public static function get_cart_details_mall($code, $lvl)
    {
        $getUserDetails = GlobalController::getUserDetails($code);

        $carts = Cart::with(['get_product_det.get_agent_price_product' => function ($query) use ($lvl) {
                                    $query->where('agent_lvl_id', $lvl);
                                }
                            ])
                     ->with(['get_fv_det.get_agent_price_variation' => function ($query_two) use ($lvl) {
                                    $query_two->where('agent_lvl_id', $lvl);
                                }
                            ])
                     ->with(['get_sv_det.get_agent_price_second_variation' => function ($query_three) use ($lvl) {
                                    $query_three->where('agent_lvl_id', $lvl);
                                }
                            ])
                     ->where('carts.user_id', $code)
                     ->where('carts.status', '1')
                     ->where('carts.mall', '1')
                     ->groupBy('carts.id')
                     ->get();

        $sub_total = 0;
        $totalWeight = 0;

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

            $product_price = GlobalController::get_product_pricing(md5($cart->product_id), $code, $cart->sub_category_id, $cart->second_sub_category_id, "", "", '1');

            $sub_total += $product_price['product_price'] * $cart->qty;

            if($cart->get_product_det->variation_enable == '1'){
                if($cart->get_product_det->second_variation_enable == '1'){
                    if(!empty($cart->get_sv_det->variation_weight)){
                        $totalWeight += $cart->get_sv_det->variation_weight * $cart->qty;
                    }
                }else{
                    if(!empty($cart->get_fv_det->variation_weight)){
                        $totalWeight += $cart->get_fv_det->variation_weight * $cart->qty;
                    }
                }
            }else{
                if(!empty($cart->get_product_det->weight)){
                    $totalWeight += $cart->get_product_det->weight * $cart->qty;
                }
            }
        }

        $totalDiscount = 0;

        $all_shipping_fee = 0;

        $check_order_shipping_fee = 0;

        $get_states = "";

        $get_countries = "";
        
        if(!empty($getUserDetails->get_default_shipping_address->state)){
            $get_states = $getUserDetails->get_default_shipping_address->state;
        }
        
        if(!empty($getUserDetails->get_default_shipping_address->country)){
            $get_countries = $getUserDetails->get_default_shipping_address->country;
        }

        $website_setting = WebsiteSetting::find(1);
        
        if($get_countries == 160){
            if($get_states != '11' && $get_states != '12' && $get_states != '15'){
                if ($website_setting->type_set_shipping_fee == '1') { // Weight Based
                    $shipping_fees = SettingShippingFee::where('area', 'west')
                                    ->where('weight', '<=', ceil($totalWeight))
                                    ->orderBy('weight', 'desc')
                                    ->first();
                } elseif ($website_setting->type_set_shipping_fee == '2') { // Price Based
                    $shipping_fees = SettingShippingFee::where('area', 'west')
                                    ->where('weight', '<=', ceil($sub_total))
                                    ->orderBy('weight', 'desc')
                                    ->first();                             
                }

                if(!empty($shipping_fees->id)){
                    $all_shipping_fee = $shipping_fees->shipping_fee;
                }
            }else{
                if ($website_setting->type_set_shipping_fee == '1') { // Weight Based
                    $shipping_fees = SettingShippingFee::where('area', 'east')
                                    ->where('weight', '<=', ceil($totalWeight))
                                    ->orderBy('weight', 'desc')
                                    ->first();
                } elseif ($website_setting->type_set_shipping_fee == '2') { // Price Based
                    $shipping_fees = SettingShippingFee::where('area', 'east')
                                    ->where('weight', '<=', ceil($sub_total))
                                    ->orderBy('weight', 'desc')
                                    ->first();                             
                }

                if(!empty($shipping_fees->id)){
                    $all_shipping_fee = $shipping_fees->shipping_fee;
                }
            }
        }else{
            if ($website_setting->type_set_shipping_fee == '1') { // Weight Based
                $shipping_fees = SettingShippingFee::where('country_id', $get_countries)
                                    ->where('weight', '<=', ceil($totalWeight))
                                    ->orderBy('weight', 'desc')
                                    ->first();
            } elseif ($website_setting->type_set_shipping_fee == '2') { // Price Based
                $shipping_fees = SettingShippingFee::where('country_id', $get_countries)
                                    ->where('weight', '<=', ceil($sub_total))
                                    ->orderBy('weight', 'desc')
                                    ->first();                             
            }

            if(!empty($shipping_fees->id)){
                $all_shipping_fee = $shipping_fees->shipping_fee;
            }
        }

        if($get_countries == 160){
            if($get_states != '11' && $get_states != '12' && $get_states != '15'){
                if ($website_setting->type_set_shipping_fee == '1') { 
                    $shipping_fees = SettingShippingFee::where('area', 'west')
                                    ->where('weight', '<=', ceil($totalWeight))
                                    ->orderBy('weight', 'desc')
                                    ->first();
                } elseif ($website_setting->type_set_shipping_fee == '2') { 
                    $shipping_fees = SettingShippingFee::where('area', 'west')
                                    ->where('weight', '<=', ceil($sub_total))
                                    ->orderBy('weight', 'desc')
                                    ->first();                             
                }

                if(!empty($shipping_fees->id)){
                    $check_order_shipping_fee = $shipping_fees->shipping_fee;
                }
            }else{
                if ($website_setting->type_set_shipping_fee == '1') { 
                    $shipping_fees = SettingShippingFee::where('area', 'east')
                                    ->where('weight', '<=', ceil($totalWeight))
                                    ->orderBy('weight', 'desc')
                                    ->first();
                } elseif ($website_setting->type_set_shipping_fee == '2') { 
                    $shipping_fees = SettingShippingFee::where('area', 'east')
                                    ->where('weight', '<=', ceil($sub_total))
                                    ->orderBy('weight', 'desc')
                                    ->first();                             
                }

                if(!empty($shipping_fees->id)){
                    $check_order_shipping_fee = $shipping_fees->shipping_fee;
                }
            }
        }else{
            if ($website_setting->type_set_shipping_fee == '1') {
                $shipping_fees = SettingShippingFee::where('area', 'get_countries')
                                    ->where('weight', '<=', ceil($totalWeight))
                                    ->orderBy('weight', 'desc')
                                    ->first();
            } elseif ($website_setting->type_set_shipping_fee == '2') { 
                $shipping_fees = SettingShippingFee::where('country_id', $get_countries)
                                    ->where('weight', '<=', ceil($sub_total))
                                    ->orderBy('weight', 'desc')
                                    ->first();                             
            }
            
            if(!empty($shipping_fees->id)){
                $check_order_shipping_fee = $shipping_fees->shipping_fee;
                                     
            }
        }

        $web_setting = WebsiteSetting::find(1);

        if(!empty($web_setting->free_shipping_amount) && $sub_total >= $web_setting->free_shipping_amount){
            $all_shipping_fee = 0;
        }


        $totalDiscount = 0;
        $discount_code = "";
        if(!empty($getUserDetails->get_applied_voucher->get_voucher_detail->id)){
           
            $minSpend = $getUserDetails->get_applied_voucher->get_voucher_detail->minSpend ?? 0;
            
            if($minSpend > 0 && $sub_total < $minSpend){
               
                $appliedVoucher = $getUserDetails->get_applied_voucher;
                if($appliedVoucher){
                    $appliedVoucher->delete();
                }
              
                $totalDiscount = 0;
                $discount_code = "";
            } else {
                
            $discount_code = $getUserDetails->get_applied_voucher->get_voucher_detail->discount_code;
            if($getUserDetails->get_applied_voucher->get_voucher_detail->free_shipping == '1'){
                $totalDiscount = $all_shipping_fee;
            }else{
                if($getUserDetails->get_applied_voucher->get_voucher_detail->amount_type == 'Percentage'){
                    $totalDiscount = (float) $sub_total * $getUserDetails->get_applied_voucher->get_voucher_detail->amount / 100;
                }else{
                    $totalDiscount = $getUserDetails->get_applied_voucher->get_voucher_detail->amount;
                    }
                }
            }
        }

       
        if (!empty($website_setting->free_shipping_threshold) && $sub_total >= $website_setting->free_shipping_threshold) {
            $totalshipping_fees = 0; 
        } else {
            $totalshipping_fees = $all_shipping_fee;
        }

        $totalAmount = $sub_total + $totalshipping_fees - $totalDiscount;

        return array('sub_total'=>$sub_total, 
                     'total_weight'=>$totalWeight, 
                     'total_discount'=>$totalDiscount, 
                     'total_shipping_fee'=>$totalshipping_fees, 
                     'grand_total'=>$totalAmount, 
                     'check_order_shipping_fee'=>$check_order_shipping_fee);
    }

    public static function get_product_sold($pid, $vid = 0, $svid = 0)
    {
        $sold = TransactionDetail::select(DB::raw('SUM(quantity) as totalSold'))
                                              ->leftJoin('transactions as t', 't.id', 'transaction_details.transaction_id')
                                              ->where('t.status', '1');

        $soldqtyadjustment = SoldQuantityAdjustment::select(DB::raw('SUM(quantity) as totalSold'),'type','product_id')
                                                                ->where('status', '1')
                                                                ->groupBy('type');

        // 2 rows = increase qty 1 row , decrease 1 row

        if(!empty($pid)){
            $soldqtyadjustment = $soldqtyadjustment->where('product_id', $pid);
        }
        if(!empty($vid)){
            $soldqtyadjustment = $soldqtyadjustment->where('variation_id', $vid);
        }
        if(!empty($svid)){
            $soldqtyadjustment = $soldqtyadjustment->where('second_variation_id', $svid);
        }
        $soldqtyadjustment = $soldqtyadjustment->get();

        $increaseQty = 0;
        $decreaseQty = 0;
       
        // Loop
        foreach($soldqtyadjustment as $row){
            if(strtolower($row->type) == "increase"){
                $increaseQty += $row->totalSold;
            }
            else{
                $decreaseQty += $row->totalSold;
            }
        }

        if(!empty($pid)){
            $sold = $sold->where('product_id', $pid);
        }

        if(!empty($vid)){
            $sold = $sold->where('variation_id', $vid);
        }
        if(!empty($svid)){
            $sold = $sold->where('second_variation_id', $svid);
        }
                                              
        $sold = $sold->first();

        $total_sold_qty = 0;

        $total_sold_qty = !empty($sold->totalSold) ? $sold->totalSold : 0;

        $total_sold_qty = $total_sold_qty + ($increaseQty) - ($decreaseQty);
        return $total_sold_qty;
    }

    public static function balance_quantity($id)
    {   
        

        $stockBalance = Stock::select(DB::raw('SUM(IF(type = "Increase", quantity, NULL)) AS totalStockIn'),
                                      DB::raw('SUM(IF(type = "Decrease", quantity, NULL)) AS totalStockOut'))
                                ->where('product_id', $id)
                                ->whereNull('variation_id')
                                ->whereNull('second_variation_id')
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

        $transactionPackage = TransactionDetail::select(DB::raw('COALESCE(SUM(transaction_details.quantity * pi.qty), 0) AS TransCart'))
                                        ->join('transactions AS t', 't.id', 'transaction_details.transaction_id')
                                        ->join('package_items as pi', 'pi.product_id', 'transaction_details.product_id')
                                        ->where('pi.products', $id)
                                        // ->where(function ($query){
                                        //     $query->whereNull('pi.variation_id')
                                        //               ->orWhere('pi.variation_id', '0');
                                        // })
                                        // ->where(function ($query2){
                                        //     $query2->whereNull('pi.second_variation_id')
                                        //                ->orWhere('pi.second_variation_id', '0');
                                        // })
                                        ->where('t.status', '1')
                                        ->first();

        return $stockBalance->totalStockIn - $stockBalance->totalStockOut - $transaction->TransCart - $transactionPackage->TransCart;
    }

    public static function variation_balance_quantity($id)
    {
        $stockBalance = Stock::select(DB::raw('SUM(IF(type = "Increase", quantity, NULL)) AS totalStockIn'),
                                      DB::raw('SUM(IF(type = "Decrease", quantity, NULL)) AS totalStockOut'))
                                ->where('variation_id', $id)
                                ->whereNull('second_variation_id')
                                ->WhereNull('packages_id')
                                ->first();

        $cart = Cart::select(DB::raw('SUM(qty) AS InCart'))
                    ->where('status', '1')
                    ->where('sub_category_id', $id);

        if(Auth::check()){
            $cart = $cart->where('user_id', '<>', Auth::user()->code);
        }

        $cart = $cart->first();

        $transaction = TransactionDetail::select(DB::raw('SUM(quantity) AS TransCart'))
                                        ->join('transactions AS t', 't.id', 'transaction_details.transaction_id')
                                        ->where('t.status', '1')
                                        ->where('variation_id', $id)
                                        ->first();

        $transactionPackage = TransactionDetail::select(DB::raw('COALESCE(SUM(transaction_details.quantity * pi.qty), 0) AS TransCart'))
                                        ->join('transactions AS t', 't.id', 'transaction_details.transaction_id')
                                        ->join('package_items as pi', 'pi.product_id', 'transaction_details.product_id')
                                        ->where(function ($query) use ($id){
                                            $query->where('pi.variation_id', $id);
                                        })
                                        // ->where(function ($query2){
                                        //     $query2->whereNull('pi.second_variation_id')
                                        //                ->orWhere('pi.second_variation_id', '0');
                                        // })
                                        ->where('t.status', '1')
                                        ->first();

        return $stockBalance->totalStockIn - $stockBalance->totalStockOut - $cart->InCart - $transaction->TransCart - $transactionPackage->TransCart;
    }

    public static function second_variation_balance_quantity($id)
    {
        $stockBalance = Stock::select(DB::raw('SUM(IF(type = "Increase", quantity, NULL)) AS totalStockIn'),
                                      DB::raw('SUM(IF(type = "Decrease", quantity, NULL)) AS totalStockOut'))
                                ->where('second_variation_id', $id)
                                ->WhereNull('packages_id')
                                ->first();

        $cart = Cart::select(DB::raw('SUM(qty) AS InCart'))
                    ->where('status', '1')
                    ->where('second_sub_category_id', $id);
        if(Auth::check()){
            $cart = $cart->where('user_id', '<>', Auth::user()->code);
        }
        $cart = $cart->first();

        $transaction = TransactionDetail::select(DB::raw('SUM(quantity) AS TransCart'))
                                        ->join('transactions AS t', 't.id', 'transaction_details.transaction_id')
                                        ->where('t.status', '1')
                                        ->where('second_variation_id', $id)
                                        ->first();

        $transactionPackage = TransactionDetail::select(DB::raw('COALESCE(SUM(transaction_details.quantity * pi.qty), 0) AS TransCart'))
                                        ->join('transactions AS t', 't.id', 'transaction_details.transaction_id')
                                        ->join('package_items as pi', 'pi.product_id', 'transaction_details.product_id')
                                        ->where(function ($query2) use ($id){
                                            $query2->where('pi.second_variation_id', $id);
                                        })
                                        ->where('t.status', '1')
                                        ->first();

        return $stockBalance->totalStockIn - $stockBalance->totalStockOut - $cart->InCart - $transaction->TransCart - $transactionPackage->TransCart;
    }

    public static function get_production_url($file_path)
    {
        $localCheck = $_SERVER["SERVER_NAME"];
        $explo_check = explode('.', $localCheck);
        if($explo_check[0] == 'demoaccount'){
            $productionURL = "https://newseller.vesson.my";
        }elseif($localCheck != '127.0.0.1'){
            $productionURL = "https://".$localCheck."/demoqc/public";
        }else{
            $productionURL = "http://".$localCheck.":8000";
        }

        return $productionURL."/".$file_path;
    }

    public static function get_image_path($path)
    {
        $localCheck = $_SERVER["SERVER_NAME"];

        if($localCheck != '127.0.0.1'){
            $url = base_path('public/'.$path);
        }else{
            $url = $path;
        }

        return $url;
    }

    public static function get_website_setting()
    {
        $website_setting = WebsiteSetting::find(1);
        $maximum_level = !empty($website_setting->maximum_level) ? $website_setting->maximum_level : 1;

        return array(['maximum_level'=>$maximum_level]);
    }

    public static function get_lvl_detail($lvl,$is_cn = Null)
    {
        $get_lvl = AgentLevel::find($lvl);

        if(!empty($is_cn)){
            $agent_lvl = !empty($get_lvl->agent_lvl_cn) ? $get_lvl->agent_lvl_cn : '';
        }else{
            $agent_lvl = !empty($get_lvl->agent_lvl) ? $get_lvl->agent_lvl : '';
        }

        return $agent_lvl;
    }
    

    public static function get_top_ten_agent_sales($year)
    {
        $top_10 = [];

        $agents = Agent::where('status', '1')->get();

        foreach($agents as $agent){
            $total = 0;

            $own_transaction = Transaction::select(DB::raw('COALESCE(SUM(grand_total - shipping_fee), 0) AS sales'))
                                          ->where('user_id', $agent->code)
                                          ->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y")'), DB::raw("'$year'"))
                                          ->where('status', '1')
                                          ->first();

            $total = $total + $own_transaction->sales;

            $direct_agents = Agent::where('master_id', $agent->code)->get();

            foreach($direct_agents as $direct_a){
                $direct_agent_transaction = Transaction::select(DB::raw('COALESCE(SUM(grand_total - shipping_fee), 0) AS sales'))
                                          ->where('user_id', $direct_a->code)
                                          ->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y")'), DB::raw("'$year'"))
                                          ->where('status', '1')
                                          ->first();

                $total = $total + $direct_agent_transaction->sales;
            }

            $direct_customers = User::where('master_id', $agent->code)
                                    ->get();

            foreach($direct_customers as $direct_c){
                $direct_customer_transaction = Transaction::select(DB::raw('COALESCE(SUM(grand_total - shipping_fee), 0) AS sales'))
                                          ->where('user_id', $direct_c->code)
                                          ->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y")'), DB::raw("'$year'"))
                                          ->where('status', '1')
                                          ->first();

                $total = $total + $direct_customer_transaction->sales;
            }


            $top_10[$agent->code] = $total;

        }

        arsort($top_10);

        $final_top_10 = array_slice($top_10, 0, 10);

        return $final_top_10;
    }

    public static function rebate_commission($code, $no)
    {
        try{
            \DB::beginTransaction();

            $website_setting = GlobalController::website_setting();

            $transaction = Transaction::where('transaction_no', $no);
            $transaction = $transaction->first();

            $bonus_agent_enable = $website_setting->bonus_agent_enable;
            $bonus_member_enable = $website_setting->bonus_member_enable;

            $member = [];
            if($bonus_member_enable == 1 && $website_setting->member_rebate_enable == 1){
                $member = User::where('code', $code)->first();
            }

            $agent = [];
            if($bonus_agent_enable == 1 && $website_setting->agent_rebate_enable == 1){
                $agent = Agent::where('code', $code)->first();
            }

            if(!empty($transaction->cart_link_id)){
                $get_cart_link_user = CartLink::find($transaction->cart_link_id);
                if(!empty($get_cart_link_user->user_id)){
                    
                    $cart_link_agent = Agent::where('code', $get_cart_link_user->user_id)->first();

                    $agent = $cart_link_agent;
                }
            }

            $detail = [];

            if(!empty($member->id)){
                $detail = $member;
            }

            if(!empty($agent->id)){
                $detail = $agent;
            }

            if(!empty($detail->code)){
                if(!empty($member->id)){
                    if(!empty($website_setting->member_rebate_amount)){
                        $comm = !empty($website_setting->member_rebate_amount) ? $website_setting->member_rebate_amount : 0;
                        if($website_setting->member_rebate_type == 'Percentage'){
                            $calc_amount = $transaction->grand_total - $transaction->shipping_fee;

                            if ($transaction->discount < $transaction->grand_total) {
                                $calc_amount = $calc_amount + $transaction->discount;
                            }

                            $aff_comm_amount = ($calc_amount) * $comm / 100;
                        }else{
                            $aff_comm_amount = $comm;
                        }

                        $insert = new AffiliateCommission();


                        $authorise_merchant = !empty($_COOKIE['vmerchant']) ? $_COOKIE['vmerchant'] : '';
                        $get_authorise_status = GlobalController::check_autorize_status($authorise_merchant);
                        if($get_authorise_status['status'] == 1){
                        $insert->merchant_id = !empty($get_authorise_status['result']['code']) ? $get_authorise_status['result']['code'] : '';
                        }

                        $insert->type = '2';
                        $insert->user_id = $detail->code;
                        $insert->user_by = $detail->code;
                        $insert->transaction_no = $no;
                        $insert->product_amount = ($calc_amount);
                        $insert->comm_pa_type = $website_setting->member_rebate_type;
                        $insert->comm_pa = $website_setting->member_rebate_amount;
                        $insert->comm_amount = $aff_comm_amount;
                        $insert->comm_desc = "Order Rebate";
                        $insert->comm_desc_cn = "订单返利";
                        $insert->status = 1;
                        $insert->save();
                    }
                }elseif(!empty($agent->id)){
                    $setting_merchant_rebate = SettingMerchantRebate::where('agent_lvl', $detail->lvl)
                                                                    ->where('amount', '>', '0')
                                                                    ->first();

                    if(!empty($setting_merchant_rebate->id)){
                        $comm = !empty($setting_merchant_rebate->amount) ? $setting_merchant_rebate->amount : 0;
                        if($setting_merchant_rebate->type == 'Percentage'){
                            $calc_amount = $transaction->grand_total - $transaction->shipping_fee;

                            if ($transaction->discount < $transaction->grand_total) {
                                $calc_amount = $calc_amount + $transaction->discount;
                            }

                            $aff_comm_amount = ($calc_amount) * $comm / 100;
                        }else{
                            $aff_comm_amount = $comm;
                        }

                        $insert = new AffiliateCommission();


                        $authorise_merchant = !empty($_COOKIE['vmerchant']) ? $_COOKIE['vmerchant'] : '';
                        $get_authorise_status = GlobalController::check_autorize_status($authorise_merchant);
                        if($get_authorise_status['status'] == 1){
                        $insert->merchant_id = !empty($get_authorise_status['result']['code']) ? $get_authorise_status['result']['code'] : '';
                        }

                        $insert->type = '2';
                        $insert->user_id = $detail->code;
                        $insert->user_by = $detail->code;
                        $insert->transaction_no = $no;
                        $insert->product_amount = ($calc_amount);
                        $insert->comm_pa_type = $setting_merchant_rebate->type;
                        $insert->comm_pa = $setting_merchant_rebate->amount;
                        $insert->comm_amount = $aff_comm_amount;
                        $insert->comm_desc = "Order Rebate";
                        $insert->comm_desc_cn = "订单返利";
                        $insert->status = 1;
                        $insert->save();
                    }
                }
            }


            \DB::commit();
        }catch (\Exception $e){
            \DB::rollback();
            return $e->getMessage();
        }catch(\Error $e){
            \DB::rollback();
            return $e->getMessage();
        }
        
        return "ok";
    }

    public  static function numberToChinese($num)
    {
        $map = [
            0 => '零',
            1 => '一',
            2 => '二',
            3 => '三',
            4 => '四',
            5 => '五',
            6 => '六',
            7 => '七',
            8 => '八',
            9 => '九',
            10 => '十'
        ];

        return $map[$num] ?? $num;
    }

    public static function heirarchy_commission($code, $no, $paid = 0)
    {
        try{
            \DB::beginTransaction();

            $website_setting = GlobalController::website_setting();

            $transaction = Transaction::where('transaction_no', $no);
            $transaction = $transaction->first();

            if(empty($transaction->id)){
                throw new \Exception('Error Transaction');
            }

            $bonus_agent_enable = $website_setting->bonus_agent_enable;
            $bonus_member_enable = $website_setting->bonus_member_enable;



            $member = [];
            if($bonus_member_enable == 1 && $website_setting->member_rebate_enable == 1){
                $member = User::where('code', $code)->first();
            }

            $agent = [];
            if($bonus_agent_enable == 1 && $website_setting->agent_rebate_enable == 1){
                $agent = Agent::where('code', $code)->first();
            }

            $detail = $code;
            $detail_lvl = "";

            if(!empty($member->id)){
                $detail = $member->code;
            }

            if(!empty($agent->id)){
                $detail = $agent->code;
            }

            $sort_level = 1;

            if(empty($detail) && !empty($transaction->get_transaction_cart_link->user_id)){
                $cart_member = [];
                if($bonus_member_enable == 1 && $website_setting->member_rebate_enable == 1){
                    $cart_member = User::where('code', $transaction->get_transaction_cart_link->user_id)->first();
                }

                $cart_agent = [];
                if($bonus_agent_enable == 1 && $website_setting->agent_rebate_enable == 1){
                    $cart_agent = Agent::where('code', $transaction->get_transaction_cart_link->user_id)->first();
                }

                if(!empty($cart_member->id)){
                    $detail = $cart_member->code;
                    $detail_lvl = $cart_member->lvl;
                }

                if(!empty($cart_agent->id)){
                    $detail = $cart_agent->code;
                    $detail_lvl = $cart_agent->lvl;
                }

                if(!empty($member->id)){
                    if(!empty($website_setting->member_heirarchy_one_amount) && $sort_level <= 3){
                        $comm = !empty($website_setting->member_heirarchy_one_amount) ? $website_setting->member_heirarchy_one_amount : 0;
                        if($website_setting->member_heirarchy_one_type == 'Percentage'){
                            $aff_comm_amount_cl = ($transaction->grand_total - $transaction->shipping_fee) * $comm / 100;
                        }else{
                            $aff_comm_amount_cl = $comm;
                        }

                        if($aff_comm_amount_cl > 0){
                            $insert = new AffiliateCommission();
                            $insert->type = '1';

                            $authorise_merchant = !empty($_COOKIE['vmerchant']) ? $_COOKIE['vmerchant'] : '';
                            $get_authorise_status = GlobalController::check_autorize_status($authorise_merchant);
                            if($get_authorise_status['status'] == 1){
                            $insert->merchant_id = !empty($get_authorise_status['result']['code']) ? $get_authorise_status['result']['code'] : '';
                            }

                            $insert->user_id = $detail;
                            $insert->user_by = $detail;
                            $insert->transaction_no = $no;
                            $insert->product_amount = ($transaction->grand_total - $transaction->shipping_fee);
                            $insert->comm_pa_type = $website_setting->member_heirarchy_one_type;
                            $insert->comm_pa = $website_setting->member_heirarchy_one_amount;
                            $insert->comm_amount = $aff_comm_amount_cl;
                            if($transaction->register_product == 1){
                                $insert->comm_desc = "Hierarchy Commission - ".$sort_level." 1st Generation (Register Product)";
                                $insert->comm_desc_cn = "层级佣金 - ".$sort_level." 第一代（注册产品)";
                            }else{
                                $insert->comm_desc = "Hierarchy Commission - ".$sort_level." 1st Generation";
                                $insert->comm_desc_cn = "层级佣金 - ".$sort_level." 第一代";
                            }
                           
                            $insert->status = 1;
                            $insert->save();

                            $sort_level++;
                        }
                    }
                }else{
                    $setting_merchant_commission_cl = SettingMerchantCommission::where('agent_lvl', $detail_lvl)
                                                                               ->where('level', $sort_level)
                                                                               ->first();

                    if(!empty($setting_merchant_commission_cl->id) && $sort_level <= 3){
                        $comm = !empty($setting_merchant_commission_cl->comm_amount) ? $setting_merchant_commission_cl->comm_amount : 0;
                        if($setting_merchant_commission_cl->comm_type == 'Percentage'){
                            $aff_comm_amount_cl = ($transaction->grand_total - $transaction->shipping_fee) * $comm / 100;
                        }else{
                            $aff_comm_amount_cl = $comm;
                        }

                        if($aff_comm_amount_cl > 0){
                            $insert = new AffiliateCommission();
                            $insert->type = '1';

                            $authorise_merchant = !empty($_COOKIE['vmerchant']) ? $_COOKIE['vmerchant'] : '';
                            $get_authorise_status = GlobalController::check_autorize_status($authorise_merchant);
                            if($get_authorise_status['status'] == 1){
                            $insert->merchant_id = !empty($get_authorise_status['result']['code']) ? $get_authorise_status['result']['code'] : '';
                            }

                            $insert->user_id = $detail;
                            $insert->user_by = $detail;
                            $insert->transaction_no = $no;
                            $insert->product_amount = ($transaction->grand_total - $transaction->shipping_fee);
                            $insert->comm_pa_type = $setting_merchant_commission_cl->comm_type;
                            $insert->comm_pa = $setting_merchant_commission_cl->comm_amount;
                            $insert->comm_amount = $aff_comm_amount_cl;
                            // $insert->comm_desc = "Heirarchy Commission - ".$sort_level." 1st Generation";
                            if($transaction->register_product == 1){
                                $insert->comm_desc = "Hierarchy Commission - ".$sort_level." 1st Generation (Register Product)";
                                $insert->comm_desc_cn = "层级佣金 - ".$sort_level." 第一代（注册产品)";
                            }else{
                                $insert->comm_desc = "Hierarchy Commission - ".$sort_level." 1st Generation";
                                $insert->comm_desc_cn = "层级佣金 - ".$sort_level." 第一代";
                            }
                            $insert->status = 1;
                            $insert->save();

                            $sort_level++;
                        }
                    }                    
                }
            }

            $affs = [];
            if($bonus_agent_enable == 1 && $website_setting->hierarchy_enable == 1){
                $affs = Affiliate::select('affiliates.*', 'm.lvl', 'm.id as is_agent')
                                 ->join('agents as m', 'm.code', 'affiliates.user_id')
                                 // ->where('sort_level', '<=', '3')
                                 ->where('affiliates.affiliate_id', $detail)
                                 ->where('m.status', '1')
                                 ->orderBy('sort_level', 'asc')
                                 ->get();

            }

            if($bonus_member_enable == 1 && $website_setting->member_hierarchy_enable == 1){
                $affs = Affiliate::select('affiliates.*', 'm.lvl', 'm.id as is_member')
                                 ->join('users as m', 'm.code', 'affiliates.user_id')
                                 // ->where('sort_level', '<=', '3')
                                 ->where('affiliates.affiliate_id', $detail)
                                 ->where('m.status', '1')
                                 ->orderBy('sort_level', 'asc')
                                 ->get();
            }

            if(($bonus_agent_enable == 1 && $website_setting->hierarchy_enable == 1) && ($bonus_member_enable == 1 && $website_setting->member_hierarchy_enable == 1)){
                $affs = Affiliate::select('affiliates.*', 
                                          DB::raw('COALESCE(m.lvl, u.lvl) as lvl'),
                                          'm.id as is_agent',
                                          'u.id as is_member')
                                 ->leftJoin('agents as m', 'm.code', 'affiliates.user_id')
                                 ->leftJoin('users as u', 'u.code', 'affiliates.user_id')
                                 // ->where('sort_level', '<=', '3')
                                 ->where(DB::raw('COALESCE(m.status, u.status)'), '1')
                                 ->where('affiliates.affiliate_id', $detail)
                                 ->orderBy('sort_level', 'asc')
                                 ->get();

            }

            foreach($affs as $aff){
                if($sort_level == 1){
                    $b = 'st';
                    $hei_comm = $website_setting->member_heirarchy_one_amount;
                    $hei_comm_type = $website_setting->member_heirarchy_one_type;
                }elseif($sort_level == 2){
                    $b = 'nd';
                    $hei_comm = $website_setting->member_heirarchy_two_amount;
                    $hei_comm_type = $website_setting->member_heirarchy_two_type;
                }else{
                    $b = 'rd';
                    $hei_comm = $website_setting->member_heirarchy_three_amount;
                    $hei_comm_type = $website_setting->member_heirarchy_three_type;
                }

                if(!empty($aff->is_member)){

                    if(!empty($hei_comm) && $sort_level <= 3){
                        $hei_comm = !empty($hei_comm) ? $hei_comm : 0;
                        if($hei_comm_type == 'Percentage'){
                            $aff_comm_amount = ($transaction->grand_total - $transaction->shipping_fee) * $hei_comm / 100;
                        }else{
                            $aff_comm_amount = $hei_comm;
                        }

                        if($aff_comm_amount > 0){
                            $insert = new AffiliateCommission();
                            $insert->type = '1';

                            $authorise_merchant = !empty($_COOKIE['vmerchant']) ? $_COOKIE['vmerchant'] : '';
                            $get_authorise_status = GlobalController::check_autorize_status($authorise_merchant);
                            if($get_authorise_status['status'] == 1){
                            $insert->merchant_id = !empty($get_authorise_status['result']['code']) ? $get_authorise_status['result']['code'] : '';
                            }

                            $insert->user_id = $aff->user_id;
                            $insert->user_by = $code;
                            $insert->transaction_no = $no;
                            $insert->product_amount = ($transaction->grand_total - $transaction->shipping_fee);
                            $insert->comm_pa_type = $hei_comm_type;
                            $insert->comm_pa = $hei_comm;
                            $insert->comm_amount = $aff_comm_amount;
                            if($transaction->register_product == 1){
                                $insert->comm_desc = "Hierarchy Commission - ".$sort_level.$b." Generation (Register Product)";
                                $insert->comm_desc_cn = "层级佣金 - 第".GlobalController::numberToChinese($sort_level)." 代（注册产品)";
                            }else{
                                $insert->comm_desc = "Hierarchy Commission - ".$sort_level.$b." Generation";
                                $insert->comm_desc_cn = "层级佣金 - 第".GlobalController::numberToChinese($sort_level)." 代";
                            }
                            $insert->status = 1;
                            $insert->save();

                            $sort_level++;
                        }
                    }

                }else{

                    $setting_merchant_commission = SettingMerchantCommission::where('agent_lvl', $aff->lvl)
                                                                          ->where('level', $sort_level)
                                                                          ->first();

                    if(!empty($setting_merchant_commission->id) && $sort_level <= 3){
                        $comm = !empty($setting_merchant_commission->comm_amount) ? $setting_merchant_commission->comm_amount : 0;
                        if($setting_merchant_commission->comm_type == 'Percentage'){
                            $aff_comm_amount = ($transaction->grand_total - $transaction->shipping_fee) * $comm / 100;
                        }else{
                            $aff_comm_amount = $comm;
                        }

                        if($aff_comm_amount > 0){
                            $insert = new AffiliateCommission();
                            $insert->type = '1';

                            $authorise_merchant = !empty($_COOKIE['vmerchant']) ? $_COOKIE['vmerchant'] : '';
                            $get_authorise_status = GlobalController::check_autorize_status($authorise_merchant);
                            if($get_authorise_status['status'] == 1){
                            $insert->merchant_id = !empty($get_authorise_status['result']['code']) ? $get_authorise_status['result']['code'] : '';
                            }

                            $insert->user_id = $aff->user_id;
                            $insert->user_by = $code;
                            $insert->transaction_no = $no;
                            $insert->product_amount = ($transaction->grand_total - $transaction->shipping_fee);
                            $insert->comm_pa_type = $setting_merchant_commission->comm_type;
                            $insert->comm_pa = $setting_merchant_commission->comm_amount;
                            $insert->comm_amount = $aff_comm_amount;
                            // $insert->comm_desc = "Heirarchy Commission - ".$sort_level.$b." Generation";
                            if($transaction->register_product == 1){
                                $insert->comm_desc = "Hierarchy Commission - ".$sort_level.$b." Generation (Register Product)";
                                $insert->comm_desc_cn = "层级佣金 - 第".GlobalController::numberToChinese($sort_level)." 代 (注册产品)";
                            }else{
                                $insert->comm_desc = "Hierarchy Commission - ".$sort_level.$b." Generation";
                                $insert->comm_desc_cn = "层级佣金 - 第".GlobalController::numberToChinese($sort_level)." 代";
                            }
                            $insert->status = 1;
                            $insert->save();

                            $sort_level++;
                        }
                    }
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

        return "ok";
    }

    public static function team_reward()
    {
        $agents = Agent::where('status', '1')
                             ->get();

        foreach($agents as $agent){
            $get_own_total_sales = GlobalController::get_user_accumulated_sales($agent->code);

            $get_own_current_tier = SettingTeamDividend::where('target_box', '<=', $get_own_total_sales)
                                                      ->orderBy('target_box', 'desc')
                                                      ->first();

            if(!empty($get_own_current_tier->amount)){
                $get_downlines = agent::where('master_id', $agent->code)
                                         ->get();

                foreach($get_downlines as $get_downline){
                    // echo $get_downline->code.' - '.$agent->code." | ";
                    $get_downline_total_sales = GlobalController::get_user_accumulated_sales($get_downline->code);

                    $get_downline_current_tier = SettingTeamDividend::where('target_box', '<=', $get_downline_total_sales)
                                                      ->orderBy('target_box', 'desc')
                                                      ->first();

                    if(!empty($get_downline_current_tier->id)){
                        $sales_percentage_balance = $get_own_current_tier->amount - $get_downline_current_tier->amount;

                        if($sales_percentage_balance > 0){
                            $total_team_bonus = $get_downline_total_sales * $sales_percentage_balance / 100;

                            if($total_team_bonus > 0){
                                $input_team = [];
                                $input_team['type'] = '4';
                                $input_team['user_id'] = $agent->code;
                                $input_team['user_tier'] = $get_own_current_tier->amount;
                                $input_team['user_by'] = $get_downline->code;
                                $input_team['user_by_tier'] = $get_downline_current_tier->amount;
                                $input_team['product_amount'] = $get_downline_total_sales;
                                $input_team['comm_pa_type'] = "Percentage";
                                $input_team['comm_pa'] = $sales_percentage_balance;
                                $input_team['comm_amount'] = $total_team_bonus;
                                $input_team['comm_desc'] = "Team Reward";
                                $input_team['comm_desc_cn'] = "团队奖励";
                                $input_team['run_date'] = date('Y-m', strtotime('-1 day'));
                                $input_team['status'] = "1";

                                AffiliateCommission::create($input_team);
                            }
                        }
                    }else{
                        $total_team_bonus = $get_downline_total_sales * $get_own_current_tier->amount / 100;

                        if($total_team_bonus > 0){
                            $input_team = [];
                            $input_team['type'] = '4';
                            $input_team['user_id'] = $agent->code;
                            $input_team['user_tier'] = $get_own_current_tier->amount;
                            $input_team['user_by'] = $get_downline->code;
                            $input_team['user_by_tier'] = 0;
                            $input_team['product_amount'] = $get_downline_total_sales;
                            $input_team['comm_pa_type'] = "Percentage";
                            $input_team['comm_pa'] = $get_own_current_tier->amount;
                            $input_team['comm_amount'] = $total_team_bonus;
                            $input_team['comm_desc'] = "Team Reward";
                            $input_team['comm_desc_cn'] = "团队奖励";
                            $input_team['run_date'] = date('Y-m', strtotime('-1 day'));
                            $input_team['status'] = "1";

                            AffiliateCommission::create($input_team);
                        }
                    }
                }
            }
        }
    }

    public static function get_team_reward_percentage($agent_code, $startDate, $endDate)
    {
        $commission = AffiliateCommission::where('type', 4)
                        ->where('user_id', $agent_code)
                        ->whereBetween('run_date', [$startDate, $endDate])
                        ->first();

        return $commission->user_tier ?? 0;
    }

    public static function get_team_reward_amount($agent_code, $startDate, $endDate)
    {
        $commission = AffiliateCommission::where('type', 4)
                        ->where('user_id', $agent_code)
                        ->whereBetween('run_date', [$startDate, $endDate])
                        ->sum('comm_amount');

        return $commission ?? 0;
    }

    public static function give_performance_reward($code)
    {
        $agent = Agent::where('code', $code)
                         ->first();

        if(!empty($agent->id)){
            $personal_performance_reward = SettingPerformanceDividend::where('lvl', $agent->lvl)
                                                                    ->where('status', '1')
                                                                    ->first();

            if(!empty($personal_performance_reward->id)){
                $performance_reward_setting = SettingPerformanceMain::first();

                // if(!empty($performance_reward_setting->date_update)){
                    // if(date('d') >= $performance_reward_setting->date_update){
                    //     $startMonth = date('m');
                    //     $endMonth = date('m', strtotime('+1 month'));

                    //     $startDate = date('Y-'.$startMonth.'-d');
                    //     $endDate = date('Y-'.$endMonth.'-d');
                    // }else{
                    //     $startMonth = date('m', strtotime('-1 month'));
                    //     $endMonth = date('m');

                    //     $startDate = date('Y-'.$startMonth.'-d');
                    //     $endDate = date('Y-'.$endMonth.'-d');
                    // }

                    $startDate = date('Y-m-01');
                    $endDate = date('Y-m-t');

                    $personal_total_sales = 0;

                    $own_transaction = Transaction::select(DB::raw('COALESCE(SUM(grand_total - shipping_fee), 0) AS sales'))
                                      ->where('user_id', $agent->code)
                                      ->whereBetween(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), array($startDate, $endDate))
                                      ->where('status', '1')
                                      ->first();

                    $personal_total_sales = $personal_total_sales + $own_transaction->sales;

                    $direct_customers = User::where('master_id', $agent->code)
                                            ->get();

                    foreach($direct_customers as $direct_c){
                        $direct_customer_transaction = Transaction::select(DB::raw('COALESCE(SUM(grand_total - shipping_fee), 0) AS sales'))
                                                  ->where('user_id', $direct_c->code)
                                                  ->whereBetween(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), array($startDate, $endDate))
                                                  ->where('status', '1')
                                                  ->first();

                        $personal_total_sales = $personal_total_sales + $direct_customer_transaction->sales;
                    }

                    if(!empty($personal_performance_reward->target) && $personal_total_sales >= $personal_performance_reward->target){
                        $comm_amount = $personal_total_sales * $personal_performance_reward->amount;
                        $comm_amount = $comm_amount / 100;

                        $input_performance_reward = [];
                        $input_performance_reward['type'] = '95';
                        $input_performance_reward['user_id'] = $agent->code;
                        $input_performance_reward['product_amount'] = $personal_total_sales;
                        $input_performance_reward['comm_pa_type'] = "Percentage";
                        $input_performance_reward['comm_pa'] = $personal_performance_reward->amount;
                        $input_performance_reward['comm_amount'] = $comm_amount;
                        $input_performance_reward['comm_desc'] = "Performance Reward";
                        $input_performance_reward['comm_desc_cn'] = "绩效奖励";
                        $input_performance_reward['run_date'] = date('Y-m'); 
                        $input_performance_reward['status'] = "1";

                        AffiliateCommission::create($input_performance_reward);
                    }
                // }

            }
        }
    }

    public static function annual_prize_pool($year)
    {
        try{
            \DB::beginTransaction();

            $condition = SettingPrizePoolCondition::first();

            if(!empty($condition->type)){
                $startYear = date($year.'-01-01');
                $endYear = date($year.'-12-31');

                $total_sales = GlobalController::get_total_sales(date('Y'));

                if(!empty($condition->target)){
                    if($total_sales >= $condition->target){
                        $split_pool = 0;

                        if($condition->type == 'Percentage'){
                            $split_pool = $total_sales * $condition->split_sales_percentage;
                            $split_pool = $split_pool / 100;
                        }

                        $get_top_ten_agent_sales = GlobalController::get_top_ten_agent_sales($year);

                        $top_ten_agent = [];
                        $key = 0;
                        foreach($get_top_ten_agent_sales as $agent_code => $agent_sales){
                            $key++;
                            $top_ten_agent[$key] = $agent_code;
                        }

                        for ($i=1; $i <= 10 ; $i++) { 
                            $setting_prize_pool = SettingPrizePool::where('position', $i)
                                                                  ->where('status', '1')
                                                                  ->first();

                            if(!empty($setting_prize_pool->amount)){
                                $amount = 0;

                                if($condition->type == 'Percentage'){
                                    $amount = $split_pool * $setting_prize_pool->amount;
                                    $amount = $amount / 100;
                                }elseif($condition->type == 'Amount'){
                                    $amount = $setting_prize_pool->amount;
                                }

                                if(!empty($top_ten_agent[$i]) && $amount > 0){
                                    $input_same = new AffiliateCommission();
                                    $input_same->type = '99';
                                    $input_same->user_id = $top_ten_agent[$i];
                                    $input_same->product_amount = $get_top_ten_agent_sales[$top_ten_agent[$i]];
                                    $input_same->total_sales = $total_sales;
                                    $input_same->comm_pa_type = $condition->type;
                                    $input_same->comm_pa = $setting_prize_pool->amount;
                                    $input_same->comm_amount = $amount;
                                    $input_same->comm_desc = "Prize Pool Reward For Year ".$year." (Position: ".$i.")";
                                    $input_same->comm_desc_cn = "年度奖金池奖励 ".$year." (Position: ".$i.")";
                                    $input_same->status = "1";

                                    $input_same->save();
                                }
                            }   
                        }
                    }
                }
            }

            \DB::commit();

            return "ok";
        }catch (\Exception $e){
            \DB::rollback();
            return $e->getMessage().' - '.$e->getLine();
        }catch(\Error $e){
            \DB::rollback();
            return $e->getMessage().' - '.$e->getLine();
        } 
    }

    public static function referral_bonus($code, $lvl)
    {
        
        try{
            \DB::beginTransaction();

            $website_setting = GlobalController::website_setting();

            $bonus_agent_enable = $website_setting->bonus_agent_enable;
            $bonus_member_enable = $website_setting->bonus_member_enable;

            $own_lvl = GlobalController::get_lvl_detail($lvl);
            $own_lvl_cn = GlobalController::get_lvl_detail($lvl,1);

            $affs = [];
            if($bonus_agent_enable == 1 && $website_setting->referral_enable == 1){
                $affs = Affiliate::select('affiliates.*', 'm.lvl', 'm.id as is_agent')
                                 ->join('agents as m', 'm.code', 'affiliates.user_id')
                                 ->where('affiliates.affiliate_id', $code)
                                 ->where('m.status', '1')
                                 ->orderBy('affiliates.sort_level', 'asc')
                                 ->get();                    
            }

            if($bonus_member_enable == 1 && $website_setting->member_referral_enable == 1){
                $affs = Affiliate::select('affiliates.*', 'm.lvl', 'm.id as is_member')
                                 ->join('users as m', 'm.code', 'affiliates.user_id')
                                 ->where('affiliates.affiliate_id', $code)
                                 ->where('m.status', '1')
                                 ->orderBy('affiliates.sort_level', 'asc')
                                 ->get();
            }

            if(($bonus_agent_enable == 1 && $website_setting->referral_enable == 1) && ($bonus_member_enable == 1  && $website_setting->member_referral_enable == 1)){
                $affs = Affiliate::select('affiliates.*', 
                                          DB::raw('COALESCE(m.lvl, u.lvl) as lvl'), 
                                          'm.id as is_agent', 
                                          'u.id as is_member')
                                 ->leftJoin('agents as m', 'm.code', 'affiliates.user_id')
                                 ->leftJoin('users as u', 'u.code', 'affiliates.user_id')
                                 ->where('affiliates.affiliate_id', $code)
                                 ->where(DB::raw('COALESCE(m.status, u.status)'), '1')
                                 ->orderBy('affiliates.sort_level', 'asc')
                                 ->get();

            }

            $sort_level = 1;
            foreach($affs as $aff){
                if($sort_level == 1){
                    $b = 'st';
                }elseif($sort_level == 2){
                    $b = 'nd';
                }elseif($sort_level == 3){
                    $b = 'rd';
                }else{
                    $b = 'th';
                }

                if(!empty($aff->is_member)){
                    $upline_level = GlobalController::get_lvl_detail($aff->lvl);
                    $upline_level_cn = GlobalController::get_lvl_detail($aff->lvl,1);

                    if($sort_level <= 1){

                        if(!empty($website_setting->member_referral_amount) && !empty($website_setting->member_referral_target)){
                            $referral_count = !empty($website_setting->member_referral_target) ? $website_setting->member_referral_target : 1;
                            $get_upline['totalDownline'] = 0;

                            if($bonus_agent_enable == 1){
                                $get_upline = Agent::select(DB::raw('COUNT(id) as totalDownline'))->where('master_id', $aff->user_id)->first();
                            }

                            if($bonus_member_enable == 1){
                                $get_upline = User::select(DB::raw('COUNT(id) as totalDownline'))->where('master_id', $aff->user_id)->first();
                            }

                            if($bonus_agent_enable == 1 && $bonus_member_enable == 1){
                                $count_agent = Agent::select(DB::raw('COUNT(id) as totalDownline'))->where('master_id', $aff->user_id)->first();
                                $count_member = User::select(DB::raw('COUNT(id) as totalDownline'))->where('master_id', $aff->user_id)->first();
                                $get_upline['totalDownline'] = $count_agent->totalDownline + $count_member->totalDownline;
                            }
                            
                            if($get_upline->totalDownline % $website_setting->member_referral_target == 0){
                                $commission = new AffiliateCommission();

                                $authorise_merchant = !empty($_COOKIE['vmerchant']) ? $_COOKIE['vmerchant'] : '';
                                $get_authorise_status = GlobalController::check_autorize_status($authorise_merchant);
                                if($get_authorise_status['status'] == 1){
                                $commission->merchant_id = !empty($get_authorise_status['result']['code']) ? $get_authorise_status['result']['code'] : '';
                                }

                                $commission->type = 6;
                                $commission->user_id = $aff->user_id;
                                $commission->user_by = $code;
                                $commission->product_amount = $website_setting->member_referral_amount;
                                $commission->comm_pa_type = "Amount";
                                $commission->comm_pa = $website_setting->member_referral_amount;
                                $commission->comm_amount = $website_setting->member_referral_amount;
                                $commission->comm_desc = "Referral Reward: ".$upline_level." -> ".$own_lvl;
                                $commission->comm_desc_cn = "推荐奖励: ".$upline_level_cn." -> ".$own_lvl_cn;
                                $commission->status = "1";
                                $commission->save();

                                $sort_level++;
                            }
                        }
                    }
                }else{
                    $upline_level = GlobalController::get_lvl_detail($aff->lvl);
                    $upline_level_cn = GlobalController::get_lvl_detail($aff->lvl,1);

                    if($sort_level <= 1){
                        $referral_reward = SettingRefferalReward::where('agent_lvl', $aff->lvl)
                                                                ->first();

                        if(!empty($referral_reward->amount) && !empty($referral_reward->direct_downlines_no)){
                            // throw new \Exception($referral_reward->id);
                            $referral_count = !empty($referral_reward->direct_downlines_no) ? $referral_reward->direct_downlines_no : 1;
                            $get_upline['totalDownline'] = 0;

                            if($bonus_agent_enable == 1){
                                $get_upline = Agent::select(DB::raw('COUNT(id) as totalDownline'))->where('master_id', $aff->user_id)->first();
                            }

                            if($bonus_member_enable == 1){
                                $get_upline = User::select(DB::raw('COUNT(id) as totalDownline'))->where('master_id', $aff->user_id)->first();
                            }

                            if($bonus_agent_enable == 1 && $bonus_member_enable == 1){
                                $count_agent = Agent::select(DB::raw('COUNT(id) as totalDownline'))->where('master_id', $aff->user_id)->first();
                                $count_member = User::select(DB::raw('COUNT(id) as totalDownline'))->where('master_id', $aff->user_id)->first();
                                $get_upline['totalDownline'] = $count_agent->totalDownline + $count_member->totalDownline;
                            }
                            
                            if($get_upline->totalDownline % $referral_reward->direct_downlines_no == 0){
                                $commission = new AffiliateCommission();

                                $authorise_merchant = !empty($_COOKIE['vmerchant']) ? $_COOKIE['vmerchant'] : '';
                                $get_authorise_status = GlobalController::check_autorize_status($authorise_merchant);
                                if($get_authorise_status['status'] == 1){
                                $commission->merchant_id = !empty($get_authorise_status['result']['code']) ? $get_authorise_status['result']['code'] : '';
                                }

                                $commission->type = 6;
                                $commission->user_id = $aff->user_id;
                                $commission->user_by = $code;
                                $commission->product_amount = $referral_reward->amount;
                                $commission->comm_pa_type = "Amount";
                                $commission->comm_pa = $referral_reward->amount;
                                $commission->comm_amount = $referral_reward->amount;
                                $commission->comm_desc = "Referral Reward: ".$upline_level." -> ".$own_lvl;
                                $commission->comm_desc_cn = "推荐奖励: ".$upline_level_cn." -> ".$own_lvl_cn;
                                $commission->status = "1";
                                $commission->save();

                                $sort_level++;
                            }
                        }
                    }
                }
            }

            \DB::commit();

            return "ok";

        }catch (\Exception $e){
            \DB::rollback();
            return $e->getMessage().' - '.$e->getLine();
        }catch(\Error $e){
            \DB::rollback();
            return $e->getMessage().' - '.$e->getLine();
        }
    }

    public static function topup_bonus_pv($topup_no)
    {
        try{
            \DB::beginTransaction();

            $website_setting = WebsiteSetting::find(1);

            if($website_setting->topup_bonus_pv_enable == 1){
                $topup = TopupTransaction::where('topup_no', $topup_no)->first();
                // throw new \Exception($website_setting->topup_bonus_pv_enable.' - '.$website_setting->topup_rm_to_pv.' - '.$topup_no);
                if(!empty($topup->id)){

                    $setting_topups = SettingTopup::where('topup_amount', '<=', $topup->amount)->orderBy('topup_amount', 'desc')->first();

                    if(!empty($setting_topups->id)){
                        $topup_pv = new TopupPv();
                        $topup_pv->user_id = $topup->user_id;
                        $topup_pv->pv_amount = $setting_topups->profit_amount;
                        $topup_pv->status = $topup->status;
                        $topup_pv->save();                        
                    }
                }
            }
            \DB::commit();

            return "ok";
        }catch (\Exception $e){
            \DB::rollback();
            return $e->getMessage();
        }catch(\Error $e){
            \DB::rollback();
            return $e->getMessage();
        }
    }

    public static function transaction_voucher_assign($transaction_id)
    {
        try{
            \DB::beginTransaction();

            $transaction = Transaction::find($transaction_id);

            $get_packages = TransactionDetail::with(['get_packages'])->where('transaction_id', $transaction_id)->get();

            foreach($get_packages as $get_package){
                foreach($get_package->get_packages as $package){
                    if(!empty($package->voucher_id)){
                        for($v=0; $v<$package->quantity; $v++){
                            $applied_promotions = new AppliedPromotion();
                            $applied_promotions->promotion_id = $package->voucher_id;
                            $applied_promotions->user_id = $transaction->user_id;
                            $applied_promotions->transaction_id = $transaction->transaction_no;
                            $applied_promotions->status = 99;
                            $applied_promotions->save();
                        }
                    }
                }
            }
            \DB::commit();

            return "ok";
        }catch (\Exception $e){
            \DB::rollback();
            return $e->getMessage();
        }catch(\Error $e){
            \DB::rollback();
            return $e->getMessage();
        }
    }

    public static function upgrade_agent_with_package($transaction_no)
    {
        try{
            \DB::beginTransaction();
            $transaction = Transaction::where('transaction_no', $transaction_no)->first();
            if(empty($transaction->id)){
                throw new \Exception('Transaction No Error');
            }
            $user_details = "";
            $agent = Agent::where('code', $transaction->user_id)->first();
            if(!empty($agent->id)){
                $user_details = $agent;
            }

            $user = User::where('code', $transaction->user_id)->first();
            if(!empty($user->id)){
                $user_details = $user;
            }

            if(empty($user_details->id)){
                throw new \Exception('user Not Found');
            }

            foreach($transaction->get_transaction_details_with_upgrade_level as $detail){

                //isCustomer
                if(!empty($user->id)){

                    if(isset($new_agent)){
                        $new_agent->lvl = $detail->level_up;
                        $new_agent->save();
                    }else{
                        $agent_display_code = GlobalController::AgentDisplayCode();

                        $new_agent = new Agent();
                        $new_agent->master_id = $user->master_id;
                        $new_agent->code = GlobalController::AgentCode();
                        $new_agent->display_code = $agent_display_code[0];
                        $new_agent->display_running_no = $agent_display_code[1];
                        $new_agent->country_code = $user->country_code;
                        $new_agent->phone = $user->phone;
                        $new_agent->email = $user->email;
                        $new_agent->dob = $user->dob;
                        $new_agent->password = $user->password;
                        $new_agent->f_name = $user->f_name;
                        $new_agent->ic = $user->ic;
                        $new_agent->gender = $user->gender;
                        $new_agent->lvl = $detail->level_up;
                        $new_agent->verify_status = 1;
                        $new_agent->status = 1;

                        $new_agent->save();

                        $affiliate_user = Affiliate::where('user_id', $user->code)->update(['user_id'=>$new_agent->code]);

                        $affiliate_user_by = AffiliateCommission::where('user_id', $user->code)->update(['user_by'=>$new_agent->code]);

                        $affiliate = Affiliate::where('affiliate_id', $user->code)->update(['affiliate_id'=>$new_agent->code]);

                        $user_shipping_address = UserShippingAddress::where('user_id', $user->code)->update(['user_id'=>$new_agent->code]);

                        $transactions = Transaction::where('user_id', $user->code)->update(['user_id'=>$new_agent->code]);

                        $applied_promotions = AppliedPromotion::where('user_id', $user->code)->update(['user_id'=>$new_agent->code]);

                        $cart = Cart::where('user_id', $user->code)->update(['user_id'=>$new_agent->code]);

                        $update_user = User::find($user->id);
                        $update_user->email = $user->email.'_upgraded_'.$transaction->transaction_no;
                        $update_user->phone = $user->phone.'_upgraded_'.$transaction->transaction_no;
                        $update_user->ic = $user->ic.'_upgraded_'.$transaction->transaction_no;
                        $update_user->upgraded = 1;
                        $update_user->upgraded_date = date('Y-m-d H:i:s');
                        $update_user->status = 3;
                        $update_user->save();
                    }

                    $upgrade_agent_record = GlobalController::upgrade_agent_record($new_agent->code, $detail->level_up);
                    if($upgrade_agent_record != 'ok'){
                        throw new \Exception($upgrade_agent_record);
                    }

                    if(!empty($detail->referral_bonus)){
                        $referral_bonus = GlobalController::referral_bonus($new_agent->code, $detail->level_up, ($detail->referral_bonus * $detail->quantity), $transaction_no);
                        if($referral_bonus != 'ok'){
                            throw new \Exception($referral_bonus);
                        }                        
                    }
                }

                if(!empty($agent->id)){

                    $upgrade_agent_record = GlobalController::upgrade_agent_record($transaction->user_id, $detail->level_up);
                    if($upgrade_agent_record != 'ok'){
                        throw new \Exception($upgrade_agent_record);
                    }

                    if(!empty($detail->referral_bonus)){
                        $referral_bonus = GlobalController::referral_bonus($transaction->user_id, $detail->level_up, ($detail->referral_bonus * $detail->quantity), $transaction_no);
                        if($referral_bonus != 'ok'){
                            throw new \Exception($referral_bonus);
                        }
                    }

                    if($detail->level_up > $agent->lvl){
                        $agent->lvl = $detail->level_up;
                        $agent->save();                        
                    }
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

        return "ok";
    }



    public static function get_user_accumulated_sales($code, $month = 0)
    {
        $total = 0;
        // }

        $transactions = Transaction::select(DB::raw('SUM(grand_total - shipping_fee) as totalGetPV'))
                                   ->where('user_id', $code)
                                   ->where('transactions.status', '1');
        if(!empty($month)){
        $transactions = $transactions->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m")'), $month);
        }
        $transactions = $transactions->first();

        $affs = Affiliate::select(DB::raw('SUM(grand_total - shipping_fee) as totalGetPV'))
                         ->join('merchants as m', 'm.code', 'affiliates.affiliate_id')
                         ->join('transactions as t', 't.user_id', 'm.code')
                         ->where('t.status', '1')
                         ->where('affiliates.user_id', $code);
        if(!empty($month)){
        $affs = $affs->where(DB::raw('DATE_FORMAT(t.created_at, "%Y-%m")'), $month);
        }
        $affs = $affs->first();
        

        $downline_total_pv = $affs->totalGetPV;

        $affs_customers = Affiliate::select(DB::raw('SUM(d.get_pv * d.quantity) as totalGetPV'))
                                   ->join('merchants as m', 'm.code', 'affiliates.affiliate_id')
                                   ->join('users as u', 'u.master_id', 'm.code')
                                   ->join('transactions as t', 't.user_id', 'u.code')
                                   ->join('transaction_details as d', 'd.transaction_id', 't.id')
                                   ->where('t.status', '1')
                                   ->where('affiliates.user_id', $code);
        if(!empty($month)){
        $affs_customers = $affs_customers->where(DB::raw('DATE_FORMAT(t.created_at, "%Y-%m")'), $month);
        }
        $affs_customers = $affs_customers->first();

        $downline_customer_total_pv = $affs_customers->totalGetPV;
        // exit();

        $mb_transactions = Transaction::select(DB::raw('SUM(d.get_pv * d.quantity) as totalGetPV'))
                               ->join('transaction_details as d', 'd.transaction_id', 'transactions.id')
                               ->join('users as u', 'transactions.user_id', 'u.code')
                               ->where('u.master_id', $code)
                               ->where('transactions.status', '1');
        if(!empty($month)){
        $mb_transactions = $mb_transactions->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m")'), $month);
        }
        $mb_transactions = $mb_transactions->first();

        $member_total_pv = $mb_transactions->totalGetPV;

        return (!empty($transactions->totalGetPV) ? $transactions->totalGetPV : 0) + $downline_total_pv + $downline_customer_total_pv + $member_total_pv;
    }

    public static function upgrade_agent_record($code, $level)
    {
        try{
            \DB::beginTransaction();

            $agent = Agent::where('code', $code)->first();
            if(empty($agent->id)){
                throw new \Exception('Agent Not Found');
            }

            $check_record_exist = AgentLevelRecord::where('user_id', $code)
                                                  ->where('level', $level)
                                                  ->first();

            if(empty($check_record_exist->id)){
                $level_up_record = new AgentLevelRecord();
                $level_up_record->user_id = $agent->code;
                $level_up_record->level = $level;
                $level_up_record->level_up_by = !empty(Auth::user()->code) ? Auth::user()->code : NULL;
                $level_up_record->save();
            }


            \DB::commit();

        }catch (\Exception $e){
            \DB::rollback();
            return $e->getMessage().' - '.$e->getLine();
        }catch(\Error $e){
            \DB::rollback();
            return $e->getMessage().' - '.$e->getLine();
        }

        return "ok";
    }

    public static function get_shipping_fee($state, $weight, $country = NULL)
    {
        $totalshipping_fees = 0;
        
        if(empty($country) || $country == 160){
            if($state != '11' && $state != '12' && $state != '15'){
                $shipping_fees = SettingShippingFee::where('area', 'west')
                                                    ->where('weight', '<=', ceil($weight))
                                                    ->orderBy('weight', 'desc')
                                                    ->first();

                if(!empty($shipping_fees->id)){
                    $totalshipping_fees = !empty($shipping_fees->shipping_fee) ? $shipping_fees->shipping_fee : 0;
                }
            }else{
                $shipping_fees = SettingShippingFee::where('area', 'east')
                                                ->where('weight', '<=', ceil($weight))
                                                ->orderBy('weight', 'desc')
                                                ->first();

                if(!empty($shipping_fees->id)){
                    $totalshipping_fees = !empty($shipping_fees->shipping_fee) ? $shipping_fees->shipping_fee : 0;                
                }
            }
        }else{
            $shipping_fees = SettingShippingFee::where('country_id', $country)
                                                ->where('weight', '<=', ceil($weight))
                                                ->orderBy('weight', 'desc')
                                                ->first();
            if(!empty($shipping_fees->id)){
                $totalshipping_fees = $shipping_fees->shipping_fee;
            }
        }

        return $totalshipping_fees;
    }

    public static function GenerateTransactionNo()
    {
        $year = date('y');
        $month = date('m');
        $combine = $year.$month;
        $transaction = Transaction::select(DB::raw('COUNT(id) AS TotalTransaction'))
                                  ->first();
        $TotalTransaction = $transaction->TotalTransaction + 1;
        if(strlen($TotalTransaction) == 1){
            $tNo = "INV".$combine."-0000".$TotalTransaction;
        }elseif(strlen($TotalTransaction) == 2){
            $tNo = "INV".$combine."-000".$TotalTransaction;
        }elseif(strlen($TotalTransaction) == 3){
            $tNo = "INV".$combine."-00".$TotalTransaction;
        }elseif(strlen($TotalTransaction) == 4){
            $tNo = "INV".$combine."-0".$TotalTransaction;
        }else{
            $tNo = "INV".$combine."-".$TotalTransaction;
        }
        return $tNo;
    } 

    public static function GenerateTopupNo()
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

    public static function get_add_on_sub_item_price($code = NULL, $add_on_id, $product_id, $variation_id, $second_variation_id)
    {
        $sub_item = AddOnDealSubItem::where('add_on_deal_sub_items.add_on_id',$add_on_id)
                                                ->where('add_on_deal_sub_items.status','1')
                                                ->where('add_on_deal_sub_items.product_id',$product_id)
                                                ->where('add_on_deal_sub_items.variation_id', $variation_id)
                                                ->where('add_on_deal_sub_items.second_variation_id', $second_variation_id)
                                                ->first();

        // $product_pricing = GlobalController::get_product_pricing(md5($product_id), $code, $variation_id, $second_variation_id);

        $total = 0;

        // $total = $product_pricing['product_price'];

        if(!empty($sub_item->id)){
            // $total = $total * (100 - $sub_item->add_on_discount);
            // $total = $total / 100;
            if(!empty($sub_item->add_on_price)){
                $total = $sub_item->add_on_price;
            }else{
                $original_price = GlobalController::get_product_pricing(md5($product_id), $code, $variation_id, $second_variation_id);

                $total = $original_price['product_price'];
            }
        }

        return $total;
    }

    public static function get_available_countries()
    {
        $countries = TblCountry::whereIn('country_id', ['160'])
                               ->orderBy('country_name', 'asc')
                               ->get();

        return $countries;
    }

    //Wallet
    public static function get_cash_wallet_balance($code)
    {
        
        $balance = AffiliateCommission::select(DB::raw('SUM(comm_amount) as totalBalance'))
                                      ->where('user_id', $code)
                                      ->where('status', '1')
                                      ->first();

        $withdrawal = WithdrawalTransaction::select(DB::raw('SUM(amount) as totalWithdrawal'))
                                             ->where('user_id', $code)
                                             ->whereIn('status', ['1', '99'])
                                             ->first();

        $transaction = Transaction::select(DB::raw('SUM(grand_total) as totalPurchase'))
                                  ->where('user_id', $code)
                                  ->where('status', '1')
                                  ->where('mall', '1')
                                  ->first();

        $adjustIn = AdjustCashWallet::select(DB::raw('SUM(amount) as totalAdjustIn'))
                                    ->where('user_id', $code)
                                    ->where('type', '1')
                                    ->first();

        $adjustOut = AdjustCashWallet::select(DB::raw('SUM(amount) as totalAdjustOut'))
                                    ->where('user_id', $code)
                                    ->where('type', '2')
                                    ->first();

        $transfer_cash_to_topup = AdjustCashToTopup::select(DB::raw('SUM(amount) as totalBalance'))
                                                   ->where('user_by', $code)
                                                   ->first();

        $totalBalance = 0;
        
        $totalBalance = $balance->totalBalance - $withdrawal->totalWithdrawal - $transaction->totalPurchase + $adjustIn->totalAdjustIn - $adjustOut->totalAdjustOut - $transfer_cash_to_topup->totalBalance;
        

        return $totalBalance;
    }

    public static function get_topup_wallet_balance($code)
    {
        
        $balance = TopupTransaction::select(DB::raw('SUM(amount) as totalBalance'))
                                   ->where('user_id', $code)
                                   ->where('status', '1')
                                   ->first();

        $transaction = Transaction::select(DB::raw('SUM(grand_total) as totalPurchase'))
                                  ->where('user_id', $code)
                                  ->where('status', '1')
                                  ->where('mall', '2')
                                  ->first();

        $adjustIn = AdjustTopupWallet::select(DB::raw('SUM(amount) as totalAdjustIn'))
                                     ->where('user_id', $code)
                                     ->where('type', '1')
                                     ->first();

        $adjustOut = AdjustTopupWallet::select(DB::raw('SUM(amount) as totalAdjustOut'))
                                      ->where('user_id', $code)
                                      ->where('type', '2')
                                      ->first();

        $transfer_cash_to_topup = AdjustCashToTopup::select(DB::raw('SUM(amount) as totalBalance'))
                                                   ->where('user_id', $code)
                                                   ->first();

        $totalBalance = 0;
        
        $totalBalance = $balance->totalBalance - $transaction->totalPurchase + $adjustIn->totalAdjustIn - $adjustOut->totalAdjustOut + $transfer_cash_to_topup->totalBalance;
        

        return $totalBalance;
    }

    public static function get_point_wallet($code)
    {
        $transaction = Transaction::select(DB::raw('SUM(d.get_point * d.quantity) as totalPoint'))
                                  ->join('transaction_details as d', 'd.transaction_id', 'transactions.id')
                                  ->where('transactions.user_id', $code)
                                  ->where('transactions.status', '1')
                                  ->first();

        $transaction_purchase = Transaction::select(DB::raw('SUM(IF(grand_total_point > 0, grand_total_point , grand_total)) as totalPoint'))
                                  ->where('transactions.user_id', $code)
                                  ->where('transactions.status', '1')
                                  ->where('transactions.pv_purchase', '1')
                                  ->first();

        $topup_pv = TopupPv::select(DB::raw('SUM(pv_amount) as totalPoint'))
                           ->where('user_id', $code)
                           ->where('status', '1')
                           ->first();

        $adjustIn = AdjustPointWallet::select(DB::raw('SUM(amount) as totalAdjustIn'))
        ->where('user_id', $code)
        ->where('type', '1')
        ->first();

        $adjustOut = AdjustPointWallet::select(DB::raw('SUM(amount) as totalAdjustOut'))
                                    ->where('user_id', $code)
                                    ->where('type', '2')
                                    ->first();

        $totalBalance = 0;

        return $transaction->totalPoint - $transaction_purchase->totalPoint + $topup_pv->totalPoint + $adjustIn->totalAdjustIn - $adjustOut->totalAdjustOut;
    }

    public static function checkUserBirthMonthToday($code)
    {
        $agent = Agent::where('code', $code)
                            ->first();
        $user = User::where('code', $code)
                    ->first();

        $dob = NULL;
        if(!empty($agent->id)){
            $dob = $agent->dob;
        }
        if(!empty($user->id)){
            $dob = $user->dob;
        }   

        if(!empty($dob)){
            $dob = str_replace('/', '-', $dob);
            $formmated_dob = date('m', strtotime($dob));

            $today = date('m');
          
            if($today == $formmated_dob){
                return true;
            }else{
                return false;
            }
        }

        return false;
    }

    public static function get_current_flash_sales()
    {
        $currentDateTime = date('Y-m-d H:i:s');

        $active_flash_sale = FlashSale::where(function ($query) use ($currentDateTime){
                                    $query->where('start', '<', $currentDateTime);
                                    $query->where('end', '>', $currentDateTime);
                               })
                              ->where('status', '1')
                              ->first();

        return !empty($active_flash_sale) ? $active_flash_sale : NULL;
    }

    public static function get_current_flash_sale_product_detail($product_id, $variation_id = NULL, $second_variation_id = NULL)
    {
        $active_flash_sale = GlobalController::get_current_flash_sales();

        $check_variation_active = ProductVariation::find($variation_id);

        $check_second_variation_active = ProductSecondVariation::find($second_variation_id);

        if(!empty($active_flash_sale)){
            $flash_sale_product_detail = FlashSaleProductDetail::where('flash_sale_id', $active_flash_sale->id)
                                                               ->where('product_id', $product_id);

            if(!empty($check_variation_active->id)){
                $flash_sale_product_detail = $flash_sale_product_detail->where('variation_id', $variation_id);
            }

            if(!empty($check_second_variation_active->id)){
                $flash_sale_product_detail = $flash_sale_product_detail->where('second_variation_id', $second_variation_id);
            }

            $flash_sale_product_detail = $flash_sale_product_detail->where('status', '1')
                                                                   ->first();
        }

        return !empty($flash_sale_product_detail) ? $flash_sale_product_detail : NULL;
    }

    public static function check_if_product_has_flash_sale_active($product_id, $variation_id = NULL, $second_variation_id = NULL)
    {
        $active_flash_sale = GlobalController::get_current_flash_sales();

        $check_variation_active = ProductVariation::find($variation_id);

        $check_second_variation_active = ProductSecondVariation::find($second_variation_id);

        if(!empty($active_flash_sale)){
            $flash_sale_product_detail = FlashSaleProductDetail::where('flash_sale_id', $active_flash_sale->id)
                                                               ->where('product_id', $product_id);

            if(!empty($check_variation_active->id)){
                $flash_sale_product_detail = $flash_sale_product_detail->where('variation_id', $variation_id);
            }

            if(!empty($check_second_variation_active->id)){
                $flash_sale_product_detail = $flash_sale_product_detail->where('second_variation_id', $second_variation_id);
            }
            
            $flash_sale_product_detail = $flash_sale_product_detail->where('status', '1')
                                                                   ->first();

            if(!empty($flash_sale_product_detail->id)){
                return true;
            }
        }

        return false;
    }

    public static function checkFlashSaleProductQuantity($flash_sale_product_detail_id, $buyerCode, $add_quantity)
    {
        $product = FlashSaleProductDetail::find($flash_sale_product_detail_id);

        if(!empty($product->qty)){
            $totalQuantity = GlobalController::get_flash_sale_product_current_quantity($flash_sale_product_detail_id, $buyerCode);

            $totalQuantity += $add_quantity;

            if($totalQuantity > $product->qty){
                return false;
            }
        }

        return true;
    }

    public static function get_flash_sale_product_quantity($flash_sale_product_detail_id)
    {
        $product = FlashSaleProductDetail::find($flash_sale_product_detail_id);

        if(!empty($product->qty)){
            return $product->qty;
        }

        return NULL;
    }

    public static function get_flash_sale_product_current_quantity($flash_sale_product_detail_id, $buyerCode)
    {
        $product = FlashSaleProductDetail::find($flash_sale_product_detail_id);

        $totalQuantity = NULL;
        if(!empty($product->id)){
            $flash_sale = FlashSale::find($flash_sale_id);

            $get_flash_sale_product_price = FlashSaleProductPrice::where('flash_sale_product_detail_id', $product->id)
                                                                 ->where('status', '1')
                                                                 ->get();

            $get_flash_sale_product_price_array = $get_flash_sale_product_price->pluck('id')->toArray();

            $in_cart = Cart::select(DB::raw('SUM(qty) as totalInCart'))
                           ->where('user_id', $buyerCode)
                           ->where('product_id', $product->product_id)
                           ->where('sub_category_id', $product->variation_id)
                           ->where('second_sub_category_id', $product->second_variation_id)
                           ->first();

            $sold = TransactionDetail::select(DB::raw('SUM(quantity) as totalSold'))
                                              ->leftJoin('transactions as t', 't.id', 'transaction_details.transaction_id')
                                              ->whereIn('flash_sale_product_price_id', $get_flash_sale_product_price_array)
                                              ->where('t.status', '1')
                                              // ->where('product_id', $product->product_id)
                                              // ->where('variation_id', $product->variation_id)
                                              // ->where('second_variation_id', $product->second_variation_id)
                                              ->whereBetween(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d %H:%i:%s")'), array($flash_sale->start, $flash_sale->end))
                                              ->first();

            $totalQuantity = $in_cart->totalInCart + $sold->totalSold;
        }

        return $totalQuantity;
    }

    public static function update_flash_sale_product_status($buyerCode)
    {
        try {
            \DB::beginTransaction();

            $carts = Cart::where('user_id', $buyerCode)
                         ->get();

            $now = date('Y-m-d H:i:s');

            foreach ($carts as $key => $cart) {
                if(!empty($cart->flash_sale_product_id)){
                    $start = $cart->get_flash_sale_product_detail->get_flash_sale->start;
                    $end = $cart->get_flash_sale_product_detail->get_flash_sale->end;
                    if($cart->status == '1'){
                        if($now < $start || $now > $end){
                            $cart->status = '3';
                            $cart->save();
                        }
                    }elseif($cart->status == '3'){
                        if($now >= $start && $now <= $end){
                            $cart->status = '1';
                            $cart->save();
                        }
                    }
                }
            }

            \DB::commit();
        } catch (\Exception $e){
            \DB::rollback();
            return $e->getMessage();
        }
    }

    public static function personal_sales_reward($code)
    {
        try{
            \DB::beginTransaction();

            $total_sales = 0;

            $last_month_year = date("Y", strtotime('first day of -1 month'));
            $last_month_month = date('m', strtotime('first day of -1 month'));

            $current_agent = Agent::where('code', $code)
                                     ->first();

            $total_sales += GlobalController::get_user_sales($current_agent->code, $last_month_year, $last_month_month);

            $downline_affs = Affiliate::where('user_id', $current_agent->code)
                                      ->where('sort_level', '1')
                                      ->where('status', '1')
                                      ->get();

            foreach($downline_affs as $aff){
                $total_sales += GlobalController::get_user_sales($downline_affs->affiliate_id, $last_month_year, $last_month_month);
            }

            $achieved_setting = SettingPerformanceDividend::where('lvl', $current_agent->lvl)
                                                          ->where('target', '<=', $total_sales)
                                                          ->where('status', '1')
                                                          ->first();

            if(!empty($achieved_setting->id)){
                $comm_amount = $total_sales * $achieved_setting->amount;
                $comm_amount = $comm_amount / 100;

                $insert_comm = new AffiliateCommission();
                $insert_comm->type = '3';
                $insert_comm->user_id = $current_agent->code;
                $insert_comm->product_amount = $total_sales;
                $insert_comm->comm_pa_type = 'Percentage';
                $insert_comm->comm_pa = $achieved_setting->amount;
                $insert_comm->comm_amount = $comm_amount;
                $insert_comm->comm_desc = "Performance Sales Reward";
                $insert_comm->comm_desc_cn = "业绩销售奖励";
                $insert_comm->status = 1;
                $insert_comm->save();
            }

            \DB::commit();

            return "ok";

        }catch (\Exception $e){
            \DB::rollback();
            return $e->getMessage().' - '.$e->getLine();
        }catch(\Error $e){
            \DB::rollback();
            return $e->getMessage().' - '.$e->getLine();
        }
    }

    public static function get_user_sales($code, $year = NULL, $month = NULL)
    {
        $total_sales = 0;

        $own_transaction = Transaction::select(DB::raw('COALESCE(SUM(grand_total - shipping_fee), 0) AS sales'))
                                      ->where('user_id', $code);

        if(!empty($start) && !empty($end)){
            $own_transaction = $own_transaction->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), date($year."-".$month));
        }

        $own_transaction = $own_transaction->where('status', '1')
                                           ->first();

        $total_sales = $own_transaction->sales;

        return $total_sales;
    }

    public static function get_total_sales($startDate = NULL, $endDate = NULL)
    {
        $total_sales = 0;

        $transactions = Transaction::select(DB::raw('COALESCE(SUM(grand_total - shipping_fee), 0) as totalSales'))
                                   ->join('transaction_details as d', 'd.transaction_id', 'transactions.id');

        if(!empty($startDate) && !empty($endDate)){
            $transactions = $transactions->whereBetween(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), array($startDate, $endDate));
        }


        if(Auth::guard('merchant')->check()){
            $transactions = $transactions->where('merchant_id', Auth::user()->code);
        }

        $transactions = $transactions->where('transactions.status', '1')
                                     ->first();

        $total_sales = $transactions->totalSales;

        return $total_sales;
    }

    public static function checkCartLinkQuantity($cart_link_id)
    {
        $current_cart_link = CartLink::find($cart_link_id);

        $used_cart_link_transactions = Transaction::where('cart_link_id', $cart_link_id)
                                                  ->whereIn('status', ['1', '98'])
                                                  ->get();

        $leftover_quantity = 0;

        if(!empty($current_cart_link->qty)){
            $leftover_quantity = $current_cart_link->qty;
        }

        $leftover_quantity = $leftover_quantity - count($used_cart_link_transactions);

        return $leftover_quantity;
    }

    public static function price_difference_commission($transaction_no)
    {
        try {
            \DB::beginTransaction();

            $transaction = Transaction::where('transaction_no', $transaction_no)
                                      ->first();

            $customer = User::where('code', $transaction->user_id)
                            ->first();

            if(!empty($customer->id)){
                $upline_agent = Agent::where('code', $customer->master_id)
                                        ->first();

                if(!empty($upline_agent->id)){
                    $details = TransactionDetail::where('transaction_id', $transaction->id)
                                                ->get();

                    foreach($details as $detail){
                        $upline_price = GlobalController::get_product_pricing(md5($detail->product_id), $upline_agent->code, $detail->variation_id != '0' ? $detail->variation_id : NULL, $detail->second_variation_id != '0' ? $detail->second_variation_id : NULL, "", "", '');


                        $comm_amount = $detail->unit_price - $upline_price['product_price'];

                        $final_comm_amount = $comm_amount * $detail->quantity;

                        if($final_comm_amount > 0){
                            $insert = new AffiliateCommission();
                            $insert->type = '10';
                            $insert->user_id = $upline_agent->code;
                            $insert->user_by = $customer->code;
                            $insert->transaction_no = $transaction_no;
                            $insert->product_amount = $upline_price['product_price'];
                            $insert->comm_pa_type = 'Amount';
                            $insert->comm_pa = $comm_amount;
                            $insert->comm_amount = $final_comm_amount;
                            $insert->comm_desc = "Price Difference Commission";
                            $insert->comm_desc_cn = "差价佣金";
                            $insert->status = 1;
                            $insert->save();
                        }
                    }
                }
            }

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return $e->getMessage().' - '.$e->getLine();
        }

        return "ok";
    }

    public static function session_set_register_upline($code)
    {
        session(['upline' => $code]);
    }

    public static function get_own_store_stock_balance($code, $pid, $vid = 0, $svid = 0)
    {
        $my_stocks = Transaction::select(DB::raw('SUM(IF(dp.quantity > 0, (dp.quantity * d.quantity), d.quantity)) as totalStock'))
                               ->join('transaction_details as d', 'd.transaction_id', 'transactions.id')
                               ->join('products as p', 'p.id', 'd.product_id')
                               ->leftJoin('product_variations as v', 'v.id', 'd.variation_id')
                               ->leftJoin('product_second_variations as sv', 'sv.id', 'd.second_variation_id')
                               ->leftJoin('transaction_packages as dp', 'dp.detail_id', 'd.id')
                               ->where('transactions.status', '1')
                               ->where('d.store_in_stock', '1')
                               ->where('transactions.user_id', $code)
                               ->where(DB::raw('IF(dp.product_id > 0, dp.product_id, d.product_id)'), $pid);
        if(!empty($vid)){
        $my_stocks = $my_stocks->where(DB::raw('IF(dp.variation_id > 0, dp.variation_id, d.variation_id)'), $vid);
        }
        if(!empty($svid)){
        $my_stocks = $my_stocks->where(DB::raw('IF(dp.second_variation_id > 0, dp.second_variation_id, d.second_variation_id)'), $svid);
        }
        $my_stocks = $my_stocks->first();


        $deduct_from_customer = Transaction::select(DB::raw('SUM(IF(dp.quantity > 0, (dp.quantity * d.quantity), d.quantity)) as totalStock'))
                                           ->join('transaction_details as d', 'd.transaction_id', 'transactions.id')
                                           ->join('products as p', 'p.id', 'd.product_id')
                                           ->leftJoin('users as u', 'u.code', 'transactions.user_id')
                                           ->leftJoin('agents as m', 'm.code', 'transactions.user_id')
                                           ->leftJoin('product_variations as v', 'v.id', 'd.variation_id')
                                           ->leftJoin('product_second_variations as sv', 'sv.id', 'd.second_variation_id')
                                           ->leftJoin('transaction_packages as dp', 'dp.detail_id', 'd.id')
                                           ->where('transactions.status', 1)
                                           ->where('d.deduct_qty', '1')
                                           ->where(DB::raw('COALESCE(m.master_id, u.master_id)'), $code)
                                           ->where(DB::raw('IF(dp.product_id > 0, dp.product_id, d.product_id)'), $pid);
        if(!empty($vid)){
        $deduct_from_customer = $deduct_from_customer->where(DB::raw('IF(dp.variation_id > 0, dp.variation_id, d.variation_id)'), $vid);
        }
        if(!empty($svid)){
        $deduct_from_customer = $deduct_from_customer->where(DB::raw('IF(dp.second_variation_id > 0, dp.second_variation_id, d.second_variation_id)'), $svid);
        }
        $deduct_from_customer = $deduct_from_customer->first();


        $deduct_from_own = Transaction::select(DB::raw('SUM(IF(dp.quantity > 0, (dp.quantity * d.quantity), d.quantity)) as totalStock'))
                                           ->join('transaction_details as d', 'd.transaction_id', 'transactions.id')
                                           ->join('products as p', 'p.id', 'd.product_id')
                                           ->leftJoin('users as u', 'u.code', 'transactions.user_id')
                                           ->leftJoin('agents as m', 'm.code', 'transactions.user_id')
                                           ->leftJoin('product_variations as v', 'v.id', 'd.variation_id')
                                           ->leftJoin('product_second_variations as sv', 'sv.id', 'd.second_variation_id')
                                           ->leftJoin('transaction_packages as dp', 'dp.detail_id', 'd.id')
                                           ->where('transactions.status', 1)
                                           ->where('d.deduct_qty', '1')
                                           ->where('transactions.user_id', $code)
                                           ->where(DB::raw('IF(dp.product_id > 0, dp.product_id, d.product_id)'), $pid);
        if(!empty($vid)){
        $deduct_from_own = $deduct_from_own->where(DB::raw('IF(dp.variation_id > 0, dp.variation_id, d.variation_id)'), $vid);
        }
        if(!empty($svid)){
        $deduct_from_own = $deduct_from_own->where(DB::raw('IF(dp.second_variation_id > 0, dp.second_variation_id, d.second_variation_id)'), $svid);
        }
        $deduct_from_own = $deduct_from_own->first();

        $withdrawal_stock = WithdrawalStockDetail::select(DB::raw('SUM(withdrawal_stock_details.quantity) as totalStock'))
                                           ->join('withdrawal_stocks as ws', 'ws.id', 'withdrawal_stock_details.withdrawal_stock_id')
                                           ->where('withdrawal_stock_details.product_id', $pid)
                                           ->where('ws.user_id', $code)
                                           ->whereIn('ws.status', ['1', '99']);

        if(!empty($vid)){
        $withdrawal_stock = $withdrawal_stock->where('withdrawal_stock_details.variation_id', $vid);
        }
        if(!empty($svid)){
        $withdrawal_stock = $withdrawal_stock->where('withdrawal_stock_details.second_variation_id', $svid);
        }
        $withdrawal_stock = $withdrawal_stock->first();

        return $my_stocks->totalStock - $deduct_from_customer->totalStock - $withdrawal_stock->totalStock - $deduct_from_own->totalStock;
        // return $my_stocks->totalStock;
    }

    public static function purchase_from_customer_deduct_stock_commission($transaction_no)
    {
        try{
            \DB::beginTransaction();

            $transaction = Transaction::where('transaction_no', $transaction_no)
                                      ->whereNull('pv_purchase');
            $transaction = $transaction->first();
            if(!empty($transaction->id)){
                $buyer_detail = "";

                $isMember = User::where('code', $transaction->user_id)->first();
                if(!empty($isMember->id)){
                    $buyer_detail = $isMember;
                }

                $isAgent = Agent::where('code', $transaction->user_id)->first();
                if(!empty($isAgent->id)){
                    $buyer_detail = $isAgent;
                }

                if(!empty($buyer_detail->id)){
                    $check_transaction_stocks = TransactionDetail::select('transaction_details.product_id',
                                                                          'transaction_details.variation_id',
                                                                          'transaction_details.second_variation_id',
                                                                          'transaction_details.quantity',
                                                                          'transaction_details.unit_price',
                                                                          'transaction_details.id as tdid',
                                                                          'transaction_details.product_name as product_name',
                                                                          'transaction_details.sub_category as sub_category',
                                                                          'transaction_details.second_sub_category as second_sub_category')
                                                                 ->join('products', 'products.id', 'transaction_details.product_id')
                                                                 ->where('transaction_id', $transaction->id)
                                                                 ->whereNull('products.packages')
                                                                 ->groupBy('transaction_details.product_id')
                                                                 ->groupBy('transaction_details.variation_id')
                                                                 ->groupBy('transaction_details.second_variation_id')
                                                                 ->get();
                    foreach($check_transaction_stocks as $stock){
                        $get_own_store_stock_balance = GlobalController::get_own_store_stock_balance($buyer_detail->master_id, $stock->product_id, $stock->variation_id, $stock->second_variation_id);
                                                
                        if($get_own_store_stock_balance > 0){
                            $quantity = $stock->quantity;
                            $count_quantity = 0;
                            if($get_own_store_stock_balance >= $quantity){
                                $count_quantity = $quantity;
                            }else{
                                $count_quantity = $quantity - $get_own_store_stock_balance;
                            }

                            // throw new \Exception($get_own_store_stock_balance.' - '.$quantity.' - '.$count_quantity);

                            $get_pricing = GlobalController::get_product_pricing(md5($stock->product_id), $buyer_detail->code, $stock->variation_id, $stock->second_variation_id);

                            $product_name = $stock->product_name;
                            if(!empty($stock->sub_category)){
                                $product_name .= " | ".$stock->sub_category;
                            }

                            if(!empty($stock->second_sub_category)){
                                $product_name .= " | ".$stock->second_sub_category;
                            }

                            $quantity = $stock->quantity;

                            $package_quantity = !empty($stock->package_quantity) ? $stock->package_quantity : 1;

                            $quantity = $quantity * $package_quantity;

                            $rebate_upline = new AffiliateCommission();
                            $rebate_upline->type = 5;
                            $rebate_upline->user_id = $buyer_detail->master_id;
                            $rebate_upline->user_by = $buyer_detail->code;
                            $rebate_upline->transaction_no = $transaction->transaction_no;
                            $rebate_upline->product_name = $product_name;
                            $rebate_upline->product_qty = $quantity;
                            $rebate_upline->product_amount = $get_pricing['product_price'];
                            $rebate_upline->comm_pa_type = "Amount";
                            $rebate_upline->comm_pa = $get_pricing['product_price'];
                            $rebate_upline->comm_amount = ($get_pricing['product_price'] * $quantity);
                            $rebate_upline->comm_desc = "Purchase From Customer (".$buyer_detail->f_name." | ".$buyer_detail->code.")";
                            $rebate_upline->comm_desc_cn = "客户购买 (".$buyer_detail->f_name." | ".$buyer_detail->code.")";
                            // $rebate_upline->comm_desc = "Deduct Stock Commission";

                            $rebate_upline->status = 1;
                            $rebate_upline->save();

                            $update_detail = TransactionDetail::find($stock->tdid);
                            $update_detail->deduct_qty = 1;
                            $update_detail->save();                            
                        }
                    }
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

        return "ok";
    }

    public static function loop_start_dates()
    {
        $website_setting = WebsiteSetting::find(1);

        $system_start_date = !empty($website_setting->system_start_date) ? $website_setting->system_start_date : date('Y-m-d H:i:s');

        $total_year = [];
        for($year=date('Y'); $year<=date('Y', strtotime($system_start_date)); $year++){
            $total_year[] = $year;
        }

        return $total_year;
    }

    public static function loop_monthly()
    {
        $month = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
        
        return $month;
    }

    public static function get_cart_link_cart_details($cart_link_id, $code, $lvl, $store = 0, $cod = 0)
    {
        $cart_link = CartLink::find($cart_link_id);

        $carts = CartLinkProductDetail::with(['get_product_det.get_agent_price_product' => function ($query) use ($lvl) {
                                            $query->where('agent_lvl_id', $lvl);
                                        }
                                    ])
                                     ->with(['get_fv_det.get_agent_price_variation' => function ($query_two) use ($lvl) {
                                                    $query_two->where('agent_lvl_id', $lvl);
                                                }
                                            ])
                                     ->with(['get_sv_det.get_agent_price_second_variation' => function ($query_three) use ($lvl) {
                                                    $query_three->where('agent_lvl_id', $lvl);
                                                }
                                            ])
                                     ->with(['get_promo_items' => function ($query_four) use ($lvl) {
                                                    $query_four->where('agent_lvl_id', $lvl);
                                                }
                                            ])
                                     ->where('cart_link_product_details.cart_link_id', $cart_link->id)
                                     ->groupBy('cart_link_product_details.id')
                                     ->get();

        $sub_total = 0;
        $totalWeight = 0;

        foreach($carts as $cart){
            
            if(!empty($cart->add_on_id)){
                $product_price = GlobalController::get_add_on_sub_item_price($code, $cart->add_on_id, $cart->product_id, $cart->sub_category_id, $cart->second_sub_category_id);

                $sub_total += $product_price * $cart->qty;
            }else{
                $product_price = GlobalController::get_product_pricing(md5($cart->product_id), $code, $cart->sub_category_id, $cart->second_sub_category_id);

                $sub_total += $product_price['product_price'] * $cart->qty;
            }

            if($cart->get_product_det->variation_enable == '1'){
                if($cart->get_product_det->second_variation_enable == '1'){
                    if(!empty($cart->get_sv_det->variation_weight)){
                        $totalWeight += $cart->get_sv_det->variation_weight * $cart->qty;
                    }
                }else{
                    if(!empty($cart->get_fv_det->variation_weight)){
                        $totalWeight += $cart->get_fv_det->variation_weight * $cart->qty;
                    }
                }
            }else{
                if(!empty($cart->get_product_det->weight)){
                    $totalWeight += $cart->get_product_det->weight * $cart->qty;
                }
            }
        }

        if(!empty($cart_link->price)){
            $sub_total = $cart_link->price;
        }

        $all_shipping_fee = 0;

        $get_states = "";

        $get_countries = "";

        $default_shipping_address = UserShippingAddress::where('user_id', $code)
                                                       ->where('default', '1')
                                                       ->first();
        
        if(!empty($default_shipping_address->state)){
            $get_states = $default_shipping_address->state;
        }
        
        if(!empty($default_shipping_address->country)){
            $get_countries = $default_shipping_address->country;
        }

        if($get_countries == 160){
            if($get_states != '11' && $get_states != '12' && $get_states != '15'){
                $shipping_fees = SettingShippingFee::where('area', 'west')
                                                   ->where('weight', '<=', ceil($totalWeight))
                                                   ->orderBy('weight', 'desc')
                                                   ->first();
                if(!empty($shipping_fees->id)){
                    $all_shipping_fee = $shipping_fees->shipping_fee;
                }
            }else{
                $shipping_fees = SettingShippingFee::where('area', 'east')
                                                   ->where('weight', '<=', ceil($totalWeight))
                                                   ->orderBy('weight', 'desc')
                                                   ->first();
                if(!empty($shipping_fees->id)){
                    $all_shipping_fee = $shipping_fees->shipping_fee;
                }
            }
        }else{
            $shipping_fees = SettingShippingFee::where('country_id', $get_countries)
                                               ->where('weight', '<=', ceil($totalWeight))
                                               ->orderBy('weight', 'desc')
                                               ->first();
            if(!empty($shipping_fees->id)){
                $all_shipping_fee = $shipping_fees->shipping_fee;
            }
        }

        if($store == 1 ||
           $cod == 1){
            $all_shipping_fee = 0;
        }

        $web_setting = WebsiteSetting::find(1);

        if(!empty($web_setting->free_shipping_amount) && $sub_total >= $web_setting->free_shipping_amount){
            $all_shipping_fee = 0;
        }

        $applied_voucher = AppliedPromotion::where('user_id', $code)
                                           ->where('status', '1')
                                           ->orderBy('created_at', 'DESC')
                                           ->first();


        $totalDiscount = 0;
        $discount_code = NULL;
        $discount_type = NULL;
        $discount_amount = NULL;

        if(!empty($applied_voucher->get_voucher_detail->id)){
            
            $minSpend = $applied_voucher->get_voucher_detail->minSpend ?? 0;
            
            if($minSpend > 0 && $sub_total < $minSpend){
                
                $applied_voucher->delete();
              
                $totalDiscount = 0;
                $discount_code = NULL;
                $discount_type = NULL;
                $discount_amount = NULL;
            } else {
                
            $discount_code = $applied_voucher->get_voucher_detail->discount_code;
            $discount_type = $applied_voucher->get_voucher_detail->amount_type;
            $discount_amount = $applied_voucher->get_voucher_detail->amount;

            if($applied_voucher->get_voucher_detail->free_shipping == '1'){
                $totalDiscount = $all_shipping_fee;
            }else{
                if($applied_voucher->get_voucher_detail->amount_type == 'Percentage'){
                    $totalDiscount = (float) $sub_total * $applied_voucher->get_voucher_detail->amount / 100;
                }else{
                    $totalDiscount = $applied_voucher->get_voucher_detail->amount;
                    }
                }
            }
        }

        
       if (!empty($website_setting->free_shipping_threshold) && $sub_total >= $website_setting->free_shipping_threshold){
            $totalshipping_fees = 0; 
        } else {
            $totalshipping_fees = $all_shipping_fee;
        }

        $totalAmount = $sub_total + $totalshipping_fees - $totalDiscount;

        if ($totalAmount < 0) {
            $totalAmount = 0;
        }

        return array('sub_total'=>$sub_total, 
                     'total_weight'=>$totalWeight, 
                     'total_discount'=>$totalDiscount, 
                     'total_shipping_fee'=>$totalshipping_fees, 
                     'grand_total'=>$totalAmount,
                     'discount_code'=>$discount_code,
                     'discount_type'=>$discount_type,
                     'discount_amount'=>$discount_amount);
    }

    public static function check_authorize()
    {
        $website_setting = WebsiteSetting::find(1);

        $authorise_enable = !empty($website_setting->authorise_enable) ? $website_setting->authorise_enable : 0;

        $authorise_merchant = !empty($_COOKIE['vmerchant']) ? $_COOKIE['vmerchant'] : '';

        if(!empty(request('vm'))){
            $authorise_merchant = request('vm');

            $cookie_name = "vmerchant";
            $cookie_value = $authorise_merchant;

            // Calculate the expiration time (1 day from the current time)
            $expiration_time = time() + (86400 * 7); // 86400 seconds = 24 hours = 1 day

            // Set the cookie with the expiration time
            setcookie($cookie_name, $cookie_value, $expiration_time, "/");
        }

        // echo $authorise_enable;
        if($authorise_enable > 0){
            if(empty($authorise_merchant)){
                // Toastr::error('Authorise required.');
                return 1;
            }else{
                $merchant = Merchant::where(DB::raw('md5(id)'), $authorise_merchant)
                                    ->where('status', '1')
                                    ->where(function($query){
                                        $query->where('active_period', 0)
                                              ->orWhere(function($query2){
                                                $query2->where(DB::raw('DATE_ADD(created_at, INTERVAL active_period DAY)'), '>=', date('Y-m-d H:i:s'))
                                                       ->where('active_period', '>', 0);
                                              });
                                    })
                                    ->first();


                if(empty($merchant->id)){
                    // Toastr::error('Authorise required.');
                    return 1;  
                }                
            }
        }
    }

    public static function check_autorize_status($id)
    {
        $website_setting = WebsiteSetting::find(1);

        $authorise_enable = !empty($website_setting->authorise_enable) ? $website_setting->authorise_enable : 0;

        $merchant = Merchant::where(DB::raw('md5(id)'), $id)
                            ->where('status', '1')
                            ->where(function($query){
                                $query->where('active_period', 0)
                                      ->orWhere(function($query2){
                                        $query2->where(DB::raw('DATE_ADD(created_at, INTERVAL active_period DAY)'), '>=', date('Y-m-d H:i:s'))
                                               ->where('active_period', '>', 0);
                                      });
                            })
                            ->first();

        if($authorise_enable > 0 && !empty($merchant->id)){
            return array('status'=>1,
                         'result'=>$merchant);
        }else{
            return array('status'=>0,
                         'result'=>[]);
        }
    }

    public static function get_merchant_expired_date($code = null, $period = null)
    {
        $translation_data = GlobalController::get_translations();
        if(!empty($code)){
            $merchant = Merchant::where('code', $code)->first();
            if(!empty($merchant->id)){
                $get_created_date = $merchant->created_at;
                if($period == null){
                    $period = $merchant->active_period;
                }
            }else{
                $get_created_date = date('Y-m-d H:i:s');
            }
        }else{
            $get_created_date = date('Y-m-d H:i:s');
        }

        if($period > 0){
            $get_expired_date = date('Y-m-d', strtotime("+".$period." day".$get_created_date));
       }elseif($period == 0){
            $get_expired_date = isset($translation_data['backendlang']['backendlang']['Unlimited']) ? $translation_data['backendlang']['backendlang']['Unlimited'] : 'Unlimited' ;
        }else{
            $get_expired_date = "";
        }

        return $get_expired_date;
    }

    public static function website_setting()
    {


        $authorise_merchant = !empty($_COOKIE['vmerchant']) ? $_COOKIE['vmerchant'] : '';
        $get_authorise_status = GlobalController::check_autorize_status($authorise_merchant);
        if($get_authorise_status['status'] == 1){
            $merchant = Merchant::where(DB::raw('md5(id)'), $_COOKIE['vmerchant'])->first();

            if(!empty($merchant->id)){
                $website_setting = $merchant;
            }else{
                $website_setting = WebsiteSetting::find(1);
            }
        }else{
            $website_setting = WebsiteSetting::find(1);
        }

        return $website_setting;
    }

    public static function merchant_setting_function($code)
    {
        try{
            \DB::beginTransaction();

            $get_default_levels = AgentLevel::where('status', '1')->where('admin_default', '1')->get();
            foreach($get_default_levels as $get_default_level){
                $create_level = new AgentLevel();
                $create_level->merchant_id = $code;
                $create_level->agent_lvl = $get_default_level->agent_lvl;
                $create_level->target = $get_default_level->target;
                $create_level->level_colour = $get_default_level->level_colour;
                $create_level->save();
            }
            
            $get_default_categories = Category::where('status', '1')->where('admin_default', '1')->get();
            foreach($get_default_categories as $get_default_category){
                $create_category = new Category();
                $create_category->merchant_id = $code;
                $create_category->code = $get_default_category->code;
                $create_category->category_name = $get_default_category->category_name;
                $create_category->save();
            }
            
            \DB::commit();
        } catch (\Exception $e){
            \DB::rollback();

            // Toastr::error($e->getMessage().' - '.$e->getLine());
            return $e->getMessage();
        } catch (\Error $e){
            \DB::rollback();

            Toastr::error($e->getMessage().' - '.$e->getLine());
            return $e->getMessage();
        }

        return "ok";
    }

    public static function validate_package($id)
    {
        $package = Product::where('packages' ,'1')
                          ->where('id', $id)
                          ->first();

        if(!empty($package)){
            try {
                DB::beginTransaction();
        
                $balanceQty = GlobalController::balance_quantity($id);
                
                if ($package->quantity > $balanceQty) {
                    throw new \Exception("Insufficient stock balance for product. Please re-enter!");
                }
                
                $packageItems = PackageItem::select('package_items.*')
                                            ->join('products as p', 'p.id', '=', 'package_items.product_id')
                                            ->where('package_items.product_id', $id)
                                            ->where('p.status', '1')
                                            ->whereNull('package_items.voucher_id')
                                            ->get();
    
                $P_ItemsQty = PHP_INT_MAX; 
                foreach ($packageItems as $item) {
                    $itemBalanceQty = GlobalController::balance_quantity($item->products);
                    $packageItemsQty = $item->qty;
    
                    if($packageItemsQty <= $itemBalanceQty){
                        $maxpackageItems = (int)floor($itemBalanceQty/$packageItemsQty);
                        if( $maxpackageItems < $P_ItemsQty){
                            $P_ItemsQty = $maxpackageItems;
                        }
                    }else{
                        $P_ItemsQty = 0;
                    }
                    
                }
    
                $vouchers = PackageItem::select('package_items.*')
                                        ->join('promotions as p', 'p.id', '=', 'package_items.voucher_id')
                                        ->where('package_items.product_id', $id)
                                        ->where('p.status', '1')
                                        ->whereNull('package_items.products')
                                        ->get();
            
                foreach ($vouchers as $voucher) {
                     $transaction = AppliedPromotion::select(DB::raw('COUNT(id) AS totalRedeemed'))
                                                ->where('promotion_id', $id)
                                                ->whereIn('status', ['1', '2'])->first();
    
                    $TotalVoucherQty = (float)$voucher->quantity - (float)$transaction->totalRedeemed;
    
                    if ($voucher->voucherqty > $TotalVoucherQty) {
                        throw new \Exception("Insufficient stock balance for voucher: {$voucher->voucher_id}. Please re-enter!");
                    }
                }
        
                $minProductQty = $balanceQty;
                $minItemQty = $packageItems->pluck('qty')->min();
                $minVoucherQty = $vouchers->pluck('qty')->min();
    
                $quantities = array_filter([$minProductQty, $minVoucherQty, $P_ItemsQty], function($value) {
                    return isset($value) ; 
                });
                
                $minQuantity = min($quantities);
                DB::commit();
                
                return $minQuantity; 
        
            } catch (\Exception $e) {
                DB::rollback();
                return $e->getMessage();
            } catch (\Error $e) {
                \DB::rollback();
                return $e->getMessage();
            }
        }else{
            return "No package";
        }
    }
    
    public static function faqs_desctiption()
    {
        $langFlag = $_COOKIE['backend_global_language'] ?? ($_COOKIE['global_language'] ?? '0');

        if ($langFlag === '1') {
            $arr = [
                '1' => '订单与运输',
                '2' => '付款与我的账户',
                '3' => '一般咨询'
            ];
        } else {
            $arr = [
                '1' => 'Orders and Shipping',
                '2' => 'Payment and My Account',
                '3' => 'General Enquiries'
            ];
        }

        return $arr;
    }

    public static function get_voucher_balance($id, $code)
    {
        $adjusts = Promotion::select(DB::raw('COUNT(ap.id) as totalVoucher'))
                            ->join('applied_promotions as ap', 'ap.promotion_id', 'promotions.id')
                            ->whereIn('ap.status', ['99', '1'])
                            ->where('ap.user_id', $code)
                            ->where('ap.promotion_id', $id)
                            ->first();

        $deduct_voucher = AdjustVoucher::select(DB::raw('SUM(amount) as totalDeduct'))
                                       ->where('user_id', $code)
                                       ->where('voucher_id', $id)
                                       ->first();

        return $adjusts->totalVoucher - $deduct_voucher->totalDeduct;
    }

    public static function auto_withdrawal($code){
        try{
            \DB::beginTransaction();

           
            $agent = Agent::where('code',$code)->where('status',1)->first();

            // foreach($agents as $agent){
            $user_wallet = floatval(HomeController::GetCashWalletBalance($agent->code));
            $checkBank = BankAccount::where('user_id', $agent->code)->where('default_banks', '1')->where('status', '1')->first();

            if(!empty($checkBank)){
                if($user_wallet > 0){
                    $withdrawal = new WithdrawalTransaction();

                    $withdrawal->user_id = $agent->code;
                    $withdrawal->amount = $user_wallet;
                    $withdrawal->actual_amount = $user_wallet;
                    $withdrawal->company_charges = 0;
                    $withdrawal->withdrawal_no = HomeController::GenerateWithdrawalTransactionNo();

                    $defaultBank = BankAccount::where('default_banks', '1')
                                            ->where('user_id',  $agent->code)
                                            ->first();

                    if(!empty($defaultBank->id)){
                    $withdrawal->bank_name = $defaultBank->bank_name;
                    $withdrawal->bank_holder_name= $agent->f_name.' '.$agent->l_name;
                    $withdrawal->bank_account = $defaultBank->bank_account;
                    }
                    
                    $withdrawal->save();
                }
            }
            // }

            \DB::commit();
            return 'ok';
        } catch (\Exception $e){
            \DB::rollback();
            return $e->getMessage().' - '.$e->getLine();
        } catch (\Error $e){
            \DB::rollback();
            return $e->getMessage().' - '.$e->getLine();
        }
    }

    public static function test_auto_withdrawal(){
        $website_setting = WebsiteSetting::first();

        if($website_setting->auto_withdrawal_enable == 1){
            if (in_array(date('d'), [$website_setting->auto_withdrawal_day, $website_setting->auto_withdrawal_day_2])) {
                $agents_with_auto_withdrawal = Agent::where('withdrawal_type', '1')
                                                    ->where('status', '1')
                                                    ->get();
               
                foreach ($agents_with_auto_withdrawal as $agent_with_auto_withdrawal) {
                    $auto_withdrawal = GlobalController::auto_withdrawal($agent_with_auto_withdrawal->code);
                    if ($auto_withdrawal != 'ok') {
                        \Log::error($auto_withdrawal);
                    }
                }
            }
        }
    }

    public static function global_countries()
    {
        $setting = WebsiteSetting::find(1);
        
        $country_ids = explode(',', $setting->website_countries);

        $countries = TblCountry::whereIn('country_id', $country_ids)
                               ->orderBy('country_name', 'asc')
                               ->get();

        return $countries;
    }

    public static function send_order_notification($no)
    {
        try{
            \DB::beginTransaction();

            $website_setting = WebsiteSetting::find(1);

            $transaction = Transaction::where('transaction_no', $no)->first();
            if(empty($transaction->id)){
                throw new \Exception('Error Transaction');
            }

            if(!empty($transaction->country_code) && !empty($transaction->phone)){
                if($transaction->phone[0] === '0'){
                    $send_phone = $transaction->country_code.$transaction->phone;
                }else{
                    $send_phone = $transaction->country_code.'0'.$transaction->phone;
                }
            }

            $language = $_COOKIE['global_language'] ?? '';
            if($language === '1'){
                $message = "来自 ".$website_setting->website_name."\n"
                           . "我们已收到您的订单!\n"
                           . "您的订单编号是: ".$no."\n\n"
                           . "请登录您的账户查看订单状态.\n\n"
                           . "谢谢您.";

            }else{
                $message = "From ".$website_setting->website_name."\n"
                           . "We have receive your order!\n"
                           . "Your Order No: ".$no."\n\n"
                           . "Kindly Login to your account to check the order status.\n\n"
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

            \DB::commit();

            return "ok";
        }catch (\Exception $e){
            \DB::rollback();
            return $e->getMessage();
        }catch(\Error $e){
            \DB::rollback();
            return $e->getMessage();
        }
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

    // public static function auto_withdrawal($code)
    // {
    //     try {
    //         \DB::beginTransaction();

    //         $cash_wallet_balance = GlobalController::get_cash_wallet_balance($code);

    //         if ($cash_wallet_balance > 0) {
    //             $defaultBank = BankAccount::where('default_banks', '1')
    //                                     ->where('user_id', $code)
    //                                     ->first();

    //             $insert_withdrawal = new WithdrawalTransaction();
    //             $insert_withdrawal->withdrawal_no = GlobalController::GenerateWithdrawalTransactionNo();
    //             $insert_withdrawal->user_id = $code;
    //             if (!empty($defaultBank->id)) {
    //                 $insert_withdrawal->bank_name = $defaultBank->bank_name;
    //                 $insert_withdrawal->bank_holder_name = $defaultBank->bank_holder_name;
    //                 $insert_withdrawal->bank_account = $defaultBank->bank_account;
    //             }
    //             $insert_withdrawal->amount = $cash_wallet_balance;
    //             $insert_withdrawal->actual_amount = $cash_wallet_balance;
    //             $insert_withdrawal->company_charges = NULL;
    //             $insert_withdrawal->status = 99;
                
    //             $insert_withdrawal->save();
    //         }

    //         \DB::commit();

    //         return "ok";
    //     } catch (\Exception $e){
    //         \DB::rollback();
    //         return $e->getMessage().' - '.$e->getLine();
    //     } catch (\Error $e){
    //         \DB::rollback();
    //         return $e->getMessage().' - '.$e->getLine();
    //     }
    // }

    public static function getFlashSalePurchaseLimit($id, $vid, $sid, $user_id=null)
    {   
        $active_flash_sale = GlobalController::get_current_flash_sales();
        $get_limit = GlobalController::get_current_flash_sale_product_detail($id, $vid, $sid);
        $purchaseLimit = isset($get_limit->qty) ? $get_limit->qty : 0;
        //  dd($purchaseLimit);
        if($purchaseLimit > 0){
            // $carts = Cart::where('status', '1')
            //         ->where('user_id', '!=', $user_id)
            //         ->where('product_id', $id)
            //         ->whereBetween('created_at', [$active_flash_sale->start, $active_flash_sale->end])
            //         ->sum('qty');
  
            $price_ids = FlashSaleProductPrice::join('flash_sale_product_details as fd', 'fd.id', 'flash_sale_product_prices.flash_sale_product_detail_id')
                                              ->where('flash_sale_product_prices.flash_sale_product_detail_id', $get_limit->id)
                                              ->pluck('flash_sale_product_prices.id');
            $transactions = Transaction::join('transaction_details as d', 'd.transaction_id', 'transactions.id')
                                       ->whereIn('transactions.status', ['1', '98'])
                                       ->whereBetween('transactions.created_at', [$active_flash_sale->start, $active_flash_sale->end])
                                       ->whereIn('d.flash_sale_product_price_id', $price_ids)
                                       ->sum('d.quantity');

            // if($carts === null){
            //     $carts = 0;
            // }

            if($transactions === null){
                $transactions = 0;
            }

            // purchase limit balance quantity
            // $balanceQty = $purchaseLimit - $carts - $transactions;
            $balanceQty = $purchaseLimit - $transactions;
          
            if($balanceQty >= 0){
                return $balanceQty;
                
            }elseif($balanceQty < 0){
                return 'not enough stock';
            }
        }

        return 'Limit unactive';
    }

    public static function checkFreeShipping($sub_total, $get_countries, $get_states = null)
    {
        $website_setting = WebsiteSetting::find(1);
        
        // Check if order qualifies for free shipping
        if ($website_setting->free_shipping_threshold && $sub_total >= $website_setting->free_shipping_threshold) {
            return 0; // Free shipping
        }
        
        // If not free shipping, calculate shipping fee
        if($get_countries == 160){
            if($get_states != '11' && $get_states != '12' && $get_states != '15'){
                if ($website_setting->type_set_shipping_fee == '1') { // Weight Based
                    $shipping_fees = SettingShippingFee::where('area', 'west')
                                    ->where('weight', '<=', ceil($totalWeight))
                                    ->orderBy('weight', 'desc')
                                    ->first();
                } elseif ($website_setting->type_set_shipping_fee == '2') { // Price Based
                    $shipping_fees = SettingShippingFee::where('area', 'west')
                                    ->where('weight', '<=', ceil($sub_total))
                                    ->orderBy('weight', 'desc')
                                    ->first();                             
                }

                if(!empty($shipping_fees->id)){
                    return $shipping_fees->shipping_fee;
                }
            }else{
                if ($website_setting->type_set_shipping_fee == '1') { // Weight Based
                    $shipping_fees = SettingShippingFee::where('area', 'east')
                                    ->where('weight', '<=', ceil($totalWeight))
                                    ->orderBy('weight', 'desc')
                                    ->first();
                } elseif ($website_setting->type_set_shipping_fee == '2') { // Price Based
                    $shipping_fees = SettingShippingFee::where('area', 'east')
                                    ->where('weight', '<=', ceil($sub_total))
                                    ->orderBy('weight', 'desc')
                                    ->first();                             
                }

                if(!empty($shipping_fees->id)){
                    return $shipping_fees->shipping_fee;
                }
            }
        }else{
            if ($website_setting->type_set_shipping_fee == '1') { // Weight Based
                $shipping_fees = SettingShippingFee::where('country_id', $get_countries)
                                    ->where('weight', '<=', ceil($totalWeight))
                                    ->orderBy('weight', 'desc')
                                    ->first();
            } elseif ($website_setting->type_set_shipping_fee == '2') { // Price Based
                $shipping_fees = SettingShippingFee::where('country_id', $get_countries)
                                    ->where('weight', '<=', ceil($sub_total))
                                    ->orderBy('weight', 'desc')
                                    ->first();                             
            }

            if(!empty($shipping_fees->id)){
                return $shipping_fees->shipping_fee;
            }
        }
        
        return 0; // Default to free shipping if no rules match
    }

    public static function get_translations()
    {
        $localCheck = $_SERVER["SERVER_NAME"];
        $explo_check = explode('.', $localCheck);
        if($explo_check[0] == 'demoaccount'){
            include('../resources/views/language/language.blade.php');
        }elseif($localCheck != '127.0.0.1'){
            include('resources/views/language/language.blade.php');
        }else{
            include('../resources/views/language/language.blade.php');
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

        return array('lang'=> compact('lang'),
                    'Blang'=> compact('Blang'),
                    'backendlang'=> compact('backendlang'));
    }
}
?>