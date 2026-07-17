@extends('layouts.app')
@section('css')
<style type="text/css">
	.switch {
	  position: relative;
	  display: inline-block;
	  width: 60px;
	  height: 34px;
	}

	/* Hide default HTML checkbox */
	.switch input {
	  opacity: 0;
	  width: 0;
	  height: 0;
	}

	/* The slider */
	.slider {
	  position: absolute;
	  cursor: pointer;
	  top: 0;
	  left: 0;
	  right: 0;
	  bottom: 0;
	  background-color: #ccc;
	  -webkit-transition: .4s;
	  transition: .4s;
	}

	.slider:before {
	  position: absolute;
	  content: "";
	  height: 26px;
	  width: 26px;
	  left: 4px;
	  bottom: 4px;
	  background-color: white;
	  -webkit-transition: .4s;
	  transition: .4s;
	}

	input:checked + .slider {
	  background-color: #2196F3;
	}

	input:focus + .slider {
	  box-shadow: 0 0 1px #2196F3;
	}

	input:checked + .slider:before {
	  -webkit-transform: translateX(26px);
	  -ms-transform: translateX(26px);
	  transform: translateX(26px);
	}

	/* Rounded sliders */
	.slider.round {
	  border-radius: 34px;
	}

	.slider.round:before {
	  border-radius: 50%;
	}
</style>
@endsection
@section('content')

@include('partial.frontend.profile_header')
<div class="profile-content pb-5">
	<div class="container">
		@if($errors->any())
          <div class="alert alert-danger">{!! implode('<br/>', $errors->all(':message')) !!}</div>
        @endif

        @if($data['web_setting']->stock_clearance_enable == 1 && Auth::user()->lvl >= 5)
        @php
        	if(Auth::user()->stock_clearance_enable == 1){
        		$stock_clearance_enable = "checked";
        	}else{
        		$stock_clearance_enable = "";
        	}
        @endphp
        <div class="form-group container-box">
        	<div class="row">
        		<div class="col-6">
        			Stock Clearance Enable
        		</div>
        		<div class="col-6" align="right">
        			<label class="switch">
					  	<input type="checkbox" name="stock_clearance_enable" class="stock_clearance_enable" {{ $stock_clearance_enable }}>
					  	<span class="slider round"></span>
					</label>
        		</div>
        	</div>
        </div>
        @endif

		<div class="form-group">
			<button class="btn btn-primary request-withdrawal set_button set_text">
				{{ isset($data['lang']['lang']['withdrawal_stocks']) ? $data['lang']['lang']['withdrawal_stocks'] :'Withdrawal Stocks'}}
			</button>
		</div>
		<div class="row">
			@foreach($my_stocks as $transaction)
			<div class="col-md-3 parent-box">
				<div class="container-box">
					<a class="" href="{{ route('MyStocksHistory', ['pid='.$transaction->product_id,
																   'vid='.$transaction->variation_id,
																   'svid='.$transaction->second_variation_id]) }}">
						<div style="background-image: url({{ asset(!empty($transaction->product_image) ? $transaction->product_image : 'images/no-image-available-icon-6.jpg') }});
									background-repeat: no-repeat;
									background-size: contain;
									background-position: center;
									width: 100%;
									height: 250px;"></div>
						<h3 class="form-group">
							{{ $transaction->product_name }}
							@if(!empty($transaction->sub_category))
							<br>
							{{ isset($data['lang']['lang']['variation']) ? $data['lang']['lang']['variation'] :'Variation'}}: {{ $transaction->sub_category }}
							@endif
							@if(!empty($transaction->second_sub_category))
							<br>
							{{ isset($data['lang']['lang']['variation']) ? $data['lang']['lang']['variation'] :'Variation'}}: {{ $transaction->second_sub_category }}
							@endif
						</h3>
					</a>
					<br>
					<h6>
						{{ isset($data['lang']['lang']['stock_balance']) ? $data['lang']['lang']['stock_balance'] :'Stock Balance'}}: {{ $productStockBalance[$transaction->product_id][$transaction->variation_id][$transaction->second_variation_id] }}
					</h6>
					<div class="form-group">
						<input type="text" class="form-control quantity" value="1" style="text-align: center;">
					</div>
					@php
						if(!empty($transaction->second_variation_id)){
							$weight_of_product = $transaction->weight_of_s_variation;
						}elseif(!empty($transaction->variation_id)){
							$weight_of_product = $transaction->weight_of_variation;
						}else{
							$weight_of_product = $transaction->weight_of_product;
						}
					@endphp
					<div class="form-group" align="center">
						<input type="checkbox" class="select-product form-control" name="withdrawal_product" 
							   data-pid="{{ $transaction->product_id }}"
							   data-vid="{{ $transaction->variation_id }}"
							   data-svid="{{ $transaction->second_variation_id }}"
							   data-weight="{{ $weight_of_product }}">
					</div>
				</div>
			</div>
			<hr>
			@endforeach
		</div>
	</div>
