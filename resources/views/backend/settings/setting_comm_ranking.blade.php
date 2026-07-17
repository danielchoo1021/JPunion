@extends('layouts.admin_app')
@section('content')
@csrf
<div class="row">
	<div class="col-sm-12"> 
		<form method="POST" action="{{ route('save_setting_comm_ranking') }}" id="setting-comm-form">
		@csrf
			<div class="big-parent">
				<div class="form-group">
					<div class="row">
						<div class="col-sm-3">
							<div class="form-group">
								<h5><b>{{ isset($data['backendlang']['backendlang']['Ranking_Title']) ? $data['backendlang']['backendlang']['Ranking_Title'] :'' }}</b></h5>
							</div>
						</div>
						<div class="col-sm-3">
							<div class="form-group">
								<h5><b>{{ isset($data['backendlang']['backendlang']['Ranking_Sales_Limit']) ? $data['backendlang']['backendlang']['Ranking_Sales_Limit'] :'' }}</b></h5>
							</div>
						</div>
						<div class="col-sm-3">
							<div class="form-group">
								<h5><b>{{ isset($data['backendlang']['backendlang']['Ranking_Commission_Percentage']) ? $data['backendlang']['backendlang']['Ranking_Commission_Percentage'] :'' }}</b></h5>
							</div>
						</div>
						<div class="col-sm-3">
							<div class="form-group">
								<h5><b>{{ isset($data['backendlang']['backendlang']['Specification']) ? $data['backendlang']['backendlang']['Specification'] :'' }}</b></h5>
							</div>
						</div>
					</div>
					<div class="child-div">
						@php
							$lower_limit = 0;
							$row_id = 1;
						@endphp
						@foreach($comm_rankings as $rank)
							<div class="form-group child-row">
								<div class="row">
									<div class="col-sm-3">
										<input type="text" name="rank_title[]" class="form-control rank_title" value="{{ isset($rank) ? $rank->title : '' }}">
									</div>
									<div class="col-sm-3">
										<input type="text" name="rank_sales_limit[]" class="form-control rank_sales_limit" value="{{ isset($rank) ? $rank->comm_requirement_limit : '' }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
									</div>
									<div class="col-sm-3">
										<input type="text" name="rank_comm_perc[]" class="form-control rank_comm_perc" value="{{ isset($rank) ? $rank->comm_perc : '' }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
									</div>
									<div class="col-sm-3 specification">
										<b>RM <span class="specification_lower_limit">{{ number_format($lower_limit, 2) }}</span> - <span class="specification_upper_limit">{{ number_format($rank->comm_requirement_limit, 2) }}</span> => <span class="specification_perc">{{ $rank->comm_perc }}</span>%</b>
									</div>
									<input type="hidden" name="rank_id[]" class="rank_id" value="{{ $rank->id }}">
									<input type="hidden" name="lower_limit[]" class="lower_limit" value="{{ $lower_limit }}">
									<input type="hidden" name="row_id" class="row_id" value="{{ $row_id }}">
								</div>
							</div>
							@php
								$lower_limit = $rank->comm_requirement_limit + 1;
								$row_id++;
							@endphp
						@endforeach
						<div class="form-group child-row">
							<div class="row">
								<div class="col-sm-3">
									<input type="text" name="rank_title[]" class="form-control rank_title" placeholder="{{ isset($data['backendlang']['backendlang']['Title']) ? $data['backendlang']['backendlang']['Title'] :'' }}">
								</div>
								<div class="col-sm-3">
									<input type="text" name="rank_sales_limit[]" class="form-control rank_sales_limit" placeholder="{{ isset($data['backendlang']['backendlang']['Sales_Requirement_Limit']) ? $data['backendlang']['backendlang']['Sales_Requirement_Limit'] :'' }} (RM)" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
								</div>
								<div class="col-sm-3">
									<input type="text" name="rank_comm_perc[]" class="form-control rank_comm_perc" placeholder="{{ isset($data['backendlang']['backendlang']['Commission_Percentage']) ? $data['backendlang']['backendlang']['Commission_Percentage'] :'' }} (%)" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
								</div>
								<div class="col-sm-3 specification">

								</div>
							</div>
						</div>
					</div>
				</div>
				<hr>
				<div class="form-group">
					<div class="row">
						<div class="col-md-12" align="center">
							<button class="add-row-btn">
								<i class="fa fa-plus"></i>
							</button>
						</div>
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
    	$('.loading-gif').show();
    	$('#setting-comm-form').submit();
    });

    $('.add-row-btn').click(function(e){
    	e.preventDefault();

    	var add_new_row = '<div class="form-group child-row">\
							<div class="row">\
								<div class="col-sm-3">\
									<input type="text" name="rank_title[]" class="form-control rank_title" placeholder="{{ isset($data["backendlang"]["backendlang"]["Title"]) ? $data["backendlang"]["backendlang"]["Title"] :''}}">\
								</div>\
								<div class="col-sm-3">\
									<input type="text" name="rank_sales_limit[]" class="form-control rank_sales_limit" placeholder="{{ isset($data["backendlang"]["backendlang"]["Sales_Requirement_Limit"]) ? $data["backendlang"]["backendlang"]["Sales_Requirement_Limit"] :''}} (RM)" pattern="[0-9]*">\
								</div>\
								<div class="col-sm-3">\
									<input type="text" name="rank_comm_perc[]" class="form-control rank_comm_perc" placeholder="{{ isset($data["backendlang"]["backendlang"]["Commission_Percentage"]) ? $data["backendlang"]["backendlang"]["Commission_Percentage"] :''}} (%)" pattern="[0-9]*">\
								</div>\
								<div class="col-sm-3 specification">\
									\
								</div>\
							</div>\
						</div>';

		$('.big-parent').find('.child-div').append(add_new_row);
    });

    $('.rank_sales_limit').on('input',function(e){
    	var ele = $(this);

    	var limit = ele.val();
    	var lower_limit = ele.closest('.row').find('.lower_limit').val();
    	var row_id = ele.closest('.row').find('.row_id').val();
    	var next_row_id = Number(row_id) + Number(1);
    	var previous_row_id = row_id - 1;
    	var next_limit = Number(limit) + Number(1);
    	
    	
		// var previous_row = $('input[name="row_id"][value="'+previous_row_id+'"]').closest('.row').find('.lower_limit').val();
		var next_row = $('input[name="row_id"][value="'+next_row_id+'"]').closest('.row').find('.lower_limit').val(limit);
		$('input[name="row_id"][value="'+next_row_id+'"]').closest('.row').find('.specification_lower_limit').html(parseFloat(next_limit).toFixed(2));
		ele.closest('.row').find('.specification_upper_limit').html(parseFloat(limit).toFixed(2));
    	
    });

    $('.rank_comm_perc').on('input', function(e){
    	var ele = $(this);

    	var perc = ele.val();

    	ele.closest('.row').find('.specification_perc').html(perc);
    });

    function setInputFilter(textbox, inputFilter) {
	  ["input", "keydown", "keyup", "mousedown", "mouseup", "select", "contextmenu", "drop"].forEach(function(event) {
	    textbox.addEventListener(event, function() {
	      if (inputFilter(this.value)) {
	        this.oldValue = this.value;
	        this.oldSelectionStart = this.selectionStart;
	        this.oldSelectionEnd = this.selectionEnd;
	      } else if (this.hasOwnProperty("oldValue")) {
	        this.value = this.oldValue;
	        this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
	      } else {
	        this.value = "";
	      }
	    });
	  });
	}
</script>
@endsection