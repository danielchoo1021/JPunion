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
            <b>{{ isset($data['backendlang']['backendlang']['Customer_Referral_Bonus_Report']) ? $data['backendlang']['backendlang']['Customer_Referral_Bonus_Report'] :'' }}</b>
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
    </tr>
</table>
<table class="table table-bordered">
    <thead>
        <tr class="info">
            <th>#</th>
            <th>{{ isset($data['backendlang']['backendlang']['Agent']) ? $data['backendlang']['backendlang']['Agent'] :'' }}</th>
            <th>{{ isset($data['backendlang']['backendlang']['Customer']) ? $data['backendlang']['backendlang']['Customer'] :'' }}</th>
            <th>{{ isset($data['backendlang']['backendlang']['Agent_Level']) ? $data['backendlang']['backendlang']['Agent_Level'] :'' }}</th>
            <th>{{ isset($data['backendlang']['backendlang']['Paid_Orders']) ? $data['backendlang']['backendlang']['Paid_Orders'] :'' }}</th>
            <th>{{ isset($data['backendlang']['backendlang']['Target']) ? $data['backendlang']['backendlang']['Target'] :'' }}</th>
            <th>{{ isset($data['backendlang']['backendlang']['Bonus_Amount']) ? $data['backendlang']['backendlang']['Bonus_Amount'] :'' }}</th>
            <th>{{ isset($data['backendlang']['backendlang']['Status']) ? $data['backendlang']['backendlang']['Status'] :'' }}</th>
        </tr>
    </thead>
    <tbody>
        @if (!$progress->isEmpty())
        @foreach($progress as $key => $row)
        <tr>
            <td>{{ $key+1 }}</td>
            <td>{{ $row->agent_display_code }}{{ $row->agent_display_running_no }} - {{ $row->agent_f_name }} {{ $row->agent_l_name }}</td>
            <td>{{ $row->customer_display_code }}{{ $row->customer_display_running_no }} - {{ $row->customer_f_name }} {{ $row->customer_l_name }}</td>
            <td>{{ $row->agent_lvl_name }}</td>
            <td>{{ $row->paid_order_count }}</td>
            <td>{{ !empty($row->target_orders) ? $row->target_orders : '-' }}</td>
            <td>{{ !empty($row->bonus_amount) ? number_format($row->bonus_amount, 2) : '-' }}</td>
            <td>
                @if(empty($row->target_orders))
                    {{ isset($data['backendlang']['backendlang']['Not_Configured']) ? $data['backendlang']['backendlang']['Not_Configured'] :'' }}
                @elseif($row->customer_referral_bonus_claimed)
                    {{ isset($data['backendlang']['backendlang']['Claimed']) ? $data['backendlang']['backendlang']['Claimed'] :'' }}
                @else
                    {{ isset($data['backendlang']['backendlang']['Pending']) ? $data['backendlang']['backendlang']['Pending'] :'' }}
                @endif
            </td>
        </tr>
        @endforeach
        @else
        <tr>
            <td colspan="8">{{ isset($data['backendlang']['backendlang']['No_Result_Found']) ? $data['backendlang']['backendlang']['No_Result_Found'] :'' }}</td>
        </tr>
        @endif
    </tbody>
</table>
