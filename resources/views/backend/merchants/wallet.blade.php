@extends('layouts.admin_app')

@section('content')
<div class="container-box form-group">
	<h4>Filter</h4>
	<form action="{{ route('merchant.merchants.index') }}" method="GET">
	<div class="row">
		<div class="col-sm-2">
			<div class="form-group">
				<input type="text" class="form-control" name="code" value="{{ !empty('code') && request('code') ? request('code') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Code']) ? $data['backendlang']['backendlang']['Search_Code'] :'' }}">
			</div>
		</div>
		<div class="col-sm-2">
			<div class="form-group">
				<input type="text" class="form-control" name="merchant_name" value="{{ !empty('merchant_name') && request('merchant_name') ? request('merchant_name') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Name']) ? $data['backendlang']['backendlang']['Search_Name'] :'' }}">
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
		<a href="{{ route('merchant.merchants.index') }}" class="btn btn-warning btn-sm">
			<i class="bi bi-arrow-clockwise"></i> {{ isset($data['backendlang']['backendlang']['Clear_Search']) ? $data['backendlang']['backendlang']['Clear_Search'] :'' }}
		</a>
	</div>
	</form>
</div>

<div class="container-box form-group">
	<div class="row" style="overflow: auto;">
		{{ $merchants->links() }}
		<div class="col-12">
			<table class="table table-bordered">
				<thead>
					<tr class="info">
						<th>#</th>
						<th>{{ isset($data['backendlang']['backendlang']['Code']) ? $data['backendlang']['backendlang']['Code'] :'' }}
							@if(empty(request('code_desc')) && empty(request('code_asc')))
								<a href="{{ route('merchant.merchants.index', ['code_desc=DESC']) }}" 
								   class="{{ !empty(request('code_desc')) ? 'selected' : '' }}">
									<i class="bi bi-sort-down"></i>
									<input type="hidden" name="sort_data" value="0">
								</a>
							@else
								@if(!empty(request('code_desc')))
									<a href="{{ route('merchant.merchants.index', ['code_asc=ASC']) }}" 
									   class="{{ !empty(request('code_asc')) ? 'selected' : '' }}">
										<i class="bi bi-sort-down"></i>
										<input type="hidden" name="sort_data" value="1">
									</a>
								@elseif(!empty(request('code_asc')))
									<a href="{{ route('merchant.merchants.index', ['code_desc=DESC']) }}" 
									   class="{{ !empty(request('code_desc')) ? 'selected' : '' }}">
										<i class="bi bi-sort-up"></i>
										<input type="hidden" name="sort_data" value="0">
									</a>
								@endif
							@endif
						</th>
						<th>{{ isset($data['backendlang']['backendlang']['Name']) ? $data['backendlang']['backendlang']['Name'] :'' }}
							@if(empty(request('name_desc')) && empty(request('name_asc')))
								<a href="{{ route('merchant.merchants.index', ['name_desc=DESC']) }}" 
								   class="{{ !empty(request('name_desc')) ? 'selected' : '' }}">
									<i class="bi bi-sort-down"></i>
									<input type="hidden" name="sort_data" value="0">
								</a>
							@else
								@if(!empty(request('name_desc')))
									<a href="{{ route('merchant.merchants.index', ['name_asc=ASC']) }}" 
									   class="{{ !empty(request('name_asc')) ? 'selected' : '' }}">
										<i class="bi bi-sort-down"></i>
										<input type="hidden" name="sort_data" value="1">
									</a>
								@elseif(!empty(request('name_asc')))
									<a href="{{ route('merchant.merchants.index', ['name_desc=DESC']) }}" 
									   class="{{ !empty(request('name_desc')) ? 'selected' : '' }}">
										<i class="bi bi-sort-up"></i>
										<input type="hidden" name="sort_data" value="0">
									</a>
								@endif
							@endif
						</th>
						
						<th>{{ isset($data['backendlang']['backendlang']['Coin_Balance']) ? $data['backendlang']['backendlang']['Coin_Balance'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Status']) ? $data['backendlang']['backendlang']['Status'] :'' }}
							@if(empty(request('status_desc') && empty(request('status_asc'))))
								<a href="{{ route('merchant.merchants.index', ['status_desc=DESC']) }}" 
								   class="{{ !empty(request('status_desc')) ? 'selected' : '' }}">
									<i class="bi bi-sort-down"></i>
									<input type="hidden" name="sort_data" value="0">
								</a>
							@else
								@if(!empty(request('status_desc')))
									<a href="{{ route('merchant.merchants.index', ['status_asc=ASC']) }}" 
									   class="{{ !empty(request('status_asc')) ? 'selected' : '' }}">
										<i class="bi bi-sort-down"></i>
										<input type="hidden" name="sort_data" value="1">
									</a>
								@elseif(!empty(request('status_asc')))
									<a href="{{ route('merchant.merchants.index', ['status_desc=DESC']) }}" 
									   class="{{ !empty(request('status_desc')) ? 'selected' : '' }}">
										<i class="bi bi-sort-up"></i>
										<input type="hidden" name="sort_data" value="0">
									</a>
								@endif
							@endif
						</th>
						<!-- <th>Date</th> -->
						<th>{{ isset($data['backendlang']['backendlang']['Action']) ? $data['backendlang']['backendlang']['Action'] :'' }}</th>
					</tr>
				</thead>
				<tbody>
					@if (!$merchants->isEmpty())
					@foreach($merchants as $key => $merchant)
					<tr>
						<td>
							{{ $key+1 }}
							<input type="hidden" class="row_id" value="{{ $merchant->id }}">
						</td>
						<td>{{ $merchant->display_code }}{{ $merchant->display_running_no }}</td>
						<td>{{ $merchant->f_name }}</td>
						<td>{{ !empty($GetWalletBalance[$merchant->code]) ? number_format($GetWalletBalance[$merchant->code], 2) : '0.00' }}</td>
						<td>
							@if ($merchant->status == 1)
								<span class="badge bg-success">
									{{ $data['backendlang']['backendlang']['Active'] ? $data['backendlang']['backendlang']['Active'] : '' }}
								</span>
							@else
								<span class="badge bg-danger">
									{{ $data['backendlang']['backendlang']['Inactive'] ? $data['backendlang']['backendlang']['Inactive'] : '' }}
								</span>
							@endif
						</td>
						<td>{{ $merchant->created_at }}</td>
						<td>
							@if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['agent-wallet-history']))
								<a href="{{ route('merchant.merchants.edit', $merchant->id) }}" class=" btn btn-outline-primary btn-sm" title="{{ isset($data['backendlang']['backendlang']['History']) ? $data['backendlang']['backendlang']['History'] :'' }}">
									<i class="ace-icon bi bi-eye bigger-130"></i>
								</a>
								&nbsp;
							@endif
							
							@if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['agent-wallet-adjust']))
								<a href="{{ route('adjustCash', [$merchant->id]) }}" class="blue btn btn-outline-info btn-sm" title="{{ isset($data['backendlang']['backendlang']['Adjust_Coin']) ? $data['backendlang']['backendlang']['Adjust_Coin'] :'' }}">
									<i class="ace-icon bi bi-plus bigger-130"></i>
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
			{{ $merchants->links() }}
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
	           url: '{{ route("MerchantStatus") }}',
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
	                // window.location.href="{{ route('merchant.merchants.index') }}";
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

    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>
@endsection