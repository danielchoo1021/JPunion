@extends('layouts.admin_app')

@section('content')
<form action="{{ route('corporate.corporates.index') }}" method="GET">
<div class="row">
	<div class="col-sm-12">
		<div class="form-group">
			<input type="text" class="form-control" name="shop_locator_name" value="{{ !empty('shop_locator_name') && request('shop_locator_name') ? request('shop_locator_name') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Distributor_Name']) ? $data['backendlang']['backendlang']['Search_Distributor_Name'] :'' }}">
		</div>
	</div>
	<div class="col-sm-12">
		<div class="form-group">
			<select class="form-control" name="status">
				<option value="">{{ isset($data['backendlang']['backendlang']['Select_Status']) ? $data['backendlang']['backendlang']['Select_Status'] :'' }}</option>
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
					<th>{{ isset($data['backendlang']['backendlang']['User']) ? $data['backendlang']['backendlang']['User'] :'' }}</th>
					<th>{{ isset($data['backendlang']['backendlang']['Rates_Star']) ? $data['backendlang']['backendlang']['Rates_Star'] :'' }}</th>
					<th>{{ isset($data['backendlang']['backendlang']['Comments']) ? $data['backendlang']['backendlang']['Comments'] :'' }}</th>
					<th>{{ isset($data['backendlang']['backendlang']['Photo']) ? $data['backendlang']['backendlang']['Photo'] :'' }}</th>
					<th>{{ isset($data['backendlang']['backendlang']['Date']) ? $data['backendlang']['backendlang']['Date'] :'' }}</th>
					<th>{{ isset($data['backendlang']['backendlang']['Action']) ? $data['backendlang']['backendlang']['Action'] :'' }}</th>
				</tr>
			</thead>
			<tbody>
				@if (!$histories->isEmpty())
				@foreach($histories as $key => $rate)
				<tr>
					<td>
						{{ $key+1 }}
						<input type="hidden" class="row_id" value="{{ $rate->id }}">
					</td>
					<td>
						@if(!empty($rate->get_reviewer_user->f_name))
                            <span style="font-size: 15px;">
                                {{ $rate->get_reviewer_user->f_name }}
                            </span>
                        @elseif(!empty($rate->get_reviewer_agent->f_name))
                            <span style="font-size: 15px;">
                                {{ $rate->get_reviewer_agent->f_name }}
                            </span>
                        @endif
					</td>
					<td>
						@if($rate->rating == 5)
                            <i class="fa fa-star" style="color: #ff7600;"></i>
                            <i class="fa fa-star" style="color: #ff7600;"></i>
                            <i class="fa fa-star" style="color: #ff7600;"></i>
                            <i class="fa fa-star" style="color: #ff7600;"></i>
                            <i class="fa fa-star" style="color: #ff7600;"></i>
                        @elseif($rate->rating == 4)
                            <i class="fa fa-star" style="color: #ff7600;"></i>
                            <i class="fa fa-star" style="color: #ff7600;"></i>
                            <i class="fa fa-star" style="color: #ff7600;"></i>
                            <i class="fa fa-star" style="color: #ff7600;"></i>
                            <i class="fa fa-star-o comments"></i>
                        @elseif($rate->rating == 3)
                            <i class="fa fa-star" style="color: #ff7600;"></i>
                            <i class="fa fa-star" style="color: #ff7600;"></i>
                            <i class="fa fa-star" style="color: #ff7600;"></i>
                            <i class="fa fa-star-o comments"></i>
                            <i class="fa fa-star-o comments"></i>
                        @elseif($rate->rating == 2)
                            <i class="fa fa-star" style="color: #ff7600;"></i>
                            <i class="fa fa-star" style="color: #ff7600;"></i>
                            <i class="fa fa-star-o comments"></i>
                            <i class="fa fa-star-o comments"></i>
                            <i class="fa fa-star-o comments"></i>
                        @elseif($rate->rating == 1)
                            <i class="fa fa-star" style="color: #ff7600;"></i>
                            <i class="fa fa-star-o comments"></i>
                            <i class="fa fa-star-o comments"></i>
                            <i class="fa fa-star-o comments"></i>
                            <i class="fa fa-star-o comments"></i>
                        @endif
					</td>
					<td style="white-space: pre-line;">{{ $rate->rating_desc }}</td>
					<td>
						@if(!empty($rate->rating_file))
	                        <div class="form-group">
	                            <a href="#" data-toggle="modal" data-target="#view-image{{ $rate->id }}">
	                                <img src="{{ asset($rate->rating_file) }}" width="150px">
	                            </a>
	                            <div class="modal fade" id="view-image{{ $rate->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	                                <div class="modal-dialog modal-lg" role="document">
	                                    <div class="modal-content">
	                                        <div class="modal-body">
	                                            <img src="{{ asset($rate->rating_file) }}" width="100%">
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
	                    @endif
					</td>
					<td>{{ $rate->created_at }}</td>
					<td>
						<a href="#" class="red change-status" data-id="3">
							<i class="ace-icon fa fa-trash-o bigger-130"></i> {{ isset($data['backendlang']['backendlang']['Delete']) ? $data['backendlang']['backendlang']['Delete'] :'' }}
						</a>
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
		{{ $histories->links() }}
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
	           url: '{{ route("RatingStatus") }}',
	           type: 'post',
	           data: fd,
	           contentType: false,
	           processData: false,
	           success: function(response){
	                $('.loading-gif').hide();
	                toastr.success("{{ isset($data['backendlang']['backendlang']['Status_Changed']) ? $data['backendlang']['backendlang']['Status_Changed'] :'' }}");
	                location.reload()
	           },
	        });
	    }else{
        	$('.loading-gif').hide();
        }
    });
</script>
@endsection