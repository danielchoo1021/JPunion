@extends('layouts.app')
@section('content')
{{-- banner --}}
<div id="slider" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
        @if(!$banners->isEmpty())
            @foreach($banners as $bannerkey => $banner)
                <li data-target="#slider" data-slide-to="{{ $bannerkey }}" class="{{ ($bannerkey == 0) ? 'active' : '' }}"></li>
            @endforeach
        @else
            <li data-target="#slider" data-slide-to="0" class="active"></li>
        @endif
    </ol>
    <div class="carousel-inner">
        @if(!$banners->isEmpty())
            @foreach($banners as $bannerkey => $banner)
                <div class="carousel-item {{ ($bannerkey == 0) ? 'active' : '' }}">
                    @if(!empty($banner->url))
                        <a href="{{ $banner->url }}">
                            <img src="{{ asset($banner->image) }}" class="d-block w-100" alt="banner image">
                        </a>
                    @else
                        <img src="{{ asset($banner->image) }}" class="d-block w-100" alt="banner image">
                    @endif
                </div>
            @endforeach
        @else
            <div class="carousel-item active">
                <img src="{{ asset('images/banner-03.jpg') }}" class="d-block w-100" alt="banner image">
            </div>
        @endif
    </div>
    @if(count($banners) > 1)
        <a class="carousel-control-prev" href="#slider" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#slider" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    @endif
</div>

<!-- products -->
@if(!$products_featured->isEmpty())
    <div class="">
        <div class="container text-center py-4">
            <div class="col-md-12">
                        <h2 class="form-group text-center fw-600 mb-4 ">
                            @if(isset($_COOKIE['global_language']) && !empty($_COOKIE['global_language']))
                                @if($_COOKIE['global_language'] == '1')
                                    {!! $setting->featured_product_title_cn ? htmlspecialchars_decode($setting->featured_product_title_cn) : '特色产品' !!}
                                @else
                                    {!! $setting->featured_product_title ? htmlspecialchars_decode($setting->featured_product_title) : 'Featured Product' !!}
                                @endif
                            @else
                                {!! $setting->featured_product_title ? htmlspecialchars_decode($setting->featured_product_title) : 'Featured Product' !!}
                            @endif
                        </h2>
            </div>                
            <div class="product-loading">
                <img src="{{ asset('images/loading/09b24e31234507.564a1d23c07b4.gif') }}" class="w-20"/>
            </div>
            <div class="container eye-container mt-4">
                <div class="product-slider owl-carousel">
                    @foreach($products_featured as $featured)
                        <div class="eye-container-box">
                            <a href="{{ route('details', md5($featured->id)) }}">
                                <div class="product">
                                    <div class="eye-black-circle-new">
                                        {{ isset($data['lang']['lang']['new']) ? $data['lang']['lang']['new'] :'New' }}
                                    </div>
                                    <div class="product-thumb">
                                        <div style="background-image: url({{ (!empty($featured->first_image->image)) ? asset($featured->first_image->image) : asset('images/800x800.png') }});
                                                background-repeat: no-repeat;
                                                background-size: 100%;
                                                background-position: center;
                                                width: 100%;
                                                height: 200px;" class="listing-image">
                                        </div>
                                    </div>
                                    <div class="product-content pb-4">
                                        <h5 class="eye-short-description">
                                            @if(!empty($featured->product_name))
                                                @if(isset($_COOKIE['global_language']) && !empty($_COOKIE['global_language']))
                                                    @if($_COOKIE['global_language'] == '1')
                                                        {{ !empty($featured->product_name_cn) ? $featured->product_name_cn : '暂无华文翻译' }}
                                                    @else
                                                        {{ !empty($featured->product_name) ? $featured->product_name : '' }}
                                                    @endif
                                                @else
                                                    {{ !empty($featured->product_name) ? $featured->product_name : '' }}
                                                @endif
                                            @else
                                                &nbsp;
                                            @endif
                                        </h5>
                                        <br/>
                                        <a href="{{ route('details', md5($featured->id)) }}">
                                            <button class="eye-btn-see-more set_button set_text">
                                                {{ isset($data['lang']['lang']['see_more_2']) ? $data['lang']['lang']['see_more_2'] :'SEE MORE' }}
                                            </button>
                                        </a>    
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif

