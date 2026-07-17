<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use App\SettingMerchantBonus;
use App\SettingMerchantRebate;
use App\SettingMerchantCommission;
use App\SettingPerformanceDividend;
use App\SettingPerformanceMain;
use App\SettingTeamDividend;
use App\SettingTeamMain;
use App\SettingRefferalReward;
use App\AgentLevel;
use App\Product;
use App\Transaction;
use App\Merchant;
use App\SettingShippingFee;
use App\SettingUom;
use App\SettingBanner;
use App\SettingDualMain;
use App\SettingDualCommission;
use App\SettingMonthlyAgentSalesBonus;
use App\SettingDownlineBonus;
use App\State;
use App\SettingPickUpAddress;
use App\SettingTopup;
use App\WebsiteSetting;
use App\SettingSignatureDish;
use App\CodAddress;
use App\SettingCommission;
use App\SettingPv;
use App\SettingCommRanking;
use App\CustomerLevel;
use App\PartnerLevel;
use App\AreaAgentLevel;
use App\SettingAgentReferralPeriod;
use App\SalesPopup;
use App\SettingRetailCommission;
use App\SettingLooBonus;
use App\SettingLolBonus;
use App\SettingJoiningFee;
use App\SettingMerchantSameLevel;
use App\TblCountry;
use App\AffiliateCommission;
use App\Affiliate;
use App\User;
use App\SettingUplineBonus;
use App\SettingPackageRebate;
use App\SettingPrizePool;
use App\SettingHomePage;
use App\SettingPrizePoolCondition;
use App\SettingWebsiteMessage;
use App\SettingHeader;
use App\SettingSecondBanner;
use App\SettingHomeVideo;
use App\SettingPaymentGateway;
use App\SettingColour;
 

use App\Http\Controllers\GlobalController;
use App\SettingEinvoice;
use Validator, Redirect, Toastr, DB, File, Auth;

class SettingController extends Controller
{   
    public function setting_merchant_bonus()
    {
        $selects = SettingMerchantBonus::get();
        $levels = AgentLevel::where('status', '1')->get();

        // $selectDetails = [];

        // foreach($selects as $select){
        //     $selectDetails[$select->agent_lvl] = array($select->id, $select->type, $select->amount);
        // }

        return view('backend.settings.setting_merchant_bonus', ['selects'=>$selects, 'levels'=>$levels]);
    }

