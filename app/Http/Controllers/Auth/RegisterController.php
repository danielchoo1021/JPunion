<?php

namespace App\Http\Controllers\Auth;

use App\Admin;
use App\User;
use App\Merchant;
use App\Corporate;
use App\Affiliate;
use App\AgentLevel;
use App\State;

use App\Http\Controllers\GlobalController;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use DB, Auth, Session;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'country_code' => ['required'],
            'phone' => ['required', 'unique:users', 'unique:merchants'],
            'f_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users', 'unique:merchants', 'unique:admins', 'unique:staff'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function MemberCode()
    {
        $user = User::select(DB::raw("COUNT(id) AS totalUser"))->first();
        $totalCount = $user->totalUser + 1;

        if(strlen($totalCount) == '1'){
            $member_id = "Mb00000".$totalCount;
        }elseif(strlen($totalCount) == '2'){
            $member_id = "Mb0000".$totalCount;
        }elseif(strlen($totalCount) == '3'){
            $member_id = "Mb000".$totalCount;
        }elseif(strlen($totalCount) == '4'){
            $member_id = "Mb00".$totalCount;
        }elseif(strlen($totalCount) == '5'){
            $member_id = "Mb0".$totalCount;
        }else{
            $member_id = "Mb".$totalCount;
        }

        return $member_id;
    }

    protected function MerchantCode()
    {
        $user = Merchant::select(DB::raw("COUNT(id) AS totalUser"))->first();
        $totalCount = $user->totalUser + 1;

        if(strlen($totalCount) == '1'){
            $member_id = "M00000".$totalCount;
        }elseif(strlen($totalCount) == '2'){
            $member_id = "M0000".$totalCount;
        }elseif(strlen($totalCount) == '3'){
            $member_id = "M000".$totalCount;
        }elseif(strlen($totalCount) == '4'){
            $member_id = "M00".$totalCount;
        }elseif(strlen($totalCount) == '5'){
            $member_id = "M0".$totalCount;
        }else{
            $member_id = "M".$totalCount;
        }

        return $member_id;
    }

    protected function create(array $data)
    {
        if(!empty(Session::get('guest_agent'))){
          $master_id = Session::get('guest_agent');
        }else{
          $master_id = (!empty($data['master_id'])) ? $data['master_id'] : 'AD000001';
        }


        $merchant = Merchant::where(DB::raw('CONCAT(display_code, display_running_no)'), 'like', '%'.$data['master_id'].'%')->where('status', '1')->first();
        $admin = Admin::where(DB::raw('CONCAT(display_code, display_running_no)'), 'like', '%'.$data['master_id'].'%')->where('status', '1')->first();
        $user = User::where(DB::raw('CONCAT(display_code, display_running_no)'), 'like', '%'.$data['master_id'].'%')->where('status', '1')->where('lvl', '1')->first();
        
        if(!empty($merchant->id)){
            $uplineDetail = $merchant;
        }

        if(!empty($admin->id)){
            $uplineDetail = $admin;
        }

        if(!empty($user->id)){
            $uplineDetail = $user;
        }

        $filterPhone = ltrim($data['phone'],"0");
        $filterPhone2 = str_replace("-","",$filterPhone);
        $filterPhone3 = str_replace(" ", "", $filterPhone2);

        if($data['role'] == '1'){
            $dc = GlobalController::MemberDisplayCode();

            return User::create([
                'master_id' => $uplineDetail->code,
                'code' => $this->MemberCode(),
                'country_code' => $data['country_code'],
                'phone' => preg_replace("/^\+?{$data['country_code']}/", '',$filterPhone3),
                'f_name' => ucwords(strtolower($data['f_name'])),
                'ic' => $data['ic'],
                'email' => strtolower($data['email']),
                'gender' => $data['gender'],
                'dob' => $data['dob'],
                'password' => Hash::make($data['password']),
                'status' => '1',
                'display_code'=> $dc[0],
                'display_running_no'=> $dc[1],
                'status'=> '1'
            ]);
        }else{
            $get_lvl_code = AgentLevel::find(1);
            $dc = GlobalController::MerchantDisplayCode();

            return Merchant::create([
                'master_id' => $uplineDetail->code,
                'code' => $this->MerchantCode(),
                'country_code' => $data['country_code'],
                'phone' => preg_replace("/^\+?{$data['country_code']}/", '',$filterPhone3),
                'f_name' => ucwords(strtolower($data['f_name'])),
                'ic' => $data['ic'],
                'gender' => $data['gender'],
                'dob' => $data['dob'],
                'email' => strtolower($data['email']),
                'agent_type'=> '2',
                'display_code'=> $dc[0],
                'display_running_no'=> $dc[1],
                'password' => Hash::make($data['password']),
                'verify_status' => '1',
                'lvl' => '1',
                'status' => '99',
            ]);
        }
    }

    protected function MerchantDisplayCode($agent_lvl_code)
    {
        $user = Merchant::select(DB::raw("COUNT(id) AS totalUser"))->where('display_code', $agent_lvl_code)->first();
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

        return array($agent_lvl_code, $member_id);
    }

    protected function MemberDisplayCode($member_lvl_code)
    {
        $user = User::select(DB::raw("COUNT(id) AS totalUser"))->where('display_code', $member_lvl_code)->first();
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
