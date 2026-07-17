@extends('layouts.admin_app')

@section('content')
<div class="form-group container-box">
	<h3>{{ isset($data['backendlang']['backendlang']['Filter']) ? $data['backendlang']['backendlang']['Filter'] :'' }}</h3>
	<hr>
	<form action="{{ route('cash_wallet_report_detail', $code) }}" method="GET">
		<div class="row">
			<div class="col-sm-4">
				<div class="form-group">
					<input type="text" name="dates"value="{{ !empty(request('dates')) ? request('dates') : date('mm/dd/Y',strtotime($startDate)).' - '.date('mm/dd/Y',strtotime($endDate)) }}" class="form-control">
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
						<a href="{{ route('cash_wallet_report_detail', $code) }}" class="btn btn-warning btn-sm">
							<i class="bi bi-arrow-clockwise"></i> {{ isset($data['backendlang']['backendlang']['Clear_Search']) ? $data['backendlang']['backendlang']['Clear_Search'] :'' }}
						</a>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>

<div class="form-group container-box">
	@if(!empty($CashDetail) && isset($CashDetail[0]['status']))
		@if($CashDetail[0]['status'] != '3')
		<h3>{{ $code }}{{ isset($data['backendlang']['backendlang']['S_Cash_Wallet_Report_Detail']) ? $data['backendlang']['backendlang']['S_Cash_Wallet_Report_Detail'] :'' }}</h3>
		@else
		<h3>{{ isset($data['backendlang']['backendlang']['deleted']) ? $data['backendlang']['backendlang']['deleted'] :'' }} - {{ $code }}{{ isset($data['backendlang']['backendlang']['S_Cash_Wallet_Report_Detail']) ? $data['backendlang']['backendlang']['S_Cash_Wallet_Report_Detail'] :'' }}</h3>
		@endif
	@else
		<h3>{{ $code }}{{ isset($data['backendlang']['backendlang']['S_Cash_Wallet_Report_Detail']) ? $data['backendlang']['backendlang']['S_Cash_Wallet_Report_Detail'] :'' }}</h3>
	@endif
	<hr>
	<div class="row" style="overflow: auto;">
		<div class="col-12">
			<table class="table table-bordered">
				<thead>
					<tr class="info">
						<th>#</th>
						<th>{{ isset($data['backendlang']['backendlang']['Date']) ? $data['backendlang']['backendlang']['Date'] :'' }}</th>
                        <th>{{ isset($data['backendlang']['backendlang']['Description']) ? $data['backendlang']['backendlang']['Description'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Cash_In']) ? $data['backendlang']['backendlang']['Cash_In'] :'' }} </th>
						<th>{{ isset($data['backendlang']['backendlang']['Cash_Out']) ? $data['backendlang']['backendlang']['Cash_Out'] :'' }}</th>
					</tr>
				</thead>
				<tbody>
					@php
						$a = 0;
						$totalCashIn = 0;
						$totalCashOut = 0;
					@endphp
					@if(!empty($CashDetail))
						@foreach($CashDetail as $detail)
						<tr>
							<td>
								{{ $a+1 }}
							</td>
							<td>
								{{ $detail['dates'] }}
							</td>
							<td>
								@if(!empty($detail['number']))
									{{ $detail['source'] }} - {{$detail['number'] }}
								@else
									{{$detail['source'] }}
								@endif
							</td>
							<td>
								{{ number_format($detail['in'], 2) }}
							</td>
							<td>
								{{ number_format($detail['out'], 2) }}
							</td>
						</tr>
						@php
							$a++;
							$totalCashIn += $detail['in'];
							$totalCashOut += $detail['out'];
						@endphp							
						@endforeach
						@else
						<tr>
							<td colspan="5">{{ isset($data['backendlang']['backendlang']['No_Result_Found']) ? $data['backendlang']['backendlang']['No_Result_Found'] :'' }}</td>
						</tr>
					@endif
					<tr class="warning">
						<td style=""  colspan="3">
							<b>{{ isset($data['backendlang']['backendlang']['Total']) ? $data['backendlang']['backendlang']['Total'] :'' }}</b>
						</td>
						<td style=" text-align: left;" >
							<b>{{ number_format($totalCashIn, 2) }}</b>
						</td>
						<td style=" text-align: left;" >
							<b>{{ number_format($totalCashOut, 2) }}</b>
						</td>							
					</tr>
				</tbody>
			</table>
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
			applyLabel: "{{ isset($data['backendlang']['backendlang']['Apply']) ? $data['backendlang']['backendlang']['Apply'] :''}}",
			cancelLabel: "{{ isset($data['backendlang']['backendlang']['Cancel']) ? $data['backendlang']['backendlang']['Cancel'] :''}}",
			format: 'DD/MM/YYYY',
		}
	});

</script>
@endsection