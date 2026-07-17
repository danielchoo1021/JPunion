@extends('layouts.admin_app')

@section('content')
<form action="{{ route('commission_slip_report') }}" method="GET">
<div class="form-group">
	<div class="row">
		<div class="col-sm-12">
			<div class="form-group">
				<select class="form-control" name="year">
					<option value="">{{ isset($data['backendlang']['backendlang']['Select_Year']) ? $data['backendlang']['backendlang']['Select_Year'] :'' }}</option>
					<option {{ (!empty($year) && $year == date('Y', strtotime('-5 years'))) ? 'selected' : '' }} value="{{ date('Y', strtotime('-5 years')) }}">{{ date('Y', strtotime('-5 years')) }}</option>
					<option {{ (!empty($year) && $year == date('Y', strtotime('-4 years'))) ? 'selected' : '' }} value="{{ date('Y', strtotime('-4 years')) }}">{{ date('Y', strtotime('-4 years')) }}</option>
					<option {{ (!empty($year) && $year == date('Y', strtotime('-3 years'))) ? 'selected' : '' }} value="{{ date('Y', strtotime('-3 years')) }}">{{ date('Y', strtotime('-3 years')) }}</option>
					<option {{ (!empty($year) && $year == date('Y', strtotime('-2 years'))) ? 'selected' : '' }} value="{{ date('Y', strtotime('-2 years')) }}">{{ date('Y', strtotime('-2 years')) }}</option>
					<option {{ (!empty($year) && $year == date('Y', strtotime('-1 years'))) ? 'selected' : '' }} value="{{ date('Y', strtotime('-1 years')) }}">{{ date('Y', strtotime('-1 years')) }}</option>
					<option {{ (!empty($year) && $year == date('Y')) ? 'selected' : '' }} value="{{ date('Y') }}">{{ date('Y') }}</option>
				</select>
			</div>
		</div>

		<div class="col-sm-12">
			<div class="form-group">
				<select class="form-control" name="month">
					<option value="">{{ isset($data['backendlang']['backendlang']['Select_Month']) ? $data['backendlang']['backendlang']['Select_Month'] :'' }}</option>
					@for($m=1; $m<=12; $m++)
					<option {{ (!empty($month) && $month == sprintf("%02d", $m)) ? 'selected' : '' }} value="{{ sprintf("%02d", $m) }}">{{ $m }}</option>
					@endfor
				</select>
			</div>
		</div>

		<div class="col-sm-12">
			<div class="form-group">
				<input type="text" class="form-control" name="name" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Name']) ? $data['backendlang']['backendlang']['Search_Name'] :'' }}">
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
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
	<div class="row">
		<div class="col-sm-12">
			<div class="form-group">
				<button class="btn btn-outline-primary btn-sm">
					<i class="fa fa-search"></i> {{ isset($data['backendlang']['backendlang']['Search']) ? $data['backendlang']['backendlang']['Search'] :'' }}
				</button>
				<a href="{{ route('commission_slip_report') }}" class="btn btn-warning btn-sm">
					<i class="fa fa-refresh"></i> {{ isset($data['backendlang']['backendlang']['Clear_Search']) ? $data['backendlang']['backendlang']['Clear_Search'] :'' }}
				</a>
			</div>
		</div>
	</div>
</div>
</form>
<div class="form-group" align="right">
	<a href="{{ route('exportMasterCommissionSlip', ['year='.(!empty(request('year')) ? request('year') : date('Y')), 
											 'month='.(!empty(request('month')) ? request('month') : date('m')),
											 'name='.(!empty(request('name')) ? request('name') : '')])}}" target="_blank" class="btn btn-warning">
		<i class="fa fa-download"></i> {{ isset($data['backendlang']['backendlang']['Export_Master_Comm_Slip']) ? $data['backendlang']['backendlang']['Export_Master_Comm_Slip'] :'' }}
	</a>
</div>
<hr>

<div class="row" style="overflow: auto;">
	<div class="col-12">
		<table class="table table-bordered">
			<thead>
				<tr class="success">
					<th>#</th>
					<th>{{ isset($data['backendlang']['backendlang']['Agent']) ? $data['backendlang']['backendlang']['Agent'] :'' }}</th>
					<th>{{ isset($data['backendlang']['backendlang']['Referral_Bonus']) ? $data['backendlang']['backendlang']['Referral_Bonus'] :'' }}</th>
					<th>{{ isset($data['backendlang']['backendlang']['Retail_Profit']) ? $data['backendlang']['backendlang']['Retail_Profit'] :'' }}</th>
					<th>{{ isset($data['backendlang']['backendlang']['Group_Performance_Bonus']) ? $data['backendlang']['backendlang']['Group_Performance_Bonus'] :'' }}</th>
					<th>{{ isset($data['backendlang']['backendlang']['Star_Leader_Bonus']) ? $data['backendlang']['backendlang']['Star_Leader_Bonus'] :'' }}</th>
					<th>{{ isset($data['backendlang']['backendlang']['CEO_Bonus']) ? $data['backendlang']['backendlang']['CEO_Bonus'] :'' }}</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@foreach($merchants as $key => $merchant)
				<tr>
					<td>
						{{ $key + 1 }}
					</td>
					<td>
						{{ $merchant->f_name }} ({{ $merchant->code }})
					</td>
					<td>
						@if(!empty($comm_total[$merchant->code][20]) && !empty($comm_total[$merchant->code][21]))
							{{ $comm_total[$merchant->code][20] + $comm_total[$merchant->code][21] }}
						@elseif(!empty($comm_total[$merchant->code][20]))
							{{ $comm_total[$merchant->code][20] }}
						@elseif(!empty($comm_total[$merchant->code][21]))
							{{ $comm_total[$merchant->code][21] }}
						@else
							-
						@endif
					</td>
					<td>
						@if(!empty($comm_total[$merchant->code][50]))
							{{ $comm_total[$merchant->code][50] }}
						@else
							-
						@endif
					</td>
					<td>
						@if(!empty($comm_total[$merchant->code][11]))
							{{ $comm_total[$merchant->code][11] }}
						@else
							-
						@endif
					</td>
					<td>
						@php
							$star_leader_bonus = 0;

							if(!empty($comm_total[$merchant->code][30])){
								$star_leader_bonus += $comm_total[$merchant->code][30];
							}
							
							if(!empty($comm_total[$merchant->code][31])){
								$star_leader_bonus += $comm_total[$merchant->code][31];
							}

							
							if(!empty($comm_total[$merchant->code][32])){
								$star_leader_bonus += $comm_total[$merchant->code][32];
							}
						@endphp
						{{-- @if(!empty($comm_total[$merchant->code][30]) && !empty($comm_total[$merchant->code][32]))
							{{ $comm_total[$merchant->code][30] + $comm_total[$merchant->code][31] + $comm_total[$merchant->code][32] }}
						@elseif(!empty($comm_total[$merchant->code][30]))
							{{ $comm_total[$merchant->code][30] }}
						@elseif(!empty($comm_total[$merchant->code][32]))
							{{ $comm_total[$merchant->code][32] }}
						@else
							-
						@endif --}}
						@if($star_leader_bonus > 0)
							{{ number_format($star_leader_bonus, 2) }}
						@else
							-
						@endif
					</td>
					<td>
						@if(!empty($comm_total[$merchant->code][40]))
							{{ $comm_total[$merchant->code][40] }}
						@else
							-
						@endif
					</td>
					<td>
						<a href="{{ route('exportCommissionSlip', ['year='.(!empty(request('year')) ? request('year') : date('Y')), 
											 'month='.(!empty(request('month')) ? request('month') : date('m')), 
										     'user_id='.$merchant->code])}}" target="_blank" class="btn">
							<i class="fa fa-download"></i>
						</a>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		{{ $merchants->links() }}
	</div>
</div>

@endsection
@section('js')
<script type="text/javascript">
	$('input[name=dates]').daterangepicker({
		'applyClass' : 'btn-sm btn-success',
		'cancelClass' : 'btn-sm btn-outline-danger',
		locale: {
			applyLabel: "{{ isset($data['backendlang']['backendlang']['Apply']) ? $data['backendlang']['backendlang']['Apply'] :''}}",
			cancelLabel: "{{ isset($data['backendlang']['backendlang']['Cancel']) ? $data['backendlang']['backendlang']['Cancel'] :''}}",
		}
	})
	.prev().on(ace.click_event, function(){
		$(this).next().focus();
	});
</script>
@endsection