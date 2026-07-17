<table>
    <tr>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th align="right">
            <b>{{ isset($data['backendlang']['backendlang']['Agent_Sales_Report']) ? $data['backendlang']['backendlang']['Agent_Sales_Report'] :'' }}</b>
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
            <th>{{ isset($data['backendlang']['backendlang']['Joined_Date']) ? $data['backendlang']['backendlang']['Joined_Date'] :'' }}</th>
            <th>{{ isset($data['backendlang']['backendlang']['Code']) ? $data['backendlang']['backendlang']['Code'] :'' }}</th>
            <th>{{ isset($data['backendlang']['backendlang']['Name']) ? $data['backendlang']['backendlang']['Name'] :'' }}</th>
            <th>{{ isset($data['backendlang']['backendlang']['referral_code']) ? $data['backendlang']['backendlang']['referral_code'] :'' }}</th>
            <th>{{ isset($data['backendlang']['backendlang']['Referral_Name']) ? $data['backendlang']['backendlang']['Referral_Name'] :'' }}</th>
            <!-- <th>Personal Commission</th> -->
            <th>{{ isset($data['backendlang']['backendlang']['Commission']) ? $data['backendlang']['backendlang']['Commission'] :'' }}</th>
            <th>{{ isset($data['backendlang']['backendlang']['Sales']) ? $data['backendlang']['backendlang']['Sales'] :'' }}</th>

        </tr>
    </thead>
    <tbody>
        @php
        $totalQty = 0;
        $totalsfee = 0;
        $totalDiscount = 0;
        $totalTax = 0;
        $totalgrand = 0;
        @endphp

        @if (!$merchants->isEmpty())
        @foreach($merchants as $key => $merchant)
        <tr class="get-details" style="cursor: pointer;" data-id="{{ $merchant->code }}">
            <td>
                {{ $key+1 }}
                <input type="hidden" class="row_id" value="{{ $merchant->id }}">
            </td>
            <td>{{ $merchant->created_at }}</td>
            <td>
                {{ $merchant->display_code }}{{ $merchant->display_running_no }}

            </td>
            <td>{{ $merchant->f_name }} {{ $merchant->l_name }}</td>
            <td>{{ $merchant->upline_code }}</td>
            <td>{{ $merchant->upline_name }}</td>
            <!-- <td>{{ number_format(!empty($personal_comm[$merchant->code]->totalCommission) ? $personal_comm[$merchant->code]->totalCommission : 0 ,2)}}</td> -->
            <td>{{ number_format(!empty($total_comm[$merchant->code]->totalCommission) ? $total_comm[$merchant->code]->totalCommission : 0 ,2)}}</td>
            <td>
                {{number_format(!empty($total_sales[$merchant->code]->totalSales) ? $total_sales[$merchant->code]->totalSales : 0,2)}}
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