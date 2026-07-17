@extends('layouts.admin_app')

@section('content')
@if($errors->any())
  <div class="alert alert-danger">{!! implode('<br/>', $errors->all(':message')) !!}</div>
@endif
<form method="POST" action="{{ route('member.members.store') }}" id="agent-form" enctype="multipart/form-data">
@csrf
@include('backend.members.form')
</form>

<div class="submit-form-btn">
    <div class="form-group wizard-actions" align="right">
        <a href="{{ route('member.members.index') }}" class="btn btn-outline-danger">
            <i class="bi bi-ban"> {{ isset($data['backendlang']['backendlang']['Cancel']) ? $data['backendlang']['backendlang']['Cancel'] :'' }}</i>
        </a>

        <button class="btn btn-outline-primary">
            <i class="bi bi-check"> {{ isset($data['backendlang']['backendlang']['Save_Changes']) ? $data['backendlang']['backendlang']['Save_Changes'] :'' }}</i>
        </button>
    </div>
</div>

@endsection

@section('js')
<link href = "https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" rel="stylesheet">
<script type="text/javascript">
    $('#agent-form .required-field').change( function(){
        if($(this).val()){
            $(this).removeClass('required-feild-error');
        }
    });

    $('.submit-form-btn .btn-outline-primary').click( function(e){
       e.preventDefault();
       $('.loading-gif').show();

        var email = $('.email').val();

        if(IsEmail(email)==false){
            toastr.error("{{ isset($data['backendlang']['backendlang']['Please_Enter_Email_Format']) ? $data['backendlang']['backendlang']['Please_Enter_Email_Format'] :'' }}");
            $('.loading-gif').hide();
            return false;
        }
       $('#agent-form').submit();

       var phone = $('input[name="phone"]').val();
       var code = $('input[name="code"]').val();
       var empty_fill = 0;
       $('#agent-form .required-field').each( function(){
            if(!$(this).val()){
                $(this).addClass('required-feild-error');
                empty_fill = 1;
            }
        });

       if(empty_fill == 1){
          $('.loading-gif').hide();
          return false;
       }

       if(!phone){
            $('#action-return-message').addClass('important-text');
            $('#action-return-message').html("{{ isset($data['backendlang']['backendlang']['Please_fill_in_phone_number']) ? $data['backendlang']['backendlang']['Please_fill_in_phone_number'] :'' }}");
            $('.loading-gif').hide();
            return false;
       }else{
          // if(phone.length < 10){
          //       $('#action-return-message').addClass('important-text');
          //       $('#action-return-message').html("Please fill in valid phone number");
          //       return false;
          // }
       }

       if(!code){
            $('#action-return-message').addClass('important-text');
            $('#action-return-message').html("{{ isset($data['backendlang']['backendlang']['Please_fill_in_valid_verification_code']) ? $data['backendlang']['backendlang']['Please_fill_in_valid_verification_code'] :'' }}");
            $('.loading-gif').hide();
            return false;
       }

       var fd = new FormData();
       fd.append('phone', phone);
       fd.append('code', code);
       fd.append('country_code', '1');

       

        
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

    $('.date-picker').datepicker({
        autoclose: true,
        todayHighlight: true,
        changeMonth: true,
        changeYear: true,
        yearRange: "-100:+0",
        dateFormat: 'dd/mm/yy'
    });
</script>
@endsection