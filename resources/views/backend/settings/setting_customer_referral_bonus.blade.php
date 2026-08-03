@extends('layouts.admin_app')
@section('content')
<div class="row">
	<div class="col-sm-12">
		<form method="POST" action="{{ route('save_setting_customer_referral_bonus') }}" id="setting-merchant-form">
			@csrf
			@if(!$levels->isEmpty())
				<div class="row">
					@foreach($levels as $level)
						<div class="col-sm-4 col-12">
							<div class="form-group">
								<input type="hidden" name="agent_lvl[]" value="{{ $level->id }}">
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
									<input type="hidden" name="ids[]" value="{{ !empty($selectDetails[$level->id][0]) ? $selectDetails[$level->id][0] : '' }}">
									<div class="row">
										<div class="col-12">
											<label>{{ isset($data['backendlang']['backendlang']['Target_Paid_Orders']) ? $data['backendlang']['backendlang']['Target_Paid_Orders'] :'' }}</label>
											<input type="text" class="form-control" name="target_orders[]" placeholder="{{ isset($data['backendlang']['backendlang']['Target_Paid_Orders']) ? $data['backendlang']['backendlang']['Target_Paid_Orders'] :'' }}" value="{{ !empty($selectDetails[$level->id][2]) ? $selectDetails[$level->id][2] : '' }}">
										</div>
										<div class="col-12">
											<label>{{ isset($data['backendlang']['backendlang']['Amount']) ? $data['backendlang']['backendlang']['Amount'] :'' }} (MYR)</label>
											<input type="text" class="form-control" name="amount[]" placeholder="{{ isset($data['backendlang']['backendlang']['Amount']) ? $data['backendlang']['backendlang']['Amount'] :'' }}"
											   	   value="{{ !empty($selectDetails[$level->id][1]) ? $selectDetails[$level->id][1] : '' }}" onkeypress="return isNumberKey(event)">
										</div>
									</div>
								</div>
							</div>
						</div>
					@endforeach
				</div>
			@else
				<h3>{{ isset($data['backendlang']['backendlang']['Agent_Level_Needed']) ? $data['backendlang']['backendlang']['Agent_Level_Needed'] :'' }}</h3>
				<p class="important-text">
					{{ isset($data['backendlang']['backendlang']['Please_go_to']) ? $data['backendlang']['backendlang']['Please_go_to'] :'' }} <b>{{ isset($data['backendlang']['backendlang']['Settings']) ? $data['backendlang']['backendlang']['Settings'] :'' }} <i class="fa fa-long-arrow-right" aria-hidden="true"></i> {{ isset($data['backendlang']['backendlang']['Agent_Level']) ? $data['backendlang']['backendlang']['Agent_Level'] :'' }}</b> {{ isset($data['backendlang']['backendlang']['For_add_Agent_Level_first']) ? $data['backendlang']['backendlang']['For_add_Agent_Level_first'] :'' }} </p>
			@endif
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
</script>
@endsection
