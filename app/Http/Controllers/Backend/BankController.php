<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// use Illuminate\Support\Facades\Input;
use App\BankAccount;
use App\PaymentBank;
use Validator, Redirect, Toastr, DB, File, Auth;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $banks = PaymentBank::where('status', '!=', '3');
                            // ->orderBy('created_at','desc');
        $queries = [];
        $columns = [
            'bank_name', 'bank_name_desc', 'bank_name_asc', 'status'
        ];

        foreach($columns as $column){
            if(request()->has($column) && !empty(request($column))){
                if($column == 'bank_name_desc'){
                    $banks = $banks->orderBy('payment_banks.bank_name', 'desc');
                }elseif($column == 'bank_name_asc'){
                    $banks = $banks->orderBy('payment_banks.bank_name', 'asc');
                }else{
                $banks = $banks->where($column, 'like', "%".request($column)."%");
                }

                $queries[$column] = request($column);

            }
        }
        $per_page = 10;
        if(!empty(request('per_page'))){
            $per_page = request('per_page');
        }
        $banks = $banks->orderBy('created_at','desc');
        $banks = $banks->paginate($per_page)->appends($queries);


        return view('backend.banks.index', ['banks'=>$banks]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.banks.create');
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
        $input['bank_name'] = trim($request->bank_name);

        $bank = PaymentBank::create($input);

        

        Toastr::success("Bank $bank->title Create Successfully!");
        return redirect()->route('bank.banks.index');
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
        $payment_bank = PaymentBank::find($id);
        return view('backend.banks.edit', ['payment_bank'=>$payment_bank]);
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


        $update = PaymentBank::find($id);
        $bank_name = $update->title;
        $update = $update->update($input);



        Toastr::success("Bank $bank_name Update Successfully!");
        return redirect()->route('bank.banks.edit', $id);
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
