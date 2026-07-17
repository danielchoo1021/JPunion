@extends('layouts.admin_app')

@section('content')
<div class="page-header">
    <h1>
       {{ isset($data['backendlang']['backendlang']['Logistics_Tracking']) ? $data['backendlang']['backendlang']['Logistics_Tracking'] :'' }}
        <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
            {{ $transaction->transaction_no }}
        </small>
        
        {{ isset($data['backendlang']['backendlang']['Tracking_Number']) ? $data['backendlang']['backendlang']['Tracking_Number'] :'' }}
        <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
            {{ $transaction->tracking_no }}
        </small>
    </h1>
</div>
@if($errors->any())
  <div class="alert alert-danger">{!! implode('<br/>', $errors->all(':message')) !!}</div>
@endif

@foreach($results['result'] as $value)
    @foreach($value['status_list'] as $key => $value2)
    	@if(isset($value2['status']))
    	<div class="form-group" style="box-shadow: 0 0 10px 0 #bdbdbd; padding: 5px 15px; " >
          	<blockquote style="margin: 0px; {{ ($key == 0) ? 'border-left: 5px solid #bdbdbd;' : ''  }}">
				<p class="lighter line-height-125 {{ ($key == 0) ? 'gold-word' : 'white-color' }}">
					[{{ $transaction->courier }}] {{ isset($value2['status']) ? $value2['status'] : '' }} at {{ isset($value2['location']) ? $value2['location'] : '' }}
				</p>

				<small style="font-size: 11px;">
					<cite title="Source Title">
						{{ isset($value2['event_date']) ? $value2['event_date'] : '' }} 
						{{ isset($value2['event_time']) ? $value2['event_time'] : '' }}
					</cite>
				</small>
			</blockquote>
		</div>
		@endif
  	@endforeach
@endforeach

@endsection