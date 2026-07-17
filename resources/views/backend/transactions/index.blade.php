@extends('layouts.admin_app')

@section('content')
<div class="container-box form-group">
	<h3>{{ isset($data['backendlang']['backendlang']['Filter']) ? $data['backendlang']['backendlang']['Filter'] :'' }}</h3>
	<hr>
	<form action="{{ route('transaction.transactions.index') }}" method="GET">
	<div class="row">
		<div class="col-sm-2">
			<div class="form-group">
				<input type="text" class="form-control" name="dates" value="{{ !empty(request('dates')) ? request('dates') : $startDate.' - '.$endDate }}">
			</div>
		</div>

		<div class="col-sm-2">
			<div class="form-group">
				<input type="text" class="form-control" name="transaction_no" value="{{ !empty(request('transaction_no')) ? request('transaction_no') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Transaction_No']) ? $data['backendlang']['backendlang']['Search_Transaction_No'] :'' }}">
			</div>
		</div>

		<div class="col-sm-2">
			<div class="form-group">
				<input type="text" class="form-control" name="buyer_name" value="{{ !empty(request('buyer_name')) ? request('buyer_name') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Buyer_Name']) ? $data['backendlang']['backendlang']['Search_Buyer_Name'] :'' }}">
			</div>
		</div>

		<div class="col-sm-2">
			<div class="form-group">
				<input type="text" class="form-control" name="buyer_code" value="{{ !empty(request('buyer_code')) ? request('buyer_code') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Buyer_Code']) ? $data['backendlang']['backendlang']['Search_Buyer_Code'] :'' }}">
			</div>
		</div>

		<!-- <div class="col-sm-2">
			<div class="form-group">
				<select class="form-control" name="payment_method">
					<option value="">Select Payment Method</option>
					<option {{ (!empty(request('payment_method')) && request('payment_method') == '1') ? 'selected' : '' }} value="1">Wallet</option>
					<option {{ (!empty(request('payment_method')) && request('payment_method') == '2') ? 'selected' : '' }} value="2">Online Banking</option>
					<option {{ (!empty(request('payment_method')) && request('payment_method') == '3') ? 'selected' : '' }} value="3">Bank Transfer</option>
				</select>
			</div>
		</div> -->

		<div class="col-sm-2">
			<div class="form-group">
				<select class="form-control" name="status">
					<option value="">{{ isset($data['backendlang']['backendlang']['Select_Transaction_Status']) ? $data['backendlang']['backendlang']['Select_Transaction_Status'] :'' }}</option>
					<option {{ (!empty(request('status')) && request('status') == '1') ? 'selected' : '' }} value="1">P{{ isset($data['backendlang']['backendlang']['Paid']) ? $data['backendlang']['backendlang']['Paid'] :'' }}</option>
					<option {{ (!empty(request('status')) && request('status') == '98') ? 'selected' : '' }} value="98">{{ isset($data['backendlang']['backendlang']['Waiting_Verification']) ? $data['backendlang']['backendlang']['Waiting_Verification'] :'' }}</option>
					<option {{ (!empty(request('status')) && request('status') == '96') ? 'selected' : '' }} value="96">{{ isset($data['backendlang']['backendlang']['Rejected']) ? $data['backendlang']['backendlang']['Rejected'] :'' }}</option>
					<option {{ (!empty(request('status')) && request('status') == '99') ? 'selected' : '' }} value="99">{{ isset($data['backendlang']['backendlang']['Unpaid']) ? $data['backendlang']['backendlang']['Unpaid'] :'' }}</option>
					<option {{ (!empty(request('status')) && request('status') == '95') ? 'selected' : '' }} value="95">{{ isset($data['backendlang']['backendlang']['Cancelled']) ? $data['backendlang']['backendlang']['Cancelled'] :'' }}</option>
					<option {{ (!empty(request('status')) && request('status') == '2') ? 'selected' : '' }} value="2">{{ isset($data['backendlang']['backendlang']['To_Receive']) ? $data['backendlang']['backendlang']['To_Receive'] :'' }}</option>
					<option {{ (!empty(request('status')) && request('status') == '3') ? 'selected' : '' }} value="3">{{ isset($data['backendlang']['backendlang']['Delivered']) ? $data['backendlang']['backendlang']['Delivered'] :'' }}</option>
				</select>
			</div>
		</div>

		<div class="col-sm-2">
			<div class="form-group">
				<select class="form-control" name="delivery_type">
					<option value="">{{ isset($data['backendlang']['backendlang']['Select_Pick_Up_Or_Delivery_Status']) ? $data['backendlang']['backendlang']['Select_Pick_Up_Or_Delivery_Status'] :'' }}</option>
					<option {{ (!empty(request('delivery_type')) && request('delivery_type') == '1') ? 'selected' : '' }} value="1">{{ isset($data['backendlang']['backendlang']['Pick_Up']) ? $data['backendlang']['backendlang']['Pick_Up'] :'' }}</option>
					<option {{ (!empty(request('delivery_type')) && request('delivery_type') == '2') ? 'selected' : '' }} value="2">{{ isset($data['backendlang']['backendlang']['Delivery']) ? $data['backendlang']['backendlang']['Delivery'] :'' }}</option>
				</select>
			</div>
		</div>
		
	</div>

	<div class="form-group">
		<div class="row">
			<div class="col-sm-2">
				<div class="form-group">
					{{ isset($data['backendlang']['backendlang']['Item_Per_Page']) ? $data['backendlang']['backendlang']['Item_Per_Page'] :'' }}: <br>
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
					<a href="{{ route('transaction.transactions.index') }}" class="btn btn-warning btn-sm">
						<i class="bi bi-refresh"></i> {{ isset($data['backendlang']['backendlang']['Clear_Search']) ? $data['backendlang']['backendlang']['Clear_Search'] :'' }}
					</a>
				</div>
			</div>
		</div>
	</div>
	</form>
	@php
	$dailyDate = date('Y-m-d');
	$MonthlyDate = date('Y-m');
	$YearlyDate = date('Y');
	@endphp
	<div class="form-group">
		<span class="badge bg-info" style="font-size: 1rem; padding: 10px;">
			{{ isset($data['backendlang']['backendlang']['Date']) ? $data['backendlang']['backendlang']['Date'] :'' }}: {{ !empty(request('dates')) ? request('dates') : $startDate.' - '.$endDate }}
		</span>
		|
		<span class="badge bg-success" style="font-size: 1rem; padding: 10px;">
			{{ isset($data['backendlang']['backendlang']['Grand_Total']) ? $data['backendlang']['backendlang']['Grand_Total'] :'' }}: <span class="grandTotal"></span>
		</span>
		|
		<span class="badge bg-warning" style="font-size: 1rem; padding: 10px;">
			{{ isset($data['backendlang']['backendlang']['Net_Total']) ? $data['backendlang']['backendlang']['Net_Total'] :'' }}: <span class="netTotal"></span>
		</span>
		|
		<a href="{{ route('transaction.transactions.index', ['today='.$dailyDate]) }}" 
		   class="btn btn-outline-primary btn-sm btn-filter {{ !empty(request('today')) ? 'selected' : '' }}">
			{{ isset($data['backendlang']['backendlang']['Daily_Sales']) ? $data['backendlang']['backendlang']['Daily_Sales'] :'' }}
			<input type="hidden" name="filter_data" value="0">
			<br>
			@if(!empty(request('mall')))
				{{ number_format($dailySales->dailySales, 2) }} {{ isset($data['backendlang']['backendlang']['Point']) ? $data['backendlang']['backendlang']['Point'] :'' }}
			@else
				RM {{ number_format($dailySales->dailySales, 2) }}
			@endif
		</a>
		<a href="{{ route('transaction.transactions.index', ['this_month='.$MonthlyDate]) }}" 
		   class="btn btn-outline-primary btn-sm btn-filter {{ !empty(request('this_month')) ? 'selected' : '' }}">
			{{ isset($data['backendlang']['backendlang']['Monthly_Sales']) ? $data['backendlang']['backendlang']['Monthly_Sales'] :'' }}
			<input type="hidden" name="filter_data" value="1">
			<br>
			@if(!empty(request('mall')))
				{{ number_format($monthlySales->monthlySales, 2) }} {{ isset($data['backendlang']['backendlang']['Point']) ? $data['backendlang']['backendlang']['Point'] :'' }}
			@else
				RM {{ number_format($monthlySales->monthlySales, 2) }}
			@endif
		</a>
		<a href="{{ route('transaction.transactions.index', ['this_year='.$YearlyDate]) }}" 
		   class="btn btn-outline-primary btn-sm btn-filter {{ !empty(request('this_year')) ? 'selected' : '' }}">
			{{ isset($data['backendlang']['backendlang']['Yearly_Sales']) ? $data['backendlang']['backendlang']['Yearly_Sales'] :'' }}
			<input type="hidden" name="filter_data" value="2">
			<br>
			@if(!empty(request('mall')))
				{{ number_format($yearlySales->yearlySales, 2) }} {{ isset($data['backendlang']['backendlang']['Point']) ? $data['backendlang']['backendlang']['Point'] :'' }}
			@else
				RM {{ number_format($yearlySales->yearlySales, 2) }}
			@endif
		</a>
	</div>
	<hr>
	<div class="form-group">
		<span class="badge bg-success" style="font-size: 1rem; padding: 10px;">
			{{ isset($data['backendlang']['backendlang']['Total_Paid']) ? $data['backendlang']['backendlang']['Total_Paid'] :'' }}: <span class="grandTotal"></span>
		</span>
		|
		<span class="badge bg-warning" style="font-size: 1rem; padding: 10px;">
			{{ isset($data['backendlang']['backendlang']['Total_Unpaid']) ? $data['backendlang']['backendlang']['Total_Unpaid'] :'' }}: <span class="totalunpaid"></span>
		</span>
		|
		<span class="badge bg-danger" style="font-size: 1rem; padding: 10px;">
			{{ isset($data['backendlang']['backendlang']['Total_Cancel']) ? $data['backendlang']['backendlang']['Total_Cancel'] :'' }}: <span class="totalcancel"></span>
		</span>
	</div>
