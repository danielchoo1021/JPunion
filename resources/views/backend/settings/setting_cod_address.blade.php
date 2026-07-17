@extends('layouts.admin_app')
@section('content')
<form action="{{ route('save_setting_cod_address') }}" method="POST" id="setting-merchant-form">
	@csrf
	<div class="form-group container-box">
		<div class="row parent-box">
			<div class="col-md-12 add-message-content">
				@foreach($settings as $setting)
				<div class="row messsage-del">
					<div class="col-md-2">
						<input type="hidden" name="sid[]" value="{{ $setting->id }}">
						<div class="form-group">
							<input type="text" class="form-control" name="cod_code[]" placeholder="{{ isset($data['backendlang']['backendlang']['Code']) ? $data['backendlang']['backendlang']['Code'] :'' }}" value="{{ $setting->cod_code }}">
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<input type="text" class="form-control" name="address_desc[]" placeholder="{{ isset($data['backendlang']['backendlang']['Address_Description']) ? $data['backendlang']['backendlang']['Address_Description'] :'' }}" value="{{ $setting->address_desc }}">
						</div>
					</div>
					<div class="col-md-7">
						<div class="form-group">
							<input type="text" class="form-control" name="address[]" placeholder="{{ isset($data['backendlang']['backendlang']['Address']) ? $data['backendlang']['backendlang']['Address'] :'' }}" value="{{ $setting->address }}">
						</div>
					</div>
					<input type="hidden" class="tid" name="tid[]" value="{{ $setting->id }}">
	
					<div class="col-1" align="center">
						<a href="#" class="important-text red del">
							<i class="bi bi-trash fa-2x"></i>
						</a>
					</div>
				</div>
				@endforeach
				<div class="row del-new-message">
					<div class="col-md-2">
						<input type="hidden" name="sid[]" value="">
						<div class="form-group">
							<input type="text" class="form-control" name="cod_code[]" placeholder="{{ isset($data['backendlang']['backendlang']['Code']) ? $data['backendlang']['backendlang']['Code'] :'' }}" value="">
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
							<input type="text" class="form-control" name="address_desc[]" placeholder="{{ isset($data['backendlang']['backendlang']['Address_Description']) ? $data['backendlang']['backendlang']['Address_Description'] :'' }}" value="">
						</div>
					</div>
					<div class="col-md-7">
						<div class="form-group">
							<input type="text" class="form-control" name="address[]" placeholder="{{ isset($data['backendlang']['backendlang']['Address']) ? $data['backendlang']['backendlang']['Address'] :'' }}" value="">
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="form-group">
				<div class="row">
					<div class="col-md-12" align="center">
						<a href="#" class="add-shipping-btn btn btn-primary" id="add-west">
							<i class="bi bi-plus"></i>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>

<div class="submit-form-btn">
	<div class="form-group wizard-actions" align="right">
		<button class="btn btn-outline-primary">
			<i class="bi bi-check"> {{ isset($data['backendlang']['backendlang']['Save_Changes']) ? $data['backendlang']['backendlang']['Save_Changes'] :'' }}</i>
		</button>
	</div>
</div>
@endsection

@section('js')
<script type="text/javascript">
	$('.submit-form-btn .btn-outline-primary').click(function(e) {
		e.preventDefault();

		$('#setting-merchant-form').submit();
	});

	var m = '<div class="row del-new-message">\
    			<div class="col-md-2">\
					<div class="form-group">\
						<input type="text" class="form-control" name="cod_code[]" placeholder="{{ isset($data["backendlang"]["backendlang"]["Code"]) ? $data["backendlang"]["backendlang"]["Code"] :''}}" value="">\
					</div>\
				</div>\
				<div class="col-md-2">\
					<div class="form-group">\
						<input type="text" class="form-control" name="address_desc[]" placeholder="{{ isset($data["backendlang"]["backendlang"]["Address_Description"]) ? $data["backendlang"]["backendlang"]["Address_Description"] :''}}">\
					</div>\
				</div>\
				<div class="col-md-7">\
					<input type="hidden" name="sid[]" value="">\
					<div class="form-group">\
						<input type="text" class="form-control" name="address[]" placeholder="{{ isset($data["backendlang"]["backendlang"]["Address"]) ? $data["backendlang"]["backendlang"]["Address"] :''}}" value="">\
					</div>\
				</div>\
			</div>';
	$('.add-shipping-btn').click(function(e) {
		e.preventDefault();
		$('.parent-box.row .col-md-12').append(m);
	})

	$('.add-message-content').on('click', '.del', function(e) {
		e.preventDefault();

		var ele = $(this);
		var id = ele.closest('.messsage-del').find('.tid').val();

		if (id) {
			if (confirm("{{ isset($data['backendlang']['backendlang']['Delete_this_pickup_address']) ? $data['backendlang']['backendlang']['Delete_this_pickup_address'] :''}}") == true) {
				var fd = new FormData();
				fd.append('id', id);

				$.ajax({
					url: '{{ route("DeleteCodAddress") }}',
					type: 'post',
					data: fd,
					contentType: false,
					processData: false,
					success: function(response) {
						$('.loading-gif').hide();
						ele.closest('.messsage-del').remove();
					},
				});


			}
		} else {
			ele.closest('.del-new-message').remove();
		}
	});
</script>
@endsection