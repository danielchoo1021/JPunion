@extends('layouts.admin_app')

@section('content')
<div class="container-box form-group">
	<h4>{{ isset($data['backendlang']['backendlang']['Filter']) ? $data['backendlang']['backendlang']['Filter'] :'' }}</h4>
	<form action="{{ route('agent.agents.index') }}" method="GET">
		<div class="row">
			<div class="col-sm-2">
				<div class="form-group">
					<input type="text" class="form-control" name="dates" value="{{ request('dates') ?? '' }}" placeholder=" Select Date Range">
				</div>
			</div>
			<div class="col-sm-2">
				<div class="form-group">
					<input type="text" class="form-control" name="code" value="{{ !empty('code') && request('code') ? request('code') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Code']) ? $data['backendlang']['backendlang']['Search_Code'] :'' }}">
				</div>
			</div>
			<div class="col-sm-2">
				<div class="form-group">
					<input type="text" class="form-control" name="agent_name" value="{{ !empty('agent_name') && request('agent_name') ? request('agent_name') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Name']) ? $data['backendlang']['backendlang']['Search_Name'] :'' }}">
				</div>
			</div>
			<div class="col-sm-2">
				<div class="form-group">
					<input type="text" class="form-control" name="referrer_code" value="{{ !empty('referrer_code') && request('referrer_code') ? request('referrer_code') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Referral_Code']) ? $data['backendlang']['backendlang']['Search_Referral_Code'] :'' }}">
				</div>
			</div>
			<div class="col-sm-2">
				<div class="form-group">
					<input type="text" class="form-control" name="referrer_name" value="{{ !empty('referrer_name') && request('referrer_name') ? request('referrer_name') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Referral_Name']) ? $data['backendlang']['backendlang']['Search_Referral_Name'] :'' }}">
				</div>
			</div>
			<div class="col-sm-2">
				<div class="form-group">
					<input type="text" class="form-control" name="email" value="{{ !empty('email') && request('email') ? request('email') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Email']) ? $data['backendlang']['backendlang']['Search_Email'] :'' }}">
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-2">
				<div class="form-group">
					<input type="text" class="form-control" name="phone" value="{{ !empty('phone') && request('phone') ? request('phone') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Phone']) ? $data['backendlang']['backendlang']['Search_Phone'] :'' }}">
				</div>
			</div>
			<div class="col-sm-2">
				<div class="form-group">
					<select class="form-control" name="status">
						<option value="">{{ isset($data['backendlang']['backendlang']['Search_Status']) ? $data['backendlang']['backendlang']['Search_Status'] :'' }}</option>
						<option {{ (!empty(request('status')) && request('status') == '1') ? 'selected' : '' }} value="1">{{ isset($data['backendlang']['backendlang']['Active']) ? $data['backendlang']['backendlang']['Active'] :'' }}</option>
						<option {{ (!empty(request('status')) && request('status') == '2') ? 'selected' : '' }} value="2">{{ isset($data['backendlang']['backendlang']['Inactive']) ? $data['backendlang']['backendlang']['Inactive'] :'' }}</option>
					</select>
				</div>
			</div>
		</div>

		<div class="form-group">
			<div class="row">
				<div class="col-sm-12">
					<div class="form-group">
						<div style="color: black;">{{ isset($data['backendlang']['backendlang']['Row_Per_Page']) ? $data['backendlang']['backendlang']['Row_Per_Page'] :'' }}:</div>
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
			<a href="{{ route('agent.agents.index') }}" class="btn btn-warning btn-sm">
				<i class="bi bi-arrow-clockwise"></i> {{ isset($data['backendlang']['backendlang']['Clear_Search']) ? $data['backendlang']['backendlang']['Clear_Search'] :'' }}
			</a>
		</div>
	</form>
</div>

