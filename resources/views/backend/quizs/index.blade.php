@extends('layouts.admin_app')
@section('content')
<div class="container-box form-group">
	<h3>
		{{ isset($data['backendlang']['backendlang']['Filter']) ? $data['backendlang']['backendlang']['Filter'] :'' }}
	</h3>
	<hr>
	<form action="{{ route('quiz.quizs.index') }}" method="GET">
		<div class="row">
			<div class="col-12">
				<div class="form-group">
					<input type="text" class="form-control" name="quiz_title" value="{{ !empty('quiz_title') && request('quiz_title') ? request('quiz_title') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Title']) ? $data['backendlang']['backendlang']['Search_Title'] :'' }}">
				</div>
			</div>

			<div class="col-12">
				<div class="form-group">
					<select class="form-control" name="status">
						<option value="">{{ isset($data['backendlang']['backendlang']['Select_Status']) ? $data['backendlang']['backendlang']['Select_Status'] :'' }}</option>
						<option {{ (!empty(request('status')) && request('status') == '1') ? 'selected' : '' }} value="1">{{ isset($data['backendlang']['backendlang']['Active']) ? $data['backendlang']['backendlang']['Active'] :'' }}</option>
						<option {{ (!empty(request('status')) && request('status') == '2') ? 'selected' : '' }} value="2">{{ isset($data['backendlang']['backendlang']['Inactive']) ? $data['backendlang']['backendlang']['Inactive'] :'' }}</option>
					</select>
				</div>
			</div>
		</div>

		<div class="form-group">
			<div class="row">
				<div class="col-sm-2">
					<div class="form-group">
						{{ isset($data['backendlang']['backendlang']['Row_Per_Page']) ? $data['backendlang']['backendlang']['Row_Per_Page'] :'' }}: <br>
						<select class="input-small" name="per_page">
							<option value="10">10</option>
							<option value="20">20</option>
							<option value="50">50</option>
						</select>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<button class="btn btn-outline-primary btn-sm">
				<i class="bi bi-search"></i> {{ isset($data['backendlang']['backendlang']['Search']) ? $data['backendlang']['backendlang']['Search'] :'' }}
			</button>
			<a href="{{ route('quiz.quizs.index') }}" class="btn btn-warning btn-sm">
				<i class="bi bi-arrow-clockwise"></i> {{ isset($data['backendlang']['backendlang']['Clear_Search']) ? $data['backendlang']['backendlang']['Clear_Search'] :'' }}
			</a>
		</div>
	</form>
</div>
<div class="form-group container-box">
	@if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['quiz-insert']))
	<div class="form-group" align="right">
		<a href="{{ route('quiz.quizs.create') }}" class="btn btn-outline-success btn-sm">
			<i class="bi bi-plus"></i> {{ isset($data['backendlang']['backendlang']['Add_New_Quiz']) ? $data['backendlang']['backendlang']['Add_New_Quiz'] :'' }}
		</a>
	</div>
	@endif
	<div class="row">
		<div class="col-12">
			<table class="table table-bordered">
				<thead>
					<tr class="info">
						<th>#</th>
						<th>{{ isset($data['backendlang']['backendlang']['Title']) ? $data['backendlang']['backendlang']['Title'] :'' }}
							@if(empty(request('product_name_desc')) && empty(request('product_name_asc')))
								<a href="{{ route('quiz.quizs.index', ['product_name_desc=DESC']) }}" 
								class="{{ !empty(request('product_name_desc')) ? 'selected' : '' }}">
									<i class="bi bi-sort"></i>
									<input type="hidden" name="sort_data" value="0">
								</a>
							@else
								@if(!empty(request('product_name_desc')))
									<a href="{{ route('quiz.quizs.index', ['product_name_asc=ASC']) }}" 
									class="{{ !empty(request('product_name_asc')) ? 'selected' : '' }}">
										<i class="bi bi-sort"></i>
										<input type="hidden" name="sort_data" value="1">
									</a>
								@elseif(!empty(request('product_name_asc')))
									<a href="{{ route('quiz.quizs.index', ['product_name_desc=DESC']) }}" 
									class="{{ !empty(request('product_name_desc')) ? 'selected' : '' }}">
										<i class="bi bi-sort"></i>
										<input type="hidden" name="sort_data" value="0">
									</a>
								@endif
							@endif
						</th>
						<th>{{ isset($data['backendlang']['backendlang']['Status']) ? $data['backendlang']['backendlang']['Status'] :'' }}
							@if(empty(request('product_status_desc')) && empty(request('product_status_asc')))
								<a href="{{ route('quiz.quizs.index', ['product_status_desc=DESC']) }}" 
								class="{{ !empty(request('product_status_desc')) ? 'selected' : '' }}">
									<i class="bi bi-sort"></i>
									<input type="hidden" name="sort_data" value="0">
								</a>
							@else
								@if(!empty(request('product_status_desc')))
									<a href="{{ route('quiz.quizs.index', ['product_status_asc=ASC']) }}" 
									class="{{ !empty(request('product_status_asc')) ? 'selected' : '' }}">
										<i class="bi bi-sort"></i>
										<input type="hidden" name="sort_data" value="1">
									</a>
								@elseif(!empty(request('product_status_asc')))
									<a href="{{ route('quiz.quizs.index', ['product_status_desc=DESC']) }}" 
									class="{{ !empty(request('product_status_desc')) ? 'selected' : '' }}">
										<i class="bi bi-sort"></i>
										<input type="hidden" name="sort_data" value="0">
									</a>
								@endif
							@endif
						</th>
						<th>{{ isset($data['backendlang']['backendlang']['Action']) ? $data['backendlang']['backendlang']['Action'] :'' }}</th>
					</tr>
				</thead>
				<tbody>
					@if (!$quizs->isEmpty())
					@foreach($quizs as $key => $quiz)
					<tr>
						<td>
							{{ $key+1 }}
							<input type="hidden" class="row_id" value="{{ $quiz->id }}">
						</td>
						<td>{{ $quiz->quiz_title }}</td>
						<td>
							@if ($quiz->status == 1)
								<span class="badge bg-success">
									{{ $data['backendlang']['backendlang']['Active'] ? $data['backendlang']['backendlang']['Active'] : '' }}
								</span>
							@else
								<span class="badge bg-danger">
									{{ $data['backendlang']['backendlang']['Inactive'] ? $data['backendlang']['backendlang']['Inactive'] : '' }}
								</span>
							@endif
						</td>
						<td>
							@if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['quiz-edit']))
							<a href="{{ route('quiz.quizs.edit', $quiz->id) }}" class="btn btn-outline-primary btn-sm">
								<i style="width: 20px;" class="ace-icon bi bi-pencil bigger-130"></i>
							</a>
							@else
							<a href="{{ route('quiz.quizs.edit', $quiz->id) }}" class="btn btn-outline-primary btn-sm">
								<i style="width: 20px;" class="ace-icon bi bi-pencil bigger-130"></i>
							</a>
							@endif

							&nbsp;&nbsp;
							@if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['quiz-edit']))
								@if($quiz->status == 1)
								<a href="#" class="red change-status btn btn-outline-danger btn-sm" data-id="2">
									<i style="width: 20px;" class="ace-icon bi bi-shield-fill-x bigger-130"></i>
								</a>
								@else
								<a href="#" class="green change-status btn btn-outline-success btn-sm" data-id="1">
									<i style="width: 20px;" class="ace-icon bi bi-shield-check bigger-130"></i>
								</a>
								@endif
							@endif

							&nbsp;&nbsp;
							@if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['quiz-delete']))
							<a href="#" class="red change-status btn btn-outline-danger btn-sm" data-id="3">
								<i style="width: 20px;" class="ace-icon bi bi-trash bigger-130"></i>
							</a>
							@endif
						</td>
					</tr>
					@endforeach
					@else
					<tr>
						<td colspan="8"> {{ isset($data['backendlang']['backendlang']['No_Result_Found']) ? $data['backendlang']['backendlang']['No_Result_Found'] :''}}</td>
					</tr>
					@endif
				</tbody>
			</table>
			{{ $quizs->links() }}
		</div>
	</div>
</div>
@endsection

@section('js')
<script type="text/javascript">
	$('.change-status').click(function(){
        $('.loading-gif').show();
        var ele = $(this);
        var row_id = ele.closest('tr').find('.row_id').val();

        var fd = new FormData();
        fd.append('row_id', row_id);
        fd.append('status', ele.data('id'));

        var message;
        if(ele.data('id') == 1){
        	message = confirm("{{ isset($data['backendlang']['backendlang']['Reactive_This_Row']) ? $data['backendlang']['backendlang']['Reactive_This_Row'] :''}}");
        }else if(ele.data('id') == 2){
        	message = confirm("{{ isset($data['backendlang']['backendlang']['Inactive_This_Row']) ? $data['backendlang']['backendlang']['Inactive_This_Row'] :''}}");
        }else{
        	message = confirm("{{ isset($data['backendlang']['backendlang']['Delete_This_Row']) ? $data['backendlang']['backendlang']['Delete_This_Row'] :''}}");
        }

        if(message == true){
	        $.ajax({
	           url: '{{ route("QuizStatus") }}',
	           type: 'post',
	           data: fd,
	           contentType: false,
	           processData: false,
	           success: function(response){
	                $('.loading-gif').hide();
	                toastr.success("{{ isset($data['backendlang']['backendlang']['Status_Changed']) ? $data['backendlang']['backendlang']['Status_Changed'] :''}}");
	                window.location.href="{{ route('quiz.quizs.index') }}";
	           },
	        });
	    }else{
        	$('.loading-gif').hide();
        }
    });
</script>
@endsection