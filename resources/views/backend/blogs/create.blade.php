@extends('layouts.admin_app')
<style type="text/css">
    .bootstrap-tagsinput {
        width: 100%;
        padding-top: 0;
        padding-bottom: 0;
    }
    .bootstrap-tagsinput input{
        border: none !important;
    }
    .bootstrap-tagsinput input{
        display: none;
    }

    .bootstrap-tagsinput:nth-child(2){
        display: none !important;
    }

    .bootstrap-tagsinput input[name="blog_tags"]{
        display: block;
    }
</style>
@section('content')
@if($errors->any())
  <div class="alert alert-danger">{!! implode('<br/>', $errors->all(':message')) !!}</div>
@endif
<form method="POST" action="{{ route('blog.blogs.store') }}" id="brands-form"  enctype="multipart/form-data">
@csrf
@include('backend.blogs.form')
</form>

@if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['blog-insert']))
<div class="submit-form-btn">
	<div class="form-group wizard-actions" align="right">
		<a href="{{ route('blog.blogs.index') }}" class="btn btn-outline-danger">
			<i class="bi bi-ban"> {{ isset($data['backendlang']['backendlang']['Cancel']) ? $data['backendlang']['backendlang']['Cancel'] :'' }}</i> 
		</a>

		<button class="btn btn-outline-primary">
			<i class="bi bi-check"> {{ isset($data['backendlang']['backendlang']['Create']) ? $data['backendlang']['backendlang']['Create'] :'' }}</i>
		</button>

	</div>
</div>
@endif
@endsection

@section('js')
<link href = "https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" rel="stylesheet">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script type="text/javascript">
	$('.submit-form-btn .btn-outline-primary').click( function(e){
    	e.preventDefault();
    	
    	$('#brands-form').submit();
    });

    var descriptionUrl = '{{ route("CKEditorUploadImage", ["_token" => csrf_token(), "p_id"=> ":p_id", "type" => "1" ]) }}';

    var description = CKEDITOR.instances["description"];
        descriptionUrl = descriptionUrl.replace(':p_id', '1');

    if(!description){
        CKEDITOR.replace( 'description',{
            filebrowserUploadUrl: descriptionUrl,
            filebrowserUploadMethod: 'form'
        });
    }

    var description_cnUrl = '{{ route("CKEditorUploadImage", ["_token" => csrf_token(), "p_id"=> ":p_id", "type" => "1" ]) }}';

    var description_cn = CKEDITOR.instances["description_cn"];
        description_cnUrl = description_cnUrl.replace(':p_id', '1');

    if(!description_cn){
        CKEDITOR.replace( 'description_cn',{
            filebrowserUploadUrl: description_cnUrl,
            filebrowserUploadMethod: 'form'
        });
    }

    $('.date-picker').datepicker({
        autoclose: true,
        todayHighlight: true,
        changeMonth: true,
        changeYear: true,
        yearRange: "-100:+0",
        dateFormat: 'dd/mm/yy'
    });

    var add_new_row = '<div class="form-group">\
                            <div class="row">\
                                <div class="col-2">\
                                    &nbsp;\
                                </div>\
                                <div class="col-3">\
                                    <input type="text" name="blog_tags[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['Tag_EN']) ? $data['backendlang']['backendlang']['Tag_EN'] :'' }}" value="">\
                                </div>\
                                <div class="col-3">\
                                    <input type="text" name="blog_tags_cn[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['Tag_CN']) ? $data['backendlang']['backendlang']['Tag_CN'] :'' }}" value="">\
                                </div>\
                            </div>\
                        </div>';

    $('.add-new-tag').click(function(e){
        e.preventDefault();
        $('.add-new-tag-list').append(add_new_row);
    });
</script>
@endsection