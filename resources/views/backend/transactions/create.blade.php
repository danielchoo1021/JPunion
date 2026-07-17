@extends('layouts.admin_app')

@section('content')

<form method="POST" action="{{ route('transaction.transactions.store') }}" id="transaction-form" enctype="multipart/form-data">
	@csrf

	@if(Request::segment(2) == 'create_point')
		<input type="hidden" name="point_transaction" value="1">
	@endif

	<div class="row">
		<div class="col-12">
			@if($errors->any())
			  <div class="alert alert-danger">{!! implode('<br/>', $errors->all(':message')) !!}</div>
			@endif
			<div class="form-group container-box">
				<h3>{{ isset($data['backendlang']['backendlang']['Agent_Member']) ? $data['backendlang']['backendlang']['Agent_Member'] :'' }}</h3>
				<hr>
				<select class="form-control merchants select2" name="merchants">
					<option value="">{{ isset($data['backendlang']['backendlang']['Select_Agent_Member']) ? $data['backendlang']['backendlang']['Select_Agent_Member'] :'' }}</option>
					@foreach($merchants as $merchant)
					<option {{ (old('merchants') == $merchant->code) ? 'selected' : '' }} value="{{ $merchant->code }}" data-lvl="{{ $merchant->lvl }}">
						{{ $merchant->f_name }} {{ $merchant->l_name }} ({{ $merchant->display_code }}{{ $merchant->display_running_no }})
					</option>
					@endforeach
				</select>
				<div id="address_option">
				<br>
					<label for="default_shipping_option">
						<input type="checkbox" class="default_shipping_option" name="default_shipping_option" data-id="0" id="default_shipping_option">
						{{ isset($data['backendlang']['backendlang']['Use_Default_Address']) ? $data['backendlang']['backendlang']['Use_Default_Address'] :'' }}
					</label>
					<div class="form-group">
						<label>{{ isset($data['backendlang']['backendlang']['Name']) ? $data['backendlang']['backendlang']['Name'] :'' }}</label>
						<input type="text" name="f_name" id="f_name" class="form-control address-disabled-check" required placeholder="{{ isset($data['backendlang']['backendlang']['Name']) ? $data['backendlang']['backendlang']['Name'] :'' }}" value="{{ old('f_name') }}">
					</div>
					<div class="form-group">
						<label>{{ isset($data['backendlang']['backendlang']['Email']) ? $data['backendlang']['backendlang']['Email'] :'' }}</label>
						<input type="email" name="email" id="email" class="form-control address-disabled-check" required placeholder="{{ isset($data['backendlang']['backendlang']['Email']) ? $data['backendlang']['backendlang']['Email'] :'' }}" value="{{ old('email') }}"> 
					</div>
					<div class="form-group row">
						<div class="col-sm-6">
							<label>{{ isset($data['backendlang']['backendlang']['Country_Code']) ? $data['backendlang']['backendlang']['Country_Code'] :'' }}</label>
							<select name="country_code" id="country_code" class="form-control address-disabled-check" required> 
								<option value="">{{ isset($data['backendlang']['backendlang']['Select_Country']) ? $data['backendlang']['backendlang']['Select_Country'] :'' }}</option>
								@foreach($countries as $country)
                            		<option value="{{ $country->country_contact }}" {{ ($country->country_id == 160) ? 'selected' : '' }}> 
										(+{{ $country->country_contact }}) {{ $country->country_name }} 
									</option>
                            	@endforeach
							</select>
						</div>
						<div class="col-sm-6">
							<label>{{ isset($data['backendlang']['backendlang']['Phone']) ? $data['backendlang']['backendlang']['Phone'] :'' }}</label>
							<input type="text" name="phone" id="phone" class="form-control address-disabled-check" placeholder="{{ isset($data['backendlang']['backendlang']['Phone']) ? $data['backendlang']['backendlang']['Phone'] :'' }}" required value="{{ old('phone') }}">
						</div>
					</div>
					<div class="form-group">
							<label for="">{{ isset($data['backendlang']['backendlang']['Country']) ? $data['backendlang']['backendlang']['Country'] :'' }}</label>
							<select class="form-control country address-disabled-check" name="country" id="country" data-live-search="true">
							@foreach($countries as $country)
							<option value="{{ $country->country_id }}"
								{{ ($country->country_id == 160) ? 'selected' : '' }}
								> {{ $country->country_name }} </option>
							@endforeach

						</select>
					</div>
					<div class="form-group">
						<label for="">{{ isset($data['backendlang']['backendlang']['Address']) ? $data['backendlang']['backendlang']['Address'] :'' }}</label>
						<textarea name="address" id="address" cols="30" rows="10" class="form-control address-disabled-check">{!! old('address') !!}</textarea>
					</div>
					<div class="form-group local_state">
						<label>{{ isset($data['backendlang']['backendlang']['State']) ? $data['backendlang']['backendlang']['State'] :'' }}</label>
						<select name="state" id="state" class="form-control new_state address-disabled-check" required>
							<option value="" >{{ isset($data['backendlang']['backendlang']['Select_State']) ? $data['backendlang']['backendlang']['Select_State'] :'' }}</option>
							@foreach($state as $states)
								<option value="{{ $states->id }}" {{ old('state') == $states->id ? 'selected' : '' }}>{{ $states->name }}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group other_state" style="display:none">
						<input type="text" name="other_state" id="other_state" class="form-control address-disabled-check">
					</div>
					<div class="form-group row">
						<div class="col-sm-6">
							<label for="">{{ isset($data['backendlang']['backendlang']['City']) ? $data['backendlang']['backendlang']['City'] :'' }}</label>
							<input type="text" name="city" id="city" class="form-control address-disabled-check" placeholder="{{ isset($data['backendlang']['backendlang']['City']) ? $data['backendlang']['backendlang']['City'] :'' }}" required value="{{ old('city') }}">
						</div>
						<div class="col-sm-6">
							<label for="">{{ isset($data['backendlang']['backendlang']['Post_Code']) ? $data['backendlang']['backendlang']['Post_Code'] :'' }}</label>
							<input type="text" name="postcode" id="postcode" placeholder="{{ isset($data['backendlang']['backendlang']['Post_Code']) ? $data['backendlang']['backendlang']['Post_Code'] :'' }}"  class="form-control address-disabled-check" required value="{{ old('postcode') }}">
						</div>
					</div>
				</div>
	
				<input type="hidden" class="state">
				<input type="hidden" name="" class="country_hidden">
				<br>
				<br>
				<div class="form-group">
					{{ isset($data['backendlang']['backendlang']['Remark']) ? $data['backendlang']['backendlang']['Remark'] :'' }}
					<hr>
					<textarea class="form-control" name="remark" placeholder="{{ isset($data['backendlang']['backendlang']['Remark']) ? $data['backendlang']['backendlang']['Remark'] :'' }}">{!! (!empty(old('remark'))) ? old('remark') : '' !!}</textarea>
				</div>
			</div>
			<div class="form-group container-box big-parent">
				<h3>{{ isset($data['backendlang']['backendlang']['Items']) ? $data['backendlang']['backendlang']['Items'] :'' }}</h3>
				<hr>
				@if(!empty(old('product_id')))
					@php
						$product_id = count(old('product_id'));
					@endphp
					@for($a=0; $a<$product_id; $a++)
					<div class="child-div">
						<div class="form-group child-row">
							<div class="row">							
								<div class="col-md-4">
									<div class="form-group">
										@php
											$old_product_id = old('product_id')[$a];
										@endphp
										<select class="form-control products select2" name="product_id[]">
											<option value="">{{ isset($data['backendlang']['backendlang']['Select_Product']) ? $data['backendlang']['backendlang']['Select_Product'] :'' }}</option>
											@foreach($products as $product)
											<option {{ ($product->id == old('product_id')[$a]) ? 'selected' : '' }} value="{{ $product->id }}">{{ $product->product_name }}</option>
											@endforeach
										</select>
									</div>
									<div class="product_variation">
										@if (!empty(old('hidden_variation_id')[$a]))
											@php
												$old_hidden_variation_id = old('hidden_variation_id')[$a];
											@endphp
											<label>Variation</label>
											<select class="form-control product_variation_option"  name="product_variation[]">
                                  				<option value="">Select Variation</option>
												@foreach ($old_variations[$old_product_id] as $old_variation)
													<option value="{{ $old_variation->id }}" {{ $old_hidden_variation_id == $old_variation->id ? 'selected' : '' }}>
														{{ $old_variation->variation_name }}
													</option>
												@endforeach
											</select>
										@endif
									</div>
									<div class="product_second_variation">
										@if (!empty(old('hidden_second_variation_id')[$a]))
											<label>{{ isset($data['backendlang']['backendlang']['Second_Variation']) ? $data['backendlang']['backendlang']['Select_Variation'] :'' }}</label>
											<select class="form-control product_second_variation_option"  name="product_second_variation[]">
												<option value="">{{ isset($data['backendlang']['backendlang']['Select_Second_Variation']) ? $data['backendlang']['backendlang']['Select_Second_Variation'] :'' }}</option>
												@foreach ($old_second_variations[$old_hidden_variation_id] as $old_second_variation)
													<option value="{{ $old_second_variation->id }}" {{ old('hidden_second_variation_id')[$a] == $old_second_variation->id ? 'selected' : '' }}>
														{{ $old_second_variation->variation_name }}
													</option>
												@endforeach
											</select>
										@endif
									</div>
									<div class="stockBalance">
									</div>
									<!-- <input type="hidden" class="hidden_price"> -->
									<input type="hidden" name="hidden_weight[]" class="hidden_weight" value="{{ !empty(old('hidden_weight')[$a]) ? old('hidden_weight')[$a] : '' }}">
									<input type="hidden" name="" class="hidden_free_shipping_west">
									<input type="hidden" name="" class="hidden_free_shipping_east">
									<input type="hidden" name="" class="hidden_free_shipping_singapore">
									<input type="hidden" name="hidden_variation_id[]" class="hidden_variation_id" value="{{ !empty(old('hidden_variation_id')[$a]) ? old('hidden_variation_id')[$a] : '' }}">
									<input type="hidden" name="hidden_second_variation_id[]" class="hidden_second_variation_id" value="{{ !empty(old('hidden_second_variation_id')[$a]) ? old('hidden_second_variation_id')[$a] : '' }}">
								</div>
								<div class="col-md-4">
									<input type="text" name="quantity[]"  class="form-control quantity" placeholder="{{ isset($data['backendlang']['backendlang']['Quantity']) ? $data['backendlang']['backendlang']['Quantity'] :'' }}" value="{{ (!empty(old('quantity')[$a])) ? old('quantity')[$a] : '' }}">
								</div>
								<div class="col-md-4">
									<input type="text" name="pricing[]"  class="form-control hidden_price" placeholder="{{ isset($data['backendlang']['backendlang']['Pricing']) ? $data['backendlang']['backendlang']['Pricing'] :'' }}" value="{{ old('pricing')[$a] }}">
								</div>
							</div>
						</div>
					</div>
					@endfor
				@else
					<div class="child-div">
						<div class="form-group child-row">
							<div class="row">							
								<div class="col-md-4">
									<div class="form-group">
										<select class="form-control products select2" name="product_id[]">
											<option value="">{{ isset($data['backendlang']['backendlang']['Select_Product']) ? $data['backendlang']['backendlang']['Select_Product'] :'' }}</option>
											@foreach($products as $product)
											<option value="{{ $product->id }}">{{ $product->product_name }}</option>
											@endforeach
										</select>

										
									</div>
									
									<div class="product_variation">
									</div>
									<div class="product_second_variation">
									</div>
									<div class="stockBalance">
									</div>
									<!-- <input type="hidden" class="hidden_price"> -->
									<input type="hidden" name="hidden_weight[]" class="hidden_weight">
									<input type="hidden" name="" class="hidden_free_shipping_west">
									<input type="hidden" name="" class="hidden_free_shipping_east">
									<input type="hidden" name="" class="hidden_free_shipping_singapore">
									<input type="hidden" name="hidden_variation_id[]" class="hidden_variation_id">
									<input type="hidden" name="hidden_second_variation_id[]" class="hidden_second_variation_id">
								</div>
								<div class="col-md-4">
									<input type="text" name="quantity[]"  class="form-control quantity" placeholder="{{ isset($data['backendlang']['backendlang']['Quantity']) ? $data['backendlang']['backendlang']['Quantity'] :'' }}">
								</div>
								<div class="col-md-4">
									<input type="text" name="pricing[]"  class="form-control hidden_price" placeholder="{{ isset($data['backendlang']['backendlang']['Pricing']) ? $data['backendlang']['backendlang']['Pricing'] :'' }}">
								</div>
							</div>
						</div>
					</div>				
				@endif
				<div class="form-group">
					<div class="row">
						<div class="col-md-12" align="center">
							<button type="button" class="btn btn-outline-success btn-sm add-row-btn">
								<i class="bi bi-plus"></i>
							</button>
						</div>
					</div>
				</div>
			</div>

			@if(Request::segment(2) == 'create_point')
			<div class="form-group">
				<div class="container-box">
					<h3>{{ isset($data['backendlang']['backendlang']['Remaining_Point_Wallet_Balance']) ? $data['backendlang']['backendlang']['Remaining_Point_Wallet_Balance'] :'' }}</h3>
					<hr>
					<div class="row">
						<div class="col-12" align="center"> 
					<b class="wallet-balance-amount" style="font-size: 18px;">
						0.00 {{ isset($data['backendlang']['backendlang']['Point']) ? $data['backendlang']['backendlang']['Point'] :'' }}
					</b>
						</div>
					</div>
				</div>
			</div>
			@endif

			<div class="form-group">
				<div class="container-box">
					<h3>{{ isset($data['backendlang']['backendlang']['Summary']) ? $data['backendlang']['backendlang']['Summary'] :'' }}</h3>
					<hr>
					<div class="row">
						@if(Request::segment(2) == 'create_point')
						<h5  class="col-sm-4">{{ isset($data['backendlang']['backendlang']['Sub_Total_Point']) ? $data['backendlang']['backendlang']['Sub_Total_Point'] :'' }}</h5>
						@else
						<h5  class="col-sm-4">{{ isset($data['backendlang']['backendlang']['Sub_Total']) ? $data['backendlang']['backendlang']['Sub_Total'] :'' }} (RM)</h5>
						@endif
						
						<span class="col-sm-8 sub_total"></span>
					</div>
					<br>
					<div class="row">
						@if(Request::segment(2) == 'create_point')
						<h5  class="col-sm-4">{{ isset($data['backendlang']['backendlang']['Shipping_Fee_Points']) ? $data['backendlang']['backendlang']['Shipping_Fee_Points'] :'' }}</h5>
						@else
						<h5  class="col-sm-4">{{ isset($data['backendlang']['backendlang']['Shipping_Fee']) ? $data['backendlang']['backendlang']['Shipping_Fee'] :'' }} (RM)</h5>
						@endif
						<span class="col-sm-8">
							<input type="text" class="form-control shipping_fee" name="shipping_fee" value="{{ (!empty(old('shipping_fee'))) ? old('shipping_fee') : '' }}">
						</span>
					</div>
					<br>
					<div class="row">
						@if(Request::segment(2) == 'create_point')
							<h5  class="col-sm-4">{{ isset($data['backendlang']['backendlang']['Discount_Points']) ? $data['backendlang']['backendlang']['Discount_Points'] :'' }}</h5>
						@else			
							<h5  class="col-sm-4">{{ isset($data['backendlang']['backendlang']['Discount']) ? $data['backendlang']['backendlang']['Discount'] :'' }} (RM)</h5>
						@endif
						<span class="col-sm-8 discount">
							<input type="text" class="form-control discount" name="discount" value="{{ (!empty(old('discount'))) ? old('discount') : '' }}" onkeypress="return isNumberKey(event)">
						</span>
					</div>
					<br>
					<div class="row">
						@if(Request::segment(2) == 'create_point')
							<h5  class="col-sm-4">{{ isset($data['backendlang']['backendlang']['Grand_Total_Points']) ? $data['backendlang']['backendlang']['Grand_Total_Points'] :'' }}</h5>
						@else
							<h5  class="col-sm-4">{{ isset($data['backendlang']['backendlang']['Grand_Total']) ? $data['backendlang']['backendlang']['Grand_Total'] :'' }} (RM)</h5>
						@endif
						<span class="col-sm-8 grand-total"></span>
					</div>
				</div>
			</div>
			@if(Request::segment(2) != 'create_point')
				<div class="form-group container-box">
					<h3>{{ isset($data['backendlang']['backendlang']['Bank_Slip']) ? $data['backendlang']['backendlang']['Bank_Slip'] :'' }} (RM)</h3>
					<hr>
					<input type="file" class="form-control" name="bank_slip" accept="image/*,application/pdf">
				</div>
			@endif
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

