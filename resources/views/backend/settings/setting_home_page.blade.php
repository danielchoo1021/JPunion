@extends('layouts.admin_app')
@section('content')
<div class="form-group">
	<form method="POST" action="{{ route('save_setting_home_page') }}" id="setting-merchant-form" enctype="multipart/form-data">
		@csrf
		<div class="big-parent pb-5">
			<div class="form-group">
				<div class="row">
					<div class="col-12">
						<div class="form-group container-box">
							<h4>
								{{ isset($data['backendlang']['backendlang']['Highlight_First_Image']) ? $data['backendlang']['backendlang']['Highlight_First_Image'] :'' }}
							</h4>
							<hr>
							<div class="row">
								<div class="col-sm-12">
									<input type="file" name="image[1]" class="form-control" accept="image/*">
									@if(!empty($home_page[1]->image))
									<img src="{{ asset($home_page[1]->image) }}" style="width: 70px;">
									@endif
								</div>
							</div>
						</div>
					</div>
					<div class="col-12">
						<div class="container-box">
							<h4>
								{{ isset($data['backendlang']['backendlang']['First_Description_EN']) ? $data['backendlang']['backendlang']['First_Description_EN'] :'' }}
							</h4>
							<hr>
							<div class="form-group">
								<textarea class="form-control" name="description[1]" id="description_first">{!! (!empty($home_page[1]->description)) ? $home_page[1]->description : '' !!}</textarea>
							</div>
						</div>
					</div>
					<div class="col-12">
						<div class="container-box">
							<h4>
								{{ isset($data['backendlang']['backendlang']['First_Description_CN']) ? $data['backendlang']['backendlang']['First_Description_CN'] :'' }}
							</h4>
							<hr>
							<div class="form-group">
								<textarea class="form-control" name="description_cn[1]" id="description_cn_first">{!! (!empty($home_page[1]->description_cn)) ? $home_page[1]->description_cn : '' !!}</textarea>
							</div>
						</div>
					</div>
				</div>
			</div>
			<hr>
			<div class="form-group pt-4">
				<div class="row">
					<div class="col-12">
						<div class="form-group container-box">
							<h4>
								{{ isset($data['backendlang']['backendlang']['Highlight_Second_Image']) ? $data['backendlang']['backendlang']['Highlight_Second_Image'] :'' }}
							</h4>
							<hr>
							<div class="row">
								<div class="col-sm-12">
									<input type="file" name="image[2]" class="form-control" accept="image/*">
									@if(!empty($home_page[2]->image))
									<img src="{{ asset($home_page[2]->image) }}" style="width: 70px;">
									@endif
								</div>
							</div>
						</div>
					</div>
					<div class="col-12">
						<div class="container-box">
							<h4>
								{{ isset($data['backendlang']['backendlang']['Second_Description_EN']) ? $data['backendlang']['backendlang']['Second_Description_EN'] :'' }}
							</h4>
							<hr>
							<div class="form-group">
								<textarea class="form-control" name="description[2]" id="description_second">{!! (!empty($home_page[2]->description)) ? $home_page[2]->description : '' !!}</textarea>
							</div>
						</div>
					</div>
					<div class="col-12">
						<div class="container-box">
							<h4>
								{{ isset($data['backendlang']['backendlang']['Second_Description_CN']) ? $data['backendlang']['backendlang']['Second_Description_CN'] :'' }}
							</h4>
							<hr>
							<div class="form-group">
								<textarea class="form-control" name="description_cn[2]" id="description_cn_second">{!! (!empty($home_page[2]->description_cn)) ? $home_page[2]->description_cn : '' !!}</textarea>
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

    	$('#setting-merchant-form').submit();
    });

	var description_firstUrl = '{{ route("CKEditorUploadImage", ["_token" => csrf_token()]) }}';

	var description_first = CKEDITOR.instances["description_first"];

    if(!description_first){
      	CKEDITOR.replace( 'description_first',{
          	filebrowserUploadUrl: description_firstUrl,
          	filebrowserUploadMethod: 'form'
      	});
  	}

	var description_secondUrl = '{{ route("CKEditorUploadImage", ["_token" => csrf_token()]) }}';

	var description_second = CKEDITOR.instances["description_second"];

	if(!description_second){
		CKEDITOR.replace( 'description_second',{
			filebrowserUploadUrl: description_secondUrl,
			filebrowserUploadMethod: 'form'
		});
	}

	var description_cn_firstUrl = '{{ route("CKEditorUploadImage", ["_token" => csrf_token()]) }}';

	var description_cn_first = CKEDITOR.instances["description_cn_first"];

    if(!description_cn_first){
      	CKEDITOR.replace( 'description_cn_first',{
          	filebrowserUploadUrl: description_cn_firstUrl,
          	filebrowserUploadMethod: 'form'
      	});
  	}

	var description_cn_secondUrl = '{{ route("CKEditorUploadImage", ["_token" => csrf_token()]) }}';

	var description_cn_second = CKEDITOR.instances["description_cn_second"];

	if(!description_cn_second){
		CKEDITOR.replace( 'description_cn_second',{
			filebrowserUploadUrl: description_cn_secondUrl,
			filebrowserUploadMethod: 'form'
		});
	}
</script>
@endsection