@if(Request::segment(1) != 'customer_invoice' && 
    Request::segment(1) != 'Menu' && 
    Request::segment(1) != 'merchant_login')
<div class="footer-one bg-footer set_button set_text footer_background">
    <div class="container div-footer">
      <div class="footer-one__body">
        <div class="row">
          <div class="col-lg-3 col-md-3 col-sm-12 col-12 text-center">
            <div class="form-group pb-2">
                <div class="footer-one__header__logo">
                  <a href="{{ route('home') }}">
                     @if(!empty($data['website_logo']))
                        <img src="{{ asset($data['website_logo']) }}" alt="Logo">
                     @endif
                  </a>
              </div>
            </div>
            <div class="form-group pb-5">
                <ul class="gap-10">
                    @if(!empty($data['web_setting']->facebook))
                    <li>
                        <a href="{{ $data['web_setting']->facebook }}" target="_blank" class="btn display-inline">
                           <img src="{{ asset('images/general/facebook.png') }}" class="social-media-icon padding-1-5" alt="Facebook Icon">
                        </a>
                    </li>
                    @endif
                    @if(!empty($data['web_setting']->tiktok))
                    <li>
                        <a href="{{ $data['web_setting']->tiktok }}" target="_blank" class="btn display-inline">
                           <img src="{{ asset('images/general/tiktok.png') }}" class="social-media-icon padding-1-5" alt="Tiktok Icon">
                        </a>
                    </li>
                    @endif
                    @if(!empty($data['web_setting']->instagram))
                    <li>
                        <a href="{{ $data['web_setting']->instagram }}" target="_blank" class="btn display-inline">
                           <img src="{{ asset('images/general/instagram.png') }}" class="social-media-icon" alt="Instagram Icon">
                        </a>
                    </li>
                    @endif
                    @if(!empty($data['web_setting']->youtube))
                    <li>
                        <a href="{{ $data['web_setting']->youtube }}" target="_blank" class="btn display-inline">
                           <img src="{{ asset('images/general/youtube.png') }}" class="social-media-icon" alt="YouTube Icon">
                        </a>
                    </li>
                    @endif
                    @if(!empty($data['web_setting']->google))
                    <li>
                        <a href="{{ $data['web_setting']->google }}" target="_blank" class="btn display-inline">
                           <img src="{{ asset('images/general/google.jpg') }}" class="social-media-icon" alt="Google Icon">
                        </a>
                    </li>
                    @endif
                    @if(!empty($data['web_setting']->book))
                    <li>
                        <a href="{{ $data['web_setting']->book }}" target="_blank" class="btn display-inline">
                           <img src="{{ asset('images/general/book.png') }}" class="social-media-icon" alt="Book Icon">
                        </a>
                    </li>
                    @endif
                    @if(!empty($data['web_setting']->twitter))
                    <li>
                        <a href="{{ $data['web_setting']->twitter }}" target="_blank" class="btn display-inline">
                           <img src="{{ asset('images/general/twitter.png') }}" class="social-media-icon" alt="Twitter Icon">
                        </a>
                    </li>
                    @endif
                </ul>
            </div>
          </div>
          <div class="col-lg-9 col-md-9 col-sm-12 col-12">
            <div class="footer__sections-wrapper d-justify-content-around">
              <div class="footer__section -links">
                <h5 class="footer-title footer_text">
                    {{ isset($data['lang']['lang']['explore']) ? $data['lang']['lang']['explore'] :'探索' }}
                </h5>
                <ul>
                  <li><a href="{{ route('home') }}" class="footer_text">{{ isset($data['lang']['lang']['home']) ? $data['lang']['lang']['home'] :'首页' }}</a></li>
                  <li><a href="{{ route('listing') }}" class="footer_text">{{ isset($data['lang']['lang']['shop']) ? $data['lang']['lang']['shop'] :'商店' }}</a></li>
                  <li><a href="{{ route('about') }}" class="footer_text">{{ isset($data['lang']['lang']['about']) ? $data['lang']['lang']['about'] :'关于我们' }}</a></li>
                  <li><a href="{{ route('blogs') }}" class="footer_text">{{ isset($data['lang']['lang']['blogs']) ? $data['lang']['lang']['blogs'] :'Blogs' }}</a></li>
                  <li><a href="{{ route('faqs') }}" class="footer_text">{{ isset($data['lang']['lang']['faqs']) ? $data['lang']['lang']['faqs'] :'Faqs' }}</a></li>
                </ul>
              </div>
              <div class="footer__section -links">
                <h5 class="footer-title footer_text">
                  {{ isset($data['lang']['lang']['customer_service']) ? $data['lang']['lang']['customer_service'] :'客户服务' }}
                </h5>
                <ul>
                  <li><a href="{{ route('Contact') }}" class="footer_text">{{ isset($data['lang']['lang']['contact_us']) ? $data['lang']['lang']['contact_us'] :'联系我们' }}</a></li>
                  <li><a href="{{ route('privacy_policy') }}" class="footer_text">{{ isset($data['lang']['lang']['privacy_policy']) ? $data['lang']['lang']['privacy_policy'] :'隐私政策' }}</a></li>
                  <li><a href="{{ route('return_policy') }}" class="footer_text">{{ isset($data['lang']['lang']['return_policy']) ? $data['lang']['lang']['return_policy'] :'退货/退款政策' }}</a></li>
                  <li><a href="{{ route('shipping_policy') }}" class="footer_text">{{ isset($data['lang']['lang']['shipping_policy']) ? $data['lang']['lang']['shipping_policy'] :'运输政策' }}</a></li>
                  <li><a href="{{ route('tnc') }}" class="footer_text">{{ isset($data['lang']['lang']['term_condition']) ? $data['lang']['lang']['term_condition'] :'规则与条例' }}</a></li>
                </ul>
              </div>
              <div class="footer__section -links">
                <h5 class="footer-title footer_text">
                    {{ isset($data['lang']['lang']['accounts']) ? $data['lang']['lang']['accounts'] :'账户' }}
                </h5>
                <ul>
                  <li><a href="{{ route('profile') }}" class="footer_text">{{ isset($data['lang']['lang']['my_accounts']) ? $data['lang']['lang']['my_accounts'] :'我的账户' }}</a></li>
                  <li><a href="{{ route('pending_shipping') }}" class="footer_text">{{ isset($data['lang']['lang']['order_history']) ? $data['lang']['lang']['order_history'] :'订单记录' }}</a></li>
                  <li><a href="{{ route('wish_list') }}" class="footer_text">{{ isset($data['lang']['lang']['wish_history']) ? $data['lang']['lang']['wish_history'] :'愿望清单' }}</a></li>
                </ul>
              </div>
              <div class="footer__section -links">
                <h5 class="footer-title footer_text">
                    {{ isset($data['lang']['lang']['payment']) ? $data['lang']['lang']['payment'] :'Payment' }}
                </h5>
                <ul class="payment-link payment-link--sm">
                  <li><img src="{{ asset('images/general/Mastercard.png') }}" alt="Mastercard" width="60" height="30"></li>
                  <li><img src="{{ asset('images/general/Visa.jpg') }}" alt="Visa" width="60" height="30" style="border-radius: 5%;"></li>
                  <li><img src="{{ asset('images/general/FPX.jpg') }}" alt="FPX" width="60" height="30" style="border-radius: 5%;"></li>
                  <li><img src="{{ asset('images/general/TouchNGo.png') }}" alt="TouchNGo" width="35" height="30"></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="footer-one__footer bg-copyright set_button footer_trademark_background">
      <div class="container">
        <div class="footer-one__footer__wrapper d-flex justify-content-center">
          <p class="footer_trademark_text">{{ isset($data['lang']['lang']['copyright']) ? $data['lang']['lang']['copyright'] :'Copyright' }} ©
              <script>
                  document.write(new Date().getFullYear());
              </script>
              {{ $data['website_name'] }} {{ !empty($data['company_registration_no']) ? $data['company_registration_no'] : '' }} | {{ isset($data['lang']['lang']['powered_by']) ? $data['lang']['lang']['powered_by'] :'Powered by' }} <a href="https://vesson.my" target="_blank" class="footer_trademark_text text-decoration-unset">Vesson.my</a> {{ isset($data['lang']['lang']['hidden_powered_by']) ? $data['lang']['lang']['hidden_powered_by'] :'' }}
          </p>
        </div>
      </div>
    </div>
  </div>
  <div class="bottom-menu-bar">
      <div class="row justify-content-center">
          <div class="col" align="center">
              <div class="top-menu-bar-box">
                  <a href="{{ route('home') }}" {!! (Request::segment(1) == '') ? 'style="color: #ae3683 !important;"' : '' !!}>
                     <i class="far fa-home fa-2x"></i>
                      <br>
                      <span class="">{{ isset($data['lang']['lang']['home']) ? $data['lang']['lang']['home'] :'Home' }}</span>            
                  </a>
              </div>
          </div>

          <div class="col" align="center">
              <div class="top-menu-bar-box">
                  <a href="{{ route('listing') }}" {!! (Request::segment(1) == 'ECommerce') || (Request::segment(1) == 'Details') ? 'style="color: #ae3683 !important;"' : '' !!}>
                      <i class="far fa-cube fa-2x"></i>
                      <br>
                      <span class="">{{ isset($data['lang']['lang']['shop']) ? $data['lang']['lang']['shop'] :'Shop' }}</span>
                  </a>
              </div>
          </div>

          <div class="col" align="center">
              <!-- <div class="top-menu-bar-box">
                  <a href="{{ route('checkout') }}" {!! (Request::segment(1) == 'Checkout') ? 'style="color: #ae3683 !important;"' : '' !!}>
                      <i class="far fa-shopping-cart fa-2x" aria-hidden="true"></i>
                      <br>
                      <span class="">{{ isset($data['lang']['lang']['cart']) ? $data['lang']['lang']['cart'] :'Cart' }}</span>
                  </a>
              </div> -->
            <div class="top-menu-bar-box" style="position: relative; display: inline-block;">
                <a class="menu-icon -cart top-cart-btn" href="{{ route('checkout') }}" style="position: relative; display: inline-block;{{ (Request::segment(1) == 'Checkout') ? 'color: #ae3683 !important;' : '' }}">
                    <i class="far fa-shopping-cart fa-2x" aria-hidden="true"></i>
                    <span class="cart__quantity" 
                          style="position: absolute; top: -8px; right: -8px; background-color: #dc3545; color: white; padding: 2px 6px; font-size: 0.75rem; font-weight: bold; border-radius: 50%; line-height: 1; min-width: 18px; text-align: center;">
                        {{ !empty($data['totalCart']) ? $data['totalCart'] : 0 }}
                    </span>
                </a>
                <br>
                <span style="{{ (Request::segment(1) == 'Checkout') ? 'color: #ae3683 !important;' : '' }}">{{ isset($data['lang']['lang']['cart']) ? $data['lang']['lang']['cart'] :'Cart' }}</span>
            </div>
          </div>

          <div class="col" align="center">
                 <!-- <i class="far fa-shopping-bag"></i> -->
              <div class="top-menu-bar-box" style="position: relative; display: inline-block;">
                <a class="menu-icon -cart top-cart-mall-btn" href="{{ route('checkout_mall') }}" style="position: relative; display: inline-block;{{ (Request::segment(1) == 'CheckoutMall') ? 'color: #ae3683 !important;' : '' }}">
                    <i class="far fa-shopping-bag fa-2x" aria-hidden="true"></i>
                    <span class="mall_cart__quantity" 
                          style="position: absolute; top: -8px; right: -8px; background-color: #dc3545; color: white; padding: 2px 6px; font-size: 0.75rem; font-weight: bold; border-radius: 50%; line-height: 1; min-width: 18px; text-align: center;">
                        {{ !empty($data['totalCartMall']) ? $data['totalCartMall'] : 0 }}
                    </span>
                </a>
                <br>
                <span style="white-space:nowrap;{{ (Request::segment(1) == 'CheckoutMall') ? 'color: #ae3683 !important;' : '' }}">{{ isset($data['lang']['lang']['point_cart']) ? $data['lang']['lang']['point_cart'] :'Point Cart' }}</span>
            </div>  
          </div>

          <div class="col" align="center">
              <div class="top-menu-bar-box">
                  <a href="{{ route('profile') }}" {!! (Request::segment(1) == 'Profile') ? 'style="color: #ae3683 !important;"' : '' !!}>
                      <i class="far fa-user fa-2x"></i>
                      <br>
                      <span class="">{{ isset($data['lang']['lang']['profile']) ? $data['lang']['lang']['profile'] :'Profile' }}</span>
                  </a>
              </div>
          </div>
      </div>
  </div>
@endif
