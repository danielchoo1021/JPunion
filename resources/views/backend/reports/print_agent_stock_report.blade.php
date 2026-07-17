@extends('layouts.admin_app')
<style type="text/css">
	@media print{
		@page {
			size: landscape;
			margin: 4mm 0mm;
		}
	}

	
</style>
@section('content')
	<a href="#" class="print-window" style="display: none;">
		<i class="fa fa-print"></i> {{ isset($data['backendlang']['backendlang']['Print']) ? $data['backendlang']['backendlang']['Print'] :'' }}
	</a>
	<div class="form-group">
		<table class="table">
			<tr>
				<td>
					<div class="form-group">
						<h3><b>{{ !empty($data['web_setting']->invoice_name) ? $data['web_setting']->invoice_name : $data['admin']->company_name }}</b></h3>
					</div>
					<div class="form-group">
						<p>Print Dates: {{ date('d/m/Y H:i:s') }}</p>
					</div>
				</td>
				<td align="right">
					<div class="form-group">
						<h3><b>{{ isset($data['backendlang']['backendlang']['Agent_Stock_Report']) ? $data['backendlang']['backendlang']['Agent_Stock_Report'] :'' }}</b></h3>
					</div>
					<div class="form-group">
						<p>Report Dates: {{ !empty(request('dates')) ? request('dates') : $startDate.' - '.$endDate }}</p>
					</div>
				</td>
			</tr>
		</table>
	</div>
	<table class="table table-bordered">
		<thead>
			<tr class="info">
				<th>#</th>
				<th>{{ isset($data['backendlang']['backendlang']['Item_Code']) ? $data['backendlang']['backendlang']['Item_Code'] :'' }}</th>
				<th>{{ isset($data['backendlang']['backendlang']['Product_Code']) ? $data['backendlang']['backendlang']['Product_Code'] :'' }}</th>
				<th>{{ isset($data['backendlang']['backendlang']['Buyer']) ? $data['backendlang']['backendlang']['Buyer'] :'' }}</th>
				<th style="text-align: right;">{{ isset($data['backendlang']['backendlang']['Net_Quantity']) ? $data['backendlang']['backendlang']['Net_Quantity'] :'' }}</th>
				<th style="text-align: right;">{{ isset($data['backendlang']['backendlang']['Discounts']) ? $data['backendlang']['backendlang']['Discounts'] :'' }}</th>
				<th style="text-align: right;">{{ isset($data['backendlang']['backendlang']['Shipping_Fee']) ? $data['backendlang']['backendlang']['Shipping_Fee'] :'' }}</th>
				<th style="text-align: right;">{{ isset($data['backendlang']['backendlang']['Net_Sales']) ? $data['backendlang']['backendlang']['Net_Sales'] :'' }}</th>
				<th style="text-align: right;">{{ isset($data['backendlang']['backendlang']['Tax']) ? $data['backendlang']['backendlang']['Tax'] :'' }}</th>
				<th style="text-align: right;">{{ isset($data['backendlang']['backendlang']['Total_Sales']) ? $data['backendlang']['backendlang']['Total_Sales'] :'' }}</th>
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
			@if(!$transactions->isEmpty())
			@foreach($transactions as $key => $transaction)
			<tr>
				<td>
					{{ $key+1 }}
					<input type="hidden" name="tid" value="{{ $transaction->Tid }}">
				</td>
				<td>{{ $transaction->item_code }}</td>
				<td>{{ $transaction->product_code }}</td>
				<td>{{ $transaction->buyer_name }}</td>
				<td align="right">{{ $transaction->totalQty }}</td>
				<td align="right">{{ number_format($transaction->totalDiscount, 2) }}</td>
				<td align="right">{{ number_format($transaction->totalShippingFee, 2) }}</td>
				<td align="right">{{ number_format($transaction->totalGrand, 2) }}</td>
				<td align="right">{{ number_format($transaction->tax, 2) }}</td>
				<td align="right">{{ number_format(($transaction->totalGrand + $transaction->tax), 2) }}</td>
				<!-- <td>
					<a href="">
						<i class="ace-icon fa fa-pencil bigger-130"></i>
					</a>
					&nbsp;&nbsp;
					<a href="#" class="red">
						<i class="ace-icon fa fa-trash-o bigger-130"></i>
					</a>
				</td> -->
			</tr>
			@php
				$totalQty += $transaction->totalQty;
				$totalsfee += $transaction->totalShippingFee;
				$totalDiscount += $transaction->totalDiscount;
				$totalTax += $transaction->tax;
				$totalgrand += $transaction->totalGrand;
			@endphp
			@endforeach
			@else
			<tr>
				<td colspan="11">{{ isset($data['backendlang']['backendlang']['No_Result_Found']) ? $data['backendlang']['backendlang']['No_Result_Found'] :'' }}</td>
			</tr>
			@endif
			<tr class="warning">
				<td colspan="4">
					<b>{{ isset($data['backendlang']['backendlang']['Summary']) ? $data['backendlang']['backendlang']['Summary'] :'' }}</b>
				</td>
				<td align="right">
					<b>{{ $totalQty }}</b>
				</td>
				<td align="right">
					<b>{{ number_format($totalDiscount, 2) }}</b>
				</td>
				<td align="right">
					<b>{{ number_format($totalsfee, 2) }}</b>
				</td>
				<td align="right">
					<b>{{ number_format($totalgrand, 2) }}</b>
				</td>
				<td align="right">
					<b>{{ number_format($totalTax, 2) }}</b>
				</td>
				<td align="right">
					<b>{{ number_format($totalgrand, 2) }}</b>
				</td>
			</tr>
		</tbody>
	</table>
@endsection

@section('js')
<script type="text/javascript">
	$('.print-window').click(function() {
	    window.print();
	});
	$(document).ready(function(){
		$('.print-window').click();
	});
</script>
@endsection