</div>

<div class="modal fade bd-example-modal-lg select-shipping-address" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  	<div class="modal-dialog modal-lg" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<h5 class="modal-title" id="exampleModalLabel">My Shipping Address</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          	<span aria-hidden="true">&times;</span>
		        </button>
      		</div>
      		<div class="modal-body">
      			<div class="form-group" align="right">
		      		<a href="#" data-toggle="modal" data-target="#add-new-address" class="btn btn-primary btn-sm set_button set_text">
		      			<i class="fa fa-plus"></i> Add New Address
		      		</a>
		      		<a href="{{ route('AddressBook.AddressBook.index') }}" class="btn btn-primary btn-sm set_button set_text">
		      			Address Manage
		      		</a>
		      	</div>
		      	<hr>

			    <form method="POST" action="{{ route('SubmitWithdrawalStock') }}" enctype="multipart/form-data" id="withdrawal-form">
			    	@csrf
			    	<div class="shipping_addresses_area">
				      	@if(!empty($data['getUserDetails']->get_shipping_address))
					        @foreach($data['getUserDetails']->get_shipping_address as $ad)
					        	@php
					        		$get_country = !empty($ad->get_country->country_name) ? $ad->get_country->country_name : $ad->country;
					        	@endphp
					        	<div class="form-group">
					        		<div class="row">
					        			<div class="col-1">
					        				<input type="radio" name="df_ad_id" value="{{ $ad->id }}" {{ ($ad->default == 1) ? 'checked' : '' }}>
					        				<input type="hidden" name="aid[]" value="{{ $ad->id }}">
					        			</div>
					        			<div class="col-11">
							        		<i class="fa fa-user" style="width: 20px;"></i> {{ $ad->f_name }} {{ $ad->l_name }}<br> 
							        		<i class="fa fa-phone" style="width: 20px;"></i>
							        			+{{ $ad->country_code }} 
							        			{{ ($ad->phone[0] == 0) ? substr($ad->phone, 1) : $ad->phone }} 
							        			<br>
							        		<i class="fa fa-location-arrow" style="width: 20px;"></i> 
							        		{{ $ad->address }}, 
							        		{{ $ad->post_code }} 
							        		{{ $ad->city }}, 
							        		{{ !empty($ad->get_states->name) ? $ad->get_states->name : $ad->state }}
							        		{{ !empty($get_country) ? $get_country : 'Malaysia' }}
					        			</div>
					        		</div>
					        	</div>
					        	<hr>
					        @endforeach
					    @endif
			    	</div>

					<div class="form-group" align="left">
						<label class="checkbox">
							<input type="checkbox" name="self_pickup" class="self-pickup"> Self Pickup
						</label>
						<div class="form-group">
							<select class="form-control" name="cod_address">
								@foreach($cod_addresses as $cod_address)
								<option value="{{ $cod_address->id }}">
									{{ $cod_address->address }} | {{ $cod_address->address_desc }}
								</option>
								@endforeach
							</select>
						</div>
					</div>
					<hr>

				    <input type="hidden" name="arr">
					<input type="hidden" name="shipping_fee">
			      	<div class="shipping_fee_area">
					    <div class="form-group">
					    	<div class="row">
					    		<div class="col-6">
					    			Shipping Fee
					    		</div>
					    		<div class="col-6" align="right">
					    			RM <span class="shipping_fee_display">0.00</span>
					    		</div>
					    	</div>
					    </div>
					    <hr>
					    <div class="form-group">
					    	<input type="file" name="bank_slip">
					    </div>
			      	</div>
					<div class="form-group">
						<textarea class="form-control" name="remark" placeholder="Remark"></textarea>
					</div>
			    </form>
			    

      		</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-secondary set_button set_text" data-dismiss="modal">Close</button>
        		<button type="button" class="btn btn-primary withdrawal-stocks set_button set_text">Confirm</button>
     	 	</div>
    	</div>
  	</div>
</div>

