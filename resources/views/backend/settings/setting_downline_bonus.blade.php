@extends('layouts.admin_app')
@section('content')
<div class="page-header">
    <h1>
        Setting Downline Purchase Bonus
        <!-- <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
            @if(Auth::check())
            {{ Auth::user()->f_name }} 
            @endif
        </small> -->
    </h1>
</div>
<form method="POST" action="{{ route('save_setting_downline_bonus') }}" id="setting-merchant-form">
@csrf
<div class="row">
	@if(!$levels->isEmpty())
	@foreach($levels as $level)
	<div class="col-sm-6 col-12">
				<h3>{{ $level->agent_lvl }}</h3>

				<div class="container-box">
					<div class="form-group">
						<div class="parent-box">
							<input type="hidden" name="level_id[]" class="level_id" value="{{ $level->id }}">
							<div class="form-group child-box">
								@foreach($settings as $setting)
									@if($setting->level_id == $level->id)
									<input type="hidden" name="lid{{ $level->id }}[]" value="{{ $setting->id }}">
									<div class="row">
										<div class="col-4">
											<input type="text" class="form-control" name="target{{ $level->id }}[]" placeholder="{{ isset($data['backendlang']['backendlang']['Target']) ? $data['backendlang']['backendlang']['Target'] :'' }}" value="{{ $setting->target }}">
										</div>
										<div class="col-4">
											<select class="form-control" name="comm_type{{ $level->id }}[]">
												<option {{ $setting->comm_type == 'Percentage' ? 'selected' : '' }} value="Percentage">{{ isset($data['backendlang']['backendlang']['Percentage']) ? $data['backendlang']['backendlang']['Percentage'] :'' }}</option>
												<option {{ $setting->comm_type == 'Amount' ? 'selected' : '' }} value="Amount">{{ isset($data['backendlang']['backendlang']['Amount']) ? $data['backendlang']['backendlang']['Amount'] :'' }}</option>
											</select>
										</div>
										<div class="col-4">
											<input type="text" class="form-control" name="comm_amount{{ $level->id }}[]" placeholder="{{ isset($data['backendlang']['backendlang']['Commission']) ? $data['backendlang']['backendlang']['Commission'] :'' }}" value="{{ $setting->comm_amount }}" onkeypress="return isNumberKey(event)">
										</div>
									</div>
									<hr>
									@endif
								@endforeach
								<div class="row">
									<div class="col-4">
										<input type="text" class="form-control" name="target{{ $level->id }}[]" placeholder="{{ isset($data['backendlang']['backendlang']['Target']) ? $data['backendlang']['backendlang']['Target'] :'' }}">
									</div>
									<div class="col-4">
										<select class="form-control" name="comm_type{{ $level->id }}[]">
											<option value="Percentage">{{ isset($data['backendlang']['backendlang']['Percentage']) ? $data['backendlang']['backendlang']['Percentage'] :'' }}</option>
											<option value="Amount">{{ isset($data['backendlang']['backendlang']['Amount']) ? $data['backendlang']['backendlang']['Amount'] :'' }}</option>
										</select>
									</div>
									<div class="col-4">
										<input type="text" class="form-control" name="comm_amount{{ $level->id }}[]" placeholder="{{ isset($data['backendlang']['backendlang']['Commission']) ? $data['backendlang']['backendlang']['Commission'] :'' }}" value="" onkeypress="return isNumberKey(event)">
									</div>
								</div>
								<hr>
							</div>
							<div class="form-group">
								<div class="row justify-content_center">
									<div class="col-md-12" align="center">
										<button type="button" class="add-row-btn" id="add-row-btn">
											<i class="fa fa-plus"></i>
										</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				</div>
		@endforeach
	@else
		<h3>Agent Level Needed</h3>
		<p class="important-text">
			{{ isset($data['backendlang']['backendlang']['Please_go_to']) ? $data['backendlang']['backendlang']['Please_go_to'] :'' }} <b>{{ isset($data['backendlang']['backendlang']['Settings']) ? $data['backendlang']['backendlang']['Settings'] :'' }} <i class="fa fa-long-arrow-right" aria-hidden="true"></i>  {{ isset($data['backendlang']['backendlang']['Agent_Level']) ? $data['backendlang']['backendlang']['Agent_Level'] :'' }}</b> {{ isset($data['backendlang']['backendlang']['For_add_Agent_Level_first']) ? $data['backendlang']['backendlang']['For_add_Agent_Level_first'] :'' }} </p>
	@endif
	
</div>
</form>
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
	$('.submit-form-btn .btn-outline-primary').click( function(e){
    	e.preventDefault();
    	$('.loading-gif').show();
    	$('#setting-merchant-form').submit();
    });

    $('.add-row-btn').click(function(e){
    	e.preventDefault();
    	var ele = $(this);
    	var lvl_id = ele.closest('.parent-box').find('.level_id').val();

    	var add_new_row = '<div class="row">\
							<div class="col-4">\
								<input type="text" class="form-control" name="target'+lvl_id+'[]" placeholder="{{ isset($data["backendlang"]["backendlang"]["Target"]) ? $data["backendlang"]["backendlang"]["Target"] :'' }}">\
							</div>\
							<div class="col-4">\
								<select class="form-control" name="comm_type'+lvl_id+'[]">\
									<option value="Percentage">{{ isset($data["backendlang"]["backendlang"]["Percentage"]) ? $data["backendlang"]["backendlang"]["Percentage"] :'' }}</option>\
									<option value="Amount">{{ isset($data["backendlang"]["backendlang"]["Amount"]) ? $data["backendlang"]["backendlang"]["Amount"] :'' }}</option>\
								</select>\
							</div>\
							<div class="col-4">\
								<input type="text" class="form-control" name="comm_amount'+lvl_id+'[]" placeholder="{{ isset($data["backendlang"]["backendlang"]["Commission"]) ? $data["backendlang"]["backendlang"]["Commission"] :'' }}" value="" onkeypress="return isNumberKey(event)">\
							</div>\
						</div>\
						<hr>';


    	ele.closest('.parent-box').find('.child-box').append(add_new_row);
    });
</script>
@endsection