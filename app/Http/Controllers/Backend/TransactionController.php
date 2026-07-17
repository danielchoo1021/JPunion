<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// use Illuminate\Support\Facades\Input;
use Validator, Redirect, Toastr, DB, File, Auth, DateTime, Arr;

use App\Transaction;
use App\WithdrawalTransaction;
use App\TransactionDetail;
use App\User;
use App\Merchant;
use App\Agent;
use App\Admin;
use App\AffiliateCommission;
use App\Bank;
use App\TopupTransaction;
use App\Affiliate;
use App\TransactionTracking;
use App\State;
use App\PickupContact;
use App\SettingPickUpAddress;
use App\TransactionBillingAddress;
use App\CodAddress;
use App\Product;
use App\TransactionQrPayment;
use App\JoiningRecord;
use App\TblCountry;
use App\WithdrawalStock;
use App\UserShippingAddress;
use App\AgentPrice;
use App\AppliedPromotion;
use App\SettingShippingFee;
use App\ProductImage;
use App\SettingMerchantRebate;
use App\Corporate;
use App\ProductVariation;
use App\TransactionPackage;
use App\SettingPackageRebate;
use App\ProductSecondVariation;

use App\Exports\WithdrawalExport;
use App\Exports\TransactionExport;
use App\Exports\TopupListExport;
use Maatwebsite\Excel\Facades\Excel;

