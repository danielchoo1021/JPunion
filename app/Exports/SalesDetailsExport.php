<?php

namespace App\Exports;

use App\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Auth, DB;

class SalesDetailsExport implements FromView, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $start, $end, $checkYear, $checkMonth, $checkDay, $per_page, $product_id, $pricing_type;

	function __construct($start, $end, $item_code, $product_code, $checkYear, $checkMonth, $checkDay, $per_page, $product_id, $pricing_type) {
	    $this->start = $start;
        $this->end = $end;
        $this->item_code = $item_code;
	    $this->product_code = $product_code;
        $this->checkYear = $checkYear;
        $this->checkMonth = $checkMonth;
        $this->checkDay = $checkDay;
        $this->per_page = $per_page;
        $this->product_id = $product_id;
        $this->pricing_type = $pricing_type;
	}

    public function view(): View
    {
        $transactions = Transaction::select(DB::raw("
                                                    CASE
                                                        WHEN transactions.user_id LIKE 'AD%' THEN 'Agent'
                                                        WHEN transactions.user_id LIKE 'A%' THEN 'Agent'
                                                        WHEN transactions.user_id LIKE 'Mb%' THEN 'Member'
                                                        ELSE 'Guest'
                                                    END AS get_pricing_type
                                                "),
                                                'transactions.transaction_no',
                                                'd.item_code', 'd.product_code', 'd.unit_price', 'd.product_name', 'd.product_id', 'd.quantity', 'd.costing_price')
                                    ->leftJoin('agents AS m', 'm.code', 'transactions.user_id')
                                    ->leftJoin('users AS u', 'u.code', 'transactions.user_id')
                                    ->leftJoin('admins AS a', 'a.code', 'transactions.user_id')
                                    ->join('transaction_details AS d', 'd.transaction_id', 'transactions.id')
                                    ->where('transactions.status', '1')
                                    ->where(DB::raw('md5(d.product_id)'), $this->product_id);
        if(empty(request('this_year'))){
        $transactions = $transactions->whereBetween(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), array($this->start, $this->end));
        }
        if(Auth::guard('merchant')->check()){
        $transactions = $transactions->where('d.merchant_id', Auth::user()->code);
        }
        $transactions = $transactions->having('get_pricing_type', '=', $this->pricing_type)
                                     ->orderBy('d.item_code')
                                     ->orderBy('d.product_name')
                                     ->orderByDesc('transactions.created_at');
        
        if(!empty($this->item_code)){
            $transactions = $transactions->where('d.item_code', $this->item_code);
        }

        if(!empty($this->product_code)){
            $transactions = $transactions->where('d.product_code', $this->product_code);
        }

        if(!empty($this->checkYear)){
            $transactions = $transactions->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y")'), $this->checkYear);
        }
        if(!empty($this->checkMonth)){
            $transactions = $transactions->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m")'), $this->checkYear.'-'.$this->checkMonth);
        }
        if(!empty($this->checkDay)){
            $transactions = $transactions->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), $this->checkYear.'-'.$this->checkMonth.'-'.$this->checkDay);
        }
                       
        $transactions = $transactions->paginate($this->per_page);

        return view('backend.reports.download_sales_report_details', ['transactions'=>$transactions, 'start'=>$this->start, 'end'=>$this->end]);
    }
}
