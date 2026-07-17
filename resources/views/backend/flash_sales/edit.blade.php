@extends('layouts.admin_app')
@section('css')
	<style type="text/css">
		.individual-product {
			margin-bottom: 1em;
		}
	</style>
@endsection
@section('content')
	@if ($errors->any())
		<div class="alert alert-danger">{!! implode('<br/>', $errors->all(':message')) !!}</div>
	@endif
	<form method="POST" action="{{ route('flash_sale.flash_sales.update', $flash_sale->id) }}" id="flash-sale-form"
		enctype="multipart/form-data">
		@csrf
		@method('PUT')
		@include('backend.flash_sales.form')
	</form>

	<div class="submit-form-btn">
		<div class="form-group wizard-actions" align="right">
			<a href="{{ route('flash_sale.flash_sales.index') }}" class="btn btn-outline-danger">
				<i class="fa fa-ban"> {{ isset($data['backendlang']['backendlang']['Cancel']) ? $data['backendlang']['backendlang']['Cancel'] :'' }}</i>
			</a>

			<button class="btn btn-outline-primary">
				<i class="fa fa-check"> {{ isset($data['backendlang']['backendlang']['Save_Changes']) ? $data['backendlang']['backendlang']['Save_Changes'] :'' }}</i>
			</button>

		</div>
	</div>

	<div class="modal fade text-left" id="add_flash_product" tabindex="-1" role="dialog" aria-labelledby="myModalLabel160"
		aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
			<div class="modal-content">
				<div class="modal-header bg-primary">
					<h5 class="modal-title white" id="myModalLabel160">
						{{ isset($data['backendlang']['backendlang']['Choose_Addon_Product']) ? $data['backendlang']['backendlang']['Choose_Addon_Product'] :'' }}
					</h5>
					<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
						<i data-feather="x"></i>
					</button>
				</div>
				<div class="modal-body">
					<div class="table-responsive">
						<table class="table table-bordered">
							<tr>
								<th width="50">
									<input type="checkbox" name="check_all_sub" id="check_all_sub">
								</th>
								<th>{{ isset($data['backendlang']['backendlang']['product']) ? $data['backendlang']['backendlang']['product'] :'' }}</th>

							</tr>
							<tbody class="sub_product_listing"></tbody>
						</table>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
						<span>{{ isset($data['backendlang']['backendlang']['Close']) ? $data['backendlang']['backendlang']['Close'] :'' }}</span>
					</button>
					<button type="button" class="btn btn-primary save-password ms-1" data-bs-dismiss="modal">
						<span class="sub_items_save">{{ isset($data['backendlang']['backendlang']['Save_Changes']) ? $data['backendlang']['backendlang']['Save_Changes'] :'' }}</span>
					</button>
				</div>
			</div>
		</div>
	</div>
	<!-- <div class="modal fade" tabindex="-1" role="dialog" id="add_flash_product">
		 <div class="modal-dialog modal-lg" role="document">
		  <div class="modal-content">
		  <div class="modal-header">
		   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		   <span aria-hidden="true">&times;</span>
		   </button>
		   <h5 class="modal-title">Choose Add-on Product</h5>

		  </div>
		  <div class="modal-body">
			<div class="table-responsive">
			 <table class="table table-bordered">
			  <tr>
			   <th width="50">
				<input type="checkbox" name="check_all_sub" id="check_all_sub">
			   </th>
			   <th>Product</th>
			 
			  </tr>
			  <tbody class="sub_product_listing"></tbody>
			 </table>
			</div>
		  </div>
		  <div class="modal-footer">
		   <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
		   <button type="button" class="btn btn-primary sub_items_save">Save changes</button>
		  </div>
		  </div>
		 </div>
		 </div> -->
@endsection

