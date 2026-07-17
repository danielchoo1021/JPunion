<div class="row">
	<div class="col-12">
		@php
			$checkedProduct = isset($feedback) ? $feedback->products : old('products');
		@endphp
		<div class="form-group">
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label>{{ isset($data['backendlang']['backendlang']['products']) ? $data['backendlang']['backendlang']['products'] :'' }} <span class="important-text">*</span></label>
						<select class="form-control" name="products">
							<option {{ ($checkedProduct == 1) ? 'selected' : '' }} value="1">Lady G</option>
							<option {{ ($checkedProduct == 2) ? 'selected' : '' }}  value="2">Triple Peptides</option>
						</select>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label>{{ isset($data['backendlang']['backendlang']['Title']) ? $data['backendlang']['backendlang']['Title'] :'' }} <span class="important-text">*</span></label>
						<input type="text" class="form-control required-field" name="title" placeholder="{{ isset($data['backendlang']['backendlang']['Title']) ? $data['backendlang']['backendlang']['Title'] :'' }} *" value="{{ isset($feedback) ? $feedback->title : old('title') }}">
					</div>
				</div>
			</div>
		</div>
	</div>
</div>