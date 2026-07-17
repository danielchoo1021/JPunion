@extends('layouts.admin_app')

@section('content')
<div class="container-box form-group">
	<form action="{{ route('student.students.index') }}" method="GET">
	<div class="row">
		<div class="col-sm-2">
			<div class="form-group">
				<input type="text" class="form-control" name="member_name" value="{{ !empty('member_name') && request('member_name') ? request('member_name') : '' }}" placeholder="名字">
			</div>
		</div>
		<div class="col-sm-2">
			<div class="form-group">
				<input type="text" class="form-control" name="member_malay_name" value="{{ !empty('member_malay_name') && request('member_malay_name') ? request('member_malay_name') : '' }}" placeholder="马来名字">
			</div>
		</div>
		
		<div class="col-sm-2">
			<div class="form-group">
				<select class="form-control" name="class_id">
					<option value="">搜索班级</option>
					@foreach($classes as $class)
					<option {{ (!empty(request('class_id')) && request('class_id') == $class->id) ? 'selected' : '' }} value="{{ $class->id }}">
						{{ $class->class_code }}{{ $class->class_level }}
					</option>
					@endforeach
				</select>
			</div>
		</div>

		<div class="col-sm-2">
			<div class="form-group">
				<select class="form-control" name="status">
					<option value="">搜索状态</option>
					<option {{ (!empty(request('status')) && request('status') == '1') ? 'selected' : '' }} value="1">活跃</option>
					<option {{ (!empty(request('status')) && request('status') == '2') ? 'selected' : '' }} value="2">不活跃</option>
				</select>
			</div>
		</div>
	</div>

	<div class="form-group">
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group">
					每頁項目: <br>
					<select class="input-small" name="per_page">
						<option {{ (!empty(request('per_page')) && request('per_page') == '20') ? 'selected' : '' }} value="20">20</option>
						<option {{ (!empty(request('per_page')) && request('per_page') == '50') ? 'selected' : '' }} value="50">50</option>
					</select>
				</div>
			</div>
		</div>
	</div>
	<div class="form-group">
		<button class="btn btn-outline-primary btn-sm">
			<i class="fa fa-search"></i> 搜索
		</button>
		<a href="{{ route('student.students.index') }}" class="btn btn-warning btn-sm">
			<i class="fa fa-refresh"></i> 清除搜索
		</a>
	</div>
	</form>
