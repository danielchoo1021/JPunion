<style type="text/css">
	/*td{
		display: table-cell;
		vertical-align: inherit;
	}

	table{
		border-collapse: collapse;
	}

	table{
		text-indent: initial;
		border-spacing: 2px;
	}

	h1, h2, h3, h4, h5, h6, b, div{
		color: #000;
	}

	body{
		font-family: "PT Serif", serif;
		font-size: 14px;
		font-weight: 400;
		line-height: 1.5;
	}

	*{
		margin: 0;
		padding: 0;
		box-sizing: border-box;
	}

	tr{
		display: table-row;
		vertical-align: inherit;
		border-color: inherit;
	}

	tbody{
		display: table-row-group;
		vertical-align: middle;
		border-color: inherit;
	}

	div{
		display: block;
	}

	h3, .h3{
		font-size: 2rem;
		display: block;
	}

	h4, .h4{
		font-size: 1.4rem;
		display: block;
	}

	h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6{
		margin-bottom: 0.5rem;
		font-family: "PT Serif", serif;
		font-weight: 700;
		line-height: 1.2;
		margin-top: 0;
	}

	hr {
    	border-top: 1px solid rgba(0, 0, 0, 0.1);
    	margin-top: 1rem;
    	margin-bottom: 1rem;
    	border: 0;
    	box-sizing: content-box;
    	height: 0;
    	overflow: visible;
    	display: block;
    	unicode-bidi: isolate;
	}

	.table-bordered {
    	border: 1px solid #dee2e6 !important;
	}

	.table{
		width: 100%;
		max-width: 100%;
		background-color: transparent;
	}

	.table-bordered td {
    	border: 1px solid #dee2e6;
    	border-right: 1px solid #dee2e6;
    	border-left: 1px solid #dee2e6;
    	padding: .5rem;
	}

	.table-bordered th {
		border: 1px solid #dee2e6;
		border-right: 1px solid #dee2e6;
		border-left: 1px solid #dee2e6;
		padding: .5rem;
	}

	.table td, .table th {
    	
    	vertical-align: top;
    	border-top: 1px solid #dee2e6;
	}

	img{
		vertical-align: middle;
		border-style: none;
	}

	@media (min-width: 1200px){
		.container {
	    	max-width: 1140px;
		}
	}

	@media (min-width: 992px){
		.container {
		    max-width: 960px;
		}
	}

	@media (min-width: 576px){
		.container {
		    max-width: 540px;
		}
	}

	.container {
	    width: 100%;
	    padding-right: 15px;
	    padding-left: 15px;
	    margin-right: auto;
	    margin-left: auto;
	}*/





	td, th{
		padding: 0;

	}

	.table-bordered, td, th{
		border-radius: 0;
	}

	*{
		box-sizing: border-box;
	}

	td{
		display: table-cell;
		vertical-align: inherit;
	}

	h3{
		font-size: 22px;
		display: block;
	}

	h1{
		font-size: 32px;
		margin: .67em 0;
		display: block;
	}

	h4{
		font-size: 18px;
	}

	.h4, .h5, .h6, h4, h5, h6 {
	    margin-top: 10px;
	    /*margin-bottom: 10px;*/
	}

	h1, h2, h3, h4, h5, h6{
		font-weight: 400;
		font-family: "Open Sans","Helvetica Neue",Helvetica,Arial,sans-serif;
	}

	.h1, .h2, .h3, h1, h2, h3 {
	    margin-top: 20px;
	    /*margin-bottom: 10px;*/
	}

	.h1, .h2, .h3, .h4, .h5, .h6, h1, h2, h3, h4, h5, h6 {
	    line-height: 1.1;
	    color: inherit;
	}

	table{
		border-collapse: collapse;
		border-spacing: 0;
		text-indent: initial;
		background-color: transparent;
		width: 100%;
		display: table;
		max-width: 100%;
		/*margin-bottom: 20px;*/
	}

	.main-content{
		margin-left: 0;
		padding: 0;
	}

	.main-content, body, html{
		min-height: 100%;
	}

	div{
		display: block;
	}

	.main-content-inner{
		float: left;
		width: 100%;
	}

	.page-content{
		padding: 8px 20px 80px 24px;
	}

	.page-content{
		background-color: #FFF;
		position: relative;
		margin: 0;
	}

	tbody{
		display: table-row-group;
		vertical-align: middle;
		border-color: inherit;
	}

	tr{
		display: table-row;
		vertical-align: inherit;
		border-color: inherit;
	}

	.table-bordered, .table-bordered>tbody>tr>td, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>td, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>thead>tr>th {
	    border: 1px solid #ddd;
	}

	.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
	    padding: 8px;
	    line-height: 1.42857143;
	    vertical-align: top;
	    border-top: 1px solid #ddd;
	}

	body {
		font-family: 'Open Sans' !important;
	    font-size: 13px;
	    color: #393939;
	    line-height: 1.5;
	}

	b, optgroup, strong{
		font-weight: 700;
	}

