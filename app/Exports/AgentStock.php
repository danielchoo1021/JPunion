<?php

namespace App\Exports;

use App\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Auth, DB;

class AgentStock implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $start, $end;

	function __construct($start, $end, $item_code, $buyer) {
	    $this->start = $start;
        $this->end = $end;
        $this->item_code = $item_code;
	    $this->buyer = $buyer;
	}

    public function view(): View
    {
        $transactions = Transaction::select(DB::raw('SUM(quantity) AS totalQty'), DB::raw('SUM(transactions.grand_total) AS totalGrand'), 
                                              DB::raw('SUM(transactions.shipping_fee) AS totalShippingFee'), 
                                              DB::raw('SUM(transactions.discount) AS totalDiscount'), 
                                              'd.item_code', 'd.product_code',
                                              DB::raw('CONCAT(m.f_name, " ", m.l_name) AS buyer_name'))
                                     ->join('merchants AS m', 'm.code', 'transactions.user_id')
                                     ->join('transaction_details AS d', 'd.transaction_id', 'transactions.id')
                                     ->where('transactions.status', '1')
                                     ->whereBetween(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), array($this->start, $this->end))
                                     ->groupBy('m.code')
                                     ->orderBy('transactions.created_at', 'desc');
        if(!empty($this->item_code)){
            $transactions = $transactions->where('d.item_code', $this->item_code);
        }

        if(!empty($this->buyer)){
            $transactions = $transactions->where(DB::raw('CONCAT(m.f_name, " ", m.l_name)'), 'like', '%'.$this->buyer.'%');
        }
                                     
        $transactions = $transactions->get();

        return view('backend.reports.download_agent_stock_report', ['transactions'=>$transactions, 'start'=>$this->start, 'end'=>$this->end]);
    }
}
