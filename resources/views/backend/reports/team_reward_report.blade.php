@extends('layouts.admin_app')

@section('content')
<div class="container-box form-group">
    <h3>{{ $data['backendlang']['backendlang']['Filter'] ?? 'Filter' }}</h3>
    <hr>
    <form action="{{ route('team_reward_report') }}" method="GET">
        <div class="row">
            <div class="col-sm-2">
                <input type="text" class="form-control" name="dates" 
                       value="{{ request('dates') ?? $startDate.' - '.$endDate }}">
            </div>
            <div class="col-sm-2">
                <input type="text" class="form-control" name="referrer_name" 
                       value="{{ request('referrer_name') ?? '' }}" 
                       placeholder="{{ $data['backendlang']['backendlang']['Search_Agent_Name'] ?? 'Search Agent Name' }}">
            </div>
            <div class="col-sm-2">
                <input type="text" class="form-control" name="referrer_code" 
                       value="{{ request('referrer_code') ?? '' }}" 
                       placeholder="{{ $data['backendlang']['backendlang']['Search_Agent_Code'] ?? 'Search Agent Code' }}">
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-sm-2">
                {{ $data['backendlang']['backendlang']['Row_Per_Page'] ?? 'Row Per Page' }}:
                <select name="per_page" class="form-control">
                    <option value="10" {{ request('per_page')=='10' ? 'selected':'' }}>10</option>
                    <option value="20" {{ request('per_page')=='20' ? 'selected':'' }}>20</option>
                    <option value="50" {{ request('per_page')=='50' ? 'selected':'' }}>50</option>
                </select>
            </div>
            <div class="col-sm-4 mt-2">
                <button class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-search"></i> {{ $data['backendlang']['backendlang']['Search'] ?? 'Search' }}
                </button>
                <a href="{{ route('team_reward_report') }}" class="btn btn-warning btn-sm">
                    <i class="bi bi-arrow-clockwise"></i> {{ $data['backendlang']['backendlang']['Clear_Search'] ?? 'Clear' }}
                </a>
            </div>
        </div>
    </form>
</div>

<div class="container-box form-group mt-3">
    <div class="row">
        <div class="col-12" style="overflow:auto;">
            {{ $agents->links() }}
            <table class="table table-bordered mt-2">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ $data['backendlang']['backendlang']['Agent_Name'] ?? 'Agent Name' }}</th>
                        <th>{{ $data['backendlang']['backendlang']['Agent_Code'] ?? 'Agent Code' }}</th>
                        <th>{{ $data['backendlang']['backendlang']['total_sale'] ?? 'Total Sale' }}</th>
                        <th>{{ $data['backendlang']['backendlang']['Entitles'] ?? 'Entitle %' }}</th>
                        <th>{{ $data['backendlang']['backendlang']['total_amount'] ?? 'Total Amount (RM)' }}</th>
                    </tr>
                </thead>
                <tbody>
                    @php 
                        $i = ($agents->currentPage()-1) * $agents->perPage();
                        $totalTeamReward = 0; 
                    @endphp

                    @forelse($agents as $agent)
                        @php
                            $groupSales = $team_rewards[$agent->agentCode]['groupSales'] ?? 0; 
                            $entitle = $team_rewards[$agent->agentCode]['percentage'] ?? 0;   
                            $commission = $team_rewards[$agent->agentCode]['commission'] ?? 0; 

                            $totalTeamReward += $commission;
                        @endphp
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td>
                                <a href="{{ route('team_reward_report_detail', $agent->agentCode) }}">
                                    {{ $agent->agentName ?? '-' }}
                                </a>
                            </td>
                            <td>{{ $agent->agentCode }}</td>
                            <td>RM {{ number_format($groupSales,2) }}</td>
                            <td>{{ $entitle }}%</td>
                            <td>RM {{ number_format($commission,2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">
                                {{ $data['backendlang']['backendlang']['No_Result_Found'] ?? 'No Result Found' }}
                            </td>
                        </tr>
                    @endforelse
                    <tr class="font-weight-bold">
                        <td colspan="5" class="text-right">
                            {{ $data['backendlang']['backendlang']['Summary'] ?? 'Summary' }}
                        </td>
                        <td><b>RM {{ number_format($totalTeamReward,2) }}</b></td>
                    </tr>
                </tbody>
            </table>
            {{ $agents->links() }}
        </div>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript">
$('input[name=dates]').daterangepicker({
    applyClass: 'btn-sm btn-success',
    cancelClass: 'btn-sm btn-outline-danger',
    locale: {
        applyLabel: "{{ $data['backendlang']['backendlang']['Apply'] ?? 'Apply' }}",
        cancelLabel: "{{ $data['backendlang']['backendlang']['Cancel'] ?? 'Cancel' }}",
        format: 'DD/MM/YYYY',
    }
});
</script>
@endsection