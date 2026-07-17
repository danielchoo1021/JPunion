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

    .main-content{
        margin-top: 120px;
    }
</style>
@endsection
@section('content')
<div class="container">
    <div class="main-content" style="margin-bottom: 120px;">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="container-box">
                    <form method="POST" action="{{ route('authorize_merchant') }}" id="login-form">
                        @csrf
                        <h3 align="center" class="header-login" style="color: #000;">
                            <!-- 欢迎登录 -->
                            {{ isset($data['lang']['lang']['vesson_account_login']) ? $data['lang']['lang']['vesson_account_login'] :'Vesson 户口登录'}}
                        </h3>
                        <br>
                        
                        <div class="form-group login-page">
                            <div class="form-group">
                                @if($errors->any())
                                  <div class="alert alert-danger">{!! implode('<br/>', $errors->all(':message')) !!}</div>
                                @endif
                            </div>

                            <div class="form-group">
                                <input type="email" class="form-control"  placeholder="{{ isset($data['lang']['lang']['email_address']) ? $data['lang']['lang']['email_address'] :'Email'}}" name="email">
                            </div>

                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon enable-password" 
                                          style="position: absolute; 
                                                 right: 10px; 
                                                 top: 10px; 
                                                 z-index: 4;
                                                 cursor: pointer;">
                                        <i class="fa fa-eye"></i>
                                    </span>
                                    <input id="password" type="password" class="form-control" name="password" placeholder="{{ isset($data['lang']['lang']['password']) ? $data['lang']['lang']['password'] :'密码'}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <div id="action-return-message"></div>
                            </div>

                            <input type="hidden" name="previous_url" value="{{ url()->previous() }}">
                            <div class="form-group">
                                <button class="btn btn-primary btn-block login-submit-button -red">
                                    {{ isset($data['lang']['lang']['login']) ? $data['lang']['lang']['login'] :'登录'}}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<br>
<div class="modal fade forget-password-modal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="background-color: #fff;">
    <div class="modal-content">
        <form method="POST" action="{{ route('SendForgotPasswordLink') }}">
        @csrf
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                  <!-- 请填写您的身份证号码 (NRIC No) -->
                  Please enter your email
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body" align="left">
                  <div class="form-group">
                      <label>
                        <!-- 身份证号码 (NRIC No) -->
                        Email
                      </label>
                      <!-- <input type="text" class="form-control ic" name="ic" placeholder="身份证号码 (NRIC No)"> -->
                      <input type="email" class="form-control forget_email" name="email" placeholder="Email">
                  </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                <!-- 关闭 -->
                  {{ isset($data['lang']['lang']['close']) ? $data['lang']['lang']['close'] :'关闭'}}
                </button>
                <button class="btn btn-primary btn-sm">
                  <!-- 提交 -->
                  {{ isset($data['lang']['lang']['submit']) ? $data['lang']['lang']['submit'] :'提交'}}
                </button>
              </div>
        </form>
    </div>
  </div>
</div>
@endsection
@section('js')
<script type="text/javascript">
    $(document).ready(function () {
        setCookie('remind_voucher', '0', '1');
    });

    function setCookie(cname, cvalue, exdays) {
      const d = new Date();
      d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
      let expires = "expires="+d.toUTCString();
      document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }

    $('.button-inside').on('click', '.get-verify-code-btn', function(e){
        e.preventDefault();
        $('.loading-gif').show();
        var ele = $(this);
        var phone = $('input[name="phone"]').val();
        if(phone.length < 10){
            alert("Please enter a valid mobile phone number");
            $('.loading-gif').hide();
            return false;
        }

        var fd = new FormData();
        fd.append('phone', phone);

        $.ajax({
            url: '{{ route("getVerifyCode") }}',
            type: 'post',
            data: fd,
            contentType: false,
            processData: false,
            success: function(response){
                // alert(response);
                // return false;
                if(response == '1'){
                    alert('Phone number does not exist');
                    $('.loading-gif').hide();
                    return false;
                }else{
                    $('.loading-gif').hide();
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
                                ele.html("Get Verfiy Code");
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


    $('.login-btn').click( function(e){
       e.preventDefault();

       var ele = $(this);
       var ic = $('input[name="ic"]').val();
       var password = $('input[name="password"]').val();
       
       var fd = new FormData();
       fd.append('ic', ic);
       fd.append('password', password);

       $.ajax({
            url: '{{ route("checkAccountFrozen") }}',
            type: 'post',
            data: fd,
            contentType: false,
            processData: false,
            success: function(response){
              if(response == '0'){
                // toastr.error('您的户口已冻结');
                toastr.error('Your account has been frozen');
              }else if(response == '1'){
                $('#login-form').submit();
              }else{
                toastr.error('Login Error, Please Contact Admin regarding this issue.');
              }
            }
          });


       // $('.loading-gif').show();
       // // $('input[name="password"]').val(phone);
       // // $('#login-form').submit();
       // var phone = $('input[name="phone"]').val();
       // var code = $('input[name="code"]').val();
       // var country_code = $('.country_code').val();

       // if(!phone){
       //      $('#action-return-message').addClass('important-text');
       //      $('#action-return-message').html("Please enter phone number");
       //      $('.loading-gif').hide();
       //      return false;
       // }else{
       //    if(phone.length < 10){
       //          $('#action-return-message').addClass('important-text');
       //          $('#action-return-message').html("Please enter a valid mobile phone number");
       //          $('.loading-gif').hide();
       //          return false;
       //    }
       // }

       // if(!code){
       //      $('#action-return-message').addClass('important-text');
       //      $('#action-return-message').html("Please enter a valid verification code");
       //      $('.loading-gif').hide();
       //      return false;
       // }



       // var fd = new FormData();
       // fd.append('phone', phone);
       // fd.append('code', code);
       // fd.append('country_code', country_code);

       // $.ajax({
       //      url: '{{ route("CheckLogin") }}',
       //      type: 'post',
       //      data: fd,
       //      contentType: false,
       //      processData: false,
       //      success: function(response){
       //          // alert(response);
       //          if(response == 1){
       //              $('#action-return-message').html("Verification code error");
       //              $('#action-return-message').addClass('important-text');
       //              $('.loading-gif').hide();
       //              return false;
       //          }else if(response == 2){
       //              $('#action-return-message').html("Phone number does not exist");
       //              $('#action-return-message').addClass('important-text');
       //              $('.loading-gif').hide();
       //          }else{
       //              // $('input[name="password"]').val(phone);
       //              $('#login-form').submit();
       //          }
       //      },
       //  }); 
    });

    $('.submit-forget-password').click(function(e){
        var ele = $(this);

        var email = $('.forget_email').val();

        if(!email){
          alert('Please Key in your details');
          return false;
        }

        var fd = new FormData();
            fd.append('email', email);

        $.ajax({
            url: '{{ route("ForgetPasswordEmail") }}',
            type: 'post',
            data: fd,
            contentType: false,
            processData: false,
            success: function(response){
              // alert(123);
                if(response == 1){
                    // toastr.error('身份证号码不存在');
                    toastr.error('NRIC Number does not exists');
                }else{
                    $('.forget-password-modal').modal('toggle');
                    // toastr.success('新密码已发送至您的电子邮件');
                    toastr.success('New Password has been sent to your email.');
                }

                $('.forget-password-modal').modal('toggle');
            },
        }); 


    });

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
</script>
@endsection