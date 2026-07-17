<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// use Illuminate\Support\Facades\Input;
use App\Category;
use App\CategoryImage;

use App\Http\Controllers\GlobalController;
use Validator, Redirect, Toastr, DB, File, Auth;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function index()
    {

        $categories = Category::where('status', '!=', '3');
        if(Auth::guard('merchant')->check()){
        $categories = $categories->where('merchant_id', Auth::user()->code);
        }
        $queries = [];
        $per_page = request('per_page', 10);
        if (!empty(request('per_page'))) {
            $queries['per_page'] = $per_page;
        }

        if (!empty(request('code'))) {
            $categories = $categories->where('code', 'like', "%" . request('code') . "%");
            $queries['code'] = request('code');
        }
        if (!empty(request('category_name'))) {
            $categories = $categories->where('category_name', 'like', "%" . request('category_name') . "%");
            $queries['category_name'] = request('category_name');
        }
        if (!empty(request('status'))) {
            $categories = $categories->where('status', request('status'));
            $queries['status'] = request('status');
        }

        if (!empty(request('category_code_desc'))) {
            $categories = $categories->orderBy('categories.code', 'desc');
            $queries['category_code_desc'] = request('category_code_desc');
        } elseif (!empty(request('category_code_asc'))) {
            $categories = $categories->orderBy('categories.code', 'asc');
            $queries['category_code_asc'] = request('category_code_asc');
        } elseif (!empty(request('category_name_desc'))) {
            $categories = $categories->orderBy('categories.category_name', 'desc');
            $queries['category_name_desc'] = request('category_name_desc');
        } elseif (!empty(request('category_name_asc'))) {
            $categories = $categories->orderBy('categories.category_name', 'asc');
            $queries['category_name_asc'] = request('category_name_asc');
        } elseif (!empty(request('status_desc'))) {
            $categories = $categories->orderBy('categories.status', 'desc');
            $queries['status_desc'] = request('status_desc');
        } elseif (!empty(request('status_asc'))) {
            $categories = $categories->orderBy('categories.status', 'asc');
            $queries['status_asc'] = request('status_asc');
        } else {
            $categories = $categories->orderBy('categories.created_at', 'desc');
        }

        $categories = $categories->paginate($per_page)->appends($queries);


        return view('backend.categories.index', ['categories'=>$categories]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.categories.create');
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
            'code' => ['required', 'unique:categories'],
            'category_name' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }

        $input = $request->all();
        $input['category_name'] = trim($request->category_name);
        $input['menu_bar'] = !empty($request->menu_bar) ? $request->menu_bar : 0;
        
        if(Auth::guard('merchant')->check()){
        $input['merchant_id'] = Auth::user()->code;
        }

        $category = Category::create($input);

        
        if(!empty($request->upload_image)){
            
            $files = $request->file('upload_image'); 
            $name = $files->getClientOriginalName();
            $exp = explode(".", $name);
            $file_ext = end($exp);
            $name = md5($name.date('Y-m-d H:i:s')).'.'.$file_ext;

            $input2 = [];
            $input2['category_id'] = $category->id;
            $input2['status'] = '1';
            $input2['image'] = "uploads/category/".$name;
            $files->move(GlobalController::get_image_path("uploads/category/"), $name);
            $category_image = CategoryImage::create($input2);            
        }

        Toastr::success( ($translation_data['backendlang']['backendlang']['Category'] ?? 'Category')  . ' ' . $category->category_name . ' ' . ($translation_data['backendlang']['backendlang']['Create_Successfully'] ?? 'Created Successfully!'));
        return redirect()->route('category.categories.index');
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
        $category = Category::find($id);
        return view('backend.categories.edit', ['category'=>$category]);
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
            'category_name' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }

        $input = $request->all();
        $input['category_name'] = trim($request->category_name);
        $input['menu_bar'] = isset($request->menu_bar) ? $request->menu_bar : 0;

        $update = Category::find($id);
        $category_name = $update->category_name;
        $update = $update->update($input);

        if(!empty($request->upload_image)){
            
            $files = $request->file('upload_image'); 
            $name = $files->getClientOriginalName();
            $exp = explode(".", $name);
            $file_ext = end($exp);
            $name = md5($name.date('Y-m-d H:i:s')).'.'.$file_ext;



            $input2 = [];

            $input2['category_id'] = $id;
            $input2['status'] = '1';
            $input2['image'] = "uploads/category/".$name;
            $files->move(GlobalController::get_image_path("uploads/category/"), $name);

            $category_image = CategoryImage::where('category_id', $id)->first();
            if(!empty($category_image->id)){
                $update_category_image = CategoryImage::find($category_image->id);
                $update_category_image = $category_image->update($input2);
            }else{
                $category_image = CategoryImage::create($input2);
            }

        }


        Toastr::success(($translation_data['backendlang']['backendlang']['Category'] ?? 'Category')  . ' ' . $category->category_name . ' ' . ($translation_data['backendlang']['backendlang']['Update_Successful'] ?? 'Update Successful'));
        return redirect()->route('category.categories.edit', $id);
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