@if(Request::segment(2) == 'create_point')
	<script type="text/javascript">
		$('.merchants').change(function(e){
			var agent = $('.merchants').find(':selected').val();

			$.ajax({
				type: 'post',
				url: '{{route("get_remaining_points")}}',
				data: {agent:agent},
				success:function(response){
					$('.wallet-balance-amount').html(parseFloat(response).toFixed(2) + ' {{ isset($data['backendlang']['backendlang']['Point']) ? $data['backendlang']['backendlang']['Point'] :'' }}');
				}
			});
		});
	</script>
@endif

<script type="text/javascript">

	var products = [];

	$('.big-parent').on('change','.products',function(e){
		var pid = $(this).find(':selected').val();
		var agent = $('.merchants').find(':selected').val();
		var ele = $(this);
		var lvl = $('.merchants').find(':selected').data('lvl');
		

	    // if(!lvl){
	    // 	alert('Please Select Agent First');
	    // 	$('.loading-gif').hide();
	    // 	ele.val("");
	    // 	return false;
	    // }
		// products.push(option);	
		// console.log(products);
		$.ajax({
			type: 'post',
			url: '{{route("get_transaction_detail")}}',
			data: {pid:pid,agent:agent},
			success:function(response){
				ele.closest('.child-row').find('.hidden_price').val(response[0]);
				ele.closest('.child-row').find('.hidden_weight').val(response[1]);
				ele.closest('.child-row').find('.hidden_free_shipping_west').val(response[2]);
				ele.closest('.child-row').find('.hidden_free_shipping_east').val(response[3]);
				calc(1);
			}
		});
		
	});

	$('.merchants').change(function(e){
		var ele = $(this);
		
		var f_name = $('#f_name');
		var email = $('#email');
		var country_code = $('#country_code');
		var phone = $('#phone');
		var country = $('#country');
		var address = $('#address');
		var state = $('#state');
		var city = $('#city');
		var postcode = $('#postcode');
		var other_state = $('#other_state');

		f_name.val('');
		email.val('');
		country_code.val('');
		phone.val('');
		country.val('');
		country.val('160');
		address.val('');
		state.val('');
		city.val('');
		postcode.val('');
		$('.address-disabled-check').prop('disabled', false);
		$('input[name="default_shipping_option"]').prop('checked', false)

		var mid = ele.val();
		var fd = new FormData();
	  		fd.append('mid', mid);

		$.ajax({
	        url: '{{ route("getShippingAddress") }}',
	        type: 'post',
	        data: fd,
	        contentType: false,
	        processData: false,
	        success: function(response){
	        	if(response){
	        		$('.state').val(response[0]);
					// $('.country_hidden').val(response[1]);
	        	}
	        },
	    });

		$('.new_state').change(function(e){
			$('.state').val($(this).val());
			
		});

		function set_country_val() {
			if($('.country').val() == 160){
				$('.other_state').hide();
				$('#state').show();
			}else{
				$('.other_state').show();
				$('#state').hide();
			}

			$('.country_hidden').val($('.country').val());
		}

		set_country_val();

		$('.country').change(function(e){
			set_country_val();
		});

		var lvl = $('.merchants').find(':selected').data('lvl');
			
	    // if(!lvl){
	    // 	alert('Please Select Agent First');
	    // 	$('.loading-gif').hide();
	    // 	ele.val("");
	    // 	return false;
	    // }else{
			$('#address_option').show();
			$('#default_shipping_option').attr('data-id', $('.merchants').find(':selected').val());
		// }
	});

	// $('.merchants').trigger('change');



	function calc(type)
	{
		var get_sub_total = 0;
		var total_weight = 0;
		var shipping_weight = 0;

		var state = $('.state').val();
		var country = $('.country_hidden').val();

		$( ".child-row" ).each(function(){
			var price = $(this).find('.hidden_price').val();
			var quantity = $(this).find('.quantity').val();
			if(quantity){
				quantity = (quantity != '') ? quantity : 0;
				var get_price =  parseFloat(price) * parseFloat(quantity);
				get_price = (get_price != '') ? get_price : 0;
				get_sub_total += get_price;
				var weight = $(this).find('.hidden_weight').val();
				get_weight = parseFloat(weight) * parseFloat(quantity);
				get_price = (get_weight != '') ? get_weight : 0;
				total_weight += get_weight;

				var free_west_shipping = $(this).find('.hidden_free_shipping_west').val();
				var free_east_shipping = $(this).find('.hidden_free_shipping_east').val();
				var free_singapore_shipping = $(this).find('.hidden_free_shipping_singapore').val();

				if(country == '160'){
					if(state != '11' && state != '12' && state != '15'){
						if(free_west_shipping != 1){
							shipping_weight += get_weight;
						}
					}else{
						if(free_east_shipping != 1){
							shipping_weight += get_weight;
						}
					}
				}else{
					if(free_singapore_shipping != 1 && country != '200'){
						shipping_weight += get_weight;
					}
				}

				 $('.sub_total').html(parseFloat(parseFloat(get_sub_total)).toFixed(2));				
			}
		});
		
		if(state != ''){
			$.ajax({
				type: 'post',
				data:{state:state,total_weight:total_weight,country:country, shipping_weight:shipping_weight},
				url: "{{ route("get_shipping_fee") }}",
				success:function(response){
					var get_shipping_fee = $('.shipping_fee').val();
					var discount = $('input[name="discount"]').val();
					// console.log(discount);
					discount = (discount > 0) ? discount : 0;
					if(type == 2){
						get_shipping_fee = get_shipping_fee;
					}else{
						// get_shipping_fee = response[0];
						get_shipping_fee = response;
					}

					// console.log(response)
					// console.log(parseFloat(get_sub_total));

					var grand_total = parseFloat(parseFloat(get_sub_total) + parseFloat(get_shipping_fee));

					if(discount > grand_total){
						toastr.error('{{ isset($data['backendlang']['backendlang']['Discount_Amount_Exceed_Grand_Total']) ? $data['backendlang']['backendlang']['Discount_Amount_Exceed_Grand_Total'] :'' }}');
						return;
					}

					$('.shipping_fee').val(parseFloat(get_shipping_fee).toFixed(2));
					$('.grand-total').html(parseFloat(parseFloat(get_sub_total) + parseFloat(get_shipping_fee) - parseFloat(discount)).toFixed(2));
				}
			});
		}
	}

	$('.shipping_fee').change(function(){
		calc(2);
	})

	$('.discount').change(function(){
		calc(2);	
	})

	$(document).on('keyup','.quantity',function(){
		var agent = $('.merchants').find(':selected').val();
		if(!agent){
			alert('{{ isset($data['backendlang']['backendlang']['Please_Select_Agent_Member']) ? $data['backendlang']['backendlang']['Please_Select_Agent_Member'] :'' }}');
			$('.loading-gif').hide();
			return false;
		}
		calc(1);
		
	});

	$(document).on('keyup','.hidden_price',function(){
		var agent = $('.merchants').find(':selected').val();
		if(!agent){
			alert('{{ isset($data['backendlang']['backendlang']['Please_Select_Agent_Member']) ? $data['backendlang']['backendlang']['Please_Select_Agent_Member'] :'' }}');
			$('.loading-gif').hide();
			return false;
		}
		calc(1);
		
	});

	$('.add-row-btn').click(function(e){
		e.preventDefault();

		var ele = $(this);

		var add_new_row = '<div class="form-group child-row">\
								<div class="row">\
									<div class="col-md-4">\
										<div class="form-group">\
											<select class="form-control products select2" name="product_id[]">\
												<option value="">{{ isset($data['backendlang']['backendlang']['Select_Product']) ? $data['backendlang']['backendlang']['Select_Product'] :'' }}</option>\
												@foreach($products as $product)\
												<option value="{{ $product->id }}">{{ $product->product_name }}</option>\
												@endforeach\
											</select>\
										</div>\
										<div class="product_variation">\
										</div>\
										<div class="product_second_variation">\
										</div>\
										<div class="stockBalance">\
										</div>\
										<input type="hidden" name="hidden_weight[]" class="hidden_weight">\
										<input type="hidden" name="" class="hidden_free_shipping_west">\
										<input type="hidden" name="" class="hidden_free_shipping_east">\
										<input type="hidden" name="" class="hidden_free_shipping_singapore">\
										<input type="hidden" name="hidden_variation_id[]" class="hidden_variation_id">\
										<input type="hidden" name="hidden_second_variation_id[]" class="hidden_second_variation_id">\
									</div>\
									<div class="col-md-4">\
										<input type="text" name="quantity[]" value="" class="form-control quantity" placeholder="{{ isset($data['backendlang']['backendlang']['Quantity']) ? $data['backendlang']['backendlang']['Quantity'] :'' }}">\
									</div>\
									<div class="col-md-4">\
										<input type="text" name="pricing[]" value="" class="form-control hidden_price" placeholder="{{ isset($data['backendlang']['backendlang']['Pricing']) ? $data['backendlang']['backendlang']['Pricing'] :'' }}">\
									</div>\
								</div>\
							</div>';

		ele.closest('.big-parent').find('.child-div').append(add_new_row);
		$('.big-parent .select2').select2();
	});

	$('.big-parent').on('change', '.products', function(){
		var ele = $(this);

		ele.closest('.child-row').find('.hidden_variation_id').val('');
		ele.closest('.child-row').find('.hidden_second_variation_id').val('');

		var numItems = $('.big-parent .products').length;
		var pid = ele.val();
		var fd = new FormData();
	  		fd.append('num', numItems);
	  		fd.append('pid', pid);

		var agent = $('.merchants').find(':selected').val();
	
		var lvl = $('.merchants').find(':selected').data('lvl');

		ele.closest('.child-row').find('.product_variation').html('');
		ele.closest('.child-row').find('.product_second_variation').html('');
		ele.closest('.child-row').find('.stockBalance').html('');
		$.ajax({
	        url: '{{ route("getTransactionVariation") }}',
	        type: 'post',
	        data: fd,
	        contentType: false,
	        processData: false,
	        success: function(response){
	        	// if(response[0] == '2'){
	        	// 	ele.closest('.child-row').find('.stockBalance').html('Balance left: '+response[1]);
	        	// }else{
	        	// 	
	        	// }
	        	if(response[0] == '1'){
					ele.closest('.child-row').find('.product_variation').html(response[1]);
	        	}else{
	        	}
	        }
	    });		
	});

	$('.big-parent').on('change', '.product_variation_option', function(){
		var ele = $(this);

		ele.closest('.child-row').find('.hidden_variation_id').val('');
		ele.closest('.child-row').find('.hidden_second_variation_id').val('');
		
		var vid = ele.val();
		var fd = new FormData();
	  		fd.append('vid', vid);

		var pid = $(this).find(':selected').data('pid');
		// console.log(pid);

		var agent = $('.merchants').find(':selected').val();

		ele.closest('.child-row').find('.hidden_variation_id').val(vid);

		$.ajax({
			type: 'post',
			url: '{{route("getTransactionSecondVariation")}}',
			data: {pid:pid,vid:vid},
			success:function(response){
				if(response[0] == '1'){
					ele.closest('.child-row').find('.product_second_variation').html(response[1]);
				} else {
					$.ajax({
						url: '{{ route("getVariationStock") }}',
						type: 'post',
						data: fd,
						contentType: false,
						processData: false,
						success: function(response){
							ele.closest('.child-row').find('.stockBalance').html('{{ isset($data['backendlang']['backendlang']['Balance_Left']) ? $data['backendlang']['backendlang']['Balance_Left'] :'' }}: '+response[1]);	        	
						},
					});
					
					$.ajax({
						type: 'post',
						url: '{{route("get_transaction_detail")}}',
						data: {pid:pid,agent:agent,vid:vid},
						success:function(response){
							ele.closest('.child-row').find('.hidden_price').val(response[0]);
							ele.closest('.child-row').find('.hidden_weight').val(response[1]);
						}
					});
				}
			}
		});
	});

	$('.big-parent').on('change', '.product_second_variation_option', function(){
		var ele = $(this);

		ele.closest('.child-row').find('.hidden_second_variation_id').val('');

		var svid = ele.val();
		var vid = ele.closest('.big-parent').find('.product_variation_option :selected').val();
		var pid = $(this).find(':selected').data('pid');
		var agent = $('.merchants').find(':selected').val();

		ele.closest('.child-row').find('.hidden_second_variation_id').val(svid);

		var fd = new FormData();
			fd.append('svid', svid);
			fd.append('vid', vid);
			fd.append('pid', pid);
			fd.append('agent', agent);


		$.ajax({
			url: '{{ route("getSecondVariationStock") }}',
			type: 'post',
			data: fd,
			contentType: false,
			processData: false,
			success: function(response){
				ele.closest('.child-row').find('.stockBalance').html('{{ isset($data['backendlang']['backendlang']['Balance_Left']) ? $data['backendlang']['backendlang']['Balance_Left'] :'' }}: '+response[1]);	        	
			},
		});
		
		$.ajax({
			type: 'post',
			url: '{{route("get_transaction_detail")}}',
			data: {pid:pid,agent:agent,vid:vid,svid:svid},
			success:function(response){
				ele.closest('.child-row').find('.hidden_price').val(response[0]);
				ele.closest('.child-row').find('.hidden_weight').val(response[1]);
			}
		});
	});

	$('#address_option').on('click', '.default_shipping_option',function(e){
		$('.loading-gif').show();

		var ele = $(this);
		var code = $('.merchants').find(':selected').val();
		if(code == 0){
			alert('{{ isset($data['backendlang']['backendlang']['Please_Select_Agent_Member']) ? $data['backendlang']['backendlang']['Please_Select_Agent_Member'] :'' }}');
			$('.loading-gif').hide();
			return false;
		}

		var f_name = $('#f_name');
		var email = $('#email');
		var country_code = $('#country_code');
		var phone = $('#phone');
		var country = $('#country');
		var address = $('#address');
		var state = $('#state');
		var city = $('#city');
		var postcode = $('#postcode');
		var other_state = $('#other_state');
		var url = "{{route('get_agent_address',':id')}}";
		url = url.replace(':id', code);
		// console.log(code)

		if(ele.is(':checked')){
			$.ajax({
				type: 'get',
				url: url,
				success:function(data){
					if(data[0]){
						f_name.val(data[0]);
						email.val(data[1]);
						country_code.val(data[2]);
						phone.val(data[3]);
						country.val(data[4]);
						
						address.val(data[5]);
						if (data[4] != 160) {
							$('.other_state').show();
							$('#state').hide();
							other_state.val(data[6]);
						}else if(data[4] == 160){
							state.val(data[6]);
							$('.other_state').hide();
							$('#state').show();
						}

						city.val(data[7]);
						postcode.val(data[8]);

						$('.address-disabled-check').prop('disabled',true);
					}else{
						alert('No Default Address')
						$('#address_option').find('.default_shipping_option').prop('checked', false);
					}
					calc(1);
				}
				
			});

			var fd = new FormData();
	  		fd.append('mid', code);

			$.ajax({
				url: '{{ route("getShippingAddress") }}',
				type: 'post',
				data: fd,
				contentType: false,
				processData: false,
				success: function(response){
					if(response){
						$('.state').val(response[0]);
						$('.country_hidden').val(response[1]);
					}
				},
	    	});
			ele.val(1);
		}else{
			f_name.val('');
			email.val('');
			country_code.val('');
			phone.val('');
			country.val('');
			country.val('160');
			address.val('');
			state.val('');
			city.val('');
			postcode.val('');
			ele.val(0);
			$('.address-disabled-check').prop('disabled',false);
		}

		$('.loading-gif').hide();
	});

	$('.btn-outline-primary').click( function(e){
    	e.preventDefault();

		$('.loading-gif').show();

    	$('#transaction-form').submit();
    });

    $('.select2').select2();
