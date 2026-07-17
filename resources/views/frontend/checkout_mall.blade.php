@extends('layouts.app')
<style type="text/css">
.ml-22{
	margin-left: 20px;
}

.grey-effect {
	background-color: rgba(128, 128, 128, 0.5);
}

.grey-effect img{
	filter: grayscale(100%);
}

.cart-detail {
	padding: 1em;
}

input[name="payment_id"] {
	display: none;
	position: absolute;
	opacity: 0;
	height: 0;
	width: 0;
}

.payment_method_title {
	font-weight: bold;
}

input[name="payment_id"] + img {
    cursor: pointer;
    width: 100px;
}

input[name="payment_id"]:checked + img {
    border: 2px solid #211c1c;
}
</style>
@section('content')
<div class="breadcrumb">
    <div class="container">
        <h2>Checkout Mall</h2>
    </div>
</div>
<div class="cart_section mt-3">
	<div class="container p-t-65 p-b-60">
		<form method="POST" action="{{ route('placeOrderMall') }}" id="placeorder-form" enctype="multipart/form-data">
		@csrf
			<div class="form-group">
				<div class="container-box">
					<div class="form-group">
						<div class="row">
							<div class="col-lg-6 col-md-6 col-sm-7 col-7">
								<h4>{{ isset($data['lang']['lang']['recipient_address']) ? $data['lang']['lang']['recipient_address'] :'收件人地址' }}</h4>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-5 col-5" align="right">
								<a href="#" class="btn btn-primary change-address set_button set_text" data-toggle="modal" data-target="#change-address">
									<i class="fa fa-pencil"></i> Change
								</a>
							</div>
						</div>
					</div>

					<div class="form-group">
        				@if($errors->any())
							<div class="alert alert-danger">{!! implode('<br/>', $errors->all(':message')) !!}</div>
                        @endif
                    </div>
					@if(!empty($getUserDetails->get_default_shipping_address->id))
						<input type="hidden" name="billing_details_im" value="{{ md5($getUserDetails->get_default_shipping_address->id) }}">
						<div class="form-group">
							<div class="form-group">
								<i class="fa fa-user w-23" aria-hidden="true"></i>
								<b>{{ $getUserDetails->get_default_shipping_address->f_name }}</b>
							</div>
							<div class="form-group">
								<i class="fa fa-map-marker w-23" aria-hidden="true"></i>
								<span>{{ $getUserDetails->get_default_shipping_address->address }},</span>
								<br> 

								<span class="w-23 d-ib"></span>
								<span>{{ $getUserDetails->get_default_shipping_address->postcode }} {{ $getUserDetails->get_default_shipping_address->city }},</span>
								<br>

								<span class="w-23 d-ib"></span>
								<span>
									{{ !empty($getUserDetails->get_default_shipping_address->get_states->name) ? $getUserDetails->get_default_shipping_address->get_states->name : $getUserDetails->get_default_shipping_address->state }}, 
								</span>
								<span>
									{{ !empty($getUserDetails->get_default_shipping_address->get_country->country_name) ? $getUserDetails->get_default_shipping_address->get_country->country_name : $getUserDetails->get_default_shipping_address->country }}
								</span>
							</div>
							<div class="form-group">
								<i class="fa fa-phone w-23" aria-hidden="true"></i>
								@if($getUserDetails->get_default_shipping_address->country_code)
									+{{ $getUserDetails->get_default_shipping_address->country_code }}
								@endif
								{{-- {{ ($getUserDetails->get_default_shipping_address->phone[0] == 0) ? substr($getUserDetails->get_default_shipping_address->phone, 1) : $getUserDetails->get_default_shipping_address->phone }} --}}
								@if($getUserDetails->get_default_shipping_address->country_code && $getUserDetails->get_default_shipping_address->country_code == '60')
									{{ ($getUserDetails->get_default_shipping_address->phone[0] == 0) ? $getUserDetails->get_default_shipping_address->phone : '0'.$getUserDetails->get_default_shipping_address->phone }}
								@else
									{{ ($getUserDetails->get_default_shipping_address->phone[0] == 0) ? substr($getUserDetails->get_default_shipping_address->phone, 1) : $getUserDetails->get_default_shipping_address->phone }}
								@endif
							</div>
							<div class="form-group">
								<i class="fa fa-envelope w-23" aria-hidden="true"></i>
								{{ $getUserDetails->get_default_shipping_address->email }}
							</div>
							<div class="form-group">
								{{-- @if(!$CodAddresses->isEmpty())
									<i class="fa fa-home w-23" aria-hidden="true"></i>
									Self Pickup:  <input type="checkbox" name="cod" class="self-pickup" value="1">
									<br>
									<br>
									<div>
										<div class="form-group cod_address_area" style="display: none;">
											<select class="form-control cod_address" name="cod_address">
												@foreach($CodAddresses as $codAddress)
													<option value="{{ $codAddress->id }}">{{ $codAddress->address }}</option>
												@endforeach
											</select>
											<br>
											<div class="address_details">

											</div>
										</div>
									</div>
								@endif --}}
								<i class="fa fa-book w-23" aria-hidden="true"></i>
								{{ isset($data['lang']['lang']['billing_checkbox']) ? $data['lang']['lang']['billing_checkbox'] :'寄付帐单地址与送货地址相同' }}:  <input type="checkbox" name="same_billing_address" class="same-billing-address" value="1" checked>
							</div>
						</div>
					@endif

					<hr>
					<div class="bill_fill_in" style="display: none;">
						<div class="form-group">
							<div class="row">
								<div class="col-6">
									<h4>Billing Address</h4>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-sm-6">
									<input type="text" class="form-control" placeholder="First Name *" name="f_name_bill" value="">
								</div>
								<div class="col-sm-6 mb-pt-15">
									<input type="text" class="form-control" placeholder="Last Name *" name="l_name_bill" value="">
								</div>
							</div>
						</div>
						<div class="form-group">
							<input type="text" class="form-control" placeholder="Email Address *" name="email_bill" value="">
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-6">
			                        <select class="form-control country_code_bill" name="country_code_bill" id="country_code_bill" data-live-search="true">
			                            @foreach($countries as $country)
											<option value="{{ $country->country_contact }}" {{ ($country->country_id == 160) ? 'selected' : '' }}>
												(+{{ $country->country_contact }}) {{ $country->country_name }} 
											</option>
			                            @endforeach
			                        </select>
								</div>
								<div class="col-6">
									<input type="text" class="form-control" placeholder="Phone *" name="phone_bill" value="" onkeypress="return isNumberKey(event)">
								</div>
							</div>
						</div>
						<div class="form-group">
							<textarea class="form-control" placeholder="Address *" name="address_bill"></textarea>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-sm-6">
									<input type="text" class="form-control" placeholder="State *" name="state_bill">
								</div>
								<div class="col-sm-6 mb-pt-15">
									<input type='text' class="form-control" placeholder="City *" name="city_bill" value="">
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-sm-6">
									<input type='text' class="form-control" placeholder="Post Code *" name="postcode_bill" value="" onkeypress="return isNumberKey(event)">
								</div>
								<div class="col-sm-6 mb-pt-15">
									<select class="form-control" name="country_bill">
										<option value="">{{ isset($data['lang']['lang']['country']) ? $data['lang']['lang']['country'] :'Country'}} *</option>
										@foreach($countries as $country)
											<option value="{{ $country->country_id }}">
												{{ $country->country_name }} 
											</option>
										@endforeach
									</select>
								</div>
							</div>
						</div>
						<div class="form-group">
							<b id="error-message" class="important-text error-message"></b>
						</div>
						<hr>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="row">
					<div class="col-md-12">
						<div class="container-box f-15">
							<div class="form-group">
								<div class="row">
									<div class="col-6">
										<b>Product Details</b>
										<input type="text" class="create_link_id" id="create_link_id" value="{{ route('checkout_mall') }}" style="height: 0; position: absolute; z-index: -1; padding: 0; border: none;">
									</div>
								</div>
							</div>
							<hr>
							@php
							$totalPrice = 0;
							$totalWeight = 0;
							@endphp
							@foreach($carts as $key => $cart)
							@php
		                        if(isset($cart->get_product_det->first_image->image) && !empty($cart->get_product_det->first_image->image)){
		                            $image = !empty($cart->get_product_det->first_image->image) ? $cart->get_product_det->first_image->image : asset('images/no-image-available-icon-6.jpg');
		                        }else{
		                            $image = asset('images/no-image-available-icon-6.jpg');
		                        }
		                    @endphp
							<div class="form-group cart-detail">
								<div class="row">
									<div class="col-2 order-images">
										<input type="hidden" name="selected_cart[]" class="form-control cid required-feild" value="{{ md5($cart->id) }}">
										
										<a href="{{ route('details_mall', md5($cart->get_product_det->id)) }}">
											<img src="{{ (!empty($cart->get_product_det->first_image->image)) ? asset($cart->get_product_det->first_image->image) : asset('images/no-image-available-icon-61.jpg') }}" style="width: 100%;">
										</a>
									</div>
									<div class="col-10 order-details">
										<div class="row">
											<div class="col-sm-2">
												<div class="form-group">
													<a href="{{ route('details_mall', md5($cart->get_product_det->id)) }}">
														{{ $cart->get_product_det->product_name }}
														@if($cart->get_product_det->variation_enable == '1')
															<br>
															Option: {{ $cart->get_fv_det->variation_name }}
														@endif
														@if($cart->get_product_det->second_variation_enable == '1')
															<br>
															Second Option: {{ $cart->get_sv_det->variation_name }}
														@endif
													</a>
													<br>
													@if($cart->get_product_det->variation_enable == '1')
														@if($cart->get_product_det->second_variation_enable == 1)
															{{ !empty($cart->get_sv_det->variation_weight) ? 'Weight: '.$cart->get_sv_det->variation_weight.'KG' : '' }}
														@else
															{{ !empty($cart->get_fv_det->variation_weight) ? 'Weight: '.$cart->get_fv_det->variation_weight.'KG' : '' }}
														@endif
													@else
														{{ !empty($cart->get_product_det->weight) ? 'Weight: '.$cart->get_product_det->weight.'KG' : '' }}
													@endif
													<br>
													<br>
													<a href="#" class="important-text non-load delete-cart-btn" data-id="{{ md5($cart->id) }}">
														<i class="fa fa-trash"></i>  Remove Item
													</a>
												</div>
											</div>

											<div class="col-sm-4" align="center">
												<div class="form-group product_unit_price">
													@php
									                    $product_price = floatval($get_pricing[$cart->id]['product_price']);
									                @endphp

									                {{ number_format($product_price, 2) }} Point
												</div>
											</div>
											<div class="col-sm-4" align="center">
												<div class="form-group quantity-setting">
													<button class="btn btn-primary deduct-qty-button">
														<i class="fa fa-minus"></i>
													</button>
													<input type="text" class="form-control" name="quantity" value="{{ $cart->qty }}" onkeypress="return isNumberKey(event)">
													<button class="btn btn-primary add-qty-button">
														<i class="fa fa-plus"></i>
													</button>
												</div>
											</div>
											<div class="col-sm-2" align="right">
												<div class="form-group product-total-price">
													{{ number_format($product_price * $cart->qty, 2) }} Point
												</div>
											</div>
										</div>
									</div>
								</div>
								<hr>
							</div>
							@php

							$totalPrice += $product_price * $cart->qty;
							
							if($cart->get_product_det->variation_enable == '1'){
								if($cart->get_product_det->second_variation_enable == '1'){
									$totalWeight += $cart->get_sv_det->variation_weight * $cart->qty;
								}else{
									$totalWeight += $cart->get_fv_det->variation_weight * $cart->qty;
								}
							}else{
								$totalWeight += $cart->get_product_det->weight * $cart->qty;
							}
							@endphp
							@endforeach
							@php
								$applied_discount_type = "";
								if(!empty($getUserDetails->get_applied_voucher->get_voucher_det->id)){
									if(empty($getUserDetails->get_applied_voucher->get_voucher_det->amount_type)){
										$applied_discount_type = "RM 0";
									}elseif($getUserDetails->get_applied_voucher->get_voucher_det->amount_type == 'Percentage'){
										$applied_discount_type = $getUserDetails->get_applied_voucher->get_voucher_det->amount."%";
									}else{
										$applied_discount_type = "RM ".$getUserDetails->get_applied_voucher->get_voucher_det->amount;
									}
								}
							@endphp
							<div class="form-group">
	                        	<div class="success-message-promo green">
	                        		{{ (!empty($getUserDetails->get_applied_voucher->get_voucher_det->id)) ? "Applied Voucher - ".$getUserDetails->get_applied_voucher->get_voucher_det->discount_code."(".$applied_discount_type.")" : '' }}
	                        		@if(!empty($getUserDetails->get_applied_voucher->get_voucher_det->id))
	                        			<a href="#" class="remove-applied-promo pull-right" data-id="{{ $getUserDetails->get_applied_voucher->get_voucher_det->id }}">
	                        				Remove
	                        			</a>
	                        		@endif
	                        	</div>
	                        	
	                        </div>
							<!-- <hr> -->

							<div class="form-group">
								<div class="row">
									<div class="col-6">
										<b>Subtotal: </b>
									</div>
									<div class="col-6" align="right">
										<b><span class="sub-total-display">{{ number_format($totalPrice, 2) }}</span> Point</b>
										<input type="hidden" name="sub_total" class='sub-total-value' id="subtotal" value="{{ $totalPrice }}">
									</div>
								</div>
							</div>
							<hr>

							@if($totalWeight > 0)
							
							<div class="form-group">
								<div class="row">
									<div class="col-6">
										<b>Total weight(KG): </b>
									</div>
									<div class="col-6" align="right">

										<b class="total-weight-display">{{ $totalWeight }}</b>
										<input type="hidden" name="weight" class="total-weight" id="weight" value="{{ $totalWeight }}">
									</div>
								</div>
							</div>
							<hr>
							@endif
							<input type="hidden" name="hidden_shipping_amount" class="hidden_shipping_amount" value="{{ $get_cart_details['total_shipping_fee'] }}">
							
							
							<div class="form-group">
								<div class="row">
									<div class="col-6">
										<b class="shipping_word">
											Shipping fees: 
										</b>
									</div>
									<div class="col-6" align="right">
										<b class="shipping_amount">{{ number_format($get_cart_details['total_shipping_fee'], 2) }} Point</b>
									</div>
								</div>
							</div>
							<hr>
							

							@php
								$applied_discount_amount = 0;
								if(!empty($getUserDetails->get_applied_voucher->get_voucher_det->id)){
									if($getUserDetails->get_applied_voucher->get_voucher_det->amount_type == 'Percentage'){
										$applied_discount_amount = (float) $totalPrice * $getUserDetails->get_applied_voucher->get_voucher_det->amount / 100;
									}else{
										$applied_discount_amount = $getUserDetails->get_applied_voucher->get_voucher_det->amount;
									}
								}
							@endphp

							
							@php
							$processing_fee = 0;
							@endphp
							<div class="form-group">
								<div class="row">
									<div class="col-6">
										<b style="font-size: 20px;">Grand total: </b>
									</div>
									<div class="col-6" align="right" style="font-size: 20px;">
										
										<b class="grand-total">{{ number_format(($totalPrice - $applied_discount_amount + $get_cart_details['total_shipping_fee'] + $processing_fee), 2) }} Point</b>
										@php
											$totalGrand = ($totalPrice - $applied_discount_amount + $get_cart_details['total_shipping_fee'] + $processing_fee);
										@endphp

										<input type="hidden" id="hidden_grand_total" class="hidden_grand_total" value="{{ $totalGrand }}">
									</div>
								</div>
							</div>
							
						</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="row">
					<div class="col-md-12">
						<div class="container-box form-group">
							<div class="widget-box transparent" id="recent-box">
								<div class="widget-header">
									<h4 class="widget-title smaller">
										<i class="fa fa-credit-card-alt" aria-hidden="true"></i> Payment Method
									</h4>

									<div class="widget-toolbar no-border">
										<ul class="nav nav-tabs" id="recent-tab">
											<li class="parent_payment_method active">
												<a data-toggle="tab" class="payment_method f-15" data-id="1" href="#point-tab">
													Point Wallet
												</a>
											</li>
										</ul>
									</div>
								</div>

								<div class="widget-body">
									<div class="widget-main padding-4">
										<div class="tab-content padding-8">
											<div id="point-tab" class="tab-pane active">
												<div class="form-group">
													<div class="row">
														<div class="col-12" align="center"> 
															<span class="wallet-balance-amount">
																{{ number_format($GetPVWallet, 2) }} Point
															</span>
															<br>
															<span class="wallet-desc profile-word">Remaining Point Wallet Balance</span>
															<br>
															<div class="form-group">
																<button class="btn btn-primary btn-block placeorder-btn bg-color set_button set_text"> Place order now </button>
															</div>
															<div class="form-group">
																<a href="{{ route('PointMall') }}" 
																   class="btn btn-primary btn-block bg-color not-same-bg
																   		  -red set_button set_text"> 
																	Continue Shopping 
																</a>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div><!-- /.widget-main -->
								</div><!-- /.widget-body -->
							</div><!-- /.widget-box -->
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>


