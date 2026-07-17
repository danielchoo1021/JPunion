@extends('layouts.app')

@section('content')
<div class="container mw-470 py-5 mb-4">
	<div class="container-box">
		<h3>{{ isset($data['lang']['lang']['key_in_your_password']) ? $data['lang']['lang']['key_in_your_password'] :'Key in your password' }}</h3>
		<hr>
		<form method="POST" action="{{ route('resetPassword') }}">
			@csrf
			<input type="hidden" name="aid" value="{{ md5($account->id) }}">
			<div class="form-group">
				<label>{{ isset($data['lang']['lang']['new_password']) ? $data['lang']['lang']['new_password'] :'New Password' }}</label>
				<input type="password" name="new_password" class="form-control" placeholder="{{ isset($data['lang']['lang']['new_password']) ? $data['lang']['lang']['new_password'] :'New Password' }}">
			</div>
			<div class="form-group">
				<label>{{ isset($data['lang']['lang']['confirm_new_password']) ? $data['lang']['lang']['confirm_new_password'] :'Confirm New Password' }}</label>
				<input type="password" name="confirm_new_password" class="form-control" placeholder="{{ isset($data['lang']['lang']['confirm_new_password']) ? $data['lang']['lang']['confirm_new_password'] :'Confirm New Password' }}">
			</div>
			<div class="form-group">
				<button class="btn set_button set_text">
					{{ isset($data['lang']['lang']['reset_password']) ? $data['lang']['lang']['reset_password'] :'Reset Password' }}
				</button>
			</div>
		</form>
	</div>
</div>
@endsection