@extends('layouts.app')

@section('content')
<div class="profile-own-bg">
	<div class="personal-header-info">
			<div class="container">
				<div class="row">
					<div class="col-4" align="left">
						<a href="{{ route('home') }}">
							<p style="color: white;"><i class="fa fa-chevron-left"></i> Home</p>
						</a>
					</div>
					<div class="col-4" align="left">
						<p align="center" class="header-title">My Account</p>
					</div>
					<div class="col-4" align="right">
						<a href="{{ route('my_setting') }}" class="setting-btn">
							<i class="fa fa-cog" style="font-size: 20px;"></i>
						</a>
					</div>
				</div>
			</div>

		<div class="container">
			<div class="form-group">
				<div class="row">
					<div class="col-2">
						<a href="{{ route('profile') }}">
							@if(!empty(Auth::user()->profile_logo))
								<!-- <img src="{{ asset(Auth::user()->profile_logo) }}" width="50" class="profile-logo"> -->
								<div style="background-image: url({{ asset(Auth::user()->profile_logo) }}); width: 50px; height: 50px; border-radius: 100%; background-size: 100%; background-position: center; background-repeat: no-repeat;"></div>
							@else
								<img src="{{ asset('images/images.png') }}" width="50" class="profile-logo">
							@endif							
						</a>
					</div>
					<div class="col-6">
						<a href="{{ route('profile') }}">
							&nbsp;
							<b class="profile-name">{{ Auth::user()->f_name }} {{ Auth::user()->l_name }}</b>
							<br>
							&nbsp;
							<small class="profile-code">Code: {{ Auth::user()->code }}</small>
							<br>
							&nbsp;
							<small class="profile-level">Level: {{ !empty($lvl) ? $lvl : ' - ' }}</small>
							
						</a>
					</div>
					<!-- <div class="col-xs-4" align="right">
						<a href="#">
							<i class="fa fa-pencil"></i> Edit Profile
						</a>

					</div> -->
				</div>
			</div>
			
			@if(Auth::guard('merchant')->check())
				<div class="form-group container-box sl-personal-header">
					<div class="row">
						<div class="col-6" align="center">
							<a href="{{ route('myqrcode') }}">
								<img src="{{ asset('images/qrcode.png') }}" width="30">
								<br>
								<span class="profile-word">My QRcode</span>
							</a>
						</div>

						<!-- <div class="col-4" align="center">
							<a href="{{ route('MyAffiliate', Auth::user()->code) }}">
								<img src="{{ asset('images/profile/585e4d1ccb11b227491c339b.png') }}" width="30">
								<br>
								<span class="profile-word">My Team</span>
							</a>
						</div> -->

						<div class="col-6" align="center">
							<a href="{{ route('wallet') }}">
								<img src="{{ asset('images/profile/c3286d4d32fa90ebcf09b488654612b9-wallet-icon-by-vexels.png') }}" width="30">
								<br>
								<span class="profile-word">My Wallet</span>
							</a>
						</div>
					</div>
				</div>
			@else
				<div class="form-group container-box sl-personal-header">
					<div class="row">
						<div class="col-4" align="center">
							<a href="{{ route('myqrcode') }}">
								<img src="{{ asset('images/qrcode.png') }}" width="30">
								<br>
								<span class="profile-word">My QRcode</span>
							</a>
						</div>

						<div class="col-4" align="center">
							<a href="{{ route('MyAffiliate', Auth::user()->code) }}">
								<img src="{{ asset('images/profile/585e4d1ccb11b227491c339b.png') }}" width="30">
								<br>
								<span class="profile-word">My Team</span>
							</a>
						</div>

						<div class="col-4" align="center">
							<a href="{{ route('wallet') }}">
								<img src="{{ asset('images/profile/c3286d4d32fa90ebcf09b488654612b9-wallet-icon-by-vexels.png') }}" width="30">
								<br>
								<span class="profile-word">My Wallet</span>
							</a>
						</div>
					</div>
				</div>
			@endif
			
		</div>
	</div>
