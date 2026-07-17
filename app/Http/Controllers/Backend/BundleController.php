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
use App\Bundle;
use App\BundleDetail;

use Validator, Redirect, Toastr, DB, File;

class BundleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bundles = Bundle::where('status', '!=', '3')
                          ->orderBy('created_at', 'desc');

        $queries = [];
        $columns = [
            'bundle_name', 'status', 'per_page'
        ];

        $per_page = 10;
        if(!empty(request('per_page'))){
            $per_page = request('per_page');
        }

        foreach($columns as $column){
            if(request()->has($column) && !empty(request($column))){
                if($column == 'per_page'){
                    $bundles = $bundles->paginate($per_page);
                }else{
                    $bundles = $bundles->where($column, 'like', "%".request($column)."%");
                }

                $queries[$column] = request($column);

            }
        }
        
        if(!empty(request('per_page'))){
            $bundles = $bundles->appends($queries);        
        }else{
            $bundles = $bundles->paginate($per_page)->appends($queries);
        }

        return view('backend.bundles.index', ['bundles' => $bundles]);
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

        $bundle_details = BundleDetail::get();

        return view('backend.bundles.create', ['products'=>$products, 'bundle_details'=>$bundle_details]);
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
            'bundle_name' => 'required',
            'bundle_price' => 'required',
            'bundle_agent_price' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }

        $input = [];
        $input['bundle_name'] = trim($request->bundle_name);
        $input['bundle_description'] = trim($request->bundle_description);
        $input['bundle_price'] = preg_replace("/[^0-9\.]/", '', $request->bundle_price);
        $input['bundle_agent_price'] = preg_replace("/[^0-9\.]/", '', $request->bundle_agent_price);

        $create = Bundle::create($input);


        for($a=0; $a<count($request->product_id); $a++){
            if(!empty($request->bid[$a])){
                $update = BundleDetail::find($request->bid[$a])->update(['product_id'=>$request->product_id[$a]]);
            }else{
                if(!empty($request->product_id[$a])){
                    $inputD = [];
                    $inputD['bundle_id'] = $create->id;
                    $inputD['product_id'] = $request->product_id[$a];

                    BundleDetail::create($inputD);
                }
            }
        }

        Toastr::success("Product $create->bundle_name Create Successfully!");
        return redirect()->route('bundle.bundles.index');
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
        $bundle = Bundle::find($id);

        if(empty($bundle->id)){
            abort(404);
        }

        $bundle_details = BundleDetail::where('bundle_id', $id)->get();

        $products = Product::where('status', '1')
                           ->orderBy('product_name', 'asc')
                           ->get();

        return view('backend.bundles.edit', ['products'=>$products, 'bundle'=>$bundle,
                                             'bundle_details'=>$bundle_details]);
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
            'bundle_name' => 'required',
            'bundle_price' => 'required',
            'bundle_agent_price' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }

        $input = [];
        $input['bundle_name'] = trim($request->bundle_name);
        $input['bundle_description'] = trim($request->bundle_description);
        $input['bundle_price'] = preg_replace("/[^0-9\.]/", '', $request->bundle_price);
        $input['bundle_agent_price'] = preg_replace("/[^0-9\.]/", '', $request->bundle_agent_price);

        $update = Bundle::find($id);
        $update = $update->update($input);

        for($a=0; $a<count($request->product_id); $a++){
            if(!empty($request->bid[$a])){
                $update = BundleDetail::find($request->bid[$a])->update(['product_id'=>$request->product_id[$a]]);
            }else{
                if(!empty($request->product_id[$a])){
                    $inputD = [];
                    $inputD['bundle_id'] = $id;
                    $inputD['product_id'] = $request->product_id[$a];

                    BundleDetail::create($inputD);
                }
            }
        }

        Toastr::success("Bundle Updated Successfully!");
        return redirect()->route('bundle.bundles.edit', $id);
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
