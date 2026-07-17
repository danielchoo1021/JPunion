@extends('layouts.admin_app')

@section('content')
<div class="container-box form-group">
    <h3>{{ $data['backendlang']['backendlang']['Filter'] ?? 'Filter' }}</h3>
    <hr>
    <form action="{{ route('team_reward_report_detail', $code) }}" method="GET">
        <div class="row">
            <div class="col-sm-4">
                <input type="text" name="dates" class="form-control"
                       value="{{ request('dates') ?? $startDate.' - '.$endDate }}">
            </div>
            <div class="col-sm-4 mt-2">
                <button class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-search"></i> {{ $data['backendlang']['backendlang']['Search'] ?? 'Search' }}
                </button>
                <a href="{{ route('team_reward_report_detail', $code) }}" class="btn btn-warning btn-sm">
                    <i class="bi bi-arrow-clockwise"></i> {{ $data['backendlang']['backendlang']['Clear_Search'] ?? 'Clear' }}
                </a>
            </div>
        </div>
    </form>
</div>

<div class="container-box form-group mt-3">
    <h3>{{ $code }} - {{ $data['backendlang']['backendlang']['S_Team_Reward_Report_Detail'] ?? 'Team Reward Report Detail' }}</h3>

    <table class="table table-borderless" style="width:30%; margin-bottom:20px;">
        <tr>
            <td><b>Agent Name</b></td>
            <td>: {{ $agent->f_name ?? '-' }} {{ $agent->l_name ?? '' }}</td>
        </tr>
        <tr>
            <td><b>Agent Code</b></td>
            <td>: {{ $agent->code ?? '-' }}</td>
        </tr>
        <tr>
            <td><b>Entitle</b></td>
            <td>: {{ $uplineEntitle ?? 0 }}%</td>
        </tr>
    </table>

    <div class="row">
        <div class="col-12" style="overflow:auto;">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ $data['backendlang']['backendlang']['Direct_Downline'] ?? 'Direct Downline' }}</th>
                        <th>{{ $data['backendlang']['backendlang']['Downline_Group_Sales'] ?? 'Downline Group Sales' }}</th>
                        <th>{{ $data['backendlang']['backendlang']['Entitles'] ?? 'Entitle %' }}</th>
                        <th>{{ $data['backendlang']['backendlang']['Difference'] ?? 'Difference %' }}</th>
                        <th>{{ $data['backendlang']['backendlang']['total_amount'] ?? 'Total Amount (RM)' }}</th>
                    </tr>
                </thead>
                <tbody>
                    @php 
						$i = 1; 
						$totalComm = 0;
					@endphp

                    @forelse($details as $detail)
						@php
                            $groupSales = $detail['groupSales'] ?? 0;
                            $entitle = $detail['entitle'] ?? 0;
                            $difference = $detail['difference'] ?? 0;

                            $commission = ($groupSales * $difference) / 100;

                            $totalComm += $commission;
                        @endphp

                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ $detail['downlineName'] ?? '-' }} ({{ $detail['downlineCode'] ?? '-' }})</td>
                            <td>RM {{ number_format($groupSales, 2) }}</td>
                            <td>{{ $entitle }}%</td>
                            <td>{{ $difference }}%</td>
                            <td>RM {{ number_format($commission, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">{{ $data['backendlang']['backendlang']['No_Result_Found'] ?? 'No Result Found' }}</td>
                        </tr>
                    @endforelse
                    <tr>
                        <td colspan="5" class="text-right"><b>Total</b></td>
                        <td><b>RM {{ number_format($totalComm, 2) }}</b></td>
                    </tr>
                </tbody>
            </table>
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