@if(Request::segment(1) == '')
<div class="banner_top" id="home">
    <div class="wrapper_top_w3layouts">

        <div class="header_agileits">
            <div class="logo">
                <h1>
                    <a class="navbar-brand" href="{{ route('home') }}">
                        <span>香港时闰集团</span> <i style="color: #bdbdbd;">Seegreen</i>
                    </a>
                </h1>
            </div>
            <div class="overlay overlay-contentpush">
                <div class="menu-top-background" style="background-image: url({{ asset('images/1.jpg') }})">
                    @if(Auth::check() || Auth::guard('merchant')->check() || Auth::guard('admin')->check())
                        <div class="col-xs-3" style="padding: 0px;">
                            @if(!empty(Auth::guard($data['userGuardRole'])->user()->profile_logo))
                            <div class="menu-top-user-image" style="background-image: url({{ asset(Auth::guard($data['userGuardRole'])->user()->profile_logo) }})"></div>
                            @else
                                <div class="menu-top-user-image" style="background-image: url({{ asset('images/50200009239445155973051638415_s.jpg') }})"></div>
                            @endif
                        </div>
                        <div class="col-xs-6">
                            <div class="menu-top-user-name" style="color: white !important;">
                                @if(empty(Auth::guard($data['userGuardRole'])->user()->f_name))
                                {{ Auth::guard($data['userGuardRole'])->user()->phone }} <br>
                                @else
                                {{ Auth::guard($data['userGuardRole'])->user()->f_name }} <br>
                                @endif
                                <span style="font-size: 11px;">ID: {{ Auth::guard($data['userGuardRole'])->user()->code }}</span><br>
                                @php
                                if(Auth::guard($data['userGuardRole'])->user()->permission_lvl == '1'){
                                    $permission = isset($data['lang']['lang']['super_admin']) ? $data['lang']['lang']['super_admin'] : '高级管理员';
                                }elseif(Auth::guard($data['userGuardRole'])->user()->permission_lvl == '2'){
                                    $permission = isset($data['lang']['lang']['admin']) ? $data['lang']['lang']['admin'] : '初级管理员';
                                }else{
                                    $permission = "";
                                }

                                @endphp
                                
                            </div>
                        </div>
                    @else
                        <div class="col-md-12 col-md-offset-4" style="margin-top: 40px;">
                            @if(!empty(Auth::guard($data['userGuardRole'])->user()->profile_logo))
                            <div class="menu-top-user-image" style="background-image: url({{ asset(Auth::guard($data['userGuardRole'])->user()->profile_logo) }})"></div>
                            @else
                                <div class="menu-top-user-image" style="background-image: url({{ asset('images/50200009239445155973051638415_s.jpg') }})"></div>
                            @endif
                        </div>
                    @endif

                    <div class="menu-top-my-profile">
                        @if(Auth::check() || Auth::guard('merchant')->check() || Auth::guard('admin')->check())
                            <div class="row">
                                <div class="col-xs-4" align="center">
                                    <a href="{{ route('profile') }}">
                                        <div class="form-group">
                                            <img src="{{ asset('images/19339625881548233621-512.png') }}" width="30px">
                                        </div>

                                        <div class="form-group"  style="color: white !important;">
                                            {{ isset($data['lang']['lang']['my_accounts']) ? $data['lang']['lang']['my_accounts'] : '我的账户' }}
                                        </div>
                                    </a>
                                </div>
                                <div class="col-xs-4" align="center">
                                    <a href="{{ route('pending_order') }}">
                                        <div class="form-group">
                                            <img src="{{ asset('images/sidebar-my-order.png') }}" width="30px">
                                        </div>

                                        <div class="form-group" style="color: white !important;">
                                            {{ isset($data['lang']['lang']['my_orders']) ? $data['lang']['lang']['my_orders'] : '我的订单' }}
                                        </div>
                                    </a>
                                </div>
                                <div class="col-xs-4" align="center">
                                    <a href="{{ route('wallet') }}">
                                        <div class="form-group">
                                            <img src="{{ asset('images/sidebar-earning.png') }}" width="30px">
                                        </div>

                                        <div class="form-group" style="color: white !important;">
                                            {{ isset($data['lang']['lang']['earnings']) ? $data['lang']['lang']['earnings'] : '我的收益' }}
                                        </div>
                                    </a>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-4" align="center">
                                    <a href="{{ route('wish_list') }}">
                                        <div class="form-group">
                                            <img src="{{ asset('images/icon-ios7-heart-outline-512.png') }}" width="30px">
                                        </div>

                                        <div class="form-group" style="color: white !important;">
                                            {{ isset($data['lang']['lang']['favourite']) ? $data['lang']['lang']['favourite'] : '我的收藏' }}
                                        </div>
                                    </a>
                                </div>
                                @if(Auth::guard($data['userGuardRole'])->check() && Auth::guard('merchant')->check() || Auth::guard('admin')->check())
                                <div class="col-xs-4" align="center">
                                    <a href="{{ route('myqrcode') }}">
                                        <div class="form-group">
                                            <img src="{{ asset('images/qrcode.png') }}" width="30px">
                                        </div>

                                        <div class="form-group" style="color: white !important;">
                                            {{ isset($data['lang']['lang']['qrcode']) ? $data['lang']['lang']['qrcode'] : '我的二维码' }}
                                        </div>
                                    </a>
                                </div>
                                @endif
                                <div class="col-xs-4" align="center">
                                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <div class="form-group">
                                            <img src="{{ asset('images/free-exit-logout-icon-2857-thumb.png') }}" width="30px">
                                        </div>

                                        <div class="form-group" style="color: white !important;">
                                            {{ isset($data['lang']['lang']['logout']) ? $data['lang']['lang']['logout'] : '登出' }}
                                        </div>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    </a>
                                </div>
                            </div>
                        @else
                        <div class="col-xs-6" align="center">
                            <a href="{{ route('login') }}" class="login-register-button">
                                {{ isset($data['lang']['lang']['login']) ? $data['lang']['lang']['login'] : '登录' }}
                            </a>
                        </div>
                        <div class="col-xs-6" align="center">
                            <a href="{{ route('register') }}" class="login-register-button">
                                {{ isset($data['lang']['lang']['register']) ? $data['lang']['lang']['register'] : '注册' }}
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
                <button type="button" class="overlay-close"><i class="fa fa-times" aria-hidden="true"></i></button>

                <nav>
                    <ul>
                        <li>
                            <b>
                                {{ isset($data['lang']['lang']['menu']) ? $data['lang']['lang']['menu'] : '目录' }}
                            </b>
                        </li>
                        <li><a href="{{ route('home') }}" class="active">{{ isset($data['lang']['lang']['home']) ? $data['lang']['lang']['home'] : '首页' }}</a></li>
                        <li><a href="{{ route('about') }}">{{ isset($data['lang']['lang']['about_us']) ? $data['lang']['lang']['about_us'] : '关于我们' }}</a></li>
			<li><a href="{{ route('faqs') }}">常见问题</a></li>
                        <li><a href="{{ route('listing') }}">{{ isset($data['lang']['lang']['buy_now']) ? $data['lang']['lang']['buy_now'] : '立刻购买' }}</a></li>
                        <!-- <li>
                            <a href="https://api.whatsapp.com/send?phone=60168650888&text=您好! 我想了解更多关于因诗美的产品.&source=&data=" target="_blank">
                                {{ isset($data['lang']['lang']['contact_us']) ? $data['lang']['lang']['contact_us'] : '联系我们' }}
                            </a>
                        </li> -->
                        <hr>
                        <li>
                            <b>
                                {{ isset($data['lang']['lang']['language']) ? $data['lang']['lang']['language'] : '语言' }}
                            </b>
                        </li>
                        <li>
                            <select class="form-control global_language" name="global_language">
                                <option {{ isset($_COOKIE['global_language']) && $_COOKIE['global_language'] == '1' ? 'selected' : '' }} value="1">简体中文</option>
                                <option {{ isset($_COOKIE['global_language']) && $_COOKIE['global_language'] == '2' ? 'selected' : '' }} value="2">English</option>
                            </select>
                        </li>
                    </ul>
                </nav>
            </div>
            <div class="mobile-nav-button">
                <button id="trigger-overlay" type="button"><i class="fa fa-bars" aria-hidden="true"></i></button>
            </div>
            <!-- cart details -->
            
            <!-- //cart details -->
            <!-- search -->
            <div class="search_w3ls_agileinfo">
                <div class="cd-main-header">
                    <ul class="cd-header-buttons">
                        <li><a class="cd-search-trigger" href="#cd-search"> <span></span></a></li>
                    </ul>
                </div>
                <div id="cd-search" class="cd-search">
                    <form method="GET" action="{{ route('listing') }}">
                        <input name="result" type="search" placeholder="{{ isset($data['lang']['lang']['search']) ? $data['lang']['lang']['search'] : '搜索' }}..">
                    </form>
                </div>

                
            </div>
            <div class="cart-button">
                <a href="{{ route('cart') }}" class="top_shoe_cart" style="position: relative;">
                    <i class="fa fa-cart-arrow-down" aria-hidden="true"></i>
                    <span class="badge" style="position: absolute; top: -10px; right: -10px; background-color: red;">{{ $data['totalCart'] }}</span>
                </a>
                
            </div>
            <!-- //search -->

            <div class="clearfix"></div>
        </div>
        <!-- /slider -->
        <div class="slider desktop-version-banner">
            <div class="callbacks_container">
                <ul class="rslides callbacks callbacks1" id="slider4">
                    @if(!$data['banners']->isEmpty())
                    @foreach($data['banners'] as $banner)
                    <li class="banner-top1 promotion-img">
                       <img src="{{ asset($banner->image) }}" width="100%"> 
                    </li>
                    @endforeach
                    @endif
                </ul>
            </div>
            <div class="clearfix"> </div>
        </div>

        <div class="slider mobile-version-banner">
            <div class="callbacks_container">
                <ul class="rslides callbacks callbacks1" id="slider4">
                    @if(!$data['banners']->isEmpty())
                    @foreach($data['banners'] as $banner)
                    <li class="banner-top2 promotion-img">
                       <img src="{{ asset($banner->image) }}" width="100%"> 
                    </li>
                    @endforeach
                    @endif
                    
                </ul>
            </div>
            <div class="clearfix"> </div>
        </div>
        <!-- //slider -->
        <!-- <ul class="top_icons">
            <li><a href="#"><span class="fa fa-facebook" aria-hidden="true"></span></a></li>
            <li><a href="#"><span class="fa fa-twitter" aria-hidden="true"></span></a></li>
            <li><a href="#"><span class="fa fa-linkedin" aria-hidden="true"></span></a></li>
            <li><a href="#"><span class="fa fa-google-plus" aria-hidden="true"></span></a></li>

        </ul> -->
    </div>
