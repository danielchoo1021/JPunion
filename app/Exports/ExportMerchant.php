<?php

namespace App\Exports;

use App\Merchant;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Auth, DB;

class ExportMerchant implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $code, $merchant_name, $phone, $email, $status;

	function __construct($code, $merchant_name, $phone, $email, $status) {
	    $this->code = $code;
        $this->merchant_name = $merchant_name;
        $this->phone = $phone;
        $this->email = $email;
	    $this->status = $status;
	}


    public function view(): View
    {
    	$merchants = Merchant::select('merchants.*')
                             ->leftJoin('merchants as upm', 'upm.code', 'merchants.master_id')
                             ->leftJoin('admins as upa', 'upa.code', 'merchants.master_id')
                             ->whereNotIn('merchants.status', ['99', '3'])
                             ->orderBy('merchants.created_at', 'desc');

        if(!empty($this->code)){
            $merchants = $merchants->where(DB::raw('CONCAT(merchants.display_code, merchants.display_running_no)'), 'like', "%".$this->code."%");
        }
        if(!empty($this->merchant_name)){
            $merchants = $merchants->where(DB::raw('CONCAT(merchants.f_name, " ", merchants.l_name)'), 'like', "%".$this->merchant_name."%");
        }
        if(!empty($this->phone)){
            $merchants = $merchants->where('merchants.phone', 'like', "%".$this->phone."%");
        }
        if(!empty($this->email)){
            $merchants = $merchants->where('merchants.email', 'like', "%".$this->email."%");
        }
        if ($this->status !== null && $this->status !== '') {
            if($this->status == 55){
                $merchants = $merchants->where('merchants.active_period', '>', 0)
                                        ->where(DB::raw('DATE_ADD(merchants.created_at, INTERVAL merchants.active_period DAY)'), '<',now());
            }elseif($this->status == 1){
                $merchants = $merchants
                    ->where('merchants.status', 1)
                    ->where(function ($q) {
                        $q->where('merchants.active_period', 0)
                            ->orWhere(DB::raw('DATE_ADD(merchants.created_at, INTERVAL merchants.active_period DAY)'), '>=', now());
                    });
            }else{
                $merchants = $merchants->where('merchants.status', 'like', "%".$this->status."%");
            }
        }

        $merchants = $merchants->get();

        $get_merchant_expired_date = [];
            foreach($merchants as $merchant){
                $get_merchant_expired_date[$merchant->code] = \App\Http\Controllers\GlobalController::get_merchant_expired_date($merchant->code, $merchant->active_period);
        }

        return view('backend.merchants.download_merchant_list', ['merchants'=>$merchants, 'get_merchant_expired_date' => $get_merchant_expired_date]);

    }

    
}
