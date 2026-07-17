<!-- Humberger Begin -->
@if(Request::segment(1) == 'ECommerce' || Request::segment(1) == 'Details' || Request::segment(1) == 'Cart' || Request::segment(1) == '' || Request::segment(1) == 'Checkout' || Request::segment(1) == 'login' || Request::segment(1) == 'About' || Request::segment(1) == 'Contact' || Request::segment(1) == 'register' || Request::segment(1) == 'merchant_register' || Request::segment(1) == 'faqs' || Request::segment(1) == 'verify_success' || Request::segment(1) == 'Blog' || 
Request::segment(1) == 'BlogDetail' || Request::segment(1) == 'TnC' || Request::segment(1) == 'ReturnPolicy' ||
Request::segment(1) == 'PrivacyPolicy' || Request::segment(1) == 'company_register' || Request::segment(1) == 'Material' ||
Request::segment(1) == 'PointMall' ||
Request::segment(1) == 'MallDetails' ||
Request::segment(1) == 'CheckoutMall' ||
Request::segment(1) == 'Terms' ||
Request::segment(1) == 'ShippingPolicy' ||
Request::segment(1) == 'OurStory' ||
Request::segment(1) == 'JoinUs' ||
Request::segment(1) == 'Commitments' ||
Request::segment(1) == 'OurStoryThinking' ||
Request::segment(1) == 'OurStoryMain' ||
Request::segment(1) == 'Award' ||
Request::segment(1) == 'Feedback' ||
Request::segment(1) == 'OurLatest' ||
Request::segment(1) == 'OurLatestDetail' ||
Request::segment(1) == 'Promotion_Listing' ||
Request::segment(1) == 'Promotion_Details' ||
Request::segment(1) == 'merchant_login' ||
Request::segment(1) == 'Quiz' ||
Request::segment(1) == 'HealthBlogDetail' ||
Request::segment(1) == 'quiz_result' ||
Request::segment(1) == 'ForgetPassword')

