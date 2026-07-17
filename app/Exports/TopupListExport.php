<?php

namespace App\Exports;

use App\TopupTransaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Auth, DB;

class TopupListExport extends \PhpOffice\PhpSpreadsheet\Cell\StringValueBinder implements WithCustomValueBinder, FromView
{
    protected $start, $end, $topup_no, $agent_name, $status;

    function __construct($start, $end, $topup_no, $agent_name, $status)
    {
        $this->start = $start;
        $this->end = $end;
        $this->topup_no = $topup_no;
        $this->agent_name = $agent_name;
        $this->status = $status;
    }

    public function view(): View
    {
        $topups = TopupTransaction::query();
        if (!empty($this->start) && !empty($this->end)) {
            $topups = $topups->whereBetween('created_at', [$this->start, $this->end]);
        }
        if (!empty($this->topup_no)) {
            $topups = $topups->where('topup_no', 'like', '%' . $this->topup_no . '%');
        }
        if (!empty($this->agent_name)) {
            $topups = $topups->where('agent_name', 'like', '%' . $this->agent_name . '%');
        }
        if (!empty($this->status)) {
            $topups = $topups->where('status', $this->status);
        }
        $topups = $topups->get();
        return view('backend.transactions.download_topup_list', [
            'topups' => $topups
        ]);
    }
}
