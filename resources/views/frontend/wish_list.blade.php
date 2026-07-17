@extends('layouts.app')

@section('content')
@include('partial.frontend.profile_header')
<div class="profile-content pb-5">
	<div class="container">
		<div class="form-group">
			<div class="row">
				<div class="col-sm-12 myOrder-list">
					<div class="form-group container-box wishlist-box">
						@if(!$favourites->isEmpty())
						@foreach($favourites as $favourite)
						@php
						$image = (!empty($favourite->image)) ? $favourite->image : 'images/no-image-available-icon-6.jpg';
						@endphp
						<div class="form-group wish-row">
							<div class="row">
								<div class="col-sm-6" align="center">
									<div class="from-group">
										<div class="row">
											<div class="col-3">
												<a href="{{ route('details', md5($favourite->id)) }}">
													<img src="{{ asset($image) }}" style="width: 70px;">
												</a>
											</div>
											<div class="col-9" align="left">
												<div class="form-group product-details">
													<a href="{{ route('details', md5($favourite->id)) }}">
														{{ $favourite->product_name }}
													</a>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-sm-6">
									@if($stockBalance[$favourite->id] == 0)
									<span class="important-text">Not Available</span>
									<a href="#" class="btn btn-outline-danger btn-sm remove-wish-list pull-right set_button set_text" data-id="{{ $favourite->id }}">
										<i class="fa fa-trash"></i>
									</a>
									@else
									<div class="from-group" align="right">
										<a href="#" class="btn btn-outline-danger btn-sm remove-wish-list pull-right set_button set_text" data-id="{{ $favourite->id }}" >
											<i class="fa fa-trash"></i>
										</a>
									</div>
									@endif
								</div>
							</div>
						</div>
						<hr>
						@endforeach
						@else
						<div class="form-group" align="center">
							{{ isset($data['lang']['lang']['no_items']) ? $data['lang']['lang']['no_items'] :'No Items'}}
						</div>
						<div class="form-group" align="center">
							{{ isset($data['lang']['lang']['add_favouries_show_up_here']) ? $data['lang']['lang']['add_favouries_show_up_here'] :'Add your favourites to wishlist and they will show up here'}}
						</div>
						<div class="form-group" align="center">
							<a href="{{ route('listing') }}" class="btn btn-primary set_button set_text">
								{{ isset($data['lang']['lang']['continue_shopping']) ? $data['lang']['lang']['continue_shopping'] :'Continue Shopping'}}
							</a>
						</div>
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('js')
<script type="text/javascript">
$('.add-to-cart-button').click( function(e){
  	e.preventDefault();
  	$('.loading-gif').show();
  	var auth_check = '{{ Auth::check() }}';
  	
  	if(auth_check){
	  	var fd = new FormData();
	  	fd.append('product_id', $(this).data('id'));
	  	fd.append('quantity', '1');

	  	$.ajax({
	        url: '{{ route("AddToCart") }}',
	        type: 'post',
	        data: fd,
	        contentType: false,
	        processData: false,
	        success: function(response){
	        	// alert(response);
	        	// return false;
	        	$('.loading-gif').hide();
	        	if(response == 'quantity error'){
	        		toastr.error('Please Add Quantity At least 1');
	        		return false;
	        	}

	        	if(response == 'quantity exceed error'){
	        		toastr.error('Product Balance Quantity Not Enough');
	        		return false;
	        	}


	        	if(response == 'ok'){
	        		$.ajax({
				        url: '{{ route("CountCart") }}',
				        type: 'get',
				        success: function(response){
				        	$('.badge-cart').html(response);
				        	
				        }
				    });
	            	toastr.success('已加入购物车. <a href="{{ route("checkout") }}" class="view-cart-button pull-right"><i class="fa fa-shopping-cart"></i> 购物车</a>');
	            }else{
	            	toastr.error('Error Please Contact Admin');
	            }
	        },
	    });
  	}else{
  		window.location.href = "{{ route('login') }}";
  	}
});

$('.remove-wish-list').click( function(e){
	e.preventDefault();
  	
  	var ele = $(this);
	var auth_check = '{{ Auth::check() }}';
	  	
	if(auth_check){
  		var fd = new FormData();
	  	fd.append('product_id', ele.data('id'));
	  	if(confirm('产品将从我的收藏中删除'))
	  	$('.loading-gif').show();
	  	$.ajax({
	        url: '{{ route("remove_wish") }}',
	        type: 'post',
	        data: fd,
	        contentType: false,
	        processData: false,
	        success: function(response){
	        	$('.loading-gif').hide();
		        ele.closest('.wish-row').remove();
		        toastr.info('产品已从我的收藏中删除');
		        if($('.wish-row').length == 0){
		        	$('.wishlist-box').html('<div class="form-group" align="center">\
												尚无收藏。\
											</div>\
											<div class="form-group" align="center">\
												将您的收藏夹添加到愿望清单，它们将在此处显示\
											</div>\
											<div class="form-group" align="center">\
												<a href="{{ route("listing") }}" class="btn btn-primary">\
													继续购物\
												</a>\
											</div>');
		        }
	        }
	    });
  	}else{
  		window.location.href = "{{ route('login') }}";
  	}
});
</script>
@endsection