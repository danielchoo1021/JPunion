@extends('layouts.admin_app')

@section('content')
<div class="container-box form-group">
	<h3>{{ isset($data['backendlang']['backendlang']['Filter']) ? $data['backendlang']['backendlang']['Filter'] :'' }}</h3>
	<hr>
	<form action="{{ route('topup_list') }}" method="GET">
	<div class="row">
		<div class="col-sm-2">
			<div class="form-group">
				<input type="text" class="form-control" name="dates" value="{{ !empty(request('dates')) ? request('dates') : $startDate.' - '.$endDate }}">
			</div>
		</div>
		
		<div class="col-sm-2">
			<div class="form-group">
				<input type="text" class="form-control" name="topup_no" value="{{ !empty(request('topup_no')) ? request('topup_no') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Topup_No']) ? $data['backendlang']['backendlang']['Search_Topup_No'] :'' }}">
			</div>
		</div>

		<div class="col-sm-2">
			<div class="form-group">
				<input type="text" class="form-control" name="agent_name" value="{{ !empty(request('agent_name')) ? request('agent_name') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Agent_Name']) ? $data['backendlang']['backendlang']['Search_Agent_Name'] :'' }}">
			</div>
		</div>

		<div class="col-sm-2">
			<div class="form-group">
				<select class="form-control" name="status">
					<option value="">{{ isset($data['backendlang']['backendlang']['Search_By_Status']) ? $data['backendlang']['backendlang']['Search_By_Status'] :'' }}</option>
					<option {{ (!empty(request('status')) && request('status') == '1') ? 'selected' : '' }} value="1">{{ isset($data['backendlang']['backendlang']['Approved']) ? $data['backendlang']['backendlang']['Approved'] :'' }}</option>
					<option {{ (!empty(request('status')) && request('status') == '98') ? 'selected' : '' }} value="98">{{ isset($data['backendlang']['backendlang']['Rejected']) ? $data['backendlang']['backendlang']['Rejected'] :'' }}</option>
					<option {{ (!empty(request('status')) && request('status') == '99') ? 'selected' : '' }} value="99">{{ isset($data['backendlang']['backendlang']['Pending']) ? $data['backendlang']['backendlang']['Pending'] :'' }}</option>
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
						<i class="bi bi-search"></i> {{ isset($data['backendlang']['backendlang']['Search']) ? $data['backendlang']['backendlang']['Search'] :'' }}
					</button>
					<a href="{{ route('topup_list') }}" class="btn btn-warning btn-sm">
						<i class="bi bi-arrow-clockwise"></i> {{ isset($data['backendlang']['backendlang']['Clear_Search']) ? $data['backendlang']['backendlang']['Clear_Search'] :'' }}
					</a>
				</div>
			</div>
		</div>
	</div>
	</form>
