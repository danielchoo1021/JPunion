@extends('layouts.admin_app')
@section('content')
<div class="form-group">
	<form method="POST" action="{{ route('save_setting_home_overview') }}" id="setting-form" enctype="multipart/form-data">
		@csrf
		<div class="big-parent pb-5">
			<div class="form-group">
				<div class="row">
					<div class="col-12">
						<div class="container-box">
							<h4>
								{{ isset($data['backendlang']['backendlang']['Overview_EN']) ? $data['backendlang']['backendlang']['Overview_EN'] :'' }}
							</h4>
							<hr>
							<div class="form-group">
								<textarea class="form-control" name="home_page_overview" id="home_page_overview">{!! (!empty($setting->home_page_overview)) ? $setting->home_page_overview : '' !!}</textarea>
							</div>
						</div>
					</div>
					<div class="col-12">
						<div class="container-box">
							<h4>
								{{ isset($data['backendlang']['backendlang']['Overview_CN']) ? $data['backendlang']['backendlang']['Overview_CN'] :'' }}
							</h4>
							<hr>
							<div class="form-group">
								<textarea class="form-control" name="home_page_overview_cn" id="home_page_overview_cn">{!! (!empty($setting->home_page_overview_cn)) ? $setting->home_page_overview_cn : '' !!}</textarea>
							</div>
						</div>
					</div>
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

	var home_page_overviewUrl = '{{ route("CKEditorUploadImage", ["_token" => csrf_token()]) }}';

	var home_page_overview = CKEDITOR.instances["home_page_overview"];

    if(!home_page_overview){
      	CKEDITOR.replace( 'home_page_overview',{
          	filebrowserUploadUrl: home_page_overviewUrl,
          	filebrowserUploadMethod: 'form'
      	});
  	}

	var home_page_overview_cnUrl = '{{ route("CKEditorUploadImage", ["_token" => csrf_token()]) }}';

	var home_page_overview_cn = CKEDITOR.instances["home_page_overview_cn"];

    if(!home_page_overview_cn){
      	CKEDITOR.replace( 'home_page_overview_cn',{
          	filebrowserUploadUrl: home_page_overview_cnUrl,
          	filebrowserUploadMethod: 'form'
      	});
  	}
</script>
@endsection