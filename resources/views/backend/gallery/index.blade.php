@extends('layouts.admin_app')

@section('content')
	<div class="page-header">
	    <h1>
	        {{ isset($data['backendlang']['backendlang']['Gallery']) ? $data['backendlang']['backendlang']['Gallery'] :'' }}
	        <small>
	            <!-- <i class="ace-icon fa fa-angle-double-right"></i>
	            @if(Auth::check())
	            {{ Auth::user()->f_name }} 
	            @endif
	        </small> -->
	    </h1>
	</div>
    <!-- <div class="form-group">
        <a href="{{ route('gallery_list', '1') }}">
            <span class="label label-success" style="">
                EVPAD 3S
            </span>
        </a>

        <a href="{{ route('gallery_list', '2') }}">
            <span class="label label-success" style="">
                EVPAD 3R
            </span>
        </a>
    </div> -->
    <div class="form-group">
        <h4>{{ isset($data['backendlang']['backendlang']['Add_New_Title']) ? $data['backendlang']['backendlang']['Add_New_Title'] :'' }}</h4>
        <div class="title-list">
            @foreach($categories as $category)
                <div class="row title-details">
                    <div class="col-md-4">
                        <div class="form-group">
                            <input type="hidden" name="cid" value="{{ $category->id }}">
                            <input type="text" name="title" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['Title']) ? $data['backendlang']['backendlang']['Title'] :'' }}" value="{{ $category->category_name }}">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <a href="#" class="del important-text">
                                <i class="fa fa-trash fa-2x"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
            <div class="row title-details">
                <div class="col-md-4">
                    <div class="form-group">
                        <input type="hidden" name="cid">
                        <input type="text" name="title" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['Title']) ? $data['backendlang']['backendlang']['Title'] :'' }}">
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <a href="#" class="del important-text">
                            <i class="fa fa-trash fa-2x"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <button class="btn btn-block btn-outline-primary add-new-row">
                        {{ isset($data['backendlang']['backendlang']['Add_New_Row']) ? $data['backendlang']['backendlang']['Add_New_Row'] :'' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
    <hr>
	<div class="form-group gallery product-image-list">
		<div class="row">
			@foreach($images as $image)
				<div class="col-md-2">
                    <div class="form-group product-image-thumbnail" style="position: relative; overflow: hidden;">
                        <div class="delete-image-box">
                            <a href="#" class="delete-image" data-id="{{ $image->id }}">
                                <i class="fa fa-trash"></i>
                            </a>
                        </div>
                        <div class="product-image-thumbnail-img" style="background-image: url({{ asset($image->image)  }})"></div>
                    </div>
                    <div class="form-group category-option">
                    	<select class="form-control category_id" name="category_id">
                            <option> {{ isset($data['backendlang']['backendlang']['Select_Title']) ? $data['backendlang']['backendlang']['Select_Title'] :'' }} </option>
                            @foreach($categories as $category)
                            <option {{ $image->category_id == $category->id ? 'selected' : '' }} value="{{ $category->id }}">{{ $category->category_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                    	<input type="text" class="form-control" name="description" value="{{ $image->description }}" placeholder="{{ isset($data['backendlang']['backendlang']['Description']) ? $data['backendlang']['backendlang']['Description'] :'' }}">
                    </div>
                </div>
			@endforeach
		</div>
	</div>
	<div class="row">
		<div class="col-12">
			<div>
				<form method="POST" action="{{ route('uploadGalleryImage') }}" class="dropzone well" id="dropzone" enctype="multipart/form-data">
					@csrf
					<div class="fallback">
						<input name="file" type="file" multiple="" accept="image/*" />
					</div>
				</form>
			</div>

			<div id="preview-template" class="hide">
				<div class="dz-preview dz-file-preview">
					<div class="dz-image">
						<img data-dz-thumbnail="" />
					</div>

					<div class="dz-details">
						<div class="dz-size">
							<span data-dz-size=""></span>
						</div>

						<div class="dz-filename">
							<span data-dz-name=""></span>
						</div>
					</div>

					<div class="dz-progress">
						<span class="dz-upload" data-dz-uploadprogress=""></span>
					</div>

					<div class="dz-error-message">
						<span data-dz-errormessage=""></span>
					</div>

					<div class="dz-success-mark">
						<span class="fa-stack fa-lg bigger-150">
							<i class="fa fa-circle fa-stack-2x white"></i>

							<i class="fa fa-check fa-stack-1x fa-inverse green"></i>
						</span>
					</div>

					<div class="dz-error-mark">
						<span class="fa-stack fa-lg bigger-150">
							<i class="fa fa-circle fa-stack-2x white"></i>

							<i class="fa fa-remove fa-stack-1x fa-inverse red"></i>
						</span>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('js')
<script type="text/javascript">
	jQuery(function($){
            
        try {
            
            var myDropzone = Dropzone.options.dropzone =
            {

                maxFilesize: 12,
                renameFile: function(file) {
                    var dt = new Date();
                    var time = dt.getTime();
                   return time+file.name;
                },
                acceptedFiles: ".jpeg,.jpg,.png,.gif",
                timeout: 5000,
                dictRemoveFile: 'Remove',
                
                dictDefaultMessage :
                '<span class="bigger-150 bolder"><i class="ace-icon fa fa-caret-right red"></i> {{ isset($data["backendlang"]["backendlang"]["dnd_files"]) ? $data["backendlang"]["backendlang"]["dnd_files"] :'' }} </span> {{ isset($data["backendlang"]["backendlang"]["To_Upload"]) ? $data["backendlang"]["backendlang"]["To_Upload"] :'' }} \
                <span class="smaller-80 grey"> {{ isset($data["backendlang"]["backendlang"]["(or Click)"]) ? $data["backendlang"]["backendlang"]["(or Click)"] :'' }} </span> <br /> \
                <i class="upload-icon ace-icon fa fa-cloud-upload blue fa-3x"></i>',
                
                thumbnail: function(file, dataUrl) {
                  if (file.previewElement) {
                    $(file.previewElement).removeClass("dz-file-preview");
                    var images = $(file.previewElement).find("[data-dz-thumbnail]").each(function() {
                        var thumbnailElement = this;
                        thumbnailElement.alt = file.name;
                        thumbnailElement.src = dataUrl;
                    });
                    setTimeout(function() { $(file.previewElement).addClass("dz-image-preview"); }, 1);

                  }
                },
                success: function(file, response) 
                {
                    refreshImageList();
                },
            };
        
          //simulating upload progress
          var minSteps = 6,
              maxSteps = 60,
              timeBetweenSteps = 100,
              bytesPerStep = 100000;
        
          
        
           
           //remove dropzone instance when leaving this page in ajax mode
           $(document).one('ajaxloadstart.page', function(e) {
                try 
                {
                    myDropzone.destroy();
                } catch(e) {}
           });
        
        } catch(e) {
          alert("{{ isset($data['backendlang']['backendlang']['Dropzone.js does not support older browsers!']) ? $data['backendlang']['backendlang']['Dropzone.js does not support older browsers!'] :'' }}");
        }
        
    });

    $('.product-image-list').on('click', '.product-image-thumbnail .delete-image', function(e){
        e.preventDefault();
        var delete_btn = $(this);
        if(confirm("{{ isset($data['backendlang']['backendlang']['Delete_This_Image']) ? $data['backendlang']['backendlang']['Delete_This_Image'] :'' }}") == true){
            var url = '{{ route("DeleteImage", ":id") }}';
            url = url.replace(':id', $(this).data('id'));
            $.ajax({
                url: url,
                type: 'get',
                success: function(response){
                    delete_btn.closest('.product-image-thumbnail').parent().remove();
                },
            });
        }else{
            return false;
        }
    });

    $('.product-image-list').on('change', '.category_id', function(){

    	var id = $(this).closest('.col-md-2').find('.delete-image-box .delete-image').data('id');
    	var description = $(this).closest('.col-md-2').find('input[name="description"]').val();
    	var fd = new FormData();
		fd.append('category_id', $(this).val());
		fd.append('description', description);
		fd.append('id', id);

		$.ajax({
	       url: '{{ route("updateImageContent") }}',
	       type: 'post',
	       data: fd,
	       contentType: false,
	       processData: false,
	       success: function(response){
	       		toastr.success("{{ isset($data['backendlang']['backendlang']['Content_Updated']) ? $data['backendlang']['backendlang']['Content_Updated'] :'' }}");
	       },
	    });
    });

    $('.product-image-list').on('change', 'input[name="description"]', function(){

    	var id = $(this).closest('.col-md-2').find('.delete-image-box .delete-image').data('id');
    	var category_id = $(this).closest('.col-md-2').find('.category_id').val();
    	var fd = new FormData();
		fd.append('category_id', category_id);
		fd.append('description', $(this).val());
		fd.append('id', id);

		$.ajax({
	       url: '{{ route("updateImageContent") }}',
	       type: 'post',
	       data: fd,
	       contentType: false,
	       processData: false,
	       success: function(response){
	       		toastr.success("{{ isset($data['backendlang']['backendlang']['Content_Updated']) ? $data['backendlang']['backendlang']['Content_Updated'] :'' }}");
	       },
	    });
    });

    $('.add-new-row').click( function(){
        var a = '<div class="row title-details">\
                    <div class="col-md-4">\
                        <div class="form-group">\
                            <input type="hidden" name="cid">\
                            <input type="text" name="title" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['Title']) ? $data['backendlang']['backendlang']['Title'] :'' }}">\
                        </div>\
                    </div>\
                    <div class="col-md-1">\
                        <div class="form-group">\
                            <a href="#" class="del important-text">\
                                <i class="fa fa-trash fa-2x"></i>\
                            </div>\
                        </div>\
                    </div>\
                </div>';
        $('.title-list').append(a);
    });

    $('.title-list').on('click', '.del', function(e){
        e.preventDefault();
        var id = $(this).closest('.title-details').find('input[name="cid"]').val();

        
        if(id){
            var fd = new FormData();
                fd.append('id', id);
            $.ajax({
               url: '{{ route("DeleteTitle") }}',
               type: 'post',
               data: fd,
               contentType: false,
               processData: false,
               success: function(response){
                    toastr.error("{{ isset($data['backendlang']['backendlang']['Title_Deleted']) ? $data['backendlang']['backendlang']['Title_Deleted'] :'' }}");
               },
            });            
        }
        $(this).closest('.title-details').remove();
    });

    $('.title-list').on('change', 'input[name="title"]', function(){
        var ele = $(this);
        var id = $(this).closest('.title-details').find('input[name="cid"]').val();

        var fd = new FormData();
            fd.append('category_name', $(this).val());
            fd.append('id', id);

        $.ajax({
           url: '{{ route("AddTitle") }}',
           type: 'post',
           data: fd,
           contentType: false,
           processData: false,
           success: function(response){

            if(!id){
                toastr.success("{{ isset($data['backendlang']['backendlang']['Title_Added']) ? $data['backendlang']['backendlang']['Title_Added'] :'' }}");
                ele.closest('.title-details').find('input[name="cid"]').val(response);
            }else{
                toastr.success("{{ isset($data['backendlang']['backendlang']['Title_Updated']) ? $data['backendlang']['backendlang']['Title_Updated'] :'' }}");
            }
                refreshImageList();
           },
        });
    });

    function refreshImageList(){

        $.ajax({
           url: '{{ route("refreshImageList") }}',
           type: 'get',
           success: function(response){

                $('.gallery .row').html(response);
           },
        });
    }
</script>
@endsection