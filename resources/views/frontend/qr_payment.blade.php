@extends('layouts.app')
@section('content')
<div class="form-group">
	<div class="container-box">
		<form method="POST" action="{{ route('QrPaymentSubmit', md5($merchant->code)) }}" id="payment-form">
			@csrf
			<div class="form-group" align="center">
				<img src="{{ asset((!empty($merchant->profile_logo)) ? $merchant->profile_logo : 'images/images.png') }}" width="100px">
				<h3>{{ $merchant->company_name }}</h3>
			</div>

			<div class="form-group">
				<h5>
					{{ isset($data['lang']['lang']['wallet_balance']) ? $data['lang']['lang']['wallet_balance'] :'Wallet Balance'}}: RM {{ number_format($get_topup_wallet_balance, 2) }}
				</h5>
			</div>
			<div class="form-group">
				<input type="number" class="form-control payment_amount" placeholder="{{ isset($data['lang']['lang']['amount']) ? $data['lang']['lang']['amount'] :'Amount'}}" name="payment_amount" onkeypress="return isNumberKey(event)">
			</div>
			<div class="form-group" align="right">
				<button class="btn btn-primary btn-block btn-payment set_button set_text">
					{{ isset($data['lang']['lang']['pay']) ? $data['lang']['lang']['pay'] :'Pay'}}
				</button>
			</div>
		</form>
	</div>
</div>
@endsection

@section('js')
<script type="text/javascript">
	$('.btn-payment').click(function(e){
		var ele = $(this);
		var balance = "{{ $get_topup_wallet_balance }}";
		var payment_amount = $('.payment_amount').val();

		if(parseFloat(balance) < parseFloat(payment_amount)){
			toastr.error('{{ isset($data['lang']['lang']['insufficient_balance']) ? $data['lang']['lang']['insufficient_balance'] :'Insufficient Balance'}}');
			return false;
		}

		$('.payment-form').submit();
	});
</script>
@endsection