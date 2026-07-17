@extends('layouts.app')
<link href="{{ asset('new_layout/css/style.css') }}" rel="stylesheet">
@section('content')
<div class="page-header" style="background-image: url({{ asset($data['setting_header']->contact_us_image) }});">

</div>
<div class="container pb-4 mw-800">
    <div class="form-group">
        <div class="row justify-content-center mb-5">
            <div class="col-lg-11 col-md-11 col-sm-12 col-12">
                <div class="form-group">
                    <h3 class="contact-title" style="text-align: center;">
                        Contact info
                    </h3>
                    <div class="form-group">
                        <div class="row">
                            @if(!empty($data['web_setting']->company_address))
                                <div class="col-md-12">
                                    <div class="contact-info__item container-box">
                                        <div class="contact-info__item__icon">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </div>
                                        <div class="contact-info__item__detail">
                                            <h3>Address</h3>
                                            <p style="white-space: pre-wrap; font-size: 15px;">{{ $data['web_setting']->company_address }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if(!empty($data['web_setting']->company_phone))
                                <div class="col-md-12">
                                    <div class="contact-info__item container-box">
                                        <div class="contact-info__item__icon">
                                            <i class="fas fa-phone-alt"></i>
                                        </div>
                                        <div class="contact-info__item__detail">
                                            <h3>Phone</h3>
                                            <p style="font-size: 15px;">
                                                {{ $data['web_setting']->company_phone }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if(!empty($data['web_setting']->contact_email))
                                <div class="col-md-12">
                                    <div class="contact-info__item container-box">
                                        <div class="contact-info__item__icon">
                                            <i class="far fa-envelope"></i>
                                        </div>
                                        <div class="contact-info__item__detail">
                                            <h3>Email</h3>
                                            <p style="font-size: 15px;">
                                                {{ $data['web_setting']->contact_email }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                {{-- 
                <div class="form-group">
                    <div class="row vert-margin">
                        <div class="col-sm-12">
                            <form id="contact-form" method="post" action="{{ route('contact_us_send') }}">
                                @csrf
                                <div class="form-confirm">
                                    <div class="success-confirm">
                                        {{ isset($data['lang']['lang']['home_accepted_message']) ? $data['lang']['lang']['home_accepted_message'] :'感谢信息，我们收到了会尽快回复'}}
                                    </div>
                                    <div class="error-confirm">
                                        {{ isset($data['lang']['lang']['home_error_message']) ? $data['lang']['lang']['home_error_message'] :'信息发送失败，请刷新页面再尝试'}}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row vert-margin-middle">
                                        <div class="col-lg">
                                            <input id="user-name" type="text" name="name" required="" class="form-control form-control--sm" placeholder="{{ isset($data['lang']['lang']['full_name']) ? $data['lang']['lang']['full_name'] :'名字'}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row vert-margin-middle">
                                        <div class="col-lg">
                                            <input id="user-email" type="email" name="email" required="" placeholder="{{ isset($data['lang']['lang']['email_address']) ? $data['lang']['lang']['email_address'] :'电子邮件'}}" class="form-control form-control--sm">
                                        </div>
                                        <div class="col-lg">
                                            <div class="row">
                                                <div class="col-6">
                                                    <select class="form-control" name="country_code" style="border: 1px solid #d0d0d0;">
                                                        @foreach($countries as $country)
                                                            <option {{ ( $country->country_id == '160') ? 'selected' : '' }} value="{{ $country->country_contact }}"> 
                                                                (+{{ $country->country_contact }}) {{ $country->country_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-6">
                                                    <input id="user-phone" type="text" name="phone" required="" class="form-control form-control--sm" placeholder="{{ isset($data['lang']['lang']['phone_number']) ? $data['lang']['lang']['phone_number'] :'电话号码'}}" onkeypress="return isNumberKey(event)">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <textarea class="form-control form-control--sm textarea--height-200" id="message" name="message" placeholder="{{ isset($data['lang']['lang']['Message']) ? $data['lang']['lang']['Message'] :'信息'}}" required=""></textarea>
                                </div>
                                <button id="submit" type="submit" class="btn btn-block" style="">
                                    Submit
                                </button>
                            </form>
                        </div>
                    </div>
                </div> 
                --}}
            </div>
        </div>
    </div>
</div>
@endsection