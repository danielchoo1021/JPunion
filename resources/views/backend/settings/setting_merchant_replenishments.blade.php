@extends('layouts.admin_app')
@section('content')
<div class="page-header">
    <h1>
        {{ isset($data['backendlang']['backendlang']['Setting_Agent_Replenishment_Commission']) ? $data['backendlang']['backendlang']['Setting_Agent_Replenishment_Commission'] :'' }}
        <!-- <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
            @if(Auth::check())
            {{ Auth::user()->f_name }} 
            @endif
        </small> -->
    </h1>
</div>
<form method="POST" action="{{ route('save_setting_merchant_replenishments') }}" id="setting-merchant-form">
@csrf
<div class="row">
	<div class="col-sm-6 col-12">
		<div class="form-group">
			<h3>{{ isset($data['backendlang']['backendlang']['Direct_Agent_Commission_Distributor']) ? $data['backendlang']['backendlang']['Direct_Agent_Commission_Distributor'] :'' }}</h3>
			<div class="container-box">
				<label>{{ isset($data['backendlang']['backendlang']['First_Transaction_Commission']) ? $data['backendlang']['backendlang']['First_Transaction_Commission'] :'' }}</label>
				<div class="form-group">
					<div class="row">
						<div class="col-2">
							@php
								$dl_f_comm_selected = isset($replenishment) ? $replenishment->dl_f_comm_type : '';
							@endphp
							<select class="form-control" name="dl_f_comm_type">
								<option {{ ($dl_f_comm_selected == 'Percentage') ? 'selected' : '' }} value="Percentage">{{ isset($data['backendlang']['backendlang']['Percentage']) ? $data['backendlang']['backendlang']['Percentage'] :'' }}</option>
								<option {{ ($dl_f_comm_selected == 'Amount') ? 'selected' : '' }} value="Amount">{{ isset($data['backendlang']['backendlang']['Amount']) ? $data['backendlang']['backendlang']['Amount'] :'' }}</option>
							</select>
						</div>
						<div class="col-10">
							<input type="text" name="dl_first_transaction_commission" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['First_Transaction_Commission']) ? $data['backendlang']['backendlang']['First_Transaction_Commission'] :'' }}" value="{{ isset($replenishment) ? $replenishment->dl_first_transaction_commission : '' }}">
						</div>
					</div>
				</div>
				<label>{{ isset($data['backendlang']['backendlang']['Replenishment_Commission']) ? $data['backendlang']['backendlang']['Replenishment_Commission'] :'' }}</label>
				<div class="form-group">
					<div class="row">
						<div class="col-2">
							@php
								$dl_e_comm_selected = isset($replenishment) ? $replenishment->dl_e_comm_type : '';
							@endphp
							<select class="form-control" name="dl_e_comm_type">
								<option {{ ($dl_e_comm_selected == 'Percentage') ? 'selected' : '' }} value="Percentage">{{ isset($data['backendlang']['backendlang']['Percentage']) ? $data['backendlang']['backendlang']['Percentage'] :'' }}</option>
								<option {{ ($dl_e_comm_selected == 'Amount') ? 'selected' : '' }} value="Amount">{{ isset($data['backendlang']['backendlang']['Amount']) ? $data['backendlang']['backendlang']['Amount'] :'' }}</option>
							</select>
						</div>
						<div class="col-10">
							<input type="text" name="dl_every_transaction_commision" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['First_Transaction_Commission']) ? $data['backendlang']['backendlang']['First_Transaction_Commission'] :'' }}" value="{{ isset($replenishment) ? $replenishment->dl_every_transaction_commision : '' }}">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-sm-6 col-12">
		<div class="form-group">
			<h3>{{ isset($data['backendlang']['backendlang']['Direct_Downline_Commission_Merchant']) ? $data['backendlang']['backendlang']['Direct_Downline_Commission_Merchant'] :'' }}</h3>
			<div class="container-box">
				<label>{{ isset($data['backendlang']['backendlang']['First_Transaction_Commission']) ? $data['backendlang']['backendlang']['First_Transaction_Commission'] :'' }}</label>
				<div class="form-group">
					<div class="row">
						<div class="col-2">
							@php
								$sp_f_comm_selected = isset($replenishment) ? $replenishment->sp_f_comm_type : '';
							@endphp
							<select class="form-control" name="sp_f_comm_type">
								<option {{ (!empty($select) && $select->type == 'Percentage') ? 'selected' : '' }} value="Percentage">{{ isset($data['backendlang']['backendlang']['Percentage']) ? $data['backendlang']['backendlang']['Percentage'] :'' }}</option>
								<option {{ (!empty($select) && $select->type == 'Amount') ? 'selected' : '' }} value="Amount">{{ isset($data['backendlang']['backendlang']['Amount']) ? $data['backendlang']['backendlang']['Amount'] :'' }}</option>
							</select>
						</div>
						<div class="col-10">
							<input type="text" name="sp_first_transaction_commission" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['First_Transaction_Commission']) ? $data['backendlang']['backendlang']['First_Transaction_Commission'] :'' }}" value="{{ isset($replenishment) ? $replenishment->sp_first_transaction_commission : '' }}">
						</div>
					</div>
				</div>

				<div class="form-group">
					<label>{{ isset($data['backendlang']['backendlang']['Replenishment_Commission']) ? $data['backendlang']['backendlang']['Replenishment_Commission'] :'' }}</label>
					<div class="form-group">
						<div class="row">
							<div class="col-2">
								@php
									$sp_e_comm_selected = isset($replenishment) ? $replenishment->sp_e_comm_type : '';
								@endphp
								<select class="form-control" name="sp_e_comm_type">
									<option {{ ($sp_e_comm_selected == 'Percentage') ? 'selected' : '' }} value="Percentage">{{ isset($data['backendlang']['backendlang']['Percentage']) ? $data['backendlang']['backendlang']['Percentage'] :'' }}</option>
									<option {{ ($sp_e_comm_selected == 'Amount') ? 'selected' : '' }} value="Amount">{{ isset($data['backendlang']['backendlang']['Amount']) ? $data['backendlang']['backendlang']['Amount'] :'' }}</option>
								</select>
							</div>
							<div class="col-10">
								<input type="text" name="sp_every_transaction_commision" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['First_Transaction_Commission']) ? $data['backendlang']['backendlang']['First_Transaction_Commission'] :'' }}" value="{{ isset($replenishment) ? $replenishment->sp_every_transaction_commision : '' }}">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</form>

<div class="submit-form-btn">
	<div class="form-group wizard-actions" align="right">
		<a href="{{ route('product.products.index') }}" class="btn btn-outline-danger">
			<i class="fa fa-ban"> {{ isset($data['backendlang']['backendlang']['Cancel']) ? $data['backendlang']['backendlang']['Cancel'] :'' }}</i>
		</a>

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