<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// use Illuminate\Support\Facades\Input;
use App\FlashSale;
use App\FlashSaleProductDetail;
use App\FlashSaleProductPrice;
use App\Product;
use App\ProductVariation;
use App\ProductSecondVariation;
use App\Admin;
use App\Merchant;
use App\User;
use App\AgentLevel;

use App\Http\Controllers\GlobalController;

use Validator, Redirect, Toastr, DB, File, Auth;

class FlashSaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $flash_sales = FlashSale::whereNotIn('status', [3, 4]);

        if (Auth::guard('merchant')->check()) {
            $flash_sales = $flash_sales->where('merchant_id', Auth::user()->code);
        }

        $queries = [];

        $per_page = request('per_page', 10);
        if (!empty(request('per_page'))) {
            $queries['per_page'] = $per_page;
        }

        if (!empty(request('title'))) {
            $flash_sales = $flash_sales->where('flash_sales.title', 'like', "%" . request('title') . "%");
            $queries['title'] = request('title');
        }

        if (!empty(request('status'))) {
            $flash_sales = $flash_sales->where('status', request('status'));
            $queries['status'] = request('status');
        }

        if (!empty(request('start_desc'))) {
            $flash_sales = $flash_sales->orderBy('flash_sales.start', 'desc');
            $queries['start_desc'] = request('start_desc');
        } elseif (!empty(request('start_asc'))) {
            $flash_sales = $flash_sales->orderBy('flash_sales.start', 'asc');
            $queries['start_asc'] = request('start_asc');
        } elseif (!empty(request('end_desc'))) {
            $flash_sales = $flash_sales->orderBy('flash_sales.end', 'desc');
            $queries['end_desc'] = request('end_desc');
        } elseif (!empty(request('end_asc'))) {
            $flash_sales = $flash_sales->orderBy('flash_sales.end', 'asc');
            $queries['end_asc'] = request('end_asc');
        } else {
            $flash_sales = $flash_sales->orderBy('flash_sales.created_at', 'desc');
        }

        $flash_sales = $flash_sales->paginate($per_page)->appends($queries);

        $approved_count = [];

        return view('backend.flash_sales.index', ['flash_sales' => $flash_sales]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $products = Product::where('status', '1')
                           ->orderBy('product_name', 'asc');

        $flash_sale = FlashSale::where('status',4)->first();

        if(Auth::guard('merchant')->check()){
        $products = $products->where('merchant_id', Auth::user()->code);
        }
        $products = $products->get();

        $flash_sale_products = collect();
        
        if ($flash_sale) {
        $flash_sale_products = FlashSaleProductDetail::select('flash_sale_product_details.*')
                                                    ->leftJoin('flash_sales as f', 'f.id', 'flash_sale_product_details.flash_sale_id')
                                                    ->where('f.id', $flash_sale->id)
                                                    ->get();
        }

        $count_flash_sale_products = count($flash_sale_products);

        $original_price = [];
        $flash_prices = [];

        foreach($flash_sale_products as $product){
            $flash_prices[$product->id] = FlashSaleProductPrice::select('flash_sale_product_prices.*',
                                                                   DB::raw('al.agent_lvl as agent_level_name'))
                                                          ->leftJoin('agent_levels as al', 'al.id', 'flash_sale_product_prices.agent_lvl_id')
                                                          ->where('flash_sale_product_prices.flash_sale_product_detail_id', $product->id)
                                                          ->where('flash_sale_product_prices.status', '1');
            if(Auth::guard('merchant')->check()){
            $flash_prices[$product->id] = $flash_prices[$product->id]->where('al.merchant_id', Auth::user()->code);
            }else{
            $flash_prices[$product->id] = $flash_prices[$product->id]->whereNull('al.merchant_id');
            }
            $flash_prices[$product->id] = $flash_prices[$product->id]->get();

            foreach($flash_prices[$product->id] as $key => $flash_price){
                $original_price[$flash_price->id] = GlobalController::get_product_pricing(md5($flash_price->product_id), "", $flash_price->variation_id, $flash_price->second_variation_id, "", $flash_price->agent_lvl_id);
            }
        }

        $agent_levels = AgentLevel::select('agent_levels.*');
        if(Auth::guard('merchant')->check()){
        $agent_levels = $agent_levels->where('merchant_id', Auth::user()->code);
        }else{
        $agent_levels = $agent_levels->whereNull('merchant_id');
        }
        $agent_levels = $agent_levels->get();

        return view('backend.flash_sales.create', ['products'=>$products, 
                                                   'flash_sale_products'=>$flash_sale_products,
                                                   'count_flash_sale_products'=>$count_flash_sale_products,
                                                   'flash_sale'=>$flash_sale,
                                                   'agent_levels'=>$agent_levels],
                                                  compact('original_price',
                                                          'flash_prices'));
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
            \DB::beginTransaction();

            // dd($request->all());

            $insert_flash_sale = FlashSale::where('status',4)->first();
           
            if(empty($insert_flash_sale)){
                return Redirect::back()->withInput($request->all())->withErrors($translation_data['backendlang']['backendlang']['No_product_selected'] ?? 'No product selected');
            }

            if(empty($request->title) || empty($request->start) || empty($request->end)){
                return Redirect::back()->withInput($request->all())->withErrors($translation_data['backendlang']['backendlang']['Please_enter_all_the_required_info'] ?? 'Please enter all the required info');
            }

            $insert_flash_sale->title = $request->title;
            $insert_flash_sale->start = date('Y-m-d H:i:s', strtotime($request->start));
            $insert_flash_sale->end = date('Y-m-d H:i:s', strtotime($request->end));
            $insert_flash_sale->status = 1;
            if(Auth::guard('merchant')->check()){
            $insert_flash_sale->merchant_id = Auth::user()->code;
            }
            
            $insert_flash_sale->save();

            // $update_flash_sale_product_details = FlashSaleProductDetail::where('flash_sale_id', '0')
            //                                                            ->where('status', '1')
            //                                                            ->get();

            // foreach($update_flash_sale_product_details as $update){
            //     $update->flash_sale_id = $insert_flash_sale->id;
            //     $update->save();
            // }

            $otherFlashSales = FlashSale::where('id', '<>', $insert_flash_sale->id)
                                        ->where('status', '1')
                                        ->get();

            foreach($otherFlashSales as $other_flash_sale){
                $other_flash_sale->status = '2';
                $other_flash_sale->save();
            }
          
            foreach($request->flash_price as $flash_price_id => $price){
              
                if(empty($price)){  
                    return Redirect::back()->withInput($request->all())->withErrors($translation_data['backendlang']['backendlang']['Please_fill_all_the_flash_sale_prices'] ?? 'Please fill all the flash sale prices');
                }

                $selected_flash_price = FlashSaleProductPrice::find($flash_price_id);

                $selected_flash_price->price = $price;
                $selected_flash_price->save();
            }

            \DB::commit();

            Toastr::success($translation_data['backendlang']['backendlang']['Flash_Sale_Created'] ?? 'Flash Sale Created');
            return redirect()->route('flash_sale.flash_sales.index');
        } catch(\Exception $e){
            \DB::rollback();
            Toastr::error($e->getMessage());
            return Redirect::back()->withInput($request->all())->withErrors($e->getMessage());
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
        $flash_sale = FlashSale::find($id);

        $flash_sale_products = $flash_sale->get_flash_product_details;

        $count_flash_sale_products = count($flash_sale_products);

        $original_price = [];
        $flash_prices = [];

        foreach($flash_sale_products as $product){
            $flash_prices[$product->id] = FlashSaleProductPrice::select('flash_sale_product_prices.*',
                                                                   DB::raw('al.agent_lvl as agent_level_name'))
                                                          ->leftJoin('agent_levels as al', 'al.id', 'flash_sale_product_prices.agent_lvl_id')
                                                          ->where('flash_sale_product_prices.flash_sale_product_detail_id', $product->id)
                                                          ->where('flash_sale_product_prices.status', '1');
            if(Auth::guard('merchant')->check()){
            $flash_prices[$product->id] = $flash_prices[$product->id]->where('al.merchant_id', Auth::user()->code);
            }else{
            $flash_prices[$product->id] = $flash_prices[$product->id]->whereNull('al.merchant_id');
            }
            $flash_prices[$product->id] = $flash_prices[$product->id]->get();

            foreach($flash_prices[$product->id] as $key => $flash_price){
                $original_price[$flash_price->id] = GlobalController::get_product_pricing(md5($flash_price->product_id), "", $flash_price->variation_id, $flash_price->second_variation_id, "", $flash_price->agent_lvl_id);
            }
        }

        $agent_levels = AgentLevel::select('agent_levels.*');
        if(Auth::guard('merchant')->check()){
        $agent_levels = $agent_levels->where('merchant_id', Auth::user()->code);
        }else{
        $agent_levels = $agent_levels->whereNull('merchant_id');
        }
        $agent_levels = $agent_levels->get();

        return view('backend.flash_sales.edit', ['flash_sale'=>$flash_sale,
                                                 'flash_sale_products'=>$flash_sale_products,
                                                 'count_flash_sale_products'=>$count_flash_sale_products,
                                                 'agent_levels'=>$agent_levels],
                                                 compact('flash_prices', 'original_price'));
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
        $translation_data = GlobalController::get_translations();
        try {
            \DB::beginTransaction();
             

            $flash_sale = FlashSale::find($id);

            $flash_sale->title = $request->title;
            $flash_sale->start = date('Y-m-d H:i:s' , strtotime($request->start));
            $flash_sale->end = date('Y-m-d H:i:s' , strtotime($request->end));

            $flash_sale->save();
                
            foreach($request->flash_price as $flash_price_id => $price){
                $selected_flash_price = FlashSaleProductPrice::find($flash_price_id);

                $selected_flash_price->price = $price;
                $selected_flash_price->save();
            }    
                 
            
        \DB::commit();
        
            Toastr::success($translation_data['backendlang']['backendlang']['Flash_Sale_Updated'] ?? 'Flash Sale Updated');
            return redirect()->route('flash_sale.flash_sales.edit', $id);
        } catch(\Exception $e){
            \DB::rollback();
            Toastr::error($e->getMessage());
            return Redirect::back()->withInput($request->all())->withErrors($e->getMessage());
        }
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
}
