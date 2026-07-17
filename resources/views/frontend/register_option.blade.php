@extends('layouts.app')
<script src="{{ asset('frontend/js/qrious.min.js') }}"></script>
<script src="https://unpkg.com/qr-code-with-logo@1.1.0/lib/qr-code-with-logo.browser.min.js"></script>
@section('content')
<div class="profile-own-bg">
	<div class="personal-header-info">
			<div class="container">
				<div class="row">
					<div class="col-4" align="left">
						
					</div>
					<div class="col-4" align="left">
						<p align="center" class="header-title">{{ isset($data['lang']['lang']['register_account']) ? $data['lang']['lang']['register_account'] :'注册账号'}}</p>
					</div>
					<div class="col-4" align="right">
						
					</div>
				</div>
			</div>

		<div class="container">
			<div class="form-group">
				<div class="row">
					<!-- <div class="col-xs-4" align="right">
						<a href="#">
							<i class="fa fa-pencil"></i> Edit Profile
						</a>

					</div> -->
				</div>
			</div>
			<div class="form-group container-box sl-personal-header" align="center">
				<div class="form-group">
					@if(!empty($data['ecommerce_logo']))
						<div class="" style="background-image: url({{ asset($data['admin']->website_logo) }});
											 background-repeat: no-repeat; background-size: cover; background-position: center; width: 70px; height: 70px;
											 border-radius: 100%;">
						</div>
					@else
						<img src="{{ asset('images/images.png') }}" class="profile-image" id="profile-image" width="80">
					@endif
				</div>

				<div class="form-group">
					
				</div>

				<div class="form-group">
					<p>{{ isset($data['lang']['lang']['please_select_account_type']) ? $data['lang']['lang']['please_select_account_type'] :'请选择账号分类'}}</p>
				</div>
				<div class="form-group">
					
					<div class="row">
						<div class="col-md-4 offset-md-4" align="center" style="margin-top: 10px; margin-bottom: 10px;">
							<div class="form-control">
								@if(!empty(request('p')))
								<a href="{{ route('register', 'p='. request('p') ) }}">
									<i class="fas fa-user"></i>
									Customer Account
								</a>
								@else
								<a href="{{ route('register') }}">
									<i class="fas fa-user"></i>
									Customer Account
								</a>
								@endif
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-4 offset-md-4" align="center" style="margin-top: 10px; margin-bottom: 10px;">
							<div class="form-control">
								@if(!empty(request('p')))
								<a href="{{ route('merchant_register', 'p='. request('p') ) }}">
									<i class="far fa-user"></i>
									Agent Account
								</a>
								@else
								<a href="{{ route('merchant_register') }}">
									<i class="far fa-user"></i>
									Agent Account
								</a>
								@endif
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-4 offset-md-4" align="center" style="margin-top: 10px; margin-bottom: 10px;">
							<div class="form-control">
								@if(!empty(request('p')))
								<a href="{{ route('company_register', 'p='. request('p') ) }}">
									<i class="fa fa-briefcase"></i>
									Corporate Account
								</a>
								@else
								<a href="{{ route('company_register') }}">
									<i class="fa fa-briefcase"></i>
									Corporate Account
								</a>
								@endif
							</div>
						</div>
					</div>
				</div>

				<div class="form-group">
					<div class="row">
						<div class="col-md-2 offset-md-5" align="center">
							<a href="{{ route('home') }}">
								{{ isset($data['lang']['lang']['cancel_and_return_home']) ? $data['lang']['lang']['cancel_and_return_home'] :'取消并往回网站主页'}}
							</a>
					    </div>
					</div>

			        <div id="previewImage" style="display: none;"></div>
			    </div>
			</div>
		</div>
	</div>
</div>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
@endsection

@section('js')
<script type="text/javascript">
	
</script>

<script type="text/javascript"> 	
</script>
@endsection