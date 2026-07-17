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
		<th></th>
		<th></th>
		<th></th>
		<th align="right">
			<b>{{ isset($data['backendlang']['backendlang']['point_order_report']) ? $data['backendlang']['backendlang']['point_order_report'] :'' }}</b>
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
		<th></th>
		<th></th>
		<th></th>
		<th align="right">
			{{ isset($data['backendlang']['backendlang']['report_date']) ? $data['backendlang']['backendlang']['report_date'] :'' }}: {{ $start }} - {{ $end }}
		</th> 
	</tr>
</table>
<table class="table table-bordered">
	<thead>
		<tr class="info">
			<th>#</th>
			<th>{{ isset($data['backendlang']['backendlang']['Date']) ? $data['backendlang']['backendlang']['Date'] :'' }}</th>
			<th>{{ isset($data['backendlang']['backendlang']['Transaction_No']) ? $data['backendlang']['backendlang']['Transaction_No'] :'' }}</th>
			<th>{{ isset($data['backendlang']['backendlang']['Buyer']) ? $data['backendlang']['backendlang']['Buyer'] :'' }}</th>
			<th style="text-align: center;">{{ isset($data['backendlang']['backendlang']['Item_Code']) ? $data['backendlang']['backendlang']['Item_Code'] :'' }}</th>
			<th style="text-align: center;">{{ isset($data['backendlang']['backendlang']['ProductSKU']) ? $data['backendlang']['backendlang']['ProductSKU'] :'' }}</th>
			<th>{{ isset($data['backendlang']['backendlang']['Product_Description']) ? $data['backendlang']['backendlang']['Product_Description'] :'' }}</th>
			<!-- <th>Unit Price</th> -->
			<th style="text-align: center;">{{ isset($data['backendlang']['backendlang']['Quantity']) ? $data['backendlang']['backendlang']['Quantity'] :'' }}</th>
			<th style="text-align: right;">{{ isset($data['backendlang']['backendlang']['Sales_Points']) ? $data['backendlang']['backendlang']['Sales_Points'] :'' }}</th>
			<th style="text-align: right;">{{ isset($data['backendlang']['backendlang']['Net_Sales_Points']) ? $data['backendlang']['backendlang']['Net_Sales_Points'] :'' }}</th>
			<th style="text-align: right;">{{ isset($data['backendlang']['backendlang']['Total_Net_Sales_Points']) ? $data['backendlang']['backendlang']['Total_Net_Sales_Points'] :'' }}</th>
			<!-- <th style="text-align: right;">Processing fee (Points)</th> -->
			<th style="text-align: right;">{{ isset($data['backendlang']['backendlang']['Shipping_Fee_Points']) ? $data['backendlang']['backendlang']['Shipping_Fee_Points'] :'' }}</th>
			<th style="text-align: right;">{{ isset($data['backendlang']['backendlang']['Discount_Points']) ? $data['backendlang']['backendlang']['Discount_Points'] :'' }}</th>
			<!-- <th style="text-align: right;">Agent Discount (Points)</th>
			<th style="text-align: right;">Tax (Points)</th> -->
			<th style="text-align: right;">{{ isset($data['backendlang']['backendlang']['Total_Sales_Points']) ? $data['backendlang']['backendlang']['Total_Sales_Points'] :'' }}</th>
			<!-- <th>Action</th> -->
		</tr>
	</thead>
	<tbody>
		@php
		$totalQty = 0;
		$totaluPrice = 0;
		$totalpfee = 0;
		$totalsfee = 0;
		$totalnet = 0;
		$totalTax = 0;
		$totalgrand = 0;
		$totalGrandNet = 0;
		$totalDis = 0;
		$totalAdDis = 0;
		$b = 0;
		@endphp
		@if(!$transactions->isEmpty())
		@foreach($transactions as $key => $transaction)
		<tr>
			
			<td align="left" @if(count($details[$transaction->Tid]) > 1) rowspan="{{ count($details[$transaction->Tid])+1 }}" @else rowspan="2"  @endif>
				{{ $key+1 }}

			</td>
			<td align="left" @if(count($details[$transaction->Tid]) > 1) rowspan="{{ count($details[$transaction->Tid])+1 }}" @else rowspan="2"  @endif>
				{{ ($transaction->created_at) }}
			</td>
			<td align="left" @if(count($details[$transaction->Tid]) > 1) rowspan="{{ count($details[$transaction->Tid])+1 }}" @else rowspan="2"  @endif>
				{{ $transaction->transaction_no }}
			</td>
			<td align="left" @if(count($details[$transaction->Tid]) > 1) rowspan="{{ count($details[$transaction->Tid])+1 }}" @else rowspan="2"  @endif>
				{{ (!empty($transaction->buyer_name)) ? $transaction->buyer_name : $transaction->address_name }}
			</td>
		</tr>
		@php
			$net = 0;
			$a = 0;
			$uprice = 0;
			
		@endphp
		@foreach($details[$transaction->Tid] as $detail)
		@php
			$totalQty += $detail->quantity;
			
			$uprice += $detail->unit_price;
			$net += ($detail->unit_price) * $detail->quantity;
			
		@endphp
		<tr>
			<td align="center">{{ $detail->item_code }}</td>
			<td align="center">{{ $detail->product_code }}</td>
			<td>
				{{ $detail->product_name }}<br>
				{!! ($detail->sub_category != '') ? "Option: ".$detail->sub_category."<br>" : '' !!}
				{!! ($detail->second_sub_category != '') ? "Second Option: ".$detail->second_sub_category."<br>" : '' !!}
			</td>
			<td align="center">{{ $detail->quantity }}</td>
			<td align="right">{{ number_format(($detail->unit_price), 2) }}</td>
			<td align="right">{{ number_format(($detail->unit_price) * $detail->quantity, 2) }}</td>

			

			@if($a == 0)
			<td align="right" @if(count($details[$transaction->Tid]) > 1) rowspan="{{ count($details[$transaction->Tid]) }}" @endif>
				{{ number_format(($details2[$transaction->Tid]->totalPrice), 2) }}
			</td>
			<!-- <td align="right" @if(count($details[$transaction->Tid]) > 1) rowspan="{{ count($details[$transaction->Tid]) }}" @endif>
				{{ number_format($transaction->processing_fee, 2) }}
			</td> -->

			<td align="right" @if(count($details[$transaction->Tid]) > 1) rowspan="{{ count($details[$transaction->Tid]) }}" @endif>
				{{ number_format($transaction->shipping_fee, 2) }}
			</td>
			<td align="right" @if(count($details[$transaction->Tid]) > 1) rowspan="{{ count($details[$transaction->Tid]) }}" @endif>
				{{ number_format($transaction->discount, 2) }}
			</td>
			<!-- <td align="right" @if(count($details[$transaction->Tid]) > 1) rowspan="{{ count($details[$transaction->Tid]) }}" @endif>
				{{ number_format($transaction->ad_discount, 2) }}
			</td>
			<td align="right" @if(count($details[$transaction->Tid]) > 1) rowspan="{{ count($details[$transaction->Tid]) }}" @endif>
				{{ number_format($transaction->tax, 2) }}
			</td> -->
			<td align="right" @if(count($details[$transaction->Tid]) > 1) rowspan="{{ count($details[$transaction->Tid]) }}" @endif>
				{{ number_format($details2[$transaction->Tid]->totalPrice - $transaction->discount - $transaction->ad_discount + $transaction->processing_fee + $transaction->shipping_fee + $transaction->tax, 2) }}
			</td>
			@endif

		</tr>
		@php
			$a++;
		@endphp
		@endforeach

			@php
			
			
			$totaluPrice += $uprice;
			$totalnet += $net;
			$totalpfee += $transaction->processing_fee;
			$totalsfee += $transaction->shipping_fee;
			$totalTax += $transaction->tax;
			$totalGrandNet += ($details2[$transaction->Tid]->totalPrice);
			$totalDis += $transaction->discount;
			$totalAdDis += $transaction->ad_discount;
			
			@endphp
			@endforeach
			@else
			<tr>
				<td colspan="12">{{ isset($data['backendlang']['backendlang']['No_Result_Found']) ? $data['backendlang']['backendlang']['No_Result_Found'] :'' }}</td>
			</tr>
			@endif

			<tr class="warning">
				<td style=""  colspan="7">
					<b>{{ isset($data['backendlang']['backendlang']['Page_Summary']) ? $data['backendlang']['backendlang']['Page_Summary'] :'' }}</b>
				</td>
				<td style=" text-align: right;" >
					<b>{{ $totalQty }}</b>
					</td>
					<td style=" text-align: right;" class="">
						<b>{{ number_format($totaluPrice, 2) }}</b>
					</td>
					<td style=" text-align: right;" class="">
						<b>{{ number_format($totalnet, 2) }}</b>
					</td>
					<td style=" text-align: right;" class="">
						<b>{{ number_format($totalGrandNet, 2) }}</b>
					</td>
					<!-- <td style=" text-align: right;" class="">
						<b>{{ number_format($totalpfee, 2) }}</b>
					</td> -->
					<td style=" text-align: right;" class="">
						<b>{{ number_format($totalsfee, 2) }}</b>
					</td>
					
					<td style=" text-align: right;" class="">
						<b>{{ number_format($totalDis, 2) }}</b>
					</td>
					<!-- <td style=" text-align: right;" class="">
						<b>{{ number_format($totalAdDis, 2) }}</b>
					</td>
					<td style=" text-align: right;" class="">
						<b>{{ number_format($totalTax, 2) }}</b>
					</td> -->
					<td style=" text-align: right;" class="">
						<b>{{ number_format(($totalnet) - $totalDis - $totalAdDis + $totalpfee + $totalsfee + $totalTax, 2) }}</b>
					</td>
				</tr>
				
			</tbody>
		</table>

