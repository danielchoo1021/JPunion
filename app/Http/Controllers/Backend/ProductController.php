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
use App\PackageItem;
use App\ProductVariation;
use App\ProductVariationStock;
use App\VariationTitle;
use App\ProductSecondVariation;
use App\AgentLevel;
use App\AgentPrice;
use App\Promotion;
use App\PromoAgentPrice;
use App\PromoAgentItem;
use App\PromoAgentItemDetail;
use App\PromoItemTitle;
use App\SoldQuantityAdjustment;

use App\Http\Controllers\GlobalController;

use Validator, Redirect, Toastr, DB, File, Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function index()
    {
        $product = Product::where('status', '!=', '3')
                          ->whereNull('packages');

        if(Auth::guard('merchant')->check()){
        $product = $product->where('products.merchant_id', Auth::user()->code);
        }

        $queries = [];
        $columns = [
            'product_name', 'product_name_desc', 'product_name_asc', 'product_variation_desc', 'product_variation_asc', 'product_featured_desc', 'product_featured_asc',
            'product_status_desc', 'product_status_asc', 'mall', 'status', 'per_page'
        ];

        $per_page = 10;
        if(!empty(request('per_page'))){
            $per_page = request('per_page');
        }

        foreach($columns as $column){
            if(request()->has($column) && !empty(request($column))){
                if($column == 'per_page'){
                    $product = $product->paginate($per_page);
                }elseif($column == 'product_name_desc'){
                    $product = $product->orderBy('products.product_name', 'desc');
                }elseif($column == 'product_name_asc'){
                    $product = $product->orderBy('products.product_name', 'asc');
                }elseif($column == 'product_variation_desc'){
                    $product = $product->orderBy('products.variation_enable', 'desc');
                }elseif($column == 'product_variation_asc'){
                    $product = $product->orderBy('products.variation_enable', 'asc');
                }elseif($column == 'product_featured_desc'){
                    $product = $product->orderBy('products.featured', 'desc');
                }elseif($column == 'product_featured_asc'){
                    $product = $product->orderBy('products.featured', 'asc');
                }elseif($column == 'product_status_desc'){
                    $product = $product->orderBy('products.status', 'desc');
                }elseif($column == 'product_status_asc'){
                    $product = $product->orderBy('products.status', 'asc');
                }elseif($column == 'mall'){
                    if(request($column) == '1'){
                        $product = $product->whereNotNull('mall');
                    }elseif(request($column) == '2'){
                        $product = $product->whereNull('mall');
                    }
                }elseif($column == 'status'){
                    $products = $product->where('products.status', 'like', "%".request($column)."%");
                }else{
                    $product = $product->where($column, 'like', "%".request($column)."%");
                }

                $queries[$column] = request($column);

            }else{
                $product = $product->orderBy('created_at', 'desc');
            }
        }
       

        if(!empty(request('per_page'))){
            $product = $product->appends($queries);        
        }else{
            $product = $product->paginate($per_page)->appends($queries);
        }
        
        $quantity = [];
        foreach($product as $value){
            $quantity[$value->id] = GlobalController::balance_quantity($value->id);
        }

        return view('backend.products.index', ['products' => $product], compact('quantity'));
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

        $agent_levels = AgentLevel::select('agent_levels.*');
        if(Auth::guard('merchant')->check()){
        $agent_levels = $agent_levels->where('merchant_id', Auth::user()->code);
        }else{
        $agent_levels = $agent_levels->whereNull('merchant_id');
        }
        $agent_levels = $agent_levels->get();

        $translation_data = GlobalController::get_translations();
        return view('backend.products.create', ['categories'=>$categories, 'brands'=>$brands, 'UOMs'=>$UOMs, 'agent_levels'=>$agent_levels, 'translation_data'=>$translation_data]);
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

            //Top Settings
            if(Auth::guard('merchant')->check()){
            $product->merchant_id = Auth::user()->code;
            }

            $product->featured = isset($request->featured) ? '1' : '0';
            $product->dow = isset($request->dow) ? '1' : '0';
            $product->free_shipping = isset($request->free_shipping) ? '1' : '0';
            $product->free_east_shipping = isset($request->free_east_shipping) ? '1' : '0';
            $product->agent_only = isset($request->agent_only) ? '1' : '0';
            $product->customer_only = isset($request->customer_only) ? '1' : '0';
            $product->mall = !empty($request->mall) ? $request->mall : NULL;
            $product->store_stock = isset($request->store_stock) ? '1' : '0';
            $product->display_home_page_product_slider = isset($request->display_home_page_product_slider) ? '1' : '0';

            //Product Details
            $product->item_code = GlobalController::product_item_code($request->category_id);
            $product->product_code = trim($request->product_code);
            $product->product_name = trim($request->product_name);
            $product->product_type = trim($request->product_type);
            $product->category_id = $request->category_id;
            $product->sub_category_id = !empty($request->sub_category_id) ? $request->sub_category_id : '';
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
            $product->retail_price = !empty(preg_replace("/[^0-9\.]/", '', $request->retail_price)) ? preg_replace("/[^0-9\.]/", '', $request->retail_price) : 0;
            $product->retail_special_price = !empty(preg_replace("/[^0-9\.]/", '', $request->retail_special_price)) ? preg_replace("/[^0-9\.]/", '', $request->retail_special_price) : 0;
            $product->price = preg_replace("/[^0-9\.]/", '', $request->price);
            $product->special_price = preg_replace("/[^0-9\.]/", '', $request->special_price);
            $product->weight = preg_replace("/[^0-9\.]/", '', $request->weight);
            $product->costing_price = preg_replace("/[^0-9\.]/", '', $request->costing_price);

            $product->birthday_price = preg_replace("/[^0-9\.]/", '', $request->birthday_price);
            $product->birthday_special_price = preg_replace("/[^0-9\.]/", '', $request->birthday_special_price);

            $product->low_stock_threshold = $request->lsthreshold;
          

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
                                $update_variation->variation_retail_price = !empty($request['retail_price_'.$a][0]) ? $request['retail_price_'.$a][0] : 0;
                                $update_variation->variation_retail_special_price    = !empty($request['retail_special_price_'.$a][0]) ? $request['retail_special_price_'.$a][0] : 0;
                                $update_variation->variation_price = $request['customer_price_'.$a][0];
                                $update_variation->variation_special_price = $request['customer_special_price_'.$a][0];
                                $update_variation->variation_birthday_price = $request['birthday_price_'.$a][0];
                                $update_variation->variation_birthday_special_price = $request['birthday_special_price_'.$a][0];
                                $update_variation->variation_weight = $request['weight_'.$a][0];
                                $update_variation->variation_get_point = !empty($request['get_point_'.$a][0]) ? $request['get_point_'.$a][0] : 0;
                                $update_variation->variation_costing_price = $request['variation_costing_price_'.$a][0];
                            }

                            $update_variation->save();
                        }

                        if($request->variation_two_enable == 1){
                            
                            for($b=0; $b<count($request['customer_price_'.$a]); $b++){
                                if(!empty($request['rid_'.$a][$b])){
                                    $update_second_variation = ProductSecondVariation::find($request['rid_'.$a][$b]);

                                    $update_second_variation->variation_name = $request['variation_option_two_value_'.$a][$b];
                                    $update_second_variation->variation_retail_price = !empty($request['retail_price_'.$a][$b]) ? $request['retail_price_'.$a][$b] : 0;
                                    $update_second_variation->variation_retail_special_price = !empty($request['retail_special_price_'.$a][$b]) ? $request['retail_special_price_'.$a][$b] : 0;
                                    $update_second_variation->variation_price = $request['customer_price_'.$a][$b];
                                    $update_second_variation->variation_special_price = $request['customer_special_price_'.$a][$b];
                                    $update_second_variation->variation_birthday_price = $request['birthday_price_'.$a][$b];
                                    $update_second_variation->variation_birthday_special_price =  $request['birthday_special_price_'.$a][$b];
                                    $update_second_variation->variation_weight = $request['weight_'.$a][$b];
                                    $update_second_variation->variation_get_point = !empty($request['get_point_'.$a][$b]) ? $request['get_point_'.$a][$b] : 0;
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
                                        $insertSecondVariation->variation_retail_price = !empty($request['retail_price_'.$a][$b]) ? $request['retail_price_'.$a][$b] : 0;
                                        $insertSecondVariation->variation_retail_special_price = !empty($request['retail_special_price_'.$a][$b]) ? $request['retail_special_price_'.$a][$b] : 0;
                                        $insertSecondVariation->variation_price = $request['customer_price_'.$a][$b];
                                        $insertSecondVariation->variation_special_price = $request['customer_special_price_'.$a][$b];
                                        $insertSecondVariation->variation_birthday_price = $request['birthday_price_'.$a][$b];
                                        $insertSecondVariation->variation_birthday_special_price = $request['birthday_special_price_'.$a][$b];
                                        $insertSecondVariation->variation_weight = $request['weight_'.$a][$b];
                                        $insertSecondVariation->variation_get_point = !empty($request['get_point_'.$a][$b]) ? $request['get_point_'.$a][$b] : 0;
                                        $insertSecondVariation->variation_costing_price = $request['variation_costing_price_'.$a][$b];

                                        if(!empty($request['variation_option_two_image_'.$a][$b])){
                                            $file = $request['variation_option_two_image_'.$a][$b];
                                            $img_name = $file->getClientOriginalName();
                                            $ext = $file->getClientOriginalExtension();
                                            $file_name = 'uploads/second_variations/'.$product->id.'/'.md5($img_name).'.'.$ext;

                                            $upload = $file->move(GlobalController::get_image_path('uploads/second_variations/').$product->id.'/', $file_name);
                                            $insertSecondVariation->variation_image = $file_name;
                                        }

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
                                $insertVariation->variation_retail_price = !empty($request['retail_price_'.$a][0]) ? $request['retail_price_'.$a][0] : 0;
                                $insertVariation->variation_retail_special_price = !empty($request['retail_special_price_'.$a][0]) ? $request['retail_special_price_'.$a][0] : 0;
                                $insertVariation->variation_price = $request['customer_price_'.$a][0];
                                $insertVariation->variation_special_price = $request['customer_special_price_'.$a][0];
                                $insertVariation->variation_birthday_price = $request['birthday_price_'.$a][0];
                                $insertVariation->variation_birthday_special_price = $request['birthday_special_price_'.$a][0];
                                $insertVariation->variation_weight = $request['weight_'.$a][0];
                                $insertVariation->variation_costing_price = $request['variation_costing_price_'.$a][0];

                            }

                            if(!empty($request['variation_image_'.$a][0])){
                                $file = $request['variation_image_'.$a][0];
                                $img_name = $file->getClientOriginalName();
                                $ext = $file->getClientOriginalExtension();
                                $file_name = 'uploads/variations/'.$product->id.'/'.md5($img_name).'.'.$ext;

                                $upload = $file->move(GlobalController::get_image_path('uploads/variations/').$product->id.'/', $file_name);
                                $insertVariation->variation_image = $file_name;
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
                                        $insertSecondVariation->variation_retail_price = !empty($request['retail_price_'.$a][$b]) ? $request['retail_price_'.$a][$b] : 0;
                                        $insertSecondVariation->variation_retail_special_price = !empty($request['retail_special_price_'.$a][$b]) ? $request['retail_special_price_'.$a][$b] : 0;
                                        $insertSecondVariation->variation_price = $request['customer_price_'.$a][$b];
                                        $insertSecondVariation->variation_special_price = $request['customer_special_price_'.$a][$b];
                                        $insertSecondVariation->variation_birthday_price = $request['birthday_price_'.$a][$b];
                                        $insertSecondVariation->variation_birthday_special_price = $request['birthday_special_price_'.$a][$b];
                                        $insertSecondVariation->variation_weight = $request['weight_'.$a][$b];
                                        $insertSecondVariation->variation_get_point = !empty($request['get_point_'.$a][$b]) ? $request['get_point_'.$a][$b] : 0;
                                        $insertSecondVariation->variation_costing_price = $request['variation_costing_price_'.$a][$b];
                                        
                                        if(!empty($request['variation_option_two_image_'.$a][$b])){
                                            $file = $request['variation_option_two_image_'.$a][$b];
                                            $img_name = $file->getClientOriginalName();
                                            $ext = $file->getClientOriginalExtension();
                                            $file_name = 'uploads/second_variations/'.$product->id.'/'.md5($img_name).'.'.$ext;

                                            $upload = $file->move(GlobalController::get_image_path('uploads/second_variations/').$product->id.'/', $file_name);
                                            $insertSecondVariation->variation_image = $file_name;
                                        }

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

        Toastr::success(($translation_data['backendlang']['backendlang']['product'] ?? 'Product') . ' ' . $product->product_name . ' ' . ($translation_data['backendlang']['backendlang']['Create_Successfully'] ?? 'Create Successfully!'));
        return redirect()->route('product.products.index');
    }

    public function sold_quantity($id)
    {
        $product = Product::find($id);
        if(!isset($product) && empty($product)){
            abort(404);
        }

        $adjustments = SoldQuantityAdjustment::where('product_id', $id)
                                         ->where('status', '1')
                                         ->WhereNull('packages_id')
                                         ->orderBy('created_at', 'desc');

        $itemPerPage = 10;
        if(request()->has('per_page') && !empty(request('per_page'))){
            $itemPerPage = request('per_page');
        }
        $queries = [];
        $columns = [
            'type', 'status'
        ];

        foreach($columns as $column){
            if(request()->has($column) && !empty(request($column))){
                
                if($column == 'status'){
                    $adjustments = $adjustments->where($column, ''.request($column).'');
                }else{
                    $adjustments = $adjustments->where($column, 'like', "%".request($column)."%");
                }

                $queries[$column] = request($column);

            }
        }

        $soldBalance = GlobalController::get_product_sold($id);

        $adjustments = $adjustments->paginate($itemPerPage)->appends($queries);

        $variation_sold = [];
        $variation_second_sold = [];
        foreach($product->get_variations as $variation){
            $variation_sold[$variation->id] = GlobalController::get_product_sold($id, $variation->id);
            foreach($variation->get_second_variations as $second_variation){
                $variation_second_sold[$variation->id][$second_variation->id] = GlobalController::get_product_sold($id, $variation->id, $second_variation->id);
            }
        }

        return view('backend.products.sold_quantity', ['product'=>$product, 'adjustments'=>$adjustments, 'soldBalance'=>$soldBalance],
                                              compact('variation_sold',
                                                      'variation_second_sold'));
    }

    public function submit_sold_quantity(Request $request, $id)
    {
        $translation_data = GlobalController::get_translations();
        $product = Product::find($id);

        $soldBalance = GlobalController::get_product_sold($id);

        try {
            \DB::beginTransaction();

            for($a=0; $a<count($request->quantity); $a++){
                if(!empty($request->quantity[$a])){

                    if($product->variation_enable == 1 && !empty($request->variation_id[$a])){
                        $soldBalance = GlobalController::get_product_sold($id, $request->variation_id[$a]);
                    }

                    if($product->second_variation_enable == 1 && !empty($request->second_variation_id[$a])){
                        $soldBalance = GlobalController::get_product_sold($id, $request->variation_id[$a], $request->second_variation_id[$a]);
                    }
                    echo $soldBalance.' - '.$request->quantity[$a].' - '.$request->type[$a];
                    if($soldBalance < $request->quantity[$a] && $request->type[$a] == 'Decrease'){
                        throw new \Exception('Quantity Exceed! Quantity More Than Balance Quantity.'); 
                        // return Redirect::back()->withInput($request->all())->withErrors('');
                    }

                    $stocks = new SoldQuantityAdjustment();
                    $stocks->product_id = $id;
                    $stocks->variation_id = !empty($request->variation_id[$a]) ? $request->variation_id[$a] : null;
                    $stocks->second_variation_id = !empty($request->second_variation_id[$a]) ? $request->second_variation_id[$a] : null;
                    $stocks->type = $request->type[$a];
                    $stocks->quantity = $request->quantity[$a];
                    $stocks->remark = $request->remark[$a];
                    $stocks->save();
                }
            }
            // exit();
            \DB::commit();
        }catch (\Exception $e){
            \DB::rollback();
            return Redirect::back()->withInput($request->all())->withErrors($e->getMessage());
        }catch(\Error $e){
            return Redirect::back()->withInput($request->all())->withErrors($e->getMessage());
        }

        Toastr::success($translation_data['backendlang']['backendlang']['Create_Successfully'] ?? "Create Successfully!");
        return redirect()->route('sold_quantity', $id);
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
            $checkVariation = Stock::where('product_id',$id)->where('variation_id',$variation->id)->whereNull('second_variation_id')->where('remark','Open Stock')->first();
            
            if(!empty($checkVariation)){
                $variation_stocks[$variation->id] = GlobalController::variation_balance_quantity($variation->id);
            }else{
                $variation_stocks[$variation->id] = 'new';
            }
            
            $s_secnd_variations[$variation->id] = ProductSecondVariation::where('variation_id', $variation->id)->get();
            foreach($s_secnd_variations[$variation->id] as $s_secnd_variation){
                $checkSecondVariation = Stock::where('product_id',$id)->where('variation_id',$variation->id)->where('second_variation_id',$s_secnd_variation->id)->where('remark','Open Stock')->first();

                if(!empty($checkSecondVariation)){
                    $second_variation_stocks[$s_secnd_variation->variation_id][$s_secnd_variation->id] = GlobalController::second_variation_balance_quantity($s_secnd_variation->id);
                }else{
                     $second_variation_stocks[$s_secnd_variation->variation_id][$s_secnd_variation->id] = 'new';
                }
            }
        }

        $agent_levels = AgentLevel::select('agent_levels.*');
        if(Auth::guard('merchant')->check()){
        $agent_levels = $agent_levels->where('merchant_id', Auth::user()->code);
        }else{
        $agent_levels = $agent_levels->whereNull('merchant_id');
        }
        $agent_levels = $agent_levels->get();

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
            $agent_prices[$agent_pricing->agent_lvl_id] = $agent_pricing->price;
            $agent_special_prices[$agent_pricing->agent_lvl_id] = $agent_pricing->special_price;
            $agent_prices_ids[$agent_pricing->agent_lvl_id] = $agent_pricing->id;
            
            $agent_birthday_price[$agent_pricing->agent_lvl_id] = $agent_pricing->birthday_price;
            $agent_birthday_special_price[$agent_pricing->agent_lvl_id] = $agent_pricing->birthday_special_price;
        }
        
        return view('backend.products.edit', ['product'=>$product, 'categories'=>$categories, 'brands'=>$brands, 
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
                                                      'agent_v2_special_prices',
                                                      'agent_birthday_price',
                                                      'agent_birthday_special_price',
                                                      'agent_v2_birthday_price',
                                                      'agent_v2_birthday_special_price',
                                                      'agent_v_birthday_prices',
                                                      'agent_v_birthday_special_prices'));
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
            $product->sub_category_id = !empty($request->sub_category_id) ? $request->sub_category_id : '';
            $product->brand_id = $request->brand_id;
            $product->description = $request->description;
            $product->label = trim($request->label);
            $product->variation_title = trim($request->variation_title);
            $product->second_variation_title = trim($request->variation_two_title);
            $product->second_variation_enable = trim($request->variation_enable) == 0 ? 0 :trim($request->variation_two_enable);
            $product->variation_enable = trim($request->variation_enable);
            $product->get_point = trim($request->get_point);
            $product->level_up = $request->level_up;
            $product->store_stock = isset($request->store_stock) ? '1' : '0';
            $product->display_home_page_product_slider = isset($request->display_home_page_product_slider) ? '1' : '0';

            
            $product->testimonial = $request->testimonial;
            $product->short_description = $request->short_description;

            $product->low_stock_threshold = $request->lsthreshold;
    
            
            //Pricing
            $product->retail_price = !empty(preg_replace("/[^0-9\.]/", '', $request->retail_price)) ? preg_replace("/[^0-9\.]/", '', $request->retail_price) : 0;
            $product->retail_special_price = !empty(preg_replace("/[^0-9\.]/", '', $request->retail_special_price)) ? preg_replace("/[^0-9\.]/", '', $request->retail_special_price) : 0;
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
                                $update_variation->variation_retail_price = !empty($request['retail_price_'.$a][0]) ? $request['retail_price_'.$a][0] : 0;
                                $update_variation->variation_retail_special_price = !empty($request['retail_special_price_'.$a][0]) ? $request['retail_special_price_'.$a][0] : 0;
                                $update_variation->variation_price = $request['customer_price_'.$a][0];
                                $update_variation->variation_special_price = $request['customer_special_price_'.$a][0];
                                $update_variation->variation_birthday_price = $request['birthday_price_'.$a][0];
                                $update_variation->variation_birthday_special_price = $request['birthday_special_price_'.$a][0];
                                $update_variation->variation_weight = $request['weight_'.$a][0];
                                $update_variation->variation_costing_price = $request['variation_costing_price_'.$a][0];
                                $update_variation->variation_get_point = !empty($request['get_point_'.$a][0]) ? $request['get_point_'.$a][0] : 0;
                               
                            }

                            if(!empty($request['variation_image_'.$a][0])){
                                $file = $request['variation_image_'.$a][0];
                                $name = $file->getClientOriginalName();    
                                $ext = $file->getClientOriginalExtension();
                                $file_name = 'uploads/variations/'.$product->id.'/'.md5($name).'.'.$ext;

                                $upload = $file->move(GlobalController::get_image_path('uploads/variations/').$product->id.'/', $file_name);
                                $updateVariationImg = ProductVariation::where('id', $request['fvid'][$a])->update(['variation_image'=>$file_name]);
                            }

                            $update_variation->save();

                            if($request->variation_two_enable != 1){
                                if(!empty($request['stock_'.$a][0])){
                                    $variation_stock = new Stock();
                                    $variation_stock->product_id = $product->id;
                                    $variation_stock->variation_id = $update_variation->id;
                                    $variation_stock->type = "Increase";
                                    $variation_stock->quantity = $request['stock_'.$a][0];
                                    $variation_stock->remark = "Open Stock";
                                    $variation_stock->save();
                                }
                            }
                        }

                        if($request->variation_two_enable == 1){
                            
                            for($b=0; $b<count($request['customer_price_'.$a]); $b++){
                                if(!empty($request['rid_'.$a][$b])){
                                    $update_second_variation = ProductSecondVariation::find($request['rid_'.$a][$b]);

                                    $update_second_variation->variation_name = $request['variation_option_two_value_'.$a][$b];
                                    $update_second_variation->variation_retail_price = !empty($request['retail_price_'.$a][$b]) ? $request['retail_price_'.$a][$b] : 0;
                                    $update_second_variation->variation_retail_special_price = !empty($request['retail_special_price_'.$a][$b]) ? $request['retail_special_price_'.$a][$b] : 0;
                                    $update_second_variation->variation_price = $request['customer_price_'.$a][$b];
                                    $update_second_variation->variation_special_price = $request['customer_special_price_'.$a][$b];
                                    $update_second_variation->variation_birthday_price = $request['birthday_price_'.$a][$b];
                                    $update_second_variation->variation_birthday_special_price = $request['birthday_special_price_'.$a][$b];
                                    $update_second_variation->variation_weight = $request['weight_'.$a][$b];
                                    $update_second_variation->variation_get_point = !empty($request['get_point_'.$a][$b]) ? $request['get_point_'.$a][$b] : 0;
                                    $update_second_variation->variation_costing_price = $request['variation_costing_price_'.$a][$b];

                                    if(!empty($request['variation_option_two_image_'.$a][$b])){
                                        $file = $request['variation_option_two_image_'.$a][$b];
                                        $sec_name = $file->getClientOriginalName();    
                                        $ext = $file->getClientOriginalExtension();
                                        $file_name = 'uploads/second_variations/'.$product->id.'/'.md5($sec_name).'.'.$ext;

                                        $upload = $file->move(GlobalController::get_image_path('uploads/second_variations/').$product->id.'/', $file_name);
                                        $updateVariationImg2 = ProductSecondVariation::where('id', $request['rid_'.$a][$b])->update(['variation_image'=>$file_name]);
                                    }

                                    $update_second_variation->save();

                                    if(!empty($request['stock_'.$a][$b])){
                                        $variation_second_stock = new Stock();
                                        $variation_second_stock->product_id = $product->id;
                                        $variation_second_stock->variation_id = $request['fvid'][$a];
                                        $variation_second_stock->second_variation_id = $update_second_variation->id;
                                        $variation_second_stock->type = "Increase";
                                        $variation_second_stock->quantity = $request['stock_'.$a][$b];
                                        $variation_second_stock->remark = "Open Stock";
                                        $variation_second_stock->save();
                                    }

                                    for($c=0; $c<count($request['agent_level_price_'.$a.'_'.$b]); $c++){
                                        if(!empty($request['variation_agent_level_id_'.$a.'_'.$b][$c])){

                                            // echo $request['agent_level_price_'.$a.'_'.$b][$c].' - '.$request['agent_level_birthday_price_'.$a.'_'.$b][$c];
                                            // echo "<br>";

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
                                        $insertSecondVariation->variation_retail_price = !empty($request['retail_price_'.$a][$b]) ? $request['retail_price_'.$a][$b] : 0;
                                        $insertSecondVariation->variation_retail_special_price = !empty($request['retail_special_price_'.$a][$b]) ? $request['retail_special_price_'.$a][$b] : 0;
                                        $insertSecondVariation->variation_price = $request['customer_price_'.$a][$b];
                                        $insertSecondVariation->variation_special_price = $request['customer_special_price_'.$a][$b];
                                        $insertSecondVariation->variation_birthday_price = $request['birthday_price_'.$a][$b];
                                        $insertSecondVariation->variation_birthday_special_price = $request['birthday_special_price_'.$a][$b];
                                        $insertSecondVariation->variation_weight = $request['weight_'.$a][$b];
                                        $insertSecondVariation->variation_get_point = !empty($request['get_point_'.$a][$b]) ? $request['get_point_'.$a][$b] : 0;

                                        if(!empty($request['variation_option_two_image_'.$a][$b])){
                                            $file = $request['variation_option_two_image_'.$a][$b];
                                            $sec_name = $file->getClientOriginalName();    
                                            $ext = $file->getClientOriginalExtension();
                                            $file_name = 'uploads/second_variations/'.$product->id.'/'.md5($sec_name).'.'.$ext;

                                            $upload = $file->move(GlobalController::get_image_path('uploads/second_variations/').$product->id.'/', $file_name);
                                            $insertSecondVariation->variation_image = $file_name;
                                        }

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
                                $insertVariation->variation_retail_price = !empty($request['retail_price_'.$a][0]) ? $request['retail_price_'.$a][0] : 0;
                                $insertVariation->variation_retail_special_price = !empty($request['retail_special_price_'.$a][0]) ? $request['retail_special_price_'.$a][0] : 0;
                                $insertVariation->variation_price = $request['customer_price_'.$a][0];
                                $insertVariation->variation_special_price = $request['customer_special_price_'.$a][0];
                                $insertVariation->variation_birthday_price = $request['birthday_price_'.$a][0];
                                $insertVariation->variation_birthday_special_price = $request['birthday_special_price_'.$a][0];
                                $insertVariation->variation_weight = $request['weight_'.$a][0];
                                $insertVariation->variation_get_point = !empty($request['get_point_'.$a][0]) ? $request['get_point_'.$a][0] : 0;
                                $insertVariation->variation_costing_price = $request['variation_costing_price_'.$a][0];

                            }

                            if(!empty($request['variation_image_'.$a][0])){
                                $file = $request['variation_image_'.$a][0];
                                $img_name = $file->getClientOriginalName();
                                $ext = $file->getClientOriginalExtension();
                                $file_name = 'uploads/variations/'.$product->id.'/'.md5($img_name).'.'.$ext;
                                
                                $upload = $file->move(GlobalController::get_image_path('uploads/variations/').$product->id.'/', $file_name);
                                $insertVariation->variation_image = $file_name;
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
                                        $insertSecondVariation->variation_retail_price = !empty($request['retail_price_'.$a][$b]) ? $request['retail_price_'.$a][$b] : 0;
                                        $insertSecondVariation->variation_retail_special_price = !empty($request['retail_special_price_'.$a][$b]) ? $request['retail_special_price_'.$a][$b] : 0;
                                        $insertSecondVariation->variation_price = $request['customer_price_'.$a][$b];
                                        $insertSecondVariation->variation_special_price = $request['customer_special_price_'.$a][$b];
                                        $insertSecondVariation->variation_birthday_price = $request['birthday_price_'.$a][$b];
                                        $insertSecondVariation->variation_birthday_special_price = $request['birthday_special_price_'.$a][$b];
                                        $insertSecondVariation->variation_weight = $request['weight_'.$a][$b];
                                        $insertSecondVariation->variation_costing_price = $request['variation_costing_price_'.$a][$b];
                                        $insertSecondVariation->variation_get_point = !empty($request['get_point_'.$a][$b]) ? $request['get_point_'.$a][$b] : 0;
                                        
                                        if(!empty($request['variation_option_two_image_'.$a][$b])){
                                            $file = $request['variation_option_two_image_'.$a][$b];
                                            $img_name = $file->getClientOriginalName();
                                            $ext = $file->getClientOriginalExtension();
                                            $file_name = 'uploads/second_variations/'.$product->id.'/'.md5($img_name).'.'.$ext;

                                            $upload = $file->move(GlobalController::get_image_path('uploads/second_variations/').$product->id.'/', $file_name);
                                            $insertSecondVariation->variation_image = $file_name;
                                        }

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

                // exit();
            }

            \DB::commit();
        }catch (\Exception $e){
            \DB::rollback();
            return Redirect::back()->withInput($request->all())->withErrors($e->getMessage()." ".$e->getLine());
        }catch(\Error $e){
            return Redirect::back()->withInput($request->all())->withErrors($e->getMessage()." ".$e->getLine());
        }

        Toastr::success(($translation_data['backendlang']['backendlang']['product'] ?? 'Product') . ' ' . $product->product_name . ' ' . ($translation_data['backendlang']['backendlang']['Update_Successful'] ?? 'Update Successful!'));
        if(!empty($request->mall)){
            return redirect()->route('point_product_edit', $id);
        }else{
            return redirect()->route('product.products.edit', $id);
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

    public function stock($id)
    {
        $translation_data = GlobalController::get_translations();
        $product = Product::find($id);
        if(!isset($product) && empty($product)){
            abort(404);
        }

        if($product->variation_enable == 1 && $product->second_variation_enable == 0){
          $stocks = Stock::select('stocks.*')->where('stocks.product_id', $id)
                    ->join('product_variations as p', 'p.id', 'stocks.variation_id')
                    ->where('stocks.status', '1')
                    ->WhereNull('stocks.packages_id')
                    ->whereNotNull('stocks.variation_id')
                    ->whereNull('stocks.second_variation_id')
                    ->orderBy('stocks.created_at', 'desc');
        }else if($product->variation_enable == 1 && $product->second_variation_enable == 1){
                $stocks = Stock::select('stocks.*')->where('stocks.product_id', $id)
                    ->join('product_second_variations as p', 'p.id', 'stocks.second_variation_id')
                    ->where('stocks.status', '1')
                    ->WhereNull('stocks.packages_id')
                    ->whereNotNull('stocks.variation_id')
                    ->whereNotNull('stocks.second_variation_id')
                    ->orderBy('stocks.created_at', 'desc');
        }else{
            $stocks = Stock::where('product_id', $id)
                    ->where('status', '1')
                    ->WhereNull('packages_id')
                    ->whereNull('variation_id')
                    ->whereNull('second_variation_id')
                    ->orderBy('created_at', direction: 'desc');
        }


        $itemPerPage = 10;
        if(request()->has('per_page') && !empty(request('per_page'))){
            $itemPerPage = request('per_page');
        }
        $queries = [];
        $columns = [
            'type', 'status'
        ];

        foreach($columns as $column){
            if(request()->has($column) && !empty(request($column))){
                
                if($column == 'status'){
                    $stocks = $stocks->where($column, ''.request($column).'');
                }else{
                    $stocks = $stocks->where($column, 'like', "%".request($column)."%");
                }

                $queries[$column] = request($column);

            }
        }

        $stockBalance = GlobalController::balance_quantity($id);

        $stocks = $stocks->paginate($itemPerPage)->appends($queries);

        foreach($stocks as $stockItem){
            if($stockItem->type === 'Increase'){
                $stockItem->type = isset($translation_data['backendlang']['backendlang']['Increase']) ? $translation_data['backendlang']['backendlang']['Increase'] : $stockItem->type;
            }elseif($stockItem->type === 'Decrease'){
                $stockItem->type = isset($translation_data['backendlang']['backendlang']['Decrease']) ? $translation_data['backendlang']['backendlang']['Decrease'] : $stockItem->type;
            }

            if($stockItem->remark === 'Open Stock'){
                $stockItem->remark = isset($translation_data['backendlang']['backendlang']['Open_Stock']) ? $translation_data['backendlang']['backendlang']['Open_Stock'] : $stockItem->remark;
            }
        }

        $variation_stocks = [];
        $variation_second_stocks = [];
        foreach($product->get_variations as $variation){
            $variation_stocks[$variation->id] = GlobalController::variation_balance_quantity($variation->id);
            foreach($variation->get_second_variations as $second_variation){
                $variation_second_stocks[$variation->id][$second_variation->id] = GlobalController::second_variation_balance_quantity($second_variation->id);
            }
        }

        return view('backend.products.stock', ['product'=>$product, 'stocks'=>$stocks, 'translation_data'=>$translation_data, 'stockBalance'=>$stockBalance],
                                              compact('variation_stocks',
                                                      'variation_second_stocks'));
    }

    public function Submitstock(Request $request, $id)
    {
        $translation_data = GlobalController::get_translations();
     
        $product = Product::find($id);

        $stockBalance = GlobalController::balance_quantity($id);

        try {
            \DB::beginTransaction();

            for($a=0; $a<count($request->quantity); $a++){
                if(!empty($request->quantity[$a])){

                    if($product->variation_enable == 1 && !empty($request->variation_id[$a])){
                        $stockBalance = GlobalController::variation_balance_quantity($request->variation_id[$a]);
                    }

                    if($product->second_variation_enable == 1 && !empty($request->second_variation_id[$a])){
                        $stockBalance = GlobalController::second_variation_balance_quantity($request->second_variation_id[$a]);
                    }
                    // Remove debug output; keep server-side clean
                    if($stockBalance < $request->quantity[$a] && $request->type[$a] == 'Decrease'){
                        throw new \Exception('Quantity Exceed! Quantity More Than Balance Quantity.'); 
                        // return Redirect::back()->withInput($request->all())->withErrors('');
                    }

                    $stocks = new Stock();
                    $stocks->product_id = $id;
                    $stocks->variation_id = !empty($request->variation_id[$a]) ? $request->variation_id[$a] : null;
                    $stocks->second_variation_id = !empty($request->second_variation_id[$a]) ? $request->second_variation_id[$a] : null;
                    // Store canonical type values for balance calculations
                    $stocks->type = $request->type[$a];
                    $stocks->quantity = $request->quantity[$a];
                    // Keep remark as provided; translate only when rendering
                    $stocks->remark = $request->remark[$a];
                    $stocks->save();
                }
            }
            // exit();
            \DB::commit();
        }catch (\Exception $e){
            \DB::rollback();
            return Redirect::back()->withInput($request->all())->withErrors($e->getMessage());
        }catch(\Error $e){
            return Redirect::back()->withInput($request->all())->withErrors($e->getMessage());
        }

        Toastr::success($translation_data['backendlang']['backendlang']['Create_Successfully'] ?? "Create Successfully!");
        return redirect()->route('stock', $id);
    }

    // public function packages_list()
    // {
    //     $products = Product::where('packages', '1')->where('status', '!=', '3');
    //                 // ->orderBy('item_code', 'asc');
     
    //     if(Auth::guard('merchant')->check()){
    //     $products = $products->where('products.merchant_id', Auth::user()->code);
    //     }
    //     $queries = [];
    //     $columns = [
    //         'product_name', 'product_name_desc', 'product_name_asc', 'product_price_desc', 'product_price_asc', 'product_status_desc', 'product_status_asc', 'product_code_desc', 'product_code_asc', 'status'
    //     ];
    //     foreach($columns as $column){
    //         if(request()->has($column) && !empty(request($column))){
    //             if($column == 'product_name_desc'){
    //                 $products = $products->orderBy('products.product_name', 'desc');
    //             }elseif($column == 'product_name_asc'){
    //                 $products = $products->orderBy('products.product_name', 'asc');
    //             }elseif($column == 'product_price_desc'){
    //                 $products = $products->orderBy('products.agent_price', 'desc');
    //             }elseif($column == 'product_price_asc'){
    //                 $products = $products->orderBy('products.agent_price', 'asc');
    //             }elseif($column == 'product_status_desc'){
    //                 $products = $products->orderBy('products.status', 'desc');
    //             }elseif($column == 'product_status_asc'){
    //                 $products = $products->orderBy('products.status', 'asc');
    //             }elseif($column == 'product_code_desc'){
    //                 $products = $products->orderBy('products.item_code', 'desc');
    //             }elseif($column == 'product_code_asc'){
    //                 $products = $products->orderBy('products.item_code', 'asc');
    //             }else{
    //                 $products = $products->where($column, 'like', "%".request($column)."%");
    //             }

    //             $queries[$column] = request($column);

    //         }
    //     }
    //     $per_page = 10;
    //     if(!empty(request('per_page'))){
    //         $per_page = request('per_page');
    //     }
    //     $products = $products->orderBy('item_code', 'asc');
    //     $products = $products->paginate($per_page)->appends($queries);

    //     return view('backend.products.packages_list', ['products'=>$products]);
    // }
    public function packages_list()
    {

        $products = Product::where('packages', '1')->where('status', '!=', '3');
                    // ->orderBy('item_code', 'asc');

        if(Auth::guard('merchant')->check()){
            $products = $products->where('products.merchant_id', Auth::user()->code);
        }
        
        if(empty(request('product_status_desc')) &&
           empty(request('product_status_asc')) &&
           empty(request('product_price_desc')) &&
           empty(request('product_price_asc')) &&
           empty(request('product_code_desc')) &&
           empty(request('product_code_asc')) &&
           empty(request('product_name_desc')) &&
           empty(request('product_name_asc'))){
            $products = $products->orderBy('created_at', 'desc');
        }

        $per_page = 10;
        if(!empty(request('per_page'))){
            $per_page = request('per_page');
        }

        $queries = [];
        $columns = [
            'product_name', 'product_name_desc', 'product_name_asc', 'product_price_desc', 'product_price_asc', 'product_status_desc', 'product_status_asc', 'product_code_desc', 'product_code_asc', 'status','per_page'
        ];
        foreach($columns as $column){
            if(request()->has($column) && !empty(request($column))){
                if($column == 'product_name_desc'){
                    $products = $products->orderBy('products.product_name', 'desc');
                }elseif($column == 'product_name_asc'){
                    $products = $products->orderBy('products.product_name', 'asc');
                }elseif($column == 'product_price_desc'){
                    $products = $products->orderBy(DB::raw('COALESCE(products.special_price, products.price)'), 'desc');
                }elseif($column == 'product_price_asc'){
                    $products = $products->orderBy(DB::raw('COALESCE(products.special_price, products.price)'), 'asc');
                }elseif($column == 'product_status_desc'){
                    $products = $products->orderBy('products.status', 'desc');
                }elseif($column == 'product_status_asc'){
                    $products = $products->orderBy('products.status', 'asc');
                }elseif($column == 'product_code_desc'){
                    $products = $products->orderBy('products.item_code', 'desc');
                }elseif($column == 'product_code_asc'){
                    $products = $products->orderBy('products.item_code', 'asc');
                }elseif($column == 'per_page'){
                    $products = $products->paginate($per_page);
                }else{
                    $products = $products->where($column, 'like', "%".request($column)."%");
                }

                $queries[$column] = request($column);

            }
        }

        if(!empty(request('per_page'))){
            $products = $products->appends($queries);        
        }else{
            $products = $products->paginate($per_page)->appends($queries);
        }


        return view('backend.products.packages_list', ['products'=>$products]);
    }

    public function packages_add()
    {   
        $products = Product::where('status', 1);
        if(Auth::guard('merchant')->check()){
        $products = $products->where('merchant_id', Auth::user()->code);
        }
        $products = $products->get();

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

        $agent_levels = AgentLevel::select('agent_levels.*');
        if(Auth::guard('merchant')->check()){
        $agent_levels = $agent_levels->where('merchant_id', Auth::user()->code);
        }else{
        $agent_levels = $agent_levels->whereNull('merchant_id');
        }
        $agent_levels = $agent_levels->get();
        $vouchers = Promotion::get();

        return view('backend.products.packages', ['products'=>$products, 'agent_levels'=>$agent_levels,
                                                  'brands'=>$brands, 'UOMs'=>$UOMs, 'categories'=>$categories,
                                                  'vouchers'=>$vouchers]);
    }

    public function packages_add_save(Request $request)
    {
        $translation_data = GlobalController::get_translations();
        $validator = Validator::make($request->all(), [
            'product_name' => 'required',
            'weight' => 'required',
            'category_id' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }

        try{
            \DB::beginTransaction();
            
            $countpkg = Product::select(DB::raw('COUNT(id) AS totalPkg'))
                               ->where('packages', '1')
                               ->first();
            $itemcode = $countpkg->totalPkg+1;

            if(strlen($itemcode) == 1){
                $itemcode = "00".$itemcode;
            }elseif(strlen($itemcode) == 1){
                $itemcode = "0".$itemcode;
            }else{
                $itemcode = $itemcode;
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
            $product->register_product = isset($request->register_product) ? '1' : '0';
            $product->level_up = $request->level_up;
            $product->store_stock = isset($request->store_stock) ? '1' : '0';
            $product->display_home_page_product_slider = isset($request->display_home_page_product_slider) ? '1' : '0';
            $product->packages = 1;

            //Product Details
            $product->item_code = GlobalController::product_item_code($request->category_id);
            $product->product_code = trim($request->product_code);
            $product->product_name = trim($request->product_name);
            $product->product_type = trim($request->product_type);
            $product->category_id = $request->category_id;
            $product->sub_category_id = !empty($request->sub_category_id) ? $request->sub_category_id : '';
            $product->brand_id = $request->brand_id;
            $product->description = $request->description;
            // $product->free_gift = $request->free_gift_description;
            $product->testimonial = $request->testimonial;
            $product->label = trim($request->label);
            $product->variation_title = trim($request->variation_title);
            $product->second_variation_title = trim($request->variation_two_title);
            $product->second_variation_enable = trim($request->variation_two_enable);
            $product->variation_enable = trim($request->variation_enable);
            $product->birthday_price = preg_replace("/[^0-9\.]/", '', $request->birthday_price);
            $product->birthday_special_price = preg_replace("/[^0-9\.]/", '', $request->birthday_special_price);
            $product->get_point = trim($request->get_point);
            
            $product->testimonial = $request->testimonial;
            $product->short_description = $request->short_description;
            
            //Pricing
            $product->retail_price = !empty(preg_replace("/[^0-9\.]/", '', $request->retail_price)) ? preg_replace("/[^0-9\.]/", '', $request->retail_price) : 0;
            $product->retail_special_price = !empty(preg_replace("/[^0-9\.]/", '', $request->retail_special_price)) ? preg_replace("/[^0-9\.]/", '', $request->retail_special_price) : 0;
            $product->price = preg_replace("/[^0-9\.]/", '', $request->price);
            $product->special_price = preg_replace("/[^0-9\.]/", '', $request->special_price);
            $product->weight = preg_replace("/[^0-9\.]/", '', $request->weight);
            $product->costing_price = preg_replace("/[^0-9\.]/", '', $request->costing_price);;

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
                if(!empty($request->every_agent_price)){
                $agent_price->save();
                }
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

            $insert = [];
            for($a=0; $a<count($request->products); $a++){
                if(!empty($request->products[$a])){
                    $package_item = new PackageItem();
                    $package_item->product_id = $product->id;
                    $package_item->products = $request->products[$a];
                    $package_item->variation_id = !empty($request['variation_option'.$a]) ? $request['variation_option'.$a] : '';
                    $package_item->second_variation_id = !empty($request['second_variation_option'.$a]) ? $request['second_variation_option'.$a] : '';
                    $package_item->qty = $request->qty[$a];
                    $package_item->unit_price = $request->unit_price[$a];
                    $package_item->save();
                }
            }

            for($b=0; $b<count($request->vouchers); $b++){
                if(!empty($request->vouchers[$b])){
                    if(!empty($request->vpid[$b])){
                        $vouchers = PackageItem::find($request->vpid[$b]);
                        $vouchers->voucher_id = $request->vouchers[$b];
                        $vouchers->qty = $request->voucher_qty[$b];
                        $vouchers->save();
                    }else{
                        $vouchers = new PackageItem();
                        $vouchers->product_id = $product->id;
                        $vouchers->voucher_id = $request->vouchers[$b];
                        $vouchers->qty = $request->voucher_qty[$b];
                        $vouchers->save();
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

        Toastr::success($translation_data['backendlang']['backendlang']['Packages_Created_Successfully'] ?? "Packages Created Successfully!");
        return redirect()->route('packages_list');
    }

    public function packages_edit($id)
    {   
        $product = Product::find($id);
        $products = Product::where('status', 1);
        if(Auth::guard('merchant')->check()){
        $products = $products->where('merchant_id', Auth::user()->code);
        }
        $products = $products->get();

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
        $packages = PackageItem::where('product_id', $id)->whereNull('voucher_id')->get();
        $packages_vouchers = PackageItem::where('product_id', $id)->whereNotNull('voucher_id')->get();

        $options = [];
        foreach($packages as $package){
        	$options[$package->products] = ProductVariation::where('product_id', $package->products)
        												   ->get();
        }

        $second_options = [];
        foreach($packages as $package){
        	$second_options[$package->variation_id] = ProductSecondVariation::where('variation_id', $package->variation_id)
        												   ->get();
        }

        // $products = Product::where('status', 1)->get();

        $agent_levels = AgentLevel::select('agent_levels.*');
        if(Auth::guard('merchant')->check()){
        $agent_levels = $agent_levels->where('merchant_id', Auth::user()->code);
        }else{
        $agent_levels = $agent_levels->whereNull('merchant_id');
        }
        $agent_levels = $agent_levels->get();
        
        $sub_categories = SubCategory::where('category_id', $product->category_id)->get();

        $stockBalance = GlobalController::balance_quantity($id);

        $agent_pricings = AgentPrice::where('product_id', $id)->get();

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
        foreach($agent_second_variation_pricings as $agent_sec_pricing){
            $agent_v2_prices[$agent_sec_pricing->agent_lvl_id][$agent_sec_pricing->variation_id][$agent_sec_pricing->second_variation_id] = $agent_sec_pricing->price;
            $agent_v2_special_prices[$agent_sec_pricing->agent_lvl_id][$agent_sec_pricing->variation_id][$agent_sec_pricing->second_variation_id] = $agent_sec_pricing->special_price;
            $agent_prices_v2_ids[$agent_sec_pricing->agent_lvl_id][$agent_sec_pricing->variation_id][$agent_sec_pricing->second_variation_id] = $agent_sec_pricing->id;

            $agent_v2_prices[$agent_sec_pricing->agent_lvl_id][$agent_sec_pricing->variation_id][$agent_sec_pricing->second_variation_id] = $agent_sec_pricing->birthday_price;
            $agent_v2_special_prices[$agent_sec_pricing->agent_lvl_id][$agent_sec_pricing->variation_id][$agent_sec_pricing->second_variation_id] = $agent_sec_pricing->birthday_special_price;
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

            //     $agent_v2_prices[$agent_pricing->agent_lvl_id][$agent_pricing->variation_id][$agent_pricing->second_variation_id] = $agent_pricing->birthday_price;
            //     $agent_v2_special_prices[$agent_pricing->agent_lvl_id][$agent_pricing->variation_id][$agent_pricing->second_variation_id] = $agent_pricing->birthday_special_price;
            // }elseif($product->variation_enable == 1 && empty($product->second_variation_enable)){
            //     $agent_v_prices[$agent_pricing->agent_lvl_id][$agent_pricing->variation_id] = $agent_pricing->price;
            //     $agent_v_special_prices[$agent_pricing->agent_lvl_id][$agent_pricing->variation_id] = $agent_pricing->special_price;
            //     $agent_prices_v_ids[$agent_pricing->agent_lvl_id][$agent_pricing->variation_id] = $agent_pricing->id;

            //     $agent_v_birthday_prices[$agent_pricing->agent_lvl_id][$agent_pricing->variation_id] = $agent_pricing->birthday_price;
            //     $agent_v_birthday_special_prices[$agent_pricing->agent_lvl_id][$agent_pricing->variation_id] = $agent_pricing->birthday_special_price;
            // }else{
                $agent_prices[$agent_pricing->agent_lvl_id] = $agent_pricing->price;
                $agent_special_prices[$agent_pricing->agent_lvl_id] = $agent_pricing->special_price;
                $agent_prices_ids[$agent_pricing->agent_lvl_id] = $agent_pricing->id;

                $agent_birthday_price[$agent_pricing->agent_lvl_id] = $agent_pricing->birthday_price;
                $agent_birthday_special_price[$agent_pricing->agent_lvl_id] = $agent_pricing->birthday_special_price;
            // }
        }

        $vouchers = Promotion::where('status' , '1')->get();

        return view('backend.products.packages_edit', ['product'=>$product, 'products'=>$products, 'packages'=>$packages,
                                                       'agent_levels'=>$agent_levels,
                                                       'brands'=>$brands, 'UOMs'=>$UOMs,
                                                       'categories'=>$categories, 'sub_categories'=>$sub_categories,
                                                       'stockBalance'=>$stockBalance,
                                                       'vouchers'=>$vouchers,
                                                       'packages_vouchers'=>$packages_vouchers],
    												  compact('options', 'second_options',
                                                              'agent_prices', 'agent_prices_ids',
                                                              'agent_v_prices', 'agent_prices_v_ids',
                                                              'agent_v2_prices', 'agent_prices_v2_ids',
                                                              'agent_special_prices',
                                                              'agent_v_special_prices',
                                                              'agent_v2_special_prices',
                                                              'agent_birthday_price',
                                                              'agent_birthday_special_price'));
    }


    public function packages_edit_save(Request $request, $id)
    {
        $translation_data = GlobalController::get_translations();
        $validator = Validator::make($request->all(), [
            'product_name' => 'required',
            'weight' => 'required',
            'category_id' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }

        try{
            \DB::beginTransaction();
            
            $countpkg = Product::select(DB::raw('COUNT(id) AS totalPkg'))
                               ->where('packages', '1')
                               ->first();
            $itemcode = $countpkg->totalPkg+1;

            if(strlen($itemcode) == 1){
                $itemcode = "00".$itemcode;
            }elseif(strlen($itemcode) == 1){
                $itemcode = "0".$itemcode;
            }else{
                $itemcode = $itemcode;
            }

            $product = Product::find($id);
            //Top Settings
            $product->featured = isset($request->featured) ? '1' : '0';
            $product->dow = isset($request->dow) ? '1' : '0';
            $product->free_shipping = isset($request->free_shipping) ? '1' : '0';
            $product->free_east_shipping = isset($request->free_east_shipping) ? '1' : '0';
            $product->agent_only = isset($request->agent_only) ? '1' : '0';
            $product->customer_only = isset($request->customer_only) ? '1' : '0';
            $product->register_product = isset($request->register_product) ? '1' : '0';
            $product->level_up = $request->level_up;
            $product->store_stock = isset($request->store_stock) ? '1' : '0';
            $product->display_home_page_product_slider = isset($request->display_home_page_product_slider) ? '1' : '0';
            $product->packages = 1;

            //Product Details
            $product->item_code = GlobalController::product_item_code($request->category_id);
            $product->product_code = trim($request->product_code);
            $product->product_name = trim($request->product_name);
            $product->product_type = trim($request->product_type);
            $product->category_id = $request->category_id;
            $product->sub_category_id = !empty($request->sub_category_id) ? $request->sub_category_id : '';
            $product->brand_id = $request->brand_id;
            $product->description = $request->description;
            // $product->free_gift = $request->free_gift_description;
            $product->testimonial = $request->testimonial;
            $product->label = trim($request->label);
            $product->variation_title = trim($request->variation_title);
            $product->second_variation_title = trim($request->variation_two_title);
            $product->second_variation_enable = trim($request->variation_two_enable);
            $product->variation_enable = trim($request->variation_enable);
            $product->birthday_price = preg_replace("/[^0-9\.]/", '', $request->birthday_price);
            $product->birthday_special_price = preg_replace("/[^0-9\.]/", '', $request->birthday_special_price);
            $product->get_point = trim($request->get_point);
            
            $product->testimonial = $request->testimonial;
            $product->short_description = $request->short_description;
            
            //Pricing
            $product->retail_price = !empty(preg_replace("/[^0-9\.]/", '', $request->retail_price)) ? preg_replace("/[^0-9\.]/", '', $request->retail_price) : 0;
            $product->retail_special_price = !empty(preg_replace("/[^0-9\.]/", '', $request->retail_special_price)) ? preg_replace("/[^0-9\.]/", '', $request->retail_special_price) : 0;
            $product->price = preg_replace("/[^0-9\.]/", '', $request->price);
            $product->special_price = preg_replace("/[^0-9\.]/", '', $request->special_price);
            $product->weight = preg_replace("/[^0-9\.]/", '', $request->weight);
            $product->costing_price = preg_replace("/[^0-9\.]/", '', $request->costing_price);;

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
                if(!empty($request->every_agent_price)){
                $agent_price->save();
                }
            }

            $insert = [];
            for($a=0; $a<count($request->products); $a++){
                if(!empty($request->products[$a])){

                    if(!empty($request->pid[$a])){
                        $package_item = PackageItem::find($request->pid[$a]);
                    }else{
                        $package_item = new PackageItem();
                    }
                    $package_item->product_id = $product->id;
                    $package_item->products = $request->products[$a];
                    $package_item->variation_id = !empty($request['variation_option'.$a]) ? $request['variation_option'.$a] : '';
                    $package_item->second_variation_id = !empty($request['second_variation_option'.$a]) ? $request['second_variation_option'.$a] : '';
                    $package_item->qty = $request->qty[$a];
                    $package_item->unit_price = $request->unit_price[$a];
                    $package_item->save();
                }
            }

            for($b=0; $b<count($request->vouchers); $b++){
                if(!empty($request->vouchers[$b])){
                    if(!empty($request->vpid[$b])){
                        $vouchers = PackageItem::find($request->vpid[$b]);
                        $vouchers->voucher_id = $request->vouchers[$b];
                        $vouchers->qty = $request->voucher_qty[$b];
                        $vouchers->save();
                    }else{
                        $vouchers = new PackageItem();
                        $vouchers->product_id = $product->id;
                        $vouchers->voucher_id = $request->vouchers[$b];
                        $vouchers->qty = $request->voucher_qty[$b];
                        $vouchers->save();
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


        Toastr::success($translation_data['backendlang']['backendlang']['Packages_Updated_Successfully'] ?? "Packages Updated Successfully!");
        return redirect()->route('packages_edit', $id);
    }

    public function point_product_add()
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
        $agent_levels = AgentLevel::select('agent_levels.*');
        if(Auth::guard('merchant')->check()){
        $agent_levels = $agent_levels->where('merchant_id', Auth::user()->code);
        }else{
        $agent_levels = $agent_levels->whereNull('merchant_id');
        }
        $agent_levels = $agent_levels->get();
        
        $promotions = Promotion::where('status', '1')
                               ->get();
        $mall = '1';

        $translation_data = GlobalController::get_translations();
        return view('backend.products.create', ['categories'=>$categories, 
                                                              'brands'=>$brands, 
                                                              'UOMs'=>$UOMs,
                                                              'agent_levels'=>$agent_levels,
                                                              'mall'=>$mall,
                                                              'promotions'=>$promotions,
                                                              'translation_data'=>$translation_data]);
    }

    public function point_product_edit($id)
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

        $agent_levels = AgentLevel::select('agent_levels.*');
        if(Auth::guard('merchant')->check()){
        $agent_levels = $agent_levels->where('merchant_id', Auth::user()->code);
        }else{
        $agent_levels = $agent_levels->whereNull('merchant_id');
        }
        $agent_levels = $agent_levels->get();

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

            //     $agent_v2_prices[$agent_pricing->agent_lvl_id][$agent_pricing->variation_id][$agent_pricing->second_variation_id] = $agent_pricing->birthday_price;
            //     $agent_v2_special_prices[$agent_pricing->agent_lvl_id][$agent_pricing->variation_id][$agent_pricing->second_variation_id] = $agent_pricing->birthday_special_price;
            // }elseif($product->variation_enable == 1 && empty($product->second_variation_enable)){
            //     $agent_v_prices[$agent_pricing->agent_lvl_id][$agent_pricing->variation_id] = $agent_pricing->price;
            //     $agent_v_special_prices[$agent_pricing->agent_lvl_id][$agent_pricing->variation_id] = $agent_pricing->special_price;
            //     $agent_prices_v_ids[$agent_pricing->agent_lvl_id][$agent_pricing->variation_id] = $agent_pricing->id;

            //     $agent_v_birthday_prices[$agent_pricing->agent_lvl_id][$agent_pricing->variation_id] = $agent_pricing->birthday_price;
            //     $agent_v_birthday_special_prices[$agent_pricing->agent_lvl_id][$agent_pricing->variation_id] = $agent_pricing->birthday_special_price;
            // }else{
                $agent_prices[$agent_pricing->agent_lvl_id] = $agent_pricing->price;
                $agent_special_prices[$agent_pricing->agent_lvl_id] = $agent_pricing->special_price;
                $agent_prices_ids[$agent_pricing->agent_lvl_id] = $agent_pricing->id;

                $agent_birthday_price[$agent_pricing->agent_lvl_id] = $agent_pricing->birthday_price;
                $agent_birthday_special_price[$agent_pricing->agent_lvl_id] = $agent_pricing->birthday_special_price;
            // }
        }

        $mall = '1';

        return view('backend.products.edit', ['product'=>$product, 'categories'=>$categories, 'brands'=>$brands, 
                                              'sub_categories'=>$sub_categories, 'code'=>$code,
                                              'stockBalance'=>$stockBalance, 'UOMs'=>$UOMs, 'variations'=>$variations,
                                              'secnd_variations'=>$secnd_variations, 'svs'=>$svs,
                                              'agent_levels'=>$agent_levels,
                                              'mall'=>$mall],
                                              compact('variation_stocks',
                                                      'second_variation_stocks',
                                                      's_secnd_variations',
                                                      'agent_prices', 'agent_prices_ids',
                                                      'agent_v_prices', 'agent_prices_v_ids',
                                                      'agent_v2_prices', 'agent_prices_v2_ids',
                                                      'agent_special_prices',
                                                      'agent_v_special_prices',
                                                      'agent_v2_special_prices',
                                                      'agent_birthday_price',
                                                      'agent_birthday_special_price',
                                                      'agent_v2_birthday_price',
                                                      'agent_v2_birthday_special_price',
                                                      'agent_v_birthday_prices',
                                                      'agent_v_birthday_special_prices'));
    }

    public function point_product_add_save(Request $request)
    {
        $translation_data = GlobalController::get_translations();
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
            
            $translation_data = GlobalController::get_translations();

            $totalCount = $product->TotalCount+1;

            if(strlen($totalCount) == 1){
                $code = "00".$totalCount;
            }elseif(strlen($totalCount) == 2){
                $code = "0".$totalCount;
            }else{
                $code = $totalCount;
            }

            $product = new Product();

            //Top Settings
            $product->featured = isset($request->featured) ? '1' : '0';
            $product->dow = isset($request->dow) ? '1' : '0';
            $product->free_shipping = isset($request->free_shipping) ? '1' : '0';
            $product->free_east_shipping = isset($request->free_east_shipping) ? '1' : '0';
            $product->agent_only = isset($request->agent_only) ? '1' : '0';
            $product->customer_only = isset($request->customer_only) ? '1' : '0';
            $product->mall = !empty($request->mall) ? $request->mall : NULL;
            $product->store_stock = isset($request->store_stock) ? '1' : '0';

            //Product Details
            $product->item_code = GlobalController::product_item_code($request->category_id);
            $product->product_code = trim($request->product_code);
            $product->product_name = trim($request->product_name);
            $product->category_id = $request->category_id;
            $product->sub_category_id = !empty($request->sub_category_id) ? $request->sub_category_id : '';
            $product->brand_id = $request->brand_id;
            $product->description = $request->description;
            $product->label = trim($request->tags);
            $product->variation_title = trim($request->variation_title);
            $product->second_variation_title = trim($request->variation_two_title);
            $product->second_variation_enable = trim($request->variation_two_enable);
            $product->variation_enable = trim($request->variation_enable);
            
            //Pricing
            $product->price = preg_replace("/[^0-9\.]/", '', $request->price);
            $product->special_price = preg_replace("/[^0-9\.]/", '', $request->special_price);
            $product->weight = preg_replace("/[^0-9\.]/", '', $request->weight);
            $product->costing_price = preg_replace("/[^0-9\.]/", '', $request->costing_price);
            $product->get_point = preg_replace("/[^0-9\.]/", '', $request->get_point);

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

                if (!File::exists('../uploads/'.$product->id)) {
                    File::makeDirectory('../uploads/'.$product->id, $mode = 0777, true, true);
                }
                
                rename("../".$value->image, '../uploads/'.$product->id.'/'.end($explode));

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
                                $update_variation->variation_weight = $request['weight_'.$a][0];
                                $update_variation->variation_costing_price = $request['variation_costing_price_'.$a][0];
                                $update_variation->variation_get_point = $request['variation_get_point_'.$a][0];
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
                                    $update_second_variation->variation_weight = $request['weight_'.$a][$b];
                                    $update_second_variation->variation_costing_price = $request['variation_costing_price_'.$a][$b];
                                    $update_second_variation->variation_get_point = $request['variation_get_point_'.$a][$b];
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
                                        $insertSecondVariation->variation_weight = $request['weight_'.$a][$b];
                                        $insertSecondVariation->variation_costing_price = $request['variation_costing_price_'.$a][$b];
                                        $insertSecondVariation->variation_get_point = $request['variation_get_point_'.$a][$b];
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
                                $insertVariation->variation_weight = $request['weight_'.$a][0];
                                $insertVariation->variation_costing_price = $request['variation_costing_price_'.$a][0];
                                $insertVariation->variation_get_point = $request['variation_get_point_'.$a][0];

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
                                        $insertSecondVariation->variation_weight = $request['weight_'.$a][$b];
                                        $insertSecondVariation->variation_costing_price = $request['variation_costing_price_'.$a][$b];
                                        $insertSecondVariation->variation_get_point = $request['variation_get_point_'.$a][$b];
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

     Toastr::success( ($translation_data['backendlang']['backendlang']['product'] ?? 'Product') . ' ' . $product->product_name . ' ' . ($translation_data['backendlang']['backendlang']['Create_Successfully'] ?? 'Create Successfully!'));
     return redirect()->route('product.products.index');
    }
    
    public function edit_variation($id){

        $product = Product::find($id);
        
        $variations = ProductVariation::where('product_id', $product->id)->get();
        $svs = ProductSecondVariation::select('product_second_variations.*', 'v.variation_name as v_variation_name')
                                      ->leftJoin('product_variations as v', 'v.id', 'product_second_variations.variation_id')
                                      ->where('product_second_variations.product_id', $id)
                                      ->groupBy('product_second_variations.variation_name')
                                      ->get();
        return view('backend.products.edit_variation', ['product'=>$product,'variations'=>$variations,'svs'=>$svs]);
    }

    public function save_edit_variation($id, Request $request){

        $translation_data = GlobalController::get_translations();
        try{
            \DB::beginTransaction();
            
                $product = Product::find($id);
          
                $product->variation_title = trim($request->variation_title);
                $product->second_variation_title = trim($request->variation_two_title);
                $product->second_variation_enable = !empty($request->variation_two_title)?trim($request->variation_two_enable):0;
                $product->variation_enable = !empty($request->variation_title)? 1 : 0;

                $product->save();

                if (!empty($product->variation_enable)) {
                    for ($a = 0; $a < count($request->variation_option); $a++) {
                        if (!empty($request['fvid'][$a])) {
                            // Update existing variation
                            $variation = ProductVariation::find($request['fvid'][$a]);

                            if (empty($request->variation_option[$a])) {
                                $variation->update(['status' => '3']);
                            } else {
                                $variation->variation_name = $request->variation_option[$a];
                                $variation->save();
                            }
                        } else {
                            // Create new variation
                            if (!empty($request->variation_option[$a])) {
                                $variation = new ProductVariation();
                                $variation->product_id = $product->id;
                                $variation->variation_name = $request->variation_option[$a];
                                $variation->save();
                            }
                        }

                        if (isset($variation) && $variation->id) {
                            $second_variation = ProductSecondVariation::where('variation_id', $variation->id)
                                ->where('status', 1)
                                ->get();

                            for ($b = 0; $b < count($request->variation_two_option); $b++) {
                                if(!empty($request->variation_two_option[$b])){
                                    if (!empty($second_variation[$b])) {
                                        $second_variation[$b]->variation_name = $request->variation_two_option[$b];
                                    } else {
                                        
                                        $second = new ProductSecondVariation();
                                        $second->product_id = $product->id;
                                        $second->variation_id = $variation->id;
                                        $second->variation_name = $request->variation_two_option[$b];
                                        $second->status = 1;
                                        $second->save();
                                        continue;
                                    
                                    }
                                    $second_variation[$b]->save();
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

        Toastr::success($translation_data['backendlang']['backendlang']['Product_Variation_Edit_Successfully'] ?? "Product Variation Edit Successfully!");
        return redirect()->route('product.products.edit', $product->id);
    }
}
