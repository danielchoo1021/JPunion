@extends('layouts.app')

@section('content')

@include('partial.frontend.profile_header')

<div class="profile-content">
	<div class="container">
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
							<!-- 
								<a href="{{ route('order_detail', $transaction->transaction_no) }}" class="btn btn-primary btn-sm pay-now-button">
									Manage
								</a>

								<a href="#" class="btn btn-success btn-sm pay-now-button" data-id="{{ md5($transaction->id) }}" data-toggle="modal" data-target="#myModal">
									Pay now
								</a> -->
							
							<a href="{{ route('download_invoice', $transaction->transaction_no) }}" class="btn btn-primary btn-sm pay-now-button set_button set_text">
								{{ isset($data['lang']['lang']['download_as']) ? $data['lang']['lang']['download_as'] :'下载为'}}PDF<i class="fa fa-download"></i>
							</a>
							<a href="{{ route('customer_invoice', $transaction->transaction_no) }}" class="btn btn-primary btn-sm set_button set_text">
								{{ isset($data['lang']['lang']['view']) ? $data['lang']['lang']['view'] :'查看'}} Invoice<i class="fa fa-eye" aria-hidden="true"></i>
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
										<span class="badge badge-pill bg-warning">{{ isset($data['lang']['lang']['unpaid']) ? $data['lang']['lang']['unpaid'] :'Unpaid'}}</span>
									@elseif($transaction->status == 98)
										<span class="badge badge-pill badge-info">{{ isset($data['lang']['lang']['awaiting_verify']) ? $data['lang']['lang']['awaiting_verify'] :'Waiting for verify'}}</span>
									@elseif($transaction->status == 97)
										<span class="badge badge-pill badge-info">{{ isset($data['lang']['lang']['awaiting_verify']) ? $data['lang']['lang']['awaiting_verify'] :'Waiting for verify'}}</span>
									@elseif($transaction->status == 1)
										@if(!empty($transaction->bank_id))
				                            <span class="badge badge-pill bg-success">{{ isset($data['lang']['lang']['paid']) ? $data['lang']['lang']['paid'] :'Paid'}}</span>
				                        @else
				                            <span class="badge badge-pill bg-success">{{ isset($data['lang']['lang']['paid']) ? $data['lang']['lang']['paid'] :'Paid'}}</span>
				                        @endif
									@else
										<span class="badge badge-pill bg-danger">{{ isset($data['lang']['lang']['cancelled']) ? $data['lang']['lang']['cancelled'] :'Cancelled'}}</span>
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
						<i class="fa fa-shopping-cart fa-3x"></i>
					</div>
				</div>
				@endif
			</div>
	</div>
</div>
@endsection

@section('js')
<script type="text/javascript">
	$('input[name=dates]').daterangepicker({
		'applyClass' : 'btn-sm btn-success',
		'cancelClass' : 'btn-sm btn-default',
		locale: {
			applyLabel: 'Apply',
			cancelLabel: 'Cancel',
		}
	})
	.prev().on(ace.click_event, function(){
		$(this).next().focus();
	});
</script>
@endsection