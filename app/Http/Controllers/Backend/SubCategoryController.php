<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// use Illuminate\Support\Facades\Input;
use App\SubCategory;
use App\Category;
use App\Merchant;

use Validator, Redirect, Toastr, DB, File, Auth;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sub_categories = SubCategory::select('sub_categories.*', 'c.category_name')
                                     ->join('categories AS c', 'c.id', 'sub_categories.category_id')
                                     ->where('sub_categories.status', '!=', '3');
                                     //->orderBy('created_at','desc');
        if(Auth::guard('merchant')->check()){
        $sub_categories = $sub_categories->where('sub_categories.merchant_id', Auth::user()->code);
        }
        $queries = [];
        $columns = [
            'sub_category_name', 'category_name', 'code_desc', 'code_asc', 'category_desc', 'category_asc', 'name_desc', 'name_asc', 'status_desc', 'status_asc', 'status'
        ];

        foreach($columns as $column){
            if(request()->has($column) && !empty(request($column))){
                if($column == 'code_desc'){
                    $sub_categories = $sub_categories->orderBy('sub_categories.sub_category_code', 'desc');
                }elseif($column == 'code_asc'){
                    $sub_categories = $sub_categories->orderBy('sub_categories.sub_category_code', 'asc');
                }elseif($column == 'category_desc'){
                    $sub_categories = $sub_categories->orderBy('c.category_name', 'desc');
                }elseif($column == 'category_asc'){
                    $sub_categories = $sub_categories->orderBy('c.category_name', 'asc');
                }elseif($column == 'name_desc'){
                    $sub_categories = $sub_categories->orderBy('sub_categories.sub_category_name', 'desc');
                }elseif($column == 'name_asc'){
                    $sub_categories = $sub_categories->orderBy('sub_categories.sub_category_name', 'asc');
                }elseif($column == 'status_desc'){
                    $sub_categories = $sub_categories->orderBy('sub_categories.status', 'desc');
                }elseif($column == 'status_asc'){
                    $sub_categories = $sub_categories->orderBy('sub_categories.status', 'asc');
                }elseif($column == 'status'){
                    $sub_categories = $sub_categories->where('sub_categories.status', 'like', "%".request($column)."%");
                }else{
                    $sub_categories = $sub_categories->where($column, 'like', "%".request($column)."%");
                }

                $queries[$column] = request($column);

            }
        }
        $per_page = 10;
        if(!empty(request('per_page'))){
            $per_page = request('per_page');
        }
        
        $sub_categories = $sub_categories->orderBy('created_at', 'desc');
        $sub_categories = $sub_categories->paginate($per_page)->appends($queries);

        return view('backend.sub_categories.index', ['sub_categories'=>$sub_categories]);
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

        $merchants = Merchant::get();

        foreach($merchants as $merchant){
            // echo substr($merchant->code, 1);
            // echo "<br>";

            // Merchant::find($merchant->id)->update(['display_running_no'=>substr($merchant->code, 1)]);
        }

        // exit();

        return view('backend.sub_categories.create', ['categories'=>$categories]);
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
            'category_id' => 'required',
            'sub_category_code' => ['required', 'unique:sub_categories'],
            'sub_category_name' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }

        $input = $request->all();
        $input['sub_category_name'] = trim($request->sub_category_name);
        if(Auth::guard('merchant')->check()){
        $input['merchant_id'] = Auth::user()->code;
        }

        $sub_category = SubCategory::create($input);

        Toastr::success( ($translation_data['backendlang']['backendlang']['subCategory'] ?? 'subCategory')  . ' ' . $sub_category->sub_category_name . ' ' . ($translation_data['backendlang']['backendlang']['Create_Successfully'] ?? 'Created Successfully!'));
        return redirect()->route('sub_category.sub_categories.index');
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
        $sub_category = SubCategory::find($id);
        $categories = Category::where('status', '1')->get();
        return view('backend.sub_categories.edit', ['sub_category'=>$sub_category, 'categories'=>$categories]);
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
            'category_id' => 'required',
            'sub_category_name' => 'required',
        ]);

        if ($request->sub_category_name == '') {
            return Redirect::back()->withInput($request->all())->withErrors($translation_data['backendlang']['backendlang']['Sub Category Name is required'] ?? 'Sub Category Name is required!');
        }

        $input = $request->all();
        $input['sub_category_name'] = trim($request->sub_category_name);

        $update = SubCategory::find($id);
        $sub_category_name = $update->sub_category_name;
        $update = $update->update($input);

        Toastr::success(($translation_data['backendlang']['backendlang']['subCategory'] ?? 'subCategory')  . ' ' . $sub_category_name . ' ' . ($translation_data['backendlang']['backendlang']['Update_Successful'] ?? 'Update Successful'));
        return redirect()->route('sub_category.sub_categories.edit', $id);
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
