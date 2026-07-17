<!-- <table class="table table-bordered">
	<thead>
		<tr class="info">
			<th>#</th>
			<th>{{ isset($data['backendlang']['backendlang']['Email']) ? $data['backendlang']['backendlang']['Email'] :'' }}</th>
			<th>{{ isset($data['backendlang']['backendlang']['Email']) ? $data['backendlang']['backendlang']['Email'] :'' }}</th>
			<th>{{ isset($data['backendlang']['backendlang']['Email']) ? $data['backendlang']['backendlang']['Email'] :'' }}</th>
			<th>{{ isset($data['backendlang']['backendlang']['Phone']) ? $data['backendlang']['backendlang']['Phone'] :'' }}</th>
			<th>{{ isset($data['backendlang']['backendlang']['Phone']) ? $data['backendlang']['backendlang']['Phone'] :'' }}</th>
			<th>{{ isset($data['backendlang']['backendlang']['Phone']) ? $data['backendlang']['backendlang']['Phone'] :'' }}</th>
			<th>madid</th>
			<th>fn</th>
			<th>ln</th>
			<th>zip</th>
			<th>ct</th>
			<th>st</th>
			<th>country</th>
			<th>dob</th>
			<th>doby</th>
			<th>gen</th>
			<th>age</th>
			<th>uid</th>
			<th>value</th>
		</tr>
	</thead>
	<tbody>
		@if (!$merchants->isEmpty())
		@foreach($merchants as $key => $merchant)
		@php
		$country_code = $merchant->country_code[0];
		$country_code_2 = !empty($merchant->country_code[1]) ? $merchant->country_code[1] : "0";
		@endphp
		<tr>
			<td>
				{{ $key+1 }}
				<input type="hidden" class="row_id" value="{{ $merchant->id }}">
			</td>
			<td>{{ $merchant->email }}</td>
			<td></td>
			<td></td>
			<td>{{ $country_code }}({{ $country_code_2.substr($merchant->phone, 0, 2) }}){{ substr($merchant->phone, 2) }}</td>
			<td></td>
			<td></td>
			<td></td>
			<td>{{ $merchant->f_name }}</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td>{{ $merchant->gender }}</td>
			<td></td>
			<td></td>
		</tr>
		@endforeach
		@else
		<tr>
			<td colspan="15"><th>{{ isset($data['backendlang']['backendlang']['No_Result_Found']) ? $data['backendlang']['backendlang']['No_Result_Found'] :'' }}</th></td>
		</tr>
		@endif
	</tbody>
</table> -->

