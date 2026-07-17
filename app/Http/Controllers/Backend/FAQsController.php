<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Admin;
use App\Merchant;
use App\User;
use App\Staff;
use App\Affiliate;
use App\VerifyCode;
use App\State;
use App\SettingRefferalReward;
use App\AgentLevel;
use App\SettingMerchantBonus;
use App\AffiliateCommission;
use App\AgentRebateHistory;
use App\Transaction;
use App\MemberWallet;
use App\AffiliateDual;
use App\UserShippingAddress;
use App\SettingNewCustomerPromotion;
use App\Promotion;
use App\AppliedPromotion;
use App\TblCountry;
use App\Faq;

use App\Http\Controllers\GlobalController;

use Validator, Redirect, Toastr, DB, File, Auth, Hash;

class FAQsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $faqs = Faq::select('faqs.*')
                     ->where('faqs.status', '!=', '3')
                     ->orderBy('created_at', 'desc');

        $queries = [];
        $columns = [
            'type',
            'per_page'
        ];

        $per_page = 10;
        if(!empty(request('per_page'))){
            $per_page = request('per_page');
        }

        foreach($columns as $column){
            if(request()->has($column) && !empty(request($column))){
                if($column == 'type'){
                    $faqs = $faqs->where('type', 'like', "%".request($column)."%");
                }elseif(request($column) == 'per_page'){
                    $faqs = $faqs->paginate($per_page);            
                }

                $queries[$column] = request($column);

            }
        }

        // if(!empty(request('per_page'))){
        //     $faqs = $faqs->appends($queries);
        // }else{
        // }
        $faqs = $faqs->paginate($per_page)->appends($queries);

        $get_type = [];
        foreach($faqs as $faq){
            $gt = GlobalController::faqs_desctiption();
            $get_type[$faq->id] = $gt[$faq->type];
        }

        $faqs_desctiption = GlobalController::faqs_desctiption();

        return view('backend.faqs.index', ['faqs'=>$faqs,
                                           'faqs_desctiption'=>$faqs_desctiption],
                                           compact('get_type'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $states = State::get();

        // $countries = TblCountry::get();
        $countries = GlobalController::global_countries();

        $faqs_desctiption = GlobalController::faqs_desctiption();

        return view('backend.faqs.create', ['states'=>$states, 
                                            'countries'=>$countries,
                                            'faqs_desctiption'=>$faqs_desctiption]);
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
        try{

            $validator = Validator::make($request->all(), [
                'type' => 'required',
                'question' => ['required'],
                'answer' => ['required'],
                'question_cn' => ['required'],
                'answer_cn' => ['required']
            ]);

            if ($validator->fails()) {
                return Redirect::back()->withInput(Input::all())->withErrors($validator);
            }

            \DB::beginTransaction();

            $faqs = new Faq();
            $faqs->type = $request->type;
            $faqs->question = $request->question;
            $faqs->answer = $request->answer;
            $faqs->question_cn = $request->question_cn;
            $faqs->answer_cn = $request->answer_cn;
            $faqs->save();

            \DB::commit();
        }catch (\Exception $e){
            \DB::rollback();
            return Redirect::back()->withInput(Input::all())->withErrors($e->getMessage());
        }catch(\Error $e){
            \DB::rollback();
            return Redirect::back()->withInput(Input::all())->withErrors($e->getMessage());
        }

        Toastr::success($translation_data['backendlang']['backendlang']['FAQs Created'] ?? "FAQs Created!");
        return redirect()->route('setting_all_faq.setting_all_faqs.index');
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
        $faq = Faq::select('faqs.*')
                  ->where('id', $id)
                  ->first();

        $faqs_desctiption = GlobalController::faqs_desctiption();

        return view('backend.faqs.edit', ['faq'=>$faq,
                                          'faqs_desctiption'=>$faqs_desctiption]);
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
        try{

            $validator = Validator::make($request->all(), [
                'type' => 'required',
                'question' => ['required'],
                'answer' => ['required'],
                'question_cn' => ['required'],
                'answer_cn' => ['required']
            ]);

            if ($validator->fails()) {
                return Redirect::back()->withInput(Input::all())->withErrors($validator);
            }

            \DB::beginTransaction();

            $faqs = Faq::find($id);
            $faqs->type = $request->type;
            $faqs->question = $request->question;
            $faqs->answer = $request->answer;
            $faqs->question_cn = $request->question_cn;
            $faqs->answer_cn = $request->answer_cn;
            $faqs->save();

            \DB::commit();
        }catch (\Exception $e){
            \DB::rollback();
            return Redirect::back()->withInput(Input::all())->withErrors($e->getMessage());
        }catch(\Error $e){
            \DB::rollback();
            return Redirect::back()->withInput(Input::all())->withErrors($e->getMessage());
        }

        Toastr::success($translation_data['backendlang']['backendlang']['Updated'] ?? "Updated" . "!");
        return redirect()->route('setting_all_faq.setting_all_faqs.edit', $id);
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
