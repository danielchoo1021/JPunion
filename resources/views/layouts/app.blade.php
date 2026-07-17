@php
if($_SERVER['REMOTE_ADDR'] != '127.0.0.1'){
    if(Request::secure()){
    }else{
    $url = "https://". $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
    header("Location: $url");
    exit;   
    }    
}

if($data['explo_check'][0] == 'admindemo'){
    $url = "https://admindemo.vesson.my/admin_login";
    header("Location: $url");
    exit;
}
@endphp
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Security-Policy" 
         content="default-src *; 
                  style-src * 'self' 'unsafe-inline' 'unsafe-eval'; 
                  script-src * 'self' 'unsafe-inline' 'unsafe-eval';">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    @if(!empty($data['web_setting']->fav_icon))
        <link rel="shortcut icon" href="{{ asset($data['web_setting']->fav_icon) }}">
    @else
        <link rel="shortcut icon" href="{{ asset($data['web_setting']->website_logo) }}">
    @endif
    <meta property="og:title" content="{{ (!empty($data['web_setting']->website_name)) ? $data['web_setting']->website_name : 'VESSON' }}">
    <meta property="og:description" content="{{ (!empty($data['web_setting']->website_name)) ? $data['web_setting']->website_name : 'VESSON' }}">
    <meta property="og:image" content="{{ asset($data['web_setting']->website_logo) }}">

    @if(!empty($data['web_setting']->website_logo))
        <link rel="apple-touch-icon-precomposed" sizes="57x57" href="{{ asset($data['web_setting']->website_logo) }}" />
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{ asset($data['web_setting']->website_logo) }}" />
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{ asset($data['web_setting']->website_logo) }}" />
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{ asset($data['web_setting']->website_logo) }}" />
    @else
        <link rel="apple-touch-icon-precomposed" sizes="57x57" href="apple-icon-57x57-precomposed.png" />
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="apple-icon-72x72-precomposed.png" />
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="apple-icon-114x114-precomposed.png" />
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="apple-icon-144x144-precomposed.png" />
    @endif

    <title>{{ (!empty($data['web_setting']->website_name)) ? $data['web_setting']->website_name : 'VESSON' }}</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/bootstrap.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/bootstrap.css') }}"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.0.0/animate.min.css"/>
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/slick.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/fontawesome.css') }}"/>
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/bootstrap-drawer.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/style.css') }}"/>
    <link rel="stylesheet" href="{{ asset('frontend/css/owl.carousel.min.css') }}" type="text/css">

    <link rel="stylesheet" href="{{ asset('frontend/thumbnail-zoom/css/main.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="https://kenwheeler.github.io/slick/slick/slick-theme.css"/>
    
    <meta name="google" content="notranslate">
    @toastr_css
</head>
@if (!empty($data['button_colour']))
    <style type="text/css">
        .set_button {
            background: #{{ $data['button_colour'] }} !important;
        }
    </style>
@endif

@if (!empty($data['text_colour']))
    <style type="text/css">
        .set_text {
            color: #{{ $data['text_colour'] }} !important;
        }
    </style>
@endif

@if (!empty($data['hover_colour']))
    <style type="text/css">
        .set_text:hover {
            color: #{{ $data['hover_colour'] }} !important;
        }

        .set_button:hover,
        .set_button:focus {
            border-color: #{{ $data['hover_colour'] }} !important;
        }
    </style>
@endif

@if (!empty($data['header_announcement_text_colour']))
    <style type="text/css">
        .header_announcement_text {
            color: #{{ $data['header_announcement_text_colour'] }} !important;
        }
    </style>
@endif

@if (!empty($data['header_announcement_background_colour']))
    <style type="text/css">
        .header_announcement_background {
            background: #{{ $data['header_announcement_background_colour'] }} !important;
        }
    </style>
@endif

@if (!empty($data['header_background_colour']))
    <style type="text/css">
        .menu.-style-2.header_background {
            background: #{{ $data['header_background_colour'] }} !important;
        }
    </style>
@endif

@if (!empty($data['header_text_colour']))
    <style type="text/css">
        .header_text {
            color: #{{ $data['header_text_colour'] }} !important;
        }
    </style>
@endif

@if (!empty($data['header_text_hover_colour']))
    <style type="text/css">
        .header_text:hover {
            color: #{{ $data['header_text_hover_colour'] }} !important;
        }
    </style>
@endif

@if (!empty($data['footer_trademark_text_colour']))
    <style type="text/css">
        .footer_trademark_text {
            color: #{{ $data['footer_trademark_text_colour'] }} !important;
        }
    </style>
@endif

@if (!empty($data['footer_trademark_background_colour']))
    <style type="text/css">
        .footer_trademark_background {
            background: #{{ $data['footer_trademark_background_colour'] }} !important;
        }
    </style>
@endif

@if (!empty($data['footer_background_colour']))
    <style type="text/css">
        .footer_background {
            background: #{{ $data['footer_background_colour'] }} !important;
        }
    </style>
@endif

@if (!empty($data['footer_text_colour']))
    <style type="text/css">
        .footer_text {
            color: #{{ $data['footer_text_colour'] }} !important;
        }
    </style>
@endif

@if (!empty($data['footer_text_hover_colour']))
    <style type="text/css">
        .footer_text:hover {
            color: #{{ $data['footer_text_hover_colour'] }} !important;
        }
    </style>
@endif

<style type="text/css">
/* @font-face {
    font-family: heiti;
    src: url({{ asset('font/heiti/heiti.ttf') }});
} */

body {
    font-size: 13px;
    /* font-family: <?php echo (isset($_COOKIE['global_language']) && $_COOKIE['global_language'] == 1) ? 'heiti' : 'unset'; ?>; */
}

.nav-pills .nav-link.active {
    background-color: #{{ $data['button_colour'] }} !important;
}
    
.text-editor-image img{
    max-width: 100%;
    height: auto !important;
}

.four-image{
    /*background-image: url(https://webdevtrick.com/wp-content/uploads/1st.jpg);*/
    background-repeat: no-repeat;
    background-size: cover;
    background-position: center;
    width: 130px;
    height: 130px;
    border-radius: 100%;
}

.slick-prev:before, .slick-next:before{
    color: #000;
}

.footer-center{
    width: 50%;
}

.introduction-four__item__content h3{
    font-size: 2.125em;
    padding-right: 40px;
}

.introduction-eight__content__item__image img{
    height: auto !important;
}

.product-price--main{
    color: #92d2d9;
}

.menu.-style-2 .menu__wrapper .navigator .menu__wrapper__logo, .menu.-style-6 .menu__wrapper .navigator .menu__wrapper__logo{
    display: block;
    margin-bottom: 17px;
}

.menu.-style-2 .menu__wrapper .navigator .menu__wrapper__logo img, .menu.-style-6 .menu__wrapper .navigator .menu__wrapper__logo img{
    height: 3.5em !important;
    width: 100% !important;
}

.category-one .category-card__background img{
    border-radius: 25px;
}

.slick-slide{
    height: auto;
}

.social-icons.-border ul > li a, .footer-one__header{
    border-color: #fff;
}

.footer-one__body .footer__section.-links ul li a{
    color: #000;
}

.introduction-four__item__image img{
    height: auto;
}

.introduction-four__item.-style-2 .introduction-four__item__content, .introduction-four__item.menu.-style-6 .introduction-four__item__content{
    bottom: 50px;
}

.tree {
    margin: auto !important;
    padding: 0 0 0 9px !important;
    overflow-x: hidden;
    overflow-y: auto;
}

.tree:before {
    border: none;
}

.tree:before {
    display: inline-block;
    content: "";
    position: absolute;
    top: -20px;
    bottom: 16px;
    left: 0;
    z-index: 1;
    /*border: 1px dotted #67B2DD;*/
    border-width: 0 0 0 1px;
}

.tree ul:before {
    top: -0.5em;
}

.tree ul:before, .tree code:before, .tree span:before {
    outline: solid 1px #666;
    content: "";
    height: 0.5em;
    left: 50%;
    position: absolute;
}

.tree li:last-child:before {
    right: 50%;
}

.tree li:first-child:before {
    left: 50%;
}

.tree li:before {
    outline: solid 1px #666;
    content: "";
    left: 0;
    position: absolute;
    right: 0;
    top: 0;
}

.tree li {
    display: table-cell;
    padding: 0.5em 0;
    vertical-align: top;
}

.tree > li {
    margin-top: 0;
}

.tree > li:before, .tree > li:after, .tree > li > code:before, .tree > li > span:before {
    outline: none;
}

.tree code:before, .tree span:before {
    top: -0.55em;
}

.tree ul:before, .tree code:before, .tree span:before {
    outline: solid 1px #666;
    content: "";
    height: 0.5em;
    left: 50%;
    position: absolute;
}

.tree code, .tree span {
    border: solid 0.1em #666;
    border-radius: 0.2em;
    display: inline-block;
    margin: 0 0.2em 0.5em;
    padding: 0.2em 0.5em;
    position: relative;
}

.tree code, .tree span {
    border: none;
}

.step-content, .tree {
    position: relative;
}

.tree, .tree ul {
    display: table;
}

.tree {
    margin: 0 0 1em;
    text-align: center;
}

.tree, .tree ul, .tree li {
    list-style: none;
    margin: 0;
    padding: 0;
    position: relative;
}

.tree li {
    display: table-cell;
    padding: 0.5em 0;
    vertical-align: top;
}

.tree > li {
    margin-top: 0;
}

.progress{
    height: 3px;
}

.owl-nav{
    position: absolute;
    top: 50%;
    transform: translate(-0%, -50%);
    width: 100%;
}

.owl-next{
    float: right !important;
}

.announcement__slider img{
    max-width: 100%;
    height: auto !important;
}

.introduction-six__wrapper__item__content p{
    text-transform:capitalize;
}

.introduction-six__wrapper__item__image{
    border-radius: 0px;
}

.mobile-size{
    display: none;
}

.introduction-seven__wrapper{
    background-color: #92d2d9 !important;
}

.introduction-seven__wrapper.-top .introduction-seven__wrapper__content__detail h3{
    color: #bf8a68;
}

.btn, .blog-sidebar__section.-newsletter .blog-sidebar-newsletter .mc-form button, .paginator li button, .category-two .slick-arrow, .footer-one__header__newsletter .footer-one-newsletter .mc-form button, .footer-two__content .footer-two-newsletter .mc-form button, .product-detail__slide-two__small .slick-arrow, .product-tab-slide__content .slick-arrow, .slider.-style-3 .slider__carousel .slick-arrow, .testimonial-three .slick-arrow{
    font-weight: 100;
}

.st-label{
    display: inline-block !important;
}

.slick-prev.slick-arrow{
    position: absolute;
    top: 50%;
    transform: translate(-50%, -50%);
    left: -6px;
}

.slick-next.slick-arrow{
    position: absolute;
    top: 50%;
    transform: translate(-50%, -50%);
    right: -25px;
}

.slider.-style-2 .slider__carousel__item.slider-1 .slider-content__description, .slider.menu.-style-6 .slider__carousel__item.slider-1 .slider-content__description{
    color: #000;
}

.product-type .-new, .product-type .-sale{
    background-color: #f4b1ad;
}

.form-control{
    border-radius: 0px;
}

