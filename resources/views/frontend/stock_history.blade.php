@extends('layouts.app')

@section('content')
<div class="profile-own-bg">
	<div class="personal-header-info">
			<div class="container">
				<div class="row">
					<div class="col-4" align="left">
						<a href="{{ route('myStock') }}">
							<p style="color: white;"><i class="fa fa-chevron-left"></i> Back</p>
						</a>
					</div>
					<div class="col-4" align="left">
						<p align="center" class="header-title">My Account</p>
					</div>
					<div class="col-4" align="right">
						<a href="{{ route('my_setting') }}" class="setting-btn">
							<i class="fa fa-cog" style="font-size: 20px;"></i>
						</a>
					</div>
				</div>
			</div>

		<div class="container">
			<div class="form-group">
				<div class="row">
					<div class="col-2">
						<a href="{{ route('profile') }}">
							@if(!empty(Auth::user()->profile_logo))
								<!-- <img src="{{ asset(Auth::user()->profile_logo) }}" width="50" class="profile-logo"> -->
								<div style="background-image: url({{ asset(Auth::user()->profile_logo) }}); width: 50px; height: 50px; border-radius: 100%; background-size: 100%; background-position: center; background-repeat: no-repeat;"></div>
							@else
								<img src="{{ asset('images/images.png') }}" width="50" class="profile-logo">
							@endif							
						</a>
					</div>
					<div class="col-6">
						<a href="{{ route('profile') }}">
							&nbsp;
							<b class="profile-name">{{ Auth::user()->f_name }} {{ Auth::user()->l_name }}</b>
							<br>
							&nbsp;
							<small class="profile-code">Code: {{ Auth::user()->code }}</small>
							<br>
							&nbsp;
							<small class="profile-level">Level: {{ !empty($lvl) ? $lvl : ' - ' }}</small>
							
						</a>
					</div>
					<!-- <div class="col-xs-4" align="right">
						<a href="#">
							<i class="fa fa-pencil"></i> Edit Profile
						</a>

					</div> -->
				</div>
			</div>
			
			@if(Auth::guard('merchant')->check())
				<div class="form-group container-box sl-personal-header">
					<div class="row">
						<div class="col-6" align="center">
							<a href="{{ route('myqrcode') }}">
								<img src="{{ asset('images/qrcode.png') }}" width="30">
								<br>
								<span class="profile-word">My QRcode</span>
							</a>
						</div>

						<!-- <div class="col-4" align="center">
							<a href="{{ route('MyAffiliate', Auth::user()->code) }}">
								<img src="{{ asset('images/profile/585e4d1ccb11b227491c339b.png') }}" width="30">
								<br>
								<span class="profile-word">My Team</span>
							</a>
						</div> -->

						<div class="col-6" align="center">
							<a href="{{ route('wallet') }}">
								<img src="{{ asset('images/profile/c3286d4d32fa90ebcf09b488654612b9-wallet-icon-by-vexels.png') }}" width="30">
								<br>
								<span class="profile-word">My Wallet</span>
							</a>
						</div>
					</div>
				</div>
			@else
				<div class="form-group container-box sl-personal-header">
					<div class="row">
						<div class="col-4" align="center">
							<a href="{{ route('myqrcode') }}">
								<img src="{{ asset('images/qrcode.png') }}" width="30">
								<br>
								<span class="profile-word">My QRcode</span>
							</a>
						</div>

						<div class="col-4" align="center">
							<a href="{{ route('MyAffiliate', Auth::user()->code) }}">
								<img src="{{ asset('images/profile/585e4d1ccb11b227491c339b.png') }}" width="30">
								<br>
								<span class="profile-word">My Team</span>
							</a>
						</div>

						<div class="col-4" align="center">
							<a href="{{ route('wallet') }}">
								<img src="{{ asset('images/profile/c3286d4d32fa90ebcf09b488654612b9-wallet-icon-by-vexels.png') }}" width="30">
								<br>
								<span class="profile-word">My Wallet</span>
							</a>
						</div>
					</div>
				</div>
			@endif
			
		</div>
	</div>
</div>

<div class="profile-content pb-5">
	<div class="container">
		<h4>Product: {{ $product->product_name }}</h4>
		<div class="form-group" style="overflow: auto;">
			<table class="table table-bordered">
				<tr>
					<td>Transaction No</td>
					<td>Date</td>
					<td>Type</td>
					<td>Recipient</td>
					<td>Quantity</td>
				</tr>
				@foreach($transactions as $transaction)
				<tr>
					<td>{{ $transaction->transaction_no }}</td>
					<td>{{ $transaction->CreatedDate }}</td>
					<td>
						@if($transaction->transaction_type == 1)
							@if(Auth::guard($data['userGuardRole'])->user()->code == $transaction->transaction_to)
								In Stock
							@else
								Out Stock
							@endif
						@else
							In Stock
						@endif
					</td>
					<td>
						@if($transaction->transaction_type == 1)
							{{ $transaction->recipient }} ({{ $transaction->transaction_to }})
						@else
							{{ $transaction->buyer }}  ({{ $transaction->user_id }})
						@endif
					</td>
					<td>
						@if($transaction->transaction_type == 1)
							@if(Auth::guard($data['userGuardRole'])->user()->code == $transaction->transaction_to)
								+ {{ $transaction->quantity }}
							@else
								- {{ $transaction->quantity }}
							@endif
						@else
							+ {{ $transaction->quantity }}
						@endif
					</td>
				</tr>
				@endforeach
			</table>
		</div>
	</div>
</div>
@endsection