</div>
<form method="POST" action="{{ route('SaveTransaction') }}" id="transaction-form" enctype="multipart/form-data">
@csrf

<div class="profile-content pb-3">
	<div class="container">
		<div class="container-box">
			@if($errors->any())
			  <div class="alert alert-danger">{!! implode('<br/>', $errors->all(':message')) !!}</div>
			@endif
			<div class="form-group">
				<label>Downline</label>
				<select class="select2 form-control" name='merchants'>
					@foreach($downlines as $downline)
					<option value="{{ $downline->code }}">
						{{ $downline->f_name }} {{ $downline->l_name }} ({{ $downline->code }})
					</option>
					@endforeach
				</select>
			</div>

			<div class="form-group big-parent">
				<label>Items</label>
				<div class="child-div">
					<div class="form-group child-row">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<select class="form-control products select2" name="product_id[]">
										<option value="">Select Product</option>
										@foreach($products as $product)
										<option value="{{ $product->product_id }}">{{ $product->product_name }}</option>
										@endforeach
									</select>
								</div>
								<div class="product_variation">
								</div>
								<div class="stockBalance">
								</div>
							</div>
							<div class="col-md-6">
								<input type="text" name="quantity[]" value="" class="form-control" placeholder="Quantity">
							</div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="row">
						<div class="col-md-12" align="center">
							<button class="add-row-btn">
								<i class="fa fa-plus"></i>
							</button>
						</div>
					</div>
				</div>
			</div>

			<div class="form-group">
				<label>Bank Slip</label>
				<input type="file" class="form-control" name="fileToUpload">
			</div>
			<hr>
			<div class="form-group">
				<button class="btn btn-primary">Submit</button>
			</div>
		</div>
	</div>
</div>
</form>
@endsection

@section('js')
<script type="text/javascript">
	$('.select2').select2();

	$('.add-row-btn').click(function(e){
		e.preventDefault();

		var ele = $(this);

		var add_new_row = '<div class="form-group child-row">\
								<div class="row">\
									<div class="col-md-6">\
										<div class="form-group">\
											<select class="form-control products select2" name="product_id[]">\
												<option value="">Select Product</option>\
												@foreach($products as $product)\
												<option value="{{ $product->product_id }}">{{ $product->product_name }}</option>\
												@endforeach\
											</select>\
										</div>\
										<div class="product_variation">\
										</div>\
										<div class="stockBalance">\
										</div>\
									</div>\
									<div class="col-md-6">\
										<input type="text" name="quantity[]" value="" class="form-control" placeholder="Quantity">\
									</div>\
								</div>\
							</div>';

		ele.closest('.big-parent').find('.child-div').append(add_new_row);
		$('.big-parent .select2').select2();
	});

	$('.big-parent').on('change', '.products', function(){
		$('.loading-gif').show();
		var ele = $(this);
		var numItems = $('.big-parent .products').length;
		var pid = ele.val();
		var fd = new FormData();
	  		fd.append('num', numItems);
	  		fd.append('pid', pid);

		$.ajax({
	        url: '{{ route("getTransactionVariation") }}',
	        type: 'post',
	        data: fd,
	        contentType: false,
	        processData: false,
	        success: function(response){
	        	$('.loading-gif').hide();
	        	if(response[0] == '2'){
	        		ele.closest('.child-row').find('.stockBalance').html('Balance left: '+response[1]);
	        	}else{
	        		ele.closest('.child-row').find('.product_variation').html(response[1]);
	        	}
	        },
	    });
	});

	$(document).ready( function() {
		$('.big-parent').on('change', '.product_variation_option', function(){
			var ele = $(this);
			
			var vid = ele.val();

			var fd = new FormData();
		  		fd.append('vid', vid);

			$.ajax({
		        url: '{{ route("getVariationStock") }}',
		        type: 'post',
		        data: fd,
		        contentType: false,
		        processData: false,
		        success: function(response){
		        	// alert(123);
		        	// alert(response);
		        	ele.closest('.child-row').find('.stockBalance').html('Balance left: '+response);	        	
		        	return false;
		        },
		    });
		});
	})

</script>
@endsection