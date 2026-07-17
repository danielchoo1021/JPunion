<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Admin;
use App\Merchant;
use App\Staff;
use App\Permission;

use Auth, Toastr, DB;

use App\Http\Controllers\GlobalController;

class AdminLoginController extends Controller
{
	public function __construct()
    {
        // $this->middleware('guest:admin');
    }

    public function ShowAdminLogin()
    {
        // $check_authorize = GlobalController::check_authorize();
        // if($check_authorize == 1){
        //     return redirect()->route('admin_login');
        // }

        if(Auth::guard('web')->check()){
            return redirect()->route('home');
        }

        if(Auth::guard('merchant')->check())
        {
            return redirect()->route('admin.admins.index');
        }

        if(Auth::guard('admin')->check()){
            return redirect()->route('admin.admins.index');
        }

    	return view('auth.admin_login');
    }

    public function login(Request $request)
    {
    	$translation_data = GlobalController::get_translations();
        $this->validate($request, [
    		'email' => 'required|email',
    		'password' => 'required'
    	]);

        if(Auth::guard('web')->check()){
            return redirect()->route('home');
        }

        if(Auth::guard('merchant')->check())
        {
            return redirect()->route('admin.admins.index');
        }

        if(Auth::guard('admin')->check())
        {
            return redirect()->route('admin.admins.index');
        }

        $admin = Admin::where('email', $request->email)
                      ->where('status', '1')
                      ->exists();

        $merchant = Merchant::where('email', $request->email)
                            ->where('status', '1')
                            ->where(function($query){
                                $query->where('active_period', 0)
                                      ->orWhere(function($query2){
                                            $query2->where(DB::raw('DATE_ADD(created_at, INTERVAL active_period DAY)'), '>=', date('Y-m-d H:i:s'))
                                                   ->where('active_period', '>', 0);
                                      });
                                })
                            ->exists();

        $staff = Staff::where('email', $request->email)
                      ->where('status', '1')
                      ->exists();

        $merchantD = Merchant::where('email', $request->email)
                             ->where('status', '1')
                             ->first();

        $staffD = Staff::where('email', $request->email)
                             ->where('status', '1')
                             ->first();
        
        if($admin == 1 || $merchant == 1 || $staff == 1){
            if((!empty($merchantD->id) && empty($merchantD->permission_lvl))){
                return redirect()->back()->withErrors( $translation_data['backendlang']['backendlang']['You do not have permission to log in'] ?? "You do not have permission to log in");
            }

        	if(Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)){
                Toastr::success($translation_data['backendlang']['backendlang']['Login Successfully'] ?? "Login Successfully!");
        	   
                return $this->checkPermission(1, 'AD000001');
        	}elseif(Auth::guard('merchant')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)){

                // if(empty($_COOKIE['vmerchant'])){
                $cookie_name = "vmerchant";
                $cookie_value = md5($merchantD->id);

                // Calculate the expiration time (1 day from the current time)
                $expiration_time = time() + (86400 * 30); // 86400 seconds = 24 hours = 1 day

                // Set the cookie with the expiration time
                setcookie($cookie_name, $cookie_value, $expiration_time, "/");
                // }

                Toastr::success($translation_data['backendlang']['backendlang']['Login Successfully'] ?? "Login Successfully!");
                return $this->checkPermission($merchantD->permission_lvl, $merchantD->code);
            }elseif(Auth::guard('staff')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)){

                Toastr::success($translation_data['backendlang']['backendlang']['Login Successfully'] ?? "Login Successfully!");   
                return $this->checkPermission($staffD->permission_lvl, $staffD->code);
            }   
        }
    	return redirect()->back()->withErrors($translation_data['backendlang']['backendlang']['Username or Password Incorrect'] ?? "Username or Password Incorrect");
    }

    public function admin_logout()
    {
        if(Auth::guard('admin')->check()){
            Auth::guard('admin')->logout();
        }else{
            Auth::guard('merchant')->logout();
        }

        return redirect()->route('admin_login');
    }

    public function checkPermission($permmission)
    {
        $checks = Permission::where('permission_lvl', $permmission)->orderBy('id', 'asc')->first();

        if(!empty($checks->id)){
            // return $checks->page;
            if($checks->page == 'dashboard'){
                return redirect()->route('dashboard.dashboards.index');
            }elseif($checks->page == 'profile'){
                return redirect()->route('admin.admins.index');
            }elseif($checks->page == 'permission-control'){
                return redirect()->route('user_permission.user_permissions.index');
            }elseif($checks->page == 'agent-list'){
                return redirect()->route('merchant.merchants.index');
            }elseif($checks->page == 'agent-add'){
                return redirect()->route('merchant.merchants.create');
            }elseif($checks->page == 'agent-pending'){
                return redirect()->route('pending_merchant');
            }elseif($checks->page == 'member-list'){
                return redirect()->route('member.members.index');
            }elseif($checks->page == 'member-add'){
                return redirect()->route('member.members.create');
            }elseif($checks->page == 'member-pending'){
                return redirect()->route('pending_member');
            }elseif($checks->page == 'staff-list'){
                return redirect()->route('staff.staffs.index');
            }elseif($checks->page == 'staff-add'){
                return redirect()->route('staff.staffs.create');
            }elseif($checks->page == 'product-list'){
                return redirect()->route('product.products.index');
            }elseif($checks->page == 'product-add'){
                return redirect()->route('product.products.create');
            }elseif($checks->page == 'product-packages'){
                return redirect()->route('packages_list');
            }elseif($checks->page == 'product-packages-add'){
                return redirect()->route('packages_add');
            }elseif($checks->page == 'gift-pack-list'){
                return redirect()->route('bundle.bundles.index');
            }elseif($checks->page == 'gift-pack-add'){
                return redirect()->route('bundle.bundles.create');
            }elseif($checks->page == 'category-list'){
                return redirect()->route('category.categories.index');
            }elseif($checks->page == 'category-add'){
                return redirect()->route('category.categories.create');
            }elseif($checks->page == 'sub-category-list'){
                return redirect()->route('sub_category.sub_categories.index');
            }elseif($checks->page == 'sub-category-add'){
                return redirect()->route('sub_category.sub_categories.create');
            }elseif($checks->page == 'brand-list'){
                return redirect()->route('brand.brands.index');
            }elseif($checks->page == 'brand-add'){
                return redirect()->route('brand.brands.create');
            }elseif($checks->page == 'promotion-list'){
                return redirect()->route('promotion.promotions.index');
            }elseif($checks->page == 'promotion-add'){
                return redirect()->route('promotion.promotions.create');
            }elseif($checks->page == 'transaction-list'){
                return redirect()->route('transaction.transactions.index');
            }elseif($checks->page == 'withdrawal-list'){
                return redirect()->route('withdrawal_list');
            }elseif($checks->page == 'bank-list'){
                return redirect()->route('payment_bank.payment_banks.index');
            }elseif($checks->page == 'bank-add'){
                return redirect()->route('payment_bank.payment_banks.create');
            }elseif($checks->page == 'sales-report'){
                return redirect()->route('sales_report');
            }elseif($checks->page == 'order-report'){
                return redirect()->route('order_report');
            }elseif($checks->page == 'commission-report'){
                return redirect()->route('commission_report');
            }elseif($checks->page == 'affiliate-list'){
                return redirect()->route('user_permission.user_permissions.index');
            }elseif($checks->page == 'agent-level'){
                return redirect()->route('setting_agent_level');
            }elseif($checks->page == 'merchant-commission'){
                return redirect()->route('setting_merchant_commission');
            }elseif($checks->page == 'setting-banner'){
                return redirect()->route('setting_banner');
            }elseif($checks->page == 'shipping-fee'){
                return redirect()->route('setting_shipping_fee');
            }elseif($checks->page == 'setting-uom'){
                return redirect()->route('setting_uom');
            }elseif($checks->page == 'set-pickup-address'){
                return redirect()->route('setting_pick_up_address');
            }elseif($checks->page == 'main-page-setting'){
                return redirect()->route('setting_signature_dish');
            }else{
                abort(404);
            }
        }
    }
}
