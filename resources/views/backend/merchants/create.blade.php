@extends('layouts.admin_app')

@section('content')
@if($errors->any())
  <div class="alert alert-danger">{!! implode('<br/>', $errors->all(':message')) !!}</div>
@endif
<form method="POST" action="{{ route('merchant.merchants.store') }}" id="agent-form" enctype="multipart/form-data">
@csrf
@include('backend.merchants.form')
</form>

<div class="submit-form-btn">
    <div class="form-group wizard-actions" align="right">
        <a href="{{ route('merchant.merchants.index') }}" class="btn 
        ">
            <i class="fa fa-ban"> {{ isset($data['backendlang']['backendlang']['Cancel']) ? $data['backendlang']['backendlang']['Cancel'] :'' }}</i>
        </a>

        @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['agent-insert']))
            <button class="btn btn-outline-primary">
                <i class="bi bi-check"> {{ isset($data['backendlang']['backendlang']['Save_Changes']) ? $data['backendlang']['backendlang']['Save_Changes'] :'' }}</i>
            </button>
        @endif

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

    $('.ic_type').click(function(e){
        var ele = $(this);

        var val = ele.val();

        if(val == 1){
            $('.ic').attr('placeholder', "{{ isset($data['lang']['lang']['nric_no']) ? $data['lang']['lang']['nric_no'] :'身份证号码 (NRIC No)'}} (Malaysia)");
        }else if(val == 2){
            $('.ic').attr('placeholder', 'Passport');
        }else{
            $('.ic').attr('placeholder', "{{ isset($data['lang']['lang']['nric_no']) ? $data['lang']['lang']['nric_no'] :'身份证号码 (NRIC No)'}} (Singapore)");
        }
    });
    

    var descriptionUrl = '{{ route("CKEditorUploadImage", ["_token" => csrf_token(), "p_id"=> ":p_id", "type" => "1" ]) }}';
        descriptionUrl = descriptionUrl.replace(':p_id', '1');

    var description = CKEDITOR.instances["description"];

    if(!description){
        CKEDITOR.replace( 'description',{
            filebrowserUploadUrl: descriptionUrl,
            filebrowserUploadMethod: 'form'
        });
    }

    $('.active_period').keyup(function(e){

        var ele = $(this);

        var fd = new FormData();
            fd.append('merchant', '{{ isset($merchant) ? $merchant->code : "" }}');
            fd.append('period', ele.val());

        $.ajax({
            url: '{{ route("get_merchant_expired_date") }}',
            type: 'post',
            data: fd,
            contentType: false,
            processData: false,
            success: function(response){
                $('.loading-gif').hide();
                // toastr.success('Reset password successful');
                $('.expired_date').html(response);
                $('.datepicker').val(response);
                console.log(response)
            },
        });
    });

    $('.datepicker').change(function(e){


        var ele = $(this);
        var selectedDate = new Date(ele.val());

        var today = new Date();
        var timeDiff = selectedDate.getTime() - today.getTime();
        var daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24));

        // console.log(daysDiff);

        var fd = new FormData();
            fd.append('merchant', '{{ isset($merchant) ? $merchant->code : "" }}');
            fd.append('period', daysDiff);

        $.ajax({
            url: '{{ route("get_merchant_expired_date") }}',
            type: 'post',
            data: fd,
            contentType: false,
            processData: false,
            success: function(response){
                $('.loading-gif').hide();
                // toastr.success('Reset password successful');
                $('.expired_date').html(response);
                // $('.datepicker').html(response);
                $('.active_period').val(daysDiff)
                // console.log(response)
            },
        });
    });
    
    $('.active_period').trigger('keyup');
</script>

@endsection