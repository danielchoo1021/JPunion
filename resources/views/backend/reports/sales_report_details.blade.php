@extends('layouts.admin_app')

@section('content')
<div class="container-box form-group">
	<h3>{{ isset($data['backendlang']['backendlang']['Filter']) ? $data['backendlang']['backendlang']['Filter'] :'' }}</h3>
	<hr>
	<form action="{{ route('sales_report_details', [$product_id, $pricing_type]) }}" method="GET">
		<div class="form-group">
			<div class="row">
				<div class="col-sm-3">
					<div class="form-group">
						<input type="text" class="form-control" name="dates" value="{{ !empty(request('dates')) ? request('dates') : $startDate.' - '.$endDate }}">
					</div>
				</div>
<!-- 
				<div class="col-sm-2">
					<div class="form-group">
						<input type="text" class="form-control" name="item_code" value="{{ !empty(request('item_code')) ? request('item_code') : '' }}" placeholder="Search Item Code..">
					</div>
				</div>

				<div class="col-sm-2">
					<div class="form-group">
						<input type="text" class="form-control" name="product_code" value="{{ !empty(request('product_code')) ? request('product_code') : '' }}" placeholder="Search Product SKU..">
					</div>
				</div>

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
						<a href="{{ route('sales_report_details', [$product_id, $pricing_type]) }}" class="btn btn-warning btn-sm">
							<i class="bi bi-arrow-clockwise"></i> {{ isset($data['backendlang']['backendlang']['Clear_Search']) ? $data['backendlang']['backendlang']['Clear_Search'] :'' }}
						</a>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
