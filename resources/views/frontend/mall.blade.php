@extends('layouts.app')
@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<ol class="breadcrumb">
			  <li><a href="{{ route('home') }}">Home</a></li>
			  <li class="active">Result</li>
			</ol>
		</div>
	</div>
	<div class="row">
		<div class="col-md-3">
			@if(!empty($categories))
			<div class="container-filter-bar">
				<b>Related Category</b>
				<form method="GET" action="{{ route('listing') }}" id="category-form">
					<input type="hidden" name="category" value="{{ !empty(request('category')) ? request('category') : '' }}">
					<input type="hidden" name="result" value="{{ !empty(request('result')) ? request('result') : '' }}">
					<ul class="related-category">
						@foreach($categories as $category)
						<li>
							<a href="#" data-id="{{ $category->category_name }}" class="search-category">
								{{ $category->category_name }}
							</a>
						</li>
						@endforeach
					</ul>					
				</form>
			</div>
			@endif

			@if(!$brands->isEmpty())
			
			<div class="container-filter-bar">
				<b>Brand</b>
				<form method="GET" action="{{ route('listing') }}" id="brand-form">
					<input type="hidden" name="brand" value="{{ !empty(request('brand')) ? request('brand') : '' }}">
					<input type="hidden" name="result" value="{{ !empty(request('result')) ? request('result') : '' }}">
					<ul class="brand-filter">
						@foreach($brands as $brand)
						<li>
							<a href="#" data-id="{{ $brand }}" class="search-brand">
								{{ $brand->brand_name }}
							</a>
						</li>
						@endforeach
					</ul>
				</form>
			</div>
			@endif

			<div class="container-filter-bar">
				<b>Point</b>
				<div class="price-filter">
					<form method="GET" action="{{ route('listing') }}" id="price-form">
						<input type="hidden" name="result" value="{{ !empty(request('result')) ? request('result') : '' }}">
						<input type="text" class="form-control" placeholder="Min Point" name="from" value="{{ !empty(request('from')) ? request('from') : '' }}">
							-
						<input type="text" class="form-control" placeholder="Max Point" name="to" value="{{ !empty(request('to')) ? request('to') : '' }}">
						<br>
						<button class="btn btn-primary btn-block btn-search-price-filter set_button set_text" type="submit">
							Apply
						</button>
					</form>
				</div>
			</div>

			<!-- <div class="container-filter-bar">
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
			</div> -->
		</div>
		<div class="col-md-9">
			<div class="web-product-listing-list">
                <div class="form-group">
                    <div class="row">
                    	@if(!$products->isEmpty())
                        @foreach($products as $product)
                        <div class="col-md-4">
                            <div class="thumbnail product-thumbnail">
                                <a href="{{ route('details', md5($product->id)) }}">
                                	@php
                                		if(!empty($product->image)){
											$image = File::exists($product->image) ? $product->image : asset('images/no-image-available-icon-6.jpg');
                                		}else{
                                			$image = asset('images/no-image-available-icon-6.jpg');
                                		}
									@endphp
                                    <div class="thumbnail-image" style="background-image: url('{{ asset($image) }}');"></div>
                                    <div class="caption">
                                        <div class="product-name">
                                            {{ $product->product_name }}
                                        </div>
                                        <div class="price">
                                        	@if(Auth::guard('merchant')->check() || Auth::guard('admin')->check())
	                                            @if($product->agent_special_price)
	                                                <span class="currency"></span>
	                                                <span class="amount">
	                                                	{{ $product->agent_special_price }} point
	                                                </span>
	                                                <strike class="actual_price">
	                                                    {{ $product->agent_price }} point
	                                                </strike>
	                                            @else
	                                                 {{ $product->price }} point
	                                            @endif
	                                        @else
	                                        	@if($product->special_price)
	                                                <span class="currency"></span>
	                                                <span class="amount">
	                                                	{{ $product->special_price }} point
	                                                </span>
	                                                <strike class="actual_price">
	                                                    {{ $product->price }} point
	                                                </strike>
	                                            @else
	                                                 {{ $product->price }} point
	                                            @endif
	                                        @endif
                                        </div>

                                        <!-- <div class="rateYo"></div> <div class="total-buyer">(3)</div> -->
                                    </div>
                                </a>
                            </div>
                        </div>
                        @endforeach
                        @else
                        <div class="form-group" align="center">
                        	Search No Result 
                        </div>
                        <div class="form-group" align="center">
                        	We're sorry. We cannot find any matches for your search term.
                        </div>
                        <div class="form-group" align="center">
                        	<i class="fa fa-search fa-5x"></i>
                        </div>
                        @endif
                    </div>
                </div>
			</div>
			<div class="form-group">
				{{ $products->links() }}
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

    $('.search-brand').click( function(){
    	$('input[name="brand"]').val($(this).data('id'));
    	$('#brand-form').submit();
    });

    $('.search-category').click( function(){
    	$('input[name="category"]').val($(this).data('id'));
    	$('#category-form').submit();
    });

</script>
@endsection