.refine_search li:before {
    content:"·";
    font-size:40px;
    vertical-align:middle;
    line-height:20px;
}

.menu-functions .menu-cart .menu-icon span {
    top: -9px;
    left: 100%;
    color: #fff !important;
}

a {
    color: #000000;
    text-decoration: unset !important;
}

a:hover {
    color: #0056b3;
}

.countdown {
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: center;
}

.countdown-item {
    display: flex;
    flex-direction: column;
    width: 90px !important;
    margin-right: 10px; 
    text-align: center;
    /* background-color: #DA291C; */
    border-radius: 7px;
}

.countdown-value {
    font-size: 40px !important;
    font-family: font-round-bold !important;
    letter-spacing: 1px !important;
    color: #{{ $data['text_colour'] }} !important;
}

.countdown-label {
    font-size: 14px !important;
    text-transform: lowercase !important;
    letter-spacing: 1px !important;
    color: #{{ $data['text_colour'] }} !important;
    padding-bottom: 10px;
}

.badge{
    padding: .30em .4em .25em .5em;
}

.top-nav{
    background-color: #FAF7F2;
    color: #000;
}

/*.top-nav.-style-2 .top-nav__wrapper__quick-links ul li a, .top-nav.menu.-style-6 .top-nav__wrapper__quick-links ul li a, .top-nav.-style-2 .top-nav__wrapper__quick-links ul li::after, .top-nav.menu.-style-6 .top-nav__wrapper__quick-links ul li::after, .top-nav__wrapper .top-nav-selections__item a{
    color: #000;
}*/

.menu.-style-2 .menu__wrapper .navigator .menu__wrapper__logo img, .menu.-style-6 .menu__wrapper .navigator .menu__wrapper__logo img{
    height: 4.8em;
    width: 10.1875em;
}

.menu.-style-2 .menu__wrapper .navigator > ul.-left, .menu.-style-6 .menu__wrapper .navigator > ul.-left, .menu.-style-2 .menu__wrapper .navigator > ul.-right, .menu.-style-6 .menu__wrapper .navigator > ul.-right{
    margin-bottom: 20px;
    margin-top: 20px;
}

.h-100 {
    height:550px!important; 
}
.h-200 {
    height:920px!important; 
}
.h-300 {
    height:1100px!important; 
}
.top-nav{
    padding: 10px 0;
}

.btn-block{
    display: block ;
    width: 100%;
}

.login-details{
    font-family: 'FontAwesome';
}

.header-login{
    color: #000;
    font-style: italic;
    /* font-family: 'Dancing Script'; */
}

.redirect-btn{
    background-color: #FFC0CB;
    border-radius: 50px;
    padding: 5px 10px;
    justify-content: center;
    margin: auto;
    display:block;
}

.modal{
    background: transparent;
}

section.about::before {
    display: none;
}

body{
    /*font-size: 12px;*/
    
}

h1, h2, h3, h4, h5, h6, b, div {
    color: #000;
}

footer{
    padding: 50px 0 0;
}

footer ul.social{
    margin: 50px 0 50px;
}

section.hero .social ul li a{
    width: 150px;
}

.search-generation.active{
    color: #496801;
    -webkit-box-shadow: 0 0 0 0.2rem rgb(127 180 1 / 50%);
    box-shadow: 0 0 0 0.2rem rgb(127 180 1 / 50%);
}

@if(Request::segment(1) == '' || Request::segment(1) == 'About' || Request::segment(1) == 'Contact' || 
    Request::segment(1) == 'Login' || Request::segment(1) == 'Register' || Request::segment(1) == 'merchant_register')
nav.navbar{
    background: #3c462b;
}

nav.navbar .nav-link{
    color: #c7a268;
}
@else
nav.navbar{
    background: #82ada6;
}

nav.navbar .nav-link{
    color: #fff;
}
@endif

/*section{
    padding-top: 100px;
    padding-bottom: 100px;
}*/

.main-content{
    margin-top: 130px;
}

.checkbox-style{
    font-size: 11px; 
    display: block;
}

.checkbox-style input{
    width: 13px;
    height: 13px;
    padding: 0;
    margin:0;
    vertical-align: bottom;
    position: relative;
    top: -1px;
    *overflow: hidden;
}

.navbar-expand-lg .navbar-nav .nav-link{
    padding-right: 1rem;
    padding-left: 1rem;
}

.dishes{
    background-attachment: fixed;
    background-size: 100%;
    background-position: center;
    background-repeat: no-repeat;
    padding-top: 20px;
    padding-bottom: 20px;
}

.register-form{
    display: none;
}

.fixed {
    position: fixed;
    top:0; 
    left:0;
    width: 100%; 
    z-index: 2000;
    border-bottom: 1px solid #ddd;
}

.sticky{
    background-color: #fff;
}

.float-wording{
  text-align: center;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  font-size: 35px;
  color: #fff;
}

.float-wording > *{
    color: #fff;
}

.header__top{
    background-color: #f2f2f2;
}

.header__top__left p, .header__top__links a{
    color: #000 !important;
}

.header__top__links a{
    margin-top: 4px;
}

.page_top{
    position: fixed;
    bottom: 80px;
    right: 20px;
    cursor: pointer;
    display: none;
    z-index: 1;
}

.search-switch .dropdown{
    display: none;
}

.top-search-area{
    top: 25px;
    right: 0;
    color: white;
    position: absolute;
    width: 1px;
    z-index: 99;
    opacity: 0;
    transition: all .75s ease;
}

.top-search-area.show {
  opacity: 1;
  width: 300px;
}


.search-switch{
    position: relative;
}

.top-search-btn:hover{
    color: black;
}

.header__menu ul li:hover .dropdown{
    top: 50px;
    left: -45px;
}

.footer__icon a{
    margin-right: 10px;
    margin-left: : 10px;
}

.product-description img{
    max-width: 100% !important;
    height: auto !important;
}

.header__logo, .header__menu, .header__nav__option{
    padding: 20px 0;
}

.main_category{
    display: block;
}

.product__item:hover .product__item__text h6{
    opacity: 1;
}

.header__nav__option a span{
    top: 3px;
}

.fb_dialog_content iframe{
    bottom: 80px !important;
}

.item-price, .item-price span{
    color: #000;
    font-size: 15px;
}

.scrolling-text{
    color: #fff;
}

.myPopUp{
    position: fixed; 
    top: 50%; 
    left: 50%; 
    transform: translate(-50%, -50%); 
    box-shadow: 0 0 10px 0 rgba(0,0,0,0.5);
    background-color: white;
    width: 320px;
}


@media screen and ( max-width: 400px ){
    .countdown-value {
        font-size: 25px !important;
    }

    .countdown-item {
        width: 65px !important;
    }
    li.page-item {

        display: none;
    }

    .page-item:first-child,
    .page-item:nth-child( 2 ),
    .page-item:nth-last-child( 2 ),
    .page-item:last-child,
    .page-item.active,
    .page-item.disabled {

        display: block;
    }
}

.product__discount__item__pic .product__discount__percent {
    height: 45px;
    /* width: 45px; */
    width: auto;
    min-width: 45px !important;
    background: #dd2222;
    border-radius: 50%;
    padding: 0px 10px  !important;
    font-size: 14px;
    color: #ffffff;
    line-height: 45px;
    text-align: center;
    position: absolute;
    left: 15px;
    top: 15px;
}

.details-img{
    width: 100%;
    height: 350px;
    background-size: contain;
    background-position: center;
    background-repeat: no-repeat;
}

.required-feild-error{
    border-color: red;
}

.sub_categories a.active{
    font-weight: bold;
}

.sub_categories{
    display: none; 
    margin-left: 20px; 
    padding: 10px 0;
}

.sub_sub_categories{
    display: none;
    margin-left: 40px;
    padding: 10px 0;
}

.listing-video{
    height: 265px;
    width: 100%;
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
    border: 2px solid #92d2d9; 
}

.variation_option.out-of-stock, .second_variation_option.out-of-stock{
    border: 2px solid #eee; 
    background-color: #eee;
    cursor: not-allowed;
    pointer-events:none;
}

.button-inside{
    position: relative;
}

.button-inside input{
    /*padding-right: 85px;*/
}

.button-inside button{
    position:absolute;
    right: 10px;
    top: 8px;
    outline:none;
    text-align:center;
    font-weight:bold;
    color: #fff;
    font-size: 10px;
    padding: 2px 8px;
}

.not-same-bg{
    background-color: transparent;
    color: #000;
}

.word-in-line {
   width: 100%; 
   text-align: center; 
   border-bottom: 1px solid #000; 
   line-height: 0.1em;
   margin: 10px 0 20px; 
} 

.word-in-line span { 
    background:#fff; 
    padding:0 5px; 
    font-size: 15px;
}

.bw-brg{
    background-color: #fff;
}

.product-description img{
    max-width: 100% !important;
}

.packages_badges{
    background: #dd2222;
    font-size: 12px;
    color: #ffffff;
    text-align: center;
    position: absolute;
    left: 15px;
    top: 15px;
    padding: 10px;
}

.footer{
    padding-bottom: 40px;
}

.product__discount__item, .featured__item{
    box-shadow: 0px 6px 10px -6px grey;
    padding-bottom: 10px;
    margin-top: 20px;
}

.product__discount__item__text h6, .featured__item__text h6{
    height: 50px;
    overflow: hidden;
}

.product__discount__item__text h5{
    height: 50px;
    overflow: hidden;
}

.details-box li{
    margin-left: 17px;
}

.price-range-wrap .range-slider .price-input input{
    max-width: 40%;
}

.nice-select{
    width: 100% !important;
}

.cat_menu li a i{
    display: block;
}

#toast-container *{
    color: #fff;
}

.container-box{
    /*box-shadow: 4px 4px 6px 0 #d5efe6;*/
    box-shadow: 0px 0px 6px 0 #ddd;
    padding: 15px;
    color: #fff;
    /*font-size: 12px;*/
    background-color: #fff;
}

.important-text{
    color: red;
}

label {
    font-weight: 400;
    font-size: 14px;
}

label input[type=checkbox].ace, label input[type=radio].ace {
    z-index: -100!important;
    width: 1px!important;
    height: 1px!important;
    clip: rect(1px,1px,1px,1px);
    position: absolute;
}


input[type=checkbox].ace+.lbl, input[type=radio].ace+.lbl {
    position: relative;
    display: inline-block;
    margin: 0;
    line-height: 20px;
    min-height: 18px;
    min-width: 18px;
    font-weight: 400;
    cursor: pointer;
}

input[type=checkbox].ace+.lbl::before, input[type=radio].ace+.lbl::before {
    cursor: pointer;
    font-family: fontAwesome;
    font-weight: 400;
    font-size: 12px;
    color: #FFF;
    content: "\a0";
    background-color: #FAFAFA;
    border: 1px solid #bdbdbd;
    box-shadow: 0 1px 2px rgba(0,0,0,.05);
    border-radius: 0;
    display: inline-block;
    text-align: center;
    height: 16px;
    line-height: 14px;
    min-width: 16px;
    margin-right: 1px;
    position: relative;
    top: -1px;
}

