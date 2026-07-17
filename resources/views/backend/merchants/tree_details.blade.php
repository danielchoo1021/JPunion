@extends('layouts.admin_app')

@section('content')

<div class="page-header">
    <h1>
        {{ Request::segment(2) }}
        <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
            {{ $generation }} {{ isset($data['backendlang']['backendlang']['Generation_Downline_List']) ? $data['backendlang']['backendlang']['Generation_Downline_List'] :'' }}
        </small>
    </h1>
</div>

<form action="{{ route('merchant.merchants.index') }}" method="GET">
	<div class="row">
		<div class="col-sm-2">
			<div class="form-group">
				<input type="text" class="form-control" name="code" value="{{ !empty('code') && request('code') ? request('code') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Agent_Code']) ? $data['backendlang']['backendlang']['Search_Agent_Code'] :'' }}">
			</div>
		</div>
		<div class="col-sm-2">
			<div class="form-group">
				<input type="text" class="form-control" name="merchant_name" value="{{ !empty('merchant_name') && request('merchant_name') ? request('merchant_name') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Agent_Name']) ? $data['backendlang']['backendlang']['Search_Agent_Name'] :'' }}">
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
</form>
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
		<i class="fa fa-search"></i> {{ isset($data['backendlang']['backendlang']['Search']) ? $data['backendlang']['backendlang']['Search'] :'' }}
	</button>
	<a href="{{ route('merchant.merchants.index') }}" class="btn btn-warning btn-sm">
		<i class="fa fa-refresh"></i> {{ isset($data['backendlang']['backendlang']['Clear_Search']) ? $data['backendlang']['backendlang']['Clear_Search'] :'' }}
	</a>
</div>

<div class="row" style="overflow: auto;">
	<div class="col-12">
		<table class="table table-bordered">
			<thead>
				<tr class="info">
					<th>#</th>
					<th>{{ isset($data['backendlang']['backendlang']['Code']) ? $data['backendlang']['backendlang']['Code'] :'' }}</th>
					<th>{{ isset($data['backendlang']['backendlang']['Name']) ? $data['backendlang']['backendlang']['Name'] :'' }}</th>
					<th>{{ isset($data['backendlang']['backendlang']['Level']) ? $data['backendlang']['backendlang']['Level'] :'' }}</th>
					<th>{{ isset($data['backendlang']['backendlang']['Upline']) ? $data['backendlang']['backendlang']['Upline'] :'' }}</th>
					<th>{{ isset($data['backendlang']['backendlang']['Email']) ? $data['backendlang']['backendlang']['Email'] :'' }}</th>
					<th>{{ isset($data['backendlang']['backendlang']['Phone']) ? $data['backendlang']['backendlang']['Phone'] :'' }}</th>
					<th>{{ isset($data['backendlang']['backendlang']['Status']) ? $data['backendlang']['backendlang']['Status'] :'' }}</th>
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
					<td>{{ $merchant->code }}</td>
					<td>{{ $merchant->f_name }} {{ $merchant->l_name }}</td>
					<td>{{ !empty($merchant->lvl) ? $merchant->l_agent_lvl : ' - ' }}</td>
					<td>{{ $merchant->master_id }}</td>
					<td>{{ $merchant->email }}</td>
					<td>{{ $merchant->phone }}</td>
					<td>	
						@if ($merchant->status == 1)
								<span class="badge bg-success">
									{{ $data['backendlang']['backendlang']['Active'] ? $data['backendlang']['backendlang']['Active'] : '' }}
								</span>
							@else
								<span class="badge bg-danger">
									{{ $data['backendlang']['backendlang']['Inactive'] ? $data['backendlang']['backendlang']['Inactive'] : '' }}
								</span>
							@endif</td>
					<td>
						<a href="{{ route('merchant.merchants.edit', $merchant->id) }}">
							<i class="ace-icon fa fa-pencil bigger-130"></i> {{ isset($data['backendlang']['backendlang']['Edit']) ? $data['backendlang']['backendlang']['Edit'] :'' }}
						</a>

						&nbsp;&nbsp;
						@if($merchant->status == 1)
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
						<a href="{{ route('tree', [$merchant->code]) }}" class="green">
							<i class="ace-icon fa fa-users bigger-130"></i> {{ isset($data['backendlang']['backendlang']['Affiliate']) ? $data['backendlang']['backendlang']['Affiliate'] :'' }}
						</a>
					</td>
				</tr>
				@endforeach
				@else
				<tr>
					<td colspan="9">{{ isset($data['backendlang']['backendlang']['No_Result_Found']) ? $data['backendlang']['backendlang']['No_Result_Found'] :'' }}</td>
				</tr>
				@endif
			</tbody>
		</table>
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

        $.ajax({
           url: '{{ route("MerchantStatus") }}',
           type: 'post',
           data: fd,
           contentType: false,
           processData: false,
           success: function(response){
                $('.loading-gif').hide();
                toastr.success("{{ isset($data['backendlang']['backendlang']['Status_Changed']) ? $data['backendlang']['backendlang']['Status_Changed'] :'' }}");
                window.location.href="{{ route('merchant.merchants.index') }}";
           },
        });
    });
</script>
@endsection