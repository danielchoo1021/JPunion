@extends('layouts.app')
<style type="text/css">
    b {
      line-height: 1.25;
    }
</style>
@section('content')
<div class="page-content">
    @if($id == 1)
    <div class="holder mt-0 py-3 py-sm-5 py-md-10 bg-cover lazyload" style="background-image: url({{ asset('images/06.jpg') }}); height: 500px; position: relative;">
    @elseif($id == 2)
    <div class="holder mt-0 py-3 py-sm-5 py-md-10 bg-cover lazyload" style="background-image: url({{ asset('images/07.jpg') }}); height: 500px; position: relative;">
    @else
    <div class="holder mt-0 py-3 py-sm-5 py-md-10 bg-cover lazyload" style="background-image: url({{ asset('images/12-1.jpg') }}); height: 500px; position: relative;">
    @endif
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
                            <img srcset="{{ asset($data['admin']->website_logo) }}" alt="Logo" style="width: 200px;">
                        </div>
                        <div class="page-title-bg" align="center">
                            <h1 style="color: white;">Feedback on {{ $product_name }}</h1>
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
            <div class="row">
                <div class="col-12" align="center">
                    <h1>Message Feedback From Our Customers</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="holder mb-5">
        <div class="container">
            @foreach($feedbacks as $feedback)
                @if($feedback->id == 2 || $feedback->id == 7)
                    @if($feedback->id == 2)
                    <div class="row">
                    @endif
                        <div class="col-md-6">
                            <div class="form-group mb-5">
                                <div class="row">
                                    <div class="col-md-8 offset-md-2">
                                        <div class="" style="background-color: #ffc0bb;
                                                             text-align: center;
                                                             border-radius: 25px;
                                                             margin-bottom: 5px;
                                                             padding: 10px;
                                                             font-size: 30px;">
                                            <b>{{ $feedback->title }}</b>
                                        </div>
                                    </div>
                                </div>
                                @if(isset($feedback_details[$feedback->id]))
                                @php
                                    $count_images = count($feedback_details[$feedback->id]);
                                @endphp
                                <div class="row">
                                    <div class="col-md-8 offset-md-2">
                                        <div style="background-color: #ffc0bb;
                                                             text-align: center;
                                                             border-radius: 25px;
                                                             padding: 25px;">
                                            <div class="row">
                                                @foreach($feedback_details[$feedback->id] as $feedback_image)
                                                    @if($count_images > 1)
                                                        <div class="col-6">
                                                            <img src="{{ asset($feedback_image->image) }}" width="100%" style="border-radius: 25px;">
                                                        </div>
                                                    @else
                                                        <div class="col-12">
                                                            <img src="{{ asset($feedback_image->image) }}" width="100%" style="border-radius: 25px;">
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    @if($feedback->id == 7)
                    </div>
                    @endif
                @else
                    <div class="form-group mb-5">
                        <div class="row">
                            <div class="col-md-4 offset-md-4">
                                @if($feedback->products == 1)
                                <div class="" style="background-color: #7cb684;
                                                     text-align: center;
                                                     border-radius: 25px;
                                                     margin-bottom: 5px;
                                                     padding: 10px;
                                                     font-size: 30px;">
                                @else
                                <div class="" style="background-color: #ffc0bb;
                                                     text-align: center;
                                                     border-radius: 25px;
                                                     margin-bottom: 5px;
                                                     padding: 10px;
                                                     font-size: 30px;">
                                @endif
                                    <b>{{ $feedback->title }}</b>
                                </div>
                            </div>
                        </div>
                        @if(isset($feedback_details[$feedback->id]))
                        @php
                            $count_images = count($feedback_details[$feedback->id]);
                        @endphp
                        <div class="row">
                            <div class="col-md-8 offset-md-2">
                                @if($feedback->products == 1)
                                <div style="background-color: #7cb684;
                                                     text-align: center;
                                                     border-radius: 25px;
                                                     padding: 25px;">
                                @else
                                <div style="background-color: #ffc0bb;
                                                     text-align: center;
                                                     border-radius: 25px;
                                                     padding: 25px;">
                                @endif
                                    <div class="row">
                                        @foreach($feedback_details[$feedback->id] as $feedback_image)
                                            @if($count_images > 1)
                                                <div class="col-6" style="margin-bottom: 3rem;">
                                                    <img src="{{ asset($feedback_image->image) }}" width="100%" style="border-radius: 25px;">
                                                </div>
                                            @else
                                                <div class="col-12" style="margin-bottom: 3rem;">
                                                    <img src="{{ asset($feedback_image->image) }}" width="100%" style="border-radius: 25px;">
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                @endif
            @endforeach
            <div class="form-group" align="center">
                <div class="row">
                    <div class="col-md-4 offset-md-4">
                        <h1 style="line-height: 2; font-weight: bolder;">Visit Our <a href="{{ $data['admin']->facebook }}" style="background-color: #3B5998 !important; border-radius: 25px;"><i class="icon-facebook" style="color: #fff !important; padding: 15px; line-height: 2;"></i></a> To View More Comment & Feedback!</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection