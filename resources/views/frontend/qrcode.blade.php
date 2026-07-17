@extends('layouts.app')
<script src="{{ asset('frontend/js/qrious.min.js') }}"></script>
<script src="https://unpkg.com/qr-code-with-logo@1.1.0/lib/qr-code-with-logo.browser.min.js"></script>
@section('content')
@include('partial.frontend.profile_header')
<div class="profile-content pb-5 mb-2">
	<div class="container">
		<div class="form-group container-box sl-personal-header" align="center">
			<form method="POST" action="{{ route('profile') }}" enctype="multipart/form-data">
				@csrf
				<div class="form-group">
					<input type="file" name="profile_logo" id="profile_logo" style="display: none;" onchange="form.submit()" accept="image/png, image/jpeg">
					<div class="form-group" style="display: none;">
						<button class="btn btn-primary btn-sm">
							<i class="fa fa-check"></i> {{ isset($data['lang']['lang']['save_changes']) ? $data['lang']['lang']['save_changes'] :'保存'}}
						</button>
					</div>
					<div class="pfp_upload"> 
					@if(!empty(Auth::guard($data['userGuardRole'])->user()->profile_logo))
						<div class="" style="background-image: url({{ asset(Auth::guard($data['userGuardRole'])->user()->profile_logo) }});
												background-repeat: no-repeat; background-size: cover; background-position: center; width: 70px; height: 70px;
												border-radius: 100%;">
						</div>
					@else
						<img src="{{ asset('images/images.png') }}" class="profile-image" id="profile-image" width="80">
					@endif
					</div>
				</div>
			</form>

			<div class="form-group">
				@if(!empty(Auth::guard($data['userGuardRole'])->user()->f_name))
					<b>{{ Auth::guard($data['userGuardRole'])->user()->f_name }} {{ Auth::guard($data['userGuardRole'])->user()->l_name }}</b>
				@else
					<b>{{ Auth::guard($data['userGuardRole'])->user()->phone }}</b>
				@endif
			</div>

			<div class="form-group">
				<p>{{ isset($data['lang']['lang']['after_register_agent_join']) ? $data['lang']['lang']['after_register_agent_join'] :'After Registering, invited agent will join your team'}}</p>
			</div>
			<div class="form-group">
				<div class="row justify-content-center">
					<div class="col-md-6" align="center">
						<br>
						<b>
						<u>
						<span style="font-size:20px">{{ isset($data['lang']['lang']['customer_register']) ? $data['lang']['lang']['customer_register'] :'顾客注册'}}</span>
						</u>
						</b>
						<br>
						<canvas id="qr-customer"></canvas>
						<br>
						<a class="btn btn-primary btn-sm set_button set_text" id="save">
							<i class="fa fa-download" style="font-size:20px"></i> {{ isset($data['lang']['lang']['download_register_qr']) ? $data['lang']['lang']['download_register_qr'] :'下载注册二维码'}}
						</a>
						<br>
						<div class="row justify-content-center mt-4 pt-2">
							<div class="col-md-6">
								<div class="form-group" style="font-size:22px">
									{{ isset($data['lang']['lang']['url']) ? $data['lang']['lang']['url'] :'网址'}}:
									<div class="button-inside">
										<p class="mb-2">
											<input type="text" name="guest_link" id="guest_link" class="form-control" value="{{ route('register', ['p='.Auth::user()->display_code.Auth::user()->display_running_no]) }}" style="text-align: center;">
										</p>
										<a href="#" class="btn btn-sm btn-primary mb-4 copy-guest-link set_button set_text">
											{{ isset($data['lang']['lang']['copy']) ? $data['lang']['lang']['copy'] :'复制'}}
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>
					@if(Auth::guard('agent')->check() || Auth::guard('admin')->check())
					<div class="col-md-6 mb-bt-4" align="center">
						<br>
						<b>
						<u>
						<span style="font-size:20px">{{ isset($data['lang']['lang']['agent_register']) ? $data['lang']['lang']['agent_register'] :'Agent Register'}}</span>
						</u>
						</b>
						<br>
						<canvas id="qr-agent"></canvas>
						<br>
						<a class="btn btn-primary btn-sm set_button set_text" id="save-two">
							<i class="fa fa-download" style="font-size:20px"></i> {{ isset($data['lang']['lang']['download_register_qr']) ? $data['lang']['lang']['download_register_qr'] :'下载注册二维码'}}
						</a>
						<br>
						<div class="row justify-content-center mt-4 pt-2">
							<div class="col-md-6">
								<div class="form-group" style="font-size:22px">
									{{ isset($data['lang']['lang']['url']) ? $data['lang']['lang']['url'] :'网址'}}:
									<div class="button-inside">
										<p class="mb-2">
											<input type="text" name="agent_link" id="agent_link" class="form-control" value="{{ route('merchant_register', ['p='.Auth::user()->display_code.Auth::user()->display_running_no]) }}" style="text-align: center;">
										</p>
										<a href="#" class="btn btn-sm btn-primary mb-4 copy-agent-link set_button set_text">
											{{ isset($data['lang']['lang']['copy']) ? $data['lang']['lang']['copy'] :'复制'}}
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>
					@endif
				</div>
			</div>

			<div class="form-group">
				<p>{{ isset($data['lang']['lang']['save_qrcode_send_it']) ? $data['lang']['lang']['save_qrcode_send_it'] :'屏幕截图/下载并保存二维码，将其发送出去'}}</p>
			</div>

			<!-- <div class="form-group">
				<div class="row justify-content-center">
					<div class="col-md-6">
						For Guest:
						<div class="button-inside">
							<input type="text" name="guest_link" id="guest_link" class="form-control" value="{{ route('home', ['a='.Auth::user()->code]) }}">
							<a href="#" class="btn btn-sm btn-primary copy-agent-link">
								Copy
							</a>
						</div>
					</div>
				</div>
			</div> -->

			<div class="form-group">
				<div class="row">
					<div class="col-md-6" align="center">
						
					</div>
					<div class="col-md-6" align="center">
						
					</div>
				</div>

				<div id="previewImage" style="display: none;"></div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('js')
