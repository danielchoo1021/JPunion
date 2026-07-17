@extends('layouts.admin_app')

@section('content')
<div class="container-box form-group">
	<div class="page-header">
	    <h3>
	        {{ $product->product_name }}
	        <span>
	            <i class="ace-icon fa fa-angle-double-right"></i>
	           	{{ isset($data['backendlang']['backendlang']['Stock_Management']) ? $data['backendlang']['backendlang']['Stock_Management'] :'' }}
	        </span>
	    </h3>
	</div>
	<hr>
	<form method="POST" action="{{ route('stock', $product->id) }}">
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
					{{ isset($data['backendlang']['backendlang']['Quantity']) ? $data['backendlang']['backendlang']['Quantity'] :'' }}
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
					<br>
					@if($product->second_variation_enable == 0)
					<span class="badge badge-pill bg-primary stockBalance" style="font-size: 15px;">
							{{ isset($data['backendlang']['backendlang']['Quantity_Left']) ? $data['backendlang']['backendlang']['Quantity_Left'] :'' }}: {{ $variation_stocks[$variation->id] }}						
					</span>
					@endif
				</td>
				@if($product->second_variation_enable == 0)
				<td>
					<input type="hidden" name="variation_id[]" value="{{ $variation->id }}">
					<select class="form-control" name="type[]">
						<option {{ (old('type')[$key] ?? '' == 'Increase') ? 'selected' : '' }} value="Increase">{{ isset($data['backendlang']['backendlang']['Increase']) ? $data['backendlang']['backendlang']['Increase'] :'' }}</option>
						<option {{ (old('type')[$key] ?? '' == 'Decrease') ? 'selected' : '' }} value="Decrease">{{ isset($data['backendlang']['backendlang']['Decrease']) ? $data['backendlang']['backendlang']['Decrease'] :'' }}</option>
					</select>
				</td>
				<td>
					<input type="text" class="form-control" name="quantity[]" placeholder="{{ isset($data['backendlang']['backendlang']['Quantity']) ? $data['backendlang']['backendlang']['Quantity'] :'' }}" onkeypress="return isNumberKey(event)" value="{{ old('quantity')[$key] ?? '' }}">
				</td>
				<td>
					<textarea class="form-control" name="remark[]" placeholder="{{ isset($data['backendlang']['backendlang']['Remark_Optional']) ? $data['backendlang']['backendlang']['Remark_Optional'] :'' }}">{{ old('remark')[$key] ?? '' }}</textarea>
				</td>
				@endif
			</tr>
				@foreach($variation->get_second_variations as $second_variation)
					<tr>
						@if($product->second_variation_enable == 1)
						<td>
							<input type="hidden" name="variation_id[]" value="{{ $variation->id }}">
							<input type="hidden" name="second_variation_id[]" value="{{ $second_variation->id }}">
							{{ $second_variation->variation_name }}
							<br>
							<span class="badge badge-pill bg-primary stockBalance" style="font-size: 15px;">
									{{ isset($data['backendlang']['backendlang']['Quantity_Left']) ? $data['backendlang']['backendlang']['Quantity_Left'] :'' }}: {{ $variation_second_stocks[$variation->id][$second_variation->id] }}
							</span>
						</td>
						
						<td>
							<select class="form-control" name="type[]">
								<option {{ old('type')[$key_two] ?? '' == 'Increase' ? 'selected' : '' }} value="Increase">{{ isset($data['backendlang']['backendlang']['Increase']) ? $data['backendlang']['backendlang']['Increase'] :'' }}</option>
								<option {{ old('type')[$key_two] ?? '' == 'Decrease' ? 'selected' : '' }} value="Decrease">{{ isset($data['backendlang']['backendlang']['Decrease']) ? $data['backendlang']['backendlang']['Decrease'] :'' }}</option>
							</select>
						</td>
						<td>
							<input type="text" class="form-control" name="quantity[]" placeholder="{{ isset($data['backendlang']['backendlang']['Quantity']) ? $data['backendlang']['backendlang']['Quantity'] :'' }}" onkeypress="return isNumberKey(event)" value="{{ old('quantity')[$key_two] ?? '' }}">
						</td>
						<td>
							<textarea class="form-control" name="remark[]" placeholder="{{ isset($data['backendlang']['backendlang']['Remark_Optional']) ? $data['backendlang']['backendlang']['Remark_Optional'] :'' }}">{{ old('remark')[$key_two] ?? "" }}</textarea>
						</td>
						@endif
					</tr>
					@php
					$key_two++;
					@endphp
				@endforeach
			@endforeach
		</table>
		@else
		<div class="form-group">
			<span class="badge badge-pill bg-primary stockBalance" style="font-size: 15px;">{{ isset($data['backendlang']['backendlang']['Quantity_Left']) ? $data['backendlang']['backendlang']['Quantity_Left'] :'' }}: {{ $stockBalance }}</span>
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
							<input type="text" class="form-control" name="quantity[]" placeholder="{{ isset($data['backendlang']['backendlang']['Quantity']) ? $data['backendlang']['backendlang']['Quantity'] :'' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Price']) ? $data['backendlang']['backendlang']['Price'] :'' }} *" onkeypress="return isNumberKey(event)"
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
					<i class="fa fa-ban"> {{ isset($data['backendlang']['backendlang']['Cancel']) ? $data['backendlang']['backendlang']['Cancel'] :'' }}</i>
				</a>

				<button class="btn btn-outline-primary">
					<i class="fa fa-check"> {{ isset($data['backendlang']['backendlang']['Create']) ? $data['backendlang']['backendlang']['Create'] :'' }}</i>
				</button>

			</div>
		</div>
	</form>