</div>

<div class="container-box form-group">
	<div class="form-group" align="right">
		<a href="{{ route('transaction.transactions.create') }}" class="btn btn-outline-primary">
			<i class="bi bi-plus"></i> {{ isset($data['backendlang']['backendlang']['Create_Transaction']) ? $data['backendlang']['backendlang']['Create_Transaction'] :'' }}
		</a>
		<a href="{{ route('exportTransaction', ['dates='.(!empty(request('dates')) ? request('dates') : $startDate.' - '.$endDate), 
											     'buyer_name='.(!empty(request('buyer_name')) ? request('buyer_name') : ''), 
											     'buyer_ic='.(!empty(request('buyer_ic')) ? request('buyer_ic') : ''),
												 'transaction_no='.(!empty(request('transaction_no')) ? request('transaction_no') : '' ),
												 'status='.(!empty(request('status')) ? request('status') : '' ),
												 'delivery_type='.(!empty(request('delivery_type')) ? request('delivery_type') : '' )])}}" target="_blank" class="btn btn-warning">
			<i class="bi bi-download"></i> {{ isset($data['backendlang']['backendlang']['Export']) ? $data['backendlang']['backendlang']['Export'] :'' }}
		</a>
	</div>
	{{ $transactions->links() }}
	<div class="row" >
		<div class="col-12" style="overflow: auto;">
			<table class="table table-bordered">
				<thead>
					<tr class="info">
						<th>#
						</th>
						<th>{{ isset($data['backendlang']['backendlang']['Date']) ? $data['backendlang']['backendlang']['Date'] :'' }}&nbsp;&nbsp;&nbsp;
							@if(empty(request('desc_sort')) && empty(request('asc_sort')))
								<a href="{{ route('transaction.transactions.index', ['desc_sort=DESC', 'dates='.(!empty(request('dates')) ? request('dates') : $startDate.' - '.$endDate), 
												     'buyer_name='.(!empty(request('buyer_name')) ? request('buyer_name') : ''), 
												     'buyer_ic='.(!empty(request('buyer_ic')) ? request('buyer_ic') : ''),
													 'transaction_no='.(!empty(request('transaction_no')) ? request('transaction_no') : '' ),
													 'status='.(!empty(request('status')) ? request('status') : '' ),
													 'delivery_type='.(!empty(request('delivery_type')) ? request('delivery_type') : '' )]) }}" 
								   class="{{ !empty(request('desc_sort')) ? 'selected' : '' }}">
									<i class="bi bi-sort-down"></i>
									<input type="hidden" name="sort_data" value="0">
								</a>
							@else
								@if(!empty(request('desc_sort')))
									<a href="{{ route('transaction.transactions.index', ['asc_sort=ASC', 'dates='.(!empty(request('dates')) ? request('dates') : $startDate.' - '.$endDate), 
													     'buyer_name='.(!empty(request('buyer_name')) ? request('buyer_name') : ''), 
													     'buyer_ic='.(!empty(request('buyer_ic')) ? request('buyer_ic') : ''),
														 'transaction_no='.(!empty(request('transaction_no')) ? request('transaction_no') : '' ),
														 'status='.(!empty(request('status')) ? request('status') : '' ),
														 'delivery_type='.(!empty(request('delivery_type')) ? request('delivery_type') : '' )]) }}" 
									   class="{{ !empty(request('asc_sort')) ? 'selected' : '' }}">
										<i class="bi bi-sort-down"></i>
										<input type="hidden" name="sort_data" value="1">
									</a>
								@elseif(!empty(request('asc_sort')))
										<a href="{{ route('transaction.transactions.index', ['desc_sort=DESC', 'dates='.(!empty(request('dates')) ? request('dates') : $startDate.' - '.$endDate), 
													     'buyer_name='.(!empty(request('buyer_name')) ? request('buyer_name') : ''), 
													     'buyer_ic='.(!empty(request('buyer_ic')) ? request('buyer_ic') : ''),
														 'transaction_no='.(!empty(request('transaction_no')) ? request('transaction_no') : '' ),
														 'status='.(!empty(request('status')) ? request('status') : '' ),
														 'delivery_type='.(!empty(request('delivery_type')) ? request('delivery_type') : '' )]) }}" 
									   class="{{ !empty(request('desc_sort')) ? 'selected' : '' }}">
										<i class="bi bi-sort-up"></i>
										<input type="hidden" name="sort_data" value="0">
									</a>
								@endif
							@endif
						</th>
						<th>{{ isset($data['backendlang']['backendlang']['Transaction_No']) ? $data['backendlang']['backendlang']['Transaction_No'] :'' }}&nbsp;&nbsp;&nbsp;
							@if(empty(request('trans_desc')) && empty(request('trans_asc')))
								<a href="{{ route('transaction.transactions.index', ['trans_desc=DESC', 'dates='.(!empty(request('dates')) ? request('dates') : $startDate.' - '.$endDate), 
												     'buyer_name='.(!empty(request('buyer_name')) ? request('buyer_name') : ''), 
												     'buyer_ic='.(!empty(request('buyer_ic')) ? request('buyer_ic') : ''),
													 'transaction_no='.(!empty(request('transaction_no')) ? request('transaction_no') : '' ),
													 'status='.(!empty(request('status')) ? request('status') : '' ),
													 'delivery_type='.(!empty(request('delivery_type')) ? request('delivery_type') : '' )]) }}" 
								   class="{{ !empty(request('trans_desc')) ? 'selected' : '' }}">
									<i class="bi bi-sort-down"></i>
									<input type="hidden" name="sort_data" value="0">
								</a>
							@else
								@if(!empty(request('trans_desc')))
									<a href="{{ route('transaction.transactions.index', ['trans_asc=ASC', 'dates='.(!empty(request('dates')) ? request('dates') : $startDate.' - '.$endDate), 
													     'buyer_name='.(!empty(request('buyer_name')) ? request('buyer_name') : ''), 
													     'buyer_ic='.(!empty(request('buyer_ic')) ? request('buyer_ic') : ''),
														 'transaction_no='.(!empty(request('transaction_no')) ? request('transaction_no') : '' ),
														 'status='.(!empty(request('status')) ? request('status') : '' ),
														 'delivery_type='.(!empty(request('delivery_type')) ? request('delivery_type') : '' )]) }}" 
									   class="{{ !empty(request('trans_asc')) ? 'selected' : '' }}">
										<i class="bi bi-sort-down"></i>
										<input type="hidden" name="sort_data" value="1">
									</a>
								@elseif(!empty(request('trans_asc')))
										<a href="{{ route('transaction.transactions.index', ['trans_desc=DESC', 'dates='.(!empty(request('dates')) ? request('dates') : $startDate.' - '.$endDate), 
													     'buyer_name='.(!empty(request('buyer_name')) ? request('buyer_name') : ''), 
													     'buyer_ic='.(!empty(request('buyer_ic')) ? request('buyer_ic') : ''),
														 'transaction_no='.(!empty(request('transaction_no')) ? request('transaction_no') : '' ),
														 'status='.(!empty(request('status')) ? request('status') : '' ),
														 'delivery_type='.(!empty(request('delivery_type')) ? request('delivery_type') : '' )]) }}" 
									   class="{{ !empty(request('trans_desc')) ? 'selected' : '' }}">
										<i class="bi bi-sort-up"></i>
										<input type="hidden" name="sort_data" value="0">
									</a>
								@endif
							@endif
						</th>
						<th>{{ isset($data['backendlang']['backendlang']['Buyer_Name']) ? $data['backendlang']['backendlang']['Buyer_Name'] :'' }}&nbsp;&nbsp;&nbsp;
							@if(empty(request('name_desc')) && empty(request('name_asc')))
								<a href="{{ route('transaction.transactions.index', ['name_desc=DESC', 'dates='.(!empty(request('dates')) ? request('dates') : $startDate.' - '.$endDate), 
												     'buyer_name='.(!empty(request('buyer_name')) ? request('buyer_name') : ''), 
												     'buyer_ic='.(!empty(request('buyer_ic')) ? request('buyer_ic') : ''),
													 'transaction_no='.(!empty(request('transaction_no')) ? request('transaction_no') : '' ),
													 'status='.(!empty(request('status')) ? request('status') : '' ),
													 'delivery_type='.(!empty(request('delivery_type')) ? request('delivery_type') : '' )]) }}" 
								   class="{{ !empty(request('name_desc')) ? 'selected' : '' }}">
									<i class="bi bi-sort-down"></i>
									<input type="hidden" name="sort_data" value="0">
								</a>
							@else
								@if(!empty(request('name_desc')))
									<a href="{{ route('transaction.transactions.index', ['name_asc=ASC', 'dates='.(!empty(request('dates')) ? request('dates') : $startDate.' - '.$endDate), 
													     'buyer_name='.(!empty(request('buyer_name')) ? request('buyer_name') : ''), 
													     'buyer_ic='.(!empty(request('buyer_ic')) ? request('buyer_ic') : ''),
														 'transaction_no='.(!empty(request('transaction_no')) ? request('transaction_no') : '' ),
														 'status='.(!empty(request('status')) ? request('status') : '' ),
														 'delivery_type='.(!empty(request('delivery_type')) ? request('delivery_type') : '' )]) }}" 
									   class="{{ !empty(request('name_asc')) ? 'selected' : '' }}">
										<i class="bi bi-sort-down"></i>
										<input type="hidden" name="sort_data" value="1">
									</a>
								@elseif(!empty(request('name_asc')))
										<a href="{{ route('transaction.transactions.index', ['name_desc=DESC', 'dates='.(!empty(request('dates')) ? request('dates') : $startDate.' - '.$endDate), 
													     'buyer_name='.(!empty(request('buyer_name')) ? request('buyer_name') : ''), 
													     'buyer_ic='.(!empty(request('buyer_ic')) ? request('buyer_ic') : ''),
														 'transaction_no='.(!empty(request('transaction_no')) ? request('transaction_no') : '' ),
														 'status='.(!empty(request('status')) ? request('status') : '' ),
														 'delivery_type='.(!empty(request('delivery_type')) ? request('delivery_type') : '' )]) }}" 
									   class="{{ !empty(request('name_desc')) ? 'selected' : '' }}">
										<i class="bi bi-sort-up"></i>
										<input type="hidden" name="sort_data" value="0">
									</a>
								@endif
							@endif
						</th>
						<th>{{ isset($data['backendlang']['backendlang']['Total_Amount']) ? $data['backendlang']['backendlang']['Total_Amount'] :'' }} ({{ !empty(request('mall')) ? (isset($data['backendlang']['backendlang']['Point']) ? $data['backendlang']['backendlang']['Point'] : '') : 'RM' }})&nbsp;&nbsp;&nbsp;
							@if(empty(request('grand_total_desc')) && empty(request('grand_total_asc')))
								<a href="{{ route('transaction.transactions.index', ['grand_total_desc=DESC', 'dates='.(!empty(request('dates')) ? request('dates') : $startDate.' - '.$endDate), 
												     'buyer_name='.(!empty(request('buyer_name')) ? request('buyer_name') : ''), 
												     'buyer_ic='.(!empty(request('buyer_ic')) ? request('buyer_ic') : ''),
													 'transaction_no='.(!empty(request('transaction_no')) ? request('transaction_no') : '' ),
													 'status='.(!empty(request('status')) ? request('status') : '' ),
													 'delivery_type='.(!empty(request('delivery_type')) ? request('delivery_type') : '' )]) }}" 
								   class="{{ !empty(request('grand_total_desc')) ? 'selected' : '' }}">
									<i class="bi bi-sort-down"></i>
									<input type="hidden" name="sort_data" value="0">
								</a>
							@else
								@if(!empty(request('grand_total_desc')))
									<a href="{{ route('transaction.transactions.index', ['grand_total_asc=ASC', 'dates='.(!empty(request('dates')) ? request('dates') : $startDate.' - '.$endDate), 
													     'buyer_name='.(!empty(request('buyer_name')) ? request('buyer_name') : ''), 
													     'buyer_ic='.(!empty(request('buyer_ic')) ? request('buyer_ic') : ''),
														 'transaction_no='.(!empty(request('transaction_no')) ? request('transaction_no') : '' ),
														 'status='.(!empty(request('status')) ? request('status') : '' ),
														 'delivery_type='.(!empty(request('delivery_type')) ? request('delivery_type') : '' )]) }}" 
									   class="{{ !empty(request('grand_total_asc')) ? 'selected' : '' }}">
										<i class="bi bi-sort-down"></i>
										<input type="hidden" name="sort_data" value="1">
									</a>
								@elseif(!empty(request('grand_total_asc')))
									<a href="{{ route('transaction.transactions.index', ['grand_total_desc=DESC', 'dates='.(!empty(request('dates')) ? request('dates') : $startDate.' - '.$endDate), 
													     'buyer_name='.(!empty(request('buyer_name')) ? request('buyer_name') : ''), 
													     'buyer_ic='.(!empty(request('buyer_ic')) ? request('buyer_ic') : ''),
														 'transaction_no='.(!empty(request('transaction_no')) ? request('transaction_no') : '' ),
														 'status='.(!empty(request('status')) ? request('status') : '' ),
														 'delivery_type='.(!empty(request('delivery_type')) ? request('delivery_type') : '' )]) }}" 
									   class="{{ !empty(request('grand_total_desc')) ? 'selected' : '' }}">
										<i class="bi bi-sort-up"></i>
										<input type="hidden" name="sort_data" value="0">
									</a>
								@endif
							@endif
						</th>
						<th>{{ isset($data['backendlang']['backendlang']['Status']) ? $data['backendlang']['backendlang']['Status'] :'' }}&nbsp;&nbsp;&nbsp;
							@if(empty(request('status_desc')) && empty(request('status_asc')))
								<a href="{{ route('transaction.transactions.index', ['status_desc=DESC', 'dates='.(!empty(request('dates')) ? request('dates') : $startDate.' - '.$endDate), 
												     'buyer_name='.(!empty(request('buyer_name')) ? request('buyer_name') : ''), 
												     'buyer_ic='.(!empty(request('buyer_ic')) ? request('buyer_ic') : ''),
													 'transaction_no='.(!empty(request('transaction_no')) ? request('transaction_no') : '' ),
													 'status='.(!empty(request('status')) ? request('status') : '' ),
													 'delivery_type='.(!empty(request('delivery_type')) ? request('delivery_type') : '' )]) }}" 
								   class="{{ !empty(request('status_desc')) ? 'selected' : '' }}">
									<i class="bi bi-sort-down"></i>
									<input type="hidden" name="sort_data" value="0">
								</a>
							@else
								@if(!empty(request('status_desc')))
									<a href="{{ route('transaction.transactions.index', ['status_asc=ASC', 'dates='.(!empty(request('dates')) ? request('dates') : $startDate.' - '.$endDate), 
													     'buyer_name='.(!empty(request('buyer_name')) ? request('buyer_name') : ''), 
													     'buyer_ic='.(!empty(request('buyer_ic')) ? request('buyer_ic') : ''),
														 'transaction_no='.(!empty(request('transaction_no')) ? request('transaction_no') : '' ),
														 'status='.(!empty(request('status')) ? request('status') : '' ),
														 'delivery_type='.(!empty(request('delivery_type')) ? request('delivery_type') : '' )]) }}" 
									   class="{{ !empty(request('status_asc')) ? 'selected' : '' }}">
										<i class="bi bi-sort-down"></i>
										<input type="hidden" name="sort_data" value="1">
									</a>
								@elseif(!empty(request('status_asc')))
									<a href="{{ route('transaction.transactions.index', ['status_desc=DESC', 'dates='.(!empty(request('dates')) ? request('dates') : $startDate.' - '.$endDate), 
													     'buyer_name='.(!empty(request('buyer_name')) ? request('buyer_name') : ''), 
													     'buyer_ic='.(!empty(request('buyer_ic')) ? request('buyer_ic') : ''),
														 'transaction_no='.(!empty(request('transaction_no')) ? request('transaction_no') : '' ),
														 'status='.(!empty(request('status')) ? request('status') : '' ),
														 'delivery_type='.(!empty(request('delivery_type')) ? request('delivery_type') : '' )]) }}" 
									   class="{{ !empty(request('status_desc')) ? 'selected' : '' }}">
										<i class="bi bi-sort-up"></i>
										<input type="hidden" name="sort_data" value="0">
									</a>
								@endif
							@endif
						</th>
						<th>{{ isset($data['backendlang']['backendlang']['Pick_Up']) ? $data['backendlang']['backendlang']['Pick_Up'] :'' }}&nbsp;&nbsp;&nbsp;
							@if(empty(request('pickup_desc')) && empty(request('pickup_asc')))
								<a href="{{ route('transaction.transactions.index', ['pickup_desc=DESC', 'dates='.(!empty(request('dates')) ? request('dates') : $startDate.' - '.$endDate), 
												     'buyer_name='.(!empty(request('buyer_name')) ? request('buyer_name') : ''), 
												     'buyer_ic='.(!empty(request('buyer_ic')) ? request('buyer_ic') : ''),
													 'transaction_no='.(!empty(request('transaction_no')) ? request('transaction_no') : '' ),
													 'status='.(!empty(request('status')) ? request('status') : '' ),
													 'delivery_type='.(!empty(request('delivery_type')) ? request('delivery_type') : '' )]) }}" 
								   class="{{ !empty(request('pickup_desc')) ? 'selected' : '' }}">
									<i class="bi bi-sort-down"></i>
									<input type="hidden" name="sort_data" value="0">
								</a>
							@else
								@if(!empty(request('pickup_desc')))
									<a href="{{ route('transaction.transactions.index', ['pickup_asc=ASC', 'dates='.(!empty(request('dates')) ? request('dates') : $startDate.' - '.$endDate), 
													     'buyer_name='.(!empty(request('buyer_name')) ? request('buyer_name') : ''), 
													     'buyer_ic='.(!empty(request('buyer_ic')) ? request('buyer_ic') : ''),
														 'transaction_no='.(!empty(request('transaction_no')) ? request('transaction_no') : '' ),
														 'status='.(!empty(request('status')) ? request('status') : '' ),
														 'delivery_type='.(!empty(request('delivery_type')) ? request('delivery_type') : '' )]) }}" 
									   class="{{ !empty(request('pickup_asc')) ? 'selected' : '' }}">
										<i class="bi bi-sort-down"></i>
										<input type="hidden" name="sort_data" value="1">
									</a>
								@elseif(!empty(request('pickup_asc')))
									<a href="{{ route('transaction.transactions.index', ['pickup_desc=DESC', 'dates='.(!empty(request('dates')) ? request('dates') : $startDate.' - '.$endDate), 
													     'buyer_name='.(!empty(request('buyer_name')) ? request('buyer_name') : ''), 
													     'buyer_ic='.(!empty(request('buyer_ic')) ? request('buyer_ic') : ''),
														 'transaction_no='.(!empty(request('transaction_no')) ? request('transaction_no') : '' ),
														 'status='.(!empty(request('status')) ? request('status') : '' ),
														 'delivery_type='.(!empty(request('delivery_type')) ? request('delivery_type') : '' )]) }}" 
									   class="{{ !empty(request('pickup_desc')) ? 'selected' : '' }}">
										<i class="bi bi-sort-up"></i>
										<input type="hidden" name="sort_data" value="0">
									</a>
								@endif
							@endif
						</th>
						<th>{{ isset($data['backendlang']['backendlang']['courier_service']) ? $data['backendlang']['backendlang']['courier_service'] :'' }}</th>
						<th></th>
						<!-- <th>Action</th> -->
					</tr>
				</thead>
				<tbody>
					@php
					$totalTransaction = 0;
					$totalUnTransaction = 0;
					$totalCancelTransaction = 0;
					@endphp
					@if(!$transactions->isEmpty())
					@foreach($transactions as $key => $transaction)
					<tr>
						<td>
							{{ $key+1 }}
							<input type="hidden" name="tid" class="tid" value="{{ $transaction->id }}">
						</td>
						<td>{{ ($transaction->created_at) }}</td>
						<td>{{ $transaction->transaction_no }}</td>
						<td>
							{{ !empty($transaction->customer_name) ? $transaction->customer_name : $transaction->address_name }} 
							({{ !empty($transaction->customer_code) ? $transaction->customer_code : 'Guest' }})
						</td>
						<td>
							{{ number_format($transaction->grand_total, 2) }}
						</td>
						<td>
							@if($transaction->status == 99)
								<span class="badge bg-warning">{{ isset($data['backendlang']['backendlang']['Unpaid']) ? $data['backendlang']['backendlang']['Unpaid'] :'' }}</span>
							@elseif($transaction->status == 98)
								<span class="badge bg-info">{{ isset($data['backendlang']['backendlang']['Waiting_Verification']) ? $data['backendlang']['backendlang']['Waiting_Verification'] :'' }}</span>
							@elseif($transaction->status == 97)
								<span class="badge bg-info">{{ isset($data['backendlang']['backendlang']['In-Progress']) ? $data['backendlang']['backendlang']['In-Progress'] :'' }}</span>
							@elseif($transaction->status == '96')
								<span class="badge bg-danger">{{ isset($data['backendlang']['backendlang']['Rejected']) ? $data['backendlang']['backendlang']['Rejected'] :'' }}</span>
							@elseif($transaction->status == 1)
								@if($transaction->completed == 1)
									<span class="badge bg-success">{{ isset($data['backendlang']['backendlang']['Delivered']) ? $data['backendlang']['backendlang']['Delivered'] :'' }}</span>
								@elseif($transaction->completed != 1 && $transaction->to_receive)
									<span class="badge bg-success">{{ isset($data['backendlang']['backendlang']['To_Receive']) ? $data['backendlang']['backendlang']['To_Receive'] :'' }}</span>
								@else
									<span class="badge bg-success">{{ isset($data['backendlang']['backendlang']['Paid']) ? $data['backendlang']['backendlang']['Paid'] :'' }}</span>
								@endif
							@else
								<span class="badge bg-danger">
									{{ isset($data['backendlang']['backendlang']['Cancelled']) ? $data['backendlang']['backendlang']['Cancelled'] :'' }}
								</span>
								
								@if(!empty($transaction->cancelled_name))
								<br>
								{{ isset($data['backendlang']['backendlang']['Cancelled_By']) ? $data['backendlang']['backendlang']['Cancelled_By'] :'' }}: {{ $transaction->cancelled_name }} 
								@endif
							@endif
						</td>
						<td>
							@if(!empty($transaction->on_hold))
								@if($transaction->on_hold == 99)
									<span class="badge bg-info">{{ isset($data['backendlang']['backendlang']['Awaiting_Pickup']) ? $data['backendlang']['backendlang']['Awaiting_Pickup'] :'' }}</span>
								@elseif($transaction->on_hold == 1)
									<span class="badge bg-success">{{ isset($data['backendlang']['backendlang']['Collected']) ? $data['backendlang']['backendlang']['Collected'] :'' }}</span>
								@endif
							@else
								<span class="badge bg-danger">{{ isset($data['backendlang']['backendlang']['No_Pickup']) ? $data['backendlang']['backendlang']['No_Pickup'] :'' }}</span>
							@endif
						</td>
						<td align="center" width="10%">
							@if($transaction->status != '95' && $transaction->status != '96' && empty($transaction->cod_address))
								@if(!empty($transaction->awb_no) && empty($transaction->tracking_no))
									<a href="#" class="add-new-awb-no" data-toggle="modal" data-target="#myModal" data-id="{{ $transaction->awb_no }}" data-name="{{ $transaction->courier }}">
										{{ $transaction->courier }}
										<br>
										{{ $transaction->awb_no }}
									</a>
								@else
									<button type="button" class="btn btn-primary btn-sm add-new-awb-no" data-toggle="modal" data-target="#myModal" data-id="">
										{{ isset($data['backendlang']['backendlang']['Add_Awb_No']) ? $data['backendlang']['backendlang']['Add_Awb_No'] :'' }}
									</button>						
								@endif
							@else
								@if(!empty($transaction->self_pick))
									<i class="bi bi-minus">{{ isset($data['backendlang']['backendlang']['Self_Pick_Up']) ? $data['backendlang']['backendlang']['Self_Pick_Up'] :'' }}</i>
								@else
									<i class="bi bi-minus"></i>
								@endif
							@endif
						</td>
						<td>
							@php
								if(!empty(request('mall'))){
									$mall = 'mall=1';
								}else{
									$mall = NULL;
								}
							@endphp
							@if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['transaction-edit']))
								<a href="{{ route('transaction.transactions.edit', [$transaction->id, $mall]) }}" class="btn btn-outline-primary btn-sm" title="{{ isset($data['backendlang']['backendlang']['View']) ? $data['backendlang']['backendlang']['View'] :'' }}">
									<i class="bi bi-eye"></i>
								</a>
								@if($transaction->status == '1' && $transaction->completed != '1')
								<div class="btn-group">
								  	<button type="button" class="btn btn-outline-danger btn-sm  dropdown-toggle" data-toggle="dropdown"
								  			aria-expanded="false">
								    <i class="bi bi-justify"></i> <span class="caret"></span>
								  	</button>
								  	@if($transaction->completed != '1' && $transaction->to_receive != '1')
								  		<ul class="dropdown-menu" role="menu">
										  	<li><a href="#" class="change_action" data-id="12">{{ isset($data['backendlang']['backendlang']['To_Receive']) ? $data['backendlang']['backendlang']['To_Receive'] :'' }}</a></li>
										</ul>
								  	@else
										<ul class="dropdown-menu" role="menu">
										  	<li><a href="#" class="change_action" data-id="11">{{ isset($data['backendlang']['backendlang']['Completed']) ? $data['backendlang']['backendlang']['Completed'] :'' }}</a></li>
										</ul>
								  	@endif
								</div>
								@endif
								@if($transaction->status == '98')
								<div class="btn-group">
								  	<button type="button" class="btn btn-outline-warning btn-sm  dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
								    	<i class="bi bi-justify"></i> <span class="caret"></span>
								  	</button>

								  	<ul class="dropdown-menu" role="menu">
								    	<li>
								    		<a href="#" class="change_action" data-id="1">
								    			{{ isset($data['backendlang']['backendlang']['Approve']) ? $data['backendlang']['backendlang']['Approve'] :'' }}
								    		</a>
								    	</li>
								    	<li>
								    		<a href="#" class="change_action" data-id="96">
								    			{{ isset($data['backendlang']['backendlang']['Reject']) ? $data['backendlang']['backendlang']['Reject'] :'' }}
								    		</a>
								    	</li>
									</ul>
								</div>
								@endif
							@endif


							@if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['transaction-delete']))
								<a href="#" class="btn btn-outline-danger btn-sm change_action important-text" data-id="95" title="{{ isset($data['backendlang']['backendlang']['Cancel']) ? $data['backendlang']['backendlang']['Cancel'] :'' }}">
									<i class='bi bi-x-circle'></i>
								</a>
							@endif

							@if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['transaction-edit']))
								@if($transaction->status == '1')
								<a href="{{ route('transaction_invoice', $transaction->transaction_no) }}" target="_blank" class="btn btn-outline-success btn-sm" title="{{ isset($data['backendlang']['backendlang']['Print']) ? $data['backendlang']['backendlang']['Print'] :'' }}">
									<i class='bi bi-printer'></i> 
								</a>
								@endif

								@if($transaction->status == '1')
								<a href="{{ route('download_invoice', $transaction->transaction_no) }}" class="btn btn-outline-info btn-sm" title="{{ isset($data['backendlang']['backendlang']['Download_Invoice']) ? $data['backendlang']['backendlang']['Download_Invoice'] :'' }}">
									<i class="bi bi-download"></i>
								</a>
								@endif

								@if($transaction->status != '95' && $transaction->status != '96' && $transaction->cod_address != 1)
									@if(!empty($ship_details[$transaction->id][0]))
									<br>
										&nbsp;&nbsp;
										<a class="display_tracking_no" data-id="{{ $transaction->id }}" style="cursor: pointer;" title="{{ isset($data['backendlang']['backendlang']['Display_Tracking_No']) ? $data['backendlang']['backendlang']['Display_Tracking_No'] :'' }}">
											<i class="bi bi-view-stacked"></i>
										</a>
									@endif
								@endif
							@endif
						</td>
					</tr>
					
					@php
					if($transaction->status == '1'){
						$totalTransaction += $transaction->grand_total;
					}

					if($transaction->status == '99'){
						$totalUnTransaction += $transaction->grand_total;
					}

					if($transaction->status == '95'){
						$totalCancelTransaction += $transaction->grand_total;
					}
					@endphp
					@endforeach
					@else
					<tr>
						<td colspan="15">{{ isset($data['backendlang']['backendlang']['No_Result_Found']) ? $data['backendlang']['backendlang']['No_Result_Found'] :'' }}</td>
					</tr>
					@endif
				</tbody>
			</table>
		</div>
	</div>
	{{ $transactions->links() }}
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog bs-example-modal-sm" role="document">
    <div class="modal-content">
      <form method="POST" action="{{ route('add_awb_no') }}">
      @csrf
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title awb-title" id="myModalLabel">{{ isset($data['backendlang']['backendlang']['Add_Awb_No']) ? $data['backendlang']['backendlang']['Add_Awb_No'] :'' }}</h4>
	      </div>
	      <div class="modal-body">
	      	<input type="hidden" class="transaction_id" name="transaction_id">
	        <input type="text" class="form-control" name="awb_no" placeholder="{{ isset($data['backendlang']['backendlang']['Awb_No']) ? $data['backendlang']['backendlang']['Awb_No'] :'' }}">
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-outline-danger" data-dismiss="modal">{{ isset($data['backendlang']['backendlang']['Close']) ? $data['backendlang']['backendlang']['Close'] :'' }}</button>
	        <button type="submit" class="btn btn-outline-primary">{{ isset($data['backendlang']['backendlang']['Save_Changes']) ? $data['backendlang']['backendlang']['Save_Changes'] :'' }}</button>
	      </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="courier_service" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modaltracking_no" style="background: #fff;">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">{{ isset($data['backendlang']['backendlang']['We_Are_Looking_For_A_Courier_Service_For_You']) ? $data['backendlang']['backendlang']['We_Are_Looking_For_A_Courier_Service_For_You'] :'' }}...</h4>
      </div>
      <div class="modal-body">
      		<form method="POST" action="" class="courier_service_form">
      			@csrf
        		<div class="courier_service_list" style="overflow: auto;"></div>      			
      		</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-danger" data-dismiss="modal">{{ isset($data['backendlang']['backendlang']['Cancel']) ? $data['backendlang']['backendlang']['Cancel'] :'' }}</button>
        <button type="button" class="btn btn-outline-primary submit-service-id">{{ isset($data['backendlang']['backendlang']['Submit']) ? $data['backendlang']['backendlang']['Submit'] :'' }}</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('js')
