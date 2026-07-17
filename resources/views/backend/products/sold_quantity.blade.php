@extends('layouts.admin_app')

@section('content')
<div class="container-box form-group">
	<div class="page-header">
	    <h3>
	        {{ $product->product_name }}
	        <span>
	            <i class="ace-icon bi bi-angle-double-right"></i>
	           	{{ isset($data['backendlang']['backendlang']['Sold_Quantity']) ? $data['backendlang']['backendlang']['Sold_Quantity'] :'' }}
	        </span>
	    </h3>
	</div>
	<hr>
	<form method="POST" action="{{ route('submit_sold_quantity', $product->id) }}">
		@csrf
		@if($errors->any())
		  	<div class="alert alert-danger">{!! implode('<br/>', $errors->all(':message')) !!}</div>
		@endif
		@if($product->variation_enable == 1 && !empty($product->get_variations))
		<table class="table table-bordered">
			<tr>
				<th>
					{{ $product->variation_title }}
				</th>
				@if($product->second_variation_enable == 1)
				<th>
					{{ $product->second_variation_title }}
				</th>
				@endif
				<th>
					{{ isset($data['backendlang']['backendlang']['Type']) ? $data['backendlang']['backendlang']['Type'] :'' }}
				</th>
				<th>
					{{ isset($data['backendlang']['backendlang']['Sold_Quantity']) ? $data['backendlang']['backendlang']['Sold_Quantity'] :'' }}
				</th>
				<th>
					{{ isset($data['backendlang']['backendlang']['Remark']) ? $data['backendlang']['backendlang']['Remark'] :'' }}
				</th>
			</tr>

			@php
			$key_two = 0;
			@endphp
			@foreach($product->get_variations as $key => $variation)
			<tr>
				<td rowspan="{{ count($variation->get_second_variations)+1 }}">
					{{ $variation->variation_name }} 
				</td>
				@if($variation->get_second_variations->isEmpty())
				<td>
					<input type="hidden" name="variation_id[]" value="{{ $variation->id }}">
					<select class="form-control" name="type[]">
						<option {{ (old('type')[$key] ?? '' == 'Increase') ? 'selected' : '' }} value="Increase">{{ isset($data['backendlang']['backendlang']['Incerase']) ? $data['backendlang']['backendlang']['Increase'] :'' }}</option>
						<option {{ (old('type')[$key] ?? '' == 'Decrease') ? 'selected' : '' }} value="Decrease">{{ isset($data['backendlang']['backendlang']['Decrease']) ? $data['backendlang']['backendlang']['Decrease'] :'' }}</option>
					</select>
				</td>
				<td>
					<input type="text" class="form-control" name="quantity[]" placeholder="{{ isset($data['backendlang']['backendlang']['Sold_Quantity']) ? $data['backendlang']['backendlang']['Sold_Quantity'] :'' }}" onkeypress="return isNumberKey(event)" value="{{ old('quantity')[$key] ?? '' }}">
					<div class="form-group" align="right">
						<br>
						<span class="badge badge-pill bg-primary stockBalance" style="font-size: 15px;">
								{{ isset($data['backendlang']['backendlang']['Quantity_Sold']) ? $data['backendlang']['backendlang']['Quantity_Sold'] :'' }}: {{ $variation_sold[$variation->id] }}						
						</span>
					</div>
				</td>
				<td>
					<textarea class="form-control" name="remark[]" placeholder="{{ isset($data['backendlang']['backendlang']['Remark_Optional']) ? $data['backendlang']['backendlang']['Remark_Optional'] :'' }}">{{ old('remark')[$key] ?? '' }}</textarea>
				</td>
				@endif
			</tr>
				@foreach($variation->get_second_variations as $second_variation)
					<tr>
						<td>
							<input type="hidden" name="variation_id[]" value="{{ $variation->id }}">
							<input type="hidden" name="second_variation_id[]" value="{{ $second_variation->id }}">
							{{ $second_variation->variation_name }}
						</td>
						<td>
							<select class="form-control" name="type[]">
								<option {{ old('type')[$key_two] ?? '' == 'Increase' ? 'selected' : '' }} value="Increase">{{ isset($data['backendlang']['backendlang']['Increase']) ? $data['backendlang']['backendlang']['Increase'] :'' }}</option>
								<option {{ old('type')[$key_two] ?? '' == 'Decrease' ? 'selected' : '' }} value="Decrease">{{ isset($data['backendlang']['backendlang']['Decrease']) ? $data['backendlang']['backendlang']['Decrease'] :'' }}</option>
							</select>
						</td>
						<td>
							<input type="text" class="form-control" name="quantity[]" placeholder="{{ isset($data['backendlang']['backendlang']['Sold_Quantity']) ? $data['backendlang']['backendlang']['Sold_Quantity'] :'' }}" onkeypress="return isNumberKey(event)" value="{{ old('quantity')[$key_two] ?? '' }}">
							<div class="form-group" align="right">
								<br>
								<span class="badge badge-pill bg-primary stockBalance" style="font-size: 15px;">
										{{ isset($data['backendlang']['backendlang']['Sold_Quantity']) ? $data['backendlang']['backendlang']['Sold_Quantity'] :'' }}: {{ $variation_second_sold[$variation->id][$second_variation->id] }}
								</span>
							</div>
						</td>
						<td>
							<textarea class="form-control" name="remark[]" placeholder="{{ isset($data['backendlang']['backendlang']['Remark_Optional']) ? $data['backendlang']['backendlang']['Remark_Optional'] :'' }}">{{ old('remark')[$key_two] ?? "" }}</textarea>
						</td>
					</tr>
					@php
					$key_two++;
					@endphp
				@endforeach
			@endforeach
		</table>
		@else
		<div class="form-group">
			<span class="badge badge-pill bg-primary stockBalance" style="font-size: 15px;">{{ isset($data['backendlang']['backendlang']['Sold_Quantity']) ? $data['backendlang']['backendlang']['Sold_Quantity'] :'' }}: {{ $soldBalance }}</span> <!-- here to edit the display -->
		</div>
		<div class="row">
			<div class="col-sm-6">
				<div class="row">
					<div class="col-sm-3">
						<select class="form-control" name="type[]">
							<option {{ (old('type')[0] ?? '') == 'Increase' ? 'selected' : '' }} value="Increase">{{ isset($data['backendlang']['backendlang']['Increase']) ? $data['backendlang']['backendlang']['Increase'] :'' }}</option>
							<option {{ (old('type')[0] ?? '') == 'Decrease' ? 'selected' : '' }} value="Decrease">{{ isset($data['backendlang']['backendlang']['Decrease']) ? $data['backendlang']['backendlang']['Decrease'] :'' }}</option>
						</select>
					</div>
					<div class="col-sm-3">
						<div class="form-group">
							<input type="text" class="form-control" name="quantity[]" placeholder="{{ isset($data['backendlang']['backendlang']['Sold_Quantity']) ? $data['backendlang']['backendlang']['Sold_Quantity'] :'' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Sold_Quantity']) ? $data['backendlang']['backendlang']['Sold_Quantity'] :'' }} *" onkeypress="return isNumberKey(event)"
								   value="{{ old('quantity')[0] ?? '' }}">
						</div>
					</div>
				</div>

				<div class="form-group">
					<textarea class="form-control" name="remark[]" placeholder="{{ isset($data['backendlang']['backendlang']['Remark_Optional']) ? $data['backendlang']['backendlang']['Remark_Optional'] :'' }}">{{ old('remark')[0] ?? '' }}</textarea>
				</div>
			</div>
		</div>
		@endif
		
		<div class="submit-form-btn">
			<div class="form-group wizard-actions" align="right">
				<a href="{{ route('product.products.index') }}" class="btn btn-outline-danger">
					<i class="bi bi-ban"> {{ isset($data['backendlang']['backendlang']['Cancel']) ? $data['backendlang']['backendlang']['Cancel'] :'' }}</i>
				</a>

				<button class="btn btn-outline-primary">
					<i class="bi bi-check"> {{ isset($data['backendlang']['backendlang']['Create']) ? $data['backendlang']['backendlang']['Create'] :'' }}</i>
				</button>

			</div>
		</div>
	</form>
