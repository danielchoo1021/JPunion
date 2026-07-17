@extends('layouts.admin_app')

@section('content')
<div class="page-header">
    <h1>
        {{ isset($data['backendlang']['backendlang']['Staff_Branch_List']) ? $data['backendlang']['backendlang']['Staff_Branch_List'] :'' }}
        <!-- <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
        </small> -->
    </h1>
</div>
<form action="{{ route('staff.staffs.index') }}" method="GET">
<div class="row">
	<div class="col-sm-2">
		<div class="form-group">
			<input type="text" class="form-control" name="code" value="{{ !empty('code') && request('code') ? request('code') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Staff_Code']) ? $data['backendlang']['backendlang']['Search_Staff_Code'] :'' }}">
		</div>
	</div>
	<div class="col-sm-2">
		<div class="form-group">
			<input type="text" class="form-control" name="staff_name" value="{{ !empty('staff_name') && request('staff_name') ? request('staff_name') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Staff_Name']) ? $data['backendlang']['backendlang']['Search_Staff_Name'] :'' }}">
		</div>
	</div>

	<!-- <div class="col-sm-2">
		<div class="form-group">
			<select class="form-control" name="lvl">
				<option value="">Select Level</option>
				@foreach($agent_lvls as $agent_lvl)
				<option {{ (!empty(request('lvl')) && request('lvl') == $agent_lvl->id) ? 'selected' : '' }} value="{{ $agent_lvl->id }}">{{ $agent_lvl->agent_lvl }}</option>
				@endforeach
			</select>
		</div>
	</div> -->
	<div class="col-sm-2">
		<div class="form-group">
			<select class="form-control" name="perm_lvl">
				<option value="">{{ isset($data['backendlang']['backendlang']['Search_Permission_Level']) ? $data['backendlang']['backendlang']['Search_Permission_Level'] :'' }}</option>
				<option {{ (!empty(request('perm_lvl')) && request('perm_lvl') == '1') ? 'selected' : '' }} value="1">{{ isset($data['backendlang']['backendlang']['Super_Admin']) ? $data['backendlang']['backendlang']['Super_Admin'] :'' }}</option>
				<option {{ (!empty(request('perm_lvl')) && request('perm_lvl') == '2') ? 'selected' : '' }} value="2">{{ isset($data['backendlang']['backendlang']['Admin']) ? $data['backendlang']['backendlang']['Admin'] :'' }}</option>
				<option {{ (!empty(request('perm_lvl')) && request('perm_lvl') == '3') ? 'selected' : '' }} value="3">{{ isset($data['backendlang']['backendlang']['Warehouse']) ? $data['backendlang']['backendlang']['Warehouse'] :'' }}</option>
				<option {{ (!empty(request('perm_lvl')) && request('perm_lvl') == '4') ? 'selected' : '' }} value="4">{{ isset($data['backendlang']['backendlang']['Finance']) ? $data['backendlang']['backendlang']['Finance'] :'' }}</option>
				<option {{ (!empty(request('perm_lvl')) && request('perm_lvl') == '5') ? 'selected' : '' }} value="5">{{ isset($data['backendlang']['backendlang']['Logistic']) ? $data['backendlang']['backendlang']['Logistic'] :'' }}</option>
				<option {{ (!empty(request('perm_lvl')) && request('perm_lvl') == '6') ? 'selected' : '' }} value="6">{{ isset($data['backendlang']['backendlang']['IT_Team']) ? $data['backendlang']['backendlang']['IT_Team'] :'' }}</option>
				<option {{ (!empty(request('perm_lvl')) && request('perm_lvl') == '7') ? 'selected' : '' }} value="7">{{ isset($data['backendlang']['backendlang']['Shareholder']) ? $data['backendlang']['backendlang']['Shareholder'] :'' }}</option>
			</select>
		</div>
	</div>

	<div class="col-sm-2">
		<div class="form-group">
			<input type="text" class="form-control" name="ic" value="{{ !empty('ic') && request('ic') ? request('ic') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_NRIC_NO']) ? $data['backendlang']['backendlang']['Search_NRIC_NO'] :'' }}">
		</div>
	</div>

	<div class="col-sm-2">
		<div class="form-group">
			<select class="form-control" name="status">
				<option value="">{{ isset($data['backendlang']['backendlang']['Serach_Status']) ? $data['backendlang']['backendlang']['Search_Status'] :'' }}</option>
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
		<i class="fa fa-search"></i> {{ isset($data['backendlang']['backendlang']['Bank_Slip']) ? $data['backendlang']['backendlang']['Bank_Slip'] :'' }}
	</button>
	<a href="{{ route('staff.staffs.index') }}" class="btn btn-warning btn-sm">
		<i class="fa fa-refresh"></i> {{ isset($data['backendlang']['backendlang']['Clear_Search']) ? $data['backendlang']['backendlang']['Clear_Search'] :'' }}
	</a>
