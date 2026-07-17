@extends('layouts.admin_app')
@section('content')
<form method="POST" action="{{ route('save_setting_merchant_commission') }}" id="setting-merchant-form">
@csrf
	<div class="row">
		@if(!$levels->isEmpty())
		@foreach($levels as $level)
		<div class="col-md-4 col-12">
			<div class="container-box form-group">
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
					<div class="">
						
						@for($a=1; $a<=3; $a++)
						<div class="form-group">
							<label>
								@php
									if($a == 1){
										$b = 'st';
									}elseif($a == 2){
										$b = 'nd';
									}elseif($a == 2){
										$b = 'rd';
									}else{
										$b = 'rd';
									}
								@endphp
								{{ $a.$b }} {{ isset($data['backendlang']['backendlang']['Generation']) ? $data['backendlang']['backendlang']['Generation'] :'' }}
							</label>

							<input type="hidden" name="agent_lvl[]" value="{{ $level->id }}">
							<input type="hidden" name="ids[]" value="{{ !empty($value[$a][$level->id][2]) ? $value[$a][$level->id][2] : '' }}">
							<input type="hidden" name="level[]" value="{{ $a }}">
							<div class="row">
								<div class="col-4">
									<select class="form-control" name="comm_type[]">
										<option {{ (!empty($value[$a][$level->id][0]) && $value[$a][$level->id][0] == 'Percentage') ? 'selected' : '' }} value="Percentage">{{ isset($data['backendlang']['backendlang']['Percentage']) ? $data['backendlang']['backendlang']['Percentage'] :'' }}</option>
										<option {{ (!empty($value[$a][$level->id][0]) && $value[$a][$level->id][0] == 'Amount') ? 'selected' : '' }} value="Amount">{{ isset($data['backendlang']['backendlang']['Amount']) ? $data['backendlang']['backendlang']['Amount'] :'' }}</option>
									</select>
								</div>
								<div class="col-8">
									<input type="text" class="form-control" name="comm_amount[]" placeholder="{{ isset($data['backendlang']['backendlang']['Commission']) ? $data['backendlang']['backendlang']['Commission'] :'' }}" 
										   value="{{ !empty($value[$a][$level->id][1]) ? $value[$a][$level->id][1] : '' }}" 
										   onkeypress="return isNumberKey(event)">
								</div>
							</div>
							<hr>
						</div>
						@endfor
					</div>
				</div>
			</div>
		</div>
		@endforeach

        @if($data['web_setting']->bonus_member_enable == 1)
			<div class="col-md-12 col-12 mt-3">
				<div class="container-box form-group">
					<span class="box form-group" style="background-color: #ffa558;">
						<h2 align='center' style="color: white;" class="text">{{ isset($data['backendlang']['backendlang']['Member']) ? $data['backendlang']['backendlang']['Member'] :'' }}</h2>
					</span>
					<br>
					<div class="form-group">
						<div class="">
							<div class="form-group">
								<label>
									{{ isset($data['backendlang']['backendlang']['1st_Generation']) ? $data['backendlang']['backendlang']['1st_Generation'] :'' }}
								</label>
								<input type="hidden" name="level[]" value="1">
								<div class="row">
									<div class="col-4">
										<select class="form-control" name="member_heirarchy_one_type">
											<option {{ (!empty($website_setting->member_heirarchy_one_type) && $website_setting->member_heirarchy_one_type == 'Percentage') ? 'selected' : '' }} value="Percentage">{{ isset($data['backendlang']['backendlang']['Percentage']) ? $data['backendlang']['backendlang']['Percentage'] :'' }}</option>
											<option {{ (!empty($website_setting->member_heirarchy_one_type) && $website_setting->member_heirarchy_one_type == 'Amount') ? 'selected' : '' }} value="Amount">{{ isset($data['backendlang']['backendlang']['Amount']) ? $data['backendlang']['backendlang']['Amount'] :'' }}</option>
										</select>
									</div>
									<div class="col-8">
										<input type="text" class="form-control" name="member_heirarchy_one_amount" placeholder="{{ isset($data['backendlang']['backendlang']['Commission']) ? $data['backendlang']['backendlang']['Commission'] :'' }}" 
											   value="{{ !empty($website_setting->member_heirarchy_one_amount) ? $website_setting->member_heirarchy_one_amount : '' }}" 
											   onkeypress="return isNumberKey(event)">
									</div>
								</div>
								<hr>
								<label>
									{{ isset($data['backendlang']['backendlang']['2nd_Generation']) ? $data['backendlang']['backendlang']['2nd_Generation'] :'' }}
								</label>
								<input type="hidden" name="level[]" value="1">
								<div class="row">
									<div class="col-4">
										<select class="form-control" name="member_heirarchy_two_type">
											<option {{ (!empty($website_setting->member_heirarchy_two_type) && $website_setting->member_heirarchy_two_type == 'Percentage') ? 'selected' : '' }} value="Percentage">{{ isset($data['backendlang']['backendlang']['Percentage']) ? $data['backendlang']['backendlang']['Percentage'] :'' }}</option>
											<option {{ (!empty($website_setting->member_heirarchy_two_type) && $website_setting->member_heirarchy_two_type == 'Amount') ? 'selected' : '' }} value="Amount">{{ isset($data['backendlang']['backendlang']['Amount']) ? $data['backendlang']['backendlang']['Amount'] :'' }}</option>
										</select>
									</div>
									<div class="col-8">
										<input type="text" class="form-control" name="member_heirarchy_two_amount" placeholder="{{ isset($data['backendlang']['backendlang']['Commission']) ? $data['backendlang']['backendlang']['Commission'] :'' }}" 
											   value="{{ !empty($website_setting->member_heirarchy_two_amount) ? $website_setting->member_heirarchy_two_amount : '' }}" 
											   onkeypress="return isNumberKey(event)">
									</div>
								</div>
								<hr>
								<label>
									{{ isset($data['backendlang']['backendlang']['3rd_Generation']) ? $data['backendlang']['backendlang']['3rd_Generation'] :'' }}
								</label>
								<input type="hidden" name="level[]" value="1">
								<div class="row">
									<div class="col-4">
										<select class="form-control" name="member_heirarchy_three_type">
											<option {{ (!empty($website_setting->member_heirarchy_three_type) && $website_setting->member_heirarchy_three_type == 'Percentage') ? 'selected' : '' }} value="Percentage">{{ isset($data['backendlang']['backendlang']['Percentage']) ? $data['backendlang']['backendlang']['Percentage'] :'' }}</option>
											<option {{ (!empty($website_setting->member_heirarchy_three_type) && $website_setting->member_heirarchy_three_type == 'Amount') ? 'selected' : '' }} value="Amount">{{ isset($data['backendlang']['backendlang']['Amount']) ? $data['backendlang']['backendlang']['Amount'] :'' }}</option>
										</select>
									</div>
									<div class="col-8">
										<input type="text" class="form-control" name="member_heirarchy_three_amount" placeholder="{{ isset($data['backendlang']['backendlang']['Commission']) ? $data['backendlang']['backendlang']['Commission'] :'' }}" 
											   value="{{ !empty($website_setting->member_heirarchy_three_amount) ? $website_setting->member_heirarchy_three_amount : '' }}" 
											   onkeypress="return isNumberKey(event)">
									</div>
								</div>
								<hr>
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
</script>
@endsection