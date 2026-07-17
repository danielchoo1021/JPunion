<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Transaction;
use App\AffiliateCommission;
use App\TransactionDetail;
use App\ProductSecondVariation;
use App\ProductVariation;
use App\WithdrawalTransaction;
use App\TopupTransaction;

use App\Agent;
use App\Merchant;
use App\Product;
use App\User;
use App\Stock;
use App\Cart;

use App\Http\Controllers\GlobalController;

use DB, Auth;

class DashboardController extends Controller
{
    public function index()
    {

        $authorise_merchant = !empty($_COOKIE['vmerchant']) ? $_COOKIE['vmerchant'] : '';
        $get_authorise_status = GlobalController::check_autorize_status($authorise_merchant);

       
        if(!empty(request('filter_monthly_sales'))){
            $selected_monthly = request('filter_monthly_sales');
        }else{
            $selected_monthly = date('Y');
        }

        if(!empty(request('filter_daily_sales'))){
            $selected_daily = request('filter_daily_sales');
        }else{
            $selected_daily = date('m');
        }

    	// $totalSales = Transaction::select(DB::raw('SUM(transactions.grand_total) AS totalSales'))
        //                         //  ->join('transaction_details as d', 'd.transaction_id', 'transactions.id')
    	// 						 ->where('transactions.status', '1')->whereNull('transactions.pv_purchase');
        // // if(Auth::guard('merchant')->check()){
        // // $totalSales = $totalSales->where('d.merchant_id', Auth::user()->code);
        // // }else{
        // // $totalSales = $totalSales->whereNull('d.merchant_id');
        // // }
    	// $totalSales = $totalSales->first();

        $totalSales = Transaction::select(DB::raw('SUM(t.grand_total) AS totalSales'))
                                ->from(DB::raw('(SELECT DISTINCT transactions.id, transactions.grand_total 
                                                FROM transactions
                                                JOIN transaction_details d ON d.transaction_id = transactions.id
                                                WHERE transactions.status = 1 
                                                AND transactions.pv_purchase IS NULL' .
                                                (Auth::guard('merchant')->check() 
                                                    ? ' AND d.merchant_id = ' . DB::getPdo()->quote(Auth::user()->code)
                                                    : ' AND d.merchant_id IS NULL') . ') AS t'));

        $monthlySales = Transaction::select(DB::raw('SUM(t.grand_total) AS monthlySales'))
                          ->from(DB::raw('(SELECT DISTINCT transactions.id, transactions.grand_total 
                                          FROM transactions
                                          JOIN transaction_details d ON d.transaction_id = transactions.id
                                          WHERE transactions.status = 1 
                                          AND transactions.pv_purchase IS NULL
                                         AND DATE_FORMAT(transactions.created_at, "%Y-%m") = ' . DB::getPdo()->quote($selected_monthly . '-' . str_pad($selected_daily, 2, '0', STR_PAD_LEFT)) .
                                          (Auth::guard('merchant')->check() 
                                              ? ' AND d.merchant_id = ' . DB::getPdo()->quote(Auth::user()->code)
                                              : ' AND d.merchant_id IS NULL') . ') AS t'));

        $totalSales = collect(DB::select($totalSales->toSql()))->first();

        $monthlySales = collect(DB::select($monthlySales->toSql()))->first();

        $totalSalesPrice = Transaction::select(DB::raw('SUM(unit_price * quantity) AS totalSales'))
                                 ->join('transaction_details as d', 'd.transaction_id', 'transactions.id')
                                 ->where('transactions.status', '1');
        if(Auth::guard('merchant')->check()){
        $totalSalesPrice = $totalSalesPrice->where('d.merchant_id', Auth::user()->code);
        }else{
        $totalSalesPrice = $totalSalesPrice->whereNull('d.merchant_id');
        }
        $totalSalesPrice = $totalSalesPrice->first();

        $monthlySalesPrice = Transaction::select(DB::raw('SUM(unit_price * quantity) AS monthlySales'))
                               ->join('transaction_details as d', 'd.transaction_id', 'transactions.id')
                               ->where('transactions.status', '1')
                               ->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m")'), $selected_monthly . '-' . str_pad($selected_daily, 2, '0', STR_PAD_LEFT));
                            
        if(Auth::guard('merchant')->check()){
        $monthlySalesPrice = $monthlySalesPrice->where('d.merchant_id', Auth::user()->code);
        }else{
        $monthlySalesPrice = $monthlySalesPrice->whereNull('d.merchant_id');
        }
        $monthlySalesPrice = $monthlySalesPrice->first();

        $salesByCatSub = TransactionDetail::select(DB::raw('SUM(transaction_details.unit_price * transaction_details.quantity) AS totalSales'), DB::raw('SUM(transaction_details.quantity) AS totalQuantity'), 'categories.category_name', 'sub_categories.sub_category_name')
                                            ->join('products', 'products.id', 'transaction_details.product_id')
                                           ->leftJoin('categories', 'categories.id', 'products.category_id')
                                           ->leftJoin('sub_categories', 'sub_categories.id', 'products.sub_category_id')
                                           ->join('transactions', 'transactions.id', 'transaction_details.transaction_id')
                                           ->where('transactions.status', '1')
                                           ->groupBy('categories.id', 'sub_categories.id')
                                           ->orderBy('totalSales', 'desc');

        if(Auth::guard('merchant')->check()){
        $salesByCatSub = $salesByCatSub->where('products.merchant_id', Auth::user()->code);
        }else{
        $salesByCatSub = $salesByCatSub->whereNull('products.merchant_id');
        }
        $salesByCatSub = $salesByCatSub->get();

        // Process sales data for donut chart
        $salesChartData = [];
        $totalSalesAmount = 0;
        
        // Group by category first
        $groupedSales = $salesByCatSub->groupBy('category_name');
        
        foreach ($groupedSales as $categoryName => $subCategories) {
            $categoryTotal = $subCategories->sum('totalSales');
            $totalSalesAmount += $categoryTotal;
            
            $salesChartData[] = [
                'name' => $categoryName ?: 'N/A',
                'y' => $categoryTotal,
                'subcategories' => $subCategories->map(function($item) {
                    return [
                        'name' => $item->sub_category_name ?: 'N/A',
                        'y' => $item->totalSales,
                        'quantity' => $item->totalQuantity
                    ];
                })->toArray()
            ];
        }

        // Sort by sales amount descending
        usort($salesChartData, function($a, $b) {
            return $b['y'] <=> $a['y'];
        });

        // Filtered sales data for specific month/year
        $sales_year = request('filter_sales_year', date('Y'));
        $sales_month = request('filter_sales_month', date('m'));
        
        $filteredSalesByCatSub = TransactionDetail::select(DB::raw('SUM(transaction_details.unit_price * transaction_details.quantity) AS totalSales'), DB::raw('SUM(transaction_details.quantity) AS totalQuantity'), 'categories.category_name', 'sub_categories.sub_category_name')
                                            ->join('products', 'products.id', 'transaction_details.product_id')
                                           ->leftJoin('categories', 'categories.id', 'products.category_id')
                                           ->leftJoin('sub_categories', 'sub_categories.id', 'products.sub_category_id')
                                           ->join('transactions', 'transactions.id', 'transaction_details.transaction_id')
                                           ->where('transactions.status', '1')
                                           ->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m")'), $sales_year.'-'.str_pad($sales_month, 2, '0', STR_PAD_LEFT))
                                           ->groupBy('categories.id', 'sub_categories.id');
                                           //->orderBy('totalSales', 'desc');

        if(Auth::guard('merchant')->check()){
        $filteredSalesByCatSub = $filteredSalesByCatSub->where('products.merchant_id', Auth::user()->code);
        }else{
        $filteredSalesByCatSub = $filteredSalesByCatSub->whereNull('products.merchant_id');
        }

        $queries = [];
        $columns = [
            'totalSales_asc',
            'totalSales_desc',
            'totalQuantity_asc',
            'totalQuantity_desc'
        ];

        $sortApplied = false;
        foreach($columns as $column){
            if(request()->has($column) && !empty(request($column))) {
                if($column == 'totalSales_asc'){
                   $filteredSalesByCatSub = $filteredSalesByCatSub->orderBy('totalSales', 'asc'); 
                }elseif($column == 'totalSales_desc'){
                  $filteredSalesByCatSub = $filteredSalesByCatSub->orderBy('totalSales', 'desc');
                }elseif($column == 'totalQuantity_asc'){
                    $filteredSalesByCatSub = $filteredSalesByCatSub->orderBy('totalQuantity', 'asc');   
                }elseif($column == 'totalQuantity_desc'){
                    $filteredSalesByCatSub = $filteredSalesByCatSub->orderBy('totalQuantity', 'desc');   
                }
                $sortApplied = true;
                $queries[$column] = request($column);
            }
        }

        if(!$sortApplied) {
            $filteredSalesByCatSub = $filteredSalesByCatSub->orderBy('totalSales', 'desc');
        }

        $filteredSalesByCatSub = $filteredSalesByCatSub->get();

        // Process filtered sales data for donut chart
        $filteredSalesChartData = [];
        $filteredTotalSalesAmount = 0;
        
        // Group by category first
        $filteredGroupedSales = $filteredSalesByCatSub->groupBy('category_name');
        
        foreach ($filteredGroupedSales as $categoryName => $subCategories) {
            $categoryTotal = $subCategories->sum('totalSales');
            $filteredTotalSalesAmount += $categoryTotal;
            
            $filteredSalesChartData[] = [
                'name' => $categoryName ?: 'N/A',
                'y' => $categoryTotal,
                'subcategories' => $subCategories->map(function($item) {
                    return [
                        'name' => $item->sub_category_name ?: 'N/A',
                        'y' => $item->totalSales,
                        'quantity' => $item->totalQuantity
                    ];
                })->toArray()
            ];
        }

        // Sort by sales amount descending
        usort($filteredSalesChartData, function($a, $b) {
            return $b['y'] <=> $a['y'];
        }); 

        $totalCommission = AffiliateCommission::select(DB::raw('SUM(comm_amount) AS totalCommission'))
                                              ->where('status', '1');
                                              
        if(Auth::guard('merchant')->check()){
        $totalCommission = $totalCommission->where('merchant_id', Auth::user()->code);
        }else{
        $totalCommission = $totalCommission->whereNull('merchant_id');
        }
        $totalCommission = $totalCommission->first();

         $monthlyCommission = AffiliateCommission::select(DB::raw('SUM(comm_amount) AS monthlyCommission'))
                                              ->where('status', '1')
                                              ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), $selected_monthly . '-' . str_pad($selected_daily, 2, '0', STR_PAD_LEFT));
        if(Auth::guard('merchant')->check()){
        $monthlyCommission = $monthlyCommission->where('merchant_id', Auth::user()->code);
        }else{
        $monthlyCommission = $monthlyCommission->whereNull('merchant_id');
        }
        $monthlyCommission = $monthlyCommission->first();

        $totalTopup = TopupTransaction::select(DB::raw('SUM(amount) AS totalTopup'))
                                              ->where('status', '1');
        if(Auth::guard('merchant')->check()){
        $totalTopup = $totalTopup->where('merchant_id', Auth::user()->code);
        }else{
        $totalTopup = $totalTopup->whereNull('merchant_id');
        }
        $totalTopup = $totalTopup->first();

        $monthlyTopup = TopupTransaction::select(DB::raw('SUM(amount) AS monthlyTopup'))
                                              ->where('status', '1')
                                              ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), $selected_monthly . '-' . str_pad($selected_daily, 2, '0', STR_PAD_LEFT));
        if(Auth::guard('merchant')->check()){
        $monthlyTopup = $monthlyTopup->where('merchant_id', Auth::user()->code);
        }else{
        $monthlyTopup = $monthlyTopup->whereNull('merchant_id');
        }
        $monthlyTopup = $monthlyTopup->first();

        $totalAgents = Agent::select(DB::raw('COUNT(id) AS totalAgent'))
                               ->where('status', '!=', '3');
        if($get_authorise_status['status'] == 1){
        $totalAgents = $totalAgents->where('dual_master_id', $get_authorise_status['result']['code']);
        }
        $totalAgents = $totalAgents->first();

        $totalActiveAgents = Agent::select(DB::raw('COUNT(id) AS totalAgent'))
                                  ->where('status', '1');

        if($get_authorise_status['status'] == 1){
        $totalActiveAgents = $totalActiveAgents->where('dual_master_id', $get_authorise_status['result']['code']);
        }

        $totalActiveAgents = $totalActiveAgents->first();

        $totalCustomers = User::select(DB::raw('COUNT(id) AS totalCustomer'))
                               ->where('status', '!=', '3');
        if($get_authorise_status['status'] == 1){
        $totalCustomers = $totalCustomers->where('dual_master_id', $get_authorise_status['result']['code']);
        }
        $totalCustomers = $totalCustomers->first();

        $totalActiveCustomers = User::select(DB::raw('COUNT(id) AS totalCustomer'))
                                     ->where('status', '1');
        if($get_authorise_status['status'] == 1){
        $totalActiveCustomers = $totalActiveCustomers->where('dual_master_id', $get_authorise_status['result']['code']);
        }
        $totalActiveCustomers = $totalActiveCustomers->first();

   		$totalProduct = Product::select(DB::raw('COUNT(id) AS totalProduct'))
    						  ->where('status', '1')
    						  ->first();

        $top_agent_sales_rankings = Agent::select(DB::raw('SUM(grand_total) as totalSales'),
                                                     'agents.code',
                                                     'agents.f_name',
                                                     'agents.profile_logo',
                                                     'l.agent_lvl')
                                            // ->leftJoin('transactions as t', 't.user_id', 'agents.code')
                                            ->leftJoin('transactions as t', function ($join) {
                                                $join->on('agents.code', '=', 't.user_id')
                                                     ->where('agents.status', '1');
                                            })
                                            ->leftJoin('agent_levels as l', 'l.id', 'agents.lvl')
                                            ->groupBy('agents.code')
                                            ->orderBy('totalSales', 'desc')
                                            ->orderBy('totalSales', 'desc')
                                            ->take(10);
        if(Auth::guard('merchant')->check()){
        $top_agent_sales_rankings = $top_agent_sales_rankings->where('agents.dual_master_id', Auth::user()->code);
        }else{
        $top_agent_sales_rankings = $top_agent_sales_rankings->whereNull('agents.dual_master_id');
        }
        $top_agent_sales_rankings = $top_agent_sales_rankings->get();




        $top_customer_sales_rankings = User::select(DB::raw('SUM(grand_total) as totalSales'),
                                                     'users.code',
                                                     'users.f_name',
                                                     'users.profile_logo')
                                                  
                                            // ->leftJoin('transactions as t', 't.user_id', 'agents.code')
                                            ->leftJoin('transactions as t', function ($join) {
                                                $join->on('users.code', '=', 't.user_id')
                                                     ->where('users.status', '1')
                                                     ->where('t.status', '1')
                                                     ->whereNull('t.pv_purchase');
                                            })
                                            ->groupBy('users.code')
                                            ->orderBy('totalSales', 'desc')
                                            ->take(10);
        if(Auth::guard('merchant')->check()){
        $top_customer_sales_rankings = $top_customer_sales_rankings->where('users.dual_master_id', Auth::user()->code);
        }else{
        $top_customer_sales_rankings = $top_customer_sales_rankings->whereNull('users.dual_master_id');
        }
        $top_customer_sales_rankings = $top_customer_sales_rankings->get();



        $totalCommission = AffiliateCommission::select(DB::raw('SUM(comm_amount) AS totalCommission'))
                                              ->whereIn('type', ['1', '99', '6', '3', '10', '4'])
                                              ->where('status', '1');
                                              
        if(Auth::guard('merchant')->check()){
        $totalCommission = $totalCommission->where('merchant_id', Auth::user()->code);
        }else{
        $totalCommission = $totalCommission->whereNull('merchant_id');
        }
        $totalCommission = $totalCommission->first();


        $top_agent_commission_rankings = Agent::select(DB::raw('SUM(comm_amount) as totalComm'),
                                                     'agents.code',
                                                     'agents.f_name',
                                                     'agents.profile_logo',
                                                     'l.agent_lvl')
                                            ->leftJoin('affiliate_commissions as ac', function ($join) {
                                                $join->on('agents.code', '=', 'ac.user_id')
                                                     ->where('agents.status', '1')
                                                     ->whereIn('ac.type', ['1', '99', '6', '3', '10', '5'])
                                                     ->where('ac.status', '1');
                                            })
                                            ->leftJoin('agent_levels as l', 'l.id', 'agents.lvl')
                                            ->groupBy('agents.code')
                                            ->orderBy('totalComm', 'desc')
                                            ->take(10);
        if(Auth::guard('merchant')->check()){
        $top_agent_commission_rankings = $top_agent_commission_rankings->where('agents.dual_master_id', Auth::user()->code);
        $top_agent_commission_rankings = $top_agent_commission_rankings->where('ac.merchant_id', Auth::user()->code);
        }else{
        $top_agent_commission_rankings = $top_agent_commission_rankings->whereNull('agents.dual_master_id');
        $top_agent_commission_rankings = $top_agent_commission_rankings->whereNull('ac.merchant_id');
        }
        $top_agent_commission_rankings = $top_agent_commission_rankings->get();

        $commissionType = [
            1 => 'Hierachy Bonus' ,
            6 => 'Refferal Reward',
            2 => 'Order Rebate',
            99 => 'Prize Pool',
            3 => 'Performance Reward',
            4 => 'Team Reward',
        ];

        $commissionSummary = AffiliateCommission::select('type', DB::raw('SUM(comm_amount) as total_amount'))
            ->whereIn('type',array_keys($commissionType))
            ->where('status',1)
            ->whereMonth('created_at', date("m"));

        if (Auth::guard('merchant')->check()) {
            $commissionSummary = $commissionSummary->where('merchant_id', Auth::user()->code);
        }else{
            $commissionSummary = $commissionSummary->whereNull('merchant_id');
        }

        $commissionSummary = $commissionSummary->groupBy('type')->get()->keyBy('type');

          $commission_year = request('filter_commission_year', date('Y'));
          $commission_month = request('filter_commission_month', date('m'));
          
          $filteredCommissionSummary = AffiliateCommission::select('type', DB::raw('SUM(comm_amount) as total_amount'))
                                    ->whereIn('type',array_keys($commissionType))
                                    ->where('status',1)
                                    ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), $commission_year.'-'.str_pad($commission_month, 2, '0', STR_PAD_LEFT));

        if (Auth::guard('merchant')->check()) {
            $filteredCommissionSummary = $filteredCommissionSummary->where('merchant_id', Auth::user()->code);
        }else{
            $filteredCommissionSummary = $filteredCommissionSummary->whereNull('merchant_id');
         }

        $filteredCommissionSummary = $filteredCommissionSummary->groupBy('type')->get()->keyBy('type');

        $top_product_sales_rankings = Product::select(DB::raw('SUM(d.quantity * d.unit_price) as totalSales'),
                                                      DB::raw('SUM(d.quantity) as totalQuantity'),
                                                      'products.product_name', 'd.product_image')
                                             ->join('transaction_details as d', 'd.product_id', 'products.id')
                                             ->join('transactions as t', 't.id', 'd.transaction_id')
                                             ->where('t.status', '1')
                                             ->groupBy('products.id')
                                             ->orderBy('totalSales', 'desc')
                                             ->orderBy('products.product_name', 'asc')
                                             ->take(10);
        if(Auth::guard('merchant')->check()){
        $top_product_sales_rankings = $top_product_sales_rankings->where('products.merchant_id', Auth::user()->code);
        }else{
        $top_product_sales_rankings = $top_product_sales_rankings->whereNull('products.merchant_id');
        }
        $top_product_sales_rankings = $top_product_sales_rankings->get();

        $get_top_agent_sales_ranking = [];
        foreach($top_agent_sales_rankings as $key => $top_agent_sales_ranking){
            $get_top_agent_sales_ranking[$key] = $top_agent_sales_ranking;
        }


        $get_top_customer_sales_ranking = [];
        foreach($top_customer_sales_rankings as $key => $top_customer_sales_ranking){
            $get_top_customer_sales_ranking[$key] = $top_customer_sales_ranking;
        }

        $get_top_agent_commission_ranking = [];
        foreach($top_agent_commission_rankings as $key => $top_agent_commission_ranking){
            $get_top_agent_commission_ranking[$key] = $top_agent_commission_ranking;
        }

        $get_top_product_sales_ranking = [];
        foreach($top_product_sales_rankings as $key_two => $top_product_sales_ranking){
            $get_top_product_sales_ranking[$key_two] = $top_product_sales_ranking;
        }

        $product_stock_data = Product::select( 'products.id','products.product_name','products.product_code', 'categories.category_name' ,'products.mall', 'products.variation_enable', 'products.second_variation_enable','products.low_stock_threshold')
            ->leftJoin('categories', 'categories.id', 'products.category_id')
            ->where('products.status', '!=', '3');
           // ->orderBy('products.product_name');
            

        if(Auth::guard('merchant')->check()){
            $product_stock_data = $product_stock_data->where('products.merchant_id', Auth::user()->code);
        }

        $queries = [];
        $columns = [
            'name_asc',
            'name_desc',
            'stock_asc',
            'stock_desc'
        ];

        $per_page = request('per_page', 10);

        foreach($columns as $column){
            if(request()->has($column) && !empty(request($column))) {
                if($column == 'name_asc'){
                   $product_stock_data = $product_stock_data->orderBy('products.product_name', 'asc');
              }elseif($column == 'name_desc'){
                  $product_stock_data = $product_stock_data->orderBy('products.product_name', 'desc');
                }elseif($column == 'stock_asc' || $column == 'stock_desc'){
                }

            $queries[$column] = request($column);
        }
    }

        if (empty($_GET)) {
            $product_stock_data = $product_stock_data->orderBy('products.id', 'desc');
        }

        $is_stock_sorting = (request()->has('stock_asc') && !empty(request('stock_asc'))) || 
                           (request()->has('stock_desc') && !empty(request('stock_desc')));
        
    
        if ($is_stock_sorting) {
            $all_products = $product_stock_data->get();
        } else {
         
            $product_stock_data = $product_stock_data
                ->paginate($per_page)
                ->appends(request()->all());
        }


        $variation_stock_data = [];
        $all_second_variation_under_product = [];
        
        $data_source = $is_stock_sorting ? $all_products : $product_stock_data;
        
        foreach ($data_source as $psd) {
            $variation_stock_data[$psd->id] = ProductVariation::where('product_id', $psd->id)->get();

            $all_second_variation_under_product[$psd->id] = ProductSecondVariation::where('product_id', $psd->id)->get();
        }

        $second_variation_stock_data = [];
        foreach ($variation_stock_data as $product_id => $variations) {
            $second_variation_stock_data[$product_id] = [];
            foreach ($variations as $variation) {
                $second_variation_stock_data[$product_id][$variation->id] = ProductSecondVariation::where('variation_id', $variation->id)->get();
            }
        }

        $stock_data = [];
        foreach ($data_source as $product) {
            $stock_data[$product->id] = [
                'product' => \App\Http\Controllers\GlobalController::balance_quantity($product->id),
                'variations' => [],
                'second_variations' => []
            ];
            
            if ($product->variation_enable == '1' && !$variation_stock_data[$product->id]->isEmpty()) {
                foreach ($variation_stock_data[$product->id] as $variation) {
                    $stock_data[$product->id]['variations'][$variation->id] = \App\Http\Controllers\GlobalController::variation_balance_quantity($variation->id);
                    
                    if ($product->second_variation_enable == '1' && !$second_variation_stock_data[$product->id][$variation->id]->isEmpty()) {
                        foreach ($second_variation_stock_data[$product->id][$variation->id] as $second_variation) {
                            $stock_data[$product->id]['second_variations'][$second_variation->id] = \App\Http\Controllers\GlobalController::second_variation_balance_quantity($second_variation->id);
                        }
                    }
                }
            }
        }

        if ($is_stock_sorting) {
            
            if(request()->has('stock_asc') && !empty(request('stock_asc'))) {
                $all_products = $all_products->sort(function($a, $b) use ($stock_data) {
                    $stock_a = (int)$stock_data[$a->id]['product'];
                    $stock_b = (int)$stock_data[$b->id]['product'];
                    return $stock_a - $stock_b;
                })->values();
            } elseif(request()->has('stock_desc') && !empty(request('stock_desc'))) {
                $all_products = $all_products->sort(function($a, $b) use ($stock_data) {
                    $stock_a = (int)$stock_data[$a->id]['product'];
                    $stock_b = (int)$stock_data[$b->id]['product'];
                    return $stock_b - $stock_a;
                })->values();
            }
           
            $current_page = request('page', 1);
            $offset = ($current_page - 1) * $per_page;
            $paginated_products = $all_products->slice($offset, $per_page);
            
            $product_stock_data = new \Illuminate\Pagination\LengthAwarePaginator(
                $paginated_products,
                $all_products->count(),
                $per_page,
                $current_page,
                [
                    'path' => request()->url(),
                    'pageName' => 'page',
                ]
            );
            $product_stock_data->appends(request()->all());
        }

        $WithdrawalTransaction = WithdrawalTransaction::select(DB::raw('SUM(amount) as totalWithdrawal'))
                                                          ->where('status', '1');

        if(Auth::guard('merchant')->check()){
            $WithdrawalTransaction = $WithdrawalTransaction->where('merchant_id', Auth::user()->code);
        }else{
            $WithdrawalTransaction = $WithdrawalTransaction->whereNull('merchant_id');
        }

        $WithdrawalTransaction = $WithdrawalTransaction->first();

         $monthlyWithdrawalTransaction = WithdrawalTransaction::select(DB::raw('SUM(amount) as monthlyWithdrawal'))
                                                          ->where('status', '1')
                                                          ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), date('Y-m'));                                                        

        if(Auth::guard('merchant')->check()){
            $monthlyWithdrawalTransaction = $monthlyWithdrawalTransaction->where('merchant_id', Auth::user()->code);
        }else{
            $monthlyWithdrawalTransaction = $monthlyWithdrawalTransaction->whereNull('merchant_id');
        }

        $monthlyWithdrawalTransaction = $monthlyWithdrawalTransaction->first();

        $totalCustomer = User::select(DB::raw('COUNT(id) AS totalCustomer'))
                        ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), date('Y-m-d'))
                        ->first();

        $totalAgent = Merchant::select(DB::raw('COUNT(id) AS totalAgent'))
                        ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), date('Y-m-d'))
                        ->first();

        $get_all_monthly_sales = [];
        for ($m=1; $m<=12; $m++) {

            $month = date('Y-m', mktime(0, 0, 0, $m, 1, $selected_monthly));

            $mTransaction = Transaction::select(DB::raw('SUM(transactions.grand_total) AS monthlySales'))
                                       ->join('transaction_details', 'transaction_details.transaction_id', 'transactions.id')
                                       ->where('transactions.status', '1')
                                       ->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m")'), $month);
            if(Auth::guard('merchant')->check()){
            $mTransaction = $mTransaction->where('transaction_details.merchant_id', Auth::user()->code);
            }
            $mTransaction = $mTransaction->first();

            $get_all_monthly_sales[] = !empty($mTransaction->monthlySales) ? $mTransaction->monthlySales : "0.00";
        }

        $implode_sales = implode(',', $get_all_monthly_sales);
        

        $max_days = date('t');
        $get_all_days_sales = [];
        $get_all_days = [];

        for ($day=1; $day<=$max_days; $day++) {

            $time=mktime(12, 0, 0, $selected_daily, $day, $selected_monthly);
            if (date('m', $time) == $selected_daily){
                $dTransaction = Transaction::select(DB::raw('SUM(grand_total) AS dailySales'), DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d") AS today'))
                                           ->join('transaction_details', 'transaction_details.transaction_id', 'transactions.id')
                                           ->where('transactions.status', '1')
                                           ->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), date('Y-m-d', $time));
                if(Auth::guard('merchant')->check()){
                $dTransaction = $dTransaction->where('transaction_details.merchant_id', Auth::user()->code);
                }
                $dTransaction = $dTransaction->first();

                $dailySales = $dTransaction->dailySales;

                $get_all_days_sales[] = !empty($dTransaction->dailySales) ? $dTransaction->dailySales : "0.00";
            }
            $get_all_days[] = $day;
        }


        $implode_days_sales = implode(',', $get_all_days_sales);
        $implode_days = implode(',', $get_all_days);

        $monthKeyMap = [
            '01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr',
            '05' => 'May', '06' => 'Jun', '07' => 'Jul', '08' => 'Aug',
            '09' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dec'
        ];

        $sales_year = request('filter_sales_year', date('Y'));
        $sales_month = request('filter_sales_month', date('m'));
        $salesMonthKey = $monthKeyMap[str_pad($sales_month, 2, '0', STR_PAD_LEFT)] ?? null;

        $commission_year = request('filter_commission_year', date('Y'));
        $commission_month = request('filter_commission_month', date('m'));
        $commissionMonthKey = $monthKeyMap[str_pad($commission_month, 2, '0', STR_PAD_LEFT)] ?? null;

        $currentMonthKey = $monthKeyMap[date('m')] ?? null;
        $currentYear = date('Y');

        // if(Auth::user()->code == 'M000001'){
        //     $key = 'pk_test_088f31aba13aa7070b73f1d271340634';
        //     $secret = 'sk_test_wv7OfmvqOjnkjmLe0xmxvGgNnLNuEnXPPgPjG4LpdsVDinaiEvChSxnIJ4+wyR0Z';

        //     $timestamp = round(microtime(true) * 1000);
        //     $method = "POST";
        //     $path = "https://rest.sandbox.lalamove.com/v3/quotations";

        //     $body = array('data'=>array(
        //                             "serviceType"=>"MOTORCYCLE",
        //                             "language"=>"en_HK",
        //                             "stops"=>array(
        //                                 "coordinates" => array(
        //                                     "lat" => "22.3353139",
        //                                     "lng" => "114.1758402"
        //                                 ),
        //                                 "address"=> "Jl. Perum Dasana"
        //                             ),
        //                         )
        //                  );

        //     $body = json_encode($body);

        //     $signature = $timestamp.'\r\n'.$method.'\r\n'.$path.'\r\n'.$body;

        //     $hash = hash_hmac('sha256', $signature, $secret);
        //     $final_signature = strtoupper($hash);

        //     $token = $key.':'.$timestamp.':'.$final_signature;

        //     $strsTime = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        //     $nonce_string = substr(str_shuffle($strsTime), mt_rand(0, strlen($strsTime) - 11), 40);

        //     $headers = array(
        //                         'Authorization'=>'hmac '.$token,
        //                         'Market'=>'MY',
        //                         'Request-ID'=>$nonce_string
        //                     );
        //     $response = Http::withHeaders($headers)->post($path);

        //     $json = json_decode($response, true);
        //     print_r($json);
            
        //     exit();
        // }

    	return view('backend.dashboard.index', ['totalSales'=>$totalSales, 
                                                'totalCommission'=>$totalCommission, 
                                                'totalAgents'=>$totalAgents, 
                                                'totalProduct'=>$totalProduct,
                                                'WithdrawalTransaction'=>$WithdrawalTransaction,
                                                'top_agent_sales_rankings'=>$top_agent_sales_rankings,
                                                'top_customer_sales_rankings'=>$top_customer_sales_rankings,
                                                'top_agent_commission_rankings'=>$top_agent_commission_rankings,
                                                'top_product_sales_rankings'=>$top_product_sales_rankings,
                                                'totalActiveAgents'=>$totalActiveAgents,
                                                'totalCustomers'=>$totalCustomers,
                                                'totalActiveCustomers'=>$totalActiveCustomers,
                                                'implode_sales'=>$implode_sales,
                                                'implode_days'=>$implode_days,
                                                'implode_days_sales'=>$implode_days_sales,
                                                'selected_monthly'=>$selected_monthly,
                                                'selected_daily'=>$selected_daily,
                                                'salesByCatSub' => $salesByCatSub,
                                                'totalSalesAmount' => $totalSalesAmount,
                                                'filteredSalesByCatSub' => $filteredSalesByCatSub,
                                                'filteredSalesChartData' => $filteredSalesChartData,
                                                'filteredTotalSalesAmount' => $filteredTotalSalesAmount,
                                                'product_stock_data'=>$product_stock_data,
                                                'sales_year' => $sales_year,
                                                'salesMonthKey' => $salesMonthKey,
                                                'commission_year' => $commission_year,
                                                'commissionMonthKey' => $commissionMonthKey,
                                                'currentMonthKey' => $currentMonthKey,
                                                'currentYear' => $currentYear],
                                                compact('get_top_agent_sales_ranking','get_top_customer_sales_ranking',
                                                        'get_top_product_sales_ranking','get_top_product_sales_ranking',  'salesChartData',
                                'get_top_agent_commission_ranking','commissionType','commissionSummary','filteredCommissionSummary',
                                                        'totalTopup', 'monthlySales','monthlyCommission','monthlyWithdrawalTransaction','monthlyTopup','variation_stock_data','second_variation_stock_data','all_second_variation_under_product','stock_data'));
    }
}
