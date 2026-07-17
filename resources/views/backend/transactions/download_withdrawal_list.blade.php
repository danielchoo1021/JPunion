<table>
	<tr>
		<th><b>{{ !empty($data['web_setting']->invoice_name) ? $data['web_setting']->invoice_name : $data['admin']->company_name }}</b></th>
		<th></th>
		<th></th>
		<th></th>
		<th></th>
		<th></th>
		<th></th>
		<th></th>
		<th align="right">
			<b>{{ isset($data['backendlang']['backendlang']['Withdrawal_List_Report']) ? $data['backendlang']['backendlang']['Withdrawal_List_Report'] :'' }}</b>
		</th>
	</tr>
	<tr>
		<th>
			{{ isset($data['backendlang']['backendlang']['print_date']) ? $data['backendlang']['backendlang']['print_date'] :'' }}: {{ date('Y-m-d H:i:s') }}
		</th>
		<th></th>
		<th></th>
		<th></th>
		<th></th>
		<th></th>
		<th></th>
		<th></th>
		<th align="right">
			{{ isset($data['backendlang']['backendlang']['report_date']) ? $data['backendlang']['backendlang']['report_date'] :'' }}: {{ $start }} - {{ $end }}
		</th> 
	</tr>
</table>
{{-- <table class="table table-bordered">
	<thead>
		<tr class="info">
			<th>
				#
			</th>
			<th>{{ isset($data['backendlang']['backendlang']['Withdrawal_No']) ? $data['backendlang']['backendlang']['Withdrawal_No'] :'' }}</th>
			<th>{{ isset($data['backendlang']['backendlang']['Agent']) ? $data['backendlang']['backendlang']['Agent'] :'' }}</th>
			<th>{{ isset($data['backendlang']['backendlang']['Withdrawal_Amount']) ? $data['backendlang']['backendlang']['Withdrawal_Amount'] :'' }}</th>
			<!-- <th>Cash Wallet Balance</th> -->
			<th>{{ isset($data['backendlang']['backendlang']['Bank_Name']) ? $data['backendlang']['backendlang']['Bank_Name'] :'' }}</th>
			<th>{{ isset($data['backendlang']['backendlang']['Bank_Holder_Name']) ? $data['backendlang']['backendlang']['Bank_Holder_Name'] :'' }}</th>
			<th>{{ isset($data['backendlang']['backendlang']['Bank_Account']) ? $data['backendlang']['backendlang']['Bank_Account'] :'' }}</th>
			<th>{{ isset($data['backendlang']['backendlang']['Status']) ? $data['backendlang']['backendlang']['Status'] :'' }}</th>
			<th>{{ isset($data['backendlang']['backendlang']['Created_At']) ? $data['backendlang']['backendlang']['Created_At'] :'' }}</th>
			<th>{{ isset($data['backendlang']['backendlang']['Updated_At']) ? $data['backendlang']['backendlang']['Updated_At'] :'' }}</th>
		</tr>
	</thead>
	<tbody>
		
		@if(!$transactions->isEmpty())
		@foreach($transactions as $key => $transaction)
		<tr>
			<td>
				{{ $key+1 }}
			</td>
			<td>{{ $transaction->withdrawal_no }}</td>
			<td>{{ $transaction->agent_name }}</td>
			<td>{{ $transaction->amount }}</td>
			<!-- <td>{{ $GetWalletBalance[$transaction->withdrawal_no] }}</td> -->
			<td>{{ $transaction->bank_name }}</td>
			<td>{{ $transaction->agent_name }}</td>
			<td>{{ $transaction->bank_account }}&#8203;</td>
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
		@endforeach
		@else
		<tr>
			<td colspan="12">{{ isset($data['backendlang']['backendlang']['No_Result_Found']) ? $data['backendlang']['backendlang']['No_Result_Found] :'' }}</td>
		</tr>
		@endif
	</tbody>
</table> --}}

<table class="table table-bordered">
	<thead>
		<tr class="info">
			<th>
				#
			</th>
			<th>
				{{ isset($data['backendlang']['backendlang']['Date_And_Time']) ? $data['backendlang']['backendlang']['Date_And_Time'] :'' }}
			</th>
			<th>
				{{ isset($data['backendlang']['backendlang']['Withdrawal_No']) ? $data['backendlang']['backendlang']['Withdrawal_No'] :'' }}
			</th>
			<th>
				{{ isset($data['backendlang']['backendlang']['Agent_Name']) ? $data['backendlang']['backendlang']['Agent_Name'] :'' }}
			</th>
			<th>
				{{ isset($data['backendlang']['backendlang']['Amount']) ? $data['backendlang']['backendlang']['Amount'] :'' }}
			</th>
			<th>
				{{ isset($data['backendlang']['backendlang']['Bank_Name']) ? $data['backendlang']['backendlang']['Bank_Name'] :'' }}
			</th>
			<th>
				{{ isset($data['backendlang']['backendlang']['Bank_Holder_Name']) ? $data['backendlang']['backendlang']['Bank_Holder_Name'] :'' }}
			</th>
			<th>
				{{ isset($data['backendlang']['backendlang']['Bank_Account']) ? $data['backendlang']['backendlang']['Bank_Account'] :'' }}
			</th>
			<th>
				{{ isset($data['backendlang']['backendlang']['Bank_Slip']) ? $data['backendlang']['backendlang']['Bank_Slip'] :'' }}
			</th>
			<th>
				{{ isset($data['backendlang']['backendlang']['Status']) ? $data['backendlang']['backendlang']['Status'] :'' }}
			</th>
			<th>
				{{ isset($data['backendlang']['backendlang']['Updated_Date_And_Time']) ? $data['backendlang']['backendlang']['Updated_Date_And_Time'] :'' }}
			</th>
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
				</td>
				<td>{{ $transaction->created_at }}</td>
				<td>{{ $transaction->withdrawal_no }}</td>
				<td>{{ $transaction->agent_name }}</td>
				<td>{{ number_format($transaction->amount, 2) }}</td>
				<td>{{ $transaction->bank_name }}</td>
				<td>{{ $transaction->bank_holder_name }}</td>
				<td>{{ $transaction->bank_account }}</td>
				<td>
					@if(!empty($transaction->withdrawal_slip))
						✔
					@else
						-
					@endif
				</td>
				<td class="status_id">
					@if($transaction->status == '1')
						{{ isset($data['backendlang']['backendlang']['Approved']) ? $data['backendlang']['backendlang']['Approved'] :'' }}
					@elseif($transaction->status == '99')
						{{ isset($data['backendlang']['backendlang']['Pending']) ? $data['backendlang']['backendlang']['Pending'] :'' }}
					@elseif($transaction->status == '98')
						{{ isset($data['backendlang']['backendlang']['Rejected']) ? $data['backendlang']['backendlang']['Rejected'] :'' }}
					@else
						{{ isset($data['backendlang']['backendlang']['Cancelled']) ? $data['backendlang']['backendlang']['Cancelled'] :'' }}
					@endif
				</td>
				<td>{{ $transaction->updated_at }}</td>
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
				<td colspan="5">
					
				</td>
			</tr>
		@else
		<tr>
			<td colspan="11">{{ isset($data['backendlang']['backendlang']['No_Result_Found']) ? $data['backendlang']['backendlang']['No_Result_Found'] :'' }}</td>
		</tr>
		@endif
	</tbody>
</table>