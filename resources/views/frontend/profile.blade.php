@extends('layouts.app')
@section('content')
@include('partial.frontend.profile_header')
<div class="profile-content pb-5 mb-2">
	<div class="container">
		<div class="form-group container-box mb-pb-8">       
			<div class="row">
				<div class="col-6" align="left">
					<b>{{ isset($data['lang']['lang']['my_orders']) ? $data['lang']['lang']['my_orders'] :'我的订单'}}</b>
				</div>
				<div class="col-6" align="right">
					<a href="{{ route('verifying_order') }}">
						<small>{{ isset($data['lang']['lang']['view_all_order']) ? $data['lang']['lang']['view_all_order'] :'查看所有订单'}} <i class="fa fa-angle-right" aria-hidden="true"></i></small>
					</a>
				</div>
			</div>
			<br>
			<div class="row">
				
				<!-- <div class="col pb-3" align="center">
					<a href="{{ route('pending_order') }}" style="position: relative;">
						@if($countPending > 0)
						<span class="badge badge-pill bg-danger" style="position: absolute; right: -5px; top: -7px;">
							{{ $countPending }}
						</span>
						@endif
						<img src="{{ asset('images/profile/JD-01-512.png') }}" width="30">
						<br>
						<span class="profile-word">未付款</span>
					</a>
				</div> -->

				<div class="col pb-3" align="center">
					<a href="{{ route('checkout') }}" style="position: relative; display: inline-block;">
						@if($data['totalCart'] > 0)
						<span class="badge badge-pill bg-danger" style="position: absolute; right: -10px; top: -7px;">
							{{ $data['totalCart'] }}
						</span>
						@endif
						<img src="{{ asset('images/cart.png') }}" width="30">
						<br>
						<span class="profile-word">{{ isset($data['lang']['lang']['to_pay']) ? $data['lang']['lang']['to_pay'] :'待付款'}}</span>
					</a>
				</div>

				<div class="col pb-3" align="center">
					<a href="{{ route('verifying_order') }}" style="position: relative; display: inline-block;">
						@if($countVerifying > 0)
						<span class="badge badge-pill bg-danger" style="position: absolute; right: -5px; top: -7px;">
							{{ $countVerifying }}
						</span>
						@endif
						<img src="{{ asset('images/profile/review.png') }}" width="30">
						<br>
						<span class="profile-word">{{ isset($data['lang']['lang']['to_verify']) ? $data['lang']['lang']['to_verify'] :'待审核'}}</span>
					</a>
				</div>

				<div class="col pb-3" align="center">
					<a href="{{ route('pending_shipping') }}" style="position: relative; display: inline-block;">
						@if($countToShip > 0)
						<span class="badge badge-pill bg-danger" style="position: absolute; right: -5px; top: -7px;">
							{{ $countToShip }}
						</span>
						@endif
						<img src="{{ asset('images/profile/shipment_pending_1017207.png') }}" width="30">
						<br>
						<span class="profile-word">{{ isset($data['lang']['lang']['to_be_delivered']) ? $data['lang']['lang']['to_be_delivered'] :'待出货'}}</span>
					</a>
				</div>

				<div class="col pb-3" align="center">
					<a href="{{ route('pending_receive') }}" style="position: relative; display: inline-block;">
						@if($countToReceive > 0)
						<span class="badge badge-pill bg-danger" style="position: absolute; right: -5px; top: -7px;">
							{{ $countToReceive }}
						</span>
						@endif
						<img src="{{ asset('images/profile/Pending-Truck-Delivery-Commerce-Logistic-Transportation-512.png') }}" width="30">
						<br>
						<span class="profile-word">{{ isset($data['lang']['lang']['to_be_received']) ? $data['lang']['lang']['to_be_received'] :'待收货'}}</span>
					</a>
				</div>

				<div class="col pb-3" align="center">
					<a href="{{ route('completed_order') }}" style="position: relative; display: inline-block;">
						@if($countCompleted > 0)
						<span class="badge badge-pill bg-danger" style="position: absolute; right: -5px; top: -7px;">
							{{ $countCompleted }}
						</span>
						@endif
						<img src="{{ asset('images/profile/Box_Package_Delivery_Shipping_Complete_Check_Done-512.png') }}" width="30">
						<br>
						<span class="profile-word">{{ isset($data['lang']['lang']['completed']) ? $data['lang']['lang']['completed'] :'已完成'}}</span>
					</a>
				</div>
			</div>
		</div>

		
		<!-- <div class="form-group container-box">
			<div class="form-group">
				<div class="row">
					<div class="col-6" align="left">
						<b>My PV</b>
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="row">
					<div class="col" align="center">
						
						<br>
						<span class="wallet-desc profile-word">
							My Total PV
						</span>
					</div>
					<div class="col" align="center">
						
						<br>
						<span class="wallet-desc profile-word">
							This Month Group PV
						</span>
					</div>
				</div>
			</div>
		</div> -->
		
		@if(Auth::guard('agent')->check() && $data['web_setting']->bonus_agent_enable == 1 || 
			Auth::guard('web')->check() && $data['web_setting']->bonus_member_enable == 1 || 
			Auth::guard('admin')->check())
		<div class="form-group container-box">
			<div class="row">
				<div class="col-6" align="left">
					<b>{{ isset($data['lang']['lang']['my_wallet']) ? $data['lang']['lang']['my_wallet'] :'我的钱包'}}</b>
				</div>
				<div class="col-6" align="right">
					<a href="{{ route('wallet') }}">
						<small>{{ isset($data['lang']['lang']['view']) ? $data['lang']['lang']['view'] :'查看'}} <i class="fa fa-angle-right" aria-hidden="true"></i></small>
					</a>
				</div>
			</div>
			<br>
			<div class="row">
				<!-- <div class="col-6" align="center" style="border-right: 1px solid #eee;">
					@if(!Auth::guard('agent')->check())
					<span class="wallet-balance-amount">
						{{ number_format($totalProductBalance, 2) }}
					</span>
					<br>
					<span class="wallet-desc profile-word">Product Wallet</span>
					@endif
				</div> -->

				<div class="col-4" align="center"> 
					RM 
					<span class="wallet-balance-amount">
						{{ number_format($totalCashBalance, 2) }}
					</span>
					<br>
					<span class="wallet-desc profile-word">{{ isset($data['lang']['lang']['cash_wallet']) ? $data['lang']['lang']['cash_wallet'] :'现金钱包'}}</span>
				</div>
				<div class="col-4" align="center">
					RM 
					<span class="wallet-balance-amount">
						{{ number_format($lastMonthCashBalance, 2) }}
					</span>
					<br>
					<span class="wallet-desc profile-word" style="color: black">{{ isset($data['lang']['lang']['prev_month_comm']) ? $data['lang']['lang']['prev_month_comm'] :'上个月佣金提款额'}}</span>
					<span class="wallet-desc profile-word" style="color: red">{{ isset($data['lang']['lang']['require_7_working_days']) ? $data['lang']['lang']['require_7_working_days'] :'注意：需要 7 个工作日处理'}}</span>
				</div>
				<div class="col-4" align="center">
					RM 
					<span class="wallet-balance-amount">
						{{ number_format($GetPendingWithdrawalAmount, 2) }}
					</span>
					<br>
					<span class="wallet-desc profile-word" style="color: black">{{ isset($data['lang']['lang']['withdrawal_verifying']) ? $data['lang']['lang']['withdrawal_verifying'] :'提款等待审核中'}}</span>
				</div>
			</div>
		</div>
		@endif

		@if(Auth::guard('web')->check() && Auth::guard('web')->user()->lvl == 1)
		<!-- <div class="form-group container-box">
			<div class="row">
				<div class="col-6" align="left">
					<b>{{ isset($data['lang']['lang']['my_wallet']) ? $data['lang']['lang']['my_wallet'] :'我的钱包'}}</b>
				</div>
				<div class="col-6" align="right">
					<a href="{{ route('wallet') }}">
						<small>{{ isset($data['lang']['lang']['view']) ? $data['lang']['lang']['view'] :'查看'}} <i class="fa fa-angle-right" aria-hidden="true"></i></small>
					</a>
				</div>
			</div>
			<br>
			<div class="row">
				<div class="col-12" align="center">
					<span class="wallet-balance-amount">
						{{ number_format($GetPVWallet, 2) }} {{ isset($data['lang']['lang']['point']) ? $data['lang']['lang']['point'] :'Point'}}
					</span>
					<br>
					<span class="wallet-desc profile-word" style="color: black">
						{{ isset($data['lang']['lang']['point_wallet']) ? $data['lang']['lang']['point_wallet'] :'Point Wallet'}}
					</span>
				</div>
			</div>
		</div> -->
		@endif

		@if(Auth::guard('agent')->check() && $data['web_setting']->bonus_agent_enable == 1 || 
			Auth::guard('web')->check() && $data['web_setting']->bonus_member_enable == 1 || 
			Auth::guard('admin')->check())
		<div class="form-group container-box">
			<div class="row">
				<div class="col-6" align="left">
					<b>My Sales</b>
				</div>
				<div class="col-6" align="right">
					<a href="{{ route('sales') }}">
						<small>{{ isset($data['lang']['lang']['view']) ? $data['lang']['lang']['view'] :'查看'}} <i class="fa fa-angle-right" aria-hidden="true"></i></small>
					</a>
				</div>
			</div>
			<br>
			<div class="row">
				<div class="col-4" align="center"> 
					<span class="wallet-balance-amount TotalPV">
						RM 0.00 
					</span>
					<br>
					<span class="wallet-desc profile-word">
						Total Accumulated Sales
					</span>
				</div>
				<div class="col-4" align="center"> 
					<span class="wallet-balance-amount monthlyPV">
						RM 0.00
					</span>
					<br>
					<span class="wallet-desc profile-word">
						This Month Sales
					</span>
				</div>
				<div class="col-4 TeamBonus" align="center"> 
					<span class="wallet-balance-amount">
						0%
					</span>
					
					<br>
					<span class="wallet-desc profile-word">
						{{ isset($data['lang']['lang']['current_team_bonus_tier']) ? $data['lang']['lang']['current_team_bonus_tier'] :'Current Team Bonus Tier'}}
					</span>
					<hr>
					<div class="progress">
					  	<div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%">
					    	<span class="sr-only">0% {{ isset($data['lang']['lang']['complete']) ? $data['lang']['lang']['complete'] :'Complete'}}</span>
					  	</div>
					</div>
				</div>
			</div>
		</div>

		<!-- <div class="form-group container-box">
			<div class="row">
				<div class="col-6" align="left">
					<b>{{ isset($data['lang']['lang']['prize_pool_bonus']) ? $data['lang']['lang']['prize_pool_bonus'] :'Prize Pool Bonus'}}</b>
				</div>
			</div>
			<br>
			<div class="row">
				<div class="col-12" align="center"> 
					<span class="wallet-balance-amount">
						RM {{ number_format($prize_pools, 2) }}
					</span>
					<br>
					<span class="wallet-desc profile-word">
						{{ isset($data['lang']['lang']['prize_pool_amount']) ? $data['lang']['lang']['prize_pool_amount'] :'Prize Pool Amount'}}
					</span>
				</div>
			</div>
		</div> -->
		@if((Auth::guard('agent')->check() && $data['web_setting']->bonus_agent_enable == 1 || 
			Auth::guard('web')->check() && $data['web_setting']->bonus_member_enable == 1 || 
			Auth::guard('admin')->check()) &&
			!empty($referral_bonus->amount) && $data['web_setting']->referral_enable == 1)
		<div class="form-group container-box">
			<div class="row">
				<div class="col-6" align="left">
					<b>{{ isset($data['lang']['lang']['referral_bonus']) ? $data['lang']['lang']['referral_bonus'] :'Referral Bonus'}}</b>
				</div>
			</div>
			<br>
			<div class="row">
				<div class="col-6 offset-3" align="center">
					<div class="form-group">
						<div class="progress" style="height: 100%;">
						  <div class="progress-bar" role="progressbar" aria-valuenow="{{ $get_upline->totalDownline }}"
						  aria-valuemin="{{ $min_range }}" aria-valuemax="{{ $max_range }}" style="width:{{ $upline_percentage }}%">
						    {{ $get_upline->totalDownline }}
						  </div>
						</div>
						<div class="start" style="float: left;">
							{{ $min_range }}
						</div>
						<div class="end" style="float: right;">
							{{ $max_range }}
						</div>
					</div>
					<span class="wallet-desc profile-word">
						RM {{ number_format($referral_bonus->amount, 2) }} For Every {{ $direct_downline_no }} Referral
					</span>
				</div>
			</div>
		</div>
		@endif
		@endif

		<div class="form-group container-box profile-setting-list">
			{{-- help agent register --}}
			@if (Auth::guard('agent')->check())
			<li>
				<a href="{{ route('merchant_register', ['r' => 1]) }}" class="profile-word">
					{{ isset($data['lang']['lang']['register_agent']) ? $data['lang']['lang']['register_agent'] :'Register Agent'}}
					<span class="pull-right">
						<i class="fa fa-angle-right" aria-hidden="true"></i>
					</span>
				</a>
			</li>
			@endif
			@if(Auth::guard('admin')->check() || Auth::guard('agent')->check())			
			<li>
				<a href="{{ route('my_stock') }}" class="profile-word">
					{{ isset($data['lang']['lang']['my_products_stock']) ? $data['lang']['lang']['my_products_stock'] :'My Products Stock'}}
					<span class="pull-right">
						<i class="fa fa-angle-right" aria-hidden="true"></i>
					</span>
				</a>
			</li>
			@endif
			<li>
				<a href="{{ route('my_voucher') }}" class="profile-word">
					{{ isset($data['lang']['lang']['my_voucher']) ? $data['lang']['lang']['my_voucher'] :'我的优惠券'}}
					<span class="pull-right">
						<i class="fa fa-angle-right" aria-hidden="true"></i>
					</span>
				</a>
			</li>
			<li>
				<a href="{{ route('wish_list') }}" class="profile-word">
					{{ isset($data['lang']['lang']['favourite']) ? $data['lang']['lang']['favourite'] :'我的收藏'}}
					<span class="pull-right">
						<i class="fa fa-angle-right" aria-hidden="true"></i>
					</span>
				</a>
			</li>
			<li>
				<a href="{{ route('AddressBook.AddressBook.index') }}" class="profile-word">
					{{ isset($data['lang']['lang']['my_delivery_address']) ? $data['lang']['lang']['my_delivery_address'] :'我的收货地址'}}
					<span class="pull-right">
						<i class="fa fa-angle-right" aria-hidden="true"></i>
					</span>
				</a>
			</li>
			@if(Auth::guard('admin')->check() || Auth::guard('agent')->check())
			<!-- <li>
				<a href="{{ route('wallet') }}" class="profile-word">
					{{ isset($data['lang']['lang']['withdrawal']) ? $data['lang']['lang']['withdrawal'] :'提款'}}
					<span class="pull-right">
						<i class="fa fa-angle-right" aria-hidden="true"></i>
					</span>
				</a>
			</li>
			<li>
				<a href="{{ route('bank_account') }}" class="profile-word">
					{{ isset($data['lang']['lang']['bank_account']) ? $data['lang']['lang']['bank_account'] :'银行户口'}}
					<span class="pull-right">
						<i class="fa fa-angle-right" aria-hidden="true"></i>
					</span>
				</a>
			</li> -->
			@endif
			<li>
				<a href="{{ route('my_setting') }}" class="profile-word">
					{{ isset($data['lang']['lang']['acc_setting']) ? $data['lang']['lang']['acc_setting'] :'户口设定'}}
					<span class="pull-right">
						<i class="fa fa-angle-right" aria-hidden="true"></i>
					</span>
				</a>
			</li>
			<li>
				<a href="{{ route('changePassword') }}" class="profile-word">
					{{ isset($data['lang']['lang']['edit_password']) ? $data['lang']['lang']['edit_password'] :'更换密码'}}
					<span class="pull-right">
						<i class="fa fa-angle-right" aria-hidden="true"></i>
					</span>
				</a>
			</li>
			<!-- <li>
				<a href="{{ route('downloadCP58', ['user='.Auth::user()->code]) }}" class="profile-word">
					Download CP58
					<span class="pull-right">
						<i class="fa fa-angle-right" aria-hidden="true"></i>
					</span>
				</a>
			</li> -->
		</div>
		<div class="form-group">
			<a class="btn btn-block profile-word set_button set_text" onclick="event.preventDefault(); $('.loading-gif').show(); document.getElementById('logout-form').submit();">
				<i class="fa fa-sign-out" aria-hidden="true"></i> 
				{{ isset($data['lang']['lang']['logout']) ? $data['lang']['lang']['logout'] :'登出'}}
			</a>
			<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
		</div>
		<div class="mt-2"></div>
	</div>
</div>

<div class="modal fade reminder_voucher" id="reminder_voucher" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="background-color: #fff;">
                <div class="modal-header">
                    <a href="#" class="close-modal">
                        x
                    </a>
                </div>
                <div class="modal-body">
                	@if(!empty($reminders->p_end_date))
                    	<p>{{ isset($data['lang']['lang']['welcome_message_voucher']) ? $data['lang']['lang']['welcome_message_voucher'] :'欢迎您的加入，您目前有优惠券还没使用，您的优惠券将在'}} ({{ $reminders->p_end_date }}) {{ isset($data['lang']['lang']['welcome_message_voucher_2']) ? $data['lang']['lang']['welcome_message_voucher_2'] :'失效，请在失效前使用，谢谢。'}}</p>
                    	<p align="center">
                    		<a href="{{ route('listing') }}" class="btn btn-primary set_button set_text">
                    			{{ isset($data['lang']['lang']['redirect_to_shop']) ? $data['lang']['lang']['redirect_to_shop'] :'前往商城'}}
                    		</a>
                    	</p>
                	@endif
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script type="text/javascript">
    $(document).ready(function () {
        var pendingVoucher = '{{ !empty($reminders->id) ? "1" : "0" }}';
        var remind_voucher = getCookie("remind_voucher");

        if(pendingVoucher == '1' && remind_voucher != '1'){
            $('#reminder_voucher').modal('show');
        }
    });

    $('.close-modal').click(function(e){
        e.preventDefault();

        setCookie('remind_voucher', '1', '1');
    });

    function setCookie(cname, cvalue, exdays) {
      const d = new Date();
      d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
      let expires = "expires="+d.toUTCString();
      document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }

    function getCookie(cname) {
      let name = cname + "=";
      let ca = document.cookie.split(';');
      for(let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') {
          c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
          return c.substring(name.length, c.length);
        }
      }
      return "";
    }

    $.ajax({
        url: '{{ route("LoadTotalPV") }}',
        type: 'get',
        success: function(response){
        	$('.TotalPV').html("RM "+response);
        }
    });

    $.ajax({
        url: '{{ route("LoadMonthlyPV") }}',
        type: 'get',
        success: function(response){
        	$('.monthlyPV').html("RM "+response);
        }
    });

    $.ajax({
        url: '{{ route("getTeamBonusTier") }}',
        type: 'get',
        success: function(response){
        	$('.TeamBonus').html(response);
        }
    });
</script>
@endsection