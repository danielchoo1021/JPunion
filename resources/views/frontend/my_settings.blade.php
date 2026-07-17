@extends('layouts.app')

@section('content')

@include('partial.frontend.profile_header')
<form method="POST" action="{{ route('profile') }}" enctype="multipart/form-data">
	@csrf
	<div class="profile-content pb-5">
		<div class="container">
			<div class="form-group">
				@if($errors->any())
	              <div class="alert alert-danger">{!! implode('<br/>', $errors->all(':message')) !!}</div>
	            @endif
	        </div>
			<div class="container-box">
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label>{{ isset($data['lang']['lang']['complete_name']) ? $data['lang']['lang']['complete_name'] :'全名'}}</label>
							@if(!empty(Auth::user()->l_name))
							<input type="text" name="f_name" value="{{ Auth::user()->f_name }} {{ Auth::user()->l_name }}" class="form-control" readonly>
							@else
							<input type="text" name="f_name" value="{{ Auth::user()->f_name }}" class="form-control" readonly>
							@endif
						</div>
					</div>

					

					<div class="col-md-4">
						<div class="form-group">
							<label>{{ isset($data['lang']['lang']['phone_number']) ? $data['lang']['lang']['phone_number'] :'手机号'}}</label>
							<div class="row">
								@php
			                		$CountryContact = (isset(Auth::user()->country_code)) ? Auth::user()->country_code : '60';

									if(Auth::user()->phone){
										$phone = Auth::user()->phone;
										
										if(Auth::user()->country_code == '60'){
											$phone = ($phone[0] == '0') ? $phone : '0'.$phone;
										}else{
											$phone = ($phone[0] == '0') ? substr($phone, 1) : $phone;
										}
									}else{
										$phone = '';
									}
			                	@endphp
								<div class="col-6">
									<select class="form-control select2 country_code" name="country_code" id="country_code" data-live-search="true" style="padding: 0.375rem 0.75rem;">
									@foreach($countries as $country)
	                                    <option {{ ($CountryContact == $country->country_contact) ? 'selected' : '' }} value="{{ $country->country_contact }}">(+{{ $country->country_contact }}) {{ $country->country_name }} </option>
	                                    @endforeach
	                                </select>
								</div>
								<div class="col-6">
									{{-- <input type="text" name="phone" value="{{ Auth::user()->phone }}" class="form-control"> --}}
									<input type="text" name="phone" value="{{ $phone }}" class="form-control">
								</div>
							</div>
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label>{{ isset($data['lang']['lang']['gender']) ? $data['lang']['lang']['gender'] :'性别'}}</label>
							<select class="form-control" name="gender" style="padding: 0.375rem 0.75rem;">
								@if(empty(Auth::user()->gender))
								<option value="">Please select gender</option>
								@endif
								<option {{ Auth::user()->gender == 'Male' ? 'selected' : '' }} value="Male">{{ isset($data['lang']['lang']['male']) ? $data['lang']['lang']['male'] :'男'}}</option>
								<option {{ Auth::user()->gender == 'Female' ? 'selected' : '' }} value="Female">{{ isset($data['lang']['lang']['female']) ? $data['lang']['lang']['female'] :'女'}}</option>
							</select>
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label>{{ isset($data['lang']['lang']['email_address']) ? $data['lang']['lang']['email_address'] :'电子邮件'}}</label>
							<input type="text" name="email" value="{{ Auth::user()->email }}" class="form-control">
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<label>{{ isset($data['lang']['lang']['ic_no']) ? $data['lang']['lang']['ic_no'] :'身份证'}} (NRIC No)</label>
							<input type="text" value="{{ Auth::user()->ic }}" placeholder="身份证 (NRIC No)" class="form-control" readonly>
						</div>
					</div>

					<!-- <div class="col-md-4">
						<div class="form-group">
	                        <label>{{ isset($data['lang']['lang']['select_display_language']) ? $data['lang']['lang']['select_display_language'] :'请选择显示语言'}}: <span class="important-text">*</span></label>
	                        <select class="form-control" name="prefer_language" style="padding: 0.375rem 0.75rem;">
	                            <option value="">
	                                -
	                            </option>
	                            <option {{ Auth::user()->prefer_language == '1' ? 'selected' : '' }} value="1">
	                                {{ isset($data['lang']['lang']['chinese']) ? $data['lang']['lang']['chinese'] :'中文'}}
	                            </option>
	                            <option {{ Auth::user()->prefer_language == '2' ? 'selected' : '' }} value="2">
	                                {{ isset($data['lang']['lang']['english']) ? $data['lang']['lang']['english'] :'English'}}
	                            </option>
	                        </select>
	                    </div>
					</div> -->
				</div>

				<div class="form-group">
					<div class="row">
						<div class="col-md-4">
							<label>{{ isset($data['lang']['lang']['profile_picture']) ? $data['lang']['lang']['profile_picture'] :'个人头像'}}</label>
							<input type="file" class="form-control" name="profile_logo">
							@if(!empty(Auth::user()->profile_logo))
							<img src="{{ asset(Auth::user()->profile_logo) }}" width="100px">
							@endif
						</div>
					</div>
				</div>

				<div class="form-group">
					<button class="btn btn-primary btn-sm set_button set_text">
						<i class="fa fa-check"></i> {{ isset($data['lang']['lang']['save_changes']) ? $data['lang']['lang']['save_changes'] :'保存'}}
					</button>
				</div>
			</div>
		</div>
	</div>
</form>
@endsection

@section('js')
<script type="text/javascript">
	$('.select2').select2();
</script>
@endsection