@extends('layouts.app')

@section('content')

<div class="breadcrumb">
    <div class="container">
        <h2>Promotion Details</h2>
        <ul><li>Home</li><li>Shop</li><li class="active">Promotion Details</li></ul>
    </div>
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
									if(!empty($Pimage->image))
										$Fimage = File::exists($Pimage->image) ? asset($Pimage->image) : asset('images/no-image-available-icon-6.jpg');
									else
										$Fimage = asset('images/no-image-available-icon-6.jpg');
									

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
									    	<div class="details-img" style="background-image: url({{ asset($Fimage) }});">
									    	</div>
										@endif										
									</div>
								</div>
								<div class="small-img">
							    	<img src="{{ asset('frontend/thumbnail-zoom/images/online_icon_right@2x.png') }}" class="icon-left" alt="" id="prev-img">
							    	<div class="small-container">
							      		<div id="small-img-roll" class="small-img-roll">
							      		@if(!$images->isEmpty())
											@foreach($images as $key => $image)
											@php
												$image = File::exists($image->image) ? asset($image->image) : asset('images/no-image-available-icon-6.jpg');
												$exp_two = explode(".", $image);
        										$file_ext_two = end($exp_two);
											@endphp
											@if($file_ext_two == 'mp4')
												<video style="width: 70px;" loop="1" class="show-small-img" src="{{ asset($image) }}">
					                            	<source src="{{ asset($image) }}" type="video/mp4">
					                        	</video>
											@else
								        		<img src="{{ $image }}" class="show-small-img" alt="" width="100%;">
											@endif
							        		@endforeach
										@else
											<img src="{{ asset('images/no-image-available-icon-6.jpg') }}" class="show-small-img" alt="" width="100%;">
										@endif
							      		</div>
							    	</div>
							    	<img src="{{ asset('frontend/thumbnail-zoom/images/online_icon_right@2x.png') }}" class="icon-right" alt="" id="next-img" width="100%;">
							  	</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<div class="row">
									<div class="col-9">
										<div class="product-details" style="width: 100%;">
											<span style="font-size: 24px;" >
												{{ $title->product_name }}
												<input type="hidden" name="ppid" value="{{ $title->pai_id }}">
											</span>
											@if(!empty($product->product_name_cn))
											<br>
											<span style="font-size: 24px;" >
												{{ $product->product_name_cn }}
											</span>
											@endif
										</div>
									</div>
									<div class="col-3" align="right">
										@if(Auth::guard('merchant')->check() || Auth::guard('admin')->check() || Auth::guard('web')->check())
											@if(!empty($favourite->id))
											<a href="#" class="add-favourite-btn" style="color: red;">
												<i class="fa fa-heart fa-2x" aria-hidden="true"></i>
											</a>
											@else
											<a href="#" class="add-favourite-btn" style="color: red;">
												<i class="fa fa-heart-o fa-2x" aria-hidden="true"></i>
											</a>
											@endif
										@endif
									</div>
								</div>
								
							</div>

							@php
							if(Auth::guard('merchant')->check() || Auth::guard('admin')->check()){
	                                $minSpecialPrice = number_format($sec_var_price_range[$title->pai_id][0], 2);
	                                $maxSpecialPrice = number_format($sec_var_price_range[$title->pai_id][1], 2);
	                                $minPrice = number_format($sec_var_price_range[$title->pai_id][2], 2);
	                                $maxPrice = number_format($sec_var_price_range[$title->pai_id][3], 2);
	                        }else{
	                        		$minSpecialPrice = number_format($sec_var_price_range_customer[$title->pai_id][0], 2);
	                                $maxSpecialPrice = number_format($sec_var_price_range_customer[$title->pai_id][1], 2);
	                                $minPrice = number_format($sec_var_price_range_customer[$title->pai_id][2], 2);
	                                $maxPrice = number_format($sec_var_price_range_customer[$title->pai_id][3], 2);
	                    	}
							@endphp

							<div class="form-group">
	                                  	<input type="hidden" name="papid" value="{{ $title->pai_id }}">
								<h4 class="main-price">
									@if(Auth::guard('merchant')->check() || Auth::guard('admin')->check())
										@if($title->variation_enable == 1 && $title->second_variation_enable == 1)
											@if($minSpecialPrice == $maxSpecialPrice && $minPrice == $maxPrice)
												@if($minSpecialPrice == 0 && $maxSpecialPrice == 0)
													RM {{ $maxPrice }}
												@else
                                                	RM {{ $minSpecialPrice }}
                                                	<del style="font-size: 13px;"> 
                                                		RM {{ $maxPrice }}
                                                	</del>
                                                	@php
	                                  					$get_amount = $maxPrice - $minSpecialPrice;
	                                  					$get_percentage = ($get_amount / $maxPrice) * 100;
	                                  				@endphp
                                                @endif
                                            @else
                                            	@if($minSpecialPrice > 0)
                                                	RM {{ $minSpecialPrice }} - {{ $maxSpecialPrice }}
                                                	<del style="font-size: 13px;"> 
                                                		RM {{ $minPrice }} - {{ $maxPrice }}
                                                	</del>
                                                	@php
	                                  					$get_amount = $minPrice - $minSpecialPrice;
	                                  					$get_percentage = ($get_amount / $minPrice) * 100;

	                                  					$get_amount_two = $maxPrice - $maxSpecialPrice;
	                                  					$get_percentage_two = ($get_amount_two / $maxPrice) * 100;
	                                  				@endphp
                                            	@else
                                            		RM {{ $minPrice }} - {{ $maxPrice }}
                                            	@endif
                                            @endif
                                            <!-- @if(!empty($priceV[$product->id][4]))
		                                	<br>
		                                	<br>
		                                	<span class="badge badge-info" style="font-size: 13px;">
		                                		Earn 
		                                		@if($priceV[$product->id][4] == $priceV[$product->id][5])
		                                			{{ $priceV[$product->id][4] }} PV
		                                		@else
		                                			{{ $priceV[$product->id][4] }} - {{ $priceV[$product->id][5] }} PV
		                                		@endif
		                                	</span>
		                                	@endif -->
										@elseif($title->variation_enable == 1)
											@if($minSpecialPrice == $maxSpecialPrice && $minPrice == $maxPrice)
												@if($minSpecialPrice == 0 && $maxSpecialPrice == 0)
													RM {{ $maxPrice }}
												@else
                                                	RM {{ $minSpecialPrice }}
                                                	<del style="font-size: 13px;"> 
                                                		RM {{ $maxPrice }}
                                                	</del>

                                                	@php
	                                  					$get_amount = $maxPrice - $minSpecialPrice;
	                                  					$get_percentage = ($get_amount / $maxPrice) * 100;
	                                  				@endphp
                                                @endif
                                            @else
                                            	@if($minSpecialPrice > 0)
                                                	RM {{ $minSpecialPrice }} - {{ $maxSpecialPrice }}
                                                	<del style="font-size: 13px;"> 
                                                		RM {{ $minPrice }} - {{ $maxPrice }}
                                                	</del>
                                                	@php
	                                  					$get_amount = $minPrice - $minSpecialPrice;
	                                  					$get_percentage = ($get_amount / $minPrice) * 100;

	                                  					$get_amount_two = $maxPrice - $maxSpecialPrice;
	                                  					$get_percentage_two = ($get_amount_two / $maxPrice) * 100;
	                                  				@endphp
                                            	@else
                                            		RM {{ $minPrice }} - {{ $maxPrice }}
                                            	@endif
                                            @endif
											
										@else
	                                  		RM {{ number_format($title->pai_price, 2) }} <small><del>RM {{ number_format($pricingRange[$title->pai_id][0], 2) }} </del></small>
	                                  	@endif
	                              	@else
	                              		@if(!empty($minSpecialPrice))
	                              			@if($title->second_variation_enable == 1 || $title->variation_enable == 1)
	                                            @if($minSpecialPrice == $maxSpecialPrice)
	                                                RM {{ $minSpecialPrice }}
	                                                <del style="font-size: 13px;"> 
                                                		RM {{ $maxPrice }}
                                                	</del>
                                                	@php
	                                  					$get_amount = $maxPrice - $minSpecialPrice;
	                                  					$get_percentage = ($get_amount / $maxPrice) * 100;
	                                  				@endphp
	                                            @else
	                                                RM {{ $minSpecialPrice }} - RM {{ $maxSpecialPrice }}
	                                                <del style="font-size: 13px;"> 
                                                		RM {{ $minPrice }} - {{ $maxPrice }}
                                                	</del>
                                                	@php
	                                  					$get_amount = $minPrice - $minSpecialPrice;
	                                  					$get_percentage = ($get_amount / $minPrice) * 100;

	                                  					$get_amount_two = $maxPrice - $maxSpecialPrice;
	                                  					$get_percentage_two = ($get_amount_two / $maxPrice) * 100;
	                                  				@endphp
	                                            @endif

	                                        @else
	                                            @if($minSpecialPrice == $maxSpecialPrice)
	                                                RM {{ $minSpecialPrice }}
	                                                <del style="font-size: 13px;"> 
                                                		RM {{ $maxPrice }}
                                                	</del>
                                                	@php
	                                  					$get_amount = $maxPrice - $minSpecialPrice;
	                                  					$get_percentage = ($get_amount / $maxPrice) * 100;
	                                  				@endphp
	                                            @else
	                                                RM {{ $minSpecialPrice }} - RM {{ $maxSpecialPrice }}
	                                                <del style="font-size: 13px;"> 
                                                		RM {{ $minPrice }} - {{ $maxPrice }}
                                                	</del>
                                                	@php
	                                  					$get_amount = $minPrice - $minSpecialPrice;
	                                  					$get_percentage = ($get_amount / $minPrice) * 100;

	                                  					$get_amount_two = $maxPrice - $maxSpecialPrice;
	                                  					$get_percentage_two = ($get_amount_two / $maxPrice) * 100;
	                                  				@endphp
	                                            @endif
	                                        @endif
	                              		@else
			                                @if($title->second_variation_enable == 1 || $title->variation_enable == 1)
	                                            @if($minPrice == $maxPrice)
	                                                RM {{ $minPrice }}
	                                                <del style="font-size: 13px;"> 
                                                		RM {{ $maxPrice }}
                                                	</del>
                                                	@php
	                                  					$get_amount = $maxPrice - $minSpecialPrice;
	                                  					$get_percentage = ($get_amount / $maxPrice) * 100;
	                                  				@endphp
	                                            @else
	                                                RM {{ $minPrice }} - RM {{ $maxPrice }}
	                                                <del style="font-size: 13px;"> 
                                                		RM {{ $minPrice }} - {{ $maxPrice }}
                                                	</del>
                                                	@php
	                                  					$get_amount = $minPrice - $minSpecialPrice;
	                                  					$get_percentage = ($get_amount / $minPrice) * 100;

	                                  					$get_amount_two = $maxPrice - $maxSpecialPrice;
	                                  					$get_percentage_two = ($get_amount_two / $maxPrice) * 100;
	                                  				@endphp
	                                            @endif

	                                        @else
	                                            @if($minPrice == $maxPrice)
	                                                RM {{ $minPrice }}
	                                                <del style="font-size: 13px;"> 
                                                		RM {{ $maxPrice }}
                                                	</del>
                                                	@php
	                                  					$get_amount = $maxPrice - $minSpecialPrice;
	                                  					$get_percentage = ($get_amount / $maxPrice) * 100;
	                                  				@endphp
	                                            @else
	                                                RM {{ $minPrice }} - RM {{ $maxPrice }}
	                                                <del style="font-size: 13px;"> 
                                                		RM {{ $minPrice }} - {{ $maxPrice }}
                                                	</del>
                                                	@php
	                                  					$get_amount = $minPrice - $minSpecialPrice;
	                                  					$get_percentage = ($get_amount / $minPrice) * 100;

	                                  					$get_amount_two = $maxPrice - $maxSpecialPrice;
	                                  					$get_percentage_two = ($get_amount_two / $maxPrice) * 100;
	                                  				@endphp
	                                            @endif
	                                        @endif
	                              		@endif
	                              	@endif
	                            </h4>
								<hr>
							</div>

							<div class="divider"></div>
                        	<div class="row">
								<div class="col-6">
		                        	<div class="product-detail__content__footer">
		                        		<ul>
		                        			@if(!empty($product->one_product_brand->brand_name))
				                            <li>
				                            	Brand: {{ $product->one_product_brand->brand_name }}
				                            </li>
				                            @endif
				                            @if(!empty($product->one_product_oum->uom_name))
				                            <li>
				                            	UOM: {{ $product->one_product_oum->uom_name }}
				                            </li>
				                            @endif
											@if($product->packages == 1)
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
											@endif
				                        </ul>
		                        	</div>
								</div>
								<div class="col-6">
		                        	<div class="product-detail__content__footer">
		                        		<ul>
		                        			@if(!empty($product->one_product_category->category_name))
				                            <li>
				                            	Category: {{ $product->one_product_category->category_name }}
				                            </li>
				                            @endif
				                            @if(!empty($product->one_product_subcategory->sub_category_name))
				                            <li>
				                            	Sub-Category: {{ $product->one_product_subcategory->sub_category_name }}
				                            </li>
				                            @endif
				                            
				                        </ul>
		                        	</div>
								</div>
							</div>

                        	@if($product->variation_enable == '1')
								@if(!$variations->isEmpty())
								<div class="form-group">
									<span style="font-size: 13px;">
										{{ (!empty($product->variation_title)) ? $product->variation_title : 'Option' }}: 
									</span>
									<br>
									<div class="form-group">
										@foreach($variations as $variationsKey => $variation)
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
										@foreach($second_variations as $svariationsKey => $second_variation)
											<div class="second_variation_option 
														{{ ($svStock[$second_variation->id] <= 0) ? 'out-of-stock' : '' }}" 
												 data-id="{{ $second_variation->id }}">
												{{ $second_variation->variation_name }}
											</div> 
										@endforeach
									</div>
								@endif
							@endif
							

							<div class="form-group">
								<span style="font-size: 13px;">Quantity: </span> &nbsp;&nbsp;
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
								<!-- @if(!Auth::guard("admin")->check() && !Auth::guard("merchant")->check())
									@if(!empty($title->pai_special_price) && $title->pai_special_price != 0)
	                              		<p class="important-text" style="font-size: 14px;">*Buy 2 or more quantity for this price per product: RM {{ number_format($title->pai_special_price, 2) }}</p>
	                              	@endif
	                            @endif -->
	                           
							</div>
							
							<div class="form-group">
								
								<a href="#" class="btn -red add-to-cart-button set_button set_text">
									<i class="fa fa-shopping-cart"></i> Add to cart
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="form-group">
			@if(!$Ppackages->isEmpty())
			<hr>
			<h5 class="gold-word">
			What's In The Box: </h5>
			<br>
			<div class="form-group" style="overflow: auto;">
				<table class="table table-bordered table-free-gift">
					<tr style="background-color: #82ada6;">
						<td>Product Description</td>
						<td>Unit price</td>
						<td>Quantity</td>
						<td>Subtotal</td>
					</tr>
					@php
					$totalPrice = 0;
					@endphp
					@foreach($Ppackages as $Ppackage)
					<tr>
						<td>
							<p>
								<img src="{{ asset(!empty($Ppackage->image) ? $Ppackage->image : 'images/no-image-available-icon-6.jpg') }}" style="width: 90px;">
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
			@endif
		</div>
		<hr>
		<div class="form-group product-description">

			<div class="widget-box transparent" id="recent-box">
				<div class="widget-header">
					

					<div class="widget-toolbar no-border" style="float: left;">
						<ul class="nav nav-tabs" id="recent-tab">
							<li class="parent_payment_method active">
								<a data-toggle="tab" class="payment_method f-15" data-id="1" href="#online-tab">Description</a>
							</li>
						</ul>
					</div>
				</div>

				<div class="widget-body">
					<div class="widget-main padding-4">
						<div class="tab-content padding-8">
							<div id="online-tab" class="tab-pane active">
								{!! htmlspecialchars_decode($product->description) !!}
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
  	var isUser = '{{ Auth::guard("web")->check() }}';
  	var option = $('.variation_option.active').data('id');
  	var second_option = $('.second_variation_option.active').data('id');

  	// console.log(option);
  	// console.log(second_option);
  	// exit();

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
  	}else if(isUser){
  		auth_check = '{{ !empty(Auth::guard("web")->user()->code) ? Auth::guard("web")->user()->code : '' }}';
  	}else{
  		auth_check = "";
  	}
  	
  	if(auth_check){
	  	var fd = new FormData();
	  	fd.append('product_id', '{{ $product->id }}');
	  	fd.append('quantity', $('input[name="quantity"]').val());
	  	fd.append('promo_id', $('input[name="ppid"]').val());
	  	fd.append('promo_price_id', $('input[name="papid"]').val());
	  	fd.append('sub_category_id', option);
	  	fd.append('second_sub_category_id', second_option);
	  	
	  	// alert($('input[name="papid"]').val());

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
	        		toastr.error('Insufficient wallet balance');
	        		return false;
	        	}

	        	if(response == 'quantity error'){
	        		toastr.error('Please add quantity at least 1');
	        		return false;
	        	}

	        	if(response == 'quantity exceed error'){
	        		toastr.error('Insufficient product balance');
	        		return false;
	        	}

	        	// if(response == 'quantity personal exceed'){
	        	// 	toastr.error('The maximum quantity available for this item is '+'{{ $stockBalance }}');
	        	// 	return false;
	        	// }

	        	if(response == 'ok'){
	        		$.ajax({
				        url: '{{ route("CountCart") }}',
				        type: 'get',
				        success: function(response){
				        	$('.cart__quantity').html(response);
				        	
				        }
				    });
				    if(mall == 1){
				    	toastr.success('Items Add To Cart. <a href="{{ route("checkout", "m=1") }}" class="view-cart-button pull-right"><i class="fa fa-shopping-cart"></i> View Cart</a>');
				    }else{
	            		toastr.success('Items Add To Cart. <a href="{{ route("checkout") }}" class="view-cart-button pull-right"><i class="fa fa-shopping-cart"></i> View Cart</a>');
				    }
	            }else{
	            	toastr.error('错误，请联系管理员');
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

		        	}else{
		        		ele.html('<i class="fa fa-heart-o fa-2x" aria-hidden="true"></i>');
		        	}
		        }
		    });
	  	}else{
	  		window.location.href = "{{ route('login') }}";
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
	  	
	  	if(auth_check){
	  		var fd = new FormData();
		  	fd.append('product_id', '{{ $product->id }}');

		  	$.ajax({
		        url: '{{ route("add_to_wish") }}',
		        type: 'post',
		        data: fd,
		        contentType: false,
		        processData: false,
		        success: function(response){
		        	$('.loading-gif').hide();
		        	if(response == 1){
		        		$('.wishlist_count').html(response);
		        		$('.add-favourite-btn').html('<i class="fa fa-heart fa-2x" aria-hidden="true"></i>')
		        	}else{
		        		toastr.info('已经在心愿单中');
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

	$('.payment_method').click( function(e){
		var ele = $(this);
		$('.parent_payment_method').removeClass('active');
		ele.parent().addClass('active');
	});

	if(variation_enable == 1){
		$('.variation_option').click( function(e){
			e.preventDefault();

			$('.loading-gif').show();
			var ele = $(this);
			var vid = ele.data('id');

			// $('.quantity-balance').html('');
			$('.variation_option').removeClass('active');
			ele.addClass('active');
			if(second_variation_enable == 0){
				var fd = new FormData();
				  	fd.append('vid', vid);
				  	fd.append('pid', '{{ $title->pai_id }}');
				
				$.ajax({
			        url: '{{ route("getVariationPromotion") }}',
			        type: 'post',
			        data: fd,
			        contentType: false,
			        processData: false,
			        success: function(response){
			        	$('.loading-gif').hide();
			        	if(response[0] != 0){
				        	if(response[4] > 0){
			        			if(response[5] > 0){
			        				$('.main-price').html('RM '+response[0]+
				        									'<del style="font-size: 13px;">\
							                          			'+response[1]+'\
							                          		</del>');
			        			}else{
				        			$('.main-price').html('RM '+response[0]+
				        									'<del style="font-size: 13px;">\
							                          			'+response[1]+'\
							                          		</del>');
			        			}
			        		}else{
				        		$('.main-price').html('RM '+response[0]);
			        		}
							$('.has-special-price').html('RM '+response[1]);
			        	}else{
			        		// alert(response[1]);
			        		$('.main-price').html('RM '+response[1]);
							$('.has-special-price').hide();
							if(response[3] != 0){
								$('.special-price-notif').html('*Buy 2 or more quantity for a special price of: RM'+response[3]);
							}else{
								$('.special-price-notif').html('This variation does not have a special price.');
							}
			        	}

			        	// if(response[2] <= 0){
			        	// 	$('.quantity-balance').html('Out of stock');
			        	// }else{
			        	// 	$('.quantity-balance').html(''+ response[2] +' Items Left');
			        	// }
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
		});

		$.ajaxSetup({
	          headers: {
	            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	        }
	    });
		$('.variation_option.active').trigger('click');
	}

	if(second_variation_enable == 1){
		$('.second-variation-list').on('click', '.second_variation_option', function(e){
			e.preventDefault();

			$('.loading-gif').show();
			var ele = $(this);
			var vid = ele.data('id');

			$('.second_variation_option').removeClass('active');
			ele.addClass('active');

			var fd = new FormData();
			  	fd.append('vid', vid);
			  	fd.append('pid', '{{ $title->pai_id }}');

			$.ajax({
		        url: '{{ route("getSecondVariationPromotion") }}',
		        type: 'post',
		        data: fd,
		        contentType: false,
		        processData: false,
		        success: function(response){
		        	$('.loading-gif').hide();
		        	if(response[0] != 0){
			        	$('.main-price').html('RM '+response[0]);
						$('.has-special-price').html('RM '+response[1]);
		        	}else{
		        		$('.main-price').html('RM '+response[1]);
						$('.has-special-price').hide();
						if(response[3] != 0){
							$('.special-price-notif').html('*Buy 2 or more quantity for a special price of: RM'+response[3]);
						}else{
							$('.special-price-notif').html('This variation does not have a special price.');
						}
		        	}

		        	// if(response[2] <= 0){
		        	// 	$('.quantity-balance').html('Out of stock');
		        	// }else{
		        	// 	$('.quantity-balance').html(''+ response[2] +' Items Left');
		        	// }
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
	
</script>
@endsection