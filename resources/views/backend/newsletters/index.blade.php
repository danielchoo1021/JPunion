@extends('layouts.admin_app')

@section('content')
<form action="{{ route('newsletter.newsletters.index') }}" method="GET">
<div class="row">
	<!-- <div class="col-sm-2">
		<div class="form-group">
			<input type="text" class="form-control" name="code" value="{{ !empty('code') && request('code') ? request('code') : '' }}" placeholder="Search Agent Code">
		</div>
	</div> -->
	<div class="col-sm-2">
		<div class="form-group">
			<input type="text" class="form-control" name="f_name" value="{{ !empty('f_name') && request('f_name') ? request('f_name') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Name']) ? $data['backendlang']['backendlang']['Search_Name'] :'' }}">
		</div>
	</div>



	<!-- <div class="col-sm-2">
		<div class="form-group">
			<select class="form-control" name="status">
				<option value="">Search Status</option>
				<option {{ (!empty(request('status')) && request('status') == '1') ? 'selected' : '' }} value="1">Active</option>
				<option {{ (!empty(request('status')) && request('status') == '2') ? 'selected' : '' }} value="2">Inactive</option>
			</select>
		</div>
	</div> -->
</div>

<div class="form-group">
	<div class="row">
		<!-- <div class="col-sm-2">
			<div class="form-group">
				Row Per Page: <br>
				<select class="input-small" name="per_page">
					<option value="10">10</option>
					<option value="20">20</option>
					<option value="50">50</option>
				</select>
			</div>
		</div> -->
	</div>
</div>
<div class="form-group">
	<button class="btn btn-outline-primary btn-sm">
		<i class="fa fa-search"></i> {{ isset($data['backendlang']['backendlang']['Search']) ? $data['backendlang']['backendlang']['Search'] :'' }}
	</button>
	<a href="{{ route('newsletter.newsletters.index') }}" class="btn btn-warning btn-sm">
		<i class="fa fa-refresh"></i> {{ isset($data['backendlang']['backendlang']['Clear_Search']) ? $data['backendlang']['backendlang']['Clear_Search'] :'' }}
	</a>
</div>
</form>
<div class="row" style="overflow: auto;">
	<div class="col-12">
		<table class="table table-bordered">
			<p class="important-text" style="font-size: 12px;">{{ isset($data['backendlang']['backendlang']['Tick_Checkbox_For_Users_To_Receive_Newsletter']) ? $data['backendlang']['backendlang']['Tick_Checkbox_For_Users_To_Receive_Newsletter'] :'' }}*</p>
			<thead>
				<tr class="info">
					<th>#
						<input type="checkbox" name="check_all" class="check_all">
					</th>
					<th>{{ isset($data['backendlang']['backendlang']['Name']) ? $data['backendlang']['backendlang']['Name'] :'' }}</th>
					<th>{{ isset($data['backendlang']['backendlang']['Code']) ? $data['backendlang']['backendlang']['Code'] :'' }}</th>
					<th>{{ isset($data['backendlang']['backendlang']['Level']) ? $data['backendlang']['backendlang']['Level'] :'' }}</th>
					<th>{{ isset($data['backendlang']['backendlang']['Email']) ? $data['backendlang']['backendlang']['Email'] :'' }}</th>
					<th>{{ isset($data['backendlang']['backendlang']['Phone']) ? $data['backendlang']['backendlang']['Phone'] :'' }}</th>
					<th>{{ isset($data['backendlang']['backendlang']['Status']) ? $data['backendlang']['backendlang']['Status'] :'' }}</th>
				</tr>
			</thead>
			<tbody>
				@if (!$merchants->isEmpty())
				@foreach($merchants as $key => $merchant)
				@php
				
				@endphp
				<tr>
					<td>
						{{ $key+1 }}
						<input type="hidden" class="row_id" name="row_id" value="{{ $merchant->id }}">
						<input type="checkbox" name="receive_newsletter" value="1" {{ $merchant->receive_newsletter == 1 ? 'checked' : '' }}>
						<input type="hidden" name="isAgent" value="{{ !empty($merchant->lvl) ? '1' : '0' }}">
					</td>
					<td>{{ $merchant->f_name }} {{ $merchant->l_name }}</td>
					<td>{{ $merchant->code }}</td>
					<td>
						@if(!empty($merchant->lvl))
							{{ $data['backendlang']['backendlang']['Agent'] ?? 'Agent' }}
						@else
							{{ $data['backendlang']['backendlang']['Member'] ?? 'Member' }}
						@endif
					</td>

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
							@endif
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
		{{ $merchants->links() }}
	</div>
</div>
<div class="form-group">
	<div class="submit-form-btn">
	    <div class="form-group wizard-actions" align="right">
	        <button class="btn btn-outline-primary">
	            <i class="fa fa-check"> {{ isset($data['backendlang']['backendlang']['Save_Changes']) ? $data['backendlang']['backendlang']['Save_Changes'] :'' }}</i>
	        </button>

	    </div>
	</div>
</div>
@endsection

@section('js')
<script type="text/javascript">
	$('.check_all').click( function(e){
		if ($(this).prop('checked')) {
            $('input[name="receive_newsletter"]').prop('checked', true);
        } else {
            $('input[name="receive_newsletter"]').prop('checked', false);
        }
	});

	$('.submit-form-btn .btn-outline-primary').click( function(e){
       e.preventDefault();
       var fd = new FormData();
       $('.row_id').each( function(){
	       	var ele = $(this);
	        var row_id = ele.closest('tr').find('.row_id').val();
	        var isAgent = ele.closest('tr').find('input[name="isAgent"]').val();
	        var receive_newsletter = ele.closest('tr').find('input[name="receive_newsletter"]:checked').val();

	       fd.append('isAgent', isAgent);
	       fd.append('id', row_id);
	       fd.append('receive_newsletter', receive_newsletter);

	       
	       // console.log('id: '+row_id);
	       // console.log('isAgent: '+isAgent);
	       // console.log('newsletter: '+receive_newsletter);

	       $.ajax({
                url: '{{ route("updateReceiveNewsletter") }}',
                type: 'post',
                data: fd,
                contentType: false,
                processData: false,
                success: function(response){
                    location.reload();
                },
            });
	      
	       
	   });

      // console.log(fd);
      // location.reload();

        
    });
</script>
@endsection