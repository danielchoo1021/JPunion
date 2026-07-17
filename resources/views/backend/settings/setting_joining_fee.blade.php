@extends('layouts.admin_app')
@section('content')
<div class="row">
	<div class="col-12">
		<form method="POST" action="{{ route('save_setting_joining_fee') }}" id="setting-merchant-form">
			@csrf
			<div class="big-parent">
				<div class="form-group">
					<div class="row">
						<div class="col-6">
							<div class="form-group">
								<h5><b>{{ isset($data['backendlang']['backendlang']['Joining_Fee']) ? $data['backendlang']['backendlang']['Joining_Fee'] :'' }} (RM)</b></h5>
							</div>
						</div>
						<div class="col-6">
							<div class="form-group">
								<h5><b>{{ isset($data['backendlang']['backendlang']['Bonus_Amount']) ? $data['backendlang']['backendlang']['Bonus_Amount'] :'' }} (RM)</b></h5>
							</div>
						</div>
					</div>
					<div class="child-div">
						@foreach($setting_joining_fees as $setting_joining_fee)
						<div class="form-group child-row">
							<div class="row">
								<div class="col-6">
									<input type="text" name="target[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['e.g.']) ? $data['backendlang']['backendlang']['e.g.'] :'' }} 1500" value="{{ $setting_joining_fee->target }}">
									<input type="hidden" name="lvl_id[]" value="{{ $setting_joining_fee->id }}" onkeypress="return isNumberKey(event)">
								</div>
								<div class="col-6">
									<input type="text" name="comm_amount[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['e.g.']) ? $data['backendlang']['backendlang']['e.g.'] :'' }} 500"  value="{{ $setting_joining_fee->comm_amount }}" onkeypress="return isNumberKey(event)">
								</div>
							</div>
						</div>
						@endforeach
						<div class="form-group child-row">
							<div class="row">
								<div class="col-6">
									<input type="text" name="target[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['e.g.']) ? $data['backendlang']['backendlang']['e.g.'] :'' }} 1500">
									<input type="hidden" name="lvl_id[]" onkeypress="return isNumberKey(event)">
								</div>
								<div class="col-6">
									<input type="text" name="comm_amount[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['e.g.']) ? $data['backendlang']['backendlang']['e.g.'] :'' }} 500" onkeypress="return isNumberKey(event)">
								</div>
							</div>
						</div>
					</div>					
				</div>
				<hr>
				<div class="form-group">
					<div class="row">
						<div class="col-md-12" align="center">
							<button class="add-row-btn">
								<i class="fa fa-plus"></i>
							</button>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>

<div class="submit-form-btn">
	<div class="form-group wizard-actions" align="right">
		<button class="btn btn-outline-primary">
			<i class="fa fa-check"> {{ isset($data['backendlang']['backendlang']['Save_Changes']) ? $data['backendlang']['backendlang']['Save_Changes'] :'' }}</i>
		</button>

	</div>
</div>
@endsection
@section('js')
<script type="text/javascript">
	$('#setting-merchant-form').on('change', 'input', function(){
		var ele = $(this);
		
		if(ele.val()){
			ele.removeClass('input-required-field');
		}
	});

	$('#setting-merchant-form').on('change', '.products', function(){
		var ele = $(this);
		var productdiv = $(this).closest('.child-row').find('.select2-container--default .select2-selection--single');

		if(ele.val()){
			productdiv.removeClass('input-required-field');
		}
	});

	$('.submit-form-btn .btn-outline-primary').click( function(e){
    	e.preventDefault();

    	$('#setting-merchant-form').submit();
    });

	var add_new_row = '<div class="form-group child-row">\
							<div class="row">\
								<div class="col-6">\
									<input type="text" name="target[]" class="form-control" placeholder="{{ isset($data["backendlang"]["backendlang"]["e.g."]) ? $data["backendlang"]["backendlang"]["e.g."] :'' }} 1500" onkeypress="return isNumberKey(event)">\
									<input type="hidden" name="lvl_id[]">\
								</div>\
								<div class="col-6">\
									<input type="text" name="comm_amount[]" class="form-control" placeholder="{{ isset($data["backendlang"]["backendlang"]["e.g."]) ? $data["backendlang"]["backendlang"]["e.g."] :'' }} 500" onkeypress="return isNumberKey(event)">\
								</div>\
							</div>\
						</div>';
    $('.add-row-btn').click(function(e){
    	e.preventDefault();
    	var ele = $(this);

    	ele.closest('.big-parent').find('.child-div').append(add_new_row);
    	$('.big-parent .products').select2();
    });

    $('.big-parent .products').select2();
</script>
@endsection