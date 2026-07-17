@extends('layouts.admin_app')
@section('content')
<div class="row">
	<div class="col-12">
		<form method="POST" action="{{ route('setting_partner_level_save') }}" id="setting-merchant-form">
			@csrf
			<div class="big-parent">
				<div class="form-group">
					<div class="row">
						<div class="col-6">
							<div class="form-group">
								<h5><b>Partner Level Description</b></h5>
							</div>
						</div>
						<div class="col-6">
							<div class="form-group">
								<h5><b>Partner Level CN</b></h5>
							</div>
						</div>
					</div>
					<div class="child-div">
						@foreach($levels as $level)
						<div class="form-group child-row">
							<div class="row">
								<div class="col-6">
									<input type="text" name="partner_lvl[]" class="form-control" placeholder="e.g. Agent" value="{{ $level->partner_lvl }}">
									<input type="hidden" name="lvl_id[]" value="{{ $level->id }}">
								</div>
								<div class="col-6">
									<input type="text" name="partner_lvl_cn[]" class="form-control" placeholder="代理" value="{{ $level->partner_lvl_cn }}">
								</div>
							</div>
						</div>
						@endforeach
						<div class="form-group child-row">
							<div class="row">
								<div class="col-6">
									<input type="text" name="partner_lvl[]" class="form-control" placeholder="e.g. Agent">
									<input type="hidden" name="lvl_id[]">
								</div>
								<div class="col-6">
									<input type="text" name="partner_lvl_cn[]" class="form-control" placeholder="代理">
								</div>
							</div>
						</div>
					</div>					
				</div>
				<hr>
				<div class="form-group">
					<div class="row">
						<div class="col-md-12" align="center">
							<button class="add-row-btn">
								<i class="fa fa-plus"></i>
							</button>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>

<div class="submit-form-btn">
	<div class="form-group wizard-actions" align="right">
		<button class="btn btn-outline-primary">
			<i class="fa fa-check"> SAVE CHANGES</i>
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

    	$(".child-row input[name='partner_lvl[]']").each(function( index ) {
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
    		alert('Please add at least one item');
    		return false;
    	}

		
    	$('#setting-merchant-form').submit();
    });

	var add_new_row = '<div class="form-group child-row">\
							<div class="row">\
								<div class="col-6">\
									<input type="text" name="partner_lvl[]" class="form-control" placeholder="e.g. Agent">\
									<input type="hidden" name="lvl_id[]">\
								</div>\
								<div class="col-6">\
									<input type="text" name="partner_lvl_cn[]" class="form-control" placeholder="代理">\
								</div>\
							</div>\
						</div>';
    $('.add-row-btn').click(function(e){
    	e.preventDefault();
    	var ele = $(this);

    	ele.closest('.big-parent').find('.child-div').append(add_new_row);
    	$('.big-parent .products').select2();
    });

    $('.big-parent .products').select2();
</script>
@endsection