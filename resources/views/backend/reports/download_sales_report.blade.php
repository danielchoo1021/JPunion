<table>
	<tr>
		<th colspan="3">
			<b>{{ $data['web_setting']->invoice_name ?? $data['website_setting']->website_name }}</b>
		</th>
		<th></th>
		<th></th>
		<th colspan="3" align="right">
			<b>{{ isset($data['backendlang']['backendlang']['Profit_Report']) ? $data['backendlang']['backendlang']['Profit_Report'] :'' }}</b>
		</th>
	</tr>
	<tr>
		<th colspan="3">
			{{ isset($data['backendlang']['backendlang']['print_date']) ? $data['backendlang']['backendlang']['print_date'] :'' }}: {{ date('Y-m-d H:i:s') }}
		</th>
		<th></th>
		<th></th>
		<th colspan="3" align="right">
			{{ isset($data['backendlang']['backendlang']['report_date']) ? $data['backendlang']['backendlang']['report_date'] :'' }}: {{ $start }} - {{ $end }}
		</th> 
	</tr>
</table>
<table class="table table-bordered">
	<thead>
		<tr class="info">
			<th>#</th>
			<th>{{ isset($data['backendlang']['backendlang']['Product_Name']) ? $data['backendlang']['backendlang']['Product_Name'] :'' }}</th>
			<th>{{ isset($data['backendlang']['backendlang']['Price_Type']) ? $data['backendlang']['backendlang']['Price_Type'] :'' }}</th>
			<th>{{ isset($data['backendlang']['backendlang']['Item_Code']) ? $data['backendlang']['backendlang']['Item_Code'] :'' }}</th>
			<th>{{ isset($data['backendlang']['backendlang']['ProductSKU']) ? $data['backendlang']['backendlang']['ProductSKU'] :'' }}</th>
			<th align="right">{{ isset($data['backendlang']['backendlang']['Net_Quantity']) ? $data['backendlang']['backendlang']['Net_Quantity'] :'' }}</th>
			<th align="right">{{ isset($data['backendlang']['backendlang']['Costing_Price']) ? $data['backendlang']['backendlang']['Costing_Price'] :'' }}</th>
			<th align="right">{{ isset($data['backendlang']['backendlang']['Total_Sales']) ? $data['backendlang']['backendlang']['Total_Sales'] :'' }} (RM)</th>
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
					<td>{{ $transaction->product_name }}</td>
					<td>{{ $pricingTypeLabels[$transaction->get_pricing_type] ?? $transaction->get_pricing_type }}</td>
					<td>{{ $transaction->item_code }}</td>
					<td>{{ $transaction->product_code }}</td>
					<td align="right">{{ $transaction->totalQty }}</td>
					<td align="right">{{ number_format($transaction->costing_price, 2) }}</td>
					<td align="right">{{ number_format($transaction->totalNet - $transaction->totalDiscount, 2) }}</td>
					
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
			<td style="border: none;" align="right">
				<b>{{ $totalQty }}</b>
			</td>
			<td style="border: none;" align="right">
				<b>{{ number_format($totalCost, 2) }}</b>
			</td>
			<td style="border: none;" align="right">
				<b>{{ number_format($totalNet - $totalDiscount, 2) }}</b>
			</td>
		</tr>
	</tbody>
</table>