<div class="container-box form-group">
	@if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['agent-insert']))
	<div class="form-group" align="right">
		<a href="{{ route('agent.agents.create') }}" class="btn btn-outline-success btn-sm">
			<i class="bi bi-plus"></i>  {{ isset($data['backendlang']['backendlang']['Add_New_Agent']) ? $data['backendlang']['backendlang']['Add_New_Agent'] :'' }}
		</a>
		<a href="{{ route('exportAgentList', ['dates='.(!empty(request('dates')) ? request('dates') : ''), 
											     'agent_name='.(!empty(request('agent_name')) ? request('agent_name') : ''), 
											     'code='.(!empty(request('code')) ? request('code') : ''),
												 'phone='.(!empty(request('phone')) ? request('phone') : '' ),
												 'status='.(!empty(request('status')) ? request('status') : '' ),
												 'referrer_code='.(!empty(request('referrer_code')) ? request('referrer_code') : '' ),
												 'referrer_name='.(!empty(request('referrer_name')) ? request('referrer_name') : '' ),
												 'email='.(!empty(request('email')) ? request('email') : '' )])}}" target="_blank" class="btn btn-warning btn-sm">
			<i class="bi bi-download"></i> {{ isset($data['backendlang']['backendlang']['Export']) ? $data['backendlang']['backendlang']['Export'] :'' }}
		</a>
	</div>
	@endif
	<div class="row" style="overflow: auto;">
		<div class="col-12">
			{{ $agents->links() }}
			<table class="table table-bordered">
				<thead>
					<tr class="info">
						<th>#</th>
						@if(Auth::user()->permission_lvl == 1)
						<th>{{ isset($data['backendlang']['backendlang']['Agent']) ? $data['backendlang']['backendlang']['Agent'] :'' }}</th>
						@endif
						<th>{{ isset($data['backendlang']['backendlang']['Code']) ? $data['backendlang']['backendlang']['Code'] :'' }}
							@if(empty(request('code_desc')) && empty(request('code_asc')))
							<a href="{{ route('agent.agents.index', ['code_desc=DESC']) }}"
								class="{{ !empty(request('code_desc')) ? 'selected' : '' }}">
								<i class="bi bi-sort-down"></i>
								<input type="hidden" name="sort_data" value="0">
							</a>
							@else
							@if(!empty(request('code_desc')))
							<a href="{{ route('agent.agents.index', ['code_asc=ASC']) }}"
								class="{{ !empty(request('code_asc')) ? 'selected' : '' }}">
								<i class="bi bi-sort-down"></i>
								<input type="hidden" name="sort_data" value="1">
							</a>
							@elseif(!empty(request('code_asc')))
							<a href="{{ route('agent.agents.index', ['code_desc=DESC']) }}"
								class="{{ !empty(request('code_desc')) ? 'selected' : '' }}">
								<i class="bi bi-sort-up"></i>
								<input type="hidden" name="sort_data" value="0">
							</a>
							@endif
							@endif
						</th>
						<th>{{ isset($data['backendlang']['backendlang']['Name']) ? $data['backendlang']['backendlang']['Name'] :'' }}
							@if(empty(request('name_desc')) && empty(request('name_asc')))
								<a href="{{ route('agent.agents.index', ['name_desc=DESC']) }}"
									class="{{ !empty(request('name_desc')) ? 'selected' : '' }}">
									<i class="bi bi-sort-down"></i>
									<input type="hidden" name="sort_data" value="0">
								</a>
							@else
								@if(!empty(request('name_desc')))
								<a href="{{ route('agent.agents.index', ['name_asc=ASC']) }}"
									class="{{ !empty(request('name_asc')) ? 'selected' : '' }}">
									<i class="bi bi-sort-down"></i>
									<input type="hidden" name="sort_data" value="1">
								</a>
								@elseif(!empty(request('name_asc')))
								<a href="{{ route('agent.agents.index', ['name_desc=DESC']) }}"
									class="{{ !empty(request('name_desc')) ? 'selected' : '' }}">
									<i class="bi bi-sort-up"></i>
									<input type="hidden" name="sort_data" value="0">
								</a>
								@endif
							@endif
						</th>
						<th>{{ isset($data['backendlang']['backendlang']['Referral']) ? $data['backendlang']['backendlang']['Referral'] :'' }}
							@if(empty(request('ref_code_desc')) && empty(request('ref_code_asc')))
							<a href="{{ route('agent.agents.index', ['ref_code_desc=DESC']) }}"
								class="{{ !empty(request('ref_code_desc')) ? 'selected' : '' }}">
								<i class="bi bi-sort-down"></i>
								<input type="hidden" name="sort_data" value="0">
							</a>
							@else
							@if(!empty(request('ref_code_desc')))
							<a href="{{ route('agent.agents.index', ['ref_code_asc=ASC']) }}"
								class="{{ !empty(request('ref_code_asc')) ? 'selected' : '' }}">
								<i class="bi bi-sort-down"></i>
								<input type="hidden" name="sort_data" value="1">
							</a>
							@elseif(!empty(request('ref_code_asc')))
							<a href="{{ route('agent.agents.index', ['ref_code_desc=DESC']) }}"
								class="{{ !empty(request('ref_code_desc')) ? 'selected' : '' }}">
								<i class="bi bi-sort-up"></i>
								<input type="hidden" name="sort_data" value="0">
							</a>
							@endif
							@endif
						</th>
						<th>{{ isset($data['backendlang']['backendlang']['Email']) ? $data['backendlang']['backendlang']['Email'] :'' }}
							@if(empty(request('email_desc')) && empty(request('email_asc')))
							<a href="{{ route('agent.agents.index', ['email_desc=DESC']) }}"
								class="{{ !empty(request('email_desc')) ? 'selected' : '' }}">
								<i class="bi bi-sort-down"></i>
								<input type="hidden" name="sort_data" value="0">
							</a>
							@else
							@if(!empty(request('email_desc')))
							<a href="{{ route('agent.agents.index', ['email_asc=ASC']) }}"
								class="{{ !empty(request('email_asc')) ? 'selected' : '' }}">
								<i class="bi bi-sort-down"></i>
								<input type="hidden" name="sort_data" value="1">
							</a>
							@elseif(!empty(request('email_asc')))
							<a href="{{ route('agent.agents.index', ['email_desc=DESC']) }}"
								class="{{ !empty(request('email_desc')) ? 'selected' : '' }}">
								<i class="bi bi-sort-up"></i>
								<input type="hidden" name="sort_data" value="0">
							</a>
							@endif
							@endif
						</th>
						<th>{{ isset($data['backendlang']['backendlang']['Phone']) ? $data['backendlang']['backendlang']['Phone'] :'' }}
							@if(empty(request('phone_desc')) && empty(request('phone_asc')))
							<a href="{{ route('agent.agents.index', ['phone_desc=DESC']) }}"
								class="{{ !empty(request('phone_desc')) ? 'selected' : '' }}">
								<i class="bi bi-sort-down"></i>
								<input type="hidden" name="sort_data" value="0">
							</a>
							@else
							@if(!empty(request('phone_desc')))
							<a href="{{ route('agent.agents.index', ['phone_asc=ASC']) }}"
								class="{{ !empty(request('phone_asc')) ? 'selected' : '' }}">
								<i class="bi bi-sort-down"></i>
								<input type="hidden" name="sort_data" value="1">
							</a>
							@elseif(!empty(request('phone_asc')))
							<a href="{{ route('agent.agents.index', ['phone_desc=DESC']) }}"
								class="{{ !empty(request('phone_desc')) ? 'selected' : '' }}">
								<i class="bi bi-sort-up"></i>
								<input type="hidden" name="sort_data" value="0">
							</a>
							@endif
							@endif
						</th>
						<!-- <th>Coin Balance</th> -->
						<th>{{ isset($data['backendlang']['backendlang']['Status']) ? $data['backendlang']['backendlang']['Status'] :'' }}
							@if (empty(request('status_desc')) && empty(request('status_asc')))
							<a href="{{ route('agent.agents.index', ['status_desc=DESC']) }}"
								class="{{ !empty(request('status_desc')) ? 'selected' : '' }}">
								<i class="bi bi-sort-down"></i>
								<input type="hidden" name="sort_data" value="0">
							</a>
							@else
							@if (!empty(request('status_desc')))
							<a href="{{ route('agent.agents.index', ['status_asc=ASC']) }}"
								class="{{ !empty(request('status_asc')) ? 'selected' : '' }}">
								<i class="bi bi-sort-down"></i>
								<input type="hidden" name="sort_data" value="1">
							</a>
							@elseif(!empty(request('status_asc')))
							<a href="{{ route('agent.agents.index', ['status_desc=DESC']) }}"
								class="{{ !empty(request('status_desc')) ? 'selected' : '' }}">
								<i class="bi bi-sort-up"></i>
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
					@if (!$agents->isEmpty())
					@foreach($agents as $key => $agent)
					<tr>
						<td>
							{{ $key+1 }}
							<input type="hidden" class="row_id" value="{{ $agent->id }}">
						</td>
						@if(Auth::user()->permission_lvl == 1)
						<td>
							{{ !empty($agent->get_merchant->f_name) ? $agent->get_merchant->f_name : 'By Admin' }}
							<br>
							({{ !empty($agent->get_merchant->code) ? $agent->get_merchant->code : 'AD000002' }})
						</td>
						@endif
						<td>{{ $agent->display_code }}{{ $agent->display_running_no }}</td>
						<td>{{ $agent->f_name }}</td>
						<td>
							@if(!empty($agent->get_upline_det->get_user_id_agent_det->code))
							{{ $agent->get_upline_det->get_user_id_agent_det->f_name }}
							@elseif(!empty($agent->get_upline_det->get_user_id_member_det->code))

							{{ $agent->get_upline_det->get_user_id_member_det->f_name }}

							@elseif(!empty($agent->get_upline_det->get_user_id_admin_det->code))

							{{ $agent->get_upline_det->get_user_id_admin_det->f_name }}
							{{ $agent->get_upline_det->get_user_id_admin_det->l_name }}

							@else
							<span style="color: red;">
								<i class="bi bi-minus"></i>

							</span>
							@endif
							<br>
							({{ $agent->master_id }})
						</td>
						<td>{{ $agent->email }}</td>
						<td>
							(+{{ $agent->country_code }})
							@if($agent->country_code && $agent->country_code == '60')
							{{ ($agent->phone[0] == 0) ? $agent->phone : '0'.$agent->phone }}
							@else
							{{ ($agent->phone[0] == 0) ? substr($agent->phone, 1) : $agent->phone }}
							@endif
							{{-- {{ $agent->phone }} --}}
						</td>
						<!-- <td>{{ !empty($GetWalletBalance[$agent->code]) ? number_format($GetWalletBalance[$agent->code], 2) : '0.00' }}</td> -->
						<td>
							@if ($agent->status == 1)
								<span class="badge bg-success">
									{{ $data['backendlang']['backendlang']['Active'] ?? '' }}
								</span>
							@else
								<span class="badge bg-danger">
										{{ $data['backendlang']['backendlang']['Inactive'] ?? '' }}
								</span>
							@endif
						</td>
						<td>{{ $agent->created_at }}</td>
						<td>
							@if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['agent-edit']))
							<a href="{{ route('agent.agents.edit', $agent->id) }}" class=" btn btn-outline-primary btn-sm" title="{{ isset($data['backendlang']['backendlang']['View_Edit']) ? $data['backendlang']['backendlang']['View_Edit'] :'' }}">
								<i class="ace-icon bi bi-pencil-square bigger-130"></i>
							</a>
							&nbsp;
							@endif


							@if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['agent-edit']))
							@if($agent->status == 1)
							<a href="#" class="red change-status btn btn-outline-danger btn-sm" data-id="2" title="{{ isset($data['backendlang']['backendlang']['Inactive']) ? $data['backendlang']['backendlang']['Inactive'] :'' }}">
								<i class="ace-icon bi bi-shield-fill-x bigger-130"></i>
							</a>
							@else
							<a href="#" class="green change-status btn btn-outline-success btn-sm" data-id="1" title="{{ isset($data['backendlang']['backendlang']['Reactive']) ? $data['backendlang']['backendlang']['Reactive'] :'' }}">
								<i class="ace-icon bi bi-shield-check bigger-130"></i>
							</a>
							@endif
							&nbsp;
							@endif


							@if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['agent-delete']))
							<a href="#" class="red change-status btn btn-outline-danger btn-sm" data-id="3" title="{{ isset($data['backendlang']['backendlang']['Delete']) ? $data['backendlang']['backendlang']['Delete'] :'' }}">
								<i class="ace-icon bi bi-trash bigger-130"></i>
							</a>
							&nbsp;
							@endif

							@if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['agent-affiliate']))
							<a href="{{ route('tree', [$agent->code]) }}" class="green btn btn-outline-success btn-sm" title="{{ isset($data['backendlang']['backendlang']['Affiliate']) ? $data['backendlang']['backendlang']['Affiliate'] :'' }}">
								<i class="ace-icon bi bi-person bigger-130"></i>
							</a>
							@endif
						</td>
					</tr>
					@endforeach
					@else
					<tr>
						<td colspan="15">{{ isset($data['backendlang']['backendlang']['No_Result_Found']) ? $data['backendlang']['backendlang']['No_Result_Found'] :'' }}</td>
					</tr>
					@endif
				</tbody>
			</table>
			{{ $agents->links() }}
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
				url: '{{ route("AgentStatus") }}',
				type: 'post',
				data: fd,
				contentType: false,
				processData: false,
				success: function(response) {
					$('.loading-gif').hide();
					if (response == 'ok') {
						toastr.success("{{ isset($data['backendlang']['backendlang']['Status_Changed']) ? $data['backendlang']['backendlang']['Status_Changed'] :'' }}");
						location.reload();
					}
					// window.location.href="{{ route('agent.agents.index') }}";
				},
				complete: function() {
					// Reload the page after the AJAX call is completed
					location.reload();
				}
			});
		} else {
			$('.loading-gif').hide();
		}
	});

	$(function() {
		$('[data-toggle="tooltip"]').tooltip()
	})

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