@extends('layouts.admin_app')

@section('content')
@if($errors->any())
  <div class="alert alert-danger">{!! implode('<br/>', $errors->all(':message')) !!}</div>
@endif
<form method="POST" action="{{ route('feedback.feedbacks.store') }}" id="agent-form" enctype="multipart/form-data">
@csrf
@include('backend.feedbacks.form')
</form>

<div class="form-group">
    <div class="row">
        <div class="col-sm-12">
            {{ isset($data['backendlang']['backendlang']['Image']) ? $data['backendlang']['backendlang']['Image'] :'' }}: 
        </div>
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
                <form method="POST" action="{{ route('uploadFeedbackImage', isset($feedback->id) ? $feedback->id : 0) }}" class="dropzone well" id="dropzone" enctype="multipart/form-data">
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

<div class="submit-form-btn">
    <div class="form-group wizard-actions" align="right">
        <a href="{{ route('member.members.index') }}" class="btn btn-outline-danger">
            <i class="fa fa-ban"> {{ isset($data['backendlang']['backendlang']['Cancel']) ? $data['backendlang']['backendlang']['Cancel'] :'' }}</i>
        </a>

        <button class="btn btn-outline-primary">
            <i class="fa fa-check"> {{ isset($data['backendlang']['backendlang']['Save_Changes']) ? $data['backendlang']['backendlang']['Save_Changes'] :'' }}</i>
        </button>

    </div>
</div>

@endsection

@section('js')
<script type="text/javascript">


    $('#agent-form .required-field').change( function(){
        if($(this).val()){
            $(this).removeClass('required-feild-error');
        }
    });

    $('.submit-form-btn .btn-outline-primary').click( function(e){
       e.preventDefault();
       $('.loading-gif').show();
       
       $('#agent-form').submit();        
    });

    $('.agent_type').change( function(){
        var ele = $(this);

        if(ele.val() == '1'){
          // $('input[name="agent_pno"]').attr('readonly');
          $('input[name="agent_pno"]').val('');
          $('input[name="agent_pno"]').prop('readonly', true);
        }else{
          // $('input[name="agent_pno"]').removeAttr('readonly');
          $('input[name="agent_pno"]').prop('readonly', false);
        }
    });

    $('.product-image-list').on('click', '.product-image-thumbnail .delete-image', function(e){
        e.preventDefault();
        var delete_btn = $(this);
        if(confirm('Delete This Image?') == true){
            var url = '{{ route("DeleteFeedBackImage", ":id") }}';
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