</div>
<div class="top-menu-bar">
    <div class="row">
        <div class="col-xs-3" align="center">
            <div class="top-menu-bar-box">
                <a href="{{ route('home') }}">
                    <i class="fa fa-home fa-2x gold-word"></i>
                    <br>
                    <span class="gold-word">{{ isset($data['lang']['lang']['home']) ? $data['lang']['lang']['home'] : '首页' }}</span>            
                </a>
            </div>
        </div>

        <div class="col-xs-3" align="center">
            <div class="top-menu-bar-box">
                <a href="{{ route('listing') }}">
                    <i class="fa fa-cubes fa-2x gold-word"></i>
                    <br>
                    <span class="gold-word">{{ isset($data['lang']['lang']['products']) ? $data['lang']['lang']['products'] : '产品' }}</span>
                </a>
            </div>
        </div>

        <div class="col-xs-3" align="center">
            <div class="top-menu-bar-box">
                <a href="{{ route('Material') }}" style="position: relative;">
                    <i class="fa fa-cube fa-2x gold-word" aria-hidden="true"></i>
                    <br>
                    <span class="gold-word">{{ isset($data['lang']['lang']['meterials']) ? $data['lang']['lang']['meterials'] : '素材' }}</span>
                    
                </a>
            </div>
        </div>

        <div class="col-xs-3" align="center">
            <div class="top-menu-bar-box">
                <a href="{{ route('profile') }}">
                    <i class="fa fa-user fa-2x gold-word"></i>
                    <br>
                    <span class="gold-word">{{ isset($data['lang']['lang']['accounts']) ? $data['lang']['lang']['accounts'] : '我的' }}</span>
                </a>
            </div>
        </div>
    </div>
</div>
<div class="bottom-menu-bar">
    <div class="row">
        <div class="col-xs-3" align="center">
            <div class="top-menu-bar-box">
                <a href="{{ route('home') }}">
                    <i class="fa fa-home fa-2x gold-word"></i>
                    <br>
                    <span class="gold-word">{{ isset($data['lang']['lang']['home']) ? $data['lang']['lang']['home'] : '首页' }}</span>            
                </a>
            </div>
        </div>

        <div class="col-xs-3" align="center">
            <div class="top-menu-bar-box">
                <a href="{{ route('listing') }}">
                    <i class="fa fa-cubes fa-2x gold-word"></i>
                    <br>
                    <span class="gold-word">{{ isset($data['lang']['lang']['products']) ? $data['lang']['lang']['products'] : '产品' }}</span>
                </a>
            </div>
        </div>

        <div class="col-xs-3" align="center">
            <div class="top-menu-bar-box">
                <a href="{{ route('Material') }}" style="position: relative;">
                    <i class="fa fa-cube fa-2x gold-word" aria-hidden="true"></i>
                    <br>
                    <span class="gold-word">{{ isset($data['lang']['lang']['meterials']) ? $data['lang']['lang']['meterials'] : '素材' }}</span>
                    
                </a>
            </div>
        </div>

        <div class="col-xs-3" align="center">
            <div class="top-menu-bar-box">
                <a href="{{ route('profile') }}">
                    <i class="fa fa-user fa-2x gold-word"></i>
                    <br>
                    <span class="gold-word">{{ isset($data['lang']['lang']['accounts']) ? $data['lang']['lang']['accounts'] : '我的' }}</span>
                </a>
            </div>
        </div>
    </div>
</div>
@else
@if(Request::segment(1) != 'MyAffiliate' && Request::segment(1) != 'Profile' && Request::segment(1) != 'MyWallet' && Request::segment(1) != 'AddressBook' && Request::segment(1) != 'MyOrder' && 
    Request::segment(1) != 'MyWishList' && Request::segment(1) != 'OrderDetails' && Request::segment(1) != 'withdrawal' && Request::segment(1) != 'BankAccount' && Request::segment(1) != 'MySetting' && 
    Request::segment(1) != 'LogisticTracking' && Request::segment(1) != 'PendingOrder' && Request::segment(1) != 'PackingOrder' && Request::segment(1) != 'PendingReceiveOrder' &&
    Request::segment(1) != 'CompletedOrder' && Request::segment(1) != 'CancelledOrder' && Request::segment(1) != 'MyQRcode')
<div class="banner_top innerpage" id="home">
    <div class="wrapper_top_w3layouts">
        <div class="header_agileits">
            <div class="logo inner_page_log">
                <h1>
                    <a class="navbar-brand" href="{{ route('home') }}" >
                        <span>香港时闰集团</span> <i style="color: #bdbdbd;">Seegreen</i>
                    </a>
                </h1>
            </div>
            <div class="overlay overlay-contentpush">
                <div class="menu-top-background" style="background-image: url({{ asset('images/1.jpg') }})">
                    @if(Auth::check() || Auth::guard('merchant')->check() || Auth::guard('admin')->check())
                        <div class="col-xs-3" style="padding: 0px;">
                            @if(!empty(Auth::guard($data['userGuardRole'])->user()->profile_logo))
                                <div class="menu-top-user-image" style="background-image: url({{ asset(Auth::guard($data['userGuardRole'])->user()->profile_logo) }})"></div>
                            @else
                                <div class="menu-top-user-image" style="background-image: url({{ asset('images/50200009239445155973051638415_s.jpg') }})"></div>
                            @endif
                        </div>
                        <div class="col-xs-6">
                            <div class="menu-top-user-name" style="color: white !important;">
                                @if(empty(Auth::guard($data['userGuardRole'])->user()->f_name))
                                {{ Auth::guard($data['userGuardRole'])->user()->phone }} <br>
                                @else
                                {{ Auth::guard($data['userGuardRole'])->user()->f_name }} <br>
                                @endif
                                @php
                                if(Auth::guard($data['userGuardRole'])->user()->permission_lvl == '1'){
                                    $permission = isset($data['lang']['lang']['super_admin']) ? $data['lang']['lang']['super_admin'] : '高级管理员';
                                }elseif(Auth::guard($data['userGuardRole'])->user()->permission_lvl == '2'){
                                    $permission = isset($data['lang']['lang']['admin']) ? $data['lang']['lang']['admin'] : '初级管理员';
                                }else{
                                    $permission = "";
                                }

                                @endphp
                                <span style="font-size: 11px;">ID: {{ Auth::guard($data['userGuardRole'])->user()->code }}</span> <br>
                            </div>
                        </div>
                    @else
                        <div class="col-md-12 col-md-offset-4" style="margin-top: 40px;">
                            @if(!empty(Auth::guard($data['userGuardRole'])->user()->profile_logo))
                                <div class="menu-top-user-image" style="background-image: url({{ asset(Auth::guard($data['userGuardRole'])->user()->profile_logo) }})"></div>
                            @else
                                <div class="menu-top-user-image" style="background-image: url({{ asset('images/50200009239445155973051638415_s.jpg') }})"></div>
                            @endif
                        </div>
                    @endif

                    <div class="menu-top-my-profile">
                        @if(Auth::check() || Auth::guard('merchant')->check() || Auth::guard('admin')->check())
                            <div class="row">
                                <div class="col-xs-4" align="center">
                                    <a href="{{ route('profile') }}">
                                        <div class="form-group">
                                            <img src="{{ asset('images/19339625881548233621-512.png') }}" width="30px">
                                        </div>

                                        <div class="form-group" style="color: white !important;">
                                            {{ isset($data['lang']['lang']['my_accounts']) ? $data['lang']['lang']['my_accounts'] : '我的账户' }}
                                        </div>
                                    </a>
                                </div>
                                <div class="col-xs-4" align="center">
                                    <a href="{{ route('pending_order') }}">
                                        <div class="form-group">
                                            <img src="{{ asset('images/sidebar-my-order.png') }}" width="30px">
                                        </div>

                                        <div class="form-group" style="color: white !important;">
                                            {{ isset($data['lang']['lang']['my_orders']) ? $data['lang']['lang']['my_orders'] : '我的订单' }}
                                        </div>
                                    </a>
                                </div>
                                <div class="col-xs-4" align="center">
                                    <a href="{{ route('wallet') }}">
                                        <div class="form-group">
                                            <img src="{{ asset('images/sidebar-earning.png') }}" width="30px">
                                        </div>

                                        <div class="form-group" style="color: white !important;">
                                            {{ isset($data['lang']['lang']['earnings']) ? $data['lang']['lang']['earnings'] : '我的收益' }}
                                        </div>
                                    </a>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-4" align="center">
                                    <a href="{{ route('wish_list') }}">
                                        <div class="form-group">
                                            <img src="{{ asset('images/icon-ios7-heart-outline-512.png') }}" width="30px">
                                        </div>

                                        <div class="form-group" style="color: white !important;">
                                            {{ isset($data['lang']['lang']['favourite']) ? $data['lang']['lang']['favourite'] : '我的收藏' }}
                                        </div>
                                    </a>
                                </div>
                                @if(Auth::guard($data['userGuardRole'])->check() && Auth::guard('merchant')->check() || Auth::guard('admin')->check())
                                <div class="col-xs-4" align="center">
                                    <a href="{{ route('myqrcode') }}">
                                        <div class="form-group">
                                            <img src="{{ asset('images/qrcode.png') }}" width="30px">
                                        </div>

                                        <div class="form-group" style="color: white !important;">
                                            {{ isset($data['lang']['lang']['qrcode']) ? $data['lang']['lang']['qrcode'] : '我的二维码' }}
                                        </div>
                                    </a>
                                </div>
                                @endif
                                <div class="col-xs-4" align="center">
                                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <div class="form-group">
                                            <img src="{{ asset('images/free-exit-logout-icon-2857-thumb.png') }}" width="30px">
                                        </div>

                                        <div class="form-group" style="color: white !important;">
                                            {{ isset($data['lang']['lang']['logout']) ? $data['lang']['lang']['logout'] : '登出' }}
                                        </div>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    </a>
                                </div>
                            </div>
                        @else
                        <div class="col-xs-6" align="center">
                            <a href="{{ route('login') }}" class="login-register-button">
                                {{ isset($data['lang']['lang']['login']) ? $data['lang']['lang']['login'] : '登录' }}
                            </a>
                        </div>
                        <div class="col-xs-6" align="center">
                            <a href="{{ route('register') }}" class="login-register-button">
                                {{ isset($data['lang']['lang']['register']) ? $data['lang']['lang']['register'] : '注册' }}
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
                <button type="button" class="overlay-close"><i class="fa fa-times" aria-hidden="true"></i></button>

                <nav>
                    <ul>
                        <li>
                            <b>
                                {{ isset($data['lang']['lang']['menu']) ? $data['lang']['lang']['menu'] : '目录' }}
                            </b>
                        </li>
                        <li><a href="{{ route('home') }}" class="active">{{ isset($data['lang']['lang']['home']) ? $data['lang']['lang']['home'] : '首页' }}</a></li>
                        <li><a href="{{ route('about') }}">{{ isset($data['lang']['lang']['about_us']) ? $data['lang']['lang']['about_us'] : '关于我们' }}</a></li>
			<li><a href="{{ route('faqs') }}">常见问题</a></li>
                        <li><a href="{{ route('listing') }}">{{ isset($data['lang']['lang']['buy_now']) ? $data['lang']['lang']['buy_now'] : '立刻购买' }}</a></li>
                        <!-- <li>
                            <a href="https://api.whatsapp.com/send?phone=60168650888&text=您好! 我想了解更多关于因诗美的产品.&source=&data=" target="_blank">
                                {{ isset($data['lang']['lang']['contact_us']) ? $data['lang']['lang']['contact_us'] : '联系我们' }}
                            </a>
                        </li> -->
                        <hr>
                        <li>
                            <b>
                                {{ isset($data['lang']['lang']['language']) ? $data['lang']['lang']['language'] : '语言' }}
                            </b>
                        </li>
                        <li>
                            <select class="form-control global_language" name="global_language">
                                <option {{ isset($_COOKIE['global_language']) && $_COOKIE['global_language'] == '1' ? 'selected' : '' }} value="1">简体中文</option>
                                <option {{ isset($_COOKIE['global_language']) && $_COOKIE['global_language'] == '2' ? 'selected' : '' }} value="2">English</option>
                            </select>
                        </li>
                    </ul>
                </nav>
            </div>
            <div class="mobile-nav-button">
                <button id="trigger-overlay" type="button"><i class="fa fa-bars" aria-hidden="true"></i></button>
            </div>
            <!-- cart details -->
           
        </div>
    </div>
    <!-- //cart details -->
    <!-- search -->
    <div class="search_w3ls_agileinfo">
        <div class="cd-main-header">
            <ul class="cd-header-buttons">
                <li><a class="cd-search-trigger" href="#cd-search"> <span></span></a></li>
            </ul>
        </div>
        <div id="cd-search" class="cd-search">
            <form method="GET" action="{{ route('listing') }}">
                <input name="result" type="search" placeholder="搜索..">
            </form>
        </div>
    </div>
    <div class="cart-button">
        <a href="{{ route('cart') }}" class="top_shoe_cart" style="position: relative;">
            <i class="fa fa-cart-arrow-down" aria-hidden="true"></i>
            <span class="badge badge-cart" style="position: absolute; top: -10px; right: -10px; background-color: red;">{{ $data['totalCart'] }}</span>
        </a>

    </div>
    <!-- //search -->
    <div class="clearfix"></div>
    <!-- /banner_inner -->
    <div class="services-breadcrumb_w3ls_agileinfo">
        <div class="inner_breadcrumb_agileits_w3">

            <ul class="short">
                <li><a href="{{ route('home') }}">{{ isset($data['lang']['lang']['home']) ? $data['lang']['lang']['home'] : '首页' }}</a><i>|</i></li>
                @if(Request::segment(1) == 'Listing')
                <li>{{ isset($data['lang']['lang']['shopping']) ? $data['lang']['lang']['shopping'] : '购物' }}</li>
                @endif

                @if(Request::segment(1) == 'Details')
                <li><a href="{{ route('listing') }}">{{ isset($data['lang']['lang']['shopping']) ? $data['lang']['lang']['shopping'] : '购物' }}</a><i>|</i></li>
                <li>{{ isset($data['lang']['lang']['product_details']) ? $data['lang']['lang']['product_details'] : '物品信息' }}</li>
                @endif

                @if(Request::segment(1) == 'Cart')
                <li>{{ isset($data['lang']['lang']['shopping_cart']) ? $data['lang']['lang']['shopping_cart'] : '购物车' }}</li>
                @endif

                @if(Request::segment(1) == 'Checkout')
                <li><a href="{{ route('cart') }}">{{ isset($data['lang']['lang']['shopping_cart']) ? $data['lang']['lang']['shopping_cart'] : '购物车' }}</a><i>|</i></li>
                <li>{{ isset($data['lang']['lang']['checkout']) ? $data['lang']['lang']['checkout'] : '提交订单' }}</li>
                @endif

                @if(Request::segment(1) == 'About')
                <li>{{ isset($data['lang']['lang']['about_us']) ? $data['lang']['lang']['about_us'] : '关于我们' }}</li>
                @endif

                @if(Request::segment(1) == 'Faqs')
                <li>常见问题</li>
                @endif

                @if(Request::segment(1) == 'Contact')
                <li>{{ isset($data['lang']['lang']['contact_us']) ? $data['lang']['lang']['contact_us'] : '联系我们' }}</li>
                @endif

                @if(Request::segment(1) == 'Profile' && Request::segment(2) == '')
                <li>{{ isset($data['lang']['lang']['my_accounts']) ? $data['lang']['lang']['my_accounts'] : '我的账户' }}</li>
                @endif

                @if(Request::segment(1) == 'Profile' && Request::segment(2) == 'Edit')
                <li>
                    <a href="{{ route('profile') }}">{{ isset($data['lang']['lang']['my_accounts']) ? $data['lang']['lang']['my_accounts'] : '我的账户' }}</a>
                </li>
                <li>
                    <i>|</i>
                    {{ isset($data['lang']['lang']['edit_profile']) ? $data['lang']['lang']['edit_profile'] : '编辑个人信息' }}
                </li>
                @endif

                @if(Request::segment(1) == 'MyWallet')
                <li>
                    <a href="{{ route('profile') }}">{{ isset($data['lang']['lang']['my_accounts']) ? $data['lang']['lang']['my_accounts'] : '我的账户' }}</a>
                </li>
                <li>
                    <i>|</i>
                    {{ isset($data['lang']['lang']['earnings']) ? $data['lang']['lang']['earnings'] : '我的收益' }}
                </li>
                @endif

                @if(Request::segment(1) == 'AddressBook')
                <li>
                    {{ isset($data['lang']['lang']['address_book']) ? $data['lang']['lang']['address_book'] : '收货地址' }}
                    @if(Request::segment(2))
                    <i>|</i>
                    @endif
                </li>

                @endif

                @if(Request::segment(2) == 'create')
                <li>{{ isset($data['lang']['lang']['add_address']) ? $data['lang']['lang']['add_address'] : '添加新地址' }}</li>
                @endif

                @if(Request::segment(3) == 'edit')
                <li>{{ isset($data['lang']['lang']['edit_address']) ? $data['lang']['lang']['edit_address'] : '编辑地址' }}</li>
                @endif

                @if(Request::segment(1) == 'MyOrder')
                <li>
                    <a href="{{ route('profile') }}">
                        {{ isset($data['lang']['lang']['my_accounts']) ? $data['lang']['lang']['my_accounts'] : '我的账户' }}
                    </a>
                </li>
                <li>
                    <i>|</i>
                    {{ isset($data['lang']['lang']['my_orders']) ? $data['lang']['lang']['my_orders'] : '我的订单' }}
                </li>
                @endif

                @if(Request::segment(1) == 'MyWishList')
                <li>{{ isset($data['lang']['lang']['favourite']) ? $data['lang']['lang']['favourite'] : '我的收藏' }}</li>
                @endif

                @if(Request::segment(1) == 'ChangePassword')
                <li>{{ isset($data['lang']['lang']['edit_password']) ? $data['lang']['lang']['edit_password'] : '更换密码' }}</li>
                @endif

                @if(Request::segment(1) == 'OrderDetails')
                <li><a href="{{ route('order_list') }}">{{ isset($data['lang']['lang']['my_orders']) ? $data['lang']['lang']['my_orders'] : '我的订单' }}</a><i>|</i></li>
                <li>{{ isset($data['lang']['lang']['order_details']) ? $data['lang']['lang']['order_details'] : '订单信息' }}</li>
                @endif

                @if(Request::segment(1) == 'Material')
                <li>{{ isset($data['lang']['lang']['meterials']) ? $data['lang']['lang']['meterials'] : '素材' }}</li>
                @endif

                @if(Request::segment(1) == 'login')
                <li>{{ isset($data['lang']['lang']['login']) ? $data['lang']['lang']['login'] : '登录' }}</li>
                @endif

                @if(Request::segment(1) == 'register')
                <li>{{ isset($data['lang']['lang']['register']) ? $data['lang']['lang']['register'] : '注册' }}</li>
                @endif

                @if(Request::segment(1) == 'withdrawal')
                <li>
                    <a href="{{ route('wallet') }}">
                        {{ isset($data['lang']['lang']['earnings']) ? $data['lang']['lang']['earnings'] : '我的收益' }}
                    </a>
                </li>
                <li>
                    <i>|</i>
                    {{ isset($data['lang']['lang']['withdrawal']) ? $data['lang']['lang']['withdrawal'] : '提现' }}
                </li>
                @endif

                @if(Request::segment(1) == 'BankAccount')
                <li>
                    <a href="{{ route('withdrawal') }}">
                        {{ isset($data['lang']['lang']['withdrawal']) ? $data['lang']['lang']['withdrawal'] : '提现' }}
                    </a>
                </li>
                <li>
                    <i>|</i>
                    {{ isset($data['lang']['lang']['bank_account']) ? $data['lang']['lang']['bank_account'] : '银行户口' }}
                </li>
                @endif
                
                <!-- @if(Request::segment(2) && Request::segment(2) != 'create' && Request::segment(3) != 'edit' && Request::segment(1) != 'OrderDetails')
                <li>{{ Request::segment(2) }}</li>
                @endif -->

            </ul>
        </div>
    </div>
    <!-- //banner_inner -->
</div>
<div class="top-menu-bar">
    <div class="row">
        <div class="col-xs-3" align="center">
            <div class="top-menu-bar-box">
                <a href="{{ route('home') }}">
                    <i class="fa fa-home fa-2x gold-word"></i>
                    <br>
                    <span class="gold-word">{{ isset($data['lang']['lang']['home']) ? $data['lang']['lang']['home'] : '首页' }}</span>            
                </a>
            </div>
        </div>

        <div class="col-xs-3" align="center">
            <div class="top-menu-bar-box">
                <a href="{{ route('listing') }}">
                    <i class="fa fa-cubes fa-2x gold-word"></i>
                    <br>
                    <span class="gold-word">{{ isset($data['lang']['lang']['products']) ? $data['lang']['lang']['products'] : '产品' }}</span>
                </a>
            </div>
        </div>

        <div class="col-xs-3" align="center">
            <div class="top-menu-bar-box">
                <a href="{{ route('Material') }}" style="position: relative;">
                    <i class="fa fa-cube fa-2x gold-word" aria-hidden="true"></i>
                    <br>
                    <span class="gold-word">{{ isset($data['lang']['lang']['meterials']) ? $data['lang']['lang']['meterials'] : '素材' }}</span>
                    
                </a>
            </div>
        </div>

        <div class="col-xs-3" align="center">
            <div class="top-menu-bar-box">
                <a href="{{ route('profile') }}">
                    <i class="fa fa-user fa-2x gold-word"></i>
                    <br>
                    <span class="gold-word">{{ isset($data['lang']['lang']['accounts']) ? $data['lang']['lang']['accounts'] : '我的' }}</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endif

