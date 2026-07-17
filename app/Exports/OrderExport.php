<?php

namespace App\Exports;

use App\Transaction;
use App\TransactionDetail;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Auth, DB;

use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
class OrderExport extends \PhpOffice\PhpSpreadsheet\Cell\StringValueBinder implements WithCustomValueBinder, FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $start, $end, $checkYear, $checkMonth, $checkDay,$transaction_no,$buyer,$item_code,$product_code;

	 function __construct($start, $end, $checkYear, $checkMonth, $checkDay,$transaction_no,$buyer,$item_code,$product_code) {
	        $this->start = $start;
            $this->end = $end;
            $this->checkYear = $checkYear;
            $this->checkMonth = $checkMonth;
            $this->checkDay = $checkDay;
            $this->transaction_no = $transaction_no;
            $this->buyer = $buyer;
            $this->item_code = $item_code;
            $this->product_code = $product_code;
	 }


    public function view(): View
    {   
        $start = $this->start;
        $end = $this->end;

        $transactions = Transaction::select(DB::raw('CASE 
                                                     WHEN m.id != "" THEN COALESCE(m.f_name, m.phone)
                                                     WHEN a.id != "" THEN COALESCE(a.f_name, a.phone)
                                                     WHEN u.id != "" THEN COALESCE(u.f_name, u.phone)
                                                 END AS buyer_name'),
                                                DB::raw('CASE 
                                                     WHEN m.id != "" THEN "Agent"
                                                     WHEN a.id != "" THEN "Admin"
                                                     WHEN u.id != "" THEN "Customer"
                                                 END AS buyer_role'),
                                                DB::raw('CASE 
                                                    WHEN m.id != "" THEN m.code
                                                    WHEN a.id != "" THEN a.code
                                                    WHEN u.id != "" THEN u.code
                                                END as buyer_code'),
                                        'transactions.transaction_no', 'transactions.status', 'transactions.created_at',
                                        'transactions.id AS Tid', 'transactions.grand_total', 'transactions.shipping_fee', 
                                        'transactions.processing_fee', 'transactions.discount', 'transactions.address_name',
                                        'transactions.ad_discount')
                               ->leftJoin('agents AS m', 'm.code', 'transactions.user_id')
                               ->leftJoin('admins AS a', 'a.code', 'transactions.user_id')
                               ->leftJoin('users AS u', 'u.code', 'transactions.user_id')
                               ->join('transaction_details as d', 'd.transaction_id', 'transactions.id')
                               ->where('transactions.status', '1')
                               ->whereNull('transactions.mall')
                               ->groupBy('transactions.id')
                                ->orderBy('transactions.created_at', 'desc');

        if(Auth::guard('merchant')->check()){
        $transactions = $transactions->where('d.merchant_id', Auth::user()->code);
        }

        if (empty($this->checkYear) && empty($this->checkMonth) && empty($this->checkDay)) {
            $transactions = $transactions->whereBetween(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), array($this->start, $this->end));
        }

        if(!empty($this->checkYear)){
            $transactions = $transactions->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y")'), $this->checkYear);

            $startYear = now()->startOfYear();
            $endYear = now()->endOfYear();

            $start = $startYear->format('Y-m-d');
            $end = $endYear->format('Y-m-d');
        }
        if(!empty($this->checkMonth)){
            $transactions = $transactions->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m")'), $this->checkMonth);
        }
        if(!empty($this->checkDay)){
            $transactions = $transactions->where(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), $this->checkDay);

            $startYear = now();
            $endYear = now();

            $start = $startYear->format('Y-m-d');
            $end = $endYear->format('Y-m-d');
        }

        if(!empty($this->transaction_no)){
            $transactions = $transactions->where('transactions.transaction_no', 'like', "%".$this->transaction_no."%");
        }


        if(!empty($this->buyer)){
            $term = '%' . $this->buyer . '%';

            $transactions = $transactions->whereRaw("
                CONVERT(CONCAT(m.f_name, ' ', m.l_name) USING utf8mb4) COLLATE utf8mb4_unicode_ci LIKE ?
            ", [$term])
            ->orWhereRaw("
                CONVERT(CONCAT(a.f_name, ' ', a.l_name) USING utf8mb4) COLLATE utf8mb4_unicode_ci LIKE ?
            ", [$term])
            ->orWhereRaw("
                CONVERT(CONCAT(u.f_name, ' ', u.l_name) USING utf8mb4) COLLATE utf8mb4_unicode_ci LIKE ?
            ", [$term]);
        }


        if(!empty($this->item_code)){
            $transactions = $transactions->where('d.item_code', 'like', "%".$this->item_code."%");
        }

        if(!empty($this->product_code)){
            $transactions = $transactions->where('d.product_code', 'like', "%".$this->product_code."%");
        }
        // $totalT = Transaction::select(DB::raw('SUM(d.quantity) as totalQty'),
        //                               DB::raw('SUM(d.unit_price) as totalUnitPrice'),
        //                               DB::raw('SUM(d.unit_price * d.quantity) as totalNet'),
        //                               DB::raw('SUM((d.unit_price * d.quantity) + transactions.processing_fee + transactions.shipping_fee + transactions.tax) as totalGrand'))
        //                            ->join('transaction_details AS d', 'd.transaction_id', 'transactions.id')
        //                            ->where('transactions.status', '1')
        //                            ->whereNull('transactions.mall')
        //                            ->whereBetween(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), array($this->start, $this->end))
        //                            ->orderBy('transactions.created_at', 'desc');

        // $totalT2 = Transaction::select(DB::raw('SUM(transactions.processing_fee) as totalProcessingFee'),
        //                               DB::raw('SUM(transactions.shipping_fee) as totalShippingFee'),
        //                               DB::raw('SUM(transactions.tax) as totalTax'),
        //                               DB::raw('SUM(transactions.discount) as totalDiscount'),
        //                               DB::raw('SUM(transactions.ad_discount) as totalAdDiscount'))
        //                       ->where('transactions.status', '1')
        //                       ->whereNull('transactions.mall')
        //                       ->whereBetween(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), array($this->start, $this->end))
        //                       ->orderBy('transactions.created_at', 'desc');

        $transactions = $transactions->get();
        // $totalT = $totalT->first();
        // $totalT2 = $totalT2->first();

        $details = [];
        $details2 = [];
        foreach($transactions as $transaction){
            $details[$transaction->Tid] = TransactionDetail::where("transaction_id", $transaction->Tid);
            if(Auth::guard('merchant')->check()){
                $details[$transaction->Tid] = $details[$transaction->Tid]->where('merchant_id', Auth::user()->code);
            }
            if(!empty($this->item_code)){
                $details[$transaction->Tid] = $details[$transaction->Tid]->where('item_code', 'like', "%".$this->item_code."%");
            }
            if(!empty($this->product_code)){
                $details[$transaction->Tid] = $details[$transaction->Tid]->where('product_code', 'like', "%".$this->product_code."%");
            }
            $details[$transaction->Tid] = $details[$transaction->Tid]->get();   

            $details2[$transaction->Tid] = TransactionDetail::select(DB::raw('SUM(unit_price*quantity) AS totalPrice'));

            if(Auth::guard('merchant')->check()){
                $details2[$transaction->Tid] = $details2[$transaction->Tid]->where('merchant_id', Auth::user()->code);
            }

            if(!empty($this->item_code)){
                $details2[$transaction->Tid] = $details2[$transaction->Tid]->where('item_code', 'like', "%".$this->item_code."%");
            }
            if(!empty($this->product_code)){
                $details2[$transaction->Tid] = $details2[$transaction->Tid]->where('product_code', 'like', "%".$this->product_code."%");
            }
            $details2[$transaction->Tid] = $details2[$transaction->Tid]->where("transaction_id", $transaction->Tid)->first();
        }

        return view('backend.reports.download_order_report', ['transactions'=>$transactions, 'start'=>$start, 'end'=>$end],
                                                            //   'totalT'=>$totalT, 'totalT2'=>$totalT2],
                                                              compact('details', 'details2'));

    }

    
}
