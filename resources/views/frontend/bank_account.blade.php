@extends('layouts.app')

@section('content')
@include('partial.frontend.profile_header')
<div class="profile-content mb-5">
	<div class="container">
		<div class="row">
			<div class="col-md-6">
				<form method="POST" action="{{ route('bank_account_save') }}" id="bank-form">
					@csrf
					<div class="container-box">
						@if(isset($bank))
							<input type="hidden" name="bid" value="{{ $bank->id }}">
						@endif
						<div class="form-group">
							<label>{{ isset($data['lang']['lang']['bank_name']) ? $data['lang']['lang']['bank_name'] :'银行名称'}} <span class="important-text">*</span></label>
							<!-- <input type="text" class="form-control required-feild" name="bank_name" value="{{ isset($bank) ? $bank->bank_name : old('bank_name') }}" placeholder="银行名称"> -->
							@php
								$bank_name = isset($bank) ? $bank->bank_name : old('bank_name');
							@endphp
							<select class="form-control required-feild" name="bank_name" style="height: auto;">
								<option value="">{{ isset($data['lang']['lang']['select_bank']) ? $data['lang']['lang']['select_bank'] :'选择银行'}}</option>
								@foreach($banks as $banks)
								<option {{ ($bank_name == $banks->bank_name) ? 'selected' : '' }} value="{{ $banks->bank_name }}">
									{{ $banks->bank_name }}
								</option>
								@endforeach
							</select>
						</div>

						<div class="form-group">
							<label>{{ isset($data['lang']['lang']['bank_holder_name']) ? $data['lang']['lang']['bank_holder_name'] :'银行持有人'}} <span class="important-text">*</span></label>
							<input type="text" class="form-control required-feild" name="bank_holder_name" value="{{ Auth::user()->f_name.' '.Auth::user()->l_name }}" placeholder="银行持有人" readonly>
						</div>

						<div class="form-group">
							<label>{{ isset($data['lang']['lang']['bank_acc_no']) ? $data['lang']['lang']['bank_acc_no'] :'银行户口号码'}} <span class="important-text">*</span></label>
							<input type="text" class="form-control required-feild" name="bank_account" value="{{ isset($bank) ? $bank->bank_account : old('bank_account') }}" placeholder="{{ isset($data['lang']['lang']['bank_acc_no']) ? $data['lang']['lang']['bank_acc_no'] :'银行户口号码'}}" onkeypress="return isNumberKey(event)">
						</div>
						<div class="form-group">
							<b id="error-message" class="important-text"></b>
						</div>
						<div class="form-group">
							<button class='btn btn-pink btn-sm submit-bank set_button set_text'><i class="fa fa-check"></i> {{ isset($data['lang']['lang']['save_changes']) ? $data['lang']['lang']['save_changes'] :'保存'}}</button>
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
	$('#bank-form .required-feild').change( function(){
    	if($(this).val()){
    		$(this).removeClass('required-feild-error');
    	}
    });

	$('.submit-bank').click( function(e){
		e.preventDefault();
		var empty_fill;
	    $('#bank-form .required-feild').each( function(){
	    	if(!$(this).val()){
	    		$(this).addClass('required-feild-error');
	    		empty_fill = 1;
	    	}
	    });
	    if(empty_fill == 1){
	    	$('#error-message').html('请填写所有必填字段.');
	    	return false;
	    }

	    $('#bank-form').submit();
	});
</script>
@endsection