@extends('layouts.app')
@section('css')
<style type="text/css">
	.loading-gif{
		/*display: block !important;*/
	}
</style>
@endsection
@section('content')
@php
$password = "IZno2NdNjMMrwNoO1G8JKaiGd3o4qovC";
$service_id = "KCG";
$payment_id = $transactions->transaction_no;
$m_return_url = "https://kimcafe.com.my/api/payment_successfully";
$m_approval_url = "";
$m_unapproval_url = "https://kimcafe.com.my/api/payment_successfully";
$m_callback_url = "https://kimcafe.com.my/CancelledOrder";
if($transactions->mall == 1){
	$amount = number_format($transactions->additional_shipping_fee, 2, '.', '');
}else{
	$amount = number_format($transactions->grand_total, 2, '.', '');	
}
$currency_code = "MYR";
$cus_ip = \Request::ip();
$page_timeout = "780";
$card_no = "";
$token = "";
$recurring_criteria = "";

$hashKey = hash("sha256", $password.$service_id.$payment_id.$m_return_url.$m_approval_url.$m_unapproval_url.$m_callback_url.$amount.$currency_code.$cus_ip.$page_timeout.$card_no.$token.$recurring_criteria);

@endphp
<form name="frmPayment" method="post" action="https://securepay.e-ghl.com/IPG/Payment.aspx">
<input type="hidden" name="TransactionType" value="SALE">
<input type="hidden" name="PymtMethod" value="ANY">
<input type="hidden" name="ServiceID" value="KCG">
<input type="hidden" name="PaymentID" value="{{ $payment_id }}">
<input type="hidden" name="OrderNumber" value="{{ $transactions->transaction_no }}">
<input type="hidden" name="PaymentDesc" value="Order No: {{ $transactions->transaction_no }}">
<input type="hidden" name="MerchantName" value="KIM CAFE">
<input type="hidden" name="MerchantReturnURL" value="{{ $m_return_url }}">
<input type="hidden" name="MerchantUnApprovalURL" value="{{ $m_unapproval_url }}">
<input type="hidden" name="MerchantCallbackURL" value="{{ $m_callback_url }}">
<input type="hidden" name="Amount" value="{{ $amount }}">
<input type="hidden" name="CurrencyCode" value="MYR">
<input type="hidden" name="CustIP" value="{{ \Request::ip() }}">
<input type="hidden" name="CustName" value="{{ $transactions->address_name }}">
<input type="hidden" name="CustEmail" value="{{ $transactions->email }}">
<input type="hidden" name="CustPhone" value="{{ $transactions->phone }}">
<input type="hidden" name="HashValue" value="{{ $hashKey }}">
<input type="hidden" name="MerchantTermsURL" value="https://kimcafe.com.my/">
<input type="hidden" name="LanguageCode" value="en">
<input type="hidden" name="PageTimeout" value="780">

<button type="submit" id="submit-btn"></button>
</form>
<div class="form-group">
	<div class="container">
		<div class="row" align="center">
			<div class="col-xs-12 container-box">
				
				<img src="{{ asset('images/loading/card-1673581__340.png') }}" width="100px">
				<h3>Payment Processing</h3>

				<p>Your Payment is beeing Processing, Please Wait..</p>
			</div>
		</div>
	</div>
</div>
@endsection

@section('js')
<script type="text/javascript">
	$( document ).ready(function() {
		

		$('#submit-btn').click( function(){
			$('payment-submit').submit();
		});


		$( "#submit-btn" ).trigger( "click" );
	});
</script>
@endsection