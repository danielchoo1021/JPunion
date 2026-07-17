<?php

namespace App\Exports;

use App\Transaction;
use App\AdjustCashWallet;
use App\AffiliateCommission;
use App\WithdrawalTransaction;
use App\Agent;
use App\User;
use App\AdjustCashToTopup;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Auth, DB;

class CashWalletExport extends \PhpOffice\PhpSpreadsheet\Cell\StringValueBinder implements WithCustomValueBinder, FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */

    protected $start, $end, $user_name, $user_code, $user_type;

    function __construct($start, $end, $user_name, $user_code, $user_type)
    {
        $this->start = $start;
        $this->end = $end;
        $this->user_name = $user_name;
        $this->user_code = $user_code;
        $this->user_type = $user_type;
        $this->user_type = $user_type;
    }

    public function view(): View
    {
        $agent = Agent::select(
            DB::raw('CONVERT(CONCAT(agents.f_name, " ", agents.l_name) USING utf8mb4) COLLATE utf8mb4_unicode_ci AS userName'),
            DB::raw('CONVERT(agents.code USING utf8mb4) COLLATE utf8mb4_unicode_ci AS userCode'),
            DB::raw('"Agent" AS user_type'),
            DB::raw('agents.status AS status')
        )
            ->whereNotIn('agents.status', ['99', '3'])
            ->orderBy('agents.code', 'asc');

        $member = User::select(
            DB::raw('CONVERT(CONCAT(users.f_name, " ", users.l_name) USING utf8mb4) COLLATE utf8mb4_unicode_ci AS userName'),
            DB::raw('CONVERT(users.code USING utf8mb4) COLLATE utf8mb4_unicode_ci AS userCode'),
            DB::raw('"Member" AS user_type'),
            DB::raw('users.status AS status')
        )
            ->whereNotIn('users.status', ['99', '3'])
            ->orderBy('users.code', 'asc');

        if (!empty($this->user_name)) {
            $agent = $agent->whereRaw("
                CONVERT(CONCAT(agents.f_name, ' ', agents.l_name) USING utf8mb4) COLLATE utf8mb4_unicode_ci LIKE ?
            ", ['%' . $this->user_name . '%']);

            $member = $member->whereRaw("
                CONVERT(CONCAT(users.f_name, ' ', users.l_name) USING utf8mb4) COLLATE utf8mb4_unicode_ci LIKE ?
            ", ['%' . $this->user_name . '%']);
        }

        if (!empty($this->user_code)) {
            $agent = $agent->where('agents.code', 'like', "%" . $this->user_code . "%");
            $member = $member->where('users.code', 'like', "%" . $this->user_code . "%");
        }

        if (!empty($this->user_type)) {
            if ($this->user_type == '1') {
                $member = $member->where(DB::raw('LEFT(users.code, 1)'), 'A');
            } elseif ($this->user_type == '2') {
                $agent = $agent->where(DB::raw('LEFT(agents.code, 2)'), 'Mb');
            }
        }

        $Users = $agent->unionAll($member);



        $total_cash_in = [];
        $total_cash_out = [];
        $previous_balance = [];

        $Users = $Users->get();

        foreach ($Users as $User) {
            $total_cash_in[$User->userCode] = AffiliateCommission::where('user_id', $User->userCode)
                ->whereBetween(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), [$this->start, $this->end])
                ->where('status', '1')
                ->sum('comm_amount') +
                AdjustCashWallet::where('user_id', $User->userCode)
                ->whereBetween(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), [$this->start, $this->end])
                ->where('type', '1')
                ->where('status', '1')
                ->sum('amount');

            $total_cash_out[$User->userCode] = Transaction::where('user_id', $User->userCode)
                ->whereBetween(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), [$this->start, $this->end])
                ->where('status', '1')
                ->where('mall', '1')
                ->sum('grand_total') +
                WithdrawalTransaction::where('user_id', $User->userCode)
                ->whereBetween(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), [$this->start, $this->end])
                ->whereIn('status', ['1', '99'])
                ->sum('amount') +
                AdjustCashWallet::where('user_id', $User->userCode)
                ->whereBetween(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), [$this->start, $this->end])
                ->where('type', '2')
                ->where('status', '1')
                ->sum('amount');
            AdjustCashToTopup::where('user_by', $User->userCode)
                ->whereBetween(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), [$this->start, $this->end])
                ->sum('amount');

            $previous_balance[$User->userCode] = $this->get_previous_cash_balance($User->userCode, $this->start);
            $current_balance[$User->userCode] = $this->get_current_cash_balance($User->userCode, $this->end);
        }

        return view('backend.reports.download_cash_wallet_report', [
            'Users' => $Users,
            'start' => $this->start,
            'end' => $this->end,
            'total_cash_in' => $total_cash_in,
            'total_cash_out' => $total_cash_out,
            'previous_balance' => $previous_balance,
            'current_balance' => $current_balance
        ]);
    }

    public function get_previous_cash_balance($userCode, $startDate)
    {
        $transaction = Transaction::where('user_id', $userCode)
            ->where('created_at', '<', $startDate)
            ->where('status', '1')
            ->where('mall', '1')
            ->sum('grand_total');

        $withdraw_transaction = WithdrawalTransaction::where('user_id', $userCode)
            ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), '<', $startDate)
            ->whereIn('status', ['1', '99'])
            ->sum('amount');

        $adjustCashWallet = AdjustCashWallet::where('user_id', $userCode)
            ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), '<', $startDate)
            ->selectRaw('SUM(CASE WHEN type = 1 THEN amount ELSE 0 END) as cash_in')
            ->selectRaw('SUM(CASE WHEN type = 2 THEN amount ELSE 0 END) as cash_out')
            ->first();

        $affiliateCommission = AffiliateCommission::where('user_id', $userCode)
            //   ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), '<', $startDate)
            ->where('created_at', '<', $startDate)
            ->where('status', '1')
            ->sum('comm_amount');

        $transfer_cash_to_topup = AdjustCashToTopup::where('user_by', $userCode)
            ->where('created_at', '<', $startDate)
            ->sum('amount');

        $previousBalance = ($affiliateCommission + $adjustCashWallet->cash_in - $adjustCashWallet->cash_out - $transaction - $withdraw_transaction - $transfer_cash_to_topup);

        return $previousBalance;
    }

    public function get_current_cash_balance($userCode, $endDate)
    {
        $transaction = Transaction::where('user_id', $userCode)
            ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), '<=', $endDate)
            ->where('status', '1')
            ->where('mall', '1')
            ->sum('grand_total');

        $withdraw_transaction = WithdrawalTransaction::where('user_id', $userCode)
            ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), '<=', $endDate)
            ->whereIn('status', ['1', '99'])
            ->sum('amount');

        $adjustCashWallet = AdjustCashWallet::where('user_id', $userCode)
            ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), '<=', $endDate)
            ->selectRaw('SUM(CASE WHEN type = 1 THEN amount ELSE 0 END) as cash_in')
            ->selectRaw('SUM(CASE WHEN type = 2 THEN amount ELSE 0 END) as cash_out')
            ->first();

        $affiliateCommission = AffiliateCommission::where('user_id', $userCode)
            ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), '<=', $endDate)
            ->where('status', '1')
            ->sum('comm_amount');

        $transfer_cash_to_topup = AdjustCashToTopup::where('user_by', $userCode)
            ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), '<=', $endDate)
            ->sum('amount');

        $currentBalance = ($affiliateCommission + $adjustCashWallet->cash_in - $adjustCashWallet->cash_out - $transaction - $withdraw_transaction - $transfer_cash_to_topup);

        return $currentBalance;
    }
}
