@extends('layouts.admin_app')

@section('content')
<form action="{{ route('point_mall.point_malls.index') }}" method="GET">
<div class="row">
	<div class="col-sm-2">
		<div class="form-group">
			<input type="text" class="form-control" name="product_name" value="{{ !empty('product_name') && request('product_name') ? request('product_name') : '' }}" placeholder="{{ (!empty(request('status')) && request('status') == '1') ? 'selected' : '' }} value="1">{{ isset($data['backendlang']['backendlang']['Search_Product_Name']) ? $data['backendlang']['backendlang']['Search_Product_Name'] :'' }}">
		</div>
	</div>

	<div class="col-sm-2">
		<div class="form-group">
			<select class="form-control" name="status">
				<option value="">{{ isset($data['backendlang']['backendlang']['Select_Status']) ? $data['backendlang']['backendlang']['Select_Status'] :'' }}</option>
				<option {{ (!empty(request('status')) && request('status') == '1') ? 'selected' : '' }} value="1">{{ isset($data['backendlang']['backendlang']['Active']) ? $data['backendlang']['backendlang']['Active'] :'' }}</option>
				<option {{ (!empty(request('status')) && request('status') == '2') ? 'selected' : '' }} value="2">{{ isset($data['backendlang']['backendlang']['Inactive']) ? $data['backendlang']['backendlang']['Inactive'] :'' }}</option>
			</select>
		</div>
	</div>

	<div class="col-sm-4">
		<div class="form-group">
			<button class="btn btn-outline-primary btn-sm">
				<i class="fa fa-search"></i> {{ isset($data['backendlang']['backendlang']['Search']) ? $data['backendlang']['backendlang']['Search'] :'' }}
			</button>
			<a href="{{ route('point_mall.point_malls.index') }}" class="btn btn-warning btn-sm">
				<i class="fa fa-refresh"></i> {{ isset($data['backendlang']['backendlang']['Clear_Search']) ? $data['backendlang']['backendlang']['Clear_Search'] :'' }}
		</div>
	</div>
</div>

<div class="form-group">
	<div class="row">
		<div class="col-sm-2">
			<div class="form-group">
				{{ isset($data['backendlang']['backendlang']['Item_Per_Page']) ? $data['backendlang']['backendlang']['Item_Per_Page'] :'' }}: <br>
				<select class="input-small" name="per_page">
					<option value="10">10</option>
					<option value="20">20</option>
					<option value="50">50</option>
				</select>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-12">
		<table class="table table-bordered">
			<thead>
				<tr class="info">
					<th>#</th>
					<th>{{ isset($data['backendlang']['backendlang']['Name']) ? $data['backendlang']['backendlang']['Name'] :'' }}</th>
					<th>{{ isset($data['backendlang']['backendlang']['Quantity']) ? $data['backendlang']['backendlang']['Quantity'] :'' }}</th>
					<th>{{ isset($data['backendlang']['backendlang']['Status']) ? $data['backendlang']['backendlang']['Status'] :'' }}</th>
					<th>{{ isset($data['backendlang']['backendlang']['Action']) ? $data['backendlang']['backendlang']['Action'] :'' }}</th>
				</tr>
			</thead>
			<tbody>
				@if (!$products->isEmpty())
				@foreach($products as $key => $product)
				<tr>
					<td>
						{{ $key+1 }}
						<input type="hidden" class="row_id" value="{{ $product->id }}">
					</td>
					<td>{{ $product->product_name }}</td>
					<td>{{ $quantity[$product->id] }}</td>
					<td>
							@if ($product->status == 1)
								<span class="badge bg-success">
									{{ $data['backendlang']['backendlang']['Active'] ? $data['backendlang']['backendlang']['Active'] : '' }}
								</span>
							@else
								<span class="badge bg-danger">
									{{ $data['backendlang']['backendlang']['Inactive'] ? $data['backendlang']['backendlang']['Inactive'] : '' }}
								</span>
							@endif
						</td>
					<td>
						<a href="{{ route('point_mall.point_malls.edit', $product->id) }}">
							<i class="ace-icon fa fa-pencil bigger-130"></i> {{ isset($data['backendlang']['backendlang']['Edit']) ? $data['backendlang']['backendlang']['Edit'] :'' }}
						</a>
						<!-- &nbsp;&nbsp;
						<a href="#" class="red">
							<i class="ace-icon fa fa-trash-o bigger-130"></i>
						</a> -->
						&nbsp;&nbsp;
						<a href="{{ route('stock', [$product->id]) }}" class="green">
							<i class="ace-icon fa fa-upload bigger-130"></i> {{ isset($data['backendlang']['backendlang']['Stock']) ? $data['backendlang']['backendlang']['Stock'] :'' }}
						</a>
						&nbsp;&nbsp;
						@if($product->status == 1)
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
					</td>
				</tr>
				@endforeach
				@else
				<tr>
					<td colspan="5">{{ isset($data['backendlang']['backendlang']['No_Result_Found']) ? $data['backendlang']['backendlang']['No_Result_Found'] :'' }}</td>
				</tr>
				@endif
			</tbody>
		</table>
		{{ $products->links() }}
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
	           url: '{{ route("ProductStatus") }}',
	           type: 'post',
	           data: fd,
	           contentType: false,
	           processData: false,
	           success: function(response){
	                $('.loading-gif').hide();
	                toastr.success("{{ isset($data['backendlang']['backendlang']['Status_Changed']) ? $data['backendlang']['backendlang']['Status_Changed'] :'' }}");
	                window.location.href="{{ route('point_mall.point_malls.index') }}";
	           },
	        });
	    }else{
	    	$('.loading-gif').hide();
	    }
    });
</script>
@endsection