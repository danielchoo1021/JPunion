@extends('layouts.app')
	<link href="css/style.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Open%20Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
	<style type="text/css">
		.slick-slide{
			height: auto;
		}
	</style>
@section('content')
	<!-- breadcrumb -->
	
	<div class="page-content">
	<div class="holder breadcrumbs-wrap mt-0">
	<div class="container">
		<ul class="breadcrumbs">
			<li><a href="{{ route('home') }}">{{ isset($data['lang']['lang']['home']) ? $data['lang']['lang']['home'] :'首页'}}</a></li>
			<li><span>{{ isset($data['lang']['lang']['shopping_cart']) ? $data['lang']['lang']['shopping_cart'] :'购物车'}}</span></li>
		</ul>
	</div>
</div>
	<div class="holder">
	<div class="container">
		<div class="page-title text-center">
			<h1>{{ isset($data['lang']['lang']['shopping_cart']) ? $data['lang']['lang']['shopping_cart'] :'购物车'}}</h1>
		</div>
		<div class="row">
			<div class="col-lg-8 col-xl-9">
				<div class="cart-table">
	<div class="cart-table-prd cart-table-prd--head py-1 d-none d-md-flex">
		<div class="cart-table-prd-image text-center">
			{{ isset($data['lang']['lang']['image']) ? $data['lang']['lang']['image'] :'图片'}}
		</div>
		<div class="cart-table-prd-content-wrap">
			<div class="cart-table-prd-info">{{ isset($data['lang']['lang']['product_name']) ? $data['lang']['lang']['product_name'] :'产品名字'}}</div>
			<div class="cart-table-prd-qty">{{ isset($data['lang']['lang']['quantity']) ? $data['lang']['lang']['quantity'] :'数量'}}</div>
			<div class="cart-table-prd-price">{{ isset($data['lang']['lang']['price']) ? $data['lang']['lang']['price'] :'价钱'}}</div>
			<div class="cart-table-prd-action">&nbsp;</div>
		</div>
	</div>
	@php
		$totalPrice = 0;
	@endphp
	@if(!empty($carts))
		@foreach($carts as $cart)
			@php
	            if(isset($cart->image) && !empty($cart->image)){
	                $image = File::exists($cart->image) ? $cart->image : asset('images/no-image-available-icon-6.jpg');
	            }else{
	                $image = asset('images/no-image-available-icon-6.jpg');
	            }
	        @endphp
			<div class="cart-table-prd">
				<div class="cart-table-prd-image">
					<input type="hidden" name="selected_cart[]" class="form-control cid required-feild" value="{{ md5($cart->cid) }}">
					<a href="{{ route('details', md5($cart->id)) }}" class="prd-img"><img class="lazyload fade-up" src="{{ asset($image) }}" data-src="{{ asset($image) }}" alt=""></a>
				</div>
				<div class="cart-table-prd-content-wrap">
					<div class="cart-table-prd-info">
						<div class="cart-table-prd-price">
							<div class="price-new">
								@if(Auth::guard('merchant')->check() || Auth::guard('admin')->check())
									@if($cart->variation_enable == '1')
										@if($cart->second_variation_enable == '1')
											@if(!empty($cart->second_variation_agent_special_price))
												RM {{ number_format($cart->second_variation_agent_special_price, 2) }}	
											@else
												RM {{ number_format($cart->second_variation_agent_price, 2) }}	
											@endif
										@else
											@if(!empty($cart->variation_agent_special_price))
												RM {{ number_format($cart->variation_agent_special_price, 2) }}	
											@else
												RM {{ number_format($cart->variation_agent_price, 2) }}	
											@endif
										@endif
									@else
										@if(!empty($cart->agent_special_price))
											RM {{ number_format($cart->agent_special_price, 2) }}	
										@else
											RM {{ number_format($cart->agent_price, 2) }}	
										@endif
									@endif
								@else
									@if($cart->variation_enable == '1')
										@if($cart->second_variation_enable == '1')
											@if(!empty($cart->second_variation_special_price))
												RM {{ number_format($cart->second_variation_special_price, 2) }}	
											@else
												RM {{ number_format($cart->second_variation_price, 2) }}	
											@endif
										@else
											@if(!empty($cart->variation_special_price))
												RM {{ number_format($cart->variation_special_price, 2) }}	
											@else
												RM {{ number_format($cart->variation_price, 2) }}	
											@endif
										@endif
									@else
										@if(!empty($cart->special_price))
											RM {{ number_format($cart->special_price, 2) }}	
										@else
											RM {{ number_format($cart->price, 2) }}	
										@endif
									@endif
								@endif
							</div>
						</div>
						<h2 class="cart-table-prd-name"><a href="{{ route('details', md5($cart->id)) }}">
							@if(isset($_COOKIE['global_language']) && !empty($_COOKIE['global_language']))
			                    @if($_COOKIE['global_language'] == '1')
			                      {{ $cart->product_name }}
			                    @else
			                      {{ $cart->product_name_en }}
			                    @endif
			                  @else
			                    {{ $cart->product_name }}
			                  @endif
	              </a></h2>
					</div>
					<div class="cart-table-prd-qty">
						<div class="qty qty-changer">
							<button class="decrease deduct-qty-button"></button>
							<input type="text" class="qty-input" name="quantity" value="{{ $cart->qty }}" onkeypress="return isNumberKey(event)">
							<button class="increase add-qty-button"></button>
						</div>
					</div>
					<div class="cart-table-prd-price-total">
						@if(Auth::guard('merchant')->check() || Auth::guard('admin')->check())
							@if($cart->variation_enable == '1')
								@if($cart->second_variation_enable == '1')
									@if(!empty($cart->second_variation_agent_special_price))
										RM {{ number_format($cart->second_variation_agent_special_price * $cart->qty, 2) }}	
									@else
										RM {{ number_format($cart->second_variation_agent_price * $cart->qty, 2) }}	
									@endif
								@else
									@if(!empty($cart->variation_agent_special_price))
										RM {{ number_format($cart->variation_agent_special_price * $cart->qty, 2) }}	
									@else
										RM {{ number_format($cart->variation_agent_price * $cart->qty, 2) }}	
									@endif
								@endif
							@else
								@if(!empty($cart->agent_special_price))
									RM {{ number_format($cart->agent_special_price * $cart->qty, 2) }}	
								@else
									RM {{ number_format($cart->agent_price * $cart->qty, 2) }}	
								@endif
							@endif
						@else
							@if($cart->variation_enable == '1')
								@if($cart->second_variation_enable == '1')
									@if(!empty($cart->second_variation_special_price))
										RM {{ number_format($cart->second_variation_special_price * $cart->qty, 2) }}	
									@else
										RM {{ number_format($cart->second_variation_price * $cart->qty, 2) }}	
									@endif
								@else
									@if(!empty($cart->variation_special_price))
										RM {{ number_format($cart->variation_special_price * $cart->qty, 2) }}	
									@else
										RM {{ number_format($cart->variation_price * $cart->qty, 2) }}	
									@endif
								@endif
							@else
								@if(!empty($cart->special_price))
									RM {{ number_format($cart->special_price * $cart->qty, 2) }}	
								@else
									RM {{ number_format($cart->price * $cart->qty, 2) }}	
								@endif
							@endif
						@endif
					</div>
				</div>
				<div class="cart-table-prd-action">
					<a href="#" class="important-text non-load delete-cart-btn" data-id="{{ md5($cart->cid) }}">
						<i class="icon-recycle"></i>
					</a>
				</div>
			</div>
			@php
				if(Auth::guard('merchant')->check() || Auth::guard('admin')->check()){
					if($cart->variation_enable == '1'){
						if($cart->second_variation_enable == '1'){
							if(!empty($cart->second_variation_agent_special_price)){
								$totalPrice += $cart->second_variation_agent_special_price * $cart->qty;
							}else{
								$totalPrice += $cart->second_variation_agent_price * $cart->qty;
							}
						}else{
							if(!empty($cart->variation_agent_special_price)){
								$totalPrice += $cart->variation_agent_special_price * $cart->qty;
							}else{
								$totalPrice += $cart->variation_agent_price * $cart->qty;
							}
						}
					}else{
						if(!empty($cart->agent_special_price)){
							$totalPrice += $cart->agent_special_price * $cart->qty;
						}else{
							$totalPrice += $cart->agent_price * $cart->qty;
						}
					}
				}else{
					if($cart->variation_enable == '1'){
						if($cart->second_variation_enable == '1'){
							if(!empty($cart->second_variation_special_price)){
								$totalPrice += $cart->second_variation_special_price * $cart->qty;
							}else{
								$totalPrice += $cart->second_variation_price * $cart->qty;
							}
						}else{
							if(!empty($cart->variation_special_price)){
								$totalPrice += $cart->variation_special_price * $cart->qty;
							}else{
								$totalPrice += $cart->variation_price * $cart->qty;
							}
						}
					}else{
						if(!empty($cart->special_price)){
							$totalPrice += $cart->special_price * $cart->qty;
						}else{
							$totalPrice += $cart->price * $cart->qty;
						}
					}
				}
			@endphp
		@endforeach
	@endif
