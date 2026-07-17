@extends('layouts.app')

@section('content')
<div class="container">
	<form method="POST" action="{{ route('placeOrder') }}" id="placeorder-form">
	@csrf
	<div class="form-group">
			<div class="row">
				<div class="col-md-6">
					<div class="billing-details form-group">
						<div class="form-group">
							<h5 class="title">Shipping Address </h5>
						</div>
						<input type="hidden" name="billing_details_im" value="">
						<div class="form-group">
							<div class="row">
								<div class="col-sm-6">
									<input type="text" class="form-control required-feild" placeholder="First Name *" name="f_name" value="">
								</div>
								<div class="col-sm-6">
									<input type="text" class="form-control required-feild" placeholder="Last Name *" name="l_name" value="">
								</div>
							</div>
						</div>
						<div class="form-group">
							<input type="text" class="form-control required-feild" placeholder="Email Address *" name="email" value="">
						</div>
						<div class="form-group">
							<input type="text" class="form-control required-feild" placeholder="Phone *" name="phone" value="" onkeypress="return isNumberKey(event)">
						</div>
						<div class="form-group">
							<textarea class="form-control required-feild" placeholder="Address *" name="address"></textarea>
						</div>
						<div class="form-group">
							<select class="form-control required-feild" name="state">
								<option value="">State *</option>
								@foreach($states as $state)
								<option value="{{ $state->id }}">{{ $state->name }}</option>
								@endforeach
							</select>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-sm-6">
									<input type='text' class="form-control required-feild" placeholder="City *" name="city" value="">
								</div>
								<div class="col-sm-6">
									<input type='text' class="form-control required-feild" placeholder="Post Code*" name="postcode" value="">
								</div>
							</div>
						</div>
						<div class="form-group">
							<textarea class="form-control" name="" placeholder="Remark"></textarea>
						</div>
						<div class="form-group">
							<b id="error-message" class="important-text"></b>
						</div>
						<hr>
						<div class="form-group">
							<h4 class="title">Select Banks </h4>
						</div>

						<div class="form-group">
							<div class="row">
								<div class="col-4" align="center">
									<label>
										<input type="radio" name="bank_id" value="1">
										<img src="{{ asset('images/banks/maybank.jpg') }}">
									</label>
								</div>
								<div class="col-4" align="center">
									<label>
										<input type="radio" name="bank_id" value="2">
										<img src="{{ asset('images/banks/cimb.jpg') }}">
									</label>
								</div>
								<div class="col-4" align="center">
									<label>
										<input type="radio" name="bank_id" value="4">
										<img src="{{ asset('images/banks/rhb.jpg') }}">
									</label>
								</div>
							</div>
						</div>
						<br>
						<div class="form-group">
							<div class="row">
								<div class="col-4" align="center">
									<label>
										<input type="radio" name="bank_id" value="5">
										<img src="{{ asset('images/banks/hongleong.jpg') }}">
									</label>
								</div>
								<div class="col-4" align="center">
									<label>
										<input type="radio" name="bank_id" value="3">
										<img src="{{ asset('images/banks/pbe.jpg') }}">
									</label>
								</div>
								<!-- <div class="col-xs-4" align="center">
									<label>
										<input type="radio" name="bank_id" value="6">
										<img src="{{ asset('images/banks/bsn.jpg') }}">
									</label>
								</div> -->
							</div>
						</div>

						<div class="form-group">
							<b id="error-message-banks" class="important-text"></b>
						</div>
						
						<div class="form-group">
							<button class="btn btn-primary btn-block placeorder-btn"> PLACE ORDER NOW </button>
						</div>
					</div>
				</div>

				<div class="col-md-6">
					<div class="total-charges-list">
						<div class="form-group">
							<div class="row">
								<div class="col-12">
									<b>Item(s) Details</b>
								</div>
							</div>
						</div>
						<hr>
						<div class="form-group cart-detail">
							<div class="row">
								<div class="col-2 order-images">
									
									
										<img src="{{ asset('frontend/img/product-15589-1566888192.jpg') }}" style="width: 70px;">
									
								</div>
								<div class="col-10 order-details">
									<div class="row">
										<div class="col-sm-6">
											<div class="form-group">
												<select class="form-control item_name" name="item_name">
													<option {{ !empty(request('p') && request('p') == '3') ? 'selected' : '' }} value="EVPAD 3S">EVPAD 3S</option>
													<option {{ !empty(request('p') && request('p') == '1') ? 'selected' : '' }} value="EVPAD 3S MY SIRIM MCMC">
														EVPAD 3S MY SIRIM MCMC
													</option>
													<option {{ !empty(request('p') && request('p') == '2') ? 'selected' : '' }} value="EVPAD 3R">EPLAY 3R</option>
												</select>
												<div class="form-group quantity-setting">
													<button class="btn btn-primary deduct-qty-button">
														<i class="fa fa-minus"></i>
													</button>
													<input type="text" class="form-control" name="quantity" value="1" onkeypress="return isNumberKey(event)">
													<button class="btn btn-primary add-qty-button">
														<i class="fa fa-plus"></i>
													</button>
												</div>
											</div>
										</div>

										
									</div>
								</div>
							</div>
							<hr>
						</div>
						
						
						<div class="form-group">
                        	<div class="success-message-promo green"></div>
                        	<input type="hidden" name="discount_code" id="code">
                        	<input type="hidden" name="discount" id="totalDiscount">
                        </div>
						<div class="form-group">
							<div class="row">
								<div class="col-6">
									<b>Subtotal: </b>
								</div>
								<div class="col-6" align="right">
									<b class="sub-total">RM 499.00 </b>
									<input type="hidden" id="subtotal" value="399">
								</div>
							</div>
						</div>
						<hr>
						<div class="form-group">
							<div class="row">
								<div class="col-6">
									<b class="discount_word">Delivery Charge: </b>
								</div>
								<div class="col-6" align="right">
									<b  class="discount_amount">RM 0.00</b>
								</div>
							</div>
						</div>
						<hr>
						<div class="form-group">
							<div class="row">
								<div class="col-6">
									<b>Grand total: </b>
								</div>
								<div class="col-6" align="right">
									<b class="grand-total">RM 499.00</b>
									<input type="hidden" name="grand_total" value="499.00">
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
@endsection

