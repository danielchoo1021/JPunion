@extends('layouts.admin_app')
@section('content')
<div class="form-group container-box pb-3 mb-5">
	<form method="POST" action="{{ route('save_setting_header') }}" id="setting-form" enctype="multipart/form-data">
		@csrf
		<div class="form-group">
			<h4>{{ isset($data['backendlang']['backendlang']['Shop']) ? $data['backendlang']['backendlang']['Shop'] :'' }}</h4>
			<hr>
			<label>{{ isset($data['backendlang']['backendlang']['Background_Image']) ? $data['backendlang']['backendlang']['Background_Image'] :'' }}</label>
			<input type="file" name="shop_image" class="form-control">
			@if(!empty($setting->shop_image))
				<img src="{{ asset($setting->shop_image) }}" width="200px">
			@endif
		</div>
		<div class="form-group">
			<h4>{{ isset($data['backendlang']['backendlang']['About']) ? $data['backendlang']['backendlang']['About'] :'' }}</h4>
			<hr>
			<label>{{ isset($data['backendlang']['backendlang']['Background_Image']) ? $data['backendlang']['backendlang']['Background_Image'] :'' }}</label>
			<input type="file" name="about_us_image" class="form-control">
			@if(!empty($setting->about_us_image))
				<img src="{{ asset($setting->about_us_image) }}" width="200px">
			@endif
		</div>
		<div class="form-group">
			<h4>{{ isset($data['backendlang']['backendlang']['Blog']) ? $data['backendlang']['backendlang']['Blog'] :'' }}</h4>
			<hr>
			<label>{{ isset($data['backendlang']['backendlang']['Background_Image']) ? $data['backendlang']['backendlang']['Background_Image'] :'' }}</label>
			<input type="file" name="blog_image" class="form-control">
			@if(!empty($setting->blog_image))
				<img src="{{ asset($setting->blog_image) }}" width="200px">
			@endif
		</div>
		<div class="form-group">
			<h4>{{ isset($data['backendlang']['backendlang']['Contact_Us']) ? $data['backendlang']['backendlang']['Contact_Us'] :'' }}</h4>
			<hr>
			<label>{{ isset($data['backendlang']['backendlang']['Background_Image']) ? $data['backendlang']['backendlang']['Background_Image'] :'' }}</label>
			<input type="file" name="contact_us_image" class="form-control">
			@if(!empty($setting->contact_us_image))
				<img src="{{ asset($setting->contact_us_image) }}" width="200px">
			@endif
		</div>
		<div class="form-group">
			<h4>{{ isset($data['backendlang']['backendlang']['FAQs']) ? $data['backendlang']['backendlang']['FAQs'] :'' }}</h4>
			<hr>
			<label>{{ isset($data['backendlang']['backendlang']['Background_Image']) ? $data['backendlang']['backendlang']['Background_Image'] :'' }}</label>
			<input type="file" name="faqs_image" class="form-control">
			@if(!empty($setting->faqs_image))
				<img src="{{ asset($setting->faqs_image) }}" width="200px">
			@endif
		</div>
		<div class="form-group">
			<h4>{{ isset($data['backendlang']['backendlang']['Quiz']) ? $data['backendlang']['backendlang']['Quiz'] :'' }}</h4>
			<hr>
			<label>{{ isset($data['backendlang']['backendlang']['Background_Image']) ? $data['backendlang']['backendlang']['Background_Image'] :'' }}</label>
			<input type="file" name="quiz_bg_image" class="form-control">
			@if(!empty($setting->quiz_bg_image))
				<img src="{{ asset($setting->quiz_bg_image) }}" width="200px">
			@endif
		</div>
		<div class="form-group">
			<h4>{{ isset($data['backendlang']['backendlang']['Privacy_Policy']) ? $data['backendlang']['backendlang']['Privacy_Policy'] :'' }}</h4>
			<hr>
			<label>{{ isset($data['backendlang']['backendlang']['Background_Image']) ? $data['backendlang']['backendlang']['Background_Image'] :'' }}</label>
			<input type="file" name="privacy_policy_bg_image" class="form-control">
			@if(!empty($setting->privacy_policy_bg_image))
				<img src="{{ asset($setting->privacy_policy_bg_image) }}" width="200px">
			@endif
		</div>
		<div class="form-group">
			<h4>{{ isset($data['backendlang']['backendlang']['Return_Policy']) ? $data['backendlang']['backendlang']['Return_Policy'] :'' }}</h4>
			<hr>
			<label>{{ isset($data['backendlang']['backendlang']['Background_Image']) ? $data['backendlang']['backendlang']['Background_Image'] :'' }}</label>
			<input type="file" name="return_policy_bg_image" class="form-control">
			@if(!empty($setting->return_policy_bg_image))
				<img src="{{ asset($setting->return_policy_bg_image) }}" width="200px">
			@endif
		</div>
		<div class="form-group">
			<h4>{{ isset($data['backendlang']['backendlang']['Shipping_Policy']) ? $data['backendlang']['backendlang']['Shipping_Policy'] :'' }}</h4>
			<hr>
			<label>{{ isset($data['backendlang']['backendlang']['Background_Image']) ? $data['backendlang']['backendlang']['Background_Image'] :'' }}</label>
			<input type="file" name="shipping_policy_bg_image" class="form-control">
			@if(!empty($setting->shipping_policy_bg_image))
				<img src="{{ asset($setting->shipping_policy_bg_image) }}" width="200px">
			@endif
		</div>
		<div class="form-group">
			<h4>{{ isset($data['backendlang']['backendlang']['Terms']) ? $data['backendlang']['backendlang']['Terms'] :'' }}</h4>
			<hr>
			<label>{{ isset($data['backendlang']['backendlang']['Background_Image']) ? $data['backendlang']['backendlang']['Background_Image'] :'' }}</label>
			<input type="file" name="terms_bg_image" class="form-control">
			@if(!empty($setting->terms_bg_image))
				<img src="{{ asset($setting->terms_bg_image) }}" width="200px">
			@endif
		</div>
	</form>
	<div class="submit-form-btn">
		<div class="form-group wizard-actions" align="right">
			<button class="btn btn-outline-primary">
				<i class="fa fa-check"> {{ isset($data['backendlang']['backendlang']['Save_Changes']) ? $data['backendlang']['backendlang']['Save_Changes'] :'' }}</i>
			</button>
		</div>
	</div>
</div>
@endsection

@section('js')
<script type="text/javascript">
	$('.submit-form-btn .btn-outline-primary').click( function(e){
    	e.preventDefault();
		
    	$('#setting-form').submit();
    });
</script>
@endsection