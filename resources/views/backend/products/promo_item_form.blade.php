@php
if(isset($title)){
	$action_url = route('promotion_item_edit_save', $title->id);
}else{
	$action_url = route('promotion_item_add_save');
}

@endphp
<div class="row">
	<div class="col-12">
		<form method="POST" action="{{ $action_url }}" id="product-form">
			@csrf

			<div class="form-group container-box">
				<h4>{{ isset($data['backendlang']['backendlang']['Fill_In_Product_Information']) ? $data['backendlang']['backendlang']['Fill_In_Product_Information'] :'' }}</h4>
				<hr>
				<div class="form-group">
					<div class="row">
						<div class="col-sm-2">
							{{ isset($data['backendlang']['backendlang']['Name']) ? $data['backendlang']['backendlang']['Name'] :'' }}: <span class="important-text">*</span>
						</div>
						<div class="col-sm-10">
							<input type="text" class="form-control" name="promo_title" value="{{ isset($title) ? $title->promo_title : old('promo_title') }}" placeholder="{{ isset($data['backendlang']['backendlang']['Name']) ? $data['backendlang']['backendlang']['Name'] :'' }}">
						</div>
					</div>
				</div>
			</div>
			<div class="form-group container-box">
				<h4>{{ isset($data['backendlang']['backendlang']['Setting_Date']) ? $data['backendlang']['backendlang']['Setting_Date'] :'' }}</h4>
				<hr>
				<div class="form-group">
					<div class="row">
						<div class="col-sm-6">
							<div class="row">
								<div class="col-sm-4">
									{{ isset($data['backendlang']['backendlang']['Start_Date']) ? $data['backendlang']['backendlang']['Start_Date'] :'' }}: <span class="important-text">*</span>
								</div>
								<div class="col-sm-8">
									<div class="input-group">
										<input id="date-timepicker1" type="text" class="form-control date-timepicker1" name="start_date" 
											   value="{{ isset($title) && !empty($title->date_from) ? date('m/d/Y h:i:s a', strtotime($title->date_from)) : '' }}" />
										<span class="input-group-addon">
											<i class="fa fa-clock-o bigger-110"></i>
										</span>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="row">
								<div class="col-sm-4">
									{{ isset($data['backendlang']['backendlang']['End_Date']) ? $data['backendlang']['backendlang']['End_Date'] :'' }}: <span class="important-text">*</span>
								</div>
								<div class="col-sm-8">
									<div class="input-group">
										<input id="date-timepicker1" type="text" class="form-control date-timepicker1" name="end_date" 
											   value="{{ isset($title) && !empty($title->date_end) ? date('m/d/Y h:i:s a', strtotime($title->date_end)) : '' }}" />
										<span class="input-group-addon">
											<i class="fa fa-clock-o bigger-110"></i>
										</span>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="form-group container-box">
				<h4>{{ isset($data['backendlang']['backendlang']['products']) ? $data['backendlang']['backendlang']['products'] :'' }}</h4>
				<hr>
				<div class="row" style="display: flex; justify-content: center;">
					<div class="col">
						{{ isset($data['backendlang']['backendlang']['product']) ? $data['backendlang']['backendlang']['product'] :'' }}
					</div>
					<div class="col customer_price_box">
						{{ isset($data['backendlang']['backendlang']['Customer_Price']) ? $data['backendlang']['backendlang']['Customer_Price'] :'' }}
					</div>
					<div class="col customer_special_price_box">
						{{ isset($data['backendlang']['backendlang']['Customer_Special_Price']) ? $data['backendlang']['backendlang']['Customer_Special_Price'] :'' }}
					</div>
					@foreach($agent_levels as $agent_level)
					<div class="col agentlvl_{{ $agent_level->id }}">
						{{ $agent_level->agent_lvl }}
					</div>
					@endforeach
				</div>
				<hr>
				@php
				$num = 0;
				@endphp
				<div class="parent-row">
					@if(isset($items) && !$items->isEmpty())
					@foreach($items as $item_key => $item)
						<div class="row row-parent-box" style="display: flex; justify-content: center;">
							<div class="col">
								<div class="form-group">
									<input type="hidden" name="iid[]" value="{{ $item->id }}">
									<input type="hidden" class="row_num" name="row_num[]" value="{{ $num }}">
									<select class="form-control products" name="products[]">
										<option value="">{{ isset($data['backendlang']['backendlang']['Select_Product']) ? $data['backendlang']['backendlang']['Select_Product'] :'' }}</option>
										@foreach($products as $product)
										<option {{ ($item->product_id == $product->id) ? 'selected' : '' }} 
												value="{{ $product->id }}">
											{{ $product->product_name }}
										</option>
										@endforeach
									</select>
								</div>
							</div>
							@if($item->variation_enable == 1)
									<div class="col">
										<input type="hidden" class="form-control" name="variation_enable[]" value="1">
										<table class="table table-bordered variation-list-child-row">
											<tr>
												<td class="variation_title">{{ isset($item) ? $item->variation_title : (isset($data['backendlang']['backendlang']['Name']) ? $data['backendlang']['backendlang']['Name'] : 'Name') }}</td>
												@if(isset($item) && $item->second_variation_enable == 1)
												<td class="variation_two variation_two_title">
													{{ isset($item) ? $item->variation_title : (isset($data['backendlang']['backendlang']['Name']) ? $data['backendlang']['backendlang']['Name'] : 'Name') }}
												</td>
												@endif
												<td>{{ isset($data['backendlang']['backendlang']['Customer_Price']) ? $data['backendlang']['backendlang']['Customer_Price'] :'' }}</td>
												<td>{{ isset($data['backendlang']['backendlang']['Customer_Special_Price']) ? $data['backendlang']['backendlang']['Customer_Special_Price'] :'' }}</td>
												@foreach($agent_levels as $key => $agentlvl)
												<td>{{ $agentlvl->agent_lvl }}</td>
												<td>{{ $agentlvl->agent_lvl }} ({{ isset($data['backendlang']['backendlang']['Special_Price']) ? $data['backendlang']['backendlang']['Special_Price'] :'' }})</td>
												@endforeach
											</tr>
											@if(isset($variations[$item->id]) && !$variations[$item->id]->isEmpty())
												
												@if($item->second_variation_enable == 1)
													@php
													$lrow=0;
													@endphp
													@foreach($variations[$item->id] as $vkey => $varia)
													<tr data-id="0">
														<td class="variation_option_display_{{ $vkey }} first_variation" data-id="0" rowspan="{{ count($s_secnd_variations[$item->id][$varia->id])+1 }}">
															{{ $varia->variation_name }}

														<input type="hidden" name="fvid_{{ $item_key }}[]" value="{{ $varia->id }}">
														</td>
													</tr>
													@php
													$slrow=0;
													@endphp
													@foreach($s_secnd_variations[$item->id][$varia->id] as $s_secnd_variation)
													@php
														$sn_in_price = (isset($normal_v2_price[$item->id][0][$s_secnd_variation->variation_id][$s_secnd_variation->id])) ? $normal_v2_price[$item->id][0][$s_secnd_variation->variation_id][$s_secnd_variation->id] : '';

														$sn_in_special_price = (isset($normal_v2_special_price[$item->id][0][$s_secnd_variation->variation_id][$s_secnd_variation->id])) ? $normal_v2_special_price[$item->id][0][$s_secnd_variation->variation_id][$s_secnd_variation->id] : '';

														$sn_in_ids = (isset($normal_v2_ids[$item->id][0][$s_secnd_variation->variation_id][$s_secnd_variation->id])) ? $normal_v2_ids[$item->id][0][$s_secnd_variation->variation_id][$s_secnd_variation->id] : '';
													@endphp
														<tr class="added-v2-option_{{ $slrow }} added" data-id="{{ $lrow }}">
															<td class="variation_option_two_display_{{ $slrow }} variation_two">
																<span>{{ $s_secnd_variation->variation_name }}</span>
																<input type="hidden" class="variation_option_two_value_{{ $slrow }}" name="variation_option_two_value_{{ $item_key }}_{{ $vkey }}[]" value="{{ $s_secnd_variation->variation_name }}">
																<input type="hidden" name="rid_{{ $lrow }}[]" value="{{ $s_secnd_variation->id }}">
															</td>
															<td>
																<input type="text" name="customer_price_{{ $item_key }}_{{ $vkey }}[]" class="form-control" value="{{ $sn_in_price }}">

																<input type="hidden" name="customer_price_ids_{{ $item_key }}_{{ $vkey }}[]" value="{{ $sn_in_ids }}" class="form-control">
															</td>
															<td><input type="text" name="customer_special_price_{{ $item_key }}_{{ $vkey }}[]" class="form-control"  value="{{ $sn_in_special_price }}"></td>
															@foreach($agent_levels as $key => $agentlvl)
															@php
																$in_price = (isset($agent_v2_prices[$item->id][$agentlvl->id][$s_secnd_variation->variation_id][$s_secnd_variation->id])) ? $agent_v2_prices[$item->id][$agentlvl->id][$s_secnd_variation->variation_id][$s_secnd_variation->id] : '';

																$in_special_price = (isset($agent_v2_special_prices[$item->id][$agentlvl->id][$s_secnd_variation->variation_id][$s_secnd_variation->id])) ? $agent_v2_special_prices[$item->id][$agentlvl->id][$s_secnd_variation->variation_id][$s_secnd_variation->id] : '';

																$in_ids = (isset($agent_prices_v2_ids[$item->id][$agentlvl->id][$s_secnd_variation->variation_id][$s_secnd_variation->id])) ? $agent_prices_v2_ids[$item->id][$agentlvl->id][$s_secnd_variation->variation_id][$s_secnd_variation->id] : '';
															@endphp
															<td>
																<input type="text" name="agent_level_price_{{ $item_key }}_{{ $vkey }}_{{ $slrow }}[]" class="form-control"  value="{{ $in_price }}">

																<input type="hidden" name="variation_agent_level_{{ $item_key }}_{{ $vkey }}_{{ $slrow }}[]" value="{{ $agentlvl->id }}" class="form-control">

																<input type="hidden" name="variation_agent_level_id_{{ $item_key }}_{{ $vkey }}_{{ $slrow }}[]" value="{{ $in_ids }}" class="form-control">
															</td>
															<td>
																<input type="text" name="agent_level_special_price_{{ $item_key }}_{{ $vkey }}_{{ $slrow }}[]" class="form-control"  value="{{ $in_special_price }}">
															</td>
															@endforeach
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
													@foreach($variations[$item->id] as $varia)
													@php
														$n_in_price = (isset($normal_v_price[$item->id][0][$varia->id])) ? $normal_v_price[$item->id][0][$varia->id] : '';

														$n_in_special_price = (isset($normal_v_special_price[$item->id][0][$varia->id])) ? $normal_v_special_price[$item->id][0][$varia->id] : '';

														$n_in_ids = (isset($normal_v_ids[$item->id][0][$varia->id])) ? $normal_v_ids[$item->id][0][$varia->id] : '';
													@endphp
													<tr data-id="{{ $lrow }}">
														<td class="variation_option_display_{{ $lrow }} first_variation" data-id="{{ $lrow }}">
															{{ $varia->variation_name }}
															<input type="hidden" name="fvid_{{ $item_key }}[]" value="{{ $varia->id }}">
															<input type="hidden" name="rid_{{ $item_key }}[]" value="">
														</td>
														<td class="variation_option_two_display_0 variation_two" style="display: none;">
															<span>Option</span>
															<input type="hidden" class="variation_option_two_value_0" name="variation_option_two_value_{{ $item_key }}_{{ $lrow }}[]">
														</td>
														<td><input type="text" name="customer_price_{{ $item_key }}_{{ $lrow }}[]" class="form-control" value="{{ $n_in_price }}">
															<input type="hidden" name="customer_price_ids_{{ $item_key }}_{{ $lrow }}[]" value="{{ $n_in_ids }}">
														</td>
														<td><input type="text" name="customer_special_price_{{ $item_key }}_{{ $lrow }}[]" class="form-control"  value="{{ $n_in_special_price }}"></td>
														@foreach($agent_levels as $key => $agentlvl)

														@php
															$in_price = (isset($agent_v_prices[$item->id][$agentlvl->id][$varia->id])) ? $agent_v_prices[$item->id][$agentlvl->id][$varia->id] : '';

															$in_special_price = (isset($agent_v_special_prices[$item->id][$agentlvl->id][$varia->id])) ? $agent_v_special_prices[$item->id][$agentlvl->id][$varia->id] : '';

															$in_ids = (isset($agent_prices_v_ids[$item->id][$agentlvl->id][$varia->id])) ? $agent_prices_v_ids[$item->id][$agentlvl->id][$varia->id] : '';
														@endphp
														<td>
															<input type="text" name="agent_level_price_{{ $item_key }}_{{ $lrow }}_0[]" class="form-control"  value="{{ $in_price }}">

															<input type="hidden" name="variation_agent_level_{{ $item_key }}_{{ $lrow }}_0[]" value="{{ $agentlvl->id }}" class="form-control">

															<input type="hidden" name="variation_agent_level_id_{{ $item_key }}_{{ $lrow }}_0[]" value="{{ $in_ids }}" class="form-control">
														</td>
														<td>
															<input type="text" name="agent_level_special_price_{{ $item_key }}_{{ $lrow }}_0[]" class="form-control"  value="{{ $in_special_price }}">
														</td>
														@endforeach
													</tr>
													@php
													$lrow++;
													@endphp
													@endforeach
												@endif
											@else
												<tr data-id="0">
													<td class="variation_option_display_0 first_variation" data-id="0">
														{{ isset($data['backendlang']['backendlang']['Option']) ? $data['backendlang']['backendlang']['Option'] :'' }}
														<input type="hidden" name="rid_0[]" value="">
													</td>
													<td class="variation_option_two_display_0 variation_two" style="display: none;">
														<span>{{ isset($data['backendlang']['backendlang']['Option']) ? $data['backendlang']['backendlang']['Option'] :'' }}</span>
				                        				<input type="hidden" class="variation_option_two_value_0" name="variation_option_two_value_0[]">
													</td>
													<td><input type="text" name="customer_price_0[]" class="form-control"></td>
													<td><input type="text" name="customer_special_price_0[]" class="form-control"></td>
													@foreach($agent_levels as $key => $agentlvl)
													<td>
														<input type="text" name="agent_level_price_0_0[]" class="form-control">
														<input type="hidden" name="variation_agent_level_0_0[]" value="{{ $agentlvl->id }}" class="form-control">
														<input type="hidden" name="variation_agent_level_id_0_0[]" value="" class="form-control">
													</td>
													<td>
														<input type="text" name="agent_level_special_price_0_0[]" class="form-control"  value="">
													</td>
													@endforeach
												</tr>
											@endif
										</table>
									</div>
							@else
								@php
									$n_price = isset($normal_price[$item->id][0]) ? $normal_price[$item->id][0] : '';
									$n_special_price = isset($normal_special_price[$item->id][0]) ? $normal_special_price[$item->id][0] : '';
									$n_ids = isset($normal_ids[$item->id][0]) ? $normal_ids[$item->id][0] : '';

								@endphp
								<div class="col">
									<input type="hidden" class="form-control" name="variation_enable[]" value="">
									<input type="text" class="form-control" name="customer_price_{{ $item_key }}[]" value="{{ $n_price }}">
									<input type="hidden" name="customer_price_ids_nv_{{ $item_key }}[]" value="{{ $n_ids }}">
								</div>
								<div class="col">
									<input type="text" class="form-control" name="customer_special_price_{{ $item_key }}[]"
										    value="{{ $n_special_price }}">
								</div>
								@foreach($agent_levels as $agent_level)
								<div class="col">
									<input type="hidden" name="apiid_{{ $num }}[]" value="{{ (isset($agent_prices_ids[$item->id][$agent_level->id])) ? $agent_prices_ids[$item->id][$agent_level->id] : '' }}">
									<input type="text" class="form-control" name="agent_price_{{ $num }}[]" value="{{ (isset($agent_prices[$item->id][$agent_level->id])) ? $agent_prices[$item->id][$agent_level->id] : '0.00' }}">
									<input type="hidden" class="form-control" name="agent_id_{{ $num }}[]" value="{{ $agent_level->id }}">
								</div>
								@endforeach
							@endif
							<div class="col" align="center">
								<a href="#" class="delete-details-item" data-id="{{ $item->id }}">
									<i class="fa fa-trash fa-2x"></i>
								</a>
							</div>
						</div>
					@php
					$num++;
					@endphp
					@endforeach
					@endif
					<div class="row row-parent-box" style="display: flex; justify-content: center;">
						<div class="col">
							<div class="form-group">
								<input type="hidden" class="row_num" name="row_num[]" value="{{ $num }}">
								<select class="form-control products" name="products[]">
									<option value="">{{ isset($data['backendlang']['backendlang']['Select_Product']) ? $data['backendlang']['backendlang']['Select_Product'] :'' }}</option>
									@foreach($products as $product)
									<option value="{{ $product->id }}">
										{{ $product->product_name }}
									</option>
									@endforeach
								</select>
							</div>
							<div class="form-group option-list">
							</div>
							<div class="form-group second-option-list">
							</div>
						</div>
						<div class="col">
							<div class="form-group pricing-list" style="display: flex; justify-content: center;">

							</div>
						</div>
						<!-- <div class="col">
							<input type="text" class="form-control" name="customer_price[]">
						</div>
						<div class="col">
							<input type="text" class="form-control" name="customer_special_price[]">
						</div>
						@foreach($agent_levels as $agent_level)
						<div class="col">
							<input type="text" class="form-control" name="agent_price_{{ $num }}[]">
							<input type="hidden" class="form-control" name="agent_id_{{ $num }}[]" value="{{ $agent_level->id }}">
						</div>
						@endforeach -->
					</div>

				</div>
				<input type="hidden" name="total_num" class="total_num" value="{{ $num }}">
				<hr>
				<div class="form-group">
					<div class="row">
						<div class="col-md-12" align="center">
							<button class="add-row-btn">
								<i class="fa fa-plus"></i>
							</button>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
