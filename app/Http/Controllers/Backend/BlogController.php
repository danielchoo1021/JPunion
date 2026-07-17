<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// use Illuminate\Support\Facades\Input;
use App\Blog;

use App\Http\Controllers\GlobalController;
use Validator, Redirect, Toastr, DB, File, Auth;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $blogs = Blog::where('status', '!=', '3')->orderBy('created_at', 'desc');
        $queries = [];
        $columns = [
            'title', 'status'
        ];

        foreach($columns as $column){
            if(request()->has($column) && !empty(request($column))){
                
                $blogs = $blogs->where($column, 'like', "%".request($column)."%");

                $queries[$column] = request($column);
            }
        }

        $per_page = 10;
        if(!empty(request('per_page'))){
            $per_page = request('per_page');
        }
        $blogs = $blogs->paginate($per_page)->appends($queries);
        return view('backend.blogs.index', ['blogs'=>$blogs]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.blogs.create');
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
            'title' => ['required'],
            'description' => 'required',
            'image' => 'required',
            'blog_date' => 'required'
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }

        $input = $request->all();
        $input['title'] = trim($request->title);
        $input['description'] = trim($request->description);

        $parts = explode('/', $request->blog_date); 

        $input['blog_date'] = $parts[2] . '-' . $parts[1] . '-' . $parts[0];

        $input['blog_tags'] = json_encode($request->input('blog_tags', []));
        $input['blog_tags_cn'] = json_encode($request->input('blog_tags_cn', []));

        if(!empty($request->image)){
            
            $files = $request->file('image'); 
            $name = $files->getClientOriginalName();
            $exp = explode(".", $name);
            $file_ext = end($exp);
            $name = md5($name.date('Y-m-d H:i:s')).'.'.$file_ext;

            $files->move(GlobalController::get_image_path("uploads/blogs/"), $name);
            $input['image'] = "uploads/blogs/".$name;
        }

        $blog = Blog::create($input);

        Toastr::success($blog->title . " " . ($translation_data['backendlang']['backendlang']['Create_Successfully'] ?? "Create Successfully"));
        return redirect()->route('blog.blogs.index');
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
        $blog = Blog::find($id);
        return view('backend.blogs.edit', ['blog'=>$blog]);
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
            'title' => 'required',
            'description' => 'required',
            'blog_date' => 'required'
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }

        $input = $request->all();
        $input['title'] = trim($request->title);
        $input['description'] = trim($request->description);

        if(!empty($request->image)){
            
            $files = $request->file('image'); 
            $name = $files->getClientOriginalName();
            $exp = explode(".", $name);
            $file_ext = end($exp);
            $name = md5($name.date('Y-m-d H:i:s')).'.'.$file_ext;

            $files->move(GlobalController::get_image_path("uploads/blogs/"), $name);
            $input['image'] = "uploads/blogs/".$name;
        }else{
            $input = $request->except(['image']);
        }

        $update = Blog::find($id);
        $title = $update->title;
        $update = $update->update($input);

        Toastr::success(($translation_data['backendlang']['backendlang']['Blog'] ?? 'Blog') . " " . $title . " " .($translation_data['backendlang']['backendlang']['Update_Successfully'] ?? 'Update Successfully') . "!");
        return redirect()->route('blog.blogs.edit', $id);
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
