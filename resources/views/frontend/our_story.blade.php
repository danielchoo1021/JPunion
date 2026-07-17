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
            <h1 style="color: #67f4ed;">Who we are</h1>
            <h2 style="color: #000;">A company built by Women, for women.</h2>
            <div class="row justify-content-center">
                <div class="col-8">
                    <p>
                        Kireina recognises and enchances the understand value of women. A sourece of inspriation, leadership and 
                        advocacy, Kireina empowers women from all walks of life, and is dedicated to creating enriched lifestyles
                        for women——at work, at home, and in the heart.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="holder">
    <div class="container">
        <img src="{{ asset('images/my_story.jpg') }}" width="100%">
    </div>
</div>

<div class="holder">
    <div class="container">
        <div class="title-wrap">
            <h1 style="border-left: 8px solid #67f4ed;">
                &nbsp;&nbsp;&nbsp; KIREINA Philosophy
            </h1>

            <h3 style="color: #67f4ed; margin-bottom: 0px;">
                A Philosophy of opportunity
            </h3>
            <div>
                Success is built on the optimisation of opportunity. At Kireina, we seize opportunity and create new ones
                for ourselves and for others.
            </div>
            <br>
    
            <h3 style="color: #67f4ed; margin-bottom: 0px;">
                KAIZEN: Change for the better.
            </h3>
            <div>
                At Kireina, we KAIZEN. We take baby steps on our way towards larger goals, aiming for 1% improvement
                each day. We focus on deliberate, continuous improvement.
            </div>
            <br>
    
            <h3 style="color: #67f4ed; margin-bottom: 0px;">
                Akiramenaide! Never give up.
            </h3>
            <div>
                At Kireina, we Akiramenaide! We fail, we get up again. We uphold each other to reach our full potential.
            </div>
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
                            <img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="{{ asset('images/09.jpg') }}" class="lazyload fade-up" alt="">
                        </div>
                        
                        <div class="post-prw-text col-md-6" style="padding: 0px; background-color: white;">
                            <h1 class="post-prw-title" style="border-left: 8px solid #67f4ed;">
                                &nbsp;&nbsp;&nbsp; Our Purpose
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
                                &nbsp;&nbsp;&nbsp; Our Vision
                            </h1>
                            <div class="post-prw-teaser">
                                To build and integrate a happy women, to grow and fight together.
                            </div>
                        </div>

                        <div class="post-prw-img col-md-6" style="padding: 0px;">
                            <img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="{{ asset('Pictures-ppt/KeyVisual02.jpg') }}" class="lazyload fade-up" alt="">
                        </div>
                    </div>
                </div>
            </div>
            <div class="post-prws-listing my-1">
                <div class="post-prw">
                    <div class="row vert-margin-middle">
                        
                        <div class="post-prw-img col-md-6" style="padding: 0px;">
                            <img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-src="{{ asset('Pictures-ppt/two-happy--friends (1).jpg') }}" class="lazyload fade-up" alt="">
                        </div>
                        
                        <div class="post-prw-text col-md-6" style="padding: 0px; background-color: white;">
                            <h1 class="post-prw-title" style="border-left: 8px solid #67f4ed;">
                                &nbsp;&nbsp;&nbsp; Our Mission
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

<div class="holder pt-5" style="background-color: #f0fbfb;">
    <div class="container">
        <div class="row justify-content-center my_story_table">
            <div class="col-8">
                <div class="title-wrap">
                    <h1 style="border-left: 8px solid #67f4ed;">
                        &nbsp;&nbsp;&nbsp; Core Value
                    </h1>
                </div>

                <p>
                    Kireina's culture is a manifestation of our values, built upon our vision and mission. We hold them firmly at the heart of the company, to move us forward in good times and bad times.
                </p>
                <br>
                <br>
                <table class="table table-bordered">
                    <tr>
                        <td width="50%">
                            <h1 style="color: #59d7d1;">
                                Persistence
                            </h1>
                            Will i be able to persevere no matter what happens.
                        </td>
                        <td width="50%">
                            <h1 style="color: #59d7d1;">
                                Selfishless
                            </h1>
                            Share unselfishly
                        </td>
                    </tr>
                    <tr>
                        <td width="50%">
                            <h1 style="color: #59d7d1;">
                                Give
                            </h1>
                            You reap what you sow
                        </td>
                        <td width="50%">
                            <h1 style="color: #59d7d1;">
                                Sincerity
                            </h1>
                            Sincerity is the greatest relationship
                        </td>
                    </tr>
                    <tr>
                        <td width="50%">
                            <h1 style="color: #59d7d1;">
                                Love
                            </h1>
                            Have the courage to love someone who deserves it.
                        </td>
                        <td width="50%">
                            <h1 style="color: #59d7d1;">
                                Gratitude
                            </h1>
                            Be grateful for everything that happens and everyone who has helped you. The more grateful you can be, the better your life will be.
                        </td>
                    </tr>
                    <tr>
                        <td width="50%">
                            <h1 style="color: #59d7d1;">
                                Independence
                            </h1>
                            You can live and think independently without being attached to anyone.
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection