@extends('layouts.admin_app')
@section('css')
    <style>
        @media(max-width: 768px) {
            .cart-body-mobile {
                padding: 0 !important;
            }
        }

        .table-bordered th {
            background-color: rgb(67, 94, 190);
            border-color: rgb(67, 94, 190);
            font-weight: 600;
            color: white;
        }

        .table-bordered td {
            border-color: #e4ddddff;
            vertical-align: middle;
        }

        .table tr:hover {
            background-color: #f8f9fa;
        }

        /* Leaderboard Styling */
        .leaderboard-tabs {
            border-bottom: 2px solid #f0f2f5;
            padding-bottom: 5px;
        }

        .leaderboard-tabs .nav-link {
            border: none;
            color: #495057;
            font-weight: 600;
            font-size: 14px;
            padding: 10px 16px;
            border-radius: 8px;
            transition: all 0.25s ease;
            background-color: #f8f9fa;
            margin-right: 5px;
        }

        .leaderboard-tabs .nav-link:hover {
            background-color: #eef1f6;
            color: #435ebe;
        }

        .leaderboard-tabs .nav-link.active {
            background-color: #435ebe !important;
            color: white !important;
            box-shadow: 0 4px 12px rgba(67, 94, 190, 0.25);
        }

        /* Pending Tasks Warning Bar */
        .pending-tasks-bar {
            background: linear-gradient(135deg, #fffcf3 0%, #fff7db 100%);
            border-left: 5px solid #ffc107;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04);
        }

        /* Metric cards */
        .metric-subtitle {
            font-size: 11px;
            color: #8898aa;
            margin-top: 4px;
        }

        .metric-value-line {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
        }

        .metric-split-val {
            font-size: 13px;
            font-weight: 600;
            color: #525f7f;
        }
    </style>
@endsection
@section('content')
    <!-- Animated -->
    @php
        $pending_agents_count = isset($data['total_pending']) ? $data['total_pending'] : 0;
        $pending_members_count = isset($data['total_member_pending']) ? $data['total_member_pending'] : 0;
        $pending_merchants_count = isset($data['total_merchant_pending']) ? $data['total_merchant_pending'] : 0;
        $pending_topups_count = isset($data['allPendingTopup']) ? $data['allPendingTopup'] : 0;
        $pending_withdrawals_count = isset($data['allPendingWith']) ? $data['allPendingWith'] : 0;

        $has_pending = ($pending_agents_count + $pending_members_count + $pending_merchants_count + $pending_topups_count + $pending_withdrawals_count) > 0;
    @endphp

    @if($has_pending)
        <div class="pending-tasks-bar p-3 mb-4 d-flex align-items-center justify-content-between flex-wrap">
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill text-warning mr-2" style="font-size: 20px;"></i>
                <strong class="text-dark mr-3" style="font-size: 14px;">待审批业务提醒:</strong>
                <div class="d-flex flex-wrap">
                    @if($pending_agents_count > 0)
                        <a href="{{ route('agent.agents.index') }}?status=99"
                            class="badge badge-warning text-dark p-2 mr-2 mb-1" style="font-size: 12px; border-radius: 5px;">
                            待核代理: <strong>{{ $pending_agents_count }}</strong>
                        </a>
                    @endif
                    @if($pending_members_count > 0)
                        <a href="{{ route('member.members.index') }}?status=99"
                            class="badge badge-warning text-dark p-2 mr-2 mb-1" style="font-size: 12px; border-radius: 5px;">
                            待核会员: <strong>{{ $pending_members_count }}</strong>
                        </a>
                    @endif
                    @if($pending_topups_count > 0)
                        <a href="/topup_list" class="badge badge-danger p-2 mr-2 mb-1 text-white"
                            style="font-size: 12px; border-radius: 5px;">
                            充值待处理: <strong>{{ $pending_topups_count }}</strong>
                        </a>
                    @endif
                    @if($pending_withdrawals_count > 0)
                        <a href="/withdrawal_list" class="badge badge-danger p-2 mr-2 mb-1 text-white"
                            style="font-size: 12px; border-radius: 5px;">
                            提现待审核: <strong>{{ $pending_withdrawals_count }}</strong>
                        </a>
                    @endif
                </div>
            </div>
            <span class="text-muted" style="font-size: 11px; font-weight: 500;">实时待办状态</span>
        </div>
    @endif

    <section class="row">
        <!-- Card 1: Agents Network -->
        <div class="col-12 col-md-6 col-lg-3 mb-4">
            <div class="card h-100 mb-0 shadow-sm">
                <div class="card-body px-4 py-4-5">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon purple mr-3"
                            style="min-width: 42px; display:-webkit-flex; display:flex; justify-content:center; align-items:center; border-radius: 10px;">
                            <i class="iconly-boldShow"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="text-muted font-semibold mb-1" style="font-size: 13px;">
                                {{ isset($data['backendlang']['backendlang']['Total_Agent']) ? $data['backendlang']['backendlang']['Total_Agent'] : '代理网络' }}
                            </h6>
                            <div class="metric-value-line">
                                <span class="h4 font-extrabold mb-0 d-inline-block">{{ $totalAgents->totalAgent }}</span>
                                <span class="text-secondary" style="font-size: 12px; font-weight: 600;">活跃:
                                    {{ $totalActiveAgents->totalAgent }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Card 2: Customers Network -->
        <div class="col-12 col-md-6 col-lg-3 mb-4">
            <div class="card h-100 mb-0 shadow-sm">
                <div class="card-body px-4 py-4-5">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon blue mr-3"
                            style="min-width: 42px; display:flex; justify-content:center; align-items:center; border-radius: 10px;">
                            <i class="iconly-boldShow"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="text-muted font-semibold mb-1" style="font-size: 13px;">
                                {{ isset($data['backendlang']['backendlang']['Total_Customer']) ? $data['backendlang']['backendlang']['Total_Customer'] : '注册会员' }}
                            </h6>
                            <div class="metric-value-line">
                                <span
                                    class="h4 font-extrabold mb-0 d-inline-block">{{ $totalCustomers->totalCustomer }}</span>
                                <span class="text-secondary" style="font-size: 12px; font-weight: 600;">活跃:
                                    {{ $totalActiveCustomers->totalCustomer }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Card 3: Overall Profit / Sales & Commission -->
        <div class="col-12 col-md-6 col-lg-3 mb-4">
            <div class="card h-100 mb-0 shadow-sm">
                <div class="card-body px-4 py-4-5">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon green mr-3"
                            style="min-width: 42px; display:flex; justify-content:center; align-items:center; border-radius: 10px;">
                            <i class="iconly-boldShow"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="text-muted font-semibold mb-1" style="font-size: 13px;">
                                公司销售与产生佣金
                            </h6>
                            <div style="font-size: 14px; font-weight: 700; color: #111;">
                                销售: {{ $data['currency_code'] }}{{ number_format($totalSales->totalSales, 2) }}
                            </div>
                            <div class="metric-subtitle mb-0">
                                佣金: {{ $data['currency_code'] }}{{ number_format($totalCommission->totalCommission, 2) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Card 4: Topup, Withdrawal & Net Cash retained -->
        <div class="col-12 col-md-6 col-lg-3 mb-4">
            <div class="card h-100 mb-0 shadow-sm">
                <div class="card-body px-4 py-4-5">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon red mr-3"
                            style="min-width: 42px; display:flex; justify-content:center; align-items:center; border-radius: 10px;">
                            <i class="iconly-boldShow"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="text-muted font-semibold mb-1" style="font-size: 13px;">
                                充提沉淀资金 (Net Cash)
                            </h6>
                            @php
                                $net_cash = $totalTopup->totalTopup + $totalSales->totalSales - $WithdrawalTransaction->totalWithdrawal;
                            @endphp
                            <div class="text-success" style="font-size: 14px; font-weight: 700;">
                                存量: {{ $data['currency_code'] }}{{ number_format($net_cash, 2) }}
                            </div>
                            <div class="metric-subtitle mb-0">
                                充: {{ number_format($totalTopup->totalTopup, 0) }} | 提:
                                {{ number_format($WithdrawalTransaction->totalWithdrawal, 0) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h4>{{ isset($data['backendlang']['backendlang']['Product_Stock']) ? $data['backendlang']['backendlang']['Product_Stock'] : '' }}
                </h4>
            </div>
            <div class="card-body cart-body-mobile">
                <table class="table table-bordered">
                    <thead>
                        <tr class="info">
                            <th style="text-align: center;">
                                {{ isset($data['backendlang']['backendlang']['No']) ? $data['backendlang']['backendlang']['No'] : '' }}
                            </th>
                            <th style="text-align: center;">
                                {{ isset($data['backendlang']['backendlang']['Type']) ? $data['backendlang']['backendlang']['Type'] : '' }}
                            </th>
                            <th style="text-align: center;">
                                {{ isset($data['backendlang']['backendlang']['product']) ? $data['backendlang']['backendlang']['product'] : '' }}
                                @if(empty(request('name_desc')) && empty(request('name_asc')))
                                    <a href="{{ route('dashboard.dashboards.index', ['name_desc=DESC']) }}"
                                        class="{{ !empty(request('name_desc')) ? 'selected' : '' }}">
                                        <i class="bi bi-sort-down" style="color: white;"></i>
                                        <input type="hidden" name="sort_data" value="0">
                                    </a>
                                @else
                                    @if(!empty(request('name_desc')))
                                        <a href="{{ route('dashboard.dashboards.index', ['name_asc=ASC']) }}"
                                            class="{{ !empty(request('name_asc')) ? 'selected' : '' }}">
                                            <i class="bi bi-sort-down" style="color: white;"></i>
                                            <input type="hidden" name="sort_data" value="1">
                                        </a>
                                    @elseif(!empty(request('name_asc')))
                                        <a href="{{ route('dashboard.dashboards.index', ['name_desc=DESC']) }}"
                                            class="{{ !empty(request('name_desc')) ? 'selected' : '' }}">
                                            <i class="bi bi-sort-up" style="color: white;"></i>
                                            <input type="hidden" name="sort_data" value="0">
                                        </a>
                                    @endif
                                @endif
                            </th>
                            <th style="text-align: center;">
                                {{ isset($data['backendlang']['backendlang']['Category']) ? $data['backendlang']['backendlang']['Category'] : '' }}
                            </th>
                            <th style="text-align: center;">
                                {{ isset($data['backendlang']['backendlang']['ProductSKU']) ? $data['backendlang']['backendlang']['ProductSKU'] : '' }}
                            </th>
                            <th style="text-align: center;">
                                {{ isset($data['backendlang']['backendlang']['Variation']) ? $data['backendlang']['backendlang']['Variation'] : '' }}
                            </th>
                            <th style="text-align: center;">
                                {{ isset($data['backendlang']['backendlang']['Second_Variation']) ? $data['backendlang']['backendlang']['Second_Variation'] : '' }}
                            </th>
                            <th style="text-align: center;">
                                {{ isset($data['backendlang']['backendlang']['Stock']) ? $data['backendlang']['backendlang']['Stock'] : '' }}
                                @if(empty(request('stock_desc')) && empty(request('stock_asc')))
                                    <a href="{{ route('dashboard.dashboards.index') }}?stock_desc=DESC"
                                        class="{{ !empty(request('stock_desc')) ? 'selected' : '' }}">
                                        <i class="bi bi-sort-down" style="color: white;"></i>
                                        <input type="hidden" name="sort_data" value="0">
                                    </a>
                                @else
                                    @if(!empty(request('stock_desc')))
                                        <a href="{{ route('dashboard.dashboards.index') }}?stock_asc=ASC"
                                            class="{{ !empty(request('stock_asc')) ? 'selected' : '' }}">
                                            <i class="bi bi-sort-down" style="color: white;"></i>
                                            <input type="hidden" name="sort_data" value="1">
                                        </a>
                                    @elseif(!empty(request('stock_asc')))
                                        <a href="{{ route('dashboard.dashboards.index') }}?stock_desc=DESC"
                                            class="{{ !empty(request('stock_desc')) ? 'selected' : '' }}">
                                            <i class="bi bi-sort-up" style="color: white;"></i>
                                            <input type="hidden" name="sort_data" value="0">
                                        </a>
                                    @endif
                                @endif
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($product_stock_data) && count($product_stock_data) > 0)
                            @php
                                $current_page = request('page', 1);
                                $per_page = request('per_page', 10);
                                $row_counter = (($current_page - 1) * $per_page);
                            @endphp
                            @foreach($product_stock_data as $key => $product)
                                @php
                                    $row_counter++;
                                    $row_number = $row_counter;
                                @endphp
                                @php
                                    $total_rows = 1;

                                    if ($product->variation_enable == '1' && !$variation_stock_data[$product->id]->isEmpty()) {
                                        $total_rows = 0;
                                        foreach ($variation_stock_data[$product->id] as $variation) {
                                            if ($product->second_variation_enable == '1' && !$second_variation_stock_data[$product->id][$variation->id]->isEmpty()) {
                                                $total_rows += count($second_variation_stock_data[$product->id][$variation->id]);
                                            } else {
                                                $total_rows += 1;
                                            }
                                        }
                                    }

                                    $first_row = true;
                                @endphp

                                @if ($product->variation_enable == '1' && !$variation_stock_data[$product->id]->isEmpty())
                                    @foreach ($variation_stock_data[$product->id] as $variation)
                                        @php
                                            $variation_rows = 1;
                                            if ($product->second_variation_enable == '1' && !$second_variation_stock_data[$product->id][$variation->id]->isEmpty()) {
                                                $variation_rows = count($second_variation_stock_data[$product->id][$variation->id]);
                                            }
                                            $variation_first_row = true;
                                        @endphp

                                        @if ($product->second_variation_enable == '1' && !$second_variation_stock_data[$product->id][$variation->id]->isEmpty())
                                            @foreach ($second_variation_stock_data[$product->id][$variation->id] as $second_variation)
                                                <tr>
                                                    @if ($first_row)
                                                        <td style="text-align: center;" rowspan="{{ $total_rows }}">{{ $row_number }}</td>
                                                        <td style="text-align: center;" rowspan="{{ $total_rows }}">
                                                            @if(!empty($product->mall))
                                                                {{ isset($data['backendlang']['backendlang']['Point_Product']) ? $data['backendlang']['backendlang']['Point_Product'] : '' }}
                                                            @else
                                                                {{ isset($data['backendlang']['backendlang']['Normal_Product']) ? $data['backendlang']['backendlang']['Normal_Product'] : '' }}
                                                            @endif
                                                        </td>
                                                        <td style="text-align: center;" rowspan="{{ $total_rows }}">{{ $product->product_name }}</td>
                                                        <td style="text-align: center;" rowspan="{{ $total_rows }}">
                                                            {{ !empty($product->category_name) ? $product->category_name : 'N/A' }}</td>
                                                        <td style="text-align: center;" rowspan="{{ $total_rows }}">
                                                            {{ !empty($product->product_code) ? $product->product_code : 'N/A' }}</td>
                                                        @php $first_row = false; @endphp
                                                    @endif

                                                    @if ($variation_first_row)
                                                        <td style="text-align: center;" rowspan="{{ $variation_rows }}">{{ $variation->variation_name }}
                                                        </td>
                                                        @php $variation_first_row = false; @endphp
                                                    @endif

                                                    <td style="text-align: center;">{{ $second_variation->variation_name }}</td>
                                                    @php $sv_stock = $stock_data[$product->id]['second_variations'][$second_variation->id]; @endphp
                                                    <td style="text-align: center;"><span
                                                            class="{{ $sv_stock <= $product->low_stock_threshold ? 'text-danger' : '' }}">{{ $sv_stock }}</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                @if ($first_row)
                                                    <td style="text-align: center;" rowspan="{{ $total_rows }}">{{ $row_number }}</td>
                                                    <td style="text-align: center;" rowspan="{{ $total_rows }}">
                                                        @if(!empty($product->mall))
                                                            {{ isset($data['backendlang']['backendlang']['Point_Product']) ? $data['backendlang']['backendlang']['Point_Product'] : '' }}
                                                        @else
                                                            {{ isset($data['backendlang']['backendlang']['Normal_Product']) ? $data['backendlang']['backendlang']['Normal_Product'] : '' }}
                                                        @endif
                                                    </td>
                                                    <td style="text-align: center;" rowspan="{{ $total_rows }}">{{ $product->product_name }}</td>
                                                    <td style="text-align: center;" rowspan="{{ $total_rows }}">
                                                        {{ !empty($product->category_name) ? $product->category_name : 'N/A' }}</td>
                                                    <td style="text-align: center;" rowspan="{{ $total_rows }}">
                                                        {{ !empty($product->product_code) ? $product->product_code : 'N/A'}}</td>
                                                    @php $first_row = false; @endphp
                                                @endif

                                                <td style="text-align: center;">{{ $variation->variation_name }}</td>
                                                <td style="text-align: center;">-</td>
                                                @php $v_stock = $stock_data[$product->id]['variations'][$variation->id]; @endphp
                                                <td style="text-align: center;"><span
                                                        class="{{ $v_stock <= $product->low_stock_threshold ? 'text-danger' : '' }}">{{ $v_stock }}</span>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @else
                                    <tr>
                                        <td style="text-align: center;">{{ $row_number }}</td>
                                        <td style="text-align: center;">
                                            @if(!empty($product->mall))
                                                {{ isset($data['backendlang']['backendlang']['Point_Product']) ? $data['backendlang']['backendlang']['Point_Product'] : '' }}
                                            @else
                                                {{ isset($data['backendlang']['backendlang']['Normal_Product']) ? $data['backendlang']['backendlang']['Normal_Product'] : '' }}
                                            @endif
                                        </td>
                                        <td style="text-align: center;">{{ $product->product_name }}</td>
                                        <td style="text-align: center;">
                                            {{ !empty($product->category_name) ? $product->category_name : 'N/A'}}</td>
                                        <td style="text-align: center;">
                                            {{ !empty($product->product_code) ? $product->product_code : 'N/A'}}</td>
                                        <td style="text-align: center;">-</td>
                                        <td style="text-align: center;">-</td>
                                        @php $p_stock = $stock_data[$product->id]['product']; @endphp
                                        <td style="text-align: center;"><span
                                                class="{{ $p_stock <= $product->low_stock_threshold ? 'text-danger' : '' }}">{{ $p_stock }}</span>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        @endif
                    </tbody>

                </table>
                {{ $product_stock_data->links() }}
            </div>
        </div>
    </div>

    <div class="row form-group">
        <div class="col-sm-6">
            <div class="card ">
                <div class="card-header">
                    <h4>{{ isset($data['backendlang']['backendlang']['Top10_Agent_Sales_Ranking']) ? $data['backendlang']['backendlang']['Top10_Agent_Sales_Ranking'] : '' }}
                    </h4>
                </div>
                <div class="card-body cart-body-mobile">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="text-align: center;">
                                    {{ isset($data['backendlang']['backendlang']['Ranking']) ? $data['backendlang']['backendlang']['Ranking'] : '' }}
                                </th>
                                <th style="text-align: center;">
                                    {{ isset($data['backendlang']['backendlang']['Agent']) ? $data['backendlang']['backendlang']['Agent'] : '' }}
                                </th>
                                <th style="text-align: center;">
                                    {{ isset($data['backendlang']['backendlang']['Agent_Level']) ? $data['backendlang']['backendlang']['Agent_Level'] : '' }}
                                </th>
                                <th style="text-align: center;">
                                    {{ isset($data['backendlang']['backendlang']['Agent_Sales']) ? $data['backendlang']['backendlang']['Agent_Sales'] : '' }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>

                            @for($a = 0; $a < 10; $a++)
                                <tr>
                                    <td height="80" align="center">
                                        @if($a == 0)
                                            <img src="{{ asset('images/cfa0077c100a0a4c7bc6f935790fd0d1.png') }}" width="50">
                                        @elseif($a == 1)
                                            <img src="{{ asset('images/imgbin_trophy-champion-cup-png.png') }}" width="25">
                                        @elseif($a == 2)
                                            <img src="{{ asset('images/Trophy_Cup_Bronze_PNG_Clipart.png') }}" width="25">
                                        @else
                                            {{ $a + 1 }}
                                        @endif
                                    </td>
                                    <td style="text-align: center; height: 80px;">
                                        @if(!empty($get_top_agent_sales_ranking[$a]->profile_logo))
                                            <!-- <img src="" width="40" style="border-radius: 100%;"> -->
                                            <div style="border-radius: 100%;
                                                            background-image: url('{{ asset($get_top_agent_sales_ranking[$a]->profile_logo) }}');
                                                            background-repeat: no-repeat;
                                                            background-size: cover;
                                                            background-position: center;
                                                            width: 40px;
                                                            height: 40px;
                                                            margin: auto;">
                                            </div>
                                        @else
                                            <!-- <img src="{{ asset('images/images.png') }}" width="40"> -->
                                            <div style="border-radius: 100%;
                                                            background-image: url('{{ asset('images/images.png') }}');
                                                            background-repeat: no-repeat;
                                                            background-size: cover;
                                                            background-position: center;
                                                            width: 40px;
                                                            height: 40px;
                                                            margin: auto;">
                                            </div>
                                        @endif
                                        {!! !empty($get_top_agent_sales_ranking[$a]->f_name) ? $get_top_agent_sales_ranking[$a]->f_name : 'TBD' !!}
                                    </td>
                                    <td style="text-align: center; height: 80px;">
                                        @if(!empty($get_top_agent_sales_ranking[$a]->agent_lvl))
                                            {{ $get_top_agent_sales_ranking[$a]->agent_lvl }}
                                        @else
                                            TBD
                                        @endif
                                    </td>
                                    <td style="text-align: center; height: 80px;">
                                        {!! (!empty($get_top_agent_sales_ranking[$a]->totalSales)) ? number_format($get_top_agent_sales_ranking[$a]->totalSales, 2) : '0.00' !!}
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="card">
                <div class="card-header">
                    <h4>{{ isset($data['backendlang']['backendlang']['Top10_Product_Sales_Ranking']) ? $data['backendlang']['backendlang']['Top10_Product_Sales_Ranking'] : '' }}
                    </h4>
                </div>
                <div class="card-body cart-body-mobile">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="text-align: center;">
                                    {{ isset($data['backendlang']['backendlang']['Ranking']) ? $data['backendlang']['backendlang']['Ranking'] : '' }}
                                </th>
                                <th style="text-align: center;">
                                    {{ isset($data['backendlang']['backendlang']['product']) ? $data['backendlang']['backendlang']['product'] : '' }}
                                </th>
                                <th style="text-align: center;">
                                    {{ isset($data['backendlang']['backendlang']['Quantity']) ? $data['backendlang']['backendlang']['Quantity'] : '' }}
                                </th>
                                <th style="text-align: center;">
                                    {{ isset($data['backendlang']['backendlang']['Sales']) ? $data['backendlang']['backendlang']['Sales'] : '' }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @for($b = 0; $b < 10; $b++)
                                <tr>
                                    <td height="80" align="center">
                                        @if($b == 0)
                                            <img src="{{ asset('images/cfa0077c100a0a4c7bc6f935790fd0d1.png') }}" width="50">
                                        @elseif($b == 1)
                                            <img src="{{ asset('images/imgbin_trophy-champion-cup-png.png') }}" width="25">
                                        @elseif($b == 2)
                                            <img src="{{ asset('images/Trophy_Cup_Bronze_PNG_Clipart.png') }}" width="25">
                                        @else
                                            {{ $b + 1 }}
                                        @endif

                                    </td>
                                    <td align="center">
                                        @if(!empty($get_top_product_sales_ranking[$b]->product_image))
                                            <!-- <img src="" width="40" style="border-radius: 100%;"> -->
                                            <div style="border-radius: 100%;
                                                            background-image: url('{{ asset($get_top_product_sales_ranking[$b]->product_image) }}');
                                                            background-repeat: no-repeat;
                                                            background-size: cover;
                                                            background-position: center;
                                                            width: 40px;
                                                            height: 40px;
                                                            margin: auto;">
                                            </div>
                                        @else
                                            <!-- <img src="{{ asset('images/images.png') }}" width="40"> -->
                                            <div style="border-radius: 100%;
                                                            background-image: url('{{ asset('images/800x800.png') }}');
                                                            background-repeat: no-repeat;
                                                            background-size: cover;
                                                            background-position: center;
                                                            width: 40px;
                                                            height: 40px;
                                                            margin: auto;">
                                            </div>
                                        @endif
                                        {{ !empty($get_top_product_sales_ranking[$b]->product_name) ? $get_top_product_sales_ranking[$b]->product_name : 'TBD' }}
                                    </td>
                                    <td align="center">
                                        {{ !empty($get_top_product_sales_ranking[$b]->totalQuantity) ? $get_top_product_sales_ranking[$b]->totalQuantity : 'TBD' }}
                                    </td>
                                    <td align="center">
                                        {{ !empty($get_top_product_sales_ranking[$b]->totalSales) ? number_format($get_top_product_sales_ranking[$b]->totalSales, 2) : '0.00' }}
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="card">
                <div class="card-header">
                    <h4>{{ isset($data['backendlang']['backendlang']['Top10_Customer_Sales_Ranking']) ? $data['backendlang']['backendlang']['Top10_Customer_Sales_Ranking'] : '' }}
                    </h4>
                </div>
                <div class="card-body cart-body-mobile">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="text-align: center;">
                                    {{ isset($data['backendlang']['backendlang']['Ranking']) ? $data['backendlang']['backendlang']['Ranking'] : '' }}
                                </th>
                                <th style="text-align: center;">
                                    {{ isset($data['backendlang']['backendlang']['Customer']) ? $data['backendlang']['backendlang']['Customer'] : '' }}
                                </th>
                                <th style="text-align: center;">
                                    {{ isset($data['backendlang']['backendlang']['Customer_Sales']) ? $data['backendlang']['backendlang']['Customer_Sales'] : '' }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @for($c = 0; $c < 10; $c++)
                                <tr>
                                    <td height="80" align="center">
                                        @if($c == 0)
                                            <img src="{{ asset('images/cfa0077c100a0a4c7bc6f935790fd0d1.png') }}" width="50">
                                        @elseif($c == 1)
                                            <img src="{{ asset('images/imgbin_trophy-champion-cup-png.png') }}" width="25">
                                        @elseif($c == 2)
                                            <img src="{{ asset('images/Trophy_Cup_Bronze_PNG_Clipart.png') }}" width="25">
                                        @else
                                            {{ $c + 1 }}
                                        @endif

                                    </td>
                                    <td align="center">
                                        @if(!empty($get_top_customer_sales_ranking[$c]->profile_logo))
                                            <!-- <img src="" width="40" style="border-radius: 100%;"> -->
                                            <div style="border-radius: 100%;
                                                            background-image: url('{{ asset($get_top_customer_sales_ranking[$c]->profile_logo) }}');
                                                            background-repeat: no-repeat;
                                                            background-size: cover;
                                                            background-position: center;
                                                            width: 40px;
                                                            height: 40px;
                                                            margin: auto;">
                                            </div>
                                        @else
                                            <!-- <img src="{{ asset('images/images.png') }}" width="40"> -->
                                            <div style="border-radius: 100%;
                                                            background-image: url('{{ asset('images/800x800.png') }}');
                                                            background-repeat: no-repeat;
                                                            background-size: cover;
                                                            background-position: center;
                                                            width: 40px;
                                                            height: 40px;
                                                            margin: auto;">
                                            </div>
                                        @endif
                                        {{ !empty($get_top_customer_sales_ranking[$c]->f_name) ? $get_top_customer_sales_ranking[$c]->f_name : 'TBD' }}
                                    </td>
                                    <td align="center">
                                        {{ !empty($get_top_customer_sales_ranking[$c]->totalSales) ? number_format($get_top_customer_sales_ranking[$c]->totalSales, 2) : '0.00' }}
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <div class="col-sm-6">
            <div class="card ">
                <div class="card-header">
                    <h4>{{ isset($data['backendlang']['backendlang']['Top10_Commission_Earner_Ranking']) ? $data['backendlang']['backendlang']['Top10_Commission_Earner_Ranking'] : '' }}
                    </h4>
                </div>
                <div class="card-body cart-body-mobile">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="text-align: center;">
                                    {{ isset($data['backendlang']['backendlang']['Ranking']) ? $data['backendlang']['backendlang']['Ranking'] : '' }}
                                </th>
                                <th style="text-align: center;">
                                    {{ isset($data['backendlang']['backendlang']['Agent']) ? $data['backendlang']['backendlang']['Agent'] : '' }}
                                </th>
                                <th style="text-align: center;">
                                    {{ isset($data['backendlang']['backendlang']['Agent_Level']) ? $data['backendlang']['backendlang']['Agent_Level'] : '' }}
                                </th>
                                <th style="text-align: center;">
                                    {{ isset($data['backendlang']['backendlang']['Commission_Earned']) ? $data['backendlang']['backendlang']['Commission_Earned'] : '' }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>

                            @for($d = 0; $d < 10; $d++)
                                <tr>
                                    <td height="80" align="center">
                                        @if($d == 0)
                                            <img src="{{ asset('images/cfa0077c100a0a4c7bc6f935790fd0d1.png') }}" width="50">
                                        @elseif($d == 1)
                                            <img src="{{ asset('images/imgbin_trophy-champion-cup-png.png') }}" width="25">
                                        @elseif($d == 2)
                                            <img src="{{ asset('images/Trophy_Cup_Bronze_PNG_Clipart.png') }}" width="25">
                                        @else
                                            {{ $d + 1 }}
                                        @endif
                                    </td>
                                    <td style="text-align: center; height: 80px;">
                                        @if(!empty($get_top_agent_commission_ranking[$d]->profile_logo))
                                            <!-- <img src="" width="40" style="border-radius: 100%;"> -->
                                            <div style="border-radius: 100%;
                                                            background-image: url('{{ asset($get_top_agent_commission_ranking[$d]->profile_logo) }}');
                                                            background-repeat: no-repeat;
                                                            background-size: cover;
                                                            background-position: center;
                                                            width: 40px;
                                                            height: 40px;
                                                            margin: auto;">
                                            </div>
                                        @else
                                            <!-- <img src="{{ asset('images/images.png') }}" width="40"> -->
                                            <div style="border-radius: 100%;
                                                            background-image: url('{{ asset('images/images.png') }}');
                                                            background-repeat: no-repeat;
                                                            background-size: cover;
                                                            background-position: center;
                                                            width: 40px;
                                                            height: 40px;
                                                            margin: auto;">
                                            </div>
                                        @endif
                                        {!! !empty($get_top_agent_commission_ranking[$d]->f_name) ? $get_top_agent_commission_ranking[$d]->f_name : 'TBD' !!}
                                    </td>
                                    <td style="text-align: center; height: 80px;">
                                        @if(!empty($get_top_agent_commission_ranking[$d]->agent_lvl))
                                            {{ $get_top_agent_commission_ranking[$d]->agent_lvl }}
                                        @else
                                            TBD
                                        @endif
                                    </td>
                                    <td style="text-align: center; height: 80px;">
                                        {!! (!empty($get_top_agent_commission_ranking[$d]->totalComm)) ? number_format($get_top_agent_commission_ranking[$d]->totalComm, 2) : '0.00' !!}
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <form method="GET" action="{{ route('dashboard.dashboards.index') }}" id="filter-chart">
        <div class="card">
            <div class="card-body">
                <label>
                    {{ isset($data['backendlang']['backendlang']['Sales_Filter']) ? $data['backendlang']['backendlang']['Sales_Filter'] : '' }}
                </label>
                <hr>
                <div class="row">
                    <div class="col-sm-2">
                        <select class="form-control" name="filter_monthly_sales">
                            @foreach($data['loop_start_dates'] as $yearly)
                                <option {{ ($selected_monthly == $yearly) ? 'selected' : '' }} value="{{ $yearly }}">
                                    {{ $yearly }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <select class="form-control" name="filter_daily_sales">
                            @foreach($data['loop_monthly'] as $monthly)
                                <option {{ ($selected_daily == $monthly) ? 'selected' : '' }} value="{{ $monthly }}">
                                    @php
                                        $__monthKeyMap = ['01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'May', '06' => 'Jun', '07' => 'Jul', '08' => 'Aug', '09' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dec'];
                                        $__mKey = $__monthKeyMap[str_pad($monthly, 2, '0', STR_PAD_LEFT)] ?? null;
                                    @endphp
                                    {{ $__mKey && isset($data['backendlang']['backendlang'][$__mKey]) ? $data['backendlang']['backendlang'][$__mKey] : date('F', mktime(0, 0, 0, $monthly, 10)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm">
                        <button class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-search"></i>
                            {{ isset($data['backendlang']['backendlang']['Search_Sales']) ? $data['backendlang']['backendlang']['Search_Sales'] : '' }}
                        </button>
                        <a href="{{ route('dashboard.dashboards.index') }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-arrow-clockwise"></i>
                            {{ isset($data['backendlang']['backendlang']['Clear_Search']) ? $data['backendlang']['backendlang']['Clear_Search'] : '' }}
                        </a>
                    </div>
                </div>
            </div>
        </div>


        <div class="row form-group">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>{{ isset($data['backendlang']['backendlang']['Monthly_Sales']) ? $data['backendlang']['backendlang']['Monthly_Sales'] : '' }}
                        </h4>
                    </div>
                    <div class="card-body">
                        <div id="chart-profile-visit"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row form-group">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>{{ isset($data['backendlang']['backendlang']['Daily_Sales']) ? $data['backendlang']['backendlang']['Daily_Sales'] : '' }}
                        </h4>
                    </div>
                    <div class="card-body">
                        <div id="chart-profile-visit-daily"></div>
                    </div>
                </div>
            </div>
        </div>

        <form method="GET" action="{{ route('dashboard.dashboards.index') }}" id="filter-sales">
            <div class="card">
                <div class="card-body">
                    <label>
                        {{ isset($data['backendlang']['backendlang']['Sales_Filter']) ? $data['backendlang']['backendlang']['Sales_Filter'] : '' }}
                    </label>
                    <hr>
                    <div class="row">
                        <div class="col-sm-2">
                            <select class="form-control" name="filter_sales_year">
                                @foreach($data['loop_start_dates'] as $yearly)
                                    <option {{ (request('filter_sales_year', date('Y')) == $yearly) ? 'selected' : '' }}
                                        value="{{ $yearly }}">
                                        {{ $yearly }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <select class="form-control" name="filter_sales_month">
                                @for($i = 1; $i <= 12; $i++)
                                    <option {{ (request('filter_sales_month', date('m')) == str_pad($i, 2, '0', STR_PAD_LEFT)) ? 'selected' : '' }} value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}">
                                        @php
                                            $__monthKeyMap2 = ['01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'May', '06' => 'Jun', '07' => 'Jul', '08' => 'Aug', '09' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dec'];
                                            $__mKey2 = $__monthKeyMap2[str_pad($i, 2, '0', STR_PAD_LEFT)] ?? null;
                                        @endphp
                                        {{ $__mKey2 && isset($data['backendlang']['backendlang'][$__mKey2]) ? $data['backendlang']['backendlang'][$__mKey2] : date('F', mktime(0, 0, 0, $i, 1)) }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-sm">
                            <button type="submit" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-search"></i>
                                {{ isset($data['backendlang']['backendlang']['Search_Sales']) ? $data['backendlang']['backendlang']['Search_Sales'] : '' }}
                            </button>
                            <a href="{{ route('dashboard.dashboards.index') }}" class="btn btn-warning btn-sm">
                                <i class="bi bi-arrow-clockwise"></i>
                                {{ isset($data['backendlang']['backendlang']['Clear_Search']) ? $data['backendlang']['backendlang']['Clear_Search'] : '' }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div class="row form-group">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h4>{{ isset($data['backendlang']['backendlang']['Sales_By_Category_And_Subcategory']) ? $data['backendlang']['backendlang']['Sales_By_Category_And_Subcategory'] : '' }}
                            ({{ isset($data['backendlang']['backendlang'][$salesMonthKey]) ? $data['backendlang']['backendlang'][$salesMonthKey] : date('F', strtotime($sales_year . '-01-01')) }}
                            {{ $sales_year }})</h4>
                    </div>
                    <div class="card-body cart-body-mobile">
                        <table class="table table-bordered">
                            <thead>
                                <tr class="info">
                                    <th style="text-align:center;">
                                        {{ isset($data['backendlang']['backendlang']['No']) ? $data['backendlang']['backendlang']['No'] : '' }}
                                    </th>
                                    <th style="text-align:center;">
                                        {{ isset($data['backendlang']['backendlang']['Category']) ? $data['backendlang']['backendlang']['Category'] : '' }}
                                    </th>
                                    <th style="text-align:center;">
                                        {{ isset($data['backendlang']['backendlang']['subCategory']) ? $data['backendlang']['backendlang']['subCategory'] : '' }}
                                    </th>
                                    <th style="text-align:center;">
                                        {{ isset($data['backendlang']['backendlang']['Amount']) ? $data['backendlang']['backendlang']['Amount'] : '' }}
                                        ({{ $data['currency_code'] }})
                                        @if(empty(request('totalSales_desc')) && empty(request('totalSales_asc')))
                                            <a href="{{ route('dashboard.dashboards.index', ['totalSales_desc=DESC']) }}"
                                                class="{{ !empty(request('totalSales_desc')) ? 'selected' : '' }}">
                                                <i class="bi bi-sort-down" style="color: white;"></i>
                                                <input type="hidden" name="sort_data" value="0">
                                            </a>
                                        @else
                                            @if(!empty(request('totalSales_desc')))
                                                <a href="{{ route('dashboard.dashboards.index', ['totalSales_asc=ASC']) }}"
                                                    class="{{ !empty(request('totalSales_asc')) ? 'selected' : '' }}">
                                                    <i class="bi bi-sort-down" style="color: white;"></i>
                                                    <input type="hidden" name="sort_data" value="1">
                                                </a>
                                            @elseif(!empty(request('totalSales_asc')))
                                                <a href="{{ route('dashboard.dashboards.index', ['totalSales_desc=DESC']) }}"
                                                    class="{{ !empty(request('totalSales_desc')) ? 'selected' : '' }}">
                                                    <i class="bi bi-sort-up" style="color: white;"></i>
                                                    <input type="hidden" name="sort_data" value="0">
                                                </a>
                                            @endif
                                        @endif
                                    </th>
                                    <th style="text-align:center;">
                                        {{ isset($data['backendlang']['backendlang']['Quantity']) ? $data['backendlang']['backendlang']['Quantity'] : '' }}
                                        @if(empty(request('totalQuantity_desc')) && empty(request('totalQuantity_asc')))
                                            <a href="{{ route('dashboard.dashboards.index', ['totalQuantity_desc=DESC']) }}"
                                                class="{{ !empty(request('totalQuantity_desc')) ? 'selected' : '' }}">
                                                <i class="bi bi-sort-down" style="color: white;"></i>
                                                <input type="hidden" name="sort_data" value="0">
                                            </a>
                                        @else
                                            @if(!empty(request('totalQuantity_desc')))
                                                <a href="{{ route('dashboard.dashboards.index', ['totalQuantity_asc=ASC']) }}"
                                                    class="{{ !empty(request('totalQuantity_asc')) ? 'selected' : '' }}">
                                                    <i class="bi bi-sort-down" style="color: white;"></i>
                                                    <input type="hidden" name="sort_data" value="1">
                                                </a>
                                            @elseif(!empty(request('totalQuantity_asc')))
                                                <a href="{{ route('dashboard.dashboards.index', ['totalQuantity_desc=DESC']) }}"
                                                    class="{{ !empty(request('totalQuantity_desc')) ? 'selected' : '' }}">
                                                    <i class="bi bi-sort-up" style="color: white;"></i>
                                                    <input type="hidden" name="sort_data" value="0">
                                                </a>
                                            @endif
                                        @endif
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $rowNumber = 1;
                                    $groupedByCategory = $filteredSalesByCatSub->groupBy('category_name');
                                @endphp

                                @forelse($groupedByCategory as $categoryName => $rows)
                                    @php $rowspan = $rows->count(); @endphp

                                    @foreach($rows as $i => $row)
                                        <tr>
                                            <td style="text-align:center;">{{ $rowNumber++ }}</td>

                                            @if($i === 0)
                                                <td style="text-align:center;" rowspan="{{ $rowspan }}">
                                                    {{ $categoryName ?: 'N/A' }}
                                                </td>
                                            @endif

                                            {{-- Subcategory --}}
                                            <td style="text-align:center;">
                                                {{ $row->sub_category_name ?: 'N/A' }}
                                            </td>

                                            {{-- Sales --}}
                                            <td style="text-align:center;">
                                                {{ number_format($row->totalSales, 2) }}
                                            </td>

                                            {{-- Quantity --}}
                                            <td style="text-align:center;">
                                                {{ number_format($row->totalQuantity) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @empty
                                    <tr>
                                        <td colspan="5" style="text-align:center;">
                                            {{ isset($data['backendlang']['backendlang']['No_Data_For_This_Period']) ? $data['backendlang']['backendlang']['No_Data_For_This_Period'] : '' }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h4>{{ isset($data['backendlang']['backendlang']['Sales_Distribution_By_Category']) ? $data['backendlang']['backendlang']['Sales_Distribution_By_Category'] : '' }}
                            ({{ isset($data['backendlang']['backendlang'][$salesMonthKey]) ? $data['backendlang']['backendlang'][$salesMonthKey] : date('F', strtotime($sales_year . '-01-01')) }}
                            {{ $sales_year }})</h4>
                    </div>
                    <div class="card-body">
                        <div class="mt-4 donut-wrapper" style="position: relative;">
                            <div id="sales-donut-chart"></div>
                            <div class="donut-center"
                                style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);text-align:center;pointer-events:none;">
                                <div style="font-weight:700;font-size:20px;">
                                    {{ isset($data['backendlang']['backendlang'][$salesMonthKey]) ? $data['backendlang']['backendlang'][$salesMonthKey] : date('F', strtotime($sales_year . '-01-01')) }}
                                    {{ $sales_year }}</div>
                                <div style="font-weight:500;font-size:16px;">{{ $data['currency_code'] }}
                                    {{ number_format($filteredTotalSalesAmount, 2) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </form>



    <form method="GET" action="{{ route('dashboard.dashboards.index') }}" id="filter-commission">
        <div class="card">
            <div class="card-body">
                <label>
                    {{ isset($data['backendlang']['backendlang']['Commission_Filter']) ? $data['backendlang']['backendlang']['Commission_Filter'] : '' }}
                </label>
                <hr>
                <div class="row">
                    <div class="col-sm-2">
                        <select class="form-control" name="filter_commission_year">
                            @foreach($data['loop_start_dates'] as $yearly)
                                <option {{ (request('filter_commission_year', date('Y')) == $yearly) ? 'selected' : '' }}
                                    value="{{ $yearly }}">
                                    {{ $yearly }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <select class="form-control" name="filter_commission_month">
                            @foreach($data['loop_monthly'] as $monthly)
                                <option {{ (request('filter_commission_month', date('m')) == $monthly) ? 'selected' : '' }}
                                    value="{{ $monthly }}">
                                    @php
                                        $__monthKeyMap3 = ['01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'May', '06' => 'Jun', '07' => 'Jul', '08' => 'Aug', '09' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dec'];
                                        $__mKey3 = $__monthKeyMap3[str_pad($monthly, 2, '0', STR_PAD_LEFT)] ?? null;
                                    @endphp
                                    {{ $__mKey3 && isset($data['backendlang']['backendlang'][$__mKey3]) ? $data['backendlang']['backendlang'][$__mKey3] : date('F', mktime(0, 0, 0, $monthly, 10)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm">
                        <button class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-search"></i>
                            {{ isset($data['backendlang']['backendlang']['Search_Commission']) ? $data['backendlang']['backendlang']['Search_Commission'] : '' }}
                        </button>
                        <a href="{{ route('dashboard.dashboards.index') }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-arrow-clockwise"></i>
                            {{ isset($data['backendlang']['backendlang']['Clear_Commission_Filter']) ? $data['backendlang']['backendlang']['Clear_Commission_Filter'] : '' }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header">
                        <h4>{{ isset($data['backendlang']['backendlang']['Commission_Summary_Filtered']) ? $data['backendlang']['backendlang']['Commission_Summary_Filtered'] : '' }}
                            -
                            {{ isset($data['backendlang']['backendlang'][$commissionMonthKey]) ? $data['backendlang']['backendlang'][$commissionMonthKey] : date('F', strtotime($commission_year . '-01-01')) }}
                            {{ $commission_year }} </h4>
                    </div>
                    <div class="card-body cart-body-mobile">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="text-align: center;">
                                        {{ isset($data['backendlang']['backendlang']['No']) ? $data['backendlang']['backendlang']['No'] : '' }}
                                    </th>
                                    <th style="text-align: center;">
                                        {{ isset($data['backendlang']['backendlang']['Commission_Type']) ? $data['backendlang']['backendlang']['Commission_Type'] : '' }}
                                    </th>
                                    <th style="text-align: center;">
                                        {{ isset($data['backendlang']['backendlang']['Commission_Amount']) ? $data['backendlang']['backendlang']['Commission_Amount'] : '' }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i = 1; @endphp
                                @php
                                    // Map numeric type to backend language keys
                                    $typeKeyMap = [
                                        1 => 'Hierarchy_Bonus',
                                        6 => 'Referral_Reward',
                                        2 => 'Order_Rebate',
                                        99 => 'Prize_Pool',
                                        3 => 'Performance_Reward',
                                        4 => 'Team_Reward',
                                        5 => 'Team_Reward'
                                    ];
                                @endphp
                                @foreach ($commissionType as $type => $label)
                                    @php
                                        $amount = isset($filteredCommissionSummary[$type]) ? $filteredCommissionSummary[$type]->total_amount : 0;
                                        $typeKey = $typeKeyMap[$type] ?? null;
                                        $translatedLabel = $typeKey && isset($data['backendlang']['backendlang'][$typeKey])
                                            ? $data['backendlang']['backendlang'][$typeKey]
                                            : $label;
                                     @endphp
                                    <tr>
                                        <td style="text-align: center;">{{ $i++ }}</td>
                                        <td style="text-align: center;">{{ $translatedLabel }}</td>
                                        <td style="text-align: center;">{{ number_format($amount, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @php
                            $filteredTotalCommission = 0;
                            foreach ($commissionType as $type => $label) {
                                $amount = isset($filteredCommissionSummary[$type]) ? $filteredCommissionSummary[$type]->total_amount : 0;
                                $filteredTotalCommission += $amount;
                            }
                        @endphp
                        <div class="mt-4 donut-wrapper" style="position: relative;">
                            <div id="filtered-commission-donut-chart"></div>
                            <div class="donut-center"
                                style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);text-align:center;pointer-events:none;">
                                <div style="font-weight:700;font-size:20px;">
                                    {{ isset($data['backendlang']['backendlang'][$commissionMonthKey]) ? $data['backendlang']['backendlang'][$commissionMonthKey] : date('F', strtotime($commission_year . '-01-01')) }}
                                    {{ $commission_year }} </div>
                                <div style="font-weight:500;font-size:16px;">{{ $data['currency_code'] }}
                                    {{ number_format($filteredTotalCommission, 2) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header">
                        <h4>{{ isset($data['backendlang']['backendlang']['Commission_Summary']) ? $data['backendlang']['backendlang']['Commission_Summary'] : '' }}
                            ({{ isset($data['backendlang']['backendlang']['Current_Month']) ? $data['backendlang']['backendlang']['Current_Month'] : '' }}
                            -
                            {{ isset($data['backendlang']['backendlang'][$currentMonthKey]) ? $data['backendlang']['backendlang'][$currentMonthKey] : date('F') }}
                            {{ $currentYear }})</h4>
                    </div>
                    <div class="card-body cart-body-mobile">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="text-align: center;">
                                        {{ isset($data['backendlang']['backendlang']['No']) ? $data['backendlang']['backendlang']['No'] : '' }}
                                    </th>
                                    <th style="text-align: center;">
                                        {{ isset($data['backendlang']['backendlang']['Commission_Type']) ? $data['backendlang']['backendlang']['Commission_Type'] : '' }}
                                    </th>
                                    <th style="text-align: center;">
                                        {{ isset($data['backendlang']['backendlang']['Commission_Amount']) ? $data['backendlang']['backendlang']['Commission_Amount'] : '' }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i = 1; @endphp
                                @foreach ($commissionType as $type => $label)
                                    @php
                                        $amount = isset($commissionSummary[$type]) ? $commissionSummary[$type]->total_amount : 0;
                                        $typeKey = $typeKeyMap[$type] ?? null;
                                        $translatedLabel = $typeKey && isset($data['backendlang']['backendlang'][$typeKey])
                                            ? $data['backendlang']['backendlang'][$typeKey]
                                            : $label;
                                     @endphp
                                    <tr>
                                        <td style="text-align: center;">{{ $i++ }}</td>
                                        <td style="text-align: center;">{{ $translatedLabel }}</td>
                                        <td style="text-align: center;">{{ number_format($amount, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @php
                            $__totalCommissionAmount = 0;
                            foreach ($commissionType as $type => $label) {
                                $amount = isset($commissionSummary[$type]) ? $commissionSummary[$type]->total_amount : 0;
                                $__totalCommissionAmount += $amount;
                            }
                        @endphp
                        <div class="mt-4 donut-wrapper" style="position: relative;">
                            <div id="commission-donut-chart"></div>
                            <div class="donut-center"
                                style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);text-align:center;pointer-events:none;">
                                <div style="font-weight:700;font-size:20px;">
                                    {{ isset($data['backendlang']['backendlang'][$currentMonthKey]) ? $data['backendlang']['backendlang'][$currentMonthKey] : date('F') }}
                                    {{ $currentYear }}</div>
                                <div style="font-weight:500;font-size:16px;">{{ $data['currency_code'] }}
                                    {{ number_format($__totalCommissionAmount, 2) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </form>
    <section class="row">
        <div class="col-6 col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                            <div class="stats-icon red mb-2">
                                <i class="iconly-boldShow"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">
                                {{ isset($data['backendlang']['backendlang']['Monthly_Sales']) ? $data['backendlang']['backendlang']['Monthly_Sales'] : '' }}
                            </h6>
                            <h6 class="font-extrabold mb-0">
                                {{ $data['currency_code'] }}{{ number_format($monthlySales->monthlySales, 2) }}
                            </h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                            <div class="stats-icon green mb-2">
                                <i class="iconly-boldShow"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">
                                {{ isset($data['backendlang']['backendlang']['Monthly_Commission']) ? $data['backendlang']['backendlang']['Monthly_Commission'] : '' }}
                            </h6>
                            <h6 class="font-extrabold mb-0">
                                {{ $data['currency_code'] }}{{ number_format($monthlyCommission->monthlyCommission, 2) }}
                            </h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                            <div class="stats-icon blue mb-2">
                                <i class="iconly-boldShow"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">
                                {{ isset($data['backendlang']['backendlang']['Monthly_Withdrawal']) ? $data['backendlang']['backendlang']['Monthly_Withdrawal'] : '' }}
                            </h6>
                            <h6 class="font-extrabold mb-0">
                                {{ $data['currency_code'] }}{{ number_format($monthlyWithdrawalTransaction->monthlyWithdrawal, 2) }}
                            </h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="row">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                            <div class="stats-icon purple mb-2">
                                <i class="iconly-boldShow"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">
                                {{ isset($data['backendlang']['backendlang']['Monthly_Topup']) ? $data['backendlang']['backendlang']['Monthly_Topup'] : '' }}
                            </h6>
                            <h6 class="font-extrabold mb-0">
                                {{ $data['currency_code'] }}{{ number_format($monthlyTopup->monthlyTopup, 2) }}
                            </h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/drilldown.js"></script>

    <script type="text/javascript">
        // $(document).ready(function () {
        //     if ("geolocation" in navigator) {
        //         navigator.geolocation.getCurrentPosition(function (position) {
        //             var latitude = position.coords.latitude;
        //             var longitude = position.coords.longitude;

        //             console.log("Latitude: " + latitude);
        //             console.log("Longitude: " + longitude);
        //         });
        //     } else {
        //         console.log("Geolocation is not supported by this browser.");
        //     }
        // });

        const API_KEY = 'pk_test_088f31aba13aa7070b73f1d271340634';
        const SECRET = 'sk_test_wv7OfmvqOjnkjmLe0xmxvGgNnLNuEnXPPgPjG4LpdsVDinaiEvChSxnIJ4+wyR0Z';
        const time = new Date().getTime().toString(); // => `1545880607433`

        const method = 'POST';
        const path = 'https://rest.sandbox.lalamove.com/v3/quotations';
        const asd = JSON.stringify({
            "data": {
                "scheduleAt": "2020-09-01T14:30:00.00Z", // optional
                "serviceType": "MOTORCYCLE",
                "specialRequests": ["TOLL_FEE_10", "PURCHASE_SERVICE_1"], // optional
                "language": "en_HK",
                "stops": [{
                    "coordinates": {
                        "lat": "5.3496528",
                        "lng": "100.3066776"
                    },
                    "address": "Vilaris Courtyard Homes The Sanctuary, 43a, Lorong Batu Uban 1, The Century, 11700 Gelugor, Penang"
                }],
                "item": { // Recommended
                    "quantity": "3",
                    "weight": "LESS_THAN_3KG",
                    "categories": ["FOOD_DELIVERY", "OFFICE_ITEM"],
                    "handlingInstructions": ["KEEP_UPRIGHT"]
                },
                "isRouteOptimized": true, // optional
            }
        }); // => the whole body for '/v3/quotations'

        const rawSignature = `${time}\r\n${method}\r\n${path}\r\n\r\n${asd}`;
        //const rawSignature = `${time}\r\n${method}\r\n${path}\r\n\r\n`; if the method is GET
        // => '1546222219293\r\nPOST\r\n/v3/quotations\r\n\r\n{\n"data":{...}'
        const SIGNATURE = CryptoJS.HmacSHA256(rawSignature, SECRET).toString();

        const TOKEN = `${API_KEY}:${time}:${SIGNATURE}`

        const apiUrl = 'https://rest.sandbox.lalamove.com/v3/quotations';

        // Make a GET request to the API
        fetch(apiUrl)
            .then(response => {
                // Check if the request was successful (status code 200)
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                console.log(123);
                // Parse the JSON in the response
                return response.json();
            })
            .then(data => {
                // Handle the data from the API
                console.log(data);
            })
            .catch(error => {
                // Handle errors
                console.error('Fetch error:', error);
            });

        var optionsProfileVisit = {
            annotations: {
                position: "back",
            },
            dataLabels: {
                enabled: false,
            },
            chart: {
                type: "bar",
                height: 300,
            },
            fill: {
                opacity: 1,
            },
            plotOptions: {},
            series: [
                {
                    name: "sales",
                    data: [{{ $implode_sales }}],
                },
            ],
            colors: "#435ebe",
            xaxis: {
                categories: [
                    "{{ isset($data['backendlang']['backendlang']['Jan']) ? $data['backendlang']['backendlang']['Jan'] : '' }}",
                    "{{ isset($data['backendlang']['backendlang']['Feb']) ? $data['backendlang']['backendlang']['Feb'] : '' }}",
                    "{{ isset($data['backendlang']['backendlang']['Mar']) ? $data['backendlang']['backendlang']['Mar'] : '' }}",
                    "{{ isset($data['backendlang']['backendlang']['Apr']) ? $data['backendlang']['backendlang']['Apr'] : '' }}",
                    "{{ isset($data['backendlang']['backendlang']['May']) ? $data['backendlang']['backendlang']['May'] : '' }}",
                    "{{ isset($data['backendlang']['backendlang']['Jun']) ? $data['backendlang']['backendlang']['Jun'] : '' }}",
                    "{{ isset($data['backendlang']['backendlang']['Jul']) ? $data['backendlang']['backendlang']['Jul'] : '' }}",
                    "{{ isset($data['backendlang']['backendlang']['Aug']) ? $data['backendlang']['backendlang']['Aug'] : '' }}",
                    "{{ isset($data['backendlang']['backendlang']['Sep']) ? $data['backendlang']['backendlang']['Sep'] : '' }}",
                    "{{ isset($data['backendlang']['backendlang']['Oct']) ? $data['backendlang']['backendlang']['Oct'] : '' }}",
                    "{{ isset($data['backendlang']['backendlang']['Nov']) ? $data['backendlang']['backendlang']['Nov'] : '' }}",
                    "{{ isset($data['backendlang']['backendlang']['Dec']) ? $data['backendlang']['backendlang']['Dec'] : '' }}",
                ],
            },
        }
        var optionsProfileVisitDaily = {
            annotations: {
                position: "back",
            },
            dataLabels: {
                enabled: false,
            },
            chart: {
                type: "bar",
                height: 300,
            },
            fill: {
                opacity: 1,
            },
            plotOptions: {},
            series: [
                {
                    name: "sales",
                    data: [{{ $implode_days_sales }}],
                },
            ],
            colors: "#435ebe",
            xaxis: {
                categories: [
                    {{ $implode_days }}
                ],
            },
        }

        let optionsVisitorsProfile = {
            series: [70, 30],
            labels: ["Male", "Female"],
            colors: ["#435ebe", "#55c6e8"],
            chart: {
                type: "donut",
                width: "100%",
                height: "350px",
            },
            legend: {
                position: "bottom",
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: "30%",
                    },
                },
            },
        }


        var optionsEurope = {
            series: [
                {
                    name: "series1",
                    data: [310, 800, 600, 430, 540, 340, 605, 805, 430, 540, 340, 605],
                },
            ],
            chart: {
                height: 80,
                type: "area",
                toolbar: {
                    show: false,
                },
            },
            colors: ["#5350e9"],
            stroke: {
                width: 2,
            },
            grid: {
                show: false,
            },
            dataLabels: {
                enabled: false,
            },
            xaxis: {
                type: "datetime",
                categories: [
                    "2018-09-19T00:00:00.000Z",
                    "2018-09-19T01:30:00.000Z",
                    "2018-09-19T02:30:00.000Z",
                    "2018-09-19T03:30:00.000Z",
                    "2018-09-19T04:30:00.000Z",
                    "2018-09-19T05:30:00.000Z",
                    "2018-09-19T06:30:00.000Z",
                    "2018-09-19T07:30:00.000Z",
                    "2018-09-19T08:30:00.000Z",
                    "2018-09-19T09:30:00.000Z",
                    "2018-09-19T10:30:00.000Z",
                    "2018-09-19T11:30:00.000Z",
                ],
                axisBorder: {
                    show: false,
                },
                axisTicks: {
                    show: false,
                },
                labels: {
                    show: false,
                },
            },
            show: false,
            yaxis: {
                labels: {
                    show: false,
                },
            },
            tooltip: {
                x: {
                    format: "dd/MM/yy HH:mm",
                },
            },
        }

        let optionsAmerica = {
            ...optionsEurope,
            colors: ["#008b75"],
        }
        let optionsIndonesia = {
            ...optionsEurope,
            colors: ["#dc3545"],
        }

        var chartProfileVisit = new ApexCharts(
            document.querySelector("#chart-profile-visit"),
            optionsProfileVisit
        )
        var chartVisitorsProfile = new ApexCharts(
            document.getElementById("chart-visitors-profile"),
            optionsVisitorsProfile
        )
        var chartEurope = new ApexCharts(
            document.querySelector("#chart-europe"),
            optionsEurope
        )
        var chartAmerica = new ApexCharts(
            document.querySelector("#chart-america"),
            optionsAmerica
        )
        var chartIndonesia = new ApexCharts(
            document.querySelector("#chart-indonesia"),
            optionsIndonesia
        )
        var chartProfileVisitDaily = new ApexCharts(
            document.querySelector("#chart-profile-visit-daily"),
            optionsProfileVisitDaily
        )
        chartIndonesia.render()
        chartAmerica.render()
        chartEurope.render()
        chartProfileVisit.render()
        chartVisitorsProfile.render()
        chartProfileVisitDaily.render()


        $('.filter_chart_select').change(function () {
            $('#filter-chart').submit();
        });

        // Handle commission filter form submission
        $('#filter-commission select').change(function () {
            $('#filter-commission').submit();
        });

        // Handle sales filter form submission
        $('#filter-sales select').change(function () {
            $('#filter-sales').submit();
        });


        Highcharts.chart('commission-donut-chart', {
            chart: {
                type: 'pie',
                height: 350,
                backgroundColor: 'transparent'
            },
            title: {
                text: null
            },
            plotOptions: {
                pie: {
                    innerSize: '50%',
                    depth: 45,
                    dataLabels: {
                        enabled: true,
                        connectorShape: 'straight',
                        connectorPadding: 3,
                        connectorWidth: 1,
                        connectorColor: '#999',
                        distance: 25,
                        formatter: function () {
                            return this.point.name + '<br/>' +
                                Highcharts.numberFormat(this.percentage, 1) + '%';
                        },
                        style: {
                            fontSize: '12px',
                            fontWeight: '500',
                            color: '#333',
                            textOutline: 'none'
                        }
                    }
                }
            },
            series: [{
                name: 'Commission',
                data: [
                    @foreach ($commissionType as $type => $label)
                        @php
                            $amount = isset($commissionSummary[$type]) ? $commissionSummary[$type]->total_amount : 0;
                            $percentage = $__totalCommissionAmount > 0 ? ($amount / $__totalCommissionAmount) * 100 : 0;
                            $typeKey = $typeKeyMap[$type] ?? null;
                            $translatedLabel = $typeKey && isset($data['backendlang']['backendlang'][$typeKey])
                                ? $data['backendlang']['backendlang'][$typeKey]
                                : $label;
                        @endphp
                        @if($amount > 0)
                                {
                                name: '{{ $translatedLabel }}',
                                y: {{ $percentage }},
                                color: '{{ ["#FFA500", "#87CEEB", "#008B8B", "#466C8B", "#191970", "#FF6B6B"][$loop->index] }}'
                            },
                        @endif
                    @endforeach
                ]
            }],
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            legend: {
                enabled: false
            },
            credits: {
                enabled: false
            },
            exporting: {
                enabled: false
            }
        });


        Highcharts.chart('filtered-commission-donut-chart', {
            chart: {
                type: 'pie',
                height: 350,
                backgroundColor: 'transparent'
            },
            title: {
                text: null
            },
            plotOptions: {
                pie: {
                    innerSize: '50%',
                    depth: 45,
                    dataLabels: {
                        enabled: true,
                        connectorShape: 'straight',
                        connectorPadding: 3,
                        connectorWidth: 1,
                        connectorColor: '#999',
                        distance: 25,
                        formatter: function () {
                            return this.point.name + '<br/>' +
                                Highcharts.numberFormat(this.percentage, 1) + '%';
                        },
                        style: {
                            fontSize: '12px',
                            fontWeight: '500',
                            color: '#333',
                            textOutline: 'none'
                        }
                    }
                }
            },
            series: [{
                name: 'Commission',
                data: [
                    @foreach ($commissionType as $type => $label)
                        @php
                            $amount = isset($filteredCommissionSummary[$type]) ? $filteredCommissionSummary[$type]->total_amount : 0;
                            $percentage = $filteredTotalCommission > 0 ? ($amount / $filteredTotalCommission) * 100 : 0;
                            $typeKey = $typeKeyMap[$type] ?? null;
                            $translatedLabel = $typeKey && isset($data['backendlang']['backendlang'][$typeKey])
                                ? $data['backendlang']['backendlang'][$typeKey]
                                : $label;
                        @endphp
                        @if($amount > 0)
                                {
                                name: '{{ $translatedLabel }}',
                                y: {{ $percentage }},
                                color: '{{ ["#FFA500", "#87CEEB", "#008B8B", "#466C8B", "#191970", "#FF6B6B"][$loop->index] }}'
                            },
                        @endif
                    @endforeach
                ]
            }],
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            legend: {
                enabled: false
            },
            credits: {
                enabled: false
            },
            exporting: {
                enabled: false
            }
        });

        // Sales Donut Chart
        Highcharts.chart('sales-donut-chart', {
            chart: {
                type: 'pie',
                height: 350,
                backgroundColor: 'transparent'
            },
            title: {
                text: null
            },
            plotOptions: {
                pie: {
                    innerSize: '50%',
                    depth: 45,
                    center: ['50%', '50%'],
                    size: '80%',
                    dataLabels: {
                        enabled: true,
                        connectorShape: 'straight',
                        connectorPadding: 3,
                        connectorWidth: 1,
                        connectorColor: '#999',
                        distance: 25,
                        formatter: function () {
                            return this.point.name + '<br/>' +
                                Highcharts.numberFormat(this.percentage, 1) + '%';
                        },
                        style: {
                            fontSize: '12px',
                            fontWeight: '500',
                            color: '#333',
                            textOutline: 'none',
                        }
                    }
                }
            },
            series: [{
                name: 'Sales',
                data: [
                    @foreach ($filteredSalesChartData as $index => $category)
                        @if($category['y'] > 0)
                                {
                                name: '{{ $category['name'] }}',
                                y: {{ $category['y'] }},
                                color: '{{ ["#FF6B6B", "#4ECDC4", "#45B7D1", "#96CEB4", "#FFEAA7", "#DDA0DD", "#98D8C8", "#F7DC6F", "#BB8FCE", "#85C1E9"][$index % 10] }}',
                                drilldown: '{{ $category['name'] }}'
                            },
                        @endif
                    @endforeach
                ]
            }],
            drilldown: {
                series: [
                    @foreach ($filteredSalesChartData as $category)
                        @if($category['y'] > 0)
                                {
                                name: '{{ $category['name'] }}',
                                id: '{{ $category['name'] }}',
                                data: [
                                    @foreach ($category['subcategories'] as $subcategory)
                                        @if($subcategory['y'] > 0)
                                            ['{{ $subcategory['name'] }}', {{ $subcategory['y'] }}],
                                        @endif
                                    @endforeach
                                    ]
                            },
                        @endif
                    @endforeach
                ]
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{{ $data['currency_code'] }} {point.y:,.2f}</b><br/>Percentage: <b>{point.percentage:.1f}%</b>'
            },
            legend: {
                enabled: false
            },
            credits: {
                enabled: false
            },
            exporting: {
                enabled: false
            }
        });


    </script>
@endsection