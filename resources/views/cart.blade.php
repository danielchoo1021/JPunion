@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="from-group">
			<div class="row">
				<div class="col-md-12">
					<ol class="breadcrumb">
					  <li><a href="#">Home</a></li>
					  <li class="active">Shopping Cart</li>
					</ol>
				</div>
			</div>
		</div>
		<div class="web-cart">
			<div class="form-group">
				<div class="cart-header-list">
					<ul>
						<li class="select-cart">
							<div class="checkbox">
								<label>
									<input name="form-field-checkbox" type="checkbox" class="ace select-all-checkbox" />
									<span class="lbl"></span>
								</label>
							</div>
						</li>
						<li class="product-name">
							<b>Product</b>
						</li>
						<li class="unit-price">
							<b>Unit Price</b>
						</li>
						<li class="product-quantity">
							<b>Quantity</b>
						</li>
						<li class="product-total-price">
							<b>Total Price</b>
						</li>
						<li class="list-action">
							<b>Actions</b>
						</li>
					</ul>
				</div>
			</div>
			<div class="form-group">
				<div class="cart-details-list">
					<div class="form-group">
						<ul>
							<li class="select-cart">
								<div class="checkbox">
									<label>
										<input name="form-field-checkbox" type="checkbox" class="ace list-check" />
										<span class="lbl"></span>
									</label>
								</div>
							</li>
							<li class="product-name">
								<div class="form-group product-all-details">
									<img src="{{ asset('assets/images/gallery/thumb-1.jpg') }}">
									<span class="product-details-name">
										Chiffon Polka Dot Long Sleeve Loose Tops Blouse Casual Shirt
									</span>
								</div>
							</li>
							<li class="unit-price">
								<div class="form-group">
									 RM13.00 <br>
									 <strike>RM15.00</strike>
								</div>
							</li>
							<li class="product-quantity">
								<div class="form-group quantity-setting">
									<button class="btn btn-primary deduct-qty-button">
										<i class="fa fa-minus"></i>
									</button>
									<input type="text" class="form-control" name="quantity" value="1">
									<button class="btn btn-primary add-qty-button">
										<i class="fa fa-plus"></i>
									</button>
								</div>
							</li>
							<li class="product-total-price">
								RM13.00
							</li>
							<li class="list-action">
								<i class="fa fa-trash important-text"></i>
							</li>
						</ul>
					</div>

					<div class="form-group">
						<ul>
							<li class="select-cart">
								<div class="checkbox">
									<label>
										<input name="form-field-checkbox" type="checkbox" class="ace list-check" />
										<span class="lbl"></span>
									</label>
								</div>
							</li>
							<li class="product-name">
								<div class="form-group product-all-details">
									<img src="{{ asset('assets/images/gallery/thumb-1.jpg') }}">
									<span class="product-details-name">
										Chiffon Polka Dot Long Sleeve Loose Tops Blouse Casual Shirt
									</span>
								</div>
							</li>
							<li class="unit-price">
								<div class="form-group">
									 RM13.00 <br>
									 <strike>RM15.00</strike>
								</div>
							</li>
							<li class="product-quantity">
								<div class="form-group quantity-setting">
									<button class="btn btn-primary deduct-qty-button">
										<i class="fa fa-minus"></i>
									</button>
									<input type="text" class="form-control" name="quantity" value="1">
									<button class="btn btn-primary add-qty-button">
										<i class="fa fa-plus"></i>
									</button>
								</div>
							</li>
							<li class="product-total-price">
								RM13.00
							</li>
							<li class="list-action">
								<i class="fa fa-trash important-text"></i>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>

		<div class="mobile-cart">
			<div class="form-group">
				<div class="cart-details-list">
					<div class="form-group">
						<ul>
							<li class="select-cart">
								<div class="checkbox">
									<label>
										<input name="form-field-checkbox" type="checkbox" class="ace list-check" />
										<span class="lbl"></span>
									</label>
								</div>
							</li>
							<li class="product-name">
								<div class="form-group product-all-details">
									<img src="{{ asset('assets/images/gallery/thumb-1.jpg') }}">
								</div>
							</li>
							<li class="unit-price">
								<div class="form-group">
									<span class="product-details-name">
										Chiffon Polka Dot Long Sleeve 
									</span><br><br>
									<div class="mobile-cart-desc">
										RM13.00 &nbsp;&nbsp; <strike>RM15.00</strike><br><br>
										<div class="form-group quantity-setting">
											<button class="btn btn-primary deduct-qty-button">
												<i class="fa fa-minus"></i>
											</button>
											<input type="text" class="form-control" name="quantity" value="1">
											<button class="btn btn-primary add-qty-button">
												<i class="fa fa-plus"></i>
											</button>
										</div>
									</div>
								</div>
							</li>
						</ul>
					</div>
					<div class="form-group">
						<ul>
							<li class="select-cart">
								<div class="checkbox">
									<label>
										<input name="form-field-checkbox" type="checkbox" class="ace list-check" />
										<span class="lbl"></span>
									</label>
								</div>
							</li>
							<li class="product-name">
								<div class="form-group product-all-details">
									<img src="{{ asset('assets/images/gallery/thumb-1.jpg') }}">
								</div>
							</li>
							<li class="unit-price">
								<div class="form-group">
									<span class="product-details-name">
										Chiffon Polka Dot Long Sleeve 
									</span><br><br>
									<div class="mobile-cart-desc">
										RM13.00 &nbsp;&nbsp; <strike>RM15.00</strike><br><br>
										<div class="form-group quantity-setting">
											<button class="btn btn-primary deduct-qty-button">
												<i class="fa fa-minus"></i>
											</button>
											<input type="text" class="form-control" name="quantity" value="1">
											<button class="btn btn-primary add-qty-button">
												<i class="fa fa-plus"></i>
											</button>
										</div>
									</div>
								</div>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>

		<div class="form-group">
			<div class="cart-checkout">
				<ul>
					<li class="select-cart">
						<div class="checkbox">
							<label>
								<input name="form-field-checkbox" type="checkbox" class="ace select-all-checkbox" />
								<span class="lbl"></span>
							</label>
						</div>
					</li>
					<li class="checkout-total" align="right">
						<div class="form-group">
							<span class="subtotal-title">Subtotal (2 Items): </span> <b class="total-amount">RM 26.00</b>
							<a href="{{ route('checkout') }}" class="btn btn-primary checkout-button">CHECK OUT</a>
						</div>
					</li>
				</ul>
			</div>
		</div>
		<hr>
		<div class="web-product-listing">
		    <div class="container">
		        <div class="row">
		            <div class="col-sm-12">
		            	<h3 align="center">YOU MAY ALSO LIKE</h3>
		                <div class="form-group">
		                    <div class="row">
		                        <div class="row">
			                        <div class="col-md-3">
			                            <div class="thumbnail product-thumbnail">
			                                <a href="{{ route('details') }}">
			                                    <div class="thumbnail-image" style="background-image: url('{{ asset('images/gallery/11594543_480x.jpg') }}');"></div>
			                                    <div class="caption">
			                                        <div class="product-name">
			                                            (BACKORDER) OCT SLIM BLACK SUIT
			                                        </div>
			                                        <div class="price">
			                                            <span class="currency">RM</span> 
			                                            <span class="amount">53.00</span>
			                                            <strike class="actual_price">
			                                                RM 73.90
			                                            </strike>
			                                        </div>

			                                        <div class="rateYo"></div> <div class="total-buyer">(3)</div>
			                                    </div>
			                                </a>
			                            </div>
			                        </div>
			                        <div class="col-md-3">
			                            <div class="thumbnail product-thumbnail">
			                                <a href="{{ route('details') }}">
			                                    <div class="thumbnail-image" style="background-image: url('{{ asset('images/gallery/11594584_480x.jpg') }}');"></div>
			                                    <div class="caption">
			                                        <div class="product-name">
			                                            (BACKORDER) OCT BABY PINK SET
			                                        </div>
			                                        <div class="price">
			                                            <span class="currency">RM</span> 
			                                            <span class="amount">53.00</span>
			                                            <strike class="actual_price">
			                                                RM 73.90
			                                            </strike>
			                                        </div>

			                                        <div class="rateYo"></div> <div class="total-buyer">(3)</div>
			                                    </div>
			                                </a>
			                            </div>
			                        </div>
			                        <div class="col-md-3">
			                            <div class="thumbnail product-thumbnail">
			                                <a href="{{ route('details') }}">
			                                    <div class="thumbnail-image" style="background-image: url('{{ asset('images/gallery/11643059_480x.jpg') }}');"></div>
			                                    <div class="caption">
			                                        <div class="product-name">
			                                            (WAITING LIST) OCT EMBROIDERY WHITE TOP
			                                        </div>
			                                        <div class="price">
			                                            <span class="currency">RM</span> 
			                                            <span class="amount">53.00</span>
			                                            <strike class="actual_price">
			                                                RM 73.90
			                                            </strike>
			                                        </div>

			                                        <div class="rateYo"></div> <div class="total-buyer">(3)</div>
			                                    </div>
			                                </a>
			                            </div>
			                        </div>
			                        <div class="col-md-3">
			                            <div class="thumbnail product-thumbnail">
			                                <a href="{{ route('details') }}">
			                                    <div class="thumbnail-image" style="background-image: url('{{ asset('images/gallery/11643087_480x.jpg') }}');"></div>
			                                    <div class="caption">
			                                        <div class="product-name">
			                                            OCT EMBROIDERY WORDING PANTS
			                                        </div>
			                                        <div class="price">
			                                            <span class="currency">RM</span> 
			                                            <span class="amount">53.00</span>
			                                            <strike class="actual_price">
			                                                RM 73.90
			                                            </strike>
			                                        </div>

			                                        <div class="rateYo"></div> <div class="total-buyer">(3)</div>
			                                    </div>
			                                </a>
			                            </div>
			                        </div>
			                        <div class="col-md-3">
			                            <div class="thumbnail product-thumbnail">
			                                <a href="{{ route('details') }}">
			                                    <div class="thumbnail-image" style="background-image: url('{{ asset('images/gallery/11643164_480x.jpg') }}');"></div>
			                                    <div class="caption">
			                                        <div class="product-name">
			                                            OCT BLUE BLOUSE OUTER
			                                        </div>
			                                        <div class="price">
			                                            <span class="currency">RM</span> 
			                                            <span class="amount">53.00</span>
			                                            <strike class="actual_price">
			                                                RM 73.90
			                                            </strike>
			                                        </div>

			                                        <div class="rateYo"></div> <div class="total-buyer">(3)</div>
			                                    </div>
			                                </a>
			                            </div>
			                        </div>
			                        <div class="col-md-3">
			                            <div class="thumbnail product-thumbnail">
			                                <a href="{{ route('details') }}">
			                                    <div class="thumbnail-image" style="background-image: url('{{ asset('images/gallery/k00733.jpg') }}');"></div>
			                                    <div class="caption">
			                                        <div class="product-name">
			                                            OCT EMBROIDERY WORDING PANTS
			                                        </div>
			                                        <div class="price">
			                                            <span class="currency">RM</span> 
			                                            <span class="amount">53.00</span>
			                                            <strike class="actual_price">
			                                                RM 73.90
			                                            </strike>
			                                        </div>

			                                        <div class="rateYo"></div> <div class="total-buyer">(3)</div>
			                                    </div>
			                                </a>
			                            </div>
			                        </div>
			                        <div class="col-md-3">
			                            <div class="thumbnail product-thumbnail">
			                                <a href="{{ route('details') }}">
			                                    <div class="thumbnail-image" style="background-image: url('{{ asset('images/gallery/k03136.jpg') }}');"></div>
			                                    <div class="caption">
			                                        <div class="product-name">
			                                            (BACKORDER) DEE KNIT POLKA DOT SKIRT IN BABY LILAC
			                                        </div>
			                                        <div class="price">
			                                            <span class="currency">RM</span> 
			                                            <span class="amount">53.00</span>
			                                            <strike class="actual_price">
			                                                RM 73.90
			                                            </strike>
			                                        </div>

			                                        <div class="rateYo"></div> <div class="total-buyer">(3)</div>
			                                    </div>
			                                </a>
			                            </div>
			                        </div>
			                        <div class="col-md-3">
			                            <div class="thumbnail product-thumbnail">
			                                <a href="{{ route('details') }}">
			                                    <div class="thumbnail-image" style="background-image: url('{{ asset('images/gallery/k04029.jpg') }}');"></div>
			                                    <div class="caption">
			                                        <div class="product-name">
			                                            (BACKORDER) DEE KOREA MICKEY PULLOVER IN GREY
			                                        </div>
			                                        <div class="price">
			                                            <span class="currency">RM</span> 
			                                            <span class="amount">53.00</span>
			                                            <strike class="actual_price">
			                                                RM 73.90
			                                            </strike>
			                                        </div>

			                                        <div class="rateYo"></div> <div class="total-buyer">(3)</div>
			                                    </div>
			                                </a>
			                            </div>
			                        </div>
			                        <div class="col-md-3">
			                            <div class="thumbnail product-thumbnail">
			                                <a href="{{ route('details') }}">
			                                    <div class="thumbnail-image" style="background-image: url('{{ asset('images/gallery/k06145.jpg') }}');"></div>
			                                    <div class="caption">
			                                        <div class="product-name">
			                                            (BACKORDER) DEE KOREA CONTRAST SHIRT IN WHITE
			                                        </div>
			                                        <div class="price">
			                                            <span class="currency">RM</span> 
			                                            <span class="amount">53.00</span>
			                                            <strike class="actual_price">
			                                                RM 73.90
			                                            </strike>
			                                        </div>

			                                        <div class="rateYo"></div> <div class="total-buyer">(3)</div>
			                                    </div>
			                                </a>
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

@section('js')
<script type="text/javascript">
	$('.select-all-checkbox').click( function(){
		$('.list-check').prop('checked', this.checked);
		$('.select-all-checkbox').prop('checked', this.checked);
	});

	$('.add-qty-button').click( function(){

		var quantity = $(this).parent().find('input[name="quantity"]').val();
		quantity = Number(quantity) + 1;
		$(this).parent().find('input[name="quantity"]').val(quantity);
	});

	$('.deduct-qty-button').click( function(){
		var quantity = $(this).parent().find('input[name="quantity"]').val();
		if(quantity != 1){
			quantity = Number(quantity) - 1;
			$(this).parent().find('input[name="quantity"]').val(quantity);
		}
	});
</script>
@endsection