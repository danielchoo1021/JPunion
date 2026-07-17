@extends('layouts.app')
@section('css')
<style type="text/css">
    .accordion {
        
    }

    .accordion__collapsible {
        border: 1px solid lightgray;
        border-radius: 3px;
    }

    .accordion__collapsible:not(:last-child) {
        border-bottom: none;
    }

    .collapsible__header {
        background-color: rgb(243, 242, 242);
        display: flex;
        justify-content: space-between;
        padding: 10px;
        cursor: pointer;
    }

    .collapsible__header::after {
        content: '+';
        position: relative;
        right: 10px;
    }

    .collapsible__content {
        max-height: 0;
        opacity: 0;
        overflow: hidden;
        transition: max-height 1s, opacity 1s;
    }

    .collapsible--open .collapsible__header::after {
        content: '-';
    }

    .collapsible--open .collapsible__content {
        max-height: 500px;
        opacity: 1;
    }



    /* Content styles - To make inner content look better */
    .collapsible__header p {
        font-size: medium;
    }

    .collapsible__content p {
        font-size: small;
        margin: 10px;
    }
</style>
@endsection
@section('content')
<div class="page-header" style="background-image: url({{ asset($data['setting_header']->faqs_image) }});">

</div>
<section class="blog spad">
    <div class="container">
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">
                    {{ isset($data["lang"]["lang"]["orders_and_shipping"]) ? $data["lang"]["lang"]["orders_and_shipping"] :"Orders and Shipping" }}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">
                    {{ isset($data["lang"]["lang"]["payment_and_my_account"]) ? $data["lang"]["lang"]["payment_and_my_account"] :"Payment and My Account" }}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false">
                    {{ isset($data["lang"]["lang"]["general_enquiries"]) ? $data["lang"]["lang"]["general_enquiries"] :"General Enquiries" }}
                </a>
            </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">

                <div class="accordion">
                    @foreach($type_one as $one)
                    <div class="accordion__collapsible">
                        <div class="collapsible__header">
                            <p>
                                @if(isset($_COOKIE['global_language']) && !empty($_COOKIE['global_language']))
                                    @if($_COOKIE['global_language'] == '1')
                                        {{ !empty($one->question_cn) ? $one->question_cn : '暂无华文翻译' }}
                                    @else
                                        {{ !empty($one->question) ? $one->question : '' }}
                                    @endif
                                @else
                                    {{ !empty($one->question) ? $one->question : '' }}
                                @endif
                            </p>
                        </div>
                        <div class="collapsible__content">
                            <p style="white-space: pre-line; padding-bottom: 20px;">
                                @if(isset($_COOKIE['global_language']) && !empty($_COOKIE['global_language']))
                                    @if($_COOKIE['global_language'] == '1')
                                        {!! !empty($one->answer_cn) ? $one->answer_cn : '暂无华文翻译' !!}
                                    @else
                                        {!! $one->answer !!}
                                    @endif
                                @else
                                    {!! $one->answer !!}
                                @endif
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                @foreach($type_two as $two)
                <div class="accordion__collapsible">
                    <div class="collapsible__header">
                        <p>
                            @if(isset($_COOKIE['global_language']) && !empty($_COOKIE['global_language']))
                                @if($_COOKIE['global_language'] == '1')
                                    {{ !empty($two->question_cn) ? $two->question_cn : '暂无华文翻译' }}
                                @else
                                    {{ !empty($two->question) ? $two->question : '' }}
                                @endif
                            @else
                                {{ !empty($two->question) ? $two->question : '' }}
                            @endif
                        </p>
                    </div>
                    <div class="collapsible__content">
                        <p style="white-space: pre-line; padding-bottom: 20px;">
                            @if(isset($_COOKIE['global_language']) && !empty($_COOKIE['global_language']))
                                @if($_COOKIE['global_language'] == '1')
                                    {!! !empty($two->answer_cn) ? $two->answer_cn : '暂无华文翻译' !!}
                                @else
                                    {!! $two->answer !!}
                                @endif
                            @else
                                {!! $two->answer !!}
                            @endif
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
                @foreach($type_three as $three)
                <div class="accordion__collapsible">
                    <div class="collapsible__header">
                        <p>
                            @if(isset($_COOKIE['global_language']) && !empty($_COOKIE['global_language']))
                                @if($_COOKIE['global_language'] == '1')
                                    {{ !empty($three->question_cn) ? $three->question_cn : '暂无华文翻译' }}
                                @else
                                    {{ !empty($three->question) ? $three->question : '' }}
                                @endif
                            @else
                                {{ !empty($three->question) ? $three->question : '' }}
                            @endif
                        </p>
                    </div>
                    <div class="collapsible__content">
                        <p style="white-space: pre-line; padding-bottom: 20px;">
                            @if(isset($_COOKIE['global_language']) && !empty($_COOKIE['global_language']))
                                @if($_COOKIE['global_language'] == '1')
                                    {!! !empty($three->answer_cn) ? $three->answer_cn : '暂无华文翻译' !!}
                                @else
                                    {!! $three->answer !!}
                                @endif
                            @else
                                {!! $three->answer !!}
                            @endif
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endsection

@section('js')
<script type="text/javascript">
    let collapsibleHeaders = document.getElementsByClassName('collapsible__header');

    Array.from(collapsibleHeaders).forEach(header => {
        header.addEventListener('click', () => {
            header.parentElement.classList.toggle('collapsible--open');
        });
    });
</script>
@endsection