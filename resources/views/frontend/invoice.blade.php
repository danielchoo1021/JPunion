@extends('layouts.app')
<style type="text/css">
	.table-bordered tbody tr td {
		border-color: #000 !important;
		border-width: 1px !important;
	}
</style>
@section('content')
<a href="#" class="print-window" style="display: none;">
	<i class="fa fa-print"></i> Print
</a>
<table width="100%">
	<tr>
		<td>
			@if(!empty($data['admin']->profile_logo))
			<img src="{{ asset($data['admin']->profile_logo) }}" style="width: 100px;">
			@endif
			<h3>{{ $data['admin']->website_name }} Sdn Bhd</h3>
		</td>
		<td align="right">
			@if($transaction->mall == 1)
			<h1>Redemption Receipt</h1>
			@else
			<h1>Invoice</h1>
			@endif
		</td>
	</tr>
	<tr>
		<td>
			<b>Contact number: </b>+6{{ $data['admin']->phone }}
		</td>
	</tr>
	<tr>
		<td>
			<b>Address: </b>{{ $data['web_setting']->address }}
		</td>
	</tr>
</table>
<br>
<table width="100%">
	<tr>
		<td>
			To:
		</td>
	</tr>
	<tr>
		<td>
			<h4>{{ $transaction->address_name }}</h4>
		</td>
		<td align="right">
			<h4>
				@if($transaction->mall == 1)
					<b>Receipt #{{ $transaction->transaction_no }}</b>
				@else
					<b>Invoice #{{ $transaction->transaction_no }}</b>
				@endif
			</h4>
		</td>
	</tr>
	<tr>
		<td>
			Email: {{ $transaction->email }}
		</td>
		<td align="right">Invoice Date: {{ $transaction->created_at }}</td>
	</tr>
	<tr>
		<td>
			Contact: 
			+{{ $transaction->country_code }}{{ ($transaction->phone[0] == 0) ? substr($transaction->phone, 1) : $transaction->phone }}
		</td>
	</tr>
	<tr>
		<td>
			@if($transaction->mall == 1)
				Payment Method: Wallet
			@else
				Payment Method: {{ (!empty($transaction->bank_id)) ? 'Online Transfer' : 'Bank Transfer' }}
			@endif
		</td>
	</tr>
	<tr>
		<td>
			Shipping Method:
			{!! (!empty($transaction->cod_address)) ? ' Pick Up' : ' Delivery' !!}
		</td>
	</tr>
	<tr>
		<td>
			@if(!empty($transaction->cod_address))
				Pickup Address: {{ $cod_address->address }}
			@else

				Delivery Address: {{ $transaction->address }}<br>
				Delivery Postcode: {{ $transaction->postcode }}<br>
				Delivery City: {{ $transaction->city }}<br>
				Delivery State: {{ $delivery_state->name }}
			@endif
		</td>
	</tr>
</table>
<hr>
<table class="table table-bordered">
	<tr>
		<td style="border: 1px solid #000;">Particulars</td>
		<td align="right" style="border: 1px solid #000;">Unit Price</td>
		<td align="right" style="border: 1px solid #000;">Qty</td>
		<td align="center" style="border: 1px solid #000;">Unit</td>
		<td align="right" style="border: 1px solid #000;">Amount (MYR)</td>
	</tr>
	@php
	$sub_total = 0;
	@endphp
	@foreach($details as $details)
	<tr>
		<td>
			{{ $details->product_name }}<br>
			{!! ($details->sub_category != '') ? "Option: ".$details->sub_category."<br>" : '' !!}
			{!! ($details->second_sub_category != '') ? "Second Option: ".$details->second_sub_category."<br>" : '' !!}
		</td>
		<td align="right">
			{{ number_format($details->unit_price, 2) }}
		</td>
		<td align="right">
			{{ $details->t_qty }}
		</td>
		<td align="center">
			@if($details->packages == '1')
				PKG
			@else
				{{ $details->uom_en }}
			@endif
		</td>
		<td align="right">
			{{ number_format(($details->unit_price) * $details->t_qty, 2) }}
		</td>
	</tr>
	@php
	$sub_total += $details->unit_price * $details->t_qty;
	@endphp
	@endforeach
	<tr>
		<td colspan="4" align="right">
			Sub Total:
		</td>
		<td colspan="4" align="right">
			{{ number_format($sub_total, 2) }}
		</td>
	</tr>
	<tr>
		<td colspan="4" align="right">
			Shipping Fee:
		</td>
		<td colspan="4" align="right">
			{{ number_format($transaction->shipping_fee, 2) }}
		</td>
	</tr>
	@if(!empty($transaction->ad_discount))
	<tr>
		<td colspan="4" align="right">
			Agent Discount:
		</td>
		<td colspan="4" align="right">
			{{ number_format($transaction->ad_discount, 2) }}
		</td>
	</tr>
	@endif

	<tr>
		<td colspan="4" align="right">
			Discount:
		</td>
		<td colspan="4" align="right">
			{{ !empty($transaction->discount) && $transaction->discount > 0 ? '(-)' : '' }} {{ number_format($transaction->discount, 2) }}
		</td>
	</tr>

	<tr>
		<td colspan="4" align="right">
			Processing Fee:
		</td>
		<td colspan="4" align="right">
			{{ number_format($transaction->processing_fee, 2) }}
		</td>
	</tr>
	<tr>
		<td colspan="4" align="right">
			Grand Total:
		</td>
		<td colspan="4" align="right">
			{{ number_format($sub_total + $transaction->shipping_fee - $transaction->discount - $transaction->ad_discount + $transaction->processing_fee, 2) }}
		</td>
	</tr>
</table>
<!-- <hr>
<table>
	<tr>
		<td>
			<b>Notes:</b> 
			<br>
			<br>
		</td>
	</tr>
	<tr>
		<td>1. All payments should be made payable to KIM CAFE SDN BHD</td>
	</tr>
	<tr>
		<td>2. Goods sold are subjected to company’s Goods Return Policy* , if there is dispute subsequently.</td>
	</tr>
	<tr>
		<td>3. Please ensure your purchase details are in order before acknowledging.</td>
	</tr>
	<tr>
		<td>4. This is computer generated duplicate invoice and requires no signature.</td>
	</tr>
	<tr>
		<td>5. This invoice is valid, subject to realization of due payments, as mentioned in details above.</td>
	</tr>
	<tr>
		<td>*Terms & Conditions apply.</td>
	</tr>
</table> -->
@endsection

@section('js')
<script type="text/javascript">
	$('.print-window').click(function() {
	    window.print();
	});
	$(document).ready(function(){
		$('.print-window').click();
		$('.tr-color').css('background-color', '#ddd');
	});
</script>
@endsection