</div>
<div class="container-box">
	<h4>{{ isset($data['backendlang']['backendlang']['Sold_Quantity_Adjustments']) ? $data['backendlang']['backendlang']['Sold_Quantity_Adjustments'] :'' }}</h4>
	<hr>
	<div class="row">
		<div class="col-sm-12">
			<form method="GET" action="{{ route('sold_quantity', $product->id) }}">
				<div class="form-group">
					<div class="row">
						<div class="col-sm-2">
							<div class="form-group">
								<select class="form-control" name="type">
									<option value="">{{ isset($data['backendlang']['backendlang']['Select_Adjustment_Type']) ? $data['backendlang']['backendlang']['Select_Adjustment_Type'] :'' }}</option>
									<option {{ (!empty(request('type')) && request('type') == 'Increase') ? 'selected' : '' }} value="Increase">
										{{ isset($data['backendlang']['backendlang']['Increase']) ? $data['backendlang']['backendlang']['Increase'] :'' }}
									</option>
									<option {{ (!empty(request('type')) && request('type') == 'Decrease') ? 'selected' : '' }} value="Decrease">
										{{ isset($data['backendlang']['backendlang']['Decrease']) ? $data['backendlang']['backendlang']['Decrease'] :'' }}
									</option>
								</select>
							</div>
						</div>
						<div class="col-sm-4">
							<button class="btn btn-outline-primary btn-sm">
								<i class="bi bi-search"></i> {{ isset($data['backendlang']['backendlang']['Search']) ? $data['backendlang']['backendlang']['Search'] :'' }}
							</button>
							<a href="{{ route('sold_quantity', $product->id) }}" class="btn btn-warning btn-sm">
								<i class="bi bi-refresh"></i> {{ isset($data['backendlang']['backendlang']['Clear_Search']) ? $data['backendlang']['backendlang']['Clear_Search'] :'' }}
							</a>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="row">
						<div class="col-sm-2">
							<div class="form-group">
								{{ isset($data['backendlang']['backendlang']['Item_Per_Page']) ? $data['backendlang']['backendlang']['Item_Per_Page'] :'' }}: <br>
								<select class="input-small" name="per_page">
									<option {{ (!empty(request('per_page')) && request('per_page') == '10') ? 'selected' : '' }} value="10">10</option>
									<option {{ (!empty(request('per_page')) && request('per_page') == '20') ? 'selected' : '' }} value="20">20</option>
									<option {{ (!empty(request('per_page')) && request('per_page') == '50') ? 'selected' : '' }} value="50">50</option>
								</select>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
		<div class="col-sm-12">
			<div class="form-group">
				<table class="table table-bordered">
					<thead>
						<tr class="info">
							<th>#</th>
							<th>{{ isset($data['backendlang']['backendlang']['Type']) ? $data['backendlang']['backendlang']['Type'] :'' }}</th>
							@if(!empty($product->variation_enable))
							<th>{{ isset($data['backendlang']['backendlang']['Variation']) ? $data['backendlang']['backendlang']['Variation'] :'' }}</th>
							@endif
							@if(!empty($product->second_variation_enable))
							<th>{{ isset($data['backendlang']['backendlang']['Second_Variation']) ? $data['backendlang']['backendlang']['Second_Variation'] :'' }}</th>
							@endif
							<th>{{ isset($data['backendlang']['backendlang']['Quantity']) ? $data['backendlang']['backendlang']['Quantity'] :'' }}</th>
							<th>{{ isset($data['backendlang']['backendlang']['Remark']) ? $data['backendlang']['backendlang']['Remark'] :'' }}</th>
							<th>{{ isset($data['backendlang']['backendlang']['Created']) ? $data['backendlang']['backendlang']['Created'] :'' }}</th>
						</tr>
					</thead>
					<tbody>
						@if(!$adjustments->isEmpty())
						@foreach($adjustments as $key => $adjustment)
						<tr>
							<td>{{ $key+1 }}</td>
							<td>{{  $adjustment->type == 'Increase' ? ($data['backendlang']['backendlang']['Increase'] ?? 'Increase') : ($data['backendlang']['backendlang']['Decrease'] ?? 'Decrease')}}</td>
							@if(!empty($product->variation_enable))
							<td>
								{{ !empty($adjustment->get_variation_det->variation_name) ? $adjustment->get_variation_det->variation_name : '' }}
							</td>
							@endif
							@if(!empty($product->second_variation_enable))
							<td>{{ !empty($adjustment->get_second_variation_det->variation_name) ? $adjustment->get_second_variation_det->variation_name : '' }}</td>
							@endif
							<td>{{ $adjustment->quantity }}</td>
							<td>{{ $adjustment->remark }}</td>
							<td>{{ $adjustment->created_at }}</td>
						</tr>
						@endforeach
						@else
						<tr>
							<td colspan="5">
								{{ isset($data['backendlang']['backendlang']['No_Result_Found']) ? $data['backendlang']['backendlang']['No_Result_Found'] :'' }}
							</td>
						</tr>
						@endif
					</tbody>
				</table>
				{{ $adjustments->links() }}
			</div>
		</div>
	</div>
