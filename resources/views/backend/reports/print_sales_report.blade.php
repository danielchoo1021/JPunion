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
						<h3><b>{{ !empty($data['web_setting']->invoice_name) ? $data['web_setting']->invoice_name : $data['admin']->company_name }}
						</b></h3>
					</div>
					<div class="form-group">
						<p>{{ isset($data['backendlang']['backendlang']['Print_Dates']) ? $data['backendlang']['backendlang']['Print_Dates'] :'' }}: {{ date('d/m/Y H:i:s') }}</p>
					</div>
				</td>
				<td align="right">
					<div class="form-group">
						<h3><b>{{ isset($data['backendlang']['backendlang']['Profit_Report']) ? $data['backendlang']['backendlang']['Profit_Report'] :'' }}</b></h3>
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
			<tr class="info">
				<th>#</th>
				<th>{{ isset($data['backendlang']['backendlang']['Product_Description']) ? $data['backendlang']['backendlang']['Product_Description'] :'' }}</th>
				<th>{{ isset($data['backendlang']['backendlang']['Item_Code']) ? $data['backendlang']['backendlang']['Item_Code'] :'' }}</th>
				<th>{{ isset($data['backendlang']['backendlang']['ProductSKU']) ? $data['backendlang']['backendlang']['ProductSKU'] :'' }}</th>
				<th>{{ isset($data['backendlang']['backendlang']['Unit_Price']) ? $data['backendlang']['backendlang']['Unit_Price'] :'' }}</th>
				<th>{{ isset($data['backendlang']['backendlang']['Net_Quantity']) ? $data['backendlang']['backendlang']['Net_Quantity'] :'' }}</th>
				<th>{{ isset($data['backendlang']['backendlang']['Total_Sales']) ? $data['backendlang']['backendlang']['Total_Sales'] :'' }} (RM)</th>
				<!-- <th>Action</th> -->
			</tr>
		</thead>
		<tbody>
			@php
					$totalQty = 0;
					$totalsfee = 0;
					$totalDiscount = 0;
					$totalgrand = 0;
					$totalUnit = 0;
					$totalNet = 0;
				@endphp
				@if(!$transactions->isEmpty())
				@foreach($transactions as $key => $transaction)
				<tr>
					<td>
						{{ $key+1 }}
						<input type="hidden" name="tid" value="{{ $transaction->Tid }}">
					</td>
					<td>{{ $transaction->product_name }}</td>
					<td>{{ $transaction->item_code }}</td>
					<td>{{ $transaction->product_code }}</td>
					<td>{{ number_format($transaction->unit_price, 2) }}</td>
					<td>{{ $transaction->totalQty }}</td>
					<td>{{ number_format($transaction->totalNet,2) }}</td>
					
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
					$totalDiscount += $transaction->tax;
					$totalgrand += $transaction->totalGrand;
					$totalUnit += $transaction->unit_price;
					$totalNet += $transaction->totalNet;
				@endphp
				@endforeach
				@else
				<tr>
					<td colspan="11">{{ isset($data['backendlang']['backendlang']['No_Result_Found']) ? $data['backendlang']['backendlang']['No_Result_Found'] :'' }}</td>
				</tr>
				@endif

				<tr class="warning">
					<td style="border: none;"  colspan="4">
						<b>{{ isset($data['backendlang']['backendlang']['Summary']) ? $data['backendlang']['backendlang']['Summary'] :'' }}</b>
					</td>
					<td style="border: none;" class="">
						<b>{{ number_format($totalUnit, 2) }}</b>
					</td>
					<td style="border: none;" >
						<b>{{ $totalQty }}
					</td>
					<td style="border: none;" class="">
						<b>{{ number_format($totalNet, 2) }}</b>
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