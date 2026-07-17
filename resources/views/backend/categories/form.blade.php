<div class="container-box">
	<div class="row">
		<div class="col-6">
			<div class="form-group">
	<!-- 			<div class="row">
					<div class="col-sm-4">
						Show on menu bar: 
					</div>
					<div class="col-sm-8">
						<div class="checkbox">
							<label>
								@php
									$checkedBox = (isset($category)) ? $category->menu_bar : old('menu_bar');
								@endphp
								<input name="menu_bar" type="checkbox" class="ace" value="1" {{ $checkedBox == 1 ? 'checked' : '' }} />
								<span class="lbl"></span>
							</label>
						</div>
					</div>
				</div> -->
			</div>
			<div class="form-group">
				<div class="row">
					<div class="col-sm-4">
						<b> {{ isset($data['backendlang']['backendlang']['Code']) ? $data['backendlang']['backendlang']['Code'] :'' }}:</b> <span class="important-text">*</span>
					</div>
					<div class="col-sm-8">
						<input type="text" class="form-control" name="code" value="{{ isset($category) ? $category->code : old('code') }}" placeholder=" {{ isset($data['backendlang']['backendlang']['Code']) ? $data['backendlang']['backendlang']['Code'] :'' }} *">
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="row">
					<div class="col-sm-4">
						<b> {{ isset($data['backendlang']['backendlang']['Name']) ? $data['backendlang']['backendlang']['Name'] :'' }}:</b> <span class="important-text">*</span>
					</div>
					<div class="col-sm-8">
						<input type="text" class="form-control" name="category_name" value="{{ isset($category) ? $category->category_name : old('category_name') }}" placeholder=" {{ isset($data['backendlang']['backendlang']['Name']) ? $data['backendlang']['backendlang']['Name'] :'' }} *">
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
	    <div class="col-sm-2">
	        <b> {{ isset($data['backendlang']['backendlang']['Image']) ? $data['backendlang']['backendlang']['Image'] :'' }}</b> <span class="important-text">*</span>
	    </div>
	    <div class="col-sm-10">
	        <div class="form-group category-image-list">
	            <div class="row">
	                
	            </div>
	            <div class="clear-both"></div>
	        </div>
	        <div class="form-group">
	          <form method="POST" action="" class="asdasd" id="upload_image_form" enctype="multipart/form-data">
	            <input type="file" name="upload_image" id="upload_image" class="form-control" accept="image/*,application/pdf" />
	            <br />
	              <div id="uploaded_image"></div>
	          </form>
	        </div>
	    </div>
	</div>
</div>