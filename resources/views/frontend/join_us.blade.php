@extends('layouts.app')
    <link href="{{ asset('new_layout/css/style.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open%20Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
@section('content')
<div class="page-content">
    <div class="holder mt-0 py-3 py-sm-5 py-md-10 bg-cover lazyload" style="background-image: url({{ asset('images/03.jpg') }}); height: 500px; position: relative;">
        <div style="position: absolute;
                  top: 50%;
                  left: 50%;
                  transform: translate(-50%, -50%);
                  text-align: center;
                  width: 100%;
                  z-index: 2;">
            <div class="container" style="position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%);">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-bg" align="center">
                            <h1 style="color: white;">Join Us</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div style="position: absolute;
                  top:0px;
                  bottom: 0px;
                  left: 0px;
                  right: 0px;
                  background-color: rgba(0,0,0,0.3);
                  z-index: 1;">
      </div>
    </div>

    <div style="position: relative;
                height: 100%;
                width: 100%;">
        <!-- <img src="{{ asset('images/1684.jpg') }}" width="100%"> -->
        <div style="position: absolute;
                    left: 0;
                    top: 0;
                    bottom: 0;
                    right: 0;
                    width: 100%;
                    height: 100%;
                    padding: 2.5em;
                    background-image: url({{ asset('images/1684.jpg') }});
                    background-size: cover;
                    background-repeat: no-repeat;
                    background-position: center;">

            <h3 style="color: #67f4ed; font-size: 1.5em;">
                Partnership
            </h3>
            <h1 style="color: #fff; font-size: 2em;">
                Be Our Partner
            </h1>
            <p style="color: #fff; font-size: 1.25em;">
                We value strategic collaborations. Join our growing network and 
                <br>take your business further with us!
            </p>
        </div>
    </div>

    <div class="holder mb-5">
        <div class="container">
            <h2 style="color: #67f4ed;">Join Us</h2>
            <h1 style="margin: 0px;">Build the Future of Social Retail</h1>
            <p>
                Together, we will explore, discover and create new, more efficient solutions that will build the future of social retail.
            </p>
            <br>
            <div class="row">
                <div class="col-md-6">
                    <img src="{{ asset('images/women.png') }}" width="100%">
                    <div style="position: absolute;
    left: 6%;
    bottom: 5%;
    font-size: 35px;
    font-weight: bold;
    color: white;
    line-height: 1;">
                        Join The 
                        <br>
                        Community
                    </div>
                </div>
                <div class="col-md-6">
                    <img src="{{ asset('images/05B.jpg') }}" width="100%">
                    <div style="position: absolute;
    left: 6%;
    bottom: 5%;
    font-size: 35px;
    font-weight: bold;
    color: white;
    line-height: 1;">
                        Career
                        <br>
                        Opportunities
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection