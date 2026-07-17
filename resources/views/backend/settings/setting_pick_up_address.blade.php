@extends('layouts.admin_app')
@section('content')
<form method="POST" action="{{ route('save_setting_pick_up_address') }}" id="setting-merchant-form">
	@csrf
	<div class="form-group ">
		<div class="row">
			<div class="col-md-6">
				<div class="container-box">
					<div class="form-group">
						<label><b>{{ isset($data['backendlang']['backendlang']['Company_Name']) ? $data['backendlang']['backendlang']['Company_Name'] :'' }}</b> <span class="important-text">*</span></label>
						<input type="text" name="company_name" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['Company_Name']) ? $data['backendlang']['backendlang']['Company_Name'] :'' }}" value="{{ isset($select) ? $select->company_name : old('company_name') }}">
					</div>

					<div class="form-group">
						<label><b>{{ isset($data['backendlang']['backendlang']['Contact_Person']) ? $data['backendlang']['backendlang']['Contact_Person'] :'' }}</b> <span class="important-text">*</span></label>
						<input type="text" name="contact" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['Contact_Person']) ? $data['backendlang']['backendlang']['Contact_Person'] :'' }}" value="{{ isset($select) ? $select->contact : old('contact') }}"  onkeypress="return isNumberKey(event)">
					</div>

					<div class="form-group">
						<label><b>{{ isset($data['backendlang']['backendlang']['Address']) ? $data['backendlang']['backendlang']['Address'] :'' }}</b> <span class="important-text">*</span></label>
						<textarea name="address" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['Address']) ? $data['backendlang']['backendlang']['Address'] :'' }}">{!! isset($select) ? $select->address : old('address') !!}</textarea>
					</div>

					<div class="form-group">
						<label><b>{{ isset($data['backendlang']['backendlang']['Postcode']) ? $data['backendlang']['backendlang']['Postcode'] :'' }}</b> <span class="important-text">*</span></label>
						<input type="text" name="postcode" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['Postcode']) ? $data['backendlang']['backendlang']['Postcode'] :'' }}" value="{{ isset($select) ? $select->postcode : old('postcode') }}"  onkeypress="return isNumberKey(event)">
					</div>

					<div class="form-group">
						<label><b>{{ isset($data['backendlang']['backendlang']['City']) ? $data['backendlang']['backendlang']['City'] :'' }}</b> <span class="important-text">*</span></label>
						<input type="text" name="city" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['City']) ? $data['backendlang']['backendlang']['City'] :'' }}" value="{{ isset($select) ? $select->city : old('city') }}">
					</div>
					@php
						$selectedValue = isset($select) ? $select->state : old('state');
					@endphp
					<div class="form-group">
						<label><b>{{ isset($data['backendlang']['backendlang']['State']) ? $data['backendlang']['backendlang']['State'] :'' }}</b> <span class="important-text">*</span></label>
						<select class="form-control" name="state">
							<option>{{ isset($data['backendlang']['backendlang']['Select_State']) ? $data['backendlang']['backendlang']['Select_State'] :'' }}</option>
							@foreach($states as $state)
							<option {{ ($selectedValue == $state->id) ? 'selected' : '' }} value="{{ $state->id }}">{{ $state->name }}</option>
							@endforeach
						</select>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>
<div class="submit-form-btn">
	<div class="form-group wizard-actions" align="right">

		<button class="btn btn-outline-primary">
			<i class="bi bi-check"> {{ isset($data['backendlang']['backendlang']['Save_Changes']) ? $data['backendlang']['backendlang']['Save_Changes'] :'' }}</i>
		</button>

	</div>
</div>
@endsection
@section('js')
<script type="text/javascript">
	$('.submit-form-btn .btn-outline-primary').click( function(e){
    	e.preventDefault();
    	
    	$('#setting-merchant-form').submit();
    });
</script>
@endsection