<table>
    <tr>
        <th><b>{{ !empty($data['web_setting']->invoice_name) ? $data['web_setting']->invoice_name : $data['admin']->company_name }}</b></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th align="right">
            <b>{{ isset($data['backendlang']['backendlang']['Topup_List_Report']) ? $data['backendlang']['backendlang']['Topup_List_Report'] : 'Topup List Report' }}</b>
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
        <th align="right">
            {{ isset($data['backendlang']['backendlang']['report_date']) ? $data['backendlang']['backendlang']['report_date'] :'' }}: {{ isset($start) ? $start : '' }} - {{ isset($end) ? $end : '' }}
        </th>
    </tr>
</table>
<table class="table table-bordered">
    <thead>
        <tr class="info">
            <th>#</th>
            <th>{{ isset($data['backendlang']['backendlang']['Topup_No']) ? $data['backendlang']['backendlang']['Topup_No'] : 'Topup No' }}</th>
            <th>{{ isset($data['backendlang']['backendlang']['Agent']) ? $data['backendlang']['backendlang']['Agent'] : 'Agent Name' }}</th>
            <th>{{ isset($data['backendlang']['backendlang']['Amount']) ? $data['backendlang']['backendlang']['Amount'] : 'Amount (RM)' }}</th>
            <th>{{ isset($data['backendlang']['backendlang']['Status']) ? $data['backendlang']['backendlang']['Status'] : 'Status' }}</th>
            <th>{{ isset($data['backendlang']['backendlang']['Created_At']) ? $data['backendlang']['backendlang']['Created_At'] : 'Created At' }}</th>
            <th>{{ isset($data['backendlang']['backendlang']['Updated_At']) ? $data['backendlang']['backendlang']['Updated_At'] : 'Updated At' }}</th>
        </tr>
    </thead>
    <tbody>
        @if(!empty($topups) && count($topups) > 0)
            @foreach($topups as $key => $topup)
            <tr>
                <td>{{ $key+1 }}</td>
                <td>{{ $topup->topup_no }}</td>
                <td>{{ $topup->agent_name }}</td>
                <td>{{ number_format($topup->actual_amount, 2) }}</td>
                <td>{{ $topup->status }}</td>
                <td>{{ $topup->created_at }}</td>
                <td>{{ $topup->updated_at }}</td>
            </tr>
            @endforeach
        @else
            <tr>
                <td colspan="7">No Result Found</td>
            </tr>
        @endif
    </tbody>
</table>
