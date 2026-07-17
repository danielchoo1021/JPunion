@extends('layouts.admin_app')
@section('content')
<div class="form-group">
	<form method="POST" action="{{ route('save_setting_home_video') }}" id="setting-merchant-form" enctype="multipart/form-data">
		@csrf
		<div class="big-parent pb-5">
			<div class="form-group">
				<div class="row">
					<div class="col-12">
						<div class="form-group container-box">
							<h4>
								{{ isset($data['backendlang']['backendlang']['First_Video']) ? $data['backendlang']['backendlang']['First_Video'] :'' }}
							</h4>
							<hr>
							<div class="row">
								<div class="col-sm-12">
									<input type="file" name="image[1]" class="form-control" accept="video/*">
									@if(!empty($video[1]->image))
										<video width="400" controls>
											<source src="{{ asset($video[1]->image) }}" type="video/mp4">
										</video>
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
								<textarea class="form-control" name="description[1]" id="description">{!! (!empty($video[1]->description)) ? $video[1]->description : '' !!}</textarea>
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
								<textarea class="form-control" name="description_cn[1]" id="description_cn">{!! (!empty($video[1]->description_cn)) ? $video[1]->description_cn : '' !!}</textarea>
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
								{{ isset($data['backendlang']['backendlang']['Second_Video']) ? $data['backendlang']['backendlang']['Second_Video'] :'' }}
							</h4>
							<hr>
							<div class="row">
								<div class="col-sm-12">
									<input type="file" name="image[2]" class="form-control" accept="video/*">
									@if(!empty($video[2]->image))
										<video width="400" controls>
											<source src="{{ asset($video[2]->image) }}" type="video/mp4">
										</video>
									@endif
								</div>
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

	var descriptionUrl = '{{ route("CKEditorUploadImage", ["_token" => csrf_token()]) }}';

	var description = CKEDITOR.instances["description"];

    if(!description){
      	CKEDITOR.replace( 'description',{
          	filebrowserUploadUrl: descriptionUrl,
          	filebrowserUploadMethod: 'form'
      	});
  	}

	var description_cnUrl = '{{ route("CKEditorUploadImage", ["_token" => csrf_token()]) }}';

	var description_cn = CKEDITOR.instances["description_cn"];

    if(!description_cn){
      	CKEDITOR.replace( 'description_cn',{
          	filebrowserUploadUrl: description_cnUrl,
          	filebrowserUploadMethod: 'form'
      	});
  	}
</script>
@endsection