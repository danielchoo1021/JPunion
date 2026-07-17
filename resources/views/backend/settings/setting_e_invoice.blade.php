@extends('layouts.admin_app')
@section('content')
<style>
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
@if($errors->any())
  <div class="alert alert-danger">{!! implode('<br/>', $errors->all(':message')) !!}</div>
@endif
	<div class="form-group">
		<form method="POST" action="{{ route('setting_einvoice_save') }}" id="setting-einvoice-form">
			@csrf
			<div class="form-group">
				<div class="row">
					<div class="col-12">
						<div class="container-box">
							<h4>
								{{ isset($data['backendlang']['backendlang']['e_Invoice_API']) ? $data['backendlang']['backendlang']['e_Invoice_API'] :'' }}
							</h4>
							<hr>
							<div class="form-group">
								<div class="row">
									<div class="col-3">
										<div class="form-group">
											<label>{{ isset($data['backendlang']['backendlang']['Enable_API']) ? $data['backendlang']['backendlang']['Enable_API'] :'' }}</label>
										</div>
									</div>
									<div class="col-3">
										<div class="form-group">
											<label class="switch">
												<input type="checkbox" name="einvoice_status" id="einvoice_status" {{ ((!empty($setting) && $setting['status'] == 1) || old('einvoice_status') == 1) ? 'checked' : '' }} value="1">
												<span class="slider round"></span>
											</label>
										</div>
									</div>
								</div>
                			<div class="row">
									<div class="col-3">
										<div class="form-group">
                     					 <label >{{ isset($data['backendlang']['backendlang']['Client_ID']) ? $data['backendlang']['backendlang']['Client_ID'] :'' }}<span style="color: red">*</span></label>
										</div>
									</div>
									<div class="col-6">
										<div class="form-group">
                      						<input type="text" class="form-control" name="client_id" id="client_id" value="{{ !empty($setting) ? $setting['client_id'] : old('client_id') }}">
										</div>
									</div>
								</div>
                			<div class="row">
									<div class="col-3">
										<div class="form-group">
                    					  <label >{{ isset($data['backendlang']['backendlang']['Client_Secret']) ? $data['backendlang']['backendlang']['Client_Secret'] :'' }}<span style="color: red">*</span></label>
										</div>
									</div>
									<div class="col-6">
										<div class="form-group">
											<input type="password" class="form-control" name="client_secret" id="client_secret" value="{{ !empty($setting) ? $setting['client_secret'] : old('client_secret') }}">
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="form-group container-einvoice" style="{{ ((!empty($setting) && $setting->status == 1) || old('einvoice_status') == 1) ? 'display: block' : 'display:none' }}">
				<div class="row">
					<div class="col-12">
						<div class="container-box">
							<h4>
								{{ isset($data['backendlang']['backendlang']['Basic_Information_Supplier']) ? $data['backendlang']['backendlang']['Basic_Information_Supplier'] :'' }}
							</h4>
							<hr>
							<div class="form-group">
								<div class="row">
									<div class="col-6">
										<div class="form-group">
											<label >{{ isset($data['backendlang']['backendlang']['Name']) ? $data['backendlang']['backendlang']['Name'] :'' }}<span style="color: red">*</span></label>
											<input type="text" class="form-control" name="supplier_name" id="supplier_name" placeholder="{{ isset($data['backendlang']['backendlang']['Supplier_Name']) ? $data['backendlang']['backendlang']['Supplier_Name'] :'' }}" value="{{ !empty($setting) ? $setting['supplier_name'] : old('supplier_name') }}" />
										</div>
									</div>
									<div class="col-6">
										<div class="form-group">
											<label>{{ isset($data['backendlang']['backendlang']['NRIC_no']) ? $data['backendlang']['backendlang']['NRIC_no'] :'' }}<span style="color: red">*</span></label>
											<input type="text" class="form-control" name="supplier_nric" id="supplier_nric" placeholder="991122004444" value="{{ !empty($setting) ? $setting['supplier_nric'] : old('supplier_nric') }}" />
										</div>
									</div>
									<div class="col-6">
										<div class="form-group">
											<label>{{ isset($data['backendlang']['backendlang']['TIN']) ? $data['backendlang']['backendlang']['TIN'] :'' }}<span style="color: red">*</span></label>
											<input type="text" class="form-control" name="supplier_tin" id="supplier_tin" placeholder="{{ isset($data['backendlang']['backendlang']['Supplier_TIN']) ? $data['backendlang']['backendlang']['Supplier_TIN'] :'' }}" value="{{ !empty($setting) ? $setting['supplier_tin'] : old('supplier_tin') }}" />
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-6">
										<div class="form-group">
											<label>{{ isset($data['backendlang']['backendlang']['Telephone']) ? $data['backendlang']['backendlang']['Telephone'] :'' }}<span style="color: red">*</span></label>
											<input type="text" class="form-control" name="supplier_phone" id="supplier_phone" placeholder="6012345678" value="{{ !empty($setting) ? $setting['supplier_telephone'] : old('supplier_phone') }}" />
										</div>
									</div>
									<div class="col-6">
										<div class="form-group">
											<label>{{ isset($data['backendlang']['backendlang']['Email']) ? $data['backendlang']['backendlang']['Email'] :'' }}<span style="color: red">*</span></label>
											<input type="text" class="form-control" name="supplier_email" id="supplier_email" placeholder="{{ isset($data['backendlang']['backendlang']['Supplier_Email']) ? $data['backendlang']['backendlang']['Supplier_Email'] :'' }}" value="{{ !empty($setting) ? $setting['supplier_email'] : old('supplier_email') }}" />
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-6">
										<div class="form-group">
											<label>{{ isset($data['backendlang']['backendlang']['Industry_Classification_Code']) ? $data['backendlang']['backendlang']['Industry_Classification_Code'] :'' }}<span style="color: red">*</span></label>
											<input type="text" class="form-control" name="industry_classification_code" id="industry_classification_code" placeholder="{{ isset($data['backendlang']['backendlang']['Industry_Classification_Code_eInvoice']) ? $data['backendlang']['backendlang']['Industry_Classification_Code_eInvoice'] :'' }}" value="{{ !empty($setting) ? $setting['industry_classification_code'] : old('industry_classification_code') }}" />
										</div>
									</div>
									<div class="col-6">
										<div class="form-group">
											<label>{{ isset($data['backendlang']['backendlang']['Industry_Classification_Description']) ? $data['backendlang']['backendlang']['Industry_Classification_Description'] :'' }}<span style="color: red">*</span></label>
											<input type="text" class="form-control" name="industry_classification_desc" id="industry_classification_desc" placeholder="{{ isset($data['backendlang']['backendlang']['Industry_Classification_Description']) ? $data['backendlang']['backendlang']['Industry_Classification_Description'] :'' }}" value="{{ !empty($setting) ? $setting['industry_classification_desc'] : old('industry_classification_desc') }}" />
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
				
			<div class="form-group container-einvoice"  style="{{ !empty($setting) && $setting->status == 1 ? 'display: block' : 'display:none' }}">
				<div class="row">
					<div class="col-12">
						<div class="container-box">
							<h4>
								{{ isset($data['backendlang']['backendlang']['Address']) ? $data['backendlang']['backendlang']['Address'] :'' }}
							</h4>
							<hr>
							<div class="form-group">
								<div class="row">
									<div class="col-6">
										<div class="form-group">
											<label>{{ isset($data['backendlang']['backendlang']['Country']) ? $data['backendlang']['backendlang']['Country'] :'' }}</label>
											<select class="form-control" name="supplier_country">
												@foreach($countries as $country)
												<option value="{{ $country['e_invoice_code'] }}" {{ (!empty($setting) && ($setting['country_code'] == $country['e_invoice_code'] || $country['e_invoice_code'] == old('supplier_country'))) ? "selected='selected'" : ''  }}>{{ $country['country_name'] }}</option>
												@endforeach
											</select>
										</div>
									</div>
									<div class="col-6">
										<div class="form-group">
											<label>{{ isset($data['backendlang']['backendlang']['State']) ? $data['backendlang']['backendlang']['State'] :'' }}</label>
											<select class="form-control" name="supplier_state">
												<option value="0">{{ isset($data['backendlang']['backendlang']['Please_Select_State']) ? $data['backendlang']['backendlang']['Please_Select_State'] :'' }}</option>
												@foreach($states as $state)
												<option value="{{ $state['e_invoice_code'] }}" {{ (!empty($setting) && ($setting['state_code'] == $state['e_invoice_code'] || $state['e_invoice_code'] == old('supplier_state'))) ? "selected='selected'" : '' }}>{{ $state['name'] }}</option>
												@endforeach
											</select>
										</div>
									</div>
									<div class="col-6">
										<div class="form-group">
											<label>{{ isset($data['backendlang']['backendlang']['City']) ? $data['backendlang']['backendlang']['City'] :'' }}</label>
											<input type="text" class="form-control" name="address_city" value="{{ !empty($setting) ? $setting['city_name'] : old('address_city') }}" />
										</div>
									</div>
									<div class="col-6">
										<div class="form-group">
											<label>{{ isset($data['backendlang']['backendlang']['Postal_Code']) ? $data['backendlang']['backendlang']['Postal_Code'] :'' }}</label>
											<input type="text" class="form-control" name="address_postal_code" value="{{ !empty($setting) ? $setting['postal_code'] : old('address_postal_code') }}" />
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-6">
										<div class="form-group">
											<label>{{ isset($data['backendlang']['backendlang']['Address_Line']) ? $data['backendlang']['backendlang']['Address_Line'] :'' }} 1</label>
											<input type="text" class="form-control" name="address_line_1" value="{{ !empty($setting) ? $setting['address_1'] : old('address_line_1') }}" />
										</div>
									</div>
									<div class="col-6">
										<div class="form-group">
											<label>{{ isset($data['backendlang']['backendlang']['Address_Line']) ? $data['backendlang']['backendlang']['Address_Line'] :'' }} 2</label>
											<input type="text" class="form-control" name="address_line_2" value="{{ !empty($setting) ? $setting['address_2'] : old('address_line_2') }}" />
										</div>
									</div>
									<div class="col-6">
										<div class="form-group">
											<label>{{ isset($data['backendlang']['backendlang']['Address_Line']) ? $data['backendlang']['backendlang']['Address_Line'] :'' }} 3</label>
											<input type="text" class="form-control" name=" address_line_3" value="{{ !empty($setting) ? $setting['address_3'] : old('address_line_3') }}"/>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>

	<div class="submit-form-btn">
		<div class="form-group wizard-actions" align="right">
			<button class="btn btn-outline-primary">
				<i class="fa fa-check"> {{ isset($data['backendlang']['backendlang']['Save_Changes']) ? $data['backendlang']['backendlang']['Save_Changes'] :'' }}</i>
			</button>

		</div>
	</div>
@endsection
@section('js')
	<script type="text/javascript">
		$('#setting-einvoice-form').on('change', 'input', function() {
			var ele = $(this);

			if (ele.val()) {
				ele.removeClass('input-required-field');
			}
		});

		$('#setting-einvoice-form').on('change', '.products', function() {
			var ele = $(this);
			var productdiv = $(this).closest('.child-row').find(
				'.select2-container--default .select2-selection--single');

			if (ele.val()) {
				productdiv.removeClass('input-required-field');
			}
		});

		$('#einvoice_status').on('change', function(){
			var ele = $(this);
			
			if(ele.is(':checked') == true){
				$('.container-einvoice').show();
			}
			else{
				$('.container-einvoice').hide();
			}
		});

		$('.submit-form-btn .btn-outline-primary').click(function(e) {
			$('#setting-einvoice-form').submit();
		});
	</script>
@endsection
