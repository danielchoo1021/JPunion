@extends('layouts.admin_app')
@section('css')
<style type="text/css">
	.remove-cart-discount{
	    display: none;
	}

	.container-box{
		color: #000;
	}

	.ps-shoe__variants .ps-shoe__variant {
	    padding: 0 25px;
	    margin-bottom: 10px;
	}

	.ps-shoe__variants .ps-shoe__variant .owl-nav {
	    position: absolute;
	    top: 10%;
	    -webkit-transform: translateY(-50%);
	    -moz-transform: translateY(-50%);
	    -ms-transform: translateY(-50%);
	    -o-transform: translateY(-50%);
	    transform: translateY(-50%);
	    left: 0;
	    height: 0;
	    width: 100%;
	}

	.ps-shoe__variants .ps-shoe__variant .owl-prev, .ps-shoe .ps-shoe__variants .ps-shoe__variant .owl-next {
	    display: inline;
	    -webkit-transform: translateY(-50%);
	    -moz-transform: translateY(-50%);
	    -ms-transform: translateY(-50%);
	    -o-transform: translateY(-50%);
	    transform: translateY(-50%);
	}

	.ps-shoe__variants .ps-shoe__variant .owl-next {
	    float: right;
	}

	.items-list{
		width: 100%;
		height: 200px;
		position: relative;
		background-size: cover;
		background-repeat: no-repeat;
		background-position: center;
		box-shadow: 0 0 10px 0 #eee;
	}

	.items-content{
		position: absolute;
		top: 50%;
		left: 50%;
		transform: translate(-50%, -50%);
		color: #fff;
		width: 100%;
	    display: block;
	    text-align: center;
	    padding: 10px 10px;
	    background-color: rgba(0,0,0,.5);
	}

	.ps-shoe__variant.normal .category_list.active{
		text-decoration: underline;
	}

	.order-type.active{
		background-color: #2AC37D !important;
		color: #fff;
	}

	.select-order-type{
		cursor: pointer;
	}

	.col-lg-1, .col-lg-10, .col-lg-11, .col-lg-12, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9, .col-md-1, .col-md-10, .col-md-11, .col-md-12, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-sm-1, .col-sm-10, .col-sm-11, .col-sm-12, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-1, .col-10, .col-11, .col-12, .col-2, .col-3, .col-4, .col-5, .col-6, .col-7, .col-8, .col-9{
		position: sticky !important;
	}

	p{
		margin: 0px;
	}

	@page {
	    margin: 0;
	}

	@media print {
	    body, html{
	        margin: 0 !important;
	    }
	}

	.selected{
		background-color: rgba(128, 128, 128, 0.4);
		color: white;
		border-color:rgba(128, 128, 128, 0.4);
		box-shadow: 0 0 10px rgba(128, 128, 128, 0.4);
	}
</style>
@endsection
@section('content')
<!-- <div class="pr-10 pl-10 mt-10" style="margin-bottom: 80px;"> -->

<div class="pr-10 pl-10 pt-10" style="">
	<div class="form-group">
		<div class="row">
			<div class="col-6">
				<!-- <a href="#" class="btn btn-danger view-delivery-btn" data-toggle="modal" data-target="#view-delivery">
					Delivery <span class="badge bg-light">{{ $transactionCount }}</span>
				</a>

				<a href="#" class="btn btn-success combine-receipt-btn" data-toggle="modal" data-target="#combine-receipt">
					Combine Receipt
				</a>

				<a href="#" class="btn btn-info transfer-table-btn" data-toggle="modal" data-target="#transfer-table">
					Transfer Table
				</a> -->

				<a href="#" class="btn btn-danger refund-transaction-btn" data-toggle="modal" data-target="#refund-modal">
					{{ isset($data['backendlang']['backendlang']['Order_History']) ? $data['backendlang']['backendlang']['Order_History'] :'' }}
				</a>

				<a href="#" class="btn btn-info add-qr-btn" data-toggle="modal" data-target="#add-qr-modal" style="color:white">
					{{ isset($data['backendlang']['backendlang']['QR_Type']) ? $data['backendlang']['backendlang']['QR_Type'] :'' }}
				</a>
			</div>
			<!-- <div class="col-6" align="right"> -->
				<!-- <a href="#" class="btn btn-primary order-type" style="background-color: #fff; border-color: #84d8b6; color: #000;" data-filter="1">
					Dine In
				</a>
				<input type="hidden" name="" class="dine-in-table">
				<input type="hidden" name="" class="dine-in-table-headcount">
				<input type="hidden" name="" class="order-no">
				<a href="#" class="btn btn-warning order-type" style="background-color: #fff; border-color: #84d8b6; color: #000;" data-filter="2">
					Take away
				</a> -->
			<!-- </div> -->
		</div>
	</div>

	<div class="row">
		<div class="col-6">
			<div class="form-group container-box">
				<div class="row">
					<div class="col-3">
						<b>{{ isset($data['backendlang']['backendlang']['Item(s)']) ? $data['backendlang']['backendlang']['Item(s)'] :'' }}</b>
					</div>
					<div class="col-3" align="right">
						<b>{{ isset($data['backendlang']['backendlang']['Unit_Price']) ? $data['backendlang']['backendlang']['Unit_Price'] :'' }} (RM)</b>
					</div>
					<div class="col-3" align="right">
						<b>{{ isset($data['backendlang']['backendlang']['Quantity']) ? $data['backendlang']['backendlang']['Quantity'] :'' }}</b>
					</div>
					<div class="col-3" align="right">
						<b>{{ isset($data['backendlang']['backendlang']['Total_Price']) ? $data['backendlang']['backendlang']['Total_Price'] :'' }} (RM)</b>
					</div>
				</div>
			</div>
			<div class="form-group container-box selected-items" style="height: 61vh; overflow: auto;">
				@php
					$totalQty = 0;
					$totalPrice = 0;
				@endphp
				@if(!$carts->isEmpty())
					@foreach($carts as $cart)
					@php
						$price = $product_pricing[$cart->id]['product_price'];
						$addon_price = 0;
					@endphp
						<div class="row got-item">
							<div class="col-3">
								<input type="hidden" name="cid" class="cid" value="{{ md5($cart->id) }}">
								{{ $cart->get_product_det->product_name }}
								@if(!empty($cart->get_fv_det->id))
									<p>
										<small>
											<i class="bi bi-plus-square-fill" style="color: red;"></i> {{ $cart->get_product_det->variation_title }} - 
											{{ $cart->get_fv_det->variation_name}} 
										</small>
									</p>
								@endif
								@if(!empty($cart->get_sv_det->id))
									<p>
										<small>
											<i class="bi bi-plus-square-fill" style="color: red;"></i> {{ $cart->get_product_det->second_variation_title }} - 
											{{ $cart->get_sv_det->variation_name}} 
										</small>
									</p>
								@endif
							</div>
							<div class="col-3" align="right">
								RM 
								{{ number_format(($price), 2) }}
							</div>
							<div class="col-3" align="right">
								<div class="form-group quantity-setting">
		                            <button class="btn btn-primary deduct-qty-button">
		                              	<i class="bi bi-dash"></i>
		                            </button>
		                            <input type="text" class="form-control" name="quantity" value="{{ $cart->qty }}" onkeypress="return isNumberKey(event)">
		                            <button class="btn btn-primary add-qty-button">
		                              	<i class="bi bi-plus"></i>
		                            </button>
		                            <input type="hidden" name="balance_quantity" value="{{ $stockBalance[$cart->id] }}">
		                        </div>
							</div>
							<div class="col-3" align="right">
								RM 
	                            <span class="row-total-price">
	                              {{ number_format(($price) * $cart->qty, 2) }}
	                            </span>
							</div>
							<div class="col-12" style="word-wrap: break-word;">
								{{ isset($data['backendlang']['backendlang']['Remark']) ? $data['backendlang']['backendlang']['Remark'] :'' }}: {{ $cart->remark }}
								<hr>
							</div>
						</div>
						
					@php
					$totalQty += $cart->qty;
					$totalPrice += ($price + $addon_price) * $cart->qty;
					@endphp
					@endforeach
				@else
					<div class="row">
						<div class="col-12 no-item" align="center">
							{{ isset($data['backendlang']['backendlang']['Please_add_an_item']) ? $data['backendlang']['backendlang']['Please_add_an_item'] :'' }}
						</div>
					</div>
				@endif
			</div>
		</div>
		<div class="col-6" style="position: static !important;">
			<div class="form-group container-box" style="height: 67.5vh; overflow: auto;">
				<div class="form-group">
					<select class="form-control all_users select2" name="all_users">
						<option value="">{{ isset($data['backendlang']['backendlang']['Select_Buyer']) ? $data['backendlang']['backendlang']['Select_Buyer'] :'' }}</option>
						@foreach($all_users as $user)
						<option value="{{ $user->code }}">
							{{ $user->f_name }}  (+{{ $user->country_code }}{{ ($user->phone[0] == 0) ? substr($user->phone, 1) : $user->phone }})
						</option>
						@endforeach
					</select>
				</div>
				<div class="input-group">
		            <input type="text" name="name" class="form-control search-query" 
		            	   placeholder="{{ isset($data['backendlang']['backendlang']['Barcode, SKU, product name or category']) ? $data['backendlang']['backendlang']['Barcode, SKU, product name or category'] :'' }}" 
		            	   value="{{ !empty(request('name')) ? request('name') : '' }}" style="height: 34px;">
		            <span class="input-group-btn">
		                <button type="submit" class="btn btn-outline-primary btn-white search-button" style="outline: none; padding: 4px 10px;">
		                    <span class="ace-icon bi bi-search icon-on-right bigger-110"></span>
		                    {{ isset($data['backendlang']['backendlang']['Search']) ? $data['backendlang']['backendlang']['Search'] :'' }}
		                </button>
		            </span>
		        </div>
		        <br>
		        <div class="form-group search_result"></div>
		        <div class="form-group item-list-parent">
		        	<div class="row item-list-child">
		        		@foreach($categories as $category)
		        		<div class="col-4">
		        			<div class="form-group">
			        			<a href="#" class="items_option" data-filter="{{ $category->id }}">
				        			<div class="items-list" style="background-image: url({{ !empty($category->image) ? asset($category->image) : asset('images/no-image-available-icon-61.jpg')  }});">
				        				<div class="items-content">
				        					{{ $category->category_name }}
				        				</div>
				        			</div>
			        			</a>
		        			</div>
		        		</div>
		        		@endforeach
		        	</div>
		        </div>
		    </div>
		</div>
	</div>
</div>

<div class="form-group container-box">
	<div class="row">
		<div class="col-3">
			<b>{{ isset($data['backendlang']['backendlang']['Item(S)']) ? $data['backendlang']['backendlang']['Item(S)'] :'' }} (<span class="totalCart">{{ $totalQty }}</span>)</b>
		</div>
		<div class="col-9">
			<div class="row">
				<div class="col-6" align="right">
					<b>{{ isset($data['backendlang']['backendlang']['Sub_Total']) ? $data['backendlang']['backendlang']['Sub_Total'] :'' }} (RM)</b>
				</div>
				<div class="col-6" align="right">
					<span class="subTotal">{{ number_format($totalPrice, 2, '.', '') }}</span>
				</div>
			</div>
			<div class="row">
				<div class="col-6" align="right">
					<b>{{ isset($data['backendlang']['backendlang']['Discount']) ? $data['backendlang']['backendlang']['Discount'] :'' }} (RM)</b>
				</div>
				<div class="col-6" align="right">
					<a href="#" class="remove-cart-discount">{{ isset($data['backendlang']['backendlang']['Remove']) ? $data['backendlang']['backendlang']['Remove'] :'' }}</a>
					- <span class="discountTotal">0.00</span>
					<input type="hidden" class="hidden_discount_type">
					<input type="hidden" class="hidden_discount_amount">
				</div>
			</div>
			<div class="row">
				<div class="col-6" align="right">
					<b>{{ isset($data['backendlang']['backendlang']['Net_Total']) ? $data['backendlang']['backendlang']['Net_Total'] :'' }} (RM)</b>
				</div>
				<div class="col-6" align="right">
					<h3 class="grandTotal">{{ number_format($totalPrice, 2, '.', '') }}</h3>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="container-box">
	<a href="#" class="btn btn-success pay-order-btn">
		<i class="bi bi-currency-dollar"></i> {{ isset($data['backendlang']['backendlang']['Pay']) ? $data['backendlang']['backendlang']['Pay'] :'' }}
	</a>
	<!-- <a href="#" class="btn btn-info sendToKitchen">
		<i class="bi bi-send" aria-hidden="true"></i> Send to kitchen
	</a>
	<a href="#" class="btn btn-warning discount-btn" data-bs-toggle="modal" data-bs-target=".discount-modal">
		<i class="bi bi-tags" aria-hidden="true"></i> Discount
	</a> -->
</div>

<!-- <button type="button" class="btn btn-primary ConfirmSendKitchen" data-bs-toggle="modal" data-bs-target="#exampleModal"></button> -->
<button type="button" class="btn btn-primary ConfirmSendKitchen" data-bs-toggle="modal" data-bs-target="#ConfirmSendKitchen" style="display: none;">
  	Launch demo modal
