@extends('layouts.app')

@section('content')
<div class="profile-own-bg">
	<div class="personal-header-info">
			<div class="container">
				<div class="row">
					<div class="col-4" align="left">
						<a href="{{ route('profile') }}">
							<p style="color: white;"><i class="fa fa-chevron-left"></i> Back</p>
						</a>
					</div>
					<div class="col-4" align="left">
						<p align="center" class="header-title">My Order</p>
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
						<a href="{{ route('my_setting') }}">
							@if(!empty(Auth::user()->profile_logo))
								<!-- <img src="{{ asset(Auth::user()->profile_logo) }}" width="50" class="profile-logo"> -->
								<div style="background-image: url({{ asset(Auth::user()->profile_logo) }}); width: 50px; height: 50px; border-radius: 100%; background-size: 100%; background-position: center; background-repeat: no-repeat;"></div>
							@else
								<img src="{{ asset('images/images.png') }}" width="50" class="profile-logo">
							@endif							
						</a>
					</div>
					<div class="col-6">
						&nbsp;
						<b class="profile-name">{{ Auth::user()->f_name }} {{ Auth::user()->l_name }}</b>
						<br>
						&nbsp;
						<small class="profile-code">Code: {{ Auth::user()->code }}</small>
						<br>
						&nbsp;
						@if(Auth::guard('admin')->check() || Auth::guard('merchant')->check())
						<small class="profile-level">Level: {{ !empty($lvl) ? $lvl : ' - ' }}</small>
						@endif
					</div>
					<!-- <div class="col-4" align="right">
						<a href="#">
							<i class="fa fa-pencil"></i> Edit Profile
						</a>

					</div> -->
				</div>
			</div>
			@if(Auth::guard('admin')->check() || Auth::guard('merchant')->check())
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
<div class="profile-content">
	<div class="container">
		<div class="form-group">
			<div class="widget-body">
				<div class="widget-main">

					@foreach($results['result'] as $value)
			            @foreach($value['status_list'] as $key => $value2)
			            	@if(isset($value2['status']))
			            	<div class="form-group" style="box-shadow: 0 0 10px 0 #f8bdbd; padding: 5px 15px; " >
				              	<blockquote style="margin: 0px; {{ ($key == 0) ? 'border-left: 5px solid #f8bdbd;' : ''  }}">
									<p class="lighter line-height-125" style="margin-left: 10px;">
										Tracking No: 
										@if(!empty($transaction->tracking_no))
										{{ $transaction->tracking_no }}
										@else
										-
										@endif
										<br>
										[{{ $transaction->courier }}] {{ isset($value2['status']) ? $value2['status'] : '' }} at {{ isset($value2['location']) ? $value2['location'] : '' }}
									</p>

									<small style="font-size: 11px; margin-left: 10px;">
										<cite title="Source Title">
											{{ isset($value2['event_date']) ? $value2['event_date'] : '' }} 
											{{ isset($value2['event_time']) ? $value2['event_time'] : '' }}
										</cite>
									</small>
								</blockquote>
							</div>
							@endif
			          	@endforeach
			        @endforeach
				</div>
			</div>
		</div>
	</div>
</div>
@endsection