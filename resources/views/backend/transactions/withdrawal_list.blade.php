@extends('layouts.admin_app')

@section('content')
<div class="container-box form-group">
	<h3>{{ isset($data['backendlang']['backendlang']['Filter']) ? $data['backendlang']['backendlang']['Filter'] :'' }}</h3>
	<hr>
	<form action="{{ route('withdrawal_list') }}" method="GET">
		<div class="row">
			<div class="col-sm-2">
				<div class="form-group">
					<input type="text" class="form-control" name="dates" value="{{ !empty(request('dates')) ? request('dates') : $startDate.' - '.$endDate }}">
				</div>
			</div>
			<div class="col-sm-2">
				<div class="form-group">
					<input type="text" class="form-control" name="withdrawal_no" value="{{ !empty(request('withdrawal_no')) ? request('withdrawal_no') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Withdrawal_No']) ? $data['backendlang']['backendlang']['Search_Withdrawal_No'] :'' }}">
				</div>
			</div>

			<div class="col-sm-2">
				<div class="form-group">
					<input type="text" class="form-control" name="agent_name" value="{{ !empty(request('agent_name')) ? request('agent_name') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Withdrawal_No']) ? $data['backendlang']['backendlang']['Search_Withdrawal_No'] :'' }}">
				</div>
			</div>

			<div class="col-sm-2">
				<div class="form-group">
					<input type="text" class="form-control" name="bank_name" value="{{ !empty(request('bank_name')) ? request('bank_name') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Bank_Name']) ? $data['backendlang']['backendlang']['Search_Bank_Name'] :'' }}">
				</div>
			</div>

			<div class="col-sm-2">
				<div class="form-group">
					<input type="text" class="form-control" name="bank_holder" value="{{ !empty(request('bank_holder')) ? request('bank_holder') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Bank_Holder_Name']) ? $data['backendlang']['backendlang']['Search_Bank_Holder_Name'] :'' }}">
				</div>
			</div>

			<div class="col-sm-2">
				<div class="form-group">
					<input type="text" class="form-control" name="bank_account" value="{{ !empty(request('bank_account')) ? request('bank_account') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Bank_Account']) ? $data['backendlang']['backendlang']['Search_Bank_Account'] :'' }}">
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
						<option {{ (!empty(request('per_page')) && request('per_page') == '10') ? 'selected' : '' }} value="10">10</option>
						<option {{ (!empty(request('per_page')) && request('per_page') == '20') ? 'selected' : '' }} value="20">20</option>
						<option {{ (!empty(request('per_page')) && request('per_page') == '50') ? 'selected' : '' }} value="50">50</option>
						</select>
					</div>
				</div>
			</div>
		</div>
		<div class="">
			<div class="row">
				<div class="col-sm-12">
					<div class="form-group">
						<button class="btn btn-outline-primary btn-sm">
							<i class="bi bi-search"></i> {{ isset($data['backendlang']['backendlang']['Search']) ? $data['backendlang']['backendlang']['Search'] :'' }}
						</button>
						<a href="{{ route('withdrawal_list') }}" class="btn btn-warning btn-sm">
							<i class="bi bi-arrow-clockwise"></i> {{ isset($data['backendlang']['backendlang']['Clear_Search']) ? $data['backendlang']['backendlang']['Clear_Search'] :'' }}
						</a>
						<a href="{{ route('withdrawal_list',['dates='.(!empty(request('dates')) ? request('dates') : $startDate.' - '.$endDate),
												 'withdrawal_no='.(!empty(request('withdrawal_no')) ? request('withdrawal_no') : ''),
												 'agent_name='.(!empty(request('agent_name')) ? request('agent_name') : '' )]) }}" class="btn btn-success btn-sm approve-btn" disabled>
							<i class="bi bi-check"></i> {{ isset($data['backendlang']['backendlang']['Approve']) ? $data['backendlang']['backendlang']['Approve'] :'' }}
						</a>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