</button>
<a class="SelectOrderType" data-toggle="modal" data-target="#SelectOrderType" style="display: none;"></a>
<div class="bottom-pop-up">
	<div class="bottom-pop-up-content">
		<div class="row">
			<div class="col-6">
				<img src="">
			</div>
			<div class="col-6">
				Vegetarian Food
			</div>
		</div>
		<hr>
		<div class="variation_area">
			<div class="ps-product--detail">
				<div class="ps-product__style">
			        <div class="ps-product__block ps-product__style v_v">
			        	<ul>
				            <li>
				            	<a href="#" class="variation_option" data-id="123">
				            		123
				            	</a>
				            </li>
				            <li>
				            	<a href="#" class="variation_option" data-id="123">
				            		321
				            	</a>
				            </li>
			          	</ul>
			        </div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="ConfirmSendKitchen" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
          <div class="form-group" align="center">
          	<h4>{{ isset($data['backendlang']['backendlang']['Would_You_Like_To_Proceed']) ? $data['backendlang']['backendlang']['Would_You_Like_To_Proceed'] :'' }}</h4>
          </div>
          <div class="form-group" align="center">
          	{{ isset($data['backendlang']['backendlang']['Would_You_Like_To_Send_Order_To_Kitchen']) ? $data['backendlang']['backendlang']['Would_You_Like_To_Send_Order_To_Kitchen'] :'' }}
          </div>

          <div class="form-group" align="center">
          	<button class="btn btn-success submit-to-kitchen">
          		{{ isset($data['backendlang']['backendlang']['Yes']) ? $data['backendlang']['backendlang']['Yes'] :'' }}
          	</button>
          	<button class="btn btn-danger" data-dismiss="modal">
          		{{ isset($data['backendlang']['backendlang']['No']) ? $data['backendlang']['backendlang']['No'] :'' }}
          	</button>
          </div>
      </div>
    </div>
  </div>
</div>


<button type="button" class="btn btn-primary open-table-list-btn" data-bs-toggle="modal" data-bs-target="#select_table">Large modal</button>
<div
    class="modal fade text-left"
    id="select_table"
    tabindex="-1"
    role="dialog"
    aria-labelledby="myModalLabel160"
    aria-hidden="true"
>
    <div
        class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl"
        role="document"
    >
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5
                    class="modal-title white"
                    id="myModalLabel160"
                >
                    {{ isset($data['backendlang']['backendlang']['Please_Select_A_Table']) ? $data['backendlang']['backendlang']['Please_Select_A_Table'] :'' }}
                </h5>
                <button
                    type="button"
                    class="close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                >
                    <i data-feather="x"></i>
                </button>
            </div>
            <div class="modal-body">
		      	<div class="row">
		      		<div class="col-5">
		      			<div class="form-group unselected-people-box container-box" align="center">
		      				{{ isset($data['backendlang']['backendlang']['Please_Select_A_Table']) ? $data['backendlang']['backendlang']['Please_Select_A_Table'] :'' }}
		      			</div>
		      			<div class="form-group selected-people-box" align="center">
			      			{{ isset($data['backendlang']['backendlang']['Table']) ? $data['backendlang']['backendlang']['Table'] :'' }} : <span class="selected-table-name"></span>
			      			<input type="hidden" class="selected_t_n_d" value="">
			      			<br>
			      			<label class="badge bg-success check-available">
			      				{{ isset($data['backendlang']['backendlang']['Available']) ? $data['backendlang']['backendlang']['Print'] :'' }}
			      			</label>
			      			<br>
			      			<br>
			      			<div class="form-group">
				      			<button class="btn btn-outline-warning btn-block btn-sm table-history">
				      				{{ isset($data['backendlang']['backendlang']['History']) ? $data['backendlang']['backendlang']['History'] :'' }}
				      			</button>
			      			</div>
			      			<div class="form-group">
				      			<button class="btn btn-danger btn-block btn-sm back-to-table-list">
				      				{{ isset($data['backendlang']['backendlang']['Close']) ? $data['backendlang']['backendlang']['Close'] :'' }}
				      			</button>
			      			</div>
			      			<hr>
			      			<div class="form-group current_order" align="center">
			      				
		      				</div>
		      				<div class="form-group current_order_action" align="center">
		      					<input type="hidden" class="pay-now-order-no">
		      					<button class="btn btn-outline-danger btn-sm pay-now-btn">
			      					{{ isset($data['backendlang']['backendlang']['Pay_Now']) ? $data['backendlang']['backendlang']['Pay_Now'] :'' }}
			      				</button>
			      				<button class="btn btn-outline-info btn-sm add-new-item-btn">
			      					{{ isset($data['backendlang']['backendlang']['Add_New_Item']) ? $data['backendlang']['backendlang']['Add_New_Item'] :'' }}
			      				</button>
			      				<button class="btn btn-outline-warning btn-sm print-transaction">
			      					{{ isset($data['backendlang']['backendlang']['Print']) ? $data['backendlang']['backendlang']['Print'] :'' }}
			      				</button>
		      				</div>

			      			<div class="form-group head-count-box" align="center">
			      				<p>{{ isset($data['backendlang']['backendlang']['How_Many_People_Join_The_Party']) ? $data['backendlang']['backendlang']['How_Many_People_Join_The_Party'] :'' }}</p>
			      				<br>
			      				<div class="row">
			      					@for($a=1; $a<=9; $a++)
				      					<div class="col-4">
				      						<div class="form-group">
					      						<button class="btn btn-outline-primary btn-block number-of-people" data-filter="{{ $a }}" align="center">
					      							{{ $a }}
					      						</button>
				      						</div>
				      					</div>
			      					@endfor
			      				</div>

			      				<div class="form-group">
				      				<button class="btn useTable btn-block btn-success btn-sm">
				      					{{ isset($data['backendlang']['backendlang']['Use_Table']) ? $data['backendlang']['backendlang']['Use_Table'] :'' }}
				      				</button>
			      				</div>

			      				<div class="form-group">
				      				<button class="btn print-qr btn-block btn-primary btn-sm">
				      					{{ isset($data['backendlang']['backendlang']['Print_QRcode']) ? $data['backendlang']['backendlang']['Print_QRcode'] :'' }}
				      				</button>
			      				</div>

		      				</div> 
		      			</div>
		      		</div>
		      		<div class="col-7">
		      			<div class="table-history-box">
		      			</div>
		      			<div class="select-table-box">
		      				<div class="row">
			      				@foreach($tables as $table)
			      				<div class="col-4">
			      					<div class="form-group container-box select-table" align="center" data-filter="{{ $table->id }}">
			      						<img src="{{ asset('images/table/table.png') }}" width="50%">
			      						<br>
			      						{{ $table->table_name }}
			      						<input type="hidden" name="table-name" class="table-name" value="{{ $table->table_name }}">
			      					</div>
			      				</div>
			      				@endforeach
		      				</div>
		      			</div>
		      		</div>
		      	</div>
	    	</div>
        </div>
    </div>
</div>

<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#payment_box" style="display: none;">
  Launch demo modal
</button>

<!-- Modal -->
<div class="modal fade" id="payment_box" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  	<div class="modal-dialog modal-xl" role="document">
    	<div class="modal-content">
      	<div class="modal-header">
        	<h4 class="modal-title" id="myModalLabel">
        		{{ isset($data['backendlang']['backendlang']['Choose_Payment_Method']) ? $data['backendlang']['backendlang']['Choose_Payment_Method'] :'' }}
        	</h4>
      	</div>
      	<div class="modal-body" align="center">
      		<!-- <div class="form-group" align="left">
      			<label>Member</label>
      			<input type="text" class="form-control member" name="member" onkeypress="return isNumberKey(event)" placeholder="Phone No.">
      		</div> -->
      		<div class="form-group member_point">
      		</div>
      		<hr>
      		<div class="form-group">
				<div class="row">
					<div class="col-md-4">
						<div class="form-group container-box payment-method active" data-filter="1">
							<img src="{{ asset('images/unnamed.png') }}" width="50%">
							<br>
							{{ isset($data['backendlang']['backendlang']['Pay_By_Cash']) ? $data['backendlang']['backendlang']['Pay_By_Cash'] :'' }}
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group container-box payment-method" data-filter="2">
							<img src="{{ asset('images/scan-pay-payment-01-512.png') }}" width="50%">
							<br>
							{{ isset($data['backendlang']['backendlang']['Pay_By_QR_Code']) ? $data['backendlang']['backendlang']['Pay_By_QR_Code'] :'' }}
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group container-box payment-method" data-filter="3">
							<img src="{{ asset('images/credit-card-back-symbol-5.png') }}" width="50%">
							<br>
							{{ isset($data['backendlang']['backendlang']['Pay_By_Credit_Debit_Card']) ? $data['backendlang']['backendlang']['Pay_By_Credit_Debit_Card'] :'' }}
						</div>
					</div>
				</div>
      		</div>

      		<input type="hidden" class="pay-order-no">
			<input type="hidden" class="pay-table">
			<input type="hidden" class="pay-order-type">
  			<div class="before-discount">
      			<a href="#" data-toggle="modal" data-target="#checkout-add-discount-modal">
      				{{ isset($data['backendlang']['backendlang']['Add_Discount']) ? $data['backendlang']['backendlang']['Add_Discount'] :'' }}
      			</a>
  			</div>
  			<div class="after-discount">
  			</div>
  			<hr>
  			<div class="form-group">
  				<div class="row justify-content-center">
  					<div class="col-12 toggle-point-area" style="display: none;" align="center">
  						<div class="check-box">
						  	{{ isset($data['backendlang']['backendlang']['Redeem_Point']) ? $data['backendlang']['backendlang']['Redeem_Point'] :'' }} <input type="checkbox" class="toggle-point" name="toggle_point" value="1">
						</div>
			  			<div class="point-discount">
			  			</div>
			  			<input type="hidden" class="member_point">
  					</div>
  				</div>
  			</div>
  			<hr>
			<h4>
  				{{ isset($data['backendlang']['backendlang']['Order_Amount']) ? $data['backendlang']['backendlang']['Order_Amount'] :'' }}: 
  				<br>
  				<br>
  				RM <span class="totalAmount" style="font-weight: bold;"></span>
  				<input type="hidden" name="totalAmount" class="totalHiddenAmount">
  				<input type="hidden" name="totalActualHiddenAmount" class="totalActualHiddenAmount">
  			</h4>
  			<hr>
      		<div class="form-group cash-payment-box container-box">
      			<div class="form-group">
      				<div class="input-group mb-3">
					  	<span class="input-group-text" id="basic-addon1">RM</span>
					  	<input type="text" class="form-control" name="amount" placeholder="{{ isset($data['backendlang']['backendlang']['Amount']) ? $data['backendlang']['backendlang']['Amount'] :'' }}" aria-label="Amount" aria-describedby="basic-addon1">
					</div>
	      			<!-- <div class="input-group">
					  <span class="input-group-addon" id="basic-addon1" style="background-color: #29c07b; border: 1px solid #29c07b; color: #fff;">RM</span>
					  <input type="text" class="form-control required-field" name="amount" placeholder="Amount" onkeypress="return isNumberKey(event)">
					</div> -->
	      		</div>
	      		<div class="form-group">
	      			{{ isset($data['backendlang']['backendlang']['Changes']) ? $data['backendlang']['backendlang']['Changes'] :'' }}: <span class="changes-amount" style="font-size: 14px;">RM 0.00</span>
	      		</div>
	      		<!-- <div class="form-group">
	      			<button class="btn">Pay</button>
	      		</div> -->
      		</div>

      		<div class="form-group container-box qr-pay-list" style="display: none;">
      			<h4>{{ isset($data['backendlang']['backendlang']['Choose_QR_Pay_Type']) ? $data['backendlang']['backendlang']['Choose_QR_Pay_Type'] :'' }}</h4>
      			<hr>
      			<div class="form-group">
	      			<div class="row" style="display: flex; justify-content: center;">
		      			@foreach($qr_pay_lists as $qr_pay_list)
		      				<div class="col-md-4" align="center">
		      					<img src="{{ asset( $qr_pay_list->image ) }}" class="qr-pay-type" data-id="{{ $qr_pay_list->id }}" width="100px">
								<br>
								<span>{{ $qr_pay_list->title }}</span>
		      				</div>
		      			@endforeach
	      			</div>
      			</div>
      			<div class="">
      				<input type="text" class="form-control" name="reference_number" value="" placeholder="{{ isset($data['backendlang']['backendlang']['Reference_Number']) ? $data['backendlang']['backendlang']['Reference_Number'] :'' }}">
      			</div>
      		</div>

      		<div class="form-group container-box credit-card-payment" style="display: none;">
      			<h4>{{ isset($data['backendlang']['backendlang']['Choose_Bank']) ? $data['backendlang']['backendlang']['Choose_Bank'] :'' }}</h4>
      			<hr>
      			<div class="form-group">
	      			<select class="form-control bank_name" name="bank_name">
		      			@foreach($payment_banks as $payment_bank)
		      				<option value="{{ $payment_bank->id }}">
		      					{{ $payment_bank->bank_name }}
		      				</option>
		      			@endforeach
	      			</select>
      			</div>
      			<div class="">
      				<input type="text" class="form-control" name="cc_reference_number" value="" placeholder="{{ isset($data['backendlang']['backendlang']['Reference_Number']) ? $data['backendlang']['backendlang']['Reference_Number'] :'' }}">
      			</div>
      		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">{{ isset($data['backendlang']['backendlang']['Close']) ? $data['backendlang']['backendlang']['Close'] :'' }}</button>
        <button type="button" class="btn btn-primary submit-pay">{{ isset($data['backendlang']['backendlang']['Pay']) ? $data['backendlang']['backendlang']['Pay'] :'' }}</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade discount-modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog">
    <div class="modal-content">
    	<div class="modal-header">
    		<h4>{{ isset($data['backendlang']['backendlang']['Key_In_Discount_Amount']) ? $data['backendlang']['backendlang']['Key_In_Discount_Amount'] :'' }}</h4>
    	</div>
    	<div class="modal-body">
	    	<div class="row">
	    		<div class="col-md-6">
			    	<select class="form-control discount_type" name="discount_type">
			    		<option value="1">{{ isset($data['backendlang']['backendlang']['Percentage']) ? $data['backendlang']['backendlang']['Percentage'] :'' }}</option>
			    		<option value="2">{{ isset($data['backendlang']['backendlang']['Amount']) ? $data['backendlang']['backendlang']['Amount'] :'' }}</option>
			    	</select>	    			
	    		</div>
	    		<div class="col-md-6">
					<input type="text" class="form-control discount_input" name="discount_input" placeholder="{{ isset($data['backendlang']['backendlang']['discount_amount']) ? $data['backendlang']['backendlang']['discount_amount'] :'' }}" onkeypress="return isNumberKey(event)">
	    		</div>
	    	</div>
    	</div>

    	<div class="modal-footer" align="right">
    		<button class="btn" data-bs-dismiss="modal">{{ isset($data['backendlang']['backendlang']['Close']) ? $data['backendlang']['backendlang']['Close'] :'' }}</button>
    		<button class="btn submit-discount">{{ isset($data['backendlang']['backendlang']['Discount']) ? $data['backendlang']['backendlang']['Discount'] :'' }}</button>
    	</div>
    </div>
  </div>
