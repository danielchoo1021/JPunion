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
        <h2>{{ isset($data['lang']['lang']['checkout']) ? $data['lang']['lang']['checkout'] :'结算' }}</h2>
    </div>
</div>
<div class="cart_section mt-3">
	<div class="container p-t-65 p-b-60">
		<form method="POST" action="{{ route('placeOrder') }}" id="placeorder-form" enctype="multipart/form-data">
		@csrf
			<input type="hidden" name="cart_link" value="{{ !empty(request('cl')) ? request('cl') : NULL }}">
			<div class="form-group">
				<div class="container-box">
					<div class="form-group">
						<div class="row">
							<div class="col-lg-6 col-md-6 col-sm-7 col-7">
								<h4>{{ isset($data['lang']['lang']['recipient_address']) ? $data['lang']['lang']['recipient_address'] :'收件人地址' }}</h4>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-5 col-5" align="right">
								<a href="#" class="btn change-address set_button set_text" data-toggle="modal" data-target="#change-address">
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
								@if(!$CodAddresses->isEmpty())
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
								@endif
								<i class="fa fa-book w-23" aria-hidden="true"></i>
								{{ isset($data['lang']['lang']['billing_checkbox']) ? $data['lang']['lang']['billing_checkbox'] :'寄付帐单地址与送货地址相同' }}:  <input type="checkbox" name="same_billing_address" class="same-billing-address" value="1" checked>
							</div>
							<!-- <div class="form-group">
								<i class="fa fa-university w-23" aria-hidden="true"></i>
								{{ isset($data['lang']['lang']['store_in_stock']) ? $data['lang']['lang']['store_in_stock'] :'储存进货仓' }}:  <input type="checkbox" name="store_stock" class="store-in-stock" value="1">
							</div> -->
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
										<b>{{ isset($data['lang']['lang']['product_detail']) ? $data['lang']['lang']['product_detail'] :'产品详细' }}</b>
										<input type="text" class="create_link_id" id="create_link_id" value="{{ route('checkout') }}" style="height: 0; position: absolute; z-index: -1; padding: 0; border: none;">
									</div>
									@if(Auth::guard('web')->check() || 
										Auth::guard('agent')->check() &&
										empty(request('cl')))
									<div class="col-6" align="right">
										<a href="#" class="btn btn-primary btn-sm share-cart-link set_button set_text">
											{{ isset($data['lang']['lang']['share_your_cart_link']) ? $data['lang']['lang']['share_your_cart_link'] :'分享购物车' }}
										</a>
									</div>
									@endif
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
										
										<a href="{{ route('details', md5($cart->get_product_det->id)) }}">
											<img src="{{ (!empty($cart->get_product_det->first_image->image)) ? asset($cart->get_product_det->first_image->image) : asset('images/no-image-available-icon-61.jpg') }}" style="width: 100%;">
										</a>
									</div>
									<div class="col-10 order-details">
										<div class="row">
											<div class="col-sm-3">
												<div class="form-group">
													@if(!empty($cart->promo))
														<span class="badge bg-danger">
															{{ isset($data['lang']['lang']['promotion_item']) ? $data['lang']['lang']['promotion_item'] :'优惠产品' }}
														</span>
														<br>
													@endif
													

													<a href="{{ route('details', md5($cart->get_product_det->id)) }}">
														<!-- {{ $cart->get_product_det->product_name }} -->
														@if(isset($_COOKIE['global_language']) && !empty($_COOKIE['global_language']))
											                @if($_COOKIE['global_language'] == '1')
											                    @if(!empty($cart->get_product_det->product_name_cn))
											                        {{ $cart->get_product_det->product_name_cn }}
											                    @else
											                        {{ $cart->get_product_det->product_name }}
											                    @endif
											                @else
											                    {{ $cart->get_product_det->product_name }}
											                @endif
											            @else
											                {{ $cart->get_product_det->product_name }}
											            @endif

														@if($cart->get_product_det->variation_enable == '1')
															<br>
															Option: {{ !empty($cart->get_fv_det->variation_name) ? $cart->get_fv_det->variation_name : NULL }}
														@endif
														@if($cart->get_product_det->second_variation_enable == '1')
															<br>
															Second Option: {{ !empty($cart->get_sv_det->variation_name) ? $cart->get_sv_det->variation_name : NULL }}
														@endif
														@if(!empty($cart->add_on_id) && empty($cart->main_add_on))
														<br>
															<span class="badge badge-info">{{ isset($data['lang']['lang']['add_on_deals']) ? $data['lang']['lang']['add_on_deals'] :'Add-on deal' }}</span>
														@endif
														@if($flash_sale_active[$cart->id])
														<br>
															<span class="badge bg-danger">{{ isset($data['lang']['lang']['flash_sale']) ? $data['lang']['lang']['flash_sale'] :'Flash Sale' }}</span>
														@endif
                                                        <!-- @if(!empty($product_pv[$cart->id]))
                                                        	<br>
                                                            {{ $product_pv[$cart->id] }} PV
                                                        @else
                                                        	<br>
                                                            0 PV
                                                        @endif -->
													</a>

													@if($cart->get_product_det->store_stock == 1 && Auth::guard('agent')->check())
													<label class="checkbox" style="font-size: 14px; margin-bottom: 0px; display: none;">
														<input type="checkbox" name="store_in_stock[]" class="store_in_stock" value="{{ $cart->get_product_det->id }}" checked> Store in stock
													</label>
													@endif
													
													
													<br>
													@if($cart->get_product_det->variation_enable == '1')
														@if($cart->get_product_det->second_variation_enable == 1)
															<!-- {{ !empty($cart->get_sv_det->variation_weight) ? 'Weight: '.$cart->get_sv_det->variation_weight.'KG' : '' }} -->
															@if(!empty($cart->get_sv_det->variation_weight))
																{{ isset($data['lang']['lang']['weight']) ? $data['lang']['lang']['weight'] : 'Weight' }}: {{ $cart->get_sv_det->variation_weight }}KG
															@endif
														@else
															<!-- {{ !empty($cart->get_fv_det->variation_weight) ? 'Weight: '.$cart->get_fv_det->variation_weight.'KG' : '' }} -->
															@if(!empty($cart->get_fv_det->variation_weight))
																{{ isset($data['lang']['lang']['weight']) ? $data['lang']['lang']['weight'] : 'Weight' }}: {{ $cart->get_fv_det->variation_weight }}KG
															@endif
														@endif
													@else
														<!-- {{ !empty($cart->get_product_det->weight) ? 'Weight: '.$cart->get_product_det->weight.'KG' : '' }} -->
														@if(!empty($cart->get_product_det->weight))
															{{ isset($data['lang']['lang']['weight']) ? $data['lang']['lang']['weight'] : 'Weight' }}: {{ $cart->get_product_det->weight }}KG
														@endif
													@endif
													@if($cart->get_product_det->variation_enable == '1')
														@if($cart->get_product_det->second_variation_enable == 1)
															@if(!empty($cart->get_sv_det->variation_get_point))
																<br>
																<span>{{ isset($data['lang']['lang']['receive_point']) ? $data['lang']['lang']['receive_point'] : 'Receive Point' }} : {{ $cart->get_sv_det->variation_get_point }}</span>
															@endif
														@else
															@if(!empty($cart->get_fv_det->variation_get_point))
																<br>
																<span>{{ isset($data['lang']['lang']['receive_point']) ? $data['lang']['lang']['receive_point'] : 'Receive Point' }} : {{ $cart->get_fv_det->variation_get_point }}</span>
															@endif
														@endif
													@else
														@if(!empty($cart->get_product_det->get_point) && $cart->get_product_det->get_point > 0)
														<br>
														<span>{{ isset($data['lang']['lang']['receive_point']) ? $data['lang']['lang']['receive_point'] : 'Receive Point' }} : {{ $cart->get_product_det->get_point }}</span>
														@endif
													@endif
													<br>
													@if(isset($remaining_flash_sales_limit[$cart->id]))
													<span class="important-text">{{ isset($data['lang']['lang']['remaining_promotion_stock']) ? $data['lang']['lang']['remaining_promotion_stock'] :'Remaining Promotion Stocks' }}: {{ $remaining_flash_sales_limit[$cart->id] }}</span>
													@endif
													<br>
													<a href="#" class="important-text non-load delete-cart-btn" data-id="{{ md5($cart->id) }}">
														<i class="fa fa-trash"></i>  {{ isset($data['lang']['lang']['remove_item']) ? $data['lang']['lang']['remove_item'] :'删除' }}
													</a>
												</div>
											</div>

											@php
												$product_price = floatval($get_pricing[$cart->id]['product_price']);
											@endphp
											<div class="col-sm-3" align="center">
											<div class="form-group product_price">
												@if(!empty($cart->add_on_id) && empty($cart->main_add_on))
													RM {!! number_format($sub_item_price[$cart->id], 2) !!}
													<br>
													<small>
														<del>
															RM {!! number_format($product_price, 2) !!}
														</del>
													</small>
												@else
													RM {!! number_format($product_price, 2) !!}
													@if(!empty($get_original_pricing[$cart->id]['product_price']))
														<br>
														<small>
															<del>
																RM {!! number_format(floatval($get_original_pricing[$cart->id]['product_price']), 2) !!}
															</del>
														</small>
													@elseif(!empty($get_pricing[$cart->id]['product_special_price']))
													<br>
													<small>
														<del>
															RM {!! $get_pricing[$cart->id]['product_special_price'] !!}
														</del>
													</small>
													@endif
												@endif
											</div>
										</div>
											
										<div class="col-sm-4" align="center">
											<div class="form-group quantity-setting">
												@if((!empty($cart->add_on_id) && empty($cart->main_add_on)) || $cart->status == '3')
													x {{ $cart->qty }}
												@elseif(!empty(request('cl')))
													x {{ $cart->qty }}
												@else
													@if(isset($remaining_flash_sales_limit[$cart->id]))
														<button class="btn btn-primary deduct-qty-button" {{$remaining_flash_sales_limit[$cart->id] <= 0 ?'readonly disabled' : ''}}>
															<i class="fa fa-minus"></i>
														</button>
														<input type="text" class="form-control" name="quantity" value="{{ $cart->qty }}" onkeypress="return isNumberKey(event)" {{$remaining_flash_sales_limit[$cart->id] <= 0 ?'readonly disabled' : ''}}>
														<button class="btn btn-primary add-qty-button" type="button" {{$remaining_flash_sales_limit[$cart->id] <= 0 ?'readonly disabled' : ''}}>
															<i class="fa fa-plus"></i>
														</button>
													@else
													<button class="btn btn-primary deduct-qty-button">
														<i class="fa fa-minus"></i>
													</button>
													<input type="text" class="form-control" name="quantity" value="{{ $cart->qty }}" onkeypress="return isNumberKey(event)">
													<button class="btn btn-primary add-qty-button" type="button">
														<i class="fa fa-plus"></i>
													</button>
													@endif
												@endif
											</div>
										</div>
										<div class="col-sm-2" align="right">
											<div class="form-group product-total-price">
												@if(!empty($cart->add_on_id) && empty($cart->main_add_on))
													RM {{ number_format($sub_item_price[$cart->id] * $cart->qty, 2) }}
												@else
													RM {{ number_format($product_price * $cart->qty, 2) }}
												@endif
											</div>
										</div>
									</div>
								</div>
							</div>
							<hr>
						</div>
						
						@php

						if(!empty($cart->add_on_id) && empty($cart->main_add_on)){
							$totalPrice += $sub_item_price[$cart->id] * $cart->qty;
						}else{
							$totalPrice += $product_price * $cart->qty;
						}
						
						if($cart->get_product_det->variation_enable == '1'){
							if($cart->get_product_det->second_variation_enable == '1'){
								if(!empty($cart->get_sv_det->variation_weight)){
									$totalWeight += $cart->get_sv_det->variation_weight * $cart->qty;
								}
							}else{
								if(!empty($cart->get_fv_det->variation_weight)){
									$totalWeight += $cart->get_fv_det->variation_weight * $cart->qty;
								}
							}
						}else{
							if(!empty($cart->get_product_det->weight)){
								$totalWeight += $cart->get_product_det->weight * $cart->qty;
							}
						}
						@endphp
						@endforeach
						@if(empty($applied_voucher->id))
						<div class="form-group promotion-field">
							<div class="row">
								<div class="col-6">
									{{ isset($data['lang']['lang']['have_voucher']) ? $data['lang']['lang']['have_voucher'] :'有优惠券' }}?
								</div>
								<div class="col-6" align="right">
									<a href="#" data-toggle="modal" data-target="#applyPromotion">
										  {{ isset($data['lang']['lang']['apply_a_voucher']) ? $data['lang']['lang']['apply_a_voucher'] :'申请优惠券' }}
									</a>

									<div class="modal fade" id="applyPromotion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="z-index: 9999;">
										  <div class="modal-dialog modal-dialog-centered">
											<div class="modal-content">
												  <div class="modal-header">
													<h5 class="modal-title" id="exampleModalLabel" align="center">
														{{ isset($data['lang']['lang']['apply_voucher']) ? $data['lang']['lang']['apply_voucher'] : 'Apply Voucher' }}
													</h5>
													<button type="button" class="close close-promo-list" data-dismiss="modal" aria-label="Close">
														  <span aria-hidden="true">&times;</span>
													</button>
												  </div>
												  <div class="modal-body">
													<div class="input-group">
														<input type="text" name="result" class="form-control discount-code" placeholder="{{ isset($data['lang']['lang']['voucher_code']) ? $data['lang']['lang']['voucher_code'] : 'Voucher Code' }}"
															   value="">
														<span class="input-group-btn">
															<button type="submit" class="btn btn-primary btn-white apply-discount set_button set_text">
																{{ isset($data['lang']['lang']['apply']) ? $data['lang']['lang']['apply'] : 'Apply' }}
															</button>
														</span>
													</div>
													<div class="error-message-promo important-text" style="display: none;"></div>
													<hr>
													@if(!empty($vouchers))
														@foreach($vouchers as $getClaimedPromo)
														<div class="form-group" align="left">
															<input type="hidden" name="apid" class="apid" value="{{ $getClaimedPromo->id }}">
															<a href="#" class="claim-voucher" style="display: block; width: 100%;" data-id="{{ $getClaimedPromo->discount_code }}" data-voucher-id="{{ $getClaimedPromo->id }}">
																<div class="row">
																	<div class="col-3">
																		<img src="{{ asset(!empty($getClaimedPromo->image) ? $getClaimedPromo->image : 'images/no-image-available-icon-61.jpg') }}" style="width: 70px;">
																	</div>
																	<div class="col-9">
																		<b>{{ $getClaimedPromo->promotion_title }}</b><br>
																		@if(!empty($getClaimedPromo->free_shipping))
																		Offer: Free Shipping Voucher <br>
																		@else
																		Offer: {{ ($getClaimedPromo->amount_type == 'Percentage') ? $getClaimedPromo->amount."%" : 'RM '.$getClaimedPromo->amount }} OFF<br>
																		@endif
																		Expiry: {{ $getClaimedPromo->end_date }}<br>
																		Discount Code: {{ $getClaimedPromo->discount_code }}</br>
																		Quantity: {{$get_quantity[$getClaimedPromo->discount_code] }}
																	</div>
																</div>
															</a>
														</div>
														<hr>
														@endforeach
													@endif
												  </div>
												  <button type="button" class="btn btn-secondary close-modal set_button set_text" data-dismiss="modal" style="display: none;">{{ isset($data['lang']['lang']['close']) ? $data['lang']['lang']['close'] : 'Close' }}</button>
												
											</div>
										  </div>
									</div>
								</div>
							</div>
						</div>
						@endif
						@php
							$applied_discount_type = "";

							if(!empty($applied_voucher->get_voucher_detail->id)){
								if(empty($applied_voucher->get_voucher_detail->amount_type)){
									$applied_discount_type = "RM 0";
								}elseif($applied_voucher->get_voucher_detail->amount_type == 'Percentage'){
									$applied_discount_type = $applied_voucher->get_voucher_detail->amount."%";
								}else{
									$applied_discount_type = "RM ".$applied_voucher->get_voucher_detail->amount;
								}
							}
						@endphp
						<div class="form-group">
							<div class="success-message-promo green">
								@if(!empty($applied_voucher->get_voucher_detail->free_shipping) && $applied_voucher->get_voucher_detail->free_shipping == '1')
								Applied Voucher - {{ $applied_voucher->get_voucher_detail->discount_code }} (Free Shipping Voucher)
								@else
								{{ (!empty($applied_voucher->get_voucher_detail->id)) ? "Applied Voucher - ".$applied_voucher->get_voucher_detail->discount_code."(".$applied_discount_type.")" : '' }}
								@endif
								@if(!empty($applied_voucher->get_voucher_detail->id))
									<a href="#" class="remove-applied-promo pull-right" data-id="{{ $applied_voucher->id }}">
										{{ isset($data['lang']['lang']['remove']) ? $data['lang']['lang']['remove'] : 'Remove' }}
									</a>
								@endif
							</div>
							
						</div>
						<hr>

						@php
							if(!empty($get_cart_details['sub_total'])){
								$totalPrice = $get_cart_details['sub_total'];
							}

							if(!empty($cart_link_modified_price)){
								$totalPrice = $cart_link_modified_price;
							}
						@endphp

						<div class="form-group">
							<div class="row">
								<div class="col-6">
									<b>{{ isset($data['lang']['lang']['sub_total']) ? $data['lang']['lang']['sub_total'] :'合计' }}: </b>
								</div>
								<div class="col-6" align="right">
									<b>RM <span class="sub-total-display">{{ number_format($totalPrice, 2) }}</span> </b>
									<input type="hidden" name="sub_total" class='sub-total-value' id="subtotal" value="{{ $totalPrice }}">
								</div>
							</div>
						</div>
						<hr>

						@if($totalWeight > 0)
						
						<div class="form-group">
							<div class="row">
								<div class="col-6">
									<b>{{ isset($data['lang']['lang']['total_weight']) ? $data['lang']['lang']['total_weight'] :'合计' }}({{ isset($data['lang']['lang']['kg']) ? $data['lang']['lang']['kg'] :'公斤' }}): </b>
								</div>
								<div class="col-6" align="right">

									<b class="total-weight-display">{{ number_format($totalWeight, 2) }}</b>
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
										{{ isset($data['lang']['lang']['shipping_fee']) ? $data['lang']['lang']['shipping_fee'] :'运费' }}: 
									</b>
								</div>
								<div class="col-6" align="right">
									@if(!empty(request('m')) && request('m') == '1')
										<b class="shipping_amount">{{ number_format($get_cart_details['total_shipping_fee'], 2) }} point</b>
									@else
										<b class="shipping_amount">RM {{ number_format($get_cart_details['total_shipping_fee'], 2) }}</b>
									@endif
								</div>
							</div>
						</div>
						<hr>

						@php
							$applied_discount_amount = 0;

							if(!empty($applied_voucher->get_voucher_detail->id)){

								if($applied_voucher->get_voucher_detail->free_shipping == '1'){
									$applied_discount_amount = $get_cart_details['total_shipping_fee'];
								}else{
									if($applied_voucher->get_voucher_detail->amount_type == 'Percentage'){
										$applied_discount_amount = (float) $totalPrice * $applied_voucher->get_voucher_detail->amount / 100;
									}else{
										$applied_discount_amount = (float) $applied_voucher->get_voucher_detail->amount;
									}
								}
								
								$maxCapped = (float)($applied_voucher->get_voucher_detail->maxCapped ?? 0);
								if ($maxCapped > 0 && $applied_discount_amount > $maxCapped) {
									$applied_discount_amount = $maxCapped;
								}
							}
						@endphp

						<div class="form-group">
							<input type="hidden" name="discount_code" id="code" value="{{ (!empty($applied_voucher->get_voucher_detail->id)) ? $applied_voucher->get_voucher_detail->promotion_id : '' }}">
							<input type="hidden" name="discount" id="totalDiscount" value="{{ $applied_discount_amount }}">
							<div class="row">
								<div class="col-6">
									<b class="discount_word">
										{{ isset($data['lang']['lang']['discount_amount']) ? $data['lang']['lang']['discount_amount'] :'折扣' }}{{ !empty($applied_discount_type) ? "(".$applied_discount_type.")" : '' }}: 
									</b>
								</div>
								<div class="col-6" align="right">
									@if(!empty($applied_discount_amount))
										<b class="discount_amount">RM {{ number_format($applied_discount_amount, '2') }}</b>
									@else
										<b class="discount_amount">RM 0.00</b>
									@endif
									<input type="hidden" name="hidden_discount" class="hidden_discount" value="{{ !empty($applied_discount_amount) ? $applied_discount_amount : '' }}">
								</div>
							</div>
						</div>
						<hr>
						
						@php
						$processing_fee = 0;
						@endphp
						<div class="form-group">
							<div class="row">
								<div class="col-6">
									<b style="font-size: 20px;">{{ isset($data['lang']['lang']['grand_total']) ? $data['lang']['lang']['grand_total'] :'总合计' }}: </b>
								</div>
								<div class="col-6" align="right" style="font-size: 20px;">
									@php
										$totalGrand = ($totalPrice - $applied_discount_amount + $get_cart_details['total_shipping_fee'] + $processing_fee);

										if ($totalGrand < 0) {
											$totalGrand = 0;
										}
									@endphp
									<b class="grand-total">RM {{ number_format(($totalGrand), 2) }}</b>

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
									<i class="fa fa-credit-card-alt" aria-hidden="true"></i> {{ isset($data['lang']['lang']['payment_method']) ? $data['lang']['lang']['payment_method'] :'付款方式' }}
								</h4>

								<div class="widget-toolbar no-border">
									<ul class="nav nav-tabs" id="recent-tab">
										<!-- <li class="parent_payment_method active">
											<a data-toggle="tab" class="payment_method f-15" data-id="1" href="#online-tab">Online Banking</a>
										</li> -->

										<li class="parent_payment_method active">
											<a data-toggle="tab" class="payment_method f-15" data-id="2" href="#cdm-tab">
												{{ isset($data['lang']['lang']['bank_transfer']) ? $data['lang']['lang']['bank_transfer'] :'银行转帐' }}
											</a>
										</li>
										
										@if(Auth::guard('web')->check() || Auth::guard('agent')->check())
										<li class="parent_payment_method">
											<a data-toggle="tab" class="payment_method f-15" data-id="4" href="#cash-wallet-tab">Cash Wallet</a>
										</li>
										<li class="parent_payment_method">
											<a data-toggle="tab" class="payment_method f-15" data-id="3" href="#topup-wallet-tab">Topup Wallet</a>
										</li>
										@endif

										@if ($data['senangpay_setting']->status == '1')
											<li class="parent_payment_method">
												<a data-toggle="tab" class="payment_method f-15" data-id="5" href="#senang-pay-tab" data-payment-gateway-setting-id="{{ $data['senangpay_setting']->id }}">
													{{ $data['senangpay_setting']->name }}
												</a>
											</li>
										@endif

										{{-- <li class="parent_payment_method">
											<a data-toggle="tab" class="payment_method f-15" data-id="6" href="#revpay-tab" data-payment-gateway-setting-id="{{ $data['revpay_setting']->id }}">
												{{ $data['revpay_setting']->name }}
											</a>
										</li>

										<li class="parent_payment_method">
											<a data-toggle="tab" class="payment_method f-15" data-id="7" href="#surepay-tab" data-payment-gateway-setting-id="{{ $data['surepay_setting']->id }}">
												{{ $data['surepay_setting']->name }}
											</a>
										</li> --}}

										@if ($data['gkash_setting']->status == '1')
											<li class="parent_payment_method">
												<a data-toggle="tab" class="payment_method f-15" data-id="8" href="#gkash-tab" data-payment-gateway-setting-id="{{ $data['gkash_setting']->id }}">
													{{ $data['gkash_setting']->name }}
												</a>
											</li>
										@endif
									</ul>
								</div>
							</div>
						</div>

							<div class="widget-body">
								<div class="widget-main padding-4">
									<div class="tab-content padding-8">
										<div id="online-tab" class="tab-pane ">
											<div class="form-group" align="center">
												<h4>Support By</h4>
												<br>
												<img src="{{ asset('images/cropped-Premier-Pay-LOGO-3-1.png') }}" width="150">
											</div>
											<input type="checkbox" name="bank_id" value="1" checked style="display: none;">

											<div class="form-group">
												<b id="error-message-banks" class="important-text"></b>
											</div>
											
											<!-- <div class="form-group">
												@if(!empty($_COOKIE['new_guest']) && empty(Auth::guard($data['userGuardRole'])->check()) && empty(Session::get('continue_guest')))
													<button type="button" class="btn btn-primary btn-block bg-color" data-toggle="modal" data-target="#login-required">
													  Place order now
													</button>
												@else
													<button class="btn btn-primary btn-block placeorder-btn bg-color"> Place order now </button>
												@endif
											</div> -->
											<div class="form-group">
												<button class="btn btn-block placeorder-btn bg-color set_button set_text"> Place order now </button>
											</div>

											<div class="form-group">
												<a href="{{ route('listing') }}" 
												   class="btn btn-block bg-color not-same-bg set_button set_text"> 
													Continue Shopping
												</a>
											</div>

											<input type="hidden" name="online" value="0">
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

											<input type="hidden" name="cdm" value="1">
											<div class="form-group">
												<input type="file" name="bank_slip" class="form-control" accept="image/*">
											</div>

											<div class="form-group">
												<b id="error-message-cdm-banks" class="important-text"></b>
											</div>
											
											<!-- <div class="form-group">
												@if(!empty($_COOKIE['new_guest']) && empty(Auth::guard($data['userGuardRole'])->check()) && empty(Session::get('continue_guest')))
													<button type="button" class="btn btn-primary btn-block bg-color" data-toggle="modal" data-target="#login-required">
													  {{ isset($data['lang']['lang']['place_order_now']) ? $data['lang']['lang']['place_order_now'] :'立即下单' }}
													</button>
												@else
													<button class="btn btn-primary btn-block cdm-placeorder-btn bg-color"> {{ isset($data['lang']['lang']['place_order_now']) ? $data['lang']['lang']['place_order_now'] :'立即下单' }} </button>
												@endif
											</div> -->
											<div class="form-group">
												<button class="btn btn-block cdm-placeorder-btn bg-color set_button set_text"> {{ isset($data['lang']['lang']['place_order_now']) ? $data['lang']['lang']['place_order_now'] :'立即下单' }} </button>
											</div>

											<div class="form-group">
												<a href="{{ route('listing') }}" 
												   class="btn btn-block bg-color not-same-bg set_button set_text"> 
													{{ isset($data['lang']['lang']['continue_shopping']) ? $data['lang']['lang']['continue_shopping'] :'继续购物' }}
												</a>
											</div>
										</div>
										
										<div id="cash-wallet-tab" class="tab-pane" align="center">
											<div class="form-group">
												<input type="hidden" name="wallet_bank_id" value="10000743">
												<div class="row">
													<div class="col-12" align="center"> 
														RM 
														<span class="wallet-balance-amount">
															{{ number_format($get_cash_wallet_balance, 2) }}
														</span>
														<br>
														<span class="wallet-desc profile-word">Remaining Wallet Balance</span>
													</div>
												</div>
											</div>
											<div class="form-group bank_details">

											</div>

											<input type="hidden" name="cash_wallet">

											<div class="form-group">
												<b id="error-message-wallet-banks" class="important-text"></b>
											</div>
											<div class="form-group">
												<button class="btn btn-block cash-wallet-placeorder-btn bg-color set_button set_text"> 
													{{ isset($data['lang']['lang']['place_order_now']) ? $data['lang']['lang']['place_order_now'] :'立即下单' }}
												</button>
											</div>

											<div class="form-group">
												<a href="{{ route('listing') }}" 
												   class="btn btn-block bg-color not-same-bg set_button set_text"> 
													{{ isset($data['lang']['lang']['continue_shopping']) ? $data['lang']['lang']['continue_shopping'] :'继续购物' }} 
												</a>
											</div>
										</div>
										
										<div id="topup-wallet-tab" class="tab-pane" align="center">
											<div class="form-group">
												<input type="hidden" name="wallet_bank_id" value="10000743">
												<div class="row">
													<div class="col-12" align="center"> 
														RM 
														<span class="wallet-balance-amount">
															{{ number_format($get_topup_wallet_balance, 2) }}
														</span>
														<br>
														<span class="wallet-desc profile-word">Remaining Wallet Balance</span>
													</div>
												</div>
											</div>
											<div class="form-group bank_details">

											</div>

											<input type="hidden" name="topup_wallet">

											<div class="form-group">
												<b id="error-message-wallet-banks" class="important-text"></b>
											</div>
											<div class="form-group">
												<button class="btn btn-block topup-wallet-placeorder-btn bg-color set_button set_text"> 
													{{ isset($data['lang']['lang']['place_order_now']) ? $data['lang']['lang']['place_order_now'] :'立即下单' }}
												</button>
											</div>

											<div class="form-group">
												<a href="{{ route('listing') }}" 
												   class="btn btn-block bg-color not-same-bg set_button set_text"> 
													{{ isset($data['lang']['lang']['continue_shopping']) ? $data['lang']['lang']['continue_shopping'] :'继续购物' }} 
												</a>
											</div>
										</div>
										
										
										<input type="hidden" name="payment_gateway_setting_id">
										@if ($data['senangpay_setting']->status == '1')
											<div id="senang-pay-tab" class="tab-pane">
												<div class="form-group" align="center">
													<h4>Support By</h4>
													<br>
													<img src="{{ asset('images/senang_pay.png') }}" width="150">
												</div>

												<div class="form-group">
													<b id="error-message-banks" class="important-text"></b>
												</div>
												
												<div class="form-group">
													<button class="btn btn-primary btn-block placeorder-btn bg-color set_button set_text "> Place order now </button>
												</div>

												<div class="form-group">
													<a href="{{ route('listing') }}" 
														class="btn btn-primary btn-block  set_button set_text"> 
														Continue Shopping 
													</a>
												</div>
											</div>
										@endif

										{{-- <div id="revpay-tab" class="tab-pane">
											<div class="form-group">
												<div class="row">
													<div class="col-md-4" align="center" style="display: flex; justify-content: center; align-items: center; margin-bottom: 2.5em; flex-direction: column;">
														<span class="payment_method_title">FPX</span>
														<label>
															<input type="radio" name="payment_id" value="3">
															<img src="{{ asset('images/payments/fpx.png') }}">
														</label>
													</div>
													<div class="col-md-4" align="center" style="display: flex; justify-content: center; align-items: center; margin-bottom: 2.5em; flex-direction: column;">
														<span class="payment_method_title">UNION PAY</span>
														<label>
															<input type="radio" name="payment_id" value="6">
															<img src="{{ asset('images/payments/union_pay.png') }}">
														</label>
													</div>
													<div class="col-md-4" align="center" style="display: flex; justify-content: center; align-items: center; margin-bottom: 2.5em; flex-direction: column;">
														<span class="payment_method_title">Visa / Master</span>
														<label>
															<input type="radio" name="payment_id" value="2">
															<img src="{{ asset('images/payments/png-clipart-mastercard-visa-bank-card-payment-mastercard-text-service.png') }}">
														</label>
													</div>
												</div>
											</div>
											<div class="form-group">
												<div class="row">
													<div class="col-md-4" align="center" style="display: flex; justify-content: center; align-items: center; margin-bottom: 2.5em; flex-direction: column;">
														<span class="payment_method_title">GRAB PAY</span>
														<label>
															<input type="radio" name="payment_id" value="17">
															<img src="{{ asset('images/payments/grab_pay.png') }}">
														</label>
													</div>
													<div class="col-md-4" align="center" style="display: flex; justify-content: center; align-items: center; margin-bottom: 2.5em; flex-direction: column;">
														<span class="payment_method_title">Touch N Go</span>
														<label>
															<input type="radio" name="payment_id" value="28">
															<img src="{{ asset('images/payments/tng.png') }}">
														</label>
													</div>
													<div class="col-md-4" align="center" style="display: flex; justify-content: center; align-items: center; margin-bottom: 2.5em; flex-direction: column;">
														<span class="payment_method_title">BOOST</span>
														<label>
															<input type="radio" name="payment_id" value="11">
															<img src="{{ asset('images/payments/boost.png') }}">
														</label>
													</div>
												</div>
											</div>
											<div class="form-group">
												<div class="row">
													<div class="col-md-4" align="center" style="display: flex; justify-content: center; align-items: center; margin-bottom: 2.5em; flex-direction: column;">
														<span class="payment_method_title">ALIPAY</span>
														<label>
															<input type="radio" name="payment_id" value="36">
															<img src="{{ asset('images/payments/alipay.png') }}">
														</label>
													</div>
												</div>
											</div>
											

											<div class="form-group">
												<b id="error-message-banks" class="error-message important-text"></b>
											</div>
											
											<div class="form-group">
												<button class="btn btn-primary btn-block set_button set_text"> Place order now </button>
											</div>

											<div class="form-group">
												<a href="{{ route('listing') }}" 
													class="btn btn-primary btn-block set_button set_text"> 
													Continue Shopping 
												</a>
											</div>
										</div> --}}

										
										{{-- <div id="surepay-tab" class="tab-pane" align="center">
											<div class="form-group" align="center">
												<h4>Support By</h4>
												<br>
												<img src="{{ asset('images/payments/surepay.png') }}" width="150">
											</div>
											<div class="form-group">
												<button class="btn btn-primary btn-block placeorder-btn bg-color set_button set_text"> 
													{{ isset($data['lang']['lang']['place_order_now']) ? $data['lang']['lang']['place_order_now'] :'立即下单' }}
												</button>
											</div>

											<div class="form-group">
												<a href="{{ route('listing') }}" 
													class="btn btn-primary btn-block bg-color not-same-bg
																-red set_button set_text"> 
													Continue Shopping 
												</a>
											</div>
										</div> --}}


										@if ($data['gkash_setting']->status == '1')
											<div id="gkash-tab" class="tab-pane" align="center">
												<div class="form-group" align="center">
													<h4>Support By</h4>
													<br>
													<img src="{{ asset('images/payments/gkash-logo.png') }}" width="150">
												</div>
												<div class="form-group">
													<button class="btn btn-primary btn-block placeorder-btn bg-color set_button set_text"> 
														{{ isset($data['lang']['lang']['place_order_now']) ? $data['lang']['lang']['place_order_now'] :'立即下单' }}
													</button>
												</div>

												<div class="form-group">
													<a href="{{ route('listing') }}" 
														class="btn btn-primary btn-block bg-color not-same-bg
																	-red set_button set_text"> 
														Continue Shopping 
													</a>
												</div>
											</div>
										@endif
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

