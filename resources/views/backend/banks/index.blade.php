@extends('layouts.admin_app')

@section('content')
<div class="page-header">
    <h1>
       {{ isset($data['backendlang']['backendlang']['Bank_List']) ? $data['backendlang']['backendlang']['Bank_List'] :'' }}
        <!-- <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
        </small> -->
    </h1>
</div>
<form action="{{ route('bank.banks.index') }}" method="GET">
<div class="row">
	<div class="col-sm-2">
		<div class="form-group">
			<input type="text" class="form-control" name="title" value="{{ !empty('title') && request('title') ? request('title') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Title']) ? $data['backendlang']['backendlang']['Search_Title'] :'' }}">
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
			<a href="{{ route('bank.banks.index') }}" class="btn btn-warning btn-sm">
				<i class="fa fa-refresh"></i> {{ isset($data['backendlang']['backendlang']['Clear_Search']) ? $data['backendlang']['backendlang']['Clear_Search'] :'' }}
			</a>
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
<div class="form-group">
	<div class="row">
		<div class="col-sm-4">
			<div class="form-group">
				<button class="btn btn-outline-primary btn-sm">
					<i class="fa fa-search"></i> {{ isset($data['backendlang']['backendlang']['Search']) ? $data['backendlang']['backendlang']['Search'] :'' }}
				</button>
				<a href="{{ route('bank.banks.index') }}" class="btn btn-warning btn-sm">
					<i class="fa fa-refresh"></i> {{ isset($data['backendlang']['backendlang']['Clear_Search']) ? $data['backendlang']['backendlang']['Clear_Search'] :'' }}
				</a>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-12">
		<table class="table table-bordered">
			<tr>
				<td>#</td>
				<td>{{ isset($data['backendlang']['backendlang']['Bank_Name']) ? $data['backendlang']['backendlang']['Bank_Name'] :'' }}
					<br>
					@if(empty(request('bank_name_desc')) && empty(request('bank_name_asc')))
						<a href="{{ route('bank.banks.index', ['bank_name_desc=DESC']) }}" 
						   class="{{ !empty(request('bank_name_desc')) ? 'selected' : '' }}">
							<i class="fa fa-sort"></i>
							<input type="hidden" name="sort_data" value="0">
						</a>
					@else
						@if(!empty(request('bank_name_desc')))
							<a href="{{ route('bank.banks.index', ['bank_name_asc=ASC']) }}" 
							   class="{{ !empty(request('bank_name_asc')) ? 'selected' : '' }}">
								<i class="fa fa-sort"></i>
								<input type="hidden" name="sort_data" value="1">
							</a>
						@elseif(!empty(request('bank_name_asc')))
							<a href="{{ route('bank.banks.index', ['bank_name_desc=DESC']) }}" 
							   class="{{ !empty(request('bank_name_desc')) ? 'selected' : '' }}">
								<i class="fa fa-sort"></i>
								<input type="hidden" name="sort_data" value="0">
							</a>
						@endif
					@endif
				</td>
				<td></td>
			</tr>
			@if(!$banks->isEmpty())
				@foreach($banks as $key => $bank)
				<tr>
					<td>{{ $key+1 }}</td>
					<td>
						<input type="hidden" class="row_id" name="bid" value="{{ $bank->id }}">
						{{ $bank->bank_name }}
					</td>
					<td>
						<a href="{{ route('bank.banks.edit', $bank->id) }}">
							<i class="ace-icon fa fa-pencil bigger-130"></i> {{ isset($data['backendlang']['backendlang']['Edit']) ? $data['backendlang']['backendlang']['Edit'] :'' }}
						</a>
						&nbsp;&nbsp;
						@if($bank->status == 1)
						<a href="#" class="red change-status" data-id="2">
							<i class="ace-icon fa fa-ban bigger-130"></i> {{ isset($data['backendlang']['backendlang']['Active']) ? $data['backendlang']['backendlang']['Active'] :'' }}
						</a>
						@else
						<a href="#" class="green change-status" data-id="1">
							<i class="ace-icon fa fa-check bigger-130"></i> {{ isset($data['backendlang']['backendlang']['Inactive']) ? $data['backendlang']['backendlang']['Inactive'] :'' }}
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
		</table>
		{{ $banks->links() }}
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
	           url: '{{ route("PaymentBankStatus") }}',
	           type: 'post',
	           data: fd,
	           contentType: false,
	           processData: false,
	           success: function(response){
	                $('.loading-gif').hide();
	                toastr.success("{{ isset($data['backendlang']['backendlang']['Status_Changed']) ? $data['backendlang']['backendlang']['Status_Changed'] :'' }}");
	                window.location.href="{{ route('bank.banks.index') }}";
	           },
	        });        	
        }else{
        	$('.loading-gif').hide();
        }
    });

    $('input[name="default_banks"]').click( function(){

		$('.loading-gif').show();

		var ele = $(this);
		var bid = ele.closest('tr').find('input[name="bid"]').val();
		var fd = new FormData();
		  	fd.append('bid', bid);

	  	$.ajax({
	        url: '{{ route("setAdminBankDefault") }}',
	        type: 'post',
	        data: fd,
	        contentType: false,
	        processData: false,
	        success: function(response){
	        	$('.loading-gif').hide(); 

        		toastr.success("{{ isset($data['backendlang']['backendlang']['Set_Default_Bank_Successfully']) ? $data['backendlang']['backendlang']['Set_Default_Bank_Successfully'] :'' }}");		        	
	        }
	    });
	});
</script>
@endsection