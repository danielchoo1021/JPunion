@extends('layouts.admin_app')

@section('content')
<form action="{{ route('packages_list') }}" method="GET">
	<div class="container-box form-group">
		<h4>{{ isset($data['backendlang']['backendlang']['Filter']) ? $data['backendlang']['backendlang']['Filter'] :''}}</h4>
		<div class="row">
			<div class="col-sm-2">
				<div class="form-group">
					<input type="text" class="form-control" name="product_name" value="{{ !empty('product_name') && request('product_name') ? request('product_name') : '' }}" placeholder='{{ isset($data['backendlang']['backendlang']['Search_Package']) ? $data['backendlang']['backendlang']['Search_Package'] :''}}'>
				</div>
			</div>

			<div class="col-sm-2">
				<div class="form-group">
					<select class="form-control" name="status">
						<option value="">{{ isset($data['backendlang']['backendlang']['Select_Status']) ? $data['backendlang']['backendlang']['Select_Status'] :''}}</option>
						<option {{ (!empty(request('status')) && request('status') == '1') ? 'selected' : '' }} value="1">
							{{ isset($data['backendlang']['backendlang']['Active']) ? $data['backendlang']['backendlang']['Active'] :''}}
						</option>
						<option {{ (!empty(request('status')) && request('status') == '2') ? 'selected' : '' }} value="2">
							{{ isset($data['backendlang']['backendlang']['Inactive']) ? $data['backendlang']['backendlang']['Inactive'] :''}}
						</option>
					</select>
				</div>
			</div>
		</div>
		<div class="form-group">
			<div class="row">
				<div class="col-sm-2">
					<div class="form-group">
						{{ isset($data['backendlang']['backendlang']['Item_Per_Page']) ? $data['backendlang']['backendlang']['Item_Per_Page'] :''}}: <br>
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
							<i class="bi bi-search"></i> {{ isset($data['backendlang']['backendlang']['Search']) ? $data['backendlang']['backendlang']['Search'] :''}}
						</button>
						<a href="{{ route('packages_list') }}" class="btn btn-warning btn-sm">
							<i class="bi bi-arrow-clockwise"></i>
							{{ isset($data['backendlang']['backendlang']['Clear_Search']) ? $data['backendlang']['backendlang']['Clear_Search'] :''}}
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>
<div class="container-box">
	<div class="form-group" align="right">
		@if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['package-insert']))
		<a href="{{ route('packages_add') }}" class="btn btn-outline-success btn-sm">
			<i class="bi bi-plus"></i> {{ isset($data['backendlang']['backendlang']['Add_New_Packages']) ? $data['backendlang']['backendlang']['Add_New_Packages'] :''}}
		</a>
		@endif
	</div>
	<div class="row">
		<div class="col-12">
			{{ $products->links() }}
			<table class="table table-bordered">
				<thead>
					<tr class="info">
						<th>#</th>
						<th>{{ isset($data['backendlang']['backendlang']['Packages_Name']) ? $data['backendlang']['backendlang']['Packages_Name'] :''}}
							@if(empty(request('product_name_desc')) && empty(request('product_name_asc')))
							<a href="{{ route('packages_list', ['product_name_desc=DESC']) }}"
								class="{{ !empty(request('product_name_desc')) ? 'selected' : '' }}">
								<i class="bi bi-sort-down"></i>
								<input type="hidden" name="sort_data" value="0">
							</a>
							@else
							@if(!empty(request('product_name_desc')))
							<a href="{{ route('packages_list', ['product_name_asc=ASC']) }}"
								class="{{ !empty(request('product_name_asc')) ? 'selected' : '' }}">
								<i class="bi bi-sort-down"></i>
								<input type="hidden" name="sort_data" value="1">
							</a>
							@elseif(!empty(request('product_name_asc')))
							<a href="{{ route('packages_list', ['product_name_desc=DESC']) }}"
								class="{{ !empty(request('product_name_desc')) ? 'selected' : '' }}">
								<i class="bi bi-sort-up"></i>
								<input type="hidden" name="sort_data" value="0">
							</a>
							@endif
							@endif
						</th>
						<th>
							{{ isset($data['backendlang']['backendlang']['Item_Code']) ? $data['backendlang']['backendlang']['Item_Code'] :''}}
							@if(empty(request('product_code_desc')) && empty(request('product_code_asc')))
							<a href="{{ route('packages_list', ['product_code_desc=DESC']) }}"
								class="{{ !empty(request('product_code_desc')) ? 'selected' : '' }}">
								<i class="bi bi-sort-down"></i>
								<input type="hidden" name="sort_data" value="0">
							</a>
							@else
							@if(!empty(request('product_code_desc')))
							<a href="{{ route('packages_list', ['product_code_asc=ASC']) }}"
								class="{{ !empty(request('product_code_asc')) ? 'selected' : '' }}">
								<i class="bi bi-sort-down"></i>
								<input type="hidden" name="sort_data" value="1">
							</a>
							@elseif(!empty(request('product_code_asc')))
							<a href="{{ route('packages_list', ['product_code_desc=DESC']) }}"
								class="{{ !empty(request('product_code_desc')) ? 'selected' : '' }}">
								<i class="bi bi-sort-up"></i>
								<input type="hidden" name="sort_data" value="0">
							</a>
							@endif
							@endif
						</th>
						<th>{{ isset($data['backendlang']['backendlang']['Birthday_Promotion']) ? $data['backendlang']['backendlang']['Birthday_Promotion'] :''}}</th>
						<th>
							{{ isset($data['backendlang']['backendlang']['Sort']) ? $data['backendlang']['backendlang']['Sort'] :''}}
						</th>
						<th>
							{{ isset($data['backendlang']['backendlang']['Status']) ? $data['backendlang']['backendlang']['Status'] :''}}
							@if(empty(request('product_status_desc')) && empty(request('product_status_asc')))
							<a href="{{ route('packages_list', ['product_status_desc=DESC']) }}"
								class="{{ !empty(request('product_status_desc')) ? 'selected' : '' }}">
								<i class="bi bi-sort-down"></i>
								<input type="hidden" name="sort_data" value="0">
							</a>
							@else
							@if(!empty(request('product_status_desc')))
							<a href="{{ route('packages_list', ['product_status_asc=ASC']) }}"
								class="{{ !empty(request('product_status_asc')) ? 'selected' : '' }}">
								<i class="bi bi-sort-down"></i>
								<input type="hidden" name="sort_data" value="1">
							</a>
							@elseif(!empty(request('product_status_asc')))
							<a href="{{ route('packages_list', ['product_status_desc=DESC']) }}"
								class="{{ !empty(request('product_status_desc')) ? 'selected' : '' }}">
								<i class="bi bi-sort-up"></i>
								<input type="hidden" name="sort_data" value="0">
							</a>
							@endif
							@endif
						</th>
						<th>
							{{ isset($data['backendlang']['backendlang']['Action']) ? $data['backendlang']['backendlang']['Action'] :''}}
						</th>
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
						<td>{{ $product->item_code }}</td>
						<td><input type="checkbox" class="birthday_promotion" value="{{ $product->id }}" {{ ($product->birthday_promotion == 1) ? 'checked' : '' }}></td>
						<td>
							<input type="text" class="form-control sorting-value" value="{{ $product->sorting }}" onkeypress="return isNumberKey(event)" style="width: 50px">
						</td>
						<td class="status">
							@if($product->status == 1)
							<span class="badge bg-success">
								{{ isset($data['backendlang']['backendlang']['Active']) ? $data['backendlang']['backendlang']['Active'] :''}}
							</span>
							@else
							<span class="badge bg-danger">
								{{ isset($data['backendlang']['backendlang']['Inactive']) ? $data['backendlang']['backendlang']['Inactive'] :''}}
							</span>
							@endif

						</td>
						<td>
							@if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['package-edit']))
							<div class="btn-group">
								<button type="button" class="btn btn-outline-warning btn-sm  dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
									<i class="bi bi-justify"></i> <span class="caret"></span>
								</button>

								<ul class="dropdown-menu dropdown-menu-end" style="width:100px" role="menu">
									<li>
										<a href="{{ route('packages_edit', $product->id) }}">
											<i class="ace-icon bi bi-pencil bigger-130"></i> {{ isset($data['backendlang']['backendlang']['View_Edit']) ? $data['backendlang']['backendlang']['View_Edit'] :''}}
										</a>
									</li>
									<li>
										@if($product->status == 1)
										<a href="#" class="red action-button" style="color:red" data-id="2">
											<i class="ace-icon bi bi-shield-fill-x bigger-130"></i> {{ isset($data['backendlang']['backendlang']['Inactive']) ? $data['backendlang']['backendlang']['Inactive'] :''}}
										</a>
										@else
										<a href="#" class="green action-button" style="color:green" data-id="1">
											<i class="ace-icon bi bi-shield-check bigger-130"></i> {{ isset($data['backendlang']['backendlang']['Reactive']) ? $data['backendlang']['backendlang']['Reactive'] :''}}
										</a>
										@endif
									</li>
									@if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['product-delete']))
									<li>
										<a href="#" class="red action-button" style="color:darkred" data-id="3">
											<i class="ace-icon bi bi-trash bigger-130"></i> {{ isset($data['backendlang']['backendlang']['Delete']) ? $data['backendlang']['backendlang']['Delete'] :''}}
									</li>
									@endif
								</ul>
							</div>
							@endif
							<!-- @if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['package-edit']))
								<a href="{{ route('packages_edit', $product->id) }}"
								   class="btn btn-outline-primary btn-sm" title="View / Edit">
									<i class="ace-icon bi bi-pencil bigger-130"></i>
								</a>
								&nbsp;&nbsp;
								@if($product->status == 1)
									<a href="#" class="action-button btn btn-outline-danger btn-sm" data-id="2" title="Inactive">
										<i class="ace-icon bi bi-shield-fill-x bigger-130"></i>
									</a>
								@else
									<a href="#" class="action-button btn btn-outline-success btn-sm" data-id="1" title="Reactive">
										<i class="ace-icon bi bi-shield-check bigger-130"></i>
									</a>
								@endif
								&nbsp;&nbsp;
							@endif
							@if(!empty($data['permission']['permission'][Auth::guard($data['userGuardRole'])->user()->permission_lvl]['package-delete']))
								<a href="#" class="action-button red btn btn-outline-danger btn-sm" data-id="3" title="Delete">
									<i class="ace-icon bi bi-trash bigger-130"></i>
								</a>
							@endif -->
						</td>
					</tr>
					@endforeach
					@else
					<tr>
						<td colspan="5">{{ isset($data['backendlang']['backendlang']['No_Result_Found']) ? $data['backendlang']['backendlang']['No_Result_Found'] :''}}</td>
					</tr>
					@endif
				</tbody>
			</table>
			{{ $products->links() }}
		</div>
	</div>
