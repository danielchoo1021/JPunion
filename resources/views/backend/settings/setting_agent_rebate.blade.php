@extends('layouts.admin_app')
@section('content')
<div class="row">
	<div class="col-12">
		<form method="POST" action="{{ route('save_setting_agent_rebate') }}" id="setting-merchant-form">
			@csrf
			<div class="row">
				@if(!$levels->isEmpty())
					@foreach($levels as $level)
					<div class="col-sm-4 col-12">
						<div class="container-box">
							<span class="box form-group" style="background-color: {{ $level->level_colour }};">
								@php
									$langFlag = $_COOKIE['backend_global_language'] ?? ($_COOKIE['backend_global_language'] ?? '0');

										if($langFlag == 1){
											$agent_lvl = $level->agent_lvl_cn;
										}else{
											$agent_lvl = $level->agent_lvl;
										}
								@endphp
								<h2 align='center' style="color: white;" class="text">{{ $agent_lvl }}</h2>
							</span>
							<br>
							<div class="form-group">
								<div class="row">
									<div class="col-sm-12">
										<input type="hidden" name="rebate_id[]" value="{{ !empty($agent_lvl_rebate[$level->id]->id) ? $agent_lvl_rebate[$level->id]->id : '' }}">
										<input type="hidden" name="agent_lvl[]" value="{{ $level->id }}">
										<div class="row">
											<div class="col-sm-6">
												<select class="form-control" name='type[]'>
													<option {{ ($agent_lvl_rebate[$level->id]->type == 'Percentage') ? 'selected' : '' }} 
															value="Percentage">{{ isset($data['backendlang']['backendlang']['Percentage']) ? $data['backendlang']['backendlang']['Percentage'] :'' }}</option>
													<option {{ ($agent_lvl_rebate[$level->id]->type == 'Amount') ? 'selected' : '' }} 
															value="Amount">{{ isset($data['backendlang']['backendlang']['Amount']) ? $data['backendlang']['backendlang']['Amount'] :'' }}</option>
												</select>
											</div>
											<div class="col-sm-6">
												<input type="text" name="amount[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['e.g.']) ? $data['backendlang']['backendlang']['e.g.'] :'' }} 5" value="{{ !empty($agent_lvl_rebate[$level->id]->amount) ? $agent_lvl_rebate[$level->id]->amount : '' }}">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					@endforeach
            		@if($data['web_setting']->bonus_member_enable == 1)
					<div class="col-sm-12">
						<div class="container-box mt-3">
							<span class="box form-group" style="background-color: #ffa558;">
								<h2 align='center' style="color: white;" class="text">
									{{ isset($data['backendlang']['backendlang']['Member']) ? $data['backendlang']['backendlang']['Member'] :'' }}
								</h2>
							</span>
							<br>
							<div class="form-group">
								<div class="row">
									<div class="col-sm-12">
										<div class="row">
											<div class="col-sm-6">
												<select class="form-control" name='member_rebate_type'>
													<option {{ ($website_setting->member_rebate_type == 'Percentage') ? 'selected' : '' }} 
															value="Percentage">{{ isset($data['backendlang']['backendlang']['Percentage']) ? $data['backendlang']['backendlang']['Percentage'] :'' }}</option>
													<option {{ ($website_setting->member_rebate_type == 'Amount') ? 'selected' : '' }} 
															value="Amount">{{ isset($data['backendlang']['backendlang']['Amount']) ? $data['backendlang']['backendlang']['Amount'] :'' }}</option>
												</select>
											</div>
											<div class="col-sm-6">
												<input type="text" name="member_rebate_amount" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['e.g.']) ? $data['backendlang']['backendlang']['e.g.'] :'' }} 5" value="{{ !empty($website_setting->member_rebate_amount) ? $website_setting->member_rebate_amount : '' }}">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
            		@endif
				@else
					<h3>{{ isset($data['backendlang']['backendlang']['Agent_Level_Needed']) ? $data['backendlang']['backendlang']['Agent_Level_Needed'] :'' }}</h3>
					<p class="important-text">
						{{ isset($data['backendlang']['backendlang']['Please_go_to']) ? $data['backendlang']['backendlang']['Please_go_to'] :'' }} <b>{{ isset($data['backendlang']['backendlang']['Settings']) ? $data['backendlang']['backendlang']['Settings'] :'' }} <i class="fa fa-long-arrow-right" aria-hidden="true"></i> {{ isset($data['backendlang']['backendlang']['Agent_Level']) ? $data['backendlang']['backendlang']['Agent_Level'] :'' }}</b> {{ isset($data['backendlang']['backendlang']['For_add_Agent_Level_first']) ? $data['backendlang']['backendlang']['For_add_Agent_Level_first'] :'' }} </p>
				@endif
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
	$('.submit-form-btn .btn-outline-primary').click( function(e){
    	e.preventDefault();
    	$('.loading-gif').show();
    	$('#setting-merchant-form').submit();
    });

    var add_new_row = '<div class="form-group child-row">\
							<div class="row">\
								<div class="col-6">\
									<input type="text" name="point_target[]" class="form-control" placeholder="{{ isset($data["backendlang"]["backendlang"]["Purchase_Amount"]) ? $data["backendlang"]["backendlang"]["Purchase_Amount"] :''}}">\
									<input type="hidden" name="sid[]">\
								</div>\
								<div class="col-6">\
									<div class="row">\
										<div class="col-6">\
											<select class="form-control" name="type[]">\
												<option value="Percentage">{{ isset($data["backendlang"]["backendlang"]["Percentage"]) ? $data["backendlang"]["backendlang"]["Percentage"] :''}}</option>\
												<option value="Amount">{{ isset($data["backendlang"]["backendlang"]["Amount"]) ? $data["backendlang"]["backendlang"]["Amount"] :''}}</option>\
											</select>\
										</div>\
										<div class="col-6">\
											<input type="text" name="amount[]" class="form-control" placeholder="{{ isset($data["backendlang"]["backendlang"]["e.g."]) ? $data["backendlang"]["backendlang"]["e.g."] :''}} 5">\
										</div>\
									</div>\
								</div>\
							</div>\
						</div>';

    $('#add-row-btn').click(function(e){
    	e.preventDefault();
    	var ele = $(this);

    	ele.closest('.big-parent').find('.child-div').append(add_new_row);
    });
</script>
@endsection