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
use App\Corporate;
use Validator, Redirect, Toastr, DB, File, Auth, Hash;

class CorporateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $corporates = Corporate::select('corporates.*', 'l.agent_lvl', DB::raw('COALESCE(CONCAT(upm.f_name, " ", upm.l_name), CONCAT(upa.f_name, " ", upa.l_name)) AS upline_name'),
                              DB::raw('COALESCE(CONCAT(upm.display_code, upm.display_running_no), upa.code) AS upline_code'),
                              DB::raw('CONCAT( "(+", corporates.country_code, ")", corporates.phone ) AS full_phone'))
                             ->leftJoin('agent_levels as l', 'l.id', 'corporates.lvl')
                             ->leftJoin('merchants as upm', 'upm.code', 'corporates.master_id')
                             ->leftJoin('admins as upa', 'upa.code', 'corporates.master_id')
                             ->whereNotIn('corporates.status', ['99', '3']);
                             // ->orderBy('corporates.created_at', 'desc');


        $per_page = 10;
        if(!empty(request('per_page'))){
            $per_page = request('per_page');
        }

        $queries = [];
        $columns = [
            'code', 'corporate_name', 'ic', 'referrer_code', 'referrer_name', 'display_code_desc', 'display_code_asc', 'name_desc', 'name_asc', 'upline_code_desc', 'upline_code_asc', 'upline_name_desc', 'upline_name_asc', 'email_desc' ,'email_asc', 'ic_desc', 'ic_asc', 'phone_desc', 'phone_asc', 'status_desc', 'status_asc', 'created_at_desc', 'created_at_asc', 'acc_type_desc', 'acc_type_asc', 'company_num_desc', 'company_num_asc', 'account_type', 'status'
        ];
        foreach($columns as $column){
            if(request()->has($column) && !empty(request($column))){
                if($column == 'corporate_name'){
                    $corporates = $corporates->where(DB::raw('CONCAT(corporates.f_name, " ", corporates.l_name)'), 'like', "%".request($column)."%");
                }elseif($column == 'code'){
                    $corporates = $corporates->where(DB::raw('CONCAT(corporates.display_code, corporates.display_running_no)'), 'like', "%".request($column)."%");
                }elseif($column == 'ic'){
                    $corporates = $corporates->where('corporates.ic', 'like', "%".request($column)."%");
                }elseif($column == 'status'){
                    $corporates = $corporates->where('corporates.status', 'like', "%".request($column)."%");
                }elseif($column == 'referrer_code'){
                    $corporates = $corporates->where(DB::raw('COALESCE(CONCAT(upm.display_code, upm.display_running_no), upa.code)') , 'like', "%".request($column)."%");
                }elseif($column == 'referrer_name'){
                    $corporates = $corporates->where(DB::raw('COALESCE(CONCAT(upm.f_name, " ", upm.l_name), CONCAT(upa.f_name, " ", upa.l_name))'), 'like', "%".request($column)."%");
                }elseif($column == 'per_page'){
                  $corporates = $corporates->paginate($per_page);
                }elseif($column == 'display_code_desc'){
                    $corporates = $corporates->orderBy(DB::raw('CONCAT(corporates.display_code, corporates.display_running_no)'), 'desc');
                }elseif($column == 'display_code_asc'){
                    $corporates = $corporates->orderBy(DB::raw('CONCAT(corporates.display_code, corporates.display_running_no)'), 'asc');
                }elseif($column == 'name_desc'){
                    $corporates = $corporates->orderBy(DB::raw('CONCAT(corporates.f_name, corporates.l_name)'), 'desc');
                }elseif($column == 'name_asc'){
                    $corporates = $corporates->orderBy(DB::raw('CONCAT(corporates.f_name, corporates.l_name)'), 'asc');
                }elseif($column == 'upline_code_desc'){
                    $corporates = $corporates->orderBy('upline_code', 'desc');
                }elseif($column == 'upline_code_asc'){
                    $corporates = $corporates->orderBy('upline_code', 'asc');
                }elseif($column == 'upline_name_desc'){
                    $corporates = $corporates->orderBy('upline_name', 'desc');
                }elseif($column == 'upline_name_asc'){
                    $corporates = $corporates->orderBy('upline_name', 'asc');
                }elseif($column == 'email_desc'){
                    $corporates = $corporates->orderBy('corporates.email', 'desc');
                }elseif($column == 'email_asc'){
                    $corporates = $corporates->orderBy('corporates.email', 'asc');
                }elseif($column == 'ic_desc'){
                    $corporates = $corporates->orderBy('corporates.ic', 'desc');
                }elseif($column == 'ic_asc'){
                    $corporates = $corporates->orderBy('corporates.ic', 'asc');
                }elseif($column == 'phone_desc'){
                    $corporates = $corporates->orderBy(DB::raw('CONCAT(corporates.country_code, corporates.phone)'), 'desc');
                }elseif($column == 'phone_asc'){
                    $corporates = $corporates->orderBy(DB::raw('CONCAT(corporates.country_code, corporates.phone)'), 'asc');
                }elseif($column == 'status_desc'){
                    $corporates = $corporates->orderBy('corporates.status', 'desc');
                }elseif($column == 'status_asc'){
                    $corporates = $corporates->orderBy('corporates.status', 'asc');
                }elseif($column == 'created_at_desc'){
                    $corporates = $corporates->orderBy('corporates.created_at', 'desc');
                }elseif($column == 'created_at_asc'){
                    $corporates = $corporates->orderBy('corporates.created_at', 'asc');
                }elseif($column == 'company_num_desc'){
                    $corporates = $corporates->orderBy('corporates.company_registration_no', 'desc');
                }elseif($column == 'company_num_asc'){
                    $corporates = $corporates->orderBy('corporates.company_registration_no', 'asc');
                }elseif($column == 'acc_type_desc'){
                    $corporates = $corporates->orderBy('corporates.company', 'desc');
                }elseif($column == 'acc_type_asc'){
                    $corporates = $corporates->orderBy('corporates.company', 'asc');
                }elseif($column == 'account_type'){
                    if(request($column) == '1'){
                        $corporates = $corporates->where('corporates.company', '1');
                    }elseif(request($column) == '2'){
                        $corporates = $corporates->whereNull('corporates.company');
                    }
                }else{
                    $corporates = $corporates->where($column, 'like', "%".request($column)."%");
                }

                $queries[$column] = request($column);

            }
        }
        $corporates = $corporates->orderBy('corporates.created_at', 'desc');
        $corporates = $corporates->paginate($per_page)->appends($queries);

        return view('backend.corporates.index', ['corporates'=>$corporates]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $states = State::get();
        return view('backend.corporates.create', ['states'=>$states]);
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
            'phone' => ['required', 'unique:users', 'unique:corporates', 'unique:admins', 'unique:merchants'],
            'email' => ['required', 'unique:users', 'unique:corporates', 'unique:admins', 'unique:merchants'],
            'password' => ['required', 'min:6'],
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }

        if(!empty($request->master_id)){
            $checkmAff = Merchant::where(DB::raw("CONCAT(display_code, display_running_no)"), $request->master_id)->first();
            if(empty($checkmAff->id)){
                return Redirect::back()->withInput($request->all())->withErrors("Refferal Code Not Exists");
            }
            $master_id = $checkmAff->code;
        }else{
            $master_id = "AD000001";
        }

        $input = $request->all();
        $input['country_code'] = $request->country_code;
        $input['master_id'] = $master_id;
        $input['f_name'] = htmlspecialchars(trim($request->f_name));
        $input['l_name'] = htmlspecialchars(trim($request->l_name));
        $input['gender'] = htmlspecialchars(trim($request->gender));
        $input['phone'] = htmlspecialchars(trim($request->phone));
        $input['code'] = htmlspecialchars(trim($this->CorporateCode()));
        $input['email'] = htmlspecialchars(trim($request->email));
        $input['address'] = htmlspecialchars(trim($request->email));
        $input['password'] = Hash::make($request->password);
        $input['status'] = '1';

        $dc = $this->CorporateDisplayCode("CP");
        $input['display_code'] = $dc[0];
        $input['display_running_no'] = $dc[1];

        $user = Corporate::create($input);

        Toastr::success("$user->f_name Created!");
        return redirect()->route('corporate.corporates.index');
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
        $corporate = Corporate::select('corporates.*', 'm.code AS master_code')
                            ->leftJoin('corporates AS m', 'm.code', 'corporates.master_id')
                            ->where('corporates.id', $id)
                            ->first();

        return view('backend.corporates.edit', ['corporate'=>$corporate]);
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
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }

        $input = $request->all();
        $input['f_name'] = htmlspecialchars(trim($request->f_name));
        $input['l_name'] = htmlspecialchars(trim($request->l_name));

        $user = Corporate::find($id);
        $user_name = $user->f_name;
        $user = $user->update($input);

        Toastr::success("$user_name Updated!");
        return redirect()->route('corporate.corporates.edit', $id);
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

    public function saveCorporateNewPassword(Request $request, $id)
    {
        $input = $request->all();
        $input['password'] = Hash::make($request->new_password);
        $update = Corporate::find($id);
        $update = $update->update($input);

        Toastr::success("Password Changed!");
        return redirect()->route('corporate.corporates.edit', $id);
    }

    protected function CorporateCode()
    {
        $user = Corporate::select(DB::raw("COUNT(id) AS totalUser"))->first();
        $totalCount = $user->totalUser + 1;

        if(strlen($totalCount) == '1'){
            $member_id = "CP00000".$totalCount;
        }elseif(strlen($totalCount) == '2'){
            $member_id = "CP0000".$totalCount;
        }elseif(strlen($totalCount) == '3'){
            $member_id = "CP000".$totalCount;
        }elseif(strlen($totalCount) == '4'){
            $member_id = "CP00".$totalCount;
        }elseif(strlen($totalCount) == '5'){
            $member_id = "CP0".$totalCount;
        }else{
            $member_id = "CP".$totalCount;
        }

        return $member_id;
    }

    public function pending_corporate()
    {

        $corporates = Corporate::where('status', '99')->orderBy('created_at', 'desc');
        if(Auth::guard('merchant')->check()){
            $corporates = $corporates->where('master_id', Auth::user()->code);
        }
        $queries = [];
        $columns = [
            'merchant_name', 'code'
        ];
        foreach($columns as $column){
            if(request()->has($column) && !empty(request($column))){
                if($column == 'merchant_name'){
                    $corporates = $corporates->where(DB::raw('CONCAT(f_name, " ", l_name)'), 'like', "%".request($column)."%");
                }else{
                    $corporates = $corporates->where($column, 'like', "%".request($column)."%");
                }

                $queries[$column] = request($column);

            }
        }
        $per_page = 10;
        if(!empty(request('per_page'))){
            $per_page = request('per_page');
        }
        $corporates = $corporates->paginate($per_page)->appends($queries);

        return view('backend.corporates.pending', ['corporates'=>$corporates]);
    }

    protected function CorporateDisplayCode($member_lvl_code)
    {
        $user = Corporate::select(DB::raw("COUNT(id) AS totalUser"))->where('display_code', $member_lvl_code)->first();
        $totalCount = $user->totalUser + 1;

        if(strlen($totalCount) == '1'){
            $member_id = "00000".$totalCount;
        }elseif(strlen($totalCount) == '2'){
            $member_id = "0000".$totalCount;
        }elseif(strlen($totalCount) == '3'){
            $member_id = "000".$totalCount;
        }elseif(strlen($totalCount) == '4'){
            $member_id = "00".$totalCount;
        }elseif(strlen($totalCount) == '5'){
            $member_id = "0".$totalCount;
        }else{
            $member_id = $totalCount;
        }

        return array($member_lvl_code, $member_id);
    }
}
