@extends('layouts.admin_app')
<style type="text/css">
	@media print{
		@page {
			size: landscape;
			margin: 4mm 0mm;
		}
	}

	
</style>
@section('content')
	<a href="#" class="print-window" style="display: none;">
		<i class="fa fa-print"></i> {{ isset($data['backendlang']['backendlang']['Print']) ? $data['backendlang']['backendlang']['Print'] :'' }}
	</a>
	<div class="form-group">
		<table class="table">
			<tr>
				<td>
					<div class="form-group">
						<h3><b>{{ !empty($data['web_setting']->invoice_name) ? $data['web_setting']->invoice_name : $data['admin']->company_name }}</b></h3>
					</div>
					<div class="form-group">
						<p>{{ isset($data['backendlang']['backendlang']['Print_Dates']) ? $data['backendlang']['backendlang']['Print_Dates'] :'' }}: {{ date('d/m/Y H:i:s') }}</p>
					</div>
				</td>
				<td align="right">
					<div class="form-group">
						<h3><b>{{ isset($data['backendlang']['backendlang']['Agent_Stock_Report_Details']) ? $data['backendlang']['backendlang']['Agent_Stock_Report_Details'] :'' }}</b></h3>
					</div>
					<div class="form-group">
						<p>{{ isset($data['backendlang']['backendlang']['Report_Dates']) ? $data['backendlang']['backendlang']['Report_Dates'] :'' }}: {{ !empty(request('dates')) ? request('dates') : $startDate.' - '.$endDate }}</p>
					</div>
				</td>
			</tr>
		</table>
	</div>
	<table class="table table-bordered">
        <thead>
            <tr class="info">
                <th>#</th>
                <th>{{ isset($data['backendlang']['backendlang']['Date']) ? $data['backendlang']['backendlang']['Date'] :'' }}</th>
                <th>{{ isset($data['backendlang']['backendlang']['Transaction_No']) ? $data['backendlang']['backendlang']['Transaction_No'] :'' }}</th>
                <th>{{ isset($data['backendlang']['backendlang']['Product_Name']) ? $data['backendlang']['backendlang']['Product_Name'] :'' }}</th>
                <th>{{ isset($data['backendlang']['backendlang']['Unit_Price']) ? $data['backendlang']['backendlang']['Unit_Price'] :'' }}</th>
                <th>{{ isset($data['backendlang']['backendlang']['Quantity']) ? $data['backendlang']['backendlang']['Quantity'] :'' }}</th>
                <!-- <th>Get PV</th> -->
            </tr>
        </thead>
        <tbody>
            @php
                $totalQty = 0;
                $totalsfee = 0;
                $totalDiscount = 0;
                $totalTax = 0;
                $totalgrand = 0;
                $totaluPrice = 0;
                $totalq = 0;
                $totalpv = 0;
            @endphp

            @if(!$all->isEmpty())
                @foreach($all as $key => $merchant)
                @php
                    $span_row = 0;

                    $span_row = count($details[$merchant->transaction_no]);

                    $row_count = count($details[$merchant->transaction_no])+1;
                @endphp
                <tr>
                    @if($span_row >= 1)
                    <td rowspan="{{ $row_count }}">
                        {{ $key+1 }}
                        <input type="hidden" class="row_id" value="{{ $merchant->id }}">
                    </td>
                    <td rowspan="{{ $row_count }}">{{ $merchant->created_at }}</td>
                    <td rowspan="{{ $row_count }}">{{ $merchant->transaction_no }}</td>
                    @endif
                </tr>
                    @foreach($details[$merchant->transaction_no] as $detail)
                    <tr>
                        <td>
                            {{ $detail->product_name }}
                        </td>
                        <td>
                            {{ $detail->unit_price }}
                        </td>
                        <td>
                            {{ $detail->quantity }}
                        </td>
                        <!-- <td>
                            {{ $detail->get_pv }}
                        </td> -->
                    </tr>
                    @php
                $totaluPrice += $detail->unit_price;
                $totalq += $detail->quantity ;
                $totalpv += $detail->get_pv ;
                @endphp
                    @endforeach
                    
                @endforeach
                
            @else
            <tr>
                <td colspan="15">{{ isset($data['backendlang']['backendlang']['No_Result_Found']) ? $data['backendlang']['backendlang']['No_Result_Found'] :'' }}</td>
            </tr>
            @endif
            <tr class="warning">
                <td style=""  colspan="4">
                    <b>{{ isset($data['backendlang']['backendlang']['Page_Summary']) ? $data['backendlang']['backendlang']['Page_Summary'] :'' }}</b>
                </td>
                <td style=" text-align: right;" >
                    <b>{{ $totaluPrice }}</b>
                </td>

                <td style=" text-align: right;" >
                    <b>{{ $totalq }}</b>
                </td>							
            </tr>
        </tbody>
    </table>
@endsection

@section('js')
<script type="text/javascript">
	$('.print-window').click(function() {
	    window.print();
	});
	$(document).ready(function(){
		$('.print-window').click();
	});
</script>
@endsection