<div class="container-box">
	<div class="form-group" align="right">
		<a href="{{ route('print_sales_report_details', ['dates='.(!empty(request('dates')) ? request('dates') : $startDate.' - '.$endDate), 
														 'item_code='.(!empty(request('item_code')) ? request('item_code') : ''), 
														 'product_code='.(!empty(request('product_code')) ? request('product_code') : ''),
														 'yearly='.(!empty(request('yearly')) ? request('yearly') : '' ),
														 'monthly='.(!empty(request('monthly')) ? request('monthly') : '' ),
														 'daily='.(!empty(request('daily')) ? request('daily') : '' ),
														 'per_page='.(!empty(request('per_page')) ? request('per_page') : '' ),
														 'pid='.($product_id),
														 'price_type='.($pricing_type) ]) }}" class="print-window btn btn-outline-primary" target="_blank">
			<i class="bi bi-printer"></i> {{ isset($data['backendlang']['backendlang']['Print']) ? $data['backendlang']['backendlang']['Print'] :'' }}
		</a>
		<a href="{{ route('exportSalesDetails', ['dates='.(!empty(request('dates')) ? request('dates') : $startDate.' - '.$endDate), 
												 'item_code='.(!empty(request('item_code')) ? request('item_code') : ''), 
												 'product_code='.(!empty(request('product_code')) ? request('product_code') : ''),
												 'yearly='.(!empty(request('yearly')) ? request('yearly') : '' ),
												 'monthly='.(!empty(request('monthly')) ? request('monthly') : '' ),
												 'daily='.(!empty(request('daily')) ? request('daily') : '' ),
												 'per_page='.(!empty(request('per_page')) ? request('per_page') : '' ),
												 'pid='.($product_id),
												 'price_type='.($pricing_type) ])}}" class="btn btn-warning" target="_blank">
			<i class="bi bi-download"></i> {{ isset($data['backendlang']['backendlang']['Export']) ? $data['backendlang']['backendlang']['Export'] :'' }}
		</a>
	</div>

	<div class="row" style="overflow: auto;">
		<div class="col-12">
			{{ $transactions->links() }}
			<table class="table table-bordered">
				<thead>
					<tr>
						<th>#</th>
						<th>{{ isset($data['backendlang']['backendlang']['Transaction_No']) ? $data['backendlang']['backendlang']['Transaction_No'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Product_Name']) ? $data['backendlang']['backendlang']['Product_Name'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Price_Type']) ? $data['backendlang']['backendlang']['Price_Type'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Item_Code']) ? $data['backendlang']['backendlang']['Item_Code'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['ProductSKU']) ? $data['backendlang']['backendlang']['ProductSKU'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Net_Quantity']) ? $data['backendlang']['backendlang']['Net_Quantity'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Costing_Price']) ? $data['backendlang']['backendlang']['Costing_Price'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Total_Costing_Price']) ? $data['backendlang']['backendlang']['Total_Costing_Price'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Unit_Price']) ? $data['backendlang']['backendlang']['Unit_Price'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Total_Sales_(RM)']) ? $data['backendlang']['backendlang']['Total_Sales_(RM)'] :'' }}</th>
					</tr>
				</thead>
				<tbody>
					@php
						$totalQty = 0;
						$totalCost = 0;
						$totalUnitPrice = 0;
						$totalCostQty = 0;
						$totalNetQty = 0;
					@endphp

					@if(!$transactions->isEmpty())
						@foreach($transactions as $key => $transaction)
							@php
								$pricingTypeLabels = [
									'Agent' => ($data['backendlang']['backendlang']['Agent'] ?? ($data['lang']['lang']['agent'] ?? 'Agent')),
									'Member' => ($data['backendlang']['backendlang']['Member'] ?? ($data['lang']['lang']['member'] ?? 'Member')),
									'Guest' => ($data['backendlang']['backendlang']['Guest'] ?? ($data['lang']['lang']['guest'] ?? 'Guest')),
								];
								// normal
								$qty = $transaction->quantity;
								$costingPrice = $transaction->costing_price;
								$unitPrice = $transaction->unit_price;
								$discount = $transaction->discount ?? 0;

								$lineCostTotal = $costingPrice * $qty;
								$lineNetTotal = ($unitPrice * $qty) - $discount;

								// total
								$totalQty += $qty;
								$totalCost += $costingPrice;
								$totalCostQty += $lineCostTotal;
								$totalUnitPrice += $unitPrice;
								$totalNetQty += $lineNetTotal;
							@endphp
							<tr>
								<td>
									{{ $key + 1 }}
									<input type="hidden" name="tid" value="{{ $transaction->Tid }}">
								</td>
								<td>{{ $transaction->transaction_no }}</td>
								<td>{{ $transaction->product_name }}</td>
								<td>{{ $pricingTypeLabels[$transaction->get_pricing_type] ?? $transaction->get_pricing_type }}</td>
								<td>{{ $transaction->item_code }}</td>
								<td>{{ $transaction->product_code }}</td>
								<td>{{ $transaction->quantity }}</td>
								<td>{{ number_format($costingPrice, 2) }}</td>
								<td>{{ number_format($lineCostTotal, 2) }}</td>
								<td>{{ number_format($unitPrice, 2) }}</td>
								<td>{{ number_format($lineNetTotal, 2) }}</td>
							</tr>
						@endforeach
					@else
						<tr>
							<td colspan="11">{{ isset($data['backendlang']['backendlang']['No_Result_Found']) ? $data['backendlang']['backendlang']['No_Result_Found'] :'' }}</td>
						</tr>
					@endif
					<tr>
						<td colspan="6"><b>{{ isset($data['backendlang']['backendlang']['Summary']) ? $data['backendlang']['backendlang']['Summary'] :'' }}</b></td>
						<td><b>{{ $totalQty }}</b></td>
						<td><b>{{ number_format($totalCost, 2) }}</b></td>
						<td><b>{{ number_format($totalCostQty, 2) }}</b></td>
						<td><b>{{ number_format($totalUnitPrice, 2) }}</b></td>
						<td><b>{{ number_format($totalNetQty, 2) }}</b></td>
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