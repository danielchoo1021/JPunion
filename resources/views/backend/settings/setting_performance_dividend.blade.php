@extends('layouts.admin_app')
@section('content')
@if(!$levels->isEmpty())
<h2 class="important-text">
	{{ isset($data['backendlang']['backendlang']['Set_Monthly_Percentage_Total_Performance']) ? $data['backendlang']['backendlang']['Set_Monthly_Percentage_Total_Performance'] :'' }}
</h2>

<div class="form-group" align="right">
	<form method="POST" action="{{ route('run_setting_performance_dividend') }}" id="run-performance-form">
		@csrf
		<button type="button" class="btn btn-outline-primary run-performance-btn">
			<i class="fa fa-refresh"></i> Trigger This Month's Performance Reward Now
		</button>
	</form>
	<small class="text-muted d-block mt-1">Runs the same calculation as the automatic month-end job, for every active agent, right now. Safe to click again - agents already rewarded this month won't be paid twice.</small>
</div>

<form method="POST" action="{{ route('save_setting_performance_dividend') }}" id="setting-merchant-form">
@csrf
<hr>
<div class="row">
	@foreach($levels as $level)
		<div class="col-sm-4">
			
			<div class="form-group container-box">
				<span class="box form-group" style="background-color: {{ $level->level_colour }};">
					@php
						$langFlag = $_COOKIE['backend_global_language'] ?? ($_COOKIE['backend_global_language'] ?? '0');

							if($langFlag == 1){
								$agent_lvl = $level->agent_lvl_cn;
							}else{
								$agent_lvl = $level->agent_lvl;
							}
					@endphp
					<h2 align='center' style="color: white;" class="text">{{ $agent_lvl }}</h2>
				</span>
				<br>
				<input type="hidden" name="sid[]" value="{{ (!empty($selectDetails[$level->id][0])) ? $selectDetails[$level->id][0] : '' }}">
				<input type="hidden" name="lvl[]" value="{{ $level->id }}">
				<div class="form-group">
					<label>{{ isset($data['backendlang']['backendlang']['Target']) ? $data['backendlang']['backendlang']['Target'] :'' }} (RM)</label>
					<input type="text" class="form-control" name="target[]" placeholder="{{ isset($data['backendlang']['backendlang']['Target_Sales']) ? $data['backendlang']['backendlang']['Target_Sales'] :'' }}" 
						   value="{{ (!empty($selectDetails[$level->id][3])) ? $selectDetails[$level->id][3] : '' }}" onkeypress="return isNumberKey(event)">
				</div>
				<div class="form-group">
					<label>{{ isset($data['backendlang']['backendlang']['Percentage']) ? $data['backendlang']['backendlang']['Percentage'] :'' }} (%)</label>
					<input type="text" class="form-control" name="amount[]" placeholder="{{ isset($data['backendlang']['backendlang']['Amount']) ? $data['backendlang']['backendlang']['Amount'] :'' }}" 
						   value="{{ (!empty($selectDetails[$level->id][2])) ? $selectDetails[$level->id][2] : '' }}" onkeypress="return isNumberKey(event)">
				</div>
			</div>
		</div>
	@endforeach
</div>
</form>

<div class="submit-form-btn">
	<div class="form-group wizard-actions" align="right">
		<button class="btn btn-outline-primary">
			<i class="fa fa-check"> {{ isset($data['backendlang']['backendlang']['Save_Changes']) ? $data['backendlang']['backendlang']['Save_Changes'] :'' }}</i>
		</button>

	</div>
</div>

<hr>
<h2 class="important-text">Additional Tier %</h2>
<p class="text-muted">Extra % added on top of the agent's own level's Performance Reward % above, based on how many direct downline <b>agents</b> (not customers) they've recruited. Applies globally to every agent regardless of level, and only when the agent already qualifies for their own Performance Reward that month.</p>

<form method="POST" action="{{ route('save_setting_performance_tier') }}" id="setting-tier-form">
@csrf
<input type="hidden" name="deleted_tier_ids" id="deleted-tier-ids" value="">

