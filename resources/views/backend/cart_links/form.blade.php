@php
if(isset($cart_link)){
	$action_url = route('cart_link.cart_links.update', $cart_link->id);
}else{
	$action_url = route('cart_link.cart_links.store');
}

@endphp
<div class="container-box">
	<div class="row">
		<div class="col-12">
			<form method="POST" action="{{ $action_url }}" id="cart-link-form">
				@csrf
				@if(isset($cart_link))
				@method('PUT')
				@endif
				<div class="form-group">
					<div class="row">
						<div class="col-sm-2">
							{{ isset($data['backendlang']['backendlang']['products']) ? $data['backendlang']['backendlang']['products'] :'' }}: <span class="important-text">*</span>
						</div>
						<div class="col-sm-6">
							<div class="product-group">
								@if(isset($cart_link) && !$cart_link_details->isEmpty())
								@php
									$row_id = 1;
								@endphp
									@foreach($cart_link_details as $detail)
										<div class="individual-product">
											<div class="individual-product-selection">
												<div class="form-group">
													<div class="row">
														<div class="col-sm-6">
															<select class="form-control products" name="products[{{ $row_id }}]">
																<option value="">{{ isset($data['backendlang']['backendlang']['Please_Select_Products']) ? $data['backendlang']['backendlang']['Please_Select_Products'] :'' }}</option>
																@foreach($products as $product)
																	<option value="{{ $product->id }}" {{ $product->id == $detail->product_id ? 'selected' : '' }}>{{ $product->product_name }}</option>
																@endforeach
															</select>
														</div>
														<div class="col-sm-6">
															<input type="text" class="form-control qty" name="qty[{{ $row_id }}]" onkeypress="return isNumberKey(event)" placeholder="{{ isset($data['backendlang']['backendlang']['Quantity']) ? $data['backendlang']['backendlang']['Quantity'] :'' }}" value="{{ !empty($detail->qty) ? $detail->qty : '' }}">
														</div>
													</div>
													<input type="hidden" class="row_id" name="row_id" value="{{ $row_id }}">
													<input type="hidden" name="cart_link_detail_id[{{ $row_id }}]" value="{{ $detail->id }}">
												</div>
												<div class="variation-selection">
													@if(isset($variation_choices[$detail->id]))
														<div class="form-group">
															<select class="form-control variations" name="variations[{{ $row_id }}]">
																<option value="">{{ isset($data['backendlang']['backendlang']['Please_Select_Variations']) ? $data['backendlang']['backendlang']['Please_Select_Variations'] :'' }}</option>
										                		@foreach($variation_choices[$detail->id] as $variation)
										                			<option value="{{ $variation->id }}" {{ $variation->id == $detail->variation_id ? 'selected' : '' }}>{{ $variation->variation_name }}</option>
										                		@endforeach
															</select>
														</div>
													@endif
												</div>
												<div class="second-variation-selection">
													@if(isset($second_variation_choices[$detail->id]))
														<div class="form-group">
															<select class="form-control second_variations" name="second_variations[{{ $row_id }}]">
																<option value="">{{ isset($data['backendlang']['backendlang']['Please_Select_Variations']) ? $data['backendlang']['backendlang']['Please_Select_Variations'] :'' }}</option>
										                		@foreach($second_variation_choices[$detail->id] as $second_variation)
										                			<option value="{{ $second_variation->id }}" {{ $second_variation->id == $detail->second_variation_id ? 'selected' : '' }}>{{ $second_variation->variation_name }}</option>
										                		@endforeach
															</select>
														</div>
													@endif
												</div>
											</div>
											<div class="individual-product-details">

											</div>
										</div>
									@php
										$row_id++;
									@endphp
									@endforeach
								@else
									<div class="individual-product">
										<div class="individual-product-selection">
											@php
												$row_id = 1;
											@endphp
											<div class="form-group">
												<div class="row">
													<div class="col-sm-6">
														<select class="form-control products" name="products[{{ $row_id }}]">
															<option value="">{{ isset($data['backendlang']['backendlang']['Please_Select_Products']) ? $data['backendlang']['backendlang']['Please_Select_Products'] :'' }}</option>
															@foreach($products as $product)
																<option value="{{ $product->id }}">{{ $product->product_name }}</option>
															@endforeach
														</select>
													</div>
													<div class="col-sm-6">
														<input type="text" class="form-control qty" name="qty[{{ $row_id }}]" onkeypress="return isNumberKey(event)" placeholder="{{ isset($data['backendlang']['backendlang']['Quantity']) ? $data['backendlang']['backendlang']['Quantity'] :'' }}">
													</div>
												</div>
												<input type="hidden" class="row_id" name="row_id" value="{{ $row_id }}">
												<input type="hidden" name="cart_link_detail_id[{{ $row_id }}]" value="">
											</div>
											<div class="variation-selection">
											</div>
											<div class="second-variation-selection">
											</div>
										</div>
										<div class="individual-product-details">

										</div>
									</div>
								@endif
							</div>
							<div class="form-group">
								<a href="#" class="btn btn-outline-primary btn-block add-product-option">
									<i class="bi bi-plus"></i> {{ isset($data['backendlang']['backendlang']['Add_Products']) ? $data['backendlang']['backendlang']['Add_Products'] :'' }}
								</a>
							</div>
						</div>
					</div>
				</div>

				<div class="form-group">
					<div class="row">
						<div class="col-sm-2">
							{{ isset($data['backendlang']['backendlang']['Sub_Total']) ? $data['backendlang']['backendlang']['Sub_Total'] :'' }}:
						</div>
						<div class="col-sm-6">
							<div class="subtotal_price"></div>
						</div>
					</div>
				</div>

				<div class="form-group">
					<div class="row">
						<div class="col-sm-2">
							{{ isset($data['backendlang']['backendlang']['Modify_Price']) ? $data['backendlang']['backendlang']['Modify_Price'] :'' }}:
						</div>
						<div class="col-sm-6">
							<input type="text" class="form-control modify_price" name="modify_price" onkeypress="return isNumberKey(event)" placeholder="{{ isset($data['backendlang']['backendlang']['Modify_Price']) ? $data['backendlang']['backendlang']['Modify_Price'] :'' }}" value="{{ !empty($cart_link->price) ? $cart_link->price : '' }}">
						</div>
					</div>
				</div>

				<div class="form-group">
					<div class="row">
						<div class="col-sm-2">
							{{ isset($data['backendlang']['backendlang']['Limit_Quantity']) ? $data['backendlang']['backendlang']['Limit_Quantity'] :'' }}:
						</div>
						<div class="col-sm-6">
							<input type="text" class="form-control quantity" name="quantity" onkeypress="return isNumberKey(event)" placeholder="{{ isset($data['backendlang']['backendlang']['Limit_Quantity']) ? $data['backendlang']['backendlang']['Limit_Quantity'] :'' }}" value="{{ !empty($cart_link->qty) ? $cart_link->qty : '' }}">
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