@section('js')
<script type="text/javascript">
	$('.deduct-qty-button').click( function(e){
		e.preventDefault();
		var ele = $(this);
		var quantity = ele.parent().find('input[name="quantity"]').val();
		var item = $('.item_name').val();

		if(item == 'EVPAD 3S MY SIRIM MCMC'){
			var amount = "499.00";
		}else if(item == 'EVPAD 3S'){
			var amount = "459.00";
		}else{
			var amount = "398.00";
		}
		if(quantity != 1){
			quantity = Number(quantity) - 1;
			ele.parent().find('input[name="quantity"]').val(quantity);
		}

		$('.grand-total, .sub-total').html("RM "+(Number(quantity) * Number(amount)).toFixed(2));
		$('input[name="grand_total"]').val(Number(quantity) * Number(amount));
	});

	$('.add-qty-button').click( function(e){
		e.preventDefault();
		var ele = $(this);
		var quantity = ele.parent().find('input[name="quantity"]').val();
		quantity = Number(quantity) + 1;
		var item = $('.item_name').val();

		if(item == 'EVPAD 3S MY SIRIM MCMC'){
			var amount = "499.00";
		}else if(item == 'EVPAD 3S'){
			var amount = "459.00";
		}else{
			var amount = "398.00";
		}

		ele.parent().find('input[name="quantity"]').val(quantity);

		$('.grand-total, .sub-total').html("RM "+(Number(quantity) * Number(amount)).toFixed(2));
		$('input[name="grand_total"]').val(Number(quantity) * Number(amount));
	});

	$('input[name="quantity"]').change( function(){
		var ele = $(this);
		var quantity = $(this).val();
		var item = $('.item_name').val();

		if(item == 'EVPAD 3S MY SIRIM MCMC'){
			var amount = "499.00";
		}else if(item == 'EVPAD 3S'){
			var amount = "459.00";
		}else{
			var amount = "398.00";
		}	

		$('.grand-total, .sub-total').html("RM "+(Number(quantity) * Number(amount)).toFixed(2));
		$('input[name="grand_total"]').val(Number(quantity) * Number(amount));
	});


	$('#placeorder-form .required-feild').change( function(){
    	if($(this).val()){
    		$(this).removeClass('required-feild-error');
    	}
    });
	$('.placeorder-btn').click( function(e){
		e.preventDefault();
		var empty_fill;
	    $('#placeorder-form .required-feild').each( function(){
	    	if(!$(this).val()){
	    		$(this).addClass('required-feild-error');
	    		empty_fill = 1;
	    	}
	    });
	    if(empty_fill == 1){
	    	$('#error-message').html('Please Fill in Required Field *.');
	    	return false;
	    }


	    if(!$("input[name='bank_id']:checked").val()){
	    	$('#error-message-banks').html('Please Select Bank.');
	    	return false;
	    }
	    
	    $('input[name="online"]').val(1);
	    $('#placeorder-form').submit();
	});

	$(document).ready( function(){
            
        if($(window).width() < 480) {
            $('.order-images').removeClass('col-2');
            $('.order-images').addClass('col-3');

            $('.order-details').removeClass('col-10');
            $('.order-details').addClass('col-9');
        }else{

        }


    });

    $('.item_name').change( function(){
    	var value = $(this).val();
	var quantity = $('input[name="quantity"]').val(); 
    	if(value == 'EVPAD 3S MY SIRIM MCMC'){
		var amount = "499.00";
	}else if(value == 'EVPAD 3S'){
		var amount = "459.00";
	}else{
		var amount = "398.00";
	}

	$('.grand-total, .sub-total').html("RM "+(Number(quantity) * Number(amount)).toFixed(2));
	$('input[name="grand_total"]').val(Number(quantity) * Number(amount));
    });

    $('.item_name').trigger('change');
</script>
@endsection