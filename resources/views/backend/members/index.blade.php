@extends('layouts.admin_app')

@section('content')
		<form action="{{ route('member.members.index') }}" method="GET">
				<div class="container-box form-group">
						<h4>{{ isset($data['backendlang']['backendlang']['Filter']) ? $data['backendlang']['backendlang']['Filter'] :'' }}</h4>
						<div class="row">
								<div class="col-sm-2">
										<div class="form-group">
												<input type="text" class="form-control" name="dates" value="{{ request('dates') ?? '' }}">
										</div>
								</div>
								<div class="col-sm-2">
										<div class="form-group">
												<input type="text" class="form-control" name="code"
														value="{{ !empty('code') && request('code') ? request('code') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Code']) ? $data['backendlang']['backendlang']['Search_Code'] :'' }}">
										</div>
								</div>
								<div class="col-sm-2">
										<div class="form-group">
												<input type="text" class="form-control" name="member_name"
														value="{{ !empty('member_name') && request('member_name') ? request('member_name') : '' }}"
														placeholder="{{ isset($data['backendlang']['backendlang']['Search_Name']) ? $data['backendlang']['backendlang']['Search_Name'] :'' }}">
										</div>
								</div>
								<div class="col-sm-2">
										<div class="form-group">
												<input type="text" class="form-control" name="referrer_code"
														value="{{ !empty('referrer_code') && request('referrer_code') ? request('referrer_code') : '' }}"
														placeholder="{{ isset($data['backendlang']['backendlang']['Search_Referral_Code']) ? $data['backendlang']['backendlang']['Search_Referral_Code'] :'' }}">
										</div>
								</div>
								<div class="col-sm-2">
										<div class="form-group">
												<input type="text" class="form-control" name="referrer_name"
														value="{{ !empty('referrer_name') && request('referrer_name') ? request('referrer_name') : '' }}"
														placeholder="{{ isset($data['backendlang']['backendlang']['Search_Referral_Name']) ? $data['backendlang']['backendlang']['Search_Referral_Name'] :'' }}">
										</div>
								</div>
								<div class="col-sm-2">
										<div class="form-group">
												<input type="text" class="form-control" name="email"
														value="{{ !empty('email') && request('email') ? request('email') : '' }}"
														placeholder="{{ isset($data['backendlang']['backendlang']['Search_Email']) ? $data['backendlang']['backendlang']['Search_Email'] :'' }}">
										</div>
								</div>

								<!-- <div class="col-sm-2">
												 <div class="form-group">
												 <select class="form-control" name="account_type">
												 <option value="">Search Account Type</option>
												 <option {{ !empty(request('account_type')) && request('account_type') == '1' ? 'selected' : '' }} value="1">Company</option>
												 <option {{ !empty(request('account_type')) && request('account_type') == '2' ? 'selected' : '' }} value="2">Personal</option>
												 </select>
												 </div>
												 </div> -->
						</div>

						<div class="row">
							<div class="col-sm-2">
									<div class="form-group">
											<input type="text" class="form-control" name="ic"
													value="{{ !empty('ic') && request('ic') ? request('ic') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_NRIC_NO']) ? $data['backendlang']['backendlang']['Search_NRIC_NO'] :'' }}">
									</div>
							</div>
							<div class="col-sm-2">
									<div class="form-group">
											<input type="text" class="form-control" name="phone"
													value="{{ !empty('phone') && request('phone') ? request('phone') : '' }}"
													placeholder="{{ isset($data['backendlang']['backendlang']['Search_Phone']) ? $data['backendlang']['backendlang']['Search_Phone'] :'' }}">
									</div>
							</div>
							<div class="col-sm-2">
									<div class="form-group">
											<select class="form-control" name="status">
													<option value="">{{ isset($data['backendlang']['backendlang']['Search_Status']) ? $data['backendlang']['backendlang']['Search_Status'] :'' }}</option>
													<option {{ !empty(request('status')) && request('status') == '1' ? 'selected' : '' }}
															value="1">{{ isset($data['backendlang']['backendlang']['Active']) ? $data['backendlang']['backendlang']['Active'] :'' }}</option>
													<option {{ !empty(request('status')) && request('status') == '2' ? 'selected' : '' }}
															value="2">{{ isset($data['backendlang']['backendlang']['Inactive']) ? $data['backendlang']['backendlang']['Inactive'] :'' }}</option>
											</select>
									</div>
							</div>
						</div>

						<div class="form-group">
								<div class="row">
										<div class="col-sm-2">
												<div class="form-group">
														<div style="color: black;">{{ isset($data['backendlang']['backendlang']['Row_Per_Page']) ? $data['backendlang']['backendlang']['Row_Per_Page'] :'' }}:</div>
														<select class="input-small" name="per_page">
																<option {{ !empty(request('per_page')) && request('per_page') == '10' ? 'selected' : '' }}
																		value="10">10</option>
																<option {{ !empty(request('per_page')) && request('per_page') == '20' ? 'selected' : '' }}
																		value="20">20</option>
																<option {{ !empty(request('per_page')) && request('per_page') == '50' ? 'selected' : '' }}
																		value="50">50</option>
														</select>
												</div>
										</div>
								</div>
						</div>
						<div class="form-group">
								<button class="btn btn-outline-primary btn-sm">
										<i class="bi bi-search"></i> {{ isset($data['backendlang']['backendlang']['Search']) ? $data['backendlang']['backendlang']['Search'] :'' }}
								</button>
								<a href="{{ route('member.members.index') }}" class="btn btn-warning btn-sm">
										<i class="bi bi-arrow-clockwise"></i> {{ isset($data['backendlang']['backendlang']['Clear_Search']) ? $data['backendlang']['backendlang']['Clear_Search'] :'' }}
								</a>
						</div>
				</div>
		</form>
		<div class="container-box">
				<div class="form-group" align="right">
						@if (
								!empty(
										$data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['member-insert']
								))
								<a href="{{ route('member.members.create') }}" class="btn btn-outline-success btn-sm">
										<i class="bi bi-plus"></i> {{ isset($data['backendlang']['backendlang']['Add_New_Member']) ? $data['backendlang']['backendlang']['Add_New_Member'] :'' }}
								</a>
						@endif
				</div>
				<div class="row" style="overflow: auto;">
						<div class="col-12">
								{{ $users->links() }}
								<table class="table table-bordered">
										<thead>
												<tr class="info">
														<th>#</th>
														<th>{{ isset($data['backendlang']['backendlang']['Code']) ? $data['backendlang']['backendlang']['Code'] :'' }}
																@if (empty(request('display_code_desc')) && empty(request('display_code_asc')))
																		<a href="{{ route('member.members.index', ['display_code_desc=DESC']) }}"
																				class="{{ !empty(request('display_code_desc')) ? 'selected' : '' }}">
																				<i class="bi bi-sort-down"></i>
																				<input type="hidden" name="sort_data" value="0">
																		</a>
																@else
																		@if (!empty(request('display_code_desc')))
																				<a href="{{ route('member.members.index', ['display_code_asc=ASC']) }}"
																						class="{{ !empty(request('display_code_asc')) ? 'selected' : '' }}">
																						<i class="bi bi-sort-down"></i>
																						<input type="hidden" name="sort_data" value="1">
																				</a>
																		@elseif(!empty(request('display_code_asc')))
																				<a href="{{ route('member.members.index', ['display_code_desc=DESC']) }}"
																						class="{{ !empty(request('display_code_desc')) ? 'selected' : '' }}">
																						<i class="bi bi-sort-up"></i>
																						<input type="hidden" name="sort_data" value="0">
																				</a>
																		@endif
																@endif
														</th>
														<th> {{ isset($data['backendlang']['backendlang']['Name']) ? $data['backendlang']['backendlang']['Name'] :'' }}
																@if (empty(request('name_desc')) && empty(request('name_asc')))
																		<a href="{{ route('member.members.index', ['name_desc=DESC']) }}"
																				class="{{ !empty(request('name_desc')) ? 'selected' : '' }}">
																				<i class="bi bi-sort-down"></i>
																				<input type="hidden" name="sort_data" value="0">
																		</a>
																@else
																		@if (!empty(request('name_desc')))
																				<a href="{{ route('member.members.index', ['name_asc=ASC']) }}"
																						class="{{ !empty(request('name_asc')) ? 'selected' : '' }}">
																						<i class="bi bi-sort-down"></i>
																						<input type="hidden" name="sort_data" value="1">
																				</a>
																		@elseif(!empty(request('name_asc')))
																				<a href="{{ route('member.members.index', ['name_desc=DESC']) }}"
																						class="{{ !empty(request('name_desc')) ? 'selected' : '' }}">
																						<i class="bi bi-sort-up"></i>
																						<input type="hidden" name="sort_data" value="0">
																				</a>
																		@endif
																@endif
														</th>
														<th>{{ isset($data['backendlang']['backendlang']['Referral']) ? $data['backendlang']['backendlang']['Referral'] :'' }}
																@if (empty(request('upline_code_desc')) && empty(request('upline_code_asc')))
																		<a href="{{ route('member.members.index', ['upline_code_desc=DESC']) }}"
																				class="{{ !empty(request('upline_code_desc')) ? 'selected' : '' }}">
																				<i class="bi bi-sort-down"></i>
																				<input type="hidden" name="sort_data" value="0">
																		</a>
																@else
																		@if (!empty(request('upline_code_desc')))
																				<a href="{{ route('member.members.index', ['upline_code_asc=ASC']) }}"
																						class="{{ !empty(request('upline_code_asc')) ? 'selected' : '' }}">
																						<i class="bi bi-sort-down"></i>
																						<input type="hidden" name="sort_data" value="1">
																				</a>
																		@elseif(!empty(request('upline_code_asc')))
																				<a href="{{ route('member.members.index', ['upline_code_desc=DESC']) }}"
																						class="{{ !empty(request('upline_code_desc')) ? 'selected' : '' }}">
																						<i class="bi bi-sort-up"></i>
																						<input type="hidden" name="sort_data" value="0">
																				</a>
																		@endif
																@endif
														</th>
														<th> {{ isset($data['backendlang']['backendlang']['Email']) ? $data['backendlang']['backendlang']['Email'] :'' }}
																@if (empty(request('email_desc')) && empty(request('email_asc')))
																		<a href="{{ route('member.members.index', ['email_desc=DESC']) }}"
																				class="{{ !empty(request('email_desc')) ? 'selected' : '' }}">
																				<i class="bi bi-sort-down"></i>
																				<input type="hidden" name="sort_data" value="0">
																		</a>
																@else
																		@if (!empty(request('email_desc')))
																				<a href="{{ route('member.members.index', ['email_asc=ASC']) }}"
																						class="{{ !empty(request('email_asc')) ? 'selected' : '' }}">
																						<i class="bi bi-sort-down"></i>
																						<input type="hidden" name="sort_data" value="1">
																				</a>
																		@elseif(!empty(request('email_asc')))
																				<a href="{{ route('member.members.index', ['email_desc=DESC']) }}"
																						class="{{ !empty(request('email_desc')) ? 'selected' : '' }}">
																						<i class="bi bi-sort-up"></i>
																						<input type="hidden" name="sort_data" value="0">
																				</a>
																		@endif
																@endif
														</th>
														<th>{{ isset($data['backendlang']['backendlang']['NRIC_no']) ? $data['backendlang']['backendlang']['NRIC_no'] :'' }}
																@if (empty(request('ic_desc')) && empty(request('ic_asc')))
																		<a href="{{ route('member.members.index', ['ic_desc=DESC']) }}"
																				class="{{ !empty(request('ic_desc')) ? 'selected' : '' }}">
																				<i class="bi bi-sort-down"></i>
																				<input type="hidden" name="sort_data" value="0">
																		</a>
																@else
																		@if (!empty(request('ic_desc')))
																				<a href="{{ route('member.members.index', ['ic_asc=ASC']) }}"
																						class="{{ !empty(request('ic_asc')) ? 'selected' : '' }}">
																						<i class="bi bi-sort-down"></i>
																						<input type="hidden" name="sort_data" value="1">
																				</a>
																		@elseif(!empty(request('ic_asc')))
																				<a href="{{ route('member.members.index', ['ic_desc=DESC']) }}"
																						class="{{ !empty(request('ic_desc')) ? 'selected' : '' }}">
																						<i class="bi bi-sort-up"></i>
																						<input type="hidden" name="sort_data" value="0">
																				</a>
																		@endif
																@endif
														</th>
														<th> {{ isset($data['backendlang']['backendlang']['Phone']) ? $data['backendlang']['backendlang']['Phone'] :'' }}
																@if (empty(request('phone_desc')) && empty(request('phone_asc')))
																		<a href="{{ route('member.members.index', ['phone_desc=DESC']) }}"
																				class="{{ !empty(request('phone_desc')) ? 'selected' : '' }}">
																				<i class="bi bi-sort-down"></i>
																				<input type="hidden" name="sort_data" value="0">
																		</a>
																@else
																		@if (!empty(request('phone_desc')))
																				<a href="{{ route('member.members.index', ['phone_asc=ASC']) }}"
																						class="{{ !empty(request('phone_asc')) ? 'selected' : '' }}">
																						<i class="bi bi-sort-down"></i>
																						<input type="hidden" name="sort_data" value="1">
																				</a>
																		@elseif(!empty(request('phone_asc')))
																				<a href="{{ route('member.members.index', ['phone_desc=DESC']) }}"
																						class="{{ !empty(request('phone_desc')) ? 'selected' : '' }}">
																						<i class="bi bi-sort-up"></i>
																						<input type="hidden" name="sort_data" value="0">
																				</a>
																		@endif
																@endif
														</th>
														<th>{{ isset($data['backendlang']['backendlang']['Status']) ? $data['backendlang']['backendlang']['Status'] :'' }}
																@if (empty(request('status_desc')) && empty(request('status_asc')))
																		<a href="{{ route('member.members.index', ['status_desc=DESC']) }}"
																				class="{{ !empty(request('status_desc')) ? 'selected' : '' }}">
																				<i class="bi bi-sort-down"></i>
																				<input type="hidden" name="sort_data" value="0">
																		</a>
																@else
																		@if (!empty(request('status_desc')))
																				<a href="{{ route('member.members.index', ['status_asc=ASC']) }}"
																						class="{{ !empty(request('status_asc')) ? 'selected' : '' }}">
																						<i class="bi bi-sort-down"></i>
																						<input type="hidden" name="sort_data" value="1">
																				</a>
																		@elseif(!empty(request('status_asc')))
																				<a href="{{ route('member.members.index', ['status_desc=DESC']) }}"
																						class="{{ !empty(request('status_desc')) ? 'selected' : '' }}">
																						<i class="bi bi-sort-up"></i>
																						<input type="hidden" name="sort_data" value="0">
																				</a>
																		@endif
																@endif
														</th>
														<th>{{ isset($data['backendlang']['backendlang']['Join_Date']) ? $data['backendlang']['backendlang']['Join_Date'] :'' }}
																@if (empty(request('created_at_desc')) && empty(request('created_at_asc')))
																		<a href="{{ route('member.members.index', ['created_at_desc=DESC']) }}"
																				class="{{ !empty(request('created_at_desc')) ? 'selected' : '' }}">
																				<i class="bi bi-sort-down"></i>
																				<input type="hidden" name="sort_data" value="0">
																		</a>
																@else
																		@if (!empty(request('created_at_desc')))
																				<a href="{{ route('member.members.index', ['created_at_asc=ASC']) }}"
																						class="{{ !empty(request('created_at_asc')) ? 'selected' : '' }}">
																						<i class="bi bi-sort-down"></i>
																						<input type="hidden" name="sort_data" value="1">
																				</a>
																		@elseif(!empty(request('created_at_asc')))
																				<a href="{{ route('member.members.index', ['created_at_desc=DESC']) }}"
																						class="{{ !empty(request('created_at_desc')) ? 'selected' : '' }}">
																						<i class="bi bi-sort-up"></i>
																						<input type="hidden" name="sort_data" value="0">
																				</a>
																		@endif
																@endif
														</th>
														<th>{{ isset($data['backendlang']['backendlang']['Action']) ? $data['backendlang']['backendlang']['Action'] :'' }}</th>
												</tr>
										</thead>
										<tbody>
												@if (!$users->isEmpty())
														@foreach ($users as $key => $user)
																<tr>
																		<td>
																				{{ $key + 1 }}
																				<input type="hidden" class="row_id" value="{{ $user->id }}">
																				<input type="hidden" class="row_username" value="{{ $user->f_name }}">
																				<input type="hidden" class="row_code" value="{{ $user->code }}">
																		</td>
																		<td>{{ $user->display_code }}{{ $user->display_running_no }}</td>
																		<td>{{ $user->f_name }} {{ $user->l_name }}</td>
																		<td>
																				@if (!empty($user->get_upline_det->get_user_id_agent_det->code))
																						{{ $user->get_upline_det->get_user_id_agent_det->f_name }}
																				@elseif(!empty($user->get_upline_det->get_user_id_member_det->code))
																						{{ $user->get_upline_det->get_user_id_member_det->f_name }}
																				@elseif(!empty($user->get_upline_det->get_user_id_admin_det->code))
																						{{ $user->get_upline_det->get_user_id_admin_det->f_name }}
																						{{ $user->get_upline_det->get_user_id_admin_det->l_name }}
																				@else
																						<span style="color: red;">
																								<i class="bi bi-minus"></i>

																						</span>
																				@endif
																				<br>
																				({{ $user->master_id }})
																		</td>
																		<td>{{ $user->email }}</td>
																		<td>
																				@if (!empty($user->ic))
																						{{ $user->ic }}
																				@else
																						-
																				@endif
																		</td>
																		<td>
																			(+{{ $user->country_code }})
																			@if($user->country_code && $user->country_code == '60')
																				{{ ($user->phone[0] == 0) ? $user->phone : '0'.$user->phone }}
																			@else
																				{{ ($user->phone[0] == 0) ? substr($user->phone, 1) : $user->phone }}
																			@endif
																		</td>
																		{{-- <td>{{ $user->phone }}</td> --}}
																		<td>
																		    @if	($user->status == 1)
																				<span class="badge bg-success">
																					{{ $data['backendlang']['backendlang']['Active'] ?? '' }}
																				</span>
																			@else
																				<span class="badge bg-danger">
																					{{ $data['backendlang']['backendlang']['Inactive'] ?? '' }}
																				</span>
																			@endif
																		</td>		
																		<td>{{ $user->created_at }}</td>
																		<td>
																				@if (
																						!empty(
																								$data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['member-edit']
																						))
																						<a href="{{ route('member.members.edit', $user->id) }}"
																								class="btn btn-outline-primary btn-sm" title="{{ isset($data['backendlang']['backendlang']['View_Edit']) ? $data['backendlang']['backendlang']['View_Edit'] :'' }}">
																								<i class="ace-icon bi bi-pencil bigger-130"></i>
																						</a>
																						&nbsp;&nbsp;
																				@endif

																				@if (
																						!empty(
																								$data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['member-edit']
																						))
																						@if ($user->status == 1)
																								<a href="#" class="red change-status btn btn-outline-danger btn-sm"
																										data-id="2" title="{{ isset($data['backendlang']['backendlang']['Inactive']) ? $data['backendlang']['backendlang']['Inactive'] :'' }}">
																										<i class="ace-icon bi bi-shield-fill-x bigger-130"></i>
																								</a>
																						@else
																								<a href="#"
																										class="green change-status btn btn-outline-success btn-sm"
																										data-id="1" title="{{ isset($data['backendlang']['backendlang']['Reactive']) ? $data['backendlang']['backendlang']['Reactive'] :'' }}">
																										<i class="ace-icon bi bi-shield-check bigger-130"></i>
																								</a>
																						@endif
																						&nbsp;&nbsp;
																				@endif

																				@if (
																						!empty(
																								$data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['member-delete']
																						))
																						<a href="#" class="red change-status btn btn-outline-danger btn-sm"
																								data-id="3" title="{{ isset($data['backendlang']['backendlang']['Delete']) ? $data['backendlang']['backendlang']['Delete'] :'' }}">
																								<i class="ace-icon bi bi-trash bigger-130"></i>
																						</a>
																						&nbsp;&nbsp;
																				@endif
																				@if (
																						!empty(
																								$data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['member-edit']
																						))
																						@if ($user->status == 1)
																								<a href="#"
																										class="green upgrade-member btn btn-outline-success btn-sm"
																										title="{{ isset($data['backendlang']['backendlang']['Upgrade_To_Agent']) ? $data['backendlang']['backendlang']['Upgrade_To_Agent'] :'' }}">
																										<i class="ace-icon bi bi-arrow-up bigger-130"></i>
																								</a>
																						@endif
																				@endif
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
								{{ $users->links() }}
						</div>
				</div>
		</div>
