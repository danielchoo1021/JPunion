@extends('layouts.app')
@section('css')
@endsection
@section('content')

@include('partial.frontend.profile_header')

<div class="profile-content pb-5">
	<div class="container">
		<div class="form-group container-box mb-pb-8">
			<div class="row justify-content-center">
				<div class="col-6" align="left">
					<b>{{ isset($data['lang']['lang']['my_orders']) ? $data['lang']['lang']['my_orders'] :'我的订单'}}</b>
				</div>
				<div class="col-6" align="right">
					<a href="{{ route('verifying_order') }}">
						<small>{{ isset($data['lang']['lang']['view_all_order']) ? $data['lang']['lang']['view_all_order'] :'查看所有订单'}} <i class="fa fa-angle-right" aria-hidden="true"></i></small>
					</a>
				</div>
				</div>
				<br>
				<div class="row">

				<div class="col pb-3" align="center">
					<a href="{{ route('checkout') }}" style="position: relative; display: inline-block;">
						@if($data['totalCart'] > 0)
						<span class="badge badge-pill bg-danger" style="position: absolute; right: -10px; top: -7px;">
							{{ $data['totalCart'] }}
						</span>
						@endif
						<img src="{{ asset('images/cart.png') }}" width="30">
						<br>
						<span class="profile-word">{{ isset($data['lang']['lang']['to_pay']) ? $data['lang']['lang']['to_pay'] :'待付款'}}</span>
					</a>
				</div>

				<div class="col pb-3" align="center">
					<a href="{{ route('verifying_order') }}" style="position: relative; display: inline-block;">
						@if($countVerifying > 0)
						<span class="badge badge-pill bg-danger" style="position: absolute; right: -5px; top: -7px;">
							{{ $countVerifying }}
						</span>
						@endif
						<img src="{{ asset('images/profile/review.png') }}" width="30">
						<br>
						<span class="profile-word">{{ isset($data['lang']['lang']['to_verify']) ? $data['lang']['lang']['to_verify'] :'待审核'}}</span>
					</a>
				</div>

				<div class="col pb-3" align="center">
					<a href="{{ route('pending_shipping') }}" style="position: relative; display: inline-block;">
						@if($countToShip > 0)
						<span class="badge badge-pill bg-danger" style="position: absolute; right: -5px; top: -7px;">
							{{ $countToShip }}
						</span>
						@endif
						<img src="{{ asset('images/profile/shipment_pending_1017207.png') }}" width="30">
						<br>
						<span class="profile-word">{{ isset($data['lang']['lang']['to_be_delivered']) ? $data['lang']['lang']['to_be_delivered'] :'待出货'}}</span>
					</a>
				</div>

				<div class="col pb-3" align="center">
					<a href="{{ route('pending_receive') }}" style="position: relative; display: inline-block;">
						@if($countToReceive > 0)
						<span class="badge badge-pill bg-danger" style="position: absolute; right: -5px; top: -7px;">
							{{ $countToReceive }}
						</span>
						@endif
						<img src="{{ asset('images/profile/Pending-Truck-Delivery-Commerce-Logistic-Transportation-512.png') }}" width="30">
						<br>
						<span class="profile-word" style="font-weight: bold; text-decoration: underline;">{{ isset($data['lang']['lang']['to_be_received']) ? $data['lang']['lang']['to_be_received'] :'待收货'}}</span>
					</a>
				</div>

				<div class="col pb-3" align="center">
					<a href="{{ route('completed_order') }}" style="position: relative; display: inline-block;">
						@if($countCompleted > 0)
						<span class="badge badge-pill bg-danger" style="position: absolute; right: -5px; top: -7px;">
							{{ $countCompleted }}
						</span>
						@endif
						<img src="{{ asset('images/profile/Box_Package_Delivery_Shipping_Complete_Check_Done-512.png') }}" width="30">
						<br>
						<span class="profile-word">{{ isset($data['lang']['lang']['completed']) ? $data['lang']['lang']['completed'] :'已完成'}}</span>
					</a>
				</div>

				<div class="col pb-3" align="center">
					<a href="{{ route('cancelled_order') }}" style="position: relative; display: inline-block;">
						@if($countCancelled > 0)
						<span class="badge badge-pill bg-danger" style="position: absolute; right: -5px; top: -7px;">
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
				@if(!$transactions->isEmpty())
				@foreach($transactions as $transaction)
				<div class="form-group container-box">
					<div class="row">
						<div class="col-6 order-no-details">
							<b>{{ isset($data['lang']['lang']['order_no']) ? $data['lang']['lang']['order_no'] :'单号'}}: {{ $transaction->transaction_no }}</b><br>
							   {{ isset($data['lang']['lang']['order_dates']) ? $data['lang']['lang']['order_dates'] :'订单日期'}}: {{ $transaction->created_at }}
							@if(!empty($transaction->awb_no))
							<br>
							{{ isset($data['lang']['lang']['tracking_no']) ? $data['lang']['lang']['tracking_no'] :'追踪号码'}}: {{ $transaction->created_at }}: <a onclick="linkTrack('{{ $transaction->awb_no }}')">{{ $transaction->awb_no }}</a>
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
								<a href="{{ route('order_detail', $transaction->transaction_no) }}" class="btn btn-primary btn-sm pay-now-button set_button set_text">
									{{ isset($data['lang']['lang']['view']) ? $data['lang']['lang']['view'] :'查看'}}<i class="fa fa-eye"></i>
								</a>
							@endif

							<!-- <a href="{{ route('customer_invoice', $transaction->transaction_no) }}" class="btn btn-primary btn-sm pay-now-button" target="_blank">
								下载单据
							</a>
 -->
							<a href="{{ route('download_invoice', $transaction->transaction_no) }}" class="btn btn-primary btn-sm pay-now-button set_button set_text">
								{{ isset($data['lang']['lang']['download_as']) ? $data['lang']['lang']['download_as'] :'下载为'}}PDF<i class="fa fa-download"></i>
							</a>
							 <!-- <a href="{{ route('download_invoice', $transaction->transaction_no) }}" class="btn btn-primary btn-sm pay-now-button" target="_blank">
								Download Invoice
							</a> -->
							<!-- <a href="{{ route('customer_invoice', $transaction->transaction_no) }}" download="invoice.pdf" class="btn btn-primary btn-sm pay-now-button">
								Download Invoice
							</a> -->
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
								@if($transaction->pv_purchase == 1)
									{{ number_format($detail->unit_price, 2) }} {{ isset($data['lang']['lang']['point']) ? $data['lang']['lang']['point'] :'Point'}}
								@else
									RM {{ number_format($detail->unit_price, 2) }}
								@endif
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
							@if($transaction->pv_purchase == 1)
								{{ isset($data['lang']['lang']['sub_total']) ? $data['lang']['lang']['sub_total'] :'合计'}}: {{ number_format($transaction->grand_total, 2) }} Point
							@else
								{{ isset($data['lang']['lang']['sub_total']) ? $data['lang']['lang']['sub_total'] :'合计'}}: RM {{ number_format($transaction->grand_total, 2) }}
							@endif
						</div>
					</div>

					@if(!$allCouriers[$transaction->id]->isEmpty())
						@foreach($allCouriers[$transaction->id] as $allCourier)
							@if(isset($ship_details[$transaction->id][$allCourier->id]))
							<hr>
							<div class="form-group">
								<a href="{{ route('logistic_tracking', $allCourier->order_number) }}" style="color: #ffa023">
									<i class="fa fa-truck" aria-hidden="true" style="font-size: 17px;"></i> &nbsp;&nbsp;&nbsp; [{{ $allCourier->courier }}] {{ $ship_details[$transaction->id][$allCourier->id] }} <br>
									<i class="fa fa-cube" aria-hidden="true" style="font-size: 17px;"></i> &nbsp;&nbsp;&nbsp; {{ isset($data['lang']['lang']['tracking_no']) ? $data['lang']['lang']['tracking_no'] :'追踪号码'}}: 
									@if(!empty($allCourier->tracking_no))
									{{ $allCourier->tracking_no }}
									@else
									-
									@endif
								</a>
							</div>
							@endif
						@endforeach
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
@endsection