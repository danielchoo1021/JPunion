<div class="form-group address-book-list">
	@if($errors->any())
                          <div class="alert alert-danger">{!! implode('<br/>', $errors->all(':message')) !!}</div>
                        @endif
	{{-- <input type="hidden" name="address_imp" value="{{ isset($address) ? md5($address->id) : '' }}"> --}}
	<div class="row">
		<div class="col-md-4">
			<div class="form-group">
				<label>{{ isset($data['lang']['lang']['complete_name']) ? $data['lang']['lang']['complete_name'] :'全名'}} <span class="important-text">*</span></label>
				<input type="text" class="form-control required-feild" placeholder="{{ isset($data['lang']['lang']['complete_name']) ? $data['lang']['lang']['complete_name'] :'全名'}}" name="f_name" value="{{ isset($address) ? $address->f_name : old('f_name') }}">
			</div>
		</div>

		<div class="col-md-4">
			 <div class="form-group">
			 	<label>{{ isset($data['lang']['lang']['phone_number']) ? $data['lang']['lang']['phone_number'] :'手机号'}} <span class="important-text">*</span></label>
                <div class="row">
                	@php
                		$CountryContact = (isset($address)) ? $address->country_code : '60';

						if(isset($address)){
							if($address->phone){
								$phone = $address->phone;
								
								if($address->country_code == '60'){
									$phone = ($phone[0] == '0') ? $phone : '0'.$phone;
								}else{
									$phone = ($phone[0] == '0') ? substr($phone, 1) : $phone;
								}
							}else{
								$phone = '';
							}
						}
                	@endphp
                    <div class="col-6">
                        <select class="form-control select2 country_code" name="country_code" id="country_code" data-live-search="true" style="padding: 0.375rem 0.75rem;">
                            @foreach($countries as $country)
                            <option {{ ($CountryContact == $country->country_contact) ? 'selected' : '' }} value="{{ $country->country_contact }}">(+{{ $country->country_contact }}) {{ $country->country_name }} </option>
                            @endforeach
                            <!-- <option value="60">(+60) Malaysia</option>
                            <option value="65">(+65) Singapore</option> -->
                        </select>
                    </div>
                    <div class="col-6">
                        <input type="text" class="form-control" placeholder="Ex: 121234567" name="phone" value="{{ isset($address) ? $phone : old('phone') }}"  onkeypress="return isNumberKey(event)">
                    </div>
                </div>
            </div>
		</div>

		<div class="col-md-4">
			<div class="form-group">
				<label>{{ isset($data['lang']['lang']['email_address']) ? $data['lang']['lang']['email_address'] :'电子邮件'}} <span class="important-text">*</span></label>
				<input type="text" class="form-control required-feild" placeholder="{{ isset($data['lang']['lang']['email_address']) ? $data['lang']['lang']['email_address'] :'电子邮件'}}" name="email" value="{{ isset($address) ? $address->email : old('email') }}">
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">
			<div class="form-group">
				<label>{{ isset($data['lang']['lang']['address']) ? $data['lang']['lang']['address'] :'地址'}} <span class="important-text">*</span></label>
				<textarea class="form-control required-feild" placeholder="{{ isset($data['lang']['lang']['address']) ? $data['lang']['lang']['address'] :'地址'}}" name="address">{{ isset($address) ? $address->address : old('address') }}</textarea>
			</div>
		</div>

		<div class="col-md-4">
			<div class="form-group">
				<label>{{ isset($data['lang']['lang']['postcode']) ? $data['lang']['lang']['postcode'] :'邮政编码'}} <span class="important-text">*</span></label>
				<input type="text" class="form-control required-feild" placeholder="{{ isset($data['lang']['lang']['postcode']) ? $data['lang']['lang']['postcode'] :'邮政编码'}}" name="postcode" value="{{ isset($address) ? $address->postcode : old('postcode') }}">
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">
			<div class="form-group">
				<label>{{ isset($data['lang']['lang']['city']) ? $data['lang']['lang']['city'] :'城市'}} <span class="important-text">*</span></label>
				<input type="text" class="form-control required-feild" placeholder="{{ isset($data['lang']['lang']['city']) ? $data['lang']['lang']['city'] :'城市'}}" name="city" value="{{ isset($address) ? $address->city : old('city') }}">
			</div>
		</div>

		<div class="col-md-4">
			<div class="form-group">
				<label>{{ isset($data['lang']['lang']['state']) ? $data['lang']['lang']['state'] :'州'}} <span class="important-text">*</span></label>
				@php
					$selectedState = isset($address) ? $address->state : old('state');
					$selectedCountry = isset($address) ? $address->country : old('country');
				@endphp
				<div class="state_area">
					@if($selectedCountry == 160 || empty($selectedCountry))
					<select class="form-control" name="state" style="padding: 0.375rem 0.75rem;">
						<option value="">{{ isset($data['lang']['lang']['select_state']) ? $data['lang']['lang']['select_state'] :'选择州'}}</option>
						@foreach($states as $state)
							<option {{ ($selectedState == $state->id) ? 'selected' : '' }} value="{{ $state->id }}">{{ $state->name }}</option>
						@endforeach
					</select>
					@else
					<input type="text" class="form-control state" name="state" placeholder="State" value="{{ $selectedState }}">
					@endif
				</div>
			</div>
		</div>
	</div>
	<div class="form-group">
		<div class="row">
			<div class="col-md-4">
		        <label>{{ isset($data['lang']['lang']['country']) ? $data['lang']['lang']['country'] :'国家'}} <span class="important-text">*</span></label>
		        <select class="form-control country" name="country" style="padding: 0.375rem 0.75rem;">
		            <option value="">{{ isset($data['lang']['lang']['select_country']) ? $data['lang']['lang']['select_country'] :'选择国家'}}</option>
		            @foreach($countries as $country)
                    <option {{ ($selectedCountry == $country->country_id) ? 'selected' : '' }} value="{{ $country->country_id }}">
                    	{{ $country->country_name }} 
                   	</option>
                    @endforeach
		        </select>
			</div>
		</div>
    </div>
	<div class="form-group">
		<b id="error-message" class="important-text"></b>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<div class="form-group">
				<button class="btn btn-primary submit-address btn-sm set_button set_text">
					<i class="fa fa-check"></i> &nbsp;&nbsp; {{ isset($data['lang']['lang']['save_changes']) ? $data['lang']['lang']['save_changes'] :'保存'}}
				</button>
			</div>
		</div>
	</div>
</div>