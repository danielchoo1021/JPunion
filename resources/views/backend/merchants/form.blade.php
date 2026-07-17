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
								<input type="email" class="form-control required-field email" name="email" placeholder="{{ isset($data['backendlang']['backendlang']['Email']) ? $data['backendlang']['backendlang']['Email'] :'' }}" value="{{ isset($merchant) ? $merchant->email : old('email') }}" >
								<!-- {{ isset($merchant) ? 'readonly' : '' }} -->
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label><b><th>{{ isset($data['backendlang']['backendlang']['Password']) ? $data['backendlang']['backendlang']['Password'] :'' }}</th></b> <span class="important-text">*</span></label>
								@if(isset($merchant))
								<br>
								<button
	                              	type="button"
	                              	class="btn btn-outline-primary btn-sm"
	                              	data-bs-toggle="modal"
	                              	data-bs-target="#primary"
	                            >
	                              	<i class="bi bi-key"></i> <th>{{ isset($data['backendlang']['backendlang']['Change_New_Password']) ? $data['backendlang']['backendlang']['Change_New_Password'] :'' }}</th>
	                            </button>
								@else
								<input type="password" class="form-control required-field" name="password" placeholder="{{ isset($data['backendlang']['backendlang']['Password_(min 6 character)']) ? $data['backendlang']['backendlang']['Password_(min 6 character)'] :'' }}" value="{{ isset($merchant) ? $merchant->password : old('password') }}">
								@endif
							</div>
						</div>

					</div>
				</div>
				<div class="container-box form-group">
					<h3>{{ isset($data['backendlang']['backendlang']['Personal_Details']) ? $data['backendlang']['backendlang']['Personal_Details'] :'' }}</h3>
					<hr>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label><b>{{ isset($data['backendlang']['backendlang']['Full_Name']) ? $data['backendlang']['backendlang']['Full_Name'] :'' }}</b> <span class="important-text">*</span></label>
								<input type="text" class="form-control required-field" name="f_name" placeholder="{{ isset($data['backendlang']['backendlang']['Full_Name']) ? $data['backendlang']['backendlang']['Full_Name'] :'' }} *" value="{{ isset($merchant) ? $merchant->f_name : old('f_name') }}">
							</div>
						</div>
						<!-- <div class="col-md-6">
							<div class="form-group">
								<label><b>NRIC No</b> <span class="important-text">*</span></label>
								@if(isset($merchant) && !empty($merchant->company_registration_no))
								<input type="text" class="form-control ic" name="ic" placeholder="NRIC No" disabled>
								@else
								<input type="text" class="form-control ic required-field" name="ic" placeholder="NRIC No (Malaysia)" value="{{ isset($merchant) ? $merchant->ic : old('ic') }}" onkeypress="return isNumberKey(event)" maxlength="12">
								@endif
							</div>
						</div> -->
						<div class="col-md-6">
							<label><b>{{ isset($data['backendlang']['backendlang']['Phone_Number']) ? $data['backendlang']['backendlang']['Phone_Number'] :'' }}</b> <span class="important-text">*</span></label>
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
					                <div class="col-6">
					                    <input type="text" class="form-control required-feild" placeholder="{{ isset($data['backendlang']['backendlang']['Phone']) ? $data['backendlang']['backendlang']['Phone'] :'' }}" name="phone"  value="{{ isset($merchant) ? $merchant->phone : old('phone') }}">
					                </div>
								</div>
							</div>
						</div>
						<!-- <div class="col-md-6">
							<div class="form-group">
								<label><b>Refferal By</b></label>
								<input type="text" name="master_id" class="form-control" placeholder="Refferal By" value="{{ isset($merchant) ? $merchant->master_id : old('master_id') }}">
							</div>
						</div> -->
					</div>
					<!-- @php
						$selectedGender = (isset($merchant)) ? $merchant->gender : old('gender');
					@endphp
					<div class="form-group">
						<label><b>Gender</b></label>
		                <select class="form-control" name="gender">
		                    <option {{ ($selectedGender == 'Male') ? 'selected' : '' }} value="Male">
		                        Male
		                    </option>
		                    <option {{ ($selectedGender == 'Female') ? 'selected' : '' }} value="Female">
		                        Female
		                    </option>
		                </select>
		            </div> -->

					<div class="form-group">
						<b>	{{ isset($data['backendlang']['backendlang']['Description']) ? $data['backendlang']['backendlang']['Description'] :'' }}:</b>
						<textarea class="form-control" name="description" id="description">{!! isset($merchant) ? $merchant->description : old('description') !!}</textarea>	
					</div>
				</div>
				<div class="container-box form-group">
					<h3>{{ isset($data['backendlang']['backendlang']['Account_Settings']) ? $data['backendlang']['backendlang']['Account_Settings'] :'' }}</h3>
					<hr>
					<div class="form-group">
						<label><b>{{ isset($data['backendlang']['backendlang']['Active_Period_Days']) ? $data['backendlang']['backendlang']['Active_Period_Days'] :'' }}</b></label>
						<div class="row">
							<div class="col-md-6">
				                <span class="important-text">{{ isset($data['backendlang']['backendlang']['Notice_0_Unlimited']) ? $data['backendlang']['backendlang']['Notice_0_Unlimited'] :'' }}</span>
				                <input type="number" class="form-control active_period" name="active_period" value="{{ isset($merchant) ? $merchant->active_period : 7 }}">
				                <div class="important-text">
				                	{{ isset($data['backendlang']['backendlang']['Created_Date']) ? $data['backendlang']['backendlang']['Created_Date'] :'' }}: {{ isset($merchant) ? date('Y-m-d', strtotime($merchant->created_at)) : date('Y-m-d') }}
				                </div>
				                <div class="important-text">
				                	c: <span class="expired_date"></span>
				                </div>
							</div>
							<div class="col-md-6">
								<label><b> </b></label>
								<input type="text" class="form-control datepicker" placeholder="{{ isset($data['backendlang']['backendlang']['DOB']) ? $data['backendlang']['backendlang']['DOB'] :'' }}" name="dob" value="{{ old('dob') ?? '' }}">
							</div>
						</div>
		            </div>
				</div>
			</div>
		</div>
	</div>
</div>
