@extends('layouts.app')

@section('content')
<div class="page-content">
    <div class="page-header" style="background-image: url({{ asset($data['setting_header']->blog_image) }});">

    </div>
    <div class="holder">
        <div class="container mb-5 pb-4">
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">
                        {{ isset($data["lang"]["lang"]["all_posts"]) ? $data["lang"]["lang"]["all_posts"] :"All Posts" }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">
                        {{ isset($data["lang"]["lang"]["health_topics"]) ? $data["lang"]["lang"]["health_topics"] :"Health Topics" }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false">
                        {{ isset($data["lang"]["lang"]["news"]) ? $data["lang"]["lang"]["news"] :"News" }}
                    </a>
                </li>
            </ul>
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                    <div class="row">
                        @foreach($blogs as $blog)
                            <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                <div class="container-box">
                                    <a href="{{ route('blog_details', md5($blog->id)) }}">
                                        <div class="blog__item">
                                            <div class="blog__item__pic">
                                                @if(!empty($blog->image))
                                                    <div class="h-400" style="background-image: url({{ asset($blog->image) }});">

                                                    </div>
                                                @endif
                                            </div>
                                            <div class="blog__item__text">
                                                <h4 class="mb-f-14 mt-3">
                                                    @if(isset($_COOKIE['global_language']) && !empty($_COOKIE['global_language']))
                                                        @if($_COOKIE['global_language'] == '1')
                                                            {{ !empty($blog->title_cn) ? $blog->title_cn : '暂无华文翻译' }}
                                                        @else
                                                            {{ !empty($blog->title) ? $blog->title : '' }}
                                                        @endif
                                                    @else
                                                        {{ !empty($blog->title) ? $blog->title : '' }}
                                                    @endif
                                                </h4>
                                                <ul class="list-style-type-none">
                                                    @if(!empty($blog->blog_date))
                                                        <p>
                                                            <i class="far fa-calendar"></i>
                                                            {{ date('d M, Y', strtotime($blog->blog_date)) }}
                                                        </p>
                                                    @endif
                                                    @php
                                                        $use_cn = isset($_COOKIE['global_language']) && $_COOKIE['global_language'] == '1';
                                                        $raw_tags = $use_cn ? $blog->blog_tags_cn : $blog->blog_tags;
                                                        $tags = is_array($raw_tags) ? $raw_tags : json_decode($raw_tags, true);
                                                    @endphp
                                                    @if(!empty($tags))
                                                        @foreach($tags as $tag)
                                                            <p><i class="fa fa-tag" aria-hidden="true"></i> {{ $tag }}</p>
                                                        @endforeach
                                                    @endif
                                                </ul>
                                                <br>
                                                <a href="{{ route('blog_details', md5($blog->id)) }}" class="btn btn-sm mb-px-30 set_button set_text">
                                                    {{ isset($data["lang"]["lang"]["read_more_2"]) ? $data["lang"]["lang"]["read_more_2"] :"READ MORE" }} <span class="arrow_right"></span>
                                                </a>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                    <div class="row">
                        @foreach($blogs_events as $event)
                            <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                <div class="container-box">
                                    <a href="{{ route('blog_details', md5($event->id)) }}">
                                        <div class="blog__item">
                                            <div class="blog__item__pic">
                                                @if(!empty($event->image))
                                                    <div class="h-400" style="background-image: url({{ asset($event->image) }});">

                                                    </div>
                                                @endif
                                            </div>
                                            <div class="blog__item__text">
                                                <h4 class="mb-f-14 mt-3">
                                                    @if(isset($_COOKIE['global_language']) && !empty($_COOKIE['global_language']))
                                                        @if($_COOKIE['global_language'] == '1')
                                                            {{ !empty($event->title_cn) ? $event->title_cn : '暂无华文翻译' }}
                                                        @else
                                                            {{ !empty($event->title) ? $event->title : '' }}
                                                        @endif
                                                    @else
                                                        {{ !empty($event->title) ? $event->title : '' }}
                                                    @endif
                                                </h4>
                                                <ul class="list-style-type-none">
                                                    @if(!empty($blog->blog_date))
                                                        <p>
                                                            <i class="far fa-calendar"></i>
                                                            {{ date('d M, Y', strtotime($blog->blog_date)) }}
                                                        </p>
                                                    @endif
                                                    @php
                                                        $use_cn = isset($_COOKIE['global_language']) && $_COOKIE['global_language'] == '1';
                                                        $raw_event_tags = $use_cn ? $event->blog_tags_cn : $event->blog_tags;
                                                        $event_tags = is_array($raw_event_tags) ? $raw_event_tags : json_decode($raw_event_tags, true);
                                                    @endphp
                                                    @if(!empty($event_tags))
                                                        @foreach($event_tags as $event_tag)
                                                            <p><i class="fa fa-tag" aria-hidden="true"></i> {{ $event_tag }}</p>
                                                        @endforeach
                                                    @endif
                                                </ul>
                                                <br>
                                                <a href="{{ route('blog_details', md5($event->id)) }}" class="btn btn-outline-primary btn-sm mb-px-30 set_button set_text">
                                                    {{ isset($data["lang"]["lang"]["read_more_2"]) ? $data["lang"]["lang"]["read_more_2"] :"READ MORE" }} <span class="arrow_right"></span>
                                                </a>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
                    <div class="row">
                        @foreach($blogs_news as $new)
                            <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                <div class="container-box">
                                    <a href="{{ route('blog_details', md5($new->id)) }}">
                                        <div class="blog__item">
                                            <div class="blog__item__pic">
                                                @if(!empty($new->image))
                                                    <div class="h-400" style="background-image: url({{ asset($new->image) }});">

                                                    </div>
                                                @endif
                                            </div>
                                            <div class="blog__item__text">
                                                <h4 class="mb-f-14 mt-3">
                                                    @if(isset($_COOKIE['global_language']) && !empty($_COOKIE['global_language']))
                                                        @if($_COOKIE['global_language'] == '1')
                                                            {{ !empty($new->title_cn) ? $new->title_cn : '暂无华文翻译' }}
                                                        @else
                                                            {{ !empty($new->title) ? $new->title : '' }}
                                                        @endif
                                                    @else
                                                        {{ !empty($new->title) ? $new->title : '' }}
                                                    @endif
                                                </h4>
                                                <ul class="list-style-type-none">
                                                    @if(!empty($blog->blog_date))
                                                        <p>
                                                            <i class="far fa-calendar"></i>
                                                            {{ date('d M, Y', strtotime($blog->blog_date)) }}
                                                        </p>
                                                    @endif
                                                    @php
                                                        $use_cn = isset($_COOKIE['global_language']) && $_COOKIE['global_language'] == '1';
                                                        $raw_new_tags = $use_cn ? $new->blog_tags_cn : $new->blog_tags;
                                                        $new_tags = is_array($raw_new_tags) ? $raw_new_tags : json_decode($raw_new_tags, true);
                                                    @endphp
                                                    @if(!empty($new_tags))
                                                        @foreach($new_tags as $new_tag)
                                                            <p><i class="fa fa-tag" aria-hidden="true"></i> {{ $new_tag }}</p>
                                                        @endforeach
                                                    @endif
                                                </ul>
                                                <br>
                                                <a href="{{ route('blog_details', md5($new->id)) }}" class="btn btn-outline-primary btn-sm mb-px-30 set_button set_text">
                                                    {{ isset($data["lang"]["lang"]["read_more_2"]) ? $data["lang"]["lang"]["read_more_2"] :"READ MORE" }} <span class="arrow_right"></span>
                                                </a>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection