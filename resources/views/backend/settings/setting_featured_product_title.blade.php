@extends('layouts.admin_app')
@section('content')
<div class="form-group">
	<form method="POST" action="{{ route('save_setting_featured_product_title') }}" id="setting-form" enctype="multipart/form-data">
		@csrf
		<div class="big-parent pb-5">
			<div class="form-group">
				<div class="row">
					<div class="col-12">
						<div class="container-box">
							<h4>
								{{ isset($data['backendlang']['backendlang']['Setting_Featured_Product_Title_EN']) ? $data['backendlang']['backendlang']['Setting_Featured_Product_Title_EN'] :'' }}
							</h4>
							<hr>
							<div class="form-group">
								<textarea class="form-control" name="setting_featured_product_title" id="setting_featured_product_title">{!! (!empty($setting->featured_product_title)) ? $setting->featured_product_title : '' !!}</textarea>
							</div>
						</div>
					</div>
					<div class="col-12">
						<div class="container-box">
							<h4>
								{{ isset($data['backendlang']['backendlang']['Setting_Featured_Product_Title_CN']) ? $data['backendlang']['backendlang']['Setting_Featured_Product_Title_CN'] :'' }}
							</h4>
							<hr>
							<div class="form-group">
								<textarea class="form-control" name="setting_featured_product_title_cn" id="setting_featured_product_title_cn">{!! (!empty($setting->featured_product_title_cn)) ? $setting->featured_product_title_cn : '' !!}</textarea>
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

	var setting_featured_product_titleUrl = '{{ route("CKEditorUploadImage", ["_token" => csrf_token()]) }}';

	var setting_featured_product_title = CKEDITOR.instances["setting_featured_product_title"];

    if(!setting_featured_product_title){
      	CKEDITOR.replace( 'setting_featured_product_title',{
          	filebrowserUploadUrl: setting_featured_product_titleUrl,
          	filebrowserUploadMethod: 'form'
      	});
  	}

	var setting_featured_product_title_cnUrl = '{{ route("CKEditorUploadImage", ["_token" => csrf_token()]) }}';

	var setting_featured_product_title_cn = CKEDITOR.instances["setting_featured_product_title_cn"];

    if(!setting_featured_product_title_cn){
      	CKEDITOR.replace( 'setting_featured_product_title_cn',{
          	filebrowserUploadUrl: setting_featured_product_title_cnUrl,
          	filebrowserUploadMethod: 'form'
      	});
  	}
</script>
@endsection