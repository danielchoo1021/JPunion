@extends('layouts.app')
@section('content')
<div class="page-header" style="background-image: url({{ asset($data['setting_header']->shop_image) }});">

</div>
<div class="shop">
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-4 col-lg-3">
                <div class="shop-sidebar">
                    <div class="shop-sidebar__content">
                        <div class="shop-sidebar__section -categories">
                            <div class="section-title -style1 -medium" style="margin-bottom:1.875em">
                                <h2 style="">{{ isset($data['lang']['lang']['search']) ? $data['lang']['lang']['search'] :'搜索' }}</h2>
                                <!-- <img src="{{ asset('frontend/assets/images/introduction/IntroductionOne/content-deco.png') }}" alt="Decoration"/> -->
                            </div>
                            <form method="GET" action="{{ route('listing') }}">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="result" placeholder="{{ isset($data['lang']['lang']['search_products']) ? $data['lang']['lang']['search_products'] :'搜索产品' }}" style="border-radius: 0px;">
                                </div>
                                <button class="btn set_button set_text" style="font-size: 0.7125em;">
                                    <i class="fa fa-search"></i> {{ isset($data['lang']['lang']['search']) ? $data['lang']['lang']['search'] :'搜索' }}
                                </button>
                            </form>
                        </div>
                        @if(!$categories->isEmpty())
                            <div class="shop-sidebar__section -categories">
                                <div class="section-title -style1 -medium" style="margin-bottom:1.5em">
                                    <a href="#" class="display_categories">
                                    <h2 style="">{{ isset($data['lang']['lang']['categories']) ? $data['lang']['lang']['categories'] :'分类' }}
                                        <i class="fa fa-caret-right" id="arrow"></i>
                                    </h2>
                                    </a>
                                    <!-- <img src="{{ asset('frontend/assets/images/introduction/IntroductionOne/content-deco.png') }}" alt="Decoration"/> -->
                                </div>
                                <ul class="all_categories" style="display: none;">
                                    @foreach($categories as $category)
                                    <li>
                                        @if(count($category->get_sub_categories) > 0)
                                        <div class="row">
                                            <div class="col-9">
                                                <a href="{{ route('listing', ['category='.urlencode($category->category_name),
                                                                    'brand='.request('brand'),
                                                                    'from='.request('from'),
                                                                    'to='.request('to'),
                                                                    'result='.request('result')]) }}" class=" reset-anchor" data-filter="{{ $category->category_name }}"  style="display: block;">
                                                    {{ $category->category_name }}
                                                </a>
                                                </div>
                                                <div class="col-3 main_category" align="right" style="cursor: pointer;">
                                                    @if(count($category->get_sub_categories) > 0) 
                                                    <span class="fa fa-chevron-right arrow-right "></span>
                                                    @endif
                                                </div>
                                            </div>
                                        @else
                                        <a href="{{ route('listing', ['category='.urlencode($category->category_name),
                                                                    'brand='.request('brand'),
                                                                    'from='.request('from'),
                                                                    'to='.request('to'),
                                                                    'result='.request('result')]) }}" style="display: block;">
                                            <div class="row">
                                                <div class="col-9">
                                                    {{ $category->category_name }}
                                                </div>
                                                <div class="col-3" align="right">
                                                    @if(count($category->get_sub_categories) > 0) 
                                                    <span class="fa fa-chevron-right arrow-right"></span>
                                                    @endif
                                                </div>
                                            </div>
                                        </a>
                                        @endif
                                        @if(count($category->get_sub_categories) > 0)
                                            @foreach($category->get_sub_categories as $sub_category)
                                            <div class="sub_categories">
                                                <a href="{{ route('listing', ['category='.urlencode($category->category_name),
                                                                            'subcategory='.urlencode($sub_category->sub_category_name)]) }}"
                                                    data-id="0" class="{{ (!empty(request('subcategory')) && request('subcategory') == $sub_category->sub_category_name) ? 'active' : '' }}" style="display: block;">
                                                    <i class="fa fa-caret-right" aria-hidden="true"></i>
                                                    {{ $sub_category->sub_category_name }}
                                                </a>
                                            </div>
                                            @endforeach
                                        @endif
                                        
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if(!$brands->isEmpty())
                            <div class="shop-sidebar__section -categories">
                                <div class="section-title -style1 -medium" style="margin-bottom:1.875em">
                                    <a href="#" class="display_brands">
                                        <h2 style="">{{ isset($data['lang']['lang']['brand']) ? $data['lang']['lang']['brand'] :'品牌' }}
                                            <i class="fa fa-caret-right" id="arrow_brands"></i>
                                        </h2>
                                    </a>
                                </div>
                                <ul class="all_brands" style="display: none;">
                                    @foreach($brands as $brand)
                                        <li>
                                            <a href="{{ route('listing', ['brand='.urlencode($brand->brand_name),
                                                                          'category='.request('category'),
                                                                          'result='.request('result')]) }}">
                                                {{ $brand->brand_name }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-8 col-lg-9">
                <div class="form-group">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                
                                {{ isset($data['lang']['lang']['listing_showing']) ? $data['lang']['lang']['listing_showing'] :'显示' }} {{ count($products) }} / {{ $count_p }} {{ isset($data['lang']['lang']['listing_results']) ? $data['lang']['lang']['listing_results'] :'结果' }} 
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="row">
                                <div class="col-6">
                                     {{ isset($data['lang']['lang']['per_page']) ? $data['lang']['lang']['per_page'] :'每页' }}: 
                                </div>
                                <div class="col-6">
                                    <select name="per_page" class="form-control per_page">
                                        <option {{ (!empty(request('per_page')) && request('per_page') == '12') ? 'selected' : ''  }} value="12">12</option>
                                        <option {{ (!empty(request('per_page')) && request('per_page') == '50') ? 'selected' : ''  }} value="50">50</option>
                                        <option {{ (!empty(request('per_page')) && request('per_page') == '100') ? 'selected' : ''  }} value="100">100</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    @if(!empty(request('result')))
                        <span class="badge bg-success p-2">
                            {{ isset($data['lang']['lang']['name']) ? $data['lang']['lang']['name'] :'Name' }}: {{ request('result') }} &nbsp;&nbsp;
                            <a href="{{ route('listing', ['category='.request('category'),
                                                          'brand='.request('brand'),
                                                          'from='.request('from'),
                                                          'to='.request('to')]) }}" style="color: white;">
                                <i class="fa fa-times"></i>
                            </a>
                        </span>
                    @endif

                    @if(!empty(request('other_category')))
                        <span class="badge bg-success p-2">
                            {{ isset($data['lang']['lang']['other']) ? $data['lang']['lang']['other'] :'其他' }}: {{ request('other_category') }}  &nbsp;&nbsp;
                            <a href="{{ route('listing', ['result='.request('result'),
                                                          'brand='.request('brand'),
                                                          'from='.request('from'),
                                                          'to='.request('to')]) }}" style="color: white;">
                                <i class="fa fa-times"></i>
                            </a>
                        </span>
                    @endif

                    @if(!empty(request('category')))
                        <span class="badge bg-success p-2">
                            {{ isset($data['lang']['lang']['categories']) ? $data['lang']['lang']['categories'] :'分类' }}: {{ request('category') }}  &nbsp;&nbsp;
                            <a href="{{ route('listing', ['result='.request('result'),
                                                          'brand='.request('brand'),
                                                          'from='.request('from'),
                                                          'to='.request('to')]) }}" style="color: white;">
                                <i class="fa fa-times"></i>
                            </a>
                        </span>
                    @endif

                    @if(!empty(request('subcategory')))
                        <span class="badge bg-success p-2">
                            {{ isset($data['lang']['lang']['sub_category']) ? $data['lang']['lang']['sub_category'] :'Subcategory' }}: {{ request('subcategory') }}  &nbsp;&nbsp;
                            <a href="{{ route('listing', ['result='.request('result'),
                                                          'brand='.request('brand'),
                                                          'from='.request('from'),
                                                          'to='.request('to')]) }}" style="color: white;">
                                <i class="fa fa-times"></i>
                            </a>
                        </span>
                    @endif

                    @if(!empty(request('brand')))
                        <span class="badge bg-success p-2">
                            {{ isset($data['lang']['lang']['brand']) ? $data['lang']['lang']['brand'] :'品牌' }}: {{ request('brand') }}  &nbsp;&nbsp;
                            <a href="{{ route('listing', ['result='.request('result'),
                                                          'category='.request('category'),
                                                          'from='.request('from'),
                                                          'to='.request('to')]) }}" style="color: white;">
                                <i class="fa fa-times"></i>
                            </a>
                        </span>
                    @endif
                </div>
                <hr>

                @if(!$products->isEmpty())
                    <div class="shop-products">
                        <div class="shop-products__gird">
                            <div class="row">
                                @foreach($products as $featured)
                                    <div class="col-sm-4 col-6 product-listing my-3 mb-px-5">
                                        <div class='container-box' style="font-size: 13px;" align="center">
                                            <a href="{{ route('details', md5($featured->id)) }}">
                                                <div class="product ">
                                                    @if($featured->packages == 1)
                                                    <div class="product-type">
                                                        <h5 class="-new">
                                                            {{ isset($data['lang']['lang']['packages']) ? $data['lang']['lang']['packages'] :'配套' }}
                                                        </h5>
                                                    </div>
                                                    @endif
                                                    <div class="product-thumb">
                                                        <div style="background-image: url({{ (!empty($featured->first_image->image)) ? asset($featured->first_image->image) : asset('frontend/assets/images/product/1.png') }});
                                                                    background-repeat: no-repeat;
                                                                    background-size: 100%;
                                                                    background-position: center;
                                                                    width: 100%;
                                                                    height: 200px;" class="listing-image">
                                                                    
                                                        </div>
                                                        <!-- <img src="{{ (!empty($featured->first_image->image)) ? asset($featured->first_image->image) : asset('frontend/assets/images/product/1.png') }}" width="100%"> -->
                                                    </div>
                                                    <div class="product-content">
                                                        <div class="product-content__header" style="text-align: center; display: block;">
                                                            <div class="product-category">
                                                                {{ !empty($featured->one_product_brand->brand_name) ? $featured->one_product_brand->brand_name : '' }} &nbsp;
                                                            </div>
                                                        </div>
                                                        <span class="product-name">
                                                            {{ $featured->product_name }}
                                                        </span>
                                                        @if(!empty($featured->product_name_cn))
                                                            <span class="product-name">
                                                                {{ $featured->product_name_cn }}
                                                            </span>
                                                        @endif
                                                        <div class="product-content__footer" style="text-align: center; display: block;">
                                                            <h5 class="product-price--main h-33" style="font-size: 15px; color: #000 !important;">
                                                                @php
                                                                    $special_price_available = 0;
                                                                    if(!empty($get_pricing[$featured->id]['product_special_range']) && $get_pricing[$featured->id]['product_special_range'] != $get_pricing[$featured->id]['product_price_range']){
                                                                        $special_price_available = 1;
                                                                    }

                                                                    if(!empty($original_pricing[$featured->id]['product_price_range']) && $original_pricing[$featured->id]['product_price_range'] != $get_pricing[$featured->id]['product_price_range']){
                                                                        $special_price_available = 1;
                                                                    }
                                                                @endphp
                                                                <div class="product__item__price {{ $special_price_available == '1' ? 'text-red' : '' }}">
                                                                    RM {!! $get_pricing[$featured->id]['product_price_range'] !!}
                                                                    @if(!empty($original_pricing[$featured->id]['product_price_range']) && $original_pricing[$featured->id]['product_price_range'] != $get_pricing[$featured->id]['product_price_range'])
                                                                        <div class="product__item__price {{ $special_price_available == '1' ? 'text-grey' : '' }}">
                                                                            <small>
                                                                                <del>
                                                                                    RM {!! $original_pricing[$featured->id]['product_price_range'] !!}
                                                                                </del>
                                                                            </small>
                                                                        </div>
                                                                    @elseif(!empty($get_pricing[$featured->id]['product_special_range']) && $get_pricing[$featured->id]['product_special_range'] != $get_pricing[$featured->id]['product_price_range'])
                                                                        <div class="product__item__price {{ $special_price_available == '1' ? 'text-grey' : '' }}">
                                                                            <small>
                                                                                <del>
                                                                                    RM {!! $get_pricing[$featured->id]['product_special_range'] !!}
                                                                                </del>
                                                                            </small>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </h5>
                                                            	@php
							                                        $disabled_setting_sold_display_product = ($data['web_setting']->setting_sold_display_product == 1) ? '' : 'disabled';
							                                    @endphp	
                                                            @if($disabled_setting_sold_display_product != 'disabled')
                                                                <span style="font-family: 'Cairo', 'sans-serif' !important; padding-bottom: 0.5em;">
                                                                    {{ $sold_amount[$featured->id] }} {{ isset($data['lang']['lang']['sold']) ? $data['lang']['lang']['sold'] :'sold'}}
                                                                </span>
                                                             @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                                <div class="endofproduct" data-id="1"></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group d-flex justify-content-center pt-3">
                        <div class="row">
                            <div class="col-12">
                                {{ $products->links() }}
                            </div>
                        </div>
                    </div>
                @else
                    <div class="form-group pb-5" align="center">
                        <img src="{{ asset('images/no result_1.png') }}" class="h-220">
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')

