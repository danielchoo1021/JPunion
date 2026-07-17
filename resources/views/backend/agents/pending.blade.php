@extends('layouts.admin_app')

@section('content')

<div class="container-box form-group">
	<h3>{{ isset($data['backendlang']['backendlang']['Filter']) ? $data['backendlang']['backendlang']['Filter'] :'' }}</h3>
	<form action="{{ route('pending_agent') }}" method="GET">
		<div class="row">
			<div class="col-sm-2">
				<div class="form-group">
					<input type="text" class="form-control" name="code" value="{{ !empty('code') && request('code') ? request('code') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Agent_Code']) ? $data['backendlang']['backendlang']['Search_Agent_Code'] :'' }}">
				</div>
			</div>

			<div class="col-sm-2">
				<div class="form-group">
					<input type="text" class="form-control" name="agent_name" value="{{ !empty('agent_name') && request('agent_name') ? request('agent_name') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Agent_Name']) ? $data['backendlang']['backendlang']['Search_Agent_Name'] :'' }}">
				</div>
			</div>

			<div class="col-sm-2">
				<div class="form-group">
					<input type="text" class="form-control" name="referral_code" value="{{ !empty('referral_code') && request('referral_code') ? request('referral_code') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Referral_Code']) ? $data['backendlang']['backendlang']['Search_Referral_Code'] :'' }}">
				</div>
			</div>

			<div class="col-sm-2">
				<div class="form-group">
					<input type="text" class="form-control" name="referral_name" value="{{ !empty('referral_name') && request('referral_name') ? request('referral_name') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Referral_Name']) ? $data['backendlang']['backendlang']['Search_Referral_Name'] :'' }}">
				</div>
			</div>

			<div class="col-sm-2">
				<div class="form-group">
					<input type="text" class="form-control" name="email" value="{{ !empty('email') && request('email') ? request('email') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Email']) ? $data['backendlang']['backendlang']['Search_Email'] :'' }}">
				</div>
			</div>

			<div class="col-sm-2">
				<div class="form-group">
					<input type="text" class="form-control" name="phone" value="{{ !empty('phone') && request('phone') ? request('phone') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Phone']) ? $data['backendlang']['backendlang']['Search_Phone'] :'' }}">
				</div>
			</div>

			<div class="col-sm-2">
				<div class="form-group">
					<input type="text" class="form-control" name="joining_product" value="{{ !empty('joining_product') && request('joining_product') ? request('joining_product') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Joining_Product']) ? $data['backendlang']['backendlang']['Search_Joining_Product'] :'' }}">
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
			<a href="{{ route('pending_agent') }}" class="btn btn-warning btn-sm">
				<i class="bi bi-arrow-clockwise"></i> {{ isset($data['backendlang']['backendlang']['Clear_Search']) ? $data['backendlang']['backendlang']['Clear_Search'] :'' }}
			</a>
		</div>
	</form>
</div>
<div class="form-group container-box">
	<div class="row" style="overflow: auto;">
		{{ $agents->links() }}
		<div class="col-12">
			<table class="table table-bordered">
				<thead>
					<tr class="info">
						<th>#</th>
						@if(Auth::user()->permission_lvl == 1)
						<th>{{ isset($data['backendlang']['backendlang']['Merchant']) ? $data['backendlang']['backendlang']['Merchant'] :'' }}</th>
						@endif
						<th>{{ isset($data['backendlang']['backendlang']['Code']) ? $data['backendlang']['backendlang']['Code'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Name']) ? $data['backendlang']['backendlang']['Name'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Referral_Code']) ? $data['backendlang']['backendlang']['Referral_Code'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Referral_Name']) ? $data['backendlang']['backendlang']['Referral_Name'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Email']) ? $data['backendlang']['backendlang']['Email'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Phone']) ? $data['backendlang']['backendlang']['Phone'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Joining_Product']) ? $data['backendlang']['backendlang']['Joining_Product'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Status']) ? $data['backendlang']['backendlang']['Status'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Action']) ? $data['backendlang']['backendlang']['Action'] :'' }}</th>
					</tr>
				</thead>
				<tbody>
					@if (!$agents->isEmpty())
					@foreach($agents as $key => $agent)
					<tr>
						<td>{{ $key+1 }}
							<input type="hidden" name='mid' value="{{ $agent->id }}">
						</td>
						@if(Auth::user()->permission_lvl == 1)
						<td>
							{{ !empty($agent->get_merchant->f_name) ? $agent->get_merchant->f_name : 'By Admin' }}
							<br>
							({{ !empty($agent->get_merchant->code) ? $agent->get_merchant->code : 'AD000002' }})
						</td>
						@endif
						<td>{{ $agent->code }}</td>
						<td>{{ $agent->f_name }} {{ $agent->l_name }}</td>
						<td>{{ $agent->upline_code }}</td>
						<td>{{ $agent->upline_name }}</td>
						<td>{{ $agent->email }}</td>
						<td>{{ $agent->phone }}</td>
						<td align="center">
							@if(!empty($transaction_bank_slip[$agent->code]->bank_slip))
								<a href="{{ route('transaction.transactions.edit', [$transaction_bank_slip[$agent->code]->id]) }}">
									{{ $transaction_bank_slip[$agent->code]->transaction_no }}								
								</a>
								<br>
								<a href="#" data-toggle="modal" data-target="#myModala{{ $agent->code }}">
									<img src="{{ asset($transaction_bank_slip[$agent->code]->bank_slip) }}" width="100px">						
								</a>

								<div class="modal fade" id="myModala{{ $agent->code }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
								  	<div class="modal-dialog" role="document">
								    	<div class="modal-content">
								      		<div class="modal-body">
								        		<img src="{{ asset($transaction_bank_slip[$agent->code]->bank_slip) }}" width="100%">
								      		</div>
								    	</div>
								  	</div>
								</div>
							@else
								<i class="bi bi-dash"></i>
							@endif
						</td>
						<td><span class="badge bg-warning">{{ isset($data['backendlang']['backendlang']['Pending']) ? $data['backendlang']['backendlang']['Pending'] :'' }}</span></td>
						<td>
							@if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['agent-approve-reject']))
								<a href="#" class="change_action btn btn-sm btn-outline-success" data-id="1" title="{{ isset($data['backendlang']['backendlang']['Approve']) ? $data['backendlang']['backendlang']['Approve'] :'' }}">
									<i class="ace-icon bi bi-shield-check bigger-130"></i>
								</a>
								<a href="#" class="change_action btn btn-sm btn-outline-danger" data-id="98" title="{{ isset($data['backendlang']['backendlang']['Reject']) ? $data['backendlang']['backendlang']['Reject'] :'' }}">
									<i class="ace-icon bi bi-shield-fill-x bigger-130"></i>
								</a>
							@endif
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
			{{ $agents->links() }}
		</div>
	</div>
</div>
@endsection

@section('js')
<script type="text/javascript">
	$('.change_action').click( function(e){
		e.preventDefault();

		$('.loading-gif').show();
		var ele = $(this);
		var action_id = $(this).data('id');
		var mid = $(this).closest('tr').find('input[name="mid"]').val();
		var fd = new FormData();
		fd.append('action_id', action_id);
		fd.append('mid', mid);

		if(action_id == '1'){
			var action_confirm = confirm("{{ isset($data['backendlang']['backendlang']['Approve_this_agent']) ? $data['backendlang']['backendlang']['Approve_this_agent'] :'' }}");
		}else{
			var action_confirm = confirm("{{ isset($data['backendlang']['backendlang']['Reject_this_agent']) ? $data['backendlang']['backendlang']['Reject_this_agent'] :'' }}");
		}
		if(action_confirm == true){
			$.ajax({
		       url: '{{ route("ApproveRejectMerchant") }}',
		       type: 'post',
		       data: fd,
		       contentType: false,
		       processData: false,
		       success: function(response){
		       		// alert(response);
		       		$('.loading-gif').hide();
		       		if(response == "ok"){
			       		toastr.success("{{ isset($data['backendlang']['backendlang']['Update_Successful']) ? $data['backendlang']['backendlang']['Update_Successful'] :'' }}");
			       		window.location.href = "{{ route('pending_agent') }}";
		       		}else{
		       			toastr.error(response);
		       		}
		       },
		    });			
		}else{
			$('.loading-gif').hide();
		}
	});
</script>
@endsection