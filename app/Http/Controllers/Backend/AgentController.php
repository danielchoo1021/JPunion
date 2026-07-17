<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Facades\Input;

use App\Agent;
use App\State;
use App\TblCountry;
use App\WebsiteSetting;
use App\AgentLevel;
use App\Affiliate;
use App\AdjustCashWallet;
use App\AdjustPointWallet;
use App\AgentLevelRecord;
use App\Transaction;
use App\Promotion;
use App\AdjustVoucher;
use App\AffiliateCommission;
use App\SettingRefferalReward;
use App\AdjustTopupWallet;
use App\AdjustCashToTopup;
use App\UserShippingAddress;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\GlobalController;

use App\Exports\AgentListExport;

use Maatwebsite\Excel\Facades\Excel;

use Validator, Redirect, Toastr, DB, File, Auth, Mail, DateTime;

class AgentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!empty(request('dates'))) {
            $new_dates = explode('-', request('dates'));
            $start = DateTime::createFromFormat('d/m/Y', trim($new_dates[0]))->format('Y-m-d');
            $end = DateTime::createFromFormat('d/m/Y', trim($new_dates[1]))->format('Y-m-d');

            $startDate = trim($new_dates[0]);
            $endDate = trim($new_dates[1]);
        } else {
            $ds = new DateTime("first day of this month");
            $de = new DateTime("last day of this month");

            $start = $ds->format('Y-m-d');
            $end = $de->format('Y-m-d');

            $startDate = $ds->format('d/m/Y');
            $endDate = $de->format('d/m/Y');
        }

        $agents = Agent::select('agents.*')
                             ->leftJoin('agents as upm', 'upm.code', 'agents.master_id')
                             ->leftJoin('admins as upa', 'upa.code', 'agents.master_id')
                             ->whereNotIn('agents.status', ['99', '3']);
                             //->orderBy('agents.created_at', 'desc');

        if(Auth::guard('merchant')->check()){
        $agents = $agents->where('agents.dual_master_id', Auth::user()->code);
        }

        $queries = [];
        $columns = [
            'dates',
            'code', 
            'agent_name', 
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
                if($column == 'agent_name'){
                    $agents = $agents->where(DB::raw('CONCAT(agents.f_name, " ", agents.l_name)'), 'like', "%".request($column)."%");
                }elseif($column == 'ic'){
                    $agents = $agents->where('agents.ic', 'like', "%".request($column)."%");
                }elseif($column == 'phone'){
                    $agents = $agents->where(DB::raw("CONCAT('0', agents.phone)"), 'like', "%".request($column)."%");
                }elseif($column == 'email'){
                    $agents = $agents->where('agents.email', 'like', "%".request($column)."%");
                }elseif($column == 'referrer_code'){
                    $agents = $agents->where(DB::raw('COALESCE(CONCAT(upm.display_code, upm.display_running_no), CONCAT(upa.display_code, upa.display_running_no))'), 'like', "%".request($column)."%");
                }elseif($column == 'lvl'){
                    $agents = $agents->where('agents.lvl', 'like', "%".request($column)."%");
                }elseif($column == 'referrer_name'){
                    $agents = $agents->where(
                    DB::raw('COALESCE(CONCAT(upm.f_name, " ", upm.l_name), CONCAT(upa.f_name, " ", upa.l_name)) COLLATE utf8mb4_general_ci'),
                    'like',
                    '%' . request($column) . '%'
                    );
                }elseif($column == 'code'){
                    $agents = $agents->where(DB::raw('CONCAT(agents.display_code, agents.display_running_no)'), 'like', "%".request($column)."%");
                }elseif($column == 'status'){
                    $agents = $agents->where('agents.status', 'like', "%".request($column)."%");
                }elseif($column == 'dates'){
                    $agents = $agents->whereBetween(DB::raw('DATE_FORMAT(agents.created_at, "%Y-%m-%d")'), array($start, $end));
                }elseif($column == 'code_desc'){
                    $agents = $agents->orderBy('agents.code', 'desc');
                }elseif($column == 'code_asc'){
                    $agents = $agents->orderBy('agents.code', 'asc');
                }elseif($column == 'name_desc'){
                    $agents = $agents->orderBy('agents.f_name', 'desc');
                }elseif($column == 'name_asc'){
                    $agents = $agents->orderBy('agents.f_name', 'asc');
                }elseif($column == 'lvl_desc'){
                    $agents = $agents->orderBy('agents.lvl', 'desc');
                }elseif($column == 'lvl_asc'){
                    $agents = $agents->orderBy('agents.lvl', 'asc');
                }elseif($column == 'ref_code_desc'){
                    $agents = $agents->orderBy(DB::raw('COALESCE(CONCAT(upm.display_code, upm.display_running_no), CONCAT(upa.display_code, upa.display_running_no))'), 'desc');
                }elseif($column == 'ref_code_asc'){
                    $agents = $agents->orderBy(DB::raw('COALESCE(CONCAT(upm.display_code, upm.display_running_no), CONCAT(upa.display_code, upa.display_running_no))'), 'asc');
                }elseif($column == 'ref_name_desc'){
                    $agents = $agents->orderBy('upline_name', 'desc');
                }elseif($column == 'ref_name_asc'){
                    $agents = $agents->orderBy('upline_name', 'asc');
                }elseif($column == 'email_desc'){
                    $agents = $agents->orderBy('agents.email', 'desc');
                }elseif($column == 'email_asc'){
                    $agents = $agents->orderBy('agents.email', 'asc');
                }elseif($column == 'ic_desc'){
                    $agents = $agents->orderBy('agents.ic', 'desc');
                }elseif($column == 'ic_asc'){
                    $agents = $agents->orderBy('agents.ic', 'asc');
                }elseif($column == 'phone_desc'){
                    $agents = $agents->orderBy('agents.phone', 'desc');
                }elseif($column == 'phone_asc'){
                    $agents = $agents->orderBy('agents.phone', 'asc');
                }elseif($column == 'status_desc'){
                    $agents = $agents->orderBy('agents.status', 'desc');
                }elseif($column == 'status_asc'){
                    $agents = $agents->orderBy('agents.status', 'asc');
                }elseif($column == 'company_num_desc'){
                    $agents = $agents->orderBy('agents.company_registration_no', 'desc');
                }elseif($column == 'company_num_asc'){
                    $agents = $agents->orderBy('agents.company_registration_no', 'asc');
                }elseif($column == 'acc_type_desc'){
                    $agents = $agents->orderBy('agents.company', 'desc');
                }elseif($column == 'acc_type_asc'){
                    $agents = $agents->orderBy('agents.company', 'asc');
                }elseif($column == 'account_type'){
                    if(request($column) == '1'){
                        $agents = $agents->where('agents.company', '1');
                    }elseif(request($column) == '2'){
                        $agents = $agents->whereNull('agents.company');
                    }
                }elseif($column == 'per_page'){
                    $agents = $agents->paginate($per_page);            
                }

                $queries[$column] = request($column);

            }
        }

        if(empty($_GET)) {
            $agents = $agents->orderBy('agents.code', 'desc');
        }

        // $agents = $agents->orderBy('agents.display_running_no', 'desc');
        // $agents = $agents->orderBy('agents.code', 'desc');
        // $agents = $agents->paginate($per_page)->appends($queries);
        if(!empty(request('per_page'))){ 
            $agents = $agents->appends($queries);        
        }else{
            $agents = $agents->paginate($per_page)->appends($queries);        
        }

        $get_cash_wallet_balance = [];
        $get_topup_wallet_balance = [];
        foreach($agents as $agent){
            $get_cash_wallet_balance[$agent->code] = GlobalController::get_cash_wallet_balance($agent->code);
            $get_topup_wallet_balance[$agent->code] = GlobalController::get_topup_wallet_balance($agent->code);
        }

        $agent_lvls = AgentLevel::get();
        
        return view('backend.agents.index', ['agents'=>$agents, 
                                             'agent_lvls'=>$agent_lvls,
                                             'startDate' => $startDate,
                                             'endDate' => $endDate],
                                            compact('get_cash_wallet_balance', 
                                                    'get_topup_wallet_balance'));
    }

    public function exportAgentList(){
        
        $start = "";
        $end = "";

        if (!empty(request('dates'))) {
            $new_dates = explode('-', request('dates'));
            $start = DateTime::createFromFormat('d/m/Y', trim($new_dates[0]))->format('Y-m-d');
            $end = DateTime::createFromFormat('d/m/Y', trim($new_dates[1]))->format('Y-m-d');

            $startDate = trim($new_dates[0]);
            $endDate = trim($new_dates[1]);
        }

        $agent_name = "";
        if(!empty(request('agent_name'))){
          $agent_name = request('agent_name');
        }

        $code = "";
        if(!empty(request('code'))){
          $code = request('code');
        }

        $referrer_code = "";
        if(!empty(request('referrer_code'))){
          $referrer_code = request('referrer_code');
        }

        $referrer_name = "";
        if(!empty(request('referrer_name'))){
          $referrer_name = request('referrer_name');
        }

        $phone = "";
        if(!empty(request('phone'))){
          $phone = request('phone');
        }

        $status = "";
        if(!empty(request('status'))){
          $status = request('status');
        }

        $email = "";
        if(!empty(request('email'))){
          $email = request('email');
        }

        return Excel::download(new AgentListExport($start, $end, $agent_name, $code, $referrer_code, $referrer_name,$phone,$status,$email), 'AgentList'.$start.' - '.$end.'.xlsx');
    }
    
    public function pending_agent()
    {

        $agents = Agent::select('agents.*',
                                      DB::raw('COALESCE(COALESCE(CONCAT(upm.display_code, upm.display_running_no), upa.code), upu.code) AS upline_code'),
                                      DB::raw('COALESCE(COALESCE(CONCAT(upm.f_name, " ", upm.l_name), CONCAT(upa.f_name, " ", upa.l_name)), upu.f_name) AS upline_name'))
                             ->leftJoin('agents as upm', 'upm.code', 'agents.master_id')
                             ->leftJoin('admins as upa', 'upa.code', 'agents.master_id')
                             ->leftJoin('users as upu', 'upu.code', 'agents.master_id')
                             ->where('agents.status', '99')
                             ->orderBy('agents.created_at', 'desc');
                             
        if(Auth::guard('merchant')->check()){
        $agents = $agents->where('agents.dual_master_id', Auth::user()->code);
        }

        $per_page = 10;
        if(!empty(request('per_page'))){
            $per_page = request('per_page');
        }

        $queries = [];
        $columns = [
            'agent_name', 'code', 'referral_code', 'referral_name', 'email', 'phone', 'joining_product', 'per_page'
        ];
        foreach($columns as $column){
            if(request()->has($column) && !empty(request($column))){
                if($column == 'agent_name'){
                    $agents = $agents->where(DB::raw('CONCAT(agents.f_name, " ", agents.l_name) COLLATE utf8mb4_general_ci'), 'like', "%".request($column)."%");
                }elseif($column == 'code'){
                    $agents = $agents->where(DB::raw('CONCAT(agents.display_code, agents.display_running_no) COLLATE utf8mb4_general_ci'), 'like', "%".request($column)."%");
                }elseif($column == 'referral_code'){
                    $agents = $agents->where(DB::raw('COALESCE(CONCAT(upm.display_code, upm.display_running_no), CONCAT(upa.display_code, upa.display_running_no)) COLLATE utf8mb4_general_ci'), 'like', "%".request($column)."%");
                }elseif($column == 'referral_name'){
                    // $agents = $agents->where(DB::raw('COALESCE(CONCAT(upm.f_name, " ", upm.l_name), CONCAT(upa.f_name, " ", upa.l_name))'), 'like', "%".request($column)."%");
                    $agents = $agents->where(DB::raw('COALESCE(COALESCE(CONCAT(upm.f_name, " ", upm.l_name), CONCAT(upa.f_name, " ", upa.l_name)), upu.f_name) COLLATE utf8mb4_general_ci'), 'like', "%".request($column)."%");
                }elseif($column == 'email'){
                    $agents = $agents->where('agents.email', 'like', "%".request($column)."%");
                }elseif($column == 'phone'){
                    $agents = $agents->where('agents.phone', 'like', "%".request($column)."%");
                }elseif($column == 'joining_product'){
                    $agents = $agents->where('agents.register_transaction', 'like', "%".request($column)."%");
                }elseif($column == 'per_page'){
                    $agents = $agents->paginate($per_page);            
                }

                $queries[$column] = request($column);
            }
        }
        
        // $agents = $agents->paginate($per_page)->appends($queries);
        if(!empty(request('per_page'))){ 
            $agents = $agents->appends($queries);        
        }else{
            $agents = $agents->paginate($per_page)->appends($queries);        
        }

        $transaction_bank_slip = [];
        foreach($agents as $agent){
            $transaction_bank_slip[$agent->code] = Transaction::where('transaction_no', $agent->register_transaction)
                                                                ->orderBy('created_at', 'asc')
                                                                ->first();
        }

        return view('backend.agents.pending', ['agents'=>$agents], compact('transaction_bank_slip'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function create()
    {
        $authorise_merchant = !empty($_COOKIE['vmerchant']) ? $_COOKIE['vmerchant'] : '';
        $get_authorise_status = GlobalController::check_autorize_status($authorise_merchant);

        $levels = AgentLevel::where('status', '1')->where('admin_default',1);
        if($get_authorise_status['status'] == 1 && Auth::guard('merchant')->check()){
            $levels = $levels->where('merchant_id', $get_authorise_status['result']['code']);
        } else {
            $levels = $levels->whereNull('merchant_id');
        }
        $levels = $levels->get();

        $agents = Agent::where('status', '1');
        if(Auth::guard('merchant')->check()){
            $agents = $agents->where('dual_master_id', Auth::user()->code);
        }
        $agents = $agents->get();

        $countries = GlobalController::global_countries();
        $states = State::get();

        return view('backend.agents.create', [
            'countries' => $countries, 
            'levels' => $levels,
            'states' => $states,
            'agents' => $agents
        ]);
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

        $messages = [
            'required' => $translation_data['backendlang']['backendlang']['The_:attribute_field_is_required'] ?? 'The :attribute field is required.',
            'min' => $translation_data['backendlang']['backendlang']['The_:attribute_must_be_at_least_:min_characters'] ?? 'The :attribute must be at least :min characters.',
            'email' => $translation_data['backendlang']['backendlang']['The_:attribute_must_be_a_valid_email_address'] ?? 'The :attribute must be a valid email address.',
            'unique' => $translation_data['backendlang']['backendlang']['The_:attribute_has_already_been_taken'] ?? 'The :attribute has already been taken.'
        ];

        $attributes = [
            'f_name' => $translation_data['backendlang']['backendlang']['Full_Name'] ?? 'Full Name',
            'phone' => $translation_data['backendlang']['backendlang']['Phone_Number'] ?? 'Phone',
            'email' => $translation_data['backendlang']['backendlang']['Email'] ?? 'Email',
            'ic' => $translation_data['backendlang']['backendlang']['NRIC_no'] ?? 'NRIC no',
            'password' => $translation_data['backendlang']['backendlang']['Password'] ?? 'Password',
            'dob' => $translation_data['backendlang']['backendlang']['date_of_birth'] ?? 'Date of Birth'
        ];

        $validator = Validator::make($request->all(), [
            'f_name' => 'required',
            'phone' => ['required', 'unique:users', 'unique:merchants', 'unique:agents'],
            'email' => ['required', 'unique:users', 'unique:merchants', 'unique:agents'],
            'ic' => ['required', 'unique:users', 'unique:merchants', 'unique:agents'],
            'password' => ['required', 'min:6'],
            'dob' => 'required'
        ], $messages, $attributes);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }

        try{

            \DB::beginTransaction();

            if(!empty($request->agent_pno)){
                $get_upline = Agent::where(DB::raw("CONCAT(display_code, display_running_no)"), $request->agent_pno)->first();
                if(empty($get_upline->id)){
                    throw new \Exception("Referral Code Not Exists");
                }

                if(!empty($get_upline->id)){
                    $master_id = $get_upline->code;
                }
            }else{
                $master_id = "AD000001";
            }

            // Check phone number
            $phone = $request->phone;
            if(substr($phone, 0, 1) == '0'){
                $phone = substr($phone, 1);

                $agent = Agent::where('phone', $phone)->first();

                if(!empty($agent->id)){
                    return Redirect::back()->withInput($request->all())->withErrors('The phone has already been taken.');
                }
            }

            $agent_display_code = GlobalController::AgentDisplayCode();

            $agent = new Agent();
            $level = !empty($request->lvl) ? $request->lvl : 1;
            //Agent Details

            if(Auth::guard('merchant')->check()){
            $agent->dual_master_id = Auth::user()->code;
            }
            $agent->master_id = $master_id;
            $agent->code = GlobalController::AgentCode();
            $agent->display_code = $agent_display_code[0];
            $agent->display_running_no = $agent_display_code[1];
            $agent->country_code = $request->country_code;
            // $agent->phone = $request->phone;
            $agent->phone = $phone;
            $agent->email = $request->email;
            $agent->password = Hash::make($request->password);
            $agent->f_name = $request->f_name;
            $agent->ic = $request->ic;
            $agent->gender = $request->gender;
            $agent->dob = $request->dob;
            $agent->lvl = $level;

            $agent->save();

            UserShippingAddress::create([
                'user_id' => $agent->code,
                'f_name' => $request->f_name,
                'email' => $request->email,
                'phone' => $request->phone,   
                'country_code' => $request->country_code,
                'address' => $request->address,
                'city' => $request->city,
                'postcode' => $request->postcode,
                'state' => $request->state,
                'country' => $request->country,
                'default' => 1,
            ]);

            $add_affiliates = GlobalController::add_affiliates($agent->code, $agent->master_id);
            if($add_affiliates != 'ok'){
                throw new \Exception($add_affiliates);
            }

            $upgrade_agent_record = GlobalController::upgrade_agent_record($agent->code, $agent->lvl);
            if($upgrade_agent_record != 'ok'){
                throw new \Exception($upgrade_agent_record);
            }

            $referral_bonus = GlobalController::referral_bonus($agent->code, $agent->lvl);
            if($referral_bonus != 'ok'){
                throw new \Exception($referral_bonus);
            }


            \DB::commit();
        }catch (\Exception $e){
            \DB::rollback();
            return Redirect::back()->withInput($request->all())->withErrors($e->getMessage());
        }catch(\Error $e){
            \DB::rollback();
            return Redirect::back()->withInput($request->all())->withErrors($e->getMessage());
        }

        Toastr::success("$agent->f_name Created!");
        return redirect()->route('agent.agents.index');
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
        $agent = Agent::where('agents.id', $id)->first();
        $defaultAddress = $agent->get_default_shipping_address;

        $authorise_merchant = !empty($_COOKIE['vmerchant']) ? $_COOKIE['vmerchant'] : '';
        $get_authorise_status = GlobalController::check_autorize_status($authorise_merchant);
        
        $levels = AgentLevel::where('status', '1')->where('admin_default',1);
        if($get_authorise_status['status'] == 1){
        $levels = $levels->where('merchant_id', $get_authorise_status['result']['code']);
        }
        $levels = $levels->get();

        // $countries = TblCountry::get();
        $countries = GlobalController::global_countries();

        $states = State::get();

        return view('backend.agents.edit', ['agent'=>$agent, 
                                            'levels'=>$levels, 
                                            'countries'=>$countries,
                                            'states'=>$states,
                                            'defaultAddress' => $defaultAddress,]);
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

        $messages = [
            'required' => $translation_data['backendlang']['backendlang']['The_:attribute_field_is_required'] ?? 'The :attribute field is required.',
            'min' => $translation_data['backendlang']['backendlang']['The_:attribute_must_be_at_least_:min_characters'] ?? 'The :attribute must be at least :min characters.',
            'email' => $translation_data['backendlang']['backendlang']['The_:attribute_must_be_a_valid_email_address'] ?? 'The :attribute must be a valid email address.',
            'unique' => $translation_data['backendlang']['backendlang']['The_:attribute_has_already_been_taken'] ?? 'The :attribute has already been taken.'
        ];

        $attributes = [
            'f_name' => $translation_data['backendlang']['backendlang']['Full_Name'] ?? 'Full Name',
            'phone' => $translation_data['backendlang']['backendlang']['Phone_Number'] ?? 'Phone',
            'email' => $translation_data['backendlang']['backendlang']['Email'] ?? 'Email',
            'ic' => $translation_data['backendlang']['backendlang']['NRIC_no'] ?? 'NRIC no',
            'dob' => $translation_data['backendlang']['backendlang']['date_of_birth'] ?? 'Date of Birth'
        ];

        $validator = Validator::make($request->all(), [
            'f_name' => 'required',
            'phone' => ['required', 'unique:agents,phone,'.$id, 'unique:merchants', 'unique:users'],
            'email' => ['required', 'unique:agents,email,'.$id, 'unique:merchants', 'unique:users'],
            'ic' => ['required', 'unique:agents,ic,'.$id, 'unique:merchants', 'unique:users'],
            'dob' => 'required'
        ], $messages, $attributes);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }

        // Check phone number
        $phone = $request->phone;
        if(substr($phone, 0, 1) == '0'){
            $phone = substr($phone, 1);

            $agent = Agent::where('phone', $phone)->whereNot('id', $id)->first();

            if(!empty($agent->id)){
                $errorMsg = $translation_data['backendlang']['backendlang']['The phone has already been taken'] ?? 'The phone has already been taken.';
                return Redirect::back()->withInput($request->all())->withErrors($errorMsg);
            }
        }

        try{
            \DB::beginTransaction();

            $agent = Agent::find($id);
            
            $level = !empty($request->lvl) ? $request->lvl : 1;

            //Agent Details
            $agent->country_code = $request->country_code;
            // $agent->phone = $request->phone;
            $agent->phone = $phone;
            $agent->email = $request->email;
            $agent->f_name = $request->f_name;
            $agent->ic = $request->ic;
            $agent->lvl = $level;
            $agent->gender = $request->gender;
            $agent->dob = $request->dob;

            $agent->save();

            UserShippingAddress::where('user_id', $agent->code)
                ->update(['default' => 0]);

            UserShippingAddress::updateOrCreate(
                ['user_id' => $agent->code],
                [
                    'address' => $request->address,
                    'city' => $request->city,
                    'postcode' => $request->postcode,
                    'state' => $request->state,
                    'country' => $request->country,
                    'default' => 1,
                ]
            );

            $upgrade_agent_record = GlobalController::upgrade_agent_record($agent->code, $agent->lvl);
            if($upgrade_agent_record != 'ok'){
                throw new \Exception($upgrade_agent_record);
            }

            \DB::commit();
        }catch (\Exception $e){
            \DB::rollback();
            return Redirect::back()->withInput($request->all())->withErrors($e->getMessage());
        }catch(\Error $e){
            \DB::rollback();
            return Redirect::back()->withInput($request->all())->withErrors($e->getMessage());
        }


        Toastr::success(($translation_data['backendlang']['backendlang']['Updated'] ?? "Updated") . "!");
        return redirect()->route('agent.agents.edit', $id);
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

    public function saveAgentNewPassword(Request $request, $id)
    {
        $input = $request->all();
        $input['password'] = Hash::make($request->new_password);
        $update = Agent::find($id);
        $update = $update->update($input);

        Toastr::success("Password Changed!");
        return redirect()->route('agent.agents.edit', $id);
    }

    public function tree($agent_code)
    {
        $agent = Agent::where('code', $agent_code)->first();
        $admin = Agent::where('code', $agent_code)->first();

        $agentD = Agent::where('master_id', $agent_code)
                             ->where('status', '1')
                             ->get();

        $mdd = [];
        $mddd = [];
        $mdddd = [];
        $sg  = 0;
        $tg  = 0;
        $tfg = 0;
        foreach($agentD as $agentdv){
            $mdd[$agentdv->code] = Agent::where('master_id', $agentdv->code)->where('status', '1')->get();
            $sg += count($mdd[$agentdv->code]);

            foreach($mdd[$agentdv->code] as $mddv){
                $mddd[$mddv->code] = Agent::where('master_id', $mddv->code)->where('status', '1')->get();
                $tg += count($mddd[$mddv->code]);

                foreach($mddd[$mddv->code] as $mdddv){
                    $mdddd[$mdddv->code] = Agent::where('master_id', $mdddv->code)->where('status', '1')->get();
                    $tfg += count($mdddd[$mdddv->code]);
                }
            }
        }

        $fg = count($agentD);
        

        $total = $fg + $sg + $tg + $tfg;
        // echo $tg;
        if($total > 0){
            $fgp = round($fg / $total * 100, 2);
            $sgp = round($sg / $total * 100, 2);
            $tgp = round($tg / $total * 100, 2);
            $tfgp = round($tfg / $total * 100, 2);
        }else{
            $fgp = 0;
            $sgp = 0;
            $tgp = 0;
            $tfgp = 0;
        }


        return view('backend.agents.tree', ['agentD'=>$agentD, 'agent'=>$agent,
                                            'fg'=>$fg, 'fgp'=>$fgp,
                                            'sg'=>$sg, 'sgp'=>$sgp,
                                            'tg'=>$tg, 'tgp'=>$tgp,
                                            'tfg'=>$tfg, 'tfgp'=>$tfgp], compact('mdd', 'mddd', 'mdddd',));
    }

    public function tree_details(Request $request, $agent_code, $g)
    {
        if(!empty($g) && $g <= 4 && $g > 0){
            if($g == '1'){
                $generation = "1st";
            }elseif($g == '2'){
                $generation = "2nd";
            }elseif($g == '3'){
                $generation = "3th";
            }else{
                $generation = "4th";
            }            
        }else{
            abort(404);
        }


        if($g == 1){
            $agents = Agent::where('master_id', $agent_code)
                                 ->where(function ($query) {
                                    $query->where('status', '1')
                                          ->orWhere('status', '2');
                                });
        }elseif($g == 2){
            $agents = Agent::select('d.*')
                                 ->join('agents AS d', 'd.master_id', 'agents.code')
                                 ->where('agents.master_id', $agent_code)
                                 ->where(function ($query) {
                                     $query->where('d.status', '1')
                                           ->orwhere('d.status', '2');
                                    });

        }elseif($g == 3){
            $agents = Agent::select('dd.*')
                                 ->join('agents AS d', 'd.master_id', 'agents.code')
                                 ->join('agents AS dd', 'dd.master_id', 'd.code')
                                 ->where('agents.master_id', $agent_code)
                                 ->where(function ($query) {
                                    $query->where('dd.status', '1')
                                          ->orwhere('dd.status', '2');
                                });
        }else{
            $agents = Agent::select('ddd.*')
                                 ->join('agents AS d', 'd.master_id', 'agents.code')
                                 ->join('agents AS dd', 'dd.master_id', 'd.code')
                                 ->join('agents AS ddd', 'ddd.master_id', 'dd.code')
                                 ->where('agents.master_id', $agent_code)
                                 ->where(function ($query){
                                    $query->where('ddd.status', '1')
                                          ->orwhere('ddd.status', '2');
                                 });
        }

        $queries = [];
        $columns = [
            'code', 
            'agent_name', 
            'status', 
            'code_desc', 
            'code_asc', 
            'name_desc', 
            'name_asc', 
            'lvl_desc', 
            'lvl_asc', 
            'per_page'
        ];

        $per_page = 10;
        if(!empty(request('per_page'))){
            $per_page = request('per_page');
        }

        foreach($columns as $column){
            if(request()->has($column) && !empty(request($column))){
                if($column == 'agent_name'){
                    if($g == 1){
                        $agents = $agents->where(DB::raw('CONCAT(agents.f_name, " ", agents.l_name)'), 'like', "%".request($column)."%");
                    }elseif($g == 2){
                        $agents = $agents->where(DB::raw('CONCAT(d.f_name, " ", d.l_name)'), 'like', "%".request($column)."%");
                    }elseif($g == 3){
                        $agents = $agents->where(DB::raw('CONCAT(dd.f_name, " ", dd.l_name)'), 'like', "%".request($column)."%");
                    }else{
                        $agents = $agents->where(DB::raw('CONCAT(ddd.f_name, " ", ddd.l_name)'), 'like', "%".request($column)."%");
                    }
                }elseif($column == 'code'){
                    if($g == 1){
                        $agents = $agents->where(DB::raw('agents.code'), 'like', "%".request($column)."%");
                    }elseif($g == 2){
                        $agents = $agents->where(DB::raw('d.code'), 'like', "%".request($column)."%");
                    }elseif($g == 3){
                        $agents = $agents->where(DB::raw('dd.code'), 'like', "%".request($column)."%");
                    }else{
                        $agents = $agents->where(DB::raw('ddd.code'), 'like', "%".request($column)."%");
                    }
                }elseif($column == 'status'){
                    if($g == 1){
                        $agents = $agents->where('agents.status', 'like', "%".request($column)."%");   
                    }elseif($g == 2){
                        $agents = $agents->where('d.status', 'like', "%".request($column)."%");
                    }elseif($g == 3){
                        $agents = $agents->where('dd.status', 'like', "%".request($column)."%");
                    }else{
                        $agents = $agents->where('ddd.status', 'like', "%".request($column)."%");
                    }
                }elseif($column == 'code_desc'){
                    if($g == 1){
                        $agents = $agents->orderBy('agents.code', 'desc');   
                    }elseif($g == 2){
                        $agents = $agents->orderBy('d.code', 'desc');
                    }elseif($g == 3){
                        $agents = $agents->orderBy('dd.code', 'desc');
                    }else{
                        $agents = $agents->orderBy('ddd.code', 'desc');
                    }
                }elseif($column == 'code_asc'){
                    if($g == 1){
                        $agents = $agents->orderBy('agents.code', 'asc');   
                    }elseif($g == 2){
                        $agents = $agents->orderBy('d.code', 'asc');
                    }elseif($g == 3){
                        $agents = $agents->orderBy('dd.code', 'asc');
                    }else{
                        $agents = $agents->orderBy('ddd.code', 'asc');
                    }
                }elseif($column == 'name_desc'){
                    if($g == 1){
                        $agents = $agents->orderBy('agents.f_name', 'desc');   
                    }elseif($g == 2){
                        $agents = $agents->orderBy('d.f_name', 'desc');
                    }elseif($g == 3){
                        $agents = $agents->orderBy('dd.f_name', 'desc');
                    }else{
                        $agents = $agents->orderBy('ddd.f_name', 'desc');
                    }
                }elseif($column == 'name_asc'){
                    if($g == 1){
                        $agents = $agents->orderBy('agents.f_name', 'asc');   
                    }elseif($g == 2){
                        $agents = $agents->orderBy('d.f_name', 'asc');
                    }elseif($g == 3){
                        $agents = $agents->orderBy('dd.f_name', 'asc');
                    }else{
                        $agents = $agents->orderBy('ddd.f_name', 'asc');
                    }
                }elseif($column == 'lvl_desc'){
                    if($g == 1){
                        $agents = $agents->orderBy('agents.lvl', 'desc');   
                    }elseif($g == 2){
                        $agents = $agents->orderBy('d.lvl', 'desc');
                    }elseif($g == 3){
                        $agents = $agents->orderBy('dd.lvl', 'desc');
                    }else{
                        $agents = $agents->orderBy('ddd.lvl', 'desc');
                    }
                }elseif($column == 'lvl_asc'){
                    if($g == 1){
                        $agents = $agents->orderBy('agents.lvl', 'asc');   
                    }elseif($g == 2){
                        $agents = $agents->orderBy('d.lvl', 'asc');
                    }elseif($g == 3){
                        $agents = $agents->orderBy('dd.lvl', 'asc');
                    }else{
                        $agents = $agents->orderBy('ddd.lvl', 'asc');
                    }
                }elseif(request($column) == 'per_page'){
                    $agents = $agents->paginate($per_page);            
                }

                $queries[$column] = request($column);

            }
        }
        $agents = $agents->orderBy('agents.code', 'desc');
        $agents = $agents->paginate($per_page)->appends($queries);

        return view('backend.agents.tree_details', [
            'generation'=>$generation, 
            'merchants'=>$agents, 
            'agent_code' => $agent_code, 
            'g' => $g
        ]);
    }

    public function AdjustCash($id)
    {
        $agent = Agent::find($id);
        if(!isset($agent) && empty($agent)){
            abort(404);
        }

        $adjusts = AdjustCashWallet::where('user_id', $agent->code)
                         ->where('status', '1')
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
                    $adjusts = $adjusts->where($column, ''.request($column).'');
                }else{
                    $adjusts = $adjusts->where($column, 'like', "%".request($column)."%");
                }

                $queries[$column] = request($column);

            }
        }

        $GetCashWalletBalance = GlobalController::get_cash_wallet_balance($agent->code);

        $adjusts = $adjusts->paginate($itemPerPage)->appends($queries);

        return view('backend.agents.adjustCash', ['agent'=>$agent, 'adjusts'=>$adjusts, 
                                               'GetCashWalletBalance'=>$GetCashWalletBalance]);
    }

    public function SubmitAdjustCash(Request $request, $id)
    {
        $translation_data = GlobalController::get_translations();
        $agent = Agent::find($id);
        $GetCashWalletBalance = GlobalController::get_cash_wallet_balance($agent->code);

        $validator = Validator::make($request->all(), [
            'adjust_type' => 'required',
            'adjust_amount' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }


        if($GetCashWalletBalance < $request->adjust_amount && $request->adjust_type == '2'){
            $errorMsg = $translation_data['backendlang']['backendlang']['Amount Exceed! Amount More Than Balance.'] ?? 'Amount Exceed! Amount More Than Balance.';
            return Redirect::back()->withInput($request->all())->withErrors($errorMsg);
        }

        $input = [];
        $input['user_id'] = $agent->code;
        $input['amount'] = preg_replace("/[^0-9\.]/", '', $request->adjust_amount);
        $input['type'] = $request->adjust_type;
        $input['remark'] = $request->remark;
        $input['created_by'] = Auth::user()->code;
        $input['updated_by'] = Auth::user()->code;

        $AdjustCashWallet = AdjustCashWallet::create($input);

        Toastr::success($request->type . " " . ($translation_data['backendlang']['backendlang']['Create_Successfully'] ?? ''));
        return redirect()->route('adjustCash', $id);
    }

    public function adjustPoint($id){
        $agent = Agent::find($id);
        if(!isset($agent) && empty($agent)){
            abort(404);
        }

        $adjusts = AdjustPointWallet::where('user_id', $agent->code)
                         ->where('status', '1')
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
                    $adjusts = $adjusts->where($column, ''.request($column).'');
                }else{
                    $adjusts = $adjusts->where($column, 'like', "%".request($column)."%");
                }

                $queries[$column] = request($column);

            }
        }

        $GetCashWalletBalance = GlobalController::get_point_wallet($agent->code);

        $adjusts = $adjusts->paginate($itemPerPage)->appends($queries);

        return view('backend.agents.adjustPoint', ['agent'=>$agent, 'adjusts'=>$adjusts, 
                                               'GetCashWalletBalance'=>$GetCashWalletBalance]);
    }

    public function SubmitAdjustPoint(Request $request, $id)
    {
        
        $translation_data = GlobalController::get_translations();
        $GetCashWalletBalance = GlobalController::get_point_wallet($agent->code);

        $validator = Validator::make($request->all(), [
            'adjust_type' => 'required',
            'adjust_amount' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }


        if($GetCashWalletBalance < $request->adjust_amount && $request->adjust_type == '2'){
            $errorMsg = $translation_data['backendlang']['backendlang']['Amount Exceed! Amount More Than Balance.'] ?? 'Amount Exceed! Amount More Than Balance.';
            return Redirect::back()->withInput($request->all())->withErrors($errorMsg);
        }

        $input = [];
        $input['user_id'] = $agent->code;
        $input['amount'] = preg_replace("/[^0-9\.]/", '', $request->adjust_amount);
        $input['type'] = $request->adjust_type;
        $input['remark'] = $request->remark;
        $input['created_by'] = Auth::user()->code;
        $input['updated_by'] = Auth::user()->code;

        $AdjustTopupWallet = AdjustPointWallet::create($input);

        Toastr::success($request->type . " " . ($translation_data['backendlang']['backendlang']['Create_Successfully'] ?? ''));
        return redirect()->route('adjustPoint', $id);
    }

    public function AdjustTopup($id)
    {
        $agent = Agent::find($id);
        if(!isset($agent) && empty($agent)){
            abort(404);
        }

        $adjusts = AdjustTopupWallet::where('user_id', $agent->code)
                         ->where('status', '1')
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
                    $adjusts = $adjusts->where($column, ''.request($column).'');
                }else{
                    $adjusts = $adjusts->where($column, 'like', "%".request($column)."%");
                }

                $queries[$column] = request($column);

            }
        }

        $GetCashWalletBalance = GlobalController::get_topup_wallet_balance($agent->code);

        $adjusts = $adjusts->paginate($itemPerPage)->appends($queries);

        return view('backend.agents.adjustTopup', ['agent'=>$agent, 'adjusts'=>$adjusts, 
                                               'GetCashWalletBalance'=>$GetCashWalletBalance]);
    }

    public function SubmitAdjustTopup(Request $request, $id)
    {
        $translation_data = GlobalController::get_translations();
        $agent = Agent::find($id);
        $GetCashWalletBalance = GlobalController::get_topup_wallet_balance($agent->code);

        $validator = Validator::make($request->all(), [
            'adjust_type' => 'required',
            'adjust_amount' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }


        if($GetCashWalletBalance < $request->adjust_amount && $request->adjust_type == '2'){
            $errorMsg = $translation_data['backendlang']['backendlang']['Amount Exceed! Amount More Than Balance.'] ?? 'Amount Exceed! Amount More Than Balance.';
            return Redirect::back()->withInput($request->all())->withErrors($errorMsg);
        }

        $input = [];
        $input['user_id'] = $agent->code;
        $input['amount'] = preg_replace("/[^0-9\.]/", '', $request->adjust_amount);
        $input['type'] = $request->adjust_type;
        $input['remark'] = $request->remark;
        $input['created_by'] = Auth::user()->code;
        $input['updated_by'] = Auth::user()->code;

        $AdjustTopupWallet = AdjustTopupWallet::create($input);

        Toastr::success($request->type . " " . ($translation_data['backendlang']['backendlang']['Create_Successfully'] ?? ''));
        return redirect()->route('AdjustTopup', $id);
    }

    public function agent_wallet()
    {
        $agents = Agent::select('agents.*')
                             ->leftJoin('agents as upm', 'upm.code', 'agents.master_id')
                             ->leftJoin('admins as upa', 'upa.code', 'agents.master_id')
                             ->whereNotIn('agents.status', ['99', '3']);
                            // ->orderBy('agents.created_at', 'desc');

        if(Auth::guard('merchant')->check()){
        $agents = $agents->where('agents.dual_master_id', Auth::user()->code);
        }

        $queries = [];
        $columns = [
            'code', 
            'agent_name', 
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
                if($column == 'agent_name'){
                    $agents = $agents->where(DB::raw('CONCAT(agents.f_name, " ", agents.l_name)'), 'like', "%".request($column)."%");
                }elseif($column == 'ic'){
                    $agents = $agents->where('agents.ic', 'like', "%".request($column)."%");
                }elseif($column == 'phone'){
                    $agents = $agents->where('agents.phone', 'like', "%".request($column)."%");
                }elseif($column == 'email'){
                    $agents = $agents->where('agents.email', 'like', "%".request($column)."%");
                }elseif($column == 'referrer_code'){
                    $agents = $agents->where(DB::raw('COALESCE(CONCAT(upm.display_code, upm.display_running_no), CONCAT(upa.display_code, upa.display_running_no))'), 'like', "%".request($column)."%");
                }elseif($column == 'lvl'){
                    $agents = $agents->where('agents.lvl', 'like', "%".request($column)."%");
                }elseif($column == 'referrer_name'){
                    $agents = $agents->where(DB::raw('COALESCE(CONCAT(upm.f_name, " ", upm.l_name), CONCAT(upa.f_name, " ", upa.l_name))'), 'like', "%".request($column)."%");
                }elseif($column == 'code'){
                    $agents = $agents->where(DB::raw('CONCAT(agents.display_code, agents.display_running_no)'), 'like', "%".request($column)."%");
                }elseif($column == 'status'){
                    $agents = $agents->where('agents.status', 'like', "%".request($column)."%");
                }elseif($column == 'code_desc'){
                    $agents = $agents->orderBy('agents.code', 'desc');
                }elseif($column == 'code_asc'){
                    $agents = $agents->orderBy('agents.code', 'asc');
                }elseif($column == 'name_desc'){
                    $agents = $agents->orderBy('agents.f_name', 'desc');
                }elseif($column == 'name_asc'){
                    $agents = $agents->orderBy('agents.f_name', 'asc');
                }elseif($column == 'lvl_desc'){
                    $agents = $agents->orderBy('agents.lvl', 'desc');
                }elseif($column == 'lvl_asc'){
                    $agents = $agents->orderBy('agents.lvl', 'asc');
                }elseif($column == 'ref_code_desc'){
                    $agents = $agents->orderBy('upline_code', 'desc');
                }elseif($column == 'ref_code_asc'){
                    $agents = $agents->orderBy('upline_code', 'asc');
                }elseif($column == 'ref_name_desc'){
                    $agents = $agents->orderBy('upline_name', 'desc');
                }elseif($column == 'ref_name_asc'){
                    $agents = $agents->orderBy('upline_name', 'asc');
                }elseif($column == 'email_desc'){
                    $agents = $agents->orderBy('agents.email', 'desc');
                }elseif($column == 'email_asc'){
                    $agents = $agents->orderBy('agents.email', 'asc');
                }elseif($column == 'ic_desc'){
                    $agents = $agents->orderBy('agents.ic', 'desc');
                }elseif($column == 'ic_asc'){
                    $agents = $agents->orderBy('agents.ic', 'asc');
                }elseif($column == 'phone_desc'){
                    $agents = $agents->orderBy('agents.phone', 'desc');
                }elseif($column == 'phone_asc'){
                    $agents = $agents->orderBy('agents.phone', 'asc');
                }elseif($column == 'status_desc'){
                    $agents = $agents->orderBy('agents.status', 'desc');
                }elseif($column == 'status_asc'){
                    $agents = $agents->orderBy('agents.status', 'asc');
                }elseif($column == 'company_num_desc'){
                    $agents = $agents->orderBy('agents.company_registration_no', 'desc');
                }elseif($column == 'company_num_asc'){
                    $agents = $agents->orderBy('agents.company_registration_no', 'asc');
                }elseif($column == 'acc_type_desc'){
                    $agents = $agents->orderBy('agents.company', 'desc');
                }elseif($column == 'acc_type_asc'){
                    $agents = $agents->orderBy('agents.company', 'asc');
                }elseif($column == 'account_type'){
                    if(request($column) == '1'){
                        $agents = $agents->where('agents.company', '1');
                    }elseif(request($column) == '2'){
                        $agents = $agents->whereNull('agents.company');
                    }
                }elseif(request($column) == 'per_page'){
                    $agents = $agents->paginate($per_page);            
                }

                $queries[$column] = request($column);

            }
        }
        // $agents = $agents->orderBy('agents.display_running_no', 'desc');
        $agents = $agents->orderBy('agents.code', 'desc');
        $agents = $agents->paginate($per_page)->appends($queries);

        $get_cash_wallet_balance = [];
        $get_topup_wallet_balance = [];
        $get_point_wallet_balance = [];
        foreach($agents as $agent){
            $get_cash_wallet_balance[$agent->code] = GlobalController::get_cash_wallet_balance($agent->code);
            $get_topup_wallet_balance[$agent->code] = GlobalController::get_topup_wallet_balance($agent->code);
            $get_point_wallet_balance[$agent->code] = GlobalController::get_point_wallet($agent->code);
            
        }
        
        $agent_lvls = AgentLevel::get();

        return view('backend.agents.wallet', ['agents'=>$agents, 
                                              'agent_lvls'=>$agent_lvls], 
                                             compact('get_cash_wallet_balance','get_point_wallet_balance',
                                                     'get_topup_wallet_balance'));
    }

    public function TransferCashToTopup($id)
    {
        $agent = Agent::find($id);
        if(!isset($agent) && empty($agent)){
            abort(404);
        }

        $adjusts = AdjustCashToTopup::select('adjust_cash_to_topup.*', 'transfer_to_agent.f_name as transfer_to_agent_name')
                         ->leftJoin('agents as transfer_to_agent', 'transfer_to_agent.code', 'adjust_cash_to_topup.user_id')
                         ->where('adjust_cash_to_topup.user_by', $agent->code)
                         ->where('adjust_cash_to_topup.status', '1')
                         ->orderBy('adjust_cash_to_topup.created_at', 'desc');

        $itemPerPage = 10;
        if(request()->has('per_page') && !empty(request('per_page'))){
            $itemPerPage = request('per_page');
        }   
        $queries = [];
        $columns = [
            'status'
        ];

        foreach($columns as $column){
            if(request()->has($column) && !empty(request($column))){
                
                if($column == 'status'){
                    $adjusts = $adjusts->where($column, ''.request($column).'');
                }else{
                    $adjusts = $adjusts->where($column, 'like', "%".request($column)."%");
                }

                $queries[$column] = request($column);

            }
        }

        $GetCashWalletBalance = GlobalController::get_cash_wallet_balance($agent->code);

        $GetTopupWalletBalance = GlobalController::get_topup_wallet_balance($agent->code);

        $adjusts = $adjusts->paginate($itemPerPage)->appends($queries);

        $direct_downlines = Affiliate::select('a.*')
                                    ->join('agents as a', 'a.code', 'affiliates.affiliate_id')
                                    ->where('affiliates.sort_level', '1')
                                    ->where('affiliates.user_id', $agent->code)
                                    ->get();

        return view('backend.agents.transfer_cash_to_topup', ['agent'=>$agent, 'adjusts'=>$adjusts, 'GetCashWalletBalance'=>$GetCashWalletBalance, 'GetTopupWalletBalance'=>$GetTopupWalletBalance, 'direct_downlines'=>$direct_downlines]);
    }

    public function SubmitTransferCashToTopup(Request $request, $id)
    {
        $translation_data = GlobalController::get_translations();
        try {
            \DB::beginTransaction();

            $current_agent = Agent::find($id);

            $GetCashWalletBalance = GlobalController::get_cash_wallet_balance($current_agent->code);

            $GetTopupWalletBalance = GlobalController::get_topup_wallet_balance($current_agent->code);

            $validator = Validator::make($request->all(), [
                'adjust_amount' => 'required',
            ]);

            if ($validator->fails()) {
                return Redirect::back()->withInput($request->all())->withErrors($validator);
            }

            if($GetCashWalletBalance < $request->adjust_amount){
                $errorMsg = $translation_data['backendlang']['backendlang']['Amount Exceed! Amount More Than Cash Balance.'] ?? 'Amount Exceed! Amount More Than Cash Balance.';
                return Redirect::back()->withInput($request->all())->withErrors($errorMsg);
            }

            $insert = new AdjustCashToTopup();
            $insert->user_id = $request->user_id;
            $insert->user_by = $current_agent->code;
            $insert->amount = preg_replace("/[^0-9\.]/", '', $request->adjust_amount);
            $insert->remark = $request->remark;
            
            $insert->save();

            \DB::commit();
        } catch (\Exception $e){
            \DB::rollback();
            Toastr::error($e->getMessage().' - '.$e->getLine());
            return Redirect::back()->withInput($request->all())->withErrors($e->getMessage());
        } catch (\Error $e){
            \DB::rollback();
            Toastr::error($e->getMessage().' - '.$e->getLine());
            return Redirect::back()->withInput($request->all())->withErrors($e->getMessage());
        }

        Toastr::success($translation_data['backendlang']['backendlang']['Transferred Successfully'] ?? "Transferred Successfully" . "!");
        return redirect()->route('TransferCashToTopup', $id);
    }
}
