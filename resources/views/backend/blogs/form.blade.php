<div class="container-box form-group mb-5 pb-5">
	<div class="row">
		<div class="col-12">
			<div class="form-group">
				<div class="row">
					<div class="col-sm-2">
						{{ isset($data['backendlang']['backendlang']['Type']) ? $data['backendlang']['backendlang']['Type'] :'' }}: <span class="important-text">*</span>
					</div>
					<div class="col-sm-10">
						@php
						$selectedType = isset($blog) ? $blog->type : old('type');
						@endphp
						<select class="form-control" name="type">
							<option {{ ($selectedType == 1) ? 'selected' : '' }} value="1">
								{{ isset($data['backendlang']['backendlang']['Event']) ? $data['backendlang']['backendlang']['Event'] :'' }}
							</option>
							<option {{ ($selectedType == 2) ? 'selected' : '' }} value="2">
								{{ isset($data['backendlang']['backendlang']['News']) ? $data['backendlang']['backendlang']['News'] :'' }}
							</option>
						</select>
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
						{{ isset($data['backendlang']['backendlang']['Title(EN)']) ? $data['backendlang']['backendlang']['Title(EN)'] :'' }}: <span class="important-text">*</span>
					</div>
					<div class="col-sm-10">
						<input type="text" class="form-control" name="title" value="{{ isset($blog) ? $blog->title : old('title') }}" placeholder="{{ isset($data['backendlang']['backendlang']['Title(EN)']) ? $data['backendlang']['backendlang']['Title(EN)'] :'' }} *">
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
						{{ isset($data['backendlang']['backendlang']['Title(CN)']) ? $data['backendlang']['backendlang']['Title(CN)'] :'' }}: <span class="important-text">*</span>
					</div>
					<div class="col-sm-10">
						<input type="text" class="form-control" name="title_cn" value="{{ isset($blog) ? $blog->title_cn : old('title_cn') }}" placeholder="{{ isset($data['backendlang']['backendlang']['Title(CN)']) ? $data['backendlang']['backendlang']['Title(CN)'] :'' }} *">
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
						{{ isset($data['backendlang']['backendlang']['Image']) ? $data['backendlang']['backendlang']['Image'] :'' }}: <span class="important-text">*</span>
					</div>
					<div class="col-sm-10">
						<input type="file" class="form-control" name="image" accept="image/*">
						@if(!empty($blog->image))
						<img src="{{ asset($blog->image) }}" width="100px">
						@endif
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
						{{ isset($data['backendlang']['backendlang']['Description(EN)']) ? $data['backendlang']['backendlang']['Description(EN)'] :'' }}: <span class="important-text">*</span>
					</div>
					<div class="col-sm-10">
						<textarea id="description" class="form-control" name="description">{{ (isset($blog)) ? $blog->description : old('description') }}</textarea>
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
						{{ isset($data['backendlang']['backendlang']['Description(CN)']) ? $data['backendlang']['backendlang']['Description(CN)'] :'' }}: <span class="important-text">*</span>
					</div>
					<div class="col-sm-10">
						<textarea id="description_cn" class="form-control" name="description_cn">{{ (isset($blog)) ? $blog->description_cn : old('description_cn') }}</textarea>
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
						{{ isset($data['backendlang']['backendlang']['Date']) ? $data['backendlang']['backendlang']['Date'] :'' }}: 
					</div>
					<div class="col-sm-10">
						<div class="input-group">
							<input type="text" class="form-control required-feild date-picker"  placeholder="{{ isset($data['backendlang']['backendlang']['Date']) ? $data['backendlang']['backendlang']['Date'] :'' }} (dd/mm/yyyy)" name="blog_date" value="{{ isset($blog) ? $blog->blog_date : old('blog_date') }}" readonly>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row pb-5 mb-4">
		<div class="col-12">
			<div class="form-group add-new-tag-list">
				@php
					if(isset($blog)){
						$tags = is_array($blog->blog_tags) ? $blog->blog_tags : json_decode($blog->blog_tags, true);
						$tags_cn = is_array($blog->blog_tags_cn) ? $blog->blog_tags_cn : json_decode($blog->blog_tags_cn, true);
					}else{
						$tags = $tags_cn = [];
					}
				@endphp
				@if(!empty($tags))
					@foreach($tags as $index => $tag)
						<div class="form-group">
							<div class="row">
								<div class="col-2">
									&nbsp;
								</div>
								<div class="col-3">
									<input type="text" name="blog_tags[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['Tag_(EN)']) ? $data['backendlang']['backendlang']['Tag_(EN)'] :'' }}" value="{{ $tag }}">
								</div>
								<div class="col-3">
									<input type="text" name="blog_tags_cn[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['Tag_(CN)']) ? $data['backendlang']['backendlang']['Tag_(CN)'] :'' }}" value="{{ $tag }}" value="{{ $tags_cn[$index] ?? '' }}">
								</div>
							</div>
						</div>
					@endforeach
				@endif
			</div>
			<div class="row">
				<div class="col-2">
					{{ isset($data['backendlang']['backendlang']['Tags(EN/CN)']) ? $data['backendlang']['backendlang']['Tags(EN/CN)'] :'' }}
				</div>
				<div class="col-10">
					<a href="#" class="btn btn-sm btn-outline-primary add-new-tag">
						<i class="bi bi-plus"></i> {{ isset($data['backendlang']['backendlang']['Add_Tag']) ? $data['backendlang']['backendlang']['Add_Tag'] :'' }}
					</a>
				</div>
			</div>
		</div>
	</div>
</div>
