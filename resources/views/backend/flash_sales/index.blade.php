@extends('layouts.admin_app')

@section('content')
<form action="{{ route('flash_sale.flash_sales.index') }}" method="GET">
<div class="container-box form-group">
	<h3>
		{{ isset($data['backendlang']['backendlang']['Filter']) ? $data['backendlang']['backendlang']['Filter'] :'' }}
	</h3>
	<hr>
	<div class="row">
		<div class="col-sm-2">
			<div class="form-group">
				<input type="text" class="form-control" name="title"
						value="{{ !empty('title') && request('title') ? request('title') : '' }}"
						placeholder="{{ isset($data['backendlang']['backendlang']['Search_Title']) ? $data['backendlang']['backendlang']['Search_Title'] :'' }}">
			</div>
		</div>
		<div class="col-sm-2">
			<div class="form-group">
				<select class="form-control" name="status">
					<option value=""> {{ isset($data['backendlang']['backendlang']['Select_Status']) ? $data['backendlang']['backendlang']['Select_Status'] :'' }}</option>
					<option {{ (!empty(request('status')) && request('status') == '1') ? 'selected' : '' }} value="1"> {{ isset($data['backendlang']['backendlang']['Active']) ? $data['backendlang']['backendlang']['Active'] :'' }}</option>
					<option {{ (!empty(request('status')) && request('status') == '2') ? 'selected' : '' }} value="2"> {{ isset($data['backendlang']['backendlang']['Inactive']) ? $data['backendlang']['backendlang']['Inactive'] :'' }}</option>
				</select>
			</div>
		</div>
	</div>

	<div class="form-group">
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group">
					 {{ isset($data['backendlang']['backendlang']['Item_Per_Page']) ? $data['backendlang']['backendlang']['Item_Per_Page'] :'' }}: <br>
					<select class="input-small" name="per_page">
						<option {{ (!empty(request('per_page')) && request('per_page') == '10') ? 'selected' : '' }} value="10">10</option>
						<option {{ (!empty(request('per_page')) && request('per_page') == '20') ? 'selected' : '' }} value="20">20</option>
						<option {{ (!empty(request('per_page')) && request('per_page') == '50') ? 'selected' : '' }} value="50">50</option>
					</select>
				</div>
			</div>
			<div class="col-sm-12">
				<div class="form-group">
					<button class="btn btn-outline-primary btn-sm">
						<i class="bi bi-search"></i>  {{ isset($data['backendlang']['backendlang']['Search']) ? $data['backendlang']['backendlang']['Search'] :'' }}
					</button>
					<a href="{{ route('flash_sale.flash_sales.index') }}" class="btn btn-warning btn-sm">
						<i class="bi bi-arrow-clockwise"></i>  {{ isset($data['backendlang']['backendlang']['Clear_Search']) ? $data['backendlang']['backendlang']['Clear_Search'] :'' }}
					</a>
				</div>
			</div>
		</div>
	</div>
