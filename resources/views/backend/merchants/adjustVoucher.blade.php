@extends('layouts.admin_app')

@section('content')
<div class="page-header">
    <h1>
        {{ $merchant->f_name }}
        <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
           	{{ isset($data['backendlang']['backendlang']['Adjust_Voucher']) ? $data['backendlang']['backendlang']['Adjust_Voucher'] :'' }}
        </small>
    </h1>
</div>
<div class="form-group">
	<span class="badge badge-pill badge-primary"></span>
</div>

<h4>{{ isset($data['backendlang']['backendlang']['voucher_list']) ? $data['backendlang']['backendlang']['voucher_list'] :'' }}</h4>
<div class="row">
	<div class="col-sm-12">
		<div class="form-group">
			<table class="table table-bordered">
				<thead>
					<tr class="info">
						<th>#</th>
						<th>{{ isset($data['backendlang']['backendlang']['Vouchers']) ? $data['backendlang']['backendlang']['Vouchers'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Quantity']) ? $data['backendlang']['backendlang']['Quantity'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Action']) ? $data['backendlang']['backendlang']['Action'] :'' }}</th>
					</tr>
				</thead>
				<tbody>
					@if(!$adjusts->isEmpty())
					@foreach($adjusts as $key => $adjust)
					<tr>
						<td>{{ $key+1 }}</td>
						<td>{{ $adjust->promotion_title }}</td>
						<td>{{ $get_balance[$adjust->promotion_id] }}</td>
						<td>
							<a href="#" data-toggle="modal" data-target="#i{{ $key }}">
								{{ isset($data['backendlang']['backendlang']['Redeem']) ? $data['backendlang']['backendlang']['Redeem'] :'' }}
							</a>
							<div class="modal fade" id="i{{ $key }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
							  <div class="modal-dialog" role="document">
							    <div class="modal-content">
							    	<form method="POST" action="{{ route('adjustVoucher', $merchant->id) }}">
							    		@csrf
								      	<div class="modal-header">
								        	<h5 class="modal-title" id="exampleModalLabel">
								        		{{ isset($data['backendlang']['backendlang']['Key_In_Quantity']) ? $data['backendlang']['backendlang']['Key_In_Quantity'] :'' }}
								        	</h5>
								      	</div>
								      	<div class="modal-body">
								        	<input type="hidden" name="v_id" value="{{ $adjust->promotion_id }}">
								        	<input type="type" class="form-control" name="quantity">
								      	</div>
								      	<div class="modal-footer">
								        	<button type="button" class="btn btn-secondary" data-dismiss="modal">{{ isset($data['backendlang']['backendlang']['Close']) ? $data['backendlang']['backendlang']['Close'] :'' }}</button>
								        	<button class="btn btn-primary submit-deduct">{{ isset($data['backendlang']['backendlang']['Submit']) ? $data['backendlang']['backendlang']['Submit'] :'' }}</button>
								      	</div>
							    	</form>
							    </div>
							  </div>
							</div>
						</td>
					</tr>
					@endforeach
					@else
					<tr>
						<td colspan="5">
							{{ isset($data['backendlang']['backendlang']['No_Result_Found']) ? $data['backendlang']['backendlang']['No_Result_Found'] :'' }}
						</td>
					</tr>
					@endif
				</tbody>
			</table>
			{{ $adjusts->links() }}
		</div>
	</div>
</div>
<hr>
<h4>Redeem History List</h4>
<div class="row">
	<div class="col-sm-12">
		<div class="form-group">
			<table class="table table-bordered">
				<thead>
					<tr class="info">
						<th>#</th>
						<th>{{ isset($data['backendlang']['backendlang']['Vouchers']) ? $data['backendlang']['backendlang']['Vouchers'] :'' }}: {{ $GetCashWalletBalance }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Quantity']) ? $data['backendlang']['backendlang']['Quantity'] :'' }}: {{ $GetCashWalletBalance }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Date']) ? $data['backendlang']['backendlang']['Date'] :'' }}: {{ $GetCashWalletBalance }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Action']) ? $data['backendlang']['backendlang']['Action'] :'' }}: {{ $GetCashWalletBalance }}</th>
					</tr>
				</thead>
				<tbody>
					@if(!$deduct_vouchers->isEmpty())
					@foreach($deduct_vouchers as $key => $deduct)
					<tr>
						<td>{{ $key+1 }}</td>
						<td>{{ $deduct->promotion_title }}</td>
						<td>{{ $deduct->amount }}</td>
						<td>{{ $deduct->created_at }}</td>
						<td>
							<a href="#" class="delete-record" style="color: red;">
								{{ isset($data['backendlang']['backendlang']['Delete']) ? $data['backendlang']['backendlang']['Delete'] :'' }}: {{ $GetCashWalletBalance }}
							</a>
						</td>
					</tr>
					@endforeach
					@else
					<tr>
						<td colspan="5">
							{{ isset($data['backendlang']['backendlang']['No_Result_Found']) ? $data['backendlang']['backendlang']['No_Result_Found'] :'' }}: {{ $GetCashWalletBalance }}
						</td>
					</tr>
					@endif
				</tbody>
			</table>
		</div>
	</div>
</div>
@endsection