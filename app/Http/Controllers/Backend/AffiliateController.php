<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Admin;
use App\Merchant;
use App\Affiliate;
use App\User;
use Validator, Redirect, Toastr, DB, File, Auth;

class AffiliateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

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

    public function affiliates($code)
    {
        $merchant = Merchant::where('code', $code)->first();
        $admin = Admin::where('code', $code)->first();
        $name = Auth::user()->f_name.' '.Auth::user()->l_name;
        
        if(!empty($merchant->id)){
            $id = $merchant->code;
            $name = $merchant->f_name.' '.$merchant->l_name;
            $lvl = $merchant->lvl;
            $profile_logo = $merchant->profile_logo;
            $upline = $merchant->master_id;
        }else{
            $id = $admin->code;
            $lvl = 99;
            $profile_logo = $admin->profile_logo;
            $upline = "";
        }
        
        $affiliates = Merchant::where('master_id', $code);

        if(!empty(request('name'))){
            $affiliates = $affiliates->where(DB::raw("CONCAT(f_name, l_name)"), 'like', '%'.request('name').'%');
        }

        if(!empty(request('aff_code'))){
            $affiliates = $affiliates->where('code', request('aff_code'));
        }

        $affiliates = $affiliates->get();

        $OwnAffiliate = $this->GetOwnTotalAffiliates($code);
        $OwnTotalAffiliate = $this->GetSelectedUserTotalAffiliates($code);
        $OwnMonthlyTotalAffiliate = $this->GetSelectedUserMonthlyTotalAffiliates($code);
        $GetSelectedUserDailyTotalAffiliates = $this->GetSelectedUserDailyTotalAffiliates($code);
        $TotalAffiliates = [];
        $TodayTotalAffiliates = [];


        
        foreach($affiliates as $affiliate){
            $TotalAffiliates[$affiliate->code] = $this->GetTotalAffiliates($affiliate->code);
            $TodayTotalAffiliates[$affiliate->code] = $this->GetTodayTotalAffiliates($affiliate->code);
        }

        return view('backend.affiliates.index', ['affiliates'=>$affiliates, 'OwnTotalAffiliate'=>$OwnTotalAffiliate, 
                                                 'OwnMonthlyTotalAffiliate'=>$OwnMonthlyTotalAffiliate, 
                                                 'GetSelectedUserDailyTotalAffiliates'=>$GetSelectedUserDailyTotalAffiliates,
                                                 'name'=>$name,
                                                 'code'=>$code,
                                                 'lvl'=>$lvl,
                                                 'upline'=>$upline,
                                                 'profile_logo'=>$profile_logo,
                                                 'OwnAffiliate'=>$OwnAffiliate],
                                                 compact('TotalAffiliates', 'TodayTotalAffiliates'));
    }

    public function GetOwnTotalAffiliates($code)
    {
      $affiliate = Merchant::select(DB::raw('COUNT(id) AS TotalAffiliates'))
                              ->where('master_id', $code)
                              ->first();

      return  $affiliate->TotalAffiliates;
    }

    public function GetTotalAffiliates($code)
    {
        $affiliate = Merchant::select(DB::raw('COUNT(id) AS TotalAffiliates'))
                              ->where('master_id', $code)
                              ->first();

        $affiliate2 = Merchant::select(DB::raw('COUNT(d.id) AS TotalAffiliates2'))
                              ->join('merchants AS d', 'd.master_id', 'merchants.code')
                              ->where('merchants.master_id', $code)
                              ->first();

        return  $affiliate->TotalAffiliates + $affiliate2->TotalAffiliates2;
    }

    public function GetTodayTotalAffiliates($code)
    {
        $affiliate = Merchant::select(DB::raw('COUNT(id) AS TotalAffiliates'))
                              ->where('master_id', $code)
                              ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), date('Y-m-d'))
                              ->first();

        $affiliate2 = Merchant::select(DB::raw('COUNT(d.id) AS TotalAffiliates2'))
                              ->join('merchants AS d', 'd.master_id', 'merchants.code')
                              ->where('merchants.master_id', $code)
                              ->where(DB::raw('DATE_FORMAT(merchants.created_at, "%Y-%m-%d")'), date('Y-m-d'))
                              ->first();

        return  $affiliate->TotalAffiliates + $affiliate2->TotalAffiliates2;
    }

    public function GetSelectedUserTotalAffiliates($code)
    {
        $affiliate = Merchant::select(DB::raw('COUNT(id) AS TotalAffiliates'))
                              ->where('master_id', $code)
                              ->first();


        $affiliate2 = Merchant::select(DB::raw('COUNT(d.id) AS TotalAffiliates2'))
                              ->join('merchants AS d', 'd.master_id', 'merchants.code')
                              ->where('merchants.master_id', $code)
                              ->first();

        return  $affiliate->TotalAffiliates + $affiliate2->TotalAffiliates2;
    }

    public function GetSelectedUserMonthlyTotalAffiliates($code)
    {
        $affiliate = Merchant::select(DB::raw('COUNT(id) AS TotalAffiliates'))
                              ->where('master_id', $code)
                              ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), date('Y-m'))
                              ->first();

        $affiliate2 = Merchant::select(DB::raw('COUNT(d.id) AS TotalAffiliates2'))
                              ->join('merchants AS d', 'd.master_id', 'merchants.code')
                              ->where('merchants.master_id', $code)
                              ->where(DB::raw('DATE_FORMAT(merchants.created_at, "%Y-%m")'), date('Y-m'))
                              ->first();

        return  $affiliate->TotalAffiliates + $affiliate2->TotalAffiliates2;
    }

    public function GetSelectedUserDailyTotalAffiliates($code)
    {
        $affiliate = Merchant::select(DB::raw('COUNT(id) AS TotalAffiliates'))
                              ->where('master_id', $code)
                              ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), date('Y-m-d'))
                              ->first();

        $affiliate2 = Merchant::select(DB::raw('COUNT(d.id) AS TotalAffiliates2'))
                              ->join('merchants AS d', 'd.master_id', 'merchants.code')
                              ->where('merchants.master_id', $code)
                              ->where(DB::raw('DATE_FORMAT(merchants.created_at, "%Y-%m-%d")'), date('Y-m-d'))
                              ->first();

        return  $affiliate->TotalAffiliates + $affiliate2->TotalAffiliates2;
    }


}
