@extends('layouts.admin_app')

@section('content')
<div class="container-box form-group">
	<h3>{{ isset($data['backendlang']['backendlang']['Filter']) ? $data['backendlang']['backendlang']['Filter'] :'' }}</h3>
	<hr>
	<form action="{{ route('sales_report') }}" method="GET">
		<div class="form-group">
			<div class="row">
				<div class="col-sm-3">
					<div class="form-group">
						<input type="text" class="form-control" name="dates" value="{{ !empty(request('dates')) ? request('dates') : $startDate.' - '.$endDate }}">
					</div>
				</div>

				<div class="col-sm-3">
					<div class="form-group">
						<input type="text" class="form-control" name="item_code" value="{{ !empty(request('item_code')) ? request('item_code') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_item_code']) ? $data['backendlang']['backendlang']['Remaining_Point_Wallet_Balance'] :'' }}">
					</div>
				</div>

				<div class="col-sm-3">
					<div class="form-group">
						<input type="text" class="form-control" name="product_code" value="{{ !empty(request('product_code')) ? request('product_code') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Product_SKU']) ? $data['backendlang']['backendlang']['Search_Product_SKU'] :'' }}">
					</div>
				</div>
				<!-- 
			<div class="col-sm-2">
				<div class="form-group">
					<select class="form-control" name="yearly">
						<option value="">Select Year</option>
						<option {{ (!empty(request('yearly')) && request('yearly') == date('Y', strtotime('-5 years'))) ? 'selected' : '' }} value="{{ date('Y', strtotime('-5 years')) }}">{{ date('Y', strtotime('-5 years')) }}</option>
						<option {{ (!empty(request('yearly')) && request('yearly') == date('Y', strtotime('-4 years'))) ? 'selected' : '' }} value="{{ date('Y', strtotime('-4 years')) }}">{{ date('Y', strtotime('-4 years')) }}</option>
						<option {{ (!empty(request('yearly')) && request('yearly') == date('Y', strtotime('-3 years'))) ? 'selected' : '' }} value="{{ date('Y', strtotime('-3 years')) }}">{{ date('Y', strtotime('-3 years')) }}</option>
						<option {{ (!empty(request('yearly')) && request('yearly') == date('Y', strtotime('-2 years'))) ? 'selected' : '' }} value="{{ date('Y', strtotime('-2 years')) }}">{{ date('Y', strtotime('-2 years')) }}</option>
						<option {{ (!empty(request('yearly')) && request('yearly') == date('Y', strtotime('-1 years'))) ? 'selected' : '' }} value="{{ date('Y', strtotime('-1 years')) }}">{{ date('Y', strtotime('-1 years')) }}</option>
					</select>
				</div>
			</div>

			<div class="col-sm-2">
				<div class="form-group">
					<select class="form-control" name="monthly">
						<option value="">Select Month</option>
						@for($m=1; $m<=12; $m++)
						<option {{ (!empty(request('monthly')) && request('monthly') == $m) ? 'selected' : '' }} value="{{ $m }}">{{ $m }}</option>
						@endfor
					</select>
				</div>
			</div>

			<div class="col-sm-2">
				<div class="form-group">
					<select class="form-control" name="daily">
						<option value="">Select Day</option>
						@for($d=1; $d<=31; $d++)
						<option {{ (!empty(request('daily')) && request('daily') == $d) ? 'selected' : '' }} value="{{ $d }}">{{ $d }}</option>
						@endfor
					</select>
				</div>
			</div> -->
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
						<a href="{{ route('sales_report') }}" class="btn btn-warning btn-sm">
							<i class="bi bi-arrow-clockwise"></i> {{ isset($data['backendlang']['backendlang']['Clear_Search']) ? $data['backendlang']['backendlang']['Clear_Search'] :'' }}
						</a>
					</div>
				</div>
			</div>
		</div>

		@if(!empty(request('today')))
		<input type="text" class="form-control" name="today" value="{{ !empty(request('today')) ? request('today') : '' }}" style="display:none">
		@elseif(!empty(request('this_month')))
		<input type="text" class="form-control" name="this_month" value="{{ !empty(request('this_month')) ? request('this_month') : '' }}" style="display:none">
		@elseif(!empty(request('this_year')))
		<input type="text" class="form-control" name="this_year" value="{{ !empty(request('this_year')) ? request('this_year') : '' }}" style="display:none">
		@endif

		@if(!empty(request('this_daily_cost')))
		<input type="text" class="form-control" name="this_daily_cost" value="{{ !empty(request('this_daily_cost')) ? request('this_daily_cost') : '' }}" style="display:none">
		@elseif(!empty(request('this_monthly_cost')))
		<input type="text" class="form-control" name="this_monthly_cost" value="{{ !empty(request('this_monthly_cost')) ? request('this_monthly_cost') : '' }}" style="display:none">
		@elseif(!empty(request('this_yearly_cost')))
		<input type="text" class="form-control" name="this_yearly_cost" value="{{ !empty(request('this_yearly_cost')) ? request('this_yearly_cost') : '' }}" style="display:none">
		@endif

		@if(!empty(request('this_daily_margin')))
		<input type="text" class="form-control" name="this_daily_margin" value="{{ !empty(request('this_daily_margin')) ? request('this_daily_margin') : '' }}" style="display:none">
		@elseif(!empty(request('this_monthly_margin')))
		<input type="text" class="form-control" name="this_monthly_margin" value="{{ !empty(request('this_monthly_margin')) ? request('this_monthly_margin') : '' }}" style="display:none">
		@elseif(!empty(request('this_yearly_margin')))
		<input type="text" class="form-control" name="this_yearly_margin" value="{{ !empty(request('this_yearly_margin')) ? request('this_yearly_margin') : '' }}" style="display:none">
		@endif

	</form>
	@php
	$dailyDate = date('Y-m-d');
	$MonthlyDate = date('Y-m');
	$YearlyDate = date('Y');
	@endphp
	<div class="form-group">
		<span class="badge bg-info" style="font-size: 1rem; padding: 10px;">
			@if(!empty(request('today')) || !empty(request('this_daily_cost')) || !empty(request('this_daily_margin')))
			{{ isset($data['backendlang']['backendlang']['Dates']) ? $data['backendlang']['backendlang']['Dates'] :'' }}: {{ $dailyDate}}
			@elseif(!empty(request('this_month')) || !empty(request('this_monthly_cost')) || !empty(request('this_monthly_margin')))
			{{ isset($data['backendlang']['backendlang']['Dates']) ? $data['backendlang']['backendlang']['Dates'] :'' }}: {{ $MonthlyDate }}
			@elseif(!empty(request('this_year')) || !empty(request('this_yearly_cost')) || !empty(request('this_yearly_margin')))
			{{ isset($data['backendlang']['backendlang']['Dates']) ? $data['backendlang']['backendlang']['Dates'] :'' }}: {{ $YearlyDate }}
			@else
			{{ isset($data['backendlang']['backendlang']['Dates']) ? $data['backendlang']['backendlang']['Dates'] :'' }}: {{ !empty(request('dates')) ? request('dates') : $startDate.' - '.$endDate }}
			@endif
		</span>
		|
		<span class="badge bg-success" style="font-size: 1rem; padding: 10px;">
			{{ isset($data['backendlang']['backendlang']['Grand_Total']) ? $data['backendlang']['backendlang']['Grand_Total'] :'' }}: <span class="grandTotal"></span>
		</span>
		<br>
		<br>
		<a href="{{ route('sales_report', ['agent='.(!empty(request('agent')) ? request('agent') : ''),
												'today='.$dailyDate]) }}"
			class="btn btn-outline-primary btn-sm btn-filter {{ !empty(request('today')) ? 'filter_selected' : '' }}">
			{{ isset($data['backendlang']['backendlang']['Daily_Sales']) ? $data['backendlang']['backendlang']['Daily_Sales'] :'' }}
			<input type="hidden" name="filter_data" value="0">
			<br>
			RM {{ number_format($dailySales->dailySales, 2) }}
		</a>
		<a href="{{ route('sales_report', ['agent='.(!empty(request('agent')) ? request('agent') : ''),
												'this_month='.$MonthlyDate]) }}"
			class="btn btn-outline-primary btn-sm btn-filter {{ !empty(request('this_month')) ? 'filter_selected' : '' }}">
			{{ isset($data['backendlang']['backendlang']['Monthly_Sales']) ? $data['backendlang']['backendlang']['Monthly_Sales'] :'' }}
			<input type="hidden" name="filter_data" value="1">
			<br>
			RM {{ number_format($monthlySales->monthlySales, 2) }}
		</a>
		<a href="{{ route('sales_report', ['agent='.(!empty(request('agent')) ? request('agent') : ''),
												'this_year='.$YearlyDate]) }}"
			class="btn btn-outline-primary btn-sm btn-filter {{ !empty(request('this_year')) ? 'filter_selected' : '' }}">
			{{ isset($data['backendlang']['backendlang']['Yearly_Sales']) ? $data['backendlang']['backendlang']['Yearly_Sales'] :'' }}
			<input type="hidden" name="filter_data" value="2">
			<br>
			RM {{ number_format($yearlySales->yearlySales, 2) }}
		</a>
		|
		<a href="{{ route('sales_report', ['agent='.(!empty(request('agent')) ? request('agent') : ''),
												'this_daily_cost='.$dailyDate]) }}"
			class="btn btn-outline-primary btn-sm btn-filter {{ !empty(request('this_daily_cost')) ? 'filter_selected' : '' }}">
			{{ isset($data['backendlang']['backendlang']['Daily_Cost']) ? $data['backendlang']['backendlang']['Daily_Cost'] :'' }}
			<input type="hidden" name="filter_data" value="2">
			<br>
			RM {{ number_format($dailyCost->dailyCost, 2) }}
		</a>
		<a href="{{ route('sales_report', ['agent='.(!empty(request('agent')) ? request('agent') : ''),
												'this_monthly_cost='.$MonthlyDate]) }}"
			class="btn btn-outline-primary btn-sm btn-filter {{ !empty(request('this_monthly_cost')) ? 'filter_selected' : '' }}">
			{{ isset($data['backendlang']['backendlang']['Monthly_Cost']) ? $data['backendlang']['backendlang']['Monthly_Cost'] :'' }}
			<input type="hidden" name="filter_data" value="2">
			<br>
			RM {{ number_format($monthlyCost->monthlyCost, 2) }}
		</a>
		<a href="{{ route('sales_report', ['agent='.(!empty(request('agent')) ? request('agent') : ''),
												'this_yearly_cost='.$YearlyDate]) }}"
			class="btn btn-outline-primary btn-sm btn-filter {{ !empty(request('this_yearly_cost')) ? 'filter_selected' : '' }}">
			{{ isset($data['backendlang']['backendlang']['Yearly_Cost']) ? $data['backendlang']['backendlang']['Yearly_Cost'] :'' }}
			<input type="hidden" name="filter_data" value="2">
			<br>
			RM {{ number_format($yearlyCost->yearlyCost, 2) }}
		</a>
		|
		<a href="{{ route('sales_report', ['agent='.(!empty(request('agent')) ? request('agent') : ''),
												'this_daily_margin='.$dailyDate]) }}"
			class="btn btn-outline-primary btn-sm btn-filter {{ !empty(request('this_daily_margin')) ? 'filter_selected' : '' }}">
			{{ isset($data['backendlang']['backendlang']['Daily_Margin']) ? $data['backendlang']['backendlang']['Daily_Margin'] :'' }}
			<input type="hidden" name="filter_data" value="2">
			<br>
			RM {{ number_format($dailySales->dailySales - $dailyCost->dailyCost, 2) }}
		</a>
		<a href="{{ route('sales_report', ['agent='.(!empty(request('agent')) ? request('agent') : ''),
												'this_monthly_margin='.$MonthlyDate]) }}"
			class="btn btn-outline-primary btn-sm btn-filter {{ !empty(request('this_monthly_margin')) ? 'filter_selected' : '' }}">
			{{ isset($data['backendlang']['backendlang']['Monthly_Margin']) ? $data['backendlang']['backendlang']['Monthly_Margin'] :'' }}
			<input type="hidden" name="filter_data" value="2">
			<br>
			RM {{ number_format($monthlySales->monthlySales - $monthlyCost->monthlyCost, 2) }}
		</a>
		<a href="{{ route('sales_report', ['agent='.(!empty(request('agent')) ? request('agent') : ''),
												'this_yearly_margin='.$YearlyDate]) }}"
			class="btn btn-outline-primary btn-sm btn-filter {{ !empty(request('this_yearly_margin')) ? 'filter_selected' : '' }}">
			{{ isset($data['backendlang']['backendlang']['Yearly_Margin']) ? $data['backendlang']['backendlang']['Yearly_Margin'] :'' }}
			<input type="hidden" name="filter_data" value="2">
			<br>
			RM {{ number_format($yearlySales->yearlySales - $yearlyCost->yearlyCost, 2) }}
		</a>
	</div>
</div>
<div class="container-box">
	<div class="form-group" align="right">
		@php

		$daily = request('today')
		?? request('this_daily_cost')
		?? request('this_daily_margin')
		?? '';

		$monthly = request('this_month')
		?? request('this_monthly_cost')
		?? request('this_monthly_margin')
		?? '';

		$yearly = request('this_year')
		?? request('this_yearly_cost')
		?? request('this_yearly_margin')
		?? '';
		@endphp

		<a href="{{ route('print_sales_report', ['dates='.(!empty(request('dates')) ? request('dates') : $startDate.' - '.$endDate), 
											     'item_code='.(!empty(request('item_code')) ? request('item_code') : ''), 
											     'product_code='.(!empty(request('product_code')) ? request('product_code') : ''),
												 'yearly='.$yearly,
												 'monthly='.$monthly,
												 'daily='.$daily,
												 'per_page='.(!empty(request('per_page')) ? request('per_page') : '' ) ]) }}" class="print-window btn btn-outline-primary" target="_blank">
			<i class="bi bi-printer"></i> {{ isset($data['backendlang']['backendlang']['Print']) ? $data['backendlang']['backendlang']['Print'] :'' }}
		</a>
		<a href="{{ route('exportSales', ['dates='.(!empty(request('dates')) ? request('dates') : $startDate.' - '.$endDate), 
										  'item_code='.(!empty(request('item_code')) ? request('item_code') : ''), 
										  'product_code='.(!empty(request('product_code')) ? request('product_code') : ''),
										  'yearly='.$yearly,
										  'monthly='.$monthly,
										  'daily='.$daily,
										  'per_page='.(!empty(request('per_page')) ? request('per_page') : '' ) ])}}" class="btn btn-warning" target="_blank">
			<i class="bi bi-download"></i> {{ isset($data['backendlang']['backendlang']['Export']) ? $data['backendlang']['backendlang']['Export'] :'' }}
		</a>
	</div>

	<div class="row" style="overflow: auto;">
		<div class="col-12">
			{{ $transactions->links() }}
			<table class="table table-bordered">
				<thead>
					<tr class="info">
						<th>#</th>
						<th>{{ isset($data['backendlang']['backendlang']['Product_Name']) ? $data['backendlang']['backendlang']['Product_Name'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Price_Type']) ? $data['backendlang']['backendlang']['Price_Type'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Item_Code']) ? $data['backendlang']['backendlang']['Item_Code'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['ProductSKU']) ? $data['backendlang']['backendlang']['ProductSKU'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Net_Quantity']) ? $data['backendlang']['backendlang']['Net_Quantity'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Costing_Price']) ? $data['backendlang']['backendlang']['Costing_Price'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Total_Sales']) ? $data['backendlang']['backendlang']['Total_Sales'] :'' }} (RM)</th>
						<!-- <th>Action</th> -->
					</tr>
				</thead>
				<tbody>
					@php
						$pricingTypeLabels = [
							'Agent' => ($data['backendlang']['backendlang']['Agent'] ?? ($data['lang']['lang']['agent'] ?? 'Agent')),
							'Member' => ($data['backendlang']['backendlang']['Member'] ?? ($data['lang']['lang']['member'] ?? 'Member')),
							'Guest' => ($data['backendlang']['backendlang']['Guest'] ?? ($data['lang']['lang']['guest'] ?? 'Guest')),
						];
					@endphp
					@php
					$totalQty = 0;
					$totalsfee = 0;
					$totalDiscount = 0;
					$totalgrand = 0;
					$totalUnit = 0;
					$totalCost = 0;
					$totalNet = 0;
					@endphp
					@if(!$transactions->isEmpty())
					@foreach($transactions as $key => $transaction)
					<tr>
						<td>
							{{ $key+1 }}
							<input type="hidden" name="tid" value="{{ $transaction->Tid }}">
						</td>
						<td>
							<a href="{{ route('sales_report_details', [md5($transaction->product_id), 
																			   $transaction->get_pricing_type, 
																			   'dates='.(!empty(request('dates')) ? request('dates') : $startDate.' - '.$endDate)]) }}">
								{{ $transaction->product_name }}
							</a>
						</td>
							<td>{{ $pricingTypeLabels[$transaction->get_pricing_type] ?? $transaction->get_pricing_type }}</td>
						<td>{{ $transaction->item_code }}</td>
						<td>{{ $transaction->product_code }}</td>
						<td>{{ $transaction->totalQty }}</td>
						<td>{{ number_format($transaction->costing_price, 2) }}</td>
						<td>{{ number_format($transaction->totalNet - $transaction->totalDiscount, 2) }}</td>

						<!-- <td>
									<a href="">
										<i class="ace-icon bi bi-pencil bigger-130"></i>
									</a>
									&nbsp;&nbsp;
									<a href="#" class="red">
									<i class="ace-icon bi bi-trash-o bigger-130"></i>
									</a>
								</td> -->
					</tr>
					@php
					$totalQty += $transaction->totalQty;
					$totalsfee += $transaction->totalShippingFee;
					$totalDiscount += $transaction->totalDiscount;

					$totalgrand += $transaction->totalGrand;
					$totalCost += $transaction->costing_price;
					$totalNet += $transaction->totalNet;
					@endphp
					@endforeach
					@else
					<tr>
						<td colspan="11">{{ isset($data['backendlang']['backendlang']['No_Result_Found']) ? $data['backendlang']['backendlang']['No_Result_Found'] :'' }}</td>
					</tr>
					@endif

					<tr class="warning">
						<td style="border: none;" colspan="5">
							<b>{{ isset($data['backendlang']['backendlang']['Summary']) ? $data['backendlang']['backendlang']['Summary'] :'' }}</b>
						</td>
						<td style="border: none;">
							<b>{{ $totalQty }}
						</td>
						<td style="border: none;" class="">
							<b>{{ number_format($totalCost, 2) }}</b>
						</td>
						<td style="border: none;" class="">
							<b>{{ number_format($totalNet - $totalDiscount, 2) }}</b>
						</td>
					</tr>
				</tbody>
			</table>
			{{ $transactions->links() }}
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

	$('.grandTotal').html('{{ number_format($totalNet, 2) }}');
</script>
@endsection