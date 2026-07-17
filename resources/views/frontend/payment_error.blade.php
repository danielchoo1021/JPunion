@extends('layouts.app')

@section('content')
<div class="form-group">
	<div class="container">
		<div class="row">
			<div class="col-md-6 col-md-offset-3 container-box">
				<div class="card">
					<div class="card-body" align="center">
						<img src="{{ asset('images/fail_641034.png') }}" style="width: 130px">

						<h3>Payment Fail!</h3>
						<p>Please Try Again</p>

						<a href="{{ route('order_list') }}" class="btn btn-primary set_button set_text"> Go To Order List</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection