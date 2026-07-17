@php
if($_SERVER['REMOTE_ADDR'] != '127.0.0.1'){
    if(Request::secure()){
    }else{
    $url = "https://". $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
    header("Location: $url");
    exit;   
    }    
}
@endphp
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ !empty($data['website_name']) ? $data['website_name'] : 'MHD' }} Backend</title>
    @if(!empty($data['website_logo']))
    <link rel="shortcut icon" href="{{ asset($data['web_setting']->fav_icon)  }}">
    <link rel="apple-touch-icon" href="{{ asset($data['web_setting']->fav_icon)  }}">
    @endif
    <!-- <link
      rel="shortcut icon"
      href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACEAAAAiCAYAAADRcLDBAAAEs2lUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4KPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iWE1QIENvcmUgNS41LjAiPgogPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4KICA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIgogICAgeG1sbnM6ZXhpZj0iaHR0cDovL25zLmFkb2JlLmNvbS9leGlmLzEuMC8iCiAgICB4bWxuczp0aWZmPSJodHRwOi8vbnMuYWRvYmUuY29tL3RpZmYvMS4wLyIKICAgIHhtbG5zOnBob3Rvc2hvcD0iaHR0cDovL25zLmFkb2JlLmNvbS9waG90b3Nob3AvMS4wLyIKICAgIHhtbG5zOnhtcD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLyIKICAgIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0vIgogICAgeG1sbnM6c3RFdnQ9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZUV2ZW50IyIKICAgZXhpZjpQaXhlbFhEaW1lbnNpb249IjMzIgogICBleGlmOlBpeGVsWURpbWVuc2lvbj0iMzQiCiAgIGV4aWY6Q29sb3JTcGFjZT0iMSIKICAgdGlmZjpJbWFnZVdpZHRoPSIzMyIKICAgdGlmZjpJbWFnZUxlbmd0aD0iMzQiCiAgIHRpZmY6UmVzb2x1dGlvblVuaXQ9IjIiCiAgIHRpZmY6WFJlc29sdXRpb249Ijk2LjAiCiAgIHRpZmY6WVJlc29sdXRpb249Ijk2LjAiCiAgIHBob3Rvc2hvcDpDb2xvck1vZGU9IjMiCiAgIHBob3Rvc2hvcDpJQ0NQcm9maWxlPSJzUkdCIElFQzYxOTY2LTIuMSIKICAgeG1wOk1vZGlmeURhdGU9IjIwMjItMDMtMzFUMTA6NTA6MjMrMDI6MDAiCiAgIHhtcDpNZXRhZGF0YURhdGU9IjIwMjItMDMtMzFUMTA6NTA6MjMrMDI6MDAiPgogICA8eG1wTU06SGlzdG9yeT4KICAgIDxyZGY6U2VxPgogICAgIDxyZGY6bGkKICAgICAgc3RFdnQ6YWN0aW9uPSJwcm9kdWNlZCIKICAgICAgc3RFdnQ6c29mdHdhcmVBZ2VudD0iQWZmaW5pdHkgRGVzaWduZXIgMS4xMC4xIgogICAgICBzdEV2dDp3aGVuPSIyMDIyLTAzLTMxVDEwOjUwOjIzKzAyOjAwIi8+CiAgICA8L3JkZjpTZXE+CiAgIDwveG1wTU06SGlzdG9yeT4KICA8L3JkZjpEZXNjcmlwdGlvbj4KIDwvcmRmOlJERj4KPC94OnhtcG1ldGE+Cjw/eHBhY2tldCBlbmQ9InIiPz5V57uAAAABgmlDQ1BzUkdCIElFQzYxOTY2LTIuMQAAKJF1kc8rRFEUxz9maORHo1hYKC9hISNGTWwsRn4VFmOUX5uZZ36oeTOv954kW2WrKLHxa8FfwFZZK0WkZClrYoOe87ypmWTO7dzzud97z+nec8ETzaiaWd4NWtYyIiNhZWZ2TvE946WZSjqoj6mmPjE1HKWkfdxR5sSbgFOr9Ll/rXoxYapQVik8oOqGJTwqPL5i6Q5vCzeo6dii8KlwpyEXFL519LjLLw6nXP5y2IhGBsFTJ6ykijhexGra0ITl5bRqmWU1fx/nJTWJ7PSUxBbxJkwijBBGYYwhBgnRQ7/MIQIE6ZIVJfK7f/MnyUmuKrPOKgZLpEhj0SnqslRPSEyKnpCRYdXp/9++msneoFu9JgwVT7b91ga+LfjetO3PQ9v+PgLvI1xkC/m5A+h7F32zoLXug38dzi4LWnwHzjeg8UGPGbFfySvuSSbh9QRqZ6H+Gqrm3Z7l9zm+h+iafNUV7O5Bu5z3L/wAdthn7QIme0YAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAJTSURBVFiF7Zi9axRBGIefEw2IdxFBRQsLWUTBaywSK4ubdSGVIY1Y6HZql8ZKCGIqwX/AYLmCgVQKfiDn7jZeEQMWfsSAHAiKqPiB5mIgELWYOW5vzc3O7niHhT/YZvY37/swM/vOzJbIqVq9uQ04CYwCI8AhYAlYAB4Dc7HnrOSJWcoJcBS4ARzQ2F4BZ2LPmTeNuykHwEWgkQGAet9QfiMZjUSt3hwD7psGTWgs9pwH1hC1enMYeA7sKwDxBqjGnvNdZzKZjqmCAKh+U1kmEwi3IEBbIsugnY5avTkEtIAtFhBrQCX2nLVehqyRqFoCAAwBh3WGLAhbgCRIYYinwLolwLqKUwwi9pxV4KUlxKKKUwxC6ZElRCPLYAJxGfhSEOCz6m8HEXvOB2CyIMSk6m8HoXQTmMkJcA2YNTHm3congOvATo3tE3A29pxbpnFzQSiQPcB55IFmFNgFfEQeahaAGZMpsIJIAZWAHcDX2HN+2cT6r39GxmvC9aPNwH5gO1BOPFuBVWAZue0vA9+A12EgjPadnhCuH1WAE8ivYAQ4ohKaagV4gvxi5oG7YSA2vApsCOH60WngKrA3R9IsvQUuhIGY00K4flQG7gHH/mLytB4C42EgfrQb0mV7us8AAMeBS8mGNMR4nwHamtBB7B4QRNdaS0M8GxDEog7iyoAguvJ0QYSBuAOcAt71Kfl7wA8DcTvZ2KtOlJEr+ByyQtqqhTyHTIeB+ONeqi3brh+VgIN0fohUgWGggizZFTplu12yW8iy/YLOGWMpDMTPXnl+Az9vj2HERYqPAAAAAElFTkSuQmCC"
      type="image/png"
    /> -->

    <link rel="stylesheet" href="{{ asset('frontend/plugins/owl-carousel/assets/owl.carousel.css') }}/">
    <link rel="stylesheet" href="{{ asset('NewBackend/assets/compiled/css/app.css') }}" />
    {{-- <link rel="stylesheet" href="{{ asset('NewBackend/assets/compiled/css/app-dark.css') }}" /> --}}
    <link rel="stylesheet" href="{{ asset('NewBackend/assets/compiled/css/iconly.css') }}" />

    <meta property="og:image" content="{{ asset($data['web_setting']->website_logo) }}">
    <link
      rel="stylesheet"
      href="{{ asset('NewBackend/assets/extensions/flatpickr/flatpickr.min.css') }}"
    />
    <!-- Old -->

        <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datetimepicker.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker3.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/css/daterangepicker.min.css') }}" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />

        <!-- Dropzone -->
        <link rel="stylesheet" href="{{ asset('assets/css/dropzone.min.css') }}" />
        <script src="{{ asset('assets/js/dropzone.min.js') }}"></script>
        <!-- EndDropzone -->
    
    <!-- EndOld -->
    @toastr_css
</head>
@yield('css')
<style type="text/css">

input:checked + .slider{
    background-color: #21f3a5;
}

.important-text{
    color: red;
}

.dropdown-menu li{
    padding-left: 20px;
    margin-top: 10px;   
}

.dropdown-menu {
    box-shadow: 0 0 4px rgba(0,0,0,0.2);
}

.filter_selected{
    background-color: #435ebe ;
    color: white ;
}

.filter_selected:hover{
    background-color: white ;
    color: #435ebe ;
}

body{
    font-size: 14px;
}

#sidebar.active .sidebar-wrapper{
    z-index: 1000;
}

.container-box{
    /*box-shadow: 4px 4px 6px 0 #d5efe6;*/
    box-shadow: 0px 0px 6px 0 #ddd;
    padding: 15px;
    background-color: #fff;
}

.submit-form-btn{
    position: fixed;
    bottom: 0px;
    right: 0px;
    left: 0px;
    padding: 10px 20px 0 10px;
    background-color: #F2F2F2;
    z-index: 10;
}

.product-image-thumbnail{
    width: 16.6666667%;
    float: left;
    padding: 0 12px;
}

.product-image-thumbnail .form-group{
    position: relative;
    overflow: hidden;
}

.delete-image-box{
    position: absolute;
    bottom: 0px;
    right: 0px;
    left: 0px;
    text-align: center;
    display: none;
    z-index: 1;
    padding: 5px 0px;
    background-color: rgba(0, 0, 0, 0.5);
    font-size: 20px;
    
}

.delete-image-box .delete-image{
    color: red;
    transition: transform .5s ease;
}

.product-image-thumbnail:hover .product-image-thumbnail-img{        
    transform: scale(1.5);
}

.product-image-thumbnail:hover .delete-image-box{
    display: block;
}

.clear-both{
    clear: both;
}
.product-image-thumbnail .product-image-thumbnail-img{
    overflow: hidden;
    width: 100%;
    transition: transform .5s ease;
    height: 200px;
    background-repeat: no-repeat;
    background-size: 100%;
    background-position: center center;
}

.box {
    display: block;
    width: 100%;
    padding: 50px;
    text-align: center;
    border-radius: 25px;
    box-shadow: 2px 2px 5px #888;
    margin: 0 auto;
}

.form-group[class*=has-icon-].has-icon-right .form-control-icon{
    top: 10;
}


.bottom-pop-up{
    position: fixed; 
    bottom: 0px; 
    left: 0px; 
    right: 0px; 
    width: 100%;
    z-index: 100000;
    display: none;
}

.bottom-pop-up-content{
    background-color: #fff;
    border-top: 1px solid #eee; 
    border-top-left-radius: 10px; 
    border-top-right-radius: 10px;
}

.bottom-pop-up:before{
    content: '';
    background-color: rgba(0,0,0,0.5);
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    width: 100%;
    height: 100%;
    z-index: -1;
}

.selected-people-box, .current_order_action, .useTable, .open-table-list-btn{
    display: none;
}

.quantity-setting{
  display: inline-flex;
}

.quantity-setting .deduct-qty-button, .quantity-setting .add-qty-button{
    padding: 6px 10px;
}

.quantity-setting input {
    width: 70px;
    height: 40px;
    text-align: center;
}

.variation_option.quantity-setting {
    display: inline-flex;
}

.ps-product--detail .ps-product__style ul li {
    display: inline-block;
    margin-right: 10px;
}

.ps-product--detail .ps-product__style ul li a {
    display: inline-block;
    width: 60px;
    border: 3px solid #e5e5e5;
}

.ps-product--detail .ps-product__style ul li a {
    width: unset;
}

.variation_option, .second_variation_option{
    border: 1px solid #ddd; 
    padding: 10px; 
    text-align: center;
    cursor: pointer;
}

