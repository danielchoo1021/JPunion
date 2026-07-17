<div class="row">
	<div class="col-6">
		<div class="form-group">
			<div class="row">
				<div class="col-sm-4">
					{{ isset($data['backendlang']['backendlang']['Bank_Name']) ? $data['backendlang']['backendlang']['Bank_Name'] :'' }}: <span class="important-text">*</span>
				</div>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="bank_name" value="{{ isset($payment_bank) ? $payment_bank->bank_name : old('bank_name') }}" placeholder="Bank Name *">
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-6">
		<div class="form-group">
			<div class="row">
				<div class="col-sm-4">
					{{ isset($data['backendlang']['backendlang']['Bank_Holder_Name']) ? $data['backendlang']['backendlang']['Bank_Holder_Name'] :'' }}: <span class="important-text">*</span>
				</div>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="bank_holder_name" value="{{ isset($payment_bank) ? $payment_bank->bank_holder_name : old('bank_holder_name') }}" placeholder="{{ isset($data['backendlang']['backendlang']['Bank_Holder_Name']) ? $data['backendlang']['backendlang']['Bank_Holder_Name'] :'' }} *">
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-6">
		<div class="form-group">
			<div class="row">
				<div class="col-sm-4">
					{{ isset($data['backendlang']['backendlang']['Bank_Account']) ? $data['backendlang']['backendlang']['Bank_Account'] :'' }}: <span class="important-text">*</span>
				</div>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="bank_account" value="{{ isset($payment_bank) ? $payment_bank->bank_account : old('bank_account') }}" placeholder="{{ isset($data['backendlang']['backendlang']['Bank_Account']) ? $data['backendlang']['backendlang']['Bank_Account'] :'' }} *">
				</div>
			</div>
		</div>
	</div>
</div>