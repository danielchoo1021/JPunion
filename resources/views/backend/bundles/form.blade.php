@php
if(isset($bundle)){
	$action_url = route('bundle.bundles.update', $bundle->id);
}else{
	$action_url = route('bundle.bundles.store');
}

@endphp
<div class="row">
	<div class="col-12">
		<form method="POST" action="{{ $action_url }}" id="bundle-form">
			@csrf
			@if(isset($bundle))
			@method('PUT')
			@endif
			
			<div class="form-group">
				<div class="row">
					<div class="col-sm-2">
						{{ isset($data['backendlang']['backendlang']['Name']) ? $data['backendlang']['backendlang']['Name'] :'' }}: <span class="important-text">*</span>
					</div>
					<div class="col-sm-10">
						<input type="text" class="form-control" name="bundle_name" value="{{ isset($bundle) ? $bundle->bundle_name : old('bundle_name') }}" placeholder="{{ isset($data['backendlang']['backendlang']['Name']) ? $data['backendlang']['backendlang']['Name'] :'' }} *">
					</div>
				</div>
			</div>
			
			<div class="form-group">
				<div class="row">
					<div class="col-sm-2">
						{{ isset($data['backendlang']['backendlang']['Description']) ? $data['backendlang']['backendlang']['Description'] :'' }}: <span class="important-text">*</span>
					</div>
					<div class="col-sm-10">
						<textarea class="form-control" name="bundle_description" id="description">{!! isset($bundle) ? $bundle->bundle_description : old('bundle_description') !!}</textarea>
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="row">
					<div class="col-sm-2">
						{{ isset($data['backendlang']['backendlang']['Short_Description']) ? $data['backendlang']['backendlang']['Short_Description'] :'' }}:
					</div>
					<div class="col-sm-10">
						<input type="text" class="form-control" name="short_description" value="{{ isset($bundle) ? $bundle->short_description : old('short_description') }}" placeholder="{{ isset($data['backendlang']['backendlang']['Short_Description']) ? $data['backendlang']['backendlang']['Short_Description'] :'' }}">
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="row">
					<div class="col-sm-2">
						{{ isset($data['backendlang']['backendlang']['Price']) ? $data['backendlang']['backendlang']['Price'] :'' }}:
					</div>
					<div class="col-sm-10">
						<input type="text" class="form-control" name="bundle_price" value="{{ isset($bundle) ? $bundle->bundle_price : old('bundle_price') }}" placeholder="{{ isset($data['backendlang']['backendlang']['Price']) ? $data['backendlang']['backendlang']['Price'] :'' }}">
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="row">
					<div class="col-sm-2">
						{{ isset($data['backendlang']['backendlang']['Agent_Price']) ? $data['backendlang']['backendlang']['Agent_Price'] :'' }}:
					</div>
					<div class="col-sm-10">
						<input type="text" class="form-control" name="bundle_agent_price" value="{{ isset($bundle) ? $bundle->bundle_agent_price : old('bundle_agent_price') }}" placeholder="{{ isset($data['backendlang']['backendlang']['Agent_Price']) ? $data['backendlang']['backendlang']['Agent_Price'] :'' }}">
					</div>
				</div>
			</div>
			<hr>
			<h3>  {{ isset($data['backendlang']['backendlang']['Items_in_the_bundle']) ? $data['backendlang']['backendlang']['Items_in_the_bundle'] :'' }}</h3>
			<div class="form-group parent-row" align="center">
				<div class="child-row">
					@foreach($bundle_details as $detail)
					<div class="row">
						<div class="col-sm-6">
							<input type="hidden" name="bid[]" value="{{ $detail->id }}">
							<select class="form-control" name="product_id[]">
								<option value="">Select Product</option>
								@foreach($products as $product)
								<option {{ ($detail->product_id == $product->id) ? 'selected' : '' }} 
										value="{{ $product->id }}">
										{{ $product->product_name }}
								</option>
								@endforeach
							</select>
						</div>
					</div>
					<hr>
					@endforeach
					<div class="row">
						<div class="col-sm-6">
							<input type="hidden" name="bid[]">
							<select class="form-control" name="product_id[]">
								<option value="">{{ isset($data['backendlang']['backendlang']['Select_Product']) ? $data['backendlang']['backendlang']['Select_Product'] :'' }}</option>
								@foreach($products as $product)
								<option value="{{ $product->id }}">{{ $product->product_name }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<hr>
				</div>
				<div class="row">
					<div class="col-sm-6">
						<a href="#" class="add-shipping-btn" id="add-west">
							<i class="fa fa-plus"></i>
						</a>
					</div>
				</div>
			</div>
		</form>
		<hr>
	</div>
</div>
