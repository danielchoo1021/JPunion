<div class="container-box form-group">
	<div class="row">
		<div class="col-12">

			<!-- <div class="form-group">
				<div class="row">
					<div class="col-sm-2">
						Product Voucher
					</div>
					<div class="col-sm-10">
						<input type="checkbox" name="product_voucher" class="product_voucher" value="1" {{ (isset($flash_sale) && $flash_sale->product_voucher == '1') ? 'checked' : ''  }}>
					</div>
				</div>
			</div> -->

			<div class="form-group">
				<div class="row">
					<div class="col-sm-2">
						{{ isset($data['backendlang']['backendlang']['Title']) ? $data['backendlang']['backendlang']['Title'] :'' }}: <span class="important-text">*</span>
					</div>
					<div class="col-sm-10">
						<input type="text" class="form-control" name="title" value="{{ isset($flash_sale) ? $flash_sale->title : old('title') }}" placeholder="{{ isset($data['backendlang']['backendlang']['Title']) ? $data['backendlang']['backendlang']['Title'] :'' }} *">
					</div>
				</div>
			</div>

			<div class="form-group non-product-voucher">
				<div class="form-group">
					<div class="row">
						<div class="col-sm-6">
							<div class="row">
								<div class="col-sm-4">
									{{ isset($data['backendlang']['backendlang']['Start_Date']) ? $data['backendlang']['backendlang']['Start_Date'] :'' }}: <span class="important-text">*</span>
								</div>
								<div class="col-sm-8">
									<!-- <div class="input-group">
										<input id="date-timepicker1" type="text" class="form-control date-timepicker1" name="start" 
											   value="{{ isset($flash_sale) && !empty($flash_sale->start) ? date('m/d/Y h:i:s a', strtotime($flash_sale->start)) : '' }}" />
										<span class="input-group-addon">
											<i class="bi bi-clock-o bigger-110"></i>
										</span>
									</div> -->

									<input
				                      	type="date"
				                      	class="form-control mb-3 flatpickr-no-config" id="start_date"
				                      	placeholder="{{ isset($flash_sale) && !empty($flash_sale->start) ? date('Y-m-d H:i:s', strtotime($flash_sale->start)) : (isset($data['backendlang']['backendlang']['Start_Date']) ? $data['backendlang']['backendlang']['Start_Date'] : 'Start date') }}"
				                      	name="start" 
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
										<input id="date-timepicker1" type="text" class="form-control date-timepicker1" name="end" 
											   value="{{ isset($flash_sale) && !empty($flash_sale->end) ? date('m/d/Y h:i:s a', strtotime($flash_sale->end)) : '' }}" />
										<span class="input-group-addon">
											<i class="bi bi-clock-o bigger-110"></i>
										</span>
									</div> -->
									<input
				                      	type="date"
				                      	class="form-control mb-3 flatpickr-no-config" id="end_date"
				                      	placeholder="{{ isset($flash_sale) && !empty($flash_sale->end) ? date('Y-m-d H:i:s', strtotime($flash_sale->end)) : (isset($data['backendlang']['backendlang']['End_Date']) ? $data['backendlang']['backendlang']['End_Date']  : 'End Date') }}"
										name="end"
				                    />
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>


			<div class="form-group">
				<div class="row">
					<div class="col-sm-12">
						<h3 class="header blue bolder smaller">{{ isset($data['backendlang']['backendlang']['products']) ? $data['backendlang']['backendlang']['products'] :'' }}</h3>
					</div>
				</div>
			</div>
			<div class="form-group">
				<button type="button" class="btn btn-info add_on_product_btn" data-bs-toggle="modal" data-bs-target="#add_flash_product">
					<i class="bi bi-plus"></i> {{ isset($data['backendlang']['backendlang']['Add_Products']) ? $data['backendlang']['backendlang']['Add_Products'] :'' }}
				</button>
			</div>

			<div class="form-group">
				<div id="batch_settings">
					<h3 class="header blue bolder smaller">{{ isset($data['backendlang']['backendlang']['Batch_Setting']) ? $data['backendlang']['backendlang']['Batch_Setting'] :'' }}</h3>
					<div class="row">
						<div class="col-lg-2">
							<label> {{ isset($data['backendlang']['backendlang']['Discount']) ? $data['backendlang']['backendlang']['Discount'] :'' }}</label>
							<div class="input-group">
								  <input type="text" name="add_on_discount" id="add_on_discount" class="form-control" onkeypress="return isNumberKey(event)">
								  <span class="input-group-addon">{{ isset($data['backendlang']['backendlang']['%OFF']) ? $data['backendlang']['backendlang']['%OFF'] :'' }}</span>
							</div>
						</div>
						<div class="col-lg-2">
							<label>{{ isset($data['backendlang']['backendlang']['Price']) ? $data['backendlang']['backendlang']['Price'] :'' }}</label>
							<input type="text" name="price" id="price" class="form-control" onkeypress="return isNumberKey(event)">
						</div>
						<div class="col-lg-2">
							<button type="button" id="update_selected" class="btn btn-primary btn-block">{{ isset($data['backendlang']['backendlang']['Update_Selected']) ? $data['backendlang']['backendlang']['Update_Selected'] :'' }}</button>
						</div>
						<div class="col-lg-2">
							<button type="button" id="update_all" class="btn btn-success btn-block">{{ isset($data['backendlang']['backendlang']['Update_All']) ? $data['backendlang']['backendlang']['Update_All'] :'' }}</button>
						</div>
					</div>
				</div>
			</div>

			<div class="form-group">
				<div id="display_deal_sub_items">
					@if(!$flash_sale_products->isEmpty())
						<div class="container-box" style="margin-top:20px">
							<small>{{ isset($data['backendlang']['backendlang']['Total']) ? $data['backendlang']['backendlang']['Total'] :'' }} ({{ $count_flash_sale_products }}) {{ isset($data['backendlang']['backendlang']['products']) ? $data['backendlang']['backendlang']['products'] :'' }}</small>
							<div class="table-responsive">
								<table class="table">
									<tr>
										<th>{{ isset($data['backendlang']['backendlang']['product']) ? $data['backendlang']['backendlang']['product'] :'' }}</th>
										<th></th>
										<th>{{ isset($data['backendlang']['backendlang']['Purchase_Limit']) ? $data['backendlang']['backendlang']['Purchase_Limit'] :'' }}</th>
					                    <th>{{ isset($data['backendlang']['backendlang']['Action']) ? $data['backendlang']['backendlang']['Action'] :'' }}</th>
										<th><input type="checkbox" name="check_all_flash_sale_products" class="check_all_flash_sale_products"></th>
										<th>{{ isset($data['backendlang']['backendlang']['User']) ? $data['backendlang']['backendlang']['User'] :'' }}</th>
					                    <th>{{ isset($data['backendlang']['backendlang']['Current_Price']) ? $data['backendlang']['backendlang']['Current_Price'] :'' }}</th>
					                    <th> {{ isset($data['backendlang']['backendlang']['Flash_Sale_Price']) ? $data['backendlang']['backendlang']['Flash_Sale_Price'] :'' }}</th>
					                    <!-- <th>Flash Sale Discount</th> -->
					                    <!-- <th>Purchase Limit</th>
					                    <th>Action</th> -->
									</tr>
									<tbody>
										@php
											$num = 0;
											$discount_num = 0;
											$hidden_price = 0;
											$price_count = 0;
											$purchase = 0;
										@endphp
										@foreach($flash_sale_products as $items)
										<tr>
											<td rowspan="{{ count($agent_levels)+2 }}">
												<img src="{{ !empty($items->get_product_detail->first_image->image) ? asset($items->get_product_detail->first_image->image) : asset('images/no-image-available-icon-6.jpg') }}" width="80" height="80">&nbsp; 

											</td>
											<td rowspan="{{ count($agent_levels)+2 }}">
												<p>{{ $items->get_product_detail->product_name }}</p><br>
												@if(!empty($items->variation_id))
													<p>Variation: {{ $items->get_variation->variation_name}}</p>
												@endif
												@if(!empty($items->second_variation_id))
													<p>Option: {{ $items->get_second_variation->variation_name }}</p>
												@endif
											</td>
											<td rowspan="{{ count($agent_levels)+2 }}">
												<input type="text" name="qty" value="{{ !empty($items->qty) ? $items->qty : '' }}" class="form-control form-control-sm" onkeypress="isNumberKey(event)" placeholder="{{ isset($data['backendlang']['backendlang']['Limit_Quantity']) ? $data['backendlang']['backendlang']['Limit_Quantity'] :'' }}">
												<input type="hidden" name="flash_sale_product_detail_id" value="{{ $items->id }}">
											</td>
											<td rowspan="{{ count($agent_levels)+2 }}">
												<a href="#" class="remove_sub_item" data-id="{{ $items->id }}">
													<i class="bi bi-trash"></i>
												</a>
											</td>
										</tr>
											@foreach($flash_prices[$items->id] as $key => $flash_price)
												<tr>
													<td><input type="checkbox" name="sub_item_check" class="sub_item_check" data-id="{{ $flash_price->id }}"></td>
													<td>
														@if(!empty($flash_price->agent_level_name))
															{{ $flash_price->agent_level_name }}
														@else
															{{ isset($data['backendlang']['backendlang']['Customer']) ? $data['backendlang']['backendlang']['Customer'] :'' }}
														@endif
													</td>
													@php
														$price = $original_price[$flash_price->id]['product_price'];
													@endphp
													<td>RM <span class="current_price_{{ $price_count++ }}">{{ !empty($original_price[$flash_price->id]['product_price']) ? number_format($original_price[$flash_price->id]['product_price'], 2) : '0.00' }}</span></td>
													@php
														$add_on_price = '';
														if(!empty($items->add_on_discount)){
															$current_price = $price;
															$add_on_price = $current_price - ($current_price * ($items->add_on_discount / 100));
														}
													@endphp
													<td>
														<input type="text" name="flash_price[{{ $flash_price->id }}]" value="{{ !empty($flash_price->price) ?  $flash_price->price : '' }}" class="form-control form-control-sm" onkeypress="isNumberKey(event)" placeholder="{{ isset($data['backendlang']['backendlang']['Price']) ? $data['backendlang']['backendlang']['Price'] :'' }}">
													</td>
													<!-- <td>
														<input type="text" name="add_on_discount[]" id="add_on_discount_{{$discount_num++}}" value="{{!empty($items->add_on_discount) ?  $items->add_on_discount : ''}}" class="form-control add_on_discount form-control-sm" onkeypress="isNumberKey(event)">
													</td> -->
												</tr>
											@endforeach
										<!-- <tr>
											<td rowspan="{{ count($agent_levels)+2 }}">
												<input type="text" name="purchase_limit"  id="purchase_limits_{{$purchase++}}" value="{{!empty($items->purchase_limit) ? $items->purchase_limit : ''}}" class="form-control form-control-sm" onkeypress="isNumberKey(event)">
											</td>
											<td rowspan="{{ count($agent_levels)+2 }}">
												<a href="#" class="remove_sub_item" data-id="{{$items->sid}}">
													<i class="bi bi-trash"></i>
												</a>
											</td>
										</tr> -->
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
<script type="text/javascript">
</script>