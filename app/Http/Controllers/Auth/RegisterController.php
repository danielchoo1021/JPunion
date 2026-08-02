<?php

namespace App\Http\Controllers\Auth;

use App\Admin;
use App\Agent;
use App\User;
use App\Merchant;
use App\Corporate;
use App\Affiliate;
use App\AgentLevel;
use App\State;
use App\WebsiteSetting;
use App\UserShippingAddress;
use App\Product;
use App\PackageItem;
use App\TransactionPackage;
use App\Transaction;
use App\TransactionDetail;

use App\Http\Controllers\GlobalController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use DB, Auth, Session, Redirect, Toastr;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the application registration form.
     *
     * This overrides Illuminate\Foundation\Auth\RegistersUsers::showRegistrationForm()
     * (a class method silently wins over a trait method of the same name -
     * no conflict). Previously this logic only existed as a hand-edited copy
     * inside vendor/laravel/ui/auth-backend/RegistersUsers.php, which is not
     * tracked by git and gets wiped out by composer install/update. Living
     * here means it survives a fresh `composer install` and deploys via git
     * like the rest of the app.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        $check_authorize = GlobalController::check_authorize();
        if($check_authorize == 1){
            return Redirect::to('https://demoaccount.vesson.my/admin_login');
        }else{
            if(!empty(request('vm'))){
                return redirect()->route(request()->route()->getName());
            }
        }

        $countries = GlobalController::global_countries();

        $states = State::get();
        $refferer_name = "";
        if(!empty(request('p'))){
            $m = Agent::where(DB::raw('CONCAT(display_code, display_running_no)'), request('p'))->where('status', '1')->first();

            $a = Admin::where(DB::raw('CONCAT(display_code, display_running_no)'), request('p'))->where('status', '1')->first();

            $u = User::where(DB::raw('CONCAT(display_code, display_running_no)'), request('p'))->where('status', '1')->first();

            if(!empty($m->id)){
                $refferer_name = $m->f_name;

                if($m->status == 55){
                  return redirect()->route('home')->with('status', '1');
                }
            }

            if(!empty($a->id)){
                $refferer_name = $a->f_name.' '.$a->l_name;
            }

            if(!empty($u->id)){
                $refferer_name = $u->f_name;
            }
        }

        if(Auth::guard('agent')->check() || Auth::guard('web')->check() || Auth::guard('admin')->check()){
            Toastr::warning("You're already logged in. If you want to create a new account, please log out first.");
            return redirect()->route('home');
        }

        return view('auth.register', ['countries'=>$countries, 'refferer_name'=>$refferer_name, 'states'=>$states]);
    }

    /**
     * Handle a registration request for the application.
     *
     * Overrides Illuminate\Foundation\Auth\RegistersUsers::register() for
     * the same reason as showRegistrationForm() above - see that docblock.
     * Since this class defines register() directly, the trait's default
     * register()/create()/validator() flow is never reached, so this method
     * does its own inline validation and record creation instead of calling
     * $this->validator()/$this->create().
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'f_name' => 'required',
            'phone' => ['required', 'unique:agents', 'unique:users'],
            'email' => ['required', 'unique:agents', 'unique:users'],
            'password' => ['required', 'min:6'],
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }


        try{
            DB::beginTransaction();
            $website_setting = WebsiteSetting::find(1);

            $master_id = "";
            $status = '';
            $type = '';

            if(!empty($request->master_id)){
                $admin = Admin::where(DB::raw("CONCAT(display_code, display_running_no)"), $request->master_id)->where('status', '1')->first();
                $agent = Agent::where(DB::raw("CONCAT(display_code, display_running_no)"), $request->master_id)->where('status', '1')->first();
                $member = User::where(DB::raw("CONCAT(display_code, display_running_no)"), $request->master_id)->where('status', '1')->first();

                if(empty($agent->id) && empty($admin->id) && empty($member->id)){
                    throw new \Exception("Referral Code Not Exists");
                }

                if(!empty($admin->id)){
                    $master_id = $admin->code;
                }elseif(!empty($agent->id)){
                    $master_id = $agent->code;
                }elseif(!empty($member->id)){
                    $master_id = $member->code;
                }
            }else{
                $master_id = "AD000001";
            }

            // Check phone number
            $phone = $request->phone;
            if(substr($phone, 0, 1) == '0'){
                $phone = substr($phone, 1);

                $member = User::where('phone', $phone)->first();
                $agent = Agent::where('phone', $phone)->first();

                if(!empty($member->id) || !empty($agent->id)){
                    return Redirect::back()->withInput($request->all())->withErrors('The phone has already been taken.');
                }
            }

            if($request->role == 1){
                $new_user = new User();

                $code = GlobalController::MemberCode();
                // Registering via an agent's shared QR/link ("p" param,
                // resolved into $agent above) gets a code scoped to that
                // agent (e.g. AGT01-0001) instead of the old global
                // Mb-prefixed sequence - see
                // GlobalController::resolveMemberDisplayCode().
                $display_code = GlobalController::resolveMemberDisplayCode(!empty($agent->id) ? $agent : null);
                $status = 1;
                $type = "web";

                $new_user->lvl = "0";
            }else{
                $new_user = new Agent();

                $code = GlobalController::AgentCode();
                $display_code = GlobalController::AgentDisplayCode();
                $status = 99;
                $type = "agent";
            }


            $authorise_merchant = !empty($_COOKIE['vmerchant']) ? $_COOKIE['vmerchant'] : '';

            $get_authorise_status = GlobalController::check_autorize_status($authorise_merchant);
            if($get_authorise_status['status'] == 1){
            $new_user->dual_master_id = !empty($get_authorise_status['result']['code']) ? $get_authorise_status['result']['code'] : '';
            }

            $new_user->master_id = $master_id;
            $new_user->code = $code;
            $new_user->display_code = $display_code[0];
            $new_user->display_running_no = $display_code[1];
            $new_user->email = $request->email;
            $new_user->password = Hash::make($request->password);
            $new_user->f_name = $request->f_name;
            $new_user->ic = $request->ic;
            $new_user->gender = $request->gender;
            $new_user->country_code = $request->country_code;
            $new_user->phone = $phone;
            $new_user->dob = $request->dob;
            $new_user->status = $status;

            $new_user->save();


            if($status == 1){
                $add_affiliates = GlobalController::add_affiliates($new_user->code, $new_user->master_id);
                if($add_affiliates != 'ok'){
                    throw new \Exception($add_affiliates);
                }
            }

            if(!empty($request->address)){
                $address_input = new UserShippingAddress();
                $address_input->user_id = $new_user->code;
                $address_input->default = '1';
                $address_input->f_name = $new_user->f_name;
                $address_input->phone = $new_user->phone;
                $address_input->country_code = $new_user->country_code;
                $address_input->email = $new_user->email;
                $address_input->address = $request->address;
                $address_input->postcode = $request->postcode;
                $address_input->city = $request->city;
                $address_input->state = $request->state;
                $address_input->country = $request->country;
                $address_input->save();
            }

            if($request->role == 2 && !empty($address_input->id)){
                $product = Product::select('products.*')
                                  ->where(DB::raw('md5(products.id)'), $request->selected_starter)
                                  ->first();

                if(!empty($request->selected_starter) && empty($product->id)){
                    throw new \Exception("Register Product Error.");
                }

                if(!empty($product->id) && $website_setting->registration_product_enable == 1){
                    $total_weight = $product->weight * $request->quantity;
                    $get_discount_pricing[$product->id] = GlobalController::get_product_pricing(md5($product->id), "", 0, 0, "", "", '1');
                    $original_pricing[$product->id] = GlobalController::get_product_pricing(md5($product->id), "");

                    if (empty($get_discount_pricing[$product->id])) {
                        $get_pricing = $original_pricing[$product->id];
                    } else {
                        $get_pricing = $get_discount_pricing[$product->id];
                    }

                    $totalshipping_fees = 0;
                    if(!empty($product->level_up)){
                    $totalshipping_fees = GlobalController::get_shipping_fee($address_input->state, $total_weight);
                    }

                    $total_subtotal = $get_pricing['product_price'] * $request->quantity;

                    $transaction = new Transaction();
                    $transaction->user_id = $new_user->code;
                    $transaction->transaction_no = GlobalController::GenerateTransactionNo();
                    $transaction->weight = $total_weight;
                    $transaction->sub_total = $total_subtotal;
                    $transaction->shipping_fee = $totalshipping_fees;
                    $transaction->grand_total = $total_subtotal + $totalshipping_fees;
                    $transaction->address_name = $address_input->f_name;
                    $transaction->address = $address_input->address;
                    $transaction->postcode = $address_input->postcode;
                    $transaction->city = $address_input->city;
                    $transaction->state = $address_input->state;
                    $transaction->country = $address_input->country;
                    $transaction->phone = $address_input->phone;
                    $transaction->email = $address_input->email;
                    $transaction->register_product = 1;
                    if($website_setting->registration_package_hierarchy_bonus != 1){
                    $transaction->commission_disabled = 1;
                    }
                    $transaction->status = 98;

                    if(!empty($request->file('bank_slip'))){
                        $files = $request->file('bank_slip');
                        $name = $files->getClientOriginalName();
                        $exp = explode(".", $name);
                        $file_ext = end($exp);
                        $name = md5($name.date('Y-m-d H:i:s')).'.'.$file_ext;
                        $files->move(GlobalController::get_image_path("uploads/bank_slip/".$new_user->code."/"), $name);

                        $transaction->bank_slip = "uploads/bank_slip/".$new_user->code."/".$name;
                    }else{
                        throw new \Exception("Please upload bank slip to continue.");
                    }

                    $transaction->save();

                    $details = new TransactionDetail();
                    $details->transaction_id = $transaction->id;
                    $details->product_image = !empty($product->first_image) ? $product->first_image->image : '';
                    $details->product_id = !empty($product->id) ? $product->id : '';
                    $details->item_code = !empty($product->id) ? $product->item_code : '';
                    $details->product_code = !empty($product->id) ? $product->product_code : '';
                    $details->unit_weight = !empty($product->id) ? $product->weight : '';
                    $details->product_name = !empty($product->id) ? $product->product_name : '';
                    $details->unit_price = !empty($get_pricing['product_price']) ? $get_pricing['product_price'] : 0;
                    $details->quantity = $request->quantity;
                    $details->costing_price = !empty($product->costing_price) ? $product->costing_price : 0;
                    $details->level_up = !empty($product->level_up) ? $product->level_up : 0;
                    $details->store_in_stock = !empty($product->store_in_stock) ? $product->store_in_stock : 0;

                    $details->save();

                    $PackageItems = PackageItem::where('product_id', $product->id)->get();

                    foreach($PackageItems as $items){
                        $package_item = new TransactionPackage();
                        $package_item->detail_id = $details->id;
                        $package_item->product_id = $items->products;
                        $package_item->voucher_id = $items->voucher_id;
                        $package_item->quantity = $items->qty;
                        $package_item->save();
                    }

                    $new_user->register_transaction = $transaction->transaction_no;
                    $new_user->save();
                }

            }

            if($status == 1){
                if(empty($transaction->commission_disabled)){
                    $referral_bonus = GlobalController::referral_bonus($new_user->code, $new_user->lvl);
                    if($referral_bonus != 'ok'){
                        throw new \Exception($referral_bonus);
                    }
                }
            }

            DB::commit();

        }catch (\Exception $e){
            DB::rollback();
            return Redirect::back()->withInput($request->all())->withErrors($e->getMessage().' - '.$e->getLine());
        }catch(\Error $e){
            return Redirect::back()->withInput($request->all())->withErrors($e->getMessage().' - '.$e->getLine());
        }

        if($status == 1){
            if(Auth::guard($type)->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)){
                Toastr::success('Register Successfully');
            }
        }

        if($status == 99){
            Toastr::success('Register Successfully! Please wait admin for approval');
        }

        return redirect()->route('home');
    }
}
