@extends('layouts.admin_app')

@section('content')
<div class="container-box form-group">
	<h3>{{ isset($data['backendlang']['backendlang']['Filter']) ? $data['backendlang']['backendlang']['Filter'] :'' }}</h3>
	<hr>
	<form action="{{ route('commission_report') }}" method="GET">
		<div class="form-group">
			<div class="row">
				<div class="col-sm-2">
					<div class="form-group">
						<input type="text" class="form-control" name="dates" value="{{ !empty(request('dates')) ? request('dates') : $startDate.' - '.$endDate }}">
					</div>
				</div>

				<div class="col-sm-2">
					<div class="form-group">
						<input type="text" class="form-control" name="transaction_no" value="{{ !empty(request('transaction_no')) ? request('transaction_no') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Transaction_Number']) ? $data['backendlang']['backendlang']['Search_Transaction_Number'] :'' }}">
					</div>
				</div>

				<div class="col-sm-2">
					<div class="form-group">
						<input type="text" class="form-control" name="referrer_name" value="{{ !empty(request('referrer_name')) ? request('referrer_name') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Agent_Name']) ? $data['backendlang']['backendlang']['Search_Agent_Name'] :'' }}">
					</div>
				</div>

				<div class="col-sm-2">
					<div class="form-group">
						<input type="text" class="form-control" name="referrer_code" value="{{ !empty(request('referrer_code')) ? request('referrer_code') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Agent_Code']) ? $data['backendlang']['backendlang']['Search_Agent_Code'] :'' }}">
					</div>
				</div>

				<div class="col-sm-2">
					<div class="form-group">
						<input type="text" class="form-control" name="agent" value="{{ !empty(request('agent')) ? request('agent') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Downline_Name']) ? $data['backendlang']['backendlang']['Search_Downline_Name'] :'' }}">
					</div>
				</div>

				<div class="col-sm-2">
					<div class="form-group">
						<input type="text" class="form-control" name="agent_code" value="{{ !empty(request('agent_code')) ? request('agent_code') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Downline_Code']) ? $data['backendlang']['backendlang']['Search_Downline_Code'] :'' }}">
					</div>
				</div>
				<div class="col-sm-2">
					<div class="form-group">
						<select class="form-control" name="comm_type">
							<option value=""> {{ isset($data['backendlang']['backendlang']['All_Commission_Types']) ? $data['backendlang']['backendlang']['All_Commission_Types'] :'' }}</option>
							<option {{ (!empty(request('comm_type')) && request('comm_type') == 'Order') ? 'selected' : '' }} value="Order">{{ isset($data['backendlang']['backendlang']['Order_Rebate']) ? $data['backendlang']['backendlang']['Order_Rebate'] :'' }}</option>
							<option {{ (!empty(request('comm_type')) && request('comm_type') == 'Order Rebate Commission') ? 'selected' : '' }} value="Order Rebate Commission">{{ isset($data['backendlang']['backendlang']['Order_Rebate_Commission']) ? $data['backendlang']['backendlang']['Order_Rebate_Commission'] :'' }}</option>
							<option {{ (!empty(request('comm_type')) && request('comm_type') == 'Direct Downline') ? 'selected' : '' }} value="Direct Downline">{{ isset($data['backendlang']['backendlang']['Direct_Downline_Order_Rebate']) ? $data['backendlang']['backendlang']['Direct_Downline_Order_Rebate'] :'' }}</option>
							<option {{ (!empty(request('comm_type')) && request('comm_type') == 'Heirarchy') ? 'selected' : '' }} value="Heirarchy">{{ isset($data['backendlang']['backendlang']['Hierarchy_Commission']) ? $data['backendlang']['backendlang']['Hierarchy_Commission'] :'' }}</option>
							<option {{ (!empty(request('comm_type')) && request('comm_type') == 'Team') ? 'selected' : '' }} value="Team">{{ isset($data['backendlang']['backendlang']['Team_Reward']) ? $data['backendlang']['backendlang']['Team_Reward'] :'' }}</option>
							<option {{ (!empty(request('comm_type')) && request('comm_type') == 'Performance') ? 'selected' : '' }} value="Performance">{{ isset($data['backendlang']['backendlang']['Performance_Reward']) ? $data['backendlang']['backendlang']['Performance_Reward'] :'' }}</option>
							<option {{ (!empty(request('comm_type')) && request('comm_type') == 'Performance Sales') ? 'selected' : '' }} value="Performance Sales">{{ isset($data['backendlang']['backendlang']['Performance_Sales_Reward']) ? $data['backendlang']['backendlang']['Performance_Sales_Reward'] :'' }}</option>
							<option {{ (!empty(request('comm_type')) && request('comm_type') == 'Referral') ? 'selected' : '' }} value="Referral">{{ isset($data['backendlang']['backendlang']['Referral_Reward']) ? $data['backendlang']['backendlang']['Referral_Reward'] :'' }}</option>
							<option {{ (!empty(request('comm_type')) && request('comm_type') == 'Prize Pool') ? 'selected' : '' }} value="Prize Pool">{{ isset($data['backendlang']['backendlang']['Prize_Pool_Reward']) ? $data['backendlang']['backendlang']['Prize_Pool_Reward'] :'' }}</option>
							<option {{ (!empty(request('comm_type')) && request('comm_type') == 'Same Tier') ? 'selected' : '' }} value="Same Tier">{{ isset($data['backendlang']['backendlang']['Same_Tier_Bonus']) ? $data['backendlang']['backendlang']['Same_Tier_Bonus'] :'' }}</option>
							<option {{ (!empty(request('comm_type')) && request('comm_type') == 'Downline Spread') ? 'selected' : '' }} value="Downline Spread">{{ isset($data['backendlang']['backendlang']['Downline_Spread_Bonus']) ? $data['backendlang']['backendlang']['Downline_Spread_Bonus'] :'' }}</option>
							<option {{ (!empty(request('comm_type')) && request('comm_type') == 'Downline Purchase') ? 'selected' : '' }} value="Downline Purchase">{{ isset($data['backendlang']['backendlang']['Downline_Purchase_Bonus']) ? $data['backendlang']['backendlang']['Downline_Purchase_Bonus'] :'' }}</option>
							<option {{ (!empty(request('comm_type')) && request('comm_type') == 'Agent Bonus') ? 'selected' : '' }} value="Agent Bonus">{{ isset($data['backendlang']['backendlang']['Agent_Target_Bonus']) ? $data['backendlang']['backendlang']['Agent_Target_Bonus'] :'' }}</option>
							<option {{ (!empty(request('comm_type')) && request('comm_type') == 'Price Difference') ? 'selected' : '' }} value="Price Difference">{{ isset($data['backendlang']['backendlang']['Price_Difference_Commission']) ? $data['backendlang']['backendlang']['Price_Difference_Commission'] :'' }}</option>
							<option {{ (!empty(request('comm_type')) && request('comm_type') == 'Purchase From Customer') ? 'selected' : '' }} value="Purchase From Customer">{{ isset($data['backendlang']['backendlang']['Purchase_From_Customer']) ? $data['backendlang']['backendlang']['Purchase_From_Customer'] :'' }}</option>
						</select>
					</div>
				</div>
				<div class="col-sm-2">
					<div class="form-group">
						<select class="form-control" name="status">
							<option value="">{{ isset($data['backendlang']['backendlang']['All_Status']) ? $data['backendlang']['backendlang']['All_Status'] : 'All Status' }}</option>
							<option value="approved" {{ request('status') == 'approved' || request('status') == '1' ? 'selected' : '' }}>{{ isset($data['backendlang']['backendlang']['Approved']) ? $data['backendlang']['backendlang']['Approved'] : 'Approved' }}</option>
							<option value="burned" {{ request('status') == 'burned' || request('status') == '2' ? 'selected' : '' }}>{{ isset($data['backendlang']['backendlang']['Burned']) ? $data['backendlang']['backendlang']['Burned'] : 'Burned'	 }}</option>
						</select>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-2">
					<div class="form-group">
						{{ isset($data['backendlang']['backendlang']['Row_Per_Page']) ? $data['backendlang']['backendlang']['Row_Per_Page'] :'' }}: <br>
						<select class="input-small" name="per_page">
							<option {{ (!empty(request('per_page')) && request('per_page') == '10') ? 'selected' : '' }} value="10">10</option>
							<option {{ (!empty(request('per_page')) && request('per_page') == '20') ? 'selected' : '' }} value="20">20</option>
							<option {{ (!empty(request('per_page')) && request('per_page') == '50') ? 'selected' : '' }} value="50">50</option>
						</select>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<button class="btn btn-outline-primary btn-sm">
				<i class="bi bi-search"></i> {{ isset($data['backendlang']['backendlang']['Search']) ? $data['backendlang']['backendlang']['Search'] :'' }}
			</button>
			<a href="{{ route('commission_report') }}" class="btn btn-warning btn-sm">
				<i class="bi bi-arrow-clockwise"></i> {{ isset($data['backendlang']['backendlang']['Clear_Search']) ? $data['backendlang']['backendlang']['Clear_Search'] :'' }}
			</a>
		</div>

		@if(!empty(request('today')))
		<input type="text" class="form-control" name="today" value="{{ !empty(request('today')) ? request('today') : '' }}" style="display:none">
		@elseif(!empty(request('this_month')))
		<input type="text" class="form-control" name="this_month" value="{{ !empty(request('this_month')) ? request('this_month') : '' }}" style="display:none">
		@elseif(!empty(request('this_year')))
		<input type="text" class="form-control" name="this_year" value="{{ !empty(request('this_year')) ? request('this_year') : '' }}" style="display:none">
		@endif

		@php
		$dailyDate = date('Y-m-d');
		$MonthlyDate = date('Y-m');
		$YearlyDate = date('Y');
		@endphp
	</form>
	<div class="form-group">
		<span class="badge bg-info" style="font-size: 1rem; padding: 10px;">
			@if(!empty(request('today')))
			 {{ isset($data['backendlang']['backendlang']['Dates']) ? $data['backendlang']['backendlang']['Dates'] :'' }}: {{ request('today') }}
			@elseif(!empty(request('this_month')))
			 {{ isset($data['backendlang']['backendlang']['Dates']) ? $data['backendlang']['backendlang']['Dates'] :'' }}: {{ request('this_month') }}
			@elseif(!empty(request('this_year')))
			 {{ isset($data['backendlang']['backendlang']['Dates']) ? $data['backendlang']['backendlang']['Dates'] :'' }}: {{ request('this_year') }}
			@else
			 {{ isset($data['backendlang']['backendlang']['Dates']) ? $data['backendlang']['backendlang']['Dates'] :'' }}: {{ !empty(request('dates')) ? request('dates') : $startDate.' - '.$endDate }}
			@endif
		</span>
		|
		<span class="badge bg-success" style="font-size: 1rem; padding: 10px;">
			{{ isset($data['backendlang']['backendlang']['Grand_Total']) ? $data['backendlang']['backendlang']['Grand_Total'] :'' }}: <span class="grandTotal"></span>
		</span>
		|
		<span class="badge bg-warning" style="font-size: 1rem; padding: 10px;">
			{{ isset($data['backendlang']['backendlang']['Net_Total']) ? $data['backendlang']['backendlang']['Net_Total'] :'' }}: <span class="netTotal"></span>
		</span>
		|
		<a href="{{ route('commission_report', ['agent='.(!empty(request('agent')) ? request('agent') : ''),'per_page='.(!empty(request('per_page')) ? request('per_page') : ''), 'status='.(!empty(request('status')) ? request('status') : ''),
																						'today='.$dailyDate]) }}"
			class="btn btn-outline-primary btn-sm btn-filter {{ !empty(request('today')) ? 'filter_selected' : '' }}">
			{{ isset($data['backendlang']['backendlang']['Daily_Commission']) ? $data['backendlang']['backendlang']['Daily_Commission'] :'' }}
			<input type="hidden" name="filter_data" value="0">
			<br>
			RM {{ number_format($dailySales->dailySales, 2) }}
		</a>
		<a href="{{ route('commission_report', ['agent='.(!empty(request('agent')) ? request('agent') : ''),'per_page='.(!empty(request('per_page')) ? request('per_page') : ''), 'status='.(!empty(request('status')) ? request('status') : ''),
																						'this_month='.$MonthlyDate]) }}"
			class="btn btn-outline-primary btn-sm btn-filter {{ !empty(request('this_month')) ? 'filter_selected' : '' }}">
			{{ isset($data['backendlang']['backendlang']['Monthly_Commission']) ? $data['backendlang']['backendlang']['Monthly_Commission'] :'' }}
			<input type="hidden" name="filter_data" value="1">
			<br>
			RM {{ number_format($monthlySales->monthlySales, 2) }}
		</a>
		<a href="{{ route('commission_report', ['agent='.(!empty(request('agent')) ? request('agent') : ''),'per_page='.(!empty(request('per_page')) ? request('per_page') : ''), 'status='.(!empty(request('status')) ? request('status') : ''),
																						'this_year='.$YearlyDate]) }}"
			class="btn btn-outline-primary btn-sm btn-filter {{ !empty(request('this_year')) ? 'filter_selected' : '' }}">
			{{ isset($data['backendlang']['backendlang']['Yearly_Commission']) ? $data['backendlang']['backendlang']['Yearly_Commission'] :'' }}
			<input type="hidden" name="filter_data" value="2">
			<br>
			RM {{ number_format($yearlySales->yearlySales, 2) }}
		</a>
	</div>
</div>
<div class="container-box form-group">
	<div class="form-group" align="right">
		<a href="{{ route('print_commission_report', ['dates='.(!empty(request('dates')) ? request('dates') : $startDate.' - '.$endDate),
													  'yearly='.(!empty(request('this_year')) ? request('this_year') : '' ),
													  'monthly='.(!empty(request('this_month')) ? request('this_month') : '' ),
													  'daily='.(!empty(request('today')) ? request('today') : '' ),
													  'transaction_no='.(!empty(request('transaction_no')) ? request('transaction_no') : '' ),
													  'referrer_name='.(!empty(request('referrer_name')) ? request('referrer_name') : '' ),
													  'referrer_code='.(!empty(request('referrer_code')) ? request('referrer_code') : '' ),
													  'agent_code='.(!empty(request('agent_code')) ? request('agent_code') : '' ),
																  'agent='.(!empty(request('agent')) ? request('agent') : '' ), 'comm_type='.(!empty(request('comm_type')) ? request('comm_type') : ''), 'status='.(!empty(request('status')) ? request('status') : '') ]) }}"
			class="print-window btn btn-outline-primary" target="_blank">
			<i class="fa fa-print"></i> {{ isset($data['backendlang']['backendlang']['Print']) ? $data['backendlang']['backendlang']['Print'] :'' }}
		</a>
		<a href="{{ route('exportCommissionReport', ['dates='.(!empty(request('dates')) ? request('dates') : $startDate.' - '.$endDate),
													  'yearly='.(!empty(request('this_year')) ? request('this_year') : '' ),
													  'monthly='.(!empty(request('this_month')) ? request('this_month') : '' ),
													  'daily='.(!empty(request('today')) ? request('today') : '' ),
													  'transaction_no='.(!empty(request('transaction_no')) ? request('transaction_no') : '' ),
													  'referrer_name='.(!empty(request('referrer_name')) ? request('referrer_name') : '' ),
													  'referrer_code='.(!empty(request('referrer_code')) ? request('referrer_code') : '' ),
													  'agent_code='.(!empty(request('agent_code')) ? request('agent_code') : '' ),
													  'agent='.(!empty(request('agent')) ? request('agent') : '' ), 'comm_type='.(!empty(request('comm_type')) ? request('comm_type') : ''), 'status='.(!empty(request('status')) ? request('status') : '')])}}"
			target="_blank" class="btn btn-warning">
			<i class="fa fa-download"></i> {{ isset($data['backendlang']['backendlang']['Export']) ? $data['backendlang']['backendlang']['Export'] :'' }}
		</a>
	</div>
	<div class="row" style="overflow: auto;">
		<div class="col-12">
			{{ $commissions->links() }}
			<table class="table table-bordered">
				<thead>
					<tr class="success">
						<th>#</th>
						<th>{{ isset($data['backendlang']['backendlang']['Commission_Date_Time']) ? $data['backendlang']['backendlang']['Commission_Date_Time'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Commission_Type']) ? $data['backendlang']['backendlang']['Commission_Type'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Transaction_Number']) ? $data['backendlang']['backendlang']['Transaction_Number'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Agent_Name']) ? $data['backendlang']['backendlang']['Agent_Name'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Agent_Code']) ? $data['backendlang']['backendlang']['Agent_Code'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Downline_Name']) ? $data['backendlang']['backendlang']['Downline_Code'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Downline_Code']) ? $data['backendlang']['backendlang']['Downline_Name'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Transaction_Amount']) ? $data['backendlang']['backendlang']['Transaction_Amount'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Percentage_Amount']) ? $data['backendlang']['backendlang']['Percentage_Amount'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Commission_Amount']) ? $data['backendlang']['backendlang']['Commission_Amount'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Status']) ? $data['backendlang']['backendlang']['Status'] :'' }}</th>
					</tr>
				</thead>
				<tbody>
					@php
					$totalCommission = 0;
					$a=0;
					@endphp
					@foreach($commissions as $commission)
					<tr>
						<td>{{ $a+1 }}</td>
						<td>{{ $commission->created_at }}</td>
						<td>
							{{ $commission->comm_desc }}
						</td>
						<td>
							@if(!empty($commission->transaction_no))
							{{ $commission->transaction_no }}
							@else
							<i class="fa fa-minus"></i>
							@endif
						</td>
						<td>
							@if(!empty($commission->agentName))
							{{ $commission->agentName }}
							@else
							<i class="fa fa-minus"></i>
							@endif
						</td>
						<td>
							{{ $commission->agentCode }}
						</td>
						<td>
							@if(!empty($commision->from_user) || !empty($commission->buyerName))
							{{ !empty($commision->from_user) ? $commision->from_user : $commission->buyerName }}
							@else
							<i class="fa fa-minus"></i>
							@endif
						</td>
						<td>
							@if(!empty($commission->buyerCode))
							{{ $commission->buyerCode }}
							@else
							<i class="fa fa-minus"></i>
							@endif
						</td>
						<td>
							{{ number_format($commission->product_amount, 2) }}
						</td>
						<td>
							@if($commission->comm_pa_type != 'Percentage')
							RM {{ number_format($commission->comm_pa, 2) }}
							@else
							{{ number_format($commission->comm_pa, 2) }}%
							@endif
						</td>
						<td>{{ $commission->comm_amount }}</td>
						<td>
							@php
								$statusText = ($commission->status == 2) ? (isset($data['backendlang']['backendlang']['Burned']) ? $data['backendlang']['backendlang']['Burned'] : 'Burned') : (isset($data['backendlang']['backendlang']['Approved']) ? $data['backendlang']['backendlang']['Approved'] : 'Approved');
								$statusClass = ($commission->status == 2) ? 'badge bg-danger' : 'badge bg-success';
							@endphp
							<span class="{{ $statusClass }}">{{ $statusText }}</span>
						</td>
					</tr>
					@php
					$a++;
					$totalCommission += $commission->comm_amount;
					@endphp
					@endforeach
					<tr class="warning">
						<td colspan="11">{{ isset($data['backendlang']['backendlang']['Summary']) ? $data['backendlang']['backendlang']['Summary'] :'' }}</td>
						<td>{{ $totalCommission }}</td>
					</tr>
				</tbody>
			</table>
			{{ $commissions->links() }}
		</div>
	</div>
</div>

@endsection
@section('js')
<script type="text/javascript">
	$('input[name=dates]').daterangepicker({
		'applyClass': 'btn-sm btn-success',
		'cancelClass': 'btn-sm btn-outline-danger',
		locale: {
			applyLabel: "{{ isset($data['backendlang']['backendlang']['Apply']) ? $data['backendlang']['backendlang']['Apply'] :''}}",
			cancelLabel: "{{ isset($data['backendlang']['backendlang']['Cancel']) ? $data['backendlang']['backendlang']['Cancel'] :''}}",
			format: 'DD/MM/YYYY',
		}
	});

	$('.grandTotal').html('{{ number_format($totalCommission, 2) }}');
	$('.netTotal').html('{{ number_format($netTotal->netTotalCommission, 2) }}');
</script>
@endsection