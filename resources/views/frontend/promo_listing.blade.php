@extends('layouts.app')

@section('css')
    <style type="text/css">
        @media only screen and (max-width: 431px){
            .listing-image {
                min-height: unset !important;
                height: 20vh !important;
            }
        }
    </style>
@endsection

@section('content')
<div class="breadcrumb">
    <div class="container">
      <h2>{{ isset($data['lang']['lang']['promotion']) ? $data['lang']['lang']['promotion'] :'优惠' }}</h2>
      <ul><li>{{ isset($data['lang']['lang']['home']) ? $data['lang']['lang']['home'] :'首页' }}</li><li class="active">{{ isset($data['lang']['lang']['promotion']) ? $data['lang']['lang']['promotion'] :'优惠' }}</li></ul>
    </div>
</div>
<div class="shop">
    <div class="container">
        <div class="row">
            <div class="col-12">
                @foreach($titles as $title)
                <div class="form-group" align="center">
                    <h3 style="">{{ $title->promo_title }}</h3>
                    <hr>
                    <div class="container">
                        <div class="row">
                            <div class="col-3" align="center">
                                <div id="days{{ $title->id }}" style="border: 1px solid #ddd; width: 50px; height: 50px; border-radius: 100%; padding: 15px 0;">
                                </div>
                            </div>
                            <div class="col-3" align="center">
                                <div id="hours{{ $title->id }}" style="border: 1px solid #ddd; width: 50px; height: 50px; border-radius: 100%; padding: 15px 0;">
                                </div>
                            </div>
                            <div class="col-3" align="center">
                                <div id="minutes{{ $title->id }}" style="border: 1px solid #ddd; width: 50px; height: 50px; border-radius: 100%; padding: 15px 0;">
                                </div>
                            </div>
                            <div class="col-3" align="center">
                                <div id="seconds{{ $title->id }}" style="border: 1px solid #ddd; width: 50px; height: 50px; border-radius: 100%; padding: 15px 0;">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-3" align="center">
                                {{ isset($data['lang']['lang']['days']) ? $data['lang']['lang']['days'] :'Days'}}
                            </div>
                            <div class="col-3" align="center">
                                {{ isset($data['lang']['lang']['hours']) ? $data['lang']['lang']['hours'] :'小时'}}
                            </div>
                            <div class="col-3" align="center">
                                {{ isset($data['lang']['lang']['minutes']) ? $data['lang']['lang']['minutes'] :'分钟'}}
                            </div>
                            <div class="col-3" align="center">
                                {{ isset($data['lang']['lang']['seconds']) ? $data['lang']['lang']['seconds'] :'秒'}}
                            </div>
                        </div>
                    </div>
                    <hr>
                </div>
                <div class="form-group mb-5">
                    <div class="row">
                        @foreach($promo_items[$title->id] as $promo_item)
                        @php 
                          if(Auth::guard('merchant')->check() || Auth::guard('admin')->check()){
                                $product_price = number_format($pricing[$promo_item->pai_id][1], 2);
                          }else{
                                if(!empty($promo_item->pai_special_price)){
                                    $product_price = number_format($promo_item->pai_special_price, 2);
                                }else{
                                    $product_price = number_format($promo_item->pai_price, 2);
                                }
                          }

                          if(Auth::guard('merchant')->check() || Auth::guard('admin')->check()){
                                $minSpecialPrice = number_format($sec_var_price_range[$promo_item->pai_id][0], 2);
                                $maxSpecialPrice = number_format($sec_var_price_range[$promo_item->pai_id][1], 2);
                                $minPrice = number_format($sec_var_price_range[$promo_item->pai_id][2], 2);
                                $maxPrice = number_format($sec_var_price_range[$promo_item->pai_id][3], 2);
                          }else{
                                $minSpecialPrice = number_format($sec_var_price_range_customer[$promo_item->pai_id][0], 2);
                                $maxSpecialPrice = number_format($sec_var_price_range_customer[$promo_item->pai_id][1], 2);
                                $minPrice = number_format($sec_var_price_range_customer[$promo_item->pai_id][2], 2);
                                $maxPrice = number_format($sec_var_price_range_customer[$promo_item->pai_id][3], 2);
                          }
                        @endphp
                        <div class="col-lg-3 col-6">
                            <div class='container-box' style="font-size: 13px;" align="center">
                                <a href="{{ route('promo_details', [str_replace('/', '-', $promo_item->product_name), md5($promo_item->pai_id)]) }}">
                                    <div class="form-group">
                                        <div class="product ">
                                            <!-- <div class="product-type"><h5 class="-new"></h5></div> -->
                                            <div class="product-thumb">
                                                <div style="background-image: url({{ (!empty($PromoImages[$promo_item->id]->image)) ? asset($PromoImages[$promo_item->id]->image) : asset('frontend/assets/images/product/1.png') }});
                                                              background-repeat: no-repeat;
                                                              background-size: cover;
                                                              background-position: center;
                                                              width: 100%;
                                                              height: 250px;" class="listing-image"></div>
                                            </div>
                                            <div class="product-content">
                                                <div class="product-content__header" style="text-align: center; display: block;">
                                                    <div class="product-category">
                                                        {{ $promo_item->brand_name }} &nbsp;
                                                    </div>
                                                </div>
                                                <span class="product-name">
                                                    {{ $promo_item->product_name }}
                                                </span>
                                                @if(!empty($promo_item->product_name_cn))
                                                    <span class="product-name">
                                                        {{ $promo_item->product_name_cn }}
                                                    </span>
                                                @endif
                                                <!-- <div class="" style="color: #888; height: 35px;">
                                                    @if(!empty($promo_item->pv_variation_name))
                                                    <small style="display: block; 
                                                                  width: 100%;
                                                                  white-space: nowrap;
                                                                  overflow: hidden;
                                                                  text-overflow: ellipsis;">
                                                        Option : {{ $promo_item->pv_variation_name }}
                                                    </small>
                                                    @endif

                                                    @if(!empty($promo_item->psv_variation_name))
                                                    <small  style="display: block; 
                                                                  width: 100%;
                                                                  white-space: nowrap;
                                                                  overflow: hidden;
                                                                  text-overflow: ellipsis;">
                                                        Second Option : {{ $promo_item->psv_variation_name }}
                                                    </small>
                                                    @endif
                                                </div> -->
                                                <div class="product-content__footer" style="text-align: center; display: block;">
                                                    <h5 class="product-price--main" style="color: #0abab5;">
                                                        
                                                            @if(Auth::guard('merchant')->check() || Auth::guard('admin')->check())
                                                                @if($promo_item->second_variation_enable == 1 || $promo_item->variation_enable == 1)
                                                                    @if($minSpecialPrice == $maxSpecialPrice && $minPrice == $maxPrice)
                                                                        @if($minSpecialPrice == 0 && $maxSpecialPrice == 0)
                                                                            RM {{ $maxPrice }}
                                                                        @else
                                                                            RM {{ $minSpecialPrice }}<small><del> RM {{ $maxPrice }}</del></small>
                                                                        @endif
                                                                    @else
                                                                        @if($minSpecialPrice > 0)
                                                                            RM {{ $minSpecialPrice }} - {{ $maxSpecialPrice }}<small><del> RM {{ $minPrice }} - {{ $maxPrice }}</del></small>
                                                                        @else
                                                                            RM {{ $minPrice }} - {{ $maxPrice }}
                                                                        @endif
                                                                    @endif
                                                                @else
                                                                    RM {{ $product_price }} <small><del>RM {{ number_format($promo_item->pai_price, 2) }}</del></small>
                                                                @endif
                                                            @else
                                                                @if(!empty($maxSpecialPrice))
                                                                    @if($promo_item->second_variation_enable == 1 || $promo_item->variation_enable == 1)
                                                                        @if($minSpecialPrice == $maxSpecialPrice)
                                                                            RM {{ $maxSpecialPrice }}
                                                                            <small><del> RM {{ $maxPrice }}</del></small>
                                                                        @else
                                                                            RM {{ $minSpecialPrice }} - RM {{ $maxSpecialPrice }} <small><del> RM {{ $minPrice }} - {{ $maxPrice }}</del></small>
                                                                        @endif

                                                                    @else
                                                                        @if($minSpecialPrice == $maxSpecialPrice)
                                                                            RM {{ $maxSpecialPrice }} <small><del> RM {{ $maxPrice }}</del></small>
                                                                        @else
                                                                            RM {{ $minSpecialPrice }} - RM {{ $maxSpecialPrice }} <small><del> RM {{ $minPrice }} - {{ $maxPrice }}</del></small>
                                                                        @endif
                                                                    @endif
                                                                @else
                                                                    @if($promo_item->second_variation_enable == 1 || $promo_item->variation_enable == 1)
                                                                        @if($minPrice == $maxPrice)
                                                                            RM {{ $minPrice }} <small><del> RM {{ $maxPrice }}</del></small>
                                                                        @else
                                                                            RM {{ $minPrice }} - RM {{ $maxPrice }} <small><del> RM {{ $minPrice }} - {{ $maxPrice }}</del></small>
                                                                        @endif

                                                                    @else
                                                                        @if($minPrice == $maxPrice)
                                                                            RM {{ $minPrice }} <small><del> RM {{ $maxPrice }}</del></small>
                                                                        @else
                                                                            RM {{ $minPrice }} - RM {{ $maxPrice }} <small><del> RM {{ $minPrice }} - {{ $maxPrice }}</del></small>
                                                                        @endif
                                                                    @endif
                                                                @endif
                                                            @endif
                                                        
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')

