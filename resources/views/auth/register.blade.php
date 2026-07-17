@extends('layouts.app')
@section('css')
<style type="text/css">
    .cat_menu_container ul {
        display: block;
        position: absolute;
        top: 100%;
        left: 0;
        visibility: hidden;
        opacity: 0;
        min-width: 100%;
        background: #FFFFFF;
        box-shadow: 0px 10px 25px rgba(0,0,0,0.1);
        -webkit-transition: opacity 0.3s ease;
        -moz-transition: opacity 0.3s ease;
        -ms-transition: opacity 0.3s ease;
        -o-transition: opacity 0.3s ease;
        transition: all 0.3s ease;
    }
    .cat_menu_container:hover .cat_menu {
        visibility: visible;
        opacity: 1;
    }

    .select2-container{
        width: 100% !important;
    }

    .select2-container--default .select2-selection--single{
        padding: 5px;
        height: 39px;
    }

    .phone-area .nice-select{
        display: none;
    }

    .main-content{
        margin-top: 120px;
    }
</style>
@endsection
@section('content')
<div class="container">
    <div class="main-content">
        <div class="container-box">
            <form method="POST" action="{{ route('register') }}" id="register-form">
                @csrf
                <h3 align="center" class="header-login">
                    {{ isset($data['lang']['lang']['register_customer_account']) ? $data['lang']['lang']['register_customer_account'] :'创建顾客户口'}}
                </h3>
                <br>
                <div class="register-page">
                    <div class="form-group">
                        @if($errors->any())
                          <div class="alert alert-danger">{!! implode('<br/>', $errors->all(':message')) !!}</div>
                        @endif
                        <input type="hidden" name="role" value="1">
                    </div>
                    
                    <div class="form-group">
                        <label>{{ isset($data['lang']['lang']['complete_name']) ? $data['lang']['lang']['complete_name'] :'全名'}} ({{ isset($data['lang']['lang']['please_enter_name']) ? $data['lang']['lang']['please_enter_name'] :'请填写身份证上的名字'}})<span class="important-text">*</span></label>
                        <input type="text" class="form-control required-feild f_name" placeholder="{{ isset($data['lang']['lang']['complete_name']) ? $data['lang']['lang']['complete_name'] :'全名'}}" name="f_name" value="{{ old('f_name') }}">
                    </div>
                    
                    <!-- <div class="form-group">
                        <input type="text" class="form-control required-feild" placeholder="身份证" name="ic" value="{{ old('ic') }}">
                    </div> -->

                    <div class="form-group">
                        <label>{{ isset($data['lang']['lang']['email_address']) ? $data['lang']['lang']['email_address'] :'电子邮件'}} <span class="important-text">*</span></label>
                        <input type="text" class="form-control required-feild email" placeholder="{{ isset($data['lang']['lang']['email_address']) ? $data['lang']['lang']['email_address'] :'电子邮件'}}" name="email" value="{{ old('email') }}">
                    </div>

                    <div class="form-group phone-area">
                        <div class="row">
                            <div class="col-6">
                                <label>{{ isset($data['lang']['lang']['country']) ? $data['lang']['lang']['country'] :'国家编码'}} <span class="important-text">*</span></label>
                                <select class="form-control select2 country_code" name="country_code" id="country_code" data-live-search="true" style="padding: 0.375rem 0.75rem;">
                                    @foreach($countries as $country)
                                    <option value="{{ $country->country_contact }}"
                                        {{ ( $country->country_id == '160') ? 'selected' : '' }}
                                        > (+{{ $country->country_contact }}) {{ $country->country_name }} </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6">
                                <label>{{ isset($data['lang']['lang']['phone_number']) ? $data['lang']['lang']['phone_number'] :'电话号码'}} <span class="important-text">*</span></label>
                                <input type="text" class="form-control required-feild phone" placeholder="{{ isset($data['lang']['lang']['example']) ? $data['lang']['lang']['example'] :'例'}}: 121234567" name="phone" value="{{ old('phone') }}"  onkeypress="return isNumberKey(event)">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>{{ isset($data['lang']['lang']['gender']) ? $data['lang']['lang']['gender'] :'性别'}}: <span class="important-text">*</span></label>
                        <select class="form-control required-feild gender" name="gender" style="padding: 0.375rem 0.75rem;">
                            <option value="">
                                -
                            </option>
                            <option value="Male" {{ (old('gender') == 'Male') ? 'selected' : '' }}>
                                {{ isset($data['lang']['lang']['male']) ? $data['lang']['lang']['male'] :'男'}}
                            </option>
                            <option value="Female" {{ (old('gender') == 'Female') ? 'selected' : '' }}>
                                {{ isset($data['lang']['lang']['female']) ? $data['lang']['lang']['female'] :'女'}}
                            </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>{{ isset($data['lang']['lang']['date_of_birth']) ? $data['lang']['lang']['date_of_birth'] : '生日日期'}}: <span class="important-text">*</span></label>
                        <input type="text" class="form-control required-feild date-picker"  placeholder="Date of birth (dd/mm/yyyy)" name="dob" value="{{ old('dob') }}" readonly>
                    </div>

                    <div class="form-group">
                        <label>{{ isset($data['lang']['lang']['password']) ? $data['lang']['lang']['password'] :'密码'}} <span class="important-text">*</span></label>
                        <div class="input-group">
                            <span class="input-group-addon enable-password" 
                                  style="position: absolute; 
                                         right: 10px; 
                                         top: 10px; 
                                         z-index: 4;
                                         cursor: pointer;">
                                <i class="fa fa-eye"></i>
                            </span>
                            <input id="password" type="password" class="form-control password required-feild" name="password" placeholder="{{ isset($data['lang']['lang']['password']) ? $data['lang']['lang']['password'] :'密码'}}" value="{{ old('password') }}">
                        </div>
                        <!-- <input type="password" class="form-control required-feild password"  placeholder="{{ isset($data['lang']['lang']['password']) ? $data['lang']['lang']['password'] :'密码'}}" name="password" value="{{ old('password') }}"> -->
                    </div>
                    

                    <div class="form-group">
                        <label>{{ isset($data['lang']['lang']['confirm_password']) ? $data['lang']['lang']['confirm_password'] :'确认密码'}} <span class="important-text">*</span></label>
                        <!-- <input type="password" class="form-control required-feild"  placeholder="{{ isset($data['lang']['lang']['confirm_password']) ? $data['lang']['lang']['confirm_password'] :'确认密码'}}" name="password_confirmation" value="{{ old('password_confirmation') }}"> -->
                        <div class="input-group">
                            <span class="input-group-addon enable-confirm-password" 
                                  style="position: absolute; 
                                         right: 10px; 
                                         top: 10px; 
                                         z-index: 4;
                                         cursor: pointer;">
                                <i class="fa fa-eye"></i>
                            </span>
                            <input type="password" class="form-control password_confirmation required-feild" name="password_confirmation" placeholder="{{ isset($data['lang']['lang']['confirm_password']) ? $data['lang']['lang']['confirm_password'] :'确认密码'}}" value="{{ old('password_confirmation') }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>{{ isset($data['lang']['lang']['nric_no']) ? $data['lang']['lang']['nric_no'] :'身份证号码 (NRIC No)'}}<span class="important-text">*</span></label>
                        <input type="text" class="form-control ic ic-field required-feild"  placeholder="{{ isset($data['lang']['lang']['nric_no']) ? $data['lang']['lang']['nric_no'] :'身份证号码 (NRIC No)'}}" name="ic" value="{{ old('ic') }}" onkeypress="return isNumberKey(event)" maxlength="12">
                    </div>

                    @if(!empty(request('p')))
                        <div class="form-group">
                            <label>{{ isset($data['lang']['lang']['upline_name']) ? $data['lang']['lang']['upline_name'] :'推荐人名字'}} <span class="important-text">*</span></label>
                            <input type="text" class="form-control" value="{{ $refferer_name }}" placeholder="{{ isset($data['lang']['lang']['upline_name']) ? $data['lang']['lang']['upline_name'] :'推荐人名字'}}"
                                   readonly>
                        </div>

                        <label>{{ isset($data['lang']['lang']['upline_code']) ? $data['lang']['lang']['upline_code'] :'推荐人代码'}} <span class="important-text">*</span></label>
                        <div class="form-control" style="background-color: #e9ecef;">
                            {{ request('p') }}
                            <input type="hidden" name="master_id" class="master_id" value="{{ request('p') }}">
                        </div>
                    @else
                        <div class="form-group">
                            <label>{{ isset($data['lang']['lang']['upline_name']) ? $data['lang']['lang']['upline_name'] :'推荐人名字'}} <span class="important-text">*</span></label>
                            <input type="text" class="form-control" value="{{ $data['admin']->f_name }} {{ $data['admin']->l_name }}" placeholder="{{ isset($data['lang']['lang']['upline_name']) ? $data['lang']['lang']['upline_name'] :'推荐人名字'}}"
                                   readonly>
                        </div>

                        <div class="form-group">
                            <label>{{ isset($data['lang']['lang']['upline_code']) ? $data['lang']['lang']['upline_code'] :'推荐人代码'}} <span class="important-text">*</span></label>
                            <input type="text" name="master_id" class="form-control master_id" value="{{ $data['admin']->code }}" placeholder="{{ isset($data['lang']['lang']['upline_code']) ? $data['lang']['lang']['upline_code'] :'推荐人代码'}}"
                                   readonly>
                        </div>
                    @endif

                    <div class="form-group">
                        <div id="action-return-message"></div>
                    </div>

                    <div class="form-group">
                        <b id="error-message" class="important-text error-message"></b>
                    </div>
                    <!-- <div class="form-group">
                        <label class="checkbox-style">
                            <input type="checkbox" class="checking-age-18"> 已超过18岁?
                        </label>
                    </div> -->
                    <div class="form-group">
                        <label class="checkbox-style">
                            <input type="checkbox" class="checking-pv" style="display: initial; vertical-align: middle;"> 
                            <a href="#" class="privacy_policy_description" data-toggle="modal" data-target="#pv">
                                By signing up, I agree to the {{ $data['web_setting']->website_name }}'s Privacy Policy.
                            </a>
                            <div class="modal fade" id="pv" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="z-index: 9999;">
                                <div class="modal-dialog" style="background-color: #fff;">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">{{ isset($data['lang']['lang']['privacy_policy']) ? $data['lang']['lang']['privacy_policy'] :'隐私政策'}}</h5>
                                            <a href="#" class="close-modal">
                                                X
                                            </a>
                                        </div>
                                        <div class="modal-body">
                                            <div class="single_post_text text-editor-image pb-4">
                                                {!! $data['web_setting']->privacy_policy_description !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary btn-block register-submit-button set_button set_text">
                            {{ isset($data['lang']['lang']['create_account']) ? $data['lang']['lang']['create_account'] :'创建户口'}}
                        </button>
                    </div>

                    <!-- <div class="form-group">
                        <a href="{{ asset('/auth/redirect/facebook') }}" class="btn btn-primary btn-block facebook-login-button">
                            <i class="fa fa-facebook-square"></i> CONTINUE WITH FACEBOOK
                        </a>
                    </div> -->

                    <div class="form-group" align="center">
                        {{ isset($data['lang']['lang']['already_have_account']) ? $data['lang']['lang']['already_have_account'] :'已有帐号'}}? <a href="{{ route('login') }}">{{ isset($data['lang']['lang']['login']) ? $data['lang']['lang']['login'] :'登录'}}</a>
                    </div>

                    <div class="form-group" align="center">
                        {{ isset($data['lang']['lang']['be_an_agent']) ? $data['lang']['lang']['be_an_agent'] :'代理注册'}}? <a href="{{ route('merchant_register') }}">{{ isset($data['lang']['lang']['register_as_agent']) ? $data['lang']['lang']['register_as_agent'] :'注册为代理'}}</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<br>

<div class="modal fade" id="exampleSecondModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="z-index: 9999;">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="background-color: #fff;">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{ isset($data['lang']['lang']['confirm_details']) ? $data['lang']['lang']['confirm_details'] :'确认资料'}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body confirm-details">
            
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-shadow" data-dismiss="modal">
            {{ isset($data['lang']['lang']['close']) ? $data['lang']['lang']['close'] :'关闭'}}
        </button>
        <button type="button" class="btn btn-shadow register-btn">
            {{ isset($data['lang']['lang']['confirm']) ? $data['lang']['lang']['confirm'] :'确认'}}
        </button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('js')
<link href = "https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" rel="stylesheet">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">
    $('.register-submit-button').click(function(e){
        e.preventDefault();

        // var ageCheck = $('.checking-age-18').prop('checked');
        var pvCheck = $('.checking-pv').prop('checked');

        var empty_fill;
        var code = $('input[name="code"]').val();
        var country_code = $('.country_code').val();
        // var refferal_code = $('input[name="refferal_code"]').val();
        var ageCheck = $('.checking-age-18').prop('checked');
        var pvCheck = $('.checking-pv').prop('checked');

        var f_name = $('.f_name').val();
        // var email = $('.email').val();
        var email = $('.email').val().trim().replace(/\s+/g, '');
        var country_code = $('.country_code').val();
        var phone = $('.phone').val();
        var prefer_language = $('.prefer_language').val();
        var gender = $('.gender').val();
        var ic = $('.ic').val();
        var master_id = $('.master_id').val();
        var password = $('.password').val();
        var password_confirmation = $('.password_confirmation').val();

        prefer_language = (prefer_language == 1) ? '中文' : 'English';
        // gender = (gender == 'Male') ? 'Male' : 'Female';


        $('#register-form .required-feild').each( function(){
            if(!$(this).val()){
                $(this).addClass('required-feild-error');
                empty_fill = 1;
            }
        });
        
        if(empty_fill == 1){
            $('.error-message').html('Please fill in all required field.');
            $('.loading-gif').hide();
            return false;
        }else{
            $('#error-message').addClass('important-text');
            $('#error-message').html("");
        }

        if(IsEmail(email)==false){
            $('#error-message').addClass('important-text');
            $('#error-message').html("Please enter a valid email format");
            return false;
        }else{
            $('#error-message').addClass('important-text');
            $('#error-message').html("");
            $('.email').val(email);
        }

        if(!phone){
            $('#error-message').addClass('important-text');
            $('#error-message').html("Please enter a phone number");
            return false;
        }else{
            $('#error-message').addClass('important-text');
            $('#error-message').html("");
        }

        if(ic.length != '12'){
            $('#error-message').addClass('important-text');
            $('#error-message').html("Please enter a valid NRIC number");
            return false;
        }else{
            $('#error-message').addClass('important-text');
            $('#error-message').html("");
        }

        if(pvCheck == false){
            $('#error-message').addClass('important-text');
            $('#error-message').html("Please click the 'Privacy Policy' checkbox to agree");
            return false;
        }else{
            $('#error-message').addClass('important-text');
            $('#error-message').html("");
        }

        if(!password){
            $('#error-message').addClass('important-text');
            $('#error-message').html("Please enter a password");
            return false;
        }else{
            $('#error-message').addClass('important-text');
            $('#error-message').html("");
        }

        if(!password_confirmation){
            $('#error-message').addClass('important-text');
            $('#error-message').html("Please enter a password confirmation");
            return false;
        }else{
            $('#error-message').addClass('important-text');
            $('#error-message').html("");
        }

        if(password !== password_confirmation){
            $('#error-message').addClass('important-text');
            $('#error-message').html("Password and confirmation do not match");
            return false;
        }else{
            $('#error-message').addClass('important-text');
            $('#error-message').html("");
        }

        if(!gender){
            $('#error-message').addClass('important-text');
            $('#error-message').html("Please select a gender");
            return false;
        }else{
            $('#error-message').addClass('important-text');
            $('#error-message').html("");
        }
        

        var fd = new FormData();
            fd.append('master_id', master_id);

        $.ajax({
            url: '{{ route("getUplineDetail") }}',
            type: 'post',
            data: fd,
            contentType: false,
            processData: false,
            success: function(response){
                if(response != 'not exists'){
                    $('#exampleSecondModal').modal('toggle');

                    var display = "<b>Full Name</b>: "+f_name+"<br><br>\
                                   <b>Email</b>: "+email+"<br><br>\
                                   <b>Phone</b>: "+country_code+phone+"<br><br>\
                                   <b>Gender</b>: "+gender+"<br><br>\
                                   <b>NRIC No</b>: "+ic+"<br><br>\
                                   <b>Referral Name</b>: "+response[0]+"<br><br>\
                                   <b>Referral Code</b>: "+master_id+"<br><br>";        
                    $('.confirm-details').html(display);
                }else{
                    alert('推荐人不存在');                    
                }
            },
        });

        

        
    });
    $('#refferal_id').select2({
        placeholder: "Select Refferal ID",
        allowClear: true
    });

    $('#country_code').select2({
        placeholder: "Select Country Code",
        allowClear: true
    });

    $('.button-inside').on('click', '.get-verify-code-btn', function(e){
        e.preventDefault();
        var ele = $(this);
        var phone = $('input[name="phone"]').val();
        var country_code = $('.country_code').val();
        
        if(phone.length < 10){
            alert("Please enter a valid mobile phone number");
            return false;
        }

        var fd = new FormData();
        fd.append('phone', phone);
        fd.append('country_code', country_code);
        fd.append('register', '1');

        $.ajax({
            url: '{{ route("getVerifyCode") }}',
            type: 'post',
            data: fd,
            contentType: false,
            processData: false,
            success: function(response){
                if(response == '1'){
                    alert('Phone number does not exist');
                    return false;
                }else{
                    ele.prop('disabled', true);
                    
                    $('#action-return-message').html('The verification code has been sent to your mobile phone, the input is valid within 10 minutes, please do not leak');

                    $('#action-return-message').addClass('important-text');

                    var timer2 = response[1];
                    // var timer2 = "0:03";
                    var interval = setInterval(function() {


                    var timer = timer2.split(':');
                    //by parsing integer, I avoid all extra string processing
                    var minutes = parseInt(timer[0], 10);
                    var seconds = parseInt(timer[1], 10);
                    --seconds;
                    minutes = (seconds < 0) ? --minutes : minutes;
                    seconds = (seconds < 0) ? 59 : seconds;
                    seconds = (seconds < 10) ? '0' + seconds : seconds;
                    //minutes = (minutes < 10) ?  minutes : minutes;
                    if (minutes == '0' && seconds == '00'){
                        clearInterval(interval);
                        var fd = new FormData();
                        fd.append('phone', phone);
                        $.ajax({
                            url: '{{ route("resetVerifyCode") }}',
                            type: 'post',
                            data: fd,
                            contentType: false,
                            processData: false,
                            success: function(response){
                                ele.html("Get Verify Code");
                                ele.prop('disabled', false);
                                $('#action-return-message').html('The verification code has been refreshed! Please click "Get Verification Code" to get the latest verification code!');
                            }
                        });
                    }

                    ele.html(minutes + ':' + seconds);

                    timer2 = minutes + ':' + seconds;
                    }, 1000);                            
                }
            },
        });
    });

    $('#register-form .required-feild').change( function(){
        if($(this).val()){
            $(this).removeClass('required-feild-error');
        }
    });

    $('.register-btn').click( function(e){
       e.preventDefault();
       console.log("123");
        $('#register-form').submit();
        
    });

    $('.privacy_policy_description').click(function(e){
        e.preventDefault()

        var ele = $(this);

        $('#pv').modal('toggle');
        $('.page-holder').slideUp('fast');
    });

    $( ".modal" ).on('hidden.bs.modal', function(){
        $('.page-holder').slideDown('fast'); 
    });

    // $('.ic-field').mask("000000-00-0000" , {reverse: true});


    $('.enable-password').click(function(e){
        e.preventDefault();

        var eye_close_open = $(this).find('i').attr('class');
        var ele = $(this).find('i');
        // alert(eye_close_open);
        if(eye_close_open == 'fa fa-eye-slash'){
            ele.removeClass('fa-eye-slash');
            ele.addClass('fa-eye');

            $('input[name="password"]').attr('type', 'password');
        }else{
            ele.addClass('fa-eye-slash');
            ele.removeClass('fa-eye');
            // alert($('input[name="password"]').attr('class'));
            // ele.parent().find('input[name="password"]').css('background-color', '#000');
            $('input[name="password"]').attr('type', 'text');
        }
    });

    $('.enable-confirm-password').click(function(e){
        e.preventDefault();

        var eye_close_open = $(this).find('i').attr('class');
        var ele = $(this).find('i');
        // alert(eye_close_open);
        if(eye_close_open == 'fa fa-eye-slash'){
            ele.removeClass('fa-eye-slash');
            ele.addClass('fa-eye');

            $('input[name="password_confirmation"]').attr('type', 'password');
        }else{
            ele.addClass('fa-eye-slash');
            ele.removeClass('fa-eye');
            // alert($('input[name="password"]').attr('class'));
            // ele.parent().find('input[name="password"]').css('background-color', '#000');
            $('input[name="password_confirmation"]').attr('type', 'text');
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