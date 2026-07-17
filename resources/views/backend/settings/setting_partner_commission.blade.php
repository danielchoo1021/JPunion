@extends('layouts.admin_app')
@section('content')
<form method="POST" action="{{ route('save_setting_partner_commission') }}" id="setting-merchant-form">
@csrf
<div class="row">
	@foreach($partner_lvl as $key => $lvl)
		<div class="col-12">
			<h3>{{ $lvl->partner_lvl }} ({{ $lvl->partner_lvl_cn }})</h3>
			<div class="container-box">
				<div class="form-group">
					<div class="row">
						<div class="col-3">
							<h5><b>{{ isset($data['backendlang']['backendlang']['Downline_Requirement']) ? $data['backendlang']['backendlang']['Downline_Requirement'] :'' }}</b></h5>
						</div>
						<div class="col-3">
							<h5><b>{{ isset($data['backendlang']['backendlang']['Downline_Requirement_Type']) ? $data['backendlang']['backendlang']['Downline_Requirement_Type'] :'' }}</b></h5>
						</div>
						<div class="col-3">
							<h5><b>{{ isset($data['backendlang']['backendlang']['Promotion_Order_Requirement']) ? $data['backendlang']['backendlang']['Promotion_Order_Requirement'] :'' }}</b></h5>
						</div>
						<div class="col-3">
							<h5><b>{{ isset($data['backendlang']['backendlang']['Allowance']) ? $data['backendlang']['backendlang']['Allowance'] :'' }}</b></h5>
						</div>
					</div>
					<div class="row">
						<div class="col-3">
							<input type="text" name="requirement[]" class="form-control requirement" value="{{ $lvl->requirement }}" onkeypress="return isNumberKey(event)">
							<input type="hidden" name="lvl_id[]" value="{{ $lvl->id }}">
						</div>
						<div class="col-3">
							<select class="form-control" name="requirement_type[]" class="form-control requirement_type">
								<option value="direct" {{ (isset($lvl) && $lvl->direct_requirement == 1) ? 'selected' : '' }}>{{ isset($data['backendlang']['backendlang']['Direct_Downline']) ? $data['backendlang']['backendlang']['Direct_Downline'] :'' }}</option>
								<option value="team" {{ (isset($lvl) && $lvl->team_requirement == 1) ? 'selected' : '' }}>{{ isset($data['backendlang']['backendlang']['Team_Downline']) ? $data['backendlang']['backendlang']['Team_Downline'] :'' }}</option>
							</select>
						</div>
						<div class="col-3">
							<input type="text" name="promotion_requirement[]" class="form-control promotion_requirement" onkeypress="return isNumberKey(event)" value="{{ $lvl->promotion_requirement }}">
						</div>
						<div class="col-3">
							<input type="text" name="allowance[]" class="form-control allowance" value="{{ $lvl->allowance }}" onkeypress="return isNumberKey(event)">
						</div>
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
			<i class="fa fa-check">  {{ isset($data['backendlang']['backendlang']['Save_Changes']) ? $data['backendlang']['backendlang']['Save_Changes'] :'' }}</i>
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