</div>
</form>
<div class="container-box form-group">
	@if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['flash-sale-insert']))
	<div class="form-group" align="right">
		<a href="{{ route('flash_sale.flash_sales.create') }}" class="btn btn-outline-success btn-sm">
			<i class="bi bi-plus"></i> {{ isset($data['backendlang']['backendlang']['Add_New_Flash_Sales']) ? $data['backendlang']['backendlang']['Add_New_Flash_Sales'] :'' }}
		</a>
	</div>
	@endif
	<div class="row">
		<div class="col-12">
			<table class="table table-bordered">
				<thead>
					<tr class="info">
						<th>#</th>
						<th>{{ isset($data['backendlang']['backendlang']['Title']) ? $data['backendlang']['backendlang']['Title'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Start']) ? $data['backendlang']['backendlang']['Start'] :'' }}
							@if(empty(request('start_desc')) && empty(request('start_asc')))
								<a href="{{ route('flash_sale.flash_sales.index', ['start_desc=DESC']) }}" 
								class="{{ !empty(request('start_desc')) ? 'selected' : '' }}">
									<i class="bi bi-sort-down"></i>
									<input type="hidden" name="sort_data" value="0">
								</a>
							@else
								@if(!empty(request('start_desc')))
									<a href="{{ route('flash_sale.flash_sales.index', ['start_asc=ASC']) }}" 
									class="{{ !empty(request('start_asc')) ? 'selected' : '' }}">
										<i class="bi bi-sort-down"></i>
										<input type="hidden" name="sort_data" value="1">
									</a>
								@elseif(!empty(request('start_asc')))
									<a href="{{ route('flash_sale.flash_sales.index', ['start_desc=DESC']) }}" 
									class="{{ !empty(request('start_desc')) ? 'selected' : '' }}">
										<i class="bi bi-sort-up"></i>
										<input type="hidden" name="sort_data" value="0">
									</a>
								@endif
							@endif
						</th>
						<th>{{ isset($data['backendlang']['backendlang']['End']) ? $data['backendlang']['backendlang']['End'] :'' }}
							@if(empty(request('end_desc')) && empty(request('end_asc')))
								<a href="{{ route('flash_sale.flash_sales.index', ['end_desc=DESC']) }}" 
								class="{{ !empty(request('end_desc')) ? 'selected' : '' }}">
									<i class="bi bi-sort-down"></i>
									<input type="hidden" name="sort_data" value="0">
								</a>
							@else
								@if(!empty(request('end_desc')))
									<a href="{{ route('flash_sale.flash_sales.index', ['end_asc=ASC']) }}" 
									class="{{ !empty(request('end_asc')) ? 'selected' : '' }}">
										<i class="bi bi-sort-down"></i>
										<input type="hidden" name="sort_data" value="1">
									</a>
								@elseif(!empty(request('end_asc')))
									<a href="{{ route('flash_sale.flash_sales.index', ['end_desc=DESC']) }}" 
									class="{{ !empty(request('end_desc')) ? 'selected' : '' }}">
										<i class="bi bi-sort-up"></i>
										<input type="hidden" name="sort_data" value="0">
									</a>
								@endif
							@endif
						</th>
						<th>{{ isset($data['backendlang']['backendlang']['Status']) ? $data['backendlang']['backendlang']['Status'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Action']) ? $data['backendlang']['backendlang']['Action'] :'' }}</th>
					</tr>
				</thead>
				<tbody>
					@if (!$flash_sales->isEmpty())
					@foreach($flash_sales as $key => $flash_sale)
					<tr>
						<td>
							{{ $key+1 }}
							<input type="hidden" class="row_id" value="{{ $flash_sale->id }}">
						</td>
						<td>
							{{ $flash_sale->title }}
						</td>
						<td>
							{{ $flash_sale->start }}
						</td>
						<td>
							{{ $flash_sale->end }}
						</td>
						<td>
							@if ($flash_sale->status == 1)
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
							@if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['flash-sale-edit']))
								<a href="{{ route('flash_sale.flash_sales.edit', $flash_sale->id) }}">
									<i class="ace-icon fa fa-pencil bigger-130"></i> {{ isset($data['backendlang']['backendlang']['Edit']) ? $data['backendlang']['backendlang']['Edit'] :'' }}
								</a>
								&nbsp;&nbsp;
								@if($flash_sale->status == 1)
								<a href="#" class="red change-status" data-id="2">
									<i class="ace-icon fa fa-ban bigger-130"></i> {{ isset($data['backendlang']['backendlang']['Inactive']) ? $data['backendlang']['backendlang']['Inactive'] :'' }}
								</a>
								@else
								<a href="#" class="green change-status" data-id="1">
									<i class="ace-icon fa fa-check bigger-130"></i> {{ isset($data['backendlang']['backendlang']['Reactive']) ? $data['backendlang']['backendlang']['Reactive'] :'' }}
								</a>
								@endif
								&nbsp;&nbsp;
							@endif
							@if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['flash-sale-delete']))
								<a href="#" class="red change-status" data-id="3">
									<i class="ace-icon fa fa-trash-o bigger-130"></i> {{ isset($data['backendlang']['backendlang']['Delete']) ? $data['backendlang']['backendlang']['Delete'] :'' }}
								</a>
							@endif
						</td>
					</tr>
					@endforeach
					@else
					<tr>
						<td colspan="8">{{ isset($data['backendlang']['backendlang']['No_Result_Found']) ? $data['backendlang']['backendlang']['No_Result_Found'] :'' }}</td>
					</tr>
					@endif
				</tbody>
			</table>
			{{ $flash_sales->links() }}
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
	           url: '{{ route("FlashSaleStatus") }}',
	           type: 'post',
	           data: fd,
	           contentType: false,
	           processData: false,
	           success: function(response){
	                $('.loading-gif').hide();
	                // toastr.success('Status Changed');
	                // window.location.href="{{ route('flash_sale.flash_sales.index') }}";

					if (response == 'ok') {
						toastr.success("{{ isset($data['backendlang']['backendlang']['Status_Changed']) ? $data['backendlang']['backendlang']['Status_Changed'] :'' }}");
						location.reload();
					} else {
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