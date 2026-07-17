@extends('layouts.app')
@section('css')

@endsection
@section('content')
<div class="profile-own-bg">
	<div class="personal-header-info">
			<div class="container">
				<div class="row">
					<div class="col-4" align="left">
						<a href="{{ route('profile') }}">
							<p style="color: white;"><i class="fa fa-chevron-left"></i> {{ isset($data['lang']['lang']['back_to_prev_page']) ? $data['lang']['lang']['back_to_prev_page'] :'回到上一页'}}</p>
						</a>
					</div>
					<div class="col-4" align="left">
						<p align="center" class="header-title">{{ isset($data['lang']['lang']['my_orders']) ? $data['lang']['lang']['my_orders'] :'我的订单'}}</p>
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
						<a href="{{ route('profile') }}">
							@if(!empty(Auth::user()->profile_logo))
								<!-- <img src="{{ asset(Auth::user()->profile_logo) }}" width="50" class="profile-logo"> -->
								<div style="background-image: url({{ asset(Auth::user()->profile_logo) }}); width: 50px; height: 50px; border-radius: 100%; background-size: 100%; background-position: center; background-repeat: no-repeat;"></div>
							@else
								<img src="{{ asset('images/images.png') }}" width="50" class="profile-logo">
							@endif							
						</a>
					</div>
					<div class="col-5">
						<a href="{{ route('profile') }}">
							&nbsp;
							<b class="profile-name">{{ Auth::user()->f_name }} {{ Auth::user()->l_name }}</b>
							<br>
							&nbsp;
							<small class="profile-code">{{ isset($data['lang']['lang']['agent_code']) ? $data['lang']['lang']['agent_code'] :'代码'}}: {{ $own_display_code }}</small>
							
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
					<div class="col-5">
							<br>
							&nbsp;
							<small class="profile-level">
								File Member: {!! !empty(Auth::user()->file_member) ? 'Active' : 'Inactive' !!}
							</small>
							<br>
							&nbsp;
							<small class="profile-level">
								Fouding Level: 
								@if(Auth::user()->lvl == 1)
									Founding Member (初级)
								@elseif(Auth::user()->lvl == 2)
									Founding Member (高级)
								@else
									- 
								@endif
							</small>
							<br>
							&nbsp;
							<small class="profile-level">
								Agent Level: {{ !empty($lvl) ? $lvl : '' }}
							</small>
							<br>
							&nbsp;
							<small class="profile-level">
								Partner Level: {{ !empty($partner_lvl) ? $partner_lvl : '' }}
							</small>
							<br>
							&nbsp;
							<small class="profile-level">
								City Agent: {{ !empty($city_agent) ? $city_agent : '' }}
							</small>
							<br>
							&nbsp;
							<small class="profile-level">
								State Agent: {{ !empty($state_agent) ? $state_agent : '' }}
							</small>
					</div>
					<!-- <div class="col-3">
						<a href="{{ route('profile') }}">
							<br>
							&nbsp;
							<small class="profile-level">
								加入的日期与时间: {{ Auth::user()->created_at }}
							</small>
							<br>
							@if(!$upgrade_record->isEmpty())
								@foreach($upgrade_record as $record)
									@if($record->level == 1)
										@php
											$firststUpgrade = $record->created_at;
										@endphp
									@elseif($record->level == 2)
										@php
											$secondUpgrade = $record->created_at;
										@endphp
									@endif
								@endforeach
									&nbsp;
									<small class="profile-level">
										升级代理的日期与时间: {{ (!empty($firststUpgrade)) ? $firststUpgrade : '-' }}
									</small>
									<br>
									&nbsp;
									<small class="profile-level">
										升级总代理的日期与时间: {{ (!empty($secondUpgrade)) ? $secondUpgrade : '-' }}
									</small>
							@endif
						</a>
					</div> -->
					<!-- <div class="col-xs-4" align="right">
						<a href="#">
							<i class="fa fa-pencil"></i> Edit Profile
						</a>

					</div> -->
				</div>
			</div>
			
			@if(Auth::guard('web')->check())
				@if(Auth::guard('web')->user()->lvl == 1)
					<div class="form-group container-box sl-personal-header">
						<div class="row">
							<div class="col-4" align="center">
								<a href="{{ route('myqrcode') }}">
									<img src="{{ asset('images/qrcode.png') }}" width="30">
									<br>
									<span class="profile-word">{{ isset($data['lang']['lang']['my_qrcode']) ? $data['lang']['lang']['my_qrcode'] :'我的二维码'}}</span>
								</a>
							</div>

							<div class="col-4" align="center">
								<a href="{{ route('MyAffiliate', Auth::user()->code) }}">
									<img src="{{ asset('images/profile/585e4d1ccb11b227491c339b.png') }}" width="30">
									<br>
									<span class="profile-word">{{ isset($data['lang']['lang']['my_team']) ? $data['lang']['lang']['my_team'] :'我的团队'}}</span>
								</a>
							</div>

							<div class="col-4" align="center">
								<a href="{{ route('wallet') }}">
									<img src="{{ asset('images/profile/c3286d4d32fa90ebcf09b488654612b9-wallet-icon-by-vexels.png') }}" width="30">
									<br>
									<span class="profile-word">{{ isset($data['lang']['lang']['my_wallet']) ? $data['lang']['lang']['my_wallet'] :'我的钱包'}}</span>
								</a>
							</div>
						</div>
					</div>
				@endif
			@else
				<div class="form-group container-box sl-personal-header">
						<div class="row">
							<div class="col-4" align="center">
								<a href="{{ route('myqrcode') }}">
									<img src="{{ asset('images/qrcode.png') }}" width="30">
									<br>
									<span class="profile-word">{{ isset($data['lang']['lang']['my_qrcode']) ? $data['lang']['lang']['my_qrcode'] :'我的二维码'}}</span>
								</a>
							</div>

							<div class="col-4" align="center">
								<a href="{{ route('MyAffiliate', Auth::user()->code) }}">
									<img src="{{ asset('images/profile/585e4d1ccb11b227491c339b.png') }}" width="30">
									<br>
									<span class="profile-word">{{ isset($data['lang']['lang']['my_team']) ? $data['lang']['lang']['my_team'] :'我的团队'}}</span>
								</a>
							</div>

							<div class="col-4" align="center">
								<a href="{{ route('wallet') }}">
									<img src="{{ asset('images/profile/c3286d4d32fa90ebcf09b488654612b9-wallet-icon-by-vexels.png') }}" width="30">
									<br>
									<span class="profile-word">{{ isset($data['lang']['lang']['my_wallet']) ? $data['lang']['lang']['my_wallet'] :'我的钱包'}}</span>
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
		<div class="form-group container-box">
			<div class="row justify-content-center">
				<div class="col-6" align="left">
					<b>{{ isset($data['lang']['lang']['my_orders']) ? $data['lang']['lang']['my_orders'] :'我的订单'}}</b>
				</div>
				<div class="col-6" align="right">
					<a href="{{ route('pending_order') }}">
						<small>{{ isset($data['lang']['lang']['view_all_order']) ? $data['lang']['lang']['view_all_order'] :'查看所有订单'}} <i class="fa fa-angle-right" aria-hidden="true"></i></small>
					</a>
				</div>
				</div>
				<br>
				<div class="row">

				<div class="col" align="center">
					<a href="{{ route('checkout') }}" style="position: relative;">
						@if($data['totalCart'] > 0)
						<span class="badge badge-pill bg-danger" style="position: absolute; right: -10px; top: -10px;">
							{{ $data['totalCart'] }}
						</span>
						@endif
						<img src="{{ asset('images/cart.png') }}" width="30">
						<br>
						<span class="profile-word">{{ isset($data['lang']['lang']['to_pay']) ? $data['lang']['lang']['to_pay'] :'待付款'}}</span>
					</a>
				</div>

				<div class="col" align="center">
					<a href="{{ route('verifying_order') }}" style="position: relative;">
						@if($countVerifying > 0)
						<span class="badge badge-pill bg-danger" style="position: absolute; right: -10px; top: -10px;">
							{{ $countVerifying }}
						</span>
						@endif
						<img src="{{ asset('images/profile/review.png') }}" width="30">
						<br>
						<span class="profile-word">{{ isset($data['lang']['lang']['to_verify']) ? $data['lang']['lang']['to_verify'] :'待审核'}}</span>
					</a>
				</div>

				<div class="col" align="center">
					<a href="{{ route('pending_shipping') }}" style="position: relative;">
						@if($countToShip > 0)
						<span class="badge badge-pill bg-danger" style="position: absolute; right: -10px; top: -10px;">
							{{ $countToShip }}
						</span>
						@endif
						<img src="{{ asset('images/profile/shipment_pending_1017207.png') }}" width="30">
						<br>
						<span class="profile-word">{{ isset($data['lang']['lang']['to_be_delivered']) ? $data['lang']['lang']['to_be_delivered'] :'待出货'}}</span>
					</a>
				</div>

				<div class="col" align="center">
					<a href="{{ route('pending_receive') }}" style="position: relative;">
						@if($countToReceive > 0)
						<span class="badge badge-pill bg-danger" style="position: absolute; right: -10px; top: -10px;">
							{{ $countToReceive }}
						</span>
						@endif
						<img src="{{ asset('images/profile/Pending-Truck-Delivery-Commerce-Logistic-Transportation-512.png') }}" width="30">
						<br>
						<span class="profile-word">{{ isset($data['lang']['lang']['to_be_received']) ? $data['lang']['lang']['to_be_received'] :'待收货'}}</span>
					</a>
				</div>

				<div class="col" align="center">
					<a href="{{ route('awaiting_order') }}" style="position: relative;">
						@if($countAwaiting > 0)
						<span class="badge badge-pill bg-danger" style="position: absolute; right: -10px; top: -10px;">
							{{ $countAwaiting }}
						</span>
						@endif
						<img src="{{ asset('images/profile/parcel.png') }}" width="30">
						<br>
						<span class="profile-word">{{ isset($data['lang']['lang']['to_be_collected']) ? $data['lang']['lang']['to_be_collected'] :'待取货'}}</span>
					</a>
				</div>

				<div class="col" align="center">
					<a href="{{ route('completed_order') }}" style="position: relative;">
						@if($countCompleted > 0)
						<span class="badge badge-pill bg-danger" style="position: absolute; right: -10px; top: -10px;">
							{{ $countCompleted }}
						</span>
						@endif
						<img src="{{ asset('images/profile/Box_Package_Delivery_Shipping_Complete_Check_Done-512.png') }}" width="30">
						<br>
						<span class="profile-word">{{ isset($data['lang']['lang']['completed']) ? $data['lang']['lang']['completed'] :'已完成'}}</span>
					</a>
				</div>

				<div class="col" align="center">
					<a href="{{ route('cancelled_order') }}" style="position: relative;">
						@if($countCancelled > 0)
						<span class="badge badge-pill bg-danger" style="position: absolute; right: -10px; top: -10px;">
							{{ $countCancelled }}
						</span>
						@endif
						<img src="{{ asset('images/profile/online_shop_ecommerce_shopping-46-512.png') }}" width="30">
						<br>
						<span class="profile-word">{{ isset($data['lang']['lang']['cancelled']) ? $data['lang']['lang']['cancelled'] :'已取消'}}</span>
					</a>
				</div>
			</div>
		</div>

		<div class="myOrder-list">
				@if (!$transactions->isEmpty())
				@foreach($transactions as $transaction)
				
				<div class="form-group container-box">
					<div class="row">
						<div class="col-6 order-no-details">
							<b>{{ isset($data['lang']['lang']['order_no']) ? $data['lang']['lang']['order_no'] :'单号'}}: #{{ $transaction->transaction_no }}</b><br>
							{{ isset($data['lang']['lang']['order_dates']) ? $data['lang']['lang']['order_dates'] :'订单日期'}}: {{ $transaction->created_at }}
						</div>
						<div class="col-6" align="right">
								<a href="#" class="btn btn-success btn-sm pay-now-button set_button set_text" data-id="{{ $transaction->id }}" data-toggle="modal" data-target="#myModal">
									{{ isset($data['lang']['lang']['pay_now']) ? $data['lang']['lang']['pay_now'] :'现在付款'}}<i class="fa fa-money"></i>
								</a>
							
								<a href="{{ route('order_detail', $transaction->transaction_no) }}" class="btn btn-primary btn-sm pay-now-button set_button set_text">
									{{ isset($data['lang']['lang']['view']) ? $data['lang']['lang']['view'] :'查看'}}<i class="fa fa-eye"></i>
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
									</div>
									@if($transaction->status == 99)
										<span class="badge badge-pill bg-warning">{{ isset($data['lang']['lang']['unpaid']) ? $data['lang']['lang']['unpaid'] :'未付款'}}</span>
									@elseif($transaction->status == 98)
										<span class="badge badge-pill badge-info">{{ isset($data['lang']['lang']['awaiting_verify']) ? $data['lang']['lang']['awaiting_verify'] :'等待验证'}}</span>
									@elseif($transaction->status == 97)
										<span class="badge badge-pill badge-info">{{ isset($data['lang']['lang']['awaiting_verify']) ? $data['lang']['lang']['awaiting_verify'] :'等待验证'}}</span>
									@elseif($transaction->status == 1)
										@if(!empty($transaction->bank_id))
				                            <span class="badge badge-pill bg-success">{{ isset($data['lang']['lang']['paid']) ? $data['lang']['lang']['paid'] :'已付款'}}</span>
				                        @else
				                            <span class="badge badge-pill bg-success">{{ isset($data['lang']['lang']['paid']) ? $data['lang']['lang']['paid'] :'已付款'}}</span>
				                        @endif
									@else
										<span class="badge badge-pill bg-danger">{{ isset($data['lang']['lang']['cancelled']) ? $data['lang']['lang']['cancelled'] :'已取消'}}</span>
									@endif
								</div>
							</div>
							<div class="col-sm-5" align="right">
								{{ isset($data['lang']['lang']['quantity']) ? $data['lang']['lang']['quantity'] :'数量'}}: x{{ $detail->quantity }}
								<br>
								<br>
								RM {{ number_format($detail->unit_price, 2) }}
							</div>	
						</div>
					</div>
					<hr>
					@endforeach
					<div class="row">
						<div class="col-6" align="left">
							{{ count($details[$transaction->id]) }} {{ isset($data['lang']['lang']['products']) ? $data['lang']['lang']['products'] :'产品'}}	
						</div>
						<div class="col-6" align="right">
							{{ isset($data['lang']['lang']['sub_total']) ? $data['lang']['lang']['sub_total'] :'合计'}}: RM {{ number_format($transaction->grand_total, 2) }}
						</div>
					</div>

					@if($transaction->cod_address != '1' && $transaction->status == '1' && !empty($transaction->tracking_no) && !empty($transaction->order_number) && isset($ship_details[$transaction->id]))
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
						{{ isset($data['lang']['lang']['no_order_yet']) ? $data['lang']['lang']['no_order_yet'] :'尚无订单'}}. <br><br>
						<a href="{{ route('listing') }}" class="continue-shopping-btn btn btn-primary set_button set_text"> {{ isset($data['lang']['lang']['continue_shopping']) ? $data['lang']['lang']['continue_shopping'] :'继续购物'}}</a>
					</div>
				</div>
				@endif
			</div>
	</div>