<div class="container-box form-group">
	<div class="form-group" align="right">
		<a href="{{ route('print_withdrawal_list', ['dates='.(!empty(request('dates')) ? request('dates') : $startDate.' - '.$endDate),
												 'withdrawal_no='.(!empty(request('withdrawal_no')) ? request('withdrawal_no') : ''),
												 'status='.(!empty(request('status')) ? request('status') : ''),
												 'agent_name='.(!empty(request('agent_name')) ? request('agent_name') : '' )]) }}" 
												 class="print-window btn btn-outline-primary" target="_blank">
			<i class="bi bi-print"></i> {{ isset($data['backendlang']['backendlang']['Print']) ? $data['backendlang']['backendlang']['Print'] :'' }}
		</a>
		<a href="{{ route('exportWithdrawalReport', ['dates='.(!empty(request('dates')) ? request('dates') : $startDate.' - '.$endDate),
										 'withdrawal_no='.(!empty(request('withdrawal_no')) ? request('withdrawal_no') : ''),
										 'status='.(!empty(request('status')) ? request('status') : ''),
										 'agent_name='.(!empty(request('agent_name')) ? request('agent_name') : '' ) ]) }}" target="_blank" class="btn btn-warning">
			<i class="bi bi-download"></i> {{ isset($data['backendlang']['backendlang']['Export']) ? $data['backendlang']['backendlang']['Export'] :'' }}
		</a>
	</div>
	<div class="row" style="overflow: auto; padding-bottom: 50px;">
		<div class="col-12">
			{{ $transactions->links() }}
			<table class="table table-bordered">
				<thead>
					<tr class="info">
						<th>
							#
							<input type="checkbox" name="select_all_withdrawal[]" class="select_all_withdrawal">
						</th>
						<th>{{ isset($data['backendlang']['backendlang']['Date_And_Time']) ? $data['backendlang']['backendlang']['Date_And_Time'] :'' }}
							@if(empty(request('created_desc')) && empty(request('created_asc')))
								<a href="{{ route('withdrawal_list', ['created_desc=DESC']) }}" 
								   class="{{ !empty(request('created_desc')) ? 'selected' : '' }}">
									<i class="bi bi-sort-down"></i>
									<input type="hidden" name="sort_data" value="0">
								</a>
							@else
								@if(!empty(request('created_desc')))
									<a href="{{ route('withdrawal_list', ['created_asc=ASC']) }}" 
									   class="{{ !empty(request('created_asc')) ? 'selected' : '' }}">
										<i class="bi bi-sort-down"></i>
										<input type="hidden" name="sort_data" value="1">
									</a>
								@elseif(!empty(request('created_asc')))
									<a href="{{ route('withdrawal_list', ['created_desc=DESC']) }}" 
									   class="{{ !empty(request('created_desc')) ? 'selected' : '' }}">
										<i class="bi bi-sort-up"></i>
										<input type="hidden" name="sort_data" value="0">
									</a>
								@endif
							@endif
						</th>
						<th>{{ isset($data['backendlang']['backendlang']['Withdrawal_No']) ? $data['backendlang']['backendlang']['Withdrawal_No'] :'' }}.
							@if(empty(request('withdrawal_desc')) && empty(request('withdrawal_asc')))
								<a href="{{ route('withdrawal_list', ['withdrawal_desc=DESC']) }}" 
								   class="{{ !empty(request('withdrawal_desc')) ? 'selected' : '' }}">
									<i class="bi bi-sort-down"></i>
									<input type="hidden" name="sort_data" value="0">
								</a>
							@else
								@if(!empty(request('withdrawal_desc')))
									<a href="{{ route('withdrawal_list', ['withdrawal_asc=ASC']) }}" 
									   class="{{ !empty(request('withdrawal_asc')) ? 'selected' : '' }}">
										<i class="bi bi-sort-down"></i>
										<input type="hidden" name="sort_data" value="1">
									</a>
								@elseif(!empty(request('withdrawal_asc')))
									<a href="{{ route('withdrawal_list', ['withdrawal_desc=DESC']) }}" 
									   class="{{ !empty(request('withdrawal_desc')) ? 'selected' : '' }}">
										<i class="bi bi-sort-up"></i>
										<input type="hidden" name="sort_data" value="0">
									</a>
								@endif
							@endif
						</th>
						<th>{{ isset($data['backendlang']['backendlang']['Agent_Name']) ? $data['backendlang']['backendlang']['Agent_Name'] :'' }}
							@if(empty(request('name_desc')) && empty(request('name_asc')))
								<a href="{{ route('withdrawal_list', ['name_desc=DESC']) }}" 
								   class="{{ !empty(request('name_desc')) ? 'selected' : '' }}">
									<i class="bi bi-sort-down"></i>
									<input type="hidden" name="sort_data" value="0">
								</a>
							@else
								@if(!empty(request('name_desc')))
									<a href="{{ route('withdrawal_list', ['name_asc=ASC']) }}" 
									   class="{{ !empty(request('name_asc')) ? 'selected' : '' }}">
										<i class="bi bi-sort-down"></i>
										<input type="hidden" name="sort_data" value="1">
									</a>
								@elseif(!empty(request('name_asc')))
									<a href="{{ route('withdrawal_list', ['name_desc=DESC']) }}" 
									   class="{{ !empty(request('name_desc')) ? 'selected' : '' }}">
										<i class="bi bi-sort-up"></i>
										<input type="hidden" name="sort_data" value="0">
									</a>
								@endif
							@endif
						</th>
						<th>{{ isset($data['backendlang']['backendlang']['Amount']) ? $data['backendlang']['backendlang']['Amount'] :'' }}
							@if(empty(request('amount_desc')) && empty(request('amount_asc')))
								<a href="{{ route('withdrawal_list', ['amount_desc=DESC']) }}" 
								   class="{{ !empty(request('amount_desc')) ? 'selected' : '' }}">
									<i class="bi bi-sort-down"></i>
									<input type="hidden" name="sort_data" value="0">
								</a>
							@else
								@if(!empty(request('amount_desc')))
									<a href="{{ route('withdrawal_list', ['amount_asc=ASC']) }}" 
									   class="{{ !empty(request('amount_asc')) ? 'selected' : '' }}">
										<i class="bi bi-sort-down"></i>
										<input type="hidden" name="sort_data" value="1">
									</a>
								@elseif(!empty(request('amount_asc')))
									<a href="{{ route('withdrawal_list', ['amount_desc=DESC']) }}" 
									   class="{{ !empty(request('amount_desc')) ? 'selected' : '' }}">
										<i class="bi bi-sort-up"></i>
										<input type="hidden" name="sort_data" value="0">
									</a>
								@endif
							@endif
						</th>
						<!-- <th>Wallet Balance
							<br>
							@if(empty(request('cash_wallet_desc')) && empty(request('cash_wallet_asc')))
								<a href="{{ route('withdrawal_list', ['cash_wallet_desc=DESC']) }}" 
								   class="{{ !empty(request('cash_wallet_desc')) ? 'selected' : '' }}">
									<i class="bi bi-sort-down"></i>
									<input type="hidden" name="sort_data" value="0">
								</a>
							@else
								@if(!empty(request('cash_wallet_desc')))
									<a href="{{ route('withdrawal_list', ['cash_wallet_asc=ASC']) }}" 
									   class="{{ !empty(request('cash_wallet_asc')) ? 'selected' : '' }}">
										<i class="bi bi-sort-down"></i>
										<input type="hidden" name="sort_data" value="1">
									</a>
								@elseif(!empty(request('cash_wallet_asc')))
									<a href="{{ route('withdrawal_list', ['cash_wallet_desc=DESC']) }}" 
									   class="{{ !empty(request('cash_wallet_desc')) ? 'selected' : '' }}">
										<i class="bi bi-sort-up"></i>
										<input type="hidden" name="sort_data" value="0">
									</a>
								@endif
							@endif
						</th> -->
						<th>{{ isset($data['backendlang']['backendlang']['Bank_Name']) ? $data['backendlang']['backendlang']['Bank_Name'] :'' }}
							@if(empty(request('bank_name_desc')) && empty(request('bank_name_asc')))
								<a href="{{ route('withdrawal_list', ['bank_name_desc=DESC']) }}" 
								   class="{{ !empty(request('bank_name_desc')) ? 'selected' : '' }}">
									<i class="bi bi-sort-down"></i>
									<input type="hidden" name="sort_data" value="0">
								</a>
							@else
								@if(!empty(request('bank_name_desc')))
									<a href="{{ route('withdrawal_list', ['bank_name_asc=ASC']) }}" 
									   class="{{ !empty(request('bank_name_asc')) ? 'selected' : '' }}">
										<i class="bi bi-sort-down"></i>
										<input type="hidden" name="sort_data" value="1">
									</a>
								@elseif(!empty(request('bank_name_asc')))
									<a href="{{ route('withdrawal_list', ['bank_name_desc=DESC']) }}" 
									   class="{{ !empty(request('bank_name_desc')) ? 'selected' : '' }}">
										<i class="bi bi-sort-up"></i>
										<input type="hidden" name="sort_data" value="0">
									</a>
								@endif
							@endif
						</th>
						<th>{{ isset($data['backendlang']['backendlang']['Bank_Holder_Name']) ? $data['backendlang']['backendlang']['Bank_Holder_Name'] :'' }}
							@if(empty(request('agent_name_desc')) && empty(request('agent_name_asc')))
								<a href="{{ route('withdrawal_list', ['agent_name_desc=DESC']) }}" 
								   class="{{ !empty(request('agent_name_desc')) ? 'selected' : '' }}">
									<i class="bi bi-sort-down"></i>
									<input type="hidden" name="sort_data" value="0">
								</a>
							@else
								@if(!empty(request('agent_name_desc')))
									<a href="{{ route('withdrawal_list', ['agent_name_asc=ASC']) }}" 
									   class="{{ !empty(request('agent_name_asc')) ? 'selected' : '' }}">
										<i class="bi bi-sort-down"></i>
										<input type="hidden" name="sort_data" value="1">
									</a>
								@elseif(!empty(request('agent_name_asc')))
									<a href="{{ route('withdrawal_list', ['agent_name_desc=DESC']) }}" 
									   class="{{ !empty(request('agent_name_desc')) ? 'selected' : '' }}">
										<i class="bi bi-sort-up"></i>
										<input type="hidden" name="sort_data" value="0">
									</a>
								@endif
							@endif
						</th>
						<th>{{ isset($data['backendlang']['backendlang']['Bank_Account']) ? $data['backendlang']['backendlang']['Bank_Account'] :'' }}
							@if(empty(request('account_desc')) && empty(request('account_asc')))
								<a href="{{ route('withdrawal_list', ['account_desc=DESC']) }}" 
								   class="{{ !empty(request('account_desc')) ? 'selected' : '' }}">
									<i class="bi bi-sort-down"></i>
									<input type="hidden" name="sort_data" value="0">
								</a>
							@else
								@if(!empty(request('account_desc')))
									<a href="{{ route('withdrawal_list', ['account_asc=ASC']) }}" 
									   class="{{ !empty(request('account_asc')) ? 'selected' : '' }}">
										<i class="bi bi-sort-down"></i>
										<input type="hidden" name="sort_data" value="1">
									</a>
								@elseif(!empty(request('account_asc')))
									<a href="{{ route('withdrawal_list', ['account_desc=DESC']) }}" 
									   class="{{ !empty(request('account_desc')) ? 'selected' : '' }}">
										<i class="bi bi-sort-up"></i>
										<input type="hidden" name="sort_data" value="0">
									</a>
								@endif
							@endif
						</th>
						<th>{{ isset($data['backendlang']['backendlang']['Bank_Slip']) ? $data['backendlang']['backendlang']['Bank_Slip'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Status']) ? $data['backendlang']['backendlang']['Status'] :'' }}
							@if(empty(request('status_desc')) && empty(request('status_asc')))
								<a href="{{ route('withdrawal_list', ['status_desc=DESC']) }}" 
								   class="{{ !empty(request('status_desc')) ? 'selected' : '' }}">
									<i class="bi bi-sort-down"></i>
									<input type="hidden" name="sort_data" value="0">
								</a>
							@else
								@if(!empty(request('status_desc')))
									<a href="{{ route('withdrawal_list', ['status_asc=ASC']) }}" 
									   class="{{ !empty(request('status_asc')) ? 'selected' : '' }}">
										<i class="bi bi-sort-down"></i>
										<input type="hidden" name="sort_data" value="1">
									</a>
								@elseif(!empty(request('status_asc')))
									<a href="{{ route('withdrawal_list', ['status_desc=DESC']) }}" 
									   class="{{ !empty(request('status_desc')) ? 'selected' : '' }}">
										<i class="bi bi-sort-up"></i>
										<input type="hidden" name="sort_data" value="0">
									</a>
								@endif
							@endif
						</th>
						<th>{{ isset($data['backendlang']['backendlang']['Updated_Date_And_Time']) ? $data['backendlang']['backendlang']['Updated_Date_And_Time'] :'' }}
							@if(empty(request('updated_desc')) && empty(request('updated_asc')))
								<a href="{{ route('withdrawal_list', ['updated_desc=DESC']) }}" 
								   class="{{ !empty(request('updated_desc')) ? 'selected' : '' }}">
									<i class="bi bi-sort-down"></i>
									<input type="hidden" name="sort_data" value="0">
								</a>
							@else
								@if(!empty(request('updated_desc')))
									<a href="{{ route('withdrawal_list', ['updated_asc=ASC']) }}" 
									   class="{{ !empty(request('updated_asc')) ? 'selected' : '' }}">
										<i class="bi bi-sort-down"></i>
										<input type="hidden" name="sort_data" value="1">
									</a>
								@elseif(!empty(request('updated_asc')))
									<a href="{{ route('withdrawal_list', ['updated_desc=DESC']) }}" 
									   class="{{ !empty(request('updated_desc')) ? 'selected' : '' }}">
										<i class="bi bi-sort-up"></i>
										<input type="hidden" name="sort_data" value="0">
									</a>
								@endif
							@endif
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
					@if(!$transactions->isEmpty())
					@foreach($transactions as $key => $transaction)
					<tr>
						<td>
							{{ $key+1 }}
							<input type="hidden" name="tid" value="{{ $transaction->id }}">
							<input type="checkbox" name="select_withdrawal[]" class="select_withdrawal" data-id="{{ $transaction->id }}">
						</td>
						<td>{{ $transaction->created_at }}</td>
						<td>{{ $transaction->withdrawal_no }}</td>
						<td>{{ $transaction->agent_name }}</td>
						<td>{{ number_format($transaction->amount, 2) }}</td>
						<!-- <td>{{ !empty($GetWalletBalance[$transaction->withdrawal_no]) ? number_format($GetWalletBalance[$transaction->withdrawal_no], 2) : '0.00' }}</td> -->
						<td>{{ $transaction->bank_name }}</td>
						<td>{{ $transaction->bank_holder_name }}</td>
						<td>{{ $transaction->bank_account }}</td>
						<td>
							@if(!empty($transaction->withdrawal_slip))
							@php
				                $ex = explode('.',$transaction->withdrawal_slip);
				                $end = end($ex);
				            @endphp
										<a href="#" data-toggle="modal" data-target="#myModala{{ $transaction->id }}">
											<img src="{{ asset($transaction->withdrawal_slip) }}" width="30%">
										</a>
										<div class="modal fade" id="myModala{{ $transaction->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" >
										  <div class="modal-dialog" role="document">
										    <div class="modal-content">
										        <div class="modal-body">
										        	<img src="{{ asset($transaction->withdrawal_slip) }}" width="100%">
										        </div>
										    </div>
										  </div>
										</div>
									@else
									<i class="bi bi-minus"></i>
									
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
						<td>{{ $transaction->updated_at }}</td>
						<td>
							
							@if($transaction->status != '97')
							<div class="btn-group">
							  	<button type="button" class="btn btn-outline-danger btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
							    	{{ isset($data['backendlang']['backendlang']['Action']) ? $data['backendlang']['backendlang']['Action'] :'' }} <span class="caret"></span>
							  	</button>
							  	<ul class="dropdown-menu" role="menu">
								  	@if($transaction->status == '1')
								  		<li><a href="#" data-id="95" class="change_action">{{ isset($data['backendlang']['backendlang']['Cancel_This_Withdrawal']) ? $data['backendlang']['backendlang']['Cancel_This_Withdrawal'] :'' }}</a></li>
								  	@else
								  		<li>
									    	<li><a href="#" data-id="1" class="change_action">{{ isset($data['backendlang']['backendlang']['Approve']) ? $data['backendlang']['backendlang']['Approve'] :'' }}</a></li>
									    </li>
									    
									    <li><a href="#" data-id="98" class="change_action">{{ isset($data['backendlang']['backendlang']['Reject']) ? $data['backendlang']['backendlang']['Reject'] :'' }}</a></li>
									@endif
							  	</ul>
							</div>
							@endif

							@if($transaction->status == '1')
							
							@endif

							<button class="btn btn-outline-primary btn-sm upload-slip" data-toggle="modal" data-target="#myModal" data-id="{{ $transaction->id }}">
								{{ isset($data['backendlang']['backendlang']['Upload_Bank_Slip']) ? $data['backendlang']['backendlang']['Upload_Bank_Slip'] :'' }}
							</button>
						</td>
						<!-- <td>
							<a href="">
								<i class="ace-icon bi bi-pencil bigger-130"></i>
							</a>
							&nbsp;&nbsp;
							<a href="#" class="red">
								<i class="ace-icon bi bi-trash-o bigger-130"></i>
							</a>
						</td> -->
					</tr>
					@php
						$totalWithdrawal += $transaction->amount;
						$totalCashBalance += $GetWalletBalance[$transaction->withdrawal_no];
					@endphp
					@endforeach
					<tr class="warning">
						<td colspan="4">
							{{ isset($data['backendlang']['backendlang']['Summary']) ? $data['backendlang']['backendlang']['Summary'] :'' }}
						</td>
						<td>
							{{ number_format($totalWithdrawal,2) }}
						</td>
						<td>
							<!-- {{ $totalCashBalance }} -->
						</td>
						<td colspan="7">
							
						</td>
					</tr>
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
	$('input[name=dates]').daterangepicker({
		'applyClass' : 'btn-sm btn-success',
		'cancelClass' : 'btn-sm btn-outline-danger',
		locale: {
			applyLabel: '{{ isset($data['backendlang']['backendlang']['Apply']) ? $data['backendlang']['backendlang']['Apply'] :'' }}',
			cancelLabel: '{{ isset($data['backendlang']['backendlang']['Cancel']) ? $data['backendlang']['backendlang']['Cancel'] :'' }}',
			format: 'DD/MM/YYYY',
		}
	})
	
	$('.change_action').click( function(){
		var ele = $(this);
		$('.loading-gif').show();
		var action_id = $(this).data('id');
		var tid = $(this).closest('tr').find('input[name="tid"]').val();
		var fd = new FormData();
		fd.append('action_id', action_id);
		fd.append('tid', tid);


		if(action_id == '1'){
			var confirmMessage = confirm('{{ isset($data['backendlang']['backendlang']['Approve_This_Withdrawal']) ? $data['backendlang']['backendlang']['Approve_This_Withdrawal'] :'' }}');
		}else if(action_id == '95'){
			var confirmMessage = confirm('{{ isset($data['backendlang']['backendlang']['Cancel_This_Withdrawal']) ? $data['backendlang']['backendlang']['Cancel_This_Withdrawal'] :'' }}');
		}else if(action_id == '98'){
			var confirmMessage = confirm('{{ isset($data['backendlang']['backendlang']['Reject_This_Withdrawal']) ? $data['backendlang']['backendlang']['Reject_This_Withdrawal'] :'' }}');
		}

		if(confirmMessage == true){
			$.ajax({
		       url: '{{ route("change_withdrawal_transaction_action") }}',
		       type: 'post',
		       data: fd,
		       contentType: false,
		       processData: false,
		       success: function(response){
		       		if(response == 1){
		       			toastr.error('此用户余额不足');
						$('.loading-gif').hide();
		       		}else{
			       		toastr.success('{{ isset($data['backendlang']['backendlang']['Update_Successful']) ? $data['backendlang']['backendlang']['Update_Successful'] :'' }}');
			       		if(action_id == '1'){
			       			ele.closest('tr').find('.status_id').html('<span class="badge bg-success">{{ isset($data['backendlang']['backendlang']['Approved']) ? $data['backendlang']['backendlang']['Approved'] :'' }}</span>');
			       		}else if(action_id == '97'){
			       			ele.closest('tr').find('.status_id').html('<span class="badge bg-danger">{{ isset($data['backendlang']['backendlang']['Cancelled']) ? $data['backendlang']['backendlang']['Cancelled'] :'' }}</span>');
			       		}else{
			       			ele.closest('tr').find('.status_id').html('<span class="badge bg-danger">{{ isset($data['backendlang']['backendlang']['Rejected']) ? $data['backendlang']['backendlang']['Rejected'] :'' }}</span>');
			       		}
						location.reload();
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