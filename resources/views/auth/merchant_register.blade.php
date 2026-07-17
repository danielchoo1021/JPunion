@extends('layouts.app')
@section('css')
<style type="text/css">
    .cat_menu_container ul {
        display: block;
        position: absolute;
        top: 100%;
        left: 0;
        visibility: hidden;
        opacity: 0;
        min-width: 100%;
        background: #FFFFFF;
        box-shadow: 0px 10px 25px rgba(0,0,0,0.1);
        -webkit-transition: opacity 0.3s ease;
        -moz-transition: opacity 0.3s ease;
        -ms-transition: opacity 0.3s ease;
        -o-transition: opacity 0.3s ease;
        transition: all 0.3s ease;
    }
    .cat_menu_container:hover .cat_menu {
        visibility: visible;
        opacity: 1;
    }

    .select2-container{
        width: 100% !important;
    }

    .select2-container--default .select2-selection--single{
        padding: 5px;
        height: 39px;
    }

    .nice-select{
        display: none;
    }

    .main-content{
        margin-top: 120px;
    }
</style>
@endsection
@section('content')
<div class="container">
    <div class="main-content">
        <div class="container-box">
            <form method="POST" action="{{ route('register') }}" id="register-form" enctype="multipart/form-data">
                @csrf
                <h3 align="center" class="header-login">Register Agent Account</h3>
                <br>
                <div class="register-page merchant">
                    <div class="form-group">
                        @if($errors->any())
                          <div class="alert alert-danger">{!! implode('<br/>', $errors->all(':message')) !!}</div>
                        @endif
                        <input type="hidden" name="role" value="2">
                    </div>

                    <div class="form-group" style="display: none;">
                        <div class="row">
                            <div class="col-6">
                                <input type="radio" name="joining_type" value="1"> Joining Fee
                            </div>
                            <div class="col-6">
                                <input type="radio" name="joining_type" value="2" checked> Purchase Products
                            </div>
                        </div>
                    </div>

                    <div class="form-group joining_fee_area" style="display: none;">
                        <select class="form-control joining_fee" name="joining_fee">
                            @foreach($get_joining_fees as $get_joining_fee)
                                <option value="{{ $get_joining_fee->id }}" data-id="{{ $get_joining_fee->target }}" data-bonus="{{ $get_joining_fee->comm_amount }}">
                                    RM {{ number_format($get_joining_fee->target, 2) }} 
                                    @if(!empty($get_joining_fee->comm_amount))
                                    +Bonus (RM {{ $get_joining_fee->comm_amount }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>{{ isset($data['lang']['lang']['complete_name']) ? $data['lang']['lang']['complete_name'] :'全名'}} ({{ isset($data['lang']['lang']['please_enter_name']) ? $data['lang']['lang']['please_enter_name'] :'请填写身份证上的名字'}})<span class="important-text">*</span></label>
                        <input type="text" class="form-control f_name required-feild" placeholder="{{ isset($data['lang']['lang']['complete_name']) ? $data['lang']['lang']['complete_name'] :'全名'}} ({{ isset($data['lang']['lang']['please_enter_name']) ? $data['lang']['lang']['please_enter_name'] :'请填写身份证上的名字'}})" name="f_name" value="{{ old('f_name') }}">
                    </div>
                    
                    <div class="form-group">
                        <label>{{ isset($data['lang']['lang']['email_address']) ? $data['lang']['lang']['email_address'] :'电子邮件'}} <span class="important-text">*</span></label>
                        <input type="text" class="form-control email required-feild" placeholder="{{ isset($data['lang']['lang']['email_address']) ? $data['lang']['lang']['email_address'] :'电子邮件'}}" name="email" value="{{ old('email') }}">
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-6">
                                <label>{{ isset($data['lang']['lang']['country']) ? $data['lang']['lang']['country'] :'国家'}} <span class="important-text">*</span></label>
                                <select class="form-control select2 country_code" name="country_code" id="country_code" data-live-search="true">
                                    @foreach($countries as $country)
                                    <option value="{{ $country->country_contact }}"
                                        {{ ( $country->country_id == '160') ? 'selected' : '' }}
                                        > (+{{ $country->country_contact }}) {{ $country->country_name }} </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6">
                                <label>{{ isset($data['lang']['lang']['phone_number']) ? $data['lang']['lang']['phone_number'] :'电话号码'}} <span class="important-text">*</span></label>
                                <input type="text" class="form-control required-feild phone" placeholder="{{ isset($data['lang']['lang']['example']) ? $data['lang']['lang']['example'] :'例'}}: 171234567" name="phone" value="{{ old('phone') }}"onkeypress="return isNumberKey(event)">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>{{ isset($data['lang']['lang']['gender']) ? $data['lang']['lang']['gender'] :'性别'}}: <span class="important-text">*</span></label>
                        <select class="form-control required-feild gender" name="gender" style="padding: 0.375rem 0.75rem;">
                            <option value="">
                                -
                            </option>
                            <option value="Male" {{ (old('gender') == 'Male') ? 'selected' : '' }}>
                                {{ isset($data['lang']['lang']['male']) ? $data['lang']['lang']['male'] :'男'}}
                            </option>
                            <option value="Female" {{ (old('gender') == 'Female') ? 'selected' : '' }}>
                                {{ isset($data['lang']['lang']['female']) ? $data['lang']['lang']['female'] :'女'}}
                            </option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{{ isset($data['lang']['lang']['date_of_birth']) ? $data['lang']['lang']['date_of_birth'] : '生日日期'}}: <span class="important-text">*</span></label>
                        <input type="text" class="form-control required-feild date-picker"  placeholder="Date of birth (dd/mm/yyyy)" name="dob" value="{{ old('dob') }}" readonly>
                    </div>
                    <div class="form-group">
                        <label>{{ isset($data['lang']['lang']['password']) ? $data['lang']['lang']['password'] :'密码'}} <span class="important-text">*</span></label>
                        <div class="input-group">
                            <span class="input-group-addon enable-password" 
                                  style="position: absolute; 
                                         right: 10px; 
                                         top: 10px; 
                                         z-index: 4;
                                         cursor: pointer;">
                                <i class="fa fa-eye"></i>
                            </span>
                            <input id="password" type="password" class="form-control password required-feild" name="password" placeholder="{{ isset($data['lang']['lang']['password']) ? $data['lang']['lang']['password'] :'密码'}}" value="{{ old('password') }}">
                        </div>
                        <!-- <input type="password" class="form-control required-feild password"  placeholder="{{ isset($data['lang']['lang']['password']) ? $data['lang']['lang']['password'] :'密码'}}" name="password" value="{{ old('password') }}"> -->
                    </div>
                    

                    <div class="form-group">
                        <label>{{ isset($data['lang']['lang']['confirm_password']) ? $data['lang']['lang']['confirm_password'] :'确认密码'}} <span class="important-text">*</span></label>
                        <!-- <input type="password" class="form-control required-feild"  placeholder="{{ isset($data['lang']['lang']['confirm_password']) ? $data['lang']['lang']['confirm_password'] :'确认密码'}}" name="password_confirmation" value="{{ old('password_confirmation') }}"> -->
                        <div class="input-group">
                            <span class="input-group-addon enable-confirm-password" 
                                  style="position: absolute; 
                                         right: 10px; 
                                         top: 10px; 
                                         z-index: 4;
                                         cursor: pointer;">
                                <i class="fa fa-eye"></i>
                            </span>
                            <input type="password" class="form-control password_confirmation required-feild" name="password_confirmation" placeholder="{{ isset($data['lang']['lang']['confirm_password']) ? $data['lang']['lang']['confirm_password'] :'确认密码'}}" value="{{ old('password_confirmation') }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>
                            {{ isset($data['lang']['lang']['nric_no']) ? $data['lang']['lang']['nric_no'] :'身份证号码 (NRIC No)' }}<span class="important-text">*</span>
                        </label>
                        <input type="text" class="form-control ic ic-field required-feild"  placeholder="{{ isset($data['lang']['lang']['nric_no']) ? $data['lang']['lang']['nric_no'] :'身份证号码 (NRIC No)'}} (Malaysia)" name="ic" value="{{ old('ic') }}" onkeypress="return isNumberKey(event)" maxlength="12">
                    </div>
                    <div class="address_area">
                        @if(!$products->isEmpty() && $data['web_setting']->registration_product_enable == 1)
                        <div class="form-group">
                            <label>{{ isset($data['lang']['lang']['country']) ? $data['lang']['lang']['country'] :'国家'}}<span class="important-text">*</span></label>
                            <select class="form-control select2 country" name="country" id="country" data-live-search="true">
                                @foreach($countries as $country)
                                <option value="{{ $country->country_id }}"
                                    {{ ( $country->country_id == '160') ? 'selected' : '' }}
                                    > {{ $country->country_name }} </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>{{ isset($data['lang']['lang']['address']) ? $data['lang']['lang']['address'] :'地址'}}<span class="important-text">*</span></label>
                            <textarea class="form-control address" placeholder="{{ isset($data['lang']['lang']['address']) ? $data['lang']['lang']['address'] :'地址'}}" name="address">{{ old('address') }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>{{ isset($data['lang']['lang']['postcode']) ? $data['lang']['lang']['postcode'] :'城市'}}<span class="important-text">*</span></label>
                            <input type="text" class="form-control postcode" placeholder="{{ isset($data['lang']['lang']['postcode']) ? $data['lang']['lang']['postcode'] :'城市'}}" name="postcode" value="{{ old('postcode') }}" onkeypress="return isNumberKey(event)">
                        </div>

                        <div class="form-group">
                            <label>{{ isset($data['lang']['lang']['city']) ? $data['lang']['lang']['city'] :'城市'}}<span class="important-text">*</span></label>
                            <input type="text" class="form-control city" placeholder="{{ isset($data['lang']['lang']['city']) ? $data['lang']['lang']['city'] :'城市'}}" name="city" value="{{ old('city') }}">
                        </div>


                        <div class="form-group state_area">
                            <label>{{ isset($data['lang']['lang']['state']) ? $data['lang']['lang']['state'] :'选择州'}}<span class="important-text">*</span></label>
                            <select class="form-control state" name="state" style="height: auto;">
                                <option value="">{{ isset($data['lang']['lang']['select_state']) ? $data['lang']['lang']['select_state'] :'选择州'}}</option>
                                @foreach($states as $state)
                                    <option value="{{ $state->id }}">
                                        {{ $state->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                    </div>

                    @if(!empty(request('p')))
                        <div class="form-group">
                            <label>Upline Name <span class="important-text">*</span></label>
                            <input type="text" class="form-control" value="{{ $refferer_name }}" placeholder="Upline Name"
                                   readonly>
                        </div>

                        <label>Upline Code <span class="important-text">*</span></label>
                        <div class="form-control" style="background-color: #e9ecef;">
                            {{ request('p') }}
                            <input type="hidden" name="master_id" class="master_id" value="{{ request('p') }}">
                        </div>
                    @elseif(Auth::guard('agent')->check())
                        <div class="form-group">
                            <label>Upline Name <span class="important-text">*</span></label>
                            <input type="text" class="form-control" value="{{ $refferer_name }}" placeholder="Upline Name"
                                   readonly>
                        </div>

                        <label>Upline Code <span class="important-text">*</span></label>
                        <div class="form-control" style="background-color: #e9ecef;">
                            {{ $refferer_code }}
                            <input type="hidden" name="master_id" class="master_id" value="{{ $refferer_code }}">
                        </div>
                    @else
                        <div class="form-group">
                            <label>Upline Name <span class="important-text">*</span></label>
                            <input type="text" class="form-control" value="{{ $data['admin']->f_name }} {{ $data['admin']->l_name }}" placeholder="Upline Name"
                                   readonly>
                        </div>

                        <div class="form-group">
                            <label>Upline Code <span class="important-text">*</span></label>
                            <input type="text" name="master_id" class="form-control master_id" value="{{ $data['admin']->code }}" placeholder="Upline Code"
                                   readonly>
                        </div>
                    @endif

                    <b id="error-message" class="important-text"></b>

                    <div class="form-group" style="font-size: 10px;">
                        <label class="checkbox-style">
                            <input type="checkbox" class="checking-pv" style="display: initial; vertical-align: middle;"> 
                            <a href="#" class="privacy_policy_description" data-toggle="modal" data-target="#pv">
                                By signing up, I agree to the {{ $data['web_setting']->website_name }}'s Privacy Policy.
                            </a>
                        </label>
                        <div class="modal fade" id="pv" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="z-index: 9999;">
                            <div class="modal-dialog" style="background-color: #fff;">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">{{ isset($data['lang']['lang']['privacy_policy']) ? $data['lang']['lang']['privacy_policy'] :'隐私政策'}}</h5>
                                        <a href="#" class="close-modal">
                                            X
                                        </a>
                                    </div>
                                    <div class="modal-body">
                                        <div class="single_post_text text-editor-image pb-4">
                                            {!! $data['web_setting']->privacy_policy_description !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary btn-block register-submit-button set_button set_text" type="button">
                            Sign up
                        </button>
                    </div>

                    <!-- <div class="form-group">
                        <a href="{{ asset('/auth/redirect/facebook') }}" class="btn btn-primary btn-block facebook-login-button">
                            <i class="fa fa-facebook-square"></i> CONTINUE WITH FACEBOOK
                        </a>
                    </div> -->

                    <div class="form-group" align="center">
                        {{ isset($data['lang']['lang']['already_have_account']) ? $data['lang']['lang']['already_have_account'] :'已有帐号'}}? <a href="{{ route('login') }}">{{ isset($data['lang']['lang']['login']) ? $data['lang']['lang']['login'] :'登录'}}</a>
                    </div>

                    <div class="form-group" align="center">
                        Member Account? <a href="{{ route('register') }}">Register as Member</a>
                    </div>
                </div>

                <div class="modal fade" id="exampleSecondModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="z-index: 9999;">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content" style="background-color: #fff;">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ isset($data['lang']['lang']['confirm_details']) ? $data['lang']['lang']['confirm_details'] :'确认资料'}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body confirm-details">
                            
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-shadow" data-dismiss="modal">
                            {{ isset($data['lang']['lang']['close']) ? $data['lang']['lang']['close'] :'关闭'}}
                        </button>
                        @if($products->isEmpty())
                            <button class="btn btn-shadow">
                                {{ isset($data['lang']['lang']['confirm']) ? $data['lang']['lang']['confirm'] :'确认'}}
                            </button>
                        @else
                            @if($data['web_setting']->registration_product_enable == 0)
                                <button class="btn btn-shadow">
                                    {{ isset($data['lang']['lang']['confirm']) ? $data['lang']['lang']['confirm'] :'确认'}}
                                </button>
                            @else
                                <button type="button" class="btn btn-shadow open-products">
                                    {{ isset($data['lang']['lang']['confirm']) ? $data['lang']['lang']['confirm'] :'确认'}}
                                </button>
                            @endif
                        @endif
                      </div>
                    </div>
                  </div>
                </div>

                <div class="modal fade" id="register-payment" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="z-index: 9999;">
                  <div class="modal-dialog">
                    <div class="modal-content" style="background-color: #fff;">
                      <div class="modal-body">
                        <div class="address_area">
                            <div class="shop-products">
                                <div class="shop-products__gird">
                                    <div class="row">
                                        @foreach($products as $key => $featured)
                                        @php
                                            $product_special_price = 0;

                                            if($featured->variation_enable == 1){
                                                if(!empty($priceV[$featured->id][2])){
                                                    if($priceV[$featured->id][2] == $priceV[$featured->id][3]){
                                                        $product_price = number_format($priceV[$featured->id][2], 2);
                                                    }else{
                                                        $product_price = number_format($priceV[$featured->id][2], 2).' - '.number_format($priceV[$featured->id][3], 2);
                                                    }
                                                }else{
                                                    if($priceV[$featured->id][0] == $priceV[$featured->id][1]){
                                                        $product_price = number_format($priceV[$featured->id][0], 2);
                                                    }else{
                                                        $product_price = number_format($priceV[$featured->id][0], 2).' - '.number_format($priceV[$featured->id][1], 2);
                                                    }
                                                }
                                            }else{
                                                if(!empty($featured->special_price)){
                                                    $product_price = number_format($featured->special_price, 2);
                                                }else{
                                                    $product_price = number_format($featured->price, 2);
                                                }
                                            }
                                      @endphp
                                      <div class="col-sm-6 product-listing my-3">
                                          <div class='container-box' style="font-size: 13px; background-color: #f8f6f6;" align="center">
                                              
                                                  <div class="product ">
                                                      @if($featured->packages == 1)
                                                      <div class="product-type">
                                                          <h5 class="-new">
                                                              Packages
                                                          </h5>
                                                      </div>
                                                      @endif
                                                      <div class="product-thumb">
                                                          <div style="background-image: url({{ (!empty($listingImages[$featured->id]->image)) ? asset($listingImages[$featured->id]->image) : asset('frontend/assets/images/product/1.png') }});
                                                                        background-repeat: no-repeat;
                                                                        background-size: 100%;
                                                                        background-position: center;
                                                                        width: 100%;
                                                                        height: 200px;" class="listing-image"></div>
                                                      </div>
                                                      <div class="product-content">
                                                          <div class="product-content__header" style="text-align: center; display: block;">
                                                              <div class="product-category">
                                                                  {{ $featured->brand_name }} &nbsp;
                                                              </div>
                                                          </div>
                                                          <span class="product-name">
                                                              {{ $featured->product_name }}
                                                          </span>
                                                          @if(!empty($featured->product_name_cn))
                                                              <span class="product-name">
                                                                  {{ $featured->product_name_cn }}
                                                              </span>
                                                          @endif
                                                          <div class="product-content__footer" style="text-align: center; display: block;">
                                                              <h5 class="product-price--main" style="color: #bf8a68;">
                                                                  RM {!! $product_price !!}
                                                              </h5>
                                                          </div>
                                                          @if(!empty($variation_options[$featured->id]))
                                                          <br>
                                                          <select class="variation_options form-control" name="variation_options" data-pid="{{ $featured->id }}">
                                                              @foreach($variation_options[$featured->id] as $option)
                                                                <option value="{{ $option->id }}" >
                                                                    {{ $option->variation_name }}
                                                                </option>
                                                              @endforeach
                                                          </select><br>
            
                                                          <div class="second_variation_option-display">
                                                          </div>
                                                          @endif
                                                      </div>
                                                  </div>
                                              <input type="radio" name="selected_starter" class="selected_starter" 
                                                     style="display: block;" value="{{ md5($featured->id) }}"
                                                     {{ ($key == 0) ? 'checked' : ''  }}>
                                          </div>
                                      </div>

                                      @endforeach
                                      <div class="endofproduct" data-id="1"></div>
                                  </div>
                              </div>
                              <div class="form-group">
                                <div class="row">
                                  <div class="col-6">
                                    <label for="quantity">Enter Quantity</label>
                                    <input type="number" name="quantity" id="quantity" class="form-control quantity" min="1" placeholder="Quantity" value="1"> 
                                  </div>
                                  <div class="col-6 shipping_method_col" style="display: none;">
                                  </div>
                                </div>
                            </div>
                          </div>
                          <hr>
                            <div class="additional_total">
                            
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-6">
                                            Subtotal
                                        </div>
                                        <div class="col-6" align="right">
                                            RM <span class="sub_total_display">0.00</span>
                                            <input type="hidden" name="sub_total_amount" class="sub_total_amount">
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-6">
                                            Shipping Fee
                                        </div>
                                        <div class="col-6" align="right">
                                            RM <span class="shipping_fee_display">0.00</span>
                                            <input type="hidden" name="shipping_fee_amount" class="shipping_fee_amount">
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-6">
                                            Grand Total
                                        </div>
                                        <div class="col-6" align="right">
                                            RM <span class="grand_total_display">0.00</span>
                                            <input type="hidden" name="grand_total_amount" class="grand_total_amount">
                                        </div>
                                    </div>
                                </div>
                          </div>
                          <input type="hidden" name="vid" class="vid">
                          <hr>
                        </div>

                        <div class="joining_fee_area">
                            <h5 class="display-amount"></h5>
                            <hr>
                        </div>
                            
                        <div class="widget-box transparent" id="recent-box">
                            <div class="widget-header">
                              <h4 class="widget-title lighter smaller">
                                <i class="fa fa-credit-card-alt" aria-hidden="true"></i> Select a payment
                              </h4>

                              <div class="widget-toolbar no-border">
                                <ul class="nav nav-tabs" id="recent-tab">
                                  <!-- <li class="parent_payment_method active">
                                    <a data-toggle="tab" class="payment_method f-15" data-id="1" href="#online-tab">Online Transfer</a>
                                  </li> -->

                                  <li class="parent_payment_method active">
                                    <a data-toggle="tab" class="payment_method f-15" data-id="2" href="#cdm-tab">Bank Transfer</a>
                                  </li>
                                </ul>
                              </div>
                              <input type="hidden" name="selected_payment_method" class="selected_payment_method" value="2">
                            </div>

                            <div class="widget-body">
                              <div class="widget-main padding-4">
                                <div class="tab-content padding-8">
                                  <div id="online-tab" class="tab-pane">
                                    <div class="form-group">
                                      <h4>Select Banks </h4>
                                    </div>
                                    <div class="form-group">
                                      <div class="row">
                                        
                                        <div class="col-4" align="center">
                                          <label>
                                            <input type="radio" name="bank_id" value="1">
                                            <img src="{{ asset('images/banks/maybank.jpg') }}">
                                          </label>
                                        </div>
                                        <div class="col-4" align="center">
                                          <label>
                                            <input type="radio" name="bank_id" value="2">
                                            <img src="{{ asset('images/banks/cimb.jpg') }}">
                                          </label>
                                        </div>
                                        <div class="col-4" align="center">
                                          <label>
                                            <input type="radio" name="bank_id" value="4">
                                            <img src="{{ asset('images/banks/rhb.jpg') }}">
                                          </label>
                                        </div>
                                      </div>
                                    </div>
                                    <br>
                                    <div class="form-group">
                                      <div class="row">
                                        <div class="col-4" align="center">
                                          <label>
                                            <input type="radio" name="bank_id" value="5">
                                            <img src="{{ asset('images/banks/hongleong.jpg') }}">
                                          </label>
                                        </div>
                                        <div class="col-4" align="center">
                                          <label>
                                            <input type="radio" name="bank_id" value="3">
                                            <img src="{{ asset('images/banks/pbe.jpg') }}">
                                          </label>
                                        </div>
                                      </div>
                                    </div>

                                    <div class="form-group">
                                        <b id="error-message-banks" class="important-text"></b>
                                    </div>
                                  </div>

                                  <div id="cdm-tab" class="tab-pane active" align="center">
                                    <div class="form-group">
                                    <input type="hidden" name="cdm_bank_id" value="10000743">
                                      <div class="card border-danger mb-3" style="max-width: 18rem;" align="center">
                                        <div class="card-body text-danger">
                                            <h5 class="card-title">{{!empty($data['web_setting']->bank_holder_name)?$data['web_setting']->bank_holder_name:'Bank Holder Name'}}</h5>
                                            <h5 class="card-title">{{!empty($data['web_setting']->bank_name)?$data['web_setting']->bank_name:'Bank Name'}}</h5>
                                            <p class="card-text">{{!empty($data['web_setting']->bank_account_number)?$data['web_setting']->bank_account_number:'Bank Account'}}</p>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="form-group bank_details">

                                    </div>
                                    <div class="form-group">
                                      <input type="file" name="bank_slip" class="form-control" accept="image/*">
                                    </div>

                                    <div class="form-group">
                                        <b id="error-message-cdm-banks" class="important-text"></b>
                                    </div>
                                  </div><!-- /.#member-tab -->
                                </div>
                              </div><!-- /.widget-main -->
                            </div><!-- /.widget-body -->
                          </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-shadow" data-dismiss="modal">
                            Close
                        </button>
                        <button class="btn btn-shadow register-btn">
                            Pay & Register
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
            </form>
        </div>
    </div>
</div>
<br>


@endsection
@section('js')
<link href = "https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" rel="stylesheet">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">
    $('#refferal_id').select2({
        placeholder: "Select Refferal ID",
        allowClear: true
    });

    $('#country_code').select2({
        placeholder: "Select Country Code",
        allowClear: true
    });

    $('.register-submit-button').click(function(e){
        e.preventDefault();

        // var ageCheck = $('.checking-age-18').prop('checked');
        var pvCheck = $('.checking-pv').prop('checked');

        var empty_fill;
        var code = $('input[name="code"]').val();
        var country_code = $('.country_code').val();
        var ageCheck = $('.checking-age-18').prop('checked');
        var pvCheck = $('.checking-pv').prop('checked');

        var f_name = $('.f_name').val();
        // var email = $('.email').val();
        var email = $('.email').val().trim().replace(/\s+/g, '');
        var country_code = $('.country_code').val();
        var phone = $('.phone').val();
        var prefer_language = $('.prefer_language').val();
        var gender = $('.gender').val();
        var ic = $('.ic').val();
        var ic_type = $('.ic_type:checked').val();
        var address = $('.address').val();
        var postcode = $('.postcode').val();
        var city = $('.city').val();
        var state = $(this).closest('.register-page').find('.state').val();
        var country = $('.country').val();
        var master_id = $('.master_id').val();
        var type = $('input[name="joining_type"]:checked').val();
        var password = $('.password').val();
        var password_confirmation = $('.password_confirmation').val();
        
        var products = '{{ $products }}';
        var registration_enable = '{{ $data["web_setting"]->registration_product_enable }}';

        prefer_language = (prefer_language == 1) ? '中文' : 'English';
        // gender = (gender == 'Male') ? 'Male' : 'Female';

        
        $('#register-form .required-feild').each( function(){
            if(!$(this).val()){
                $(this).addClass('required-feild-error');
                empty_fill = 1;
            }
        });
        
        if(empty_fill == 1){
            $('.error-message').html('Please fill in all required field.');
            $('.loading-gif').hide();
            return false;
        }else{
            $('#error-message').addClass('important-text');
            $('#error-message').html("");
        }

        if(IsEmail(email)==false){
            $('#error-message').addClass('important-text');
            $('#error-message').html("Please enter a valid email format");
            return false;
        }else{
            $('#error-message').addClass('important-text');
            $('#error-message').html("");
            $('.email').val(email);
        }

        if(!phone){
            $('#error-message').addClass('important-text');
            $('#error-message').html("Please enter a phone number");
            return false;
        }else{
            $('#error-message').addClass('important-text');
            $('#error-message').html("");
        }

        if(country == 160){
            if(ic.length != '12'){
                $('#error-message').addClass('important-text');
                $('#error-message').html("Please enter a valid NRIC number");
                return false;
            }else{
                $('#error-message').addClass('important-text');
                $('#error-message').html("");
            }
        }

        if(pvCheck == false){
            $('#error-message').addClass('important-text');
            $('#error-message').html("Please click the 'Privacy Policy' checkbox to agree");
            return false;
        }else{
            $('#error-message').addClass('important-text');
            $('#error-message').html("");
        }

        if(!password){
            $('#error-message').addClass('important-text');
            $('#error-message').html("Please enter a password");
            return false;
        }else{
            $('#error-message').addClass('important-text');
            $('#error-message').html("");
        }

        if(!password_confirmation){
            $('#error-message').addClass('important-text');
            $('#error-message').html("Please enter a password confirmation");
            return false;
        }else{
            $('#error-message').addClass('important-text');
            $('#error-message').html("");
        }

        if(password !== password_confirmation){
            $('#error-message').addClass('important-text');
            $('#error-message').html("Password and confirmation do not match");
            return false;
        }else{
            $('#error-message').addClass('important-text');
            $('#error-message').html("");
        }

        if(!gender){
            $('#error-message').addClass('important-text');
            $('#error-message').html("Please select a gender");
            return false;
        }else{
            $('#error-message').addClass('important-text');
            $('#error-message').html("");
        }

        // alert(products);
        if(products != '[]' && registration_enable == 1){
            if(type == 2){
                if(!address){
                    $('#error-message').addClass('important-text');
                    $('#error-message').html("Please field in address");
                    return false;
                }else if(!postcode){
                    $('#error-message').addClass('important-text');
                    $('#error-message').html("Please field in postcode");
                    return false;
                }else if(!city){
                    $('#error-message').addClass('important-text');
                    $('#error-message').html("Please field in city");
                    return false;
                }else if(!state){
                    $('#error-message').addClass('important-text');
                    $('#error-message').html("Please select state");
                    return false;
                }
            }
        }

        var fd = new FormData();
            fd.append('master_id', master_id);
            fd.append('state', state);
            fd.append('country', country);

        $.ajax({
            url: '{{ route("getUplineDetail") }}',
            type: 'post',
            data: fd,
            contentType: false,
            processData: false,
            success: function(response){
                if(response != 'not exists'){
                    $('#exampleSecondModal').modal('toggle');
                    if(!address){
                        var display = "<b>Full Name</b>: "+f_name+"<br><br>\
                                       <b>Email</b>: "+email+"<br><br>\
                                       <b>Phone</b>: "+country_code+phone+"<br><br>\
                                       <b>Gender</b>: "+gender+"<br><br>\
                                       <b>NRIC No</b>: "+ic+"<br><br>\
                                       <b>Referral Name</b>: "+response[0]+"<br><br>\
                                       <b>Referral Code</b>: "+master_id+"";
                    }else{
                        var display = "<b>Full Name</b>: "+f_name+"<br><br>\
                                       <b>Email</b>: "+email+"<br><br>\
                                       <b>Phone</b>: "+country_code+phone+"<br><br>\
                                       <b>Gender</b>: "+gender+"<br><br>\
                                       <b>NRIC No</b>: "+ic+"<br><br>\
                                       <b>Address</b>: "+address+"<br><br>\
                                       <b>Postcode</b>: "+postcode+"<br><br>\
                                       <b>City</b>: "+city+"<br><br>\
                                       <b>State</b>: "+response[1]+"<br><br>\
                                       <b>Referral Name</b>: "+response[0]+"<br><br>\
                                       <b>Referral Code</b>: "+master_id+"";
                    }
                    
                    $('.confirm-details').html(display);
                    calc();
                }else{
                    alert('Referral Not Exists');                    
                }
            },
        });

        

        
    });

    $('.register-btn').click( function(e){
        e.preventDefault();

        var bank_slip = $('input[name="bank_slip"]').val();

        if(!bank_slip){
            $('#error-message-cdm-banks').addClass('important-text');
            $('#error-message-cdm-banks').html("Please upload bank slip");
            return false;
        }

        // var ship_type = $('.ship_type').val();


        // if(ship_type == ''){
        //     toastr.error('PLease Select Shipping Method');
        //     return false;
        // }


       $('#register-form').submit();
    });

    $('.payment_method').click(function(e){
      var ele = $(this);
      var id = ele.data('id');
      $('.parent_payment_method').removeClass('active');
      ele.parent().addClass('active');
      $('.selected_payment_method').val(id);

    });

    $('.open-products').click(function(e){
        e.preventDefault();

        // $('#exampleSecondModal').modal('toggle');
        $('#register-payment').modal('toggle');
        // $('body').addClass('modal-open');
    });

    $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $('.variation_options').trigger('change');

    $('.selected_starter').change(function(){
        var change_status = $(this).closest('.product-listing').find('.variation_options').val();
        $('.vid').val(change_status);
        calc();
    });

    $('.state').change(function(){
        var change_status = $('.product-listing').find('.variation_options').val();
        $('.vid').val(change_status);
        calc();
    });

    $(document).on('change', '.ship_type', function(e){
        var ele = $(this);
        var ship_type = ele.val();

        calc();
    });

    function calc()
    {
        $('.loading-gif').show();
        var ele = $(this);
        var get_checked = $('.selected_starter:checked').val();
        var get_checked_variation = $('.selected_starter:checked').closest('.product-listing').find('.variation_options').val();
        var get_checked_second_variation = $('.selected_starter:checked').closest('.product-listing').find('.second_variation_option').val();
        var get_state = $('.state').val();
        var get_country = $('.country').val();
        var ship_type = $('.ship_type').val();

        var quantity = $('.quantity').val();

        // alert(get_checked_variation);
        var fd = new FormData();
            fd.append('pid', get_checked);
            fd.append('vid', get_checked_variation);
            fd.append('svid', get_checked_second_variation);
            fd.append('get_state', get_state);
            fd.append('get_country', get_country);
            fd.append('quantity', quantity);
            fd.append('ship_type', ship_type);

        $.ajax({
              url: '{{ route("GetRegisterPayment") }}',
              type: 'post',
              data: fd,
              contentType: false,
              processData: false,
              success: function(response){
                  $('.loading-gif').hide();
                  // alert(response[1]);
                  $('.sub_total_display').html(parseFloat(response[0]).toFixed(2));
                  $('.shipping_fee_display').html(parseFloat(response[1]).toFixed(2));
                  $('.grand_total_display').html(parseFloat(response[2]).toFixed(2));
              },
          });
    }


    $('.quantity').change(function(e){
        calc();
    })
    calc();

    $('input[name="joining_type"]').change( function() {
        var ele = $(this);

        var data = $('input[name="joining_type"]:checked').val();

        if(data == 1){
            $('.address_area').hide();
            $('.joining_fee_area').show();
        }else if(data == 2){
            $('.address_area').show();
            $('.joining_fee_area').hide();
        }else{
            $('.address_area').hide();
            $('.joining_fee_area').hide();
        }
    });

    $('input[name="joining_type"]').trigger('change');

    $('.joining_fee').change(function(e){
        var ele = $(this);
        var amount = ele.find(':selected').data('id');
        var bonus = ele.find(':selected').data('bonus');

        if(bonus > 0){
            bonus = " + Bonus(RM "+parseFloat(bonus).toFixed(2)+")";
        }
        $('.display-amount').html('Joining Fees: RM '+parseFloat(amount).toFixed(2) + bonus)
    });

    $('.joining_fee').trigger('change');

    $('.ic_type').click(function(e){
        var ele = $(this);

        var val = ele.val();

        if(val == 1){
            $('.ic').attr('placeholder', "{{ isset($data['lang']['lang']['nric_no']) ? $data['lang']['lang']['nric_no'] :'身份证号码 (NRIC No)'}} (Malaysia)");
        }else if(val == 2){
            $('.ic').attr('placeholder', 'Passport');
        }else{
            $('.ic').attr('placeholder', "{{ isset($data['lang']['lang']['nric_no']) ? $data['lang']['lang']['nric_no'] :'身份证号码 (NRIC No)'}} (Singapore)");
        }
    });

    $('.country').change(function(){
        var ele = $(this);

        if(ele.val() != '160'){
            $('.state_area').html('<input type="text" class="form-control state" name="state" placeholder="State">');
        }else{
            $('.state_area').html('<select class="form-control state" name="state" style="height: auto;">\
                                        <option value="">Select State</option>\
                                        @foreach($states as $state)\
                                            <option value="{{ $state->id }}">\
                                                {{ $state->name }}\
                                            </option>\
                                        @endforeach\
                                   </select>');
        }
    })

    $('.enable-password').click(function(e){
        e.preventDefault();

        var eye_close_open = $(this).find('i').attr('class');
        var ele = $(this).find('i');
        // alert(eye_close_open);
        if(eye_close_open == 'fa fa-eye-slash'){
            ele.removeClass('fa-eye-slash');
            ele.addClass('fa-eye');

            $('input[name="password"]').attr('type', 'password');
        }else{
            ele.addClass('fa-eye-slash');
            ele.removeClass('fa-eye');
            // alert($('input[name="password"]').attr('class'));
            // ele.parent().find('input[name="password"]').css('background-color', '#000');
            $('input[name="password"]').attr('type', 'text');
        }
    });

    $('.enable-confirm-password').click(function(e){
        e.preventDefault();

        var eye_close_open = $(this).find('i').attr('class');
        var ele = $(this).find('i');
        // alert(eye_close_open);
        if(eye_close_open == 'fa fa-eye-slash'){
            ele.removeClass('fa-eye-slash');
            ele.addClass('fa-eye');

            $('input[name="password_confirmation"]').attr('type', 'password');
        }else{
            ele.addClass('fa-eye-slash');
            ele.removeClass('fa-eye');
            // alert($('input[name="password"]').attr('class'));
            // ele.parent().find('input[name="password"]').css('background-color', '#000');
            $('input[name="password_confirmation"]').attr('type', 'text');
        }
    });

    $('.date-picker').datepicker({
        autoclose: true,
        todayHighlight: true,
        changeMonth: true,
        changeYear: true,
        yearRange: "-100:+0",
        dateFormat: 'dd/mm/yy'
    });
    
</script>
@endsection