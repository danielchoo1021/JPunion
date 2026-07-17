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
            <b>{{ isset($data['backendlang']['backendlang']['Agent_List_Report']) ? $data['backendlang']['backendlang']['Agent_List_Report'] :'' }}</b>
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
            <th>{{ isset($data['backendlang']['backendlang']['Agent']) ? $data['backendlang']['backendlang']['Agent'] :'' }}</th>
            <th>{{ isset($data['backendlang']['backendlang']['Code']) ? $data['backendlang']['backendlang']['Code'] :'' }}</th>
            <th>{{ isset($data['backendlang']['backendlang']['Name']) ? $data['backendlang']['backendlang']['Name'] :'' }}</th>
            <th>{{ isset($data['backendlang']['backendlang']['Referral']) ? $data['backendlang']['backendlang']['Referral'] :'' }}</th>
            <th>{{ isset($data['backendlang']['backendlang']['Email']) ? $data['backendlang']['backendlang']['Email'] :'' }}</th>
            <th>{{ isset($data['backendlang']['backendlang']['Phone']) ? $data['backendlang']['backendlang']['Phone'] :'' }}</th>
            <th>{{ isset($data['backendlang']['backendlang']['Status']) ? $data['backendlang']['backendlang']['Status'] :'' }}</th>
            <th>{{ isset($data['backendlang']['backendlang']['Date']) ? $data['backendlang']['backendlang']['Date'] :'' }}</th>
        </tr>
    </thead>
    <tbody>
        @forelse($agents as $key => $agent)
        <tr>
            <td>
                {{ $key+1 }}
            </td>
        <td>
            @if(Auth::user()->permission_lvl == 1)
                {{ !empty($agent->get_merchant->f_name) ? $agent->get_merchant->f_name : 'By Admin' }}
                <br>
                ({{ !empty($agent->get_merchant->code) ? $agent->get_merchant->code : 'AD000002' }})
            @endif
        </td>
            <td>{{ $agent->display_code }}{{ $agent->display_running_no }}</td>
            <td>{{ $agent->f_name }}</td>
            <td>
                @if(!empty($agent->get_upline_det->get_user_id_agent_det->code))
                {{ $agent->get_upline_det->get_user_id_agent_det->f_name }}
                @elseif(!empty($agent->get_upline_det->get_user_id_member_det->code))

                {{ $agent->get_upline_det->get_user_id_member_det->f_name }}

                @elseif(!empty($agent->get_upline_det->get_user_id_admin_det->code))

                {{ $agent->get_upline_det->get_user_id_admin_det->f_name }}
                {{ $agent->get_upline_det->get_user_id_admin_det->l_name }}

                @else
                <span style="color: red;">
                    <i class="bi bi-minus"></i>

                </span>
                @endif
                <br>
                ({{ $agent->master_id }})
            </td>
            <td>{{ $agent->email }}</td>
            <td>
                (+{{ $agent->country_code }})
                {{($agent->country_code && $agent->country_code == '60' &&
                $agent->phone[0] != '0') ? $agent->phone : $agent->phone }}
            </td>
            <td>
                {{ $agent->status == 1 ? ($data['backendlang']['backendlang']['Active'] ?? 'Active') : ($data['backendlang']['backendlang']['Inactive'] ?? 'Inactive') }}
            </td>   
            <td>{{ $agent->created_at }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="9">{{ isset($data['backendlang']['backendlang']['No_Result_Found']) ? $data['backendlang']['backendlang']['No_Result_Found'] :'' }}</td>
        </tr>
        @endforelse
    </tbody>
</table>