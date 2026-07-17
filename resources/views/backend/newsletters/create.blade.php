@extends('layouts.admin_app')

@section('content')
@if($errors->any())
  <div class="alert alert-danger">{!! implode('<br/>', $errors->all(':message')) !!}</div>
@endif
<form method="POST" action="{{ route('newsletter.newsletters.store') }}" id="newsletter-form" enctype="multipart/form-data">
@csrf
@include('backend.newsletters.form')
</form>

<div class="submit-form-btn">
    <div class="form-group wizard-actions" align="right">
        <a href="{{ route('newsletter.newsletters.index') }}" class="btn btn-outline-danger">
            <i class="fa fa-ban"> {{ isset($data['backendlang']['backendlang']['Cancel']) ? $data['backendlang']['backendlang']['Cancel'] :'' }}</i>
        </a>

        <button class="btn btn-outline-primary">
            <i class="fa fa-check"> {{ isset($data['backendlang']['backendlang']['Submit']) ? $data['backendlang']['backendlang']['Submit'] :'' }}</i>
        </button>

    </div>
</div>

@endsection

@section('js')
<script type="text/javascript">
    var create_new_newsletterUrl = '{{ route("CKEditorUploadImage", ["_token" => csrf_token()]) }}';

    var create_new_newsletter = CKEDITOR.instances["create_new_newsletter"];

    if(!create_new_newsletter){
        CKEDITOR.replace( 'create_new_newsletter',{
            filebrowserUploadUrl: create_new_newsletterUrl,
            filebrowserUploadMethod: 'form'
        });
    }

    $('#newsletter-form .required-field').change( function(){
        if($(this).val()){
            $(this).removeClass('required-feild-error');
        }
    });

    $('.submit-form-btn .btn-outline-primary').click( function(e){
       e.preventDefault();
       $('.loading-gif').show();
       $('#newsletter-form').submit();

       var entry = $('input[name="new_newsletter"]').val();
       var empty_fill = 0;
       $('#newsletter-form .required-field').each( function(){
            if(!$(this).val()){
                $(this).addClass('required-feild-error');
                empty_fill = 1;
            }
        });

       if(empty_fill == 1){
          $('.loading-gif').hide();
          return false;
       }




       var fd = new FormData();
       fd.append('new_newsletter', entry);

       

        
    });
</script>

@endsection