</div>
		<!-- <div class="d-none d-lg-block">
			<div class="mt-4"></div>
				<div class="holder">
					<div class="container">
						<div class="title-wrap text-center">
							<h2 class="h1-style">{{ isset($data['lang']['lang']['you_may_also_like']) ? $data['lang']['lang']['you_may_also_like'] :'推荐产品'}}</h2>
							<div class="carousel-arrows carousel-arrows--center"></div>
						</div>
							<div class="prd-grid prd-carousel js-prd-carousel slick-arrows-aside-simple slick-arrows-mobile-lg data-to-show-4 data-to-show-md-3 data-to-show-sm-3 data-to-show-xs-2"
								 data-slick='{"slidesToShow": 4, "slidesToScroll": 2, "responsive": [{"breakpoint": 992,"settings": {"slidesToShow": 3, "slidesToScroll": 1}},{"breakpoint": 768,"settings": {"slidesToShow": 2, "slidesToScroll": 1}},{"breakpoint": 480,"settings": {"slidesToShow": 2, "slidesToScroll": 1}}]}'>
								 @foreach($products_latest as $product)
								<div class="prd prd--style2 prd-labels--max prd-labels-shadow ">
									<div class="prd-inside">
										<div class="prd-img-area">
											<a href="{{ route('details', md5($product->id)) }}" class="prd-img image-hover-scale image-container">
												<img src="{{ $product->image }}" data-src="{{ $product->image }}" alt="Image" class="js-prd-img lazyload fade-up">
												<div class="foxic-loader"></div>
												<div class="prd-big-squared-labels">
													
													
													
												</div>
											</a>
											<div class="prd-circle-labels">
												
											</div>
											
										</div>
										<div class="prd-info">
											<div class="prd-info-wrap">
												<div class="prd-info-top">
													
												</div>
												<div class="prd-rating justify-content-center"></div>
												<div class="prd-tag"><a href="{{ route('listing', 'brand='.urlencode($product->brand_name)) }}">{{ $product->brand_name }}</a></div>
												<h2 class="prd-title"><a href="{{ route('details', md5($product->id)) }}">
													@if(isset($_COOKIE['global_language']) && !empty($_COOKIE['global_language']))
									                    @if($_COOKIE['global_language'] == '1')
									                      {{ $product->product_name }}
									                    @else
									                      {{ $product->product_name_en }}
									                    @endif
									                  @else
									                    {{ $product->product_name }}
									                  @endif
												</a></h2>
											</div>
											<div class="prd-hovers">
												<div class="prd-circle-labels">
													<div><a href="#" class="circle-label-compare circle-label-wishlist--add js-add-wishlist mt-0" title="Add To Wishlist"><i class="icon-heart-stroke"></i></a><a href="#" class="circle-label-compare circle-label-wishlist--off js-remove-wishlist mt-0" title="Remove From Wishlist"><i class="icon-heart-hover"></i></a></div>
												</div>
												<div class="prd-price">
													
													@if(Auth::guard('merchant')->check() || Auth::guard('admin')->check())
											            <div class="price-new">RM {{ $product->agent_actual_price }}</div>
											          @else
											            <div class="price-new">RM {{ $product->retail_price }}</div>
											          @endif
												</div>
											</div>
										</div>
									</div>
								</div>
								@endforeach
							</div>
						
					</div>
					</div>
				</div> -->
			</div>
			<div class="col-lg-4 col-xl-3 mt-3 mt-md-0">
				<div class="card-total">
					<div class="text-right">
						<button class="btn btn--grey" onclick="window.location.reload();"><span>{{ isset($data['lang']['lang']['update_cart']) ? $data['lang']['lang']['update_cart'] :'更新购物车'}}</span><i class="icon-refresh"></i></button>
					</div>
					<div class="row d-flex">
						<div class="col card-total-txt">{{ isset($data['lang']['lang']['sub_total']) ? $data['lang']['lang']['sub_total'] :'合计'}}</div>
						<div class="col-auto card-total-price text-right">RM {{ number_format($totalPrice, 2) }}</div>
					</div>
					<a href="{{ route('checkout') }}" style="width: 100%;"><button class="btn btn--full btn--lg"><span>{{ isset($data['lang']['lang']['checkout']) ? $data['lang']['lang']['checkout'] :'结算'}}</span></button></a>
					<div class="card-text-info text-right">
						<h5>{{ isset($data['lang']['lang']['standard_shipping']) ? $data['lang']['lang']['standard_shipping'] :'标准运输'}}</h5>
						<p><b>2 - 3 {{ isset($data['lang']['lang']['business_days']) ? $data['lang']['lang']['business_days'] :'工作日'}}</b></p>
					</div>
				</div>
				<div class="mt-2"></div>
				<div class="panel-group panel-group--style1 prd-block_accordion" id="productAccordion">
					
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12" style="width: 100%;">
				<div class="d-none d-lg-block">
				<div class="mt-4"></div>
					<div class="holder">
						<div class="container">
							<div class="title-wrap text-center">
								<h2 class="h1-style">{{ isset($data['lang']['lang']['you_may_also_like']) ? $data['lang']['lang']['you_may_also_like'] :'推荐产品'}}</h2>
								<div class="carousel-arrows carousel-arrows--center"></div>
							</div>
							<div class="prd-grid prd-carousel js-prd-carousel slick-arrows-aside-simple slick-arrows-mobile-lg data-to-show-4 data-to-show-md-3 data-to-show-sm-3 data-to-show-xs-2"
								 data-slick='{"slidesToShow": 4, "slidesToScroll": 2, "responsive": [{"breakpoint": 992,"settings": {"slidesToShow": 3, "slidesToScroll": 1}},{"breakpoint": 768,"settings": {"slidesToShow": 2, "slidesToScroll": 1}},{"breakpoint": 480,"settings": {"slidesToShow": 2, "slidesToScroll": 1}}]}'>
								 @foreach($products_latest as $product)
								<div class="prd prd--style2 prd-labels--max prd-labels-shadow ">
									<div class="prd-inside">
										<div class="prd-img-area">
											<a href="{{ route('details', md5($product->id)) }}" class="prd-img image-hover-scale image-container">
												<img src="{{ $product->image }}" data-src="{{ $product->image }}" alt="Image" class="js-prd-img lazyload fade-up">
												<div class="foxic-loader"></div>
												<div class="prd-big-squared-labels">
													
													
													
												</div>
											</a>
											<div class="prd-circle-labels">
												
											</div>
											
										</div>
										<div class="prd-info">
											<div class="prd-info-wrap">
												<div class="prd-info-top">
													
												</div>
												<div class="prd-rating justify-content-center"></div>
												<div class="prd-tag"><a href="{{ route('listing', 'brand='.urlencode($product->brand_name)) }}">{{ $product->brand_name }}</a></div>
												<h2 class="prd-title"><a href="{{ route('details', md5($product->id)) }}">
													@if(isset($_COOKIE['global_language']) && !empty($_COOKIE['global_language']))
									                    @if($_COOKIE['global_language'] == '1')
									                      {{ $product->product_name }}
									                    @else
									                      {{ $product->product_name_en }}
									                    @endif
									                  @else
									                    {{ $product->product_name }}
									                  @endif
												</a></h2>
											</div>
											<div class="prd-hovers">
												<div class="prd-circle-labels">
													<div><a href="#" class="circle-label-compare circle-label-wishlist--add js-add-wishlist mt-0" title="Add To Wishlist"><i class="icon-heart-stroke"></i></a><a href="#" class="circle-label-compare circle-label-wishlist--off js-remove-wishlist mt-0" title="Remove From Wishlist"><i class="icon-heart-hover"></i></a></div>
												</div>
												<div class="prd-price">
          
										          @php
										            $discount_percentage = 0;
										            $second_percentage = 0;
										            if(Auth::guard('merchant')->check() || Auth::guard('admin')->check()){
										                if($product->variation_enable == '1'){
										                    if($product->second_variation_enable == '1'){
										                        if($priceV2[$product->id][3] == $priceV2[$product->id][2]){
										                            if($priceV2[$product->id][4]){
										                                $discount_percentage = (($priceV2[$product->id][5] - $priceV2[$product->id][4])*100) / $priceV2[$product->id][5];
										                            }
										                        }else{
										                            if($priceV2[$product->id][4]){
										                                $discount_percentage = (($priceV2[$product->id][7] - $priceV2[$product->id][6])*100) / $priceV2[$product->id][7];
										                            }
										                        }
										                    }else{
										                        if($priceV[$product->id][3] == $priceV[$product->id][2]){
										                            if($priceV[$product->id][4]){
										                                $discount_percentage = (($priceV[$product->id][5] - $priceV[$product->id][4])*100) / $priceV[$product->id][5];
										                            }
										                        }else{
										                            if($priceV[$product->id][4]){
										                                $discount_percentage = (($priceV[$product->id][7] - $priceV[$product->id][6])*100) / $priceV[$product->id][7];
										                            }
										                        }

										                    }
										                }else{
										                    if(!empty($product->agent_special_price)){
										                        $discount_percentage =  (($product->agent_price - $product->agent_special_price)*100) / $product->agent_price;
										                    }
										                }
										            }else{
										                if($product->variation_enable == '1'){
										                    if($product->second_variation_enable == '1'){
										                        if($priceV2[$product->id][1] == $priceV2[$product->id][0]){
										                            if($priceV2[$product->id][8]){
										                                $discount_percentage = (($priceV2[$product->id][9] - $priceV2[$product->id][8])*100) / $priceV2[$product->id][9];
										                            }
										                        }else{
										                            if($priceV2[$product->id][8]){
										                                $discount_percentage = (($priceV2[$product->id][11] - $priceV2[$product->id][10])*100) / $priceV2[$product->id][10];
										                            }
										                        }
										                    }else{
										                        if($priceV[$product->id][1] == $priceV[$product->id][0]){
										                            if($priceV[$product->id][8]){
										                                $discount_percentage = (($priceV[$product->id][9] - $priceV[$product->id][8])*100) / $priceV[$product->id][9];
										                            }
										                        }else{
										                            if($priceV[$product->id][8]){
										                                $discount_percentage = (($priceV[$product->id][11] - $priceV[$product->id][10])*100) / $priceV[$product->id][10];
										                            }
										                        }

										                    }
										                }else{
										                    if(!empty($product->special_price)){
										                        $discount_percentage = (($product->price - $product->special_price)*100) / $product->price;
										                    }
										                }
										            }

										        @endphp
										          

										          @if(Auth::guard('merchant')->check() || Auth::guard('admin')->check())
										            @if($product->variation_enable == '1')
										                @if($product->second_variation_enable == '1')
										                    @if($priceV2[$product->id][3] == $priceV2[$product->id][2])
										                        <div class="price-new">
										                            <span>RM {{ number_format($priceV2[$product->id][3], 2) }}</span>
										                        </div>
										                    @else
										                        <div class="price-new">
										                            <span>RM {{ number_format($priceV2[$product->id][3], 2) }} - {{ number_format($priceV2[$product->id][2], 2) }}</span>
										                        </div>

										                    @endif
										                @else
										                    @if($priceV[$product->id][3] == $priceV[$product->id][2])
										                        <div class="price-new">
										                            <span>RM {{ number_format($priceV[$product->id][3], 2) }}</span>
										                        </div>
										                    @else
										                        <div class="price-new">
										                            <span>RM {{ number_format($priceV[$product->id][3], 2) }} - {{ number_format($priceV[$product->id][2], 2) }}</span>
										                        </div>

										                    @endif
										                @endif
										            @else
										                @if(!empty($product->agent_special_price))
										                    @if($product->agent_special_price != $product->agent_price)
										                      <div class="price-old">RM{{ number_format($product->agent_price, 2) }}</div>

										                      <div class="price-new">
										                        <span>RM {{ number_format($product->agent_special_price, 2) }}</span>
										                      </div>
										                       
										                    @else
										                      <div class="price-new">
										                        <span>RM {{ number_format($product->agent_special_price, 2) }}</span>
										                      </div>
										                    @endif
										                @else
										                    <div class="price-new">
										                        <span>RM {{ number_format($product->agent_price, 2) }}</span>
										                    </div>
										                @endif
										            @endif
										        @else
										            @if($product->variation_enable == '1')
										                @if($product->second_variation_enable == '1')
										                    @if($priceV2[$product->id][1] == $priceV2[$product->id][0])
										                        <div class="price-new">
										                            <span>RM {{ number_format($priceV2[$product->id][1], 2) }}</span>
										                        </div>
										                    @else
										                        <div class="price-new">
										                            <span>RM {{ number_format($priceV2[$product->id][1], 2) }} - {{ number_format($priceV2[$product->id][0], 2) }}</span>
										                        </div>

										                    @endif
										                @else
										                    @if($priceV[$product->id][1] == $priceV[$product->id][0])
										                        <div class="price-new">
										                            <span>RM {{ number_format($priceV[$product->id][1], 2) }}</span>
										                        </div>
										                    @else
										                        <div class="price-new">
										                            <span>RM {{ number_format($priceV[$product->id][1], 2) }} - {{ number_format($priceV[$product->id][0], 2) }}</span>
										                        </div>

										                    @endif
										                @endif
										            @else
										                @if(!empty($product->special_price))
										                  @if($product->special_price != $product->price)
										                      <div class="price-old">RM{{ number_format($product->price, 2) }}</div>

										                      <div class="price-new">RM {{ number_format($product->special_price, 2) }}</div>
										                  @else
										                      <span>RM {{ number_format($product->special_price, 2) }}</span>
										                  @endif
										                @else
										                    <div class="price-new">
										                        RM {{ number_format($product->price, 2) }}
										                    </div>
										                @endif
										            @endif
										        @endif
										          
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
			</div>
		</div>
	</div>
