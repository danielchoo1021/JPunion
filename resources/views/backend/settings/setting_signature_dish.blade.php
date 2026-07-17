@extends('layouts.admin_app')

@section('content')
<div class="page-header">
    <h1>
       {{ isset($data['backendlang']['backendlang']['Setting_Signature_Dish']) ? $data['backendlang']['backendlang']['Setting_Signature_Dish'] :'' }}
    </h1>
</div>

<div class="form-group">
	<div class="row">
		<div class="col-sm-12">
			<div class="form-group product-image-list">
				<div class="row">
					
				</div>
				<div class="clear-both"></div>
			</div>
			<!-- <div class="form-group">
				<form method="POST" action="" class="asdasd" id="upload_image_form" enctype="multipart/form-data">
					<input type="file" name="upload_image" id="upload_image" class="form-control" />
					<br />
		  			<div id="uploaded_image"></div>
				</form>
			</div> -->
			<div>
				<form method="POST" action="{{ route('uploadSignatureDishImage') }}" class="dropzone well" id="dropzone" enctype="multipart/form-data">
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
</div>
@endsection

@section('js')
<script type="text/javascript">
	var url = '{{ route("LoadSignatureDishImage") }}';
    
	$.ajax({
        url: url,
        type: 'get',
        success: function(response){
            $('.product-image-list .row').html(response);
            
        },
    });

	jQuery(function($){
            
        try {
            
            var myDropzone = Dropzone.options.dropzone =
            {

                
                renameFile: function(file) {
                    var dt = new Date();
                    var time = dt.getTime();
                   return time+file.name;
                },
                acceptedFiles: ".jpeg,.jpg,.png,.gif",
                timeout: 5000,
                dictRemoveFile: 'Remove',
                maxFiles: 100,
                dataType:'json',
                dictDefaultMessage :
               '<span class="bigger-150 bolder"><i class="ace-icon fa fa-caret-right red"></i> {{ isset($data['backendlang']['backendlang']['dnd_files']) ? $data['backendlang']['backendlang']['dnd_files'] :'' }} </span> {{ isset($data['backendlang']['backendlang']['To_Upload']) ? $data['backendlang']['backendlang']['To_Upload'] :'' }} \
                <span class="smaller-80 grey"> {{ isset($data['backendlang']['backendlang']['(or Click)']) ? $data['backendlang']['backendlang']['(or Click)'] :'' }} </span> <br /> \
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
                    $('.product-image-list .row').html(response);
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
          alert('{{ isset($data['backendlang']['backendlang']['Dropzone.js does not support older browsers!']) ? $data['backendlang']['backendlang']['Dropzone.js does not support older browsers!'] :'' }}');
        }
        
    });

    $('.product-image-list').on('click', '.product-image-thumbnail .delete-image', function(e){
        e.preventDefault();
        var delete_btn = $(this);
        if(confirm('{{ isset($data['backendlang']['backendlang']['Delete_This_Image']) ? $data['backendlang']['backendlang']['Delete_This_Image'] :'' }}') == true){
            var url = '{{ route("DeleteSignatureDishImage", ":id") }}';
            url = url.replace(':id', $(this).data('id'));
            $.ajax({
                url: url,
                type: 'get',
                success: function(response){
                    location.reload();
                    delete_btn.closest('.product-image-thumbnail').hide();
                },
            });
        }else{
            return false;
        }
    });
</script>
@endsection