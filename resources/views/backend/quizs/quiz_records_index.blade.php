@extends('layouts.admin_app')
@section('content')
<div class="container-box form-group">
	<h3>
		{{ isset($data['backendlang']['backendlang']['Filter']) ? $data['backendlang']['backendlang']['Filter'] :'' }}
	</h3>
	<hr>
	<form action="{{ route('quiz_records_index') }}" method="GET">
		<div class="row">
			<div class="col-2">
				<div class="form-group">
					<input type="text" class="form-control" name="code" value="{{ !empty('code') && request('code') ? request('code') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Code']) ? $data['backendlang']['backendlang']['Search_Code'] :'' }}">
				</div>
			</div>
			<div class="col-2">
				<div class="form-group">
					<input type="text" class="form-control" name="f_name" value="{{ !empty('f_name') && request('f_name') ? request('f_name') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Name']) ? $data['backendlang']['backendlang']['Search_Name'] :'' }}">
				</div>
			</div>
			{{-- <div class="col-2">
				<div class="form-group">
					<input type="text" class="form-control" name="email" value="{{ !empty('email') && request('email') ? request('email') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Email']) ? $data['backendlang']['backendlang']['Search_Email'] :'' }}">
				</div>
			</div> --}}
			<div class="col-2">
				<div class="form-group">
					<input type="text" class="form-control" name="phone" value="{{ !empty('phone') && request('phone') ? request('phone') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Phone']) ? $data['backendlang']['backendlang']['Search_Phone'] :'' }}">
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
			<button class="btn btn-outline-primary btn-sm">
				<i class="bi bi-search"></i> {{ isset($data['backendlang']['backendlang']['Search']) ? $data['backendlang']['backendlang']['Search'] :'' }}
			</button>
			<a href="{{ route('quiz_records_index') }}" class="btn btn-warning btn-sm">
				<i class="bi bi-arrow-clockwise"></i> {{ isset($data['backendlang']['backendlang']['Clear_Search']) ? $data['backendlang']['backendlang']['Clear_Search'] :'' }}
			</a>
		</div>
	</form>
</div>
<div class="form-group container-box">
	<div class="row">
		<div class="col-12">
			{{ $records->links() }}
			<table class="table table-bordered">
				<thead>
					<tr class="info">
						<th>#</th>
						<th>{{ isset($data['backendlang']['backendlang']['Code']) ? $data['backendlang']['backendlang']['Code'] :'' }}
							@if(empty(request('code_desc')) && empty(request('code_asc')))
								<a href="{{ route('quiz_records_index', ['code_desc=DESC']) }}" 
								class="{{ !empty(request('code_desc')) ? 'selected' : '' }}">
									<i class="bi bi-sort"></i>
									<input type="hidden" name="sort_data" value="0">
								</a>
							@else
								@if(!empty(request('code_desc')))
									<a href="{{ route('quiz_records_index', ['code_asc=ASC']) }}" 
									class="{{ !empty(request('code_asc')) ? 'selected' : '' }}">
										<i class="bi bi-sort"></i>
										<input type="hidden" name="sort_data" value="1">
									</a>
								@elseif(!empty(request('code_asc')))
									<a href="{{ route('quiz_records_index', ['code_desc=DESC']) }}" 
									class="{{ !empty(request('code_desc')) ? 'selected' : '' }}">
										<i class="bi bi-sort"></i>
										<input type="hidden" name="sort_data" value="0">
									</a>
								@endif
							@endif
						</th>
						<th>{{ isset($data['backendlang']['backendlang']['Name']) ? $data['backendlang']['backendlang']['Name'] :'' }}
							@if(empty(request('f_name_desc')) && empty(request('f_name_asc')))
								<a href="{{ route('quiz_records_index', ['f_name_desc=DESC']) }}" 
								class="{{ !empty(request('f_name_desc')) ? 'selected' : '' }}">
									<i class="bi bi-sort"></i>
									<input type="hidden" name="sort_data" value="0">
								</a>
							@else
								@if(!empty(request('f_name_desc')))
									<a href="{{ route('quiz_records_index', ['f_name_asc=ASC']) }}" 
									class="{{ !empty(request('f_name_asc')) ? 'selected' : '' }}">
										<i class="bi bi-sort"></i>
										<input type="hidden" name="sort_data" value="1">
									</a>
								@elseif(!empty(request('f_name_asc')))
									<a href="{{ route('quiz_records_index', ['f_name_desc=DESC']) }}" 
									class="{{ !empty(request('f_name_desc')) ? 'selected' : '' }}">
										<i class="bi bi-sort"></i>
										<input type="hidden" name="sort_data" value="0">
									</a>
								@endif
							@endif
						</th>
						{{-- <th>{{ isset($data['backendlang']['backendlang']['Email']) ? $data['backendlang']['backendlang']['Email'] :'' }}
							@if(empty(request('email_desc')) && empty(request('email_asc')))
								<a href="{{ route('quiz_records_index', ['email_desc=DESC']) }}" 
								class="{{ !empty(request('email_desc')) ? 'selected' : '' }}">
									<i class="bi bi-sort"></i>
									<input type="hidden" name="sort_data" value="0">
								</a>
							@else
								@if(!empty(request('email_desc')))
									<a href="{{ route('quiz_records_index', ['email_asc=ASC']) }}" 
									class="{{ !empty(request('email_asc')) ? 'selected' : '' }}">
										<i class="bi bi-sort"></i>
										<input type="hidden" name="sort_data" value="1">
									</a>
								@elseif(!empty(request('email_asc')))
									<a href="{{ route('quiz_records_index', ['email_desc=DESC']) }}" 
									class="{{ !empty(request('email_desc')) ? 'selected' : '' }}">
										<i class="bi bi-sort"></i>
										<input type="hidden" name="sort_data" value="0">
									</a>
								@endif
							@endif
						</th> --}}
						<th>{{ isset($data['backendlang']['backendlang']['Phone']) ? $data['backendlang']['backendlang']['Phone'] :'' }}
							@if(empty(request('phone_desc')) && empty(request('phone_asc')))
								<a href="{{ route('quiz_records_index', ['phone_desc=DESC']) }}" 
								class="{{ !empty(request('phone_desc')) ? 'selected' : '' }}">
									<i class="bi bi-sort"></i>
									<input type="hidden" name="sort_data" value="0">
								</a>
							@else
								@if(!empty(request('phone_desc')))
									<a href="{{ route('quiz_records_index', ['phone_asc=ASC']) }}" 
									class="{{ !empty(request('phone_asc')) ? 'selected' : '' }}">
										<i class="bi bi-sort"></i>
										<input type="hidden" name="sort_data" value="1">
									</a>
								@elseif(!empty(request('phone_asc')))
									<a href="{{ route('quiz_records_index', ['phone_desc=DESC']) }}" 
									class="{{ !empty(request('phone_desc')) ? 'selected' : '' }}">
										<i class="bi bi-sort"></i>
										<input type="hidden" name="sort_data" value="0">
									</a>
								@endif
							@endif
						</th>
						<th>{{ isset($data['backendlang']['backendlang']['Date']) ? $data['backendlang']['backendlang']['Date'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Action']) ? $data['backendlang']['backendlang']['Action'] :'' }}</th>
					</tr>
				</thead>
				<tbody>
					@if(!$records->isEmpty())
						@foreach($records as $key => $record)
						<tr>
							<td>
								{{ $key+1 }}
								<input type="hidden" class="row_id" value="{{ $record->id }}">
							</td>
							<td>{{ $record->code ?? '-' }}</td>
							<td>{{ $record->f_name ?? '-' }}</td>
							{{-- <td>{{ $record->email ?? '-' }}</td> --}}
							<td>{{ $record->phone ?? '-' }}</td>
							<td>{{ $record->created_at }}</td>
							<td>
								@if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['quiz-result']))
									<a href="{{ route('quiz_records_view', $record->id) }}" class=" btn btn-outline-primary btn-sm" title="{{ isset($data['backendlang']['backendlang']['View']) ? $data['backendlang']['backendlang']['View'] :'' }}">
										<i class="ace-icon bi bi-eye bigger-130"></i>
									</a>
									&nbsp;
								@endif
							</td>
						</tr>
						@endforeach
					@else
						<tr>
							<td colspan="5">{{ isset($data['backendlang']['backendlang']['No_Result_Found']) ? $data['backendlang']['backendlang']['No_Result_Found'] :'' }}</td>
						</tr>
					@endif
				</tbody>
			</table>
			{{ $records->links() }}
		</div>
	</div>
</div>
@endsection

@section('js')

@endsection