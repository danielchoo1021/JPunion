@extends('layouts.admin_app')
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
          <img src="{{ asset($data['website_logo']) }}" width="100px" style="border-radius: 100%;">
          <h4>{{ !empty($data['web_setting']->invoice_name) ? $data['web_setting']->invoice_name : $data['admin']->company_name }}</h4>
          <small>({{ $web_setting->sst_registration_no }})</small>
          <h6 style="white-space: pre-wrap;">{!! htmlspecialchars_decode($web_setting->address) !!}</h6>
          <h6>{{ $admin->phone }}</h6>
        </td>
    </tr>
    <tr>
        <td style="font-size: 8px;">
          Order No.: {{ $transaction->transaction_no }}
          @if(!empty($transaction->combined_id))
          , {{ $transaction->combined_id }}
          @endif
        </td>
        <td align="right"  style="font-size: 8px;">
          Date.: {{ $transaction->created_at }}
        </td>
    </tr>
</table>
<div style="border-top: 1px solid; padding: 5px 0px;"></div>
<table class="" width="100%">
    <tr style="border-bottom: 2px solid;">
        <td style="font-size: 9px;">{{ isset($data['backendlang']['backendlang']['Quantity']) ? $data['backendlang']['backendlang']['Quantity'] :'' }}</td>
        <td style="font-size: 9px;">{{ isset($data['backendlang']['backendlang']['Items']) ? $data['backendlang']['backendlang']['Items'] :'' }}</td>
        <td style="font-size: 9px;" align="right">{{ isset($data['backendlang']['backendlang']['Total']) ? $data['backendlang']['backendlang']['Total'] :'' }} (RM)</td>
    </tr>
    @php
      $sub_total = 0;
    @endphp
    @foreach($details as $detail)
    	<tr>
	        <td style="font-size: 9px;">x{{ $detail->quantity }}</td>
	        <td style="font-size: 9px;">{{ $detail->product_name }}
	        	@php
	        	$add_on_price = 0;
	        	@endphp
	        	@foreach($history_add_ons[$detail->id] as $history_add_on)
	        		<p style="margin-top: 2px;">
                        <small>
                            + {{ $history_add_on->add_on_title }} - {{ $history_add_on->add_on_name }}
                            ({{ number_format($history_add_on->price, 2)}})
                        </small>
                    </p>
                @php
                	$add_on_price += $history_add_on->price;
                @endphp
	        	@endforeach
	        </td>
	        <td style="font-size: 9px;" align="right">
	          {{ number_format((($detail->unit_price + $add_on_price) * $detail->quantity), 2) }}
	        </td>
	    </tr>
    @php
      $sub_total += ($detail->unit_price + $add_on_price) * $detail->quantity;
    @endphp
    @endforeach
</table>
@php
$netTotal = $sub_total - $transaction->discount;
@endphp
<div style="border-bottom: 1px solid; padding: 5px 0px;"></div>
<table class="" width="100%" style="padding-top: 10px;">
    <tr style="border-top: 1px solid #000; padding-top: 10px;">
      <td colspan="2" style="font-size: 9px;">
        {{ isset($data['backendlang']['backendlang']['Sub_Total']) ? $data['backendlang']['backendlang']['Sub_Total'] :'' }}
      </td>
      <td align="right" style="font-size: 9px;">
        {{ number_format($sub_total, 2) }}
      </td>
    </tr>
   </table>
   <div style="padding: 5px 0px;"></div>
   <table class="" width="100%">
    <tr>
      <td colspan="2" style="font-size: 9px;">
        {{ isset($data['backendlang']['backendlang']['Discount']) ? $data['backendlang']['backendlang']['Discount'] :'' }}
      </td>
      <td align="right" style="font-size: 9px;">
        {{ number_format($transaction->discount, 2) }}
      </td>
    </tr>
  </table>
  <div style="padding: 5px 0px;"></div>
   <table class="" width="100%">
    <tr>
      <td colspan="2" style="font-size: 9px;">
        {{ isset($data['backendlang']['backendlang']['Point_Re']) ? $data['backendlang']['backendlang']['Action'] :'' }}
      </td>
      <td align="right" style="font-size: 9px;">
        {{ number_format($transaction->grand_total_point, 2) }}
      </td>
    </tr>
  </table>
  <div style="padding: 5px 0px;"></div>
  <table class="" width="100%">
   <tr>
      <td colspan="2" style="font-size: 9px;">
        {{ isset($data['backendlang']['backendlang']['Net_Payable']) ? $data['backendlang']['backendlang']['Net_Payable'] :'' }}
      </td>
      <td align="right" style="font-size: 9px;">
        {{ number_format($sub_total - $transaction->grand_total_point - $transaction->discount, 2) }}
      </td>
   </tr>
  </table>
  <div style="padding: 5px 0px;"></div>
  <table class="" width="100%">
   <tr>
      <td colspan="2" style="font-size: 9px;">
        {{ isset($data['backendlang']['backendlang']['Cash_Tendered']) ? $data['backendlang']['backendlang']['Cash_Tendered'] :'' }}
      </td>
      <td align="right" style="font-size: 9px;">
        {{ number_format($transaction->paid_amount, 2) }}
      </td>
   </tr>
  </table>
  <div style="padding: 5px 0px;"></div>
  <table class="" width="100%">
   <tr>
      <td colspan="2" style="font-size: 9px;">
        {{ isset($data['backendlang']['backendlang']['Change']) ? $data['backendlang']['backendlang']['Change'] :'' }}
      </td>
      <td align="right" style="font-size: 9px;">
      	@if($transaction->paid_amount > 0)
        {{ number_format(($transaction->paid_amount - $sub_total - $transaction->grand_total_point - $transaction->discount), 2) }}
        @else
        	0.00
        @endif
      </td>
   </tr>
</table>
<div style="padding: 10px 0px;"></div>
<div align="center" style="font-size: 9px;">
  **{{ isset($data['backendlang']['backendlang']['Thank_You']) ? $data['backendlang']['backendlang']['Thank_You'] :'' }}**<br> 
  **{{ isset($data['backendlang']['backendlang']['See_You_Next_Time']) ? $data['backendlang']['backendlang']['See_You_Next_Time'] :'' }}**
</div>
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
</script>
@endsection