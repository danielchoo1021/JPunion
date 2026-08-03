<?php

namespace App\Exports;

use App\Transaction;
use App\Http\Controllers\GlobalController;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CustomerReferralBonusReportExport implements FromView
{
    protected $agent_code, $agent_name, $customer_code, $customer_name, $claimed;

	function __construct($agent_code, $agent_name, $customer_code, $customer_name, $claimed) {
	    $this->agent_code = $agent_code;
        $this->agent_name = $agent_name;
        $this->customer_code = $customer_code;
	    $this->customer_name = $customer_name;
        $this->claimed = $claimed;
	}

    public function view(): View
    {
        $progress = GlobalController::customer_referral_bonus_progress_query(
            $this->agent_code, $this->agent_name, $this->customer_code, $this->customer_name, $this->claimed
        )->get();

        foreach($progress as $row){
            $row->paid_order_count = Transaction::where('user_id', $row->customer_code)->where('status', '1')->count();
        }

        return view('backend.reports.download_customer_referral_bonus_report', compact('progress'));
    }
}
