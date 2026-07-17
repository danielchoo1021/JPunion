<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Transaction;
use App\TransactionDetail;
use App\SettingAgentPackage;
use App\AffiliateCommission;
use App\SettingTeamDividend;
use App\SettingMerchantBonus;
use App\Product;
use App\Stock;
use App\PickupContact;
use App\User;
use App\ProductVariation;
use App\ProductSecondVariation;
use App\Agent;
use App\Admin;
use App\MemberPv;
use App\Affiliate;
use App\AdjustCashWallet;
use App\AdjustTopupWallet;
use App\TopupTransaction;
use App\WithdrawalTransaction;
use App\AdjustCashToTopup;

use App\Exports\RedemptionExport;
use App\Exports\OrderExport;
use App\Exports\PointOrderExport;
use App\Exports\SalesExport;
use App\Exports\AgentStock;
use App\Exports\CommissionExport;
use App\Exports\TopupWalletExport;
use App\Exports\CashWalletExport;
use App\Exports\StockDetailExport;
use App\Exports\StockExport;
use App\Exports\AgentSalesExport;
use App\Exports\SalesDetailsExport;
use Maatwebsite\Excel\Facades\Excel;
use Validator, Redirect, Toastr, DB, File, Auth, DateTime;

use App\Http\Controllers\GlobalController;

class ReportController extends Controller
{
    public function agent_stock_report()
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

