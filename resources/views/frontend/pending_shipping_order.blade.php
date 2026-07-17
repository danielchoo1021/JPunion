@extends('layouts.app')
@section('css')
@endsection
@section('content')
<div class="profile-own-bg" style="background-color: #dbc7c1;">
	<div class="personal-header-info">
			<div class="container">
				<div class="row">
					<div class="col-4" align="left">
						<a href="{{ route('profile') }}">
							<p style="color: white;"><i class="fa fa-chevron-left"></i> Back</p>
						</a>
					</div>
					<div class="col-4" align="left">
						<p align="center" class="header-title">My Orders</p>
					</div>
					<div class="col-4" align="right">
						<a href="{{ route('my_setting') }}" class="setting-btn">
							<i class="fa fa-cog" style="font-size: 20px;"></i>
						</a>
					</div>
				</div>
			</div>

		<div class="container">
			<div class="form-group header-group">
				<div class="row">
					<div class="col-2">
						<a href="{{ route('my_setting') }}">
							@if(!empty(Auth::user()->profile_logo))
								<!-- <img src="{{ asset(Auth::user()->profile_logo) }}" width="50" class="profile-logo"> -->
								<div style="background-image: url({{ asset(Auth::user()->profile_logo) }}); width: 50px; height: 50px; border-radius: 100%; background-size: 100%; background-position: center; background-repeat: no-repeat;"></div>
							@else
								<img src="{{ asset('images/images.png') }}" width="50" class="profile-logo">
							@endif							
						</a>
					</div>
					<div class="col-10">
						<a href="{{ route('profile') }}">
							&nbsp;
							<b class="profile-name">{{ Auth::user()->f_name }} {{ Auth::user()->l_name }}</b>
							<br>
							&nbsp;
							<small class="profile-code">{{ isset($data['lang']['lang']['agent_code']) ? $data['lang']['lang']['agent_code'] :'代码'}}: {{ $own_display_code }}</small>
							
							<br>
							&nbsp;
							<small class="profile-level">
								@if(Auth::guard('web')->check())
									Level: Member {!! (Auth::user()->lvl == 1) ? '<b>(KVIP)</b>' : '' !!}
								@else
									Level: Klady

									@if(Auth::user()->lvl == 2)
										(K1)
									@elseif(Auth::user()->lvl == 3)
										(K2)
									@elseif(Auth::user()->lvl == 4)
										(K3)
									@elseif(Auth::user()->lvl == 5)
										(K4)
									@elseif(Auth::user()->lvl == 6)
										(K5)
									@endif
								@endif
							</small>
							<br>
							<!-- &nbsp;
							<small class="profile-level">
								{{ isset($data['lang']['lang']['upline_name']) ? $data['lang']['lang']['upline_name'] :'推荐人名字'}}: {{ (!empty($upline_name)) ? $upline_name : '' }}
							</small>
							<br>
							&nbsp;
							<small class="profile-level">
								{{ isset($data['lang']['lang']['upline_code']) ? $data['lang']['lang']['upline_code'] :'推荐人代码'}}: {{ (!empty($upline_code)) ? $upline_code : '-' }}
							</small>
							<br> -->
							&nbsp;
							<small class="profile-level">
								Join Date: 
								@if(!empty($aff_joined_date[Auth::user()->code]))
								{{ $aff_joined_date[Auth::user()->code]->created_at }}
								@else
								{{ Auth::user()->created_at }}
								@endif
							</small>
							
						</a>
					</div>
					<!-- <div class="col-xs-4" align="right">
						<a href="#">
							<i class="fa fa-pencil"></i> Edit Profile
						</a>

					</div> -->
				</div>
			</div>
			
			@if(Auth::guard('web')->check())
				<!-- <div class="form-group container-box sl-personal-header">
					<div class="row">
						<div class="col-6" align="center">
							<a href="{{ route('myqrcode') }}">
								<img src="{{ asset('images/qrcode.png') }}" width="30">
								<br>
								<span class="profile-word">我的二维码</span>
							</a>
						</div>

						<div class="col-4" align="center">
							<a href="{{ route('MyAffiliate', Auth::user()->code) }}">
								<img src="{{ asset('images/profile/585e4d1ccb11b227491c339b.png') }}" width="30">
								<br>
								<span class="profile-word">My Team</span>
							</a>
						</div>

						<div class="col-12" align="center">
							<a href="{{ route('wallet') }}">
								<img src="{{ asset('images/profile/c3286d4d32fa90ebcf09b488654612b9-wallet-icon-by-vexels.png') }}" width="30">
								<br>
								<span class="profile-word">我的钱包</span>
							</a>
						</div>
					</div>
				</div> -->
			@else
				<div class="form-group container-box sl-personal-header">
					<div class="row">
						<div class="col-3" align="center">
							<a href="{{ route('myqrcode') }}">
								<img src="{{ asset('images/qrcode.png') }}" width="30">
								<br>
								<span class="profile-word">My QRcode</span>
							</a>
						</div>

						<div class="col-3" align="center">
							<a href="{{ route('MyAffiliate', Auth::user()->code) }}">
								<img src="{{ asset('images/profile/585e4d1ccb11b227491c339b.png') }}" width="30">
								<br>
								<span class="profile-word">My Team</span>
							</a>
						</div>

						<div class="col-3" align="center">
							<a href="{{ route('MyCustomer', Auth::user()->code) }}">
								<img src="{{ asset('images/profile/585e4d1ccb11b227491c339b.png') }}" width="30">
								<br>
								<span class="profile-word">My Customer</span>
							</a>
						</div>

						<div class="col-3" align="center">
							<a href="{{ route('wallet') }}">
								<img src="{{ asset('images/profile/c3286d4d32fa90ebcf09b488654612b9-wallet-icon-by-vexels.png') }}" width="30">
								<br>
								<span class="profile-word">My Wallet</span>
							</a>
						</div>
					</div>
				</div>
			@endif
		</div>
	</div>