@section('js')
	<script type="text/javascript">
		$('.submit-form-btn .btn-outline-primary').click(function(e) {
			e.preventDefault();

			$('#flash-sale-form').submit();
		});

		$('.add_on_product_btn').click(function(e) {
			$('.loading-gif').show();
			e.preventDefault();

			$.ajax({
				type: 'get',
				url: '{{ route('flash_sale_product_listing') }}',
				success: function(data) {
					$('.sub_product_listing').html(data);
					// $('#add_flash_product').modal('show');
					$('.loading-gif').hide();
				}
			});

		});

		$(document).on('click', '.sub_items_save', function() {
			var product_arr = [];
			var flash_sale_id = "{{ !empty($flash_sale->id) ? $flash_sale->id : '' }}";
			var variation_arr = [];
			var second_variations_arr = [];
			var item_variation = [];
			$('.check_sub_items:checked').each(function() {
				product_arr.push($(this).data('id'));
			});



			$('.second_variation_id:checked').each(function() {
				second_variations_arr.push($(this).data('id'));

				variation_arr.push($(this).attr('id'));
			});


			$('.check_product_variation:checked').each(function() {
				item_variation.push($(this).data('id'));
			});


			if (product_arr <= 0) {
				alert('{{ isset($data['backendlang']['backendlang']['Please_Select_Products']) ? $data['backendlang']['backendlang']['Please_Select_Products'] :'' }}');
				return false;
			} else {
				if (confirm("{{ isset($data['backendlang']['backendlang']['Are_You_Sure_You_Want_To_Add_Those_Items']) ? $data['backendlang']['backendlang']['Are_You_Sure_You_Want_To_Add_Those_Items'] :'' }}") == true) {
					$('.loading-gif').show();
					var product = product_arr.join(',');
					var variation = variation_arr.join(',');
					var second_variation = second_variations_arr.join(',');
					var item_variation = item_variation.join(',');


					$.ajax({
						type: 'post',
						url: '{{ route('save_flash_product') }}',
						data: {
							product: product,
							variation: variation,
							second_variation: second_variation,
							item_variation: item_variation,
							flash_sale_id: flash_sale_id
						},
						success: function(data) {

							if (data.status == 1) {
								location.reload();
								$.ajax({
									type: 'get',
									url: '{{ route('display_flash_products') }}',
									data: {
										flash_sale_id: data.flash_sale_id
									},
									success: function(data) {
										$('.loading-gif').hide();
										// $('#add_flash_product').modal('hide');
										$('#add_flash_product').hide();
										$('.modal-backdrop').hide();
										$('body').removeClass('modal-open');
										$('#display_deal_sub_items').html(data);
										$('#batch_settings').show();
									}
								});

							} else if (data.status == '97') {
								toastr.error('' + data.p_name + ' under stock ');
								$('.loading-gif').hide();
								return false;
							}
							console.log(data);
						}
					});
				}
			}
		});

		$(document).on('click', '.remove_sub_item', function(e) {
			e.preventDefault();

			var ele = $(this);

			var fd = new FormData();
			fd.append('flash_sale_product_id', ele.data('id'));
			fd.append('status', '3');

			if (confirm("{{ isset($data['backendlang']['backendlang']['Confirm_Deleting_Product']) ? $data['backendlang']['backendlang']['Confirm_Deleting_Product'] :'' }}")) {
				$.ajax({
					type: 'POST',
					contentType: false,
					processData: false,
					url: '{{ route('change_flash_sale_product_status') }}',
					data: fd,
					success: function(response) {
						if (response == "ok") {
							toastr.success("{{ isset($data['backendlang']['backendlang']['Update_Successful']) ? $data['backendlang']['backendlang']['Update_Successful'] :'' }}");
							location.reload();
						} else {
							toastr.error(response);
						}
					}
				});
			}
		});

		$('#update_selected').click(function() {
			var sub_items_arr = [];
			var discount = $('#add_on_discount').val();
			var price = $('#price').val();
			$('.sub_item_check:checked').each(function() {
				sub_items_arr.push($(this).data('id'));
			});

			if (sub_items_arr <= 0) {
				alert("{{ isset($data['backendlang']['backendlang']['Please_Select_Records']) ? $data['backendlang']['backendlang']['Please_Select_Records'] :'' }}");
				return false;
			} else {
				if (confirm("{{ isset($data['backendlang']['backendlang']['Are_You_Sure_Want_To_Update_The_Selected_Product']) ? $data['backendlang']['backendlang']['Are_You_Sure_Want_To_Update_The_Selected_Product'] :'' }}") == true) {
					$('.loading-gif').show();
					var product = sub_items_arr.join(',');
					$.ajax({
						type: 'post',
						url: '{{ route('update_selected_flash_sale_product') }}',
						data: {
							product: product,
							discount: discount,
							price: price
						},
						success: function(data) {
							if (data == 1) {
								$('.loading-gif').hide();
								location.reload();
							} else {
								toastr.error(data);
								return false;
							}
							// console.log(data);
						}
					});
				}
			}
		});

		$('#update_all').on('click', function() {
			$('.loading-gif').show();
			var discount = $('#add_on_discount').val();
			var price = $('#price').val();
			var flash_sale_id = "{{ !empty($flash_sale->id) ? $flash_sale->id : '' }}";

			$.ajax({
				type: 'post',
				url: '{{ route('update_all_flash_sale_product') }}',
				data: {
					discount: discount,
					price: price,
					flash_sale_id: flash_sale_id
				},
				success: function(data) {
					if (data.status == 1) {
						$('.loading-gif').hide();
						// for(i=0; i<=data.batch_count; i++){
						//     $('#add_on_discount_'+i+'').val(data.batch_discount);
						//     $('#purchase_limits_'+i+'').val(data.batch_purchase_limit);
						//     var price = $('#hidden_price_'+i+'').val();
						//     var cal = price - (price * (data.batch_discount / 100));
						//     $('#add_on_price_'+i+'').val(cal);
						// }
						location.reload();
					}
				}
			});
		});

		$('input[name="qty"]').change(function(e) {
			e.preventDefault();
			var ele = $(this);

			var product_detail_id = ele.closest('td').find('input[name="flash_sale_product_detail_id"]').val();

			var fd = new FormData();
			fd.append('id', product_detail_id);
			fd.append('qty', ele.val());

			$.ajax({
				url: '{{ route('update_flash_product_details') }}',
				type: 'post',
				data: fd,
				contentType: false,
				processData: false,
				success: function(response) {
					$('.loading-gif').hide();
					if (response == '1') {
						toastr.success("{{ isset($data['backendlang']['backendlang']['Update_Successful']) ? $data['backendlang']['backendlang']['Update_Successful'] :'' }}");
					} else {
						toastr.error(response);
					}
					location.reload();
				},
			});
		});

		$('.date-timepicker1').click(function(e) {
			var ele = $(this);

			console.log('123');

			ele.closest('.input-group').find('.dropdown-menu .list-unstyled .collapse.in').addClass('show');
			ele.closest('.input-group').find('.dropdown-menu .list-unstyled .collapse').not('.in').addClass('show');
			ele.closest('.input-group').find('.dropdown-menu .list-unstyled .picker-switch.accordion-toggle').css(
				'display', 'none');
		});

		$('.check_all_flash_sale_products').click(function() {
			$('.sub_item_check').prop('checked', this.checked);
		});

		const myDatePicker = flatpickr("#start_date", {
			defaultDate: "{{ $flash_sale->start }}",
			enableTime: true,
		});

		const myDatePicker2 = flatpickr("#end_date", {
			defaultDate: "{{ $flash_sale->end }}",
			enableTime: true,
		});

		$(document).on('click', '.second_variation_id, .check_product_variation', function(e) {
			var ele = $(this);

			if (ele.is(':checked') && !ele.closest('.add_on_listing').find('.check_sub_items').is(':checked')) {
				ele.closest('.add_on_listing').find('.check_sub_items').prop('checked', true);
			} else if (!ele.is(':checked') && ele.closest('.add_on_listing').find('.check_sub_items').is(':checked')) {
				ele.closest('.add_on_listing').find('.check_sub_items').prop('checked', false);
			}
		});
	</script>
@endsection
