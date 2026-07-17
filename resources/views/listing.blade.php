@extends('layouts.app')
@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<ol class="breadcrumb">
			  <li><a href="#">Home</a></li>
			  <li class="active">Result</li>
			</ol>
		</div>
	</div>
	<div class="row">
		<div class="col-md-3">
			<div class="container-filter-bar">
				<b>Related Category</b>
				<ul class="related-category">
					<li>
						<a href="#">In-Dash DVD & Video Receivers</a>
					</li>
					<li>
						<a href="#">Car Stereo Receivers</a>
					</li>
					<li>
						<a href="#">Exterior Deflectors & Shields</a>
					</li>
					<li>
						<a href="#">In-Dash Navigation</a>
					</li>
					<li>
						<a href="#">Digital Media Receivers</a>
					</li>
					<li>
						<a href="#">Car Dash Mounting Kits</a>
					</li>
				</ul>
			</div>

			<div class="container-filter-bar">
				<b>Brand</b>
				<ul class="brand-filter">
					<li>
						<a href="#">Leon</a>
					</li>
					<li>
						<a href="#">Murah King 2U</a>
					</li>
					<li>
						<a href="#">China OEM</a>
					</li>
					<li>
						<a href="#">Perodua</a>
					</li>
					<li>
						<a href="#">MM</a>
					</li>
					<li>
						<a href="#">Stapon</a>
					</li>
				</ul>
			</div>

			<div class="container-filter-bar">
				<b>Price</b>
				<div class="price-filter">
					<input type="text" class="form-control" placeholder="Min Price">
						-
					<input type="text" class="form-control" placeholder="Max Price">
					<br>
					<button class="btn btn-primary btn-block btn-search-price-filter">
						Apply
					</button>
				</div>
			</div>

			<div class="container-filter-bar">
				<b>Color</b>
				<ul class="color-filter">
					<li>
						<a href="#">Black</a>
					</li>
					<li>
						<a href="#">Silver</a>
					</li>
					<li>
						<a href="#">Red</a>
					</li>
					<li>
						<a href="#">Light Blue</a>
					</li>
					<li>
						<a href="#">Gradient</a>
					</li>
					<li>
						<a href="#">Green</a>
					</li>
				</ul>
			</div>
		</div>
		<div class="col-md-9">
			<div class="web-product-listing-list">
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-4">
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
                        <div class="col-md-4">
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
                        <div class="col-md-4">
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
                        <div class="col-md-4">
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
                        <div class="col-md-4">
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
                        <div class="col-md-4">
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
                        <div class="col-md-4">
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
                        <div class="col-md-4">
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
                        <div class="col-md-4">
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