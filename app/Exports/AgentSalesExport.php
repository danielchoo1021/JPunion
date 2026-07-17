<?php

namespace App\Exports;

use App\Transaction;
use App\AffiliateCommission;
use App\Agent;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Auth, DB;

class AgentSalesExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $start, $end, $agent, $code, $referrer_code, $referrer_name;

	function __construct($start, $end, $agent, $code, $referrer_code, $referrer_name) {
	    $this->start = $start;
        $this->end = $end;
        $this->agent = $agent;
	    $this->code = $code;
        $this->referrer_code = $referrer_code;
	    $this->referrer_name = $referrer_name;
	}

    public function view(): View
    {

        $merchants = Agent::select('l.agent_lvl AS l_agent_lvl', 'l.agent_lvl_cn AS l_agent_lvl_cn', 'agents.*',
                                DB::raw('COALESCE(COALESCE(CONCAT(upm.display_code, upm.display_running_no), upa.code), upu.code) AS upline_code'),
                                DB::raw('COALESCE(COALESCE(CONCAT(upm.f_name, " ", upm.l_name), CONCAT(upa.f_name, " ", upa.l_name)), upu.f_name) AS upline_name'))
                        ->leftJoin('agent_levels AS l', 'l.id', 'agents.lvl')
                        ->leftJoin('agents as upm', 'upm.code', 'agents.master_id')
                        ->leftJoin('admins as upa', 'upa.code', 'agents.master_id')
                        ->leftJoin('users as upu', 'upu.code', 'agents.master_id')
                        ->where('agents.status','1')
                        ->whereNotNull('agents.verify_status')
                        // ->whereBetween(DB::raw('DATE_FORMAT(agents.created_at, "%Y-%m-%d")'), array($this->start, $this->end))
                        ->orderBy('agents.code', 'asc');

        if(!empty($this->agent)){
            $merchants = $merchants->where(DB::raw('CONCAT(agents.f_name, " ", agents.l_name)'), 'like', "%".$this->agent."%");
        }

        if(!empty($this->code)){
            $merchants = $merchants->where('agents.code','like',"%".$this->code."%");
        }

        if(!empty($this->referrer_code)){
              $merchants = $merchants->whereRaw("
                        COALESCE(COALESCE(CONCAT(upm.display_code, upm.display_running_no), upa.code), upu.code) LIKE ?
                    ", ['%' . $this->referrer_code . '%']);
        }

        if(!empty($this->referrer_name)){
             $merchants = $merchants->whereRaw("
                    CAST(
                        COALESCE(
                            COALESCE(CONCAT(upm.f_name, ' ', upm.l_name), CONCAT(upa.f_name, ' ', upa.l_name)),
                            upu.f_name
                        ) AS CHAR CHARACTER SET utf8mb4
                    ) COLLATE utf8mb4_unicode_ci LIKE ?
                ", ['%' . $this->referrer_name . '%']);
        }
                                     
        $merchants = $merchants->get();

        $total_sales = [];
        $personal_comm = [];
        $total_comm = [];

        foreach($merchants as $merchant){
         
                $total_sales[$merchant->code] = Transaction::select(DB::raw('SUM(grand_total - COALESCE(shipping_fee, 0)) AS totalSales'))
                                                    ->where('status', '1')
                                                    ->where('user_id',$merchant->code)
                                                    ->whereBetween(DB::raw('DATE_FORMAT(created_at,"%Y-%m-%d")'),array($this->start, $this->end))
                                                    ->whereNull('pv_purchase')
                                                    ->first();

                $personal_comm[$merchant->code] =  AffiliateCommission::select(DB::raw('SUM(comm_amount) AS totalCommission'))
                                                    ->where('status', '1')
                                                    ->where('user_id',$merchant->code)
                                                    ->whereBetween(DB::raw('DATE_FORMAT(created_at,"%Y-%m-%d")'),array($this->start, $this->end))
                                                    ->where('comm_desc', '=', 'Order Rebate Commission')
                                                    ->first();

                $total_comm[$merchant->code] =  AffiliateCommission::select(DB::raw('SUM(comm_amount) AS totalCommission'))
                                                    ->where('status', '1')
                                                    ->where('user_id',$merchant->code)
                                                    ->whereBetween(DB::raw('DATE_FORMAT(created_at,"%Y-%m-%d")'),array($this->start, $this->end))
                                                    ->where('comm_desc', '!=', 'Order Rebate Commission')
                                                    ->first();
        }


        return view('backend.reports.download_agent_sales_report', ['merchants'=>$merchants, 'start'=>$this->start, 'end'=>$this->end],compact('total_sales','personal_comm','total_comm'));
    }
}
