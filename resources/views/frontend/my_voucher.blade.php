@extends('layouts.app')

@section('content')
@include('partial.frontend.profile_header')
<div class="profile-content pb-5">
	<div class="container">
		<div class="form-group container-box">
			<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
			  	<li class="nav-item">
			    	<a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">
			    			{{ isset($data['lang']['lang']['active_voucher']) ? $data['lang']['lang']['active_voucher'] :'Active Voucher'}}
			    	</a>
			  	</li>
			  	<li class="nav-item">
			    	<a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">
			    			{{ isset($data['lang']['lang']['redeemed_voucher']) ? $data['lang']['lang']['redeemed_voucher'] :'Redeemed Voucher'}}
			    	</a>
			  	</li>
				<li class="nav-item">
					<a class="nav-link" id="pills-expired-tab" data-toggle="pill" href="#expired-profile" role="tab" aria-controls="expired-profile" aria-selected="false">
						{{ isset($data['lang']['lang']['expired_voucher']) ? $data['lang']['lang']['expired_voucher'] :'Expired Voucher'}}
					</a>
				</li>
			</ul>
			<div class="tab-content" id="pills-tabContent">
			  	<div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
			  			@if(!$applied_promotions->isEmpty())
							@foreach($applied_promotions as $applied_promotion)
								@php
								$currentDate = new DateTime();
								$currentDate = $currentDate->format('Y-m-d');

								$voucherEndDate = new DateTime($applied_promotion->end_date);
								$voucherEndDate = $voucherEndDate->format('Y-m-d');
								@endphp

								@if($currentDate <= $voucherEndDate)
								<div class="form-group wish-row">
									<div class="row">
										<div class="col-md-2" align="center">
											@if(!empty($applied_promotion->image))
											<img src="{{ asset($applied_promotion->image) }}" style="width: 100px;height:100px">
											@else
											<img src="{{ asset('images/no-image-available-icon-61.jpg') }}" style="width: 100%;">
											@endif
										</div>
										<div class="col-md-3">
											@if($applied_promotion->product_voucher == 1)
												<b>{{ $applied_promotion->promotion_title }}</b> <br>
												<b>{{ isset($data['lang']['lang']['type']) ? $data['lang']['lang']['type'] :'Type'}}: {{ isset($data['lang']['lang']['product_voucher']) ? $data['lang']['lang']['product_voucher'] :'Product Voucher'}}</b> <br>
												<b>{{ isset($data['lang']['lang']['from_transaction']) ? $data['lang']['lang']['from_transaction'] :'From Transaction'}}: {{ $applied_promotion->transaction_id }}</b> <br>
												<b>{{ isset($data['lang']['lang']['quantity']) ? $data['lang']['lang']['quantity'] :'Quantity'}}: {{ $get_balance[$applied_promotion->promotion_id] }}</b>
											@else
												<b>{{ $applied_promotion->promotion_title }}</b> <br>
												<b>{{ isset($data['lang']['lang']['type']) ? $data['lang']['lang']['type'] :'Type'}}: {{ isset($data['lang']['lang']['system_voucher']) ? $data['lang']['lang']['system_voucher'] :'System Voucher'}}</b> <br>
												<b>{{ isset($data['lang']['lang']['voucher']) ? $data['lang']['lang']['voucher'] :'优惠券'}}: </b> 
													@if($applied_promotion->free_shipping == 1)
														{{ isset($data['lang']['lang']['free_shipping']) ? $data['lang']['lang']['free_shipping'] :'Free Shipping'}}
													@else
														{{ ($applied_promotion->amount_type == 'Percentage') ? $applied_promotion->amount."%" : "RM ".$applied_promotion->amount }}
													@endif
												<br>
												<b>{{ isset($data['lang']['lang']['discount_code']) ? $data['lang']['lang']['discount_code'] :'Discount Code'}}: {{ $applied_promotion->discount_code }}</b><br>
												<b>{{ isset($data['lang']['lang']['quantity']) ? $data['lang']['lang']['quantity'] :'Quantity'}}: {{ $get_quantity[$applied_promotion->discount_code] }}</b><br>			
												<b>{{ isset($data['lang']['lang']['expiry_date']) ? $data['lang']['lang']['expiry_date'] :'失效日期与时间'}}: </b> {{ $applied_promotion->end_date }} <br>
												<b>{{ isset($data['lang']['lang']['voucher_code']) ? $data['lang']['lang']['voucher_code'] :'优惠券代码'}}: </b> {{ $applied_promotion->discount_code }}
											@endif
										</div>
										<div class="col-md-7" align="right">
											<input type="hidden" name="apid" class="apid" value="{{ $applied_promotion->apid }}">
											<!-- <button class="btn btn-warning btn-sm claim-voucher" data-id="{{ $applied_promotion->discount_code }}">
												使用
											</button> -->
										</div>
									</div>
								</div>
								<hr>
								@endif
							@endforeach
						@else
						<div class="form-group" align="center">
							{{ isset($data['lang']['lang']['no_voucher_available']) ? $data['lang']['lang']['no_voucher_available'] :'你目前没有任何优惠券'}}
						</div>
						<div class="form-group" align="center">
							<a href="{{ route('home') }}" class="btn btn-primary set_button set_text">
								{{ isset($data['lang']['lang']['continue_shopping']) ? $data['lang']['lang']['continue_shopping'] :'继续购物'}}
							</a>
						</div>
						@endif	
			  	</div>
			  	<div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
			  		@if(!$deduct_vouchers->isEmpty())
							@foreach($deduct_vouchers as $deduct_voucher)
								<div class="form-group wish-row">
									<div class="row">
										<div class="col-md-2" align="center">
											@if(!empty($deduct_voucher->image))
											<img src="{{ asset($deduct_voucher->image) }}" style="width: 100px;height:100px">
											@else
											<img src="{{ asset('images/no-image-available-icon-61.jpg') }}" style="width: 100%;">
											@endif
										</div>
										<div class="col-md-3">
											@if($deduct_voucher->product_voucher == 1)
												<b>{{ $deduct_voucher->promotion_title }}</b> <br>
												<b>{{ isset($data['lang']['lang']['type']) ? $data['lang']['lang']['type'] :'Type'}}: {{ isset($data['lang']['lang']['product_voucher']) ? $data['lang']['lang']['product_voucher'] :'Product Voucher'}}</b> <br>
												<b>{{ isset($data['lang']['lang']['redeem_quantity']) ? $data['lang']['lang']['redeem_quantity'] :'Redeem Quantity'}}: x{{ $deduct_voucher->amount }}</b><br>
												<b>{{ isset($data['lang']['lang']['redeem_date']) ? $data['lang']['lang']['redeem_date'] :'Redeem Date'}}: {{ $deduct_voucher->created_at }}</b>
											@else
												<b>{{ $deduct_voucher->promotion_title }}</b> <br>
												<b>{{ isset($data['lang']['lang']['type']) ? $data['lang']['lang']['type'] :'Type'}}: {{ isset($data['lang']['lang']['system_voucher']) ? $data['lang']['lang']['system_voucher'] :'System Voucher'}}</b> <br>
												<b>{{ isset($data['lang']['lang']['voucher']) ? $data['lang']['lang']['voucher'] :'优惠券'}}: </b> 
													@if($deduct_voucher->free_shipping == 1)
														{{ isset($data['lang']['lang']['free_shipping']) ? $data['lang']['lang']['free_shipping'] :'Free Shipping'}}
													@else
														{{ ($deduct_voucher->amount_type == 'Percentage') ? $deduct_voucher->amount."%" : "RM ".$deduct_voucher->amount }}
													@endif
												<br>
												<b>{{ isset($data['lang']['lang']['discount_code']) ? $data['lang']['lang']['discount_code'] :'Discount Code'}}:{{ $deduct_voucher->discount_code }}</b><br>
												<b>{{ isset($data['lang']['lang']['expiry_date']) ? $data['lang']['lang']['expiry_date'] :'失效日期与时间'}}: </b> {{ $deduct_voucher->end_date }} <br>
												<b>{{ isset($data['lang']['lang']['voucher_code']) ? $data['lang']['lang']['voucher_code'] :'优惠券代码'}}: </b> {{ $deduct_voucher->discount_code }}
											@endif
										</div>
									</div>
								</div>
								<hr>
							@endforeach
						@else
						<div class="form-group" align="center">
							{{ isset($data['lang']['lang']['no_voucher_available']) ? $data['lang']['lang']['no_voucher_available'] :'你目前没有任何优惠券'}}
						</div>
						<div class="form-group" align="center">
							<a href="{{ route('home') }}" class="btn btn-primary set_button set_text">
								{{ isset($data['lang']['lang']['continue_shopping']) ? $data['lang']['lang']['continue_shopping'] :'继续购物'}}
							</a>
						</div>
						@endif
			  	</div>
				<div class="tab-pane fade" id="expired-profile" role="tabpanel" aria-labelledby="pills-expired-tab">
					@if(!$applied_promotions->isEmpty())
					@foreach($applied_promotions as $applied_promotion)
					@php
					$currentDate = new DateTime();
					$currentDate = $currentDate->format('Y-m-d');

					$voucherEndDate = new DateTime($applied_promotion->end_date);
					$voucherEndDate = $voucherEndDate->format('Y-m-d');
					@endphp

					@if($currentDate >= $voucherEndDate)
					<div class="form-group wish-row">
						<div class="row">
							<div class="col-md-2" align="center">
								@if(!empty($applied_promotion->image))
								<img src="{{ asset($applied_promotion->image) }}" style="width: 100px;height:100px">
								@else
								<img src="{{ asset('images/no-image-available-icon-61.jpg') }}" style="width: 100%;">
								@endif
							</div>
							<div class="col-md-3">
								@if($applied_promotion->product_voucher == 1)
								<b>{{ $applied_promotion->promotion_title }}</b> <br>
								<b>{{ isset($data['lang']['lang']['type']) ? $data['lang']['lang']['type'] :'Type'}}: {{ isset($data['lang']['lang']['product_voucher']) ? $data['lang']['lang']['product_voucher'] :'Product Voucher'}}</b> <br>
								<b>{{ isset($data['lang']['lang']['from_transaction']) ? $data['lang']['lang']['from_transaction'] :'From Transaction'}}: {{ $applied_promotion->transaction_id }}</b> <br>
								<b>{{ isset($data['lang']['lang']['quantity']) ? $data['lang']['lang']['quantity'] :'Quantity'}}: x{{ $get_balance[$applied_promotion->promotion_id] }}</b>
								@else
								<b>{{ $applied_promotion->promotion_title }}</b> <br>
								<b>{{ isset($data['lang']['lang']['type']) ? $data['lang']['lang']['type'] :'Type'}}: {{ isset($data['lang']['lang']['system_voucher']) ? $data['lang']['lang']['system_voucher'] :'System Voucher'}}</b> <br>
								<b>{{ isset($data['lang']['lang']['voucher']) ? $data['lang']['lang']['voucher'] :'优惠券'}}: </b>
								@if($applied_promotion->free_shipping == 1)
								{{ isset($data['lang']['lang']['free_shipping']) ? $data['lang']['lang']['free_shipping'] :'Free Shipping'}}
								@else
								{{ ($applied_promotion->amount_type == 'Percentage') ? $applied_promotion->amount."%" : "RM ".$applied_promotion->amount }}
								@endif
								<br>
								<b>{{ isset($data['lang']['lang']['discount_code']) ? $data['lang']['lang']['discount_code'] :'Discount Code'}}: {{ $applied_promotion->discount_code }}</b><br>
								<b>{{ isset($data['lang']['lang']['quantity']) ? $data['lang']['lang']['quantity'] :'Quantity'}}: {{ $get_quantity[$applied_promotion->discount_code] }}</b><br>	
								<b>{{ isset($data['lang']['lang']['expiry_date']) ? $data['lang']['lang']['expiry_date'] :'失效日期与时间'}}: </b> {{ $applied_promotion->end_date }} <br>
								<b>{{ isset($data['lang']['lang']['voucher_code']) ? $data['lang']['lang']['voucher_code'] :'优惠券代码'}}: </b> {{ $applied_promotion->discount_code }}
								@endif
							</div>
							<div class="col-md-7" align="right">
								<input type="hidden" name="apid" class="apid" value="{{ $applied_promotion->apid }}">
								<!-- <button class="btn btn-warning btn-sm claim-voucher" data-id="{{ $applied_promotion->discount_code }}">
												使用
											</button> -->
							</div>
						</div>
					</div>
					<hr>
					@endif
					
					@endforeach
					@else
					<div class="form-group" align="center">
						{{ isset($data['lang']['lang']['no_voucher_available']) ? $data['lang']['lang']['no_voucher_available'] :'你目前没有任何优惠券'}}
					</div>
					<div class="form-group" align="center">
						<a href="{{ route('home') }}" class="btn btn-primary set_button set_text">
							{{ isset($data['lang']['lang']['continue_shopping']) ? $data['lang']['lang']['continue_shopping'] :'继续购物'}}
						</a>
					</div>
					@endif
				</div>
			</div>
			<br>
	@if(empty($getUserDetails->get_applied_voucher->id))
						<div class="form-group promotion-field">
							<div class="row">
								
								<div class="col-12" align="center">
									<a href="#" data-toggle="modal" data-target="#applyPromotion" class="btn btn-primary set_button set_text">
										  {{ isset($data['lang']['lang']['apply_a_voucher']) ? $data['lang']['lang']['apply_a_voucher'] :'申请优惠券' }}
									</a>

									<div class="modal fade" id="applyPromotion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
										  <div class="modal-dialog modal-dialog-centered">
											<div class="modal-content">
												  <div class="modal-header">
													<h5 class="modal-title" id="exampleModalLabel" align="center">
														{{ isset($data['lang']['lang']['apply_voucher']) ? $data['lang']['lang']['apply_voucher'] : 'Apply Voucher' }}
													</h5>
													<button type="button" class="close close-promo-list" data-dismiss="modal" aria-label="Close">
														  <span aria-hidden="true">&times;</span>
													</button>
												  </div>
												  <div class="modal-body">
													<div class="input-group">
														<input type="text" name="result" class="form-control discount-code" placeholder="{{ isset($data['lang']['lang']['voucher_code']) ? $data['lang']['lang']['voucher_code'] : 'Voucher Code' }}"
															   value="">
														<span class="input-group-btn">
															<button type="submit" class="btn btn-primary btn-white apply-discount set_button set_text">
																{{ isset($data['lang']['lang']['apply']) ? $data['lang']['lang']['apply'] : 'Apply' }}
															</button>
														</span>
													</div>
													<div class="error-message-promo important-text"></div>
													<hr>
													@if(!empty($getUserDetails->get_saved_vouchers))
														@foreach($getUserDetails->get_saved_vouchers as $getClaimedPromo)
														<div class="form-group" align="left">
															<input type="hidden" name="apid" class="apid" value="{{ $getClaimedPromo->get_voucher_detail->id }}">
															<a href="#" class="claim-voucher" style="display: block; width: 100%;" data-id="{{ $getClaimedPromo->get_voucher_detail->discount_code }}">
																<div class="row">
																	<div class="col-3">
																		<img src="{{ asset(!empty($getClaimedPromo->get_voucher_detail->image) ? $getClaimedPromo->get_voucher_detail->image : 'images/no-image-available-icon-61.jpg') }}" style="width: 70px;">
																	</div>
																	<div class="col-9">
																		<b>{{ $getClaimedPromo->get_voucher_detail->promotion_title }}</b><br>
																		@if(!empty($getClaimedPromo->get_voucher_detail->free_shipping))
																		Offer: Free Shipping Voucher <br>
																		@else
																		Offer: {{ ($getClaimedPromo->get_voucher_detail->amount_type == 'Percentage') ? $getClaimedPromo->get_voucher_detail->amount."%" : 'RM '.$getClaimedPromo->get_voucher_detail->amount }} OFF<br>
																		@endif
																		Expiry: {{ $getClaimedPromo->get_voucher_detail->end_date }}<br>
																		Code: {{ $getClaimedPromo->get_voucher_detail->discount_code }}<br>
																	</div>
																</div>
															</a>
														</div>
														<hr>
														@endforeach
													@endif
												  </div>
												  <button type="button" class="btn btn-secondary close-modal set_button set_text" data-dismiss="modal" style="display: none;">{{ isset($data['lang']['lang']['close']) ? $data['lang']['lang']['close'] : 'Close' }}</button>
												
											</div>
										  </div>
									</div>
								</div>
							</div>
						</div>
						@endif
	</div>