<div class="tier-rows">
	@foreach($tiers as $index => $tier)
	<div class="form-group container-box tier-row" data-tier-id="{{ $tier->id }}">
		<div class="row">
			<div class="col-sm-3">
				<label>Tier</label>
				<div class="tier-label"><b>Tier {{ $index + 1 }}</b></div>
			</div>
			<div class="col-sm-3">
				<label>Direct Downline Agents (at least)</label>
				<input type="hidden" name="tier_id[]" value="{{ $tier->id }}">
				<input type="text" class="form-control" name="tier_target[]" value="{{ $tier->target }}" onkeypress="return isNumberKey(event)">
			</div>
			<div class="col-sm-3">
				<label>Additional % </label>
				<input type="text" class="form-control" name="tier_amount[]" value="{{ $tier->amount }}" onkeypress="return isNumberKey(event)">
			</div>
			<div class="col-sm-3" align="right">
				<label>&nbsp;</label>
				<button type="button" class="btn btn-outline-danger btn-block remove-tier-btn">
					<i class="fa fa-trash"></i> Remove
				</button>
			</div>
		</div>
	</div>
	@endforeach
</div>

<div class="form-group" align="center">
	<button type="button" class="btn btn-outline-secondary add-tier-btn" style="background:#f8f9fa; color:#333; border-color:#ccc;">
		<i class="fa fa-plus"></i> Add Tier
	</button>
</div>
</form>

<div class="tier-actions">
	<div class="form-group wizard-actions" align="right">
		<button type="button" class="btn btn-outline-primary save-tier-btn">
			<i class="fa fa-check"></i> Save Additional Tier %
		</button>
	</div>
</div>
@else
	<h3>{{ isset($data['backendlang']['backendlang']['Agent_Level_Needed']) ? $data['backendlang']['backendlang']['Agent_Level_Needed'] :'' }}</h3>
	<p class="important-text">
		{{ isset($data['backendlang']['backendlang']['Please_go_to']) ? $data['backendlang']['backendlang']['Please_go_to'] :'' }} <b>{{ isset($data['backendlang']['backendlang']['Settings']) ? $data['backendlang']['backendlang']['Settings'] :'' }} <i class="fa fa-long-arrow-right" aria-hidden="true"></i> {{ isset($data['backendlang']['backendlang']['Agent_Level']) ? $data['backendlang']['backendlang']['Agent_Level'] :'' }}</b> {{ isset($data['backendlang']['backendlang']['For_add_Agent_Level_first']) ? $data['backendlang']['backendlang']['For_add_Agent_Level_first'] :'' }} </p>
@endif
@endsection
@section('js')
<script type="text/javascript">
	$('.submit-form-btn .btn-outline-primary').click( function(e){
    	e.preventDefault();
    	$('.loading-gif').show();
    	$('#setting-merchant-form').submit();
    });

	$('.run-performance-btn').click(function(e){
		e.preventDefault();
		if(!confirm('Calculate this month\'s Performance Reward for every active agent now? Agents already rewarded this month will be skipped.')){
			return;
		}
		$('.loading-gif').show();
		$('#run-performance-form').submit();
	});

	function renumberTiers(){
		$('.tier-rows .tier-row').each(function(i){
			$(this).find('.tier-label').html('<b>Tier ' + (i + 1) + '</b>');
		});
	}

	function addTierRow(){
		var row = $('<div class="form-group container-box tier-row" data-tier-id="">\
						<div class="row">\
							<div class="col-sm-3">\
								<label>Tier</label>\
								<div class="tier-label"><b>Tier</b></div>\
							</div>\
							<div class="col-sm-3">\
								<label>Direct Downline Agents (at least)</label>\
								<input type="hidden" name="tier_id[]" value="">\
								<input type="text" class="form-control" name="tier_target[]" value="" onkeypress="return isNumberKey(event)">\
							</div>\
							<div class="col-sm-3">\
								<label>Additional %</label>\
								<input type="text" class="form-control" name="tier_amount[]" value="" onkeypress="return isNumberKey(event)">\
							</div>\
							<div class="col-sm-3" align="right">\
								<label>&nbsp;</label>\
								<button type="button" class="btn btn-outline-danger btn-block remove-tier-btn">\
									<i class="fa fa-trash"></i> Remove\
								</button>\
							</div>\
						</div>\
					</div>');

		$('.tier-rows').append(row);
		renumberTiers();
	}

	$('.add-tier-btn').click(function(e){
		e.preventDefault();
		addTierRow();
	});

	$('.tier-rows').on('click', '.remove-tier-btn', function(e){
		e.preventDefault();
		var row = $(this).closest('.tier-row');
		var tierId = row.data('tier-id');
		if(tierId){
			var existing = $('#deleted-tier-ids').val();
			var ids = existing ? existing.split(',') : [];
			ids.push(tierId);
			$('#deleted-tier-ids').val(ids.join(','));
		}
		row.remove();
		renumberTiers();
	});

	$('.save-tier-btn').click(function(e){
		e.preventDefault();
		$('.loading-gif').show();
		$('#setting-tier-form').submit();
	});
</script>
@endsection