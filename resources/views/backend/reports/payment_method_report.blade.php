@extends('layouts.admin_app')

@section('content')
<div class="page-header">
    <h1>
        {{ isset($data['backendlang']['backendlang']['Payment_Method_Report_List']) ? $data['backendlang']['backendlang']['Payment_Method_Report_List'] :'' }}
        <!-- <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
        </small> -->
    </h1>
</div>
<form action="{{ route('payment_method_report') }}" method="GET">
<div class='form-group'>
	<div class="row">
		<!-- <div class="col-sm-2">
			<div class="form-group">
				<input type="text" class="form-control" name="dates" value="{{ !empty(request('dates')) ? request('dates') : $startDate.' - '.$endDate }}">
			</div>
		</div> -->

		<div class="col-sm-2">
			<div class="form-group">
				<input type="text" class="form-control" name="transaction_no" value="{{ !empty(request('transaction_no')) ? request('transaction_no') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Transaction_Number']) ? $data['backendlang']['backendlang']['Search_Transaction_Number'] :'' }}">
			</div>
		</div>

		<div class="col-sm-2">
			<div class="form-group">
				<input type="text" class="form-control" name="buyer" value="{{ !empty(request('buyer')) ? request('buyer') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Buyer']) ? $data['backendlang']['backendlang']['Search_Buyer'] :'' }}">
			</div>
		</div>
	</div>
</div>

<!-- <div class="form-group">
	<div class="row">
		<div class="col-sm-2">
			<div class="form-group">
				<select class="form-control" name="yearly">
					<option value="">Select Year</option>
					<option {{ (!empty(request('yearly')) && request('yearly') == "2021") ? 'selected' : '' }} value="2021">2021</option>
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
	</div>
</div> -->

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
				<a href="{{ route('payment_method_report') }}" class="btn btn-warning btn-sm">
					<i class="fa fa-refresh"></i> {{ isset($data['backendlang']['backendlang']['Clear_Search']) ? $data['backendlang']['backendlang']['Clear_Search'] :'' }}
				</a>
			</div>
		</div>
	</div>
</div>
@php
$dailyDate = date('Y-m-d');
$MonthlyDate = date('Y-m');
$YearlyDate = date('Y');
@endphp
</form>

<div class="row" style="overflow: auto;">
	<div class="col-12">
		<table class="table table-bordered">
			<thead>
				<tr class="info">
					<th>#</th>
					<th>{{ isset($data['backendlang']['backendlang']['Date']) ? $data['backendlang']['backendlang']['Date'] :'' }}</th>
					<th>{{ isset($data['backendlang']['backendlang']['Transaction_No']) ? $data['backendlang']['backendlang']['Transaction_No'] :'' }}</th>
					<th>{{ isset($data['backendlang']['backendlang']['Buyer']) ? $data['backendlang']['backendlang']['Buyer'] :'' }}</th>
					<th>{{ isset($data['backendlang']['backendlang']['Payment_Method']) ? $data['backendlang']['backendlang']['Payment_Method'] :'' }}</th>
					<th>{{ isset($data['backendlang']['backendlang']['Payment_Details']) ? $data['backendlang']['backendlang']['Payment_Details'] :'' }}</th>
				</tr>
			</thead>
			<tbody>
				@if(!$transactions->isEmpty())
				@foreach($transactions as $key => $transaction)
				<tr>
					<td>
						{{ $key+1 }}

					</td>
					<td>
						{{ ($transaction->created_at) }}
					</td>
					<td>
						<a href="{{ route('transaction.transactions.edit', $transaction->id) }}">
							{{ $transaction->transaction_no }}
						</a>
					</td>
					<td>
						{{ (!empty($transaction->buyer_name)) ? $transaction->buyer_name : $transaction->address_name }}
					</td>
					<td>
						@if(!empty($transaction->online_payment_method))
							@if($transaction->online_payment_method == 'WA')
								{{ $data['backendlang']['backendlang']['Online_Transfer_Wallet'] ?? 'Online Transfer (Wallet)' }}
							@else
								{{ $data['backendlang']['backendlang']['Online_Banking'] ?? 'Online Banking' }}
							@endif
						@else
							-
						@endif
					</td>
					<td>
						{{ (!empty($transaction->bank_name)) ? $transaction->bank_name : '-' }}
					</td>
				</tr>
				@endforeach
				@else
				<tr>
					<td colspan="12">{{ isset($data['backendlang']['backendlang']['No_Result_Found']) ? $data['backendlang']['backendlang']['No_Result_Found'] :'' }}</td>
				</tr>
				@endif
			</tbody>
		</table>
		{{ $transactions->links() }}
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