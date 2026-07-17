@extends('layouts.admin_app')

@section('content')
<div class="form-group container-box">
	<h3>{{ isset($data['backendlang']['backendlang']['Filter']) ? $data['backendlang']['backendlang']['Filter'] :'' }}</h3>
	<hr>
	<form action="{{ route('agent_sales_report_detail', $agent->code) }}" method="GET">
		<div class="row">
			<div class="col-sm-4">
				<div class="form-group">
							<!-- 	<div class="row">
									<div class="col-6">
										<select name="month" class="form-control month">
											<option value="01" {{ (!empty(request('month')) && request('month') == '01') ? 'selected' : '' }}>January</option>
											<option value="02" {{ (!empty(request('month')) && request('month') == '02') ? 'selected' : '' }}>Febuary</option>
											<option value="03" {{ (!empty(request('month')) && request('month') == '03') ? 'selected' : '' }}>March</option>
											<option value="04" {{ (!empty(request('month')) && request('month') == '04') ? 'selected' : '' }}>April</option>
											<option value="05" {{ (!empty(request('month')) && request('month') == '05') ? 'selected' : '' }}>May</option>
											<option value="06" {{ (!empty(request('month')) && request('month') == '06') ? 'selected' : '' }}>June</option>
											<option value="07" {{ (!empty(request('month')) && request('month') == '07') ? 'selected' : '' }}>July</option>
											<option value="08" {{ (!empty(request('month')) && request('month') == '08') ? 'selected' : '' }}>August</option>
											<option value="09" {{ (!empty(request('month')) && request('month') == '09') ? 'selected' : '' }}>September</option>
											<option value="10" {{ (!empty(request('month')) && request('month') == '10') ? 'selected' : '' }}>October</option>
											<option value="11" {{ (!empty(request('month')) && request('month') == '11') ? 'selected' : '' }}>November</option>
											<option value="12" {{ (!empty(request('month')) && request('month') == '12') ? 'selected' : '' }}>December</option>
										</select>
									</div>
									<div class="col-6">
										<select name="year" class="form-control year">
											<option value="2023" {{ (!empty(request('year')) && request('year') == '2023') ? 'selected' : '' }}>2023</option>
											<option value="2022" {{ (!empty(request('year')) && request('year') == '2022') ? 'selected' : '' }}>2022</option>
										</select>
									</div>
								</div> -->
								<input type="text" name="dates"value="{{ !empty(request('dates')) ? request('dates') : date('mm/dd/Y',strtotime($startDate)).' - '.date('mm/dd/Y',strtotime($endDate)) }}" class="form-control">
				</div>
			</div>

			<!-- <div class="col-sm-2">
				<div class="form-group">
					<input type="text" class="form-control" name="item_code" value="{{ !empty(request('item_code')) ? request('item_code') : '' }}" placeholder="Search Item Code..">
				</div>
			</div> -->

			<!-- <div class="col-sm-3">
				<div class="form-group">
					<input type="text" class="form-control" name="agent" value="{{ !empty(request('agent')) ? request('agent') : '' }}" placeholder="Search Agent..">
				</div>
			</div> -->
		<!-- 	<div class="col-sm-2">
				<div class="form-group">
					<select class="form-control" name="status">
						<option value="">Select Status</option>
						<option {{ (!empty(request('status')) && request('status') == '1') ? 'selected' : '' }} value="1">Paid</option>
						<option {{ (!empty(request('status')) && request('status') == '99') ? 'selected' : '' }} value="99">Unpaid</option>
					</select>
				</div>
			</div> -->

			
		</div>

		<div class="form-group">
			<div class="row">
				<div class="col-sm-2">
					<div class="form-group">
					{{ isset($data['backendlang']['backendlang']['Row_Per_Page']) ? $data['backendlang']['backendlang']['Row_Per_Page'] :'' }}: <br>
						<select class="input-small" name="per_page">
							<option value="10">10</option>
							<option value="20">20</option>
							<option value="50">50</option>
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
						<a href="{{ route('agent_sales_report_detail', $agent->code) }}" class="btn btn-warning btn-sm">
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
		<a href="{{ route('print_agent_sales_report_detail', [$agent->code, 'dates='.(!empty(request('dates')) ? request('dates') : $startDate.' - '.$endDate)]) }}" class="print-window btn btn-outline-primary" target="_blank">
			<i class="bi bi-printer"></i> {{ isset($data['backendlang']['backendlang']['Print']) ? $data['backendlang']['backendlang']['Print'] :'' }}
		</a>
	</div> 
	<h3>{{ $agent->f_name }}{{ isset($data['backendlang']['backendlang']['S_Sales_Report']) ? $data['backendlang']['backendlang']['S_Sales_Report'] :'' }}</h3>
	<hr>
	<div class="row" style="overflow: auto;">
		<div class="col-12">
			<table class="table table-bordered">
				<thead>
					<tr class="info">
						<th>#</th>
						<th>{{ isset($data['backendlang']['backendlang']['Date']) ? $data['backendlang']['backendlang']['Date'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Transaction_No']) ? $data['backendlang']['backendlang']['Transaction_No'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Product_Name']) ? $data['backendlang']['backendlang']['Product_Name'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Unit_Price']) ? $data['backendlang']['backendlang']['Unit_Price'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Quantity']) ? $data['backendlang']['backendlang']['Quantity'] :'' }}</th>
						<!-- <th>Get PV</th> -->
					</tr>
				</thead>
				<tbody>
					@php
						$totalQty = 0;
						$totalsfee = 0;
						$totalDiscount = 0;
						$totalTax = 0;
						$totalgrand = 0;
						$totaluPrice = 0;
						$totalq = 0;
						$totalpv = 0;
					@endphp

					@if(!$all->isEmpty())
						@foreach($all as $key => $merchant)
						@php
							$span_row = 0;

							$span_row = count($details[$merchant->transaction_no]);

							$row_count = count($details[$merchant->transaction_no])+1;
						@endphp
						<tr>
							@if($span_row >= 1)
							<td rowspan="{{ $row_count }}">
								{{ $key+1 }}
								<input type="hidden" class="row_id" value="{{ $merchant->id }}">
							</td>
							<td rowspan="{{ $row_count }}">{{ $merchant->created_at }}</td>
							<td rowspan="{{ $row_count }}">{{ $merchant->transaction_no }}</td>
							@endif
						</tr>
							@foreach($details[$merchant->transaction_no] as $detail)
							<tr>
								<td>
									{{ $detail->product_name }}
								</td>
								<td>
									{{ $detail->unit_price }}
								</td>
								<td>
									{{ $detail->quantity }}
								</td>
								<!-- <td>
									{{ $detail->get_pv }}
								</td> -->
							</tr>
							@php
						$totaluPrice += $detail->unit_price;
						$totalq += $detail->quantity ;
						$totalpv += $detail->get_pv ;
						@endphp
							@endforeach
							
						@endforeach
						
					@else
					<tr>
						<td colspan="15">{{ isset($data['backendlang']['backendlang']['No_Result_Found']) ? $data['backendlang']['backendlang']['No_Result_Found'] :'' }}</td>
					</tr>
					@endif
					<tr class="warning">
						<td style=""  colspan="4">
							<b>{{ isset($data['backendlang']['backendlang']['Page_Summary']) ? $data['backendlang']['backendlang']['Page_Summary'] :'' }}</b>
						</td>
						<td style=" text-align: right;" >
							<b>{{ $totaluPrice }}</b>
						</td>

						<td style=" text-align: right;" >
							<b>{{ $totalq }}</b>
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
			applyLabel: "{{ isset($data['backendlang']['backendlang']['Apply']) ? $data['backendlang']['backendlang']['Apply'] :'' }}",
			cancelLabel: "{{ isset($data['backendlang']['backendlang']['Cancel']) ? $data['backendlang']['backendlang']['Cancel'] :'' }}",
			format: 'DD/MM/YYYY',
		}
	});

	$('.grandTotal').html('{{ number_format($totalgrand, 2) }}');

	$("#datepicker").datepicker( {
	    format: "mm-yyyy",
	    viewMode: "months", 
	    minViewMode: "months"
	});
</script>
@endsection