.variation_option, .second_variation_option{
    padding: 10px; 
    text-align: center;
    cursor: pointer;
    min-width: 5rem;
    min-height: 2.125rem;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
    padding: .25rem .75rem;
    margin: 0 8px 8px 0;
    color: rgba(0,0,0,.8);
    text-align: left;
    border-radius: 2px;
    border: 1px solid rgba(0,0,0,.09);
    position: relative;
    background: #fff;
    outline: 0;
    word-break: break-word;
    display: -webkit-inline-box;
    display: -webkit-inline-flex;
    display: -moz-inline-box;
    display: -ms-inline-flexbox;
    display: inline-flex;
    -webkit-box-align: center;
    -webkit-align-items: center;
    -moz-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    -webkit-box-pack: center;
    -webkit-justify-content: center;
    -moz-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
}

.variation_option.active, .second_variation_option.active{
    border: 3px solid #2AC37D !important;
}

.variation_option.out-of-stock, .second_variation_option.out-of-stock{
    border: 2px solid #eee; 
    background-color: #eee;
    cursor: not-allowed;
    pointer-events:none;
}

.loading-gif{
    position: fixed;
    left: 0;
    top: 0;
    bottom: 0;
    right: 0;
    z-index: 1000;
    background-size: 3%;
    background-repeat: no-repeat;
    background-position: center center;
    width: 100%;
    height: 100%;
    background-color: rgba(255,255,255, 0.5);
    display: none;
    z-index: 10000;
}

.ui-datepicker {
    z-index: 9999 !important;
}

.mb-170 {
    margin-bottom: 170px;
}

.backend_global_language {
    padding: 0.375rem 0.75rem; 
    width: auto; 
    -webkit-appearance: auto; 
    z-index: 1000;
    border-radius: 5px;
}

</style>

@if(Request::segment(1) == 'transaction_invoice' ||
    Request::segment(1) == 'print_withdrawal_list' ||
    Request::segment(1) == 'print_sales_report' ||
    Request::segment(1) == 'print_order_report' ||
    Request::segment(1) == 'print_point_order_report' ||
    Request::segment(1) == 'print_sales_report_details' ||
    Request::segment(1) == 'print_commission_report' ||
    Request::segment(1) == 'cashier_screen' ||
    Request::segment(1) == 'topup_invoice' ||
    Request::segment(1) == 'print_agent_sales_report_detail')
    <style type="text/css">
        #main{
            margin-left: 0px !important;
            padding:  1rem !important;
            min-height: auto !important;
        }
    </style>
