@php
if(isset($product)){
	$action_url = route('product.products.update', $product->id);
}else{
	$action_url = route('product.products.store');
}
@endphp
<div class="row">
	<div class="col-12">
		<form method="POST" action="{{ $action_url }}" id="product-form" enctype="multipart/form-data">
			@csrf
			@if(isset($product))
			@method('PUT')
			@endif
			<div class="container-box form-group">
				<h3>{{ isset($data['backendlang']['backendlang']['Fill_In_Product_Information']) ? $data['backendlang']['backendlang']['Fill_In_Product_Information'] :'' }}</h3>
				<hr>
				<div class="form-group">
					<div class="row">
						<div class="col-sm-2">

						</div>
						<div class="col-md-5">
							<div class="checkbox">
								<label>
									<input name="featured" type="checkbox" class="ace" {{ isset($product) && $product->featured == '1' ? 'checked' : '' }} />
									<span class="lbl"> {{ isset($data['backendlang']['backendlang']['Featured_Product']) ? $data['backendlang']['backendlang']['Featured_Product'] :'' }}</span>
									<input type="hidden" name="mall" value="{{ !empty($mall) ? $mall : NULL }}">
								</label>
							</div>
						</div>
						<div class="col-md-5">
							<div class="checkbox">
								<label>
									<input name="dow" type="checkbox" class="ace" {{ (isset($product) && $product->dow == '1' || !isset($product)) ? 'checked' : '' }} />
									<span class="lbl"> {{ isset($data['backendlang']['backendlang']['Display_On_Website']) ? $data['backendlang']['backendlang']['Display_On_Website'] :'' }}</span>
								</label>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="row">
						<div class="col-sm-2">

						</div>
						<div class="col-md-5">
							<div class="checkbox">
								<label>
									<input name="free_shipping" type="checkbox" class="ace" {{ isset($product) && $product->free_shipping == '1' ? 'checked' : '' }} />
									<span class="lbl"> {{ isset($data['backendlang']['backendlang']['Free_West_Shipping']) ? $data['backendlang']['backendlang']['Free_West_Shipping'] :'' }}</span>
								</label>
							</div>
						</div>
						<div class="col-md-5">
							<div class="checkbox">
								<label>
									<input name="free_east_shipping" type="checkbox" class="ace" {{ isset($product) && $product->free_east_shipping == '1' ? 'checked' : '' }} />
									<span class="lbl">{{ isset($data['backendlang']['backendlang']['Free_East_Shipping']) ? $data['backendlang']['backendlang']['Free_East_Shipping'] :'' }}</span>
								</label>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="row">
						<div class="col-sm-2">

						</div>
						<div class="col-md-5">
							<div class="checkbox">
								<label>
									<input name="agent_only" type="checkbox" class="ace" {{ (isset($product) && $product->agent_only == '1' || !isset($product)) ? 'checked' : '' }} />
									<span class="lbl"> {{ isset($data['backendlang']['backendlang']['Agent_Only']) ? $data['backendlang']['backendlang']['Agent_Only'] :'' }}</span>
								</label>
							</div>
						</div>
						<div class="col-md-5">
							<div class="checkbox">
								<label>
									<input name="customer_only" type="checkbox" class="ace" {{ (isset($product) && $product->customer_only == '1' || !isset($product)) ? 'checked' : '' }} />
									<span class="lbl"> {{ isset($data['backendlang']['backendlang']['Customer_Only']) ? $data['backendlang']['backendlang']['Customer_Only'] :'' }}</span>
								</label>
							</div>
						</div>
					</div>
				</div>

				<div class="form-group">
					<div class="row">
						<div class="col-sm-2">

						</div>
						<div class="col-md-5">
							<div class="checkbox">
								<label>
									<input name="store_stock" type="checkbox" class="ace" {{ isset($product) && $product->store_stock == '1' ? 'checked' : '' }} />
									<span class="lbl"> {{ isset($data['backendlang']['backendlang']['Store_Stock']) ? $data['backendlang']['backendlang']['Store_Stock'] :'' }}</span>
								</label>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="row">
						<div class="col-sm-2">

						</div>
						<div class="col-md-10">
							<div class="checkbox">
								<label>
									<input name="display_home_page_product_slider" type="checkbox" class="ace" {{ isset($product) && $product->display_home_page_product_slider == '1' ? 'checked' : '' }} />
									<span class="lbl">{{ isset($data['backendlang']['backendlang']['Display_Slider']) ? $data['backendlang']['backendlang']['Display_Slider'] :'' }}</span>
									<input type="hidden" name="mall" value="{{ !empty($mall) ? $mall : NULL }}">
								</label>
							</div>
						</div>

					</div>
				</div>

				<div class="form-group">
					<div class="row">
						<div class="col-sm-2">
							<b>{{ isset($data['backendlang']['backendlang']['Name']) ? $data['backendlang']['backendlang']['Name'] :'' }}:</b> <span class="important-text">*</span>
						</div>
						<div class="col-sm-10">
							<input type="text" class="form-control" name="product_name" value="{{ isset($product) ? $product->product_name : old('product_name') }}" placeholder="{{ isset($data['backendlang']['backendlang']['Name']) ? $data['backendlang']['backendlang']['Name'] :'' }} *">
						</div>
					</div>
				</div>

				<div class="form-group">
					<div class="row">
						<div class="col-sm-2">
							<b>{{ isset($data['backendlang']['backendlang']['Category']) ? $data['backendlang']['backendlang']['Category'] :'' }}: </b><span class="important-text">*</span>
						</div>
						<div class="col-sm-6">
							<select class="form-control category_id" name="category_id">
								<option value="">{{ isset($data['backendlang']['backendlang']['Select_Category']) ? $data['backendlang']['backendlang']['Select_Category'] :'' }}</option>
								@foreach($categories as $category)
								<option {{ ((isset($product) && $product->category_id == $category->id) || old('category_id') == $category->id) ? 'selected': '' }} value="{{ $category->id }}">
									{{ $category->category_name }}
								</option>
								@endforeach
							</select>
						</div>
						<div class="col-sm-4 item_code">
							@if(isset($product) && empty($product->sub_category_id))
							{{ isset($product) ? "Item code: ".$product->item_code : '' }}
							@endif
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="row">
						<div class="col-sm-2">
							<b>{{ isset($data['backendlang']['backendlang']['subCategory']) ? $data['backendlang']['backendlang']['subCategory'] :'' }}:</b>
						</div>
						<div class="col-sm-6 sub_category">
							<select class="form-control sub_category_id" name="sub_category_id">
								<option value="">{{ isset($data['backendlang']['backendlang']['Select_Subcategory']) ? $data['backendlang']['backendlang']['Select_Subcategory'] :'' }}</option>
								@if(isset($sub_categories) && !$sub_categories->isEmpty())
								@foreach($sub_categories as $sub_category)
								<option {{ (isset($product) && $product->sub_category_id == $sub_category->id) ? 'selected' : '' }} value="{{ $sub_category->id }}">{{ $sub_category->sub_category_name }}</option>
								@endforeach
								@endif
							</select>
						</div>
						<div class="col-sm-4 sub_item_code">
							@if(isset($product) && !empty($product->sub_category_id))
							{{ isset($product) ? "Item code: ".$product->item_code : '' }}
							@endif
						</div>
					</div>
				</div>

				<input type="hidden" name="item_code" class="hidden_item_code" value="{{ isset($product) ? $product->item_code: '' }}">

				<div class="form-group">
					<div class="row">
						<div class="col-sm-2">
							<b>{{ isset($data['backendlang']['backendlang']['ProductSKU']) ? $data['backendlang']['backendlang']['ProductSKU'] :'' }}</b>
						</div>
						<div class="col-sm-10">
							<input type="text" name="product_code" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['ProductSKU']) ? $data['backendlang']['backendlang']['ProductSKU'] :'' }}" value="{{ isset($product) ? $product->product_code : old('product_code') }}">
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="row">
						<div class="col-sm-2">
							<b>{{ isset($data['backendlang']['backendlang']['Brand']) ? $data['backendlang']['backendlang']['Brand'] :'' }}: </b>
						</div>
						<div class="col-sm-10">
							<select class="form-control" name="brand_id">
								<option value>{{ isset($data['backendlang']['backendlang']['Select_Brand']) ? $data['backendlang']['backendlang']['Select_Brand'] :'' }}</option>
								@foreach($brands as $brand)
								<option {{ (isset($product) && $product->brand_id == $brand->id) ? 'selected': '' }} value="{{ $brand->id }}">
									{{ $brand->brand_name }}
								</option>
								@endforeach
							</select>
						</div>
					</div>
				</div>

				<div class="form-group">
					<div class="row">
						<div class="col-sm-2">
							<b>{{ (!empty(request('status')) && request('status') == '1') ? 'selected' : '' }} {{ isset($data['backendlang']['backendlang']['Type']) ? $data['backendlang']['backendlang']['Type'] :'' }}:</b>
						</div>
						<div class="col-sm-10">
							<select class="form-control" name="product_type">
								<option value>{{ isset($data['backendlang']['backendlang']['Select_Type']) ? $data['backendlang']['backendlang']['Select_Type'] :'' }}</option>
								@foreach($UOMs as $uom)
								<option {{ isset($product) && $product->product_type == $uom->id ? 'selected' : '' }} value="{{ $uom->id }}">
									{{ $uom->uom_name }}
								</option>
								@endforeach
							</select>
						</div>
					</div>
				</div>
			</div>

			<div class="container-box form-group">
				<h3>{{ isset($data['backendlang']['backendlang']['Fill_In_The_Price_(RM)']) ? $data['backendlang']['backendlang']['Fill_In_The_Price_(RM)'] :'' }}</h3>
				<hr>
				<input type="hidden" name="variation_enable" class="variation_enable" value="{{ isset($product) ? $product->variation_enable : '0' }}">
				<div class="non-variation-tab"
					style="{{ (!isset($product) || (isset($product) && $product->variation_enable == 0)) ? 'display: block;' : 'display: none;'  }}">
					<div class="form-group">
						<div class="row">
							<div class="col-sm-2">
								<b>{{ isset($data['backendlang']['backendlang']['Costing']) ? $data['backendlang']['backendlang']['Costing'] :'' }}{{ (!empty($mall) && $mall == '1') ? ($data['backendlang']['backendlang']['Point'] ?? '') : ($data['backendlang']['backendlang']['Price'] ?? '') }}: </b>
							</div>
							<div class="col-sm-10">
								<input type="text" name="costing_price" class="form-control" value="{{ isset($product) ? $product->costing_price : old('costing_price') }}" placeholder="{{ isset($data['backendlang']['backendlang']['Costing']) ? $data['backendlang']['backendlang']['Costing'] :'' }} {{ (!empty($mall) && $mall == '1') ? ($data['backendlang']['backendlang']['Point'] ?? '') : ($data['backendlang']['backendlang']['Price'] ?? '') }}" onkeypress="return isNumberKey(event)">
							</div>
						</div>
					</div>
					@if(Request::segment(2) != 'point_product_list')
					<div class="form-group">
						<div class="row">
							<div class="col-sm-2">
								<b> {{ isset($data['backendlang']['backendlang']['Get_Point']) ? $data['backendlang']['backendlang']['Get_Point'] :'' }}:</b>
							</div>
							<div class="col-sm-10">
								<input type="text" name="get_point" class="form-control" value="{{ isset($product) ? $product->get_point : old('get_point') }}" placeholder="{{ isset($data['backendlang']['backendlang']['Get_Point']) ? $data['backendlang']['backendlang']['Get_Point'] :'' }}" onkeypress="return isNumberKey(event)">
							</div>
						</div>
					</div>

					<div class="form-group">
						<h4 style="text-decoration: underline;">{{ isset($data['backendlang']['backendlang']['Retail']) ? $data['backendlang']['backendlang']['Retail'] :'' }}</h4>
						<div class="row">
							<div class="col-sm-6">
								<div class="row">
									<div class="col-sm-4">
										<b>{{ isset($data['backendlang']['backendlang']['Retail']) ? $data['backendlang']['backendlang']['Retail'] :'' }}{{ (!empty($mall) && $mall == '1') ? ($data['backendlang']['backendlang']['Point'] ?? '') : ($data['backendlang']['backendlang']['Price'] ?? '') }}:</b> <span class="important-text">*</span>
									</div>
									<div class="col-sm-8">
										<input type="text" class="form-control" name="retail_price" value="{{ isset($product) ? $product->retail_price : old('retail_price') }}" placeholder="{{ isset($data['backendlang']['backendlang']['Retail']) ? $data['backendlang']['backendlang']['Retail'] :'' }}{{ (!empty($mall) && $mall == '1') ? ($data['backendlang']['backendlang']['Point'] ?? '') : ($data['backendlang']['backendlang']['Price'] ?? '') }} *" onkeypress="return isNumberKey(event)">
									</div>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="row">
									<div class="col-sm-4">
										<b>{{ isset($data['backendlang']['backendlang']['Retail_Special']) ? $data['backendlang']['backendlang']['Retail_Special'] :'' }}{{ (!empty($mall) && $mall == '1') ? ($data['backendlang']['backendlang']['Point'] ?? '') : ($data['backendlang']['backendlang']['Price'] ?? '') }}:</b>
									</div>
									<div class="col-sm-8">
										<input type="text" class="form-control" name="retail_special_price" value="{{ isset($product) ? $product->retail_special_price : old('retail_special_price') }}" placeholder="{{ isset($data['backendlang']['backendlang']['Retail_Speial']) ? $data['backendlang']['backendlang']['Retail_Special'] :'' }}{{ (!empty($mall) && $mall == '1') ? ($data['backendlang']['backendlang']['Point'] ?? '') : ($data['backendlang']['backendlang']['Price'] ?? '') }}" onkeypress="return isNumberKey(event)">
									</div>
								</div>
							</div>
						</div>
					</div>
					@endif

					<div class="form-group">
						<h4 style="text-decoration: underline;">{{ isset($data['backendlang']['backendlang']['Retail']) ? $data['backendlang']['backendlang']['Retail'] :'' }}</h4>
						<div class="row">
							<div class="col-sm-6">
								<div class="row">
									<div class="col-sm-4">
										<b>{{ isset($data['backendlang']['backendlang']['Member']) ? $data['backendlang']['backendlang']['Member'] :'' }} {{ (!empty($mall) && $mall == '1') ? ($data['backendlang']['backendlang']['Point'] ?? '') : ($data['backendlang']['backendlang']['Price'] ?? '') }}:</b> <span class="important-text">*</span>
									</div>
									<div class="col-sm-8">
										<input type="text" class="form-control" name="price" value="{{ isset($product) ? $product->price : old('price') }}" placeholder="{{ (!empty($mall) && $mall == '1') ? ($data['backendlang']['backendlang']['Point'] ?? 'Point') : ($data['backendlang']['backendlang']['Price'] ?? 'Price') }} *" onkeypress="return isNumberKey(event)">
									</div>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="row">
									<div class="col-sm-4">
										<b>{{ isset($data['backendlang']['backendlang']['Member_Special']) ? $data['backendlang']['backendlang']['Member_Special'] :'' }} {{ (!empty($mall) && $mall == '1') ? ($data['backendlang']['backendlang']['Point'] ?? '') : ($data['backendlang']['backendlang']['Price'] ?? '') }}:</b>
									</div>
									<div class="col-sm-8">
										<input type="text" class="form-control" name="special_price" value="{{ isset($product) ? $product->special_price : old('special_price') }}" placeholder="{{ isset($data['backendlang']['backendlang']['Special']) ? $data['backendlang']['backendlang']['Special'] :'' }}{{ (!empty($mall) && $mall == '1') ? ($data['backendlang']['backendlang']['Point'] ?? '') : ($data['backendlang']['backendlang']['Price'] ?? '') }}" onkeypress="return isNumberKey(event)">
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="form-group">
						<div class="row">
							<div class="col-sm-6">
								<div class="row">
									<div class="col-sm-4">
										<b>{{ isset($data['backendlang']['backendlang']['Member_Birthday']) ? $data['backendlang']['backendlang']['Member_Birthday'] :'' }} {{ (!empty($mall) && $mall == '1') ? ($data['backendlang']['backendlang']['Point'] ?? '') : ($data['backendlang']['backendlang']['Price'] ?? '') }}:</b>
									</div>
									<div class="col-sm-8">
										<input type="text" class="form-control" name="birthday_price" value="{{ isset($product) ? $product->birthday_price : old('birthday_price') }}" placeholder="{{ (!empty($mall) && $mall == '1') ? ($data['backendlang']['backendlang']['Point'] ?? 'Point') : ($data['backendlang']['backendlang']['Price'] ?? 'Price') }} *" onkeypress="return isNumberKey(event)">
									</div>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="row">
									<div class="col-sm-4">
										<b>{{ isset($data['backendlang']['backendlang']['Member_Birthday_Special']) ? $data['backendlang']['backendlang']['Member_Birthday_Special'] :'' }} {{ (!empty($mall) && $mall == '1') ? ($data['backendlang']['backendlang']['Point'] ?? '') : ($data['backendlang']['backendlang']['Price'] ?? '') }}:</b>
									</div>
									<div class="col-sm-8">
										<input type="text" class="form-control" name="birthday_special_price" value="{{ isset($product) ? $product->birthday_special_price : old('birthday_special_price') }}" placeholder="{{ isset($data['backendlang']['backendlang']['Special']) ? $data['backendlang']['backendlang']['Special'] :'' }}{{ (!empty($mall) && $mall == '1') ? ($data['backendlang']['backendlang']['Point'] ?? '') : ($data['backendlang']['backendlang']['Price'] ?? '') }}" onkeypress="return isNumberKey(event)">
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						@foreach($agent_levels as $key => $agentlvl)
						@php
						$old_price = !empty(old('every_agent_price')[$key]) ? old('every_agent_price')[$key] : 0;
						$old_special_price = !empty(old('every_agent_special_price')[$key]) ? old('every_agent_special_price')[$key] : 0;

						$out_price = (isset($agent_prices[$agentlvl->id])) ? $agent_prices[$agentlvl->id] : $old_price;
						$out_special_price = (isset($agent_special_prices[$agentlvl->id])) ? $agent_special_prices[$agentlvl->id] : $old_special_price;

						$old_birthday_price = !empty(old('level_price')[$key]) ? old('level_price')[$key] : 0;
						$old_birthday_special_price = !empty(old('level_special_price')[$key]) ? old('level_special_price')[$key] : 0;

						$birthday_price = (isset($agent_birthday_price[$agentlvl->id])) ? $agent_birthday_price[$agentlvl->id] : $old_birthday_price;
						$birthday_special_price = (isset($agent_birthday_special_price[$agentlvl->id])) ? $agent_birthday_special_price[$agentlvl->id] : $old_birthday_special_price;

						$out_id = (isset($agent_prices_ids[$agentlvl->id])) ? $agent_prices_ids[$agentlvl->id] : '';
						@endphp
						<div class="col-sm-12">
							<h4 style="text-decoration: underline;">{{ $agentlvl->agent_lvl }}</h4>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<div class="row">
									<div class="col-sm-4">
										<b>{{ $agentlvl->agent_lvl }} {{ (!empty($mall) && $mall == '1') ? ($data['backendlang']['backendlang']['Point'] ?? 'Point') : ($data['backendlang']['backendlang']['Price'] ?? 'Price') }}:</b> <span class="important-text">*</span>
									</div>
									<div class="col-sm-8">
										<input type="text" class="form-control" name="every_agent_price[]" value="{{ $out_price }}" placeholder="{{ $agentlvl->agent_lvl }} {{ (!empty($mall) && $mall == '1') ? ($data['backendlang']['backendlang']['Point'] ?? 'Point') : ($data['backendlang']['backendlang']['Price'] ?? 'Price') }} *" onkeypress="return isNumberKey(event)">
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<div class="row">
									<div class="col-sm-4">
										<b> {{ $agentlvl->agent_lvl }} {{ isset($data['backendlang']['backendlang']['Special']) ? $data['backendlang']['backendlang']['Special'] :'' }} {{ (!empty($mall) && $mall == '1') ? ($data['backendlang']['backendlang']['Point'] ?? 'Point') : ($data['backendlang']['backendlang']['Price'] ?? 'Price') }}:</b>
									</div>
									<div class="col-sm-8">
										<input type="text" class="form-control" name="every_agent_special_price[]" value="{{ $out_special_price }}" placeholder="{{ $agentlvl->agent_lvl }} Special {{ (!empty($mall) && $mall == '1') ? 'Point' : 'Price' }}" onkeypress="return isNumberKey(event)">
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<div class="row">
									<div class="col-sm-4">
										<b>{{ $agentlvl->agent_lvl }} {{ isset($data['backendlang']['backendlang']['Birthday']) ? $data['backendlang']['backendlang']['Birthday'] :'' }} {{ (!empty($mall) && $mall == '1') ? ($data['backendlang']['backendlang']['Point'] ?? '') : ($data['backendlang']['backendlang']['Price'] ?? '') }}:</b>
									</div>
									<div class="col-sm-8">
										<input type="text" class="form-control" name="level_price[]" value="{{ $birthday_price }}" placeholder="{{ $agentlvl->agent_lvl }} {{ isset($data['backendlang']['backendlang']['Birthday']) ? $data['backendlang']['backendlang']['Birthday'] :'' }} {{ (!empty($mall) && $mall == '1') ? ($data['backendlang']['backendlang']['Point'] ?? 'Point') : ($data['backendlang']['backendlang']['Price'] ?? 'Price') }}  *" onkeypress="return isNumberKey(event)">
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<div class="row">
									<div class="col-sm-4">
										<b>{{ $agentlvl->agent_lvl }} {{ isset($data['backendlang']['backendlang']['Birthday_Special']) ? $data['backendlang']['backendlang']['Birthday_Special'] :'' }} {{ (!empty($mall) && $mall == '1') ? ($data['backendlang']['backendlang']['Point'] ?? '') : ($data['backendlang']['backendlang']['Price'] ?? '') }}:</b>
									</div>
									<div class="col-sm-8">
										<input type="text" class="form-control" name="level_special_price[]" value="{{ $birthday_special_price }}" placeholder="{{ $agentlvl->agent_lvl }} {{ isset($data['backendlang']['backendlang']['Birthday_Special']) ? $data['backendlang']['backendlang']['Birthday_Special'] :'' }} {{ (!empty($mall) && $mall == '1') ? 'Point' : 'Price' }}" onkeypress="return isNumberKey(event)">
									</div>
								</div>
							</div>
						</div>
						<input type="hidden" name="aid[]" value="{{ $agentlvl->id }}">
						<input type="hidden" name="price_id[]" value="{{ $out_id }}">
						@endforeach
					</div>

					<div class="form-group">
						<div class="row">
							<div class="col-sm-2">
								<b>{{ isset($data['backendlang']['backendlang']['Quantity']) ? $data['backendlang']['backendlang']['Quantity'] :'' }}:</b> <span class="important-text">*</span>
							</div>
							<div class="col-sm-10">
								@if(isset($product))
								{{ $stockBalance }}
								&nbsp;&nbsp;
								<a href="{{ route('stock', [$product->id]) }}" class="green">
									<i class="ace-icon bi bi-download bigger-130"></i>
								</a>
								@else
								<input type="text" class="form-control" name="quantity" value="{{ isset($product) ? $product->quantity : old('quantity') }}" placeholder="{{ isset($data['backendlang']['backendlang']['Quantity']) ? $data['backendlang']['backendlang']['Quantity'] :'' }} *" onkeypress="return isNumberKey(event)">
								@endif
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-sm-2">
								<b>{{ isset($data['backendlang']['backendlang']['Low_Stock_Threshold']) ? $data['backendlang']['backendlang']['Low_Stock_Threshold'] :'' }}: </b><span class="important-text">*</span>
							</div>
							<div class="col-sm-10">
								<input type="text" name="lsthreshold" class="form-control" value="{{ isset($product) ? $product->low_stock_threshold : old('low_stock_threshold') }}" placeholder="{{ isset($data['backendlang']['backendlang']['Low_Stock_Threshold']) ? $data['backendlang']['backendlang']['Low_Stock_Threshold'] :'' }} *" onkeypress="return isNumberKey(event)">
							</div>
						</div>
					</div>

					<div class="form-group">
						<div class="row">
							<div class="col-sm-2">
								<b>{{ isset($data['backendlang']['backendlang']['Weight_(kg)']) ? $data['backendlang']['backendlang']['Weight_(kg)'] :'' }}: </b>
							</div>
							<div class="col-sm-10">
								<input type="text" name="weight" class="form-control" value="{{ isset($product) ? $product->weight : old('weight') }}" placeholder="{{ isset($data['backendlang']['backendlang']['Weight_(kg)']) ? $data['backendlang']['backendlang']['Weight_(kg)'] :'' }}" onkeypress="return isNumberKey(event)">
							</div>
						</div>
					</div>
					<div class="form-group">
						<a href="#" class="btn btn-block btn-outline-primary add-variation">
							{{ isset($data['backendlang']['backendlang']['Add_Variation']) ? $data['backendlang']['backendlang']['Add_Variation'] :'' }}
						</a>
					</div>
				</div>
				<div class="variation-tab"
					style="{{ (isset($product) && $product->variation_enable == 1) ? 'display: block;' : 'display: none;'  }}">
					<div class="row">
						<div class="col-11">
							<h4 class="mb-0" style="display: inline-block;">{{ isset($data['backendlang']['backendlang']['Product_Variation']) ? $data['backendlang']['backendlang']['Product_Variation'] :'' }}
								@if(Request::segment(3) == 'edit')
								<a href="{{ route('edit_variation',$product->id) }}" class="redirect_variation_setting" title="{{ isset($data['backendlang']['backendlang']['Edit_Variatiion']) ? $data['backendlang']['backendlang']['Edit_Variation'] :'' }}">
									<i class="bi bi-gear-fill"></i>
								</a>
								@endif
							</h4>
						</div>
						<div class="col-1" align="center">
							<a href="#" class="delete-variation">
								<i class="bi bi-x"></i>
							</a>
						</div>
					</div>
					<hr>
			
					<div class="row" style="{{ Request::segment(3) == 'edit'?'display:none': '' }}">
						<div class="col-3">
							<div class="form-group" align="right">
								<b>{{ isset($data['backendlang']['backendlang']['Variation']) ? $data['backendlang']['backendlang']['Variation'] :'' }} 1</b>
							</div>
						</div>
						@php
						$v1_num = 0;
						$v2_num = 0;
						@endphp
						<div class="col-6">
							<div class="form-group">
								<input type="text" class="form-control" name="variation_title" placeholder="{{ isset($data['backendlang']['backendlang']['Name']) ? $data['backendlang']['backendlang']['Name'] :'' }}"
									value="{{ (isset($product)) ? $product->variation_title : old('variation_title') }}">
							</div>
							<div class="variation-parent-row">
								@if(isset($variations) && !$variations->isEmpty())
								@foreach($variations as $variation)
								<div class="form-group variation-child-row">
									<div class="row">
										<div class="col-10">
											<input type="text" class="form-control variation_option" name="variation_option[]" placeholder="{{ isset($data['backendlang']['backendlang']['Option']) ? $data['backendlang']['backendlang']['Option'] :'' }}" data-id="{{ $v1_num }}" value="{{ $variation->variation_name }}">
										</div>
										<div class="col-2" align="center">
											<a href="#" class="del-v1-btn" data-id="{{ $variation->id }}">
												<i class="bi bi-trash" style="font-size: 25px;"></i>
											</a>
										</div>
									</div>
									<input type="hidden" name="fvid[]" value="{{ $variation->id }}">
								</div>
								@php
								$v1_num++;
								@endphp
								@endforeach
								@else
								<div class="form-group variation-child-row">
									<input type="text" class="form-control variation_option" name="variation_option[]" placeholder="{{ isset($data['backendlang']['backendlang']['Option']) ? $data['backendlang']['backendlang']['Option'] :'' }}" data-id="{{ $v1_num }}">
								</div>
								@endif
							</div>
							<input type="hidden" name="total_v1_variation" class="total_v1_variation" value="{{ $v1_num }}">
							<div class="form-group">
								<a href="#" class="btn btn-outline-primary btn-block add-v1-option">
									<i class="bi bi-plus"></i> {{ isset($data['backendlang']['backendlang']['Add_Option']) ? $data['backendlang']['backendlang']['Add_Option'] :'' }}
								</a>
							</div>
						</div>
					</div>
					<br style="{{ Request::segment(3) == 'edit'?'display:none': '' }}">
					<div class="row" style="{{ Request::segment(3) == 'edit'?'display:none': '' }}">
						<div class="col-3">
							<div class="form-group" align="right">
								<b>{{ isset($data['backendlang']['backendlang']['Variation']) ? $data['backendlang']['backendlang']['Variation'] :'' }} 2</b>
							</div>
						</div>
						<div class="col-6">
							<div class="hide_variation_two_area">
								<a href="#" class="btn btn-outline-primary btn-block">
									<i class="bi bi-plus"></i> {{ isset($data['backendlang']['backendlang']['Add']) ? $data['backendlang']['backendlang']['Add'] :'' }}
								</a>
							</div>
							<input type="hidden" name="variation_two_enable" value='0'>
							<div class="variation_two_area" style="display: none;">
								<div class="form-group" align="right">
									<a href="#" class="close-variation-two">
										<i class="bi bi-x"></i>
									</a>
								</div>
								<div class="form-group">

									<input type="text" class="form-control" name="variation_two_title" placeholder="{{ isset($data['backendlang']['backendlang']['Name']) ? $data['backendlang']['backendlang']['Name'] :'' }}"
										value="{{ (isset($product)) ? $product->second_variation_title : old('variation_two_title') }}">
								</div>
								<div class="variation-parent-row">
									@if(isset($svs) && !$svs->isEmpty())
									@foreach($svs as $sv)
									<div class="form-group variation-child-row">
										<div class="row">
											<div class="col-10">
												<input type="text" class="form-control second_variation_option second_variation_option_{{ $v2_num }}" name="variation_two_option[]" placeholder="{{ isset($data['backendlang']['backendlang']['Option']) ? $data['backendlang']['backendlang']['Option'] :'' }}" data-id="{{ $v2_num }}" value="{{ $sv->variation_name }}">
											</div>
											<div class="col-2" align="center">
												<a href="#" class="del-v2-btn" data-id="{{ $sv->id }}" data-variation="{{ $sv->variation_id }}" data-name="{{ $sv->variation_name }}">
													<i class="bi bi-trash" style="font-size: 25px;"></i>
												</a>
											</div>
										</div>
									</div>
									@php
									$v2_num++;
									@endphp
									@endforeach
									@else
									<div class="form-group variation-child-row">
										<input type="text" class="form-control second_variation_option second_variation_option_{{ $v2_num }}" name="variation_two_option[]" placeholder="{{ isset($data['backendlang']['backendlang']['Option']) ? $data['backendlang']['backendlang']['Option'] :'' }}" data-id="{{ $v2_num }}">
									</div>
									@endif
								</div>
								<input type="hidden" name="total_v2_variation" class="total_v2_variation" value="{{ $v2_num }}">
								<div class="form-group">
									<a href="#" class="btn btn-outline-primary btn-block add-v2-option">
										<i class="bi bi-plus"></i> {{ isset($data['backendlang']['backendlang']['Add_Option']) ? $data['backendlang']['backendlang']['Add_Option'] :'' }}
									</a>
								</div>
							</div>
						</div>
					</div>
					
					<br>
					<div class="row ">
						<div class="col-2">
							<div class="form-group" align="right">
								<b> {{ isset($data['backendlang']['backendlang']['Variation_List']) ? $data['backendlang']['backendlang']['Variation_List'] :'' }}</b>
							</div>
						</div>
						<div class="col-10" style="overflow: auto;">
							<table class="table table-bordered variation-list-child-row table-responsive">
								<tr>
									<td class="variation_title">{{ (isset($product)) ? $product->variation_title : ($data['backendlang']['backendlang']['Name'] ?? 'Name') }}</td>
									<td class="variation_two variation_two_title" style="display: none;">
										{{ (isset($product)) ? $product->variation_title : ($data['backendlang']['backendlang']['Name'] ?? 'Name') }}
									</td>

									<!--<td>Get PV</td> -->

									@if(Request::segment(2) != 'point_product_list')
									<td>{{ isset($data['backendlang']['backendlang']['Retail_Price']) ? $data['backendlang']['backendlang']['Retail_Price'] :'' }}</td>
									<td>{{ isset($data['backendlang']['backendlang']['Special_Retail_Price']) ? $data['backendlang']['backendlang']['Special_Retail_Price'] :'' }}</td>
									@endif
									<td> {{ isset($data['backendlang']['backendlang']['Member']) ? $data['backendlang']['backendlang']['Member'] :'' }} {{ (!empty($mall) && $mall == '1') ? ($data['backendlang']['backendlang']['Point'] ?? '') : ($data['backendlang']['backendlang']['Price'] ?? '') }}</td>
									<td> {{ isset($data['backendlang']['backendlang']['Member_Special']) ? $data['backendlang']['backendlang']['Member_Special'] :'' }} {{ (!empty($mall) && $mall == '1') ? ($data['backendlang']['backendlang']['Point'] ?? '') : ($data['backendlang']['backendlang']['Price'] ?? '') }}</td>
									<td> {{ isset($data['backendlang']['backendlang']['Member_Birthday']) ? $data['backendlang']['backendlang']['Member_Birthday'] :'' }} {{ (!empty($mall) && $mall == '1') ? ($data['backendlang']['backendlang']['Point'] ?? '') : ($data['backendlang']['backendlang']['Price'] ?? '') }}</td>
									<td>{{ isset($data['backendlang']['backendlang']['Member_Birthday_Special']) ? $data['backendlang']['backendlang']['Member_Birthday_Special'] :'' }}{{ (!empty($mall) && $mall == '1') ? ($data['backendlang']['backendlang']['Point'] ?? '') : ($data['backendlang']['backendlang']['Price'] ?? '') }}</td>
									@foreach($agent_levels as $a_lvl)
									<td>{{ $a_lvl->agent_lvl }} {{ (!empty($mall) && $mall == '1') ? ($data['backendlang']['backendlang']['Point'] ?? '') : ($data['backendlang']['backendlang']['Price'] ?? '') }}</td>
									<td>{{ $a_lvl->agent_lvl }} {{ $data['backendlang']['backendlang']['Special'] ? $data['backendlang']['backendlang']['Special'] : '' }} {{ (!empty($mall) && $mall == '1') ? ($data['backendlang']['backendlang']['Point'] ?? '') : ($data['backendlang']['backendlang']['Price'] ?? '') }}</td>
									<td>{{ $a_lvl->agent_lvl }} {{ $data['backendlang']['backendlang']['Birthday'] ? $data['backendlang']['backendlang']['Birthday'] : '' }} {{ (!empty($mall) && $mall == '1') ? ($data['backendlang']['backendlang']['Point'] ?? '') : ($data['backendlang']['backendlang']['Price'] ?? '') }}</td>
									<td>{{ $a_lvl->agent_lvl }} {{ $data['backendlang']['backendlang']['Birthday_Special'] ? $data['backendlang']['backendlang']['Birthday_Special'] : '' }} {{ (!empty($mall) && $mall == '1') ? ($data['backendlang']['backendlang']['Point'] ?? '') : ($data['backendlang']['backendlang']['Price'] ?? '') }}</td>
									@endforeach
									<td> {{ isset($data['backendlang']['backendlang']['Costing']) ? $data['backendlang']['backendlang']['Costing'] :'' }} </td>
									@if(Request::segment(2) != 'point_product_list')
									<td> {{ isset($data['backendlang']['backendlang']['Get_Point']) ? $data['backendlang']['backendlang']['Get_Point'] :'' }} </td>
									@endif
									<td> {{ isset($data['backendlang']['backendlang']['Weight']) ? $data['backendlang']['backendlang']['Weight'] :'Weight' }} </td>
									<td> {{ isset($data['backendlang']['backendlang']['Stock']) ? $data['backendlang']['backendlang']['Stock'] :'' }} </td>

								</tr>
								@if(isset($variations) && !$variations->isEmpty())

								@if($product->second_variation_enable == 1)
								@php
								$lrow=0;
								@endphp
								@foreach($variations as $vkey => $varia)
								<tr data-id="0">
									<td class="variation_option_display_{{ $vkey }} first_variation" data-id="0" rowspan="{{ count($s_secnd_variations[$varia->id])+1 }}">
										{{ $varia->variation_name }}
										<br>
										<img src="{{ asset(!empty($varia->variation_image) ? $varia->variation_image : '') }}" class="img-thumbnail mb-2" width="70px">	
										<input type="file" name="variation_image_{{ $vkey }}[]" class="form-control">
									</td>
								</tr>
								@php
								$slrow=0;
								@endphp
								@foreach($s_secnd_variations[$varia->id] as $s_secnd_variation)
								<tr class="added-v2-option_{{ $slrow }} added" data-id="{{ $lrow }}">
									<td class="variation_option_two_display_{{ $slrow }} variation_two" style="display: none;">
										<input type="hidden" class="variation_option_two_value_{{ $slrow }}" name="variation_option_two_value_{{ $vkey }}[]" value="{{ $s_secnd_variation->variation_name }}">
										<input type="hidden" name="rid_{{ $lrow }}[]" value="{{ $s_secnd_variation->id }}">
										<span>{{ $s_secnd_variation->variation_name }}</span>
										<br>
										<img src="{{ asset(!empty($s_secnd_variation->variation_image) ? $s_secnd_variation->variation_image : '') }}" width="70px" class="img-thumbnail mb-2">
										<input type="file" name="variation_option_two_image_{{ $vkey }}[]" class="form-control" id="img" value="{{ $s_secnd_variation->variation_image }}">	
									</td>

									<!--<td><input type="text" name="get_pv_{{ $vkey }}[]" class="form-control" value="{{ $s_secnd_variation->variation_get_pv }}"></td> -->

									@if(Request::segment(2) != 'point_product_list')
									<td><input type="text" name="retail_price_{{ $vkey }}[]" class="form-control" value="{{ $s_secnd_variation->variation_retail_price }}"></td>
									<td><input type="text" name="retail_special_price_{{ $vkey }}[]" class="form-control" value="{{ $s_secnd_variation->variation_retail_special_price }}"></td>
									@endif
									<td><input type="text" name="customer_price_{{ $vkey }}[]" class="form-control" value="{{ $s_secnd_variation->variation_price }}"></td>
									<td><input type="text" name="customer_special_price_{{ $vkey }}[]" class="form-control" value="{{ $s_secnd_variation->variation_special_price }}"></td>
									<td><input type="text" class="form-control" name="birthday_price_{{ $vkey }}[]" value="{{$s_secnd_variation->variation_birthday_price}}"></td>
									<td><input type="text" class="form-control" name="birthday_special_price_{{ $vkey }}[]" value="{{$s_secnd_variation->variation_birthday_special_price}}"></td>
									@foreach($agent_levels as $key => $agentlvl)
									@php
									$in_price = (isset($agent_v2_prices[$agentlvl->id][$s_secnd_variation->variation_id][$s_secnd_variation->id])) ? $agent_v2_prices[$agentlvl->id][$s_secnd_variation->variation_id][$s_secnd_variation->id] : '';

									$in_special_price = (isset($agent_v2_special_prices[$agentlvl->id][$s_secnd_variation->variation_id][$s_secnd_variation->id])) ? $agent_v2_special_prices[$agentlvl->id][$s_secnd_variation->variation_id][$s_secnd_variation->id] : '';

									$in_ids = (isset($agent_prices_v2_ids[$agentlvl->id][$s_secnd_variation->variation_id][$s_secnd_variation->id])) ? $agent_prices_v2_ids[$agentlvl->id][$s_secnd_variation->variation_id][$s_secnd_variation->id] : '';

									$in_max_coins = (isset($agent_v2_max_coins[$agentlvl->id][$s_secnd_variation->variation_id][$s_secnd_variation->id])) ? $agent_v2_max_coins[$agentlvl->id][$s_secnd_variation->variation_id][$s_secnd_variation->id] : '';

									$in_sgd = (isset($agent_v2_sgd_price[$agentlvl->id][$s_secnd_variation->variation_id][$s_secnd_variation->id])) ? $agent_v2_sgd_price[$agentlvl->id][$s_secnd_variation->variation_id][$s_secnd_variation->id] : '';

									$in_special_sgd = (isset($agent_v2_sgd_special_price[$agentlvl->id][$s_secnd_variation->variation_id][$s_secnd_variation->id])) ? $agent_v2_sgd_special_price[$agentlvl->id][$s_secnd_variation->variation_id][$s_secnd_variation->id] : '';

									$birthday_price = (isset($agent_v2_birthday_price[$agentlvl->id][$s_secnd_variation->variation_id][$s_secnd_variation->id])) ? $agent_v2_birthday_price[$agentlvl->id][$s_secnd_variation->variation_id][$s_secnd_variation->id] : '';

									$birthday_special_price = (isset($agent_v2_birthday_special_price[$agentlvl->id][$s_secnd_variation->variation_id][$s_secnd_variation->id])) ? $agent_v2_birthday_special_price[$agentlvl->id][$s_secnd_variation->variation_id][$s_secnd_variation->id] : '';
									@endphp
									<td>
										<input type="text" name="agent_level_price_{{ $vkey }}_{{ $slrow }}[]" class="form-control" value="{{ $in_price }}">

										<input type="hidden" name="variation_agent_level_{{ $vkey }}_{{ $slrow }}[]" value="{{ $agentlvl->id }}" class="form-control">

										<input type="hidden" name="variation_agent_level_id_{{ $vkey }}_{{ $slrow }}[]" value="{{ $in_ids }}" class="form-control">
									</td>
									<td>
										<input type="text" name="agent_level_special_price_{{ $vkey }}_{{ $slrow }}[]" class="form-control" value="{{ $in_special_price }}">

									</td>
									<td>
										<input type="text" name="agent_level_birthday_price_{{ $vkey }}_{{ $slrow }}[]" class="form-control" value="{{ $birthday_price }}">
									</td>
									<td>
										<input type="text" name="agent_level_birthday_special_price_{{ $vkey }}_{{ $slrow }}[]" class="form-control" value="{{ $birthday_special_price }}">
									</td>
									@endforeach
									<td><input type="text" name="variation_costing_price_{{ $vkey }}[]" class="form-control" value="{{ $s_secnd_variation->variation_costing_price }}"></td>
									@if(Request::segment(2) != 'point_product_list')
									<td><input type="text" name="get_point_{{ $vkey }}[]" class="form-control" value="{{ $s_secnd_variation->variation_get_point }}"></td>
									@endif
									<td><input type="text" name="weight_{{ $vkey }}[]" class="form-control" value="{{ $s_secnd_variation->variation_weight }}"></td>
									<td>
										@if( $second_variation_stocks[$s_secnd_variation->variation_id][$s_secnd_variation->id] == 'new')
											<input type="text" name="stock_{{ $vkey }}[]" class="form-control">
										@else
											<input type="hidden" name="stock_{{ $vkey }}[]">
											<a href="{{ route('stock', [$product->id]) }}" class="green">
												{{ $second_variation_stocks[$s_secnd_variation->variation_id][$s_secnd_variation->id] }}
											</a>
										@endif
									</td>
								</tr>
								@php
								$slrow++;
								@endphp
								@endforeach
								@php
								$lrow++;
								@endphp
								@endforeach
								@else
								@php
								$lrow = 0;
								@endphp
								@foreach($variations as $varia)
								<tr data-id="{{ $lrow }}">
									<td class="variation_option_display_{{ $lrow }} first_variation" data-id="{{ $lrow }}">
										<input type="hidden" name="rid_{{ $lrow }}[]" value="">
										<span>{{ $varia->variation_name }}</span>
										<br>
										<img src="{{ asset(!empty($varia->variation_image) ? $varia->variation_image : '') }}" width="70px" class="img-thumbnail mb-2">
										<input type="file" name="variation_image_{{ $lrow }}[]" class="form-control">
									</td>
									<td class="variation_option_two_display_0 variation_two" style="display: none;">
										<input type="hidden" class="variation_option_two_value_0" name="variation_option_two_value_{{ $lrow }}[]">
										<span>{{ isset($data['backendlang']['backendlang']['Option']) ? $data['backendlang']['backendlang']['Option'] :'' }}</span>
										<br>
										<input type="file" name="variation_option_two_image_{{ $lrow }}[]" class="form-control">
									</td>

									<!--<td><input type="text" name="get_pv_{{ $lrow }}[]" class="form-control" value="{{ $varia->variation_get_pv }}"></td> -->
									@if(Request::segment(2) != 'point_product_list')
									<td><input type="text" name="retail_price_{{ $lrow }}[]" class="form-control" value="{{ $varia->variation_retail_price }}"></td>
									<td><input type="text" name="retail_special_price_{{ $lrow }}[]" class="form-control" value="{{ $varia->variation_retail_special_price }}"></td>
									@endif
									<td><input type="text" name="customer_price_{{ $lrow }}[]" class="form-control" value="{{ $varia->variation_price }}"></td>
									<td><input type="text" name="customer_special_price_{{ $lrow }}[]" class="form-control" value="{{ $varia->variation_special_price }}"></td>
									<td><input type="text" class="form-control" name="birthday_price_{{ $lrow }}[]" value="{{$varia->variation_birthday_price}}"></td>
									<td><input type="text" class="form-control" name="birthday_special_price_{{ $lrow }}[]" value="{{$varia->variation_birthday_special_price}}"></td>
									@foreach($agent_levels as $key => $agentlvl)

									@php
									$in_price = (isset($agent_v_prices[$agentlvl->id][$varia->id])) ? $agent_v_prices[$agentlvl->id][$varia->id] : '';

									$in_special_price = (isset($agent_v_special_prices[$agentlvl->id][$varia->id])) ? $agent_v_special_prices[$agentlvl->id][$varia->id] : '';

									$in_ids = (isset($agent_prices_v_ids[$agentlvl->id][$varia->id])) ? $agent_prices_v_ids[$agentlvl->id][$varia->id] : '';

									$in_max_coin = (isset($agent_v_max_coins[$agentlvl->id][$varia->id])) ? $agent_v_max_coins[$agentlvl->id][$varia->id] : '';

									$in_sgd_price = (isset($agent_v_sgd_price[$agentlvl->id][$varia->id])) ? $agent_v_sgd_price[$agentlvl->id][$varia->id] : '';

									$in_sgd_special_price = (isset($agent_v_sgd_special_price[$agentlvl->id][$varia->id])) ? $agent_v_sgd_special_price[$agentlvl->id][$varia->id] : '';

									$birthday_price = (isset($agent_v_birthday_prices[$agentlvl->id][$varia->id])) ? $agent_v_birthday_prices[$agentlvl->id][$varia->id] : '';

									$birthday_special_price = (isset($agent_v_birthday_special_prices[$agentlvl->id][$varia->id])) ? $agent_v_birthday_special_prices[$agentlvl->id][$varia->id] : '';
									@endphp
									<td>
										<input type="text" name="agent_level_price_{{ $lrow }}_0[]" class="form-control" value="{{ $in_price }}">

										<input type="hidden" name="variation_agent_level_{{ $lrow }}_0[]" value="{{ $agentlvl->id }}" class="form-control">

										<input type="hidden" name="variation_agent_level_id_{{ $lrow }}_0[]" value="{{ $in_ids }}" class="form-control">
									</td>
									<td>
										<input type="text" name="agent_level_special_price_{{ $lrow }}_0[]" class="form-control" value="{{ $in_special_price }}">
									</td>
									<td>
										<input type="text" name="agent_level_birthday_price_{{ $lrow }}_0[]" class="form-control" value="{{ $birthday_price }}">
									</td>
									<td>
										<input type="text" name="agent_level_birthday_special_price_{{ $lrow }}_0[]" class="form-control" value="{{ $birthday_special_price }}">
									</td>
									@endforeach
									<td><input type="text" name="variation_costing_price_{{ $lrow }}[]" class="form-control" value="{{ $varia->variation_costing_price }}"></td>
									@if(Request::segment(2) != 'point_product_list')
									<td><input type="text" name="get_point_{{ $lrow }}[]" class="form-control" value="{{ $varia->variation_get_point }}"></td>
									@endif
									<td><input type="text" name="weight_{{ $lrow }}[]" class="form-control" value="{{ $varia->variation_weight }}"></td>
									<td>
										@if($variation_stocks[$varia->id] == 'new')
											<input type="text" name="stock_{{ $lrow }}[]" class="form-control">
										@else
											<input type="hidden" name="stock_{{ $lrow }}[]">
											<a href="{{ route('stock', [$product->id]) }}" class="green">
												{{ $variation_stocks[$varia->id] }}
											</a>
										@endif
									</td>
								</tr>
								@php
								$lrow++;
								@endphp
								@endforeach
								@endif
								@else
								@if(Request::segment(3) != 'edit')
								<tr data-id="0">
									<td class="variation_option_display_0 first_variation" data-id="0">
										<input type="hidden" name="rid_0[]" value="">
										<span>{{ isset($data['backendlang']['backendlang']['Option']) ? $data['backendlang']['backendlang']['Option'] :'' }}</span>
										<br>
										<input type="file" name="variation_image_0[]" class="form-control">
									</td>
									<td class="variation_option_two_display_0 variation_two" style="display: none;">
										<input type="hidden" class="variation_option_two_value_0" name="variation_option_two_value_0[]">
										<span>{{ isset($data['backendlang']['backendlang']['Option']) ? $data['backendlang']['backendlang']['Option'] :'' }}</span>
										<br>
										<input type="file" name="variation_option_two_image_0[]" class="form-control">
									</td>

									@if(Request::segment(2) != 'point_product_list')
									<td><input type="text" name="retail_price_0[]" class="form-control"></td>
									<td><input type="text" name="retail_special_price_0[]" class="form-control"></td>
									@endif
									<td><input type="text" name="customer_price_0[]" class="form-control"></td>
									<td><input type="text" name="customer_special_price_0[]" class="form-control"></td>
									<td><input type="text" class="form-control" name="birthday_price_0[]"></td>
									<td><input type="text" class="form-control" name="birthday_special_price_0[]"></td>
									@foreach($agent_levels as $key => $agentlvl)
									<td>
										<input type="text" name="agent_level_price_0_0[]" class="form-control">
										<input type="hidden" name="variation_agent_level_0_0[]" value="{{ $agentlvl->id }}" class="form-control">
										<input type="hidden" name="variation_agent_level_id_0_0[]" value="" class="form-control">
									</td>
									<td>
										<input type="text" name="agent_level_special_price_0_0[]" class="form-control" value="">
									</td>
									<td>
										<input type="text" name="agent_level_birthday_price_0_0[]" class="form-control" value="">
									</td>
									<td>
										<input type="text" name="agent_level_birthday_special_price_0_0[]" class="form-control" value="">
									</td>
									@endforeach
									<td><input type="text" name="variation_costing_price_0[]" class="form-control"></td>
									@if(Request::segment(2) != 'point_product_list')
									<td><input type="text" name="get_point_0[]" class="form-control"></td>
									@endif
									<td><input type="text" name="weight_0[]" class="form-control"></td>
									<td><input type="text" name="stock_0[]" class="form-control"></td>
								</tr>
								@endif
								@endif
							</table>
						</div>
					</div>
				</div>
			</div>

			<div class="container-box form-group">
				<h3> {{ isset($data['backendlang']['backendlang']['Description']) ? $data['backendlang']['backendlang']['Description'] :'' }}</h3>
				<hr>
				<div class="form-group">
					<div class="row">
						<div class="col-sm-2">
							<b> {{ isset($data['backendlang']['backendlang']['Description']) ? $data['backendlang']['backendlang']['Description'] :'' }}:</b>
						</div>
						<div class="col-sm-10">
							<textarea class="form-control" name="description" id="description">{!! isset($product) ? $product->description : old('description') !!}</textarea>
						</div>
					</div>
				</div>

				<div class="form-group">
					<div class="row">
						<div class="col-sm-2">
							<b> {{ isset($data['backendlang']['backendlang']['Testimonial']) ? $data['backendlang']['backendlang']['Testimonial'] :'' }}:</b>
						</div>
						<div class="col-sm-10">
							<textarea class="form-control" name="testimonial" id="testimonial">{!! isset($product) ? $product->testimonial : old('testimonial') !!}</textarea>
						</div>
					</div>
				</div>

				<div class="form-group">
					<div class="row">
						<div class="col-sm-2">
							<b> {{ isset($data['backendlang']['backendlang']['Short_Description']) ? $data['backendlang']['backendlang']['Short_Description'] :'' }}:</b>
						</div>
						<div class="col-sm-10">
							<input type="text" class="form-control" name="short_description" value="{{ isset($product) ? $product->short_description : old('short_description') }}" placeholder="{{ isset($data['backendlang']['backendlang']['Short_Description']) ? $data['backendlang']['backendlang']['Short_Description'] :'' }}">
						</div>
					</div>
				</div>
			</div>
		</form>
		<div class="container-box form-group">
			<h3>{{ isset($data['backendlang']['backendlang']['Image']) ? $data['backendlang']['backendlang']['Image'] :'' }}</h3>
			<hr>
			<div class="row">
				<div class="col-sm-2">
					<b>{{ isset($data['backendlang']['backendlang']['Image']) ? $data['backendlang']['backendlang']['Image'] :'' }}: </b>
				</div>
				<div class="col-sm-10">
					<div class="form-group product-image-list">
						<div class="row" id="imageListId">

						</div>
						<div class="clear-both"></div>
					</div>
					<div class="form-group">
						<form method="POST" action="" class="asdasd" id="upload_image_form" enctype="multipart/form-data">
							<!--	<input type="file" name="upload_image" id="upload_image" class="form-control" />
							<br />-->
							<div id="uploaded_image"></div>
						</form>
					</div>
					<div>
						<form method="POST" action="{{ route('uploadImage', isset($product->id) ? $product->id : 0) }}" class="dropzone well" id="dropzone" enctype="multipart/form-data">
							@csrf
							<div class="fallback">
								<input name="file" type="file" multiple="" accept="image/*" />
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="uploadimageModal" class="modal bs-example-modal-lg" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">{{ isset($data['backendlang']['backendlang']['Upload_&_Crop_Image']) ? $data['backendlang']['backendlang']['Upload_&_Crop_Image'] :'' }}</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12 text-center">
						<center>
							<div id="image_demo" style="width: 100%; margin-top:30px"></div>
						</center>
					</div>
					<div class="form-group" align="center">
						<button class="btn btn-success crop_image">{{ isset($data['backendlang']['backendlang']['Crop_&_Upload_Image']) ? $data['backendlang']['backendlang']['Crop_&_Upload_Image'] :'' }}</button>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-danger" data-dismiss="modal">{{ isset($data['backendlang']['backendlang']['Close']) ? $data['backendlang']['backendlang']['Close'] :'' }}</button>
			</div>
		</div>
	</div>
</div>