</div>

<div class="profile-content">
	<div class="container">

		<div class="myOrder-list">
				<div class="form-group container-box">
					<div class="row">
						<div class="col-sm-6">
							<button class="btn btn-danger btn-pay-all" disabled>
								Pay shipping fee
							</button>
						</div>
						<div class="col-sm-6" align="left" style="margin: auto;">
							<!-- <b>Current Recruiting Progress For Free Product: {{ $purchase_qty }} / 20</b> -->
							<label for="progress">Current Recruiting Progress For Free Product:</label>
							<div class="progress">
							  <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="{{ $purchase_qty }}"
							  aria-valuemin="0" aria-valuemax="20" style="width: {{ ($purchase_qty / 20) * 100 }}%">
							    ({{ $purchase_qty }} / 20)
							  </div>
							</div>
						</div>
					</div>
					<!-- <button class="btn btn-warning btn-set-all" disabled>
						Self pickup as free shipping fee
					</button> -->
				</div>
				@if (!$transactions->isEmpty())
				@foreach($transactions as $transaction)
				<div class="form-group container-box">
					<div class="row">
						<div class="col-6 order-no-details">
							<input type="checkbox" class="multiple-select" value="{{ md5($transaction->transaction_no) }}" style="display: block;">
							<br>
							<b>Order No: #{{ $transaction->transaction_no }}</b><br>
							   Order Date: {{ $transaction->created_at }}
							@if(!empty($transaction->is_free))
								<br>
								<span class="badge badge-pill badge-info" style="font-size: 20px;">Free Product</span> 
							@endif
						</div>
						<div class="col-6" align="right">
							@if($transaction->status == 99)
								
								
								@if(!empty($transaction->address_name))
									<a href="#" class="btn btn-info btn-sm select-shipping-address-btn" data-id="{{ md5($transaction->id) }}" data-toggle="modal" data-target="#change-address" style="display: none;">
										Select Shipping Address
									</a>

									<a href="#" class="btn btn-warning btn-sm pay-now-button" data-id="{{ $transaction->transaction_no }}" data-transaction="{{ $transaction->transaction_no }}" data-ship="{{ $transaction->shipping_fee }}" data-toggle="modal" data-target="#myModal" style="display: none;">
										Pay Shipping Fee Now
									</a>
								@else
									<a href="#" class="btn btn-warning btn-sm select-shipping-address-btn" data-id="{{ md5($transaction->id) }}" data-toggle="modal" data-target="#change-address" style="display: none;">
										Pay Shipping Fee Now
									</a>
								@endif
							@else
								<a href="{{ route('invoice', $transaction->transaction_no) }}" class="btn btn-primary btn-sm" target="_blank">
									Download Order Details
								</a>
							@endif
							<a href="{{ route('order_detail', $transaction->transaction_no) }}" class="btn btn-primary btn-sm">
								Manage
							</a>
						</div>
					</div>
					<hr>
					@foreach($details[$transaction->id] as $detail)
					@php
					$image = (!empty($detail->product_image)) ? $detail->product_image : 'images/no-image-available-icon-6.jpg';
					@endphp
					<div class="form-group">
						<div class="row">
							<div class="col-sm-1">
								<div class="from-group">
									<img src="{{ asset($image) }}" style="width: 70px;">
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group product-details">
									<div class="form-group">
										<b>{{ $detail->product_name }}</b>
										<br>
										{!! ($detail->sub_category != '') ? "Option: ".$detail->sub_category."<br>" : '' !!}
										{!! ($detail->second_sub_category != '') ? "Second Option: ".$detail->second_sub_category."<br>" : '' !!}
									</div>
									@if($transaction->status == 99)
										<span class="badge badge-pill bg-warning">Unpaid</span>
									@elseif($transaction->status == 98)
										<span class="badge badge-pill badge-info">Waiting Verification</span>
									@elseif($transaction->status == 97)
										<span class="badge badge-pill badge-info">Waiting Verification</span>
									@elseif($transaction->status == 1)
										@if(!empty($transaction->bank_id))
				                            <span class="badge badge-pill bg-success">Paid</span>
				                        @else
				                            <span class="badge badge-pill bg-success">Paid</span>
				                        @endif
									@else
										<span class="badge badge-pill bg-danger">Cancelled</span>
									@endif
								</div>
							</div>
							<div class="col-sm-5" align="right">
								Qty: x{{ $detail->quantity }}
								<br>
								<br>
								RM {{ number_format($detail->unit_price, 2) }}
								<br>
								<br>
								Subtotal: RM {{ number_format($detail->unit_price * $detail->quantity, 2) }}
							</div>	
						</div>
					</div>
					<hr>

					@endforeach
					<div class="row">
						<div class="col-6" align="left">
							{{ count($details[$transaction->id]) }} Products
						</div>
						<div class="col-6" align="right">
							<div class="form-group">
								Shipping Fee: RM {{ number_format($transaction->shipping_fee, 2) }}
							</div>
							<div class="form-group">
								Member Discount: RM {{ number_format($transaction->member_discount, 2) }}
							</div>
							<div class="form-group">
								Voucher Discount: RM {{ number_format($transaction->discount, 2) }}
							</div>
							<div class="form-group">
								Grand total: RM {{ number_format($transaction->grand_total, 2) }}
							</div>
						</div>
					</div>

					@if($transaction->status == '98' && !empty($transaction->tracking_no) && !empty($transaction->order_number) && isset($ship_details[$transaction->id]))
					<hr>
					<div class="form-group">
						<i class="fa fa-truck" aria-hidden="true" style="font-size: 17px;"></i> &nbsp;&nbsp;&nbsp; [{{ $transaction->courier }}] {{ $ship_details[$transaction->id] }}
					</div>
					@endif
				</div>
				@endforeach
				@else
				<div class="form-group container-box">
					<div class="form-group" align="center">
						No orders yet. <br><br>
						<a href="{{ route('home') }}" class="continue-shopping-btn btn btn-primary"> Continue Shopping</a>
					</div>
				</div>
				@endif
			</div>
	</div>
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  	<div class="modal-dialog modal-lg" role="document">
    	<div class="modal-content" style="background-color: #fff;">
      		<div class="modal-header">
        		<h4 class="modal-title" id="myModalLabel" style="text-align: left;">
        			Shipping Fee: RM {{ $total_shipping_fee }}
        			<input type="hidden" name="shipping_fee_to_pay_amount" class="shipping_fee_to_pay_amount">
        		</h4>
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      		</div>
      		<div class="modal-body">
      			<form method="POST" action="{{ route('pay_shipping_fee') }}" id="submit-form" enctype="multipart/form-data">
      				@csrf
      				<input type="hidden" name="traind" id="traind">
      				<span>Transaction No: <b class="important-text">{{ request('t') }}</b></span>
      				<hr>
      				<div class="widget-box transparent" id="recent-box">
						<div class="widget-header">
							<h4 class="widget-title lighter smaller">
								<i class="fa fa-credit-card-alt" aria-hidden="true"></i> Payment Method
							</h4>

							<div class="widget-toolbar no-border">
								<ul class="nav nav-tabs" id="recent-tab">
									<!-- <li class="parent_payment_method active">
										<a data-toggle="tab" class="payment_method f-15" data-id="1" href="#online-tab">Online Banking</a>
									</li> -->

									<li class="parent_payment_method active">
										<a data-toggle="tab" class="payment_method f-15" data-id="2" href="#cdm-tab">Bank Transfer</a>
									</li>

									<!-- <li class="parent_payment_method">
										<a data-toggle="tab" class="payment_method f-15" data-id="3" href="#cash-wallet-tab">Commission Wallet</a>
									</li> -->
								</ul>
							</div>
						</div>

						<div class="widget-body">
							<div class="widget-main padding-4">
								<div class="tab-content padding-8">
									<div id="online-tab" class="tab-pane">
										<div class="form-group">
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
												<!-- <div class="col-4" align="center">
													<label>
														<input type="radio" name="bank_id" value="6">
														<img src="{{ asset('images/banks/bsn.jpg') }}">
													</label>
												</div> -->
											</div>
										</div>

										<div class="form-group">
											<b id="error-message-banks" class="error-message important-text"></b>
										</div>

										<!-- <input type="hidden" name="online" value="1"> -->
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
									</div>
									
									<div id="cash-wallet-tab" class="tab-pane" align="center">
										<div class="form-group">
											<input type="hidden" name="wallet_bank_id" value="10000743">
											<div class="row">
												<div class="col-12" align="center"> 
													RM 
													<span class="wallet-balance-amount">
														{{ number_format($GetCashWalletBalance, 2) }}
													</span>
													<br>
													<span class="wallet-desc profile-word">Remaining Wallet Balance</span>
												</div>
											</div>
										</div>
										<div class="form-group bank_details">

										</div>

										<input type="hidden" name="cash_wallet">
	<!-- 													<div class="form-group">
											<input type="file" name="bank_slip" class="form-control" accept="image/*">
										</div> -->

										<div class="form-group">
											<b id="error-message-wallet-banks" class="important-text"></b>
										</div>
									</div>

								</div>
							</div><!-- /.widget-main -->
						</div><!-- /.widget-body -->
					</div><!-- /.widget-box -->
      			</form>
      		</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        		<button type="button" class="btn btn-primary pay-button">Pay Now</button>
      		</div>
    	</div>
  	</div>
