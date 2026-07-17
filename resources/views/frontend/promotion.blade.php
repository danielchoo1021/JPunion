@extends('layouts.app')

@section('content')
<section class="breadcrumb-option main-content">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb__text">
                    <h4>产品</h4>
                    <div class="breadcrumb__links">
                        <a href="{{ route('home') }}">首页</a> / 
                        <span>产品</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@if(!empty($brand_banner_image->id))
    <img src="{{ $brand_banner_image->banner_image }}" width="100%">
@endif
<section class="shop spad" style="padding-top: 0px;">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <h3>筛选</h3>
            </div>
            <div class="col-lg-9">
                <span>
                    @if(!empty(request('page')))
                    显示 {{ count($products) * request('page') }} 的 {{ $count_p }} 结果
                    @else
                    显示 {{ count($products) }} 的 {{ $count_p }} 结果
                    @endif
                </span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="搜索产品" style="border-radius: 0px;">
                </div>
                <button class="btn btn-primary btn-sm">
                    <i class="fa fa-search"></i> 搜索
                </button>
                <hr>
                <h3>类别</h3>
                @foreach($categories as $category)
                    <a href="{{ route('listing', ['category='.urlencode($category->category_name),
                                                  'brand='.request('brand'),
                                                  'result='.request('result')]) }}" 
                                                  class="{{ (request('category') == $category->category_name) ? 'current' : '' }}">
                        {{ $category->category_name }}
                    </a>
                @endforeach
                <hr>
                <h3>品牌</h3>
                @foreach($brands as $brand)
                    <a href="{{ route('listing', ['brand='.urlencode($brand->brand_name),
                                                    'category='.request('category'),
                                                    'result='.request('result')]) }}">
                        {{ $brand->brand_name }}
                    </a>
                @endforeach
                <hr>
            </div>
            <div class="col-lg-9">
                @if(!empty(request('result')))
                    <span class="badge bg-danger p-2">
                        Name: {{ request('result') }} &nbsp;&nbsp;
                        <a href="{{ route('listing', ['category='.request('category'),
                                                      'brand='.request('brand'),
                                                      'from='.request('from'),
                                                      'to='.request('to')]) }}" style="color: white;">
                            <i class="fa fa-times"></i>
                        </a>
                    </span>
                @endif

                @if(!empty(request('other_category')))
                    <span class="badge bg-danger p-2">
                        Others: {{ request('other_category') }}  &nbsp;&nbsp;
                        <a href="{{ route('listing', ['result='.request('result'),
                                                      'brand='.request('brand'),
                                                      'from='.request('from'),
                                                      'to='.request('to')]) }}" style="color: white;">
                            <i class="fa fa-times"></i>
                        </a>
                    </span>
                @endif

                @if(!empty(request('sub_category')))
                    <span class="badge bg-danger p-2">
                        Subcategory: {{ request('sub_category') }}  &nbsp;&nbsp;
                        <a href="{{ route('listing', ['result='.request('result'),
                                                      'brand='.request('brand'),
                                                      'from='.request('from'),
                                                      'to='.request('to')]) }}" style="color: white;">
                            <i class="fa fa-times"></i>
                        </a>
                    </span>
                @endif

                @if(!empty(request('category')))
                    <span class="badge bg-danger p-2">
                        类别: {{ request('category') }}  &nbsp;&nbsp;
                        <a href="{{ route('listing', ['result='.request('result'),
                                                      'brand='.request('brand'),
                                                      'from='.request('from'),
                                                      'to='.request('to')]) }}" style="color: white;">
                            <i class="fa fa-times"></i>
                        </a>
                    </span>
                @endif

                @if(!empty(request('brand')))
                    <span class="badge bg-danger p-2">
                        品牌: {{ request('brand') }}  &nbsp;&nbsp;
                        <a href="{{ route('listing', ['result='.request('result'),
                                                      'category='.request('category'),
                                                      'from='.request('from'),
                                                      'to='.request('to')]) }}" style="color: white;">
                            <i class="fa fa-times"></i>
                        </a>
                    </span>
                @endif

                @if(!empty(request('from')) && !empty(request('to')))
                    <span class="badge bg-danger p-2">
                        Price: {{ request('from') }} - {{ request('to') }} &nbsp;&nbsp;
                        <a href="{{ route('listing', ['result='.request('result'),
                                                      'category='.request('category'),
                                                      'brand='.request('brand')]) }}" style="color: white;">
                            <i class="fa fa-times"></i>
                        </a>
                    </span>
                @endif
                <hr>
                <div class="row">
                @foreach($products as $featured)
                @php
                    $discount_percentage = 0;
                    $second_percentage = 0;
                    if(Auth::guard('merchant')->check() || Auth::guard('admin')->check()){
                        if($featured->variation_enable == '1'){
                            if($featured->second_variation_enable == '1'){
                                if($priceV2[$featured->id][3] == $priceV2[$featured->id][2]){
                                    if($priceV2[$featured->id][4]){
                                        $discount_percentage = (($priceV2[$featured->id][5] - $priceV2[$featured->id][4])*100) / $priceV2[$featured->id][5];
                                    }
                                }else{
                                    if($priceV2[$featured->id][4]){
                                        $discount_percentage = (($priceV2[$featured->id][7] - $priceV2[$featured->id][6])*100) / $priceV2[$featured->id][7];
                                    }
                                }
                            }else{
                                if($priceV[$featured->id][3] == $priceV[$featured->id][2]){
                                    if($priceV[$featured->id][4]){
                                        $discount_percentage = (($priceV[$featured->id][5] - $priceV[$featured->id][4])*100) / $priceV[$featured->id][5];
                                    }
                                }else{
                                    if($priceV[$featured->id][4]){
                                        $discount_percentage = (($priceV[$featured->id][7] - $priceV[$featured->id][6])*100) / $priceV[$featured->id][7];
                                    }
                                }

                            }
                        }else{
                            if(!empty($featured->agent_special_price)){
                                $discount_percentage =  (($featured->agent_price - $featured->agent_special_price)*100) / $featured->agent_price;
                            }
                        }
                    }else{
                        if($featured->variation_enable == '1'){
                            if($featured->second_variation_enable == '1'){
                                if($priceV2[$featured->id][1] == $priceV2[$featured->id][0]){
                                    if($priceV2[$featured->id][8]){
                                        $discount_percentage = (($priceV2[$featured->id][9] - $priceV2[$featured->id][8])*100) / $priceV2[$featured->id][9];
                                    }
                                }else{
                                    if($priceV2[$featured->id][8]){
                                        $discount_percentage = (($priceV2[$featured->id][11] - $priceV2[$featured->id][10])*100) / $priceV2[$featured->id][10];
                                    }
                                }
                            }else{
                                if($priceV[$featured->id][1] == $priceV[$featured->id][0]){
                                    if($priceV[$featured->id][8]){
                                        $discount_percentage = (($priceV[$featured->id][9] - $priceV[$featured->id][8])*100) / $priceV[$featured->id][9];
                                    }
                                }else{
                                    if($priceV[$featured->id][8]){
                                        $discount_percentage = (($priceV[$featured->id][11] - $priceV[$featured->id][10])*100) / $priceV[$featured->id][10];
                                    }
                                }

                            }
                        }else{
                            if(!empty($featured->special_price)){
                                $discount_percentage = (($featured->price - $featured->special_price)*100) / $featured->price;
                            }
                        }
                    }

                @endphp
                <div class="col-lg-3 col-md-6 col-sm-6 col-md-6 col-6 mix new-arrivals">
                    
                    <a href="{{ route('details', md5($featured->id)) }}" style="display: block; margin-bottom: 30px;">
                        <div class="product__item">
                            <div class="form-group">
                                <!-- <img src="{{ (!empty($listingImages[$featured->id]->image)) ? asset($listingImages[$featured->id]->image) : asset('images/no-image-available-icon-61.jpg') }}" alt="Dish Name" class="img-responsive" width="100%"> -->
                                <!-- <div style="background-image: url({{ (!empty($listingImages[$featured->id]->image)) ? asset($listingImages[$featured->id]->image) : asset('images/no-image-available-icon-61.jpg') }});
                                            background-repeat: no-repeat;
                                            background-position: center;
                                            background-size: 100%;
                                            width: 100%;
                                            height: 200px">
                                </div> -->
                                <img src="{{ (!empty($listingImages[$featured->id]->image)) ? asset($listingImages[$featured->id]->image) : asset('images/no-image-available-icon-61.jpg') }}" width="100%">
                            </div>
                            
                            <div class="product__item__text">
                                <b>{{ $featured->brand_name }}</b>
                                <p style="height: 50px;">{{ $featured->product_name }}</p>
                                <!-- <a href="#" class="add-cart">+ Add To Cart</a> -->
                                <!-- <div class="rating">
                                    <i class="fa fa-star-o"></i>
                                    <i class="fa fa-star-o"></i>
                                    <i class="fa fa-star-o"></i>
                                    <i class="fa fa-star-o"></i>
                                    <i class="fa fa-star-o"></i>
                                </div> -->
                                @if(Auth::guard('merchant')->check() || Auth::guard('admin')->check())
                                    @if($featured->variation_enable == '1')
                                        @if($featured->second_variation_enable == '1')
                                            @if($priceV2[$featured->id][3] == $priceV2[$featured->id][2])
                                                <b class="item-price">
                                                    <span>RM {{ number_format($priceV2[$featured->id][3], 2) }}</span>
                                                </b>
                                            @else
                                                <b class="item-price">
                                                    <span>RM {{ number_format($priceV2[$featured->id][3], 2) }} - {{ number_format($priceV2[$featured->id][2], 2) }}</span>
                                                </b>

                                            @endif
                                        @else
                                            @if($priceV[$featured->id][3] == $priceV[$featured->id][2])
                                                <b class="item-price">
                                                    <span>RM {{ number_format($priceV[$featured->id][3], 2) }}</span>
                                                </b>
                                            @else
                                                <b class="item-price">
                                                    <span>RM {{ number_format($priceV[$featured->id][3], 2) }} - {{ number_format($priceV[$featured->id][2], 2) }}</span>
                                                </b>

                                            @endif
                                        @endif
                                    @else
                                        @if(!empty($featured->agent_special_price))
                                            <b class="item-price">
                                                @if($featured->agent_special_price != $featured->agent_price)
                                                    <span>RM {{ number_format($featured->agent_special_price, 2) }}</span>
                                                   
                                                    <br>
                                                    <del><small>RM{{ number_format($featured->agent_price, 2) }}</small></del>
                                                @else
                                                    <span>RM {{ number_format($featured->agent_special_price, 2) }}</span>
                                                @endif
                                            </b>
                                        @else
                                            <b class="item-price">
                                                <span>RM {{ number_format($featured->agent_price, 2) }}</span>
                                            </b>
                                        @endif
                                    @endif
                                @else
                                    @if($featured->variation_enable == '1')
                                        @if($featured->second_variation_enable == '1')
                                            @if($priceV2[$featured->id][1] == $priceV2[$featured->id][0])
                                                <b class="item-price">
                                                    <span>RM {{ number_format($priceV2[$featured->id][1], 2) }}</span>
                                                </b>
                                            @else
                                                <b class="item-price">
                                                    <span>RM {{ number_format($priceV2[$featured->id][1], 2) }} - {{ number_format($priceV2[$featured->id][0], 2) }}</span>
                                                </b>

                                            @endif
                                        @else
                                            @if($priceV[$featured->id][1] == $priceV[$featured->id][0])
                                                <b class="item-price">
                                                    <span>RM {{ number_format($priceV[$featured->id][1], 2) }}</span>
                                                </b>
                                            @else
                                                <b class="item-price">
                                                    <span>RM {{ number_format($priceV[$featured->id][1], 2) }} - {{ number_format($priceV[$featured->id][0], 2) }}</span>
                                                </b>

                                            @endif
                                        @endif
                                    @else
                                        @if(!empty($featured->special_price))
                                            <b class="item-price">
                                                @if($featured->special_price != $featured->price)
                                                    <span>RM {{ number_format($featured->special_price, 2) }}</span>
                                                    
                                                    <br>
                                                    <del><small>RM{{ number_format($featured->price, 2) }}</small></del>
                                                @else
                                                    <span>RM {{ number_format($featured->special_price, 2) }}</span>
                                                @endif
                                            </b>
                                        @else
                                            <b class="item-price">
                                                <span>RM {{ number_format($featured->price, 2) }}</span>
                                            </b>
                                        @endif
                                    @endif
                                @endif
                                @if(!empty($discount_percentage))
                                    <span class="badge" style="font-size: 12px; background-color: #e53637; border-radius: 0px; color: white;">
                                        -{{ number_format($discount_percentage) }}%
                                    </span>
                                @endif
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
                <!-- <div class="row">
                    <div class="col-lg-12">
                        <div class="product__pagination">
                            <a class="active" href="#">1</a>
                            <a href="#">2</a>
                            <a href="#">3</a>
                            <span>...</span>
                            <a href="#">21</a>
                        </div>
                    </div>
                </div> -->
                {{ $products->links() }}
            </div>
        </div>
    </div>
</section>
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
@endsection