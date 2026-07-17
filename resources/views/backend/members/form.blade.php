<div class="row">
	<div class="col-12">
		<div class="container-box form-group">
			<h3>{{ isset($data['backendlang']['backendlang']['Login_Details']) ? $data['backendlang']['backendlang']['Login_Details'] :'' }}</h3>
			<hr>
			<div class="form-group">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label><b>{{ isset($data['backendlang']['backendlang']['Email']) ? $data['backendlang']['backendlang']['Email'] :'' }}</b> <span class="important-text">*</span></label>
							<input type="email" class="form-control required-field email" name="email" placeholder="{{ isset($data['backendlang']['backendlang']['Email']) ? $data['backendlang']['backendlang']['Email'] :'' }}" value="{{ isset($user) ? $user->email : old('email') }}">
							<!-- {{ isset($user) ? 'readonly' : '' }} -->
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group">
							<label><b>{{ isset($data['backendlang']['backendlang']['Password']) ? $data['backendlang']['backendlang']['Password'] :'' }}</b> <span class="important-text">*</span></label>
							@if(isset($user))
							<br>
							<button
                              	type="button"
                              	class="btn btn-outline-primary btn-sm"
                              	data-bs-toggle="modal"
                              	data-bs-target="#primary"
                            >
                              	<i class="bi bi-key"></i> {{ isset($data['backendlang']['backendlang']['Change_New_Password']) ? $data['backendlang']['backendlang']['Change_New_Password'] :'' }}
                            </button>
							<!-- <button type="button" class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#myModal">
								<i class="fa fa-key"></i> Change New Password
							</button> -->
							@else
							<input type="password" class="form-control required-field" name="password" placeholder="{{ isset($data['backendlang']['backendlang']['Password_(min 6 character)']) ? $data['backendlang']['backendlang']['Password_(min 6 character)'] :'' }}" value="{{ isset($user) ? $user->password : old('password') }}">
							@endif
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="container-box form-group mb-170">
			<h3>{{ isset($data['backendlang']['backendlang']['Personal_Information']) ? $data['backendlang']['backendlang']['Personal_Information'] :'' }}</h3>
			<hr>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label><b>{{ isset($data['backendlang']['backendlang']['Full_Name']) ? $data['backendlang']['backendlang']['Full_Name'] :'' }}</b> <span class="important-text">*</span></label>
						<input type="text" class="form-control required-field" name="f_name" placeholder="{{ isset($data['backendlang']['backendlang']['Full_Name']) ? $data['backendlang']['backendlang']['Full_Name'] :'' }}" value="{{ isset($user) ? $user->f_name : old('f_name') }}" 	>
					</div>
				</div>

				<div class="col-md-6">
					<div class="form-group">
						<label><b>{{ isset($data['backendlang']['backendlang']['NRIC_no']) ? $data['backendlang']['backendlang']['NRIC_no'] :'' }}</b> <span class="important-text">*</span></label>
						@if(isset($user) && !empty($user->company_registration_no))
						<input type="text" class="form-control" name="ic" placeholder="{{ isset($data['backendlang']['backendlang']['NRIC_no']) ? $data['backendlang']['backendlang']['NRIC_no'] :'' }}" disabled>
						@else
						<input type="text" class="form-control required-field" name="ic" placeholder="{{ isset($data['backendlang']['backendlang']['NRIC_no']) ? $data['backendlang']['backendlang']['NRIC_no'] :'' }}" value="{{ isset($user) ? $user->ic : old('ic') }}" onkeypress="return isNumberKey(event)" maxlength="12">
						@endif
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="row">
					<div class="col-md-6">
						<label><b>{{ isset($data['backendlang']['backendlang']['Phone']) ? $data['backendlang']['backendlang']['Phone'] :'' }}</b></label>
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
								if(isset($user)){
									if($user->phone){
										$phone = $user->phone;
										
										if($user->country_code == '60'){
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
								<input type="text" class="form-control required-feild" placeholder="{{ isset($data['backendlang']['backendlang']['Ex']) ? $data['backendlang']['backendlang']['Ex'] :'' }}: 171234567" name="phone"  value="{{ isset($user) ? $phone : old('phone') }}" onkeypress="return isNumberKey(event)">
			                    {{-- <input type="text" class="form-control required-feild" placeholder="{{ isset($data['backendlang']['backendlang']['Phone']) ? $data['backendlang']['backendlang']['Phone'] :'' }}" name="phone"  value="{{ isset($user) ? $user->phone : old('phone') }}"> --}}
			                </div>
						</div>
					</div>
			

					@if(!isset($user))
    					<div class="col-md-6">
        					<div class="form-group">
            					<label><b>{{ isset($data['backendlang']['backendlang']['Referral_Code_(Agent Code e.g. "A000001")']) ? $data['backendlang']['backendlang']['Referral_Code_(Agent Code e.g. "A000001")'] :'' }}</b></label>
            					<select name="master_id" class="form-control select2">
   									<option value="">{{ isset($data['backendlang']['backendlang']['Referral_Code_(Agent Code)']) ? $data['backendlang']['backendlang']['Referral_Code_(Agent Code)'] :'' }}</option>
										@foreach($agents as $ref)
											<option {{ old('master_id') == $ref->code ? 'selected' : '' }}  value="{{ $ref->code }}"> 
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
			</div>
			
			<div class="form-group">
				<label><b>{{ isset($data['backendlang']['backendlang']['date_of_birth']) ? $data['backendlang']['backendlang']['date_of_birth'] :'' }}</b><span class="important-text">*</span></label>
                <input type="text" class="form-control required-feild date-picker" placeholder="{{ isset($data['backendlang']['backendlang']['date_of_birth_dd_mm_yyyy']) ? $data['backendlang']['backendlang']['date_of_birth_dd_mm_yyyy'] :'' }}" name="dob" value="{{ isset($user) ? $user->dob : old('dob') }}" readonly>
            </div>

			@php
				$selectedGender = (isset($user)) ? $user->gender : old('gender');
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
            @if($data['web_setting']->bonus_member_enable == 1)
            <!-- <div class="form-group">
				<label><b>Level</b></label>
				<select class="form-control" name="lvl">
					@foreach($levels as $lvl)
					@php
						$selectedLvl = isset($agent) ? $agent->lvl : old('lvl');
					@endphp
					<option {{ ($selectedLvl == $lvl->id) ? 'selected' : '' }} value="{{ $lvl->id }}">
						{{ $lvl->agent_lvl }}
					</option>
					@endforeach
				</select>
            </div> -->
            @endif
		</div>
	</div>
</div>