<table class="table table-bordered">
	<thead>
		<tr class="info">
			<th>#</th>
			<th>{{ isset($data['backendlang']['backendlang']['Code']) ? $data['backendlang']['backendlang']['Code'] :'' }}
				@if(empty(request('code_desc')) && empty(request('code_asc')))
				<a class="{{ !empty(request('code_desc')) ? 'selected' : '' }}">
					<i class="bi bi-sort-down"></i>
					<input type="hidden" name="sort_data" value="0">
				</a>
				@else
				@if(!empty(request('code_desc')))
				<a class="{{ !empty(request('code_asc')) ? 'selected' : '' }}">
					<i class="bi bi-sort-down"></i>
					<input type="hidden" name="sort_data" value="1">
				</a>
				@elseif(!empty(request('code_asc')))
				<a class="{{ !empty(request('code_desc')) ? 'selected' : '' }}">
					<i class="bi bi-sort-up"></i>
					<input type="hidden" name="sort_data" value="0">
				</a>
				@endif
				@endif
			</th>
			<th>{{ isset($data['backendlang']['backendlang']['Name']) ? $data['backendlang']['backendlang']['Name'] :'' }}
				@if(empty(request('name_desc')) && empty(request('name_asc')))
				<a class="{{ !empty(request('name_desc')) ? 'selected' : '' }}">
					<i class="bi bi-sort-down"></i>
					<input type="hidden" name="sort_data" value="0">
				</a>
				@else
				@if(!empty(request('name_desc')))
				<a class="{{ !empty(request('name_asc')) ? 'selected' : '' }}">
					<i class="bi bi-sort-down"></i>
					<input type="hidden" name="sort_data" value="1">
				</a>
				@elseif(!empty(request('name_asc')))
				<a class="{{ !empty(request('name_desc')) ? 'selected' : '' }}">
					<i class="bi bi-sort-up"></i>
					<input type="hidden" name="sort_data" value="0">
				</a>
				@endif
				@endif
			</th>
			<th>{{ isset($data['backendlang']['backendlang']['Email']) ? $data['backendlang']['backendlang']['Email'] :'' }}
				@if(empty(request('email_desc')) && empty(request('email_asc')))
				<a class="{{ !empty(request('email_desc')) ? 'selected' : '' }}">
					<i class="bi bi-sort-down"></i>
					<input type="hidden" name="sort_data" value="0">
				</a>
				@else
				@if(!empty(request('email_desc')))
				<a class="{{ !empty(request('email_asc')) ? 'selected' : '' }}">
					<i class="bi bi-sort-down"></i>
					<input type="hidden" name="sort_data" value="1">
				</a>
				@elseif(!empty(request('email_asc')))
				<a class="{{ !empty(request('email_desc')) ? 'selected' : '' }}">
					<i class="bi bi-sort-up"></i>
					<input type="hidden" name="sort_data" value="0">
				</a>
				@endif
				@endif
			</th>
			<th>{{ isset($data['backendlang']['backendlang']['Phone']) ? $data['backendlang']['backendlang']['Phone'] :'' }}
				@if(empty(request('phone_desc')) && empty(request('phone_asc')))
				<a class="{{ !empty(request('phone_desc')) ? 'selected' : '' }}">
					<i class="bi bi-sort-down"></i>
					<input type="hidden" name="sort_data" value="0">
				</a>
				@else
				@if(!empty(request('phone_desc')))
				<a class="{{ !empty(request('phone_asc')) ? 'selected' : '' }}">
					<i class="bi bi-sort-down"></i>
					<input type="hidden" name="sort_data" value="1">
				</a>
				@elseif(!empty(request('phone_asc')))
				<a class="{{ !empty(request('phone_desc')) ? 'selected' : '' }}">
					<i class="bi bi-sort-up"></i>
					<input type="hidden" name="sort_data" value="0">
				</a>
				@endif
				@endif
			</th>
			<th>{{ isset($data['backendlang']['backendlang']['Period']) ? $data['backendlang']['backendlang']['Period'] :'' }}</th>
			<th>{{ isset($data['backendlang']['backendlang']['Expired_Date']) ? $data['backendlang']['backendlang']['Expired_Date'] :'' }}</th>
			<th>{{ isset($data['backendlang']['backendlang']['Status']) ? $data['backendlang']['backendlang']['Status'] :'' }}
				@if (empty(request('status_desc')) && empty(request('status_asc')))
				<a class="{{ !empty(request('status_desc')) ? 'selected' : '' }}">
					<i class="bi bi-sort-down"></i>
					<input type="hidden" name="sort_data" value="0">
				</a>
				@else
				@if (!empty(request('status_desc')))
				<a class="{{ !empty(request('status_asc')) ? 'selected' : '' }}">
					<i class="bi bi-sort-down"></i>
					<input type="hidden" name="sort_data" value="1">
				</a>
				@elseif(!empty(request('status_asc')))
				<a class="{{ !empty(request('status_desc')) ? 'selected' : '' }}">
					<i class="bi bi-sort-up"></i>
					<input type="hidden" name="sort_data" value="0">
				</a>
				@endif
				@endif
			</th>
			<th>{{ isset($data['backendlang']['backendlang']['Date']) ? $data['backendlang']['backendlang']['Date'] :'' }}</th>
		</tr>
	</thead>
	<tbody>
		@if (!$merchants->isEmpty())
		@foreach($merchants as $key => $merchant)
		<tr>
			<td>
				{{ $key+1 }}
				<input type="hidden" class="row_id" value="{{ $merchant->id }}">
			</td>
			<td>{{ $merchant->display_code }}{{ $merchant->display_running_no }}</td>
			<td>{{ $merchant->f_name }}</td>
			<td>{{ $merchant->email }}</td>
			<td>(+{{ $merchant->country_code }}){{ $merchant->phone }}</td>
			<td>{{ !empty($merchant->active_period) ? $merchant->active_period : 0 }}</td>
			<td>
				@if(isset($get_merchant_expired_date[$merchant->code]))
				@if(date('Y-m-d') > $get_merchant_expired_date[$merchant->code])
				<span class="important-text">
					{{ $get_merchant_expired_date[$merchant->code] }}
				</span>
				@else
				{{ $get_merchant_expired_date[$merchant->code] }}
				@endif
				@else
				-
				@endif
			</td>
			<td>
				@php
					$expired_date = $get_merchant_expired_date[$merchant->code] ?? null;
				@endphp

				@if($expired_date && date('Y-m-d') > $expired_date)
					<span class="badge bg-dark">{{ $data['backendlang']['backendlang']['Expired'] ? $data['backendlang']['backendlang']['Expired'] : '' }}</span>
				@else
					@if ($merchant->status == 1)
						<span class="badge bg-success">
						{{ $data['backendlang']['backendlang']['Active'] ? $data['backendlang']['backendlang']['Active'] : '' }}
					</span>
				@else
				<span class="badge bg-danger">
					{{ $data['backendlang']['backendlang']['Inactive'] ? $data['backendlang']['backendlang']['Inactive'] : '' }}
				</span>
				@endif
				@endif
			<td>{{ $merchant->created_at }}</td>
			
		</tr>
		@endforeach
		@else
		<tr>
			<td colspan="15">{{ isset($data['backendlang']['backendlang']['No_Result_Found']) ? $data['backendlang']['backendlang']['No_Result_Found'] :'' }}</td>
		</tr>
		@endif
	</tbody>
</table>