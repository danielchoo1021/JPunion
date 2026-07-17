@extends('layouts.admin_app')

@section('content')
<div class="page-header">
    <h1>
        {{ isset($data['backendlang']['backendlang']['Setting_Dual_Affiliate_Commission']) ? $data['backendlang']['backendlang']['Setting_Dual_Affiliate_Commission'] :'' }}
    </h1>
</div>

<form method="POST" action="{{ route('save_setting_dual_commission') }}" id="setting-merchant-form">
@csrf
<div class="form-group">
	<label>{{ isset($data['backendlang']['backendlang']['Commission_Per_Transaction']) ? $data['backendlang']['backendlang']['Commission_Per_Transaction'] :'' }}</label>
	<div class="row">
		<div class="col-md-3">
			<select class="form-control" name="commission_p_t_type" >
				<option value="1">{{ isset($data['backendlang']['backendlang']['Percentage']) ? $data['backendlang']['backendlang']['Percentage'] :'' }}</option>
				<option value="2">{{ isset($data['backendlang']['backendlang']['Amount']) ? $data['backendlang']['backendlang']['Amount'] :'' }}</option>
			</select>
		</div>
		<div class="col-md-9">
			<input type="text" class="form-control" name="commission_p_t" value="{{ $SettingDualMain->comm_amount }}">
		</div>
	</div>
</div>

<div class="form-group">
	<div class="row">
		@foreach($levels as $level)
		<div class="col-md-6">
			<h4>{{ $level->agent_lvl }}</h4>
			<input type="hidden" name="agent_lvl[]" value="{{ $level->id }}">
			<input type="hidden" name="did[]" value="{{ !empty($SettingDualCommission[$level->id]->id) ? $SettingDualCommission[$level->id]->id : '' }}">
			<div class="container-box">
				<div class="row">
					@php
						$selectedCT = !empty($SettingDualCommission[$level->id]->comm_type) ? $SettingDualCommission[$level->id]->comm_type : old('level_comm_type')
					@endphp
					<div class="col-md-3">
						<select class="form-control" name="level_comm_type[]">
							<option {{ ($selectedCT == 1) ? 'selected' : '' }} value="1">{{ isset($data['backendlang']['backendlang']['Percentage']) ? $data['backendlang']['backendlang']['Percentage'] :'' }}</option>
							<option {{ ($selectedCT == 2) ? 'selected' : '' }} value="2">{{ isset($data['backendlang']['backendlang']['Amount']) ? $data['backendlang']['backendlang']['Amount'] :'' }}</option>
						</select>
					</div>
					<div class="col-md-9">
						<input type="text" class="form-control" name="level_comm_amount[]" value="{{ !empty($SettingDualCommission[$level->id]->comm_amount) ? $SettingDualCommission[$level->id]->comm_amount : '' }}">
					</div>
				</div>
			</div>
		</div>
		@endforeach
	</div>
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