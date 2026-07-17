<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Facades\Input;
use App\ProductImage;
use App\Cart;
use App\UserShippingAddress;
use App\Favourite;
use App\Product;
use App\FlashSaleProductDetail;
use App\Promotion;
use App\Transaction;
use App\TransactionDetail;
use App\BankAccount;
use App\AffiliateCommission;
use App\WithdrawalTransaction;
use App\Bank;
use App\AppliedPromotion;
use App\Merchant;
use App\Admin;
use App\User;
use App\VerifyCode;
use App\ProductVariation;
use App\SettingTopup;
use App\CartLink;
use App\CartLinkProductDetail;
use App\ProductSecondVariation;
use App\SettingShippingFee; 
use App\CodAddress;
use App\PackageItem;
use App\AgentPrice;
use App\City;
use App\State;
use App\WebsiteSetting;
use App\SalesPopup;
use App\MemberPv;
use App\PromoAgentItemDetail;
use App\TblCountry;
use App\Affiliate;
use App\SettingTeamDividend;
use App\WithdrawalStock;
use App\WithdrawalStockDetail;
use App\Agent;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\GlobalController;

use Validator, Redirect, Toastr, DB, File, Auth, Session, DateTime, Mail;

class AjaxController extends Controller
{
    public function AddToCart(Request $request)
    {
        try{

            \DB::beginTransaction();

            $minQty = GlobalController::validate_package($request->product_id);            
            $getCurrentLogin = GlobalController::getCurrentLogin();
            $buyerCode = $getCurrentLogin['code'];
            $buyerLvl = $getCurrentLogin['lvl'];

            $product = Product::find($request->product_id);

            if($product->variation_enable == '1'){
                if($product->second_variation_enable == '1'){
                    $BalanceQty = GlobalController::second_variation_balance_quantity($request->second_sub_category_id);
                }else{
                    $BalanceQty = GlobalController::variation_balance_quantity($request->sub_category_id);
                }
            }else{
                $BalanceQty = GlobalController::balance_quantity($request->product_id);
            }

        	if($request->quantity <= 0){
        		throw new \Exception("Please add at least 1 quantity");
            }elseif(isset($minQty) && $minQty != "No package"){
                if($request->quantity > $minQty){
                    throw new \Exception("Insufficient stock balance for product!");
                }
            }elseif($request->quantity > $BalanceQty){
                throw new \Exception("Insufficient stock balance for product!");
            }

            
            $is_birthday = null;
            if(!Auth::guard('admin')->check() && (!empty(Auth::guard('agent')->check()) || !empty(Auth::guard('web')->check()))){
                $user_birthday_promotion = GlobalController::checkUserBirthMonthToday($buyerCode);
                
                $buyerDOB = $getCurrentLogin->dob;
                $dob = DateTime::createFromFormat('d/m/Y', $buyerDOB);

                $birthdayMonth = $dob->format('m');
                $currentYear = date('Y');

                $hasBirthdayTransaction = TransactionDetail::leftJoin('transactions as t', 't.id', 'transaction_details.transaction_id')
                    ->where('t.user_id', $buyerCode)
                    ->where('transaction_details.is_birthday', 1)
                    ->whereIn('t.status',[1,98])
                    ->whereMonth('t.created_at', $birthdayMonth)
                    ->whereYear('t.created_at', $currentYear)
                    ->exists();
            
                if(!$hasBirthdayTransaction){
                    if ( $product->birthday_promotion == 1 && $user_birthday_promotion) {
                        if($request->quantity > 1){
                            throw new \Exception("You can only add one birthday promotion product to your cart during your birthday month.");
                        }
                        
                        $cartItems = Cart::where('user_id', $buyerCode)
                            ->where('status', 1)
                            ->with('get_product_det')
                            ->get();
                
                        foreach ($cartItems as $cartItem) {
                            if ($cartItem->get_product_det && $cartItem->get_product_det->birthday_promotion) {
                                throw new \Exception("You can only add one birthday promotion product to your cart during your birthday month.");
                            }
                        }

                        $is_birthday = 1;
                    }
                }
            }

            if($product->variation_enable == '1'){
                if($product->second_variation_enable == '1'){
                    $BalanceQty = GlobalController::second_variation_balance_quantity($request->second_sub_category_id);
                }else{
                    $BalanceQty = GlobalController::variation_balance_quantity($request->sub_category_id);
                }
            }else{
                $BalanceQty = GlobalController::balance_quantity($request->product_id);
            }

            $current_flash_sale_product = GlobalController::get_current_flash_sale_product_detail($request->product_id, $request->sub_category_id, $request->second_sub_category_id);

        	$check = Cart::where('user_id', $buyerCode)
        			     ->where('product_id', $request->product_id)
        			     ->where('status', '1');
            // throw new \Exception($request->product_id);

            if(isset($request->sub_category_id) && !empty($request->sub_category_id) && isset($request->second_sub_category_id) && !empty($request->second_sub_category_id)){
                $check = $check->where('sub_category_id', $request->sub_category_id)
                               ->where('second_sub_category_id', $request->second_sub_category_id);
            }
            if(!empty($request->promo_id)){
                $check = $check->where('promo', $request->promo_id);
            }else{
                $check = $check->whereNull('promo');
            }
            if(!empty($request->mall)){
                $check = $check->whereNotNull('mall');   
            }
        	$check = $check->first();

            $totalAddQty = $request->quantity;


            if(!empty($current_flash_sale_product)){
                $flash_check = Cart::where('user_id', $buyerCode)
                                   ->where('product_id', $request->product_id)
                                   ->where('status', '1');

                if(isset($request->sub_category_id) && !empty($request->sub_category_id) && isset($request->second_sub_category_id) && !empty($request->second_sub_category_id)){
                    $flash_check = $flash_check->where('sub_category_id', $request->sub_category_id)
                                               ->where('second_sub_category_id', $request->second_sub_category_id);
                }
                if(!empty($request->promo_id)){
                    $flash_check = $flash_check->where('promo', $request->promo_id);
                }else{
                    $flash_check = $flash_check->whereNull('promo');
                }
                // $flash_check = $flash_check->where('flash_sale_product_id', $current_flash_sale_product->id)
                //                            ->first();
                $flash_check = $flash_check->first();



                $check_non_flash_cart = Cart::where('user_id', $buyerCode)
                                            ->where('product_id', $request->product_id)
                                            ->where('status', '1');

                if(isset($request->sub_category_id) && !empty($request->sub_category_id) && isset($request->second_sub_category_id) && !empty($request->second_sub_category_id)){
                    $check_non_flash_cart = $check_non_flash_cart->where('sub_category_id', $request->sub_category_id)
                                                                 ->where('second_sub_category_id', $request->second_sub_category_id);
                }
                if(!empty($request->promo_id)){
                    $check_non_flash_cart = $check_non_flash_cart->where('promo', $request->promo_id);
                }else{
                    $check_non_flash_cart = $check_non_flash_cart->whereNull('promo');
                }

                // $check_non_flash_cart = $check_non_flash_cart->whereNull('flash_sale_product_id')
                //                                              ->first();

                $check_non_flash_cart = $check_non_flash_cart->first();

                if(isset($flash_check) && !empty($flash_check->id)){
                    $flash_update = Cart::find($flash_check->id);

                    if(!empty($current_flash_sale_product->qty)){
                        $total_after_add = $flash_update->qty + $totalAddQty;

                        if($flash_update->qty >= $current_flash_sale_product->qty){
                            $totalQty = $flash_update->qty;

                            if(!empty($check_non_flash_cart->qty)){
                                $totalAddQty += $check_non_flash_cart->qty;
                            }
                        }else{

                            if($total_after_add > $current_flash_sale_product->qty){

                                $totalQty = $current_flash_sale_product->qty;
                                $totalAddQty = $check_non_flash_cart->qty + ($totalAddQty - ($current_flash_sale_product->qty - $flash_update->qty)); 

                            }else{
                                $totalQty = $flash_update->qty + $totalAddQty;
                                $totalAddQty = 0;
                            }
                        }
                    }else{
                        $totalQty = $flash_update->qty + $totalAddQty;
                    }

                    if($totalQty <= $BalanceQty){
                        $flash_update->qty = $totalQty;
                        $flash_update->save();
                    }else{
                        throw new \Exception("Please add at least 1 quantity");
                    }

                    if($totalAddQty > 0){
                        if(!empty($check_non_flash_cart->id)){
                            $update_cart = Cart::find($check_non_flash_cart->id);
                            $update_cart = $update_cart->update(['qty'=>$totalAddQty]);
                        }else{
                            $input = new Cart();
                            $input->product_id = $request->product_id;
                            $input->is_birthday = $is_birthday;
                            $input->sub_category_id = $request->sub_category_id;
                            $input->second_sub_category_id = $request->second_sub_category_id;
                            $input->user_id = $buyerCode;
                            $input->qty = $totalAddQty;
                            if(!empty($request->promo_id)){
                                $input->promo = $request->promo_id;
                            }
                            if(!empty($request->promo_price_id)){
                                $input->promo_price_id = $request->promo_price_id;
                            }
                            // $input->flash_sale_product_id = !empty($current_flash_sale_product) ? $current_flash_sale_product->id : NULL;
                            $input->save();
                        }
                    }
                }else{
                    if(!empty($current_flash_sale_product->qty)){
                        if($totalAddQty > $current_flash_sale_product->qty){
                            $totalQty = $current_flash_sale_product->qty;
                            $totalAddQty -= $current_flash_sale_product->qty;
                        }else{
                            if($totalAddQty <= $current_flash_sale_product->qty){
                                $totalQty = $totalAddQty;
                                $totalAddQty = 0;
                            }
                        }
                    }else{
                        $totalQty = $totalAddQty;
                        $totalAddQty = 0;
                    }

                    $input = new Cart();
                    $input->is_birthday = $is_birthday;
                    $input->product_id = $request->product_id;
                    $input->sub_category_id = $request->sub_category_id;
                    $input->second_sub_category_id = $request->second_sub_category_id;
                    $input->user_id = $buyerCode;
                    $input->qty = $totalQty;
                    if(!empty($request->promo_id)){
                        $input->promo = $request->promo_id;
                    }
                    if(!empty($request->promo_price_id)){
                        $input->promo_price_id = $request->promo_price_id;
                    }
                    // $input->flash_sale_product_id = !empty($current_flash_sale_product) ? $current_flash_sale_product->id : NULL;
                    $input->save();
              }          
            }else{
                if(isset($check) && !empty($check->id)){

                    $update = Cart::find($check->id);
                    $totalQty = $update->qty + $totalAddQty;
                    // throw new \Exception($totalQty);
                    if($totalQty <= $BalanceQty){
                        $update->qty = $totalQty;
                        $update->save();
                    }else{
                        throw new \Exception("Insufficient stock balance");
                    }
                }else{
                    if($totalAddQty > 0){
                        $input = new Cart();
                        $input->is_birthday = $is_birthday;
                        $input->product_id = $request->product_id;
                        $input->sub_category_id = $request->sub_category_id;
                        $input->second_sub_category_id = $request->second_sub_category_id;
                        $input->user_id = $buyerCode;
                        $input->qty = $totalAddQty;
                        if(!empty($request->mall)){
                            $input->mall = $request->mall;
                        }
                        if(!empty($request->promo_id)){
                            $input->promo = $request->promo_id;
                        }
                        if(!empty($request->promo_price_id)){
                            $input->promo_price_id = $request->promo_price_id;
                        }
                        $input->save();
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

    public function SelectCart(Request $request)
    {
        $amount = 0;
        $count = 0;
        $totalWeight = 0;
        if(!empty($request->cart_id)){

            $explode = explode(",", $request->cart_id);
            foreach(array_unique($explode) as $key => $value){
                $count++;
                $carts = Cart::select('carts.qty', 'p.weight', 
                                      DB::raw('COALESCE(special_price, price) AS actual_price'),
                                      DB::raw('COALESCE(agent_special_price, agent_price) AS agent_actual_price'),
                                      'p.*')
                             ->join('products AS p', 'p.id', 'carts.product_id')
                             ->where(DB::raw('md5(carts.id)'), $value)
                             ->first();
                // if(Auth::guard('agent')->check() || Auth::guard('admin')->check()){
                //     $amount += $carts->agent_actual_price * $carts->qty;                    
                // }else{
                //     $amount += $carts->actual_price * $carts->qty;
                // }
                if(!empty($carts->special_price)){
                  $amount += $carts->special_price * $carts->qty;
                }else{
                  $amount += $carts->price * $carts->qty;
                }
                $totalWeight += $carts->weight * $carts->qty;
            }
            
        }


        return array(number_format($amount, 2), $count, $totalWeight);
    }

    public function updateQuantity(Request $request)
    {
        $getCurrentLogin = GlobalController::getCurrentLogin();
        $buyerCode = $getCurrentLogin['code'];
        $buyerLvl = $getCurrentLogin['lvl'];
        if(!empty($request->member)){
            $buyerCode = $request->member;
        }

        $user_birthday_promotion = GlobalController::checkUserBirthMonthToday($buyerCode);
            
        if ($user_birthday_promotion == true) {
            $cartBirthday = Cart::where(DB::raw('md5(id)'), $request->cart_id)
                ->with('get_product_det')
                ->first();

            if ($cartBirthday->get_product_det && $cartBirthday->get_product_det->birthday_promotion == 1) {
                return "Only one birthday product is allowed";
            }
            
        }
        
        $update = Cart::where(DB::raw('md5(id)'), $request->cart_id);
        
        $findCart = Cart::where(DB::raw('md5(id)'), $request->cart_id)
                        ->first();
       
        if(!empty($findCart->second_sub_category_id) && $findCart->second_sub_category_id != '0'){
            $stock = GlobalController::second_variation_balance_quantity($findCart->second_sub_category_id);

            if($request->quantity > $stock){
                return "not enough stock";
            }
        }elseif(!empty($findCart->sub_category_id) && $findCart->sub_category_id != '0'){
            $stock = GlobalController::variation_balance_quantity($findCart->sub_category_id);

            if($request->quantity > $stock){
                return "not enough stock";
            }
        }else{
            $stock = GlobalController::balance_quantity($findCart->product_id);

            if($request->quantity > $stock){
                return "not enough stock";
            }
        }

        $active_flash_sale = GlobalController::get_current_flash_sales();
      
        if(!empty($active_flash_sale)){
            $current_active_flash_sale_product = FlashSaleProductDetail::where('flash_sale_id', $active_flash_sale->id)
                                                                        ->where('product_id', $findCart->product_id);
            if(!empty($findCart->sub_category_id)){
                $current_active_flash_sale_product = $current_active_flash_sale_product->where('variation_id', $findCart->sub_category_id);
            }else{
                $current_active_flash_sale_product = $current_active_flash_sale_product->whereNull('variation_id');
            }

            if(!empty($findCart->second_sub_category_id)){
                $current_active_flash_sale_product = $current_active_flash_sale_product->where('second_variation_id', $findCart->second_sub_category_id);
            }else{
                $current_active_flash_sale_product = $current_active_flash_sale_product->whereNull('second_variation_id');
            }

            $current_active_flash_sale_product = $current_active_flash_sale_product->where('status', '1')
                                                                                    ->first();
         
            if(!empty($current_active_flash_sale_product->id)){
                $sub_category_id = !empty($findCart->sub_category_id) ? $findCart->sub_category_id : null;
                $second_sub_category_id = !empty($findCart->second_sub_category_id) ? $findCart->second_sub_category_id : null;
                $balanceLimit = GlobalController::getFlashSalePurchaseLimit($findCart->product_id, $sub_category_id, $second_sub_category_id, $buyerCode);
              
                if($balanceLimit != 'Limit unactive'){
                    if($request->quantity > $balanceLimit){
                        return "exceed flash sale purchase limit";
                    }
                }
            }
        }

        $update = $update->update(['qty'=>$request->quantity]);

        $findCart = $findCart->update(['qty'=>$request->quantity]);

        $cart = Cart::with(['get_product_det.get_agent_price_product' => function ($query) use ($buyerLvl) {
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
                     ->where(DB::raw('md5(carts.id)'), $request->cart_id)
                     ->groupBy('carts.id')
                     ->first();

        $totalAmount = 0;

        $product_price = GlobalController::get_product_pricing(md5($cart->product_id), $buyerCode, $cart->sub_category_id, $cart->second_sub_category_id,"", "", '1');

        $totalAmount += $product_price['product_price'] * $cart->qty;

        return $totalAmount;
    }

    public function checkAvailablePackage(Request $request)
    {
        $currentCart = Cart::select('carts.*', 'p.packages')
                     ->join('products AS p', 'p.id', 'carts.product_id')
                     ->where(DB::raw('md5(carts.id)'), $request->cart_id)
                     ->first();

        $availablePack = PackageItem::select('package_items.*')
                                    ->join('products as p', 'p.id', 'package_items.product_id')
                                    ->where('products', $currentCart->product_id)
                                    ->where('qty', $request->quantity)
                                    ->where('p.status', '1')
                                    ->get();


        $result = "";
        if(!$availablePack->isEmpty() && empty($currentCart->packages)){
          foreach($availablePack as $pack){
            $product = Product::find($pack->product_id);
            $prodImage = ProductImage::where('product_id', $product->id)
                                     ->where('status', '1')
                                     ->first();
            
            $image = !empty($prodImage) ? $prodImage->image : asset('images/no-image-available-icon-6.jpg');
            $result .= '<div class="product-name">
                          <div class="row">
                            <div class="col-4">
                              <img src="'.asset($image).'" style="width: 100%;">
                            </div>
                            <div class="col-6">
                              <a href="'.route('details', md5($product->id)).'">
                                '.$product->product_name.'
                              </a>
                            </div>
                          </div>
                        </div>';

          }
          return $result;
        }
        return 1;
    }

    public function deleteCart(Request $request)
    {
        $delete = Cart::where(DB::raw('md5(id)'), $request->cart_id);
        $check = Cart::where(DB::raw('md5(id)'), $request->cart_id)
                     ->first();
        if(!empty($check->add_on_id)){
            $delete2 = Cart::where('add_on_id',$check->add_on_id);
            $delete2 = $delete2->delete();
        }
        $delete = $delete->delete();

        $getCurrentLogin = GlobalController::getCurrentLogin();
        $BuyerCode = $getCurrentLogin['code'];

        $apply_voucher = AppliedPromotion::where('status',1)->where('user_id', $BuyerCode)->first();

        if(!empty($apply_voucher->promotion_id)){
            $promotion = Promotion::find($apply_voucher->promotion_id);

            if(!empty($promotion->products)){
               $checkUserCart = Cart::where('user_id', $BuyerCode)
                                ->whereNull('mall')
                                ->get();

                if(!$checkUserCart->isEmpty()){
                    $cartProductIds = $checkUserCart->pluck('product_id')->toArray();
                    $promotionProductIds = explode(',', $promotion->products);

                    if (empty(array_intersect($promotionProductIds, $cartProductIds))) { 
                        $apply_voucher = $apply_voucher->update(['status' => '99']);
                    }
                }else{
                    $apply_voucher->update(['status' => '99']);
                }
            }
        }
    }

    public function CountCart(Request $request)
    {
        $getCurrentLogin = GlobalController::getCurrentLogin();
        $BuyerCode = $getCurrentLogin['code'];
        $BuyerLvl = $getCurrentLogin['lvl'];

        $cart = Cart::select(DB::raw('SUM(qty) AS totalCart'))
                    ->whereNull('mall')
                    ->where('user_id', $BuyerCode)->first();

        return $cart->totalCart;
    }

    public function CountCartMall(Request $request)
    {
        $getCurrentLogin = GlobalController::getCurrentLogin();
        $BuyerCode = $getCurrentLogin['code'];
        $BuyerLvl = $getCurrentLogin['lvl'];

        $cart = Cart::select(DB::raw('SUM(qty) AS totalCart'))
                    ->where('user_id', $BuyerCode)
                    ->where('mall', '1')
                    ->first();

        $cartP = Cart::select(DB::raw("IF(special_price != '0', special_price, price) AS Price"),
                              DB::raw("IF(corporate_special_price != '0', corporate_special_price, corporate_price) AS CPrice"),
                                      DB::raw("IF(v.variation_special_price != '0', v.variation_special_price, v.variation_price) AS VPrice"),
                                      DB::raw("IF(v.variation_corporate_special_price != '0', v.variation_corporate_special_price, v.variation_corporate_price) AS VCPrice"),
                                      DB::raw("IF(sv.variation_special_price != '0', sv.variation_special_price, sv.variation_price) AS SVPrice"),
                                      DB::raw("IF(sv.variation_corporate_special_price != '0', sv.variation_corporate_special_price, sv.variation_corporate_price) AS SVCPrice"),
                                      'qty', 'variation_enable', 'p.second_variation_enable',
                                      'carts.second_sub_category_id',
                                      'carts.sub_category_id',
                                      'carts.product_id')
                            ->join('products AS p', 'p.id', 'carts.product_id')
                            ->leftJoin('product_variations AS v', 'v.id', 'carts.sub_category_id')
                            ->leftJoin('product_second_variations AS sv', 'sv.id', 'carts.second_sub_category_id')
                            ->where('user_id', $BuyerCode)
                            ->where('carts.mall', '1')
                            ->get();
        $totalPrice = 0;
        
        foreach($cartP as $cartP_item){
            if($cartP_item->variation_enable == 1){
                if($cartP_item->second_variation_enable == 1){
                    $totalPrice += $cartP_item->SVPrice * $cartP_item->qty;
                }else{
                    $totalPrice += $cartP_item->VPrice * $cartP_item->qty;
                }
            }else{
                $totalPrice += $cartP_item->Price * $cartP_item->qty;
            }
        }
                            
        

        return array($cart->totalCart, $totalPrice);
    }

    public function updateCart(Request $request)
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

        $totalPrice = 0;
        $result = "";
        if(!empty($carts)){
            foreach ($carts as $cart) {
                if(isset($cart->image) && !empty($cart->image)){
                    $image = File::exists(public_path($cart->image)) ? $cart->image : asset('images/no-image-available-icon-6.jpg');
                }else{
                    $image = asset('images/no-image-available-icon-6.jpg');
                }

                $result .= '<div class="minicart-prd row">
                                <div class="minicart-prd-image image-hover-scale-circle col">
                                    <a href="'.route('details', md5($cart->id)).'"><img class="lazyload fade-up" src="'.asset($image).'" data-src="'.asset($image).'" alt=""></a>
                                </div>
                                <div class="minicart-prd-info col">
                                    <h2 class="minicart-prd-name"><a href="'.route('details', md5($cart->id)).'">';

                if(isset($_COOKIE['global_language']) && !empty($_COOKIE['global_language'])){
                    if($_COOKIE['global_language'] == '1'){
                        $result .= $cart->product_name;
                    }else{
                        $result .= $cart->product_name_en;
                    }
                }else{
                    $result .= $cart->product_name;
                }

                $result .=  '</a></h2>
                            <div class="minicart-prd-qty"><span class="minicart-prd-qty-label">';

                if(isset($_COOKIE['global_language']) && !empty($_COOKIE['global_language'])){
                    if($_COOKIE['global_language'] == '1'){
                        $result .= "数量:";
                    }else{
                        $result .= "Quantity:";
                    }
                }else{
                    $result .= "数量:";
                }

                $result .= '</span>
                              <span class="minicart-prd-qty-value">'.$cart->qty.'</span>
                            </div>
                            <div class="minicart-prd-price prd-price">
                                <div class="price-new">';

                if(Auth::guard('agent')->check() || Auth::guard('admin')->check()){
                      if($cart->variation_enable == '1'){
                            if($cart->second_variation_enable == '1'){
                                if(!empty($cart->second_variation_agent_special_price)){
                                    $result .= 'RM'.number_format($cart->second_variation_agent_special_price * $cart->qty, 2);
                                }else{
                                    $result .= 'RM'.number_format($cart->second_variation_agent_price * $cart->qty, 2);
                                }
                            }else{
                                if(!empty($cart->variation_agent_special_price)){
                                    $result .= 'RM'.number_format($cart->variation_agent_special_price * $cart->qty, 2);
                                }else{
                                    $result .= 'RM'.number_format($cart->variation_agent_price * $cart->qty, 2);
                                }
                            }
                      }else{
                          if(!empty($cart->agent_special_price)){
                              $result .= 'RM'.number_format($cart->agent_special_price * $cart->qty, 2);
                          }else{
                              $result .= 'RM'.number_format($cart->agent_price * $cart->qty, 2);
                          }
                      }
                }else{
                    if($cart->variation_enable == '1'){
                        if($cart->second_variation_enable == '1'){
                            if(!empty($cart->second_variation_special_price)){
                                $result .= 'RM'.number_format($cart->second_variation_special_price * $cart->qty, 2);
                            }else{
                                $result .= 'RM'.number_format($cart->second_variation_price * $cart->qty, 2);
                            }
                        }else{
                            if(!empty($cart->variation_special_price)){
                                $result .= 'RM'.number_format($cart->variation_special_price * $cart->qty, 2);
                            }else{
                                $result .= 'RM'.number_format($cart->variation_price * $cart->qty, 2);
                            }
                        }
                    }else{
                        if(!empty($cart->special_price)){
                            $result .= 'RM'.number_format($cart->special_price * $cart->qty, 2);
                        }else{
                            $result .= 'RM'.number_format($cart->price * $cart->qty, 2);
                        }
                    }
                }

                $result .= '</div>
                            </div>
                            </div>
                            </div>';

                if(Auth::guard('agent')->check() || Auth::guard('admin')->check()){
                    if($cart->variation_enable == '1'){
                        if($cart->second_variation_enable == '1'){
                            if(!empty($cart->second_variation_agent_special_price)){
                                $totalPrice += $cart->second_variation_agent_special_price * $cart->qty;
                            }else{
                                $totalPrice += $cart->second_variation_agent_price * $cart->qty;
                            }
                        }else{
                            if(!empty($cart->variation_agent_special_price)){
                                $totalPrice += $cart->variation_agent_special_price * $cart->qty;
                            }else{
                                $totalPrice += $cart->variation_agent_price * $cart->qty;
                            }
                        }
                    }else{
                        if(!empty($cart->agent_special_price)){
                            $totalPrice += $cart->agent_special_price * $cart->qty;
                        }else{
                            $totalPrice += $cart->agent_price * $cart->qty;
                        }
                    }
                }else{
                    if($cart->variation_enable == '1'){
                        if($cart->second_variation_enable == '1'){
                            if(!empty($cart->second_variation_special_price)){
                                $totalPrice += $cart->second_variation_special_price * $cart->qty;
                            }else{
                                $totalPrice += $cart->second_variation_price * $cart->qty;
                            }
                        }else{
                            if(!empty($cart->variation_special_price)){
                                $totalPrice += $cart->variation_special_price * $cart->qty;
                            }else{
                                $totalPrice += $cart->variation_price * $cart->qty;
                            }
                        }
                    }else{
                        if(!empty($cart->special_price)){
                            $totalPrice += $cart->special_price * $cart->qty;
                        }else{
                            $totalPrice += $cart->price * $cart->qty;
                        }
                    }
                }

                

            }
        }

        return array($result, $totalPrice);
    }

    public function changeDefaultAddress(Request $request)
    {
        $clearDefault = UserShippingAddress::where('user_id', Auth::user()->code)->update(['default' => NULL]);

        $setDefault = UserShippingAddress::where(DB::raw('md5(id)'), $request->address_id);
        $setDefault = $setDefault->update(['default' => '1']);

    }

    public function deleteAddress(Request $request)
    {
        $delete = UserShippingAddress::where(DB::raw('md5(id)'), $request->address_id);
        $delete = $delete->delete();
    }

    public function add_wish(Request $request)
    {
        $favourite = Favourite::where('user_id', Auth::user()->code)
                              ->where('product_id', $request->product_id)
                              ->first();

        if(!empty($favourite->id)){
          $delete = Favourite::find($favourite->id);
          $delete = $delete->delete();

          // return 0;
          $return_value = "2";
        }else{
          $create = Favourite::create(['user_id'=>Auth::user()->code,
                                       'product_id'=>$request->product_id]);
          // return 1;
          $return_value = "1";
        }

        $wish = Favourite::select(DB::raw('COUNT(id) AS totalWish'))
                                 ->where('user_id', Auth::user()->code)
                                 ->first();
                                 
        return array($return_value, $wish->totalWish);
    }


    public function add_to_wish(Request $request)
    {
        $favourite = Favourite::where('user_id', Auth::user()->code)
                              ->where('product_id', $request->product_id)
                              ->first();

        if(!empty($favourite->id)){
          return 0;
        }else{
          $create = Favourite::create(['user_id'=>Auth::user()->code,
                                       'product_id'=>$request->product_id]);
          return 1;
        }
    }


    public function remove_wish(Request $request)
    {
        
        $favourite = Favourite::where('user_id', Auth::user()->code)
                              ->where('product_id', $request->product_id)
                              ->first();

        $delete = Favourite::where('id', $favourite->id)->delete();
    }

    public function ApplyPromo(Request $request)
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

        if(!empty($request->use)){
          $update = AppliedPromotion::find($request->apid);
          $update = $update->update(['status'=>'1']);
          return "ok";
        }
           
        // if(!empty($request->voucher_id)){
        //     // $promotion = Promotion::where('id', $request->voucher_id)
        //     //                       ->where('status', '1')
        //     //                       ->first();

        //     $promotion = AppliedPromotion::select('applied_promotions.*','p.minSpend','p.quantity', 'p.start_date','p.end_date','p.limit_type')->leftJoin('promotions as p','p.id','applied_promotions.promotion_id')->where('applied_promotions.user_id',$BuyerCode)->where('applied_promotions.status',99)->where('applied_promotions.discount_code',$request->discount_code)->first();

        //     if(empty($promotion->id)){
        //         $promotion = Promotion::where('discount_code', $request->discount_code)
        //                     ->where('status', '1')
        //                     ->first();
        //     } 
        // } else {
        //     // $promotion = Promotion::where('discount_code', $request->discount_code)
        //     //                       ->where('status', '1')
        //     //                       ->orderBy('created_at', 'desc')
        //     //                       ->first();
        // }
        if (!empty($request->voucher_id)) {
            $applied = AppliedPromotion::where('user_id', $BuyerCode)
                ->where('status', 99)
                ->where('discount_code', $request->discount_code)
                ->first();

            if (empty($applied)) {
                return 7; 
            }

            $promotion = Promotion::where('id', $applied->promotion_id)
                ->where('status', 1)
                ->first();

        } else {
            $promotion = Promotion::where('discount_code', $request->discount_code)
                ->where('status', 1)
                ->first();
        }
        if(!empty($promotion->id)){
            // if($promotion->free_shipping == 1){
            //     $checkOwn = AppliedPromotion::where('status', '99')
            //                                 ->where('promotion_id', $promotion->id)
            //                                 ->first();

            //     if(empty($checkOwn->id)){
            //         return 0;
            //     }
            // }
            $transaction = AppliedPromotion::select(DB::raw('COUNT(id) AS CodeBalance'))
                                           ->where('promotion_id', $promotion->id)
                                           ->whereIn('status', ['1', '2'])
                                           ->first();
          
            $codeBalance = $promotion->quantity - $transaction->CodeBalance;

            if($codeBalance <= 0){
                return 1;
            }

            if(!empty($promotion->start_date) && !empty($promotion->end_date)){
                $t = date('Y-m-d H:i:s');
                $today = date('Y-m-d H:i:s', strtotime($t));
                $start = date('Y-m-d H:i:s', strtotime($promotion->start_date));
                $end = date('Y-m-d H:i:s', strtotime($promotion->end_date));
                if(($today <= $start) || ($today >= $end)){
                    return 2;
                }                
            }
            
            if(!empty($promotion->minSpend) && $promotion->minSpend > 0){
                $cartTotal = 0;
                if(!empty($request->cart_total)){
                    $cartTotal = floatval($request->cart_total);
                } else {
                    $cartItems = Cart::where('user_id', $BuyerCode)
                                    ->whereNull('mall')
                                    ->where('status', '1')
                                    ->get();
                    
                    foreach($cartItems as $item){
                        if(!empty($item->add_on_id) && empty($item->main_add_on)){
                            $cartTotal += floatval($item->get_product_det->add_on_price ?? 0) * $item->qty;
                        } else {
                            $cartTotal += floatval($item->get_product_det->price ?? 0) * $item->qty;
                        }
                    }
                }
                
                if($cartTotal < floatval($promotion->minSpend)){
                    return array(6, $promotion->minSpend);
                }
            }

            if(!empty($promotion->products) && empty($request->save)){
                $product = Cart::whereIn('product_id', explode(",", $promotion->products))->where('user_id', $BuyerCode)->get();
                if($product->isEmpty()){
                    return 3;
                }
            }

            if($promotion->limit_type == 2){
                $transaction = Transaction::where('discount_code', $promotion->discount_code)
                                          ->where('user_id', $BuyerCode)
                                          ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), date('Y-m-d'))
                                          ->whereIn('status',  ['1','98'])
                                          ->get();
                $count = count($transaction);
               
                if($count >= $promotion->usage_limit){
                    return 4;
                }
            }
          
            if($promotion->limit_type == 3){
                $transaction = AppliedPromotion::where('promotion_id', $promotion->id)
                                               ->where('user_id', $BuyerCode)
                                               ->where('status', '2')
                                               ->get();

                $count = count($transaction);

                // return $count;
                if($count >= $promotion->usage_limit){
                    return 4;
                }
            }
            // return $request->discount_code;
            $input = [];
            $input['promotion_id'] = $promotion->id;
            $input['user_id'] = $BuyerCode;
            $input['status'] = '1';
            

            if(!empty($request->save)){
              $checkApplied = AppliedPromotion::whereIn('status', ['1', '99'])->where('user_id', $BuyerCode)->where('promotion_id', $promotion->id)->first();
              if(!empty($checkApplied->id)){
                  return 6;
              }
              $input['status'] = '99';
            }

            if(!empty($request->checkout_apply)){
              $checkClaimed = AppliedPromotion::where('status', '99')->where('user_id', $BuyerCode)->where('promotion_id', $promotion->id)->first();
              if(!empty($checkClaimed->id)){
                  $updateClaimed = AppliedPromotion::find($checkClaimed->id)->update(['status'=>'1']);
                  $create = AppliedPromotion::find($checkClaimed->id);
              }else{
                $create = AppliedPromotion::create($input);  
              }

            }else{
              $create = AppliedPromotion::create($input);              
            }



            if($promotion->amount_type == 'Percentage'){
              $applied_discount_type = $promotion->amount."%";
            }else{
              $applied_discount_type = "RM ".$promotion->amount;
            }

            return array($promotion->amount, $promotion->amount_type, $promotion->id, $create->id, $applied_discount_type, $promotion->discount_code);

        }else{
            return 0;
        }
    }

    public function Repayment(Request $request)
    {
        $transaction = Transaction::where(DB::raw('md5(id)'), $request->transaction_id)
                                  ->first();

        $transactionDs =  TransactionDetail::where('transaction_id', $transaction->id)->get();

        $transaction_no = $transaction->transaction_no;
        $explodeTransaction = explode('-', $transaction_no);

        if(!empty($explodeTransaction[1])){
            ++$transaction_no;
        }else{
            $transaction_no = $transaction_no."-A";
        }

        $bank = Bank::where('bank_code', $request->bank_code)->first();

        // Set Old Transaction Status to Failed
        $updateOldTstatus = Transaction::where(DB::raw('md5(id)'), $request->transaction_id)
                                       ->update(['status'=>'95']);

        $createNewTransaction = Transaction::create(['transaction_no'=>$transaction_no,
                                                     'user_id'=>$transaction->user_id,
                                                     'weight'=>$transaction->weight,
                                                     'discount_code'=>$transaction->discount_code,
                                                     'sub_total'=>$transaction->sub_total,
                                                     'discount'=>$transaction->discount,
                                                     'tax'=>$transaction->tax,
                                                     'processing_fee'=>$transaction->processing_fee,
                                                     'shipping_fee'=>$transaction->shipping_fee,
                                                     'grand_total'=>$transaction->grand_total,
                                                     'address_name'=>$transaction->address_name,
                                                     'address'=>$transaction->address,
                                                     'postcode'=>$transaction->postcode,
                                                     'city'=>$transaction->city,
                                                     'state'=>$transaction->state,
                                                     'country'=>$transaction->country,
                                                     'phone'=>$transaction->phone,
                                                     'email'=>$transaction->email,
                                                     'bank_id'=>$bank->id,
                                                     'cod_address'=>$transaction->cod_address,
                                                     'status'=>$transaction->status]);
        $tdetails = [];
        foreach($transactionDs as $transactionD){
            $tdetails[] = [
                            'transaction_id'=>$createNewTransaction->id,
                            'product_image'=>$transactionD->product_image,
                            'product_id'=>$transactionD->product_id,
                            'item_code'=>$transactionD->item_code,
                            'product_code'=>$transactionD->product_code,
                            'unit_weight'=>$transactionD->weight,
                            'sub_category'=>$transactionD->sub_category,
                            'unit_weight'=>$transactionD->unit_weight,
                            'product_name'=>$transactionD->product_name,
                            'product_name_en'=>$transactionD->product_name_en,
                            'unit_price'=>$transactionD->unit_price,
                            'costing_price'=>$transactionD->costing_price,
                            'quantity'=>$transactionD->quantity,
                            'total_amount'=>$transactionD->total_amount,
                            'upgrade_agent'=>$transactionD->upgrade_agent,
                            'status'=>$transactionD->status,
                            'created_at'=>date('Y-m-d H:i:s'),
                            'updated_at'=>date('Y-m-d H:i:s')
                          ];
        }
        
        $createNewTransactionD = TransactionDetail::insert($tdetails);
        

        return md5($createNewTransaction->id);
    }

    public function cdmRepayment(Request $request){
      $buyerCode = Auth::user()->code;
      $transaction = Transaction::where(DB::raw('md5(id)'), $request->transaction_id)
                                  ->first();

        $transactionDs =  TransactionDetail::where('transaction_id', $transaction->id)->get();

        $transaction_no = $transaction->transaction_no;
        $explodeTransaction = explode('-', $transaction_no);

        if(!empty($explodeTransaction[1])){
            ++$transaction_no;
        }else{
            $transaction_no = $transaction_no."-A";
        }


        if(!empty($request->bank_slip)){
            $files = $request->file('bank_slip'); 
            $name = $files->getClientOriginalName();
            $exp = explode(".", $name);
            $file_ext = end($exp);
            $name = md5($name.date('Y-m-d H:i:s')).'.'.$file_ext;
            $files->move(GlobalController::get_image_path("uploads/bank_slip/".$buyerCode."/"), $name);
        }

        // if($request->cdm == 1){
        //       $input['cdm_bank_id'] = $request->cdm_bank_id;
        //       $input['bank_slip'] = "uploads/bank_slip/".$buyerCode."/".$name;
        //       $input['status'] = '98';
        //       $input['cod_address'] = "0";
        //   }

          $updateOldTstatus = Transaction::where(DB::raw('md5(id)'), $request->transaction_id)
                                       ->update(['status'=>'95']);

        $createNewTransaction = Transaction::create(['transaction_no'=>$transaction_no,
                                                     'user_id'=>$transaction->user_id,
                                                     'weight'=>$transaction->weight,
                                                     'discount_code'=>$transaction->discount_code,
                                                     'sub_total'=>$transaction->sub_total,
                                                     'discount'=>$transaction->discount,
                                                     'tax'=>$transaction->tax,
                                                     'processing_fee'=>$transaction->processing_fee,
                                                     'shipping_fee'=>$transaction->shipping_fee,
                                                     'grand_total'=>$transaction->grand_total,
                                                     'address_name'=>$transaction->address_name,
                                                     'address'=>$transaction->address,
                                                     'postcode'=>$transaction->postcode,
                                                     'city'=>$transaction->city,
                                                     'state'=>$transaction->state,
                                                     'country'=>$transaction->country,
                                                     'phone'=>$transaction->phone,
                                                     'email'=>$transaction->email,
                                                     'bank_slip'=>"uploads/bank_slip/".$buyerCode."/".$name,
                                                     'cdm_bank_id'=>$request->cdm_bank_id,
                                                     'cod_address'=>$transaction->cod_address,
                                                     'status'=>'98']);

        $tdetails = [];
        foreach($transactionDs as $transactionD){
            $tdetails[] = [
                            'transaction_id'=>$createNewTransaction->id,
                            'product_image'=>$transactionD->product_image,
                            'product_id'=>$transactionD->product_id,
                            'item_code'=>$transactionD->item_code,
                            'product_code'=>$transactionD->product_code,
                            'unit_weight'=>$transactionD->weight,
                            'sub_category'=>$transactionD->sub_category,
                            'unit_weight'=>$transactionD->unit_weight,
                            'product_name'=>$transactionD->product_name,
                            'product_name_en'=>$transactionD->product_name_en,
                            'unit_price'=>$transactionD->unit_price,
                            'costing_price'=>$transactionD->costing_price,
                            'quantity'=>$transactionD->quantity,
                            'total_amount'=>$transactionD->total_amount,
                            'upgrade_agent'=>$transactionD->upgrade_agent,
                            'status'=>$transactionD->status,
                            'created_at'=>date('Y-m-d H:i:s'),
                            'updated_at'=>date('Y-m-d H:i:s')
                          ];
        }
        
        $createNewTransactionD = TransactionDetail::insert($tdetails);
        

        return md5($createNewTransaction->id);
    }

    public function setBankDefault(Request $request)
    {
        $clearDefault = BankAccount::where('user_id', Auth::user()->code)->update(['default_banks' => NULL]);

        $setDefault = BankAccount::where("id", $request->bid);
        $setDefault = $setDefault->update(['default_banks' => '1']);

        $getDefault = BankAccount::where('default_banks', '1')
                                 ->where('user_id',  Auth::user()->code)
                                 ->first();

        return array($getDefault->bank_name, $getDefault->bank_holder_name, $getDefault->bank_account);
    }

    public function GetWalletBalance()
    {
        $balance = AffiliateCommission::select(DB::raw('SUM(comm_amount) as totalBalance'))
                                      ->where('user_id', Auth::user()->code)
                                      ->where('status', '1')
                                      ->first();

        $withdrawal = WithdrawalTransaction::select(DB::raw('SUM(amount) as totalWithdrawal'))
                                             ->where('user_id', Auth::user()->code)
                                             ->whereNotIn('status', ['97', '98'])
                                             ->first();

        $purchase = Transaction::select(DB::raw('SUM(grand_total) as totalPurchase'))
                               ->where('user_id', Auth::user()->code)
                               ->where('status', '1')
                               ->where('mall', '1')
                               ->first();

        $totalBalance = 0;
        
        $totalBalance = $balance->totalBalance - $withdrawal->totalWithdrawal - $purchase->totalPurchase;
        

        return $totalBalance;
    }


    public function getBankDetails(Request $request)
    {
        $bank = Bank::where('bank_code', $request->bank_id)->first();
        if(!empty($bank->id)){
            return array($bank->bank_name, $bank->bank_account, $bank->bank_holder_name);
        }else{
            return 0;
        }
    }

    public function removePromotion(Request $request)
    {
        $remove = AppliedPromotion::find($request->id)->update(['status' => '99']);
    }
    
    public function setNewGuest()
    {
        Session::put('continue_guest', '1');
    }

    public function Confirmation_message()
    {
        Session::forget('registered_account');
        Session::forget('registered_account_topup');
    }

    public function checkAccountFrozen(Request $request)
    {
      $hashed_password = Hash::make($request->password);

      $user = User::where('ic', $request->ic)
                  ->where('status', '2')
                  ->exists();

      $merchant = Agent::where('ic', $request->ic)
                          ->where('status', '2')
                          ->exists();

      // $admin = Admin::where('email', $request->username)
      //               ->where('password', $hashed_password)
      //               ->where('status', '2')
      //               ->exists();

      if($merchant == 1){
        return 0;
      }elseif($user == 1){
        return 0;
      }else{
        return 1;
      }
    }

    public function getVerifyCode(Request $request)
    {
        $phone = preg_replace("/^\+?{$request->country_code}/", '', $request->phone);

        if(empty($request->register)){
            $merchant = Agent::where('phone', $phone)->first();
            $admin = Admin::where('phone', $phone)->first();
            $user = User::where('phone', $phone)->first();

            if(empty($merchant->id) && empty($admin->id) && empty($user->id)){
                return 1;
            }            
        }

        $verify_code = VerifyCode::where('phone', $phone)
                                 ->where('status', '1')
                                 ->orderBy('created_at', 'desc')
                                 ->first();
        if(empty($verify_code->id)){
            $input = [];
            $input['code'] = mt_rand(100000, 999999);
            $input['phone'] = $phone;
            $verify_code = VerifyCode::create($input);
        }

        //if exists but time ady exceed 10min
        //Delete & Reset
        if(date('Y-m-d H:i:s') > date('Y-m-d H:i:s', strtotime($verify_code->created_at." +10 minutes"))){
            $delete = VerifyCode::where('phone', $phone)
                              ->delete();
            $input = [];
            $input['code'] = mt_rand(100000, 999999);
            $input['phone'] = $phone;
            $verify_code = VerifyCode::create($input);   
        }

        $verify = $this->sendVerifyCode($phone, $verify_code->code);

        if($verify != '2000 = SUCCESS'){
            $this->sendVerifyCode($phone, $verify_code->code);
        }
        
        $date_a = new DateTime(date('Y-m-d H:i:s', strtotime($verify_code->created_at." +10 minutes")));
        $date_b = new DateTime(date('Y-m-d H:i:s'));

        $interval = date_diff($date_a,$date_b);

        return array($verify_code->code, $interval->format('%I:%S'));
    }

    public function sendVerifyCode($phone, $code)
    {
        $destination = urlencode($phone);
        $message = "Zstore: Your verification code is: ".$code;
        $message = html_entity_decode($message, ENT_QUOTES, 'utf-8'); 
        $message = urlencode($message);
          
        $username = urlencode("zstore");
        $password = urlencode("zstore1234");
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

        return $http_result;
    }

    public function resetVerifyCode(Request $request)
    {
        $delete = VerifyCode::where('phone', $request->phone)
                            ->delete();
    }

    public function CheckLogin(Request $request)
    {

        $phone = preg_replace("/^\+?{$request->country_code}/", '', $request->phone);
        
        if(!empty($request->refferal_code)){
            $merchant = Agent::where('phone', $request->refferal_code)->first();
            $admin = Admin::where('phone', $request->refferal_code)->first();

            if(empty($merchant->id) && empty($admin->id)){
                return 4;
            }
        }

        $userIC = User::where('ic', $request->ICnumber)
                      ->exists();

        $MerchantIC = Agent::where('ic', $request->ICnumber)
                              ->exists();

        if($userIC == 1 || $MerchantIC == 1){
          return 5;
        }

        $user = User::where('phone', $phone)
                    ->where('country_code', $request->country_code)
                    ->where('status', '1')
                    ->exists();

        $merchant = Agent::where('phone', $phone)
                            ->where('country_code', $request->country_code)
                            ->where('status', '1')
                            ->exists();

        $admin = Admin::where('phone', $phone)
                            ->where('country_code', $request->country_code)
                            ->where('status', '1')
                            ->exists();

        if($user == 0 && $merchant == 0 && $admin == 0){
            return 2;
        }else{
            return 3;
        }
    }

    public function getVariation(Request $request)
    {
        $getCurrentLogin = GlobalController::getCurrentLogin();
        $BuyerCode = $getCurrentLogin['code'];
        $BuyerLvl = $getCurrentLogin['lvl'];

        if(!empty(request('member'))){
            $BuyerCode = request('member');
        }
        $variation = ProductVariation::find($request->vid);
        $price = 0;
        $special_price = 0;
        $variation_img = "";

        if(!empty($variation->id)){
            $get_product_pricing = GlobalController::get_product_pricing(md5($variation->product_id), $BuyerCode, $variation->id);

            $price = $get_product_pricing['product_price'];
            $special_price = $get_product_pricing['product_price'];
        
            $original_price = $get_product_pricing['product_special_price'];

            if(!empty($variation->variation_image)){
                $variation_img = asset($variation->variation_image);
            }
        }

        $get_pv = $variation->variation_get_pv;

        $balance = GlobalController::variation_balance_quantity($request->vid);

        return array(number_format($special_price, 2), number_format($price, 2), $balance, $get_pv, $original_price, $variation_img);
    }

    public function getSecondVariation(Request $request)
    {
        $getCurrentLogin = GlobalController::getCurrentLogin();
        $BuyerCode = $getCurrentLogin['code'];
        $BuyerLvl = $getCurrentLogin['lvl'];

        if(!empty($request->member)){
            $BuyerCode = $request->member;
        }

        $variation = ProductSecondVariation::find($request->vid);
        $price = 0;
        $special_price = 0;
        $variation_img = "";

        if(!empty($variation->id)){
            $get_product_pricing = GlobalController::get_product_pricing(md5($variation->product_id), $BuyerCode, $variation->variation_id, $variation->id);

            $price = $get_product_pricing['product_price'];
            $special_price = $get_product_pricing['product_price'];
        
            $original_price = $get_product_pricing['product_special_price'];

            if(!empty($variation->variation_image)){
                $variation_img = asset($variation->variation_image);
            }
        }

        $get_pv = $variation->variation_get_pv;

        $balance = GlobalController::second_variation_balance_quantity($request->vid);

        return array(number_format($special_price, 2), number_format($price, 2), $balance, $get_pv, $original_price, $variation_img);
    }

    public function getSecondVariationList(Request $request)
    {
        $variations = ProductSecondVariation::where('variation_id', $request->vid)
                                            ->orderBy('variation_name', 'asc')
                                            ->get();

        $result = "";
        foreach($variations as $variation){

        $balance = GlobalController::second_variation_balance_quantity($variation->id);

          $out_of_stock = ($balance <= 0) ? 'out-of-stock' : '';

          $result .= '<div class="second_variation_option '.$out_of_stock.'" data-id="'.$variation->id.'"
                           style="margin: 0 10.9px 8px 0;">
                        '.$variation->variation_name.'
                      </div>';

        }

        return $result;
    }

    public function getVariationDropdown(Request $request)
    {
        if(!empty(Auth::guard('admin')->check())){
            $BuyerCode = Auth::guard('admin')->user()->code;
            $BuyerLvl = Auth::guard('admin')->user()->lvl;
        }elseif(!empty(Auth::guard('agent')->check())){
            $BuyerCode = Auth::guard('agent')->user()->code;
            $BuyerLvl = Auth::guard('agent')->user()->lvl;
        }elseif(!empty(Auth::guard('web')->check())){
            $BuyerCode = Auth::guard('web')->user()->code;
            $BuyerLvl = Auth::guard('web')->user()->lvl;
        }else{
            $BuyerCode = "";
            $BuyerLvl = "";
        }

        $product = Product::find($request->product_id);

        $result = "";
        if($product->variation_enable == 1){
            $variations = ProductVariation::where('product_id', $product->id)
                                          ->where('status', '1')
                                          ->get();

            if(!$variations->isEmpty()){
                $result .= '<label>Variation</label>';
                $result .= '<select class="form-control variation_option" name="variation_option" id="variation-option" onchange="getSecondVariationList();">';
                $result .= '<option value="">Please Select Variation</option>';
                foreach ($variations as $key => $variation) {

                    $result .= '<option value="'.$variation->id.'">
                                  '.$variation->variation_name;
                    if($product->second_variation_enable != '1'){
                        $price = $this->getProductPrice($BuyerCode, $product->id, $variation->id, "");
                        $result .= ' (RM'.number_format($price, 2).')';
                    }
                    $result .= '</option>';
                }
                $result .= '</select>';
            }
        }

        return $result;
    }

    public function getSecondVariationDropdown(Request $request)
    {
        if(!empty(Auth::guard('admin')->check())){
            $BuyerCode = Auth::guard('admin')->user()->code;
            $BuyerLvl = Auth::guard('admin')->user()->lvl;
        }elseif(!empty(Auth::guard('agent')->check())){
            $BuyerCode = Auth::guard('agent')->user()->code;
            $BuyerLvl = Auth::guard('agent')->user()->lvl;
        }elseif(!empty(Auth::guard('web')->check())){
            $BuyerCode = Auth::guard('web')->user()->code;
            $BuyerLvl = Auth::guard('web')->user()->lvl;
        }else{
            $BuyerCode = "";
            $BuyerLvl = "";
        }

        $first_variation = ProductVariation::find($request->vid);

        $product = Product::find($first_variation->product_id);

        $result = "";
        $variations = ProductSecondVariation::where('variation_id', $request->vid)
                                      ->where('status', '1')
                                      ->get();

        if(!$variations->isEmpty()){
            $result .= '<label>Second Variation</label>';
            $result .= '<select class="form-control second_variation_option" name="second_variation_option" id="second-variation-option">';
            $result .= '<option value="">Please Select Variation</option>';
            foreach ($variations as $key => $variation) {
                $price = $this->getProductPrice($BuyerCode, $product->id, $first_variation->id, $variation->id);

                $result .= '<option value="'.$variation->id.'">
                              '.$variation->variation_name.' (RM'.number_format($price, 2).')
                            </option>';
            }
            $result .= '</select>';
        }

        return $result;
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

        return $quantityAmount->variation_stock - $transaction->TransCart;
    }

    public function getShippingFee(Request $request)
    {
        $totalshipping_fees = 0;
        $actual_weight = $request->total_weight;

        if($request->state > 16){
          $shipping_fees = SettingShippingFee::where('area', 'sg')
                                             ->orderBy('weight', 'desc')
                                             ->first();
          if(!empty($shipping_fees->id)){
            $totalshipping_fees = $shipping_fees->shipping_fee;                
          }
        }elseif($request->state != '11' && $request->state != '12' && $request->state != '15'){
          
          $shipping_fees = SettingShippingFee::where('area', 'west')
                                             ->orderBy('weight', 'desc')
                                             ->first();
          if(!empty($shipping_fees->id)){
            $totalshipping_fees = $shipping_fees->shipping_fee;                
            
          }

        }else{
          $shipping_fees = SettingShippingFee::where('area', 'east')
                                             ->orderBy('weight', 'desc')
                                             ->first();
          if(!empty($shipping_fees->id)){
            $totalshipping_fees = $shipping_fees->shipping_fee;                
          }
        }
        // exit();

        $totalshipping_fees = (!empty($totalshipping_fees)) ? $totalshipping_fees : 0;
        $perKG = 5;
        $totalWeight = ceil($actual_weight / $perKG);
        $totalshipping_fees = $totalshipping_fees * $totalWeight;

        return $totalshipping_fees;
    }

    public function guestAgent(Request $request)
    {
        Session::put('guest_agent', $request->agent);
    }

    public function getTopupPackages(Request $request)
    {
        $topup = SettingTopup::find($request->tid);
        $profit_bonus = 0;
        if(!empty($topup->profit_amount)){
          if($topup->profit_type == 'Percentage'){
            $profit_bonus = $topup->topup_amount * $topup->profit_amount / 100;
          }else{
            $profit_bonus = $topup->profit_amount;
          }
        }

        $profit_display = "";

        if($profit_bonus > 0){
            $profit_display = " + (RM ".$profit_bonus.")";
        }
        return array($topup->topup_amount, $profit_display);
    }

    public function CreateCartLink()
    {
        $carts = Cart::where('user_id', Auth::user()->code)->get();
        $unique_id = strtotime(date('Y-m-d H:i:s'));
        foreach($carts as $cart){
            $input = [];

            $input['unique_id'] = $unique_id;
            $input['user_id'] = $cart->user_id;
            $input['product_id'] = $cart->product_id;
            $input['sub_category_id'] = $cart->sub_category_id;
            $input['qty'] = $cart->qty;

            $links = CartLink::create($input);
        }

        Cart::where('user_id', Auth::user()->code)->delete();

        return $unique_id;
    }

    public function ProceedCartLink(Request $request)
    {
        if(!empty(Auth::guard('admin')->check())){
            $BuyerCode = Auth::guard('admin')->user()->code;
        }elseif(!empty(Auth::guard('agent')->check())){
            $BuyerCode = Auth::guard('agent')->user()->code;
        }elseif(!empty(Auth::guard('web')->check())){
            $BuyerCode = Auth::guard('web')->user()->code;
        }else{
            $BuyerCode = "";
        }

        if($BuyerCode == ""){
          Session::put('cart_link_id', $request->link_id);
        }else{
          $cart_links = CartLink::where('unique_id', $request->link_id)->get();

          foreach($cart_links as $cart){
              $input = [];
              $input['user_id'] = $BuyerCode;
              $input['product_id'] = $cart->product_id;
              $input['sub_category_id'] = $cart->sub_category_id;
              $input['qty'] = $cart->qty;

              $cart = Cart::create($input);
          }

          CartLink::where('unique_id', $request->link_id)->delete();

          // session()->forget('cart_link_id');
        }

    }

    public function getCartAmount(Request $request)
    {
        if(!empty($request->mall)){
            $get_cart_details = GlobalController::get_cart_details_mall(Auth::user()->code, Auth::user()->lvl, $request->store);
        }else{
            $get_cart_details = GlobalController::get_cart_details(Auth::user()->code, Auth::user()->lvl, $request->store);
        }

        return array($get_cart_details['sub_total'], 
                     $get_cart_details['total_weight'], 
                     $get_cart_details['total_discount'], 
                     $get_cart_details['total_shipping_fee'], 
                     $get_cart_details['grand_total'], 
                     $get_cart_details['check_order_shipping_fee']);
    }

    public function getAddressDetails(Request $request)
    {
        $address = CodAddress::find($request->address_id);
        if(!empty($address->id)){
            return $address->address_desc;
        }else{
            return 0;
        }
    }

    public function getReferrerDetails(Request $request)
    {
      $referrer = Agent::where('code', $request->user_id)
                          ->first();
      if(empty($referrer)){
        $referrer = Admin::where('code', $request->user_id)
                         ->first();
      }

      if(!empty($referrer->id)){
        return "名字: ". $referrer->f_name."".$referrer->l_name."<br>电邮: " .$referrer->email. "<br>电话号码: " .$referrer->country_code ."". $referrer->phone;
      }else{
        return 0;
      }
    }

    public function getUplineDetail(Request $request)
    {
        $merchant = Agent::where(DB::raw('CONCAT(display_code, display_running_no)'), 'like', '%'.$request->master_id.'%')->where('status', '1')->first();
        $admin = Admin::where(DB::raw('CONCAT(display_code, display_running_no)'), 'like', '%'.$request->master_id.'%')->where('status', '1')->first();
        $user = User::where(DB::raw('CONCAT(display_code, display_running_no)'), 'like', '%'.$request->master_id.'%')->where('status', '1')->first();

        if(!empty($merchant->id)){
            $uplineDetail = $merchant;
        }

        if(!empty($admin->id)){
            $uplineDetail = $admin;
        }

        if(!empty($user->id)){
            $uplineDetail = $user;
        }

        $state_value = "";
        if(!empty($request->state)){
            $states = State::find($request->state);
            if(!empty($states->id)){
                $state_value = $states->name;
            }
        }

        if(empty($state_value)){
            $state_value = $request->state;
        }

        $country_value = "";
        if(!empty($request->country)){
            $countries = TblCountry::where('country_id', $request->country)->first();
            if(!empty($countries->country_id)){
                $country_value = $countries->country_name;
            }
        }

        if(!empty($uplineDetail->l_name)){
            $uplineName = $uplineDetail->f_name." ".$uplineDetail->l_name;
        }else{
            $uplineName = $uplineDetail->f_name;
        }

        if(!empty($uplineDetail->id)){
            return array($uplineName, $state_value, $country_value);
        }else{
            return "not exists";
        }
    }

    public function viewTransaction(Request $request)
    {
        $transaction = Transaction::find($request->tid);

        $transaction = $transaction->update(['viewed'=>'1']);

        return "done";
    }

    public function getStatePrice(Request $request)
    {
        if(!empty(Auth::guard('admin')->check())){
            $BuyerCode = Auth::guard('admin')->user()->code;
            $BuyerLvl = Auth::guard('admin')->user()->lvl;
        }elseif(!empty(Auth::guard('agent')->check())){
            $BuyerCode = Auth::guard('agent')->user()->code;
            $BuyerLvl = Auth::guard('agent')->user()->lvl;
        }elseif(!empty(Auth::guard('web')->check())){
            $BuyerCode = Auth::guard('web')->user()->code;
            $BuyerLvl = Auth::guard('web')->user()->lvl;
        }elseif(!empty(Auth::guard('corporate')->check())){
            $BuyerCode = Auth::guard('corporate')->user()->code;
            $BuyerLvl = Auth::guard('corporate')->user()->lvl;
        }else{
          if(empty($_COOKIE['new_guest'])){
            $BuyerCode = setcookie('new_guest', strtotime(date('Y-m-d H:i:s')).rand(100000, 999999), time() + (86400 * 30), "/");
          }else{
            $BuyerCode = $_COOKIE['new_guest'];
          }
            $BuyerLvl = "";
        }

        $exp = explode(",", $request->cart_items);

        $carts = Cart::whereIn(DB::raw('md5(id)'), $exp)->get();

        $return_value = [];
        foreach($carts as $cart){
            $search_product = Product::find($cart->product_id);
            if(!empty($search_product->id)){
                if($search_product->variation_enable == 1){
                    if($search_product->second_variation_enable == 1){
                        // $agent_price = ProductSecondVariation::where('', '')->first();
                    }else{

                    }
                }else{
                    if($request->state != '11' && $request->state != '12' && $request->state != '15'){
                        $return_value[md5($cart->id)] = (!empty($search_product->west_special_price)) ? $search_product->west_special_price * $cart->qty : $search_product->west_price * $cart->qty;
                    }else{
                        $return_value[md5($cart->id)] = (!empty($search_product->special_price)) ? $search_product->special_price * $cart->qty : $search_product->price * $cart->qty;
                    }
                }
            }
        }

        return json_encode($return_value);
    }

    public function getCartQuantity(Request $request)
    {
        $carts = Cart::where(DB::raw('md5(id)'), $request->cart_id)->first();

        return $carts->qty;
    }

    public function getCities(Request $request)
    {
        $cities = City::where('state_id', $request->state)->orderBy('city_name', 'asc')->get();

        if(!$cities->isEmpty()){
            $selected_city = !empty($request->selected_city) ? $request->selected_city : '';
            $selected_value = "";

            $c = '<select class="form-control form-control--sm city" name="city" style="background-color: #f7f7f8;">';
            foreach($cities as $city){
            if($selected_city == $city->id){
                $selected_value = "selected";
            }else{
                $selected_value = "";
            }
            $c .= '<option '.$selected_value.' value="'.$city->id.'">'.$city->city_name.'</option>';
            }
            $c .= '</select>';
        }else{
            $c = '<select class="form-control form-control--sm city" name="city" style="background-color: #f7f7f8; cursor: not-allowed;" disabled>';
            $c .= '<option value="">Please Select State First</option>';
            $c .= '</select>';
        }


        return $c;
    }

    public function getBillCities(Request $request)
    {
        $cities = City::where('state_id', $request->state)->orderBy('city_name', 'asc')->get();

        if(!$cities->isEmpty()){
            $selected_city = !empty($request->selected_city) ? $request->selected_city : '';
            $selected_value = "";
            $disabled = "";

            if($request->check == '1'){
                $disabled = "disabled";
            }else{
                $disabled = "";
            }

            $c = '<select class="form-control form-control--sm bill_city" name="bill_city" style="background-color: #f7f7f8;" '.$disabled.'>';
            foreach($cities as $city){
            if($selected_city == $city->id){
                $selected_value = "selected";
            }else{
                $selected_value = "";
            }
            $c .= '<option '.$selected_value.' value="'.$city->id.'">'.$city->city_name.'</option>';
            }
            $c .= '</select>';
        }else{
            $c = '<select class="form-control form-control--sm city" name="city" style="background-color: #f7f7f8; cursor: not-allowed;" disabled>';
            $c .= '<option value="">Please Select State First</option>';
            $c .= '</select>';
        }


        return $c;
    }

    public function getOrderNotification(Request $request)
    {
        $transactions = Transaction::select('product_name', 'product_image', 'transactions.created_at', 'transactions.address_name as buyer_name', 'd.product_id as pid')
                                   ->join('transaction_details as d', 'd.id', 'transactions.id')
                                   ->where('transactions.status', '1')
                                   ->orderBy(DB::raw('RAND()'))
                                   ->take(1)
                                   ->get();

        $fake = SalesPopup::select('p.product_name', 'pi.image as product_image', 'sales_popups.sales_date as created_at', 'sales_popups.name as buyer_name', 'p.id as pid')
                            ->join('products as p', 'p.id', 'sales_popups.product_id')
                            ->leftJoin('product_images as pi', 'pi.product_id', 'p.id')
                            ->orderBy(DB::raw('RAND()'))
                            ->take(1)
                            ->get();

        $all = $transactions->concat($fake);

        $all = array_reverse(array_sort($all, function ($value) {
            return $value['created_at'];
        }));
        
        $product_name = "";
        $product_image = "";
        $product_date = "";
        $product_user = "Someone purchased";


        foreach($all as $value){
            $product_id = $value->pid;
            $product_name = $value->product_name;
            if(!empty($value->product_image)){
              $product_image = $value->product_image;
            }else{
              $product_image = 'images/no-image-available-icon-61.jpg';
            }
            $product_date = $value->created_at;
            $product_user = $value->buyer_name;
        }

        $now_time = strtotime(date('Y-m-d H:i:s'));
        $order_time = strtotime($product_date);

        $d1 = new DateTime(date('Y-m-d H:i:s'));
        $d2 = new DateTime(date('Y-m-d H:i:s', $order_time));
        $interval = $d1->diff($d2);
        $diffInSeconds = $interval->s; //45
        $diffInMinutes = $interval->i; //23
        $diffInHours   = $interval->h; //8
        $diffInDays    = $interval->d; //21
        $diffInMonths  = $interval->m; //4
        $diffInYears   = $interval->y; //1


        // if(!empty($diffInYears)){
        //     $display_time = $diffInYears." Years";
        // }elseif(!empty($diffInMonths)){
        //     $display_time = $diffInMonths." Month";
        // }elseif(!empty($diffInDays)){
        //     $display_time = $diffInDays." Days";
        // }elseif(!empty($diffInHours)){
        //     $display_time = $diffInHours." Hours";
        // }elseif(!empty($diffInMinutes)){
        //     $display_time = $diffInMinutes." Min";
        // }elseif(!empty($diffInSeconds)){
        //     $display_time = $diffInSeconds." Sec";
        // }

        $array_days = ['1 Hours', '1 Days', 'Few Seconds', '2 Hours', '3 Hours', '5 Hours'];
        $k = array_rand($array_days);
        $display_time = $array_days[$k];

        return array($product_name, asset($product_image), $display_time, $product_user, route('details', md5($product_id)));
    }

    public function GetRegisterPayment(Request $request)
    {
        $second_variation_de = ProductSecondVariation::find($request->svid);
        $variation_de = ProductVariation::find($request->vid);
        $product_de = Product::where(DB::raw('md5(id)'), $request->pid)->first();
        $quantity = !empty($request->quantity) ? $request->quantity : 1;
        $pricing = 0;
        $weight = 0;
        if(!empty($second_variation_de->id)){
            $pricing = !empty($second_variation_de->variation_special_price) ? $second_variation_de->variation_special_price : $second_variation_de->variation_price;

            $weight = $second_variation_de->variation_weight;
        }elseif(!empty($variation_de->id)){
            $pricing = !empty($variation_de->variation_special_price) ? $variation_de->variation_special_price : $variation_de->variation_price;
            $weight = $variation_de->variation_weight;
        }elseif(!empty($product_de->id)){
            $pricing = !empty($product_de->special_price) ? $product_de->special_price : $product_de->price;
            $weight = $product_de->weight;
        }

        $totalAmount = $pricing * $quantity;

        $totalWeight = $weight * $quantity;

        $totalshipping_fees = 0;
        if($request->get_country == '160'){
            if($request->get_state != '11' && $request->get_state != '12' && $request->get_state != '15'){
                $shipping_fees = SettingShippingFee::where('area', 'west')
                                                     ->where('weight', '<=', ceil($totalWeight))
                                                     ->orderBy('weight', 'desc')
                                                     ->first();

                if(!empty($shipping_fees->id)){
                    $totalshipping_fees = !empty($shipping_fees->shipping_fee) ? $shipping_fees->shipping_fee : 0;
                }
            }else{

                $shipping_fees = SettingShippingFee::where('area', 'east')
                                                  ->where('weight', '<=', ceil($totalWeight))
                                                   ->orderBy('weight', 'desc');

                $shipping_fees = $shipping_fees->first();

                if(!empty($shipping_fees->id)){
                    $totalshipping_fees = !empty($shipping_fees->shipping_fee) ? $shipping_fees->shipping_fee : 0;                
                }
            }
        }else{
            $shipping_fees = SettingShippingFee::where('country_id', $request->get_country)
                                               ->where('weight', '<=', ceil($totalWeight))
                                               ->orderBy('weight', 'desc');

            $shipping_fees = $shipping_fees->first();

            if(!empty($shipping_fees->id)){
                $totalshipping_fees = $shipping_fees->shipping_fee;
            }
        }

        $pricing = $pricing * $quantity;

        return array($pricing, $totalshipping_fees, ($pricing + $totalshipping_fees));
    }

    public function setPickup(Request $request)
    {
        $fp = explode(',', $request->transaction_no);
        $transactions = Transaction::whereIn(DB::raw('md5(transaction_no)'), $fp)->update(['cod_address'=>'1', 'status'=>'98']);
    }

    public function getProductPrice($code, $product_id, $variation_id, $sec_variation_id)
    {
        $merchants = Agent::where('code', $code)->first();
        $admins = Admin::where('code', $code)->first();
        $users = User::where('code', $code)->first();

        $currentUser = "";
        if(!empty($merchants->id)){
            $currentUser = $merchants;
        }elseif(!empty($admins->id)){
            $currentUser = $admins;
        }elseif(!empty($users->id)){
            $currentUser = $users;
        }

        $product = Product::find($product_id);
        if(empty($product->id)){
          abort(417);
        }

        $price = 0;
        if(!empty($merchants->id)){
            $agent_price = AgentPrice::where('agent_lvl_id', $currentUser->lvl)
                                         ->where('product_id', $product->id);

            if(!empty($variation_id)){
                $agent_price = $agent_price->where('variation_id', $variation_id);
            }

            if(!empty($sec_variation_id)){
                $agent_price = $agent_price->where('second_variation_id', $sec_variation_id);
            }
                                         
            $agent_price = $agent_price->first();

            if(!empty($agent_price->special_price) && $agent_price->special_price > 0){
                $price = $agent_price->special_price;
            }else{
                $price = $agent_price->price;
            }
        }else{
            if($product->variation_enable == '1' && !empty($variation_id)){
                if($product->second_variation_enable == '1' && !empty($sec_variation_id)){
                    $sec_variation = ProductSecondVariation::find($sec_variation_id);

                    if(!empty($sec_variation->second_variation_special_price) && $sec_variation->second_variation_special_price > 0){
                        $price = $sec_variation->variation_special_price;
                    }else{
                        $price = $sec_variation->variation_price;        
                    }
                }else{
                    $variation = ProductVariation::find($variation_id);

                    if(!empty($variation->variation_special_price) && $variation->variation_special_price > 0){
                        $price = $variation->variation_special_price;
                    }else{
                        $price = $variation->variation_price;
                    }
                }
            }else{
                if(!empty($product->special_price) && $product->special_price > 0){
                    $price = $product->special_price;
                }else{
                    $price = $product->price;
                }
            }
        }

        
        return $price;
        
    }

    public function getCartTotalPrice($code)
    {
        $leftJoin = DB::raw("(SELECT * FROM product_images ORDER BY created_at ASC) AS i");
        $carts = Cart::select('carts.*', 'p.product_name', 'p.product_name_en',
                              'agent_price', 'agent_special_price', 'price', 'special_price', 'weight',
                              DB::raw('SUM(IF(special_price != "0", special_price * qty, price * qty)) AS totalSum'), 
                              DB::raw('SUM(IF(agent_special_price != "0", agent_price * qty, price * qty)) AS totalAgentSum'), 
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
                              'p.level_up',
                              'p.free_shipping',
                              'p.corporate_special_price',
                              'p.corporate_price',
                              'p.corporate_moq',
                              'p.voucher_id',
                              'scl.variation_corporate_price',
                              'scl.variation_corporate_special_price',
                              'sscl.variation_corporate_price as second_variation_corporate_price', 
                              'sscl.variation_corporate_special_price as second_variation_corporate_special_price',
                              'scl.variation_west_price',
                              'scl.variation_west_special_price',
                              'sscl.variation_west_price as second_variation_west_price', 
                              'sscl.variation_west_special_price as second_variation_west_special_price',

                              'scl.variation_costing_price',
                              'sscl.variation_costing_price as second_variation_costing_price',
                              'west_special_price',
                              'west_price')
                     ->join('products AS p', 'p.id', 'carts.product_id')
                     ->leftJoin('product_variations AS scl', 'scl.id', 'carts.sub_category_id')
                     ->leftJoin('product_second_variations AS sscl', 'sscl.id', 'carts.second_sub_category_id')
                     ->leftJoin($leftJoin, function($join) {
                        $join->on('p.id', '=', 'i.product_id');
                     })
                     ->where('carts.status', '1')
                     ->where('carts.user_id', $code)
                     ->groupBy('carts.id')
                     ->get();

        $merchants = Agent::where('code', $code)->first();
        $admins = Admin::where('code', $code)->first();
        $users = User::where('code', $code)->first();

        $currentUser = "";
        if(!empty($merchants->id)){
            $currentUser = $merchants;
        }elseif(!empty($admins->id)){
            $currentUser = $admins;
        }elseif(!empty($users->id)){
            $currentUser = $users;
        }

        $totalAmount = 0;
        foreach($carts as $cart){
            if(!empty($merchants->id)){
                // $agent_price = AgentPrice::where('agent_lvl_id', $currentUser->lvl)
                //                          ->where('product_id', $cart->product_id)
                //                          ->where('variation_id', $cart->sub_category_id)
                //                          ->where('second_variation_id', $cart->second_sub_category_id)
                //                          ->first();

                // $agent_price = AgentPrice::where('agent_lvl_id', $currentUser->lvl)
                //                          ->where('product_id', $cart->product_id);

                // if(!empty($cart->sub_category_id) && $cart->sub_category_id != 'undefined'){
                //     $agent_price = $agent_price->where('variation_id', $cart->sub_category_id);
                // }

                // if(!empty($cart->second_sub_category_id) && $cart->second_sub_category_id != 'undefined'){
                //     $agent_price = $agent_price->where('second_variation_id', $cart->second_sub_category_id);
                // }
                                             
                // $agent_price = $agent_price->first();

                $agent_price = AgentPrice::where('agent_lvl_id', $currentUser->lvl);

                if(!empty($cart->second_sub_category_id) && $cart->second_sub_category_id != 'undefined'){

                    $agent_price = $agent_price->where('second_variation_id', $cart->second_sub_category_id);

                }elseif(!empty($cart->sub_category_id) && $cart->sub_category_id != 'undefined'){

                    $agent_price = $agent_price->where('variation_id', $cart->sub_category_id);

                }else{
                    $agent_price = $agent_price->where('product_id', $cart->product_id);
                }
                $agent_price = $agent_price->first();

                if(!empty($agent_price->special_price)){
                    $totalAmount += $agent_price->special_price * $cart->qty;
                }else{
                    $totalAmount += $agent_price->price * $cart->qty;
                }
            }else{
                if($cart->variation_enable == '1'){
                    if($cart->second_variation_enable == '1'){
                        if(!empty($cart->second_variation_special_price)){
                            $totalAmount += $cart->second_variation_special_price * $cart->qty;
                        }else{
                            $totalAmount += $cart->second_variation_price * $cart->qty;             
                        }
                    }else{
                        if(!empty($cart->variation_special_price)){
                            $totalAmount += $cart->variation_special_price * $cart->qty;
                        }else{
                            $totalAmount += $cart->variation_price * $cart->qty;             
                        }
                    }
                }else{
                    if(!empty($cart->special_price)){
                        $totalAmount += $cart->special_price * $cart->qty;
                    }else{
                        $totalAmount += $cart->price * $cart->qty;             
                    }
                }
            }
        }

        return $totalAmount;
    }

    public function getVariationPromotion(Request $request)
    {
        if(!empty(Auth::guard('admin')->check())){
            $BuyerCode = Auth::guard('admin')->user()->code;
            $BuyerLvl = Auth::guard('admin')->user()->lvl;
        }elseif(!empty(Auth::guard('agent')->check())){
            $BuyerCode = Auth::guard('agent')->user()->code;
            $BuyerLvl = Auth::guard('agent')->user()->lvl;
        }elseif(!empty(Auth::guard('web')->check())){
            $BuyerCode = Auth::guard('web')->user()->code;
            $BuyerLvl = Auth::guard('web')->user()->lvl;
        }else{
            $BuyerCode = "";
            $BuyerLvl = "";
        }

        $variation = ProductVariation::find($request->vid);
        $price = 0;
        $special_price = 0;
        $customer_special_price = 0;
        $get_percentage = 0;
        $get_pv = 0;

        if(Auth::guard('admin')->check() || Auth::guard('agent')->check()){
            $agentPrice = PromoAgentItemDetail::where('variation_id', $request->vid)
                                    ->where('promo_item_id', $request->pid)
                                    ->where('status', '1')
                                    ->first();

            if(!empty($agentPrice->special_price)){
              $special_price = $agentPrice->special_price;
            }
            $price = $agentPrice->price;
            if(!empty($special_price)){
                $get_amount = $price - $special_price;
                $get_percentage = ($get_amount / $price) * 100;
            }

            $get_pv = $variation->variation_get_pv;
        }else{
            $customerPrice = PromoAgentItemDetail::where('variation_id', $request->vid)
                                  ->whereNull('agent_lvl_id')
                                  ->where('promo_item_id', $request->pid)
                                  ->where('status', '1')
                                  ->first();

            
            $price = $customerPrice->price;

            $special_price = $customerPrice->special_price;
            if(!empty($special_price)){
                $get_amount = $price - $special_price;
                $get_percentage = ($get_amount / $price) * 100;
            }
        }

        $balance = $this->VariationBalanceQuantity($request->vid);

        return array(number_format($special_price, 2), number_format($price, 2), $balance, number_format($customer_special_price, 2),
                     number_format($get_percentage), $get_pv);
    }

    public function getSecondVariationPromotion(Request $request)
    {
        if(!empty(Auth::guard('admin')->check())){
            $BuyerCode = Auth::guard('admin')->user()->code;
            $BuyerLvl = Auth::guard('admin')->user()->lvl;
        }elseif(!empty(Auth::guard('agent')->check())){
            $BuyerCode = Auth::guard('agent')->user()->code;
            $BuyerLvl = Auth::guard('agent')->user()->lvl;
        }elseif(!empty(Auth::guard('web')->check())){
            $BuyerCode = Auth::guard('web')->user()->code;
            $BuyerLvl = Auth::guard('web')->user()->lvl;
        }else{
            $BuyerCode = "";
            $BuyerLvl = "";
        }

        $price = 0;
        $special_price = 0;
        $customer_special_price = 0;

        if(Auth::guard('admin')->check() || Auth::guard('agent')->check()){
          
          $agentPrice = PromoAgentItemDetail::where('second_variation_id', $request->vid)
                                  ->where('agent_lvl_id', $BuyerLvl)
                                  ->where('promo_item_id', $request->pid)
                                  ->where('status', '1')
                                  ->first();

          if(!empty($agentPrice->special_price)){
              $special_price = $agentPrice->special_price;
            }
          $price = $agentPrice->price;

        }else{

          $customerPrice = PromoAgentItemDetail::where('second_variation_id', $request->vid)
                                  ->whereNull('agent_lvl_id')
                                  ->where('promo_item_id', $request->pid)
                                  ->where('status', '1')
                                  ->first();

            if(!empty($customerPrice->special_price)){
                $price = $customerPrice->special_price;
            }else{
                $price = $customerPrice->price;
            }

            $customer_special_price = $customerPrice->special_price;

        }

        $balance = $this->SecondVariationBalanceQuantity($request->vid);

        return array(number_format($special_price, 2), number_format($price, 2), $balance, number_format($customer_special_price, 2));
    }

    public function LoadTotalPV()
    {
        if(!empty(Auth::guard('admin')->check())){
            $buyerCode = Auth::guard('admin')->user()->code;
        }elseif(!empty(Auth::guard('agent')->check())){
            $buyerCode = Auth::guard('agent')->user()->code;
        }elseif(!empty(Auth::guard('corporate')->check())){
            $buyerCode = Auth::guard('corporate')->user()->code;
        }elseif(!empty(Auth::guard('web')->check())){
            $buyerCode = Auth::guard('web')->user()->code;
        }else{
          if(empty($_COOKIE['new_guest'])){
            $buyerCode = setcookie('new_guest', strtotime(date('Y-m-d H:i:s')).rand(100000, 999999), time() + (86400 * 30), "/");
          }else{
            $buyerCode = $_COOKIE['new_guest'];
          }
        }

        // $transactions = Transaction::select(DB::raw('SUM((unit_price * d.quantity) * 0.5) as totalGetPV'))
        //                            ->join('transaction_details as d', 'd.transaction_id', 'transactions.id')
        //                            ->where('user_id', $buyerCode)
        //                            ->where('transactions.status', '1')
        //                            ->first();

        // $affs = Affiliate::select(DB::raw('SUM((unit_price * d.quantity) * 0.5) as totalGetPV'))
        //                  ->join('agents as m', 'm.code', 'affiliates.affiliate_id')
        //                  ->join('transactions as t', 't.user_id', 'm.code')
        //                  ->join('transaction_details as d', 'd.transaction_id', 't.id')
        //                  ->where('t.status', '1')
        //                  ->where('affiliates.user_id', $buyerCode)
        //                  ->first();

        $transactions = Transaction::select(DB::raw('SUM(d.get_pv * d.quantity) as totalGetPV'))
                                   ->join('transaction_details as d', 'd.transaction_id', 'transactions.id')
                                   ->where('user_id', $buyerCode)
                                   ->where('transactions.status', '1')
                                   ->first();

        $affs = Affiliate::select(DB::raw('SUM(d.get_pv * d.quantity) as totalGetPV'))
                         ->join('agents as m', 'm.code', 'affiliates.affiliate_id')
                         ->join('transactions as t', 't.user_id', 'm.code')
                         ->join('transaction_details as d', 'd.transaction_id', 't.id')
                         ->where('t.status', '1')
                         ->where('affiliates.user_id', $buyerCode)
                         ->first();
        

        $downline_total_pv = $affs->totalGetPV;

        $affs_customers = Affiliate::select(DB::raw('SUM(d.get_pv * d.quantity) as totalGetPV'))
                                   ->join('agents as m', 'm.code', 'affiliates.affiliate_id')
                                   ->join('users as u', 'u.master_id', 'm.code')
                                   ->join('transactions as t', 't.user_id', 'u.code')
                                   ->join('transaction_details as d', 'd.transaction_id', 't.id')
                                   ->where('t.status', '1')
                                   ->where('affiliates.user_id', $buyerCode)
                                   ->first();
        $downline_customer_total_pv = $affs_customers->totalGetPV;
        // exit();

        $mb_transactions = Transaction::select(DB::raw('SUM(d.get_pv * d.quantity) as totalGetPV'))
                               ->join('transaction_details as d', 'd.transaction_id', 'transactions.id')
                               ->join('users as u', 'transactions.user_id', 'u.code')
                               ->where('u.master_id', $buyerCode)
                               ->where('transactions.status', '1')
                               ->first();

        $member_total_pv = $mb_transactions->totalGetPV;

        return number_format($transactions->totalGetPV + $downline_total_pv + $downline_customer_total_pv + $member_total_pv, 2);
    }

    public function LoadMonthlyPV()
    {
        if(!empty(Auth::guard('admin')->check())){
            $buyerCode = Auth::guard('admin')->user()->code;
        }elseif(!empty(Auth::guard('agent')->check())){
            $buyerCode = Auth::guard('agent')->user()->code;
        }elseif(!empty(Auth::guard('corporate')->check())){
            $buyerCode = Auth::guard('corporate')->user()->code;
        }elseif(!empty(Auth::guard('web')->check())){
            $buyerCode = Auth::guard('web')->user()->code;
        }else{
          if(empty($_COOKIE['new_guest'])){
            $buyerCode = setcookie('new_guest', strtotime(date('Y-m-d H:i:s')).rand(100000, 999999), time() + (86400 * 30), "/");
          }else{
            $buyerCode = $_COOKIE['new_guest'];
          }
        }

        $transactions = Transaction::select(DB::raw('SUM(unit_price * d.quantity) as totalSales'))
                                   ->join('transaction_details as d', 'd.transaction_id', 'transactions.id')
                                   ->where('user_id', $buyerCode)
                                   ->where('transactions.status', '1')
                                   ->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m")'), date('Y-m'))
                                   ->first();

        $affs = Affiliate::select(DB::raw('SUM(unit_price * d.quantity) as totalSales'))
                         ->join('agents as m', 'm.code', 'affiliates.affiliate_id')
                         ->join('transactions as t', 't.user_id', 'affiliates.affiliate_id')
                         ->join('transaction_details as d', 'd.transaction_id', 't.id')
                         ->where('t.status', '1')
                         ->where('affiliates.user_id', $buyerCode)
                         ->where(DB::raw('DATE_FORMAT(t.created_at, "%Y-%m")'), date('Y-m'))
                         ->first();
        

        $downline_total_sales = $affs->totalSales;

        $affs_customers = Affiliate::select(DB::raw('SUM(unit_price * d.quantity) as totalSales'))
                                   ->join('agents as m', 'm.code', 'affiliates.affiliate_id')
                                   ->join('users as u', 'u.master_id', 'm.code')
                                   ->join('transactions as t', 't.user_id', 'u.code')
                                   ->join('transaction_details as d', 'd.transaction_id', 't.id')
                                   ->where('t.status', '1')
                                   ->where('affiliates.user_id', $buyerCode)
                                   ->where(DB::raw('DATE_FORMAT(t.created_at, "%Y-%m")'), date('Y-m'))
                                   ->first();
        $downline_customer_total_sales = $affs_customers->totalSales;
        // exit();

        $mb_transactions = Transaction::select(DB::raw('SUM(unit_price * d.quantity) as totalSales'))
                               ->join('transaction_details as d', 'd.transaction_id', 'transactions.id')
                               ->join('users as u', 'transactions.user_id', 'u.code')
                               ->where('u.master_id', $buyerCode)
                               ->where('transactions.status', '1')
                               ->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m")'), date('Y-m'))
                               ->first();

        $member_total_sales = $mb_transactions->totalSales;


        return number_format(($transactions->totalSales ?? 0) + ($downline_total_sales ?? 0) + ($downline_customer_total_sales ?? 0) + ($member_total_sales ?? 0), 2);
    }

    public function getTeamBonusTier()
    {
        $TotalPVWallet = str_replace(',', '', $this->LoadTotalPV());

        $SettingTeamDividend = SettingTeamDividend::where('target_box', '<=', $TotalPVWallet)
                                                  ->orderBy('target_box', 'desc')
                                                  ->first();

        $get_next_level_team_bonus = "";
        if(!empty($SettingTeamDividend->id)){
            $get_next_level_team_bonus = SettingTeamDividend::where('target_box', '>=', $TotalPVWallet)
                                           ->orderBy('target_box', 'asc')
                                           ->first();
        }

        $TierValue = !empty($SettingTeamDividend->id) ? $SettingTeamDividend->amount : 0;

        $return_value = '<span class="wallet-balance-amount">
                            '.$TierValue.'%
                          </span>
                          <br>
                          <span class="wallet-desc profile-word">';
                          if($_COOKIE['global_language'] == '1'){
            $return_value .= '现团队级别';
                          }else{
            $return_value .= 'Current Team Bonus Tier';
                          }
            $return_value .= '</span>';
        if(!empty($get_next_level_team_bonus->id)){
            $leftPercentage = $TotalPVWallet / $get_next_level_team_bonus->target_box * 100;
            $return_value .= '<hr>
                              Next: '.($get_next_level_team_bonus->target_box - $TotalPVWallet).' PV more to hit '.$get_next_level_team_bonus->amount.'%
                              <div class="progress">
                                  <div class="progress-bar" role="progressbar" aria-valuenow="'.$leftPercentage.'" aria-valuemin="0" aria-valuemax="100" style="width:'.$leftPercentage.'%">
                                    <span class="sr-only">'.$leftPercentage.'% Complete</span>
                                  </div>
                              </div>';
        }

        return $return_value;
    }

    public function SubmitWithdrawalStock(Request $request)
    {
        try{
            \DB::beginTransaction();

            $website_setting = WebsiteSetting::find(1);

            $maximum_stock_quantity = !empty($website_setting->maximum_stock_quantity) ? $website_setting->maximum_stock_quantity : 0;

            $shipping_default = (isset($request->df_ad_id)) ? $request->df_ad_id : 0;

            $self_pickup = isset($request->self_pickup) ? 1 : 0;

            $get_shipping = UserShippingAddress::find($shipping_default);

            $input = new WithdrawalStock();
            $input->transaction_no = $this->GenerateProductTransactionNo();
            $input->user_id = Auth::user()->code;
            if(!empty($request->file('bank_slip'))){
                $files = $request->file('bank_slip'); 
                $name = $files->getClientOriginalName();
                $exp = explode(".", $name);
                $file_ext = end($exp);
                $name = md5($name.date('Y-m-d H:i:s')).'.'.$file_ext;
                $files->move(GlobalController::get_image_path("uploads/bank_slip/".Auth::user()->code."/"), $name);

                $input->bank_slip = "uploads/bank_slip/".Auth::user()->code."/".$name;
            }
            $input->shipping_fee = $request->shipping_fee;
            $input->address_name = $get_shipping->f_name;
            $input->country_code = $get_shipping->country_code;
            $input->phone = $get_shipping->phone;
            $input->address = $get_shipping->address;
            $input->country = $get_shipping->country;
            $input->state = $get_shipping->state;
            $input->email = $get_shipping->email;
            $input->postcode = $get_shipping->postcode;
            $input->city = $get_shipping->city;
            $input->remark = $request->remark;
            $input->status = '99';

            if($self_pickup == 1){
                $input->cod_address = $request->cod_address;
            }
            $input->save();
            $total_weight = 0;
            foreach(json_decode(stripslashes($request->arr)) as $array_products){
                $ProductBalanceLeft = GlobalController::get_own_store_stock_balance(Auth::user()->code, $array_products[0], $array_products[1], $array_products[2]);
                // echo $array_products[3].' - '.$ProductBalanceLeft;
                // echo "<br>";

                if($maximum_stock_quantity > 0){
                    $check_withdrawal_stock_control = GlobalController::check_withdrawal_stock_control(Auth::user()->code, $array_products[0], $array_products[1], $array_products[2]);
                    $total_withdrawal_quantity = $check_withdrawal_stock_control + $array_products[3];

                    if($total_withdrawal_quantity > $maximum_stock_quantity){
                        throw new \Exception('Limited Only '.$maximum_stock_quantity.' boxes can be withdrawal per month.');
                    }
                }

                if($array_products[3] > $ProductBalanceLeft){
                    Toastr::error('Insufficient Balance');
                    return redirect()->route('my_stock');
                }

                $input_detail = new WithdrawalStockDetail();
                $input_detail->withdrawal_stock_id = $input->id;
                $input_detail->product_id = $array_products[0];
                $input_detail->variation_id = $array_products[1];
                $input_detail->second_variation_id = $array_products[2];
                $input_detail->quantity = $array_products[3];
                $input_detail->unit_weight = $array_products[4];
                $input_detail->save();

                $total_weight += $array_products[4];
            }

            $input->weight = $total_weight;
            $input->save();
            
            \DB::commit();

        } catch (\Exception $e){
            \DB::rollback();
            return Redirect::back()->withInput($request->all())->withErrors($e->getMessage());
        } catch (\Error $e){
            \DB::rollback();
            return Redirect::back()->withInput($request->all())->withErrors($e->getMessage());
        }

        Toastr::success('Product Withdrawal Submited. Please Wait Admin For Approval');
        return redirect()->route('my_stock');
    }

    public static function GenerateProductTransactionNo()
    {
        $year = date('Y');
        $month = date('m');
        $day = date('d');
        $hour = date('H');
        $minute = date('i');
        $combine = $year.$month.$day.$hour.$minute;
        $transaction = WithdrawalStock::select(DB::raw('COUNT(id) AS TotalTransaction'))
                                      ->first();
        $TotalTransaction = $transaction->TotalTransaction + 1;
        if(strlen($TotalTransaction) == 1){
            $tNo = "TP".$combine."0000".$TotalTransaction;
        }elseif(strlen($TotalTransaction) == 2){
            $tNo = "TP".$combine."000".$TotalTransaction;
        }elseif(strlen($TotalTransaction) == 3){
            $tNo = "TP".$combine."00".$TotalTransaction;
        }elseif(strlen($TotalTransaction) == 4){
            $tNo = "TP".$combine."0".$TotalTransaction;
        }else{
            $tNo = "TP".$combine.$TotalTransaction;
        }
        return $tNo;
    }

    public function getFlashSaleDetail(Request $request)
    {
        $active_flash_sale = GlobalController::get_current_flash_sales();

        if(!empty($active_flash_sale)){
            $get_flash_sale_product = GlobalController::get_current_flash_sale_product_detail($request->product_id, $request->variation_id, $request->second_variation_id);

            if(!empty($get_flash_sale_product) && !empty($get_flash_sale_product->qty)){
                return array('1', $get_flash_sale_product->qty);
            }
        }

        return array('0');
    }

    public function ProductBalanceLeft($code, $pid, $vid, $svid)
    {
        $my_stocks = Transaction::select(DB::raw('SUM(d.quantity) as totalStock'))
                               ->join('transaction_details as d', 'd.transaction_id', 'transactions.id')
                               ->join('products as p', 'p.id', 'd.product_id')
                               ->leftJoin('product_variations as v', 'v.id', 'd.variation_id')
                               ->leftJoin('product_second_variations as sv', 'sv.id', 'd.second_variation_id')
                               ->where('transactions.status', '1')
                               ->where('transactions.store_stock', '1')
                               ->where('transactions.user_id', $code)
                               ->where('d.product_id', $pid);
        if(!empty($vid)){
        $my_stocks = $my_stocks->where('d.variation_id', $vid);
        }
        if(!empty($svid)){
        $my_stocks = $my_stocks->where('d.second_variation_id', $svid);
        }
        $my_stocks = $my_stocks->first();

        $withdrawal_stock = WithdrawalStock::select(DB::raw('SUM(quantity) as totalStock'))
                                           ->where('product_id', $pid)
                                           ->where('user_id', $code)
                                           ->whereIn('status', ['1', '99']);

        if(!empty($vid)){
        $withdrawal_stock = $withdrawal_stock->where('variation_id', $vid);
        }
        if(!empty($svid)){
        $withdrawal_stock = $withdrawal_stock->where('second_variation_id', $svid);
        }
        $withdrawal_stock = $withdrawal_stock->first();

        return $my_stocks->totalStock - $withdrawal_stock->totalStock;
    }

    public function get_shipping_fee(Request $request)
    {
        if(!empty($request->sid)){
            $selected_shipping_address = UserShippingAddress::find($request->sid);

            $state = $selected_shipping_address->state;
        }else{
            $state = $request->state;
        }

        $total_weight = $request->shipping_weight;

        $shipping_fee = 0;
        $shipping_fee = GlobalController::get_shipping_fee($state, $total_weight, $request->country);

        return $shipping_fee;
    }

    public function add_share_cart_link()
    {
        try{
            \DB::beginTransaction();

            $getCurrentLogin = GlobalController::getCurrentLogin();
            $buyerCode = $getCurrentLogin['code'];
            $buyerLvl = $getCurrentLogin['lvl'];

            $carts = Cart::where('user_id', $buyerCode)
                         ->whereNull('mall')
                         ->get();

            $get_cart_details = GlobalController::get_cart_details($buyerCode, $buyerLvl);

            $cart_link = new CartLink();
            $cart_link->user_id = $buyerCode;
            $cart_link->qty = 1;
            $cart_link->price = $get_cart_details['sub_total'];
            // $cart_link->price = $get_cart_details['grand_total'];
            $cart_link->save();

            $update_cart_link_unique = CartLink::find($cart_link->id);
            $update_cart_link_unique->unique_id = route('checkout', 'cl='.md5($cart_link->id));
            $update_cart_link_unique->save();

            foreach($carts as $cart){
                $cart_link_product = new CartLinkProductDetail();
                $cart_link_product->cart_link_id = $cart_link->id;
                $cart_link_product->product_id = $cart->product_id;
                $cart_link_product->variation_id = $cart->sub_category_id;
                $cart_link_product->second_variation_id = $cart->second_sub_category_id;
                $cart_link_product->qty = $cart->qty;
                $cart_link_product->main_add_on = $cart->main_add_on;
                $cart_link_product->add_on_id = $cart->add_on_id;
                $cart_link_product->mall = $cart->mall;
                $cart_link_product->promo = $cart->promo;
                $cart_link_product->promo_price_id = $cart->promo_price_id;
                $cart_link_product->save();
            }

            \DB::commit();
        }catch (\Exception $e){
            \DB::rollback();
            return array('message'=>$e->getMessage(),
                         'unique_id'=>"");
        }catch(\Error $e){
            \DB::rollback();
            return array('message'=>$e->getMessage(),
                         'unique_id'=>"");
        }

        return array('message'=>"ok",
                     'unique_id'=>$update_cart_link_unique->unique_id);
    }

    public function SetWithdrawalType(Request $request)
    {
        try {
            \DB::beginTransaction();

            if (Auth::guard('agent')->check()) {
                $current_agent = Agent::where('code', Auth::guard('agent')->user()->code)->first();

                $current_agent->withdrawal_type = $request->withdrawal_type;
                $current_agent->save();

            } else {
                throw new \Exception('Withdrawal Type Option Only Available To Agents');
            }

            \DB::commit();

            Toastr::success('Updated Successfully');
            return "ok";
        } catch (\Exception $e){
            \DB::rollback();
            Toastr::error($e->getMessage());
            return $e->getMessage();
        } catch (\Error $e){
            \DB::rollback();
            Toastr::error($e->getMessage());
            return $e->getMessage();
        }
    }

    public function getVariationimage(Request $request)
    {
        $variation = ProductVariation::find($request->vid);
        $variation_img = "";

        if(!empty($variation->id)){
            if(!empty($variation->variation_image)){
                $variation_img = asset($variation->variation_image);
            }
        }

        return $variation_img;
    }
}
