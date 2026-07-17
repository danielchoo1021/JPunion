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
$amount = str_replace(',', '', $transactions->grand_total);
$post_amount = number_format($amount, 2, '.', '');

$token = md5("69dom".$amount.$transactions->transaction_no.$transactions->user_id."aba747e46ab730f79ad32ac3ed1c6759940e786c"."MYR".\Request::ip());

@endphp
<form method="post" id="payment-submit" action="https://pgw.surepay88.com/fundtransfer/">
<input type="hidden" name="merchant" value="69dom" /> 
<input type="hidden" name="amount" value="{{ $amount }}" /> 
<input type="hidden" name="refid" value="{{ $transactions->transaction_no }}" /> 
<input type="hidden" name="token" value="{{ $token }}" />
<input type="hidden" name="customer" value="{{ $transactions->user_id }}" /> 
<input type="hidden" name="currency" value="MYR" /> 
<input type="hidden" name="bankcode" value="{{ $bank_code }}" /> 
<input type="hidden" name="clientip" value="{{ \Request::ip() }}" /> 
<input type="hidden" name="post_url" value="https://69dom.com/demo/api/payment_successfully"> 
<input type="hidden" name="failed_return_url" value="https://69dom.com/demo/PendingOrder" />
<input type="hidden" name="return_url" value="https://69dom.com/demo/PendingShipping" />

<button type="submit" id="submit-btn" style="display: none;"></button>
</form>
<div class="form-group">
	<div class="container">
		<div class="row" align="center">
			<div class="col-xs-12 container-box">
				
				<img src="{{ url('images/loading/card-1673581__340.png') }}" width="100px">
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