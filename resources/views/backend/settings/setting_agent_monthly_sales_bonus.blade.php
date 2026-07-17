@extends('layouts.admin_app')

@section('content')
<div class="page-header">
    <h1>
        {{ isset($data['backendlang']['backendlang']['Setting_Agent_Monthly_Sales_Bonus']) ? $data['backendlang']['backendlang']['Setting_Agent_Monthly_Sales_Bonus'] :'' }}
    </h1>
</div>

<form method="POST" action="{{ route('save_setting_agent_monthly_sales_bonus') }}" id="setting-merchant-form">
@csrf
<div class="form-group">
	<div class="row">
		<div class="col-md-6">
			<div class="form-group m-big-parent">
				<h4>{{ isset($data['backendlang']['backendlang']['Monthly']) ? $data['backendlang']['backendlang']['Monthly'] :'' }}</h4>
				<hr>
				<div class="m-parent-box">
					@foreach($monthly_s as $monthly)
						<div class="form-group">
							<div class="row">
								<div class="col-6">
									<input type="hidden" name="mid[]" value="{{ $monthly->id }}">
									<input type="text" class="form-control" name="m_target_amount[]" placeholder="{{ isset($data['backendlang']['backendlang']['Target_Sales']) ? $data['backendlang']['backendlang']['Target_Sales'] :'' }}" value="{{ $monthly->target }}">
								</div>
								<div class="col-6">
									<div class="row">
										<div class="col-6">
											<select class="form-control" name="m_comm_type[]">
												<option {{ ($monthly->comm_type == 'Percentage') ? 'selected' : '' }} value="Percentage">{{ isset($data['backendlang']['backendlang']['Percentage']) ? $data['backendlang']['backendlang']['Percentage'] :'' }}</option>
												<option {{ ($monthly->comm_type == 'Amount') ? 'selected' : '' }} value="Amount">{{ isset($data['backendlang']['backendlang']['Amount']) ? $data['backendlang']['backendlang']['Amount'] :'' }}</option>
											</select>
										</div>
										<div class="col-6">
											<input type="text" class="form-control" name="m_comm_amount[]" placeholder="{{ isset($data['backendlang']['backendlang']['Amount']) ? $data['backendlang']['backendlang']['Amount'] :'' }}" value="{{ $monthly->comm_amount }}">
										</div>
									</div>
								</div>
							</div>
						</div>
					@endforeach
					<div class="form-group">
						<div class="row">
							<div class="col-6">
								<input type="text" class="form-control" name="m_target_amount[]" placeholder="{{ isset($data['backendlang']['backendlang']['Target_Sales']) ? $data['backendlang']['backendlang']['Target_Sales'] :'' }}">
							</div>
							<div class="col-6">
								<div class="row">
									<div class="col-6">
										<select class="form-control" name="m_comm_type[]">
											<option value="Percentage">{{ isset($data['backendlang']['backendlang']['Percentage']) ? $data['backendlang']['backendlang']['Percentage'] :'' }}</option>
											<option value="Amount">{{ isset($data['backendlang']['backendlang']['Amount']) ? $data['backendlang']['backendlang']['Amount'] :'' }}</option>
										</select>
									</div>
									<div class="col-6">
										<input type="text" class="form-control" name="m_comm_amount[]" placeholder="{{ isset($data['backendlang']['backendlang']['Amount']) ? $data['backendlang']['backendlang']['Amount'] :'' }}">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<hr>
				<div class="form-group">
					<div class="row justify-content_center">
						<div class="col-md-12" align="center">
							<button class="m-add-row-btn" id="add-row-btn">
								<i class="fa fa-plus"></i>
							</button>
						</div>
					</div>
				</div>
			</div>		
		</div>
		<div class="col-md-6">
			<div class="form-group q-big-parent">
				<h4>{{ isset($data['backendlang']['backendlang']['Quarterly']) ? $data['backendlang']['backendlang']['Quaterly'] :'' }}</h4>
				<hr>
				<div class="q-parent-box">
					@foreach($quaterly_s as $quaterly)
						<div class="form-group">
							<div class="row">
								<div class="col-6">
									<input type="hidden" name="qid[]" value="{{ $quaterly->id }}">
									<input type="text" class="form-control" name="q_target_amount[]" placeholder="{{ isset($data['backendlang']['backendlang']['Target_Sales']) ? $data['backendlang']['backendlang']['Target_Sales'] :'' }}" value="{{ $quaterly->target }}">
								</div>
								<div class="col-6">
									<div class="row">
										<div class="col-6">
											<select class="form-control" name="q_comm_type[]">
												<option {{ ($quaterly->comm_type == 'Percentage') ? 'selected' : '' }} value="Percentage">{{ isset($data['backendlang']['backendlang']['Percentage']) ? $data['backendlang']['backendlang']['Percentage'] :'' }}</option>
												<option {{ ($quaterly->comm_type == 'Amount') ? 'selected' : '' }} value="Amount">{{ isset($data['backendlang']['backendlang']['Amount']) ? $data['backendlang']['backendlang']['Amount'] :'' }}</option>
											</select>
										</div>
										<div class="col-6">
											<input type="text" class="form-control" name="q_comm_amount[]" placeholder="{{ isset($data['backendlang']['backendlang']['Amount']) ? $data['backendlang']['backendlang']['Amount'] :'' }}" value="{{ $quaterly->comm_amount }}">
										</div>
									</div>
								</div>
							</div>
						</div>
					@endforeach
					<div class="form-group">
						<div class="row">
							<div class="col-6">
								
								<input type="text" class="form-control" name="q_target_amount[]" placeholder="{{ isset($data['backendlang']['backendlang']['Target_Sales']) ? $data['backendlang']['backendlang']['Target_Sales'] :'' }}">
							</div>
							<div class="col-6">
								<div class="row">
									<div class="col-6">
										<select class="form-control" name="q_comm_type[]">
											<option value="Percentage">{{ isset($data['backendlang']['backendlang']['Percentage']) ? $data['backendlang']['backendlang']['Percentage'] :'' }}</option>
											<option value="Amount">{{ isset($data['backendlang']['backendlang']['Amount']) ? $data['backendlang']['backendlang']['Amount'] :'' }}</option>
										</select>
									</div>
									<div class="col-6">
										<input type="text" class="form-control" name="q_comm_amount[]" placeholder="{{ isset($data['backendlang']['backendlang']['Amount']) ? $data['backendlang']['backendlang']['Amount'] :'' }}">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<hr>
				<div class="form-group">
					<div class="row justify-content_center">
						<div class="col-md-12" align="center">
							<button class="q-add-row-btn" id="add-row-btn">
								<i class="fa fa-plus"></i>
							</button>
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
		<button class="btn btn-outline-primary">
			<i class="fa fa-check"> {{ isset($data['backendlang']['backendlang']['Save_Changes']) ? $data['backendlang']['backendlang']['Save_Changes'] :'' }}</i>
		</button>

	</div>
