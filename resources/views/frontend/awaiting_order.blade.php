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
			<div class="form-group">
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
					<div class="col-10">
						<a href="{{ route('profile') }}">
							<!-- &nbsp;
							<b class="profile-name">{{ Auth::user()->f_name }} {{ Auth::user()->l_name }}</b>
							<br>
							&nbsp;
							<small class="profile-code">{{ isset($data['lang']['lang']['agent_code']) ? $data['lang']['lang']['agent_code'] :'代码'}}: {{ $own_display_code }}</small>
							<br>
							&nbsp;
							<small class="profile-level">
								{{ isset($data['lang']['lang']['level']) ? $data['lang']['lang']['level'] :'等级'}}: {{ !empty($lvl) ? $lvl : ' - ' }}
							</small>
							<br>
							&nbsp;
							<small class="profile-level">
								{{ isset($data['lang']['lang']['upline_name']) ? $data['lang']['lang']['upline_name'] :'推荐人名字'}}: {{ (!empty($upline_name)) ? $upline_name : '' }}
							</small>
							<br>
							&nbsp;
							<small class="profile-level">
								{{ isset($data['lang']['lang']['upline_code']) ? $data['lang']['lang']['upline_code'] :'推荐人代码'}}: {{ (!empty($upline_code)) ? $upline_code : '-' }}
							</small>
							<br>
							&nbsp;
							<small class="profile-level">
								Join Date: 
								@if(!empty($aff_joined_date[Auth::user()->code]))
								{{ $aff_joined_date[Auth::user()->code]->created_at }}
								@else
								{{ Auth::user()->created_at }}
								@endif
							</small> -->
							&nbsp;
							<b class="profile-name">{{ Auth::user()->f_name }} {{ Auth::user()->l_name }}</b>
							<br>
							&nbsp;
							<small class="profile-level">
								Join Date: 
								@if(!empty($aff_joined_date[Auth::user()->code]))
								{{ $aff_joined_date[Auth::user()->code]->created_at }}
								@else
								{{ Auth::user()->created_at }}
								@endif
							</small>
							<br>
							&nbsp;
							<br>
							&nbsp;
							<br>
							&nbsp;
							<br>
							&nbsp;
						</a>
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
			
			@if(Auth::guard('web')->check() || Auth::guard('corporate')->check())
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
				@if(Auth::guard('admin')->check() || (Auth::guard('merchant')->check() && Auth::guard('merchant')->user()->verify_status == 1))
					<!-- <div class="form-group container-box sl-personal-header">
						<div class="row">
							<div class="col-3" align="center">
								<a href="{{ route('myqrcode') }}">
									<img src="{{ asset('images/qrcode.png') }}" width="30">
									<br>
									<span class="profile-word">{{ isset($data['lang']['lang']['my_qrcode']) ? $data['lang']['lang']['my_qrcode'] :'我的二维码'}}</span>
								</a>
							</div>

							<div class="col-3" align="center">
								<a href="{{ route('MyAffiliate', Auth::user()->code) }}">
									<img src="{{ asset('images/profile/585e4d1ccb11b227491c339b.png') }}" width="30">
									<br>
									<span class="profile-word">{{ isset($data['lang']['lang']['my_team']) ? $data['lang']['lang']['my_team'] :'我的团队'}}</span>
								</a>
							</div>

							<div class="col-3" align="center">
								<a href="{{ route('MyCustomer', Auth::user()->code) }}">
									<img src="{{ asset('images/profile/585e4d1ccb11b227491c339b.png') }}" width="30">
									<br>
									<span class="profile-word">{{ isset($data['lang']['lang']['my_customer']) ? $data['lang']['lang']['my_customer'] :'我的顾客'}}</span>
								</a>
							</div>

							<div class="col-3" align="center">
								<a href="{{ route('wallet') }}">
									<img src="{{ asset('images/profile/c3286d4d32fa90ebcf09b488654612b9-wallet-icon-by-vexels.png') }}" width="30">
									<br>
									<span class="profile-word">{{ isset($data['lang']['lang']['my_wallet']) ? $data['lang']['lang']['my_wallet'] :'我的钱包'}}</span>
								</a>
							</div>
						</div>
					</div> -->
				@endif
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
						<span class="profile-word" style="font-weight: bold; text-decoration: underline;">{{ isset($data['lang']['lang']['to_be_collected']) ? $data['lang']['lang']['to_be_collected'] :'待取货'}}</span>
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
							@if(!empty($transaction->awb_no))
							<br>
							{{ isset($data['lang']['lang']['tracking_no']) ? $data['lang']['lang']['tracking_no'] :'追踪号码'}}: <a onclick="linkTrack('{{ $transaction->awb_no }}')">{{ $transaction->awb_no }}</a>
							<button onclick="linkTrack('{{ $transaction->awb_no }}')">{{ isset($data['lang']['lang']['track']) ? $data['lang']['lang']['track'] :'追踪'}}</button>
							<script src="//www.tracking.my/track-button.js"></script>
							<script>
							  function linkTrack(num) {
							    TrackButton.track({
							      tracking_no: num
							    });
							  }
							</script>
							@endif
						</div>
						<div class="col-6" align="right">
							@if($transaction->status == 99)
								<a href="#" class="btn btn-primary btn-sm pay-now-button set_button set_text" data-id="{{ md5($transaction->id) }}" data-toggle="modal" data-target="#myModal">
									{{ isset($data['lang']['lang']['pay_now']) ? $data['lang']['lang']['pay_now'] :'现在付款'}}<i class="fa fa-money"></i>
								</a>
							@else
								<a href="{{ route('order_detail', $transaction->transaction_no) }}" class="btn btn-primary btn-sm pay-now-button check-button set_button set_text" data-id="{{ $transaction->id }}">
									{{ isset($data['lang']['lang']['view']) ? $data['lang']['lang']['view'] :'查看'}}<i class="fa fa-eye"></i>
								</a>
							@endif
							<!-- <a href="{{ route('customer_invoice', $transaction->transaction_no) }}" class="btn btn-primary btn-sm pay-now-button" target="_blank">
								下载单据
							</a> -->
							<a href="{{ route('download_invoice', $transaction->transaction_no) }}" class="btn btn-primary btn-sm pay-now-button set_button set_text">
								{{ isset($data['lang']['lang']['download_as']) ? $data['lang']['lang']['download_as'] :'下载为'}}PDF<i class="fa fa-download"></i>
							</a>
							<!-- <a href="{{ route('download_invoice', $transaction->transaction_no) }}" class="btn btn-primary btn-sm pay-now-button">
								download invoice
							</a> -->
							<!-- <input type="hidden" name="tid" id="tid" value="{{ $transaction->id }}"> -->
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
@endsection

@section('js')
<script type="text/javascript">
	$('.check-button').click( function(){
		$('.loading-gif').show();
		var fd = new FormData();
		fd.append('tid', $(this).data('id'));

		$.ajax({
			url: '{{ route("viewTransaction") }}',
			type: 'post',
			data: fd,
			contentType: false,
			processData: false,
			success: function(response){
				$('.loading-gif').hide();
			}
		})
	});
</script>
@endsection