@endif
<body>
    <script src="{{ asset('NewBackend/assets/static/js/initTheme.js') }}/"></script>
    <div class="loading-gif" style="background-image: url({{ asset('images/loading/09b24e31234507.564a1d23c07b4.gif') }}); "></div>
    <div id="app">
        @include('partial.admin.sidebar')
        @include('partial.admin.header')
        <div id="main" style="margin-bottom: 20px;">

            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
                @if(!(Request::segment(1) == 'transaction_invoice' ||
                    Request::segment(1) == 'print_withdrawal_list' ||
                    Request::segment(1) == 'print_sales_report' ||
                    Request::segment(1) == 'print_order_report' ||
                    Request::segment(1) == 'print_point_order_report' ||
                    Request::segment(1) == 'print_sales_report_details' ||
                    Request::segment(1) == 'print_commission_report' ||
                    Request::segment(1) == 'cashier_screen' ||
                    Request::segment(1) == 'topup_invoice' ||
                    Request::segment(1) == 'print_agent_sales_report_detail'))
                <div class="d-flex justify-content-end px-3">
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-bar-right"></i>
                        <span>{{ isset($data['backendlang']['backendlang']['Logout']) ? $data['backendlang']['backendlang']['Logout'] : ''}}</span>
                    </a>
                </div>
                <form id="logout-form" action="{{ route('admin_logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                @endif
            </header>
            <div class="page-heading" style="margin: 0px;">
            @if(Request::segment(1) == 'dashboards')
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"> {{ isset($data['backendlang']['backendlang']['dashboard']) ? $data['backendlang']['backendlang']['dashboard'] :'' }}</h4>
                    </div>
                    <div class="card-body">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                     {{ isset($data['backendlang']['backendlang']['dashboard']) ? $data['backendlang']['backendlang']['dashboard'] :'' }}
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            @elseif(Request::segment(1) == 'admins')
                <!-- <h3>Company Profile</h3> -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['Company_Profile']) ? $data['backendlang']['backendlang']['Company_Profile'] :'' }}</h4>
                    </div>
                    <div class="card-body">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ isset($data['backendlang']['backendlang']['Company_Profile']) ? $data['backendlang']['backendlang']['Company_Profile'] :'' }}
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            @elseif(Request::segment(1) == 'agents')
                @if(Request::segment(3) == 'edit')
                    <!-- <h3>Agent Detail</h3> -->
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['Agent_List']) ? $data['backendlang']['backendlang']['Agent_List'] :'' }}</h4>
                        </div>
                        <div class="card-body">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('agent.agents.index') }}">
                                            {{ isset($data['backendlang']['backendlang']['Agent_List']) ? $data['backendlang']['backendlang']['Agent_List'] :'' }}
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ isset($data['backendlang']['backendlang']['agent_detail']) ? $data['backendlang']['backendlang']['agent_detail'] :'' }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                @elseif(Request::segment(2) == 'create')
                    <!-- <h3>Create New Agent</h3> -->
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['Agent_List']) ? $data['backendlang']['backendlang']['Agent_List'] :'' }}</h4>
                        </div>
                        <div class="card-body">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('agent.agents.index') }}">
                                            {{ isset($data['backendlang']['backendlang']['Agent_List']) ? $data['backendlang']['backendlang']['Agent_List'] :'' }}
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ isset($data['backendlang']['backendlang']['Create_New_Agent']) ? $data['backendlang']['backendlang']['Create_New_Agent'] :'' }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                @else
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['Agent_List']) ? $data['backendlang']['backendlang']['Agent_List'] :'' }}</h4>
                        </div>
                        <div class="card-body">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ isset($data['backendlang']['backendlang']['Agent_List']) ? $data['backendlang']['backendlang']['Agent_List'] :'' }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                @endif
            @elseif(Request::segment(1) == 'merchants')
                @if(Request::segment(3) == 'edit')
                    <!-- <h3>Agent Detail</h3> -->
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['merchantList']) ? $data['backendlang']['backendlang']['merchantList'] :'' }}</h4>
                        </div>
                        <div class="card-body">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('merchant.merchants.index') }}">
                                            {{ isset($data['backendlang']['backendlang']['merchantList']) ? $data['backendlang']['backendlang']['merchantList'] :'' }}
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ isset($data['backendlang']['backendlang']['Merchant_Detail']) ? $data['backendlang']['backendlang']['Merchant_Detail'] :'' }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                @elseif(Request::segment(2) == 'create')
                    <!-- <h3>Create New Agent</h3> -->
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['merchantList']) ? $data['backendlang']['backendlang']['merchantList'] :'' }}</h4>
                        </div>
                        <div class="card-body">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('merchant.merchants.index') }}">
                                            {{ isset($data['backendlang']['backendlang']['merchantList']) ? $data['backendlang']['backendlang']['merchantList'] :'' }}
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ isset($data['backendlang']['backendlang']['Create_New_Merchant']) ? $data['backendlang']['backendlang']['Create_New_Merchant'] :'' }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                @else
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title"> {{ isset($data['backendlang']['backendlang']['merchantList']) ? $data['backendlang']['backendlang']['merchantList'] :'' }}</h4>
                        </div>
                        <div class="card-body">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                         {{ isset($data['backendlang']['backendlang']['merchantList']) ? $data['backendlang']['backendlang']['merchantList'] :'' }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                @endif
            @elseif(Request::segment(1) == 'agent_wallet')
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"> {{ isset($data['backendlang']['backendlang']['agentWallet']) ? $data['backendlang']['backendlang']['agentWallet'] :'' }}</h4>
                    </div>
                    <div class="card-body">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                     {{ isset($data['backendlang']['backendlang']['agentWallet']) ? $data['backendlang']['backendlang']['agentWallet'] :'' }}
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            @elseif(Request::segment(1) == 'pending_agent')
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['Pending_Agent']) ? $data['backendlang']['backendlang']['Pending_Agent'] :'' }}</h4>
                    </div>
                    <div class="card-body">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ isset($data['backendlang']['backendlang']['Pending_Agent']) ? $data['backendlang']['backendlang']['Pending_Agent'] :'' }}
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            @elseif(Request::segment(1) == 'members')
                <!-- <h3>Member List</h3> -->
                @if(Request::segment(3) == 'edit')
                    <!-- <h3>Agent Detail</h3> -->
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['Member_List']) ? $data['backendlang']['backendlang']['Member_List'] :'' }}</h4>
                        </div>
                        <div class="card-body">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('member.members.index') }}">
                                            {{ isset($data['backendlang']['backendlang']['Member_List']) ? $data['backendlang']['backendlang']['Member_List'] :'' }}
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ isset($data['backendlang']['backendlang']['Member_Detail']) ? $data['backendlang']['backendlang']['Member_Detail'] :'' }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                @elseif(Request::segment(2) == 'create')
                    <!-- <h3>Create New Agent</h3> -->
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title"> {{ isset($data['backendlang']['backendlang']['Member_List']) ? $data['backendlang']['backendlang']['Member_List'] :'' }}</h4>
                        </div>
                        <div class="card-body">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('member.members.index') }}">
                                             {{ isset($data['backendlang']['backendlang']['Member_List']) ? $data['backendlang']['backendlang']['Member_List'] :'' }}
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                         {{ isset($data['backendlang']['backendlang']['Create_New_Mmember']) ? $data['backendlang']['backendlang']['Create_New_Member'] :'' }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                @else
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title"> {{ isset($data['backendlang']['backendlang']['Member_List']) ? $data['backendlang']['backendlang']['Member_List'] :'' }}</h4>
                        </div>
                        <div class="card-body">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                         {{ isset($data['backendlang']['backendlang']['Member_List']) ? $data['backendlang']['backendlang']['Member_List'] :'' }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                @endif
            @elseif(Request::segment(1) == 'products')
                <!-- <h3>Member List</h3> -->
                @if(Request::segment(3) == 'edit')
                    <!-- <h3>Agent Detail</h3> -->
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">  {{ isset($data['backendlang']['backendlang']['Product_List']) ? $data['backendlang']['backendlang']['Product_List'] :'' }}</h4>
                        </div>
                        <div class="card-body">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('product.products.index') }}">
                                             {{ isset($data['backendlang']['backendlang']['Product_List']) ? $data['backendlang']['backendlang']['Product_List'] :'' }}
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                         {{ isset($data['backendlang']['backendlang']['product_detail']) ? $data['backendlang']['backendlang']['product_detail'] :'' }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                @elseif(Request::segment(2) == 'create')
                    <!-- <h3>Create New Agent</h3> -->
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['Product_List']) ? $data['backendlang']['backendlang']['Product_List'] :'' }}</h4>
                        </div>
                        <div class="card-body">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('product.products.index') }}">
                                            {{ isset($data['backendlang']['backendlang']['Product_List']) ? $data['backendlang']['backendlang']['Product_List'] :'' }}
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ isset($data['backendlang']['backendlang']['Add_New_Product']) ? $data['backendlang']['backendlang']['Add_New_Product'] :'' }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                @elseif(Request::segment(2) == 'point_product_list')
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['Product_List']) ? $data['backendlang']['backendlang']['Product_List'] :'' }}</h4>
                        </div>
                        <div class="card-body">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('product.products.index') }}">
                                           {{ isset($data['backendlang']['backendlang']['Product_List']) ? $data['backendlang']['backendlang']['Product_List'] :'' }}
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ isset($data['backendlang']['backendlang']['Add_New_Point_Product']) ? $data['backendlang']['backendlang']['Add_New_Point_Product'] :'' }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                @elseif(Request::segment(2) == 'point_product_edit')
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['Point_Product_Detail']) ? $data['backendlang']['backendlang']['Point_Product_Detail'] :'' }}</h4>
                        </div>
                        <div class="card-body">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('product.products.index') }}">
                                            {{ isset($data['backendlang']['backendlang']['Product_List']) ? $data['backendlang']['backendlang']['Product_List'] :'' }}
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ isset($data['backendlang']['backendlang']['Point_Product_Detail']) ? $data['backendlang']['backendlang']['Point_Product_Detail'] :'' }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                @elseif(Request::segment(3) == 'stock')
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">  {{ isset($data['backendlang']['backendlang']['Stock']) ? $data['backendlang']['backendlang']['Stock'] :'' }}</h4>
                        </div>
                        <div class="card-body">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('product.products.index') }}">
                                              {{ isset($data['backendlang']['backendlang']['Product_List']) ? $data['backendlang']['backendlang']['Product_List'] :'' }}
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                          {{ isset($data['backendlang']['backendlang']['Stock']) ? $data['backendlang']['backendlang']['Stock'] :'' }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                @elseif(Request::segment(2) == 'packages_list')
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">  {{ isset($data['backendlang']['backendlang']['packages']) ? $data['backendlang']['backendlang']['packages'] :'' }}</h4>
                        </div>
                        <div class="card-body">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                          {{ isset($data['backendlang']['backendlang']['packages']) ? $data['backendlang']['backendlang']['packages'] :'' }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                @elseif(Request::segment(2) == 'packages' && Request::segment(3) == 'add')
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">  {{ isset($data['backendlang']['backendlang']['Add_New_Packages']) ? $data['backendlang']['backendlang']['Add_New_Packages'] :'' }}</h4>
                        </div>
                        <div class="card-body">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('packages_list') }}">
                                              {{ isset($data['backendlang']['backendlang']['packages']) ? $data['backendlang']['backendlang']['packages'] :'' }}
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                         {{ isset($data['backendlang']['backendlang']['Add_New_Packages']) ? $data['backendlang']['backendlang']['Add_New_Packages'] :'' }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                @elseif(Request::segment(2) == 'packages' && Request::segment(4) == 'edit')
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['package_details']) ? $data['backendlang']['backendlang']['package_details'] :'' }}</h4>
                        </div>
                        <div class="card-body">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('packages_list') }}">
                                            {{ isset($data['backendlang']['backendlang']['packages']) ? $data['backendlang']['backendlang']['packages'] :'' }}
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ isset($data['backendlang']['backendlang']['package_details']) ? $data['backendlang']['backendlang']['package_details'] :'' }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                @else
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['Product_List']) ? $data['backendlang']['backendlang']['Product_List'] :'' }}</h4>
                        </div>
                        <div class="card-body">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ isset($data['backendlang']['backendlang']['Product_List']) ? $data['backendlang']['backendlang']['Product_List'] :'' }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                @endif
            @elseif(Request::segment(1) == 'flash_sales')
                <!-- <h3>Member List</h3> -->
                @if(Request::segment(3) == 'edit')
                    <!-- <h3>Agent Detail</h3> -->
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['Flash_Sales_Detail']) ? $data['backendlang']['backendlang']['Flash_Sales_Detail'] :'' }}</h4>
                        </div>
                        <div class="card-body">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('flash_sale.flash_sales.index') }}">
                                           {{ isset($data['backendlang']['backendlang']['flash_sale']) ? $data['backendlang']['backendlang']['flash_sale'] :'' }}
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ isset($data['backendlang']['backendlang']['Flash_Sales_Detail']) ? $data['backendlang']['backendlang']['Flash_Sales_Detail'] :'' }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                @elseif(Request::segment(2) == 'create')
                    <!-- <h3>Create New Agent</h3> -->
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['Create_New_Flash_Sales']) ? $data['backendlang']['backendlang']['Create_New_Flash_Sales'] :'' }}</h4>
                        </div>
                        <div class="card-body">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('flash_sale.flash_sales.index') }}">
                                            {{ isset($data['backendlang']['backendlang']['flash_sale']) ? $data['backendlang']['backendlang']['flash_sale'] :'' }}
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ isset($data['backendlang']['backendlang']['Create_New_Flash_Sales']) ? $data['backendlang']['backendlang']['Create_New_Flash_Sales'] :'' }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                @else
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['flash_sale']) ? $data['backendlang']['backendlang']['flash_sale'] :'' }}</h4>
                        </div>
                        <div class="card-body">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                         {{ isset($data['backendlang']['backendlang']['flash_sale']) ? $data['backendlang']['backendlang']['flash_sale'] :'' }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                @endif
            @elseif(Request::segment(1) == 'setting_uom')
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['Setting_UOM']) ? $data['backendlang']['backendlang']['Setting_UOM'] :'' }}</h4>
                    </div>
                    <div class="card-body">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ isset($data['backendlang']['backendlang']['Setting_UOM']) ? $data['backendlang']['backendlang']['Setting_UOM'] :'' }}
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
             @elseif(Request::segment(1) == 'setting_auto_withdrawal')
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['Auto_Withdrawal_Setting']) ? $data['backendlang']['backendlang']['Auto_Withdrawal_Setting'] :'' }}</h4>
                    </div>
                    <div class="card-body">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ isset($data['backendlang']['backendlang']['Auto_Withdrawal_Setting']) ? $data['backendlang']['backendlang']['Auto_Withdrawal_Setting'] :'' }}
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div
            @elseif(Request::segment(1) == 'categories')
                <!-- <h3>Member List</h3> -->
                @if(Request::segment(3) == 'edit')
                    <!-- <h3>Agent Detail</h3> -->
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['Category_Detail']) ? $data['backendlang']['backendlang']['Category_Detail'] :'' }}</h4>
                        </div>
                        <div class="card-body">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('category.categories.index') }}">
                                            {{ isset($data['backendlang']['backendlang']['categories']) ? $data['backendlang']['backendlang']['categories'] :'' }}
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ isset($data['backendlang']['backendlang']['Category_Detail']) ? $data['backendlang']['backendlang']['Category_Detail'] :'' }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                @elseif(Request::segment(2) == 'create')
                    <!-- <h3>Create New Agent</h3> -->
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['Create_New_Category']) ? $data['backendlang']['backendlang']['Create_New_Category'] :'' }}</h4>
                        </div>
                        <div class="card-body">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('category.categories.index') }}">
                                           {{ isset($data['backendlang']['backendlang']['categories']) ? $data['backendlang']['backendlang']['categories'] :'' }}
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                       {{ isset($data['backendlang']['backendlang']['Create_New_Category']) ? $data['backendlang']['backendlang']['Create_New_Category'] :'' }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                @else
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['categories']) ? $data['backendlang']['backendlang']['categories'] :'' }}</h4>
                        </div>
                        <div class="card-body">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ isset($data['backendlang']['backendlang']['categories']) ? $data['backendlang']['backendlang']['categories'] :'' }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                @endif
            @elseif(Request::segment(1) == 'sub_categories')
                <!-- <h3>Member List</h3> -->
                @if(Request::segment(3) == 'edit')
                    <!-- <h3>Agent Detail</h3> -->
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['subCategory_Detail']) ? $data['backendlang']['backendlang']['subCategory_Detail'] :'' }}</h4>
                        </div>
                        <div class="card-body">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('sub_category.sub_categories.index') }}">
                                            {{ isset($data['backendlang']['backendlang']['subCategories']) ? $data['backendlang']['backendlang']['subCategories'] :'' }}
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ isset($data['backendlang']['backendlang']['subCategory_Detail']) ? $data['backendlang']['backendlang']['subCategory_Detail'] :'' }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                @elseif(Request::segment(2) == 'create')
                    <!-- <h3>Create New Agent</h3> -->
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['Create_New_Subcategory']) ? $data['backendlang']['backendlang']['Create_New_Subcategory'] :'' }}</h4>
                        </div>
                        <div class="card-body">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('sub_category.sub_categories.index') }}">
                                            {{ isset($data['backendlang']['backendlang']['subCategories']) ? $data['backendlang']['backendlang']['subCategories'] :'' }}
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ isset($data['backendlang']['backendlang']['Create_New_Subcategory']) ? $data['backendlang']['backendlang']['Create_New_Subcategory'] :'' }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                @else
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['subCategories']) ? $data['backendlang']['backendlang']['subCategories'] :'' }}</h4>
                        </div>
                        <div class="card-body">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ isset($data['backendlang']['backendlang']['subCategories']) ? $data['backendlang']['backendlang']['subCategories'] :'' }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                @endif
            @elseif(Request::segment(1) == 'brands')
                <!-- <h3>Member List</h3> -->
                @if(Request::segment(3) == 'edit')
                    <!-- <h3>Agent Detail</h3> -->
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['Brand_Detail']) ? $data['backendlang']['backendlang']['Brand_Detail'] :'' }}</h4>
                        </div>
                        <div class="card-body">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('brand.brands.index') }}">
                                            {{ isset($data['backendlang']['backendlang']['Brands']) ? $data['backendlang']['backendlang']['Brands'] :'' }}
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ isset($data['backendlang']['backendlang']['Brand_Detail']) ? $data['backendlang']['backendlang']['Brand_Detail'] :'' }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                @elseif(Request::segment(2) == 'create')
                    <!-- <h3>Create New Agent</h3> -->
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['Create_New_Brand']) ? $data['backendlang']['backendlang']['Create_New_Brand'] :'' }}</h4>
                        </div>
                        <div class="card-body">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('brand.brands.index') }}">
                                            {{ isset($data['backendlang']['backendlang']['Brands']) ? $data['backendlang']['backendlang']['Brands'] :'' }}
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ isset($data['backendlang']['backendlang']['Create_New_Brand']) ? $data['backendlang']['backendlang']['Create_New_Brand'] :'' }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                @else
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['Brands']) ? $data['backendlang']['backendlang']['Brands'] :'' }}</h4>
                        </div>
                        <div class="card-body">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                       {{ isset($data['backendlang']['backendlang']['Brands']) ? $data['backendlang']['backendlang']['Brands'] :'' }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                @endif
            @elseif(Request::segment(1) == 'promotions')
                <!-- <h3>Member List</h3> -->
                @if(Request::segment(3) == 'edit')
                    <!-- <h3>Agent Detail</h3> -->
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['Voucher_Detail']) ? $data['backendlang']['backendlang']['Voucher_Detail'] :'' }}</h4>
                        </div>
                        <div class="card-body">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('promotion.promotions.index') }}">
                                             {{ isset($data['backendlang']['backendlang']['voucher_list']) ? $data['backendlang']['backendlang']['voucher_list'] :'' }}
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ isset($data['backendlang']['backendlang']['Voucher_Detail']) ? $data['backendlang']['backendlang']['Voucher_Detail'] :'' }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                @elseif(Request::segment(2) == 'create')
                    <!-- <h3>Create New Agent</h3> -->
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['Create_New_Voucher']) ? $data['backendlang']['backendlang']['Create_New_Voucher'] :'' }}</h4>
                        </div>
                        <div class="card-body">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('promotion.promotions.index') }}">
                                            {{ isset($data['backendlang']['backendlang']['voucher_list']) ? $data['backendlang']['backendlang']['voucher_list'] :'' }}
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ isset($data['backendlang']['backendlang']['Create_New_Voucher']) ? $data['backendlang']['backendlang']['Create_New_Voucher'] :'' }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                @else
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['voucher_list']) ? $data['backendlang']['backendlang']['voucher_list'] :'' }}</h4>
                        </div>
                        <div class="card-body">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                       {{ isset($data['backendlang']['backendlang']['voucher_list']) ? $data['backendlang']['backendlang']['voucher_list'] :'' }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                @endif
            @elseif(Request::segment(1) == 'add_on_deal')
                <!-- <h3>Member List</h3> -->
                @if(Request::segment(3) == 'edit')
                    <!-- <h3>Agent Detail</h3> -->
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['Add_On_Deal_Detail']) ? $data['backendlang']['backendlang']['Add_On_Deal_Detail'] :'' }}</h4>
                        </div>
                        <div class="card-body">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('add_on_deal') }}">
                                            {{ isset($data['backendlang']['backendlang']['Add_On_Deals']) ? $data['backendlang']['backendlang']['Add_On_Deals'] :'' }}
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ isset($data['backendlang']['backendlang']['Add_On_Deal_Detail']) ? $data['backendlang']['backendlang']['Add_On_Deal_Detail'] :'' }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                @elseif(Request::segment(2) == 'create')
                    <!-- <h3>Create New Agent</h3> -->
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title"> {{ isset($data['backendlang']['backendlang']['Create_New_Promotion']) ? $data['backendlang']['backendlang']['Create_New_Promotion'] :'' }}</h4>
                        </div>
                        <div class="card-body">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('add_on_deal') }}">
                                             {{ isset($data['backendlang']['backendlang']['Add_On_Deals']) ? $data['backendlang']['backendlang']['Add_On_Deals'] :'' }}
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                       {{ isset($data['backendlang']['backendlang']['Create_New_Add_On_Deal']) ? $data['backendlang']['backendlang']['Create_New_Add_On_Deal'] :'' }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                @else
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title"> {{ isset($data['backendlang']['backendlang']['Add_On_Deals']) ? $data['backendlang']['backendlang']['Add_On_Deals'] :'' }}</h4>
                        </div>
                        <div class="card-body">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                         {{ isset($data['backendlang']['backendlang']['Add_On_Deals']) ? $data['backendlang']['backendlang']['Add_On_Deals'] :'' }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                @endif
            @elseif(Request::segment(1) == 'cart_links')
                <!-- <h3>Member List</h3> -->
                @if(Request::segment(3) == 'edit')
                    <!-- <h3>Agent Detail</h3> -->
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['Cart_Link_Detail']) ? $data['backendlang']['backendlang']['Cart_Link_Detail'] :'' }}</h4>
                        </div>
                        <div class="card-body">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('cart_link.cart_links.index') }}">
                                            {{ isset($data['backendlang']['backendlang']['Cart_Link']) ? $data['backendlang']['backendlang']['Cart_Link'] :'' }}
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ isset($data['backendlang']['backendlang']['Cart_Link_Detail']) ? $data['backendlang']['backendlang']['Cart_Link_Detail'] :'' }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                @elseif(Request::segment(2) == 'create')
                    <!-- <h3>Create New Agent</h3> -->
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['Create_New_Cart_Link']) ? $data['backendlang']['backendlang']['Create_New_Cart_Link'] :'' }}</h4>
                        </div>
                        <div class="card-body">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('cart_link.cart_links.index') }}">
                                            {{ isset($data['backendlang']['backendlang']['Cart_Link']) ? $data['backendlang']['backendlang']['Cart_Link'] :'' }}
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ isset($data['backendlang']['backendlang']['Create_New_Cart_Link']) ? $data['backendlang']['backendlang']['Create_New_Cart_Link'] :'' }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                @else
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['Cart_Link']) ? $data['backendlang']['backendlang']['Cart_Link'] :'' }}</h4>
                        </div>
                        <div class="card-body">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ isset($data['backendlang']['backendlang']['Cart_Link']) ? $data['backendlang']['backendlang']['Cart_Link'] :'' }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                @endif
            @elseif(Request::segment(1) == 'transactions')
                <!-- <h3>Member List</h3> -->
                @if(Request::segment(3) == 'edit')
                    <!-- <h3>Agent Detail</h3> -->
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{ request('mall') ? (isset($data['backendlang']['backendlang']['Point_Transaction_Detail']) ? $data['backendlang']['backendlang']['Point_Transaction_Detail'] : 'Point Transaction Detail') : (isset($data['backendlang']['backendlang']['transaction_detail']) ? $data['backendlang']['backendlang']['transaction_detail'] : 'Transaction Detail') }}</h4>
                        </div>
                        <div class="card-body">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('cart_link.cart_links.index') }}">                                     
                                            {{ request('mall') ? (isset($data['backendlang']['backendlang']['point_transaction']) ? $data['backendlang']['backendlang']['point_transaction'] : '') : (isset($data['backendlang']['backendlang']['Transaction']) ? $data['backendlang']['backendlang']['Transaction'] : '') }}
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ request('mall') ? (isset($data['backendlang']['backendlang']['Point_Transaction_Detail']) ? $data['backendlang']['backendlang']['Point_Transaction_Detail'] : 'Point Transaction Detail') : (isset($data['backendlang']['backendlang']['transaction_detail']) ? $data['backendlang']['backendlang']['transaction_detail'] : 'Transaction Detail') }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                @elseif(Request::segment(2) == 'create_point')
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['Create_Point_Transaction']) ? $data['backendlang']['backendlang']['Create_Point_Transaction'] :'' }}</h4>
                        </div>
                        <div class="card-body">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                    <li class="breadcrumb-item">
                                        <a href="#">
                                            {{ isset($data['backendlang']['backendlang']['Point_Transaction']) ? $data['backendlang']['backendlang']['Point_Transaction'] :'' }}
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ isset($data['backendlang']['backendlang']['Create_Point_Transaction']) ? $data['backendlang']['backendlang']['Create_Point_Transaction'] :'' }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                @elseif(Request::segment(2) == 'create')
                    <!-- <h3>Create New Agent</h3> -->
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['Create_Transaction']) ? $data['backendlang']['backendlang']['Create_Transaction'] :'' }}</h4>
                        </div>
                        <div class="card-body">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('cart_link.cart_links.index') }}">
                                              {{ isset($data['backendlang']['backendlang']['Transaction']) ? $data['backendlang']['backendlang']['Transaction'] :'' }}
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ isset($data['backendlang']['backendlang']['Create_Transaction']) ? $data['backendlang']['backendlang']['Create_Transaction'] :'' }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                @else
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{ request('mall') ? (isset($data['backendlang']['backendlang']['point_transaction']) ? $data['backendlang']['backendlang']['point_transaction'] : '') : (isset($data['backendlang']['backendlang']['Transaction']) ? $data['backendlang']['backendlang']['Transaction'] : '') }}</h4>
                        </div>
                        <div class="card-body">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ request('mall') ? (isset($data['backendlang']['backendlang']['point_transaction']) ? $data['backendlang']['backendlang']['point_transaction'] : '') : (isset($data['backendlang']['backendlang']['Transaction']) ? $data['backendlang']['backendlang']['Transaction'] : '') }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                @endif
            @elseif(Request::segment(1) == 'topup_list')
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"> {{ isset($data['backendlang']['backendlang']['topup_list']) ? $data['backendlang']['backendlang']['topup_list'] :'' }}</h4>
                    </div>
                    <div class="card-body">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ isset($data['backendlang']['backendlang']['topup_list']) ? $data['backendlang']['backendlang']['topup_list'] :'' }}
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            @elseif(Request::segment(1) == 'withdrawal_list')
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['Withdrawal_List']) ? $data['backendlang']['backendlang']['Withdrawal_List'] :'' }}</h4>
                    </div>
                    <div class="card-body">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ isset($data['backendlang']['backendlang']['Withdrawal_List']) ? $data['backendlang']['backendlang']['Withdrawal_List'] :'' }}
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            @elseif(Request::segment(1) == 'sales_report')
                @if(Request::segment(2) == 'sales_report_details')
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['Item_Profit_Details_Report']) ? $data['backendlang']['backendlang']['Item_Profit_Details_Report'] :'' }}</h4>
                    </div>
                    <div class="card-body">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    <a href="#">
                                    {{ isset($data['backendlang']['backendlang']['item_profit_report']) ? $data['backendlang']['backendlang']['item_profit_report'] :'' }}
                                    </a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ isset($data['backendlang']['backendlang']['Item_Profit_Details_Report']) ? $data['backendlang']['backendlang']['Item_Profit_Details_Report'] :'' }}
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
                @else
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['item_profit_report']) ? $data['backendlang']['backendlang']['item_profit_report'] :'' }}</h4>
                    </div>
                    <div class="card-body">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ isset($data['backendlang']['backendlang']['item_profit_report']) ? $data['backendlang']['backendlang']['item_profit_report'] :'' }}
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
                @endif
            @elseif(Request::segment(1) == 'order_report')
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['order_report']) ? $data['backendlang']['backendlang']['order_report'] :'' }}</h4>
                    </div>
                    <div class="card-body">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ isset($data['backendlang']['backendlang']['order_report']) ? $data['backendlang']['backendlang']['order_report'] :'' }}
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            @elseif(Request::segment(1) == 'point_order_report')
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['point_order_report']) ? $data['backendlang']['backendlang']['point_order_report'] :'' }}</h4>
                    </div>
                    <div class="card-body">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ isset($data['backendlang']['backendlang']['point_order_report']) ? $data['backendlang']['backendlang']['point_order_report'] :'' }}
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            @elseif(Request::segment(1) == 'commission_report')
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['Commission_Report']) ? $data['backendlang']['backendlang']['Commission_Report'] :'' }}</h4>
                    </div>
                    <div class="card-body">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                   {{ isset($data['backendlang']['backendlang']['Commission_Report']) ? $data['backendlang']['backendlang']['Commission_Report'] :'' }}
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            @elseif(Request::segment(1) == 'team_reward_report')
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['Team_Reward_Report']) ? $data['backendlang']['backendlang']['Team_Reward_Report'] :'' }}</h4>
                    </div>
                    <div class="card-body">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                   {{ isset($data['backendlang']['backendlang']['Team_Reward_Report']) ? $data['backendlang']['backendlang']['Team_Reward_Report'] :'' }}
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            @elseif(Request::segment(1) == 'topup_wallet_report')
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['topup_wallet_report']) ? $data['backendlang']['backendlang']['topup_wallet_report'] :'' }}</h4>
                    </div>
                    <div class="card-body">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ isset($data['backendlang']['backendlang']['topup_wallet_report']) ? $data['backendlang']['backendlang']['topup_wallet_report'] :'' }}
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            @elseif(Request::segment(1) == 'topup_wallet_report_detail')
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['Topup_Wallet_Report_Detail']) ? $data['backendlang']['backendlang']['Topup_Wallet_Report_Detail'] :'' }}</h4>
                    </div>
                    <div class="card-body">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ isset($data['backendlang']['backendlang']['Topup_Wallet_Report_Detail']) ? $data['backendlang']['backendlang']['Topup_Wallet_Report_Detail'] :'' }}
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            @elseif(Request::segment(1) == 'cash_wallet_report')
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['cash_wallet_report']) ? $data['backendlang']['backendlang']['cash_wallet_report'] :'' }}</h4>
                    </div>
                    <div class="card-body">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ isset($data['backendlang']['backendlang']['cash_wallet_report']) ? $data['backendlang']['backendlang']['cash_wallet_report'] :'' }}
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            @elseif(Request::segment(1) == 'cash_wallet_report_detail')
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['Cash_Wallet_Report_Detail']) ? $data['backendlang']['backendlang']['Cash_Wallet_Report_Detail'] :'' }}</h4>
                    </div>
                    <div class="card-body">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ isset($data['backendlang']['backendlang']['Cash_Wallet_Report_Detail']) ? $data['backendlang']['backendlang']['Cash_Wallet_Report_Detail'] :'' }}
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            @elseif(Request::segment(1) == 'stock_report')
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['stock_report']) ? $data['backendlang']['backendlang']['stock_report'] :'' }}</h4>
                    </div>
                    <div class="card-body">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ isset($data['backendlang']['backendlang']['stock_report']) ? $data['backendlang']['backendlang']['stock_report'] :'' }}
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            @elseif(Request::segment(1) == 'stock_report_details')
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['Stock_Report_Detail']) ? $data['backendlang']['backendlang']['Stock_Report_Detail'] :'' }}</h4>
                    </div>
                    <div class="card-body">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ isset($data['backendlang']['backendlang']['Stock_Report_Detail']) ? $data['backendlang']['backendlang']['Stock_Report_Detail'] :'' }}
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            @elseif(Request::segment(1) == 'setting_agent_rebate')
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['Agent_Order_Rebate']) ? $data['backendlang']['backendlang']['Agent_Order_Rebate'] :'' }}</h4>
                    </div>
                    <div class="card-body">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ isset($data['backendlang']['backendlang']['Agent_Order_Rebate']) ? $data['backendlang']['backendlang']['Agent_Order_Rebate'] :'' }}
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            @elseif(Request::segment(1) == 'setting_merchant_commission')
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['Hierarchy_Bonus']) ? $data['backendlang']['backendlang']['Hierarchy_Bonus'] :'' }}</h4>
                    </div>
                    <div class="card-body">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ isset($data['backendlang']['backendlang']['Hierarchy_Bonus']) ? $data['backendlang']['backendlang']['Hierarchy_Bonus'] :'' }}
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            @elseif(Request::segment(1) == 'setting_recommend_bonus')
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['Referral_Reward']) ? $data['backendlang']['backendlang']['Referral_Reward'] :'' }}</h4>
                    </div>
                    <div class="card-body">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ isset($data['backendlang']['backendlang']['Referral_Reward']) ? $data['backendlang']['backendlang']['Referral_Reward'] :'' }}
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            @elseif(Request::segment(1) == 'setting_shipping_fee')
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['Setting_Shipping_Fee']) ? $data['backendlang']['backendlang']['Setting_Shipping_Fee'] :'' }}</h4>
                    </div>
                    <div class="card-body">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ isset($data['backendlang']['backendlang']['Setting_Shipping_Fee']) ? $data['backendlang']['backendlang']['Setting_Shipping_Fee'] :'' }}
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            @elseif(Request::segment(1) == 'setting_cod_address')
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['Setting_Pickup_Address']) ? $data['backendlang']['backendlang']['Setting_Pickup_Address'] :'' }}s</h4>
                    </div>
                    <div class="card-body">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ isset($data['backendlang']['backendlang']['Setting_Pickup_Address']) ? $data['backendlang']['backendlang']['Setting_Pickup_Address'] :'' }}
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            @elseif(Request::segment(1) == 'setting_pick_up_address')
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['Setting_Pickup_Address']) ? $data['backendlang']['backendlang']['Setting_Pickup_Address'] :'' }} (Easyparcel)</h4>
                    </div>
                    <div class="card-body">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ isset($data['backendlang']['backendlang']['Setting_Pickup_Address']) ? $data['backendlang']['backendlang']['Setting_Pickup_Address'] :'' }} (Easyparcel)
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            @elseif(Request::segment(1) == 'website_setting')
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['Website_Setting']) ? $data['backendlang']['backendlang']['Website_Setting'] :'' }}</h4>
                    </div>
                    <div class="card-body">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ isset($data['backendlang']['backendlang']['Website_Setting']) ? $data['backendlang']['backendlang']['Website_Setting'] :'' }}
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            @elseif(Request::segment(1) == 'setting_agent_level')
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['Agent_Level']) ? $data['backendlang']['backendlang']['Agent_Level'] :'' }}</h4>
                    </div>
                    <div class="card-body">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ isset($data['backendlang']['backendlang']['Agent_Level']) ? $data['backendlang']['backendlang']['Agent_Level'] :'' }}
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            @elseif(Request::segment(1) == 'member_wallet')
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">  {{ isset($data['backendlang']['backendlang']['memberWallet']) ? $data['backendlang']['backendlang']['memberWallet'] :'' }}</h4>
                    </div>
                    <div class="card-body">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ isset($data['backendlang']['backendlang']['memberWallet']) ? $data['backendlang']['backendlang']['memberWallet'] :'' }}
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            @elseif(Request::segment(1) == 'pending_member')
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['Pending_Member']) ? $data['backendlang']['backendlang']['Pending_Member'] :'' }}</h4>
                    </div>
                    <div class="card-body">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ isset($data['backendlang']['backendlang']['Pending_Member']) ? $data['backendlang']['backendlang']['Pending_Member'] :'' }}
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            @elseif(Request::segment(1) == 'setting_topup_amount')
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['Topup_Bonus']) ? $data['backendlang']['backendlang']['Topup_Bonus'] :'' }}</h4>
                    </div>
                    <div class="card-body">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ isset($data['backendlang']['backendlang']['Topup_Bonus']) ? $data['backendlang']['backendlang']['Topup_Bonus'] :'' }}
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            @elseif(Request::segment(1) == 'agent_sales_report')
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['Agent_Sales_Report']) ? $data['backendlang']['backendlang']['Agent_Sales_Report'] :'' }}</h4>
                    </div>
                    <div class="card-body">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ isset($data['backendlang']['backendlang']['Agent_Sales_Report']) ? $data['backendlang']['backendlang']['Agent_Sales_Report'] :'' }}
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            @elseif(Request::segment(1) == 'setting_team_dividend')
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['Setting_Team_Reward']) ? $data['backendlang']['backendlang']['Setting_Team_Reward'] :'' }}</h4>
                    </div>
                    <div class="card-body">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">
                                     {{ isset($data['backendlang']['backendlang']['Setting_Team_Reward']) ? $data['backendlang']['backendlang']['Setting_Team_Reward'] :'' }}
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            @elseif(Request::segment(1) == 'setting_prize_pool')
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['Setting_Prize_Pool']) ? $data['backendlang']['backendlang']['Setting_Prize_Pool'] :'' }}</h4>
                    </div>
                    <div class="card-body">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ isset($data['backendlang']['backendlang']['Setting_Prize_Pool']) ? $data['backendlang']['backendlang']['Setting_Prize_Pool'] :'' }}
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            @elseif(Request::segment(1) == 'setting_einvoice')
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['Setting_e_Invoice']) ? $data['backendlang']['backendlang']['Setting_e_Invoice'] :'' }}</h4>
                    </div>
                    <div class="card-body">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ isset($data['backendlang']['backendlang']['Setting_e_Invoice']) ? $data['backendlang']['backendlang']['Setting_e_Invoice'] :'' }}
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            @elseif(Request::segment(1) == 'setting_website_messages')
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"> {{ isset($data['backendlang']['backendlang']['Website_Messages']) ? $data['backendlang']['backendlang']['Website_Messages'] :'' }}</h4>
                    </div>
                    <div class="card-body">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ isset($data['backendlang']['backendlang']['Website_Messages']) ? $data['backendlang']['backendlang']['Website_Messages'] :'' }}
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            @elseif(Request::segment(1) == 'setting_header')
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['Setting_Header']) ? $data['backendlang']['backendlang']['Setting_Header'] :'' }}</h4>
                    </div>
                    <div class="card-body">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ isset($data['backendlang']['backendlang']['Setting_Header']) ? $data['backendlang']['backendlang']['Setting_Header'] :'' }}
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            @elseif(Request::segment(1) == 'setting_banner')
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['First_Banner']) ? $data['backendlang']['backendlang']['First_Banner'] :'' }}</h4>
                    </div>
                    <div class="card-body">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ isset($data['backendlang']['backendlang']['First_Banner']) ? $data['backendlang']['backendlang']['First_Banner'] :'' }}
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            @elseif(Request::segment(1) == 'setting_home_page')
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['Two_Highlight']) ? $data['backendlang']['backendlang']['Two_Highlight'] :'' }}</h4>
                    </div>
                    <div class="card-body">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                   {{ isset($data['backendlang']['backendlang']['Two_Highlight']) ? $data['backendlang']['backendlang']['Two_Highlight'] :'' }}
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            @elseif(Request::segment(1) == 'setting_second_banner')
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['Second_Banner']) ? $data['backendlang']['backendlang']['Second_Banner'] :'' }}</h4>
                    </div>
                    <div class="card-body">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                     {{ isset($data['backendlang']['backendlang']['Second_Banner']) ? $data['backendlang']['backendlang']['Second_Banner'] :'' }}
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            @elseif(Request::segment(1) == 'setting_home_video')
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['Video']) ? $data['backendlang']['backendlang']['Video'] :'' }}</h4>
                    </div>
                    <div class="card-body">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                     {{ isset($data['backendlang']['backendlang']['Video']) ? $data['backendlang']['backendlang']['Video'] :'' }}
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            @elseif(Request::segment(1) == 'quizs')
                @if(Request::segment(3) == 'edit')
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title"> {{ isset($data['backendlang']['backendlang']['Quiz_Detail']) ? $data['backendlang']['backendlang']['Quiz_Detail'] :'' }}</h4>
                        </div>
                        <div class="card-body">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('quiz.quizs.index') }}">
                                             {{ isset($data['backendlang']['backendlang']['Quizs']) ? $data['backendlang']['backendlang']['Quizs'] :'' }}
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                         {{ isset($data['backendlang']['backendlang']['Quiz_Detail']) ? $data['backendlang']['backendlang']['Quiz_Detail'] :'' }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                @elseif(Request::segment(2) == 'create')
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['Create_New_Quiz']) ? $data['backendlang']['backendlang']['Create_New_Quiz'] :'' }}</h4>
                        </div>
                        <div class="card-body">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('quiz.quizs.index') }}">
                                             {{ isset($data['backendlang']['backendlang']['Quizs']) ? $data['backendlang']['backendlang']['Quizs'] :'' }}
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ isset($data['backendlang']['backendlang']['Create_New_Quiz']) ? $data['backendlang']['backendlang']['Create_New_Quiz'] :'' }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                @else
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title"> {{ isset($data['backendlang']['backendlang']['Quizs']) ? $data['backendlang']['backendlang']['Quizs'] :'' }}</h4>
                        </div>
                        <div class="card-body">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ isset($data['backendlang']['backendlang']['Quizs']) ? $data['backendlang']['backendlang']['Quizs'] :'' }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                @endif
            @elseif(Request::segment(1) == 'quiz_records_index')
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['View_Records']) ? $data['backendlang']['backendlang']['View_Records'] :'' }}</h4>
                    </div>
                    <div class="card-body">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                     {{ isset($data['backendlang']['backendlang']['View_Records']) ? $data['backendlang']['backendlang']['View_Records'] :'' }}
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            @elseif(Request::segment(1) == 'quiz_records_view')
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"> {{ isset($data['backendlang']['backendlang']['View_Record_Details']) ? $data['backendlang']['backendlang']['View_Record_Details'] :'' }}</h4>
                    </div>
                    <div class="card-body">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                <li class="breadcrumb-item"><a href="#">{{ isset($data['backendlang']['backendlang']['View_Records']) ? $data['backendlang']['backendlang']['View_Records'] :'' }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                     {{ isset($data['backendlang']['backendlang']['View_Record_Details']) ? $data['backendlang']['backendlang']['View_Record_Details'] :'' }}
                                </li>
                            </ol>
                        </nav>
              </di      v>
                </div>
            @elseif(Request::segment(1) == 'blogs')
                @if(Request::segment(3) == 'edit')
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title"> {{ isset($data['backendlang']['backendlang']['Blog_Detail']) ? $data['backendlang']['backendlang']['Blog_Detail'] :'' }}</h4>
                        </div>
                        <div class="card-body">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('blog.blogs.index') }}">
                                            {{ isset($data['backendlang']['backendlang']['Blogs']) ? $data['backendlang']['backendlang']['Blogs'] :'' }}
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ isset($data['backendlang']['backendlang']['Blog_Detail']) ? $data['backendlang']['backendlang']['Blog_Detail'] :'' }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                @elseif(Request::segment(2) == 'create')
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">  {{ isset($data['backendlang']['backendlang']['Create_New_Blog']) ? $data['backendlang']['backendlang']['Create_New_Blog'] :'' }}</h4>
                        </div>
                        <div class="card-body">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('blog.blogs.index') }}">
                                            {{ isset($data['backendlang']['backendlang']['Blogs']) ? $data['backendlang']['backendlang']['Blogs'] :'' }}
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ isset($data['backendlang']['backendlang']['Create_New_Blog']) ? $data['backendlang']['backendlang']['Create_New_Blog'] :'' }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                @else
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['Blogs']) ? $data['backendlang']['backendlang']['Blogs'] :'' }}</h4>
                        </div>
                        <div class="card-body">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ isset($data['backendlang']['backendlang']['Blogs']) ? $data['backendlang']['backendlang']['Blogs'] :'' }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                @endif
            @elseif(Request::segment(1) == 'setting_all_faqs')
                @if(Request::segment(3) == 'edit')
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['Faqs_Detail']) ? $data['backendlang']['backendlang']['Faqs_Detail'] :'' }}</h4>
                        </div>
                        <div class="card-body">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('setting_all_faq.setting_all_faqs.index') }}">
                                           {{ isset($data['backendlang']['backendlang']['FAQs']) ? $data['backendlang']['backendlang']['FAQs'] :'' }}
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                         {{ isset($data['backendlang']['backendlang']['Faqs_Detail']) ? $data['backendlang']['backendlang']['Faqs_Detail'] :'' }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                @elseif(Request::segment(2) == 'create')
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title"> {{ isset($data['backendlang']['backendlang']['Create_New_Faqs']) ? $data['backendlang']['backendlang']['Create_New_Faqs'] :'' }}</h4>
                        </div>
                        <div class="card-body">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('setting_all_faq.setting_all_faqs.index') }}">
                                              {{ isset($data['backendlang']['backendlang']['FAQs']) ? $data['backendlang']['backendlang']['FAQs'] :'' }}
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                          {{isset($data['backendlang']['backendlang']['Create_New_Faqs']) ? $data['backendlang']['backendlang']['Create_New_Faqs'] :'' }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                @else
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['FAQs']) ? $data['backendlang']['backendlang']['FAQs'] :'' }}</h4>
                        </div>
                        <div class="card-body">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ isset($data['backendlang']['backendlang']['FAQs']) ? $data['backendlang']['backendlang']['FAQs'] :'' }}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                @endif
            @elseif(Request::segment(1) == 'setting_home_overview')
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"> {{ isset($data['backendlang']['backendlang']['Overview']) ? $data['backendlang']['backendlang']['Overview'] :'' }}</h4>
                    </div>
                    <div class="card-body">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                     {{ isset($data['backendlang']['backendlang']['Overview']) ? $data['backendlang']['backendlang']['Overview'] :'' }}
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
                @elseif(Request::segment(1) == 'setting_featured_product_title')
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"> {{ isset($data['backendlang']['backendlang']['Setting_Featured_Product_Title']) ? $data['backendlang']['backendlang']['Setting_Featured_Product_Title'] :'' }}</h4>
                    </div>
                    <div class="card-body">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                     {{ isset($data['backendlang']['backendlang']['Setting_Featured_Product_Title']) ? $data['backendlang']['backendlang']['Setting_Featured_Product_Title'] :'' }}
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            @elseif(Request::segment(1) == 'user_permissions')
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"> {{ isset($data['backendlang']['backendlang']['permission']) ? $data['backendlang']['backendlang']['permission'] :'' }}</h4>
                    </div>
                    <div class="card-body">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                     {{ isset($data['backendlang']['backendlang']['permission']) ? $data['backendlang']['backendlang']['permission'] :'' }}
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            @elseif(Request::segment(1) == 'setting_website_countries')
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"> {{ isset($data['backendlang']['backendlang']['Country_Setting']) ? $data['backendlang']['backendlang']['Country_Setting'] :'' }}</h4>
                    </div>
                    <div class="card-body">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ isset($data['backendlang']['backendlang']['Country_Setting']) ? $data['backendlang']['backendlang']['Country_Setting'] :'' }}
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            @elseif(Request::segment(1) == 'setting_payment_gateway')
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ isset($data['backendlang']['backendlang']['Setting_Payment_Gateway']) ? $data['backendlang']['backendlang']['Setting_Payment_Gateway'] :'' }}</h4>
                    </div>
                    <div class="card-body">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                     {{ isset($data['backendlang']['backendlang']['Setting_Payment_Gateway']) ? $data['backendlang']['backendlang']['Setting_Payment_Gateway'] :'' }}
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
                @elseif(Request::segment(1) == 'setting_colour')
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"> {{ isset($data['backendlang']['backendlang']['Setting_Website_Theme_Colour']) ? $data['backendlang']['backendlang']['Setting_Website_Theme_Colour'] :'' }}</h4>
                    </div>
                    <div class="card-body">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#"> {{ isset($data['backendlang']['backendlang']['Home']) ? $data['backendlang']['backendlang']['Home'] :'' }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ isset($data['backendlang']['backendlang']['Setting_Website_Theme_Colour']) ? $data['backendlang']['backendlang']['Setting_Website_Theme_Colour'] :'' }}
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            @endif
            </div>
            <div class="page-content">
                @yield('content')
            </div>
        </div>
    </div>
