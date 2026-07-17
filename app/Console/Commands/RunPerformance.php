<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Cart;
use App\Transaction;
use App\WithdrawalTransaction;
use App\Product;
use App\TransactionDetail;
use App\Stock;
use App\User;
use App\Agent;

use App\SettingPerformanceMain;
use App\SettingPerformanceDividend;
use App\Merchant;
use App\Affiliate;
use App\AffiliateCommission;
use App\TopupTransaction;
use App\SettingMerchantRebate;
use App\PackageItem;

use App\SettingTeamMain;
use App\SettingTeamDividend;
use App\SettingMonthlyAgentSalesBonus;
use App\SettingDownlineBonus;
use App\BankAccount;
use App\SettingMerchantSameLevel;

use App\WebsiteSetting;
use App\SettingPrizePool;

use DB, Mail;
use App\Http\Controllers\GlobalController;

date_default_timezone_set("Asia/Kuala_Lumpur");
class RunPerformance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:cronjob';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run Cron Job';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {   
        // Manual options removed; command runs scheduled tasks only
        //Clear Cart
        // $cart = Cart::where(DB::raw('DATE_ADD(created_at, INTERVAL 7 DAY)'), '<=', date('Y-m-d H:i:s'))->delete();
        $cart = Cart::where('id', '2')->delete();

        $transaction = Transaction::where('status', '99')->where(DB::raw('DATE_ADD(created_at, INTERVAL 7 DAY)'), '<=', date('Y-m-d H:i:s'))->update(['status'=>'55']);



        $lastYear = date("Y-m", strtotime("-1 year"));
        $lastMonth = date("Y-m", strtotime("previous month"));
        $merchants = Merchant::where('status', '1')->get();
        $users = User::where('status', '1')->get();
        $lastMonth = date("2022-09");

        if(date('Y-m-d') == date('Y-01-01')){
            GlobalController::annual_prize_pool(date('Y', strtotime('-1 year')));
        }

        if(date('Y-m-d') == date('Y-m-01')){
            GlobalController::team_reward();

            $checkMonth = date('Y-m', strtotime('-1 day')); 
            $agents = Agent::where('status', '1')->get();
            foreach ($agents as $agent) {
                $agentLevel = $agent->get_level;
                if (!$agentLevel || empty($agentLevel->target)) continue;

                $ownSales = Transaction::where('user_id', $agent->code)
                    ->where('status', '1')
                    ->whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [$checkMonth])
                    ->sum('grand_total');

                $firstLevelMembers = Affiliate::where('user_id', $agent->code)
                    ->where('sort_level', 1)
                    ->pluck('affiliate_id')
                    ->toArray();

                $downlineSales = 0;
                if (!empty($firstLevelMembers)) {
                    $downlineSales = Transaction::whereIn('user_id', $firstLevelMembers)
                        ->where('status', '1')
                        ->whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [$checkMonth])
                        ->sum('grand_total');
                }