</div>

<div class="modal fade" id="change-address" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="background-color: #fff;">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">My Shipping Address</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="POST" action="{{ route('update_pending_address') }}">
      <input type="hidden" name="traind" id="traind2">
      <div class="modal-body" align="left">
      	<div class="form-group" align="right">
      		<a href="#" data-toggle="modal" data-target="#add-new-address" class="btn btn-primary btn-sm">
      			<i class="fa fa-plus"></i> Add New Address
      		</a>
      		<a href="{{ route('AddressBook.AddressBook.index') }}" class="btn btn-primary btn-sm">
      			Address Manage
      		</a>
      	</div>
      	<hr>
      	@csrf
	        @foreach($ownShippingAddress as $ad)
	        	<div class="form-group">
	        		<div class="row">
	        			<div class="col-1">
	        				<input type="radio" name="default" value="{{ $ad->id }}" style="display: block;">
	        				<input type="hidden" name="aid[]" value="{{ $ad->id }}">
	        			</div>
	        			<div class="col-11">
			        		<i class="fa fa-user" style="width: 20px;"></i> {{ $ad->f_name }} {{ $ad->l_name }}<br> 
			        		<i class="fa fa-phone" style="width: 20px;"></i>
			        			+{{ $ad->country_code }} 
			        			{{ ($ad->phone[0] == 0) ? substr($ad->phone, 1) : $ad->phone }} 
			        			<br>
			        		<i class="fa fa-location-arrow" style="width: 20px;"></i> {{ $ad->address }}, {{ $ad->post_code }} {{ $ad->city }}, {{ $ad->state_name }}
	        			</div>
	        		</div>
	        	</div>
	        	<hr>
	        @endforeach
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button class="btn btn-primary">Save Changes</button>
      </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="add-new-address" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content" style="box-shadow: 0 2px 10px #bdbdbd; background-color: #fff;">
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
				<select class="form-control" name="state" style="height: auto;">
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
      		
            <div class="form-group">
                <div id="action-return-message"></div>
            </div>
      		
      		<div class="form-group">
	      		<button class="btn btn-primary btn-block btn-sm default_btn add-new-address-btn">
	        		Submit
	        	</button>
      			<br>
      			<button type="button" class="btn btn-secondary btn-block btn-sm" data-dismiss="modal">Cancel</button>
      		</div>
  		</form>
      </div>
     
    </div>
  </div>