</div>
@endsection

@section('js')
<script type="text/javascript">
	var variation_enable = '{{ $product->variation_enable }}';
  	var second_variation_enable = '{{ $product->second_variation_enable }}';

	$('.variations').on('change', function(){
		$('.loading-gif').show();
		var ele = $(this);

		vid = ele.val();
		pid = '{{ $product->id }}';

		var fd = new FormData();
			fd.append('vid', vid);
			fd.append('pid', pid);

		if(second_variation_enable == 1){
			$.ajax({
		        url: '{{ route("getSecondVariationDropdown") }}',
		        type: 'post',
		        data: fd,
		        contentType: false,
		        processData: false,
		        success: function(response){
		        	$('.loading-gif').hide();
		        	$('.second_variation').empty();
		        	$('.second_variation').append(response);
		        }
		    });
		}else{
			$.ajax({
		        url: '{{ route("getVariationAndStock") }}',
		        type: 'post',
		        data: fd,
		        contentType: false,
		        processData: false,
		        success: function(response){
		        	$('.loading-gif').hide();
		        	$('.stock-balance').html("{{ isset($data['backendlang']['backendlang']['Quantity_Left']) ? $data['backendlang']['backendlang']['Quantity_Left'] :'' }}: "+response);
		        }
		    });
		}
	});
</script>
@endsection