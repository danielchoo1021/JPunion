@extends('layouts.admin_app')

@section('content')
<div class="page-header">
    <h1>
        {{ isset($data['backendlang']['backendlang']['New_Customer_Voucher_List']) ? $data['backendlang']['backendlang']['New_Customer_Voucher_List'] :'' }}
        <!-- <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
        </small> -->
    </h1>
</div>
<form action="{{ route('new_customer_promotions') }}" method="GET">
<div class="row">
	<div class="col-sm-2">
		<div class="form-group">
			<input type="text" class="form-control" name="dates" value="{{ !empty(request('dates')) ? request('dates') : $startDate.' - '.$endDate }}">
		</div>
	</div>

	<div class="col-sm-2">
		<div class="form-group">
			<input type="text" class="form-control" name="promotion_title" value="{{ !empty(request('promotion_title')) ? request('promotion_title') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Promotion_Name']) ? $data['backendlang']['backendlang']['Search_Promotion_Name'] :'' }}">
		</div>
	</div>

	<div class="col-sm-2">
		<div class="form-group">
			<input type="text" class="form-control" name="customer_name" value="{{ !empty(request('customer_name')) ? request('customer_name') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Customer_Name']) ? $data['backendlang']['backendlang']['Search_Customer_Name'] :'' }}">
		</div>
	</div>

	<div class="col-sm-2">
		<div class="form-group">
			<input type="text" class="form-control" name="own_code" value="{{ !empty(request('own_code')) ? request('own_code') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Customer_Code']) ? $data['backendlang']['backendlang']['Search_Customer_cODE'] :'' }}">
		</div>
	</div>

	<div class="col-sm-2">
		<div class="form-group">
			<input type="text" class="form-control" name="ic" value="{{ !empty(request('ic')) ? request('ic') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Customer_IC']) ? $data['backendlang']['backendlang']['Search_Customer_IC'] :'' }}">
		</div>
	</div>

	<div class="col-sm-2">
		<div class="form-group">
			<input type="text" class="form-control" name="phone" value="{{ !empty(request('phone')) ? request('phone') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Customer_Phone']) ? $data['backendlang']['backendlang']['Search_Customer_Phone'] :'' }}">
		</div>
	</div>

	<div class="col-sm-2">
		<div class="form-group">
			<input type="text" class="form-control" name="referrer_code" value="{{ !empty(request('referrer_code')) ? request('referrer_code') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Referral_Code']) ? $data['backendlang']['backendlang']['Search_Referral_Code'] :'' }}">
		</div>
	</div>

	<div class="col-sm-2">
		<div class="form-group">
			<input type="text" class="form-control" name="referrer_name" value="{{ !empty(request('referrer_name')) ? request('referrer_name') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Referral_Name']) ? $data['backendlang']['backendlang']['Search_Referral_Name'] :'' }}">
		</div>
	</div>

	<div class="col-sm-2">
		<div class="form-group">
			<input type="text" class="form-control" name="company_registration_no" value="{{ !empty(request('company_registration_no')) ? request('company_registration_no') : '' }}" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Company_Registration_No']) ? $data['backendlang']['backendlang']['Search_Company_Registration_No'] :'' }}">
		</div>
	</div>

	<div class="col-sm-2">
		<div class="form-group">
			<select class="form-control" name="status">
				<option value="">{{ isset($data['backendlang']['backendlang']['Select_Status']) ? $data['backendlang']['backendlang']['Select_Status'] :'' }}</option>
				<option {{ (!empty(request('status')) && request('status') == '1') ? 'selected' : '' }} value="1">{{ isset($data['backendlang']['backendlang']['Active']) ? $data['backendlang']['backendlang']['Active'] :'' }}</option>
				<option {{ (!empty(request('status')) && request('status') == '2') ? 'selected' : '' }} value="2">{{ isset($data['backendlang']['backendlang']['Inactive']) ? $data['backendlang']['backendlang']['Inative'] :'' }}</option>
			</select>
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
<div class="form-group">
	<div class="row">
		<div class="col-sm-4">
			<div class="form-group">
				<button class="btn btn-outline-primary btn-sm">
					<i class="fa fa-search"></i> {{ isset($data['backendlang']['backendlang']['Search']) ? $data['backendlang']['backendlang']['Search'] :'' }}
				</button>
				<a href="{{ route('new_customer_promotions') }}" class="btn btn-warning btn-sm">
					<i class="fa fa-refresh"></i> {{ isset($data['backendlang']['backendlang']['Clear_Search']) ? $data['backendlang']['backendlang']['Clear_Search'] :'' }}
				</a>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-12">
		<div class="table-responsive">
			<table class="table table-bordered">
				<thead>
					<tr class="info">
						<th>#</th>
						<th>{{ isset($data['backendlang']['backendlang']['Promotion_title']) ? $data['backendlang']['backendlang']['Promotion_title'] :'' }}
							<br>
							@if(empty(request('title_desc')) && empty(request('title_asc')))
								<a href="{{ route('new_customer_promotions', ['title_desc=DESC']) }}" 
								   class="{{ !empty(request('title_desc')) ? 'selected' : '' }}">
									<i class="fa fa-sort"></i>
									<input type="hidden" name="sort_data" value="0">
								</a>
							@else
								@if(!empty(request('title_desc')))
									<a href="{{ route('new_customer_promotions', ['title_asc=ASC']) }}" 
									   class="{{ !empty(request('title_asc')) ? 'selected' : '' }}">
										<i class="fa fa-sort"></i>
										<input type="hidden" name="sort_data" value="1">
									</a>
								@elseif(!empty(request('title_asc')))
									<a href="{{ route('new_customer_promotions', ['title_desc=DESC']) }}" 
									   class="{{ !empty(request('title_desc')) ? 'selected' : '' }}">
										<i class="fa fa-sort"></i>
										<input type="hidden" name="sort_data" value="0">
									</a>
								@endif
							@endif
						</th>
						<th>{{ isset($data['backendlang']['backendlang']['Customer_Name']) ? $data['backendlang']['backendlang']['Customer_Name'] :'' }}
							<br>
							@if(empty(request('cust_name_desc')) && empty(request('cust_name_asc')))
								<a href="{{ route('new_customer_promotions', ['cust_name_desc=DESC']) }}" 
								   class="{{ !empty(request('cust_name_desc')) ? 'selected' : '' }}">
									<i class="fa fa-sort"></i>
									<input type="hidden" name="sort_data" value="0">
								</a>
							@else
								@if(!empty(request('cust_name_desc')))
									<a href="{{ route('new_customer_promotions', ['cust_name_asc=ASC']) }}" 
									   class="{{ !empty(request('cust_name_asc')) ? 'selected' : '' }}">
										<i class="fa fa-sort"></i>
										<input type="hidden" name="sort_data" value="1">
									</a>
								@elseif(!empty(request('cust_name_asc')))
									<a href="{{ route('new_customer_promotions', ['cust_name_desc=DESC']) }}" 
									   class="{{ !empty(request('cust_name_desc')) ? 'selected' : '' }}">
										<i class="fa fa-sort"></i>
										<input type="hidden" name="sort_data" value="0">
									</a>
								@endif
							@endif
						</th>
						<th>Code
							<br>
							@if(empty(request('code_desc')) && empty(request('code_asc')))
								<a href="{{ route('new_customer_promotions', ['code_desc=DESC']) }}" 
								   class="{{ !empty(request('code_desc')) ? 'selected' : '' }}">
									<i class="fa fa-sort"></i>
									<input type="hidden" name="sort_data" value="0">
								</a>
							@else
								@if(!empty(request('code_desc')))
									<a href="{{ route('new_customer_promotions', ['code_asc=ASC']) }}" 
									   class="{{ !empty(request('code_asc')) ? 'selected' : '' }}">
										<i class="fa fa-sort"></i>
										<input type="hidden" name="sort_data" value="1">
									</a>
								@elseif(!empty(request('code_asc')))
									<a href="{{ route('new_customer_promotions', ['code_desc=DESC']) }}" 
									   class="{{ !empty(request('code_desc')) ? 'selected' : '' }}">
										<i class="fa fa-sort"></i>
										<input type="hidden" name="sort_data" value="0">
									</a>
								@endif
							@endif
						</th>
						<th>IC
							<br>
							@if(empty(request('ic_desc')) && empty(request('ic_asc')))
								<a href="{{ route('new_customer_promotions', ['ic_desc=DESC']) }}" 
								   class="{{ !empty(request('ic_desc')) ? 'selected' : '' }}">
									<i class="fa fa-sort"></i>
									<input type="hidden" name="sort_data" value="0">
								</a>
							@else
								@if(!empty(request('ic_desc')))
									<a href="{{ route('new_customer_promotions', ['ic_asc=ASC']) }}" 
									   class="{{ !empty(request('ic_asc')) ? 'selected' : '' }}">
										<i class="fa fa-sort"></i>
										<input type="hidden" name="sort_data" value="1">
									</a>
								@elseif(!empty(request('ic_asc')))
									<a href="{{ route('new_customer_promotions', ['ic_desc=DESC']) }}" 
									   class="{{ !empty(request('ic_desc')) ? 'selected' : '' }}">
										<i class="fa fa-sort"></i>
										<input type="hidden" name="sort_data" value="0">
									</a>
								@endif
							@endif
						</th>
						<th>{{ isset($data['backendlang']['backendlang']['Phone']) ? $data['backendlang']['backendlang']['Phone'] :'' }}
							<br>
							@if(empty(request('phone_desc')) && empty(request('phone_asc')))
								<a href="{{ route('new_customer_promotions', ['phone_desc=DESC']) }}" 
								   class="{{ !empty(request('phone_desc')) ? 'selected' : '' }}">
									<i class="fa fa-sort"></i>
									<input type="hidden" name="sort_data" value="0">
								</a>
							@else
								@if(!empty(request('phone_desc')))
									<a href="{{ route('new_customer_promotions', ['phone_asc=ASC']) }}" 
									   class="{{ !empty(request('phone_asc')) ? 'selected' : '' }}">
										<i class="fa fa-sort"></i>
										<input type="hidden" name="sort_data" value="1">
									</a>
								@elseif(!empty(request('phone_asc')))
									<a href="{{ route('new_customer_promotions', ['phone_desc=DESC']) }}" 
									   class="{{ !empty(request('phone_desc')) ? 'selected' : '' }}">
										<i class="fa fa-sort"></i>
										<input type="hidden" name="sort_data" value="0">
									</a>
								@endif
							@endif
						</th>
						<th>{{ isset($data['backendlang']['backendlang']['Level']) ? $data['backendlang']['backendlang']['Level'] :'' }}
							<br>
							@if(empty(request('lvl_desc')) && empty(request('lvl_asc')))
								<a href="{{ route('new_customer_promotions', ['lvl_desc=DESC']) }}" 
								   class="{{ !empty(request('lvl_desc')) ? 'selected' : '' }}">
									<i class="fa fa-sort"></i>
									<input type="hidden" name="sort_data" value="0">
								</a>
							@else
								@if(!empty(request('lvl_desc')))
									<a href="{{ route('new_customer_promotions', ['lvl_asc=ASC']) }}" 
									   class="{{ !empty(request('lvl_asc')) ? 'selected' : '' }}">
										<i class="fa fa-sort"></i>
										<input type="hidden" name="sort_data" value="1">
									</a>
								@elseif(!empty(request('lvl_asc')))
									<a href="{{ route('new_customer_promotions', ['lvl_desc=DESC']) }}" 
									   class="{{ !empty(request('lvl_desc')) ? 'selected' : '' }}">
										<i class="fa fa-sort"></i>
										<input type="hidden" name="sort_data" value="0">
									</a>
								@endif
							@endif
						</th>
						<th>{{ isset($data['backendlang']['backendlang']['Referrer_Code']) ? $data['backendlang']['backendlang']['Referrer_Code'] :'' }}
							<br>
							@if(empty(request('ref_code_desc')) && empty(request('ref_code_asc')))
								<a href="{{ route('new_customer_promotions', ['ref_code_desc=DESC']) }}" 
								   class="{{ !empty(request('ref_code_desc')) ? 'selected' : '' }}">
									<i class="fa fa-sort"></i>
									<input type="hidden" name="sort_data" value="0">
								</a>
							@else
								@if(!empty(request('ref_code_desc')))
									<a href="{{ route('new_customer_promotions', ['ref_code_asc=ASC']) }}" 
									   class="{{ !empty(request('ref_code_asc')) ? 'selected' : '' }}">
										<i class="fa fa-sort"></i>
										<input type="hidden" name="sort_data" value="1">
									</a>
								@elseif(!empty(request('ref_code_asc')))
									<a href="{{ route('new_customer_promotions', ['ref_code_desc=DESC']) }}" 
									   class="{{ !empty(request('ref_code_desc')) ? 'selected' : '' }}">
										<i class="fa fa-sort"></i>
										<input type="hidden" name="sort_data" value="0">
									</a>
								@endif
							@endif
						</th>
						<th>{{ isset($data['backendlang']['backendlang']['Referrer_Name']) ? $data['backendlang']['backendlang']['Referrer_Name'] :'' }}
							<br>
							@if(empty(request('ref_name_desc')) && empty(request('ref_name_asc')))
								<a href="{{ route('new_customer_promotions', ['ref_name_desc=DESC']) }}" 
								   class="{{ !empty(request('ref_name_desc')) ? 'selected' : '' }}">
									<i class="fa fa-sort"></i>
									<input type="hidden" name="sort_data" value="0">
								</a>
							@else
								@if(!empty(request('ref_name_desc')))
									<a href="{{ route('new_customer_promotions', ['ref_name_asc=ASC']) }}" 
									   class="{{ !empty(request('ref_name_asc')) ? 'selected' : '' }}">
										<i class="fa fa-sort"></i>
										<input type="hidden" name="sort_data" value="1">
									</a>
								@elseif(!empty(request('ref_name_asc')))
									<a href="{{ route('new_customer_promotions', ['ref_name_desc=DESC']) }}" 
									   class="{{ !empty(request('ref_name_desc')) ? 'selected' : '' }}">
										<i class="fa fa-sort"></i>
										<input type="hidden" name="sort_data" value="0">
									</a>
								@endif
							@endif
						</th>
						<th>{{ isset($data['backendlang']['backendlang']['Company_Registration_No']) ? $data['backendlang']['backendlang']['Company_Registration_No'] :'' }}
							<br>
							@if(empty(request('company_no_desc')) && empty(request('company_no_asc')))
								<a href="{{ route('new_customer_promotions', ['company_no_desc=DESC']) }}" 
								   class="{{ !empty(request('company_no_desc')) ? 'selected' : '' }}">
									<i class="fa fa-sort"></i>
									<input type="hidden" name="sort_data" value="0">
								</a>
							@else
								@if(!empty(request('company_no_desc')))
									<a href="{{ route('new_customer_promotions', ['company_no_asc=ASC']) }}" 
									   class="{{ !empty(request('company_no_asc')) ? 'selected' : '' }}">
										<i class="fa fa-sort"></i>
										<input type="hidden" name="sort_data" value="1">
									</a>
								@elseif(!empty(request('company_no_asc')))
									<a href="{{ route('new_customer_promotions', ['company_no_desc=DESC']) }}" 
									   class="{{ !empty(request('company_no_desc')) ? 'selected' : '' }}">
										<i class="fa fa-sort"></i>
										<input type="hidden" name="sort_data" value="0">
									</a>
								@endif
							@endif
						</th>
						<th>{{ isset($data['backendlang']['backendlang']['Start_Date']) ? $data['backendlang']['backendlang']['Start_Date'] :'' }}
							<br>
							@if(empty(request('start_date_desc')) && empty(request('start_date_asc')))
								<a href="{{ route('new_customer_promotions', ['start_date_desc=DESC']) }}" 
								   class="{{ !empty(request('start_date_desc')) ? 'selected' : '' }}">
									<i class="fa fa-sort"></i>
									<input type="hidden" name="sort_data" value="0">
								</a>
							@else
								@if(!empty(request('start_date_desc')))
									<a href="{{ route('new_customer_promotions', ['start_date_asc=ASC']) }}" 
									   class="{{ !empty(request('start_date_asc')) ? 'selected' : '' }}">
										<i class="fa fa-sort"></i>
										<input type="hidden" name="sort_data" value="1">
									</a>
								@elseif(!empty(request('start_date_asc')))
									<a href="{{ route('new_customer_promotions', ['start_date_desc=DESC']) }}" 
									   class="{{ !empty(request('start_date_desc')) ? 'selected' : '' }}">
										<i class="fa fa-sort"></i>
										<input type="hidden" name="sort_data" value="0">
									</a>
								@endif
							@endif
						</th>
						<th>{{ isset($data['backendlang']['backendlang']['End_Date']) ? $data['backendlang']['backendlang']['End_Date'] :'' }}
							<br>
							@if(empty(request('end_date_desc')) && empty(request('end_date_asc')))
								<a href="{{ route('new_customer_promotions', ['end_date_desc=DESC']) }}" 
								   class="{{ !empty(request('end_date_desc')) ? 'selected' : '' }}">
									<i class="fa fa-sort"></i>
									<input type="hidden" name="sort_data" value="0">
								</a>
							@else
								@if(!empty(request('end_date_desc')))
									<a href="{{ route('new_customer_promotions', ['end_date_asc=ASC']) }}" 
									   class="{{ !empty(request('end_date_asc')) ? 'selected' : '' }}">
										<i class="fa fa-sort"></i>
										<input type="hidden" name="sort_data" value="1">
									</a>
								@elseif(!empty(request('end_date_asc')))
									<a href="{{ route('new_customer_promotions', ['end_date_desc=DESC']) }}" 
									   class="{{ !empty(request('end_date_desc')) ? 'selected' : '' }}">
										<i class="fa fa-sort"></i>
										<input type="hidden" name="sort_data" value="0">
									</a>
								@endif
							@endif
						</th>
						<th>{{ isset($data['backendlang']['backendlang']['Redeemed_Voucher']) ? $data['backendlang']['backendlang']['Redeemed_Voucher'] :'' }}</th>
						<th>{{ isset($data['backendlang']['backendlang']['Status']) ? $data['backendlang']['backendlang']['Status'] :'' }}
							<br>
							@if(empty(request('status_desc')) && empty(request('status_asc')))
								<a href="{{ route('new_customer_promotions', ['status_desc=DESC']) }}" 
								   class="{{ !empty(request('status_desc')) ? 'selected' : '' }}">
									<i class="fa fa-sort"></i>
									<input type="hidden" name="sort_data" value="0">
								</a>
							@else
								@if(!empty(request('status_desc')))
									<a href="{{ route('new_customer_promotions', ['status_asc=ASC']) }}" 
									   class="{{ !empty(request('status_asc')) ? 'selected' : '' }}">
										<i class="fa fa-sort"></i>
										<input type="hidden" name="sort_data" value="1">
									</a>
								@elseif(!empty(request('status_asc')))
									<a href="{{ route('new_customer_promotions', ['status_desc=DESC']) }}" 
									   class="{{ !empty(request('status_desc')) ? 'selected' : '' }}">
										<i class="fa fa-sort"></i>
										<input type="hidden" name="sort_data" value="0">
									</a>
								@endif
							@endif
						</th>
					</tr>
				</thead>
				<tbody>
					@if(!$promotions->isEmpty())
					@foreach($promotions as $key => $promotion)

					@php
						$before_start = (date('Y-m-d H:i:s') < $promotion->start_date) ? '1' : '';
						$after_end = (date('Y-m-d H:i:s') > $promotion->end_date) ? '1' : '';
						$ic = [];
						$phone = [];

						if($promotion->user_status == 3){
							if(!empty($promotion->customer_ic)){
								$ic = explode('-', $promotion->customer_ic);
							}

							if(!empty($promotion->customer_phone)){
								$phone = explode('-', $promotion->customer_phone);
							}
						}
					@endphp
					<tr>
						<td>{{ $key+1 }}
							<input type="hidden" class="row_id" value="{{ $promotion->id }}">
						</td>
						<td>{{ $promotion->promotion_title }}</td>
						<td>{{ $promotion->customer_name }}</td>
						<td>{{ $promotion->customer_code }}</td>
						<td>
							@if($promotion->user_status == 3)
								{{ $ic[0] }}
							@else
								@if(!empty($promotion->customer_ic))
									{{ $promotion->customer_ic }}
								@else
									-
								@endif
							@endif
						</td>
						<td>
							@if($promotion->user_status == 3)
								{{ $phone[0] }}
							@else
								@if(!empty($promotion->customer_phone))
									{{ $promotion->customer_phone }}
								@else
									-
								@endif
							@endif
						</td>
						<td>
							@if($promotion->user_status == 3)
								@if(!empty($phone))
									{{ $phone[1] }}
								@elseif(!empty($ic))
									{{ $ic[1] }}
								@else
									-
								@endif
							@else
								-
							@endif
						</td>
						<td>{{ $promotion->referrer_code }}</td>
						<td>{{ $promotion->referrer_name }}</td>
						<td>
							@if(!empty($promotion->customer_company_registration_no))
								{{ $promotion->customer_company_registration_no }}
							@else
								-
							@endif
						</td>
						<td>{{ $promotion->start_date }}</td>
						<td>{{ $promotion->end_date }}</td>
						<td>
							@if($redeemed[$promotion->id] > 0)
								@if(!empty($redeemedWhere[$promotion->id]))
									<a href="{{ route('transaction.transactions.edit', $redeemedWhere[$promotion->id]->id) }}"> 
										Used on transaction #{{ $redeemedWhere[$promotion->id]->transaction_no }}
									</a>
								@else
									@if($before_start == 1 || $after_end == 1)
										Expired
									@else
										Unused
									@endif
								@endif
							@elseif($underRedemption[$promotion->id] > 0)
								@if($before_start == 1 || $after_end == 1)
									Expired
								@else
									Unused
								@endif
							@else
								Unused
							@endif
						</td>
						<td>
							{!! ($promotion->status == 1) && ($before_start != 1) && ($after_end != 1) 
								? '<span class="badge bg-success">' . ($data['backendlang']['backendlang']['Active'] ?? 'Active') . '</span>' 
								: '<span class="badge bg-danger">' . ($data['backendlang']['backendlang']['Inactive'] ?? 'Inactive') . '</span>' 
							!!}
						</td>

					</tr>
					@endforeach
					@else
					<tr>
						<td colspan="14">{{ isset($data['backendlang']['backendlang']['No_Result_Found']) ? $data['backendlang']['backendlang']['No_Result_Found'] :'' }}</td>
					</tr>
					@endif
				</tbody>
			</table>
			{{ $promotions->links() }}
		</div>
	</div>
