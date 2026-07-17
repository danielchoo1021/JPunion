<div class="row">
	<div class="col-12">
		<div class="form-group">
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label>{{ isset($data['backendlang']['backendlang']['Full_Name']) ? $data['backendlang']['backendlang']['Full_Name'] :'' }} <span class="important-text">*</span></label>
						<input type="text" class="form-control required-field" name="f_name" placeholder="{{ isset($data['backendlang']['backendlang']['Full_Name']) ? $data['backendlang']['backendlang']['Full_Name'] :'' }} *" value="{{ isset($staff) ? $staff->f_name : old('f_name') }}">
					</div>
				</div>

				<div class="col-md-6">
					<div class="form-group">
						<label>{{ isset($data['backendlang']['backendlang']['Email']) ? $data['backendlang']['backendlang']['Email'] :'' }} <span class="important-text">*</span></label>
						<input type="email" class="form-control required-field" name="email" placeholder="{{ isset($data['backendlang']['backendlang']['Email']) ? $data['backendlang']['backendlang']['Email'] :'' }}" value="{{ isset($staff) ? $staff->email : old('email') }}" >
						<!-- {{ isset($staff) ? 'readonly' : '' }} -->
					</div>
				</div>

				<div class="col-md-6">
					<div class="form-group">
						<label>{{ isset($data['backendlang']['backendlang']['Password']) ? $data['backendlang']['backendlang']['Password'] :'' }} <span class="important-text">*</span></label>
						@if(isset($staff))
						<br>
						<button type="button" class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#myModal">
							<i class="fa fa-key"></i> {{ isset($data['backendlang']['backendlang']['Change_New_Password']) ? $data['backendlang']['backendlang']['Change_New_Password'] :'' }}
						</button>
						@else
						<input type="password" class="form-control required-field" name="password" placeholder="{{ isset($data['backendlang']['backendlang']['Password_(min 6 character)']) ? $data['backendlang']['backendlang']['Password_(min 6 character)'] :'' }}" value="{{ isset($staff) ? $staff->password : old('password') }}">
						@endif
					</div>
				</div>

				<div class="col-md-6">
					<div class="form-group">
						<label>{{ isset($data['backendlang']['backendlang']['NRIC_no']) ? $data['backendlang']['backendlang']['NRIC_no'] :'' }} <span class="important-text">*</span></label>
						<input type="text" class="form-control required-field" name="ic" placeholder="{{ isset($data['backendlang']['backendlang']['NRIC_no']) ? $data['backendlang']['backendlang']['NRIC_no'] :'' }}" value="{{ isset($staff) ? $staff->ic : old('ic') }}">
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
			                    <input type="text" class="form-control required-feild" placeholder="{{ isset($data['backendlang']['backendlang']['Phone']) ? $data['backendlang']['backendlang']['Phone'] :'' }}" name="phone"  value="{{ isset($staff) ? $staff->phone : old('phone') }}">
			                </div>
						</div>
					</div>
					
					<div class="col-6">
						<div class="form-group">
						<label>{{ isset($data['backendlang']['backendlang']['Job_Position']) ? $data['backendlang']['backendlang']['Job_Position'] :'' }}</span></label>
						<input type="text" class="form-control required-field" name="job" placeholder="{{ isset($data['backendlang']['backendlang']['Job_Position']) ? $data['backendlang']['backendlang']['Job_Position'] :'' }}" value="{{ isset($staff) ? $staff->job : old('job') }}">
					</div>
					</div>
				</div>
			</div>
			<hr>
			@if(Auth::guard('admin')->check())
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label>{{ isset($data['backendlang']['backendlang']['Set_Staff_Permission_Level']) ? $data['backendlang']['backendlang']['Set_Staff_Permission_Level'] :'' }}</label>
						@php
							$selectedPMValue = (isset($staff)) ? $staff->permission_lvl : old('permission_lvl');
						@endphp
						<select class="form-control" name="permission_lvl">
						<option value="">{{ isset($data['backendlang']['backendlang']['Search_Permission_Level']) ? $data['backendlang']['backendlang']['Search_Permission_Level'] :'' }}</option>
						<option {{ (!empty(request('perm_lvl')) && request('perm_lvl') == '1') ? 'selected' : '' }} value="1">{{ isset($data['backendlang']['backendlang']['Super_Admin']) ? $data['backendlang']['backendlang']['Super_Admin'] :'' }}</option>
						<option {{ (!empty(request('perm_lvl')) && request('perm_lvl') == '2') ? 'selected' : '' }} value="2">{{ isset($data['backendlang']['backendlang']['Admin']) ? $data['backendlang']['backendlang']['Admin'] :'' }}</option>
						<option {{ (!empty(request('perm_lvl')) && request('perm_lvl') == '3') ? 'selected' : '' }} value="3">{{ isset($data['backendlang']['backendlang']['Warehouse']) ? $data['backendlang']['backendlang']['Warehouse'] :'' }}</option>
						<option {{ (!empty(request('perm_lvl')) && request('perm_lvl') == '4') ? 'selected' : '' }} value="4">{{ isset($data['backendlang']['backendlang']['Finance']) ? $data['backendlang']['backendlang']['Finance'] :'' }}</option>
						<option {{ (!empty(request('perm_lvl')) && request('perm_lvl') == '5') ? 'selected' : '' }} value="5">{{ isset($data['backendlang']['backendlang']['Logistic']) ? $data['backendlang']['backendlang']['Logistic'] :'' }}</option>
						<option {{ (!empty(request('perm_lvl')) && request('perm_lvl') == '6') ? 'selected' : '' }} value="6">{{ isset($data['backendlang']['backendlang']['IT_Team']) ? $data['backendlang']['backendlang']['IT_Team'] :'' }}</option>
						<option {{ (!empty(request('perm_lvl')) && request('perm_lvl') == '7') ? 'selected' : '' }} value="7">{{ isset($data['backendlang']['backendlang']['Shareholder']) ? $data['backendlang']['backendlang']['Shareholder'] :'' }}</option>
						</select>
					</div>
				</div>
			</div>
			@endif
		</div>
	</div>
</div>

