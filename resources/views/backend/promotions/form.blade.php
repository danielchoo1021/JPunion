<div class="container-box form-group">
	
	@section('css')
	<style type="text/css">
	    .promotion-user-select + .select2-container .select2-selection--single {
	        height: calc(2.25rem) !important;
	        padding: 0.375rem 0.75rem !important;
	        font-size: 0.95rem !important; 
	        font-weight: 400 !important;
	        color: #34495e !important;
	        background-color: #ffffff !important;
	        border: 1px solid #ced4da !important;
	        border-radius: 4px !important;
	        box-shadow: inset 0 1px 2px rgba(0,0,0,0.03);
	        transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
	    }
	    .promotion-user-select + .select2-container .select2-selection--single:hover {
	        border-color: #80bdff !important;
	    }
	    .promotion-user-select + .select2-container .select2-selection--single .select2-selection__rendered {
	        padding-left: 0 !important;
	        line-height: 1.4 !important;
	    }
	    .promotion-user-select + .select2-container .select2-selection--single .select2-selection__arrow {
	        height: calc(2.25rem) !important;
	        right: 6px !important;
	        width: 28px;
	    }
	    .select2-results__option--highlighted[aria-selected] {
	        background-color: #0d6efd !important; /* bootstrap primary */
	        color: #fff !important;
	    }
	    .select2-container--default .select2-results__option[aria-selected=true] {
	        background-color: #e9f3ff !important;
	        color: #0d6efd !important;
	    }
	</style>
	@endsection

	<div class="container-box form-group">

	<div class="row">
		<div class="col-12">
			<div class="form-group">
				<div class="row">
					<div class="col-sm-2">
						<b>{{ isset($data['backendlang']['backendlang']['Title']) ? $data['backendlang']['backendlang']['Title'] :'' }}:</b> <span class="important-text">*</span>
					</div>
					<div class="col-sm-10">
						<input type="text" class="form-control" name="promotion_title" value="{{ isset($promotion) ? $promotion->promotion_title : old('promotion_title') }}" placeholder="{{ isset($data['backendlang']['backendlang']['Title']) ? $data['backendlang']['backendlang']['Title'] :'' }} *">
					</div>
				</div>
			</div>
			
			<div class="form-group">
				<div class="row">
					<div class="col-sm-2">
						<b>{{ isset($data['backendlang']['backendlang']['Upload_Image']) ? $data['backendlang']['backendlang']['Upload_Image'] :'' }}: </b>
					</div>
					<div class="col-sm-10">
						<input type="file" name="image" class="form-control" accept="image/*">
						<br>
						@if(isset($promotion) && !empty($promotion->image))
						<img src="{{ asset($promotion->image) }}" style="width: 100px;">
						@endif
					</div>
				</div>
				<div class="form-group ">
					<div class="row">
						<div class="col-sm-2">
							<b>{{ isset($data['backendlang']['backendlang']['Discount_Code']) ? $data['backendlang']['backendlang']['Discount_Code'] :'' }}:</b> <span class="important-text">*</span>
						</div>
						<div class="col-sm-10">
							<input type="text" name="discount_code" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['Discount_Code']) ? $data['backendlang']['backendlang']['Discount_Code'] :'' }} *" value="{{ isset($promotion) ? $promotion->discount_code : old('discount_code') }}">
						</div>
					</div>
				</div>
				@php
					$get_type = isset($promotion) ? $promotion->free_shipping : '';

					if($get_type == 1){
						$display_area = "none";
					}else{
						$display_area = "block";
					}
				@endphp
				<div class="form-group discount_amount_area" style="display: {{ $display_area }};">
					<div class="row">
						<div class="col-sm-2">
							<b>{{ isset($data['backendlang']['backendlang']['discount_amount']) ? $data['backendlang']['backendlang']['discount_amount'] :'' }}:</b> <span class="important-text">*</span>
						</div>
						<div class="col-sm-2">
							<select class="form-control" name="amount_type">
								@php
									$selectedValue = (isset($promotion)) ? $promotion->amount_type : old('amount_type');
								@endphp
								<option {{ $selectedValue == 'Percentage' ? 'selected' : '' }} value="Percentage">{{ isset($data['backendlang']['backendlang']['Percentage']) ? $data['backendlang']['backendlang']['Percentage'] :'' }}</option>
								<option {{ $selectedValue == 'Amount' ? 'selected' : '' }} value="Amount">(RM) {{ isset($data['backendlang']['backendlang']['Amount']) ? $data['backendlang']['backendlang']['Amount'] :'' }}</option>
							</select>
						</div>
						<div class="col-sm-8">
							<input type="text" class="form-control" name="amount" value="{{ isset($promotion) ? $promotion->amount : old('amount') }}" onkeypress="return isNumberKey(event)">
						</div>
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="row">
					<div class="col-sm-2">
						<b>{{ isset($data['backendlang']['backendlang']['Quantity']) ? $data['backendlang']['backendlang']['Quantity'] :'' }}:</b> <span class="important-text">*</span>
					</div>
					<div class="col-sm-10">
						<input type="text" name="quantity" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['Quantity']) ? $data['backendlang']['backendlang']['Quantity'] :'' }} *" value="{{ isset($promotion) ? $promotion->quantity : old('quantity') }}">
					</div>
				</div>
			</div>

			<div class="form-group non-product-voucher">
				<div class="form-group">
					<div class="row">
						<div class="col-sm-6">
							<div class="row">
								<div class="col-sm-4">
									<b>{{ isset($data['backendlang']['backendlang']['Start_Date']) ? $data['backendlang']['backendlang']['Start_Date'] :'' }}: <span class="important-text">*</span></b>
								</div>
								<div class="col-sm-8">
									<!-- <div class="input-group">
										<input id="date-timepicker1" type="text" class="form-control date-timepicker1" name="start_date" 
											   value="{{ isset($promotion) && !empty($promotion->start_date) ? date('m/d/Y h:i:s a', strtotime($promotion->start_date)) : '' }}" />
										<span class="input-group-addon" style="pointer-events: none;">
											<i class="fa fa-clock-o bigger-110"></i>
										</span>
									</div> -->
									<input
				                      	type="date"
				                      	class="form-control mb-3 flatpickr-no-config" id="start_date"
				                      	placeholder="{{ isset($promotion) && !empty($promotion->start_date) ? date('Y-m-d H:i:s', strtotime($promotion->start_date)) : (isset($data['backendlang']['backendlang']['Start_Date']) ? $data['backendlang']['backendlang']['Start_Date'] : 'Start date') }}"
				                      	name="start_date"
				                      	value="{{  isset($promotion) && !empty($promotion->start_date) ? date('Y-m-d H:i:s', strtotime($promotion->start_date)) : '	'}}" 
				                    />
								</div>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="row">
								<div class="col-sm-4">
									<b>{{ isset($data['backendlang']['backendlang']['End_Date']) ? $data['backendlang']['backendlang']['End_Date'] :'' }}:<span class="important-text">*</span></b>
								</div>
								<div class="col-sm-8">
									<!-- <div class="input-group">
										<input id="date-timepicker1" type="text" class="form-control date-timepicker1"  name="end_date" 
											   value="{{ isset($promotion) && !empty($promotion->end_date) ? date('m/d/Y h:i:s a', strtotime($promotion->end_date)) : '' }}" />
										<span class="input-group-addon" style="pointer-events: none;">
											<i class="fa fa-clock-o bigger-110"></i>
										</span>
									</div> -->

									<input
				                      	type="date"
				                      	class="form-control mb-3 flatpickr-no-config" id="end_date"
				                      	placeholder="{{ isset($promotion) && !empty($promotion->end_date) ? date('Y-m-d H:i:s', strtotime($promotion->end_date)) : (isset($data['backendlang']['backendlang']['End_Date']) ? $data['backendlang']['backendlang']['End_Date'] : 'End date') }}"
				                      	name="end_date"
				                      	value="{{ isset($promotion) && !empty($promotion->end_date) ? date('Y-m-d H:i:s', strtotime($promotion->end_date)) : '	' }}"
				                    />
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

				<div class="form-group ">
					<div class="row">
						<div class="col-sm-2">
							<b>{{ isset($data['backendlang']['backendlang']['Minimum_Spend']) ? $data['backendlang']['backendlang']['Minimum_Spend'] :'' }}:</b> 
						</div>
						<div class="col-sm-10">
							<input type="text" name="minSpend" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['Minimum_Spend']) ? $data['backendlang']['backendlang']['Minimum_Spend'] :'' }}" value="{{ isset($promotion) ? $promotion->minSpend : old('minSpend') }}">
						</div>
					</div>
				</div>

				<div class="form-group">
					<div class="row">
						<div class="col-sm-2">
							<b>{{ isset($data['backendlang']['backendlang']['Maximum_Capped']) ? $data['backendlang']['backendlang']['Maximum_Capped'] :'' }}:</b> 
						</div>
						<div class="col-sm-10">
							<input type="text" name="maxCapped" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['Maximum_Capped']) ? $data['backendlang']['backendlang']['Maximum_Capped'] :'' }}" value="{{ isset($promotion) ? $promotion->maxCapped : old('maxCapped') }}">	
						</div>
					</div>
				</div>
			
			<div class="form-group">
				<div class="row">
					<div class="col-sm-2">
						<b>{{ isset($data['backendlang']['backendlang']['Usage_Limit_Optional']) ? $data['backendlang']['backendlang']['Usage_Limit_Optional'] :'' }}:</b>
					</div>
					@php
						$checkedValue = (isset($promotion)) ? $promotion->limit_type : old('limit_type');
					@endphp
					<div class="col-sm-10">
						<label>
							<input name="limit_type" type="radio" value="1" class="ace limit_type" {{ $checkedValue == '1' ? 'checked' : '' }}  checked />
							<span class="lbl"> {{ isset($data['backendlang']['backendlang']['None_Until_Promotion_End']) ? $data['backendlang']['backendlang']['None_Until_Promotion_End'] :'' }}</span>
						</label>
	                    &nbsp;&nbsp;&nbsp;&nbsp;
	                    <label>
							<input name="limit_type" type="radio" value="2" class="ace limit_type" {{ $checkedValue == '2' ? 'checked' : '' }} />
							<span class="lbl">{{ isset($data['backendlang']['backendlang']['Daily']) ? $data['backendlang']['backendlang']['Daily'] :'' }}</span>
						</label>
	                    &nbsp;&nbsp;&nbsp;&nbsp;
	                    <label>
							<input name="limit_type" type="radio" value="3" class="ace limit_type" {{ $checkedValue == '3' ? 'checked' : '' }} />
							<span class="lbl"> {{ isset($data['backendlang']['backendlang']['Per_User']) ? $data['backendlang']['backendlang']['Per_User'] :'' }}</span>
						</label>
						<br>
						<div class="times-limit">
							@if($checkedValue == '2')
							 {{ isset($data['backendlang']['backendlang']['User_Able_To_Use']) ? $data['backendlang']['backendlang']['User_Able_To_Use'] :'' }} <input type="text" name="usage_limit" value="{{ isset($promotion) ? $promotion->usage_limit : old('usage_limit') }}">  {{ isset($data['backendlang']['backendlang']['Time_Per_Day']) ? $data['backendlang']['backendlang']['Time_Per_Day'] :'' }}
							@elseif($checkedValue == '3')
							 {{ isset($data['backendlang']['backendlang']['User_Able_To_Use_Total']) ? $data['backendlang']['backendlang']['User_Able_To_Use_Total'] :'' }} <input type="text" name="usage_limit" value="{{ isset($promotion) ? $promotion->usage_limit : old('usage_limit') }}">  {{ isset($data['backendlang']['backendlang']['Time_s']) ? $data['backendlang']['backendlang']['Time_s'] :'' }}
							@endif
						</div>
						
					</div>
				</div>
			</div>


			<div class="form-group">
				<div class="row">
					<div class="col-sm-2">
						<b>{{ isset($data['backendlang']['backendlang']['Products_Optional']) ? $data['backendlang']['backendlang']['Products_Optional'] :'' }}:</b>
					</div>
					<div class="col-sm-10">
						<select class="selectpicker form-control" data-live-search="true" multiple name="products[]">
							@php
								$promotion_products = isset($promotion) ? explode(',', $promotion->products) : [];
							@endphp
							@foreach($products as $product)
						  		<option {{in_array($product->id, $promotion_products ?: []) ? "selected": ""}} value="{{ $product->id }}" data-tokens="{{ $product->id }}">
						  			{{ $product->product_name }}
						  		</option>
						  	@endforeach
						</select>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>		

