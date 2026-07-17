@extends('layouts.admin_app')

@section('content')
<div class="container-box form-group">
	<h3>
		{{ isset($data['backendlang']['backendlang']['Filter']) ? $data['backendlang']['backendlang']['Filter'] :'' }}
	</h3>
	<hr>
	<form action="{{ route('setting_all_faq.setting_all_faqs.index') }}" method="GET">
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group">
					<select class="form-control" name="type">
						<option value="">{{ isset($data['backendlang']['backendlang']['Select_Type']) ? $data['backendlang']['backendlang']['Select_Type'] :'' }}</option>
						@foreach($faqs_desctiption as $key => $description)
						<option value="{{ $key }}">
							{{ $description }}
						</option>
						@endforeach
					</select>
				</div>
			</div>
		</div>

		<div class="form-group">
			<div class="row">
				<div class="col-sm-12">
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
			<a href="{{ route('setting_all_faq.setting_all_faqs.index') }}" class="btn btn-warning btn-sm">
				<i class="bi bi-arrow-clockwise"></i> {{ isset($data['backendlang']['backendlang']['Clear_Search']) ? $data['backendlang']['backendlang']['Clear_Search'] :'' }}
			</a>
		</div>
	</form>
</div>

<div class="form-group container-box">
	@if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['faqs-insert']))
		<div class="form-group" align="right">
			<a href="{{ route('setting_all_faq.setting_all_faqs.create') }}" class="btn btn-outline-success btn-sm">
				<i class="bi bi-plus"></i> {{ isset($data['backendlang']['backendlang']['Add_New_FAQs']) ? $data['backendlang']['backendlang']['Add_New_FAQs'] :'' }}
			</a>
		</div>
	@endif
	<div class="row" style="overflow: auto;">
		<div class="col-12">
			<table class="table table-bordered">
				<thead>
					<tr class="info">
						<th>#</th>
						<td>{{ isset($data['backendlang']['backendlang']['Type']) ? $data['backendlang']['backendlang']['Type'] :'' }}</td>
						<td>{{ isset($data['backendlang']['backendlang']['Question']) ? $data['backendlang']['backendlang']['Question'] :'' }}</td>
						<td>{{ isset($data['backendlang']['backendlang']['Answer']) ? $data['backendlang']['backendlang']['Answer'] :'' }}</td>
						<td>{{ isset($data['backendlang']['backendlang']['Status']) ? $data['backendlang']['backendlang']['Status'] :'' }}</td>
						<td>{{ isset($data['backendlang']['backendlang']['Date']) ? $data['backendlang']['backendlang']['Date'] :'' }}</td>
						<th>{{ isset($data['backendlang']['backendlang']['Action']) ? $data['backendlang']['backendlang']['Action'] :'' }}</th>
					</tr>
				</thead>
				<tbody>
					@if (!$faqs->isEmpty())
					@foreach($faqs as $key => $faq)
					<tr>
						<td>
							{{ $key+1 }}
							<input type="hidden" class="row_id" value="{{ $faq->id }}">
						</td>
						<td>
							{{ $get_type[$faq->id] }}
						</td>
						<td>{{ $faq->question }}</td>
						<td>{{ $faq->answer }}</td>
						<td>
							@if ($faq->status == 1)
								<span class="badge bg-success">
									{{ $data['backendlang']['backendlang']['Active'] ?? '' }}
								</span>
							@else
								<span class="badge bg-danger">
									{{ $data['backendlang']['backendlang']['Inactive'] ?? '' }}
								</span>
							@endif
						</td>
						<td>{{ $faq->created_at }}</td>
						<td>

							@if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['faqs-edit']))
							<a href="{{ route('setting_all_faq.setting_all_faqs.edit', $faq->id) }}" class="btn btn-outline-primary btn-sm">
								<i style="width: 20px;" class="ace-icon bi bi-pencil bigger-130"></i>
							</a>
							@else
							<a href="{{ route('setting_all_faq.setting_all_faqs.edit', $faq->id) }}" class="btn btn-outline-primary btn-sm">
								<i style="width: 20px;" class="ace-icon bi bi-pencil bigger-130"></i>
							</a>
							@endif
							<br>
							<br>
							
							@if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['faqs-edit']))
								@if($faq->status == 1)
								<a href="#" class="red change-status btn btn-outline-danger btn-sm" data-id="2">
									<i style="width: 20px;" class="ace-icon bi bi-shield-fill-x bigger-130"></i>
								</a>
								@else
								<a href="#" class="green change-status btn btn-outline-success btn-sm" data-id="1">
									<i style="width: 20px;" class="ace-icon bi bi-shield-fill-x bigger-130"></i>
								</a>
								@endif
							@endif
							
							<br>
							<br>
							@if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['faqs-delete']))
							<a href="#" class="red change-status btn btn-outline-danger btn-sm" data-id="3">
								<i style="width: 20px;" class="ace-icon bi bi-trash bigger-130"></i>
							</a>
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
			{{ $faqs->links() }}
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
	           url: '{{ route("FAQsStatus") }}',
	           type: 'post',
	           data: fd,
	           contentType: false,
	           processData: false,
	           success: function(response){
	                $('.loading-gif').hide();
	                toastr.success("{{ isset($data['backendlang']['backendlang']['Status_Changed']) ? $data['backendlang']['backendlang']['Status_Changed'] :'' }}");
	                window.location.href="{{ route('setting_all_faq.setting_all_faqs.index') }}";
	           },
	        });
	    }else{
        	$('.loading-gif').hide();
        }
    });
</script>
@endsection