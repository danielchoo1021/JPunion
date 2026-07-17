<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Facades\Input;
use App\Admin;
use App\Merchant;
use App\User;
use App\Affiliate;
use App\VerifyCode;
use App\State;
use App\SettingRefferalReward;
use App\AgentLevel;
use App\SettingMerchantBonus;
use App\AffiliateCommission;
use App\AgentRebateHistory;
use App\Transaction;
use App\AffiliateDual;
use App\TopupTransaction;
use App\SettingMerchantRebate;
use App\AgentLevelRecord;
use App\SettingJoiningFee;
use App\AdjustCashWallet;
use App\WithdrawalTransaction;
use App\NewsletterHistory;

use Validator, Redirect, Toastr, DB, File, Auth, Mail;

class NewsletterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $merchants = Merchant::select('merchants.*')
                             ->whereNotIn('merchants.status', ['99', '3']);

        $users = User::select('users.*')
                      ->whereNotIn('users.status', ['99', '3'])
                      ->get();

        $queries = [];
        $columns = [
            'code', 'f_name', 'lvl', 'status', 'agent_type', 'ic'
        ];
        foreach($columns as $column){
            if(request()->has($column) && !empty(request($column))){
                if($column == 'f_name'){
                    $merchants = $merchants->where(DB::raw('CONCAT(f_name, " ", l_name)'), 'like', "%".request($column)."%");
                }else{
                    $merchants = $merchants->where($column, 'like', "%".request($column)."%");
                }

                $queries[$column] = request($column);

            }
        }
        $per_page = 10;
        if(!empty(request('per_page'))){
            $per_page = request('per_page');
        }
        $merchants = $merchants->paginate($per_page)->appends($queries);

        return view('backend.newsletters.index', ['merchants'=>$merchants, 'users'=>$users]);
    }

    public function member_list()
    {
        $users = User::select('users.*')
                      ->whereNotIn('users.status', ['99', '3']);

        $queries = [];
        $columns = [
            'code', 'f_name', 'lvl', 'status', 'agent_type', 'ic'
        ];
        foreach($columns as $column){
            if(request()->has($column) && !empty(request($column))){
                if($column == 'f_name'){
                    $users = $users->where(DB::raw('CONCAT(f_name, " ", l_name)'), 'like', "%".request($column)."%");
                }else{
                    $users = $users->where($column, 'like', "%".request($column)."%");
                }

                $queries[$column] = request($column);

            }
        }
        $per_page = 10;
        if(!empty(request('per_page'))){
            $per_page = request('per_page');
        }
        $users = $users->paginate($per_page)->appends($queries);

        return view('backend.newsletters.member_list', ['users'=>$users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $merchants = Merchant::select('merchants.*')
                             ->whereNotIn('merchants.status', ['99', '3'])
                             ->orderBy('merchants.created_at', 'desc')
                             ->get();

        $users = User::select('users.*')
                      ->whereNotIn('users.status', ['99', '3'])
                      ->orderBy('users.created_at', 'desc')
                      ->get();

        $allUsers = $merchants->concat($users);

        return view('backend.newsletters.create', ['allUsers'=>$allUsers]);
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
            'new_newsletter' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }

        $input = [];
        $input['newsletter'] = $request->new_newsletter;

        $new_entry = NewsletterHistory::create($input);


        $merchants = Merchant::select('merchants.*')
                             ->whereNotIn('merchants.status', ['99', '3'])
                             ->orderBy('merchants.created_at', 'desc')
                             ->get();

        $users = User::select('users.*')
                      ->whereNotIn('users.status', ['99', '3'])
                      ->orderBy('users.created_at', 'desc')
                      ->get();

        $allUsers = $merchants->concat($users);

        $admin = Admin::first();

        foreach ($allUsers as $user) {
            if($user->receive_newsletter == 1){
                // $this->sendEmailNotification($user->email, $admin->contact_email, $user->f_name, $request->new_newsletter, "Newsletter");
                Mail::to($user->email)->send(new \App\Mail\SendNewsletter('Kim E Biz Newsletter', $request->new_newsletter));
            }
        }

        Toastr::success("Newsletter Created!");
        return redirect()->route('newsletter.newsletters.create');

    }

    public function newsletter_history()
    {
        $allEntry = NewsletterHistory::where('status', '1')
                                     ->get();

        return view('backend.newsletters.newsletter_history', ['allEntry'=>$allEntry]);
    }

    public function sendEmailNotification($to, $from, $name, $content, $subject)
    {
      $headers = "From: $from";
      $headers = "From: " . $from . "\r\n";
      $headers .= "Reply-To: ". $from . "\r\n";
      $headers .= "MIME-Version: 1.0\r\n";
      // $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
      $headers .= "Content-Type: text/html; charset=UTF-8";
      $headers .= '<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">';

      // $subject = "Testing.";


      $link = 'www.zstore.com';

      $body = "<!DOCTYPE html><html lang='en'><head><meta charset='UTF-8'><title>Express Mail</title></head><body>";
      $body .= "<table style='width: 100%;'>";
      $body .= "<thead style='text-align: center;'><tr><td style='border:none;' colspan='2'>";

      $body .= "</td></tr></thead><tbody><tr>";
      $body .= "<td style='border:none;'> ". $content ." </td></tr>";
      $body .= "<tr>
                  <td style='border:none;'>
                  </td>
                </tr>";
      $body .= "<tr><td></td></tr>
                <tr><td></td></tr>";
      $body .= "<tr><td></td></tr>";
      $body .= "<tr><td></td></tr>";
      $body .= "<tr><td>Regards,</td></tr>";
      $body .= "<tr><td>Kimcafe</td></tr>";
      $body .= "<tr><td></td></tr>";
      // $body .= "<tr><td colspan='2' style='border:none;'>{$cmessage}</td></tr>";
      $body .= "</tbody></table>";
      $body .= "</body></html>";

      $send = mail($to, $subject, $body, $headers);
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