                $totalSales = (float) $ownSales + (float) $downlineSales;
                if ($totalSales < (float) $agentLevel->target) {
                    AffiliateCommission::where('user_id', $agent->code)
                        ->where('status', 1)
                        ->whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [$checkMonth])
                        ->whereRaw("DATE_FORMAT(run_date, '%Y-%m') = ?", [$checkMonth])
                        ->update(['status' => 2, 'burned' => 1]);
                }
            }
        }

        $performance_reward_setting = SettingPerformanceMain::first();
        if(date('d') == date('t')){
            foreach($merchants as $merchant){
                GlobalController::give_performance_reward($merchant->code);
            }
        }
        
        $website_setting = WebsiteSetting::first();

        if($website_setting->auto_withdrawal_enable == 1){
            if (in_array(date('d'), [$website_setting->auto_withdrawal_day, $website_setting->auto_withdrawal_day_2])) {
                $agents_with_auto_withdrawal = Agent::where('withdrawal_type', '1')
                                                    ->where('status', '1')
                                                    ->get();

                foreach ($agents_with_auto_withdrawal as $agent_with_auto_withdrawal) {
                    $auto_withdrawal = GlobalController::auto_withdrawal($agent_with_auto_withdrawal->code);
                    if ($auto_withdrawal != 'ok') {
                        \Log::error($auto_withdrawal);
                    }
                }
            }
        }
        // GlobalController::auto_withdrawal();

        // foreach($merchants as $merchant){
        //     $get_own_total_pv = $this->TotalEarnPV($merchant->code);
        //     $MonthlyTotalPVWallet = $this->MonthlyTotalPVWallet($merchant->code, $lastMonth);
        //     $MonthlyOwnPVWallet = $this->MonthlyOwnPVWallet($merchant->code, $lastMonth);
        //     // echo $get_own_total_pv.' - '.$merchant->code.' - '.$MonthlyTotalPVWallet.' | ';
        //     $SettingTeamDividend = SettingTeamDividend::where('target_box', '<=', $MonthlyTotalPVWallet)
        //                                               ->orderBy('target_box', 'desc')
        //                                               ->first();


        //     $get_own_current_tier = SettingTeamDividend::where('target_box', '<=', $get_own_total_pv)
        //                                               ->orderBy('target_box', 'desc')
        //                                               ->first();

        //     $highest_team_bonus = SettingTeamDividend::orderBy('target_box', 'desc')
        //                                                      ->first();

        //     if(!empty($get_own_current_tier->amount)){

                
        //         if(date('Y-m-d') == date('Y-m-25')){
        //             $get_downlines = Merchant::where('master_id', $merchant->code)
        //                                      ->get();

        //             // $get_downlines = Affiliate::select('affiliates.*', 'm.lvl', 'm.code')
        //             //                           ->join('merchants as m', 'm.code', 'affiliates.affiliate_id')
        //             //                           ->where('m.status', '1')
        //             //                           ->where('user_id', $merchant->code)
        //             //                           ->get();
        //             $own_total_team_bonus = ($MonthlyOwnPVWallet) * $get_own_current_tier->amount / 100;

        //             if($own_total_team_bonus > 0){
        //                 $input_team = [];
        //                 $input_team['type'] = '4';
        //                 $input_team['user_id'] = $merchant->code;
        //                 $input_team['user_tier'] = $get_own_current_tier->amount;
        //                 $input_team['user_by'] = "";
        //                 $input_team['user_by_tier'] = 0;
        //                 $input_team['product_amount'] = ($MonthlyOwnPVWallet);
        //                 $input_team['comm_pa_type'] = "Percentage";
        //                 $input_team['comm_pa'] = $get_own_current_tier->amount;
        //                 $input_team['comm_amount'] = $own_total_team_bonus;
        //                 $input_team['comm_desc'] = "Team Reward";
        //                 $input_team['status'] = "1";

        //                 AffiliateCommission::create($input_team);
        //             }

        //             foreach($get_downlines as $get_downline){
        //                 // echo $get_downline->code.' - '.$merchant->code." | ";
        //                 $get_downline_total_pv = $this->TotalEarnPV($get_downline->code);

        //                 $get_downline_monthly_pv[$get_downline->code] = $this->MonthlyTotalPVWallet($get_downline->code, $lastMonth);


        //                 $get_downline_current_tier = SettingTeamDividend::where('target_box', '<=', $get_downline_total_pv)
        //                                                   ->orderBy('target_box', 'desc')
        //                                                   ->first();

        //                 if(!empty($get_downline_current_tier->id)){
        //                     $pv_percentage_balance = $get_own_current_tier->amount - $get_downline_current_tier->amount;

        //                     if($pv_percentage_balance > 0){
        //                         $total_team_bonus = ($get_downline_monthly_pv[$get_downline->code]) * $pv_percentage_balance / 100;

        //                         if($total_team_bonus > 0){
        //                             $input_team = [];
        //                             $input_team['type'] = '4';
        //                             $input_team['user_id'] = $merchant->code;
        //                             $input_team['user_tier'] = $get_own_current_tier->amount;
        //                             $input_team['user_by'] = $get_downline->code;
        //                             $input_team['user_by_tier'] = $get_downline_current_tier->amount;
        //                             $input_team['product_amount'] = ($get_downline_monthly_pv[$get_downline->code]);
        //                             $input_team['comm_pa_type'] = "Percentage";
        //                             $input_team['comm_pa'] = $pv_percentage_balance;
        //                             $input_team['comm_amount'] = $total_team_bonus;
        //                             $input_team['comm_desc'] = "Team Reward";
        //                             $input_team['status'] = "1";

        //                             AffiliateCommission::create($input_team);
        //                         }
        //                     }
        //                 }else{
        //                     $total_team_bonus = ($get_downline_monthly_pv[$get_downline->code]) * $get_own_current_tier->amount / 100;

        //                     if($total_team_bonus > 0){
        //                         $input_team = [];
        //                         $input_team['type'] = '4';
        //                         $input_team['user_id'] = $merchant->code;
        //                         $input_team['user_tier'] = $get_own_current_tier->amount;
        //                         $input_team['user_by'] = $get_downline->code;
        //                         $input_team['user_by_tier'] = 0;
        //                         $input_team['product_amount'] = ($get_downline_monthly_pv[$get_downline->code]);
        //                         $input_team['comm_pa_type'] = "Percentage";
        //                         $input_team['comm_pa'] = $get_own_current_tier->amount;
        //                         $input_team['comm_amount'] = $total_team_bonus;
        //                         $input_team['comm_desc'] = "Team Reward";
        //                         $input_team['status'] = "1";

        //                         AffiliateCommission::create($input_team);
        //                     }
        //                 }
        //             }

        //             if($get_own_current_tier->amount == $highest_team_bonus->amount){
        //                 $affs = Affiliate::select('affiliates.*')
        //                                  ->join('merchants as m', 'm.code', 'affiliates.affiliate_id')
        //                                  ->where('sort_level', '<=', '3')
        //                                  ->where('m.status', '1')
        //                                  ->where('user_id', $merchant->code)
        //                                  ->orderBy('sort_level', 'asc')
        //                                  ->get();

        //                 foreach($affs as $aff){
                            
        //                     $get_aff_downline_total_pv = $this->TotalEarnPV($aff->affiliate_id);
        //                     $get_aff_current_tier = SettingTeamDividend::where('target_box', '<=', $get_aff_downline_total_pv)
        //                                                       ->orderBy('target_box', 'desc')
        //                                                       ->first();

        //                     $get_layer_monthly_pv = $this->MonthlyOwnPVWallet($aff->affiliate_id, $lastMonth);
        //                     if(!empty($get_aff_current_tier->amount)){
        //                         if($get_aff_current_tier->amount == $get_own_current_tier->amount){
        //                             $SettingMerchantSameLevel = SettingMerchantSameLevel::where('level', $aff->sort_level)
        //                                                                                 ->first();

        //                             $getNonHighTotalSalesPV = $this->getNonHighTotalSalesPV($aff->affiliate_id, $lastMonth);

        //                             // echo $aff->sort_level." Downline - ".$aff->affiliate_id." - ".$getNonHighTotalSalesPV." - Upline: ".$merchant->code." | ";
        //                             if($getNonHighTotalSalesPV > 0){
        //                                 if(!empty($SettingMerchantSameLevel->id)){
        //                                     if($SettingMerchantSameLevel->comm_type == 'Percentage'){
        //                                         $comm_amount = $getNonHighTotalSalesPV * $SettingMerchantSameLevel->comm_amount / 100;
        //                                     }else{
        //                                         $comm_amount = $SettingMerchantSameLevel->comm_amount;
        //                                     }

        //                                     if($comm_amount > 0){
        //                                         $input_same = [];
        //                                         $input_same['type'] = '5';
        //                                         $input_same['user_id'] = $merchant->code;
        //                                         $input_same['user_tier'] = $get_own_current_tier->amount;
        //                                         $input_same['user_by'] = $aff->affiliate_id;
        //                                         $input_same['user_by_tier'] = $get_aff_current_tier->amount;
        //                                         $input_same['product_amount'] = $getNonHighTotalSalesPV;
        //                                         $input_same['comm_pa_type'] = $SettingMerchantSameLevel->comm_type;
        //                                         $input_same['comm_pa'] = $SettingMerchantSameLevel->comm_amount;
        //                                         $input_same['comm_amount'] = $comm_amount;
        //                                         $input_same['comm_desc'] = "Same Tier Bonus (".$highest_team_bonus->amount."%)";
        //                                         $input_same['status'] = "1";

        //                                         AffiliateCommission::create($input_same);
        //                                     }
        //                                 }                                    
        //                             }
        //                         }
        //                     }
        //                 }
        //             }
        //         }
        //     }else{
                
        //     }
        // }
    }

    public function MonthlyTotalPVWallet($buyerCode, $lastMonth)
    {
        $transactions = Transaction::select(DB::raw('SUM(d.get_pv * d.quantity) as totalGetPV'))
                                   ->join('transaction_details as d', 'd.transaction_id', 'transactions.id')
                                   ->where('user_id', $buyerCode)
                                   ->where('transactions.status', '1')
                                   ->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m")'), $lastMonth)
                                   ->first();

        $affs = Affiliate::select(DB::raw('SUM(d.get_pv * d.quantity) as totalGetPV'))
                         ->join('merchants as m', 'm.code', 'affiliates.affiliate_id')
                         ->join('transactions as t', 't.user_id', 'm.code')
                         ->join('transaction_details as d', 'd.transaction_id', 't.id')
                         ->where('t.status', '1')
                         ->where('affiliates.user_id', $buyerCode)
                         ->where(DB::raw('DATE_FORMAT(t.created_at, "%Y-%m")'), $lastMonth)
                         ->first();
        

        $downline_total_pv = $affs->totalGetPV;

        $affs_customers = Affiliate::select(DB::raw('SUM(d.get_pv * d.quantity) as totalGetPV'))
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

        $mb_transactions = Transaction::select(DB::raw('SUM(d.get_pv * d.quantity) as totalGetPV'))
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
        $transactions = Transaction::select(DB::raw('SUM(d.get_pv * d.quantity) as totalGetPV'))
                                   ->join('transaction_details as d', 'd.transaction_id', 'transactions.id')
                                   ->where('user_id', $buyerCode)
                                   ->where('transactions.status', '1')
                                   ->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m")'), $lastMonth)
                                   ->first();

        $member_total_pv = 0;

        $users = User::where('master_id', $buyerCode)->where('status', '1')->get();

        foreach($users as $user){
            $mb_transactions = Transaction::select(DB::raw('SUM(d.get_pv * d.quantity) as totalGetPV'))
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
        $transactions = Transaction::select(DB::raw('SUM(d.get_pv * d.quantity) as totalGetPV'))
                                   ->join('transaction_details as d', 'd.transaction_id', 'transactions.id')
                                   ->where('user_id', $buyerCode)
                                   ->where('transactions.status', '1')
                                   
                                   ->first();

        $affs = Affiliate::select(DB::raw('SUM(d.get_pv * d.quantity) as totalGetPV'))
                         ->join('merchants as m', 'm.code', 'affiliates.affiliate_id')
                         ->join('transactions as t', 't.user_id', 'affiliates.affiliate_id')
                         ->join('transaction_details as d', 'd.transaction_id', 't.id')
                         ->where('t.status', '1')
                         ->where('affiliates.user_id', $buyerCode)
                         
                         ->first();
        

        $downline_total_pv = $affs->totalGetPV;

        $affs_customers = Affiliate::select(DB::raw('SUM(d.get_pv * d.quantity) as totalGetPV'))
                                   ->join('merchants as m', 'm.code', 'affiliates.affiliate_id')
                                   ->join('users as u', 'u.master_id', 'm.code')
                                   ->join('transactions as t', 't.user_id', 'u.code')
                                   ->join('transaction_details as d', 'd.transaction_id', 't.id')
                                   ->where('t.status', '1')
                                   ->where('affiliates.user_id', $buyerCode)
                                   
                                   ->first();
        $downline_customer_total_pv = $affs_customers->totalGetPV;
        // exit();

        $mb_transactions = Transaction::select(DB::raw('SUM(d.get_pv * d.quantity) as totalGetPV'))
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
                if($highest_team_bonus->amount != $get_aff_current_tier->amount){
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
}
