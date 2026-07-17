<table>
	<tr>
		<th><b>{{ !empty($data['web_setting']->invoice_name) ? $data['web_setting']->invoice_name : $data['admin']->company_name }}</b></th>
		<th></th>
		<th></th>
		<th></th>
		<th></th>
		<th></th>
		<th></th>
		<th align="right">
			<b>{{ isset($data['backendlang']['backendlang']['stock_report']) ? $data['backendlang']['backendlang']['stock_report'] :'' }}</b>
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
	</tr>
</table>
<table class="table table-bordered">
	<thead>
		<tr class="info">
			<th>#</th>
			<th>{{ isset($data['backendlang']['backendlang']['Product_Name']) ? $data['backendlang']['backendlang']['Product_Name'] :'' }}</th>
			<th>{{ isset($data['backendlang']['backendlang']['Type']) ? $data['backendlang']['backendlang']['Type'] :'' }}</th>
			<th>{{ isset($data['backendlang']['backendlang']['Item_Code']) ? $data['backendlang']['backendlang']['Item_Code'] :'' }}</th>
			<th>{{ isset($data['backendlang']['backendlang']['Total_Costing_Price']) ? $data['backendlang']['backendlang']['Total_Costing_Price'] :'' }}</th>
			<th>{{ isset($data['backendlang']['backendlang']['Total_Unit_Sold_Price']) ? $data['backendlang']['backendlang']['Total_Unit_Sold_Price'] :'' }}</th>
			<th style="text-align: right;">{{ isset($data['backendlang']['backendlang']['In_Stock']) ? $data['backendlang']['backendlang']['In_Stock'] :'' }}</th>
			<th style="text-align: right;">{{ isset($data['backendlang']['backendlang']['Out_Stock']) ? $data['backendlang']['backendlang']['Out_Stock'] :'' }}</th>
			<th style="text-align: right;">{{ isset($data['backendlang']['backendlang']['Stock_Sold']) ? $data['backendlang']['backendlang']['Stock_Sold'] :'' }}</th>
			<th style="text-align: right;">{{ isset($data['backendlang']['backendlang']['Current_Stock_Amount']) ? $data['backendlang']['backendlang']['Current_Stock_Amount'] :'' }}</th>
		</tr>
	</thead>
	<tbody>
		@php
		$overallInStock = 0;
		$overallOutStock = 0;
		$overallSoldStock = 0;
		$overallSoldStockDelivery = 0;
		$overallStockAmount = 0;
		$overallSoldPrice = 0;
		$overallCostPrice = 0;
		@endphp
		@if(!$products->isEmpty())
		@foreach($products as $key => $product)
		<tr>
			<td>
				{{ $key+1 }}
				<input type="hidden" name="pid" value="{{ $product->id }}">

			</td>
			<td>
				<a href="{{ route('stock_report_details', [
								$product->id,
								'variation=' . ($product->variation_id == 0 ? '' : $product->variation_id),
								'second_variation=' . ($product->second_variation_id == 0 ? '' : $product->second_variation_id)
							]) }}">
					{!! $product->product_name !!}
				</a>
			</td>
			<td>
				@if($product->type == 'NormalProduct')
				@if(!empty($product->packages))
				Package
				@else
				Normal Product
				@endif
				@elseif($product->type == 'VariationProduct')
				Product Variation
				@else
				Product Second Variation
				@endif
			</td>
			<td>{{ $product->item_code }}</td>
			<td>
				@if(!empty($stockCostPrice[$key]))
				{{ $stockCostPrice[$key] }}
				@else
				0.00
				@endif
			</td>
			<td>
				@if(!empty($stockSoldPrice[$key]))
				{{ $stockSoldPrice[$key] }}
				@else
				0.00
				@endif
			</td>
			<td align="right">
				@if(!empty($totalInStock[$key]->totalStockIn))
				{{ $totalInStock[$key]->totalStockIn }}
				@else
				0
				@endif
			</td>
			<td align="right">
				@if(!empty($totalOutStock[$key]->totalStockOut))
				{{ $totalOutStock[$key]->totalStockOut }}
				@else
				0
				@endif
			</td>
			<td align="right">
				@if(!empty($totalSoldStockByDelivery[$key]->TransCart))
				{{ $totalSoldStockByDelivery[$key]->TransCart }}
				@else
				0
				@endif
			</td>
			<td align="right">
				@if(!empty($currentStockAmount[$key]))
				{{ $currentStockAmount[$key] }}
				@else
				0
				@endif
			</td>
		</tr>
		@php
		$overallInStock += $totalInStock[$key]->totalStockIn;
		$overallOutStock += $totalOutStock[$key]->totalStockOut;
		$overallSoldStock += $totalSoldStock[$key]->TransCart;
		$overallStockAmount += $currentStockAmount[$key];
		$overallSoldStockDelivery += $totalSoldStockByDelivery[$key]->TransCart;
		$overallSoldPrice += $stockSoldPrice[$key];
		$overallCostPrice += $stockCostPrice[$key];
		@endphp
		@endforeach
		@else
		<tr>
			<td colspan="10">{{ isset($data['backendlang']['backendlang']['No_Result_Found']) ? $data['backendlang']['backendlang']['No_Result_Found'] :'' }}</td>
		</tr>
		@endif
		<tr class="warning">
			<td colspan="4">
				<b>{{ isset($data['backendlang']['backendlang']['Summary']) ? $data['backendlang']['backendlang']['Summary'] :'' }}</b>
			</td>
			<td align="right">
				{{ $overallCostPrice }}
			</td>
			<td align="right">
				{{ $overallSoldPrice }}
			</td>
			<td align="right">
				{{ $overallInStock }}
			</td>
			<td align="right">
				{{ $overallOutStock }}
			</td>
			<td align="right">
				{{ $overallSoldStockDelivery }}
			</td>
			<td align="right">
				{{ $overallStockAmount }}
			</td>
		</tr>
	</tbody>
</table>