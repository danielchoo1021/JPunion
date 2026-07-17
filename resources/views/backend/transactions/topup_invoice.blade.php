@extends('layouts.admin_app')
<style type="text/css">
	@media print { 
	    table td, table th { 
	        background-color: #ddd !important; 
	    } 
	}

	
</style>
@section('content')
<a href="#" class="print-window" style="display: none;">
	<i class="fa fa-print"></i> {{ isset($data['backendlang']['backendlang']['Print']) ? $data['backendlang']['backendlang']['Print'] :'' }}
</a>
<table width="100%">
	<tr>
		<td>
			@if(!empty($data['admin']->website_logo))
			<img src="{{ asset($data['admin']->website_logo) }}" style="width: 100px;">
			@endif
			<h3>{{ $data['admin']->website_name }} {{ isset($data['backendlang']['backendlang']['Sdn_Bhd']) ? $data['backendlang']['backendlang']['Sdn_Bhd'] :'' }}</h3>
		</td>
		<td align="right">
			<h1>{{ isset($data['backendlang']['backendlang']['Topup_Invoice']) ? $data['backendlang']['backendlang']['Topup_Invoice'] :'' }}</h1>
		</td>
	</tr>
	<tr>
		<td>
			<b>{{ isset($data['backendlang']['backendlang']['Contact_Number']) ? $data['backendlang']['backendlang']['Contact_Number'] :'' }}: </b>{{ $data['admin']->phone }}
		</td>
	</tr>
	<tr>
		<td>
			<b>{{ isset($data['backendlang']['backendlang']['Address']) ? $data['backendlang']['backendlang']['Address'] :'' }}: </b>{{ $data['web_setting']->address }}
		</td>
	</tr>
</table>
<br>
<table width="100%">
	<tr>
		<td>
			{{ isset($data['backendlang']['backendlang']['To']) ? $data['backendlang']['backendlang']['To'] :'' }}:
		</td>
	</tr>
	<tr>
		<td>
			<h4>{{ $transaction->agent_name }} ({{ $transaction->user_id }})</h4>
		</td>
		<td align="right">
			<h4><b>{{ isset($data['backendlang']['backendlang']['Invoice']) ? $data['backendlang']['backendlang']['Invoice'] :'' }} #{{ $transaction->topup_no }}</b></h4>
		</td>
	</tr>
	<tr>
		<td>
			@if($transaction->mall == 1)
				{{ isset($data['backendlang']['backendlang']['Payment_Method']) ? $data['backendlang']['backendlang']['Payment_Method'] :'' }}: {{ isset($data['backendlang']['backendlang']['Wallet']) ? $data['backendlang']['backendlang']['Wallet'] :'' }}
			@else
				{{ isset($data['backendlang']['backendlang']['Payment_Method']) ? $data['backendlang']['backendlang']['Payment_Method'] :'' }}: {{ ($transaction->topup_payment_method == '1') ? ($data['backendlang']['backendlang']['Online_Transfer'] ?? 'Online Transfer') : ($data['backendlang']['backendlang']['Bank_Transfer'] ?? 'Bank Transfer')}}

			@endif
		</td>
	</tr>
</table>
<hr>
<table class="table table-bordered">
	<tr>
		<td>{{ isset($data['backendlang']['backendlang']['Particulars']) ? $data['backendlang']['backendlang']['Particulars'] :'' }}</td>
		<td align="right">{{ isset($data['backendlang']['backendlang']['Unit_Price']) ? $data['backendlang']['backendlang']['Unit_Price'] :'' }}</td>
		<td align="right">{{ isset($data['backendlang']['backendlang']['Quantity']) ? $data['backendlang']['backendlang']['Quantity'] :'' }}</td>
		<td align="right">{{ isset($data['backendlang']['backendlang']['Amount']) ? $data['backendlang']['backendlang']['Amount'] :'' }} (MYR)</td>
	</tr>
	<tr>
		<td>
			{{ $transaction->amount_desc }}
		</td>
		<td align="right">
			{{ number_format($transaction->actual_amount, 2) }}
		</td>
		<td align="right">
			x1
		</td>
		<td align="right">
			{{ number_format(($transaction->actual_amount), 2) }}
		</td>
	</tr>
	<tr>
		<td colspan="3" align="right">
			{{ isset($data['backendlang']['backendlang']['Grand_Total']) ? $data['backendlang']['backendlang']['Grand_Total'] :'' }}:
		</td>
		<td colspan="3" align="right">
			{{ number_format($transaction->actual_amount, 2) }}
		</td>
	</tr>
</table>
@endsection

@section('js')
<script type="text/javascript">
	$('.print-window').click(function() {
	    window.print();
	});
	$(document).ready(function(){
		$('.print-window').click();
		$('.tr-color').css('background-color', '#ddd');
	});
</script>
@endsection