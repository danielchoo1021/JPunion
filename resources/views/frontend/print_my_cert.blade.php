@extends('layouts.app')
<style type="text/css">
	@media print{
		@page {
			size: landscape;
			margin: 4mm 0mm;
		}
	}

	
</style>
@section('content')
<a href="#" class="print-window" style="display: none;">
	<i class="fa fa-print"></i> Print
</a>

<div style="position: relative;">
	<img src="{{ asset('images/mmexport1607790280474.jpg') }}" width="100%;">
	<img src="{{ !empty($merchant->profile_logo) ? asset($merchant->profile_logo) : asset('images/images.png') }}" class="dv_top_logo">
	<div class="dv_top_name">
		{{ $merchant->f_name }}
	</div>

	<div class="dv_top_code">
		{{ $merchant->code }}
	</div>

	<div class="dv_center_name">
		{{ $merchant->f_name }}
	</div>

	<div class="dv_center_code">
		{{ $merchant->code }}
	</div>
</div>
@endsection

@section('js')
<script type="text/javascript">
	$('.print-window').click(function() {
	    window.print();
	});
	$(document).ready(function(){
		$('.print-window').click();
	});
</script>
@endsection