</div>

<div class="modal fade" id="edit-transaction" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  	<div class="modal-dialog modal-lg">
    	<div class="modal-content">
      		<!-- <div class="modal-header">
        		<h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
      		</div> -->
      		<form method="POST" id="transaction-form">
      		@csrf
	      	<div class="modal-body transaction-listing" style="max-height: 500px; overflow: auto;">
	        	
	      	</div>
      		</form>
	      	<div class="modal-footer">
	        	<button type="button" class="btn btn-secondary" data-dismiss="modal">{{ isset($data['backendlang']['backendlang']['Cancel']) ? $data['backendlang']['backendlang']['Cancel'] :'' }}</button>
	        	<button type="button" class="btn btn-primary save-change-transaction-btn">{{ isset($data['backendlang']['backendlang']['Save_Changes']) ? $data['backendlang']['backendlang']['Save_Changes'] :'' }}</button>
	      	</div>
    	</div>
  	</div>
</div>

<div class="modal fade" id="combine-receipt" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  	<div class="modal-dialog modal-lg" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        		<h4 class="modal-title" id="myModalLabel">{{ isset($data['backendlang']['backendlang']['Combine_Receipt']) ? $data['backendlang']['backendlang']['Combine_Receipt'] :'' }}</h4>
      		</div>
      		<div class="modal-body">
        		<div class="order-list-area" style="max-height: 450px; overflow-y: auto; padding: 10px;">

        		</div>
      		</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-outline-danger" data-dismiss="modal">{{ isset($data['backendlang']['backendlang']['Close']) ? $data['backendlang']['backendlang']['Close'] :'' }}</button>
        		<button type="button" class="btn btn-primary comfirm-combine-receipt-btn">{{ isset($data['backendlang']['backendlang']['Combine_And_Pay']) ? $data['backendlang']['backendlang']['Combine_And_Pay'] :'' }}</button>
      		</div>
    	</div>
  	</div>
</div>

<div class="modal fade" id="print-combine-receipt" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  	<div class="modal-dialog modal-lg" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        		<h4 class="modal-title" id="myModalLabel">{{ isset($data['backendlang']['backendlang']['Print_Combined_Receipt']) ? $data['backendlang']['backendlang']['Print_Combined_Receipt'] :'' }}</h4>
      		</div>
      		<div class="modal-body">
      			<input type="hidden" class="combine_receipt_transaction_no">
        		<div style="max-height: 450px; overflow-y: auto; padding: 10px;">
        			{{ isset($data['backendlang']['backendlang']['Print_Combined_Receipt_Confirm']) ? $data['backendlang']['backendlang']['Print_Combined_Receipt_Confirm'] :'' }}
        		</div>
      		</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-outline-danger" data-dismiss="modal">{{ isset($data['backendlang']['backendlang']['Cancel']) ? $data['backendlang']['backendlang']['Cancel'] :'' }}</button>
        		<button type="button" class="btn btn-primary comfirm-print-receipt-btn">{{ isset($data['backendlang']['backendlang']['OK']) ? $data['backendlang']['backendlang']['OK'] :'' }}</button>
      		</div>
    	</div>
  	</div>
</div>


<div class="modal fade" id="add-discount-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  	<div class="modal-dialog" role="document">
    	<div class="modal-content">
      		<div class="modal-body">
        		<div class="row">
      				<div class="col-md-6">
      					<select class="form-control payment_discount_type" name="combine_payment_discount_type">
      						<option value="Percentage">{{ isset($data['backendlang']['backendlang']['Percentage']) ? $data['backendlang']['backendlang']['Percentage'] :'' }}</option>
      						<option value="Amount">{{ isset($data['backendlang']['backendlang']['Amount']) ? $data['backendlang']['backendlang']['Amount'] :'' }}</option>
      					</select>
      				</div>
      				<div class="col-md-6">
      					<input type="text" class="form-control" name="combine_payment_discount_amount" placeholder="{{ isset($data['backendlang']['backendlang']['discount_amount']) ? $data['backendlang']['backendlang']['discount_amount'] :'' }}">
      				</div>
      			</div>
      		</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-outline-danger" data-dismiss="modal">{{ isset($data['backendlang']['backendlang']['Close']) ? $data['backendlang']['backendlang']['Close'] :'' }}</button>
        		<button type="button" class="btn btn-primary add-discount-btn">{{ isset($data['backendlang']['backendlang']['Add_Discount']) ? $data['backendlang']['backendlang']['Add_Discount'] :'' }}</button>
      		</div>
    	</div>
  	</div>
</div>

<div class="modal fade text-left"
     id="checkout-add-discount-modal"
     tabindex="-1"
     role="dialog"
     aria-labelledby="myModalLabel160"
     aria-hidden="true">
  	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    	<div class="modal-content">
      		<div class="modal-body">
        		<div class="row">
      				<div class="col-md-6">
      					<select class="form-control payment_discount_type" name="combine_payment_discount_type">
      						<option value="Percentage">{{ isset($data['backendlang']['backendlang']['Percentage']) ? $data['backendlang']['backendlang']['Percentage'] :'' }}</option>
      						<option value="Amount">{{ isset($data['backendlang']['backendlang']['Amount']) ? $data['backendlang']['backendlang']['Amount'] :'' }}</option>
      					</select>
      				</div>
      				<div class="col-md-6">
      					<input type="text" class="form-control" name="combine_payment_discount_amount" placeholder="{{ isset($data['backendlang']['backendlang']['discount_amount']) ? $data['backendlang']['backendlang']['discount_amount'] :'' }}">
      				</div>
      			</div>
      		</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-outline-danger" data-dismiss="modal">{{ isset($data['backendlang']['backendlang']['Close']) ? $data['backendlang']['backendlang']['Close'] :'' }}</button>
        		<button type="button" class="btn btn-primary checkout-add-discount-btn">{{ isset($data['backendlang']['backendlang']['Add_Discount']) ? $data['backendlang']['backendlang']['Add_Discount'] :'' }}</button>
      		</div>
    	</div>
  	</div>
</div>

<div class="modal fade" id="transfer-table" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  	<div class="modal-dialog modal-lg" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        		<h4 class="modal-title" id="myModalLabel">{{ isset($data['backendlang']['backendlang']['Transfer_Table']) ? $data['backendlang']['backendlang']['Transfer_Table'] :'' }}</h4>
      		</div>
      		<div class="modal-body">
        		<div class="table-list-area">

        		</div>
      		</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-outline-danger" data-dismiss="modal">{{ isset($data['backendlang']['backendlang']['Close']) ? $data['backendlang']['backendlang']['Close'] :'' }}</button>
        		<button type="button" class="btn btn-primary comfirm-transfer-table-btn">{{ isset($data['backendlang']['backendlang']['Transfer']) ? $data['backendlang']['backendlang']['Transfer'] :'' }}</button>
      		</div>
    	</div>
  	</div>
</div>

<div class="modal fade" id="view-delivery" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  	<div class="modal-dialog modal-lg" role="document">
    	<div class="modal-content">
    		<div class="modal-header">
    			{{ isset($data['backendlang']['backendlang']['Delivery_List']) ? $data['backendlang']['backendlang']['Delivery_List'] :'' }}
    		</div>
      		<div class="modal-body"  style="height: 500px; overflow: auto;">
        		<div class="delivery-list-area">

        		</div>
      		</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-outline-danger" data-dismiss="modal">{{ isset($data['backendlang']['backendlang']['Close']) ? $data['backendlang']['backendlang']['Close'] :'' }}</button>
      		</div>
    	</div>
  	</div>
</div>

<div class="modal fade" id="refund-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  	<div class="modal-dialog modal-xl" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        		<h4 class="modal-title" id="myModalLabel">{{ isset($data['backendlang']['backendlang']['Order_History']) ? $data['backendlang']['backendlang']['Order_History'] :'' }}</h4>
      		</div>
      		<div class="modal-body" style="max-height: 420px; overflow-y: auto;">
        		<div class="transaction-list-area">

        		</div>
      		</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-outline-danger" data-dismiss="modal">{{ isset($data['backendlang']['backendlang']['Close']) ? $data['backendlang']['backendlang']['Close'] :'' }}</button>
      		</div>
    	</div>
  	</div>
</div>

<div class="modal fade" id="add-qr-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  	<div class="modal-dialog modal-xl" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        		<h4 class="modal-title" id="myModalLabel">{{ isset($data['backendlang']['backendlang']['QR_Type']) ? $data['backendlang']['backendlang']['QR_Type'] :'' }}</h4>
      		</div>
      		<div class="modal-body" style="max-height: 420px; overflow-y: auto;">
				<form method="POST" action="{{ route('save_qr_type') }}" id="setting-message-form" enctype="multipart/form-data">
				@csrf
				<div class="add-message-content">
					@if(!$qr_pay_lists->isEmpty())
					@foreach ($qr_pay_lists as $qr_pay_list)
					<div class="row messsage-del">
						<input type="hidden" name="id[]" class="id" value="{{ $qr_pay_list->id }}">
						<div class="row mb-3 col-sm-12">
							<div class="col-6">
								<input type="text" name="title[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['Title']) ? $data['backendlang']['backendlang']['Title'] :'' }}" value="{{ $qr_pay_list->title }}">
							</div>
							<div class="col-5">
								<input type="file" name="image[]" class="form-control" accept="image/*">
								@if(!empty($qr_pay_list->image))
								<img src="{{ asset($qr_pay_list->image) }}" style="width: 70px;">
								@endif
							</div>
							<div class="col-1" align="center">
								<a href="#" class="important-text del">
									<i class="bi bi-trash" style="font-size: 20px;"></i>
								</a>
							</div>
						</div>
					</div>
					@endforeach
					@else
					<div class="row">
						<div class="row mb-3 col-sm-12">
							<div class="col-6">
								<input type="text" name="title[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['Title']) ? $data['backendlang']['backendlang']['Title'] :'' }}">
							</div>
							<div class="col-5">
								<input type="file" name="image[]" class="form-control" accept="image/*">
							</div>
							<div class="col-1" align="center">
								<a href="#" class="important-text del">
									<i class="bi bi-trash" style="font-size: 20px;"></i>
								</a>
							</div>
						</div>
					</div>
					@endif
				</div>
		
				<div class="form-group">
					<div class="row">
						<div class="col-md-12" align="center">
							<a href="#" class="add-messages-btn btn btn-primary btn-sm" id="add-messages">
								<i class="bi bi-plus"></i>
							</a>
						</div>
					</div>
				</div>
			</form>
      		</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-outline-danger" data-dismiss="modal">{{ isset($data['backendlang']['backendlang']['Close']) ? $data['backendlang']['backendlang']['Close'] :'' }}</button>
				<button type="button" class="btn btn-primary submit-qr">{{ isset($data['backendlang']['backendlang']['Submit']) ? $data['backendlang']['backendlang']['Submit'] :'' }}</button>
      		</div>
    	</div>
  	</div>
</div>
@endsection