<input type="text" name="cart_link_value" class="cart_link_value" id="cart_link_value" style="opacity: 0;">

<div class="modal fade" id="staticBackdrop" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index: 9999;">
<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="height: calc(120vh - 1rem);">
<div class="modal-content" style="box-shadow: 0 2px 10px #bdbdbd;">
  <div class="modal-header">
	<h4>
		{{ isset($data['lang']['lang']['create_new_shipping_address']) ? $data['lang']['lang']['create_new_shipping_address'] : 'Create New Shipping Address' }}
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
			<label>{{ isset($data['lang']['lang']['name']) ? $data['lang']['lang']['name'] : 'Name' }} <span class="important-text">*</span></label>
			<input type="text" class="form-control required-feild" placeholder="{{ isset($data['lang']['lang']['name']) ? $data['lang']['lang']['name'] : 'Name' }} *" name="f_name" value="{{ !empty(Auth::guard($data['userGuardRole'])->user()->f_name) ? Auth::guard($data['userGuardRole'])->user()->f_name : '' }}">
		</div>
		<div class="form-group">
			<label>{{ isset($data['lang']['lang']['email']) ? $data['lang']['lang']['email'] : 'Email' }} <span class="important-text">*</span></label>
			<input type="text" class="form-control required-feild" placeholder="{{ isset($data['lang']['lang']['email']) ? $data['lang']['lang']['email'] : 'Email' }} *" name="email" value="{{ !empty(Auth::guard($data['userGuardRole'])->user()->email) ? Auth::guard($data['userGuardRole'])->user()->email : '' }}">
		</div>
		<div class="form-group">
			<div class="row">
				<div class="col-6">
					<label>{{ isset($data['lang']['lang']['country_code']) ? $data['lang']['lang']['country_code'] : 'Country Code' }} <span class="important-text">*</span></label>
					<select class="form-control country_code" name="country_code" id="country_code" data-live-search="true">
						@foreach($countries as $country)
						<option value="{{ $country->country_contact }}" {{ ($country->country_id == 160) ? 'selected' : '' }}>
							(+{{ $country->country_contact }}) {{ $country->country_name }} 
						</option>
						@endforeach
					</select>
				</div>
				<div class="col-6">
					<label>{{ isset($data['lang']['lang']['phone']) ? $data['lang']['lang']['phone'] : 'Phone' }} <span class="important-text">*</span></label>
					<input type="text" class="form-control required-feild" placeholder="ex: 171234567" name="phone" value="{{ !empty(Auth::guard($data['userGuardRole'])->user()->phone) ? Auth::guard($data['userGuardRole'])->user()->phone : '' }}"  onkeypress="return isNumberKey(event)">
				</div>
			</div>
		</div>
		<div class="form-group">
			<label>{{ isset($data['lang']['lang']['address']) ? $data['lang']['lang']['address'] : 'Address' }} <span class="important-text">*</span></label>
			<textarea class="form-control required-feild" placeholder="{{ isset($data['lang']['lang']['address']) ? $data['lang']['lang']['address'] : 'Address' }} *" name="address"></textarea>
		</div>
		<div class="form-group">
			<label>{{ isset($data['lang']['lang']['state']) ? $data['lang']['lang']['state'] : 'State' }} <span class="important-text">*</span></label>
			<select class="form-control" name="state">
				<option value="">{{ isset($data['lang']['lang']['select_state']) ? $data['lang']['lang']['select_state'] : 'Select State' }}</option>
				@foreach($states as $state)
				<option value="{{ $state->id }}">{{ $state->name }}</option>
				@endforeach
			</select>
		</div>
		<div class="form-group">
			<div class="row">
				<div class="col-6">
					<label>{{ isset($data['lang']['lang']['city']) ? $data['lang']['lang']['city'] : 'City' }} <span class="important-text">*</span></label>
					<input type='text' class="form-control required-feild" placeholder="{{ isset($data['lang']['lang']['city']) ? $data['lang']['lang']['city'] : 'City' }} *" name="city" value="">
				</div>
				<div class="col-6">
					<label>{{ isset($data['lang']['lang']['post_code']) ? $data['lang']['lang']['post_code'] : 'Postcode' }} <span class="important-text">*</span></label>
					<input type='text' class="form-control required-feild" placeholder="{{ isset($data['lang']['lang']['post_code']) ? $data['lang']['lang']['post_code'] : 'Postcode' }} *" name="postcode" value="" onkeypress="return isNumberKey(event)">
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
				{{ isset($data['lang']['lang']['submit']) ? $data['lang']['lang']['submit'] : 'Submit' }}
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
		{{ isset($data['lang']['lang']['my_shipping_address']) ? $data['lang']['lang']['my_shipping_address'] : 'My Shipping Address' }}
	</h5>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	  	<span aria-hidden="true">&times;</span>
	</button>
  </div>
  <form method="POST" action="{{ route('update_address') }}">
	<div class="modal-body" align="left">
		<div class="form-group" align="right">
			<a href="#" data-toggle="modal" data-target="#add-new-address" class="btn btn-primary btn-sm set_button set_text">
				<i class="fa fa-plus"></i> {{ isset($data['lang']['lang']['add_new_address']) ? $data['lang']['lang']['add_new_address'] : 'Add New Address' }}
			</a>
			<a href="{{ route('AddressBook.AddressBook.index') }}" class="btn btn-primary btn-sm set_button set_text">
				{{ isset($data['lang']['lang']['address_manage']) ? $data['lang']['lang']['address_manage'] : 'Address Manage' }}
			</a>
		</div>
		<hr>
		@csrf
		@if(!empty($getUserDetails->get_shipping_address))
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
		@endif
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-secondary set_button set_text " data-dismiss="modal">
			{{ isset($data['lang']['lang']['cancel']) ? $data['lang']['lang']['cancel'] : 'Cancel' }}
		</button>
		<button class="btn btn-primary set_button set_text">
			{{ isset($data['lang']['lang']['save_changes']) ? $data['lang']['lang']['save_changes'] : 'Save Changes' }}
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
			{{ isset($data['lang']['lang']['add_shipping_address']) ? $data['lang']['lang']['add_shipping_address'] : 'Add Shipping Address' }}
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
				{{ isset($data['lang']['lang']['name']) ? $data['lang']['lang']['name'] : 'Name' }} <span class="important-text">*</span>
				<input type="text" class="form-control required-feild" placeholder="{{ isset($data['lang']['lang']['name']) ? $data['lang']['lang']['name'] : 'Name' }} *" name="f_name" value="">
			</div>
			<div class="form-group">
				{{ isset($data['lang']['lang']['email_address']) ? $data['lang']['lang']['email_address'] : 'Email Address' }} <span class="important-text">*</span>
				<input type="text" class="form-control required-feild" placeholder="{{ isset($data['lang']['lang']['email_address']) ? $data['lang']['lang']['email_address'] : 'Email Address' }} *" name="email" value="">
			</div>
			<div class="form-group">
				<div class="row">
					<div class="col-6">
						{{ isset($data['lang']['lang']['country_code']) ? $data['lang']['lang']['country_code'] : 'Country Code' }} <span class="important-text">*</span>
						<select class="form-control country_code" name="country_code" id="country_code" data-live-search="true">
							@foreach($countries as $country)
							<option value="{{ $country->country_contact }}"
								{{ ($country->country_id == 160) ? 'selected' : '' }}
								> (+{{ $country->country_contact }}) {{ $country->country_name }} </option>
							@endforeach
						</select>
					</div>
					<div class="col-6">
						{{ isset($data['lang']['lang']['phone']) ? $data['lang']['lang']['phone'] : 'Phone' }} <span class="important-text">*</span>
						<input type="text" class="form-control required-feild" placeholder="Ex: 121234567" name="phone" value=""  onkeypress="return isNumberKey(event)">
					</div>
				</div>
			</div>
			<div class="form-group">
				{{ isset($data['lang']['lang']['address']) ? $data['lang']['lang']['address'] : 'Address' }} <span class="important-text">*</span>
				<textarea class="form-control required-feild" placeholder="{{ isset($data['lang']['lang']['address']) ? $data['lang']['lang']['address'] : 'Address' }} *" name="address"></textarea>
			</div>
			<div class="form-group">
				{{ isset($data['lang']['lang']['state']) ? $data['lang']['lang']['state'] : 'State' }} <span class="important-text">*</span>
				<select class="form-control" name="state">
					<option>{{ isset($data['lang']['lang']['select_state']) ? $data['lang']['lang']['select_state'] : 'Select State' }}</option>
					@foreach($states as $state)
						<option value="{{ $state->id }}">{{ $state->name }}</option>
					@endforeach
				</select>
			</div>
			<div class="form-group">
				<div class="row">
					<div class="col-6">
						{{ isset($data['lang']['lang']['city']) ? $data['lang']['lang']['city'] : 'City' }} <span class="important-text">*</span>
						<input type='text' class="form-control required-feild" placeholder="{{ isset($data['lang']['lang']['city']) ? $data['lang']['lang']['city'] : 'City' }} *" name="city" value="">
					</div>
					<div class="col-6">
						{{ isset($data['lang']['lang']['post_code']) ? $data['lang']['lang']['post_code'] : 'Postcode' }} <span class="important-text">*</span>
						<input type='text' class="form-control required-feild" placeholder="{{ isset($data['lang']['lang']['post_code']) ? $data['lang']['lang']['post_code'] : 'Postcode' }} *" name="postcode" value="" onkeypress="return isNumberKey(event)">
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="row">
					<div class="col-6">
						<label>{{ isset($data['lang']['lang']['country']) ? $data['lang']['lang']['country'] :'国家'}} <span class="important-text">*</span></label>
						<select class="form-control country" name="country" style="padding: 0.375rem 0.75rem;">
							<option value="">{{ isset($data['lang']['lang']['select_country']) ? $data['lang']['lang']['select_country'] :'选择国家'}}</option>
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
				<button class="btn btn-primary btn-block btn-sm default_btn add-new-address-btn set_button set_text">
					{{ isset($data['lang']['lang']['submit']) ? $data['lang']['lang']['submit'] : 'Submit' }}
				</button>
				<br>
				<button type="button" class="btn btn-secondary btn-block btn-sm set_button set_text" data-dismiss="modal">
					{{ isset($data['lang']['lang']['cancel']) ? $data['lang']['lang']['cancel'] : 'Cancel' }}
				</button>
			</div>
		</form>
	</div>
