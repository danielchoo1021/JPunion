@extends('layouts.admin_app')
@section('content')
<div class="row">
	<div class="col-12">
		<form method="POST" action="{{ route('save_setting_customer_level') }}" id="setting-customer-form">
			@csrf
			<div class="big-parent">
				<div class="form-group">
					<div class="row">
						<div class="col-6">
							<div class="form-group">
								<h5><b>{{ isset($data['backendlang']['backendlang']['Customer_Level_Description']) ? $data['backendlang']['backendlang']['Customer_Level_Description'] :'' }}</b></h5>
							</div>
						</div>
						<div class="col-6">
							<div class="form-group">
								<h5><b>{{ isset($data['backendlang']['backendlang']['Customer_Level_Code']) ? $data['backendlang']['backendlang']['Customer_Level_Code'] :'' }}</b></h5>
							</div>
						</div>
					</div>
					<div class="child-div">
						@foreach($levels as $level)
						<div class="form-group child-row">
							<div class="row">
								<div class="col-6">
									<input type="text" name="customer_lvl[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['EG_Customer']) ? $data['backendlang']['backendlang']['EG_Customer'] :'' }}" value="{{ $level->customer_lvl }}">
									<input type="hidden" name="lvl_id[]" value="{{ $level->id }}">
								</div>
								<div class="col-6">
									<input type="text" name="customer_lvl_code[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['e.g.']) ? $data['backendlang']['backendlang']['e.g.'] :'' }} M"  value="{{ $level->customer_lvl_code }}">
								</div>
							</div>
						</div>
						@endforeach
						<div class="form-group child-row">
							<div class="row">
								<div class="col-6">
									<input type="text" name="customer_lvl[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['EG_Customer']) ? $data['backendlang']['backendlang']['EG_Customer'] :'' }}">
									<input type="hidden" name="lvl_id[]">
								</div>
								<div class="col-6">
									<input type="text" name="customer_lvl_code[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['e.g.']) ? $data['backendlang']['backendlang']['e.g.'] :'' }} M"  value="">
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
	$('#setting-customer-form').on('change', 'input', function(){
		var ele = $(this);
		
		if(ele.val()){
			ele.removeClass('input-required-field');
		}
	});

	$('#setting-customer-form').on('change', '.products', function(){
		var ele = $(this);
		var productdiv = $(this).closest('.child-row').find('.select2-container--default .select2-selection--single');

		if(ele.val()){
			productdiv.removeClass('input-required-field');
		}
	});

	$('.submit-form-btn .btn-outline-primary').click( function(e){
    	e.preventDefault();

		
    	$('#setting-customer-form').submit();
    });

	var add_new_row = '<div class="row">\
							<div class="col-6">\
								<input type="text" name="customer_lvl[]" class="form-control" placeholder="{{ isset($data["backendlang"]["backendlang"]["EG_Customer"]) ? $data["backendlang"]["backendlang"]["EG_Customer"] :'' }}">\
								<input type="hidden" name="lvl_id[]">\
							</div>\
							<div class="col-6">\
								<input type="text" name="customer_lvl_code[]" class="form-control" placeholder="{{ isset($data["backendlang"]["backendlang"]["e.g."]) ? $data["backendlang"]["backendlang"]["e.g."] :'' }} M"  value="">\
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