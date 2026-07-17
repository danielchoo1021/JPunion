@extends('layouts.admin_app')

@section('content')
@if($errors->any())
  <div class="alert alert-danger">{!! implode('<br/>', $errors->all(':message')) !!}</div>
@endif
<form method="POST" action="{{ route('payment_bank.payment_banks.store') }}" id="payment_banks-form" enctype="multipart/form-data">
@csrf
@include('backend.payment_banks.form')
</form>


<div class="submit-form-btn">
	<div class="form-group wizard-actions" align="right">
		<a href="{{ route('payment_bank.payment_banks.index') }}" class="btn btn-outline-danger">
			<i class="fa fa-ban"> {{ isset($data['backendlang']['backendlang']['Cancel']) ? $data['backendlang']['backendlang']['Cancel'] :'' }}</i>
		</a>

		<button class="btn btn-outline-primary">
			<i class="fa fa-check"> {{ isset($data['backendlang']['backendlang']['Create']) ? $data['backendlang']['backendlang']['Create'] :'' }}</i>
		</button>

	</div>
</div>

@endsection

@section('js')
<script type="text/javascript">
	$('.submit-form-btn .btn-outline-primary').click( function(e){
    	e.preventDefault();
    	
    	$('#payment_banks-form').submit();
    });
</script>
@endsection