</div>
</div>
</div>

<div class="modal fade" id="cart-link-modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index: 9999;">
  	<div class="modal-dialog modal-lg modal-dialog-centered" style="height: calc(100vh - 1rem);">
		<div class="modal-content" style="box-shadow: 0 2px 10px #bdbdbd;">
		  	<div class="modal-body" style="overflow: auto;">
				<div class="form-group" style="text-align: center;">
					<img src="{{ asset('images/fail_641034.png') }}" width="200px" style="padding: 20px">
					<p style="font-size: 16px; font-weight: bold;">
						The product link has exceeded purchase limit.
					</p>
					<p style="font-size: 14px;">
						Please request for a new link to continue purchase.
					</p>
				</div>
		  	</div>
		  	<div class="modal-footer">
			  	<a href="{{ route('home') }}" class="btn btn-secondary btn-block btn-sm set_button set_text">
					{{ isset($data['lang']['lang']['back_to_home']) ? $data['lang']['lang']['back_to_home'] : 'Back To Home' }}
				</a>
		  	</div>
		</div>
 	</div>
</div>

@endsection
@section('js')
<script type="text/javascript">

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
	var grand_total = $('#hidden_grand_total').val();
	var m = '{{ request("m") }}';

	if(!$("input[name='bank_id']:checked").val()){
		$('#error-message-banks').html('Please select bank to continue payment.');
		$('.loading-gif').hide();
		return false;
	}

	$('#placeorder-form').submit();
});

$('.revpay-placeorder-btn').click( function(e){
	e.preventDefault();
	$('.loading-gif').show();
	
	if(!$("input[name='payment_id']:checked").val()){
		$('#error-message-banks').html('Please select payment to continue payment.');
		$('.loading-gif').hide();
		return false;
	}

	$('input[name="revpay"]').val(1);
	$('input[name="cdm"]').val(0);
	$('input[name="topup_wallet"]').val(0);
	$('input[name="cash_wallet"]').val(0);
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


$('.cash-wallet-placeorder-btn').click( function(e){
	e.preventDefault();
	$('.loading-gif').show();

	var GrandTotal = $('#hidden_grand_total').val();
	
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
	
	var GrandTotal = $('#hidden_grand_total').val();
	
	$('input[name="topup_wallet"]').val(1);
	if(confirm('{{ isset($data["lang"]["lang"]["confirm_payment_using_topup_wallet_balance_of"]) ? $data["lang"]["lang"]["confirm_payment_using_topup_wallet_balance_of"] :"Confirm payment for using topup wallet balance of" }} '+GrandTotal+'?') == true){
		$('#placeorder-form').submit();
	}else{
		$('.loading-gif').hide();
	}
});

$('.apply-discount').click( function(e){
	e.preventDefault();
	
	var discount_code = $('.discount-code').val();
	var GrandTotal = $('#hidden_grand_total').val();
	var minSpend = parseFloat('{{ $minSpend }}');
	

	if(discount_code){
		// For now, we'll validate the minSpend on the server side
		// The AJAX call will return an error if minSpend is not met
		// This prevents having to duplicate the voucher logic in JavaScript

		$('.loading-gif').show();
		var fd = new FormData();
		fd.append('discount_code', discount_code);
		fd.append('checkout_apply', '1');
		fd.append('cart_total', GrandTotal);
		
		var voucher_id = $(this).data('voucher-id');
		if(voucher_id){
			fd.append('voucher_id', voucher_id);
		}

		$.ajax({
		   	url: '{{ route("ApplyPromo") }}',
		   	type: 'post',
		   	data: fd,
		   	contentType: false,
		   	processData: false,
		   	success: function(response){
			   	$('.loading-gif').hide();
			   
			   	if(Array.isArray(response) && response[0] == 6){
			   		$('.error-message-promo').html('You must spend at least RM' + response[1] + ' to use this Promotion Code.');
			   		$('.error-message-promo').show();
			   		return false;
			   	}
			   
			   	if(response == 0){
					$('.error-message-promo').html('Invalid Promotion Code');
					$('.error-message-promo').show();
					return false;
			   	}else if(response == 1){
					$('.error-message-promo').html('Promotion Code out of limit');
					$('.error-message-promo').show();
					return false;
			   	}else if(response == 2){
					$('.error-message-promo').html('Promotion Code not in date range');
					$('.error-message-promo').show();
					return false;
			   	}else if(response == 3){
					$('.error-message-promo').html('Your shopping cart does not meet the requirements of the Promotion Code: '+discount_code+'.');
					$('.error-message-promo').show();
					return false;		       			
			   	}else if(response == 4){
					$('.error-message-promo').html('Promotion Code out of limit.');
					$('.error-message-promo').show();
					return false;		       			
			   	}else if(response == 5){
					$('.error-message-promo').html('Promotion Code out of limit.');
					$('.error-message-promo').show();
					return false;		       			
			   	}else{
				   	location.reload();
			   	}
		   	},
		});
	}else{
		$('.error-message-promo').html('Please fill in Promotion Code');
		$('.error-message-promo').show();
		return false;
	}
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

	$('input[name="payment_gateway_setting_id"]').val('');

	if(ele.data('id') == 1){
		$("input[name='online']").val(1);
		$("input[name='cdm']").val(0);
		$("input[name='cash_wallet']").val(0);
		$("input[name='topup_wallet']").val(0);
	}else if(ele.data('id') == '5' || ele.data('id') == '6' || ele.data('id') == '7' || ele.data('id') == '8'){
		$("input[name='online']").val(1);
		$("input[name='cdm']").val(0);
		$("input[name='cash_wallet']").val(0);
		$("input[name='topup_wallet']").val(0);

		$('input[name="payment_gateway_setting_id"]').val(ele.attr('data-payment-gateway-setting-id'));
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
	
	validateVoucherAfterCartChange();
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
						
						validateVoucherAfterCartChange();
						
						location.reload();
					}
				});		   	},
		});			
	}
	
});

$('.claim-voucher').click( function(e){
	e.preventDefault();
	var ele = $(this);
	var voucher_id = ele.data('voucher-id');
	$('.discount-code').val(ele.data('id'));
	
	$('.apply-discount').data('voucher-id', voucher_id);
	$('.apply-discount').click();
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
		 
		   // alert(response);
		   if(response == 'not enough stock'){
			   toastr.error('{{ isset($data["lang"]["lang"]["balance_not_enough"]) ? $data["lang"]["lang"]["balance_not_enough"] :"Insufficient product balance" }}');
			   location.reload();
		   }else if(response == 'Only one birthday product is allowed'){
				toastr.error('{{ isset($data["lang"]["lang"]["one_birthday_only"]) ? $data["lang"]["lang"]["one_birthday_only"] :"Insufficient product balance" }}');
				location.reload();
		   }else if(response == 'exceed flash sale purchase limit'){
		   		toastr.error('{{ isset($data["lang"]["lang"]["exceed_flash_sale_purchase_limit"]) ? $data["lang"]["lang"]["exceed_flash_sale_purchase_limit"] :"Exceed Flash Sale Purchase Limit" }}');
		   		location.reload();
		   }else{
			   ele.closest('.cart-detail').find('.product_special_price').html('RM '+parseFloat(parseFloat(response) / parseFloat(qty)).toLocaleString());
			   ele.closest('.cart-detail').find('.product_unit_price').html('<del>RM ' + parseFloat(parseFloat(response) / parseFloat(qty)).toFixed(2) + '</del>');
			   ele.closest('.cart-detail').find('.product-total-price').html('RM '+parseFloat(response).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
			   calc();

			   $('.loading-gif').hide();
		   }
	   },
	});
	
}