</div>
@endsection

@section('js')
<script type="text/javascript">
	var m_add_new_row = '<div class="form-group">\
							<div class="row">\
								<div class="col-6">\
									<input type="text" class="form-control" name="m_target_amount[]" placeholder="{{ isset($data["backendlang"]["backendlang"]["Target_Sales"]) ? $data["backendlang"]["backendlang"]["Target_Sales"] :''}}">\
								</div>\
								<div class="col-6">\
									<div class="row">\
										<div class="col-6">\
											<select class="form-control" name="m_comm_type[]">\
												<option value="Percentage">{{ isset($data["backendlang"]["backendlang"]["Percentage"]) ? $data["backendlang"]["backendlang"]["Percentage"] :''}}</option>\
												<option value="Amount">{{ isset($data["backendlang"]["backendlang"]["Amount"]) ? $data["backendlang"]["backendlang"]["Amount"] :''}}</option>\
											</select>\
										</div>\
										<div class="col-6">\
											<input type="text" class="form-control" name="m_comm_amount[]" placeholder="{{ isset($data["backendlang"]["backendlang"]["Amount"]) ? $data["backendlang"]["backendlang"]["Amount"] :''}}">\
										</div>\
									</div>\
								</div>\
							</div>\
						</div>';

	$('.m-add-row-btn').click(function(e){
    	e.preventDefault();
    	var ele = $(this);

    	ele.closest('.m-big-parent').find('.m-parent-box').append(m_add_new_row);
    });

    var q_add_new_row = '<div class="form-group">\
							<div class="row">\
								<div class="col-6">\
									<input type="text" class="form-control" name="q_target_amount[]" placeholder="{{ isset($data["backendlang"]["backendlang"]["Target_Sales"]) ? $data["backendlang"]["backendlang"]["Target_Sales"] :''}}">\
								</div>\
								<div class="col-6">\
									<div class="row">\
										<div class="col-6">\
											<select class="form-control" name="q_comm_type[]">\
												<option value="Percentage">{{ isset($data["backendlang"]["backendlang"]["Percentage"]) ? $data["backendlang"]["backendlang"]["Percentage"] :''}}</option>\
												<option value="Amount">{{ isset($data["backendlang"]["backendlang"]["Amount"]) ? $data["backendlang"]["backendlang"]["Amount"] :''}}</option>\
											</select>\
										</div>\
										<div class="col-6">\
											<input type="text" class="form-control" name="q_comm_amount[]" placeholder="{{ isset($data["backendlang"]["backendlang"]["Amount"]) ? $data["backendlang"]["backendlang"]["Amount"] :''}}">\
										</div>\
									</div>\
								</div>\
							</div>\
						</div>';

	$('.q-add-row-btn').click(function(e){
    	e.preventDefault();
    	var ele = $(this);

    	ele.closest('.q-big-parent').find('.q-parent-box').append(q_add_new_row);
    });

    $('.submit-form-btn .btn-outline-primary').click( function(e){
    	$('#setting-merchant-form').submit();
    });
</script>
@endsection