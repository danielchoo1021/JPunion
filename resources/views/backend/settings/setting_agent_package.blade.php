@extends('layouts.admin_app')

@section('content')
<div class="page-header">
    <h1>
       {{ isset($data['backendlang']['backendlang']['Setting_Agent_Packages']) ? $data['backendlang']['backendlang']['Setting_Agent_Packages'] :'' }}
        <!-- <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
            @if(Auth::check())
            {{ Auth::user()->f_name }} 
            @endif
        </small> -->
    </h1>
</div>
<div class="row">
	<div class="col-sm-6 col-12">
		
		<form method="POST" action="{{ route('save_setting_agent_package') }}" id="setting-merchant-form">
			@csrf
			<div class="form-group">
				<div class="row">
					<div class="col-3">
						{{ isset($data['backendlang']['backendlang']['product']) ? $data['backendlang']['backendlang']['product'] :'' }}
					</div>
					<div class="col-2">
						{{ isset($data['backendlang']['backendlang']['Purchase_Item_More_Than_Equal']) ? $data['backendlang']['backendlang']['Purchase_Item_More_Than_Equal'] :'' }}
					</div>
					<div class="col-3">
						{{ isset($data['backendlang']['backendlang']['Amount']) ? $data['backendlang']['backendlang']['Amount'] :'' }}
					</div>
					<div class="col-3">
						{{ isset($data['backendlang']['backendlang']['Agent_Level_Name']) ? $data['backendlang']['backendlang']['Agent_Level_Name'] :'' }}
					</div>
				</div>
			</div>
			<hr>
			<div class="parent-box">
				@foreach($selects as $select)
				<div class="form-group child-box">
					<div class="row">
						<div class="col-3">
							<select class="form-control select2" name="product_id[]" data-live-search="true">
								@foreach($products as $product)
			                    <option value="{{ $product->id }}">{{ $product->product_name }}</option>
			                    @endforeach
			                </select>
						</div>
						<div class="col-2">
							<input type="hidden" name="sid[]" value="{{ $select->id }}">
							<input type="text" class="form-control" name="type[]" placeholder="{{ isset($data['backendlang']['backendlang']['More_Than']) ? $data['backendlang']['backendlang']['More_Than'] :'' }}" value="{{ $select->type }}">
						</div>
						<div class="col-3">
							<input type="text" class="form-control" name="amount[]" placeholder="{{ isset($data['backendlang']['backendlang']['Amount']) ? $data['backendlang']['backendlang']['Amount'] :'' }}" value="{{ $select->amount }}">
						</div>

						<div class="col-3">
							<input type="text" class="form-control" name="lvl[]" placeholder="{{ isset($data['backendlang']['backendlang']['Agent_Upgrade_Rank']) ? $data['backendlang']['backendlang']['Agent_Upgrade_Rank'] :'' }}" value="{{ $select->lvl }}">
						</div>

						<div class="col-1">
							<a href="#"  class="important-text del" data-id="{{ $select->id }}">
								<i class="fa fa-trash fa-2x"></i>
							</a>
						</div>
					</div>
				<hr>
				</div>
				@endforeach
				<div class="form-group child-box">
					<div class="row">
						<div class="col-3">
							<select class="form-control select2" name="product_id[]" data-live-search="true">
								@foreach($products as $product)
			                    <option value="{{ $product->id }}">{{ $product->product_name }}</option>
			                    @endforeach
			                </select>
						</div>
						<div class="col-2">
							<input type="hidden" name="sid[]" value="">
							<input type="text" class="form-control" name="type[]" placeholder="{{ isset($data['backendlang']['backendlang']['More_Than']) ? $data['backendlang']['backendlang']['More_Than'] :'' }}">
						</div>
						<div class="col-3">
							<input type="text" class="form-control" name="amount[]" placeholder="{{ isset($data['backendlang']['backendlang']['Amount']) ? $data['backendlang']['backendlang']['Amount'] :'' }}" value="">
						</div>
						<div class="col-3">
							<input type="text" class="form-control" name="lvl[]" placeholder="{{ isset($data['backendlang']['backendlang']['Agent_Upgrade_Rank']) ? $data['backendlang']['backendlang']['Agent_Upgrade_Rank'] :'' }}" value="">
						</div>
						<div class="col-1">
							<a href="#"  class="important-text del">
								<i class="fa fa-trash fa-2x"></i>
							</a>
						</div>
					</div>
				</div>
				<hr>
			</div>

			<div class="form-group ">
				<div class="row justify-content_center">
					<div class="col-md-11" align="center">
						<button class="add-row-btn" id="add-row-btn">
							<i class="fa fa-plus"></i>
						</button>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>

<div class="submit-form-btn">
	<div class="form-group wizard-actions" align="right">
		<button class="btn btn-outline-primary">
			<i class="fa fa-check"> {{ isset($data['backendlang']['backendlang']['Save_Changes']) ? $data['backendlang']['backendlang']['Save_Changes'] :'' }}</i>
		</button>

	</div>
</div>
@endsection
@section('js')
<script type="text/javascript">
	$('.submit-form-btn .btn-outline-primary').click( function(e){
    	e.preventDefault();
    	
    	$('#setting-merchant-form').submit();
    });

    var agent_rebate = '<div class="form-group child-box">\
							<div class="row">\
								<div class="col-3">\
									<select class="form-control select2" name="product_id[]" data-live-search="true">\
										@foreach($products as $product)\
					                    <option value="{{ $product->id }}">{{ $product->product_name }}</option>\
					                    @endforeach\
					                </select>\
								</div>\
								<div class="col-2">\
									<input type="hidden" name="sid[]" value="">\
									<input type="text" class="form-control" name="type[]" placeholder="{{ isset($data["backendlang"]["backendlang"]["More_Than"]) ? $data["backendlang"]["backendlang"]["More_Than"] :''}} ">\
								</div>\
								<div class="col-3">\
									<input type="text" class="form-control" name="amount[]" placeholder="{{ isset($data["backendlang"]["backendlang"]["Amount"]) ? $data["backendlang"]["backendlang"]["Amount"] :''}}" value="">\
								</div>\
								<div class="col-3">\
									<input type="text" class="form-control" name="amount[]" placeholder="{{ isset($data["backendlang"]["backendlang"]["Agent_Upgrade_Rank"]) ? $data["backendlang"]["backendlang"]["Agent_Upgrade_Rank"] :''}}" value="">\
								</div>\
								<div class="col-1">\
									<a href="#"  class="important-text del">\
										<i class="fa fa-trash fa-2x"></i>\
									</a>\
								</div>\
							</div>\
							<hr>\
						</div>';
    $('#add-row-btn').click(function (e){
    	e.preventDefault();
    	$('.parent-box').append(agent_rebate);
    });

    $('.parent-box').on('click', '.del', function (e){
    	e.preventDefault();
    	
    	if($(this).data('id')){
	    	var fd = new FormData();
	        fd.append('id', $(this).data('id'));
    		$.ajax({
	            url: '{{ route("deleteAgentPackages") }}',
	            type: 'post',
	            data: fd,
	            contentType: false,
	            processData: false,
	            success: function(response){
	                toastr.info("{{ isset($data['backendlang']['backendlang']['Row_Deleted']) ? $data['backendlang']['backendlang']['Row_Deleted'] :''}}");
	            },
	        });
    	}

    	$(this).closest('.child-box.form-group').remove();
    });

	    // $('.parent-box .child-box .select2').select2({
	    //     placeholder: "Select Refferal ID",
	    //     allowClear: true
	    // });
</script>
@endsection