</div>

<!-- <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  	<div class="modal-dialog" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<h4 class="modal-title" id="myModalLabel" align="left">选择银行</h4>
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      		</div>
      		<div class="modal-body">
        		<div class="form-group">
        			<input type="hidden" id="traind">
					<div class="row"> -->
						<!-- <div class="col-4" align="center">
							<label>
								<input type="radio" name="bank_id" value="10001842">
								<img src="{{ asset('images/banks/maybank.jpg') }}">
							</label>
						</div> -->
						<!-- <div class="col-4" align="center">
							<label>
								<input type="radio" name="bank_id" value="10001841">
								<img src="{{ asset('images/banks/cimb.jpg') }}">
							</label>
						</div>
						<div class="col-4" align="center">
							<label>
								<input type="radio" name="bank_id" value="10001802">
								<img src="{{ asset('images/banks/rhb.jpg') }}">
							</label>
						</div>
						<div class="col-4" align="center">
							<label>
								<input type="radio" name="bank_id" value="10001803">
								<img src="{{ asset('images/banks/hongleong.jpg') }}">
							</label>
						</div>
					</div>
				</div>
				<br>
				<div class="form-group">
					<div class="row">
						<div class="col-4" align="center">
							<label>
								<input type="radio" name="bank_id" value="10001843">
								<img src="{{ asset('images/banks/pbe.jpg') }}">
							</label>
						</div>
					</div>
				</div>
				<div class="form-group">
					<b id="error-message-banks" class="important-text"></b>
				</div>
      		</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
        		<button type="button" class="btn btn-primary pay-button">现在付款</button>
      		</div>
    	</div>
  	</div>
