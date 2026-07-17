@extends('layouts.admin_app')
@section('content')
<form method="POST" action="{{ route('setting_topup_amount') }}" id="setting-merchant-form">
	@csrf
	<div class="big-parent">
		<div class="form-group container-box">
			<div class="row">
				<div class="col-6">
					<div class="form-group">
						<h5><b>{{ isset($data['backendlang']['backendlang']['Topup_Amount']) ? $data['backendlang']['backendlang']['Topup_Amount'] :'' }} (RM)</b></h5>
					</div>
				</div>
				<div class="col-6">
					<div class="form-group">
						<h5><b>{{ isset($data['backendlang']['backendlang']['Receive_Pin']) ? $data['backendlang']['backendlang']['Receive_Pin'] :'' }}</b></h5>
					</div>
				</div>
			</div>
			<div class="child-div add-message-content">
				@foreach($selects as $select)
				<div class="form-group child-row messsage-del">
					<div class="row">
						<div class="col-3">
							<input type="text" name="topup_amount[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['e.g.']) ? $data['backendlang']['backendlang']['e.g.'] :'' }} 5" value="{{ $select->topup_amount }}">
						</div>
						<div class="col-3">
							<select class="form-control" name="profit_type[]">
								<option {{ $select->profit_type == 'Amount' ? 'selected' : '' }} value="Amount">
									{{ isset($data['backendlang']['backendlang']['Amount']) ? $data['backendlang']['backendlang']['Amount'] :'' }}
								</option>
								<!-- <option {{ $select->profit_type == 'Percentage' ? 'selected' : '' }} value="Percentage">
									Percentage
								</option> -->
							</select>
						</div>
						<div class="col-3">
							<input type="text" name="profit_amount[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['e.g.']) ? $data['backendlang']['backendlang']['e.g.'] :'' }} 5" value="{{ $select->profit_amount }}">
						</div>
						<input type="hidden" class="tid" name="tid[]" value="{{ $select->id }}">

						<div class="col-3" align="center">
							<a href="#" class="important-text red del">
								<i class="bi bi-trash fa-2x"></i>
							</a>
						</div>
					</div>
				</div>
				@endforeach
				<div class="form-group child-row  del-new-message">
					<div class="row">
						<div class="col-3">
							<input type="text" name="topup_amount[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['e.g.']) ? $data['backendlang']['backendlang']['e.g.'] :'' }} 5">
							<input type="hidden" name="tid[]" value="">
						</div>
						<div class="col-3">
							<select class="form-control" name="profit_type[]">
								<option  value="Amount">
									{{ isset($data['backendlang']['backendlang']['Amount']) ? $data['backendlang']['backendlang']['Amount'] :'' }}
								</option>
								<!-- <option value="Percentage">
									Percentage
								</option> -->
							</select>
						</div>
						<div class="col-3">
							<input type="text" name="profit_amount[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['e.g.']) ? $data['backendlang']['backendlang']['e.g.'] :'' }} 5">
						</div>
						<div class="col-3" align="center">
							<a href="#" class="important-text red del">
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
					<button class="btn btn-primary btn-sm add-row-btn">
						<i class="bi bi-plus"></i>
					</button>
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
@endsection

@section('js')
<script type="text/javascript">
	var add_new_row = '<div class="form-group child-row del-new-message">\
							<div class="row">\
								<div class="col-3">\
									<input type="text" name="topup_amount[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['e.g.']) ? $data['backendlang']['backendlang']['e.g.'] :'' }} 5">\
									<input type="hidden" name="tid[]" value="">\
								</div>\
								<div class="col-3">\
									<select class="form-control" name="profit_type[]">\
										<option  value="Amount">\
											{{ isset($data['backendlang']['backendlang']['Amount']) ? $data['backendlang']['backendlang']['Amount'] :'' }}\
										</option>\
									</select>\
								</div>\
								<div class="col-3">\
									<input type="text"  name="profit_amount[]" class="form-control" placeholder="{{ isset($data['backendlang']['backendlang']['e.g.']) ? $data['backendlang']['backendlang']['e.g.'] :'' }} 5">\
								</div>\
								<div class="col-3" align="center">\
									<a href="#"  class="important-text red del">\
										<i class="bi bi-trash fa-2x"></i>\
									</a>\
								</div>\
							</div>\
						</div>';
	$('.add-row-btn').click(function(e) {
		e.preventDefault();
		var ele = $(this);

		ele.closest('.big-parent').find('.child-div').append(add_new_row);

	});

	$('.submit-form-btn .btn-outline-primary').click(function(e) {
		e.preventDefault();

		$('#setting-merchant-form').submit();
	});

	$('.add-message-content').on('click', '.del', function(e) {
		e.preventDefault();

		var ele = $(this);
		var id = ele.closest('.messsage-del').find('.tid').val();

		if (id) {
			if (confirm('{{ isset($data['backendlang']['backendlang']['Delete_this_bonus']) ? $data['backendlang']['backendlang']['Delete_this_bonus'] :'' }}') == true) {
				var fd = new FormData();
				fd.append('id', id);

				$.ajax({
					url: '{{ route("DeleteTopupBonus") }}',
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
</script>
@endsection