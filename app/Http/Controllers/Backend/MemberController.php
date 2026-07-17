<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// use Illuminate\Support\Facades\Input;
use App\Admin;
use App\Merchant;
use App\User;
use App\Staff;
use App\Affiliate;
use App\VerifyCode;
use App\State;
use App\SettingRefferalReward;
use App\AgentLevel;
use App\SettingMerchantBonus;
use App\AffiliateCommission;
use App\AgentRebateHistory;
use App\Transaction;
use App\MemberWallet;
use App\AffiliateDual;
use App\UserShippingAddress;
use App\SettingNewCustomerPromotion;
use App\Promotion;
use App\AppliedPromotion;
use App\TblCountry;
use App\AdjustCashWallet;
use App\AdjustPointWallet;
use App\AdjustTopupWallet;
use App\Agent;

use App\Http\Controllers\GlobalController;

use Validator, Redirect, Toastr, DB, File, Auth, Hash, DateTime;

class MemberController extends Controller
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

        $users = User::select('users.*')
                     ->leftJoin('users as upu', 'upu.code', 'users.master_id')
                     ->leftJoin('merchants as upm', 'upm.code', 'users.master_id')
                     ->leftJoin('admins as upa', 'upa.code', 'users.master_id')
                     ->leftJoin('agents as agt', 'agt.code', 'users.master_id')                                          
                     ->where('users.status', '!=', '3');
                     //->orderBy('created_at', 'desc');

        if(Auth::guard('merchant')->check()){
        $users = $users->where('users.dual_master_id', Auth::user()->code);
        }

        $queries = [];
        $columns = [
            'code',
            'member_name',
            'ic',
            'phone', 
            'email',  
            'status',
            'dates',
            'display_code_desc',
            'display_code_asc',
            'name_desc', 
            'name_asc',
            'upline_code_desc',
            'upline_code_asc',
            'ic_desc',
            'ic_asc', 
            'email_desc', 
            'email_asc',
            'phone_desc', 
            'phone_asc', 
            'status_desc', 
            'status_asc',
            'created_at_desc',
            'created_at_asc',
            'referrer_code',
            'referrer_name',
            'per_page'
        ];

        $per_page = 10;
        if(!empty(request('per_page'))){
            $per_page = request('per_page');
        }

        foreach($columns as $column){
            if(request()->has($column) && !empty(request($column))){
                if($column == 'member_name'){
                    $users = $users->where(DB::raw('CONCAT(users.f_name, " ", users.l_name) COLLATE utf8mb4_general_ci'), 'like', "%".request($column)."%");
                }elseif($column == 'ic'){
                    $users = $users->where('users.ic','like',"%".request($column)."%");
                }elseif($column == 'phone'){
                    $users = $users->where(DB::raw("CONCAT('0', users.phone)"), 'like', "%".request($column)."%");
                }elseif($column == 'email'){
                    $users = $users->where('users.email', 'like', "%".request($column)."%");
                }elseif($column == 'code'){
                    $users = $users->where(DB::raw('CONCAT(users.display_code, users.display_running_no) COLLATE utf8mb4_general_ci'), 'like', "%".request($column)."%");
                }elseif($column == 'status'){
                    $users = $users->where('users.status', 'like', "%".request($column)."%");
                }elseif($column == 'dates'){
                    $users = $users->whereBetween(DB::raw('DATE_FORMAT(users.created_at, "%Y-%m-%d")'), array($start, $end));
                }elseif($column == 'display_code_desc'){
                    $users = $users->orderBy('users.code', 'desc');
                }elseif($column == 'display_code_asc'){
                    $users = $users->orderBy('users.code', 'asc');
                }elseif($column == 'name_desc'){
                    $users = $users->orderBy('users.f_name', 'desc');
                }elseif($column == 'name_asc'){
                    $users = $users->orderBy('users.f_name', 'asc');  
                }elseif($column == 'upline_code_desc'){
                    $users = $users->orderBy(DB::raw('COALESCE(CONCAT(upm.display_code, upm.display_running_no), CONCAT(upa.display_code, upa.display_running_no), CONCAT(agt.display_code, agt.display_running_no), CONCAT(upu.display_code, upu.display_running_no)) COLLATE utf8mb4_general_ci'), 'desc');
                }elseif($column == 'upline_code_asc'){
                    $users = $users->orderBy(DB::raw('COALESCE(CONCAT(upm.display_code, upm.display_running_no), CONCAT(upa.display_code, upa.display_running_no), CONCAT(agt.display_code, agt.display_running_no), CONCAT(upu.display_code, upu.display_running_no)) COLLATE utf8mb4_general_ci'), 'asc');
                }elseif($column == 'ic_desc'){
                    $users = $users->orderBy('users.ic', 'desc');
                }elseif($column == 'ic_asc'){
                    $users = $users->orderBy('users.ic', 'asc');               
                }elseif($column == 'email_desc'){
                    $users = $users->orderBy('users.email', 'desc');
                }elseif($column == 'email_asc'){
                    $users = $users->orderBy('users.email', 'asc');
                }elseif($column == 'phone_desc'){
                    $users = $users->orderBy('users.phone', 'desc');
                }elseif($column == 'phone_asc'){
                    $users = $users->orderBy('users.phone', 'asc');
                }elseif($column == 'status_desc'){
                    $users = $users->orderBy('users.status', 'desc');
                }elseif($column == 'status_asc'){
                    $users = $users->orderBy('users.status', 'asc');
                }elseif($column == 'created_at_desc'){
                    $users = $users->orderBy('users.created_at', 'desc');
                }elseif($column == 'created_at_asc'){
                    $users = $users->orderBy('users.created_at', 'asc');
                }elseif($column == 'referrer_code'){
                    $users = $users->where(DB::raw('COALESCE(CONCAT(upm.display_code, upm.display_running_no), CONCAT(upa.display_code, upa.display_running_no), CONCAT(agt.display_code, agt.display_running_no), CONCAT(upu.display_code, upu.display_running_no)) COLLATE utf8mb4_general_ci'), 'like', "%".request($column)."%");
                }elseif($column == 'referrer_name'){
                    $users = $users->where(DB::raw('COALESCE(CONCAT(upm.f_name, " ", upm.l_name), CONCAT(upa.f_name, " ", upa.l_name), CONCAT(agt.f_name, " ", agt.l_name), CONCAT(upu.f_name, " ", upu.l_name)) COLLATE utf8mb4_general_ci'), 'like', "%".request($column)."%");
                }elseif($column == 'per_page'){
                    $users = $users->paginate($per_page);            
                }

                $queries[$column] = request($column);

            } else {
                $users = $users->orderBy('created_at', 'desc');
            }
        }

        // if(!empty(request('per_page'))){
        //     $users = $users->appends($queries);
        // }else{
        // }
        // $users = $users->orderBy('created_at', 'desc');
        // $users = $users->paginate($per_page)->appends($queries);
        if(!empty(request('per_page'))){ 
            $users = $users->appends($queries);        
        }else{
            $users = $users->paginate($per_page)->appends($queries);        
        }

        return view('backend.members.index', ['users'=>$users, 'startDate' => $startDate, 'endDate' => $endDate]);
    }
    
    public function pending_member()
    {

        $users = User::select('users.*',
                              DB::raw('COALESCE(COALESCE(CONCAT(upm.display_code, upm.display_running_no), upa.code), upu.code) AS upline_code'),
                              DB::raw('COALESCE(COALESCE(CONCAT(upm.f_name, " ", upm.l_name), CONCAT(upa.f_name, " ", upa.l_name)), upu.f_name) AS upline_name'))
                     ->leftJoin('users as upm', 'upm.code', 'users.master_id')
                     ->leftJoin('admins as upa', 'upa.code', 'users.master_id')
                     ->leftJoin('users as upu', 'upu.code', 'users.master_id')
                     ->where('users.status', '99')
                     ->orderBy('users.created_at', 'desc');
                             
        if(Auth::guard('merchant')->check()){
        $users = $users->where('users.dual_master_id', Auth::user()->code);
        }

        $queries = [];
        $columns = [
            'agent_name', 'code'
        ];
        foreach($columns as $column){
            if(request()->has($column) && !empty(request($column))){
                if($column == 'agent_name'){
                    $users = $users->where(DB::raw('CONCAT(users.f_name, " ", users.l_name)'), 'like', "%".request($column)."%");
                }else{
                    $users = $users->where($column, 'like', "%".request($column)."%");
                }

                $queries[$column] = request($column);

            }
        }
        $per_page = 10;
        if(!empty(request('per_page'))){
            $per_page = request('per_page');
        }
        $users = $users->paginate($per_page)->appends($queries);

        $transaction_bank_slip = [];
        foreach($users as $agent){
            $transaction_bank_slip[$agent->code] = Transaction::where('transaction_no', $agent->register_transaction)
                                                                ->orderBy('created_at', 'asc')
                                                                ->first();
        }

        return view('backend.members.pending', ['users'=>$users], compact('transaction_bank_slip'));
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

        $states = State::get();
        $countries = GlobalController::global_countries();
        $levels = AgentLevel::where('status', '1');
        if($get_authorise_status['status'] == 1){
            $levels = $levels->where('merchant_id', $get_authorise_status['result']['code']);
        }
        $levels = $levels->get();

        $agents = Agent::where('status', '1');
        if(Auth::guard('merchant')->check()){
            $agents = $agents->where('dual_master_id', Auth::user()->code);
        }
        $agents = $agents->get();

        return view('backend.members.create', [
            'states' => $states, 
            'countries' => $countries, 
            'levels' => $levels,
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
        try{
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

            \DB::beginTransaction();

            if(!empty($request->master_id)){
                $get_upline = Agent::where(DB::raw("CONCAT(display_code, display_running_no)"), $request->master_id)->first();
                $get_upline_member = User::where(DB::raw("CONCAT(display_code, display_running_no)"), $request->master_id)->first();

                if(empty($get_upline->id) && empty($get_upline_member->id)){
                    throw new \Exception("Referral Code Not Exists");
                }

                if(!empty($get_upline->id)){
                    $master_id = $get_upline->code;
                }

                if(!empty($get_upline_member->id)){
                    $master_id = $get_upline_member->code;
                }
            }else{
                $master_id = "AD000001";
            }

            // Check phone number
            $phone = $request->phone;
            if(substr($phone, 0, 1) == '0'){
                $phone = substr($phone, 1);

                $member = User::where('phone', $phone)->first();

                if(!empty($member->id)){
                    return Redirect::back()->withInput($request->all())->withErrors('The phone has already been taken.');
                }
            }

            $member_display_code = GlobalController::MemberDisplayCode();

            $user = new User();

            $level = !empty($request->lvl) ? $request->lvl : 0;

            //Agent Details
            if(Auth::guard('merchant')->check()){
            $user->dual_master_id = Auth::user()->code;
            }
            $user->master_id = $master_id;
            $user->code = GlobalController::MemberCode();
            $user->display_code = $member_display_code[0];
            $user->display_running_no = $member_display_code[1];
            $user->country_code = $request->country_code;
            // $user->phone = $request->phone;
            $user->phone = $phone;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->f_name = $request->f_name;
            $user->ic = $request->ic;
            $user->gender = $request->gender;
            $user->lvl = $level;
            $user->dob = $request->dob;
            $user->status = 1;

            $user->save();

            $add_affiliates = GlobalController::add_affiliates($user->code, $user->master_id);
            if($add_affiliates != 'ok'){
                throw new \Exception($add_affiliates);
            }


            \DB::commit();
        }catch (\Exception $e){
            \DB::rollback();
            return Redirect::back()->withInput($request->all())->withErrors($e->getMessage());
        }catch(\Error $e){
            \DB::rollback();
            return Redirect::back()->withInput($request->all())->withErrors($e->getMessage());
        }

        Toastr::success($user->f_name . ' ' . ($translation_data['backendlang']['backendlang']['Created'] ?? 'Created') . '!');
        return redirect()->route('member.members.index');
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
        $user = User::select('users.*', 'm.code AS master_code')
                            ->leftJoin('users AS m', 'm.code', 'users.master_id')
                            ->where('users.id', $id)
                            ->first();

        $shipping_address = UserShippingAddress::where('user_id', $user->code)
                                      ->where('default', '1')
                                      ->first();
                                      
        // $countries = TblCountry::get();
        $countries = GlobalController::global_countries();

        $levels = AgentLevel::get();

        return view('backend.members.edit', ['user'=>$user, 'shipping_address'=>$shipping_address, 'countries'=>$countries], compact('levels'));
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
            'phone' => ['required', 'unique:users,phone,'.$id, 'unique:merchants', 'unique:agents'],
            'email' => ['required', 'unique:users,email,'.$id, 'unique:merchants', 'unique:agents'],
            'ic' => ['required', 'unique:users,ic,'.$id, 'unique:merchants', 'unique:agents'],
            'dob' => 'required'
        ], $messages, $attributes);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }

        // Check phone number
        $phone = $request->phone;
        if(substr($phone, 0, 1) == '0'){
            $phone = substr($phone, 1);

            $member = User::where('phone', $phone)->whereNot('id', $id)->first();

            if(!empty($member->id)){
                return Redirect::back()->withInput($request->all())->withErrors($translation_data['backendlang']['backendlang']['The phone has already been taken.'] ?? 'The phone has already been taken.');
            }
        }

        try{

            \DB::beginTransaction();

            $user = User::find($id);

            $level = !empty($request->lvl) ? $request->lvl : 0;

            //Agent Details
            $user->country_code = $request->country_code;
            // $user->phone = $request->phone;
            $user->phone = $phone;
            $user->email = $request->email;
            $user->f_name = $request->f_name;
            $user->ic = $request->ic;
            $user->gender = $request->gender;
            $user->lvl = $level;
            $user->dob = $request->dob;
            $user->status = 1;

            $user->save();

            \DB::commit();
        }catch (\Exception $e){
            \DB::rollback();
            return Redirect::back()->withInput($request->all())->withErrors($e->getMessage());
        }catch(\Error $e){
            \DB::rollback();
            return Redirect::back()->withInput($request->all())->withErrors($e->getMessage());
        }

        Toastr::success($translation_data['backendlang']['backendlang']['Updated'] ?? 'Updated' . '!');
        return redirect()->route('member.members.edit', $id);
    }

    protected $fillable = [
        'country_code',
        'phone',
        'email',
        'f_name',
        'ic',
        'gender',
        'agent_lvl',
    ];

    public function levelUp()
{
    $translation_data = GlobalController::get_translations();
    $user = auth()->user();
    $user->agent_lvl = 1; // or any other appropriate value
    $user->save();

    return redirect()->back()->with('success', $translation_data['backendlang']['backendlang']['Congratulations_you_have_been_promoted_to_an_agent'] ?? 'Congratulations, you have been promoted to an agent!');
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

    public function tree($agent_code)
    {
        $user = User::where('code', $agent_code)->first();

        $userD = User::where('master_id', $agent_code)
                             ->where('status', '1')
                             ->get();

        $mdd = [];
        $mddd = [];
        $mddd1 = [];
        $mddd2 = [];
        $mddd3 = [];
        $sg  = 0;
        $tg  = 0;
        $fog  = 0;
        $fig  = 0;
        $sig  = 0;
        foreach($userD as $userdv){
            $mdd[$userdv->code] = User::where('master_id', $userdv->code)->where('status', '1')->get();
            $sg += count($mdd[$userdv->code]);

            foreach($mdd[$userdv->code] as $mddv){
                $mddd[$mddv->code] = User::where('master_id', $mddv->code)->where('status', '1')->get();
                $tg += count($mddd[$mddv->code]);

                foreach($mddd[$mddv->code] as $mdddv){
                    $mddd1[$mdddv->code] = User::where('master_id', $mdddv->code)->where('status', '1')->get();
                    $fog += count($mddd1[$mdddv->code]);

                    foreach($mddd1[$mdddv->code] as $mddddv){
                        $mddd2[$mddddv->code] = User::where('master_id', $mddddv->code)->where('status', '1')->get();
                        $fig += count($mddd2[$mddddv->code]);

                        foreach($mddd2[$mddddv->code] as $mdddddv){
                            $mddd3[$mdddddv->code] = User::where('master_id', $mdddddv->code)->where('status', '1')->get();
                            $sig += count($mddd3[$mdddddv->code]);
                        }
                    }
                }
            }
        }
        
        $fg = count($userD);
        

        $total = $fg + $sg + $tg + $fog + $fig + $sig;
        // echo $tg;
        if($total > 0){
            $fgp = round($fg / $total * 100, 2);
            $sgp = round($sg / $total * 100, 2);
            $tgp = round($tg / $total * 100, 2);

            $fogp = round($fog / $total * 100, 2);
            $figp = round($fig / $total * 100, 2);
            $sigp = round($sig / $total * 100, 2);
        }else{
            $fgp = 0;
            $sgp = 0;
            $tgp = 0;            

            $fogp = 0;
            $figp = 0;
            $sigp = 0;
        }


        return view('backend.members.tree', ['userD'=>$userD, 'user'=>$user,
                                               'fg'=>$fg, 'fgp'=>$fgp,
                                               'sg'=>$sg, 'sgp'=>$sgp,
                                               'tg'=>$tg, 'tgp'=>$tgp,
                                               'fog'=>$fog, 'fogp'=>$fogp,
                                               'fig'=>$fig, 'figp'=>$figp,
                                               'sig'=>$sig, 'sigp'=>$sigp,], compact('mdd', 'mddd', 'mddd1', 'mddd2', 'mddd3'));
    }

    public function tree_details($agent_code, $g)
    {
        if(!empty($g) && $g <= 3 && $g > 0){
            if($g == '1'){
                $generation = "1st";
            }elseif($g == '2'){
                $generation = "2nd";
            }else{
                $generation = "3th";
            }            
        }else{
            abort(404);
        }


        if($g == 1){
            $users = User::where('master_id', $agent_code)
                                 ->where('status', '1')
                                 ->get();
        }elseif($g == 2){
            $users = User::select('d.*')
                                 ->join('users AS d', 'd.master_id', 'users.code')
                                 ->where('users.master_id', $agent_code)
                                 ->where('d.status', '1')
                                 ->get();

        }else{
            $users = User::select('dd.*')
                                 ->join('users AS d', 'd.master_id', 'users.code')
                                 ->join('users AS dd', 'dd.master_id', 'd.code')
                                 ->where('users.master_id', $agent_code)
                                 ->where('dd.status', '1')
                                 ->get();
        }


        return view('backend.members.tree_details', ['generation'=>$generation, 'users'=>$users]);
    }

    public function adjustMemberPoint($id)
    {
        $user = User::find($id);
        if(!isset($user) && empty($user)){
            abort(404);
        }

        $adjusts = AdjustPointWallet::where('user_id', $user->code)
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

        $GetCashWalletBalance = GlobalController::get_point_wallet($user->code);

        $adjusts = $adjusts->paginate($itemPerPage)->appends($queries);

        return view('backend.members.adjustPoint', ['user'=>$user, 'adjusts'=>$adjusts, 
                                               'GetCashWalletBalance'=>$GetCashWalletBalance]);
    }

    public function SubmitadjustMemberPoint(Request $request, $id)
    {
        $translation_data = GlobalController::get_translations();
        $agent = User::find($id);
        $GetCashWalletBalance = GlobalController::get_point_wallet($agent->code);

        $validator = Validator::make($request->all(), [
            'adjust_type' => 'required',
            'adjust_amount' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }


        if($GetCashWalletBalance < $request->adjust_amount && $request->adjust_type == '2'){
            return Redirect::back()->withInput($request->all())->withErrors($translation_data['backendlang']['backendlang']['Amount_Exceed_Amount_More_Than_Balance'] ?? 'Amount Exceed! Amount More Than Balance.');
        }

        $input = [];
        $input['user_id'] = $agent->code;
        $input['amount'] = preg_replace("/[^0-9\.]/", '', $request->adjust_amount);
        $input['type'] = $request->adjust_type;
        $input['remark'] = $request->remark;
        $input['created_by'] = Auth::user()->code;
        $input['updated_by'] = Auth::user()->code;

        $AdjustCashWallet = AdjustPointWallet::create($input);

        Toastr::success($request->type . ' ' . ($translation_data['backendlang']['backendlang']['Create_Successfully'] ?? 'Create Successfully!') );
        return redirect()->route('adjustMemberPoint', $id);
    }

    public function AdjustMemberCash($id)
    {
        $user = User::find($id);
        if(!isset($user) && empty($user)){
            abort(404);
        }

        $adjusts = AdjustCashWallet::where('user_id', $user->code)
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

        $GetCashWalletBalance = GlobalController::get_cash_wallet_balance($user->code);

        $adjusts = $adjusts->paginate($itemPerPage)->appends($queries);

        return view('backend.members.adjustCash', ['user'=>$user, 'adjusts'=>$adjusts, 
                                               'GetCashWalletBalance'=>$GetCashWalletBalance]);
    }

    public function SubmitAdjustMemberCash(Request $request, $id)
    {
        $translation_data = GlobalController::get_translations();
        $agent = User::find($id);
        $GetCashWalletBalance = GlobalController::get_cash_wallet_balance($agent->code);

        $validator = Validator::make($request->all(), [
            'adjust_type' => 'required',
            'adjust_amount' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }


        if($GetCashWalletBalance < $request->adjust_amount && $request->adjust_type == '2'){
            return Redirect::back()->withInput($request->all())->withErrors($translation_data['backendlang']['backendlang']['Amount_Exceed_Amount_More_Than_Balance'] ?? 'Amount Exceed! Amount More Than Balance.');
        }

        $input = [];
        $input['user_id'] = $agent->code;
        $input['amount'] = preg_replace("/[^0-9\.]/", '', $request->adjust_amount);
        $input['type'] = $request->adjust_type;
        $input['remark'] = $request->remark;
        $input['created_by'] = Auth::user()->code;
        $input['updated_by'] = Auth::user()->code;

        $AdjustCashWallet = AdjustCashWallet::create($input);

        Toastr::success($request->type . ' ' . ($translation_data['backendlang']['backendlang']['Create_Successfully'] ?? 'Create Successfully!') );
        return redirect()->route('adjustMemberCash', $id);
    }

    public function AdjustMemberTopup($id)
    {
        $user = User::find($id);
        if(!isset($user) && empty($user)){
            abort(404);
        }

        $adjusts = AdjustTopupWallet::where('user_id', $user->code)
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

        $GetCashWalletBalance = GlobalController::get_topup_wallet_balance($user->code);

        $adjusts = $adjusts->paginate($itemPerPage)->appends($queries);

        return view('backend.members.adjustTopup', ['user'=>$user, 'adjusts'=>$adjusts, 
                                               'GetCashWalletBalance'=>$GetCashWalletBalance]);
    }

    public function SubmitAdjustMemberTopup(Request $request, $id)
    {
        $translation_data = GlobalController::get_translations();
        $agent = User::find($id);
        $GetCashWalletBalance = GlobalController::get_topup_wallet_balance($agent->code);

        $validator = Validator::make($request->all(), [
            'adjust_type' => 'required',
            'adjust_amount' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }


        if($GetCashWalletBalance < $request->adjust_amount && $request->adjust_type == '2'){
            return Redirect::back()->withInput($request->all())->withErrors($translation_data['backendlang']['backendlang']['Amount_Exceed_Amount_More_Than_Balance'] ?? 'Amount Exceed! Amount More Than Balance.');
        }

        $input = [];
        $input['user_id'] = $agent->code;
        $input['amount'] = preg_replace("/[^0-9\.]/", '', $request->adjust_amount);
        $input['type'] = $request->adjust_type;
        $input['remark'] = $request->remark;
        $input['created_by'] = Auth::user()->code;
        $input['updated_by'] = Auth::user()->code;

        $AdjustTopupWallet = AdjustTopupWallet::create($input);

        Toastr::success($request->type . ' ' . ($translation_data['backendlang']['backendlang']['Create_Successfully'] ?? 'Create Successfully!') );
        return redirect()->route('AdjustMemberTopup', $id);
    }

    public function saveMemberNewPassword(Request $request, $id)
    {
        $translation_data = GlobalController::get_translations();
        $input = $request->all();
        $input['password'] = Hash::make($request->new_password);
        $update = User::find($id);
        $update = $update->update($input);

        Toastr::success($translation_data['backendlang']['backendlang']['Password_Changed'] ?? "Password Changed!");
        return redirect()->route('member.members.edit', $id);
    }

    public function member_wallet()
    {
        $users = User::select('users.*')
                     ->leftJoin('merchants as upm', 'upm.code', 'users.master_id')
                     ->leftJoin('admins as upa', 'upa.code', 'users.master_id')
                     ->where('users.status', '!=', '3');
                     //->orderBy('created_at', 'desc');

        if(Auth::guard('merchant')->check()){
        $users = $users->where('users.dual_master_id', Auth::user()->code);
        }

        $queries = [];
        $columns = [
            'code',
            'member_name',
            'phone', 
            'email',  
            'status',
            'code_desc',
            'code_asc',
            'name_desc', 
            'name_asc',  
            'email_desc', 
            'email_asc',
            'phone_desc', 
            'phone_asc', 
            'status_desc', 
            'status_asc', 
            'referrer_code',
            'referrer_name',
            'per_page'
        ];

        $per_page = 10;
        if(!empty(request('per_page'))){
            $per_page = request('per_page');
        }

        foreach($columns as $column){
            if(request()->has($column) && !empty(request($column))){
                if($column == 'member_name'){
                    $users = $users->where(DB::raw('CONCAT(users.f_name, " ", users.l_name)'), 'like', "%".request($column)."%");
                }elseif($column == 'phone'){
                    $users = $users->where('users.phone', 'like', "%".request($column)."%");
                }elseif($column == 'email'){
                    $users = $users->where('users.email', 'like', "%".request($column)."%");
                }elseif($column == 'code'){
                    $users = $users->where(DB::raw('CONCAT(users.display_code, users.display_running_no)'), 'like', "%".request($column)."%");
                }elseif($column == 'status'){
                    $users = $users->where('users.status', 'like', "%".request($column)."%");
                }elseif($column == 'code_desc'){
                    $users = $users->orderBy('users.code', 'desc');
                }elseif($column == 'code_asc'){
                    $users = $users->orderBy('users.code', 'asc');
                }elseif($column == 'name_desc'){
                    $users = $users->orderBy('users.f_name', 'desc');
                }elseif($column == 'name_asc'){
                    $users = $users->orderBy('users.f_name', 'asc');
                }elseif($column == 'email_desc'){
                    $users = $users->orderBy('users.email', 'desc');
                }elseif($column == 'email_asc'){
                    $users = $users->orderBy('users.email', 'asc');
                }elseif($column == 'phone_desc'){
                    $users = $users->orderBy('users.phone', 'desc');
                }elseif($column == 'phone_asc'){
                    $users = $users->orderBy('users.phone', 'asc');
                }elseif($column == 'status_desc'){
                    $users = $users->orderBy('users.status', 'desc');
                }elseif($column == 'status_asc'){
                    $users = $users->orderBy('users.status', 'asc');
                }elseif($column == 'referrer_code'){
                    $users = $users->where(DB::raw('COALESCE(CONCAT(upm.display_code, upm.display_running_no), CONCAT(upa.display_code, upa.display_running_no))'), 'like', "%".request($column)."%");
                }elseif($column == 'referrer_name'){
                    $users = $users->where(DB::raw('COALESCE(CONCAT(upm.f_name, " ", upm.l_name), CONCAT(upa.f_name, " ", upa.l_name))'), 'like', "%".request($column)."%");
                }elseif(request($column) == 'per_page'){
                    $users = $users->paginate($per_page);            
                }

                $queries[$column] = request($column);

            }
        }

        // if(!empty(request('per_page'))){
        //     $users = $users->appends($queries);
        // }else{
        // }
        $users = $users->orderBy('created_at', 'desc');
        $users = $users->paginate($per_page)->appends($queries);

        $get_cash_wallet_balance = [];
        $get_topup_wallet_balance = [];
        $get_point_wallet_balance = [];
        foreach($users as $user){
            $get_cash_wallet_balance[$user->code] = GlobalController::get_cash_wallet_balance($user->code);
            $get_topup_wallet_balance[$user->code] = GlobalController::get_topup_wallet_balance($user->code);
            $get_point_wallet_balance[$user->code] = GlobalController::get_point_wallet($user->code);
        }
        
        $agent_lvls = AgentLevel::get();

        return view('backend.members.wallet', ['members'=>$users, 
                                              'agent_lvls'=>$agent_lvls], 
                                             compact('get_cash_wallet_balance', 'get_point_wallet_balance',
                                                     'get_topup_wallet_balance'));
    }
}
