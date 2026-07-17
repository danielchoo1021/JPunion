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

	h1, h2, h6, h6, h6, h6, b, div{
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

	h6, .h6{
		font-size: 2rem;
		display: block;
	}

	h6, .h6{
		font-size: 1.4rem;
		display: block;
	}

	h1, h2, h6, h6, h6, h6, .h1, .h2, .h6, .h6, .h6, .h6{
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

	h1{
		font-size: 32px;
		margin: .67em 0;
		display: block;
	}

	h6{
		font-size: 17px;
	}

	.h6, .h6, .h6, h6, h6, h6 {
	    margin-top: 0;
	    margin-bottom: 0;
	}

	.h1, .h2, .h6, h1, h2, h6 {
	    margin-top: 10px;
	    /*margin-bottom: 10px;*/
	}

	.h1, .h2, .h6, .h6, .h6, .h6, h1, h2, h6, h6, h6, h6 {
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
		margin: 0px;
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

	.col, .col-1, .col-2, .col-3, .col-4, .col-5, .col-6 {
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

	.col {
		flex-basis: 0;
		flex-grow: 1;
		max-width: 100%; 
	}

	.col-1 {
		flex: 0 0 2.333333%;
		max-width: 2.333333%;
	}


	.col-2 {
		flex: 0 0 16.66667%;
		max-width: 16.66667%; 
	}

	.col-3 {
		  flex: 0 0 25%;
		  max-width: 25%; 
	}

	.col-4 {
		flex: 0 0 33.333333%;
		max-width: 33.333333%;
	}


	.col-5 {
		flex: 0 0 41.66667%;
		max-width: 41.66667%; 
	}

	.col-6 {
		flex: 0 0 50%;
		max-width: 50%; 
	}

	/* .page-end-border {
		border-bottom: 15px solid #034424;
		position: absolute;
		bottom: 0;
		left: 0;
		width: 100%;
	} */


	.address-arrows-top {
		position: relative;
		padding: 15px 20px;
		margin-bottom: 20px;
	}

	.address-arrows-top::before {
		content: '';
		position: absolute;
		top: 0;
		left: 0;
		width: 25px;
		height: 25px;
		border: 1px solid #000000;
		border-right: none;
		border-bottom: none;
	}

	.address-arrows-top .address-tr {
		content: '';
		position: absolute;
		top: 0;
		right: 0;
		width: 25px;
		height: 25px;
		border: 1px solid #000000;
		border-left: none;
		border-bottom: none;
	}

	.address-arrows-middle {
		position: relative; 
		padding: 15px 20px; 
		margin-top: -20px;
	}

	.address-arrows-bottom {
		position: relative;
		margin-top: -20px;
	}

	.address-arrows-bottom::before {
		content: '';
		position: absolute;
		top: 0;
		left: 0;
		width: 25px;
		height: 25px;
		border: 1px solid #000000;
		border-right: none;
		border-top: none;
	}

	.address-arrows-bottom .address-br {
		content: '';
		position: absolute;
		top: 0;
		right: 0;
		width: 25px;
		height: 25px;
		border: 1px solid #000000;
		border-left: none;
		border-top: none;
	}

	h1, h2, h3, h4, h5, h6{
		font-size: 13px;
		font-weight: 400;
		font-family: "Open Sans","Helvetica Neue",Helvetica,Arial,sans-serif;
	}

	@media print {
		.page-number::before {
			content: counter(page) " of 1";
		}
    }

    .page-number::before {
      	content: "1 of 1";
    }
</style>
<div class="main-content">
	<div class="print-header">
		<div style="text-align: center;">
			<table class="table" style="width: auto; margin: auto;">
				<tr class="row">
					<td style="text-align: left; vertical-align: middle; padding-right: 20px;">
						@if(!empty($data['website_setting']->website_logo))
							<img src="{{ asset($data['website_logo']) }}" style="width: 70px;">
						@endif
					</td>
					<td style="text-align: left; vertical-align: middle;">
						<h3 style="margin: 5px 0;">
							<span style="font-weight: 600;">
								{{ !empty($data['web_setting']->invoice_name) ? $data['web_setting']->invoice_name : $data['admin']->company_name }}
							</span>
							@if(!empty($data['web_setting']->company_registration_no))
								<span>
									{{ $data['web_setting']->company_registration_no }}
								</span>
							@endif
						</h3>
						@if(!empty($data['web_setting']->company_address))
							<h6 style="margin: 5px 0; width: 350px; line-height: 1.4;">
								<span style="font-weight: 600;">
									{{isset($data['backendlang']['backendlang']['Office']) ? $data['backendlang']['backendlang']['Office'] :'Office' }}:
								</span>
								{{ $data['web_setting']->company_address }}
							</h6>
						@endif
						@if(!empty($data['web_setting']->contact_email))
							<h6 style="margin: 5px 0;">
								{{ isset($data['backendlang']['backendlang']['Email']) ? $data['backendlang']['backendlang']['Email'] : 'Email' }} : {{ $data['web_setting']->contact_email }}
							</h6>
						@endif
						<h6 style="margin: 5px 0;">
							@if(!empty($data['web_setting']->company_phone))
								{{ isset($data['backendlang']['backendlang']['TEL']) ? $data['backendlang']['backendlang']['TEL'] : 'TEL' }} : {{ $data['web_setting']->company_phone }}
								&nbsp;
								&nbsp;
								&nbsp;
							@endif
							@if(!empty($data['web_setting']->tin_no))
								({{ isset($data['backendlang']['backendlang']['Tin_No']) ? $data['backendlang']['backendlang']['Tin_No'] : 'TIN No' }} : {{ $data['web_setting']->tin_no }})
							@endif
						</h6>
					</td>
				</tr>
			</table>
		</div>

		<hr style="margin-bottom: 15px; border-top: 1px solid #000000;">

		<table class="table" style="width: 98%; margin: auto;">
			<tr>
				<td width="50%" style="padding: 0px;">
					@if(empty($transaction->payment_method))
						<div class="address-arrows-top">
							<div class="address-tr">

							</div>
							<div>
								@if($transaction->different_billing_address == 1 && !empty($bill_address->id))
									<h6 style="margin-top: 0px; margin-bottom: 0px; font-weight: 800;">{{ isset($data['backendlang']['backendlang']['BILL_TO']) ? $data['backendlang']['backendlang']['BILL_TO'] : 'BILL TO' }}:</h6>
									<h6 style="margin-top: 5px; margin-bottom: 0px;">{{ $bill_address->address_name }}</h6>
									@if(!empty($company_name) && !empty($company_registration_no))
										<h6 style="margin-top: 5px; margin-bottom: 0px;">{{ $company_name }} ({{ $company_registration_no }})</h6>
									@elseif(!empty($company_name))
										<h6 style="margin-top: 5px; margin-bottom: 0px;">{{ $company_name }}</h6>
									@elseif(!empty($company_registration_no))
										<h6 style="margin-top: 0px; margin-bottom: 0px;">({{ $company_registration_no }})</h6>
									@endif
									<h6 style="margin-top: 5px; margin-bottom: 0px;">{{ !empty($bill_address->address) ? $bill_address->address : "" }}</h6>
									<h6 style="margin-top: 5px; margin-bottom: 0px;">{{ !empty($bill_address->address_2) ? $bill_address->address_2 : "" }}</h6>
									<h6 style="margin-top: 5px; margin-bottom: 0px;">{{ !empty($bill_address->address_3) ? $bill_address->address_3 : "" }}</h6>
									<h6 style="margin-top: 5px; margin-bottom: 0px;">
										{{ $bill_address->city }}, 
										{{ $bill_address->postcode }}, 
										{{ $bill_address->state }}, 
										{{ !empty($bill_address->country_name) ? $bill_address->country_name : $transaction->country }}
									</h6>
									<br>
									<h6>
										<b>{{ isset($data['backendlang']['backendlang']['TEL']) ? $data['backendlang']['backendlang']['TEL'] : 'TEL' }}:</b> 
										@if(!empty($bill_address->country_code))
										+{{ $bill_address->country_code }}
										@endif
										@if($bill_address->country_code && $bill_address->country_code == '60')
											{{ ($bill_address->phone[0] == 0) ? $bill_address->phone : '0'.$bill_address->phone }}
										@else
											{{ ($bill_address->phone[0] == 0) ? substr($bill_address->phone, 1) : $bill_address->phone }}
										@endif
									</h6>
								@else
									<h6 style="margin-top: 0px; margin-bottom: 0px; font-weight: 800;">{{ isset($data['backendlang']['backendlang']['BILL_TO']) ? $data['backendlang']['backendlang']['BILL_TO'] : 'BILL TO' }}:</h6>
									<h6 style="margin-top: 5px; margin-bottom: 0px;">{{ $transaction->address_name }}</h6>
									<h6 style="margin-top: 5px; margin-bottom: 0px;">{{ !empty($transaction->address) ? $transaction->address : "" }}</h6>
									<h6 style="margin-top: 5px; margin-bottom: 0px;">{{ !empty($transaction->address_2) ? $transaction->address_2 : "" }}</h6>
									<h6 style="margin-top: 5px; margin-bottom: 0px;">{{ !empty($transaction->address_3) ? $transaction->address_3 : "" }}</h6>
									<h6 style="margin-top: 5px; margin-bottom: 0px;">
										{{ $transaction->city }}, 
										{{ $transaction->postcode }}, 
										{{ !empty($delivery_state->name) ? $delivery_state->name : $transaction->state }}, 
										{{ !empty($delivery_country->country_name) ? $delivery_country->country_name : $transaction->country }}
									</h6>
									<br>
									<h6 style="margin-top: 5px; margin-bottom: 0px;">
										<b>{{ isset($data['backendlang']['backendlang']['TEL']) ? $data['backendlang']['backendlang']['TEL'] : 'TEL' }}:</b> 
										@if(!empty($transaction->country_code))
										+{{ $transaction->country_code }}
										@endif
										@if($transaction->country_code && $transaction->country_code == '60')
											{{ ($transaction->phone[0] == 0) ? $transaction->phone : '0'.$transaction->phone }}
										@else
											{{ ($transaction->phone[0] == 0) ? substr($transaction->phone, 1) : $transaction->phone }}
										@endif
									</h6>
								@endif
							</div>
						</div>
					@endif
				</td>

				<td width="50%" style="position: relative;">
					<div style="position: absolute; right: 5px;">
						<table style="margin: 10px 0;">
							<tr>
								<td style="width: 150px;">
									<h6 style="margin-top: 5px; margin-bottom: 0px; font-weight: 600;">
										{{ !empty($transaction->pv_purchase) ? ($data['backendlang']['backendlang']['Point_Invoice'] ?? 'Point Invoice') : ($data['backendlang']['backendlang']['Invoice'] ?? 'Invoice')  }}
									</h6>
								</td>
								<td style="width: 8px;">
									<h6 style="margin-top: 5px; margin-bottom: 0px; font-weight: 600;">
										:
									</h6>
								</td>
								<td style="width: 150px;">
									<h6 style="margin-top: 5px; margin-bottom: 0px; font-weight: 600;">
										@if(!empty($transaction->pv_purchase))
											PIV-{{ substr($transaction->transaction_no, 0, 8) . substr($transaction->transaction_no, 12) }}
										@else
											{{ $transaction->transaction_no }}
										@endif
									</h6>
								</td>
							</tr>
						</table>
						<table style="margin: 10px 0;">
							<tr>
								<td style="width: 150px;">
									<h6 style="margin-top: 5px; margin-bottom: 0px;">
										{{ isset($data['backendlang']['backendlang']['Purchased_By']) ? $data['backendlang']['backendlang']['Purchased_By'] : 'Purchased By' }}
									</h6>
								</td>
								<td style="width: 8px;">
									<h6 style="margin-top: 5px; margin-bottom: 0px; font-weight: 600;">
										:
									</h6>
								</td>
								<td style="width: 150px;">
									<h6 style="margin-top: 5px; margin-bottom: 0px;">
										{{ $transaction->address_name }} ({{ $transaction->user_id }})
									</h6>
								</td>
							</tr>
						</table>
						<table style="margin: 10px 0;">
							<tr>
								<td style="width: 150px;">
									<h6 style="margin-top: 5px; margin-bottom: 0px;">
										{{ isset($data['backendlang']['backendlang']['Payment_Method']) ? $data['backendlang']['backendlang']['Payment_Method'] : 'Payment Method' }}
									</h6>
								</td>
								<td style="width: 8px;">
									<h6 style="margin-top: 5px; margin-bottom: 0px; font-weight: 600;">
										:
									</h6>
								</td>
								<td style="width: 150px;">
									<h6 style="margin-top: 5px; margin-bottom: 0px;">
										@if($transaction->mall == 1)
											{{ $data['backendlang']['backendlang']['Cash_Wallet'] ?? 'Cash Wallet' }}
										@elseif($transaction->mall == 2)
											{{ $data['backendlang']['backendlang']['Topup_Wallet'] ?? 'Topup Wallet' }}
										@elseif(!empty($transaction->bank_id))
											{{ $data['backendlang']['backendlang']['Online_Banking'] ?? 'Online Banking' }}
										@elseif(!empty($transaction->bank_slip))
											@if(!empty($transaction->created_backend == 1))
												{{ $data['backendlang']['backendlang']['Bank_Transfer_Create_From_Backend'] ?? 'Bank Transfer (Create From Backend)' }}
											@else
												{{ $data['backendlang']['backendlang']['Bank_Transfer'] ?? 'Bank Transfer' }}
											@endif
										@elseif(!empty($transaction->pv_purchase))
											{{ $data['backendlang']['backendlang']['Point_Wallet'] ?? 'Point Wallet' }}
										@elseif($transaction->cod == 1)
											{{ $data['backendlang']['backendlang']['Cash_on_Delivery'] ?? 'Cash on Delivery' }}
										@elseif(!empty($transaction->payment_method == 1))
											{{ isset($data['backendlang']['backendlang']['POS_Cash']) ? $data['backendlang']['backendlang']['POS_Cash'] : 'POS_Cash' }}
										@elseif(!empty($transaction->payment_method == 2))
											{{ $data['backendlang']['backendlang']['POS_QR_Code'] ?? 'POS (QR code)' }}
										@elseif(!empty($transaction->payment_method == 3))
											{{ $data['backendlang']['backendlang']['POS_Credit_Card_Debit_Card'] ?? 'POS (Credit Card / Debit Card)' }}
										@elseif(!empty($transaction->created_backend == 1))
											{{ $data['backendlang']['backendlang']['Create_From_Backend_No_Bank_Slip'] ?? 'Create From Backend (No Bank Slip)' }}
										@endif
									</h6>
								</td>
							</tr>
						</table>
						<table style="margin: 10px 0;">
							<tr>
								<td style="width: 150px;">
									<h6 style="margin-top: 5px; margin-bottom: 0px;">
										{{ isset($data['backendlang']['backendlang']['Terms']) ? $data['backendlang']['backendlang']['Terms'] : 'Terms' }}
									</h6>
								</td>
								<td style="width: 8px;">
									<h6 style="margin-top: 5px; margin-bottom: 0px; font-weight: 600;">
										:
									</h6>
								</td>
								<td style="width: 150px;">
									<h6 style="margin-top: 5px; margin-bottom: 0px;">
										@if($transaction->delivery_method == '3')
											Shopee
										@elseif($transaction->delivery_method == '2')
											{{ $data['backendlang']['backendlang']['Self_Pick_Up'] ?? 'Self Pick Up' }}
										@elseif($transaction->delivery_method == '4')
											{{ $data['backendlang']['backendlang']['J&T_Standard_Delivery'] ?? 'J&T Standard Delivery' }}
										@elseif($transaction->delivery_method == '5')
											{{ $data['backendlang']['backendlang']['J&T_Next_Day_Delivery'] ?? 'J&T Next Day Delivery' }}
										@else
											@if($transaction->cod_address == '')
												@if(!empty($transaction->payment_method))
													{{ $data['backendlang']['backendlang']['Self_Pick_Up'] ?? 'Self Pick Up' }}
												@else
													{{ $data['backendlang']['backendlang']['courier_service'] ?? 'Courier Service' }}
												@endif
											@else
												{{ $data['backendlang']['backendlang']['Self_Pick_Up'] ?? 'Self Pick Up' }}
											@endif
										@endif	
									</h6>
								</td>
							</tr>
						</table>
						<table style="margin: 10px 0;">
							<tr>
								<td style="width: 150px;">
									<h6 style="margin-top: 5px; margin-bottom: 0px;">
										{{ $data['backendlang']['backendlang']['Date'] ?? 'Date' }}
									</h6>
								</td>
								<td style="width: 8px;">
									<h6 style="margin-top: 5px; margin-bottom: 0px; font-weight: 600;">
										:
									</h6>
								</td>
								<td style="width: 150px;">
									<h6 style="margin-top: 5px; margin-bottom: 0px;">
										{{ date('d/m/Y', strtotime($transaction->created_at)) }}
									</h6>
								</td>
							</tr>
						</table>
						{{-- <table style="margin: 10px 0;">
							<tr>
								<td style="width: 150px;">
									<h6 style="margin-top: 5px; margin-bottom: 0px;">
										{{ $data['backendlang']['backendlang']['Page'] ?? 'Page' }}
									</h6>
								</td>
								<td style="width: 10px;">
									<h6 style="margin-top: 5px; margin-bottom: 0px; font-weight: 600;">
										:
									</h6>
								</td>
								<td style="width: 150px;">
									<h6 style="margin-top: 5px; margin-bottom: 0px;">
										<span class="page-number"></span>
									</h6>
								</td>
							</tr>
						</table> --}}
					</div>
				</td>
			</tr>

			@if(empty($transaction->payment_method))
				<tr>
					<td width="50%" style="padding: 0px;">
						<div class="address-arrows-middle">
							@if(!empty($transaction->cod_address))
								<div>
									<h6 style="margin-top: 5px; margin-bottom: 0px; font-weight: 800;">{{ $data['backendlang']['backendlang']['pickup_address'] ?? 'Pickup Address' }}:</h6>
									<h6 style="margin-top: 5px; margin-bottom: 0px;">{{ $transaction->ca_address }}</h6>
									<h6 style="margin-top: 5px; margin-bottom: 0px;">{{ $transaction->ca_address_desc }}</h6>
								</div>
							@else
								<div>
									<h6 style="margin-top: 5px; margin-bottom: 0px; font-weight: 800;">{{ $data['backendlang']['backendlang']['SHIP_TO'] ?? 'SHIP TO' }}:</h6>
									<h6 style="margin-top: 5px; margin-bottom: 0px;">{{ $transaction->address_name }}</h6>
									<h6 style="margin-top: 5px; margin-bottom: 0px;">{{ !empty($transaction->address) ? $transaction->address : "" }}</h6>
									<h6 style="margin-top: 5px; margin-bottom: 0px;">{{ !empty($transaction->address_2) ? $transaction->address_2 : "" }}</h6>
									<h6 style="margin-top: 5px; margin-bottom: 0px;">{{ !empty($transaction->address_3) ? $transaction->address_3 : "" }}</h6>
									<h6 style="margin-top: 5px; margin-bottom: 0px;">
										{{ $transaction->city }}, 
										{{ $transaction->postcode }}, 
										{{ !empty($delivery_state->name) ? $delivery_state->name : $transaction->state }}, 
										{{ !empty($delivery_country->country_name) ? $delivery_country->country_name : $transaction->country }}
									</h6>
									<br>
									<h6 style="margin-top: 5px; margin-bottom: 0px;">
										<b>{{ isset($data['backendlang']['backendlang']['TEL']) ? $data['backendlang']['backendlang']['TEL'] : 'TEL' }}:</b> 
										@if(!empty($transaction->country_code))
										+{{ $transaction->country_code }}
										@endif
										@if($transaction->country_code && $transaction->country_code == '60')
											{{ ($transaction->phone[0] == 0) ? $transaction->phone : '0'.$transaction->phone }}
										@else
											{{ ($transaction->phone[0] == 0) ? substr($transaction->phone, 1) : $transaction->phone }}
										@endif
									</h6>
								</div>
							@endif
						</div>
					</td>
					<td width="50%"></td>
				</tr>
			@endif

			<tr>
				<td width="50%" style="padding: 0px;">
					<div class="address-arrows-bottom">
						<div class="address-br">

						</div>
					</div>
				</td>
				<td width="50%"></td>
			</tr>
		</table>
		<br>
	</div>

	<div class="print-content">
		<table class="table" style="width: 98%; margin: 5px auto;">
			<tr style="border-top: 1px solid #000000;">
				<td><h6 style="margin-top: 3px; margin-bottom: 0px; font-weight: 600;">{{ $data['backendlang']['backendlang']['No'] ?? 'No' }}</h6></td>
				<td><h6 style="margin-top: 3px; margin-bottom: 0px; font-weight: 600;">{{ $data['backendlang']['backendlang']['Item_Code'] ?? 'Item Code' }}</h6></td>
				<td><h6 style="margin-top: 3px; margin-bottom: 0px; font-weight: 600;">{{ $data['backendlang']['backendlang']['Description'] ?? 'Description' }}</h6></td>
				<td><h6 style="margin-top: 3px; margin-bottom: 0px; font-weight: 600;">{{ $data['backendlang']['backendlang']['Qty'] ?? 'Qty' }}</h6></td>
				<td><h6 style="margin-top: 3px; margin-bottom: 0px; font-weight: 600;">{{ !empty($transaction->pv_purchase) ? ($data['backendlang']['backendlang']['Point'] ?? 'Point') : ($data['backendlang']['backendlang']['Price'] ?? 'Price') }}/{{ $data['backendlang']['backendlang']['Unit'] ?? 'Unit' }}</h6></td>
				<td align="center" style="width: 130px;"><h6 style="margin-top: 3px; margin-bottom: 0px; font-weight: 600;">{{ $data['backendlang']['backendlang']['Amount'] ?? 'Amount' }}</h6></td>
			</tr>
			<tr style="border-top: 1px solid #000000;">
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			@php
				$sub_total = 0;
			@endphp
			@foreach($details as $key => $details)
				<tr>
					<td><h6>{{ $key+1 }}</h6></td>
					<td><h6>{{ $details->item_code }}</h6></td>
					<td class="cn">
						<h6>
							{{ $details->product_name }}<br>
							{!! ($details->sub_category != '') ? "Option: ".$details->sub_category."<br>" : '' !!}
							{!! ($details->second_sub_category != '') ? "Second Option: ".$details->second_sub_category."<br>" : '' !!}
							{{ !empty($details->unique_code) ? '('.$details->unique_code.')<br>' : '' }}
						</h6>
					</td>
					<td><h6>{{ number_format($details->t_qty, 2) }}</h6></td>
					<td><h6>{{ number_format($details->unit_price, 2) }}</h6></td>
					<td align="right"><h6>{{ number_format(($details->unit_price) * $details->t_qty, 2) }}</h6></td>
				</tr>
				@php
					$sub_total += $details->unit_price * $details->t_qty;
				@endphp
			@endforeach
			<tr><td></td></tr>
			<tr><td></td></tr>
			<tr>
				<td></td>
				<td></td>
				<td>
					@if(!empty($transaction->remark))
						<h6>{{ $data['backendlang']['backendlang']['REMARKS'] ?? 'REMARKS' }}</h6>
						<h6 style="margin-top: unset;
									max-width: 300px;
									line-height: 1.4;
									word-wrap: break-word;
									white-space: normal;">
							{!! $transaction->remark !!}
						</h6>
					@endif
				</td>
				<td></td>
				<td>
					<h6 style="margin: 10px 0; font-weight: 600;">
						<span style="display: inline-block; width: 150px;">{{ $data['backendlang']['backendlang']['Sub_Total'] ?? 'Sub Total' }}:</span>
					</h6>
					<h6 style="margin: 10px 0; font-weight: 600;">
						<span style="display: inline-block; width: 150px;">{{ $data['backendlang']['backendlang']['Shipping_Fee'] ?? 'Shipping Fee' }}:</span>
					</h6>
					@if(!empty($transaction->ad_discount))
						<h6 style="margin: 10px 0; font-weight: 600;">
							<span style="display: inline-block; width: 150px;">{{ $data['backendlang']['backendlang']['Agent_Discount'] ?? 'Agent Discount' }}:</span>
						</h6>
					@endif
					@if(!empty($transaction->discount))
						<h6 style="margin: 10px 0; font-weight: 600;">
							<span style="display: inline-block; width: 150px;">
								{{ $data['backendlang']['backendlang']['Discount'] ?? 'Discount' }}
								@if(!empty($transaction->discount_code))
									(
										@if(!empty($transaction->discount_code))
											{{ $transaction->discount_code }}
											->
										@endif

										@if($transaction->discount_type == 'Percentage')
											{{ number_format($transaction->discount_amount, 2) }}%
										@else
											@if(!empty($transaction->pv_purchase))
												{{ number_format($transaction->discount_amount, 2) }} {{ $data['backendlang']['backendlang']['Point'] ?? 'Point' }}
											@else
												RM {{ number_format($transaction->discount_amount, 2) }}
											@endif
										@endif
									)
								@endif
								:
							</span>
						</h6>
					@endif
					<h6 style="margin: 10px 0; font-weight: 600;">
						<span style="display: inline-block; width: 150px;">{{ $data['backendlang']['backendlang']['Processing_Fee'] ?? 'Processing Fee' }}:</span>
					</h6>
				</td>
				<td align="right">
					<h6 style="margin: 10px 0; font-weight: 600;">
						<span>{{ number_format($sub_total, 2) }}</span>
					</h6>
					<h6 style="margin: 10px 0; font-weight: 600;">
						<span>{{ number_format($transaction->shipping_fee, 2) }}</span>
					</h6>
					@if(!empty($transaction->ad_discount))
					<h6 style="margin: 10px 0; font-weight: 600;">
						<span>{{ number_format($transaction->ad_discount, 2) }}</span>
					</h6>
					@endif
					@if(!empty($transaction->discount))
					<h6 style="margin: 10px 0; font-weight: 600;">
						<span>{{ number_format($transaction->discount, 2) }}</span>
					</h6>
					@endif
					<h6 style="margin: 10px 0; font-weight: 600;">
						<span>{{ number_format($transaction->processing_fee, 2) }}</span>
					</h6>
				</td>
			</tr>
		</table>
	</div>
		
	<div class="page-footer">
		<table class="table" style="width: 98%; margin: 5px auto;">
			<tr style="border-top: 1px solid #000000; height: 7px;">
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			<tr style="font-weight: 600;">
				<td colspan="3">
					@php
						if(!function_exists('numberToWordsMYR')) {
							function numberToWordsMYR($number){
								$f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
								$words = strtoupper($f->format($number));
								return $words . ' ONLY';
							}
						}
						
						$grand_total = $sub_total + $transaction->shipping_fee - $transaction->discount - $transaction->ad_discount + $transaction->processing_fee;
					@endphp
					<h6 style="margin: 0; font-weight: 600; line-height: 1.4;">
						@if(!function_exists('numberToWordsMYR_CN'))
							@php
								function numberToWordsMYR_CN($number) {
									$chiNumbers = ['零','一','二','三','四','五','六','七','八','九'];
									$chiUnits = ['', '十', '百', '千', '万', '十万', '百万', '千万', '亿'];

									$number = round($number);
									if ($number == 0) return '零而已';

									$numStr = (string)$number;
									$numArr = array_reverse(str_split($numStr));
									$result = '';

									foreach ($numArr as $i => $num) {
										$num = intval($num);
										if ($num != 0) {
											$result = $chiNumbers[$num] . $chiUnits[$i] . $result;
										} else {
											if (substr($result, 0, 3) != '零') {
												$result = '零' . $result;
											}
										}
									}

									$result = rtrim($result, '零');

									return $result . '而已';
								}
							@endphp
						@endif

						@if(!empty($transaction->pv_purchase))
							{{ $data['backendlang']['backendlang']['Point'] ?? 'Point' }}: {{ numberToWordsMYR($grand_total) }}
						@else
							{{ $data['backendlang']['backendlang']['Malaysia_Ringgit'] ?? 'Malaysia Ringgit' }}:  
							@if(isset($_COOKIE['backend_global_language']) && !empty($_COOKIE['backend_global_language']))
					            @if($_COOKIE['backend_global_language'] == '1')
					                {{ numberToWordsMYR_CN($grand_total) }}
					            @else
					                {{ numberToWordsMYR($grand_total) }}
					            @endif   
							@endif
						@endif
					</h6>
				</td>
				<td></td>
				<td>
					<h6 style="margin: 0; font-weight: 600;">
						{{ $data['backendlang']['backendlang']['Total'] ?? 'Total' }}({{ !empty($transaction->pv_purchase) ? ($data['backendlang']['backendlang']['Point'] ?? 'Point') : 'MYR' }}):
					</h6>
				</td>
				<td align="right" style="border: 1px solid #000000;">
					<h6 style="margin: 0; font-weight: 600;">
						{{ number_format($grand_total, 2) }}
					</h6>
				</td>
			</tr>
		</table>
		<div class="holder" style="width: 95%; margin: 20px auto;">
			@if(isset($_COOKIE['backend_global_language']) && !empty($_COOKIE['backend_global_language']))  
				@if($_COOKIE['backend_global_language'] == '1')
					@if(!empty($data['web_setting']->invoice_notes_cn))
						{!! $data['web_setting']->invoice_notes_cn !!}
					@else
						{!! $data['web_setting']->invoice_notes !!}
					@endif
				@else
					{!! $data['web_setting']->invoice_notes !!}
				@endif
			@else
				{!! $data['web_setting']->invoice_notes !!}
			@endif
		</div>
		<br>
		<br>
		<br>
		<div style="margin: 20px; width: 250px;">
			<div style="border-top: 1px solid #000000; margin-bottom: 8px;">

			</div>
			<div style="text-align: center;">
				<h6 style="margin: 0; font-weight: 600;">
					{{ $data['backendlang']['backendlang']['Authorised_Signature'] ?? 'Authorised Signature' }}
				</h6>
			</div>
		</div>
	</div>
</div>
<a href="#" class="print-window" style="display: none;">
	<i class="fa fa-print"></i> Print
</a>
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