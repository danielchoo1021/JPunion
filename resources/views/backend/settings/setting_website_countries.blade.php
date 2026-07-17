@extends('layouts.admin_app')
@section('content')
<div class="form-group">
	<form method="POST" action="{{ route('save_setting_website_countries') }}" id="setting-form" enctype="multipart/form-data">
		@csrf
		@if($errors->any())
			<div class="alert alert-danger">{!! implode('<br/>', $errors->all(':message')) !!}</div>
		@endif
		<div class="container-box form-group">
			<div class="row">
				<div class="col-sm-2">
					<b>{{ isset($data['backendlang']['backendlang']['Country_s']) ? $data['backendlang']['backendlang']['Country_s'] :'' }}:</b><span class="important-text">*</span>
				</div>
				<div class="col-sm-10">
					<select class="selectpicker form-control" data-live-search="true" multiple name="countries[]">
						@php
							$setting_countries = isset($setting) ? explode(',', $setting->website_countries) : [];
						@endphp
						@foreach($countries as $country)
							<option {{in_array($country->country_id, $setting_countries ?: []) ? "selected": ""}} value="{{ $country->country_id }}" data-tokens="{{ $country->country_id }}">
								{{ $country->country_name }}
							</option>
						@endforeach
					</select>
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
</div>
@endsection

@section('js')
<script type="text/javascript">
	$('.submit-form-btn .btn-outline-primary').click( function(e){
    	e.preventDefault();

    	$('#setting-form').submit();
    });
</script>
@endsection