<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Facades\Input;

use App\Http\Controllers\Controller;

use App\PaymentBank;
use App\Admin;
use App\Merchant;
use App\WebsiteSetting;

use App\Http\Controllers\GlobalController;
use Toastr, Auth, DB, Redirect;

class AdminController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        ini_set('allow_url_fopen', 1);
        
        if(Auth::guard('merchant')->check()){
            $admin = Merchant::find(Auth::user()->id);
            $setting = Merchant::find(Auth::user()->id);
        }else{
            $setting = WebsiteSetting::find(1);
            $admin = Admin::find(1);
        }

        $paymentBanks = PaymentBank::where('status', '1')->get();

        return view('backend.admins.index', ['setting'=>$setting, 'admin'=>$admin,'paymentBanks'=>$paymentBanks]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
        if(isset($request->password) && !empty($request->password)){
            $this->validate($request,
            [
               'password'=> 'confirmed',
            ]);
        }

        try{
            $input = $request->all();
            if(Auth::guard('admin')->check()){
                $admin = Admin::find(1);
            }else{
                $admin = Merchant::find(Auth::user()->id);
            }

            $admin->f_name = $request->f_name;

            if(isset($request->password) && !empty($request->password)){
                $admin->password = Hash::make($request->password);
            }

            if(!empty($request->file('profile_logo'))){
                $files = $request->file('profile_logo'); 
                $name = $files->getClientOriginalName();
                $exp = explode(".", $name);
                $file_ext = end($exp);
                $name = md5($name.date('Y-m-d H:i:s')).'.'.$file_ext;

                $files->move(GlobalController::get_image_path("uploads/profile_logo/"), $name);
                $admin->profile_logo = "uploads/profile_logo/".$name;
            }
            $admin->save();

            if(Auth::guard('admin')->check()){
                $website_setting = WebsiteSetting::find(1);
            }else{
                $website_setting = Merchant::find(Auth::user()->id);
            }
            

            if(!empty($request->file('fav_icon'))){
                $files = $request->file('fav_icon'); 
                $name = $files->getClientOriginalName();
                $exp = explode(".", $name);
                $file_ext = end($exp);
                $name = md5($name.date('Y-m-d H:i:s')).'.'.$file_ext;

                $files->move(GlobalController::get_image_path("uploads/fav_icon/"), $name);
                $website_setting->fav_icon = "uploads/fav_icon/".$name;
            }

            if(!empty($request->file('website_logo'))){
                $files = $request->file('website_logo'); 
                $name = $files->getClientOriginalName();
                $exp = explode(".", $name);
                $file_ext = end($exp);
                $name = md5($name.date('Y-m-d H:i:s')).'.'.$file_ext;

                $files->move(GlobalController::get_image_path("uploads/website_logo/"), $name);
                $website_setting->website_logo = "uploads/website_logo/".$name;
            }

            $website_setting->company_address = $request->address;
            $website_setting->company_phone = $request->phone;
            $website_setting->about_us = $request->about_us;
            // $website_setting->faqs = $request->setting_faqs_description;
            $website_setting->company_registration_no = $request->company_registration_no;
            $website_setting->invoice_name = $request->invoice_name;
            $website_setting->contact_email = $request->contact_email;
            $website_setting->company_phone = $request->company_phone;
            $website_setting->contact_whatsapp = $request->contact_whatsapp;
            $website_setting->privacy_policy_description = $request->privacy_policy_description;
            $website_setting->privacy_policy_description_cn = $request->privacy_policy_description_cn;
            $website_setting->return_policy_description = $request->return_policy_description;
            $website_setting->return_policy_description_cn = $request->return_policy_description_cn;
            $website_setting->shipping_policy_description = $request->shipping_policy_description;
            $website_setting->shipping_policy_description_cn = $request->shipping_policy_description_cn;
            $website_setting->tnc_description = $request->tnc_description;
            $website_setting->tnc_description_cn = $request->tnc_description_cn;
            $website_setting->invoice_notes = $request->invoice_notes;
            $website_setting->invoice_notes_cn = $request->invoice_notes_cn;
            // $website_setting->website_short_description = $request->website_short_description;
            $website_setting->facebook = $request->facebook;
            $website_setting->tiktok = $request->tiktok;
            $website_setting->instagram = $request->instagram;
            $website_setting->youtube = $request->youtube;
            $website_setting->google = $request->google;
            $website_setting->book = $request->book;
            $website_setting->twitter = $request->twitter;
            $website_setting->website_name = $request->website_name;

            $website_setting->bank_name = $request->bank_name;
            $website_setting->bank_account_number = $request->bank_account;
            $website_setting->bank_holder_name = $request->bank_holder_name;

            $website_setting->tin_no = $request->tin_no;

            $website_setting->save();
            
            \DB::commit();

        } catch (\Exception $e){
            \DB::rollback();
            return Redirect::back()->withInput($request->all())->withErrors($e->getMessage());
        } catch (\Error $e){
            \DB::rollback();
            return Redirect::back()->withInput($request->all())->withErrors($e->getMessage());
        }

        Toastr::success("Profile Updated!");
        return redirect()->route('admin.admins.index');
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

    public function setting_faqs()
    {
        $setting = WebsiteSetting::find(1);
        return view('backend.admins.setting_faqs', ['setting'=>$setting]);
    }


    public function save_setting_faqs(Request $request)
    {
        $translation_data = GlobalController::get_translations();
        $setting = WebsiteSetting::find(1);

        if(!empty($setting->id)){
            $setting = $setting->update(['faqs'=>$request->setting_faqs_description]);
        }else{
            $createSetting = WebsiteSetting::create(['faqs'=>$request->setting_faqs_description]);
        }

        Toastr::success($translation_data['backendlang']['backendlang']['FAQs_Updated'] ?? "FAQs Updated!");
        return redirect()->route('setting_faqs');
    }
}
