@extends('layouts.admin_app')

@section('content')
<div class="form-group container-box">
	<h3>{{ isset($data['backendlang']['backendlang']['Filter']) ? $data['backendlang']['backendlang']['Filter'] :'' }}</h3>
	<hr>
	<form action="{{ route('agent_sales_report') }}" method="GET">
		<div class="row">
			<div class="col-sm-4">
				<div class="form-group">
					<input type="text" name="dates" value="{{ !empty(request('dates')) ? request('dates') : date('mm/dd/Y',strtotime($startDate)).' - '.date('mm/dd/Y',strtotime($endDate)) }}" class="form-control">
				</div>
			</div>

			<div class="col-sm-3">
				<div class="form-group">
					<input type="text" class="form-control" name="code" value="{{ !empty(request('code')) ? request('code') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Agent_Code']) ? $data['backendlang']['backendlang']['Search_Agent_Code'] :'' }}">
				</div>
			</div>

			<div class="col-sm-3">
				<div class="form-group">
					<input type="text" class="form-control" name="agent" value="{{ !empty(request('agent')) ? request('agent') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Agent_Name']) ? $data['backendlang']['backendlang']['Search_Agent_Name'] :'' }}">
				</div>
			</div>

			<div class="col-sm-3">
				<div class="form-group">
					<input type="text" class="form-control" name="referrer_code" value="{{ !empty(request('referrer_code')) ? request('referrer_code') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Referral_Code']) ? $data['backendlang']['backendlang']['Search_Referral_Code'] :'' }}">
				</div>
			</div>

			<div class="col-sm-3">
				<div class="form-group">
					<input type="text" class="form-control" name="referrer_name" value="{{ !empty(request('referrer_name')) ? request('referrer_name') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Referral_Name']) ? $data['backendlang']['backendlang']['Search_Referral_Name'] :'' }}">
				</div>
			</div>
		</div>
		<div class="form-group">
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
			<div class="row">
				<div class="col-sm-4">
					<div class="form-group">
						<button class="btn btn-outline-primary btn-sm">
							<i class="bi bi-search"></i> {{ isset($data['backendlang']['backendlang']['Search']) ? $data['backendlang']['backendlang']['Search'] :'' }}
						</button>
						<a href="{{ route('agent_sales_report') }}" class="btn btn-warning btn-sm">
							<i class="bi bi-arrow-clockwise"></i> {{ isset($data['backendlang']['backendlang']['Clear_Search']) ? $data['backendlang']['backendlang']['Clear_Search'] :'' }}
						</a>
					</div>
				</div>
			</div>
		</div>
	</form>

	<div class="form-group">
		<span class="badge bg-info" style="font-size: 1rem; padding: 10px;">
			{{ isset($data['backendlang']['backendlang']['Dates']) ? $data['backendlang']['backendlang']['Dates'] :'' }}: {{ !empty(request('year')) ? request('dates') : $startDate.' - '.$endDate }}
		</span>
	</div>
</div>

<div class="form-group container-box">
	<div class="form-group" align="right">
		<!-- <a href="{{ route('print_agent_sales_report', ['dates='.(!empty(request('dates')) ? request('dates') : $startDate.' - '.$endDate), 
												   	   'buyer='.(!empty(request('buyer')) ? request('buyer') : '')]) }}" class="print-window btn btn-outline-primary" target="_blank">
			<i class="fa fa-print"></i> Print
		</a> -->
		<a href="{{ route('exportAgentReport', ['dates='.(!empty(request('dates')) ? request('dates') : $startDate.' - '.$endDate), 
											     'agent='.(!empty(request('agent')) ? request('agent') : ''), 
											     'code='.(!empty(request('code')) ? request('code') : ''),
												 'referrer_code='.(!empty(request('referrer_code')) ? request('referrer_code') : '' ),
												 'referrer_name='.(!empty(request('referrer_name')) ? request('referrer_name') : '' )])}}" target="_blank" class="btn btn-warning btn-sm">
			<i class="bi bi-download"></i> {{ isset($data['backendlang']['backendlang']['Export']) ? $data['backendlang']['backendlang']['Export'] :'' }}
		</a>

	</div>

	<div class="row" style="overflow: auto;">
		<div class="col-12">
			<table class="table table-bordered">
				<thead>
					<tr class="info">
						<th>#</th>
						<th>{{ isset($data['backendlang']['backendlang']['Joined_Date']) ? $data['backendlang']['backendlang']['Joined_Date'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Code']) ? $data['backendlang']['backendlang']['Code'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Name']) ? $data['backendlang']['backendlang']['Name'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Referral_Code']) ? $data['backendlang']['backendlang']['Referral_Code'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Referral_Name']) ? $data['backendlang']['backendlang']['Referral_Name'] :'' }}</th>
						<!-- <th>Personal Commission</th> -->
						<th>{{ isset($data['backendlang']['backendlang']['Commission']) ? $data['backendlang']['backendlang']['Commission'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Sales']) ? $data['backendlang']['backendlang']['Sales'] :'' }}</th>

					</tr>
				</thead>
				<tbody>
					@php
					$totalQty = 0;
					$totalsfee = 0;
					$totalDiscount = 0;
					$totalTax = 0;
					$totalgrand = 0;
					@endphp

					@if (!$merchants->isEmpty())
					@foreach($merchants as $key => $merchant)
					<tr class="get-details" style="cursor: pointer;" data-id="{{ $merchant->code }}">
						<td>
							{{ $key+1 }}
							<input type="hidden" class="row_id" value="{{ $merchant->id }}">
						</td>
						<td>{{ $merchant->created_at }}</td>
						<td>
							{{ $merchant->display_code }}{{ $merchant->display_running_no }}

						</td>
						<td>{{ $merchant->f_name }} {{ $merchant->l_name }}</td>
						<td>{{ $merchant->upline_code }}</td>
						<td>{{ $merchant->upline_name }}</td>
						<!-- <td>{{ number_format(!empty($personal_comm[$merchant->code]->totalCommission) ? $personal_comm[$merchant->code]->totalCommission : 0 ,2)}}</td> -->
						<td>{{ number_format(!empty($total_comm[$merchant->code]->totalCommission) ? $total_comm[$merchant->code]->totalCommission : 0 ,2)}}</td>
						<td>
							{{number_format(!empty($total_sales[$merchant->code]->totalSales) ? $total_sales[$merchant->code]->totalSales : 0,2)}}
						</td>
					</tr>
					@endforeach
					@else
					<tr>
						<td colspan="12">{{ isset($data['backendlang']['backendlang']['No_Result_Found']) ? $data['backendlang']['backendlang']['No_Result_Found'] :'' }}</td>
					</tr>
					@endif

					<tr class="warning">
						<td colspan="5">
							<b>{{ isset($data['backendlang']['backendlang']['Summary']) ? $data['backendlang']['backendlang']['Summary'] :'' }}</b>
						</td>

						<td align="right">
							<b>{{ number_format($sumTotalCommissions, 2) }}</b>
						</td>
						<td align="right">
							<b>{{ number_format($sumTotalCommissions1, 2) }}</b>
						</td>
						<td align="right">
							<b>{{ number_format($sumTotalSales, 2) }}</b>
						</td>
					</tr>
				</tbody>
			</table>
			{{ $merchants->links() }}
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

	$('.grandTotal').html('{{ number_format($totalgrand, 2) }}');

	$("#datepicker").datepicker({
		format: "mm-yyyy",
		viewMode: "months",
		minViewMode: "months"
	});



	$('.get-details').click(function(e) {

		var url = "{{ route('agent_sales_report_detail', ':code') }}";
		url = url.replace(':code', $(this).data('id'));

		window.location.href = url;
	})
</script>
@endsection