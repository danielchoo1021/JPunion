@extends('layouts.admin_app')

@section('content')
<form action="{{ route('point_report') }}" method="GET">
<!-- <div class='form-group'>
	<div class="row">
		<div class="col-sm-2">
			<div class="form-group">
				<input type="text" class="form-control" name="transaction_no" value="{{ !empty(request('transaction_no')) ? request('transaction_no') : '' }}" placeholder="Search Transaction No..">
			</div>
		</div>

		<div class="col-sm-2">
			<div class="form-group">
				<input type="text" class="form-control" name="buyer" value="{{ !empty(request('buyer')) ? request('buyer') : '' }}" placeholder="Search Buyer..">
			</div>
		</div>

		<div class="col-sm-2">
			<div class="form-group">
				<input type="text" class="form-control" name="item_code" value="{{ !empty(request('item_code')) ? request('item_code') : '' }}" placeholder="Search Item Code..">
			</div>
		</div>

		<div class="col-sm-2">
			<div class="form-group">
				<input type="text" class="form-control" name="product_code" value="{{ !empty(request('product_code')) ? request('product_code') : '' }}" placeholder="Search Product Code..">
			</div>
		</div>
	</div>
</div>

<div class="form-group">
	<div class="row">
		<div class="col-sm-2">
			<div class="form-group">
				Row Per Page: <br>
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
					<i class="fa fa-search"></i> Search
				</button>
				<a href="{{ route('point_report') }}" class="btn btn-warning btn-sm">
					<i class="fa fa-refresh"></i> Clear Search
				</a>
			</div>
		</div>
	</div>
</div> -->

<div class="row" style="overflow: auto;">
	<div class="col-12">
		<table class="table table-bordered">
			<thead>
				<tr class="info">
					<th>#</th>
					<th>{{ isset($data['backendlang']['backendlang']['PV_Description']) ? $data['backendlang']['backendlang']['PV_Description'] :'' }}</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@if(!$PV_History[$user->code]->isEmpty())
					@foreach($PV_History[$user->code] as $key => $pv_history)
					<tr>
						<td>{{ $key+1 }}</td>
						<td>
							@if(!empty($pv_history->pv_amount))
								@if(!empty($pv_history->pv_transaction_no))
									{{ isset($data['backendlang']['backendlang']['Gain_PV_from_Transaction']) ? $data['backendlang']['backendlang']['Gain_PV_from_Transaction'] :'' }}
									<a href="{{ route('transaction.transactions.edit', $pv_history->Tid) }}" target="_blank">
										#{{ $pv_history->pv_transaction_no }}
									</a>
								@else
									{{ isset($data['backendlang']['backendlang']['Gain_PV']) ? $data['backendlang']['backendlang']['Gain_PV'] :'' }}
								@endif
							@elseif(!empty($pv_history->grand_total))
								@if(!empty($pv_history->t_transaction_no))
									{{ isset($data['backendlang']['backendlang']['Spend_PV_on_Transaction']) ? $data['backendlang']['backendlang']['Spend_PV_on_Transaction'] :'' }}
									<a href="{{ route('transaction.transactions.edit', $pv_history->transaction_id) }}" target="_blank">
										#{{ $pv_history->t_transaction_no }}
									</a>
								@else
									{{ isset($data['backendlang']['backendlang']['Spend_PV']) ? $data['backendlang']['backendlang']['Spend_PV'] :'' }}
								@endif
							@else
								-
							@endif
						</td>
						<td>
							@if(!empty($pv_history->pv_amount))
								(+) {{ number_format($pv_history->pv_amount, 2) }}
							@elseif(!empty($pv_history->grand_total))
								(-) {{ number_format($pv_history->grand_total, 2) }}
							@else
								-
							@endif
						</td>
					</tr>
					@endforeach
					@if($user->status == 3)
					<tr>
						<td>{{ $key+1 }}</td>
						<td>Burn PV</td>
						<td>(-) {{ number_format($PV_Balance, 2) }}</td>
					</tr>
					@endif
				@else
				<tr>
					<td colspan="12">{{ isset($data['backendlang']['backendlang']['No_Result_Found']) ? $data['backendlang']['backendlang']['No_Result_Found'] :'' }}</td>
				</tr>
				@endif
			</tbody>
		</table>
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