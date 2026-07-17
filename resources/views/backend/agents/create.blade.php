@extends('layouts.admin_app')

@section('css')
<style type="text/css">
    .agent-referral-select + .select2-container .select2-selection--single {
        height: calc(1.5em + 0.75rem + 2px) !important;
        padding: 0.375rem 0.75rem !important;
        font-size: 1rem !important;
        font-weight: 100 !important;
        color: #495057 !important;
        background-color: #fff !important;
        border: 1px solid #ced4da !important;
        border-radius: 0.25rem !important;
    }
    .agent-referral-select + .select2-container .select2-selection--single .select2-selection__rendered {
        padding-left: 0 !important;
        padding-right: 20px !important;
    }
    .agent-referral-select + .select2-container .select2-selection--single .select2-selection__arrow {
        height: calc(1.5em + 0.75rem + 2px) !important;
        right: 1px !important;
    }
    .select2-results__option--highlighted[aria-selected] {
        background-color: #1560d1ff !important;
        color: white !important;
    }
</style>
@endsection

@section('content')
@if($errors->any())
  <div class="alert alert-danger">{!! implode('<br/>', $errors->all(':message')) !!}</div>
@endif
<form method="POST" action="{{ route('agent.agents.store') }}" id="agent-form" enctype="multipart/form-data">
@csrf
@include('backend.agents.form')
</form>

<div class="submit-form-btn">
    <div class="form-group wizard-actions" align="right">
        <a href="{{ route('agent.agents.index') }}" class="btn">
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

    $('.date-picker').datepicker({
        autoclose: true,
        todayHighlight: true,
        changeMonth: true,
        changeYear: true,
        yearRange: "-100:+0",
        dateFormat: 'dd/mm/yy'
    });

    $('select[name="agent_pno"]').select2({
        placeholder: "{{ isset($data['backendlang']['backendlang']['Referral_Code_(Agent Code)']) ? $data['backendlang']['backendlang']['Referral_Code_(Agent Code)'] :'Select Referral Code' }}",
        allowClear: true,
        width: '100%',
        minimumResultsForSearch: 0
    });
</script>
@endsection