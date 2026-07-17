<div class="profile-own-bg set_button">
	<div class="personal-header-info">
		<div class="container {{ Request::segment(1) == 'MyAffiliate' || Request::segment(1) == 'MyCustomer' ? 'mw-100' : '' }}">
			<div class="row">
				<div class="col-4" align="left">
					@if(Request::segment(1) == 'Profile')
					<a href="{{ route('home') }}">
						<p style="color: white;"><i class="fa fa-chevron-left"></i> 
							{{ isset($data['lang']['lang']['home']) ? $data['lang']['lang']['home'] :'首页'}}
						</p>
					</a>
					@else
						@if(Request::segment(1) == 'AddressBook' && !empty(Request::segment(2)))
							<a href="{{ route('AddressBook.AddressBook.index') }}">
								<p style="color: white;"><i class="fa fa-chevron-left"></i> 
									{{ isset($data['lang']['lang']['back']) ? $data['lang']['lang']['back'] :'回到上一页'}}
								</p>
							</a>
						@elseif(Request::segment(1) == 'BankAccount')
							<a href="{{ route('wallet') }}">
								<p style="color: white;"><i class="fa fa-chevron-left"></i> 
									{{ isset($data['lang']['lang']['back']) ? $data['lang']['lang']['back'] :'回到上一页'}}
								</p>
							</a>
						@else
							<a href="{{ route('profile') }}">
								<p style="color: white;"><i class="fa fa-chevron-left"></i> 
									{{ isset($data['lang']['lang']['back']) ? $data['lang']['lang']['back'] :'回到上一页'}}
								</p>
							</a>
						@endif
					@endif
				</div>
				<div class="col-4" align="left">
					<p align="center" class="header-title">
						<!-- {{ isset($data['lang']['lang']['my_personal_account']) ? $data['lang']['lang']['my_personal_account'] :'我的个人账号'}} -->
						@if(Request::segment(1) == 'Profile')
							{{ isset($data['lang']['lang']['my_personal_account']) ? $data['lang']['lang']['my_personal_account'] :'我的个人账号'}}
						@elseif(Request::segment(1) == 'MyWallet')
							{{ isset($data['lang']['lang']['my_wallet']) ? $data['lang']['lang']['my_wallet'] :'我的钱包'}}
						@elseif(Request::segment(1) == 'PendingShipping' || 
								Request::segment(1) == 'PendingReceive' || 
								Request::segment(1) == 'CompletedOrder' || 
								Request::segment(1) == 'CancelledOrder')
							{{ isset($data['lang']['lang']['my_orders']) ? $data['lang']['lang']['my_orders'] :'我的订单'}}
						@elseif(Request::segment(1) == 'MyWishList')
							{{ isset($data['lang']['lang']['favourite']) ? $data['lang']['lang']['favourite'] :'我的收藏'}}
						@elseif(Request::segment(1) == 'AddressBook')
							@if(Request::segment(2) == 'create')
								{{ isset($data['lang']['lang']['create_new_address']) ? $data['lang']['lang']['create_new_address'] :'创建新地址'}}
							@elseif(!empty(Request::segment(2)))
								{{ isset($data['lang']['lang']['address_detail_title']) ? $data['lang']['lang']['address_detail_title'] :'详细地址'}}
							@else
								{{ isset($data['lang']['lang']['my_delivery_address']) ? $data['lang']['lang']['my_delivery_address'] :'我的收货地址'}}
							@endif
						@elseif(Request::segment(1) == 'BankAccount')
							{{ isset($data['lang']['lang']['new_bank_acc']) ? $data['lang']['lang']['new_bank_acc'] :'新银行帐户'}}
						@elseif(Request::segment(1) == 'redeem_product')
							Redeem Product
						@endif
					</p>
				</div>
				<div class="col-4" align="right">
					<select class="global_language" name="global_language" st onchange="changeLanguage(value);">
						<option>{{ isset($data['lang']['lang']['language']) ? $data['lang']['lang']['language'] :'语言'}}</option>
						<option value="1">{{ isset($data['lang']['lang']['chinese']) ? $data['lang']['lang']['chinese'] :'中文'}}</option>
						<option value="2">{{ isset($data['lang']['lang']['english']) ? $data['lang']['lang']['english'] :'英文'}}</option>
					</select>
					<a href="{{ route('my_setting') }}" class="setting-btn">
						<i class="fa fa-cog f-20"></i>
					</a>
				</div>
			</div>

			<div class="form-group">
				<div class="row">
					<div class="col-2">
						<a href="{{ route('profile') }}">
							@if(!empty(Auth::user()->profile_logo))
								<!-- <img src="{{ asset(Auth::user()->profile_logo) }}" width="50" class="profile-logo"> -->
								<div style="background-image: url({{ asset(Auth::user()->profile_logo) }}); width: 50px; height: 50px; border-radius: 100%; background-size: cover; background-position: center; background-repeat: no-repeat;"></div>
							@else
								<img src="{{ asset('images/images.png') }}" width="50" class="profile-logo">
							@endif							
						</a>
					</div>
					<div class="col-10 f-profile-account">
						<a href="{{ route('profile') }}">
							&nbsp;
							<b class="profile-name">{{ Auth::user()->f_name }} {{ Auth::user()->l_name }}</b>
							<br>
							&nbsp;
							<small class="profile-code">{{ isset($data['lang']['lang']['code']) ? $data['lang']['lang']['code'] :'代码'}}: 
								{{ Auth::user()->display_code }}{{ Auth::user()->display_running_no }}
							</small>
							
							<br>
							&nbsp;
							@php
								$langFlag = $_COOKIE['global_language'] ?? ($_COOKIE['global_language'] ?? '0');

								if(!empty($data['getUserDetails']->get_level)){
									if($langFlag == 1){
										$agent_lvl = $data['getUserDetails']->get_level->agent_lvl_cn;
									}else{
										$agent_lvl = $data['getUserDetails']->get_level->agent_lvl;
									}
								}else{
									$agent_lvl = 'Agent';
								}
							@endphp
							<small class="profile-level">
								@if(Auth::guard('web')->check())
									{{ isset($data['lang']['lang']['level']) ? $data['lang']['lang']['level'] :'Level'}}: Customer
								@else
									{{ isset($data['lang']['lang']['level']) ? $data['lang']['lang']['level'] :'Level'}}:
									{{ $agent_lvl }} 
									<!-- {{ !empty($data['getUserDetails']->get_level->agent_lvl) ? $data['getUserDetails']->get_level->agent_lvl : 'Agent' }} -->
								@endif
							</small>
							<br>
							&nbsp;
							<small class="profile-level">
								{{ isset($data['lang']['lang']['upline_name']) ? $data['lang']['lang']['upline_name'] :'推荐人名字'}}: 

								@if(!empty($data['getUserDetails']->get_upline_det->get_user_id_agent_det->code))
									{{ $data['getUserDetails']->get_upline_det->get_user_id_agent_det->f_name }}
								@elseif(!empty($data['getUserDetails']->get_upline_det->get_user_id_member_det->code))
									
									{{ $data['getUserDetails']->get_upline_det->get_user_id_member_det->f_name }}

								@elseif(!empty($data['getUserDetails']->get_upline_det->get_user_id_admin_det->code))
									
									{{ $data['getUserDetails']->get_upline_det->get_user_id_admin_det->f_name }}
									{{ $data['getUserDetails']->get_upline_det->get_user_id_admin_det->l_name }}

								@else
									<span style="color: red;">
										<i class="fa fa-minus"></i>

									</span>
								@endif
							</small>
							<br>
							&nbsp;
							<small class="profile-level">
								{{ isset($data['lang']['lang']['upline_code']) ? $data['lang']['lang']['upline_code'] :'推荐人代码'}}: 

								@if(!empty($data['getUserDetails']->get_upline_det->get_user_id_agent_det->code))
									{{ $data['getUserDetails']->get_upline_det->get_user_id_agent_det->display_code }}{{ $data['getUserDetails']->get_upline_det->get_user_id_agent_det->display_running_no }}
								@elseif(!empty($data['getUserDetails']->get_upline_det->get_user_id_member_det->code))
									
									{{ $data['getUserDetails']->get_upline_det->get_user_id_member_det->display_code }}{{ $data['getUserDetails']->get_upline_det->get_user_id_member_det->display_running_no }}

								@elseif(!empty($data['getUserDetails']->get_upline_det->get_user_id_admin_det->code))
									
									{{ $data['getUserDetails']->get_upline_det->get_user_id_admin_det->display_code }}{{ $data['getUserDetails']->get_upline_det->get_user_id_admin_det->display_running_no }}

								@else
									<span style="color: red;">
										<i class="fa fa-minus"></i>

									</span>
								@endif
							</small>
							<br>
							&nbsp;
							<small class="profile-level">
								{{ isset($data['lang']['lang']['join_date']) ? $data['lang']['lang']['join_date'] :'Join Date'}}: 
								{{ Auth::user()->created_at }}
							</small>
						</a>
					</div>
				</div>
			</div>
			
			@if(Request::segment(1) !== 'MyAffiliate' && Request::segment(1) !== 'MyCustomer')
				@if(Auth::guard('web')->check())
					<div class="form-group container-box sl-personal-header">
						<div class="row">
							<div class="col-4" align="center">
								<a href="{{ route('myqrcode') }}">
									<img src="{{ asset('images/qrcode.png') }}" width="30">
									<br>
									<span class="profile-word">{{ isset($data['lang']['lang']['my_qrcode']) ? $data['lang']['lang']['my_qrcode'] :'我的二维码'}}</span>
								</a>
							</div>

							<div class="col-4" align="center">
								<a href="{{ route('MyCustomer', Auth::user()->code) }}">
									<img src="{{ asset('images/profile/585e4d1ccb11b227491c339b.png') }}" width="30">
									<br>
									<span class="profile-word">{{ isset($data['lang']['lang']['my_customer']) ? $data['lang']['lang']['my_customer'] :'我的顾客'}}</span>
								</a>
							</div>

							<div class="col-4" align="center">
								<a href="{{ route('wallet') }}">
									<img src="{{ asset('images/profile/c3286d4d32fa90ebcf09b488654612b9-wallet-icon-by-vexels.png') }}" width="30">
									<br>
									<span class="profile-word">{{ isset($data['lang']['lang']['my_wallet']) ? $data['lang']['lang']['my_wallet'] :'我的钱包'}}</span>
								</a>
							</div>
						</div>
					</div>
				@else
					@if(Auth::guard('admin')->check() || (Auth::guard('agent')->check() && Auth::guard('agent')->user()->verify_status == 1))
						<div class="form-group container-box sl-personal-header">
							<div class="row">
								<div class="col" align="center">
									<a href="{{ route('myqrcode') }}">
										<img src="{{ asset('images/qrcode.png') }}" width="30">
										<br>
										<span class="profile-word">{{ isset($data['lang']['lang']['my_qrcode']) ? $data['lang']['lang']['my_qrcode'] :'我的二维码'}}</span>
									</a>
								</div>

								<div class="col" align="center">
									<a href="{{ route('MyAffiliate', Auth::user()->code) }}">
										<img src="{{ asset('images/profile/585e4d1ccb11b227491c339b.png') }}" width="30">
										<br>
										<span class="profile-word">{{ isset($data['lang']['lang']['my_team']) ? $data['lang']['lang']['my_team'] :'我的团队'}}</span>
									</a>
								</div>

								<div class="col" align="center">
									<a href="{{ route('MyCustomer', Auth::user()->code) }}">
										<img src="{{ asset('images/profile/585e4d1ccb11b227491c339b.png') }}" width="30">
										<br>
										<span class="profile-word">
											{{ isset($data['lang']['lang']['my_customer']) ? $data['lang']['lang']['my_customer'] :'我的顾客'}}
										</span>
									</a>
								</div>

								<div class="col" align="center">
									<a href="{{ route('wallet') }}">
										<img src="{{ asset('images/profile/c3286d4d32fa90ebcf09b488654612b9-wallet-icon-by-vexels.png') }}" width="30">
										<br>
										<span class="profile-word">{{ isset($data['lang']['lang']['my_wallet']) ? $data['lang']['lang']['my_wallet'] :'我的钱包'}}</span>
									</a>
								</div>
							</div>
						</div>
					@endif
				@endif
			@endif
			
		</div>
	</div>
</div>