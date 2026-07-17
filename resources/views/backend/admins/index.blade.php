@extends('layouts.admin_app')
@section('content')

@if($errors->any())
  <div class="alert alert-danger">{!! implode('<br/>', $errors->all(':message')) !!}</div>
@endif
<div class="custom-tab">
	<form class="form-horizontal" id="admin-profile" method="POST" action="{{ route('admin.admins.update', Auth::user()->id) }}" enctype="multipart/form-data">
	@csrf
	@method('PUT')
	<div class="container-box">
	    <nav>
	        <div class="nav nav-tabs" id="nav-tab" role="tablist">
	            <a class="nav-link active"
		           id="home-tab"
		           data-bs-toggle="tab"
		           href="#home"
		           role="tab"
		           aria-controls="home"
		           aria-selected="true"
		        >
	            	{{ isset($data['backendlang']['backendlang']['Company_Info']) ? $data['backendlang']['backendlang']['Company_Info'] :'' }}
	            </a>
	            <a class="nav-link"
	          	   id="profile-tab"
	          	   data-bs-toggle="tab"
	          	   href="#profile"
	          	   role="tab"
	          	   aria-controls="profile"
	          	   aria-selected="false"
	          	>
	            	{{ isset($data['backendlang']['backendlang']['Password']) ? $data['backendlang']['backendlang']['Password'] :'' }}
	            </a>
	            <a class="nav-link"
		           id="contact-tab"
		           data-bs-toggle="tab"
		           href="#contact"
		           role="tab"
		           aria-controls="contact"
		           aria-selected="false"
		        >
	            	{{ isset($data['backendlang']['backendlang']['Website_Setting_Logo']) ? $data['backendlang']['backendlang']['Website_Setting_Logo'] :'' }}
	            </a>
	            <a class="nav-link"
		           id="ordering-fulfillment-tab"
		           data-bs-toggle="tab"
		           href="#ordering-fulfillment"
		           role="tab"
		           aria-controls="ordering-fulfillment"
		           aria-selected="false"
		        >
	            	{{ isset($data['backendlang']['backendlang']['Ordering_Fulfilment']) ? $data['backendlang']['backendlang']['Ordering_Fulfilment'] :'' }}
	            </a>
	            <a class="nav-link"
		           id="payment-qrcode-tab"
		           data-bs-toggle="tab"
		           href="#payment-qrcode"
		           role="tab"
		           aria-controls="payment-qrcode"
		           aria-selected="false"
				   style="display: none !important;"
		        >
	            	{{ isset($data['backendlang']['backendlang']['PaymentQRCode']) ? $data['backendlang']['backendlang']['PaymentQRCode'] :'' }}
	            </a>
	        </div>
	    </nav>

	    <div class="tab-content pl-3 pt-2" id="nav-tabContent">
	        <div
		        class="tab-pane fade show active pt-3"
		        id="home"
		        role="tabpanel"
		        aria-labelledby="home-tab"
		    >
	        	<h4>{{ isset($data['backendlang']['backendlang']['General']) ? $data['backendlang']['backendlang']['General'] :'' }}</h4>
	        	<hr>
	        	<div class="form-group">
		            <div class="row">
		            	<div class="col-sm-6">
		            		<div class="form-group">
		            			<label>
		            				<b>{{ isset($data['backendlang']['backendlang']['Full_Name']) ? $data['backendlang']['backendlang']['Full_Name'] :'' }}</b>
		            			</label>
			                    <input class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['First_Name']) ? $data['backendlang']['backendlang']['First_Name'] :'' }}" name="f_name" value="{{ Auth::user()->f_name }}">
			                </div>
		            	</div>
		            	<!-- <div class="col-sm-6">
		            		<div class="form-group">
		            			<label>
		            				<b>Last Name</b>
		            			</label>
			                    <input class="form-control" placeholder="Last Name" name="l_name" value="{{ Auth::user()->l_name }}">
			                </div>
		            	</div> -->
		            </div>
	        	</div>

		        <div class="form-group">
		        	<div class="row">
		        		<div class="col-md-6">
		        			<div class="form-group">
		            			<label>
		            				<b>{{ isset($data['backendlang']['backendlang']['Company_Name']) ? $data['backendlang']['backendlang']['Company_Name'] :'' }}</b>
		            			</label>
			                    <input class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['Company_Name']) ? $data['backendlang']['backendlang']['Company_Name'] :'' }}" name="invoice_name" 
			                    	   value="{{ isset($setting) ? $setting->invoice_name : old('invoice_name') }}">
			                </div>
		        		</div>
		        	</div>
					<div class="row">
						<div class="col-md-6">
		        			<div class="form-group">
		            			<label>
		            				<b>{{ isset($data['backendlang']['backendlang']['Company_Registration_No']) ? $data['backendlang']['backendlang']['Company_Registration_No'] :'' }}</b>
		            			</label>
			                    <input class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['Company_Registration_No']) ? $data['backendlang']['backendlang']['Company_Registration_No'] :'' }}" name="company_registration_no" 
			                    	   value="{{ isset($setting) ? $setting->company_registration_no : old('company_registration_no') }}">
			                </div>
		        		</div>
					</div>
					<div class="row">
		        		<div class="col-md-6">
		        			<div class="form-group">
		            			<label>
		            				<b>{{ isset($data['backendlang']['backendlang']['Tin_No']) ? $data['backendlang']['backendlang']['Tin_No'] :'' }}</b>
		            			</label>
			                    <input class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['Tin_No']) ? $data['backendlang']['backendlang']['Tin_No'] :'' }}" name="tin_no" value="{{ isset($setting) ? $setting->tin_no : old('tin_no') }}">
			                </div>
		        		</div>
		        	</div>
		        </div>

				<h4>{{ isset($data['backendlang']['backendlang']['Bank_Info']) ? $data['backendlang']['backendlang']['Bank_Info'] :'' }}</h4>
	        	<hr>
				<div class="form-group">
		        	<div class="row">
		        		<div class="col-sm-6">
		        			<div class="form-group">
		            			<label>
		            				<b>{{ isset($data['backendlang']['backendlang']['Bank_Holder_Name']) ? $data['backendlang']['backendlang']['Bank_Holder_Name'] :'' }}</b>
		            			</label>
			                	<input type="text" class="form-control required-feild" name="bank_holder_name" value="{{ isset($setting) ? $setting->bank_holder_name : old('bank_holder_name') }}" placeholder="{{ isset($data['lang']['lang']['bank_holder_name']) ? $data['lang']['lang']['bank_holder_name'] :'银行户口号码'}}">
			                </div>
		        		</div>
					</div>
					<div class="row">
		        		<div class="col-sm-6">
		        			<div class="form-group">
		            			<label>
		            				<b>{{ isset($data['backendlang']['backendlang']['Bank_Name']) ? $data['backendlang']['backendlang']['Bank_Name'] :'' }}</b>
		            			</label>
								<select class="form-control" name="bank_name" style="height: auto;">
									<option value="">{{ isset($data['lang']['lang']['select_bank']) ? $data['lang']['lang']['select_bank'] :'选择银行'}}</option>
									@foreach($paymentBanks as $bank)
									<option value="{{ $bank->bank_name }}" {{ !empty($setting->bank_name) && $setting->bank_name == $bank->bank_name? 'selected': ''}}>
										{{ $bank->bank_name }}
									</option>
									@endforeach
								</select>
			                </div>
		        		</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
		        			<div class="form-group">
		            			<label>
		            				<b>{{ isset($data['backendlang']['backendlang']['Bank_Account']) ? $data['backendlang']['backendlang']['Bank_Account'] :'' }}</b>
		            			</label>
			                	<input type="text" class="form-control required-feild" name="bank_account" value="{{ isset($setting) ? $setting->bank_account_number : old('bank_account') }}" placeholder="{{ isset($data['lang']['lang']['bank_acc_no']) ? $data['lang']['lang']['bank_acc_no'] :'银行户口号码'}}" onkeypress="return isNumberKey(event)">
			                </div>
		        		</div>
		        	</div>
		        </div>

	        	<h4>{{ isset($data['backendlang']['backendlang']['Contact']) ? $data['backendlang']['backendlang']['Contact'] :'' }}</h4>
	        	<hr>
	        	<div class="form-group">
	        		<div class="row">
						<div class="col-sm-6">
	                        <div class="form-group position-relative has-icon-right">
	                          	<input
	                            	type="text"
	                            	class="form-control"
	                            	placeholder="Email"
	                            	name="contact_email"
	                            	value="{{ (!empty($setting)) ? $setting->contact_email : '' }}"
	                          	/>
	                          	<div class="form-control-icon">
	                            	<i class="bi bi-envelope"></i>
	                          	</div>
	                        </div>
						</div>
						<div class="col-sm-6">
	                        <div class="form-group position-relative has-icon-right">
	                          	<input
	                            	type="text"
	                            	class="form-control"
	                            	placeholder="Phone"
	                            	name="company_phone"
	                            	value="{{ (!empty($setting)) ? $setting->company_phone : '' }}"
	                            	onkeypress="return isNumberKey(event)"
	                          	/>
	                          	<div class="form-control-icon">
	                            	<i class="bi bi-phone"></i>
	                          	</div>
	                        </div>
						</div>
					</div>
	        	</div>

	        	<div class="form-group">
	        		<div class="row">
	        			<div class="col-sm-6">
			        		<textarea class="form-control" name="address" placeholder="{{ isset($data['backendlang']['backendlang']['Address']) ? $data['backendlang']['backendlang']['Address'] :'' }}">{{ (!empty($setting)) ? $setting->company_address : '' }}</textarea>
	        			</div>
	        			<div class="col-sm-6">
	                        <div class="form-group position-relative has-icon-right">
	                          	<input
	                            	type="text"
	                            	class="form-control"
	                            	placeholder="Whatsapp"
	                            	name="contact_whatsapp"
	                            	value="{{ (!empty($setting)) ? $setting->contact_whatsapp : '' }}"
	                            	onkeypress="return isNumberKey(event)"
	                          	/>
	                          	<div class="form-control-icon">
	                            	<i class="bi bi-whatsapp"></i>
	                          	</div>
	                        </div>
						</div>
	        		</div>
	        	</div>

	        	

	        	<div class="form-group">
		        	<h4>{{ isset($data['backendlang']['backendlang']['About_Us']) ? $data['backendlang']['backendlang']['About_Us'] :'' }}</h4>
		        	<hr>
		        	<textarea class="form-control" name="about_us" id="about_us">{!! (!empty($setting)) ? $setting->about_us : '' !!}</textarea>
	        	</div>

	        	{{-- <div class="form-group">
		        	<h4>{{ isset($data['backendlang']['backendlang']['FAQs']) ? $data['backendlang']['backendlang']['FAQs'] :'' }}</h4>
		        	<hr>
		        	<textarea class="form-control" name="setting_faqs_description" id="setting_faqs_description">{!! (!empty($setting)) ? $setting->faqs : '' !!}</textarea>
	        	</div> --}}
	        	<h4>{{ isset($data['backendlang']['backendlang']['Social_Link_Note']) ? $data['backendlang']['backendlang']['Social_Link_Note'] :'' }}</h4>
	        	<hr>
	        	<div class="form-group">
	        		<div class="row">
						<div class="col-sm-6">
	                        <div class="form-group position-relative has-icon-right">
	                          	<input
	                            	type="text"
	                            	class="form-control"
	                            	placeholder="Facebook"
	                            	name="facebook"
	                            	value="{{ (!empty($setting)) ? $setting->facebook : '' }}"
	                            	
	                          	/>
	                          	<div class="form-control-icon">
	                            	<i class="bi bi-facebook"></i>
	                          	</div>
	                        </div>
						</div>
						<div class="col-sm-6">
	                        <div class="form-group position-relative has-icon-right">
	                          	<input
	                            	type="text"
	                            	class="form-control"
	                            	placeholder="TikTok"
	                            	name="tiktok"
	                            	value="{{ (!empty($setting)) ? $setting->tiktok : '' }}"
	                            	
	                          	/>
	                          	<div class="form-control-icon">
	                            	<i class="fab fa-tiktok"></i>
	                          	</div>
	                        </div>
						</div>
					</div>
	        	</div>
	        	<div class="form-group">
	        		<div class="row">
						<div class="col-sm-6">
	                        <div class="form-group position-relative has-icon-right">
	                          	<input
	                            	type="text"
	                            	class="form-control"
	                            	placeholder="Instagram"
	                            	name="instagram"
	                            	value="{{ (!empty($setting)) ? $setting->instagram : '' }}"
	                            	
	                          	/>
	                          	<div class="form-control-icon">
	                            	<i class="bi bi-instagram"></i>
	                          	</div>
	                        </div>
						</div>
						<div class="col-sm-6">
	                        <div class="form-group position-relative has-icon-right">
	                          	<input
	                            	type="text"
	                            	class="form-control"
	                            	placeholder="Youtube"
	                            	name="youtube"
	                            	value="{{ (!empty($setting)) ? $setting->youtube : '' }}"
	                            	
	                          	/>
	                          	<div class="form-control-icon">
	                            	<i class="bi bi-youtube"></i>
	                          	</div>
	                        </div>
						</div>
					</div>
	        	</div>
	        	<div class="form-group">
	        		<div class="row">
						<div class="col-sm-6">
	                        <div class="form-group position-relative has-icon-right">
	                          	<input
	                            	type="text"
	                            	class="form-control"
	                            	placeholder="Google"
	                            	name="google"
	                            	value="{{ (!empty($setting)) ? $setting->google : '' }}"
	                            	
	                          	/>
	                          	<div class="form-control-icon">
	                            	<i class="bi bi-google"></i>
	                          	</div>
	                        </div>
						</div>
						<div class="col-sm-6">
	                        <div class="form-group position-relative has-icon-right">
	                          	<input
	                            	type="text"
	                            	class="form-control"
	                            	placeholder="Book"
	                            	name="book"
	                            	value="{{ (!empty($setting)) ? $setting->book : '' }}"
	                            	
	                          	/>
	                          	<div class="form-control-icon">
	                            	<i class="bi bi-book"></i>
	                          	</div>
	                        </div>
						</div>
					</div>
	        	</div>
				<div class="form-group">
	        		<div class="row">
						<div class="col-sm-6">
	                        <div class="form-group position-relative has-icon-right">
	                          	<input
	                            	type="text"
	                            	class="form-control"
	                            	placeholder="Twitter"
	                            	name="twitter"
	                            	value="{{ (!empty($setting)) ? $setting->twitter : '' }}"
	                            	
	                          	/>
	                          	<div class="form-control-icon">
	                            	<i class="bi bi-twitter"></i>
	                          	</div>
	                        </div>
						</div>
					</div>
	        	</div>
	        </div>
	        <div
		        class="tab-pane fade pt-3"
		        id="profile"
		        role="tabpanel"
		        aria-labelledby="profile-tab">
	            <div class="form-group">
		        	<h4>{{ isset($data['backendlang']['backendlang']['Change_New_Password']) ? $data['backendlang']['backendlang']['Change_New_Password'] :'' }}</h4>
		        	<hr>
	        	</div>
	        	<div class="form-group">
		        	<input type="password" name="password" class="form-control" id="form-field-pass1" placeholder="{{ isset($data['backendlang']['backendlang']['New_Password']) ? $data['backendlang']['backendlang']['New_Password'] :'' }}" />
		        </div>
	        	<div class="form-group">
		        	<input type="password" name="password_confirmation" class="form-control" id="form-field-pass2" placeholder="{{ isset($data['backendlang']['backendlang']['Change_New_Password']) ? $data['backendlang']['backendlang']['Change_New_Password'] :'' }}" />
		        </div>
	        </div>
	        <div
		        class="tab-pane fade pt-3"
		        id="contact"
		        role="tabpanel"
		        aria-labelledby="contact-tab">
	            <div class="form-group">
					<h4>
						{{ isset($data['backendlang']['backendlang']['Website_Logo']) ? $data['backendlang']['backendlang']['Website_Logo'] :'' }}
					</h4>
					<hr>
					<div class="row">
						<div class="col-sm-12">
							<input type="file" name="website_logo" class="form-control">
							<br>
							@if(!empty($setting->website_logo))
								<img src="{{ asset($setting->website_logo) }}" style="width: 100px; background-color: rgba(0, 0, 0, 0.5);">
							@endif
						</div>
					</div>
	            </div>

	            <div class="form-group">
					<h4>
						{{ isset($data['backendlang']['backendlang']['Website_Name']) ? $data['backendlang']['backendlang']['Website_Name'] :'' }}
					</h4>
					<hr>
					<div class="row">
						<div class="col-sm-12">
							<input type="text" class="form-control" name="website_name" value="{{ !empty($setting) ? $setting->website_name : '' }}">
						</div>
					</div>
	            </div>
				<div class="form-group">
					<h4>
						{{ isset($data['backendlang']['backendlang']['Profile_Logo']) ? $data['backendlang']['backendlang']['Profile_Logo'] :'' }}
					</h4>
					<hr>
					<div class="row">
						<div class="col-sm-12">
							<input type="file" name="profile_logo" class="form-control">
							<br>
							@if(!empty($admin->profile_logo))
							<img src="{{ asset($admin->profile_logo) }}" style="width: 100px; background-color: rgba(0, 0, 0, 0.5);">
							@endif
						</div>
					</div>
				</div>
				<div class="form-group">
					<h4>
						{{ isset($data['backendlang']['backendlang']['Website_Fav_Icon']) ? $data['backendlang']['backendlang']['Website_Fav_Icon'] :'' }}
					</h4>
					<hr>
					<div class="row">
						<div class="col-sm-12">
							<input type="file" name="fav_icon" class="form-control">
							<br>
							@if(!empty($setting->fav_icon))
							<img src="{{ asset($setting->fav_icon) }}" style="width: 100px; background-color: rgba(0, 0, 0, 0.5);">
							@endif
						</div>
					</div>
				</div>
	        </div>
	        <div
		        class="tab-pane fade pt-3"
		        id="ordering-fulfillment"
		        role="tabpanel"
		        aria-labelledby="ordering-fulfillment-tab">

	        	<div class="form-group">
		        	<h4>{{ isset($data['backendlang']['backendlang']['Privacy_Policy']) ? $data['backendlang']['backendlang']['Privacy_Policy'] :'' }}</h4>
		        	<hr>
		        	<textarea class="form-control" name="privacy_policy_description" id="privacy_policy_description">{!! (!empty($setting)) ? $setting->privacy_policy_description : '' !!}</textarea>
	        	</div><br>

				<div class="form-group">
		        	<h4>{{ isset($data['backendlang']['backendlang']['Privacy_Policy_CN']) ? $data['backendlang']['backendlang']['Privacy_Policy_CN'] :'' }}</h4>
		        	<hr>
		        	<textarea class="form-control" name="privacy_policy_description_cn" id="privacy_policy_description_cn">{!! (!empty($setting)) ? $setting->privacy_policy_description_cn : '' !!}</textarea>
	        	</div><br>

	        	<div class="form-group">
		        	<h4>{{ isset($data['backendlang']['backendlang']['Return_Policy']) ? $data['backendlang']['backendlang']['Return_Policy'] :'' }}</h4>
		        	<hr>
		        	<textarea class="form-control" name="return_policy_description" id="return_policy_description">{!! (!empty($setting)) ? $setting->return_policy_description : '' !!}</textarea>
	        	</div><br>

				<div class="form-group">
		        	<h4>{{ isset($data['backendlang']['backendlang']['Return_Policy_CN']) ? $data['backendlang']['backendlang']['Return_Policy_CN'] :'' }}</h4>
		        	<hr>
		        	<textarea class="form-control" name="return_policy_description_cn" id="return_policy_description_cn">{!! (!empty($setting)) ? $setting->return_policy_description_cn : '' !!}</textarea>
	        	</div><br>

	        	<div class="form-group">
		        	<h4>{{ isset($data['backendlang']['backendlang']['Shipping_Policy']) ? $data['backendlang']['backendlang']['Shipping_Policy'] :'' }}</h4>
		        	<hr>
		        	<textarea class="form-control" name="shipping_policy_description" id="shipping_policy_description">{!! (!empty($setting)) ? $setting->shipping_policy_description : '' !!}</textarea>
	        	</div><br>

				<div class="form-group">
		        	<h4>{{ isset($data['backendlang']['backendlang']['Shipping_Policy_CN']) ? $data['backendlang']['backendlang']['Shipping_Policy_CN'] :'' }}</h4>
		        	<hr>
		        	<textarea class="form-control" name="shipping_policy_description_cn" id="shipping_policy_description_cn">{!! (!empty($setting)) ? $setting->shipping_policy_description_cn : '' !!}</textarea>
	        	</div><br>

	        	<div class="form-group">
		        	<h4>{{ isset($data['backendlang']['backendlang']['Terms_Conditions']) ? $data['backendlang']['backendlang']['Terms_Conditions'] :'' }}</h4>
		        	<hr>
		        	<textarea class="form-control" name="tnc_description" id="tnc_description">{!! (!empty($setting)) ? $setting->tnc_description : '' !!}</textarea>
	        	</div><br>

				<div class="form-group">
		        	<h4>{{ isset($data['backendlang']['backendlang']['Terms_Conditions_CN']) ? $data['backendlang']['backendlang']['Terms_Conditions_CN'] :'' }}</h4>
		        	<hr>
		        	<textarea class="form-control" name="tnc_description_cn" id="tnc_description_cn">{!! (!empty($setting)) ? $setting->tnc_description_cn : '' !!}</textarea>
	        	</div><br>

				<div class="form-group">
		        	<h4>{{ isset($data['backendlang']['backendlang']['Invoice_Notes']) ? $data['backendlang']['backendlang']['Invoice_Notes'] :'' }}</h4>
		        	<hr>
		        	<textarea class="form-control" name="invoice_notes" id="invoice_notes">{!! (!empty($setting)) ? $setting->invoice_notes : '' !!}</textarea>
	        	</div><br>

				<div class="form-group">
		        	<h4>{{ isset($data['backendlang']['backendlang']['Invoice_Notes_CN']) ? $data['backendlang']['backendlang']['Invoice_Notes_CN'] :'' }}</h4>
		        	<hr>
		        	<textarea class="form-control" name="invoice_notes_cn" id="invoice_notes_cn">{!! (!empty($setting)) ? $setting->invoice_notes_cn : '' !!}</textarea>
	        	</div>
	        </div>
	        <div
		        class="tab-pane fade pt-3"
		        id="payment-qrcode"
		        role="tabpanel"
		        aria-labelledby="payment-qrcode-tab">

	        	<div class="form-group">
		        	<h4>{{ isset($data['backendlang']['backendlang']['PaymentQRCode']) ? $data['backendlang']['backendlang']['PaymentQRCode'] :'' }}</h4>
		        	<hr>
		        	<div class="row">
						<div class="col-sm-12" align="center">
							@php
								$logo_image = !empty($data['web_setting']->website_logo) ? asset($data['web_setting']->website_logo) : asset('images/images.png');
							@endphp
							
							<canvas id="qr-customer"></canvas>
							<br>
							<a class="btn btn-primary btn-sm" id="save-two" style="color: white;">
			        	<i class="fa fa-download" style="font-size:20px"></i> {{ isset($data['backendlang']['backendlang']['DownloadQRCode']) ? $data['backendlang']['backendlang']['DownloadQRCode'] :'' }}
			        </a>
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
        <a href="{{ route('member.members.index') }}" class="btn btn-outline-danger">
            <i class="bi bi-ban"> {{ isset($data['backendlang']['backendlang']['Cancel']) ? $data['backendlang']['backendlang']['Cancel'] :'' }}</i>
        </a>

        <button class="btn btn-outline-primary">
            <i class="bi bi-check"> {{ isset($data['backendlang']['backendlang']['Save_Changes']) ? $data['backendlang']['backendlang']['Save_Changes'] :'' }}</i>
        </button>

    </div>
