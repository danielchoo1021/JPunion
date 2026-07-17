@extends('layouts.admin_app')
@section('content')
<div class="form-group">
	<div class="row">
		<div class="col-12">
			<form method="POST" action="{{ route('save_setting_payment_gateway') }}" id="setting-merchant-form">
				@csrf
				<div class="big-parent">
					<div class="form-group">
						<div class="child-div row">
                            <div class="col-sm-6">
								<div class="form-group child-row">
									<span class="box form-group" style="background-color: white;">
										<div class="form-group">
											<h5 align="center">
												<b>
													<input type="hidden" name="setting_enable[{{ $data['senangpay_setting']->id }}]" value="0">
													<input type="checkbox" {{ $data['senangpay_setting']->status == 1 ? 'checked' : '' }} name="setting_enable[{{ $data['senangpay_setting']->id }}]" value="1"> {{ $data['senangpay_setting']->name }}
												</b>
											</h5>
										</div>
										<input type="hidden" name="setting_id[]" value="{{ $data['senangpay_setting']->id }}">
										<div class="form-group">
											<div class="row">
												<div class="col">
													<label><b>{{ isset($data['backendlang']['backendlang']['Merchant_ID']) ? $data['backendlang']['backendlang']['Merchant_ID'] :'' }}</b></label>
													<input type="text" name="param[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['Merchant_ID']) ? $data['backendlang']['backendlang']['Merchant_ID'] :'' }}" value="{{ $data['senangpay_setting']->param }}">
												</div>
											</div>
										</div>
										<div class="form-group">
											<div class="row">
												<div class="col">
													<label><b>{{ isset($data['backendlang']['backendlang']['Secret_Key']) ? $data['backendlang']['backendlang']['Secret_Key'] :'' }}</b></label>
													<input type="text" name="param_1[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['Secret_Key']) ? $data['backendlang']['backendlang']['Secret_Key'] :'' }}" value="{{ $data['senangpay_setting']->param_1 }}">
												</div>
											</div>
										</div>
									</span>
								</div>
							</div>

							<div class="col-sm-6">
								<div class="form-group child-row">
									<span class="box form-group" style="background-color: white;">
										<div class="form-group">
											<h5 align="center">
												<b>
													<input type="hidden" name="setting_enable[{{ $data['gkash_setting']->id }}]" value="0">
													<input type="checkbox" {{ $data['gkash_setting']->status == 1 ? 'checked' : '' }} name="setting_enable[{{ $data['gkash_setting']->id }}]" value="1"> {{ $data['gkash_setting']->name }}
												</b>
											</h5>
										</div>
										<input type="hidden" name="setting_id[]" value="{{ $data['gkash_setting']->id }}">
										<div class="form-group">
											<div class="row">
												<div class="col">
													<label><b>{{ isset($data['backendlang']['backendlang']['Merchant_ID']) ? $data['backendlang']['backendlang']['Merchant_ID'] :'' }}</b></label>
													<input type="text" name="param[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['Merchant_ID']) ? $data['backendlang']['backendlang']['Merchant_ID'] :'' }}" value="{{ $data['gkash_setting']->param }}">
												</div>
											</div>
										</div>
										<div class="form-group">
											<div class="row">
												<div class="col">
													<label><b>{{ isset($data['backendlang']['backendlang']['Signature_Key']) ? $data['backendlang']['backendlang']['Signature_Key'] :'' }}</b></label>
													<input type="text" name="param_1[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['Signature_Key']) ? $data['backendlang']['backendlang']['Signature_Key'] :'' }}" value="{{ $data['gkash_setting']->param_1 }}">
												</div>
											</div>
										</div>
									</span>
								</div>
							</div>
						</div>					
					</div>
				</div>
			</form>
		</div>
	</div>
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
	$('.submit-form-btn').on('click', '.btn-outline-primary', function(e){
		e.preventDefault();
        $('.loading-gif').show();

		$('#setting-merchant-form').submit();
    });
</script>
@endsection