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

class AgentListExport implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */

    protected $start, $end, $agent_name, $code, $referrer_code, $referrer_name, $phone, $status, $email;

    function __construct($start, $end, $agent_name, $code, $referrer_code, $referrer_name, $phone, $status, $email)
    {
        $this->start = $start;
        $this->end = $end;
        $this->agent_name = $agent_name;
        $this->code = $code;
        $this->referrer_code = $referrer_code;
        $this->referrer_name = $referrer_name;
        $this->phone = $phone;
        $this->status = $status;
        $this->email = $email;
    }

    public function view(): View
    {

        $agents = Agent::select('agents.*')
            ->leftJoin('agents as upm', 'upm.code', 'agents.master_id')
            ->leftJoin('admins as upa', 'upa.code', 'agents.master_id')
            ->whereNotIn('agents.status', ['99', '3']);

        if (!empty($this->start) && !empty($this->end)) {
            $agents->whereDate('agents.created_at', '>=', $this->start)
                   ->whereDate('agents.created_at', '<=', $this->end);
        }

        if (!empty($this->agent_name)) {
            $agents = $agents->where(DB::raw('CONCAT(agents.f_name, " ", agents.l_name)'), 'like', "%".$this->agent_name."%");
        }

        if (!empty($this->code)) {
            $agents = $agents->where(DB::raw('CONCAT(agents.display_code, agents.display_running_no)'), 'like', "%".$this->code."%");
        }

        if (!empty($this->referrer_code)) {
            $agents = $agents->where(DB::raw('COALESCE(CONCAT(upm.display_code, upm.display_running_no), CONCAT(upa.display_code, upa.display_running_no))'), 'like', "%" . $this->referrer_code . "%");
        }

        if (!empty($this->referrer_name)) {
            $agents = $agents->where(
                DB::raw('COALESCE(CONCAT(upm.f_name, " ", upm.l_name), CONCAT(upa.f_name, " ", upa.l_name)) COLLATE utf8mb4_general_ci'),
                'like',
                '%' . $this->referrer_name . '%'
            );
        }

        if (!empty($this->phone)) {
            $phone = $this->phone;

            $agents = $agents->where(function($query) use ($phone) {
                $query->where('agents.phone', 'like', "%$phone%")
                    ->orWhere(DB::raw("CONCAT('0', agents.phone)"), 'like', "%$phone%")
                    ->orWhere(DB::raw("CONCAT('60', agents.phone)"), 'like', "%$phone%");
            });
        }


        if (!empty($this->status)) {
            $agents = $agents->where('agents.status', 'like', "%".$this->status."%");
        }

        if (!empty($this->email)) {
            $agents = $agents->where('agents.email', 'like', "%" . $this->email . "%");
        }

        $agents = $agents->get();

        return view('backend.reports.download_agent_list', ['agents' => $agents, 'start' => $this->start, 'end' => $this->end]);
    }
}
