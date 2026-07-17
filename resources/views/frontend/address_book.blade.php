@extends('layouts.app')

@section('content')
@php
	$url = str_replace(url('/'), '', url()->previous());
	if($url == '/Checkout'){
		$back_url = url()->previous();
	}else{
		$back_url = route('profile');
	}
@endphp
@include('partial.frontend.profile_header')
<div class="form-group pb-5">
	<div class="container">
		<div class="profile-content">
			<div class="form-group">
				<a href="{{ route('AddressBook.AddressBook.create') }}" class="btn btn-primary btn-sm set_button set_text">
					<i class="fa fa-plus"></i> &nbsp;&nbsp; {{ isset($data['lang']['lang']['add_new_address']) ? $data['lang']['lang']['add_new_address'] :'添加新地址'}}
				</a>
			</div>
		</div>
		<div class="container-box">
		@if(!$address_book->isEmpty())
			@foreach($address_book as $key => $address)
				<div class="form-group" style="position: relative;">
					<!-- <a href="{{ route('AddressBook.AddressBook.edit', md5($address->id)) }}" class="btn btn-sm btn-primary" style="position: absolute; right: 5px; z-index: 10;">
						{{ isset($data['lang']['lang']['edit_address']) ? $data['lang']['lang']['edit_address'] :'修改地址'}}
					</a>

					<a href="#" class="btn btn-sm btn-danger delete-address-btn" data-id="{{ md5($address->id) }}" style="position: absolute; right: 5px; top: 33px; z-index: 10;">
						{{ isset($data['lang']['lang']['delete']) ? $data['lang']['lang']['delete'] :'删除'}}
					</a> -->

					<div class="row">
						<div class="col-lg-2 col-md-2 col-sm-12 col-12">
							<div class="form-group">
								<i class="fa fa-user w-26"></i>{{ $address->f_name }} {{ $address->l_name }}
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-12 col-12">
							<div class="form-group">
								<i class="fa fa-phone w-23" aria-hidden="true"></i>
								+{{ $address->country_code }} 
								{{-- {{ ($address->phone[0] == 0) ? substr($address->phone, 1) : $address->phone }} --}}
								@if($address->country_code && $address->country_code == '60')
									{{ ($address->phone[0] == 0) ? $address->phone : '0'.$address->phone }}
								@else
									{{ ($address->phone[0] == 0) ? substr($address->phone, 1) : $address->phone }}
								@endif
							</div>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-12 col-12">
							<div class="form-group">
								<i class="fa fa-map-marker w-23" aria-hidden="true"></i>
								{{ $address->address }}
								<br>

								<span class="w-23 d-ib"></span>
								<span>{{ $address->postcode }} {{ $address->city_name }}</span> 
								<br>

								<span class="w-23 d-ib"></span>
								<span>{{ !empty($address->state_name) ? $address->state_name : $address->state }}, {{ !empty($address->country_name) ? $address->country_name : $address->country }}</span>
							</div>
						</div>

						<div class="col-lg-2 col-md-2 col-sm-12 col-12">
							<div class="form-group default-box">
								@if($address->default == 1)
									<span class="badge bg-success">{{ isset($data['lang']['lang']['default']) ? $data['lang']['lang']['default'] :'默认'}}</span>
								@else
									<button class="btn btn-sm d-padding-10 default set_button set_text" data-id="{{ md5($address->id) }}">{{ isset($data['lang']['lang']['set_as_default_address']) ? $data['lang']['lang']['set_as_default_address'] :'设置为默认地址'}}</button>
								@endif
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-12 col-12">
							<a href="{{ route('AddressBook.AddressBook.edit', md5($address->id)) }}" class="btn btn-sm btn-primary set_button set_text" style="right: 5px; z-index: 10;">
								{{ isset($data['lang']['lang']['edit_address']) ? $data['lang']['lang']['edit_address'] :'修改地址'}}
							</a>

							<a href="#" class="btn btn-sm btn-danger delete-address-btn set_button set_text" data-id="{{ md5($address->id) }}" style="right: 5px; top: 33px; z-index: 10;">
								{{ isset($data['lang']['lang']['delete']) ? $data['lang']['lang']['delete'] :'删除'}}
							</a>
						</div>
						<!-- <div class="col-lg-2 col-md-2 col-sm-12 col-12">
							<div class="form-group">
								<input type="radio" name="default" data-id="{{ md5($address->id) }}" {{ ($address->default == 1) ? 'checked' : '' }}>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-12 col-12">
							<div class="form-group">
								<a href="{{ route('AddressBook.AddressBook.edit', md5($address->id)) }}" class="action-btn">
									<i class="fa fa-pencil"></i>
								</a>
								@if($count > 1)
								&nbsp;&nbsp;
								<a href="#" class="action-btn delete-address-btn non-load red" data-id="{{ md5($address->id) }}">
									<i class="fa fa-trash"></i>
								</a>
								@endif
							</div>
						</div> -->

					</div>
				</div>
				<hr>
			@endforeach
		@else
			<div class="form-group" align="center">
				{{ isset($data['lang']['lang']['no_address']) ? $data['lang']['lang']['no_address'] :'尚无地址' }}
			</div>
		@endif
		</div>
	</div>
</div>
@endsection

@section('js')
<script type="text/javascript">
	$('input[name="default"]').click( function(e){
		
		$('.loading-gif').show();
		var ele = $(this);
		var value = ele.data('id');


		var fd = new FormData();
		fd.append('address_id', value);

		$.ajax({
	       url: '{{ route("changeDefaultAddress") }}',
	       type: 'post',
	       data: fd,
	       contentType: false,
	       processData: false,
	       success: function(response){
	       		toastr.success('Update Successfully');
	       		$('.loading-gif').hide();
	       },
	    });
	});

	$('.delete-address-btn').click( function(e){
		e.preventDefault();
		var ele = $(this);
		var value = ele.data('id');
		var $confirm;
		if(ele.closest('tr').find('input[name="default"]').prop("checked")){
			$confirm = confirm("This Address Information is your Default Address, Remove? \n *If Yes, Please reselect your Default Address*");
		}else{
			$confirm = confirm("Address will be deleted. Are you sure you want to delete it?");
		}
		
		var fd = new FormData();
		fd.append('address_id', value);
		if($confirm == true){
			$('.loading-gif').show();
			$.ajax({
		       url: '{{ route("deleteAddress") }}',
		       type: 'post',
		       data: fd,
		       contentType: false,
		       processData: false,
		       success: function(response){
		       		toastr.info('Address Row Deleted');
		       		location.reload();
		       },
		    });
		}else{

		}
	});

	$('.row .default-box').on('click', '.default', function(e){
		
		$('.loading-gif').show();
		var ele = $(this);
		var value = ele.data('id');


		var fd = new FormData();
		fd.append('address_id', value);
		
		$.ajax({
	       url: '{{ route("changeDefaultAddress") }}',
	       type: 'post',
	       data: fd,
	       contentType: false,
	       processData: false,
	       success: function(response){
	       		toastr.success('Setting successfully');

	       		$('.loading-gif').hide();
	    		location.reload();   		
	       },
	    });
	});

</script>
@endsection