</div>
<div class="container-box form-group">
	@if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['member-insert']))
	<div class="form-group" align="right">
		<a href="{{ route('student.students.create') }}" class="btn btn-outline-success btn-sm">
			<i class="fa fa-plus"></i> 添加新学生
		</a>
	</div>
	@endif
	<div class="row" style="overflow: auto;">
		<div class="col-12">
			{{ $users->links() }}
			<div class="form-group">
				<select id="pageSelect" class="pageSelect">
				    @for ($i = 1; $i <= $users->lastPage(); $i++)
				        <option value="{{ $users->url($i) }}" {{ $users->currentPage() == $i ? 'selected' : '' }}>
				            {{ $i }}
				        </option>
				    @endfor
				</select>
			</div>
			<table class="table table-bordered">
				<thead>
					<tr class="" style="background-color: blue; color: white;">
						<th>#</th>
						<th>编号
							@if(empty(request('code_desc')) && empty(request('code_asc')))
								<a href="{{ route('student.students.index', ['code_desc=DESC']) }}" 
								   class="{{ !empty(request('code_desc')) ? 'selected' : '' }}">
									<i class="fa fa-sort"></i>
									<input type="hidden" name="sort_data" value="0">
								</a>
							@else
								@if(!empty(request('code_desc')))
									<a href="{{ route('student.students.index', ['code_asc=ASC']) }}" 
									   class="{{ !empty(request('code_asc')) ? 'selected' : '' }}">
										<i class="fa fa-sort"></i>
										<input type="hidden" name="sort_data" value="1">
									</a>
								@elseif(!empty(request('code_asc')))
									<a href="{{ route('student.students.index', ['code_desc=DESC']) }}" 
									   class="{{ !empty(request('code_desc')) ? 'selected' : '' }}">
										<i class="fa fa-sort"></i>
										<input type="hidden" name="sort_data" value="0">
									</a>
								@endif
							@endif</th>
						<th>名字
							@if(empty(request('name_desc')) && empty(request('name_asc')))
								<a href="{{ route('student.students.index', ['name_desc=DESC']) }}" 
								   class="{{ !empty(request('name_desc')) ? 'selected' : '' }}">
									<i class="fa fa-sort"></i>
									<input type="hidden" name="sort_data" value="0">
								</a>
							@else
								@if(!empty(request('name_desc')))
									<a href="{{ route('student.students.index', ['name_asc=ASC']) }}" 
									   class="{{ !empty(request('name_asc')) ? 'selected' : '' }}">
										<i class="fa fa-sort"></i>
										<input type="hidden" name="sort_data" value="1">
									</a>
								@elseif(!empty(request('name_asc')))
									<a href="{{ route('student.students.index', ['name_desc=DESC']) }}" 
									   class="{{ !empty(request('name_desc')) ? 'selected' : '' }}">
										<i class="fa fa-sort"></i>
										<input type="hidden" name="sort_data" value="0">
									</a>
								@endif
							@endif
						</th>
						<th>马来名字
							@if(empty(request('l_name_desc')) && empty(request('l_name_asc')))
								<a href="{{ route('student.students.index', ['l_name_desc=DESC']) }}" 
								   class="{{ !empty(request('l_name_desc')) ? 'selected' : '' }}">
									<i class="fa fa-sort"></i>
									<input type="hidden" name="sort_data" value="0">
								</a>
							@else
								@if(!empty(request('l_name_desc')))
									<a href="{{ route('student.students.index', ['l_name_asc=ASC']) }}" 
									   class="{{ !empty(request('l_name_asc')) ? 'selected' : '' }}">
										<i class="fa fa-sort"></i>
										<input type="hidden" name="sort_data" value="1">
									</a>
								@elseif(!empty(request('l_name_asc')))
									<a href="{{ route('student.students.index', ['l_name_desc=DESC']) }}" 
									   class="{{ !empty(request('l_name_desc')) ? 'selected' : '' }}">
										<i class="fa fa-sort"></i>
										<input type="hidden" name="sort_data" value="0">
									</a>
								@endif
							@endif
						</th>
						<th>班级
							@if(empty(request('class_id_desc')) && empty(request('class_id_asc')))
								<a href="{{ route('student.students.index', ['class_id_desc=DESC']) }}" 
								   class="{{ !empty(request('class_id_desc')) ? 'selected' : '' }}">
									<i class="fa fa-sort"></i>
									<input type="hidden" name="sort_data" value="0">
								</a>
							@else
								@if(!empty(request('class_id_desc')))
									<a href="{{ route('student.students.index', ['class_id_asc=ASC']) }}" 
									   class="{{ !empty(request('class_id_asc')) ? 'selected' : '' }}">
										<i class="fa fa-sort"></i>
										<input type="hidden" name="sort_data" value="1">
									</a>
								@elseif(!empty(request('class_id_asc')))
									<a href="{{ route('student.students.index', ['class_id_desc=DESC']) }}" 
									   class="{{ !empty(request('class_id_desc')) ? 'selected' : '' }}">
										<i class="fa fa-sort"></i>
										<input type="hidden" name="sort_data" value="0">
									</a>
								@endif
							@endif
						</th>
						<th>状态
							@if(empty(request('status_desc')) && empty(request('status_asc')))
								<a href="{{ route('student.students.index', ['status_desc=DESC']) }}" 
								   class="{{ !empty(request('status_desc')) ? 'selected' : '' }}">
									<i class="fa fa-sort"></i>
									<input type="hidden" name="sort_data" value="0">
								</a>
							@else
								@if(!empty(request('status_desc')))
									<a href="{{ route('student.students.index', ['status_asc=ASC']) }}" 
									   class="{{ !empty(request('status_asc')) ? 'selected' : '' }}">
										<i class="fa fa-sort"></i>
										<input type="hidden" name="sort_data" value="1">
									</a>
								@elseif(!empty(request('status_asc')))
									<a href="{{ route('student.students.index', ['status_desc=DESC']) }}" 
									   class="{{ !empty(request('status_desc')) ? 'selected' : '' }}">
										<i class="fa fa-sort"></i>
										<input type="hidden" name="sort_data" value="0">
									</a>
								@endif
							@endif
						</th>
						<th>
							创建时间

							@if(empty(request('created_at_desc') && empty(request('created_at_asc'))))
								<a href="{{ route('setting_standard.setting_standards.index', ['created_at_desc=DESC']) }}" 
								   class="{{ !empty(request('created_at_desc')) ? 'selected' : '' }}">
									<i class="fa fa-sort"></i>
									<input type="hidden" name="sort_data" value="0">
								</a>
							@else
								@if(!empty(request('created_at_desc')))
									<a href="{{ route('setting_standard.setting_standards.index', ['created_at_asc=ASC']) }}" 
									   class="{{ !empty(request('created_at_asc')) ? 'selected' : '' }}">
										<i class="fa fa-sort"></i>
										<input type="hidden" name="sort_data" value="1">
									</a>
								@elseif(!empty(request('created_at_asc')))
									<a href="{{ route('setting_standard.setting_standards.index', ['created_at_desc=DESC']) }}" 
									   class="{{ !empty(request('created_at_desc')) ? 'selected' : '' }}">
										<i class="fa fa-sort"></i>
										<input type="hidden" name="sort_data" value="0">
									</a>
								@endif
							@endif
						</th>
						<th>
							创建者

							@if(empty(request('created_by_desc') && empty(request('created_by_asc'))))
								<a href="{{ route('setting_standard.setting_standards.index', ['created_by_desc=DESC']) }}" 
								   class="{{ !empty(request('created_by_desc')) ? 'selected' : '' }}">
									<i class="fa fa-sort"></i>
									<input type="hidden" name="sort_data" value="0">
								</a>
							@else
								@if(!empty(request('created_by_desc')))
									<a href="{{ route('setting_standard.setting_standards.index', ['created_by_asc=ASC']) }}" 
									   class="{{ !empty(request('created_by_asc')) ? 'selected' : '' }}">
										<i class="fa fa-sort"></i>
										<input type="hidden" name="sort_data" value="1">
									</a>
								@elseif(!empty(request('created_by_asc')))
									<a href="{{ route('setting_standard.setting_standards.index', ['created_by_desc=DESC']) }}" 
									   class="{{ !empty(request('created_by_desc')) ? 'selected' : '' }}">
										<i class="fa fa-sort"></i>
										<input type="hidden" name="sort_data" value="0">
									</a>
								@endif
							@endif
						</th>
						<th>
							更新时间

							@if(empty(request('updated_at_desc') && empty(request('updated_at_asc'))))
								<a href="{{ route('setting_standard.setting_standards.index', ['updated_at_desc=DESC']) }}" 
								   class="{{ !empty(request('updated_at_desc')) ? 'selected' : '' }}">
									<i class="fa fa-sort"></i>
									<input type="hidden" name="sort_data" value="0">
								</a>
							@else
								@if(!empty(request('updated_at_desc')))
									<a href="{{ route('setting_standard.setting_standards.index', ['updated_at_asc=ASC']) }}" 
									   class="{{ !empty(request('updated_at_asc')) ? 'selected' : '' }}">
										<i class="fa fa-sort"></i>
										<input type="hidden" name="sort_data" value="1">
									</a>
								@elseif(!empty(request('updated_at_asc')))
									<a href="{{ route('setting_standard.setting_standards.index', ['updated_at_desc=DESC']) }}" 
									   class="{{ !empty(request('updated_at_desc')) ? 'selected' : '' }}">
										<i class="fa fa-sort"></i>
										<input type="hidden" name="sort_data" value="0">
									</a>
								@endif
							@endif
						</th>
						<th>
							更新者

							@if(empty(request('updated_by_desc') && empty(request('updated_by_asc'))))
								<a href="{{ route('setting_standard.setting_standards.index', ['updated_by_desc=DESC']) }}" 
								   class="{{ !empty(request('updated_by_desc')) ? 'selected' : '' }}">
									<i class="fa fa-sort"></i>
									<input type="hidden" name="sort_data" value="0">
								</a>
							@else
								@if(!empty(request('updated_by_desc')))
									<a href="{{ route('setting_standard.setting_standards.index', ['updated_by_asc=ASC']) }}" 
									   class="{{ !empty(request('updated_by_asc')) ? 'selected' : '' }}">
										<i class="fa fa-sort"></i>
										<input type="hidden" name="sort_data" value="1">
									</a>
								@elseif(!empty(request('updated_by_asc')))
									<a href="{{ route('setting_standard.setting_standards.index', ['updated_by_desc=DESC']) }}" 
									   class="{{ !empty(request('updated_by_desc')) ? 'selected' : '' }}">
										<i class="fa fa-sort"></i>
										<input type="hidden" name="sort_data" value="0">
									</a>
								@endif
							@endif
						</th>
						<th>操作</th>
					</tr>
				</thead>
				<tbody>
					@if (!$users->isEmpty())
					@foreach($users as $key => $user)
					<tr style="cursor: pointer; background-color: #fff !important; color: #000 !important;">
						<td class="details" data-id="{{ $user->id }}">
							{{ $key+1 }}
							<input type="hidden" class="row_id" value="{{ $user->id }}">
						</td>
						<td class="details" data-id="{{ $user->id }}">{{ $user->code }}</td>
						<td class="details" data-id="{{ $user->id }}">{{ $user->f_name }}</td>
						<td class="details" data-id="{{ $user->id }}">{{ $user->l_name }}</td>
						<td class="details" data-id="{{ $user->id }}">
							@if(!empty($user->get_class))
								{{ $user->get_class->class_code }}{{ $user->get_class->class_level }}
							@else
								<i class="fa fa-minus"></i>
							@endif
						</td>
						<td class="details" data-id="{{ $user->id }}">{!! ($user->status == 1) ? '<span class="badge bg-success">活跃</span>' : '<span class="badge bg-danger">不活跃</span>' !!}</td>
						<td class="details" data-id="{{ $user->id }}">
							{{ $user->created_at }}
						</td>
						<td class="details" data-id="{{ $user->id }}">
							@if(!empty($user->get_created_by_admin->code))
								{{ $user->get_created_by_admin->f_name }}
							@elseif(!empty($user->get_created_by_agent->code))
								{{ $user->get_created_by_agent->f_name }}
							@else
								<i class="fa fa-minus"></i>
							@endif
						</td>
						<td class="details" data-id="{{ $user->id }}">
							{{ $user->updated_at }}
						</td>
						<td class="details" data-id="{{ $user->id }}">
							@if(!empty($user->get_updated_by_admin->code))
								{{ $user->get_updated_by_admin->f_name }}
							@elseif(!empty($user->get_updated_by_agent->code))
								{{ $user->get_updated_by_agent->f_name }}
							@else
								<i class="fa fa-minus"></i>
							@endif
						</td>
						<td>
							@if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['member-edit']))
								<a href="{{ route('student.students.edit', $user->id) }}" class=" btn btn-outline-primary btn-sm">
									<i class="ace-icon fa fa-pencil bigger-130"></i>
								</a>
								
								@if($user->status == 1)
								<a href="#" class="red change-status btn btn-outline-danger btn-sm" data-id="2">
									<i class="ace-icon fa fa-ban bigger-130"></i>
								</a>
								@else
								<a href="#" class="green change-status btn btn-outline-success btn-sm" data-id="1">
									<i class="ace-icon fa fa-check bigger-130"></i>
								</a>
								@endif
							@endif
							
							@if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['member-delete']))
							<a href="#" class="red change-status btn btn-outline-danger btn-sm" data-id="3">
								<i class="ace-icon fa fa-trash-o bigger-130"></i>
							</a>
							@endif
						</td>
					</tr>
					@endforeach
					@else
					<tr>
						<td colspan="13" align="center">没有结果</td>
					</tr>
					@endif
				</tbody>
			</table>
			{{ $users->links() }}
		
			<select id="pageSelect" class="pageSelect">
			    @for ($i = 1; $i <= $users->lastPage(); $i++)
			        <option value="{{ $users->url($i) }}" {{ $users->currentPage() == $i ? 'selected' : '' }}>
			            {{ $i }}
			        </option>
			    @endfor
			</select>
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
        	message = confirm("活跃?");
        }else if(ele.data('id') == 2){
        	message = confirm("不活跃?");
        }else{
        	message = confirm("删除?");
        }

        if(message == true){
	        $.ajax({
	           url: '{{ route("UserStatus") }}',
	           type: 'post',
	           data: fd,
	           contentType: false,
	           processData: false,
	           success: function(response){
	           		if(response == 'ok'){
		                $('.loading-gif').hide();
		                toastr.success('成功');
		                location.reload();
	           		}else{
	           			toastr.error(response);
	           		}
	           },
	        });
	    }else{
        	$('.loading-gif').hide();
        }
    });

	$('.details').click(function(e){
		e.preventDefault();

		var ele = $(this);
		
		var url = '{{ route("student.students.edit", [":id"]) }}';
		url = url.replace(':id', ele.data('id'));
		if(e.ctrlKey == true){
			window.open(url);
		}else{
	    	window.location.href = url;
		}
	})
</script>
<script>
    $('.pageSelect').on('change', function() {
        window.location.href = this.value;
    });
</script>
@endsection