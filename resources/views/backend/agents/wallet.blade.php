@extends('layouts.admin_app')

@section('content')
<div class="container-box form-group">
	<h4>{{ isset($data['backendlang']['backendlang']['Filter']) ? $data['backendlang']['backendlang']['Filter'] :'' }}</h4>
	<form action="{{ route('agent_wallet') }}" method="GET">
	<div class="row">
		<div class="col-sm-2">
			<div class="form-group">
				<input type="text" class="form-control" name="code" value="{{ !empty('code') && request('code') ? request('code') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Code']) ? $data['backendlang']['backendlang']['Search_Code'] :'' }}">
			</div>
		</div>
		<div class="col-sm-2">
			<div class="form-group">
				<input type="text" class="form-control" name="agent_name" value="{{ !empty('agent_name') && request('agent_name') ? request('agent_name') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Name']) ? $data['backendlang']['backendlang']['Search_Name'] :'' }}">
			</div>
		</div>
		<div class="col-sm-2">
			<div class="form-group">
				<select class="form-control" name="status">
					<option value="">{{ isset($data['backendlang']['backendlang']['Search_Status']) ? $data['backendlang']['backendlang']['Search_Status'] :'' }}</option>
					<option {{ (!empty(request('status')) && request('status') == '1') ? 'selected' : '' }} value="1">{{ isset($data['backendlang']['backendlang']['Active']) ? $data['backendlang']['backendlang']['Active'] :'' }}</option>
					<option {{ (!empty(request('status')) && request('status') == '2') ? 'selected' : '' }} value="2">{{ isset($data['backendlang']['backendlang']['Inactive']) ? $data['backendlang']['backendlang']['Inactive'] :'' }}</option>
				</select>
			</div>
		</div>
	</div>

	<div class="form-group">
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group">
					<div style="color: black;">{{ isset($data['backendlang']['backendlang']['Row_Per_Page']) ? $data['backendlang']['backendlang']['Row_Per_Page'] :'' }}:</div>
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
		<a href="{{ route('agent_wallet') }}" class="btn btn-warning btn-sm">
			<i class="bi bi-arrow-clockwise"></i> {{ isset($data['backendlang']['backendlang']['Clear_Search']) ? $data['backendlang']['backendlang']['Clear_Search'] :'' }}
		</a>
	</div>
	</form>
</div>

