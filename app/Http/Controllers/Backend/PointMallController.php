<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// use Illuminate\Support\Facades\Input;
use App\Product;
use App\ProductImage;
use App\Stock;
use App\Category;
use App\SubCategory;
use App\Brand;
use App\Cart;
use App\TransactionDetail;
use App\SettingUom;
use App\AgentLevel;
use App\ProductVariation;
use App\ProductSecondVariation;
use App\AgentPrice;
use App\Promotion;

use App\Http\Controllers\GlobalController;

use Validator, Redirect, Toastr, DB, File;

class PointMallController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product = Product::orderBy('created_at', 'desc')
                          ->where('mall', '1')
                          ->where('status', '!=', '3');

        $queries = [];
        $columns = [
            'product_name', 'status'
        ];
        foreach($columns as $column){
            if(request()->has($column) && !empty(request($column))){
                
                $product = $product->where($column, 'like', "%".request($column)."%");

                $queries[$column] = request($column);

            }
        }
        $per_page = 10;
        if(!empty(request('per_page'))){
            $per_page = request('per_page');
        }
        $product = $product->paginate($per_page)->appends($queries);
        $quantity = [];
        foreach($product as $value){
            $quantity[$value->id] = $this->BalanceQuantity($value->id);
        }

        return view('backend.products.point_product_index', ['products' => $product], compact('quantity'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::where('status', '1');
        if(Auth::guard('merchant')->check()){
        $categories = $categories->where('merchant_id', Auth::user()->code);
        }
        $categories = $categories->get();
        
        $brands = Brand::where('status', '1');
        if(Auth::guard('merchant')->check()){
        $brands = $brands->where('merchant_id', Auth::user()->code);
        }
        $brands = $brands->get();

        $UOMs = SettingUom::select('setting_uoms.*');
        if(Auth::guard('merchant')->check()){
        $UOMs = $UOMs->where('merchant_id', Auth::user()->code);
        }
        $UOMs = $UOMs->get();

        $sub_categories = SubCategory::where('status', '1')->get();
        $agent_levels = AgentLevel::get();

        $promotions = Promotion::where('status', '1')
                               ->get();

        return view('backend.products.point_product_create', ['categories'=>$categories, 'brands'=>$brands, 
                                                              'sub_categories'=>$sub_categories,
                                                              'UOMs'=>$UOMs,
                                                              'agent_levels'=>$agent_levels,
                                                              'promotions'=>$promotions]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            
        ]);

        if($request->variation_enable != '1'){
            $validator = Validator::make($request->all(), [
                'product_name' => 'required',
                'category_id' => 'required',
                'price' => 'required',
                'quantity' => 'required',
            ]);

            if($request->price <= 0){
                return Redirect::back()->withInput()->withErrors('Price must > 0');
            }
        }else{
            $validator = Validator::make($request->all(), [
                'product_name' => 'required',
                'category_id' => 'required',
            ]);
        }

        if ($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }

        try{
            \DB::beginTransaction();

            $c_detail = Category::find($request->category_id);
            $product = Product::select(DB::raw('COUNT(id) AS TotalCount'))
                              ->where('category_id', $request->category_id)
                              ->first();

            $totalCount = $product->TotalCount+1;

            if(strlen($totalCount) == 1){
                $code = "00".$totalCount;
            }elseif(strlen($totalCount) == 2){
                $code = "0".$totalCount;
            }else{
                $code = $totalCount;
            }

            $product = new Product();

            if(Auth::guard('merchant')->check()){
            $product->merchant_id = Auth::user()->code;
            }
            //Top Settings
            $product->featured = isset($request->featured) ? '1' : '0';
            $product->dow = isset($request->dow) ? '1' : '0';
            $product->free_shipping = isset($request->free_shipping) ? '1' : '0';
            $product->free_east_shipping = isset($request->free_east_shipping) ? '1' : '0';
            $product->agent_only = isset($request->agent_only) ? '1' : '0';
            $product->customer_only = isset($request->customer_only) ? '1' : '0';
            $product->mall = !empty($request->mall) ? $request->mall : NULL;

            //Product Details
            $product->item_code = GlobalController::product_item_code($request->category_id);
            $product->product_code = trim($request->product_code);
            $product->product_name = trim($request->product_name);
            $product->product_type = trim($request->product_type);
            $product->category_id = $request->category_id;
            $product->brand_id = $request->brand_id;
            $product->description = $request->description;
            $product->label = trim($request->label);
            $product->variation_title = trim($request->variation_title);
            $product->second_variation_title = trim($request->variation_two_title);
            $product->second_variation_enable = trim($request->variation_two_enable);
            $product->variation_enable = trim($request->variation_enable);
            $product->get_point = trim($request->get_point);
            $product->level_up = $request->level_up;
            
            $product->testimonial = $request->testimonial;
            $product->short_description = $request->short_description;
            
            
            //Pricing
            $product->price = preg_replace("/[^0-9\.]/", '', $request->price);
            $product->special_price = preg_replace("/[^0-9\.]/", '', $request->special_price);
            $product->weight = preg_replace("/[^0-9\.]/", '', $request->weight);
            $product->costing_price = preg_replace("/[^0-9\.]/", '', $request->costing_price);

            $product->birthday_price = preg_replace("/[^0-9\.]/", '', $request->birthday_price);
            $product->birthday_special_price = preg_replace("/[^0-9\.]/", '', $request->birthday_special_price);
            $product->save();

            //Agent Price
            for($z=0; $z<count($request->every_agent_price); $z++){

                $agent_price = new AgentPrice();
                $agent_price->product_id = $product->id;
                $agent_price->agent_lvl_id = $request->aid[$z];
                $agent_price->price = $request->every_agent_price[$z];
                $agent_price->special_price = $request->every_agent_special_price[$z];
                $agent_price->birthday_price = $request->level_price[$z];
                $agent_price->birthday_special_price = $request->level_special_price[$z];
                if(!empty($request->every_agent_price[$z])){
                $agent_price->save();
                }
            }

            //Move Image
            $move = ProductImage::where('status', '99')->get();
            foreach($move as $key => $value){
                $files = $value->image;
                $explode = explode('/', $files);

                if (!File::exists(GlobalController::get_image_path('uploads/'.$product->id))) {
                    File::makeDirectory(GlobalController::get_image_path('uploads/'.$product->id), $mode = 0777, true, true);
                }
                
                rename(GlobalController::get_image_path($value->image), GlobalController::get_image_path('uploads/'.$product->id.'/'.end($explode)));

                $updateI = ProductImage::find($value->id);
                $updateI->image = 'uploads/'.$product->id.'/'.end($explode);
                $updateI->product_id = $product->id;
                $updateI->status = 1;
                $updateI->save();
            }


            //Product Stocks
            if(!empty($request->quantity)){
                $stocks = new Stock();
                $stocks->type = 'Increase';
                $stocks->quantity = $request->quantity;
                $stocks->product_id = $product->id;
                $stocks->remark = 'Open Stock';
                $stocks->save();                
            }

            if(!empty($product->variation_enable)){
                for($a=0; $a<count($request->variation_option); $a++){
                    if(!empty($request['fvid'][$a])){
                        
                        $update_variation = ProductVariation::find($request['fvid'][$a]);
                        if(empty($request->variation_option[$a])){
                            $update_variation = $update_variation->update(['status'=>'3']);
                        }else{
                            
                            if($request->variation_two_enable == 1){
                                $update_variation->variation_name = $request->variation_option[$a];
                            }else{
                                $update_variation->variation_name = $request->variation_option[$a];
                                $update_variation->variation_price = $request['customer_price_'.$a][0];
                                $update_variation->variation_special_price = $request['customer_special_price_'.$a][0];
                                $update_variation->variation_birthday_price = $request['birthday_special_price_'.$a][0];
                                $update_variation->variation_weight = $request['weight_'.$a][0];
                                $update_variation->variation_get_point = $request['get_point_'.$a][0];
                                $update_variation->variation_costing_price = $request['variation_costing_price_'.$a][0];
                            }

                            $update_variation->save();
                        }

                        if($request->variation_two_enable == 1){
                            
                            for($b=0; $b<count($request['customer_price_'.$a]); $b++){
                                if(!empty($request['rid_'.$a][$b])){
                                    $update_second_variation = ProductSecondVariation::find($request['rid_'.$a][$b]);

                                    $update_second_variation->variation_name = $request['variation_option_two_value_'.$a][$b];
                                    $update_second_variation->variation_price = $request['customer_price_'.$a][$b];
                                    $update_second_variation->variation_special_price = $request['customer_special_price_'.$a][$b];
                                    $update_second_variation->variation_birthday_price = $request['birthday_special_price_'.$a][$b];
                                    $update_second_variation->variation_weight = $request['weight_'.$a][$b];
                                    $update_second_variation->variation_get_point = $request['get_point_'.$a][$b];
                                    $update_second_variation->variation_costing_price = $request['variation_costing_price_'.$a][$b];
                                    $update_second_variation->save();

                                    for($c=0; $c<count($request['agent_level_price_'.$a.'_'.$b]); $c++){
                                        if(!empty($request['variation_agent_level_id_'.$a.'_'.$b][$c])){

                                            $update_variation_price = AgentPrice::find($request['variation_agent_level_id_'.$a.'_'.$b][$c]);
                                            $update_variation_price->price = $request['agent_level_price_'.$a.'_'.$b][$c];
                                            $update_variation_price->special_price = $request['agent_level_special_price_'.$a.'_'.$b][$c];
                                            $update_variation_price->birthday_price = $request['agent_level_birthday_price_'.$a.'_'.$b][$c];
                                            $update_variation_price->birthday_special_price = $request['agent_level_birthday_special_price_'.$a.'_'.$b][$c];
                                            $update_variation_price->save();

                                        }else{

                                            $insert_variation_price = new AgentPrice();
                                            $insert_variation_price->product_id = $product->id;
                                            $insert_variation_price->variation_id = $request['fvid'][$a];
                                            $insert_variation_price->second_variation_id = $request['rid_'.$a][$b];
                                            $insert_variation_price->agent_lvl_id = $request['variation_agent_level_'.$a.'_'.$b][$c];
                                            $insert_variation_price->price = $request['agent_level_price_'.$a.'_'.$b][$c];
                                            $insert_variation_price->special_price = $request['agent_level_special_price_'.$a.'_'.$b][$c];
                                            $insert_variation_price->birthday_price = $request['agent_level_birthday_price_'.$a.'_'.$b][$c];
                                            $insert_variation_price->birthday_special_price = $request['agent_level_birthday_special_price_'.$a.'_'.$b][$c];
                                            $insert_variation_price->save();

                                        }
                                    }
                                }else{
                                    
                                    if(!empty($request['variation_option_two_value_'.$a][$b])){
                                        
                                        $insertSecondVariation = new ProductSecondVariation();

                                        $insertSecondVariation->product_id = $product->id;
                                        $insertSecondVariation->variation_id = $request['fvid'][$a];
                                        $insertSecondVariation->variation_name = $request['variation_option_two_value_'.$a][$b];
                                        $insertSecondVariation->variation_price = $request['customer_price_'.$a][$b];
                                        $insertSecondVariation->variation_special_price = $request['customer_special_price_'.$a][$b];
                                        $insertSecondVariation->variation_birthday_price = $request['birthday_special_price_'.$a][$b];
                                        $insertSecondVariation->variation_weight = $request['weight_'.$a][$b];
                                        $insertSecondVariation->variation_get_point = $request['get_point_'.$a][$b];
                                        $insertSecondVariation->variation_costing_price = $request['variation_costing_price_'.$a][$b];
                                        $insertSecondVariation->save();
                                        
                                        if(!empty($request['stock_'.$a][$b])){
                                            $variation_second_stock = new Stock();
                                            $variation_second_stock->product_id = $product->id;
                                            $variation_second_stock->variation_id = $request['fvid'][$a];
                                            $variation_second_stock->second_variation_id = $insertSecondVariation->id;
                                            $variation_second_stock->type = "Increase";
                                            $variation_second_stock->quantity = $request['stock_'.$a][$b];
                                            $variation_second_stock->remark = "Open Stock";
                                            $variation_second_stock->save();
                                        }

                                        for($c=0; $c<count($request['agent_level_price_'.$a.'_'.$b]); $c++){

                                            $insert_variation_price = new AgentPrice();
                                            $insert_variation_price->product_id = $product->id;
                                            $insert_variation_price->variation_id = $request['fvid'][$a];
                                            $insert_variation_price->second_variation_id = $insertSecondVariation->id;
                                            $insert_variation_price->agent_lvl_id = $request['variation_agent_level_'.$a.'_'.$b][$c];
                                            $insert_variation_price->price = $request['agent_level_price_'.$a.'_'.$b][$c];
                                            $insert_variation_price->special_price = $request['agent_level_special_price_'.$a.'_'.$b][$c];
                                            $insert_variation_price->birthday_price = $request['agent_level_birthday_price_'.$a.'_'.$b][$c];
                                            $insert_variation_price->birthday_special_price = $request['agent_level_birthday_special_price_'.$a.'_'.$b][$c];
                                            $insert_variation_price->save();
                                        }
                                    }
                                }
                            }
                        }else{
                            for($c=0; $c<count($request['variation_agent_level_'.$a.'_0']); $c++){
                                if(!empty($request['variation_agent_level_id_'.$a.'_0'][$c])){

                                    $update_variation_price = AgentPrice::find($request['variation_agent_level_id_'.$a.'_0'][$c]);
                                    $update_variation_price->price = $request['agent_level_price_'.$a.'_0'][$c];
                                    $update_variation_price->special_price = $request['agent_level_special_price_'.$a.'_0'][$c];
                                    $update_variation_price->birthday_price = $request['agent_level_birthday_price_'.$a.'_0'][$c];
                                    $update_variation_price->birthday_special_price = $request['agent_level_birthday_special_price_'.$a.'_0'][$c];
                                    $update_variation_price->save();

                                }else{

                                    $insert_variation_price = new AgentPrice();
                                    $insert_variation_price->product_id = $product->id;
                                    $insert_variation_price->variation_id = $request['fvid'][$a];
                                    $insert_variation_price->agent_lvl_id = $request['variation_agent_level_'.$a.'_0'][$c];
                                    $insert_variation_price->price = $request['agent_level_price_'.$a.'_0'][$c];
                                    $insert_variation_price->special_price = $request['agent_level_special_price_'.$a.'_0'][$c];
                                    $insert_variation_price->birthday_price = $request['agent_level_birthday_price_'.$a.'_0'][$c];
                                    $insert_variation_price->birthday_special_price = $request['agent_level_birthday_special_price_'.$a.'_0'][$c];
                                    $insert_variation_price->save();

                                }
                            }
                        }
                    }else{
                        if(!empty($request->variation_option[$a])){
                            $insertVariation = new ProductVariation();
                            $insertVariation->product_id = $product->id;
                            if($request->variation_two_enable == 1){

                                $insertVariation->variation_name = $request->variation_option[$a];

                            }else{

                                $insertVariation->variation_name = $request->variation_option[$a];
                                $insertVariation->variation_price = $request['customer_price_'.$a][0];
                                $insertVariation->variation_special_price = $request['customer_special_price_'.$a][0];
                                $insertVariation->variation_birthday_price = $request['birthday_special_price_'.$a][0];
                                $insertVariation->variation_weight = $request['weight_'.$a][0];
                                $insertVariation->variation_costing_price = $request['variation_costing_price_'.$a][0];

                            }
                            $insertVariation->save();

                            if($request->variation_two_enable != 1){
                                if(!empty($request['stock_'.$a][0])){
                                    $variation_stock = new Stock();
                                    $variation_stock->product_id = $product->id;
                                    $variation_stock->variation_id = $insertVariation->id;
                                    $variation_stock->type = "Increase";
                                    $variation_stock->quantity = $request['stock_'.$a][0];
                                    $variation_stock->remark = "Open Stock";
                                    $variation_stock->save();
                                }
                            }

                            if($request->variation_two_enable == 1){

                                for($b=0; $b<count($request['customer_price_'.$a]); $b++){
                                    if(!empty($request['variation_option_two_value_'.$a][$b])){
                                        
                                        $insertSecondVariation = new ProductSecondVariation();
                                        $insertSecondVariation->product_id = $product->id;
                                        $insertSecondVariation->variation_id = $insertVariation->id;
                                        $insertSecondVariation->variation_name = $request['variation_option_two_value_'.$a][$b];
                                        $insertSecondVariation->variation_price = $request['customer_price_'.$a][$b];
                                        $insertSecondVariation->variation_special_price = $request['customer_special_price_'.$a][$b];
                                        $insertSecondVariation->variation_birthday_price = $request['birthday_special_price_'.$a][$b];
                                        $insertSecondVariation->variation_weight = $request['weight_'.$a][$b];
                                        $insertSecondVariation->variation_get_point = $request['get_point_'.$a][$b];
                                        $insertSecondVariation->variation_costing_price = $request['variation_costing_price_'.$a][$b];
                                        $insertSecondVariation->save();

                                        if(!empty($request['stock_'.$a][$b])){

                                            $variation_second_stock = new Stock();
                                            $variation_second_stock->product_id = $product->id;
                                            $variation_second_stock->variation_id = $insertVariation->id;
                                            $variation_second_stock->second_variation_id = $insertSecondVariation->id;
                                            $variation_second_stock->type = "Increase";
                                            $variation_second_stock->quantity = $request['stock_'.$a][$b];
                                            $variation_second_stock->remark = "Open Stock";
                                            $variation_second_stock->save();

                                        }

                                        for($c=0; $c<count($request['agent_level_price_'.$a.'_'.$b]); $c++){

                                            $insert_variation_price = new AgentPrice();
                                            $insert_variation_price->product_id = $product->id;
                                            $insert_variation_price->variation_id = $insertVariation->id;
                                            $insert_variation_price->second_variation_id = $insertSecondVariation->id;
                                            $insert_variation_price->agent_lvl_id = $request['variation_agent_level_'.$a.'_'.$b][$c];
                                            $insert_variation_price->price = $request['agent_level_price_'.$a.'_'.$b][$c];
                                            $insert_variation_price->special_price = $request['agent_level_special_price_'.$a.'_'.$b][$c];
                                            $insert_variation_price->birthday_price = $request['agent_level_birthday_price_'.$a.'_'.$b][$c];
                                            $insert_variation_price->birthday_special_price = $request['agent_level_birthday_special_price_'.$a.'_'.$b][$c];
                                            $insert_variation_price->save();
                                        }
                                    }
                                }
                            }else{
                                if($request->variation_enable == 1){
                                    for($c=0; $c<count($request['variation_agent_level_'.$a.'_0']); $c++){

                                        $insert_variation_price = new AgentPrice();
                                        $insert_variation_price->product_id = $product->id;
                                        $insert_variation_price->variation_id = $insertVariation->id;
                                        $insert_variation_price->agent_lvl_id = $request['variation_agent_level_'.$a.'_0'][$c];
                                        $insert_variation_price->price = $request['agent_level_price_'.$a.'_0'][$c];
                                        $insert_variation_price->special_price = $request['agent_level_special_price_'.$a.'_0'][$c];
                                        $insert_variation_price->birthday_price = $request['agent_level_birthday_price_'.$a.'_0'][$c];
                                        $insert_variation_price->birthday_special_price = $request['agent_level_birthday_special_price_'.$a.'_0'][$c];
                                        $insert_variation_price->save();

                                    }
                                }
                            }
                        }
                    }
                }
            }

            \DB::commit();
        }catch (\Exception $e){
            \DB::rollback();
            return Redirect::back()->withInput($request->all())->withErrors($e->getMessage());
        }catch(\Error $e){
            return Redirect::back()->withInput($request->all())->withErrors($e->getMessage());
        }

        Toastr::success("Product $product->product_name Create Successfully!");
        return redirect()->route('point_mall.point_malls.index');
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
        $categories = Category::where('status', '1');
        if(Auth::guard('merchant')->check()){
        $categories = $categories->where('merchant_id', Auth::user()->code);
        }
        $categories = $categories->get();
        
        $brands = Brand::where('status', '1');
        if(Auth::guard('merchant')->check()){
        $brands = $brands->where('merchant_id', Auth::user()->code);
        }
        $brands = $brands->get();

        $UOMs = SettingUom::select('setting_uoms.*');
        if(Auth::guard('merchant')->check()){
        $UOMs = $UOMs->where('merchant_id', Auth::user()->code);
        }
        $UOMs = $UOMs->get();
        
        $product = Product::find($id);
        $code = Category::find($product->category_id);
        if(!isset($product) && empty($product)){
            abort(404);
        }

        $sub_categories = SubCategory::where('category_id', $product->category_id)->get();

        $stockBalance = GlobalController::balance_quantity($id);

        $variations = ProductVariation::where('product_id', $id)->get();

        $svs = ProductSecondVariation::select('product_second_variations.*', 'v.variation_name as v_variation_name')
                                      ->leftJoin('product_variations as v', 'v.id', 'product_second_variations.variation_id')
                                      ->where('product_second_variations.product_id', $id)
                                      ->groupBy('product_second_variations.variation_name')
                                      ->get();

        $secnd_variations = ProductSecondVariation::select('product_second_variations.*', 'v.variation_name as v_variation_name')
                                                  ->leftJoin('product_variations as v', 'v.id', 'product_second_variations.variation_id')
                                                  ->where('product_second_variations.product_id', $id)
                                                  ->get();
        $s_secnd_variations = [];
        $variation_stocks = [];
        $second_variation_stocks = [];
        foreach($variations as $variation){
            $variation_stocks[$variation->id] = GlobalController::variation_balance_quantity($variation->id);
            $s_secnd_variations[$variation->id] = ProductSecondVariation::where('variation_id', $variation->id)->get();
            foreach($s_secnd_variations[$variation->id] as $s_secnd_variation){
                $second_variation_stocks[$s_secnd_variation->variation_id][$s_secnd_variation->id] = GlobalController::second_variation_balance_quantity($s_secnd_variation->id);
            }
        }

        $agent_levels = AgentLevel::get();

        $agent_pricings = AgentPrice::where('product_id', $id)
                                    ->whereNull('variation_id')
                                    ->get();

        $agent_variation_pricings = AgentPrice::where('product_id', $id)
                                              ->whereNotNull('variation_id')
                                              ->get();
        $agent_second_variation_pricings = AgentPrice::where('product_id', $id)
                                                     ->whereNotNull('variation_id')
                                                     ->whereNotNull('second_variation_id')
                                                     ->get();

        $agent_prices = [];
        $agent_special_prices = [];
        $agent_v_prices = [];
        $agent_v_special_prices = [];
        $agent_v2_prices = [];
        $agent_v2_special_prices = [];

        $agent_prices_ids = [];
        $agent_prices_v_ids = [];
        $agent_prices_v2_ids = [];

        $agent_birthday_price = [];
        $agent_birthday_special_price = [];
        $agent_v2_birthday_price = [];
        $agent_v2_birthday_special_price = [];
        $agent_v_birthday_prices = [];
        $agent_v_birthday_special_prices = [];

        foreach($agent_second_variation_pricings as $agent_sec_pricing){
            $agent_v2_prices[$agent_sec_pricing->agent_lvl_id][$agent_sec_pricing->variation_id][$agent_sec_pricing->second_variation_id] = $agent_sec_pricing->price;
            $agent_v2_special_prices[$agent_sec_pricing->agent_lvl_id][$agent_sec_pricing->variation_id][$agent_sec_pricing->second_variation_id] = $agent_sec_pricing->special_price;
            $agent_prices_v2_ids[$agent_sec_pricing->agent_lvl_id][$agent_sec_pricing->variation_id][$agent_sec_pricing->second_variation_id] = $agent_sec_pricing->id;
        
            $agent_v2_birthday_price[$agent_sec_pricing->agent_lvl_id][$agent_sec_pricing->variation_id][$agent_sec_pricing->second_variation_id] = $agent_sec_pricing->birthday_price;
            $agent_v2_birthday_special_price[$agent_sec_pricing->agent_lvl_id][$agent_sec_pricing->variation_id][$agent_sec_pricing->second_variation_id] = $agent_sec_pricing->birthday_special_price;
        }

        foreach($agent_variation_pricings as $agent_first_pricing){
            $agent_v_prices[$agent_first_pricing->agent_lvl_id][$agent_first_pricing->variation_id] = $agent_first_pricing->price;
            $agent_v_special_prices[$agent_first_pricing->agent_lvl_id][$agent_first_pricing->variation_id] = $agent_first_pricing->special_price;
            $agent_prices_v_ids[$agent_first_pricing->agent_lvl_id][$agent_first_pricing->variation_id] = $agent_first_pricing->id;
        
            $agent_v_birthday_prices[$agent_first_pricing->agent_lvl_id][$agent_first_pricing->variation_id] = $agent_first_pricing->birthday_price;
            $agent_v_birthday_special_prices[$agent_first_pricing->agent_lvl_id][$agent_first_pricing->variation_id] = $agent_first_pricing->birthday_special_price;
        }

        foreach($agent_pricings as $agent_pricing){
            // if($product->second_variation_enable == 1 && $product->variation_enable == 1){
            //     $agent_v2_prices[$agent_pricing->agent_lvl_id][$agent_pricing->variation_id][$agent_pricing->second_variation_id] = $agent_pricing->price;
            //     $agent_v2_special_prices[$agent_pricing->agent_lvl_id][$agent_pricing->variation_id][$agent_pricing->second_variation_id] = $agent_pricing->special_price;
            //     $agent_prices_v2_ids[$agent_pricing->agent_lvl_id][$agent_pricing->variation_id][$agent_pricing->second_variation_id] = $agent_pricing->id;

            // }elseif($product->variation_enable == 1 && empty($product->second_variation_enable)){
            //     $agent_v_prices[$agent_pricing->agent_lvl_id][$agent_pricing->variation_id] = $agent_pricing->price;
            //     $agent_v_special_prices[$agent_pricing->agent_lvl_id][$agent_pricing->variation_id] = $agent_pricing->special_price;
            //     $agent_prices_v_ids[$agent_pricing->agent_lvl_id][$agent_pricing->variation_id] = $agent_pricing->id;
            // }else{
                $agent_prices[$agent_pricing->agent_lvl_id] = $agent_pricing->price;
                $agent_special_prices[$agent_pricing->agent_lvl_id] = $agent_pricing->special_price;
                $agent_prices_ids[$agent_pricing->agent_lvl_id] = $agent_pricing->id;

                $agent_birthday_price[$agent_pricing->agent_lvl_id] = $agent_pricing->birthday_price;
                $agent_birthday_special_price[$agent_pricing->agent_lvl_id] = $agent_pricing->birthday_special_price;
            // }
        }

        return view('backend.products.point_product_edit', ['product'=>$product, 'categories'=>$categories, 'brands'=>$brands, 
                                              'sub_categories'=>$sub_categories, 'code'=>$code,
                                              'stockBalance'=>$stockBalance, 'UOMs'=>$UOMs, 'variations'=>$variations,
                                              'secnd_variations'=>$secnd_variations, 'svs'=>$svs,
                                              'agent_levels'=>$agent_levels],
                                              compact('variation_stocks',
                                                      'second_variation_stocks',
                                                      's_secnd_variations',
                                                      'agent_prices', 'agent_prices_ids',
                                                      'agent_v_prices', 'agent_prices_v_ids',
                                                      'agent_v2_prices', 'agent_prices_v2_ids',
                                                      'agent_special_prices',
                                                      'agent_v_special_prices',
                                                      'agent_v2_special_prices'));
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

        $validator = Validator::make($request->all(), [
            
        ]);

        if($request->variation_enable != '1'){
            $validator = Validator::make($request->all(), [
                'product_name' => 'required',
                'category_id' => 'required',
                'price' => 'required',
            ]);

            if($request->price <= 0){
                return Redirect::back()->withInput()->withErrors('Price must > 0');
            }
        }else{
            $validator = Validator::make($request->all(), [
                'product_name' => 'required',
                'category_id' => 'required',
            ]);
        }

        if ($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }

        try{
            \DB::beginTransaction();

            $c_detail = Category::find($request->category_id);
            $product = Product::select(DB::raw('COUNT(id) AS TotalCount'))
                              ->where('category_id', $request->category_id)
                              ->first();

            $totalCount = $product->TotalCount+1;

            if(strlen($totalCount) == 1){
                $code = "00".$totalCount;
            }elseif(strlen($totalCount) == 2){
                $code = "0".$totalCount;
            }else{
                $code = $totalCount;
            }

            $product = Product::find($id);

            //Top Settings
            $product->featured = isset($request->featured) ? '1' : '0';
            $product->dow = isset($request->dow) ? '1' : '0';
            $product->free_shipping = isset($request->free_shipping) ? '1' : '0';
            $product->free_east_shipping = isset($request->free_east_shipping) ? '1' : '0';
            $product->agent_only = isset($request->agent_only) ? '1' : '0';
            $product->customer_only = isset($request->customer_only) ? '1' : '0';

            //Product Details
            $product->item_code = GlobalController::product_item_code($request->category_id);
            $product->product_code = trim($request->product_code);
            $product->product_name = trim($request->product_name);
            $product->product_type = trim($request->product_type);
            $product->category_id = $request->category_id;
            $product->brand_id = $request->brand_id;
            $product->description = $request->description;
            $product->label = trim($request->label);
            $product->variation_title = trim($request->variation_title);
            $product->second_variation_title = trim($request->variation_two_title);
            $product->second_variation_enable = trim($request->variation_two_enable);
            $product->variation_enable = trim($request->variation_enable);
            $product->get_point = trim($request->get_point);
            $product->level_up = $request->level_up;

            
            $product->testimonial = $request->testimonial;
            $product->short_description = $request->short_description;
            
            
            //Pricing
            $product->price = preg_replace("/[^0-9\.]/", '', $request->price);
            $product->special_price = preg_replace("/[^0-9\.]/", '', $request->special_price);
            $product->weight = preg_replace("/[^0-9\.]/", '', $request->weight);
            $product->costing_price = preg_replace("/[^0-9\.]/", '', $request->costing_price);
            $product->birthday_price = preg_replace("/[^0-9\.]/", '', $request->birthday_price);
            $product->birthday_special_price = preg_replace("/[^0-9\.]/", '', $request->birthday_special_price);
            $product->save();

            //Agent Price
            for($z=0; $z<count($request->every_agent_price); $z++){

                if(!empty($request->price_id[$z])){
                $agent_price = AgentPrice::find($request->price_id[$z]);
                }else{
                $agent_price = new AgentPrice();
                }
                $agent_price->product_id = $product->id;
                $agent_price->agent_lvl_id = $request->aid[$z];
                $agent_price->price = $request->every_agent_price[$z];
                $agent_price->special_price = $request->every_agent_special_price[$z];
                $agent_price->birthday_price = $request->level_price[$z];
                $agent_price->birthday_special_price = $request->level_special_price[$z];
                if(!empty($request->every_agent_price[$z])){
                $agent_price->save();
                }
            }

            //Move Image
            $move = ProductImage::where('status', '99')->get();
            foreach($move as $key => $value){
                $files = $value->image;
                $explode = explode('/', $files);

                if (!file_exists(GlobalController::get_production_url('uploads/'.$product->id))) {
                    File::makeDirectory(GlobalController::get_production_url('uploads/'.$product->id), $mode = 0777, true, true);
                }
                
                rename($value->image, GlobalController::get_production_url('uploads/'.$product->id.'/'.end($explode)));

                $updateI = ProductImage::find($value->id);
                $updateI->image = 'uploads/'.$product->id.'/'.end($explode);
                $updateI->product_id = $product->id;
                $updateI->status = 1;
                $updateI->save();
            }


            //Product Stocks
            if(!empty($request->quantity)){
                $stocks = new Stock();
                $stocks->type = 'Increase';
                $stocks->quantity = $request->quantity;
                $stocks->product_id = $product->id;
                $stocks->remark = 'Open Stock';
                $stocks->save();                
            }

            if(!empty($product->variation_enable)){
                for($a=0; $a<count($request->variation_option); $a++){
                    if(!empty($request['fvid'][$a])){
                        
                        $update_variation = ProductVariation::find($request['fvid'][$a]);
                        if(empty($request->variation_option[$a])){
                            $update_variation = $update_variation->update(['status'=>'3']);
                        }else{
                            
                            if($request->variation_two_enable == 1){
                                $update_variation->variation_name = $request->variation_option[$a];
                            }else{
                                $update_variation->variation_name = $request->variation_option[$a];
                                $update_variation->variation_price = $request['customer_price_'.$a][0];
                                $update_variation->variation_special_price = $request['customer_special_price_'.$a][0];
                                $update_variation->variation_birthday_price = $request['birthday_special_price_'.$a][0];
                                $update_variation->variation_weight = $request['weight_'.$a][0];
                                $update_variation->variation_costing_price = $request['variation_costing_price_'.$a][0];
                                $update_variation->variation_get_point = $request['get_point_'.$a][0];
                               
                            }

                            $update_variation->save();
                        }

                        if($request->variation_two_enable == 1){
                            
                            for($b=0; $b<count($request['customer_price_'.$a]); $b++){
                                if(!empty($request['rid_'.$a][$b])){
                                    $update_second_variation = ProductSecondVariation::find($request['rid_'.$a][$b]);

                                    $update_second_variation->variation_name = $request['variation_option_two_value_'.$a][$b];
                                    $update_second_variation->variation_price = $request['customer_price_'.$a][$b];
                                    $update_second_variation->variation_special_price = $request['customer_special_price_'.$a][$b];
                                    $update_second_variation->variation_birthday_price = $request['birthday_special_price_'.$a][$b];
                                    $update_second_variation->variation_weight = $request['weight_'.$a][$b];
                                    $update_second_variation->variation_get_point = $request['get_point_'.$a][$b];
                                    $update_second_variation->variation_costing_price = $request['variation_costing_price_'.$a][$b];
                                    $update_second_variation->save();

                                    for($c=0; $c<count($request['agent_level_price_'.$a.'_'.$b]); $c++){
                                        if(!empty($request['variation_agent_level_id_'.$a.'_'.$b][$c])){

                                            $update_variation_price = AgentPrice::find($request['variation_agent_level_id_'.$a.'_'.$b][$c]);
                                            $update_variation_price->price = $request['agent_level_price_'.$a.'_'.$b][$c];
                                            $update_variation_price->special_price = $request['agent_level_special_price_'.$a.'_'.$b][$c];
                                            $update_variation_price->birthday_price = $request['agent_level_birthday_price_'.$a.'_'.$b][$c];
                                            $update_variation_price->birthday_special_price = $request['agent_level_birthday_special_price_'.$a.'_'.$b][$c];
                                            $update_variation_price->save();

                                        }else{

                                            $insert_variation_price = new AgentPrice();
                                            $insert_variation_price->product_id = $product->id;
                                            $insert_variation_price->variation_id = $request['fvid'][$a];
                                            $insert_variation_price->second_variation_id = $request['rid_'.$a][$b];
                                            $insert_variation_price->agent_lvl_id = $request['variation_agent_level_'.$a.'_'.$b][$c];
                                            $insert_variation_price->price = $request['agent_level_price_'.$a.'_'.$b][$c];
                                            $insert_variation_price->special_price = $request['agent_level_special_price_'.$a.'_'.$b][$c];
                                            $insert_variation_price->birthday_price = $request['agent_level_birthday_price_'.$a.'_'.$b][$c];
                                            $insert_variation_price->birthday_special_price = $request['agent_level_birthday_special_price_'.$a.'_'.$b][$c];
                                            $insert_variation_price->save();

                                        }
                                    }
                                }else{
                                    
                                    if(!empty($request['variation_option_two_value_'.$a][$b])){
                                        
                                        $insertSecondVariation = new ProductSecondVariation();

                                        $insertSecondVariation->product_id = $product->id;
                                        $insertSecondVariation->variation_id = $request['fvid'][$a];
                                        $insertSecondVariation->variation_name = $request['variation_option_two_value_'.$a][$b];
                                        $insertSecondVariation->variation_price = $request['customer_price_'.$a][$b];
                                        $insertSecondVariation->variation_special_price = $request['customer_special_price_'.$a][$b];
                                        $insertSecondVariation->variation_birthday_price = $request['birthday_special_price_'.$a][$b];
                                        $insertSecondVariation->variation_weight = $request['weight_'.$a][$b];
                                        $insertSecondVariation->variation_costing_price = $request['variation_costing_price_'.$a][$b];
                                        $insertSecondVariation->variation_get_point = $request['get_point_'.$a][$b];
                                        $insertSecondVariation->save();
                                        
                                        if(!empty($request['stock_'.$a][$b])){
                                            $variation_second_stock = new Stock();
                                            $variation_second_stock->product_id = $product->id;
                                            $variation_second_stock->variation_id = $request['fvid'][$a];
                                            $variation_second_stock->second_variation_id = $insertSecondVariation->id;
                                            $variation_second_stock->type = "Increase";
                                            $variation_second_stock->quantity = $request['stock_'.$a][$b];
                                            $variation_second_stock->remark = "Open Stock";
                                            $variation_second_stock->save();
                                        }

                                        for($c=0; $c<count($request['agent_level_price_'.$a.'_'.$b]); $c++){

                                            $insert_variation_price = new AgentPrice();
                                            $insert_variation_price->product_id = $product->id;
                                            $insert_variation_price->variation_id = $request['fvid'][$a];
                                            $insert_variation_price->second_variation_id = $insertSecondVariation->id;
                                            $insert_variation_price->agent_lvl_id = $request['variation_agent_level_'.$a.'_'.$b][$c];
                                            $insert_variation_price->price = $request['agent_level_price_'.$a.'_'.$b][$c];
                                            $insert_variation_price->special_price = $request['agent_level_special_price_'.$a.'_'.$b][$c];
                                            $insert_variation_price->birthday_price = $request['agent_level_birthday_price_'.$a.'_'.$b][$c];
                                            $insert_variation_price->birthday_special_price = $request['agent_level_birthday_special_price_'.$a.'_'.$b][$c];
                                            $insert_variation_price->save();
                                        }
                                    }
                                }
                            }
                        }else{
                            for($c=0; $c<count($request['variation_agent_level_'.$a.'_0']); $c++){
                                if(!empty($request['variation_agent_level_id_'.$a.'_0'][$c])){

                                    $update_variation_price = AgentPrice::find($request['variation_agent_level_id_'.$a.'_0'][$c]);
                                    $update_variation_price->price = $request['agent_level_price_'.$a.'_0'][$c];
                                    $update_variation_price->special_price = $request['agent_level_special_price_'.$a.'_0'][$c];
                                    $update_variation_price->birthday_price = $request['agent_level_birthday_price_'.$a.'_0'][$c];
                                    $update_variation_price->birthday_special_price = $request['agent_level_birthday_special_price_'.$a.'_0'][$c];
                                    $update_variation_price->save();

                                }else{

                                    $insert_variation_price = new AgentPrice();
                                    $insert_variation_price->product_id = $product->id;
                                    $insert_variation_price->variation_id = $request['fvid'][$a];
                                    $insert_variation_price->agent_lvl_id = $request['variation_agent_level_'.$a.'_0'][$c];
                                    $insert_variation_price->price = $request['agent_level_price_'.$a.'_0'][$c];
                                    $insert_variation_price->special_price = $request['agent_level_special_price_'.$a.'_0'][$c];
                                    $insert_variation_price->birthday_price = $request['agent_level_birthday_price_'.$a.'_0'][$c];
                                    $insert_variation_price->birthday_special_price = $request['agent_level_birthday_special_price_'.$a.'_0'][$c];
                                    $insert_variation_price->save();

                                }
                            }
                        }
                    }else{
                        if(!empty($request->variation_option[$a])){
                            $insertVariation = new ProductVariation();
                            $insertVariation->product_id = $product->id;
                            if($request->variation_two_enable == 1){

                                $insertVariation->variation_name = $request->variation_option[$a];

                            }else{

                                $insertVariation->variation_name = $request->variation_option[$a];
                                $insertVariation->variation_price = $request['customer_price_'.$a][0];
                                $insertVariation->variation_special_price = $request['customer_special_price_'.$a][0];
                                $insertVariation->variation_birthday_price = $request['birthday_special_price_'.$a][0];
                                $insertVariation->variation_weight = $request['weight_'.$a][0];
                                $insertVariation->variation_get_point = $request['get_point_'.$a][0];
                                $insertVariation->variation_costing_price = $request['variation_costing_price_'.$a][0];

                            }
                            $insertVariation->save();

                            if($request->variation_two_enable != 1){
                                if(!empty($request['stock_'.$a][0])){
                                    $variation_stock = new Stock();
                                    $variation_stock->product_id = $product->id;
                                    $variation_stock->variation_id = $insertVariation->id;
                                    $variation_stock->type = "Increase";
                                    $variation_stock->quantity = $request['stock_'.$a][0];
                                    $variation_stock->remark = "Open Stock";
                                    $variation_stock->save();
                                }
                            }

                            if($request->variation_two_enable == 1){

                                for($b=0; $b<count($request['customer_price_'.$a]); $b++){
                                    if(!empty($request['variation_option_two_value_'.$a][$b])){
                                        
                                        $insertSecondVariation = new ProductSecondVariation();
                                        $insertSecondVariation->product_id = $product->id;
                                        $insertSecondVariation->variation_id = $insertVariation->id;
                                        $insertSecondVariation->variation_name = $request['variation_option_two_value_'.$a][$b];
                                        $insertSecondVariation->variation_price = $request['customer_price_'.$a][$b];
                                        $insertSecondVariation->variation_special_price = $request['customer_special_price_'.$a][$b];
                                        $insertSecondVariation->variation_birthday_price = $request['birthday_special_price_'.$a][$b];
                                        $insertSecondVariation->variation_weight = $request['weight_'.$a][$b];
                                        $insertSecondVariation->variation_costing_price = $request['variation_costing_price_'.$a][$b];
                                        $insertSecondVariation->variation_get_point = $request['get_point_'.$a][$b];
                                        $insertSecondVariation->save();

                                        if(!empty($request['stock_'.$a][$b])){

                                            $variation_second_stock = new Stock();
                                            $variation_second_stock->product_id = $product->id;
                                            $variation_second_stock->variation_id = $insertVariation->id;
                                            $variation_second_stock->second_variation_id = $insertSecondVariation->id;
                                            $variation_second_stock->type = "Increase";
                                            $variation_second_stock->quantity = $request['stock_'.$a][$b];
                                            $variation_second_stock->remark = "Open Stock";
                                            $variation_second_stock->save();

                                        }

                                        for($c=0; $c<count($request['agent_level_price_'.$a.'_'.$b]); $c++){

                                            $insert_variation_price = new AgentPrice();
                                            $insert_variation_price->product_id = $product->id;
                                            $insert_variation_price->variation_id = $insertVariation->id;
                                            $insert_variation_price->second_variation_id = $insertSecondVariation->id;
                                            $insert_variation_price->agent_lvl_id = $request['variation_agent_level_'.$a.'_'.$b][$c];
                                            $insert_variation_price->price = $request['agent_level_price_'.$a.'_'.$b][$c];
                                            $insert_variation_price->special_price = $request['agent_level_special_price_'.$a.'_'.$b][$c];
                                            $insert_variation_price->birthday_price = $request['agent_level_birthday_price_'.$a.'_'.$b][$c];
                                            $insert_variation_price->birthday_special_price = $request['agent_level_birthday_special_price_'.$a.'_'.$b][$c];
                                            $insert_variation_price->save();
                                        }
                                    }
                                }
                            }else{
                                if($request->variation_enable == 1){
                                    for($c=0; $c<count($request['variation_agent_level_'.$a.'_0']); $c++){

                                        $insert_variation_price = new AgentPrice();
                                        $insert_variation_price->product_id = $product->id;
                                        $insert_variation_price->variation_id = $insertVariation->id;
                                        $insert_variation_price->agent_lvl_id = $request['variation_agent_level_'.$a.'_0'][$c];
                                        $insert_variation_price->price = $request['agent_level_price_'.$a.'_0'][$c];
                                        $insert_variation_price->special_price = $request['agent_level_special_price_'.$a.'_0'][$c];
                                        $insert_variation_price->birthday_price = $request['agent_level_birthday_price_'.$a.'_0'][$c];
                                        $insert_variation_price->birthday_special_price = $request['agent_level_birthday_special_price_'.$a.'_0'][$c];
                                        $insert_variation_price->save();
                                        
                                    }
                                }
                            }
                        }
                    }
                }
            }

            \DB::commit();
        }catch (\Exception $e){
            \DB::rollback();
            return Redirect::back()->withInput($request->all())->withErrors($e->getMessage()." ".$e->getLine());
        }catch(\Error $e){
            return Redirect::back()->withInput($request->all())->withErrors($e->getMessage()." ".$e->getLine());
        }

        Toastr::success("Product $product->product_name Update Successfully!");

            return redirect()->route('point_mall.point_malls.edit', $id);
        
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

    public function BalanceQuantity($id)
    {
        $stockBalance = Stock::select(DB::raw('SUM(IF(type = "Increase", quantity, NULL)) AS totalStockIn'),
                                      DB::raw('SUM(IF(type = "Decrease", quantity, NULL)) AS totalStockOut'))
                                ->where('product_id', $id)
                                ->whereNull('packages_id')
                                ->first();

        $cart = Cart::select(DB::raw('SUM(qty) AS InCart'))
                    ->where('status', '1')
                    ->where('product_id', $id)
                    ->first();

        $transaction = TransactionDetail::select(DB::raw('SUM(quantity) AS TransCart'))
                                        ->join('transactions AS t', 't.id', 'transaction_details.transaction_id')
                                        ->whereIn('t.status', ['1', '98'])
                                        ->where('product_id', $id)
                                        ->first();

        return $stockBalance->totalStockIn - $stockBalance->totalStockOut - $transaction->TransCart;
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
    
}