@if(!empty($current_active_flash_sales->id))
    <section class="featured spad-sm">
        <div class="bg-skygrey-1 py-4">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="form-group text-center fw-600 mb-4 ">
                            {{ isset($data['lang']['lang']['flash_sale']) ? $data['lang']['lang']['flash_sale'] : '限时抢购' }}
                        </h2>
                    </div>
                </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="section-countdown -center" style="margin-bottom: 1.875em">
                        <!-- <div id="countdown">
                            <span id="days">

                            </span>
                            <span id="hours">

                            </span>
                            <span id="minutes">

                            </span>
                            <span id="seconds">

                            </span>
                        </div> -->
                        <div class="countdown">
                          <div class="countdown-item set_button">
                            <div class="countdown-value" id="days"></div>
                            <div class="countdown-label">{{ isset($data['lang']['lang']['days']) ? $data['lang']['lang']['days'] : 'Days' }}</div>
                          </div>
                          <div class="countdown-item set_button">
                            <div class="countdown-value" id="hours"></div>
                            <div class="countdown-label">{{ isset($data['lang']['lang']['hours']) ? $data['lang']['lang']['hours'] : 'Hours' }}</div>
                          </div>
                          <div class="countdown-item set_button">
                            <div class="countdown-value" id="minutes"></div>
                            <div class="countdown-label">{{ isset($data['lang']['lang']['minutes']) ? $data['lang']['lang']['minutes'] : 'Minutes' }}</div>
                          </div>
                          <div class="countdown-item set_button">
                            <div class="countdown-value" id="seconds"></div>
                            <div class="countdown-label">{{ isset($data['lang']['lang']['seconds']) ? $data['lang']['lang']['seconds'] : 'Seconds' }}</div>
                          </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="flash-sales-slider owl-carousel">
                @foreach($current_active_flash_sales->get_flash_product_details as $flash_product)
                {{--     
                    <div class="col-md-3 col-6 mix {{ preg_replace('/[^A-Za-z0-9\-]/', '', $flash_product->category_name) }} small-gap form-group">
                        <a href="{{ route('details', md5($flash_product->get_product_detail->id)) }}">
                            <div class="featured__item">
                                @php
                                    if(!empty($flash_product->get_product_detail->first_image->image)){
                                        $bg_img = asset($flash_product->get_product_detail->first_image->image);
                                    }else{
                                        $bg_img = 'images/no-image-available-icon-61.jpg';
                                    }
                                @endphp
                                <div class="featured__item__pic product__discount__item__pic set-bg" style="background-image: url('{{ $bg_img }}'); height: 250px; background-position: center center; position: relative; overflow: hidden; background-repeat: no-repeat; background-size: cover; border-top-left-radius: 10px; border-top-right-radius: 10px;">
                                <!-- <div class="featured__item__pic product__discount__item__pic set-bg" data-setbg="{{ $bg_img }}">-->
                                    <!-- @if(!empty($discount_percentage))
                                    <div class="product__discount__percent">
                                        -{{ number_format($discount_percentage) }}%
                                    </div>
                                    @endif -->
                                </div>
                                <div class="product__discount__item__text" style="text-align: center;
                                                                                  padding-top: 20px;">
                                    <!-- <small>
                                        @if(isset($_COOKIE['global_language']) && !empty($_COOKIE['global_language']))
                                            @if($_COOKIE['global_language'] == '1')
                                                @if(!empty($flash_product->get_product_detail->one_product_category->category_name_cn))
                                                    {{ $flash_product->get_product_detail->one_product_category->category_name_cn }}
                                                @else
                                                    {{ $flash_product->get_product_detail->one_product_category->category_name }}
                                                @endif
                                            @else
                                                {{ $flash_product->get_product_detail->one_product_category->category_name }}
                                            @endif
                                        @else
                                            {{ $flash_product->get_product_detail->one_product_category->category_name }}
                                        @endif
                                    </small> -->
                                    <h6 style="height: 40px;">
                                        <span class="one-liner">
                                            @if(isset($_COOKIE['global_language']) && !empty($_COOKIE['global_language']))
                                                @if($_COOKIE['global_language'] == '1')
                                                    @if(!empty($flash_product->get_product_detail->product_name_cn))
                                                        {{ $flash_product->get_product_detail->product_name_cn }}
                                                    @else
                                                        {{ $flash_product->get_product_detail->product_name }}
                                                    @endif
                                                @else
                                                    {{ $flash_product->get_product_detail->product_name }}
                                                @endif
                                            @else
                                                {{ $flash_product->get_product_detail->product_name_cn }}
                                            @endif
                                        </span>
                                    </h6>
                                    <!-- @if(!empty($flash_product->variation_id))
                                    <h6 style="height: 40px;">
                                        {{ $flash_product->get_variation->variation_name }}
                                    </h6>
                                    @endif
                                    @if(!empty($flash_product->second_variation_id))
                                    <h6 style="height: 40px;">
                                      {{ $flash_product->get_second_variation->variation_name }}
                                    </h6>
                                    @endif -->
                                    <h5 style="height: 40px; color:">
                                        <div class="product__item__price text-red">
                                            RM {{ number_format($flash_sale_price[$flash_product->id], 2) }}
                                        </div>
                                        <div class="product__item__price text-grey" style="font-size: 13px;">
                                            <del>RM {{ number_format($original_price[$flash_product->id], 2) }}</del>
                                        </div>
                                    </h5>
                                    <span style="font-family: 'Cairo', 'sans-serif' !important; padding-bottom: 0.5em;">
                                        {{ $sold_amount[$flash_product->product_id] }} {{ isset($data['lang']['lang']['sold']) ? $data['lang']['lang']['sold'] :'sold'}}
                                    </span>

                                    <br/>
                                        <a href="{{ route('details', md5($flash_product->product_id)) }}">
                                            <button class="eye-btn-see-more set_button set_text">
                                                {{ isset($data['lang']['lang']['see_more_2']) ? $data['lang']['lang']['see_more_2'] :'SEE MORE' }}
                                            </button>
                                        </a>    

                                </div>
                            </div>                        
                        </a>
                    </div>
                --}}
                {{--  <div class="eye-container-box">
                    <a href="{{ route('details', md5($flash_product->get_product_detail->id)) }}">  
                        <div class="product">
                            <div class="product-thumb">
                                <div style="background-image: url({{ (!empty($featured->first_image->image)) ? asset($flash_product->get_product_detail->first_image->image) : asset('images/no-image-available-icon-61.jpg') }});" class="h-320">
                                    
                                </div>
                            </div>
                            <div class="product-content pb-4">
                                <!-- <h5 class="eye-short-description">
                                    @if(!empty($featured->product_name))
                                        @if(isset($_COOKIE['global_language']) && !empty($_COOKIE['global_language']))
                                            @if($_COOKIE['global_language'] == '1')
                                                {{ !empty($flash_product->get_product_detail->one_product_category->category_name_cn)}}
                                                {{ $flash_product->get_flash_product_details->one_product_category->category_name_cn }}
                                            @else
                                                {{ $flash_product->get_product_detail->one_product_category->category_name }}
                                            @endif
                                        @else
                                            {{ $flash_product->get_product_detail->one_product_category->category_name}}
                                        @endif
                                    @else
                                        {{ $flash_product->get_product_detail->one_product_category->category_name }}
                                    @endif
                                </h5>
                                <br/> -->
                                <h6 style="height: 30px;">
                                    <span class="one-liner">
                                        @if(isset($_COOKIE['global_language']) && !empty($_COOKIE['global_language']))
                                            @if($_COOKIE['global_language'] == '1')
                                                @if(!empty($flash_product->get_product_detail->product_name_cn))
                                                    {{ $flash_product->get_product_detail->product_name_cn }}
                                                @else
                                                    {{ $flash_product->get_product_detail->product_name }}
                                                @endif
                                            @else
                                                {{ $flash_product->get_product_detail->product_name }}
                                            @endif
                                        @else
                                            {{ $flash_product->get_product_detail->product_name_cn }}
                                        @endif
                                    </span>
                                </h6>
                                
                                    <!-- @if(!empty($flash_product->variation_id))
                                    <h6 style="height: 40px;">
                                        {{ $flash_product->get_variation->variation_name }}
                                    </h6>
                                    @endif
                                    @if(!empty($flash_product->second_variation_id))
                                    <h6 style="height: 40px;">
                                      {{ $flash_product->get_second_variation->variation_name }}
                                    </h6>
                                    @endif -->
                                    <h5 style="height: 40px; color:">
                                        <div class="product__item__price text-red">
                                            RM {{ number_format($flash_sale_price[$flash_product->id], 2) }}
                                        </div>
                                        <div class="product__item__price text-grey" style="font-size: 13px;">
                                            <del>RM {{ number_format($original_price[$flash_product->id], 2) }}</del>
                                        </div>
                                    </h5>
                                    <!-- <span style="font-family: 'Cairo', 'sans-serif' !important; padding-bottom: 0.5em;">
                                        {{ $sold_amount[$flash_product->product_id] }} {{ isset($data['lang']['lang']['sold']) ? $data['lang']['lang']['sold'] :'sold'}}
                                    </span> -->

                                <br>
                                
                                <a href="{{ route('details', md5($featured->id)) }}">
                                    <button class="eye-btn-see-more set_button set_text">
                                        {{ isset($data['lang']['lang']['see_more_2']) ? $data['lang']['lang']['see_more_2'] :'SEE MORE' }}
                                    </button>
                                </a>    
                            </div>
                        </div>
                    </a>
                </div>
                --}}
                
                <div class="flash-item">
                    <div class='container' style="font-size: 13px;" align="center">
                        <a href="{{ route('details', md5($flash_product->get_product_detail->id)) }}">
                            <div class="product ">
                                <div class="product-thumb">
                                    <div style="background-image: url({{ (!empty($flash_product->get_product_detail->first_image->image)) ? asset($flash_product->get_product_detail->first_image->image) : asset('images/no-image-available-icon-61.jpg') }});
                                                background-repeat: no-repeat;
                                                background-size: 100%;
                                                background-position: center;
                                                width: 100%;
                                                height: 200px;" class="listing-image">
                                    </div>
                                </div>
                                <div class="product-content">
                                    <span class="one-liner">
                                        @if(isset($_COOKIE['global_language']) && !empty($_COOKIE['global_language']))
                                            @if($_COOKIE['global_language'] == '1')
                                                @if(!empty($flash_product->get_product_detail->product_name_cn))
                                                    {{ $flash_product->get_product_detail->product_name_cn }}
                                                @else
                                                    {{ $flash_product->get_product_detail->product_name }}
                                                @endif
                                            @else
                                                {{ $flash_product->get_product_detail->product_name }}
                                            @endif
                                        @else
                                            {{ $flash_product->get_product_detail->product_name_cn }}
                                        @endif
                                    </span>
                                </h6>
                                    <div class="product-content__footer" style="text-align: center; display: block;">
                                        <h5 class="product-price--main h-33" style="font-size: 15px; color: #000 !important;">
                                            <div class="product__item__price text-red">
                                                RM {{ number_format($flash_sale_price[$flash_product->id], 2) }}
                                            </div>

                                            <div class="product__item__price text-grey" style="font-size: 13px;">
                                                <del>RM {{ number_format($original_price[$flash_product->id], 2) }}</del>
                                            </div>
                                        </h5>
                                    </div>

                                    <!-- <span style="font-family: 'Cairo', 'sans-serif' !important; padding-bottom: 0.5em;">
                                        {{ $sold_amount[$flash_product->product_id] }} {{ isset($data['lang']['lang']['sold']) ? $data['lang']['lang']['sold'] :'sold'}}
                                    </span> -->

                                     <br/>
                                        <a href="{{ route('details', md5($flash_product->product_id)) }}">
                                            <button class="eye-btn-see-more set_button set_text">
                                                {{ isset($data['lang']['lang']['see_more_2']) ? $data['lang']['lang']['see_more_2'] :'SEE MORE' }}
                                            </button>
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
    </section>
