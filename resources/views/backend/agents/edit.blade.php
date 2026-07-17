@extends('layouts.admin_app')

@section('content')
@if($errors->any())
  <div class="alert alert-danger">{!! implode('<br/>', $errors->all(':message')) !!}</div>
@endif
<form method="POST" action="{{ route('agent.agents.update', $agent->id) }}" id="agent-form" enctype="multipart/form-data">
@csrf
@method('PUT')
@include('backend.agents.form')
</form>

<div
    class="modal fade text-left"
    id="primary"
    tabindex="-1"
    role="dialog"
    aria-labelledby="myModalLabel160"
    aria-hidden="true"
>
    <div
        class="modal-dialog modal-dialog-centered modal-dialog-scrollable"
        role="document"
    >
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5
                    class="modal-title white"
                    id="myModalLabel160"
                >
                    {{ isset($data['backendlang']['backendlang']['Change_New_Password']) ? $data['backendlang']['backendlang']['Change_New_Password'] :'' }}
                </h5>
                <button
                    type="button"
                    class="close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                >
                    <i data-feather="x"></i>
                </button>
            </div>
            <form method="POST" action="{{ route('saveAgentNewPassword', [$agent->id]) }}" id="change_password-form">
                @csrf
                <div class="modal-body">
                      <div class="form-group">
                          <label>{{ isset($data['backendlang']['backendlang']['New_Password']) ? $data['backendlang']['backendlang']['New_Password'] :'' }}</label>
                          <input type="text" name="new_password" class="form-control">
                      </div>
                      <div class="form-group">
                          <label>{{ isset($data['backendlang']['backendlang']['Confirm_New_Password']) ? $data['backendlang']['backendlang']['Confirm_New_Password'] :'' }}</label>
                          <input type="text" name="password_confirmation" class="form-control">
                      </div>
                </div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-light-secondary"
                        data-bs-dismiss="modal"
                    >
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block"
                        >{{ isset($data['backendlang']['backendlang']['Close']) ? $data['backendlang']['backendlang']['Close'] :'' }}</span
                        >
                    </button>
                    <button
                        type="button"
                        class="btn btn-primary save-password ms-1"
                        data-bs-dismiss="modal"
                    >
                        <i
                            class="bx bx-check d-block d-sm-none"
                        ></i>
                        <span class="d-none d-sm-block"
                            >{{ isset($data['backendlang']['backendlang']['Save_Password']) ? $data['backendlang']['backendlang']['Save_Password'] :'' }}</span
                        >
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="submit-form-btn">
	<div class="form-group wizard-actions" align="right">
		<a href="{{ route('agent.agents.index') }}" class="btn btn-outline-danger">
			<i class="bi bi-ban"> {{ isset($data['backendlang']['backendlang']['Cancel']) ? $data['backendlang']['backendlang']['Cancel'] :'' }}</i>
		</a>

        @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['agent-edit']))
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
    $('.submit-form-btn .btn-outline-primary').click( function(e){
    	   e.preventDefault();
    	   $('.loading-gif').show();

           var email = $('.email').val();

            if(IsEmail(email)==false){
                toastr.error("{{ isset($data['backendlang']['backendlang']['Please_Enter_Email_Format']) ? $data['backendlang']['backendlang']['Please_Enter_Email_Format'] :'' }}");
                return false;
            }
    	   $('#agent-form').submit();
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

    $('.save-password').click( function(e){
        e.preventDefault();

        var new_password = $('input[name="new_password"]').val();
        var con_password = $('input[name="password_confirmation"]').val();

        if(new_password == con_password){
            
            $('#change_password-form').submit();
        }else{
            alert("{{ isset($data['backendlang']['backendlang']['Password_Not_Match']) ? $data['backendlang']['backendlang']['Password_Not_Match'] :'' }}");
            return false;
        }
    });

    $('.save-personal').click( function(e){
        e.preventDefault();

        $('#change_account_personal-form').submit();
    });

    $('.save-company').click( function(e){
        e.preventDefault();

        $('#change_account_company-form').submit();
    });

    $('.save-bank').click( function(e){
        e.preventDefault();

        $('#save_bank_account-form').submit();
    });

    $('.agent_type').change( function(){
        var ele = $(this);

        if(ele.val() == '1'){
          // $('input[name="agent_pno"]').attr('readonly');
          $('input[name="agent_pno"]').val('{{ $agent->master_code }}');
          $('input[name="agent_pno"]').prop('readonly', true);
        }else{
          // $('input[name="agent_pno"]').removeAttr('readonly');
          $('input[name="agent_pno"]').prop('readonly', false);
        }
    });

    $('.change-new-password').click(function(e){
        var fd = new FormData();
            fd.append('mid', '{{ $agent->id }}');
        if(confirm("{{ isset($data['backendlang']['backendlang']['Confirm_Reset_Password']) ? $data['backendlang']['backendlang']['Confirm_Reset_Password'] :'' }}") == true){
            $.ajax({
                url: '{{ route("updatePassword") }}',
                type: 'post',
                data: fd,
                contentType: false,
                processData: false,
                success: function(response){
                    $('.loading-gif').hide();
                    toastr.success("{{ isset($data['backendlang']['backendlang']['Reset_Password_Successful']) ? $data['backendlang']['backendlang']['Reset_Password_Successful'] :'' }}");
                },
            });
        }
    });

    $('.copy-guest-link').click( function(e){
        e.preventDefault();
        
        var copyText = document.getElementById("guest_link");
            copyText.select();
            copyText.setSelectionRange(0, 99999)
            document.execCommand("copy");

        $(this).html('复制了');
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