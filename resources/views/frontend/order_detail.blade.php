@extends('layouts.app')

@section('content')

@include('partial.frontend.profile_header')
<div class="profile-content pb-5">
	<div class="container">
		<div class="form-group">
			<div class="row">
				<div class="col-sm-12 myOrder-list">
					<div class="form-group container-box">
						<div class="row">
							<div class="col-lg-6 col-md-6 col-sm-12 col-12 order-lg-1 order-2">
								{{ isset($data['lang']['lang']['order_no']) ? $data['lang']['lang']['order_no'] :'单号'}}:<b> #{{ $transaction->transaction_no }}</b><br>
								{{ isset($data['lang']['lang']['order_dates']) ? $data['lang']['lang']['order_dates'] :'订单日期'}}: <b>{{ $transaction->created_at }}</b><br>
								{{ isset($data['lang']['lang']['payment_method']) ? $data['lang']['lang']['payment_method'] :'Payment Method'}}: 
					            @if($transaction->mall == 1)
					                <b>{{ isset($data['lang']['lang']['cash_wallet']) ? $data['lang']['lang']['cash_wallet'] :'Cash Wallet'}}</b>
					            @elseif($transaction->mall == 2)
					            	<b>{{ isset($data['lang']['lang']['topup_wallet']) ? $data['lang']['lang']['topup_wallet'] :'Topup Wallet'}}</b>
					            @elseif(!empty($transaction->bank_id))
					                <b>{{ isset($data['lang']['lang']['online_banking']) ? $data['lang']['lang']['online_banking'] :'Online Banking'}}</b>
					            @elseif(!empty($transaction->bank_slip))
					                <b>{{ isset($data['lang']['lang']['bank_transfer']) ? $data['lang']['lang']['bank_transfer'] :'Bank Transfer'}}</b>
					            @elseif(!empty($transaction->pv_purchase))
					                <b>{{ isset($data['lang']['lang']['point_wallet']) ? $data['lang']['lang']['point_wallet'] :'Point Wallet'}}</b>
					            @endif
								<br>
								{{ isset($data['lang']['lang']['pickup_method']) ? $data['lang']['lang']['pickup_method'] :'取货方式'}}: 
								<b>
									@if((!empty($transaction->self_pick) && $transaction->self_pick == 1))
										{{ isset($data['lang']['lang']['self_pickup']) ? $data['lang']['lang']['self_pickup'] :'自提'}}
									@elseif(!empty($transaction->cod_address))
										{{ isset($data['lang']['lang']['self_pickup']) ? $data['lang']['lang']['self_pickup'] :'自提'}}
									@else
										{{ isset($data['lang']['lang']['courier_service']) ? $data['lang']['lang']['courier_service'] :'快递服务'}}
									@endif									
								</b>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-12 col-12 d-text-right order-lg-2 order-1">
								@if($transaction->pv_purchase == 1)
									<h4>{{ isset($data['lang']['lang']['total_price']) ? $data['lang']['lang']['total_price'] :'总价'}}: {{ number_format($transaction->grand_total, 2) }} Point</h4>
								@else
									<h4>{{ isset($data['lang']['lang']['total_price']) ? $data['lang']['lang']['total_price'] :'总价'}}: RM {{ number_format($transaction->grand_total, 2) }}</h4>
								@endif
							</div>
						</div>
					</div>
					<div class="form-group container-box">
						@if(!empty($transaction->cod_address))
							<div class="form-group">
								<h4>
									<b>{{ isset($data['lang']['lang']['pickup_address']) ? $data['lang']['lang']['pickup_address'] :'取货地址'}}</b>
								</h4>
							</div>
							<div class="form-group">
								{{ $transaction->cod_address_name }} <br><br>
								{{ $transaction->address_desc }}
							</div>
							<div class="form-group">
								<h4>
									<b>{{ isset($data['lang']['lang']['pickup_personnel_details']) ? $data['lang']['lang']['pickup_personnel_details'] :'提货人资料'}}</b>
								</h4>
							</div>
							<div class="form-group">
								@if(!empty($transaction->pickup_f_name))
									<b>{{ isset($data['lang']['lang']['name']) ? $data['lang']['lang']['name'] :'名称'}}:</b> {{ $transaction->pickup_f_name }}<br>
								@endif
								@if(!empty($transaction->pickup_phone))
									<b>{{ isset($data['lang']['lang']['phone_number']) ? $data['lang']['lang']['phone_number'] :'电话号码'}}:</b> {{ $transaction->pickup_phone }} <br>
								@endif
								@if(!empty($transaction->pickup_email))
									<b>{{ isset($data['lang']['lang']['email_address']) ? $data['lang']['lang']['email_address'] :'电子邮件'}}:</b> {{ $transaction->pickup_email }} <br>
								@endif
							</div>
						@else
							<div class="form-group">
								<h4>
									<b>{{ isset($data['lang']['lang']['recipient_address']) ? $data['lang']['lang']['recipient_address'] :'收件地址'}}</b>
								</h4>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-1">
										<b>Name:</b> 
									</div>
									<div class="col-11">
										{{ $transaction->address_name }}
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-1">
										<b>Address:</b> 
									</div>
									<div class="col-11">
										{{ $transaction->address }} <br>
										{{ $transaction->postcode }} {{ $transaction->city_name }} <br>
										{{ !empty($transaction->state_name) ? $transaction->state_name : $transaction->state }}, 
										{{ $transaction->country_name }}
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-1">
										<b>Phone:</b> 
									</div>
									<div class="col-11">
										@if(!empty($transaction->country_code))
										+{{ $transaction->country_code }}
										@endif
										{{ $transaction->phone }}
									</div>
								</div>
							</div>								
						@endif
					</div>
					<div class="form-group container-box">
						@foreach($details as $detail)
						@php
						$image = (!empty($detail->product_image)) ? $detail->product_image : 'images/no-image-available-icon-6.jpg';
						@endphp
						<div class="form-group">
							<div class="row">
								<div class="col-sm-1" align="">
									<div class="from-group">
										<img src="{{ asset($image) }}" style="width: 70px;">
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group product-details">
										<b>{{ $detail->product_name }}</b> <br>
										<!-- {!! ($detail->sub_category != '') ? "选项: ".$detail->sub_category."<br>" : '' !!} -->
										@if($detail->sub_category != '')
											{{ isset($data['lang']['lang']['variation']) ? $data['lang']['lang']['variation'] :'选项'}}: <b>{{ $detail->sub_category }}</b><br>
										@else

										@endif

										<!-- {!! ($detail->second_sub_category != '') ? "选项: ".$detail->second_sub_category."<br>" : '' !!} -->
										@if($detail->second_sub_category != '')
											Second {{ isset($data['lang']['lang']['variation']) ? $data['lang']['lang']['variation'] :'选项'}}: <b>{{ $detail->second_sub_category }}</b><br>
										@else

										@endif
										{{ isset($data['lang']['lang']['quantity']) ? $data['lang']['lang']['quantity'] :'数量'}}: <b>x{{ $detail->quantity }}</b><br>
										@if($transaction->pv_purchase == 1)
											{{ isset($data['lang']['lang']['unit_price']) ? $data['lang']['lang']['unit_price'] :'单价'}}: <b>{{ number_format($detail->unit_price, 2) }} Point</b> <br>
										@else
											{{ isset($data['lang']['lang']['unit_price']) ? $data['lang']['lang']['unit_price'] :'单价'}}: <b>RM {{ number_format($detail->unit_price, 2) }}</b> <br>
										@endif

										@if(!empty($detail->get_pv))
											{{ $detail->get_pv }} {{ isset($data['lang']['lang']['pv']) ? $data['lang']['lang']['pv'] :'PV'}} <br>
										@endif
									</div>
				                    @if(!empty($detail->get_promo_title))
				                    <span class="badge bg-danger">
				                        {{ $detail->get_promo_title->promo_title }}
				                    </span>
				                    @endif
								</div>

								<div class="col-sm-4">
									@if($transaction->status == 99)
										<span class="badge badge-pill bg-warning">{{ isset($data['lang']['lang']['unpaid']) ? $data['lang']['lang']['unpaid'] :'未付款'}}</span>
									@elseif($transaction->status == 98)
										<span class="badge badge-pill badge-info">{{ isset($data['lang']['lang']['awaiting_verify']) ? $data['lang']['lang']['awaiting_verify'] :'等待验证'}}</span>
									@elseif($transaction->status == 97)
										<span class="badge badge-pill badge-info">{{ isset($data['lang']['lang']['in_progress']) ? $data['lang']['lang']['in_progress'] :'进行中'}}</span>
									@elseif($transaction->status == '96')
										<span class="badge bg-danger">{{ isset($data['lang']['lang']['rejected']) ? $data['lang']['lang']['rejected'] :'已拒绝'}}</span>
									@elseif($transaction->status == 1)
										<span class="badge bg-success">{{ isset($data['lang']['lang']['paid']) ? $data['lang']['lang']['paid'] :'已付款'}}</span>
									@else
										<span class="badge badge-pill bg-danger">{{ isset($data['lang']['lang']['cancelled']) ? $data['lang']['lang']['cancelled'] :'已取消'}}</span>
									@endif
								</div>
							</div>
						</div>
						<hr>
						@endforeach
					</div>
					<div class="form-group">
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group container-box">
								<div class="form-group">
									<h4>
										<b>{{ isset($data['lang']['lang']['bank_slip']) ? $data['lang']['lang']['bank_slip'] :'银行发票'}}</b>
									</h4>
								</div>

								@if(!empty($transaction->bank_slip))
									<a href="#" data-toggle="modal" data-target="#exampleSecondModal">
										<img src="{{ asset($transaction->bank_slip) }}" width="150px">
									</a>

									<div class="modal fade" id="exampleSecondModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
									  	<div class="modal-dialog">
									    	<div class="modal-content">
										      	<div class="modal-body">
										        	<img src="{{ asset($transaction->bank_slip) }}" width="100%">
										      	</div>
									    	</div>
									  	</div>
									</div>
								@else
									<h5>{{ isset($data['lang']['lang']['no_bank_slip']) ? $data['lang']['lang']['no_bank_slip'] :'没有银行发票'}}</h5>
								@endif
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group container-box">
								<div class="form-group">
									<h4>
										<b>{{ isset($data['lang']['lang']['summary']) ? $data['lang']['lang']['summary'] :'总结'}}</b>
									</h4>
								</div>
								<div class="form-group">
									<div class="row">
										<div class="col-6">
											{{ isset($data['lang']['lang']['sub_total']) ? $data['lang']['lang']['sub_total'] :'合计'}}: 
										</div>
										<div class="col-6" align="right">
											<b>
												@if($transaction->pv_purchase == 1)
													{{ number_format(str_replace(',', '', $transaction->sub_total), 2) }} Point
												@else
													RM {{ number_format(str_replace(',', '', $transaction->sub_total), 2) }}
												@endif												
											</b>
										</div>
									</div>
								</div>
								@if(!empty($transaction->ad_discount))
								@php
									if($transaction->ad_discount_type == 'Percentage'){
										$display_ad = "(".$transaction->ad_discount_amount."%)";
									}else{
										$display_ad = "(RM ".number_format($transaction->ad_discount_amount, 2).")";
									}
								@endphp
								<div class="form-group">
				                    <div class="row">
				                        <div class="col-6">
				                            {{ isset($data['lang']['lang']['ad_discount']) ? $data['lang']['lang']['ad_discount'] :'Additional Discount'}} {{ $display_ad }}: 
				                        </div>
				                        <div class="col-6" align="right">
				                        	<b>
				                            	(-) RM {{ number_format($transaction->ad_discount, 2) }}				                        		
				                        	</b>
				                        </div>
				                    </div>
				                </div>
				                @endif

								<div class="form-group">
				                    <div class="row">
				                        <div class="col-6">
				                            {{ isset($data['lang']['lang']['discount']) ? $data['lang']['lang']['discount'] :'优惠'}}
				                            @if(!empty($transaction->discount_code))
					                            (
					                            	@if(!empty($transaction->discount_code))
														{{ $transaction->discount_code }}
														->
													@endif

													@if($transaction->discount_type == 'Percentage')
				                                        {{ number_format($transaction->discount_amount, 2) }}%
				                                    @else
				                                        @if(!empty($transaction->pv_purchase))
				                                            {{ number_format($transaction->discount_amount, 2) }} {{ isset($data['lang']['lang']['point']) ? $data['lang']['lang']['point'] : 'Point' }}
				                                        @else
				                                            RM {{ number_format($transaction->discount_amount, 2) }}
				                                        @endif
				                                    @endif
					                            ) 
			                                @endif
				                            : 
				                        </div>

				                        <div class="col-6" align="right">
				                        	<b>
			                        		-
			                        		@if(!empty($transaction->pv_purchase))
			                                    {{ number_format($transaction->discount, 2) }} {{ isset($data['lang']['lang']['point']) ? $data['lang']['lang']['point'] : 'Point' }}
			                                @elseif(!empty($transaction->discount))
			                                    RM {{ number_format($transaction->discount, 2) }}
			                                @else
			                                	RM {{ number_format(0, 2) }}
			                                @endif
				                        	</b>
				                        </div>
				                    </div>
				                </div>

								<div class="form-group">
									<div class="row">
										<div class="col-6">
											{{ isset($data['lang']['lang']['shipping_fee']) ? $data['lang']['lang']['shipping_fee'] :'运费'}}: 
										</div>
										<div class="col-6" align="right">
				                        	<b>
												@if($transaction->pv_purchase == 1)
													{{ number_format($transaction->shipping_fee, 2) }} Point
												@else
													RM {{ number_format($transaction->shipping_fee, 2) }}
												@endif
				                        	</b>
										</div>
									</div>
								</div>

								<!-- <div class="form-group">
				                    <div class="row">
				                        <div class="col-6">
				                            Processing Fee: 
				                        </div>
				                        <div class="col-6" align="right">
				                            RM {{ number_format($transaction->processing_fee, 2) }}
				                        </div>
				                    </div>
				                </div> -->

								<hr>
								<div class="form-group">
									<div class="row">
										<div class="col-6">
											{{ isset($data['lang']['lang']['grand_total']) ? $data['lang']['lang']['grand_total'] :'总合计'}}: 
										</div>
										@if($transaction->pv_purchase == 1)
											<div class="col-6" align="right">
					                        	<h3>
													{{ number_format($transaction->grand_total, 2) }} Point
					                        	</h3>
											</div>
										@else
											<div class="col-6" align="right">
												<h3>
													RM {{ number_format($transaction->grand_total, 2) }}
												</h3>
											</div>
										@endif
									</div>
								</div>
							</div>					
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection