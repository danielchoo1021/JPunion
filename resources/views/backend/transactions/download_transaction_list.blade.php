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
			<b>{{ isset($data['backendlang']['backendlang']['Transaction_List_Report']) ? $data['backendlang']['backendlang']['Transaction_List_Report'] :'' }}</b>
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
<table class="table table-bordered">
	<thead>
		<tr class="info">
			<th>#</th>
			<th>{{ isset($data['backendlang']['backendlang']['Date']) ? $data['backendlang']['backendlang']['Date'] :'' }}</th>
			<th>{{ isset($data['backendlang']['backendlang']['Transaction_No']) ? $data['backendlang']['backendlang']['Transaction_No'] :'' }}</th>
			@if (empty(request('mall')))
			<th>{{ isset($data['backendlang']['backendlang']['Payment_Method']) ? $data['backendlang']['backendlang']['Payment_Method'] :'' }}</th>
			@endif
			<th>{{ isset($data['backendlang']['backendlang']['Buyer_Name']) ? $data['backendlang']['backendlang']['Buyer_Name'] :'' }}</th>
			<th>{{ isset($data['backendlang']['backendlang']['Total_Amount']) ? $data['backendlang']['backendlang']['Total_Amount'] :'' }}</th>
			<th>{{ isset($data['backendlang']['backendlang']['Status']) ? $data['backendlang']['backendlang']['Status'] :'' }}</th>
			<th>{{ isset($data['backendlang']['backendlang']['Pick_Up']) ? $data['backendlang']['backendlang']['Pick_Up'] :'' }}</th>
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
				<input type="hidden" name="tid" class="tid" value="{{ $transaction->Tid }}">
			</td>
			<td>{{ ($transaction->created_at) }}</td>
			<td>{{ $transaction->transaction_no }}</td>
			@if (empty(request('mall')))
			<td>
				@php
				$payment_method = "";

				if($transaction->mall == null && $transaction->bank_slip == null && !empty($transaction->payment_method)){
					if($transaction->payment_method == 1){
						$payment_method = "POS Payment\n (Cash)";
					}elseif($transaction->payment_method == 2){
						$payment_method = "POS Payment\n (QR Code)";
					}elseif($transaction->payment_method == 3){
						$payment_method = "POS Payment\n (Credit Card / Debit Card)";
					}}elseif($transaction->mall == null && $transaction->bank_slip == null && $transaction->payment_method == null){
						if($transaction->created_backend == 1){
							$payment_method = "Create From Backend (No Bank Slip)";
						}else{
							$payment_method = "Online Banking";
						}
					}elseif($transaction->mall == null && !empty($transaction->bank_slip)){
						if($transaction->created_backend == 1){
							$payment_method = "Bank Slip (Create From Backend)";
						}else{
							$payment_method = "Bank Slip";
						}
					}elseif($transaction->mall == 1){
					$payment_method = "Cash Wallet";
					}elseif($transaction->mall == 2){
					$payment_method = "Topup Wallet";
				}
				@endphp

				{!! nl2br(e($payment_method)) !!}
			</td>
			@endif
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
				<!-- @if(!empty($transaction->on_hold))
				@if($transaction->on_hold == 99)
				<span class="badge bg-info">Awaiting Pick Up</span>
				@elseif($transaction->on_hold == 1)
				<span class="badge bg-success">Collected</span>
				@endif
				@else
				<span class="badge bg-danger">No Pickup</span>
				@endif -->
				@if(!empty($transaction->payment_method))
				@if(empty($transaction->completed))
				<span class="badge bg-info">{{ isset($data['backendlang']['backendlang']['Awaiting_Pickup']) ? $data['backendlang']['backendlang']['Awaiting_Pickup'] :'' }}</span>
				@else
				<span class="badge bg-success">{{ isset($data['backendlang']['backendlang']['Pick_Up']) ? $data['backendlang']['backendlang']['Pick_Up'] :'' }}</span>
				@endif
				@else
				@if($transaction->self_pick == 1 && empty($transaction->completed))
				<span class="badge bg-info">{{ isset($data['backendlang']['backendlang']['Awaiting_Pickup']) ? $data['backendlang']['backendlang']['Awaiting_Pickup'] :'' }}</span>
				@elseif($transaction->self_pick == 1 && $transaction->completed == 1)
				<span class="badge bg-success">{{ isset($data['backendlang']['backendlang']['Pick_Up']) ? $data['backendlang']['backendlang']['Pick_Up'] :'' }}</span>
				@else
				<span class="badge bg-danger">{{ isset($data['backendlang']['backendlang']['No_Pickup']) ? $data['backendlang']['backendlang']['No_Pickup'] :'' }}</span>
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