<br>
<div class="modal fade" id="staticBackdrop" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index: 9999;">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="box-shadow: 0 2px 10px #bdbdbd;">
      <div class="modal-header">
        <h4>
        	Create New Shipping Address
        </h4>
     </div>
      <div class="modal-body" style="overflow: auto;">
        <form method="POST" action="{{ route('add_new_address') }}" id="add-new-address-form">
  			@csrf
  			<div class="form-group">
                @if($errors->any())
                  <div class="alert alert-danger">{!! implode('<br/>', $errors->all(':message')) !!}</div>
                @endif
            </div>
      		<div class="form-group">
      			<label>Name <span class="important-text">*</span></label>
            	<input type="text" class="form-control required-feild" placeholder="Name *" name="f_name" value="{{ Auth::guard($data['userGuardRole'])->user()->f_name }}">
            </div>
      		<div class="form-group">
      			<label>Email <span class="important-text">*</span></label>
				<input type="text" class="form-control required-feild" placeholder="Email *" name="email" value="{{ Auth::guard($data['userGuardRole'])->user()->email }}">
			</div>
			<div class="form-group">
                <div class="row">
                    <div class="col-6">
                    	<label>Country Code <span class="important-text">*</span></label>
                        <select class="form-control country_code" name="country_code" id="country_code" data-live-search="true">
                            @foreach($countries as $country)
                            <option value="{{ $country->country_contact }}" {{ ($country->country_id == 160) ? 'selected' : '' }}>
                            	(+{{ $country->country_contact }}) {{ $country->country_name }} 
                            </option>
                            @endforeach

                        </select>
                    </div>
                    <div class="col-6">
                    	<label>Phone <span class="important-text">*</span></label>
                        <input type="text" class="form-control required-feild" placeholder="ex: 171234567" name="phone" value="{{ Auth::guard($data['userGuardRole'])->user()->phone }}"  onkeypress="return isNumberKey(event)">
                    </div>
                </div>
            </div>
			<div class="form-group">
				Address <span class="important-text">*</span>
				<textarea class="form-control required-feild" placeholder="Address *" name="address"></textarea>
			</div>
			<div class="form-group">
				<label>State <span class="important-text">*</span></label>
				<select class="form-control" name="state">
					<option>Select State</option>
					@foreach($states as $state)
						<option value="{{ $state->id }}">{{ $state->name }}</option>
					@endforeach
				</select>
			</div>
			<div class="form-group">
				<div class="row">
					<div class="col-6">
						<label>City <span class="important-text">*</span></label>
						<input type='text' class="form-control required-feild" placeholder="City *" name="city" value="">
					</div>
					<div class="col-6">
						<label>Postcode <span class="important-text">*</span></label>
						<input type='text' class="form-control required-feild" placeholder="Postcode *" name="postcode" value="" onkeypress="return isNumberKey(event)">
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="row">
					<div class="col-12">
						<label>{{ isset($data['lang']['lang']['country']) ? $data['lang']['lang']['country'] :'Country'}}</label>
						<select class="form-control country" name="country" style="padding: 0.375rem 0.75rem;">
							<option value="">{{ isset($data['lang']['lang']['select_country']) ? $data['lang']['lang']['select_country'] :'Select Country'}}</option>
							@foreach($countries as $country)
								<option value="{{ $country->country_id }}">
									{{ $country->country_name }} 
							</option>
							@endforeach
						</select>
					</div>
				</div>
			</div>
            <div class="form-group">
                <div id="action-return-message"></div>
            </div>
      		<div class="form-group">
	      		<button class="btn -red btn-block btn-sm default_btn add-new-address-btn set_button set_text">
	        		Submit
	        	</button>
      		</div>
  		</form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="change-address" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="z-index: 9999;">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">
			My Shipping Address
		</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="POST" action="{{ route('update_address') }}">
		@csrf
		<div class="modal-body" align="left">
			<div class="form-group" align="right">
				<a href="#" data-toggle="modal" data-target="#add-new-address" class="btn btn-primary btn-sm set_button set_text">
					<i class="fa fa-plus"></i> Add New Address
				</a>
				<a href="{{ route('AddressBook.AddressBook.index') }}" class="btn btn-primary btn-sm set_button set_text">
					Address Manage
				</a>
			</div>
			<hr>
			@foreach($getUserDetails->get_shipping_address as $ad)
				<div class="form-group">
					<div class="row">
						<div class="col-1">
							<input type="radio" name="default" value="{{ $ad->id }}" {{ ($ad->default == 1) ? 'checked' : '' }}>
							<input type="hidden" name="aid[]" value="{{ $ad->id }}">
						</div>
						<div class="col-10">
							<i class="fa fa-user" style="width: 20px;"></i> {{ $ad->f_name }} {{ $ad->l_name }}<br> 
							<i class="fa fa-phone" style="width: 20px;"></i>
								+{{ $ad->country_code }} 
								{{-- {{ ($ad->phone[0] == 0) ? substr($ad->phone, 1) : $ad->phone }}  --}}
								@if($ad->country_code && $ad->country_code == '60')
									{{ ($ad->phone[0] == 0) ? $ad->phone : '0'.$ad->phone }}
								@else
									{{ ($ad->phone[0] == 0) ? substr($ad->phone, 1) : $ad->phone }}
								@endif
								<br>
							<i class="fa fa-location-arrow" style="width: 20px;"></i> {{ $ad->address }}, {{ $ad->post_code }} {{ $ad->city }}, {{ $ad->state_name }}
						</div>
					</div>
				</div>
				<hr>
			@endforeach
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-secondary set_button set_text" data-dismiss="modal">
				Cancel
			</button>
			<button class="btn btn-primary set_button set_text">
				Save Changes
			</button>
		</div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="add-new-address" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index: 9999;">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content" style="box-shadow: 0 2px 10px #bdbdbd;">
      <div class="modal-header">
        <h4>
        	Add Shipping Address
        </h4>
     </div>
      <div class="modal-body">
        <form method="POST" action="{{ route('add_new_address') }}" id="add-new-address-form">
  			@csrf
  			<div class="form-group">
                @if($errors->any())
                  <div class="alert alert-danger">{!! implode('<br/>', $errors->all(':message')) !!}</div>
                @endif
            </div>
      		<div class="form-group">
      			Name <span class="important-text">*</span>
            	<input type="text" class="form-control required-feild" placeholder="Name *" name="f_name" value="">
            </div>
      		<div class="form-group">
      			Email Address <span class="important-text">*</span>
				<input type="text" class="form-control required-feild" placeholder="Email Address *" name="email" value="">
			</div>
			<div class="form-group">
                <div class="row">
                    <div class="col-6">
                    	Country Code <span class="important-text">*</span>
                        <select class="form-control country_code" name="country_code" id="country_code" data-live-search="true">
                            @foreach($countries as $country)
                            <option value="{{ $country->country_contact }}"
                                {{ ($country->country_id == 160) ? 'selected' : '' }}
                                > (+{{ $country->country_contact }}) {{ $country->country_name }} </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6">
                    	Phone <span class="important-text">*</span>
                        <input type="text" class="form-control required-feild" placeholder="Ex: 121234567" name="phone" value=""  onkeypress="return isNumberKey(event)">
                    </div>
                </div>
            </div>
			<div class="form-group">
				Address <span class="important-text">*</span>
				<textarea class="form-control required-feild" placeholder="Address *" name="address"></textarea>
			</div>
			<div class="form-group">
				State <span class="important-text">*</span>
				<select class="form-control" name="state">
					<option>Select State</option>
					@foreach($states as $state)
						<option value="{{ $state->id }}">{{ $state->name }}</option>
					@endforeach
				</select>
			</div>
			<div class="form-group">
				<div class="row">
					<div class="col-6">
						City <span class="important-text">*</span>
						<input type='text' class="form-control required-feild" placeholder="City *" name="city" value="">
					</div>
					<div class="col-6">
						Postcode <span class="important-text">*</span>
						<input type='text' class="form-control required-feild" placeholder="Postcode *" name="postcode" value="" onkeypress="return isNumberKey(event)">
					</div>
				</div>
			</div>
			@php
				$selectedCountry = isset($address) ? $address->country : old('country');
			@endphp
			<div class="form-group">
				<div class="row">
					<div class="col-6">
						<label>{{ isset($data['lang']['lang']['country']) ? $data['lang']['lang']['country'] :'国家'}} <span class="important-text">*</span></label>
						<select class="form-control country" name="country" style="padding: 0.375rem 0.75rem;">
							<option value="">{{ isset($data['lang']['lang']['select_country']) ? $data['lang']['lang']['select_country'] :'选择国家'}}</option>
							@foreach($countries as $country)
								@if($country->country_name == 'Malaysia')
									<option selected value="{{ $country->country_id}}">
										{{ $country->country_name }} 
									</option>
								@endif
							@endforeach
						</select>
					</div>
				</div>
			</div>
            <div class="form-group">
                <div id="action-return-message"></div>
            </div>
      		<div class="form-group">
	      		<button class="btn btn-primary btn-block btn-sm default_btn add-new-address-btn set_button set_text">
	        		Submit
	        	</button>
      			<br>
      			<button type="button" class="btn btn-secondary btn-block btn-sm set_button set_text" data-dismiss="modal">
					Cancel
				</button>
      		</div>
  		</form>
      </div>
    </div>
  </div>
