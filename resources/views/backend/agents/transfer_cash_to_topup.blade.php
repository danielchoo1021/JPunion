@extends('layouts.admin_app')

@section('content')
<div class="container-box form-group">
	<div class="">
	    <h3>
	        {{ $agent->f_name }}
	        <small>
	            <i class="ace-icon fa fa-angle-double-right"></i>
	           	{{ isset($data['backendlang']['backendlang']['Transfer_Cash_To_Topup']) ? $data['backendlang']['backendlang']['Transfer_Cash_To_Topup'] :'' }}
	        </small>
	    </h3>
	</div>
	<div class="form-group">
		<span class="badge badge-pill bg-primary">{{ isset($data['backendlang']['backendlang']['Cash_Balance']) ? $data['backendlang']['backendlang']['Cash_Balance'] :'' }}: {{ $GetCashWalletBalance }}</span>
	</div>
    {{-- <div class="form-group">
		<span class="badge badge-pill bg-primary">{{ isset($data['backendlang']['backendlang']['Top_Up_Balance']) ? $data['backendlang']['backendlang']['Top_Up_Balance'] :'' }}: {{ $GetTopupWalletBalance }}</span>
	</div> --}}
	<form method="POST" action="{{ route('SubmitTransferCashToTopup', $agent->id) }}">
		@csrf
		@if($errors->any())
		  <div class="alert alert-danger">{!! implode('<br/>', $errors->all(':message')) !!}</div>
		@endif
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
					<select class="form-control" name="user_id">
						<option value="{{ $agent->code }}">{{ $agent->f_name }}</option>
						@foreach ($direct_downlines as $direct_downline)
							<option value="{{ $direct_downline->code }}">{{ $direct_downline->f_name }}</option>
						@endforeach
					</select>
				</div>

				<div class="form-group">
					<input type="text" class="form-control" name="adjust_amount" placeholder="{{ isset($data['backendlang']['backendlang']['Amount']) ? $data['backendlang']['backendlang']['Amount'] :'' }}" onkeypress="return isNumberKey(event)"
							value="{{ old('quantity') }}">
				</div>

				<div class="form-group">
					<textarea class="form-control" name="remark" placeholder="{{ isset($data['backendlang']['backendlang']['Remark_Optional']) ? $data['backendlang']['backendlang']['Remark_Optional'] :'' }}">{{ old('remark') }}</textarea>
				</div>
			</div>
		</div>
		<div class="submit-form-btn">
			<div class="form-group wizard-actions" align="right">
				<a href="{{ route('agent_wallet') }}" class="btn btn-default">
					<i class="fa fa-ban"> {{ isset($data['backendlang']['backendlang']['Cancel']) ? $data['backendlang']['backendlang']['Cancel'] :'' }}</i>
				</a>
				<button class="btn btn-primary">
					<i class="bi bi-check"> {{ isset($data['backendlang']['backendlang']['Create']) ? $data['backendlang']['backendlang']['Create'] :'' }}</i>
				</button>
			</div>
		</div>
	</form>
</div>
<div class="container-box">
	<h4>{{ isset($data['backendlang']['backendlang']['Adjustment_History_List']) ? $data['backendlang']['backendlang']['Adjustment_History_List'] :'' }}</h4>
	<hr>
	<div class="row">
		<div class="col-sm-12">
			<form method="GET" action="{{ route('TransferCashToTopup', $agent->id) }}">
				<div class="form-group">
					<div class="row">
						<div class="col-sm-4">
							<button class="btn btn-primary btn-sm">
								<i class="bi bi-search"></i> {{ isset($data['backendlang']['backendlang']['Search']) ? $data['backendlang']['backendlang']['Search'] :'' }}
							</button>
							<a href="{{ route('TransferCashToTopup', $agent->id) }}" class="btn btn-warning btn-sm">
								<i class="bi bi-arrow-clockwise"></i> {{ isset($data['backendlang']['backendlang']['Clear_Search']) ? $data['backendlang']['backendlang']['Clear_Search'] :'' }}
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
			</form>
		</div>
		<div class="col-sm-12">
			<div class="form-group">
				<table class="table table-bordered">
					<thead>
						<tr class="info">
							<th>#</th>
							<th>{{ isset($data['backendlang']['backendlang']['Transfer_To']) ? $data['backendlang']['backendlang']['Transfer_To'] :'' }}</th>
							<th>{{ isset($data['backendlang']['backendlang']['Amount']) ? $data['backendlang']['backendlang']['Amount'] :'' }}</th>
							<th>{{ isset($data['backendlang']['backendlang']['Remark']) ? $data['backendlang']['backendlang']['Remark'] :'' }}</th>
							<th>{{ isset($data['backendlang']['backendlang']['Created_Date']) ? $data['backendlang']['backendlang']['Created_Date'] :'' }}</th>
						</tr>
					</thead>
					<tbody>
						@if(!$adjusts->isEmpty())
						@foreach($adjusts as $key => $adjust)
						<tr>
							<td>{{ $key+1 }}</td>
							<td>{{ $adjust->transfer_to_agent_name }} ({{ $adjust->user_id }})</td>
							<td>{{ $adjust->amount }}</td>
							<td>{{ $adjust->remark }}</td>
							<td>{{ $adjust->created_at }}</td>
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
</div>
@endsection