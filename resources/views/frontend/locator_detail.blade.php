@extends('layouts.app')
<style type="text/css">
    

    .rating {
      display: inline-block;
      position: relative;
      height: 50px;
      line-height: 50px;
      font-size: 25px !important;
    }

    .rating label {
      position: absolute;
      top: 0;
      left: 0;
      height: 100%;
      cursor: pointer;
    }

    .rating label:last-child {
      position: static;
    }

    .rating label:nth-child(1) {
      z-index: 5;
    }

    .rating label:nth-child(2) {
      z-index: 4;
    }

    .rating label:nth-child(3) {
      z-index: 3;
    }

    .rating label:nth-child(4) {
      z-index: 2;
    }

    .rating label:nth-child(5) {
      z-index: 1;
    }

    .rating label input {
      position: absolute;
      top: 0;
      left: 0;
      opacity: 0;
    }

    .rating label .icon {
      float: left;
      color: transparent;
    }

    .rating label:last-child .icon {
      color: #000;
    }

    .rating:not(:hover) label input:checked ~ .icon,
    .rating:hover label:hover input ~ .icon {
      color: #ff7600;
    }

    .rating label input:focus:not(:checked) ~ .icon:last-child {
      color: #000;
      text-shadow: 0 0 5px #09f;
    }
</style>
@section('content')
<div class="breadcrumb">
    <div class="container">
        <h2>{{ isset($data['lang']['lang']['locator']) ? $data['lang']['lang']['locator'] :'商店地点' }} ({{ $state->name }})</h2>
    </div>
</div>

