@extends('layouts.admin_app')

@section('content')
<form action="{{ route('withdrawal_stocks') }}" method="GET">
<div class="row">
	<div class="col-sm-2">
		<div class="form-group">
			<input type="text" class="form-control" name="dates" value="{{ !empty(request('dates')) ? request('dates') : $startDate.' - '.$endDate }}">
		</div>
	</div>
	<div class="col-sm-2">
		<div class="form-group">
			<input type="text" class="form-control" name="withdrawal_no" value="{{ !empty(request('withdrawal_no')) ? request('withdrawal_no') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Withdrawal_Number']) ? $data['backendlang']['backendlang']['Search_Withdrawal_Number'] :'' }}">
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
				<option value="">{{ isset($data['backendlang']['backendlang']['Select_Withdrawal_Status']) ? $data['backendlang']['backendlang']['Select_Withdrawal_Status'] :'' }}</option>
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
		<div class="col-sm-12">
			<div class="form-group">
				<button class="btn btn-outline-primary btn-sm">
					<i class="fa fa-search"></i> {{ isset($data['backendlang']['backendlang']['Search']) ? $data['backendlang']['backendlang']['Search'] :'' }}
				</button>
				<a href="{{ route('withdrawal_stocks') }}" class="btn btn-warning btn-sm">
					<i class="fa fa-refresh"></i> {{ isset($data['backendlang']['backendlang']['Clear_Search']) ? $data['backendlang']['backendlang']['Clear_Search'] :'' }}
				</a>
			</div>
		</div>
	</div>
</div>
</form>
<hr>
<!-- <div class="form-group" align="right">
	<a href="{{ route('withdrawal_stocks', ['dates='.(!empty(request('dates')) ? request('dates') : $startDate.' - '.$endDate),
											 'withdrawal_no='.(!empty(request('withdrawal_no')) ? request('withdrawal_no') : ''),
											 'agent_name='.(!empty(request('agent_name')) ? request('agent_name') : '' )]) }}" 
											 class="print-window btn btn-outline-primary" target="_blank">
		<i class="fa fa-print"></i> Print
	</a>
	<a href="{{ route('exportWithdrawalReport', ['dates='.(!empty(request('dates')) ? request('dates') : $startDate.' - '.$endDate),
									 'withdrawal_no='.(!empty(request('withdrawal_no')) ? request('withdrawal_no') : ''),
									 'agent_name='.(!empty(request('agent_name')) ? request('agent_name') : '' ) ]) }}" target="_blank" class="btn btn-warning">
		<i class="fa fa-download"></i> Export
	</a>
