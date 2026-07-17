@php
if(isset($product)){
	$action_url = route('packages_edit_save', $product->id);
}else{
	$action_url = route('packages_add');
}

@endphp
<div class="row">
	<div class="col-12">
		<form method="POST" action="{{ $action_url }}" id="product-form">
			@csrf

			<div class="form-group container-box">
				<h4>{{ isset($data['backendlang']['backendlang']['Fill_In_Product_Information']) ? $data['backendlang']['backendlang']['Fill_In_Product_Information'] :''}}</h4>
				<hr>

				<div class="form-group">
					<div class="row">
						<div class="col-sm-2">

						</div>
						<div class="col-md-5">
							<div class="checkbox">
								<label>
									<input name="featured" type="checkbox" class="ace" {{ isset($product) && $product->featured == '1' ? 'checked' : '' }} />
									<span class="lbl"> {{ isset($data['backendlang']['backendlang']['Featured_Product']) ? $data['backendlang']['backendlang']['Featured_Product'] :''}}</span>
								</label>
							</div>
						</div>
						<div class="col-md-5">
							<div class="checkbox">
								<label>
									<input name="dow" type="checkbox" class="ace" {{ (isset($product) && $product->dow == '1' || !isset($product)) ? 'checked' : '' }} />
									<span class="lbl"> {{ isset($data['backendlang']['backendlang']['Display_On_Website']) ? $data['backendlang']['backendlang']['Display_On_Website'] :''}}</span>
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
									<span class="lbl"> {{ isset($data['backendlang']['backendlang']['Free_West_Shipping']) ? $data['backendlang']['backendlang']['Free_West_Shipping'] :''}}</span>
								</label>
							</div>
						</div>
						<div class="col-md-5">
							<div class="checkbox">
								<label>
									<input name="free_east_shipping" type="checkbox" class="ace" {{ isset($product) && $product->free_east_shipping == '1' ? 'checked' : '' }} />
									<span class="lbl"> {{ isset($data['backendlang']['backendlang']['Free_East_Shipping']) ? $data['backendlang']['backendlang']['Free_East_Shipping'] :''}}</span>
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
									<span class="lbl"> {{ isset($data['backendlang']['backendlang']['Agent_Only']) ? $data['backendlang']['backendlang']['Agent_Only'] :''}}</span>
								</label>
							</div>
						</div>
						<div class="col-md-5">
							<div class="checkbox">
								<label>
									<input name="customer_only" type="checkbox" class="ace" {{ (isset($product) && $product->customer_only == '1' || !isset($product)) ? 'checked' : '' }} />
									<span class="lbl"> {{ isset($data['backendlang']['backendlang']['Customer_Only']) ? $data['backendlang']['backendlang']['Customer_Only'] :''}}</span>
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
									<span class="lbl">{{ isset($data['backendlang']['backendlang']['Store_Stock']) ? $data['backendlang']['backendlang']['Store_Stock'] :''}}</span>
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
							@php
							$disabled_register_product = ($data['web_setting']->registration_product_enable == 1) ? '' : 'disabled';
							@endphp
							<div class="checkbox">
								<label>
									<input name="register_product" type="checkbox" class="ace" {{ isset($product) && $product->register_product == '1' ? 'checked' : '' }} {{ $disabled_register_product }} />
									<span class="lbl"> {{ isset($data['backendlang']['backendlang']['Register_Product']) ? $data['backendlang']['backendlang']['Register_Product'] :'' }}</span>
								</label>
								@if($disabled_register_product == 'disabled')
								<br>
								<span class="important-text">
									{{ isset($data['backendlang']['backendlang']['Disabled_When']) ? $data['backendlang']['backendlang']['Disabled_When'] :'' }} <b>{{ isset($data['backendlang']['backendlang']['Settings_Flow_Setting']) ? $data['backendlang']['backendlang']['Settings_Flow_Setting'] :'' }}</b> {{ isset($data['backendlang']['backendlang']['Has_Been_Deactivated']) ? $data['backendlang']['backendlang']['Has_Been_Deactivated'] :'' }}
								</span>
								@endif
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
							<b>{{ isset($data['backendlang']['backendlang']['Upgrade']) ? $data['backendlang']['backendlang']['Upgrade'] :'' }}</b>
						</div>
						<div class="col-md-10">
							@php
							 	$selectedLevel = (isset($product)) ? $product->level_up : old('level_up');
							@endphp
							<select class="form-control" name="level_up">
								<option>{{ isset($data['backendlang']['backendlang']['Select_Level']) ? $data['backendlang']['backendlang']['Select_Level'] :'' }}</option>
								@foreach($agent_levels as $agent_level)
								<option {{ ($selectedLevel == $agent_level->id) ? 'selected' : '' }} value="{{ $agent_level->id }}">{{ $agent_level->agent_lvl }}</option>
								@endforeach
							</select>
						</div>
					</div>
				</div>

				<div class="form-group">
					<div class="row">
						<div class="col-sm-2">
							<b>{{ isset($data['backendlang']['backendlang']['Name']) ? $data['backendlang']['backendlang']['Name'] :'' }}:</b> <span class="important-text">*</span>
						</div>
						<div class="col-sm-10">
							<input type="text" class="form-control" name="product_name" value="{{ isset($product) ? $product->product_name : old('product_name') }}" placeholder='{{ isset($data['backendlang']['backendlang']['Name']) ? $data['backendlang']['backendlang']['Name'] :'' }}'>
						</div>
					</div>
				</div>

				<div class="form-group">
				<div class="row">
					<div class="col-sm-2">
						<b>{{ isset($data['backendlang']['backendlang']['Category']) ? $data['backendlang']['backendlang']['Category'] :'' }}:</b> <span class="important-text">*</span>
					</div>
					<div class="col-sm-6">
						<select class="form-control category_id" name="category_id">
							<option value="">{{ isset($data['backendlang']['backendlang']['Select_Category']) ? $data['backendlang']['backendlang']['Select_Category'] :'' }}</option>
							@foreach($categories as $category)
							<option {{ (isset($product) && $product->category_id == $category->id) ? 'selected': '' }} value="{{ $category->id }}">
								{{ $category->category_name }}
							</option>
							@endforeach
						</select>
					</div>
					<div class="col-sm-4 item_code">
						@if(isset($product) && empty($product->sub_category_id))
						{{ isset($product) ? (isset($data['backendlang']['backendlang']['Item_Code']) ? $data['backendlang']['backendlang']['Item_Code'] : 'Item Code') . ': ' . $product->item_code : '' }}
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
						{{ isset($product) ? (isset($data['backendlang']['backendlang']['Item_Code']) ? $data['backendlang']['backendlang']['Item_Code'] : 'Item Code') . ': ' . $product->item_code : '' }}
						@endif
					</div>
				</div>
			</div>
			<input type="hidden" name="item_code" class="hidden_item_code" value="{{ isset($product) ? $product->item_code: '' }}">
				<div class="form-group">
					<div class="row">
						<div class="col-sm-2">
							<b>{{ isset($data['backendlang']['backendlang']['Brand']) ? $data['backendlang']['backendlang']['Brand'] :'' }}:</b>
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
							<b>{{ isset($data['backendlang']['backendlang']['Type']) ? $data['backendlang']['backendlang']['Type'] :'' }}:</b>
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
			<div class="form-group container-box">
				<h4>{{ isset($data['backendlang']['backendlang']['Fill_In_The_Price_(RM)']) ? $data['backendlang']['backendlang']['Fill_In_The_Price_(RM)'] :'' }} </h4>
				<hr>
				<div class="form-group">
					<div class="row">
						<div class="col-sm-2">
							<b>{{ isset($data['backendlang']['backendlang']['Costing_Price']) ? $data['backendlang']['backendlang']['Costing_Price'] :'' }}: </b> 
						</div>
						<div class="col-sm-10">
							<input type="text" name="costing_price" class="form-control" value="{{ isset($product) ? $product->costing_price : old('costing_price') }}" placeholder="{{ isset($data['backendlang']['backendlang']['Costing']) ? $data['backendlang']['backendlang']['Costing'] :'' }} {{ (!empty($mall) && $mall == '1') ? ($data['backendlang']['backendlang']['Point'] ?? 'Point') : ($data['backendlang']['backendlang']['Price'] ?? 'Price') }}" onkeypress="return isNumberKey(event)">
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="row">
						<div class="col-sm-2">
						<b>	{{ isset($data['backendlang']['backendlang']['Get_Point']) ? $data['backendlang']['backendlang']['Get_Point'] :'' }}:</b>  
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
								<b>	{{ isset($data['backendlang']['backendlang']['Retail']) ? $data['backendlang']['backendlang']['Retail'] :'' }} {{ (!empty($mall) && $mall == '1') ? ($data['backendlang']['backendlang']['Point'] ?? 'Point') : ($data['backendlang']['backendlang']['Price'] ?? 'Price') }}:</b> <span class="important-text">*</span>
								</div>
								<div class="col-sm-8">
									<input type="text" class="form-control" name="retail_price" value="{{ isset($product) ? $product->retail_price : old('retail_price') }}" placeholder="{{ isset($data['backendlang']['backendlang']['Retail']) ? $data['backendlang']['backendlang']['Retail'] :'' }} {{ (!empty($mall) && $mall == '1') ? ($data['backendlang']['backendlang']['Point'] ?? 'Point') : ($data['backendlang']['backendlang']['Price'] ?? 'Price') }} *" onkeypress="return isNumberKey(event)">
								</div>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="row">
								<div class="col-sm-4">
									<b>{{ isset($data['backendlang']['backendlang']['Retail_Special']) ? $data['backendlang']['backendlang']['Retail_Special'] :'' }} {{ (!empty($mall) && $mall == '1') ? ($data['backendlang']['backendlang']['Point'] ?? 'Point') : ($data['backendlang']['backendlang']['Price'] ?? 'Price') }}:</b>
								</div>
								<div class="col-sm-8">
									<input type="text" class="form-control" name="retail_special_price" value="{{ isset($product) ? $product->retail_special_price : old('retail_special_price') }}" placeholder="{{ isset($data['backendlang']['backendlang']['Retail_Special']) ? $data['backendlang']['backendlang']['Retail_Special'] :'' }} {{ (!empty($mall) && $mall == '1') ? ($data['backendlang']['backendlang']['Point'] ?? 'Point') : ($data['backendlang']['backendlang']['Price'] ?? 'Price') }}" onkeypress="return isNumberKey(event)">
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="form-group">
					<h4 style="text-decoration: underline;">{{ isset($data['backendlang']['backendlang']['Member']) ? $data['backendlang']['backendlang']['Member'] :'' }}</h4>
					<div class="row">
						<div class="col-sm-6">
							<div class="row">
								<div class="col-sm-4">
								<b>	{{ isset($data['backendlang']['backendlang']['Member']) ? $data['backendlang']['backendlang']['Member'] :'' }} {{ (!empty($mall) && $mall == '1') ? ($data['backendlang']['backendlang']['Point'] ?? 'Point') : ($data['backendlang']['backendlang']['Price'] ?? 'Price') }}:</b> <span class="important-text">*</span>
								</div>
								<div class="col-sm-8">
									<input type="text" class="form-control" name="price" value="{{ isset($product) ? $product->price : old('price') }}" placeholder="{{ isset($data['backendlang']['backendlang']['Member']) ? $data['backendlang']['backendlang']['Member'] :'' }} {{ (!empty($mall) && $mall == '1') ? ($data['backendlang']['backendlang']['Point'] ?? 'Point') : ($data['backendlang']['backendlang']['Price'] ?? 'Price') }} *" onkeypress="return isNumberKey(event)">
								</div>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="row">
								<div class="col-sm-4">
									<b>{{ isset($data['backendlang']['backendlang']['Member_Special']) ? $data['backendlang']['backendlang']['Member_Special'] :'' }} {{ (!empty($mall) && $mall == '1') ? ($data['backendlang']['backendlang']['Point'] ?? 'Point') : ($data['backendlang']['backendlang']['Price'] ?? 'Price') }}:</b>
								</div>
								<div class="col-sm-8">
									<input type="text" class="form-control" name="special_price" value="{{ isset($product) ? $product->special_price : old('special_price') }}" placeholder="{{ isset($data['backendlang']['backendlang']['Member_Special']) ? $data['backendlang']['backendlang']['Member_Special'] :'' }} {{ (!empty($mall) && $mall == '1') ? ($data['backendlang']['backendlang']['Point'] ?? 'Point') : ($data['backendlang']['backendlang']['Price'] ?? 'Price') }}" onkeypress="return isNumberKey(event)">
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
									<b>{{ isset($data['backendlang']['backendlang']['Member_Birthday']) ? $data['backendlang']['backendlang']['Member_Birthday'] :'' }} {{ (!empty($mall) && $mall == '1') ? ($data['backendlang']['backendlang']['Point'] ?? 'Point') : ($data['backendlang']['backendlang']['Price'] ?? 'Price') }}:</b>
								</div>
								<div class="col-sm-8">
									<input type="text" class="form-control" name="birthday_price" value="{{ isset($product) ? $product->birthday_price : old('birthday_price') }}" placeholder="{{ isset($data['backendlang']['backendlang']['Member_Birthday']) ? $data['backendlang']['backendlang']['Member_Birthday'] :'' }} {{ (!empty($mall) && $mall == '1') ? ($data['backendlang']['backendlang']['Point'] ?? 'Point') : ($data['backendlang']['backendlang']['Price'] ?? 'Price') }} *" onkeypress="return isNumberKey(event)">
								</div>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="row">
								<div class="col-sm-4">
									<b>{{ isset($data['backendlang']['backendlang']['Member_Birthday_Special']) ? $data['backendlang']['backendlang']['Member_Birthday_Special'] :'' }} {{ (!empty($mall) && $mall == '1') ? ($data['backendlang']['backendlang']['Point'] ?? 'Point') : ($data['backendlang']['backendlang']['Price'] ?? 'Price') }}:</b>
								</div>
								<div class="col-sm-8">
									<input type="text" class="form-control" name="birthday_special_price" value="{{ isset($product) ? $product->birthday_special_price : old('birthday_special_price') }}" placeholder="{{ isset($data['backendlang']['backendlang']['Member_Birthday_Special']) ? $data['backendlang']['backendlang']['Member_Birthday_Special'] :'' }} {{ (!empty($mall) && $mall == '1') ? ($data['backendlang']['backendlang']['Point'] ?? 'Point') : ($data['backendlang']['backendlang']['Price'] ?? 'Price') }}" onkeypress="return isNumberKey(event)">
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
									<b>	{{ $agentlvl->agent_lvl }} {{ isset($data['backendlang']['backendlang']['Special']) ? $data['backendlang']['backendlang']['Special'] :'' }} {{ (!empty($mall) && $mall == '1') ? ($data['backendlang']['backendlang']['Point'] ?? 'Point') : ($data['backendlang']['backendlang']['Price'] ?? 'Price') }}:</b>
									</div>
									<div class="col-sm-8">
										<input type="text" class="form-control" name="every_agent_special_price[]" value="{{ $out_special_price }}" placeholder="{{ $agentlvl->agent_lvl }} {{ isset($data['backendlang']['backendlang']['Special']) ? $data['backendlang']['backendlang']['Special'] :'' }} {{ (!empty($mall) && $mall == '1') ? ($data['backendlang']['backendlang']['Point'] ?? 'Point') : ($data['backendlang']['backendlang']['Price'] ?? 'Price') }}" onkeypress="return isNumberKey(event)">
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<div class="row">
									<div class="col-sm-4">
										<b>{{ $agentlvl->agent_lvl }} {{ isset($data['backendlang']['backendlang']['Birthday']) ? $data['backendlang']['backendlang']['Birthday'] :'' }} {{ (!empty($mall) && $mall == '1') ? ($data['backendlang']['backendlang']['Point'] ?? 'Point') : ($data['backendlang']['backendlang']['Price'] ?? 'Price') }}:</b>
									</div>
									<div class="col-sm-8">
										<input type="text" class="form-control" name="level_price[]" value="{{ $birthday_price }}" placeholder="{{ $agentlvl->agent_lvl }} {{ isset($data['backendlang']['backendlang']['Birthday']) ? $data['backendlang']['backendlang']['Birthday'] :'' }} {{ (!empty($mall) && $mall == '1') ? ($data['backendlang']['backendlang']['Point'] ?? 'Point') : ($data['backendlang']['backendlang']['Price'] ?? 'Price') }} *" onkeypress="return isNumberKey(event)">
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<div class="row">
									<div class="col-sm-4">
										<b>{{ $agentlvl->agent_lvl }} {{ isset($data['backendlang']['backendlang']['Birthday_Special']) ? $data['backendlang']['backendlang']['Birthday_Special'] :'' }} {{ (!empty($mall) && $mall == '1') ? ($data['backendlang']['backendlang']['Point'] ?? 'Point') : ($data['backendlang']['backendlang']['Price'] ?? 'Price') }}:</b>
									</div>
									<div class="col-sm-8">
										<input type="text" class="form-control" name="level_special_price[]" value="{{ $birthday_special_price }}" placeholder="{{ $agentlvl->agent_lvl }} {{ isset($data['backendlang']['backendlang']['Birthday_Special']) ? $data['backendlang']['backendlang']['Birthday_Special'] :'' }} {{ (!empty($mall) && $mall == '1') ? ($data['backendlang']['backendlang']['Point'] ?? 'Point') : ($data['backendlang']['backendlang']['Price'] ?? 'Price') }}" onkeypress="return isNumberKey(event)">
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
									<i class="ace-icon bi bi-upload bigger-130"></i>
								</a>
							@else
								<input type="text" class="form-control" name="quantity" value="{{ isset($product) ? $product->quantity : old('quantity') }}" placeholder="{{ isset($data['backendlang']['backendlang']['Quantity']) ? $data['backendlang']['backendlang']['Quantity'] :'' }} *" onkeypress="return isNumberKey(event)">
							@endif
						</div>
					</div>
				</div>
			</div>

			<div class="form-group container-box">
				<h4>{{ isset($data['backendlang']['backendlang']['Fill_In_Quantity_And_Weight']) ? $data['backendlang']['backendlang']['Fill_In_Quantity_And_Weight'] :'' }}</h4>
				<hr>
				<div class="form-group">
					<div class="row">
						<div class="col-sm-2">
							<b>{{ isset($data['backendlang']['backendlang']['Weight_(kg)']) ? $data['backendlang']['backendlang']['Weight_(kg)'] :'' }}:</b> <span class="important-text">*</span>
						</div>
						<div class="col-sm-10">
							<input type="text" class="form-control" name="weight" value="{{ isset($product) ? $product->weight : old('weight') }}" placeholder="{{ isset($data['backendlang']['backendlang']['Weight_(kg)']) ? $data['backendlang']['backendlang']['Weight_(kg)'] :'' }}" onkeypress="return isNumberKey(event)">
						</div>
					</div>
				</div>
			</div>

			<div class="form-group container-box">
				<h4>{{ isset($data['backendlang']['backendlang']['The_Package_Contains_Items']) ? $data['backendlang']['backendlang']['The_Package_Contains_Items'] :'' }}</h4>
				<hr>
				<div class="parent-box">
					<div class="form-group">
						@php
							$totalItems = 0;
						@endphp
						@if(isset($packages) && !$packages->isEmpty())
							@foreach($packages as $package)
								<input type="hidden" name="pid[]" value="{{ $package->id }}">
								<div class="row row-parent-box">
									<div class="col-md-2">
										<div class="form-group">
											<select class="form-control products" name="products[]" data-filter="{{ $totalItems }}">
												<option value="">{{ isset($data['backendlang']['backendlang']['Select_Product']) ? $data['backendlang']['backendlang']['Select_Product'] :'' }}</option>
												@foreach($products as $product_s)
												@if($product_s->packages != 1)
												<option {{ ($package->products == $product_s->id) ? 'selected' : '' }}  value="{{ $product_s->id }}">{{ $product_s->product_name }}</option>
												@endif
												@endforeach
											</select>
										</div>
										<div class="form-group option-list">
											@if(!empty($package->variation_id))
												<select class="form-control variation_option" name="variation_option{{ $totalItems }}">
													@foreach($options[$package->products] as $option)
														<option {{ $package->variation_id == $option->id ? 'selected' : '' }} value="{{ $option->id }}">
															{{ $option->variation_name }}
														</option>
													@endforeach
												</select>
											@endif
										</div>
										<div class="form-group second-option-list">
											@if(!empty($package->second_variation_id))
												<select class="form-control second_variation_option" name="second_variation_option{{ $totalItems }}">
													@foreach($second_options[$package->variation_id] as $second_option)
														<option {{ $package->variation_id == $second_option->id ? 'selected' : '' }} value="{{ $second_option->id }}">
															{{ $second_option->variation_name }}
														</option>
													@endforeach
												</select>
											@endif
										</div>

									</div>
									<div class="col-md-2">
										<input type="input" name="qty[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['Quantity']) ? $data['backendlang']['backendlang']['Quantity'] :'' }}" value="{{ $package->qty }}" onkeypress="return isNumberKey(event)">	
									</div>
									<div class="col-md-2">
										<input type="input" name="unit_price[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['Total_Cost']) ? $data['backendlang']['backendlang']['Total_Cost'] :'' }} (RM)" onkeypress="return isNumberKey(event)" value="{{ $package->unit_price }}">
									</div>
									<div class="col-md-2">
										<a href="#" class="important-text del-packages" data-id="{{ $package->id }}">
											<i class="bi bi-trash fa-2x"></i>
										</a>
									</div>
								</div>
								<br>
							@php
								$totalItems++;
							@endphp
							@endforeach
						@else
							<input type="hidden" name="pid[]" value="">
							<div class="row row-parent-box">
								<div class="col-md-2">
									<div class="form-group">
										<select class="form-control products" name="products[]" data-filter="{{ $totalItems }}">
											<option value="">{{ isset($data['backendlang']['backendlang']['Select_Product']) ? $data['backendlang']['backendlang']['Select_Product'] :'' }}</option>
											@foreach($products as $product_s)
											@if($product_s->packages != 1)
											<option value="{{ $product_s->id }}">{{ $product_s->product_name }} </option>
											@endif
											@endforeach
										</select>
									</div>
									<div class="form-group option-list">
											
									</div>
									<div class="form-group second-option-list">
									</div>
								</div>
								<div class="col-md-2">
									<input type="input" name="qty[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['Quantity']) ? $data['backendlang']['backendlang']['Quantity'] :'' }}" onkeypress="return isNumberKey(event)">	
								</div>
								<div class="col-md-2">
									<input type="input" name="unit_price[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['Total_Cost']) ? $data['backendlang']['backendlang']['Total_Cost'] :'' }} (RM)" onkeypress="return isNumberKey(event)">
								</div>
								<div class="col-md-2">
									<a href="#" class="important-text del-packages">
										<i class="bi bi-trash fa-2x"></i>
									</a>
								</div>
							</div>
						@endif
					</div>
					<input type="hidden" class="totalItems" value="{{ $totalItems }}">
				</div>

				<hr>
				<div class="form-group">
					<div class="row">
						<div class="col-md-6" align="center">
							<a href='#' class="btn btn-outline-primary btn-sm add-shipping-btn">
								<i class="bi bi-plus"></i>
							</a>
						</div>
					</div>
				</div>
			</div>

			<div class="form-group container-box">
				<h4>{{ isset($data['backendlang']['backendlang']['The_Package_Contains_Vouchers']) ? $data['backendlang']['backendlang']['The_Package_Contains_Vouchers'] :'' }}</h4>
				<hr>
				<div class="voucher-parent-box">
					<div class="form-group">
						@php
							$totalVouchers = 0;
						@endphp
						@if(isset($packages_vouchers) && !$packages_vouchers->isEmpty())
							@foreach($packages_vouchers as $packages_vouchers)
								<input type="hidden" name="vpid[]" value="{{ $packages_vouchers->id }}">
								<div class="row row-parent-box">
									<div class="col-md-2">
										<div class="form-group">
											<select class="form-control vouchers" name="vouchers[]" data-filter="{{ $totalVouchers }}">
												<option value="">{{ isset($data['backendlang']['backendlang']['Select_Vouchers']) ? $data['backendlang']['backendlang']['Select_Vouchers'] :'' }}</option>
												@foreach($vouchers as $voucher)
												<option {{ ($packages_vouchers->voucher_id == $voucher->id) ? 'selected' : '' }}  value="{{ $voucher->id }}">
													{{ $voucher->promotion_title }}
												</option>
												@endforeach
											</select>
										</div>

									</div>
									<div class="col-md-2">
										<input type="input" name="voucher_qty[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['Quantity']) ? $data['backendlang']['backendlang']['Quantity'] :'' }}" value="{{ $packages_vouchers->qty }}" onkeypress="return isNumberKey(event)">	
									</div>
									<div class="col-md-2">
										<a href="#" class="important-text del-packages" data-id="{{ $packages_vouchers->id }}">
											<i class="bi bi-trash fa-2x"></i>
										</a>
									</div>
								</div>
								<br>
							@php
								$totalVouchers++;
							@endphp
							@endforeach
						@else
							<input type="hidden" name="vpid[]" value="">
							<div class="row row-parent-box">
								<div class="col-md-2">
									<div class="form-group">
										<select class="form-control vouchers" name="vouchers[]" data-filter="{{ $totalVouchers }}">
											<option value="">{{ isset($data['backendlang']['backendlang']['Select_Vouchers']) ? $data['backendlang']['backendlang']['Select_Vouchers'] :'' }}</option>
											@foreach($vouchers as $voucher)
											<option value="{{ $voucher->id }}">
												{{ $voucher->promotion_title }}
											</option>
											@endforeach
										</select>
									</div>
								</div>
								<div class="col-md-2">
									<input type="input" name="voucher_qty[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['Quantity']) ? $data['backendlang']['backendlang']['Quantity'] :'' }}" onkeypress="return isNumberKey(event)">	
								</div>
								<div class="col-md-2">
									<a href="#" class="important-text">
										<i class="bi bi-trash fa-2x"></i>
									</a>
								</div>
							</div>
						@endif
					</div>
					<input type="hidden" class="totalVouchers" value="{{ $totalVouchers }}">
				</div>

				<hr>
				<div class="form-group">
					<div class="row">
						<div class="col-md-6" align="center">
							<a href='#' class="add-voucher-btn btn btn-outline-primary btn-sm">
								<i class="bi bi-plus"></i>
							</a>
						</div>
					</div>
				</div>
			</div>
			<hr>
			<div class="form-group">
				<div class="row">
					<div class="col-sm-2">
						<b>{{ isset($data['backendlang']['backendlang']['Description']) ? $data['backendlang']['backendlang']['Description'] :'' }}:</b> <span class="important-text">*</span>
					</div>
					<div class="col-sm-10">
						<textarea class="form-control" name="description" id="description">{!! isset($product) ? $product->description : old('description') !!}</textarea>
					</div>
				</div>
			</div>

			<!-- <div class="form-group">
				<div class="row">
					<div class="col-sm-2">
						<b>Giveaway: </b>
					</div>
					<div class="col-sm-10">
						<textarea class="form-control" name="free_gift_description" id="free_gift_description">{!! isset($product) ? $product->free_gift : old('free_gift_description') !!}</textarea>
					</div>
				</div>
			</div> -->
			<div class="form-group">
				<div class="row">
					<div class="col-sm-2">
					<b>	{{ isset($data['backendlang']['backendlang']['Testimonial']) ? $data['backendlang']['backendlang']['Testimonial'] :'' }}:</b>
					</div>
					<div class="col-sm-10">
						<textarea class="form-control" name="testimonial" id="free_gift_description">{!! isset($product) ? $product->testimonial : old('testimonial') !!}</textarea>
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="row">
					<div class="col-sm-2">
						<b>{{ isset($data['backendlang']['backendlang']['Short_Description']) ? $data['backendlang']['backendlang']['Short_Description'] :'' }}:</b>
					</div>
					<div class="col-sm-10">
						<input type="text" class="form-control" name="short_description" value="{{ isset($product) ? $product->short_description : old('short_description') }}" placeholder="{{ isset($data['backendlang']['backendlang']['Short_Description']) ? $data['backendlang']['backendlang']['Short_Description'] :'' }}">
					</div>
				</div>
			</div>			
		</form>
		<hr>

		<div class="form-group">
			<div class="row">
				<div class="col-sm-2">
					<b>{{ isset($data['backendlang']['backendlang']['Image']) ? $data['backendlang']['backendlang']['Image'] :'' }}</b>
				</div>
				<div class="col-sm-10">
					<div class="form-group product-image-list">
						<div class="row" id="imageListId">
							
						</div>
						<div class="clear-both"></div>
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
