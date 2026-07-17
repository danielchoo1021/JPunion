<?php

namespace App\Exports;

use App\Transaction;
use App\TopupTransaction;
use App\AdjustTopupWallet;
use App\Agent;
use App\User;
use App\AdjustCashToTopup;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Auth, DB;

class TopupWalletExport extends \PhpOffice\PhpSpreadsheet\Cell\StringValueBinder implements WithCustomValueBinder, FromView
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

        $topupWallets = $agent->unionAll($member);

        // if (!empty($this->user_type)) {
        //     if ($this->user_type == '1') {
        //         $topupWallets = $topupWallets->where(DB::raw('LEFT(code, 1)'), 'A');
        //     } elseif ($this->user_type == '2') {
        //         $topupWallets = $topupWallets->where(DB::raw('LEFT(code, 2)'), 'Mb');
        //     }
        // }

        $total_wallet_in = [];
        $total_wallet_out = [];
        $previous_balance = [];

        $topupWallets = $topupWallets->get();

        foreach ($topupWallets as $topupWallet) {
            $total_wallet_in[$topupWallet->userCode] = TopupTransaction::where('user_id', $topupWallet->userCode)
                ->whereBetween(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), [$this->start, $this->end])
                ->where('status', '1')
                ->sum('amount')
                + AdjustTopupWallet::where('user_id', $topupWallet->userCode)
                ->whereBetween(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), [$this->start, $this->end])
                ->where('type', '1')
                ->where('status', '1')
                ->sum('amount');
            +AdjustCashToTopup::where('user_id', $topupWallet->userCode)
                ->whereBetween(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), array($this->start, $this->end))
                ->sum('amount');

            $total_wallet_out[$topupWallet->userCode] = Transaction::where('user_id', $topupWallet->userCode)
                ->whereBetween(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), [$this->start, $this->end])
                ->where('status', '1')
                ->where('mall', '2')
                ->sum('grand_total')
                + AdjustTopupWallet::where('user_id', $topupWallet->userCode)
                ->whereBetween(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), [$this->start, $this->end])
                ->where('type', '2')
                ->where('status', '1')
                ->sum('amount');

            $previous_balance[$topupWallet->userCode] = $this->getPreviousBalance($topupWallet->userCode, $this->start);
            $current_balance[$topupWallet->userCode] = $this->get_current_topup_balance($topupWallet->userCode, $this->end);
        }

        return view('backend.reports.download_topup_wallet_report', [
            'topupWallets' => $topupWallets,
            'start' => $this->start,
            'end' => $this->end,
            'total_wallet_in' => $total_wallet_in,
            'total_wallet_out' => $total_wallet_out,
            'previous_balance' => $previous_balance,
            'current_balance' => $current_balance
        ]);
    }

    public function getPreviousBalance($userCode, $startDate)
    {
        $topupTransactions = TopupTransaction::where('user_id', $userCode)
            ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), '<', $startDate)
            ->where('status', 1)
            ->sum('amount');

        $transactions = Transaction::where('user_id', $userCode)
            ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), '<', $startDate)
            ->where('status', 1)
            ->where('mall', 2)
            ->sum('grand_total');

        $adjustTopupWallets = AdjustTopupWallet::where('user_id', $userCode)
            ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), '<', $startDate)
            ->selectRaw('SUM(CASE WHEN type = 1 THEN amount ELSE 0 END) as wallet_in')
            ->selectRaw('SUM(CASE WHEN type = 2 THEN amount ELSE 0 END) as wallet_out')
            ->first();

        $transfer_cash_to_topup = AdjustCashToTopup::where('user_id', $userCode)
            ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), '<', $startDate)
            ->sum('amount');

        $previousBalance = ($topupTransactions + $adjustTopupWallets->wallet_in - $transactions - $adjustTopupWallets->wallet_out + $transfer_cash_to_topup);

        return $previousBalance;
    }

    public function get_current_topup_balance($userCode, $endDate)
    {
        $topupTransactions = TopupTransaction::where('user_id', $userCode)
            ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), '<=', $endDate)
            ->where('status', '1')
            ->sum('amount');

        $transactions = Transaction::where('user_id', $userCode)
            ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), '<=', $endDate)
            ->where('status', '1')
            ->where('mall', '2')
            ->sum('grand_total');

        $adjustTopupWallets = AdjustTopupWallet::where('user_id', $userCode)
            ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), '<=', $endDate)
            ->where('status', '1')
            ->selectRaw('SUM(CASE WHEN type = 1 THEN amount ELSE 0 END) as wallet_in')
            ->selectRaw('SUM(CASE WHEN type = 2 THEN amount ELSE 0 END) as wallet_out')
            ->first();

        $transfer_cash_to_topup = AdjustCashToTopup::where('user_id', $userCode)
            ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), '<=', $endDate)
            ->sum('amount');

        $currentBalance = ($topupTransactions + $adjustTopupWallets->wallet_in - $transactions - $adjustTopupWallets->wallet_out + $transfer_cash_to_topup);

        return $currentBalance;
    }
}
