<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// use Illuminate\Support\Facades\Input;
use Validator, Redirect, Toastr, DB, File;
use App\Promotion;
use App\Product;
use App\Transaction;
use App\AppliedPromotion;
use App\SettingNewCustomerPromotion;
use App\Admin;
use App\User;
use App\AssignedVoucher;
use App\Agent;

use App\AddOnDeal;
use App\AddOnDealItem;
use App\AddOnDealSubItem;
use App\ProductVariation;
use App\ProductSecondVariation;

use App\Http\Controllers\GlobalController;

use DateTime, Auth;

class PromotionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $promotions = Promotion::where('status', '!=', '3')
                               ->whereNull('register_voucher');
                               //->orderBy('created_at', 'desc');
        if(Auth::guard('merchant')->check()){
        $promotions = $promotions->where('merchant_id', Auth::user()->code);
        }
        $queries = [];
        $columns = [
            'promotion_title', 'status', 'start_desc', 'start_asc', 'end_desc', 'end_asc', 'title_desc', 'title_asc' 
        ];

        foreach($columns as $column){
            if(request()->has($column) && !empty(request($column))){
                if($column == 'start_desc'){
                    $promotions = $promotions->orderBy('promotions.start_date', 'desc');
                }elseif($column == 'start_asc'){
                    $promotions = $promotions->orderBy('promotions.start_date', 'asc');
                }elseif($column == 'end_desc'){
                    $promotions = $promotions->orderBy('promotions.end_date', 'desc');
                }elseif($column == 'end_asc'){
                    $promotions = $promotions->orderBy('promotions.end_date', 'asc');
                }elseif($column == 'title_desc'){
                    $promotions = $promotions->orderBy('promotions.promotion_title', 'desc');
                }elseif($column == 'title_asc'){
                    $promotions = $promotions->orderBy('promotions.promotion_title', 'asc');
                }elseif($column == 'status'){
                    $current_date = date('Y-m-d H:i:s');

                    if(request($column) == 1){
                        $promotions = $promotions->where('promotions.status', request($column));
                        $promotions = $promotions->where('promotions.end_date', '>', $current_date);
                    }else{
                        $promotions = $promotions->where(function ($q) use ($current_date) {
                            $q->where('promotions.status', 2)
                            ->orWhere(function ($q1) use ($current_date) {
                                $q1->where('promotions.status', 1)
                                    ->where('promotions.end_date', '<', $current_date);
                            });
                        });
                    }                      
                }else{
                    $promotions = $promotions->where($column, 'like', "%".request($column)."%");
                }

                $queries[$column] = request($column);

            }
        }
        $per_page = 10;
        if(!empty(request('per_page'))){
            $per_page = request('per_page');
        }
        $promotions = $promotions->orderBy('promotions.created_at', 'desc');
        $promotions = $promotions->paginate($per_page)->appends($queries);

        $available = [];
        $redeemed = [];
        foreach($promotions as $promotion){
            $transaction = AppliedPromotion::select(DB::raw('COUNT(id) AS totalRedeemed'))->where('promotion_id', $promotion->id)->whereIn('status', ['1', '2'])->first();
            
            $available[$promotion->id] = (float)$promotion->quantity - (float)$transaction->totalRedeemed;
            $redeemed[$promotion->id] = $transaction->totalRedeemed;
        }

        return view('backend.promotions.index', ['promotions'=>$promotions], compact('available', 'redeemed'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $products = Product::where('status', 1);
        if(Auth::guard('merchant')->check()){
            $products = $products->where('merchant_id', Auth::user()->code);
        }
        $products = $products->get();

        $users = User::where('status', 1)->get();
        $agents = Agent::where('status', 1)->get();
        $assignmentHistory = AssignedVoucher::where('promotion_id', -1)->paginate(request('per_page', 10));
        return view('backend.promotions.create', ['products'=>$products, 'users'=>$users, 'agents'=>$agents, 'assignmentHistory'=>$assignmentHistory]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $translation_data = GlobalController::get_translations();
        $free_shipping = isset($request->free_shipping) ? 1 : 0;
        $display_voucher= isset($request->display_voucher) ? 1 : 0;
        $product_voucher = isset($request->product_voucher) ? 1 : 0;

        if($product_voucher == 1){
            $validator = Validator::make($request->all(), [
                'promotion_title' => 'required',
                'quantity' => 'required',
                'products' => 'required',
                'minSpend' => 'nullable',
                'maxCapped' => 'nullable',
            ]);
        }else{
            if($free_shipping == 1){
                $validator = Validator::make($request->all(), [
                    'promotion_title' => 'required',
                    'quantity' => 'required',
                    'start_date' => 'required',
                    'end_date' => 'required',
                    'minSpend' => 'nullable',
                    'maxCapped' => 'nullable',
                ]);
            }else{
                $validator = Validator::make($request->all(), [
                    'promotion_title' => 'required',
                    'discount_code' => 'required',
                    'amount_type' => 'required',
                    'amount' => 'required',
                    'quantity' => 'required',
                    'start_date' => 'required',
                    'end_date' => 'required',
                    'minSpend' => 'nullable',
                    'maxCapped' => 'nullable',
                ]);            
            }            
        }

        if ($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }

        if($request->amount_type == 'Percentage' && $request->amount > '100'){
            return Redirect::back()->withInput()->withErrors($translation_data['backendlang']['backendlang']['The discount percentage unable exceed 100'] ?? "The discount percentage unable exceed 100");
        }

        $input = $request->all();
        $input['products'] = !empty($request->products) ? implode(',', $request->products) : '';
        $input['start_date'] = !empty($request->start_date) ? date('Y-m-d H:i:s', strtotime($request->start_date)) : '';
        $input['end_date'] = !empty($request->end_date) ? date('Y-m-d H:i:s', strtotime($request->end_date)) : '';
        $input['free_shipping'] = $free_shipping;
        $input['display_voucher'] = $display_voucher;
        $input['product_voucher'] = $product_voucher;
        $input['minSpend'] = !empty($request->minSpend) ? $request->minSpend : '';
        $input['maxCapped'] = !empty($request->maxCapped) ? $request->maxCapped : '';
        if(Auth::guard('merchant')->check()){
        $input['merchant_id'] = Auth::user()->code;
        }
        
        if(!empty($request->image)){
            $files = $request->file('image'); 
            $name = $files->getClientOriginalName();
            $exp = explode(".", $name);
            $file_ext = end($exp);
            $name = md5($name.date('Y-m-d H:i:s')).'.'.$file_ext;

            $files->move(GlobalController::get_image_path("uploads/promotions/"), $name);

            $input['image'] = "uploads/promotions/".$name;
        }

        $create = Promotion::create($input);

        if (!empty($request->users) && !empty($request->assign_quantity)) {
            foreach ($request->users as $userCode) {
                for($i = 0; $i<$request->assign_quantity;$i++){
                    AppliedPromotion::create([
                        'promotion_id' => $create->id,
                        'user_id' => $userCode, // now stores code for both user and agent
                        'is_assign' => 1,
                        'assign_by' => Auth::user()->code,
                        'remark' => $request->remark,
                        'promotion_title' => $create->promotion_title,
                        'image' => $create->image,
                        'discount_code' => $create->discount_code,
                        'amount_type' => $create->amount_type,
                        'amount' => $create->amount,
                        'quantity' => 1,
                        'created_date' => now(),
                        'created_by' => Auth::id(),
                    ]);
                }
            }
        }

        Toastr::success($translation_data['backendlang']['backendlang']['Promotion_Create_Successfully'] ?? "Promotion Create Successfully!");
        return redirect()->route('promotion.promotions.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $promotion = Promotion::find($id);
        $products = Product::where('status', '1')->get();
        $users = User::where('status', 1)->get();
        $agents = Agent::where('status', 1)->get();
        $query = AppliedPromotion::where('promotion_id', $promotion->id);
        if (request('user_id')) {
            $query->where('user_id', request('user_id'));
        }
        if (request('dates')) {
            $dates = explode(' - ', request('dates'));
            if (count($dates) == 2) {
                $start = date('Y-m-d 00:00:00', strtotime(str_replace('/', '-', trim($dates[0]))));
                $end = date('Y-m-d 23:59:59', strtotime(str_replace('/', '-', trim($dates[1]))));
                $query->whereBetween('created_at', [$start, $end]);
            }
        }
        $assignmentHistory = $query->with(['userByCode','agentByCode','admin'])->paginate(request('per_page', 10));

        $startDate = '';
        $endDate = '';
        if (request('dates')) {
            $dates = explode(' - ', request('dates'));
            if (count($dates) == 2) {
                $startDate = trim($dates[0]);
                $endDate = trim($dates[1]);
            }
        } else {
            $startDate = date('d/m/Y', strtotime('first day of this month'));
            $endDate = date('d/m/Y', strtotime('last day of this month'));
        }
        
        return view('backend.promotions.edit', [
            'promotion' => $promotion,
            'products' => $products,
            'users' => $users,
            'agents' => $agents,
            'assignmentHistory' => $assignmentHistory,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $translation_data = GlobalController::get_translations();
        $free_shipping = isset($request->free_shipping) ? 1 : 0;
        $display_voucher = isset($request->display_voucher) ? 1 : 0;
        $product_voucher = isset($request->product_voucher) ? 1 : 0;

        if($product_voucher == 1){
            $validator = Validator::make($request->all(), [
                'promotion_title' => 'required',
                'quantity' => 'required',
                'products' => 'required',
                'minSpend' => 'nullable',
                'maxCapped' => 'nullable',
            ]);
        }else{
            if($free_shipping == 1){
                $validator = Validator::make($request->all(), [
                    'promotion_title' => 'required',
                    'quantity' => 'required',
                    'minSpend' => 'nullable',
                    'maxCapped' => 'nullable',
                    // 'start_date' => 'required',
                    // 'end_date' => 'required',
                ]);
            }else{
                $validator = Validator::make($request->all(), [
                    'promotion_title' => 'required',
                    'discount_code' => 'required',
                    'amount_type' => 'required',
                    'amount' => 'required',
                    'quantity' => 'required',
                    'minSpend' => 'nullable',
                    'maxCapped' => 'nullable',
                    // 'start_date' => 'required',
                    // 'end_date' => 'required',
                ]);            
            }            
        }

        if ($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }

        if($request->amount_type == 'Percentage' && $request->amount > '100'){
            return Redirect::back()->withInput()->withErrors($translation_data['backendlang']['backendlang']['The discount percentage unable exceed 100'] ?? "The discount percentage unable exceed 100");
        }
        
        $input = $request->all();
        $input['products'] = !empty($request->products) ? implode(',', $request->products) : '';
        if(!empty($request->start_date)){
        $input['start_date'] = !empty($request->start_date) ? date('Y-m-d H:i:s', strtotime($request->start_date)) : '';            
        }
        if(!empty($request->end_date)){
        $input['end_date'] = !empty($request->end_date) ? date('Y-m-d H:i:s', strtotime($request->end_date)) : '';
        }
        $input['free_shipping'] = $request->free_shipping;
        $input['display_voucher'] = $request->display_voucher;
        $input['product_voucher'] = $request->product_voucher;
        $input['minSpend'] = !empty($request->minSpend) ? $request->minSpend : '';
        $input['maxCapped'] = !empty($request->maxCapped) ? $request->maxCapped : '';

        if(!empty($request->file('image'))){
            $files = $request->file('image'); 
            $name = $files->getClientOriginalName();
            $exp = explode(".", $name);
            $file_ext = end($exp);
            $name = md5($name.date('Y-m-d H:i:s')).'.'.$file_ext;

            $files->move(GlobalController::get_image_path("uploads/promotions/"), $name);

            $input['image'] = "uploads/promotions/".$name;
        }
        $update = Promotion::find($id);
        $update->update($input);

        if (!empty($request->users) && !empty($request->assign_quantity)) {
            foreach ($request->users as $userId) {

                $check_unique_promotion = AppliedPromotion::where('user_id', $userId)->where('promotion_id',$update->id)->where('amount_type', $update->amount_type)->where('amount',$update->amount)->count();

                for($i = 0; $i<$request->assign_quantity;$i++){
                    $discount_code = "";

                    // Check same value exists
                    $existing_same_value = AppliedPromotion::where('user_id', $userId)
                        ->where('promotion_id', $update->id)
                        ->where('amount_type', $update->amount_type)
                        ->where('amount', $update->amount)
                        ->first();

                    if ($existing_same_value) {

                        // Same value → no suffix
                        $discount_code = $existing_same_value->discount_code;

                    } else {

                        // Find highest suffix
                        $latest = AppliedPromotion::where('user_id', $userId)
                            ->where('promotion_id', $update->id)
                            ->where('discount_code', 'like', $update->discount_code . '-%')
                            ->orderByRaw("CAST(SUBSTRING_INDEX(discount_code, '-', -1) AS UNSIGNED) DESC")
                            ->first();

                        if ($latest) {
                            $lastNumber = (int) substr($latest->discount_code, strrpos($latest->discount_code, '-') + 1);
                            $nextNumber = $lastNumber + 1;
                        } else {
                            // First change → start at 01
                            $nextNumber = 1;
                        }

                        // Format as -01, -02, -03
                        $discount_code = $update->discount_code . '-' . str_pad($nextNumber, 2, '0', STR_PAD_LEFT);
                    }


                    AppliedPromotion::create([
                        'promotion_id' => $update->id,
                        'user_id' => $userId,
                        'is_assign' => 1,
                        'assign_by' => Auth::user()->code,
                        'remark' => $request->remark,
                        'promotion_title' => $update->promotion_title,
                        'image' => $update->image,
                        'discount_code' => $discount_code,
                        'amount_type' => $update->amount_type,
                        'amount' => $update->amount,
                        'quantity' => 1,
                        'created_date' => now(),
                        'created_by' => Auth::id(),
                    ]);
                }
            }
        }

        Toastr::success($translation_data['backendlang']['backendlang']['Promotion_Updated_Successfully'] ?? 'Promotion Updated Successfully!');
        return redirect()->route('promotion.promotions.edit', $id);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function new_customer_promotions()
    {
        if(!empty(request('dates'))){

          $new_dates = explode('-', request('dates'));
          $start = date('Y-m-d', strtotime($new_dates[0]));
          $end = date('Y-m-d', strtotime($new_dates[1]));

          $startDate = $new_dates[0];
          $endDate = $new_dates[1];

        }else{

          $ds = new DateTime("first day of this month");
          $de = new DateTime("last day of this month");

          $start = $ds->format('Y-m-d');
          $end = $de->format('Y-m-d');

          $startDate = $ds->format('m/d/Y');
          $endDate = $de->format('m/d/Y');
        }

        $leftJoin = DB::raw("(SELECT * FROM merchants WHERE status = '1') AS i");
        $leftJoin2 = DB::raw("(SELECT * FROM admins) AS x");

        $promotions = Promotion::select('promotions.*', 'u.status AS user_status',
                                        DB::raw('COALESCE(m.master_id, u.master_id) AS referrer_code'),
                                        DB::raw('COALESCE(CONCAT(x.f_name, " ", x.l_name), CONCAT(i.f_name, " ", i.l_name)) AS referrer_name'),
                                        DB::raw('COALESCE(CONCAT(m.f_name, " ", m.l_name), CONCAT(u.f_name, " ", u.l_name)) AS customer_name'),
                                        DB::raw('COALESCE(m.code, u.code) AS customer_code'),
                                        DB::raw('COALESCE(m.ic, u.ic) AS customer_ic'),
                                        DB::raw('COALESCE(CONCAT( "+(", m.country_code, ")", m.phone), CONCAT( "+(", u.country_code, ")", u.phone)) AS customer_phone'),
                                        DB::raw('COALESCE(m.code, u.code) AS customer_code'),
                                        DB::raw('COALESCE(m.company_registration_no, u.company_registration_no) AS customer_company_registration_no'))
                               ->leftjoin('users as u', 'u.code', 'promotions.register_voucher')
                               ->leftjoin('merchants as m', 'm.code', 'promotions.register_voucher')
                               ->leftjoin('agent_levels as al', 'al.id', 'm.lvl')
                               ->leftJoin($leftJoin, function($join) {
                                        $join->on(DB::raw('COALESCE(m.master_id, u.master_id)'), '=', 'i.code');
                                   })
                                    ->leftJoin($leftJoin2, function($join) {
                                        $join->on(DB::raw('COALESCE(m.master_id, u.master_id)'), '=', 'x.code');
                                   })
                               ->where('promotions.status', '!=', '3')
                               ->whereNotNull('promotions.register_voucher');
                               // ->orderBy('promotions.created_at', 'desc');

        $queries = [];
        $columns = [
            'promotion_title', 'customer_name', 'own_code', 'ic', 'phone', 'referrer_code', 'referrer_name', 'company_registration_no', 'title_desc', 'title_asc', 'cust_name_desc', 'cust_name_asc', 'code_desc', 'code_asc', 'ic_desc', 'ic_asc', 'phone_desc', 'phone_asc', 'lvl_desc', 'lvl_asc', 'ref_code_desc', 'ref_code_asc', 'ref_name_desc', 'ref_name_asc', 'company_no_desc', 'company_no_asc', 'start_date_desc', 'start_date_asc', 'end_date_desc', 'end_date_asc', 'status_desc', 'status_asc', 'dates', 'status'
        ];

        foreach($columns as $column){
            if(request()->has($column) && !empty(request($column))){
                if($column == 'status'){
                    $now = date('Y-m-d H:i:s');
                    if(request($column) == '1'){
                        $promotions = $promotions->where('start_date', '<=', $now)
                                                 ->Where('end_date', '>=', $now);
                    }elseif(request($column) == '2'){
                        $promotions = $promotions->where('start_date', '>=', $now)
                                                 ->orWhere('end_date', '<=', $now);
                    }
                }elseif($column == 'dates'){
                  $promotions = $promotions->whereBetween(DB::raw('DATE_FORMAT(promotions.created_at, "%Y-%m-%d")'), array($start, $end));
                }elseif($column == 'customer_name'){
                    $promotions = $promotions->where(DB::raw('COALESCE(CONCAT(m.f_name, " ", m.l_name), CONCAT(u.f_name, " ", u.l_name))'), 'like', "%".request($column)."%");
                }elseif($column == 'own_code'){
                    $promotions = $promotions->where(DB::raw('COALESCE(m.code, u.code)'), 'like', "%".request($column)."%");
                }elseif($column == 'ic'){
                    $promotions = $promotions->where(DB::raw('COALESCE(m.ic, u.ic)'), 'like', "%".request($column)."%");
                }elseif($column == 'phone'){
                    $promotions = $promotions->where(DB::raw('COALESCE(CONCAT(m.country_code, m.phone), CONCAT(u.country_code, u.phone))'), 'like', "%".request($column)."%");
                }elseif($column == 'referrer_code'){
                    $promotions = $promotions->where(DB::raw('COALESCE(m.master_id, u.master_id)'), 'like', "%".request($column)."%");
                }elseif($column == 'referrer_name'){
                    $promotions = $promotions->where(DB::raw('COALESCE(CONCAT(x.f_name, " ", x.l_name), CONCAT(i.f_name, " ", i.l_name))'), 'like', "%".request($column)."%");
                }elseif($column == 'company_registration_no'){
                    $promotions = $promotions->where(DB::raw('COALESCE(m.company_registration_no, u.company_registration_no)'), 'like', "%".request($column)."%");
                }elseif($column == 'title_desc'){
                    $promotions = $promotions->orderBy('promotions.promotion_title', 'desc');
                }elseif($column == 'title_asc'){
                    $promotions = $promotions->orderBy('promotions.promotion_title', 'asc');
                }elseif($column == 'cust_name_desc'){
                    $promotions = $promotions->orderBy('customer_name', 'desc');
                }elseif($column == 'cust_name_asc'){
                    $promotions = $promotions->orderBy('customer_name', 'asc');
                }elseif($column == 'code_desc'){
                    $promotions = $promotions->orderBy('customer_code', 'desc');
                }elseif($column == 'code_asc'){
                    $promotions = $promotions->orderBy('customer_code', 'asc');
                }elseif($column == 'ic_desc'){
                    $promotions = $promotions->orderBy('customer_ic', 'desc');
                }elseif($column == 'ic_asc'){
                    $promotions = $promotions->orderBy('customer_ic', 'asc');
                }elseif($column == 'phone_desc'){
                    $promotions = $promotions->orderBy('customer_phone', 'desc');
                }elseif($column == 'phone_asc'){
                    $promotions = $promotions->orderBy('customer_phone', 'asc');
                }elseif($column == 'lvl_desc'){
                    $promotions = $promotions->orderBy('user_status', 'desc');
                }elseif($column == 'lvl_asc'){
                    $promotions = $promotions->orderBy('user_status', 'asc');
                }elseif($column == 'ref_code_desc'){
                    $promotions = $promotions->orderBy('referrer_code', 'desc');
                }elseif($column == 'ref_code_asc'){
                    $promotions = $promotions->orderBy('referrer_code', 'asc');
                }elseif($column == 'ref_name_desc'){
                    $promotions = $promotions->orderBy('referrer_name', 'desc');
                }elseif($column == 'ref_name_asc'){
                    $promotions = $promotions->orderBy('referrer_name', 'asc');
                }elseif($column == 'company_no_desc'){
                    $promotions = $promotions->orderBy('customer_company_registration_no', 'desc');
                }elseif($column == 'company_no_asc'){
                    $promotions = $promotions->orderBy('customer_company_registration_no', 'asc');
                }elseif($column == 'start_date_desc'){
                    $promotions = $promotions->orderBy('promotions.start_date', 'desc');
                }elseif($column == 'start_date_asc'){
                    $promotions = $promotions->orderBy('promotions.start_date', 'asc');
                }elseif($column == 'end_date_desc'){
                    $promotions = $promotions->orderBy('promotions.end_date', 'desc');
                }elseif($column == 'end_date_asc'){
                    $promotions = $promotions->orderBy('promotions.end_date', 'asc');
                }elseif($column == 'status_desc'){
                    $promotions = $promotions->orderBy('promotions.status', 'desc');
                }elseif($column == 'status_asc'){
                    $promotions = $promotions->orderBy('promotions.status', 'asc');
                }else{
                    $promotions = $promotions->where($column, 'like', "%".request($column)."%");
                }
                $queries[$column] = request($column);

            }
        }
        $per_page = 10;
        if(!empty(request('per_page'))){
            $per_page = request('per_page');
        }
        $promotions = $promotions->orderBy('promotions.created_at', 'desc');
        $promotions = $promotions->paginate($per_page)->appends($queries);

        $available = [];
        $redeemed = [];
        $underRedemption = [];
        $redeemedWhere = [];
        foreach($promotions as $promotion){
            // $transaction = AppliedPromotion::select(DB::raw('COUNT(id) AS totalRedeemed'))->where('promotion_id', $promotion->id)->whereIn('status', ['1', '99', '2'])->first();
            $transaction = AppliedPromotion::select(DB::raw('COUNT(id) AS totalRedeemed'))->where('promotion_id', $promotion->id)->where('status', '2')->first();
            $applying = AppliedPromotion::select(DB::raw('COUNT(id) AS totalRedeeming'))->where('promotion_id', $promotion->id)->where('status', '1')->first();
            
            $available[$promotion->id] = (float)$promotion->quantity - (float)$transaction->totalRedeemed;
            $redeemed[$promotion->id] = $transaction->totalRedeemed;
            $redeemedWhere[$promotion->id] = Transaction::where('discount_code', $promotion->id)
                                                        ->where('status', '1')
                                                        ->first();

            $underRedemption[$promotion->id] = $applying->totalRedeeming;
        }

        return view('backend.promotions.new_customer_promotions', ['promotions'=>$promotions, 'startDate'=>$startDate, 'endDate'=>$endDate], compact('available', 'redeemed', 'underRedemption', 'redeemedWhere'));
    }

    public function setting_new_customer_promotions()
    {
        $setting = SettingNewCustomerPromotion::find(1);
        $products = Product::where('status', '1')->get();

        return view('backend.promotions.setting_new_customer_promotions', ['setting'=>$setting, 'products'=>$products]);
    }

    public function setting_new_customer_promotion_save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'promotion_title' => 'required',
            'discount_code' => 'required',
            'amount_type' => 'required',
            'amount' => 'required',
            'duration' => 'required',
            'minSpend' => 'nullable',
            'maxCapped' => 'nullable',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }

        if($request->amount_type == 'Percentage' && $request->amount > '100'){
            return Redirect::back()->withInput()->withErrors("The discount percentage unable exceed 100");
        }

        $setting = SettingNewCustomerPromotion::find(1);

        if(!empty($setting->id)){
            $input = [];

            $input['active'] = !empty($request->active) ? '1' : '0';
            $input['promotion_title'] = $request->promotion_title;

            if(!empty($request->file('image'))){
                $files = $request->file('image'); 
                $name = $files->getClientOriginalName();
                $exp = explode(".", $name);
                $file_ext = end($exp);
                $name = md5($name.date('Y-m-d H:i:s')).'.'.$file_ext;

                $files->move(GlobalController::get_image_path("uploads/promotions/"), $name);

                $input['image'] = "uploads/promotions/".$name;
            }

            $input['discount_code'] = $request->discount_code;
            $input['amount_type'] = $request->amount_type;
            $input['amount'] = $request->amount;
            $input['limit_type'] = $request->limit_type;
            $input['minSpend'] = !empty($request->minSpend) ? $request->minSpend : null;
            $input['maxCapped'] = !empty($request->maxCapped) ? $request->maxCapped : null;
            $input['usage_limit'] = !empty($request->usage_limit) ? $request->usage_limit : '';
            $input['products'] = !empty($request->products) ? implode(',', $request->products) : '';
            $input['duration'] = $request->duration;
            $input['status'] = '1';

            $setting = $setting->update($input);
        }else{

            $insert = [];

            $insert['active'] = !empty($request->active) ? '1' : '0';
            $insert['promotion_title'] = $request->promotion_title;

            if(!empty($request->file('image'))){
                $files = $request->file('image'); 
                $name = $files->getClientOriginalName();
                $exp = explode(".", $name);
                $file_ext = end($exp);
                $name = md5($name.date('Y-m-d H:i:s')).'.'.$file_ext;

                $files->move(GlobalController::get_image_path("uploads/promotions/"), $name);

                $insert['image'] = "uploads/promotions/".$name;
            }

            $insert['discount_code'] = $request->discount_code;
            $insert['amount_type'] = $request->amount_type;
            $insert['amount'] = $request->amount;
            $insert['minSpend'] = !empty($request->minSpend) ? $request->minSpend : '';
            $insert['maxCapped'] = !empty($request->maxCapped) ? $request->maxCapped : '';
            $insert['limit_type'] = $request->limit_type;
            $insert['usage_limit'] = !empty($request->usage_limit) ? $request->usage_limit : '';
            $insert['products'] = !empty($request->products) ? implode(',', $request->products) : '';
            $insert['duration'] = $request->duration;
            $insert['status'] = '1';          

            $create = SettingNewCustomerPromotion::Create($insert);  
        }

        Toastr::success("New Customer Promotion Updated Successfully!");
        return redirect()->route('setting_new_customer_promotions');
    }

    public function add_on_deal()
    {
        $product = Product::where('status','1');
        if(Auth::guard('merchant')->check()){
        $product = $product->where('merchant_id', Auth::user()->code);
        }
        $product = $product->get();

        $AddOnDeal = AddOnDeal::whereNotNull('status')
                              ->whereNot('status',4)
                              ->where('end_date', '>', date('m/d/Y h:mm:ss'));
        if(Auth::guard('merchant')->check()){
        $AddOnDeal = $AddOnDeal->where('merchant_id', Auth::user()->code);
        }

        $per_page = request('per_page', 10);

        $queries = [];
        if (!empty(request('per_page'))) {
            $queries['per_page'] = $per_page;
        }

        if (!empty(request('promotion_title'))) {
            $AddOnDeal = $AddOnDeal->where('add_on_deals.promotion_name', 'like', "%" . request('promotion_title') . "%");
            $queries['promotion_title'] = request('promotion_title');
        }

        if (!empty(request('status'))) {
            $AddOnDeal = $AddOnDeal->where('add_on_deals.status', request('status'));
            $queries['status'] = request('status');
        }

        if (!empty(request('start_desc'))) {
            $AddOnDeal = $AddOnDeal->orderBy('add_on_deals.start_date', 'desc');
            $queries['start_desc'] = request('start_desc');
        } elseif (!empty(request('start_asc'))) {
            $AddOnDeal = $AddOnDeal->orderBy('add_on_deals.start_date', 'asc');
            $queries['start_asc'] = request('start_asc');
        } elseif (!empty(request('end_desc'))) {
            $AddOnDeal = $AddOnDeal->orderBy('add_on_deals.end_date', 'desc');
            $queries['end_desc'] = request('end_desc');
        } elseif (!empty(request('end_asc'))) {
            $AddOnDeal = $AddOnDeal->orderBy('add_on_deals.end_date', 'asc');
            $queries['end_asc'] = request('end_asc');
        } elseif (!empty(request('title_desc'))) {
            $AddOnDeal = $AddOnDeal->orderBy('add_on_deals.promotion_name', 'desc');
            $queries['title_desc'] = request('title_desc');
        } elseif (!empty(request('title_asc'))) {
            $AddOnDeal = $AddOnDeal->orderBy('add_on_deals.promotion_name', 'asc');
            $queries['title_asc'] = request('title_asc');
        } else {
            $AddOnDeal = $AddOnDeal->orderBy('add_on_deals.created_at', 'desc');
        }

        $AddOnDeal = $AddOnDeal->paginate($per_page)->appends($queries);



        return view('backend.promotions.add_on_deal',['product'=>$product,'AddOnDeal'=>$AddOnDeal]);
    }

    public function add_on_deal_create()
    {
        // $item = AddOnDealItem::select('add_on_deal_items.*','p.*','p.product_name as p_name','p.id as pid','add_on_deal_items.id as aid')
        //                     ->leftjoin('products as p','add_on_deal_items.product_id','p.id')
        //                     ->where('add_on_deal_items.status','99')
        //                     ->get();

        $add_on_deal = AddOnDeal::where('status',4)->first();

        $item = collect();

        if($add_on_deal){
            $item = AddOnDealItem::select('add_on_deal_items.*','p.*','p.product_name as p_name','add_on_deal_items.id as aid','p.id as pid')
                    ->leftjoin('products as p','add_on_deal_items.product_id','p.id')
                    ->where('add_on_deal_items.add_on_id', $add_on_deal->id)
                    ->get();
        }

        $count = count($item);
        $stock = [];
        $image = [];    
        $item_variation = [];
        $item_sec_variation = [];
        $item_variation_stock = [];
        foreach ($item as $key => $items) {
            $stock[$items->aid] = GlobalController::balance_quantity($items->product_id);
            if(!empty($items->get_product_det->first_image->image)){
                $image[$items->aid] = $items->get_product_det->first_image->image;
            }
            if (!empty($items->variation_id)) {
                $item_variation[$items->aid] = ProductVariation::where('id',$items->variation_id)->first();
                $item_variation_stock[$items->aid] = GlobalController::variation_balance_quantity($items->variation_id);
                if (!empty($items->second_variation_id)) {
                    $item_sec_variation[$items->aid] = ProductSecondVariation::where('id',$items->second_variation_id)->where('variation_id',$items->variation_id)->first();
                }
            }
        }



        $sub_item_image = [];
        $sub_item_stock = [];
        $sub_item_variations_stock = [];
        $variations = [];
        $second_variations = [];
        // $sub_item  = AddOnDealSubItem::select('add_on_deal_sub_items.*','p.*','p.product_name as p_name','p.id as pid','add_on_deal_sub_items.id as sid')
        //             ->leftjoin('products as p','add_on_deal_sub_items.product_id','p.id')
        //             ->where('add_on_deal_sub_items.status','99')
        //             ->get();
        $sub_item = collect();

        if($add_on_deal){
        $sub_item  = AddOnDealSubItem::select('add_on_deal_sub_items.*','p.*','p.product_name as p_name','p.id as pid','add_on_deal_sub_items.id as sid')
                    ->leftjoin('products as p','add_on_deal_sub_items.product_id','p.id')
                     ->where('add_on_deal_sub_items.add_on_id',$add_on_deal->id)
                    ->where('add_on_deal_sub_items.status','1')
                    ->get();
        }
   
        $original_price = [];
        $current_price = [];

        foreach ($sub_item as $key => $items) {
            $sub_item_stock[$items->sid] = GlobalController::balance_quantity($items->product_id);
            if (!empty($items->variation_id)) {
                $sub_item_stock[$items->sid] = GlobalController::variation_balance_quantity($items->variation_id);
                if (!empty($items->second_variation_id)) {
                    $sub_item_stock[$items->sid] = GlobalController::second_variation_balance_quantity($items->second_variation_id);
                }
            }
            if(!empty($items->get_product_det->first_image->image)){
            $sub_item_image[$items->sid] = $items->get_product_det->first_image->image;
            }
            if (!empty($items->variation_id)) {
                $variations[$items->sid] = ProductVariation::where('id',$items->variation_id)->first();
                if ($items->second_variation_id) {
                    $second_variations[$items->sid] = ProductSecondVariation::where('id',$items->second_variation_id)->where('variation_id',$items->variation_id)->first();
                }
            }

            $original_price[$items->sid] = GlobalController::get_product_pricing(md5($items->product_id), "", $items->variation_id, $items->second_variation_id);

            $current_price[$items->sid] = GlobalController::get_add_on_sub_item_price("", $items->add_on_id, $items->product_id, $items->variation_id, $items->second_variation_id);
        }

        $count_sub_items = count($sub_item);

        return view('backend.promotions.add_on_deal_create',['item'=>$item,'sub_item'=>$sub_item,'count'=>$count,'count_sub_items'=>$count_sub_items], compact('add_on_deal','stock','image','sub_item_stock','sub_item_image','variations','sub_item_variations_stock','second_variations','item_variation','item_sec_variation','item_variation_stock',
            'original_price',
            'current_price'));
    }

    public function add_on_deal_save(Request $request)
    {
        $translation_data = GlobalController::get_translations();
        DB::beginTransaction();
        try {

            $validator = Validator::make($request->all(), [
                'promotion_name' => 'required',
  
                'start_date' => 'required',
                'end_date' => 'required',
            ]);

            if ($validator->fails()) {
                return Redirect::back()->withInput($request->all())->withErrors($validator);
            }

            // $input = [];
            $add_on_deal = AddOnDeal::where('status',4)->first();
            $add_on_deal->promotion_name = $request->promotion_name;
            $add_on_deal->status = 1;

            if(Auth::guard('merchant')->check()){
            $add_on_deal->merchant_id = Auth::user()->code;
            }

            $add_on_deal->start_date = date('Y-m-d H:i:s', strtotime($request->start_date));
            $add_on_deal->end_date = date('Y-m-d H:i:s', strtotime($request->end_date));

            $add_on_deal->save();
            // $add_on_deal = AddOnDeal::create($input);

            $main_item = AddOnDealItem::where('status','99')->update(['status'=>'1','add_on_id'=>$add_on_deal->id]);
            $sub_item  = AddOnDealSubItem::where('status','99')->update(['status'=>'1','add_on_id'=>$add_on_deal->id]);

            if($request->sid !== null){
                for($z=0; $z<count($request->sid); $z++){
                    $add_on_sub_item = AddOnDealSubItem::find($request->sid[$z]);
                    $add_on_sub_item->add_on_price = $request->add_on_price[$add_on_sub_item->id];
                    $add_on_sub_item->add_on_discount = $request->add_on_discount[$add_on_sub_item->id];
                    $add_on_sub_item->purchase_limit = $request->purchase_limit[$add_on_sub_item->id];
                    $add_on_sub_item->save();
                }
            }

            $inactive_other_add_on_deals = AddOnDeal::whereNotIn('id', [$add_on_deal->id])
                                                      ->where('status', '!=', '3')
                                                      ->update(['status'=>'3']);

            DB::commit();
            toastr::success($translation_data['backendlang']['backendlang']['Add_on_deal_created_successfully'] ?? 'Add-on deal created successfully');
            return redirect()->route('add_on_deal');
        } catch (Throwable $e) {
            DB::rollback();
            $message = $e->getMessage();
            toastr::error($message);
            return redirect()->route('add_on_deal_create');
        }    
    }

    public function add_on_deal_edit($id)
    {
        try{
            $add_on_deal = AddOnDeal::find($id);

            $item = AddOnDealItem::select('add_on_deal_items.*','p.*','p.product_name as p_name','add_on_deal_items.id as aid','p.id as pid')
                    ->leftjoin('products as p','add_on_deal_items.product_id','p.id')
                    ->whereIn('add_on_deal_items.status', ['1', '2'])
                    ->where('add_on_deal_items.add_on_id', $id)
                    ->get();


            $count = count($item);

            $stock = [];
            $image = [];
            $item_variation = [];
            $item_sec_variation = [];
            $item_variation_stock = [];
            $sub_item_variations_stock = [];
            foreach ($item as $key => $items) {
                $stock[$items->aid] = GlobalController::balance_quantity($items->product_id);
                if(!empty($items->get_product_det->first_image->image)){
                    $image[$items->aid] = $items->get_product_det->first_image->image;
                }
                if (!empty($items->variation_id)) {
                    $item_variation[$items->aid] = ProductVariation::where('id',$items->variation_id)->first();
                    $item_variation_stock[$items->aid] = GlobalController::variation_balance_quantity($items->variation_id);
                   
                }

                 if (!empty($items->second_variation_id)) {
                        $item_sec_variation[$items->aid] = ProductSecondVariation::where('id',$items->second_variation_id)->first();
                }

            }

        $sub_item_image = [];
        $sub_item_stock = [];
        $variations = [];
        $second_variations = [];
        $sec_variation_stock = [];
        $sub_item  = AddOnDealSubItem::select('add_on_deal_sub_items.*', 'p.product_name as p_name', 'p.id as pid', 'add_on_deal_sub_items.id as sid')
                    ->leftjoin('products as p','add_on_deal_sub_items.product_id','p.id')
                    ->where(function($query) {
                        $query->where('add_on_deal_sub_items.status','1')
                              ->orWhere('add_on_deal_sub_items.status','2');
                    })
                    ->where('add_on_id', $id)
                    ->get();

        $original_price = [];
        $current_price = [];

        foreach ($sub_item as $key => $items) {
            $sub_item_stock[$items->sid] = GlobalController::balance_quantity($items->product_id);
            if (!empty($items->variation_id)) {
                $sub_item_stock[$items->sid] = GlobalController::variation_balance_quantity($items->variation_id);
                if (!empty($items->second_variation_id)) {
                    $sub_item_stock[$items->sid] = GlobalController::second_variation_balance_quantity($items->second_variation_id);
                }
            }
            if(!empty($items->get_product_det->first_image->image)){
                $sub_item_image[$items->sid] = $items->get_product_det->first_image->image;
            }
            if (!empty($items->variation_id)) {
                $variations[$items->sid] = ProductVariation::where('id',$items->variation_id)->first();
                if (!empty($items->second_variation_id)) {
                    $second_variations[$items->sid] = ProductSecondVariation::where('id',$items->second_variation_id)->first();
                }
            }

            $original_price[$items->sid] = GlobalController::get_product_pricing(md5($items->product_id), "", $items->variation_id, $items->second_variation_id);

            $current_price[$items->sid] = GlobalController::get_add_on_sub_item_price("", $items->add_on_id, $items->product_id, $items->variation_id, $items->second_variation_id);
        }

        $count_sub_items = count($sub_item);

        return view('backend.promotions.add_on_deal_edit',['add_on_deal'=>$add_on_deal,'item'=>$item,'count'=>$count,'sub_item'=>$sub_item,'count_sub_items'=>$count_sub_items],compact('stock','image','sub_item_image','sub_item_stock','variations','second_variations','item_variation','item_sec_variation','item_variation_stock','sub_item_variations_stock','sec_variation_stock',
            'original_price',
            'current_price'));
        }catch(Throwable $e){
            $message = $e->getMessage();
            toastr::error($message);
            return redirect()->back();
        }
    }

    public function update_deal(Request $request)
    {
        DB::beginTransaction();
        try {
            if (!empty($request->deal_id)) {
                $input = [
                    'promotion_name' => $request->promotion_name,
                    'start_date' => date('Y-m-d H:i:s', strtotime($request->start_date)),
                    'end_date' => date('Y-m-d H:i:s', strtotime($request->end_date)),
                ];
    
                $update = AddOnDeal::where('id', $request->deal_id)->update($input);
    
                if ($update) {
                    for ($z = 0; $z < count($request->sid); $z++) {
                        $add_on_sub_item = AddOnDealSubItem::find($request->sid[$z]);
                        if ($add_on_sub_item) {
                            $add_on_sub_item->add_on_price = $request->add_on_price[$add_on_sub_item->id];
                            $add_on_sub_item->add_on_discount = $request->add_on_discount[$add_on_sub_item->id];
                            $add_on_sub_item->purchase_limit = $request->purchase_limit[$add_on_sub_item->id];
                            $add_on_sub_item->save();
                        } else {
                            throw new \Exception("Add-on sub item not found for ID: " . $request->sid[$z]);
                        }
                    }
    
                    DB::commit();
                    Toastr::success('Add-on deal updated');
                    return redirect()->route('add_on_deal');
                } else {
                    throw new \Exception("Failed to update add-on deal");
                }
            } else {
                throw new \Exception("Missing deal ID");
            }
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Error updating add-on deal: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }
}