<script type="text/javascript">
    $('.display_categories').click( function(e){
        e.preventDefault();
        if($('.all_categories').css('display') == 'none'){
            $('.all_categories').show("slow");
            $("#arrow").toggleClass('fa fa-caret-right fa fa-caret-down');
        }else{
            $('.all_categories').hide("slow");
            $("#arrow").toggleClass('fa fa-caret-right fa fa-caret-down');
        }
    });

    $('.display_brands').click( function(e){
        e.preventDefault();
        if($('.all_brands').css('display') == 'none'){
            $('.all_brands').show("slow");
            $("#arrow_brands").toggleClass('fa fa-caret-right fa fa-caret-down');
        }else{
            $('.all_brands').hide("slow");
            $("#arrow_brands").toggleClass('fa fa-caret-right fa fa-caret-down');
        }
    });

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
        // ele.toggleClass('fa-chevron-down');
        // alert(ele.parent().html());
        ele.closest('li').find('.sub_categories').slideToggle('fast', function(){});
    });

    $('.inside-sub-category').click( function(e){
        e.preventDefault();

        var ele = $(this);
        // ele.find('.arrow-right').toggleClass('fa-chevron-down');
        // alert(ele.parent().html());
        ele.closest('li').find('.sub_sub_categories').slideToggle('fast', function(){});
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

<script type="text/javascript">
    $(window).scroll(function() {
        var hT = $('.endofproduct').offset().top,
            hH = $('.endofproduct').outerHeight(),
            wH = $(window).height(),
            wS = $(this).scrollTop();

        var count_listing = $('.product-listing').length;
        var a = $('.endofproduct').data('id');

        if($(window).scrollTop() == $(document).height() - $(window).height()) {
            var per_page = '{{ request("per_page") }}';
            var totalPage;
            var fd = new FormData();
            

            // alert(per_page);
            if(per_page != ''){
                // alert(a);
                totalPage = parseFloat(per_page) - parseFloat(count_listing);
                a = parseFloat(a)+1;
                fd.append('page', a);
                $('.endofproduct').attr('data-id', a)
            }
        }
    });

    $('.per_page').change(function(){
        var ele = $(this);
        var val = ele.val();
        var url = "{{ route('listing', ['per_page=:per_page']) }}";

        url = url.replace(':per_page', val);
        window.location.href = url;
    });
</script>
@endsection