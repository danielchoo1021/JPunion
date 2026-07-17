@extends('layouts.admin_app')

@section('content')
<div class="container-box form-group">
	<h3>{{ isset($data['backendlang']['backendlang']['Filter']) ? $data['backendlang']['backendlang']['Filter'] :'' }}</h3>
	<hr>
	<form action="{{ route('order_report') }}" method="GET">
		<div class='form-group'>
			<div class="row">
				<div class="col-sm-2">
					<div class="form-group">
						<input type="text" class="form-control" name="dates" value="{{ !empty(request('dates')) ? request('dates') : $startDate.' - '.$endDate }}">
					</div>
				</div>

				<div class="col-sm-2">
					<div class="form-group">
						<select class="form-control" name="transaction_type">
							<option value="">{{ isset($data['backendlang']['backendlang']['Select_Type']) ? $data['backendlang']['backendlang']['Select_Type'] :'' }}</option>
							<option {{ (!empty(request('transaction_type')) && request('transaction_type') == '1') ? 'selected' : '' }} value="1">{{ isset($data['backendlang']['backendlang']['Normal_Transaction']) ? $data['backendlang']['backendlang']['Normal_Transaction'] :'' }}</option>
							<option {{ (!empty(request('transaction_type')) && request('transaction_type') == '2') ? 'selected' : '' }} value="2">{{ isset($data['backendlang']['backendlang']['Cash_Wallet']) ? $data['backendlang']['backendlang']['Cash_Wallet'] :'' }}</option>
							<option {{ (!empty(request('transaction_type')) && request('transaction_type') == '3') ? 'selected' : '' }} value="3">{{ isset($data['backendlang']['backendlang']['Topup_Wallet']) ? $data['backendlang']['backendlang']['Topup_Wallet'] :'' }}</option>
						</select>
					</div>
				</div>

				<div class="col-sm-2">
					<div class="form-group">
						<input type="text" class="form-control" name="transaction_no" value="{{ !empty(request('transaction_no')) ? request('transaction_no') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Transaction_No']) ? $data['backendlang']['backendlang']['Search_Transaction_No'] :'' }}">
					</div>
				</div>

				<div class="col-sm-2">
					<div class="form-group">
						<input type="text" class="form-control" name="buyer" value="{{ !empty(request('buyer')) ? request('buyer') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Buyer']) ? $data['backendlang']['backendlang']['Search_Buyer'] :'' }}">
					</div>
				</div>

				<div class="col-sm-2">
					<div class="form-group">
						<input type="text" class="form-control" name="item_code" value="{{ !empty(request('item_code')) ? request('item_code') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_item_code']) ? $data['backendlang']['backendlang']['Search_item_code'] :'' }}">
					</div>
				</div>

				<div class="col-sm-2">
					<div class="form-group">
						<input type="text" class="form-control" name="product_code" value="{{ !empty(request('product_code')) ? request('product_code') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Product_Code']) ? $data['backendlang']['backendlang']['Search_Product_Code'] :'' }}">
					</div>
				</div>
			</div>
			<!-- <div class="row">
				<div class="col-sm-2">
					<div class="form-group">
						<select class="form-control" name="yearly">
							<option value="">Select Year</option>
							<option {{ (!empty(request('yearly')) && request('yearly') == date('Y', strtotime('-5 years'))) ? 'selected' : '' }} value="{{ date('Y', strtotime('-5 years')) }}">{{ date('Y', strtotime('-5 years')) }}</option>
							<option {{ (!empty(request('yearly')) && request('yearly') == date('Y', strtotime('-4 years'))) ? 'selected' : '' }} value="{{ date('Y', strtotime('-4 years')) }}">{{ date('Y', strtotime('-4 years')) }}</option>
							<option {{ (!empty(request('yearly')) && request('yearly') == date('Y', strtotime('-3 years'))) ? 'selected' : '' }} value="{{ date('Y', strtotime('-3 years')) }}">{{ date('Y', strtotime('-3 years')) }}</option>
							<option {{ (!empty(request('yearly')) && request('yearly') == date('Y', strtotime('-2 years'))) ? 'selected' : '' }} value="{{ date('Y', strtotime('-2 years')) }}">{{ date('Y', strtotime('-2 years')) }}</option>
							<option {{ (!empty(request('yearly')) && request('yearly') == date('Y', strtotime('-1 years'))) ? 'selected' : '' }} value="{{ date('Y', strtotime('-1 years')) }}">{{ date('Y', strtotime('-1 years')) }}</option>
						</select>
					</div>
				</div>

				<div class="col-sm-2">
					<div class="form-group">
						<select class="form-control" name="monthly">
							<option value="">Select Month</option>
							@for($m=1; $m<=12; $m++)
								<option {{ (!empty(request('monthly')) && request('monthly') == $m) ? 'selected' : '' }} value="{{ $m }}">{{ $m }}</option>
								@endfor
						</select>
					</div>
				</div>

				<div class="col-sm-2">
					<div class="form-group">
						<select class="form-control" name="daily">
							<option value="">Select Day</option>
							@for($d=1; $d<=31; $d++)
								<option {{ (!empty(request('daily')) && request('daily') == $d) ? 'selected' : '' }} value="{{ $d }}">{{ $d }}</option>
								@endfor
						</select>
					</div>
				</div>
			</div> -->
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
						<a href="{{ route('order_report') }}" class="btn btn-warning btn-sm">
							<i class="bi bi-arrow-clockwise"></i> {{ isset($data['backendlang']['backendlang']['Clear_Search']) ? $data['backendlang']['backendlang']['Clear_Search'] :'' }}
						</a>
					</div>
				</div>
			</div>
		</div>

		@if(!empty(request('today')))
		<input type="text" class="form-control" name="today" value="{{ !empty(request('today')) ? request('today') : '' }}" style="display:none">
		@elseif(!empty(request('this_month')))
		<input type="text" class="form-control" name="this_month" value="{{ !empty(request('this_month')) ? request('this_month') : '' }}" style="display:none">
		@elseif(!empty(request('this_year')))
		<input type="text" class="form-control" name="this_year" value="{{ !empty(request('this_year')) ? request('this_year') : '' }}" style="display:none">
		@endif
		
		@php
		$dailyDate = date('Y-m-d');
		$MonthlyDate = date('Y-m');
		$YearlyDate = date('Y');
		@endphp
	</form>
	<div class="form-group">
		<span class="badge bg-info" style="font-size: 1rem; padding: 10px;">
			@if(!empty(request('today')))
			{{ isset($data['backendlang']['backendlang']['Dates']) ? $data['backendlang']['backendlang']['Dates'] :'' }}: {{ request('today') }}
			@elseif(!empty(request('this_month')))
			{{ isset($data['backendlang']['backendlang']['Dates']) ? $data['backendlang']['backendlang']['Dates'] :'' }}: {{ request('this_month') }}
			@elseif(!empty(request('this_year')))
			{{ isset($data['backendlang']['backendlang']['Dates']) ? $data['backendlang']['backendlang']['Dates'] :'' }}: {{ request('this_year') }}
			@else
			{{ isset($data['backendlang']['backendlang']['Dates']) ? $data['backendlang']['backendlang']['Dates'] :'' }}: {{ !empty(request('dates')) ? request('dates') : $startDate.' - '.$endDate }}
			@endif
		</span>
		|
		<span class="badge bg-success" style="font-size: 1rem; padding: 10px;">
			{{ isset($data['backendlang']['backendlang']['Grand_Total']) ? $data['backendlang']['backendlang']['Grand_Total'] :'' }}: <span class="grandTotal"></span>
		</span>
		|
		<a href="{{ route('order_report', ['agent='.(!empty(request('agent')) ? request('agent') : ''),'per_page='.(!empty(request('per_page')) ? request('per_page') : ''),
												'today='.$dailyDate]) }}"
			class="btn btn-outline-primary btn-sm btn-filter {{ !empty(request('today')) ? 'filter_selected' : '' }}">
			{{ isset($data['backendlang']['backendlang']['Daily_Total_Sales']) ? $data['backendlang']['backendlang']['Daily_Total_Sales'] :'' }}
			<input type="hidden" name="filter_data" value="0">
			<br>
			RM {{ number_format($dailySales->dailySales, 2) }}
		</a>
		<a href="{{ route('order_report', ['agent='.(!empty(request('agent')) ? request('agent') : ''),'per_page='.(!empty(request('per_page')) ? request('per_page') : ''),
												'this_month='.$MonthlyDate]) }}"
			class="btn btn-outline-primary btn-sm btn-filter {{ !empty(request('this_month')) ? 'filter_selected' : '' }}">
			{{ isset($data['backendlang']['backendlang']['Monthly_Total_Sales']) ? $data['backendlang']['backendlang']['Monthly_Total_Sales'] :'' }}
			<input type="hidden" name="filter_data" value="1">
			<br>
			RM {{ number_format($monthlySales->monthlySales, 2) }}
		</a>
		<a href="{{ route('order_report', ['agent='.(!empty(request('agent')) ? request('agent') : ''),'per_page='.(!empty(request('per_page')) ? request('per_page') : ''),
												'this_year='.$YearlyDate]) }}"
			class="btn btn-outline-primary btn-sm btn-filter {{ !empty(request('this_year')) ? 'filter_selected' : '' }}">
			{{ isset($data['backendlang']['backendlang']['Yearly_Total_Sales']) ? $data['backendlang']['backendlang']['Yearly_Total_Sales'] :'' }}
			<input type="hidden" name="filter_data" value="2">
			<br>
			RM {{ number_format($yearlySales->yearlySales, 2) }}
		</a>
	</div>