<script type="text/javascript">
	$('.pfp_upload').on('click', function(){
		$('#profile_logo').trigger('click');
	});

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

	var element = $(".qrcode-div"); // global variable
    var getCanvas; // global variable

    // html2canvas(element, {
    //     onrendered: function (canvas) {
    //         $("#previewImage").append(canvas);
    //         getCanvas = canvas;
    //     }
    // });

	$('.download-qr').click( function(e){
		var imgageData = getCanvas.toDataURL("image/png");
        // Now browser starts downloading it instead of just showing it
        var newData = imgageData.replace(/^data:image\/png/, "data:application/octet-stream");
        $(".download-qr").attr("download", "MyQRcode.png").attr("href", newData);
	});
</script>


<script type="text/javascript">
	$('.copy-agent-link').click( function(e){
		e.preventDefault();
		
		var copyText = document.getElementById("agent_link");
		    copyText.select();
		    copyText.setSelectionRange(0, 99999)
		    document.execCommand("copy");

		$(this).html('Copied');
	});

	$('.copy-guest-link').click( function(e){
		e.preventDefault();
		
		var copyText = document.getElementById("guest_link");
		    copyText.select();
		    copyText.setSelectionRange(0, 99999)
		    document.execCommand("copy");

		$(this).html('Copied');
	});
</script>