@endsection

@section('js')
		<script type="text/javascript">
				$('.change-status').click(function() {
						$('.loading-gif').show();
						var ele = $(this);
						var row_id = ele.closest('tr').find('.row_id').val();

						var fd = new FormData();
						fd.append('row_id', row_id);
						fd.append('status', ele.data('id'));

						var message;
						if (ele.data('id') == 1) {
								message = confirm("{{ isset($data['backendlang']['backendlang']['Reactive_This_Row']) ? $data['backendlang']['backendlang']['Reactive_This_Row'] :'' }}");
						} else if (ele.data('id') == 2) {
								message = confirm("{{ isset($data['backendlang']['backendlang']['Inactive_This_Row']) ? $data['backendlang']['backendlang']['Inactive_This_Row'] :'' }}");
						} else {
								message = confirm("{{ isset($data['backendlang']['backendlang']['Delete_This_Row']) ? $data['backendlang']['backendlang']['Delete_This_Row'] :'' }}");
						}

						if (message == true) {
								$.ajax({
										url: '{{ route('UserStatus') }}',
										type: 'post',
										data: fd,
										contentType: false,
										processData: false,
										success: function(response) {
												$('.loading-gif').hide();
												toastr.success("{{ isset($data['backendlang']['backendlang']['Status_Changed']) ? $data['backendlang']['backendlang']['Status_Changed'] :'' }}");
												window.location.href = "{{ route('member.members.index') }}";
										},
								});
						} else {
								$('.loading-gif').hide();
						}
				});

				$('.upgrade-member').click(function() {
					$('.loading-gif').show();
					var ele = $(this);
					var row_id = ele.closest('tr').find('.row_id').val();
					var row_username = ele.closest('tr').find('.row_username').val();
					var row_code = ele.closest('tr').find('.row_code').val();

					var fd = new FormData();
					fd.append('row_id', row_id);
					if(confirm('{{ isset($data["backendlang"]["backendlang"]["Confirm_Upgrade_Member"]) ? $data["backendlang"]["backendlang"]["Confirm_Upgrade_Member"] : "Confirm upgrading member :username (:code) to agent?" }}' .replace(':username', row_username) .replace(':code', row_code))) {
								$.ajax({
								url: '{{ route('MemberUpgrade') }}',
								type: 'post',
								data: fd,
								contentType: false,
								processData: false,
								success: function(response) {
									location.reload();
									$('.loading-gif').hide();
									toastr.success('{{ isset($data["backendlang"]["backendlang"]["Upgraded_Member_To_Agent"]) ? $data["backendlang"]["backendlang"]["Upgraded_Member_To_Agent"] : "Upgraded member :username (:code) to agent" }}'.replace(':username', row_username) .replace(':code', row_code));
								},
						});
					}else{
						$('.loading-gif').hide();
					}
				});
				
				$('input[name=dates]').daterangepicker({
		autoUpdateInput: false,
		'applyClass': 'btn-sm btn-success',
		'cancelClass': 'btn-sm btn-outline-danger',
		locale: {
			applyLabel: "{{ isset($data['backendlang']['backendlang']['Apply']) ? $data['backendlang']['backendlang']['Apply'] :'' }}",
			cancelLabel: "{{ isset($data['backendlang']['backendlang']['Cancel']) ? $data['backendlang']['backendlang']['Cancel'] :'' }}",
			format: 'DD/MM/YYYY',
		}
	}).attr('placeholder', 'Select Date Range');

	$('input[name=dates]').on('apply.daterangepicker', function(ev, picker) {
    	$(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
		$(this).trigger('change');
	});

	// Clear input when user cancels
	$('input[name=dates]').on('cancel.daterangepicker', function(ev, picker) {
    	$(this).val('');
	});
		</script>
@endsection