</div>

<div class="container-box form-group">
	<div class="form-group" align="right">
		<a href="{{ route('print_order_report', ['dates='.(!empty(request('dates')) ? request('dates') : $startDate.' - '.$endDate),
												 'transaction_no='.(!empty(request('transaction_no')) ? request('transaction_no') : ''),
												 'buyer='.(!empty(request('buyer')) ? request('buyer') : '' ),
												 'item_code='.(!empty(request('item_code')) ? request('item_code') : '' ),
												 'product_code='.(!empty(request('product_code')) ? request('product_code') : '' ),
												 'yearly='.(!empty(request('this_year')) ? request('this_year') : '' ),
												 'monthly='.(!empty(request('this_month')) ? request('this_month') : '' ),
												 'daily='.(!empty(request('today')) ? request('today') : '' )]) }}"
			class="print-window btn btn-outline-primary" target="_blank">
			<i class="fa fa-print"></i> {{ isset($data['backendlang']['backendlang']['Print']) ? $data['backendlang']['backendlang']['Print'] :'' }}
		</a>
		<a href="{{ route('exportOrder', ['dates='.(!empty(request('dates')) ? request('dates') : $startDate.' - '.$endDate),
												 'transaction_no='.(!empty(request('transaction_no')) ? request('transaction_no') : ''),
												 'buyer='.(!empty(request('buyer')) ? request('buyer') : '' ),
												 'item_code='.(!empty(request('item_code')) ? request('item_code') : '' ),
												 'product_code='.(!empty(request('product_code')) ? request('product_code') : '' ),
											 	 'yearly='.(!empty(request('this_year')) ? request('this_year') : '' ),
												 'monthly='.(!empty(request('this_month')) ? request('this_month') : '' ),
												 'daily='.(!empty(request('today')) ? request('today') : '' ) ]) }}" target="_blank" class="btn btn-warning">
			<i class="fa fa-download"></i> {{ isset($data['backendlang']['backendlang']['Export']) ? $data['backendlang']['backendlang']['Export'] :'' }}
		</a>
	</div>
	<div class="row" style="overflow: auto;">
		<div class="col-12">
			{{ $transactions->links() }}
			<table class="table table-bordered">
				<thead>
					<tr class="info">
						<th>#</th>
						<th>{{ isset($data['backendlang']['backendlang']['Date']) ? $data['backendlang']['backendlang']['Date'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Type']) ? $data['backendlang']['backendlang']['Type'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Transaction_No']) ? $data['backendlang']['backendlang']['Transaction_No'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Buyer']) ? $data['backendlang']['backendlang']['Buyer'] :'' }}</th>
						<th style="text-align: center;">{{ isset($data['backendlang']['backendlang']['Item_Code']) ? $data['backendlang']['backendlang']['Item_Code'] :'' }}</th>
						<th style="text-align: center;">{{ isset($data['backendlang']['backendlang']['ProductSKU']) ? $data['backendlang']['backendlang']['ProductSKU'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Product_Description']) ? $data['backendlang']['backendlang']['Product_Description'] :'' }}</th>
						<!-- <th>Unit Price</th> -->
						<th style="text-align: center;">{{ isset($data['backendlang']['backendlang']['Quantity']) ? $data['backendlang']['backendlang']['Quantity'] :'' }}</th>
						<th style="text-align: right;">{{ isset($data['backendlang']['backendlang']['Sales_(RM)']) ? $data['backendlang']['backendlang']['Sales_(RM)'] :'' }}</th>
						<th style="text-align: right;">{{ isset($data['backendlang']['backendlang']['Net_Sales_(RM)']) ? $data['backendlang']['backendlang']['Net_Sales_(RM)'] :'' }}</th>
						<th style="text-align: right;">{{ isset($data['backendlang']['backendlang']['Total_Net_Sales_(RM)']) ? $data['backendlang']['backendlang']['Total_Net_Sales_(RM)'] :'' }}</th>
						<!-- <th style="text-align: right;">Processing fee (RM)</th> -->
						<th style="text-align: right;">{{ isset($data['backendlang']['backendlang']['Shipping_Fee']) ? $data['backendlang']['backendlang']['Shipping_Fee'] :'' }} (RM)</th>
						<th style="text-align: right;">{{ isset($data['backendlang']['backendlang']['Discount']) ? $data['backendlang']['backendlang']['Discount'] :'' }} (RM)</th>
						<!-- <th style="text-align: right;">Agent Discount (RM)</th>
						<th style="text-align: right;">Tax (RM)</th> -->
						<th style="text-align: right;">{{ isset($data['backendlang']['backendlang']['Total_Sales_(RM)']) ? $data['backendlang']['backendlang']['Total_Sales_(RM)'] :'' }}</th>
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

						<td align="left" @if(count($details[$transaction->Tid]) > 1) rowspan="{{ count($details[$transaction->Tid])+1 }}" @else rowspan="2" @endif>
							{{ $key+1 }}

						</td>
						<td align="left" @if(count($details[$transaction->Tid]) > 1) rowspan="{{ count($details[$transaction->Tid])+1 }}" @else rowspan="2" @endif>
							{{ ($transaction->created_at) }}
						</td>
						<td align="left" @if(count($details[$transaction->Tid]) > 1) rowspan="{{ count($details[$transaction->Tid])+1 }}" @else rowspan="2" @endif>
							@if(empty($transaction->mall))
								{{ isset($data['backendlang']['backendlang']['Normal_Transaction']) ? $data['backendlang']['backendlang']['Normal_Transaction'] :'' }}
							@elseif($transaction->mall == 1)
								{{ isset($data['backendlang']['backendlang']['Cash_Wallet']) ? $data['backendlang']['backendlang']['Cash_Wallet'] :'' }}
							@elseif($transaction->mall == 2)
								{{ isset($data['backendlang']['backendlang']['Topup_Wallet']) ? $data['backendlang']['backendlang']['Topup_Wallet'] :'' }}
							@endif
						</td>
						<td align="left" @if(count($details[$transaction->Tid]) > 1) rowspan="{{ count($details[$transaction->Tid])+1 }}" @else rowspan="2" @endif>
							<a href="{{ route('transaction.transactions.edit', $transaction->Tid) }}">
								{{ $transaction->transaction_no }}
							</a>
						</td>
						<td align="left" @if(count($details[$transaction->Tid]) > 1) rowspan="{{ count($details[$transaction->Tid])+1 }}" @else rowspan="2" @endif>
							{{ (!empty($transaction->buyer_name)) ? $transaction->buyer_name : $transaction->address_name }}
							({{ !empty($transaction->buyer_code) ? $transaction->buyer_code : 'Guest' }})
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
						<td style="" colspan="7">
							<b>{{ isset($data['backendlang']['backendlang']['Page_Summary']) ? $data['backendlang']['backendlang']['Page_Summary'] :'' }}</b>
						</td>
						<td style=" text-align: right;">
							<b>{{ $totalQty }}
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
					<tr class="warning">
						<td style="" colspan="7">
							<b>{{ isset($data['backendlang']['backendlang']['Total_Summary']) ? $data['backendlang']['backendlang']['Total_Summary'] :'' }}</b>
						</td>
						<td style=" text-align: right;">
							<b>{{ $totalT->totalQty }}</b>
						</td>
						<td style=" text-align: right;" class="">
							<b>{{ number_format($totalT->totalUnitPrice, 2) }}</b>
						</td>
						<td style=" text-align: right;" class="">
							<b>{{ number_format($totalT->totalNet, 2) }}</b>
						</td>
						<td style=" text-align: right;" class="">
							<b>{{ number_format(($totalT->totalNet), 2) }}</b>
						</td>
						<!-- <td style=" text-align: right;" class="">
								<b>{{ number_format($totalT2->totalProcessingFee, 2) }}</b>
							</td> -->
						<td style=" text-align: right;" class="">
							<b>{{ number_format($totalT2->totalShippingFee, 2) }}</b>
						</td>

						<td style=" text-align: right;" class="">
							<b>{{ number_format($totalT2->totalDiscount, 2) }}</b>
						</td>
						<!-- <td style=" text-align: right;" class="">
								<b>{{ number_format($totalT2->totalAdDiscount, 2) }}</b>
							</td>
							<td style=" text-align: right;" class="">
								<b>{{ number_format($totalT2->totalTax, 2) }}</b>
							</td> -->
						<td style=" text-align: right;" class="">
							<b>{{ number_format($totalT->totalNet - $totalT2->totalDiscount - $totalT2->totalAdDiscount + $totalT2->totalProcessingFee + $totalT2->totalShippingFee + $totalT2->totalTax, 2) }}</b>
						</td>
					</tr>
				</tbody>
			</table>
			{{ $transactions->links() }}
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
			applyLabel: "{{ isset($data['backendlang']['backendlang']['Apply']) ? $data['backendlang']['backendlang']['Apply'] :'' }}",
			cancelLabel: "{{ isset($data['backendlang']['backendlang']['Cancel']) ? $data['backendlang']['backendlang']['Cancel'] :'' }}",
			format: 'DD/MM/YYYY',
		}
	});


	$('.grandTotal').html('{{ number_format($totalT->totalNet - $totalT2->totalDiscount - $totalT2->totalAdDiscount + $totalT2->totalProcessingFee + $totalT2->totalShippingFee + $totalT2->totalTax, 2) }}');
</script>
@endsection