<script type="text/javascript">
    $('.add-to-wish-btn').click( function(e){
        e.preventDefault();
        $('.loading-gif').show();
        var ele = $(this);
        var isAdmin = '{{ Auth::guard("admin")->check() }}';
        var isMerchant = '{{ Auth::guard("merchant")->check() }}';
        var isUser = '{{ Auth::check() }}';

        if(isAdmin){
            auth_check = isAdmin;
        }else if(isMerchant){
            auth_check = isMerchant;
        }else if(isUser){
            auth_check = isUser;
        }else{
            auth_check = "";
        }
        var id = ele.data('id');
        var nameProduct = ele.parent().parent().find('.js-name-b2').html();
        if(auth_check){
            var fd = new FormData();
            fd.append('product_id', id);

            $.ajax({
                url: '{{ route("Favourite") }}',
                type: 'post',
                data: fd,
                contentType: false,
                processData: false,
                success: function(response){
                    $('.loading-gif').hide();
                    if(ele.hasClass('active') == true){
                    	// ele.removeClass('active');
                    	toastr.success('Removed from wish list');
                    }else{
                    	// ele.addClass('active');
                    	toastr.success('Added to wish list');
                    }

                    $('.wishlist_count').html(response);
                }
            });
        }else{
            window.location.href = "{{ route('login') }}";
        }
  });