</script>
@if (old('default_shipping_option') == '1')
	<script type="text/javascript">
		$('#default_shipping_option').trigger('click');
	</script>
@endif

<script type="text/javascript">
	$(document).ready(function(e) {
		$('.child-row').each(function() {
			var ele = $(this);

			var svid = ele.find('.product_second_variation_option :selected').val();
			var vid = ele.find('.product_variation_option :selected').val();
			var pid = ele.find('.products :selected').val();
			var agent = $('.merchants').find(':selected').val();

			var fd = new FormData();
			if (svid) {
				fd.append('svid', svid);
			}

			if (vid) {
				fd.append('vid', vid);
			}

			fd.append('pid', pid);
			fd.append('agent', agent);

			if (svid) {
				$.ajax({
					url: '{{ route("getSecondVariationStock") }}',
					type: 'post',
					data: fd,
					contentType: false,
					processData: false,
					success: function(response){
						ele.find('.stockBalance').html('{{ isset($data['backendlang']['backendlang']['Balance_Left']) ? $data['backendlang']['backendlang']['Balance_Left'] :'' }}: '+response[1]);	        	
					},
				});
			} else if (vid) {
				$.ajax({
					url: '{{ route("getVariationStock") }}',
					type: 'post',
					data: fd,
					contentType: false,
					processData: false,
					success: function(response){
						ele.find('.stockBalance').html('{{ isset($data['backendlang']['backendlang']['Balance_Left']) ? $data['backendlang']['backendlang']['Balance_Left'] :'' }}: '+response[1]);	        	
					},
				});
			}
			
			$.ajax({
				type: 'post',
				url: '{{ route("get_transaction_detail") }}',
				data: fd,
				contentType: false,
				processData: false,
				success:function(response){
					ele.find('.hidden_price').val(response[0]);
					ele.find('.hidden_weight').val(response[1]);
				}
			});
		});

		calc(1);
	});
</script>
@endsection