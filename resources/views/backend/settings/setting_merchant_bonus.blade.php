@extends('layouts.admin_app')
@section('content')
@if(!$levels->isEmpty())
<form method="POST" action="{{ route('setting_merchant_bonus') }}" id="setting-merchant-form">
@csrf
	<h5>{{ isset($data['backendlang']['backendlang']['Set_the_cumulative_number_of_agent_recommended']) ? $data['backendlang']['backendlang']['Set_the_cumulative_number_of_agent_recommended'] :'' }}</h5>
	<h5 class="important-text">{{ isset($data['backendlang']['backendlang']['Attachment: Set the number of agents downline and the rebate (Agent who hit the target will be qualified to get extra rebate)']) ? $data['backendlang']['backendlang']['Attachment: Set the number of agents downline and the rebate (Agent who hit the target will be qualified to get extra rebate)'] :'' }}</h5>
	<hr>
	<div class="row">
		<div class="col-sm-6">
			@foreach($levels as $level)
			<div class="form-group">
				<div class="row">
					<div class="col-sm-12">
						<h3>{{ $level->agent_lvl }}</h3>
						<br>				
						<div class="container-box">
							
							<div class="form-group">
								<div class="row">
									<div class="col-4">
										 {{ isset($data['backendlang']['backendlang']['Cumulative_setting']) ? $data['backendlang']['backendlang']['Cumulative_setting'] :'' }}
									</div>
									<div class="col-4">
										 {{ isset($data['backendlang']['backendlang']['Number_of_agents_More_than_equal']) ? $data['backendlang']['backendlang']['Number_of_agents_More_than_equal'] :'' }}
									</div>
									<div class="col-4">
										{{ isset($data['backendlang']['backendlang']['Amount']) ? $data['backendlang']['backendlang']['Amount'] :'' }}		
									</div>
								</div>
							</div>
							<hr>
							<div class="parent-box">
								@foreach($selects as $select)
									@if($select->agent_lvl == $level->id)
									<input type="hidden" name="lvl[]" class="agent_lvl" value="{{ $level->id }}">			
									<div class="form-group child-box">
										<div class="row">
											<div class="col-3">
												<select class="form-control" name="type[]">
													<option {{ $select->type == '1' ? 'selected' : '' }} value="1">{{ isset($data['backendlang']['backendlang']['Cumulative_number']) ? $data['backendlang']['backendlang']['Cumulative_number'] :'' }}</option>
													<option {{ $select->type == '2' ? 'selected' : '' }} value="2">{{ isset($data['backendlang']['backendlang']['Monthly']) ? $data['backendlang']['backendlang']['Monthly'] :'' }}</option>
													<option {{ $select->type == '3' ? 'selected' : '' }} value="3">{{ isset($data['backendlang']['backendlang']['Weekly']) ? $data['backendlang']['backendlang']['Weekly'] :'' }}</option>
													<option {{ $select->type == '4' ? 'selected' : '' }} value="4">{{ isset($data['backendlang']['backendlang']['Yearly']) ? $data['backendlang']['backendlang']['Yearly'] :'' }}</option>
												</select>
											</div>
											<div class="col-3">
												<input type="hidden" name="sid[]" value="{{ $select->id }}">
												<input type="text" class="form-control" name="qty[]" placeholder="{{ isset($data['backendlang']['backendlang']['Number_of_agents']) ? $data['backendlang']['backendlang']['Number_of_agents'] :'' }}" value="{{ isset($select) ? $select->qty : '' }}" onkeypress="return isNumberKey(event)">
											</div>
											<div class="col-5">
												<input type="text" class="form-control" name="amount[]" placeholder="{{ isset($data['backendlang']['backendlang']['Amount']) ? $data['backendlang']['backendlang']['Amount'] :'' }}" value="{{ isset($select) ? $select->amount : '' }}" onkeypress="return isNumberKey(event)">
											</div>
											<div class="col-1">
												<a href="#"  class="important-text del" data-id="{{ $select->id }}">
													<i class="fa fa-trash fa-2x"></i>
												</a>
											</div>
										</div>
									<hr>
									</div>
									@endif
								@endforeach
								<input type="hidden" name="lvl[]" class="agent_lvl" value="{{ $level->id }}">
								<div class="form-group child-box">
									<div class="row">
										<div class="col-3">
											<select class="form-control" name="type[]">
												<option value="1">{{ isset($data['backendlang']['backendlang']['Cumulative_number']) ? $data['backendlang']['backendlang']['Cumulative_number'] :'' }}</option>
												<option value="2">{{ isset($data['backendlang']['backendlang']['Monthly']) ? $data['backendlang']['backendlang']['Monthly'] :'' }}</option>
												<option value="3">{{ isset($data['backendlang']['backendlang']['Weekly']) ? $data['backendlang']['backendlang']['Weekly'] :'' }}</option>
												<option value="4">{{ isset($data['backendlang']['backendlang']['Yearly']) ? $data['backendlang']['backendlang']['Yearly'] :'' }}</option>
											</select>
										</div>
										<div class="col-3">
											<input type="hidden" name="sid[]" value="">
											<input type="text" class="form-control" name="qty[]" placeholder="{{ isset($data['backendlang']['backendlang']['Number_of_agents']) ? $data['backendlang']['backendlang']['Number_of_agents'] :'' }}" onkeypress="return isNumberKey(event)">
										</div>
										<div class="col-5">
											<input type="text" class="form-control" name="amount[]" placeholder="{{ isset($data['backendlang']['backendlang']['Amount']) ? $data['backendlang']['backendlang']['Amount'] :'' }}" value="" onkeypress="return isNumberKey(event)">
										</div>
										<div class="col-1">
											<a href="#"  class="important-text del">
												<i class="fa fa-trash fa-2x"></i>
											</a>
										</div>
									</div>
								<hr>
								</div>
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
						</div>
					</div>
				</div>
			</div>
			@endforeach
		</div>

		<div class="col-sm-6">
			<div class="container-box">
				<h4>{{ isset($data['backendlang']['backendlang']['Set_the_cumulative_number_of_agent_recommended']) ? $data['backendlang']['backendlang']['Set_the_cumulative_number_of_agent_recommended'] :'' }} <i class="fa fa-long-arrow-right" aria-hidden="true"></i> {{ isset($data['backendlang']['backendlang']['How_to_set']) ? $data['backendlang']['backendlang']['How_to_set'] :'' }}?</h4>
				<hr>
				<h5>
					<b>{{ isset($data['backendlang']['backendlang']['Cumulative_setting']) ? $data['backendlang']['backendlang']['Cumulative_setting'] :'' }}</b>
				</h5>
				<hr>
				<ul style="list-style-type: none;">
					<li style="margin: 10px 0px; font-size: 14px;">
						<b>{{ isset($data['backendlang']['backendlang']['Cumulative_number']) ? $data['backendlang']['backendlang']['Cumulative_number'] :'' }}</b> - {{ isset($data['backendlang']['backendlang']['Calculate_Total_Cumulative_Agents']) ? $data['backendlang']['backendlang']['Calculate_Total_Cumulative_Agents'] :'' }} <br>
						{!! isset($data['backendlang']['backendlang']['Agent_Recruit_Accumulation_Example']) ? $data['backendlang']['backendlang']['Agent_Recruit_Accumulation_Example'] :'' !!}
					
					</li>

					<li style="margin: 10px 0px; font-size: 14px;">
						<b>{{ isset($data['backendlang']['backendlang']['Monthly']) ? $data['backendlang']['backendlang']['Monthly'] :'' }}</b> / 
						<b>{{ isset($data['backendlang']['backendlang']['Weekly']) ? $data['backendlang']['backendlang']['Weekly'] :'' }}</b> / 
						<b>{{ isset($data['backendlang']['backendlang']['Yearly']) ? $data['backendlang']['backendlang']['Yearly'] :'' }}</b> 
						{{ isset($data['backendlang']['backendlang']['Agents_Period_Total_Headcount']) ? $data['backendlang']['backendlang']['Agents_Period_Total_Headcount'] :'' }} <br>
						{!! isset($data['backendlang']['backendlang']['Agent_Recruit_Example']) ? $data['backendlang']['backendlang']['Agent_Recruit_Example'] :'' !!} <br>
								  <b>{{ isset($data['backendlang']['backendlang']['Monthly']) ? $data['backendlang']['backendlang']['Monthly'] :'' }}</b> / 
								  <b>{{ isset($data['backendlang']['backendlang']['Weekly']) ? $data['backendlang']['backendlang']['Weekly'] :'' }}</b> / 
								  <b>{{ isset($data['backendlang']['backendlang']['Yearly']) ? $data['backendlang']['backendlang']['Yearly'] :'' }}</b>, {{ isset($data['backendlang']['backendlang']['Agent_Cumulative_Reset']) ? $data['backendlang']['backendlang']['Agent_Cumulative_Reset'] :'' }}
					</li>
				</ul>
				<hr>
				<h5>
					<b>{{ isset($data['backendlang']['backendlang']['Number_of_agents']) ? $data['backendlang']['backendlang']['Number_of_agents'] :'' }}</b>
				<hr>
				<ul style="list-style-type: none;">
					<li style="margin: 10px 0px; font-size: 14px;">
						{{ isset($data['backendlang']['backendlang']['Agent_Rebate_Requirement']) ? $data['backendlang']['backendlang']['Agent_Rebate_Requirement'] :'' }} <br>
						{{ isset($data['backendlang']['backendlang']['Rebate_Column_Example']) ? $data['backendlang']['backendlang']['Rebate_Column_Example'] :'' }}
					</li>
				</ul>
				<hr>
				<h5>
					<b>{{ isset($data['backendlang']['backendlang']['Amount']) ? $data['backendlang']['backendlang']['Amount'] :'' }}</b>
				<hr>
				<ul style="list-style-type: none;">
					<li style="margin: 10px 0px; font-size: 14px;">
						{{ isset($data['backendlang']['backendlang']['Agent_Rebate_Condition']) ? $data['backendlang']['backendlang']['Agent_Rebate_Condition'] :'' }}
					</li>
				</ul>
			</div>
		</div>
	</div>
	@else
		<h3>{{ isset($data['backendlang']['backendlang']['Agent_Level_Needed']) ? $data['backendlang']['backendlang']['Agent_Level_Needed'] :'' }}</h3>
		<p class="important-text">
			{{ isset($data['backendlang']['backendlang']['Please_go_to']) ? $data['backendlang']['backendlang']['Please_go_to'] :'' }} <b>{{ isset($data['backendlang']['backendlang']['Settings']) ? $data['backendlang']['backendlang']['Settings'] :'' }} <i class="fa fa-long-arrow-right" aria-hidden="true"></i> {{ isset($data['backendlang']['backendlang']['Agent_Level']) ? $data['backendlang']['backendlang']['Agent_Level'] :'' }}</b> {{ isset($data['backendlang']['backendlang']['For_add_Agent_Level_first']) ? $data['backendlang']['backendlang']['For_add_Agent_Level_first'] :'' }} </p>
	@endif