</div> -->

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  	<div class="modal-dialog" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<h4 class="modal-title" id="myModalLabel" align="left">{{ isset($data['lang']['lang']['select_bank']) ? $data['lang']['lang']['select_bank'] :'选择银行'}}</h4>
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      		</div>
      		<div class="modal-body">
      			<form method="POST" action="{{ route('repayment') }}" id="repayment-form" enctype="multipart/form-data">
      			@csrf
      			<input type="hidden" id="traind" name="traind">
        		<div class="widget-box transparent" id="recent-box">
									<div class="widget-header">
										<h4 class="widget-title lighter smaller">
											<i class="fa fa-credit-card-alt" aria-hidden="true"></i> {{ isset($data['lang']['lang']['payment_method']) ? $data['lang']['lang']['payment_method'] :'付款方式'}}
										</h4>

										<div class="widget-toolbar no-border">
											<ul class="nav nav-tabs" id="recent-tab">
												<li class="  active">
													<a data-toggle="tab" class="payment_method f-15" data-id="1" href="#online-tab">{{ isset($data['lang']['lang']['online_transfer']) ? $data['lang']['lang']['online_transfer'] :'线上支付'}}</a>
												</li>

												<li class=" ">
													<a data-toggle="tab" class="payment_method f-15" data-id="2" href="#cdm-tab">{{ isset($data['lang']['lang']['bank_transfer']) ? $data['lang']['lang']['bank_transfer'] :'银行转账'}}</a>
												</li>
											</ul>
										</div>
									</div>

									<div class="widget-body">
										<div class="widget-main padding-4">
											<div class="tab-content padding-8">
												<div id="online-tab" class="tab-pane active">
													<div class="form-group">
													</div>
													<div class="form-group">
														<div class="row">
															<div class="col-4" align="center">
																<label>
																	<input type="radio" name="bank_id" value="10001841">
																	<img src="{{ asset('images/banks/cimb.jpg') }}">
																</label>
															</div>
															<div class="col-4" align="center">
																<label>
																	<input type="radio" name="bank_id" value="10001802">
																	<img src="{{ asset('images/banks/rhb.jpg') }}">
																</label>
															</div>
															<div class="col-4" align="center">
																<label>
																	<input type="radio" name="bank_id" value="10001803">
																	<img src="{{ asset('images/banks/hongleong.jpg') }}">
																</label>
															</div>
														</div>
													</div>
													<br>
													<div class="form-group">
														<div class="row">
															<div class="col-4" align="center">
																<label>
																	<input type="radio" name="bank_id" value="10001843">
																	<img src="{{ asset('images/banks/pbe.jpg') }}">
																</label>
															</div>
														</div>
													</div>

													<div class="form-group">
														<b id="error-message-banks" class="important-text"></b>
													</div>
													
													<div class="form-group">
														<button class="btn btn-primary btn-block placeorder-btn bg-color set_button set_text"> {{ isset($data['lang']['lang']['submit_order']) ? $data['lang']['lang']['submit_order'] :'提交订单'}} </button>
													</div>
													<input type="hidden" name="online" value="1">
												</div>

												<div id="cdm-tab" class="tab-pane" align="center">
													<div class="form-group">
														<input type="hidden" name="cdm_bank_id" value="10000743">
														<div class="card border-danger mb-3" style="max-width: 18rem;" align="center">
															<div class="card-body text-danger">
															    <h5 class="card-title">KIM CAFE SDN BHD</h5>
															    <h5 class="card-title">2-01062-0012787-3</h5>
															    <p class="card-text">RHB BANK</p>
															</div>
														</div>
													</div>
													<div class="form-group bank_details ">
														<h5 class="important-text">{{ isset($data['lang']['lang']['attention_3_days_verify']) ? $data['lang']['lang']['attention_3_days_verify'] :'注意: 需要 3 个工作日审核'}}</h5>
													</div>

													<input type="hidden" name="cdm">
													<div class="form-group">
														<input type="file" name="bank_slip" class="form-control" accept="image/*">
													</div>

													<div class="form-group">
														<b id="error-message-cdm-banks" class="important-text"></b>
													</div>
													
													<div class="form-group">
															<button class="btn btn-primary btn-block cdm-placeorder-btn bg-color set_button set_text"> {{ isset($data['lang']['lang']['submit_order']) ? $data['lang']['lang']['submit_order'] :'提交订单'}} </button>
													</div>
												</div><!-- /.#member-tab -->
												
											</div>
										</div><!-- /.widget-main -->
									</div><!-- /.widget-body -->
								</div>
							</form>
      		</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-default set_button set_text" data-dismiss="modal">{{ isset($data['lang']['lang']['cancel']) ? $data['lang']['lang']['cancel'] :'取消'}}</button>
        		<!-- <button type="button" class="btn btn-primary pay-button">现在付款</button> -->
      		</div>
    	</div>
  	</div>
