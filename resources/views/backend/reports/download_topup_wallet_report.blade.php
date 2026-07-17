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
			<b>{{ isset($data['backendlang']['backendlang']['topup_wallet_report']) ? $data['backendlang']['backendlang']['topup_wallet_report'] :'' }}</b>
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
		<tr class="success">
			<th>#</th>
			<th>{{ isset($data['backendlang']['backendlang']['User_Name']) ? $data['backendlang']['backendlang']['User_Name'] :'' }}</th>
			<th>{{ isset($data['backendlang']['backendlang']['User_Code']) ? $data['backendlang']['backendlang']['User_Code'] :'' }}</th>
			<th>{{ isset($data['backendlang']['backendlang']['User_Type']) ? $data['backendlang']['backendlang']['User_Type'] :'' }}</th>
			<th>{{ isset($data['backendlang']['backendlang']['Previous_Month_Balance_Amount']) ? $data['backendlang']['backendlang']['Previous_Month_Balance_Amount'] :'' }}</th>
			<th>{{ isset($data['backendlang']['backendlang']['Wallet_In_Amount']) ? $data['backendlang']['backendlang']['Wallet_In_Amount'] :'' }}</th>
			<th>{{ isset($data['backendlang']['backendlang']['Wallet_Out_Amount']) ? $data['backendlang']['backendlang']['Wallet_Out_Amount'] :'' }}</th>
            <th>{{ isset($data['backendlang']['backendlang']['Current_Balance_Amount']) ? $data['backendlang']['backendlang']['Current_Balance_Amount'] :'' }}</th>
		</tr>
	</thead>
	<tbody>
		@php
			$a=0;
		@endphp
		@if(!$topupWallets->isEmpty())
		@foreach($topupWallets as $topupWallet)
		<tr>
			<td>{{ $a+1 }}</td>
			<td>
				@if($topupWallet->status != '3')
					{{ $topupWallet->userName }}
				@else
					{{ $topupWallet->userName }} - {{ isset($data['backendlang']['backendlang']['deleted']) ? $data['backendlang']['backendlang']['deleted'] :'' }}
				@endif
			</td>
			<td>
				@if($topupWallet->status != '3') 
				{{ $topupWallet->userCode }}
				@else
				{{ $topupWallet->userCode }} - {{ isset($data['backendlang']['backendlang']['deleted']) ? $data['backendlang']['backendlang']['deleted'] :'' }}
				@endif
			</td>
			<td>
				{{$topupWallet->user_type}}
			</td>
			<td>
				{{ number_format($previous_balance[$topupWallet->userCode], 2) }}
			</td>
			<td>
			    {{ number_format($total_wallet_in[$topupWallet->userCode], 2) }}
			</td>
			<td>
                {{ number_format($total_wallet_out[$topupWallet->userCode], 2) }}
			</td>
			<td>
				{{ number_format($current_balance[$topupWallet->userCode], 2) }}
			</td>
		</tr>
		@php
			$a++;
		@endphp
		@endforeach
		@else
		<tr>
			<td colspan="5">{{ isset($data['backendlang']['backendlang']['No_Result_Found']) ? $data['backendlang']['backendlang']['No_Result_Found'] :'' }}</td>
		</tr>
		@endif
	</tbody>
</table>