@extends('layouts.admin_app')
@section('content')
@if(!$levels->isEmpty())
<h2 class="important-text">
	{{ isset($data['backendlang']['backendlang']['Set_Monthly_Percentage_Total_Performance']) ? $data['backendlang']['backendlang']['Set_Monthly_Percentage_Total_Performance'] :'' }}
</h2>
<form method="POST" action="{{ route('save_setting_performance_dividend') }}" id="setting-merchant-form">
@csrf
<hr>
<div class="row">
	@foreach($levels as $level)
		<div class="col-sm-4">
			
			<div class="form-group container-box">
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
				<input type="hidden" name="sid[]" value="{{ (!empty($selectDetails[$level->id][0])) ? $selectDetails[$level->id][0] : '' }}">
				<input type="hidden" name="lvl[]" value="{{ $level->id }}">
				<div class="form-group">
					<label>{{ isset($data['backendlang']['backendlang']['Target']) ? $data['backendlang']['backendlang']['Target'] :'' }} (RM)</label>
					<input type="text" class="form-control" name="target[]" placeholder="{{ isset($data['backendlang']['backendlang']['Target_Sales']) ? $data['backendlang']['backendlang']['Target_Sales'] :'' }}" 
						   value="{{ (!empty($selectDetails[$level->id][3])) ? $selectDetails[$level->id][3] : '' }}" onkeypress="return isNumberKey(event)">
				</div>
				<div class="form-group">
					<label>{{ isset($data['backendlang']['backendlang']['Percentage']) ? $data['backendlang']['backendlang']['Percentage'] :'' }} (%)</label>
					<input type="text" class="form-control" name="amount[]" placeholder="{{ isset($data['backendlang']['backendlang']['Amount']) ? $data['backendlang']['backendlang']['Amount'] :'' }}" 
						   value="{{ (!empty($selectDetails[$level->id][2])) ? $selectDetails[$level->id][2] : '' }}" onkeypress="return isNumberKey(event)">
				</div>
			</div>
		</div>
	@endforeach
</div>
</form>

<div class="submit-form-btn">
	<div class="form-group wizard-actions" align="right">
		<button class="btn btn-outline-primary">
			<i class="fa fa-check"> {{ isset($data['backendlang']['backendlang']['Save_Changes']) ? $data['backendlang']['backendlang']['Save_Changes'] :'' }}</i>
		</button>

	</div>
</div>
@else
	<h3>{{ isset($data['backendlang']['backendlang']['Agent_Level_Needed']) ? $data['backendlang']['backendlang']['Agent_Level_Needed'] :'' }}</h3>
	<p class="important-text">
		{{ isset($data['backendlang']['backendlang']['Please_go_to']) ? $data['backendlang']['backendlang']['Please_go_to'] :'' }} <b>{{ isset($data['backendlang']['backendlang']['Settings']) ? $data['backendlang']['backendlang']['Settings'] :'' }} <i class="fa fa-long-arrow-right" aria-hidden="true"></i> {{ isset($data['backendlang']['backendlang']['Agent_Level']) ? $data['backendlang']['backendlang']['Agent_Level'] :'' }}</b> {{ isset($data['backendlang']['backendlang']['For_add_Agent_Level_first']) ? $data['backendlang']['backendlang']['For_add_Agent_Level_first'] :'' }} </p>
@endif
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