<div class="container-box form-group">
	<div class="">
		<h4>
			{{ isset($data['backendlang']['backendlang']['Assign_Voucher_To_Users']) ? $data['backendlang']['backendlang']['Assign_Voucher_To_Users'] : 'Assign Voucher To Users' }}
		</h4>
	</div>
		<form method="POST" action="">
			@csrf
				<div class="row">
					<div class="col-sm-5">
						<select class="form-control selectpicker" data-live-search="true" multiple name="users[]">
							@foreach($users as $user)
								<option value="{{ $user->code }}" data-tokens="{{ $user->code }}">{{ $user->f_name }} ({{ $user->code }})</option>
							@endforeach
							@if(isset($agents))
								@foreach($agents as $agent)
									<option value="{{ $agent->code }}" data-tokens="{{ $agent->code }}">{{ $agent->f_name }} ({{ $agent->code }})</option>
								@endforeach
							@endif
						</select>
					</div>
					<div class="col-sm-7">
						<div class="form-group">
							<input type="text" class="form-control" name="assign_quantity" placeholder="{{ isset($data['backendlang']['backendlang']['Quantity_To_Assign']) ? $data['backendlang']['backendlang']['Quantity_To_Assign'] : 'Quantity To Assign' }}" onkeypress="return isNumberKey(event)">
						</div>
					</div>
				</div>
				<div class="form-group">
					<textarea class="form-control" name="remark" placeholder="{{ isset($data['backendlang']['backendlang']['Remark_Optional']) ? $data['backendlang']['backendlang']['Remark_Optional'] : 'Remark (Optional)' }}">{{ old('remark') }}</textarea>
				</div>
		</form>
	</div>
	@if(Request::segment(2) != 'create')
			<div class="container-box">
				<h4>{{ isset($data['backendlang']['backendlang']['Assignment_History_List']) ? $data['backendlang']['backendlang']['Assignment_History_List'] : 'Assignment History List' }}</h4>
				<hr>
				<div class="row">
					<div class="col-sm-12">
						<form method="GET" action="">
							<div class="row align-items-center mb-4">
								<div class="col-sm-3">
									<select name="user_id" class="form-control select2 promotion-user-select" style="width:100%;" data-placeholder="{{ isset($data['backendlang']['backendlang']['Select_User']) ? $data['backendlang']['backendlang']['Select_User'] : 'Select User' }}">
										<option value="">{{ isset($data['backendlang']['backendlang']['Select_User']) ? $data['backendlang']['backendlang']['Select_User'] : 'Select User' }}</option>
										@foreach($users as $user)
											<option value="{{ $user->code }}" {{ request('user_id') == $user->code ? 'selected' : '' }}>
												{{ $user->f_name }} ({{ $user->code }}) 
											</option>
										@endforeach
										@if(isset($agents))
											@foreach($agents as $agent)
												<option value="{{ $agent->code }}" {{ request('user_id') == $agent->code ? 'selected' : '' }}>
													{{ $agent->f_name }} ({{ $agent->code }})
												</option>
											@endforeach
										@endif
									</select>
								</div>
								<div class="col-sm-4">
									<input type="text" class="form-control" name="dates" value="{{ !empty(request('dates')) ? request('dates') : (($startDate ?? '') . ' - ' . ($endDate ?? '')) }}">
								</div>
								<div class="col-sm-4">
									<button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> {{ isset($data['backendlang']['backendlang']['Search']) ? $data['backendlang']['backendlang']['Search'] : 'Search' }}</button>
									<a href="{{ url()->current() }}" class="btn btn-warning"><i class="bi bi-arrow-clockwise"></i> {{ isset($data['backendlang']['backendlang']['Clear_Search']) ? $data['backendlang']['backendlang']['Clear_Search'] : 'Clear Search' }}</a>
								</div>
							</div>
								<div class="form-group">
									<div class="row">
										<div class="col-sm-2">
											<label>{{ isset($data['backendlang']['backendlang']['Item_Per_Page']) ? $data['backendlang']['backendlang']['Item_Per_Page'] :'' }}:</label>
											<select class="input-small" name="per_page">
												<option {{ (!empty(request('per_page')) && request('per_page') == '10') ? 'selected' : '' }} value="10">10</option>
												<option {{ (!empty(request('per_page')) && request('per_page') == '20') ? 'selected' : '' }} value="20">20</option>
												<option {{ (!empty(request('per_page')) && request('per_page') == '50') ? 'selected' : '' }} value="50">50</option>
											</select>
										</div>
									</div>
								</div>
							</form>
						<div class="form-group">
							<table class="table table-bordered">
								<thead>
									<tr class="info">
										<th>#</th>
										<th style="width: 20%">{{ isset($data['backendlang']['backendlang']['User']) ? $data['backendlang']['backendlang']['User'] : 'User' }}</th>
										<th>{{ isset($data['backendlang']['backendlang']['Quantity_Assigned']) ? $data['backendlang']['backendlang']['Quantity_Assigned'] : 'Quantity Assigned' }}</th>
										<th>{{ isset($data['backendlang']['backendlang']['Remark']) ? $data['backendlang']['backendlang']['Remark'] : 'Remark' }}</th>
										<th>{{ isset($data['backendlang']['backendlang']['Created_At']) ? $data['backendlang']['backendlang']['Created_At'] : 'Created At' }}</th>
										<th>{{ isset($data['backendlang']['backendlang']['Updated_At']) ? $data['backendlang']['backendlang']['Updated_At'] : 'Updated At' }}</th>
										<th>{{ isset($data['backendlang']['backendlang']['Created_By']) ? $data['backendlang']['backendlang']['Created_By'] : 'Created By' }}</th>
									</tr>
								</thead>
								<tbody>
									@if(isset($assignmentHistory) && count($assignmentHistory) > 0)
										@foreach($assignmentHistory as $key => $assign)
											<tr>
												<td>{{ $key+1 }}</td>
												<td>
													@if($assign->userByCode)
														{{ $assign->userByCode->f_name }} ({{ $assign->userByCode->code }})
													@elseif($assign->agentByCode)
														{{ $assign->agentByCode->f_name }} ({{ $assign->agentByCode->code }})
													@else
														{{ $assign->user_id }}
													@endif
												</td>
												<td style="text-align: center;">1</td>
												<td style="text-align: center;">{{ $assign->remark ? $assign->remark : '-' }}</td>
												<td>{{ $assign->created_at }}</td>
												<td>{{ $assign->updated_at }}</td>
												<td>{{ $assign->admin ? ($assign->admin->f_name . ' (' . $assign->admin->code . ')') : '-' }}</td>
											</tr>
										@endforeach
									@else
										<tr>
											<td colspan="7">{{ isset($data['backendlang']['backendlang']['No_Result_Found']) ? $data['backendlang']['backendlang']['No_Result_Found'] : 'No Result Found' }}</td>
										</tr>
									@endif
								</tbody>
							</table>
							{{ $assignmentHistory->links() }}
						</div>
					</div>
				</div>
			</div>
	@endif

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function(){
    var els = document.querySelectorAll('select.select2');
    els.forEach(function(el){
        $(el).select2({
            width: 'resolve'
        });
    });
});
</script>