@section('js')
<script type="text/javascript">

		$('.submit-qr').on('click', function() {
			$('#setting-message-form').submit();
		});

	    var add_message = '<div class="row del-new-message">\
						<div class="row mb-3 col-sm-12">\
							<div class="col-6">\
								<input type="text" name="title[]" class="form-control" placeholder="Title">\
							</div>\
							<div class="col-5">\
								<input type="file" name="image[]" class="form-control" accept="image/*">\
							</div>\
							<div class="col-1" align="center">\
								<a href="#" class="important-text del">\
									<i class="bi bi-trash" style="font-size: 20px;"></i>\
								</a>\
							</div>\
						</div>\
					</div>';

    $('#add-messages').click(function(e) {
        e.preventDefault();
        $('.add-message-content').append(add_message);
    });

	$('.add-message-content').on('click', '.del', function(e) {
        e.preventDefault();

        var ele = $(this);
        var id = ele.closest('.messsage-del').find('.id').val();
		
        if (id) {
            if (confirm('{{ isset($data["backendlang"]["backendlang"]["Delete_this_QR_Type?"]) ? $data["backendlang"]["backendlang"]["Delete_this_QR_Type?"] : "Delete this QR Type?" }}') == true) {
                var fd = new FormData();
                fd.append('id', id);

                $.ajax({
                    url: "{{ route('DeleteQRType') }}",
                    type: 'post',
                    data: fd,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $('.loading-gif').hide();
                        ele.closest('.messsage-del').remove();
						location.reload();
                    },
                });


            }
        } else {
            ele.closest('.del-new-message').remove();
        }
    });


	$('#checkout-add-discount-modal').on('shown.bs.modal', function () {
		$('#checkout-add-discount-modal').find('input[name="combine_payment_discount_amount"]').val("");
		$('#checkout-add-discount-modal').modal('show');
	    $('input[name="combine_payment_discount_amount"]').focus();
	});

	$('.select2').select2();

	$('.item-list-parent, .search_result').on('click', '.items_option', function(e){
		e.preventDefault();
		$('.loading-gif').show();
		var ele = $(this);
		var all_users = $('.all_users').val();

		var fd = new FormData();
    		fd.append('id', ele.data('filter'));
    		fd.append('selected_buyer', all_users);
    	
    	$.ajax({
	       url: '{{ route("ChooseCategory") }}',
	       type: 'post',
	       data: fd,
	       contentType: false,
	       processData: false,
	       success: function(response){
	       		$('.loading-gif').hide();
	       		$('.item-list-child').html(response[0]);
	       		$('.search_result').html(response[1]);
	       		$('.ps-shoe__variant.normal').owlCarousel({
		            margin: 20,
		            autoplay: false,
		            loop: false,
		            nav: true,
		            dots: false,
		            mouseDrag: true,
		            touchDrag: true,
		            navSpeed: 1000,
		            items: 4,
		            navText: ["<i class='bi bi-arrow-left'></i>", "<i class='bi bi-arrow-right'></i>"],
		            responsive: {
		                0: {
		                    items: 3
		                },
		                480: {
		                    items: 3
		                },
		                768: {
		                    items: 3
		                },
		                992: {
		                    items: 4
		                },
		                1200: {
		                    items: 4
		                }
		            }
		        });
	       		
	       },
	    });
	});

	$('.item-list-parent').on('click', '.sub_items_option', function(e){
		e.preventDefault();
		$('.loading-gif').show();
		var ele = $(this);
		var fd = new FormData();
    		fd.append('scid', ele.data('filter'));
    		fd.append('pid', ele.data('id'));

    	$.ajax({
	       url: '{{ route("ChooseSubCategory") }}',
	       type: 'post',
	       data: fd,
	       contentType: false,
	       processData: false,
	       success: function(response){
	       		$('.loading-gif').hide();
	       		$('.item-list-child').html(response[0]);
	       		$('.search_result').html(response[1]);
	       		$('.ps-shoe__variant.normal').owlCarousel({
		            margin: 20,
		            autoplay: false,
		            loop: false,
		            nav: true,
		            dots: false,
		            mouseDrag: true,
		            touchDrag: true,
		            navSpeed: 1000,
		            items: 4,
		            navText: ["<i class='ps-icon-back'></i>", "<i class='ps-icon-next'></i>"],
		            responsive: {
		                0: {
		                    items: 3
		                },
		                480: {
		                    items: 3
		                },
		                768: {
		                    items: 3
		                },
		                992: {
		                    items: 4
		                },
		                1200: {
		                    items: 4
		                }
		            }
		        });
	       		
	       },
	    });
	});

	$('.item-list-parent').on('click', '.product_items_option', function(e){
		$('.loading-gif').show();
		var ele = $(this);
		var all_users = $('.all_users').val();

		var fd = new FormData();
    		fd.append('pid', ele.data('id'));
    		fd.append('selected_buyer', all_users);

        $.ajax({
            url: '{{ route("GetProductVariation") }}',
            type: 'post',
            data: fd,
            contentType: false,
            processData: false,
            success: function(response){
                $('.loading-gif').hide();
				$('.bottom-pop-up-content').html(response);
                $('.bottom-pop-up').slideDown( "fast", function() {
				});  
            },
        });

        $('.bottom-pop-up').on('click', '.close-bottom-pop-up', function(e){
			$('.bottom-pop-up').slideUp( "fast", function() {
			});
		});
	});

	$('.bottom-pop-up').on('click', '.deduct-qty-button', function(e){
		e.preventDefault();
		
		var ele = $(this);
		var quantity = ele.parent().find('input[name="quantity"]').val();
		if(quantity != 1){
			quantity = Number(quantity) - 1;
			ele.parent().find('input[name="quantity"]').val(quantity);
		}
	});

	$('.bottom-pop-up').on('click', '.add-qty-button', function(e){

		e.preventDefault();
		
		var ele = $(this);
		var quantity = ele.parent().find('input[name="quantity"]').val();
		var balance = ele.closest('ul').find('input[name="balance_quantity"]').val();
		quantity = Number(quantity) + 1;
		if(quantity > balance){
			alert('{{ isset($data["backendlang"]["backendlang"]["available_for_this_item_is"]) ? $data["backendlang"]["backendlang"]["available_for_this_item_is"] : "The maximum quantity available for this item is" }}'+balance);
			$('.loading-gif').hide();
			return false;
		}else{
			ele.parent().find('input[name="quantity"]').val(quantity);			
		}
		
	});

	$('.transaction-listing').on('click', '.deduct-qty-button', function(e){
		e.preventDefault();
		
		var ele = $(this);
		var quantity = ele.parent().find('input[name="quantity[]"]').val();
		if(quantity != 1){
			quantity = Number(quantity) - 1;
			ele.parent().find('input[name="quantity[]"]').val(quantity);
		}
	});

	$('.transaction-listing').on('click', '.add-qty-button', function(e){

		e.preventDefault();
		
		var ele = $(this);
		var quantity = ele.parent().find('input[name="quantity[]"]').val();
		var balance = ele.parent().find('input[name="balance_quantity[]"]').val();
		quantity = Number(quantity) + 1;
		if(quantity > balance){
			alert('{{ isset($data["backendlang"]["backendlang"]["available_for_this_item_is"]) ? $data["backendlang"]["backendlang"]["available_for_this_item_is"] : "The maximum quantity available for this item is" }}'+balance);
			$('.loading-gif').hide();
			return false;
		}else{
			ele.parent().find('input[name="quantity[]"]').val(quantity);			
		}
	});

	$('.transaction-listing').on('change', 'input[name="quantity[]"]', function(){
		var ele = $(this);
		$('.loading-gif').show();

		var quantity = $(this).val();
		var balance = ele.parent().find('input[name="balance_quantity[]"]').val();

		if(parseInt(quantity) > parseInt(balance)){
			ele.val(balance);
			alert('{{ isset($data["backendlang"]["backendlang"]["available_for_this_item_is"]) ? $data["backendlang"]["backendlang"]["available_for_this_item_is"] : "The maximum quantity available for this item is" }}'+balance);
			$('.loading-gif').hide();
			return false;
		}

		$('.loading-gif').hide();
		
	});

	$('.bottom-pop-up').on('click', '.add-to-cart-button', function(e){
	  	e.preventDefault();
	  	$('.loading-gif').show();
	  	var member = $('.all_users').val();
	  	var ele = $(this);
	  	
	  	var isGuest = "{{ Session::get('dine_in_guest') }}";
	  	
	    var option_error;
	    var option_message;


	    $('.v_v').each(function () {
	      	var checkType = $(this).find('.variation_type').val();
	      	var checkoption = $(this).find('.variation_option.active').data('id');
	      	var title = $(this).find('h4').html();
	      
	      	if(checkType == '1' && !checkoption){
	          	option_error = 1;
	          	alert('Please pick your '+title);
	      	}
	    });

	    $('.v_v_2').each(function () {
	      	var checkType = $(this).find('.variation_type').val();
	      	var checkoption = $(this).find('.second_variation_option.active').data('id');
	      	var title = $(this).find('h4').html();
	      
	      	if(checkType == '1' && !checkoption){
	          	option_error = 1;
	          	alert('{{ isset($data["backendlang"]["backendlang"]["Please_pick_your"]) ? $data["backendlang"]["backendlang"]["Please_pick_your"] : "Please pick your" }} '+title);
	      	}
	    });

	    if(option_error == 1){  
	        $('.loading-gif').hide();
	        return false;
	    }

	  	var arrayA = [];
	    $('.variation_option.active').each(function () {
	        arrayA.push($(this).data('id'));
	    });

	    // console.log(arrayA);

	    // return false;

	  	if(isGuest){
	  		auth_check = "{{ Session::get('dine_in_guest') }}";
	  	}else{
	  		auth_check = "";
	  	}

	  	var option = $('.variation_option.active').data('id');
  		var second_option = $('.second_variation_option.active').data('id');
	  	
  	
	  	var fd = new FormData();
	  	fd.append('pid', $(this).data('id'));
	  	fd.append('sub_category_id', option);
	  	fd.append('second_sub_category_id', second_option);
	  	fd.append('quantity', ele.closest('.bottom-pop-up').find('input[name="quantity"]').val());
	  	fd.append('cashier', '1');
	  	fd.append('remark', ele.closest('.bottom-pop-up').find('#remark').val());
	  	fd.append('member', member);

	  	$.ajax({
	        url: '{{ route("ChooseItem") }}',
	        type: 'post',
	        data: fd,
	        contentType: false,
	        processData: false,
	        success: function(response){
	        	
	        	$('.loading-gif').hide();

	        	if(response == 'quantity error'){
	        		toastr.error('{{ isset($data["backendlang"]["backendlang"]["please_add_quantity_at_least_1"]) ? $data["backendlang"]["backendlang"]["please_add_quantity_at_least_1"] : "Please Add Quantity At least 1" }}');
	        		return false;
	        	}

	        	if(response == 'quantity exceed error'){
	        		toastr.error('{{ isset($data["backendlang"]["backendlang"]["product_balance_quantity_not_enough"]) ? $data["backendlang"]["backendlang"]["product_balance_quantity_not_enough"] : "Product Balance Quantity Not Enough" }}');
	        		return false;
	        	}

	       		$('.selected-items').html(response);
	       		ele.closest('.bottom-pop-up').find('.close-bottom-pop-up').click();
	       		calc();
	       		$('.no-item').remove();
	        },
	    });
	  	
	});

	$('.bottom-pop-up').on('click', '.variation_option', function(e){
		e.preventDefault();

		$('.loading-gif').show();
		var ele = $(this);
			
		var actual_price = $('.actual_price').val();
		var variation_type = ele.closest('ul').find('.variation_type').val();
		var second_variation = ele.data('second-variation');
		var member = $('.all_users').val();

		if(variation_type == 1){
			ele.closest('ul').find('.variation_option').removeClass('active');
			ele.addClass('active');
		}else{
			if(ele.hasClass('active')){
		  		ele.removeClass('active');
			}else{
		  		ele.addClass('active');
			}
		}
			
		var arrayA = [];
		$('.variation_option.active').each(function () {
		  	arrayA.push($(this).data('id'));
		});

		// console.log(second_variation)

		if(second_variation != 1){
			var fd = new FormData();
			  	fd.append('vid', $('.variation_option.active').data('id'));
			  	fd.append('member', member);

				$.ajax({
			        url: '{{ route("getVariation") }}',
			        type: 'post',
			        data: fd,
			        contentType: false,
			        processData: false,
			        success: function(response){
			        	$('.loading-gif').hide();

			    		// var totalPrice = parseFloat(parseFloat(actual_price) + parseFloat(response[1])).toFixed(2);
			        	$('.pricing').html(response[0]);
			        }
			    });
		}else{
			// console.log($('.variation_option.active').data('id'));
			var fd = new FormData();
			  	fd.append('vid', $('.variation_option.active').data('id'));

				$.ajax({
			        url: '{{ route("getBackendSecondVariationList") }}',
			        type: 'post',
			        data: fd,
			        contentType: false,
			        processData: false,
			        success: function(response){
			        	// $('.loading-gif').hide();

			    		// var totalPrice = parseFloat(parseFloat(actual_price) + parseFloat(response[1])).toFixed(2);
			        	// $('.pricing').html(totalPrice);
			        	ele.closest('.variation_area').find('.second-variation-list').html(response);
			    		console.log(response);
			        }
			    });

		}
	});

	$('.bottom-pop-up').on('click', '.second_variation_option', function(e){
		e.preventDefault();

		var ele = $(this);

		var variation_type = ele.closest('ul').find('.variation_type').val();
		var member = $('.all_users').val();

		if(variation_type == 1){
			ele.closest('ul').find('.second_variation_option').removeClass('active');
			ele.addClass('active');
		}else{
			if(ele.hasClass('active')){
		  		ele.removeClass('active');
			}else{
		  		ele.addClass('active');
			}
		}

		var fd = new FormData();
		  	fd.append('vid', ele.data('id'));
		  	fd.append('member', member);

		$.ajax({
	        url: '{{ route("getSecondVariation") }}',
	        type: 'post',
	        data: fd,
	        contentType: false,
	        processData: false,
	        success: function(response){
	        	$('.loading-gif').hide();
	        	$('.pricing').html(response[0]);
	        }
	    });
	});

	$('.selected-items').on('click', '.add-qty-button', function(e){
		e.preventDefault();
		$('.loading-gif').show();
		var ele = $(this);

		var cart_id = ele.closest('.got-item').find('.cid').val();

		var quantity = ele.parent().find('input[name="quantity"]').val();
		var balance = ele.closest('.got-item').find('input[name="balance_quantity"]').val();
		
			quantity = Number(quantity) + 1;

		if(quantity > balance){
			alert('{{ isset($data["backendlang"]["backendlang"]["available_for_this_item_is"]) ? $data["backendlang"]["backendlang"]["available_for_this_item_is"] : "The maximum quantity available for this item is" }}'+balance);
			$('.loading-gif').hide();
			return false;
		}else{
			ele.parent().find('input[name="quantity"]').val(quantity);
			updateQty(quantity, cart_id, ele);
		}
	});

	$('.selected-items').on('click', '.deduct-qty-button', function(e){
		e.preventDefault();
		$('.loading-gif').show();
		var ele = $(this);
		var quantity = ele.parent().find('input[name="quantity"]').val();
		var cart_id = ele.closest('.got-item').find('.cid').val();

		quantity = Number(quantity) - 1;
		
		if(quantity > 0){
			ele.parent().find('input[name="quantity"]').val(quantity);
			updateQty(quantity, cart_id, ele);
		}else{
			deleteCartItem(cart_id, ele);
		}
	});

	$('.selected-items').on('change', 'input[name="quantity"]', function(){
		var ele = $(this);
		$('.loading-gif').show();

		var quantity = $(this).val();
		var cart_id = ele.closest('.got-item').find('.cid').val();
		var balance = ele.closest('.got-item').find('input[name="balance_quantity"]').val();
		if(quantity < 1){
			deleteCartItem(cart_id, ele);
		}

		if(parseInt(quantity) > parseInt(balance)){
			ele.val(balance);
			alert('{{ isset($data["backendlang"]["backendlang"]["available_for_this_item_is"]) ? $data["backendlang"]["backendlang"]["available_for_this_item_is"] : "The maximum quantity available for this item is" }}'+balance);
			return false;
		}else{
			updateQty(quantity, cart_id, ele);
		}
		
	});

	function updateQty(qty, cart_id, ele){
		var member = $('.all_users').val();
		var fd = new FormData();
			fd.append('cart_id', cart_id);
			fd.append('quantity', qty);
			fd.append('member', member);

		$.ajax({
	       	url: '{{ route("updateQuantity") }}',
	       	type: 'post',
	       	data: fd,
	       	contentType: false,
	       	processData: false,
	       	success: function(response){
	       		ele.closest('.got-item').find('.row-total-price').html(parseFloat(response).toFixed(2));
	       		calc();
	       	},
	    });
	}

	function deleteCartItem(cart_id, ele){

		var fd = new FormData();
			fd.append('cart_id', cart_id);

		if(confirm("{{ isset($data['backendlang']['backendlang']['Item(s) will be removed from Cart']) ? $data['backendlang']['backendlang']['Item(s) will be removed from Cart'] : 'Item(s) will be removed from Cart' }}") == true){
			$.ajax({
		       url: '{{ route("deleteCart") }}',
		       type: 'post',
		       data: fd,
		       contentType: false,
		       processData: false,
		       success: function(response){

		       		ele.closest('.got-item').remove();
		       		if($('.selected-items').find('.got-item').length == 0){
		       			$('.remove-cart-discount').click();
		       		}
		       		calc();
		       },
		    });
		}else{
			$('.loading-gif').hide();
			return false;
		}
	}

	$('.remove-cart-discount').click(function(e){
		e.preventDefault();

		var ele = $(this);
		$('.hidden_discount_type').val("");
		$('.hidden_discount_amount').val(0);
		$('.discountTotal').html('0.00');
		ele.hide();
		calc();
	});

	function calc(){
		var members = $('.all_users').val();

		var fd = new FormData();
			fd.append('members', members);

		$.ajax({
	       	url: '{{ route("CountCashierCart") }}',
	       	type: 'post',
		    data: fd,
	       	contentType: false,
	       	processData: false,
	       	success: function(response){
	       		
	       		var discount_type = $('.hidden_discount_type').val();
				var discount_amount = $('.hidden_discount_amount').val();
				var totalDiscount = 0;
				var d_dis;

				if(discount_amount > 0){
					if(discount_type == 1){
						totalDiscount = response[0] * discount_amount / 100;
						d_dis = '('+discount_amount+"%)";
					}else{
						totalDiscount = discount_amount;
						d_dis = '';
					}
				}

				if(totalDiscount > 0){
					$('.discountTotal').html(d_dis+' '+parseFloat(totalDiscount).toFixed(2));
					$('.remove-cart-discount').show();
				}

	       		$('.subTotal').html(parseFloat(parseFloat(response[0])).toFixed(2) );
	       		$('.grandTotal').html(parseFloat(parseFloat(response[0]) - parseFloat(totalDiscount)).toFixed(2) );
	       		$('.totalCart').html(response[1]);
	       		$('.loading-gif').hide();
	       	},
	    });
	}

	$('.item-list-parent').on('mouseover', '.product_name span', function() {

	    if( this.offsetWidth > this.parentNode.offsetWidth ) {
	    	var total = this.offsetWidth - 20;
	        $(this).animate({'left': '-100px'}, 100, function(){});
	    }
	} ).on('mouseout', '.product_name span', function() {
	    $(this).stop();
	    this.style.left = '0px';
	} );

	$('.order-type').click( function(e){
		e.preventDefault();
		var ele = $(this);


		if(ele.hasClass('active')){
			ele.removeClass('active');
		}else{
			$('.order-type').removeClass('active');
			ele.addClass('active');
		}

		if(ele.data('filter') == '1' && ele.hasClass('active')){

			// $('.open-table-list-btn').click();
			$('#select_table').modal('toggle');
		}
	});

	$('.sendToKitchen').click( function(e){
		e.preventDefault();

		var ele = $(this);

		var checkItem = $('.got-item').length;
		if(checkItem < 1){
			toastr.error('{{ isset($data["backendlang"]["backendlang"]["Please_pick_at_least_one_item_first."]) ? $data["backendlang"]["backendlang"]["Please_pick_at_least_one_item_first."] : "Please pick at least one item first." }}');
			return false;
		}

		var order_type = $('.order-type.active').data('filter');
		var dine_table = $('.dine-in-table').val();
		var dine_head = $('.dine-in-table-headcount').val();

		if(!order_type){
			$('.SelectOrderType').click();
		}else{
			if(order_type == '1' && !dine_table && !dine_head){
				$('#select_table').modal('toggle');
			}else{
				$('.ConfirmSendKitchen').click();
			}
		}
	});

	$('.discount-btn').click(function(e){
		e.preventDefault();

		var ele = $(this);

		var checkItem = $('.got-item').length;
		if(checkItem < 1){
			toastr.error('{{ isset($data["backendlang"]["backendlang"]["Please_pick_at_least_one_item_first."]) ? $data["backendlang"]["backendlang"]["Please_pick_at_least_one_item_first."] : "Please pick at least one item first." }}');
			return false;
		}
	});

	$('.submit-discount').click(function(e){
		e.preventDefault();

		var subTotal = parseFloat($('.subTotal').html());
		var discountType = $('.discount_type').val();
		var discountAmount = $('.discount_input').val();
		
		if(!discountAmount){
			toastr.error('{{ isset($data["backendlang"]["backendlang"]["Please key in discount amount"]) ? $data["backendlang"]["backendlang"]["Please key in discount amount"] : "Please key in discount amount" }}');
			return false;
		}

		if(discountType == 2){
			if(discountAmount > subTotal){
				toastr.error('{{ isset($data["backendlang"]["backendlang"]["Discount amount cannot exceed subtotal amount."]) ? $data["backendlang"]["backendlang"]["Discount amount cannot exceed subtotal amount."] : "Discount amount cannot exceed subtotal amount." }}');
				return false;
			}
		}

		if(discountType == 1){
			if(discountAmount > 100){
				toastr.error('{{ isset($data["backendlang"]["backendlang"]["Discount percentage cannot exceed 100."]) ? $data["backendlang"]["backendlang"]["Discount percentage cannot exceed 100."] : "Discount percentage cannot exceed 100." }}');
				return false;
			}
		}

		$('.discount-modal').modal('toggle');

		$('.hidden_discount_type').val(discountType);
		$('.hidden_discount_amount').val(discountAmount);

		calc();
	});

	$('.select-order-type').click( function(e){
		e.preventDefault();

		var ele = $(this);
		var type = ele.data('filter');

		$('.order-type').filter(function(){return $(this).data('filter')==type}).trigger('click');
		$('.close-so-btn').click();
	});

	$('.submit-to-kitchen').click( function(e){
		e.preventDefault();
		$('.loading-gif').show();

		var member_phone = $('.member').val();

		var toggle_point_enable = $('.toggle-point').prop('checked');

		var used_point = 0;
		if(toggle_point_enable == true){
			used_point = 1;
		}

		var fd = new FormData();
			fd.append('order_type', $('.order-type.active').data('filter'));
			fd.append('table_id', $('.dine-in-table').val());
			fd.append('order_no', $('.order-no').val());
			fd.append('hidden_discount_type', $('.hidden_discount_type').val());
			fd.append('hidden_discount_amount', $('.hidden_discount_amount').val());
			fd.append('remark', $('.remark').val());
			fd.append('member_phone', member_phone);
			fd.append('used_point', used_point);

		$.ajax({
	       	url: '{{ route("cashier_checkout") }}',
	       	type: 'post',
	       	data: fd,
	       	contentType: false,
	       	processData: false,
	       	success: function(response){
	       		$('.loading-gif').hide();
	       		if(response != 'ok'){
	       			console.log(response);
	       			toastr.error(response);
	       			return false;
	       		}
	       		$('.order-type').filter(function(){return $(this).data('filter')==1}).html('Dine in');
	       		$('.order-type').removeClass('active');
	       		$('.dine-in-table').val('');
	       		$('.dine-in-table-headcount').val('');
	       		$('.order-no').val('');
	       		$('#ConfirmSendKitchen').modal('toggle');
	       		$('.discountTotal').html('0.00');
	       		$('.hidden_discount_type').val('');
	       		$('.hidden_discount_amount').val('');
	       		$('.member').val('');
	       		$('.member_point').html('');
	       		$('.toggle-point-area').hide();
	       		$('.toggle-point').prop('checked', false);

				toastr.success('{{ isset($data["backendlang"]["backendlang"]["Order_placed_successful"]) ? $data["backendlang"]["backendlang"]["Order_placed_successful"] : "Order placed successful" }}');
	       		$('.selected-items').html('<div class="row">\
												<div class="col-12 no-item" align="center">\
													{{ isset($data["backendlang"]["backendlang"]["Please_add_an_item"]) ? $data["backendlang"]["backendlang"]["Please_add_an_item"] : "Please add an item" }}\
												</div>\
										   </div>');
	       		calc();
	       	},
	    });
	});

	$('#select_table').on('hidden.bs.modal', function () {
	  	$('.unselected-people-box').show();
	  	$('.selected-people-box').hide();
	  	$('.select-table-box').show();
	  	$('.table-history-box').hide();
	})

	$('.select-table').click( function(e){
		e.preventDefault();
		var ele = $(this);
		var id = ele.data('filter');
		var name = ele.find('.table-name').val();
		$('.loading-gif').show();

		$('.unselected-people-box').hide();
		$('.selected-people-box').show();

		$('.selected-table-name').html(name);
		$('.selected_t_n_d').val(id);
		$('.table-history').attr('data-filter', id);

		var fd = new FormData();
			fd.append('id', id);

		$.ajax({
	       	url: '{{ route("checkTableAvailable") }}',
	       	type: 'post',
	       	data: fd,
	       	contentType: false,
	       	processData: false,
	       	success: function(response){
	       		$('.back-to-table-list').show();
	       		if(response == 0){
	       			$('.check-available').html('Available');
	       			$('.head-count-box').show();
	       			$('.select-table-box').show();
	       			$('.table-history').show();
	       			$('.current_order_action').hide();

	       			$('.loading-gif').hide();
	       		}else{
	       			$('.check-available').html('{{ isset($data["backendlang"]["backendlang"]["In_Use"]) ? $data["backendlang"]["backendlang"]["In_Use"] : "In Use" }}');
					$('.current_order').html('{{ isset($data["backendlang"]["backendlang"]["Current_Order"]) ? $data["backendlang"]["backendlang"]["Current_Order"] : "Current Order" }}: <span class="current_order_no">'+response+'</span>');

	       			$('.current_order_action').show();
	       			$('.head-count-box').hide();
	       			$('.table-history').hide();
	       			$('.useTable').hide();
	       			$('.pay-now-order-no').val(response);
	       			$('.pay-now-btn').attr('data-table', id);
	       			$('.print-transaction').attr('data-id', response)


	       			var fd = new FormData();
						fd.append('id', id);
						fd.append('search_date', "");

					$.ajax({
				       	url: '{{ route("GetTableHistory") }}',
				       	type: 'post',
				       	data: fd,
				       	contentType: false,
				       	processData: false,
				       	success: function(response){
				       		$('.loading-gif').hide();
				       		$('.select-table-box').hide();
				       		$('.table-history-box').show();
				       		$('.table-history-box').html(response);
				       		$('.table-history-box .search_date').datepicker("setDate", new Date());

				       	},
				    });
	       		}
	       	},
	    });
	});

	$('.back-to-table-list').click( function(e){
		e.preventDefault();
		var ele = $(this);

		ele.hide();
		$('.current_order_action').hide();
		$('.table-history-box').hide();
		$('.useTable').hide();

		
		$('.select-table-box').show();
		$('.current_order').html('');
		$('.check-available').html('');
		$('.unselected-people-box').show();
		$('.selected-people-box').hide();
		$('.number-of-people').removeClass('active');

	});

	$('.number-of-people').click( function(e){
		e.preventDefault();
		var ele = $(this);

		$('.number-of-people').removeClass('active');
		ele.addClass('active');

		$('.useTable').show();
	});

	$('.useTable').click( function(e){
		e.preventDefault();
		var ele = $(this);
		$('.order-type').filter(function(){return $(this).data('filter')==1}).addClass('active');
		$('.order-type').filter(function(){return $(this).data('filter')==1}).html('Dine in on '+$('.selected-table-name').html());
		$('.dine-in-table').val($('.selected_t_n_d').val());
		$('#select_table').modal('toggle');
		$('.dine-in-table-headcount').val($('.number-of-people.active').data('filter'));
	});

	$('.print-qr').click( function(e){
		e.preventDefault();
		var ele = $(this);
		var selected_table = $('.selected_t_n_d').val();


		var fd = new FormData();
    		fd.append('selected_table', selected_table);

    	$.ajax({
	       url: '{{ route("GenerateQR") }}',
	       type: 'post',
	       data: fd,
	       contentType: false,
	       processData: false,
	       success: function(response){
	       		$('.loading-gif').hide();
	       		var url = "{{ route('print_qr', [':id', ':tid']) }}";
					url = url.replace(':id', response[0]);
					url = url.replace(':tid', response[1]);

				window.open(url);
	       },
	    });

	});

	$('.add-new-item-btn').click( function(e){
		e.preventDefault();
		var ele = $(this);
		$('.order-no').val($('.current_order_no').html());
		$('.order-type').filter(function(){return $(this).data('filter')==1}).addClass('active');
		$('.order-type').filter(function(){return $(this).data('filter')==1}).html('Dine in on '+$('.selected-table-name').html());
		$('.dine-in-table').val($('.selected_t_n_d').val());
		$('#select_table').modal('toggle');
	});

	$('.selected-people-box').on('click', '.table-history', function(e){
		e.preventDefault();
		$('.loading-gif').show();
		var ele = $(this);

		var fd = new FormData();
			fd.append('id', $('.selected_t_n_d').val());

		$.ajax({
	       	url: '{{ route("GetTableHistory") }}',
	       	type: 'post',
	       	data: fd,
	       	contentType: false,
	       	processData: false,
	       	success: function(response){
	       		$('.loading-gif').hide();
	       		$('.select-table-box').hide();
	       		$('.table-history-box').show();
	       		$('.table-history-box').html(response);
	       		$('.table-history-box .search_date').datepicker("setDate", new Date());
	       	},
	    });
	});

	$('.payment-method').click(function(e){
		e.preventDefault();

		var ele = $(this);
		var id = ele.data('filter');

		$('.payment-method').removeClass('active');
		ele.addClass('active');

		if(id == 1){
			$('.cash-payment-box').slideDown( "fast", function() {});
		}else{
			$('.cash-payment-box').slideUp( "fast", function() {});
		}

		if(id == 2){
			$('.qr-pay-list').slideDown( "fast", function() {});
		}else{
			$('.qr-pay-list').slideUp( "fast", function() {});
		}

		if(id == 3){
			$('.credit-card-payment').slideDown( "fast", function() {});
		}else{
			$('.credit-card-payment').slideUp( "fast", function() {});
		}
	});

	$('.submit-pay').click( function(e){
		e.preventDefault();

		var ele = $(this);
		var payment_method = $('.payment-method.active').data('filter');
		var amount = parseFloat($('input[name="amount"]').val());
		var totalAmount = $('#payment_box').find('.totalHiddenAmount').val();
		var qr_type = $('#payment_box').find('.qr-pay-type.selected').data('id');
		var reference_number = $('#payment_box').find('input[name="reference_number"]').val();
		var bank_name = $('#payment_box').find('.bank_name').val();
		var cc_reference_number = $('#payment_box').find('input[name="cc_reference_number"]').val();
		var totalDiscount = $('#payment_box .after-discount').find('.after_discount_amount').val();
		var payment_discount_type = $('#payment_box').find('.payment_discount_type').val();
		var combine_payment_discount_amount = $('#payment_box').find('.combine_payment_discount_amount').val();
		// var member_phone = ele.closest('#payment_box').find('.member').val();
		var member_phone = $('.all_users').val();

		var toggle_point_enable = ele.closest('#payment_box').find('.toggle-point').prop('checked');

		var used_point = 0;
		if(toggle_point_enable == true){
			used_point = 1;
		}

		payment_discount_type = (payment_discount_type) ? payment_discount_type : "";
		combine_payment_discount_amount = (combine_payment_discount_amount > 0) ? combine_payment_discount_amount : 0;
		totalDiscount = (totalDiscount > 0) ? totalDiscount : 0;

		if(!payment_method){
			alert('{{ isset($data["backendlang"]["backendlang"]["Please_select_payment_method_to_continue"]) ? $data["backendlang"]["backendlang"]["Please_select_payment_method_to_continue"] : "Please select payment method to continue" }}');
			return false;
		}

		if(payment_method == 1 && !amount){
			alert('{{ isset($data["backendlang"]["backendlang"]["please_key_in_amount_to_pay"]) ? $data["backendlang"]["backendlang"]["please_key_in_amount_to_pay"] : "Please key in the amount to pay" }}');
			return false;
		}

		if(payment_method == 1 && parseFloat(amount) < parseFloat(totalAmount)){
			toastr.error('{{ isset($data["backendlang"]["backendlang"]["Amount_Less_then_Payment_Amount"]) ? $data["backendlang"]["backendlang"]["Amount_Less_then_Payment_Amount"] : "Amount Less then Payment Amount" }}');
			return false;
		}

		if(payment_method == 2 && !qr_type){
			toastr.error('{{ isset($data["backendlang"]["backendlang"]["Please_Choose_Qr_Pay_Type"]) ? $data["backendlang"]["backendlang"]["Please_Choose_Qr_Pay_Type"] : "Please Choose Qr Pay Type" }}');
			return false;
		}

		if(payment_method == 2 && !reference_number){
			toastr.error('{{ isset($data["backendlang"]["backendlang"]["Please_Insert_Reference_Number"]) ? $data["backendlang"]["backendlang"]["Please_Insert_Reference_Number"] : "Please Insert Reference Number" }}');
			return false;
		}

		if(payment_method == 3 && !bank_name){
			toastr.error('{{ isset($data["backendlang"]["backendlang"]["Please_Select_Bank"]) ? $data["backendlang"]["backendlang"]["Please_Select_Bank"] : "Please Select Bank" }}');
			return false;
		}

		if(payment_method == 3 && !cc_reference_number){
            toastr.error('{{ isset($data["backendlang"]["backendlang"]["Please_Insert_Reference_Number"]) ? $data["backendlang"]["backendlang"]["Please_Insert_Reference_Number"] : "Please Insert Reference Number" }}');
			return false;
		}
		if(confirm('{{ isset($data["backendlang"]["backendlang"]["Confirm_Payment"]) ? $data["backendlang"]["backendlang"]["Confirm_Payment"] : "Confirm Payment" }}')){
			var fd = new FormData();
				fd.append('payment_method', payment_method);
				fd.append('order_type', $('.pay-order-type').val());
				fd.append('table_id', $('.pay-table').val());
				fd.append('order_no', $('.pay-order-no').val());
				fd.append('paid_amount', amount);
				fd.append('grand_total', totalAmount);
				fd.append('totalDiscount', totalDiscount);
				fd.append('payment_discount_type', payment_discount_type);
				fd.append('combine_payment_discount_amount', combine_payment_discount_amount);
				fd.append('reference_number', reference_number);
				fd.append('cc_reference_number', cc_reference_number);
				fd.append('bank_name', bank_name);
				fd.append('qr_type', qr_type);
				fd.append('member_phone', member_phone);
				fd.append('used_point', used_point);

			$.ajax({
		       	url: '{{ route("cashier_pay") }}',
		       	type: 'post',
		       	data: fd,
		       	contentType: false,
		       	processData: false,
		       	success: function(response){
		       		$('.loading-gif').hide();
		       		if(response != "ok"){
		       			// console.log(response)
		       			toastr.error(response);
		       			return false;
		       		}
					
		       		if(!$('.pay-order-no').val()){
			       		$('.order-type').filter(function(){return $(this).data('filter')==1}).html('Dine in');
			       		$('.order-type').removeClass('active');
			       		$('.dine-in-table').val('');
			       		$('.dine-in-table-headcount').val('');
			       		$('.order-no').val('');
			       		$('.member').val('');
			       		$('.member_point').html('');
						$('.toggle-point-area').hide();
			       		$('.toggle-point').prop('checked', false);

			       		$('.selected-items').html('<div class="row">\
														<div class="col-12 no-item" align="center">\
															{{ isset($data["backendlang"]["backendlang"]["Please_add_an_item"]) ? $data["backendlang"]["backendlang"]["Please_add_an_item"] : "Please add an item" }}\
														</div>\
												   </div>');
			       		calc();		
						location.reload();       			
		       		}else{
		       			var fd = new FormData();
							fd.append('id', $('.pay-table').val());

		       			$.ajax({
					       	url: '{{ route("GetTableHistory") }}',
					       	type: 'post',
					       	data: fd,
					       	contentType: false,
					       	processData: false,
					       	success: function(response){
					       		$('.select-table-box').hide();
					       		$('.table-history-box').show();
					       		$('.table-history-box').html(response);
					       		$('.table-history-box .search_date').datepicker("setDate", new Date());
					       		calc();
					       	},
					    });
		       		}

		       		$('#payment_box').modal('toggle');
		       		$('input[name="amount"]').val('');
		       		$('.changes-amount').html('');
		       		$('.pay-order-no').val('');

					toastr.success( '{{ isset($data["backendlang"]["backendlang"]["Order_placed_successful"]) ? $data["backendlang"]["backendlang"]["Order_placed_successful"] : "Order placed successful" }}');
					location.reload(); 
		       	},
		    });
		}
	});

	$('.pay-order-btn').click(function(e){
		e.preventDefault();

		var ele = $(this);
		var checkItem = $('.got-item').length;
		if(checkItem < 1){
			toastr.error('{{ isset($data["backendlang"]["backendlang"]["Please_pick_at_least_one_item_first"]) ? $data["backendlang"]["backendlang"]["Please_pick_at_least_one_item_first"] : "Please pick at least one item first." }}');
			return false;
		}

		var order_type = $('.order-type.active').data('filter');
		var dine_table = $('.dine-in-table').val();
		var dine_head = $('.dine-in-table-headcount').val();
		var grandTotal = $('.grandTotal').html();
		
		

		$('#payment_box').modal('toggle');
		$('.totalAmount').html(grandTotal);
		$('.totalHiddenAmount').val(grandTotal);
		$('.totalActualHiddenAmount').val(grandTotal);
		$('.pay-table').val(dine_table);
		$('.pay-order-type').val(order_type);
		// if(!order_type){
		// 	$('.SelectOrderType').click();
		// }else{
		// 	if(order_type == 1){
		// 		if(!dine_table && !dine_head){
		// 			$('#select_table').modal('toggle');
		// 		}else{
		// 		}
		// 	}else{
		// 		$('.totalAmount').html(grandTotal);
		// 		$('.totalHiddenAmount').val(grandTotal);
		// 		$('.totalActualHiddenAmount').val(grandTotal);
		// 		$('#payment_box').modal('toggle');
		// 		$('.pay-table').val(dine_table);
		// 		$('.pay-order-type').val(order_type);
		// 	}
		// }
	});

	$('input[name="amount"]').keyup(function(e){
		var ele = $(this);
		if(ele.val() == ''){
			$('.changes-amount').html('0.00');
			return false;
		}
		var amount = parseFloat(ele.val());
		var totalAmount = parseFloat($('.totalAmount').html());
		// alert(amount+' '+totalAmount);
		var balance = amount - totalAmount;

		if(balance > 0){
			$('.changes-amount').html(parseFloat(balance).toFixed(2));
		}else if(balance == 0){
			$('.changes-amount').html('0.00');
		}
	});

	$('.pay-now-btn').click( function(e){
		e.preventDefault();
		$('.loading-gif').show();
		var ele = $(this);
		$('#payment_box').modal('toggle');
		
		var fd = new FormData();
			fd.append('order_no', $('.pay-now-order-no').val());
			
			$.ajax({
		       	url: '{{ route("checkOrderAmount") }}',
		       	type: 'post',
		       	data: fd,
		       	contentType: false,
		       	processData: false,
		       	success: function(response){
		       		$('.loading-gif').hide();
		       		$('.totalAmount').html(response);
		       		$('.totalHiddenAmount').val(response);
					$('.totalActualHiddenAmount').val(response);
		       		$('.pay-order-no').val($('.pay-now-order-no').val());
		       		$('.pay-table').val(ele.data('table'));
		       		$('.pay-order-type').val('1');
		       	},
		    });
	});

	$('.search-button').click( function(e){
		e.preventDefault();
		$('.loading-gif').show();
		var ele = $(this);
		var value = $('.search-query').val();

		var fd = new FormData();
    		fd.append('search_value', value);

    	$.ajax({
	       url: '{{ route("SearchItems") }}',
	       type: 'post',
	       data: fd,
	       contentType: false,
	       processData: false,
	       success: function(response){
	       		$('.loading-gif').hide();
	       		$('.item-list-child').html(response);
	       },
	    });
	});

	$('.table-history-box').on('click', '.print-receipt', function(e){
		e.preventDefault();
		var ele = $(this);
		var url = "{{ route('print_receipt', ':id') }}";
		url = url.replace(':id', ele.data('id'))
		window.open(url);
		// var fd = new FormData();
		// 	fd.append('transaction_no', ele.data('id'));

		// $.ajax({
	 //       	url: '{{ route("PrintTransaction") }}',
	 //       	type: 'post',
	 //       	data: fd,
	 //       	contentType: false,
	 //       	processData: false,
	 //       	success: function(response){
	 //       		var newWin=window.open('','Print-Window');

		// 		  	newWin.document.open();

		// 		  	newWin.rightMargin = 0;
		// 		    newWin.leftMargin = 0;
		// 		    newWin.topMargin = 0;
		// 		    newWin.bottomMargin = 0;

		// 		  	newWin.document.write('<html style="margin: 0px;"><body onload="window.print()" style="margin: 0px;">'+response+'</body></html>');


		// 		  	newWin.document.close();

		// 		setTimeout(function(){newWin.close();},10);
	 //       	},
	 //    });
   	});

	$('.print-transaction').click( function(e){
		e.preventDefault();
		var ele = $(this);

		var url = "{{ route('print_receipt', ':id') }}";
		url = url.replace(':id', ele.data('id'))
		window.open(url);
	});

	$('.table-history-box').on('click', '.select-transaction-pay-btn', function(e){
		e.preventDefault();
		var ele = $(this);
		$('#payment_box').modal('toggle');
		
		var fd = new FormData();
			fd.append('order_no', ele.data('id'));
			
			$.ajax({
		       	url: '{{ route("checkOrderAmount") }}',
		       	type: 'post',
		       	data: fd,
		       	contentType: false,
		       	processData: false,
		       	success: function(response){
		       		$('.loading-gif').hide();
		       		// alert(ele.data('id')+' '+ele.data('table'));
		       		$('.totalAmount').html(response);		       		
		       		$('.totalHiddenAmount').val(response);
					$('.totalActualHiddenAmount').val(response);
		       		$('.pay-order-no').val(ele.data('id'));
		       		$('.pay-table').val(ele.data('table'));
		       		$('.pay-order-type').val('1');
		       	},
		    });
   	});

   	$('.table-history-box').on('click', '.select-cancel-transaction-btn', function(e){
		e.preventDefault();
		var ele = $(this);
		if(confirm('{{ isset($data["backendlang"]["backendlang"]["Cancel_this_order?"]) ? $data["backendlang"]["backendlang"]["Cancel_this_order?"] : "Cancel this order?" }}') == true){
			$('.loading-gif').show();
			var fd = new FormData();
				fd.append('order_no', ele.data('id'));
				
				$.ajax({
			       	url: '{{ route("CancelOrder") }}',
			       	type: 'post',
			       	data: fd,
			       	contentType: false,
			       	processData: false,
			       	success: function(response){

			       		var fd = new FormData();
							fd.append('id', ele.data('table'));

			       		$.ajax({
					       	url: '{{ route("GetTableHistory") }}',
					       	type: 'post',
					       	data: fd,
					       	contentType: false,
					       	processData: false,
					       	success: function(response){
					       		$('.loading-gif').hide();
					       		$('.select-table-box').hide();
					       		$('.table-history-box').show();
					       		$('.table-history-box').html(response);
					       		$('.table-history-box .search_date').datepicker("setDate", new Date());
					       	},
					    });
			       	},
			    });
		}
   	});

   	$('.table-history-box').on('click', '.select-edit-transaction-btn', function(e){
		e.preventDefault();
		var ele = $(this);
		$('.loading-gif').show();
		

		var fd = new FormData();
			fd.append('transaction_no', ele.data('id'));

		$.ajax({
	       	url: '{{ route("GetTransaction") }}',
	       	type: 'post',
	       	data: fd,
	       	contentType: false,
	       	processData: false,
	       	success: function(response){
	       		$('#edit-transaction').modal('toggle');
	       		$('.loading-gif').hide();
	       		$('.transaction-listing').html(response);
	       	},
	    });
   	});

   	$('.table-history-box').on('click', '.select-add-transaction-btn', function(e){
		e.preventDefault();
		var ele = $(this);

		$('.order-no').val(ele.data('id'));
		$('.order-type').filter(function(){return $(this).data('filter')==1}).addClass('active');
		$('.order-type').filter(function(){return $(this).data('filter')==1}).html('Dine in on '+$('.selected-table-name').html());
		$('.dine-in-table').val(ele.data('table'));
		$('#select_table').modal('toggle');
	});

	$('.transaction-listing').on('click', '.edit-list-delete-btn', function(e){
		e.preventDefault();
		var ele = $(this);
		if(confirm('Delete this item?') == true){
			ele.closest('.parent-box').remove();
			var items = $('.transaction-listing').find('.parent-box').length;
			if(items == 1){
				$('.transaction-listing').find('.edit-list-delete-btn').remove();
			}
		}
	});

	$('.save-change-transaction-btn').click(function(e){
		e.preventDefault();

		if(confirm('Confirm save this order?') == true){
			$('.loading-gif').show();

			$.ajax({
		       	url: '{{ route("saveTransaction") }}',
		       	type: 'get',
		       	data: $("#transaction-form").serialize(),
		       	success: function(response){
		       		$('.loading-gif').hide();
		       		// alert(response);
		       		$('#edit-transaction').modal('toggle');
					toastr.success('{{ isset($data["backendlang"]["backendlang"]["Order updated"]) ? $data["backendlang"]["backendlang"]["Order updated"] : "Order updated" }}');

		       		var fd = new FormData();
						fd.append('id', response);

		       		$.ajax({
				       	url: '{{ route("GetTableHistory") }}',
				       	type: 'post',
				       	data: fd,
				       	contentType: false,
				       	processData: false,
				       	success: function(response){

				       		$('.loading-gif').hide();
				       		$('.select-table-box').hide();
				       		$('.table-history-box').show();
				       		$('.table-history-box').html(response);
				       		$('.table-history-box .search_date').datepicker("setDate", new Date());

				       	},
				    });
		       	},
		    });
		}
	});

	$('.table-history-box').on('change', '.search_date', function(){
		var ele = $(this);
		$('.loading-gif').show();
		var fd = new FormData();
			fd.append('id', $('.selected_t_n_d').val());
			fd.append('search_date', ele.val());

		$.ajax({
	       	url: '{{ route("SearchTableHistory") }}',
	       	type: 'post',
	       	data: fd,
	       	contentType: false,
	       	processData: false,
	       	success: function(response){
	       		// alert($('.selected_t_n_d').val());
	       		$('.loading-gif').hide();
	       		$('.table-history-box').find('.result-box').html(response);
	       		// $('.select-table-box').hide();
	       		// $('.table-history-box').show();
	       		// $('.table-history-box').html(response);
	       		// $('.table-history-box .search_date').datepicker();
	       		// return false;

	       	},
	    });
	});

	$('.combine-receipt-btn').click(function(e){
		e.preventDefault();
		$('.loading-gif').show();
		var ele = $(this);
		$('.order-list-area').html("Loading..");

		var fd = new FormData();
			fd.append('id', $('.selected_t_n_d').val());
			fd.append('search_date', ele.val());

		$.ajax({
	       	url: '{{ route("GetOrders") }}',
	       	type: 'post',
	       	data: fd,
	       	contentType: false,
	       	processData: false,
	       	success: function(response){
	       		$('.loading-gif').hide();

	       		$('#combine-receipt .order-list-area').html(response);
	       	},
	    });
	});

	$('.transfer-table-btn').click(function(e){
		e.preventDefault();
		$('.loading-gif').show();
		var ele = $(this);
		$('.order-list-area').html("Loading..");

		var fd = new FormData();
			fd.append('id', $('.selected_t_n_d').val());
			fd.append('search_date', ele.val());

		$.ajax({
	       	url: '{{ route("GetAvailableTable") }}',
	       	type: 'post',
	       	data: fd,
	       	contentType: false,
	       	processData: false,
	       	success: function(response){
	       		$('.loading-gif').hide();

	       		$('#transfer-table .table-list-area').html(response);
	       	},
	    });
	});

	$('.refund-transaction-btn').click(function(e){
		e.preventDefault();
		$('.loading-gif').show();
		var ele = $(this);
		$('.order-list-area').html("Loading..");

		var fd = new FormData();
			// fd.append('id', $('.selected_t_n_d').val());
			// fd.append('search_date', ele.val());

		$.ajax({
	       	url: '{{ route("GetTransactionList") }}',
	       	type: 'post',
	       	data: fd,
	       	contentType: false,
	       	processData: false,
	       	success: function(response){
	       		$('.loading-gif').hide();

	       		$('#refund-modal .transaction-list-area').html(response);
	       	},
	    });
	});

	$('.transaction-list-area').on('click', '.search-button', function(){
		
		$('.loading-gif').show();
		var ele = $(this);
		var value = $('.transaction-query').val();
		var date = $('.datetimepicker').val();
		
		var fd = new FormData();
    		fd.append('search_value', value);
    		fd.append('date', date);

    	$.ajax({
	       url: '{{ route("GetTransactionList") }}',
	       type: 'post',
	       data: fd,
	       contentType: false,
	       processData: false,
	       success: function(response){
	       		$('.loading-gif').hide();
	       		$('#refund-modal .transaction-list-area').html(response[0]);
	       },
	    });
	});

	$('.transaction-list-area').on('click', '.refund_action', function(){
		$('.loading-gif').show();

		var ele = $(this);
		var tid = $(this).closest('tr').find('input[name="tid"]').val();
		var fd = new FormData();
		fd.append('tid', tid);

		if(confirm('{{ isset($data["backendlang"]["backendlang"]["Confirm_Refunding_This_Transaction?"]) ? $data["backendlang"]["backendlang"]["Confirm_Refunding_This_Transaction?"] : "Confirm Refunding This Transaction?" }}')== true){
			$.ajax({
				url: '{{ route("RefundTransaction") }}',
				type: 'post',
				data: fd,
				contentType: false,
				processData: false,
				success: function(response){
					$('.loading-gif').hide();
					toastr.success("Done");
					window.location.href = "{{ route('cashier_screen') }}";
				},
			});
		}else{
			$('.loading-gif').hide();
		}
	});

	$('.order-list-area').on('click', '.selected-transaction', function(){
		var ele = $(this);

		ele.toggleClass('selected');
	});

	$('.comfirm-combine-receipt-btn').click(function(e){
		e.preventDefault();

		var check = $('.selected-transaction.selected').length;

		
		if(check <= 1){
			toastr.error('{{ isset($data["backendlang"]["backendlang"]["Please_select_at_least_two_order_to_combine"]) ? $data["backendlang"]["backendlang"]["Please_select_at_least_two_order_to_combine"] : "Please select at least two order to combine" }}');
			return false;
		}

		var arrayA = [];
	    $('.selected-transaction.selected').each(function () {
	        arrayA.push($(this).data('id'));
	    });

	    var fd = new FormData();
			fd.append('selected_transaction_no', arrayA);

		$.ajax({
	       	url: '{{ route("CombineReceipt") }}',
	       	type: 'post',
	       	data: fd,
	       	contentType: false,
	       	processData: false,
	       	success: function(response){
	       		$('.loading-gif').hide();
	       		$('#payment_combine').modal('toggle');
	       		$('#payment_combine').find('.totalAmount').html(parseFloat(response).toFixed(2));
	       		$('#payment_combine').find('.totalHiddenAmount').val(response);
	       		$('#payment_combine').find('.totalActualHiddenAmount').val(response);

	       	},
	    });

	});

	$('.qr-pay-type').click(function(e){
		var ele = $(this);

		$('.qr-pay-type').removeClass('selected');
		ele.addClass('selected');
	});

	$('.submit-combine-payment-btn').click(function(e){
		e.preventDefault();

		var ele = $(this);
		var payment_method = $('#payment_combine').find('.payment-method.active').data('filter');
		var cash_amount = $('#payment_combine').find('input[name="amount"]').val();
		var qr_type = $('#payment_combine').find('.qr-pay-type.selected').data('id');
		var reference_number = $('#payment_combine').find('input[name="reference_number"]').val();
		var bank_name = $('#payment_combine').find('.bank_name').val();
		var cc_reference_number = $('#payment_combine').find('input[name="cc_reference_number"]').val();
		var totalAmount = $('#payment_combine').find('.totalHiddenAmount').val();
		var totalDiscount = $('.after-discount').find('.after_discount_amount').val();
		var payment_discount_type = $('#payment_combine').find('.payment_discount_type').val();
		var combine_payment_discount_amount = $('#payment_combine').find('.combine_payment_discount_amount').val();
		payment_discount_type = (payment_discount_type) ? payment_discount_type : "";
		combine_payment_discount_amount = (combine_payment_discount_amount > 0) ? combine_payment_discount_amount : 0;
		totalDiscount = (totalDiscount > 0) ? totalDiscount : 0;

		if(!payment_method){
			toastr.error('{{ isset($data["backendlang"]["backendlang"]["Please_Select_Payment_Method"]) ? $data["backendlang"]["backendlang"]["Please_Select_Payment_Method"] : "Please Select Payment Method" }}');
			return false;
		}

		if(payment_method == 1 && !cash_amount){
			toastr.error('{{ isset($data["backendlang"]["backendlang"]["Please_Insert_Amount"]) ? $data["backendlang"]["backendlang"]["Please_Insert_Amount"] : "Please Insert Amount" }}');
			return false;
		}

		if(payment_method == 1 && parseFloat(cash_amount) < parseFloat(totalAmount)){
			toastr.error('{{ isset($data["backendlang"]["backendlang"]["Amount_Less_then_Payment_Amount"]) ? $data["backendlang"]["backendlang"]["Amount_Less_then_Payment_Amount"] : "Amount Less then Payment Amount" }}');
			return false;
		}

		if(payment_method == 2 && !qr_type){
			toastr.error('{{ isset($data["backendlang"]["backendlang"]["Please_Choose_Qr_Pay_Type"]) ? $data["backendlang"]["backendlang"]["Please_Choose_Qr_Pay_Type"] : "Please Choose Qr Pay Type" }}');
			return false;
		}

		if(payment_method == 2 && !reference_number){
			toastr.error('{{ isset($data["backendlang"]["backendlang"]["Please_Insert_Reference_Number"]) ? $data["backendlang"]["backendlang"]["Please_Insert_Reference_Number"] : "Please Insert Reference Number" }}');
			return false;
		}

		if(payment_method == 3 && !bank_name){
			toastr.error('{{ isset($data["backendlang"]["backendlang"]["Please_Select_Bank"]) ? $data["backendlang"]["backendlang"]["Please_Select_Bank"] : "Please Select Bank" }}');
			return false;
		}

		if(payment_method == 3 && !cc_reference_number){
			toastr.error('{{ isset($data["backendlang"]["backendlang"]["Please_Insert_Reference_Number"]) ? $data["backendlang"]["backendlang"]["Please_Insert_Reference_Number"] : "Please Insert Reference Number" }}');
			return false;
		}

		var arrayA = [];
	    $('.selected-transaction.selected').each(function () {
	        arrayA.push($(this).data('id'));
	    });

	    
		var fd = new FormData();
			fd.append('payment_method', payment_method);
			fd.append('transaction_no', arrayA);
			fd.append('totalAmount', totalAmount);
			fd.append('totalDiscount', totalDiscount);
			fd.append('cash_amount', cash_amount);
			fd.append('payment_discount_type', payment_discount_type);
			fd.append('combine_payment_discount_amount', combine_payment_discount_amount);
			fd.append('reference_number', reference_number);
			fd.append('cc_reference_number', cc_reference_number);
			fd.append('bank_name', bank_name);
			fd.append('qr_type', qr_type);
		
		

		$.ajax({
	       	url: '{{ route("CombineReceiptSubmit") }}',
	       	type: 'post',
	       	data: fd,
	       	contentType: false,
	       	processData: false,
	       	success: function(response){
	       		$('.loading-gif').hide();
	       		if(response[0] == "ok"){
	       			$('#combine-receipt').modal('toggle');
	       			$('#payment_combine').modal('toggle');
	       			toastr.success('Combine & Pay Successfully');
	       			$('.before-discount').show();
					$('.after-discount').html("");
					$('.after-discount').hide();
	       			$('input[name="amount"]').val('');
	       			$('.changes-amount').html("0.00");
	       			$('#print-combine-receipt').modal('toggle');
	       			$('.combine_receipt_transaction_no').val(response[1]);
	       		}else{
	       			toastr.error('{{ isset($data["backendlang"]["backendlang"]["Combine_Error"]) ? $data["backendlang"]["backendlang"]["Combine_Error"] : "Combine Error" }}');
	       		}
	       	},
	    });
	});

	$('#print-combine-receipt').on('click', '.comfirm-print-receipt-btn', function(e){
		e.preventDefault();
		var ele = $(this);
		var transNo = $(this).closest('#print-combine-receipt').find('.combine_receipt_transaction_no').val();
		var url = "{{ route('print_receipt', ':id') }}";
		url = url.replace(':id', transNo);
		window.open(url);
	});

	$('.add-discount-btn').click(function(e){
		e.preventDefault();

		var ele = $(this);
		var payment_discount_type = $('.payment_discount_type').val();
      	var combine_payment_discount_amount = $('input[name="combine_payment_discount_amount"]').val();
      	var totalAmount = $('#payment_combine').find('.totalHiddenAmount').val();

      	var totalDiscount = 0;
      	var discountDisplay = "";
      	if(payment_discount_type == 'Percentage'){
      		if(combine_payment_discount_amount > 100){
      			$('input[name="combine_payment_discount_amount"]').val('');
      			toastr.error('{{ isset($data["backendlang"]["backendlang"]["Maximum_of_percentage_not_exceed_100"]) ? $data["backendlang"]["backendlang"]["Maximum_of_percentage_not_exceed_100"] : "Maximum of percentage not exceed 100" }}');
				
      			return false;
      		}else if(combine_payment_discount_amount <= 0){
      			$('input[name="combine_payment_discount_amount"]').val('');
      			toastr.error('{{ isset($data["backendlang"]["backendlang"]["Minimum_1_percentage_for_discount"]) ? $data["backendlang"]["backendlang"]["Minimum_1_percentage_for_discount"] : "Minimum 1 percentage for discount" }}');
      			return false;
      		}else{
      			totalDiscount = parseFloat(totalAmount) * parseFloat(combine_payment_discount_amount) / 100;
      			discountDisplay = "("+combine_payment_discount_amount+'%)';
      		}
      	}else{
      		totalDiscount = parseFloat(combine_payment_discount_amount);
      		discountDisplay = "";
      	}

      	if(parseFloat(totalDiscount) > parseFloat(totalAmount)){
      		toastr.error('{{ isset($data["backendlang"]["backendlang"]["Discount_Amount_exceeded_Pay_Amount"]) ? $data["backendlang"]["backendlang"]["Discount_Amount_exceeded_Pay_Amount"] : "Discount Amount exceeded Pay Amount" }}');		
      		return false;
      	}

      	var balanceAmount = parseFloat(totalAmount) - parseFloat(totalDiscount);
      	var amount = $('#payment_combine').find('input[name="amount"]').val();
      	var balance = amount - balanceAmount;

      	$('.changes-amount').html(parseFloat(balance).toFixed(2));

      	$('#payment_combine').find('.totalAmount').html(parseFloat(balanceAmount).toFixed(2));
      	$('#payment_combine').find('.totalHiddenAmount').val(balanceAmount);

      	$('.before-discount').hide();
      	$('.after-discount').html(' {{ isset($data["backendlang"]["backendlang"]["Discount"]) ? $data["backendlang"]["backendlang"]["Discount"] : "Discount" }}: - RM'+parseFloat(totalDiscount).toFixed(2)+discountDisplay+' <a href="#" class="remove-discount" style="color: #84d8b6;">Remove</a>\n <input type="hidden" class="after_discount_amount" value="'+totalDiscount+'">\n <input type="hidden" class="payment_discount_type" value="'+payment_discount_type+'">\n<input type="hidden" class="combine_payment_discount_amount" value="'+combine_payment_discount_amount+'">');
      	$('.after-discount').show();

		$('input[name="combine_payment_discount_amount"]').val("");
		$('#add-discount-modal').modal('toggle');

	});

	$('.after-discount').on('click', '.remove-discount', function(e){
		e.preventDefault();

		var ele = $(this);
		var actual_amount = $('#payment_combine').find('.totalActualHiddenAmount').val();

		var amount = $('#payment_combine').find('input[name="amount"]').val();
		// var totalAmount = parseFloat($('.totalAmount').html());
		// var discountRemoved = $('.after-discount').find('input[name="combine_payment_discount_amount"]').val();
		  // alert(amount+' '+totalAmount+ '  discount:' +discountRemoved+ 'actual_amount' + actual_amount);
		var balance = amount - actual_amount;

		$('.changes-amount').html(parseFloat(balance).toFixed(2));

		$('.before-discount').show();
		$('.after-discount').html("");
		$('.after-discount').hide();

		$('#payment_combine').find('.totalAmount').html(parseFloat(actual_amount).toFixed(2));
      	$('#payment_combine').find('.totalHiddenAmount').val(actual_amount);


	});

	$('.checkout-add-discount-btn').click(function(e){
		e.preventDefault();

		var ele = $(this);
		var payment_discount_type = $('#checkout-add-discount-modal').find('.payment_discount_type').val();
      	var combine_payment_discount_amount = $('#checkout-add-discount-modal').find('input[name="combine_payment_discount_amount"]').val();
      	var totalAmount = $('#payment_box').find('.totalHiddenAmount').val();

      	var totalDiscount = 0;
      	var discountDisplay = "";
      	if(payment_discount_type == 'Percentage'){
      		if(combine_payment_discount_amount > 100){
      			$('input[name="combine_payment_discount_amount"]').val('');
      			toastr.error('{{ isset($data["backendlang"]["backendlang"]["Maximum_100_percentage_for_discount"]) ? $data["backendlang"]["backendlang"]["Maximum_100_percentage_for_discount"] : "Maximum 100 percentage for discount" }}');
      			return false;
      		}else if(combine_payment_discount_amount <= 0){
      			$('input[name="combine_payment_discount_amount"]').val('');
      			toastr.error('{{ isset($data["backendlang"]["backendlang"]["Minimum_1_percentage_for_discount"]) ? $data["backendlang"]["backendlang"]["Minimum_1_percentage_for_discount"] : "Minimum 1 percentage for discount" }}');
      			return false;
      		}else{
      			totalDiscount = parseFloat(totalAmount) * parseFloat(combine_payment_discount_amount) / 100;
      			discountDisplay = "("+combine_payment_discount_amount+'%)';
      		}
      	}else{
      		totalDiscount = parseFloat(combine_payment_discount_amount);
      		discountDisplay = "";
      	}



      	if(parseFloat(totalDiscount) > parseFloat(totalAmount)){
      		toastr.error('{{ isset($data["backendlang"]["backendlang"]["Discount_Amount_exceeded_Pay_Amount"]) ? $data["backendlang"]["backendlang"]["Discount_Amount_exceeded_Pay_Amount"] : "Discount Amount exceeded Pay Amount" }}');
      		return false;
      	}

      	var balanceAmount = parseFloat(totalAmount) - parseFloat(totalDiscount);
      	var amount = $('.cash-payment-box').find('input[name="amount"]').val();
      	var balance = amount - balanceAmount;

      	$('.changes-amount').html(parseFloat(balance).toFixed(2));

      	$('#payment_box').find('.totalAmount').html(parseFloat(balanceAmount).toFixed(2));
      	$('#payment_box').find('.totalHiddenAmount').val(balanceAmount);

      	$('#payment_box').find('.before-discount').hide();
      	$('#payment_box').find('.after-discount').html('{{ isset($data["backendlang"]["backendlang"]["Discount"]) ? $data["backendlang"]["backendlang"]["Discount"] : "Discount" }}: - RM'+parseFloat(totalDiscount).toFixed(2)+discountDisplay+' <a href="#" class="remove-discount" style="color: #84d8b6;">Remove</a>\n <input type="hidden" class="after_discount_amount" value="'+totalDiscount+'">\n <input type="hidden" class="payment_discount_type" value="'+payment_discount_type+'">\n<input type="hidden" class="combine_payment_discount_amount" value="'+combine_payment_discount_amount+'">');
      	$('#payment_box').find('.after-discount').show();

		$('#checkout-add-discount-modal').find('input[name="combine_payment_discount_amount"]').val("");
		$('#checkout-add-discount-modal').modal('toggle');
	});

	$('#payment_box .after-discount').on('click', '.remove-discount', function(e){
		e.preventDefault();

		var ele = $(this);
		var actual_amount = $('#payment_box').find('.totalActualHiddenAmount').val();

		var amount = $('#payment_box').find('input[name="amount"]').val();
		var balance = amount - actual_amount;

		$('.changes-amount').html(parseFloat(balance).toFixed(2));

		$('.before-discount').show();
		$('.after-discount').html("");
		$('.after-discount').hide();

		$('#payment_box').find('.totalAmount').html(parseFloat(actual_amount).toFixed(2));
      	$('#payment_box').find('.totalHiddenAmount').val(actual_amount);
	});

	$('#payment_combine').on('keyup', 'input[name="amount"]', function(){
		var ele = $(this);

		var totalAmount = $('#payment_combine').find('.totalHiddenAmount').val();
		if(ele.val() == ''){
			$('#payment_combine').find('.changes-amount').html('0.00');
			return false;
		}
		var amount = parseFloat(ele.val());
		var balance = amount - totalAmount;

		if(balance > 0){
			$('#payment_combine').find('.changes-amount').html(parseFloat(balance).toFixed(2));
		}else if(balance == 0){
			$('#payment_combine').find('.changes-amount').html('0.00');
		}

		
	});

	$('#payment_box').on('hidden.bs.modal', function () {
	  	$('#payment_box').find('.before-discount').show();
		$('#payment_box').find('.after-discount').html("");
		$('#payment_box').find('.after-discount').hide();
	});

	$('#transfer-table').on('click', '.comfirm-transfer-table-btn', function(e){
		e.preventDefault();

		var ele = $(this);

		var transfer_from = $('#transfer-table').find('.transfer_from').val();
		var transfer_to = $('#transfer-table').find('.transfer_to').val();

		var fd = new FormData();
			fd.append('transfer_from', transfer_from);
			fd.append('transfer_to', transfer_to);
		if(confirm('Comfirm Transfer Table?')){
			$.ajax({
		       	url: '{{ route("TransferTable") }}',
		       	type: 'post',
		       	data: fd,
		       	contentType: false,
		       	processData: false,
		       	success: function(response){
		       		$('.loading-gif').hide();
		       		$('#transfer-table').modal('toggle');
		       		toastr.success('Transfer Successful');
		       	},
		    });
		}
	});

	$('.view-delivery-btn').click(function(e){
		e.preventDefault();
		$('.loading-gif').show();
		var ele = $(this);

		$.ajax({
	       	url: '{{ route("getDeliveryOrder") }}',
	       	type: 'get',
	       	success: function(response){
	       		$('.loading-gif').hide();
	       		$('.delivery-list-area').html(response);
	       	},
	    });
	});


	$('.delivery-list-area').on('click', '.complete-delivery-btn', function(){
		var ele = $(this);

		var transaction_no = ele.data('id');

		var fd = new FormData();
			fd.append('transaction_no', transaction_no);

		if(confirm('Comfirm Completed?')){
			$.ajax({
		       	url: '{{ route("CompleteDelivery") }}',
		       	type: 'post',
		       	data: fd,
		       	contentType: false,
		       	processData: false,
		       	success: function(response){
		       		$('.loading-gif').hide();
		       		ele.closest('.selected-delivery-transaction').remove();
		       		toastr.success('Successful');
		       	},
		    });
		}
	});

	$('.member').change(function(){

		var ele = $(this);

		$('.member_point').val('');
		var fd = new FormData();
			fd.append('phone', ele.val());

			$.ajax({
		       	url: '{{ route("get_member_wallet") }}',
		       	type: 'post',
		       	data: fd,
		       	contentType: false,
		       	processData: false,
		       	success: function(response){
		       		$('.loading-gif').hide();
		       		if(response['message'] == 2){
		       			$('.member_point').html('<span class="badge bg-success"> New Customer</span><br><span class="badge-primary">'+parseFloat(response['wallet']).toFixed(2)+' Point Balance</span>');
		       		}else{
		       			$('.member_point').html('<span class="badge bg-info"> Existing Customer </span><br><span class="badge-primary">'+parseFloat(response['wallet']).toFixed(2)+' Point Balance</span>');
		       		}

		       		if(response['wallet'] > 0){
		       			ele.closest('#payment_box').find('.member_point').val(response['wallet']);
		       			$('.toggle-point-area').show();
		       		}else{
		       			$('.toggle-point-area').hide();
		       		}
		       	},
		    });
	});

	$('.toggle-point').click(function(){
		var ele = $(this);

		var member = $('.member').val();

		if(!member){
			toastr.error('Please insert member phone');
			return false;
		}

		var grandTotal = $('.totalHiddenAmount').val();

		if(ele.prop('checked') == true){
			var fd = new FormData();
				fd.append('phone', member);

				$.ajax({
			       	url: '{{ route("get_member_wallet") }}',
			       	type: 'post',
			       	data: fd,
			       	contentType: false,
			       	processData: false,
			       	success: function(response){
			       		console.log(response['wallet'])
			       		if(response['wallet'] > 0){
			       			var point_convert = "{{ $data['web_setting']->rm_to_point }}";
			       			point_convert = (point_convert > 0) ? point_convert : 1;

			       			var total_discount = parseFloat(response['wallet']) * parseFloat(point_convert);
			       			$('.point-discount').html('Point Discount: (-) RM '+parseFloat(total_discount).toFixed(2));

			       			$('.totalAmount').html(parseFloat(parseFloat(grandTotal) - parseFloat(total_discount)).toFixed(2));
			       			$('.totalHiddenAmount').val(parseFloat(parseFloat(grandTotal) - parseFloat(total_discount)).toFixed(2));
			       		}
			       	},
			    });
		}else{
			$('.point-discount').html('');

			$('.totalAmount').html(parseFloat($('.totalActualHiddenAmount').val()).toFixed(2));
			$('.totalHiddenAmount').val(parseFloat($('.totalActualHiddenAmount').val()).toFixed(2));
		}
	})

	$('.all_users').change(function(e){

		var member = $('.all_users').val();

		var fd = new FormData();
			fd.append('member', member);

			$.ajax({
		       	url: '{{ route("refresh_carts") }}',
		       	type: 'post',
		       	data: fd,
		       	contentType: false,
		       	processData: false,
		       	success: function(response){
		       		calc();
		       		$('.selected-items').html(response)
		       		// console.log(response)
		       	},
		    });
	});
</script>
@endsection