@endif 

{{-- home overview --}}
@if(!empty($setting->home_page_overview))
    <div class="container text-center py-4">
        <div class="second_post_text text-editor-image">
            @if(isset($_COOKIE['global_language']) && !empty($_COOKIE['global_language']))
                @if($_COOKIE['global_language'] == '1')
                    {!! $setting->home_page_overview_cn ? htmlspecialchars_decode($setting->home_page_overview_cn) : '暂无华文翻译' !!}
                @else
                    {!! $setting->home_page_overview ? htmlspecialchars_decode($setting->home_page_overview) : '' !!}
                @endif
            @else
                {!! $setting->home_page_overview ? htmlspecialchars_decode($setting->home_page_overview) : '' !!}
            @endif
        </div>
    </div>
@endif

{{-- two highlight --}}
@if(!empty($home_page[1]) || !empty($home_page[2]))
    <div class="container py-4">
        <div class="form-group mb-mb-40">
            <div class="row d-flex align-items-center">
                <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                    <img src="{{ !empty($home_page[1]->image) ? asset($home_page[1]->image) : asset('images/800x800.png') }}" class="h-650" alt="highlight image 1">
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-12 px-35">
                    <div class="second_post_text">
                        @if(!empty($home_page[1]->description))
                            @if(isset($_COOKIE['global_language']) && !empty($_COOKIE['global_language']))
                                @if($_COOKIE['global_language'] == '1')
                                    {!! $home_page[1]->description_cn ? htmlspecialchars_decode($home_page[1]->description_cn) : '暂无华文翻译' !!}
                                @else
                                    {!! $home_page[1]->description ? htmlspecialchars_decode($home_page[1]->description) : '' !!}
                                @endif
                            @else
                                {!! $home_page[1]->description ? htmlspecialchars_decode($home_page[1]->description) : '' !!}
                            @endif
                        @else
                            <div class="form-group mt-4" align="center">
                                <i class="fa fa-search"></i> {{ isset($data['lang']['lang']['no_content']) ? $data['lang']['lang']['no_content'] :'No Content' }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row d-flex align-items-center">
                <div class="col-lg-6 col-md-6 col-sm-12 col-12 px-35 order-lg-1 order-2">
                    <div class="second_post_text">
                        @if(!empty($home_page[2]->description))
                            @if(isset($_COOKIE['global_language']) && !empty($_COOKIE['global_language']))
                                @if($_COOKIE['global_language'] == '1')
                                    {!! $home_page[2]->description_cn ? htmlspecialchars_decode($home_page[2]->description_cn) : '暂无华文翻译' !!}
                                @else
                                    {!! $home_page[2]->description ? htmlspecialchars_decode($home_page[2]->description) : '' !!}
                                @endif
                            @else
                                {!! $home_page[2]->description ? htmlspecialchars_decode($home_page[2]->description) : '' !!}
                            @endif
                        @else
                            <div class="form-group mt-4" align="center">
                                <i class="fa fa-search"></i> {{ isset($data['lang']['lang']['no_content']) ? $data['lang']['lang']['no_content'] :'No Content' }}
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-12 order-lg-2 order-1">
                    <img src="{{ !empty($home_page[2]->image) ? asset($home_page[2]->image) : asset('images/800x800.png') }}" class="h-650" alt="highlight image 2">
                </div>
            </div>
        </div>
    </div>
@endif

{{-- second banner --}}
@if(!$second_banners->isEmpty())
    <div class="py-3">
        <div id="slider" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
                @if(!$second_banners->isEmpty())
                    @foreach($second_banners as $bannerkey => $second_banner)
                        <li data-target="#slider" data-slide-to="{{ $bannerkey }}" class="{{ ($bannerkey == 0) ? 'active' : '' }}"></li>
                    @endforeach
                @else
                    <li data-target="#slider" data-slide-to="0" class="active"></li>
                @endif
            </ol>
            <div class="carousel-inner">
                @if(!$second_banners->isEmpty())
                    @foreach($second_banners as $bannerkey => $second_banner)
                        <div class="carousel-item {{ ($bannerkey == 0) ? 'active' : '' }}">
                        @if(!empty($second_banner->url))
                            <a href="{{ $banner->url }}">
                                <img src="{{ asset($second_banner->image) }}" class="d-block w-100" alt="second banner image">
                                {{-- <img src="{{ asset($second_banner->image) }}" class="h-651" alt="second banner image"> --}}
                            </a>
                        @else
                            <img src="{{ asset($second_banner->image) }}" class="d-block w-100" alt="second banner image">
                            {{-- <img src="{{ asset($second_banner->image) }}" class="h-651" alt="second banner image"> --}}
                        @endif
                        </div>
                    @endforeach
                @else
                    <div class="carousel-item active">
                        <img src="{{ asset('images/banner-03.jpg') }}" class="d-block w-100" alt="second banner image">
                        {{-- <img src="{{ asset('images/banner-03.jpg') }}" class="h-651" alt="second banner image"> --}}
                    </div>
                @endif
            </div>
            @if(count($second_banners) > 1)
                <a class="carousel-control-prev" href="#slider" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#slider" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            @endif
        </div>
    </div>
@endif

{{-- video 1 --}}
@if(!empty($video[1]->image))
    <div class="bg-skygrey py-4">
        <div class="container">
            <h2 class="form-group text-center fw-600">
                {{ isset($data['lang']['lang']['video_2']) ? $data['lang']['lang']['video_2'] :'VIDEO' }}
            </h2>
            <div class="form-group text-center">
                <video class="w-400" autoplay loop muted playsinline>
                    <source src="{{ asset(!empty($video[1]->image) ? $video[1]->image : 'images/no-image-available-icon-6.jpg') }}" type="video/mp4">
                </video>
            </div>
            @if(!empty($video[1]->description))
                <div class="form-group pt-4">
                    <div class="second_post_text">
                        @if(isset($_COOKIE['global_language']) && !empty($_COOKIE['global_language']))
                            @if($_COOKIE['global_language'] == '1')
                                {!! $video[1]->description_cn ? htmlspecialchars_decode($video[1]->description_cn) : '暂无华文翻译' !!}
                            @else
                                {!! $video[1]->description ? htmlspecialchars_decode($video[1]->description) : '' !!}
                            @endif
                        @else
                            {!! $video[1]->description ? htmlspecialchars_decode($video[1]->description) : '' !!}
                        @endif
                    </div>
                </div>
            @endif
            <div class="py-80">    
                <div class="w-330">
                    <a href="{{ route('listing') }}" class="f-26 set_button set_text">
                        {{ isset($data['lang']['lang']['shop_now_2']) ? $data['lang']['lang']['shop_now_2'] :'SHOP NOW' }}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endif

{{-- voucher --}}
@if(!$vouchers->isEmpty())
    <div class="container py-4 mt-3">
        <h2 class="form-group text-center fw-600">
            {{ isset($data['lang']['lang']['voucher']) ? $data['lang']['lang']['voucher'] :'优惠劵' }}
        </h2>
        <div class="product-tab__content">
            <div class="product-tab__content__wrapper">
                <div class="row mx-n1 mx-lg-n3 justify-content-center">
                    @foreach($vouchers as $voucher)
                        @if($voucher->display_voucher == 1)
                            @if($voucher->status == 1 && $voucher->end_date >= date('Y-m-d Y-m-d H:i:s'))
                                <div class="col-lg-3 col-6 px-1 mb-3">
                                    <div class='container-box' style="font-size: 13px; padding: 0px;" align="center">
                                        <a href="{{ route('my_voucher', [str_replace('/', '-', $voucher->promotion_title), md5($voucher->id)]) }}">
                                            <div class="product ">
                                                <div class="product-type">

                                                </div>
                                                <div class="">
                                                    <img src="{{ !empty($voucher->image) ? asset($voucher->image) : asset('images/800x800.png') }}" class="voucher-image" alt="voucher image">
                                                </div>
                                                <div class="product-content pb-4">
                                                    <h6 class="mb-2">
                                                        {{ $voucher->promotion_title }}
                                                    </h6>
                                                    <h6 class="fw-600">
                                                        @if ($voucher->free_shipping == 1) 
                                                            {{$voucher->discount = 'Free Shipping'}}
                                                        @elseif ($voucher->amount_type == 'Percentage') 
                                                            {{$voucher->discount = $voucher->amount . '%'}}
                                                        @else 
                                                            {{$voucher->discount = 'RM ' . $voucher->amount}}
                                                        @endif
                                                    </h6>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            @endif
                        @endif
                    @endforeach          
                </div>
            </div>
        </div>
    </div>
@endif

{{-- quiz --}}
@if(!$quizes->isEmpty())
    <div class="container text-center my-50">
        <div class="form-group">
            <h2 class="form-group text-center fw-600 mb-4">
                {{ isset($data['lang']['lang']['take_a_quiz']) ? $data['lang']['lang']['take_a_quiz'] :'Take A Quiz' }}
            </h2>
            <h6 class="text-grey pb-3">
                Put your knowledge to the test and see how well you do. Click below to start the quiz.
            </h6>
        </div>
        <div class="form-group py-3">
            <a href="{{ route('quiz') }}" class="bg-darkblue set_button set_text">
                {{ isset($data['lang']['lang']['take_a_quiz_now_2']) ? $data['lang']['lang']['take_a_quiz_now_2'] :'TAKE A QUIZ NOW' }}
            </a>
        </div>
       
    </div>
@endif

@if(!empty($video[2]->image) && !$quizes->isEmpty())
<hr class="d-w-30">
@endif

{{-- video 2 --}}
@if(!empty($video[2]->image))
    <div class="container pb-2">
        <br>
        <div class="form-group text-center">
            <video class="w-400" autoplay loop muted playsinline>
                <source src="{{ asset($video[2]->image) }}" type="video/mp4">
            </video>
        </div>
    </div>
@endif

{{-- blog --}}
@if(!$blogs->isEmpty())
    <hr>
    <div class="container py-60">
        <h2 class="form-group text-center fw-600 mb-4">
            {{ isset($data['lang']['lang']['health_blog']) ? $data['lang']['lang']['health_blog'] :'Health Blog' }}
        </h2>
        <br>
        <div class="blogs-loading">
            <img src="{{ asset('images/loading/09b24e31234507.564a1d23c07b4.gif') }}" class="w-20"/>
        </div>
        <div class="blogs-slide owl-carousel">
            @foreach($blogs as $blog)
                <div class="container-box mx-2 mb-px-5">
                    <a href="{{ route('blog_details', md5($blog->id)) }}" class="text-decoration-unset">
                        <img src="{{ !empty($blog->image) ? asset($blog->image) : asset('images/800x800.png') }}" class="form-group h-250" alt="blog image">
                        <h5 class="form-group h-74 mb-4 pb-3">
                            @if(isset($_COOKIE['global_language']) && !empty($_COOKIE['global_language']))
                                @if($_COOKIE['global_language'] == '1')
                                    {{ !empty($blog->title_cn) ? $blog->title_cn : '暂无华文翻译' }}
                                @else
                                    {{ !empty($blog->title) ? $blog->title : '' }}
                                @endif
                            @else
                                {{ !empty($blog->title) ? $blog->title : '' }}
                            @endif
                        </h5>
                        <div class="form-group text-right">
                            <a href="{{ route('blog_details', md5($blog->id)) }}" class="btn py-10 set_button set_text">
                                {{ isset($data['lang']['lang']['read_more']) ? $data['lang']['lang']['read_more'] :'Read More' }}
                            </a>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endif

<div class="modal fade birthday_popup" id="birthday_popup" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="justify-content: center;">
        <div class="modal-content" style="background-color: #fff;">
            <div class="modal-header">
                <div class="modal-title">
                    <h4><b>{{ isset($data['lang']['lang']['birthday_popup']) ? $data['lang']['lang']['birthday_popup'] : 'Birthday Popup'}}</b></h4>
                </div>
                <a href="#" class="close-modal">
                    x
                </a>
            </div>
            <div class="modal-body">
                <div class="form-group"> 
                    {!! $setting->birthday_popup !!}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ isset($data['lang']['lang']['close']) ? $data['lang']['lang']['close'] : 'Close'}}</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
{{-- @if($birth_month_today)
    <script>
        $(document).ready(function() {
            $('#birthday_popup').modal('show');
        });
    </script>
@endif --}}
@if(!empty($current_active_flash_sales->id))
<script type="text/javascript">
    $(document).ready(function(){
        // Set the date and time for the countdown (in this example, it's set to one hour from now)
        var countDownDate = new Date('{{ $current_active_flash_sales->end }}').getTime();

        // Update the countdown every second
        var x = setInterval(function() {

          // Get the current date and time
          var now = new Date().getTime();

          // Calculate the time remaining between now and the countdown date
          var distance = countDownDate - now;

          // Calculate the days, hours, minutes, and seconds remaining
          var days = Math.floor(distance / (1000 * 60 * 60 * 24));
          var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
          var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
          var seconds = Math.floor((distance % (1000 * 60)) / 1000);

          // Display the countdown in the "countdown" div
          // document.getElementById("countdown").innerHTML = days + "d " + hours + "h "
          // + minutes + "m " + seconds + "s ";

          // $('#countdown').find('#days').htmcountdownl(days + "d");
          // $('#countdown').find('#hours').html(hours + "h");
          // $('#countdown').find('#minutes').html(minutes + "m");
          // $('#countdown').find('#seconds').html(seconds + "s");

          $('.countdown').find('#days').html(days);
          $('.countdown').find('#hours').html(hours);
          $('.countdown').find('#minutes').html(minutes);
          $('.countdown').find('#seconds').html(seconds);

          // If the countdown is finished, display a message
          if (distance < 0) {
            clearInterval(x);
            // document.getElementById("countdown").innerHTML = "EXPIRED";
            $('#countdown').html('EXPIRED');
          }
        }, 1000);
    });