</div>
@endsection

@section('js')
<script type="text/javascript">
	$('.pay-now-button').click( function(){
		$('#traind').val($(this).data('id'));
		var shipping_fee = $(this).data('ship');
		$('.shipping-fee-amount').html(parseFloat(shipping_fee).toFixed(2));
		$('.shipping_fee_to_pay_amount').val(shipping_fee);
	});

	$('.select-shipping-address-btn').click(function(e){
		$('#traind2').val($(this).data('id'));
	});

	$('.pay-button').click( function(e){
		e.preventDefault();
		var payment_method = $('.parent_payment_method.active .payment_method').data('id');
		var bank_slip = $('input[name="bank_slip"]').val();
		var balance = "{{ $GetCashWalletBalance }}";
		var shipping_fee = $('.shipping_fee_to_pay_amount').val();

		if(payment_method == 1){
			if(!$("input[name='bank_id']:checked").val()){
		    	$('#error-message-banks').html('Please select bank to continue payment.');
		    	return false;
		    }			
		}else if(payment_method == 2){
			if(!bank_slip){
				$('#error-message-cdm-banks').html('Please upload bank slip to continue payment.');
				$('.loading-gif').hide();
				return false;
			}
		}else{
			if(balance < shipping_fee){
				$('#error-message-wallet-banks').html('Insufficient balance');
				$('.loading-gif').hide();
				return false;
			}
		}

		$('#submit-form').submit();
	});

	$('.payment_method').click( function(e){
		e.preventDefault();

		var ele = $(this);
		
		
		$('.parent_payment_method').removeClass('active');
		ele.parent().addClass('active');

		if(ele.data('id') == 1){
			$("input[name='online']").val(1);
			$("input[name='cdm']").val(0);
			$("input[name='cash_wallet']").val(0);
		}else{
			if(ele.data('id') == 3){
				$("input[name='cash_wallet']").val(1);
				$("input[name='cdm']").val(0);
				$("input[name='online']").val(0);
			}else{
				$("input[name='cdm']").val(1);
				$("input[name='cash_wallet']").val(0);
				$("input[name='online']").val(0);
			}
		}
	});

	$('.multiple-select').click(function(e){
		var total = $('.myOrder-list').find('.multiple-select:checked').length;

		if(total > 0){
			$('.btn-pay-all').prop('disabled', false);
			$('.btn-set-all').prop('disabled', false);
		}else{
			$('.btn-pay-all').prop('disabled', true);
			$('.btn-set-all').prop('disabled', true);
		}
	});

	$('.btn-pay-all').click(function(){
		var asd = [];
		$( ".multiple-select:checked" ).each(function( index ) {
		  	asd.push($(this).val());
		});

		$('#traind2').val(asd);
		$('#change-address').modal();
	});

	$('.btn-set-all').click(function(){
		var asd = [];
		$( ".multiple-select:checked" ).each(function( index ) {
		  	asd.push($(this).val());
		});

		var fd = new FormData();
			fd.append('_token', '{{ csrf_token() }}');
			fd.append('transaction_no', asd);

		$.ajax({
	       url: '{{ route("setPickup") }}',
	       type: 'post',
	       data: fd,
	       contentType: false,
	       processData: false,
	       success: function(response){
	       		toastr.success('Success');
	       		location.reload();
	       },
	    });
	});
</script>
@if(!empty(request('t')))
<script type="text/javascript">
	var t = "{{ request('t') }}";
	$( ".pay-now-button" ).filter(function( index ) {
    return $(this).data('transaction') == t;
  	}).click();
		// alert(t);
  	if (t.indexOf(',') > -1) {
	    var fd = new FormData();
			fd.append('_token', '{{ csrf_token() }}');
			fd.append('transaction_no', t);
		$.ajax({
	       url: '{{ route("getShippingFee") }}',
	       type: 'post',
	       data: fd,
	       contentType: false,
	       processData: false,
	       success: function(response){
	       		$('#myModal').modal();
						$('.shipping-fee-amount').html(response);
						$('.shipping_fee_to_pay_amount').val(response);
						$('#traind').val(t);
				
	       },
	    });
	}
</script>
@endif
@endsection