use App\Http\Controllers\GlobalController;
use App\SettingEinvoice;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if (!empty(request('dates'))) {
            $new_dates = explode('-', request('dates'));
            $start = DateTime::createFromFormat('d/m/Y', trim($new_dates[0]))->format('Y-m-d');
            $end = DateTime::createFromFormat('d/m/Y', trim($new_dates[1]))->format('Y-m-d');

            $startDate = trim($new_dates[0]);
            $endDate = trim($new_dates[1]);
        } else {
            $ds = new DateTime("first day of this month");
            $de = new DateTime("last day of this month");

            $start = $ds->format('Y-m-d');
            $end = $de->format('Y-m-d');

            $startDate = $ds->format('d/m/Y');
            $endDate = $de->format('d/m/Y');
        }
        $leftJoin = DB::raw("(SELECT * FROM agents WHERE status = '1') AS i");
        $leftJoin2 = DB::raw("(SELECT * FROM admins) AS x");

        $transactions = Transaction::select('transactions.*', 
                                            DB::raw('COALESCE(COALESCE(ag.f_name, u.f_name), a.f_name) as customer_name'), 
                                            DB::raw('COALESCE(COALESCE(ag.code, u.code), a.code) as customer_code'), 'te.status as te_status', 'te.einvoice_uuid as te_einvoice')
                                   ->join('transaction_details as d', 'd.transaction_id', 'transactions.id')
                                   ->leftJoin('agents as ag', 'ag.code', 'transactions.user_id')
                                   ->leftJoin('users as u', 'u.code', 'transactions.user_id')
                                   ->leftJoin('admins as a', 'a.code', 'transactions.user_id')
                                   ->leftJoin('transaction_einvoices as te','te.transaction_no', 'transactions.transaction_no')
                                   ->where('transactions.status', '!=', '55');
        if(Auth::guard('merchant')->check()){
            $transactions = $transactions->where('merchant_id', Auth::user()->code);
        }
        if(!empty(request('mall'))){
            $transactions = $transactions->whereNotNull('pv_purchase');
        }else{
            $transactions = $transactions->whereNull('pv_purchase');
        }

        if(empty(request('this_year'))){
        $transactions = $transactions->whereBetween(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), array($start, $end));
        }
        $transactions = $transactions->groupBy('transactions.id');

        $per_page = 10;
        if(!empty(request('per_page'))){
            $per_page = request('per_page');
        }

        $queries = [];
        $columns = [
            'dates', 
            'transaction_no', 
            'buyer_name', 
            'buyer_code', 
            'buyer_code', 
            'delivery_type',
            'status', 
            'desc_sort',
            'asc_sort',
            'trans_desc',
            'trans_asc',
            'name_desc',
            'name_asc',
            'grand_total_desc',
            'grand_total_asc',
            'status_desc',
            'status_asc',
            'pickup_desc',
            'pickup_asc',
            'payment',
            'per_page'
        ];

        foreach($columns as $column){
            if(request()->has($column) && !empty(request($column))){
                if($column == 'status'){
                    if(request($column) != '2'){
                        if(request($column) == '3'){
                            $transactions = $transactions->where('transactions.completed', '1');
                        }elseif(request($column) == '1'){
                             $transactions = $transactions->where("transactions.status", '1')->whereNull('completed')->whereNull('to_receive');
                        }else{
                            $transactions = $transactions->where("transactions.status", 'like', "%".request($column)."%");
                        }
                    }else{
                        $transactions = $transactions->where('to_receive', '1')
                                                     ->whereNull('completed');
                    }
                }elseif($column == 'dates'){
                    $transactions = $transactions->whereBetween(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), array($start, $end));
                }elseif($column == 'buyer_name'){
                    $transactions = $transactions->where(function($query) use($column){
                                      $query->where(DB::raw('COALESCE(COALESCE(ag.f_name, u.f_name), a.f_name)'), 'like', '%'.request($column).'%')
                                            ->orWhere('address_name', 'like', '%'.request($column).'%');
                                    });
                }elseif($column == 'buyer_code'){
                    $transactions = $transactions->where(DB::raw('COALESCE(COALESCE(ag.code, u.code), a.code)'), 'like', '%'.request($column).'%');
                }elseif($column == 'delivery_type'){
                    // if(request($column) == '1'){
                    //     $transactions = $transactions->whereIn('transactions.on_hold', ['99', '1']);
                    // }else{
                    //     $transactions = $transactions->whereNull('transactions.on_hold');
                    // }

                    if(request($column) == '1'){
                        $transactions = $transactions->where(function($query) {
                            $query->where(function($q) {
                                $q->where('transactions.self_pick', 1)
                                ->whereNull('transactions.completed');
                            })->orWhere(function($q) {
                                $q->whereNotNull('transactions.payment_method')
                                ->whereNull('transactions.completed');
                            });
                        });
                    }elseif(request($column) == '2'){
                        $transactions = $transactions->where(function($query) {
                            $query->where(function($q) {
                                $q->where('transactions.self_pick', 1)
                                ->where('transactions.completed', 1);
                            })->orWhere(function($q) {
                                $q->whereNotNull('transactions.payment_method')
                                ->where('transactions.completed', 1);
                            });
                        });
                    }elseif(request($column) == '3'){
                       $transactions = $transactions->whereNull('transactions.self_pick')->whereNull('transactions.payment_method');
                    }
                    // elseif(request($column) == '4'){
                       
                    // }
                }elseif($column == 'desc_sort'){
                    $transactions = $transactions->orderBy('transactions.created_at', 'desc');
                }elseif($column == 'asc_sort'){
                    $transactions = $transactions->orderBy('transactions.created_at', 'asc');
                }elseif($column == 'trans_desc'){
                    $transactions = $transactions->orderBy('transactions.transaction_no', 'desc');
                }elseif($column == 'trans_asc'){
                    $transactions = $transactions->orderBy('transactions.transaction_no', 'asc');
                }elseif($column == 'name_desc'){
                    $transactions = $transactions->orderBy('transactions.address_name', 'desc');
                }elseif($column == 'name_asc'){
                    $transactions = $transactions->orderBy('transactions.address_name', 'asc');
                }elseif($column == 'grand_total_desc'){
                    $transactions = $transactions->orderBy('transactions.grand_total', 'desc');
                }elseif($column == 'grand_total_asc'){
                    $transactions = $transactions->orderBy('transactions.grand_total', 'asc');
                }elseif($column == 'status_desc'){
                    if(!empty('transactions.to_receive')){
                        $transactions = $transactions->orderBy('transactions.to_receive', 'desc');
                    }elseif(!empty('transactions.completed')){
                        $transactions = $transactions->orderBy('transactions.complete', 'desc');
                    }else{
                        $transactions = $transactions->orderBy('transactions.status', 'desc');
                    }
                }elseif($column == 'status_asc'){
                    if(!empty('transactions.to_receive')){
                        $transactions = $transactions->orderBy('transactions.to_receive', 'asc');
                    }elseif(!empty('transactions.completed')){
                        $transactions = $transactions->orderBy('transactions.complete', 'asc');
                    }else{
                        $transactions = $transactions->orderBy('transactions.status', 'asc');
                    }
                }elseif($column == 'pickup_desc'){
                    $transactions = $transactions->orderBy('transactions.delivery_type', 'desc');
                }elseif($column == 'pickup_asc'){
                    $transactions = $transactions->orderBy('transactions.delivery_type', 'asc');
                }elseif($column == 'per_page'){
                    $transactions = $transactions->paginate($per_page);
                }elseif($column == 'payment'){
                    if(request($column) == 1){
                       $transactions = $transactions->whereNull('transactions.mall')->whereNotNull('transactions.bank_slip');
                    }elseif(request($column) == 2){
                       $transactions = $transactions->where('transactions.mall',1);
                    }elseif(request($column) == 3){
                       $transactions = $transactions->where('transactions.mall',2);
                    }elseif(request($column) == 4){
                       $transactions = $transactions->whereNull('transactions.mall')->whereNull('transactions.bank_slip')->whereNull('transactions.payment_method');
                    }elseif(request($column) == 5){
                       $transactions = $transactions->whereNull('transactions.mall')->whereNull('transactions.bank_slip')->where('transactions.payment_method',1);
                    }elseif(request($column) == 6){
                       $transactions = $transactions->whereNull('transactions.mall')->whereNull('transactions.bank_slip')->where('transactions.payment_method',2);
                    }elseif(request($column) == 7){
                       $transactions = $transactions->whereNull('transactions.mall')->whereNull('transactions.bank_slip')->where('transactions.payment_method',3);
                    }elseif(request($column) == 8){
                       $transactions = $transactions->whereNull('transactions.mall')->whereNotNull('transactions.bank_slip')->where('transactions.created_backend',1);
                    }elseif(request($column) == 9){
                       $transactions = $transactions->whereNull('transactions.mall')->whereNull('transactions.bank_slip')->whereNull('transactions.payment_method')->where('transactions.created_backend',1);
                    }
                }elseif($column == 'transaction_no'){
                        $transactions = $transactions->where('transactions.transaction_no', 'like', "%".request($column)."%");
                }else{
                    $transactions = $transactions->where($column, 'like', "%".request($column)."%");
                }

                $queries[$column] = request($column);
            }else{
              $transactions = $transactions->orderBy('created_at', 'desc');
            }
            
        }
        // echo $transactions = $transactions->toSql();
        // exit();

        if(!empty(request('per_page'))){ 
            $transactions = $transactions->appends($queries);        
        }else{
            $transactions = $transactions->paginate($per_page)->appends($queries);        
        }
        // $transactions = $transactions->paginate($per_page)->appends($queries);

        $ship_details = [];
        $print_ship_details = [];
        $transaction_total_weight = [];
        $promoWeight = 0;
        foreach($transactions as $transaction){
          $TransactionTrackings = TransactionTracking::where('transaction_id', $transaction->Tid)->get();

          foreach($TransactionTrackings as $TransactionTracking){

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
              if(!empty($json->result)){
                foreach($json->result as $value){
                    foreach($value->parcel as $value2){
                        $ship_details[$transaction->Tid][$TransactionTracking->row_id] = $value2->ship_status;
                        $print_ship_details[$transaction->Tid][$TransactionTracking->row_id] = $value2->awb_id_link;
                    }
                }              
              }
          }
          
        }

        // exit();


        $netTransaction = Transaction::select(DB::raw('SUM(transactions.grand_total) AS netTotal'))
                                     ->where('status', '1');

                                      if(!empty(request('mall'))){
                                          $netTransaction = $netTransaction->whereNotNull('pv_purchase');
                                      }else{
                                          $netTransaction = $netTransaction->whereNull('pv_purchase');
                                      }

                                     $netTransaction = $netTransaction->first();

        $yearlySales = Transaction::select(DB::raw('SUM(transactions.grand_total) as yearlySales'))
                                  ->where('status', '1');

                                  if(!empty(request('mall'))){
                                      $yearlySales = $yearlySales->whereNotNull('pv_purchase');
                                  }else{
                                      $yearlySales = $yearlySales->whereNull('pv_purchase');
                                  }

                                  $yearlySales = $yearlySales->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y")'), date('Y'))
                                  ->first();

        $monthlySales = Transaction::select(DB::raw('SUM(transactions.grand_total) as monthlySales'))
                                  ->where('status', '1');

                                  if(!empty(request('mall'))){
                                      $monthlySales = $monthlySales->whereNotNull('pv_purchase');
                                  }else{
                                      $monthlySales = $monthlySales->whereNull('pv_purchase');
                                  }

                                  $monthlySales = $monthlySales->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m")'), date('Y-m'))
                                  ->first();

        $dailySales = Transaction::select(DB::raw('SUM(transactions.grand_total) as dailySales'))
                                  ->where('status', '1');

                                  if(!empty(request('mall'))){
                                      $dailySales = $dailySales->whereNotNull('pv_purchase');
                                  }else{
                                      $dailySales = $dailySales->whereNull('pv_purchase');
                                  }

                                  $dailySales = $dailySales->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), date('Y-m-d'))
                                  ->first();


        // $affs = Affiliate::select(DB::raw('coalesce(m.agent_lvl, a.agent_lvl) as upline_lvl'),
        //                                   DB::raw('coalesce(m.partner_lvl, a.partner_lvl) as upline_partner_lvl'), 
        //                                   'affiliates.user_id', 'affiliates.sort_level')
        //                          ->leftJoin('merchants as m', 'm.code', 'affiliates.user_id')
        //                          ->leftJoin('admins as a', 'a.code', 'affiliates.user_id')
        //                          ->where('affiliate_id', "M000004")
        //                          ->where(DB::raw('coalesce(m.status, a.status)'), '1')
        //                          ->whereNotNull('m.agent_lvl')
        //                          ->where('sort_level', '<=', '3')
        //                          ->orderBy('sort_level', 'asc')
        //                          ->get();

        // foreach($affs as $aff){
        //     echo $aff->user_id;
        // }
        // echo GlobalController::purchase_from_customer_deduct_stock_commission("20230904180200002");
        // exit();

        $settingEinvoice = SettingEinvoice::where('status', 1)->first();
        return view('backend.transactions.new_index', ['transactions'=>$transactions, 'netTransaction'=>$netTransaction,
                                                        'startDate'=>$startDate, 'endDate'=>$endDate,
                                                        'yearlySales'=>$yearlySales, 'monthlySales'=>$monthlySales, 
                                                        'dailySales'=>$dailySales,   
                                                        'mall'=>request('mall')],
                                                        compact('ship_details', 'print_ship_details', 'settingEinvoice'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $merchants = Agent::where('status', '1');
        if(Auth::guard('merchant')->check()){
            $merchants = $merchants->where('dual_master_id', Auth::user()->code);
        }
        $merchants = $merchants->get();

        $users = User::where('status', '1');
        if(Auth::guard('merchant')->check()){
            $users = $users->where('dual_master_id', Auth::user()->code);
        }
        $users = $users->get();

        $merchants = $merchants->concat($users);

        $merchants = Arr::sort($merchants, function ($value) {
            return $value['code'];
        });

        $products = Product::where('status', '1')
                           ->whereNull('mall');
        if(Auth::guard('merchant')->check()){
            $products = $products->where('merchant_id', Auth::user()->code);
        }
        $products = $products->get();


        $state = State::all();
        $countries = GlobalController::global_countries();

        $old_variations = [];
        $old_second_variations = [];
        if (!empty(old('product_id'))) {
            foreach (old('product_id') as $old_product_id_key => $old_product_id) {
                $current_product = Product::find($old_product_id);

                if (!empty($current_product->id)) {
                    if ($current_product->variation_enable == 1) {
                        $old_variations[$current_product->id] = ProductVariation::where('product_id', $current_product->id)
                                                                                ->get();

                        if ($current_product->second_variation_enable == 1) {
                            foreach ($old_variations[$current_product->id] as $old_variation_key => $old_variation) {
                                $old_second_variations[$old_variation->id] = ProductSecondVariation::where('variation_id', $old_variation->id)
                                                                                                   ->get();
                            }
                        }
                    }
                }
            }
        }

        return view('backend.transactions.create', ['merchants'=>$merchants, 'products'=>$products, 'state'=>$state, 'countries'=>$countries], compact('old_variations', 'old_second_variations'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $translation_data = GlobalController::get_translations();

        try {
            $merchant = Agent::where('code',$request->merchants)->where('status','1')->first();

            if(empty($merchant->id)){
                $merchant = User::where('code',$request->merchants)->where('status','1')->first();
                if(empty($merchant->id)){
                    $merchant = Admin::where('code', $request->merchants)->where('status','1')->first();
                }
            }

            if(!empty($merchant->id) && !empty($request->default_shipping_option) && $request->default_shipping_option == 1){
                $shipping_address = UserShippingAddress::where('user_id',$merchant->code)->where('default','1')->first();
                if(!empty($shipping_address)){
                    $f_name = $shipping_address->f_name;
                    $email = $shipping_address->email;
                    $country_code = $shipping_address->country_code;
                    $phone = $shipping_address->phone;
                    $country = $shipping_address->country;
                    $address = $shipping_address->address;
                    $state = $shipping_address->state;
                    $city = $shipping_address->city;
                    $postcode = $shipping_address->postcode;
                }else{
                    throw new \Exception("Address Not Found! Please Create New Address");
                }
            }else{

                $validator = Validator::make($request->all(), [
                  'f_name' => 'required',
                  'email' => 'required',
                  'country_code' => 'required',
                  'phone' => 'required',
                  'address' => 'required',
                  'city' => 'required',
        
                ]);

                $errorMsg = $translation_data['backendlang']['backendlang']['Please ensure every details in address is filled correctly.'] ?? 'Please ensure every details in address is filled correctly.';

                if ($validator->fails()) {
                    return Redirect::back()->withInput($request->all())->withErrors($errorMsg);
                }

                $get_state = '';

                $f_name = $request->f_name;
                $email = $request->email;
                $country_code = $request->country_code;
                $phone = $request->phone;
                $country = $request->country;
                if(!empty($country) && $country == 160){
                    $get_state = $request->state;
                }else if(!empty($country) && $country != 160){
                    $get_state = $request->other_state;
                }
                if(empty($get_state) && $country == 160){
                    $errorMsg = $translation_data['backendlang']['backendlang']['State Not Defined'] ?? 'State Not Defined';
                    throw new \Exception($errorMsg);
                }
                $address = $request->address;
                $state = $get_state;
                $city = $request->city;
                $postcode = $request->postcode;
            }

            if(empty($request->product_id[0])){
                $errorMsg = $translation_data['backendlang']['backendlang']['Please Select A Product'] ?? 'Please Select A Product';
                throw new \Exception($errorMsg);
            }  

            // if(!isset($request->bank_slip)){
            //     throw new \Exception("Please Upload Bank Slip");
            // }

            $arr = [];
            $total_weight = 0;
            $total_shipping_weight = 0;
            $weight = 0;
            $sub_total = 0;
            $unit_price = 0;
            $grand_total = 0;
            $west_additional_shipping_fee = 0;
            $east_additional_shipping_fee = 0;
            $totalshipping_fees = 0;
            $shipping_fee = 0;
            $get_pv = 0;
            $get_total_pv = 0;
            foreach($request->product_id as $key => $a)
            {
                if(!empty($request->product_id[$key])){
                    $product = Product::where('id',$request->product_id[$key])->first();
                    if (!empty($product->second_variation_enable) && !empty($product->second_variation_enable)) {
                        if (!empty($request->hidden_second_variation_id[$key])) {
                            $second_variation = ProductSecondVariation::find($request->hidden_second_variation_id[$key]);
                            if (!empty($second_variation->id)) {
                                $weight = $second_variation->variation_weight * $request->quantity[$key];
                                $total_weight += $weight;
                    
                                if(!empty($merchant->id)){
                                    $agentPrice  = AgentPrice::where('product_id',$request->product_id[$key])
                                                             ->where('variation_id',$request->hidden_variation_id[$key])
                                                             ->where('second_variation_id', $request->hidden_second_variation_id[$key])
                                                             ->where('agent_lvl_id',$merchant->lvl)
                                                             ->where('status','1')
                                                             ->first();

                                    $unit_price = $request->pricing[$key];
                                    $unit_price = $unit_price * $request->quantity[$key];
                                    $sub_total += $unit_price;
                                }
                            }
                        } else {
                            throw new \Exception($translation_data['backendlang']['backendlang']['Please Select Second Variation'] ?? 'Please Select Second Variation');
                        }
                    }elseif(!empty($product->variation_enable) && empty($product->second_variation_enable)){
                        if(!empty($request->hidden_variation_id[$key]))
                        {
                          $variation = ProductVariation::find($request->hidden_variation_id[$key]);
                          if(!empty($variation->id)){
                            $weight = $variation->variation_weight * $request->quantity[$key];
                            $total_weight += $weight;
                
                            if(!empty($merchant->id)){
                                $agentPrice  = AgentPrice::where('product_id',$request->product_id[$key])->where('variation_id',$request->hidden_variation_id[$key])->where('agent_lvl_id',$merchant->lvl)->where('status','1')->first();
                                $unit_price = $request->pricing[$key];
                                $unit_price = $unit_price * $request->quantity[$key];
                                $sub_total += $unit_price;
                            }
                          }
                        } else {
                            throw new \Exception($translation_data['backendlang']['backendlang']['Please Select Variation'] ?? 'Please Select Variation');
                        }
                    }else{
                      $weight = $product->weight * $request->quantity[$key];
                      $total_weight += $weight;
          
                      if(!empty($merchant->id)){
                          if(!empty($merchant->lvl)){
                              $agentPrice  = AgentPrice::where('product_id',$request->product_id[$key])->where('agent_lvl_id',$merchant->lvl)->where('status','1')->first();
                              $unit_price = $request->pricing[$key];
                              $unit_price = $unit_price * $request->quantity[$key];
                              $sub_total += $unit_price;                            
                          }else{
                              $unit_price = $request->pricing[$key];
                              $unit_price = $unit_price * $request->quantity[$key];
                              $sub_total += $unit_price;
                          }
                      }
                    }

                    if($country == '160'){
                        if($state != '11' && $state != '12' && $state != '15'){
                            if($product->free_shipping != '1'){
                                $total_shipping_weight += $weight;
                            }
                        }else{
                            if($product->free_east_shipping != '1'){
                                $total_shipping_weight += $weight;
                            }
                        }
                    }else{
                        if($product->free_singapore_shipping != '1' && $country != '200'){
                            $total_shipping_weight += $weight;
                        }
                    }
                }
                // if(!empty($address) && !empty($state)){
                //   if($state > 16){
                //       $shipping_fees = SettingShippingFee::where('area', 'sg')
                //                                         ->where('weight', '<=', ceil($total_weight))
                //                                         ->orderBy('weight', 'desc')
                //                                         ->first();
                //       if(!empty($shipping_fees->id)){
                //           $totalshipping_fees = $shipping_fees->shipping_fee;
                //       }
                    
                //   }elseif($state != '11' && $state != '12' && $state != '15'){
                 
                //       $shipping_fees = SettingShippingFee::where('area', 'west')
                //                                         ->where('weight', '<=', ceil($total_weight))
                //                                         ->orderBy('weight', 'desc')
                //                                         ->first();
                //       if(!empty($shipping_fees->id)){
                //           $totalshipping_fees = $shipping_fees->shipping_fee;
                //       }
                  
                //   }else{
                //       $shipping_fees = SettingShippingFee::where('area', 'east')
                //                                         ->where('weight', '<=', ceil($total_weight))
                //                                         ->orderBy('weight', 'desc')
                //                                         ->first();
                //       if(!empty($shipping_fees->id)){
                //           $totalshipping_fees = $shipping_fees->shipping_fee;                
                //       }
                      
                //   }
                // }

                if($country == 160){
                  if($state != '11' && $state != '12' && $state != '15'){
                      $shipping_fees = SettingShippingFee::where('area', 'west')
                                                         ->where('weight', '<=', ceil($total_shipping_weight))
                                                         ->orderBy('weight', 'desc')
                                                         ->first();
                      if(!empty($shipping_fees->id)){
                          $totalshipping_fees = $shipping_fees->shipping_fee;
                      }
                  }else{
                      $shipping_fees = SettingShippingFee::where('area', 'east')
                                                         ->where('weight', '<=', ceil($total_shipping_weight))
                                                         ->orderBy('weight', 'desc')
                                                         ->first();
                      if(!empty($shipping_fees->id)){
                          $totalshipping_fees = $shipping_fees->shipping_fee;
                      }
                  }
              }else{
                  $shipping_fees = SettingShippingFee::where('country_id', $country)
                                                     ->where('weight', '<=', ceil($total_shipping_weight))
                                                     ->orderBy('weight', 'desc')
                                                     ->first();
                  if(!empty($shipping_fees->id)){
                      $totalshipping_fees = $shipping_fees->shipping_fee;
                  }
              }

              if($request->type == 2){
                $shipping_fee = $totalshipping_fees;
              }else{
                $shipping_fee = $request->shipping_fee;
              }

                $grand_total = $sub_total + $shipping_fee;
    
                  //  $arr[$product->id] = [$product->product_name,$request->quantity[$key],$weight,$unit_price,$totalshipping_fees];
            }

            $discount = !empty($request->discount) ? $request->discount : 0;

            if(isset($request->point_transaction)){
                $GetPVWallet = GlobalController::get_point_wallet($merchant->code);

                $totalAmount = $grand_total - $discount;
      
                if($totalAmount > $GetPVWallet){
                    $errorMsg = $translation_data['backendlang']['backendlang']['Insufficient Point Balance'] ?? 'Insufficient Point Balance';
                    throw new \Exception($errorMsg);
                }
            }

            DB::beginTransaction();

            $transaction = new Transaction;
            $transaction->user_id = $merchant->code;
            $transaction->transaction_no = GlobalController::GenerateTransactionNo();
            $transaction->weight = $total_weight;
            $transaction->sub_total = $sub_total;
            $transaction->shipping_fee = $request->shipping_fee;
            $transaction->discount = $request->discount;
            $transaction->grand_total = $grand_total - $discount;

            $transaction->pv_purchase = isset($request->point_transaction)? 1 : null;
            // address details

            $transaction->address_name = $f_name;
            $transaction->address = $address;
            $transaction->postcode = $postcode;
            $transaction->city = $city;
            $transaction->state = $state;
            $transaction->country = $country;
            $transaction->phone = $phone;
            $transaction->created_backend = 1;
            $transaction->email = $email;
            $transaction->remark = $request->remark;

            if(!empty($request->hasFile('bank_slip'))){
                $file = $request->file('bank_slip');
                $name = 'uploads/bank_slip/'.$merchant->code.'/'.md5($file->getClientOriginalName()).'.'.$file->getClientOriginalExtension();
                $move = $file->move(GlobalController::get_image_path('uploads/bank_slip/'.$merchant->code.'/'),$name);
                $transaction->bank_slip = $name;
            }else{
                // throw new \Exception('Please upload bank slip');
            }
            $transaction->status = '1';

            // throw new \Exception('Testing');

            $transaction->save();

            foreach($request->product_id as $key => $a)
            {
                $product = Product::where('id',$request->product_id[$key])->first();
                $product_image = ProductImage::where('product_id',$request->product_id[$key])->first();
                $variation_name = NULL;
                $second_variation_name = NULL;
                if (!empty($product->second_variation_enable) && !empty($product->second_variation_enable)) {
                    
                    $second_variations = ProductSecondVariation::find($request->hidden_second_variation_id[$key]);
                    if (!empty($second_variations->id)) {

                        $get_pv = $second_variations->variation_get_pv;
                        $weight = $second_variations->variation_weight;
                        $second_variation_name = $second_variations->variation_name;
                    }
                }elseif(!empty($product->variation_enable) && empty($product->second_variation_enable)){
              
                    $variations = ProductVariation::find($request->hidden_variation_id[$key]);
                    if(!empty($variations->id)){
                
                        $get_pv = $variations->variation_get_pv;
                        $weight = $variations->variation_weight;
                        $variation_name = $variations->variation_name;
                    }
                    $agentPrice  = AgentPrice::where('product_id',$request->product_id[$key])->where('variation_id',$request->hidden_variation_id[$key])->where('agent_lvl_id',$merchant->lvl)->where('status','1')->first();
                }else{
                    $agentPrice  = AgentPrice::where('product_id',$request->product_id[$key])->where('agent_lvl_id',$merchant->lvl)->where('status','1')->first();
                    $get_pv = !empty($product->get_pv) ? $product->get_pv : 0;
                    $weight = $product->weight;
                }
                // $arr[] = [$request->hidden_variation_id[$key]];
                $detail = new TransactionDetail;
                $detail->transaction_id = $transaction->id;
                $detail->product_image = !empty($product_image->image) ? $product_image->image : null;
                $detail->product_id = $product->id;
                $detail->item_code = $product->item_code;
                $detail->unit_weight = $weight;
                $detail->sub_category = !empty($variation_name) ? $variation_name : null;
                $detail->variation_id = !empty($request->hidden_variation_id[$key]) ? $request->hidden_variation_id[$key] : null;
                $detail->second_sub_category = !empty($second_variation_name) ? $second_variation_name : NULL;
                $detail->second_variation_id = !empty($request->hidden_second_variation_id[$key]) ? $request->hidden_second_variation_id[$key] : NULL;

                // $detail->transaction_id = $transaction->id;
                // $detail->transaction_id = $transaction->id;
                $detail->product_name = $product->product_name;
                $detail->product_name_en = $product->product_name_en;
                // if(!empty($agentPrice->price)){
                // $detail->unit_price = !empty($agentPrice->special_price) ? $agentPrice->special_price : $agentPrice->price;                  
                // }else{
                // $detail->unit_price = !empty($product->corporate_special_price) ? $product->corporate_special_price : $product->corporate_price;                  
                // }
                if(Auth::guard('merchant')->check()){
                $detail->merchant_id = Auth::user()->code;                
                }
                $detail->unit_price = $request->pricing[$key];
                $detail->quantity = $request->quantity[$key];
                $detail->get_pv =  ($request->pricing[$key] > 0) ? $get_pv : 0;
                $detail->status = 1;
                $detail->save();

                if(!empty($product->get_product_packages)){
                    foreach($product->get_product_packages as $package){
                        $transactions_packages = new TransactionPackage();
                        $transactions_packages->detail_id = $detail->id;
                        $transactions_packages->product_id = $package->products;
                        $transactions_packages->variation_id = $package->variation_id;
                        $transactions_packages->second_variation_id = $package->second_variation_id;
                        $transactions_packages->voucher_id = $package->voucher_id;
                        $transactions_packages->quantity = $package->qty;

                        $transactions_packages->save();
                    }
                }
            }

            $details = TransactionDetail::where('transaction_id',$transaction->id)->get();

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
            }


            $get_packages = TransactionDetail::with(['get_packages'])->where('transaction_id', $transaction->id)->get();
            foreach($get_packages as $get_package){
                foreach($get_package->get_packages as $package){
                    if(!empty($package->voucher_id)){
                        for($v=0; $v<$package->quantity; $v++){
                            $input_voucher = new AppliedPromotion();
                            $input_voucher->promotion_id = $package->voucher_id;                              
                            $input_voucher->user_id = $transaction->user_id;
                            $input_voucher->transaction_id = $transaction->transaction_no;
                            $input_voucher->status = 99;
                            $input_voucher->save();
                        }
                    }
                }
            }

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

            DB::commit();

            $translation_data = GlobalController::get_translations();
            $successMsg = $translation_data['backendlang']['backendlang']['Transaction_Created'] ?? 'Transaction Created';
            toastr()->success($successMsg);


            if(isset($request->point_transaction)){
                return redirect()->route('transaction.transactions.index','mall='.'1');
            }else{
                return redirect()->route('transaction.transactions.index');
            }


            // dd($arr);
        } catch (\Exception $e) {
          DB::rollback();
          return Redirect::back()->withInput($request->all())->withErrors($e->getMessage().' '.$e->getLine());
        
        } catch (\Error $e) {
          DB::rollback();
          return Redirect::back()->withInput($request->all())->withErrors($e->getMessage().' '.$e->getLine());
        
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $transaction = Transaction::select('transactions.*', 'p.amount_type', 'p.amount AS discount_amount', 
                                           'p.discount_code', 'transactions.discount_amount as tamount','transactions.discount_type as tamount_type',
                                           's.name as address_state',
                                           'ca.address as ca_address', 'ca.address_desc as ca_address_desc', 'pc.f_name AS pickup_name', 'pc.email AS pickup_email', 'pc.phone AS pickup_phone',
                                           'tc.country_name', 'te.einvoice_uuid as te_einvoice')
                                  ->leftJoin('promotions AS p', 'p.discount_code', 'transactions.discount_code')
                                  ->leftJoin('states as s', 's.id', 'transactions.state')
                                  ->leftJoin('tbl_countries as tc', 'tc.country_id', 'transactions.country')
                                  ->leftJoin('pickup_contacts as pc', 'pc.transaction_id', 'transactions.id')
                                  ->leftJoin('cod_addresses AS ca', 'ca.id', 'transactions.cod_address')
                                  ->leftJoin('transaction_einvoices as te', 'te.transaction_no', 'transactions.transaction_no')
                                  ->where('transactions.id', $id)
                                  ->first();
        if(empty($transaction->id)){
            abort(404);
        }
        

        $transactionState = Transaction::select('transactions.*', 'states.name as NameOfState')
                                       ->leftJoin('states', 'states.id', 'transactions.state')
                                       ->where('transactions.id', $id)
                                       ->first();

        $bank_online = Bank::find($transaction->bank_id);
        $bank_cdm = Bank::where('bank_code', $transaction->cdm_bank_id)->first();

        $bill_address = TransactionBillingAddress::select('transaction_billing_addresses.*', 's.name as NameOfState', 'tc.country_name')
                                                 ->leftJoin('states as s', 's.id', 'transaction_billing_addresses.state')
                                                 ->leftJoin('tbl_countries as tc', 'tc.country_id', 'transaction_billing_addresses.country')
                                                 ->where('transaction_id', $id)
                                                 ->first();

        $details = TransactionDetail::select('transaction_details.*', 'i.image as product_image')
                                    ->leftJoin('product_images as i', 'i.product_id', 'transaction_details.product_id')
                                    ->where('transaction_id', $transaction->id)
                                    ->groupBy('transaction_details.id')
                                    ->get();
        
        return view('backend.transactions.view', ['transaction'=>$transaction, 'details'=>$details, 'bank_online'=>$bank_online, 'bank_cdm'=>$bank_cdm, 'transactionState'=>$transactionState, 'bill_address'=>$bill_address]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function withdrawal_list()
    {
        if (!empty(request('dates'))) {
            $new_dates = explode('-', request('dates'));
            $start = DateTime::createFromFormat('d/m/Y', trim($new_dates[0]))->format('Y-m-d');
            $end = DateTime::createFromFormat('d/m/Y', trim($new_dates[1]))->format('Y-m-d');

            $startDate = trim($new_dates[0]);
            $endDate = trim($new_dates[1]);
        } else {
            $ds = new DateTime("first day of this month");
            $de = new DateTime("last day of this month");

            $start = $ds->format('Y-m-d');
            $end = $de->format('Y-m-d');

            $startDate = $ds->format('d/m/Y');
            $endDate = $de->format('d/m/Y');
        }

        $transactions = WithdrawalTransaction::select(DB::raw('coalesce(CONCAT(m.f_name, " ", m.l_name), CONCAT(a.f_name, " ", a.l_name)) AS agent_name'), 'withdrawal_transactions.*')
                                             ->leftJoin('agents AS m', 'm.code', 'withdrawal_transactions.user_id')
                                             ->leftJoin('admins AS a', 'a.code', 'withdrawal_transactions.user_id')
                                             ->whereBetween(DB::raw('DATE_FORMAT(withdrawal_transactions.created_at, "%Y-%m-%d")'), array($start, $end));
                                             // ->orderBy('withdrawal_transactions.id', 'desc');

        if(Auth::guard('merchant')->check()){
            $transactions = $transactions->where('merchant_id', Auth::guard('merchant')->user()->code);
        }

        $queries = [];
        $columns = [
            'withdrawal_no', 'agent_name', 'withdrawal_desc', 'withdrawal_asc', 'name_desc', 'name_asc', 'amount_desc','amount_asc',
            'bank_name_desc', 'bank_name_asc', 'agent_name_desc', 'agent_name_asc', 'account_desc', 'account_asc','status_desc', 'status_asc',
            'created_desc', 'created_asc', 'updated_desc', 'updated_asc', 'status', 'dates', 'bank_name', 'bank_holder', 'bank_account'
        ];

        foreach($columns as $column){
            if(request()->has($column) && !empty(request($column))){
                if($column == 'agent_name'){
                    $transactions = $transactions->where(DB::raw('CONCAT(m.f_name, " ", m.l_name)'), 'like', "%".request($column)."%");
                }elseif($column == 'status'){
                    $transactions = $transactions->where('withdrawal_transactions.status', 'like', "%".request($column)."%");
                }elseif($column == 'dates'){
                    $transactions = $transactions->whereBetween(DB::raw('DATE_FORMAT(withdrawal_transactions.created_at, "%Y-%m-%d")'), array($start, $end));
                }elseif($column == 'withdrawal_desc'){
                    $transactions = $transactions->orderBy('withdrawal_transactions.withdrawal_no', 'desc');
                }elseif($column == 'withdrawal_asc'){
                    $transactions = $transactions->orderBy('withdrawal_transactions.withdrawal_no', 'asc');
                }elseif($column == 'name_desc'){
                    $transactions = $transactions->orderBy('agent_name', 'desc');
                }elseif($column == 'name_asc'){
                    $transactions = $transactions->orderBy('agent_name', 'asc');
                }elseif($column == 'amount_desc'){
                    $query = "CAST(amount AS SIGNED) DESC";
                    $transactions = $transactions->orderByRaw($query);
                }elseif($column == 'amount_asc'){
                    $query = "CAST(amount AS SIGNED) ASC";
                    $transactions = $transactions->orderByRaw($query);
                }elseif($column == 'bank_name_desc'){
                    $transactions = $transactions->orderBy('withdrawal_transactions.bank_name', 'desc');
                }elseif($column == 'bank_name_asc'){
                    $transactions = $transactions->orderBy('withdrawal_transactions.bank_name', 'asc');
                }elseif($column == 'agent_name_desc'){
                    $transactions = $transactions->orderBy('agent_name', 'desc');
                }elseif($column == 'agent_name_asc'){
                    $transactions = $transactions->orderBy('agent_name', 'asc');
                }elseif($column == 'account_desc'){
                    $transactions = $transactions->orderBy('withdrawal_transactions.bank_account', 'desc');
                }elseif($column == 'account_asc'){
                    $transactions = $transactions->orderBy('withdrawal_transactions.bank_account', 'asc');
                }elseif($column == 'status_desc'){
                    $transactions = $transactions->orderBy('withdrawal_transactions.status', 'desc');
                }elseif($column == 'status_asc'){
                    $transactions = $transactions->orderBy('withdrawal_transactions.status', 'asc');
                }elseif($column == 'created_desc'){
                    $transactions = $transactions->orderBy('withdrawal_transactions.created_at', 'desc');
                }elseif($column == 'created_asc'){
                    $transactions = $transactions->orderBy('withdrawal_transactions.created_at', 'asc');
                }elseif($column == 'updated_desc'){
                    $transactions = $transactions->orderBy('withdrawal_transactions.updated_at', 'desc');
                }elseif($column == 'updated_asc'){
                    $transactions = $transactions->orderBy('withdrawal_transactions.updated_at', 'asc');
                }elseif($column == 'bank_holder'){
                    $transactions = $transactions->where('withdrawal_transactions.bank_holder_name', 'like', "%".request($column)."%");
                }elseif($column == 'bank_account'){
                    $transactions = $transactions->where('withdrawal_transactions.bank_account', 'like', "%".request($column)."%");
                }else{
                    $transactions = $transactions->where($column, 'like', "%".request($column)."%");
                }

                $queries[$column] = request($column);

            }
        }

        $per_page = 10;
        if(!empty(request('per_page'))){
            $per_page = request('per_page');
        }
        $transactions = $transactions->orderBy('withdrawal_transactions.id', 'desc');
        $transactions = $transactions->paginate($per_page)->appends($queries);

        $GetWalletBalance = [];
        foreach($transactions as $transaction){
          $GetWalletBalance[$transaction->withdrawal_no] = $this->previousGetWalletBalance($transaction->user_id, $transaction->created_at);
        }

        
        return view('backend.transactions.withdrawal_list', ['transactions'=>$transactions, 
                                                             'startDate'=>$startDate, 'endDate'=>$endDate],
                                                            compact('GetWalletBalance'));
    }

    public function print_withdrawal_list()
    {
        if(!empty(request('dates'))){

          $new_dates = explode('-', request('dates'));
          $start = DateTime::createFromFormat('d/m/Y', trim($new_dates[0]))->format('Y-m-d');
          $end = DateTime::createFromFormat('d/m/Y', trim($new_dates[1]))->format('Y-m-d');

          $startDate = $new_dates[0];
          $endDate = $new_dates[1];

        }else{

          $ds = new DateTime("first day of this month");
          $de = new DateTime("last day of this month");

          $start = $ds->format('Y-m-d');
          $end = $de->format('Y-m-d');

          $startDate = $ds->format('d/m/Y');
          $endDate = $de->format('d/m/Y');
        }

        $transactions = WithdrawalTransaction::select(DB::raw('coalesce(CONCAT(m.f_name, " ", m.l_name), CONCAT(a.f_name, " ", a.l_name)) AS agent_name'), 'withdrawal_transactions.*')
                                             ->leftJoin('agents AS m', 'm.code', 'withdrawal_transactions.user_id')
                                             ->leftJoin('admins AS a', 'a.code', 'withdrawal_transactions.user_id')
                                             ->whereBetween(DB::raw('DATE_FORMAT(withdrawal_transactions.created_at, "%Y-%m-%d")'), array($start, $end))
                                             ->orderBy('withdrawal_transactions.id', 'desc');
        $queries = [];
        $columns = [
            'withdrawal_no', 'agent_name', 'status', 'dates'
        ];

        foreach($columns as $column){
            if(request()->has($column) && !empty(request($column))){
                if($column == 'agent_name'){
                    $transactions = $transactions->where(DB::raw('CONCAT(m.f_name, " ", m.l_name)'), 'like', "%".request($column)."%");
                }elseif($column == 'status'){
                    $transactions = $transactions->where('withdrawal_transactions.status', request($column));
                }elseif($column == 'dates'){
                    $transactions = $transactions->whereBetween(DB::raw('DATE_FORMAT(withdrawal_transactions.created_at, "%Y-%m-%d")'), array($start, $end));
                }else{
                    $transactions = $transactions->where($column, 'like', "%".request($column)."%");                    
                }

                $queries[$column] = request($column);

            }
        }

        $transactions = $transactions->get();

        $GetWalletBalance = [];
        foreach($transactions as $transaction){
          $GetWalletBalance[$transaction->withdrawal_no] = $this->previousGetWalletBalance($transaction->user_id, $transaction->created_at);
        }
        
        return view('backend.transactions.print_withdrawal_list', ['transactions'=>$transactions, 
                                                             'startDate'=>$startDate, 'endDate'=>$endDate],
                                                            compact('GetWalletBalance'));
    }

    public function exportWithdrawalReport()
    {
        if (!empty(request('dates'))) {
            $new_dates = explode('-', request('dates'));
            $start = DateTime::createFromFormat('d/m/Y', trim($new_dates[0]))->format('Y-m-d');
            $end = DateTime::createFromFormat('d/m/Y', trim($new_dates[1]))->format('Y-m-d');

            $startDate = trim($new_dates[0]);
            $endDate = trim($new_dates[1]);
        } else {
            $ds = new DateTime("first day of this month");
            $de = new DateTime("last day of this month");

            $start = $ds->format('Y-m-d');
            $end = $de->format('Y-m-d');

            $startDate = $ds->format('d/m/Y');
            $endDate = $de->format('d/m/Y');
        }

        $withdrawal_no = "";
        if(!empty(request('withdrawal_no'))){
          $withdrawal_no = request('withdrawal_no');
        }

        $agent_name = "";
        if(!empty(request('agent_name'))){
          $agent_name = request('agent_name');
        }

        $status = "";
        if(!empty(request('status'))){
          $status = request('status');
        }

          return Excel::download(new WithdrawalExport($start, $end, $withdrawal_no, $agent_name, $status), 'Withdrawal Report '.$start.' - '.$end.'.xlsx');
    }

    public function exportTransaction()
    {
        if (!empty(request('dates'))) {
            $new_dates = explode('-', request('dates'));
            $start = DateTime::createFromFormat('d/m/Y', trim($new_dates[0]))->format('Y-m-d');
            $end = DateTime::createFromFormat('d/m/Y', trim($new_dates[1]))->format('Y-m-d');

            $startDate = trim($new_dates[0]);
            $endDate = trim($new_dates[1]);
        } else {
            $ds = new DateTime("first day of this month");
            $de = new DateTime("last day of this month");

            $start = $ds->format('Y-m-d');
            $end = $de->format('Y-m-d');

            $startDate = $ds->format('d/m/Y');
            $endDate = $de->format('d/m/Y');
        }

        $buyer_name = "";
        if(!empty(request('buyer_name'))){
            $buyer_name = request('buyer_name');
        }

        $buyer_code = "";
        if(!empty(request('buyer_code'))){
            $buyer_code = request('buyer_code');
        }

        $transaction_no = "";
        if(!empty(request('transaction_no'))){
            $transaction_no = request('transaction_no');
        }

        $status = "";
        if(!empty(request('status'))){
            $status = request('status');
        }

        $delivery_type = "";
        if(!empty(request('delivery_type'))){
            $delivery_type = request('delivery_type');
        }

        $payment = "";
        if(!empty(request('payment'))){
            $payment = request('payment');
        }

        $mall = "";

        if(!empty(request('mall'))){
            $mall = request('mall');
        }

        return Excel::download(new TransactionExport($start, $end, $buyer_name, $buyer_code, $transaction_no, $status, $delivery_type, $payment, $mall), 'TransactionReport'.$start.' - '.$end.'.xlsx');
    }

    public function exportTopupList()
    {
        if (!empty(request('dates'))) {
            $new_dates = explode('-', request('dates'));
            $start = DateTime::createFromFormat('d/m/Y', trim($new_dates[0]))->format('Y-m-d');
            $end = DateTime::createFromFormat('d/m/Y', trim($new_dates[1]))->format('Y-m-d');
            $startDate = trim($new_dates[0]);
            $endDate = trim($new_dates[1]);
        } else {
            $ds = new DateTime("first day of this month");
            $de = new DateTime("last day of this month");
            $start = $ds->format('Y-m-d');
            $end = $de->format('Y-m-d');
            $startDate = $ds->format('d/m/Y');
            $endDate = $de->format('d/m/Y');
        }

        $topup_no = request('topup_no', '');
        $agent_name = request('agent_name', '');
        $status = request('status', '');

        return Excel::download(new TopupListExport($start, $end, $topup_no, $agent_name, $status), 'TopupList_' . str_replace('/', '-', $startDate) . ' - ' . str_replace('/', '-', $endDate) . '.xlsx');
    }

    public function uploadBankSlip(Request $request)
    {
        $translation_data = GlobalController::get_translations();
        $files = $request->file('uploadSlip'); 
        $name = $files->getClientOriginalName();
        $exp = explode(".", $name);
        $file_ext = end($exp);
        $name = md5($name.date('Y-m-d H:i:s')).'.'.$file_ext;
        $files->move(GlobalController::get_image_path("uploads/withdrawal_bank_slip/"), $name);

        $input = $request->all();
        $input['withdrawal_slip'] = "uploads/withdrawal_bank_slip/".$name;

        $withdrawal = WithdrawalTransaction::find($request->wid);

        $merchants = Agent::where('code', $withdrawal->user_id)->first();
        $admins = Admin::where('code', $withdrawal->user_id)->first();

        if(!empty($merchants->id)){
            $phone = $merchants->phone;
        }else{
            $phone = $admins->phone;
        }

        if($request->withAction == 1){
            // $destination = urlencode($phone);
            // $message = "因诗美: 您的提款号: ".$withdrawal->withdrawal_no." 已批准";
            // $message = html_entity_decode($message, ENT_QUOTES, 'utf-8'); 
            // $message = urlencode($message);
              
            // $username = urlencode("yinshimei");
            // $password = urlencode("yinshimei1234");
            // $sender_id = urlencode("66300");
            // $type = "2";

            // $fp = "https://www.isms.com.my/isms_send_all.php";
            // $fp .= "?un=$username&pwd=$password&dstno=$destination&msg=$message&type=$type&sendid=$sender_id&agreedterm=YES";
            // //echo $fp;
              
            // $http = curl_init($fp);

            // curl_setopt($http, CURLOPT_RETURNTRANSFER, TRUE);
            // $http_result = curl_exec($http);
            // $http_status = curl_getinfo($http, CURLINFO_HTTP_CODE);
            // curl_close($http);

            
            $totalBalance = $this->GetWalletBalance($withdrawal->user_id);
            if($totalBalance < $withdrawal->amount){
                Toastr::error($translation_data['backendlang']['backendlang']['This user has insufficient balance!'] ?? 'This user has insufficient balance!');
            }else{
                $input['status'] = 1;
            }
        }

        $withdrawal = $withdrawal->update($input);

        Toastr::success($translation_data['backendlang']['backendlang']['Upload_Successful'] ?? 'Upload Successful');
        return redirect()->back();
    }

    public function GetWalletBalance($user)
    {
        $balance = AffiliateCommission::select(DB::raw('SUM(comm_amount) as totalBalance'))
                                      ->where('user_id', $user)
                                      ->where('status', '1')
                                      ->first();

        $withdrawal = WithdrawalTransaction::select(DB::raw('SUM(amount) as totalWithdrawal'))
                                             ->where('user_id', $user)
                                             ->where('status', '1')
                                             ->first();
        $totalBalance = 0;
        
        $totalBalance = $balance->totalBalance - $withdrawal->totalWithdrawal;
        

        return $totalBalance;
    }

    public function transaction_invoice($transaction_no)
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

        $details = TransactionDetail::select('transaction_details.*', 'transaction_details.quantity as t_qty', 'u.uom_en', 'p.packages')
                                    ->join('products AS p', 'p.id', 'transaction_details.product_id')
                                    ->leftJoin('setting_uoms AS u', 'u.id', 'p.product_type')
                                    ->where('transaction_id', $transaction->id)
                                    ->get();

        $cod_address = CodAddress::first();

        $delivery_state = State::find($transaction->state);

        $delivery_country = TblCountry::where('country_id', $transaction->country)->first();

        $bill_address = TransactionBillingAddress::select('transaction_billing_addresses.*', 's.name as NameOfState', 'tc.country_name')
                                                 ->leftJoin('states as s', 's.id', 'transaction_billing_addresses.state')
                                                 ->leftJoin('tbl_countries as tc', 'tc.country_id', 'transaction_billing_addresses.country')
                                                 ->where('transaction_id', $transaction->id)
                                                 ->first();

        return view('invoice', ['transaction'=>$transaction, 
                                'details'=>$details, 
                                'cod_address'=>$cod_address, 
                                'delivery_state'=>$delivery_state,
                                'delivery_country'=>$delivery_country,
                                'bill_address'=>$bill_address]);
    }

    public function topup_list()
    {
        if (!empty(request('dates'))) {
            $new_dates = explode('-', request('dates'));
            $start = DateTime::createFromFormat('d/m/Y', trim($new_dates[0]))->format('Y-m-d');
            $end = DateTime::createFromFormat('d/m/Y', trim($new_dates[1]))->format('Y-m-d');

            $startDate = trim($new_dates[0]);
            $endDate = trim($new_dates[1]);
        } else {
            $ds = new DateTime("first day of this month");
            $de = new DateTime("last day of this month");

            $start = $ds->format('Y-m-d');
            $end = $de->format('Y-m-d');

            $startDate = $ds->format('d/m/Y');
            $endDate = $de->format('d/m/Y');
        }

        $topups = TopupTransaction::select('topup_transactions.*', DB::raw('CONCAT(m.f_name, " ", m.l_name) AS agent_name'))
                                  ->leftJoin('agents AS m', 'm.code', 'topup_transactions.user_id')
                                  ->orderBy('topup_transactions.id', 'desc');

        if(Auth::guard('merchant')->check()){
            $topups = $topups->where('merchant_id', Auth::guard('merchant')->user()->code);
        }
                                  
        // $topups = TopupTransaction::select(DB::raw('coalesce(CONCAT(m.f_name, " ", m.l_name), CONCAT(a.f_name, " ", a.l_name)) AS agent_name'), 'topup_transactions.*')
        //                           ->leftJoin('merchants AS m', 'm.code', 'topup_transactions.user_id')
        //                           ->leftJoin('admins AS a', 'a.code', 'topup_transactions.user_id')
        //                           ->orderBy('topup_transactions.id', 'desc');
        $queries = [];
        $columns = [
            'topup_no', 'agent_name', 'status', 'dates'
        ];

        foreach($columns as $column){
            if(request()->has($column) && !empty(request($column))){
                if($column == 'agent_name'){
                    $topups = $topups->where(DB::raw('CONCAT(m.f_name, " ", m.l_name)'), 'like', "%".request($column)."%");
                }elseif($column == 'status'){
                    $topups = $topups->where('topup_transactions.status', 'like', "%".request($column)."%");
                }elseif($column == 'dates'){
                    $topups = $topups->whereBetween(DB::raw('DATE_FORMAT(topup_transactions.created_at, "%Y-%m-%d")'), array($start, $end));
                }else{
                    $topups = $topups->where($column, 'like', "%".request($column)."%");                    
                }

                $queries[$column] = request($column);

            }
        }

        $per_page = 10;
        if(!empty(request('per_page'))){
            $per_page = request('per_page');
        }
        $topups = $topups->paginate($per_page)->appends($queries);

        

        return view('backend.transactions.topup_list', ['topups'=>$topups, 'startDate' => $startDate, 'endDate' => $endDate]);
    }

    public function join_list()
    {
        $join_records = JoiningRecord::select('joining_records.*', DB::raw('CONCAT(m.f_name, " ", m.l_name) AS agent_name'))
                                     ->leftJoin('merchants as m', 'm.code', 'joining_records.user_id')
                                     ->orderBy('created_at', 'desc');

        $queries = [];
        $columns = [
            'code', 'per_page'
        ];

        $per_page = 10;
        if(!empty(request('per_page'))){
            $per_page = request('per_page');
        }

        foreach($columns as $column){
            if(request()->has($column) && !empty(request($column))){
                if($column == 'code'){
                    $join_records = $join_records->where('user_id', 'like', "%".request($column)."%");
                }elseif($column == 'per_page'){
                    $join_records = $join_records->paginate($per_page);
                }else{
                    $join_records = $join_records->where($column, 'like', "%".request($column)."%");                    
                }

                $queries[$column] = request($column);

            }
        }

        if(!empty(request('per_page'))){
            $join_records = $join_records->appends($queries);
        }else{
            $join_records = $join_records->paginate($per_page)->appends($queries);
        }

        return view('backend.transactions.join_list', ['join_records'=>$join_records]);
    }

    public function topup_invoice($topup_no)
    {
        $transaction = TopupTransaction::select('topup_transactions.*', DB::raw('CONCAT(m.f_name, " ", m.l_name) AS agent_name'))
                                       ->join('agents as m', 'm.code', 'topup_transactions.user_id')
                                       ->where('topup_no', $topup_no)
                                       ->first();

        return view('backend.transactions.topup_invoice', ['transaction'=>$transaction]);
    }

    public function add_awb_no(Request $request)
    {
        $translation_data = GlobalController::get_translations();
        $transaction = Transaction::find($request->transaction_id);
        if(!empty($transaction->id)){
            $transaction = $transaction->update(['awb_no'=>$request->awb_no]);
        }

        Toastr::success($translation_data['backendlang']['backendlang']['Add_Successful!'] ?? 'Add Successful!');
        return redirect()->back();
    }

    public function shipping_details($transaction_no, $row)
    {
        $transaction = Transaction::select('t.*', 'transactions.transaction_no')
                                  ->join('transaction_trackings as t', 't.transaction_id', 'transactions.id')
                                  ->where('transaction_no', $transaction_no)
                                  ->where('row_id', $row)
                                  ->first();

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

        return view('backend.transactions.shipping_details', ['transaction'=>$transaction, 'results'=>$json]);
    }

    public function previousGetWalletBalance($user, $created)
    {
        $balance = AffiliateCommission::select(DB::raw('SUM(comm_amount) as totalBalance'))
                                      ->where('user_id', $user)
                                      ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d %H:%i:%s")'), '<', $created)
                                      ->where('status', '1')
                                      ->first();

        $withdrawal = WithdrawalTransaction::select(DB::raw('SUM(amount) as totalWithdrawal'))
                                             ->where('user_id', $user)
                                             ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d %H:%i:%s")'), '<', $created)
                                             ->where('status', '1')
                                             ->first();
        $totalBalance = 0;
        
        $totalBalance = $balance->totalBalance - $withdrawal->totalWithdrawal;
        

        return $totalBalance;
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

        $data['admin'] = $admins;
        $data['web_setting'] = WebsiteSetting::first();
        $data['bank_required'] = '0';
        if(Auth::guard('agent')->check()){
            $data['userGuardRole'] = "agent";
        }elseif(Auth::guard('web')->check()){
            $data['userGuardRole'] = "web";
        }elseif(Auth::guard('admin')->check()){
            $data['userGuardRole'] = "admin";
        }else{
            $data['userGuardRole'] = "";
        }

        // $opciones_ssl=array(
        //   "ssl"=>array(
        //   "verify_peer"=>false,
        //   "verify_peer_name"=>false,
        //   ),
        // );
       
        // $logo_path = asset($admins->ecommerce_logo);
        // $extencion = pathinfo($logo_path, PATHINFO_EXTENSION);
        // $data = file_get_contents($logo_path, false, stream_context_create($opciones_ssl));
        // $img_base_64 = base64_encode($data);
        // $logo = 'data:image/' . $extencion . ';base64,' . $img_base_64;

        // $image = base64_encode(file_get_contents(asset($admins->ecommerce_logo)));

        $invoice_name = !empty($data['web_setting']->invoice_name) ? $data['web_setting']->invoice_name : $data['admin']->website_name;
        $pdf = \PDF::loadView('backend.transactions.invoice', ['transaction'=>$transaction, 'details'=>$details, 'cod_address'=>$cod_address, 'delivery_state'=>$delivery_state, 'delivery_country'=>$delivery_country, 'bill_address'=>$bill_address], compact('detail', 'data'));
        return $pdf->download($invoice_name.' '.$transaction_no.'.pdf');

        // return view('frontend.invoice', ['transaction'=>$transaction, 'details'=>$details]);
    }

    public function qr_transactions_list(){
            $transactions = TransactionQrPayment::select(DB::raw('COALESCE(COALESCE(CONCAT(m.f_name, " ", m.l_name), CONCAT(s.f_name, " ", s.l_name)), CONCAT(a.f_name, " ", a.l_name)) AS customer_name'),
                                                'transaction_qr_payments.*',
                                                'transaction_qr_payments.id AS Tid',
                                                'm.code as Acode', 's.code as Ccode', 'a.code as ADcode',
                                                'mr.f_name as merchant_name')
                                       ->leftJoin('merchants AS m', 'm.code', 'transaction_qr_payments.user_id')
                                       ->leftJoin('users AS s', 's.code', 'transaction_qr_payments.user_id')
                                       ->leftJoin('admins AS a', 'a.code', 'transaction_qr_payments.user_id')
                                       ->leftJoin('merchants AS mr', 'mr.code', 'transaction_qr_payments.merchant_id')
                                       ->where('transaction_qr_payments.status', '!=', '55')
                                       ->groupBy('transaction_qr_payments.id')
                                       ->orderBy('transaction_qr_payments.created_at', 'desc');
        
        $per_page = 10;
        if(!empty(request('per_page'))){
            $per_page = request('per_page');
        }

        $queries = [];
        $columns = [
            'transaction_no', 'status', 'per_page'
        ];

        foreach($columns as $column){
            if(request()->has($column) && !empty(request($column))){
                if($column == 'status'){
                    $transactions = $transactions->where("transactions.status", 'like', "%".request($column)."%");
                }elseif($column == 'per_page'){
                  $transactions = $transactions->paginate($per_page);
                }else{
                    $transactions = $transactions->where($column, 'like', "%".request($column)."%");
                }

                $queries[$column] = request($column);

            }
        }

        if(!empty(request('per_page'))){
            $transactions = $transactions->appends($queries);        
        }else{
            $transactions = $transactions->paginate($per_page)->appends($queries);        
        }

        return view('backend.transactions.qr_transaction', ['qr_list'=>$transactions]);
    }

    public function withdrawal_stocks()
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

          $startDate = $ds->format('d/m/Y');
          $endDate = $de->format('d/m/Y');
        }

        $withdrawal_stock = WithdrawalStock::select('withdrawal_stocks.*', 'withdrawal_stocks.id as ws_id',
                                                    'm.f_name',
                                                    'p.product_name',
                                                    'v.variation_name',
                                                    'sv.variation_name as second_variation_name')
                                           ->join('merchants as m', 'm.code', 'withdrawal_stocks.user_id')
                                           ->join('products as p', 'p.id', 'withdrawal_stocks.product_id')
                                           ->leftJoin('product_variations as v', 'v.id', 'withdrawal_stocks.variation_id')
                                           ->leftJoin('product_second_variations as sv', 'sv.id', 'withdrawal_stocks.second_variation_id')
                                           ->whereIn('withdrawal_stocks.status', ['1', '99'])
                                           ->whereBetween(DB::raw('DATE_FORMAT(withdrawal_stocks.created_at, "%Y-%m-%d")'), array($start, $end));
        $queries = [];
        $columns = [
            'withdrawal_no', 'agent_name', 'withdrawal_desc', 'withdrawal_asc', 'name_desc', 'name_asc', 'amount_desc', 'amount_asc', 'bank_name_desc', 'bank_name_asc', 'agent_name_desc', 'agent_name_asc', 'account_desc', 'account_asc', 'status_desc', 'status_asc', 'created_desc', 'created_asc', 'updated_desc', 'updated_asc', 'status', 'dates'
        ];

        foreach($columns as $column){
            if(request()->has($column) && !empty(request($column))){
                if($column == 'agent_name'){
                    $transactions = $transactions->where(DB::raw('CONCAT(m.f_name, " ", m.l_name)'), 'like', "%".request($column)."%");
                }elseif($column == 'status'){
                    $transactions = $transactions->where('withdrawal_transactions.status', 'like', "%".request($column)."%");
                }elseif($column == 'dates'){
                    $transactions = $transactions->whereBetween(DB::raw('DATE_FORMAT(withdrawal_transactions.created_at, "%Y-%m-%d")'), array($start, $end));
                }else{
                    $transactions = $transactions->where($column, 'like', "%".request($column)."%");                    
                }

                $queries[$column] = request($column);

            }
        }

        $per_page = 10;
        if(!empty(request('per_page'))){
            $per_page = request('per_page');
        }
        $withdrawal_stock = $withdrawal_stock->orderBy('withdrawal_stocks.id', 'desc');
        $withdrawal_stock = $withdrawal_stock->paginate($per_page)->appends($queries);

        $ProductBalanceLeft = [];
        foreach($withdrawal_stock as $stock){
            $ProductBalanceLeft[$stock->id] = $this->ProductBalanceLeft($stock->user_id, $stock->product_id, $stock->variation_id, $stock->second_variation_id, $stock->created_at);
        }

        return view('backend.transactions.withdrawal_stocks', ['withdrawal_stock'=>$withdrawal_stock,
                                                               'startDate'=>$startDate,
                                                               'endDate'=>$endDate], 
                                                              compact('ProductBalanceLeft'));
    }

    public function ProductBalanceLeft($code, $pid, $vid, $svid, $date_before)
    {
        $my_stocks = Transaction::select(DB::raw('SUM(d.quantity) as totalStock'))
                               ->join('transaction_details as d', 'd.transaction_id', 'transactions.id')
                               ->join('products as p', 'p.id', 'd.product_id')
                               ->leftJoin('product_variations as v', 'v.id', 'd.variation_id')
                               ->leftJoin('product_second_variations as sv', 'sv.id', 'd.second_variation_id')
                               ->where('transactions.status', '1')
                               ->where('transactions.store_stock', '1')
                               ->where('transactions.user_id', $code)
                               ->where('d.product_id', $pid)
                               ->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d %H:%i:%s")'), '<', $date_before);
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
                                           ->whereIn('status', ['1', '99'])
                                           ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d %H:%i:%s")'), '<', $date_before);

        if(!empty($vid)){
        $withdrawal_stock = $withdrawal_stock->where('variation_id', $vid);
        }
        if(!empty($svid)){
        $withdrawal_stock = $withdrawal_stock->where('second_variation_id', $svid);
        }
        $withdrawal_stock = $withdrawal_stock->first();

        return $my_stocks->totalStock - $withdrawal_stock->totalStock;
    }

    public function update_remark(Request $request, $id)
    {
        try{
            \DB::beginTransaction();
            
            $transaction = Transaction::where(DB::raw('md5(id)'), $id)->first();

            if(empty($transaction->id)){
                throw new \Exception('Error Transaction');
            }

            $transaction->remark = $request->remark;
            $transaction->save();
            
            \DB::commit();

            Toastr::success('Remark Saved');
            return Redirect::back();
        }catch (\Exception $e){
            \DB::rollback();
            Toastr::error($e->getMessage());
            return Redirect::back();
        }catch(\Error $e){
            Toastr::error($e->getMessage());
            return Redirect::back();
        }
    }

    public function create_transaction_points(){
        $merchants = Agent::where('status', '1');
        if(Auth::guard('merchant')->check()){
            $merchants = $merchants->where('dual_master_id', Auth::user()->code);
        }
        $merchants = $merchants->get();

        $users = User::where('status', '1');
        if(Auth::guard('merchant')->check()){
            $users = $users->where('dual_master_id', Auth::user()->code);
        }
        $users = $users->get();

        $merchants = $merchants->concat($users);

        $merchants = Arr::sort($merchants, function ($value) {
            return $value['code'];
        });

        $products = Product::where('status', '1')
                           ->where('mall',1);
        if(Auth::guard('merchant')->check()){
            $products = $products->where('merchant_id', Auth::user()->code);
        }
        $products = $products->get();


        $state = State::all();
        $countries = GlobalController::global_countries();

        $old_variations = [];
        $old_second_variations = [];
        if (!empty(old('product_id'))) {
            foreach (old('product_id') as $old_product_id_key => $old_product_id) {
                $current_product = Product::find($old_product_id);

                if (!empty($current_product->id)) {
                    if ($current_product->variation_enable == 1) {
                        $old_variations[$current_product->id] = ProductVariation::where('product_id', $current_product->id)
                                                                                ->get();

                        if ($current_product->second_variation_enable == 1) {
                            foreach ($old_variations[$current_product->id] as $old_variation_key => $old_variation) {
                                $old_second_variations[$old_variation->id] = ProductSecondVariation::where('variation_id', $old_variation->id)
                                                                                                   ->get();
                            }
                        }
                    }
                }
            }
        }

        return view('backend.transactions.create', ['merchants'=>$merchants, 'products'=>$products, 'state'=>$state, 'countries'=>$countries], compact('old_variations', 'old_second_variations'));
    }
}
