@extends('layouts.admin_app')
<script src="{{ asset('frontend/js/qrious.min.js') }}"></script>
@section('css')
<style type="text/css">
 @page{
    size: auto;
    margin: 0 20px;
    margin-right: 20px;
    margin-bottom: 20px;
  }
</style>
@endsection
@section('content')
<a href="#" class="print-window" style="display: none;">
	<i class="fa fa-print"></i> {{ isset($data['backendlang']['backendlang']['Print']) ? $data['backendlang']['backendlang']['Print'] :'' }}
</a>
<table class="" width="100%">
    <tr>
        <td colspan="2" align="center">
          <br>
          <img src="{{ asset($data['website_logo']) }}" width="50px" style="border-radius: 100%;">
          <h4>{{ !empty($data['web_setting']->invoice_name) ? $data['web_setting']->invoice_name : $data['admin']->company_name }}</h4>
          <small>({{ $web_setting->sst_registration_no }})</small>
          <h6 style="white-space: pre-wrap;">{!! htmlspecialchars_decode($web_setting->address) !!}</h6>
          <h6>{{ $admin->phone }}</h6>
        </td>
    </tr>
</table>
<div style="border-top: 1px solid; padding: 5px 0px;"></div>
<table class="" width="100%">
  <tr>
    <td align="center">
      <canvas id="qr-table"></canvas>      
    </td>
  </tr>
  <tr>
    <td align="center"></td>
  </tr>
  <tr>
    <td align="center"></td>
  </tr>
  <tr>
    <td align="center"></td>
  </tr>
  <tr>
    <td align="center"></td>
  </tr>
  <tr>
    <td align="center"></td>
  </tr>
  <tr>
    <td align="center"></td>
  </tr>
</table>
<div style="padding: 15px 0px;"></div>
@endsection
@section('js')
<script type="text/javascript">
	$('.print-window').click(function() {
	    window.print();
	});
	$(document).ready(function(){
		$('.print-window').click();
		$('.tr-color').css('background-color', '#ddd');
	});

  var canvas = new QRious({
    element: document.getElementById('qr-table'),
    value: "{{ route('main_menu', [$id, $tid]) }}",
    size: '250',
    background: 'white',
    foreground: 'black',
    level: 'L',
    padding: '38',
    foregroundAlpha: '2.8'
  })


  var canvas = document.getElementById('qr-table');
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

  var x = 200 / 1.6;
  var y = 200 / 0.855;

  var textString = "{{ $tables->table_name }}";
  ctx.fillText(textString, x, y);
</script>
@endsection