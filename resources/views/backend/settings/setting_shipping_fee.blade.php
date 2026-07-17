@extends('layouts.admin_app')
@section('content')
<style>

.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

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

.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}

.toggle-container {
  text-align: center;
  margin-bottom: 30px;
}

.toggle-container .toggle-labels {
  display: flex;
  justify-content: left;
  gap: 20px;
}

.toggle-container .switch {
  margin: 0 15px;
}

.free-shipping-section {
  background: #ffffffff;
  padding: 20px;
  border-radius: 8px;
  margin-bottom: 20px;
}

.column-header {
  font-weight: bold;
  margin-bottom: 10px;
}
</style>

<form method="POST" action="{{ route('save_setting_shipping_fee') }}" id="setting-merchant-form">
@csrf

<div class="toggle-container">
	<div class="toggle-labels">
		<span style="font-size: 20px; color: #000;">{{ isset($data['backendlang']['backendlang']['By_Weight']) ? $data['backendlang']['backendlang']['By_Weight'] :'' }}</span>
		<label class="switch">
			<input type="checkbox" name="type_set_shipping_fee" id="shipping-toggle" {{ (!empty($website_setting->id) && $website_setting->type_set_shipping_fee == 2) ? 'checked' : '' }}>
			<span class="slider round"></span>
		</label>
		<span style="font-size: 20px; color: #000;">{{ isset($data['backendlang']['backendlang']['By_Amount']) ? $data['backendlang']['backendlang']['By_Amount'] :'' }}</span>
	</div>
</div>

<div class="free-shipping-section">
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<label id="threshold-label">{{ isset($data['backendlang']['backendlang']['Free_Shipping_Above_Certain_Price']) ? $data['backendlang']['backendlang']['Free_Shipping_Above_Certain_Price'] :'' }} (MYR)</label>
				<input type="text" name="free_shipping_threshold" id="free-shipping-threshold" class="form-control" 
					value="{{ (!empty($website_setting->id)) ? $website_setting->free_shipping_threshold : '' }}" 
					placeholder="{{ isset($data['backendlang']['backendlang']['Enter_price_for_free_shipping']) ? $data['backendlang']['backendlang']['Enter_price_for_free_shipping'] :'' }}">
			</div>
		</div>
	</div>
</div>

