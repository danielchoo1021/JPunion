@extends('layouts.admin_app')

@section('content')
<div class="container-box form-group">
	<h3>{{ isset($data['backendlang']['backendlang']['Filter']) ? $data['backendlang']['backendlang']['Filter'] :'' }}</h3>
	<hr>
	<form action="{{ route('cash_wallet_report') }}" method="GET">
	<div class="form-group">
		<div class="row">
			<div class="col-sm-2">
				<div class="form-group">
					<input type="text" class="form-control" name="dates" value="{{ !empty(request('dates')) ? request('dates') : $startDate.' - '.$endDate }}">
				</div>
			</div>

			<div class="col-sm-2">
				<div class="form-group">
					<input type="text" class="form-control" name="user_name" value="{{ !empty(request('user_name')) ? request('user_name') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_User_Name']) ? $data['backendlang']['backendlang']['Search_User_Name'] :'' }}">
				</div>
			</div>

			<div class="col-sm-2">
				<div class="form-group">
					<input type="text" class="form-control" name="user_code" value="{{ !empty(request('user_code')) ? request('user_code') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_User_Code']) ? $data['backendlang']['backendlang']['Search_User_Code'] :'' }}">
				</div>
			</div>

			<div class="col-sm-2">
				<div class="form-group">
					<select class="form-control" name="user_type">
						<option value="">{{ isset($data['backendlang']['backendlang']['Select_User_Type']) ? $data['backendlang']['backendlang']['Select_User_Type'] :'' }}</option>
						<option {{ (!empty(request('user_type')) && request('user_type') == '1') ? 'selected' : '' }} value="1">{{ isset($data['backendlang']['backendlang']['Agent']) ? $data['backendlang']['backendlang']['Agent'] :'' }}</option>
						<option {{ (!empty(request('user_type')) && request('user_type') == '2') ? 'selected' : '' }} value="2">{{ isset($data['backendlang']['backendlang']['Member']) ? $data['backendlang']['backendlang']['Member'] :'' }}</option>
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
		<a href="{{ route('cash_wallet_report') }}" class="btn btn-warning btn-sm">
			<i class="bi bi-arrow-clockwise"></i> {{ isset($data['backendlang']['backendlang']['Clear_Search']) ? $data['backendlang']['backendlang']['Clear_Search'] :'' }}
		</a>
	</div>

	</form>
	<div class="form-group">
		<span class="badge bg-info" style="font-size: 1rem; padding: 10px;">
			{{ isset($data['backendlang']['backendlang']['Dates']) ? $data['backendlang']['backendlang']['Dates'] :'' }}: {{ !empty(request('dates')) ? request('dates') : $startDate.' - '.$endDate }}
		</span>
	</div>
