@extends('layouts.admin_app')
@section('content')
<form method="POST" action="{{ route('save_setting_pv') }}" id="setting-pv-form">
@csrf
<div class="row">
	<div class="col-sm-6">
		<h3>{{ isset($data['backendlang']['backendlang']['Get_PV']) ? $data['backendlang']['backendlang']['Get_PV'] :'' }}</h3>
		<div class="form-group container-box">
			<div class="row">
				<div class="col-sm-4">
					<h4>RM 1.00</h4>
				</div>
				<div class="col-sm-2">
					<h4>=</h4>
				</div>
				<div class="col-sm-6">
					<input type="text" name="get_pv" class="form-control get_pv" placeholder="{{ isset($data['backendlang']['backendlang']['PV']) ? $data['backendlang']['backendlang']['PV'] :'' }}" style="text-align: right;" value="{{ isset($pv_setting) ? $pv_setting->get_pv_rate : '' }}">
				</div>
			</div>
		</div>
	</div>
	<!-- <div class="col-sm-6">
		<h3>Spend PV</h3>
		<div class="form-group container-box">
			<div class="row">
				<div class="col-sm-4">
					<h4>RM 1.00</h4>
				</div>
				<div class="col-sm-2">
					<h4>=</h4>
				</div>
				<div class="col-sm-6">
					<input type="text" name="spend_pv" class="form-control spend_pv" placeholder="PV" style="text-align: right;" value="{{ isset($pv_setting) ? $pv_setting->spend_pv_rate : '' }}">
				</div>
			</div>
		</div>
	</div> -->
</div>
<!-- <h2>SYSTEM UPDATE</h2>
<hr>
<h4>
	This page and functions are temporary unavailable. System update will be complete in soon..<br>
</h4>
<span style="font-size: 12px">(If any inquiries please contact your IT consultance.)</span> -->
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
    	$('#setting-pv-form').submit();
    });
</script>
@endsection