    public function save_setting_merchant_bonus(Request $request)
    {
        $translation_data = GlobalController::get_translations(); 
        $insert = [];
        $caseString = $caseString1 = $caseString2 = 'case id';
        $ids = '';

        for($a=0; $a<count($request->amount); $a++){

            if(!empty($request->sid[$a])){
                
                $sid = $request->sid[$a];
                $qty = $request->qty[$a];
                $type = $request->type[$a];
                $amount = $request->amount[$a];

                $caseString .= " when $sid then '$qty'";
                $caseString1 .= " when $sid then '$type'";
                $caseString2 .= " when $sid then '$amount'";

                $ids .= "$sid,";
            }else{
                
                if(!empty($request->amount[$a])){
                    
                    $insert[] = [
                                    "agent_lvl"=>$request->lvl[$a],
                                    "qty"=>$request->qty[$a],
                                    "type"=>$request->type[$a],
                                    "amount"=>$request->amount[$a],
                                ];
                }
            }
            
        }
        
        $ids = trim($ids, ',');

        $create = SettingMerchantBonus::insert($insert);
        if($ids != ''){
            DB::update("update setting_merchant_bonuses set qty = $caseString end,
                                                            type = $caseString1 end,
                                                            amount = $caseString2 end
                                                            where id in ($ids)");
        }

        Toastr::success($translation_data['backendlang']['backendlang']['Setting Agent Rebate Successful'] ?? 'Setting Agent Rebate Successful');
        return redirect()->route('setting_merchant_bonus');
    } 

    public function setting_agent_rebate()
    {
        $levels = AgentLevel::where('status', '1');
        if(Auth::guard('merchant')->check()){
        $levels = $levels->where('merchant_id', Auth::user()->code);    
        }else{
        $levels = $levels->whereNull('merchant_id');
        }
        $levels = $levels->get();

        $agent_lvl_rebate = [];
        foreach($levels as $level){
            $agent_lvl_rebate[$level->id] = SettingMerchantRebate::where('agent_lvl', $level->id)->first();
        }

        if(isset($_COOKIE['vmerchant']) && !empty($_COOKIE['vmerchant'])){
            $merchant = Merchant::where(DB::raw('md5(id)'), $_COOKIE['vmerchant'])->first();
            if(!empty($merchant->id)){
                $get_authorise_status = GlobalController::check_autorize_status(md5($merchant->id));
                if($get_authorise_status['status'] == 1){
                    $website_setting = $merchant;
                }else{
                    $website_setting = WebsiteSetting::find(1);
                }
            }else{
                $website_setting = WebsiteSetting::find(1);
            }
        }else{
            $website_setting = WebsiteSetting::find(1);
        }

        return view('backend.settings.setting_agent_rebate',  compact('levels', 
                                                                      'agent_lvl_rebate',
                                                                      'website_setting'));
    }

    public function save_setting_agent_rebate(Request $request)
    {
        $translation_data = GlobalController::get_translations(); 
        try{
            \DB::beginTransaction();

            for($a=0; $a<count($request->agent_lvl); $a++){

                if(!empty($request->rebate_id[$a])){
                    $rebate_bonus = SettingMerchantRebate::find($request->rebate_id[$a]);
                }else{
                    $rebate_bonus = new SettingMerchantRebate();
                }

                if($request->type[$a] == 'Percentage' && $request->amount[$a] > 100){
                    Toastr::error($translation_data['backendlang']['backendlang']['Percentage cannot be over 100'] ?? 'Percentage cannot be over 100');
                    return Redirect::back();
                }

                $rebate_bonus->agent_lvl = $request->agent_lvl[$a];
                $rebate_bonus->type = $request->type[$a];
                $rebate_bonus->amount = $request->amount[$a];
                $rebate_bonus->save();
            }

            if(isset($_COOKIE['vmerchant']) && !empty($_COOKIE['vmerchant'])){
                $merchant = Merchant::where(DB::raw('md5(id)'), $_COOKIE['vmerchant'])->first();
                if(!empty($merchant->id)){
                    $get_authorise_status = GlobalController::check_autorize_status(md5($merchant->id));
                    if($get_authorise_status['status'] == 1){
                        $website_setting = $merchant;
                    }else{
                        $website_setting = WebsiteSetting::find(1);
                    }
                }else{
                    $website_setting = WebsiteSetting::find(1);
                }
            }else{
                $website_setting = WebsiteSetting::find(1);
            }

            $website_setting->member_rebate_type = $request->member_rebate_type;
            $website_setting->member_rebate_amount = $request->member_rebate_amount;
            $website_setting->save();

            \DB::commit();
        }catch (\Exception $e){
            \DB::rollback();
            Toastr::error($e->getMessage());
            return Redirect::back()->withInput($request->all())->withErrors($e->getMessage());
        }catch(\Error $e){
            \DB::rollback();
            Toastr::error($e->getMessage());
            return Redirect::back()->withInput($request->all())->withErrors($e->getMessage());
        }

        Toastr::success($translation_data['backendlang']['backendlang']['Merchant Bonus Updated!'] ?? 'Merchant Bonus Updated!');
        return redirect()->route('setting_agent_rebate');
    }

    public function setting_merchant_commission()
    {
        $setting_merchant_commissions = SettingMerchantCommission::get();
        
        $levels = AgentLevel::where('status', '1');
        if(Auth::guard('merchant')->check()){
        $levels = $levels->where('merchant_id', Auth::user()->code);    
        }else{
        $levels = $levels->whereNull('merchant_id');
        }
        $levels = $levels->get();

        $value = [];
        foreach($setting_merchant_commissions as $smc){
            $value[$smc->level][$smc->agent_lvl] = array($smc->comm_type, $smc->comm_amount, $smc->id);
        }

        if(isset($_COOKIE['vmerchant']) && !empty($_COOKIE['vmerchant'])){
            $merchant = Merchant::where(DB::raw('md5(id)'), $_COOKIE['vmerchant'])->first();
            if(!empty($merchant->id)){
                $get_authorise_status = GlobalController::check_autorize_status(md5($merchant->id));
                if($get_authorise_status['status'] == 1){
                    $website_setting = $merchant;
                }else{
                    $website_setting = WebsiteSetting::find(1);
                }
            }else{
                $website_setting = WebsiteSetting::find(1);
            }
        }else{
            $website_setting = WebsiteSetting::find(1);
        }

        return view('backend.settings.setting_merchant_commission', compact('levels', 
                                                                            'value',
                                                                            'website_setting'));
    }

    public function save_setting_merchant_commission(Request $request)
    {
        $translation_data = GlobalController::get_translations();
        try{
            \DB::beginTransaction();

            for($a=0; $a<count($request->comm_amount); $a++){

                if(!empty($request->ids[$a])){
                    $rebate_bonus = SettingMerchantCommission::find($request->ids[$a]);
                }else{
                    $rebate_bonus = new SettingMerchantCommission();
                }

                $rebate_bonus->agent_lvl = $request->agent_lvl[$a];
                $rebate_bonus->level = $request->level[$a];
                $rebate_bonus->comm_type = $request->comm_type[$a];
                $rebate_bonus->comm_amount = $request->comm_amount[$a];
                $rebate_bonus->save();
            }

            if(isset($_COOKIE['vmerchant']) && !empty($_COOKIE['vmerchant'])){
                $merchant = Merchant::where(DB::raw('md5(id)'), $_COOKIE['vmerchant'])->first();
                if(!empty($merchant->id)){
                    $get_authorise_status = GlobalController::check_autorize_status(md5($merchant->id));
                    if($get_authorise_status['status'] == 1){
                        $website_setting = $merchant;
                    }else{
                        $website_setting = WebsiteSetting::find(1);
                    }
                }else{
                    $website_setting = WebsiteSetting::find(1);
                }
            }else{
                $website_setting = WebsiteSetting::find(1);
            }

            $website_setting->member_heirarchy_one_type = $request->member_heirarchy_one_type;
            $website_setting->member_heirarchy_one_amount = $request->member_heirarchy_one_amount;
            $website_setting->member_heirarchy_two_type = $request->member_heirarchy_two_type;
            $website_setting->member_heirarchy_two_amount = $request->member_heirarchy_two_amount;
            $website_setting->member_heirarchy_three_type = $request->member_heirarchy_three_type;
            $website_setting->member_heirarchy_three_amount = $request->member_heirarchy_three_amount;
            $website_setting->save();

            \DB::commit();
        }catch (\Exception $e){
            \DB::rollback();
            Toastr::error($e->getMessage());
            return Redirect::back()->withInput($request->all())->withErrors($e->getMessage());
        }catch(\Error $e){
            \DB::rollback();
            Toastr::error($e->getMessage());
            return Redirect::back()->withInput($request->all())->withErrors($e->getMessage());
        }

        Toastr::success($translation_data['backendlang']['backendlang']['Setting Heirarchy Bonus Successful'] ?? 'Setting Heirarchy Bonus Successful');
        return redirect()->route('setting_merchant_commission');
    }

    public function setting_commission()
    {
        $agents = [];

        $agent_lvl = AgentLevel::where('status', '1')
                               ->get();
        foreach ($agent_lvl as $key => $lvl) {
            $agents[$key] = SettingCommission::where('user_type', 'Agent')
                                             ->where('agent_level', $lvl->id)
                                             ->get();
        }

        return view('backend.settings.setting_commission', ['agents'=>$agents, 'agent_lvl'=>$agent_lvl], compact('agents'));
    }

    public function save_setting_commission(Request $request)
    {
        $translation_data = GlobalController::get_translations();
        $caseString = $caseString1 = $caseString2 = "case id";
        $ids = '';

        $insert = [];
        for($a=0; $a<count($request->comm_amount); $a++){
            if(!empty($request->agent_lvl[$a])){
                //Update
                $id = $request->agent_lvl[$a];
                $comm_type = $request->comm_type[$a];
                $comm_amount = !empty($request->comm_amount[$a]) ? $request->comm_amount[$a] : 0;
                $lvl_id = $request->lvl_id[$a];

                $caseString .= " when $id then '$comm_type'";
                $caseString1 .= " when $id then '$comm_amount'";
                $caseString2 .= " when $id then '$lvl_id'";

                $ids .= "$id,";
            }else{
                //Create

                $insert[] = [
                                'user_type'=>$request->type[$a],
                                'level'=>$request->level[$a],
                                'comm_type'=>$request->comm_type[$a],
                                'comm_amount'=>!empty($request->comm_amount[$a]) ? $request->comm_amount[$a] : 0,
                                'agent_level'=>$request->lvl_id[$a]
                            ];
            }
        }

        $ids = trim($ids, ',');

        if($ids != ''){
            DB::update("update setting_commissions set comm_type = $caseString end,
                                                                comm_amount = $caseString1 end,
                                                                agent_level = $caseString2 end
                                                                where id in ($ids)");
        }


        $create = SettingCommission::insert($insert);

        Toastr::success($translation_data['backendlang']['backendlang']['Setting Commission Successful'] ?? 'Setting Commission Successful');
        return redirect()->route('setting_commission');
    }

    public function setting_performance_dividend()
    {
        $selects = SettingPerformanceDividend::get();
        
        $levels = AgentLevel::where('status', '1');
        if(Auth::guard('merchant')->check()){
        $levels = $levels->where('merchant_id', Auth::user()->code);    
        }else{
        $levels = $levels->whereNull('merchant_id');
        }
        $levels = $levels->get();

        $setting = SettingPerformanceMain::first();

        $selectDetails = [];

        foreach($selects as $select){
            $selectDetails[$select->lvl] = array($select->id, $select->type, $select->amount, $select->target);
        }

        return view('backend.settings.setting_performance_dividend', ['levels'=>$levels, 'setting'=>$setting], compact('selectDetails'));
    }

    public function save_setting_performance_dividend(Request $request)
    {
        $translation_data = GlobalController::get_translations();  
        $input = $request->all();
        
        $insert = [];
        $caseString1 = $caseString2 = $caseString3 = 'case id';

        $ids = "";
        for($a=0; $a<count($request->amount); $a++){

            if(!empty($request->sid[$a])){
                $sid = $request->sid[$a];

                $amount = $request->amount[$a];
                $lvl = $request->lvl[$a];
                $target = $request->target[$a];

                $caseString1 .= " when $sid then '$target'";
                $caseString2 .= " when $sid then '$amount'";
                $caseString3 .= " when $sid then '$lvl'";

                $ids .= "$sid,";
            }else{
                if(!empty($request->amount[$a])){

                    $insert[] = [
                                    "target"=>$request->target[$a],
                                    "amount"=>$request->amount[$a],
                                    "lvl"=>$request->lvl[$a],
                                    "date_update"=>date('Y-m-d H:i:s'),
                                    "created_at"=>date('Y-m-d H:i:s'),
                                    "updated_at"=>date('Y-m-d H:i:s'),
                                ];
                }
            }
            
        }
        $ids = trim($ids, ',');

        $create = SettingPerformanceDividend::insert($insert);
        if($ids != ''){
            DB::update("update setting_performance_dividends set amount = $caseString2 end,
                                                                    lvl = $caseString3 end,
                                                                 target = $caseString1 end
                                                               where id in ($ids)");
        }

        $checkSetting = SettingPerformanceMain::first();
        if(!empty($checkSetting->id)){
            $setting = SettingPerformanceMain::find(1);
            $setting = $setting->update($input);
        }else{
            $setting = SettingPerformanceMain::create($input);            
        }
        

        Toastr::success($translation_data['backendlang']['backendlang']['Setting Performance Dividend Successful'] ?? 'Setting Performance Dividend Successful');
        return redirect()->route('setting_performance_dividend');
    }

     public function setting_team_dividend()
    {
        $selects = SettingTeamDividend::get();
        
        $levels = AgentLevel::where('status', '1');
        if(Auth::guard('merchant')->check()){
        $levels = $levels->where('merchant_id', Auth::user()->code);    
        }else{
        $levels = $levels->whereNull('merchant_id');
        }
        $levels = $levels->get();
        
        $setting = SettingTeamMain::first();

        $selectDetails = [];

        foreach($selects as $select){
            $selectDetails[$select->lvl] = array($select->id, $select->target_box, $select->amount, $select->target);
        }

        return view('backend.settings.setting_team_dividend', ['selects'=>$selects, 'levels'=>$levels, 'setting'=>$setting], compact('selectDetails'));
    }

    public function save_setting_team_dividend(Request $request)
    {
        $translation_data = GlobalController::get_translations();
        try{

            \DB::beginTransaction();

            $caseString = $caseString1 = "case id";
            $ids = '';
            for($a=0; $a<count($request->amount); $a++){
                if(!empty($request->sid[$a])){
                    $referral_reward = SettingTeamDividend::find($request->sid[$a]);   
                }else{
                    $referral_reward = new SettingTeamDividend();
                }
                $referral_reward->amount = $request->amount[$a];
                $referral_reward->target_box = $request->point_target[$a];
                if(!empty($request->amount[$a])){
                $referral_reward->save();   
                }

                \DB::commit();
            }

        } catch (\Exception $e){
            \DB::rollback();
            return Redirect::back()->withInput($request->all())->withErrors($e->getMessage());
        }

        Toastr::success($translation_data['backendlang']['backendlang']['Setting Team Dividend Successful'] ?? 'Setting Team Dividend Successful');
        return redirect()->route('setting_team_dividend');
    }


    public function setting_recommend_bonus()
    {
        $selects = SettingRefferalReward::get();
        
        
        $levels = AgentLevel::where('status', '1');
        if(Auth::guard('merchant')->check()){
        $levels = $levels->where('merchant_id', Auth::user()->code);    
        }else{
        $levels = $levels->whereNull('merchant_id');
        }
        $levels = $levels->get();

        $selectDetails = [];

        foreach($selects as $select){
            $selectDetails[$select->agent_lvl] = array($select->id, $select->amount, $select->direct_downlines_no);
        }

        if(isset($_COOKIE['vmerchant']) && !empty($_COOKIE['vmerchant'])){
            $merchant = Merchant::where(DB::raw('md5(id)'), $_COOKIE['vmerchant'])->first();
            if(!empty($merchant->id)){
                $get_authorise_status = GlobalController::check_autorize_status(md5($merchant->id));
                if($get_authorise_status['status'] == 1){
                    $website_setting = $merchant;
                }else{
                    $website_setting = WebsiteSetting::find(1);
                }
            }else{
                $website_setting = WebsiteSetting::find(1);
            }
        }else{
            $website_setting = WebsiteSetting::find(1);
        }

        return view('backend.settings.setting_recommend_bonus', ['levels'=>$levels], compact('selectDetails', 'website_setting'));
    }

    public function save_setting_recommend_bonus(Request $request)
    {
        $translation_data = GlobalController::get_translations();
        try{

            \DB::beginTransaction();

            $caseString = $caseString1 = "case id";
            $ids = '';
            for($a=0; $a<count($request->amount); $a++){
                if(!empty($request->ids[$a])){
                    $referral_reward = SettingRefferalReward::find($request->ids[$a]);   
                }else{
                    $referral_reward = new SettingRefferalReward();
                }
                $referral_reward->agent_lvl = $request->agent_lvl[$a];
                $referral_reward->amount = $request->amount[$a];
                $referral_reward->direct_downlines_no = $request->direct_downlines_no[$a];

                $referral_reward->save();

                if(isset($_COOKIE['vmerchant']) && !empty($_COOKIE['vmerchant'])){
                    $merchant = Merchant::where(DB::raw('md5(id)'), $_COOKIE['vmerchant'])->first();
                    if(!empty($merchant->id)){
                        $get_authorise_status = GlobalController::check_autorize_status(md5($merchant->id));
                        if($get_authorise_status['status'] == 1){
                            $website_setting = $merchant;
                        }else{
                            $website_setting = WebsiteSetting::find(1);
                        }
                    }else{
                        $website_setting = WebsiteSetting::find(1);
                    }
                }else{
                    $website_setting = WebsiteSetting::find(1);
                }

                $website_setting->member_referral_target = $request->member_referral_target;
                $website_setting->member_referral_amount = $request->member_referral_amount;
                $website_setting->save();

                \DB::commit();
            }

        } catch (\Exception $e){
            \DB::rollback();
            return Redirect::back()->withInput($request->all())->withErrors($e->getMessage());
        }
        

        Toastr::success($translation_data['backendlang']['backendlang']['Setting Recommended Reward Successful'] ?? 'Setting Recommended Reward Successful');
        return redirect()->route('setting_recommend_bonus');
    }

    public function setting_agent_level()
    {
        $levels = AgentLevel::where('status', '1');
        if(Auth::guard('merchant')->check()){
        $levels = $levels->where('merchant_id', Auth::user()->code);    
        }else{
        $levels = $levels->whereNull('merchant_id');
        }
        $levels = $levels->get();


        if($levels->isEmpty()){
            try{

                \DB::beginTransaction();

                $get_default_levels = AgentLevel::where('status', '1')->where('admin_default', '1')->get();
                foreach($get_default_levels as $get_default_level){
                    $create_level = new AgentLevel();
                    $create_level->merchant_id = Auth::user()->code;
                    $create_level->agent_lvl = $get_default_level->agent_lvl;
                    $create_level->target = $get_default_level->target;
                    $create_level->level_colour = $get_default_level->level_colour;
                    $create_level->save();
                }
                \DB::commit();
            } catch (\Exception $e){
                \DB::rollback();

                Toastr::error($e->getMessage().' - '.$e->getLine());
                return Redirect::back()->withErrors($e->getMessage());
            } catch (\Error $e){
                \DB::rollback();

                Toastr::error($e->getMessage().' - '.$e->getLine());
                return Redirect::back()->withErrors($e->getMessage());
            }
        }

        return view('backend.settings.setting_agent_lvl', compact('levels'));
    }

    public function setting_agent_level_save(Request $request)
    {
        $translation_data = GlobalController::get_translations();
        try{

            \DB::beginTransaction();

            $ids = '';
            for($a=0; $a<count($request->agent_lvl); $a++){
                if(!empty($request->lvl_id[$a])){
                    $agent_lvls = AgentLevel::find($request->lvl_id[$a]);   
                }else{
                    $agent_lvls = new AgentLevel();
                }
                $agent_lvls->agent_lvl = $request->agent_lvl[$a];
                $agent_lvls->agent_lvl_cn = $request->agent_lvl_cn[$a];
                $agent_lvls->target = $request->target[$a];
                
                if(!empty($request->agent_lvl[$a])){
                $agent_lvls->save();
                }

                \DB::commit();
            }
            // exit();
        } catch (\Exception $e){
            \DB::rollback();

            Toastr::error($e->getMessage().' - '.$e->getLine());
            return Redirect::back()->withErrors($e->getMessage());
        } catch (\Error $e){
            \DB::rollback();

            Toastr::error($e->getMessage().' - '.$e->getLine());
            return Redirect::back()->withErrors($e->getMessage());
        }
        
        Toastr::success($translation_data['backendlang']['backendlang']['Agent Level Setting Updated!'] ?? 'Agent Level Setting Updated!');
        return redirect()->route('setting_agent_level');
    }

    public function setting_shipping_fee()
    {
        $settingShippingFees = SettingShippingFee::get();

        
        // $countries = TblCountry::whereIn('country_id', ['243', '104', '49', '119', '200'])
        //                        ->orderBy('country_name', 'asc')
        //                        ->get();

        $countries = GlobalController::global_countries();

        $website_setting = WebsiteSetting::first();

        return view('backend.settings.setting_shipping_fee', ['settingShippingFees'=>$settingShippingFees,
                                                              'countries'=>$countries,
                                                              'website_setting'=>$website_setting]);
    }

    public function save_setting_shipping_fee(Request $request)
    {
        $translation_data = GlobalController::get_translations();
        try{

            \DB::beginTransaction();

            $website_setting = WebsiteSetting::find(1);
            
            $website_setting->type_set_shipping_fee = $request->type_set_shipping_fee ? 2 : 1;
            $website_setting->free_shipping_threshold = $request->free_shipping_threshold;
            
            $result = $website_setting->save();

            $ids = '';
            
            $request->shipping_fee = is_array($request->shipping_fee) ? $request->shipping_fee : [];
            
            for($a=0; $a<count($request->shipping_fee); $a++){
                if(!empty($request->sid[$a])){
                    $shipping_fee = SettingShippingFee::find($request->sid[$a]);
                }else{
                    $shipping_fee = new SettingShippingFee();
                }
                
                $shipping_fee->shipping_fee = $request->shipping_fee[$a];
                $shipping_fee->area = $request->type[$a];
                $shipping_fee->weight = $request->weight[$a];
                $shipping_fee->country_id = $request->country_id[$a];
                
                if(!empty($request->shipping_fee[$a])){
                $shipping_fee->save();   
                }

                \DB::commit();
            }
            // exit();
        } catch (\Exception $e){
            \DB::rollback();

            Toastr::error($e->getMessage());
            return Redirect::back()->withErrors($e->getMessage());
        }

         Toastr::success($translation_data['backendlang']['backendlang']['Shipping Fee Setting Successful'] ?? 'Shipping Fee Setting Successful');
        return redirect()->route('setting_shipping_fee');
    }

    public function setting_uom()
    {
        $select = SettingUom::select('setting_uoms.*');
        if(Auth::guard('merchant')->check()){
        $select = $select->where('merchant_id', Auth::user()->code);
        }
        $select = $select->get();

        return view('backend.settings.setting_uom', ['setting_uoms'=>$select]);
    }


    public function setting_uom_save(Request $request)
    {
        $translation_data = GlobalController::get_translations();
        $caseString = 'case id';
        $ids = '';

        $insert = [];
        for($a=0; $a<count($request->uom_name); $a++){
            if(!empty($request->uid[$a])){
                //Update

                $id = $request->uid[$a];
                $uom_name = $request->uom_name[$a];

                $caseString .= " when $id then '$uom_name'";

                $ids .= "$id,"; 
            }else{
                //Create
                if(!empty($request->uom_name[$a])){

                    if(Auth::guard('merchant')->check()){
                        $insert[] = [
                                        'merchant_id'=>Auth::user()->code,
                                        'uom_name'=>$request->uom_name[$a],
                                        'status'=>'1',
                                        'created_at'=>date('Y-m-d H:i:s'),
                                        'updated_at'=>date('Y-m-d H:i:s'),
                                    ];
                    }else{
                        $insert[] = [
                                        'uom_name'=>$request->uom_name[$a],
                                        'status'=>'1',
                                        'created_at'=>date('Y-m-d H:i:s'),
                                        'updated_at'=>date('Y-m-d H:i:s'),
                                    ];

                    }
                }                
            }
        }
        //Update
        $ids = trim($ids, ',');

        if($ids != ''){
            DB::update("update setting_uoms set uom_name = $caseString end
                                                            where id in ($ids)");
        }

        //Insert
        $create = SettingUom::insert($insert);

        Toastr::success($translation_data['backendlang']['backendlang']['Updated UOM setting Successful'] ?? 'Updated UOM setting Successful');
        return redirect()->route('setting_uom');
    }

    public function setting_banner()
    {
        // $select = SettingUom::get();
        return view('backend.settings.setting_banner');
    }

    public function setting_material()
    {
        
        return view('backend.settings.setting_material');
    }

    public function setting_signature_dish()
    {
        return view('backend.settings.setting_signature_dish');
    }

    public function setting_dual_commission()
    {
        $levels = AgentLevel::where('status', '1')->get();
        $SettingDualMain = SettingDualMain::find(1);

        $SettingDualCommission = [];
        foreach($levels as $level){
            $SettingDualCommission[$level->id] = SettingDualCommission::where('agent_lvl', $level->id)->first();
        }

        return view('backend.settings.setting_dual_commission', ['levels'=>$levels, 'SettingDualMain'=>$SettingDualMain],
                                                                compact('SettingDualCommission'));
    }

    public function save_setting_dual_commission(Request $request)
    {
        $translation_data = GlobalController::get_translations();
        $SettingDualMain = SettingDualMain::find(1);
        if(!empty($SettingDualMain->id)){
            $SettingDualMain = $SettingDualMain->update(['comm_type'=>$request->commission_p_t_type,
                                                         'comm_amount'=>$request->commission_p_t]);
        }else{
            $insertDualMain = SettingDualMain::insert(['comm_type'=>$request->commission_p_t_type,
                                                       'comm_amount'=>$request->commission_p_t,
                                                       'status'=>'1',
                                                       'created_at'=>date('Y-m-d H:i:s'),
                                                       'updated_at'=>date('Y-m-d H:i:s')]);
        }
        // SettingDualCommission
        $caseString = $caseString1 = 'case id';
        $ids = '';
        $insert = [];
        for($a=0; $a<count($request->level_comm_amount); $a++){
            if(!empty($request->did[$a])){

                $id = $request->did[$a];
                $level_comm_type = $request->level_comm_type[$a];
                $level_comm_amount = $request->level_comm_amount[$a];

                $caseString .= " when $id then '$level_comm_type'";
                $caseString1 .= " when $id then '$level_comm_amount'";

                $ids .= "$id,"; 
            }else{
                $insert[] = ['agent_lvl'=>$request->agent_lvl[$a],
                             'comm_type'=>$request->level_comm_type[$a],
                             'comm_amount'=>$request->level_comm_amount[$a],
                             'status'=>'1',
                             'created_at'=>date('Y-m-d H:i:s'),
                             'updated_at'=>date('Y-m-d H:i:s')];
            }
        }
        $ids = trim($ids, ',');

        if($ids != ''){
            DB::update("update setting_dual_commissions set comm_type = $caseString end,
                                                            comm_amount = $caseString1 end
                                                            where id in ($ids)");
        }

        $create = SettingDualCommission::insert($insert);

        Toastr::success($translation_data['backendlang']['backendlang']['Update_Successful'] ?? 'Update_Successful');
        return redirect()->route('setting_dual_commission');
    }

    public function setting_agent_monthly_sales_bonus()
    {
        $monthly_s = SettingMonthlyAgentSalesBonus::where('monthly_type', '1')->get();
        $quaterly_s = SettingMonthlyAgentSalesBonus::where('monthly_type', '2')->get();

        return view('backend.settings.setting_agent_monthly_sales_bonus', ['monthly_s'=>$monthly_s, 'quaterly_s'=>$quaterly_s]);
    }

    public function save_setting_agent_monthly_sales_bonus(Request $request)
    {
        $translation_data = GlobalController::get_translations();
        $insert = [];
        $caseString = $caseString1 =  $caseString2 = 'case id';
        $ids = '';
        for($a=0; $a<count($request->m_target_amount); $a++){
            if(!empty($request->mid[$a])){

                $id = $request->mid[$a];
                $m_target_amount = $request->m_target_amount[$a];
                $m_comm_type = $request->m_comm_type[$a];
                $m_comm_amount = $request->m_comm_amount[$a];

                $caseString .= " when $id then '$m_target_amount'";
                $caseString1 .= " when $id then '$m_comm_type'";
                $caseString2 .= " when $id then '$m_comm_amount'";

                $ids .= "$id,"; 

            }else{
                if(!empty($request->m_target_amount[$a])){
                    $insert[] = ['monthly_type'=>'1',
                                 'target'=>$request->m_target_amount[$a],
                                 'comm_type'=>$request->m_comm_type[$a],
                                 'comm_amount'=>$request->m_comm_amount[$a],
                                 'status'=>'1',
                                 'created_at'=>date('Y-m-d H:i:s'),
                                 'updated_at'=>date('Y-m-d H:i:s')];                    
                }
            }
        }

        $create = SettingMonthlyAgentSalesBonus::insert($insert);

        $ids = trim($ids, ',');

        if($ids != ''){
            DB::update("update setting_monthly_agent_sales_bonuses set target = $caseString end,
                                                                       comm_type = $caseString1 end,
                                                                       comm_amount = $caseString2 end
                                                                   where id in ($ids)");
        }

        $insert_q = [];
        $caseString3 = $caseString4 =  $caseString5 = 'case id';
        $qids = '';
        for($b=0; $b<count($request->q_target_amount); $b++){
            if(!empty($request->qid[$b])){
                $qid = $request->qid[$b];
                $q_target_amount = $request->q_target_amount[$b];
                $q_comm_type = $request->q_comm_type[$b];
                $q_comm_amount = $request->q_comm_amount[$b];

                $caseString3 .= " when $qid then '$q_target_amount'";
                $caseString4 .= " when $qid then '$q_comm_type'";
                $caseString5 .= " when $qid then '$q_comm_amount'";

                $qids .= "$qid,"; 
            }else{
                if(!empty($request->q_target_amount[$b])){
                    $insert_q[] = ['monthly_type'=>'2',
                                   'target'=>$request->q_target_amount[$b],
                                   'comm_type'=>$request->q_comm_type[$b],
                                   'comm_amount'=>$request->q_comm_amount[$b],
                                   'status'=>'1',
                                   'created_at'=>date('Y-m-d H:i:s'),
                                   'updated_at'=>date('Y-m-d H:i:s')];                    
                }
            }
        }

        $create = SettingMonthlyAgentSalesBonus::insert($insert_q);

        $qids = trim($qids, ',');

        if($qids != ''){
            DB::update("update setting_monthly_agent_sales_bonuses set target = $caseString3 end,
                                                                       comm_type = $caseString4 end,
                                                                       comm_amount = $caseString5 end
                                                                   where id in ($qids)");
        }

        Toastr::success($translation_data['backendlang']['backendlang']['Update_Successful'] ?? 'Update_Successful');

        return redirect()->route('setting_agent_monthly_sales_bonus');
    }

    public function setting_downline_bonus()
    {
        $levels = AgentLevel::get();
        $settings = SettingDownlineBonus::get();

        return view('backend.settings.setting_downline_bonus', ['levels'=>$levels, 'settings'=>$settings]);
    }

    public function save_setting_downline_bonus(Request $request)
    {
        $translation_data = GlobalController::get_translations();
        $caseString3 = $caseString4 =  $caseString5 = 'case id';
        $ids = '';
        for($a=0; $a<count($request->level_id); $a++){
            $l = $request->level_id[$a];

            for($b=0; $b<count($request['target'.$l]); $b++){
                if(!empty($request['lid'.$l][$b])){
                    $id = $request['lid'.$l][$b];
                    $q_target_amount = $request['target'.$l][$b];
                    $q_comm_type = $request['comm_type'.$l][$b];
                    $q_comm_amount = $request['comm_amount'.$l][$b];

                    $caseString3 .= " when $id then '$q_target_amount'";
                    $caseString4 .= " when $id then '$q_comm_type'";
                    $caseString5 .= " when $id then '$q_comm_amount'";

                    $ids .= "$id,";
                }else{
                    if(!empty($request['comm_amount'.$l][$b])){
                        SettingDownlineBonus::insert(
                                                      ['level_id'=>$l,
                                                       'target'=>$request['target'.$l][$b],
                                                       'comm_type'=>$request['comm_type'.$l][$b],
                                                       'comm_amount'=>$request['comm_amount'.$l][$b]
                                                      ]
                                                    );
                    }
                }
            }
        }

        $ids = trim($ids, ',');

        if($ids != ''){
            DB::update("update setting_downline_bonuses set target = $caseString3 end,
                                                            comm_type = $caseString4 end,
                                                            comm_amount = $caseString5 end
                                                        where id in ($ids)");
        }

        Toastr::success($translation_data['backendlang']['backendlang']['Update_Successful'] ?? 'Update_Successful');
        return redirect()->route('setting_downline_bonus');
    }

    public function setting_pick_up_address()
    {
        $states = State::get();
        $select = SettingPickUpAddress::first();

        return view('backend.settings.setting_pick_up_address', ['states'=>$states, 'select'=>$select]);
    }

    public function save_setting_pick_up_address(Request $request)
    {
        $translation_data = GlobalController::get_translations();
        $input = $request->all();

        $select = SettingPickUpAddress::get();

        if(!$select->isEmpty()){
            $shipping = SettingPickUpAddress::find(1);
            $shipping = $shipping->update($input);
        }else{
            $shipping = SettingPickUpAddress::create($input);
        }

         Toastr::success($translation_data['backendlang']['backendlang']['The courier pickup address is saved successfully'] ?? 'The courier pickup address is saved successfully');
        return redirect()->route('setting_pick_up_address');
    }

    public function setting_topup_amount()
    {
        $selects = SettingTopup::get();
        return view('backend.settings.setting_topup_amount', ['selects'=>$selects]);
    }

    public function save_setting_topup_amount(Request $request)
    {
        $translation_data = GlobalController::get_translations();
        $caseString3 = $caseString4 = $caseString5 = 'case id';
        $ids = '';
        for($a=0; $a<count($request['topup_amount']); $a++){
            if(!empty($request['tid'][$a])){
                $id = $request['tid'][$a];
                $topup_amount = $request['topup_amount'][$a];
                $profit_type = $request['profit_type'][$a];
                $profit_amount = $request['profit_amount'][$a];

                $caseString3 .= " when $id then '$topup_amount'";
                $caseString4 .= " when $id then '$profit_type'";
                $caseString5 .= " when $id then '$profit_amount'";

                $ids .= "$id,";
            }else{
                if(!empty($request['topup_amount'][$a])){
                    SettingTopup::insert(
                                          [
                                            'topup_amount'=>$request->topup_amount[$a],
                                            'profit_type'=>$request->profit_type[$a],
                                            'profit_amount'=>$request->profit_amount[$a]
                                          ]
                                        );
                }
            }
        }

        $ids = trim($ids, ',');

        if($ids != ''){
            DB::update("update setting_topups set topup_amount = $caseString3 end,
                                                  profit_type = $caseString4 end,
                                                  profit_amount = $caseString5 end
                                              where id in ($ids)");
        }

        Toastr::success($translation_data['backendlang']['backendlang']['Topup Amount Saved!'] ?? "Topup Amount Saved!");
        return redirect()->route('setting_topup_amount');
    }

    public function setting_main_page()
    {
        return view('backend.settings.setting_main_page');
    }

    public function website_setting()
    {
        if(Auth::guard('merchant')->check()){
        $setting = Merchant::find(Auth::guard('merchant')->user()->id);
        }else{
        $setting = WebsiteSetting::find(1);
        }

        $agent_levels = AgentLevel::get();

        $SettingAgentReferralPeriod = SettingAgentReferralPeriod::get();

        $value = [];
        foreach($SettingAgentReferralPeriod as $smc){
            $value[$smc->agent_lvl] = array($smc->month_period, $smc->id);
        }

        return view('backend.settings.website_setting', ['setting'=>$setting, 'agent_levels'=>$agent_levels], compact('value'));
    }

    public function save_website_setting(Request $request)
    {
        $translation_data = GlobalController::get_translations();
        try{

            \DB::beginTransaction();

            if(Auth::guard('merchant')->check()){
                if(Auth::user()->permission_lvl == 1){
                    $update_autorise = WebsiteSetting::find(1);
                    $update_autorise->authorise_enable = isset($request->authorise_enable) ? 1 : 0;
                    $update_autorise->save();
                }

                $website_setting = Merchant::find(Auth::guard('merchant')->user()->id);
            }else{
                $website_setting = WebsiteSetting::find(1);
                if(Auth::user()->permission_lvl == 1){
                $website_setting->authorise_enable = isset($request->authorise_enable) ? 1 : 0;
                }
            }

            // dd($website_setting->id);
            $website_setting->registration_product_enable = isset($request->registration_product_enable) ? 1 : 0;
            $website_setting->registration_package_hierarchy_bonus = isset($request->registration_package_hierarchy_bonus) ? 1 : 0;
            $website_setting->setting_sold_display_product = isset($request->setting_sold_display_product) ? 1 : 0;
            $website_setting->bonus_agent_enable = isset($request->bonus_agent_enable) ? 1 : 0;
            $website_setting->bonus_member_enable = isset($request->bonus_member_enable) ? 1 : 0;
            
            $website_setting->agent_rebate_enable = isset($request->agent_rebate_enable) ? 1 : 0;
            $website_setting->hierarchy_enable = isset($request->hierarchy_enable) ? 1 : 0;
            $website_setting->referral_enable = isset($request->referral_enable) ? 1 : 0;

            $website_setting->member_rebate_enable = isset($request->member_rebate_enable) ? 1 : 0;
            $website_setting->member_hierarchy_enable = isset($request->member_hierarchy_enable) ? 1 : 0;
            $website_setting->member_referral_enable = isset($request->member_referral_enable) ? 1 : 0;

            $website_setting->topup_bonus_pv_enable = isset($request->topup_bonus_pv_enable) ? 1 : 0;
            $website_setting->topup_rm_to_pv = $request->topup_rm_to_pv;
            $website_setting->save();
            

            \DB::commit();

        } catch (\Exception $e){
            \DB::rollback();
            Toastr::error($e->getMessage());
            return Redirect::back()->withInput($request->all())->withErrors($e->getMessage());
        } catch (\Error $e){
            \DB::rollback();
            Toastr::error($e->getMessage());
            return Redirect::back()->withInput($request->all())->withErrors($e->getMessage());
        }

        Toastr::success($translation_data['backendlang']['backendlang']['Setting Saved'] ?? 'Setting Saved');
        return redirect()->route('website_setting');
    }

    public function setting_cod_address()
    {
        $settings = CodAddress::get();
        return view('backend.settings.setting_cod_address', ['settings'=>$settings]);
    }

    public function save_setting_cod_address(Request $request)
    {
        $translation_data = GlobalController::get_translations();
        $insert = [];
        $caseString = $caseString1 = $caseString2 = 'case id';
        $ids = '';

        for($a=0; $a<count($request->address); $a++){
            if(!empty($request->sid[$a])){
                $sid = $request->sid[$a];

                $address = $request->address[$a];
                $address_desc = $request->address_desc[$a];
                $cod_code = $request->cod_code[$a];

                $caseString .= " when $sid then '$address'";
                $caseString1 .= " when $sid then '$address_desc'";
                $caseString2 .= " when $sid then '$cod_code'";

                $ids .= "$sid,";
            }else{
                if(!empty($request->address[$a])){
                    $insert[] = [
                                    "cod_code"=>$request->cod_code[$a],
                                    "address"=>$request->address[$a],
                                    "address_desc"=>$request->address_desc[$a],
                                ];
                }
            }
            
        }
        $ids = trim($ids, ',');

        $create = CodAddress::insert($insert);
        
        if($ids != ''){
            DB::update("update cod_addresses set address = $caseString end,
                                                 address_desc = $caseString1 end,
                                                 cod_code = $caseString2 end
                                                 where id in ($ids)");
        }

        Toastr::success($translation_data['backendlang']['backendlang']['COD Address Setting Successful'] ?? 'COD Address Setting Successful');
        return redirect()->route('setting_cod_address');
    }

    public function setting_pv()
    {
        $pv_setting = SettingPv::find(1);

        return view('backend.settings.setting_pv', ['pv_setting'=>$pv_setting]);
    }

    public function save_setting_pv(Request $request)
    {
        $translation_data = GlobalController::get_translations();
        $pv_setting = SettingPv::find(1);

        $input = [];
        $input['get_pv_rate'] = number_format($request->get_pv, 2);
        $input['spend_pv_rate'] = number_format($request->spend_pv, 2);
        if(!empty($pv_setting->id)){
            $pv_setting = $pv_setting->update($input);
        }else{
            $create_pv_setting = SettingPv::create($input);
        }

        Toastr::success($translation_data['backendlang']['backendlang']['Setting PV Success'] ?? 'Setting PV Success');
        return redirect()->route('setting_pv');
    }

    public function setting_comm_ranking()
    {
        $comm_rankings = SettingCommRanking::get();

        return view('backend.settings.setting_comm_ranking', ['comm_rankings'=>$comm_rankings]);
    }

    public function save_setting_comm_ranking(Request $request)
    {
        $translation_data = GlobalController::get_translations();
        $insert = [];
        $caseString = $caseString1 = $caseString2 = 'case id';
        $ids = '';
        $highest_comm_limit = 0;

        for($a=0; $a<count($request->rank_title); $a++){
            if(!empty($request->rank_title[$a])){
                if($request->rank_sales_limit[$a] <= $highest_comm_limit){
                    Toastr::error($translation_data['backendlang']['backendlang']['Unable to set requirements limit lower than previous ranking'] ?? 'Unable to set requirements limit lower than previous ranking');
                    return redirect()->route('setting_comm_ranking');
                }else{
                    $highest_comm_limit = $request->rank_sales_limit[$a];
                }

                if(!empty($request->rank_id[$a])){
                    $rank_id = $request->rank_id[$a];

                    $title = $request->rank_title[$a];
                    $rank_sales_limit = $request->rank_sales_limit[$a];
                    $rank_comm_perc = $request->rank_comm_perc[$a];

                    $caseString .= " when $rank_id then '$title'";
                    $caseString1 .= " when $rank_id then '$rank_sales_limit'";
                    $caseString2 .= " when $rank_id then '$rank_comm_perc'";

                    $ids .= "$rank_id,";
                }else{
                    if(!empty($request->rank_title[$a])){
                        $insert[] = [
                                        "title"=>$request->rank_title[$a],
                                        "comm_requirement_limit"=>$request->rank_sales_limit[$a],
                                        "comm_perc"=>$request->rank_comm_perc[$a],
                                    ];
                    }
                }
            }
            
        }
        $ids = trim($ids, ',');

        $create = SettingCommRanking::insert($insert);
        
        if($ids != ''){
            DB::update("update setting_comm_rankings set title = $caseString end,
                                                         comm_requirement_limit = $caseString1 end,
                                                         comm_perc = $caseString2 end
                                                         where id in ($ids)");
        }

        Toastr::success($translation_data['backendlang']['backendlang']['Commission Setting Successful'] ?? 'Commission Setting Successful');  
        return redirect()->route('setting_comm_ranking');
    }

    public function setting_customer_level()
    {
        $levels = CustomerLevel::where('status', '1')->get();

        return view('backend.settings.setting_customer_level', ['levels'=>$levels]);
    }

    public function save_setting_customer_level(Request $request)
    {
        $translation_data = GlobalController::get_translations();
        $caseString = $caseString1 = 'case id';
        $ids = '';

        $insert = [];
        for($a=0; $a<count($request->customer_lvl); $a++){
            if(!empty($request->lvl_id[$a])){
                //Update

                $id = $request->lvl_id[$a];
                $customer_lvl = $request->customer_lvl[$a];
                $customer_lvl_code = $request->customer_lvl_code[$a];

                $caseString .= " when $id then '$customer_lvl'";
                $caseString1 .= " when $id then '$customer_lvl_code'";

                $ids .= "$id,";
            }else{
                //Create
                if(!empty($request->customer_lvl[$a])){
                    $insert[] = [
                                    'customer_lvl'=>$request->customer_lvl[$a],
                                    'customer_lvl_code'=>$request->customer_lvl_code[$a]
                                ];
                }                
            }
        }
        //Update
        $ids = trim($ids, ',');

        if($ids != ''){
            DB::update("update customer_levels set customer_lvl = $caseString end,
                                                customer_lvl_code = $caseString1 end
                                                where id in ($ids)");
        }

        //Insert
        $create = CustomerLevel::insert($insert);


        Toastr::success($translation_data['backendlang']['backendlang']['Customer Level Setting Updated'] ?? 'Customer Level Setting Updated!');
        return redirect()->route('setting_customer_level');
    }

    public function setting_joining_fee()
    {
        $setting_joining_fees = SettingJoiningFee::get();

        return view('backend.settings.setting_joining_fee', ['setting_joining_fees'=>$setting_joining_fees]);
    }

    public function save_setting_joining_fee(Request $request)
    {
        $translation_data = GlobalController::get_translations();
        $caseString = $caseString1 = $caseString2 = $caseString3 = $caseString4 = $caseString5 = 'case id';
        $ids = '';

        $insert = [];
        for($a=0; $a<count($request->target); $a++){
            if(!empty($request->lvl_id[$a])){
                //Update

                $id = $request->lvl_id[$a];
                $target = $request->target[$a];
                $comm_amount = $request->comm_amount[$a];

                $caseString .= " when $id then '$target'";
                $caseString1 .= " when $id then '$comm_amount'";

                $ids .= "$id,";
            }else{
                //Create
                if(!empty($request->target[$a])){
                    $insert[] = [
                                    'target'=>$request->target[$a],
                                    'comm_type'=>'Percentage',
                                    'comm_amount'=>$request->comm_amount[$a]
                                ];
                }                
            }
        }
        //Update
        $ids = trim($ids, ',');

        if($ids != ''){
            DB::update("update setting_joining_fees set target = $caseString end,
                                                              comm_amount = $caseString1 end
                                                          where id in ($ids)");
        }

        //Insert
        $create = SettingJoiningFee::insert($insert);

        Toastr::success($translation_data['backendlang']['backendlang']['Setting Updated!'] ?? 'Setting Updated!');
        return redirect()->route('setting_joining_fee');
    }

    public function setting_partner_level()
    {
        $levels = PartnerLevel::where('status', '1')->get();
        
        return view('backend.settings.setting_partner_lvl', ['levels'=>$levels]);
    }

    public function setting_partner_level_save(Request $request)
    {
        $translation_data = GlobalController::get_translations();
        $caseString = $caseString1 = 'case id';
        $ids = '';

        $insert = [];
        for($a=0; $a<count($request->partner_lvl); $a++){
            if(!empty($request->lvl_id[$a])){
                //Update

                $id = $request->lvl_id[$a];
                $partner_lvl = $request->partner_lvl[$a];
                $partner_lvl_cn = $request->partner_lvl_cn[$a];

                $caseString .= " when $id then '$partner_lvl'";
                $caseString1 .= " when $id then '$partner_lvl_cn'";

                $ids .= "$id,";
            }else{
                //Create
                if(!empty($request->partner_lvl[$a])){
                    $insert[] = [
                                    'partner_lvl'=>$request->partner_lvl[$a],
                                    'partner_lvl_cn'=>$request->partner_lvl_cn[$a]
                                ];
                }                
            }
        }
        //Update
        $ids = trim($ids, ',');

        if($ids != ''){
            DB::update("update agent_levels set partner_lvl = $caseString end,
                                                partner_lvl_cn = $caseString1 end
                                                where id in ($ids)");
        }

        //Insert
        $create = PartnerLevel::insert($insert);


        Toastr::success($translation_data['backendlang']['backendlang']['Partner Level Setting Updated!'] ?? 'Partner Level Setting Updated!');
        return redirect()->route('setting_partner_level');
    }

    public function setting_partner_commission()
    {
        $partner_lvl = PartnerLevel::where('status', '1')
                               ->get();

        return view('backend.settings.setting_partner_commission', ['partner_lvl'=>$partner_lvl]);
    }

    public function save_setting_partner_commission(Request $request)
    {
        $translation_data = GlobalController::get_translations();
        $caseString = $caseString1 = $caseString2 = $caseString3 = $caseString4 = "case id";
        $ids = '';

        $insert = [];
        for($a=0; $a<count($request->lvl_id); $a++){
                //Update
                $id = $request->lvl_id[$a];
                $requirement = $request->requirement[$a];
                $direct_requirement = (!empty($request->requirement_type[$a]) && $request->requirement_type[$a] == 'direct') ? 1 : 0;
                $team_requirement = (!empty($request->requirement_type[$a]) && $request->requirement_type[$a] == 'team') ? 1 : 0;
                $allowance = $request->allowance[$a];
                $promotion_requirement = $request->promotion_requirement[$a];

                $caseString .= " when $id then '$requirement'";
                $caseString1 .= " when $id then '$direct_requirement'";
                $caseString2 .= " when $id then '$team_requirement'";
                $caseString3 .= " when $id then '$allowance'";
                $caseString4 .= " when $id then '$promotion_requirement'";

                $ids .= "$id,";
        }

        $ids = trim($ids, ',');

        if($ids != ''){
            DB::update("update partner_levels set requirement = $caseString end,
                                                                direct_requirement = $caseString1 end,
                                                                team_requirement = $caseString2 end,
                                                                allowance = $caseString3 end,
                                                                promotion_requirement = $caseString4 end
                                                                where id in ($ids)");
        }


        Toastr::success($translation_data['backendlang']['backendlang']['Setting Partner Commission Successful'] ?? 'Setting Partner Commission Successful');
        return redirect()->route('setting_partner_commission');
    }

    public function setting_area_agent_level()
    {
        $levels = AreaAgentLevel::where('status', '1')->get();

        return view('backend.settings.setting_area_agent_lvl', ['levels'=>$levels]);
    }

    public function setting_area_agent_level_save(Request $request)
    {
        $translation_data = GlobalController::get_translations();   
        $caseString = $caseString1 = 'case id';
        $ids = '';

        $insert = [];
        for($a=0; $a<count($request->area_agent_lvl); $a++){
            if(!empty($request->lvl_id[$a])){
                //Update

                $id = $request->lvl_id[$a];
                $area_agent_lvl = $request->area_agent_lvl[$a];
                $area_agent_lvl_cn = $request->area_agent_lvl_cn[$a];

                $caseString .= " when $id then '$area_agent_lvl'";
                $caseString1 .= " when $id then '$area_agent_lvl_cn'";

                $ids .= "$id,";
            }else{
                //Create
                if(!empty($request->area_agent_lvl[$a])){
                    $insert[] = [
                                    'area_agent_lvl'=>$request->area_agent_lvl[$a],
                                    'area_agent_lvl_cn'=>$request->area_agent_lvl_cn[$a]
                                ];
                }                
            }
        }
        //Update
        $ids = trim($ids, ',');

        if($ids != ''){
            DB::update("update area_agent_levels set area_agent_lvl = $caseString end,
                                                area_agent_lvl_cn = $caseString1 end
                                                where id in ($ids)");
        }

        //Insert
        $create = AreaAgentLevel::insert($insert);


        Toastr::success($translation_data['backendlang']['backendlang']['Area Agent Level Setting Updated!'] ?? 'Area Agent Level Setting Updated!');
        return redirect()->route('setting_area_agent_level');
    }

    public function setting_area_agent_subsidy()
    {
        $agent_lvls = AreaAgentLevel::where('status', '1')
                                    ->get();

        return view('backend.settings.setting_area_agent_subsidy', ['agent_lvls'=>$agent_lvls]);
    }

    public function save_setting_area_agent_subsidy(Request $request)
    {
        $translation_data = GlobalController::get_translations();
        $caseString = 'case id';
        $ids = '';

        $insert = [];
        for($a=0; $a<count($request->lvl_id); $a++){
                //Update

                $id = $request->lvl_id[$a];
                $subsidy = $request->subsidy[$a];

                $caseString .= " when $id then '$subsidy'";

                $ids .= "$id,";
        }
        //Update
        $ids = trim($ids, ',');

        if($ids != ''){
            DB::update("update area_agent_levels set subsidy = $caseString end
                                                where id in ($ids)");
        }


        Toastr::success($translation_data['backendlang']['backendlang']['Area Agent Level Setting Updated!'] ?? 'Area Agent Level Setting Updated!');
        return redirect()->route('setting_area_agent_subsidy');
    }

    public function setting_testimonial()
    {
        return view('backend.settings.setting_testimonial');
    }

    public function setting_sales_pop_up()
    {
        $sales = SalesPopup::get();
        $products = Product::where('status', '1')->get();
        return view('backend.settings.setting_sales_pop_up', ['sales'=>$sales, 'products'=>$products]);
    }

    public function save_setting_sales_pop_up(Request $request)
    {
        $translation_data = GlobalController::get_translations();
        $caseString = $caseString1 = $caseString2 = $caseString3 = $caseString4 = $caseString5 = 'case id';
        $ids = '';

        $insert = [];
        for($a=0; $a<count($request->name); $a++){
            if(!empty($request->sales_id[$a])){
                //Update

                $id = $request->sales_id[$a];
                $name = $request->name[$a];
                $product_id = $request->product_id[$a];
                $sales_date = $request->sales_date[$a];

                $caseString .= " when $id then '$name'";
                $caseString1 .= " when $id then '$product_id'";
                $caseString4 .= " when $id then '$sales_date'";

                $ids .= "$id,";
            }else{
                //Create
                if(!empty($request->name[$a])){
                    $insert[] = [
                                    'name'=>$request->name[$a],
                                    'product_id'=>$request->product_id[$a],
                                    'sales_date'=>$request->sales_date[$a]
                                ];
                }                
            }
        }
        //Update
        $ids = trim($ids, ',');

        if($ids != ''){
            DB::update("update sales_popups set name = $caseString end,
                                                product_id = $caseString1 end,
                                                sales_date = $caseString4 end
                                                where id in ($ids)");
        }

        //Insert
        $create = SalesPopup::insert($insert);


        Toastr::success($translation_data['backendlang']['backendlang']['Sales Popup Setting Updated!'] ?? 'Sales Popup Setting Updated!');
        return redirect()->route('setting_sales_pop_up');
    }

    public function setting_retail_commissions()
    {
        $SettingRetailCommissions = SettingRetailCommission::get();
        $setting = WebsiteSetting::find(1);

        return view('backend.settings.setting_retail_commissions', ['SettingRetailCommissions'=>$SettingRetailCommissions,
                                                                    'setting'=>$setting]);
    }

    public function save_setting_retail_commissions(Request $request)
    {
        $translation_data = GlobalController::get_translations();   
        $caseString = $caseString1 = $caseString2 = $caseString3 = $caseString4 = $caseString5 = 'case id';
        $ids = '';

        $insert = [];
        for($a=0; $a<count($request->target); $a++){
            if(!empty($request->lvl_id[$a])){
                //Update

                $id = $request->lvl_id[$a];
                $target = $request->target[$a];
                $comm_amount = $request->comm_amount[$a];

                $caseString .= " when $id then '$target'";
                $caseString1 .= " when $id then '$comm_amount'";

                $ids .= "$id,";
            }else{
                //Create
                if(!empty($request->target[$a])){
                    $insert[] = [
                                    'target'=>$request->target[$a],
                                    'comm_type'=>'Percentage',
                                    'comm_amount'=>$request->comm_amount[$a]
                                ];
                }                
            }
        }
        //Update
        $ids = trim($ids, ',');

        if($ids != ''){
            DB::update("update setting_retail_commissions set target = $caseString end,
                                                              comm_amount = $caseString1 end
                                                          where id in ($ids)");
        }

        //Insert
        $create = SettingRetailCommission::insert($insert);

        WebsiteSetting::find(1)->update($request->all());


        Toastr::success($translation_data['backendlang']['backendlang']['Retail Bonus Setting Updated!'] ?? 'Retail Bonus Setting Updated!');
        return redirect()->route('setting_retail_commissions');
    }

    public function setting_loo_bonus()
    {
        $SettingLooBonus = SettingLooBonus::get();
        $setting = WebsiteSetting::find(1);

        return view('backend.settings.setting_loo_bonus', ['SettingLooBonus'=>$SettingLooBonus,
                                                                    'setting'=>$setting]);
    }

    public function save_setting_loo_bonus(Request $request)
    {
        $translation_data = GlobalController::get_translations();
        $caseString = $caseString1 = $caseString2 = $caseString3 = $caseString4 = $caseString5 = 'case id';
        $ids = '';

        $insert = [];
        for($a=0; $a<count($request->target); $a++){
            if(!empty($request->lvl_id[$a])){
                //Update

                $id = $request->lvl_id[$a];
                $target = $request->target[$a];
                $comm_amount = $request->comm_amount[$a];

                $caseString .= " when $id then '$target'";
                $caseString1 .= " when $id then '$comm_amount'";

                $ids .= "$id,";
            }else{
                //Create
                if(!empty($request->target[$a])){
                    $insert[] = [
                                    'target'=>$request->target[$a],
                                    'comm_type'=>'Percentage',
                                    'comm_amount'=>$request->comm_amount[$a]
                                ];
                }                
            }
        }
        //Update
        $ids = trim($ids, ',');

        if($ids != ''){
            DB::update("update setting_loo_bonuses set target = $caseString end,
                                                              comm_amount = $caseString1 end
                                                          where id in ($ids)");
        }

        //Insert
        $create = SettingLooBonus::insert($insert);

        WebsiteSetting::find(1)->update($request->all());


        Toastr::success($translation_data['backendlang']['backendlang']['LOO Bonus Setting Updated!'] ?? 'LOO Bonus Setting Updated!');
        return redirect()->route('setting_loo_bonus');
    }

    public function setting_lol_bonus()
    {
        $SettingLolBonus = SettingLolBonus::get();
        $setting = WebsiteSetting::find(1);

        return view('backend.settings.setting_lol_bonus', ['SettingLolBonus'=>$SettingLolBonus,
                                                                    'setting'=>$setting]);
    }

    public function save_setting_lol_bonus(Request $request)
    {
        $translation_data = GlobalController::get_translations();
        $caseString = $caseString1 = $caseString2 = $caseString3 = $caseString4 = $caseString5 = 'case id';
        $ids = '';

        $insert = [];
        for($a=0; $a<count($request->target); $a++){
            if(!empty($request->lvl_id[$a])){
                //Update

                $id = $request->lvl_id[$a];
                $target = $request->target[$a];
                $comm_amount = $request->comm_amount[$a];

                $caseString .= " when $id then '$target'";
                $caseString1 .= " when $id then '$comm_amount'";

                $ids .= "$id,";
            }else{
                //Create
                if(!empty($request->target[$a])){
                    $insert[] = [
                                    'target'=>$request->target[$a],
                                    'comm_type'=>'Percentage',
                                    'comm_amount'=>$request->comm_amount[$a]
                                ];
                }                
            }
        }
        //Update
        $ids = trim($ids, ',');

        if($ids != ''){
            DB::update("update setting_lol_bonuses set target = $caseString end,
                                                              comm_amount = $caseString1 end
                                                          where id in ($ids)");
        }

        //Insert
        $create = SettingLolBonus::insert($insert);

        WebsiteSetting::find(1)->update($request->all());


        Toastr::success($translation_data['backendlang']['backendlang']['LOO Bonus Setting Updated!'] ?? 'LOO Bonus Setting Updated!');
        
        return redirect()->route('setting_lol_bonus');
    }

    public function setting_birthday_popup()
    {
        $setting = WebsiteSetting::find(1); 

        return view('backend.settings.setting_birthday_popup', ['setting'=>$setting]);
    }

    public function save_setting_birthday_popup(Request $request)
    {
        $translation_data = GlobalController::get_translations();
        try {
            \DB::beginTransaction();

            $settings = WebsiteSetting::find(1);

            $settings->birthday_popup = $request->birthday_popup;

            $settings = $settings->save();

            \DB::commit();

            Toastr::success($translation_data['backendlang']['backendlang']['Birthday Popup Setting Updated!'] ?? 'Birthday Popup Setting Updated!');
            return redirect()->route('setting_birthday_popup');
        } catch (\Exception $e) {
            \DB::rollback();
            Toastr::error($e->getMessage());
            return redirect()->route('setting_birthday_popup');
        }
    }

    public function setting_merchant_same_level()
    {
        $setting_merchant_commissions = SettingMerchantSameLevel::get();
        $levels = AgentLevel::get();
        $value = [];
        foreach($setting_merchant_commissions as $smc){
            $value[$smc->level][$smc->agent_lvl] = array($smc->comm_type, $smc->comm_amount, $smc->id);
        }

        return view('backend.settings.setting_merchant_same_levels', ['levels'=>$levels], compact('value'));
    }

    public function save_setting_merchant_same_level(Request $request)
    {
        $translation_data = GlobalController::get_translations();
        $caseString = $caseString1 = "case id";
        $ids = '';

        $insert = [];
        for($a=0; $a<count($request->comm_amount); $a++){
            if(!empty($request->ids[$a])){
                //Update
                $id = $request->ids[$a];
                $comm_type = $request->comm_type[$a];
                $comm_amount = $request->comm_amount[$a];

                $caseString .= " when $id then '$comm_type'";
                $caseString1 .= " when $id then '$comm_amount'";

                $ids .= "$id,";
            }else{
                //Create

                if(!empty($request->comm_amount[$a])){
                    $insert[] = [
                                    'agent_lvl'=>$request->agent_lvl[$a],
                                    'level'=>$request->level[$a],
                                    'comm_type'=>$request->comm_type[$a],
                                    'comm_amount'=>$request->comm_amount[$a]
                                ];
                }
            }
        }

        $ids = trim($ids, ',');

        if($ids != ''){
            DB::update("update setting_merchant_same_levels set comm_type = $caseString end,
                                                                comm_amount = $caseString1 end
                                                                where id in ($ids)");
        }


        $create = SettingMerchantSameLevel::insert($insert);

        Toastr::success($translation_data['backendlang']['backendlang']['Setting Same Tier Bonus Successful'] ?? 'Setting Same Tier Bonus Successful');
        return redirect()->route('setting_merchant_same_level');
    }

    public function setting_prize_pool()
    {
        $web_setting = WebsiteSetting::find(1);

        $prize_pool_setting = [];
        for ($i=1; $i <= 10; $i++) { 
            $prize_pool_setting[$i] = SettingPrizePool::where('position', $i)
                                                      ->first();
        }

        $prize_pool_condition = SettingPrizePoolCondition::find(1);

        $get_total_sales = GlobalController::get_total_sales(date('Y'));

        return view('backend.settings.setting_prize_pool', ['web_setting'=>$web_setting,
                                                            'get_total_sales'=>$get_total_sales], 
                                                            compact('prize_pool_setting',
                                                                    'prize_pool_condition'));
    }

    public function save_setting_prize_pool(Request $request)
    {
        $translation_data = GlobalController::get_translations();
        try {
            \DB::beginTransaction();

            $checkIfOver100 = 0;
            if($request->condition_type == 'Percentage'){
                for($i = 1; $i <= 10; $i++){
                    if(!empty($request['amount_'.$i])){
                        $checkIfOver100 += $request['amount_'.$i];
                    }
                }
            }

            if($checkIfOver100 > 100){
                throw new \Exception($translation_data['backendlang']['backendlang']['Amount Exceeded 100 Percent of Total Prize Pool'] ?? 'Amount Exceeded 100 Percent of Total Prize Pool');
                return redirect()->route('setting_prize_pool');
            }

            $condition = SettingPrizePoolCondition::first();
            if(empty($condition->id)){
                $condition = new SettingPrizePoolCondition();
            }
            $condition->type = $request->condition_type;
            $condition->target = $request->target;
            $condition->split_sales_percentage = $request->split_sales_percentage;

            $condition->save();
            

            for($i = 1; $i <= 10; $i++){
                $existing_position = SettingPrizePool::where('position', $i)
                                                     ->first();

                if(empty($existing_position->id)){
                    $existing_position = new SettingPrizePool();
                    $existing_position->position = $i;
                }

                $existing_position->type = $request->condition_type;
                $existing_position->amount = $request['amount_'.$i];

                $existing_position->save();
            }

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            Toastr::error($e->getMessage());
            return redirect()->route('setting_prize_pool');
        }

        Toastr::success($translation_data['backendlang']['backendlang']['Setting Prize Pool Successful'] ?? 'Setting Prize Pool Successful');
        return redirect()->route('setting_prize_pool');
    }
    
    public function run_cron_job(Request $request)
    {
        $translation_data = GlobalController::get_translations();
        $lastYear = date("Y-m", strtotime("-1 year"));
        $lastMonth = date("Y-m", strtotime("previous month"));
        $merchants = Merchant::where('status', '1')->get();
        $users = User::where('status', '1')->get();
        $lastMonth = date("2022-09");
        $entitled_merchant_total_sales = 0;

        foreach($merchants as $merchant){
            $get_own_total_pv = $this->TotalEarnPV($merchant->code);
            $MonthlyTotalPVWallet = $this->MonthlyTotalPVWallet($merchant->code, $lastMonth);
            $MonthlyOwnPVWallet = $this->MonthlyOwnPVWallet($merchant->code, $lastMonth);
            // echo $get_own_total_pv.' - '.$merchant->code.' - '.$MonthlyTotalPVWallet.' | ';
            $SettingTeamDividend = SettingTeamDividend::where('target_box', '<=', $MonthlyTotalPVWallet)
                                                      ->orderBy('target_box', 'desc')
                                                      ->first();


            $get_own_current_tier = SettingTeamDividend::where('target_box', '<=', $get_own_total_pv)
                                                      ->orderBy('target_box', 'desc')
                                                      ->first();

            $highest_team_bonus = SettingTeamDividend::orderBy('target_box', 'desc')
                                                             ->first();

            if(!empty($get_own_current_tier->amount)){

                
                if(date('Y-m-d') == date('Y-m-26')){
                    $get_downlines = Merchant::where('master_id', $merchant->code)
                                             ->get();

                    // $get_downlines = Affiliate::select('affiliates.*', 'm.lvl', 'm.code')
                    //                           ->join('merchants as m', 'm.code', 'affiliates.affiliate_id')
                    //                           ->where('m.status', '1')
                    //                           ->where('user_id', $merchant->code)
                    //                           ->get();
                    $own_total_team_bonus = ($MonthlyOwnPVWallet) * $get_own_current_tier->amount / 100;

                    if($own_total_team_bonus > 0){
                        $input_team = [];
                        $input_team['type'] = '4';
                        $input_team['user_id'] = $merchant->code;
                        $input_team['user_tier'] = $get_own_current_tier->amount;
                        $input_team['user_by'] = "";
                        $input_team['user_by_tier'] = 0;
                        $input_team['product_amount'] = ($MonthlyOwnPVWallet);
                        $input_team['comm_pa_type'] = "Percentage";
                        $input_team['comm_pa'] = $get_own_current_tier->amount;
                        $input_team['comm_amount'] = $own_total_team_bonus;
                        $input_team['comm_desc'] = "Team Reward";
                        $input_team['status'] = "1";

                        AffiliateCommission::create($input_team);
                    }

                    foreach($get_downlines as $get_downline){
                        // echo $get_downline->code.' - '.$merchant->code." | ";
                        $get_downline_total_pv = $this->TotalEarnPV($get_downline->code);

                        $get_downline_monthly_pv[$get_downline->code] = $this->MonthlyTotalPVWallet($get_downline->code, $lastMonth);


                        $get_downline_current_tier = SettingTeamDividend::where('target_box', '<=', $get_downline_total_pv)
                                                          ->orderBy('target_box', 'desc')
                                                          ->first();

                        if(!empty($get_downline_current_tier->id)){
                            $pv_percentage_balance = $get_own_current_tier->amount - $get_downline_current_tier->amount;

                            if($pv_percentage_balance > 0){
                                $total_team_bonus = ($get_downline_monthly_pv[$get_downline->code]) * $pv_percentage_balance / 100;

                                if($total_team_bonus > 0){
                                    $input_team = [];
                                    $input_team['type'] = '4';
                                    $input_team['user_id'] = $merchant->code;
                                    $input_team['user_tier'] = $get_own_current_tier->amount;
                                    $input_team['user_by'] = $get_downline->code;
                                    $input_team['user_by_tier'] = $get_downline_current_tier->amount;
                                    $input_team['product_amount'] = ($get_downline_monthly_pv[$get_downline->code]);
                                    $input_team['comm_pa_type'] = "Percentage";
                                    $input_team['comm_pa'] = $pv_percentage_balance;
                                    $input_team['comm_amount'] = $total_team_bonus;
                                    $input_team['comm_desc'] = "Team Reward";
                                    $input_team['status'] = "1";

                                    AffiliateCommission::create($input_team);
                                }
                            }
                        }else{
                            $total_team_bonus = ($get_downline_monthly_pv[$get_downline->code]) * $get_own_current_tier->amount / 100;

                            if($total_team_bonus > 0){
                                $input_team = [];
                                $input_team['type'] = '4';
                                $input_team['user_id'] = $merchant->code;
                                $input_team['user_tier'] = $get_own_current_tier->amount;
                                $input_team['user_by'] = $get_downline->code;
                                $input_team['user_by_tier'] = 0;
                                $input_team['product_amount'] = ($get_downline_monthly_pv[$get_downline->code]);
                                $input_team['comm_pa_type'] = "Percentage";
                                $input_team['comm_pa'] = $get_own_current_tier->amount;
                                $input_team['comm_amount'] = $total_team_bonus;
                                $input_team['comm_desc'] = "Team Reward";
                                $input_team['status'] = "1";

                                AffiliateCommission::create($input_team);
                            }
                        }
                    }
                    echo $get_own_current_tier->amount.' - '.$highest_team_bonus->amount;
                    if($get_own_current_tier->amount == $highest_team_bonus->amount){
                        $affs = Affiliate::select('affiliates.*')
                                         ->join('merchants as m', 'm.code', 'affiliates.affiliate_id')
                                         ->where('sort_level', '<=', '3')
                                         ->where('m.status', '1')
                                         ->where('user_id', $merchant->code)
                                         ->orderBy('sort_level', 'asc')
                                         ->get();

                        foreach($affs as $aff){
                            $get_aff_downline_total_pv = $this->TotalEarnPV($aff->affiliate_id);
                            $get_aff_current_tier = SettingTeamDividend::where('target_box', '<=', $get_aff_downline_total_pv)
                                                              ->orderBy('target_box', 'desc')
                                                              ->first();

                            $get_layer_monthly_pv = $this->MonthlyOwnPVWallet($aff->affiliate_id, $lastMonth);
                            if(!empty($get_aff_current_tier->amount)){
                                // echo $aff->affiliate_id.' - '.$aff->user_id.' - '.$get_aff_downline_total_pv;
                                // echo $get_own_current_tier->amount.' - '.$get_aff_current_tier->amount;
                                // echo "<br>";
                                if($get_aff_current_tier->amount == $get_own_current_tier->amount){
                                    $SettingMerchantSameLevel = SettingMerchantSameLevel::where('level', $aff->sort_level)
                                                                                        ->first();

                                    $getNonHighTotalSalesPV = $this->MonthlyTotalPVWallet($aff->affiliate_id, $lastMonth);
                                    // echo $getNonHighTotalSalesPV;
                                    // echo 
                                    // echo $aff->sort_level." Downline - ".$aff->affiliate_id." - ".$getNonHighTotalSalesPV." - Upline: ".$merchant->code." | ";
                                    // echo "<br>";
                                    if($getNonHighTotalSalesPV > 0){
                                        if(!empty($SettingMerchantSameLevel->id)){
                                            if($SettingMerchantSameLevel->comm_type == 'Percentage'){
                                                $comm_amount = $getNonHighTotalSalesPV * $SettingMerchantSameLevel->comm_amount / 100;
                                            }else{
                                                $comm_amount = $SettingMerchantSameLevel->comm_amount;
                                            }

                                            if($comm_amount > 0){
                                                $input_same = [];
                                                $input_same['type'] = '5';
                                                $input_same['user_id'] = $merchant->code;
                                                $input_same['user_tier'] = $get_own_current_tier->amount;
                                                $input_same['user_by'] = $aff->affiliate_id;
                                                $input_same['user_by_tier'] = $get_aff_current_tier->amount;
                                                $input_same['product_amount'] = $getNonHighTotalSalesPV;
                                                $input_same['comm_pa_type'] = $SettingMerchantSameLevel->comm_type;
                                                $input_same['comm_pa'] = $SettingMerchantSameLevel->comm_amount;
                                                $input_same['comm_amount'] = $comm_amount;
                                                $input_same['comm_desc'] = "Same Tier Bonus (".$highest_team_bonus->amount."%)";
                                                $input_same['status'] = "1";

                                                AffiliateCommission::create($input_same);
                                            }
                                        }                                    
                                    }
                                }
                            }
                        }
                    }
                }
            }else{
                
            }

            

            // if(date('Y-m-d') == date('Y-04-01') || date('Y-m-d') == date('Y-07-15') || date('Y-m-d') == date('Y-10-01') || date('Y-m-d') == date('Y-01-01')){

            // }

            $MonthlyTotalSales = $this->MonthlyTotalSales($merchant->code, $lastMonth);

            $token = 0;
            if($MonthlyTotalSales >= 500000){
                $token = 1;
            }elseif($MonthlyTotalSales >= 1000000){
                $token = 2;
            }elseif($MonthlyTotalSales >= 3000000){
                $token = 3;
            }elseif($MonthlyTotalSales >= 5000000){
                $token = 4;
            }

            if($token >= 1){
                $entitled_merchant_total_sales += $MonthlyTotalSales;
            }
            echo $merchant->code.' - '.$MonthlyTotalSales;
            echo "<br>";
        }

        echo "=========================================";
        echo "<br>";
        echo "Total: ".$entitled_merchant_total_sales;
        echo "<br>";
        echo "=========================================";
        echo "<br>";

        $prize_pools = Transaction::select(DB::raw('SUM((grand_total - shipping_fee) * 2 / 100) as totalPrizePool'))
                                  ->where('transactions.status', '1')
                                  ->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m")'), $lastMonth)
                                  ->first();
        $total_price_pool_amount = 0;
        if($entitled_merchant_total_sales > 0 && $prize_pools->totalPrizePool > 0){
            $total_price_pool_amount = $prize_pools->totalPrizePool / $entitled_merchant_total_sales;
        }

        if($total_price_pool_amount > 0){

            foreach($merchants as $p_merchant){
                $MonthlyTotalSales = $this->MonthlyTotalSales($p_merchant->code, $lastMonth);
                

                $token = 0;
                if($MonthlyTotalSales >= 500000){
                    $token = 1;
                }elseif($MonthlyTotalSales >= 1000000){
                    $token = 2;
                }elseif($MonthlyTotalSales >= 3000000){
                    $token = 3;
                }elseif($MonthlyTotalSales >= 5000000){
                    $token = 4;
                }

                if($token >= 1){
                    $total_token_sales = $MonthlyTotalSales * $token;

                    if($total_token_sales > 0){
                        $total_token_sales_convert = $total_token_sales * $total_price_pool_amount;

                        if($total_token_sales_convert > 0){
                            $input_prize = [];
                            $input_prize['type'] = '6';
                            $input_prize['user_id'] = $p_merchant->code;
                            $input_prize['product_amount'] = $total_token_sales;
                            $input_prize['comm_pa_type'] = "Percentage";
                            $input_prize['comm_pa'] = $total_price_pool_amount;
                            $input_prize['comm_amount'] = $total_token_sales_convert;
                            $input_prize['comm_desc'] = "Prize Pool Bonus";
                            $input_prize['status'] = "99";

                            // AffiliateCommission::create($input_prize);
                        }
                    }
                }
            }

        }
        // echo $total_price_pool_amount;
        exit();
        Toastr::success($translation_data['backendlang']['backendlang']['Run Successfully'] ?? 'Run Successfully');
        return redirect()->route('website_setting');
    }

    public function MonthlyTotalPVWallet($buyerCode, $lastMonth)
    {
        $transactions = Transaction::select(DB::raw('SUM((d.unit_price * d.quantity) * 0.5) as totalGetPV'))
                                   ->join('transaction_details as d', 'd.transaction_id', 'transactions.id')
                                   ->where('user_id', $buyerCode)
                                   ->where('transactions.status', '1')
                                   ->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m")'), $lastMonth)
                                   ->first();

        $affs = Affiliate::select(DB::raw('SUM((d.unit_price * d.quantity) * 0.5) as totalGetPV'))
                         ->join('merchants as m', 'm.code', 'affiliates.affiliate_id')
                         ->join('transactions as t', 't.user_id', 'm.code')
                         ->join('transaction_details as d', 'd.transaction_id', 't.id')
                         ->where('t.status', '1')
                         ->where('affiliates.user_id', $buyerCode)
                         ->where(DB::raw('DATE_FORMAT(t.created_at, "%Y-%m")'), $lastMonth)
                         ->first();
        

        $downline_total_pv = $affs->totalGetPV;

        $affs_customers = Affiliate::select(DB::raw('SUM((d.unit_price * d.quantity) * 0.5) as totalGetPV'))
                                   ->join('merchants as m', 'm.code', 'affiliates.affiliate_id')
                                   ->join('users as u', 'u.master_id', 'm.code')
                                   ->join('transactions as t', 't.user_id', 'u.code')
                                   ->join('transaction_details as d', 'd.transaction_id', 't.id')
                                   ->where('t.status', '1')
                                   ->where('affiliates.user_id', $buyerCode)
                                   ->where(DB::raw('DATE_FORMAT(t.created_at, "%Y-%m")'), $lastMonth)
                                   ->first();
        $downline_customer_total_pv = $affs_customers->totalGetPV;
        // exit();

        $mb_transactions = Transaction::select(DB::raw('SUM((d.unit_price * d.quantity) * 0.5) as totalGetPV'))
                               ->join('transaction_details as d', 'd.transaction_id', 'transactions.id')
                               ->join('users as u', 'transactions.user_id', 'u.code')
                               ->where('u.master_id', $buyerCode)
                               ->where('transactions.status', '1')
                               ->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m")'), $lastMonth)
                               ->first();

        $member_total_pv = $mb_transactions->totalGetPV;

        return (!empty($transactions->totalGetPV) ? $transactions->totalGetPV : 0) + $downline_total_pv + $downline_customer_total_pv + $member_total_pv;
    }

    public function MonthlyOwnPVWallet($buyerCode, $lastMonth)
    {
        $transactions = Transaction::select(DB::raw('SUM((d.unit_price * d.quantity) * 0.5) as totalGetPV'))
                                   ->join('transaction_details as d', 'd.transaction_id', 'transactions.id')
                                   ->where('user_id', $buyerCode)
                                   ->where('transactions.status', '1')
                                   ->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m")'), $lastMonth)
                                   ->first();

        $member_total_pv = 0;

        $users = User::where('master_id', $buyerCode)->where('status', '1')->get();

        foreach($users as $user){
            $mb_transactions = Transaction::select(DB::raw('SUM((d.unit_price * d.quantity) * 0.5) as totalGetPV'))
                                                ->join('transaction_details as d', 'd.transaction_id', 'transactions.id')
                                                ->where('user_id', $user->code)
                                                ->where('transactions.status', '1')
                                                ->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m")'), $lastMonth)
                                                ->first();

            $member_total_pv += $mb_transactions->totalGetPV;
        }

        return (!empty($transactions->totalGetPV) ? $transactions->totalGetPV : 0) + $member_total_pv;
    }



    public function TotalEarnPV($buyerCode)
    {
        $transactions = Transaction::select(DB::raw('SUM((d.unit_price * d.quantity) * 0.5) as totalGetPV'))
                                   ->join('transaction_details as d', 'd.transaction_id', 'transactions.id')
                                   ->where('user_id', $buyerCode)
                                   ->where('transactions.status', '1')
                                   
                                   ->first();

        $affs = Affiliate::select(DB::raw('SUM((d.unit_price * d.quantity) * 0.5) as totalGetPV'))
                         ->join('merchants as m', 'm.code', 'affiliates.affiliate_id')
                         ->join('transactions as t', 't.user_id', 'affiliates.affiliate_id')
                         ->join('transaction_details as d', 'd.transaction_id', 't.id')
                         ->where('t.status', '1')
                         ->where('affiliates.user_id', $buyerCode)
                         
                         ->first();
        

        $downline_total_pv = $affs->totalGetPV;

        $affs_customers = Affiliate::select(DB::raw('SUM((d.unit_price * d.quantity) * 0.5) as totalGetPV'))
                                   ->join('merchants as m', 'm.code', 'affiliates.affiliate_id')
                                   ->join('users as u', 'u.master_id', 'm.code')
                                   ->join('transactions as t', 't.user_id', 'u.code')
                                   ->join('transaction_details as d', 'd.transaction_id', 't.id')
                                   ->where('t.status', '1')
                                   ->where('affiliates.user_id', $buyerCode)
                                   
                                   ->first();
        $downline_customer_total_pv = $affs_customers->totalGetPV;
        // exit();

        $mb_transactions = Transaction::select(DB::raw('SUM((d.unit_price * d.quantity) * 0.5) as totalGetPV'))
                               ->join('transaction_details as d', 'd.transaction_id', 'transactions.id')
                               ->join('users as u', 'transactions.user_id', 'u.code')
                               ->where('u.master_id', $buyerCode)
                               ->where('transactions.status', '1')
                               
                               ->first();

        $member_total_pv = $mb_transactions->totalGetPV;

        return $transactions->totalGetPV + $downline_total_pv + $downline_customer_total_pv + $member_total_pv;
    }

    public function getNonHighTotalSalesPV($buyerCode, $lastMonth)
    {
        $merchants = Merchant::where('master_id', $buyerCode)->get();

        $highest_team_bonus = SettingTeamDividend::orderBy('target_box', 'desc')->first();
        $totalPVSales = 0;
        foreach($merchants as $merchant){
            // echo $merchant->code." | ";
            $getRankingPv = $this->TotalEarnPV($merchant->code);
            $get_aff_current_tier = SettingTeamDividend::where('target_box', '<=', $getRankingPv)
                                                       ->orderBy('target_box', 'desc')
                                                       ->first();


            if(!empty($highest_team_bonus->id) && !empty($get_aff_current_tier->id)){
                // echo number_format($highest_team_bonus->amount, 2).' - '.number_format($get_aff_current_tier->amount, 2);
                if(floatval($highest_team_bonus->amount) != floatval($get_aff_current_tier->amount)){
                    $getMerchantMonthlyPV = $this->MonthlyOwnPVWallet($merchant->code, $lastMonth);
                    $totalPVSales += $getMerchantMonthlyPV;
                    // echo $merchant->code." - ".$buyerCode.' - '.$get_aff_current_tier->amount.' | ';
                    $affs = Affiliate::select('affiliates.*')
                                     ->join('merchants as m', 'm.code', 'affiliates.affiliate_id')
                                     ->where('user_id', $merchant->code)
                                     ->orderBy('sort_level', 'asc')
                                     ->get();

                    foreach($affs as $aff){
                        $getsales = $this->MonthlyOwnPVWallet($aff->affiliate_id, $lastMonth);
                        $totalPVSales += $getsales;
                    }
                }
            }
        }

        return $totalPVSales;
    }

    public function MonthlyTotalSales($buyerCode, $lastMonth)
    {
        $transactions = Transaction::select(DB::raw('SUM((grand_total - shipping_fee)) as totalGetPV'))
                                   ->where('user_id', $buyerCode)
                                   ->where('transactions.status', '1')
                                   ->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m")'), $lastMonth)
                                   ->first();

        $affs = Affiliate::select(DB::raw('SUM((grand_total - shipping_fee)) as totalGetPV'))
                         ->join('merchants as m', 'm.code', 'affiliates.affiliate_id')
                         ->join('transactions as t', 't.user_id', 'm.code')
                         ->where('t.status', '1')
                         ->where('affiliates.user_id', $buyerCode)
                         ->where(DB::raw('DATE_FORMAT(t.created_at, "%Y-%m")'), $lastMonth)
                         ->first();
        

        $downline_total_pv = $affs->totalGetPV;

        $affs_customers = Affiliate::select(DB::raw('SUM((grand_total - shipping_fee)) as totalGetPV'))
                                   ->join('merchants as m', 'm.code', 'affiliates.affiliate_id')
                                   ->join('users as u', 'u.master_id', 'm.code')
                                   ->join('transactions as t', 't.user_id', 'u.code')
                                   ->where('t.status', '1')
                                   ->where('affiliates.user_id', $buyerCode)
                                   ->where(DB::raw('DATE_FORMAT(t.created_at, "%Y-%m")'), $lastMonth)
                                   ->first();
        $downline_customer_total_pv = $affs_customers->totalGetPV;
        // exit();

        $mb_transactions = Transaction::select(DB::raw('SUM((grand_total - shipping_fee)) as totalGetPV'))
                               ->join('users as u', 'transactions.user_id', 'u.code')
                               ->where('u.master_id', $buyerCode)
                               ->where('transactions.status', '1')
                               ->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m")'), $lastMonth)
                               ->first();

        $member_total_pv = $mb_transactions->totalGetPV;

        return (!empty($transactions->totalGetPV) ? $transactions->totalGetPV : 0) + $downline_total_pv + $downline_customer_total_pv + $member_total_pv;
    }

    public function setting_einvoice(){
      $countries = GlobalController::global_countries();
      $states = State::where('status', 1)->get();
      $setting = SettingEinvoice::find(1);

      return view('backend.settings.setting_e_invoice', compact('countries','states', 'setting'));
    }

    public function setting_einvoice_save(Request $request){

      $translation_data = GlobalController::get_translations();
      $validator = Validator::make($request->all(), [
        'client_id' => 'required_if:einvoice_status,==,1',
        'client_secret' => 'required_if:einvoice_status,==,1',
        'supplier_name' => 'required_if:einvoice_status,==,1',
        'supplier_nric' => 'required_if:einvoice_status,==,1',
        'supplier_tin' => 'required_if:einvoice_status,==,1',
        'supplier_phone' => 'required_if:einvoice_status,==,1',
        'supplier_email' => 'required_if:einvoice_status,==,1,email',
        'industry_classification_code' => 'required_if:einvoice_status,==,1',
        'industry_classification_desc' => 'required_if:einvoice_status,==,1',
        'supplier_country' => 'required_if:einvoice_status,==,1',
        'supplier_state' => 'required_if:einvoice_status,==,1',
        'address_city' => 'required_if:einvoice_status,==,1',
        'address_postal_code' => 'required_if:einvoice_status,==,1',
        'address_line_1' => 'required_if:einvoice_status,==,1',
        'address_line_2' => 'required_if:einvoice_status,==,1',
        'address_line_3' => 'required_if:einvoice_status,==,1',
      ],[
        'required_if' => 'The :attribute field is required.',
      ]);

      if($validator->fails()){
        return Redirect::back()->withInput()->withErrors($validator);
      }

      $setting = SettingEinvoice::find(1);

      if(!$setting){
        $setting = new SettingEinvoice();
      }

      $setting->client_id = $request->client_id;
      $setting->client_secret = $request->client_secret;

      if($request->einvoice_status == 1){
        $setting->supplier_name = $request->supplier_name;
        $setting->supplier_nric = $request->supplier_nric;
        $setting->supplier_tin = $request->supplier_tin;
        $setting->supplier_telephone = $request->supplier_phone;
        $setting->supplier_email = $request->supplier_email;
        $setting->industry_classification_code = $request->industry_classification_code;
        $setting->industry_classification_desc = $request->industry_classification_desc;
        $setting->address_1 = $request->address_line_1;
        $setting->address_2 = $request->address_line_2;
        $setting->address_3 = $request->address_line_3;
        $setting->country_code = $request->supplier_country;
        $setting->state_code = $request->supplier_state;
        $setting->postal_code = $request->address_postal_code;
        $setting->city_name = $request->address_city;
      }

      $setting->status = $request->einvoice_status ? 1 : 0;

      $setting->save();
      
      Toastr::success($translation_data['backendlang']['backendlang']['e-Invoice Setting Saved Successfully!'] ?? "e-Invoice Setting Saved Successfully!");
      return redirect()->route('setting_einvoice');
    }

    public function setting_website_messages()
    {
        $settings = SettingWebsiteMessage::select('setting_website_messages.*')
                                         ->where('status', '1')
                                         ->get();

        return view('backend.settings.setting_website_messages', ['settings'=>$settings]);
    }

    public function save_setting_website_messages(Request $request)
    {
        $translation_data = GlobalController::get_translations();
        try{
            \DB::beginTransaction();

            for($a = 0; $a < count($request->message); $a++){
                if(!empty($request->sid[$a])){
                    $sid = $request->sid[$a];
                    $message = $request->message[$a];
                    $message_cn = $request->message_cn[$a];

                    $update = SettingWebsiteMessage::find($sid);

                    if($update){
                        $update->message = $message;
                        $update->message_cn = $message_cn;
                        $update->save();
                    }
                }else{
                    if(!empty($request->message[$a])){
                        $insert = new SettingWebsiteMessage();
                        $insert->message = $request->message[$a];
                        $insert->message_cn = $request->message_cn[$a];
                        $insert->status = 1;
                        $insert->created_at = now();
                        $insert->updated_at = now();
                        $insert->save();
                    }
                }
            }

            \DB::commit();
        }catch (\Exception $e){
            \DB::rollback();
            Toastr::error($e->getMessage());
            return Redirect::back()->withInput($request->all())->withErrors($e->getMessage());
        }catch(\Error $e){
            \DB::rollback();
            Toastr::error($e->getMessage());
            return Redirect::back()->withInput($request->all())->withErrors($e->getMessage());
        }

        Toastr::success($translation_data['backendlang']['backendlang']['Update_Successful'] ?? 'Update Successful');
        return redirect()->route('setting_website_messages');
    }

    public function setting_header()
    {
        $setting = SettingHeader::find(1);

        return view('backend.settings.setting_header', ['setting'=>$setting]);
    }

    public function save_setting_header(Request $request)
    {
        $translation_data = GlobalController::get_translations();
        try{
            \DB::beginTransaction();

            $website_setting = SettingHeader::find(1);

            if(!empty($request->file('shop_image'))){
                $files = $request->file('shop_image'); 
                $name = $files->getClientOriginalName();
                $exp = explode(".", $name);
                $file_ext = end($exp);
                $name = md5($name.date('Y-m-d H:i:s')).'.'.$file_ext;
            
                $files->move(GlobalController::get_image_path("uploads/shop_image/"), $name);
                $website_setting->shop_image = "uploads/shop_image/".$name;
            }
            
            if(!empty($request->file('about_us_image'))){
                $files = $request->file('about_us_image'); 
                $name = $files->getClientOriginalName();
                $exp = explode(".", $name);
                $file_ext = end($exp);
                $name = md5($name.date('Y-m-d H:i:s')).'.'.$file_ext;
            
                $files->move(GlobalController::get_image_path("uploads/about_us_image/"), $name);
                $website_setting->about_us_image = "uploads/about_us_image/".$name;
            }

            if(!empty($request->file('blog_image'))){
                $files = $request->file('blog_image'); 
                $name = $files->getClientOriginalName();
                $exp = explode(".", $name);
                $file_ext = end($exp);
                $name = md5($name.date('Y-m-d H:i:s')).'.'.$file_ext;
            
                $files->move(GlobalController::get_image_path("uploads/blog_image/"), $name);
                $website_setting->blog_image = "uploads/blog_image/".$name;
            }

            if(!empty($request->file('contact_us_image'))){
                $files = $request->file('contact_us_image'); 
                $name = $files->getClientOriginalName();
                $exp = explode(".", $name);
                $file_ext = end($exp);
                $name = md5($name.date('Y-m-d H:i:s')).'.'.$file_ext;
            
                $files->move(GlobalController::get_image_path("uploads/contact_us_image/"), $name);
                $website_setting->contact_us_image = "uploads/contact_us_image/".$name;
            }

            if(!empty($request->file('faqs_image'))){
                $files = $request->file('faqs_image'); 
                $name = $files->getClientOriginalName();
                $exp = explode(".", $name);
                $file_ext = end($exp);
                $name = md5($name.date('Y-m-d H:i:s')).'.'.$file_ext;
            
                $files->move(GlobalController::get_image_path("uploads/faqs_image/"), $name);
                $website_setting->faqs_image = "uploads/faqs_image/".$name;
            }

            if(!empty($request->file('quiz_bg_image'))){
                $files = $request->file('quiz_bg_image'); 
                $name = $files->getClientOriginalName();
                $exp = explode(".", $name);
                $file_ext = end($exp);
                $name = md5($name.date('Y-m-d H:i:s')).'.'.$file_ext;
            
                $files->move(GlobalController::get_image_path("uploads/quiz_bg_image/"), $name);
                $website_setting->quiz_bg_image = "uploads/quiz_bg_image/".$name;
            }

            if(!empty($request->file('privacy_policy_bg_image'))){
                $files = $request->file('privacy_policy_bg_image'); 
                $name = $files->getClientOriginalName();
                $exp = explode(".", $name);
                $file_ext = end($exp);
                $name = md5($name.date('Y-m-d H:i:s')).'.'.$file_ext;
            
                $files->move(GlobalController::get_image_path("uploads/privacy_policy_bg_image/"), $name);
                $website_setting->privacy_policy_bg_image = "uploads/privacy_policy_bg_image/".$name;
            }
            
            if(!empty($request->file('return_policy_bg_image'))){
                $files = $request->file('return_policy_bg_image'); 
                $name = $files->getClientOriginalName();
                $exp = explode(".", $name);
                $file_ext = end($exp);
                $name = md5($name.date('Y-m-d H:i:s')).'.'.$file_ext;
            
                $files->move(GlobalController::get_image_path("uploads/return_policy_bg_image/"), $name);
                $website_setting->return_policy_bg_image = "uploads/return_policy_bg_image/".$name;
            }
            
            if(!empty($request->file('shipping_policy_bg_image'))){
                $files = $request->file('shipping_policy_bg_image'); 
                $name = $files->getClientOriginalName();
                $exp = explode(".", $name);
                $file_ext = end($exp);
                $name = md5($name.date('Y-m-d H:i:s')).'.'.$file_ext;
            
                $files->move(GlobalController::get_image_path("uploads/shipping_policy_bg_image/"), $name);
                $website_setting->shipping_policy_bg_image = "uploads/shipping_policy_bg_image/".$name;
            }
            
            if(!empty($request->file('terms_bg_image'))){
                $files = $request->file('terms_bg_image'); 
                $name = $files->getClientOriginalName();
                $exp = explode(".", $name);
                $file_ext = end($exp);
                $name = md5($name.date('Y-m-d H:i:s')).'.'.$file_ext;
            
                $files->move(GlobalController::get_image_path("uploads/terms_bg_image/"), $name);
                $website_setting->terms_bg_image = "uploads/terms_bg_image/".$name;
            }
            
            $website_setting->save();


            \DB::commit();
        }catch (\Exception $e){
            \DB::rollback();
            Toastr::error($e->getMessage());
            return Redirect::back()->withInput($request->all())->withErrors($e->getMessage());
        }catch(\Error $e){
            \DB::rollback();
            Toastr::error($e->getMessage());
            return Redirect::back()->withInput($request->all())->withErrors($e->getMessage());
        }

        Toastr::success($translation_data['backendlang']['backendlang']['Update_Successful'] ?? 'Update Successful');
        return redirect()->route('setting_header');
    }

    public function setting_home_page()
    {
        $home_page = [];
        for($x = 1; $x <= 7; $x++){
            $home_page[$x] = SettingHomePage::find($x); 
        }

        return view('backend.settings.setting_home_page', compact('home_page'));
    }

    public function save_setting_home_page(Request $request)
    {
        $translation_data = GlobalController::get_translations();
        try {
            \DB::beginTransaction();

            for($x = 1; $x <= count($request->description); $x++){
                $setting = SettingHomePage::find($x);

                if(empty($setting->id)){
                    $setting = new SettingHomePage();
                }

                if(!empty($request->file('image')[$x])){
                    $files = $request->file('image')[$x]; 
                    $name = $files->getClientOriginalName();
                    $exp = explode(".", $name);
                    $file_ext = end($exp);
                    $name = md5($name.date('Y-m-d H:i:s')).'.'.$file_ext;

                    $files->move(GlobalController::get_image_path("uploads/home_page_image/"), $name);
                    $setting->image = "uploads/home_page_image/".$name;
                }

                $setting->description = $request->description[$x];
                $setting->description_cn = !empty($request->description_cn[$x]) ? $request->description_cn[$x] : NULL;
                $setting->save();
            }

            \DB::commit();
        } catch (\Exception $e){
            \DB::rollback();
            Toastr::error($translation_data['backendlang']['backendlang']['Setting Updated'] ?? 'Setting Updated'); 
            return redirect()->route('setting_home_page');
        }

        Toastr::success($translation_data['backendlang']['backendlang']['Setting Updated'] ?? 'Setting Updated');
        return redirect()->route('setting_home_page');
    }

    public function setting_second_banner()
    {
        return view('backend.settings.setting_second_banner');
    }

    public function setting_home_video()
    {
        $video = [];
        for($x = 1; $x <= 7; $x++){
            $video[$x] = SettingHomeVideo::find($x); 
        }

        return view('backend.settings.setting_home_video', compact('video'));
    }

    public function save_setting_home_video(Request $request)
    {
        $translation_data = GlobalController::get_translations();   
        try {
            \DB::beginTransaction();

            for($x = 1; $x <= 2; $x++){
                $setting = SettingHomeVideo::find($x);

                if(empty($setting)){
                    $setting = new SettingHomeVideo();
                }

                if(!empty($request->file('image')[$x])){
                    $file = $request->file('image')[$x]; 
                    $name = $file->getClientOriginalName();
                    $exp = explode(".", $name);
                    $file_ext = end($exp);
                    $name = md5($name . date('Y-m-d H:i:s')) . '.' . $file_ext;

                    $file->move(GlobalController::get_image_path("uploads/home_page_video/"), $name);
                    $setting->image = "uploads/home_page_video/" . $name;
                }

                if($x == 1){
                    $setting->description = !empty($request->description[$x]) ? $request->description[$x] : null;
                    $setting->description_cn = !empty($request->description_cn[$x]) ? $request->description_cn[$x] : null;
                }

                $setting->save();
            }

            \DB::commit();
        }catch (\Exception $e){
            \DB::rollback();
            Toastr::error($translation_data['backendlang']['backendlang']['Setting Updated'] ?? 'Setting Updated'); 
            return redirect()->route('setting_home_video');
        }

        Toastr::success($translation_data['backendlang']['backendlang']['Setting Updated'] ?? 'Setting Updated');
        return redirect()->route('setting_home_video');
    }

    public function setting_home_overview()
    {
        $setting = WebsiteSetting::find(1); 

        return view('backend.settings.setting_home_overview', compact('setting'));
    }

    public function save_setting_home_overview(Request $request)
    {
        $translation_data = GlobalController::get_translations();
        try {
            \DB::beginTransaction();

            $setting = WebsiteSetting::find(1);
            $setting->home_page_overview = $request->home_page_overview ?? NULL;
            $setting->home_page_overview_cn = $request->home_page_overview_cn ?? NULL;
            $setting->save();

            \DB::commit();
        }catch (\Exception $e){
            \DB::rollback();
            Toastr::error($translation_data['backendlang']['backendlang']['Setting Updated'] ?? 'Setting Updated');
            return redirect()->route('setting_home_overview');
        }

        Toastr::success($translation_data['backendlang']['backendlang']['Setting Updated'] ?? 'Setting Updated');
        return redirect()->route('setting_home_overview');
    }

    public function setting_featured_product_title()
    {
        $setting = WebsiteSetting::find(1); 

        return view('backend.settings.setting_featured_product_title', compact('setting'));
    }

    public function save_setting_featured_product_title(Request $request)
    {
        $translation_data = GlobalController::get_translations();
        // dd($request->all());
        try {
            \DB::beginTransaction();

            $setting = WebsiteSetting::find(1);
            $setting->featured_product_title = $request->setting_featured_product_title ?? NULL;
            $setting->featured_product_title_cn = $request->setting_featured_product_title_cn ?? NULL;
            $setting->save();

            \DB::commit();
        }catch (\Exception $e){
            \DB::rollback();
            Toastr::error($translation_data['backendlang']['backendlang']['Setting Updated'] ?? 'Setting Updated');
            return redirect()->route('setting_featured_product_title');
        }

        Toastr::success($translation_data['backendlang']['backendlang']['Setting Updated'] ?? 'Setting Updated');
        return redirect()->route('setting_featured_product_title');
    }

    public function setting_website_countries()
    {
        $setting = WebsiteSetting::find(1);
        $countries = TblCountry::orderBy('country_name', 'asc')
                               ->where('country_contact', '!=', '')
                               ->get();

        return view('backend.settings.setting_website_countries', compact('setting', 'countries'));
    }

    public function save_setting_website_countries(Request $request)
    {
        $translation_data = GlobalController::get_translations();
        try {
            \DB::beginTransaction();
           
            $validator = Validator::make($request->all(), [
                'countries' => 'required'
            ]);

            if($validator->fails()){
                return Redirect::back()->withInput($request->all())->withErrors($validator);
            }

            $update = WebsiteSetting::find(1);
            $update->website_countries = !empty($request->countries) ? implode(',', $request->countries) : '';
            $update->save();

            \DB::commit();
        }catch (\Exception $e){
            \DB::rollback();
            Toastr::error($translation_data['backendlang']['backendlang']['Setting Updated'] ?? 'Setting Updated');
            return redirect()->route('setting_website_countries');
        }

        Toastr::success($translation_data['backendlang']['backendlang']['Setting Updated'] ?? 'Setting Updated');
        return redirect()->route('setting_website_countries');
    }

    public function setting_auto_withdrawal(){
        
        $setting = WebsiteSetting::first();

        return view('backend.settings.setting_auto_withdrawal',['setting'=>$setting]);
    }

    public function save_setting_auto_withdrawal(Request $request){
       
       $translation_data = GlobalController::get_translations();
        try {
            \DB::beginTransaction();
                
                $setting = WebsiteSetting::find(1);

                $setting->auto_withdrawal_enable = isset($request->auto_withdrawal_enable)? 1 :0;
                $setting->auto_withdrawal_day = $request->day_only[0];
                $setting->auto_withdrawal_day_2 = $request->day_only[1];
                if(isset($request->min_withdrawal_amount)){
                    $setting->min_withdrawal_amount = $request->min_withdrawal_amount;
                }

                $setting->save();
            \DB::commit();
        }catch (\Exception $e){
            \DB::rollback();
            Toastr::error($e->getMessage());
            return redirect()->back();
        }

        Toastr::success($translation_data['backendlang']['backendlang']['Auto Withdrawal Setting Updated'] ?? 'Auto Withdrawal Setting Updated');
        return redirect()->back();
    }

    public function setting_payment_gateway()
    {
        $settings = SettingPaymentGateway::get();

        return view('backend.settings.setting_payment_gateway', compact('settings'));
    }

    public function save_setting_payment_gateway(Request $request)
    {
        $translation_data = GlobalController::get_translations();
        try {
            \DB::beginTransaction();

            for ($x = 0; $x < count($request->setting_id); $x++) {
                $payment_gateway_setting = SettingPaymentGateway::find($request->setting_id[$x]);
                
                if ($request->setting_enable[$payment_gateway_setting->id] == 1) {
                    $payment_gateway_setting->status = 1;
                } else {
                    $payment_gateway_setting->status = 3;
                }

                $payment_gateway_setting->param = $request->param[$x];
                $payment_gateway_setting->param_1 = $request->param_1[$x];

                $payment_gateway_setting->save();
            }

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            Toastr::error($e->getMessage());
            return Redirect::back()->withInput($request->all())->withErrors($e->getMessage());
        } catch (\Error $e) {
            \DB::rollback();
            Toastr::error($e->getMessage());
            return Redirect::back()->withInput($request->all())->withErrors($e->getMessage());
        }

        Toastr::success($translation_data['backendlang']['backendlang']['Setting Updated'] ?? 'Setting Updated');
        return redirect()->route('setting_payment_gateway');
    }

    public function setting_colour()
    {
        $settings = SettingColour::get();
        $select = SettingColour::first();


        return view('backend.settings.setting_colour', compact('settings','select'));
    }

    public function save_setting_colour(Request $request)
    {
        $translation_data = GlobalController::get_translations();
        try {
            \DB::beginTransaction();

            $select = SettingColour::get();

            if(!$select->isEmpty()){
                $colour = SettingColour::find(1);
            }else{
                $colour = new SettingColour();
            }

            $colour->button_colour = $request->button_colour;
            $colour->text_colour = $request->text_colour;
            $colour->hover_colour = $request->hover_colour;
            $colour->header_announcement_background_colour = $request->header_announcement_background_colour;
            $colour->header_announcement_text_colour = $request->header_announcement_text_colour;
            $colour->header_background_colour = $request->header_background_colour;
            $colour->header_text_colour = $request->header_text_colour;
            $colour->header_text_hover_colour = $request->header_text_hover_colour;
            $colour->footer_trademark_background_colour = $request->footer_trademark_background_colour;
            $colour->footer_trademark_text_colour = $request->footer_trademark_text_colour;
            $colour->footer_background_colour = $request->footer_background_colour;
            $colour->footer_text_colour = $request->footer_text_colour;
            $colour->footer_text_hover_colour = $request->footer_text_hover_colour;

            $colour->save();

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            Toastr::error($e->getMessage());
            return Redirect::back()->withInput($request->all())->withErrors($e->getMessage());
        } catch (\Error $e) {
            \DB::rollback();
            Toastr::error($e->getMessage());
            return Redirect::back()->withInput($request->all())->withErrors($e->getMessage());
        }

        Toastr::success( $translation_data['backendlang']['backendlang']['The colour is saved successfully'] ?? 'The colour is saved successfully');
        return redirect()->route('setting_colour');
    }

}
