@extends('layouts.app')
@section('css')
<style type="text/css">
	.full img{
		width: 100%;
	}

	.full{
		margin-bottom: 10px;
	}
</style>
@endsection
@section('content')
<div class="container details-page">
	<div class="row">
		<div class="col-md-12">
			<ol class="breadcrumb">
			  <li><a href="#">Motors</a></li>
			  <li><a href="#">Automotive</a></li>
			  <li><a href="#">Electronics</a></li>
			  <li><a href="#">Video</a></li>
			  <li><a href="#">In-Dash DVD & Video Receivers</a></li>
			  <li class="active">Perodua Alza 2009-2019 10 Player + Casing (Set) IPS Screen</li>
			</ol>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="details-box">
				<div class="row">
					<div class="col-md-5">
						<div class="gallery">
							<div class="full"> 
								<!-- first image is viewable to start --> 
								<img src="{{ asset('fancybox/images/top1large.jpg') }}" /> 
							</div>
							<div class="previews"> 
								<a href="javaScript:void(0);" class="selected" data-full="{{ asset('fancybox/images/top1large.jpg') }}">
									<img src="{{ asset('fancybox/images/top1small.jpg') }}" />
								</a> 
								<a href="javaScript:void(0);" data-full="{{ asset('fancybox/images/top2large.jpg') }}">
									<img src="{{ asset('fancybox/images/top2small.jpg') }}" />
								</a> 
								<a href="javaScript:void(0);" data-full="{{ asset('fancybox/images/top3large.jpg') }}">
									<img src="{{ asset('fancybox/images/top3small.jpg') }}" />
								</a> 
								<a href="javaScript:void(0);" data-full="{{ asset('fancybox/images/top4large.jpg') }}">
									<img src="{{ asset('fancybox/images/top4small.jpg') }}" />
								</a> 
								<a href="javaScript:void(0);" data-full="{{ asset('fancybox/images/top5large.jpg') }}">
									<img src="{{ asset('fancybox/images/top5small.jpg') }}" />
								</a> 
								<a href="javaScript:void(0);" data-full="{{ asset('fancybox/images/top5large.jpg') }}">
									<img src="{{ asset('fancybox/images/top5small.jpg') }}" />
								</a> 
								<a href="javaScript:void(0);" data-full="{{ asset('fancybox/images/top5large.jpg') }}">
									<img src="{{ asset('fancybox/images/top5small.jpg') }}" />
								</a> 
								<a href="javaScript:void(0);" data-full="{{ asset('fancybox/images/top5large.jpg') }}">
									<img src="{{ asset('fancybox/images/top5small.jpg') }}" />
								</a> 
							</div>
						</div>
					</div>
					<div class="col-md-7">
						<div class="form-group">
							<b>(BACKORDER) DEE CHECKERED LONG SLEEVE TOP IN RED</b>
						</div>

						<div class="form-group">
							<b>Product SKU: A002573</b>
						</div>

						<div class="form-group">
							<b>RM 53.00</b>
						</div>

						<div class="form-group">
							<b>Quantity: </b>
						</div>

						<div class="form-group">
							<a href="#" class="btn btn-primary add-to-cart-button">
								ADD TO CART
							</a>
						</div>
						<hr>
						<div class="form-group">
							<h4>Details</h4>
							<ul>
								<li>
									Made of polyester material
								</li>
								<li>
									Non-sheer
								</li>
							</ul>

							<div class="form-group">
								<b>Measurements: </b>
							</div>

							<div class="form-group">
								<p>
									<b>Free</b> |  Length (40cm) Bust (97cm) Shoulder (36cm) Sleeve (57cm) 
								</p>
							</div>

							<div class="form-group">
								<b>Model's size: </b>
							</div>

							<div class="form-group">
								<p>Bust 31inches , Waist 23inches, Hip 31inches / Height 164cm / Weight 42kg</p>
							</div>
							
							<div class="form-group">
								<b>Backorder Status: </b>
							</div>
							
							<div class="form-group">
								<p><b>Update Date:</b> 22nd Oct 2019</p>
							</div>
							<div class="form-group">
								<p class="important-text">Batch 1</p>
								<p class="important-text">Stock are estimate to arrive by 15 Nov 2019</p>
							</div>
							
							<div class="form-group">
								<p class="important-text">
									*Please note BOs are not instocks. Items will arrive at a later date as indicated above. Please make a purchase only if you are alright with the waiting time.
								</p>
							</div>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<hr>
	<div class="row">
		<div class="col-md-12">
			<h3 align="center">Related Product</h3>
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
		</div>
	</div>
</div>
@endsection

@section('js')
<script type="text/javascript">
	
  $(document).ready(function(){
    $('a').click(function(){
      var largeImage = $(this).attr('data-full');
      $('.selected').removeClass();
      $(this).addClass('selected');
      $('.full img').hide();
      $('.full img').attr('src', largeImage);
      $('.full img').fadeIn();


    }); // closing the listening on a click
    $('.full img').on('click', function(){
      var modalImage = $(this).attr('src');
      $.fancybox.open(modalImage);
    });
  }); //closing our doc ready
  
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-36251023-1']);
  _gaq.push(['_setDomainName', 'jqueryscript.net']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>
@endsection