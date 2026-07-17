@extends('layouts.app')
@section('css')
<style type="text/css">
	.loading-gif{
		display: block !important;
	}
</style>
@endsection
@section('content')
@php
if(!empty($transactions->partial_wallet_type) && !empty($transactions->amount_paid_online)){
	$amount = str_replace(',', '', number_format($transactions->amount_paid_online, 2));
}else{
	if(!empty($transactions->get_currency->conversion_rate)){
		$converted_amount = $transactions->grand_total * $transactions->get_currency->conversion_rate;

		$amount = str_replace(',', '', number_format($converted_amount, 2));
	}else{
		$amount = str_replace(',', '', number_format($transactions->grand_total, 2));
	}
}

$revpay_merchant_id = $data['revpay_setting']->param; // e.g. MER00000144240
$revpay_merchant_key = $data['revpay_setting']->param_1; // e.g. rhZ1KtzC3F

$sigkey = $revpay_merchant_key.$revpay_merchant_id.$transactions->transaction_no.$amount."MYR";

$sig = hash('sha512', $sigkey);

@endphp

<section class="ftco-section">
<form method="post" action="https://mpg.revpay.com.my/v1/payment"
accept-charset="UTF-8" id="payment-submit">
<input type="hidden" name="Revpay_Merchant_ID" value="{{ $revpay_merchant_id }}"><br/>
<input type="hidden" name="Payment_ID" value="{{ $transactions->payment_id }}"><br/>
<input type="hidden" name="Bank_Code" value=""><br/>
<input type="hidden" name="Reference_Number" value="{{ $transactions->transaction_no }}"><br/>
<input type="hidden" name="Amount" value="{{ $amount }}"><br/>
<input type="hidden" name="Currency" value="MYR"><br/>
<input type="hidden" name="Transaction_Description"
value="{{ !empty($data['website_name']) ? $data['website_name'] : '' }} {{ $transactions->transaction_no }}"><br/>
<input type="hidden" name="Billing_Address" value=""><br/>
<input type="hidden" name="Shipping_Address" value=""><br/>
<input type="hidden" name="Device_ID" value=""><br/>
<input type="hidden" name="Ecomm_Marketplace" value=""><br/>
<input type="hidden" name="Promo_Code" value=""><br/>
<input type="hidden" name="Transaction_Type" value=""><br/>
<input type="hidden" name="Customer_ID" value=""><br/>
<input type="hidden" name="Customer_Name" value="{{ $transactions->user_id }}"><br/>
<input type="hidden" name="Customer_Email" value=""><br/>
<input type="hidden" name="Customer_Contact" value=""><br/>
<input type="hidden" name="Customer_IP" value="{{ \Request::ip() }}"><br/>
<input type="hidden" name="Geo_Location" value=""><br/>
<input type="hidden" name="Card_Type" value=""><br/>
<input type="hidden" name="Card_Holder_Name" value=""><br/>
<input type="hidden" name="Funding_Pan" value=""><br/>
<input type="hidden" name="Funding_Exp_Date" value=""><br/>
<input type="hidden" name="Funding_CVV" value=""><br/>
<input type="hidden" name="Card_Issuer_Bank_Country_Code" value=""><br/>
<input type="hidden" name="Instalment_Plan" value=""><br/>
<input type="hidden" name="Instalment_Term" value=""><br/>
<input type="hidden" name="Token_Pan" value=""><br/>
<input type="hidden" name="Token_Exp_Date" value=""><br/>
<input type="hidden" name="Key_Index" value="1"><br/>
<input type="hidden" name="Signature" value="{{ $sig }}"><br/>
<input type="hidden" name="Return_URL"
value="{{ $data['productionURL'] }}/PendingShipping?oid={{ $transactions->transaction_no }}"><br/>
<!-- <input type="hidden" name="Return_URL"
value="https://x-mate.com.my/api/rev_payment_successfully"><br/> -->
<input value="Submit" type="submit">

<button type="submit" id="submit-btn" style="display: none;"></button>
</form>


<div class="form-group">
	<div class="container">
		<div class="row" align="center">
			<div class="col-12 container-box">
				
				<img src="{{ url('images/loading/card-1673581__340.png') }}" width="100px">
				<h3>Payment Processing</h3>

				<p>Your Payment is beeing Processing, Please Wait..</p>
			</div>
		</div>
	</div>
</div>
</section>
@endsection

@section('js')
<script type="text/javascript">
	$( document ).ready(function() {
		

		$('#submit-btn').click( function(){
			$('#payment-submit').submit();
		});


		$( "#submit-btn" ).trigger( "click" );
	});
</script>
@endsection