</body>


<script src="{{ asset('assets/js/jquery-2.1.4.min.js') }}"></script>
<script src="{{ asset('NewBackend/assets/static/js/components/dark.js') }}"></script>
<script src="{{ asset('NewBackend/assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>

<script src="{{ asset('NewBackend/assets/compiled/js/app.js') }}"></script>
<script src="{{ asset('NewBackend/assets/extensions/apexcharts/apexcharts.min.js') }}"></script>
<!-- <script src="{{ asset('NewBackend/assets/static/js/pages/dashboard.js') }}"></script> -->
<script src="{{ asset('NewBackend/assets/extensions/flatpickr/flatpickr.min.js') }}"></script>
<script src="{{ asset('NewBackend/assets/static/js/pages/date-picker.js') }}"></script>


<!-- Old -->
<script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.4/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>


<script src="{{ asset('assets/js/dropzone.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.22.2/moment.min.js"></script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script src="{{ asset('frontend/tagsinput.js') }}"></script>


<script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('assets/js/daterangepicker.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap-datetimepicker.min.js') }}"></script>

<!-- CkEditor -->
<script src="{{ asset('js/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('js/ckeditor/plugins/video/plugin.js') }}"></script>
<script src="{{ asset('js/ckeditor/plugins/html5video/plugin.js') }}"></script>
<script type="text/javascript" src="{{ asset('frontend/plugins/owl-carousel/owl.carousel.min.js') }}"></script>

