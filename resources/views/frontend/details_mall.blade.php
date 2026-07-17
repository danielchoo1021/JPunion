@extends('layouts.app')
@php
	if(!empty(Auth::guard($data['userGuardRole'])->check())){
		$memberCode = Auth::guard($data['userGuardRole'])->user()->code;
	}else{
		$memberCode = "";
	}

	if(!empty($product->first_image->image)){
		$Fimage = !empty($product->first_image->image) ? asset($product->first_image->image) : asset('images/no-image-available-icon-6.jpg');
	}else{
		$Fimage = asset('images/no-image-available-icon-6.jpg');
	}
	

	$exp_one = explode(".", $Fimage);
	$file_ext_one = end($exp_one);
@endphp
<meta property="og:url"           content="{{ $_SERVER['REQUEST_URI'] }}?afapi={{ $memberCode }}" />
<meta property="og:type"          content="{{ $product->product_name }}" />
<meta property="og:title"         content="{{ $product->product_name }}" />
<meta property="og:description"   content="{{ !empty($product->short_description) ? $product->short_description : $product->product_name }}" />
<meta property="og:image"         content="{{ $Fimage }}" />
@section('content')

<div class="page-header" style="background-image: url({{ asset($data['setting_header']->shop_image) }});">

</div>
<div class="shop mt-5" style="padding-top: 0px;">
	<div class="container details-page">
		<div class="row">
			<div class="col-md-12">
				<div class="">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								@php
									$exp_one = explode(".", $Fimage);
        							$file_ext_one = end($exp_one);
								@endphp
								<div class="" href="{{ asset($Fimage) }}" style="width: 100%; height: auto !important;">
									<div id="show-img">
									    @if($file_ext_one == 'mp4')
											<video style="width: 100%;" autoplay="autoplay" loop="1" controls controlsList="nodownload">
					                            <source src="{{ asset($Fimage) }}" type="video/mp4">
					                        </video>
										@else
									    	<!-- <img src="{{ asset($Fimage) }}" alt="{{ $product->product_name }}" width="100%"> -->
									    	<a href="#" class="img-header" data-toggle="modal" data-target="#myModal">
									    		<div class="details-img" id="details-img" style="background-image: url({{ asset($Fimage) }});">

									    		</div>
									    	</a>
											<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index: 9999;">
												<div class="modal-dialog">
													<div class="modal-content">
														<div class="modal-body">
															<img src="{{ $Fimage }}" width="100%" class="modal-img">
														</div>
													</div>
												</div>
											</div>
										@endif										
									</div>
								</div>
								<div class="small-img">
							    	<img src="{{ asset('frontend/thumbnail-zoom/images/online_icon_right@2x.png') }}" class="icon-left" alt="Icon" id="prev-img">
							    	<div class="small-container">
							      		<div id="small-img-roll" class="small-img-roll">
											@if(!$product->get_images->isEmpty())
												@foreach($product->get_images as $key => $image)
													@php
														$image = !empty($image->image) ? asset($image->image) : asset('images/no-image-available-icon-6.jpg');
														$exp_two = explode(".", $image);
														$file_ext_two = end($exp_two);
													@endphp
													@if($file_ext_two == 'mp4')
														<video style="width: 70px;" loop="1" class="show-small-img" src="{{ asset($image) }}">
															<source src="{{ asset($image) }}" type="video/mp4">
														</video>
													@else
														<img src="{{ $image }}" class="show-small-img" alt="Image" width="100%;">
													@endif
												@endforeach
											@else
												<img src="{{ asset('images/no-image-available-icon-6.jpg') }}" class="show-small-img" alt="Image" width="100%;">
											@endif
							      		</div>
							    	</div>
							    	<img src="{{ asset('frontend/thumbnail-zoom/images/online_icon_right@2x.png') }}" class="icon-right" alt="Icon" id="next-img" width="100%;">
							  	</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<div class="row">
									<div class="col-9">
										<div class="product-details" style="width: 100%;">
											<span style="font-size: 24px;">
												{{ $product->product_name }}
											</span>
											@if(!empty($product->product_name_cn))
											<br>
											<span style="font-size: 24px;">
												{{ $product->product_name_cn }}
											</span>
											@endif

											@if(!empty($product->product_short_name))
											<br>
											<span style="font-size: 14px;">
												{{ $product->product_short_name }}
											</span>
											@endif
										</div>
										<span style="font-size: 14px;">
											@if(!empty($product->short_description))
												{{ $product->short_description }}
				                            @endif											
										</span>
									</div>
									<div class="col-3" align="right">
										@if(Auth::guard('agent')->check() || Auth::guard('admin')->check() || Auth::guard('web')->check())
											@if(!empty($favourite->id))
												<a href="#" class="add-favourite-btn" style="color: red;">
													<i class="fa fa-heart fa-2x" aria-hidden="true"></i>
												</a>
											@else
												<a href="#" class="add-favourite-btn" style="color: red;">
													<i class="far fa-heart fa-2x"></i>
												</a>
											@endif
										@endif
									</div>
								</div>
								
							</div>

							@if($product->packages == 1)
							<span class="badge badge-primary">
								Packages
							</span>
                            @endif

							<div class="form-group">
								<h4 class="main-price">
				                	{!! $get_product_pricing['product_price_range'] !!} {{ isset($data['lang']['lang']['point']) ? $data['lang']['lang']['point'] :'点数' }}
	                                @if(!empty($get_product_pricing['product_special_range']))
	                                <small>
	                                    <del>
	                                    	{!! $get_product_pricing['product_special_range'] !!} {{ isset($data['lang']['lang']['point']) ? $data['lang']['lang']['point'] :'点数' }}
	                                    </del>
	                                </small>
	                                @endif
				                </h4>
								<hr>
							</div>

							@if($product->variation_enable == '1')
								@if(!$product->get_variations->isEmpty())
								<div class="form-group">
									<span style="font-size: 13px;">
										{{ (!empty($product->variation_title)) ? $product->variation_title : 'Option' }}: 
									</span>
									<br>
									<div class="form-group">
										@foreach($product->get_variations as $variationsKey => $variation)
											<div class="variation_option 
														{{ ($vStock[$variation->id] <= 0 && $product->second_variation_enable == '0') ? 'out-of-stock' : '' }}" 
												 data-id="{{ $variation->id }}">
												{{ $variation->variation_name }}
											</div> 
										@endforeach
									</div>
								</div>
								@endif
								@if($product->second_variation_enable == '1')
									<span style="font-size: 13px;">
										{{ (!empty($product->second_variation_title)) ? $product->second_variation_title : 'Option' }}: 
									</span>
									<br>
									<div class="form-group second-variation-list">
										@foreach($product->get_second_variations as $svariationsKey => $second_variation)
											<div class="second_variation_option 
														{{ ($svStock[$second_variation->id] <= 0) ? 'out-of-stock' : '' }}" 
												 data-id="{{ $second_variation->id }}">
												{{ $second_variation->variation_name }}
											</div> 
										@endforeach
									</div>
								@endif
							@endif

							<div class="divider"></div>
							<div class="row">
								<div class="col-6">
		                        	<div class="product-detail__content__footer">
		                        		<ul>
		                        			@if(!empty($product->one_product_category->category_name))
				                            <li>
				                            	{{ isset($data['lang']['lang']['categories']) ? $data['lang']['lang']['categories'] :'分类' }}: {{ $product->one_product_category->category_name }}
				                            </li>
				                            @endif
				                            @if(!empty($product->one_product_subcategory->sub_category_name))
				                            <li>
				                            	{{ isset($data['lang']['lang']['sub_category']) ? $data['lang']['lang']['sub_category'] :'子类别' }}: {{ $product->one_product_subcategory->sub_category_name }}
				                            </li>
				                            @endif
				                            
				                        </ul>
		                        	</div>
								</div>
								<div class="col-6">
		                        	<div class="product-detail__content__footer">
		                        		<ul>
		                        			@if(!empty($product->one_product_brand->brand_name))
				                            <li>
				                            	{{ isset($data['lang']['lang']['brand']) ? $data['lang']['lang']['brand'] :'品牌' }}: {{ $product->one_product_brand->brand_name }}
				                            </li>
				                            @endif
				                            @if(!empty($product->one_product_oum->uom_name))
				                            <li>
				                            	{{ isset($data['lang']['lang']['uom']) ? $data['lang']['lang']['uom'] :'UOM' }}: {{ $product->one_product_oum->uom_name }}
				                            </li>
				                            @endif
											<!-- @if($product->packages == 1)
											@else
												@if($stockBalance <= 0)
												<li class="quantity-balance important-text">
													@if($product->variation_enable != 1)
													Out of stock
													@endif
												</li>
												@else
													<li class="quantity-balance">
														@if($product->variation_enable != 1)
															Stock Balance: {{ $stockBalance }} Items
														@endif
													</li>
												@endif
											@endif -->
				                        </ul>
		                        	</div>
								</div>
							</div>

							<div class="form-group">
								<!-- <span style="font-size: 13px;">Quantity: </span> -->
								<!-- <input type="text" class="input-sm" name="quantity" id="spinner3" onkeypress="return isNumberKey(event)" /> &nbsp;&nbsp; -->
								<div class="form-group quantity-setting">
									<button class="btn btn-primary deduct-qty-button">
										<i class="fa fa-minus"></i>
									</button>
									<input type="text" class="form-control" name="quantity" value="1" onkeypress="return isNumberKey(event)">
									<button class="btn btn-primary add-qty-button">
										<i class="fa fa-plus"></i>
									</button>
								</div>
								
							</div>
							
							<div class="form-group">
								
								<a href="#" class="btn -red mb-p-13 add-to-cart-button set_button set_text">
									<i class="fa fa-shopping-cart"></i> {{ isset($data['lang']['lang']['add_to_cart']) ? $data['lang']['lang']['add_to_cart'] :'加入购物车' }}
								</a>
							</div>
							<!-- <div class="form-group">
								<div class="sharethis-inline-share-buttons"></div>
							</div> -->
							<div class="form-group">
								<div id="fb-root"></div>
							    <script>(function(d, s, id) {
							    var js, fjs = d.getElementsByTagName(s)[0];
							    if (d.getElementById(id)) return;
							    js = d.createElement(s); js.id = id;
							    js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.0";
							    fjs.parentNode.insertBefore(js, fjs);
							    }(document, 'script', 'facebook-jssdk'));</script>
							    @php
							    	if(!empty(request('afapi'))){
							    		$shareLinks = "https://".$_SERVER['HTTP_HOST'].urlencode($_SERVER['REQUEST_URI']);
							    	}else{
							    		$shareLinks = "https://".$_SERVER['HTTP_HOST'].urlencode($_SERVER['REQUEST_URI'])."?afapi=".$memberCode;
							    	}
							    	
							    @endphp
							    <div class="fb-share-button" 
								    data-href="{{ $_SERVER['REQUEST_URI'] }}?afapi={{ $memberCode }}" 
								    data-layout="button_count">
								</div>

								<a href="https://api.whatsapp.com/send?text={{ $shareLinks }}" data-action="share/whatsapp/share" onClick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;" target="_blank" title="Share on whatsapp" class="btn btn-success btn-sm h-20 btn-whatspp">
									<i class="fab fa-whatsapp"></i> Share
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		@if(!$Ppackages->isEmpty())
			<div class="form-group">
				<hr>
				<h5 class="gold-word">
					{{ isset($data['lang']['lang']['witb']) ? $data['lang']['lang']['witb'] :'配套里包括' }}:
				</h5>
				<br>
				<div class="form-group" style="overflow: auto;">
					<table class="table table-bordered table-free-gift mb-f-13">
						<tr class="set_button set_text">
							<td>{{ isset($data['lang']['lang']['product_description']) ? $data['lang']['lang']['product_description'] :'产品描述' }}</td>
							<td>{{ isset($data['lang']['lang']['unit_price']) ? $data['lang']['lang']['unit_price'] :'单价' }}</td>
							<td>{{ isset($data['lang']['lang']['quantity']) ? $data['lang']['lang']['quantity'] :'数量' }}</td>
							<td>{{ isset($data['lang']['lang']['sub_total']) ? $data['lang']['lang']['sub_total'] :'合计' }}</td>
						</tr>
						@php
							$totalPrice = 0;
						@endphp
						@foreach($Ppackages as $Ppackage)
							<tr>
								<td>
									<p>
										<img src="{{ asset(!empty($Ppackage->image) ? $Ppackage->image : 'images/no-image-available-icon-6.jpg') }}" style="width: 90px;">
										<br>
										{{ $Ppackage->product_name }}
									</p>
								</td>
								<td>
									<p>{{ number_format($Ppackage->unit_price, 2) }}</p>
								</td>
								<td>
									<p>{{ $Ppackage->qty }}</p>
								</td>
								<td>
									<p>{{ number_format($Ppackage->unit_price * $Ppackage->qty, 2) }}</p>
								</td>
							</tr>
							@php
								$totalPrice += $Ppackage->unit_price * $Ppackage->qty;
							@endphp
						@endforeach
						<tr>
							<td colspan="3" align="right">Grand total</td>
							<td>
								<p>{{ number_format($totalPrice, 2) }}</p>
							</td>
						</tr>
						@if(!empty($product->free_gift))
							<tr style="color: white;">
								<td colspan="4" style="color: #000;">
									<h5>Gift:</h5>
									<br>
									{!! $product->free_gift !!}
								</td>
							</tr>
						@endif
					</table>
				</div>
			</div>
		@endif

		@if(!$Vpackages->isEmpty())
			<div class="form-group">
				<hr>
				<h5 class="gold-word">
					Voucher in the box:
				</h5>
				<br>
				<div class="form-group" style="overflow: auto;">
					<table class="table table-bordered table-free-gift">
						<tr style="background-color: #4C8FBD; color: #fff;">
							<td>Voucher Description</td>
							<td>{{ isset($data['lang']['lang']['quantity']) ? $data['lang']['lang']['quantity'] :'数量' }}</td>
						</tr>
						@php
							$totalPrice = 0;
						@endphp
						@foreach($Vpackages as $Vpackage)
							<tr>
								<td>
									<p>
										<img src="{{ asset(!empty($Vpackage->image) ? $Vpackage->image : 'images/no-image-available-icon-6.jpg') }}" style="width: 90px;">
										<br>
										{{ $Vpackage->promotion_title }}
									</p>
								</td>
								<td>
									<p>{{ $Vpackage->qty }}</p>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									{!! $Vpackage->description !!}
								</td>
							</tr>
							@php
								$totalPrice += $Vpackage->unit_price * $Vpackage->qty;
							@endphp
						@endforeach
					</table>
				</div>
			</div>
		@endif
		<hr>

		<div class="form-group product-description">
			<div class="widget-box transparent" id="recent-box">
				<div class="widget-header">
					<div class="widget-toolbar no-border" style="float: left;">
						<ul class="nav nav-tabs" id="recent-tab">
							<li class="parent_payment_method active">
								<a data-toggle="tab" class="payment_method f-15" data-id="1" href="#online-tab">
									{{ isset($data['lang']['lang']['description']) ? $data['lang']['lang']['description'] :'描述' }}
								</a>
							</li>
							
							<li class="parent_payment_method">
								<a data-toggle="tab" class="payment_method f-15" data-id="2" href="#testimonial-tab">
									{{ isset($data['lang']['lang']['Testimonial']) ? $data['lang']['lang']['Testimonial'] :'见证' }}
								</a>
							</li>
						</ul>
					</div>
				</div>

				<div class="widget-body">
					<div class="widget-main padding-4">
						<div class="tab-content padding-8">
							<div id="online-tab" class="tab-pane active">
								@if(!empty($product->description))
									{!! htmlspecialchars_decode($product->description) !!}
								@else
									<div class="form-group" align="center">
										<i class="fa fa-search"></i> No Content
									</div>
								@endif
							</div>

							<div id="testimonial-tab" class="tab-pane">
								@if(!empty($product->testimonial))
									{!! htmlspecialchars_decode($product->testimonial) !!}
								@else
									<div class="form-group" align="center">
										<i class="fa fa-search"></i> No Content
									</div>
								@endif
							</div>
						</div>
					</div><!-- /.widget-main -->
				</div><!-- /.widget-body -->
			</div>
		</div>
	</div>