</style>
<div class="main-content">
	<table width="100%">
		<tr>
			<td>
				<!-- @if(!empty($data['admin']->ecommerce_logo))
				<img src="{{ asset($data['admin']->ecommerce_logo) }}" style="width: 100px;">
				<br>
				<br>
				<br>
				@endif -->
				<!-- @if(!empty($logo))
				<img src="data:image/png;base64,{{ $logo }}">
				<br>
				<br>
				<br>
				@endif -->
				<h3>{{ $data['admin']->website_name }} Sdn Bhd <!-- <span style="font-size: 13px;">({{ $data['web_setting']->company_registration_no }})</span> --></h3>
			</td>
			<td align="right">
				@if($transaction->mall == 1)
				<h1>Redemption Receipt</h1>
				@else
				<!-- <h3 style="font-family: normal !important;"> --><h1>Invoice</h1>
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
				<b>Address: </b>
				<!-- <br>
				<span style="white-space: pre-wrap;"> -->{{ $data['web_setting']->address }}<!-- </span> -->
			</td>
		</tr>
	</table>

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
	                <b>Cash Wallet</b> <br>
	            @elseif($transaction->mall == 2)
	                <b>Topup Wallet</b> <br>
	            @elseif(!empty($transaction->bank_id))
	                <b>Online Banking</b> <br>
	            @elseif(!empty($transaction->bank_slip))
	                <b>Bank Transfer</b> <br>
	            @elseif(!empty($transaction->pv_purchase))
	                <b>Point Wallet</b> <br>
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
					Pickup Address: {{ $setting_pickup->address }}<br>
					Pickup Postcode: {{ $setting_pickup->postcode }}<br>
					Pickup City: {{ $setting_pickup->city }}<br>
					Pickup State: {{ $pickup_state->name }}
				@else

					Delivery Address: {{ $transaction->address }}<br>
					Delivery Postcode: {{ $transaction->postcode }}<br>
					Delivery City: {{ $transaction->city }}<br>
					Delivery State: {{ !empty($delivery_state->name) ? $delivery_state->name : $transaction->state }}<br>
					Delivery Country: {{ !empty($delivery_country->country_name) ? $delivery_country->country_name : $transaction->country }}
				@endif
			</td>
		</tr>
	</table>
	<hr>
	<table class="table table-bordered">
		<tr>
			<td>Particulars</td>
			<td align="right">Unit Price &nbsp;</td>
			<td align="right">Qty &nbsp;</td>
			<td align="right">Unit &nbsp;</td>
			<td align="right">Amount (MYR) &nbsp;</td>
		</tr>
		@php
		$sub_total = 0;
		@endphp
		@foreach($details as $detail)
		<tr>
			<td>
				{{ $detail->product_name }}<br>
				{!! ($detail->sub_category != '') ? "Option: ".$detail->sub_category."<br>" : '' !!}
				{!! ($detail->second_sub_category != '') ? "Second Option: ".$detail->second_sub_category."<br>" : '' !!}
			</td>
			<td align="right">
				{{ number_format($detail->unit_price, 2) }}
			</td>
			<td align="right">
				{{ $detail->t_qty }}
			</td>
			<td align="right">
				@if($detail->packages == '1')
					PKG
				@else
					{{ $detail->uom_en }}
				@endif
			</td>
			<td align="right">
				{{ number_format(($detail->unit_price) * $detail->t_qty, 2) }}
			</td>
		</tr>
		@php
		$sub_total += $detail->unit_price * $detail->t_qty;
		@endphp
		@endforeach
		<tr>
			<td colspan="4" align="right">
				Sub Total: &nbsp;
			</td>
			<td colspan="4" align="right">
				{{ number_format($sub_total, 2) }}
			</td>
		</tr>
		<tr>
			<td colspan="4" align="right">
				Shipping Fee: &nbsp;
			</td>
			<td colspan="4" align="right">
				{{ number_format($transaction->shipping_fee, 2) }}
			</td>
		</tr>
		@if(!empty($transaction->ad_discount))
		<tr>
			<td colspan="4" align="right">
				Agent Discount: &nbsp;
			</td>
			<td colspan="4" align="right">
				{{ number_format($transaction->ad_discount, 2) }}
			</td>
		</tr>
		@endif

		<tr>
			<td colspan="4" align="right">
				Discount: &nbsp;
			</td>
			<td colspan="4" align="right">
				{{ !empty($transaction->discount) && $transaction->discount > 0 ? '(-)' : '' }} {{ number_format($transaction->discount, 2) }}
			</td>
		</tr>

		<tr>
			<td colspan="4" align="right">
				Processing Fee: &nbsp;
			</td>
			<td colspan="4" align="right">
				{{ number_format($transaction->processing_fee, 2) }}
			</td>
		</tr>
		<tr>
			<td colspan="4" align="right">
				Grand Total: &nbsp;
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
</div>