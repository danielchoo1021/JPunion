@extends('layouts.admin_app')

@section('content')
<div class="container-box form-group">
	<h4>{{ isset($data['backendlang']['backendlang']['Filter']) ? $data['backendlang']['backendlang']['Filter'] :'' }}</h4>
	<form action="{{ route('cart_link.cart_links.index') }}" method="GET">
		<div class="row">
			<div class="col-sm-2">
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
							<i class="bi bi-search"></i> {{ isset($data['backendlang']['backendlang']['Search']) ? $data['backendlang']['backendlang']['Search'] :'' }}
						</button>
						<a href="{{ route('cart_link.cart_links.index') }}" class="btn btn-warning btn-sm">
							<i class="bi bi-arrow-clockwise"></i> {{ isset($data['backendlang']['backendlang']['Clear_Search']) ? $data['backendlang']['backendlang']['Clear_Search'] :'' }}
						</a>
					</div>
				</div>
			</div>
		</div>	
	</form>
</div>

<div class="container-box form-group">
	<div class="row">
		<div class="col-12">
			<div class="form-group" align="right">
				<a href="{{ route('cart_link.cart_links.create') }}" class="btn btn-outline-success btn-sm">
					<i class="bi bi-plus"></i>  {{ isset($data['backendlang']['backendlang']['Add_New_Cart_Link']) ? $data['backendlang']['backendlang']['Add_New_Cart_Link'] :'' }}
				</a>
			</div>
			{{ $cart_links->links() }}
			<table class="table table-bordered">
				<thead>
					<tr class="info">
						<th>#</th>
						<th>{{ isset($data['backendlang']['backendlang']['Link']) ? $data['backendlang']['backendlang']['Link'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Created_By']) ? $data['backendlang']['backendlang']['Created_By'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Quantity']) ? $data['backendlang']['backendlang']['Quantity'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Status']) ? $data['backendlang']['backendlang']['Status'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Action']) ? $data['backendlang']['backendlang']['Action'] :'' }}</th>
					</tr>
				</thead>
				<tbody>
					@if (!$cart_links->isEmpty())
					@foreach($cart_links as $key => $cart_link)
					<tr>
						<td>
							{{ $key+1 }}
							<input type="hidden" class="row_id" value="{{ $cart_link->id }}">
						</td>
						<td>{{ $cart_link->unique_id }}</td>
						<td>
							@if(!empty($cart_link->user_id))
								{{ $cart_link->user_name }} ({{ $cart_link->user_id }})
							@else
								<i class="bi bi-minus"></i>
							@endif
						</td>
						<td>
							@if(!empty($cart_link->qty))
								{{ $remaining_count[$cart_link->id] }} / {{ $cart_link->qty }}
							@else
								<i class="bi bi-minus"></i>
							@endif
						</td>
						<td>
							@if ($cart_link->status == 1)
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
							<a href="#" class="copy-link btn btn-outline-warning btn-sm" data-link="{{ $cart_link->unique_id }}" title="{{ isset($data['backendlang']['backendlang']['Copy_Link']) ? $data['backendlang']['backendlang']['Copy_Link'] :'' }}">
								<i class="bi bi-link-45deg"></i>
							</a>
							&nbsp;&nbsp;
							<a href="{{ route('cart_link.cart_links.edit', $cart_link->id) }}" class="btn btn-outline-primary btn-sm" title="{{ isset($data['backendlang']['backendlang']['View_Edit']) ? $data['backendlang']['backendlang']['View_Edit'] :'' }}">
								<i class="ace-icon bi bi-pencil-square bigger-130"></i>
							</a>
							&nbsp;&nbsp;
							@if($cart_link->status == 1)
							<a href="#" class="red change-status btn btn-outline-danger btn-sm" data-id="2" title="{{ isset($data['backendlang']['backendlang']['Inactive']) ? $data['backendlang']['backendlang']['Inactive'] :'' }}">
								<i class="ace-icon bi bi-shield-fill-x bigger-130"></i>
							</a>
							@else
							<a href="#" class="green change-status btn btn-outline-success btn-sm" data-id="1" title="{{ isset($data['backendlang']['backendlang']['Reactive']) ? $data['backendlang']['backendlang']['Reactive'] :'' }}">
								<i class="ace-icon bi bi-shield-check bigger-130"></i>
							</a>
							@endif

							&nbsp;&nbsp;
							<a href="#" class="red change-status btn btn-outline-danger btn-sm" data-id="3" title="{{ isset($data['backendlang']['backendlang']['Delete']) ? $data['backendlang']['backendlang']['Delete'] :'' }}">
								<i class="ace-icon bi bi-trash bigger-130"></i>
							</a>
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
			{{ $cart_links->links() }}
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
	           url: '{{ route("CartLinkStatus") }}',
	           type: 'post',
	           data: fd,
	           contentType: false,
	           processData: false,
	           success: function(response){
	                $('.loading-gif').hide();
	                toastr.success("{{ isset($data['backendlang']['backendlang']['Status_Changed']) ? $data['backendlang']['backendlang']['Status_Changed'] :'' }}");
	                window.location.href="{{ route('cart_link.cart_links.index') }}";
	           },
	        });
	    }else{
	    	$('.loading-gif').hide();
	    }
    });

    $('.copy-link').click(function(e){
    	e.preventDefault();
    	var ele = $(this);

    	var tempInput = $("<input>");
    	tempInput.val(ele.data('link'));
    	$("body").append(tempInput);
    	tempInput.select();
    	document.execCommand("copy");
    	tempInput.remove();

		toastr.success("{{ isset($data['backendlang']['backendlang']['Copied']) ? $data['backendlang']['backendlang']['Copied'] :'' }}");
    });
</script>
@endsection