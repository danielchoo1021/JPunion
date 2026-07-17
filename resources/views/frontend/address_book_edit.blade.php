@extends('layouts.app')

@section('content')
@include('partial.frontend.profile_header')
<div class="container pb-5">
	<div class="profile-content">
		<div class="container-box">
			<div class="form-group">
				<div class="row">
					<div class="col-sm-12">
						<div class="form-group">
							<h4>{{ isset($data['lang']['lang']['address_form_detail_info']) ? $data['lang']['lang']['address_form_detail_info'] :'地址簿详细信息'}}</h4>
						</div>
						<form method="POST" action="{{ route('AddressBook.AddressBook.update', $address->id) }}" id="new-address-form">
						@method('PUT')
						@csrf
							@include('frontend.address_book_form')
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('js')
<script type="text/javascript">
	$('#country_code').select2({
        placeholder: "请选择国家代码",
        allowClear: true
    });
    
	$('#new-address-form .required-feild').change( function(){
    	if($(this).val()){
    		$(this).removeClass('required-feild-error');
    	}
    });

	$('.submit-address').click( function(e){
		e.preventDefault();
		var empty_fill;
	    $('#new-address-form .required-feild').each( function(){
	    	if(!$(this).val()){
	    		$(this).addClass('required-feild-error');
	    		empty_fill = 1;
	    	}
	    });
	    if(empty_fill == 1){
	    	$('#error-message').html('Please fill out all required information.');
	    	return false;
	    }

	    $('#new-address-form').submit();
	});

	$('.country').change(function(e){
		var ele = $(this);

		if(ele.val() == '160'){
			$('.state_area').html('<select class="form-control" name="state" style="padding: 0.375rem 0.75rem;">\
										<option value="">Select State</option>\
										@foreach($states as $state)\
											<option {{ ($address->state == $state->id) ? "selected" : "" }} value="{{ $state->id }}">{{ $state->name }}</option>\
										@endforeach\
									</select>');
		}else{
			$('.state_area').html('<input type="text" class="form-control state" name="state" placeholder="State" value="{{ $address->state }}">');
		}
	});
</script>
@endsection