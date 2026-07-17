<?php

namespace App\Exports;

use App\AffiliateCommission;
use App\Transaction;
use App\SettingAgentPackage;
use App\SettingMerchantBonus;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Auth, DB;

use Maatwebsite\Excel\Concerns\WithCustomValueBinder;

class CommissionExport  extends \PhpOffice\PhpSpreadsheet\Cell\StringValueBinder implements WithCustomValueBinder, FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */

    protected $start, $end, $checkYear, $checkMonth, $checkDay, $agent, $agent_code, $transaction_no, $referrer_name, $referrer_code, $status;

    function __construct($start, $end, $checkYear, $checkMonth, $checkDay, $agent, $agent_code, $transaction_no, $referrer_name, $referrer_code, $comm_type, $status = null)
    {
        $this->start = $start;
        $this->end = $end;
        $this->checkYear = $checkYear;
        $this->checkMonth = $checkMonth;
        $this->checkDay = $checkDay;
        $this->agent = $agent;
        $this->agent_code = $agent_code;
        $this->transaction_no = $transaction_no;
        $this->referrer_name = $referrer_name;
        $this->referrer_code = $referrer_code;
        $this->comm_type = $comm_type;
        $this->status = $status;
    }


    public function view(): View
    {
        $start = $this->start;
        $end = $this->end;

        $commissions = AffiliateCommission::select(
            DB::raw('COALESCE(CONCAT(m.f_name, " ", m.l_name), CONCAT(a.f_name, " ", a.l_name)) AS agentName'),
            'al.agent_lvl',
            DB::raw('COALESCE(m.ic, a.ic) AS agentIC'),
            'affiliate_commissions.*',
            't.id AS tID',
            't.grand_total',
            't.shipping_fee',
            't.processing_fee',
            't.discount',
            't.created_at AS transaction_date',
            't.user_id AS buyer',
            'affiliate_commissions.product_amount',
            'affiliate_commissions.product_qty',
            'affiliate_commissions.product_name',
            DB::raw('COALESCE(COALESCE(CONCAT(mt.f_name, " ", mt.l_name), CONCAT(ut.f_name, " ", ut.l_name)), CONCAT(at.f_name, " ", at.l_name)) AS buyerName'),
            DB::raw('COALESCE(COALESCE(mt.ic, ut.ic), at.ic) AS buyerIC'),
            DB::raw('COALESCE(COALESCE(m.code, a.code), u.code) AS agentCode'),
            DB::raw('COALESCE(COALESCE(mt.code, ut.code), at.code) AS buyerCode')
        )
            ->leftJoin('agents AS m', 'm.code', 'affiliate_commissions.user_id')
            ->leftJoin('admins AS a', 'a.code', 'affiliate_commissions.user_id')
            ->leftJoin('users AS u', 'u.code', 'affiliate_commissions.user_id')
            ->leftJoin('agent_levels as al', 'al.id', 'm.lvl')
            ->leftJoin('transactions AS t', 't.transaction_no', 'affiliate_commissions.transaction_no')
            ->leftJoin('agents AS mt', 'mt.code', 'affiliate_commissions.user_by')
            ->leftJoin('admins AS at', 'at.code', 'affiliate_commissions.user_by')
            ->leftJoin('users AS ut', 'ut.code', 'affiliate_commissions.user_by')
            ->where('affiliate_commissions.comm_amount', '>', '0');



        if (Auth::guard('merchant')->check()) {
            $commissions = $commissions->where('affiliate_commissions.merchant_id', Auth::user()->code);
        }

        $commissions = $commissions->orderBy('affiliate_commissions.created_at', 'desc')
                                   ->orderBy('affiliate_commissions.user_id', 'desc');

        if (empty($this->checkYear) && empty($this->checkMonth) && empty($this->checkDay)) {
            $commissions = $commissions->whereBetween(DB::raw('DATE_FORMAT(affiliate_commissions.created_at, "%Y-%m-%d")'), array($this->start, $this->end));
        }

        if (!empty($this->checkYear)) {
            $commissions = $commissions->where(DB::raw('DATE_FORMAT(affiliate_commissions.created_at, "%Y")'), $this->checkYear);

            $startYear = now()->startOfYear();
            $endYear = now()->endOfYear();

            $start = $startYear->format('Y-m-d');
            $end = $endYear->format('Y-m-d');
        }
        if (!empty($this->checkMonth)) {
            $commissions = $commissions->where(DB::raw('DATE_FORMAT(affiliate_commissions.created_at, "%Y-%m")'), $this->checkMonth);
        }
        if (!empty($this->checkDay)) {
            $commissions = $commissions->where(DB::raw('DATE_FORMAT(affiliate_commissions.created_at, "%Y-%m-%d")'), $this->checkDay);

            $startYear = now();
            $endYear = now();

            $start = $startYear->format('Y-m-d');
            $end = $endYear->format('Y-m-d');
        }

        if (!empty($this->agent)) {
            $commissions = $commissions->whereRaw("
                                CONVERT(
                                    COALESCE(
                                        CONCAT(mt.f_name, ' ', mt.l_name),
                                        CONCAT(ut.f_name, ' ', ut.l_name)
                                    )
                                USING utf8mb4) COLLATE utf8mb4_unicode_ci LIKE ?
                            ", ['%' . $this->agent . '%']);
        }

        if (!empty($this->agent_code)) {
            $commissions = $commissions->where(DB::raw('COALESCE(COALESCE(mt.code, ut.code), at.code)'), 'like', "%" . $this->agent_code . "%");
        }

        if (!empty($this->transaction_no)) {
            $commissions = $commissions->where('affiliate_commissions.transaction_no', 'like', "%" . $this->transaction_no . "%");
        }

        if (!empty($this->referrer_name)) {
            $commissions = $commissions->whereRaw("
                        CONVERT(
                            COALESCE(
                                CONCAT(m.f_name, ' ', m.l_name),
                                CONCAT(a.f_name, ' ', a.l_name)
                            )
                        USING utf8mb4) COLLATE utf8mb4_unicode_ci LIKE ?
                    ", ['%' . $this->referrer_name . '%']);
        }

        if (!empty($this->referrer_code)) {
             $commissions = $commissions->where(DB::raw('COALESCE(m.code, a.code)'), 'like', "%".$this->referrer_code."%");
        }

        if (!empty($this->comm_type)) {
            $commissions = $commissions->where('affiliate_commissions.comm_desc', 'like', "%".$this->comm_type."%");
        }

        // Apply status filter (default approved)
        $statusVal = strtolower((string) $this->status);
        if ($statusVal === 'burned' || $statusVal === '2') {
            $commissions = $commissions->where('affiliate_commissions.status', 2)
                                       ->where('affiliate_commissions.burned', 1);
        } else {
            $commissions = $commissions->where('affiliate_commissions.status', 1);
        }

        $commissions = $commissions->get();

        // $totalCommission = AffiliateCommission::select(DB::raw("SUM(IF(type = '1', comm_amount, NULL)) as totalAgentBonus"),
        //                                                DB::raw("SUM(IF(type = '2', comm_amount, NULL)) as totalAgentRebateBonus"),
        //                                                DB::raw("SUM(IF(type = '3', comm_amount, NULL)) as totalAffiliateBonus"),
        //                                                DB::raw("SUM(IF(type = '4', comm_amount, NULL)) as totalPerformance"),
        //                                                DB::raw("SUM(IF(type = '5', comm_amount, NULL)) as totalTeam"),
        //                                                DB::raw("SUM(IF(type = '6', comm_amount, NULL)) as totalRefferal"),
        //                                                DB::raw("SUM(IF(type = '7', comm_amount, NULL)) as totalProduct"))
        //                                       ->leftjoin('agents AS m', 'm.code', 'affiliate_commissions.user_id')
        //                                       ->leftjoin('admins AS a', 'a.code', 'affiliate_commissions.user_id')
        //                                       ->whereBetween(DB::raw('DATE_FORMAT(affiliate_commissions.created_at, "%Y-%m-%d")'), array($this->start, $this->end))
        //                                       ->where('affiliate_commissions.status', '1');
        // if(!empty($this->checkYear)){
        //     $totalCommission = $totalCommission->where(DB::raw('DATE_FORMAT(affiliate_commissions.created_at, "%Y")'), $this->checkYear);
        // }
        // if(!empty($this->checkMonth)){
        //     $totalCommission = $totalCommission->where(DB::raw('DATE_FORMAT(affiliate_commissions.created_at, "%Y-%m")'), $this->checkYear.'-'.$this->checkMonth);
        // }
        // if(!empty($this->checkDay)){
        //     $totalCommission = $totalCommission->where(DB::raw('DATE_FORMAT(affiliate_commissions.created_at, "%Y-%m-%d")'), $this->checkYear.'-'.$this->checkMonth.'-'.$this->checkDay);
        // }
        // if(!empty($this->agent)){
        //     $totalCommission = $totalCommission->where(DB::raw('CONCAT(m.f_name, " ", m.l_name)'), 'like', "%".$this->agent."%");
        // }
        // $totalCommission = $totalCommission->first();

        // $netTotal = AffiliateCommission::select(DB::raw('SUM(comm_amount) AS netTotalCommission'))
        //                                ->where('status', '1')
        //                                ->first();


        return view('backend.reports.download_commission_report', ['commissions' => $commissions,  'start' => $start, 'end' => $end]);
    }
}