$('.add-to-cart-btn').click( function(e){
    e.preventDefault();
    $('.loading-gif').show();
    var ele = $(this);
    var isAdmin = '{{ Auth::guard("admin")->check() }}';
    var isMerchant = '{{ Auth::guard("merchant")->check() }}';
    var isUser = '{{ Auth::check() }}';

    if(isAdmin){
        auth_check = isAdmin;
    }else if(isMerchant){
        auth_check = isMerchant;
    }else if(isUser){
        auth_check = isUser;
    }else{
        auth_check = "";
    }

    if(auth_check){
        var fd = new FormData();
        fd.append('product_id', ele.data('id'));
        fd.append('quantity', '1');

        $.ajax({
            url: '{{ route("AddToCart") }}',
            type: 'post',
            data: fd,
            contentType: false,
            processData: false,
            success: function(response){
                // alert(response);
                // return false;
                $('.loading-gif').hide();

                if(response == 'wallet not enough balance'){
                    toastr.error('Wallet Balance Not Enough');
                    return false;
                }

                if(response == 'quantity error'){
                    toastr.error('Please Add Quantity At least 1');
                    return false;
                }

                if(response == 'quantity exceed error'){
                    toastr.error('Product Balance Quantity Not Enough');
                    return false;
                }

                if(response == 'ok'){
                    $.ajax({
                        url: '{{ route("CountCart") }}',
                        type: 'get',
                        success: function(response){
                            $('.cart_count span').html(response[0]);
                            $('.cart_price').html('RM '+parseFloat(response[1]).toFixed(2));
                            
                        }
                    });
                    
                    toastr.success('Items Add To Cart. <a href="{{ route("checkout") }}" class="view-cart-button pull-right"><i class="fa fa-shopping-cart"></i> View Cart</a>');
                }else{
                    toastr.error('Error Please Contact Admin');
                }
            },
        });
    }else{
        window.location.href = "{{ route('login') }}";
    }
});

$('.main_category').click( function(e){
    e.preventDefault();

    var ele = $(this);
    ele.find('.arrow-right').toggleClass('fa-chevron-down');
    // alert(ele.parent().html());
    ele.parent().find('.sub_categories').slideToggle('fast', function(){});
});

$('.product__item__pic').hover(function(){
        var ele = $(this);
        
        
        var nextImage = ele.parent().find('.hidden_feature_image').val();
        // alert(ele.attr('class'));
        if(nextImage){
            ele.css("transform", "rotateY(180deg)");
            ele.attr('style', 'background-image: url('+nextImage+') !important');            
        }
    }, function(){
        var ele = $(this);
        var current_image = ele.data('setbg');
        // ele.css("transform", "rotateY(90deg)");
        ele.attr('style', 'background-image: url('+current_image+') !important');
    }); 
</script>

@if(!empty(request('category')))
<script type="text/javascript">
    var categoryS = "{{ request('category') }}";
    $(document).ready(function() {
        $(window).on('load', function() {
            $('.main_category').filter(function(){return $(this).data('filter')==categoryS}).click();
        });
    });
</script>
@endif
@foreach($titles as $title)
<script type="text/javascript">
    var id = '{{ $title->id }}';
    var date_end = '{{ date("Y-m-t H:i:s", strtotime($title->date_end)) }}';

    var second = 1000,
          minute = second * 60,
          hour = minute * 60,
          day = hour * 24;

    var countDown = new Date(date_end).getTime(),
        x = setInterval(function() {

          var now = new Date().getTime(),
              distance = countDown - now;

          var display_day = Math.floor(distance / (day));
          var display_hour = Math.floor((distance % (day)) / (hour));
          var display_minutes = Math.floor((distance % (hour)) / (minute));
          var display_seconds = Math.floor((distance % (minute)) / second);

          document.getElementById('days'+id).innerText =  display_day,
          document.getElementById('hours'+id).innerText = display_hour,
          document.getElementById('minutes'+id).innerText = display_minutes,
          document.getElementById('seconds'+id).innerText = display_seconds;

        }, second)
</script>
@endforeach
@endsection