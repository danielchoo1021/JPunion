<?php

namespace App\Exports;

use App\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Auth, DB;

class SalesExport implements FromView, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */

    protected $start, $end, $item_code, $product_code, $checkYear, $checkMonth, $checkDay, $per_page;

    function __construct($start, $end, $item_code, $product_code, $checkYear, $checkMonth, $checkDay, $per_page)
    {
        $this->start = $start;
        $this->end = $end;
        $this->item_code = $item_code;
        $this->product_code = $product_code;
        $this->checkYear = $checkYear;
        $this->checkMonth = $checkMonth;
        $this->checkDay = $checkDay;
        $this->per_page = $per_page;
    }

    public function view(): View
    {
        $start = $this->start;
        $end = $this->end;

        $transactions = Transaction::select(
            DB::raw('SUM(quantity) AS totalQty'),
            DB::raw('SUM(d.costing_price) AS costing_price'),
            DB::raw('SUM(transactions.grand_total) AS totalGrand'),
            DB::raw('SUM(transactions.shipping_fee) AS totalShippingFee'),
            DB::raw('SUM(transactions.discount) AS totalDiscount'),
            DB::raw('SUM(d.unit_price * d.quantity) AS totalNet'),
            DB::raw("
                                                CASE
                                                    WHEN transactions.user_id LIKE 'AD%' THEN 'Agent'
                                                    WHEN transactions.user_id LIKE 'A%' THEN 'Agent'
                                                    WHEN transactions.user_id LIKE 'Mb%' THEN 'Member'
                                                    ELSE 'Guest'
                                                END AS get_pricing_type
                                            "),
            'd.item_code',
            'd.product_code',
            'd.unit_price',
            'd.product_name'
        )
            ->leftJoin('agents AS m', 'm.code', 'transactions.user_id')
            ->leftJoin('users AS u', 'u.code', 'transactions.user_id')
            ->leftJoin('admins AS a', 'a.code', 'transactions.user_id')
            ->join('transaction_details AS d', 'd.transaction_id', 'transactions.id')
            ->where('transactions.status', '1')
            ->whereNull('transactions.pv_purchase');

        if (empty($this->checkYear) && empty($this->checkMonth) && empty($this->checkDay)) {
            $transactions = $transactions->whereBetween(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), array($this->start, $this->end));
        }
        
        if (Auth::guard('merchant')->check()) {
            $transactions = $transactions->where('d.merchant_id', Auth::user()->code);
        }
        $transactions = $transactions->groupBy(DB::raw("
                                                        CASE
                                                            WHEN transactions.user_id LIKE 'AD%' OR transactions.user_id LIKE 'A%' THEN 'Agent'
                                                            WHEN transactions.user_id LIKE 'Mb%' THEN 'Member'
                                                            ELSE 'Guest'
                                                        END
                                                    "), 'd.item_code')
            ->orderBy('d.item_code')
            ->orderByRaw("
                                                    CASE
                                                        WHEN transactions.user_id LIKE 'AD%' OR transactions.user_id LIKE 'A%' THEN 3
                                                        WHEN transactions.user_id LIKE 'Mb%' THEN 2
                                                        ELSE 1
                                                    END
                                                ")
            ->orderBy('d.product_name')
            ->orderByDesc('transactions.created_at');

        if (!empty($this->item_code)) {
            $transactions = $transactions->where('d.item_code', $this->item_code);
        }

        if (!empty($this->product_code)) {
            $transactions = $transactions->where('d.product_code', $this->product_code);
        }

        if (!empty($this->checkYear)) {
            $transactions = $transactions->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y")'), $this->checkYear);

            $startYear = now()->startOfYear();
            $endYear = now()->endOfYear();

            $start = $startYear->format('Y-m-d');
            $end = $endYear->format('Y-m-d');
        }
        if (!empty($this->checkMonth)) {
            $transactions = $transactions->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m")'), $this->checkMonth);
        }
        if (!empty($this->checkDay)) {
            $transactions = $transactions->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), $this->checkDay);

            $startYear = now();
            $endYear = now();

            $start = $startYear->format('Y-m-d');
            $end = $endYear->format('Y-m-d');
        }

        $transactions = $transactions->paginate($this->per_page);

        return view('backend.reports.download_sales_report', ['transactions' => $transactions, 'start' => $start, 'end' => $end]);
    }
}