<section class="blog spad">
    <div class="container">
        <div class="container my-3">
            <div class="form-group">
                <div class="row">
                    @foreach($corporates as $state)
                        <div class="col-6 container-box" style="margin-bottom: 2em;">
                            <h4 class="mb-3">
                                {{ $state->f_name }}
                            </h4>
                            @if(!empty($get_shop_rating[$state->id][0]))
                                <div class="form-group">
                                    <span style="font-size: 13px;">
                                        {{ $get_shop_rating[$state->id][0] }}
                                    </span>
                                    &nbsp;
                                    @if($get_shop_rating[$state->id][0] == 5)
                                        <i class="fas fa-star" style="color: #ff7600;"></i>
                                        <i class="fas fa-star" style="color: #ff7600;"></i>
                                        <i class="fas fa-star" style="color: #ff7600;"></i>
                                        <i class="fas fa-star" style="color: #ff7600;"></i>
                                        <i class="fas fa-star" style="color: #ff7600;"></i>
                                    @elseif($get_shop_rating[$state->id][0] == 4)
                                        <i class="fas fa-star" style="color: #ff7600;"></i>
                                        <i class="fas fa-star" style="color: #ff7600;"></i>
                                        <i class="fas fa-star" style="color: #ff7600;"></i>
                                        <i class="fas fa-star" style="color: #ff7600;"></i>
                                        <i class="far fa-star comments"></i>
                                    @elseif($get_shop_rating[$state->id][0] == 3)
                                        <i class="fas fa-star" style="color: #ff7600;"></i>
                                        <i class="fas fa-star" style="color: #ff7600;"></i>
                                        <i class="fas fa-star" style="color: #ff7600;"></i>
                                        <i class="far fa-star comments"></i>
                                        <i class="far fa-star comments"></i>
                                    @elseif($get_shop_rating[$state->id][0] == 2)
                                        <i class="fas fa-star" style="color: #ff7600;"></i>
                                        <i class="fas fa-star" style="color: #ff7600;"></i>
                                        <i class="far fa-star comments"></i>
                                        <i class="far fa-star comments"></i>
                                        <i class="far fa-star comments"></i>
                                    @elseif($get_shop_rating[$state->id][0] == 1)
                                        <i class="fas fa-star" style="color: #ff7600;"></i>
                                        <i class="far fa-star comments"></i>
                                        <i class="far fa-star comments"></i>
                                        <i class="far fa-star comments"></i>
                                        <i class="far fa-star comments"></i>
                                    @endif
                                    &nbsp;&nbsp;
                                    <span style="font-size: 13px;">
                                        ({{ $get_shop_rating[$state->id][1] }})
                                    </span>
                                </div>
                            @endif
                            <hr>
                            <p style="color: #000; font-size: 14px;" class="mb-3">
                                <i style="width: 20px; color: blue;" class="far fa-phone"></i> {{ $state->phone }}
                            </p>
                            <p style="color: #000; font-size: 14px;" class="mb-3">
                                <i style="width: 20px; color: #f4b1ad;" class="far fa-envelope"></i> {{ $state->email }}
                            </p>
                            @if(!empty($state->whatsapp))
                            <p style="color: #000; font-size: 14px;" class="mb-3">
                                <i class="fab fa-whatsapp" style="width: 20px; color: green;"></i>
                                <a href="https://api.whatsapp.com/send?phone=6{{ $state->whatsapp }}&source=&data=" target="_blank">
                                    {{ $state->whatsapp }}
                                </a>
                            </p>
                            @endif
                            <p style="color: #000; font-size: 14px;" class="mb-3">
                                <i style="width: 20px; color: red;" class="far fa-map-marker"></i> {{ $state->company_address }}
                            </p>
                            <p style="color: #000; font-size: 14px;" class="mb-3">
                                <i style="width: 20px; color: red;" class="far fa-map-marker"></i> {{ $state->company_postcode }}
                            </p>
                            <p style="color: #000; font-size: 14px;" class="mb-3">
                                <i style="width: 20px; color: red;" class="far fa-map-marker"></i> {{ $state->company_city }}
                            </p>
                            <p style="color: #000; font-size: 14px;" class="mb-3">
                                <i style="width: 20px; color: red;" class="far fa-map-marker"></i> {{ $state->get_state->name }}
                            </p>
                            @if($get_last_rating_voucher > 0)
                            <button class="btn btn-primary btn-block" data-toggle="modal" data-target="#state{{ $state->id }}">
                                Rate this shop
                            </button>
                            @endif

                            <div class="modal fade" id="state{{ $state->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <form method="POST" action="{{ route('rate_locator') }}" enctype="multipart/form-data">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">
                                                    Rate this shop
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <input type="hidden" name="shop_locator" value="{{ $state->id }}">
                                                    <div class="rating">
                                                        <label style="font-size: 25px;">
                                                            <input type="radio" name="stars" value="1" />
                                                            <span class="icon">★</span>
                                                        </label>
                                                        <label style="font-size: 25px;">
                                                            <input type="radio" name="stars" value="2" />
                                                            <span class="icon">★</span>
                                                            <span class="icon">★</span>
                                                        </label>
                                                        <label style="font-size: 25px;">
                                                            <input type="radio" name="stars" value="3" />
                                                            <span class="icon">★</span>
                                                            <span class="icon">★</span>
                                                            <span class="icon">★</span>   
                                                        </label>
                                                        <label style="font-size: 25px;">
                                                            <input type="radio" name="stars" value="4" />
                                                            <span class="icon">★</span>
                                                            <span class="icon">★</span>
                                                            <span class="icon">★</span>
                                                            <span class="icon">★</span>
                                                        </label>
                                                        <label style="font-size: 25px;">
                                                            <input type="radio" name="stars" value="5" />
                                                            <span class="icon">★</span>
                                                            <span class="icon">★</span>
                                                            <span class="icon">★</span>
                                                            <span class="icon">★</span>
                                                            <span class="icon">★</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Upload File</label>
                                                    <input type="file" class="form-control" name="rate_file">
                                                </div>
                                                <div class="form-group">
                                                    <label>Comments</label>
                                                    <textarea class="form-control" name="rate_comments"></textarea>
                                                </div>
                                            </div>                                            
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button class="btn btn-primary">Rate Now</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <br>
                            @if($get_shop_rating[$state->id][1] > 0)
                            <button class="btn btn-primary btn-block" data-toggle="modal" data-target="#details{{ $state->id }}">
                                View Rates
                            </button>
                            @endif
                            
                            <div class="modal fade" id="details{{ $state->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">
                                                Review summary
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-9">
                                                    <div>
                                                        <div class="row">
                                                            <div class="col-1">
                                                                <label>
                                                                    5
                                                                </label>
                                                            </div>
                                                            <div class="col-11">
                                                                <div class="progress">
                                                                    <div class="progress-bar w-{{ !empty($get_shop_rating[$state->id][2][5]) ? $get_shop_rating[$state->id][2][5] : 0 }}" role="progressbar" aria-valuenow="{{ !empty($get_shop_rating[$state->id][2][5]) ? $get_shop_rating[$state->id][2][5] : 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div class="row">
                                                            <div class="col-1">
                                                                <label>
                                                                    4
                                                                </label>
                                                            </div>
                                                            <div class="col-11">
                                                                <div class="progress">
                                                                    <div class="progress-bar w-{{ !empty($get_shop_rating[$state->id][2][4]) ? $get_shop_rating[$state->id][2][4] : 0 }}" role="progressbar" aria-valuenow="{{ !empty($get_shop_rating[$state->id][2][4]) ? $get_shop_rating[$state->id][2][4] : 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div class="row">
                                                            <div class="col-1">
                                                                <label>
                                                                    3
                                                                </label>
                                                            </div>
                                                            <div class="col-11">
                                                                <div class="progress">
                                                                    <div class="progress-bar w-{{ !empty($get_shop_rating[$state->id][2][3]) ? $get_shop_rating[$state->id][2][3] : 0 }}" role="progressbar" aria-valuenow="{{ !empty($get_shop_rating[$state->id][2][3]) ? $get_shop_rating[$state->id][2][3] : 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div class="row">
                                                            <div class="col-1">
                                                                <label>
                                                                    2
                                                                </label>
                                                            </div>
                                                            <div class="col-11">
                                                                <div class="progress">
                                                                    <div class="progress-bar w-{{ !empty($get_shop_rating[$state->id][2][2]) ? $get_shop_rating[$state->id][2][2] : 0 }}" role="progressbar" aria-valuenow="{{ !empty($get_shop_rating[$state->id][2][2]) ? $get_shop_rating[$state->id][2][2] : 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div class="row">
                                                            <div class="col-1">
                                                                <label>
                                                                    1
                                                                </label>
                                                            </div>
                                                            <div class="col-11">
                                                                <div class="progress">
                                                                    <div class="progress-bar w-{{ !empty($get_shop_rating[$state->id][2][1]) ? $get_shop_rating[$state->id][2][1] : 0 }}" role="progressbar" aria-valuenow="{{ !empty($get_shop_rating[$state->id][2][1]) ? $get_shop_rating[$state->id][2][1] : 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-3" align="center">
                                                    <div class="mt-4"></div>
                                                    <span style="font-size: 25px;">
                                                        {{ $get_shop_rating[$state->id][0] }}
                                                    </span>
                                                    <br>
                                                    @if($get_shop_rating[$state->id][0] == 5)
                                                        <i class="fas fa-star" style="color: #ff7600;"></i>
                                                        <i class="fas fa-star" style="color: #ff7600;"></i>
                                                        <i class="fas fa-star" style="color: #ff7600;"></i>
                                                        <i class="fas fa-star" style="color: #ff7600;"></i>
                                                        <i class="fas fa-star" style="color: #ff7600;"></i>
                                                    @elseif($get_shop_rating[$state->id][0] == 4)
                                                        <i class="fas fa-star" style="color: #ff7600;"></i>
                                                        <i class="fas fa-star" style="color: #ff7600;"></i>
                                                        <i class="fas fa-star" style="color: #ff7600;"></i>
                                                        <i class="fas fa-star" style="color: #ff7600;"></i>
                                                        <i class="far fa-star comments"></i>
                                                    @elseif($get_shop_rating[$state->id][0] == 3)
                                                        <i class="fas fa-star" style="color: #ff7600;"></i>
                                                        <i class="fas fa-star" style="color: #ff7600;"></i>
                                                        <i class="fas fa-star" style="color: #ff7600;"></i>
                                                        <i class="far fa-star comments"></i>
                                                        <i class="far fa-star comments"></i>
                                                    @elseif($get_shop_rating[$state->id][0] == 2)
                                                        <i class="fas fa-star" style="color: #ff7600;"></i>
                                                        <i class="fas fa-star" style="color: #ff7600;"></i>
                                                        <i class="far fa-star comments"></i>
                                                        <i class="far fa-star comments"></i>
                                                        <i class="far fa-star comments"></i>
                                                    @elseif($get_shop_rating[$state->id][0] == 1)
                                                        <i class="fas fa-star" style="color: #ff7600;"></i>
                                                        <i class="far fa-star comments"></i>
                                                        <i class="far fa-star comments"></i>
                                                        <i class="far fa-star comments"></i>
                                                        <i class="far fa-star comments"></i>
                                                    @endif
                                                    <br>
                                                    <span style="font-size: 13px;">
                                                        ({{ $get_shop_rating[$state->id][1] }})
                                                    </span>
                                                </div>
                                            </div>
                                            <hr>
                                            <h4>Reviews</h4>
                                            <hr>
                                            @foreach($state->get_ratings as $rating)
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-2">
                                                        @if(!empty($rating->get_reviewer_user->profile_logo))
                                                            <div style="background-image: url({{ asset($rating->get_reviewer_user->profile_logo) }}); width: 50px; height: 50px; border-radius: 100%; background-size: cover; background-position: center; background-repeat: no-repeat;"></div>
                                                        @elseif(!empty($rating->get_reviewer_agent->profile_logo))
                                                            <div style="background-image: url({{ asset($rating->get_reviewer_agent->profile_logo) }}); width: 50px; height: 50px; border-radius: 100%; background-size: cover; background-position: center; background-repeat: no-repeat;"></div>
                                                        @else
                                                            <div style="background-image: url({{ asset('images/images.png') }}); width: 50px; height: 50px; border-radius: 100%; background-size: cover; background-position: center; background-repeat: no-repeat;"></div>
                                                        @endif
                                                    </div>
                                                    <div class="col-10">
                                                        @if(!empty($rating->get_reviewer_user->f_name))
                                                            <span style="font-size: 15px;">
                                                                {{ $rating->get_reviewer_user->f_name }}
                                                            </span>
                                                        @elseif(!empty($rating->get_reviewer_agent->f_name))
                                                            <span style="font-size: 15px;">
                                                                {{ $rating->get_reviewer_agent->f_name }}
                                                            </span>
                                                        @endif
                                                        <br>
                                                        @if($rating->rating == 5)
                                                            <i class="fas fa-star" style="color: #ff7600;"></i>
                                                            <i class="fas fa-star" style="color: #ff7600;"></i>
                                                            <i class="fas fa-star" style="color: #ff7600;"></i>
                                                            <i class="fas fa-star" style="color: #ff7600;"></i>
                                                            <i class="fas fa-star" style="color: #ff7600;"></i>
                                                        @elseif($rating->rating == 4)
                                                            <i class="fas fa-star" style="color: #ff7600;"></i>
                                                            <i class="fas fa-star" style="color: #ff7600;"></i>
                                                            <i class="fas fa-star" style="color: #ff7600;"></i>
                                                            <i class="fas fa-star" style="color: #ff7600;"></i>
                                                            <i class="far fa-star comments"></i>
                                                        @elseif($rating->rating == 3)
                                                            <i class="fas fa-star" style="color: #ff7600;"></i>
                                                            <i class="fas fa-star" style="color: #ff7600;"></i>
                                                            <i class="fas fa-star" style="color: #ff7600;"></i>
                                                            <i class="far fa-star comments"></i>
                                                            <i class="far fa-star comments"></i>
                                                        @elseif($rating->rating == 2)
                                                            <i class="fas fa-star" style="color: #ff7600;"></i>
                                                            <i class="fas fa-star" style="color: #ff7600;"></i>
                                                            <i class="far fa-star comments"></i>
                                                            <i class="far fa-star comments"></i>
                                                            <i class="far fa-star comments"></i>
                                                        @elseif($rating->rating == 1)
                                                            <i class="fas fa-star" style="color: #ff7600;"></i>
                                                            <i class="far fa-star comments"></i>
                                                            <i class="far fa-star comments"></i>
                                                            <i class="far fa-star comments"></i>
                                                            <i class="far fa-star comments"></i>
                                                        @endif
                                                        <br>
                                                        {{ $rating->created_at }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" style="white-space: pre-line;">{{ $rating->rating_desc }}</div>
                                            @if(!empty($rating->rating_file))
                                            <div class="form-group">
                                                <a href="#" data-toggle="modal" data-target="#view-image{{ $rating->id }}">
                                                    <img src="{{ asset($rating->rating_file) }}" width="150px">
                                                </a>
                                                <div class="modal fade" id="view-image{{ $rating->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-body">
                                                                <img src="{{ asset($rating->rating_file) }}" width="100%">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                            <hr>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('js')
<script type="text/javascript">
    $('.comments').
</script>
@endsection