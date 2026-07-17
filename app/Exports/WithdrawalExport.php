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
use Auth, DB;

class WithdrawalExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $start, $end, $withdrawal_no, $agent_name, $status;

	 function __construct($start, $end, $withdrawal_no, $agent_name, $status) {
          $this->start = $start;
          $this->end = $end;
          $this->withdrawal_no = $withdrawal_no;
          $this->agent_name = $agent_name;
          $this->status = $status;
	 }


    public function view(): View
    {
    	 $transactions = WithdrawalTransaction::select(DB::raw('coalesce(CONCAT(m.f_name, " ", m.l_name), CONCAT(a.f_name, " ", a.l_name)) AS agent_name'), 'withdrawal_transactions.*')
                                             ->leftJoin('agents AS m', 'm.code', 'withdrawal_transactions.user_id')
                                             ->leftJoin('admins AS a', 'a.code', 'withdrawal_transactions.user_id')
                                             ->whereBetween(DB::raw('DATE_FORMAT(withdrawal_transactions.created_at, "%Y-%m-%d")'), array($this->start, $this->end))
                                             ->orderBy('withdrawal_transactions.id', 'desc');

        if(!empty($this->withdrawal_no)){
            $transactions = $transactions->where('withdrawal_transactions.withdrawal_no', 'like', $this->withdrawal_no);
        }
        if(!empty($this->agent_name)){
            $transactions = $transactions->where(DB::raw('CONCAT(m.f_name, " ", m.l_name)'), 'like', "%".$this->agent_name."%");
        }
        if(!empty($this->status)){
            $transactions = $transactions->where('withdrawal_transactions.status', $this->status);
        }

        $transactions = $transactions->get();

        $GetWalletBalance = [];
        foreach($transactions as $transaction){
          $GetWalletBalance[$transaction->withdrawal_no] = $this->previousGetWalletBalance($transaction->user_id, $transaction->created_at);
        }

        return view('backend.transactions.download_withdrawal_list', ['transactions'=>$transactions, 
                                                             'start'=>$this->start, 'end'=>$this->end],
                                                            compact('GetWalletBalance'));

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
