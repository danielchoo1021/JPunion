<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// use Illuminate\Support\Facades\Input;
use App\CartLink;
use App\CartLinkProductDetail;
use App\Product;
use App\ProductVariation;
use App\ProductSecondVariation;
use App\Admin;
use App\Merchant;
use App\User;

use App\Http\Controllers\GlobalController;

use Validator, Redirect, Toastr, DB, File, Auth;

class CartLinkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cart_links = CartLink::select('cart_links.*',
                                        DB::raw('COALESCE(a.f_name, m.f_name) as user_name'))
                              ->leftJoin('admins as a', 'a.code', 'cart_links.user_id')
                              ->leftJoin('merchants as m', 'm.code', 'cart_links.user_id')
                              ->where('cart_links.status', '!=', '3')
                              ->orderBy('cart_links.created_at', 'desc');
        if(Auth::guard('merchant')->check()){
        $cart_links = $cart_links->where('merchant_id', Auth::user()->code);
        }
        $queries = [];
        $columns = [
            'status', 'per_page'
        ];

        $per_page = 10;
        if(!empty(request('per_page'))){
            $per_page = request('per_page');
        }

        foreach($columns as $column){
            if(request()->has($column) && !empty(request($column))){
                if($column == 'per_page'){
                    $cart_links = $cart_links->paginate($per_page);
                }elseif($column == 'status'){
                    $cart_links = $cart_links->where('cart_links.status', request($column));
                }else{
                    $cart_links = $cart_links->where($column, 'like', "%".request($column)."%");
                }

                $queries[$column] = request($column);

            }
        }
        
        if(!empty(request('per_page'))){
            $cart_links = $cart_links->appends($queries);        
        }else{
            $cart_links = $cart_links->paginate($per_page)->appends($queries);
        }

        $remaining_count = [];
        foreach($cart_links as $link){
            $remaining_count[$link->id] = GlobalController::checkCartLinkQuantity($link->id);
        }

        return view('backend.cart_links.index', ['cart_links' => $cart_links],
                                                compact('remaining_count'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $products = Product::where('status', '1')
                           ->orderBy('product_name', 'asc')
                           ->get();

        return view('backend.cart_links.create', ['products'=>$products]);
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

            $insert_cart_link = new CartLink();
            if(Auth::guard('merchant')->check()){
            $insert_cart_link->merchant_id = Auth::user()->code;
            }
            $insert_cart_link->user_id = Auth::user()->code;
            $insert_cart_link->price = $request->modify_price;
            if(!empty($request->quantity)){
                $insert_cart_link->qty = $request->quantity;
            }else{
                throw new \Exception($translation_data['backendlang']['backendlang']['Insert_Limit_Quantity'] ?? 'Please Insert Limit Quantity For Cart Link');
            }

            if(!empty($request->variations)){
                foreach($request->variations as $v){
                    if(empty($v)){
                        throw new \Exception($translation_data['backendlang']['backendlang']['Select_Product_Variations'] ?? 'Please Select Product Variations');
                    }
                }
            }

            if(!empty($request->second_variations)){
                foreach($request->second_variations as $sv){
                    if(empty($sv)){
                        throw new \Exception($translation_data['backendlang']['backendlang']['Select_Product_Second_Variations'] ?? 'Please Select Product Second Variations');                     
                    }
                }
            }

            $insert_cart_link->save();

            $update_cart_link = CartLink::find($insert_cart_link->id);
            $update_cart_link->unique_id = route('checkout', 'cl='.md5($insert_cart_link->id));
            // $update_cart_link->unique_id = GlobalController::get_production_url('Checkout?cl='.md5($insert_cart_link->id));
            $update_cart_link->save();

            if(count($request->products) < 1){
                throw new \Exception($translation_data['backendlang']['backendlang']['Please_Select_Products'] ?? 'Please Select Products');
            }

            for($x = 1; $x <= count($request->products); $x++){
                if(!empty($request['products'][$x])){
                    if(!empty($request['cart_link_detail_id'][$x])){
                        $updateCartLinkProductDetail = CartLinkProductDetail::find($request['cart_link_detail_id'][$x]);

                        $updateCartLinkProductDetail->product_id = $request['products'][$x];
                        if(!empty($request['variations'][$x])){
                            $updateCartLinkProductDetail->variation_id = $request['variations'][$x];
                        }
                        if(!empty($request['second_variations'])){
                            $updateCartLinkProductDetail->second_variation_id = $request['second_variations'][$x];
                        }
                        $updateCartLinkProductDetail->qty = $request['qty'][$x];

                        $updateCartLinkProductDetail->save();
                    }else{
                        $insertCartLinkProductDetail = new CartLinkProductDetail();

                        $insertCartLinkProductDetail->cart_link_id = $insert_cart_link->id;
                        $insertCartLinkProductDetail->product_id = $request['products'][$x];
                        if(!empty($request['variations'][$x])){
                            $insertCartLinkProductDetail->variation_id = $request['variations'][$x];
                        }
                        if(!empty($request['second_variations'])){
                            $insertCartLinkProductDetail->second_variation_id = $request['second_variations'][$x];
                        }
                        $insertCartLinkProductDetail->qty = $request['qty'][$x];

                        $insertCartLinkProductDetail->save();
                    }
                }else{
                    if($x == 1){
                        throw new \Exception($translation_data['backendlang']['backendlang']['Please_Select_Products'] ?? 'Please Select Products');
                    }
                }
            }

            \DB::commit();

            Toastr::success($translation_data['backendlang']['backendlang']['Cart_Link_Created'] ?? 'Cart Link Created');
            return redirect()->route('cart_link.cart_links.index');
        } catch(\Exception $e){
            \DB::rollback();
            // Toastr::error($e->getMessage());
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
        $cart_link = CartLink::find($id);

        $cart_link_details = CartLinkProductDetail::where('cart_link_id', $id)->get();

        $variation_choices = [];
        $second_variation_choices = [];
        foreach($cart_link_details as $detail){
            if(!empty($detail->variation_id)){
                $variation_choices[$detail->id] = ProductVariation::where('product_id', $detail->product_id)
                                                                  ->where('status', '1')
                                                                  ->get();
            }

            if(!empty($detail->second_variation_id)){
                $second_variation_choices[$detail->id] = ProductSecondVariation::where('product_id', $detail->product_id)
                                                                               ->where('variation_id', $detail->variation_id)
                                                                               ->where('status', '1')
                                                                               ->get();
            }
        }

        $products = Product::where('status', '1')
                           ->orderBy('product_name', 'asc')
                           ->get();

        return view('backend.cart_links.edit', ['products'=>$products, 'cart_link'=>$cart_link,
                                             'cart_link_details'=>$cart_link_details],
                                             compact('variation_choices', 'second_variation_choices'));
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

            $update_cart_link = CartLink::find($id);
            $update_cart_link->user_id = Auth::user()->code;
            $update_cart_link->price = $request->modify_price;
            if(!empty($request->quantity)){
                $update_cart_link->qty = $request->quantity;
            }else{
                throw new \Exception($translation_data['backendlang']['backendlang']['Insert_Limit_Quantity'] ?? 'Please Insert Limit Quantity For Cart Link');
            }

            if(!empty($request->variations)){
                foreach($request->variations as $v){
                    if(empty($v)){
                        throw new \Exception($translation_data['backendlang']['backendlang']['Select_Product_Variations'] ?? 'Please Select Product Variations');
                    }
                }
            }

            if(!empty($request->second_variations)){
                foreach($request->second_variations as $sv){
                    if(empty($sv)){
                        throw new \Exception($translation_data['backendlang']['backendlang']['Select_Product_Second_Variations'] ?? 'Please Select Product Second Variations');
                    }
                }
            }

            $update_cart_link->save();

            if(count($request->products) < 1){
                   throw new \Exception($translation_data['backendlang']['backendlang']['Please_Select_Products'] ?? 'Please Select Products');
            }

            for($x = 1; $x <= count($request->products); $x++){
                if(!empty($request['products'][$x])){
                    if(!empty($request['cart_link_detail_id'][$x])){
                        $updateCartLinkProductDetail = CartLinkProductDetail::find($request['cart_link_detail_id'][$x]);

                        $updateCartLinkProductDetail->product_id = $request['products'][$x];
                        if(!empty($request['variations'][$x])){
                            $updateCartLinkProductDetail->variation_id = $request['variations'][$x];
                        }
                        if(!empty($request['second_variations'])){
                            $updateCartLinkProductDetail->second_variation_id = $request['second_variations'][$x];
                        }
                        $updateCartLinkProductDetail->qty = $request['qty'][$x];

                        $updateCartLinkProductDetail->save();
                    }else{
                        $insertCartLinkProductDetail = new CartLinkProductDetail();

                        $insertCartLinkProductDetail->cart_link_id = $update_cart_link->id;
                        $insertCartLinkProductDetail->product_id = $request['products'][$x];
                        if(!empty($request['variations'][$x])){
                            $insertCartLinkProductDetail->variation_id = $request['variations'][$x];
                        }
                        if(!empty($request['second_variations'])){
                            $insertCartLinkProductDetail->second_variation_id = $request['second_variations'][$x];
                        }
                        $insertCartLinkProductDetail->qty = $request['qty'][$x];

                        $insertCartLinkProductDetail->save();
                    }
                }else{
                    if($x == 1){
                        throw new \Exception($translation_data['backendlang']['backendlang']['Please_Select_Products'] ?? 'Please Select Products');
                    }
                }
            }

            \DB::commit();

            Toastr::success($translation_data['backendlang']['backendlang']['Cart_Link_Updated'] ?? 'Cart Link Updated');
            return redirect()->route('cart_link.cart_links.edit', $id);
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
