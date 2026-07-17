@extends('layouts.admin_app')
@section('content')
<form method="POST" action="{{ route('save_setting_merchant_same_level') }}" id="setting-merchant-form">
@csrf
<div class="row">
	@if(!$levels->isEmpty())
	@foreach($levels as $level)
	<div class="col-sm-6 col-12">
				<h3>{{ $level->agent_lvl }}</h3>
				<br>
				<div class="container-box">
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
										}else{
											$b = 'th';
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
	@else
		<h3>{{ isset($data['backendlang']['backendlang']['Agent_Level_Needed']) ? $data['backendlang']['backendlang']['Agent_Level_Needed'] :'' }}</h3>
		<p class="important-text">
			{{ isset($data['backendlang']['backendlang']['Please_go_to']) ? $data['backendlang']['backendlang']['Please_go_to'] :'' }} <b>{{ isset($data['backendlang']['backendlang']['Settings']) ? $data['backendlang']['backendlang']['Settings'] :'' }} <i class="fa fa-long-arrow-right" aria-hidden="true"></i> {{ isset($data['backendlang']['backendlang']['Agent_Level']) ? $data['backendlang']['backendlang']['Agent_Level'] :'' }}</b> {{ isset($data['backendlang']['backendlang']['For_add_Agent_Level_first']) ? $data['backendlang']['backendlang']['For_add_Agent_Level_first'] :'' }} </p>
	@endif
	
</div>
</form>

<!-- <div class="row">
	<div class="col-sm-6 col-12">
		<div class="form-group">
			<h3>经销商 <i class="fa fa-long-arrow-right" aria-hidden="true"></i> 服务商</h3>
			<div class="container-box">
				<div class="form-group">
					<label>所需人数</label>
					<input type="text" name="" class="form-control" placeholder="人数">
				</div>
			</div>
		</div>
	</div>
</div> -->

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