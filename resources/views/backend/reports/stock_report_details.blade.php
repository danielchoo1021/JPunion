@extends('layouts.admin_app')

@section('content')
<div class="form-group container-box">
	<form action="{{ route('stock_report_details', $products->id) }}" method="GET">
		<h3>{{ isset($data['backendlang']['backendlang']['Filter']) ? $data['backendlang']['backendlang']['Filter'] :'' }}</h3>
		<div class="row">
			<div class="col-sm-3">
				<div class="form-group">
					<input type="text" class="form-control" name="dates" value="{{ !empty(request('dates')) ? request('dates') : $startDate.' - '.$endDate }}">
				</div>
			</div>
			<!-- <div class="col-sm-2">
				<div class="form-group">
					<select class="form-control" name="sort_month">
						<option value="">Select Month</option>
						<option {{ (!empty(request('sort_month')) && request('sort_month') == '1') ? 'selected' : '' }} value="1">January</option>
						<option {{ (!empty(request('sort_month')) && request('sort_month') == '2') ? 'selected' : '' }} value="2">February</option>
						<option {{ (!empty(request('sort_month')) && request('sort_month') == '3') ? 'selected' : '' }} value="3">March</option>
						<option {{ (!empty(request('sort_month')) && request('sort_month') == '4') ? 'selected' : '' }} value="4">April</option>
						<option {{ (!empty(request('sort_month')) && request('sort_month') == '5') ? 'selected' : '' }} value="5">May</option>
						<option {{ (!empty(request('sort_month')) && request('sort_month') == '6') ? 'selected' : '' }} value="6">June</option>
						<option {{ (!empty(request('sort_month')) && request('sort_month') == '7') ? 'selected' : '' }} value="7">July</option>
						<option {{ (!empty(request('sort_month')) && request('sort_month') == '8') ? 'selected' : '' }} value="8">August</option>
						<option {{ (!empty(request('sort_month')) && request('sort_month') == '9') ? 'selected' : '' }} value="9">September</option>
						<option {{ (!empty(request('sort_month')) && request('sort_month') == '10') ? 'selected' : '' }} value="10">October</option>
						<option {{ (!empty(request('sort_month')) && request('sort_month') == '11') ? 'selected' : '' }} value="11">November</option>
						<option {{ (!empty(request('sort_month')) && request('sort_month') == '12') ? 'selected' : '' }} value="12">December</option>
					</select>
				</div>
			</div> -->
		</div>

		<input type="hidden" name="variation" value="{{ !empty(request('variation'))? request('variation'):'' }}">
		<input type="hidden" name="second_variation" value="{{ !empty(request('second_variation'))? request('second_variation'):'' }}">

		<div class="form-group">
			<div class="row">
				<div class="col-sm-4">
					<div class="form-group">
						<button class="btn btn-outline-primary btn-sm">
							<i class="bi bi-search"></i> {{ isset($data['backendlang']['backendlang']['Search']) ? $data['backendlang']['backendlang']['Search'] :'' }}
						</button>
						<a href="{{ route('stock_report_details', $products->id) }}?variation={{ request('variation') ?? '' }}&second_variation={{ request('second_variation') ?? '' }}" class="btn btn-warning btn-sm">
							<i class="bi bi-arrow-clockwise"></i> {{ isset($data['backendlang']['backendlang']['Clear_Search']) ? $data['backendlang']['backendlang']['Clear_Search'] :'' }}
						</a>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
<div class="container-box">
	<div class="form-group">
		<span class="" style="font-size: 1.5rem; padding: 10px;">
			{{ isset($data['backendlang']['backendlang']['Open_Stock']) ? $data['backendlang']['backendlang']['Open_Stock'] :'' }}:
			<span class="openStock">
				@if(!empty(request('sort_month')) || !empty(request('dates')))
				@if(!empty($openStock))
				{{ $openStock }}
				@else
				0
				@endif
				@endif
			</span>
		</span>
		<!-- <span class="badge label-danger" style="font-size: 1.5rem; padding: 10px;">
			Closed Stock: 
			<span class="closedStock">
			@if(!empty(request('sort_month')))
			{{ $closedStock }}
			@endif
			</span>
		</span> -->
	</div>
	<hr>
	<div class="form-group" align="right">
		<a href="{{ route('exportStockDetailsReport', ['dates='.(!empty(request('dates')) ? request('dates') : $startDate.' - '.$endDate), 
													 					'buyer='.(!empty(request('buyer')) ? request('buyer') : ''), 
																		'product_id='.$products->id, 
																		'variation='.(request('variation') ?? ''),
																		'second_variation='.(request('second_variation') ?? ''),
													 					'sort_month='.(!empty(request('sort_month')) ? request('sort_month') : '')]) }}" target="_blank" class="btn btn-warning">
			<i class="fa fa-download"></i> {{ isset($data['backendlang']['backendlang']['Export']) ? $data['backendlang']['backendlang']['Export'] :'' }}
		</a>
	</div>

	<div class="row" style="overflow: auto;">
		<div class="col-12">
			<table class="table table-bordered">
				<thead>
					<tr class="info">
						<th>#</th>
						<th>{{ isset($data['backendlang']['backendlang']['Transaction_No']) ? $data['backendlang']['backendlang']['Transaction_No'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Item_Code']) ? $data['backendlang']['backendlang']['Item_Code'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Costing_Price']) ? $data['backendlang']['backendlang']['Costing_Price'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Unit_Price']) ? $data['backendlang']['backendlang']['Unit_Price'] :'' }}</th>
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
								{{ $stock->transaction_no }}
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
							@if(!empty($stock->costing_price))
							{{ $stock->costing_price }}
							@else
							-
							@endif
						</td>
						<td>
							@if(!empty($stock->unit_price))
							{{ $stock->unit_price }}
							@else
							-
							@endif
						</td>
						<td>
							@if(!empty($stock->created_at))
							{{ $stock->created_at }}
							@else
							<i class="fa fa-minus"></i>
							@endif
						</td>
						<td align="right">
							@if(!empty($stock->totalStockIn))
							{{ $stock->totalStockIn }}
							@else
							<i class="fa fa-minus"></i>
							@endif
						</td>
						<td align="right">
							@if(!empty($stock->totalStockOut))
							{{ $stock->totalStockOut }}
							@else
							<i class="fa fa-minus"></i>
							@endif
						</td>
						<td align="right">
							@if(!empty($stock->TransCart))
							{{ $stock->TransCart }}
							@else
							<i class="fa fa-minus"></i>
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
						<th colspan="6">
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
						<td colspan="6">

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
							@if(!empty(request('sort_month')) || !empty(request('dates')))
								@if(!empty($closedStock))
									{{ $closedStock }}
								@else
									0
								@endif
							@endif
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
@endsection

@section('js')
<script type="text/javascript">
	$('input[name=dates]').daterangepicker({
			'applyClass': 'btn-sm btn-success',
			'cancelClass': 'btn-sm btn-outline-danger',
			locale: {
				applyLabel: "{{ isset($data['backendlang']['backendlang']['Apply']) ? $data['backendlang']['backendlang']['Apply'] :''}}",
				cancelLabel: "{{ isset($data['backendlang']['backendlang']['Cancel']) ? $data['backendlang']['backendlang']['Cancel'] :''}}",
				format: 'DD/MM/YYYY',
			}
		})
		.prev().on(ace.click_event, function() {
			$(this).next().focus();
		});

	// $('.openStock').html('{{ $overallStockAmount }} Units');
	// $('.closedStock').html('{{ $overallStockAmount }} Units');
</script>
@endsection