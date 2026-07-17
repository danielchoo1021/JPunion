@extends('layouts.admin_app')
@section('content')
@if(!$levels->isEmpty())
<form method="POST" action="{{ route('save_setting_team_dividend') }}" id="setting-merchant-form">
@csrf
<div class="row">
	<div class="col-12">
		<div class="big-parent">
			<div class="form-group">
				<div class="row">
					<div class="col-6">
						<div class="form-group">
							<h5><b>{{ isset($data['backendlang']['backendlang']['Sales_Amount']) ? $data['backendlang']['backendlang']['Sales_Amount'] :'' }} (RM)</b></h5>
						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<h5><b>{{ isset($data['backendlang']['backendlang']['Rebate_Amount']) ? $data['backendlang']['backendlang']['Rebate_Amount'] :'' }}</b></h5>
						</div>
					</div>
				</div>
				<div class="child-div add-message-content">
					@foreach($selects as $select)
					<div class="form-group child-row messsage-del">
						<div class="row">
							<div class="col-5">
								<div class="row">
									<div class="col-1">
										<span class="badge bg-info mt-2 tier-level">
										</span>
									</div>
									<div class="col-11">
										<input type="text" name="point_target[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['Sales_Amount']) ? $data['backendlang']['backendlang']['Sales_Amount'] :'' }} (RM)" value="{{ $select->target_box }}">
										<input type="hidden" class="sid" name="sid[]" value="{{ $select->id }}">
									</div>
								</div>
							</div>
							<div class="col-5">
								<div class="row">
									<div class="col-6">
										<select class="form-control" name='type[]'>
											<option {{ ($select->type == 'Percentage') ? 'selected' : '' }} 
													value="Percentage">{{ isset($data['backendlang']['backendlang']['Percentage']) ? $data['backendlang']['backendlang']['Percentage'] :'' }}</option>
											<option {{ ($select->type == 'Amount') ? 'selected' : '' }} 
													value="Amount">{{ isset($data['backendlang']['backendlang']['Amount']) ? $data['backendlang']['backendlang']['Amount'] :'' }}</option>
										</select>
									</div>
									<div class="col-6">
										<input type="text" name="amount[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['e.g.']) ? $data['backendlang']['backendlang']['e.g.'] :'' }} 5" value="{{ $select->amount }}">
									</div>
								</div>
							</div>
							<div class="col-2" align="center">
								<a href="#"  class="important-text red del">
									<i class="bi bi-trash fa-2x"></i>
								</a>
							</div>
						</div>
					</div>
					@endforeach
					<div class="form-group child-row del-new-message">
						<div class="row">
							<div class="col-5">
								<div class="row">
									<div class="col-1">
										<span class="badge bg-info mt-2 tier-level">
										</span>
									</div>
									<div class="col-11">
										<input type="text" name="point_target[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['Sales_Amount']) ? $data['backendlang']['backendlang']['Sales_Amount'] :'' }} (RM)">
										<input type="hidden" name="sid[]">
									</div>
								</div>
							</div>
							<div class="col-5">
								<div class="row">
									<div class="col-6">
										<select class="form-control" name='type[]'>
											<option value="Percentage">{{ isset($data['backendlang']['backendlang']['Percentage']) ? $data['backendlang']['backendlang']['Percentage'] :'' }}</option>
											<option value="Amount">{{ isset($data['backendlang']['backendlang']['Amount']) ? $data['backendlang']['backendlang']['Amount'] :'' }}</option>
										</select>
									</div>
									<div class="col-6">
										<input type="text" name="amount[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['e.g.']) ? $data['backendlang']['backendlang']['e.g.'] :'' }} 5">
									</div>
								</div>
							</div>
							<div class="col-2" align="center">
								<a href="#"  class="important-text red del">
									<i class="bi bi-trash fa-2x"></i>
								</a>
							</div>
						</div>
					</div>
				</div>					
			</div>
			<hr>
			<div class="form-group">
				<div class="row">
					<div class="col-md-12" align="center">
						<button class="add-row-btn btn btn-primary btn-sm">
							<i class="bi bi-plus"></i>
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</form>



