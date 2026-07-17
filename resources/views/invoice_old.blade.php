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
		font-size: 18px;
		display: block;
	}

	h1{
		font-size: 32px;
		margin: .67em 0;
		display: block;
	}

	h4{
		font-size: 14px;
	}

	.h4, .h5, .h6, h4, h5, h6 {
	    margin-top: 0;
	    margin-bottom: 0;
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
	    font-size: 12px;
	    color: #393939;
	    line-height: 1.5;
	}

	b, optgroup, strong{
		font-weight: 700;
	}

	.col, .col-2, .col-3, .col-4, .col-5, .col-6 {
		position: relative;
	    width: 100%;
	    min-height: 1px;
	    padding-right: 15px;
	    padding-left: 15px;
	}

	.col-4 {
		flex: 0 0 33.333333%;
    	max-width: 33.333333%;
	}

	.cn {
		font-family: simhei !important;
	}

	@font-face {
	    font-family: 'CursiveItalic';
	    src: url({{ storage_path('fonts\brush_script_mt_kursiv.ttf') }}) format("truetype");
	    font-weight: 400;
	    font-style: normal;
	}

	.col-5 {
		flex: 0 0 41.66667%;
		max-width: 41.66667%; 
	}

	.col-3 {
		  flex: 0 0 25%;
		  max-width: 25%; 
	}

	.col-2 {
		  flex: 0 0 16.66667%;
		  max-width: 16.66667%; 
	}

	.col-6 {
		  flex: 0 0 50%;
		  max-width: 50%; 
	}

	.col {
		  flex-basis: 0;
		  flex-grow: 1;
		  max-width: 100%; 
	}

</style>
<div class="main-content">
	<table width="100%" class="table">
		<tr class="row">
			<td align="right" style="width: 20%;">
				@if(!empty($data['admin']->profile_logo))
				<img src="{{ asset($data['website_logo']) }}" style="width: 100px;">
				@endif
			</td>
			<td style="text-align: center;" style="width: 60%;">
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
				<h4>TEL: {{ $data['web_setting']->company_phone }}</h4>
			</td>
			<td align="right" style="width: 20%;">
			</td>
		</tr>
	</table>
	<hr>
	<table width="100%" class="table">
		<tr class="row">
			<td class="col-4"></td>
			<td class="col-4" style="text-align: center;"><h4><b>INVOICE</b></h4></td>
		</tr>
		<tr class="form-group row">
			<td class="col-4" style="border: 1px solid #000;">
				<br>
				@if($transaction->different_billing_address == 1 && !empty($diff_billing_address->id))
					<h5>Bill To:</h5>
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
					<h5>Tel: +{{ $diff_billing_address->country_code }}{{ ($diff_billing_address->phone[0] == 0) ? substr($diff_billing_address->phone, 1) : $diff_billing_address->phone }}</h5>
				@else
					<h5>Bill To:</h5>
					<h5>{{ $transaction->address_name }}</h5>
					<h5>{{ $transaction->address }}</h5>
					<h5>{{ $transaction->city }}</h5>
					<h5>{{ $transaction->postcode }}, {{ !empty($delivery_state->name) ? $delivery_state->name : $transaction->state }}</h5>
					<h5>{{ !empty($delivery_country->country_name) ? $delivery_country->country_name : $transaction->country }}</h5>
					<br>
					<h5>Tel: +{{ $transaction->country_code }}{{ ($transaction->phone[0] == 0) ? substr($transaction->phone, 1) : $transaction->phone }}</h5>
				@endif
				<br>
			</td>
			<td class="col-4"></td>
			<td class="col-4" align="right">
				<h5><b>Transaction No: {{ $transaction->transaction_no }}</b></h5>
				<br>
				<h5>Date: {{ date('d/m/Y', strtotime($transaction->created_at)) }}</h5>
				<br>
				<h5>
					Delivery Method:
					@if($transaction->delivery_method == '3')
						Shopee
					@elseif($transaction->delivery_method == '2')
						Self Pick Up
		            @elseif($transaction->delivery_method == '4')
		                J&T Standard Delivery
		            @elseif($transaction->delivery_method == '5')
		                J&T Next Day Delivery
					@else
						Courier Service
					@endif
				</h5>
			</td>
		</tr>
		<tr class="form-group row">
			<td class="col-4" style="border: 1px solid #000;">
				<br>
				@if(!empty($transaction->cod_address))
					<h5>Pickup Address: {{ $cod_address->address }}</h5>
				@else
					<h5>Ship To:</h5>
					<h5>{{ $transaction->address_name }}</h5>
					<h5>{{ $transaction->address }}</h5>
					<h5>{{ $transaction->city }}</h5>
					<h5>{{ $transaction->postcode }}, {{ !empty($delivery_state->name) ? $delivery_state->name : $transaction->state }}</h5>
					<h5>{{ !empty($delivery_country->country_name) ? $delivery_country->country_name : $transaction->country }}</h5>
					<br>
					<h5>Tel: +{{ $transaction->country_code }}{{ ($transaction->phone[0] == 0) ? substr($transaction->phone, 1) : $transaction->phone }}</h5>
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
			<td>No.</td>
			<td>Item Code</td>
			<td>Description</td>
			<td>Qty</td>
			<td>UOM</td>
			<td>U/Price {{ !empty($transaction->pv_purchase) ? 'Point' : 'RM' }}</td>
			<td>Total {{ !empty($transaction->pv_purchase) ? 'Point' : 'RM' }}</td>
		</tr>
		@php
		$sub_total = 0;
		@endphp
		@foreach($details as $key => $details)
		<tr>
			<td>{{ $key+1 }}</td>
			<td>{{ $details->product_code }}</td>
			<td class="cn">
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
				Sub Total:
			</td>
			<td>
				{{ number_format($sub_total, 2) }}
			</td>
		</tr>
		<tr>
			<td colspan="5"></td>
			<td align="right">
				Shipping Fee:
			</td>
			<td>
				{{ number_format($transaction->shipping_fee, 2) }}
			</td>
		</tr>
		@if(!empty($transaction->ad_discount))
		<tr>
			<td colspan="5"></td>
			<td align="right">
				Agent Discount:
			</td>
			<td>
				{{ number_format($transaction->ad_discount, 2) }}
			</td>
		</tr>
		@endif

		<tr>
			<td colspan="5"></td>
			<td align="right">
				Discount:
			</td>
			<td>
				{{ !empty($transaction->discount) && $transaction->discount > 0 ? '(-)' : '' }} {{ number_format($transaction->discount, 2) }}
			</td>
		</tr>

		<tr>
			<td colspan="5"></td>
			<td align="right">
				Processing Fee:
			</td>
			<td>
				{{ number_format($transaction->processing_fee, 2) }}
			</td>
		</tr>
		<tr>
			<td colspan="5">
				@if(!empty($transaction->remark))
					Remark: <span class="cn">{{ $transaction->remark }}</span>
				@endif
			</td>
			<td align="right">
				Grand Total:
			</td>
			<td>
				{{ number_format($sub_total + $transaction->shipping_fee - $transaction->discount - $transaction->ad_discount + $transaction->processing_fee, 2) }}
			</td>
		</tr>
		</table>
	<div class="holder">
				{!! $data['web_setting']->invoice_notes !!}
    		</div>
</div>