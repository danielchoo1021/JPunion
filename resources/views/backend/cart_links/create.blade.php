@extends('layouts.admin_app')
@section('css')
<style type="text/css">
	.individual-product {
		margin-bottom: 1em;
	}
</style>
@endsection
@section('content')
@if($errors->any())
  <div class="alert alert-danger">{!! implode('<br/>', $errors->all(':message')) !!}</div>
@endif
@include('backend.cart_links.form')

<div class="submit-form-btn">
	<div class="form-group wizard-actions" align="right">
		<a href="{{ route('cart_link.cart_links.index') }}" class="btn btn-outline-danger">
			<i class="bi bi-ban"> {{ isset($data['backendlang']['backendlang']['Cancel']) ? $data['backendlang']['backendlang']['Cancel'] :'' }}</i>
		</a>

		<button class="btn btn-outline-primary">
			<i class="bi bi-check"> {{ isset($data['backendlang']['backendlang']['Create']) ? $data['backendlang']['backendlang']['Create'] :'' }}</i>
		</button>

	</div>
</div>

@endsection

@section('js')

<script type="text/javascript">
	$('.submit-form-btn .btn-outline-primary').click( function(e){
    	e.preventDefault();
    	
    	$('#cart-link-form').submit();
    });

	$('.add-product-option').click(function(e){
		e.preventDefault();
		var ele = $(this);

		var row_id = $('.products').length;
		row_id++;

		var append = '<div class="individual-product">\
						<div class="individual-product-selection">\
							<div class="form-group">\
								<div class="row">\
									<div class="col-sm-6">\
										<select class="form-control products" name="products['+row_id+']">\
											<option value="">{{ isset($data['backendlang']['backendlang']['Please_Select_Products']) ? $data['backendlang']['backendlang']['Please_Select_Products'] :'' }}</option>\
											@foreach($products as $product)\
												<option value="{{ $product->id }}">{{ $product->product_name }}</option>\
											@endforeach\
										</select>\
									</div>\
									<div class="col-sm-6">\
										<input type="text" class="form-control qty" name="qty['+row_id+']" onkeypress="return isNumberKey(event)" placeholder="{{ isset($data['backendlang']['backendlang']['Quantity']) ? $data['backendlang']['backendlang']['Quantity'] :'' }}">\
									</div>\
								</div>\
								<input type="hidden" class="row_id" name="row_id" value="'+row_id+'">\
								<input type="hidden" name="cart_link_detail_id['+row_id+']" value="">\
							</div>\
							<div class="variation-selection">\
							</div>\
							<div class="second-variation-selection">\
							</div>\
						</div>\
						<div class="individual-product-details">\
						</div>\
					 </div>';

		ele.closest('.col-sm-6').find('.product-group').append(append);
	});

	function getSelectionDetails(individual_product){
		var ele = individual_product;

		var product = ele.find('.products :selected').text();
		var product_id = ele.find('.products :selected').val();
		if(typeof product === 'undefined' || product_id == ''){
			product = '-';
		}

		var variation = ele.find('.variations :selected').text();
		var variation_id = ele.find('.variations :selected').val();
		if(typeof variation === 'undefined' || variation_id == ''){
			variation = '-';
		}

		var second_variation = ele.find('.second_variations :selected').text();
		var second_variation_id = ele.find('.second_variations :selected').val();
		if(typeof second_variation === 'undefined' || second_variation_id == ''){
			second_variation = '-';
		}

	  	var qty = ele.find('.qty').val();

	  	var fd = new FormData();
	  	fd.append('product_id', product_id);
	  	fd.append('variation_id', variation_id);
	  	fd.append('second_variation_id', second_variation_id);
	  	fd.append('qty', qty);

	  	$.ajax({
	       url: '{{ route("get_cart_link_product_price") }}',
	       type: 'post',
	       data: fd,
	       contentType: false,
	       processData: false,
	       success: function(response){
	            $('.loading-gif').hide();
	            ele.find('.individual-product-details').empty();
				ele.find('.individual-product-details').html('<b>{{ isset($data["backendlang"]["backendlang"]["product"]) ? $data["backendlang"]["backendlang"]["product"] :""}}:</b> '+product+'<br>\
															 <b>{{ isset($data["backendlang"]["backendlang"]["Variation"]) ? $data["backendlang"]["backendlang"]["Variation"] :""}}:</b> '+variation+'<br>\
															 <b>{{ isset($data["backendlang"]["backendlang"]["Second_Variation"]) ? $data["backendlang"]["backendlang"]["Second_Variation"] :""}}:</b> '+second_variation+'<br>\
															 <b>{{ isset($data["backendlang"]["backendlang"]["Price"]) ? $data["backendlang"]["backendlang"]["Price"] :""}}:</b> '+parseFloat(response).toFixed(2));
	       }
	    });
	}

	function calcPrice(){
		// var product_price = [];

		// $('.individual-product-selection').each(function(){
		// 	var product_id = $(this).find('.products :selected').val();
		// 	var variation_id = $(this).find('.variations :selected').val();
		// 	var second_variation_id = $(this).find('.second_variations :selected').val();

		// 	var fd = new FormData();
		// 		fd.append('product_id', product_id);
		// 		fd.append('variation_id', variation_id);
		// 		fd.append('second_variation_id', second_variation_id);

		// 	$.ajax({
	 //           url: '{{ route("get_cart_link_product_price") }}',
	 //           type: 'post',
	 //           data: fd,
	 //           contentType: false,
	 //           processData: false,
	 //           success: function(response){
	 //                $('.loading-gif').hide();
	 //                product_price.push(response);
	 //                // console.log(product_price);
	 //           }
	 //        });
		// });

		var product_price = [];

		var ajaxCalls = [];

		$('.individual-product-selection').each(function(){
		  	var product_id = $(this).find('.products :selected').val();
		  	var variation_id = $(this).find('.variations :selected').val();
		  	var second_variation_id = $(this).find('.second_variations :selected').val();
		  	var qty = $(this).find('.qty').val();

		  	var fd = new FormData();
		  	fd.append('product_id', product_id);
		  	fd.append('variation_id', variation_id);
		  	fd.append('second_variation_id', second_variation_id);
		  	fd.append('qty', qty);

		  	var request = $.ajax({
		       url: '{{ route("get_cart_link_product_price") }}',
		       type: 'post',
		       data: fd,
		       contentType: false,
		       processData: false
		    });

		    ajaxCalls.push(request);
		});

		$.when.apply($, ajaxCalls).done(function() {
			// console.log(arguments);
			if(Array.isArray(arguments[0])){
				for (var i = 0; i < arguments.length; i++) {
					if(Array.isArray(arguments[0])){
						// console.log(arguments[i][0]);
						product_price.push(arguments[i][0]);
					}else{
						// console.log(arguments[0]);
						product_price.push(arguments[0]);
					}
				}
			}else{
				// console.log(arguments[0]);
				product_price.push(arguments[0]);
			}
		  

		  	// console.log(product_price);

		  	var total_price = 0;
			for (var i = 0; i < product_price.length; i++){
				// console.log(product_price[i]);
				total_price += parseFloat(product_price[i]);
			}

			// console.log(total_price);
			$('.subtotal_price').html('RM '+parseFloat(total_price).toFixed(2));
		});
	}

	$(document).on('change', '.products', function(){
		var ele = $(this);

		getSelectionDetails(ele.closest('.individual-product'));

		var product_id = ele.val();

		var fd = new FormData();
			fd.append('product_id', product_id);

		$.ajax({
           url: '{{ route("get_cart_link_variation") }}',
           type: 'post',
           data: fd,
           contentType: false,
           processData: false,
           success: function(response){
                $('.loading-gif').hide();
                ele.closest('.individual-product-selection').find('.variation-selection').empty();
                if(response !== null && typeof response !== undefined && Object.keys(response).length !== 0){
                	var row_id = ele.closest('.individual-product-selection').find('.row_id').val();

                	var append = '<div class="form-group">\
										<select class="form-control variations" name="variations['+row_id+']">\
											<option value="">{{ isset($data['backendlang']['backendlang']['Please_Select_Variations']) ? $data['backendlang']['backendlang']['Please_Select_Variations'] :''}}</option>';
	                response.forEach(function(e){
	                	append += '<option value='+e.id+'>'+e.variation_name+'</option>';
	                });
					append += '</select>\
								</div>';

					ele.closest('.individual-product-selection').find('.variation-selection').append(append);
	            }
           }
        });

        calcPrice();
	});

	$(document).on('change', '.variations', function(){
		var ele = $(this);

		getSelectionDetails(ele.closest('.individual-product'));

		var product_id = ele.closest('.individual-product-selection').find('.products').val();
		var variation_id = ele.val();

		var fd = new FormData();
			fd.append('product_id', product_id);
			fd.append('variation_id', variation_id);

		$.ajax({
           url: '{{ route("get_cart_link_second_variation") }}',
           type: 'post',
           data: fd,
           contentType: false,
           processData: false,
           success: function(response){
                $('.loading-gif').hide();
                ele.closest('.individual-product-selection').find('.second-variation-selection').empty();
                if(response !== null && typeof response !== undefined && Object.keys(response).length !== 0){
                	var row_id = ele.closest('.individual-product-selection').find('.row_id').val();

                	var append = '<div class="form-group">\
										<select class="form-control second_variations" name="second_variations['+row_id+']">\
											<option value="">{{ isset($data['backendlang']['backendlang']['Please_Select_Second_Variations']) ? $data['backendlang']['backendlang']['Please_Select_Second_Variations'] :''}}</option>';
	                response.forEach(function(e){
	                	append += '<option value='+e.id+'>'+e.variation_name+'</option>';
	                });
					append += '</select>\
								</div>';

					ele.closest('.individual-product-selection').find('.second-variation-selection').append(append);
	            }
           }
        });

        calcPrice();
	});

	$(document).on('change', '.second_variations', function(){
		var ele = $(this);

		getSelectionDetails(ele.closest('.individual-product'));

		calcPrice();
	});

	$(document).on('change', '.qty', function(){
		var ele = $(this);

		getSelectionDetails(ele.closest('.individual-product'));

		calcPrice();
	});
</script>
@endsection