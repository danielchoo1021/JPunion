@extends('layouts.app')

@section('content')
<div class="container my-5">
	<div class="row">
		<div class="col-md-6 offset-md-3">
			<form method="POST" action="{{ route('resetPasswordAction', md5($detail->code)) }}">
			@csrf
				<div class="container-box">
					
					<div class="form-group" align="center">
						<h3>Reset your new password</h3>
					</div>
					<hr>
					@if($errors->any())
					  	<div class="alert alert-danger">{!! implode('<br/>', $errors->all(':message')) !!}</div>
					@endif
					<div class="form-group">
						<label>New Password</label>
						<input type="password" name="password" class="form-control" placeholder="New Password">
					</div>
					<div class="form-group">
						<label>Confirmation Password</label>
						<input type="password" name="password_confirmation" class="form-control" placeholder="Password Confirmation">
					</div>

					<div class="form-group" align="right">
						<button class="btn btn-primary">
							Submit
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection	