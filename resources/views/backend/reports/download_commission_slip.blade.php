<table>
	<tr></tr>
	<tr></tr>
	<tr></tr>
	<tr></tr>
	<tr></tr>
	<tr></tr>
</table>
<table class="table table-bordered">
	<tbody>
		<tr>
			<td>&nbsp;</td>
			<td>
				{{ $merchant->f_name }}
			</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td>
				ACCBV (PREVIOUS)
			</td>
			<td></td>
			<td>
				{{ number_format($my_previous_acc_pv, 2) }}
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>
				{{ isset($data['backendlang']['backendlang']['Address']) ? $data['backendlang']['backendlang']['Address'] :'' }}:
			</td>
			<td>
				@if(!empty($merchant->get_default_shipping_address->id))
					{{ !empty($merchant->get_default_shipping_address->address) ? $merchant->get_default_shipping_address->address : '' }}, <br>

					{{ !empty($merchant->get_default_shipping_address->postcode) ? $merchant->get_default_shipping_address->postcode : '' }} 

					{{ !empty($merchant->get_default_shipping_address->city) ? $merchant->get_default_shipping_address->city : '' }}, <br>

					{{ !empty($merchant->get_default_shipping_address->get_states->name) ? $merchant->get_default_shipping_address->get_states->name : '' }} 

					@if(!empty($merchant->get_default_shipping_address->country))
						@if(!empty($merchant->get_default_shipping_address->get_country->country_name))
							{{ $merchant->get_default_shipping_address->get_country->country_name }}
						@else
							{{ $merchant->get_default_shipping_address->country }}
						@endif
					@else
						-
					@endif
				@else
					-
				@endif
			</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td>
				ACC BV (CURRENT)
			</td>
			<td></td>
			<td>
				{{ number_format($my_current_acc_pv, 2) }}
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>
				{{ isset($data['backendlang']['backendlang']['Email']) ? $data['backendlang']['backendlang']['Email'] :'' }}:
			</td>
			<td>
				{{ $merchant->email }}
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>
			</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td>
				SPONSOR CODE</td>
			<td></td>
			<td>
				{{ $upline->display_code }}{{ $upline->display_running_no }}
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td>
				SPONSOR NAME</td>
			<td></td>
			<td>
				{{ $upline->f_name }}
			</td>
		</tr>
		<tr></tr>
		<tr>
			<td>&nbsp;</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td>
				DATE JOINED</td>
			<td></td>
			<td>
				{{ date('d/m/Y', strtotime($merchant->created_at)) }}
			</td>
		</tr>
	</tbody>
</table>
<table class="table table-bordered">
	<thead>
		<tr>
			<th>&nbsp;</th>
			<th>
				<b>
					ID
				</b>
			</th>
			<th>
				<b>
					Name
				</b>
			</th>
			<th>
				<b>
					Rank
				</b>
			</th>
			<th colspan="2">
				<b>
					Per BV
				</b>
			</th>
			<th>
				<b>
					Small Group BV
				</b>
			</th>
			<th>
				<b>
					Accumulated BV
				</b>
			</th>
			<th>
				<b>
					Amount Of Customer
				</b>
			</th>
			<th>
				<b>
					Amount Of Distributor
				</b>
			</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>&nbsp;</td>
			<td>
				{{ $merchant->display_code }}{{ $merchant->display_running_no }}
			</td>
			<td>
				{{ $merchant->f_name }}
			</td>
			<td>
				{{ $merchant->get_agent_level->agent_lvl }}
			</td>
			<td colspan="2">
				{{ $my_personal_pv }}
			</td>
			<td>
				{{ $my_small_group_pv }}
			</td>
			<td>
				{{ $my_total_accumulate_pv }}
			</td>
			<td>
				{{ count($my_recruited_customer_this_month) }}
			</td>
			<td>
				{{ count($my_recruited_distributor_this_month) }}
			</td>
		</tr>
		<tr></tr>
	</tbody>
</table>

<table class="table table-bordered">
	<thead>
		<tr>
			<th>&nbsp;</th>
			<th>
				<b>
					Direct Partner
				</b>
			</th>
		</tr>
		<tr>
			<th></th>
			<th>
				<b>
					ID
				</b>
			</th>
			<th>
				<b>
					Name
				</b>
			</th>
			<th>
				<b>
					Rank
				</b>
			</th>
			<th colspan="2">
				<b>
					Per BV
				</b>
			</th>
			<th>
				<b>
					Small Group BV
				</b>
			</th>
			<th>
				<b>
					Accumulated BV
				</b>
			</th>
			<th>
				<b>
					Amount Of Customer
				</b>
			</th>
			<th>
				<b>
					Amount Of Distributor
				</b>
			</th>
		</tr>
	</thead>
	<tbody>
		@if(!$direct_partners->isEmpty())
			@foreach($direct_partners as $direct_partner)
			<tr>
				<td>&nbsp;</td>
				<td style="border: 1px solid #000;">
					{{ $direct_partner->display_code }}{{ $direct_partner->display_running_no }}
				</td>
				<td style="border: 1px solid #000;">
					{{ $direct_partner->f_name }}
				</td>
				<td style="border: 1px solid #000;">
					{{ $direct_partner->get_agent_level->agent_lvl }}
				</td>
				<td style="border: 1px solid #000;" colspan="2">
					{{ $direct_partner_details[$direct_partner->code]['personal_pv'] }}
				</td>
				<td style="border: 1px solid #000;">
					{{ $direct_partner_details[$direct_partner->code]['small_group_pv'] }}
				</td>
				<td style="border: 1px solid #000;">
					{{ $direct_partner_details[$direct_partner->code]['accumulated_pv'] }}
				</td>
				<td style="border: 1px solid #000;">
					{{ count($direct_partner_details[$direct_partner->code]['customers']) }}
				</td>
				<td style="border: 1px solid #000;">
					{{ count($direct_partner_details[$direct_partner->code]['distributors']) }}
				</td>
			</tr>
			@endforeach
		@else
			<tr>
				<td>&nbsp;</td>
				<td>
					-
				</td>
			</tr>
		@endif
	</tbody>
</table>

<table class="table table-bordered">
	<thead>
		<tr>
			<th>&nbsp;</th>
			<th>
				<b>
					Direct Distributor
				</b>
			</th>
		</tr>
		<tr>
			<th></th>
			<th>
				<b>
					ID
				</b>
			</th>
			<th>
				<b>
					Name
				</b>
			</th>
			<th>
				<b>
					Rank
				</b>
			</th>
			<th colspan="2">
				<b>
					Per BV
				</b>
			</th>
			<th>
				<b>
					Small Group BV
				</b>
			</th>
			<th>
				<b>
					Accumulated BV
				</b>
			</th>
			<th>
				<b>
					Amount Of Customer
				</b>
			</th>
			<th>
				<b>
					Amount Of Distributor
				</b>
			</th>
		</tr>
	</thead>
	<tbody>
		@if(!$direct_distributors->isEmpty())
			@foreach($direct_distributors as $direct_distributor)
			<tr>
				<td>&nbsp;</td>
				<td style="border: 1px solid #000;">
					{{ $direct_distributor->display_code }}{{ $direct_distributor->display_running_no }}
				</td>
				<td style="border: 1px solid #000;">
					{{ $direct_distributor->f_name }}
				</td>
				<td style="border: 1px solid #000;">
					{{ $direct_distributor->get_agent_level->agent_lvl }}
				</td>
				<td style="border: 1px solid #000;" colspan="2">
					{{ $direct_distributor_details[$direct_distributor->code]['personal_pv'] }}
				</td>
				<td style="border: 1px solid #000;">
					{{ $direct_distributor_details[$direct_distributor->code]['small_group_pv'] }}
				</td>
				<td style="border: 1px solid #000;">
					{{ $direct_distributor_details[$direct_distributor->code]['accumulated_pv'] }}
				</td>
				<td style="border: 1px solid #000;">
					{{ count($direct_distributor_details[$direct_distributor->code]['customers']) }}
				</td>
				<td style="border: 1px solid #000;">
					{{ count($direct_distributor_details[$direct_distributor->code]['distributors']) }}
				</td>
			</tr>
			@endforeach
		@else
			<tr>
				<td>&nbsp;</td>
				<td>
					-
				</td>
			</tr>
		@endif
	</tbody>
</table>