<div class="shipping-content">
	<div class="row">
		<div class="col-sm-6">
			<h3>{{ isset($data['backendlang']['backendlang']['West_Malaysia_Freight']) ? $data['backendlang']['backendlang']['West_Malaysia_Freight'] :'' }}</h3>
			<hr>
			<div class="form-group">
				<div class="row">
					<div class="col-5">
						<div class="column-header" id="west-col1-header">{{ isset($data['backendlang']['backendlang']['Weight_(kg)']) ? $data['backendlang']['backendlang']['Weight_(kg)'] :'' }}</div>
					</div>
					<div class="col-5">
						<div class="column-header">{{ isset($data['backendlang']['backendlang']['Shipping_amount']) ? $data['backendlang']['backendlang']['Shipping_amount'] :'' }} (MYR)</div>
					</div>
					<div class="col-2">
						<div class="column-header">{{ isset($data['backendlang']['backendlang']['Action']) ? $data['backendlang']['backendlang']['Action'] :'' }}</div>
					</div>
				</div>
			</div>
			<hr>
			<div class="west row-parent">
				@if(!$settingShippingFees->isEmpty())
				@foreach($settingShippingFees as $settingShippingFee)
					@if($settingShippingFee->area == 'west')
						<div class="form-group">
							<input type="hidden" class="sid" name="sid[]" class="sid" value="{{ $settingShippingFee->id }}">
							<input type="hidden" name="type[]" value="west">
							<input type="hidden" name="country_id[]" value="160">
							<input type="hidden" name="ship_type[]" value="">
							<div class="row">
								<div class="col-5">
									<input type="text" name="weight[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['Weight_(kg)']) ? $data['backendlang']['backendlang']['Weight_(kg)'] :'' }}" value="{{ $settingShippingFee->weight }}">
								</div>
								<div class="col-5">
									<input type="text" name="shipping_fee[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['Shipping_amount']) ? $data['backendlang']['backendlang']['Shipping_amount'] :'' }}" value="{{ $settingShippingFee->shipping_fee }}">
								</div>
								<div class="col-2" align="center">
									<a href="#"  class="important-text del">
										<i class="bi bi-trash fa-2x"></i>
									</a>
								</div>
							</div>
						</div>
					@endif
				@endforeach
				@else
					<div class="form-group">
						<input type="hidden" name="sid[]" class="sid" value="">
						<input type="hidden" name="type[]" value="west">
						<input type="hidden" name="country_id[]" value="160">
						<input type="hidden" name="ship_type[]" value="">
						<div class="row">
							<div class="col-5">
								<input type="text" name="weight[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['Weight_(kg)']) ? $data['backendlang']['backendlang']['Weight_(kg)'] :'' }}">
							</div>
							<div class="col-5">
								<input type="text" name="shipping_fee[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['Shipping_amount']) ? $data['backendlang']['backendlang']['Shipping_amount'] :'' }}">
							</div>
							<div class="col-2" align="center">
								<a href="#"  class="important-text del">
									<i class="bi bi-trash fa-2x"></i>
								</a>
							</div>
						</div>
					</div>
				@endif
			</div>
			<br>
			<div class="form-group">
				<div class="row">
					<div class="col-md-12" align="center">
						<a href="#" class="add-shipping-btn btn btn-primary btn-sm" id="add-west">
							<i class="bi bi-plus"></i>
						</a>
					</div>
				</div>
			</div>
		</div>

		<div class="col-sm-6">
			<h3>{{ isset($data['backendlang']['backendlang']['East_Malaysia_Freight']) ? $data['backendlang']['backendlang']['East_Malaysia_Freight'] :'' }}</h3>
			<hr>
			<div class="form-group">
				<div class="row">
					<div class="col-5">
						<div class="column-header" id="east-col1-header">{{ isset($data['backendlang']['backendlang']['Weight_(kg)']) ? $data['backendlang']['backendlang']['Weight_(kg)'] :'' }}</div>
					</div>
					<div class="col-5">
						<div class="column-header">{{ isset($data['backendlang']['backendlang']['Shipping_amount']) ? $data['backendlang']['backendlang']['Shipping_amount'] :'' }} (MYR)</div>
					</div>
					<div class="col-2">
						<div class="column-header">{{ isset($data['backendlang']['backendlang']['Action']) ? $data['backendlang']['backendlang']['Action'] :'' }}</div>
					</div>
				</div>
			</div>
			<hr>
			<div class="east row-parent">
				@if(!$settingShippingFees->isEmpty())
					@foreach($settingShippingFees as $settingShippingFee)
						@if($settingShippingFee->area == 'east')
							<div class="form-group">
								<input type="hidden" name="sid[]" class="sid" value="{{ $settingShippingFee->id }}">
								<input type="hidden" name="type[]" value="east">
								<input type="hidden" name="country_id[]" value="160">
								<input type="hidden" name="ship_type[]" value="1">
								<div class="row">
									<div class="col-5">
										<input type="text" name="weight[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['Weight_(kg)']) ? $data['backendlang']['backendlang']['Weight_(kg)'] :'' }}" value="{{ $settingShippingFee->weight }}">
									</div>
									<div class="col-5">
										<input type="text" name="shipping_fee[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['Shipping_amount']) ? $data['backendlang']['backendlang']['Shipping_amount'] :'' }}" value="{{ $settingShippingFee->shipping_fee }}">
									</div>
									<div class="col-2" align="center">
										<a href="#" class="important-text del">
											<i class="bi bi-trash fa-2x"></i>
										</a>
									</div>
								</div>
							</div>
						@endif
					@endforeach
				@else
					<div class="form-group">
						<div class="row">
							<input type="hidden" name="sid[]" class="sid" value="">
							<input type="hidden" name="country_id[]" value="160">
							<input type="hidden" name="type[]" value="east">
							<input type="hidden" name="ship_type[]" value="1">
							<div class="col-5">
								<input type="text" name="weight[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['Weight_(kg)']) ? $data['backendlang']['backendlang']['Weight_(kg)'] :'' }}">
							</div>
							<div class="col-5">
								<input type="text" name="shipping_fee[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['Shipping_amount']) ? $data['backendlang']['backendlang']['Shipping_amount'] :'' }}">
							</div>
							<div class="col-2" align="center">
								<a href="#" class="important-text del">
									<i class="bi bi-trash fa-2x"></i>
								</a>
							</div>
						</div>
					</div>
				@endif
			</div>
			<br>
			<div class="form-group">
				<div class="row">
					<div class="col-md-12" align="center">
						<a href="#" class="add-shipping-btn btn btn-primary btn-sm" id="add-east">
							<i class="bi bi-plus"></i>
						</a>
					</div>
				</div>
			</div>
		</div>
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
<script type="text/javascript">
	$(document).ready(function() {
		if ($('#shipping-toggle').is(':checked')) {
			$('#threshold-label').text('{{ isset($data['backendlang']['backendlang']['Free_Shipping_Above_Certain_Price']) ? $data['backendlang']['backendlang']['Free_Shipping_Above_Certain_Price'] : 'Free Shipping Above Certain Price' }} (MYR)');
			$('#free-shipping-threshold').attr('placeholder', '{{ isset($data['backendlang']['backendlang']['Enter_price_for_free_shipping']) ? $data['backendlang']['backendlang']['Enter_price_for_free_shipping'] : 'Enter Price for free shipping' }}');
			$('#west-col1-header').text('{{ isset($data['backendlang']['backendlang']['Order_Amount']) ? $data['backendlang']['backendlang']['Order_Amount'] : 'Order Amount' }} (MYR)');
			$('#east-col1-header').text('{{ isset($data['backendlang']['backendlang']['Order_Amount']) ? $data['backendlang']['backendlang']['Order_Amount'] : 'Order Amount' }} (MYR)');
			$('.west input[name="weight[]"]').attr('placeholder', '{{ isset($data['backendlang']['backendlang']['Order_Amount']) ? $data['backendlang']['backendlang']['Order_Amount'] : 'Order Amount' }} (MYR)');
			$('.east input[name="weight[]"]').attr('placeholder', '{{ isset($data['backendlang']['backendlang']['Order_Amount']) ? $data['backendlang']['backendlang']['Order_Amount'] : 'Order Amount' }} (MYR)');
		} else {
			$('#threshold-label').text('{{ isset($data['backendlang']['backendlang']['Free_Shipping_Above_Certain_Price']) ? $data['backendlang']['backendlang']['Free_Shipping_Above_Certain_Price'] : 'Free Shipping Above Certain Price' }} (MYR)');
			$('#free-shipping-threshold').attr('placeholder', '{{ isset($data['backendlang']['backendlang']['Enter_price_for_free_shipping']) ? $data['backendlang']['backendlang']['Enter_price_for_free_shipping'] : 'Enter price for free shipping' }}');
			$('#west-col1-header').text('{{ isset($data['backendlang']['backendlang']['Weight_(kg)']) ? $data['backendlang']['backendlang']['Weight_(kg)'] : 'Weight (KG)' }}');
			$('#east-col1-header').text('{{ isset($data['backendlang']['backendlang']['Weight_(kg)']) ? $data['backendlang']['backendlang']['Weight_(kg)'] : 'Weight (KG)' }}');
			$('.west input[name="weight[]"]').attr('placeholder', '{{ isset($data['backendlang']['backendlang']['Weight_(kg)']) ? $data['backendlang']['backendlang']['Weight_(kg)'] : 'Weight (KG)' }}');
			$('.east input[name="weight[]"]').attr('placeholder', '{{ isset($data['backendlang']['backendlang']['Weight_(kg)']) ? $data['backendlang']['backendlang']['Weight_(kg)'] : 'Weight (KG)' }}');
		}
	});

	$('#shipping-toggle').change(function() {
		if ($(this).is(':checked')) {
			$('#threshold-label').text('{{ isset($data['backendlang']['backendlang']['Free_Shipping_Above_Certain_Price']) ? $data['backendlang']['backendlang']['Free_Shipping_Above_Certain_Price'] : 'Free Shipping Above Certain Price' }} (MYR)');
			$('#free-shipping-threshold').attr('placeholder', '{{ isset($data['backendlang']['backendlang']['Enter_price_for_free_shipping']) ? $data['backendlang']['backendlang']['Enter_price_for_free_shipping'] : 'Enter Price for free shipping' }}');
			$('#west-col1-header').text('{{ isset($data['backendlang']['backendlang']['Order_Amount']) ? $data['backendlang']['backendlang']['Order_Amount'] : 'Order Amount' }} (MYR)');
			$('#east-col1-header').text('{{ isset($data['backendlang']['backendlang']['Order_Amount']) ? $data['backendlang']['backendlang']['Order_Amount'] : 'Order Amount' }} (MYR)');
			$('.west input[name="weight[]"]').attr('placeholder', '{{ isset($data['backendlang']['backendlang']['Order_Amount']) ? $data['backendlang']['backendlang']['Order_Amount'] : 'Order Amount' }} (MYR)');
			$('.east input[name="weight[]"]').attr('placeholder', '{{ isset($data['backendlang']['backendlang']['Order_Amount']) ? $data['backendlang']['backendlang']['Order_Amount'] : 'Order Amount' }} (MYR)');
			
		} else {
			$('#threshold-label').text('{{ isset($data['backendlang']['backendlang']['Free_Shipping_Above_Certain_Price']) ? $data['backendlang']['backendlang']['Free_Shipping_Above_Certain_Price'] : 'Free Shipping Above Certain Price' }} (MYR)');
			$('#free-shipping-threshold').attr('placeholder', '{{ isset($data['backendlang']['backendlang']['Enter_price_for_free_shipping']) ? $data['backendlang']['backendlang']['Enter_price_for_free_shipping'] : 'Enter Price for free shipping' }}');
			$('#west-col1-header').text('{{ isset($data['backendlang']['backendlang']['Weight_(kg)']) ? $data['backendlang']['backendlang']['Weight_(kg)'] : 'Weight (KG)' }}');
			$('#east-col1-header').text('{{ isset($data['backendlang']['backendlang']['Weight_(kg)']) ? $data['backendlang']['backendlang']['Weight_(kg)'] : 'Weight (KG)' }}');
			$('.west input[name="weight[]"]').attr('placeholder', '{{ isset($data['backendlang']['backendlang']['Weight_(kg)']) ? $data['backendlang']['backendlang']['Weight_(kg)'] : 'Weight (KG)' }}');
			$('.east input[name="weight[]"]').attr('placeholder', '{{ isset($data['backendlang']['backendlang']['Weight_(kg)']) ? $data['backendlang']['backendlang']['Weight_(kg)'] : 'Weight (KG)' }}');
		}
	});

	$('.submit-form-btn .btn-outline-primary').click( function(e){
    	e.preventDefault();
    	$('#setting-merchant-form').submit();
    });

	
	var west_item = '<div class="form-group">\
						<input type="hidden" name="sid[]" class="sid" value="">\
						<input type="hidden" name="type[]" value="west">\
						<input type="hidden" name="country_id[]" value="160">\
						<input type="hidden" name="ship_type[]" value="">\
						<div class="row">\
							<div class="col-5">\
								<input type="text" name="weight[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['Weight_(kg)']) ? $data['backendlang']['backendlang']['Weight_(kg)'] : 'Weight (KG)' }}">\
							</div>\
							<div class="col-5">\
								<input type="text" name="shipping_fee[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['Shipping_amount']) ? $data['backendlang']['backendlang']['Shipping_amount'] : 'Shipping amount' }}">\
							</div>\
							<div class="col-2" align="center">\
								<a href="#"  class="important-text del">\
									<i class="bi bi-trash fa-2x"></i>\
								</a>\
							</div>\
						</div>\
					</div>';
    $('#add-west').click(function (e){
    	e.preventDefault();
    	$('.west').append(west_item);
    });

    var east_item = '<div class="form-group">\
    					<input type="hidden" name="sid[]" class="sid" value="">\
    					<input type="hidden" name="type[]" value="east">\
    					<input type="hidden" name="country_id[]" value="160">\
    					<input type="hidden" name="ship_type[]" value="1">\
						<div class="row">\
							<div class="col-5">\
								<input type="text" name="weight[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['Weight_(kg)']) ? $data['backendlang']['backendlang']['Weight_(kg)'] : 'Weight (KG)' }}">\
							</div>\
							<div class="col-5">\
								<input type="text" name="shipping_fee[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['Shipping_amount']) ? $data['backendlang']['backendlang']['Shipping_amount'] : 'Shipping amount' }}">\
							</div>\
							<div class="col-2" align="center">\
								<a href="#" class="important-text del">\
									<i class="bi bi-trash fa-2x"></i>\
								</a>\
							</div>\
						</div>\
					</div>';
    $('#add-east').click(function (e){
    	e.preventDefault();
    	$('.east').append(east_item);
    });

    $('.row-parent').on('click', '.del', function (e){
    	e.preventDefault();
    	var ele = $(this);
    	var sid = ele.closest('.row-parent .form-group').find('.sid').val();
    	if(confirm('{{ isset($data['backendlang']['backendlang']['Delete_this_shipping_fee']) ? $data['backendlang']['backendlang']['Delete_this_shipping_fee'] : 'Delete this shipping fee?' }}') == true){
	    	var fd = new FormData();
	        fd.append('sid', sid);
	    	$.ajax({
		         url: '{{ route("DeleteShipping") }}',
		         type: 'post',
		         data: fd,
		         contentType: false,
		         processData: false,
		         success: function(response){
		              $('.loading-gif').hide();
		              ele.closest('.row-parent .form-group').remove();
		         },
		      });
    	}
    });
</script>
@endsection