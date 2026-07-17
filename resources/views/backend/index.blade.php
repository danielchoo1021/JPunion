@extends('layouts.admin_app')

@section('content')
<form action="{{ route('transaction.transactions.index') }}" method="GET">
<div class="row">
	<div class="col-sm-2">
		<div class="form-group">
			<input type="text" class="form-control" name="transaction_no" value="{{ !empty(request('transaction_no')) ? request('transaction_no') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Transaction_No']) ? $data['backendlang']['backendlang']['Search_Transaction_No'] :'' }}">
		</div>
	</div>

	<div class="col-sm-2">
		<div class="form-group">
			<select class="form-control" name="status">
				<option value="">{{ isset($data['backendlang']['backendlang']['Select_Status']) ? $data['backendlang']['backendlang']['Select_Status'] :'' }}</option>
				<option {{ (!empty(request('status')) && request('status') == '1') ? 'selected' : '' }} value="1">{{ isset($data['backendlang']['backendlang']['Paid']) ? $data['backendlang']['backendlang']['Paid'] :'' }}</option>
				<option {{ (!empty(request('status')) && request('status') == '98') ? 'selected' : '' }} value="98">{{ isset($data['backendlang']['backendlang']['Waiting_Verification']) ? $data['backendlang']['backendlang']['Waiting_Verification'] :'' }}</option>
				<option {{ (!empty(request('status')) && request('status') == '96') ? 'selected' : '' }} value="96">{{ isset($data['backendlang']['backendlang']['Rejected']) ? $data['backendlang']['backendlang']['Rejected'] :'' }}</option>
				<option {{ (!empty(request('status')) && request('status') == '99') ? 'selected' : '' }} value="99">{{ isset($data['backendlang']['backendlang']['Unpaid']) ? $data['backendlang']['backendlang']['Unpaid'] :'' }}</option>
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
					<i class="fa fa-search"></i> {{ isset($data['backendlang']['backendlang']['Search']) ? $data['backendlang']['backendlang']['Search'] :'' }}
				</button>
				<a href="{{ route('transaction.transactions.index') }}" class="btn btn-warning btn-sm">
					<i class="fa fa-refresh"></i> {{ isset($data['backendlang']['backendlang']['Clear_Search']) ? $data['backendlang']['backendlang']['Clear_Search'] :'' }}
				</a>
			</div>
		</div>
	</div>
</div>
<hr>
<div class="form-group">
	<span class="badge label-success" style="font-size: 1.5rem; padding: 10px;">
		{{ isset($data['backendlang']['backendlang']['Grand_Total']) ? $data['backendlang']['backendlang']['Grand_Total'] :'' }}: <span class="grandTotal"></span>
	</span>
	|
	<span class="badge label-warning" style="font-size: 1.5rem; padding: 10px;">
		{{ isset($data['backendlang']['backendlang']['Net_Total']) ? $data['backendlang']['backendlang']['Net_Total'] :'' }}: <span class="netTotal"></span>
	</span>
</div>

<div class="row">
	<div class="col-12">
		<table class="table table-bordered">
			<thead>
				<tr class="info">
					<th>#</th>
					<th>{{ isset($data['backendlang']['backendlang']['Transaction_No']) ? $data['backendlang']['backendlang']['Transaction_No'] :'' }}</th>
					<th>{{ isset($data['backendlang']['backendlang']['Customer']) ? $data['backendlang']['backendlang']['Customer'] :'' }}</th>
					<!-- <th>Product</th>
					<th>Category</th>
					<th>Price (RM)</th> -->
					<th>{{ isset($data['backendlang']['backendlang']['Quantity']) ? $data['backendlang']['backendlang']['Quantity'] :'' }}</th>
					<th>{{ isset($data['backendlang']['backendlang']['Shipping_Fee']) ? $data['backendlang']['backendlang']['Shipping_Fee'] :'' }} (RM)</th>
					<th>{{ isset($data['backendlang']['backendlang']['Processing_Fee']) ? $data['backendlang']['backendlang']['Processing_Fee'] :'' }} (RM)</th>
					<th>{{ isset($data['backendlang']['backendlang']['Total_Amount']) ? $data['backendlang']['backendlang']['Total_Amount'] :'' }} (RM)</th>
					<th>{{ isset($data['backendlang']['backendlang']['Status']) ? $data['backendlang']['backendlang']['Status'] :'' }}</th>
					<th>{{ isset($data['backendlang']['backendlang']['Created']) ? $data['backendlang']['backendlang']['Created'] :'' }}</th>
					<th></th>
					<!-- <th>Action</th> -->
				</tr>
			</thead>
			<tbody>
				@php
				$totalTransaction = 0;
				@endphp
				@if(!$transactions->isEmpty())
				@foreach($transactions as $key => $transaction)
				<tr>
					<td>{{ $key+1 }}
						<input type="hidden" name="tid" value="{{ $transaction->Tid }}">
					</td>
					<td>{{ $transaction->transaction_no }}</td>
					<td>{{ $transaction->customer_name }}</td>
					<!-- <td>{{ $transaction->product_name }}</td>
					<td>{{ !empty($transaction->sub_category) ? $transaction->sub_category."°" : '-' }}</td>
					<td>{{ $transaction->unit_price }}</td> -->
					<td>{{ $transaction->quantity }}</td>
					<td>{{ number_format($transaction->shipping_fee, 2) }}</td>
					<td>{{ number_format($transaction->processing_fee, 2) }}</td>
					<td>{{ number_format($transaction->grand_total, 2) }}</td>
					<td>
						@if($transaction->status == 99)
							<span class="badge badge-pill bg-warning">{{ isset($data['backendlang']['backendlang']['Unpaid']) ? $data['backendlang']['backendlang']['Unpaid'] :'' }}</span>
						@elseif($transaction->status == 98)
							<span class="badge badge-pill badge-info">{{ isset($data['backendlang']['backendlang']['Waiting_Verification']) ? $data['backendlang']['backendlang']['Waiting_Verification'] :'' }}</span>
						@elseif($transaction->status == 97)
							<span class="badge badge-pill badge-info">{{ isset($data['backendlang']['backendlang']['In-Progress']) ? $data['backendlang']['backendlang']['In_progress'] :'' }}</span>
						@elseif($transaction->status == '96')
							<span class="badge bg-danger">{{ isset($data['backendlang']['backendlang']['Rejected']) ? $data['backendlang']['backendlang']['Rejected'] :'' }}</span>
						@elseif($transaction->status == 1)
							<span class="badge bg-success">{{ isset($data['backendlang']['backendlang']['Paid']) ? $data['backendlang']['backendlang']['Paid'] :'' }}</span>
						@else
							<span class="badge badge-pill bg-danger">{{ isset($data['backendlang']['backendlang']['Cancelled']) ? $data['backendlang']['backendlang']['Cancelled'] :'' }}</span>
						@endif
					</td>
					<td>{{ ($transaction->created_at) }}</td>
					<td>
						<a href="{{ route('transaction.transactions.edit', $transaction->Tid) }}">
							<i class="fa fa-eye"></i> {{ isset($data['backendlang']['backendlang']['View']) ? $data['backendlang']['backendlang']['View'] :'' }}
						</a>
						&nbsp;&nbsp;
						<div class="btn-group">
						  <button type="button" class="btn btn-outline-danger btn-sm  dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
						   {{ isset($data['backendlang']['backendlang']['Action']) ? $data['backendlang']['backendlang']['Action'] :'' }} <span class="caret"></span>
						  </button>
						  <ul class="dropdown-menu" role="menu">
						    <li><a href="#" class="change_action" data-id="1">{{ isset($data['backendlang']['backendlang']['Approve']) ? $data['backendlang']['backendlang']['Approve'] :'' }}</a></li>
						    <li><a href="#" class="change_action" data-id="96">{{ isset($data['backendlang']['backendlang']['Reject']) ? $data['backendlang']['backendlang']['Reject'] :'' }}</a></li>
						  </ul>
						</div>
					</td>
					<!-- <td>
						<a href="">
							<i class="ace-icon fa fa-pencil bigger-130"></i>
						</a>
						&nbsp;&nbsp;
						<a href="#" class="red">
							<i class="ace-icon fa fa-trash-o bigger-130"></i>
						</a>
					</td> -->
				</tr>
				@php
				if($transaction->status == '1'){
					$totalTransaction += $transaction->grand_total;
				}
				@endphp
				@endforeach
				@else
				<tr>
					<td colspan="10">{{ isset($data['backendlang']['backendlang']['No_Result_Found']) ? $data['backendlang']['backendlang']['No_Result_Found'] :'' }}</td>
				</tr>
				@endif
			</tbody>
		</table>
		
	</div>
</div>
</form>
@endsection

@section('js')
<script type="text/javascript">
	$('.change_action').click( function(){

		$('.loading-gif').show();

		var ele = $(this);
		var action_id = $(this).data('id');
		var tid = $(this).closest('tr').find('input[name="tid"]').val();
		var fd = new FormData();
		fd.append('action_id', action_id);
		fd.append('tid', tid);
		if(action_id == '1'){
			var confirmMessage = confirm('{{ isset($data['backendlang']['backendlang']['Complete_This_Transaction']) ? $data['backendlang']['backendlang']['Complete_This_Transaction'] :'' }}');
		}else if(action_id == '95'){
			var confirmMessage = confirm('{{ isset($data['backendlang']['backendlang']['Cancel_This_Transaction']) ? $data['backendlang']['backendlang']['Cancel_This_Transaction'] :'' }}');
		}else if(action_id == '96'){
			var confirmMessage = confirm('{{ isset($data['backendlang']['backendlang']['Reject_This_Transaction']) ? $data['backendlang']['backendlang']['Reject_This_Transaction'] :'' }}');
		}else if(action_id == '11'){
			var confirmMessage = confirm('{{ isset($data['backendlang']['backendlang']['Delivered?']) ? $data['backendlang']['backendlang']['Delivered?'] :'' }}');
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
		       		// alert(response);
		       		toastr.success('{{ isset($data['backendlang']['backendlang']['Update_Successful']) ? $data['backendlang']['backendlang']['Update_Successful'] :'' }}');
		       		window.location.href = "{{ route('transaction.transactions.index') }}";
		       		// if(action_id == '1'){
		       		// 	ele.closest('tr').find('.status_id').html('<span class="badge bg-success">Approved</span>');
		       		// }else if(action_id == '98'){
		       		// 	ele.closest('tr').find('.status_id').html('<span class="badge bg-danger">Rejected</span>');
		       		// }else{
		       		// 	ele.closest('tr').find('.status_id').html('<span class="badge bg-danger">Cancelled</span>');
		       		// }
		       },
		    });			
		}else{
			$('.loading-gif').hide();
		}
	});
</script>

<script type="text/javascript">


	$('.grandTotal').html('{{ number_format($totalTransaction, 2) }}');
	$('.netTotal').html('{{ number_format($netTransaction->netTotal, 2) }}');
</script>
@endsection