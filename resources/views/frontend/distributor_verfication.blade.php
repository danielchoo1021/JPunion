@extends('layouts.app')
@section('content')
<div class="custom-border-bottom py-3">
    <div class="container">
        <div class="row">
            <div class="col-md-12 mb-0">
                <a href="{{ route('home') }}">Home</a> 
                <span class="mx-2 mb-0">/</span> 
                <strong class="text-black">Distributor's Verification</strong>
            </div>
        </div>
    </div>
</div>

<section class="ftco-section">
	<div class="container">
		<div class="row justify-content-center mb-5">
          <div class="col-md-7 site-section-heading text-center pt-4">
            <h2 style="color: #fff !important;">Distributor's Verification</h2>
          </div>
        </div>
		<div class="row">
			<div class="col-md-12 col-lg-12 order-md-last">
				<div class="form-group" align="center">
					<div class="col-md-6">
						<div class="button-inside">
							<input type="text" class="form-control search-verification" name="mc" placeholder="{{ isset($data['lang']['lang']['Please enter Distributor code / Contact Number']) ? $data['lang']['lang']['Please enter Distributor code / Contact Number'] : 'Please enter Distributor code / Contact Number' }}">
							<a href="#" class="btn btn-sm btn-pink search-member-code" 
							   style="right: 0px; top: 0px; background-color: transparent; padding: 13px 8px;">
	                            <i class="fa fa-search fa-2x"></i> Search
	                        </a>
						</div>
					</div>
				</div>
				<hr>

				<div class="form-group" align="center">
					<div class="card border-success verification-success mb-3" style="max-width: 18rem; background-color: transparent; display: none;">
					  <div class="card-header">
					  		{{ isset($data['lang']['lang']['Verification Status']) ? $data['lang']['lang']['Verification Status'] : "Verification Status" }}
					  </div>
					  <div class="card-body  text-success" style=" padding: 0px;">
					    	<span >					    		
					    		<b style="font-size: 1.5rem;">
					    			{{ isset($data['lang']['lang']['active']) ? $data['lang']['lang']['active'] : "Active" }}
					    		</b>
					    	</span>
					    	<!-- <br>
					    	<span class="badge bg-success">Success</span> -->
					  </div>
					</div>
					<div class="card border-danger verification-danger mb-3" style="max-width: 18rem; background-color: #000; display: none;">
					  <div class="card-header">
					  	{{ isset($data['lang']['lang']['Result']) ? $data['lang']['lang']['Result'] : "Result" }}
					  </div>
					  <div class="card-body text-danger"  style=" padding: 0px;">
					    <b style="font-size: 1.5rem;">{{ isset($data['lang']['lang']['no_result']) ? $data['lang']['lang']['no_result'] : "No Result" }}</b>
					  </div>
					</div>
				</div>
				
				<div class="form-group verification-form" style="position: relative; display: none;">
					
				</div>
	    	</div>
		</div>
	</div>
</section>
@endsection

@section('js')
<script type="text/javascript">
	$('.search-member-code').click( function(e){
		e.preventDefault();
		$('.loading-gif').show();
		var ele = $(this);
		var code = $('.search-verification').val();
		var fd = new FormData();
	    	fd.append('code', code);
	    
		$.ajax({
	       url: '{{ route("getVerificationForm") }}',
	       type: 'post',
	       data: fd,
	       contentType: false,
	       processData: false,
	       success: function(response){
	       		$('.loading-gif').hide();
	       		if(response == 0){
	       			$('.verification-danger').show();
	       			$('.verification-success').hide();
	       			$('.verification-form').hide();
	       		}else{
	       			$('.verification-success').show();
	       			$('.verification-danger').hide();
	       			$('.verification-form').html(response);
	       			$('.verification-form').show();

	       		}
	       },
	    });
	});
</script>
@endsection