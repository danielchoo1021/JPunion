@extends('layouts.admin_app')
	<style type="text/css">
		.table > tbody > tr.info > td {
			background-color: #d9edf7;
			font-weight: bold;
		}
	</style>
@section('content')
<div class="page-header">
    <h1>
        {{ isset($data['backendlang']['backendlang']['Agent_Joining_Fees']) ? $data['backendlang']['backendlang']['Agent_Joining_Fees'] :'' }}
        <!-- <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
        </small> -->
    </h1>
</div>
<form action="{{ route('join_list') }}" method="GET">
<div class="row">
	<div class="col-sm-2">
		<div class="form-group">
			<input type="text" class="form-control" name="code" value="{{ !empty(request('code')) ? request('code') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Agent_Code']) ? $data['backendlang']['backendlang']['Search_Agent_Code'] :'' }}">
		</div>
	</div>

	<!-- <div class="col-sm-2">
		<div class="form-group">
			<select class="form-control" name="status">
				<option value="">Search By Status</option>
				<option {{ (!empty(request('status')) && request('status') == '1') ? 'selected' : '' }} value="1">Approved</option>
				<option {{ (!empty(request('status')) && request('status') == '98') ? 'selected' : '' }} value="98">Rejected</option>
				<option {{ (!empty(request('status')) && request('status') == '99') ? 'selected' : '' }} value="99">Pending</option>
			</select>
		</div>
	</div> -->

	
</div>

<div class="form-group">
	<div class="row">
		<div class="col-sm-2">
			<div class="form-group">
				{{ isset($data['backendlang']['backendlang']['Row_Per_Page']) ? $data['backendlang']['backendlang']['Row_Per_Page'] :'' }}: <br>
				<select class="input-small" name="per_page">
					<option value="10">10</option>
					<option value="20">20</option>
					<option value="50">50</option>
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
				<a href="{{ route('join_list') }}" class="btn btn-warning btn-sm">
					<i class="fa fa-refresh"></i> {{ isset($data['backendlang']['backendlang']['Clear_Search']) ? $data['backendlang']['backendlang']['Clear_Search'] :'' }}
				</a>
			</div>
		</div>
	</div>
</div>
</form>
<div class="row" style="overflow: auto; padding-bottom: 50px;">
	<div class="col-12">
		<table class="table table-bordered">
			<thead>
				<tr class="info">
					<th>#</th>
					<th>{{ isset($data['backendlang']['backendlang']['Transaction_No']) ? $data['backendlang']['backendlang']['Transaction_No'] :'' }}</th>
					<th>{{ isset($data['backendlang']['backendlang']['Agent']) ? $data['backendlang']['backendlang']['Agent'] :'' }}</th>
					<th>{{ isset($data['backendlang']['backendlang']['Main_Code']) ? $data['backendlang']['backendlang']['Main_Code'] :'' }}</th>
					<th>{{ isset($data['backendlang']['backendlang']['Bank_Slip']) ? $data['backendlang']['backendlang']['Bank_Slip'] :'' }}</th>
					<th>{{ isset($data['backendlang']['backendlang']['Amount']) ? $data['backendlang']['backendlang']['Amount'] :'' }} (RM)</th>
					<th>{{ isset($data['backendlang']['backendlang']['Status']) ? $data['backendlang']['backendlang']['Status'] :'' }}</th>
					<th>{{ isset($data['backendlang']['backendlang']['Created_At']) ? $data['backendlang']['backendlang']['Created_At'] :'' }}</th>
					<th>{{ isset($data['backendlang']['backendlang']['Updated_At']) ? $data['backendlang']['backendlang']['Updated_At'] :'' }}</th>
					<!-- <th>Action</th> -->
				</tr>
			</thead>
			<tbody>
				@php
					$totalFee = 0;
				@endphp
				@if(!$join_records->isEmpty())
				@foreach($join_records as $key => $transaction)
				<tr>
					<td>
						{{ $key+1 }}
						<input type="hidden" name="tid" value="{{ $transaction->id }}">
					</td>
					<td>{{ $transaction->transaction_no }}</td>
					<td>{{ $transaction->agent_name }}</td>
					<td>{{ $transaction->user_id }}</td>
					<td>
						@if($transaction->bank_slip)
							<a href="#" data-toggle="modal" data-target="#{{ $transaction->transaction_no }}">
								<div style="background-image: url({{ asset($transaction->bank_slip) }});
											background-size: cover;
											background-position: center;
											background-repeat: no-repeat;
											width: 100px;
											height: 100px;">
								</div>
							</a>

							<div class="modal fade" id="{{ $transaction->transaction_no }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
							  <div class="modal-dialog">
							    <div class="modal-content">
							        <div class="modal-body">
							        	<img src="{{ asset($transaction->bank_slip) }}" width="100%">
							        </div>
							    </div>
							  </div>
							</div>
						@endif
					</td>
					<td>{{ number_format($transaction->amount, 2) }}</td>
					<td class="status_id">
						@if($transaction->status == '1')
							<span class="badge bg-success">{{ isset($data['backendlang']['backendlang']['Approved']) ? $data['backendlang']['backendlang']['Approved'] :'' }}</span>
						@elseif($transaction->status == '99')
							<span class="badge bg-warning">{{ isset($data['backendlang']['backendlang']['Pending']) ? $data['backendlang']['backendlang']['Pending'] :'' }}</span>
						@elseif($transaction->status == '98')
							<span class="badge bg-danger">{{ isset($data['backendlang']['backendlang']['Rejected']) ? $data['backendlang']['backendlang']['Rejected'] :'' }}</span>
						@else
							<span class="badge bg-danger">{{ isset($data['backendlang']['backendlang']['Cancelled']) ? $data['backendlang']['backendlang']['Cancelled'] :'' }}</span>
						@endif
					</td>
					<td>{{ $transaction->created_at }}</td>
					<td>{{ $transaction->updated_at }}</td>
				</tr>
				@php
					$totalFee += $transaction->amount;
				@endphp
				@endforeach
				<tr class="info">
					<td colspan="5"></td>
					<td>{{ number_format($totalFee, 2) }}</td>
					<td colspan="3"></td>
				</tr>
				@else
				<tr>
					<td colspan="12">{{ isset($data['backendlang']['backendlang']['No_Result_Found']) ? $data['backendlang']['backendlang']['No_Result_Found'] :'' }}</td>
				</tr>
				@endif
			</tbody>
		</table>
		
	</div>
</div>
{{ $join_records->links() }}
@endsection
@section('js')
<script type="text/javascript">
	
</script>
@endsection