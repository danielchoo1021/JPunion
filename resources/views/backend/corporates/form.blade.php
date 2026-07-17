<div class="row">
	<div class="col-12">
		<div class="form-group">
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label>{{ isset($data['backendlang']['backendlang']['Full_Name']) ? $data['backendlang']['backendlang']['Full_Name'] :'' }} <span class="important-text">*</span></label>
						<input type="text" class="form-control required-field" name="f_name" placeholder="{{ isset($data['backendlang']['backendlang']['Full_Name']) ? $data['backendlang']['backendlang']['Full_Name'] :'' }} *" value="{{ isset($corporate) ? $corporate->f_name : old('f_name') }}">
					</div>
				</div>

				<div class="col-md-6">
					<div class="form-group">
						<label>{{ isset($data['backendlang']['backendlang']['Email']) ? $data['backendlang']['backendlang']['Email'] :'' }} <span class="important-text">*</span></label>
						<input type="email" class="form-control required-field" name="email" placeholder="{{ isset($data['backendlang']['backendlang']['Email']) ? $data['backendlang']['backendlang']['Email'] :'' }}" value="{{ isset($corporate) ? $corporate->email : old('email') }}">
						<!-- {{ isset($corporate) ? 'readonly' : '' }} -->
					</div>
				</div>

				<div class="col-md-6">
					<div class="form-group">
						<label>{{ isset($data['backendlang']['backendlang']['Password']) ? $data['backendlang']['backendlang']['Password'] :'' }} <span class="important-text">*</span></label>
						@if(isset($corporate))
						<br>
						<button type="button" class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#myModal">
							<i class="fa fa-key"></i> {{ isset($data['backendlang']['backendlang']['Change_New_Password']) ? $data['backendlang']['backendlang']['Change_New_Password'] :'' }}
						</button>
						@else
						<input type="password" class="form-control required-field" name="password" placeholder="{{ isset($data['backendlang']['backendlang']['Password_(min 6 character)']) ? $data['backendlang']['backendlang']['Password_(min 6 character)'] :'' }}" value="{{ isset($corporate) ? $corporate->password : old('password') }}">
						@endif
					</div>
				</div>

				<div class="col-md-6">
					<div class="form-group">
						<label>{{ isset($data['backendlang']['backendlang']['Company_Registration_No']) ? $data['backendlang']['backendlang']['Company_Registration_No'] :'' }} <span class="important-text">*</span></label>
						@if(isset($corporate) && !empty($corporate->company_registration_no))
						<input type="text" class="form-control" name="company_registration_no" placeholder="{{ isset($data['backendlang']['backendlang']['Company_Registration_No']) ? $data['backendlang']['backendlang']['Company_Registration_No'] :'' }}" value="{{ $corporate->company_registration_no }}" disabled>
						@else
						<input type="text" class="form-control required-field" name="company_registration_no" placeholder="{{ isset($data['backendlang']['backendlang']['Company_Registration_No']) ? $data['backendlang']['backendlang']['Company_Registration_No'] :'' }}" value="{{ isset($corporate) ? $corporate->company_registration_no : old('company_registration_no') }}">
						@endif
					</div>
				</div>
			</div>
			<hr>
			<div class="form-group">
				<div class="row">
					<div class="col-md-6">
						<label>{{ isset($data['backendlang']['backendlang']['Phone']) ? $data['backendlang']['backendlang']['Phone'] :'' }}</label>
						<div class="row">
							<div class="col-6">
			                    <select class="form-control select2" name="country_code" id="country_code" data-live-search="true">
			                        <option value="60">(+60) Malaysia</option>
			                        <option value="65">(+65) Singapore</option>
			                    </select>
			                </div>
			                <div class="col-6">
			                    <input type="text" class="form-control required-feild" placeholder="{{ isset($data['backendlang']['backendlang']['Phone']) ? $data['backendlang']['backendlang']['Phone'] :'' }}" name="phone"  value="{{ isset($corporate) ? $corporate->phone : old('phone') }}">
			                </div>
						</div>
					</div>
				</div>
			</div>
			
			@php
				$selectedGender = (isset($corporate)) ? $corporate->gender : old('gender');
			@endphp
			<div class="form-group">
				<label>{{ isset($data['backendlang']['backendlang']['Gender']) ? $data['backendlang']['backendlang']['Gender'] :'' }}</label>
                <select class="form-control" name="gender">
                    <option {{ ($selectedGender == 'Male') ? 'selected' : '' }} value="Male">
                        {{ isset($data['backendlang']['backendlang']['Male']) ? $data['backendlang']['backendlang']['Male'] :'' }}
                    </option>
                    <option {{ ($selectedGender == 'Female') ? 'selected' : '' }} value="Female">
                        {{ isset($data['backendlang']['backendlang']['Female']) ? $data['backendlang']['backendlang']['Female'] :'' }}
                    </option>
                </select>
            </div>
		</div>
	</div>
</div>