</div>
@endsection

@section('js')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
<script type="text/javascript">



    
	$('.submit-form-btn .btn-outline-primary').click( function(e){
       e.preventDefault();
       $('.loading-gif').show();
       $('#admin-profile').submit();
    });


	var about_usUrl = '{{ route("CKEditorUploadImage", ["_token" => csrf_token()]) }}';

	var about_us = CKEDITOR.instances["about_us"];

    if(!about_us){
      	CKEDITOR.replace( 'about_us',{
          	filebrowserUploadUrl: about_usUrl,
          	filebrowserUploadMethod: 'form'
      	});
  	}

  	var setting_faqs_descriptionUrl = '{{ route("CKEditorUploadImage", ["_token" => csrf_token()]) }}';

	// var setting_faqs_description = CKEDITOR.instances["setting_faqs_description"];

    // if(!setting_faqs_description){
    //   	CKEDITOR.replace( 'setting_faqs_description',{
    //       	filebrowserUploadUrl: setting_faqs_descriptionUrl,
    //       	filebrowserUploadMethod: 'form'
    //   	});
  	// }

  	var privacy_policy_description = CKEDITOR.instances["privacy_policy_description"];

	if(!privacy_policy_description){
	    CKEDITOR.replace( 'privacy_policy_description',{
	        filebrowserUploadUrl: about_usUrl,
	        filebrowserUploadMethod: 'form'
	    });
	}

	var privacy_policy_description_cn = CKEDITOR.instances["privacy_policy_description_cn"];

	if(!privacy_policy_description_cn){
	    CKEDITOR.replace( 'privacy_policy_description_cn',{
	        filebrowserUploadUrl: about_usUrl,
	        filebrowserUploadMethod: 'form'
	    });
	}

	var return_policy_description = CKEDITOR.instances["return_policy_description"];

	if(!return_policy_description){
	    CKEDITOR.replace( 'return_policy_description',{
	        filebrowserUploadUrl: about_usUrl,
	        filebrowserUploadMethod: 'form'
	    });
	}

	var return_policy_description = CKEDITOR.instances["return_policy_description_cn"];

	if(!return_policy_description){
	    CKEDITOR.replace( 'return_policy_description_cn',{
	        filebrowserUploadUrl: about_usUrl,
	        filebrowserUploadMethod: 'form'
	    });
	}

	var shipping_policy_description = CKEDITOR.instances["shipping_policy_description"];

	if(!shipping_policy_description){
	    CKEDITOR.replace( 'shipping_policy_description',{
	        filebrowserUploadUrl: about_usUrl,
	        filebrowserUploadMethod: 'form'
	    });
	}

	var shipping_policy_description = CKEDITOR.instances["shipping_policy_description_cn"];

	if(!shipping_policy_description){
	    CKEDITOR.replace( 'shipping_policy_description_cn',{
	        filebrowserUploadUrl: about_usUrl,
	        filebrowserUploadMethod: 'form'
	    });
	}

	var tnc_description = CKEDITOR.instances["tnc_description"];

	if(!tnc_description){
	    CKEDITOR.replace( 'tnc_description',{
	        filebrowserUploadUrl: about_usUrl,
	        filebrowserUploadMethod: 'form'
	    });
	}

	var tnc_description = CKEDITOR.instances["tnc_description_cn"];

	if(!tnc_description){
	    CKEDITOR.replace( 'tnc_description_cn',{
	        filebrowserUploadUrl: about_usUrl,
	        filebrowserUploadMethod: 'form'
	    });
	}

	var invoice_notes = CKEDITOR.instances["invoice_notes"];

	if(!invoice_notes){
	    CKEDITOR.replace( 'invoice_notes',{
	        filebrowserUploadUrl: about_usUrl,
	        filebrowserUploadMethod: 'form'
	    });
	}

	var invoice_notes = CKEDITOR.instances["invoice_notes_cn"];

	if(!invoice_notes){
	    CKEDITOR.replace( 'invoice_notes_cn',{
	        filebrowserUploadUrl: about_usUrl,
	        filebrowserUploadMethod: 'form'
	    });
	}
</script>


@endsection