</div>
<div class="container-box form-group">
	<div class="form-group" align="right">
		<a href="{{ route('exportCashWalletReport', ['dates='.(!empty(request('dates')) ? request('dates') : $startDate.' - '.$endDate),
														'user_name='.(!empty(request('user_name')) ? request('user_name') : ''), 
														'user_type='.(!empty(request('user_type')) ? request('user_type') : ''), 
											     		'user_code='.(!empty(request('user_code')) ? request('user_code') : '')])}}" 
														target="_blank" class="btn btn-warning">
			<i class="fa fa-download"></i> {{ isset($data['backendlang']['backendlang']['Export']) ? $data['backendlang']['backendlang']['Export'] :'' }}
		</a>
	</div>
	<div class="row" style="overflow: auto;">
		<div class="col-12">
			{{ $Users->links() }}
			<table class="table table-bordered">
				<thead>
					<tr class="success">
						<th>#</th>
						<th>{{ isset($data['backendlang']['backendlang']['User_Name']) ? $data['backendlang']['backendlang']['User_Name'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['User_Code']) ? $data['backendlang']['backendlang']['User_Code'] :'' }}
							@if(empty(request('user_code_desc')) && empty(request('user_code_asc')))
								<a href="{{ route('cash_wallet_report', ['user_code_desc=DESC']) }}" 
								   class="{{ !empty(request('user_code_desc')) ? 'selected' : '' }}">
									<i class="bi bi-sort-down"></i>
									<input type="hidden" name="sort_data" value="0">
								</a>
							@else
								@if(!empty(request('user_code_desc')))
									<a href="{{ route('cash_wallet_report', ['user_code_asc=ASC']) }}" 
									   class="{{ !empty(request('user_code_asc')) ? 'selected' : '' }}">
										<i class="bi bi-sort-down"></i>
										<input type="hidden" name="sort_data" value="1">
									</a>
								@elseif(!empty(request('user_code_asc')))
									<a href="{{ route('cash_wallet_report', ['user_code_desc=DESC']) }}" 
									   class="{{ !empty(request('user_code_desc')) ? 'selected' : '' }}">
										<i class="bi bi-sort-up"></i>
										<input type="hidden" name="sort_data" value="0">
									</a>
								@endif
							@endif
						</th>
						<th>{{ isset($data['backendlang']['backendlang']['User_Type']) ? $data['backendlang']['backendlang']['User_Type'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Previous_Month_Balance_Amount']) ? $data['backendlang']['backendlang']['Previous_Month_Balance_Amount'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Cash_In']) ? $data['backendlang']['backendlang']['Cash_In'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Cash_Out']) ? $data['backendlang']['backendlang']['Cash_Out'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Current_Balance']) ? $data['backendlang']['backendlang']['Current_Balance'] :'' }}</th>
					</tr>
				</thead>
				<tbody>
					@php
						$a = 0;
						$CurrentBalance = 0;
					@endphp
                    @if (!$Users->isEmpty())
                        @foreach($Users as $User)
                        <tr>
                            <td>{{ $a+1 }}</td>   
                            <td>
								@if($User->status != '3')
									<a href="{{route('cash_wallet_report_detail', $User->code)}}">
									{{ $User->userName}}
									</a>
								@else
									<a href="{{route('cash_wallet_report_detail', $User->code)}}">
									{{ $User->userName}} - {{ isset($data['backendlang']['backendlang']['deleted']) ? $data['backendlang']['backendlang']['deleted'] :''}}
									</a>
								@endif
							</td>
							<td>
								@if($User->status != '3')
								{{ $User->code }}
								@else
								{{ $User->code }} - {{ isset($data['backendlang']['backendlang']['deleted']) ? $data['backendlang']['backendlang']['deleted'] :''}}
								@endif
							</td>
							<td>
								{{ $User->type}}
							</td>
                            <td>
								{{ number_format($previous_balance[$User->code], 2) }}
							</td>
                            <td>
								{{ number_format($total_cash_in[$User->code], 2) }}
							</td>
                            <td>
								{{ number_format($total_cash_out[$User->code], 2) }}
							</td>
							<td>
								{{ number_format($current_balance[$User->code], 2) }}
							</td>
                        </tr>
						@php
							$a++;
						@endphp
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8">{{ isset($data['backendlang']['backendlang']['No_Result_Found']) ? $data['backendlang']['backendlang']['No_Result_Found'] :'' }}</td>
                        </tr>
                    @endif
                </tbody>
			</table>
			{{ $Users->links() }}
		</div>
	</div>
</div>

@endsection
@section('js')
<script type="text/javascript">
	$('input[name=dates]').daterangepicker({
		'applyClass' : 'btn-sm btn-success',
		'cancelClass' : 'btn-sm btn-outline-danger',
		locale: {
			format: 'DD/MM/YYYY',
			applyLabel: "{{ isset($data['backendlang']['backendlang']['Apply']) ? $data['backendlang']['backendlang']['Apply'] :''}}",
			cancelLabel: "{{ isset($data['backendlang']['backendlang']['Cancel']) ? $data['backendlang']['backendlang']['Cancel'] :''}}",
		}
	});

</script>
@endsection