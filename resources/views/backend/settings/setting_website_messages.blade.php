@extends('layouts.admin_app')
@section('content')
<div class="form-group">
	<form method="POST" action="{{ route('save_setting_website_messages') }}" id="setting-website-message-form">
		@csrf
		<div class="big-parent">
			<div class="container-box">
				<div class="form-group">
					<div class="row">
						<div class="col-6">
							<div class="form-group">
								<h5><b>{{ isset($data['backendlang']['backendlang']['Message_EN_CN']) ? $data['backendlang']['backendlang']['Message_EN_CN'] :'' }}</b></h5>
							</div>
						</div>
					</div>
					<div class="child-div">
						@foreach($settings as $setting)
							<div class="form-group child-row">
								<div class="row">
									<div class="col-5">
										<input type="hidden" class="sid" name="sid[]" value="{{ $setting->id }}">
										<input type="text" name="message[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['Message_EN']) ? $data['backendlang']['backendlang']['Message_EN'] :'' }}" value="{{ $setting->message }}">
									</div>
									<div class="col-5">
										<input type="text" name="message_cn[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['Message_CN']) ? $data['backendlang']['backendlang']['Message_CN'] :'' }}" value="{{ $setting->message_cn }}">
									</div>
									<div class="col-2">
										<a href="#" class="important-text red del">
											<i class="bi bi-trash fa-2x"></i>
										</a>
									</div>
								</div>
							</div>
						@endforeach
					</div>					
				</div>
				<div class="form-group pt-2">
					<div class="row">
						<div class="col-6" align="center">
							<button class="add-row-btn btn btn-primary btn-sm">
								<i class="bi bi-plus"></i>
							</button>
						</div>
					</div>
				</div>
			</div>
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
		
    	$('#setting-website-message-form').submit();
    });

	var add_new_row = '<div class="form-group child-row">\
							<div class="row">\
								<div class="col-5">\
									<input type="hidden" name="sid[]" value="">\
									<input type="text" name="message[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['Message_EN']) ? $data['backendlang']['backendlang']['Message_EN'] :'' }}">\
								</div>\
								<div class="col-5">\
									<input type="text" name="message_cn[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['Message_CN']) ? $data['backendlang']['backendlang']['Message_CN'] :'' }}">\
								</div>\
							</div>\
						</div>';
						
    $('.add-row-btn').click(function(e){
    	e.preventDefault();
    	var ele = $(this);

    	ele.closest('.big-parent').find('.child-div').append(add_new_row);
    });

    $('.child-div').on('click', '.del', function (e){
    	e.preventDefault();

    	var ele = $(this);
    	var sid = ele.closest('.row').find('.sid').val();

    	if(confirm('{{ isset($data['backendlang']['backendlang']['Delete_this_Message']) ? $data['backendlang']['backendlang']['Delete_this_Message'] :'' }}') == true){
	    	var fd = new FormData();
	        	fd.append('sid', sid);

	    	$.ajax({
		         url: '{{ route("DeleteSettingWebsiteMessage") }}',
		         type: 'post',
		         data: fd,
		         contentType: false,
		         processData: false,
		         success: function(response){
		              $('.loading-gif').hide();
		              ele.closest('.form-group .child-row').remove();

		         },
			});
    	}
    });
</script>
@endsection