</div>
@endsection

@section('js')
<script type="text/javascript">
	$('.action-button').click(function(e) {
		e.preventDefault();
		var ele = $(this);
		var row_id = ele.closest('tr').find('.row_id').val();

		var fd = new FormData();
		fd.append('row_id', row_id);
		fd.append('status', $(this).data('id'));

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
				url: '{{ route("ProductStatus") }}',
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
					//window.location.href="{{ route('product.products.index') }}";
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

	$('.birthday_promotion').click(function() {
		$('.loading-gif').show();
		var ele = $(this);

		var fd = new FormData();
		fd.append('id', ele.val());

		$.ajax({
			url: '{{ route("setBirthdayPromotion") }}',
			type: 'post',
			data: fd,
			contentType: false,
			processData: false,
			success: function(response) {
				$('.loading-gif').hide();
				toastr.success("{{ isset($data['backendlang']['backendlang']['Updated']) ? $data['backendlang']['backendlang']['Updated'] :'' }}");
				location.reload();
			},
		});
	});


	$('.sorting-value').change(function() {
		var ele = $(this);
		var id = $(this).closest('tr').find('.row_id').val();

		var fd = new FormData();
		fd.append('sorting', $(this).val());
		fd.append('id', id);

		$.ajax({
			url: '{{ route("sortingProduct") }}',
			type: 'post',
			data: fd,
			contentType: false,
			processData: false,
			success: function(response) {
				if (response == 1) {
					alert("{{ isset($data['backendlang']['backendlang']['This arrangement number already exists']) ? $data['backendlang']['backendlang']['This arrangement number already exists'] :'' }}");
					ele.val(" ");
					return false;
				} else {
					toastr.success("{{ isset($data['backendlang']['backendlang']['Arrangement successful']) ? $data['backendlang']['backendlang']['Arrangement successful'] :'' }}")
				}
			},
		});
	});
</script>
@endsection