</div>

<div class="container-box">
	<h4>{{ isset($data['backendlang']['backendlang']['Stock_History_List']) ? $data['backendlang']['backendlang']['Stock_History_List'] :'' }}</h4>
	<hr>
	<div class="row">
		<div class="col-sm-12">
			<form method="GET" action="{{ route('stock', $product->id) }}">
				<div class="form-group">
					<div class="row">
						<div class="col-sm-2">
							<div class="form-group">
								<select class="form-control" name="type">
									<option value="">{{ isset($data['backendlang']['backendlang']['Select_Stock_Type']) ? $data['backendlang']['backendlang']['Select_Stock_Type'] :'' }}</option>
									<option {{ (!empty(request('type')) && request('type') == 'Increase') ? 'selected' : '' }} value="Increase">
										{{ isset($data['backendlang']['backendlang']['Stock_In']) ? $data['backendlang']['backendlang']['Stock_In'] :'' }}
									</option>
									<option {{ (!empty(request('type')) && request('type') == 'Decrease') ? 'selected' : '' }} value="Decrease">
										{{ isset($data['backendlang']['backendlang']['Stock_Out']) ? $data['backendlang']['backendlang']['Stock_Out'] :'' }}
									</option>
								</select>
							</div>
						</div>
						<div class="col-sm-4">
							<button class="btn btn-outline-primary btn-sm">
								<i class="fa fa-search"></i> {{ isset($data['backendlang']['backendlang']['Search']) ? $data['backendlang']['backendlang']['Search'] :'' }}
							</button>
							<a href="{{ route('stock', $product->id) }}" class="btn btn-warning btn-sm">
								<i class="fa fa-refresh"></i> {{ isset($data['backendlang']['backendlang']['Clear_Search']) ? $data['backendlang']['backendlang']['Clear_Search'] :'' }}
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
						@if(!$stocks->isEmpty())
						@foreach($stocks as $key => $stock)
						<tr>
							<td>{{ $key+1 }}</td>
							<td>{{ $stock->type }}</td>
							@if(!empty($product->variation_enable))
							<td>
								{{ !empty($stock->get_variation_det->variation_name) ? $stock->get_variation_det->variation_name : '' }}
							</td>
							@endif
							@if(!empty($product->second_variation_enable))
							<td>{{ !empty($stock->get_second_variation_det->variation_name) ? $stock->get_second_variation_det->variation_name : '' }}</td>
							@endif
							<td>{{ $stock->quantity }}</td>
							<td>{{ $stock->remark }}</td>
							<td>{{ $stock->created_at }}</td>
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
				{{ $stocks->links() }}
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