</div> -->
<div class="row" style="overflow: auto; padding-bottom: 50px;">
	<div class="col-12">
		<table class="table table-bordered">
			<thead>
				<tr class="info">
					<th>
						#
					</th>
					<th>
						{{ isset($data['backendlang']['backendlang']['Stock_Withdrawal_Date_And_Time']) ? $data['backendlang']['backendlang']['Stock_Withdrawal_Date_And_Time'] :'' }}
					</th>
					<th>{{ isset($data['backendlang']['backendlang']['Stock_Withdrawal_Number']) ? $data['backendlang']['backendlang']['Stock_Withdrawal_Number'] :'' }}
					</th>
					<th>{{ isset($data['backendlang']['backendlang']['Agent_Name']) ? $data['backendlang']['backendlang']['Agent_Name'] :'' }}
					</th>
					<th>
						{{ isset($data['backendlang']['backendlang']['product_detail']) ? $data['backendlang']['backendlang']['product_detail'] :'' }}
					</th>
					<th>{{ isset($data['backendlang']['backendlang']['Stock_Withdrawal_Quantity']) ? $data['backendlang']['backendlang']['Stock_Withdrawal_Quantity'] :'' }}
					</th>
					<th>{{ isset($data['backendlang']['backendlang']['Stock_Balance']) ? $data['backendlang']['backendlang']['Stock_Balance'] :'' }}
					</th>
					<th>{{ isset($data['backendlang']['backendlang']['Stock_Withdrawal_Status']) ? $data['backendlang']['backendlang']['Stock_Withdrawal_Status'] :'' }}
					</th>
					<th>{{ isset($data['backendlang']['backendlang']['Updated_Date_And_Time']) ? $data['backendlang']['backendlang']['Updated_Date_And_Time'] :'' }}
					</th>
					<th></th>
					<!-- <th>Action</th> -->
				</tr>
			</thead>
			<tbody>
				@php
					$totalWithdrawal = 0;
					$totalCashBalance = 0;
				@endphp
				@if(!$withdrawal_stock->isEmpty())
				@foreach($withdrawal_stock as $key => $transaction)
				<tr>
					<td>
						{{ $key+1 }}
						<input type="hidden" name="tid" value="{{ $transaction->id }}">
					</td>
					<td>{{ $transaction->created_at }}</td>
					<td>{{ $transaction->transaction_no }}</td>
					<td>{{ $transaction->f_name }}</td>
					<td>
						{{ $transaction->product_name }}
						@if(!empty($transaction->variation_name))
						<br>
						Option: {{ $transaction->variation_name }}
						@endif
						@if(!empty($transaction->second_variation_name))
						<br>
						Second Option: {{ $transaction->second_variation_name }}
						@endif
					</td>
					<td>
						{{ $transaction->quantity }}
					</td>
					<td>
						{{ !empty($ProductBalanceLeft[$transaction->id]) ? $ProductBalanceLeft[$transaction->id] : '0' }}
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
					<td>{{ $transaction->updated_at }}</td>
					<td>
						
						@if($transaction->status != '97')
						<div class="btn-group">
						  	<button type="button" class="btn btn-outline-danger btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
						    	{{ isset($data['backendlang']['backendlang']['Action']) ? $data['backendlang']['backendlang']['Action'] :'' }} <span class="caret"></span>
						  	</button>
						  	<ul class="dropdown-menu" role="menu">
							  	@if($transaction->status == '1')
							  		<li><a href="#" data-id="97" class="change_action">{{ isset($data['backendlang']['backendlang']['Cancel_This_Withdrawal']) ? $data['backendlang']['backendlang']['Cancel_This_Withdrawal'] :'' }}</a></li>
							  	@else
							  		<li>
								    	<li><a href="#" data-id="1" class="change_action">{{ isset($data['backendlang']['backendlang']['Approve']) ? $data['backendlang']['backendlang']['Approve'] :'' }}</a></li>
								    </li>
								    
								    <li><a href="#" data-id="98" class="change_action">{{ isset($data['backendlang']['backendlang']['Reject']) ? $data['backendlang']['backendlang']['Reject'] :'' }}</a></li>
								@endif
						  	</ul>
						</div>
						@endif
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
				@endforeach
				@else
				<tr>
					<td colspan="12">{{ isset($data['backendlang']['backendlang']['No_Result_Found']) ? $data['backendlang']['backendlang']['No_Result_Found'] :'' }}</td>
				</tr>
				@endif
			</tbody>
		</table>
		{{ $withdrawal_stock->links() }}
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
	        	<input type="file" name="uploadSlip" accept="image/jpeg,image/gif,image/png,application/pdf" required>
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
	// $('input[name=dates]').daterangepicker({
	// 	'applyClass' : 'btn-sm btn-success',
	// 	'cancelClass' : 'btn-sm btn-outline-danger',
	// 	locale: {
	// 		applyLabel: 'Apply',
	// 		cancelLabel: 'Cancel',
	// 	}
	// })
	// .prev().on(ace.click_event, function(){
	// 	$(this).next().focus();
	// });
	
	$('.change_action').click( function(){
		var ele = $(this);
		var action_id = $(this).data('id');
		var tid = $(this).closest('tr').find('input[name="tid"]').val();
		var fd = new FormData();
		fd.append('action_id', action_id);
		fd.append('tid', tid);


		if(action_id == '1'){
			var confirmMessage = confirm('{{ isset($data['backendlang']['backendlang']['Approve_This_Withdrawal']) ? $data['backendlang']['backendlang']['Approve_This_Withdrawal'] :'' }}');
		}else if(action_id == '95'){
			var confirmMessage = confirm('{{ isset($data['backendlang']['backendlang']['Cancel_This_Withdrawal']) ? $data['backendlang']['backendlang']['Cancel_This_Withdrawal'] :'' }} ');
		}else if(action_id == '98'){
			var confirmMessage = confirm('{{ isset($data['backendlang']['backendlang']['Reject_This_Transaction']) ? $data['backendlang']['backendlang']['Reject_This_Transaction'] :'' }}');
		}

		if(confirmMessage == true){
			$.ajax({
		       url: '{{ route("change_withdrawal_stock") }}',
		       type: 'post',
		       data: fd,
		       contentType: false,
		       processData: false,
		       success: function(response){
		       		if(response == 1){
		       			toastr.error('此用户余额不足');
		       		}else{
			       		toastr.success('{{ isset($data['backendlang']['backendlang']['Update_Successful']) ? $data['backendlang']['backendlang']['Update_Successful'] :'' }}');
			       		if(action_id == '1'){
			       			ele.closest('tr').find('.status_id').html('<span class="badge bg-success">{{ isset($data['backendlang']['backendlang']['Approved']) ? $data['backendlang']['backendlang']['Approved'] :'' }}</span>');
			       		}else if(action_id == '97'){
			       			ele.closest('tr').find('.status_id').html('<span class="badge bg-danger">{{ isset($data['backendlang']['backendlang']['Cancelled']) ? $data['backendlang']['backendlang']['Cancelled'] :'' }}</span>');
			       		}else{
			       			ele.closest('tr').find('.status_id').html('<span class="badge bg-danger">{{ isset($data['backendlang']['backendlang']['Rejected']) ? $data['backendlang']['backendlang']['Rejected'] :'' }}</span>');
			       		}
			       	}
		    	},
			});			
		}else{
			
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

	$('.select_all_withdrawal').click( function(){
		$('.select_withdrawal').prop('checked', this.checked);
		checkApproveChecked();
	});

	$('.select_withdrawal').click( function(){
		checkApproveChecked();
	});

	$('.approve-btn').click(function(e){
		var arrayA = [];
		$('.select_withdrawal:checked').each(function () {
            var sThisVal = (this.checked ? $(this).data('id') : "");
            arrayA.push(sThisVal);
        });

		var fd = new FormData();
			fd.append('arrayA', arrayA);

		$.ajax({
	       url: '{{ route("ApproveAllWithdrawal") }}',
	       type: 'post',
	       data: fd,
	       contentType: false,
	       processData: false,
	       success: function(response){
	       		// alert(response);
	       		toastr.success('{{ isset($data['backendlang']['backendlang']['Update_Successful']) ? $data['backendlang']['backendlang']['Update_Successful'] :'' }}');
	       		window.reload();
	    	},
		});
	});

	function checkApproveChecked()
	{
		var total = $('.select_withdrawal').length;
		var check = $('.select_withdrawal:checked').length;
		
		if(check > 0){
			if(total == check){
				$('.select_all_withdrawal').prop('checked', true);
			}else{
				$('.select_all_withdrawal').prop('checked', false);
			}
			$('.approve-btn').removeAttr("disabled");
		}else{
			$('.approve-btn').attr("disabled", "disabled");
			$('.select_all_withdrawal').prop('checked', false);
		}
	}
</script>
@endsection