</div>
@endsection

@section('js')
<script type="text/javascript">
	$('.pay-now-button').click( function(){
		$('#traind').val($(this).data('id'));
	});

	$('.cdm-placeorder-btn').click( function(e){
		// e.preventDefault();
		$('.loading-gif').show();
		// var empty_fill;
		

	 //    if(empty_fill == 1){
	 //    	$('.error-message').html('Please fill in all required field.');
	 //    	$('.loading-gif').hide();
	 //    	return false;
	 //    }

	    

	 //    var cdm_bank_id = $('select[name="cdm_bank_id"]').val();
		// var bank_slip = $('input[name="bank_slip"]').val();
		// var tid = $('#traind').val();
		
		// if(!bank_slip){
		// 	$('#error-message-cdm-banks').html('请选择银行并上传您的银行卡以继续.');
		// 	$('.loading-gif').hide();
		// 	return false;
		// }
		
	    $('input[name="cdm"]').val(1);
	    $('.loading-gif').hide();
	    $('#repayment-form').submit();

	   //  fd = new FormData();
	   //  fd.append('cdm_bank_id', cdm_bank_id);
	   //  fd.append('bank_slip', bank_slip);
	   //  fd.append('transaction_id', tid);
	   //  fd.append('cdm', '1');
	    

	   //  $.ajax({
	   //     url: '{{ route("cdmRepayment") }}',
	   //     type: 'post',
	   //     data: fd,
	   //     contentType: false,
	   //     processData: false,
	   //     success: function(response){
				// window.reload();
	       	  
	   //     },
	   //  });
	});

	$('.placeorder-btn').click( function(e){
		// e.preventDefault();
		
		// if(!$("input[name='bank_id']:checked").val()){
	 //    	$('#error-message-banks').html('请选择银行以继续付款.');
	 //    	return false;
	 //    }
	    
	 //    var fd = new FormData();
		// fd.append('transaction_id', $('#traind').val());
		// fd.append('bank_code', $("input[name='bank_id']:checked").val());

		$('.loading-gif').show();
		$('input[name="cdm"]').val(0);
		// $.ajax({
	 //       url: '{{ route("Repayment") }}',
	 //       type: 'post',
	 //       data: fd,
	 //       contentType: false,
	 //       processData: false,
	 //       success: function(response){

	 //       		var url = "{{ route('PaymentProcess', [':id', ':bank_code']) }}";
		// 			url = url.replace(':id', response);
		// 			url = url.replace(':bank_code', $("input[name='bank_id']:checked").val());

		// 		window.location.href = url;
	       	  
	 //       },
	 //    });
	 	$('.loading-gif').hide();
		$('#repayment-form').submit();
	});
</script>
@endsection