<div class="bottom-menu-bar">
    <div class="row">
        <div class="col-xs-3" align="center">
            <div class="top-menu-bar-box">
                <a href="{{ route('home') }}">
                    <i class="fa fa-home fa-2x gold-word"></i>
                    <br>
                    <span class="gold-word">{{ isset($data['lang']['lang']['home']) ? $data['lang']['lang']['home'] : '首页' }}</span>            
                </a>
            </div>
        </div>

        <div class="col-xs-3" align="center">
            <div class="top-menu-bar-box">
                <a href="{{ route('listing') }}">
                    <i class="fa fa-cubes fa-2x gold-word"></i>
                    <br>
                    <span class="gold-word">{{ isset($data['lang']['lang']['products']) ? $data['lang']['lang']['products'] : '产品' }}</span>
                </a>
            </div>
        </div>

        <div class="col-xs-3" align="center">
            <div class="top-menu-bar-box">
                <a href="{{ route('Material') }}" style="position: relative;">
                    <i class="fa fa-cube fa-2x gold-word" aria-hidden="true"></i>
                    <br>
                    <span class="gold-word">{{ isset($data['lang']['lang']['meterials']) ? $data['lang']['lang']['meterials'] : '素材' }}</span>
                    
                </a>
            </div>
        </div>

        <div class="col-xs-3" align="center">
            <div class="top-menu-bar-box">
                <a href="{{ route('profile') }}">
                    <i class="fa fa-user fa-2x gold-word"></i>
                    <br>
                    <span class="gold-word">{{ isset($data['lang']['lang']['accounts']) ? $data['lang']['lang']['accounts'] : '我的' }}</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endif