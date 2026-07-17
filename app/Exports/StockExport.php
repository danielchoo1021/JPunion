<?php

namespace App\Exports;

use App\Transaction;
use App\StockDetail;
use App\Stock;
use App\TransactionDetail;
use App\Product;
use App\ProductVariation;
use App\ProductSecondVariation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Auth, DB;

class StockExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $product_name, $item_code;

	function __construct($product_name, $item_code) {
        $this->product_name = $product_name;
        $this->item_code = $item_code;
	}

    public function view(): View
    {
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

      if(!empty($this->item_code)){
        $normal_products = $normal_products->where('item_code', 'like', "%".$this->item_code."%");
        $variation_products = $variation_products->where('item_code', 'like', "%".$this->item_code."%");
        $second_variation_products = $second_variation_products->where('item_code', 'like', "%".$this->item_code."%");
      }

      if(!empty($this->product_name)){
        $normal_products = $normal_products->where('product_name', 'like', "%".$this->product_name."%");
        $variation_products = $variation_products->where('product_name', 'like', "%".$this->product_name."%");
        $second_variation_products = $second_variation_products->where('product_name', 'like', "%".$this->product_name."%");
      }
      

      $products = $normal_products
      ->union($variation_products)
      ->union($second_variation_products);

      $products = $products->orderBy('product_name', 'asc');
      
      $products = $products->get();


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
                                        //   ->where('t.on_hold', '!=', '99')
                                          ->where('product_id', $product->id)
                                          ->first();

            $totalSoldStockByDelivery[$key] = TransactionDetail::select(DB::raw('SUM(quantity) AS TransCart'))
                                          ->join('transactions AS t', 't.id', 'transaction_details.transaction_id')
                                          ->where('t.status', '1')
                                          ->whereNull('t.on_hold')
                                          ->where('product_id', $product->id)
                                          ->first(); 

            $currentStockAmount[$key] = $totalInStock[$key]->totalStockIn - $totalOutStock[$key]->totalStockOut - $totalSoldStock[$key]->TransCart - $totalSoldStockByDelivery[$key]->TransCart;

          }elseif(empty($product->packages)){
            // $totalInStock[$key] = Stock::select(DB::raw('SUM(IF(type = "Increase", quantity, NULL)) AS totalStockIn'))
            //                                     ->where('product_id', $product->id)
            //                                     ->WhereNull('packages_id')
            //                                     ->first();

            // $totalOutStock[$key] = Stock::select(DB::raw('SUM(IF(type = "Decrease", quantity, NULL)) AS totalStockOut'))
            //                                     ->where('product_id', $product->id)
            //                                     ->WhereNull('packages_id')
            //                                     ->first();

            // $totalSoldStock[$key] = TransactionDetail::select(DB::raw('SUM(quantity) AS TransCart'))
            //                               ->join('transactions AS t', 't.id', 'transaction_details.transaction_id')
            //                               ->where('t.status', '1')
            //                               ->where('t.on_hold', '!=', '99')
            //                               ->where('product_id', $product->id)
            //                               ->first();

            // $totalSoldStockByDelivery[$key] = TransactionDetail::select(DB::raw('SUM(quantity) AS TransCart'))
            //                               ->join('transactions AS t', 't.id', 'transaction_details.transaction_id')
            //                               ->where('t.status', '1')
            //                               ->whereNull('t.on_hold')
            //                               ->where('product_id', $product->id)
            //                               ->first();

            // $currentStockAmount[$key] = $totalInStock[$key]->totalStockIn - $totalOutStock[$key]->totalStockOut - $totalSoldStock[$key]->TransCart - $totalSoldStockByDelivery[$key]->TransCart;
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
          //   $totalInStock[$key] = Stock::select(DB::raw('SUM(IF(type = "Increase", quantity, NULL)) AS totalStockIn'))
          //                                       ->where('product_id', $product->id)
          //                                       ->WhereNull('packages_id')
          //                                       ->first();

          //   $totalOutStock[$key] = Stock::select(DB::raw('SUM(IF(type = "Decrease", quantity, NULL)) AS totalStockOut'))
          //                                       ->where('product_id', $product->id)
          //                                       ->WhereNull('packages_id')
          //                                       ->first();

          //   $totalSoldStock[$key] = TransactionDetail::select(DB::raw('SUM(quantity) AS TransCart'))
          //                                 ->join('transactions AS t', 't.id', 'transaction_details.transaction_id')
          //                                 ->where('t.status', '1')
          //                                 ->where('t.on_hold', '!=', '99')
          //                                 ->where('product_id', $product->id)
          //                                 ->first();

          //   $totalSoldStockByDelivery[$key] = TransactionDetail::select(DB::raw('SUM(quantity) AS TransCart'))
          //                                 ->join('transactions AS t', 't.id', 'transaction_details.transaction_id')
          //                                 ->where('t.status', '1')
          //                                 ->whereNull('t.on_hold')
          //                                 ->where('product_id', $product->id)
          //                                 ->first();

          //   $currentStockAmount[$key] = $totalInStock[$key]->totalStockIn - $totalOutStock[$key]->totalStockOut - $totalSoldStock[$key]->TransCart - $totalSoldStockByDelivery[$key]->TransCart;
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

      return view('backend.reports.download_stock_report', ['products'=>$products, 'item_code'=>$this->item_code, 'product_name'=>$this->product_name], compact('totalInStock', 'totalOutStock', 'totalSoldStock', 'currentStockAmount', 'totalSoldStockByDelivery', 'stockSoldPrice', 'stockCostPrice'));
    }
}
