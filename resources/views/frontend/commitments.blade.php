@extends('layouts.app')
    <link href="{{ asset('new_layout/css/style.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open%20Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
@section('content')
<div class="page-content">
    <div class="holder mt-0 py-3 py-sm-5 py-md-10 bg-cover lazyload" style="background-image: url({{ asset('Pictures-ppt/KeyVisual03.jpg') }}); height: 500px; position: relative;">
        <div class="container" style="position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%);">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-bg" align="center">
                        <h1 style="color: white;">OUR Commitments</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
<div class="holder">
    <div class="container">
        <div class="title-wrap">
            <h3 style="color: #67f4ed;">Overview</h3>
            <h1>A Better World For All</h1>
            <p>
                Kireina recognises and enchances the understand value of women. A sourece of inspriation, leadership and 
                advocacy, Kireina empowers women from all walks of life, and is dedicated to creating enriched lifestyles
                for women——at work, at home, and in the heart.
            </p>
        </div>
    </div>
</div>

<div class="holder">
    <div class="container">
        <div class="title-wrap">
            <div class="post-prws-listing my-1">
                <div class="post-prw">
                    <div class="row vert-margin-middle">
                        
                        <div class="post-prw-img col-md-6" style="padding: 0px;">
                            <img src="{{ asset('images/2523.jpg') }}" data-src="{{ asset('images/2523.jpg') }}" class="lazyload fade-up" alt="" style="height: auto;">
                        </div>
                        
                        <div class="post-prw-text col-md-6" style="padding: 0px; background-color: white;">
                            <h1 class="post-prw-title" style="border-left: 8px solid #67f4ed;">
                                &nbsp;&nbsp;&nbsp; Inspiring Others
                            </h1>
                            <div class="post-prw-teaser">
                                Create a group of happy women (happiness is defined as being financially independent, able 
                                to manage relationships, independent in thinking)
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="post-prws-listing my-1">
                <div class="post-prw">
                    <div class="row vert-margin-middle">
                        
                        
                        <div class="post-prw-text col-md-6" style="padding: 0px; background-color: white;">
                            <h1 class="post-prw-title" style="border-left: 8px solid #67f4ed;">
                                &nbsp;&nbsp;&nbsp; Supporting Women's Cause
                            </h1>
                            <div class="post-prw-teaser">
                                To build and integrate a happy women, to grow and fight together.
                            </div>
                        </div>

                        <div class="post-prw-img col-md-6" style="padding: 0px;">
                            <img src="{{ asset('images/5366564.png') }}" data-src="{{ asset('images/5366564.png') }}" class="lazyload fade-up" alt="" style="height: auto;">
                        </div>
                    </div>
                </div>
            </div>
            <div class="post-prws-listing my-1">
                <div class="post-prw">
                    <div class="row vert-margin-middle">
                        
                        <div class="post-prw-img col-md-6" style="padding: 0px;">
                            <img src="{{ asset('images/green-planet.jpg') }}" data-src="{{ asset('images/green-planet.jpg') }}" class="lazyload fade-up" alt="" style="height: auto;">
                        </div>
                        
                        <div class="post-prw-text col-md-6" style="padding: 0px; background-color: white;">
                            <h1 class="post-prw-title" style="border-left: 8px solid #67f4ed;">
                                &nbsp;&nbsp;&nbsp; Safeguard Environment
                            </h1>
                            <div class="post-prw-teaser">
                                Create a group of happy women (happiness is defined as being financially independent, able 
                                to manage relationships, independent in thinking)
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection