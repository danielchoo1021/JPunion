@extends('layouts.app')
@section('content')
@if(!empty($data['setting_header']->return_policy_bg_image))
    <div class="page-header" style="background-image: url({{ asset($data['setting_header']->return_policy_bg_image) }});">

    </div>
@else
    <div class="breadcrumb">
        <div class="container">
            <h2>Return Policy</h2>
        </div>
    </div>
@endif
<div class="container pt-3 pb-5 mb-5">
    <div class="row">
        <div class="col-12 col-md-12 col-xl-12">
            @if(!empty($data['web_setting']->return_policy_description))
                <div class="single_post_text text-editor-image">
                    {!! $data['web_setting']->return_policy_description !!}
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