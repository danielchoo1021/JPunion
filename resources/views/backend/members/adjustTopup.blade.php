@extends('layouts.admin_app')

@section('content')
<div class="container-box form-group">
	<div class="page-header">
	    <h3>
	        {{ $user->f_name }}
	        >
	        <span>
	            <i class="ace-icon fa fa-angle-double-right"></i>
	           	{{ isset($data['backendlang']['backendlang']['Adjust_Topup_Wallet']) ? $data['backendlang']['backendlang']['Adjust_Topup_Wallet'] :'' }}
	        </span>
	    </h3>
	</div>
	<div class="form-group">
		<span class="badge bg-primary">{{ isset($data['backendlang']['backendlang']['Topup_Wallet_Balance']) ? $data['backendlang']['backendlang']['Topup_Wallet_Balance'] :'' }}: {{ $GetCashWalletBalance }}</span>
	</div>
	<form method="POST" action="{{ route('AdjustMemberTopup', $user->id) }}">
		@csrf
		@if($errors->any())
		  <div class="alert alert-danger">{!! implode('<br/>', $errors->all(':message')) !!}</div>
		@endif
		<div class="row">
			<div class="col-sm-6">
				<div class="row">
					<div class="col-sm-3">
						<select class="form-control" name="adjust_type">
							<option {{ (old('type') == '1') ? 'selected' : '' }} value="1">{{ isset($data['backendlang']['backendlang']['Increase']) ? $data['backendlang']['backendlang']['Increase'] :'' }}</option>
							<option {{ (old('type') == '2') ? 'selected' : '' }} value="2">{{ isset($data['backendlang']['backendlang']['Decrease']) ? $data['backendlang']['backendlang']['Decrease'] :'' }}</option>
						</select>
					</div>
					<div class="col-sm-9">
						<div class="form-group">
							<input type="text" class="form-control" name="adjust_amount" placeholder="{{ isset($data['backendlang']['backendlang']['Amount']) ? $data['backendlang']['backendlang']['Amount'] :'' }}" onkeypress="return isNumberKey(event)"
								   value="{{ old('quantity') }}">
						</div>
					</div>
				</div>

				<div class="form-group">
					<textarea class="form-control" name="remark" placeholder="{{ isset($data['backendlang']['backendlang']['Remark_Optional']) ? $data['backendlang']['backendlang']['Remark_Optional'] :'' }}">{{ old('remark') }}</textarea>
				</div>
			</div>
		</div>
		<div class="submit-form-btn">
			<div class="form-group wizard-actions" align="right">
				<a href="{{ route('member_wallet') }}" class="btn btn-default">
					<i class="fa fa-ban"> {{ isset($data['backendlang']['backendlang']['Cancel']) ? $data['backendlang']['backendlang']['Cancel'] :'' }}</i>
				</a>

				<button class="btn btn-primary">
					<i class="fa fa-check"> {{ isset($data['backendlang']['backendlang']['Create']) ? $data['backendlang']['backendlang']['Create'] :'' }}</i>
				</button>

			</div>
		</div>
	</form>
</div>

<div class="container-box">
	<h4>{{ isset($data['backendlang']['backendlang']['Adjustment_History_List']) ? $data['backendlang']['backendlang']['Adjustment_History_List'] :'' }}</h4>
	<div class="row">
		<div class="col-sm-12">
			<form method="GET" action="{{ route('AdjustMemberTopup', $user->id) }}">
				<div class="form-group">
					<div class="row">
						<div class="col-sm-2">
							<div class="form-group">
								<select class="form-control" name="type">
									<option value="">{{ isset($data['backendlang']['backendlang']['Select_Type']) ? $data['backendlang']['backendlang']['Select_Type'] :'' }}</option>
									<option {{ (!empty(request('type')) && request('type') == '1') ? 'selected' : '' }} value="1">
										{{ isset($data['backendlang']['backendlang']['Increase']) ? $data['backendlang']['backendlang']['Increase'] :'' }}
									</option>
									<option {{ (!empty(request('type')) && request('type') == '2') ? 'selected' : '' }} value="2">
										{{ isset($data['backendlang']['backendlang']['Decrease']) ? $data['backendlang']['backendlang']['Decrease'] :'' }}
									</option>
								</select>
							</div>
						</div>
						<div class="col-sm-4">
							<button class="btn btn-primary btn-sm">
								<i class="fa fa-search"></i> {{ isset($data['backendlang']['backendlang']['Search']) ? $data['backendlang']['backendlang']['Search'] :'' }}
							</button>
							<a href="{{ route('AdjustMemberTopup', $user->id) }}" class="btn btn-warning btn-sm">
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
									<option {{ (!empty(request('per_page')) && request('per_page') == '10') ? 'selected' : '' }} value="10">10</option>
									<option {{ (!empty(request('per_page')) && request('per_page') == '20') ? 'selected' : '' }} value="20">20</option>
									<option {{ (!empty(request('per_page')) && request('per_page') == '50') ? 'selected' : '' }} value="50">50</option>
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
							<th> {{ isset($data['backendlang']['backendlang']['Type']) ? $data['backendlang']['backendlang']['Type'] :'' }}</th>
							<th> {{ isset($data['backendlang']['backendlang']['Amount']) ? $data['backendlang']['backendlang']['Amount'] :'' }}</th>
							<th> {{ isset($data['backendlang']['backendlang']['Remark']) ? $data['backendlang']['backendlang']['Remark'] :'' }}</th>
							<th> {{ isset($data['backendlang']['backendlang']['Created_Date']) ? $data['backendlang']['backendlang']['Created_Date'] :'' }}</th>
							<th> {{ isset($data['backendlang']['backendlang']['Created_By']) ? $data['backendlang']['backendlang']['Created_By'] :'' }}</th>
						</tr>
					</thead>
					<tbody>
						@if(!$adjusts->isEmpty())
						@foreach($adjusts as $key => $adjust)
						<tr>
							<td>{{ $key+1 }}</td>
						<td>
								@if ($adjust->type == 1)
									{{ $data['backendlang']['backendlang']['Increase'] ?? 'Increase' }}
								@else
									{{ $data['backendlang']['backendlang']['Decrease'] ?? 'Decrease' }}
								@endif
							</td>
							<td>{{ $adjust->amount }}</td>
							<td>{{ $adjust->remark }}</td>
							<td>{{ $adjust->created_at }}</td>
							<td>{{ $adjust->created_by }}</td>
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