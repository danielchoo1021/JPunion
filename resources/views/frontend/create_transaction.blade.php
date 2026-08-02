@extends('layouts.app')

@section('content')
@include('partial.frontend.profile_header')

<style>
	.ct-section {
		background: #fff;
		border: 1px solid #e6e6e6;
		border-radius: 8px;
		padding: 16px;
		margin-bottom: 16px;
	}
	.ct-section-title {
		font-size: 15px;
		font-weight: 700;
		margin-bottom: 14px;
		padding-bottom: 10px;
		border-bottom: 2px solid #f0f0f0;
	}
	.ct-item-card {
		border: 1px solid #e0e0e0;
		border-radius: 6px;
		padding: 12px;
		background: #fafafa;
	}
	.ct-item-card + .ct-item-card {
		margin-top: 12px;
	}
	.ct-item-card-header {
		display: flex;
		justify-content: space-between;
		align-items: center;
		margin-bottom: 10px;
	}
	.ct-item-number {
		font-weight: 600;
		color: #555;
		font-size: 13px;
		text-transform: uppercase;
		letter-spacing: .03em;
	}
	.ct-remove-row-btn {
		color: #dc3545;
		background: none;
		border: none;
		padding: 0;
		font-size: 13px;
	}
	.ct-item-card .row {
		align-items: flex-start;
	}
	.ct-item-price {
		background: #fff;
		border: 1px solid #e0e0e0;
		border-radius: 4px;
		padding: 6px 10px;
	}
	.ct-item-price .line-total {
		font-size: 16px;
		font-weight: 700;
		color: #1a1a1a;
	}
	.ct-item-price .unit-price {
		font-size: 12px;
		color: #888;
	}
	.ct-field-label {
		font-size: 12px;
		color: #888;
		margin-bottom: 4px;
		display: block;
	}
</style>

<form method="POST" action="{{ route('agentSaveTransaction') }}" id="transaction-form" enctype="multipart/form-data">
@csrf