<div class="container-box form-group">
	<div class="row" style="overflow: auto;">
		{{ $agents->links() }}
		<div class="col-12">
			<table class="table table-bordered">
				<thead>
					<tr class="info">
						<th>#</th>
						@if(Auth::user()->permission_lvl == 1)
						<th>{{ isset($data['backendlang']['backendlang']['Merchant']) ? $data['backendlang']['backendlang']['Merchant'] :'' }}</th>
						@endif
						<th>{{ isset($data['backendlang']['backendlang']['Code']) ? $data['backendlang']['backendlang']['Code'] :'' }}
							@if(empty(request('code_desc')) && empty(request('code_asc')))
								<a href="{{ route('agent_wallet', ['code_desc=DESC']) }}" 
								   class="{{ !empty(request('code_desc')) ? 'selected' : '' }}">
									<i class="bi bi-sort-down"></i>
									<input type="hidden" name="sort_data" value="0">
								</a>
							@else
								@if(!empty(request('code_desc')))
									<a href="{{ route('agent_wallet', ['code_asc=ASC']) }}" 
									   class="{{ !empty(request('code_asc')) ? 'selected' : '' }}">
										<i class="bi bi-sort-down"></i>
										<input type="hidden" name="sort_data" value="1">
									</a>
								@elseif(!empty(request('code_asc')))
									<a href="{{ route('agent_wallet', ['code_desc=DESC']) }}" 
									   class="{{ !empty(request('code_desc')) ? 'selected' : '' }}">
										<i class="bi bi-sort-up"></i>
										<input type="hidden" name="sort_data" value="0">
									</a>
								@endif
							@endif
						</th>
						<th>{{ isset($data['backendlang']['backendlang']['Name']) ? $data['backendlang']['backendlang']['Name'] :'' }}
							@if(empty(request('name_desc')) && empty(request('name_asc')))
								<a href="{{ route('agent_wallet', ['name_desc=DESC']) }}" 
								   class="{{ !empty(request('name_desc')) ? 'selected' : '' }}">
									<i class="bi bi-sort-down"></i>
									<input type="hidden" name="sort_data" value="0">
								</a>
							@else
								@if(!empty(request('name_desc')))
									<a href="{{ route('agent_wallet', ['name_asc=ASC']) }}" 
									   class="{{ !empty(request('name_asc')) ? 'selected' : '' }}">
										<i class="bi bi-sort-down"></i>
										<input type="hidden" name="sort_data" value="1">
									</a>
								@elseif(!empty(request('name_asc')))
									<a href="{{ route('agent_wallet', ['name_desc=DESC']) }}" 
									   class="{{ !empty(request('name_desc')) ? 'selected' : '' }}">
										<i class="bi bi-sort-up"></i>
										<input type="hidden" name="sort_data" value="0">
									</a>
								@endif
							@endif
						</th>
						
						<th>{{ isset($data['backendlang']['backendlang']['Cash_Wallet_Balance']) ? $data['backendlang']['backendlang']['Cash_Wallet_Balance'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Topup_Wallet_Balance']) ? $data['backendlang']['backendlang']['Topup_Wallet_Balance'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Point_Wallet_Balance']) ? $data['backendlang']['backendlang']['Point_Wallet_Balance'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Status']) ? $data['backendlang']['backendlang']['Status'] :'' }}
							@if (empty(request('status_desc')) && empty(request('status_asc')))
								<a href="{{ route('agent_wallet', ['status_desc=DESC']) }}"
									class="{{ !empty(request('status_desc')) ? 'selected' : '' }}">
									<i class="bi bi-sort-down"></i>
									<input type="hidden" name="sort_data" value="0">
								</a>
							@else
								@if (!empty(request('status_desc')))
									<a href="{{ route('agent_wallet', ['status_asc=ASC']) }}"
										class="{{ !empty(request('status_asc')) ? 'selected' : '' }}">
										<i class="bi bi-sort-down"></i>
										<input type="hidden" name="sort_data" value="1">
									</a>
								@elseif(!empty(request('status_asc')))
									<a href="{{ route('agent_wallet', ['status_desc=DESC']) }}"
										class="{{ !empty(request('status_desc')) ? 'selected' : '' }}">
										<i class="bi bi-sort-up"></i>
										<input type="hidden" name="sort_data" value="0">
									</a>
								@endif
							@endif
						</th>
						<th>{{ isset($data['backendlang']['backendlang']['Date']) ? $data['backendlang']['backendlang']['Date'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Action']) ? $data['backendlang']['backendlang']['Action'] :'' }}</th>
					</tr>
				</thead>
				<tbody>
					@if (!$agents->isEmpty())
					@foreach($agents as $key => $agent)
					<tr>
						<td>
							{{ $key+1 }}
							<input type="hidden" class="row_id" value="{{ $agent->id }}">
						</td>
						@if(Auth::user()->permission_lvl == 1)
						<td>
							{{ !empty($agent->get_merchant->f_name) ? $agent->get_merchant->f_name : 'By Admin' }}
							<br>
							({{ !empty($agent->get_merchant->code) ? $agent->get_merchant->code : 'AD000002' }})
						</td>
						@endif
						<td>{{ $agent->display_code }}{{ $agent->display_running_no }}</td>
						<td>{{ $agent->f_name }}</td>
						<td>{{ !empty($get_cash_wallet_balance[$agent->code]) ? number_format($get_cash_wallet_balance[$agent->code], 2) : '0.00' }}</td>
						<td>{{ !empty($get_topup_wallet_balance[$agent->code]) ? number_format($get_topup_wallet_balance[$agent->code], 2) : '0.00' }}</td>
						<td>{{ !empty($get_point_wallet_balance[$agent->code]) ? number_format($get_point_wallet_balance[$agent->code], 2) : '0.00' }}</td>
						<td>
							@if ($agent->status == 1)
 								<span class="badge bg-success">
									{{ $data['backendlang']['backendlang']['Active'] ?? '' }}
								</span>
							@else
								<span class="badge bg-danger">
									{{ $data['backendlang']['backendlang']['Inactive'] ?? '' }}
								</span>
							@endif
						</td>
						<td>{{ $agent->created_at }}</td>
						<td align="center">
							{{-- @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['agent-wallet-history']))
								<a href="{{ route('agent.agents.edit', $agent->id) }}" class=" btn btn-outline-primary btn-sm" title="{{ isset($data['backendlang']['backendlang']['History']) ? $data['backendlang']['backendlang']['History'] :'' }}">
									<i class="ace-icon bi bi-eye bigger-130"></i>
								</a>
							@endif --}}
							
							@if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['agent-wallet-adjust']))
								<a href="{{ route('adjustCash', [$agent->id]) }}" class="blue btn btn-outline-info btn-sm" title="{{ isset($data['backendlang']['backendlang']['Adjust_Cash_Wallet']) ? $data['backendlang']['backendlang']['Adjust_Cash_Wallet'] :'' }}">
									<i class="ace-icon bi bi-plus bigger-130"></i>
								</a>
							@endif
							
							@if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['agent-wallet-adjust']))
								<a href="{{ route('AdjustTopup', [$agent->id]) }}" class="blue btn btn-outline-danger btn-sm" title="{{ isset($data['backendlang']['backendlang']['Adjust_Topup_Wallet']) ? $data['backendlang']['backendlang']['Adjust_Topup_Wallet'] :'' }}">
									<i class="ace-icon bi bi-plus bigger-130"></i>
								</a>
							@endif

							@if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['agent-wallet-adjust']))
								<a href="{{ route('adjustPoint', [$agent->id]) }}" class="blue btn btn-outline-warning btn-sm" title="{{ isset($data['backendlang']['backendlang']['Adjust_Point_Wallet']) ? $data['backendlang']['backendlang']['Adjust_Point_Wallet'] :'' }}">
									<i class="ace-icon bi bi-plus bigger-130"></i>
								</a>
							@endif

							@if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['agent-wallet-adjust']))
								<a href="{{ route('TransferCashToTopup', [$agent->id]) }}" class="blue btn btn-outline-primary btn-sm" title="{{ isset($data['backendlang']['backendlang']['Transfer_Cash_To_Topup']) ? $data['backendlang']['backendlang']['Transfer_Cash_To_Topup'] :'' }}">
									<i class="ace-icon bi bi-currency-exchange bigger-130"></i>
								</a>
							@endif
						</td>
					</tr>
					@endforeach
					@else
					<tr>
						<td colspan="15">{{ isset($data['backendlang']['backendlang']['No_Result_Found']) ? $data['backendlang']['backendlang']['No_Result_Found'] :'' }}</td>
					</tr>
					@endif
				</tbody>
			</table>
			{{ $agents->links() }}
		</div>
	</div>
</div>
@endsection