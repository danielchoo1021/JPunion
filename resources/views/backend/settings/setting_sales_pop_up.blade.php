@extends('layouts.admin_app')
@section('content')
<div class="row">
	<div class="col-12">
		<form method="POST" action="{{ route('setting_sales_pop_up') }}" id="setting-merchant-form">
			@csrf
			<div class="big-parent">
				<div class="form-group">
					<div class="row">
						<div class="col-3">
							<div class="form-group">
								<h5><b>{{ isset($data['backendlang']['backendlang']['Buyer_Name']) ? $data['backendlang']['backendlang']['Buyer_Name'] :'' }}</b></h5>
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<h5><b>{{ isset($data['backendlang']['backendlang']['products']) ? $data['backendlang']['backendlang']['products'] :'' }}</b></h5>
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<h5><b>{{ isset($data['backendlang']['backendlang']['date']) ? $data['backendlang']['backendlang']['date'] :'' }}</b></h5>
							</div>
						</div>
						<div class="col-3">
							<div class="form-group">
								<h5><b>{{ isset($data['backendlang']['backendlang']['Action']) ? $data['backendlang']['backendlang']['Action'] :'' }}</b></h5>
							</div>
						</div>
					</div>
					<div class="child-div">
						@foreach($sales as $sale)
						<div class="form-group child-row">
							<div class="row">
								<div class="col-3">
									<input type="text" name="name[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['Buyer_Name']) ? $data['backendlang']['backendlang']['Buyer_Name'] :'' }}" value="{{ $sale->name }}">
									<input type="hidden" name="sales_id[]" value="{{ $sale->id }}">
								</div>
								<div class="col-3">
									<select class="form-control" name="product_id[]">
										@foreach($products as $product)
										<option value="{{ $product->id }}">
											{{ $product->product_name }}
										</option>
										@endforeach
									</select>
								</div>
								<div class="col-3">
									<input type="text" name="sales_date[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['e.g.']) ? $data['backendlang']['backendlang']['e.g.'] :'' }} yyyy-mm-dd H:i:s.g."  value="{{ $sale->sales_date }}">
								</div>
								<div class="col-3">
									<a href="#" class="del-record" data-id="{{ $sale->id }}">
										<i class="fa fa-trash"></i>
									</a>
								</div>
							</div>
						</div>
						@endforeach
						<div class="form-group child-row">
							<div class="row">
								<div class="col-3">
									<input type="text" name="name[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['Buyer_Name']) ? $data['backendlang']['backendlang']['Buyer_Name'] :'' }}">
									<input type="hidden" name="sales_id[]">
								</div>
								<div class="col-3">
									<select class="form-control" name="product_id[]">
										@foreach($products as $product)
										<option value="{{ $product->id }}">
											{{ $product->product_name }}
										</option>
										@endforeach
									</select>
								</div>
								<div class="col-3">
									<input type="text" name="sales_date[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['e.g.']) ? $data['backendlang']['backendlang']['e.g.'] :'' }} yyyy-mm-dd H:i:s">
								</div>
								<div class="col-3">
									<a href="#" class="del-record">
										<i class="fa fa-trash"></i>
									</a>
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
			<i class="fa fa-check"> {{ isset($data['backendlang']['backendlang']['Save_Changes']) ? $data['backendlang']['backendlang']['Save_Changes'] :'' }}</i>
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

	$('#setting-merchant-form').on('click', '.del-record', function(){
		
		$('.loading-gif').show();
        var ele = $(this);
        var row_id = ele.data('id');
        if(row_id){
	        var fd = new FormData();
	        fd.append('row_id', row_id);

	        if(confirm("{{ isset($data['backendlang']['backendlang']['Delete_This_Row']) ? $data['backendlang']['backendlang']['Delete_This_Row'] :'' }}") == true){
		        $.ajax({
		           url: '{{ route("deleteSalesPopup") }}',
		           type: 'post',
		           data: fd,
		           contentType: false,
		           processData: false,
		           success: function(response){
		                $('.loading-gif').hide();
		                toastr.success("{{ isset($data['backendlang']['backendlang']['Status_Changed']) ? $data['backendlang']['backendlang']['Status_Changed'] :'' }}");
		                // window.location.href="{{ route('merchant.merchants.index') }}";
		                location.reload();
		           },
		        });
		    }else{
	        	$('.loading-gif').hide();
	        }        	
        }else{
        	$('.loading-gif').hide();
        	ele.closest('.child-row').remove();
        }
	});

	$('.submit-form-btn .btn-outline-primary').click( function(e){
    	e.preventDefault();
    	var checkRow;
    	var checkProductsQty;
    	var checkProducts;

    	$(".child-row input[name='name[]']").each(function( index ) {
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
    		alert("{{ isset($data['backendlang']['backendlang']['Please_add_at_least_one_item']) ? $data['backendlang']['backendlang']['Please_add_at_least_one_item'] :'' }}");
    		return false;
    	}

		
    	$('#setting-merchant-form').submit();
    });

	var add_new_row = '<div class="form-group child-row">\
							<div class="row">\
								<div class="col-3">\
									<input type="text" name="name[]" class="form-control" placeholder="{{ isset($data["backendlang"]["backendlang"]["Buyer_Name"]) ? $data["backendlang"]["backendlang"]["Buyer_Name"] :"" }}">\
									<input type="hidden" name="sales_id[]">\
								</div>\
								<div class="col-3">\
									<select class="form-control" name="product_id[]">\
										@foreach($products as $product)\
										<option value="{{ $product->id }}">\
											{{ $product->product_name }}\
										</option>\
										@endforeach\
									</select>\
								</div>\
								<div class="col-3">\
									<input type="text" name="sales_date[]" class="form-control" placeholder="{{ isset($data["backendlang"]["backendlang"]["e.g."]) ? $data["backendlang"]["backendlang"]["e.g."] :"" }} yyyy-mm-dd H:i:s">\
								</div>\
								<div class="col-3">\
									<a href="#" class="del-record">\
										<i class="fa fa-trash"></i>\
									</a>\
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