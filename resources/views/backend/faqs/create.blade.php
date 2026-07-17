@extends('layouts.admin_app')

@section('content')
@if($errors->any())
  <div class="alert alert-danger">{!! implode('<br/>', $errors->all(':message')) !!}</div>
@endif
<form method="POST" action="{{ route('setting_all_faq.setting_all_faqs.store') }}" id="agent-form" enctype="multipart/form-data">
@csrf
@include('backend.faqs.form')
</form>

@if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['faqs-insert']))
<div class="submit-form-btn">
    <div class="form-group wizard-actions" align="right">
        <a href="{{ route('setting_all_faq.setting_all_faqs.index') }}" class="btn btn-outline-danger">
            <i class="bi bi-ban"> {{ isset($data['backendlang']['backendlang']['Cancel']) ? $data['backendlang']['backendlang']['Cancel'] :'' }}</i>
        </a>

        <button class="btn btn-outline-primary">
            <i class="bi bi-check"> {{ isset($data['backendlang']['backendlang']['Save_Changes']) ? $data['backendlang']['backendlang']['Save_Changes'] :'' }}</i>
        </button>

    </div>
</div>
@endif

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
</script>

@endsection