@extends('layouts.admin_app')

@section('content')
<div class="container-box form-group">
	<h3>{{ isset($data['backendlang']['backendlang']['Filter']) ? $data['backendlang']['backendlang']['Filter'] :'' }}</h3>
	<hr>
	<form action="{{ route('stock_report') }}" method="GET">
	<div class="row">
		<!-- <div class="col-sm-2">
			<div class="form-group">
				<input type="text" class="form-control" name="dates" value="{{ !empty(request('dates')) ? request('dates') : $startDate.' - '.$endDate }}">
			</div>
		</div> -->

		<div class="col-sm-2">
			<div class="form-group">
				<input type="text" class="form-control" name="product_name" value="{{ !empty(request('product_name')) ? request('product_name') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Product_Name']) ? $data['backendlang']['backendlang']['Search_Product_Name'] :'' }}">
			</div>
		</div>

		<div class="col-sm-2">
			<div class="form-group">
				<input type="text" class="form-control" name="item_code" value="{{ !empty(request('item_code')) ? request('item_code') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_item_code']) ? $data['backendlang']['backendlang']['Search_item_code'] :'' }}">
			</div>
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
					<a href="{{ route('stock_report') }}" class="btn btn-warning btn-sm">
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
		<a href="{{ route('exportStockReport', ['item_code='.(!empty(request('item_code')) ? request('item_code') : ''), 
													 'product_name='.(!empty(request('product_name')) ? request('product_name') : '')]) }}" target="_blank" class="btn btn-warning">
			<i class="fa fa-download"></i> {{ isset($data['backendlang']['backendlang']['Export']) ? $data['backendlang']['backendlang']['Export'] :'' }}
		</a>
	</div>

	<div class="row" style="overflow: auto;">
		<div class="col-12">
			{{ $products->links() }}
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
								{{ isset($data['backendlang']['backendlang']['package']) ? $data['backendlang']['backendlang']['package'] :'' }}
								@else
								{{ isset($data['backendlang']['backendlang']['Normal_Product']) ? $data['backendlang']['backendlang']['Normal_Product'] :'' }}
								@endif
							@elseif($product->type == 'VariationProduct')
							{{ isset($data['backendlang']['backendlang']['Product_Variation']) ? $data['backendlang']['backendlang']['Product_Variation'] :'' }}
							@else
							{{ isset($data['backendlang']['backendlang']['Product_Second_Variation']) ? $data['backendlang']['backendlang']['Product_Second_Variation'] :'' }}
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
			{{ $products->links() }}
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
	})
	.prev().on(ace.click_event, function(){
		$(this).next().focus();
	});

</script>
@endsection