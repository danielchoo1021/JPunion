<div class="container-box">
	<div class="row">
		<div class="col-6">
			<div class="form-group">
				<div class="row">
					<div class="col-sm-2">
						<b>{{ isset($data['backendlang']['backendlang']['Category']) ? $data['backendlang']['backendlang']['Category'] :'' }}:</b><span class="important-text">*</span>
					</div>
					<div class="col-sm-10">
						@php
							$checkedBox = isset($sub_category) ? $sub_category->category_id : old('category_id');
						@endphp
						<select class="form-control" name="category_id">
							<option value="">{{ isset($data['backendlang']['backendlang']['Select_Category']) ? $data['backendlang']['backendlang']['Select_Category'] :'' }}</option>
							@foreach($categories as $category)
							<option {{ ($checkedBox == $category->id) ? 'selected' : '' }} value="{{ $category->id }}">{{ $category->category_name }}</option>
							@endforeach
						</select>
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="row">
					<div class="col-sm-2">
						<b>{{ isset($data['backendlang']['backendlang']['Code']) ? $data['backendlang']['backendlang']['Code'] :'' }}:</b> <span class="important-text">*</span>
					</div>
					<div class="col-sm-10">
						<input type="text" class="form-control" name="sub_category_code" value="{{ isset($sub_category) ? $sub_category->sub_category_code : old('sub_category_code') }}" placeholder="{{ isset($data['backendlang']['backendlang']['Code']) ? $data['backendlang']['backendlang']['Code'] :'' }} *">
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="row">
					<div class="col-sm-2">
						<b>{{ isset($data['backendlang']['backendlang']['Name']) ? $data['backendlang']['backendlang']['Name'] :'' }}:</b> <span class="important-text">*</span>
					</div>
					<div class="col-sm-10">
						<input type="text" class="form-control" name="sub_category_name" value="{{ isset($sub_category) ? $sub_category->sub_category_name : old('sub_category_name') }}" placeholder="{{ isset($data['backendlang']['backendlang']['Name']) ? $data['backendlang']['backendlang']['Name'] :'' }} *">
					</div>
				</div>
			</div>
		</div>
	</div>
</div>