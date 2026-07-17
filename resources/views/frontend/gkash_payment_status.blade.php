@extends('layouts.app')

@section('js')
@endsection
@section('content')
	<div class="container my-5">
		<div class="form-group" align="center">
            @if ($is_successful == '1')
                <img src="{{ asset('images/successgif.gif') }}" width="200">
                <p>Payment Successfull!</p>
                <p>We will receive your order and send Goods to your address</p>
            @else
                <img src="{{ asset('images/fail_641034.png') }}" width="200">
                <p>Payment Unsuccessfull!</p>
                <p>Please Try Again</p>
            @endif
			<p>Order No: {{ $transaction->transaction_no }}</p>
		</div>

        <div class="form-group" align="center">
            <a href="{{ route('home') }}" class="btn btn-sm">
                Back To Home
            </a>
        </div>
	</div>
@endsection