</form>
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
    	$('.loading-gif').show();
    	$('#setting-merchant-form').submit();
    });

    
    $('.add-row-btn').click(function (e){
    	e.preventDefault();
    	var lvl = $(this).closest('.col-sm-12').find('.agent_lvl').val();
    	$(this).closest('.col-sm-12').find('.container-box .parent-box').append('<input type="hidden" name="lvl[]" class="agent_lvl" value="'+lvl+'">\
															    					<div class="form-group child-box">\
																						<div class="row">\
																							<div class="col-3">\
																								<select class="form-control" name="type[]">\
																									<option value="1">{{ isset($data["backendlang"]["backendlang"]["Cumulative_number"]) ? $data["backendlang"]["backendlang"]["Cumulative_number"] :'' }}</option>\
																									<option value="2">{{ isset($data["backendlang"]["backendlang"]["Monthly"]) ? $data["backendlang"]["backendlang"]["Monthly"] :'' }}</option>\
																									<option value="3">{{ isset($data["backendlang"]["backendlang"]["Weekly"]) ? $data["backendlang"]["backendlang"]["Weekly"] :'' }}</option>\
																									<option value="4">{{ isset($data["backendlang"]["backendlang"]["Yearly"]) ? $data["backendlang"]["backendlang"]["Yearly"] :'' }}</option>\
																								</select>\
																							</div>\
																							<div class="col-3">\
																								<input type="hidden" name="sid[]" value="">\
																								<input type="text" class="form-control" name="qty[]" placeholder="{{ isset($data["backendlang"]["backendlang"]["Number_of_agents"]) ? $data["backendlang"]["backendlang"]["Number_of_agents"] :'' }}" onkeypress="return isNumberKey(event)">\
																							</div>\
																							<div class="col-5">\
																								<input type="text" class="form-control" name="amount[]" placeholder="{{ isset($data["backendlang"]["backendlang"]["Amount"]) ? $data["backendlang"]["backendlang"]["Amount"] :'' }}" value="" onkeypress="return isNumberKey(event)">\
																							</div>\
																							<div class="col-1">\
																								<a href="#"  class="important-text del">\
																									<i class="fa fa-trash fa-2x"></i>\
																								</a>\
																							</div>\
																						</div>\
																						<hr>\
																					</div>');
    });

    $('.parent-box').on('click', '.del', function (e){
    	e.preventDefault();
    	
    	if($(this).data('id')){
	    	var fd = new FormData();
	        fd.append('id', $(this).data('id'));
	        if(confirm('{{ isset($data["backendlang"]["backendlang"]["Confirm_delete_this_row"]) ? $data["backendlang"]["backendlang"]["Confirm_delete_this_row"] :'' }}') == true){
	    		$.ajax({
		            url: '{{ route("deleteAgentBonus") }}',
		            type: 'post',
		            data: fd,
		            contentType: false,
		            processData: false,
		            success: function(response){
		                toastr.error("{{ isset($data['backendlang']['backendlang']['Row_Deleted']) ? $data['backendlang']['backendlang']['Row_Deleted'] :'' }}");
		            },
		        });	        	
	        }else{
	        	return false;
	        }
    	}

    	$(this).closest('.child-box.form-group').remove();
    });
</script>
@endsection