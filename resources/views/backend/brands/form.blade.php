<div class="container-box form-group">
	<div class="row">
		<div class="col-12">
			<div class="form-group">
				<div class="row">
					<div class="col-sm-2">
						<b>{{ isset($data['backendlang']['backendlang']['Name']) ? $data['backendlang']['backendlang']['Name'] :'' }}:</b> <span class="important-text">*</span>
					</div>
					<div class="col-sm-10">
						<input type="text" class="form-control" name="brand_name" value="{{ isset($brand) ? $brand->brand_name : old('brand_name') }}" placeholder="{{ isset($data['backendlang']['backendlang']['Name']) ? $data['backendlang']['backendlang']['Name'] :'' }} *">
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-12">
			<div class="form-group">
				<div class="row">
					<div class="col-sm-2">
						<b>{{ isset($data['backendlang']['backendlang']['Short_Description']) ? $data['backendlang']['backendlang']['Short_Description'] :'' }}:</b> <span class="important-text">*</span>
					</div>
					<div class="col-sm-10">
						<input type="text" class="form-control" name="short_description" value="{{ isset($brand) ? $brand->short_description : old('short_description') }}" placeholder="{{ isset($data['backendlang']['backendlang']['Short_Description']) ? $data['backendlang']['backendlang']['Short_Description'] :'' }} *">
					</div>
				</div>
			</div>
		</div>
	</div>
</div>