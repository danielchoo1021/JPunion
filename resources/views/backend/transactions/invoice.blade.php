@extends('layouts.admin_app')
<style type="text/css">
	body{
		font-size: 12px !important;
	}

	.right-panel{
		margin-left: 0px !important;
	}
</style>
@section('content')
<a href="#" class="print-window" style="display: none;">
	<i class="fa fa-print"></i> {{ isset($data['backendlang']['backendlang']['Print']) ? $data['backendlang']['backendlang']['Print'] :'' }}
</a>
<table width="100%">
	<tr class="row">
		<td class="col-2" align="right">
			@if(!empty($data['website_logo']))
			<img src="{{ asset($data['website_logo']) }}" style="width: 100px;">
			@endif
		</td>
		<td style="text-align: center;" class="col-8">
			<h3>
				<b>
					{{ !empty($data['web_setting']->invoice_name) ? $data['web_setting']->invoice_name : $data['admin']->company_name }} 
				</b>
			</h3>
			<h4>
				@if(!empty($data['web_setting']->company_registration_no))
				({{ $data['web_setting']->company_registration_no }})
				@endif
			</h4>
			<h4 style="white-space: pre-line;">
				{{ $data['web_setting']->company_address }}
			</h4>
			<!-- <h4>{{ $data['web_setting']->company_address }}</h4> -->
			<h4>{{ isset($data['backendlang']['backendlang']['Tel']) ? $data['backendlang']['backendlang']['Tel'] :'' }}: {{ $data['web_setting']->company_phone }}</h4>
		</td>
		<td align="right" class="col-2">
			<!-- @if($transaction->mall == 1)
			<h1>Redemption Receipt</h1>
			@else
			<h1>Invoice</h1>
			@endif -->
		</td>
	</tr>
	<!-- <tr>
		<td>
			<b>Contact number: </b>+6{{ $data['admin']->phone }}
		</td>
	</tr>
	<tr>
		<td>
			<b>Address: </b>{{ $data['web_setting']->address }}
		</td>
	</tr> -->
