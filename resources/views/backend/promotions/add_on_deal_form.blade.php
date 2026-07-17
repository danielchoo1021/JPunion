<div class="container-box">
	<div class="row">
		<div class="col-sm-12">
			<div class="form-group">
				<h3>{{ isset($data['backendlang']['backendlang']['Basic_Information']) ? $data['backendlang']['backendlang']['Basic_Information'] :'' }}</h3>
			</div>
			<hr>
			<div class="form-group">
				<div class="row">
					<div class="col-sm-2">
						{{ isset($data['backendlang']['backendlang']['Promotion_Name']) ? $data['backendlang']['backendlang']['Promotion_Name'] :'' }}: <span class="important-text">*</span>
					</div>
					<div class="col-sm-10">
						<input type="text" class="form-control" name="promotion_name" value="{{ isset($add_on_deal) ? $add_on_deal->promotion_name : old('promotion_name') }}" placeholder="{{ isset($data['backendlang']['backendlang']['Promotion_Name']) ? $data['backendlang']['backendlang']['Promotion_Name'] :'' }} *">
						<input type="hidden" name="deal_id" id="deal_id" value="{{ isset($add_on_deal) ? $add_on_deal->id : '' }}">
					</div>
				</div>
			</div>
			
			<div class="form-group">
				<div class="row">
					<div class="col-sm-6">
						<div class="row">
							<div class="col-sm-4">
								{{ isset($data['backendlang']['backendlang']['Start_Date']) ? $data['backendlang']['backendlang']['Start_Date'] :'' }}: <span class="important-text">*</span>
							</div>
							<div class="col-sm-8">
								<!-- <div class="input-group">
									<input id="date-timepicker1" type="text" class="form-control date-timepicker1" name="start_date" 
										   value="{{ isset($add_on_deal) && !empty($add_on_deal->start_date) ? date('m/d/Y h:i:s a', strtotime($add_on_deal->start_date)) : '' }}" />
									<span class="input-group-addon">
										<i class="bi bi-clock-o bigger-110"></i>
									</span>
								</div> -->
								
								<input
			                      	type="date"
			                      	class="form-control mb-3 flatpickr-no-config" id="start_date"
			                      	placeholder="{{ isset($add_on_deal) && !empty($add_on_deal->start_date) ? date('Y-m-d H:i:s', strtotime($add_on_deal->start_date)) : (isset($data['backendlang']['backendlang']['Start_Date']) ? $data['backendlang']['backendlang']['Start_Date'] : 'Start date') }}"
			                      	name="start_date" 
			                    />
							</div>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="row">
							<div class="col-sm-4">
								{{ isset($data['backendlang']['backendlang']['End_Date']) ? $data['backendlang']['backendlang']['End_Date'] :'' }}: <span class="important-text">*</span>
							</div>
							<div class="col-sm-8">
								<!-- <div class="input-group">
									<input id="date-timepicker1" type="text" class="form-control date-timepicker1" name="end_date" 
										   value="{{ isset($add_on_deal) && !empty($add_on_deal->end_date) ? date('m/d/Y h:i:s a', strtotime($add_on_deal->end_date)) : '' }}" />
									<span class="input-group-addon">
										<i class="bi bi-clock-o bigger-110"></i>
									</span>
								</div> -->
								<input
			                      	type="date"
			                      	class="form-control mb-3 flatpickr-no-config" id="end_date"
			                      	placeholder="{{ isset($add_on_deal) && !empty($add_on_deal->end_date) ? date('Y-m-d H:i:s', strtotime($add_on_deal->end_date)) : (isset($data['backendlang']['backendlang']['End_Date']) ? $data['backendlang']['backendlang']['End_Date'] : 'End date') }}"
			                      	name="end_date"
									
			                    />
							</div>
						</div>
					</div>
				</div>
			</div>
			<hr>
			<div class="col-sm-12">
				<div class="form-group">
					<h3>{{ isset($data['backendlang']['backendlang']['Main_Product']) ? $data['backendlang']['backendlang']['Main_Product'] :'' }}</h3>
				</div>
				<div class="form-group">
					<button type="button" class="btn btn-info add_main_product_btn" data-bs-toggle="modal" data-bs-target="#add_main_product">
						<i class="bi bi-plus"></i> {{ isset($data['backendlang']['backendlang']['Add_Main_Product']) ? $data['backendlang']['backendlang']['Add_Main_Product'] :'' }}
					</button>
				</div>
				<div id="display_deal_items">
					@if(!$item->isEmpty())
					<div class="container-box" style="margin-top:20px;">
						<small>{{ isset($data['backendlang']['backendlang']['Total']) ? $data['backendlang']['backendlang']['Total'] :'' }} ({{$count}}) {{ isset($data['backendlang']['backendlang']['products']) ? $data['backendlang']['backendlang']['products'] :'' }}</small>
						<div class="table-responsive">
							<table class="table">
								<tr>
									<th>{{ isset($data['backendlang']['backendlang']['product']) ? $data['backendlang']['backendlang']['product'] :'' }}</th>
									<th></th>
									<th>{{ isset($data['backendlang']['backendlang']['Current_Price']) ? $data['backendlang']['backendlang']['Current_Price'] :'' }}</th>
									<th>{{ isset($data['backendlang']['backendlang']['Stock']) ? $data['backendlang']['backendlang']['Stock'] :'' }}</th>
									<th>{{ isset($data['backendlang']['backendlang']['Action']) ? $data['backendlang']['backendlang']['Action'] :'' }}</th>
								</tr>
								<tbody>
									@foreach($item as $items)

									<tr>
										<td><img src="{{ !empty($image[$items->aid]) ? asset($image[$items->aid]) : '' }}" width="80" height="80">&nbsp;</td>
										<td>
											<p>{{$items->p_name}}</p>
											@if(!empty($items->variation_id))
											
												<p>Variation: {{$item_variation[$items->aid]->variation_name}} </p>
												@if(!empty($items->second_variation_id))
													<p>Option: {{$item_sec_variation[$items->aid]->variation_name}}</p>
												@endif
											@endif
										</td>
										@php
											$price = 0;
											if(!empty($items->variation_id)){
										
												$price = !empty($item_variation[$items->aid]->variation_special_price) ?  $item_variation[$items->aid]->variation_special_price : $item_variation[$items->aid]->variation_price;
												if(!empty($price)){
													$price = $price;
												}else{

													if(!empty($items->second_variation_id)){
														
														$price = !empty($item_sec_variation[$items->aid]->variation_special_price) ? $item_sec_variation[$items->aid]->variation_special_price : $item_sec_variation[$items->aid]->variation_price;
													}
												}
											
											}else{

												$price = !empty($items->special_price) ? $items->special_price : $items->price;
											}
										@endphp
										<td>RM {{number_format(!empty($price) ? $price : 0,2)}}</td>
										<td>
									
											@if(!empty($items->variation_id))
												{{$item_variation_stock[$items->aid]}}
											@else
												{{$stock[$items->aid]}}
											@endif
										</td>
										<td><a href="#" class="remove_item" data-id="{{$items->aid}}"><i class="bi bi-trash"></i></a></td>
									</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
					@endif
				</div>
			</div>
			<hr>
			<div class="col-sm-12">
				<h3 class="header blue bolder smaller">{{ isset($data['backendlang']['backendlang']['Addon_Products']) ? $data['backendlang']['backendlang']['Addon_Products'] :'' }}</h3>
				<button type="button" class="btn btn-info add_on_product_btn" data-bs-toggle="modal" data-bs-target="#add_sub_product">
						<i class="bi bi-plus"></i> {{ isset($data['backendlang']['backendlang']['Addon_Product']) ? $data['backendlang']['backendlang']['Addon_Product'] :'' }}
				</button>

				<hr>
				<div id="batch_settings">
					<h3 class="header blue bolder smaller">{{ isset($data['backendlang']['backendlang']['Batch_Setting']) ? $data['backendlang']['backendlang']['Batch_Setting'] :'' }}</h3>
					<div class="row">
						<div class="col-lg-2">
							<label>{{ isset($data['backendlang']['backendlang']['Addon_Discount']) ? $data['backendlang']['backendlang']['Addon_Discount'] :'' }}</label>
							<div class="input-group">
								  <input type="text" name="add_on_discount" id="add_on_discount" class="form-control" onkeypress="return isNumberKey(event)">
								  <span class="input-group-addon">{{ isset($data['backendlang']['backendlang']['%OFF']) ? $data['backendlang']['backendlang']['%OFF'] :'' }}</span>
							</div>
						</div>
						<div class="col-lg-2">
							<label>{{ isset($data['backendlang']['backendlang']['Purchase_Limit']) ? $data['backendlang']['backendlang']['Purchase_Limit'] :'' }}</label>
							<input type="text" name="purchase_limits" id="purchase_limits" class="form-control" onkeypress="return isNumberKey(event)">
						</div>
						<div class="col-lg-2">
							<br>
							<button type="button" id="update_selected" class="btn btn-primary btn-block">{{ isset($data['backendlang']['backendlang']['Update_Selected']) ? $data['backendlang']['backendlang']['Update_Selected'] :'' }}</button>
						</div>
						<div class="col-lg-2">
							<br>
							<button type="button" id="update_all" class="btn btn-success btn-block">{{ isset($data['backendlang']['backendlang']['Update_All']) ? $data['backendlang']['backendlang']['Update_All'] :'' }}</button>
						</div>
					</div>
				</div>
				<div id="display_deal_sub_items">
					@if(!$sub_item->isEmpty())
						<div class="container-box" style="margin-top:20px">
							<small>{{ isset($data['backendlang']['backendlang']['Total']) ? $data['backendlang']['backendlang']['Total'] :'' }} ({{$count_sub_items}}) {{ isset($data['backendlang']['backendlang']['products']) ? $data['backendlang']['backendlang']['products'] :'' }}</small>
						<div class="table-responsive">
							<table class="table">
								<tr>
									<th><input type="checkbox" name="check_all_sub_items" class="check_all_sub_items"></th>
									<th>{{ isset($data['backendlang']['backendlang']['product']) ? $data['backendlang']['backendlang']['product'] :'' }}</th>
									<th></th>
				                    <th>{{ isset($data['backendlang']['backendlang']['Current_Price']) ? $data['backendlang']['backendlang']['Current_Price'] :'' }}</th>
				                    <th>{{ isset($data['backendlang']['backendlang']['Addon_Price']) ? $data['backendlang']['backendlang']['Addon_Price'] :'' }}</th>
				                    <th>{{ isset($data['backendlang']['backendlang']['Addon_Discount']) ? $data['backendlang']['backendlang']['Addon_Discount'] :'' }}</th>
				                    <th>{{ isset($data['backendlang']['backendlang']['Stock']) ? $data['backendlang']['backendlang']['Stock'] :'' }}</th>
				                    <th>{{ isset($data['backendlang']['backendlang']['Purchase_Limit']) ? $data['backendlang']['backendlang']['Purchase_Limit'] :'' }}</th>
				                    <th>{{ isset($data['backendlang']['backendlang']['Action']) ? $data['backendlang']['backendlang']['Action'] :'' }}</th>
								</tr>
								<tbody>
									@php
										$num = 0;
										$discount_num = 0;
										$hidden_price = 0;
										$price_count = 0;
										$purchase = 0;
									@endphp
									@foreach($sub_item as $items)
									<tr>
										<td>
											<input type="checkbox" name="sub_item_check" class="sub_item_check" data-id="{{$items->sid}}">
											<input type="hidden" name="sid[]" class="sid" value="{{ $items->sid }}">
										</td>
										<td>
											<img src="{{ !empty($sub_item_image[$items->sid]) ? asset($sub_item_image[$items->sid]) : '' }}" width="80" height="80">&nbsp; 

										</td>
										<td>
												<p>{{$items->p_name}}</p><br>
											@if(!empty($items->variation_id))

												<p>Variation: {{$variations[$items->sid]->variation_name}}</p>
											@endif
											@if(!empty($items->second_variation_id))
												<p>Option: {{$second_variations[$items->sid]->variation_name}}</p>
											@endif
										</td>
										@php
											$price = '';
											if(!empty($items->variation_id)){
												$price = !empty($variations[$items->sid]->special_price) ? $variations[$items->sid]->special_price : $variations[$items->sid]->variation_price;
												if(!empty($items->second_variation_id)){
													$price = !empty($second_variations[$items->sid]->variation_special_price) ? $second_variations[$items->sid]->variation_special_price : $second_variations[$items->sid]->variation_price;
												}
											
											}else{
												$price = !empty($items->special_price) ? $items->special_price : $items->price;
											}

											$price = $original_price[$items->sid]['product_price'];
										@endphp
										<td>RM <span class="current_price_{{$price_count++}}">{{number_format(!empty($price) ? $price : 0,2)}}</span></td>
										<td>
											<input type="hidden" id="hidden_price_{{$hidden_price++}}" class="hidden_price" value="{{$price}}">
											<input type="text" name="add_on_price[{{ $items->sid }}]" id="add_on_price_{{ $num++ }}" value="{{ !empty($current_price[$items->sid]) ?  $current_price[$items->sid] : '' }}" class="form-control add_on_price form-control-sm" onkeypress="isNumberKey(event)"></td>
										<td>
											<div class="input-group">
												<input type="text" name="add_on_discount[{{ $items->sid }}]" id="add_on_discount_{{$discount_num++}}" value="{{!empty($items->add_on_discount) ?  $items->add_on_discount : ''}}" class="form-control add_on_discount form-control-sm" onkeypress="isNumberKey(event)">
												<span class="input-group-addon form-control-sm" style="padding: 0.25rem 0.5rem;">%</span>
											</div>
										</td>
										<td>
							
											{{ $sub_item_stock[$items->sid] }}
										</td>
										<td><input type="text" name="purchase_limit[{{ $items->sid }}]"  id="purchase_limits_{{$purchase++}}" value="{{!empty($items->purchase_limit) ? $items->purchase_limit : ''}}" class="form-control form-control-sm" onkeypress="isNumberKey(event)"></td>
										<td><a href="#" class="remove_sub_item" data-id="{{$items->sid}}"><i class="bi bi-trash"></i></a></td>
									</tr>
									@endforeach
								</tbody>
							</table>
						</div>
						</div>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>