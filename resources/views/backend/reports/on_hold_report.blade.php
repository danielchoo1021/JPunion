@extends('layouts.admin_app')

@section('content')
<div class="page-header">
    <h1>
        {{ isset($data['backendlang']['backendlang']['On_Hold_Report']) ? $data['backendlang']['backendlang']['On_Hold_Report'] :'' }}
        <!-- <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
        </small> -->
    </h1>
</div>
<form action="{{ route('on_hold_report') }}" method="GET">
<div class="row">
	<div class="col-sm-2">
		<div class="form-group">
			<input type="text" class="form-control" name="dates" value="{{ !empty(request('dates')) ? request('dates') : $startDate.' - '.$endDate }}">
		</div>
	</div>
	<div class="col-sm-2">
		<div class="form-group">
			<input type="text" class="form-control" name="transaction_no" value="{{ !empty(request('transaction_no')) ? request('transaction_no') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Transaction_Number']) ? $data['backendlang']['backendlang']['Search_Transaction_Number'] :'' }}">
		</div>
	</div>
	<div class="col-sm-2">
		<div class="form-group">
			<input type="text" class="form-control" name="product_name" value="{{ !empty(request('product_name')) ? request('product_name') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Product_Name']) ? $data['backendlang']['backendlang']['Search_Product_Name'] :'' }}">
		</div>
	</div>
	<div class="col-sm-2">
		<div class="form-group">
			<input type="text" class="form-control" name="item_code" value="{{ !empty(request('item_code')) ? request('item_code') : '' }}" placeholder="Search Product Code">
		</div>
	</div>
	<div class="col-sm-2">
		<div class="form-group">
			<input type="text" class="form-control" name="receiver_name" value="{{ !empty(request('receiver_name')) ? request('receiver_name') : '' }}" placeholder="Search Receiver Name">
		</div>
	</div>
	<div class="col-sm-2">
		<div class="form-group">
			<input type="text" class="form-control" name="agent_name" value="{{ !empty(request('agent_name')) ? request('agent_name') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Agent_Name']) ? $data['backendlang']['backendlang']['Search_Agent_Name'] :'' }}">
		</div>
	</div>
	<div class="col-sm-2">
		<div class="form-group">
			<input type="text" class="form-control" name="receiver_code" value="{{ !empty(request('receiver_code')) ? request('receiver_code') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Agent_Code']) ? $data['backendlang']['backendlang']['Search_Agent_Code'] :'' }}">
		</div>
	</div>
	<div class="col-sm-2">
		<div class="form-group">
			<input type="text" class="form-control" name="referrer_name" value="{{ !empty(request('referrer_name')) ? request('referrer_name') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Referrer_Name']) ? $data['backendlang']['backendlang']['Search_Referrer_Name'] :'' }}">
		</div>
	</div>
	<div class="col-sm-2">
		<div class="form-group">
			<input type="text" class="form-control" name="referrer_code" value="{{ !empty(request('referrer_code')) ? request('referrer_code') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Referrer_Code']) ? $data['backendlang']['backendlang']['Search_Referrer_Code'] :'' }}">
		</div>
	</div>
	<div class="col-sm-2">
		<div class="form-group">
			<select class="form-control" name="item_code_dropdown">
				<option value="">{{ isset($data['backendlang']['backendlang']['Select_Product_Code']) ? $data['backendlang']['backendlang']['Select_Product_Code'] :'' }}</option>
				@foreach($dropdowns as $dropdown)
				<option {{ (!empty(request('item_code_dropdown')) && request('item_code_dropdown') == '$dropdown->item_code') ? 'selected' : '' }} value="{{ $dropdown->item_code }}"> {{ $dropdown->item_code }} </option>
				@endforeach
			</select>
		</div>
	</div>

		<div class="col-sm-2">
		<div class="form-group">
			<select class="form-control" name="status">
				<option value="">{{ isset($data['backendlang']['backendlang']['Select_Transaction_Status']) ? $data['backendlang']['backendlang']['Select_Transaction_Status'] :'' }}</option>
				<option {{ (!empty(request('status')) && request('status') == '1') ? 'selected' : '' }} value="1">{{ isset($data['backendlang']['backendlang']['Collected']) ? $data['backendlang']['backendlang']['Collected'] :'' }}</option>
				<option {{ (!empty(request('status')) && request('status') == '99') ? 'selected' : '' }} value="99">{{ isset($data['backendlang']['backendlang']['Awaiting_Pickup']) ? $data['backendlang']['backendlang']['Awaiting_Pickup'] :'' }}</option>
			</select>
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
					<i class="fa fa-search"></i> {{ isset($data['backendlang']['backendlang']['Search']) ? $data['backendlang']['backendlang']['Search'] :'' }}
				</button>
				<a href="{{ route('on_hold_report') }}" class="btn btn-warning btn-sm">
					<i class="fa fa-refresh"></i> {{ isset($data['backendlang']['backendlang']['Clear_Search']) ? $data['backendlang']['backendlang']['Clear_Search'] :'' }}
				</a>
			</div>
		</div>
	</div>
