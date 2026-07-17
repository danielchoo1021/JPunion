<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\CategoryImage;
use App\Product;
use App\Category;
use App\Cart;
use App\Transaction;
use App\RTable;
use App\QrPayList;
use App\PaymentBank;
use App\ProductVariation;
use App\TransactionDetail;
use App\TransactionAddon;
use App\WebsiteSetting;
use App\Admin;
use App\Agent;
use App\User;

use App\Http\Controllers\GlobalController;
use Validator, Redirect, Toastr, DB, File, Auth;

class CafeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
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

    public function cashier_screen()
    {
        $products = Product::select('products.*')
                           ->whereIn('products.status', ['1', '2']);
        if(Auth::guard('merchant')->check()){
        $products = $products->where('products.merchant_id', Auth::user()->code);
        }
        $products = $products->get();

        $categories = Category::select('categories.*', 'i.image')
                              ->leftJoin('category_images as i', 'categories.id', 'i.category_id')
                              ->where('categories.status', '1')
                              ->groupBy('categories.id');
        if(Auth::guard('merchant')->check()){
        $categories = $categories->where('categories.merchant_id', Auth::user()->code);
        }
        $categories = $categories->get();

        $carts = Cart::where('cashier', '1')
                     ->get();

        $transactions = Transaction::select('s.name as state_name', 'transactions.*')
                                   ->leftJoin('states as s', 's.id', 'transactions.state')
                                   ->where('order_type', '3')
                                   ->where('transactions.status', '1')
                                   ->whereNull('completed')
                                   ->orderBy('created_at', 'asc')
                                   ->get();

        $transactionCount = count($transactions);
        $addons = [];
        $stockBalance = [];
        $product_pricing = [];
        foreach($carts as $cart){
            $product_pricing[$cart->id] = GlobalController::get_product_pricing(md5($cart->product_id), "", $cart->sub_category_id, $cart->second_sub_category_id);

            if($cart->get_product_det->variation_enable == 1){
                if($cart->get_product_det->second_variation_enable){
                    $stockBalance[$cart->id] = GlobalController::second_variation_balance_quantity($cart->second_sub_category_id);
                }else{
                    $stockBalance[$cart->id] = GlobalController::variation_balance_quantity($cart->sub_category_id);
                }
            }else{
                $stockBalance[$cart->id] = GlobalController::balance_quantity($cart->product_id);
            }
        }

        $tables = RTable::where('status', '1')->get();
        $qr_pay_lists = QrPayList::where('status', '1')->get();
        $payment_banks = PaymentBank::where('status', '1')->get();

        $agents = Agent::select('agents.*')
                       ->where('status', '1');
        if(Auth::guard('merchant')->check()){
        $agents = $agents->where('agents.dual_master_id', Auth::user()->code);
        }
        $agents = $agents->get();

        $members = User::select('users.*')
                        ->where('status', '1');
        if(Auth::guard('merchant')->check()){
        $members = $members->where('users.dual_master_id', Auth::user()->code);
        }
        $members = $members->get();

        $all_users = $agents->concat($members);

        return view('backend.cashier.index', ['categories'=>$categories, 
                                              'carts'=>$carts, 'tables'=>$tables,
                                              'qr_pay_lists'=>$qr_pay_lists, 
                                              'payment_banks'=>$payment_banks, 
                                              'transactionCount'=>$transactionCount], 
                                              compact('addons', 
                                                      'stockBalance',
                                                      'all_users',
                                                      'product_pricing'));
    }

    public function print_receipt($transaction_no)
    {
        $transaction = Transaction::where('transaction_no', $transaction_no)->first();
        if(empty($transaction->id)){
            abort(404);
        }
        if(!empty($transaction->combined_id)){
            $expTran = explode(', ', $transaction->combined_id);

            $ts = Transaction::whereIn('transaction_no', $expTran)->get();
            $ids = [];
            foreach($ts as $t){
                $ids[] = $t->id;
            }
            $detail1 = TransactionDetail::where('transaction_id', $transaction->id)->get();
            $detail2 = TransactionDetail::whereIn('transaction_id', $ids)->get();

            $details = $detail1->concat($detail2);
        }else{
            $details = TransactionDetail::where('transaction_id', $transaction->id)->get();
        }

        $history_add_ons = [];
        foreach($details as $detail){
            $history_add_ons[$detail->id] = TransactionAddon::where('details_id', $detail->id)->get();
        }

        $web_setting = WebsiteSetting::find(1);
        $admin = Admin::where('id', '1')->first();

        return view('backend.cashier.print_receipt', ['transaction'=>$transaction, 'details'=>$details,
                                                      'web_setting'=>$web_setting, 'admin'=>$admin], compact('history_add_ons'));
    }

    public function kitchen_screen()
    {
        $transactions = Transaction::select('transactions.*', 't.table_name', 's.name as state_name', 'o.specific_time')
                                   ->leftJoin('r_tables as t', 't.id', 'transactions.table_id')
                                   ->leftJoin('states as s', 's.id', 'transactions.state')
                                   ->leftJoin('order_now_details as o', 'o.user_id', 'transactions.user_id')
                                   ->where(function($query){
                                        $query->where('order_type', '!=', '3');
                                        $query->whereNotIn('transactions.status', ['98', '95']);
                                        $query->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), date('Y-m-d'));
                                        $query->whereNull('transactions.cook_status');
                                   })
                                   ->orWhere(function($query){
                                        $query->where('order_type', '=', '3');
                                        $query->where('transactions.status', '=', '1');
                                        $query->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), date('Y-m-d'));
                                        $query->whereNull('transactions.cook_status');
                                   })
                                   ->orderBy('transactions.created_at', 'asc')
                                   ->get();

        $details = [];
        $add_on_details = [];
        foreach($transactions as $transaction){
           $details[$transaction->id] = TransactionDetail::where('transaction_id', $transaction->id)
                                                         ->where('status', '!=', '2')
                                                         ->where('product_type', '1')
                                                         ->orderBy(DB::raw("CASE status 
                                                                         WHEN '99' THEN 1
                                                                         WHEN '1' THEN 2
                                                                    ELSE 3 END"))
                                                         ->get();
           foreach($details[$transaction->id] as $dt){
              $add_on_details[$dt->id] = TransactionAddon::select('t.variation_title', 'transaction_addons.*')
                                                         ->join('product_variations AS v', 'v.id', 'transaction_addons.add_on_id')
                                                         ->join('variation_titles as t', 't.id', 'v.variation_title_id')
                                                         ->where('details_id', $dt->id)
                                                         ->where('add_on_type', '1')
                                                         ->get();
           }
        }

        return view('backend.kitchen.index', ['transactions'=>$transactions], compact('details', 'add_on_details'));
    }

    public function beverage_screen()
    {
        $transactions = Transaction::select('transactions.*', 't.table_name', 'o.specific_time')
                                   ->leftJoin('r_tables as t', 't.id', 'transactions.table_id')
                                   ->leftJoin('order_now_details as o', 'o.user_id', 'transactions.user_id')
                                   ->where(function($query){
                                        $query->whereNotIn('transactions.status', ['98', '95']);
                                        $query->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), date('Y-m-d'));
                                        $query->whereNull('transactions.cook_status');
                                   })
                                   ->orWhere(function($query){
                                        $query->where('order_type', '=', '3');
                                        $query->where('transactions.status', '=', '1');
                                        $query->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), date('Y-m-d'));
                                        $query->whereNull('transactions.cook_status');
                                   })
                                   ->orderBy('transactions.created_at', 'asc')
                                   ->get();

        $details = [];
        $add_on_details = [];
        foreach($transactions as $transaction){
           $details[$transaction->id] = TransactionDetail::select('transaction_details.*', 'transaction_details.quantity AS td_qty',
                                                                  'transaction_details.id AS td_id',
                                                                  'transaction_details.status as td_status')
                                                         ->where('transaction_id', $transaction->id)
                                                         ->where('status', '!=', '2')
                                                         ->where('product_type', '2')
                                                         ->get();

           $asd[$transaction->id] = TransactionAddon::select('t.variation_title', 'transaction_addons.*', 
                                                             'transaction_addons.qty as ad_qty', 'transaction_addons.id as ta_id',
                                                             'transaction_addons.status as ta_status')
                                                         ->join('product_variations AS v', 'v.id', 'transaction_addons.add_on_id')
                                                         ->join('variation_titles as t', 't.id', 'v.variation_title_id')
                                                         ->where('transaction_addons.transaction_id', $transaction->id)
                                                         ->where('add_on_type', '2')
                                                         ->where('transaction_addons.status', '!=', '2')
                                                         ->get();

           $details[$transaction->id] = $details[$transaction->id]->concat($asd[$transaction->id]);

           $details[$transaction->id] = array_reverse(array_sort($details[$transaction->id], function ($value) {
                return $value['created_at'];
           }));

           foreach($details[$transaction->id] as $dt){
              $add_on_details[$dt->id] = TransactionAddon::select('t.variation_title', 'transaction_addons.*')
                                                         ->join('product_variations AS v', 'v.id', 'transaction_addons.add_on_id')
                                                         ->join('variation_titles as t', 't.id', 'v.variation_title_id')
                                                         ->where('details_id', $dt->id)
                                                         ->get();
           }
        }

        return view('backend.beverage.index', ['transactions'=>$transactions], compact('details', 'add_on_details'));
    }

    public function print_qr($id, $tid)
    {
        $tables = RTable::find($tid);
        $web_setting = WebsiteSetting::find(1);
        $admin = Admin::where('id', '1')->first();

        return view('backend.cashier.print_qr', ["id"=>$id, "tid"=>$tid, 'tables'=>$tables, 'web_setting'=>$web_setting,
                                         'admin'=>$admin]);
    }

    public function main_menu($qid, $tid)
    {
      $qrcode = QrCode::find($qid);
      if(empty($qrcode->id)){
        abort(404);
      }
      $table = RTable::find($qrcode->table_id);


      Session::put('selected_table', $table->id);
      Session::put('selected_qr', $qrcode->id);
      if(empty(Session::get('dine_in_guest'))){
        Session::put('dine_in_guest', strtotime(date('Y-m-d H:i:s')).rand(100000, 999999));
      }

      return view('backend.dine_in.main_menu', ['table'=>$table]);
    }
}