</div>
</form>
@endsection

@section('js')
	<script type="text/javascript">
		$('input[name=dates]').daterangepicker({
			'applyClass' : 'btn-sm btn-success',
			'cancelClass' : 'btn-sm btn-outline-danger',
			locale: {
				applyLabel: "{{ isset($data['backendlang']['backendlang']['Apply']) ? $data['backendlang']['backendlang']['Apply'] :'' }}",
				cancelLabel: "{{ isset($data['backendlang']['backendlang']['Cancel']) ? $data['backendlang']['backendlang']['Cancel'] :'' }}",
			}
		})
		.prev().on(ace.click_event, function(){
			$(this).next().focus();
		});

		$('.change-status').click(function(){
	        $('.loading-gif').show();
	        var ele = $(this);
	        var row_id = ele.closest('tr').find('.row_id').val();

	        var fd = new FormData();
	        fd.append('row_id', row_id);
	        fd.append('status', ele.data('id'));

	        $.ajax({
	           url: '{{ route("PromotionStatus") }}',
	           type: 'post',
	           data: fd,
	           contentType: false,
	           processData: false,
	           success: function(response){
	                $('.loading-gif').hide();
	                toastr.success("{{ isset($data['backendlang']['backendlang']['Status_Changed']) ? $data['backendlang']['backendlang']['Status_Changed'] :'' }}");
	                window.location.href="{{ route('promotion.promotions.index') }}";
	           },
	        });
	    });
	</script>
@endsection