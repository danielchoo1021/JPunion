<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PermissionGroup;
use App\Permission;

use Validator, Redirect, Toastr, DB, File, Auth;

class UserPermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $selects = PermissionGroup::where('status' , 1)->get();

        $permissions = Permission::get();

        $get_permission = [];

        foreach($permissions as $permission){
            $get_permission[$permission->permission_lvl][$permission->page] = 1;
        }

        return view('backend.permissions.index', ['selects'=>$selects], compact('get_permission'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
        //
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

    public function add_permission_level(Request $request)
    {
        $b = [];
        $caseString = 'case id';
        $ids = '';
        for($a=0; $a<count($request->name); $a++){
            if(empty($request->pid[$a])){
                if(!empty($request->name[$a])){
                    $b[] = [
                              "name"=>$request->name[$a]
                           ];                    
                }
            }else{
                $pid = $request->pid[$a];
                $name = $request->name[$a];

                $caseString .= " when $pid then '$name'";

                $ids .= "$pid,";
            }
        }

        $insert = PermissionLevelList::insert($b);

        $ids = trim($ids, ',');
        if($ids != ''){
            DB::update("update permission_level_lists set name = $caseString end
                                                    where id in ($ids)");
        }

        Toastr::success("已设置级别成功");
        return redirect()->route('user_permission.user_permissions.index');
    }
}
