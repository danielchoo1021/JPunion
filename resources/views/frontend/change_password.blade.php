@extends('layouts.app')

@section('content')
@include('partial.frontend.profile_header')
<div class="container" style="margin-top: 65px;">
	<div class="form-group">
		<div class="row">
			<!-- <div class="col-sm-2">
				<div class="form-group">
					<h4>Hello, {{ Auth::guard($data['userGuardRole'])->user()->f_name }} {{ Auth::guard($data['userGuardRole'])->user()->l_name }}</h4>
				</div>

				<div class="form-group">
					<ul id="menu">
						<li>
							<a href="{{ route('profile') }}">
								My Profile
							</a>
						</li>
						@if(Auth::guard('merchant')->check())
						<li>
							<a href="{{ route('wallet') }}">
								My Wallet
							</a>
						</li>
						@endif
						<li>
							<a href="{{ route('AddressBook.AddressBook.index') }}">
								Address Book
							</a>
						</li>
						<li>
							<a href="{{ route('order_list') }}">
								Order  List
							</a>
						</li>
						<li>
							<a href="{{ route('wish_list') }}">
								Wish List
							</a>
						</li>
						<li class="active">
							<a href="{{ route('changePassword') }}">
								Change Password
							</a>
						</li>
					</ul>
				</div>
			</div> -->
			<div class="col-sm-6">
				<form method="POST" action="{{ route('updateNewPassword') }}">
					@csrf
					<div class="form-group">
						@if($errors->any())
						  <div class="alert alert-danger">{!! implode('<br/>', $errors->all(':message')) !!}</div>
						@endif
					</div>
					<div class="form-group container-box">
						<div class="form-group">
							<label>{{ isset($data['lang']['lang']['current_password']) ? $data['lang']['lang']['current_password'] :'当前密码'}}</label>
							<input type="password" class="form-control" name="old_password" value="{{ old('old_password') }}">
						</div>

						<div class="form-group">
							<label>{{ isset($data['lang']['lang']['new_password']) ? $data['lang']['lang']['new_password'] :'新密码'}}</label>
							<input type="password" class="form-control" name="new_password" value="{{ old('new_password') }}">
						</div>

						<div class="form-group">
							<label>{{ isset($data['lang']['lang']['confirm_new_password']) ? $data['lang']['lang']['confirm_new_password'] :'确认新密码'}}</label>
							<input type="password" class="form-control" name="password_confirmation" value="{{ old('password_confirmation') }}">
						</div>

						<div class="form-group">
							<button class="btn btn-primary set_button set_text">
								<i class="fa fa-check"></i> {{ isset($data['lang']['lang']['save_changes']) ? $data['lang']['lang']['save_changes'] :'保存'}}
							</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@endsection

@section('js')
<script type="text/javascript">
	
</script>
@endsection