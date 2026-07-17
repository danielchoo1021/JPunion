@extends('layouts.admin_app')
@section('content')
<form method="POST" action="{{ route('save_setting_commission') }}" id="setting-merchant-form">
@csrf
<div class="row">

	@foreach($agent_lvl as $key => $lvl)
		<div class="col-sm-6 col-12">
			<h3>{{ $lvl->agent_lvl }}</h3>

			<div class="container-box">
				<div class="form-group">
					<div class="">
						@if(!$agents[$key]->isEmpty())
							@foreach($agents[$key] as $agent)
								<div class="form-group">
									<label>
										@php
											if($agent->level == 1){
												$b = 'st';
											}elseif($agent->level == 2){
												$b = 'nd';
											}else{
												$b = 'rd';
											}
										@endphp
										{{ $agent->level.$b }} {{ isset($data['backendlang']['backendlang']['Generation']) ? $data['backendlang']['backendlang']['Generation'] :'' }}
									</label>

									<input type="hidden" name="type[]" value="Agent">
									<input type="hidden" name="agent_lvl[]" value="{{ $agent->id }}">
									<input type="hidden" name="level[]" value="{{ $agent->level }}">
									<input type="hidden" name="lvl_id[]" value="{{ $lvl->id }}">
									<div class="row">
										<div class="col-4">
											<select class="form-control" name="comm_type[]">
												<option {{ (!empty($agent) && $agent->comm_type == 'Percentage') ? 'selected' : '' }} value="Percentage">{{ isset($data['backendlang']['backendlang']['Percentage']) ? $data['backendlang']['backendlang']['Percentage'] :'' }}</option>
												<option {{ (!empty($agent) && $agent->comm_type == 'Amount') ? 'selected' : '' }} value="Amount">{{ isset($data['backendlang']['backendlang']['Amount']) ? $data['backendlang']['backendlang']['Amount'] :'' }}</option>
											</select>
										</div>
										<div class="col-8">
											<input type="text" class="form-control" name="comm_amount[]" placeholder="{{ isset($data['backendlang']['backendlang']['Commission']) ? $data['backendlang']['backendlang']['Commission'] :'' }}" 
												   value="{{ !empty($agent) ? $agent->comm_amount  : '' }}" 
												   onkeypress="return isNumberKey(event)">
										</div>
									</div>
									<hr>
								</div>
							@endforeach
						@else

							@for($a=1; $a<=3; $a++)
							<div class="form-group">
								<label>
									@php
										if($a == 1){
											$b = 'st';
										}elseif($a == 2){
											$b = 'nd';
										}else{
											$b = 'rd';
										}
									@endphp
									{{ $a.$b }} {{ isset($data['backendlang']['backendlang']['Generation']) ? $data['backendlang']['backendlang']['Generation'] :'' }}
								</label>

								<input type="hidden" name="type[]" value="Agent">
								<input type="hidden" name="agent_lvl[]">
								<input type="hidden" name="level[]" value="{{ $a }}">
								<input type="hidden" name="lvl_id[]" value="{{ $lvl->id }}">
								<div class="row">
									<div class="col-4">
										<select class="form-control" name="comm_type[]">
											<option value="Percentage">{{ isset($data['backendlang']['backendlang']['Percentage']) ? $data['backendlang']['backendlang']['Percentage'] :'' }}</option>
											<option value="Amount">{{ isset($data['backendlang']['backendlang']['Amount']) ? $data['backendlang']['backendlang']['Amount'] :'' }}</option>
										</select>
									</div>
									<div class="col-8">
										<input type="text" class="form-control" name="comm_amount[]" placeholder="{{ isset($data['backendlang']['backendlang']['Commission']) ? $data['backendlang']['backendlang']['Commission'] :'' }}" value="" 
											   onkeypress="return isNumberKey(event)">
									</div>
								</div>
								<hr>
							</div>
							@endfor
						@endif
					</div>
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