</div>
</div>
@endsection

@section('js')
<script type="text/javascript">
	$('.claim-voucher').click( function(e){
        e.preventDefault();

        var ele = $(this);
        var promo_id = ele.data('id');
        var apid = ele.parent().find('.apid').val();
        
        $('.loading-gif').show();
        var fd = new FormData();
        fd.append('discount_code', promo_id);
        fd.append('use', '1');
        fd.append('apid', apid);

        $.ajax({
           url: '{{ route("ApplyPromo") }}',
           type: 'post',
           data: fd,
           contentType: false,
           processData: false,
           success: function(response){
                $('.loading-gif').hide();
                toastr.success('成功使用优惠券！');
           }
        });
    });

	$('.apply-discount').click( function(e){
	e.preventDefault();
	
	var discount_code = $('.discount-code').val();
	if(discount_code){
		$('.loading-gif').show();
		var fd = new FormData();
		fd.append('discount_code', discount_code);
		fd.append('checkout_apply', '1');

		$.ajax({
		   url: '{{ route("ApplyPromo") }}',
		   type: 'post',
		   data: fd,
		   contentType: false,
		   processData: false,
		   success: function(response){
				   $('.loading-gif').hide();
				   
				   // alert(response);
				   // return false;
				   if(response == 0){
					$('.error-message-promo').html('Invalid Promotion Code');
					return false;
				   }else if(response == 1){
					$('.error-message-promo').html('Promotion Code out of limit');
					return false;
				   }else if(response == 2){
					$('.error-message-promo').html('Promotion Code not in date range');
					return false;
				   }else if(response == 3){
					$('.error-message-promo').html('Your shopping cart does not meet the requirements of the Promotion Code: '+discount_code+'.');
					return false;		       			
				   }else if(response == 4){
					$('.error-message-promo').html('Promotion Code out of limit.');
					return false;		       			
				   }else if(response == 5){
					$('.error-message-promo').html('Promotion Code out of limit.');
					return false;		       			
				   }else{
					   location.reload();
					   if(response[1] == 'Percentage'){
						   var total = $('#subtotal').val() * response[0] / 100;
						   var grand_total = $('#subtotal').val() - total;
						   var shipping_fee = $('.hidden_shipping_amount').val();

						   $('.discount_word').html('Discount ('+response[0]+'%)');
						   $('.discount_amount').html('- RM '+parseFloat(total).toFixed(2));
						   $('.hidden_discount').val(parseFloat(total).toFixed(2));

						   if($('.payment_method.active').data('id') == '2'){
							   $('.processing_amount').html('RM 0.00');
							   $('.grand-total').html('RM '+parseFloat((parseFloat(grand_total) + parseFloat(shipping_fee))).toFixed(2));
							   $('#hidden_grand_total').val(parseFloat((parseFloat(grand_total) + parseFloat(shipping_fee))).toFixed(2));
						   }else{
							   // $('.processing_amount').html('RM '+parseFloat((parseFloat(grand_total) + parseFloat(shipping_fee)) * 1.6 / 100).toFixed(2));
							   $('.grand-total').html('RM '+parseFloat((parseFloat(grand_total) + parseFloat(shipping_fee))).toFixed(2));
							   $('#hidden_grand_total').val(parseFloat((parseFloat(grand_total) + parseFloat(shipping_fee))).toFixed(2));
							   
						   }

						   $('#code').val(response[2]);
						   $('#totalDiscount').val(total);
					   }else{
						   var total = response[0];
						   var shipping_fee = $('.hidden_shipping_amount').val();
						   var grand_total = parseFloat($('#subtotal').val()) + parseFloat(shipping_fee) - total;
						   
						   $('.discount_word').html('Discount: ');
						   if(grand_total <= 0){

							   $('.discount_amount').html('RM '+parseFloat(total).toFixed(2));
							   $('.hidden_discount').val(parseFloat(total).toFixed(2));

							   if($('.payment_method.active').data('id') == '2'){

								   $('.grand-total').html('RM '+parseFloat(shipping_fee).toFixed(2));
								   $('.processing_amount').html('RM 0.00');
								   $('.hidden_processing_amount').val(0);
								   $('#hidden_grand_total').val(shipping_fee);

							   }else{

								   // $('.processing_amount').html('RM '+parseFloat(parseFloat(shipping_fee) * 1.6 / 100).toFixed(2));
								   // $('.hidden_processing_amount').val(parseFloat(parseFloat(shipping_fee) * 1.6 / 100).toFixed(2));

								   $('.grand-total').html('RM '+parseFloat(parseFloat(shipping_fee)).toFixed(2));
								   $('#hidden_grand_total').val(parseFloat(parseFloat(shipping_fee)).toFixed(2));

							   }
						   }else{
							   if($('.payment_method.active').data('id') == '2'){
								   $('.discount_amount').html('- RM '+parseFloat(total).toFixed(2));
								   $('.hidden_discount').val(parseFloat(total).toFixed(2));
								   $('.processing_amount').html('RM 0.00');
								   $('.hidden_processing_amount').val(0);
								   $('.grand-total').html('RM '+parseFloat(grand_total).toFixed(2));
								   $('#hidden_grand_total').val(parseFloat(grand_total).toFixed(2));

							   }else{
								$('.discount_amount').html('- RM '+parseFloat(total).toFixed(2));
								   $('.hidden_discount').val(parseFloat(total).toFixed(2));
								   // $('.processing_amount').html('RM '+parseFloat(parseFloat(grand_total) * 1.6 / 100).toFixed(2));
								   // $('.hidden_processing_amount').val(parseFloat(parseFloat(grand_total) * 1.6 / 100).toFixed(2));
								   $('.grand-total').html('RM '+parseFloat(parseFloat(grand_total)).toFixed(2));
								   $('#hidden_grand_total').val(parseFloat(parseFloat(grand_total)).toFixed(2));
							   }
						   }

						   $('#code').val(response[2]);
						   $('#totalDiscount').val(total);

					   }
					   $('.close-modal').click();
					   $('.modal-backdrop').remove();
					   $('.modal-open').css('overflow', 'auto');
		//    			$(function () {
					//    $('#applyPromotion').modal('hide');
					// });
					   $('.success-message-promo').html('Applied Successfully - '+response[5]+'('+response[4]+') <a href="#" class="remove-applied-promo pull-right" data-id="'+response[3]+'">Remove</a>');
					   $('.promotion-field').remove();
				   }
		   },
		});
	}else{
		$('.error-message-promo').html('Please fill in Promotion Code');
		return false;
	}
});
</script>
@endsection