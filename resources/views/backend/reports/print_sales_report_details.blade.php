@extends('layouts.admin_app')
<style type="text/css">
	@media print {
		@page {
			size: landscape;
			margin: 4mm 0mm;
		}
	}
</style>
@section('content')
<div class="form-group">
	<table class="table">
		<tr>
			<td>
				<div class="form-group">
					<h3>
						<b>{{ $data['web_setting']->invoice_name ?? $data['website_setting']->website_name }}</b>
					</h3>
				</div>
				<div class="form-group">
					<p>{{ isset($data['backendlang']['backendlang']['Print_Dates']) ? $data['backendlang']['backendlang']['Print_Dates'] :'' }}: {{ date('d/m/Y H:i:s') }}</p>
				</div>
			</td>
			<td align="right">
				<div class="form-group">
					<h3><b>{{ isset($data['backendlang']['backendlang']['Profit_Details_Report']) ? $data['backendlang']['backendlang']['Profit_Details_Report'] :'' }}</b></h3>
				</div>
				<div class="form-group">
					<p>{{ isset($data['backendlang']['backendlang']['Report_Dates']) ? $data['backendlang']['backendlang']['Report_Dates'] :'' }}: {{ !empty(request('dates')) ? request('dates') : $startDate.' - '.$endDate }}</p>
				</div>
			</td>
		</tr>
	</table>
</div>
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
			<th>{{ isset($data['backendlang']['backendlang']['Total_Sales']) ? $data['backendlang']['backendlang']['Total_Sales'] :'' }} (RM)</th>
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
			'Agent' => ($data['backendlang']['backendlang']['Agent'] ?? ($data['lang']['lang']['Agent'] ?? 'Agent')),
			'Member' => ($data['backendlang']['backendlang']['Member'] ?? ($data['lang']['lang']['Member'] ?? 'Member')),
			'Guest' => ($data['backendlang']['backendlang']['Guest'] ?? ($data['lang']['lang']['Guest'] ?? 'Guest')),
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
@endsection

@section('js')
<script type="text/javascript">
	$('.print-window').click(function() {
		window.print();
	});
	$(document).ready(function() {
		$('.print-window').click();
	});
</script>
@endsection