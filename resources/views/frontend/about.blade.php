@extends('layouts.app')
@section('content')
@if(!empty($data['setting_header']->about_us_image))
    <div class="page-header" style="background-image: url({{ asset($data['setting_header']->about_us_image) }});">

    </div>
@else
    <div class="breadcrumb">
        <div class="container">
            <h2>{{ isset($data['lang']['lang']['about']) ? $data['lang']['lang']['about'] :'关于我们' }}</h2>
        </div>
    </div>
@endif

<div class="container pb-5 mb-5">
    <div class="row pb-3">
        <div class="col-lg-12">
            @if(!empty($data['web_setting']->about_us))
                <div class="single_post_text text-editor-image">
                    @if(isset($_COOKIE['global_language']) && !empty($_COOKIE['global_language']))
                        @if($_COOKIE['global_language'] == '1')
                            {!! !empty($data['web_setting']->about_us_cn) ? $data['web_setting']->about_us_cn : '暂无华文翻译' !!}
                        @else
                            {!! $data['web_setting']->about_us !!}
                        @endif
                    @else
                        {!! $data['web_setting']->about_us !!}
                    @endif
                </div>
            @else
                <div class="form-group pb-5" align="center">
                    <img src="{{ asset('images/no result_1.png') }}" class="h-220">
                </div>
            @endif
        </div>
    </div>
</div>
@endsection