<!-- EndOld -->

<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>

<script language=Javascript>


    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function IsEmail(email) {
        var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if(!regex.test(email)) {
            return false;
        }else{
            return true;
        }
    }

    $('.submit-form-btn').on('click', '.btn-outline-primary', function(e){
        $('.loading-gif').show();
    });

    function isNumberKey(evt)
    {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)){
            return false;
        }
        return true;
    }

    var minDate = new Date();
        minDate.setDate(minDate.getDate() + 7);

    $( ".datepicker" ).datepicker({ 
        format: 'yyyy-mm-dd',
        minDate: minDate,
        autoclose: true
    });

    function changeBackendLanguage(value){
        var d = new Date();
        d.setTime(d.getTime() + (10*24*60*60*1000));
        var expires = "expires=" + d.toUTCString();
        document.cookie = 'backend_global_language' + "=" + value + ";" + expires + ";path=/";
        location.reload();
    }

</script>

@if(Request::segment(1) == 'admins')
<script type="text/javascript">
    /*! QRious v4.0.2 | (C) 2017 Alasdair Mercer | GPL v3 License
Based on jsqrencode | (C) 2010 tz@execpc.com | GPL v3 License
*/



!function(t,e){"object"==typeof exports&&"undefined"!=typeof module?module.exports=e():"function"==typeof define&&define.amd?define(e):t.QRious=e()}(this,function(){"use strict";function t(t,e){var n;return"function"==typeof Object.create?n=Object.create(t):(s.prototype=t,n=new s,s.prototype=null),e&&i(!0,n,e),n}function e(e,n,s,r){var o=this;return"string"!=typeof e&&(r=s,s=n,n=e,e=null),"function"!=typeof n&&(r=s,s=n,n=function(){return o.apply(this,arguments)}),i(!1,n,o,r),n.prototype=t(o.prototype,s),n.prototype.constructor=n,n.class_=e||o.class_,n.super_=o,n}function i(t,e,i){for(var n,s,a=0,h=(i=o.call(arguments,2)).length;a<h;a++){s=i[a];for(n in s)t&&!r.call(s,n)||(e[n]=s[n])}}function n(){}var s=function(){},r=Object.prototype.hasOwnProperty,o=Array.prototype.slice,a=e;n.class_="Nevis",n.super_=Object,n.extend=a;var h=n,f=h.extend(function(t,e,i){this.qrious=t,this.element=e,this.element.qrious=t,this.enabled=Boolean(i)},{draw:function(t){},getElement:function(){return this.enabled||(this.enabled=!0,this.render()),this.element},getModuleSize:function(t){var e=this.qrious,i=e.padding||0,n=Math.floor((e.size-2*i)/t.width);return Math.max(1,n)},getOffset:function(t){var e=this.qrious,i=e.padding;if(null!=i)return i;var n=this.getModuleSize(t),s=Math.floor((e.size-n*t.width)/2);return Math.max(0,s)},render:function(t){this.enabled&&(this.resize(),this.reset(),this.draw(t))},reset:function(){},resize:function(){}}),c=f.extend({draw:function(t){var e,i,n=this.qrious,s=this.getModuleSize(t),r=this.getOffset(t),o=this.element.getContext("2d");for(o.fillStyle=n.foreground,o.globalAlpha=n.foregroundAlpha,e=0;e<t.width;e++)for(i=0;i<t.width;i++)t.buffer[i*t.width+e]&&o.fillRect(s*e+r,s*i+r,s,s)},reset:function(){var t=this.qrious,e=this.element.getContext("2d"),i=t.size;e.lineWidth=1,e.clearRect(0,0,i,i),e.fillStyle=t.background,e.globalAlpha=t.backgroundAlpha,e.fillRect(0,0,i,i)},resize:function(){var t=this.element;t.width=t.height=this.qrious.size}}),u=h.extend(null,{BLOCK:[0,11,15,19,23,27,31,16,18,20,22,24,26,28,20,22,24,24,26,28,28,22,24,24,26,26,28,28,24,24,26,26,26,28,28,24,26,26,26,28,28]}),l=h.extend(null,{BLOCKS:[1,0,19,7,1,0,16,10,1,0,13,13,1,0,9,17,1,0,34,10,1,0,28,16,1,0,22,22,1,0,16,28,1,0,55,15,1,0,44,26,2,0,17,18,2,0,13,22,1,0,80,20,2,0,32,18,2,0,24,26,4,0,9,16,1,0,108,26,2,0,43,24,2,2,15,18,2,2,11,22,2,0,68,18,4,0,27,16,4,0,19,24,4,0,15,28,2,0,78,20,4,0,31,18,2,4,14,18,4,1,13,26,2,0,97,24,2,2,38,22,4,2,18,22,4,2,14,26,2,0,116,30,3,2,36,22,4,4,16,20,4,4,12,24,2,2,68,18,4,1,43,26,6,2,19,24,6,2,15,28,4,0,81,20,1,4,50,30,4,4,22,28,3,8,12,24,2,2,92,24,6,2,36,22,4,6,20,26,7,4,14,28,4,0,107,26,8,1,37,22,8,4,20,24,12,4,11,22,3,1,115,30,4,5,40,24,11,5,16,20,11,5,12,24,5,1,87,22,5,5,41,24,5,7,24,30,11,7,12,24,5,1,98,24,7,3,45,28,15,2,19,24,3,13,15,30,1,5,107,28,10,1,46,28,1,15,22,28,2,17,14,28,5,1,120,30,9,4,43,26,17,1,22,28,2,19,14,28,3,4,113,28,3,11,44,26,17,4,21,26,9,16,13,26,3,5,107,28,3,13,41,26,15,5,24,30,15,10,15,28,4,4,116,28,17,0,42,26,17,6,22,28,19,6,16,30,2,7,111,28,17,0,46,28,7,16,24,30,34,0,13,24,4,5,121,30,4,14,47,28,11,14,24,30,16,14,15,30,6,4,117,30,6,14,45,28,11,16,24,30,30,2,16,30,8,4,106,26,8,13,47,28,7,22,24,30,22,13,15,30,10,2,114,28,19,4,46,28,28,6,22,28,33,4,16,30,8,4,122,30,22,3,45,28,8,26,23,30,12,28,15,30,3,10,117,30,3,23,45,28,4,31,24,30,11,31,15,30,7,7,116,30,21,7,45,28,1,37,23,30,19,26,15,30,5,10,115,30,19,10,47,28,15,25,24,30,23,25,15,30,13,3,115,30,2,29,46,28,42,1,24,30,23,28,15,30,17,0,115,30,10,23,46,28,10,35,24,30,19,35,15,30,17,1,115,30,14,21,46,28,29,19,24,30,11,46,15,30,13,6,115,30,14,23,46,28,44,7,24,30,59,1,16,30,12,7,121,30,12,26,47,28,39,14,24,30,22,41,15,30,6,14,121,30,6,34,47,28,46,10,24,30,2,64,15,30,17,4,122,30,29,14,46,28,49,10,24,30,24,46,15,30,4,18,122,30,13,32,46,28,48,14,24,30,42,32,15,30,20,4,117,30,40,7,47,28,43,22,24,30,10,67,15,30,19,6,118,30,18,31,47,28,34,34,24,30,20,61,15,30],FINAL_FORMAT:[30660,29427,32170,30877,26159,25368,27713,26998,21522,20773,24188,23371,17913,16590,20375,19104,13663,12392,16177,14854,9396,8579,11994,11245,5769,5054,7399,6608,1890,597,3340,2107],LEVELS:{L:1,M:2,Q:3,H:4}}),_=h.extend(null,{EXPONENT:[1,2,4,8,16,32,64,128,29,58,116,232,205,135,19,38,76,152,45,90,180,117,234,201,143,3,6,12,24,48,96,192,157,39,78,156,37,74,148,53,106,212,181,119,238,193,159,35,70,140,5,10,20,40,80,160,93,186,105,210,185,111,222,161,95,190,97,194,153,47,94,188,101,202,137,15,30,60,120,240,253,231,211,187,107,214,177,127,254,225,223,163,91,182,113,226,217,175,67,134,17,34,68,136,13,26,52,104,208,189,103,206,129,31,62,124,248,237,199,147,59,118,236,197,151,51,102,204,133,23,46,92,184,109,218,169,79,158,33,66,132,21,42,84,168,77,154,41,82,164,85,170,73,146,57,114,228,213,183,115,230,209,191,99,198,145,63,126,252,229,215,179,123,246,241,255,227,219,171,75,150,49,98,196,149,55,110,220,165,87,174,65,130,25,50,100,200,141,7,14,28,56,112,224,221,167,83,166,81,162,89,178,121,242,249,239,195,155,43,86,172,69,138,9,18,36,72,144,61,122,244,245,247,243,251,235,203,139,11,22,44,88,176,125,250,233,207,131,27,54,108,216,173,71,142,0],LOG:[255,0,1,25,2,50,26,198,3,223,51,238,27,104,199,75,4,100,224,14,52,141,239,129,28,193,105,248,200,8,76,113,5,138,101,47,225,36,15,33,53,147,142,218,240,18,130,69,29,181,194,125,106,39,249,185,201,154,9,120,77,228,114,166,6,191,139,98,102,221,48,253,226,152,37,179,16,145,34,136,54,208,148,206,143,150,219,189,241,210,19,92,131,56,70,64,30,66,182,163,195,72,126,110,107,58,40,84,250,133,186,61,202,94,155,159,10,21,121,43,78,212,229,172,115,243,167,87,7,112,192,247,140,128,99,13,103,74,222,237,49,197,254,24,227,165,153,119,38,184,180,124,17,68,146,217,35,32,137,46,55,63,209,91,149,188,207,205,144,135,151,178,220,252,190,97,242,86,211,171,20,42,93,158,132,60,57,83,71,109,65,162,31,45,67,216,183,123,164,118,196,23,73,236,127,12,111,246,108,161,59,82,41,157,85,170,251,96,134,177,187,204,62,90,203,89,95,176,156,169,160,81,11,245,22,235,122,117,44,215,79,174,213,233,230,231,173,232,116,214,244,234,168,80,88,175]}),d=h.extend(null,{BLOCK:[3220,1468,2713,1235,3062,1890,2119,1549,2344,2936,1117,2583,1330,2470,1667,2249,2028,3780,481,4011,142,3098,831,3445,592,2517,1776,2234,1951,2827,1070,2660,1345,3177]}),v=h.extend(function(t){var e,i,n,s,r,o=t.value.length;for(this._badness=[],this._level=l.LEVELS[t.level],this._polynomial=[],this._value=t.value,this._version=0,this._stringBuffer=[];this._version<40&&(this._version++,n=4*(this._level-1)+16*(this._version-1),s=l.BLOCKS[n++],r=l.BLOCKS[n++],e=l.BLOCKS[n++],i=l.BLOCKS[n],n=e*(s+r)+r-3+(this._version<=9),!(o<=n)););this._dataBlock=e,this._eccBlock=i,this._neccBlock1=s,this._neccBlock2=r;var a=this.width=17+4*this._version;this.buffer=v._createArray(a*a),this._ecc=v._createArray(e+(e+i)*(s+r)+r),this._mask=v._createArray((a*(a+1)+1)/2),this._insertFinders(),this._insertAlignments(),this.buffer[8+a*(a-8)]=1,this._insertTimingGap(),this._reverseMask(),this._insertTimingRowAndColumn(),this._insertVersion(),this._syncMask(),this._convertBitStream(o),this._calculatePolynomial(),this._appendEccToData(),this._interleaveBlocks(),this._pack(),this._finish()},{_addAlignment:function(t,e){var i,n=this.buffer,s=this.width;for(n[t+s*e]=1,i=-2;i<2;i++)n[t+i+s*(e-2)]=1,n[t-2+s*(e+i+1)]=1,n[t+2+s*(e+i)]=1,n[t+i+1+s*(e+2)]=1;for(i=0;i<2;i++)this._setMask(t-1,e+i),this._setMask(t+1,e-i),this._setMask(t-i,e-1),this._setMask(t+i,e+1)},_appendData:function(t,e,i,n){var s,r,o,a=this._polynomial,h=this._stringBuffer;for(r=0;r<n;r++)h[i+r]=0;for(r=0;r<e;r++){if(255!==(s=_.LOG[h[t+r]^h[i]]))for(o=1;o<n;o++)h[i+o-1]=h[i+o]^_.EXPONENT[v._modN(s+a[n-o])];else for(o=i;o<i+n;o++)h[o]=h[o+1];h[i+n-1]=255===s?0:_.EXPONENT[v._modN(s+a[0])]}},_appendEccToData:function(){var t,e=0,i=this._dataBlock,n=this._calculateMaxLength(),s=this._eccBlock;for(t=0;t<this._neccBlock1;t++)this._appendData(e,i,n,s),e+=i,n+=s;for(t=0;t<this._neccBlock2;t++)this._appendData(e,i+1,n,s),e+=i+1,n+=s},_applyMask:function(t){var e,i,n,s,r=this.buffer,o=this.width;switch(t){case 0:for(s=0;s<o;s++)for(n=0;n<o;n++)n+s&1||this._isMasked(n,s)||(r[n+s*o]^=1);break;case 1:for(s=0;s<o;s++)for(n=0;n<o;n++)1&s||this._isMasked(n,s)||(r[n+s*o]^=1);break;case 2:for(s=0;s<o;s++)for(e=0,n=0;n<o;n++,e++)3===e&&(e=0),e||this._isMasked(n,s)||(r[n+s*o]^=1);break;case 3:for(i=0,s=0;s<o;s++,i++)for(3===i&&(i=0),e=i,n=0;n<o;n++,e++)3===e&&(e=0),e||this._isMasked(n,s)||(r[n+s*o]^=1);break;case 4:for(s=0;s<o;s++)for(e=0,i=s>>1&1,n=0;n<o;n++,e++)3===e&&(e=0,i=!i),i||this._isMasked(n,s)||(r[n+s*o]^=1);break;case 5:for(i=0,s=0;s<o;s++,i++)for(3===i&&(i=0),e=0,n=0;n<o;n++,e++)3===e&&(e=0),(n&s&1)+!(!e|!i)||this._isMasked(n,s)||(r[n+s*o]^=1);break;case 6:for(i=0,s=0;s<o;s++,i++)for(3===i&&(i=0),e=0,n=0;n<o;n++,e++)3===e&&(e=0),(n&s&1)+(e&&e===i)&1||this._isMasked(n,s)||(r[n+s*o]^=1);break;case 7:for(i=0,s=0;s<o;s++,i++)for(3===i&&(i=0),e=0,n=0;n<o;n++,e++)3===e&&(e=0),(e&&e===i)+(n+s&1)&1||this._isMasked(n,s)||(r[n+s*o]^=1)}},_calculateMaxLength:function(){return this._dataBlock*(this._neccBlock1+this._neccBlock2)+this._neccBlock2},_calculatePolynomial:function(){var t,e,i=this._eccBlock,n=this._polynomial;for(n[0]=1,t=0;t<i;t++){for(n[t+1]=1,e=t;e>0;e--)n[e]=n[e]?n[e-1]^_.EXPONENT[v._modN(_.LOG[n[e]]+t)]:n[e-1];n[0]=_.EXPONENT[v._modN(_.LOG[n[0]]+t)]}for(t=0;t<=i;t++)n[t]=_.LOG[n[t]]},_checkBadness:function(){var t,e,i,n,s,r=0,o=this._badness,a=this.buffer,h=this.width;for(s=0;s<h-1;s++)for(n=0;n<h-1;n++)(a[n+h*s]&&a[n+1+h*s]&&a[n+h*(s+1)]&&a[n+1+h*(s+1)]||!(a[n+h*s]||a[n+1+h*s]||a[n+h*(s+1)]||a[n+1+h*(s+1)]))&&(r+=v.N2);var f=0;for(s=0;s<h;s++){for(i=0,o[0]=0,t=0,n=0;n<h;n++)t===(e=a[n+h*s])?o[i]++:o[++i]=1,f+=(t=e)?1:-1;r+=this._getBadness(i)}f<0&&(f=-f);var c=0,u=f;for(u+=u<<2,u<<=1;u>h*h;)u-=h*h,c++;for(r+=c*v.N4,n=0;n<h;n++){for(i=0,o[0]=0,t=0,s=0;s<h;s++)t===(e=a[n+h*s])?o[i]++:o[++i]=1,t=e;r+=this._getBadness(i)}return r},_convertBitStream:function(t){var e,i,n=this._ecc,s=this._version;for(i=0;i<t;i++)n[i]=this._value.charCodeAt(i);var r=this._stringBuffer=n.slice(),o=this._calculateMaxLength();t>=o-2&&(t=o-2,s>9&&t--);var a=t;if(s>9){for(r[a+2]=0,r[a+3]=0;a--;)e=r[a],r[a+3]|=255&e<<4,r[a+2]=e>>4;r[2]|=255&t<<4,r[1]=t>>4,r[0]=64|t>>12}else{for(r[a+1]=0,r[a+2]=0;a--;)e=r[a],r[a+2]|=255&e<<4,r[a+1]=e>>4;r[1]|=255&t<<4,r[0]=64|t>>4}for(a=t+3-(s<10);a<o;)r[a++]=236,r[a++]=17},_getBadness:function(t){var e,i=0,n=this._badness;for(e=0;e<=t;e++)n[e]>=5&&(i+=v.N1+n[e]-5);for(e=3;e<t-1;e+=2)n[e-2]===n[e+2]&&n[e+2]===n[e-1]&&n[e-1]===n[e+1]&&3*n[e-1]===n[e]&&(0===n[e-3]||e+3>t||3*n[e-3]>=4*n[e]||3*n[e+3]>=4*n[e])&&(i+=v.N3);return i},_finish:function(){this._stringBuffer=this.buffer.slice();var t,e,i=0,n=3e4;for(e=0;e<8&&(this._applyMask(e),(t=this._checkBadness())<n&&(n=t,i=e),7!==i);e++)this.buffer=this._stringBuffer.slice();i!==e&&this._applyMask(i),n=l.FINAL_FORMAT[i+(this._level-1<<3)];var s=this.buffer,r=this.width;for(e=0;e<8;e++,n>>=1)1&n&&(s[r-1-e+8*r]=1,e<6?s[8+r*e]=1:s[8+r*(e+1)]=1);for(e=0;e<7;e++,n>>=1)1&n&&(s[8+r*(r-7+e)]=1,e?s[6-e+8*r]=1:s[7+8*r]=1)},_interleaveBlocks:function(){var t,e,i=this._dataBlock,n=this._ecc,s=this._eccBlock,r=0,o=this._calculateMaxLength(),a=this._neccBlock1,h=this._neccBlock2,f=this._stringBuffer;for(t=0;t<i;t++){for(e=0;e<a;e++)n[r++]=f[t+e*i];for(e=0;e<h;e++)n[r++]=f[a*i+t+e*(i+1)]}for(e=0;e<h;e++)n[r++]=f[a*i+t+e*(i+1)];for(t=0;t<s;t++)for(e=0;e<a+h;e++)n[r++]=f[o+t+e*s];this._stringBuffer=n},_insertAlignments:function(){var t,e,i,n=this._version,s=this.width;if(n>1)for(t=u.BLOCK[n],i=s-7;;){for(e=s-7;e>t-3&&(this._addAlignment(e,i),!(e<t));)e-=t;if(i<=t+9)break;i-=t,this._addAlignment(6,i),this._addAlignment(i,6)}},_insertFinders:function(){var t,e,i,n,s=this.buffer,r=this.width;for(t=0;t<3;t++){for(e=0,n=0,1===t&&(e=r-7),2===t&&(n=r-7),s[n+3+r*(e+3)]=1,i=0;i<6;i++)s[n+i+r*e]=1,s[n+r*(e+i+1)]=1,s[n+6+r*(e+i)]=1,s[n+i+1+r*(e+6)]=1;for(i=1;i<5;i++)this._setMask(n+i,e+1),this._setMask(n+1,e+i+1),this._setMask(n+5,e+i),this._setMask(n+i+1,e+5);for(i=2;i<4;i++)s[n+i+r*(e+2)]=1,s[n+2+r*(e+i+1)]=1,s[n+4+r*(e+i)]=1,s[n+i+1+r*(e+4)]=1}},_insertTimingGap:function(){var t,e,i=this.width;for(e=0;e<7;e++)this._setMask(7,e),this._setMask(i-8,e),this._setMask(7,e+i-7);for(t=0;t<8;t++)this._setMask(t,7),this._setMask(t+i-8,7),this._setMask(t,i-8)},_insertTimingRowAndColumn:function(){var t,e=this.buffer,i=this.width;for(t=0;t<i-14;t++)1&t?(this._setMask(8+t,6),this._setMask(6,8+t)):(e[8+t+6*i]=1,e[6+i*(8+t)]=1)},_insertVersion:function(){var t,e,i,n,s=this.buffer,r=this._version,o=this.width;if(r>6)for(t=d.BLOCK[r-7],e=17,i=0;i<6;i++)for(n=0;n<3;n++,e--)1&(e>11?r>>e-12:t>>e)?(s[5-i+o*(2-n+o-11)]=1,s[2-n+o-11+o*(5-i)]=1):(this._setMask(5-i,2-n+o-11),this._setMask(2-n+o-11,5-i))},_isMasked:function(t,e){var i=v._getMaskBit(t,e);return 1===this._mask[i]},_pack:function(){var t,e,i,n=1,s=1,r=this.width,o=r-1,a=r-1,h=(this._dataBlock+this._eccBlock)*(this._neccBlock1+this._neccBlock2)+this._neccBlock2;for(e=0;e<h;e++)for(t=this._stringBuffer[e],i=0;i<8;i++,t<<=1){128&t&&(this.buffer[o+r*a]=1);do{s?o--:(o++,n?0!==a?a--:(n=!n,6===(o-=2)&&(o--,a=9)):a!==r-1?a++:(n=!n,6===(o-=2)&&(o--,a-=8))),s=!s}while(this._isMasked(o,a))}},_reverseMask:function(){var t,e,i=this.width;for(t=0;t<9;t++)this._setMask(t,8);for(t=0;t<8;t++)this._setMask(t+i-8,8),this._setMask(8,t);for(e=0;e<7;e++)this._setMask(8,e+i-7)},_setMask:function(t,e){var i=v._getMaskBit(t,e);this._mask[i]=1},_syncMask:function(){var t,e,i=this.width;for(e=0;e<i;e++)for(t=0;t<=e;t++)this.buffer[t+i*e]&&this._setMask(t,e)}},{_createArray:function(t){var e,i=[];for(e=0;e<t;e++)i[e]=0;return i},_getMaskBit:function(t,e){var i;return t>e&&(i=t,t=e,e=i),i=e,i+=e*e,i>>=1,i+=t},_modN:function(t){for(;t>=255;)t=((t-=255)>>8)+(255&t);return t},N1:3,N2:3,N3:40,N4:10}),p=v,m=f.extend({draw:function(){this.element.src=this.qrious.toDataURL()},reset:function(){this.element.src=""},resize:function(){var t=this.element;t.width=t.height=this.qrious.size}}),g=h.extend(function(t,e,i,n){this.name=t,this.modifiable=Boolean(e),this.defaultValue=i,this._valueTransformer=n},{transform:function(t){var e=this._valueTransformer;return"function"==typeof e?e(t,this):t}}),k=h.extend(null,{abs:function(t){return null!=t?Math.abs(t):null},hasOwn:function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},noop:function(){},toUpperCase:function(t){return null!=t?t.toUpperCase():null}}),w=h.extend(function(t){this.options={},t.forEach(function(t){this.options[t.name]=t},this)},{exists:function(t){return null!=this.options[t]},get:function(t,e){return w._get(this.options[t],e)},getAll:function(t){var e,i=this.options,n={};for(e in i)k.hasOwn(i,e)&&(n[e]=w._get(i[e],t));return n},init:function(t,e,i){"function"!=typeof i&&(i=k.noop);var n,s;for(n in this.options)k.hasOwn(this.options,n)&&(s=this.options[n],w._set(s,s.defaultValue,e),w._createAccessor(s,e,i));this._setAll(t,e,!0)},set:function(t,e,i){return this._set(t,e,i)},setAll:function(t,e){return this._setAll(t,e)},_set:function(t,e,i,n){var s=this.options[t];if(!s)throw new Error("Invalid option: "+t);if(!s.modifiable&&!n)throw new Error("Option cannot be modified: "+t);return w._set(s,e,i)},_setAll:function(t,e,i){if(!t)return!1;var n,s=!1;for(n in t)k.hasOwn(t,n)&&this._set(n,t[n],e,i)&&(s=!0);return s}},{_createAccessor:function(t,e,i){var n={get:function(){return w._get(t,e)}};t.modifiable&&(n.set=function(n){w._set(t,n,e)&&i(n,t)}),Object.defineProperty(e,t.name,n)},_get:function(t,e){return e["_"+t.name]},_set:function(t,e,i){var n="_"+t.name,s=i[n],r=t.transform(null!=e?e:t.defaultValue);return i[n]=r,r!==s}}),M=w,b=h.extend(function(){this._services={}},{getService:function(t){var e=this._services[t];if(!e)throw new Error("Service is not being managed with name: "+t);return e},setService:function(t,e){if(this._services[t])throw new Error("Service is already managed with name: "+t);e&&(this._services[t]=e)}}),B=new M([new g("background",!0,"white"),new g("backgroundAlpha",!0,1,k.abs),new g("element"),new g("foreground",!0,"black"),new g("foregroundAlpha",!0,1,k.abs),new g("level",!0,"L",k.toUpperCase),new g("mime",!0,"image/png"),new g("padding",!0,null,k.abs),new g("size",!0,100,k.abs),new g("value",!0,"")]),y=new b,O=h.extend(function(t){B.init(t,this,this.update.bind(this));var e=B.get("element",this),i=y.getService("element"),n=e&&i.isCanvas(e)?e:i.createCanvas(),s=e&&i.isImage(e)?e:i.createImage();this._canvasRenderer=new c(this,n,!0),this._imageRenderer=new m(this,s,s===e),this.update()},{get:function(){return B.getAll(this)},set:function(t){B.setAll(t,this)&&this.update()},toDataURL:function(t){return this.canvas.toDataURL(t||this.mime)},update:function(){var t=new p({level:this.level,value:this.value});this._canvasRenderer.render(t),this._imageRenderer.render(t)}},{use:function(t){y.setService(t.getName(),t)}});Object.defineProperties(O.prototype,{canvas:{get:function(){return this._canvasRenderer.getElement()}},image:{get:function(){return this._imageRenderer.getElement()}}});var A=O,L=h.extend({getName:function(){}}).extend({createCanvas:function(){},createImage:function(){},getName:function(){return"element"},isCanvas:function(t){},isImage:function(t){}}).extend({createCanvas:function(){return document.createElement("canvas")},createImage:function(){return document.createElement("img")},isCanvas:function(t){return t instanceof HTMLCanvasElement},isImage:function(t){return t instanceof HTMLImageElement}});return A.use(new L),A});
    
    
    var canvas2 = new QRious({
        element: document.getElementById('qr-customer'),
        value: "{{ route('QrPayment', Auth::user()->code) }}",
        size: '250',
        background: 'white',
        foreground: 'black',
        level: 'L',
        padding: '38',
        foregroundAlpha: '2.8'
    })


    var canvas2 = document.getElementById('qr-customer');
    var ctx = canvas2.getContext('2d');
        ctx.webkitImageSmoothingEnabled = false;
        ctx.mozImageSmoothingEnabled = false;
        ctx.imageSmoothingEnabled = false;
        ctx.retinaResolutionEnabled = false;
        // Set display size (css pixels).
    var size = 200;

        // // Set actual size in memory (scaled to account for extra pixel density).
    var scale = window.devicePixelRatio; // Change to 1 on retina screens to see blurry canvas2.
        canvas2.style.width = size + "px";
        canvas2.style.height = size + "px";

        ctx.fillStyle = "#000000";
        ctx.fillRect(37, 217, 175, 30);
        ctx.fillStyle = "#FFFFFF";
        
        ctx.font = '12px Signika Negative';
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';

        var x = size / 1.6;
        var y = size / 0.855;

        var textString = "{{ $data['web_setting']->website_name }}";
        ctx.fillText(textString, x, y);

        function downloadURI(uri, name) {
        var link = document.createElement('a');
        link.download = name;
        link.href = uri;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        delete link;
      }

    document.getElementById('save-two').addEventListener(
        'click',
        function() {
          var dataURL = canvas2.toDataURL({ pixelRatio: 300 });
          downloadURI(dataURL, 'MyQRcode.jpeg');
        },
        false
    );
</script>
@endif
@toastr_js
@toastr_render
@yield('js')
</body>
</html>
