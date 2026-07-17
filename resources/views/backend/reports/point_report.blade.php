@extends('layouts.admin_app')

@section('content')
<form action="{{ route('point_report') }}" method="GET">
<div class='form-group'>
	<div class="row">
		<div class="col-sm-2">
			<div class="form-group">
				<input type="text" class="form-control" name="member_name" value="{{ !empty(request('member_name')) ? request('member_name') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Member_Name']) ? $data['backendlang']['backendlang']['Search_Member_Name'] :'' }}">
			</div>
		</div>

		<div class="col-sm-2">
			<div class="form-group">
				<input type="text" class="form-control" name="member_code" value="{{ !empty(request('member_code')) ? request('member_code') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Member_Code']) ? $data['backendlang']['backendlang']['Search_Member_Code'] :'' }}">
			</div>
		</div>
	</div>
</div>

<div class="form-group">
	<div class="row">
		<div class="col-sm-2">
			<div class="form-group">
				{{ isset($data['backendlang']['backendlang']['Row_Per_Page']) ? $data['backendlang']['backendlang']['Row_Per_Page'] :'' }}: <br>
				<select class="input-small" name="per_page">
					<option {{ (!empty(request('per_page')) && request('per_page') == '10') ? 'selected' : '' }} value="10">10</option>
					<option {{ (!empty(request('per_page')) && request('per_page') == '20') ? 'selected' : '' }} value="20">20</option>
					<option {{ (!empty(request('per_page')) && request('per_page') == '50') ? 'selected' : '' }} value="50">50</option>
				</select>
			</div>
		</div>
	</div>
</div>
<div class="form-group">
	<div class="row">
		<div class="col-sm-4">
			<div class="form-group">
				<button class="btn btn-outline-primary btn-sm">
					<i class="fa fa-search"></i> {{ isset($data['backendlang']['backendlang']['Search']) ? $data['backendlang']['backendlang']['Search'] :'' }}
				</button>
				<a href="{{ route('point_report') }}" class="btn btn-warning btn-sm">
					<i class="fa fa-refresh"></i> {{ isset($data['backendlang']['backendlang']['Clear_Search']) ? $data['backendlang']['backendlang']['Clear_Search'] :'' }}
				</a>
			</div>
		</div>
	</div>
</div>

<div class="row" style="overflow: auto;">
	<div class="col-12">
		<table class="table table-bordered">
			<thead>
				<tr class="info">
					<th>#</th>
					<th>{{ isset($data['backendlang']['backendlang']['Member_Name']) ? $data['backendlang']['backendlang']['Member_Name'] :'' }}</th>
					<th>{{ isset($data['backendlang']['backendlang']['Member_Code']) ? $data['backendlang']['backendlang']['Member_Code'] :'' }}</th>
					<th>{{ isset($data['backendlang']['backendlang']['PV_Balance']) ? $data['backendlang']['backendlang']['PV_Balance'] :'' }}</th>
					<th>{{ isset($data['backendlang']['backendlang']['Member_Status']) ? $data['backendlang']['backendlang']['Member_Status'] :'' }}</th>
				</tr>
			</thead>
			<tbody>
				@if(!$users->isEmpty())
					@foreach($users as $key => $user)
					<tr>
						<td>{{ $key+1 }}</td>
						<td>
							<a href="{{ route('point_report_details', $user->id) }}" target="_blank"> 
								{{ $user->f_name }}
							</a>
						</td>
						<td>{{ $user->display_code }}{{ $user->display_running_no }}</td>
						<td>
							{{ number_format($PV_Balance[$user->code], 2) }}
						</td>
						<td>
							@if ($user->status == 1)
>								<span class="badge bg-success">
									{{ $data['backendlang']['backendlang']['Active'] ?? '' }}
								</span>
							@else
								<span class="badge bg-danger">
									{{ $data['backendlang']['backendlang']['Inactive'] ?? '' }}
								</span>
							@endif
						</td>
					</tr>
					@endforeach
				@else
				<tr>
					<td colspan="12">{{ isset($data['backendlang']['backendlang']['No_Result_Found']) ? $data['backendlang']['backendlang']['No_Result_Found'] :'' }}</td>
				</tr>
				@endif
			</tbody>
		</table>
		{{ $users->links() }}
	</div>
</div>

@endsection
@section('js')
<script type="text/javascript">
	$('input[name=dates]').daterangepicker({
		'applyClass' : 'btn-sm btn-success',
		'cancelClass' : 'btn-sm btn-outline-danger',
		locale: {
			applyLabel: "{{ isset($data['backendlang']['backendlang']['Apply']) ? $data['backendlang']['backendlang']['Apply'] :''}}",
			cancelLabel: "{{ isset($data['backendlang']['backendlang']['Cancel']) ? $data['backendlang']['backendlang']['Cancel'] :''}}",
			format: 'DD/MM/YYYY',
		}
	})
	.prev().on(ace.click_event, function(){
		$(this).next().focus();
	});
</script>
@endsection