</script>
@endif

<script type="text/javascript">
    // product
    $(window).on('load', function() {
        $('.product-loading').fadeIn(100);

        $(".product-slider").owlCarousel({
            loop: true,
            margin: 0,
            items: 5,
            dots: true,
            nav: true,
            navText: ["", ""],
            smartSpeed: 1200,
            autoplay: true,
            autoplayTimeout: 7000,
            autoHeight: false,
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 1
                },
                1350: {
                    items: 3
                },
                1800: {
                    items: 4
                }
            }
        });

        $('.product-loading').css('display', 'none');
    });

    // flash sales
    $(window).on('load', function() {
        $('.flash-sales-loading').fadeIn(100);

        $(".flash-sales-slider").owlCarousel({
            loop: true,
            margin: 0,
            items: 5,
            dots: true,
            nav: true,
            navText: ["", ""],
            smartSpeed: 1200,
            autoplay: true,
            autoplayTimeout: 7000,
            autoHeight: false,
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 1
                },
                1350: {
                    items: 3
                },
                1800: {
                    items: 4
                }
            }
        });

        $('.flash-sales-loading').css('display', 'none');
    });


    // blogs
    $(window).on('load', function() {
        $(".blogs-slide").owlCarousel({
            loop: true,
            margin: 0,
            dots: true,
            nav: true,
            navText: ["", ""],
            smartSpeed: 1200,
            autoplay: true,
            autoplayTimeout: 7000,
            autoHeight: false,
            responsive: {
                0: {
                    items: 2,
                },
                576: {
                    items: 2,
                },
                992: {
                    items: 3,
                },
                1400: {
                    items: 4,
                }
            },
        });

        $('.blogs-loading').css('display', 'none');
    });
</script>
@endsection