<div class="profile-content pb-3">
	<div class="container">
		<h5 class="mb-3">Create Order For Customer</h5>

		@if($errors->any())
		  <div class="alert alert-danger">{!! implode('<br/>', $errors->all(':message')) !!}</div>
		@endif

		@if($downlines->isEmpty())
		<div class="alert alert-warning">You don't have any customers recruited under you yet.</div>
		@else

		<div class="ct-section">
			<div class="ct-section-title">Customer</div>
			<select class="select2 form-control" name="customer_code" required>
				<option value="">Select Customer</option>
				@foreach($downlines as $downline)
				<option value="{{ $downline->code }}" {{ old('customer_code') == $downline->code ? 'selected' : '' }}>
					{{ $downline->f_name }} {{ $downline->l_name }} ({{ $downline->display_code }}{{ $downline->display_running_no }})
				</option>
				@endforeach
			</select>
		</div>

		<div class="ct-section big-parent">
			<div class="ct-section-title">Order Items</div>

			<div class="child-div">
				<div class="ct-item-card child-row">
					<div class="ct-item-card-header">
						<span class="ct-item-number">Item No. 1</span>
						<button type="button" class="ct-remove-row-btn" style="display:none;">
							<i class="fa fa-trash"></i> Remove
						</button>
					</div>
					<div class="row">
						<div class="col-12 col-md-6">
							<span class="ct-field-label">Product</span>
							<select class="form-control products select2" name="product_id[]">
								<option value="">Select Product</option>
								@foreach($products as $product)
								<option value="{{ $product->id }}">{{ $product->product_name }}</option>
								@endforeach
							</select>
							<div class="product_variation mt-2"></div>
						</div>
						<div class="col-6 col-md-3 mt-2 mt-md-0">
							<span class="ct-field-label">Quantity</span>
							<input type="number" min="1" name="quantity[]" value="" class="form-control" placeholder="Qty">
						</div>
						<div class="col-6 col-md-3 mt-2 mt-md-0">
							<span class="ct-field-label">Price</span>
							<div class="ct-item-price">
								<div class="line-total">-</div>
								<div class="unit-price"></div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="text-center mt-3">
				<button type="button" class="add-row-btn btn btn-outline-secondary" style="background:#f8f9fa; color:#333; border-color:#ccc;">
					<i class="fa fa-plus"></i> Add another item
				</button>
			</div>

			<div class="preview-error text-danger mt-2" style="display:none;"></div>

			<div class="order-summary" style="display:none; background:#f8f9fa; border-radius:6px; padding:12px 15px; margin-top:14px;">
				<div class="row">
					<div class="col-6">Subtotal</div>
					<div class="col-6 text-right summary-sub-total">RM 0.00</div>
				</div>
				<div class="row summary-shipping-row">
					<div class="col-6">Shipping Fee</div>
					<div class="col-6 text-right summary-shipping-fee">RM 0.00</div>
				</div>
				<div class="row">
					<div class="col-6"><b>Total</b></div>
					<div class="col-6 text-right"><b class="summary-grand-total">RM 0.00</b></div>
				</div>
				<small class="summary-no-address-note text-muted" style="display:none;">Shipping fee will be calculated once the customer's delivery address is known.</small>
			</div>
		</div>

		<div class="ct-section">
			<div class="ct-section-title">Payment</div>
			<label class="d-block mb-2">
				<input type="radio" name="payment_method" value="bank_slip" {{ old('payment_method', 'bank_slip') == 'bank_slip' ? 'checked' : '' }}>
				Upload bank transfer slip (order goes to Waiting Verification)
			</label>
			<label class="d-block">
				<input type="radio" name="payment_method" value="processed" {{ old('payment_method') == 'processed' ? 'checked' : '' }}>
				Already processed / collected offline (order marked as Paid immediately)
			</label>

			<div class="bank-slip-field mt-3">
				<span class="ct-field-label">Bank Slip</span>
				<input type="file" class="form-control" name="bank_slip">
			</div>
		</div>

		<div class="ct-section">
			<a href="#" class="address-toggle">
				<small>Customer has no delivery address on file yet? Click to add one <i class="fa fa-angle-down"></i></small>
			</a>
			<div class="address-fields" style="display: none;">
				<div class="row mt-2">
					<div class="col-md-6">
						<input type="text" name="f_name" value="{{ old('f_name') }}" class="form-control mb-2" placeholder="Recipient Name">
					</div>
					<div class="col-md-6">
						<input type="text" name="phone" value="{{ old('phone') }}" class="form-control mb-2" placeholder="Phone">
					</div>
					<div class="col-md-12">
						<input type="text" name="address" value="{{ old('address') }}" class="form-control mb-2" placeholder="Address">
					</div>
					<div class="col-md-4">
						<input type="text" name="city" value="{{ old('city') }}" class="form-control mb-2" placeholder="City">
					</div>
					<div class="col-md-4">
						<input type="text" name="postcode" value="{{ old('postcode') }}" class="form-control mb-2" placeholder="Postcode">
					</div>
					<div class="col-md-4">
						<select name="state" class="form-control mb-2">
							<option value="">Select State</option>
							@foreach(\App\State::get() as $state)
							<option value="{{ $state->id }}" {{ old('state') == $state->id ? 'selected' : '' }}>{{ $state->name }}</option>
							@endforeach
						</select>
					</div>
				</div>
			</div>
		</div>

		<button class="btn btn-primary btn-block" type="submit">Submit</button>
		@endif
	</div>
</div>
</form>
@endsection

