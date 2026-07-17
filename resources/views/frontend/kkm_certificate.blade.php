@extends('layouts.app')
@section('content')

<div class="custom-border-bottom py-3">
    <div class="container">
        <div class="row">
            <div class="col-md-12 mb-0">
                <a href="{{ route('home') }}">Home</a> 
                <span class="mx-2 mb-0">/</span> 
                <strong class="text-black">KKM Certificate</strong>
            </div>
        </div>
    </div>
</div>

<section class="ftco-section mb-5">
	<div class="container">
		<div class="row justify-content-center mb-5">
          <div class="col-md-7 site-section-heading text-center pt-4">
            <h2>KKM Certificate</h2>
          </div>
        </div>
		<div class="row">
			<div class="col-lg-12">
				<div class="single_post_text">
					{!! $data['web_setting']->kkm_cert !!}
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