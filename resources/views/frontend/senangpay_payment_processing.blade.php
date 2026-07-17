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

$merchant_id = $data['senangpay_setting']->param; // e.g. 189171870496134
$secretkey = $data['senangpay_setting']->param_1; // e.g. 41852-460

$detail = "Payment For ".$transactions->transaction_no;

$hashed_string = md5($secretkey.urldecode($detail).urldecode($post_amount).urldecode($transactions->transaction_no));

@endphp
<form method="post" id="payment-submit" action="https://app.senangpay.my/payment/{{ $merchant_id }}">

<input type="hidden" name="detail" value="{{ $detail }}">
<input type="hidden" name="amount" value="{{ $post_amount }}">
<input type="hidden" name="order_id" value="{{ $transactions->transaction_no }}">
<input type="hidden" name="name" value="{{ $transactions->user_id }}">
<input type="hidden" name="email" value="{{ $transactions->email }}">
<input type="hidden" name="phone" value="{{ $transactions->phone }}">
<input type="hidden" name="hash" value="{{ $hashed_string }}">

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