</div>
</div>
@endsection

@section('js')
<script type="text/javascript">
	$('.select-all-checkbox').click( function(){

		$('.loading-gif').show();
		$('.list-check').prop('checked', this.checked);
		$('.select-all-checkbox').prop('checked', this.checked);
		calc();
	});

	$('.web-cart .list-check, .mobile-cart .list-check').click( function(){
		$('.loading-gif').show();
		var cart_id = $(this).data('id');
		$('.list-check').each(function () {
			if($(this).data('id') == cart_id){
				$(this).click();
			}
        });
		calc();
	});

	

	$('.add-qty-button').click( function(e){

		e.preventDefault();
		$('.loading-gif').show();
		var ele = $(this);
		var quantity = ele.parent().find('input[name="quantity"]').val();
		var balance = ele.closest('ul').find('input[name="balance_quantity"]').val();
		quantity = Number(quantity) + 1;
		if(quantity > balance){
			alert('The maximum quantity available for this item is '+balance);
			$('.loading-gif').hide();
			return false;
		}else{
			ele.parent().find('input[name="quantity"]').val(quantity);			
		}
		// var cart_id = ele.closest('ul').find('.list-check').data('id');
		var cart_id = ele.closest('.cart-table-prd').find('.cid').val();

		updateQty(quantity, cart_id, ele);
		
	});

	$('.deduct-qty-button').click( function(e){
		e.preventDefault();
		$('.loading-gif').show();
		var ele = $(this);
		var quantity = ele.parent().find('input[name="quantity"]').val();
		if(quantity != 1){
			quantity = Number(quantity) - 1;
			ele.parent().find('input[name="quantity"]').val(quantity);
		}
		// var cart_id = ele.closest('ul').find('.list-check').data('id');
		var cart_id = ele.closest('.cart-table-prd').find('.cid').val();

		updateQty(quantity, cart_id, ele);
		
	});

	// $('input[name="quantity"]').change( function(){
	// 	var ele = $(this);
	// 	var quantity = $(this).val();
	// 	var cart_id = $(this).closest('ul').find('.list-check').data('id');
	// 	var balance = ele.closest('ul').find('input[name="balance_quantity"]').val();

	// 	if(parseInt(quantity) > parseInt(balance)){
	// 		ele.val(balance);
	// 		alert('The maximum quantity available for this item is '+balance);
	// 		return false;
	// 	}else{
	// 		updateQty(quantity, cart_id, ele);			
	// 	}
		
	// });

	$('input[name="quantity"]').change( function(){

		var ele = $(this);
		var id = ele.closest('.cart-table-prd').find('.cid').val();
		var qty = ele.val();

		updateQty(qty, id, ele);
	});

	// $('.delete-cart-btn').click( function(e){
	// 	e.preventDefault();
	// 	var ele = $(this);
	// 	var cart_id = $(this).closest('ul').find('.list-check').data('id');
	// 	var cart_id = $(this).data('id');

	// 	deleteCart(cart_id, ele);
		
	// });

	$('.delete-cart-btn').click( function(e){
		e.preventDefault();
		var ele = $(this);
		var cart_id = $(this).data('id');
		var fd = new FormData();
		fd.append('cart_id', cart_id);
		
		if(confirm("物品将从购物车中移除") == true){
			$('.loading-gif').show();
			$.ajax({
		       url: '{{ route("deleteCart") }}',
		       type: 'post',
		       data: fd,
		       contentType: false,
		       processData: false,
		       success: function(response){
		       		$('.loading-gif').hide();
		       		$.ajax({
				        url: '{{ route("CountCart") }}',
				        type: 'get',
				        success: function(response){
				        	
				        	$('.cart_count span').html(response[0]);
				        	$('.cart_price').html('RM '+parseFloat(response[1]).toFixed(2));
				        }
				    });
			        ele.closest('.cart-detail').remove();
			        var check = $('.container-box .cart-detail').length;
		       		
			        if(check == 0){
			        	window.location.href = "{{ route('listing') }}";
			        }else{
			        	location.reload();
			        }
		       },
		    });			
		}
		
	});

	$('.checkout-button').click( function (e){
		e.preventDefault();
		var check = $('.list-check:checked').length;
		
		if(check == 0){
			alert('Please Select At Least 1 item(s) To Checkout.');
			return false;
		}else{
			$('#form-cart').submit();
		}
	});

	function updateQty(qty, cart_id, ele){

		var m = '{{ request("m") }}';
		var fd = new FormData();
		fd.append('cart_id', cart_id);
		fd.append('quantity', qty);

		$.ajax({
	       url: '{{ route("updateQuantity") }}',
	       type: 'post',
	       data: fd,
	       contentType: false,
	       processData: false,
	       success: function(response){
	       		if(m && m == 1){
	       			ele.closest('ul').find('.product-total-price').html(response+' point');
	       		}else{
	       			ele.closest('ul').find('.product-total-price').html('RM '+response);
	       		}

	       		calc();
	       },
	    });
	}

	function deleteCart(cart_id, ele){

		var fd = new FormData();
		fd.append('cart_id', cart_id);

		

		if(confirm("Item(s) will be removed from Cart") == true){
			$('.loading-gif').show();
			$.ajax({
		       url: '{{ route("deleteCart") }}',
		       type: 'post',
		       data: fd,
		       contentType: false,
		       processData: false,
		       success: function(response){
		       		var cart_id = ele.closest('ul').find('.list-check').data('id');
		       		$.ajax({
				        url: '{{ route("CountCart") }}',
				        type: 'get',
				        success: function(response){
				        	
				        	$('.cart_count span').html(response[0]);
				        	$('.cart_price').html('RM '+parseFloat(response[1]).toFixed(2));
				        }
				    });
					$('.list-check').each(function () {

			        	if($(this).data('id') == cart_id){
							$(this).closest('ul').remove();
						}

			        });
			        var check = $('ul .list-check').length;
			        if(check == 0){
			        	$('.cart-details-list').html('<div class="form-group" align="center">There are no items in this cart <br><br><a href="{{ route("home") }}" class="continue-shopping-btn btn"> Continue Shopping</a></div>');
			        	$('.select-all-checkbox').prop('checked', false);
			        }
		       		calc();
		       },
		    });			
		}else{
			return false;
		}
	}

	function calc(){
		var m = '{{ request("m") }}';
		var arrayA = [];
		var checkedBox = $('.cart-details-list').find('.list-check:checked').data('id');
		$('.list-check:checked').each(function () {
            var sThisVal = (this.checked ? $(this).data('id') : "");
            arrayA.push(sThisVal);
        });
        
	    var fd = new FormData();
	    fd.append('cart_id', arrayA); 

	    $.ajax({
	       url: '{{ route("SelectCart") }}',
	       type: 'post',
	       data: fd,
	       contentType: false,
	       processData: false,
	       success: function(response){

	       		$.ajax({
			        url: '{{ route("CountCart") }}',
			        type: 'get',
			        success: function(response){
			        	$('.cart_count span').html(response[0]);
				        $('.cart_price').html('RM '+parseFloat(response[1]).toFixed(2));
			        }
			    });
	       		
	       		if(m && m == 1){
		         	$('.total-amount').html(response[0]+' point');
	       		}else{
	       			$('.total-amount').html('RM '+response[0]);
	       		}
	         	$('#item-count').html(response[1]);
	         	$('#total-weight').html(parseFloat(response[2]).toFixed(2));

	         	$('.loading-gif').hide();
	       },
	    });

	    if($('.list-check:checked').length == 0){
	    	$('.select-all-checkbox').prop('checked', false);
	    }

	    var countCheckbox = $('.list-check').length;

	    if($('.list-check:checked').length == countCheckbox && countCheckbox != 0){
	    	$('.select-all-checkbox').prop('checked', true);
	    }else{
	    	$('.select-all-checkbox').prop('checked', false);
	    }
	}
</script>
@endsection