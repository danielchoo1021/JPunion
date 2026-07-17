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
$amount = number_format($transactions->grand_total, 2);
$post_amount = str_replace(',', '', $amount);
$absolute_amount = str_replace('.', '', $post_amount);

$version = '1.5.5';
$merchant_id = $data['gkash_setting']->param; // e.g. M161-U-41322
$merchant_reference_number = $transactions->transaction_no;
$currency = 'MYR';
$signature_key = $data['gkash_setting']->param_1; // e.g. RiLy5QyPlnG81fV
$v_signature = hash('sha512', strtoupper($signature_key).";".$merchant_id.";".$merchant_reference_number.";".$absolute_amount.";".$currency);

@endphp
<form method="post" id="payment-submit" action="https://api-staging.pay.asia/api/paymentform.aspx">

<input type="hidden" name="version" value="{{ $version }}" />
<input type="hidden" name="CID" value="{{ $merchant_id }}" />
<input type="hidden" name="v_cartid" value="{{ $merchant_reference_number }}" />
<input type="hidden" name="v_currency" value="{{ $currency }}" />
<input type="hidden" name="v_amount" value="{{ $post_amount }}" />
<input type="hidden" name="signature" value="{{ $v_signature }}" />
<input type="hidden" name="returnurl" value="{{ $data['productionURL'].'/api/gkash_payment_status' }}" />
<input type="hidden" name="callbackurl" value="{{ $data['productionURL'].'/api/gkash_payment_successfully' }}" />
<input type="submit" value="Submit" />

<button type="submit" id="submit-btn" style="display: none;"></button>
</form>
<div class="form-group">
	<div class="container">
		<div class="row" align="center">
			<div class="col-xs-12 container-box">
				
				<img src="{{ url('images/loading/card-1673581__340.png') }}" width="100px">
				<h3>Payment Processing</h3>

				<p>Your Payment is being Processed, Please Wait..</p>
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