@extends('layouts.app')

@section('content')

@include('partial.frontend.profile_header')

<div class="profile-content pb-5">
	<div class="container">
		<div class="container-box">
			<h3>
				{{ isset($data['lang']['lang']['product_name']) ? $data['lang']['lang']['product_name'] :'Product Name'}}: {{ $product->product_name }}
			</h3>
			@if(!empty($variation->id))
				{{ isset($data['lang']['lang']['option']) ? $data['lang']['lang']['option'] :'Option'}}: {{ $variation->variation_name }}
			@endif
			@if(!empty($second_variation->id))
				{{ isset($data['lang']['lang']['second_option']) ? $data['lang']['lang']['second_option'] :'Second Option'}}: {{ $second_variation->variation_name }}
			@endif
			<div class="row">
				<div class="col-12">
					<table class="table table-bordered">
						<thead>
							<tr>
								<th>
									{{ isset($data['lang']['lang']['date_and_time']) ? $data['lang']['lang']['date_and_time'] :'Date & Time'}}
								</th>
								<th>
									{{ isset($data['lang']['lang']['description']) ? $data['lang']['lang']['description'] :'Description'}}
								</th>
								<th>
									{{ isset($data['lang']['lang']['in']) ? $data['lang']['lang']['in'] :'In'}}
								</th>
								<th>
									{{ isset($data['lang']['lang']['out']) ? $data['lang']['lang']['out'] :'Out'}}
								</th>
							</tr>
						</thead>

						<tbody>
							@foreach($all as $transaction)
							<tr>
								<td>
									{{ $transaction->created_at }}
								</td>
								<td>
									@if($transaction->ws_id)
									<b>{{ isset($data['lang']['lang']['withdrawal_stocks']) ? $data['lang']['lang']['withdrawal_stocks'] :'Withdrawal Stocks'}}</b>
									<br>
									@elseif($transaction->customer_purchase_id)
										@if($transaction->buyer_code == Auth::user()->code)
											<b>Self Purchase</b>
										@else
											<b>{{ isset($data['lang']['lang']['purchase_from_customer']) ? $data['lang']['lang']['purchase_from_customer'] :'顾客购买'}}</b>
										@endif
										<br>
									@elseif($transaction->scr_id)
										<b>{{ isset($data['lang']['lang']['stock_clearance']) ? $data['lang']['lang']['stock_clearance'] :'清仓'}}</b>
										<br>
									@elseif(!empty($transaction->type))
										<b>
											Adjustment By Admin 
											@if($transaction->type == 1)
												(Increase)
											@else
												(Decrease)
											@endif
										</b>
										<br>
									@else
									<b>{{ isset($data['lang']['lang']['store_stocks']) ? $data['lang']['lang']['store_stocks'] :'Store Stocks'}}</b>
									<br>
									@endif
									@if(empty($transaction->type))
									Transaction No: {{ $transaction->transaction_no }}
									<br>
									@endif
									@if($transaction->status == 99)
									<span class="badge badge-info">
										{{ isset($data['lang']['lang']['awaiting_verify']) ? $data['lang']['lang']['awaiting_verify'] :'Pending Verification'}}
									</span>
									@elseif($transaction->status == 1)
									<span class="badge bg-success">
										{{ isset($data['lang']['lang']['active']) ? $data['lang']['lang']['active'] :'Active'}}
									</span>
									@endif
									@if(!empty($transaction->awb_no))
									<br>
									Tracking No: 
									<a onclick="linkTrack('{{ $transaction->awb_no }}')">
										{{ $transaction->awb_no }}
									</a>
									<button onclick="linkTrack('{{ $transaction->awb_no }}')">{{ isset($data['lang']['lang']['track']) ? $data['lang']['lang']['track'] :'TRACK'}}</button>
									<script src="//www.tracking.my/track-button.js"></script>
									<script>
									  function linkTrack(num) {
									    TrackButton.track({
									      tracking_no: num
									    });
									  }
									</script>
									@endif

									@if(isset($ship_details[$transaction->transaction_no]) && !empty($ship_details[$transaction->transaction_no]))
										<br>
										<a href="{{ route('logistic_tracking', $transaction->transaction_no) }}" style="color: #ffa023">
											<i class="fa fa-truck" aria-hidden="true" style="font-size: 17px;"></i> &nbsp;&nbsp;&nbsp; [{{ $transaction->courier }}] {{ $ship_details[$transaction->transaction_no] }} <br>
											<i class="fa fa-cube" aria-hidden="true" style="font-size: 17px;"></i> &nbsp;&nbsp;&nbsp; {{ isset($data['lang']['lang']['tracking_no']) ? $data['lang']['lang']['tracking_no'] :'追踪号码'}}: 
											@if(!empty($transaction->tracking_no))
											{{ $transaction->tracking_no }}
											@else
											-
											@endif
										</a>
									@endif
								</td>
								<td>
									@if($transaction->d_id)
									{{ $transaction->quantity }}
									@elseif($transaction->ws_id)
									<i class="fa fa-minus"></i>
									@elseif($transaction->customer_purchase_d_id)
									<i class="fa fa-minus"></i>
									@elseif($transaction->scr_id)
									<i class="fa fa-minus"></i>
									@elseif($transaction->type)
										@if($transaction->type == 1)
											{{ $transaction->amount }}
										@else
											<i class="fa fa-minus"></i>
										@endif
									@endif
								</td>
								<td>
									@if($transaction->ws_id)
									{{ $transaction->quantity }}
									@elseif($transaction->customer_purchase_d_id)
									{{ $transaction->quantity }}
									@elseif($transaction->d_id)
									<i class="fa fa-minus"></i>
									@elseif($transaction->scr_id)
									{{ $transaction->amount }}
									@elseif($transaction->type)
										@if($transaction->type == 1)
											<i class="fa fa-minus"></i>
										@else
											{{ $transaction->amount }}
										@endif
									@endif
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('js')
<script type="text/javascript">
	$('.withdrawal-stocks').click(function(e){
		e.preventDefault();

		var ele = $(this);
		var pid = ele.data('pid');
		var vid = ele.data('vid');
		var svid = ele.data('svid');
		var quantity = ele.closest('.parent-box').find('.quantity').val();

    	var fd = new FormData();
			fd.append('pid', pid);
			fd.append('vid', vid);
			fd.append('svid', svid);
			fd.append('quantity', quantity);
			
		$.ajax({
		       url: '{{ route("SubmitWithdrawalStock") }}',
		       type: 'post',
		       data: fd,
		       contentType: false,
		       processData: false,
		       success: function(response){
		       		if(response == 1){
			       		toastr.success('Product Withdrawal Submited. Please Wait Admin For Approval');
			       		location.reload();
		       		}else{
		       			toastr.success('Insufficient Balance');
			       		// location.reload();
		       		}
		       }
		});
	});
</script>
@endsection