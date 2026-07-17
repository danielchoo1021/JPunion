@extends('layouts.admin_app')
@section('css')
<style type="text/css">
	.right-panel{
		margin-left: 0px !important;
	}
</style>
@endsection
@section('content')
<a href="#" class="print-window" style="display: none;">
	<i class="fa fa-print"></i> {{ isset($data['backendlang']['backendlang']['Print']) ? $data['backendlang']['backendlang']['Print'] :'' }}
</a>
<table width="100%">
	<tr>
		<td>
			@if(!empty($data['website_logo']))
			<img src="{{ asset($data['website_logo']) }}" style="width: 100px;">
			@endif
			<h3>
				{{ !empty($data['web_setting']->invoice_name) ? 'YGT' : $data['web_setting']->website_name }}
			</h3>
			<h5>
				@if(!empty($data['web_setting']->company_registration_no))
				({{ $data['web_setting']->company_registration_no }})
				@endif
			</h5>
		</td>
		<td align="right">
			@if($transaction->mall == 1)
			<h1>{{ isset($data['backendlang']['backendlang']['Redemption_Receipt']) ? $data['backendlang']['backendlang']['Redemption_Receipt'] :'' }}</h1>
			@else
			<h1>{{ isset($data['backendlang']['backendlang']['Invoice']) ? $data['backendlang']['backendlang']['Invoice'] :'' }}</h1>
			@endif
		</td>
	</tr>
	<tr>
		<td>
			<b>{{ isset($data['backendlang']['backendlang']['Contact_Number']) ? $data['backendlang']['backendlang']['Contact_Number'] :'' }}: </b>+6{{ $data['admin']->phone }}
		</td>
	</tr>
	<tr>
		<td>
			<b>{{ isset($data['backendlang']['backendlang']['Address']) ? $data['backendlang']['backendlang']['Address'] :'' }}: </b>{{ $data['web_setting']->address }}
		</td>
	</tr>
</table>
<br>
<table width="100%">
	<tr>
		<td>
			{{ isset($data['backendlang']['backendlang']['To']) ? $data['backendlang']['backendlang']['To'] :'' }}:
		</td>
	</tr>
	<tr>
		<td>
			<h4>{{ $transaction->address_name }}</h4>
		</td>
		<td align="right">
			<h4>
				@if($transaction->mall == 1)
					<b>{{ isset($data['backendlang']['backendlang']['Receipt']) ? $data['backendlang']['backendlang']['Receipt'] :'' }}: #{{ $transaction->transaction_no }}</b>
				@else
					<b>{{ isset($data['backendlang']['backendlang']['Invoice']) ? $data['backendlang']['backendlang']['Invoice'] :'' }} #{{ $transaction->transaction_no }}</b>
				@endif
			</h4>
		</td>
	</tr>
	<tr>
		<td>
			{{ isset($data['backendlang']['backendlang']['Email']) ? $data['backendlang']['backendlang']['Email'] :'' }}: {{ $transaction->email }}
		</td>
		<td align="right">{{ isset($data['backendlang']['backendlang']['Invoice_Date']) ? $data['backendlang']['backendlang']['Invoice_Date'] :'' }}: {{ $transaction->created_at }}</td>
	</tr>
	<tr>
		<td>
			{{ isset($data['backendlang']['backendlang']['Contact']) ? $data['backendlang']['backendlang']['Contact'] :'' }}: 
			+{{ $transaction->country_code }}{{ ($transaction->phone[0] == 0) ? substr($transaction->phone, 1) : $transaction->phone }}
		</td>
	</tr>
	<tr>
		<td>
			@if($transaction->mall == 1)
                <b>{{ isset($data['backendlang']['backendlang']['Cash_Wallet']) ? $data['backendlang']['backendlang']['Cash_Wallet'] :'' }}</b> <br>
            @elseif($transaction->mall == 2)
                <b>{{ isset($data['backendlang']['backendlang']['Topup_Wallet']) ? $data['backendlang']['backendlang']['Topup_Wallet'] :'' }}</b> <br>
            @elseif(!empty($transaction->bank_id))
                <b>{{ isset($data['backendlang']['backendlang']['Online_Banking']) ? $data['backendlang']['backendlang']['Online_Banking'] :'' }}</b> <br>
            @elseif(!empty($transaction->bank_slip))
                <b>{{ isset($data['backendlang']['backendlang']['Bank_Transfer']) ? $data['backendlang']['backendlang']['Bank_Transfer'] :'' }}</b> <br>
            @elseif(!empty($transaction->pv_purchase))
                <b>{{ isset($data['backendlang']['backendlang']['Point_Wallet']) ? $data['backendlang']['backendlang']['Point_Wallet'] :'' }}</b> <br>
            @endif
		</td>
	</tr>
	<tr>
		<td>
			{{ isset($data['backendlang']['backendlang']['Shipping_Method']) ? $data['backendlang']['backendlang']['Shipping_Method'] :'' }}:
			{!! (!empty($transaction->cod_address)) ? ' Pick Up' : ' Delivery' !!}
		</td>
	</tr>
	<tr>
		<td>
			@if(!empty($transaction->cod_address))
				{{ isset($data['backendlang']['backendlang']['Pickup_Address']) ? $data['backendlang']['backendlang']['Pickup_Address'] :'' }}: {{ $cod_address->address }}
			@else

				{{ isset($data['backendlang']['backendlang']['Delivery_Address']) ? $data['backendlang']['backendlang']['Delivery_Address'] :'' }}: {{ $transaction->address }}<br>
				{{ isset($data['backendlang']['backendlang']['Delivery_Postcode']) ? $data['backendlang']['backendlang']['Delivery_Postcode'] :'' }}: {{ $transaction->postcode }}<br>
				{{ isset($data['backendlang']['backendlang']['Delivery_City']) ? $data['backendlang']['backendlang']['Delivery_City'] :'' }}: {{ $transaction->city }}<br>
				{{ isset($data['backendlang']['backendlang']['Delivery_State']) ? $data['backendlang']['backendlang']['Delivery_State'] :'' }}: {{ !empty($delivery_state->name) ? $delivery_state->name : $transaction->state }}<br>
				{{ isset($data['backendlang']['backendlang']['Delivery_Country']) ? $data['backendlang']['backendlang']['Delivery_Country'] :'' }}: {{ !empty($delivery_country->country_name) ? $delivery_country->country_name : $transaction->country }}
			@endif
		</td>
	</tr>
