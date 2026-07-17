<?php

namespace App\Exports;

use App\Transaction;
use App\TransactionDetail;
use App\WithdrawalTransaction;
use App\AffiliateCommission;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Auth, DB;

class TransactionExport extends \PhpOffice\PhpSpreadsheet\Cell\StringValueBinder implements WithCustomValueBinder, FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */

    protected $start, $end, $buyer_name, $buyer_code, $transaction_no, $status, $delivery_type, $payment , $mall;

    function __construct($start, $end, $buyer_name, $buyer_code, $transaction_no, $status, $delivery_type, $payment , $mall)
    {
        $this->start = $start;
        $this->end = $end;
        $this->buyer_name = $buyer_name;
        $this->buyer_code = $buyer_code;
        $this->transaction_no = $transaction_no;
        $this->status = $status;
        $this->delivery_type = $delivery_type;
        $this->payment = $payment;
        $this->mall = $mall;
    }


    public function view(): View
    {
        $transactions = Transaction::select(
            'transactions.*',
            DB::raw('COALESCE(COALESCE(ag.f_name, u.f_name), a.f_name) as customer_name'),
            DB::raw('COALESCE(COALESCE(ag.code, u.code), a.code) as customer_code')
        )
            ->join('transaction_details as d', 'd.transaction_id', 'transactions.id')
            ->leftJoin('agents as ag', 'ag.code', 'transactions.user_id')
            ->leftJoin('users as u', 'u.code', 'transactions.user_id')
            ->leftJoin('admins as a', 'a.code', 'transactions.user_id')
            ->where('transactions.status', '!=', '55');

        if(!empty($this->mall)){
            $transactions = $transactions->whereNotNull('pv_purchase');
        }else{
            $transactions = $transactions->whereNull('pv_purchase');
        }


        if (Auth::guard('merchant')->check()) {
            $transactions = $transactions->where('merchant_id', Auth::user()->code);
        }

        $transactions = $transactions->whereBetween(DB::raw('DATE_FORMAT(transactions.created_at, "%Y-%m-%d")'), array($this->start, $this->end));

        $transactions = $transactions->groupBy('transactions.id')
            ->orderBy('transactions.created_at', 'desc');

        if (!empty($this->buyer_name)) {
            $transactions = $transactions->where(DB::raw('COALESCE(COALESCE(ag.f_name, u.f_name), a.f_name)'), 'like', "%" . $this->buyer_name . "%");
        }

        if (!empty($this->buyer_code)) {
            $transactions = $transactions->where(DB::raw('COALESCE(COALESCE(ag.code, u.code), a.code)'), 'like', "%" . $this->buyer_ic . "%");
        }

        if (!empty($this->transaction_no)) {
            $transactions = $transactions->where('transactions.transaction_no', 'like', "%" . $this->transaction_no . "%");
        }

        if (!empty($this->status)) {
            if ($this->status != '2') {
                if ($this->status == '3') {
                    $transactions = $transactions->where('transactions.completed', '1');
                } else {
                    $transactions = $transactions->where("transactions.status", 'like', "%" . $this->status . "%");
                }
            } else {
                $transactions = $transactions->where('to_receive', '1')
                    ->whereNull('completed');
            }
        }

        if (!empty($this->delivery_type)) {
            // if($this->delivery_type == '1'){
            //     $transactions = $transactions->where('cod_address', '1');
            // }else{
            //     $transactions = $transactions->where('cod_address', '0');
            // }
            if ($this->delivery_type == '1') {
                $transactions = $transactions->where(function ($query) {
                    $query->where(function ($q) {
                        $q->where('transactions.self_pick', 1)
                            ->whereNull('transactions.completed');
                    })->orWhere(function ($q) {
                        $q->whereNotNull('transactions.payment_method')
                            ->whereNull('transactions.completed');
                    });
                });
            } elseif ($this->delivery_type == '2') {
                $transactions = $transactions->where(function ($query) {
                    $query->where(function ($q) {
                        $q->where('transactions.self_pick', 1)
                            ->where('transactions.completed', 1);
                    })->orWhere(function ($q) {
                        $q->whereNotNull('transactions.payment_method')
                            ->where('transactions.completed', 1);
                    });
                });
            } elseif ($this->delivery_type == '3') {
                $transactions = $transactions->whereNull('transactions.self_pick')->whereNull('transactions.payment_method');
            }
        }

        if (!empty($this->payment)) {
            if ($this->payment == 1) {
                $transactions = $transactions->whereNull('transactions.mall')->whereNotNull('transactions.bank_slip');
            } elseif ($this->payment == 2) {
                $transactions = $transactions->where('transactions.mall', 1);
            } elseif ($this->payment == 3) {
                $transactions = $transactions->where('transactions.mall', 2);
            } elseif ($this->payment == 4) {
                $transactions = $transactions->whereNull('transactions.mall')->whereNull('transactions.bank_slip')->whereNull('transactions.payment_method');
            } elseif ($this->payment == 5) {
                $transactions = $transactions->whereNull('transactions.mall')->whereNull('transactions.bank_slip')->where('transactions.payment_method', 1);
            } elseif ($this->payment == 6) {
                $transactions = $transactions->whereNull('transactions.mall')->whereNull('transactions.bank_slip')->where('transactions.payment_method', 2);
            } elseif ($this->payment == 7) {
                $transactions = $transactions->whereNull('transactions.mall')->whereNull('transactions.bank_slip')->where('transactions.payment_method', 3);
            }elseif ($this->payment == 8) {
               $transactions = $transactions->whereNull('transactions.mall')->whereNotNull('transactions.bank_slip')->where('transactions.created_backend',1);
            }elseif ($this->payment == 9) {
               $transactions = $transactions->whereNull('transactions.mall')->whereNull('transactions.bank_slip')->whereNull('transactions.payment_method')->where('transactions.created_backend',1);
            }
        }

        $transactions = $transactions->get();


        return view('backend.transactions.download_transaction_list', [
            'transactions' => $transactions,
            'start' => $this->start,
            'end' => $this->end,
            'buyer_name' => $this->buyer_name,
            'buyer_code' => $this->buyer_code,
            'transaction_no' => $this->transaction_no,
            'status' => $this->status,
            'delivery_type' => $this->delivery_type
        ]);
    }

    public function GetWalletBalance($user)
    {
        $balance = AffiliateCommission::select(DB::raw('SUM(comm_amount) as totalBalance'))
            ->where('user_id', $user)
            ->where('status', '1')
            ->first();

        $withdrawal = WithdrawalTransaction::select(DB::raw('SUM(amount) as totalWithdrawal'))
            ->where('user_id', $user)
            ->where('status', '1')
            ->first();
        $totalBalance = 0;

        $totalBalance = $balance->totalBalance - $withdrawal->totalWithdrawal;


        return $totalBalance;
    }

    public function previousGetWalletBalance($user, $created)
    {
        $balance = AffiliateCommission::select(DB::raw('SUM(comm_amount) as totalBalance'))
            ->where('user_id', $user)
            ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d %H:%i:%s")'), '<', $created)
            ->where('status', '1')
            ->first();

        $withdrawal = WithdrawalTransaction::select(DB::raw('SUM(amount) as totalWithdrawal'))
            ->where('user_id', $user)
            ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d %H:%i:%s")'), '<', $created)
            ->where('status', '1')
            ->first();
        $totalBalance = 0;

        $totalBalance = $balance->totalBalance - $withdrawal->totalWithdrawal;


        return $totalBalance;
    }
}
