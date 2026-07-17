<?php

namespace App\Exports;

use App\Transaction;
use App\StockDetail;
use App\Stock;
use App\TransactionDetail;
use App\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Auth, DB;

class StockDetailExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $start, $end, $product_id, $sort_month , $variation , $second_variation;

	function __construct($start, $end, $product_id, $sort_month, $variation , $second_variation) {
	    $this->start = $start;
        $this->end = $end;
        $this->product_id = $product_id;
        $this->sort_month = $sort_month;
        $this->variation = $variation;
        $this->second_variation = $second_variation;
	}

    public function view(): View
    {
        $products = Product::find($this->product_id);

        $stockBalance = Stock::select(DB::raw('IF(stocks.type = "Increase", stocks.quantity, NULL) AS totalStockIn'),
                                      DB::raw('IF(stocks.type = "Decrease", stocks.quantity, NULL) AS totalStockOut'), 'p.product_name', 'stocks.created_at', 'stocks.product_id')
                                ->leftjoin('products as p', 'p.id', 'stocks.product_id')
                                ->where('stocks.product_id', $this->product_id)
                                ->when(empty($this->variation) && empty($this->second_variation), function ($query) {
                                    $query->whereNull('stocks.variation_id')->whereNull('stocks.second_variation_id');
                                })
                                ->when(!empty($this->variation) && empty($this->second_variation), function ($query) {
                                    $query->where('stocks.variation_id', $this->variation)
                                        ->whereNull('stocks.second_variation_id');
                                })
                                ->when(!empty($this->variation) && !empty($this->second_variation), function ($query)  {
                                    $query->where('stocks.variation_id', $this->variation)
                                        ->where('stocks.second_variation_id', $this->second_variation);
                                })
                                ->whereBetween(DB::raw('DATE_FORMAT(stocks.created_at, "%Y-%m-%d")'), array($this->start, $this->end))
                                ->WhereNull('stocks.packages_id');

        $transaction = TransactionDetail::select(DB::raw('quantity AS TransCart'), 'transaction_details.product_name', 'transaction_details.created_at', 'transaction_details.transaction_id', 't.transaction_no')
                                      ->join('transactions AS t', 't.id', 'transaction_details.transaction_id')
                                      ->where('t.status', '1')
                                      ->when(empty($this->variation) && empty($this->second_variation), function ($query) {
                                        $query->where('transaction_details.variation_id',0)->where('transaction_details.second_variation_id',0);
                                      })
                                      ->when(!empty($this->variation) && empty($this->second_variation), function ($query) {
                                          $query->where('transaction_details.variation_id', $this->variation)
                                                ->where('transaction_details.second_variation_id',0);
                                      })
                                      ->when(!empty($this->variation) && !empty($this->second_variation), function ($query)  {
                                          $query->where('transaction_details.variation_id', $this->variation)
                                              ->where('transaction_details.second_variation_id', $this->second_variation);
                                      })
                                      // ->where('t.on_hold', '!=', '99')
                                      ->whereBetween(DB::raw('DATE_FORMAT(transaction_details.created_at, "%Y-%m-%d")'), array($this->start, $this->end))
                                      ->where('product_id', $this->product_id);

        $transactionDelivery = TransactionDetail::select(DB::raw('IF(tp.quantity > 0, (tp.quantity * transaction_details.quantity), transaction_details.quantity) AS TransCart'), 'transaction_details.product_name', 'transaction_details.created_at', 'transaction_details.transaction_id', 't.transaction_no', 'transaction_details.costing_price', 'transaction_details.unit_price')
                                      ->join('transactions AS t', 't.id', 'transaction_details.transaction_id')
                                      ->join('transaction_packages AS tp', 'tp.detail_id', 'transaction_details.id')
                                      ->where('t.status', '1')
                                      ->when(empty($this->variation) && empty($this->second_variation), function ($query) {
                                        $query->where('transaction_details.variation_id',0)->where('transaction_details.second_variation_id',0);
                                      })
                                      ->when(!empty($this->variation) && empty($this->second_variation), function ($query) {
                                          $query->where('transaction_details.variation_id', $this->variation)
                                                ->where('transaction_details.second_variation_id',0);
                                      })
                                      ->when(!empty($this->variation) && !empty($this->second_variation), function ($query)  {
                                          $query->where('transaction_details.variation_id', $this->variation)
                                              ->where('transaction_details.second_variation_id', $this->second_variation);
                                      })
                                      ->whereNull('transaction_details.deduct_qty')
                                      // ->whereNull('t.on_hold')
                                      ->whereBetween(DB::raw('DATE_FORMAT(transaction_details.created_at, "%Y-%m-%d")'), array($this->start, $this->end))
                                      // ->where('product_id', $this->product_id);
                                      ->where(DB::raw('IF(tp.product_id > 0, tp.product_id, transaction_details.product_id)'), $this->product_id);


        $stockBalance1 = Stock::select(DB::raw('IF(stocks.type = "Increase", stocks.quantity, NULL) AS totalStockIn'),
                                      DB::raw('IF(stocks.type = "Decrease", stocks.quantity, NULL) AS totalStockOut'), 'p.product_name', 'stocks.created_at', 'stocks.product_id')
                                ->leftjoin('products as p', 'p.id', 'stocks.product_id')
                                ->when(empty($this->variation) && empty($this->second_variation), function ($query) {
                                    $query->whereNull('stocks.variation_id')->whereNull('stocks.second_variation_id');
                                })
                                ->when(!empty($this->variation) && empty($this->second_variation), function ($query) {
                                    $query->where('stocks.variation_id', $this->variation)
                                        ->whereNull('stocks.second_variation_id');
                                })
                                ->when(!empty($this->variation) && !empty($this->second_variation), function ($query)  {
                                    $query->where('stocks.variation_id', $this->variation)
                                        ->where('stocks.second_variation_id', $this->second_variation);
                                })
                                ->whereBetween(DB::raw('DATE_FORMAT(stocks.created_at, "%Y-%m-%d")'), array($this->start, $this->end))
                                ->where('stocks.product_id', $this->product_id)
                                ->WhereNull('stocks.packages_id');

      $transaction1 = TransactionDetail::select(DB::raw('quantity AS TransCart'), 'transaction_details.product_name', 'transaction_details.created_at', 'transaction_details.transaction_id', 't.transaction_no')
                                      ->join('transactions AS t', 't.id', 'transaction_details.transaction_id')
                                      ->where('t.status', '1')
                                      ->when(empty($this->variation) && empty($this->second_variation), function ($query) {
                                        $query->where('transaction_details.variation_id',0)->where('transaction_details.second_variation_id',0);
                                      })
                                      ->when(!empty($this->variation) && empty($this->second_variation), function ($query) {
                                          $query->where('transaction_details.variation_id', $this->variation)
                                                ->where('transaction_details.second_variation_id',0);
                                      })
                                      ->when(!empty($this->variation) && !empty($this->second_variation), function ($query)  {
                                          $query->where('transaction_details.variation_id', $this->variation)
                                              ->where('transaction_details.second_variation_id', $this->second_variation);
                                      })
                                      // ->where('t.on_hold', '!=', '99')
                                      ->whereBetween(DB::raw('DATE_FORMAT(transaction_details.created_at, "%Y-%m-%d")'), array($this->start, $this->end))
                                      ->where('product_id', $this->product_id);

      $transactionDelivery1 = TransactionDetail::select(DB::raw('quantity AS TransCart'), 'transaction_details.product_name', 'transaction_details.created_at', 'transaction_details.transaction_id', 't.transaction_no')
                                      ->join('transactions AS t', 't.id', 'transaction_details.transaction_id')
                                      ->where('t.status', '1')
                                      ->when(empty($this->variation) && empty($this->second_variation), function ($query) {
                                        $query->where('transaction_details.variation_id',0)->where('transaction_details.second_variation_id',0);
                                      })
                                      ->when(!empty($this->variation) && empty($this->second_variation), function ($query) {
                                          $query->where('transaction_details.variation_id', $this->variation)
                                                ->where('transaction_details.second_variation_id',0);
                                      })
                                      ->when(!empty($this->variation) && !empty($this->second_variation), function ($query)  {
                                          $query->where('transaction_details.variation_id', $this->variation)
                                                ->where('transaction_details.second_variation_id', $this->second_variation);
                                      })
                                      ->whereNull('t.on_hold')
                                      ->whereBetween(DB::raw('DATE_FORMAT(transaction_details.created_at, "%Y-%m-%d")'), array($this->start, $this->end))
                                      ->where('product_id', $this->product_id);


        if($this->sort_month == '1'){
            $transactionDelivery = $transactionDelivery->where(DB::raw('DATE_FORMAT(transaction_details.created_at, "%Y-%m")'), date('Y-01'));
            $stockBalance = $stockBalance->where(DB::raw('DATE_FORMAT(stocks.created_at, "%Y-%m")'), date('Y-01'));
            $transaction = $transaction->where(DB::raw('DATE_FORMAT(transaction_details.created_at, "%Y-%m")'), date('Y-01'));

            $transactionDelivery1 = $transactionDelivery1->where('transaction_details.created_at', '<=', date('Y-01-01 00:00:00'));
            $stockBalance1 = $stockBalance1->where('stocks.created_at', '<=', date('Y-01-01 00:00:00'));
            $transaction1 = $transaction1->where('transaction_details.created_at', '<=', date('Y-01-01 00:00:00'));

          }elseif($this->sort_month == '2'){
            $transactionDelivery = $transactionDelivery->where(DB::raw('DATE_FORMAT(transaction_details.created_at, "%Y-%m")'), date('Y-02'));
            $stockBalance = $stockBalance->where(DB::raw('DATE_FORMAT(stocks.created_at, "%Y-%m")'), date('Y-02'));
            $transaction = $transaction->where(DB::raw('DATE_FORMAT(transaction_details.created_at, "%Y-%m")'), date('Y-02'));

            $transactionDelivery1 = $transactionDelivery1->where('transaction_details.created_at', '<=', date('Y-02-01 00:00:00'));
            $stockBalance1 = $stockBalance1->where('stocks.created_at', '<=', date('Y-02-01 00:00:00'));
            $transaction1 = $transaction1->where('transaction_details.created_at', '<=', date('Y-02-01 00:00:00'));

          }elseif($this->sort_month == '3'){
            $transactionDelivery = $transactionDelivery->where(DB::raw('DATE_FORMAT(transaction_details.created_at, "%Y-%m")'), date('Y-03'));
            $stockBalance = $stockBalance->where(DB::raw('DATE_FORMAT(stocks.created_at, "%Y-%m")'), date('Y-03'));
            $transaction = $transaction->where(DB::raw('DATE_FORMAT(transaction_details.created_at, "%Y-%m")'), date('Y-03'));

            $transactionDelivery1 = $transactionDelivery1->where('transaction_details.created_at', '<=', date('Y-03-01 00:00:00'));
            $stockBalance1 = $stockBalance1->where('stocks.created_at', '<=', date('Y-03-01 00:00:00'));
            $transaction1 = $transaction1->where('transaction_details.created_at', '<=', date('Y-03-01 00:00:00'));
            

            // $transactionDelivery1 = $transactionDelivery1->where(DB::raw('DATE_FORMAT(transaction_details.created_at, "%Y-%m")'), date('Y-03'));
            // $stockBalance1 = $stockBalance1->where(DB::raw('DATE_FORMAT(stocks.created_at, "%Y-%m")'), date('Y-03'));
            // $transaction1 = $transaction1->where(DB::raw('DATE_FORMAT(transaction_details.created_at, "%Y-%m")'), date('Y-03'));

          }elseif($this->sort_month == '4'){
            $transactionDelivery = $transactionDelivery->where(DB::raw('DATE_FORMAT(transaction_details.created_at, "%Y-%m")'), date('Y-04'));
            $stockBalance = $stockBalance->where(DB::raw('DATE_FORMAT(stocks.created_at, "%Y-%m")'), date('Y-04'));
            $transaction = $transaction->where(DB::raw('DATE_FORMAT(transaction_details.created_at, "%Y-%m")'), date('Y-04'));

            $transactionDelivery1 = $transactionDelivery1->where('transaction_details.created_at', '<=', date('Y-04-01 00:00:00'));
            $stockBalance1 = $stockBalance1->where('stocks.created_at', '<=', date('Y-04-01 00:00:00'));
            $transaction1 = $transaction1->where('transaction_details.created_at', '<=', date('Y-04-01 00:00:00'));
            
            // $transactionDelivery1 = $transactionDelivery1->where(DB::raw('DATE_FORMAT(transaction_details.created_at, "%Y-%m")'), date('Y-03'));
            // $stockBalance1 = $stockBalance1->where(DB::raw('DATE_FORMAT(stocks.created_at, "%Y-%m")'), date('Y-03'));
            // $transaction1 = $transaction1->where(DB::raw('DATE_FORMAT(transaction_details.created_at, "%Y-%m")'), date('Y-03'));

          }elseif($this->sort_month == '5'){
            $transactionDelivery = $transactionDelivery->where(DB::raw('DATE_FORMAT(transaction_details.created_at, "%Y-%m")'), date('Y-05'));
            $stockBalance = $stockBalance->where(DB::raw('DATE_FORMAT(stocks.created_at, "%Y-%m")'), date('Y-05'));
            $transaction = $transaction->where(DB::raw('DATE_FORMAT(transaction_details.created_at, "%Y-%m")'), date('Y-05'));

            $transactionDelivery1 = $transactionDelivery1->where('transaction_details.created_at', '<=', date('Y-05-01 00:00:00'));
            $stockBalance1 = $stockBalance1->where('stocks.created_at', '<=', date('Y-05-01 00:00:00'));
            $transaction1 = $transaction1->where('transaction_details.created_at', '<=', date('Y-05-01 00:00:00'));
            

          }elseif($this->sort_month == '6'){
            $transactionDelivery = $transactionDelivery->where(DB::raw('DATE_FORMAT(transaction_details.created_at, "%Y-%m")'), date('Y-06'));
            $stockBalance = $stockBalance->where(DB::raw('DATE_FORMAT(stocks.created_at, "%Y-%m")'), date('Y-06'));
            $transaction = $transaction->where(DB::raw('DATE_FORMAT(transaction_details.created_at, "%Y-%m")'), date('Y-06'));

            $transactionDelivery1 = $transactionDelivery1->where('transaction_details.created_at', '<=', date('Y-06-01 00:00:00'));
            $stockBalance1 = $stockBalance1->where('stocks.created_at', '<=', date('Y-06-01 00:00:00'));
            $transaction1 = $transaction1->where('transaction_details.created_at', '<=', date('Y-06-01 00:00:00'));
            

          }elseif($this->sort_month == '7'){
            $transactionDelivery = $transactionDelivery->where(DB::raw('DATE_FORMAT(transaction_details.created_at, "%Y-%m")'), date('Y-07'));
            $stockBalance = $stockBalance->where(DB::raw('DATE_FORMAT(stocks.created_at, "%Y-%m")'), date('Y-07'));
            $transaction = $transaction->where(DB::raw('DATE_FORMAT(transaction_details.created_at, "%Y-%m")'), date('Y-07'));

            $transactionDelivery1 = $transactionDelivery1->where('transaction_details.created_at', '<=', date('Y-07-01 00:00:00'));
            $stockBalance1 = $stockBalance1->where('stocks.created_at', '<=', date('Y-07-01 00:00:00'));
            $transaction1 = $transaction1->where('transaction_details.created_at', '<=', date('Y-07-01 00:00:00'));
            

          }elseif($this->sort_month == '8'){
            $transactionDelivery = $transactionDelivery->where(DB::raw('DATE_FORMAT(transaction_details.created_at, "%Y-%m")'), date('Y-08'));
            $stockBalance = $stockBalance->where(DB::raw('DATE_FORMAT(stocks.created_at, "%Y-%m")'), date('Y-08'));
            $transaction = $transaction->where(DB::raw('DATE_FORMAT(transaction_details.created_at, "%Y-%m")'), date('Y-08'));

            $transactionDelivery1 = $transactionDelivery1->where('transaction_details.created_at', '<=', date('Y-08-01 00:00:00'));
            $stockBalance1 = $stockBalance1->where('stocks.created_at', '<=', date('Y-08-01 00:00:00'));
            $transaction1 = $transaction1->where('transaction_details.created_at', '<=', date('Y-08-01 00:00:00'));
            
            // exit();
          }elseif($this->sort_month == '9'){
            $transactionDelivery = $transactionDelivery->where(DB::raw('DATE_FORMAT(transaction_details.created_at, "%Y-%m")'), date('Y-09'));
            $stockBalance = $stockBalance->where(DB::raw('DATE_FORMAT(stocks.created_at, "%Y-%m")'), date('Y-09'));
            $transaction = $transaction->where(DB::raw('DATE_FORMAT(transaction_details.created_at, "%Y-%m")'), date('Y-09'));

            $transactionDelivery1 = $transactionDelivery1->where('transaction_details.created_at', '<=', date('Y-09-01 00:00:00'));
            $stockBalance1 = $stockBalance1->where('stocks.created_at', '<=', date('Y-09-01 00:00:00'));
            $transaction1 = $transaction1->where('transaction_details.created_at', '<=', date('Y-09-01 00:00:00'));
            

          }elseif($this->sort_month == '10'){
            $transactionDelivery = $transactionDelivery->where(DB::raw('DATE_FORMAT(transaction_details.created_at, "%Y-%m")'), date('Y-10'));
            $stockBalance = $stockBalance->where(DB::raw('DATE_FORMAT(stocks.created_at, "%Y-%m")'), date('Y-10'));
            $transaction = $transaction->where(DB::raw('DATE_FORMAT(transaction_details.created_at, "%Y-%m")'), date('Y-10'));

            $transactionDelivery1 = $transactionDelivery1->where('transaction_details.created_at', '<=', date('Y-10-01 00:00:00'));
            $stockBalance1 = $stockBalance1->where('stocks.created_at', '<=', date('Y-10-01 00:00:00'));
            $transaction1 = $transaction1->where('transaction_details.created_at', '<=', date('Y-10-01 00:00:00'));


          }elseif($this->sort_month == '11'){
            $transactionDelivery = $transactionDelivery->where(DB::raw('DATE_FORMAT(transaction_details.created_at, "%Y-%m")'), date('Y-11'));
            $stockBalance = $stockBalance->where(DB::raw('DATE_FORMAT(stocks.created_at, "%Y-%m")'), date('Y-11'));
            $transaction = $transaction->where(DB::raw('DATE_FORMAT(transaction_details.created_at, "%Y-%m")'), date('Y-11'));

            $transactionDelivery1 = $transactionDelivery1->where('transaction_details.created_at', '<=', date('Y-11-01 00:00:00'));
            $stockBalance1 = $stockBalance1->where('stocks.created_at', '<=', date('Y-11-01 00:00:00'));
            $transaction1 = $transaction1->where('transaction_details.created_at', '<=', date('Y-11-01 00:00:00'));
            

          }elseif($this->sort_month == '12'){
            $transactionDelivery = $transactionDelivery->where(DB::raw('DATE_FORMAT(transaction_details.created_at, "%Y-%m")'), date('Y-12'));
            $stockBalance = $stockBalance->where(DB::raw('DATE_FORMAT(stocks.created_at, "%Y-%m")'), date('Y-12'));
            $transaction = $transaction->where(DB::raw('DATE_FORMAT(transaction_details.created_at, "%Y-%m")'), date('Y-12'));

            $transactionDelivery1 = $transactionDelivery1->where('transaction_details.created_at', '<=', date('Y-12-01 00:00:00'));
            $stockBalance1 = $stockBalance1->where('stocks.created_at', '<=', date('Y-12-01 00:00:00'));
            $transaction1 = $transaction1->where('transaction_details.created_at', '<=', date('Y-12-01 00:00:00'));
            

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
        // print_r($stocks); 
        // exit();

        return view('backend.reports.download_stock_details_report', ['stocks'=>$stocks, 'start'=>$this->start, 'end'=>$this->end, 'products'=>$products, 'openStock'=>$openStock, 'closedStock'=>$closedStock]);
    }
}
