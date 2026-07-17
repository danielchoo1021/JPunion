<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Facades\Input;

use App\Merchant;
use App\State;
use App\TblCountry;
use App\WebsiteSetting;
use App\AgentLevel;
use App\Affiliate;
use App\AdjustCashWallet;
use App\AgentLevelRecord;
use App\Transaction;
use App\Promotion;
use App\AdjustVoucher;
use App\AffiliateCommission;
use App\SettingRefferalReward;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\GlobalController;

use App\Exports\ExportMerchant;
use Maatwebsite\Excel\Facades\Excel;

use Validator, Redirect, Toastr, DB, File, Auth, Mail;

class MerchantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $merchants = Merchant::select('merchants.*')
                             ->leftJoin('merchants as upm', 'upm.code', 'merchants.master_id')
                             ->leftJoin('admins as upa', 'upa.code', 'merchants.master_id')
                             ->whereNotIn('merchants.status', ['99', '3'])
                             ->orderBy('merchants.created_at', 'desc');

        $queries = [];
        $columns = [
            'code', 
            'merchant_name', 
            'lvl', 
            'status', 
            'agent_type', 
            'ic', 
            'phone', 
            'email', 
            'referrer_code', 
            'referrer_name', 
            'code_desc', 
            'code_asc', 
            'name_desc', 
            'name_asc', 
            'lvl_desc', 
            'lvl_asc', 
            'ref_code_desc', 
            'ref_code_asc', 
            'ref_name_desc', 
            'ref_name_asc', 
            'email_desc', 
            'email_asc', 
            'ic_desc', 
            'ic_asc', 
            'phone_desc', 
            'phone_asc', 
            'status_desc', 
            'status_asc', 
            'company_num_desc', 
            'company_num_asc', 
            'acc_type_desc', 
            'acc_type_asc', 
            'account_type', 
            'per_page'
        ];

        $per_page = 10;
        if(!empty(request('per_page'))){
            $per_page = request('per_page');
        }

        foreach($columns as $column){
            if(request()->has($column) && !empty(request($column))){
                if($column == 'merchant_name'){
                    $merchants = $merchants->where(DB::raw('CONCAT(merchants.f_name, " ", merchants.l_name)'), 'like', "%".request($column)."%");
                }elseif($column == 'ic'){
                    $merchants = $merchants->where('merchants.ic', 'like', "%".request($column)."%");
                }elseif($column == 'phone'){
                    $merchants = $merchants->where('merchants.phone', 'like', "%".request($column)."%");
                }elseif($column == 'email'){
                    $merchants = $merchants->where('merchants.email', 'like', "%".request($column)."%");
                }elseif($column == 'referrer_code'){
                    $merchants = $merchants->where(DB::raw('COALESCE(CONCAT(upm.display_code, upm.display_running_no), CONCAT(upa.display_code, upa.display_running_no))'), 'like', "%".request($column)."%");
                }elseif($column == 'lvl'){
                    $merchants = $merchants->where('merchants.lvl', 'like', "%".request($column)."%");
                }elseif($column == 'referrer_name'){
                    $merchants = $merchants->where(DB::raw('COALESCE(CONCAT(upm.f_name, " ", upm.l_name), CONCAT(upa.f_name, " ", upa.l_name))'), 'like', "%".request($column)."%");
                }elseif($column == 'code'){
                    $merchants = $merchants->where(DB::raw('CONCAT(merchants.display_code, merchants.display_running_no)'), 'like', "%".request($column)."%");
                }elseif($column == 'status'){
                    if(request($column) == 55){
                        $merchants = $merchants->where('merchants.active_period', '>', 0)
                                               ->where(DB::raw('DATE_ADD(merchants.created_at, INTERVAL merchants.active_period DAY)'), '<',now());
                    }elseif(request($column) == 1){
                        $merchants = $merchants
                            ->where('merchants.status', 1)
                            ->where(function ($q) {
                                $q->where('merchants.active_period', 0)
                                    ->orWhere(DB::raw('DATE_ADD(merchants.created_at, INTERVAL merchants.active_period DAY)'), '>=', now());
                            });
                    }else{
                        $merchants = $merchants->where('merchants.status', 'like', "%".request($column)."%");
                    }
                }elseif($column == 'code_desc'){
                    $merchants = $merchants->orderBy('merchants.code', 'desc');
                }elseif($column == 'code_asc'){
                    $merchants = $merchants->orderBy('merchants.code', 'asc');
                }elseif($column == 'name_desc'){
                    $merchants = $merchants->orderBy('merchants.f_name', 'desc');
                }elseif($column == 'name_asc'){
                    $merchants = $merchants->orderBy('merchants.f_name', 'asc');
                }elseif($column == 'lvl_desc'){
                    $merchants = $merchants->orderBy('merchants.lvl', 'desc');
                }elseif($column == 'lvl_asc'){
                    $merchants = $merchants->orderBy('merchants.lvl', 'asc');
                }elseif($column == 'ref_code_desc'){
                    $merchants = $merchants->orderBy('upline_code', 'desc');
                }elseif($column == 'ref_code_asc'){
                    $merchants = $merchants->orderBy('upline_code', 'asc');
                }elseif($column == 'ref_name_desc'){
                    $merchants = $merchants->orderBy('upline_name', 'desc');
                }elseif($column == 'ref_name_asc'){
                    $merchants = $merchants->orderBy('upline_name', 'asc');
                }elseif($column == 'email_desc'){
                    $merchants = $merchants->orderBy('merchants.email', 'desc');
                }elseif($column == 'email_asc'){
                    $merchants = $merchants->orderBy('merchants.email', 'asc');
                }elseif($column == 'ic_desc'){
                    $merchants = $merchants->orderBy('merchants.ic', 'desc');
                }elseif($column == 'ic_asc'){
                    $merchants = $merchants->orderBy('merchants.ic', 'asc');
                }elseif($column == 'phone_desc'){
                    $merchants = $merchants->orderBy('merchants.phone', 'desc');
                }elseif($column == 'phone_asc'){
                    $merchants = $merchants->orderBy('merchants.phone', 'asc');
                }elseif($column == 'status_desc'){
                    $merchants = $merchants->orderBy('merchants.status', 'desc');
                }elseif($column == 'status_asc'){
                    $merchants = $merchants->orderBy('merchants.status', 'asc');
                }elseif($column == 'company_num_desc'){
                    $merchants = $merchants->orderBy('merchants.company_registration_no', 'desc');
                }elseif($column == 'company_num_asc'){
                    $merchants = $merchants->orderBy('merchants.company_registration_no', 'asc');
                }elseif($column == 'acc_type_desc'){
                    $merchants = $merchants->orderBy('merchants.company', 'desc');
                }elseif($column == 'acc_type_asc'){
                    $merchants = $merchants->orderBy('merchants.company', 'asc');
                }elseif($column == 'account_type'){
                    if(request($column) == '1'){
                        $merchants = $merchants->where('merchants.company', '1');
                    }elseif(request($column) == '2'){
                        $merchants = $merchants->whereNull('merchants.company');
                    }
                }elseif(request($column) == 'per_page'){
                    $merchants = $merchants->paginate($per_page);            
                }

                $queries[$column] = request($column);

            }
        }
        // $merchants = $merchants->orderBy('merchants.display_running_no', 'desc');
        $merchants = $merchants->orderBy('merchants.code', 'desc');
        $merchants = $merchants->paginate($per_page)->appends($queries);

        $get_merchant_expired_date = [];
        foreach($merchants as $merchant){
            $get_merchant_expired_date[$merchant->code] = GlobalController::get_merchant_expired_date($merchant->code, $merchant->active_period);
        }
        $agent_lvls = AgentLevel::get();
        
        return view('backend.merchants.index', ['merchants'=>$merchants, 'agent_lvls'=>$agent_lvls], compact('get_merchant_expired_date'));
    }
    public function pending_merchant()
    {

        $merchants = Merchant::select('merchants.*',
                                      DB::raw('COALESCE(COALESCE(CONCAT(upm.display_code, upm.display_running_no), upa.code), upu.code) AS upline_code'),
                                      DB::raw('COALESCE(COALESCE(CONCAT(upm.f_name, " ", upm.l_name), CONCAT(upa.f_name, " ", upa.l_name)), upu.f_name) AS upline_name'))
                             ->leftJoin('merchants as upm', 'upm.code', 'merchants.master_id')
                             ->leftJoin('admins as upa', 'upa.code', 'merchants.master_id')
                             ->leftJoin('users as upu', 'upu.code', 'merchants.master_id')
                             ->where('merchants.status', '99')
                             ->orderBy('merchants.created_at', 'desc');
        $queries = [];
        $columns = [
            'merchant_name', 'code'
        ];
        foreach($columns as $column){
            if(request()->has($column) && !empty(request($column))){
                if($column == 'merchant_name'){
                    $merchants = $merchants->where(DB::raw('CONCAT(merchants.f_name, " ", merchants.l_name)'), 'like', "%".request($column)."%");
                }else{
                    $merchants = $merchants->where($column, 'like', "%".request($column)."%");
                }

                $queries[$column] = request($column);

            }
        }
        $per_page = 10;
        if(!empty(request('per_page'))){
            $per_page = request('per_page');
        }
        $merchants = $merchants->paginate($per_page)->appends($queries);

        $transaction_bank_slip = [];
        foreach($merchants as $merchant){
            $transaction_bank_slip[$merchant->code] = Transaction::where('transaction_no', $merchant->register_transaction)
                                                                ->orderBy('created_at', 'asc')
                                                                ->first();
        }

        return view('backend.merchants.pending', ['merchants'=>$merchants], compact('transaction_bank_slip'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        

        $levels = AgentLevel::select('agent_levels.*');
        if(Auth::guard('merchant')->check()){
        $levels = $levels->where('merchant_id', Auth::user()->code);
        }else{
        $levels = $levels->whereNull('merchant_id');
        }
        $levels = $levels->get();

        // $countries = TblCountry::get();
        $countries = GlobalController::global_countries();

        $states = State::get();

        return view('backend.merchants.create', ['countries'=>$countries, 
                                                 'levels'=>$levels,
                                                 'states'=>$states]);
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
            'f_name' => 'required',
            'phone' => ['required', 'unique:users', 'unique:agents', 'unique:merchants'],
            'email' => ['required', 'unique:users', 'unique:agents', 'unique:merchants'],
            'password' => ['required', 'min:6']
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }

        try{

            \DB::beginTransaction();

            if(!empty($request->agent_pno)){
                $get_upline = Merchant::where(DB::raw("CONCAT(display_code, display_running_no)"), $request->agent_pno)->first();
                if(empty($get_upline->id)){
                    throw new \Exception("Referral Code Not Exists");
                }

                if(!empty($get_upline->id)){
                    $master_id = $get_upline->code;
                }
            }else{
                $master_id = "AD000001";
            }

            $website_setting = WebsiteSetting::find(1);

            $agent_display_code = GlobalController::MerchantDisplayCode();

            $agent = new Merchant();
            //Agent Details
            $agent->code = GlobalController::MerchantCode();
            $agent->display_code = $agent_display_code[0];
            $agent->display_running_no = $agent_display_code[1];
            $agent->country_code = $request->country_code;
            $agent->phone = $request->phone;
            $agent->email = $request->email;
            $agent->password = Hash::make($request->password);
            $agent->f_name = $request->f_name;
            $agent->description = $request->description;
            $agent->active_period = $request->active_period;

            $agent->bonus_agent_enable = 1;
            $agent->agent_rebate_enable = 1;
            $agent->hierarchy_enable = 1;
            $agent->referral_enable = 1;

            $agent->website_name = $website_setting->website_name;
            $agent->website_logo = $website_setting->website_logo;
            $agent->fav_icon = $website_setting->fav_icon;
            $agent->home_page_first_image = $website_setting->home_page_first_image;
            $agent->home_page_second_image = $website_setting->home_page_second_image;
            
            $agent->permission_lvl = 2;

            $agent->save();

            

            $merchant_setting_function = GlobalController::merchant_setting_function($agent->code, $agent->master_id);
            if($merchant_setting_function != 'ok'){
                throw new \Exception($merchant_setting_function);
            }

            // $upgrade_agent_record = GlobalController::upgrade_agent_record($agent->code, $agent->lvl);
            // if($upgrade_agent_record != 'ok'){
            //     throw new \Exception($upgrade_agent_record);
            // }

            // $referral_bonus = GlobalController::referral_bonus($agent->code, $agent->lvl);
            // if($referral_bonus != 'ok'){
            //     throw new \Exception($referral_bonus);
            // }


            \DB::commit();
        }catch (\Exception $e){
            \DB::rollback();
            return Redirect::back()->withInput($request->all())->withErrors($e->getMessage());
        }catch(\Error $e){
            \DB::rollback();
            return Redirect::back()->withInput($request->all())->withErrors($e->getMessage());
        }

        Toastr::success("$agent->f_name Created!");
        return redirect()->route('merchant.merchants.index');
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
        $merchant = Merchant::select('merchants.*', DB::raw('COALESCE(m.code, a.code) AS master_code'))
                            ->leftJoin('merchants AS m', 'm.code', 'merchants.master_id')
                            ->leftJoin('admins AS a', 'a.code', 'merchants.master_id')
                            ->where('merchants.id', $id)
                            ->first();

        
        $levels = AgentLevel::select('agent_levels.*');
        if(Auth::guard('merchant')->check()){
        $levels = $levels->where('merchant_id', Auth::user()->code);
        }else{
        $levels = $levels->whereNull('merchant_id');
        }
        $levels = $levels->get();

        // $countries = TblCountry::get();
        $countries = GlobalController::global_countries();

        $states = State::get();

        return view('backend.merchants.edit', ['merchant'=>$merchant, 
                                               'levels'=>$levels, 
                                               'countries'=>$countries,
                                               'states'=>$states]);
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
            'f_name' => 'required',
            'phone' => ['required', 'unique:users', 'unique:agents', 'unique:merchants,phone,'.$id],
            'email' => ['required', 'unique:users', 'unique:agents', 'unique:merchants,email,'.$id]
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }

        try{

            \DB::beginTransaction();

            $agent = Merchant::find($id);
            
            // $level = !empty($request->lvl) ? $request->lvl : 1;

            //Agent Details
            $agent->country_code = $request->country_code;
            $agent->phone = $request->phone;
            $agent->email = $request->email;
            $agent->f_name = $request->f_name;
            $agent->description = $request->description;
            $agent->active_period = $request->active_period;
            // $agent->ic = $request->ic;
            // $agent->gender = $request->gender;
            $agent->permission_lvl = 2;

            $agent->save();

            \DB::commit();
        }catch (\Exception $e){
            \DB::rollback();
            return Redirect::back()->withInput($request->all())->withErrors($e->getMessage());
        }catch(\Error $e){
            \DB::rollback();
            return Redirect::back()->withInput($request->all())->withErrors($e->getMessage());
        }


        Toastr::success("Updated!");
        return redirect()->route('merchant.merchants.edit', $id);
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

    public function saveNewPassword(Request $request, $id)
    {
        $input = $request->all();
        $input['password'] = Hash::make($request->new_password);
        $update = Merchant::find($id);
        $update = $update->update($input);

        Toastr::success("Password Changed!");
        return redirect()->route('merchant.merchants.edit', $id);
    }

    public function ExportMerchant()
    {
        if(!empty(request('code'))){
            $code = request('code');
        }else{
            $code = "";
        }
        
        if(!empty(request('merchant_name'))){
            $merchant_name = request('merchant_name');
        }else{
            $merchant_name = "";
        }
        
        if(!empty(request('phone'))){
            $phone = request('phone');
        }else{
            $phone = "";
        }
        
        if(!empty(request('email'))){
            $email = request('email');
        }else{
            $email = "";
        }
        
        if(!empty(request('status'))){
            $status = request('status');
        }else{
            $status = "";
        }


        return Excel::download(new ExportMerchant($code, $merchant_name, $phone, $email, $status), 'MerchantList.xls');
    }
}
