@extends('layouts.admin_app')
@section('content')

<div class="row">
	<div class="col-12">
		<div role="tabpanel">

		  <!-- Nav tabs -->
		  <ul class="nav nav-tabs" role="tablist">
		    <li role="presentation" class="active">
		    	<a href="#home" aria-controls="home" role="tab" data-toggle="tab">
		    		 {{ isset($data['backendlang']['backendlang']['Knowledge_Photo']) ? $data['backendlang']['backendlang']['Knowledge_Photo'] :'' }}
		    	</a>
		    </li>
		    <li role="presentation">
		    	<a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">
		    		 {{ isset($data['backendlang']['backendlang']['Video']) ? $data['backendlang']['backendlang']['Video'] :'' }}
		    	</a>
		    </li>
		    <li role="presentation">
		    	<a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">
		    		 {{ isset($data['backendlang']['backendlang']['File']) ? $data['backendlang']['backendlang']['File'] :'' }}
		    	</a>
		    </li>
		  </ul>

		  <!-- Tab panes -->
		  <div class="tab-content">
		    <div role="tabpanel" class="tab-pane active" id="home">
		    	<div class="form-group product-image-list" id="1">
					<div class="row">
						
					</div>
					<div class="clear-both"></div>
				</div>
		    	<div>
					<form method="POST" action="{{ route('UploadMaterial', '1') }}" class="dropzone well" id="dropzone" enctype="multipart/form-data">
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
		    <div role="tabpanel" class="tab-pane" id="profile">
		    	<div class="form-group product-image-list" id="2">
					<div class="row">
						
					</div>
					<div class="clear-both"></div>
				</div>

		    	<div>
					<form method="POST" action="{{ route('UploadMaterial', '2') }}" class="dropzone well" id="dropzone" enctype="multipart/form-data">
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
		    <div role="tabpanel" class="tab-pane" id="messages">
		    	<div class="form-group product-image-list" id="3">
					<div class="row">
						
					</div>
					<div class="clear-both"></div>
				</div>
		    	<div>
					<form method="POST" action="{{ route('UploadMaterial', '3') }}" class="dropzone well" id="dropzone" enctype="multipart/form-data">
						@csrf
						<div class="fallback">
							<input name="file" type="file" multiple="" accept="image/*,application/pdf" />
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
	</div>
</div>
@endsection
@section('js')
<script type="text/javascript">
	$('.submit-form-btn .btn-outline-primary').click( function(e){
    	e.preventDefault();
    	
    	$('#setting-merchant-form').submit();
    });

	var url = '{{ route("LoadMaterialImage", "1") }}';

    $.ajax({
        url: url,
        type: 'get',
        success: function(response){
            $('#1 .row').html(response);
            
        },
    });


    var url2 = '{{ route("LoadMaterialImage", "2") }}';

    $.ajax({
        url: url2,
        type: 'get',
        success: function(response){
            $('#2 .row').html(response);
            
        },
    });


    var url3 = '{{ route("LoadMaterialImage", "3") }}';

    $.ajax({
        url: url3,
        type: 'get',
        success: function(response){
            $('#3 .row').html(response);
            
        },
    });

    jQuery(function($){
            
        try {
            
            var myDropzone = Dropzone.options.dropzone =
            {

                maxFilesize: 12000,
                renameFile: function(file) {
                    var dt = new Date();
                    var time = dt.getTime();
                   return time+file.name;
                },
                acceptedFiles: ".jpeg,.jpg,.png,.gif,.mp4,.pdf",
                timeout: 5000,
                dictRemoveFile: 'Remove',
                maxFiles: 100,
                
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
                    $('#'+response[1]+' .row').html(response[0]);
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

    $('#1,#2,#3').on('click', '.product-image-thumbnail .delete-image', function(e){
        e.preventDefault();
        var delete_btn = $(this);
        if(confirm("{{ isset($data['backendlang']['backendlang']['Delete_This_Image']) ? $data['backendlang']['backendlang']['Delete_This_Image'] :'' }}") == true){
            var url = '{{ route("DeleteMaterialImage", ":id") }}';
            url = url.replace(':id', $(this).data('id'));
            $.ajax({
                url: url,
                type: 'get',
                success: function(response){
                    delete_btn.closest('.product-image-thumbnail').hide();
                },
            });
        }else{
            return false;
        }
    });
</script>
@endsection