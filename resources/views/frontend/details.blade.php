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

							@if($flash_sale_active)
                        		<span class="badge bg-danger">
                        			{{ isset($data['lang']['lang']['flash_sale']) ? $data['lang']['lang']['flash_sale'] :'Flash Sale' }}
                        		</span>
                        		<br>
                        		<span class="important-text flash-sale-quantity"></span>
                        	@endif
							<div class="form-group">
								@php
                                    $special_price_available = 0;
                                    if(!empty($get_product_pricing['product_special_range']) && $get_product_pricing['product_special_range'] != $get_product_pricing['product_price_range']){
                                        $special_price_available = 1;
                                    }
                                @endphp
								<h4 class="main-price {{ $special_price_available == '1' ? 'text-red' : '' }}" style="display: contents;">
									RM {!! $get_product_pricing['product_price_range'] !!}
								</h4>
								<span>
	                                @if($flash_sale_active && $get_original_product_pricing['product_price_range'] != $get_product_pricing['product_price_range'])
	                                	<small>
	                                		<del class="has-special-price {{ $special_price_available == '1' ? 'text-grey' : '' }}">
	                                			RM {!! $get_original_product_pricing['product_price_range'] !!}
	                                		</del>
	                                	</small>
	                                @elseif(!empty($get_product_pricing['product_special_range']) && $get_product_pricing['product_special_range'] != $get_product_pricing['product_price_range'])
		                                <small>
		                                    <del class="has-special-price {{ $special_price_available == '1' ? 'text-grey' : '' }}">
		                                    	RM {!! $get_product_pricing['product_special_range'] !!}
		                                    </del>
		                                </small>
	                                @endif
	                           	</span>
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
	                            
							<!-- <h4 class="pv-display" style="color: #0abab5; font-size: 14px;">
								@if(!empty($product_pv[1]))
									{{ $product_pv[0] }} - {{ $product_pv[1] }} PV
								@else
									@if(!empty($product_pv[0]))
										{{ $product_pv[0] }} PV
									@else
										0 PV
									@endif
								@endif
							</h4> -->
							<hr>

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

		@if(!empty($addondealItem->id) && !empty($addonDeal->id))
			<div class="mt-4">
				<h4 class="title title-underline">
					{{ isset($data['lang']['lang']['add_on_deals']) ? $data['lang']['lang']['add_on_deals'] : 'Add-on Deals' }}
				</h4>
				<hr>
				@foreach($subItem as $items)
					@php
						$class = '';
						if ($loop->iteration > 1) {
							$class = 'd-none';
						}else{
							$class = '';
						}
					@endphp
				<form action="{{route('deal_cart')}}" method="post">
					@csrf
					<div class="form-group">
						<div class="row margin-30 add-on-row {{$class}}">
							<div class="col-lg-2 col-md-4 col-sm-4 col-4 product-wrap text-center p-2">
								<div class="product-media">
									<img src="{{asset(!empty($product->first_image->image) ? $product->first_image->image : '')}}" class="height-120" alt="">
								</div>
								<div class="product-details mt-2">
									<h4 class="product-name" style="font-size: 14px;">
										<!-- {{ $addondealItem->product_name }} -->
										@if(isset($_COOKIE['global_language']) && !empty($_COOKIE['global_language']))
											@if($_COOKIE['global_language'] == '1')
												@if(!empty($addondealItem->product_name_cn))
													{{ $addondealItem->product_name_cn }}
												@else
													{{ $addondealItem->product_name }}
												@endif
											@else
												{{ $addondealItem->product_name }}
											@endif
										@else
											{{ $addondealItem->product_name }}
										@endif
										<input type="hidden" name="product_id" value="{{$addondealItem->product_id}}">
										<input type="hidden" name="add_on_id" value="{{$addondealItem->add_on_id}}">
									</h4>
									@if($addondealItem->variation_enable == 1)
									<select name="addon_variation" id="addon_variation" style="font-size: 14px;">
										<option value="">{{ isset($data['lang']['lang']['select_variation']) ? $data['lang']['lang']['select_variation'] : 'Select variation' }}</option>
										@if(!empty($variations) && !$variations->isEmpty())
											@foreach($variations as $variation)
												<option value="{{$variation->id}}" {{ ($loop->iteration  == 1) ? 'selected' : '' }}>{{$variation->variation_name}}</option>
											@endforeach
										@endif
									</select>
									@endif
									@if($addondealItem->second_variation_enable)
										<select name="addon_second_variation" id="addon_second_variation" class="mt-2">
											<option value="">{{ isset($data['lang']['lang']['select_second_variation']) ? $data['lang']['lang']['select_second_variation'] : 'Select Second Variation' }}</option>
											@if(!empty($second_variations) && !$second_variations->isEmpty())
											@foreach($second_variations as $variation)
												<option value="{{$variation->id}}" {{ ($loop->iteration  == 1) ? 'selected' : '' }}>{{$variation->variation_name}}</option>
											@endforeach
										@endif
										</select>
									@endif
									<div class="product-price">
										RM {{ number_format($main_item_price['product_price'], 2) }}
									</div>
								</div>
							</div>
							<div class="col-1 d-flex align-items-center justify-content-center mb-mw-0">
								<i class="fa fa-plus"></i>
							</div>
							<div class="col-lg-2 col-md-4 col-sm-4 col-4 product-wrap text-center p-2">
								<div class="product-media">
									<img src="{{asset(!empty($subItem_image[$items->id]) ? $subItem_image[$items->id] : '')}}" class="height-120" alt="">
								</div>
								<div class="product-details mt-2">
									<h4 class="product-name" style="font-size: 14px;">
										<!-- {{$items->product_name}} -->
										@if(isset($_COOKIE['global_language']) && !empty($_COOKIE['global_language']))
											@if($_COOKIE['global_language'] == '1')
												@if(!empty($items->product_name_cn))
													{{ $items->product_name_cn }}
												@else
													{{ $items->product_name }}
												@endif
											@else
												{{ $items->product_name }}
											@endif
										@else
											{{ $items->product_name }}
										@endif
										<input type="hidden" name="addon" value="{{$items->id}}">
									</h4>
									@if(!empty($items->variation_id))
										<small>
											{{ isset($data['lang']['lang']['variation']) ? $data['lang']['lang']['variation'] : 'Variation' }}: 
											<!-- {{!empty($subItem_variation[$items->id]->variation_name) ? $subItem_variation[$items->id]->variation_name : ''}} -->
											{{ !empty($items->variation_name) ? $items->variation_name : '' }}
										</small>
									@endif
									@if(!empty($items->second_variation_id))
									<br>
										<small>
											{{ isset($data['lang']['lang']['second_variation']) ? $data['lang']['lang']['second_variation'] : 'Option' }}:
											<!-- {{!empty($subItem_second_variation[$items->id]->variation_name) ? $subItem_second_variation[$items->id]->variation_name : ''}} -->
											{{ !empty($items->second_variation_name) ? $items->second_variation_name : '' }}
										</small>
									@endif
									<div class="qty">
										@php
											$limit = !empty($items->purchase_limit) ? $items->purchase_limit : 1;
										@endphp
										<select name="qty" id="qty">
											@for($i=1; $i<=$limit; $i++)
												<option value="{{$i}}">{{$i}}</option>
											@endfor
										</select>
									</div>
									<div class="product-price">
										@if (!empty($sub_item_price[$items->id]))
											RM {{ number_format($sub_item_price[$items->id], 2) }}
										@endif
									</div>
									@if(!empty($original_sub_item_price[$items->id]) && $original_sub_item_price[$items->id] != $sub_item_price[$items->id])
										<div class="product-price">
											<del>
												RM {{ number_format($original_sub_item_price[$items->id], 2) }}
											</del>
										</div>
									@endif
								</div>
							</div>
							<div class="col-1 mb-d-none">
								&nbsp;
							</div>
							<div class="col-lg-2 col-md-4 col-sm-4 col-4 product-button text-center p-2 mb-d-end">
								@php
									$total_amount = 0;
									$total_amount = $main_item_price['product_price'] + $sub_item_price[$items->id];
									$total_saving = ($main_item_price['product_price'] + $original_sub_item_price[$items->id]) - $total_amount;
								@endphp
								<div class="product-details">
									<h6 class="total-p-price mb-2">
										{{ isset($data['lang']['lang']['total']) ? $data['lang']['lang']['total'] : 'Total' }} RM {{ number_format($total_amount, 2) }}
									</h6>
									<h6 class="total-saving">
										{{ isset($data['lang']['lang']['saving']) ? $data['lang']['lang']['saving'] : 'Saving' }} RM {{ number_format($total_saving, 2) }}
									</h6>
									<br>
									<button type="submit" class="btn mb-px-8 mb-f-9 set_button set_text">{{ isset($data['lang']['lang']['add_to_cart']) ? $data['lang']['lang']['add_to_cart'] : 'Add to cart' }}</button>
								</div>
							</div>
						</div>
					</div>
				</form>
				@endforeach
				<div class="text-center">
					<a href="#" class="btn view-more set_button set_text">
						{{ isset($data['lang']['lang']['view_more']) ? $data['lang']['lang']['view_more'] : 'View More' }}
					</a>
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
  		auth_check = "1";
  	}
  	
  	if(auth_check){
	  	var fd = new FormData();
	  	fd.append('product_id', '{{ $product->id }}');
	  	fd.append('quantity', $('input[name="quantity"]').val());
	  	fd.append('sub_category_id', option);
	  	fd.append('second_sub_category_id', second_option);
	  	


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

	        	if(response == 'ok'){
	        		$.ajax({
				        url: '{{ route("CountCart") }}',
				        type: 'get',
				        success: function(response){
				        	$('.cart__quantity').html(response);
				        }
				    });
				    
	            	toastr.success('Items Add To Cart. <a href="{{ route("checkout") }}" class="view-cart-button pull-right"><i class="fa fa-shopping-cart"></i> View Cart</a>');
	            }else{
	            	toastr.error(response);
	            	// console.log(response)
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
			        		// if(response[4] > 0){
			        		// 	if(response[5] > 0){
			        		// 		$('.main-price').html('RM '+response[0]+
				        	// 								'<del style="font-size: 13px;">\
							//                           			'+response[1]+'\
							//                           		</del>\
							//                               	&nbsp;\
							//                               	<span class="badge bg-danger" style="font-size: 13px;">\
				            //                             		{{ isset($data["lang"]["lang"]["saving"]) ? $data["lang"]["lang"]["saving"] : "Saving" }} '+response[4]+'%\
				            //                             	</span>\
				            //                             	<br>\
	                        //         						<br>\
	                        //         						<span class="badge badge-info" style="font-size: 13px;">\
	                        //         							Earn '+response[5]+' PV\
	                        //         						</span>');
			        		// 	}else{
				        	// 		$('.main-price').html('RM '+response[0]+
				        	// 								'<del style="font-size: 13px;">\
							//                           			'+response[1]+'\
							//                           		</del>\
							//                               	&nbsp;\
							//                               	<span class="badge bg-danger" style="font-size: 13px;">\
				            //                             		Saving '+response[4]+'%\
				            //                             	</span>');
			        		// 	}
			        		// }else{
				        	// 	$('.main-price').html('RM '+response[0]);
			        		// }
			        		$('.main-price').html('RM '+response[0]);
							$('.has-special-price').html('RM '+response[1]);

							if(response[4] != 0){
								$('.has-special-price').html('RM '+response[4]);
							}
			        	}else{
			        		$('.main-price').html('RM '+response[1]);
							$('.has-special-price').hide();
							// if(response[3] != 0){
							// 	$('.special-price-notif').html('*Buy 2 or more quantity for a special price of: RM'+response[3]);
							// }else{
							// 	$('.special-price-notif').html('This variation does not have a special price.');
							// }
							if(response[4] != 0){
								$('.has-special-price').html('RM '+response[4]);
							}
			        	}

			        	if(response[2] <= 0){
			        		$('.quantity-balance').html('Out of stock');
			        	}else{
			        		$('.quantity-balance').html('Stock Balance: '+ response[2] +' Items');
			        	}

			        	// if(response[6]){
			        	// 	$('.pv-display').empty();
			        	// 	$('.pv-display').html(response[6]+' PV');
			        	// }else{
			        	// 	$('.pv-display').empty();
			        	// 	$('.pv-display').html('0 PV');	
			        	// }

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

			update_product_sold();

			get_flash_sale_details();
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
			        	// if(response[4] > 0){
		        		// 	$('.main-price').html('RM '+response[0]+
		        		// 							'<del style="font-size: 13px;">\
					    //                       			'+response[1]+'\
					    //                       		</del>\
					    //                           	&nbsp;\
					    //                           	<span class="badge bg-danger" style="font-size: 13px;">\
		                //                         		Saving '+response[4]+'%\
		                //                         	</span>');
		        		// }else{
			        	// 	$('.main-price').html('RM '+response[0]);
		        		// }
		        		$('.main-price').html('RM '+response[0]);
						$('.has-special-price').html('RM '+response[1]);

						if(response[4] != 0){
							$('.has-special-price').html('RM '+response[4]);
						}
		        	}else{
		        		$('.main-price').html('RM '+response[1]);
						$('.has-special-price').hide();
						// if(response[3] != 0){
						// 	$('.special-price-notif').html('*Buy 2 or more quantity for a special price of: RM'+response[3]);
						// }else{
						// 	$('.special-price-notif').html('This variation does not have a special price.');
						// }
						if(response[4] != 0){
							$('.has-special-price').html('RM '+response[4]);
						}
		        	}

		        	if(response[2] <= 0){
		        		$('.quantity-balance').html('Out of stock');
		        	}else{
		        		$('.quantity-balance').html('Stock Balance: '+ response[2] +' Items');
		        	}

		        	// if(response[6]){
		        	// 	$('.pv-display').empty();
		        	// 	$('.pv-display').html(response[6]+' PV');
		        	// }else{
		        	// 	$('.pv-display').empty();
		        	// 	$('.pv-display').html('0 PV');	
		        	// }

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

			update_product_sold();

		    get_flash_sale_details();
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

	$('.view-more').click(function(e){
		e.preventDefault();
		$('.add-on-row').removeClass('d-none');
		$('.add-on-row').addClass('p-hide');
		$('.view-more').addClass('hide-row');

	});

	function get_flash_sale_details(){
		$('.flash-sale-quantity').empty();
		$('.loading-gif').show();

		var product_id = '{{ $product->id }}';
		var variation_id = $('.variation_option.active').data('id');
  		var second_variation_id = $('.second_variation_option.active').data('id');

		var fd = new FormData();
			fd.append('product_id', product_id);
			fd.append('variation_id', variation_id);
			fd.append('second_variation_id', second_variation_id);

		$.ajax({
	        url: '{{ route("getFlashSaleDetail") }}',
	        type: 'post',
	        data: fd,
	        contentType: false,
	        processData: false,
	        success: function(response){
	        	$('.loading-gif').hide();
	        	if(response[0] == '1'){
	        		$('.flash-sale-quantity').html('{{ isset($data["lang"]["lang"]["limited_quantity"]) ? $data["lang"]["lang"]["limited_quantity"] :"Limited Quantity" }}: '+response[1]);
	        	}
	        },
	    });
	}

	function update_product_sold(){
		var option = $('.variation_option.active').data('id');
  		var second_option = $('.second_variation_option.active').data('id');

  		if(!second_option){
  			var array = <?php echo $variation_qty; ?>;

  			$('.soldBalance').html(array[option]);
  		}else{
  			var array = <?php echo $second_variation_qty; ?>;

  			$('.soldBalance').html(array[second_option]);
  		}
	}

	$.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
	get_flash_sale_details();

	document.addEventListener('DOMContentLoaded', function() {
        var copyLink = document.getElementById('copyLink');
        copyLink.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent the default link behavior

            // Create a temporary input element
            var input = document.createElement('input');
            input.setAttribute('value', copyLink.getAttribute('href'));
            document.body.appendChild(input);

            // Select the input's value
            input.select();
            input.setSelectionRange(0, 99999); // For mobile devices

            // Copy the selected text
            document.execCommand('copy');

            // Remove the temporary input element
            document.body.removeChild(input);

            toastr.success('Copied Successful');
        });
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