<table class="table table-bordered">
	<thead>
		<tr>
			<th>&nbsp;</th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th>
				<b>
					RM
				</b>
			</th>
		</tr>
	</thead>
	<tbody>
		@foreach($setting_referral_bonus as $rbkey => $referral_bonus)
		<tr>
			@php
				if($rbkey == 0){
					$referral_desc = '1st Level';
					$referral_type = '20';

				}elseif($rbkey == 1){
					$referral_desc = '2nd Level';
					$referral_type = '21';

				}else{
					$referral_desc = 'Other';
					$referral_type = '21';
				}

				$referral_count = 0;
				if(isset($comm[$merchant->code][$referral_type])){
					foreach($comm[$merchant->code][$referral_type] as $referral_comm){
						if($referral_comm->comm_pa == $referral_bonus->amount){
							$referral_count++;
						}
					}
				}
			@endphp
			<td>&nbsp;</td>
			@if($rbkey == 0)
				<td rowspan="2">Referral Bonus</td>
			@endif
			<td>{{ $referral_desc }}</td>
			<td>
				<!-- @if(isset($comm[$merchant->code][$referral_type]))
					{{ count($comm[$merchant->code][$referral_type]) }}
				@else
					0
				@endif -->
				{{ $referral_count }}
				x
				{{ $referral_bonus->amount }}
			</td>
			<td>:</td>
			<td>
				<b>
					@if(!empty($comm_total[$merchant->code][$referral_type]))
						{{ number_format($comm_total[$merchant->code][$referral_type], 2) }}
					@else
						0
					@endif
				
				</b>
			</td>
		</tr>
		@endforeach
		<tr>
			<td>&nbsp;</td>
			<td>
				Retail Profit
			</td>
			<td></td>
			<td>
				@if(!empty($comm_total[$merchant->code]['50']))
					{{ number_format($comm_total[$merchant->code]['50'], 2) }}
				@else
					0
				@endif
			
			</td>
			<td>:</td>
			<td>
				<b>
					@if(!empty($comm_total[$merchant->code]['50']))
						{{ number_format($comm_total[$merchant->code]['50'], 2) }}
					@else
						0
					@endif
				
				</b>
			</td>
		</tr>
		<tr>
			@php
				$zero_group_performance_count = 0;
				$zero_group_performance_product_amount = 0;

				if(isset($comm[$merchant->code]['11'])){
					foreach($comm[$merchant->code]['11'] as $group_performance){
						if($group_performance->comm_amount == 0){
							$zero_group_performance_count++;

							$zero_group_performance_product_amount += $group_performance->product_amount;
						}
					}
				}
			@endphp
			<td>&nbsp;</td>
			<td rowspan="4">
				Group Performance Bonus
			</td>
			<td>
				0%
			</td>
			<td>
				<!-- {{ $zero_group_performance_count }} -->
				{{ $zero_group_performance_product_amount }}
				x
				0%
			</td>
			<td>
				:
			</td>
			<td>
				<b>
					0.00
				</b>
			</td>
		</tr>
		@foreach($setting_partnership_bonus as $partnership_bonus)
			@php
				$group_performance_product_amount = 0;
				$group_performance_comm_amount = 0;

				if(isset($comm[$merchant->code]['11'])){
					foreach($comm[$merchant->code]['11'] as $group_performance){
						if($group_performance->comm_pa == $partnership_bonus->percentage){
							$group_performance_product_amount += $group_performance->product_amount;
							$group_performance_comm_amount += $group_performance->comm_amount;
						}
					}
				}
			@endphp
			<tr>
				<td>&nbsp;</td>
				<td>
					{{ $partnership_bonus->percentage }}%
				</td>
				<td>
					{{ $group_performance_product_amount }} 
					x
					{{ $partnership_bonus->percentage }}%
				</td>
				<td>
					:
				</td>
				<td>
					<b>
						<!-- @if(!empty($comm_total[$merchant->code]['11']))
							{{ number_format($comm_total[$merchant->code]['11'], 2) }}
						@else
							0
						@endif -->
						{{ number_format($group_performance_comm_amount, 2) }}
					
					</b>
				</td>
			</tr>
		@endforeach
		@php
			$star_leader_product_amount = 0;
			if(isset($comm[$merchant->code]['30'])){
				foreach($comm[$merchant->code]['30'] as $star_leader){
					if($star_leader->comm_pa == 5){
						$star_leader_product_amount += $star_leader->product_amount;
					}
				}
			}
		@endphp
		<tr>
			<td>&nbsp;</td>
			<td>
				Star Leader
			</td>
			<td>
				1st Generation
			</td>
			<td>
				{{ $star_leader_product_amount }}
				x
				5%
			</td>
			<td>
				:
			</td>
			<td>
				<b>
					@if(!empty($comm_total[$merchant->code]['30']))
						{{ number_format($comm_total[$merchant->code]['30'], 2) }}
					@else
						0
					@endif
				
				</b>
			</td>
		</tr>
		@php
			$three_star_leader_product_amount = 0;
			if(isset($comm[$merchant->code]['31'])){
				foreach($comm[$merchant->code]['31'] as $star_leader){
					if($star_leader->comm_pa == 5){
						$three_star_leader_product_amount += $star_leader->product_amount;
					}
				}
			}
		@endphp
		<tr>
			<td>&nbsp;</td>
			<td>
				Star Leader
			</td>
			<td>
				2nd Generation
			</td>
			<td>
				{{ $three_star_leader_product_amount }}
				x
				5%
			</td>
			<td>
				:
			</td>
			<td>
				<b>
					@if(!empty($comm_total[$merchant->code]['31']))
						{{ number_format($comm_total[$merchant->code]['31'], 2) }}
					@else
						0
					@endif
				
				</b>
			</td>
		</tr>
		@php
			$five_star_leader_product_amount = 0;
			if(isset($comm[$merchant->code]['32'])){
				foreach($comm[$merchant->code]['32'] as $star_leader){
					if($star_leader->comm_pa == 5){
						$five_star_leader_product_amount += $star_leader->product_amount;
					}
				}
			}
		@endphp
		<tr>
			<td>&nbsp;</td>
			<td>
				Star Leader
			</td>
			<td>
				3rd Generation
			</td>
			<td>
				{{ $five_star_leader_product_amount }}
				x
				5%
			</td>
			<td>
				:
			</td>
			<td>
				<b>
					@if(!empty($comm_total[$merchant->code]['32']))
						{{ number_format($comm_total[$merchant->code]['32'], 2) }}
					@else
						0
					@endif
				
				</b>
			</td>
		</tr>
		@php
			$ceo_bonus_product_amount = 0;
			if(isset($comm[$merchant->code]['40'])){
				foreach($comm[$merchant->code]['40'] as $ceo_bonus){
					$ceo_bonus_product_amount += $ceo_bonus->product_amount;
				}
			}
		@endphp
		<tr>
			<td>&nbsp;</td>
			<td>
				Ceo Bonus
			</td>
			<td>
				
			</td>
			<td>
				{{ $ceo_bonus_product_amount }}
				x
				5%
			</td>
			<td>
				:
			</td>
			<td>
				<b>
					@if(!empty($comm_total[$merchant->code]['40']))
						{{ number_format($comm_total[$merchant->code]['40'], 2) }}
					@else
						0
					@endif
				
				</b>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>
				
			</td>
			<td>
				
			</td>
			<td>
				TOTAL
			</td>
			<td>
				:
			</td>
			<td style="border-top: 1px solid #000; border-bottom: 1px double #000;">
				<b>
					{{ $my_all_comm_total }}
				</b>
			</td>
		</tr>
		<tr></tr>
		<tr>
			<td>&nbsp;</td>
			<td style="text-decoration: underline;">
				<b>
					Banking Details
				</b>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>Bank Name:</td>
			<td>
				@if(!empty($merchant->get_default_bank_account->bank_name))
					{{ $merchant->get_default_bank_account->bank_name }}
				@else
					-
				@endif
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>Beneficiary Name:</td>
			<td>
				@if(!empty($merchant->get_default_bank_account->bank_holder_name))
					{{ $merchant->get_default_bank_account->bank_holder_name }}
				@else
					-
				@endif
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>Bank Account No:</td>
			<td>
				@if(!empty($merchant->get_default_bank_account->bank_account))
					{{ $merchant->get_default_bank_account->bank_account }}
				@else
					-
				@endif
			</td>
		</tr>
	</tbody>
</table>