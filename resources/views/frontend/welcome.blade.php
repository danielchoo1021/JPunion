@extends('layouts.app')

@section('content')
<div class="web-home-header-slider">
    <div class="container">
        <div class="row">
            <div class="col-sm-8">
                <div id="myCarousel" class="carousel slide" data-ride="carousel">
                    <!-- Indicators -->
                    <ol class="carousel-indicators">
                          <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                          <li data-target="#myCarousel" data-slide-to="1"></li>
                          <li data-target="#myCarousel" data-slide-to="2"></li>
                    </ol>

                    <!-- Wrapper for slides -->
                    <div class="carousel-inner" style="height: 300px;">
                        <div class="item active" style="height: 300px;">
                            <img src="{{ asset('images/banner/ejkNT9ftNKmyg3GeNGuB6f.jpg') }}" alt="Los Angeles" style="width:100%; height: 300px;">
                        </div>

                        <div class="item">
                            <img src="{{ asset('images/banner/aa4716_36ca1edca67946aa87a04705a02486a7_mv2.png') }}" alt="Chicago" style="width:100%; height: 300px;">
                        </div>
                        
                        <div class="item">
                            <img src="{{ asset('images/banner/Caudalie-tout-1920x1080.jpg') }}" alt="New york" style="width:100%; height: 300px;">
                        </div>
                    </div>

                    <!-- Left and right controls -->
                    <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                        <span class="glyphicon glyphicon-chevron-left"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="right carousel-control" href="#myCarousel" data-slide="next">
                        <span class="glyphicon glyphicon-chevron-right"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group ads-side-image">
                    <img src="{{ asset('images/banner/e5e448d549d767f7e082e7667049b635.jpg') }}" style="width: 100%; height: 142px;">
                </div>
                <div class="form-group ads-side-image">
                    <img src="{{ asset('images/banner/1920x1080.jpg') }}" style="width: 100%; height: 142px;">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mobile-home-header-slider">
    <div class="form-group">
        <div id="mobile-myCarousel" class="carousel slide" data-ride="carousel">
            <!-- Indicators -->
            <ol class="carousel-indicators">
                  <li data-target="#mobile-myCarousel" data-slide-to="0" class="active"></li>
                  <li data-target="#mobile-myCarousel" data-slide-to="1"></li>
                  <li data-target="#mobile-myCarousel" data-slide-to="2"></li>
            </ol>

            <!-- Wrapper for slides -->
            <div class="carousel-inner">
                <div class="item active">
                    <img src="{{ asset('images/banner/e5e448d549d767f7e082e7667049b635.jpg') }}" alt="Los Angeles">
                </div>

                <div class="item">
                    <img src="{{ asset('images/banner/aa4716_36ca1edca67946aa87a04705a02486a7_mv2.png') }}" alt="Chicago">
                </div>
                
                <div class="item">
                    <img src="{{ asset('images/banner/Caudalie-tout-1920x1080.jpg') }}" alt="New york">
                </div>
            </div>

            <!-- Left and right controls -->
            <a class="left carousel-control" href="#mobile-myCarousel" data-slide="prev">
                <span class="glyphicon glyphicon-chevron-left"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="right carousel-control" href="#mobile-myCarousel" data-slide="next">
                <span class="glyphicon glyphicon-chevron-right"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>
</div>

