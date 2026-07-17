<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// use Illuminate\Support\Facades\Input;
use App\BankAccount;
use Validator, Redirect, Toastr, DB, File, Auth;

class PaymentBankController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function index()
    {

        $payment_banks = BankAccount::where('status', '!=', '3')
                                    ->where('user_id', Auth::user()->code)
                                    ->orderBy('created_at','desc');
        $queries = [];
        $columns = [
            'bank_name', 'bank_name_desc', 'bank_name_asc', 'bank_holder_name_desc', 'bank_holder_name_asc', 'bank_account_desc', 'bank_account_asc', 'status'
        ];

        foreach($columns as $column){
            if(request()->has($column) && !empty(request($column))){
                if($column == 'bank_name_desc'){
                    $banks = $banks->orderBy('payment_banks.bank_name', 'desc');
                }elseif($column == 'bank_name_asc'){
                    $banks = $banks->orderBy('payment_banks.bank_name', 'asc');
                }elseif($column == 'bank_holder_name_desc'){
                    $banks = $banks->orderBy('payment_banks.bank_holder_name', 'desc');
                }elseif($column == 'bank_holder_name_asc'){
                    $banks = $banks->orderBy('payment_banks.bank_holder_name', 'asc');
                }elseif($column == 'bank_account_desc'){
                    $banks = $banks->orderBy('payment_banks.bank_account', 'desc');
                }elseif($column == 'bank_account_asc'){
                    $banks = $banks->orderBy('payment_banks.bank_account', 'asc');
                }else{
                $payment_banks = $payment_banks->where($column, 'like', "%".request($column)."%");
                }

                $queries[$column] = request($column);

            }
        }
        $per_page = 10;
        if(!empty(request('per_page'))){
            $per_page = request('per_page');
        }
        $payment_banks = $payment_banks->paginate($per_page)->appends($queries);


        return view('backend.payment_banks.index', ['payment_banks'=>$payment_banks]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.payment_banks.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bank_name' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }

        $input = $request->all();
        $input['user_id'] = Auth::user()->code;
        $input['bank_name'] = trim($request->bank_name);

        $payment_bank = BankAccount::create($input);

        

        Toastr::success("Bank $payment_bank->title Create Successfully!");
        return redirect()->route('payment_bank.payment_banks.index');
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
        $payment_bank = BankAccount::find($id);
        return view('backend.payment_banks.edit', ['payment_bank'=>$payment_bank]);
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
        $validator = Validator::make($request->all(), [
            'bank_name' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }

        $input = $request->all();
        $input['bank_name'] = trim($request->bank_name);


        $update = BankAccount::find($id);
        $bank_name = $update->title;
        $update = $update->update($input);



        Toastr::success("Bank $bank_name Update Successfully!");
        return redirect()->route('payment_bank.payment_banks.edit', $id);
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
}
