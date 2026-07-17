@extends('layouts.admin_app')
@section('content')
<style>
.box {
	display: block;
	padding: 50px;
	text-align: center;
	border-radius: 20px;
	margin: 0 left;
	background-color: #5886FF;
	float:left;
	margin-bottom: 20px;
  }

  .box1 {
	display: block;
	padding: 50px;
	text-align: center;
	border-radius: 20px;
	margin: 0 left;
	background-color: rgb(87, 87, 87);
	text-align: center;
	float:left;
	margin-bottom: 20px;
}

.box2 {
	display: block;
	padding: 50px;
	text-align: center;
	border-radius: 20px;
	margin: 0 left;
	background-color: rgb(87, 87, 87);
	text-align: center;
	float:left;
	margin-bottom: 20px;
}

.big-parent{
	width:100%;
	float: center;
	margin-top: 0px;
	margin-right: -15px;
}
</style>
<form method="POST" action="{{ route('save_setting_prize_pool') }}" id="setting-merchant-form">
@csrf
<div class="row">

	<div class="col-md-4">
		<span style="width: 100%;" class="form-group box">
		<h5 style="color: white; font-size: 20px;"><b>{{ isset($data['backendlang']['backendlang']['Total_Prize_Pool']) ? $data['backendlang']['backendlang']['Total_Prize_Pool'] :'' }} (RM)</b></h5>
		<br>
		<i class="text" style="color: white; font-size: 35px;">{{ isset($get_total_sales) ? number_format($get_total_sales, 2) : 'NULL'}}</i>
		<br>
		<br>
			<i class="ace-icon fa fa-arrow-up bigger-130" style="color:white; font-size: 20px;">{{ isset($data['backendlang']['backendlang']['This_Year']) ? $data['backendlang']['backendlang']['This_Year'] :'' }}</i> 

		</span>
		<span style="width: 100%;" class="form-group box2">
		<h5 style="color: white; font-size: 16px;"><b>{{ isset($data['backendlang']['backendlang']['Sales_Target']) ? $data['backendlang']['backendlang']['Sales_Target'] :'' }} (RM)</b></h5>
		<br>
		<input type="text" name="target" class="text" style="text-align: center; border:none; font-size:28px; width: -webkit-fill-available;" placeholder="{{ isset($data['backendlang']['backendlang']['Sales_Target']) ? $data['backendlang']['backendlang']['Sales_Target'] :'' }} (RM)" value="{{ !empty($prize_pool_condition->target) ? $prize_pool_condition->target : '' }}" onkeypress="return isNumberKey(event)">
		</span>
		<br>
		<span style="width: 100%;" class="form-group box1 split_sales_percentage_display">
			<h5 style="color: white; font-size: 16px;"><b>{{ isset($data['backendlang']['backendlang']['Split_Percentage']) ? $data['backendlang']['backendlang']['Split_Percentage'] :'' }} (%)</b></h5>
			<input type="text" name="split_sales_percentage" class="text" style="text-align: center; border:none; font-size:28px; width: -webkit-fill-available;" placeholder="{{ isset($data['backendlang']['backendlang']['Split_Percentage']) ? $data['backendlang']['backendlang']['Split_Percentage'] :'' }} (%)" value="{{ !empty($prize_pool_condition->split_sales_percentage) ? $prize_pool_condition->split_sales_percentage : '' }}" onkeypress="return isNumberKey(event)">
		</span>
	</div>
	<div class="col-md-8">
		<div class="big-parent">
			<div class="form-group">
				<div class="row">
					<div class="col-6 offset-3">
						<div class="form-group">
							<h5><b>{{ isset($data['backendlang']['backendlang']['Split_Type']) ? $data['backendlang']['backendlang']['Split_Type'] :'' }}</b></h5>
						</div>
						<select class="form-control condition_type" name="condition_type">
							<option {{ !empty($prize_pool_condition->type) && ($prize_pool_condition->type == 'Percentage') ? 'selected' : '' }} 
									value="Percentage">{{ isset($data['backendlang']['backendlang']['Percentage']) ? $data['backendlang']['backendlang']['Percentage'] :'' }}</option>
							<option {{ !empty($prize_pool_condition->type) && ($prize_pool_condition->type == 'Amount') ? 'selected' : '' }} 
											value="Amount">{{ isset($data['backendlang']['backendlang']['Amount']) ? $data['backendlang']['backendlang']['Amount'] :'' }}</option>
						</select>
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="row">
					<div class="col-6 offset-3">
						<div class="form-group">
							<h5><b>{{ isset($data['backendlang']['backendlang']['Price_Amount']) ? $data['backendlang']['backendlang']['Price_Amount'] :'' }} (RM)</b></h5>
						</div>
					</div>
				</div>
				<div class="child-div">
					@for($i = 1; $i <= 10; $i++)
					<div class="form-group child-row">
						<div class="row">
							<div class="col-1 offset-2" align="right">
								<b>@if($i == 1)
									<img src="{{ asset('images/cfa0077c100a0a4c7bc6f935790fd0d1.png') }}" width="30">
									@elseif($i == 2)
									<img src="{{ asset('images/imgbin_trophy-champion-cup-png.png') }}" width="25">
									@elseif($i == 3)
									<img src="{{ asset('images/Trophy_Cup_Bronze_PNG_Clipart.png') }}" width="25">
									@else
									{{ $i }}
									@endif</b>
							</div>
							<div class="col-6">
								<div class="row">
									<div class="col-6">
										<input type="text" name="type" class="form-control type" placeholder="{{ isset($data['backendlang']['backendlang']['e.g.']) ? $data['backendlang']['backendlang']['e.g.'] :'' }} {{ isset($data['backendlang']['backendlang']['Percentage']) ? $data['backendlang']['backendlang']['Percentage'] :'' }}" readonly>
									</div>
									<div class="col-6">
										<input type="text" name="amount_{{ $i }}" class="form-control checkAmount" placeholder="{{ isset($data['backendlang']['backendlang']['e.g.']) ? $data['backendlang']['backendlang']['e.g.'] :'' }} 5" value="{{ !empty($prize_pool_setting[$i]->amount) ? $prize_pool_setting[$i]->amount : '' }}">
									</div>
								</div>
							</div>
						</div>
					</div>
					@endfor
				</div>					
			</div>

			<!-- <div class="form-group">
				<div class="row">
					<div class="col-6 offset-3">
						<div class="form-group">
							<h5><b>Total Prize Pool (RM)</b></h5>
						</div>
						<input type="text" name="total_prize_pool" class="form-control" placeholder="Total Prize Pool (RM)" value="{{ !empty($web_setting->prize_pool) ? $web_setting->prize_pool : '' }}">
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="row">
					<div class="col-6 offset-3">
						<div class="form-group">
							<h5><b>Prize Amount (RM)</b></h5>
						</div>
					</div>
				</div>
				<div class="child-div">
					@for($i = 1; $i <= 10; $i++)
					<div class="form-group child-row">
						<div class="row">
							<div class="col-1 offset-2" align="right">
								{{ $i }}.
							</div>
							<div class="col-6">
								<div class="row">
									<div class="col-6">
										<select class="form-control type" name='type_{{ $i }}'>
											<option {{ !empty($prize_pool_setting[$i]->type) && ($prize_pool_setting[$i]->type == 'Percentage') ? 'selected' : '' }} 
													value="Percentage">Percentage</option>
											<option {{ !empty($prize_pool_setting[$i]->type) && ($prize_pool_setting[$i]->type == 'Amount') ? 'selected' : '' }} 
															value="Amount">Amount</option>
										</select>
									</div>
									<div class="col-6">
										<input type="text" name="amount_{{ $i }}" class="form-control checkAmount" placeholder="e.g. 5" value="{{ !empty($prize_pool_setting[$i]->amount) ? $prize_pool_setting[$i]->amount : '' }}">
									</div>
								</div>
							</div>
						</div>
					</div>
					@endfor
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
    	$('.loading-gif').show();

    	var checkTotal = 0;
    	$('.checkAmount').each(function() {
		    var total = $(this).val();

		    if(total){
		    	checkTotal += parseInt(total);
		    }
		});

		var type = $('.condition_type :selected').val();
		if(type == 'Percentage'){
			if(checkTotal > 100){
				$('.loading-gif').hide();
				toastr.error("{{ isset($data['backendlang']['backendlang']['Amount_Exceeded_100_Percent_of_Total_Prize_Pool']) ? $data['backendlang']['backendlang']['Amount_Exceeded_100_Percent_of_Total_Prize_Pool'] : 'Percentage' }}");
				return false;
			}
		}

    	$('#setting-merchant-form').submit();
    });

    function checkConditionType()
    {
    	var type = $('.condition_type :selected').val();
    	var translatedType = '';

    	if(type == 'Percentage'){
    		translatedType = '{{ isset($data["backendlang"]["backendlang"]["Percentage"]) ? $data["backendlang"]["backendlang"]["Percentage"] : "Percentage" }}';
    		$('.split_sales_percentage_display').show();
    	}else if(type == 'Amount'){
    		translatedType = '{{ isset($data["backendlang"]["backendlang"]["Amount"]) ? $data["backendlang"]["backendlang"]["Amount"] : "Amount" }}';
    		$('.split_sales_percentage_display').hide();
    	}

    	$('.type').val(translatedType);
    }

    $('.condition_type').change(function(e){
    	checkConditionType();
    });

    checkConditionType();
</script>
@endsection