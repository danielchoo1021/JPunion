@extends('layouts.admin_app')
@section('content')
<div class="form-group container-box">
	<form method="POST" action="{{ route('setting_uom_save') }}" id="setting-merchant-form">
		@csrf
		<div class="big-parent">
			<div class="form-group">
				<div class="row">
					<div class="col-4">
						<div class="form-group">
							<h5><b>{{ isset($data['backendlang']['backendlang']['UOM_Name']) ? $data['backendlang']['backendlang']['UOM_Name'] :'' }}</b></h5>
						</div>
					</div>
				</div>
				<div class="child-div">
					@foreach($setting_uoms as $setting_uom)
						<div class="form-group child-row">
							<div class="row">
								<div class="col-4">
									<input type="hidden" class="uid" name="uid[]" value="{{ $setting_uom->id }}">
									<input type="text" name="uom_name[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['Pcs']) ? $data['backendlang']['backendlang']['Pcs'] :'' }}" value="{{ $setting_uom->uom_name }}">
								</div>
								<div class="col-2" align="center">
									@if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['setting-uom-delete']))
									<a href="#"  class="important-text red del">
										<i class="bi bi-trash fa-2x"></i>
									</a>
									@endif
								</div>
							</div>
						</div>
					@endforeach
					<div class="form-group child-row">
						<div class="row">
							<div class="col-4">
								<input type="hidden" name="uid[]" value="">
								<input type="text" name="uom_name[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['Pcs']) ? $data['backendlang']['backendlang']['Pcs'] :'' }}">
							</div>

							@if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['setting-uom-delete']))
							<div class="col-2" align="center">
								<a href="#" class="important-text red del">
									<i class="bi bi-trash fa-2x"></i>
								</a>
							</div>
							@endif
						</div>
					</div>
				</div>					
			</div>
			@if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['setting-uom-insert']))
			<div class="form-group">
				<div class="row">
					<div class="col-md-4" align="center">
						<button class="add-row-btn btn btn-primary btn-sm">
							<i class="bi bi-plus"></i>
						</button>
					</div>
				</div>
			</div>
			@endif
		</div>
	</form>
	@if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['setting-uom-insert']))
	<div class="submit-form-btn">
		<div class="form-group wizard-actions" align="right">
			<button class="btn btn-outline-primary">
				<i class="bi bi-check"> {{ isset($data['backendlang']['backendlang']['Save_Changes']) ? $data['backendlang']['backendlang']['Save_Changes'] :'' }}</i>
			</button>

		</div>
	</div>
	@endif
</div>
@endsection

@section('js')
<script type="text/javascript">
	$('#setting-merchant-form').on('change', 'input', function(){
		var ele = $(this);
		
		if(ele.val()){
			ele.removeClass('input-required-field');
		}
	});

	$('#setting-merchant-form').on('change', '.products', function(){
		var ele = $(this);
		var productdiv = $(this).closest('.child-row').find('.select2-container--default .select2-selection--single');

		if(ele.val()){
			productdiv.removeClass('input-required-field');
		}
	});

	$('.submit-form-btn .btn-outline-primary').click( function(e){
    	e.preventDefault();

		
    	$('#setting-merchant-form').submit();
    });

	var add_new_row = '<div class="form-group child-row">\
							<div class="row">\
								<div class="col-4">\
									<input type="hidden" name="uid[]" value="">\
									<input type="text" name="uom_name[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['Pcs']) ? $data['backendlang']['backendlang']['Pcs'] :'' }}">\
								</div>\
								@if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['setting-uom-delete']))\
								<div class="col-2" align="center">\
									<a href="#" class="important-text red del">\
										<i class="bi bi-trash fa-2x"></i>\
									</a>\
								</div>\
								@endif\
							</div>\
						</div>';
    $('.add-row-btn').click(function(e){
    	e.preventDefault();
    	var ele = $(this);

    	ele.closest('.big-parent').find('.child-div').append(add_new_row);
    	$('.big-parent .products').select2();
    });

    $('.child-div').on('click', '.del', function (e){
    	e.preventDefault();

    	var ele = $(this);
    	var uid = ele.closest('.row').find('.uid').val();

		if(!uid){
			ele.closest('.child-row').remove();
			return;
    	}

    	if(confirm('{{ isset($data['backendlang']['backendlang']['Delete_this_UOM']) ? $data['backendlang']['backendlang']['Delete_this_UOM'] :'Delete This UOM?' }}') == true){
	    	var fd = new FormData();
	        	fd.append('uid', uid);

	    	$.ajax({
		         url: '{{ route("DeleteUOM") }}',
		         type: 'post',
		         data: fd,
		         contentType: false,
		         processData: false,
		         success: function(response){
		              $('.loading-gif').hide();
					  ele.closest('.child-row').remove();
		            //ele.closest('.form-group .child-row').remove();

		         },
		      });
	    	
    		
    	}
    });
</script>
@endsection