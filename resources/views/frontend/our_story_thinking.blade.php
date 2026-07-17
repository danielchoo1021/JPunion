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
                <h1 style="color: #59d7d1;">The Thinking</h1>
                <img src="{{ asset('Pictures-ppt/butterfly.jpg') }}" width="100%">
                <div class="py-5 pr-5 pl-5" style="background-color: #f2d9d9;">
                    <h1>
                        A Journey of Creating a Beautiful World
                    </h1>
                    <div>
                        Our dream at KIREINA is to contribute to humanity by optimising our technological expertise
                        and compassionate nature for the creation of everlasting health and beauty. We want to create a
                        magnificent world, where everyone, everywhere can reimagine beauty in infinite ways.
                        So, at KIREINA, we have discovered and continue to explore innovative ways of reaching the
                        greatest height of true beauty and health, using only the purest, highestquality, traceable
                        specimens selected for their effectiveness- because we believe that good health is the
                        essential beginning for achieving greater beauty, inside and out.
                    </div>
                </div>
                <div class="py-5 pr-5 pl-5" style="background-color: eee;">
                    <div class="title-wrap" align="left">
                        <div class="post-prws-listing my-1">
                            <div class="post-prw">
                                <div class="row vert-margin-middle">
                                    
                                    <div class="post-prw-text col-md-6" style="padding: 0px; background-color: transparent;">
                                        <h1 class="post-prw-title" style="color: #59d7d1;">
                                            A Company with a Culture of Beauty
                                        </h1>
                                        <div class="post-prw-teaser">
                                            As a beauty and healthcare company, KIREINA is
                                            dedicated to sharing and promoting the culture of
                                            beauty, health and sensibility to a wider audience.
                                            This unique pursuit can be described as the secret
                                            of Asian beauty, which is the inspiration and origin
                                            of our philosophy of true beauty.
                                        </div>
                                    </div>
                                    
                                    <div class="post-prw-img col-md-6" style="padding: 0px;">
                                        <img src="{{ asset('images/11.jpg') }}" data-src="{{ asset('images/11.jpg') }}" class="lazyload fade-up" alt="" style="height: auto;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="title-wrap" align="left">
                        <div class="post-prws-listing my-1">
                            <div class="post-prw">
                                <div class="row vert-margin-middle">
                                    
                                    <div class="post-prw-img col-md-6" style="padding: 0px;">
                                        <img src="{{ asset('images/22.jpg') }}" data-src="{{ asset('images/22.jpg') }}" class="lazyload fade-up" alt="" style="height: auto;">
                                    </div>
                                    <div class="post-prw-text col-md-6" style="padding: 0px; background-color: transparent;">
                                        <h1 class="post-prw-title" style="color: #59d7d1;">
                                            Together and Further
                                        </h1>
                                        <div class="post-prw-teaser">
                                            KIREINA has immense appreciation for the value of
                                            great talent. Our respect and recognition for the power
                                            of people is our company's flywheel; we work together
                                            in elegant harmony to deliver the gift of beauty to our
                                            customers while relishing every individual's continuous
                                            self-growth. We recognise that our collective character
                                            is built not only by our endless dedication, but also by
                                            the innovative ideas of those who share our journey.
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="title-wrap" align="left">
                        <div class="post-prws-listing my-1">
                            <div class="post-prw">
                                <div class="row vert-margin-middle">
                                    
                                    <div class="post-prw-text col-md-6" style="padding: 0px; background-color: transparent;">
                                        <h1 class="post-prw-title" style="color: #59d7d1;">
                                            The Customer is the Priority
                                        </h1>
                                        <div class="post-prw-teaser">
                                            At KIREINA, nothing is more important than our
                                            customers. All our research is designed and conducted
                                            with the customer's interest in mind, and our promise to
                                            them is our topmost commitment. It is our devotion to
                                            our customers that forms the basis of our relationship
                                            with them, making them more than just recipients of our
                                            products and services, but true partners in our journey.
                                        </div>
                                    </div>
                                    <div class="post-prw-img col-md-6" style="padding: 0px;">
                                        <img src="{{ asset('images/33.jpg') }}" data-src="{{ asset('images/33.jpg') }}" class="lazyload fade-up" alt="" style="height: auto;">
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="title-wrap" align="left">
                        <div class="post-prws-listing my-1">
                            <div class="post-prw">
                                <div class="row vert-margin-middle">
                                    
                                    <div class="post-prw-img col-md-6" style="padding: 0px;">
                                        <img src="{{ asset('images/44.png') }}" data-src="{{ asset('images/44.png') }}" class="lazyload fade-up" alt="" style="height: auto;">
                                    </div>
                                    <div class="post-prw-text col-md-6" style="padding: 0px; background-color: transparent;">
                                        <h1 class="post-prw-title" style="color: #59d7d1;">
                                            Support for Womankind
                                        </h1>
                                        <div class="post-prw-teaser">
                                            Our sincerity in returning the love we receive from
                                            women all over the world is demonstrated by our
                                            numerous CSR activities, which we plan and carry
                                            out with enthusiasm and authenticity. KIREINA
                                            gives all our support and love for helping women
                                            gain the courage to stand on their own feet and
                                            build beautiful lives for themselves and for others.
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="title-wrap" align="left">
                        <div class="post-prws-listing my-1">
                            <div class="post-prw">
                                <div class="row vert-margin-middle">
                                    
                                    <div class="post-prw-text col-md-6" style="padding: 0px; background-color: transparent;">
                                        <h1 class="post-prw-title" style="color: #59d7d1;">
                                            Technology through Innovation
                                        </h1>
                                        <div class="post-prw-teaser">
                                            KIREINA does not rest in the search for the secret of
                                            True Beauty. We have never once ceased our research
                                            and innovation efforts: instead, we persistently study
                                            developments in cosmetic and health science to deepen
                                            our understanding in the realm of beauty, which allows
                                            us to constantly propose new ideas and carve out a
                                            wondrous niche of our own- one in which we integrate
                                            our core innovative technologies in what we call
                                            KIREINA'S True Beauty research.
                                        </div>
                                    </div>
                                    
                                    <div class="post-prw-img col-md-6" style="padding: 0px;">
                                        <img src="{{ asset('images/12.jpg') }}" data-src="{{ asset('images/12.jpg') }}" class="lazyload fade-up" alt="" style="height: auto;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection