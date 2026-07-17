<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator,Redirect,Response,File;
use Socialite, Auth;
use App\User;

class GoogleSocialController extends Controller
{
 public function redirect($provider)
 {
     return Socialite::driver($provider)->redirect();
 }
 public function callback($provider, Request $request)
 {
  if (!$request->has('code') || $request->has('denied') || Auth::check()) {
    return redirect('/');
  }
   $getInfo = Socialite::driver($provider)->user(); 
   $user = $this->createUser($getInfo,$provider); 
   auth()->login($user); 
   return redirect()->to('/');
 }

 function createUser($getInfo,$provider){
 $user = User::where('provider_id', $getInfo->id)->where('status', '1')->first();
 if (!$user) {
      $user = User::create([
         'email'    => $getInfo->email,
         'f_name'     => $getInfo->name,
         'photo'    => str_replace("normal","large", $getInfo->avatar),
         'provider' => $provider,
         'provider_id' => $getInfo->id
     ]);
   }
   return $user;
 }
}
