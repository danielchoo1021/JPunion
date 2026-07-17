@extends('layouts.admin_app')
@section('content')
<form method="POST" action="{{ route('save_setting_birthday_popup') }}" id='setting-merchant-form'>
@csrf
	<div class="form-group">
		<div class="row">
			<div class="col-md-12">
				<div class="container-box">
					<div class="form-group">
						<div class="row">
							<div class="col-sm-12">
								<label><h4>{{ isset($data['backendlang']['backendlang']['Contents']) ? $data['backendlang']['backendlang']['Contents'] :'' }}</h4></label>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-sm-12">
								<textarea class="form-control" name="birthday_popup" id="birthday_popup">{!! (!empty($setting)) ? $setting->birthday_popup : '' !!}</textarea>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>
<!-- <form method="POST" action="{{ route('run_cron_job') }}">
	@csrf
	<div class="form-group">
		<button class="btn">
			Run Cron Job
		</button>
	</div>
</form> -->
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
	var birthday_popup_url = '{{ route("CKEditorUploadImage", ["_token" => csrf_token()]) }}';

	var birthday_popup = CKEDITOR.instances["birthday_popup"];

    if(!birthday_popup){
      	CKEDITOR.replace( 'birthday_popup',{
          	filebrowserUploadUrl: birthday_popup_url,
          	filebrowserUploadMethod: 'form'
      	});
  	}

	$('.submit-form-btn .btn-outline-primary').click( function(e){
    	e.preventDefault();
    	
    	$('#setting-merchant-form').submit();
    });
</script>
@endsection