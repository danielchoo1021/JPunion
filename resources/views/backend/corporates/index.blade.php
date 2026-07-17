@extends('layouts.admin_app')

@section('content')
<form action="{{ route('corporate.corporates.index') }}" method="GET">
<div class="row">
	<div class="col-sm-12">
		<div class="form-group">
			<input type="text" class="form-control" name="code" value="{{ !empty('code') && request('code') ? request('code') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Distributor_Code']) ? $data['backendlang']['backendlang']['Search_Distributor_Code'] :'' }}">
		</div>
	</div>
	<div class="col-sm-12">
		<div class="form-group">
			<input type="text" class="form-control" name="corporate_name" value="{{ !empty('corporate_name') && request('corporate_name') ? request('corporate_name') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Distributor_Name']) ? $data['backendlang']['backendlang']['Search_Distributor_Name'] :'' }}">
		</div>
	</div>
	<div class="col-sm-12">
		<div class="form-group">
			<input type="text" class="form-control" name="ic" value="{{ !empty('ic') && request('ic') ? request('ic') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Distributor_NRIC_No']) ? $data['backendlang']['backendlang']['Search_Distributor_NRIC_No'] :'' }}">
		</div>
	</div>

	<div class="col-sm-12">
		<div class="form-group">
			<select class="form-control" name="status">
				<option value="">{{ isset($data['backendlang']['backendlang']['Search_Status']) ? $data['backendlang']['backendlang']['Search_Status'] :'' }}</option>
				<option {{ (!empty(request('status')) && request('status') == '1') ? 'selected' : '' }} value="1">{{ isset($data['backendlang']['backendlang']['Active']) ? $data['backendlang']['backendlang']['Active'] :'' }}</option>
				<option {{ (!empty(request('status')) && request('status') == '2') ? 'selected' : '' }} value="2">{{ isset($data['backendlang']['backendlang']['Inactive']) ? $data['backendlang']['backendlang']['Inactive'] :'' }}</option>
			</select>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-sm-12">
		<div class="form-group">
			<input type="text" class="form-control" name="referrer_code" value="{{ !empty('referrer_code') && request('referrer_code') ? request('referrer_code') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Referrer_Code']) ? $data['backendlang']['backendlang']['Search_Referrer_Code'] :'' }}">
		</div>
	</div>
	<div class="col-sm-12">
		<div class="form-group">
			<input type="text" class="form-control" name="referrer_name" value="{{ !empty('referrer_name') && request('referrer_name') ? request('referrer_name') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Referrer_Name']) ? $data['backendlang']['backendlang']['Search_Referrer_Name'] :'' }}">
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
		<i class="fa fa-search"></i> {{ isset($data['backendlang']['backendlang']['Search']) ? $data['backendlang']['backendlang']['Search'] :'' }}
	</button>
	<a href="{{ route('corporate.corporates.index') }}" class="btn btn-warning btn-sm">
		<i class="fa fa-refresh"></i> {{ isset($data['backendlang']['backendlang']['Clear_Search']) ? $data['backendlang']['backendlang']['Clear_Search'] :'' }}
	</a>
