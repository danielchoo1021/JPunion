@extends('layouts.admin_app')
@section('content')
<form method="POST" action="{{ route('save_setting_area_agent_subsidy') }}" id="setting-merchant-form">
@csrf
<div class="row">
		<div class="col-sm-12">
			<div class="container-box">
				@foreach($agent_lvls as $lvl)
					<div class="form-group">
						<div class="row">
							<div class="col-sm-6">
								<h5><b>{{ $lvl->area_agent_lvl }} ({{ $lvl->area_agent_lvl_cn }})</b></h5>
								<input type="hidden" name="lvl_id[]" value="{{ $lvl->id }}">
							</div>
							<div class="col-sm-6">
								<input type="text" name="subsidy[]" class="form-control subsidy" onkeypress="return isNumberKey(event)" value="{{ $lvl->subsidy }}">
							</div>
						</div>
					</div>
				@endforeach
			</div>
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