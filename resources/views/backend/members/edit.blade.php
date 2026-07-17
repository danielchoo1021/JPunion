@extends('layouts.admin_app')

@section('content')
@if($errors->any())
  <div class="alert alert-danger">{!! implode('<br/>', $errors->all(':message')) !!}</div>
@endif
<form method="POST" action="{{ route('member.members.update', $user->id) }}" id="agent-form" enctype="multipart/form-data">
@csrf
@method('PUT')
@include('backend.members.form')
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
            <form method="POST" action="{{ route('saveMemberNewPassword', [$user->id]) }}" id="change_password-form">
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

<div class="modal fade" id="personalModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      
      <h3>&nbsp;&nbsp;&nbsp;<i class="fa fa-user"></i> {{ isset($data['backendlang']['backendlang']['Change_To_Personal_Account']) ? $data['backendlang']['backendlang']['Change_To_Personal_Account'] :'' }}</h3>
      <hr>
      <form method="POST" action="{{ route('changeUserAccountPersonal', [$user->id]) }}" id="change_account_personal-form">
        @csrf
        <div class="modal-body">
              <div class="form-group">
                  <label><!-- 身份证号码 -->{{ isset($data['backendlang']['backendlang']['NRIC_no']) ? $data['backendlang']['backendlang']['NRIC_no'] :'' }}</label>
                  <input type="text" name="ic" class="form-control">
              </div>

              @php
                $selectedGender = (isset($user)) ? $user->gender : old('gender');
              @endphp
              <div class="form-group">
                  <label><!-- 性别 -->{{ isset($data['backendlang']['backendlang']['Gender']) ? $data['backendlang']['backendlang']['Gender'] :'' }}</label>
                  <select class="form-control" name="gender">
                    <option {{ ($selectedGender == 'Male') ? 'selected' : '' }} value="Male">
                        <!-- 男 -->{{ isset($data['backendlang']['backendlang']['Male']) ? $data['backendlang']['backendlang']['Male'] :'' }}
                    </option>
                    <option {{ ($selectedGender == 'Female') ? 'selected' : '' }} value="Female">
                        <!-- 女 -->{{ isset($data['backendlang']['backendlang']['Female']) ? $data['backendlang']['backendlang']['Female'] :'' }}
                    </option>
                </select>
              </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-danger" data-dismiss="modal">{{ isset($data['backendlang']['backendlang']['Close']) ? $data['backendlang']['backendlang']['Close'] :'' }}</button>
          <button type="button" class="btn btn-outline-primary save-personal">{{ isset($data['backendlang']['backendlang']['Save_Changes']) ? $data['backendlang']['backendlang']['Save_Changes'] :'' }}</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="companyModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      
      <h3>&nbsp;&nbsp;&nbsp;<i class="fa fa-briefcase"></i> {{ isset($data['backendlang']['backendlang']['Change_To_Company_Account']) ? $data['backendlang']['backendlang']['Change_To_Company_Account'] :'' }}</h3>
      <hr>
      <form method="POST" action="{{ route('changeUserAccountCompany', [$user->id]) }}" id="change_account_company-form">
        @csrf
        <div class="modal-body">
              <div class="form-group">
                  <label><!-- 公司注册码 -->{{ isset($data['backendlang']['backendlang']['Company_Registration_No']) ? $data['backendlang']['backendlang']['Company_Registration_No'] :'' }}</label>
                  <input type="text" name="company_registration_no" class="form-control required-field">
              </div>

              @php
                $companyAddress = (isset($user)) ? $user->company_address : old('company_address');
              @endphp
              <div class="form-group">
                  <label><!-- 公司地址 -->{{ isset($data['backendlang']['backendlang']['Company_Address']) ? $data['backendlang']['backendlang']['Company_Address'] :'' }}</label>
                  <textarea class="form-control required-field address" name="address" placeholder="{{ isset($data['backendlang']['backendlang']['Company_Address']) ? $data['backendlang']['backendlang']['Company_Address'] :'' }}">{{ $companyAddress }}</textarea>
              </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-danger" data-dismiss="modal">{{ isset($data['backendlang']['backendlang']['Close']) ? $data['backendlang']['backendlang']['Close'] :'' }}</button>
          <button type="button" class="btn btn-outline-primary save-company">{{ isset($data['backendlang']['backendlang']['Save_Changes']) ? $data['backendlang']['backendlang']['Save_Changes'] :'' }}</button>
        </div>
      </form>
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
<link href = "https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" rel="stylesheet">
<script type="text/javascript">
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

     $('.change-new-password').click(function(e){
        var fd = new FormData();
            fd.append('mid', '{{ $user->id }}');
        if(confirm("{{ isset($data['backendlang']['backendlang']['Confirm_Reset_Password']) ? $data['backendlang']['backendlang']['Confirm_Reset_Password'] :'' }}") == true){
            $.ajax({
                url: '{{ route("updateMemberPassword") }}',
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