</div>
<div class="row" style="overflow: auto;">
	<div class="col-12">
		<table class="table table-bordered">
			<thead>
				<tr class="info">
					<th>#</th>
					<th>{{ isset($data['backendlang']['backendlang']['Code']) ? $data['backendlang']['backendlang']['Code'] :'' }}
						<br>
						@if(empty(request('display_code_desc')) && empty(request('display_code_asc')))
							<a href="{{ route('corporate.corporates.index', ['display_code_desc=DESC']) }}" 
							   class="{{ !empty(request('display_code_desc')) ? 'selected' : '' }}">
								<i class="fa fa-sort"></i>
								<input type="hidden" name="sort_data" value="0">
							</a>
						@else
							@if(!empty(request('display_code_desc')))
								<a href="{{ route('corporate.corporates.index', ['display_code_asc=ASC']) }}" 
								   class="{{ !empty(request('display_code_asc')) ? 'selected' : '' }}">
									<i class="fa fa-sort"></i>
									<input type="hidden" name="sort_data" value="1">
								</a>
							@elseif(!empty(request('display_code_asc')))
								<a href="{{ route('corporate.corporates.index', ['display_code_desc=DESC']) }}" 
								   class="{{ !empty(request('display_code_desc')) ? 'selected' : '' }}">
									<i class="fa fa-sort"></i>
									<input type="hidden" name="sort_data" value="0">
								</a>
							@endif
						@endif
					</th>
					<th>{{ isset($data['backendlang']['backendlang']['Name']) ? $data['backendlang']['backendlang']['Name'] :'' }}
						<br>
						@if(empty(request('name_desc')) && empty(request('name_asc')))
							<a href="{{ route('corporate.corporates.index', ['name_desc=DESC']) }}" 
							   class="{{ !empty(request('name_desc')) ? 'selected' : '' }}">
								<i class="fa fa-sort"></i>
								<input type="hidden" name="sort_data" value="0">
							</a>
						@else
							@if(!empty(request('name_desc')))
								<a href="{{ route('corporate.corporates.index', ['name_asc=ASC']) }}" 
								   class="{{ !empty(request('name_asc')) ? 'selected' : '' }}">
									<i class="fa fa-sort"></i>
									<input type="hidden" name="sort_data" value="1">
								</a>
							@elseif(!empty(request('name_asc')))
								<a href="{{ route('corporate.corporates.index', ['name_desc=DESC']) }}" 
								   class="{{ !empty(request('name_desc')) ? 'selected' : '' }}">
									<i class="fa fa-sort"></i>
									<input type="hidden" name="sort_data" value="0">
								</a>
							@endif
						@endif
					</th>
					<th>{{ isset($data['backendlang']['backendlang']['Email']) ? $data['backendlang']['backendlang']['Email'] :'' }}
						<br>
						@if(empty(request('email_desc')) && empty(request('email_asc')))
							<a href="{{ route('corporate.corporates.index', ['email_desc=DESC']) }}" 
							   class="{{ !empty(request('email_desc')) ? 'selected' : '' }}">
								<i class="fa fa-sort"></i>
								<input type="hidden" name="sort_data" value="0">
							</a>
						@else
							@if(!empty(request('email_desc')))
								<a href="{{ route('corporate.corporates.index', ['email_asc=ASC']) }}" 
								   class="{{ !empty(request('email_asc')) ? 'selected' : '' }}">
									<i class="fa fa-sort"></i>
									<input type="hidden" name="sort_data" value="1">
								</a>
							@elseif(!empty(request('email_asc')))
								<a href="{{ route('corporate.corporates.index', ['email_desc=DESC']) }}" 
								   class="{{ !empty(request('email_desc')) ? 'selected' : '' }}">
									<i class="fa fa-sort"></i>
									<input type="hidden" name="sort_data" value="0">
								</a>
							@endif
						@endif
					</th>
					<th>{{ isset($data['backendlang']['backendlang']['Company_Registration_No']) ? $data['backendlang']['backendlang']['Company_Registration_No'] :'' }}
						<br>
						@if(empty(request('company_num_desc')) && empty(request('company_num_asc')))
							<a href="{{ route('corporate.corporates.index', ['company_num_desc=DESC']) }}" 
							   class="{{ !empty(request('company_num_desc')) ? 'selected' : '' }}">
								<i class="fa fa-sort"></i>
								<input type="hidden" name="sort_data" value="0">
							</a>
						@else
							@if(!empty(request('company_num_desc')))
								<a href="{{ route('corporate.corporates.index', ['company_num_asc=ASC']) }}" 
								   class="{{ !empty(request('company_num_asc')) ? 'selected' : '' }}">
									<i class="fa fa-sort"></i>
									<input type="hidden" name="sort_data" value="1">
								</a>
							@elseif(!empty(request('company_num_asc')))
								<a href="{{ route('corporate.corporates.index', ['company_num_desc=DESC']) }}" 
								   class="{{ !empty(request('company_num_desc')) ? 'selected' : '' }}">
									<i class="fa fa-sort"></i>
									<input type="hidden" name="sort_data" value="0">
								</a>
							@endif
						@endif
					</th>
					<th>{{ isset($data['backendlang']['backendlang']['Phone']) ? $data['backendlang']['backendlang']['Phone'] :'' }}
						<br>
						@if(empty(request('phone_desc')) && empty(request('phone_asc')))
							<a href="{{ route('corporate.corporates.index', ['phone_desc=DESC']) }}" 
							   class="{{ !empty(request('phone_desc')) ? 'selected' : '' }}">
								<i class="fa fa-sort"></i>
								<input type="hidden" name="sort_data" value="0">
							</a>
						@else
							@if(!empty(request('phone_desc')))
								<a href="{{ route('corporate.corporates.index', ['phone_asc=ASC']) }}" 
								   class="{{ !empty(request('phone_asc')) ? 'selected' : '' }}">
									<i class="fa fa-sort"></i>
									<input type="hidden" name="sort_data" value="1">
								</a>
							@elseif(!empty(request('phone_asc')))
								<a href="{{ route('corporate.corporates.index', ['phone_desc=DESC']) }}" 
								   class="{{ !empty(request('phone_desc')) ? 'selected' : '' }}">
									<i class="fa fa-sort"></i>
									<input type="hidden" name="sort_data" value="0">
								</a>
							@endif
						@endif
					</th>
					<th>Status
						<br>
						@if(empty(request('status_desc')) && empty(request('status_asc')))
							<a href="{{ route('corporate.corporates.index', ['status_desc=DESC']) }}" 
							   class="{{ !empty(request('status_desc')) ? 'selected' : '' }}">
								<i class="fa fa-sort"></i>
								<input type="hidden" name="sort_data" value="0">
							</a>
						@else
							@if(!empty(request('status_desc')))
								<a href="{{ route('corporate.corporates.index', ['status_asc=ASC']) }}" 
								   class="{{ !empty(request('status_asc')) ? 'selected' : '' }}">
									<i class="fa fa-sort"></i>
									<input type="hidden" name="sort_data" value="1">
								</a>
							@elseif(!empty(request('status_asc')))
								<a href="{{ route('corporate.corporates.index', ['status_desc=DESC']) }}" 
								   class="{{ !empty(request('status_desc')) ? 'selected' : '' }}">
									<i class="fa fa-sort"></i>
									<input type="hidden" name="sort_data" value="0">
								</a>
							@endif
						@endif
					</th>
					<th>{{ isset($data['backendlang']['backendlang']['Join_Date']) ? $data['backendlang']['backendlang']['Join_Date'] :'' }}
						<br>
						@if(empty(request('created_at_desc')) && empty(request('created_at_asc')))
							<a href="{{ route('corporate.corporates.index', ['created_at_desc=DESC']) }}" 
							   class="{{ !empty(request('created_at_desc')) ? 'selected' : '' }}">
								<i class="fa fa-sort"></i>
								<input type="hidden" name="sort_data" value="0">
							</a>
						@else
							@if(!empty(request('created_at_desc')))
								<a href="{{ route('corporate.corporates.index', ['created_at_asc=ASC']) }}" 
								   class="{{ !empty(request('created_at_asc')) ? 'selected' : '' }}">
									<i class="fa fa-sort"></i>
									<input type="hidden" name="sort_data" value="1">
								</a>
							@elseif(!empty(request('created_at_asc')))
								<a href="{{ route('corporate.corporates.index', ['created_at_desc=DESC']) }}" 
								   class="{{ !empty(request('created_at_desc')) ? 'selected' : '' }}">
									<i class="fa fa-sort"></i>
									<input type="hidden" name="sort_data" value="0">
								</a>
							@endif
						@endif
					</th>
					<th>{{ isset($data['backendlang']['backendlang']['Action']) ? $data['backendlang']['backendlang']['Action'] :'' }}</th>
				</tr>
			</thead>
			<tbody>
				@if (!$corporates->isEmpty())
				@foreach($corporates as $key => $corporate)
				<tr>
					<td>
						{{ $key+1 }}
						<input type="hidden" class="row_id" value="{{ $corporate->id }}">
					</td>
					<td>{{ $corporate->display_code }}{{ $corporate->display_running_no }}</td>
					<td>{{ $corporate->f_name }} {{ $corporate->l_name }}</td>
					<td>{{ $corporate->email }}</td>
					<td>
						@if(!empty($corporate->company_registration_no))
							{{ $corporate->company_registration_no }}
						@else
							-
						@endif
					</td>
					<td>{{ $corporate->full_phone }}</td>
					<td>
					<td>
						@if ($corporate->status == 1)