</table>
<hr>
<table width="100%">
	<tr class="row">
		<td class="col-4"></td>
		<td class="col-4" style="text-align: center;"><h4><b>{{ isset($data['backendlang']['backendlang']['Invoice']) ? $data['backendlang']['backendlang']['Invoice'] :'' }}</b></h4></td>
	</tr>
	<tr class="form-group row">
		<td class="col-4" style="border: 1px solid #000;">
			<br>
			@if($transaction->different_billing_address == 1 && !empty($diff_billing_address->id))
				<h5>{{ isset($data['backendlang']['backendlang']['Bill_To']) ? $data['backendlang']['backendlang']['Bill_To'] :'' }}:</h5>
				<h5>{{ $diff_billing_address->address_name }}</h5>
				@if(!empty($company_name) && !empty($company_registration_no))
					<h5>{{ $company_name }} ({{ $company_registration_no }})</h5>
				@elseif(!empty($company_name))
					<h5>{{ $company_name }}</h5>
				@elseif(!empty($company_registration_no))
					<h5>({{ $company_registration_no }})</h5>
				@endif
				<h5>{{ $diff_billing_address->address }}</h5>
				<h5>{{ $diff_billing_address->city }}</h5>
				<h5>{{ $diff_billing_address->postcode }}, {{ $diff_billing_address->state }}</h5>
				<br>
				<h5>{{ isset($data['backendlang']['backendlang']['Tel']) ? $data['backendlang']['backendlang']['Tel'] :'' }}: +{{ $diff_billing_address->country_code }}{{ ($diff_billing_address->phone[0] == 0) ? substr($diff_billing_address->phone, 1) : $diff_billing_address->phone }}</h5>
			@else
				<h5>{{ isset($data['backendlang']['backendlang']['Bill_To']) ? $data['backendlang']['backendlang']['Bill_To'] :'' }}:</h5>
				<h5>{{ $transaction->address_name }}</h5>
				<h5>{{ $transaction->address }}</h5>
				<h5>{{ $transaction->city }}</h5>
				<h5>{{ $transaction->postcode }}, {{ !empty($delivery_state->name) ? $delivery_state->name : $transaction->state }}</h5>
				<h5>{{ !empty($delivery_country->country_name) ? $delivery_country->country_name : $transaction->country }}</h5>
				<br>
				<h5>{{ isset($data['backendlang']['backendlang']['Tel']) ? $data['backendlang']['backendlang']['Tel'] :'' }}: +{{ $transaction->country_code }}{{ ($transaction->phone[0] == 0) ? substr($transaction->phone, 1) : $transaction->phone }}</h5>
			@endif
			<br>
		</td>
		<td class="col-4"></td>
		<td class="col-4" align="right">
			<h5><b>{{ isset($data['backendlang']['backendlang']['Transaction_No']) ? $data['backendlang']['backendlang']['Transaction_No'] :'' }}: {{ $transaction->transaction_no }}</b></h5>
			<br>
			<h5>{{ isset($data['backendlang']['backendlang']['Date']) ? $data['backendlang']['backendlang']['Date'] :'' }}: {{ date('d/m/Y', strtotime($transaction->created_at)) }}</h5>
			<br>
			<h5>
				{{ isset($data['backendlang']['backendlang']['Delivery_Method']) ? $data['backendlang']['backendlang']['Delivery_Method'] :'' }}:
				@if($transaction->delivery_method == '3')
					Shopee
				@elseif($transaction->delivery_method == '2')
					{{ isset($data['backendlang']['backendlang']['Self_Pick_Up']) ? $data['backendlang']['backendlang']['Self_Pick_Up'] :'' }}
	            @elseif($transaction->delivery_method == '4')
	               	{{ isset($data['backendlang']['backendlang']['J&T_Standard_Delivery']) ? $data['backendlang']['backendlang']['J&T_Standard_Delivery'] :'' }}
	            @elseif($transaction->delivery_method == '5')
	                {{ isset($data['backendlang']['backendlang']['J&T_Next_Day_Delivery']) ? $data['backendlang']['backendlang']['J&T_Next_Day_Delivery'] :'' }}
				@else
					{{ isset($data['backendlang']['backendlang']['courier_service']) ? $data['backendlang']['backendlang']['courier_service'] :'' }}
				@endif
			</h5>
		</td>
	</tr>
	<tr class="form-group row">
		<td class="col-4" style="border: 1px solid #000;">
			<br>
			@if(!empty($transaction->cod_address))
				<h5>	{{ isset($data['backendlang']['backendlang']['Pickup_Address']) ? $data['backendlang']['backendlang']['Pickup_Address'] :'' }}: {{ $cod_address->address }}</h5>
			@else
				<h5>{{ isset($data['backendlang']['backendlang']['Ship_To']) ? $data['backendlang']['backendlang']['Ship_To'] :'' }}:</h5>
				<h5>{{ $transaction->address_name }}</h5>
				<h5>{{ $transaction->address }}</h5>
				<h5>{{ $transaction->city }}</h5>
				<h5>{{ $transaction->postcode }}, {{ !empty($delivery_state->name) ? $delivery_state->name : $transaction->state }}</h5>
				<h5>{{ !empty($delivery_country->country_name) ? $delivery_country->country_name : $transaction->country }}</h5>
				<br>
				<h5>{{ isset($data['backendlang']['backendlang']['Tel']) ? $data['backendlang']['backendlang']['Tel'] :'' }}: +{{ $transaction->country_code }}{{ ($transaction->phone[0] == 0) ? substr($transaction->phone, 1) : $transaction->phone }}</h5>
			@endif
			<br>
		</td>
		<td class="col-4"></td>
		<td class="col-4"></td>
	</tr>
