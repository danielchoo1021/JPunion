@extends('layouts.admin_app')

@section('content')
<div class="form-group">
	<div class="row">
		<div class="col-sm-12">
			<div class="form-group product-image-list">
				<div class="row" id="imageListId">
					
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
				<form method="POST" action="{{ route('uploadBannerImage') }}" class="dropzone well" id="dropzone" enctype="multipart/form-data">
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
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<script type="text/javascript">
	var url = '{{ route("LoadBannerImage") }}';
    
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
        if(confirm("{{ isset($data['backendlang']['backendlang']['Delete_This_Image']) ? $data['backendlang']['backendlang']['Delete_This_Image'] :'' }}") == true){
            var url = '{{ route("DeleteBannerImage", ":id") }}';
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

    $(document).on('change', '.banner_url', function(e){
        e.preventDefault();
        var ele = $(this);

        var url = ele.val();
        var bid = ele.data('id');

        var fd = new FormData();
            fd.append('url', url);
            fd.append('bid', bid);

        $.ajax({
           url: '{{ route("changeBannerUrl") }}',
           type: 'post',
           data: fd,
           contentType: false,
           processData: false,
           success: function(response){
                toastr.success("{{ isset($data['backendlang']['backendlang']['Update_Successful']) ? $data['backendlang']['backendlang']['Update_Successful'] :'' }}");
           },
        }); 
    });

    $(function() { 
        $("#imageListId").sortable({ 
            update: function(event, ui) { 
                    getIdsOfImages();
                } //end update 
        }); 
    }); 

    function getIdsOfImages() { 
        $('.loading-gif').show();
        var values = []; 
        var  a = 0;
        $('.product-image-thumbnail').each(function(index) { 
            a++;
            var mid = $(this).data('id');

            var fd = new FormData();
                fd.append('mid', mid);
                fd.append('number', a);

            // alert(mid);
            $.ajax({
               url: '{{ route("SortBanner") }}',
               type: 'post',
               data: fd,
               contentType: false,
               processData: false,
               success: function(response){
                    $('.loading-gif').hide();
                    toastr.success("{{ isset($data['backendlang']['backendlang']['Updated']) ? $data['backendlang']['backendlang']['Updated'] :''}}");
               },
            });
        }); 
        
    }
</script>
@endsection