</div>
</form>
<!-- <div class="form-group">
	<span class="badge label-info" style="font-size: 1.5rem; padding: 10px;">
		Dates: {{ !empty(request('dates')) ? request('dates') : $startDate.' - '.$endDate }}
	</span>
</div> -->
<hr>
<!-- <div class="form-group" align="right">
	<a href="{{ route('print_agent_stock_report', ['dates='.(!empty(request('dates')) ? request('dates') : $startDate.' - '.$endDate), 
												   'buyer='.(!empty(request('buyer')) ? request('buyer') : '')]) }}" class="print-window btn btn-outline-primary" target="_blank">
		<i class="fa fa-print"></i> Print
	</a>
	
	<a href="{{ route('exportAgentStockReport', ['dates='.(!empty(request('dates')) ? request('dates') : $startDate.' - '.$endDate), 
												 'buyer='.(!empty(request('buyer')) ? request('buyer') : '')]) }}"])}}" target="_blank" class="btn btn-warning">
		<i class="fa fa-download"></i> Export
	</a>
</div> -->

<div class="row" style="overflow: auto;">
	<div class="col-12">
		<table class="table table-bordered">
			<thead>
				<tr class="info">
					<th>#</th>
					<th>{{ isset($data['backendlang']['backendlang']['Transaction_Date_Time']) ? $data['backendlang']['backendlang']['Transaction_Date_Time'] :'' }}</th>
					<th>{{ isset($data['backendlang']['backendlang']['Transaction_Number']) ? $data['backendlang']['backendlang']['Transaction_Number'] :'' }}</th>
					<th>{{ isset($data['backendlang']['backendlang']['Product_Name']) ? $data['backendlang']['backendlang']['Product_Name'] :'' }}</th>
					<th>P{{ isset($data['backendlang']['backendlang']['Product_Code']) ? $data['backendlang']['backendlang']['Product_Code'] :'' }}</th>
					<th>{{ isset($data['backendlang']['backendlang']['Receiver_Name']) ? $data['backendlang']['backendlang']['Receiver_Name'] :'' }}</th>
					<th>{{ isset($data['backendlang']['backendlang']['Receiver_Phone_Number']) ? $data['backendlang']['backendlang']['Receiver_Phone_Number'] :'' }}</th>
					<th>{{ isset($data['backendlang']['backendlang']['Receiver_Email_Address']) ? $data['backendlang']['backendlang']['Receiver_Email_Address'] :'' }}</th>
					<th>{{ isset($data['backendlang']['backendlang']['Agent_Name']) ? $data['backendlang']['backendlang']['Agent_Name'] :'' }}</th>
					<th>{{ isset($data['backendlang']['backendlang']['Agent_Code']) ? $data['backendlang']['backendlang']['Agent_Code'] :'' }}</th>
					<th>{{ isset($data['backendlang']['backendlang']['Referrer_Code']) ? $data['backendlang']['backendlang']['Referrer_Code'] :'' }}</th>
					<th>{{ isset($data['backendlang']['backendlang']['Referrer_Name']) ? $data['backendlang']['backendlang']['Referrer_Name'] :'' }}</th>
					<th>{{ isset($data['backendlang']['backendlang']['Transaction_Status']) ? $data['backendlang']['backendlang']['Transaction_Status'] :'' }}</th>
					<th style="text-align: right;">{{ isset($data['backendlang']['backendlang']['In_Record']) ? $data['backendlang']['backendlang']['In_Record'] :'' }}</th>
					<th style="text-align: right;">{{ isset($data['backendlang']['backendlang']['Out_Record']) ? $data['backendlang']['backendlang']['Out_Record'] :'' }}</th>
				</tr>
			</thead>
			<tbody>
				@php
					$totalIn = 0;
					$totalOut = 0;
				@endphp
				@if(!$pickups->isEmpty())
				@foreach($pickups as $key => $pickup)
				<tr>
					<td>
						{{ $key+1 }}
						<input type="hidden" name="pid" value="{{ $pickup->Pid }}">
					</td>
					<td>
						@if(!empty($pickup->created_at))
							{{ $pickup->created_at }}
						@else
							<i class="fa fa-minus"></i>
						@endif
					</td>
					<td>
						<a href="{{ route('transaction.transactions.edit', $pickup->transaction_id) }}"> 
						{{ $pickup->transaction_no }}
						</a>
					</td>
					<td> 
						{{ $pickup->product_name }}
					</td>
					<td>{{ $pickup->item_code }}</td>
					<td>
						{{ $pickup->f_name }}
					</td>
					<td>
						{{ $pickup->phone }}
					</td>
					<td>
						@if(!empty($pickup->email))
							{{ $pickup->email }}
						@else
							<i class="fa fa-minus"></i>
						@endif
					</td>
					<td>
						{{ $pickup->receiver_name }}
					</td>
					<td>
						{{ $pickup->receiver_code }}
					</td>
					<td>
						{{ $pickup->referrer_code }}
					</td>
					<td>
						{{ $pickup->referrer_name }}
					</td>
					<td>
						@if(!empty($pickup->stockOut))
							<span class="badge label-success">{{ isset($data['backendlang']['backendlang']['Collected']) ? $data['backendlang']['backendlang']['Collected'] :'' }}</span>
						@elseif(!empty($pickup->stockIn))
							<span class="badge label-info">{{ isset($data['backendlang']['backendlang']['Awaiting_Pickup']) ? $data['backendlang']['backendlang']['Awaiting_Pickup'] :'' }}</span>
						@else
							<i class="fa fa-minus"></i>
						@endif
					</td>
					<td align="right">
						@if(!empty($pickup->stockIn))
							{{ $pickup->stockIn }}
						@else
							<i class="fa fa-minus"></i>
						@endif
					</td>
					<td align="right">
						@if(!empty($pickup->stockOut))
							{{ $pickup->stockOut }}
						@else
							<i class="fa fa-minus"></i>
						@endif
					</td>
				</tr>
				@php
					$totalIn += $pickup->stockIn;
					$totalOut += $pickup->stockOut;
				@endphp
				@endforeach
				@else
				<tr>
					<td colspan="15">{{ isset($data['backendlang']['backendlang']['No_Result_Found']) ? $data['backendlang']['backendlang']['No_Result_Found'] :'' }}</td>
				</tr>
				@endif
				<tr class="warning">
					<th colspan="13">
						<b>{{ isset($data['backendlang']['backendlang']['Summary']) ? $data['backendlang']['backendlang']['Summary'] :'' }}</b>
					</th>
					<th style="text-align: right;">
						<b>{{ isset($data['backendlang']['backendlang']['Total_In']) ? $data['backendlang']['backendlang']['Total_In'] :'' }}</b>
					</th>
					<th style="text-align: right;">
						<b>{{ isset($data['backendlang']['backendlang']['Total_Out']) ? $data['backendlang']['backendlang']['Total_Out'] :'' }}</b>
					</th>
				</tr>
				<tr class="warning">
					<td colspan="13">

					</td>
					<td align="right">
						{{ $totalIn }}
					</td>
					<td align="right">
						{{ $totalOut }}
					</td>
				</tr>
			</tbody>
		</table>
		{{ $pickups->links() }}
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