<div class="header -two">
    @if(!empty($data['website_messages']))
        <div class="loading-message">
            <img src="{{ asset('images/loading/09b24e31234507.564a1d23c07b4.gif') }}" class="longer-25"/>
        </div>
        <div class="hdr-topline js-hdr-top header_announcement_background">
            <div class="container">
                <div class="row flex-nowrap align-items-center">
                    <div class="col hdr-topline-center">
                        <div class="custom-text js-custom-text-carousel word-13">
                            @foreach($data['website_messages'] as $message)
                                <div class="custom-text-item display-inline-block header_announcement_text">
                                    @if(isset($_COOKIE['global_language']) && !empty($_COOKIE['global_language']))
                                        @if($_COOKIE['global_language'] == '1')
                                            {{ !empty($message->message_cn) ? $message->message_cn : '暂无华文翻译' }}
                                        @else
                                            {{ !empty($message->message) ? $message->message : '' }}
                                        @endif
                                    @else
                                        {{ !empty($message->message) ? $message->message : '' }}
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="hide-header-icon" align="center" style=" padding: 10px 0px;">
                        <select class="global_language" name="global_language" style="padding: 0.375rem 0.75rem; width: auto; -webkit-appearance: auto; z-index: 1000; border: 1px solid" onchange="changeLanguage(value);">
                            <option>{{ isset($data['lang']['lang']['language']) ? $data['lang']['lang']['language'] :'语言'}}</option>
                            <option value="1">{{ isset($data['lang']['lang']['chinese']) ? $data['lang']['lang']['chinese'] :'中文'}}</option>
                            <option value="2">{{ isset($data['lang']['lang']['english']) ? $data['lang']['lang']['english'] :'英文'}}</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    @endif
    
        <div class="menu -style-2 header_background">
            
            <div class="container">
                 <div class="menu__wrapper">
                    <div class="menu-functions ">
                        <a class="menu__wrapper__logo" href="{{ route('home') }}">
                            @if(!empty($data['website_logo']))
                                <img src="{{ asset($data['website_logo']) }}" class="h-70" alt="Logo"/>
                            @endif
                        </a>
                    </div>
                    <div class="navigator">
                        <ul class="navigator_part -left">
                            <li>
                                <a href="{{ route('home') }}" class="header_text">
                                    {{ isset($data['lang']['lang']['home']) ? $data['lang']['lang']['home'] :'首页' }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('listing') }}" class="header_text">
                                    {{ isset($data['lang']['lang']['shop']) ? $data['lang']['lang']['shop'] :'商店' }}
                                </a>
                            </li>
                            @if(Auth::guard('agent')->check() || Auth::guard('web')->check() || Auth::guard('admin')->check())
                            <li>
                                <a href="{{ route('PointMall') }}" class="header_text">
                                    {{ isset($data['lang']['lang']['point_mall']) ? $data['lang']['lang']['point_mall'] :'点数商城' }}
                                </a>
                            </li>
                            @endif

                            {{-- About --}}
                            <li class="dropdown">
                                <a href="{{ route('about') }}" class="header_text">
                                    {{ isset($data['lang']['lang']['about']) ? $data['lang']['lang']['about'] :'关于我们' }}<i class="fa fa-caret-down pl-2"></i>
                                </a>
                                <ul class="dropdown-menu" style="width: 98px;">
                                    <li>
                                        <a href="{{ route('blogs') }}"class="header_text">
                                            {{ isset($data['lang']['lang']['blogs']) ? $data['lang']['lang']['blogs'] :'Blogs' }}
                                        </a>
                                    </li>
                                    <div class="bb-2">

                                    </div>
                                    <li>
                                        <a href="{{ route('faqs') }}" class="header_text">
                                            {{ isset($data['lang']['lang']['faqs']) ? $data['lang']['lang']['faqs'] :'Faqs' }}
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li>
                                <a href="{{ route('Contact') }}" class="header_text">
                                    {{ isset($data['lang']['lang']['contact_us']) ? $data['lang']['lang']['contact_us'] :'联系我们' }}
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="menu-functions">
                        <a class="menu-icon -search -mobile" href="#">
                            <img src="{{ asset('frontend/assets/images/header/search-icon.png') }}" alt="Search icon"/>
                        </a>
                        <div class="search-box">
                            <form method="GET" action="{{ route('listing') }}">
                                <input type="text" placeholder="{{ isset($data['lang']['lang']['what_are_you_looking_for']) ? $data['lang']['lang']['what_are_you_looking_for'] :'What are you looking for' }}?" name="result"/>
                                <button>
                                    <img src="{{ asset('frontend/assets/images/header/search-icon.png') }}" alt="Search icon"/>
                                </button>
                            </form>
                        </div>
                        <div class="menu-cart hide-header-icon">
                            <a class="menu-icon -cart top-cart-btn" href="{{ route('checkout') }}">
                                <img src="{{ asset('images/general/icon_cart.png') }}" width="33px" alt="Cart icon">
                                <span class="cart__quantity badge badge-pill badge-danger" style="top: -5px;">
                                    {{ !empty($data['totalCart']) ? $data['totalCart'] : 0 }}
                                </span>
                            </a>
                        </div>
                        @if(Auth::guard('agent')->check() || Auth::guard('web')->check() || Auth::guard('admin')->check())
                        <div class="menu-cart hide-header-icon">
                            <a class="menu-icon -cart top-cart-mall-btn" href="{{ route('checkout_mall') }}">
                                <img src="{{ asset('images/general/icon_mall.png') }}" width="25px" alt="Mall icon">
                                <span class="mall_cart__quantity badge badge-pill badge-danger">
                                    {{ !empty($data['totalCartMall']) ? $data['totalCartMall'] : 0 }}
                                </span>
                            </a>
                        </div>
                        @endif
                        <!-- <div class="hide-header-icon" align="center" style=" padding: 10px 0px;">
                        <select class="global_language" name="global_language" style="padding: 0.375rem 0.75rem; width: auto; -webkit-appearance: auto; z-index: 1000; border: 1px solid" onchange="changeLanguage(value);">
                            <option>{{ isset($data['lang']['lang']['language']) ? $data['lang']['lang']['language'] :'语言'}}</option>
                            <option value="1">{{ isset($data['lang']['lang']['chinese']) ? $data['lang']['lang']['chinese'] :'中文'}}</option>
                            <option value="2">{{ isset($data['lang']['lang']['english']) ? $data['lang']['lang']['english'] :'英文'}}</option>
                        </select>
                    </div> -->
                        
                        <div class="menu-cart show-language-icon" style="position: relative;display:none">
                            @if(Auth::guard('agent')->check() || Auth::guard('web')->check() || Auth::guard('admin')->check())
                                <a class="menu-icon -cart language-header-btn" href="#">
                                    <img src="{{ asset('images/general/language_icon.png') }}" width="25px" alt="Language Icon">
                                </a>
                                <div style="position: absolute;
                                            top: 35px;
                                            right: 0px;
                                            box-shadow: 0px 0px 6px 0 #ddd;
                                            color: #fff;
                                            font-size: 13px;
                                            background-color: #fff;
                                            z-index: 9999;
                                            width: 200px;
                                            display: none;" class="header-language-lists">
                                <div class="" align="center" style="border-bottom: 1px solid #eee; padding: 10px 0px;">
                                    <a href="#" onclick="changeLanguage(1);">
                                        {{ isset($data['lang']['lang']['chinese']) ? $data['lang']['lang']['chinese'] :'中文'}}
                                    </a>
                                </div>

                                <div class="" align="center" style="border-bottom: 1px solid #eee; padding: 10px 0px;">
                                    <a href="#" onclick="changeLanguage(2);">
                                        {{ isset($data['lang']['lang']['english']) ? $data['lang']['lang']['english'] :'英文'}}
                                    </a>
                                </div>
                            </div>
                        @else
                            <a class="menu-icon -cart top-profile-btn" href="#">
                                <img src="{{ asset('images/general/icon_user.png') }}" width="33px" alt="User Icon">
                            </a>
                            <div style="position: absolute;
                                        top: 35px;
                                        right: 25px;
                                        box-shadow: 0px 0px 6px 0 #ddd;
                                        color: #fff;
                                        font-size: 13px;
                                        background-color: #fff;
                                        z-index: 9999;
                                        width: 200px;
                                        display: none;" class="right-top-items">
                                <div class="" align="center" style="border-bottom: 1px solid #eee; padding: 10px 0px;">
                                    <a href="{{ route('profile') }}">
                                        {{ isset($data['lang']['lang']['login']) ? $data['lang']['lang']['login'] :'Login' }}
                                    </a>
                                </div>
                                <div class="" align="center" style="border-bottom: 1px solid #eee; padding: 10px 0px;">
                                    <a href="{{ route('register') }}">
                                        {{ isset($data['lang']['lang']['register']) ? $data['lang']['lang']['register'] :'Register' }}
                                    </a>
                                </div>
                                <div class="" align="center" style="border-bottom: 1px solid #eee; padding: 10px 0px;">
                                    <a href="{{ route('merchant_register') }}">
                                        {{ isset($data['lang']['lang']['register_agent']) ? $data['lang']['lang']['register_agent'] :'Register Agent' }}
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>

                        <div class="menu-cart" style="position: relative;">
                            @if(Auth::guard('agent')->check() || Auth::guard('web')->check() || Auth::guard('admin')->check())
                                <a class="menu-icon -cart top-profile-btn" href="#">
                                    <img src="{{ asset('images/general/icon_user.png') }}" width="33px" alt="User Icon">
                                </a>
                                <div style="position: absolute;
                                            top: 35px;
                                            right: 25px;
                                            box-shadow: 0px 0px 6px 0 #ddd;
                                            color: #fff;
                                            font-size: 13px;
                                            background-color: #fff;
                                            z-index: 9999;
                                            width: 200px;
                                            display: none;" class="right-top-items">
                                <div class="" align="center" style="border-bottom: 1px solid #eee; padding: 10px 0px;">
                                    {{ Auth::guard($data['userGuardRole'])->user()->f_name }} - {{ Auth::guard($data['userGuardRole'])->user()->code }}
                                </div>
                                <div class="" align="center" style="border-bottom: 1px solid #eee; padding: 10px 0px;">
                                    <a href="{{ route('profile') }}">
                                        {{ isset($data['lang']['lang']['my_accounts']) ? $data['lang']['lang']['my_accounts'] :'我的账户' }}
                                    </a>
                                </div>
                                <div class="" align="center" style="border-bottom: 1px solid #eee; padding: 10px 0px;">
                                    <a href="#" onclick="event.preventDefault(); $('.loading-gif').show(); document.getElementById('logout-form').submit();">
                                        {{ isset($data['lang']['lang']['logout']) ? $data['lang']['lang']['logout'] :'登出'}}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </div>
                        @else
                            <a class="menu-icon -cart top-profile-btn" href="#">
                                <img src="{{ asset('images/general/icon_user.png') }}" width="33px" alt="User Icon">
                            </a>
                            <div style="position: absolute;
                                        top: 35px;
                                        right: 25px;
                                        box-shadow: 0px 0px 6px 0 #ddd;
                                        color: #fff;
                                        font-size: 13px;
                                        background-color: #fff;
                                        z-index: 9999;
                                        width: 200px;
                                        display: none;" class="right-top-items">
                                <!-- <div class="" align="center" style="padding: 10px 0px;">
                                    <select class="global_language" name="global_language" style="padding: 0.375rem 0.75rem; width: auto; -webkit-appearance: auto; z-index: 1000; border: 1px solid" onchange="changeLanguage(value);">
                                        <option>{{ isset($data['lang']['lang']['language']) ? $data['lang']['lang']['language'] :'语言'}}</option>
                                        <option value="1">{{ isset($data['lang']['lang']['chinese']) ? $data['lang']['lang']['chinese'] :'中文'}}</option>
                                        <option value="2">{{ isset($data['lang']['lang']['english']) ? $data['lang']['lang']['english'] :'英文'}}</option>
                                    </select>
                                </div> -->
                                <div class="" align="center" style="border-bottom: 1px solid #eee; padding: 10px 0px;">
                                    <a href="{{ route('profile') }}">
                                        {{ isset($data['lang']['lang']['login']) ? $data['lang']['lang']['login'] :'Login' }}
                                    </a>
                                </div>
                                <div class="" align="center" style="border-bottom: 1px solid #eee; padding: 10px 0px;">
                                    <a href="{{ route('register') }}">
                                        {{ isset($data['lang']['lang']['register']) ? $data['lang']['lang']['register'] :'Register' }}
                                    </a>
                                </div>
                                <div class="" align="center" style="border-bottom: 1px solid #eee; padding: 10px 0px;">
                                    <a href="{{ route('merchant_register') }}">
                                        {{ isset($data['lang']['lang']['register_agent']) ? $data['lang']['lang']['register_agent'] :'Register Agent' }}
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                    <a class="menu-icon -navbar" href="#">
                        <div class="bar"></div>
                        <div class="bar"></div>
                        <div class="bar"></div>
                    </a>
                </div>
            </div>
        </div>

    </div>
    </div>
    
    <div class="drawer drawer-right slide" id="mobile-menu-drawer" tabindex="-1" role="dialog" aria-labelledby="drawer-demo-title" aria-hidden="true">
        <div class="drawer-content drawer-content-scrollable" role="document">
          <div class="drawer-body">
            <div class="cart-sidebar">
              <div class="cart-items__wrapper">
                <div class="navigation-sidebar">
                  <div class="search-box">
                    <form method="get" action="{{ route('listing') }}">
                        <input type="text" name="result" placeholder="{{ isset($data['lang']['lang']['what_are_you_looking_for']) ? $data['lang']['lang']['what_are_you_looking_for'] :'What are you looking for' }}?"/>
                        <button>
                            <img src="{{ asset('frontend/assets/images/header/search-icon.png') }}" alt="Search icon"/>
                        </button>
                    </form>
                  </div>
                  <div class="navigator-mobile">
                    <ul>
                        <li class="relative">
                            <a href="{{ route('home') }}">
                                {{ isset($data['lang']['lang']['home']) ? $data['lang']['lang']['home'] :'首页' }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('listing') }}">
                                {{ isset($data['lang']['lang']['shop']) ? $data['lang']['lang']['shop'] :'商店' }}
                            </a>
                        </li>
                        @if(Auth::guard('agent')->check() || Auth::guard('web')->check() || Auth::guard('admin')->check())
                        <li>
                            <a href="{{ route('PointMall') }}">
                                {{ isset($data['lang']['lang']['point_mall']) ? $data['lang']['lang']['point_mall'] :'点数商城' }}
                            </a>
                        </li>
                        @endif

                        {{-- About --}}
                        <div class="form-group pt-4 header-about-us">
                            <h6 class="fw-600">
                                {{ isset($data['lang']['lang']['about_2']) ? $data['lang']['lang']['about_2'] :'关于' }}
                            </h6>
                            <hr class="bt-grey">
                            <ul class="list-style-type-none">
                                <li>
                                    <a href="{{ route('about') }}">
                                        {{ isset($data['lang']['lang']['about']) ? $data['lang']['lang']['about'] :'关于我们' }}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('blogs') }}">
                                        {{ isset($data['lang']['lang']['blogs']) ? $data['lang']['lang']['blogs'] :'Blogs' }}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('faqs') }}">
                                        {{ isset($data['lang']['lang']['faqs']) ? $data['lang']['lang']['faqs'] :'Faqs' }}
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <li>
                            <a href="{{ route('Contact') }}">
                                {{ isset($data['lang']['lang']['contact_us']) ? $data['lang']['lang']['contact_us'] :'联系我们' }}
                            </a>
                        </li>
                    </ul>
                  </div>
                    <div class="social-icons">
                      <ul>
                        @if(!empty($data['web_setting']->facebook))
                        <li>
                            <a href="{{ $data['web_setting']->facebook }}" style="'color: undefined'" target="_blank">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                        </li>
                        @endif
                        @if(!empty($data['web_setting']->tiktok))
                        <li>
                            <a href="{{ $data['web_setting']->tiktok }}" style="'color: undefined'" target="_blank">
                                <i class="fab fa-tiktok"> </i>
                            </a>
                        </li>
                        @endif
                        @if(!empty($data['web_setting']->instagram))
                        <li>
                            <a href="{{ $data['web_setting']->instagram }}" style="'color: undefined'" target="_blank">
                                <i class="fab fa-instagram"> </i>
                            </a>
                        </li>
                        @endif
                        @if(!empty($data['web_setting']->youtube))
                        <li>
                            <a href="{{ $data['web_setting']->youtube }}" style="'color: undefined'" target="_blank">
                                <i class="fab fa-youtube"></i>
                            </a>
                        </li>
                        @endif
                        @if(!empty($data['web_setting']->google))
                        <li>
                            <a href="{{ $data['web_setting']->google }}" style="'color: undefined'" target="_blank">
                                <i class="fab fa-google"></i>
                            </a>
                        </li>
                        @endif
                        @if(!empty($data['web_setting']->book))
                        <li>
                            <a href="{{ $data['web_setting']->book }}" style="'color: undefined'" target="_blank">
                                <i class="fa fa-book"></i>
                            </a>
                        </li>
                        @endif
                        @if(!empty($data['web_setting']->twitter))
                        <li>
                            <a href="{{ $data['web_setting']->twitter }}" style="'color: undefined'" target="_blank">
                                <i class="fab fa-twitter"></i>
                            </a>
                        </li>
                        @endif
                      </ul>
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    @endif