</div>

@endsection

@section('js')
<script type="text/javascript">  
  var variation_enable = '{{ $product->variation_enable }}';
  var second_variation_enable = '{{ $product->second_variation_enable }}';

  $('.add-to-cart-button').click( function(e){
  	// alert('123');
  	e.preventDefault();
	

  	$('.loading-gif').show();
  	var mall = '{{ $product->mall }}';
  	var isAdmin = '{{ Auth::guard("admin")->check() }}';
  	var isMerchant = '{{ Auth::guard("merchant")->check() }}';
  	var isAgent = '{{ Auth::guard("agent")->check() }}';
  	var isUser = '{{ Auth::guard("web")->check() }}';
  	var isCorporate = '{{ Auth::guard("corporate")->check() }}';
  	var isGuest = "{{ !empty($_COOKIE['new_guest']) ? $_COOKIE['new_guest'] : $data['new_guest'] }}";
  	var option = $('.variation_option.active').data('id');
  	var second_option = $('.second_variation_option.active').data('id');


  	if(variation_enable == 1 && !option){
  		alert('Please select variation first');
  		$('.loading-gif').hide();
  		return false;
  	}

  	if(second_variation_enable == 1 && !second_option){
  		alert('Please select variation first');
  		$('.loading-gif').hide();
  		return false;
  	}

  	if(isAdmin){
  		auth_check = '{{ !empty(Auth::guard("admin")->user()->code) ? Auth::guard("admin")->user()->code : '' }}';
  	}else if(isMerchant){
  		auth_check = '{{ !empty(Auth::guard("merchant")->user()->code) ? Auth::guard("merchant")->user()->code : '' }}';
  	}else if(isAgent){
  		auth_check = '{{ !empty(Auth::guard("agent")->user()->code) ? Auth::guard("agent")->user()->code : '' }}';
  	}else if(isUser){
  		auth_check = '{{ !empty(Auth::guard("web")->user()->code) ? Auth::guard("web")->user()->code : '' }}';
  	}else if(isCorporate){
  		auth_check = '{{ !empty(Auth::guard("corporate")->user()->code) ? Auth::guard("corporate")->user()->code : '' }}';
  	}else{
  		auth_check = "";
  	}
  	
  	if(auth_check){
	  	var fd = new FormData();
  			fd.append('product_id', '{{ $product->id }}');
  			fd.append('quantity', $('input[name="quantity"]').val());
  			fd.append('sub_category_id', option);
  			fd.append('second_sub_category_id', second_option);
        	fd.append('mall', '1');

	  	$.ajax({
	        url: '{{ route("AddToCart") }}',
	        type: 'post',
	        data: fd,
	        contentType: false,
	        processData: false,
	        success: function(response){

	        	$('.loading-gif').hide();

	        	if(response == 'ok'){
	        		$.ajax({
				        url: '{{ route("CountCartMall") }}',
				        type: 'get',
				        success: function(response){
				        	$('.mall_cart__quantity').html(response[0]);
				        }
				    });
	            	toastr.success('Items Add To Cart. <a href="{{ route("checkout_mall") }}" class="view-cart-button pull-right"><i class="fa fa-shopping-cart"></i> View Cart</a>');
	            }else{
	            	toastr.error(response);
	            }
	        },
	    });
  	}else{
  		window.location.href = "{{ route('login') }}";
  	}
  });

  $('.add-favourite-btn').click( function(e){
  		e.preventDefault();
  		$('.loading-gif').show();
  		var ele = $(this);
	  	var isAdmin = '{{ Auth::guard("admin")->check() }}';
	  	var isAgent = '{{ Auth::guard("agent")->check() }}';
	  	var isUser = '{{ Auth::guard("web")->check() }}';

	  	if(isAdmin){
	  		auth_check = isAdmin;
	  	}else if(isAgent){
	  		auth_check = isAgent;
	  	}else if(isUser){
	  		auth_check = isUser;
	  	}else{
	  		auth_check = "";
	  	}
	  	
	  	if(auth_check){
	  		var fd = new FormData();
		  		fd.append('product_id', '{{ $product->id }}');

		  	$.ajax({
		        url: '{{ route("Favourite") }}',
		        type: 'post',
		        data: fd,
		        contentType: false,
		        processData: false,
		        success: function(response){
		        	$('.wishlist_count').html(response);
		        	$('.loading-gif').hide();
		        	if(response[0] == 1){
		        		ele.html('<i class="fa fa-heart fa-2x" aria-hidden="true"></i>');
						toastr.success('Added to the wish list.');
		        	}else{
						ele.html('<i class="far fa-heart fa-2x" aria-hidden="true"></i>');
						toastr.info('Removed from wish list.');
		        	}
		        }
		    });
	  	}else{
	  		window.location.href = "{{ route('login') }}";
	  	}
  });

  $('.sub-category-list').click( function(){
  	  var ele = $(this);
  	  $('.sub-category-list').removeClass('active');
  	  $(this).addClass('active');
  	  ele.parent().find('input[name="sub_category_id"]').prop("checked", true);
  });

  $('.add-qty-button').click( function(e){

		e.preventDefault();
		
		var ele = $(this);
		var quantity = ele.parent().find('input[name="quantity"]').val();
		var balance = ele.closest('ul').find('input[name="balance_quantity"]').val();
		quantity = Number(quantity) + 1;
		if(quantity > balance){
			alert('此商品的最大数量是 '+balance);
			$('.loading-gif').hide();
			return false;
		}else{
			ele.parent().find('input[name="quantity"]').val(quantity);			
		}
		
	});

	$('.deduct-qty-button').click( function(e){
		e.preventDefault();
		
		var ele = $(this);
		var quantity = ele.parent().find('input[name="quantity"]').val();
		if(quantity != 1){
			quantity = Number(quantity) - 1;
			ele.parent().find('input[name="quantity"]').val(quantity);
		}		
	});

	
	if(variation_enable == 1){
		$('.variation_option').click( function(e){
			e.preventDefault();

			$('.loading-gif').show();
			var ele = $(this);
			var vid = ele.data('id');

			$('.quantity-balance').html('');
			$('.variation_option').removeClass('active');
			ele.addClass('active');
			if(second_variation_enable == 0){
				var fd = new FormData();
				  	fd.append('vid', vid);

				$.ajax({
			        url: '{{ route("getVariation") }}',
			        type: 'post',
			        data: fd,
			        contentType: false,
			        processData: false,
			        success: function(response){
			        	$('.loading-gif').hide();
			        	if(response[0] != 0){
			        		if(response[4] > 0){
			        			if(response[5] > 0){
			        				$('.main-price').html(response[0]+' Point'+
				        									'<del style="font-size: 13px;">\
							                          			'+response[1]+'\
							                          		</del>\
							                              	&nbsp;\
							                              	<span class="badge bg-danger" style="font-size: 13px;">\
				                                        		Saving '+response[4]+'%\
				                                        	</span>\
				                                        	<br>\
	                                						<br>\
	                                						<span class="badge badge-info" style="font-size: 13px;">\
	                                							Earn '+response[5]+' PV\
	                                						</span>');
			        			}else{
				        			$('.main-price').html(response[0]+' Point'+
				        									'<del style="font-size: 13px;">\
							                          			'+response[1]+'\
							                          		</del>\
							                              	&nbsp;\
							                              	<span class="badge bg-danger" style="font-size: 13px;">\
				                                        		Saving '+response[4]+'%\
				                                        	</span>');
			        			}
			        		}else{
				        		$('.main-price').html(response[0]+" Point");
			        		}
							$('.has-special-price').html(response[1]+" Point");
			        	}else{
			        		$('.main-price').html(response[1]+' Point');
							$('.has-special-price').hide();
							if(response[3] != 0){
								$('.special-price-notif').html('*Buy 2 or more quantity for a special price of: '+response[3]+' Point');
							}else{
								$('.special-price-notif').html('This variation does not have a special price.');
							}
			        	}

			        	if(response[2] <= 0){
			        		$('.quantity-balance').html('Out of stock');
			        	}else{
			        		$('.quantity-balance').html('Stock Balance: '+ response[2] +' Items');
			        	}

						// variation image
						if(response[5] != '') {
							$('.details-img').css('background-image', 'url(' + response[5] + ')');
							$('#show-img').attr('src', response[5]);
							$('#show-img').find('img').attr('src', response[5]);
						} else {
							$('.details-img').css('background-image', 'url(' + '{{$Fimage}}' + ')');
						}
			        }
			    });				
			}else{
				var fd = new FormData();
				  	fd.append('vid', vid);

				$.ajax({
			        url: '{{ route("getSecondVariationList") }}',
			        type: 'post',
			        data: fd,
			        contentType: false,
			        processData: false,
			        success: function(response){
			        	$('.special-price-notif').html('*Buy 2 or more quantity for a special price');
			        	$('.loading-gif').hide();
			        	$('.second-variation-list').html(response);	
			        }
			    });
				$('.loading-gif').hide();
			}

			if(variation_enable == 1){
				var fd = new FormData();
					fd.append('vid', vid);

				$.ajax({
					url: '{{ route("getVariationimage") }}',
					type: 'post',
					data: fd,
					contentType: false,
					processData: false,
					success: function(response){
						// variation image
						if(response != '') {
							$('.details-img').css('background-image', 'url(' + response + ')');
							$('#show-img').attr('src', response);
							$('#show-img').find('img').attr('src', response);
						} else {
							$('.details-img').css('background-image', 'url(' + '{{$Fimage}}' + ')');
						}
					}
				});
			}
		});

		$.ajaxSetup({
	          headers: {
	            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	        }
	    });
		$('.variation_option.active').trigger('click');
	}

	if(second_variation_enable == 1){

		var ori_price = "{{ $get_product_pricing['product_price_range'] }}";
		
		$('.variation_option').click( function(e){
			e.preventDefault();

			$('.main-price').html('RM '+ori_price);
		});
		
		$('.second-variation-list').on('click', '.second_variation_option', function(e){
			e.preventDefault();

			$('.loading-gif').show();
			var ele = $(this);
			var vid = ele.data('id');

			$('.second_variation_option').removeClass('active');
			ele.addClass('active');

			var fd = new FormData();
			  	fd.append('vid', vid);

			$.ajax({
		        url: '{{ route("getSecondVariation") }}',
		        type: 'post',
		        data: fd,
		        contentType: false,
		        processData: false,
		        success: function(response){
		        	$('.loading-gif').hide();
		        	if(response[0] != 0){
			        	if(response[4] > 0){
		        			$('.main-price').html(response[0]+' Point'+
		        									'<del style="font-size: 13px;">\
					                          			'+response[1]+'\
					                          		</del>\
					                              	&nbsp;\
					                              	<span class="badge bg-danger" style="font-size: 13px;">\
		                                        		Saving '+response[4]+'%\
		                                        	</span>');
		        		}else{
			        		$('.main-price').html(response[0]+' Point');
		        		}
						$('.has-special-price').html(response[1]+' Point');
		        	}else{
		        		$('.main-price').html(response[1]+' Point');
						$('.has-special-price').hide();
						if(response[3] != 0){
							$('.special-price-notif').html('*Buy 2 or more quantity for a special price of: '+response[3]+' Point');
						}else{
							$('.special-price-notif').html('This variation does not have a special price.');
						}
		        	}

		        	if(response[2] <= 0){
		        		$('.quantity-balance').html('Out of stock');
		        	}else{
		        		$('.quantity-balance').html('Stock Balance: '+ response[2] +' Items');
		        	}

					// variation image
					if(response[5] != '') {
						$('.details-img').css('background-image', 'url(' + response[5] + ')');
						$('#show-img').attr('src', response[5]);
						$('#show-img').find('img').attr('src', response[5]);
					} else {
						$('.details-img').css('background-image', 'url(' + '{{$Fimage}}' + ')');
					}
		        }
		    });
		});

		$.ajaxSetup({
	          headers: {
	            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	        }
	    });
		$('.second_variation_option.active').trigger('click');
	}

	$('.payment_method').click( function(e){
		var ele = $(this);
		$('.parent_payment_method').removeClass('active');
		ele.parent().addClass('active');
	});

	$('.img-header').on('click', '.details-img', function(e){
		// e.preventDefault();

		var ele = $(this);
		var backgroundImage = ele.css('background-image');
		var imageURL = backgroundImage.replace(/^url\(['"]?(.*?)['"]?\)$/, '$1');
		$('.modal-img').attr('src', imageURL);
	});
</script>
@endsection