<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Facades\Input;
use App\Admin;
use App\Merchant;
use App\Staff;
use App\User;
use App\Affiliate;
use App\VerifyCode;
use App\State;
use App\SettingRefferalReward;
use App\AgentLevel;
use App\SettingMerchantBonus;
use App\AffiliateCommission;
use App\AgentRebateHistory;
use App\Transaction;
use App\AffiliateDual;
use App\TopupTransaction;


use Validator, Redirect, Toastr, DB, File, Auth;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $staffs = Staff::select('l.agent_lvl AS l_agent_lvl', 'staff.*', DB::raw('COALESCE(CONCAT("(+", staff.country_code, ")", staff.phone), staff.phone) AS full_phone'))
                             ->leftJoin('agent_levels AS l', 'l.id', 'staff.lvl')
                             ->whereNotIn('staff.status', ['99', '3']);
                             // ->orderBy('staff.created_at', 'desc');

        if(Auth::guard('merchant')->check()){
            $staffs = $staffs->where('master_id', Auth::user()->code);
        }
        $queries = [];
        $columns = [
            'code', 'staff_name', 'lvl', 'ic', 'perm_lvl', 'status', 'agent_type', 'code_desc', 'code_asc', 'name_desc', 'name_asc', 'email_desc', 'email_asc', 'phone_desc', 'phone_asc', 'ic_desc', 'ic_asc', 'job_desc', 'job_asc',
            'lvl_desc', 'lvl_asc', 'status_desc', 'status_asc'
        ];
        foreach($columns as $column){
            if(request()->has($column) && !empty(request($column))){
                if($column == 'staff_name'){
                    $staffs = $staffs->where(DB::raw('CONCAT(f_name, " ", l_name)'), 'like', "%".request($column)."%");
                }elseif($column == 'status'){
                    $staffs = $staffs->where('staff.status', 'like', "%".request($column)."%");
                }elseif($column == 'ic'){
                    $staffs = $staffs->where('staff.ic', 'like', "%".request($column)."%");
                }elseif($column == 'perm_lvl'){
                    $staffs = $staffs->where('staff.permission_lvl', 'like', "%".request($column)."%");
                }elseif($column == 'code_desc'){
                    $staffs = $staffs->orderBy('staff.code', 'desc');
                }elseif($column == 'code_asc'){
                    $staffs = $staffs->orderBy('staff.code', 'asc');
                }elseif($column == 'name_desc'){
                    $staffs = $staffs->orderBy('staff.f_name', 'desc');
                }elseif($column == 'name_asc'){
                    $staffs = $staffs->orderBy('staff.f_name', 'asc');
                }elseif($column == 'email_desc'){
                    $staffs = $staffs->orderBy('staff.email', 'desc');
                }elseif($column == 'email_asc'){
                    $staffs = $staffs->orderBy('staff.email', 'asc');
                }elseif($column == 'phone_desc'){
                    $staffs = $staffs->orderBy(DB::raw('CONCAT(country_code, phone)'), 'desc');
                }elseif($column == 'phone_asc'){
                    $staffs = $staffs->orderBy(DB::raw('CONCAT(country_code, phone)'), 'asc');
                }elseif($column == 'ic_desc'){
                    $staffs = $staffs->orderBy('staff.ic', 'desc');
                }elseif($column == 'ic_asc'){
                    $staffs = $staffs->orderBy('staff.ic', 'asc');
                }elseif($column == 'job_desc'){
                    $staffs = $staffs->orderBy('staff.job', 'desc');
                }elseif($column == 'job_asc'){
                    $staffs = $staffs->orderBy('staff.job', 'asc');
                }elseif($column == 'lvl_desc'){
                    $staffs = $staffs->orderBy('staff.permission_lvl', 'desc');
                }elseif($column == 'lvl_asc'){
                    $staffs = $staffs->orderBy('staff.permission_lvl', 'asc');
                }elseif($column == 'status_desc'){
                    $staffs = $staffs->orderBy('staff.status', 'desc');
                }elseif($column == 'status_asc'){
                    $staffs = $staffs->orderBy('staff.status', 'asc');
                }else{
                    $staffs = $staffs->where($column, 'like', "%".request($column)."%");
                }

                $queries[$column] = request($column);

            }
        }
        $per_page = 10;
        if(!empty(request('per_page'))){
            $per_page = request('per_page');
        }
        $staffs = $staffs->orderBy('staff.created_at', 'desc');
        $staffs = $staffs->paginate($per_page)->appends($queries);
        $agent_lvls = AgentLevel::get();

        return view('backend.staffs.index', ['staffs'=>$staffs, 'agent_lvls'=>$agent_lvls]);
    }


    public function pending_staff()
    {

        $staffs = Staff::where('status', '99')->orderBy('created_at', 'desc');
        $queries = [];
        $columns = [
            'merchant_name', 'code'
        ];
        foreach($columns as $column){
            if(request()->has($column) && !empty(request($column))){
                if($column == 'merchant_name'){
                    $staffs = $staffs->where(DB::raw('CONCAT(f_name, " ", l_name)'), 'like', "%".request($column)."%");
                }else{
                    $staffs = $staffs->where($column, 'like', "%".request($column)."%");
                }

                $queries[$column] = request($column);

            }
        }
        $per_page = 10;
        if(!empty(request('per_page'))){
            $per_page = request('per_page');
        }
        $staffs = $staffs->paginate($per_page)->appends($queries);

        $topup_bank_slip = [];
        foreach($staffs as $staff){
            $topup_bank_slip[$staff->code] = TopupTransaction::where('user_id', $staff->code)
                                                                ->where('status', '99')
                                                                ->orderBy('created_at', 'asc')
                                                                ->first();
        }

        return view('backend.staffs.pending', ['staffs'=>$staffs], compact('topup_bank_slip'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $states = State::get();
        $levels = AgentLevel::get();
        

        return view('backend.staffs.create', ['states'=>$states, 'levels'=>$levels]);
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
            'ic' => ['required', 'unique:users', 'unique:merchants', 'unique:admins', 'unique:staff'],
            'phone' => ['required', 'unique:merchants', 'unique:users', 'unique:admins', 'unique:staff'],
            'email' => ['required', 'unique:merchants', 'unique:users', 'unique:admins', 'unique:staff'],
            'password' => ['required', 'min:6'],
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }

        $input = $request->all();
        $input['country_code'] = $request->country_code;
        $input['master_id'] = Auth::user()->code;
        $input['f_name'] = trim($request->f_name);
        $input['l_name'] = trim($request->l_name);
        $input['job'] = trim($request->job);
        $input['gender'] = trim($request->gender);
        $input['phone'] = trim($request->phone);
        $input['code'] = trim($this->StaffCode());
        $input['email'] = trim($request->email);
        $input['address'] = trim($request->email);
        $input['password'] = Hash::make($request->password);

        $staff = Staff::create($input);

        Toastr::success("$staff->f_name Created!");
        return redirect()->route('staff.staffs.index');
    }

    public function saveNewStaffPassword(Request $request, $id)
    {
        $input = $request->all();
        $input['password'] = Hash::make($request->new_password);
        $update = Staff::find($id);
        $update = $update->update($input);

        Toastr::success("Password Changed!");
        return redirect()->route('staff.staffs.edit', $id);
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
        $staff = Staff::select('staff.*', DB::raw('COALESCE(m.code, a.code) AS master_code'))
                            ->leftJoin('staff AS m', 'm.code', 'staff.master_id')
                            ->leftJoin('admins AS a', 'a.code', 'staff.master_id')
                            ->where('staff.id', $id)
                            ->first();

        $levels = AgentLevel::get();

        

        return view('backend.staffs.edit', ['staff'=>$staff, 'levels'=>$levels]);
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

        $checkMerchantIC = Merchant::where('status', '1')->where('ic', $request->ic)->where('id', '<>', $id)->first();
        $checkUserIC = User::where('status', '1')->where('ic', $request->ic)->where('id', '<>', $id)->first();
        $checkStaffIC = Staff::where('status', '1')->where('ic', $request->ic)->where('id', '<>', $id)->first();

        if(!empty($checkMerchantIC->id) || !empty($checkUserIC->id) || !empty($checkStaffIC->id)){
            return Redirect::back()->withInput($request->all())->withErrors("NRIC No exists!");
        }
        
        $input = $request->all();
        $input['f_name'] = trim($request->f_name);
        $input['l_name'] = trim($request->l_name);
        $input['ic'] = trim($request->ic);
        $input['job'] = trim($request->job);
        $input['country_code'] = $request->country_code;

        // $staff = Merchant::find($id);
        $staff = Staff::find($id);

        $staff_name = $staff->f_name;
        $staff = $staff->update($input);
        // echo $input['ic'];
        // exit();
        Toastr::success("$staff_name Updated!");
        return redirect()->route('staff.staffs.edit', $id);
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

    protected function StaffCode()
    {
        $user = Staff::select(DB::raw("COUNT(id) AS totalUser"))->first();
        $totalCount = $user->totalUser + 1;

        if(strlen($totalCount) == '1'){
            $member_id = "S00000".$totalCount;
        }elseif(strlen($totalCount) == '2'){
            $member_id = "S0000".$totalCount;
        }elseif(strlen($totalCount) == '3'){
            $member_id = "S000".$totalCount;
        }elseif(strlen($totalCount) == '4'){
            $member_id = "S00".$totalCount;
        }elseif(strlen($totalCount) == '5'){
            $member_id = "S0".$totalCount;
        }else{
            $member_id = "S".$totalCount;
        }

        return $member_id;
    }

    public function saveNewPassword(Request $request, $id)
    {
        $input = $request->all();
        $input['password'] = Hash::make($request->new_password);
        $update = Staff::find($id);
        $update = $update->update($input);

        Toastr::success("Password Changed!");
        return redirect()->route('staff.staffs.edit', $id);
    }

    public function RebateAgentCommission($user_id, $agent_lvl)
    {
        $countAff = Merchant::select(DB::raw('COUNT(id) AS totalAff'))
                                    ->where('master_id', $user_id)
                                    ->where('status', '1')
                                    ->first();

        // $lvl = AgentLevel::where('agent_lvl', $agent_lvl)->first();
        // if(!empty($lvl->lvl)){
        //     $agent_lvl = $lvl->id;
        // }else{
        //     $agent_lvl = "";
        // }
        $totalAgentAff = 0;

        if(!empty($countAff->totalAff)){
            $totalAgentAff = $countAff->totalAff;
        }


        $selectBate = SettingMerchantBonus::where(DB::raw('CAST(qty AS UNSIGNED INTEGER)'), '<=', $totalAgentAff)
                                           ->where('agent_lvl', $agent_lvl)
                                           ->orderBy('amount', 'desc')
                                           ->first();
        

        if(!empty($selectBate->id)){
            if($selectBate->type == '1'){
                //累计人数
                $exists = AgentRebateHistory::where('user_id', $user_id)
                                            ->where('commision_id', $selectBate->id)
                                            ->exists();
                // return $exists;
                if($exists != 1){                            
                  $create = AffiliateCommission::create(['type'=>'1',
                                                         'user_id'=>$user_id,
                                                         'comm_pa_type'=>'Amount',
                                                         'comm_pa'=>$selectBate->amount,
                                                         'comm_amount'=>$selectBate->amount,
                                                         'comm_desc'=>"Agent Bonus - You've hit the agent bonus target of ".$totalAgentAff." and you get RM ".$selectBate->amount." bonus"]);

                  $createH = AgentRebateHistory::create(['user_id'=>$user_id,
                                                               'commision_id'=> $selectBate->id]);
                }
            }elseif($selectBate->type == '2'){
                //每个月
                $countMonthAff = Merchant::select(DB::raw('COUNT(id) AS totalAff'))
                                    ->where('master_id', $user_id)
                                    ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'), date('Y-m'))
                                    ->first();
                
                if(!empty($countMonthAff->totalAff)){
                    $exists = AgentRebateHistory::join('setting_merchant_rebates AS r', 'r.id', 'agent_rebate_histories.commision_id')
                                                ->where('user_id', $user_id)
                                                ->where('commision_id', $selectBate->id)
                                                ->where(DB::raw('DATE_FORMAT(agent_rebate_histories.created_at, "%Y-%m")'), date('Y-m'))
                                                ->exists();
                    if($exists != 1){
                        
                        $create = AffiliateCommission::create(['type'=>'1',
                                                               'user_id'=>$user_id,
                                                               'comm_pa_type'=>'Amount',
                                                               'comm_pa'=>$selectBate->amount,
                                                               'comm_amount'=>$selectBate->amount,
                                                               'comm_desc'=>"Agent Bonus - You've hit the agent bonus target of ".$totalAgentAff." and you get RM ".$selectBate->amount." bonus"]);

                        $createH = AgentRebateHistory::create(['user_id'=>$user_id,
                                                               'commision_id'=> $selectBate->id]);

                    }
                }
            }elseif($selectBate->type == '3'){
                //每个星期
                $countMonthAff = Merchant::select(DB::raw('COUNT(id) AS totalAff'))
                                    ->where('master_id', $user_id)
                                    ->whereBetween('created_at', [
                                        Carbon\Carbon::parse('last monday')->startOfDay(),
                                        Carbon\Carbon::parse('next friday')->endOfDay(),
                                    ])
                                    ->first();
                
                if(!empty($countMonthAff->totalAff)){
                    $exists = AgentRebateHistory::join('setting_merchant_rebates AS r', 'r.id', 'agent_rebate_histories.commision_id')
                                                ->where('user_id', $user_id)
                                                ->where('commision_id', $selectBate->id)
                                                ->whereBetween('agent_rebate_histories.created_at', [
                                                    Carbon\Carbon::parse('last monday')->startOfDay(),
                                                    Carbon\Carbon::parse('next friday')->endOfDay(),
                                                ])
                                                ->exists();
                    if($exists != 1){
                      $create = AffiliateCommission::create(['type'=>'1',
                                                             'user_id'=>$user_id,
                                                             'comm_pa_type'=>'Amount',
                                                             'comm_pa'=>$selectBate->amount,
                                                             'comm_amount'=>$selectBate->amount,
                                                             'comm_desc'=>"Agent Bonus - You've hit the agent bonus target of ".$totalAgentAff." and you get RM ".$selectBate->amount." bonus"]);

                      $createH = AgentRebateHistory::create(['user_id'=>$user_id,
                                                             'commision_id'=> $selectBate->id]);
                    }
                }
            }elseif($selectBate->type == '4'){
                //每年
                $countMonthAff = Merchant::select(DB::raw('COUNT(id) AS totalAff'))
                                    ->where('master_id', $user_id)
                                    ->where(DB::raw('DATE_FORMAT(created_at, "%Y")'), date('Y'))
                                    ->first();

                if(!empty($countMonthAff->totalAff)){

                    $exists = AgentRebateHistory::join('setting_merchant_rebates AS r', 'r.id', 'agent_rebate_histories.commision_id')
                                                ->where('user_id', $user_id)
                                                ->where('commision_id', $selectBate->id)
                                                ->where(DB::raw('DATE_FORMAT(agent_rebate_histories.created_at, "%Y")'), date('Y'))
                                                ->exists();

                    if($exists != 1){
                      $create = AffiliateCommission::create(['type'=>'1',
                                                             'user_id'=>$user_id,
                                                             'comm_pa_type'=>'Amount',
                                                             'comm_pa'=>$selectBate->amount,
                                                             'comm_amount'=>$selectBate->amount,
                                                             'comm_desc'=>"Agent Bonus - You've hit the agent bonus target of ".$totalAgentAff." and you get RM ".$selectBate->amount." bonus"]);

                      $createH = AgentRebateHistory::create(['user_id'=>$user_id,
                                                             'commision_id'=> $selectBate->id]);
                    }
                }
            }
        }
    }

    public function AgentUpgrade($master_id)
    {

        $levels = AgentLevel::get();
        $merchant = Merchant::where('code', $master_id)->first();
        
        if(!empty($merchant->id)){
          foreach($levels as $level){
              // echo $level->agent_lvl.' - '.$level->product_id.' - '.$level->buy_quantity;
              // echo "<br>";

              $transaction1 = Transaction::select(DB::raw('SUM(d.quantity) AS totalQty'))
                                                    ->join('transaction_details AS d', 'd.transaction_id', 'transactions.id')
                                                    ->where('user_id', $master_id)
                                                    ->where('transactions.status', '1')
                                                    ->first();

              $transaction2 = Transaction::select(DB::raw('SUM(d.quantity) AS totalQty'))
                                                    ->join('transaction_details AS d', 'd.transaction_id', 'transactions.id')
                                                    ->where('user_id', $master_id)
                                                    ->where('d.product_id', $level->product_id)
                                                    ->where('transactions.status', '1')
                                                    ->first();

              

              $affiliate = Merchant::select(DB::raw('COUNT(id) AS totalAffiliate'))
                                           ->where('master_id', $master_id)
                                           ->where('status', '1')
                                           ->first();

              if(!empty($level->product_id) && !empty($level->affiliate_quantity) && $level->affiliate_quantity != 0){
                  if($level->product_id == 'all' && $affiliate->totalAffiliate >= $level->affiliate_quantity){

                      if($transaction1->totalQty >= $level->buy_quantity){
                          $merchant1 = Merchant::find($merchant->id);
                          $merchant1 = $merchant1->update(['lvl'=>$level->id]);
                      }

                  }else{
                      

                      if($transaction2->totalQty >= $level->buy_quantity && $affiliate->totalAffiliate >= $level->affiliate_quantity){
                          $merchant1 = Merchant::find($merchant->id);
                          $merchant1 = $merchant1->update(['lvl'=>$level->id]);
                      }
                  }
              }else{
                  if(!empty($level->product_id)){
                      if($level->product_id == 'all'){
                          
                          if($transaction1->totalQty >= $level->buy_quantity){
                              $merchant1 = Merchant::find($merchant->id);
                              $merchant1 = $merchant1->update(['lvl'=>$level->id]);
                          }
                      }else{
                          

                          if($transaction2->totalQty >= $level->buy_quantity){

                              $merchant1 = Merchant::find($merchant->id);
                              $merchant1 = $merchant1->update(['lvl'=>$level->id]);
                          }
                      }
                  }

                  if(!empty($level->affiliate_quantity) && $level->affiliate_quantity != 0){
                      

                      if($affiliate->totalAffiliate >= $level->affiliate_quantity){
                          $merchant1 = Merchant::find($merchant->id);
                          $merchant1 = $merchant1->update(['lvl'=>$level->id]);
                      }
                  }                
              }
              
          }
          
          $detail = Merchant::where('code', $master_id)->first();

          
          return $detail->lvl;
        }

        
    }


    public function tree($agent_code)
    {
        $merchant = Merchant::where('code', $agent_code)->first();
        $admin = Merchant::where('code', $agent_code)->first();

        $merchantD = Merchant::where('master_id', $agent_code)
                             ->where('status', '1')
                             ->get();

        $mdd = [];
        $mddd = [];
        $sg  = 0;
        $tg  = 0;
        foreach($merchantD as $merchantdv){
            $mdd[$merchantdv->code] = Merchant::where('master_id', $merchantdv->code)->where('status', '1')->get();
            $sg += count($mdd[$merchantdv->code]);

            foreach($mdd[$merchantdv->code] as $mddv){
                $mddd[$mddv->code] = Merchant::where('master_id', $mddv->code)->where('status', '1')->get();
                $tg += count($mddd[$mddv->code]);
            }
        }

        $fg = count($merchantD);
        

        $total = $fg + $sg + $tg;
        // echo $tg;
        if($total > 0){
            $fgp = round($fg / $total * 100, 2);
            $sgp = round($sg / $total * 100, 2);
            $tgp = round($tg / $total * 100, 2);            
        }else{
            $fgp = 0;
            $sgp = 0;
            $tgp = 0;            
        }


        return view('backend.merchants.tree', ['merchantD'=>$merchantD, 'merchant'=>$merchant,
                                               'fg'=>$fg, 'fgp'=>$fgp,
                                               'sg'=>$sg, 'sgp'=>$sgp,
                                               'tg'=>$tg, 'tgp'=>$tgp], compact('mdd', 'mddd'));
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
            $merchants = Merchant::where('master_id', $agent_code)
                                 ->where('status', '1')
                                 ->get();
        }elseif($g == 2){
            $merchants = Merchant::select('d.*')
                                 ->join('merchants AS d', 'd.master_id', 'merchants.code')
                                 ->where('merchants.master_id', $agent_code)
                                 ->where('d.status', '1')
                                 ->get();

        }else{
            $merchants = Merchant::select('dd.*')
                                 ->join('merchants AS d', 'd.master_id', 'merchants.code')
                                 ->join('merchants AS dd', 'dd.master_id', 'd.code')
                                 ->where('merchants.master_id', $agent_code)
                                 ->where('dd.status', '1')
                                 ->get();
        }


        return view('backend.merchants.tree_details', ['generation'=>$generation, 'merchants'=>$merchants]);
    }

    public function GenerateTopupNo()
    {
      $topup = TopupTransaction::select(DB::raw('COUNT(id) AS TotalTopup'))->first();
      $TotalTopup = $topup->TotalTopup + 1;

      if(strlen($TotalTopup) == 1){
          $TNo = 'T'.strtotime(date('Y-m-d H:i:s'))."0000".$TotalTopup;
      }elseif(strlen($TotalTopup) == 2){
          $TNo = 'T'.strtotime(date('Y-m-d H:i:s'))."000".$TotalTopup;
      }elseif(strlen($TotalTopup) == 3){
          $TNo = 'T'.strtotime(date('Y-m-d H:i:s'))."00".$TotalTopup;
      }elseif(strlen($TotalTopup) == 4){
          $TNo = 'T'.strtotime(date('Y-m-d H:i:s'))."0".$TotalTopup;
      }else{
          $TNo = 'T'.strtotime(date('Y-m-d H:i:s')).$TotalTopup;
      }
      return $TNo;
    }
}