>							<span class="badge bg-success">
								{{ $data['backendlang']['backendlang']['Active'] ?? '' }}
							</span>
						@else
							<span class="badge bg-danger">
								{{ $data['backendlang']['backendlang']['Inactive'] ?? '' }}
							</span>
							@endif
						</td>
					</td>
					<td>{{ $corporate->created_at }}</td>
					<td>
						<a href="{{ route('corporate.corporates.edit', $corporate->id) }}">
							<i class="ace-icon fa fa-pencil bigger-130"></i> {{ isset($data['backendlang']['backendlang']['Edit']) ? $data['backendlang']['backendlang']['Edit'] :'' }}
						</a>

						&nbsp;&nbsp;
						@if($corporate->status == 1)
						<a href="#" class="red change-status" data-id="2">
							<i class="ace-icon fa fa-ban bigger-130"></i> {{ isset($data['backendlang']['backendlang']['Inactive']) ? $data['backendlang']['backendlang']['Inactive'] :'' }}
						</a>
						@else
						<a href="#" class="green change-status" data-id="1">
							<i class="ace-icon fa fa-check bigger-130"></i> {{ isset($data['backendlang']['backendlang']['Reactive']) ? $data['backendlang']['backendlang']['Reactive'] :'' }}
						</a>
						@endif

						&nbsp;&nbsp;
						<a href="#" class="red change-status" data-id="3">
							<i class="ace-icon fa fa-trash-o bigger-130"></i> {{ isset($data['backendlang']['backendlang']['Delete']) ? $data['backendlang']['backendlang']['Delete'] :'' }}
						</a>

						<!-- &nbsp;&nbsp;
						<a href="{{ route('tree', [$corporate->code]) }}" class="green">
							<i class="ace-icon fa fa-users bigger-130"></i> Affiliate
						</a> -->
					</td>
				</tr>
				@endforeach
				@else
				<tr>
					<td colspan="13">{{ isset($data['backendlang']['backendlang']['No_Result_Found']) ? $data['backendlang']['backendlang']['No_Result_Found'] :'' }}</td>
				</tr>
				@endif
			</tbody>
		</table>
		{{ $corporates->links() }}
	</div>
</div>
</form>
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
	           url: '{{ route("CorporateStatus") }}',
	           type: 'post',
	           data: fd,
	           contentType: false,
	           processData: false,
	           success: function(response){
	                $('.loading-gif').hide();
	                toastr.success("{{ isset($data['backendlang']['backendlang']['Status_Changed']) ? $data['backendlang']['backendlang']['Status_Changed'] :'' }}");
	                window.location.href="{{ route('corporate.corporates.index') }}";
	           },
	        });
	    }else{
        	$('.loading-gif').hide();
        }
    });
</script>
@endsection