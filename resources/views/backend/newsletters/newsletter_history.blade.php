@extends('layouts.admin_app')

@section('content')
<form action="{{ route('newsletter_history') }}" method="GET">
<div class="row">
	<!-- <div class="col-sm-2">
		<div class="form-group">
			<input type="text" class="form-control" name="code" value="{{ !empty('code') && request('code') ? request('code') : '' }}" placeholder="Search Agent Code">
		</div>
	</div>
	<div class="col-sm-2">
		<div class="form-group">
			<input type="text" class="form-control" name="merchant_name" value="{{ !empty('merchant_name') && request('merchant_name') ? request('merchant_name') : '' }}" placeholder="Search Agent Name">
		</div>
	</div>



	<div class="col-sm-2">
		<div class="form-group">
			<select class="form-control" name="status">
				<option value="">Search Status</option>
				<option {{ (!empty(request('status')) && request('status') == '1') ? 'selected' : '' }} value="1">Active</option>
				<option {{ (!empty(request('status')) && request('status') == '2') ? 'selected' : '' }} value="2">Inactive</option>
			</select>
		</div>
	</div> -->
</div>

<div class="form-group">
	<div class="row">
		<!-- <div class="col-sm-2">
			<div class="form-group">
				Row Per Page: <br>
				<select class="input-small" name="per_page">
					<option value="10">10</option>
					<option value="20">20</option>
					<option value="50">50</option>
				</select>
			</div>
		</div> -->
	</div>
</div>
<div class="form-group">
	<!-- <button class="btn btn-outline-primary btn-sm">
		<i class="fa fa-search"></i> Search
	</button>
	<a href="{{ route('newsletter.newsletters.index') }}" class="btn btn-warning btn-sm">
		<i class="fa fa-refresh"></i> Clear Search
	</a> -->
</div>
</form>
<div class="row" style="overflow: auto;">
	<div class="col-12">
		<table class="table table-bordered">
			<thead>
				<tr class="info">
					<th>#</th>
					<th>{{ isset($data['backendlang']['backendlang']['Newsletter_Content']) ? $data['backendlang']['backendlang']['Newsletter_Content'] :'' }}</th>
					<th>{{ isset($data['backendlang']['backendlang']['Created_At']) ? $data['backendlang']['backendlang']['Created_At'] :'' }}</th>
				</tr>
			</thead>
			<tbody>
				@if (!$allEntry->isEmpty())
				@foreach($allEntry as $key => $entry)
				@php
				
				@endphp
				<tr>
					<td>
						{{ $key+1 }}
						<input type="hidden" class="row_id" value="{{ $entry->id }}">
					</td>
					<td>{!! $entry->newsletter !!}</td>
					<td>{{ $entry->created_at }}</td>
				@endforeach
				@else
				<tr>
					<td colspan="3">{{ isset($data['backendlang']['backendlang']['No_Result_Found']) ? $data['backendlang']['backendlang']['No_Result_Found'] :'' }}</td>
				</tr>
				@endif
			</tbody>
		</table>
	</div>
</div>
@endsection

@section('js')
<script type="text/javascript">
	
</script>
@endsection