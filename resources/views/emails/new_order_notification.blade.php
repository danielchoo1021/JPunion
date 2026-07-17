<!DOCTYPE html>

<html>

<head>

	<title>{{ $data['website_name'] }}</title>
	<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
</head>

<body>
	<div class="container mt-5">
		<h3>New Order Received</h3>
		<span>Transaction No: #{{ $transaction->transaction_no }}</span><br>
		<span>Purchaser: {{ $buyer_details->display_code }}{{ $buyer_details->display_running_no }} ({{ $buyer_details->f_name }})</span><br>
		<span>
			Upline:
			@if(!empty($upline_details))
				{{ $upline_details->display_code }}{{ $upline_details->display_running_no }} ({{ $upline_details->f_name }})
			@else
				-
			@endif
		</span><br>
		<span>
			Status: 
			@if($transaction->status == 99)
				Unpaid
			@elseif($transaction->status == 98)
				Waiting Verification
			@elseif($transaction->status == 97)
				In-progress
			@elseif($transaction->status == '96')
				Rejected
			@elseif($transaction->status == 1)
				@if($transaction->completed == 1)
					Delivered
				@elseif($transaction->completed != 1 && $transaction->to_receive)
					To Receive
				@else
					Paid
				@endif
			@else
				Cancelled
				
				@if(!empty($transaction->cancelled_name))
					(Cancelled By: {{ $transaction->cancelled_name }}) 
				@endif
			@endif
		</span><br>
		<span>
			Delivery Method: 
			@if(!empty($transaction->on_hold))
				@if($transaction->on_hold == 99)
					Awaiting Pick Up
				@elseif($transaction->on_hold == 1)
					Collected
				@endif
			@else
				No Pickup
			@endif
		</span>
		<hr>
		<table class="table table-bordered">
			<thead style="border:1px solid #ddd">
				<th style="border:1px solid #ddd">Product Image</th>
				<th style="border:1px solid #ddd">Product Details</th>
				<th style="border:1px solid #ddd">Unit Price</th>
				<th style="border:1px solid #ddd">Quantity</th>
				<th style="border:1px solid #ddd">Total Amount</th>
			</thead>
			<tbody>
				@foreach ($transaction_details as $item)
				<tr style="border:1px solid #ddd">
					<td style="border:1px solid #ddd">
						<img src="{{ asset($item->product_image) }}" width="80" height="80" class="img-thumbnail" alt="">
					</td>
					<td style="border:1px solid #ddd">{{ $item->product_name }}</td>
					<td style="border:1px solid #ddd">RM {{ number_format($item->unit_price, 2) }}</td>
					<td style="border:1px solid #ddd">{{ $item->quantity }}</td>
					<td style="border:1px solid #ddd">RM {{ number_format($item->unit_price * $item->quantity, 2) }}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</body>

</html>