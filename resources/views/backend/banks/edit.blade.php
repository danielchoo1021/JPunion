@extends('layouts.admin_app')

@section('content')
<div class="page-header">
    <h1>
       {{ isset($data['backendlang']['backendlang']['Bank_Detail']) ? $data['backendlang']['backendlang']['Bank_Detail'] :'' }}
        <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
            {{ $payment_bank->bank_name }}
        </small>
    </h1>
</div>
@if($errors->any())
  <div class="alert alert-danger">{!! implode('<br/>', $errors->all(':message')) !!}</div>
@endif
<form method="POST" action="{{ route('bank.banks.update', $payment_bank->id) }}" id="payment_banks-form" enctype="multipart/form-data">
@csrf
@method('PUT')
@include('backend.banks.form')
</form>
<div class="submit-form-btn">
	<div class="form-group wizard-actions" align="right">
		<a href="{{ route('bank.banks.index') }}" class="btn btn-outline-danger">
			<i class="fa fa-ban"> {{ isset($data['backendlang']['backendlang']['Cancel']) ? $data['backendlang']['backendlang']['Cancel'] :'' }}</i>
		</a>

		<button class="btn btn-outline-primary">
			<i class="fa fa-check"> {{ isset($data['backendlang']['backendlang']['Save_Changes']) ? $data['backendlang']['backendlang']['Save_Changes'] :'' }}</i>
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