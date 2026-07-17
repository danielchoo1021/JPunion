<table>
	<tr>
		<th><b>{{ !empty($data['web_setting']->invoice_name) ? $data['web_setting']->invoice_name : $data['admin']->company_name }}</b></th>
		<th></th>
		<th></th>
		<th></th>
		<th></th>
		<th></th>
		<th></th>
		<th></th>
		<th align="right">
			<b>{{ isset($data['backendlang']['backendlang']['Agent_Stock_Report']) ? $data['backendlang']['backendlang']['Agent_Stock_Report'] :'' }}</b>
		</th>
	</tr>
	<tr>
		<th>
			{{ isset($data['backendlang']['backendlang']['print_date']) ? $data['backendlang']['backendlang']['print_date'] :'' }}: {{ date('Y-m-d H:i:s') }}
		</th>
		<th></th>
		<th></th>
		<th></th>
		<th></th>
		<th></th>
		<th></th>
		<th></th>
		<th align="right">
			{{ isset($data['backendlang']['backendlang']['report_date']) ? $data['backendlang']['backendlang']['report_datet'] :'' }}: {{ $start }} - {{ $end }}
		</th> 
	</tr>
</table>
<table class="table table-bordered">
	<thead>
		<tr class="info">
			<th><b>{{ isset($data['backendlang']['backendlang']['Item_Code']) ? $data['backendlang']['backendlang']['Item_Code'] :'' }}</b></th>
			<th><b>{{ isset($data['backendlang']['backendlang']['Product_Code']) ? $data['backendlang']['backendlang']['Product_Code'] :'' }}</b></th>
			<th><b>{{ isset($data['backendlang']['backendlang']['Buyer']) ? $data['backendlang']['backendlang']['Buyer'] :'' }}</b></th>
			<th style="text-align: right;"><b>{{ isset($data['backendlang']['backendlang']['Net_Quantity']) ? $data['backendlang']['backendlang']['Net_Quantity'] :'' }}</b></th>
			<th style="text-align: right;"><b>{{ isset($data['backendlang']['backendlang']['Discounts']) ? $data['backendlang']['backendlang']['Discounts'] :'' }}</b></th>
			<th style="text-align: right;"><b>{{ isset($data['backendlang']['backendlang']['Shipping_Fee']) ? $data['backendlang']['backendlang']['Shipping_Fee'] :'' }}</b></th>
			<th style="text-align: right;"><b>{{ isset($data['backendlang']['backendlang']['Net_Sales']) ? $data['backendlang']['backendlang']['Net_Sales'] :'' }}</b></th>
			<th style="text-align: right;"><b>{{ isset($data['backendlang']['backendlang']['Tax']) ? $data['backendlang']['backendlang']['Tax'] :'' }}</b></th>
			<th style="text-align: right;"><b>{{ isset($data['backendlang']['backendlang']['Total_Sales']) ? $data['backendlang']['backendlang']['Total_Sales'] :'' }}</b></th>
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
			<td>
				<b>{{ isset($data['backendlang']['backendlang']['Summary']) ? $data['backendlang']['backendlang']['Summary'] :'' }}</b>
			</td>
			<td></td>
			<td></td>
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