<div class="web-home-listing">
    <div class="form-group">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 widget-container-col" id="widget-container-col-2">
                    <div class="widget-box widget-color-blue" id="widget-box-2">
                        <div class="widget-header">
                            <h5 class="widget-title bigger lighter">
                                CATEGORIES
                            </h5>
                        </div>

                        <div class="widget-body">
                            <div class="widget-main no-padding">
                                <table class="table table-bordered  category-table">
                                    <tbody>
                                        <tr align="center">
                                            <td class="">
                                                <a href="{{ route('listing') }}">
                                                    <img src="{{ asset('images/category/il_340x270.1758338797_iqy1.jpg') }}" class="categories-img">
                                                    <br>
                                                    Men's Clothing
                                                </a>

                                            </td>

                                            <td class="">
                                                <a href="{{ route('listing') }}">
                                                    <img src="{{ asset('images/category/2118Hay2018_TH.jpg') }}" class="categories-img">
                                                    <br>
                                                    Table
                                                </a>

                                            </td>

                                            <td class="">
                                                <a href="{{ route('listing') }}">
                                                    <img src="{{ asset('images/category/41EF8zWZdML._SY450_.jpg') }}" class="categories-img">
                                                    <br>
                                                    Gaming Chair
                                                </a>

                                            </td>

                                            <td class="">
                                                <a href="{{ route('listing') }}">
                                                    <img src="{{ asset('images/category/ahmed_zohaib_leather_belt_for_men_black_1.jpg') }}" class="categories-img">
                                                    <br>
                                                    Belt
                                                </a>

                                            </td>

                                            <td class="">
                                                <a href="{{ route('listing') }}">
                                                    <img src="{{ asset('images/category/13528_23950_42566.jpg') }}" class="categories-img">
                                                    <br>
                                                    Women's Bags
                                                </a>

                                            </td>

                                            <td class="">
                                                <a href="{{ route('listing') }}">
                                                    <img src="{{ asset('images/category/9c-2.jpg') }}" class="categories-img">
                                                    <br>
                                                    High Heels
                                                </a>

                                            </td>
                                        </tr>

                                        <tr align="center">
                                            <td class="">
                                                <a href="{{ route('listing') }}">
                                                    <img src="{{ asset('images/category/81b0Lkw-HgL._SR500,500_.jpg') }}" class="categories-img">
                                                    <br>
                                                    Gaming Mouse
                                                </a>
                                            </td>

                                            <td class="">
                                                <a href="{{ route('listing') }}">
                                                    <img src="{{ asset('images/category/91PlJRE587L._SR500,500_.jpg') }}" class="categories-img">
                                                    <br>
                                                    Gaming Keyboard
                                                </a>
                                            </td>

                                            <td class="">
                                                <a href="{{ route('listing') }}">
                                                    <img src="{{ asset('images/category/akne-maske-300x300@2x.jpg') }}" class="categories-img">
                                                    <br>
                                                    Skin Care
                                                </a>
                                            </td>

                                            <td class="">
                                                <a href="{{ route('listing') }}">
                                                    <img src="{{ asset('images/category/Shower-Curtain-Multi-function-70X70-Inch-hot-Bundle-Heart-Black-Rose-White-Red-Antibacterial-waterproof-Polyester.jpg_640x640.jpg') }}" class="categories-img">
                                                    <br>
                                                    Curtain
                                                </a>
                                            </td>

                                            <td class="">
                                                <a href="{{ route('listing') }}">
                                                    <img src="{{ asset('images/category/99b6b9eb46d5d11fdd7966567333c038.jpg') }}" class="categories-img">
                                                    <br>
                                                    Car Accessories
                                                </a>
                                            </td>

                                            <td class="">
                                                <a href="{{ route('listing') }}">
                                                    <img src="{{ asset('images/category/32fe040c697dab0fd00351c9309338e9_tn.jpg') }}" class="categories-img">
                                                    <br>
                                                    TV
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div><!-- /.span -->
            </div>
        </div>
    </div>
</div>

<div class="web-product-listing">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="thumbnail product-thumbnail">
                                <a href="{{ route('details') }}">
                                    <div class="thumbnail-image" style="background-image: url('{{ asset('images/gallery/11594543_480x.jpg') }}');"></div>
                                    <div class="caption">
                                        <div class="product-name">
                                            (BACKORDER) OCT SLIM BLACK SUIT
                                        </div>
                                        <div class="price">
                                            <span class="currency">RM</span> 
                                            <span class="amount">53.00</span>
                                            <strike class="actual_price">
                                                RM 73.90
                                            </strike>
                                        </div>

                                        <div class="rateYo"></div> <div class="total-buyer">(3)</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="thumbnail product-thumbnail">
                                <a href="{{ route('details') }}">
                                    <div class="thumbnail-image" style="background-image: url('{{ asset('images/gallery/11594584_480x.jpg') }}');"></div>
                                    <div class="caption">
                                        <div class="product-name">
                                            (BACKORDER) OCT BABY PINK SET
                                        </div>
                                        <div class="price">
                                            <span class="currency">RM</span> 
                                            <span class="amount">53.00</span>
                                            <strike class="actual_price">
                                                RM 73.90
                                            </strike>
                                        </div>

                                        <div class="rateYo"></div> <div class="total-buyer">(3)</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="thumbnail product-thumbnail">
                                <a href="{{ route('details') }}">
                                    <div class="thumbnail-image" style="background-image: url('{{ asset('images/gallery/11643059_480x.jpg') }}');"></div>
                                    <div class="caption">
                                        <div class="product-name">
                                            (WAITING LIST) OCT EMBROIDERY WHITE TOP
                                        </div>
                                        <div class="price">
                                            <span class="currency">RM</span> 
                                            <span class="amount">53.00</span>
                                            <strike class="actual_price">
                                                RM 73.90
                                            </strike>
                                        </div>

                                        <div class="rateYo"></div> <div class="total-buyer">(3)</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="thumbnail product-thumbnail">
                                <a href="{{ route('details') }}">
                                    <div class="thumbnail-image" style="background-image: url('{{ asset('images/gallery/11643087_480x.jpg') }}');"></div>
                                    <div class="caption">
                                        <div class="product-name">
                                            OCT EMBROIDERY WORDING PANTS
                                        </div>
                                        <div class="price">
                                            <span class="currency">RM</span> 
                                            <span class="amount">53.00</span>
                                            <strike class="actual_price">
                                                RM 73.90
                                            </strike>
                                        </div>

                                        <div class="rateYo"></div> <div class="total-buyer">(3)</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="thumbnail product-thumbnail">
                                <a href="{{ route('details') }}">
                                    <div class="thumbnail-image" style="background-image: url('{{ asset('images/gallery/11643164_480x.jpg') }}');"></div>
                                    <div class="caption">
                                        <div class="product-name">
                                            OCT BLUE BLOUSE OUTER
                                        </div>
                                        <div class="price">
                                            <span class="currency">RM</span> 
                                            <span class="amount">53.00</span>
                                            <strike class="actual_price">
                                                RM 73.90
                                            </strike>
                                        </div>

                                        <div class="rateYo"></div> <div class="total-buyer">(3)</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="thumbnail product-thumbnail">
                                <a href="{{ route('details') }}">
                                    <div class="thumbnail-image" style="background-image: url('{{ asset('images/gallery/k00733.jpg') }}');"></div>
                                    <div class="caption">
                                        <div class="product-name">
                                            OCT EMBROIDERY WORDING PANTS
                                        </div>
                                        <div class="price">
                                            <span class="currency">RM</span> 
                                            <span class="amount">53.00</span>
                                            <strike class="actual_price">
                                                RM 73.90
                                            </strike>
                                        </div>

                                        <div class="rateYo"></div> <div class="total-buyer">(3)</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="thumbnail product-thumbnail">
                                <a href="{{ route('details') }}">
                                    <div class="thumbnail-image" style="background-image: url('{{ asset('images/gallery/k03136.jpg') }}');"></div>
                                    <div class="caption">
                                        <div class="product-name">
                                            (BACKORDER) DEE KNIT POLKA DOT SKIRT IN BABY LILAC
                                        </div>
                                        <div class="price">
                                            <span class="currency">RM</span> 
                                            <span class="amount">53.00</span>
                                            <strike class="actual_price">
                                                RM 73.90
                                            </strike>
                                        </div>

                                        <div class="rateYo"></div> <div class="total-buyer">(3)</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="thumbnail product-thumbnail">
                                <a href="{{ route('details') }}">
                                    <div class="thumbnail-image" style="background-image: url('{{ asset('images/gallery/k04029.jpg') }}');"></div>
                                    <div class="caption">
                                        <div class="product-name">
                                            (BACKORDER) DEE KOREA MICKEY PULLOVER IN GREY
                                        </div>
                                        <div class="price">
                                            <span class="currency">RM</span> 
                                            <span class="amount">53.00</span>
                                            <strike class="actual_price">
                                                RM 73.90
                                            </strike>
                                        </div>

                                        <div class="rateYo"></div> <div class="total-buyer">(3)</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="thumbnail product-thumbnail">
                                <a href="{{ route('details') }}">
                                    <div class="thumbnail-image" style="background-image: url('{{ asset('images/gallery/k06145.jpg') }}');"></div>
                                    <div class="caption">
                                        <div class="product-name">
                                            (BACKORDER) DEE KOREA CONTRAST SHIRT IN WHITE
                                        </div>
                                        <div class="price">
                                            <span class="currency">RM</span> 
                                            <span class="amount">53.00</span>
                                            <strike class="actual_price">
                                                RM 73.90
                                            </strike>
                                        </div>

                                        <div class="rateYo"></div> <div class="total-buyer">(3)</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script type="text/javascript">
    $(".rateYo").rateYo({
        rating: '3',
        starWidth: "10px",
        readOnly: true,
        halfStar: true,
    });
</script>
@endsection