</div>

@endsection
@section('js')
<script type="text/javascript">
	// $('.delete-cart-btn').click( function(e){
	// 	e.preventDefault();
	// 	var ele = $(this);
	// 	var cart_id = $(this).data('id');
	// 	var fd = new FormData();
	// 	fd.append('cart_id', cart_id);

	// 	if(confirm("Item(s) will be removed from Cart") == true){
	// 		$('.loading-gif').show();
			
	// 		$.ajax({
	// 	       url: '{{ route("deleteCart") }}',
	// 	       type: 'post',
	// 	       data: fd,
	// 	       contentType: false,
	// 	       processData: false,
	// 	       success: function(response){
		       		
	// 	       		$('.loading-gif').hide();
		       		
	// 	       		$.ajax({
	// 			        url: '{{ route("CountCart") }}',
	// 			        type: 'get',
	// 			        success: function(response){
	// 			        	$('.badge-cart').html(response);
	// 			        }
	// 			    });
	// 			    ele.closest('.cart-detail').remove();
		       		
	// 	       		// calc();
	// 	       },
	// 	    });			
	// 	}else{
	// 		return false;
	// 	}

	// });

	$(document).ready( function(){
            
        if($(window).width() < 480) {
            $('.order-images').removeClass('col-2');
            $('.order-images').addClass('col-3');

            $('.order-details').removeClass('col-10');
            $('.order-details').addClass('col-9');
        }else{

        }
    });
    $('#placeorder-form .required-feild').change( function(){
    	if($(this).val()){
    		$(this).removeClass('required-feild-error');
    	}
    });
	$('.placeorder-btn').click( function(e){
		e.preventDefault();
		$('.loading-gif').show();
		var empty_fill;
		var wallet_balance = '{{ $GetPVWallet }}';
		var grand_total = $('#hidden_grand_total').val();
		// alert(wallet_balance+' - '+grand_total);
		if(parseFloat(wallet_balance) < parseFloat(grand_total)){
			// $('#error-balance').html('');
			toastr.error('Insufficient Point Balance.');
			$('.loading-gif').hide();
    		return false;
		}

	    $('#placeorder-form').submit();
	});

	$('.qty-button').click( function(e){
		e.preventDefault();

		var ele = $(this);
		var option = ele.data('id');

	})

	$('.cdm-placeorder-btn').click( function(e){
		e.preventDefault();
		$('.loading-gif').show();
		var empty_fill;
		var MphoneCheck;
		var phoneCheck;
	    var merchant = $('.merchant_select').val();		
		var md_state = $('input[name="merchant_billing_details_im"]').val();
		var state = $('input[name="billing_details_im"]').val();
		//if selected Merchant, get another column


		if(merchant){
			if(!md_state){
				if($.isNumeric($('input[name="m_phone"]').val()) == false){
		    		MphoneCheck = 1;
		    	}else{
		    		MphoneCheck = 0;
		    	}
				$('#placeorder-form .merchantShippingAddress .required-feild').each( function(){
					if(!$(this).val()){
			    		$(this).addClass('required-feild-error');
			    		empty_fill = 1;
			    	}
			    });	
			}
		}else{
			if(!state){
		    	if($.isNumeric($('input[name="phone"]').val()) == false){
		    		phoneCheck = 1;
		    	}else{
		    		phoneCheck = 0;
		    	}

			    $('#placeorder-form .ownShippingAddress .required-feild').each( function(){
			    	if(!$(this).val()){
			    		$(this).addClass('required-feild-error');
			    		empty_fill = 1;
			    	}

			    });
			}
		}

	    if(empty_fill == 1){
	    	$('.error-message').html('Please fill in all required field.');
	    	$('.loading-gif').hide();
	    	return false;
	    }

	    if(phoneCheck == 1){
	    	$('.error-message').html('Please field in valid phone number.');
	    	$('.phone').attr('style', 'border-color: red !important');;
	    	$('.loading-gif').hide();
	    	return false;
	    }else{
	    	$('.error-message').html('');
	    	$('.phone').attr('style', 'border-color: #bdbdbd !important');
	    }
	    
	    if(MphoneCheck == 1){

	    	$('.error-message').html('Please field in valid phone number.');
	    	$('.phone').attr('style', 'border-color: red !important');
	    	$('.loading-gif').hide();
	    	return false;

	    }else{
	    	$('.error-message').html('');
	    	$('.phone').attr('style', 'border-color: #bdbdbd !important');
	    }

	    var cdm_bank_id = $('select[name="cdm_bank_id"]').val();
		var bank_slip = $('input[name="bank_slip"]').val();
		
		if(!bank_slip){
			$('#error-message-cdm-banks').html('Please upload bank slip to continue payment.');
			$('.loading-gif').hide();
			return false;
		}
		

	    $('input[name="cdm"]').val(1);

	    $('#placeorder-form').submit();
	});

	$('.wallet-placeorder-btn').click( function(e){
		e.preventDefault();
		$('.loading-gif').show();
		var empty_fill;
		var MphoneCheck;
		var phoneCheck;
	    var merchant = $('.merchant_select').val();
		var md_state = $('input[name="merchant_billing_details_im"]').val();
		var state = $('input[name="billing_details_im"]').val();

	    var customer_address = $('.customer_address').prop('checked');
		//if selected Merchant, get another column


		if(customer_address == true){
			
		}else{
			if(!state){
		    	if($.isNumeric($('input[name="phone"]').val()) == false){
		    		phoneCheck = 1;
		    	}else{
		    		phoneCheck = 0;
		    	}

			    $('#placeorder-form .ownShippingAddress .required-feild').each( function(){
			    	if(!$(this).val()){
			    		$(this).addClass('required-feild-error');
			    		empty_fill = 1;
			    	}

			    });
			}
		}

	    if(empty_fill == 1){
	    	$('.error-message').html('Please fill in all required field.');
	    	$('.loading-gif').hide();
	    	return false;
	    }

	    if(phoneCheck == 1){
	    	$('.error-message').html('Please field in valid phone number.');
	    	$('.phone').attr('style', 'border-color: red !important');;
	    	$('.loading-gif').hide();
	    	return false;
	    }else{
	    	$('.error-message').html('');
	    	$('.phone').attr('style', 'border-color: #bdbdbd !important');
	    }
	    
	    if(MphoneCheck == 1){

	    	$('.error-message').html('Please field in valid phone number.');
	    	$('.phone').attr('style', 'border-color: red !important');
	    	$('.loading-gif').hide();
	    	return false;

	    }else{
	    	$('.error-message').html('');
	    	$('.phone').attr('style', 'border-color: #bdbdbd !important');
	    }

	    var GrandTotal = $('#hidden_grand_total').val();
		
		if(parseFloat(Balance) < parseFloat(GrandTotal)){
			$('.loading-gif').hide();
			$('#error-balance').html('Insufficient balance');
			alert('Insufficient balance');
			return false;
		}

	    $('input[name="topup_wallet"]').val(1);
	    if(confirm('Confirm payment for using product wallet balance of '+GrandTotal+'?') == true){
	    	$('#placeorder-form').submit();
	    }else{
	    	$('.loading-gif').hide();
	    }
	});

	$('.cash-wallet-placeorder-btn').click( function(e){
		e.preventDefault();
		$('.loading-gif').show();
		var empty_fill;
		var MphoneCheck;
		var phoneCheck;
	    var merchant = $('.merchant_select').val();
		var md_state = $('input[name="merchant_billing_details_im"]').val();
		var state = $('input[name="billing_details_im"]').val();

	    var customer_address = $('.customer_address').prop('checked');
		//if selected Merchant, get another column


		if(customer_address == true){
			
		}else{
			if(!state){
		    	if($.isNumeric($('input[name="phone"]').val()) == false){
		    		phoneCheck = 1;
		    	}else{
		    		phoneCheck = 0;
		    	}

			    $('#placeorder-form .ownShippingAddress .required-feild').each( function(){
			    	if(!$(this).val()){
			    		$(this).addClass('required-feild-error');
			    		empty_fill = 1;
			    	}

			    });
			}
		}

	    if(empty_fill == 1){
	    	$('.error-message').html('Please fill in all required field.');
	    	$('.loading-gif').hide();
	    	return false;
	    }

	    if(phoneCheck == 1){
	    	$('.error-message').html('Please field in valid phone number.');
	    	$('.phone').attr('style', 'border-color: red !important');;
	    	$('.loading-gif').hide();
	    	return false;
	    }else{
	    	$('.error-message').html('');
	    	$('.phone').attr('style', 'border-color: #bdbdbd !important');
	    }
	    
	    if(MphoneCheck == 1){

	    	$('.error-message').html('Please field in valid phone number.');
	    	$('.phone').attr('style', 'border-color: red !important');
	    	$('.loading-gif').hide();
	    	return false;

	    }else{
	    	$('.error-message').html('');
	    	$('.phone').attr('style', 'border-color: #bdbdbd !important');
	    }

	    var GrandTotal = $('#hidden_grand_total').val();
	    var Balance = '{{ $totalBalance }}';
		
		if(parseFloat(Balance) < parseFloat(GrandTotal)){
			$('.loading-gif').hide();
			$('#error-balance').html('Insufficient balance');
			alert('Insufficient balance');
			return false;
		}

	    $('input[name="cash_wallet"]').val(1);
	    if(confirm('Confirm payment for using cash wallet balance of '+GrandTotal+'?') == true){
	    	$('#placeorder-form').submit();
	    }else{
	    	$('.loading-gif').hide();
	    }
	});

	$('.topup-wallet-placeorder-btn').click(function(e){
		e.preventDefault();
		$('.loading-gif').show();
		var empty_fill;
		var MphoneCheck;
		var phoneCheck;
	    var merchant = $('.merchant_select').val();
		var md_state = $('input[name="merchant_billing_details_im"]').val();
		var state = $('input[name="billing_details_im"]').val();

	    var customer_address = $('.customer_address').prop('checked');
		//if selected Merchant, get another column


		if(customer_address == true){
			
		}else{
			if(!state){
		    	if($.isNumeric($('input[name="phone"]').val()) == false){
		    		phoneCheck = 1;
		    	}else{
		    		phoneCheck = 0;
		    	}

			    $('#placeorder-form .ownShippingAddress .required-feild').each( function(){
			    	if(!$(this).val()){
			    		$(this).addClass('required-feild-error');
			    		empty_fill = 1;
			    	}

			    });
			}
		}

	    if(empty_fill == 1){
	    	$('.error-message').html('Please fill in all required field.');
	    	$('.loading-gif').hide();
	    	return false;
	    }

	    if(phoneCheck == 1){
	    	$('.error-message').html('Please field in valid phone number.');
	    	$('.phone').attr('style', 'border-color: red !important');;
	    	$('.loading-gif').hide();
	    	return false;
	    }else{
	    	$('.error-message').html('');
	    	$('.phone').attr('style', 'border-color: #bdbdbd !important');
	    }
	    
	    if(MphoneCheck == 1){

	    	$('.error-message').html('Please field in valid phone number.');
	    	$('.phone').attr('style', 'border-color: red !important');
	    	$('.loading-gif').hide();
	    	return false;

	    }else{
	    	$('.error-message').html('');
	    	$('.phone').attr('style', 'border-color: #bdbdbd !important');
	    }

	    var GrandTotal = $('#hidden_grand_total').val();
	    var Balance = '{{ $GetProductWalletBalance }}';
		
		if(parseFloat(Balance) < parseFloat(GrandTotal)){
			$('.loading-gif').hide();
			$('#error-balance').html('Insufficient balance');
			alert('Insufficient balance');
			return false;
		}

	    $('input[name="cash_wallet"]').val(1);
	    if(confirm('Confirm payment for using topup wallet balance of '+GrandTotal+'?') == true){
	    	$('#placeorder-form').submit();
	    }else{
	    	$('.loading-gif').hide();
	    }
	});

	$('.apply-discount').click( function(e){
		e.preventDefault();
		
		var discount_code = $('.discount-code').val();
		if(discount_code){
			$('.loading-gif').show();
			var fd = new FormData();
			fd.append('discount_code', discount_code);
			fd.append('checkout_apply', '1');

			$.ajax({
		       url: '{{ route("ApplyPromo") }}',
		       type: 'post',
		       data: fd,
		       contentType: false,
		       processData: false,
		       success: function(response){
		       		$('.loading-gif').hide();
		       		
		       		// alert(response);
		       		// return false;
		       		if(response == 0){
		    			$('.error-message-promo').html('Invalid Promotion Code');
		    			return false;
		       		}else if(response == 1){
						$('.error-message-promo').html('Promotion Code out of limit');
		    			return false;
		       		}else if(response == 2){
						$('.error-message-promo').html('Promotion Code not in date range');
		    			return false;
		       		}else if(response == 3){
						$('.error-message-promo').html('Your shopping cart does not meet the requirements of the Promotion Code: '+discount_code+'.');
		    			return false;		       			
		       		}else if(response == 4){
						$('.error-message-promo').html('Promotion Code out of limit.');
		    			return false;		       			
		       		}else if(response == 5){
						$('.error-message-promo').html('Promotion Code out of limit.');
		    			return false;		       			
		       		}else{
		       			location.reload();
		       			if(response[1] == 'Percentage'){
		       				var total = $('#subtotal').val() * response[0] / 100;
		       				var grand_total = $('#subtotal').val() - total;
		       				var shipping_fee = $('.hidden_shipping_amount').val();

		       				$('.discount_word').html('Discount ('+response[0]+'%)');
		       				$('.discount_amount').html('- RM '+parseFloat(total).toFixed(2));
		       				$('.hidden_discount').val(parseFloat(total).toFixed(2));

		       				if($('.payment_method.active').data('id') == '2'){
		       					$('.processing_amount').html('RM 0.00');
			       				$('.grand-total').html('RM '+parseFloat((parseFloat(grand_total) + parseFloat(shipping_fee))).toFixed(2));
			       				$('#hidden_grand_total').val(parseFloat((parseFloat(grand_total) + parseFloat(shipping_fee))).toFixed(2));
		       				}else{
			       				// $('.processing_amount').html('RM '+parseFloat((parseFloat(grand_total) + parseFloat(shipping_fee)) * 1.6 / 100).toFixed(2));
			       				$('.grand-total').html('RM '+parseFloat((parseFloat(grand_total) + parseFloat(shipping_fee))).toFixed(2));
			       				$('#hidden_grand_total').val(parseFloat((parseFloat(grand_total) + parseFloat(shipping_fee))).toFixed(2));
		       					
		       				}

		       				$('#code').val(response[2]);
		       				$('#totalDiscount').val(total);
		       			}else{
		       				var total = response[0];
		       				var shipping_fee = $('.hidden_shipping_amount').val();
		       				var grand_total = parseFloat($('#subtotal').val()) + parseFloat(shipping_fee) - total;
		       				
		       				$('.discount_word').html('Discount: ');
		       				if(grand_total <= 0){

			       				$('.discount_amount').html('RM '+parseFloat(total).toFixed(2));
			       				$('.hidden_discount').val(parseFloat(total).toFixed(2));

			       				if($('.payment_method.active').data('id') == '2'){

			       					$('.grand-total').html('RM '+parseFloat(shipping_fee).toFixed(2));
			       					$('.processing_amount').html('RM 0.00');
				       				$('.hidden_processing_amount').val(0);
			       					$('#hidden_grand_total').val(shipping_fee);

			       				}else{

			       					// $('.processing_amount').html('RM '+parseFloat(parseFloat(shipping_fee) * 1.6 / 100).toFixed(2));
				       				// $('.hidden_processing_amount').val(parseFloat(parseFloat(shipping_fee) * 1.6 / 100).toFixed(2));

			       					$('.grand-total').html('RM '+parseFloat(parseFloat(shipping_fee)).toFixed(2));
			       					$('#hidden_grand_total').val(parseFloat(parseFloat(shipping_fee)).toFixed(2));

			       				}
		       				}else{
		       					if($('.payment_method.active').data('id') == '2'){
				       				$('.discount_amount').html('- RM '+parseFloat(total).toFixed(2));
				       				$('.hidden_discount').val(parseFloat(total).toFixed(2));
				       				$('.processing_amount').html('RM 0.00');
				       				$('.hidden_processing_amount').val(0);
				       				$('.grand-total').html('RM '+parseFloat(grand_total).toFixed(2));
				       				$('#hidden_grand_total').val(parseFloat(grand_total).toFixed(2));

		       					}else{
									$('.discount_amount').html('- RM '+parseFloat(total).toFixed(2));
				       				$('.hidden_discount').val(parseFloat(total).toFixed(2));
				       				// $('.processing_amount').html('RM '+parseFloat(parseFloat(grand_total) * 1.6 / 100).toFixed(2));
				       				// $('.hidden_processing_amount').val(parseFloat(parseFloat(grand_total) * 1.6 / 100).toFixed(2));
				       				$('.grand-total').html('RM '+parseFloat(parseFloat(grand_total)).toFixed(2));
				       				$('#hidden_grand_total').val(parseFloat(parseFloat(grand_total)).toFixed(2));
		       					}
		       				}

		       				$('#code').val(response[2]);
		       				$('#totalDiscount').val(total);

		       			}
		       			$('.close-modal').click();
		       			$('.modal-backdrop').remove();
		       			$('.modal-open').css('overflow', 'auto');
		    //    			$(function () {
						//    $('#applyPromotion').modal('hide');
						// });
		       			$('.success-message-promo').html('Applied Successfully - '+response[5]+'('+response[4]+') <a href="#" class="remove-applied-promo pull-right" data-id="'+response[3]+'">Remove</a>');
		       			$('.promotion-field').remove();
		       		}
		       },
		    });
		}else{
			$('.error-message-promo').html('Please fill in Promotion Code');
			return false;
		}
	});


	$('.cdm_bank_id').change( function(){
		var ele = $(this);

		var fd = new FormData();
			fd.append('bank_id', ele.val());
			
		$.ajax({
		       url: '{{ route("getBankDetails") }}',
		       type: 'post',
		       data: fd,
		       contentType: false,
		       processData: false,
		       success: function(response){
		       		if(response == 0){
		       			$('.bank_details').html('');
		       		}else{
		       			$('.bank_details').html('Bank name: '+response[0]+'<br>'+'Bank account: '+response[1]+'<br> Bank holder name: '+response[2]);
		       		}
		       }
		});
	});

	$('.payment_method').click( function(e){
		e.preventDefault();

		var ele = $(this);
		var total = $('#hidden_grand_total').val();
		var sub = $('#subtotal').val();
		var selfPickup = $('.self-pickup').prop('checked');
		
		if(selfPickup == true){
			var shipping_fee = 0;
		}else{
			var shipping_fee = $('.hidden_shipping_amount').val();
		}
		
		var discount = $('.hidden_discount').val();

		$('.parent_payment_method').removeClass('active');
		ele.parent().addClass('active');

		discount = (discount) ? discount : 0;
		total = parseFloat(sub) + parseFloat(shipping_fee) - parseFloat(discount);

		if(ele.data('id') == 1){
			$("input[name='online']").val(1);
			$("input[name='cdm']").val(0);
			$("input[name='cash_wallet']").val(0);
			$("input[name='topup_wallet']").val(0);
		}else{
			$("input[name='online']").val(0);
			if(ele.data('id') == 3){
				$("input[name='topup_wallet']").val(1);
				$("input[name='cdm']").val(0);
				$("input[name='cash_wallet']").val(0);
			}else if(ele.data('id') == 4){
				$("input[name='cash_wallet']").val(1);
				$("input[name='cdm']").val(0);
				$("input[name='topup_wallet']").val(0);
			}else{
				$("input[name='topup_wallet']").val(0);
				$("input[name='cdm']").val(1);
				$("input[name='cash_wallet']").val(0);
			}
		}
		
	});

	$('.success-message-promo').on('click', '.remove-applied-promo', function(e){
		e.preventDefault();

		var ele = $(this);

		var fd = new FormData();
			fd.append('id', ele.data('id'));
		if(confirm("Remove this promotion?") == true){
			$.ajax({
			       url: '{{ route("removePromotion") }}',
			       type: 'post',
			       data: fd,
			       contentType: false,
			       processData: false,
			       success: function(response){
			       		location.reload();
			       }
			});			
		}
	});

	$('.delete-cart-btn').click( function(e){
		e.preventDefault();
		var ele = $(this);
		var cart_id = $(this).data('id');
		var applied_promo_id = $('.remove-applied-promo').data('id');

		var fd = new FormData();
		fd.append('cart_id', cart_id);
		fd.append('applied_promo_id', applied_promo_id);
		
		if(confirm("Item(s) will be removed from Cart") == true){
			$('.loading-gif').show();
			$.ajax({
		       url: '{{ route("deleteCart") }}',
		       type: 'post',
		       data: fd,
		       contentType: false,
		       processData: false,
		       success: function(response){
		       		$('.loading-gif').hide();
		       		$.ajax({
				        url: '{{ route("CountCart") }}',
				        type: 'get',
				        success: function(response){
				        	
				        	$('.cart_count span').html(response[0]);
				        	$('.cart_price').html('RM '+parseFloat(response[1]).toFixed(2));
				        }
				    });
			        ele.closest('.cart-detail').remove();
			        var check = $('.container-box .cart-detail').length;
		       		
			        if(check == 0){
			        	window.location.href = "{{ route('PointMall') }}";
			        }else{
			        	location.reload();
			        }
		       },
		    });			
		}
		
	});

	$('.change-login-register-tab').click( function(e){
		e.preventDefault();
		var ele = $(this);
		$('.rl-tab').removeClass('show active');
		$(ele.attr('href')).addClass('show active');
		// alert(ele.parent().parent().('class'));
	});

	$('.continue-as-guess').click( function(e){
		$('.loading-gif').show();
		$.ajax({
	        url: '{{ route("setNewGuest") }}',
	        type: 'get',
	        success: function(response){
	        	$('.loading-gif').hide();
	        	location.reload();
	        }
	    });
	});

	$('#login-form .button-inside').on('click', '.get-verify-code-btn', function(e){
        e.preventDefault();
        var ele = $(this);
        var phone = $('#login-form input[name="phone"]').val();
        if(phone.length < 10){
            alert("Please enter a valid mobile phone number");
            return false;
        }

        var fd = new FormData();
        fd.append('phone', phone);

        $.ajax({
            url: '{{ route("getVerifyCode") }}',
            type: 'post',
            data: fd,
            contentType: false,
            processData: false,
            success: function(response){
                if(response == '1'){
                    alert('Phone number does not exist');
                    return false;
                }else{
                    ele.prop('disabled', true);
                    
                    $('#login-form #action-return-message').html('The verification code has been sent to your mobile phone, the input is valid within 10 minutes, please do not leak');

                    $('#login-form #action-return-message').addClass('important-text');

                    var timer2 = response[1];
                    // var timer2 = "0:03";
                    var interval = setInterval(function() {


                    var timer = timer2.split(':');
                    //by parsing integer, I avoid all extra string processing
                    var minutes = parseInt(timer[0], 10);
                    var seconds = parseInt(timer[1], 10);
                    --seconds;
                    minutes = (seconds < 0) ? --minutes : minutes;
                    seconds = (seconds < 0) ? 59 : seconds;
                    seconds = (seconds < 10) ? '0' + seconds : seconds;
                    //minutes = (minutes < 10) ?  minutes : minutes;
                    if (minutes == '0' && seconds == '00'){
                        clearInterval(interval);
                        var fd = new FormData();
                        fd.append('phone', phone);
                        $.ajax({
                            url: '{{ route("resetVerifyCode") }}',
                            type: 'post',
                            data: fd,
                            contentType: false,
                            processData: false,
                            success: function(response){
                                ele.html("Get Verify Code");
                                ele.prop('disabled', false);
                                $('#login-form #action-return-message').html('The verification code has been refreshed! Please click "Get Verification Code" to get the latest verification code!');
                            }
                        });
                    }

                    ele.html(minutes + ':' + seconds);

                    timer2 = minutes + ':' + seconds;
                    }, 1000);                            
                }
            },
        });
    });


    $('#register-form .button-inside').on('click', '.get-verify-code-btn', function(e){
        e.preventDefault();
        var ele = $(this);
        var phone = $('#register-form input[name="phone"]').val();
        if(phone.length < 10){
            alert("Please enter a valid mobile phone number");
            return false;
        }

        var fd = new FormData();
        fd.append('phone', phone);
        fd.append('register', '1');

        $.ajax({
            url: '{{ route("getVerifyCode") }}',
            type: 'post',
            data: fd,
            contentType: false,
            processData: false,
            success: function(response){
                if(response == '1'){
                    alert('Phone number does not exist');
                    return false;
                }else{
                    ele.prop('disabled', true);
                    
                    $('#register-form #action-return-message').html('The verification code has been sent to your mobile phone, the input is valid within 10 minutes, please do not leak');

                    $('#register-form #action-return-message').addClass('important-text');

                    var timer2 = response[1];
                    // var timer2 = "0:03";
                    var interval = setInterval(function() {


                    var timer = timer2.split(':');
                    //by parsing integer, I avoid all extra string processing
                    var minutes = parseInt(timer[0], 10);
                    var seconds = parseInt(timer[1], 10);
                    --seconds;
                    minutes = (seconds < 0) ? --minutes : minutes;
                    seconds = (seconds < 0) ? 59 : seconds;
                    seconds = (seconds < 10) ? '0' + seconds : seconds;
                    //minutes = (minutes < 10) ?  minutes : minutes;
                    if (minutes == '0' && seconds == '00'){
                        clearInterval(interval);
                        var fd = new FormData();
                        fd.append('phone', phone);
                        $.ajax({
                            url: '{{ route("resetVerifyCode") }}',
                            type: 'post',
                            data: fd,
                            contentType: false,
                            processData: false,
                            success: function(response){
                                ele.html("Get Verify Code");
                                ele.prop('disabled', false);
                                $('#register-form #action-return-message').html('The verification code has been refreshed! Please click "Get Verification Code" to get the latest verification code!');
                            }
                        });
                    }

                    ele.html(minutes + ':' + seconds);

                    timer2 = minutes + ':' + seconds;
                    }, 1000);                            
                }
            },
        });
    });

    $('.claim-voucher').click( function(e){
        e.preventDefault();
        var ele = $(this);
		$('.discount-code').val(ele.data('id'));
        $('.apply-discount').click();
        
    });

    $('.create-cart-link').click(function(e){
    	e.preventDefault();

    	$.ajax({
            url: '{{ route("CreateCartLink") }}',
            type: 'get',
            success: function(response){
            	var url = "{{ route('home', 'l=:id') }}";
            		url = url.replace(':id', response);
            	// alert(url);
            	$('.create_link_id').val(url);
                var copyText = document.getElementById("create_link_id");
				    copyText.select();
				    copyText.setSelectionRange(0, 99999)
				    document.execCommand("copy");

				toastr.success('Link Copied');
				window.location.href = "{{ route('home') }}";
            },
        });
    	
    });

    $('.change-register').click(function(e){
    	e.preventDefault();

    	var ele = $(this);
    	
    	$(".login-form").fadeOut( "fast", function() {
		    // Animation complete.
		    $('.register-form').fadeIn('fast');
		});
    });

    $('.change-login').click(function(e){
    	e.preventDefault();

    	var ele = $(this);

    	$(".register-form").fadeOut( "fast", function() {
		    // Animation complete.
		    $('.login-form').fadeIn('fast');
		});
    });

    $('.add-qty-button').click( function(e){

		e.preventDefault();

		
		var ele = $(this);
		var quantity = ele.parent().find('input[name="quantity"]').val();
		var id = ele.closest('.cart-detail').find('.cid').val();
		var balance = ele.closest('ul').find('input[name="balance_quantity"]').val();
		quantity = Number(quantity) + 1;

		if(quantity > balance){
			alert('The maximum quantity available for this item is '+balance);
			$('.loading-gif').hide();
			return false;
		}else{
			ele.parent().find('input[name="quantity"]').val(quantity);
			updateQty(quantity, id, ele);
		}
		
	});

	$('.deduct-qty-button').click( function(e){
		e.preventDefault();
		
		var ele = $(this);
		var quantity = ele.parent().find('input[name="quantity"]').val();
		var id = ele.closest('.cart-detail').find('.cid').val();
		if(quantity != 1){
			quantity = Number(quantity) - 1;
			ele.parent().find('input[name="quantity"]').val(quantity);
			updateQty(quantity, id, ele);
		}

	});

	$('input[name="quantity"]').on('keypress', function(event) {
		if (event.which === 13) {
			event.preventDefault();
			var ele = $(this);

			if (ele.val() == '0' || !ele.val()) {
				location.reload();
				return false;
			}

			var id = ele.closest('.cart-detail').find('.cid').val();
			var qty = ele.val();
			
			// Increase the quantity by 1
			qty = parseInt(qty);

			ele.val(qty); // Update the input field with the increased quantity

			updateQty(qty, id, ele);
		}
	});

	function updateQty(qty, cart_id, ele){
		$('.loading-gif').show();
		var m = '{{ request("m") }}';
		var fd = new FormData();
		fd.append('cart_id', cart_id);
		fd.append('quantity', qty);
		$.ajax({
	       url: '{{ route("updateQuantity") }}',
	       type: 'post',
	       data: fd,
	       contentType: false,
	       processData: false,
	       success: function(response){
	       	$('.loading-gif').hide();
	       	// alert(response);

       		ele.closest('.cart-detail').find('.product_unit_price').html(parseFloat(parseFloat(response) / parseFloat(qty)).toFixed(2)+' Point ');

       		ele.closest('.cart-detail').find('.product-total-price').html(parseFloat(response).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})+' Point ');
			calc();
	       },
	    });
		
	}

	function calc()
	{
		var discount = $('input[name="discount_code"]').val();
		var ship_type = $('#ship-type').val();
		var store_checked = $('.store-in-stock').prop('checked');
		var store;
		if(store_checked == true){
			store = 1
		}

		var fd = new FormData();
			fd.append('discount', discount);
			fd.append('ship_type', ship_type);
			fd.append('store', store);
			fd.append('mall', 1);
		$.ajax({
	       url: '{{ route("getCartAmount") }}',
	       type: 'post',
	       data: fd,
	       contentType: false,
	       processData: false,
	       success: function(response){
	       		$('.sub-total-display').html(parseFloat(response[0]).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
				$('.sub-total-value').val(response[0]);
				$('.total-weight-display').html(response[1]);
				$('.total-weight').val(response[1]);
				$('.discount_amount').html(parseFloat(response[2]).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})+ ' Point');
				$('.hidden_discount').val(response[2]);
				$('.shipping_amount').html(parseFloat(response[3]).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})+ ' Point');
				$('.hidden_shipping_amount').val(response[3]);
				$('.grand-total').html(parseFloat(response[4]).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})+ ' Point');
				$('.hidden_grand_total').val(response[4]);

	       },
	    });
	}

	$('#ship-type').change(function(e){
		e.preventDefault();

		calc();
	});

	$('.select2').select2();

	$('.self-pickup').click(function (e){
    	var shipping_fee = $('.hidden_shipping_amount').val();
    	var grand_total = $('.hidden_grand_total').val();
    	var parent_payment_method = $('.parent_payment_method.active').find('.payment_method').data('id');
    	var sub = $('#subtotal').val();
    	var currentTotal = 0;

    	// alert(shipping_fee);
    	if($(this).prop('checked') == true){

    		$('.shipping_amount').html('RM 0.00');
    		currentTotal = parseFloat(sub);

    		if(parent_payment_method == 1){
    			// alert(currentTotal);
    			// $('.processing_amount').html('RM '+parseFloat(currentTotal * 1.6 / 100).toFixed(2));
    			currentTotal = parseFloat(currentTotal);
    		}else{
    			$('.processing_amount').html('RM 0.00');
    			currentTotal = parseFloat(currentTotal);
    		}
    		$('.grand-total').html('RM '+parseFloat(currentTotal).toFixed(2));
    		$('.hidden_grand_total').val(parseFloat(currentTotal).toFixed(2));
    		$('.cod_address_area').show();
    	}else{
    		$('.shipping_amount').html('RM '+parseFloat(shipping_fee).toFixed(2));
    		currentTotal = parseFloat(sub) + parseFloat(shipping_fee);

    		if(parent_payment_method == 1){
    			$('.processing_amount').html('RM '+parseFloat(currentTotal * 1.6 / 100).toFixed(2));
    			currentTotal = parseFloat(currentTotal);
    		}else{
    			currentTotal = parseFloat(currentTotal);
    			$('.processing_amount').html('RM 0.00');
    		}

    		$('.grand-total').html('RM '+parseFloat(currentTotal).toFixed(2));
    		$('.hidden_grand_total').val(parseFloat(currentTotal).toFixed(2));
    		$('.cod_address_area').hide();
    	}
    });

    $('.same-billing-address').click(function (e){
    	if($(this).prop('checked') == true){
    		$('.bill_fill_in').hide("easing");
    	}else{
    		$('.bill_fill_in').show("easing");
    	}
    });

    $('.cod_address').change( function(){
		var ele = $(this);

		var fd = new FormData();
			fd.append('address_id', ele.val());
			
		$.ajax({
		       url: '{{ route("getAddressDetails") }}',
		       type: 'post',
		       data: fd,
		       contentType: false,
		       processData: false,
		       success: function(response){
		       		if(response == 0){
		       			$('.address_details').html('');
		       		}else{
		       			$('.address_details').html(response);
		       		}
		       }
		});
	});

	$(document).ready( function(){
		var fd = new FormData();
			fd.append('address_id', $('.cod_address').find(':selected').val());
		$('.loading-gif').show();
		$.ajax({
		       url: '{{ route("getAddressDetails") }}',
		       type: 'post',
		       data: fd,
		       contentType: false,
		       processData: false,
		       success: function(response){
		       		$('.loading-gif').hide();
		       		if(response == 0){
		       			$('.address_details').html('');
		       		}else{
		       			$('.address_details').html(response);
		       		}
		       }
		});
	});
</script>

@if(empty($getUserDetails->get_default_shipping_address->id))
<script type="text/javascript">
	$('#staticBackdrop').modal();
	$('#staticBackdrop').addClass('show');
</script>
@endif

@endsection