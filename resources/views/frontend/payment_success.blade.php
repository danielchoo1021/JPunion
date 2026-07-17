@extends('layouts.app')

@section('js')
<!-- Facebook Pixel Code -->

<!-- End Facebook Pixel Code -->
<script>
  fbq('track', 'Purchase');
</script>
@endsection
@section('content')
	<div class="container">
		<div class="form-group" align="center">
			<img src="{{ asset('frontend/img/success.gif') }}" width="200">
			<p>Payment Successfull!</p>
			<p>We will receive your order and send Goods to your address</p>
			<p>Order No: {{ $transaction->transaction_no }}</p>
		</div>
	</div>
@endsection