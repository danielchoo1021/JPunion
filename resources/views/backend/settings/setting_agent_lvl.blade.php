@extends('layouts.admin_app')
@section('content')
<!-- <div class="form-group">
	<div class="container-box">
		<ul>
		</ul>
	</div>
</div> -->
<div class="form-group">
	<div class="row">
		<div class="col-12">
			<form method="POST" action="{{ route('setting_agent_level_save') }}" id="setting-merchant-form">
				@csrf
				<div class="big-parent">
					<div class="form-group">
						<div class="row">
							<!-- <div class="col">
								<div class="form-group">
									<h5><b>Agent Level Name</b></h5>
								</div>
							</div>
							<div class="col">
								<div class="form-group">
									<h5><b>Maintain</b></h5>
								</div>
							</div> -->
							<!-- <div class="col">
								<div class="form-group">
									<h5><b>Commission Requirement</b></h5>
								</div>
							</div> -->
						</div>
						<div class="child-div row">
							@foreach($levels as $level)
							<div class="col-sm-12">
								<div class="form-group child-row">
									<span class="box form-group" style="background-color: {{ $level->level_colour }};">
										<!-- <h2 align='center' style="color: white;" class="text">{{ $level->agent_lvl }}</h2> -->
										<div class="row">
											<div class="col">
												<h5 align="left"><b style="color: white;">{{ isset($data['backendlang']['backendlang']['Agent_Level_Name_EN']) ? $data['backendlang']['backendlang']['Agent_Level_Name_EN'] :'' }}</b></h5>
												<input type="text" name="agent_lvl[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['EG_Agent']) ? $data['backendlang']['backendlang']['EG_Agent'] :'' }}" value="{{ $level->agent_lvl }}" style="background-color: transparent; color: #fff;">
												<input type="hidden" name="lvl_id[]" value="{{ $level->id }}">
												<input type="hidden" class="row_count" value="{{ count($levels) }}">
											</div>
											<div class="col">
												<h5 align="left"><b style="color: white;">{{ isset($data['backendlang']['backendlang']['Agent_Level_Name_CN']) ? $data['backendlang']['backendlang']['Agent_Level_Name_CN'] :'' }}</b></h5>
												<input type="text" name="agent_lvl_cn[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['EG_Agent']) ? $data['backendlang']['backendlang']['EG_Agent'] :'' }}" value="{{ $level->agent_lvl_cn }}" style="background-color: transparent; color: #fff;">
												<!-- <input type="hidden" name="lvl_id_cn[]" value="{{ $level->id }}"> -->
												<!-- <input type="hidden" class="row_count" value="{{ count($levels) }}"> -->
											</div>
											<div class="col">
												<h5 align="left"><b style="color: white;">{{ isset($data['backendlang']['backendlang']['Agent_Level_Maintain']) ? $data['backendlang']['backendlang']['Agent_Level_Maintain'] :'' }} (RM)</b></h5>
												<input type="text" name="target[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['Agent_Level_Maintain']) ? $data['backendlang']['backendlang']['Agent_Level_Maintain'] :'' }} (RM)" value="{{ $level->target }}" style="background-color: transparent; color: #fff;">
											</div>
										</div>
									</span>
								</div>
							</div>
							@endforeach
						</div>					
					</div>
					@if(count($levels) < 3)
					<!-- <div class="form-group">
						<div class="row">
							<div class="col-md-12" align="center">
								<button class="btn btn-primary btn-sm add-row-btn">
									<i class="bi bi-plus"></i>
								</button>
							</div>
						</div>
					</div> -->
					@endif
				</div>
			</form>
		</div>
	</div>
</div>

<div class="submit-form-btn">
	<div class="form-group wizard-actions" align="right">
		<button class="btn btn-outline-primary">
			<i class="fa fa-check">{{ isset($data['backendlang']['backendlang']['Save_Changes']) ? $data['backendlang']['backendlang']['Save_Changes'] :'' }}</i>
		</button>

	</div>
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
    	var checkRow;
    	var checkProductsQty;
    	var checkProducts;

    	$(".child-row input[name='agent_lvl[]']").each(function( index ) {
	  		var productVal = $(this).closest('.child-row').find('select[name="product_id[]"]');
	  		var productdiv = $(this).closest('.child-row').find('.select2-container--default .select2-selection--single');
	  		var productQtyVal = $(this).closest('.child-row').find('input[name="affiliate_quantity[]"]');

	  		var aff_qty = $(this).closest('.child-row').find('input[name="affiliate_quantity[]"]');

		  	if($(this).val()){
		  		checkRow = 1;

		  		if(!productQtyVal.val()){

		  			checkProductsQty = 1;
		  			productQtyVal.addClass('input-required-field');

		  		}
		  	}else{
		  		if(productVal.val() || productQtyVal.val() || aff_qty.val()){
		  			$(this).addClass('input-required-field');
		  		}
		  	}
		});

    	if(checkRow != 1){
    		alert('{{ isset($data['backendlang']['backendlang']['Please_add_at_least_one_item']) ? $data['backendlang']['backendlang']['Please_add_at_least_one_item'] :''}}');
    		return false;
    	}

		
    	$('#setting-merchant-form').submit();
    });

	var add_new_row = '<div class="form-group child-row">\
							<div class="row">\
								<div class="col">\
									<input type="text" name="agent_lvl[]" class="form-control" placeholder="{{ isset($data["backendlang"]["backendlang"]["EG_Agent"]) ? $data["backendlang"]["backendlang"]["EG_Agent"] :''}}">\
									<input type="hidden" name="lvl_id[]">\
								</div>\
							</div>\
						</div>';
    $('.add-row-btn').click(function(e){
    	e.preventDefault();
    	var ele = $(this);

    	ele.closest('.big-parent').find('.child-div').append(add_new_row);
    	// $('.big-parent .products').select2();

    	$('.row_count').val(parseInt($('.row_count').val()) + 1);

    	if($('.row_count').val() >= 3){
    		ele.hide();
    	}
    });

    // $('.big-parent .products').select2();
</script>
@endsection