@section('js')
<script type="text/javascript">
	$('.select2').select2();

	$('.bank-slip-field').toggle($('input[name="payment_method"]:checked').val() == 'bank_slip');
	$(document).on('change', 'input[name="payment_method"]', function(){
		$('.bank-slip-field').toggle($(this).val() == 'bank_slip');
	});

	$('.address-toggle').click(function(e){
		e.preventDefault();
		$('.address-fields').slideToggle();
	});

	function renumberItems(){
		var rows = $('.big-parent .child-row');
		rows.each(function(i){
			$(this).find('.ct-item-number').text('Item No. ' + (i + 1));
		});
		rows.find('.ct-remove-row-btn').toggle(rows.length > 1);
	}

	function addProductRow(){
		var row = $('<div class="ct-item-card child-row">\
						<div class="ct-item-card-header">\
							<span class="ct-item-number">Item</span>\
							<button type="button" class="ct-remove-row-btn">\
								<i class="fa fa-trash"></i> Remove\
							</button>\
						</div>\
						<div class="row">\
							<div class="col-12 col-md-6">\
								<span class="ct-field-label">Product</span>\
								<select class="form-control products select2" name="product_id[]">\
									<option value="">Select Product</option>\
									@foreach($products as $product)\
									<option value="{{ $product->id }}">{{ $product->product_name }}</option>\
									@endforeach\
								</select>\
								<div class="product_variation mt-2"></div>\
							</div>\
							<div class="col-6 col-md-3 mt-2 mt-md-0">\
								<span class="ct-field-label">Quantity</span>\
								<input type="number" min="1" name="quantity[]" value="" class="form-control" placeholder="Qty">\
							</div>\
							<div class="col-6 col-md-3 mt-2 mt-md-0">\
								<span class="ct-field-label">Price</span>\
								<div class="ct-item-price">\
									<div class="line-total">-</div>\
									<div class="unit-price"></div>\
								</div>\
							</div>\
						</div>\
					</div>');

		$('.big-parent .child-div').append(row);
		row.find('.select2').select2();
		renumberItems();
	}

	$('.add-row-btn').click(function(e){
		e.preventDefault();
		addProductRow();
	});

	$('.big-parent').on('click', '.ct-remove-row-btn', function(e){
		e.preventDefault();
		$(this).closest('.child-row').remove();
		renumberItems();
		refreshPreview();
	});

	renumberItems();

	var previewTimer = null;
	function refreshPreview(){
		clearTimeout(previewTimer);
		previewTimer = setTimeout(doRefreshPreview, 300);
	}

	function doRefreshPreview(){
		var customerCode = $('select[name="customer_code"]').val();

		var data = {
			_token: $('input[name="_token"]').val(),
			customer_code: customerCode,
			product_id: [],
			variation_id: [],
			second_variation_id: [],
			quantity: []
		};

		$('.big-parent .child-row').each(function(){
			var row = $(this);
			data.product_id.push(row.find('.products').val() || '');
			data.variation_id.push(row.find('[name="variation_id[]"]').val() || '');
			data.second_variation_id.push(row.find('[name="second_variation_id[]"]').val() || '');
			data.quantity.push(row.find('[name="quantity[]"]').val() || '');
		});

		$('.big-parent .line-total').text('-');
		$('.big-parent .unit-price').text('');
		$('.preview-error').hide();

		if(!customerCode){
			$('.order-summary').hide();
			return;
		}

		$.ajax({
			url: '{{ route("agentPreviewTransaction") }}',
			type: 'post',
			data: data,
			success: function(response){
				if(response.error){
					$('.preview-error').text(response.error).show();
					$('.order-summary').hide();
					return;
				}

				if(!response.lines || response.lines.length == 0){
					$('.order-summary').hide();
					return;
				}

				response.lines.forEach(function(line){
					var row = $('.big-parent .child-row').eq(line.key);
					row.find('.line-total').text('RM ' + line.line_total);
					row.find('.unit-price').text('RM ' + line.unit_price + ' each');
				});

				$('.summary-sub-total').text('RM ' + response.sub_total);

				if(response.shipping_fee !== null){
					$('.summary-shipping-row').show();
					$('.summary-shipping-fee').text('RM ' + response.shipping_fee);
					$('.summary-grand-total').text('RM ' + response.grand_total);
					$('.summary-no-address-note').hide();
				}else{
					$('.summary-shipping-row').hide();
					$('.summary-grand-total').text('RM ' + response.sub_total + ' + shipping');
					$('.summary-no-address-note').show();
				}

				$('.order-summary').show();
			}
		});
	}

	$(document).on('change', 'select[name="customer_code"]', refreshPreview);
	$(document).on('input change', '.big-parent [name="quantity[]"]', refreshPreview);

	$('.big-parent').on('change', '.products', function(){
		var ele = $(this);
		var row = ele.closest('.child-row');
		var pid = ele.val();

		row.find('.product_variation').empty();

		if(!pid){
			refreshPreview();
			return;
		}

		$.ajax({
			url: '{{ route("agentGetTransactionVariation") }}',
			type: 'post',
			data: { pid: pid },
			success: function(response){
				if(response[0] == '1'){
					row.find('.product_variation').html(response[1]);
				}
				refreshPreview();
			},
		});
	});

	$('.big-parent').on('change', '.product_variation_option', function(){
		var ele = $(this);
		var row = ele.closest('.child-row');
		var vid = ele.val();

		// Give this row's variation select a name the server expects.
		ele.attr('name', 'variation_id[]');

		$.ajax({
			url: '{{ route("agentGetVariationStock") }}',
			type: 'post',
			data: { vid: vid },
			success: function(response){
				if(response[0] == '1'){
					row.find('.product_second_variation_option').closest('div').remove();
					row.append(response[1]);
				}
				refreshPreview();
			},
		});
	});

	$('.big-parent').on('change', '.product_second_variation_option', function(){
		$(this).attr('name', 'second_variation_id[]');
		refreshPreview();
	});
</script>
@endsection