</div>
<div class="container-box form-group">
	<div class="form-group" align="right">
		<a href="{{ route('exportTopupList', ['dates' => (!empty(request('dates')) ? request('dates') : $startDate.' - '.$endDate),
								'topup_no' => (!empty(request('topup_no')) ? request('topup_no') : ''),'agent_name' => (!empty(request('agent_name')) ? request('agent_name') : ''),
								'status' => (!empty(request('status')) ? request('status') : '')]) }}" target="_blank" class="btn btn-warning btn-sm">
							<i class="bi bi-download"></i> {{ isset($data['backendlang']['backendlang']['Export']) ? $data['backendlang']['backendlang']['Export'] :'' }}
						</a>
	</div>
	<div class="row" style="overflow: auto;">
		<div class="col-12">
			{{ $topups->links() }}
			<table class="table table-bordered">
				<thead>
					<tr class="info">
						<th>#</th>
						<th>{{ isset($data['backendlang']['backendlang']['Topup_No']) ? $data['backendlang']['backendlang']['Topup_No'] :'' }}</th>
						<!-- <th>Method</th> -->
						<th>{{ isset($data['backendlang']['backendlang']['Agent']) ? $data['backendlang']['backendlang']['Agent'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Amount']) ? $data['backendlang']['backendlang']['Amount'] :'' }} (RM)</th>
						<th>{{ isset($data['backendlang']['backendlang']['Bank_Slip']) ? $data['backendlang']['backendlang']['Bank_Slip'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Status']) ? $data['backendlang']['backendlang']['Status'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Created_At']) ? $data['backendlang']['backendlang']['Created_At'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Updated_At']) ? $data['backendlang']['backendlang']['Updated_At'] :'' }}</th>
						<th></th>
						<!-- <th>Action</th> -->
					</tr>
				</thead>
				<tbody>
					
					@if(!$topups->isEmpty())
					@foreach($topups as $key => $transaction)
					<tr>
						<td>
							{{ $key+1 }}
							<input type="hidden" name="tid" value="{{ $transaction->id }}">
						</td>
						<td>{{ $transaction->topup_no }}</td>
						<!-- <td>{{ ($transaction->topup_payment_method == '1') ? 'Online Banking' : 'Bank Transfer' }}</td> -->
						<td>{{ $transaction->agent_name }}</td>
						<td>{{ number_format($transaction->actual_amount, 2) }}</td>
						<td>
							@if($transaction->bank_slip)
								<a href="#" data-toggle="modal" data-target="#{{ $transaction->topup_no }}">
									<div style="background-image: url({{ asset($transaction->bank_slip) }});
												background-size: cover;
												background-position: center;
												background-repeat: no-repeat;
												width: 100px;
												height: 100px;">
									</div>
								</a>

								<div class="modal fade" id="{{ $transaction->topup_no }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
						<td>
							
							@if($transaction->status != '97')
							<div class="btn-group">
							  	<button type="button" class="btn btn-outline-danger btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
							    	{{ isset($data['backendlang']['backendlang']['Action']) ? $data['backendlang']['backendlang']['Action'] :'' }} <span class="caret"></span>
							  	</button>
							  	<ul class="dropdown-menu" role="menu">
								  	@if($transaction->status == '1')
								  		<li><a href="#" data-id="95" class="change_action">{{ isset($data['backendlang']['backendlang']['Cancel_This_Topup']) ? $data['backendlang']['backendlang']['Cancel_This_Topup'] :'' }}</a></li>
								  	@else
									    <li><a href="#" data-id="1" class="change_action">{{ isset($data['backendlang']['backendlang']['Approve']) ? $data['backendlang']['backendlang']['Approve'] :'' }}</a></li>								    
									    <li><a href="#" data-id="98" class="change_action">{{ isset($data['backendlang']['backendlang']['Reject']) ? $data['backendlang']['backendlang']['Reject'] :'' }}</a></li>
									@endif
							  	</ul>
							</div>
							@endif

							&nbsp;&nbsp;
							@if($transaction->status == '1')
							<a href="{{ route('topup_invoice', $transaction->topup_no) }}" target="_blank">
								<i class='fa fa-print'></i> {{ isset($data['backendlang']['backendlang']['Print_Invoice']) ? $data['backendlang']['backendlang']['Print_Invoice'] :'' }}
							</a>
							@endif
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
			{{ $topups->links() }}
		</div>
	</div>
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">
        	{{ isset($data['backendlang']['backendlang']['Upload_Bank_Slip']) ? $data['backendlang']['backendlang']['Upload_Bank_Slip'] :'' }}
        </h4>
      </div>
      	<form method="POST" action="{{ route('uploadBankSlip') }}" enctype="multipart/form-data">
        @csrf
	      <div class="modal-body">
	        	<input type="hidden" name="withAction" class="withAction">
	        	<input type="hidden" name="wid" class="wid">
	        	<input type="file" name="uploadSlip" required accept="image/*,application/pdf">
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-outline-danger" data-dismiss="modal">{{ isset($data['backendlang']['backendlang']['Cancel']) ? $data['backendlang']['backendlang']['Cancel'] :'' }}</button>
	        <button type="submit" class="btn btn-outline-primary">{{ isset($data['backendlang']['backendlang']['Submit']) ? $data['backendlang']['backendlang']['Submit'] :'' }}</button>
	      </div>
      </form>
    </div>
  </div>
</div>
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
			var confirmMessage = confirm('{{ isset($data['backendlang']['backendlang']['Approve_This_Topup']) ? $data['backendlang']['backendlang']['Approve_This_Topup'] :'' }}');
		}else if(action_id == '95'){
			var confirmMessage = confirm('{{ isset($data['backendlang']['backendlang']['Cancel_This_Topup']) ? $data['backendlang']['backendlang']['Cancel_This_Topup'] :'' }} ');
		}else if(action_id == '98'){
			var confirmMessage = confirm('{{ isset($data['backendlang']['backendlang']['Reject_This_Topup']) ? $data['backendlang']['backendlang']['Reject_This_Topup'] :'' }}');
		}

		if(confirmMessage == true){
			$.ajax({
		       url: '{{ route("change_topup_action") }}',
		       type: 'post',
		       data: fd,
		       contentType: false,
		       processData: false,
		       success: function(response){
		       		// alert(response);
		       		// return false;
		       		if(response == 'ok'){
		       			toastr.success('{{ isset($data['backendlang']['backendlang']['Update_Successful']) ? $data['backendlang']['backendlang']['Update_Successful'] :'' }}');
		       			location.reload();
		       		}else{
		       			toastr.error(response);
		       			$('.loading-gif').hide();
					}
		    	},
			});			
		}else{
			$('.loading-gif').hide();
		}
	});

	$('.upload-slip').click( function(e){
		e.preventDefault();
		var ele = $(this);
		$('.wid').val(ele.data('id'));
		
		if(ele.hasClass('WAction')){
			$('.withAction').val(1);
		}else{
			$('.withAction').val(0);
		}
	});

	$('input[name=dates]').daterangepicker({
		'applyClass' : 'btn-sm btn-success',
		'cancelClass' : 'btn-sm btn-outline-danger',
		locale: {
			applyLabel: '{{ isset($data['backendlang']['backendlang']['Apply']) ? $data['backendlang']['backendlang']['Apply'] :'' }}',
			cancelLabel: '{{ isset($data['backendlang']['backendlang']['Cancel']) ? $data['backendlang']['backendlang']['Cancel'] :'' }}',
			format: 'DD/MM/YYYY',
		}
	});
</script>
@endsection