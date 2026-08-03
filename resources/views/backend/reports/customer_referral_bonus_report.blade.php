@extends('layouts.admin_app')

@section('content')
<div class="form-group container-box">
	<h3>{{ isset($data['backendlang']['backendlang']['Filter']) ? $data['backendlang']['backendlang']['Filter'] :'' }}</h3>
	<hr>
	<form action="{{ route('customer_referral_bonus_report') }}" method="GET">
		<div class="row">
			<div class="col-sm-3">
				<div class="form-group">
					<input type="text" class="form-control" name="agent_code" value="{{ !empty(request('agent_code')) ? request('agent_code') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Agent_Code']) ? $data['backendlang']['backendlang']['Search_Agent_Code'] :'' }}">
				</div>
			</div>

			<div class="col-sm-3">
				<div class="form-group">
					<input type="text" class="form-control" name="agent_name" value="{{ !empty(request('agent_name')) ? request('agent_name') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Agent_Name']) ? $data['backendlang']['backendlang']['Search_Agent_Name'] :'' }}">
				</div>
			</div>

			<div class="col-sm-3">
				<div class="form-group">
					<input type="text" class="form-control" name="customer_code" value="{{ !empty(request('customer_code')) ? request('customer_code') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Customer_Code']) ? $data['backendlang']['backendlang']['Search_Customer_Code'] :'' }}">
				</div>
			</div>

			<div class="col-sm-3">
				<div class="form-group">
					<input type="text" class="form-control" name="customer_name" value="{{ !empty(request('customer_name')) ? request('customer_name') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Customer_Name']) ? $data['backendlang']['backendlang']['Search_Customer_Name'] :'' }}">
				</div>
			</div>
		</div>
		<div class="form-group">
			<div class="row">
				<div class="col-sm-2">
					<div class="form-group">
						{{ isset($data['backendlang']['backendlang']['Status']) ? $data['backendlang']['backendlang']['Status'] :'' }}: <br>
						<select class="input-small form-control" name="claimed">
							<option value="" {{ (!request()->has('claimed') || request('claimed') === '') ? 'selected' : '' }}>{{ isset($data['backendlang']['backendlang']['All']) ? $data['backendlang']['backendlang']['All'] :'' }}</option>
							<option value="1" {{ (request('claimed') === '1') ? 'selected' : '' }}>{{ isset($data['backendlang']['backendlang']['Claimed']) ? $data['backendlang']['backendlang']['Claimed'] :'' }}</option>
							<option value="0" {{ (request('claimed') === '0') ? 'selected' : '' }}>{{ isset($data['backendlang']['backendlang']['Pending']) ? $data['backendlang']['backendlang']['Pending'] :'' }}</option>
						</select>
					</div>
				</div>

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
			<div class="row">
				<div class="col-sm-4">
					<div class="form-group">
						<button class="btn btn-outline-primary btn-sm">
							<i class="bi bi-search"></i> {{ isset($data['backendlang']['backendlang']['Search']) ? $data['backendlang']['backendlang']['Search'] :'' }}
						</button>
						<a href="{{ route('customer_referral_bonus_report') }}" class="btn btn-warning btn-sm">
							<i class="bi bi-arrow-clockwise"></i> {{ isset($data['backendlang']['backendlang']['Clear_Search']) ? $data['backendlang']['backendlang']['Clear_Search'] :'' }}
						</a>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>

<div class="form-group container-box">
	<div class="form-group" align="right">
		<a href="{{ route('exportCustomerReferralBonusReport', ['agent_code'=>request('agent_code'), 'agent_name'=>request('agent_name'), 'customer_code'=>request('customer_code'), 'customer_name'=>request('customer_name'), 'claimed'=>request('claimed')]) }}" target="_blank" class="btn btn-warning btn-sm">
			<i class="bi bi-download"></i> {{ isset($data['backendlang']['backendlang']['Export']) ? $data['backendlang']['backendlang']['Export'] :'' }}
		</a>
	</div>

	<div class="row" style="overflow: auto;">
		<div class="col-12">
			<table class="table table-bordered">
				<thead>
					<tr class="info">
						<th>#</th>
						<th>{{ isset($data['backendlang']['backendlang']['Agent']) ? $data['backendlang']['backendlang']['Agent'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Customer']) ? $data['backendlang']['backendlang']['Customer'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Agent_Level']) ? $data['backendlang']['backendlang']['Agent_Level'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Paid_Orders']) ? $data['backendlang']['backendlang']['Paid_Orders'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Target']) ? $data['backendlang']['backendlang']['Target'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Bonus_Amount']) ? $data['backendlang']['backendlang']['Bonus_Amount'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Status']) ? $data['backendlang']['backendlang']['Status'] :'' }}</th>
					</tr>
				</thead>
				<tbody>
					@if (!$progress->isEmpty())
					@foreach($progress as $key => $row)
					<tr>
						<td>{{ $key+1 }}</td>
						<td>{{ $row->agent_display_code }}{{ $row->agent_display_running_no }} - {{ $row->agent_f_name }} {{ $row->agent_l_name }}</td>
						<td>{{ $row->customer_display_code }}{{ $row->customer_display_running_no }} - {{ $row->customer_f_name }} {{ $row->customer_l_name }}</td>
						<td>{{ $row->agent_lvl_name }}</td>
						<td>{{ $row->paid_order_count }}</td>
						<td>{{ !empty($row->target_orders) ? $row->target_orders : '-' }}</td>
						<td>{{ !empty($row->bonus_amount) ? number_format($row->bonus_amount, 2) : '-' }}</td>
						<td>
							@if(empty($row->target_orders))
								<span class="badge bg-secondary">{{ isset($data['backendlang']['backendlang']['Not_Configured']) ? $data['backendlang']['backendlang']['Not_Configured'] :'' }}</span>
							@elseif($row->customer_referral_bonus_claimed)
								<span class="badge bg-success">{{ isset($data['backendlang']['backendlang']['Claimed']) ? $data['backendlang']['backendlang']['Claimed'] :'' }}</span>
							@else
								<span class="badge bg-warning">{{ isset($data['backendlang']['backendlang']['Pending']) ? $data['backendlang']['backendlang']['Pending'] :'' }}</span>
							@endif
						</td>
					</tr>
					@endforeach
					@else
					<tr>
						<td colspan="8">{{ isset($data['backendlang']['backendlang']['No_Result_Found']) ? $data['backendlang']['backendlang']['No_Result_Found'] :'' }}</td>
					</tr>
					@endif
				</tbody>
			</table>
			{{ $progress->links() }}
		</div>
	</div>
</div>
@endsection