input[type=checkbox].ace:checked+.lbl::before, input[type=radio].ace:checked+.lbl::before {
    display: inline-block;
    content: '\f00c';
    color: #000;
    background-color: #F5F8FC;
    border-color: #ADB8C0;
    box-shadow: 0 1px 2px rgba(0,0,0,.05),inset 0 -15px 10px -12px rgba(0,0,0,.05),inset 15px 10px -12px rgba(255,255,255,.1);
}

.cart-header-list, .cart-details-list, .cart-checkout{
  padding: 10px;
  box-shadow: 0 1px 4px 0 rgba(0,0,0,.26);
  background-color: #fff;
}

.cart-header-list ul, .cart-details-list ul, .cart-checkout ul{
  list-style-type: none;
  margin: 0px;
  width: 100%;

}

.cart-details-list ul{
  border-bottom: 1px solid #d3d3d3;
}

.cart-header-list ul li, .cart-details-list ul li, .cart-checkout ul li{
  display: inline-block;
  vertical-align: top;
}

ul .select-cart{
  width: 5%;
}

ul .product-name{
  width: 45%;
}

ul .unit-price, ul .product-quantity, ul .product-total-price, ul .list-action{
  width: 12%;
  text-align: center;
}

.product-name img{
  width: 70px;
  display: inline-flex;

}

.product-all-details{
  height: 100px;
}

.product-details-name{
  width: 200px;
  display: inline-flex;
  word-wrap: break-word;
  vertical-align: top;
}

.product-details{
    padding-top: 0px;
}

.quantity-setting{
  display: inline-flex;
}

.quantity-setting .deduct-qty-button, .quantity-setting .add-qty-button{
  font-size: 8px;
  padding: 6px 10px;
  background-color: #fff !important;
  border-color: #fff !important;
  border: 1px solid #d3d3d3 !important;
  color: #000 !important;
  background: unset !important;
  border-radius: .25rem;
}

.quantity-setting input{
  width: 70px;
  text-align: center;
}

.list-action i{
  font-size: 20px;
}

.mobile-cart{
  display: none;
}

.checkout-total {
    width: 93%;
}

.checkout-total b.total-amount, .checkout-total b.east-total-amount{
    font-size: 20px;
    margin-right: 10px;
    color: #717fe0;
}

.details-page .details-box{
    padding: 10px;
    box-shadow: 0 1px 4px 0 rgba(0,0,0,.26);
}

.quantity-balance{
    font-size: 10px;
    color: #928c8c;
}

input[name="bank_id"], input[name="sub_category_id"] { 
  position: absolute;
  opacity: 0;
  width: 0;
  height: 0;
}

input[name="bank_id"] + img {
  cursor: pointer;
  width: 100px;
}

input[name="bank_id"]:checked + img {
  border: 2px solid #211c1c;
}

.widget-box.transparent {
    border-width: 0;
}

.widget-box {
    padding: 0;
    box-shadow: none;
    margin: 3px 0;
    border: 1px solid #CCC;
}

.progress, .widget-box {
    -webkit-box-shadow: none;
}

.widget-box.transparent>.widget-header {
    background: 0 0;
    border-width: 0;
    border-bottom: 1px solid #DCE8F1;
    color: #4383B4;
    padding-left: 3px;
}

.widget-box.transparent>.widget-header, .widget-header-flat {
    filter: progid:DXImageTransform.Microsoft.gradient(enabled=false);
}

.widget-header {
    -webkit-box-sizing: content-box;
    -moz-box-sizing: content-box;
    box-sizing: content-box;
    position: relative;
    min-height: 38px;
    background: repeat-x #f7f7f7;
    background-image: -webkit-linear-gradient(top,#FFF 0,#EEE 100%);
    background-image: -o-linear-gradient(top,#FFF 0,#EEE 100%);
    background-image: linear-gradient(to bottom,#FFF 0,#EEE 100%);
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffffff', endColorstr='#ffeeeeee', GradientType=0);
    color: #669FC7;
    border-bottom: 1px solid #DDD;
    padding-left: 12px;
}

.widget-header:after, .widget-header:before {
    content: "";
    display: table;
    line-height: 0;
}

.widget-header:after {
    clear: right;
}

.widget-header>.widget-title {
    line-height: 36px;
    padding: 0;
    margin: 0;
    display: inline;
}

.widget-header>.widget-title>.ace-icon {
    margin-right: 5px;
    font-weight: 400;
    display: inline-block;
}

h4.smaller {
    font-size: 17px;
}

.lighter {
    font-weight: lighter;
}

.widget-toolbar {
    display: inline-block;
    padding: 0 10px;
    line-height: 37px;
    float: right;
    position: relative;
}

.no-border {
    border-width: 0;
}

.widget-toolbar>.nav-tabs {
    border-bottom-width: 0;
    margin-bottom: 0;
    top: auto;
    margin-top: 3px!important;
}

.nav-tabs {
    border-color: #C5D0DC;
    margin-bottom: 0!important;
    position: relative;
    top: 1px;
}

.nav-tabs, .nav-tabs>li:first-child>a {
    margin-left: 0;
}

.widget-toolbar>.nav-tabs>li {
    margin-bottom: auto;
}

.nav-tabs>li {
    float: left;
    margin-bottom: -1px;
}

.nav>li, .nav>li>a {
    display: block;
    position: relative;
}

.transparent>.widget-header>.widget-toolbar>.nav-tabs>li.active>a {
    border-top-color: #d53c82;
    border-right: 1px solid #C5D0DC;
    border-left: 1px solid #C5D0DC;
    background-color: #FFF;
    box-shadow: none;
}

.transparent>.widget-header>.widget-toolbar>.nav-tabs>li>a {
    color: #555;
    background-color: transparent;
    border-right: 1px solid transparent;
    border-left: 1px solid transparent;
}

.widget-toolbar>.nav-tabs>li.active>a {
    background-color: #FFF;
    border-bottom-color: transparent;
    box-shadow: none;
    margin-top: auto;
}

.widget-toolbar>.nav-tabs>li>a {
    box-shadow: none;
    position: relative;
    top: 1px;
    margin-top: 1px;
}

.nav-tabs>li.active>a, .nav-tabs>li.active>a:focus, .nav-tabs>li.active>a:hover {
    color: #576373;
    border-color: #C5D0DC #C5D0DC transparent;
    border-top: 2px solid #4C8FBD !important;
    background-color: #FFF;
    z-index: 1;
    line-height: 18px;
    margin-top: -1px;
    box-shadow: 0 -2px 3px 0 rgba(0,0,0,.15);
}

.nav-tabs, .nav-tabs>li:first-child>a {
    margin-left: 0;
}

.nav-tabs>li>a, .nav-tabs>li>a:focus {
    border-radius: 0!important;
    border-color: #C5D0DC;
    background-color: #F9F9F9;
    color: #999;
    margin-right: -1px;
    line-height: 18px;
    position: relative;
}
.nav-tabs>li>a {
    padding: 7px 12px 8px;
}

.widget-box.transparent>.widget-body {
    border-width: 0;
    background-color: transparent;
}

.widget-body {
    background-color: #FFF;
}

.widget-main.padding-4 {
    padding: 4px;
}

.widget-main {
    /*overflow: auto;*/
}

.widget-main {
    padding: 12px;
}

.widget-main .tab-content {
    border-width: 0;
}

.tab-content.padding-8 {
    padding: 8px 6px;
}

.tab-content {
    border: 1px solid #C5D0DC;
    padding: 16px 12px;
    position: relative;
}

.tab-content>.tab-pane {
    display: none;
}

.tab-content>.active {
    display: block;
}

select{
    margin-left: 0px;
}

form button{
    margin-top: 0px;
}

.personal-header-info {
    position: absolute;
    top: 5%;
    width: 100%;
    color: #000;
}

.profile-content {
    margin-top: 65px;
}

.profile-logo{
    border-radius: 100%;
}

.header-title, .setting-btn, .profile-name, .profile-code, .profile-level, .personal-header-info a p{
    color: #000 !important;
}

.profile-setting-list li {
    list-style-type: none;
    border-bottom: 1px solid #eee;
    padding: 10px 0;
}

.profile-setting-list li a {
    width: 100%;
    display: block;
}

.pull-right {
    float: right;
}

.pull-left {
    float: left;
}

.profile-word{
    color: #000;
    margin-top : 5px;
    display: block;
}

.bottom-menu-bar {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 10px 10px;
    box-shadow: 0 0 7px #eee;
    z-index: 9998;
    background-color: #fff;
    display: none;
    font-size: 11px;
}

.wallet-desc {
    border-top: 1px solid #eee;
    display: block;
    width: 100%;
    font-size: 14px;
    padding-top: 5px;
}

.wallet-balance-amount {
    color: #4F99C6;
    font-size: 20px;
}

.affiliate_list ul{
    list-style-type: none;
    margin-left: 0px;
    /*margin-left: 12px;*/
}

.affiliate_list ul li {
    border-bottom: 1px solid #000;

}

.affiliate_list ul li a{
    display: block;
    width: 100%;
}

.affiliate_list ul li a .view-affiliate{
    float: right;
    margin-top: 20px;
}

.affiliate_list ul li .users-img{
    border-radius: 100%;
    width: 40px;
    height: 40px;
    background-repeat: no-repeat;
    background-position: center center;
    background-size: 100%;
}

.affiliate_list .affiliate-search-area{
    padding: 10px;
    background-color: transparent;
}

.affiliate_list .affiliate-list-area{
    padding: 10px;
    margin: 0 10px;
    border-radius: 10px;
}

.affiliate_list .affliate-details-background .user-details-img{
    border-radius: 100%;
    width: 50px;
    height: 50px;
    background-repeat: no-repeat;
    background-position: center center;
    background-size: cover;   
}

.affiliate_list .users-details-box{
    float: left;
    display: inline;
    margin-right: 20px;
    color: #000;
    font-size: 16px;
    line-height: 1.5;
}

.affiliate_list .search_affiliates{
    border-radius: 25px !important;
    padding-left: 15px;
}

.affiliate_list .search-query{
    border-top-left-radius: 25px !important;
    border-bottom-left-radius: 25px !important;
    padding-left: 15px;
}

.affiliate_list .search-button{
    border-top-right-radius: 25px !important;
    border-bottom-right-radius: 25px !important;
    border-left: none;
    /* border-color: #bdbdbd !important; */
    padding: 8.2px 10px;
    /* background-color: #fae7d5; */
}

.affiliate_list .affliate-details-background{
    position: relative;
    padding: 20px;
    width: 100%;
    background-size: 100%;
    background-position: center center;
    background-repeat: no-repeat;
    color: #fff;
    /*background-image: url({{ url('images/videoblocks-golden-globe-of-light-appears-and-moves-over-a-dark-background-abstract-warm-bulb-of-light_bnqdhq_9e_thumbnail-full02.png') }});*/
    background-color: #92d2d9;
}

.affiliate_list .affliate-details-background .totalResult{
    position: absolute;
    bottom: 5px;
    width: 100%;
}

.affiliate_list .totalResult .col-xs-4{
    border-right: 1px solid #fff;
}

.affiliate_list .totalResult .col-xs-4:last-child{
    border-right: none;
}

.affiliate_list .user-name{
    margin-top: 5px;
}

.loading-gif{
    position: fixed;
    left: 0;
    top: 0;
    bottom: 0;
    right: 0;
    z-index: 1000;
    background-size: 2%;
    background-repeat: no-repeat;
    background-position: center center;
    width: 100%;
    height: 100%;
    background-color: rgba(255,255,255, 0.5);
    display: none;
    z-index: 10000;
}

.banner-images{
    background-size: 100%;
    background-position: center;
    background-repeat: no-repeat;
    width: 100%;
    height: 378px;
}

.header__logo__box__mobile{
    display: none;
}

.featured__item__text h6{
    font-size: 14px;
    color: #b2b2b2;
    display: block;
    margin-bottom: 4px;
}

.product__discount__item__text span{
    color: #000 !important;
}

.listing-image{
    background-size: contain !important; 
}

.social-icons ul > li a {
    font-size: 16px;
}

@media only screen and (max-width: 992px) {
    .mobile-cart {
        display: block;
    }

    .web-cart {
        display: none;
    }

    ul .select-cart {
        width: 35px;
    }

    ul .unit-price {
        width: auto;
        text-align: left;
        font-size: 11px;
    }

    .countdown-value {
        font-size: 35px !important;
    }

    .countdown-label {
        font-size: 12px !important;
    }

    .countdown-item {
        width: 80px !important;
    }

    ul .product-name {
        width: 120px;
    }

    .listing-image{
        height: 200px !important;
    }

    .mobile-size{
        display: block;

    }

    .web-size{
        display: none;
    }
}

@media only screen and (max-width: 768px) {
    .countdown-value {
        font-size: 27px !important;
    }

    .countdown-label {
        font-size: 11px !important;
    }

    .countdown-item {
        width: 70px !important;
    }
    .bottom-menu-bar {
        display: block;
    }

    .banner-images{
        height: 200px;
    }

    .header__cart__box{
        display: none;
    }

    .header__cart{
        margin-top: 13px;
    }

    .header__cart ul li a i{
        font-size: 25px;
    }

    .header__cart ul li a span{
        top: -10px;
    }

    .header__logo__box__mobile{
        display: block;
    }

    .header__logo__box{
        display: none;
    }

    .f-15{
        font-size: 12px !important;
    }

    .listing-video{
        height: 150px;
    }

    .offcanvas__nav__option a span{
        top: 4.5px !important;
    }

    .card-wrapper img{
        width: 30% !important;
    }

    .footer-one{
        padding-bottom: 49px;
    }

    .footer-center{
        width: 100%;
    }

    .top-nav{
        display: block;
    }
}

@media only screen and (max-width: 550px) {
    .float-wording{
        font-size: 25px;
    }

    @if(Request::segment(1) == 'Details')
        header{
            display: none;
        }

        .main-content{
            margin-top: 0px;
        }

        .details-img{
            height: 200px;
        }

        .product-details > *{
            font-size: 13px !important;
        }
    @endif
}

@media only screen and (max-width: 480px) {
    ul .select-cart {
        width: 20px;
    }

    .product-name img, ul .product-name {
        width: 50px;
    }

    .product__discount__item__pic, .featured__item__pic{
        height: 150px;
    }

    .card-wrapper img{
        width: 50% !important;
    }
}

@media only screen and (max-width: 431px) {
      .float-wording{
          font-size: 15px;
      }

      .listing-image{
        /*height: 150px !important;*/
        min-height: unset !important;
        height: 30vh !important;
    }
}

@media only screen and (min-width: 992px){
    .menu-functions .menu-icon.-search.-mobile {
          display: none; }
    }

@media only screen and (max-width: 992px){
    .menu-functions .menu-icon.-search.-mobile {
          display: block; }

    .shop-sidebar__section.-categories.-mobile{
        display: none; }

    .menu__wrapper .search-box{
    width: 90vw; 
    transform: translateX(-150px); }
    }


.div-show {
    animation: fadeIn 2s ease-in !important;
}

.text-white {
    color: #fff !important;
}

.text-black {
    color: #000000 !important;
}

.one-linear {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: normal;
}

.two-linear {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: normal;
}

.three-linear {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: normal;
}

.ls-0-3 {
    letter-spacing: 0.3px !important;
}

.ls-0-5 {
    letter-spacing: 0.5px !important;
}

.ls-1 {
    letter-spacing: 1px !important;
}

.ls-2 {
    letter-spacing: 2px !important;
}

.ls-3 {
    letter-spacing: 3px !important;
}

.ls-4 {
    letter-spacing: 4px !important;
}

.ls-5 {
    letter-spacing: 5px !important;
}

.fw-600 {
    font-weight: 600 !important;
}

.br-5 {
    border-radius: 5px !important
}

.br-10 {
    border-radius: 10px !important
}

.br-15 {
    border-radius: 15px !important
}

.br-20 {
    border-radius: 20px !important
}

.lh-0 {
    line-height: 0 !important;
}

.lh-1 {
    line-height: 1 !important;
}

.lh-2 {
    line-height: 2 !important;
}

.lh-3 {
    line-height: 3 !important;
}

.lh-4 {
    line-height: 4 !important;
}

.lh-5 {
    line-height: 5 !important;
}

.overflow-hidden {
    overflow: hidden !important;
}

.btn,
.btn.hovered,
.btn.-red {
    color: #fff;
    border: 1px solid #fff;
    /* border: 1px solid transparent; */
    border-radius: 7px;
    font-weight: unset;
    font-size: 12px;
    padding: 10px 25px;
}

.btn:hover,
.btn:active:hover,
.btn:focus:hover,
.btn.-red:hover {
    color: #48409e ;
    background: #fff;
    border: 1px solid #48409e;
}

.btn:focus,
.btn.-red:focus {
    box-shadow: 0 0 0 .1rem #c8945321;
}

.btn-primary:focus,
.btn-danger:focus {
    border-color: unset;
}

.btn-primary:not(:disabled):not(.disabled).active, 
.btn-primary:not(:disabled):not(.disabled):active, 
.show>.btn-primary.dropdown-toggle,
.btn-danger:not(:disabled):not(.disabled).active, 
.btn-danger:not(:disabled):not(.disabled):active, 
.show>.btn-danger.dropdown-toggle {
    background: linear-gradient(to right, #48409e, #db3c81);
}

.text-decoration-unset {
    text-decoration: unset !important;
}

.badge {
    color: #fff;
    font-weight: unset;
    font-size: 85%;
}

.text-rose {
    color: #db3c81;
}

.f-38 {
    font-size: 38px;
    margin-bottom: 10px;
}

.f-19 {
    font-size: 19px;
    margin-bottom: 25px;
}

.f-14 {
    font-size: 14px;
    color: #545454;
    line-height: 2;
}

.h-650 {
    height: 650px !important;
    width: 100% !important;
    object-fit: cover !important;
    border-radius: 15px !important;
    margin: auto !important;
}

.px-35 {
    padding-left: 35px !important;
    padding-right: 35px !important;
}

.h-500 {
    height: 500px;
    object-fit: contain;
    width: 100%;
}

.loading-message {
    position: fixed;
    top: 4px;
    left: 50%;
    transform: translate(-50%, 0%);
}

.longer-25 {
    height: 20px !important;
    width: 20px !important;
    filter: grayscale(1) !important;
}

.hdr-topline-center
{
    display: unset;
}

.hdr-topline {
    border-bottom-color: #f4f4f4;
}

.hdr-topline.js-hdr-top {
    background-color: #000000;
    text-align: center;
    line-height: 40px;
    width: 100%;
    opacity: 0;
    transition: opacity 0.5s ease-in-out;
}

.hdr-topline.js-hdr-top.show {
    opacity: 1;
}

.word-13 {
    font-size: 13px !important;
    white-space: nowrap !important;
    font-weight: 600;
}

.display-inline-block {
    display: inline-block !important;
    color: #fff !important;
    font-weight: 600 !important;
    margin: 0 auto;
}

.menu.-style-2 .menu-functions:first-child, .menu.-style-6 .menu-functions:first-child {
    display: block;
}

.h-66 {
    height: 66px;
    width: 66px;
    background-size: contain;
    background-repeat: no-repeat;
    background-position: center;
    border-radius: 50%;
}

.menu-functions .menu-icon.-navbar .bar {
    height: 0.174em;
}

.bg-footer {
    background: radial-gradient(circle, #ffffff, #efefef);
    box-shadow: 0 0 7px 0 #ddd !important;
}

.gap-10 {
    display: flex !important;
    justify-content: center !important;
    gap: 10px !important;
    font-size: 15px !important;
    margin-top: 8px !important;
}

.gap-10 li {
    list-style: none;
    color: #fff;
    height: 2.3125em;
    width: 2.3125em;
    text-align: center;
    line-height: 2.8125em;
    transition: background-color 0.3s ease-in-out;
}

.display-inline {
    display: inline-block !important;
    padding: 0 !important;
    border: none !important;
    background-color: unset !important;
    overflow: hidden !important;
    background: unset !important;
}

.display-inline:hover {
    background-color: unset !important;
}

.social-media-icon {
    max-width: 100%;
    height: auto;
    border-radius: 7px;
}

.padding-1-5 {
    padding: 1.5px !important;
}

.d-justify-content-around {
    display: flex !important;
    justify-content: space-around !important;
}

.payment-link {
    display: flex !important;
}

.payment-link > * {
    margin: 5px 5px !important;
}

.bg-copyright {
    background-color: #e6e6e6;
}

.footer-one .footer-title {
    font-size: 1.1em;
}

.footer-one__body .footer__section.-links ul li a {
    font-size: 1em;
}

.footer-one__footer__wrapper p {
    font-size: 0.9em;
    line-height: 1.5;
}

.whatsapp-btn {
    display: block;
    position: fixed; 
    height: 60px; 
    right: 2%;
    bottom: 9%; 
    border-radius: 100%;
    background-image: url({{ url('images/Whatsapp.png') }}); 
    background-size: 90%; width: 60px; 
    background-position: center; 
    background-repeat: no-repeat;
    z-index: 99999;
}

.product-loading,
.testimonial-loading,
.blogs-loading {
    text-align: center;
}

.product-slider,
.flash-sales-slider,
.testimonial-slide,
.blogs-slide {
    display: none;
}

.w-20 {
    height: 20px !important;
    width: 20px !important;
    filter: grayscale(1) !important;
}

.f-18 {
    font-size: 18px;
    margin-bottom: 10px;
    padding: 15px 50px !important;
    border-radius: 15px !important;
}

.br-50 {
    border-radius: 50%;
}

.d-w-30 {
    width: 30%;
    padding-bottom: 20px;
    border-top: 2px solid #dddddd;
    margin: auto;
}

.twentytwenty-container img{
    /*left: 50%;
    transform: translate(-50%, 0);*/
}

.ba-container {
  width: 60%;
}

.ba-slider {
  position: relative;
  overflow: hidden;
}

.ba-slider img {
  width: 100%;
  display: block;
}

.resize {
  position: absolute;
  top: 0;
  left: 0;
  height: 100%;
  width: 50%;
  overflow: hidden;
}

.handle {
  /* Thin line seperator */
  position: absolute;
  left: 50%;
  top: 0;
  bottom: 0;
  width: 4px;
  margin-left: -2px;
  background: rgba(0, 0, 0, .5);
  cursor: ew-resize;
}

.handle:after {
  position: absolute;
  top: 50%;
  width: 48px;
  height: 48px;
  margin: -24px 0 0 -24px;
  content: '\21ff';
  color: #000;
  font-size: 30px;
  text-align: center;
  line-height: 48px;
  background: transparent;
  border: 1px solid #000;
  border-radius: 50%;
  transition: all 0.3s ease;
  box-shadow: 0 2px 6px rgba(0, 0, 0, .3), inset 0 2px 0 rgba(255, 255, 255, .5), inset 0 60px 50px -30px #e6ffe6;
}

.top-draggable:after {
  width: 48px;
  height: 48px;
  margin: -24px 0 0 -24px;
  line-height: 48px;
  font-size: 30px;
}

.eye-heading {
    font-size: 60px;
}

.eye-container {
    position: relative;
    display: flex; 
    justify-content: center; 
    align-items: center; 
}

.eye-container-box{
    position: relative;
    font-size: 13px; 
    padding: 5px;
}

.eye-black-circle-new {
    position: absolute;
    right: 50px;
    color: #fff;
    width: 50px;
    height: 50px;
    background-color: black;
    border-radius: 50%;
    z-index: 1;
    display: flex;
    text-align: center;
    justify-content: center;
    align-items: center;
}

.eye-green-best-seller {
    position: absolute;
    top: 10px;
    right: 10px;
    color: #fff;
    width: 60px;
    height: 60px;
    background-color: green;
    border-radius: 50%;
    z-index: 1;
    display: flex;
    text-align: center;
    justify-content: center;
    align-items: center;
}

.product-content.pb-4 a:hover {
    color: unset;
    text-decoration: unset;
}

.eye-product-name {
    margin: 0 auto;
    width: 90%;
    text-decoration: none;
    transition: 0.2s ease-in-out;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-bottom: 0.625em;
    font-size: 16px;
    color: #4f4f4f;
    -webkit-text-stroke: thin;
    letter-spacing: 0.2px;
    cursor: text;
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
}

.eye-short-description {
    height: 39px;
    width: 90%;
    margin-bottom: 20px;
    margin: 0 auto;
    text-decoration: none;
    transition: 0.2s ease-in-out;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    font-size: 15px;
    color: #000000;
    letter-spacing: 0.2px;
    -webkit-text-stroke-width: thin;
    -webkit-box-orient: vertical;
}

.product-thumb {
    padding: 5px;
}

.product-content.pb-4 {
    text-align: center;
}

.eye-btn-see-more {
    width: 60%;
    margin: 0 auto;
    padding: 10px 0;
    font-size: 16px;
    color: #fff;
    font-weight: 600;
    letter-spacing: 1px;
    border: 1px solid #fff;
    border-radius: 5px;
    background: linear-gradient(to right, #48409e, #db3c81);
    transition: .2s ease-in-out;
    cursor: pointer;
}

.eye-btn-see-more:hover {
    color: #48409e; 
    background: #ffffffff;
    border: 1px solid #48409e;
}

.testimonials-padding {
    padding-left: 0;
    padding-right: 0;
}

.product-slider .slick-arrow.slick-next {
    transform: translate(46%, -50%);
}

.flash-sales-slider .slick-arrow.slick-next {
    transform: translate(46%, -50%);
}

.small-container .show-small-img{
    /* height: auto; */
    border-color: #4C8FBD !important;
    object-fit: contain;
}

.h-90 {
    height: 90px;
}

.height-120 {
    height: 120px;
}

.mb-d-end {
    display: flex;
    align-items: center;
    margin-top: 30px;
}

.btn-whatspp {
    color: #fff !important;
    border: 1px solid #1e7e34 !important;
    background-color: #1e7e34 !important;
    border-radius: 4px !important;
}

.btn-whatspp:hover {
    color: #1e7e34 !important;
    border: 1px solid #1e7e34 !important;
    background-color: #fff !important;
}

.margin-right-5 {
    margin-right: 5px;
}

.margin-right-7 {
    margin-right: 7px;
}

.ml-24 {
	margin-left: 24px;
}

.f-12 {
    font-size: 12px !important;
}

.ml-7 {
    margin-right: 7px;
}

.translateY-13 {
    transform: translateY(13%);
}

.profile-own-bg {
    /* background-color: #eee7dd !important; */
    background: radial-gradient(circle, #ffffff, #efefef);
    padding: 100px 0 128px 0;
    border-bottom-left-radius: 20px;
    border-bottom-right-radius: 20px;
    position: relative;
}

.w-101 {
    width: 101px;
    object-fit: contain;
}

.padding-3 {
    padding: 0.375rem 0.75rem; 
    width: auto;
    -webkit-appearance: auto; 
    z-index: 1000;
}

.font-18 {
    font-size: 18px;
}

.affiliate_list .affliate-details-background{
    position: relative;
    padding: 20px;
    width: 100%;
    background-size: 100%;
    background-position: center center;
    background-repeat: no-repeat;
    color: #fff;
    /*background-image: url({{ asset('images/videoblocks-golden-globe-of-light-appears-and-moves-over-a-dark-background-abstract-warm-bulb-of-light_bnqdhq_9e_thumbnail-full02.png') }});*/
    background: radial-gradient(circle, #ffffff, #efefef);
}

.hover-text-black {
    color: #fff !important;
}

.hover-text-black:hover {
    color: #000000 !important;
}

.py-10 {
    padding-top: 10px;
    padding-bottom: 10px;
}

.h-74 {
    height: 74px;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: normal;
}

.h-651 {
    height: 650px;
    object-fit: cover;
    width: 100%;
}

.footer-one__header__logo a img {
    height: 70px;
    width: 100%;
    object-fit: contain;
}

.main-content {
    margin-top: 40px !important;
    margin-bottom: 80px !important;
}

.menu.-style-2, .menu.-style-6 {
    background: radial-gradient(circle, #fefefe, #fefefe) !important;
    /* box-shadow: 0 0 7px 0 #fefefe !important; */
}

.transparent>.widget-header>.widget-toolbar>.nav-tabs>li.active>a {
    border-top-color: #c69352;
    border-right: 1px solid #d4d4d4;
    border-left: 1px solid #d4d4d4;
}

.footer-one__body .footer__section.-links ul li a:hover {
    -webkit-text-stroke: thin;
}

.bg-white-coco {
    background-image: linear-gradient(to bottom, #fff 30%, #e0d2bd 70%);
    padding-top: 130px;
    padding-bottom: 170px;
    text-align: center;
}

.h-501 {
    height: 501px;
    width: 100%;
    object-fit: cover;
}

.main-price {
    color: #000000;
}

.h-400 {
    height: 400px; 
    width: 100%; 
    background-position: center; 
    background-size: cover; 
    background-repeat: no-repeat;
}

input[type=checkbox].ace:checked+.lbl::before, 
input[type=radio].ace:checked+.lbl::before {
    font-family: 'Font Awesome 5 Pro';
}

input[type=checkbox].ace+.lbl::before, 
input[type=radio].ace+.lbl::before {
    border: 1px solid #989898;
    box-shadow: 0 1px 2px rgb(255 244 233);
}

.menu__wrapper .navigator > ul > li {
    margin: unset;
}

.menu__wrapper .navigator > ul > li .dropdown-menu {
    top: 35px;
    display: block !important;
    padding: 0.4em 0.8em;
    text-align: center;
    /* background-color: #2b2b2b; */
    border: unset;
    background: radial-gradient(circle, #fff, #efefeff1);
    box-shadow: unset;
}

.menu__wrapper .navigator > ul > li > a {
    color: #000000;
    font-size: 18px;
    font-weight: unset;
    line-height: 2.1;
    text-align: center;
    transition: 0.2s ease-in-out !important;
    overflow: hidden;
}

.menu__wrapper .navigator > ul > li > a:hover {
    transform: scale(1.05);
    -webkit-text-stroke: thin;
}

.menu.-style-2 .menu__wrapper .navigator > ul > li > a, 
.menu.-style-6 .menu__wrapper .navigator > ul > li > a {
    padding: 6px 20px;
}

.menu__wrapper .navigator > ul > li > a:after{
    background-color: unset;
}

.dropdown-menu {
    display: none;
    position: absolute;
}

li:hover .dropdown-menu {
    display: block;
}

.menu__wrapper .navigator > ul > li .dropdown-menu li a {
    /* color: #fff; */
    color: #000000;
    font-size: 18px;
    font-weight: unset;
    line-height: 2;
    text-align: center;
}

.menu__wrapper .navigator > ul > li .dropdown-menu li a:hover {
    color: #000000;
}

.menu.-style-2, .menu.-style-6{
    padding: 0.3em 0;
}

.menu.-style-2 .menu__wrapper .navigator > ul.-left, 
.menu.-style-6 .menu__wrapper .navigator > ul.-left {
    margin-right: unset;
}

.bb-2 {
    border-bottom: 2px solid #e0e0e0;
}

.navigation-sidebar .navigator-mobile > ul > li > a {
    font-weight: unset;
}

.bt-grey {
    border-top: 1px solid #f2f0f0;
    margin-top: 5px;
    margin-bottom: 10px;
}

.drawer.show.drawer-right {
    z-index: 9999;
}

.drawer {
    max-width: 93vw;
}

.h-240 {
    height: 240px;
    width: 100%;
    border-radius: 15px;
    object-fit: contain;
}

.h-320 {
    height: 320px;
    background-size: contain !important;
    background-repeat: no-repeat;
    background-position: center;
    border-radius: 5px;
}

.single_post_text ul,
.second_post_text ul {
    list-style-type: disc;
}

.single_post_text img,
.page-title-bg img,
.tab-pane img,
.second_post_text img {
    max-width: 100%;
    height: auto !important;
}

.single_post_text h1, 
.single_post_text h2, 
.single_post_text h3,
.single_post_text h4, 
.single_post_text h5, 
.single_post_text h6,
.single_post_text p, 
.single_post_text span, 
.page-title-bg h1, 
.page-title-bg h2, 
.page-title-bg h3,
.page-title-bg h4, 
.page-title-bg h5, 
.page-title-bg h6,
.page-title-bg p, 
.page-title-bg span, 
.tab-pane h1, 
.tab-pane h2, 
.tab-pane h3,
.tab-pane h4, 
.tab-pane h5, 
.tab-pane h6,
.tab-pane p, 
.tab-pane span,
.second_post_text h1, 
.second_post_text h2, 
.second_post_text h3,
.second_post_text h4, 
.second_post_text h5, 
.second_post_text h6,
.second_post_text p, 
.second_post_text span {
    line-height: 2 !important;
}

.single_post_text,
.second_post_text {
    overflow: hidden;
}

.bg-skygrey {
    background: linear-gradient(to bottom, #fff, #fff, #fff, #fff, #f9f9f9, #ececec);
}

.bg-skygrey-1 {
    background: linear-gradient(to bottom, #ececec, #f9f9f9, #fff, #fff, #f9f9f9, #ececec);
}

.w-400 {
    min-width: 400px; 
    max-width: 700px;
    width: 100%;
    border-radius: 5px;
    background-color: #000000;
}

.py-80 {
    padding-top: 80px;
    padding-bottom: 80px;
    text-align: center;
}

.w-330 {
    width: 330px;
    margin: auto;
    border-radius: 10px;
    color: #48409e;
    border: 1px solid #48409e;
    background-color: #fff;
}

.w-330 a,
.f-26 a {
    display: inline-block;
    position: relative;
    color: #000000;
    font-size: 26px;
    font-weight: 200;
    text-align: center;
    letter-spacing: 0.5px;
    padding: 8px 20px;
}

.f-26 {
    width: 100%;
    background-color: #fff;
    border: 2px solid transparent;
    background-origin: border-box;
    background-clip: content-box, border-box;
}

.bg-darkblue {
    color: #fff;
    font-size: 18px;
    margin-bottom: 10px;
    padding: 15px 50px !important;
    border-radius: 15px !important;
    color: #48409e;
    background: linear-gradient(to right, #48409e, #db3c81);
    /* border: 1px solid #48409e; */
    /* background-color: #fff; */
}

.bg-darkblue:hover {
    color: #48409e ;
    border: 1px solid #48409e;
    background: #fff;
    /* color: #fff;
    border: 1px solid #48409e;
    background-color: #48409e; */
}

.text-grey {
    color: #959595;
}

.my-50 {
    margin-top: 50px;
    margin-bottom: 15px;
}

.shop-sidebar__section.-categories ul > li a {
    font-size: 1em;
}

.h-220 {
    height: 220px;
}

.page-header {
    height: 200px;
    width: 100%;
    margin-bottom: 38px;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
}

.h-20 {
    height: 20px !important;
    display: inline-block !important;
    padding: 1px 8px 17px 8px !important;
    color: #fff !important;
    font-size: 11px !important;
    border: 1px solid #1e7e34 !important;
    background-color: #1e7e34 !important;
    background: #1e7e34 !important;
    vertical-align: top !important;
    text-transform: capitalize !important;
    font-family: sans-serif !important;
    font-weight: 600 !important;
    letter-spacing: 0.3px !important;
}

.margin-30 {
    margin-top: 30px;
    margin-bottom: 30px;
}

.list-style-type-none {
    list-style-type: none;
}

.contact-info__item__icon {
    color: #{{ $data['button_colour'] }};
}

.hover-text-yellow {
    color: #fff !important;
    border: 1px solid #fff !important;
}

.hover-text-yellow:hover {
    color: #c69252 !important;
    border: 1px solid #c69252 !important;
    background: #fff !important;
    background-color: #fff !important;
}

.btn-black {
    background: #000000;
    border-color: transparent;
}

.btn-black:hover {
    background: #fff;
    border-color: #000000;
}

.text-18 {
    font-size: 18px;
}

.hover-text-sunset {
    color: #fff !important;
}

.hover-text-sunset:hover {
    color: #c69252 !important;
}

.mb-padding-15 {
    padding: 10px 17px 10px 17px;
    letter-spacing: 1.5px;
    font-size: 11px;
}

.product-type .-new, 
.product-type .-sale {
    background-color: #{{ $data['button_colour'] }};
    color: #{{ $data['text_colour'] }};
}

.mobile-mb-15 {
    text-align: right;
}

.quiz-text-black * {
    color: #000000 !important;
}

.mb-mb-40 {
    margin-bottom: 50px !important;
}

.d-pb-25 {
    padding-bottom: 25px;
}

.desktop-block {
    display: block;
}

.mobile-block {
    display: none;
}

.mw-470 {
    max-width: 470px;
    margin: auto;
}

.px-130 {
    padding-left: 130px;
    padding-right: 130px;
}

.h-70 {
    height: 70px; 
    width: 100%; 
    object-fit: contain;
}

.carousel-indicators li {
    width: 20px;
}

.h-250 {
    height: 250px;
    width: 100%;
    background-repeat: no-repeat;
    background-size: cover;
    background-position: center;
}

.page-link {
    color: #000000 !important;
}

.page-item.active .page-link {
    background-color: #e7e7e7;
    border-color: #d9d9d9;
    color: #000000 !important;
}

.breadcrumb{
    padding: 1.125em;
    margin-bottom: 3.25rem;
    background: radial-gradient(circle, #f2f2f2, #efefef);
}

.breadcrumb h2 {
    margin-top: 0.2em;
    margin-bottom: 0.2em;
    color: #000000;
}

.breadcrumb ul li {
    color: #000000;
}

.breadcrumb ul li.active{
    color: #000000;
}

.breadcrumb-option{
    padding: 10px 0px;
    border-top: 1px solid #82ada6;
    border-bottom: 1px solid #82ada6;
    background-color: rgb(130 173 166 / 0.2);
}

.breadcrumb-section{
    padding: 70px 0 70px;
}

.py-60 {
    padding-top: 10px;
    padding-bottom: 60px;
}

.h-33 {
    height: 33px;
    margin-bottom: 5px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: normal;
}

.w-23 {
    width: 23px;
}

.d-ib {
    display: inline-block;
}

.menu-functions .menu-cart .menu-icon span {
    font-size: 0.725em;
}

.btn-shadow {
    background: #000000;
}

.f-profile-account {
    font-size: 15px !important;
}

.global_language {
    padding: 0.375rem 0.75rem; 
    width: auto; 
    -webkit-appearance: auto; 
    z-index: 1000;
    border-radius: 5px;
}

.f-20 {
    font-size: 20px;
}

.mw-100 {
    max-width: 100%;
}

.g-10 {
    gap: 10px;
    padding-top: 10px;
}

.d-text-right {
    text-align: right;
}

@if(Request::segment(1) == 'MyAffiliate' || 
    Request::segment(1) == 'MyCustomer')
    .profile-own-bg {
        padding: 100px 0 98px 0 !important;
    }
@else
    .profile-own-bg {
        padding: 100px 0 128px 0 !important;
    }
@endif

.w-101 {
    width: 101px;
    object-fit: contain;
    border-radius: 50%;
}

.w-29 {
    width: 29px;
}

.w-26 {
    width: 26px;
}

.mw-800 {
    max-width: 800px;
}

.header-about-us > ul > li > a {
    line-height: 2.25em;
}

.d-padding-10 {
    padding: 10px 15px;
}

.voucher-image {
    display: block; 
    margin: 0 auto;
    padding: 10px 0;
    height: 300px;
    width: 100%;
    object-fit: contain; 
}

.h-101 {
    height: 101px;
    width: 101px;
    background-size: contain;
    background-position: center;
    background-repeat: no-repeat;
}

@media only screen and (max-width: 1100px) {
    .menu__wrapper .navigator > ul > li > a {
        font-size: 16px;
    }
}

@media only screen and (max-width: 992px) {
    .h-650 {
        height: 500px !important;
        width: 100% !important;
        margin: 0 0 15px 0 !important;
    }

    .px-35 {
        padding-left: 15px !important;
        padding-right: 15px !important;
    }

    .f-38 {
        font-size: 23px;
        margin-bottom: 10px;
    }

    .f-19 {
        font-size: 14px;
        margin-bottom: 20px;
    }

    .f-14 {
        font-size: 12px;
    }

    .mb-mb-40 {
        margin-bottom: 40px !important;
    }

    .h-500 {
        height: 400px;
    }

    .loading-message {
        top: 5px;
    }

    .word-13 {
        font-size: 11px !important;
        letter-spacing: 0.5px !important;
    }
    
    .h-66 {
        height: 46px;
        width: 46px;
    }

    .menu.-style-2, .menu.-style-6 {
        padding: 0.5em 0;
    }

    .menu__wrapper .search-box {
        width: 100%;
    }

    .d-justify-content-around {
        display: flex !important;
        flex-wrap: wrap;
        justify-content: space-evenly;
        /* justify-content: unset !important;
        flex-direction: column !important; */
    }

    .f-18 {
        font-size: 12px;
        padding: 10px 40px !important;
        margin-bottom: unset;
    }

    .d-w-30 {
        width: 60%;
        margin-bottom: unset;
    }

    .py-5 {
        padding-bottom: 1.5rem !important;
        padding-top: 1.5rem !important;
    }

    .h2, h2 {
        font-size: 1.5rem;
    }

    .eye-heading {
        font-size: 30px;
    }

    .mb-f-12 {
        font-size: 12px;
    }

    .mb-f-13 {
        font-size: 13px !important;
    }

    .h-90 {
        height: 50px;
        object-fit: contain;
    }

    .mb-w-100 {
        width: 100%;
        margin-bottom: 10px;
    }

    .height-120 {
        height: 90px;
    }

    .mb-mw-0 {
        max-width: 0%;
        flex: 0%;
        padding-right: 0;
        padding-left: 0;
    }

    .mb-d-none {
        display: none !important;
    }

    .mb-d-end {
        align-items: flex-end;
        margin-top: unset;
    }

    .ml-24 {
		margin-left: 20px;
	}

    .f-12 {
        font-size: 10px !important;
    }

    .ml-7 {
        margin-right: 6px;
    }

    .breadcrumb{
        margin-bottom: 1.5rem;
    }

    .breadcrumb h2 {
        margin-top: 0.1em;
        margin-bottom: 0.1em;
        font-size: 2.125em;
    }

    .w-101 {
        width: 61px;
    }

    .padding-3 {
        font-size: 10px;
        padding: 0.375rem 0.5rem;
    }

    .mb-px-5 {
        padding-left: 5px;
        padding-right: 5px;
    }

    .font-18 {
        transform: translateY(4px);
    }

    .h-74 {
        height: 47px;
        font-size: 13px;
    }

    .h-651 {
        height: 150px;
        object-fit: contain;
    }

    .bg-white-coco {
        background-image: linear-gradient(to bottom, #fff, #fff, #e0d2bd);
        padding-top: 30px;
        padding-bottom: 50px;
    }

    .h-501 {
        height: 181px;
    }

    .product-thumb {
        margin-bottom: 5px;
    }

    .h-33 {
        height: 28px;
        margin-bottom: 10px;
    }

    .shop-products .product, .shop-products .product-list {
        margin-bottom: 10px;
    }

    .mb-f-9 {
        font-size: 9px !important;
    }

    #toast-container>div {
        padding: 10px 10px 10px 20px;
        font-size: 11px;
        letter-spacing: 0.5px;
        width: 22em;
    }

    .h-400 {
        height: 150px;
    }

    .mb-f-14 {
        font-size: 14px;
        margin-bottom: 10px;
    }

    .eye-short-description {
        height: 39px;
        font-size: 16px;
    }

    .eye-btn-see-more {
        font-size: 14px;
    }

    .h-240 {
        height: 100px;
    }

    .h-320 {
        height: 350px;
    }

    .single_post_text h1, 
    .single_post_text h2, 
    .single_post_text h3,
    .single_post_text h4, 
    .single_post_text h5, 
    .single_post_text h6,
    .page-title-bg h1, 
    .page-title-bg h2, 
    .page-title-bg h3,
    .page-title-bg h4, 
    .page-title-bg h5, 
    .page-title-bg h6,
    .tab-pane h1, 
    .tab-pane h2, 
    .tab-pane h3,
    .tab-pane h4, 
    .tab-pane h5, 
    .tab-pane h6 {
        font-size: 14px;
    }

    .single_post_text p, 
    .single_post_text span,
    .page-title-bg p, 
    .page-title-bg span,
    .tab-pane p, 
    .tab-pane span {
        font-size: 13px;
    }

    .w-400 {
        min-width: 250px;
    }

    .py-80 {
        padding-top: 20px;
        padding-bottom: 20px;
    }

    .w-330 {
        width: 230px;
    }

    .w-330 a, 
    .f-26 a {
        font-size: 17px;
    }

    .bg-darkblue {
        padding: 14px 40px !important;
        font-size: 14px;
    }

    .my-50 {
        margin-top: 30px;
        margin-bottom: unset;
    }

    .h-220 {
        height: 170px;
    }

    .page-header {
        height: 150px;
        margin-bottom: 20px;
    }

    .mb-p-13 {
        padding: 13px 33px;
        font-size: 11px;
    }

    .margin-30 {
        margin-top: 20px;
        margin-bottom: 40px;
    }

    .mb-f-10 {
        font-size: 10px;
    }

    .mb-font-11 a p,
    .mb-font-11 p {
        font-size: 11px;
    }

    .text-18 {
        transform: translateY(4px);
    }

    .badge {
        font-size: 75%;
    }

    .profile-word{
        font-size: 12px;
    }

    table {
        font-size: 13px;
    }

    .mb-padding-15 {
        padding: 10px 17px 10px 17px;
        font-size: 11px;
        letter-spacing: 1.5px;
    }

    .wallet-balance-amount {
        font-size: 20px !important;
    }

    .important-text {
        font-size: 13px;
        font-weight: unset;
    }

    .profile-content {
        margin-top: 75px;
    }

    .second_post_text h1, 
    .second_post_text h2, 
    .second_post_text h3,
    .second_post_text h4, 
    .second_post_text h5, 
    .second_post_text h6,
    .second_post_text p, 
    .second_post_text span {
        font-size: 17px !important;
    }

    .mb-pt-14 {
        padding-top: 14px;
    }

    .mb-px-30 {
        padding-left: 30px !important;
        padding-right: 30px !important;
    }

    .contact-title {
        margin-bottom: 1em;
    }

    .mb-padding-10 {
        padding: 10px 20px;
    }
    
    .mobile-mb-15 {
        margin-bottom: 15px;
        text-align: left;
    }
    
    .d-pb-25 {
        padding-bottom: unset;
    }

    .eye-container {
        padding-left: 5px !important;
        padding-right: 5px !important;
    }

    .footer-one__body .footer__section.-links {
        width: 50%;
    }

    .payment-link {
        flex-wrap: wrap;
        gap: 3px;
    }

    .mb-pb-20 {
        padding-bottom: 20px;
    }

    .footer-one__body {
        padding-bottom: 5.125em;
    }

    .desktop-block {
        display: none;
    }

    .mobile-block {
        display: block;
    }

    .h-46 {
        height: 46px;
        width: 120px;
        background-size: contain;
        background-repeat: no-repeat;
        background-position: center;
        /* border-radius: 50%; */
    }

    .px-130 {
        padding-left: 5px;
        padding-right: 5px;
    }

    .h-70 {
        height: 40px;
    }

    .footer-one__footer {
        padding: 0.7em 0;
    }

    .h-250 {
        height: 250px;
        object-fit: cover;
    }

    .py-60 {
        padding-top: unset;
        padding-bottom: 20px;
    }

    .mb-pt-15 {
        padding-top: 15px !important;
    }

    @if(Request::segment(1) == 'MyAffiliate' || 
        Request::segment(1) == 'MyCustomer')
        .profile-own-bg {
            padding: 100px 0;
        }
    @else
        .profile-own-bg {
            padding: 100px 0 140px 0;  
        }
    @endif

    .mb-bt-4 {
        border-top: 4px solid #f0f0f0;
        padding-top: 15px;
    }

    .f-20 {
        font-size: 16px;
    }

    .global_language {
        padding: 0.275rem 0rem;
        font-size: 11px;
    }

    .btn,
    .btn.hovered,
    .btn.-red {
        font-size: 11px;
        padding: 8px 18px;
    }

    .g-10 {
        padding-top: 10px;
    }

    .w-101 {
        width: 61px;
    }

    .affiliate_list .affiliate-list-area {
        padding: unset;
        margin: unset;
    }

    .affiliate_list .users-details-box {
        font-size: 15px;
    }

    .mb-pb-8 {
        padding-bottom: 8px;
    }

    .d-text-right {
        text-align: left;
        margin-bottom: 10px;
    }

    .navigation-sidebar .search-box form input {
        font-size: 1.1em;
        line-height: 1;
    }

    .d-padding-10 {
        padding: 8px 18px;
    }

    .right-top-items{
        right: 0px !important;
    }

    .hide-header-icon {
        display:none !important;
    }

    .show-language-icon{
        display:block !important;
    }
}

@media only screen and (max-width: 768px) {
    .h-500 {
        height: 250px;
    }

    .ba-container {
        width: 100%;
    }
}

@media only screen and (max-width: 602px) {
    .h-500 {
        height: 200px;
    }
}

@media only screen and (max-width: 480px) {
    .h-250 {
        height: 150px;
    }

    .voucher-image {
        height: 250px;
        padding: 0 0 10px 0;
    }
}

@media only screen and (max-width: 460px) {
    .eye-black-circle-new  {
        width: 30px;
        height: 30px;
        font-size: 9px;
    }

    .eye-green-best-seller {
        width: 30px;
        height: 30px;
        font-size: 6px;
    }
}

@media only screen and (max-width: 430px) {
    .h-500 {
        height: 160px;
    }

    .whatsapp-btn {
        bottom: 14%;
    }
}

@media only screen and (max-width: 385px) {
    .h-500 {
        height: 140px;
    }
}
</style>
@yield('css')

<body>    
    <div id="preloder">
        <div class="loader"></div>
    </div>
    <div class="loading-gif" style="background-image: url({{ asset('images/loading/09b24e31234507.564a1d23c07b4.gif') }}); "></div>
    <div id="app">
        <div class="super_container">
            @include('partial.frontend.header')
            @yield('content')
            @include('partial.frontend.footer')
        </div>
    </div>

    @if(!empty($data['web_setting']->contact_whatsapp))
    <a href="https://api.whatsapp.com/send?phone=6{{ $data['web_setting']->contact_whatsapp }}&source=&data=" target="_blank" class="whatsapp-btn">

    </a>
    @endif

    @if(!empty(Session::get('registered_account')))
    <div class="myPopUp" align="center">
        <img src="{{ asset('images/successgif.gif') }}" width="70%">
        <div class="" style="padding: 15px;">
            <p>
                <b>{{ isset($data['lang']['lang']['register_success']) ? $data['lang']['lang']['register_success'] :'注册成功！'}} </b>
                <br>
                {{ isset($data['lang']['lang']['verification_link_message']) ? $data['lang']['lang']['verification_link_message'] :'验证链接已发送至你的电子邮件。请检查你的垃圾邮件如果你没收到任何电邮。点击链接将验证你的户口。'}}
            </p>
            <button class="btn btn-success close-pop-up-message-btn">
                {{ isset($data['lang']['lang']['close']) ? $data['lang']['lang']['close'] :'关闭'}}
            </button>
        </div>
    </div>
    @endif

    @if(!empty(Session::get('registered_account_topup')))
    <div class="myPopUp" align="center">
        <img src="{{ asset('images/successgif.gif') }}" width="70%">
        <div class="" style="padding: 15px;">
            <p>
                {{ isset($data['lang']['lang']['register_success_message']) ? $data['lang']['lang']['register_success_message'] :'注册成功！等待管理员的批准.'}}
            </p>
            <button class="btn btn-success close-pop-up-message-btn">
                {{ isset($data['lang']['lang']['close']) ? $data['lang']['lang']['close'] :'关闭'}}
            </button>
        </div>
    </div>
    @endif

    <div class="modal fade" id="agree-logout-form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content" style="box-shadow: 0 2px 10px #bdbdbd; background-color: #fff;">
                <div class="modal-header">
                <h4>
                    {{ isset($data['lang']['lang']['notification']) ? $data['lang']['lang']['notification'] :'提示'}}
                </h4>
                </div>
                <div class="modal-body">
                {{ isset($data['lang']['lang']['first_purchase_notification']) ? $data['lang']['lang']['first_purchase_notification'] :'您已经完成了首次购买。 因此，你即将上升为代理等级。 您是否要重新登录为了显示您的邮寄状态与等级更新？'}}
                </div>
                <div class="modal-footer">
                    <div class="form-group">
                            <form method='POST' action="{{ route('logout') }}">
                                @csrf
                                <button class="btn btn-primary agree-logout-btn">
                                    {{ isset($data['lang']['lang']['confirm']) ? $data['lang']['lang']['confirm'] :'确定'}}
                                </button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ isset($data['lang']['lang']['cancel']) ? $data['lang']['lang']['cancel'] :'取消'}}</button>
                            </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if($data['bank_required'] == 1)
    <div class="modal fade bank_required" id="bank_required" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="background-color: #fff;">
                <form method="POST" action="{{ route('bank_account_save') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">
                            {{ isset($data['lang']['lang']['attention_create_bank']) ? $data['lang']['lang']['attention_create_bank'] :'注意: 您需要创建银行.'}}
                        </h5>
                        <a href="#" class="close-modal">
                            x
                        </a>
                    </div>
                    <div class="modal-body">
                        @if($errors->any())
                            <div class="alert alert-danger">{!! implode('<br/>', $errors->all(':message')) !!}</div>
                        @endif
                        <div class="form-group">
                            <label>{{ isset($data['lang']['lang']['bank_name']) ? $data['lang']['lang']['bank_name'] :'银行名字'}} <span class="important-text">*</span></label>
                            <select class="form-control" name="bank_name" style="height: auto;">
                                <option value="">{{ isset($data['lang']['lang']['select_bank']) ? $data['lang']['lang']['select_bank'] :'选择银行'}}</option>
                                @foreach($data['banks'] as $top_banks)
                                <option value="{{ $top_banks->bank_name }}">
                                    {{ $top_banks->bank_name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>{{ isset($data['lang']['lang']['bank_holder']) ? $data['lang']['lang']['bank_holder'] :'银行持有人'}} <span class="important-text">*</span></label>
                            <input type="text" class="form-control required-feild" name="bank_holder_name" 
                                   value="{{ Auth::guard($data['userGuardRole'])->user()->f_name }} {{ Auth::guard($data['userGuardRole'])->user()->l_name }}" placeholder="{{ isset($data['lang']['lang']['bank_holder']) ? $data['lang']['lang']['bank_holder'] :'银行持有人'}}" readonly>
                        </div>

                        <div class="form-group">
                            <label>{{ isset($data['lang']['lang']['bank_account']) ? $data['lang']['lang']['bank_account'] :'银行户口'}} <span class="important-text">*</span></label>
                            <input type="text" class="form-control required-feild" name="bank_account" value="{{ isset($bank) ? $bank->bank_account : old('bank_account') }}" placeholder="{{ isset($data['lang']['lang']['bank_account']) ? $data['lang']['lang']['bank_account'] :'银行户口'}}" onkeypress="return isNumberKey(event)">
                        </div>
                        <div class="form-group">
                            <b id="error-message" class="important-text"></b>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary set_button set_text" data-dismiss="modal">{{ isset($data['lang']['lang']['close']) ? $data['lang']['lang']['close'] :'关闭'}}</button>
                        <button class="btn btn-primary set_button set_text">{{ isset($data['lang']['lang']['submit']) ? $data['lang']['lang']['submit'] :'提交'}}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    @if(session('status') == 1)
    <div class="modal fade agent_downgrade_notification" id="agent_downgrade_notification" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="background-color: #fff;">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel" style="margin-left: auto; margin-right: auto;">
                        {{ isset($data['lang']['lang']['attention']) ? $data['lang']['lang']['attention'] :'注意'}}
                    </h5>
                    <a href="#" class="close-modal">
                        x
                    </a>
                </div>
                <div class="modal-body">
                    <div class="form-group" style="text-align: center;">
                        {{ isset($data['lang']['lang']['unable_register_customer']) ? $data['lang']['lang']['unable_register_customer'] :'此代理暂时无法招募顾客'}}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ isset($data['lang']['lang']['close']) ? $data['lang']['lang']['close'] :'关闭'}}</button>
                </div>
            </div>
        </div>
    </div>
    @endif


    <div class="modal fade" id="downgrade-form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content" style="box-shadow: 0 2px 10px #bdbdbd; background-color: #fff;">
                <div class="modal-header">
                <h4>
                    {{ isset($data['lang']['lang']['notification']) ? $data['lang']['lang']['notification'] :'提示'}}
                </h4>
                </div>
                <div class="modal-body">
                    {{ isset($data['lang']['lang']['downgrade_agent_prompt']) ? $data['lang']['lang']['downgrade_agent_prompt'] :'由于你维持一个月没任何消费，你已失去代理身份和福利。如果你想获得回去代理身份和福利，请重新登录以及购买任何产品。'}}
                </div>
                <div class="modal-footer">
                    <div class="form-group row">
                        <div class="col-md-4 offset-md-7">
                            <form method='POST' action="{{ route('logout') }}">
                                @csrf
                                <button class="btn btn-primary agree-logout-btn">
                                    {{ isset($data['lang']['lang']['confirm']) ? $data['lang']['lang']['confirm'] :'确定'}}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="upgrade-form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content" style="box-shadow: 0 2px 10px #bdbdbd; background-color: #fff;">
                <div class="modal-header">
                <h4>
                    {{ isset($data['lang']['lang']['notification']) ? $data['lang']['lang']['notification'] :'提示'}}
                </h4>
                </div>
                <div class="modal-body">
                    {{ isset($data['lang']['lang']['returning_agent_prompt']) ? $data['lang']['lang']['returning_agent_prompt'] :'欢迎回来，你已获得回去所有代理福利与身份，请重新登录即方便系统更新你的资料。'}}
                </div>
                <div class="form-group row" style="margin-bottom: 1rem;">
                    <div class="col-md-4 offset-md-8">
                        <form method='POST' action="{{ route('logout') }}">
                            @csrf
                            <button class="btn btn-primary agree-logout-btn">
                                {{ isset($data['lang']['lang']['confirm']) ? $data['lang']['lang']['confirm'] :'确定'}}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script src="{{ asset('frontend/assets/js/jquery-3.5.1.min.js') }}"></script>
<script src="{{ asset('frontend/assets/js/parallax.min.js') }}"></script>
<script src="{{ asset('frontend/assets/js/slick.min.js') }}"></script>
<script src="{{ asset('frontend/assets/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('frontend/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('frontend/assets/js/bootstrap-drawer.min.js') }}"></script>
<script src="{{ asset('frontend/assets/js/jquery.countdown.min.js') }}"></script>
<script src="{{ asset('frontend/assets/js/main.min.js') }}"></script>
<script src="{{ asset('frontend/js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('frontend/thumbnail-zoom/scripts/zoom-image.js') }}"></script>
<script src="{{ asset('frontend/thumbnail-zoom/scripts/main.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>


<script src="{{ asset('frontend/assets/js/swipe.js') }}"></script>

<script src="{{ asset('assets/js/tree.min.js') }}"></script>


</body>

@toastr_js
@toastr_render
@yield('js')
<script type="text/javascript">
    $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function changeLanguage(value){
        var d = new Date();
        d.setTime(d.getTime() + (10*24*60*60*1000));
        var expires = "expires=" + d.toUTCString();
        document.cookie = 'global_language' + "=" + value + ";" + expires + ";path=/";
        location.reload();
    }


    function isNumberKey(evt)
    {
      var charCode = (evt.which) ? evt.which : evt.keyCode;
      if (charCode != 46 && charCode > 31 
        && (charCode < 48 || charCode > 57))
         return false;

      return true;
    }

    function isAlphabetKey(evt)
    {
      var charCode = (evt.which) ? evt.which : evt.keyCode;
      if ((charCode < 65 || charCode > 90) && (charCode < 97 || charCode > 122))
        return false;

      return true;
    }

    $('.close-modal').click(function(e){
        e.preventDefault();

        var ele = $(this);

        ele.closest('.modal').modal('toggle');
    });

    // $(document).ready( function () {
    //     $('#picker').dateTimePicker();
    //     // $('#picker-no-time').dateTimePicker({ showTime: false, dateFormat: 'DD/MM/YYYY', title: 'Select Date'});
    // });

    $(".featured-slide").owlCarousel({
        loop: true,
        margin: 0,
        items: 4,
        dots: true,
        nav: true,
        navText: ['<i class="fa fa-angle-left fa-2x" aria-hidden="true"></i>', '<i class="fa fa-angle-right fa-2x" aria-hidden="true"></i>'],
        animateOut: 'fadeOut',
        animateIn: 'fadeIn',
        smartSpeed: 1200,
        autoHeight: false,
        autoplay: true,
        responsive:{
            0:{
                items: 1
            },
            600:{
                items: 2
            },
            1000:{
                items: 4
            }
        }
    });

    $(".partner-slide").owlCarousel({
        loop: true,
        margin: 0,
        items: 2,
        dots: false,
        nav: true,
        navText: ["<span class='arrow_left'><span/>", "<span class='arrow_right'><span/>"],
        animateOut: 'fadeOut',
        animateIn: 'fadeIn',
        smartSpeed: 1200,
        autoHeight: false,
        autoplay: true,
        responsive:{
            0:{
                items: 1
            },
            600:{
                items: 2
            },
            1000:{
                items: 2
            }
        }
    });

    $(".insta-slide").owlCarousel({
        loop: true,
        margin: 0,
        items: 5,
        dots: false,
        nav: true,
        navText: ["<span class='arrow_left'><span/>", "<span class='arrow_right'><span/>"],
        animateOut: 'fadeOut',
        animateIn: 'fadeIn',
        smartSpeed: 1200,
        autoHeight: false,
        autoplay: true,
        responsive:{
            0:{
                items: 1
            },
            600:{
                items: 2
            },
            1000:{
                items: 5
            }
        }
    });

</script>
@if($data['bank_required'] == 1)
<script type="text/javascript">
    //open required bank modal

    $('.bank_required').modal('toggle');
</script>
@endif

@if(session('status') == 1)
    <script type="text/javascript">
        $('.agent_downgrade_notification').modal('toggle');
    </script>
@endif

<script type="text/javascript">
    $( ".modal" ).on('shown.bs.modal', function(){
        $('.navbar.navbar-expand-lg').removeClass('fast');
    });
</script>
<!-- <script type="text/javascript">
    $(window).scroll(function(){
        var sticky = $('.sticky'),
        scroll = $(window).scrollTop();

        if (scroll >= 100) sticky.addClass('fixed');
            else sticky.removeClass('fixed');
});
</script> -->
@if(isset(Auth::guard($data['userGuardRole'])->user()->upgraded) && Auth::guard($data['userGuardRole'])->user()->upgraded == 1)
<script type="text/javascript">
    $('#agree-logout-form').modal('toggle');
</script>
@endif

@if(isset(Auth::guard($data['userGuardRole'])->user()->status) && Auth::guard($data['userGuardRole'])->user()->status == 55)
    @if(isset(Auth::guard($data['userGuardRole'])->user()->downgrade_notification) && Auth::guard($data['userGuardRole'])->user()->downgrade_notification == 1)
        <script type="text/javascript">
            $('#downgrade-form').modal('toggle');
        </script>
    @endif
@endif

@if(isset(Auth::guard($data['userGuardRole'])->user()->status) && Auth::guard($data['userGuardRole'])->user()->status == 3)
    @if(isset(Auth::guard($data['userGuardRole'])->user()->relegated_from_agent))
        <script type="text/javascript">
            $('#upgrade-form').modal('toggle');
        </script>
    @endif
@endif

<script type="text/javascript">
    function IsEmail(email) {
        var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if(!regex.test(email)) {
            return false;
        }else{
            return true;
        }
    }

    $('.sub-menu-title').click(function(e){
        e.preventDefault();
        var ele = $(this);

        ele.closest('.sub-menu-parent').find('.sub-menu-child').slideToggle();
    });

    $('.top-profile-btn').click(function(e){
        e.preventDefault();

        $('.right-top-items').toggle();
        $('.header-language-lists').hide();

        // window.location.href = "{{ route('profile') }}";
    });

    $('.language-header-btn').click(function(e){
        e.preventDefault();

        $('.header-language-lists').toggle();
        $('.right-top-items').hide();

        // window.location.href = "{{ route('profile') }}";
    });

    $('.top-cart-btn').click(function(e){
        e.preventDefault();

        window.location.href = "{{ route('checkout') }}";
    });

    $('.top-cart-mall-btn').click(function(e){
        e.preventDefault();

        window.location.href = "{{ route('checkout_mall') }}";
    });

    $( "#exampleModal" ).on('shown.bs.modal', function(){
        // alert("I want this to appear after the modal has opened!");
        $('.bottom-menu-bar').hide();
        $('.whatsapp-btn').hide();
    });

    $( "#exampleModal" ).on('hidden.bs.modal', function(){
        // alert("I want this to appear after the modal has opened!");
        $('.bottom-menu-bar').show();
        $('.whatsapp-btn').show();
    });

    $(document).on("scroll", function() {
        var pageTop = $(document).scrollTop();
        var pageBottom = pageTop + $(window).height();
        var tags = $(".introduction-seven__wrapper__image");
        
        if($( window ).width() > 768){
            var srcolled = 2000;
        }else{
            var srcolled = 1000;
        }

        for (var i = 0; i < tags.length; i++) {
            var tag = tags[i];

            if ($(this).scrollTop() >= srcolled) {
                $(tag).addClass("visible");
            }
        }
    });

    // Get the current URL.
    const currentURL = window.location.href;

    if (currentURL.includes("About") || currentURL.includes("ECommerce") || currentURL.includes("Subcategory") || currentURL.includes("Details")) {
        document.body.classList.add("assigned-page");
    }
</script>

<script type="text/javascript">
    $(window).on('load', function () {
        $('.loading-message').fadeIn(100);

        $('.js-custom-text-carousel').slick({
            speed: 1000,
            autoplay: true,
            autoplaySpeed: 8000,
            fade: true,
            arrows: false
        });
        
        $('.loading-message').css('display', 'none');
        $('.hdr-topline.js-hdr-top').addClass('show');
    });
</script>
</html>
