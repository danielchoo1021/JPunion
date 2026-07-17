@extends('layouts.app')

@section('content')
<div class="container">
	<div class="from-group">
		<div class="row">
			<div class="col-md-12">
				<ol class="breadcrumb">
				  <li><a href="#">Home</a></li>
				  <li><a href="#">Shopping Cart</a></li>
				  <li class="active">Checkout</li>
				</ol>
			</div>
		</div>
	</div>
	<div class="form-group">
		<div class="row">
			<div class="col-md-6">
				<div class="billing-details">
					<div class="form-group">
						<h4>Shipping Address</h4>
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-sm-6">
								<input type="text" class="form-control" placeholder="First Name">
							</div>
							<div class="col-sm-6">
								<input type="text" class="form-control" placeholder="Last Name">
							</div>
						</div>
					</div>
					<div class="form-group">
						<input type="text" class="form-control" placeholder="Email Address">
					</div>
					<div class="form-group">
						<input type="text" class="form-control" placeholder="Phone">
					</div>
					<div class="form-group">
						<textarea class="form-control" placeholder="Address"></textarea>
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-sm-6">
								<select class="form-control">
									<option value="">Country</option>
								</select>
							</div>
							<div class="col-sm-6">
								<select class="form-control">
									<option value="">State</option>
								</select>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-sm-6">
								<input type='text' class="form-control" placeholder="City">
							</div>
							<div class="col-sm-6">
								<input type='text' class="form-control" placeholder="Zip Code">
							</div>
						</div>
					</div>
					<div class="form-group">
						<textarea class="form-control" placeholder="Remark"></textarea>
					</div>

					<div class="form-group">
						<button class="btn btn-primary btn-block"> PLACE ORDER NOW </button>
					</div>
				</div>
			</div>

			<div class="col-md-6">
				<div class="total-charges-list">
					<table class="table">
						<thead>
							<tr>
								<th>Product</th>
								<th>Quantity</th>
								<th>Unit Price</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									<img src="{{ asset('assets/images/gallery/thumb-1.jpg') }}">
									<div class="product-name">
										Chiffon Polka Dot Long Sleeve Loose Tops Blouse Casual Shirt
									</div>
								</td>
								<td>1</td>
								<td>RM13.00</td>
							</tr>
							<tr>
								<td>
									<img src="{{ asset('assets/images/gallery/thumb-1.jpg') }}">
									<div class="product-name">
										Chiffon Polka Dot Long Sleeve Loose Tops Blouse Casual Shirt
									</div>
								</td>
								<td>1</td>
								<td>RM13.00</td>
							</tr>
							<tr>
								<td colspan="2">Subtotal</td>
								<td>RM 26.00</td>
							</tr>
							<tr>
								<td colspan="2">Total</td>
								<td>RM 26.00</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection