@extends('layouts.admin_app')

@section('content')
<div class="container-box form-group">
	<h3>
		 {{ isset($data['backendlang']['backendlang']['Filter']) ? $data['backendlang']['backendlang']['Filter'] :'' }}
	</h3>
	<hr>
	<form action="{{ route('category.categories.index') }}" method="GET">
	<div class="row">
		<div class="col-sm-12">
			<div class="form-group">
				<input type="text" class="form-control" name="code" value="{{ !empty('code') && request('code') ? request('code') : '' }}" placeholder=" {{ isset($data['backendlang']['backendlang']['Search_Category_Name']) ? $data['backendlang']['backendlang']['Search_Category_Name'] :'' }}">
			</div>
		</div>

		<div class="col-sm-12">
			<div class="form-group">
				<input type="text" class="form-control" name="category_name" value="{{ !empty('category_name') && request('category_name') ? request('category_name') : '' }}" placeholder=" {{ isset($data['backendlang']['backendlang']['Search_Category_Name']) ? $data['backendlang']['backendlang']['Search_Category_Name'] :'' }}">
			</div>
		</div>

		<div class="col-sm-12">
			<div class="form-group">
				<select class="form-control" name="status">
					<option value=""> {{ isset($data['backendlang']['backendlang']['Select_Status']) ? $data['backendlang']['backendlang']['Select_Status'] :'' }}</option>
					<option {{ (!empty(request('status')) && request('status') == '1') ? 'selected' : '' }} value="1"> {{ isset($data['backendlang']['backendlang']['Active']) ? $data['backendlang']['backendlang']['Active'] :'' }}</option>
					<option {{ (!empty(request('status')) && request('status') == '2') ? 'selected' : '' }} value="2"> {{ isset($data['backendlang']['backendlang']['Inactive']) ? $data['backendlang']['backendlang']['Inactive'] :'' }}</option>
				</select>
			</div>
		</div>
	</div>

	<div class="form-group">
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group">
					 {{ isset($data['backendlang']['backendlang']['Item_Per_Page']) ? $data['backendlang']['backendlang']['Item_Per_Page'] :'' }}: <br>
					<select class="input-small" name="per_page">
						<option {{ (!empty(request('per_page')) && request('per_page') == '10') ? 'selected' : '' }} value="10">10</option>
						<option {{ (!empty(request('per_page')) && request('per_page') == '20') ? 'selected' : '' }} value="20">20</option>
						<option {{ (!empty(request('per_page')) && request('per_page') == '50') ? 'selected' : '' }} value="50">50</option>
					</select>
				</div>
			</div>
		</div>
	</div>
	<div class="">
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group">
					<button class="btn btn-outline-primary btn-sm">
						<i class="bi bi-search"></i>  {{ isset($data['backendlang']['backendlang']['Search']) ? $data['backendlang']['backendlang']['Search'] :'' }}
					</button>
					<a href="{{ route('category.categories.index') }}" class="btn btn-warning btn-sm">
						<i class="bi bi-arrow-clockwise"></i>  {{ isset($data['backendlang']['backendlang']['Clear_Search']) ? $data['backendlang']['backendlang']['Clear_Search'] :'' }}
					</a>
				</div>
			</div>
		</div>
	</div>
	</form>
</div>
<div class="container-box">
	@if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['category-insert']))
	<div class="form-group" align="right">
		<a href="{{ route('category.categories.create') }}" class="btn btn-outline-success btn-sm">
			<i class="bi bi-plus"></i>  {{ isset($data['backendlang']['backendlang']['Add_New_Category']) ? $data['backendlang']['backendlang']['Add_New_Category'] :'' }}
		</a>
	</div>
	@endif
	<div class="row">
		<div class="col-12">
			{{ $categories->links() }}
			<table class="table table-bordered">
				<thead>
					<tr class="info">
						<th>#</th>
						<th> {{ isset($data['backendlang']['backendlang']['Code']) ? $data['backendlang']['backendlang']['Code'] :'' }}
							@if(empty(request('category_code_desc')) && empty(request('category_code_asc')))
								<a href="{{ route('category.categories.index', ['category_code_desc=DESC']) }}" 
								   class="{{ !empty(request('category_code_desc')) ? 'selected' : '' }}">
									<i class="bi bi-sort-down"></i>
									<input type="hidden" name="sort_data" value="0">
								</a>
							@else
								@if(!empty(request('category_code_desc')))
									<a href="{{ route('category.categories.index', ['category_code_asc=ASC']) }}" 
									   class="{{ !empty(request('category_code_asc')) ? 'selected' : '' }}">
										<i class="bi bi-sort-down"></i>
										<input type="hidden" name="sort_data" value="1">
									</a>
								@elseif(!empty(request('category_code_asc')))
									<a href="{{ route('category.categories.index', ['category_code_desc=DESC']) }}" 
									   class="{{ !empty(request('category_code_desc')) ? 'selected' : '' }}">
										<i class="bi bi-sort-up"></i>
										<input type="hidden" name="sort_data" value="0">
									</a>
								@endif
							@endif
						</th>
						<th> {{ isset($data['backendlang']['backendlang']['Name']) ? $data['backendlang']['backendlang']['Name'] :'' }}
							@if(empty(request('category_name_desc')) && empty(request('category_name_asc')))
								<a href="{{ route('category.categories.index', ['category_name_desc=DESC']) }}" 
								   class="{{ !empty(request('category_name_desc')) ? 'selected' : '' }}">
									<i class="bi bi-sort-down"></i>
									<input type="hidden" name="sort_data" value="0">
								</a>
							@else
								@if(!empty(request('category_name_desc')))
									<a href="{{ route('category.categories.index', ['category_name_asc=ASC']) }}" 
									   class="{{ !empty(request('category_name_asc')) ? 'selected' : '' }}">
										<i class="bi bi-sort-down"></i>
										<input type="hidden" name="sort_data" value="1">
									</a>
								@elseif(!empty(request('category_name_asc')))
									<a href="{{ route('category.categories.index', ['category_name_desc=DESC']) }}" 
									   class="{{ !empty(request('category_name_desc')) ? 'selected' : '' }}">
										<i class="bi bi-sort-up"></i>
										<input type="hidden" name="sort_data" value="0">
									</a>
								@endif
							@endif
						</th>
						<th> {{ isset($data['backendlang']['backendlang']['Status']) ? $data['backendlang']['backendlang']['Status'] :'' }}
							@if(empty(request('status_desc')) && empty(request('status_asc')))
								<a href="{{ route('category.categories.index', ['status_desc=DESC']) }}" 
								   class="{{ !empty(request('status_desc')) ? 'selected' : '' }}">
									<i class="bi bi-sort-down"></i>
									<input type="hidden" name="sort_data" value="0">
								</a>
							@else
								@if(!empty(request('status_desc')))
									<a href="{{ route('category.categories.index', ['status_asc=ASC']) }}" 
									   class="{{ !empty(request('status_asc')) ? 'selected' : '' }}">
										<i class="bi bi-sort-down"></i>
										<input type="hidden" name="sort_data" value="1">
									</a>
								@elseif(!empty(request('status_asc')))
									<a href="{{ route('category.categories.index', ['status_desc=DESC']) }}" 
									   class="{{ !empty(request('status_desc')) ? 'selected' : '' }}">
										<i class="bi bi-sort-up"></i>
										<input type="hidden" name="sort_data" value="0">
									</a>
								@endif
							@endif
						</th>
						<th> {{ isset($data['backendlang']['backendlang']['Action']) ? $data['backendlang']['backendlang']['Action'] :'' }}</th>
					</tr>
				</thead>
				<tbody>
					@if (!$categories->isEmpty())
					@foreach($categories as $key => $category)
					<tr>
						<td>
							{{ $key+1 }}
							<input type="hidden" class="row_id" value="{{ $category->id }}">
						</td>
						<td>{{ $category->code }}</td>
						<td>{{ $category->category_name }}</td>
												<td>
							@if ($category->status == 1)
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
							@if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['category-edit']))
								<a href="{{ route('category.categories.edit', $category->id) }}" class="btn btn-outline-primary btn-sm" title="{{ isset($data['backendlang']['backendlang']['View_Edit']) ? $data['backendlang']['backendlang']['View_Edit'] :'' }}">
									<i class="ace-icon bi bi-pencil bigger-130"></i>
								</a>
								&nbsp;&nbsp;
								@if($category->status == 1)
								<a href="#" class="red change-status btn btn-outline-danger btn-sm" data-id="2" title="{{ isset($data['backendlang']['backendlang']['Inactive']) ? $data['backendlang']['backendlang']['Inactive'] :'' }}">
									<i class="ace-icon bi bi-shield-fill-x bigger-130"></i> 
								</a>
								@else
								<a href="#" class="green change-status btn btn-outline-success btn-sm" data-id="1" title="{{ isset($data['backendlang']['backendlang']['Reactive']) ? $data['backendlang']['backendlang']['Reactive'] :'' }}">
									<i class="ace-icon bi bi-shield-check bigger-130"></i> 
								</a>
								@endif
								&nbsp;&nbsp;
							@endif
							@if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['category-delete']))
							<a href="#" class="red change-status btn btn-outline-danger btn-sm" data-id="3" title="{{ isset($data['backendlang']['backendlang']['Delete']) ? $data['backendlang']['backendlang']['Delete'] :'' }}">
								<i class="ace-icon bi bi-trash bigger-130"></i> 
							</a>
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
			{{ $categories->links() }}
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
        	message = confirm("{{ isset($data['backendlang']['backendlang']['Reactive_This_Row']) ? $data['backendlang']['backendlang']['Reactive_This_Row'] :'' }}");
        }else if(ele.data('id') == 2){
        	message = confirm("{{ isset($data['backendlang']['backendlang']['Inactive_This_Row']) ? $data['backendlang']['backendlang']['Inactive_This_Row'] :'' }}");
        }else{
        	message = confirm("{{ isset($data['backendlang']['backendlang']['Delete_This_Row']) ? $data['backendlang']['backendlang']['Delete_This_Row'] :'' }}");
        }
        
		if(message == true){
	        $.ajax({
	           url: '{{ route("CategoryStatus") }}',
	           type: 'post',
	           data: fd,
	           contentType: false,
	           processData: false,
	           success: function(response){
	                $('.loading-gif').hide();
					if(response == 'ok'){
	                toastr.success("{{ isset($data['backendlang']['backendlang']['Status_Changed']) ? $data['backendlang']['backendlang']['Status_Changed'] :'' }}");
					location.reload();
					}
	                //window.location.href="{{ route('sub_category.sub_categories.index') }}";
	           },
			   complete: function(){
            // Reload the page after the AJAX call is completed
 		           location.reload();
    	    }
	        });
	    }else{
	    	$('.loading-gif').hide();
	    }
    });
</script>
@endsection