</table>
<hr>
<table class="table">
	<tr>
		<td>{{ isset($data['backendlang']['backendlang']['No']) ? $data['backendlang']['backendlang']['No'] :'' }}</td>
		<td>{{ isset($data['backendlang']['backendlang']['Item_Code']) ? $data['backendlang']['backendlang']['Item_Code'] :'' }}</td>
		<td>{{ isset($data['backendlang']['backendlang']['Description']) ? $data['backendlang']['backendlang']['Description'] :'' }}</td>
		<td>{{ isset($data['backendlang']['backendlang']['Quantity']) ? $data['backendlang']['backendlang']['Quantity'] :'' }}</td>
		<td>{{ isset($data['backendlang']['backendlang']['UOM']) ? $data['backendlang']['backendlang']['UOM'] :'' }}</td>
		<td>{{ isset($data['backendlang']['backendlang']['Price']) ? $data['backendlang']['backendlang']['Price'] :'' }} RM</td>
		<td>{{ isset($data['backendlang']['backendlang']['Total']) ? $data['backendlang']['backendlang']['Total'] :'' }} RM</td>
		<!-- <td>Particulars</td>
		<td align="right">Unit Price</td>
		<td align="right">Qty</td>
		<td align="center">Unit</td>
		<td align="right">Amount (MYR)</td> -->
	</tr>
	@php
	$sub_total = 0;
	@endphp
	@foreach($details as $key => $details)
	<tr>
		<td>{{ $key+1 }}</td>
		<td>{{ $details->product_code }}</td>
		<td>
			{{ $details->product_name }}<br>
			{!! ($details->sub_category != '') ? "Option: ".$details->sub_category."<br>" : '' !!}
			{!! ($details->second_sub_category != '') ? "Second Option: ".$details->second_sub_category."<br>" : '' !!}
		</td>
		<td>{{ $details->t_qty }}</td>
		<td>{{ $details->product_uom }}</td>
		<td>{{ $details->unit_price }}</td>
		<td>{{ number_format(($details->unit_price) * $details->t_qty, 2) }}</td>
	</tr>
	@php
	$sub_total += $details->unit_price * $details->t_qty;
	@endphp
	@endforeach
	<tr>
		<td colspan="5"></td>
		<td align="right">
			{{ isset($data['backendlang']['backendlang']['Sub_Total']) ? $data['backendlang']['backendlang']['Sub_Total'] :'' }}:
		</td>
		<td>
			{{ number_format($sub_total, 2) }}
		</td>
	</tr>
	<tr>
		<td colspan="5"></td>
		<td align="right">
			{{ isset($data['backendlang']['backendlang']['Shipping_Fee']) ? $data['backendlang']['backendlang']['Shipping_Fee'] :'' }}:
		</td>
		<td>
			{{ number_format($transaction->shipping_fee, 2) }}
		</td>
	</tr>
	@if(!empty($transaction->ad_discount))
	<tr>
		<td colspan="5"></td>
		<td align="right">
			{{ isset($data['backendlang']['backendlang']['Agent_Discount']) ? $data['backendlang']['backendlang']['Agent_Discount'] :'' }}:
		</td>
		<td>
			{{ number_format($transaction->ad_discount, 2) }}
		</td>
	</tr>
	@endif

	<tr>
		<td colspan="5"></td>
		<td align="right">
			{{ isset($data['backendlang']['backendlang']['Discount']) ? $data['backendlang']['backendlang']['Discount'] :'' }}:
		</td>
		<td>
			{{ !empty($transaction->discount) && $transaction->discount > 0 ? '(-)' : '' }} {{ number_format($transaction->discount, 2) }}
		</td>
	</tr>

	<tr>
		<td colspan="5"></td>
		<td align="right">
			{{ isset($data['backendlang']['backendlang']['Processing_Fee']) ? $data['backendlang']['backendlang']['Processing_Fee'] :'' }}:
		</td>
		<td>
			{{ number_format($transaction->processing_fee, 2) }}
		</td>
	</tr>
	<tr>
		<td colspan="5">
			@if(!empty($transaction->remark))
				Remark: {{ $transaction->remark }}
			@endif
		</td>
		<td align="right">
			{{ isset($data['backendlang']['backendlang']['Grand_Total']) ? $data['backendlang']['backendlang']['Grand_Total'] :'' }}:
		</td>
		<td>
			{{ number_format($sub_total + $transaction->shipping_fee - $transaction->discount - $transaction->ad_discount + $transaction->processing_fee, 2) }}
		</td>
	</tr>
</table>
<tr>
	<td>
	{!! $data['web_setting']->tnc_description !!}
	</td>
</tr>
@endsection

@section('js')
<script type="text/javascript">
	$('.print-window').click(function() {
	    window.print();
	});
	$(document).ready(function(){
		$('.print-window').click();
	});
</script>
@endsection