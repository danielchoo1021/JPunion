@if(Request::segment(1) != 'transaction_invoice' &&
    Request::segment(1) != 'print_withdrawal_list' &&
    Request::segment(1) != 'print_sales_report' && 
    Request::segment(1) != 'print_order_report' && 
    Request::segment(1) != 'print_commission_report')
<!-- <header id="header" class="header">
    <div class="top-left">
        <div class="navbar-header">
            @if(!empty($data['website_logo']))
            <a class="navbar-brand" href="{{ route('dashboard.dashboards.index') }}" style="font-size: 1rem;">
                <img src="{{ asset($data['website_logo']) }}" alt="Logo" style="max-width: 45px">
                {{ $data['website_name'] }} Backend
            </a>
            @else
            <a class="navbar-brand" href="{{ route('dashboard.dashboards.index') }}">
                <img src="{{ asset('images/logo/Vesson_Enterprise_Trans_Gold.png') }}" alt="Logo" style="max-width: 45px">
            </a>
            <a class="navbar-brand hidden" href="{{ route('dashboard.dashboards.index') }}">
                <img src="{{ asset('images/logo/Vesson_Enterprise_Trans_Gold.png') }}" alt="Logo" style="max-width: 45px">
            </a>
            @endif
            <a id="menuToggle" class="menutoggle"><i class="fa fa-bars"></i></a>
        </div>
    </div>
    <div class="top-right">
        <div class="header-menu">
            <div class="header-left">
            <div class="user-area dropdown float-right">
                <a href="#" class="dropdown-toggle active" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    @if(!empty(Auth::user()->profile_logo))
                    <img class="user-avatar rounded-circle" src="{{ asset(Auth::user()->profile_logo) }}" alt="User Avatar">
                    @else
                    <img class="user-avatar rounded-circle" src="{{ asset("images/images.png") }}" alt="User Avatar">
                    @endif
                </a>

                <div class="user-menu dropdown-menu">

                    <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fa fa-power -off"></i>
                        Logout
                    </a>
                    <form id="logout-form" action="{{ route('admin_logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>

        </div>
    </div>
</header> -->
@endif