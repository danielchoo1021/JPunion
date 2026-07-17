<?php

namespace App\Http\Controllers;

// use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\UserShippingAddress;
use App\AgentLevel;
use App\State;
use App\TblCountry;
use App\Merchant;
use App\Admin;
use App\AgentLevelRecord;
use App\User;
use App\PartnerLevel;
use App\City;
use App\Agent;

use DB, Auth, Validator, Redirect, Toastr;

class UserShippingAddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $address_book = UserShippingAddress::select('user_shipping_addresses.*', 's.name as state_name', 'c.city_name', 'tc.country_name')
                                           ->leftjoin('states as s', 's.id', 'user_shipping_addresses.state')
                                           ->leftjoin('cities as c', 'c.id', 'user_shipping_addresses.city')
                                           ->leftJoin('tbl_countries as tc', 'tc.country_id', 'user_shipping_addresses.country')
                                           ->where('user_id', Auth::user()->code)
                                           ->orderBy('user_shipping_addresses.default', 'desc') 
                                           ->orderBy('user_shipping_addresses.id', 'desc')
                                           ->groupBy('user_shipping_addresses.id');
        $queries = [];
        $columns = [
           'name', 'address', 'phone'
        ];

        foreach($columns as $column){
            if(request()->has($column) && !empty(request($column))){
              
                $address_book = $address_book->where($column, 'like', "%".request($column)."%");

                $queries[$column] = request($column);

            }
        }

        $address_book = $address_book->paginate(10)->appends($queries);

        $count = count($address_book);

        $lvl = "";
        $partner_lvl = "";
        $city_agent = "";
        $state_agent = "";
        $agentLVL = AgentLevel::find(Auth::user()->lvl);
        $PartnerAgentLVL = PartnerLevel::find(Auth::user()->lvl);

        if(!empty($agentLVL->id)){
            $lvl = $agentLVL->agent_lvl." (".$agentLVL->agent_lvl_cn.")";
        }

        if(!empty($PartnerAgentLVL->id)){
            $partner_lvl = $PartnerAgentLVL->partner_lvl." (".$PartnerAgentLVL->partner_lvl_cn.")";
        }

        if(!empty(Auth::user()->city_agent)){
            $get_city = City::find(Auth::user()->city_agent);
            $city_agent = $get_city->city_name;
        }

        if(!empty(Auth::user()->state_agent)){
            $get_state = State::find(Auth::user()->state_agent);
            $state_agent = $get_state->name;
        }

        $getMUpline = Agent::where('code', Auth::user()->master_id)->first();
        $getAUpline = Admin::where('code', Auth::user()->master_id)->first();

        $upline_name = "";
        $upline_code = "";
        if(!empty($getMUpline->id)){
          $upline_name = $getMUpline->f_name.' '.$getMUpline->l_name;
          $upline_code = $getMUpline->display_code.$getMUpline->display_running_no;
        }

        if(!empty($getAUpline->id)){
          $upline_name = $getAUpline->f_name.' '.$getAUpline->l_name;
          $upline_code = $getAUpline->display_code.$getAUpline->display_running_no;
        }

        $upgrade_record = AgentLevelRecord::where('user_id', Auth::user()->code)->get();

        $aff_joined_date = [];
        if(Auth::guard('agent') || Auth::guard('admin')){
            $allAffiliateRecord = User::where('status', '3')->get();

            $allMerchants = Agent::where('status', '1')->get();

            foreach($allAffiliateRecord as $affiliate_record){
                $ic_record = explode('-', $affiliate_record->ic);
                foreach($allMerchants as $affiliate){
                    if($affiliate->ic == $ic_record[0]){
                        $aff_joined_date[$affiliate->code] = $affiliate_record;
                    }
                }
            }
        }

        $own_display_code = Auth::user()->display_code.Auth::user()->display_running_no;
        if(!empty(Auth::user()->relegated_from_agent)){
            $original_agent = Agent::where('code', Auth::user()->relegated_from_agent)
                                    ->where('status', 55)
                                    ->first();

            if(!empty($original_agent->id)){
                $own_display_code = $original_agent->display_code.$original_agent->display_running_no;
            }
        }

        return view('frontend.address_book', ['address_book'=>$address_book, 
                                              'count'=>$count, 
                                              'lvl'=>$lvl,
                                              'upline_name'=>$upline_name, 
                                              'upline_code'=>$upline_code, 
                                              'upgrade_record'=>$upgrade_record,
                                              'own_display_code'=>$own_display_code,
                                              'partner_lvl'=>$partner_lvl,
                                              'city_agent'=>$city_agent,
                                              'state_agent'=>$state_agent], 
                                              compact('aff_joined_date'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $lvl = "";
        $partner_lvl = "";
        $city_agent = "";
        $state_agent = "";
        $agentLVL = AgentLevel::find(Auth::user()->lvl);
        $PartnerAgentLVL = PartnerLevel::find(Auth::user()->lvl);

        if(!empty($agentLVL->id)){
            $lvl = $agentLVL->agent_lvl." (".$agentLVL->agent_lvl_cn.")";
        }

        if(!empty($PartnerAgentLVL->id)){
            $partner_lvl = $PartnerAgentLVL->partner_lvl." (".$PartnerAgentLVL->partner_lvl_cn.")";
        }

        if(!empty(Auth::user()->city_agent)){
            $get_city = City::find(Auth::user()->city_agent);
            $city_agent = $get_city->city_name;
        }

        if(!empty(Auth::user()->state_agent)){
            $get_state = State::find(Auth::user()->state_agent);
            $state_agent = $get_state->name;
        }

        $states = State::get();
        
        
        // $countries = TblCountry::whereIn('country_id', ['243', '104', '49', '119', '160', '200'])
        //                        ->orderBy('country_name', 'asc')
        //                        ->get();
        $countries = GlobalController::global_countries();

        $getMUpline = Agent::where('code', Auth::user()->master_id)->first();
        $getAUpline = Admin::where('code', Auth::user()->master_id)->first();

        $upline_name = "";
        $upline_code = "";
        if(!empty($getMUpline->id)){
          $upline_name = $getMUpline->f_name.' '.$getMUpline->l_name;
          $upline_code = $getMUpline->display_code.$getMUpline->display_running_no;
        }

        if(!empty($getAUpline->id)){
          $upline_name = $getAUpline->f_name.' '.$getAUpline->l_name;
          $upline_code = $getAUpline->display_code.$getAUpline->display_running_no;
        }

        $upgrade_record = AgentLevelRecord::where('user_id', Auth::user()->code)->get();

        $aff_joined_date = [];
        if(Auth::guard('agent') || Auth::guard('admin')){
            $allAffiliateRecord = User::where('status', '3')->get();
            $allMerchants = Agent::where('status', '1')->get();

            foreach($allAffiliateRecord as $affiliate_record){
                $ic_record = explode('-', $affiliate_record->ic);
                foreach($allMerchants as $affiliate){
                    if($affiliate->ic == $ic_record[0]){
                        $aff_joined_date[$affiliate->code] = $affiliate_record;
                    }
                }
            }
        }

        $own_display_code = Auth::user()->display_code.Auth::user()->display_running_no;
        if(!empty(Auth::user()->relegated_from_agent)){
            $original_agent = Agent::where('code', Auth::user()->relegated_from_agent)
                                    ->where('status', 55)
                                    ->first();

            if(!empty($original_agent->id)){
                $own_display_code = $original_agent->display_code.$original_agent->display_running_no;
            }
        }

        return view('frontend.address_book_create', ['lvl'=>$lvl, 
                                                     'states'=>$states, 
                                                     'countries'=>$countries,
                                                     'upline_name'=>$upline_name, 
                                                     'upline_code'=>$upline_code,
                                                     'upgrade_record'=>$upgrade_record, 
                                                     'own_display_code'=>$own_display_code,
                                                     'partner_lvl'=>$partner_lvl,
                                                     'city_agent'=>$city_agent,
                                                     'state_agent'=>$state_agent], 
                                                     compact('aff_joined_date'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        \DB::beginTransaction();

        try{
            // \DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'f_name' => 'required',
                'country_code' => 'required',
                'phone' => 'required',
                'email' => 'required',
                'address' => 'required',
                'postcode' => 'required',
                'city' => 'required',
                'state' => 'required',
                'country' => 'required',
            ]);

            if ($validator->fails()) {
                return Redirect::back()->withInput($request->all())->withErrors($validator);
            }

            // Check phone number
            $phone = $request->phone;
            if(substr($phone, 0, 1) == '0'){
                $phone = substr($phone, 1);
            }
            
            UserShippingAddress::where('user_id', Auth::user()->code)
            ->update(['default' => 0]);
            
            UserShippingAddress::create([
            'user_id'       => Auth::user()->code,
            'f_name'        => $request->f_name,
            'country_code'  => $request->country_code,
            'phone'         => $phone,
            'email'         => $request->email,
            'address'       => $request->address,
            'postcode'      => $request->postcode,
            'city'          => $request->city,
            'state'         => $request->state,
            'country'       => $request->country,
            'default'       => 1,
        ]);
            // $insert = new UserShippingAddress();
            // $insert->user_id = Auth::user()->code;
            // $insert->f_name = $request->f_name;
            // $insert->country_code = $request->country_code;
            // $insert->phone = $phone;
            // $insert->email = $request->email;
            // $insert->address = $request->address;
            // $insert->postcode = $request->postcode;
            // $insert->city = $request->city;
            // $insert->state = $request->state;
            // $insert->country = $request->country;
            // $insert->default = 1;
            // $insert->save();

            \DB::commit();
        }catch (\Exception $e){
            \DB::rollback();
            return Redirect::back()->withInput($request->all())->withErrors($e->getMessage());
        }catch(\Error $e){
            \DB::rollback();
            return Redirect::back()->withInput($request->all())->withErrors($e->getMessage());
        }

        if(isset($_COOKIE['global_language']) && !empty($_COOKIE['global_language'])){
            if($_COOKIE['global_language'] == '1'){
                Toastr::success("Address created successfully!");
            }else{
                Toastr::success("地址创建成功!");
            }
        }else{
            Toastr::success("地址创建成功!");
        }
        return redirect()->route('AddressBook.AddressBook.index');
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
        $address = UserShippingAddress::where(DB::raw('md5(id)'), $id)
            ->where('user_id', Auth::user()->code)
            ->firstOrFail();
        // if(empty($address->id)){
        //   abort(404);
        // }

        $lvl = "";
        $partner_lvl = "";
        $city_agent = "";
        $state_agent = "";
        $agentLVL = AgentLevel::find(Auth::user()->lvl);
        $PartnerAgentLVL = PartnerLevel::find(Auth::user()->lvl);

        if(!empty($agentLVL->id)){
            $lvl = $agentLVL->agent_lvl." (".$agentLVL->agent_lvl_cn.")";
        }

        if(!empty($PartnerAgentLVL->id)){
            $partner_lvl = $PartnerAgentLVL->partner_lvl." (".$PartnerAgentLVL->partner_lvl_cn.")";
        }

        if(!empty(Auth::user()->city_agent)){
            $get_city = City::find(Auth::user()->city_agent);
            $city_agent = $get_city->city_name;
        }

        if(!empty(Auth::user()->state_agent)){
            $get_state = State::find(Auth::user()->state_agent);
            $state_agent = $get_state->name;
        }

        $states = State::get();
        
        
        // $countries = TblCountry::whereIn('country_id', ['243', '104', '49', '119', '160', '200'])
        //                        ->orderBy('country_name', 'asc')
        //                        ->get();

        $countries = GlobalController::global_countries();

        $getMUpline = Agent::where('code', Auth::user()->master_id)->first();
        $getAUpline = Admin::where('code', Auth::user()->master_id)->first();

        $upline_name = "";
        $upline_code = "";
        if(!empty($getMUpline->id)){
          $upline_name = $getMUpline->f_name.' '.$getMUpline->l_name;
          $upline_code = $getMUpline->display_code.$getMUpline->display_running_no;
        }

        if(!empty($getAUpline->id)){
          $upline_name = $getAUpline->f_name.' '.$getAUpline->l_name;
          $upline_code = $getAUpline->display_code.$getAUpline->display_running_no;
        }

        $upgrade_record = AgentLevelRecord::where('user_id', Auth::user()->code)->get();

        $aff_joined_date = [];
        if(Auth::guard('agent') || Auth::guard('admin')){
            $allAffiliateRecord = User::where('status', '3')->get();
            $allMerchants = Agent::where('status', '1')->get();

            foreach($allAffiliateRecord as $affiliate_record){
                $ic_record = explode('-', $affiliate_record->ic);
                foreach($allMerchants as $affiliate){
                    if($affiliate->ic == $ic_record[0]){
                        $aff_joined_date[$affiliate->code] = $affiliate_record;
                    }
                }
            }
        }

        $own_display_code = Auth::user()->display_code.Auth::user()->display_running_no;
        if(!empty(Auth::user()->relegated_from_agent)){
            $original_agent = Agent::where('code', Auth::user()->relegated_from_agent)
                                    ->where('status', 55)
                                    ->first();

            if(!empty($original_agent->id)){
                $own_display_code = $original_agent->display_code.$original_agent->display_running_no;
            }
        }

        return view('frontend.address_book_edit', ['address'=>$address, 
                                                   'lvl'=>$lvl, 
                                                   'states'=>$states, 
                                                   'countries'=>$countries,
                                                   'upline_name'=>$upline_name, 
                                                   'upline_code'=>$upline_code,
                                                   'upgrade_record'=>$upgrade_record, 
                                                   'own_display_code'=>$own_display_code,
                                                   'partner_lvl'=>$partner_lvl,
                                                   'city_agent'=>$city_agent,
                                                   'state_agent'=>$state_agent], 
                                                   compact('aff_joined_date'));
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
        \DB::beginTransaction();

        try{
            // \DB::beginTransaction();
            
            $validator = Validator::make($request->all(), [
                'f_name' => 'required',
                'country_code' => 'required',
                'phone' => 'required',
                'email' => 'required',
                'address' => 'required',
                'postcode' => 'required',
                'city' => 'required',
                'state' => 'required',
                'country' => 'required',
            ]);

            if ($validator->fails()) {
                return Redirect::back()->withInput($request->all())->withErrors($validator);
            }

            // Check phone number
            $phone = $request->phone;
            if(substr($phone, 0, 1) == '0'){
                $phone = substr($phone, 1);
            }

            UserShippingAddress::where('user_id', Auth::user()->code)
                        ->update(['default' => 0]);

            $address = UserShippingAddress::where(DB::raw('md5(id)'), md5($id))
                ->where('user_id', Auth::user()->code)
                ->firstOrFail();

            $address->update([
                'f_name'        => $request->f_name,
                'country_code'  => $request->country_code,
                'phone'         => $phone,
                'email'         => $request->email,
                'address'       => $request->address,
                'postcode'      => $request->postcode,
                'city'          => $request->city,
                'state'         => $request->state,
                'country'       => $request->country,
                'default'       => 1,   
            ]);
            // $update = UserShippingAddress::find($id);
            // $update->f_name = $request->f_name;
            // $update->country_code = $request->country_code;
            // $update->phone = $phone;
            // $update->email = $request->email;
            // $update->address = $request->address;
            // $update->postcode = $request->postcode;
            // $update->city = $request->city;
            // $update->state = $request->state;
            // $update->country = $request->country;
            // $update->default = 1;
            // $update->save();

            \DB::commit();
        }catch (\Exception $e){
            \DB::rollback();
            return Redirect::back()->withInput($request->all())->withErrors($e->getMessage());
        }catch(\Error $e){
            \DB::rollback();
            return Redirect::back()->withInput($request->all())->withErrors($e->getMessage());
        }

        if(isset($_COOKIE['global_language']) && !empty($_COOKIE['global_language'])){
            if($_COOKIE['global_language'] == '1'){
                Toastr::success("地址更新成功!");
            }else{
                Toastr::success("Address updated successfully!");
            }
        }else{
            Toastr::success("Address updated successfully!");
        }
        return redirect()->back();
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