function calc(skipVoucherValidation = false)
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
	// alert(store_checked);
	$.ajax({
	   url: '{{ route("getCartAmount") }}',
	   type: 'post',
	   data: fd,
	   contentType: false,
	   processData: false,
	   success: function(response){
			$('.sub-total-display').html(parseFloat(response[0]).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
			$('.sub-total-value').val(response[0]);
			$('.total-weight-display').html(parseFloat(response[1]).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
			$('.total-weight').val(response[1]);
			$('.discount_amount').html('RM '+parseFloat(response[2]).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
			$('.hidden_discount').val(response[2]);
			$('.shipping_amount').html('RM '+parseFloat(response[3]).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
			$('.hidden_shipping_amount').val(response[3]);
			$('.grand-total').html('RM '+parseFloat(response[4]).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
			$('.hidden_grand_total').val(response[4]);
			
			$('#debug-total').text(response[4]);

			if (!skipVoucherValidation) {
				validateVoucherAfterCartChange();
			}
	   },
	});
}

function validateVoucherAfterCartChange() {
	var total = parseFloat($('.hidden_grand_total').val()) || 0;
	var minSpend = parseFloat('{{ $minSpend ?? 0 }}');
	var appliedVoucher = $('input[name="discount_code"]').val();
	
	if (appliedVoucher && total < minSpend) {
		$('.error-message-promo').html('Your cart no longer meets the minimum spend of RM' + minSpend + '. Voucher removed.');
		$('.error-message-promo').show();
		$('input[name="discount_code"]').val('');
		$('.success-message-promo').html('');
		
		calc(true);
		
		setTimeout(function() {
			$('.error-message-promo').html('');
			$('.error-message-promo').hide();
		}, 5000);
	}
}

$('.store-in-stock').click(function(e){
	calc();
});

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
		
		validateVoucherAfterCartChange();
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
		
		validateVoucherAfterCartChange();
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
	
	setTimeout(function() {
		validateVoucherAfterCartChange();
	}, 1000);
});

$('.share-cart-link').click(function(e){

	e.preventDefault();

	$('.loading-gif').show();

	var ele = $(this);

	$.ajax({
	   	url: '{{ route("add_share_cart_link") }}',
	   	type: 'get',
	   	success: function(response){
			$('.loading-gif').hide();

			if(response['message'] != "ok"){
				toastr.error(response['message']);
			}else{
				console.log(response['unique_id'])
				$('#cart_link_value').val(response['unique_id']);
				// document.getElementById("cart_link_value").value = response['unique_id'];
				var copyText = document.getElementById("cart_link_value");
				    copyText.select();
				    copyText.setSelectionRange(0, 99999)
				    document.execCommand("copy");

				toastr.success('Link Copied');
			}
	   	}
	});

});
</script>

@if(!empty($cart_link->id) && $cart_link_remaining_qty <= 0)
<script type="text/javascript">
	$('#cart-link-modal').modal();
	$('#cart-link-modal').addClass('show');
</script>
@else
@if(empty($default_shipping_address->id))
<script type="text/javascript">
	$('#staticBackdrop').modal();
	$('#staticBackdrop').addClass('show');
</script>
@endif
@endif
@endsection