<script type="text/javascript">
  var canvas = new QRious({
    element: document.getElementById('qr-customer'),
    // value: "{{ route('register', 'p='.Auth::guard($data['userGuardRole'])->user()->display_code.Auth::guard($data['userGuardRole'])->user()->display_running_no) }}",
    value: "{{ route('register', 'p='.Auth::guard($data['userGuardRole'])->user()->display_code.Auth::guard($data['userGuardRole'])->user()->display_running_no) }}",
    size: '250',
    background: 'white',
    foreground: 'black',
    level: 'L',
    padding: '38',
    foregroundAlpha: '2.8'
  })


  	var canvas = document.getElementById('qr-customer');
	var ctx = canvas.getContext('2d');
	ctx.webkitImageSmoothingEnabled = false;
	ctx.mozImageSmoothingEnabled = false;
	ctx.imageSmoothingEnabled = false;
	ctx.retinaResolutionEnabled = false;
	// Set display size (css pixels).
	var size = 200;

	// // Set actual size in memory (scaled to account for extra pixel density).
	var scale = window.devicePixelRatio; // Change to 1 on retina screens to see blurry canvas.
	canvas.style.width = size + "px";
	canvas.style.height = size + "px";

	// canvas.width = size * scale;
	// canvas.height = size * scale;

	// // Normalize coordinate system to use css pixels.
	// ctx.scale(10, scale);

	ctx.fillStyle = "#000000";
	ctx.fillRect(37, 217, 175, 30);
	ctx.fillStyle = "#FFFFFF";
	
	ctx.font = '18pt Signika Negative';
	ctx.textAlign = 'center';
	ctx.textBaseline = 'middle';

	var x = size / 1.6;
	var y = size / 0.855;

	var textString = "{{ Auth::guard($data['userGuardRole'])->user()->display_code }}{{ Auth::guard($data['userGuardRole'])->user()->display_running_no }}";
	ctx.fillText(textString, x, y);

	function downloadURI(uri, name) {
    var link = document.createElement('a');
    link.download = name;
    link.href = uri;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    delete link;
  }

  document.getElementById('save').addEventListener(
    'click',
    function() {
      var dataURL = canvas.toDataURL({ pixelRatio: 300 });
      downloadURI(dataURL, 'MyQRcode.jpeg');
    },
    false
  );
</script>
@if(Auth::guard('agent')->check() || Auth::guard('admin')->check())
<script type="text/javascript">
  var canvas = new QRious({
    element: document.getElementById('qr-agent'),
    // value: "{{ route('register', 'p='.Auth::guard($data['userGuardRole'])->user()->display_code.Auth::guard($data['userGuardRole'])->user()->display_running_no) }}",
    value: "{{ route('merchant_register', 'p='.Auth::guard($data['userGuardRole'])->user()->display_code.Auth::guard($data['userGuardRole'])->user()->display_running_no) }}",
    size: '250',
    background: 'white',
    foreground: 'black',
    level: 'L',
    padding: '38',
    foregroundAlpha: '2.8'
  })


  	var canvas = document.getElementById('qr-agent');
	var ctx = canvas.getContext('2d');
	ctx.webkitImageSmoothingEnabled = false;
	ctx.mozImageSmoothingEnabled = false;
	ctx.imageSmoothingEnabled = false;
	ctx.retinaResolutionEnabled = false;
	// Set display size (css pixels).
	var size = 200;

	// // Set actual size in memory (scaled to account for extra pixel density).
	var scale = window.devicePixelRatio; // Change to 1 on retina screens to see blurry canvas.
	canvas.style.width = size + "px";
	canvas.style.height = size + "px";

	// canvas.width = size * scale;
	// canvas.height = size * scale;

	// // Normalize coordinate system to use css pixels.
	// ctx.scale(10, scale);

	ctx.fillStyle = "#000000";
	ctx.fillRect(37, 217, 175, 30);
	ctx.fillStyle = "#FFFFFF";
	
	ctx.font = '18pt Signika Negative';
	ctx.textAlign = 'center';
	ctx.textBaseline = 'middle';

	var x = size / 1.6;
	var y = size / 0.855;

	var textString = "{{ Auth::guard($data['userGuardRole'])->user()->display_code }}{{ Auth::guard($data['userGuardRole'])->user()->display_running_no }}";
	ctx.fillText(textString, x, y);

	function downloadURI(uri, name) {
    var link = document.createElement('a');
    link.download = name;
    link.href = uri;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    delete link;
  }

  document.getElementById('save-two').addEventListener(
    'click',
    function() {
      var dataURL = canvas.toDataURL({ pixelRatio: 300 });
      downloadURI(dataURL, 'MyQRcode.jpeg');
    },
    false
  );
</script>
@endif
@endsection