  	  if(Auth::guard('admin')->check()){
          $transactions = Transaction::select(DB::raw('SUM(quantity) AS totalQty'), DB::raw('SUM(transactions.grand_total) AS totalGrand'), 
                                              DB::raw('SUM(transactions.shipping_fee) AS totalShippingFee'), 
                                              DB::raw('SUM(transactions.discount) AS totalDiscount'), 
                                              'd.item_code', 'd.product_code',
                                              DB::raw('CONCAT(m.f_name, " ", m.l_name) AS buyer_name'))
                                     ->join('merchants AS m', 'm.code', 'transactions.user_id')
                                     ->join('transaction_details AS d', 'd.transaction_id', 'transactions.id')
                                     ->where('transactions.status', '1')
                                     ->whereBetween(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), array($start, $end))
                                     ->groupBy('m.code')
                                     ->orderBy('transactions.created_at', 'desc');
      }else{
          $transactions = Transaction::select(DB::raw('COALESCE(CONCAT(m.f_name, " ", m.l_name), CONCAT(s.f_name, " ", s.l_name)) AS customer_name'),
                                              'transactions.transaction_no', 'product_name', 'unit_price', 'quantity', 'total_amount', 'transactions.status', 
                                              'transactions.created_at', 'd.sub_category',
                                              'transactions.id AS Tid', 'transactions.grand_total', 'transactions.shipping_fee')
                                     ->leftJoin('merchants AS m', 'm.code', 'transactions.user_id')
                                     ->leftJoin('users AS s', 's.code', 'transactions.user_id')
                                     ->join('transaction_details AS d', 'd.transaction_id', 'transactions.id')
                                     ->where('m.master_id', Auth::user()->code)
                                     ->orWhere('s.master_id', Auth::user()->code)
                                     ->groupBy('transactions.id')
                                     ->orderBy('transactions.created_at', 'desc');
      }

      $queries = [];
      $columns = [
          'item_code', 'dates', 'buyer', 'status'
      ];

      foreach($columns as $column){
          if(request()->has($column) && !empty(request($column))){
              if($column == 'status'){
                  $transactions = $transactions->where('transactions.status', 'like', "%".request($column)."%");
              }elseif($column == 'dates'){
                  $transactions = $transactions->whereBetween(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), array($start, $end));
              }elseif($column == 'buyer'){
                  $transactions = $transactions->where(DB::raw('CONCAT(m.f_name, " ", m.l_name)'), 'like', "%".request($column)."%");
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
      $transactions = $transactions->paginate($per_page)->appends($queries);

    	return view('backend.reports.agent_stock_report', ['transactions'=>$transactions, 'startDate'=>$startDate, 'endDate'=>$endDate]);
    }

    public function print_agent_stock_report()
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
        
        if(Auth::guard('admin')->check()){
            $transactions = Transaction::select(DB::raw('SUM(quantity) AS totalQty'), DB::raw('SUM(transactions.grand_total) AS totalGrand'), 
                                              DB::raw('SUM(transactions.shipping_fee) AS totalShippingFee'), 
                                              DB::raw('SUM(transactions.discount) AS totalDiscount'), 
                                              'd.item_code', 'd.product_code',
                                              DB::raw('CONCAT(m.f_name, " ", m.l_name) AS buyer_name'))
                                     ->join('merchants AS m', 'm.code', 'transactions.user_id')
                                     ->join('transaction_details AS d', 'd.transaction_id', 'transactions.id')
                                     ->where('transactions.status', '1')
                                     ->whereBetween(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), array($start, $end))
                                     ->groupBy('m.code')
                                     ->orderBy('transactions.created_at', 'desc');
        }else{
            $transactions = Transaction::select('quantity', 'transactions.grand_total', 'transactions.shipping_fee', 'transactions.discount', 'd.item_code', 'd.product_code')
                                       ->leftJoin('merchants AS m', 'm.code', 'transactions.user_id')
                                       ->leftJoin('users AS s', 's.code', 'transactions.user_id')
                                       ->join('transaction_details AS d', 'd.transaction_id', 'transactions.id')
                                       ->where('m.master_id', Auth::user()->code)
                                       ->orWhere('s.master_id', Auth::user()->code)
                                       ->groupBy('transactions.id')
                                       ->orderBy('transactions.created_at', 'desc');
        }

        $queries = [];
        $columns = [
            'transaction_no', 'dates', 'buyer', 'status'
        ];

        foreach($columns as $column){
            if(request()->has($column) && !empty(request($column))){
                if($column == 'status'){
                    $transactions = $transactions->where('transactions.status', 'like', "%".request($column)."%");
                }elseif($column == 'dates'){
                    $transactions = $transactions->whereBetween(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), array($start, $end));
                }elseif($column == 'buyer'){
                    $transactions = $transactions->where(DB::raw('CONCAT(m.f_name, " ", m.l_name)'), 'like', "%".request($column)."%");
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

        $transactions = $transactions->get();

        return view('backend.reports.print_agent_stock_report', ['transactions'=>$transactions, 'startDate'=>$startDate, 'endDate'=>$endDate]);
    }

    public function exportAgentStockReport()
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

        $item_code = "";
        if(!empty(request('item_code'))){
          $item_code = request('item_code');
        }

        $buyer = "";
        if(!empty(request('buyer'))){
          $buyer = request('buyer');
        }

        return Excel::download(new AgentStock($start, $end, $item_code, $buyer), 'AgentStockReport'.$start.' - '.$end.'.xlsx');
    }

    public function sales_report()
    {
        if(!empty(request('dates'))){

            $new_dates = explode('-', request('dates'));
            $start = DateTime::createFromFormat('d/m/Y', trim($new_dates[0]))->format('Y-m-d');
            $end = DateTime::createFromFormat('d/m/Y', trim($new_dates[1]))->format('Y-m-d');

            $startDate = trim($new_dates[0]);
            $endDate = trim($new_dates[1]);

        }else{

            $ds = new DateTime("first day of this month");
            $de = new DateTime("last day of this month");

            $start = $ds->format('Y-m-d');
            $end = $de->format('Y-m-d');

            $startDate = $ds->format('d/m/Y');
            $endDate = $de->format('d/m/Y');

        }

        $transactions = Transaction::select(DB::raw('SUM(quantity) AS totalQty'), 
                                            DB::raw('SUM(d.costing_price) AS costing_price'), 
                                            DB::raw('SUM(transactions.grand_total) AS totalGrand'), 
                                            DB::raw('SUM(transactions.shipping_fee) AS totalShippingFee'), 
                                            DB::raw('SUM(transactions.discount) AS totalDiscount'),
                                            DB::raw('SUM(d.unit_price * d.quantity) AS totalNet'),
                                            DB::raw("
                                                CASE
                                                    WHEN transactions.user_id LIKE 'AD%' THEN 'Agent'
                                                    WHEN transactions.user_id LIKE 'A%' THEN 'Agent'
                                                    WHEN transactions.user_id LIKE 'Mb%' THEN 'Member'
                                                    ELSE 'Guest'
                                                END AS get_pricing_type
                                            "),
                                            'd.item_code', 'd.product_code', 'd.unit_price', 'd.product_name', 'd.product_id')
                                   ->leftJoin('agents AS m', 'm.code', 'transactions.user_id')
                                   ->leftJoin('users AS u', 'u.code', 'transactions.user_id')
                                   ->leftJoin('admins AS a', 'a.code', 'transactions.user_id')
                                   ->join('transaction_details AS d', 'd.transaction_id', 'transactions.id')
                                   ->where('transactions.status', '1')
                                   ->whereNull('transactions.pv_purchase');
        if(empty(request('this_year'))){
        $transactions = $transactions->whereBetween(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), array($start, $end));
        }
        if(Auth::guard('merchant')->check()){
        $transactions = $transactions->where('d.merchant_id', Auth::user()->code);
        }
        $transactions = $transactions->groupBy(DB::raw("
                                                        CASE
                                                            WHEN transactions.user_id LIKE 'AD%' OR transactions.user_id LIKE 'A%' THEN 'Agent'
                                                            WHEN transactions.user_id LIKE 'Mb%' THEN 'Member'
                                                            ELSE 'Guest'
                                                        END
                                                    "), 'd.item_code')
                                    ->orderBy('d.item_code')
                                    ->orderByRaw("
                                                    CASE
                                                        WHEN transactions.user_id LIKE 'AD%' OR transactions.user_id LIKE 'A%' THEN 3
                                                        WHEN transactions.user_id LIKE 'Mb%' THEN 2
                                                        ELSE 1
                                                    END
                                                ")
                                    ->orderBy('d.product_name')
                                    ->orderByDesc('transactions.created_at');

        // $transactions = $transactions->groupBy('d.item_code')
        //                              ->orderBy('transactions.created_at', 'desc');

        $queries = [];
        $columns = [
            'transaction_no', 'dates', 'item_code', 'product_code', 'buyer', 'status', 'yearly', 'monthly','daily','today', 'this_month', 'this_year','this_daily_cost','this_monthly_cost','this_yearly_cost','this_daily_margin','this_monthly_margin','this_yearly_margin', 'per_page'
        ];

        $per_page = 10;
        if(!empty(request('per_page'))){
            $per_page = request('per_page');
        }

        foreach($columns as $column){
            if(request()->has($column) && !empty(request($column))){
                if($column == 'status'){
                    $transactions = $transactions->where('transactions.status', 'like', "%".request($column)."%");
                }elseif($column == 'dates'){
                    $transactions = $transactions->whereBetween(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), array($start, $end));
                }elseif($column == 'buyer'){
                    $transactions = $transactions->where(DB::raw('CONCAT(m.f_name, " ", m.l_name)'), 'like', "%".request($column)."%")
                                                 ->orWhere(DB::raw('CONCAT(a.f_name, " ", a.l_name)'), 'like', "%".request($column)."%")
                                                 ->orWhere(DB::raw('CONCAT(u.f_name, " ", u.l_name)'), 'like', "%".request($column)."%");
                }elseif($column == 'yearly'){
                    $transactions = $transactions->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y")'), request($column));
                }elseif($column == 'monthly'){
                    if(!empty(request('yearly'))){
                      $checkMonth = (strlen(request($column)) > 1) ? request($column) : '0'.request($column);
                      $transactions = $transactions->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m")'), request('yearly').'-'.$checkMonth);
                    }else{
                      $transactions = $transactions->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m")'), '2021-0'.request($column));
                    }
                }elseif($column == 'daily'){
                    if(!empty(request('yearly'))){
                        $searchYear = request('yearly');
                    }else{
                        $searchYear = date('Y');
                    }

                    if(!empty(request('monthly'))){
                        $searchMonth = (strlen(request('monthly')) > 1) ? request('monthly') : '0'.request('monthly');
                    }else{
                        $searchMonth = date('m');
                    }

                    $checkDay = (strlen(request($column)) > 1) ? request($column) : '0'.request($column);
                    
                    
                    $transactions = $transactions->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), $searchYear.'-'.$searchMonth.'-'.$checkDay);
                    
                }elseif($column == 'today' || $column == 'this_daily_cost' || $column == 'this_daily_margin'){
                    $transactions = $transactions->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), date('Y-m-d'));

                    $startYear = now();
                    $endYear = now();

                    $startDate = $startYear->format('d/m/Y');
                    $endDate = $endYear->format('d/m/Y');
                }elseif($column == 'this_month' || $column == 'this_monthly_cost' || $column == 'this_monthly_margin'){
                  $transactions = $transactions->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m")'), date('Y-m'));
                }elseif($column == 'this_year' || $column == 'this_yearly_cost' || $column == 'this_yearly_margin'){
                    $transactions = $transactions->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y")'), date('Y'));
                    
                    $startYear = now()->startOfYear();
                    $endYear = now()->endOfYear();

                    $startDate = $startYear->format('d/m/Y');
                    $endDate = $endYear->format('d/m/Y');
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

        $yearlySales = Transaction::select(DB::raw('SUM(transactions.grand_total - transactions.shipping_fee) as yearlySales'))
                                //   ->join('transaction_details as d', 'd.transaction_id', 'transactions.id')
                                  ->where('transactions.status', '1')
                                  ->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y")'), date('Y'));
        if(Auth::guard('merchant')->check()){
        $yearlySales = $yearlySales->where('d.merchant_id', Auth::user()->code);
        }
        $yearlySales = $yearlySales->first();

        $monthlySales = Transaction::select(DB::raw('SUM(grand_total - shipping_fee) as monthlySales'))
                                //   ->join('transaction_details as d', 'd.transaction_id', 'transactions.id')
                                  ->where('transactions.status', '1')
                                  ->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m")'), date('Y-m'));
        if(Auth::guard('merchant')->check()){
        $monthlySales = $monthlySales->where('d.merchant_id', Auth::user()->code);
        }
        $monthlySales = $monthlySales->first();

        $dailySales = Transaction::select(DB::raw('SUM(grand_total - processing_fee - shipping_fee) as dailySales'))
                                //   ->join('transaction_details as d', 'd.transaction_id', 'transactions.id')
                                  ->where('transactions.status', '1')
                                  ->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), date('Y-m-d'));
        if(Auth::guard('merchant')->check()){
        $dailySales = $dailySales->where('d.merchant_id', Auth::user()->code);
        }
        $dailySales = $dailySales->first();

        $yearlyCost = Transaction::select(DB::raw('SUM(costing_price * quantity) as yearlyCost'))
                                  ->join('transaction_details as d', 'd.transaction_id', 'transactions.id')
                                  ->where('transactions.status', '1')
                                  ->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y")'), date('Y'));
        if(Auth::guard('merchant')->check()){
        $yearlyCost = $yearlyCost->where('d.merchant_id', Auth::user()->code);
        }
        $yearlyCost = $yearlyCost->first();

        $monthlyCost = Transaction::select(DB::raw('SUM(costing_price * quantity) as monthlyCost'))
                                  ->join('transaction_details as d', 'd.transaction_id', 'transactions.id')
                                  ->where('transactions.status', '1')
                                  ->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m")'), date('Y-m'));
        if(Auth::guard('merchant')->check()){
        $monthlyCost = $monthlyCost->where('d.merchant_id', Auth::user()->code);
        }
        $monthlyCost = $monthlyCost->first();

        $dailyCost = Transaction::select(DB::raw('SUM(costing_price * quantity) as dailyCost'))
                                  ->join('transaction_details as d', 'd.transaction_id', 'transactions.id')
                                  ->where('transactions.status', '1')
                                  ->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), date('Y-m-d'));
        if(Auth::guard('merchant')->check()){
        $dailyCost = $dailyCost->where('d.merchant_id', Auth::user()->code);
        }
        $dailyCost = $dailyCost->first();

        return view('backend.reports.sales_report', ['transactions'=>$transactions, 'startDate'=>$startDate, 'endDate'=>$endDate,
                                                     'yearlySales'=>$yearlySales, 'monthlySales'=>$monthlySales, 
                                                     'dailySales'=>$dailySales, 'yearlyCost'=>$yearlyCost,
                                                     'monthlyCost'=>$monthlyCost, 'dailyCost'=>$dailyCost]);
    }

    public function print_sales_report()
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

        //   $dailytoday = date('Y-m-d');
        //   $dailytomrrow = date("Y-m-d", strtotime("+1 day"));

        //   $dailyStart = $dailytoday->format('d/m/Y');
        //   $dailyEnd = $dailytomrrow->format('d/m/Y');

        //   $year = date('Y') - 1; // Get current year and subtract 1
        //   $start = "January 1st, {$year}";
        //   $end = "December 31st, {$year}";
        }
        
        $transactions = Transaction::select(DB::raw('SUM(quantity) AS totalQty'), 
                                            DB::raw('SUM(d.costing_price) AS costing_price'), 
                                            DB::raw('SUM(transactions.grand_total) AS totalGrand'), 
                                            DB::raw('SUM(transactions.shipping_fee) AS totalShippingFee'), 
                                            DB::raw('SUM(transactions.discount) AS totalDiscount'),
                                            DB::raw('SUM(d.unit_price * d.quantity) AS totalNet'),
                                            DB::raw("
                                                CASE
                                                    WHEN transactions.user_id LIKE 'AD%' THEN 'Agent'
                                                    WHEN transactions.user_id LIKE 'A%' THEN 'Agent'
                                                    WHEN transactions.user_id LIKE 'Mb%' THEN 'Member'
                                                    ELSE 'Guest'
                                                END AS get_pricing_type
                                            "),
                                            'd.item_code', 'd.product_code', 'd.unit_price', 'd.product_name')
                                   ->leftJoin('agents AS m', 'm.code', 'transactions.user_id')
                                   ->leftJoin('users AS u', 'u.code', 'transactions.user_id')
                                   ->leftJoin('admins AS a', 'a.code', 'transactions.user_id')
                                   ->join('transaction_details AS d', 'd.transaction_id', 'transactions.id')
                                   ->where('transactions.status', '1')
                                   ->whereNull('transactions.pv_purchase');
        // if(empty(request('this_year'))){
        // $transactions = $transactions->whereBetween(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), array($start, $end));
        // }
        if(Auth::guard('merchant')->check()){
        $transactions = $transactions->where('d.merchant_id', Auth::user()->code);
        }
        $transactions = $transactions->groupBy(DB::raw("
                                                        CASE
                                                            WHEN transactions.user_id LIKE 'AD%' OR transactions.user_id LIKE 'A%' THEN 'Agent'
                                                            WHEN transactions.user_id LIKE 'Mb%' THEN 'Member'
                                                            ELSE 'Guest'
                                                        END
                                                    "), 'd.item_code')
                                    ->orderBy('d.item_code')
                                    ->orderByRaw("
                                                    CASE
                                                        WHEN transactions.user_id LIKE 'AD%' OR transactions.user_id LIKE 'A%' THEN 3
                                                        WHEN transactions.user_id LIKE 'Mb%' THEN 2
                                                        ELSE 1
                                                    END
                                                ")
                                    ->orderBy('d.product_name')
                                    ->orderByDesc('transactions.created_at');

        // $transactions = $transactions->groupBy('d.item_code')
        //                              ->orderBy('transactions.created_at', 'desc');

        $queries = [];
        $columns = [
            'transaction_no', 'dates', 'item_code', 'product_code', 'buyer', 'status', 'yearly', 'monthly','daily','today', 'this_month', 'this_year', 'per_page'
        ];

        $per_page = 10;
        if(!empty(request('per_page'))){
            $per_page = request('per_page');
        }

        foreach($columns as $column){
            if(request()->has($column) && !empty(request($column))){
                if($column == 'status'){
                    $transactions = $transactions->where('transactions.status', 'like', "%".request($column)."%");
                }elseif($column == 'dates'){
                    if(empty(request('yearly')) && empty(request('monthly')) && empty(request('daily'))){
                        $transactions = $transactions->whereBetween(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), array($start, $end));
                    }
                }elseif($column == 'buyer'){
                    $transactions = $transactions->where(DB::raw('CONCAT(m.f_name, " ", m.l_name)'), 'like', "%".request($column)."%")
                                                 ->orWhere(DB::raw('CONCAT(a.f_name, " ", a.l_name)'), 'like', "%".request($column)."%")
                                                 ->orWhere(DB::raw('CONCAT(u.f_name, " ", u.l_name)'), 'like', "%".request($column)."%");
                }elseif($column == 'yearly'){
                    $transactions = $transactions->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y")'), request($column));


                    $startYear = now()->startOfYear();
                    $endYear = now()->endOfYear();

                    $startDate = $startYear->format('d/m/Y');
                    $endDate = $endYear->format('d/m/Y');
                }elseif($column == 'monthly'){
                    // if(!empty(request('yearly'))){
                    //   $checkMonth = (strlen(request($column)) > 1) ? request($column) : '0'.request($column);
                    //   $transactions = $transactions->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m")'), request('yearly').'-'.$checkMonth);
                    // }else{
                      $transactions = $transactions->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m")'), request($column));
                    // }
                }elseif($column == 'daily'){
                    // if(!empty(request('yearly'))){
                    //     $searchYear = request('yearly');
                    // }else{
                    //     $searchYear = date('Y');
                    // }

                    // if(!empty(request('monthly'))){
                    //     $searchMonth = (strlen(request('monthly')) > 1) ? request('monthly') : '0'.request('monthly');
                    // }else{
                    //     $searchMonth = date('m');
                    // }

                    $checkDay = (strlen(request($column)) > 1) ? request($column) : '0'.request($column);
                    
                    $transactions = $transactions->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), $checkDay);
                    
                    $startYear = now();
                    $endYear = now();

                    $startDate = $startYear->format('d/m/Y');
                    $endDate = $endYear->format('d/m/Y');
                }elseif($column == 'today'){
                  $transactions = $transactions->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), date('Y-m-d'));
                }elseif($column == 'this_month'){
                  $transactions = $transactions->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m")'), date('Y-m'));
                }elseif($column == 'this_year'){
                  $transactions = $transactions->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y")'), date('Y'));
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

        return view('backend.reports.print_sales_report', ['transactions'=>$transactions, 'startDate'=>$startDate, 'endDate'=>$endDate]);
    }

    public function exportSales()
    {
        if(!empty(request('dates'))){

            $new_dates = explode('-', request('dates'));
            $start = DateTime::createFromFormat('d/m/Y', trim($new_dates[0]))->format('Y-m-d');
            $end = DateTime::createFromFormat('d/m/Y', trim($new_dates[1]))->format('Y-m-d');

            $startDate = trim($new_dates[0]);
            $endDate = trim($new_dates[1]);

        }else{

            $ds = new DateTime("first day of this month");
            $de = new DateTime("last day of this month");

            $start = $ds->format('Y-m-d');
            $end = $de->format('Y-m-d');

            $startDate = $ds->format('d/m/Y');
            $endDate = $de->format('d/m/Y');

        }

        
        $item_code = "";
        if(!empty(request('item_code'))){
          $item_code = request('item_code');
        }

        $product_code = "";
        if(!empty(request('product_code'))){
          $product_code = request('product_code');
        }

        $checkYear = "";
        if(!empty(request('yearly'))){
            $checkYear = request('yearly');
        }

        $checkMonth = "";
        if(!empty(request('monthly'))){
            $checkMonth = (strlen(request('monthly')) > 1) ? request('monthly') : '0'.request('monthly');
        }

        $checkDay = "";
        if(!empty(request('daily'))){
            $checkDay = (strlen(request('daily')) > 1) ? request('daily') : '0'.request('daily');            
        }

        $per_page = 10;
        if(!empty(request('per_page'))){
            $per_page = request('per_page');
        }

        return Excel::download(new SalesExport($start, $end, $item_code, $product_code, $checkYear, $checkMonth, $checkDay, $per_page), date('Y-m-d').' '.'Profit Report.xlsx');
    }

    public function sales_report_details($product_id, $pricing_type)
    {
        if(!empty(request('dates'))){

            $new_dates = explode('-', request('dates'));
            $start = DateTime::createFromFormat('d/m/Y', trim($new_dates[0]))->format('Y-m-d');
            $end = DateTime::createFromFormat('d/m/Y', trim($new_dates[1]))->format('Y-m-d');

            $startDate = trim($new_dates[0]);
            $endDate = trim($new_dates[1]);

        }else{

            $ds = new DateTime("first day of this month");
            $de = new DateTime("last day of this month");

            $start = $ds->format('Y-m-d');
            $end = $de->format('Y-m-d');

            $startDate = $ds->format('d/m/Y');
            $endDate = $de->format('d/m/Y');

        }

        $transactions = Transaction::select(DB::raw("
                                                    CASE
                                                        WHEN transactions.user_id LIKE 'AD%' THEN 'Agent'
                                                        WHEN transactions.user_id LIKE 'A%' THEN 'Agent'
                                                        WHEN transactions.user_id LIKE 'Mb%' THEN 'Member'
                                                        ELSE 'Guest'
                                                    END AS get_pricing_type
                                                "),
                                                'transactions.transaction_no',
                                                'd.item_code', 'd.product_code', 'd.unit_price', 'd.product_name', 'd.product_id', 'd.quantity', 'd.costing_price')
                                    ->leftJoin('agents AS m', 'm.code', 'transactions.user_id')
                                    ->leftJoin('users AS u', 'u.code', 'transactions.user_id')
                                    ->leftJoin('admins AS a', 'a.code', 'transactions.user_id')
                                    ->join('transaction_details AS d', 'd.transaction_id', 'transactions.id')
                                    ->where('transactions.status', '1')
                                    ->where(DB::raw('md5(d.product_id)'), $product_id);
        if(empty(request('this_year'))){
        $transactions = $transactions->whereBetween(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), array($start, $end));
        }
        if(Auth::guard('merchant')->check()){
        $transactions = $transactions->where('d.merchant_id', Auth::user()->code);
        }
        $transactions = $transactions->having('get_pricing_type', '=', $pricing_type)
                                     ->orderBy('d.item_code')
                                     ->orderBy('d.product_name')
                                     ->orderByDesc('transactions.created_at');

        // $transactions = $transactions->groupBy('d.item_code')
        //                              ->orderBy('transactions.created_at', 'desc');

        $queries = [];
        $columns = [
            'transaction_no', 'dates', 'item_code', 'product_code', 'buyer', 'status', 'yearly', 'monthly','daily','today', 'this_month', 'this_year', 'per_page'
        ];

        $per_page = 10;
        if(!empty(request('per_page'))){
            $per_page = request('per_page');
        }

        foreach($columns as $column){
            if(request()->has($column) && !empty(request($column))){
                if($column == 'status'){
                    $transactions = $transactions->where('transactions.status', 'like', "%".request($column)."%");
                }elseif($column == 'dates'){
                    $transactions = $transactions->whereBetween(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), array($start, $end));
                }elseif($column == 'buyer'){
                    $transactions = $transactions->where(DB::raw('CONCAT(m.f_name, " ", m.l_name)'), 'like', "%".request($column)."%")
                                                 ->orWhere(DB::raw('CONCAT(a.f_name, " ", a.l_name)'), 'like', "%".request($column)."%")
                                                 ->orWhere(DB::raw('CONCAT(u.f_name, " ", u.l_name)'), 'like', "%".request($column)."%");
                }elseif($column == 'yearly'){
                    $transactions = $transactions->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y")'), request($column));
                }elseif($column == 'monthly'){
                    if(!empty(request('yearly'))){
                      $checkMonth = (strlen(request($column)) > 1) ? request($column) : '0'.request($column);
                      $transactions = $transactions->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m")'), request('yearly').'-'.$checkMonth);
                    }else{
                      $transactions = $transactions->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m")'), '2021-0'.request($column));
                    }
                }elseif($column == 'daily'){
                    if(!empty(request('yearly'))){
                        $searchYear = request('yearly');
                    }else{
                        $searchYear = date('Y');
                    }

                    if(!empty(request('monthly'))){
                        $searchMonth = (strlen(request('monthly')) > 1) ? request('monthly') : '0'.request('monthly');
                    }else{
                        $searchMonth = date('m');
                    }

                    $checkDay = (strlen(request($column)) > 1) ? request($column) : '0'.request($column);
                    
                    
                    $transactions = $transactions->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), $searchYear.'-'.$searchMonth.'-'.$checkDay);
                    
                }elseif($column == 'today'){
                  $transactions = $transactions->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), date('Y-m-d'));
                }elseif($column == 'this_month'){
                  $transactions = $transactions->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m")'), date('Y-m'));
                }elseif($column == 'this_year'){
                  $transactions = $transactions->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y")'), date('Y'));
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

        return view('backend.reports.sales_report_details', ['transactions'=>$transactions, 
                                                             'startDate'=>$startDate, 
                                                             'endDate'=>$endDate, 
                                                             'product_id'=>$product_id, 
                                                             'pricing_type'=>$pricing_type]);
    }

    public function print_sales_report_details(Request $request)
    {
        $product_id = $request->pid ?? NULL;
        $pricing_type = $request->price_type ?? NULL;

        if(!empty(request('dates'))){

            $new_dates = explode('-', request('dates'));
            $start = DateTime::createFromFormat('d/m/Y', trim($new_dates[0]))->format('Y-m-d');
            $end = DateTime::createFromFormat('d/m/Y', trim($new_dates[1]))->format('Y-m-d');

            $startDate = trim($new_dates[0]);
            $endDate = trim($new_dates[1]);

        }else{

            $ds = new DateTime("first day of this month");
            $de = new DateTime("last day of this month");

            $start = $ds->format('Y-m-d');
            $end = $de->format('Y-m-d');

            $startDate = $ds->format('d/m/Y');
            $endDate = $de->format('d/m/Y');

        }
        
        $transactions = Transaction::select(DB::raw("
                                                    CASE
                                                        WHEN transactions.user_id LIKE 'AD%' THEN 'Agent'
                                                        WHEN transactions.user_id LIKE 'A%' THEN 'Agent'
                                                        WHEN transactions.user_id LIKE 'Mb%' THEN 'Member'
                                                        ELSE 'Guest'
                                                    END AS get_pricing_type
                                                "),
                                                'transactions.transaction_no',
                                                'd.item_code', 'd.product_code', 'd.unit_price', 'd.product_name', 'd.product_id', 'd.quantity', 'd.costing_price')
                                    ->leftJoin('agents AS m', 'm.code', 'transactions.user_id')
                                    ->leftJoin('users AS u', 'u.code', 'transactions.user_id')
                                    ->leftJoin('admins AS a', 'a.code', 'transactions.user_id')
                                    ->join('transaction_details AS d', 'd.transaction_id', 'transactions.id')
                                    ->where('transactions.status', '1')
                                    ->whereNull('transactions.pv_purchase')
                                    ->where(DB::raw('md5(d.product_id)'), $product_id);
        if(empty(request('this_year'))){
        $transactions = $transactions->whereBetween(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), array($start, $end));
        }
        if(Auth::guard('merchant')->check()){
        $transactions = $transactions->where('d.merchant_id', Auth::user()->code);
        }
        $transactions = $transactions->having('get_pricing_type', '=', $pricing_type)
                                     ->orderBy('d.item_code')
                                     ->orderBy('d.product_name')
                                     ->orderByDesc('transactions.created_at');

        // $transactions = $transactions->groupBy('d.item_code')
        //                              ->orderBy('transactions.created_at', 'desc');

        $queries = [];
        $columns = [
            'transaction_no', 'dates', 'item_code', 'product_code', 'buyer', 'status', 'yearly', 'monthly','daily','today', 'this_month', 'this_year', 'per_page'
        ];

        $per_page = 10;
        if(!empty(request('per_page'))){
            $per_page = request('per_page');
        }

        foreach($columns as $column){
            if(request()->has($column) && !empty(request($column))){
                if($column == 'status'){
                    $transactions = $transactions->where('transactions.status', 'like', "%".request($column)."%");
                }elseif($column == 'dates'){
                    $transactions = $transactions->whereBetween(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), array($start, $end));
                }elseif($column == 'buyer'){
                    $transactions = $transactions->where(DB::raw('CONCAT(m.f_name, " ", m.l_name)'), 'like', "%".request($column)."%")
                                                 ->orWhere(DB::raw('CONCAT(a.f_name, " ", a.l_name)'), 'like', "%".request($column)."%")
                                                 ->orWhere(DB::raw('CONCAT(u.f_name, " ", u.l_name)'), 'like', "%".request($column)."%");
                }elseif($column == 'yearly'){
                    $transactions = $transactions->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y")'), request($column));
                }elseif($column == 'monthly'){
                    if(!empty(request('yearly'))){
                      $checkMonth = (strlen(request($column)) > 1) ? request($column) : '0'.request($column);
                      $transactions = $transactions->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m")'), request('yearly').'-'.$checkMonth);
                    }else{
                      $transactions = $transactions->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m")'), '2021-0'.request($column));
                    }
                }elseif($column == 'daily'){
                    if(!empty(request('yearly'))){
                        $searchYear = request('yearly');
                    }else{
                        $searchYear = date('Y');
                    }

                    if(!empty(request('monthly'))){
                        $searchMonth = (strlen(request('monthly')) > 1) ? request('monthly') : '0'.request('monthly');
                    }else{
                        $searchMonth = date('m');
                    }

                    $checkDay = (strlen(request($column)) > 1) ? request($column) : '0'.request($column);
                    
                    
                    $transactions = $transactions->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), $searchYear.'-'.$searchMonth.'-'.$checkDay);
                    
                }elseif($column == 'today'){
                  $transactions = $transactions->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), date('Y-m-d'));
                }elseif($column == 'this_month'){
                  $transactions = $transactions->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m")'), date('Y-m'));
                }elseif($column == 'this_year'){
                  $transactions = $transactions->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y")'), date('Y'));
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

        return view('backend.reports.print_sales_report_details', ['transactions'=>$transactions, 
                                                                   'startDate'=>$startDate, 
                                                                   'endDate'=>$endDate, 
                                                                   'product_id'=>$product_id, 
                                                                   'pricing_type'=>$pricing_type]);
    }

    public function exportSalesDetails(Request $request)
    {
        $product_id = $request->pid ?? NULL;
        $pricing_type = $request->price_type ?? NULL;

        if(!empty(request('dates'))){

            $new_dates = explode('-', request('dates'));
            $start = DateTime::createFromFormat('d/m/Y', trim($new_dates[0]))->format('Y-m-d');
            $end = DateTime::createFromFormat('d/m/Y', trim($new_dates[1]))->format('Y-m-d');

            $startDate = trim($new_dates[0]);
            $endDate = trim($new_dates[1]);

        }else{

            $ds = new DateTime("first day of this month");
            $de = new DateTime("last day of this month");

            $start = $ds->format('Y-m-d');
            $end = $de->format('Y-m-d');

            $startDate = $ds->format('d/m/Y');
            $endDate = $de->format('d/m/Y');

        }

        
        $item_code = "";
        if(!empty(request('item_code'))){
          $item_code = request('item_code');
        }

        $product_code = "";
        if(!empty(request('product_code'))){
          $product_code = request('product_code');
        }

        $checkYear = "";
        if(!empty(request('yearly'))){
            $checkYear = request('yearly');
        }

        $checkMonth = "";
        if(!empty(request('monthly'))){
            $checkMonth = (strlen(request('monthly')) > 1) ? request('monthly') : '0'.request('monthly');
        }

        $checkDay = "";
        if(!empty(request('daily'))){
            $checkDay = (strlen(request('daily')) > 1) ? request('daily') : '0'.request('daily');            
        }

        $per_page = 10;
        if(!empty(request('per_page'))){
            $per_page = request('per_page');
        }

        return Excel::download(new SalesDetailsExport($start, $end, $item_code, $product_code, $checkYear, $checkMonth, $checkDay, $per_page, $product_id, $pricing_type), date('Y-m-d').' '.'Order Details Report.xlsx');
    }

    public function order_report()
    {
      $defaultYear = date('Y');

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

    //   if(!empty(request('yearly'))){
    //       if(!empty(request('monthly'))){
    //           if(!empty(request('today'))){
    //               $start = date('Y-m-d', strtotime(request('yearly'). '-' .request('monthly'). '-' .request('daily')));
    //               $end = date('Y-m-d', strtotime(request('yearly'). '-' .request('monthly'). '-' .request('daily')));

    //               $startDate = date('Y-m-d H:i:s', strtotime(request('yearly'). '-' .request('monthly'). '-' .request('daily')));
    //               $endDate = date('Y-m-d H:i:s', strtotime(request('yearly'). '-' .request('monthly'). '-' .request('daily')));
    //           }else{
    //               $start = date('Y-m-d', strtotime(request('yearly'). '-' .request('monthly'). '-01'));
    //               $end = date('Y-m-t', strtotime(request('yearly'). '-' .request('monthly')));

    //               $startDate = date('Y-m-d', strtotime(request('yearly'). '-' .request('monthly'). '-01'));
    //               $endDate = date('Y-m-t', strtotime(request('yearly'). '-' .request('monthly')));
    //           }
    //       }else{
    //           $start = date('Y-m-d', strtotime(request('yearly'). '-01-01'));
    //           $end = date('Y-m-d', strtotime(request('yearly'). '-12-31'));

    //           $startDate = date('Y-m-d', strtotime(request('yearly'). '-01-01'));
    //           $endDate = date('Y-m-d', strtotime(request('yearly'). '-12-31'));
    //       }
    //   }

        $transactions = Transaction::select(DB::raw('CASE 
                                                     WHEN m.id != "" THEN COALESCE(CONCAT(m.f_name, " ", m.l_name), m.phone)
                                                     WHEN a.id != "" THEN COALESCE(CONCAT(a.f_name, " ", a.l_name), a.phone)
                                                     WHEN u.id != "" THEN COALESCE(CONCAT(u.f_name, " ", u.l_name), u.phone)
                                                 END AS buyer_name'),
                                                DB::raw('CASE 
                                                     WHEN m.id != "" THEN "Agent"
                                                     WHEN a.id != "" THEN "Admin"
                                                     WHEN u.id != "" THEN "Customer"
                                                 END AS buyer_role'),
                                                DB::raw('CASE 
                                                    WHEN m.id != "" THEN m.code
                                                    WHEN a.id != "" THEN a.code
                                                    WHEN u.id != "" THEN u.code
                                                END as buyer_code'),
                                        'transactions.transaction_no', 'transactions.status', 'transactions.created_at','transactions.mall',
                                        'transactions.id AS Tid', 'transactions.grand_total', 'transactions.shipping_fee', 
                                        'transactions.processing_fee', 'transactions.discount', 'transactions.address_name',
                                        'transactions.ad_discount')
                               ->leftJoin('agents AS m', 'm.code', 'transactions.user_id')
                               ->leftJoin('admins AS a', 'a.code', 'transactions.user_id')
                               ->leftJoin('users AS u', 'u.code', 'transactions.user_id')
                               ->join('transaction_details as d', 'd.transaction_id', 'transactions.id')
                               ->where('transactions.status', '1')
                            //    ->whereNull('transactions.mall')
                               ->whereNull('transactions.pv_purchase')
                               ->groupBy('transactions.id');
        if(Auth::guard('merchant')->check()){
        $transactions = $transactions->where('d.merchant_id', Auth::user()->code);
        }
        if(empty(request('this_year'))){
            $transactions = $transactions->whereBetween(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), array($start, $end));
        }
        $transactions = $transactions->orderBy('transactions.created_at', 'desc');

        $totalT = Transaction::select(DB::raw('SUM(d.quantity) as totalQty'),
                                      DB::raw('SUM(d.unit_price) as totalUnitPrice'),
                                      DB::raw('SUM(d.unit_price * d.quantity) as totalNet'),
                                      DB::raw('SUM((d.unit_price * d.quantity) + transactions.processing_fee + transactions.shipping_fee + transactions.tax) as totalGrand'))
                                   ->join('transaction_details AS d', 'd.transaction_id', 'transactions.id')
                                   ->where('transactions.status', '1')
                                   ->whereNull('transactions.pv_purchase')
                                   ->whereNull('transactions.mall');
        if(empty(request('this_year'))){
        $totalT = $totalT->whereBetween(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), array($start, $end));
        }
        if(!empty(request('today'))){
          $totalT = $totalT->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), date('Y-m-d'));
        }

        if(!empty(request('item_code'))){
            $totalT = $totalT->where('d.item_code', 'like', "%".request('item_code')."%");
        }

        if(!empty(request('product_code'))){
            $totalT = $totalT->where('d.product_code', 'like', "%".request('product_code')."%");
        }

        if(!empty(request('transaction_no'))){
            $totalT = $totalT->where('transactions.transaction_no', 'like', "%".request('transaction_no')."%");
        }

        if(!empty(request('buyer'))){
            $totalT = $totalT->leftJoin('agents AS m', 'm.code', 'transactions.user_id')
            ->leftJoin('admins AS a', 'a.code', 'transactions.user_id')
            ->leftJoin('users AS u', 'u.code', 'transactions.user_id');

             $term = '%' . request('buyer') . '%';

            $totalT = $totalT->whereRaw("
                CONVERT(CONCAT(m.f_name, ' ', m.l_name) USING utf8mb4) COLLATE utf8mb4_unicode_ci LIKE ?
            ", [$term])
            ->orWhereRaw("
                CONVERT(CONCAT(a.f_name, ' ', a.l_name) USING utf8mb4) COLLATE utf8mb4_unicode_ci LIKE ?
            ", [$term])
            ->orWhereRaw("
                CONVERT(CONCAT(u.f_name, ' ', u.l_name) USING utf8mb4) COLLATE utf8mb4_unicode_ci LIKE ?
            ", [$term]);
        }
        
        if(Auth::guard('merchant')->check()){
        $totalT = $totalT->where('d.merchant_id', Auth::user()->code);
        }

        $totalT = $totalT->orderBy('transactions.created_at', 'desc');

        $totalT2 = Transaction::select(DB::raw('SUM(transactions.processing_fee) as totalProcessingFee'),
                                      DB::raw('SUM(transactions.shipping_fee) as totalShippingFee'),
                                      DB::raw('SUM(transactions.tax) as totalTax'),
                                      DB::raw('SUM(transactions.discount) as totalDiscount'),
                                      DB::raw('SUM(transactions.ad_discount) as totalAdDiscount'))
                            //   ->join('transaction_details as d', 'd.transaction_id', 'transactions.id')
                              ->where('transactions.status', '1')
                              ->whereNull('transactions.mall')
                              ->whereNull('transactions.pv_purchase');

        if(empty(request('this_year'))){
        $totalT2 = $totalT2->whereBetween(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), array($start, $end));
        }
        if(!empty(request('today'))){
          $totalT2 = $totalT2->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), date('Y-m-d'));
        }

        if(!empty(request('item_code')) || !empty(request('product_code'))){
            $totalT2 = $totalT2->join('transaction_details as d', 'd.transaction_id', 'transactions.id');

            if(!empty(request('item_code'))){
                $totalT2 = $totalT2->where('d.item_code', 'like', "%".request('item_code')."%");
            }
            
            if(!empty(request('product_code'))){
                 $totalT2 = $totalT2->where('d.product_code', 'like', "%".request('product_code')."%");
            }
        }

        if(!empty(request('transaction_no'))){
            $totalT2 = $totalT2->where('transactions.transaction_no', 'like', "%".request('transaction_no')."%");
        }

        if(!empty(request('buyer'))){
            $totalT2 = $totalT2->leftJoin('agents AS m', 'm.code', 'transactions.user_id')
            ->leftJoin('admins AS a', 'a.code', 'transactions.user_id')
            ->leftJoin('users AS u', 'u.code', 'transactions.user_id');

            $term = '%' . request('buyer') . '%';

            $totalT2 = $totalT2->whereRaw("
                CONVERT(CONCAT(m.f_name, ' ', m.l_name) USING utf8mb4) COLLATE utf8mb4_unicode_ci LIKE ?
            ", [$term])
            ->orWhereRaw("
                CONVERT(CONCAT(a.f_name, ' ', a.l_name) USING utf8mb4) COLLATE utf8mb4_unicode_ci LIKE ?
            ", [$term])
            ->orWhereRaw("
                CONVERT(CONCAT(u.f_name, ' ', u.l_name) USING utf8mb4) COLLATE utf8mb4_unicode_ci LIKE ?
            ", [$term]);
        }

        if(Auth::guard('merchant')->check()){
            $totalT2 = $totalT2->where('d.merchant_id', Auth::user()->code);
        }
        $totalT2 = $totalT2->orderBy('transactions.created_at', 'desc');

        $queries = [];
        $columns = [
            'transaction_no', 'transaction_type', 'dates', 'buyer', 'item_code', 'product_code', 'status', 'yearly', 'monthly','daily', 'today', 'this_month', 'this_year', 'per_page'
        ];

        $per_page = 10;
        if(!empty(request('per_page'))){
            $per_page = request('per_page');
        }

        foreach($columns as $column){
            if(request()->has($column) && !empty(request($column))){
                if($column == 'status'){
                    $transactions = $transactions->where('transactions.status', 'like', "%".request($column)."%");
                }elseif($column == 'dates'){
                    if(!empty(request('yearly')) && !empty(request('monthly')) && !empty(request('daily'))){
                        $transactions = $transactions->whereBetween(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), array($start, $end));
                    }
                }elseif($column == 'buyer'){
                    $term = '%' . request($column) . '%';

                    $transactions = $transactions->whereRaw("
                        CONVERT(CONCAT(m.f_name, ' ', m.l_name) USING utf8mb4) COLLATE utf8mb4_unicode_ci LIKE ?
                    ", [$term])
                    ->orWhereRaw("
                        CONVERT(CONCAT(a.f_name, ' ', a.l_name) USING utf8mb4) COLLATE utf8mb4_unicode_ci LIKE ?
                    ", [$term])
                    ->orWhereRaw("
                        CONVERT(CONCAT(u.f_name, ' ', u.l_name) USING utf8mb4) COLLATE utf8mb4_unicode_ci LIKE ?
                    ", [$term]);
                }elseif($column == 'yearly'){
                    $transactions = $transactions->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y")'), request($column));
                }elseif($column == 'monthly'){
                    if(!empty(request('yearly'))){
                      $checkMonth = (strlen(request($column)) > 1) ? request($column) : '0'.request($column);
                      $transactions = $transactions->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m")'), request('yearly').'-'.$checkMonth);
                    }else{
                      $transactions = $transactions->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m")'), '2021-0'.request($column));
                    }
                }elseif($column == 'daily'){
                    if(!empty(request('yearly'))){
                        $searchYear = request('yearly');
                    }else{
                        $searchYear = date('Y');
                    }

                    if(!empty(request('monthly'))){
                        $searchMonth = (strlen(request('monthly')) > 1) ? request('monthly') : '0'.request('monthly');
                    }else{
                        $searchMonth = date('m');
                    }

                    $checkDay = (strlen(request($column)) > 1) ? request($column) : '0'.request($column);
                    
                    
                    $transactions = $transactions->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), $searchYear.'-'.$searchMonth.'-'.$checkDay);
                    
                }elseif($column == 'today'){
                    $transactions = $transactions->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), date('Y-m-d'));

                    $startYear = now();
                    $endYear = now();

                    $startDate = $startYear->format('d/m/Y');
                    $endDate = $endYear->format('d/m/Y');
                }elseif($column == 'this_month'){
                    $transactions = $transactions->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m")'), date('Y-m'));
                }elseif($column == 'this_year'){
                    $transactions = $transactions->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y")'), date('Y'));

                    $startYear = now()->startOfYear();
                    $endYear = now()->endOfYear();

                    $startDate = $startYear->format('d/m/Y');
                    $endDate = $endYear->format('d/m/Y');
                }elseif($column == 'per_page'){
                  $transactions = $transactions->paginate($per_page);
                }elseif($column == 'item_code'){
                    $transactions = $transactions->where('d.item_code', 'like', "%".request($column)."%");
                }elseif($column == 'product_code'){
                    $transactions = $transactions->where('d.product_code', 'like', "%".request($column)."%");
                }elseif($column == 'transaction_type'){
                   if(request($column) == '1'){
                    $transactions = $transactions->whereNull('transactions.mall');
                   }elseif(request($column) == '2'){
                    $transactions = $transactions->where('transactions.mall', 1);
                   }elseif(request($column) == '3'){
                    $transactions = $transactions->where('transactions.mall', 2);
                   }
                }else{
                    $transactions = $transactions->where($column, 'like', "%".request($column)."%");
                }

                $queries[$column] = request($column);

            }
        }
        // return $transactions->toSql();
        
        if(!empty(request('per_page'))){
            $transactions = $transactions->appends($queries);        
        }else{
            $transactions = $transactions->paginate($per_page)->appends($queries);        
        }

        $totalT = $totalT->first();
        $totalT2 = $totalT2->first();

        $details = [];
        $details2 = [];
        foreach($transactions as $transaction){
            $details[$transaction->Tid] = TransactionDetail::where("transaction_id", $transaction->Tid);
            if(Auth::guard('merchant')->check()){
                $details[$transaction->Tid] = $details[$transaction->Tid]->where('merchant_id', Auth::user()->code);
            }
            if(!empty(request('item_code'))){
                $details[$transaction->Tid] = $details[$transaction->Tid]->where('item_code', 'like', "%".request('item_code')."%");
            }
            if(!empty(request('product_code'))){
                $details[$transaction->Tid] = $details[$transaction->Tid]->where('product_code', 'like', "%".request('product_code')."%");
            }
            $details[$transaction->Tid] = $details[$transaction->Tid]->get();   

            $details2[$transaction->Tid] = TransactionDetail::select(DB::raw('SUM(unit_price*quantity) AS totalPrice'));

            if(Auth::guard('merchant')->check()){
                $details2[$transaction->Tid] = $details2[$transaction->Tid]->where('merchant_id', Auth::user()->code);
            }
            if(!empty(request('item_code'))){
                $details2[$transaction->Tid] = $details2[$transaction->Tid]->where('item_code', 'like', "%".request('item_code')."%");
            }
            if(!empty(request('product_code'))){
                $details2[$transaction->Tid] = $details2[$transaction->Tid]->where('product_code', 'like', "%".request('product_code')."%");
            }
            $details2[$transaction->Tid] = $details2[$transaction->Tid]->where("transaction_id", $transaction->Tid)->first();
        }

        $yearlySales = Transaction::select(DB::raw('SUM(grand_total) as yearlySales'))
                                //   ->join('transaction_details as d', 'd.transaction_id', 'transactions.id')
                                  ->where('transactions.status', '1')
                                  ->whereNull('transactions.pv_purchase')
                                  ->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y")'), date('Y'));
        if(Auth::guard('merchant')->check()){
        $yearlySales = $yearlySales->where('d.merchant_id', Auth::user()->code);
        }
        $yearlySales = $yearlySales->first();

        $monthlySales = Transaction::select(DB::raw('SUM(grand_total) as monthlySales'))
                                //   ->join('transaction_details as d', 'd.transaction_id', 'transactions.id')
                                  ->where('transactions.status', '1')
                                  ->whereNull('transactions.pv_purchase')
                                  ->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m")'), date('Y-m'));
        if(Auth::guard('merchant')->check()){
        $monthlySales = $monthlySales->where('d.merchant_id', Auth::user()->code);
        }
        $monthlySales = $monthlySales->first();

        $dailySales = Transaction::select(DB::raw('SUM(grand_total) as dailySales'))
                                //   ->join('transaction_details as d', 'd.transaction_id', 'transactions.id')
                                  ->where('transactions.status', '1')
                                  ->whereNull('transactions.pv_purchase')
                                  ->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), date('Y-m-d'));
        if(Auth::guard('merchant')->check()){
        $dailySales = $dailySales->where('d.merchant_id', Auth::user()->code);
        }
        $dailySales = $dailySales->first();

        return view('backend.reports.order_report', ['transactions'=>$transactions, 'startDate'=>$startDate, 'endDate'=>$endDate, 
                                                     'totalT'=>$totalT, 'totalT2'=>$totalT2,
                                                     'yearlySales'=>$yearlySales, 'monthlySales'=>$monthlySales, 
                                                     'dailySales'=>$dailySales],
                                                     compact('details', 'details2'));
    }

    public function print_order_report(){

        if(!empty(request('dates'))){

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

    //     if(!empty(request('yearly'))){
    //       if(!empty(request('monthly'))){
    //           if(!empty(request('daily'))){
    //               $start = date('Y-m-d', strtotime(request('yearly'). '-' .request('monthly'). '-' .request('daily')));
    //               $end = date('Y-m-d', strtotime(request('yearly'). '-' .request('monthly'). '-' .request('daily')));

    //               $startDate = date('Y-m-d', strtotime(request('yearly'). '-' .request('monthly'). '-' .request('daily')));
    //               $endDate = date('Y-m-d', strtotime(request('yearly'). '-' .request('monthly'). '-' .request('daily')));
    //           }else{
    //               $start = date('Y-m-d', strtotime(request('yearly'). '-' .request('monthly'). '-01'));
    //               $end = date('Y-m-t', strtotime(request('yearly'). '-' .request('monthly')));

    //               $startDate = date('Y-m-d', strtotime(request('yearly'). '-' .request('monthly'). '-01'));
    //               $endDate = date('Y-m-t', strtotime(request('yearly'). '-' .request('monthly')));
    //           }
    //       }else{
    //           $start = date('Y-m-d', strtotime(request('yearly'). '-01-01'));
    //           $end = date('Y-m-d', strtotime(request('yearly'). '-12-31'));

    //           $startDate = date('Y-m-d', strtotime(request('yearly'). '-01-01'));
    //           $endDate = date('Y-m-d', strtotime(request('yearly'). '-12-31'));
    //       }
    //   }
        
   $transactions = Transaction::select(DB::raw('CASE 
                                                     WHEN m.id != "" THEN COALESCE(m.f_name, m.phone)
                                                     WHEN a.id != "" THEN COALESCE(a.f_name, a.phone)
                                                     WHEN u.id != "" THEN COALESCE(u.f_name, u.phone)
                                                 END AS buyer_name'),
                                                DB::raw('CASE 
                                                     WHEN m.id != "" THEN "Agent"
                                                     WHEN a.id != "" THEN "Admin"
                                                     WHEN u.id != "" THEN "Customer"
                                                 END AS buyer_role'),
                                                DB::raw('CASE 
                                                    WHEN m.id != "" THEN m.code
                                                    WHEN a.id != "" THEN a.code
                                                    WHEN u.id != "" THEN u.code
                                                END as buyer_code'),
                                        'transactions.transaction_no', 'transactions.status', 'transactions.created_at',
                                        'transactions.id AS Tid', 'transactions.grand_total', 'transactions.shipping_fee', 
                                        'transactions.processing_fee', 'transactions.discount', 'transactions.address_name',
                                        'transactions.ad_discount')
                               ->leftJoin('agents AS m', 'm.code', 'transactions.user_id')
                               ->leftJoin('admins AS a', 'a.code', 'transactions.user_id')
                               ->leftJoin('users AS u', 'u.code', 'transactions.user_id')
                               ->join('transaction_details as d', 'd.transaction_id', 'transactions.id')
                               ->where('transactions.status', '1')
                               ->whereNull('transactions.mall')
                               ->whereNull('transactions.pv_purchase')
                               ->groupBy('transactions.id')
                                ->orderBy('transactions.created_at', 'desc');


        if(Auth::guard('merchant')->check()){
        $transactions = $transactions->where('d.merchant_id', Auth::user()->code);
        }

        // $totalT = Transaction::select(DB::raw('SUM(d.quantity) as totalQty'),
        //                               DB::raw('SUM(d.unit_price) as totalUnitPrice'),
        //                               DB::raw('SUM(d.unit_price * d.quantity) as totalNet'),
        //                               DB::raw('SUM((d.unit_price * d.quantity) + transactions.processing_fee + transactions.shipping_fee + transactions.tax) as totalGrand'))
        //                            ->join('transaction_details AS d', 'd.transaction_id', 'transactions.id')
        //                            ->where('transactions.status', '1')
        //                            ->whereNull('transactions.mall')
        //                            ->whereBetween(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), array($start, $end))
        //                            ->orderBy('transactions.created_at', 'desc');

        // $totalT2 = Transaction::select(DB::raw('SUM(transactions.processing_fee) as totalProcessingFee'),
        //                               DB::raw('SUM(transactions.shipping_fee) as totalShippingFee'),
        //                               DB::raw('SUM(transactions.tax) as totalTax'),
        //                               DB::raw('SUM(transactions.discount) as totalDiscount'),
        //                               DB::raw('SUM(transactions.ad_discount) as totalAdDiscount'))
        //                       ->where('transactions.status', '1')
        //                       ->whereNull('transactions.mall')
        //                       ->whereBetween(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), array($start, $end))
        //                       ->orderBy('transactions.created_at', 'desc');

        $queries = [];
        $columns = [
            'transaction_no', 'dates', 'buyer', 'item_code', 'product_code', 'status', 'yearly', 'monthly','daily'
        ];

        foreach($columns as $column){
            if(request()->has($column) && !empty(request($column))){
                if($column == 'status'){
                    $transactions = $transactions->where('transactions.status', 'like', "%".request($column)."%");
                }elseif($column == 'dates'){
                    if(empty(request('yearly')) && empty(request('monthly')) && empty(request('daily'))){
                        $transactions = $transactions->whereBetween(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), array($start, $end));
                    }
                }elseif($column == 'buyer'){
                    $term = '%' . request($column) . '%';

                    $transactions = $transactions->whereRaw("
                        CONVERT(CONCAT(m.f_name, ' ', m.l_name) USING utf8mb4) COLLATE utf8mb4_unicode_ci LIKE ?
                    ", [$term])
                    ->orWhereRaw("
                        CONVERT(CONCAT(a.f_name, ' ', a.l_name) USING utf8mb4) COLLATE utf8mb4_unicode_ci LIKE ?
                    ", [$term])
                    ->orWhereRaw("
                        CONVERT(CONCAT(u.f_name, ' ', u.l_name) USING utf8mb4) COLLATE utf8mb4_unicode_ci LIKE ?
                    ", [$term]);

                }elseif($column == 'yearly'){
                    $transactions = $transactions->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y")'), request($column));

                    $startYear = now()->startOfYear();
                    $endYear = now()->endOfYear();

                    $startDate = $startYear->format('d/m/Y');
                    $endDate = $endYear->format('d/m/Y');
                }elseif($column == 'monthly'){
                    // if(!empty(request('yearly'))){
                    //   $checkMonth = (strlen(request($column)) > 1) ? request($column) : '0'.request($column);
                    //   $transactions = $transactions->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m")'), request('yearly').'-'.$checkMonth);
                    // }else{
                      $transactions = $transactions->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m")'), request($column));
                    // }
                }elseif($column == 'daily'){
                    // if(!empty(request('yearly'))){
                    //     $searchYear = request('yearly');
                    // }else{
                    //     $searchYear = date('Y');
                    // }

                    // if(!empty(request('monthly'))){
                    //     $searchMonth = (strlen(request('monthly')) > 1) ? request('monthly') : '0'.request('monthly');
                    // }else{
                    //     $searchMonth = date('m');
                    // }

                    $checkDay = (strlen(request($column)) > 1) ? request($column) : '0'.request($column);
                    
                    $transactions = $transactions->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), $checkDay);
                    
                    $startYear = now();
                    $endYear = now();

                    $startDate = $startYear->format('d/m/Y');
                    $endDate = $endYear->format('d/m/Y');
                    // $transactions = $transactions->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), $searchYear.'-'.$searchMonth.'-'.$checkDay);
                    
                }elseif($column == 'item_code'){
                    $transactions = $transactions->where('d.item_code', 'like', "%".request($column)."%");
                }elseif($column == 'product_code'){
                    $transactions = $transactions->where('d.product_code', 'like', "%".request($column)."%");
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

        $transactions = $transactions->get();

        // $totalT = $totalT->first();
        // $totalT2 = $totalT2->first();

        $details = [];
        $details2 = [];
        foreach($transactions as $transaction){
            $details[$transaction->Tid] = TransactionDetail::where("transaction_id", $transaction->Tid);
            if(Auth::guard('merchant')->check()){
                $details[$transaction->Tid] = $details[$transaction->Tid]->where('merchant_id', Auth::user()->code);
            }
            if(!empty(request('item_code'))){
                $details[$transaction->Tid] = $details[$transaction->Tid]->where('item_code', 'like', "%".request('item_code')."%");
            }
            if(!empty(request('product_code'))){
                $details[$transaction->Tid] = $details[$transaction->Tid]->where('product_code', 'like', "%".request('product_code')."%");
            }
            $details[$transaction->Tid] = $details[$transaction->Tid]->get();   

            $details2[$transaction->Tid] = TransactionDetail::select(DB::raw('SUM(unit_price*quantity) AS totalPrice'));

            if(Auth::guard('merchant')->check()){
                $details2[$transaction->Tid] = $details2[$transaction->Tid]->where('merchant_id', Auth::user()->code);
            }
            if(!empty(request('item_code'))){
                $details2[$transaction->Tid] = $details2[$transaction->Tid]->where('item_code', 'like', "%".request('item_code')."%");
            }
            if(!empty(request('product_code'))){
                $details2[$transaction->Tid] = $details2[$transaction->Tid]->where('product_code', 'like', "%".request('product_code')."%");
            }
            $details2[$transaction->Tid] = $details2[$transaction->Tid]->where("transaction_id", $transaction->Tid)->first();
        }

        return view('backend.reports.print_order_report', ['transactions'=>$transactions, 'startDate'=>$startDate, 'endDate'=>$endDate], 
                                                    //  'totalT'=>$totalT, 'totalT2'=>$totalT2],
                                                     compact('details', 'details2'));
    }

    public function exportOrder()
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
        
        $checkYear = "";
        if(!empty(request('yearly'))){
            $checkYear = request('yearly');
        }

        $checkMonth = "";
        if(!empty(request('monthly'))){
            $checkMonth = (strlen(request('monthly')) > 1) ? request('monthly') : '0'.request('monthly');
        }

        $checkDay = "";
        if(!empty(request('daily'))){
            $checkDay = (strlen(request('daily')) > 1) ? request('daily') : '0'.request('daily');            
        }

        $transaction_no = "";
        if(!empty(request('transaction_no'))){
            $transaction_no = request('transaction_no');
        }

        $buyer = "";
        if(!empty(request('buyer'))){
            $buyer = request('buyer');
        }

        $item_code = "";
        if(!empty(request('item_code'))){
            $item_code = request('item_code');
        }

        $product_code = "";
        if(!empty(request('product_code'))){
            $product_code = request('product_code');
        }


    //     if(!empty(request('yearly'))){
    //       if(!empty(request('monthly'))){
    //           if(!empty(request('daily'))){
    //               $start = date('Y-m-d', strtotime(request('yearly'). '-' .request('monthly'). '-' .request('daily')));
    //               $end = date('Y-m-d', strtotime(request('yearly'). '-' .request('monthly'). '-' .request('daily')));

    //               $startDate = date('Y-m-d H:i:s', strtotime(request('yearly'). '-' .request('monthly'). '-' .request('daily')));
    //               $endDate = date('Y-m-d H:i:s', strtotime(request('yearly'). '-' .request('monthly'). '-' .request('daily')));
    //           }else{
    //               $start = date('Y-m-d', strtotime(request('yearly'). '-' .request('monthly'). '-01'));
    //               $end = date('Y-m-t', strtotime(request('yearly'). '-' .request('monthly')));

    //               $startDate = date('Y-m-d', strtotime(request('yearly'). '-' .request('monthly'). '-01'));
    //               $endDate = date('Y-m-t', strtotime(request('yearly'). '-' .request('monthly')));
    //           }
    //       }else{
    //           $start = date('Y-m-d', strtotime(request('yearly'). '-01-01'));
    //           $end = date('Y-m-d', strtotime(request('yearly'). '-12-31'));

    //           $startDate = date('Y-m-d', strtotime(request('yearly'). '-01-01'));
    //           $endDate = date('Y-m-d', strtotime(request('yearly'). '-12-31'));
    //       }
    //   }


        return Excel::download(new OrderExport($start, $end, $checkYear, $checkMonth, $checkDay,$transaction_no,$buyer,$item_code,$product_code), date('Y-m-d').' '.'Orders Report'.'.xlsx');
    }

    
    public function point_order_report()
    {
      $defaultYear = date('Y');

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

    //   if(!empty(request('yearly'))){
    //       if(!empty(request('monthly'))){
    //           if(!empty(request('today'))){
    //               $start = date('Y-m-d', strtotime(request('yearly'). '-' .request('monthly'). '-' .request('daily')));
    //               $end = date('Y-m-d', strtotime(request('yearly'). '-' .request('monthly'). '-' .request('daily')));

    //               $startDate = date('Y-m-d H:i:s', strtotime(request('yearly'). '-' .request('monthly'). '-' .request('daily')));
    //               $endDate = date('Y-m-d H:i:s', strtotime(request('yearly'). '-' .request('monthly'). '-' .request('daily')));
    //           }else{
    //               $start = date('Y-m-d', strtotime(request('yearly'). '-' .request('monthly'). '-01'));
    //               $end = date('Y-m-t', strtotime(request('yearly'). '-' .request('monthly')));

    //               $startDate = date('Y-m-d', strtotime(request('yearly'). '-' .request('monthly'). '-01'));
    //               $endDate = date('Y-m-t', strtotime(request('yearly'). '-' .request('monthly')));
    //           }
    //       }else{
    //           $start = date('Y-m-d', strtotime(request('yearly'). '-01-01'));
    //           $end = date('Y-m-d', strtotime(request('yearly'). '-12-31'));

    //           $startDate = date('Y-m-d', strtotime(request('yearly'). '-01-01'));
    //           $endDate = date('Y-m-d', strtotime(request('yearly'). '-12-31'));
    //       }
    //   }

        $transactions = Transaction::select(DB::raw('CASE 
                                                     WHEN m.id != "" THEN COALESCE(CONCAT(m.f_name, " ", m.l_name), m.phone)
                                                     WHEN a.id != "" THEN COALESCE(CONCAT(a.f_name, " ", a.l_name), a.phone)
                                                     WHEN u.id != "" THEN COALESCE(CONCAT(u.f_name, " ", u.l_name), u.phone)
                                                 END AS buyer_name'),
                                                DB::raw('CASE 
                                                     WHEN m.id != "" THEN "Agent"
                                                     WHEN a.id != "" THEN "Admin"
                                                     WHEN u.id != "" THEN "Customer"
                                                 END AS buyer_role'),
                                                DB::raw('CASE 
                                                    WHEN m.id != "" THEN m.code
                                                    WHEN a.id != "" THEN a.code
                                                    WHEN u.id != "" THEN u.code
                                                END as buyer_code'),
                                        'transactions.transaction_no', 'transactions.status', 'transactions.created_at',
                                        'transactions.id AS Tid', 'transactions.grand_total', 'transactions.shipping_fee', 
                                        'transactions.processing_fee', 'transactions.discount', 'transactions.address_name',
                                        'transactions.ad_discount')
                               ->leftJoin('agents AS m', 'm.code', 'transactions.user_id')
                               ->leftJoin('admins AS a', 'a.code', 'transactions.user_id')
                               ->leftJoin('users AS u', 'u.code', 'transactions.user_id')
                               ->join('transaction_details as d', 'd.transaction_id', 'transactions.id')
                               ->where('transactions.status', '1')
                               ->whereNull('transactions.mall')
                               ->where('transactions.pv_purchase', '1')
                               ->groupBy('transactions.id');
        if(Auth::guard('merchant')->check()){
        $transactions = $transactions->where('d.merchant_id', Auth::user()->code);
        }
        if(empty(request('this_year'))){
            $transactions = $transactions->whereBetween(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), array($start, $end));
        }
        $transactions = $transactions->orderBy('transactions.created_at', 'desc');

        $totalT = Transaction::select(DB::raw('SUM(d.quantity) as totalQty'),
                                      DB::raw('SUM(d.unit_price) as totalUnitPrice'),
                                      DB::raw('SUM(d.unit_price * d.quantity) as totalNet'),
                                      DB::raw('SUM((d.unit_price * d.quantity) + transactions.processing_fee + transactions.shipping_fee + transactions.tax) as totalGrand'))
                                   ->join('transaction_details AS d', 'd.transaction_id', 'transactions.id')
                                   ->where('transactions.status', '1')
                                   ->whereNull('transactions.mall')
                                   ->where('transactions.pv_purchase', '1');
        if(empty(request('this_year'))){
        $totalT = $totalT->whereBetween(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), array($start, $end));
        }
        if(!empty(request('today'))){
          $totalT = $totalT->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), date('Y-m-d'));
        }

        if(!empty(request('item_code'))){
            $totalT = $totalT->where('d.item_code', 'like', "%".request('item_code')."%");
        }

        if(!empty(request('product_code'))){
            $totalT = $totalT->where('d.product_code', 'like', "%".request('product_code')."%");
        }

        if(!empty(request('transaction_no'))){
            $totalT = $totalT->where('transactions.transaction_no', 'like', "%".request('transaction_no')."%");
        }

        if(!empty(request('buyer'))){
            $totalT = $totalT->leftJoin('agents AS m', 'm.code', 'transactions.user_id')
            ->leftJoin('admins AS a', 'a.code', 'transactions.user_id')
            ->leftJoin('users AS u', 'u.code', 'transactions.user_id');

             $term = '%' . request('buyer') . '%';

            $totalT = $totalT->whereRaw("
                CONVERT(CONCAT(m.f_name, ' ', m.l_name) USING utf8mb4) COLLATE utf8mb4_unicode_ci LIKE ?
            ", [$term])
            ->orWhereRaw("
                CONVERT(CONCAT(a.f_name, ' ', a.l_name) USING utf8mb4) COLLATE utf8mb4_unicode_ci LIKE ?
            ", [$term])
            ->orWhereRaw("
                CONVERT(CONCAT(u.f_name, ' ', u.l_name) USING utf8mb4) COLLATE utf8mb4_unicode_ci LIKE ?
            ", [$term]);
        }
        
        if(Auth::guard('merchant')->check()){
        $totalT = $totalT->where('d.merchant_id', Auth::user()->code);
        }

        $totalT = $totalT->orderBy('transactions.created_at', 'desc');

        $totalT2 = Transaction::select(DB::raw('SUM(transactions.processing_fee) as totalProcessingFee'),
                                      DB::raw('SUM(transactions.shipping_fee) as totalShippingFee'),
                                      DB::raw('SUM(transactions.tax) as totalTax'),
                                      DB::raw('SUM(transactions.discount) as totalDiscount'),
                                      DB::raw('SUM(transactions.ad_discount) as totalAdDiscount'))
                            //   ->join('transaction_details as d', 'd.transaction_id', 'transactions.id')
                              ->where('transactions.status', '1')
                              ->whereNull('transactions.mall')
                              ->where('transactions.pv_purchase', '1');

        if(empty(request('this_year'))){
        $totalT2 = $totalT2->whereBetween(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), array($start, $end));
        }
        if(!empty(request('today'))){
          $totalT2 = $totalT2->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), date('Y-m-d'));
        }

        if(!empty(request('item_code')) || !empty(request('product_code'))){
            $totalT2 = $totalT2->join('transaction_details as d', 'd.transaction_id', 'transactions.id');

            if(!empty(request('item_code'))){
                $totalT2 = $totalT2->where('d.item_code', 'like', "%".request('item_code')."%");
            }
            
            if(!empty(request('product_code'))){
                 $totalT2 = $totalT2->where('d.product_code', 'like', "%".request('product_code')."%");
            }
        }

        if(!empty(request('transaction_no'))){
            $totalT2 = $totalT2->where('transactions.transaction_no', 'like', "%".request('transaction_no')."%");
        }

        if(!empty(request('buyer'))){
            $totalT2 = $totalT2->leftJoin('agents AS m', 'm.code', 'transactions.user_id')
            ->leftJoin('admins AS a', 'a.code', 'transactions.user_id')
            ->leftJoin('users AS u', 'u.code', 'transactions.user_id');

            $term = '%' . request('buyer') . '%';

            $totalT2 = $totalT2->whereRaw("
                CONVERT(CONCAT(m.f_name, ' ', m.l_name) USING utf8mb4) COLLATE utf8mb4_unicode_ci LIKE ?
            ", [$term])
            ->orWhereRaw("
                CONVERT(CONCAT(a.f_name, ' ', a.l_name) USING utf8mb4) COLLATE utf8mb4_unicode_ci LIKE ?
            ", [$term])
            ->orWhereRaw("
                CONVERT(CONCAT(u.f_name, ' ', u.l_name) USING utf8mb4) COLLATE utf8mb4_unicode_ci LIKE ?
            ", [$term]);
        }

        if(Auth::guard('merchant')->check()){
            $totalT2 = $totalT2->where('d.merchant_id', Auth::user()->code);
        }
        $totalT2 = $totalT2->orderBy('transactions.created_at', 'desc');

        $queries = [];
        $columns = [
            'transaction_no', 'dates', 'buyer', 'item_code', 'product_code', 'status', 'yearly', 'monthly','daily', 'today', 'this_month', 'this_year', 'per_page'
        ];

        $per_page = 10;
        if(!empty(request('per_page'))){
            $per_page = request('per_page');
        }

        foreach($columns as $column){
            if(request()->has($column) && !empty(request($column))){
                if($column == 'status'){
                    $transactions = $transactions->where('transactions.status', 'like', "%".request($column)."%");
                }elseif($column == 'dates'){
                    if(!empty(request('yearly')) && !empty(request('monthly')) && !empty(request('daily'))){
                        $transactions = $transactions->whereBetween(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), array($start, $end));
                    }
                }elseif($column == 'buyer'){
                    $term = '%' . request($column) . '%';

                    $transactions = $transactions->whereRaw("
                        CONVERT(CONCAT(m.f_name, ' ', m.l_name) USING utf8mb4) COLLATE utf8mb4_unicode_ci LIKE ?
                    ", [$term])
                    ->orWhereRaw("
                        CONVERT(CONCAT(a.f_name, ' ', a.l_name) USING utf8mb4) COLLATE utf8mb4_unicode_ci LIKE ?
                    ", [$term])
                    ->orWhereRaw("
                        CONVERT(CONCAT(u.f_name, ' ', u.l_name) USING utf8mb4) COLLATE utf8mb4_unicode_ci LIKE ?
                    ", [$term]);
                }elseif($column == 'yearly'){
                    $transactions = $transactions->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y")'), request($column));
                }elseif($column == 'monthly'){
                    if(!empty(request('yearly'))){
                      $checkMonth = (strlen(request($column)) > 1) ? request($column) : '0'.request($column);
                      $transactions = $transactions->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m")'), request('yearly').'-'.$checkMonth);
                    }else{
                      $transactions = $transactions->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m")'), '2021-0'.request($column));
                    }
                }elseif($column == 'daily'){
                    if(!empty(request('yearly'))){
                        $searchYear = request('yearly');
                    }else{
                        $searchYear = date('Y');
                    }

                    if(!empty(request('monthly'))){
                        $searchMonth = (strlen(request('monthly')) > 1) ? request('monthly') : '0'.request('monthly');
                    }else{
                        $searchMonth = date('m');
                    }

                    $checkDay = (strlen(request($column)) > 1) ? request($column) : '0'.request($column);
                    
                    
                    $transactions = $transactions->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), $searchYear.'-'.$searchMonth.'-'.$checkDay);
                    
                }elseif($column == 'today'){
                    $transactions = $transactions->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), date('Y-m-d'));

                    $startYear = now();
                    $endYear = now();

                    $startDate = $startYear->format('d/m/Y');
                    $endDate = $endYear->format('d/m/Y');
                }elseif($column == 'this_month'){
                    $transactions = $transactions->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m")'), date('Y-m'));
                }elseif($column == 'this_year'){
                    $transactions = $transactions->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y")'), date('Y'));

                    $startYear = now()->startOfYear();
                    $endYear = now()->endOfYear();

                    $startDate = $startYear->format('d/m/Y');
                    $endDate = $endYear->format('d/m/Y');
                }elseif($column == 'per_page'){
                  $transactions = $transactions->paginate($per_page);
                }elseif($column == 'item_code'){
                    $transactions = $transactions->where('d.item_code', 'like', "%".request($column)."%");
                }elseif($column == 'product_code'){
                    $transactions = $transactions->where('d.product_code', 'like', "%".request($column)."%");
                }else{
                    $transactions = $transactions->where($column, 'like', "%".request($column)."%");
                }

                $queries[$column] = request($column);

            }
        }
        // return $transactions->toSql();
        
        if(!empty(request('per_page'))){
            $transactions = $transactions->appends($queries);        
        }else{
            $transactions = $transactions->paginate($per_page)->appends($queries);        
        }

        $totalT = $totalT->first();
        $totalT2 = $totalT2->first();

        $details = [];
        $details2 = [];
        foreach($transactions as $transaction){
            $details[$transaction->Tid] = TransactionDetail::where("transaction_id", $transaction->Tid);
            if(Auth::guard('merchant')->check()){
                $details[$transaction->Tid] = $details[$transaction->Tid]->where('merchant_id', Auth::user()->code);
            }
            if(!empty(request('item_code'))){
                $details[$transaction->Tid] = $details[$transaction->Tid]->where('item_code', 'like', "%".request('item_code')."%");
            }
            if(!empty(request('product_code'))){
                $details[$transaction->Tid] = $details[$transaction->Tid]->where('product_code', 'like', "%".request('product_code')."%");
            }
            $details[$transaction->Tid] = $details[$transaction->Tid]->get();   

            $details2[$transaction->Tid] = TransactionDetail::select(DB::raw('SUM(unit_price*quantity) AS totalPrice'));

            if(Auth::guard('merchant')->check()){
                $details2[$transaction->Tid] = $details2[$transaction->Tid]->where('merchant_id', Auth::user()->code);
            }
            if(!empty(request('item_code'))){
                $details2[$transaction->Tid] = $details2[$transaction->Tid]->where('item_code', 'like', "%".request('item_code')."%");
            }
            if(!empty(request('product_code'))){
                $details2[$transaction->Tid] = $details2[$transaction->Tid]->where('product_code', 'like', "%".request('product_code')."%");
            }
            $details2[$transaction->Tid] = $details2[$transaction->Tid]->where("transaction_id", $transaction->Tid)->first();
        }

        $yearlySales = Transaction::select(DB::raw('SUM(grand_total) as yearlySales'))
                                //   ->join('transaction_details as d', 'd.transaction_id', 'transactions.id')
                                  ->where('transactions.status', '1')
                                  ->where('transactions.pv_purchase', '1')
                                  ->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y")'), date('Y'));
        if(Auth::guard('merchant')->check()){
        $yearlySales = $yearlySales->where('d.merchant_id', Auth::user()->code);
        }
        $yearlySales = $yearlySales->first();

        $monthlySales = Transaction::select(DB::raw('SUM(grand_total) as monthlySales'))
                                //   ->join('transaction_details as d', 'd.transaction_id', 'transactions.id')
                                  ->where('transactions.status', '1')
                                  ->where('transactions.pv_purchase', '1')
                                  ->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m")'), date('Y-m'));
        if(Auth::guard('merchant')->check()){
        $monthlySales = $monthlySales->where('d.merchant_id', Auth::user()->code);
        }
        $monthlySales = $monthlySales->first();

        $dailySales = Transaction::select(DB::raw('SUM(grand_total) as dailySales'))
                                //   ->join('transaction_details as d', 'd.transaction_id', 'transactions.id')
                                  ->where('transactions.status', '1')
                                  ->where('transactions.pv_purchase', '1')
                                  ->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), date('Y-m-d'));
        if(Auth::guard('merchant')->check()){
        $dailySales = $dailySales->where('d.merchant_id', Auth::user()->code);
        }
        $dailySales = $dailySales->first();

        return view('backend.reports.point_order_report', ['transactions'=>$transactions, 'startDate'=>$startDate, 'endDate'=>$endDate, 
                                                     'totalT'=>$totalT, 'totalT2'=>$totalT2,
                                                     'yearlySales'=>$yearlySales, 'monthlySales'=>$monthlySales, 
                                                     'dailySales'=>$dailySales],
                                                     compact('details', 'details2'));
    }

    public function print_point_order_report(){

        if(!empty(request('dates'))){

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

    //     if(!empty(request('yearly'))){
    //       if(!empty(request('monthly'))){
    //           if(!empty(request('daily'))){
    //               $start = date('Y-m-d', strtotime(request('yearly'). '-' .request('monthly'). '-' .request('daily')));
    //               $end = date('Y-m-d', strtotime(request('yearly'). '-' .request('monthly'). '-' .request('daily')));

    //               $startDate = date('Y-m-d', strtotime(request('yearly'). '-' .request('monthly'). '-' .request('daily')));
    //               $endDate = date('Y-m-d', strtotime(request('yearly'). '-' .request('monthly'). '-' .request('daily')));
    //           }else{
    //               $start = date('Y-m-d', strtotime(request('yearly'). '-' .request('monthly'). '-01'));
    //               $end = date('Y-m-t', strtotime(request('yearly'). '-' .request('monthly')));

    //               $startDate = date('Y-m-d', strtotime(request('yearly'). '-' .request('monthly'). '-01'));
    //               $endDate = date('Y-m-t', strtotime(request('yearly'). '-' .request('monthly')));
    //           }
    //       }else{
    //           $start = date('Y-m-d', strtotime(request('yearly'). '-01-01'));
    //           $end = date('Y-m-d', strtotime(request('yearly'). '-12-31'));

    //           $startDate = date('Y-m-d', strtotime(request('yearly'). '-01-01'));
    //           $endDate = date('Y-m-d', strtotime(request('yearly'). '-12-31'));
    //       }
    //   }
        
   $transactions = Transaction::select(DB::raw('CASE 
                                                     WHEN m.id != "" THEN COALESCE(m.f_name, m.phone)
                                                     WHEN a.id != "" THEN COALESCE(a.f_name, a.phone)
                                                     WHEN u.id != "" THEN COALESCE(u.f_name, u.phone)
                                                 END AS buyer_name'),
                                                DB::raw('CASE 
                                                     WHEN m.id != "" THEN "Agent"
                                                     WHEN a.id != "" THEN "Admin"
                                                     WHEN u.id != "" THEN "Customer"
                                                 END AS buyer_role'),
                                                DB::raw('CASE 
                                                    WHEN m.id != "" THEN m.code
                                                    WHEN a.id != "" THEN a.code
                                                    WHEN u.id != "" THEN u.code
                                                END as buyer_code'),
                                        'transactions.transaction_no', 'transactions.status', 'transactions.created_at',
                                        'transactions.id AS Tid', 'transactions.grand_total', 'transactions.shipping_fee', 
                                        'transactions.processing_fee', 'transactions.discount', 'transactions.address_name',
                                        'transactions.ad_discount')
                               ->leftJoin('agents AS m', 'm.code', 'transactions.user_id')
                               ->leftJoin('admins AS a', 'a.code', 'transactions.user_id')
                               ->leftJoin('users AS u', 'u.code', 'transactions.user_id')
                               ->join('transaction_details as d', 'd.transaction_id', 'transactions.id')
                               ->where('transactions.status', '1')
                               ->whereNull('transactions.mall')
                               ->where('transactions.pv_purchase', '1')
                               ->groupBy('transactions.id')
                                ->orderBy('transactions.created_at', 'desc');


        if(Auth::guard('merchant')->check()){
        $transactions = $transactions->where('d.merchant_id', Auth::user()->code);
        }

        // $totalT = Transaction::select(DB::raw('SUM(d.quantity) as totalQty'),
        //                               DB::raw('SUM(d.unit_price) as totalUnitPrice'),
        //                               DB::raw('SUM(d.unit_price * d.quantity) as totalNet'),
        //                               DB::raw('SUM((d.unit_price * d.quantity) + transactions.processing_fee + transactions.shipping_fee + transactions.tax) as totalGrand'))
        //                            ->join('transaction_details AS d', 'd.transaction_id', 'transactions.id')
        //                            ->where('transactions.status', '1')
        //                            ->whereNull('transactions.mall')
        //                            ->whereBetween(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), array($start, $end))
        //                            ->orderBy('transactions.created_at', 'desc');

        // $totalT2 = Transaction::select(DB::raw('SUM(transactions.processing_fee) as totalProcessingFee'),
        //                               DB::raw('SUM(transactions.shipping_fee) as totalShippingFee'),
        //                               DB::raw('SUM(transactions.tax) as totalTax'),
        //                               DB::raw('SUM(transactions.discount) as totalDiscount'),
        //                               DB::raw('SUM(transactions.ad_discount) as totalAdDiscount'))
        //                       ->where('transactions.status', '1')
        //                       ->whereNull('transactions.mall')
        //                       ->whereBetween(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), array($start, $end))
        //                       ->orderBy('transactions.created_at', 'desc');

        $queries = [];
        $columns = [
            'transaction_no', 'dates', 'buyer', 'item_code', 'product_code', 'status', 'yearly', 'monthly','daily'
        ];

        foreach($columns as $column){
            if(request()->has($column) && !empty(request($column))){
                if($column == 'status'){
                    $transactions = $transactions->where('transactions.status', 'like', "%".request($column)."%");
                }elseif($column == 'dates'){
                    if(empty(request('yearly')) && empty(request('monthly')) && empty(request('daily'))){
                        $transactions = $transactions->whereBetween(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), array($start, $end));
                    }
                }elseif($column == 'buyer'){
                    $term = '%' . request($column) . '%';

                    $transactions = $transactions->whereRaw("
                        CONVERT(CONCAT(m.f_name, ' ', m.l_name) USING utf8mb4) COLLATE utf8mb4_unicode_ci LIKE ?
                    ", [$term])
                    ->orWhereRaw("
                        CONVERT(CONCAT(a.f_name, ' ', a.l_name) USING utf8mb4) COLLATE utf8mb4_unicode_ci LIKE ?
                    ", [$term])
                    ->orWhereRaw("
                        CONVERT(CONCAT(u.f_name, ' ', u.l_name) USING utf8mb4) COLLATE utf8mb4_unicode_ci LIKE ?
                    ", [$term]);

                }elseif($column == 'yearly'){
                    $transactions = $transactions->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y")'), request($column));

                    $startYear = now()->startOfYear();
                    $endYear = now()->endOfYear();

                    $startDate = $startYear->format('d/m/Y');
                    $endDate = $endYear->format('d/m/Y');
                }elseif($column == 'monthly'){
                    // if(!empty(request('yearly'))){
                    //   $checkMonth = (strlen(request($column)) > 1) ? request($column) : '0'.request($column);
                    //   $transactions = $transactions->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m")'), request('yearly').'-'.$checkMonth);
                    // }else{
                      $transactions = $transactions->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m")'), request($column));
                    // }
                }elseif($column == 'daily'){
                    // if(!empty(request('yearly'))){
                    //     $searchYear = request('yearly');
                    // }else{
                    //     $searchYear = date('Y');
                    // }

                    // if(!empty(request('monthly'))){
                    //     $searchMonth = (strlen(request('monthly')) > 1) ? request('monthly') : '0'.request('monthly');
                    // }else{
                    //     $searchMonth = date('m');
                    // }

                    $checkDay = (strlen(request($column)) > 1) ? request($column) : '0'.request($column);
                    
                    $transactions = $transactions->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), $checkDay);
                    
                    $startYear = now();
                    $endYear = now();

                    $startDate = $startYear->format('d/m/Y');
                    $endDate = $endYear->format('d/m/Y');
                    // $transactions = $transactions->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), $searchYear.'-'.$searchMonth.'-'.$checkDay);
                    
                }elseif($column == 'item_code'){
                    $transactions = $transactions->where('d.item_code', 'like', "%".request($column)."%");
                }elseif($column == 'product_code'){
                    $transactions = $transactions->where('d.product_code', 'like', "%".request($column)."%");
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

        $transactions = $transactions->get();

        // $totalT = $totalT->first();
        // $totalT2 = $totalT2->first();

        $details = [];
        $details2 = [];
        foreach($transactions as $transaction){
            $details[$transaction->Tid] = TransactionDetail::where("transaction_id", $transaction->Tid);
            if(Auth::guard('merchant')->check()){
                $details[$transaction->Tid] = $details[$transaction->Tid]->where('merchant_id', Auth::user()->code);
            }
            if(!empty(request('item_code'))){
                $details[$transaction->Tid] = $details[$transaction->Tid]->where('item_code', 'like', "%".request('item_code')."%");
            }
            if(!empty(request('product_code'))){
                $details[$transaction->Tid] = $details[$transaction->Tid]->where('product_code', 'like', "%".request('product_code')."%");
            }
            $details[$transaction->Tid] = $details[$transaction->Tid]->get();   

            $details2[$transaction->Tid] = TransactionDetail::select(DB::raw('SUM(unit_price*quantity) AS totalPrice'));

            if(Auth::guard('merchant')->check()){
                $details2[$transaction->Tid] = $details2[$transaction->Tid]->where('merchant_id', Auth::user()->code);
            }
            if(!empty(request('item_code'))){
                $details2[$transaction->Tid] = $details2[$transaction->Tid]->where('item_code', 'like', "%".request('item_code')."%");
            }
            if(!empty(request('product_code'))){
                $details2[$transaction->Tid] = $details2[$transaction->Tid]->where('product_code', 'like', "%".request('product_code')."%");
            }
            $details2[$transaction->Tid] = $details2[$transaction->Tid]->where("transaction_id", $transaction->Tid)->first();
        }

        return view('backend.reports.print_point_order_report', ['transactions'=>$transactions, 'startDate'=>$startDate, 'endDate'=>$endDate], 
                                                    //  'totalT'=>$totalT, 'totalT2'=>$totalT2],
                                                     compact('details', 'details2'));
    }

    public function exportPointOrder()
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
        
        $checkYear = "";
        if(!empty(request('yearly'))){
            $checkYear = request('yearly');
        }

        $checkMonth = "";
        if(!empty(request('monthly'))){
            $checkMonth = (strlen(request('monthly')) > 1) ? request('monthly') : '0'.request('monthly');
        }

        $checkDay = "";
        if(!empty(request('daily'))){
            $checkDay = (strlen(request('daily')) > 1) ? request('daily') : '0'.request('daily');            
        }

        $transaction_no = "";
        if(!empty(request('transaction_no'))){
            $transaction_no = request('transaction_no');
        }

        $buyer = "";
        if(!empty(request('buyer'))){
            $buyer = request('buyer');
        }

        $item_code = "";
        if(!empty(request('item_code'))){
            $item_code = request('item_code');
        }

        $product_code = "";
        if(!empty(request('product_code'))){
            $product_code = request('product_code');
        }


    //     if(!empty(request('yearly'))){
    //       if(!empty(request('monthly'))){
    //           if(!empty(request('daily'))){
    //               $start = date('Y-m-d', strtotime(request('yearly'). '-' .request('monthly'). '-' .request('daily')));
    //               $end = date('Y-m-d', strtotime(request('yearly'). '-' .request('monthly'). '-' .request('daily')));

    //               $startDate = date('Y-m-d H:i:s', strtotime(request('yearly'). '-' .request('monthly'). '-' .request('daily')));
    //               $endDate = date('Y-m-d H:i:s', strtotime(request('yearly'). '-' .request('monthly'). '-' .request('daily')));
    //           }else{
    //               $start = date('Y-m-d', strtotime(request('yearly'). '-' .request('monthly'). '-01'));
    //               $end = date('Y-m-t', strtotime(request('yearly'). '-' .request('monthly')));

    //               $startDate = date('Y-m-d', strtotime(request('yearly'). '-' .request('monthly'). '-01'));
    //               $endDate = date('Y-m-t', strtotime(request('yearly'). '-' .request('monthly')));
    //           }
    //       }else{
    //           $start = date('Y-m-d', strtotime(request('yearly'). '-01-01'));
    //           $end = date('Y-m-d', strtotime(request('yearly'). '-12-31'));

    //           $startDate = date('Y-m-d', strtotime(request('yearly'). '-01-01'));
    //           $endDate = date('Y-m-d', strtotime(request('yearly'). '-12-31'));
    //       }
    //   }


        return Excel::download(new PointOrderExport($start, $end, $checkYear, $checkMonth, $checkDay,$transaction_no,$buyer,$item_code,$product_code), date('Y-m-d').' '.'Point Orders Report'.'.xlsx');
    }

   public function team_reward_report()
    {
         if (!empty(request('dates'))) {
        $new_dates = explode('-', request('dates'));
        $start = DateTime::createFromFormat('d/m/Y', trim($new_dates[0]))->format('Y-m-d');
        $end   = DateTime::createFromFormat('d/m/Y', trim($new_dates[1]))->format('Y-m-d');
        $startDate = trim($new_dates[0]);
        $endDate   = trim($new_dates[1]);
        } else {
            $ds = new DateTime("first day of this month");
            $de = new DateTime("last day of this month");
            $start = $ds->format('Y-m-d');
            $end   = $de->format('Y-m-d');
            $startDate = $ds->format('d/m/Y');
            $endDate   = $de->format('d/m/Y');
        }

        $translation_data = GlobalController::get_translations();

        $agents = Agent::select(
            DB::raw('CONVERT(CONCAT(agents.f_name," ",agents.l_name) USING utf8mb4) COLLATE utf8mb4_unicode_ci AS agentName'),
            DB::raw('CONVERT(agents.code USING utf8mb4) COLLATE utf8mb4_unicode_ci AS agentCode'),
            'agents.lvl',
            'agents.status'
        )
        ->whereNotIn('agents.status', ['99','3']);

        if(!empty(request('referrer_name'))){
            $agents->where(DB::raw('CONCAT(agents.f_name," ",agents.l_name)'), 'like', '%'.request('referrer_name').'%');
        }

        if(!empty(request('referrer_code'))){
            $agents->where('agents.code', 'like', '%'.request('referrer_code').'%');
        }

        $per_page = request('per_page', 10);
        $agents = $agents->paginate($per_page);

        $team_rewards = [];

        foreach ($agents as $agent) {

            $totalSales = GlobalController::get_user_accumulated_sales($agent->agentCode);

            $tier = SettingTeamDividend::where('status', 1)
                ->where('target_box', '<=', $totalSales)
                ->orderBy('target_box', 'desc')
                ->first();

            $entitle = $tier->amount ?? 0;

            $commission = AffiliateCommission::where('user_id',$agent->agentCode)
                ->where('type',6)
                ->whereBetween('created_at', [$start.' 00:00:00', $end.' 23:59:59'])
                ->sum('comm_amount');
        
            $team_rewards[$agent->agentCode] = [
                'groupSales' => $totalSales,
                'commission' => $commission,
                'percentage' => $entitle
            ];
        }

        return view('backend.reports.team_reward_report', [
            'startDate'=>$startDate,
            'endDate'=>$endDate,
            'agents'=>$agents,
            'team_rewards'=>$team_rewards
        ]);
    }
    public function team_reward_report_detail($code)
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

    $agent = Agent::select('f_name','l_name','code','lvl')
        ->where('code',$code)
        ->first();

    $uplineTotalSales = Transaction::where('user_id', $code)
        ->where('status', 1)
        ->whereBetween(DB::raw('DATE(created_at)'), [$start, $end])
        ->sum('grand_total');

    $uplineTier = SettingTeamDividend::where('status',1)
        ->where('target_box', '<=', $uplineTotalSales)
        ->orderBy('target_box','desc')
        ->first();

    $uplineEntitle = $uplineTier->amount ?? 0;

    $downlines = Agent::where('master_id',$code)->get();    

    $details = [];
    $totalTeamReward = 0;

    foreach($downlines as $downline){

        /* group sales */
        $groupSales = Transaction::where('user_id',$downline->code)
                        ->where('status',1)
                        ->whereBetween(DB::raw('DATE(created_at)'),[$start,$end])
                        ->sum('grand_total');

        $commission = AffiliateCommission::where('user_id',$code)
            ->where('user_by',$downline->code)
            ->where('type',6)
            ->whereBetween('created_at', [$start.' 00:00:00', $end.' 23:59:59'])
            ->sum('comm_amount');

        /* get entitle tier from setting_team_dividends */
        $tier = SettingTeamDividend::where('status',1)
                ->where('target_box','<=',$groupSales)
                ->orderBy('target_box','desc')
                ->first();
        $downlineEntitle = $tier->amount ?? 0;
        $difference = max($uplineEntitle - $downlineEntitle, 0);
        $teamReward = $groupSales * ($difference / 100);

        $entitle = $tier->amount ?? 0;
        $teamReward = $commission;

        $details[] = [
            'downlineName' => $downline->f_name.' '.$downline->l_name,
            'downlineCode' => $downline->code,
            'groupSales' => $groupSales,
            'entitle' => $downlineEntitle,
            'difference' => $difference,
            'teamReward' => $teamReward
        ];

        $totalTeamReward += $teamReward;
    }

    return view('backend.reports.team_reward_report_detail',[
        'details'=>$details,
        'agent'=>$agent,
        'code'=>$code,
        'startDate'=>$startDate,
        'endDate'=>$endDate,
        'uplineEntitle' => $uplineEntitle,
        'totalTeamReward'=>$totalTeamReward
    ]);
}
    
    public function commission_report()
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


        $commissions = AffiliateCommission::select(DB::raw('COALESCE(COALESCE(CONCAT(m.f_name, " ", m.l_name), CONCAT(a.f_name, " ", a.l_name), u.f_name)) AS agentName'), 'al.agent_lvl',
          DB::raw('COALESCE(COALESCE(m.code, a.code), u.code) AS agentCode'),
          DB::raw('COALESCE(m.ic, a.ic) AS agentIC'), 
                                                  'affiliate_commissions.*', 't.id AS tID', 't.grand_total', 't.shipping_fee', 't.processing_fee', 't.discount',
                                                  't.created_at AS transaction_date', 't.user_id AS buyer', 
                                                  'affiliate_commissions.product_amount', 
                                                  'affiliate_commissions.product_qty', 
                                                  'affiliate_commissions.product_name',
                                                  DB::raw('COALESCE(COALESCE(CONCAT(mt.f_name, " ", mt.l_name), CONCAT(ut.f_name, " ", ut.l_name)), CONCAT(at.f_name, " ", at.l_name)) AS buyerName'),
                                                  DB::raw('COALESCE(COALESCE(mt.code, ut.code), at.code) AS buyerCode'),
                                                  DB::raw('COALESCE(COALESCE(mt.ic, ut.ic), at.ic) AS buyerIC'),
                                                  'mb.f_name as from_user')
                                          ->leftJoin('agents AS m', 'm.code', 'affiliate_commissions.user_id')
                                          ->leftJoin('admins AS a', 'a.code', 'affiliate_commissions.user_id')
                                          ->leftJoin('users AS u', 'u.code', 'affiliate_commissions.user_id')
                                          ->leftJoin('agents AS mb', 'mb.code', 'affiliate_commissions.user_by')
                                          ->leftJoin('agent_levels as al', 'al.id', 'm.lvl')
                                          ->leftJoin('transactions AS t', 't.transaction_no', 'affiliate_commissions.transaction_no')
                                          ->leftJoin('agents AS mt', 'mt.code', 'affiliate_commissions.user_by')
                                          ->leftJoin('admins AS at', 'at.code', 'affiliate_commissions.user_by')
                                          ->leftJoin('users AS ut', 'ut.code', 'affiliate_commissions.user_by')
                                          ->where('affiliate_commissions.comm_amount', '>', '0');
        if(Auth::guard('merchant')->check()){
        $commissions = $commissions->where('affiliate_commissions.merchant_id', Auth::user()->code);
        }
        if(empty(request('this_year'))){
        $commissions = $commissions->whereBetween(DB::raw('DATE_FORMAT(affiliate_commissions.created_at, "%Y-%m-%d")'), array($start, $end));
        }
                                          
        $commissions = $commissions->orderBy('affiliate_commissions.created_at', 'desc')
                                   ->orderBy('affiliate_commissions.user_id', 'desc');

        $totalCommission = AffiliateCommission::select(DB::raw("SUM(IF(type = '1', comm_amount, NULL)) as totalAgentBonus"),
                                                       DB::raw("SUM(IF(type = '8', comm_amount, NULL)) as totalAgentRebateBonus"),
                                                       DB::raw("SUM(IF(type = '3', comm_amount, NULL)) as totalAffiliateBonus"),
                                                       DB::raw("SUM(IF(type = '4', comm_amount, NULL)) as totalPerformance"),
                                                       DB::raw("SUM(IF(type = '5', comm_amount, NULL)) as totalTeam"),
                                                       DB::raw("SUM(IF(type = '6', comm_amount, NULL)) as totalRefferal"),
                                                       DB::raw("SUM(IF(type = '7', comm_amount, NULL)) as totalProduct"))
                                              ->leftjoin('agents AS m', 'm.code', 'affiliate_commissions.user_id')
                                              ->leftjoin('admins AS a', 'a.code', 'affiliate_commissions.user_id');
        if(empty(request('this_year'))){
        $totalCommission = $totalCommission->whereBetween(DB::raw('DATE_FORMAT(affiliate_commissions.created_at, "%Y-%m-%d")'), array($start, $end));
        }


        $queries = [];
        $columns = [
            'transaction_no', 'comm_type', 'referrer_name', 'referrer_code', 'referrer_ic', 'dates', 'agent', 'agent_code', 'agent_ic', 'status', 'yearly', 'monthly','daily','today', 'this_month', 'this_year', 'per_page'
        ];

        $per_page = 10;
        if(!empty(request('per_page'))){
            $per_page = request('per_page');
        }

        foreach($columns as $column){
            if(request()->has($column) && !empty(request($column))){
                if($column == 'status'){
                    $statusVal = strtolower(request('status'));
                    if ($statusVal === 'approved' || $statusVal === '1') {
                        $commissions = $commissions->where('affiliate_commissions.status', 1);
                        $totalCommission = $totalCommission->where('affiliate_commissions.status', 1);
                    } elseif ($statusVal === 'burned' || $statusVal === '2') {
                        $commissions = $commissions->where('affiliate_commissions.status', 2)
                                                   ->where('affiliate_commissions.burned', 1);
                        $totalCommission = $totalCommission->where('affiliate_commissions.status', 2)
                                                           ->where('affiliate_commissions.burned', 1);
                    }
                }elseif($column == 'dates'){
                    if(!empty(request('yearly')) && !empty(request('monthly')) && !empty(request('daily'))){
                        $commissions = $commissions->whereBetween(DB::raw('DATE_FORMAT(affiliate_commissions.created_at, "%Y-%m-%d")'), array($start, $end));
                        $totalCommission = $totalCommission->whereBetween(DB::raw('DATE_FORMAT(affiliate_commissions.created_at, "%Y-%m-%d")'), array($start, $end));
                    }
                }elseif($column == 'agent'){
                    // $totalCommission = $totalCommission->where(DB::raw('COALESCE(CONCAT(m.f_name, " ", m.l_name),CONCAT(a.f_name, " ", a.l_name))'), 'like', "%".request($column)."%");
                    $totalCommission = $totalCommission->whereRaw("
                                CONVERT(
                                    COALESCE(
                                        CONCAT(m.f_name, ' ', m.l_name),
                                        CONCAT(a.f_name, ' ', a.l_name)
                                    )
                                USING utf8mb4) COLLATE utf8mb4_unicode_ci LIKE ?
                            ", ['%' . request($column) . '%']);
                    $commissions = $commissions->whereRaw("
                                CONVERT(
                                    COALESCE(
                                        CONCAT(mt.f_name, ' ', mt.l_name),
                                        CONCAT(ut.f_name, ' ', ut.l_name)
                                    )
                                USING utf8mb4) COLLATE utf8mb4_unicode_ci LIKE ?
                            ", ['%' . request($column) . '%']);
                }elseif($column == 'agent_code'){

                    $commissions = $commissions->where(DB::raw('COALESCE(COALESCE(mt.code, ut.code), at.code)'), 'like', "%".request($column)."%");
                    $totalCommission = $totalCommission->where(DB::raw('COALESCE(m.code, a.code)'), 'like', "%".request($column)."%");

                }elseif($column == 'agent_ic'){

                    $commissions = $commissions->where(DB::raw('COALESCE(COALESCE(mt.ic, ut.ic), at.ic)'), 'like', "%".request($column)."%");
                    $totalCommission = $totalCommission->where(DB::raw('COALESCE(m.ic, a.ic)'), 'like', "%".request($column)."%");

                }elseif($column == 'transaction_no'){
                    $commissions = $commissions->where('affiliate_commissions.transaction_no', 'like', "%".request($column)."%");
                    $totalCommission = $totalCommission->where('affiliate_commissions.transaction_no', 'like', "%".request($column)."%");
                }elseif($column == 'yearly'){
                    $commissions = $commissions->where(DB::raw('DATE_FORMAT(affiliate_commissions.created_at, "%Y")'), request($column));
                    $totalCommission = $totalCommission->where(DB::raw('DATE_FORMAT(affiliate_commissions.created_at, "%Y")'), request($column));
                }elseif($column == 'monthly'){
                    if(!empty(request('yearly'))){
                      $checkMonth = (strlen(request($column)) > 1) ? request($column) : '0'.request($column);
                      $commissions = $commissions->where(DB::raw('DATE_FORMAT(affiliate_commissions.created_at, "%Y-%m")'), request('yearly').'-'.$checkMonth);
                      $totalCommission = $totalCommission->where(DB::raw('DATE_FORMAT(affiliate_commissions.created_at, "%Y-%m")'), request('yearly').'-'.$checkMonth);

                    }else{
                      $commissions = $commissions->where(DB::raw('DATE_FORMAT(affiliate_commissions.created_at, "%Y-%m")'), '2021-0'.request($column));
                      $totalCommission = $totalCommission->where(DB::raw('DATE_FORMAT(affiliate_commissions.created_at, "%Y-%m")'), '2021-0'.request($column));
                    }
                }elseif($column == 'daily'){
                    if(!empty(request('yearly'))){
                        $searchYear = request('yearly');
                    }else{
                        $searchYear = date('Y');
                    }

                    if(!empty(request('monthly'))){
                        $searchMonth = (strlen(request('monthly')) > 1) ? request('monthly') : '0'.request('monthly');
                    }else{
                        $searchMonth = date('m');
                    }

                    $checkDay = (strlen(request($column)) > 1) ? request($column) : '0'.request($column);
                    
                    
                    $commissions = $commissions->where(DB::raw('DATE_FORMAT(affiliate_commissions.created_at, "%Y-%m-%d")'), $searchYear.'-'.$searchMonth.'-'.$checkDay);
                    $totalCommission = $totalCommission->where(DB::raw('DATE_FORMAT(affiliate_commissions.created_at, "%Y-%m-%d")'), $searchYear.'-'.$searchMonth.'-'.$checkDay);
                    
                }elseif($column == 'today'){
                  $commissions = $commissions->where(DB::raw('DATE_FORMAT(affiliate_commissions.created_at, "%Y-%m-%d")'), date('Y-m-d'));

                    $startYear = now();
                    $endYear = now();

                    $startDate = $startYear->format('d/m/Y');
                    $endDate = $endYear->format('d/m/Y');
                }elseif($column == 'comm_type'){
                  $commissions = $commissions->where('affiliate_commissions.comm_desc', 'like', "%".request($column)."%");
                }elseif($column == 'referrer_name'){
                    $commissions = $commissions->whereRaw("
                        CONVERT(
                            COALESCE(
                                CONCAT(m.f_name, ' ', m.l_name),
                                CONCAT(a.f_name, ' ', a.l_name)
                            )
                        USING utf8mb4) COLLATE utf8mb4_unicode_ci LIKE ?
                    ", ['%' . request($column) . '%']);
                }elseif($column == 'referrer_code'){
                  $commissions = $commissions->where(DB::raw('COALESCE(m.code, a.code)'), 'like', "%".request($column)."%");
                }elseif($column == 'referrer_ic'){
                  $commissions = $commissions->where(DB::raw('COALESCE(m.ic, a.ic)'), 'like', "%".request($column)."%");
                }elseif($column == 'this_month'){
                  $commissions = $commissions->where(DB::raw('DATE_FORMAT(affiliate_commissions.created_at, "%Y-%m")'), date('Y-m'));
                }elseif($column == 'this_year'){
                  $commissions = $commissions->where(DB::raw('DATE_FORMAT(affiliate_commissions.created_at, "%Y")'), date('Y'));

                    $startYear = now()->startOfYear();
                    $endYear = now()->endOfYear();

                    $startDate = $startYear->format('d/m/Y');
                    $endDate = $endYear->format('d/m/Y');
                }
                elseif($column == 'per_page'){
                  $commissions = $commissions->paginate($per_page);
                }

                $queries[$column] = request($column);

            }
        }

        $totalCommission = $totalCommission->first();

        if(!empty(request('per_page'))){
            $commissions = $commissions->appends($queries);        
        }else{
            $commissions = $commissions->paginate($per_page)->appends($queries);        
        }

        $netTotal = AffiliateCommission::select(DB::raw('SUM(comm_amount) AS netTotalCommission'))
                                       ->where('status', '1');
        if(Auth::guard('merchant')->check()){
        $netTotal = $netTotal->where('affiliate_commissions.merchant_id', Auth::user()->code);
        }
        $netTotal = $netTotal->first();


        $yearlySales = AffiliateCommission::select(DB::raw('SUM(comm_amount) as yearlySales'))
                                  ->where('status', '1')
                                  ->where(DB::raw('DATE_FORMAT(created_at, "%Y")'), date('Y'));
        if(Auth::guard('merchant')->check()){
        $yearlySales = $yearlySales->where('affiliate_commissions.merchant_id', Auth::user()->code);
        }
        $yearlySales = $yearlySales->first();

        $monthlySales = AffiliateCommission::select(DB::raw('SUM(comm_amount) as monthlySales'))
                                  ->where('status', '1')
                                  ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), date('Y-m'));
        if(Auth::guard('merchant')->check()){
        $monthlySales = $monthlySales->where('affiliate_commissions.merchant_id', Auth::user()->code);
        }
        $monthlySales = $monthlySales->first();

        $dailySales = AffiliateCommission::select(DB::raw('SUM(comm_amount) as dailySales'))
                                  ->where('status', '1')
                                  ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), date('Y-m-d'));
        if(Auth::guard('merchant')->check()){
        $dailySales = $dailySales->where('affiliate_commissions.merchant_id', Auth::user()->code);
        }
        $dailySales = $dailySales->first();
        
        
        return view('backend.reports.commission_report', ['startDate'=>$startDate, 'endDate'=>$endDate,
                                                          'commissions'=>$commissions, 'totalCommission'=>$totalCommission,
                                                          'netTotal'=>$netTotal,
                                                          'yearlySales'=>$yearlySales, 'monthlySales'=>$monthlySales, 
                                                          'dailySales'=>$dailySales]);
    }

    public function print_commission_report()
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

        $commissions = AffiliateCommission::select(DB::raw('COALESCE(COALESCE(CONCAT(m.f_name, " ", m.l_name), CONCAT(a.f_name, " ", a.l_name), u.f_name)) AS agentName'), 'al.agent_lvl',
          DB::raw('COALESCE(COALESCE(m.code, a.code), u.code) AS agentCode'),
          DB::raw('COALESCE(m.ic, a.ic) AS agentIC'), 
                                                  'affiliate_commissions.*', 't.id AS tID', 't.grand_total', 't.shipping_fee', 't.processing_fee', 't.discount',
                                                  't.created_at AS transaction_date', 't.user_id AS buyer', 
                                                  'affiliate_commissions.product_amount', 
                                                  'affiliate_commissions.product_qty', 
                                                  'affiliate_commissions.product_name',
                                                  DB::raw('COALESCE(COALESCE(CONCAT(mt.f_name, " ", mt.l_name), CONCAT(ut.f_name, " ", ut.l_name)), CONCAT(at.f_name, " ", at.l_name)) AS buyerName'),
                                                  DB::raw('COALESCE(COALESCE(mt.code, ut.code), at.code) AS buyerCode'),
                                                  DB::raw('COALESCE(COALESCE(mt.ic, ut.ic), at.ic) AS buyerIC'),
                                                  'mb.f_name as from_user')
                                          ->leftJoin('agents AS m', 'm.code', 'affiliate_commissions.user_id')
                                          ->leftJoin('admins AS a', 'a.code', 'affiliate_commissions.user_id')
                                          ->leftJoin('users AS u', 'u.code', 'affiliate_commissions.user_id')
                                          ->leftJoin('agents AS mb', 'mb.code', 'affiliate_commissions.user_by')
                                          ->leftJoin('agent_levels as al', 'al.id', 'm.lvl')
                                          ->leftJoin('transactions AS t', 't.transaction_no', 'affiliate_commissions.transaction_no')
                                          ->leftJoin('agents AS mt', 'mt.code', 'affiliate_commissions.user_by')
                                          ->leftJoin('admins AS at', 'at.code', 'affiliate_commissions.user_by')
                                          ->leftJoin('users AS ut', 'ut.code', 'affiliate_commissions.user_by')
                                          ->where('affiliate_commissions.comm_amount', '>', '0');
    
        if(Auth::guard('merchant')->check()){
            $commissions = $commissions->where('affiliate_commissions.merchant_id', Auth::user()->code);
        }

        $commissions = $commissions->orderBy('affiliate_commissions.created_at', 'desc')
                                   ->orderBy('affiliate_commissions.user_id', 'desc');

        $totalCommission = AffiliateCommission::select(DB::raw("SUM(IF(type = '1', comm_amount, NULL)) as totalAgentBonus"),
                                                       DB::raw("SUM(IF(type = '2', comm_amount, NULL)) as totalAgentRebateBonus"),
                                                       DB::raw("SUM(IF(type = '3', comm_amount, NULL)) as totalAffiliateBonus"),
                                                       DB::raw("SUM(IF(type = '4', comm_amount, NULL)) as totalPerformance"),
                                                       DB::raw("SUM(IF(type = '5', comm_amount, NULL)) as totalTeam"),
                                                       DB::raw("SUM(IF(type = '6', comm_amount, NULL)) as totalRefferal"),
                                                       DB::raw("SUM(IF(type = '7', comm_amount, NULL)) as totalProduct"))
                                              ->leftjoin('agents AS m', 'm.code', 'affiliate_commissions.user_id')
                                              ->leftjoin('admins AS a', 'a.code', 'affiliate_commissions.user_id');

        $queries = [];
        $columns = [
            'transaction_no', 'comm_type', 'referrer_name', 'referrer_code', 'referrer_ic', 'dates', 'agent', 'agent_code', 'agent_ic', 'status', 'yearly', 'monthly','daily'
        ];
        
        foreach($columns as $column){
            if(request()->has($column) && !empty(request($column))){
                if($column == 'status'){
                    $statusVal = strtolower(request('status'));
                    if ($statusVal === 'approved' || $statusVal === '1') {
                        $commissions = $commissions->where('affiliate_commissions.status', 1);
                        $totalCommission = $totalCommission->where('affiliate_commissions.status', 1);
                    } elseif ($statusVal === 'burned' || $statusVal === '2') {
                        $commissions = $commissions->where('affiliate_commissions.status', 2)
                                                   ->where('affiliate_commissions.burned', 1);
                        $totalCommission = $totalCommission->where('affiliate_commissions.status', 2)
                                                           ->where('affiliate_commissions.burned', 1);
                    }
                }elseif($column == 'dates'){
                    if(empty(request('yearly')) && empty(request('monthly')) && empty(request('daily'))){
                        $commissions = $commissions->whereBetween(DB::raw('DATE_FORMAT(affiliate_commissions.created_at, "%Y-%m-%d")'), array($start, $end));
                        $totalCommission = $totalCommission->whereBetween(DB::raw('DATE_FORMAT(affiliate_commissions.created_at, "%Y-%m-%d")'), array($start, $end));
                    }
                }elseif($column == 'yearly'){
                    $commissions = $commissions->where(DB::raw('DATE_FORMAT(affiliate_commissions.created_at, "%Y")'), request($column));
                    $totalCommission = $totalCommission->where(DB::raw('DATE_FORMAT(affiliate_commissions.created_at, "%Y")'), request($column));

                    $startYear = now()->startOfYear();
                    $endYear = now()->endOfYear();

                    $startDate = $startYear->format('d/m/Y');
                    $endDate = $endYear->format('d/m/Y');
                }elseif($column == 'monthly'){
                    // if(!empty(request('yearly'))){
                    //   $checkMonth = (strlen(request($column)) > 1) ? request($column) : '0'.request($column);
                    //   $commissions = $commissions->where(DB::raw('DATE_FORMAT(affiliate_commissions.created_at, "%Y-%m")'), request('yearly').'-'.$checkMonth);
                    //   $totalCommission = $totalCommission->where(DB::raw('DATE_FORMAT(affiliate_commissions.created_at, "%Y-%m")'), request('yearly').'-'.$checkMonth);
                      
                    // }else{
                      $commissions = $commissions->where(DB::raw('DATE_FORMAT(affiliate_commissions.created_at, "%Y-%m")'), request($column));
                      $totalCommission = $totalCommission->where(DB::raw('DATE_FORMAT(affiliate_commissions.created_at, "%Y-%m")'), request($column));
                    // }
                }elseif($column == 'daily'){
    
                    $checkDay = (strlen(request($column)) > 1) ? request($column) : '0'.request($column);
                    
                    
                    $commissions = $commissions->where(DB::raw('DATE_FORMAT(affiliate_commissions.created_at, "%Y-%m-%d")'), $searchYear.'-'.$searchMonth.'-'.$checkDay);
                    $totalCommission = $totalCommission->where(DB::raw('DATE_FORMAT(affiliate_commissions.created_at, "%Y-%m-%d")'), $searchYear.'-'.$searchMonth.'-'.$checkDay);
                    
                }elseif($column == 'transaction_no'){
                    $commissions = $commissions->where('affiliate_commissions.transaction_no', 'like', "%".request($column)."%");
                    $totalCommission = $totalCommission->where('affiliate_commissions.transaction_no', 'like', "%".request($column)."%");
                }elseif($column == 'agent'){
                    // $totalCommission = $totalCommission->where(DB::raw('COALESCE(CONCAT(m.f_name, " ", m.l_name),CONCAT(a.f_name, " ", a.l_name))'), 'like', "%".request($column)."%");
                    $totalCommission = $totalCommission->whereRaw("
                                CONVERT(
                                    COALESCE(
                                        CONCAT(m.f_name, ' ', m.l_name),
                                        CONCAT(a.f_name, ' ', a.l_name)
                                    )
                                USING utf8mb4) COLLATE utf8mb4_unicode_ci LIKE ?
                            ", ['%' . request($column) . '%']);
                    $commissions = $commissions->whereRaw("
                                CONVERT(
                                    COALESCE(
                                        CONCAT(mt.f_name, ' ', mt.l_name),
                                        CONCAT(ut.f_name, ' ', ut.l_name)
                                    )
                                USING utf8mb4) COLLATE utf8mb4_unicode_ci LIKE ?
                            ", ['%' . request($column) . '%']);
                }elseif($column == 'agent_code'){

                    $commissions = $commissions->where(DB::raw('COALESCE(COALESCE(mt.code, ut.code), at.code)'), 'like', "%".request($column)."%");
                    $totalCommission = $totalCommission->where(DB::raw('COALESCE(m.code, a.code)'), 'like', "%".request($column)."%");

                }elseif($column == 'referrer_name'){
                    $commissions = $commissions->whereRaw("
                        CONVERT(
                            COALESCE(
                                CONCAT(m.f_name, ' ', m.l_name),
                                CONCAT(a.f_name, ' ', a.l_name)
                            )
                        USING utf8mb4) COLLATE utf8mb4_unicode_ci LIKE ?
                    ", ['%' . request($column) . '%']);
                }elseif($column == 'referrer_code'){
                  $commissions = $commissions->where(DB::raw('COALESCE(m.code, a.code)'), 'like', "%".request($column)."%");
                }elseif($column == 'comm_type'){
                    $commissions = $commissions->where('affiliate_commissions.comm_desc', 'like', "%".request($column)."%");
                }else{
                    $commissions = $commissions->where($column, 'like', "%".request($column)."%");
                    $totalCommission = $totalCommission->where('affiliate_commissions.transaction_no', 'like', "%".request($column)."%");
                }

                $queries[$column] = request($column);

            }
        }


        // Default to approved when no explicit status filter supplied
        if (empty(request('status'))) {
            $commissions = $commissions->where('affiliate_commissions.status', 1);
            $totalCommission = $totalCommission->where('affiliate_commissions.status', 1);
        }

        $commissions = $commissions->get();
        $totalCommission = $totalCommission->first();

        $netTotal = AffiliateCommission::select(DB::raw('SUM(comm_amount) AS netTotalCommission'))
                                       ->where('status', '1')
                                       ->first();
        

        

        return view('backend.reports.print_commission_report', ['startDate'=>$startDate, 'endDate'=>$endDate,
                                                                'commissions'=>$commissions, 'totalCommission'=>$totalCommission,
                                                                'netTotal'=>$netTotal]);
    }

    public function exportCommissionReport()
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

        if(!empty(request('yearly'))){
            $checkYear = request('yearly');
        }else{
            $checkYear = "";
        }

        if(!empty(request('monthly'))){
            $checkMonth = (strlen(request('monthly')) > 1) ? request('monthly') : '0'.request('monthly');
        }else{
            $checkMonth = "";
        }

        if(!empty(request('daily'))){
            $checkDay = (strlen(request('daily')) > 1) ? request('daily') : '0'.request('daily');            
        }else{
            $checkDay = "";
        }

        if(!empty(request('agent'))){
            $agent = request('agent');            
        }else{
            $agent = "";
        }

        if(!empty(request('agent_code'))){
            $agent_code = request('agent_code');            
        }else{
            $agent_code = "";
        }

        if(!empty(request('transaction_no'))){
            $transaction_no = request('transaction_no');            
        }else{
            $transaction_no = "";
        }

        if(!empty(request('referrer_name'))){
            $referrer_name = request('referrer_name');            
        }else{
            $referrer_name = "";
        }

        if(!empty(request('referrer_code'))){
            $referrer_code = request('referrer_code');            
        }else{
            $referrer_code = "";
        }

        if(!empty(request('comm_type'))){
            $comm_type = request('comm_type');
        }else{
            $comm_type = "";
        }        


        $status = request('status') ?? null;
        return Excel::download(new CommissionExport($start, $end, $checkYear, $checkMonth, $checkDay, $agent,$agent_code,$transaction_no,$referrer_name,$referrer_code,$comm_type, $status), date('Y-m-d').' '.'Commission Report'.'.xlsx');
    }

    public function redemption_report()
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


        $transactions = Transaction::select(DB::raw('CASE 
                                                     WHEN m.id != "" THEN COALESCE(m.f_name, m.phone)
                                                     WHEN a.id != "" THEN COALESCE(a.f_name, a.phone)
                                                     WHEN u.id != "" THEN COALESCE(u.f_name, u.phone)
                                                 END AS buyer_name'),
                                        DB::raw('CASE 
                                                     WHEN m.id != "" THEN "Agent"
                                                     WHEN a.id != "" THEN "Admin"
                                                     WHEN u.id != "" THEN "Customer"
                                                 END AS buyer_role'),
                                        'transactions.transaction_no', 'transactions.status', 'transactions.created_at',
                                        'transactions.id AS Tid', 'transactions.grand_total', 'transactions.shipping_fee', 
                                        'transactions.processing_fee', 'transactions.discount', 'transactions.address_name',
                                        'transactions.ad_discount')
                               ->leftJoin('merchants AS m', 'm.code', 'transactions.user_id')
                               ->leftJoin('admins AS a', 'a.code', 'transactions.user_id')
                               ->leftJoin('users AS u', 'u.code', 'transactions.user_id')
                               ->where('transactions.status', '1')
                               ->where('transactions.mall', '1')
                               ->whereBetween(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), array($start, $end))
                               ->orderBy('transactions.created_at', 'desc');

        $totalT = Transaction::select(DB::raw('SUM(d.quantity) as totalQty'),
                                      DB::raw('SUM(d.unit_price) as totalUnitPrice'),
                                      DB::raw('SUM(d.unit_price * d.quantity) as totalNet'),
                                      DB::raw('SUM((d.unit_price * d.quantity) + transactions.processing_fee + transactions.shipping_fee + transactions.tax) as totalGrand'))
                                   ->join('transaction_details AS d', 'd.transaction_id', 'transactions.id')
                                   ->where('transactions.status', '1')
                                   ->where('transactions.mall', '1')
                                   ->whereBetween(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), array($start, $end))
                                   ->orderBy('transactions.created_at', 'desc');

        $totalT2 = Transaction::select(DB::raw('SUM(transactions.processing_fee) as totalProcessingFee'),
                                      DB::raw('SUM(transactions.shipping_fee) as totalShippingFee'),
                                      DB::raw('SUM(transactions.tax) as totalTax'),
                                      DB::raw('SUM(transactions.discount) as totalDiscount'),
                                      DB::raw('SUM(transactions.ad_discount) as totalAdDiscount'))
                              ->where('transactions.status', '1')
                              ->where('transactions.mall', '1')
                              ->whereBetween(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), array($start, $end))
                              ->orderBy('transactions.created_at', 'desc');

        $queries = [];
        $columns = [
            'transaction_no', 'dates', 'buyer', 'per_page', 'status'
        ];

        $per_page = 10;
        if(!empty(request('per_page'))){
            $per_page = request('per_page');
        }

        foreach($columns as $column){
            if(request()->has($column) && !empty(request($column))){
                if($column == 'status'){
                    $transactions = $transactions->where('transactions.status', 'like', "%".request($column)."%");
                }elseif($column == 'dates'){
                    $transactions = $transactions->whereBetween(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), array($start, $end));
                }elseif($column == 'buyer'){
                    $transactions = $transactions->where(DB::raw('CONCAT(m.f_name, " ", m.l_name)'), 'like', "%".request($column)."%")
                                                 ->orWhere(DB::raw('CONCAT(a.f_name, " ", a.l_name)'), 'like', "%".request($column)."%")
                                                 ->orWhere(DB::raw('CONCAT(u.f_name, " ", u.l_name)'), 'like', "%".request($column)."%");
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

        $totalT = $totalT->first();
        $totalT2 = $totalT2->first();

        $details = [];
        $details2 = [];
        foreach($transactions as $transaction){
            $details[$transaction->Tid] = TransactionDetail::where("transaction_id", $transaction->Tid)->get();   
            $details2[$transaction->Tid] = TransactionDetail::select(DB::raw('SUM(unit_price*quantity) AS totalPrice'))
                                                            ->where("transaction_id", $transaction->Tid)
                                                            ->first();
        }

        return view('backend.reports.redemption_report', ['transactions'=>$transactions, 'startDate'=>$startDate, 'endDate'=>$endDate, 
                                                     'totalT'=>$totalT, 'totalT2'=>$totalT2],
                                                     compact('details', 'details2'));
    }

    public function print_redemption_report()
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
        
        $transactions = Transaction::select(DB::raw('CASE 
                                                     WHEN m.id != "" THEN COALESCE(m.f_name, m.phone)
                                                     WHEN a.id != "" THEN COALESCE(a.f_name, a.phone)
                                                     WHEN u.id != "" THEN COALESCE(u.f_name, u.phone)
                                                 END AS buyer_name'),
                                        DB::raw('CASE 
                                                     WHEN m.id != "" THEN "Agent"
                                                     WHEN a.id != "" THEN "Admin"
                                                     WHEN u.id != "" THEN "Customer"
                                                 END AS buyer_role'),
                                        'transactions.transaction_no', 'transactions.status', 'transactions.created_at',
                                        'transactions.id AS Tid', 'transactions.grand_total', 'transactions.shipping_fee', 
                                        'transactions.processing_fee', 'transactions.discount', 'transactions.address_name',
                                        'transactions.ad_discount')
                               ->leftJoin('merchants AS m', 'm.code', 'transactions.user_id')
                               ->leftJoin('admins AS a', 'a.code', 'transactions.user_id')
                               ->leftJoin('users AS u', 'u.code', 'transactions.user_id')
                               ->where('transactions.status', '1')
                               ->where('transactions.mall', '1')
                               ->whereBetween(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), array($start, $end))
                               ->orderBy('transactions.created_at', 'desc');

        $totalT = Transaction::select(DB::raw('SUM(d.quantity) as totalQty'),
                                      DB::raw('SUM(d.unit_price) as totalUnitPrice'),
                                      DB::raw('SUM(d.unit_price * d.quantity) as totalNet'),
                                      DB::raw('SUM((d.unit_price * d.quantity) + transactions.processing_fee + transactions.shipping_fee + transactions.tax) as totalGrand'))
                                   ->join('transaction_details AS d', 'd.transaction_id', 'transactions.id')
                                   ->where('transactions.status', '1')
                                   ->where('transactions.mall', '1')
                                   ->whereBetween(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), array($start, $end))
                                   ->orderBy('transactions.created_at', 'desc');

        $totalT2 = Transaction::select(DB::raw('SUM(transactions.processing_fee) as totalProcessingFee'),
                                      DB::raw('SUM(transactions.shipping_fee) as totalShippingFee'),
                                      DB::raw('SUM(transactions.tax) as totalTax'),
                                      DB::raw('SUM(transactions.discount) as totalDiscount'),
                                      DB::raw('SUM(transactions.ad_discount) as totalAdDiscount'))
                              ->where('transactions.status', '1')
                              ->where('transactions.mall', '1')
                              ->whereBetween(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), array($start, $end))
                              ->orderBy('transactions.created_at', 'desc');

        $queries = [];
        $columns = [
            'transaction_no', 'dates', 'buyer', 'status'
        ];

        foreach($columns as $column){
            if(request()->has($column) && !empty(request($column))){
                if($column == 'status'){
                    $transactions = $transactions->where('transactions.status', 'like', "%".request($column)."%");
                }elseif($column == 'dates'){
                    $transactions = $transactions->whereBetween(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), array($start, $end));
                }elseif($column == 'buyer'){
                    $transactions = $transactions->where(DB::raw('CONCAT(m.f_name, " ", m.l_name)'), 'like', "%".request($column)."%")
                                                 ->orWhere(DB::raw('CONCAT(a.f_name, " ", a.l_name)'), 'like', "%".request($column)."%")
                                                 ->orWhere(DB::raw('CONCAT(u.f_name, " ", u.l_name)'), 'like', "%".request($column)."%");
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

        $transactions = $transactions->get();

        $totalT = $totalT->first();
        $totalT2 = $totalT2->first();

        $details = [];
        $details2 = [];
        foreach($transactions as $transaction){
            $details[$transaction->Tid] = TransactionDetail::where("transaction_id", $transaction->Tid)->get();   
            $details2[$transaction->Tid] = TransactionDetail::select(DB::raw('SUM(unit_price*quantity) AS totalPrice'))
                                                            ->where("transaction_id", $transaction->Tid)
                                                            ->first();
        }

        return view('backend.reports.print_redemption_report', ['transactions'=>$transactions, 'startDate'=>$startDate, 'endDate'=>$endDate, 
                                                     'totalT'=>$totalT, 'totalT2'=>$totalT2],
                                                     compact('details', 'details2'));
    }

    public function ExportRedemtion()
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

        return Excel::download(new RedemptionExport($start, $end), 'RedemptionReport'.strtotime(now()).'.xlsx');
    }

    public function stock_report()
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


        // $products = Product::select('product_name','id','item_code',DB::raw("'NormalProduct' as type"))->where('status', '!=', '3')
        //                    ->orderBy('product_name', 'asc')->where('variation_enable',0)->where('second_variation_enable',0);

        $normal_products = Product::select('products.product_name','products.id','products.packages','products.item_code',DB::raw("'NormalProduct' as type"), DB::raw("'0' as variation_id"), DB::raw("'0' as second_variation_id"))->where('products.status', '!=', '3')
        ->leftJoin('stocks as s', 's.product_id', 'products.id')
        ->whereNull('s.variation_id')
        ->whereNull('s.second_variation_id');

        $variation_products = ProductVariation::select([
            DB::raw("CONCAT(p.product_name, '<br>Variation: ', product_variations.variation_name) as product_name"),
            'p.id','p.packages',
            'p.item_code',
            DB::raw("'VariationProduct' as type"),
            'product_variations.id as variation_id',
            DB::raw("'0' as second_variation_id")
        ])
        ->leftJoin('products as p', 'p.id', 'product_variations.product_id')
        ->leftJoin('stocks as s', 's.product_id', 'p.id')
        ->whereNotNull('s.variation_id')
        ->whereNull('s.second_variation_id')
        ->where('p.status', '!=', '3');

        $second_variation_products = ProductSecondVariation::select([
            DB::raw("CONCAT(p.product_name, '<br>Variation: ', v.variation_name, '<br>Second Variation: ', product_second_variations.variation_name) as product_name"),
            'p.id','p.packages',
            'p.item_code',
            DB::raw("'SecondVariationProduct' as type"),
            'v.id as variation_id',
            "product_second_variations.id as second_variation_id",
        ])
        ->leftJoin('products as p', 'p.id', 'product_second_variations.product_id')
        ->leftJoin('product_variations as v', 'v.id', 'product_second_variations.variation_id')
        ->where('p.status', '!=', '3');
        

        // if(Auth::guard('merchant')->check()){
        //     $products = $products->where('merchant_id', Auth::user()->code);
        // }

        // $queries = [];
        // $columns = [
        //     'item_code', 'product_name', 'dates', 'buyer', 'status','per_page'
        // ];

        // $per_page = 10;
        // if(!empty(request('per_page'))){
        //     $per_page = request('per_page');
        // }

        // foreach($columns as $column){
        //     if(request()->has($column) && !empty(request($column))){
        //         if($column == 'status'){
        //             $products = $products->where('products.status', 'like', "%".request($column)."%");
        //         }elseif($column == 'dates'){
        //             $products = $products->whereBetween(DB::raw('DATE_FORMAT(products.created_at, "%Y-%m-%d")'), array($start, $end));
        //         }elseif($column == 'product_name'){
        //             $products = $products->where('product_name', 'like', "%".request($column)."%");
        //         }elseif($column == 'item_code'){
        //             $products = $products->where('item_code', 'like', "%".request($column)."%");
        //         }elseif($column == 'per_page'){
        //           $products = $products->paginate($per_page);
        //         }else{
        //             $products = $products->where($column, 'like', "%".request($column)."%");
        //         }

        //         $queries[$column] = request($column);
        //     }
        // }

        $queries = [];
        $columns = [
            'item_code', 'product_name'
        ];

        $per_page = 10;
        if(!empty(request('per_page'))){
            $per_page = request('per_page');
        }

        foreach($columns as $column){
            if(request()->has($column) && !empty(request($column))){
                if($column == 'product_name'){
                    $normal_products = $normal_products->where('product_name', 'like', "%".request($column)."%");
                    $variation_products = $variation_products->where('product_name', 'like', "%".request($column)."%");
                    $second_variation_products = $second_variation_products->where('product_name', 'like', "%".request($column)."%");
                }elseif($column == 'item_code'){
                    $normal_products = $normal_products->where('item_code', 'like', "%".request($column)."%");
                    $variation_products = $variation_products->where('item_code', 'like', "%".request($column)."%");
                    $second_variation_products = $second_variation_products->where('item_code', 'like', "%".request($column)."%");
                }

                $queries[$column] = request($column);
            }
        }

        $products = $normal_products
        ->union($variation_products)
        ->union($second_variation_products);

        $products = $products->orderBy('product_name', 'asc');

        // if(!empty(request('per_page'))){
        //     $products = $products->appends($queries);
        // }else{
        $products = $products->paginate($per_page)->appends($queries);    
        // }

        $totalInStock = [];
        $totalOutStock = [];
        $totalSoldStock = [];
        $totalSoldStockByDelivery = [];
        $currentStockAmount = [];
        $stockSoldPrice = [];
        $stockCostPrice = [];
        foreach ($products as $key => $product) {
            if(!empty($product->packages)){
                $totalInStock[$key] = Stock::select(DB::raw('SUM(IF(type = "Increase", quantity, NULL)) AS totalStockIn'))
                                                   ->where('product_id', $product->id)
                                                   ->first();
                $totalOutStock[$key] = Stock::select(DB::raw('SUM(IF(type = "Decrease", quantity, NULL)) AS totalStockOut'))
                                                    ->where('product_id', $product->id)
                                                    ->first();
                $totalSoldStock[$key] = TransactionDetail::select(DB::raw('SUM(quantity) AS TransCart'))
                                          ->join('transactions AS t', 't.id', 'transaction_details.transaction_id')
                                          ->where('t.status', '1')
                                          // ->where('t.on_hold', '!=', '99')
                                          ->where('product_id', $product->id)
                                          ->first();

                $totalSoldStockByDelivery[$key] = TransactionDetail::select(DB::raw('SUM(quantity) AS TransCart'))
                                          ->join('transactions AS t', 't.id', 'transaction_details.transaction_id')
                                          ->where('t.status', '1')
                                          ->whereNull('t.on_hold')
                                          ->where('product_id', $product->id)
                                          ->first(); 

                $currentStockAmount[$key] = $totalInStock[$key]->totalStockIn - $totalOutStock[$key]->totalStockOut - $totalSoldStockByDelivery[$key]->TransCart;

            }elseif(empty($product->packages)){
                $totalInStock[$key] = Stock::select(DB::raw('SUM(IF(type = "Increase", quantity, NULL)) AS totalStockIn'))
                ->where('product_id', $product->id)
                ->whereNull('packages_id')
                ->when($product->type === 'NormalProduct', function ($query) {
                    $query->whereNull('variation_id')->whereNull('second_variation_id');
                })
                ->when($product->type === 'VariationProduct', function ($query) use ($product) {
                    $query->where('variation_id', $product->variation_id)
                        ->whereNull('second_variation_id');
                })
                ->when($product->type === 'SecondVariationProduct', function ($query) use ($product) {
                    $query->where('variation_id', $product->variation_id)
                        ->where('second_variation_id', $product->second_variation_id);
                })
                ->first();

                $totalOutStock[$key] = Stock::select(DB::raw('SUM(IF(type = "Decrease", quantity, NULL)) AS totalStockOut'))
                ->where('product_id', $product->id)
                ->whereNull('packages_id')
                ->when($product->type === 'NormalProduct', function ($query) {
                    $query->whereNull('variation_id')->whereNull('second_variation_id');
                })
                ->when($product->type === 'VariationProduct', function ($query) use ($product) {
                    $query->where('variation_id', $product->variation_id)
                        ->whereNull('second_variation_id');
                })
                ->when($product->type === 'SecondVariationProduct', function ($query) use ($product) {
                    $query->where('variation_id', $product->variation_id)
                        ->where('second_variation_id', $product->second_variation_id);
                })
                ->first();

                $totalSoldStock[$key] = TransactionDetail::select(DB::raw('SUM(quantity) AS TransCart'))
                ->join('transactions AS t', 't.id', 'transaction_details.transaction_id')
                ->where('t.status', '1')
                ->where('t.on_hold', '!=', '99')
                ->when($product->type === 'NormalProduct', function ($query) {
                    $query->where('transaction_details.variation_id',0)->where('transaction_details.second_variation_id',0);
                })
                ->when($product->type === 'VariationProduct', function ($query) use ($product) {
                    $query->where('transaction_details.variation_id', $product->variation_id)
                        ->where('transaction_details.second_variation_id',0);
                })
                ->when($product->type === 'SecondVariationProduct', function ($query) use ($product) {
                    $query->where('transaction_details.variation_id', $product->variation_id)
                        ->where('transaction_details.second_variation_id', $product->second_variation_id);
                })
                ->where('product_id', $product->id)
                ->first();

                $totalSoldStockByDelivery[$key] = TransactionDetail::select(DB::raw('SUM(IF(tp.quantity > 0, (tp.quantity * transaction_details.quantity), transaction_details.quantity)) AS TransCart'))
                ->join('transactions AS t', 't.id', 'transaction_details.transaction_id')
                ->leftJoin('transaction_packages AS tp', 'tp.detail_id', 'transaction_details.id')
                ->where('t.status', '1')
                ->whereNull('transaction_details.deduct_qty')
                 ->when($product->type === 'NormalProduct', function ($query) {
                    $query->where('transaction_details.variation_id',0)->where('transaction_details.second_variation_id',0);
                })
                ->when($product->type === 'VariationProduct', function ($query) use ($product) {
                    $query->where('transaction_details.variation_id', $product->variation_id)
                        ->where('transaction_details.second_variation_id',0);
                })
                ->when($product->type === 'SecondVariationProduct', function ($query) use ($product) {
                    $query->where('transaction_details.variation_id', $product->variation_id)
                        ->where('transaction_details.second_variation_id', $product->second_variation_id);
                })
                ->where(DB::raw('IF(tp.product_id > 0, tp.product_id, transaction_details.product_id)'), $product->id)
                ->first();

                $currentStockAmount[$key] = $totalInStock[$key]->totalStockIn - $totalOutStock[$key]->totalStockOut - $totalSoldStockByDelivery[$key]->TransCart;

            }
            // else{
            //     $totalInStock[$key] = Stock::select(DB::raw('SUM(IF(type = "Increase", quantity, NULL)) AS totalStockIn'))
            //                                     ->where('product_id', $product->id)
            //                                     ->WhereNull('packages_id')
            //                                     ->first();

            //     $totalOutStock[$key] = Stock::select(DB::raw('SUM(IF(type = "Decrease", quantity, NULL)) AS totalStockOut'))
            //                                     ->where('product_id', $product->id)
            //                                     ->WhereNull('packages_id')
            //                                     ->first();

            //     $totalSoldStock[$key] = TransactionDetail::select(DB::raw('SUM(quantity) AS TransCart'))
            //                               ->join('transactions AS t', 't.id', 'transaction_details.transaction_id')
            //                               ->where('t.status', '1')
            //                               // ->where('t.on_hold', '!=', '99')
            //                               ->where('product_id', $product->id)
            //                               ->first();

            //     $totalSoldStockByDelivery[$key] = TransactionDetail::select(DB::raw('SUM(quantity) AS TransCart'))
            //                               ->join('transactions AS t', 't.id', 'transaction_details.transaction_id')
            //                               ->where('t.status', '1')
            //                               ->whereNull('t.on_hold')
            //                               ->where('product_id', $product->id)
            //                               ->first();

            //     $currentStockAmount[$key] = $totalInStock[$key]->totalStockIn - $totalOutStock[$key]->totalStockOut - $totalSoldStockByDelivery[$key]->TransCart;
            // }

            $stockDetail[$key] = TransactionDetail::select(DB::raw('SUM(unit_price) AS TotalSoldPrice'),
                                                                DB::raw('SUM(costing_price) AS TotalCostingPrice'))
                                            ->join('transactions AS t', 't.id', 'transaction_details.transaction_id')
                                            ->when($product->type === 'NormalProduct', function ($query) {
                                                $query->where('transaction_details.variation_id',0)->where('transaction_details.second_variation_id',0);
                                            })
                                            ->when($product->type === 'VariationProduct', function ($query) use ($product) {
                                                $query->where('transaction_details.variation_id', $product->variation_id)
                                                    ->where('transaction_details.second_variation_id',0);
                                            })
                                            ->when($product->type === 'SecondVariationProduct', function ($query) use ($product) {
                                                $query->where('transaction_details.variation_id', $product->variation_id)
                                                    ->where('transaction_details.second_variation_id', $product->second_variation_id);
                                            })
                                          ->where('t.status', '1')
                                          ->where('product_id', $product->id)
                                          ->first();

            $stockSoldPrice[$key] = $stockDetail[$key]->TotalSoldPrice;
            $stockCostPrice[$key] = $stockDetail[$key]->TotalCostingPrice;
        }

        return view('backend.reports.stock_report', ['products'=>$products, 'startDate'=>$startDate, 'endDate'=>$endDate], compact('totalInStock', 'totalOutStock', 'totalSoldStock', 'currentStockAmount', 'totalSoldStockByDelivery', 'stockSoldPrice', 'stockCostPrice'));
    }

    public function stock_report_details($product_id)
    {
        if (!empty(request('dates'))) {
            $new_dates = explode('-', request('dates'));
            $start = DateTime::createFromFormat('d/m/Y', trim($new_dates[0]))->format('Y-m-d');
            $end = DateTime::createFromFormat('d/m/Y', trim($new_dates[1]))->format('Y-m-d');

            $startDate = trim($new_dates[0]);
            $endDate = trim($new_dates[1]);
        } else {
        // $ds = new DateTime("first day of this month");
        // $de = new DateTime("last day of this month");

        $ds = new DateTime("first day of January". date('Y'));
        $de = new DateTime("last day of December". date('Y'));

        $start = $ds->format('Y-m-d');
        $end = $de->format('Y-m-d');

        $startDate = $ds->format('d/m/Y');
        $endDate = $de->format('d/m/Y');
      }

      $products = Product::find($product_id);

      $stockBalance = Stock::select(DB::raw('IF(stocks.type = "Increase", stocks.quantity, NULL) AS totalStockIn'),
                                      DB::raw('IF(stocks.type = "Decrease", stocks.quantity, NULL) AS totalStockOut'), 'p.product_name', 'stocks.created_at', 'stocks.product_id')
                                ->leftjoin('products as p', 'p.id', 'stocks.product_id')
                                ->where('stocks.product_id', $products->id)
                                ->when(empty(request('variation')) && empty(request('second_variation')), function ($query) {
                                    $query->whereNull('stocks.variation_id')->whereNull('stocks.second_variation_id');
                                })
                                ->when(!empty(request('variation')) && empty(request('second_variation')), function ($query) {
                                    $query->where('stocks.variation_id', request('variation'))
                                        ->whereNull('stocks.second_variation_id');
                                })
                                ->when(!empty(request('variation')) && !empty(request('second_variation')), function ($query)  {
                                    $query->where('stocks.variation_id', request('variation'))
                                        ->where('stocks.second_variation_id', request('second_variation'));
                                })
                                ->WhereNull('stocks.packages_id')
                                ->whereBetween(DB::raw('DATE_FORMAT(stocks.created_at, "%Y-%m-%d")'), array($start, $end));

      $transaction = TransactionDetail::select(DB::raw('quantity AS TransCart'), 'transaction_details.product_name', 'transaction_details.created_at', 'transaction_details.transaction_id', 't.transaction_no', 'transaction_details.costing_price', 'transaction_details.unit_price')
                                ->join('transactions AS t', 't.id', 'transaction_details.transaction_id')
                                ->where('t.status', '1')
                                ->when(empty(request('variation')) && empty(request('second_variation')), function ($query) {
                                    $query->where('transaction_details.variation_id',0)->where('transaction_details.second_variation_id',0);
                                })
                                ->when(!empty(request('variation')) && empty(request('second_variation')),function ($query) {
                                    $query->where('transaction_details.variation_id', request('variation'))
                                        ->where('transaction_details.second_variation_id',0);
                                })
                                ->when(!empty(request('variation')) && !empty(request('second_variation')), function ($query) {
                                    $query->where('transaction_details.variation_id', request('variation'))
                                        ->where('transaction_details.second_variation_id', request('second_variation'));
                                })
                                // ->where('t.on_hold', '!=', '99')
                                ->where('product_id', $products->id)
                                ->whereBetween(DB::raw('DATE_FORMAT(transaction_details.created_at, "%Y-%m-%d")'), array($start, $end));

      $transactionDelivery = TransactionDetail::select(DB::raw('IF(tp.quantity > 0, (tp.quantity * transaction_details.quantity), transaction_details.quantity) AS TransCart'), 'transaction_details.product_name', 'transaction_details.created_at', 'transaction_details.transaction_id', 't.transaction_no', 'transaction_details.costing_price', 'transaction_details.unit_price')
                                      ->join('transactions AS t', 't.id', 'transaction_details.transaction_id')
                                      ->join('transaction_packages AS tp', 'tp.detail_id', 'transaction_details.id')
                                      ->where('t.status', '1')
                                        ->when(empty(request('variation')) && empty(request('second_variation')), function ($query) {
                                            $query->where('transaction_details.variation_id',0)->where('transaction_details.second_variation_id',0);
                                        })
                                        ->when(!empty(request('variation')) && empty(request('second_variation')),function ($query) {
                                            $query->where('transaction_details.variation_id', request('variation'))
                                                ->where('transaction_details.second_variation_id',0);
                                        })
                                        ->when(!empty(request('variation')) && !empty(request('second_variation')), function ($query) {
                                            $query->where('transaction_details.variation_id', request('variation'))
                                                ->where('transaction_details.second_variation_id', request('second_variation'));
                                        })
                                      ->whereNull('transaction_details.deduct_qty')
                                      ->where(DB::raw('IF(tp.product_id > 0, tp.product_id, transaction_details.product_id)'), $products->id)
                                      ->whereBetween(DB::raw('DATE_FORMAT(transaction_details.created_at, "%Y-%m-%d")'), array($start, $end));

      $stockBalance1 = Stock::select(DB::raw('IF(stocks.type = "Increase", stocks.quantity, NULL) AS totalStockIn'),
                                      DB::raw('IF(stocks.type = "Decrease", stocks.quantity, NULL) AS totalStockOut'), 'p.product_name', 'stocks.created_at', 'stocks.product_id')
                                ->leftjoin('products as p', 'p.id', 'stocks.product_id')
                                ->where('stocks.product_id', $products->id)
                                ->when(empty(request('variation')) && empty(request('second_variation')), function ($query) {
                                    $query->whereNull('stocks.variation_id')->whereNull('stocks.second_variation_id');
                                })
                                ->when(!empty(request('variation')) && empty(request('second_variation')), function ($query) {
                                    $query->where('stocks.variation_id', request('variation'))
                                        ->whereNull('stocks.second_variation_id');
                                })
                                ->when(!empty(request('variation')) && !empty(request('second_variation')), function ($query)  {
                                    $query->where('stocks.variation_id', request('variation'))
                                        ->where('stocks.second_variation_id', request('second_variation'));
                                })
                                ->WhereNull('stocks.packages_id');

      $transaction1 = TransactionDetail::select(DB::raw('quantity AS TransCart'), 'transaction_details.product_name', 'transaction_details.created_at', 'transaction_details.transaction_id', 't.transaction_no')
                                      ->join('transactions AS t', 't.id', 'transaction_details.transaction_id')
                                      ->where('t.status', '1')
                                        ->when(empty(request('variation')) && empty(request('second_variation')), function ($query) {
                                            $query->where('transaction_details.variation_id',0)->where('transaction_details.second_variation_id',0);
                                        })
                                        ->when(!empty(request('variation')) && empty(request('second_variation')),function ($query) {
                                            $query->where('transaction_details.variation_id', request('variation'))
                                                ->where('transaction_details.second_variation_id',0);
                                        })
                                        ->when(!empty(request('variation')) && !empty(request('second_variation')), function ($query) {
                                            $query->where('transaction_details.variation_id', request('variation'))
                                                ->where('transaction_details.second_variation_id', request('second_variation'));
                                        })
                                      // ->where('t.on_hold', '!=', '99')
                                      ->where('product_id', $products->id);

      $transactionDelivery1 = TransactionDetail::select(DB::raw('quantity AS TransCart'), 'transaction_details.product_name', 'transaction_details.created_at', 'transaction_details.transaction_id', 't.transaction_no')
                                      ->join('transactions AS t', 't.id', 'transaction_details.transaction_id')
                                      ->where('t.status', '1')
                                      ->whereNull('t.on_hold')
                                        ->when(empty(request('variation')) && empty(request('second_variation')), function ($query) {
                                            $query->where('transaction_details.variation_id',0)->where('transaction_details.second_variation_id',0);
                                        })
                                        ->when(!empty(request('variation')) && empty(request('second_variation')),function ($query) {
                                            $query->where('transaction_details.variation_id', request('variation'))
                                                ->where('transaction_details.second_variation_id',0);
                                        })
                                        ->when(!empty(request('variation')) && !empty(request('second_variation')), function ($query) {
                                            $query->where('transaction_details.variation_id', request('variation'))
                                                ->where('transaction_details.second_variation_id', request('second_variation'));
                                        })
                                      ->where('product_id', $products->id);


      $queries = [];
      $columns = [
          'item_code', 'dates', 'buyer', 'sort_month', 'status'
      ];

      foreach($columns as $column){
          if(request()->has($column) && !empty(request($column))){
              if($column == 'dates'){
                  $stockBalance = $stockBalance->whereBetween(DB::raw('DATE_FORMAT(stocks.created_at, "%Y-%m-%d")'), array($start, $end));
                  $transaction = $transaction->whereBetween(DB::raw('DATE_FORMAT(transaction_details.created_at, "%Y-%m-%d")'), array($start, $end));
                  $transactionDelivery = $transactionDelivery->whereBetween(DB::raw('DATE_FORMAT(transaction_details.created_at, "%Y-%m-%d")'), array($start, $end));

                  $new_dates = explode('-', request('dates'));
                  $startDate = $new_dates[0];
                  $start = date('Y-m-d', strtotime($new_dates[0]));

                  $transactionDelivery1 = $transactionDelivery1->where('transaction_details.created_at', '<=', date($start.' 00:00:00'));
                  $stockBalance1 = $stockBalance1->where('stocks.created_at', '<=', date($start.' 00:00:00'));
                  $transaction1 = $transaction1->where('transaction_details.created_at', '<=', date($start.' 00:00:00'));

              }elseif($column == 'sort_month'){
                  if(request($column) == '1'){
                    $transactionDelivery = $transactionDelivery->where(DB::raw('DATE_FORMAT(transaction_details.created_at, "%Y-%m")'), date('Y-01'));
                    $stockBalance = $stockBalance->where(DB::raw('DATE_FORMAT(stocks.created_at, "%Y-%m")'), date('Y-01'));
                    $transaction = $transaction->where(DB::raw('DATE_FORMAT(transaction_details.created_at, "%Y-%m")'), date('Y-01'));

                    $transactionDelivery1 = $transactionDelivery1->where('transaction_details.created_at', '<=', date('Y-01-01 00:00:00'));
                    $stockBalance1 = $stockBalance1->where('stocks.created_at', '<=', date('Y-01-01 00:00:00'));
                    $transaction1 = $transaction1->where('transaction_details.created_at', '<=', date('Y-01-01 00:00:00'));

                  }elseif(request($column) == '2'){
                    $transactionDelivery = $transactionDelivery->where(DB::raw('DATE_FORMAT(transaction_details.created_at, "%Y-%m")'), date('Y-02'));
                    $stockBalance = $stockBalance->where(DB::raw('DATE_FORMAT(stocks.created_at, "%Y-%m")'), date('Y-02'));
                    $transaction = $transaction->where(DB::raw('DATE_FORMAT(transaction_details.created_at, "%Y-%m")'), date('Y-02'));

                    $transactionDelivery1 = $transactionDelivery1->where('transaction_details.created_at', '<=', date('Y-02-01 00:00:00'));
                    $stockBalance1 = $stockBalance1->where('stocks.created_at', '<=', date('Y-02-01 00:00:00'));
                    $transaction1 = $transaction1->where('transaction_details.created_at', '<=', date('Y-02-01 00:00:00'));

                  }elseif(request($column) == '3'){
                    $transactionDelivery = $transactionDelivery->where(DB::raw('DATE_FORMAT(transaction_details.created_at, "%Y-%m")'), date('Y-03'));
                    $stockBalance = $stockBalance->where(DB::raw('DATE_FORMAT(stocks.created_at, "%Y-%m")'), date('Y-03'));
                    $transaction = $transaction->where(DB::raw('DATE_FORMAT(transaction_details.created_at, "%Y-%m")'), date('Y-03'));

                    $transactionDelivery1 = $transactionDelivery1->where('transaction_details.created_at', '<=', date('Y-03-01 00:00:00'));
                    $stockBalance1 = $stockBalance1->where('stocks.created_at', '<=', date('Y-03-01 00:00:00'));
                    $transaction1 = $transaction1->where('transaction_details.created_at', '<=', date('Y-03-01 00:00:00'));
                    

                    // $transactionDelivery1 = $transactionDelivery1->where(DB::raw('DATE_FORMAT(transaction_details.created_at, "%Y-%m")'), date('Y-03'));
                    // $stockBalance1 = $stockBalance1->where(DB::raw('DATE_FORMAT(stocks.created_at, "%Y-%m")'), date('Y-03'));
                    // $transaction1 = $transaction1->where(DB::raw('DATE_FORMAT(transaction_details.created_at, "%Y-%m")'), date('Y-03'));

                  }elseif(request($column) == '4'){
                    $transactionDelivery = $transactionDelivery->where(DB::raw('DATE_FORMAT(transaction_details.created_at, "%Y-%m")'), date('Y-04'));
                    $stockBalance = $stockBalance->where(DB::raw('DATE_FORMAT(stocks.created_at, "%Y-%m")'), date('Y-04'));
                    $transaction = $transaction->where(DB::raw('DATE_FORMAT(transaction_details.created_at, "%Y-%m")'), date('Y-04'));

                    $transactionDelivery1 = $transactionDelivery1->where('transaction_details.created_at', '<=', date('Y-04-01 00:00:00'));
                    $stockBalance1 = $stockBalance1->where('stocks.created_at', '<=', date('Y-04-01 00:00:00'));
                    $transaction1 = $transaction1->where('transaction_details.created_at', '<=', date('Y-04-01 00:00:00'));
                    
                    // $transactionDelivery1 = $transactionDelivery1->where(DB::raw('DATE_FORMAT(transaction_details.created_at, "%Y-%m")'), date('Y-03'));
                    // $stockBalance1 = $stockBalance1->where(DB::raw('DATE_FORMAT(stocks.created_at, "%Y-%m")'), date('Y-03'));
                    // $transaction1 = $transaction1->where(DB::raw('DATE_FORMAT(transaction_details.created_at, "%Y-%m")'), date('Y-03'));

                  }elseif(request($column) == '5'){
                    $transactionDelivery = $transactionDelivery->where(DB::raw('DATE_FORMAT(transaction_details.created_at, "%Y-%m")'), date('Y-05'));
                    $stockBalance = $stockBalance->where(DB::raw('DATE_FORMAT(stocks.created_at, "%Y-%m")'), date('Y-05'));
                    $transaction = $transaction->where(DB::raw('DATE_FORMAT(transaction_details.created_at, "%Y-%m")'), date('Y-05'));

                    $transactionDelivery1 = $transactionDelivery1->where('transaction_details.created_at', '<=', date('Y-05-01 00:00:00'));
                    $stockBalance1 = $stockBalance1->where('stocks.created_at', '<=', date('Y-05-01 00:00:00'));
                    $transaction1 = $transaction1->where('transaction_details.created_at', '<=', date('Y-05-01 00:00:00'));
                    

                  }elseif(request($column) == '6'){
                    $transactionDelivery = $transactionDelivery->where(DB::raw('DATE_FORMAT(transaction_details.created_at, "%Y-%m")'), date('Y-06'));
                    $stockBalance = $stockBalance->where(DB::raw('DATE_FORMAT(stocks.created_at, "%Y-%m")'), date('Y-06'));
                    $transaction = $transaction->where(DB::raw('DATE_FORMAT(transaction_details.created_at, "%Y-%m")'), date('Y-06'));

                    $transactionDelivery1 = $transactionDelivery1->where('transaction_details.created_at', '<=', date('Y-06-01 00:00:00'));
                    $stockBalance1 = $stockBalance1->where('stocks.created_at', '<=', date('Y-06-01 00:00:00'));
                    $transaction1 = $transaction1->where('transaction_details.created_at', '<=', date('Y-06-01 00:00:00'));
                    

                  }elseif(request($column) == '7'){
                    $transactionDelivery = $transactionDelivery->where(DB::raw('DATE_FORMAT(transaction_details.created_at, "%Y-%m")'), date('Y-07'));
                    $stockBalance = $stockBalance->where(DB::raw('DATE_FORMAT(stocks.created_at, "%Y-%m")'), date('Y-07'));
                    $transaction = $transaction->where(DB::raw('DATE_FORMAT(transaction_details.created_at, "%Y-%m")'), date('Y-07'));

                    $transactionDelivery1 = $transactionDelivery1->where('transaction_details.created_at', '<=', date('Y-07-01 00:00:00'));
                    $stockBalance1 = $stockBalance1->where('stocks.created_at', '<=', date('Y-07-01 00:00:00'));
                    $transaction1 = $transaction1->where('transaction_details.created_at', '<=', date('Y-07-01 00:00:00'));
                    

                  }elseif(request($column) == '8'){
                    $transactionDelivery = $transactionDelivery->where(DB::raw('DATE_FORMAT(transaction_details.created_at, "%Y-%m")'), date('Y-08'));
                    $stockBalance = $stockBalance->where(DB::raw('DATE_FORMAT(stocks.created_at, "%Y-%m")'), date('Y-08'));
                    $transaction = $transaction->where(DB::raw('DATE_FORMAT(transaction_details.created_at, "%Y-%m")'), date('Y-08'));

                    $transactionDelivery1 = $transactionDelivery1->where('transaction_details.created_at', '<=', date('Y-08-01 00:00:00'));
                    $stockBalance1 = $stockBalance1->where('stocks.created_at', '<=', date('Y-08-01 00:00:00'));
                    $transaction1 = $transaction1->where('transaction_details.created_at', '<=', date('Y-08-01 00:00:00'));
                    

                  }elseif(request($column) == '9'){
                    $transactionDelivery = $transactionDelivery->where(DB::raw('DATE_FORMAT(transaction_details.created_at, "%Y-%m")'), date('Y-09'));
                    $stockBalance = $stockBalance->where(DB::raw('DATE_FORMAT(stocks.created_at, "%Y-%m")'), date('Y-09'));
                    $transaction = $transaction->where(DB::raw('DATE_FORMAT(transaction_details.created_at, "%Y-%m")'), date('Y-09'));

                    $transactionDelivery1 = $transactionDelivery1->where('transaction_details.created_at', '<=', date('Y-09-01 00:00:00'));
                    $stockBalance1 = $stockBalance1->where('stocks.created_at', '<=', date('Y-09-01 00:00:00'));
                    $transaction1 = $transaction1->where('transaction_details.created_at', '<=', date('Y-09-01 00:00:00'));
                    

                  }elseif(request($column) == '10'){
                    $transactionDelivery = $transactionDelivery->where(DB::raw('DATE_FORMAT(transaction_details.created_at, "%Y-%m")'), date('Y-10'));
                    $stockBalance = $stockBalance->where(DB::raw('DATE_FORMAT(stocks.created_at, "%Y-%m")'), date('Y-10'));
                    $transaction = $transaction->where(DB::raw('DATE_FORMAT(transaction_details.created_at, "%Y-%m")'), date('Y-10'));

                    $transactionDelivery1 = $transactionDelivery1->where('transaction_details.created_at', '<=', date('Y-10-01 00:00:00'));
                    $stockBalance1 = $stockBalance1->where('stocks.created_at', '<=', date('Y-10-01 00:00:00'));
                    $transaction1 = $transaction1->where('transaction_details.created_at', '<=', date('Y-10-01 00:00:00'));


                  }elseif(request($column) == '11'){
                    $transactionDelivery = $transactionDelivery->where(DB::raw('DATE_FORMAT(transaction_details.created_at, "%Y-%m")'), date('Y-11'));
                    $stockBalance = $stockBalance->where(DB::raw('DATE_FORMAT(stocks.created_at, "%Y-%m")'), date('Y-11'));
                    $transaction = $transaction->where(DB::raw('DATE_FORMAT(transaction_details.created_at, "%Y-%m")'), date('Y-11'));

                    $transactionDelivery1 = $transactionDelivery1->where('transaction_details.created_at', '<=', date('Y-11-01 00:00:00'));
                    $stockBalance1 = $stockBalance1->where('stocks.created_at', '<=', date('Y-11-01 00:00:00'));
                    $transaction1 = $transaction1->where('transaction_details.created_at', '<=', date('Y-11-01 00:00:00'));
                    

                  }elseif(request($column) == '12'){
                    $transactionDelivery = $transactionDelivery->where(DB::raw('DATE_FORMAT(transaction_details.created_at, "%Y-%m")'), date('Y-12'));
                    $stockBalance = $stockBalance->where(DB::raw('DATE_FORMAT(stocks.created_at, "%Y-%m")'), date('Y-12'));
                    $transaction = $transaction->where(DB::raw('DATE_FORMAT(transaction_details.created_at, "%Y-%m")'), date('Y-12'));

                    $transactionDelivery1 = $transactionDelivery1->where('transaction_details.created_at', '<=', date('Y-12-01 00:00:00'));
                    $stockBalance1 = $stockBalance1->where('stocks.created_at', '<=', date('Y-12-01 00:00:00'));
                    $transaction1 = $transaction1->where('transaction_details.created_at', '<=', date('Y-12-01 00:00:00'));
                    

                  }
              }else{
                  $stockBalance = $stockBalance->where($column, 'like', "%".request($column)."%");
              }

              $queries[$column] = request($column);

          }
      }
      $stockBalance = $stockBalance->get();
      $transaction = $transaction->get();
      $transactionDelivery = $transactionDelivery->get();


      $stocks = $stockBalance->concat($transaction);
      $stocks = $stocks->concat($transactionDelivery);

      $stockBalance1 = $stockBalance1->get();
      $transaction1 = $transaction1->get();
      $transactionDelivery1 = $transactionDelivery1->get();

      $stocks1 = $stockBalance1->concat($transaction1);
      $stocks1 = $stocks1->concat($transactionDelivery1);

      $openStock = 0;
      $closedStock = 0;
      $overallstockAmount = 0;
      foreach ($stocks1 as $key => $stock) {
          if(!empty($stock->totalStockIn)){
              $openStock = $openStock + $stock->totalStockIn;
          }elseif(!empty($stock->totalStockOut)){
              $openStock = $openStock - $stock->totalStockOut;
          }elseif(!empty($stock->TransCart)){
              $openStock = $openStock - $stock->TransCart;
          }else{
              $openStock = $openStock;
          }
      }

      foreach ($stocks as $key => $stock) {
          if(!empty($stock->totalStockIn)){
              $closedStock = $closedStock + $stock->totalStockIn;
          }elseif(!empty($stock->totalStockOut)){
              $closedStock = $closedStock - $stock->totalStockOut;
          }elseif(!empty($stock->TransCart)){
              $closedStock = $closedStock - $stock->TransCart;
          }else{
              $closedStock = $closedStock;
          }
      }

      $closedStock = $openStock + $closedStock;

      return view('backend.reports.stock_report_details', ['stocks'=>$stocks, 'products'=>$products, 'startDate'=>$startDate, 'endDate'=>$endDate, 'openStock'=>$openStock, 'closedStock'=>$closedStock]);
    }

    public function exportStockDetailsReport()
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

        if(!empty(request('product_id'))){
            $product_id = request('product_id');
        }else{
            $product_id = "";
        }

        if(!empty(request('sort_month'))){
            $sort_month = request('sort_month');

            $dateObj   = DateTime::createFromFormat('!m', $sort_month);
            $monthName = $dateObj->format('F');

            $ds = new DateTime("first day of ". $monthName ." ". date('Y'));
            $de = new DateTime("last day of ". $monthName ." ". date('Y'));

            $start = $ds->format('Y-m-d');
            $end = $de->format('Y-m-d');

            $startDate = $ds->format('d/m/Y');
            $endDate = $de->format('d/m/Y');
        }else{
            $sort_month = "";
        }

        $variation = "";

        if(!empty(request('variation'))){
            $variation = request('variation');
        }

        $second_variation = "";

        if(!empty(request('second_variation'))){
            $second_variation = request('second_variation');
        }
 
        // return $sort_month;
        return Excel::download(new StockDetailExport($start, $end, $product_id, $sort_month, $variation, $second_variation), 'StockDetailReport'.$start.' - '.$end.'.xlsx');
    }

    public function exportStockReport()
    {

        if(!empty(request('product_name'))){
            $product_name = request('product_name');
        }else{
            $product_name = "";
        }

        if(!empty(request('item_code'))){
            $item_code = request('item_code');
        }else{
            $item_code = "";
        }

        // return $sort_month;
        return Excel::download(new StockExport($product_name, $item_code), 'StockReport.xlsx');
    }

    public function on_hold_report()
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

      $leftJoin = DB::raw("(SELECT * FROM merchants WHERE status = '1') AS i");
      $leftJoin2 = DB::raw("(SELECT * FROM admins) AS x");

      $pickups = PickupContact::select(DB::raw('IF(pickup_contacts.status = "1", td.quantity, NULL) AS stockOut'),
        DB::raw('IF(pickup_contacts.status = "99", td.quantity, NULL) AS stockIn'), 
        DB::raw('COALESCE(m.master_id, s.master_id) AS referrer_code'), 
        DB::raw('COALESCE(CONCAT(x.f_name, " ", x.l_name), CONCAT(i.f_name, " ", i.l_name)) AS referrer_name'),
        DB::raw('COALESCE(COALESCE(CONCAT(m.f_name, " ", m.l_name), CONCAT(s.f_name, " ", s.l_name), CONCAT(a.f_name, " ", a.l_name))) AS receiver_name'),
        DB::raw('COALESCE(COALESCE(m.code, s.code), a.code) AS receiver_code'),
        'pickup_contacts.*', 'td.product_name', 'td.quantity', 'td.item_code', 't.transaction_no')
                              ->leftjoin('transaction_details as td', 'td.transaction_id', 'pickup_contacts.transaction_id')
                              ->leftjoin('transactions as t', 't.id', 'pickup_contacts.transaction_id')
                              ->leftJoin('merchants AS m', 'm.code', 't.user_id')
                              ->leftJoin('users AS s', 's.code', 't.user_id')
                              ->leftJoin('admins AS a', 'a.code', 't.user_id')
                              ->leftJoin($leftJoin, function($join) {
                                    $join->on(DB::raw('COALESCE(m.master_id, s.master_id)'), '=', 'i.code');
                               })
                                ->leftJoin($leftJoin2, function($join) {
                                    $join->on(DB::raw('COALESCE(m.master_id, s.master_id)'), '=', 'x.code');
                               })
                              ->whereIn('pickup_contacts.status', ['1', '99'])
                              ->where('td.status', '1')
                              ->where('t.status', '1')
                              ->orderBy('pickup_contacts.created_at', 'desc');

      $dropdowns = PickupContact::select(DB::raw('IF(pickup_contacts.status = "1", td.quantity, NULL) AS stockOut'),
        DB::raw('IF(pickup_contacts.status = "99", td.quantity, NULL) AS stockIn'),
        'pickup_contacts.*', 'td.product_name', 'td.quantity', 'td.item_code')
                              ->leftjoin('transaction_details as td', 'td.transaction_id', 'pickup_contacts.transaction_id')
                              ->leftjoin('transactions as t', 't.id', 'pickup_contacts.transaction_id')
                              ->whereIn('pickup_contacts.status', ['1', '99'])
                              ->where('td.status', '1')
                              ->where('t.status', '1')
                              ->groupBy('td.item_code')
                              ->get();

      $queries = [];
      $columns = [
          'item_code', 'product_name', 'item_code_dropdown', 'dates', 'buyer', 'transaction_no', 'referrer_name', 'referrer_code', 'receiver_name', 'receiver_code', 'agent_name', 'status'
      ];

      foreach($columns as $column){
          if(request()->has($column) && !empty(request($column))){
              if($column == 'status'){
                  $pickups = $pickups->where('pickup_contacts.status', 'like', "%".request($column)."%");
              }elseif($column == 'dates'){
                  $pickups = $pickups->whereBetween(DB::raw('DATE_FORMAT(pickup_contacts.created_at, "%Y-%m-%d")'), array($start, $end));
              }elseif($column == 'product_name'){
                  $pickups = $pickups->where('td.product_name', 'like', "%".request($column)."%");
              }elseif($column == 'item_code'){
                  $pickups = $pickups->where('item_code', 'like', "%".request($column)."%");
              }elseif($column == 'item_code_dropdown'){
                  $pickups = $pickups->where('item_code', 'like', "%".request($column)."%");
              }elseif($column == 'agent_name'){
                  $pickups = $pickups->where(DB::raw('COALESCE(COALESCE(CONCAT(m.f_name, " ", m.l_name), CONCAT(s.f_name, " ", s.l_name), CONCAT(a.f_name, " ", a.l_name)))'), 'like', '%'.request($column).'%');
              }elseif($column == 'transaction_no'){
                  $pickups = $pickups->where('transaction_no', 'like', "%".request($column)."%");
              }elseif($column == 'referrer_name'){
                  $pickups = $pickups->where(DB::raw('COALESCE(CONCAT(x.f_name, " ", x.l_name), CONCAT(i.f_name, " ", i.l_name))'), 'like', '%'.request($column).'%');
              }elseif($column == 'referrer_code'){
                $pickups = $pickups->where(DB::raw('COALESCE(m.master_id, s.master_id)'), 'like', '%'.request($column).'%');
              }elseif($column == 'receiver_name'){
                  $pickups = $pickups->where('pickup_contacts.f_name', 'like', '%'.request($column).'%');
              }elseif($column == 'receiver_code'){
                $pickups = $pickups->where(DB::raw('COALESCE(COALESCE(m.code, s.code), a.code)'), 'like', '%'.request($column).'%');
              }else{
                  $pickups = $pickups->where($column, 'like', "%".request($column)."%");
              }

              $queries[$column] = request($column);

          }
      }

      $per_page = 10;
      if(!empty(request('per_page'))){
          $per_page = request('per_page');
      }
      $pickups = $pickups->paginate($per_page)->appends($queries);


      return view('backend.reports.on_hold_report', ['pickups'=>$pickups, 'startDate'=>$startDate, 'endDate'=>$endDate, 'dropdowns'=>$dropdowns]);
    }

    public function payment_method_report()
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

      $transactions = Transaction::select(DB::raw('COALESCE(COALESCE(CONCAT(m.f_name, " ", m.l_name), CONCAT(u.f_name, " ", u.l_name), CONCAT(a.f_name, " ", a.l_name))) AS buyer_name'),
                                  'transactions.*')
                                 ->leftjoin('users as u', 'transactions.user_id', 'u.code')
                                 ->leftjoin('merchants as m', 'transactions.user_id', 'm.code')
                                 ->leftjoin('admins as a', 'transactions.user_id', 'a.code')
                                 ->where('transactions.status', '1')
                                 ->orderBy('transactions.created_at', 'desc');

      $queries = [];
      $columns = [
        'transaction_no', 'buyer'
      ];

      foreach($columns as $column){
        if(request()->has($column) && !empty(request($column))){
          if($column == 'transaction_no'){
            $transactions = $transactions->where('transaction_no', 'like', '%'.request($column).'%');
          }elseif($column == 'buyer'){
            $transactions = $transactions->where(DB::raw('COALESCE(COALESCE(CONCAT(m.f_name, " ", m.l_name), CONCAT(u.f_name, " ", u.l_name), CONCAT(a.f_name, " ", a.l_name)))'), 'like', '%'.request($column).'%');
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
      $transactions = $transactions->paginate($per_page)->appends($queries);

      return view('backend.reports.payment_method_report', ['transactions'=>$transactions, 'startDate'=>$startDate, 'endDate'=>$endDate]);
    }

    public function point_report()
    {
        $users = User::orderBy('created_at', 'DESC');

        $queries = [];
        $columns = [
          'member_code', 'member_name', 'per_page'
        ];

        $per_page = 10;
        if(!empty(request('per_page'))){
            $per_page = request('per_page');
        }
        foreach($columns as $column){
          if(request()->has($column) && !empty(request($column))){
            if($column == 'member_name'){
              $users = $users->where('users.f_name', 'like', "%".request($column)."%");
            }elseif($column == 'member_code'){
              $users = $users->where('users.code', 'like', "%".request($column)."%");
            }elseif($column == 'per_page'){
              $users = $users->paginate($per_page);
            }else{
              $users = $users->where($column, 'like', "%".request($column)."%");
            }

            $queries[$column] = request($column);
            }
        }

        if(!empty(request('per_page'))){
            $users = $users->appends($queries);        
        }else{
            $users = $users->paginate($per_page)->appends($queries);        
        }

        $PV_Balance = [];
        $PV_History = [];
        foreach ($users as $key => $user) {
            $PV_Balance[$user->code] = $this->GetPVWallet($user->code);

            $transaction = MemberPv::where('member_pvs.user_id', $user->code)
                                   ->where('member_pvs.status', '1')
                                   ->get();

            $transaction_purchase = Transaction::where('transactions.user_id', $user->code)
                                                ->where('transactions.status', '1')
                                                ->where('transactions.pv_purchase', '1')
                                                ->get();

            $PV_History[$user->code] = $transaction->concat($transaction_purchase);
        }

        return view('backend.reports.point_report', ['users'=>$users], compact('PV_Balance', 'PV_History'));
    }

    public function point_report_details($id)
    {
        $user = User::find($id);


        $PV_History = [];
        $PV_Balance = 0;

        $transaction = MemberPv::select('member_pvs.*', 'member_pvs.transaction_no as pv_transaction_no', 't.id as Tid')
                               ->leftJoin('transactions as t', 't.transaction_no', 'member_pvs.transaction_no')
                               ->where('member_pvs.user_id', $user->code)
                               ->where('member_pvs.status', '1')
                               ->get();

        $transaction_purchase = Transaction::select('transactions.*', 'transaction_no as t_transaction_no', 'transactions.id as transaction_id')
                                            ->where('transactions.user_id', $user->code)
                                            ->where('transactions.status', '1')
                                            ->where('transactions.pv_purchase', '1')
                                            ->get();

        $PV_History[$user->code] = $transaction->concat($transaction_purchase);

        $PV_Balance = $this->GetPVWallet($user->code);

        return view('backend.reports.point_report_details', ['user'=>$user, 'PV_Balance'=>$PV_Balance], compact('PV_History'));
    }

    public function GetPVWallet($buyerCode)
    {
        $transaction = MemberPv::select(DB::raw('SUM(pv_amount) as totalPoint'))
                               ->where('member_pvs.user_id', $buyerCode)
                               ->where('member_pvs.status', '1')
                               ->first();

        $transaction_purchase = Transaction::select(DB::raw('SUM(grand_total) as totalPoint'))
                                  ->where('transactions.user_id', $buyerCode)
                                  ->where('transactions.status', '1')
                                  ->where('transactions.pv_purchase', '1')
                                  ->first();

        $total = 0;

        $total = $transaction->totalPoint - $transaction_purchase->totalPoint;

        return $total;
    }

    public function agent_sales_report()
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


        // if (!empty(request('month'))) {
            
        // }

        // $year = request('year');
        // $month = request('month');
        
        $merchants = Agent::select('l.agent_lvl AS l_agent_lvl', 'l.agent_lvl_cn AS l_agent_lvl_cn', 'agents.*',
                                      DB::raw('COALESCE(COALESCE(CONCAT(upm.display_code, upm.display_running_no), upa.code), upu.code) AS upline_code'),
                                      DB::raw('COALESCE(COALESCE(CONCAT(upm.f_name, " ", upm.l_name), CONCAT(upa.f_name, " ", upa.l_name)), upu.f_name) AS upline_name'))
                             ->leftJoin('agent_levels AS l', 'l.id', 'agents.lvl')
                             ->leftJoin('agents as upm', 'upm.code', 'agents.master_id')
                             ->leftJoin('admins as upa', 'upa.code', 'agents.master_id')
                             ->leftJoin('users as upu', 'upu.code', 'agents.master_id')
                             ->where('agents.status','1')
                             ->whereNotNull('agents.verify_status')
                            //  ->whereBetween(DB::raw('DATE_FORMAT(agents.created_at, "%Y-%m-%d")'), array($start, $end))
                             ->orderBy('agents.code', 'asc');
                             

        if(Auth::guard('merchant')->check()){
            $merchants = $merchants->where('agents.dual_master_id', Auth::user()->code);
        }

        $queries = [];
        $columns = [
            'transaction_no', 'agent', 'code', 'referrer_code', 'referrer_name', 'status', 'per_page'
        ];

        foreach($columns as $column){
            if(request()->has($column) && !empty(request($column))){
                // if($column == 'dates'){
                //     $merchants = $merchants->whereBetween(DB::raw('DATE_FORMAT(agents.created_at, "%Y-%m-%d")'), array($start, $end));
                // }else
                if($column == 'agent'){
                    $merchants = $merchants->where(DB::raw('CONCAT(agents.f_name, " ", agents.l_name)'), 'like', "%".request($column)."%");
                }elseif($column == 'code'){
                    $merchants = $merchants->where('agents.code','like',"%".request($column)."%");
                }elseif($column == 'referrer_name'){
                $merchants = $merchants->whereRaw("
                    CAST(
                        COALESCE(
                            COALESCE(CONCAT(upm.f_name, ' ', upm.l_name), CONCAT(upa.f_name, ' ', upa.l_name)),
                            upu.f_name
                        ) AS CHAR CHARACTER SET utf8mb4
                    ) COLLATE utf8mb4_unicode_ci LIKE ?
                ", ['%' . request($column) . '%']);

                }elseif($column == 'referrer_code'){
                    $merchants = $merchants->whereRaw("
                        COALESCE(COALESCE(CONCAT(upm.display_code, upm.display_running_no), upa.code), upu.code) LIKE ?
                    ", ['%' . request($column) . '%']);
                }

                $queries[$column] = request($column);

            }
        }



        $per_page = 10;
        if(!empty(request('per_page'))){
            $per_page = request('per_page');
        }

        if (!empty(request('dates'))) {
            $queries['dates'] = request('dates');
        }

        $merchants = $merchants->paginate($per_page)->appends($queries);

        $total_sales = [];
        $personal_comm = [];
        $total_comm = [];
        $personal_pv = [];
        $total_pv = [];

        foreach($merchants as $merchant){
         
                $total_sales[$merchant->code] = Transaction::select(DB::raw('SUM(grand_total - COALESCE(shipping_fee, 0)) AS totalSales'))
                                                    ->where('status', '1')
                                                    ->where('user_id',$merchant->code)
                                                    ->whereBetween(DB::raw('DATE_FORMAT(created_at,"%Y-%m-%d")'),array($start,$end))
                                                    ->whereNull('pv_purchase')
                                                    ->first();

                $personal_comm[$merchant->code] =  AffiliateCommission::select(DB::raw('SUM(comm_amount) AS totalCommission'))
                                                    ->where('status', '1')
                                                    ->where('user_id',$merchant->code)
                                                    ->whereBetween(DB::raw('DATE_FORMAT(created_at,"%Y-%m-%d")'),array($start,$end))
                                                    ->where('comm_desc', '=', 'Order Rebate Commission')
                                                    ->first();

                $total_comm[$merchant->code] =  AffiliateCommission::select(DB::raw('SUM(comm_amount) AS totalCommission'))
                                                    ->where('status', '1')
                                                    ->where('user_id',$merchant->code)
                                                    ->whereBetween(DB::raw('DATE_FORMAT(created_at,"%Y-%m-%d")'),array($start,$end))
                                                    ->where('comm_desc', '!=', 'Order Rebate Commission')
                                                    ->first();
        }

        $total_sales1 = 0;         
              $total_sales1 = Transaction::select(DB::raw('SUM(grand_total) AS totalSales'))
                        ->where('status', '1')
                        ->whereIn('user_id', function($query) {
                            $query->select('code')
                                  ->from('agents');
                        })
                        ->whereBetween(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), [$start, $end])
                        ->first();

                        $sumTotalSales = 0;
                        foreach ($total_sales as $sales) {
                            if ($sales && isset($sales->totalSales)) {
                                $sumTotalSales += $sales->totalSales;
                            }
                        }

                        $sumTotalCommissions = 0;
                        foreach ($personal_comm as $commission) {
                            if ($commission && isset($commission->totalCommission)) {
                                $sumTotalCommissions += $commission->totalCommission;
                            }
                        }

                        $sumTotalCommissions1 = 0;
                        foreach ($total_comm as $commission1) {
                          if ($commission1 && isset($commission1->totalCommission)) {
                              $sumTotalCommissions1 += $commission1->totalCommission;
                          }
                      }
                      $totalPv = 0;
                      foreach ($personal_pv as $pv) {
                        $totalPv += floatval($pv);
                    }

                    $totalPv1 = 0;
                      foreach ($total_pv as $pvs) {
                        $totalPv1 += floatval($pvs);
                    }

                    $merchantCodes = DB::table('merchants')
                        ->select('code')
                        ->get()
                        ->pluck('code')
                        ->toArray();

                    $userCodes = DB::table('users')
                        ->select('code')
                        ->get()
                        ->pluck('code')
                        ->toArray();

                    $codes = array_merge($merchantCodes, $userCodes);

                    $transactionsPv = Transaction::select(DB::raw('SUM(d.get_pv * d.quantity) as totalGetPV'))
                        ->join('transaction_details as d', 'd.transaction_id', 'transactions.id')
                        ->where('transactions.status', '1')
                        ->whereIn('user_id', $codes)
                        ->whereBetween(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), [$start, $end])
                        ->first();



                    return view('backend.reports.agent_sales_report', ['merchants'=>$merchants, 'startDate'=>$startDate, 'endDate'=>$endDate, 'totalPv'=>$totalPv],compact('total_sales','total_comm','personal_pv', 'total_pv', 'personal_comm', 'total_sales1', 'sumTotalSales', 'sumTotalCommissions', 'sumTotalCommissions1', 'totalPv1', 'transactionsPv'));
    }

    public function exportAgentReport(){
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

        $agent = "";
        if(!empty(request('agent'))){
          $agent = request('agent');
        }

        $code = "";
        if(!empty(request('code'))){
          $code = request('code');
        }

        $referrer_code = "";
        if(!empty(request('referrer_code'))){
          $referrer_code = request('referrer_code');
        }

        $referrer_name = "";
        if(!empty(request('referrer_name'))){
          $referrer_name = request('referrer_name');
        }

        return Excel::download(new AgentSalesExport($start, $end, $agent, $code, $referrer_code, $referrer_name), 'AgentSalesReport'.$start.' - '.$end.'.xlsx');
    }
    public function print_agent_sales_report()
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



        // if (!empty(request('month'))) {
            
        // }

        // $year = request('year');
        // $month = request('month');
        
        $merchants = Agent::select('l.agent_lvl AS l_agent_lvl', 'l.agent_lvl_cn AS l_agent_lvl_cn', 'agents.*',
                                      DB::raw('COALESCE(COALESCE(CONCAT(upm.display_code, upm.display_running_no), upa.code), upu.code) AS upline_code'),
                                      DB::raw('COALESCE(COALESCE(CONCAT(upm.f_name, " ", upm.l_name), CONCAT(upa.f_name, " ", upa.l_name)), upu.f_name) AS upline_name'))
                             ->leftJoin('agent_levels AS l', 'l.id', 'agents.lvl')
                             ->leftJoin('agents as upm', 'upm.code', 'agents.master_id')
                             ->leftJoin('admins as upa', 'upa.code', 'agents.master_id')
                             ->leftJoin('users as upu', 'upu.code', 'agents.master_id')
                             ->where('agents.status','1')
                             ->whereNotNull('agents.verify_status')
                             ->orderBy('agents.code', 'asc');


        $queries = [];
        $columns = [
            'transaction_no', 'agent', 'status'
        ];

        foreach($columns as $column){
            if(request()->has($column) && !empty(request($column))){
                if($column == 'agent'){
                    $merchants = $merchants->where(DB::raw('CONCAT(agents.f_name, " ", agents.l_name)'), 'like', "%".request($column)."%");
                }else{
                    $merchants = $merchants->where($column, 'like', "%".request($column)."%");
                }

                $queries[$column] = request($column);

            }
        }



        $per_page = 10;
        if(!empty(request('per_page'))){
            $per_page = request('per_page');
        }

        if (!empty(request('dates'))) {
            $queries['dates'] = request('dates');
        }

        $merchants = $merchants->get();

        $total_sales = [];
        $personal_comm = [];
        $total_comm = [];
        $personal_pv = [];
        $total_pv = [];

        foreach($merchants as $merchant){
         
                $total_sales[$merchant->code] = Transaction::select(DB::raw('SUM(grand_total) AS totalSales'))
                                                    ->where('status', '1')
                                                    ->where('user_id',$merchant->code)
                                                    ->whereBetween(DB::raw('DATE_FORMAT(created_at,"%Y-%m-%d")'),array($start,$end))
                                                    ->first();

                $personal_comm[$merchant->code] =  AffiliateCommission::select(DB::raw('SUM(comm_amount) AS totalCommission'))
                                                    ->where('status', '1')
                                                    ->where('user_id',$merchant->code)
                                                    ->whereBetween(DB::raw('DATE_FORMAT(created_at,"%Y-%m-%d")'),array($start,$end))
                                                    ->where('comm_desc', '=', 'Order Rebate Commission')
                                                    ->first();

                $total_comm[$merchant->code] =  AffiliateCommission::select(DB::raw('SUM(comm_amount) AS totalCommission'))
                                                    ->where('status', '1')
                                                    ->where('user_id',$merchant->code)
                                                    ->whereBetween(DB::raw('DATE_FORMAT(created_at,"%Y-%m-%d")'),array($start,$end))
                                                    ->first();
        }

        $total_sales1 = 0;         
              $total_sales1 = Transaction::select(DB::raw('SUM(grand_total) AS totalSales'))
                        ->where('status', '1')
                        ->whereIn('user_id', function($query) {
                            $query->select('code')
                                  ->from('agents');
                        })
                        ->whereBetween(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), [$start, $end])
                        ->first();

                        $sumTotalSales = 0;
                        foreach ($total_sales as $sales) {
                            if ($sales && isset($sales->totalSales)) {
                                $sumTotalSales += $sales->totalSales;
                            }
                        }

                        $sumTotalCommissions = 0;
                        foreach ($personal_comm as $commission) {
                            if ($commission && isset($commission->totalCommission)) {
                                $sumTotalCommissions += $commission->totalCommission;
                            }
                        }

                        $sumTotalCommissions1 = 0;
                        foreach ($total_comm as $commission1) {
                          if ($commission1 && isset($commission1->totalCommission)) {
                              $sumTotalCommissions1 += $commission1->totalCommission;
                          }
                      }
                      $totalPv = 0;
                      foreach ($personal_pv as $pv) {
                        $totalPv += floatval($pv);
                    }

                    $totalPv1 = 0;
                      foreach ($total_pv as $pvs) {
                        $totalPv1 += floatval($pvs);
                    }

                    $merchantCodes = DB::table('agents')
                        ->select('code')
                        ->get()
                        ->pluck('code')
                        ->toArray();

                    $userCodes = DB::table('users')
                        ->select('code')
                        ->get()
                        ->pluck('code')
                        ->toArray();

                    $codes = array_merge($merchantCodes, $userCodes);

                    $transactionsPv = Transaction::select(DB::raw('SUM(d.get_pv * d.quantity) as totalGetPV'))
                        ->join('transaction_details as d', 'd.transaction_id', 'transactions.id')
                        ->where('transactions.status', '1')
                        ->whereIn('user_id', $codes)
                        ->whereBetween(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), [$start, $end])
                        ->first();



                    return view('backend.reports.print_agent_sales_report', ['merchants'=>$merchants, 'startDate'=>$startDate, 'endDate'=>$endDate, 'totalPv'=>$totalPv],compact('total_sales','total_comm','personal_pv', 'total_pv', 'personal_comm', 'total_sales1', 'sumTotalSales', 'sumTotalCommissions', 'sumTotalCommissions1', 'totalPv1', 'transactionsPv'));
    }

    public function agent_sales_report_detail($code)
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

        $transactions = Transaction::select('transactions.*')
                                   ->where('user_id', $code)
                                   ->where('transactions.status', '1')
                                   ->whereBetween(DB::raw('DATE_FORMAT(transactions.created_at,"%Y-%m-%d")'), array($start,$end))
                                   ->whereNull('pv_purchase')
                                   ->get();

        $affs = Affiliate::select('t.*')
                         ->join('agents as m', 'm.code', 'affiliates.affiliate_id')
                         ->join('transactions as t', 't.user_id', 'affiliates.affiliate_id')
                         ->where('t.status', '1')
                         ->where('affiliates.user_id', $code)
                         ->whereBetween(DB::raw('DATE_FORMAT(t.created_at,"%Y-%m-%d")'), array($start,$end))
                         ->get();

        $affs_customers = Affiliate::select('t.*')
                                   ->join('agents as m', 'm.code', 'affiliates.affiliate_id')
                                   ->join('users as u', 'u.master_id', 'm.code')
                                   ->join('transactions as t', 't.user_id', 'u.code')
                                   ->where('t.status', '1')
                                   ->where('affiliates.user_id', $code)
                                   ->whereBetween(DB::raw('DATE_FORMAT(t.created_at,"%Y-%m-%d")'), array($start,$end))
                                   ->get();

        $mb_transactions = Transaction::select('transactions.*')
                                      ->join('users as u', 'transactions.user_id', 'u.code')
                                      ->where('u.master_id', $code)
                                      ->where('transactions.status', '1')
                                      ->whereBetween(DB::raw('DATE_FORMAT(transactions.created_at,"%Y-%m-%d")'), array($start,$end))
                                      ->get();

        // $all = $transactions->concat($affs);
        // $all = $all->concat($affs_customers);
        // $all = $all->concat($mb_transactions);

        $all = $transactions;
        
        $details = [];
        foreach($all as $transaction){
            $details[$transaction->transaction_no] = TransactionDetail::where('transaction_id', $transaction->id)->get();
        }

        $agent = Agent::where('code', $code)->first();
        
        return view('backend.reports.agent_sales_report_detail', ['all'=>$all, 
                                                                  'startDate'=>$startDate, 
                                                                  'endDate'=>$endDate,
                                                                  'agent'=>$agent],
                                                                  compact('details'));
    }

    public function print_agent_sales_report_detail($code)
    {
        if(!empty(request('dates'))){

            $new_dates = explode('-', request('dates'));
        //   $start = date('Y-m-d', strtotime($new_dates[0]));
        //   $end = date('Y-m-d', strtotime($new_dates[1]));
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

        $transactions = Transaction::select('transactions.*')
                                   ->where('user_id', $code)
                                   ->where('transactions.status', '1')
                                   ->whereBetween(DB::raw('DATE_FORMAT(transactions.created_at,"%Y-%m-%d")'), array($start,$end))
                                   ->get();

        $affs = Affiliate::select('t.*')
                         ->join('agents as m', 'm.code', 'affiliates.affiliate_id')
                         ->join('transactions as t', 't.user_id', 'affiliates.affiliate_id')
                         ->where('t.status', '1')
                         ->where('affiliates.user_id', $code)
                         ->whereBetween(DB::raw('DATE_FORMAT(t.created_at,"%Y-%m-%d")'), array($start,$end))
                         ->get();

        $affs_customers = Affiliate::select('t.*')
                                   ->join('agents as m', 'm.code', 'affiliates.affiliate_id')
                                   ->join('users as u', 'u.master_id', 'm.code')
                                   ->join('transactions as t', 't.user_id', 'u.code')
                                   ->where('t.status', '1')
                                   ->where('affiliates.user_id', $code)
                                   ->whereBetween(DB::raw('DATE_FORMAT(t.created_at,"%Y-%m-%d")'), array($start,$end))
                                   ->get();

        $mb_transactions = Transaction::select('transactions.*')
                                      ->join('users as u', 'transactions.user_id', 'u.code')
                                      ->where('u.master_id', $code)
                                      ->where('transactions.status', '1')
                                      ->whereBetween(DB::raw('DATE_FORMAT(transactions.created_at,"%Y-%m-%d")'), array($start,$end))
                                      ->get();

        // $all = $transactions->concat($affs);
        // $all = $all->concat($affs_customers);
        // $all = $all->concat($mb_transactions);

        $all = $transactions;
        
        $details = [];
        foreach($all as $transaction){
            $details[$transaction->transaction_no] = TransactionDetail::where('transaction_id', $transaction->id)->get();
        }

        $agent = Agent::where('code', $code)->first();
        
        return view('backend.reports.print_agent_sales_report_detail', ['all'=>$all, 
                                                                  'startDate'=>$startDate, 
                                                                  'endDate'=>$endDate,
                                                                  'agent'=>$agent],
                                                                  compact('details'));
    }

    public function get_previous_topup_balance($userCode, $startDate)
    {
        $topupTransactions = TopupTransaction::where('user_id', $userCode)
                                             ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), '<', $startDate)
                                             ->where('status', '1')
                                             ->sum('amount');

        $transactions = Transaction::where('user_id', $userCode)
                                   ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), '<', $startDate)
                                   ->where('status', '1')
                                   ->where('mall', '2')
                                   ->sum('grand_total');

        $adjustTopupWallets = AdjustTopupWallet::where('user_id', $userCode)
                                               ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), '<', $startDate)
                                               ->selectRaw('SUM(CASE WHEN type = 1 THEN amount ELSE 0 END) as wallet_in')
                                               ->selectRaw('SUM(CASE WHEN type = 2 THEN amount ELSE 0 END) as wallet_out')
                                               ->first();

        $transfer_cash_to_topup = AdjustCashToTopup::where('user_id', $userCode)
                                                    ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), '<', $startDate)
                                                    ->sum('amount');

        $previousBalance = ($topupTransactions + $adjustTopupWallets->wallet_in - $transactions - $adjustTopupWallets->wallet_out + $transfer_cash_to_topup);

        return $previousBalance;
    }

    public function get_current_topup_balance($userCode, $endDate)
    {
        $topupTransactions = TopupTransaction::where('user_id', $userCode)
                                             ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), '<=', $endDate)
                                             ->where('status', '1')
                                             ->sum('amount');
    
        $transactions = Transaction::where('user_id', $userCode)
                                   ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), '<=', $endDate)
                                   ->where('status', '1')
                                   ->where('mall', '2')
                                   ->sum('grand_total');
    
        $adjustTopupWallets = AdjustTopupWallet::where('user_id', $userCode)
                                               ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), '<=', $endDate)
                                               ->where('status', '1')
                                               ->selectRaw('SUM(CASE WHEN type = 1 THEN amount ELSE 0 END) as wallet_in')
                                               ->selectRaw('SUM(CASE WHEN type = 2 THEN amount ELSE 0 END) as wallet_out')
                                               ->first();

        $transfer_cash_to_topup = AdjustCashToTopup::where('user_id', $userCode)
                                                    ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), '<=', $endDate)
                                                    ->sum('amount');
    
        $currentBalance = ($topupTransactions + $adjustTopupWallets->wallet_in - $transactions - $adjustTopupWallets->wallet_out + $transfer_cash_to_topup);
    
        return $currentBalance;
    }

    public function topup_wallet_report()
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

        $translation_data = GlobalController::get_translations();

        $agent = Agent::select(
                    DB::raw('CONVERT(CONCAT(agents.f_name, " ", agents.l_name) USING utf8mb4) COLLATE utf8mb4_unicode_ci AS userName'),
                    DB::raw('CONVERT(agents.code USING utf8mb4) COLLATE utf8mb4_unicode_ci AS userCode'),
                    DB::raw('"'.(isset($translation_data["backendlang"]["backendlang"]["Agent"]) ? $translation_data["backendlang"]["backendlang"]["Agent"] : 'Agent').'" AS user_type'),
                    DB::raw('agents.status AS status'))
               ->whereNotIn('agents.status', ['99', '3']);

        $member = User::select(
                    DB::raw('CONVERT(CONCAT(users.f_name, " ", users.l_name) USING utf8mb4) COLLATE utf8mb4_unicode_ci AS userName'),
                    DB::raw('CONVERT(users.code USING utf8mb4) COLLATE utf8mb4_unicode_ci AS userCode'),
                    DB::raw('"'.(isset($translation_data["backendlang"]["backendlang"]["Member"]) ? $translation_data["backendlang"]["backendlang"]["Member"] : 'Member').'" AS user_type'),
                    DB::raw('users.status AS status')
                )
                ->whereNotIn('users.status', ['99', '3']);

    
        $topupWallets = DB::query()
        ->fromSub($agent->unionAll($member), 'combined_users')
        ->select('userName', 'userCode', 'status', 'user_type');

        if (!empty(request('user_type'))) {
            if (request('user_type') == '1') { 
                $topupWallets = $topupWallets->where(DB::raw('LEFT(userCode, 1)'), 'A');
            } elseif (request('user_type') == '2') { 
                $topupWallets = $topupWallets->where(DB::raw('LEFT(userCode, 2)'), 'Mb');
            }
        }

        $queries = [];
        $columns = ['dates', 'user_name', 'user_code', 'user_code_desc', 'user_code_asc', 'per_page'];

        $per_page = 10;
        if(!empty(request('per_page'))){
            $per_page = request('per_page');
        }

        foreach($columns as $column){
            if(request()->has($column) && !empty(request($column))){
                if($column == 'user_name'){ 
                    $topupWallets = $topupWallets->where('userName', 'like', '%'.request($column).'%');
                }elseif($column == 'user_code'){
                    $topupWallets = $topupWallets->where('userCode', 'like', '%'.request($column).'%');
                }elseif($column == 'user_code_desc'){
                    $topupWallets = $topupWallets->orderBy('userCode', 'desc');
                }elseif($column == 'user_code_asc'){
                    $topupWallets = $topupWallets->orderBy('userCode', 'asc');
                }elseif($column == 'per_page'){
                    $topupWallets = $topupWallets->paginate($per_page);
                }
                $queries[$column] = request($column);
            }
        }

        if(!empty(request('per_page'))){
            $topupWallets = $topupWallets->appends($queries);
        }else{
            $topupWallets = $topupWallets->paginate($per_page)->appends($queries);
        }

        $total_wallet_in = [];
        $total_wallet_out = [];
        $previous_balance = [];
        $current_balance = [];

        foreach($topupWallets AS $topupWallet){
            $total_wallet_in[$topupWallet->userCode] = TopupTransaction::where('user_id', $topupWallet->userCode)
                                                                        ->whereBetween(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), [$start, $end])
                                                                        ->where('status', '1')
                                                                        ->sum('amount') 
                                                        + AdjustTopupWallet::where('user_id', $topupWallet->userCode)
                                                                        ->whereBetween(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), [$start, $end])
                                                                        ->where('type', '1')
                                                                        ->where('status', '1')
                                                                        ->sum('amount');
                                                        + AdjustCashToTopup::where('user_id', $topupWallet->userCode)
                                                                           ->whereBetween(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), [$start, $end])
                                                                           ->sum('amount');

            $total_wallet_out[$topupWallet->userCode] = Transaction::where('user_id', $topupWallet->userCode)
                                                                   ->whereBetween(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), [$start, $end])
                                                                   ->where('status', '1')
                                                                   ->where('mall', '2')
                                                                   ->sum('grand_total') 
                                                        + AdjustTopupWallet::where('user_id', $topupWallet->userCode)
                                                                           ->whereBetween(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), [$start, $end])
                                                                           ->where('type', '2')
                                                                           ->where('status', '1')
                                                                           ->sum('amount');
                                                                    
            $previous_balance[$topupWallet->userCode] = $this->get_previous_topup_balance($topupWallet->userCode, $start);
            $current_balance[$topupWallet->userCode] = $this->get_current_topup_balance($topupWallet->userCode, $end);
        }

        return view('backend.reports.topup_wallet_report', ['startDate'=>$startDate,
                                                            'endDate'=>$endDate,
                                                            'topupWallets'=>$topupWallets,
                                                            'total_wallet_in' => $total_wallet_in,
                                                            'total_wallet_out' => $total_wallet_out,
                                                            'previous_balance' => $previous_balance,
                                                            'current_balance' => $current_balance
                                                        ]);
    }

    public function exportTopupWalletReport()
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

        $user_name = "";
        if(!empty(request('user_name'))){
            $user_name = request('user_name');
        }

        $user_code = "";
        if(!empty(request('user_code'))){
            $user_code = request('user_code');
        }

        $user_type = "";
        if(!empty(request('user_type'))){
            $user_type = request('user_type');
        }

        return Excel::download(new TopupWalletExport($start, $end, $user_name, $user_code,$user_type),  'TopupWalletReport'.$start.' - '.$end.'.xlsx');
    }

    public function topup_wallet_report_detail($code)
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

        if ($code) {
                $topupTransaction = TopupTransaction::select(DB::raw('topup_transactions.created_at AS created_date'), DB::raw('COALESCE(a.status, u.status) AS status'),
                                            'topup_transactions.amount AS walletIn', DB::raw('0 AS walletOut'), DB::raw('0 AS adjustAmount'),
                                            'topup_transactions.status AS tt_status', DB::raw('NULL AS t_status'), DB::raw('NULL AS t_mall'),
                                            DB::raw('NULL AS atw_type'), DB::raw('NULL AS transNo'), 'topup_transactions.topup_no AS topupNo')
                                    ->leftJoin('agents AS a', 'a.code', 'topup_transactions.user_id')
                                    ->leftJoin('admins AS m', 'm.code', 'topup_transactions.user_id')
                                    ->leftJoin('users AS u', 'u.code', 'topup_transactions.user_id')
                                    ->where(DB::raw('COALESCE(a.code, m.code, u.code)'), $code)
                                    ->where('topup_transactions.status', 1);

                $transaction = Transaction::select(DB::raw('transactions.created_at AS created_date'), DB::raw('COALESCE(a.status, u.status) AS status'),
                                                DB::raw('0 AS walletIn'), 'transactions.grand_total AS walletOut', DB::raw('0 AS adjustAmount'),
                                                DB::raw('NULL AS tt_status'), 'transactions.status AS t_status', 'transactions.mall AS t_mall',
                                                DB::raw('NULL AS atw_type'), 'transactions.transaction_no AS transNo', DB::raw('NULL AS topupNo'))
                                        ->leftJoin('agents AS a', 'a.code', 'transactions.user_id')
                                        ->leftJoin('admins AS m', 'm.code', 'transactions.user_id')
                                        ->leftJoin('users AS u', 'u.code', 'transactions.user_id')
                                        ->where(DB::raw('COALESCE(a.code, m.code, u.code)'), $code)
                                        ->where('transactions.status', 1)
                                        ->where('transactions.mall', 2);

                $adjustTopupWallet = AdjustTopupWallet::select(DB::raw('adjust_topup_wallets.created_at AS created_date'), DB::raw('COALESCE(a.status, u.status) AS status'),
                                            DB::raw('0 AS walletIn'), DB::raw('0 AS walletOut'), 'adjust_topup_wallets.amount AS adjustAmount',
                                            DB::raw('NULL AS tt_status'), DB::raw('NULL AS t_status'), DB::raw('NULL AS t_mall'),
                                            'adjust_topup_wallets.type AS atw_type', DB::raw('NULL AS transNo'), DB::raw('NULL AS topupNo'))
                                    ->leftJoin('agents AS a', 'a.code', DB::raw('adjust_topup_wallets.user_id COLLATE utf8mb4_unicode_ci'))
                                    ->leftJoin('admins AS m', 'm.code', DB::raw('adjust_topup_wallets.user_id COLLATE utf8mb4_unicode_ci'))
                                    ->leftJoin('users AS u', 'u.code', DB::raw('adjust_topup_wallets.user_id COLLATE utf8mb4_unicode_ci'))
                                    ->where(DB::raw('COALESCE(a.code, m.code, u.code)'), $code)
                                    ->where('adjust_topup_wallets.status', 1);

                $transfer_cash_to_topup = AdjustCashToTopup::select(DB::raw('adjust_cash_to_topup.created_at AS created_date'), DB::raw('COALESCE(a.status, u.status) AS status'),
                                                            DB::raw('adjust_cash_to_topup.amount AS walletIn'), DB::raw('0 AS walletOut'), DB::raw('0 AS adjustAmount'),
                                                            DB::raw('NULL AS tt_status'), DB::raw('NULL AS t_status'), DB::raw('NULL AS t_mall'),
                                                            DB::raw('NULL AS atw_type'), DB::raw('NULL AS transNo'), DB::raw('NULL AS topupNo'))
                                                            ->leftJoin('agents AS a', 'a.code', DB::raw('adjust_cash_to_topup.user_id COLLATE utf8mb4_unicode_ci'))
                                                            ->leftJoin('admins AS m', 'm.code', DB::raw('adjust_cash_to_topup.user_id COLLATE utf8mb4_unicode_ci'))
                                                            ->leftJoin('users AS u', 'u.code', DB::raw('adjust_cash_to_topup.user_id COLLATE utf8mb4_unicode_ci'))
                                                            ->where('adjust_cash_to_topup.user_id', $code);
    
                $query = $topupTransaction->unionAll($transaction)
                                          ->unionAll($adjustTopupWallet)
                                          ->unionAll($transfer_cash_to_topup);
        }
    
        $topupResult = [];
        $results = $query->orderBy('created_date', 'desc')->get();
        foreach ($results as $result) {
            $in = 0;
            $out = 0;
            $source = null;
            $number = null;
    
           $translation_data = GlobalController::get_translations();

            if ($result->tt_status == 1) {
                $in = $result->walletIn;
                $source = isset($translation_data['backendlang']['backendlang']['Topup_Transaction'])? $translation_data['backendlang']['backendlang']['Topup_Transaction'] : 'Topup Transaction';
                $number = $result->topupNo;

            } elseif ($result->t_status == 1 && $result->t_mall == 2) {
                $out = $result->walletOut;
                $source = isset($translation_data['backendlang']['backendlang']['Transaction']) ? $translation_data['backendlang']['backendlang']['Transaction'] : 'Transaction';
                $number = $result->transNo;

            } elseif (!empty($result->atw_type)) {

                if ($result->atw_type == 1) {
                    $in = $result->adjustAmount;
                } else {
                    $out = $result->adjustAmount;
                }

                $source = isset($translation_data['backendlang']['backendlang']['Adjust_Topup_Wallet']) ? $translation_data['backendlang']['backendlang']['Adjust_Topup_Wallet']: 'Adjust Topup Wallet';

            } else {

                $in = $result->walletIn;
                $source = isset($translation_data['backendlang']['backendlang']['Adjust_Cash_To_Topup'])? $translation_data['backendlang']['backendlang']['Adjust_Cash_To_Topup']: 'Adjust Cash To Topup';
            }

    
            $topupResult[] = ["dates" => $result->created_date, "source" => $source, "in" => $in,
                              "out" => $out, "number" => $number, "status" => $result->status];
        }

        $queries = [];
        $columns = ['dates'];

        foreach($columns as $column){
            if(request()->has($column) && !empty(request($column))){
                if($column == 'dates'){
                    $topupResult = array_filter($topupResult, function ($search) use ($start, $end) {
                        $searchDate = date('Y-m-d', strtotime($search['dates']));
                        return $searchDate >= $start && $searchDate <= $end;
                    });
                }
                $queries[$column] = request($column);
            }
        }
        
        return view('backend.reports.topup_wallet_report_detail', ['topupResult' => $topupResult,
                                                                    'startDate' => $startDate,
                                                                    'endDate' => $endDate,
                                                                    'code' => $code
                                                                ]);
    }
    
    public function get_previous_cash_balance($userCode, $startDate)
    {
        $transaction = Transaction::where('user_id', $userCode)
                                  ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), '<', $startDate)
                                  ->where('status', '1')
                                  ->where('mall', '1')
                                  ->sum('grand_total');

        $withdraw_transaction = WithdrawalTransaction::where('user_id', $userCode)
                                                     ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), '<', $startDate)
                                                     ->whereIn('status', ['1', '99'])
                                                     ->sum('amount');

        $adjustCashWallet = AdjustCashWallet::where('user_id', $userCode)
                                            ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), '<', $startDate)
                                            ->selectRaw('SUM(CASE WHEN type = 1 THEN amount ELSE 0 END) as cash_in')
                                            ->selectRaw('SUM(CASE WHEN type = 2 THEN amount ELSE 0 END) as cash_out')
                                            ->first();

        $affiliateCommission = AffiliateCommission::where('user_id', $userCode)
                                                  ->where('created_at', '<', $startDate)
                                                  ->where('status', '1')
                                                  ->sum('comm_amount');

        $transfer_cash_to_topup = AdjustCashToTopup::where('user_by', $userCode)
                                                    ->where('created_at', '<', $startDate)
                                                    ->sum('amount');

        $previousBalance = ($affiliateCommission + $adjustCashWallet->cash_in - $adjustCashWallet->cash_out - $transaction - $withdraw_transaction - $transfer_cash_to_topup);

        return $previousBalance;
    }

    public function get_current_cash_balance($userCode, $endDate)
    {
        $transaction = Transaction::where('user_id', $userCode)
                                  ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), '<=', $endDate)
                                  ->where('status', '1')
                                  ->where('mall', '1')
                                  ->sum('grand_total');

        $withdraw_transaction = WithdrawalTransaction::where('user_id', $userCode)
                                                     ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), '<=', $endDate)
                                                     ->whereIn('status', ['1', '99'])
                                                     ->sum('amount');

        $adjustCashWallet = AdjustCashWallet::where('user_id', $userCode)
                                            ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), '<=', $endDate)
                                            ->selectRaw('SUM(CASE WHEN type = 1 THEN amount ELSE 0 END) as cash_in')
                                            ->selectRaw('SUM(CASE WHEN type = 2 THEN amount ELSE 0 END) as cash_out')
                                            ->first();

        $affiliateCommission = AffiliateCommission::where('user_id', $userCode)
                                                  ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), '<=', $endDate)
                                                  ->where('status', '1')
                                                  ->sum('comm_amount');

        $transfer_cash_to_topup = AdjustCashToTopup::where('user_by', $userCode)
                                                    ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), '<=', $endDate)
                                                    ->sum('amount');

        $currentBalance = ($affiliateCommission + $adjustCashWallet->cash_in - $adjustCashWallet->cash_out - $transaction - $withdraw_transaction - $transfer_cash_to_topup);
        //dd($endDate);
        return $currentBalance;
    }

    public function cash_wallet_report()
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

        $translation_data = GlobalController::get_translations();
    
        $agent = Agent::select(
                    DB::raw('CONVERT(CONCAT(agents.f_name, " ", agents.l_name) USING utf8mb4) COLLATE utf8mb4_unicode_ci AS userName'),
                    DB::raw('CONVERT(agents.code USING utf8mb4) COLLATE utf8mb4_unicode_ci AS code'),
                    DB::raw('CONVERT("'.(isset($translation_data["backendlang"]["backendlang"]["Agent"]) ? $translation_data["backendlang"]["backendlang"]["Agent"] : 'Agent').'" USING utf8mb4) COLLATE utf8mb4_unicode_ci AS type'),
                    DB::raw('agents.status AS status')
                )
                ->whereNotIn('agents.status', ['99', '3']);

        $member = User::select(
                    DB::raw('CONVERT(CONCAT(users.f_name, " ", users.l_name) USING utf8mb4) COLLATE utf8mb4_unicode_ci AS userName'),
                    DB::raw('CONVERT(users.code USING utf8mb4) COLLATE utf8mb4_unicode_ci AS code'),
                    DB::raw('CONVERT("'.(isset($translation_data["backendlang"]["backendlang"]["Member"]) ? $translation_data["backendlang"]["backendlang"]["Member"] : 'Member').'" USING utf8mb4) COLLATE utf8mb4_unicode_ci AS type'),
                    DB::raw('users.status AS status')
                )
                ->whereNotIn('users.status', ['99', '3']);
    
        $Users = DB::query()
        ->fromSub($agent->unionAll($member), 'combined_users')
        ->select('userName', 'code', 'status', 'type');

        if (!empty(request('user_type'))) {
            if (request('user_type') == '1') { 
                $Users = $Users->where(DB::raw('LEFT(code, 1)'), 'A');
            } elseif (request('user_type') == '2') { 
                $Users = $Users->where(DB::raw('LEFT(code, 2)'), 'Mb');
            }
        }
    
        $queries = [];
        $columns = ['user_name', 'user_code', 'user_code_desc', 'user_code_asc', 'per_page'];

        $per_page = 10;
        if(!empty(request('per_page'))){
            $per_page = request('per_page');
        }
        
        foreach($columns as $column){
            if(request()->has($column) && !empty(request($column))){
                if($column == 'per_page'){
                    $Users = $Users->paginate($per_page);
                }elseif($column == 'user_name'){ 
                    $Users = $Users->where('userName', 'like', '%'.request($column).'%');
                }elseif($column == 'user_code'){
                    $Users = $Users->where('code', 'like', '%'.request($column).'%');
                }elseif($column == 'user_code_desc'){
                    $Users = $Users->orderBy('code', 'desc');
                }elseif($column == 'user_code_asc'){
                    $Users = $Users->orderBy('code', 'asc');
                }
                $queries[$column] = request($column);
            }
        }

        if(!empty(request('per_page'))){
            $Users = $Users->appends($queries);
        }else{
            $Users = $Users->paginate($per_page)->appends($queries);
        }
    
        $total_cash_in = [];
        $total_cash_out = [];
        $previous_balance = [];
        $current_balance = [];
    
        foreach ($Users as $User) {
            $total_cash_in[$User->code] = AffiliateCommission::where('user_id', $User->code)
                                                             ->whereBetween(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), [$start, $end])
                                                             ->where('status', '1')
                                                             ->sum('comm_amount') +
                                          AdjustCashWallet::where('user_id', $User->code)
                                                          ->whereBetween(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), [$start, $end])
                                                          ->where('type', '1')
                                                          ->where('status', '1')
                                                          ->sum('amount');
    
            $total_cash_out[$User->code] = Transaction::where('user_id', $User->code)
                                                      ->whereBetween(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), [$start, $end])
                                                      ->where('status', '1')
                                                      ->where('mall', '1')
                                                      ->sum('grand_total') +
                                           WithdrawalTransaction::where('user_id', $User->code)
                                                                ->whereBetween(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), [$start, $end])
                                                                ->whereIn('status', ['1', '99'])
                                                                ->sum('amount') +
                                           AdjustCashWallet::where('user_id', $User->code)
                                                            ->whereBetween(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), [$start, $end])
                                                            ->where('type', '2')
                                                            ->where('status', '1')
                                                            ->sum('amount');
                                            AdjustCashToTopup::where('user_by', $User->code)
                                                            ->whereBetween(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), [$start, $end])
                                                            ->sum('amount');
    
            $previous_balance[$User->code] = $this->get_previous_cash_balance($User->code, $start);
            $current_balance[$User->code] = $this->get_current_cash_balance($User->code, $end);
        }
    
        return view('backend.reports.cash_wallet_report', ['Users' => $Users,
                                                            'startDate' => $startDate, 
                                                            'endDate' => $endDate,
                                                            'total_cash_in' => $total_cash_in,
                                                            'total_cash_out' => $total_cash_out,
                                                            'previous_balance' => $previous_balance,
                                                            'current_balance' => $current_balance
                                                        ]);
    }

    public function exportCashWalletReport()
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

        $user_name = "";
        if(!empty(request('user_name'))){
            $user_name = request('user_name');
        }

        $user_code = "";
        if(!empty(request('user_code'))){
            $user_code = request('user_code');
        }

        $user_type = "";
        if(!empty(request('user_type'))){
            $user_type = request('user_type');
        }
        
        return Excel::download(new CashWalletExport($start, $end, $user_name, $user_code,$user_type), 'CashWalletReport'.$start.' - '.$end.'.xlsx');
    }

    public function cash_wallet_report_detail($code)
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

        if($code){
            $transaction = Transaction::select(DB::raw('transactions.created_at AS created_date'), DB::raw('COALESCE(a.status, u.status) AS status'),
                                                DB::raw('0 AS CashIn'), 'transactions.grand_total AS CashOut', DB::raw('0 AS adjustAmount'),
                                                DB::raw('NULL AS wt_status'), DB::raw('NULL AS ac_status'), 'transactions.mall AS t_mall',
                                                DB::raw('NULL AS acw_type'), 'transactions.transaction_no AS transNo', DB::raw('NULL AS WithdrawNo'))
                                        ->leftJoin('agents AS a', 'a.code', 'transactions.user_id')
                                        ->leftJoin('admins AS m', 'm.code', 'transactions.user_id')
                                        ->leftJoin('users AS u', 'u.code', 'transactions.user_id')
                                        ->where(DB::raw('COALESCE(a.code, m.code, u.code)'), $code)
                                        ->where('transactions.status', 1)
                                        ->where('transactions.mall', 1);
            
            $withdraw_transaction = WithdrawalTransaction::select(DB::raw('withdrawal_transactions.created_at AS created_date'), 
                                                                  DB::raw('COALESCE(a.status, u.status) AS status'),
                                                                  DB::raw('0 AS CashIn'), 'withdrawal_transactions.amount AS CashOut', DB::raw('0 AS adjustAmount'),
                                                                  'withdrawal_transactions.status AS wt_status', DB::raw('NULL AS ac_status'), DB::raw('NULL AS t_mall'),
                                                                  DB::raw('NULL AS acw_type'), DB::raw('NULL AS transNo'), 'withdrawal_transactions.withdrawal_no AS WithdrawNo')
                                                           ->leftJoin('agents AS a', 'a.code', 'withdrawal_transactions.user_id')
                                                           ->leftJoin('admins AS m', 'm.code', 'withdrawal_transactions.user_id')
                                                           ->leftJoin('users AS u', 'u.code', 'withdrawal_transactions.user_id')
                                                           ->where(DB::raw('COALESCE(a.code, m.code, u.code)'), $code)
                                                           ->whereIn('withdrawal_transactions.status', ['1', '99']);

           $adjustCashWallet = AdjustCashWallet::select(DB::raw('adjust_cash_wallets.created_at AS created_date'),
                                                        DB::raw('COALESCE(a.status, u.status) AS status'),
                                                        DB::raw('0 AS CashIn'), DB::raw('0 AS CashOut'), 'adjust_cash_wallets.amount AS adjustAmount',
                                                        DB::raw('NULL AS wt_status'), DB::raw('NULL AS ac_status'), DB::raw('NULL AS t_mall'),
                                                        'adjust_cash_wallets.type AS acw_type', DB::raw('NULL AS transNo'), DB::raw('NULL AS WithdrawNo'))
                                                 ->leftJoin('agents AS a', 'a.code', DB::raw('adjust_cash_wallets.user_id COLLATE utf8mb4_unicode_ci'))
                                                 ->leftJoin('admins AS m', 'm.code', DB::raw('adjust_cash_wallets.user_id COLLATE utf8mb4_unicode_ci'))
                                                 ->leftJoin('users AS u', 'u.code', DB::raw('adjust_cash_wallets.user_id COLLATE utf8mb4_unicode_ci'))
                                                 ->where(DB::raw('COALESCE(a.code, m.code, u.code)'), $code)
                                                 ->where('adjust_cash_wallets.status', '1');

            $affiliateCommission = AffiliateCommission::select(DB::raw('affiliate_commissions.created_at AS created_date'),
                                                              DB::raw('COALESCE(a.status, u.status) AS status'),
                                                              'affiliate_commissions.comm_amount AS CashIn', DB::raw('0 AS CashOut'), DB::raw('0 AS adjustAmount'),
                                                              DB::raw('NULL AS wt_status'), 'affiliate_commissions.status AS ac_status', DB::raw('NULL AS t_mall'),
                                                              DB::raw('NULL AS acw_type'), 'affiliate_commissions.transaction_no AS transNo', DB::raw('NULL AS WithdrawNo'))
                                                       ->leftJoin('agents AS a', 'a.code', 'affiliate_commissions.user_id')
                                                       ->leftJoin('admins AS m', 'm.code', 'affiliate_commissions.user_id')
                                                       ->leftJoin('users AS u', 'u.code', 'affiliate_commissions.user_id')
                                                       ->where(DB::raw('COALESCE(a.code, m.code, u.code)'), $code)
                                                       ->where('affiliate_commissions.status', '1');

            $transfer_cash_to_topup = AdjustCashToTopup::select(DB::raw('adjust_cash_to_topup.created_at AS created_date'),
                                                              DB::raw('COALESCE(a.status, u.status) AS status'),
                                                              DB::raw('0 AS CashIn'), 'adjust_cash_to_topup.amount AS CashOut', DB::raw('0 AS adjustAmount'),
                                                              DB::raw('NULL AS wt_status'), DB::raw('NULL AS ac_status'), DB::raw('NULL AS t_mall'),
                                                              DB::raw('NULL AS acw_type'), DB::raw('NULL AS transNo'), DB::raw('NULL AS WithdrawNo'))
                                                    ->leftJoin('agents AS a', 'a.code', 'adjust_cash_to_topup.user_by')
                                                    ->leftJoin('admins AS m', 'm.code', 'adjust_cash_to_topup.user_by')
                                                    ->leftJoin('users AS u', 'u.code', 'adjust_cash_to_topup.user_by')
                                                    ->where('adjust_cash_to_topup.user_by', $code);

            $query = $transaction->unionAll($withdraw_transaction)
                                 ->unionAll($adjustCashWallet)
                                 ->unionAll($affiliateCommission)
                                 ->unionAll($transfer_cash_to_topup);
        }

        $CashDetail = [];
        $results = $query->orderBy('created_date', 'desc')
                         ->get();

        foreach ($results as $result) {
            $in = 0;
            $out = 0;
            $source = null;
            $number = null;

            $translation_data = GlobalController::get_translations();
            
            if($result->t_mall == 1){
                $out = $result->CashOut;
                $source = isset($translation_data['backendlang']['backendlang']['Transaction']) ? $translation_data['backendlang']['backendlang']['Transaction']: 'Transaction';
                $number = $result->transNo;
            }elseif($result->wt_status == 1 || $result->wt_status == 99){
                $out = $result->CashOut;
                $source = isset($translation_data['backendlang']['backendlang']['Withdrawal_Transaction']) ? $translation_data['backendlang']['backendlang']['Withdrawal_Transaction']: 'Transaction';
                $number = $result->WithdrawNo;
            }elseif($result->ac_status == 1){
                $in = $result->CashIn;
                $source = isset($translation_data['backendlang']['backendlang']['Affiliate_Commission']) ? $translation_data['backendlang']['backendlang']['Affiliate_Commission']: 'Affiliate Commission';
                $number = $result->transNo;
            }elseif (!empty($result->acw_type)) {
                if($result->acw_type == 1){
                    $in = $result->adjustAmount;
                }else{
                    $out = $result->adjustAmount;
                }
                $source = isset($translation_data['backendlang']['backendlang']['Adjust_Cash_Wallet']) ? $translation_data['backendlang']['backendlang']['Adjust_Cash_Wallet']: 'Adjust Cash Wallet';
            } else {
                $out = $result->CashOut;
                $source = isset($translation_data['backendlang']['backendlang']['Adjust_Cash_To_Topup']) ? $translation_data['backendlang']['backendlang']['Adjust_Cash_To_Topup']: 'Adjust Cash To Topup';
            }

            $CashDetail[] = ["dates" => $result->created_date, "out" => $out, "in" => $in, "source" => $source,
                              "number" => $number, "status" => $result->status];
        }

        $queries = [];
        $columns = ['dates'];

        foreach($columns as $column){
            if(request()->has($column) && !empty(request($column))){
                if($column == 'dates'){
                    $CashDetail = array_filter($CashDetail, function ($search) use ($start, $end) {
                        $searchDate = date('Y-m-d', strtotime($search['dates']));
                        return $searchDate >= $start && $searchDate <= $end;
                    });
                }
                $queries[$column] = request($column);
            }
        }
        
        return view('backend.reports.cash_wallet_report_detail', ['CashDetail' => $CashDetail,
                                                                    'startDate' => $startDate,
                                                                    'endDate' => $endDate,
                                                                    'code' => $code
                                                                ]);
    }
}