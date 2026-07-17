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
			<b>{{ isset($data['backendlang']['backendlang']['Commission_Report']) ? $data['backendlang']['backendlang']['Commission_Report'] :'' }}</b>
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
			report_date: {{ $start }} - {{ $end }}
		</th> 
	</tr>
</table>
<table class="table table-bordered">
	<thead>
		<tr class="success">
			<th>#</th>
			<th>{{ isset($data['backendlang']['backendlang']['Commission_Date_Time']) ? $data['backendlang']['backendlang']['Commission_Date_Time'] :'' }}</th>
			<th>{{ isset($data['backendlang']['backendlang']['Commission_Type']) ? $data['backendlang']['backendlang']['Commission_Type'] :'' }}</th>
			<th>{{ isset($data['backendlang']['backendlang']['Transaction_Number']) ? $data['backendlang']['backendlang']['Transaction_Number'] :'' }}</th>
			<th>{{ isset($data['backendlang']['backendlang']['Agent_Name']) ? $data['backendlang']['backendlang']['Agent_Name'] :'' }}</th>
			<th>{{ isset($data['backendlang']['backendlang']['Agent_Code']) ? $data['backendlang']['backendlang']['Agent_Code'] :'' }}</th>
			<th>{{ isset($data['backendlang']['backendlang']['Downline_Name']) ? $data['backendlang']['backendlang']['Downline_Name'] :'' }}</th>
			<th>{{ isset($data['backendlang']['backendlang']['Downline_Code']) ? $data['backendlang']['backendlang']['Downline_Code'] :'' }}</th>
			<th>{{ isset($data['backendlang']['backendlang']['Transaction_Amount']) ? $data['backendlang']['backendlang']['Transaction_Amount'] :'' }}</th>
			<th>{{ isset($data['backendlang']['backendlang']['Percentage_Amount']) ? $data['backendlang']['backendlang']['Percentage_Amount'] :'' }}</th>
			<th>{{ isset($data['backendlang']['backendlang']['Commission_Amount']) ? $data['backendlang']['backendlang']['Commission_Amount'] :'' }}</th>
		</tr>
	</thead>
		<tbody>
			@php
				$totalCommission = 0;
				$a=0;
			@endphp
			@foreach($commissions as $commission)
			<tr>
				<td>{{ $a+1 }}</td>
				<td>{{ $commission->created_at }}</td>
				<td>
						{{ $commission->comm_desc }}
				</td>
				<td>
					@if(!empty($commission->transaction_no))
					{{ $commission->transaction_no }}
					@else
					<i class="fa fa-minus"></i>
					@endif
				</td>
				<td>
					@if(!empty($commission->agentName))
					{{ $commission->agentName }}
					@else
					<i class="fa fa-minus"></i>
					@endif
				</td>
				<td>
					{{ $commission->agentCode }}
				</td>
				<td>
					@if(!empty($commission->from_user) || !empty($commission->buyerName))
					{{ !empty($commission->from_user) ? $commission->from_user : $commission->buyerName }}
					@else
					<i class="fa fa-minus"></i>
					@endif
				</td>
				<td>
					@if(!empty($commission->buyerCode))
						{{ $commission->buyerCode }}
					@else
					<i class="fa fa-minus"></i>
					@endif
				</td>
				<td>
					{{ number_format($commission->product_amount, 2) }}
				</td>
				<td>
					@if($commission->comm_pa_type != 'Percentage')
						RM {{ number_format($commission->comm_pa, 2) }}
					@else
						{{ number_format($commission->comm_pa, 2) }}%
					@endif
				</td>
				<td>{{ $commission->comm_amount }}</td>
			</tr>
			@php
				$a++;
				$totalCommission += $commission->comm_amount;
			@endphp
			@endforeach
		<tr>
			<td>{{ isset($data['backendlang']['backendlang']['Summary']) ? $data['backendlang']['backendlang']['Summary'] :'' }}</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td>{{ number_format($totalCommission, 2) }}</td>
		</tr>
	</tbody>
</table>