</div>
</form>
<div class="row" style="overflow: auto;">
	<div class="col-12">
		<table class="table table-bordered">
			<thead>
				<tr class="info">
					<th>#</th>
					<th>{{ isset($data['backendlang']['backendlang']['Code']) ? $data['backendlang']['backendlang']['Code'] :'' }}
						<br>
						@if(empty(request('code_desc')) && empty(request('code_asc')))
							<a href="{{ route('staff.staffs.index', ['code_desc=DESC']) }}" 
							   class="{{ !empty(request('code_desc')) ? 'selected' : '' }}">
								<i class="fa fa-sort"></i>
								<input type="hidden" name="sort_data" value="0">
							</a>
						@else
							@if(!empty(request('code_desc')))
								<a href="{{ route('staff.staffs.index', ['code_asc=ASC']) }}" 
								   class="{{ !empty(request('code_asc')) ? 'selected' : '' }}">
									<i class="fa fa-sort"></i>
									<input type="hidden" name="sort_data" value="1">
								</a>
							@elseif(!empty(request('code_asc')))
								<a href="{{ route('staff.staffs.index', ['code_desc=DESC']) }}" 
								   class="{{ !empty(request('code_desc')) ? 'selected' : '' }}">
									<i class="fa fa-sort"></i>
									<input type="hidden" name="sort_data" value="0">
								</a>
							@endif
						@endif
					</th>
					<th>{{ isset($data['backendlang']['backendlang']['Name']) ? $data['backendlang']['backendlang']['Name'] :'' }}
						<br>
						@if(empty(request('name_desc')) && empty(request('name_asc')))
							<a href="{{ route('staff.staffs.index', ['name_desc=DESC']) }}" 
							   class="{{ !empty(request('name_desc')) ? 'selected' : '' }}">
								<i class="fa fa-sort"></i>
								<input type="hidden" name="sort_data" value="0">
							</a>
						@else
							@if(!empty(request('name_desc')))
								<a href="{{ route('staff.staffs.index', ['name_asc=ASC']) }}" 
								   class="{{ !empty(request('name_asc')) ? 'selected' : '' }}">
									<i class="fa fa-sort"></i>
									<input type="hidden" name="sort_data" value="1">
								</a>
							@elseif(!empty(request('name_asc')))
								<a href="{{ route('staff.staffs.index', ['name_desc=DESC']) }}" 
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
							<a href="{{ route('staff.staffs.index', ['email_desc=DESC']) }}" 
							   class="{{ !empty(request('email_desc')) ? 'selected' : '' }}">
								<i class="fa fa-sort"></i>
								<input type="hidden" name="sort_data" value="0">
							</a>
						@else
							@if(!empty(request('email_desc')))
								<a href="{{ route('staff.staffs.index', ['email_asc=ASC']) }}" 
								   class="{{ !empty(request('email_asc')) ? 'selected' : '' }}">
									<i class="fa fa-sort"></i>
									<input type="hidden" name="sort_data" value="1">
								</a>
							@elseif(!empty(request('email_asc')))
								<a href="{{ route('staff.staffs.index', ['email_desc=DESC']) }}" 
								   class="{{ !empty(request('email_desc')) ? 'selected' : '' }}">
									<i class="fa fa-sort"></i>
									<input type="hidden" name="sort_data" value="0">
								</a>
							@endif
						@endif
					</th>
					<th>{{ isset($data['backendlang']['backendlang']['Phone']) ? $data['backendlang']['backendlang']['Phone'] :'' }}
						<br>
						@if(empty(request('phone_desc')) && empty(request('email_asc')))
							<a href="{{ route('staff.staffs.index', ['phone_desc=DESC']) }}" 
							   class="{{ !empty(request('phone_desc')) ? 'selected' : '' }}">
								<i class="fa fa-sort"></i>
								<input type="hidden" name="sort_data" value="0">
							</a>
						@else
							@if(!empty(request('phone_desc')))
								<a href="{{ route('staff.staffs.index', ['phone_asc=ASC']) }}" 
								   class="{{ !empty(request('phone_asc')) ? 'selected' : '' }}">
									<i class="fa fa-sort"></i>
									<input type="hidden" name="sort_data" value="1">
								</a>
							@elseif(!empty(request('phone_asc')))
								<a href="{{ route('staff.staffs.index', ['phone_desc=DESC']) }}" 
								   class="{{ !empty(request('phone_desc')) ? 'selected' : '' }}">
									<i class="fa fa-sort"></i>
									<input type="hidden" name="sort_data" value="0">
								</a>
							@endif
						@endif
					</th>
					<th>{{ isset($data['backendlang']['backendlang']['NRIC_no']) ? $data['backendlang']['backendlang']['NRIC_no'] :'' }}
						<br>
						@if(empty(request('ic_desc')) && empty(request('ic_asc')))
							<a href="{{ route('staff.staffs.index', ['ic_desc=DESC']) }}" 
							   class="{{ !empty(request('ic_desc')) ? 'selected' : '' }}">
								<i class="fa fa-sort"></i>
								<input type="hidden" name="sort_data" value="0">
							</a>
						@else
							@if(!empty(request('ic_desc')))
								<a href="{{ route('staff.staffs.index', ['ic_asc=ASC']) }}" 
								   class="{{ !empty(request('ic_asc')) ? 'selected' : '' }}">
									<i class="fa fa-sort"></i>
									<input type="hidden" name="sort_data" value="1">
								</a>
							@elseif(!empty(request('ic_asc')))
								<a href="{{ route('staff.staffs.index', ['ic_desc=DESC']) }}" 
								   class="{{ !empty(request('ic_desc')) ? 'selected' : '' }}">
									<i class="fa fa-sort"></i>
									<input type="hidden" name="sort_data" value="0">
								</a>
							@endif
						@endif
					</th>
					<th>{{ isset($data['backendlang']['backendlang']['Job_Position']) ? $data['backendlang']['backendlang']['Job_Position'] :'' }}
						<br>
						@if(empty(request('job_desc')) && empty(request('job_asc')))
							<a href="{{ route('staff.staffs.index', ['job_desc=DESC']) }}" 
							   class="{{ !empty(request('job_desc')) ? 'selected' : '' }}">
								<i class="fa fa-sort"></i>
								<input type="hidden" name="sort_data" value="0">
							</a>
						@else
							@if(!empty(request('job_desc')))
								<a href="{{ route('staff.staffs.index', ['job_asc=ASC']) }}" 
								   class="{{ !empty(request('job_asc')) ? 'selected' : '' }}">
									<i class="fa fa-sort"></i>
									<input type="hidden" name="sort_data" value="1">
								</a>
							@elseif(!empty(request('job_asc')))
								<a href="{{ route('staff.staffs.index', ['job_desc=DESC']) }}" 
								   class="{{ !empty(request('job_desc')) ? 'selected' : '' }}">
									<i class="fa fa-sort"></i>
									<input type="hidden" name="sort_data" value="0">
								</a>
							@endif
						@endif
					</th>
					<th>{{ isset($data['backendlang']['backendlang']['Permission']) ? $data['backendlang']['backendlang']['NRIC_no'] :'' }}
						<br>
						@if(empty(request('lvl_desc')) && empty(request('lvl_asc')))
							<a href="{{ route('staff.staffs.index', ['lvl_desc=DESC']) }}" 
							   class="{{ !empty(request('lvl_desc')) ? 'selected' : '' }}">
								<i class="fa fa-sort"></i>
								<input type="hidden" name="sort_data" value="0">
							</a>
						@else
							@if(!empty(request('lvl_desc')))
								<a href="{{ route('staff.staffs.index', ['lvl_asc=ASC']) }}" 
								   class="{{ !empty(request('lvl_asc')) ? 'selected' : '' }}">
									<i class="fa fa-sort"></i>
									<input type="hidden" name="sort_data" value="1">
								</a>
							@elseif(!empty(request('lvl_asc')))
								<a href="{{ route('staff.staffs.index', ['lvl_desc=DESC']) }}" 
								   class="{{ !empty(request('lvl_desc')) ? 'selected' : '' }}">
									<i class="fa fa-sort"></i>
									<input type="hidden" name="sort_data" value="0">
								</a>
							@endif
						@endif
					</th>
					<th>{{ isset($data['backendlang']['backendlang']['Status']) ? $data['backendlang']['backendlang']['Status'] :'' }}
						<br>
						@if(empty(request('status_desc')) && empty(request('status_asc')))
							<a href="{{ route('staff.staffs.index', ['status_desc=DESC']) }}" 
							   class="{{ !empty(request('status_desc')) ? 'selected' : '' }}">
								<i class="fa fa-sort"></i>
								<input type="hidden" name="sort_data" value="0">
							</a>
						@else
							@if(!empty(request('status_desc')))
								<a href="{{ route('staff.staffs.index', ['status_asc=ASC']) }}" 
								   class="{{ !empty(request('status_asc')) ? 'selected' : '' }}">
									<i class="fa fa-sort"></i>
									<input type="hidden" name="sort_data" value="1">
								</a>
							@elseif(!empty(request('status_asc')))
								<a href="{{ route('staff.staffs.index', ['status_desc=DESC']) }}" 
								   class="{{ !empty(request('status_desc')) ? 'selected' : '' }}">
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
				@if (!$staffs->isEmpty())
				@foreach($staffs as $key => $staff)
				<tr>
					<td>
						{{ $key+1 }}
						<input type="hidden" class="row_id" value="{{ $staff->id }}">
					</td>
					<td>{{ $staff->code }}</td>
					<td>{{ $staff->f_name }} {{ $staff->l_name }}</td>
					<td>{{ $staff->email }}</td>
					<td>{{ $staff->full_phone }}</td>
					<td>{{ $staff->ic }}</td>
					<td>
						@if(!empty($staff->job))
						{{ $staff->job }}</td>
						@else
						Empty
						@endif
					<td>
						@if($staff->permission_lvl == '1')
							{{ isset($data['backendlang']['backendlang']['Super_Admin']) ? $data['backendlang']['backendlang']['Super_Admin'] :'' }}
						@elseif($staff->permission_lvl == '2')
							{{ isset($data['backendlang']['backendlang']['Admin']) ? $data['backendlang']['backendlang']['Admin'] :'' }}
						@elseif($staff->permission_lvl == '3')
							{{ isset($data['backendlang']['backendlang']['Warehouse']) ? $data['backendlang']['backendlang']['Warehouse'] :'' }}
						@elseif($staff->permission_lvl == '4')
							{{ isset($data['backendlang']['backendlang']['Finance']) ? $data['backendlang']['backendlang']['Finance'] :'' }}
						@elseif($staff->permission_lvl == '5')
							{{ isset($data['backendlang']['backendlang']['Logistic']) ? $data['backendlang']['backendlang']['Logistic'] :'' }}
						@elseif($staff->permission_lvl == '6')
							{{ isset($data['backendlang']['backendlang']['IT_Team']) ? $data['backendlang']['backendlang']['IT_Team'] :'' }}
						@elseif($staff->permission_lvl == '7')
							{{ isset($data['backendlang']['backendlang']['Shareholder']) ? $data['backendlang']['backendlang']['Shareholder'] :'' }}
						@endif
					</td>
					<td>
						@if ($staff->status == 1)
 								<span class="badge bg-success">
									{{ $data['backendlang']['backendlang']['Active'] ?? '' }}
								</span>
							@else
								<span class="badge bg-danger">
									{{ $data['backendlang']['backendlang']['Inactive'] ?? '' }}
								</span>
							@endif
				</td>

					<td>
						<a href="{{ route('staff.staffs.edit', $staff->id) }}">
							<i class="ace-icon fa fa-pencil bigger-130"></i> {{ isset($data['backendlang']['backendlang']['Edit']) ? $data['backendlang']['backendlang']['Edit'] :'' }}
						</a>

						&nbsp;&nbsp;
						@if($staff->status == 1)
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

						&nbsp;&nbsp;
						<!-- <a href="{{ route('tree', [$staff->code]) }}" class="green">
							<i class="ace-icon fa fa-users bigger-130"></i> Affiliate
						</a> -->
					</td>
				</tr>
				@endforeach
				@else
				<tr>
					<td colspan="7">{{ isset($data['backendlang']['backendlang']['No_Result_Found']) ? $data['backendlang']['backendlang']['No_Result_Found'] :'' }}</td>
				</tr>
				@endif
			</tbody>
		</table>
		{{ $staffs->links() }}
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
        	message = confirm("{{ isset($data['backendlang']['backendlang']['Inactive_This_Row']) ? $data['backendlang']['backendlang']['Inative_This_Row'] :'' }}");
        }else{
        	message = confirm("{{ isset($data['backendlang']['backendlang']['Delete_This_Row']) ? $data['backendlang']['backendlang']['Delete_This_Row'] :'' }}");
        }

        if(message == true){
	        $.ajax({
	           url: '{{ route("StaffStatus") }}',
	           type: 'post',
	           data: fd,
	           contentType: false,
	           processData: false,
	           success: function(response){
	                $('.loading-gif').hide();
	                toastr.success('{{ isset($data['backendlang']['backendlang']['Status_Changed']) ? $data['backendlang']['backendlang']['Status_Changed'] :'' }}');
	                window.location.href="{{ route('staff.staffs.index') }}";
	           },
	        });
	    }else{
        	$('.loading-gif').hide();
        }
    });
</script>
@endsection