<script type="text/javascript">
	$('input[name=dates]').daterangepicker({
		'applyClass' : 'btn-sm btn-success',
		'cancelClass' : 'btn-sm btn-default',
		locale: {
			applyLabel: "{{ isset($data['backendlang']['backendlang']['Apply']) ? $data['backendlang']['backendlang']['Apply'] :'' }}",
			cancelLabel: "{{ isset($data['backendlang']['backendlang']['Cancel']) ? $data['backendlang']['backendlang']['Cancel'] :'' }}",
			format: 'DD/MM/YYYY',
		}
	});

	$('.change_action').click( function(){

		$('.loading-gif').show();

		var ele = $(this);
		var action_id = $(this).data('id');
		var tid = $(this).closest('tr').find('input[name="tid"]').val();
		var fd = new FormData();
		fd.append('action_id', action_id);
		fd.append('tid', tid);
		if(action_id == '1'){
			var confirmMessage = confirm("{{ isset($data['backendlang']['backendlang']['Approve_This_Transaction']) ? $data['backendlang']['backendlang']['Approve_This_Transaction'] :'' }}");
		}else if(action_id == '95'){
			var confirmMessage = confirm("{{ isset($data['backendlang']['backendlang']['Cancel_This_Transaction']) ? $data['backendlang']['backendlang']['Cancel_This_Transaction'] :'' }} ");
		}else if(action_id == '96'){
			var confirmMessage = confirm("{{ isset($data['backendlang']['backendlang']['Reject_This_Transaction']) ? $data['backendlang']['backendlang']['Reject_This_Transaction'] :'' }}");
		}else if(action_id == '11'){
			var confirmMessage = confirm("{{ isset($data['backendlang']['backendlang']['Delivered?']) ? $data['backendlang']['backendlang']['Delivered?'] :'' }}");
		}else if(action_id == '12'){
			var confirmMessage = confirm("{{ isset($data['backendlang']['backendlang']['To_Receive?']) ? $data['backendlang']['backendlang']['To_Receive?'] :'' }}");
		}


		if(confirmMessage == true){
			$.ajax({
		       url: '{{ route("change_transaction_action") }}',
		       type: 'post',
		       data: fd,
		       contentType: false,
		       processData: false,
		       success: function(response){
		       		$('.loading-gif').hide();
		       		if(response == 'ok'){
		       			toastr.success("{{ isset($data['backendlang']['backendlang']['Update_Successful']) ? $data['backendlang']['backendlang']['Update_Successful'] :'' }}");
		       			location.reload();
		       		}else{
		       			toastr.error(response);
		       		}
		       },
		    });			
		}else{
			$('.loading-gif').hide();
		}
	});

	$('.courier_service_selection').click( function(e){
		$('.loading-gif').show();
		$('.courier_service_list').html(" ");
		var ele = $(this);

		var action_id = ele.data('id');
		var tid = $(this).closest('tr').find('.tid').val();
		var fd = new FormData();
			fd.append('action_id', action_id);
			fd.append('tid', tid);
			fd.append('weight', ele.data('weight'));
			fd.append('row', ele.data('row'));

		$.ajax({
		       url: '{{ route("courier_service_list") }}',
		       type: 'post',
		       data: fd,
		       contentType: false,
		       processData: false,
		       success: function(response){
		       		
		       		if(response == 'pick uptracking_no error'){
		       			$('.courier_service_list').html("{{ isset($data['backendlang']['backendlang']['Need_Fill_Pickup_Address']) ? $data['backendlang']['backendlang']['Need_Fill_Pickup_Address'] :'' }} <b>{{ isset($data['backendlang']['backendlang']['Setting']) ? $data['backendlang']['backendlang']['Setting'] :'' }} -> {{ isset($data['backendlang']['backendlang']['Setting_Pickup_Address']) ? $data['backendlang']['backendlang']['Setting_Pickup_Address'] :'' }}</b> {{ isset($data['backendlang']['backendlang']['Fill_In_Your_Information']) ? $data['backendlang']['backendlang']['Fill_In_Your_Information'] :'' }}");
		       		}else{
		       			$('.modal-title').html("{{ isset($data['backendlang']['backendlang']['Courier_Service_Found_For_You']) ? $data['backendlang']['backendlang']['Courier_Service_Found_For_You'] :'' }}");
		       			$('.courier_service_list').html(response);
		       		}

		       		$('select[name="drop_off_point"]').change( function(){
		       			var ele_select = $(this);

		       			if(ele_select.val()){
			       			var detail = ele_select.val().split(',');
			       			ele_select.closest('tr').find('.dropoff_details').html('<i class="bi bi-home"></i> '+detail[1]);
		       			}else{
		       				ele_select.closest('tr').find('.dropoff_details').html(' ');
		       			}
		       		});

		       		$('.loading-gif').hide();
		       },
		});
	});

	$('.display_tracking_no').click( function(e){
		var ele = $(this);
		var tid = ele.data('id');

		var fd = new FormData();
				fd.append('tid', tid);

		$.ajax({
		       url: '{{ route("get_tracking_number") }}',
		       type: 'post',
		       data: fd,
		       contentType: false,
		       processData: false,
		       success: function(response){
		       		alert(response);
		       		
		       },
			});
	})

	$('.add-new-awb-no').click( function(e){
		var ele = $(this);

		var tid = ele.closest('tr').find('.tid').val();
		var awb_no = ele.data('id');

		$('.transaction_id').val(tid);
		$('input[name="awb_no"]').val(awb_no);
		if(awb_no != ''){
			$('.awb-title').html("{{ isset($data['backendlang']['backendlang']['Edit_Awb_No']) ? $data['backendlang']['backendlang']['Edit_Awb_No'] :'' }}");
		}else{
			$('.awb-title').html("{{ isset($data['backendlang']['backendlang']['Add_Awb_No']) ? $data['backendlang']['backendlang']['Add_Awb_No'] :'' }}");
		}
	});

	$('.submit-service-id').click( function(e){
		$('.loading-gif').show();
		var tid = $('input[name="service_id"]:checked').closest('tr').find('input[name="tid"]').val();
		// var collect_date = $('.courier_service_list').find('input[name="collect_date"]').val();
		var collect_date = $('input[name="service_id"]:checked').closest('tr').find('input[name="collect_date"]').val();
		var courier_logo = $('input[name="service_id"]:checked').closest('tr').find('input[name="courier_logo"]').val();
		var sid = $('.courier_service_list').find('input[name="service_id"]:checked').val();
		var bid = $('input[name="service_id"]:checked').closest('tr').next('tr').find('select[name="drop_off_point"]').val();
		var type = $('input[name="service_id"]:checked').closest('tr').find('.service_detail').html();
		var rowid = $('.courier_service_list').find('input[name="rowid"]').val();
		var weight = $('.courier_service_list').find('input[name="Inweight"]').val();
		
		if(tid && sid){
			if(type == 'dropoff' && !bid){
				alert('您需要选择下车地点');
				$('.loading-gif').hide();
			}

			var fd = new FormData();
				fd.append('tid', tid);
				fd.append('sid', sid);
				fd.append('collect_date', collect_date);
				fd.append('courier_logo', courier_logo);
				fd.append('rowid', rowid);
				fd.append('weight', weight);

			$.ajax({
		       url: '{{ route("courier_make_order") }}',
		       type: 'post',
		       data: fd,
		       contentType: false,
		       processData: false,
		       success: function(response){
		       		if(response == 1){
		       			alert('成功');
		       			window.location.href = "{{ route('transaction.transactions.index') }}";
		       		}else if(response == 2){
		       			alert("{{ isset($data['backendlang']['backendlang']['Insufficient_credit']) ? $data['backendlang']['backendlang']['Insufficient_credit'] :'' }}");
		       		}else{
		       			alert('错误! 请联络制作团队');
		       		}
		       		$('.loading-gif').hide();
		       		
		       },
			});

		}else{
			alert('请选择您要的快递服务');
			$('.loading-gif').hide();
		}
	});
</script>

<script type="text/javascript">

	$('.netTotal').html('{{ number_format($netTransaction->netTotal, 2) }}');
	$('.grandTotal').html('{{ number_format($totalTransaction, 2) }}');
	$('.totalunpaid').html('{{ number_format($totalUnTransaction, 2) }}');
	$('.totalcancel').html('{{ number_format($totalCancelTransaction, 2) }}');
</script>
@endsection