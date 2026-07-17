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
use App\SettingFeedback;
use App\SettingFeedbackDetail;

use Validator, Redirect, Toastr, DB, File, Auth, Hash;

class FeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $feedbacks = SettingFeedback::where('status', '1');

        $per_page = 10;
        if(!empty(request('per_page'))){
            $per_page = request('per_page');
        }

        $queries = [];
        $columns = [
            'title'
        ];

        foreach($columns as $column){
            if(request()->has($column) && !empty(request($column))){
                if($column == 'title'){
                    $feedbacks = $feedbacks->where('title', 'like', "%".request($column)."%");
                }

                $queries[$column] = request($column);
            }
        }


        $feedbacks = $feedbacks->orderBy('created_at', 'desc');
        $feedbacks = $feedbacks->paginate($per_page)->appends($queries);
        // echo 123;
        // exit();


        return view('backend.feedbacks.index', ['feedbacks'=>$feedbacks]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.feedbacks.create');
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
            'title' => 'required',
            'products' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }

        $input = $request->all();

        $setting = SettingFeedback::create($input);

        $move = SettingFeedbackDetail::where('status', '99')->get();
        foreach($move as $key => $value){
            $files = $value->image;
            $explode = explode('/', $files);

            if (!File::exists(public_path('uploads/'.$setting->id))) {
                File::makeDirectory('uploads/'.$setting->id, $mode = 0777, true, true);
            }
            
            rename($value->image, 'uploads/'.$setting->id.'/'.end($explode));
            $updateI = SettingFeedbackDetail::find($value->id);
            $updateI = $updateI->update(['image'=>'uploads/'.$setting->id.'/'.end($explode),
                                         'feedback_id' => $setting->id, 
                                         'status'=> '1']);
        }


        Toastr::success("$setting->title Create Successfully!");
        return redirect()->route('feedback.feedbacks.index');
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
        $feedback = SettingFeedback::find($id);

        return view('backend.feedbacks.edit', ['feedback'=>$feedback]);
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
            'title' => 'required',
            'products' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }

        $input = $request->all();

        $update = SettingFeedback::find($id);        
        $update = $update->update($input);

        Toastr::success("Update Successfully!");
        return redirect()->route('feedback.feedbacks.edit', $id);
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
