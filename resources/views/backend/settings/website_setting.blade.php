@extends('layouts.admin_app')
@section('css')
<style type="text/css">
	/* The switch - the box around the slider */
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
@endsection
@section('content')
<form method="POST" action="{{ route('save_website_setting') }}" id='setting-merchant-form'>
@csrf
<div class="form-group container-box">

	<div class="form-group">
		@if(Auth::user()->permission_lvl == 1)
		<h3>{{ isset($data['backendlang']['backendlang']['Website_Setting']) ? $data['backendlang']['backendlang']['Website_Setting'] :'' }}</h3>
		<hr>
		<div class="col-md-12">
			<div class="form-group container-box">
				<div class="row">
					<div class="col-6">
						<span style="font-size: 20px; color: #000;">{{ isset($data['backendlang']['backendlang']['Authorise_Merchant']) ? $data['backendlang']['backendlang']['Authorise_Merchant'] :'' }}</span>
					</div>
					<div class="col-6" align="right">
						<label class="switch">
						  	<input type="checkbox" name="authorise_enable" {{ ($data['website_setting']->authorise_enable == 1) ? 'checked' : '' }}>
						  	<span class="slider round"></span>
						</label>
					</div>
				</div>
			</div>

			<div class="form-group container-box">
					<div class="row">
						<div class="col-6">
							<span style="font-size: 20px; color: #000;">{{ isset($data['backendlang']['backendlang']['Setting_Sold_Display_Product']) ? $data['backendlang']['backendlang']['Setting_Sold_Display_Product'] :'' }}</span>
						</div>
						<div class="col-6" align="right">
							<label class="switch">
							  	<input type="checkbox" name="setting_sold_display_product" {{ (!empty($setting->id) && $setting->setting_sold_display_product == 1) ? 'checked' : '' }}>
							  	<span class="slider round"></span>
							</label>
						</div>
					</div>
				</div>
			</div>
		</div>
		@endif
	</div>

	<div class="form-group container-box">
		<h3>{{ isset($data['backendlang']['backendlang']['Registration_Setting']) ? $data['backendlang']['backendlang']['Registration_Setting'] :'' }}</h3>
		<hr>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group container-box">
					<div class="row">
						<div class="col-6">
							<span style="font-size: 20px; color: #000;">{{ isset($data['backendlang']['backendlang']['Setting_Registration_Product']) ? $data['backendlang']['backendlang']['Setting_Registration_Product'] :'' }}</span>
						</div>
						<div class="col-6" align="right">
							<label class="switch">
							  	<input type="checkbox" name="registration_product_enable" {{ (!empty($setting->id) && $setting->registration_product_enable == 1) ? 'checked' : '' }}>
							  	<span class="slider round"></span>
							</label>
						</div>
					</div>
				</div>
			</div>
		</div>
	
		<div class="form-group container-box">
			<div class="row">
				<div class="col-6">
					<span style="font-size: 20px; color: #000;">{{ isset($data['backendlang']['backendlang']['Hierarchy_Bonus']) ? $data['backendlang']['backendlang']['Hierarchy_Bonus'] :'' }}</span>
				</div>
				<div class="col-6" align="right">
					<label class="switch">
						<input type="checkbox" name="registration_package_hierarchy_bonus" {{ (!empty($setting->id) && $setting->registration_package_hierarchy_bonus == 1) ? 'checked' : '' }}>
						<span class="slider round"></span>
					</label>
				</div>
			</div>
		</div>
	</div>

	<div class="form-group">
		<h3>{{ isset($data['backendlang']['backendlang']['Bonus_Setting']) ? $data['backendlang']['backendlang']['Bonus_Setting'] :'' }}</h3>
		<hr>
		<div class="row">
			<div class="col-md-6">
				<div class="container-box">
					<div class="form-group container-box">
						<div class="row">
							<div class="col-6">
								<span style="font-size: 20px; color: #000; font-weight: bold;">{{ isset($data['backendlang']['backendlang']['Agent_Only']) ? $data['backendlang']['backendlang']['Agent_Only'] :'' }}</span>
							</div>
							<div class="col-6" align="right">
								<label class="switch">
								  	<input type="checkbox" name="bonus_agent_enable" {{ (!empty($setting->id) && $setting->bonus_agent_enable == 1) ? 'checked' : '' }}>
								  	<span class="slider round"></span>
								</label>
							</div>
						</div>
					</div>
					<div class="form-group container-box">
						<div class="row">
							<div class="col-6">
								<li style="font-size: 20px; color: #000;">{{ isset($data['backendlang']['backendlang']['Order_Rebate']) ? $data['backendlang']['backendlang']['Order_Rebate'] :'' }}</li>
							</div>
							<div class="col-6" align="right">
								<label class="switch">
								  	<input type="checkbox" name="agent_rebate_enable" {{ (!empty($setting->id) && $setting->agent_rebate_enable == 1) ? 'checked' : '' }}>
								  	<span class="slider round"></span>
								</label>
							</div>
						</div>
					</div>

					<div class="form-group container-box">
						<div class="row">
							<div class="col-6">
								<li style="font-size: 20px; color: #000;"> {{ isset($data['backendlang']['backendlang']['Hierarchy_Bonus']) ? $data['backendlang']['backendlang']['Hierarchy_Bonus'] :'' }}</li>
							</div>
							<div class="col-6" align="right">
								<label class="switch">
								  	<input type="checkbox" name="hierarchy_enable" {{ (!empty($setting->id) && $setting->hierarchy_enable == 1) ? 'checked' : '' }}>
								  	<span class="slider round"></span>
								</label>
							</div>
						</div>
					</div>

					<div class="form-group container-box">
						<div class="row">
							<div class="col-6">
								<li style="font-size: 20px; color: #000;">{{ isset($data['backendlang']['backendlang']['Referral_Reward']) ? $data['backendlang']['backendlang']['Referral_Reward'] :'' }}</li>
							</div>
							<div class="col-6" align="right">
								<label class="switch">
								  	<input type="checkbox" name="referral_enable" {{ (!empty($setting->id) && $setting->referral_enable == 1) ? 'checked' : '' }}>
								  	<span class="slider round"></span>
								</label>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="container-box">
				<div class="form-group container-box">
					<div class="row">
						<div class="col-6">
							<span style="font-size: 20px; color: #000; font-weight: bold;">{{ isset($data['backendlang']['backendlang']['Member_Only']) ? $data['backendlang']['backendlang']['Member_Only'] :'' }}</span>
						</div>
						<div class="col-6" align="right">
							<label class="switch">
							  	<input type="checkbox" name="bonus_member_enable" {{ (!empty($setting->id) && $setting->bonus_member_enable == 1) ? 'checked' : '' }}>
							  	<span class="slider round"></span>
							</label>
						</div>
					</div>
				</div>
				<div class="form-group container-box">
						<div class="row">
							<div class="col-6">
								<li style="font-size: 20px; color: #000;">{{ isset($data['backendlang']['backendlang']['Order_Rebate']) ? $data['backendlang']['backendlang']['Order_Rebate'] :'' }}</li>
							</div>
							<div class="col-6" align="right">
								<label class="switch">
								  	<input type="checkbox" name="member_rebate_enable" {{ (!empty($setting->id) && $setting->member_rebate_enable == 1) ? 'checked' : '' }}>
								  	<span class="slider round"></span>
								</label>
							</div>
						</div>
					</div>

					<div class="form-group container-box">
						<div class="row">
							<div class="col-6">
								<li style="font-size: 20px; color: #000;">{{ isset($data['backendlang']['backendlang']['Hierarchy_Bonus']) ? $data['backendlang']['backendlang']['Hierarchy_Bonus'] :'' }}</li>
							</div>
							<div class="col-6" align="right">
								<label class="switch">
								  	<input type="checkbox" name="member_hierarchy_enable" {{ (!empty($setting->id) && $setting->member_hierarchy_enable == 1) ? 'checked' : '' }}>
								  	<span class="slider round"></span>
								</label>
							</div>
						</div>
					</div>

					<div class="form-group container-box">
						<div class="row">
							<div class="col-6">
								<li style="font-size: 20px; color: #000;">{{ isset($data['backendlang']['backendlang']['Referral_Reward']) ? $data['backendlang']['backendlang']['Referral_Reward'] :'' }}</li>
							</div>
							<div class="col-6" align="right">
								<label class="switch">
								  	<input type="checkbox" name="member_referral_enable" {{ (!empty($setting->id) && $setting->member_referral_enable == 1) ? 'checked' : '' }}>
								  	<span class="slider round"></span>
								</label>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-12">
				<br>
				<span style="font-weight: bold; font-size: 15px; color: #000; display: block; width: 100%; border-bottom: 1px solid #ddd; margin-bottom: 10px;">
					{{ isset($data['backendlang']['backendlang']['Topup_Bonus_Setting']) ? $data['backendlang']['backendlang']['Topup_Bonus_Setting'] :'' }}
				</span>
			</div>
			<div class="col-md-6">
				<div class="form-group container-box">
					<div class="row">
						<div class="col-6">
							<span style="font-size: 20px; color: #000;">{{ isset($data['backendlang']['backendlang']['Topup_Bonus_Point']) ? $data['backendlang']['backendlang']['Topup_Bonus_Point'] :'' }}</span>
						</div>
						<div class="col-6" align="right">
							<label class="switch">
							  	<input type="checkbox" name="topup_bonus_pv_enable" {{ (!empty($setting->id) && $setting->topup_bonus_pv_enable == 1) ? 'checked' : '' }}>
							  	<span class="slider round"></span>
							</label>
						</div>
					</div>
				</div>
			</div>
			<!-- <div class="col-md-6">
				<div class="form-group container-box">
					<div class="row">
						<div class="col-6">
							<span style="font-size: 20px; color: #000;">Topup RM 1.00 <i class="bi bi-arrow-right"></i> Point</span>
						</div>
						<div class="col-6" align="right">
							<input type="text" class="form-control" name="topup_rm_to_pv" value="{{ (!empty($setting->topup_rm_to_pv)) ? $setting->topup_rm_to_pv : old('topup_rm_to_pv') }}" onkeypress="return isNumberKey(event)">
						</div>
					</div>
				</div>
			</div> -->
		</div>
	</div>
</div>
</form>
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
	$('.submit-form-btn .btn-outline-primary').click( function(e){
    	e.preventDefault();
    	
    	$('#setting-merchant-form').submit();
    });
</script>
@endsection