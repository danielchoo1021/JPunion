@extends('layouts.admin_app')
@section('content')
<div class="page-header">
	<h1>
	   {{ isset($data['backendlang']['backendlang']['Setting_Agent_Price']) ? $data['backendlang']['backendlang']['Setting_Agent_Price'] :'' }}
	</h1>
</div>
<div class="row">
	<div class="col-lg-12">
		<form method="POST" action="{{ route('setting_agent_price_save') }}" id="setting-agent-price-form">
			@csrf
			<div class="form-group child-row">
				<div class="row">
					<div class="col-lg-3">
						<h5><b>{{ isset($data['backendlang']['backendlang']['product']) ? $data['backendlang']['backendlang']['product'] :'' }}:</b></h5>
						<select class="form-control" name="product_list" id="selectedProduct" value="{{ old('product_list') }}">
							@foreach($products as $key => $product)
							<option value = "{{ $product->id }}" {{ old('product_list') ? "selected" : "" }}>{{ $product->product_name }}
							</option>
							@endforeach
						</select>
					</div>
				</div>
			</div>
			


			<div class="form-group child-row">
				<div class="container">
					<div class="row">
						@foreach($agentslvl as $key => $agentLvl)
							<div class="col-lg-4">
								<!-- @php
									$agentLvlPrice = $settingAgentPrice->where('agent_id', $agentLvl->id)->where('product_id', '.product_list.val()');
								@endphp -->
								<input type="hidden" name="aid[]" value="{{ $agentLvl->id }}">
								<h5><b>{{ $agentLvl->agent_lvl }}</b></h5>
								<div class="row">
									<input type="text" name="agent_lvl_price[]" placeholder="{{ isset($data['backendlang']['backendlang']['Price']) ? $data['backendlang']['backendlang']['Price'] :'' }}" value="{{ !empty($agentLvlPrice->price) ? '$agentLvlPrice->price' : '' }}">
								</div>
							</div>
						@endforeach
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
	function getSelectedValue(){
		$value = document.getElementById("selectedProduct").value;

		return $value;
	};

	$('.submit-form-btn .btn-outline-primary').click( function(e){
		e.preventDefault();

		var ProductID = $(this).closest('.child-row').find('input[name="product_list"]');
		$(".child-row input[name='aid[]']").each(function( index ) {
			var agentProductPrice = $(this).closest('.child-row').find('input[name="agent_lvl_price[]"]');
			var selectedAgentID = $(this).closest('.child-row').find('input[name="aid[]"]');
		});

		$('#setting-agent-price-form').submit();
	});
</script>
@endsection