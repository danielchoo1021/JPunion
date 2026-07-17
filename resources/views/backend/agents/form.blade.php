<div class="form-group">
	<div class="row">
		<div class="col-12">
			<div class="form-group">
				<div class="container-box form-group">
					<h3>{{ isset($data['backendlang']['backendlang']['Login_Details']) ? $data['backendlang']['backendlang']['Login_Details'] :'' }}</h3>
					<hr>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label><b>{{ isset($data['backendlang']['backendlang']['Email']) ? $data['backendlang']['backendlang']['Email'] :'' }}</b> <span class="important-text">*</span></label>
								<input type="email" class="form-control required-field email" name="email" placeholder="{{ isset($data['backendlang']['backendlang']['Email']) ? $data['backendlang']['backendlang']['Email'] :'' }}" value="{{ isset($agent) ? $agent->email : old('email') }}" >
								<!-- {{ isset($agent) ? 'readonly' : '' }} -->
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label><b>{{ isset($data['backendlang']['backendlang']['Password']) ? $data['backendlang']['backendlang']['Password'] :'' }}</b> <span class="important-text">*</span></label>
								@if(isset($agent))
								<br>
								<button
	                              	type="button"
	                              	class="btn btn-outline-primary btn-sm"
	                              	data-bs-toggle="modal"
	                              	data-bs-target="#primary"
	                            >
	                              	<i class="bi bi-key"></i>{{ isset($data['backendlang']['backendlang']['Change_New_Password']) ? $data['backendlang']['backendlang']['Change_New_Password'] :'' }}
	                            </button>
								@else
								<input type="password" class="form-control required-field" name="password" placeholder="{{ isset($data['backendlang']['backendlang']['Password_(min 6 character)']) ? $data['backendlang']['backendlang']['Password_(min 6 character)'] :'' }}" value="{{ isset($agent) ? $agent->password : old('password') }}">
								@endif
							</div>
						</div>

					</div>
				</div>
				<div class="container-box form-group">
					<h3>{{ isset($data['backendlang']['backendlang']['Personal_Information']) ? $data['backendlang']['backendlang']['Personal_Information'] :'' }}</h3>
					<hr>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label><b>{{ isset($data['backendlang']['backendlang']['Full_Name']) ? $data['backendlang']['backendlang']['Full_Name'] :'' }}</b> <span class="important-text">*</span></label>
								<input type="text" class="form-control required-field" name="f_name" placeholder="{{ isset($data['backendlang']['backendlang']['Full_Name']) ? $data['backendlang']['backendlang']['Full_Name'] :'' }} *" value="{{ isset($agent) ? $agent->f_name : old('f_name') }}">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label><b>{{ isset($data['backendlang']['backendlang']['NRIC_no']) ? $data['backendlang']['backendlang']['NRIC_no'] :'' }}</b> <span class="important-text">*</span></label>
								@if(isset($agent) && !empty($agent->company_registration_no))
								<input type="text" class="form-control ic" name="ic" placeholder="{{ isset($data['backendlang']['backendlang']['NRIC_no']) ? $data['backendlang']['backendlang']['NRIC_no'] :'' }}" disabled>
								@else
								<input type="text" class="form-control ic required-field" name="ic" placeholder="{{ isset($data['backendlang']['backendlang']['NRIC_no_(Msia)']) ? $data['backendlang']['backendlang']['NRIC_no_(Msia)'] :'' }}" value="{{ isset($agent) ? $agent->ic : old('ic') }}" onkeypress="return isNumberKey(event)" maxlength="12">
								@endif
							</div>
						</div>
						<div class="col-md-6">
							<label><b>{{ isset($data['backendlang']['backendlang']['Phone_Number']) ? $data['backendlang']['backendlang']['Phone_Number'] :'' }}</b></label>
							<div class="form-group">
								<div class="row">
									<div class="col-6">
					                    <select class="form-control select2 country_code" name="country_code" id="country_code" data-live-search="true" style="padding: 0.375rem 0.75rem;">
		                                    @foreach($countries as $country)
		                                    <option value="{{ $country->country_contact }}"
		                                        {{ ( $country->country_id == '160') ? 'selected' : '' }}
		                                        > (+{{ $country->country_contact }}) {{ $country->country_name }} </option>
		                                    @endforeach

		                                </select>
					                </div>
									@php
										if(isset($agent)){
											if($agent->phone){
												$phone = $agent->phone;
												
												if($agent->country_code == '60'){
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
					                    <input type="text" class="form-control required-feild" placeholder="{{ isset($data['backendlang']['backendlang']['Ex']) ? $data['backendlang']['backendlang']['Ex'] :'' }}: 171234567" name="phone"  value="{{ isset($agent) ? $phone : old('phone') }}" onkeypress="return isNumberKey(event)">
										{{-- <input type="text" class="form-control required-feild" placeholder="{{ isset($data['backendlang']['backendlang']['Phone']) ? $data['backendlang']['backendlang']['Phone'] :'' }}" name="phone"  value="{{ isset($agent) ? $agent->phone : old('phone') }}"> --}}
					                </div>
								</div>
							</div>
						</div>
						@if(!isset($agent))
                        <div class="col-md-6">
                             <div class="form-group">
                                <label><b>{{ isset($data['backendlang']['backendlang']['Referral_Code_(Agent Code e.g. "A000001")']) ? $data['backendlang']['backendlang']['Referral_Code_(Agent Code e.g. "A000001")'] :'' }}</b></label>
                                <select name="agent_pno" class="form-control select2 agent-referral-select">
                                    <option value="">{{ isset($data['backendlang']['backendlang']['Referral_Code_(Agent Code)']) ? $data['backendlang']['backendlang']['Referral_Code_(Agent Code)'] :'' }}</option>
                                    @foreach($agents as $ref)
                                        <option {{ old('agent_pno') == $ref->code ? 'selected' : '' }} value="{{ $ref->code }}">
                                            {{ $ref->f_name }} {{ $ref->l_name ?? '' }} ({{ $ref->display_code ?? '' }}{{ $ref->display_running_no ?? '' }})
                                        </option>
                                    @endforeach
                                </select>
                                <span class="important-text">
                                    {{ isset($data['backendlang']['backendlang']['If_this_agent_is_attribute_to_ADMIN,_leave_it_blank']) ? $data['backendlang']['backendlang']['If_this_agent_is_attribute_to_ADMIN,_leave_it_blank'] :'' }}
                                </span>
                             </div>
                        </div>
                        @endif
					</div>

					<div class="form-group">
						<label><b>{{ isset($data['backendlang']['backendlang']['date_of_birth']) ? $data['backendlang']['backendlang']['date_of_birth'] :'' }}</b><span class="important-text">*</span></label>
						<input type="text" class="form-control required-feild date-picker" placeholder="{{ isset($data['backendlang']['backendlang']['date_of_birth_dd_mm_yyyy']) ? $data['backendlang']['backendlang']['date_of_birth_dd_mm_yyyy'] :'' }}" name="dob" value="{{ isset($agent) ? $agent->dob : old('dob') }}" readonly>
					</div>

					@php
						$selectedGender = (isset($agent)) ? $agent->gender : old('gender');
					@endphp
					<div class="form-group">
						<label><b>{{ isset($data['backendlang']['backendlang']['Gender']) ? $data['backendlang']['backendlang']['Gender'] :'' }}</b></label>
		                <select class="form-control" name="gender">
		                    <option {{ ($selectedGender == 'Male') ? 'selected' : '' }} value="Male">
		                        {{ isset($data['backendlang']['backendlang']['Male']) ? $data['backendlang']['backendlang']['Male'] :'' }}
		                    </option>
		                    <option {{ ($selectedGender == 'Female') ? 'selected' : '' }} value="Female">
		                        {{ isset($data['backendlang']['backendlang']['Female']) ? $data['backendlang']['backendlang']['Female'] :'' }}
		                    </option>
		                </select>
		            </div>

		            <div class="form-group">
						<label><b>{{ isset($data['backendlang']['backendlang']['Level']) ? $data['backendlang']['backendlang']['Level'] :'' }}</b></label>
						<select class="form-control" name="lvl">
							@foreach($levels as $lvl)
							@php
								$selectedLvl = isset($agent) ? $agent->lvl : old('lvl');
								$langFlag = $_COOKIE['backend_global_language'] ?? '0';
							@endphp

							<option {{ ($selectedLvl == $lvl->id) ? 'selected' : '' }} value="{{ $lvl->id }}">
								{{ $langFlag == 1 ? $lvl->agent_lvl_cn : $lvl->agent_lvl }}
							</option>
							@endforeach
						</select>
		            </div>
				</div>
				<div class="container-box form-group mb-170">
					<h3>{{ isset($data['backendlang']['backendlang']['Address_Information']) ? $data['backendlang']['backendlang']['Address_Information'] : 'Address Information' }}</h3>
					<hr>

					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>
									<b>{{ isset($data['backendlang']['backendlang']['Address']) ? $data['backendlang']['backendlang']['Address'] : 'Address' }}</b>
									<span class="important-text">*</span>
								</label>
								<textarea
									class="form-control required-field"
									name="address"
									rows="3"
									placeholder="{{ isset($data['backendlang']['backendlang']['Address']) ? $data['backendlang']['backendlang']['Address'] : 'Address' }}"
								>{{ old('address', $defaultAddress->address ?? '') }}</textarea>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label>
									<b>{{ isset($data['backendlang']['backendlang']['City']) ? $data['backendlang']['backendlang']['City'] : 'City' }}</b>
								</label>
								<input
									type="text"
									class="form-control"
									name="city"
									placeholder="{{ isset($data['backendlang']['backendlang']['City']) ? $data['backendlang']['backendlang']['City'] : 'City' }}"
									value="{{ old('city', $defaultAddress->city ?? '') }}"
								>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label>
									<b>{{ isset($data['backendlang']['backendlang']['Postcode']) ? $data['backendlang']['backendlang']['Postcode'] : 'Postcode' }}</b>
								</label>
								<input
									type="text"
									class="form-control"
									name="postcode"
									placeholder="{{ isset($data['backendlang']['backendlang']['Postcode']) ? $data['backendlang']['backendlang']['Postcode'] : 'Postcode' }}"
									value="{{ old('postcode', $defaultAddress->postcode ?? '') }}"
									onkeypress="return isNumberKey(event)"
								>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label>
									<b>{{ isset($data['backendlang']['backendlang']['State']) ? $data['backendlang']['backendlang']['State'] : 'State' }}</b>
								</label>
										@php
											$selectedState = isset($defaultAddress) ? $defaultAddress->state : old('state');
											$selectedCountry = isset($defaultAddress) ? $defaultAddress->country : old('country');
										@endphp
								<select class="form-control" name="state" style="padding: 0.375rem 0.75rem;">
									<option value="">{{ isset($data['lang']['lang']['select_state']) ? $data['lang']['lang']['select_state'] :'选择州'}}</option>
									@foreach($states as $state)
										<option {{ ($selectedState == $state->id) ? 'selected' : '' }} value="{{ $state->id }}">{{ $state->name }}</option>
									@endforeach
								</select>
								<!-- <input
									type="text"
									class="form-control"
									name="state"
									placeholder="{{ isset($data['backendlang']['backendlang']['State']) ? $data['backendlang']['backendlang']['State'] : 'State' }}"
									value="{{ old('state', $defaultAddress->state ?? '') }}"
								> -->
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label>
									<b>{{ isset($data['backendlang']['backendlang']['Country']) ? $data['backendlang']['backendlang']['Country'] : 'Country' }}</b>
								</label>
									@php
										$CountryContact = (isset($defaultAddress)) ? $defaultAddress->country_code : '60';
									@endphp
								<select class="form-control select2 country_code" name="country_code" id="country_code" data-live-search="true" style="padding: 0.375rem 0.75rem;">
									@foreach($countries as $country)
									<option {{ ($CountryContact == $country->country_contact) ? 'selected' : '' }} value="{{ $country->country_contact }}">(+{{ $country->country_contact }}) {{ $country->country_name }} </option>
									@endforeach
									<!-- <option value="60">(+60) Malaysia</option>
									<option value="65">(+65) Singapore</option> -->
								</select>
								<!-- <input
									type="text"
									class="form-control"
									name="country"
									placeholder="{{ isset($data['backendlang']['backendlang']['Country']) ? $data['backendlang']['backendlang']['Country'] : 'Country' }}"
									value="{{ old('country', $defaultAddress->country ?? '') }}"
								> -->
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>
