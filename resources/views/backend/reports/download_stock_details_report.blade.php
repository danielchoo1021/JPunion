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
			<b>{{ isset($data['backendlang']['backendlang']['Stock_Details_Report']) ? $data['backendlang']['backendlang']['Stock_Details_Report'] :'' }}</b>
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
		<th align="right">
			{{ isset($data['backendlang']['backendlang']['report_date']) ? $data['backendlang']['backendlang']['report_date'] :'' }}: {{ $start }} - {{ $end }}
		</th> 
	</tr>
</table>
<table class="table table-bordered">
			<thead>
				<tr class="info">
					<th>#</th>
					<th>{{ isset($data['backendlang']['backendlang']['Transaction_No']) ? $data['backendlang']['backendlang']['Transaction_No'] :'' }}</th>
					<th>{{ isset($data['backendlang']['backendlang']['Item_Code']) ? $data['backendlang']['backendlang']['Item_Code'] :'' }}</th>
					<th>{{ isset($data['backendlang']['backendlang']['Date']) ? $data['backendlang']['backendlang']['Date'] :'' }}</th>
					<th style="text-align: right;">{{ isset($data['backendlang']['backendlang']['In_Stock']) ? $data['backendlang']['backendlang']['In_Stock'] :'' }}</th>
					<th style="text-align: right;">{{ isset($data['backendlang']['backendlang']['Out_Stock']) ? $data['backendlang']['backendlang']['Out_Stock'] :'' }}</th>
					<th style="text-align: right;">{{ isset($data['backendlang']['backendlang']['Stock_Sold_and_Delivered']) ? $data['backendlang']['backendlang']['Stock_Sold_and_Delivered'] :'' }}</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@php
					$overallInStock = 0;
					$overallOutStock = 0;
					$overallSoldStock = 0;
					$overallStockAmount = 0;
				@endphp
				@if(!$stocks->isEmpty())
				@foreach($stocks as $key => $stock)
				<tr>
					<td>
						{{ $key+1 }}
					</td>
					<td>
						@if(!empty($stock->transaction_id))
							<a href="{{ route('transaction.transactions.edit', $stock->transaction_id) }}">
								="{{ $stock->transaction_no }}"
							</a>
						@elseif(!empty($stock->totalStockIn) || !empty($stock->totalStockOut))
							<a href="{{ route('stock', [$stock->product_id]) }}">
								{{ isset($data['backendlang']['backendlang']['Stock_Management']) ? $data['backendlang']['backendlang']['Stock_Management'] :'' }}
							</a>
						@else
							<i class="fa fa-minus"></i>
						@endif
					</td>
					<td>{{ $products->item_code }}</td>
					<td>
						@if(!empty($stock->created_at))
							{{ $stock->created_at }}
						@else
							<!-- <i class="fa fa-minus"></i> -->
							0
						@endif
					</td>
					<td align="right">
						@if(!empty($stock->totalStockIn))
							{{ $stock->totalStockIn }}
						@else
							<!-- <i class="fa fa-minus"></i> -->
							0
						@endif
					</td>
					<td align="right">
						@if(!empty($stock->totalStockOut))
							{{ $stock->totalStockOut }}
						@else
							<!-- <i class="fa fa-minus"></i> -->
							0
						@endif
					</td>
					<td align="right">
						@if(!empty($stock->TransCart))
							{{ $stock->TransCart }}
						@else
							<!-- <i class="fa fa-minus"></i> -->
							0
						@endif
					</td>
					<td></td>
				</tr>
				@php
					$overallInStock += $stock->totalStockIn;
					$overallOutStock += $stock->totalStockOut;
					$overallSoldStock += $stock->TransCart;
				@endphp
				@endforeach
				@else
				<tr>
					<td colspan="7">{{ isset($data['backendlang']['backendlang']['No_Result_Found']) ? $data['backendlang']['backendlang']['No_Result_Found'] :'' }}</td>
				</tr>
				@endif

				@php
					$overallStockAmount = $overallInStock - $overallOutStock - $overallSoldStock;
				@endphp
				<tr class="warning">
					<th colspan="4">
						<b>{{ isset($data['backendlang']['backendlang']['Summary']) ? $data['backendlang']['backendlang']['Summary'] :'' }}</b>
					</th>
					<th style="text-align: right;">
						<b>{{ isset($data['backendlang']['backendlang']['Total_In_Stock']) ? $data['backendlang']['backendlang']['Total_In_Stock'] :'' }}</b>
					</th>
					<th style="text-align: right;">
						<b>{{ isset($data['backendlang']['backendlang']['Total_Out_Stock']) ? $data['backendlang']['backendlang']['Total_Out_Stock'] :'' }}</b>
					</th>
					<th style="text-align: right;">
						<b>{{ isset($data['backendlang']['backendlang']['Total_Sold_Stock']) ? $data['backendlang']['backendlang']['Total_Sold_Stock'] :'' }}</b>
					</th>
					<th style="text-align: right;">
						<b>{{ isset($data['backendlang']['backendlang']['Stock_Balance']) ? $data['backendlang']['backendlang']['Stock_Balance'] :'' }}</b>
					</th>
				</tr>
				<tr class="warning">
					<td colspan="4">
						
					</td>
					<td align="right">
						{{ $overallInStock }}
					</td>
					<td align="right">
						{{ $overallOutStock }}
					</td>
					<td align="right">
						{{ $overallSoldStock }}
					</td>
					<td align="right">
						@if(!empty(request('sort_month')))
						{{ $closedStock }}
						@endif
					</td>
				</tr>
			</tbody>
		</table>