<div class="submit-form-btn">
	<div class="form-group wizard-actions" align="right">
		<button class="btn btn-outline-primary">
			<i class="bi bi-check"> {{ isset($data['backendlang']['backendlang']['Save_Changes']) ? $data['backendlang']['backendlang']['Save_Changes'] :'' }}</i>
		</button>

	</div>
</div>
@else
	<h3>{{ isset($data['backendlang']['backendlang']['Agent_Level_Needed']) ? $data['backendlang']['backendlang']['Agent_Level_Needed'] :'' }}</h3>
	<p class="important-text">
		{{ isset($data['backendlang']['backendlang']['Please_go_to']) ? $data['backendlang']['backendlang']['Please_go_to'] :'' }} <b>{{ isset($data['backendlang']['backendlang']['Settings']) ? $data['backendlang']['backendlang']['Settings'] :'' }} <i class="bi bi-long-arrow-right" aria-hidden="true"></i> {{ isset($data['backendlang']['backendlang']['Agent_Level']) ? $data['backendlang']['backendlang']['Agent_Level'] :'' }}</b> {{ isset($data['backendlang']['backendlang']['For_add_Agent_Level_first']) ? $data['backendlang']['backendlang']['For_add_Agent_Level_first'] :'' }} </p>
@endif
@endsection
@section('js')
<script type="text/javascript">
	$('.submit-form-btn .btn-outline-primary').click( function(e){
    	e.preventDefault();
    	$('.loading-gif').show();
    	$('#setting-merchant-form').submit();
    });
</script>

<script type="text/javascript">

    var add_new_row = '<div class="form-group child-row del-new-message">\
							<div class="row">\
								<div class="col-5">\
									<div class="row">\
										<div class="col-1">\
											<span class="badge bg-info mt-2 tier-level">\
											</span>\
										</div>\
										<div class="col-11">\
											<input type="text" name="point_target[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['Sales_Amount']) ? $data['backendlang']['backendlang']['Sales_Amount'] :'' }} (RM)">\
											<input type="hidden" name="sid[]">\
										</div>\
									</div>\
								</div>\
								<div class="col-5">\
									<div class="row">\
										<div class="col-6">\
											<select class="form-control" name="type[]">\
												<option value="Percentage">{{ isset($data['backendlang']['backendlang']['Percentage']) ? $data['backendlang']['backendlang']['Percentage'] :'' }}</option>\
												<option value="Amount">{{ isset($data['backendlang']['backendlang']['Amount']) ? $data['backendlang']['backendlang']['Amount'] :'' }}</option>\
											</select>\
										</div>\
										<div class="col-6">\
											<input type="text" name="amount[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['e.g.']) ? $data['backendlang']['backendlang']['e.g.'] :'' }} 5">\
										</div>\
									</div>\
								</div>\
								<div class="col-2" align="center">\
									<a href="#"  class="important-text red del">\
										<i class="bi bi-trash fa-2x"></i>\
									</a>\
								</div>\
							</div>\
						</div>';

    $('.add-row-btn').click(function(e){
    	e.preventDefault();
    	var ele = $(this);

    	ele.closest('.big-parent').find('.child-div').append(add_new_row);
    	load_tier();
    });

	$('.add-message-content').on('click', '.del', function(e) {
		e.preventDefault();

		var ele = $(this);
		var id = ele.closest('.messsage-del').find('.sid').val();

		if (id) {
			if (confirm('{{ isset($data['backendlang']['backendlang']['Delete_this_tier']) ? $data['backendlang']['backendlang']['Delete_this_tier'] :'' }}') == true) {
				var fd = new FormData();
				fd.append('id', id);

				$.ajax({
					url: '{{ route("DeleteTeamBonus") }}',
					type: 'post',
					data: fd,
					contentType: false,
					processData: false,
					success: function(response) {
						$('.loading-gif').hide();
						ele.closest('.messsage-del').remove();
					},
				});


			}
		} else {
			ele.closest('.del-new-message').remove();
		}
	});


    function load_tier()
    {
    	var num = 0;
    	$('.tier-level').each(function(){
    		num++;
    		$(this).html('{{ isset($data['backendlang']['backendlang']['Tier']) ? $data['backendlang']['backendlang']['Tier'] :''}} '+num);
    	});
    }
    load_tier();
</script>
@endsection