<div class="modal fade" id="add-new-address" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  	<div class="modal-dialog modal-dialog-scrollable">
    	<div class="modal-content" style="box-shadow: 0 2px 10px #bdbdbd;">
      		<div class="modal-header">
        		<h4>
        			Add Shipping Address
        		</h4>
     		</div>
      		<div class="modal-body">
        			<div class="form-group">
		                @if($errors->any())
		                  <div class="alert alert-danger">{!! implode('<br/>', $errors->all(':message')) !!}</div>
		                @endif
		            </div>
		      		<div class="form-group">
		      			Name <span class="important-text">*</span>
		            	<input type="text" class="form-control required-feild" placeholder="Name *" name="f_name" value="">
		            </div>
		      		<div class="form-group">
		      			Email Address <span class="important-text">*</span>
						<input type="text" class="form-control required-feild" placeholder="Email Address *" name="email" value="">
					</div>
					<div class="form-group">
		                <div class="row">
		                    <div class="col-6">
		                    	Country Code <span class="important-text">*</span>
		                        <select class="form-control country_code" name="country_code" id="country_code" data-live-search="true">
		                            @foreach($countries as $country)
		                            <option value="{{ $country->country_contact }}"
		                                {{ ($country->country_id == 160) ? 'selected' : '' }}
		                                > (+{{ $country->country_contact }}) {{ $country->country_name }} </option>
		                            @endforeach

		                        </select>
		                    </div>
		                    <div class="col-6">
		                    	Phone <span class="important-text">*</span>
		                        <input type="text" class="form-control required-feild" placeholder="Ex: 121234567" name="phone" value=""  onkeypress="return isNumberKey(event)">
		                    </div>
		                </div>
		            </div>
					<div class="form-group">
						Country
						<select class="form-control country" name="country">
							@foreach($countries as $country)
							<option {{ ( $country->country_id == '160') ? 'selected' : '' }} value="{{ $country->country_id }}">
								{{ $country->country_name }}
							</option>
							@endforeach
						</select>
					</div>
					<div class="form-group">
						Address <span class="important-text">*</span>
						<textarea class="form-control required-feild" placeholder="Address *" name="address"></textarea>
					</div>
					<div class="form-group">
						State <span class="important-text">*</span>
						<div class="state_area">
							<select class="form-control state" name="state">
								<option>Select State</option>
								@foreach($states as $state)
								<option value="{{ $state->id }}">{{ $state->name }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-6">
								City <span class="important-text">*</span>
								<input type='text' class="form-control required-feild" placeholder="City *" name="city" value="">
							</div>
							<div class="col-6">
								Postcode <span class="important-text">*</span>
								<input type='text' class="form-control required-feild" placeholder="Postcode *" name="postcode" value="" onkeypress="return isNumberKey(event)">
							</div>
						</div>
					</div>
		      		
		            <div class="form-group">
		                <div id="action-return-message"></div>
		            </div>
		      		
		      		<div class="form-group">
			      		<button class="btn btn-primary btn-block btn-sm default_btn add-new-address-btn set_button set_text">
			        		Submit
			        	</button>
		      			<br>
		      			<button type="button" class="btn btn-secondary btn-block btn-sm set_button set_text" data-dismiss="modal">Cancel</button>
		      		</div>
      		</div> 
    	</div>
  	</div>
</div>
@endsection

@section('js')
<script type="text/javascript">
	$('.withdrawal-stocks').click(function(e){
		e.preventDefault();

		var shipping_fee = $('input[name="shipping_fee"]').val();
		if(shipping_fee > 0){
			var bank_slip = $('input[name="bank_slip"]').val(); 
			if(!bank_slip){
				toastr.error('Please upload bank slip to continue.');
				return false;
			}
		}

		$('#withdrawal-form').submit();
	});

	$('.request-withdrawal').click(function(e){
		e.preventDefault();

		var ele = $(this);
		var arr = [];
		$('.select-product:checked').each(function(i, obj){
			arr.push([$(this).data('pid'), 
					  $(this).data('vid'), 
					  $(this).data('svid'),
					  $(this).closest('.parent-box').find('.quantity').val(),
					  $(this).data('weight')]);
		});
		// console.log(arr);
		$('input[name="arr"]').val(JSON.stringify(arr));
		console.log(arr)
		if(arr.length === 0){
			toastr.error('Please Select Product');
			return false;
		}

		$('.select-shipping-address').modal('show');

		calc();
	});

	$('.self-pickup').click(function(e){
		calc();
	})

	function calc()
	{
		var sid = $('input[name="df_ad_id"]:checked').val();

		var self_pickup = $('.self-pickup').prop('checked');

		var self_pickup_checked;
		if(self_pickup == true){
			self_pickup_checked = 1
		}else{
			self_pickup_checked = 0;
		}

		var arr = [];
		$('.select-product:checked').each(function(i, obj){
			arr.push([$(this).data('pid'), 
					  $(this).data('vid'), 
					  $(this).data('svid'),
					  $(this).closest('.parent-box').find('.quantity').val(), 
					  $(this).data('weight')]);
		});

		// console.log(arr);
		var fd = new FormData();
			fd.append('arr', JSON.stringify(arr));
			fd.append('sid', sid);
			fd.append('self_pickup_checked', self_pickup_checked);
		// alert(sid);	
		$.ajax({
		       url: '{{ route("get_shipping_fee") }}',
		       type: 'post',
		       data: fd,
		       contentType: false,
		       processData: false,
		       success: function(response){
		       		// alert(response);
		       		if(response[0] > 0){
		       			$('.shipping_fee_area').show();
		       			$('.shipping_fee_display').html(parseFloat(response[0]).toFixed(2));
		       			$('input[name="shipping_fee"]').val(response[0]);
		       		}else{
		       			$('.shipping_fee_display').html('0.00');
		       			$('input[name="shipping_fee"]').val(0);
		       			$('.shipping_fee_area').hide();
		       		}
		       }
		});
	}

	$('input[name="df_ad_id"]').click(function(){
		calc();
	});

	$('.add-new-address-btn').click(function(e){
		e.preventDefault();

		var f_name = $('input[name="f_name"]').val();
		var email = $('input[name="email"]').val();
		var country_code = $('select[name="country_code"]').val();
		var phone = $('input[name="phone"]').val();
		var country = $('select[name="country"]').val();
		var address = $('textarea[name="address"]').val();
		var state = $('.state_area').find('.state').val();
		var city = $('input[name="city"]').val();
		var postcode = $('input[name="postcode"]').val();

		if(!f_name){
			toastr.error('Please Field in Name');
			return false;
		}

		if(!email){
			toastr.error('Please Field in Email');
			return false;
		}

		if(!country_code){
			toastr.error('Please Select Country Code');
			return false;
		}

		if(!phone){
			toastr.error('Please Field in Phone');
			return false;
		}

		if(!country){
			toastr.error('Please Select Country');
			return false;
		}

		if(!address){
			toastr.error('Please Field in Address');
			return false;
		}

		if(!state){
			toastr.error('Please Select State');
			return false;
		}

		if(!city){
			toastr.error('Please Field in City');
			return false;
		}

		if(!postcode){
			toastr.error('Please Field in Postcode');
			return false;
		}

		var fd = new FormData();
			fd.append('f_name', f_name);
			fd.append('email', email);
			fd.append('country_code', country_code);
			fd.append('phone', phone);
			fd.append('country', country);
			fd.append('address', address);
			fd.append('state', state);
			fd.append('city', city);
			fd.append('postcode', postcode);

		$.ajax({
		       url: '{{ route("add_new_shipping_address") }}',
		       type: 'post',
		       data: fd,
		       contentType: false,
		       processData: false,
		       success: function(response){
		       		if(response == 'ok'){
		       			get_shipping_address();
		       			$('#add-new-address').modal('toggle');
		       		}else{
		       			toastr.error('Error! Please contact admin');
		       		}
		       }
		});
	});

	function get_shipping_address()
	{
		$.ajax({
		       url: '{{ route("get_shipping_address") }}',
		       type: 'get',
		       success: function(response){
		       		$('.shipping_addresses_area').html(response);
		       }
		});
	}

	// get_shipping_address();

	$('.shipping_addresses_area').on('click', '.input[name="df_ad_id"]', function(){
		calc()
	});

    $('.country').change(function(){
        var ele = $(this);

        if(ele.val() != '160'){
            $('.state_area').html('<input type="text" class="form-control state" name="state" placeholder="State">');
        }else{
            $('.state_area').html('<select class="form-control state" name="state" style="height: auto;">\
                                        <option value="">Select State</option>\
                                        @foreach($states as $state)\
                                            <option value="{{ $state->id }}">\
                                                {{ $state->name }}\
                                            </option>\
                                        @endforeach\
                                   </select>');
        }
    })
</script>
@endsection