</table>
<hr>
<table class="table table-bordered">
	<tr>
		<td>{{ isset($data['backendlang']['backendlang']['Particulars']) ? $data['backendlang']['backendlang']['Particulars'] :'' }}:</td>
		<td align="right">{{ isset($data['backendlang']['backendlang']['Unit_Price']) ? $data['backendlang']['backendlang']['Unit_Price'] :'' }}:</td>
		<td align="right">{{ isset($data['backendlang']['backendlang']['Quantity']) ? $data['backendlang']['backendlang']['Quantity'] :'' }}</td>
		<td align="center">{{ isset($data['backendlang']['backendlang']['Unit']) ? $data['backendlang']['backendlang']['Unit'] :'' }}</td>
		<td align="right">{{ isset($data['backendlang']['backendlang']['Amount']) ? $data['backendlang']['backendlang']['Amount'] :'' }} (MYR)</td>
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
			{{ isset($data['backendlang']['backendlang']['Sub_Total']) ? $data['backendlang']['backendlang']['Sub_Total'] :'' }}:
		</td>
		<td colspan="4" align="right">
			{{ number_format($sub_total, 2) }}
		</td>
	</tr>
	<tr>
		<td colspan="4" align="right">
			{{ isset($data['backendlang']['backendlang']['Shipping_Fee']) ? $data['backendlang']['backendlang']['Shipping_Fee'] :'' }}:
		</td>
		<td colspan="4" align="right">
			{{ number_format($transaction->shipping_fee, 2) }}
		</td>
	</tr>
	@if(!empty($transaction->ad_discount))
	<tr>
		<td colspan="4" align="right">
			{{ isset($data['backendlang']['backendlang']['Agent_Discount']) ? $data['backendlang']['backendlang']['Agent_Discount'] :'' }}:
		</td>
		<td colspan="4" align="right">
			{{ number_format($transaction->ad_discount, 2) }}
		</td>
	</tr>
	@endif

	<tr>
		<td colspan="4" align="right">
			{{ isset($data['backendlang']['backendlang']['Discount']) ? $data['backendlang']['backendlang']['Discount'] :'' }}:
		</td>
		<td colspan="4" align="right">
			{{ !empty($transaction->discount) && $transaction->discount > 0 ? '(-)' : '' }} {{ number_format($transaction->discount, 2) }}
		</td>
	</tr>

	<tr>
		<td colspan="4" align="right">
			{{ isset($data['backendlang']['backendlang']['Processing_Fee']) ? $data['backendlang']['backendlang']['Processing_Fee'] :'' }}:
		</td>
		<td colspan="4" align="right">
			{{ number_format($transaction->processing_fee, 2) }}
		</td>
	</tr>
	<tr>
		<td colspan="4" align="right">
			{{ isset($data['backendlang']['backendlang']['Grand_Total']) ? $data['backendlang']['backendlang']['Grand_Total'] :'' }}:
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
		<td>1. All payments should be made payable to POWERLINK SDN BHD</td>
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