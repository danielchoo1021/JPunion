@extends('layouts.app')
    <link href="{{ asset('new_layout/css/style.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open%20Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
@section('content')
<div class="page-content">
    <div class="holder mt-0 py-3 py-sm-5 py-md-10 bg-cover lazyload" style="background-image: url({{ asset('images/04.jpg') }}); height: 500px; position: relative;">
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
                            <h1 style="color: white;">OUR STORY</h1>
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
<div class="holder">
    <div class="container">
        <div class="title-wrap text-center">
            <h1 style="color: #67f4ed;">KIREINA Management Profile Structure: </h1>
            <p>
                KIREINA's beauty-strong team of feminine power is led by Vivian Tong, who took up the role of CEO with
                an inspiring leadership philosophy that anything is possible with the right spirit of determination and
                self-belief. Driven by a passion to make KIREINA a women-empowering company, she has worked hard
                to bring value to the lives of women, by discovering and optimising the value in her own life and in that of her
                professional team.
                Vivian's leadership and efficiency, garnered over a dozen years of experience, has enabled her to lead a
                team of a thousand staff with a performance of RM10 million, at a rate of up to RM2 pillion a day. With her at
                the helm, KIREINA is sure to grow and succeed in its journey of spreading beauty across the world.
            </p>
        </div>
    </div>
</div>
<div class="holder">
    <div class="container">
        <img src="{{ asset('images/Website_Design_NEW_v02-8-01-01.jpg') }}" width="100%">
    </div>
</div>

<div class="holder">
    <div class="container">
        <div class="title-wrap text-center">
            <p>
                Coco Tong represents the vibrant and youthful spirit of KIREINA's dignified leadership. An entrepreneurial
                prodigy from the age of 13, she essentially began her career a whole decade earlier than the average
                millennial, and has distinguished herself as a force to be reckoned with in the beauty and wellness business.
                When asked her secrets to her unprecedented achievements, Coco cites self-belief, tenaciousness
                and courage as the keys to success.
                <br>
                <br>
                As the youngest member of KIREINA's higher management team, Coco inspires courage and self-love
                amongst the personnel whom she leads with ease and confidence. Having had experience with leading
                teams of up to 600 people, and networking at numerous large-scale international events, she is the perfect
                leader of KIREINA's sales division.
            </p>
        </div>
    </div>
</div>
<div class="holder">
    <div class="container">
        <img src="{{ asset('images/story_main-1.jpg') }}" width="100%">
    </div>
</div>
@endsection