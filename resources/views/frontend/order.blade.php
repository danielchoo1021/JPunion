@extends('layouts.app')

@section('content')
<div class="profile-own-bg">
	<div class="personal-header-info">
			<div class="container">
				<div class="row">
					<div class="col-4" align="left">
						<a href="{{ route('profile') }}">
							<p style="color: white;"><i class="fa fa-chevron-left"></i> Back</p>
						</a>
					</div>
					<div class="col-4" align="left">
						<p align="center" class="header-title">My orders</p>
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
						@if(!empty(Auth::user()->profile_logo))
							<img src="{{ asset(Auth::user()->profile_logo) }}" width="50" class="profile-logo">
						@else
							<img src="{{ asset('images/images.png') }}" width="50" class="profile-logo">
						@endif
					</div>
					<div class="col-6">
						&nbsp;
						<b class="profile-name">{{ Auth::user()->f_name }} {{ Auth::user()->l_name }}</b>
						<br>
						&nbsp;
						<small class="profile-code">Code: {{ Auth::user()->code }}</small>
						<br>
						&nbsp;
						<small class="profile-level">Level: {{ !empty($lvl) ? $lvl : ' - ' }}</small>
					</div>
					<!-- <div class="col-4" align="right">
						<a href="#">
							<i class="fa fa-pencil"></i> Edit Profile
						</a>

					</div> -->
				</div>
			</div>
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
		</div>
	</div>
</div>
<div class="profile-content">
	<div class="container">
		<div class="form-group">
			<div class="row">
				<div class="col-sm-12 myOrder-list">
					@if (!$transactions->isEmpty())
					@foreach($transactions as $transaction)
					<div class="form-group container-box">
						<div class="row">
							<div class="col-6 order-no-details">
								<b>Order: #{{ $transaction->transaction_no }}</b><br>
								{{ $transaction->created_at }}
							</div>
							<div class="col-6" align="right">
								@if($transaction->status == 99)
									<a href="#" class="btn btn-primary btn-sm pay-now-button set_button set_text" data-id="{{ md5($transaction->id) }}" data-toggle="modal" data-target="#myModal">
										PAY NOW
									</a>
								@else
									<a href="{{ route('order_detail', $transaction->transaction_no) }}" class="btn btn-primary btn-sm pay-now-button set_button set_text">MANAGE</a>
								@endif
							</div>
						</div>
						<hr>
						@foreach($details[$transaction->id] as $detail)
						@php
						$image = (!empty($detail->product_image)) ? $detail->product_image : 'images/no-image-available-icon-6.jpg';
						@endphp
						<div class="form-group">
							<div class="row">
								<div class="col-sm-1">
									<div class="from-group">
										<img src="{{ asset($image) }}" style="width: 70px;">
									</div>
								</div>
								<div class="col-sm-6">
									<div class="form-group product-details">
										<b>{{ $detail->product_name }}</b> <br>
										{!! ($detail->sub_category != '') ? "L: ".$detail->sub_category."°<br>" : '' !!}
										{!! ($detail->second_sub_category != '') ? "R: ".$detail->second_sub_category."°<br>" : '' !!}
										Qty: {{ $detail->quantity }}
									</div>
								</div>
								<div class="col-sm-4">
									@if($transaction->status == 99)
										<span class="badge badge-pill bg-warning">Unpaid</span>
									@elseif($transaction->status == 98)
										<span class="badge badge-pill badge-info">Waiting Verification</span>
									@elseif($transaction->status == 97)
										<span class="badge badge-pill badge-info">In-progress</span>
									@elseif($transaction->status == '96')
										<span class="badge bg-danger">Rejected</span>
									@elseif($transaction->status == 1)
										<span class="badge bg-success">Paid</span>
									@else
										<span class="badge badge-pill bg-danger">Cancelled</span>
									@endif
								</div>
							</div>
						</div>
						<hr>
						@endforeach
					</div>
					@endforeach
					@else
					<div class="form-group container-box">
						<div class="form-group" align="center">
							There are no orders placed yet. <br><br>
							<a href="{{ route('home') }}" class="continue-shopping-btn btn btn-primary btn-sm set_button set_text"> Continue Shopping</a>
						</div>
					</div>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  	<div class="modal-dialog" role="document">
    	<div class="modal-content">
      		<div class="modal-header">
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        		<h4 class="modal-title" id="myModalLabel" align="left">Select Bank</h4>
      		</div>
      		<div class="modal-body">
        		<div class="form-group">
        			<input type="hidden" id="traind">
					<div class="row">
						<div class="col-4" align="center">
							<label>
								<input type="radio" name="bank_id" value="10000406">
								<img src="{{ asset('images/banks/maybank.jpg') }}">
							</label>
						</div>
						<div class="col-4" align="center">
							<label>
								<input type="radio" name="bank_id" value="10000408">
								<img src="{{ asset('images/banks/cimb.jpg') }}">
							</label>
						</div>
						<div class="col-4" align="center">
							<label>
								<input type="radio" name="bank_id" value="10000409">
								<img src="{{ asset('images/banks/rhb.jpg') }}">
							</label>
						</div>
					</div>
				</div>
				<br>
				<div class="form-group">
					<div class="row">
						<div class="col-4" align="center">
							<label>
								<input type="radio" name="bank_id" value="10000410">
								<img src="{{ asset('images/banks/hongleong.jpg') }}">
							</label>
						</div>
						<div class="col-4" align="center">
							<label>
								<input type="radio" name="bank_id" value="10000407">
								<img src="{{ asset('images/banks/pbe.jpg') }}">
							</label>
						</div>
					</div>
				</div>
				<div class="form-group">
					<b id="error-message-banks" class="important-text"></b>
				</div>
      		</div>
      		<div class="modal-footer">
        		<button type="button" class="btn btn-default set_button set_text" data-dismiss="modal">Cancel</button>
        		<button type="button" class="btn btn-primary pay-button set_button set_text">Pay Now</button>
      		</div>
    	</div>
  	</div>
</div>
@endsection

@section('js')
<script type="text/javascript">
	$('.pay-now-button').click( function(){
		$('#traind').val($(this).data('id'));
	});

	$('.pay-button').click( function(e){
		e.preventDefault();
		
		if(!$("input[name='bank_id']:checked").val()){
	    	$('#error-message-banks').html('Please Select Bank To Continue Payment.');
	    	return false;
	    }
	    
	    var fd = new FormData();
		fd.append('transaction_id', $('#traind').val());
		$('.loading-gif').show();
		$.ajax({
	       url: '{{ route("Repayment") }}',
	       type: 'post',
	       data: fd,
	       contentType: false,
	       processData: false,
	       success: function(response){

	       		var url = "{{ route('PaymentProcess', [':id', ':bank_code']) }}";
					url = url.replace(':id', response);
					url = url.replace(':bank_code', $("input[name='bank_id']:checked").val());

				window.location.href = url;
	       	  
	       },
	    });

		
	});
</script>
@endsection