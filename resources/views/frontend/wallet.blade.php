@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('assets/css/daterangepicker.min.css') }}" />

@section('css')
<style type="text/css">
    /* The switch - the box around the slider */
    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }

    /* Hide default HTML checkbox */
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    /* The slider */
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked+.slider {
        background-color: #2196F3;
    }

    input:focus+.slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked+.slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }

	input::-webkit-outer-spin-button,
	input::-webkit-inner-spin-button {
		-webkit-appearance: none;
		margin: 0;
	}

	.tab-pane h1, 
	.tab-pane h2, 
	.tab-pane h3,
	.tab-pane h4, 
	.tab-pane h5, 
	.tab-pane h6,
	.tab-pane p, 
	.tab-pane span, 
	.tab-pane.badge {
		line-height: 1.3 !important;
	}

	@media only screen and (max-width: 992px) {
		table {
			white-space: nowrap;
		}
	}
</style>
@endsection

@section('content')
@include('partial.frontend.profile_header')
<div class="profile-content mb-5">
	<div class="container">
		<div class="form-group">
			<div class="row">
				<div class="col-sm-12">
					@if(Auth::guard('agent')->check() && $data['web_setting']->bonus_agent_enable == 1)
					 	@if($website_setting->auto_withdrawal_enable == 1)
							<div class="form-group container-box">
								<div class="row">
									<div class="col-md-6" style="display: flex; align-items: center;">
										<!-- <span style="margin-left: 1em; margin-right: 1em;">{{ isset($data['lang']['lang']['manual_withdrawal']) ? $data['lang']['lang']['manual_withdrawal'] :'手动提款'}}</span> -->
										<h5 style="margin-left: 1em; margin-right: 1em;">{{ isset($data['lang']['lang']['auto_withdrawal']) ? $data['lang']['lang']['auto_withdrawal'] :'自动提款'}}</h5>
										<label class="switch" style="margin-bottom: 0;">
											<input type="checkbox" name="withdrawal_type" class="withdrawal_type" {{ (Auth::guard('agent')->user()->withdrawal_type == 1) ? 'checked' : '' }}>
											<span class="slider round">
												
											</span>
										</label>
									</div>
								</div>
							</div>
						@endif
					@endif
					<div class="form-group container-box">
						<div class="form-group">
							@if($errors->any())
								<div class="alert alert-danger">{!! implode('<br/>', $errors->all(':message')) !!}</div>
							@endif
							<div class="row">
								@if(Auth::guard('agent')->check() && $data['web_setting']->bonus_agent_enable == 1 || 
									Auth::guard('web')->check() && $data['web_setting']->bonus_member_enable == 1 || 
									Auth::guard('admin')->check())
								<div class="col-md-6">
									<form method="POST" action="{{ route('save_wallet') }}" id="withdrawal-form" enctype="multipart/form-data">
										@csrf
										<div class="form-group container-box">
											<h5>
												{{ isset($data['lang']['lang']['cash_wallet_balance']) ? $data['lang']['lang']['cash_wallet_balance'] :'现金钱包余额'}}: RM {{ number_format($CashWallet, 2) }}  
												<span style="font-size: 11px;">
													@if(strpos($CashWallet,".") !== false) (RM {{ number_format($CashWallet, 2) }}) @endif
												</span>
											</h5>
										</div>
										<div class="form-group">
											<label>
												<h5>{{ isset($data['lang']['lang']['default_bank_acc']) ? $data['lang']['lang']['default_bank_acc'] :'Default Bank Account'}}</h5>
											</label>
											<p id="bank_name">
												{{ isset($data['lang']['lang']['bank_name']) ? $data['lang']['lang']['bank_name'] :'Bank Name'}}: {!! !empty($banksDefault->bank_name) ? $banksDefault->bank_name : '<i class="fa fa-minus"></i>' !!}
											</p>
											<p id="bank_holder_name">
												{{ isset($data['lang']['lang']['bank_holder_name']) ? $data['lang']['lang']['bank_holder_name'] :'Bank Holder Name'}}: {!! !empty($banksDefault->bank_holder_name) ? $banksDefault->bank_holder_name : '<i class="fa fa-minus"></i>' !!}
											</p>
											<p id="bank_account">
												{{ isset($data['lang']['lang']['bank_account']) ? $data['lang']['lang']['bank_account'] :'Bank Account'}}: {!! !empty($banksDefault->bank_account) ? $banksDefault->bank_account : '<i class="fa fa-minus"></i>' !!}
											</p>
										</div>
										<div style="height: 11px;"></div>
										<div class="form-group">
											<label>{{ isset($data['lang']['lang']['withdrawal_amount']) ? $data['lang']['lang']['withdrawal_amount'] :'Withdrawal Amount'}} <span class="important-text">*</span></label>
											@if(!empty($website_setting->min_withdrawal_amount) && $website_setting->min_withdrawal_amount > 0)
												<div class="text-muted" style="font-size: 12px;">Minimum: RM {{ number_format($website_setting->min_withdrawal_amount, 2) }}</div>
											@endif
											<input type="number" class="form-control required-field" name="amount" value="{{ old('amount') }}" placeholder="{{ isset($data['lang']['lang']['amount']) ? $data['lang']['lang']['amount'] :'Amount'}}">
										</div>
										<div class="form-group">
											<b id="error-message" class="important-text"></b>
										</div>
										<div class="form-group" align="center">
											<button class="btn btn-primary btn-sm submit-withdrawal btn-block set_button set_text">
												{{ isset($data['lang']['lang']['withdrawal']) ? $data['lang']['lang']['withdrawal'] :'Withdrawal'}}
											</button>
										</div>
									</form>

									<form method="POST" action="{{ route('transfer_cash_to_topup') }}" id="transfer-form" enctype="multipart/form-data">
										@csrf
										<div class="container-box">
											<div class="form-group">
												<label>
													<h5>{{ isset($data['lang']['lang']['transfer_cash_wallet_to_topup_wallet']) ? $data['lang']['lang']['transfer_cash_wallet_to_topup_wallet'] : 'Transfer Cash Wallet To Topup Wallet' }}</h5>
												</label>
												<select class="form-control" name="user_id">
													<option value="{{ Auth::user()->code }}">{{ Auth::user()->f_name }}</option>
													@foreach ($direct_downlines as $direct_downline)
														<option value="{{ $direct_downline->code }}">{{ $direct_downline->f_name }}</option>
													@endforeach
												</select>
											</div>
											<div class="form-group">
												<label>
													{{ isset($data['lang']['lang']['amount']) ? $data['lang']['lang']['amount'] : 'Amount' }}
												</label>
												<input type="number" class="form-control" name="adjust_amount" value="{{ old('adjust_amount') }}" placeholder="{{ isset($data['lang']['lang']['amount']) ? $data['lang']['lang']['amount'] :'Amount'}}" onkeypress="return isNumberKey(event)">
											</div>
											<div class="form-group">
												<label>
													{{ isset($data['lang']['lang']['remark']) ? $data['lang']['lang']['remark'] : 'Remark' }}
												</label>
												<input type="text" class="form-control" name="remark" value="{{ old('remark') }}" placeholder="{{ isset($data['lang']['lang']['remark']) ? $data['lang']['lang']['remark'] :'Remark'}}">
											</div>
											<button class="btn btn-primary btn-sm submit-transfer btn-block set_button set_text">
												{{ isset($data['lang']['lang']['submit']) ? $data['lang']['lang']['submit'] :'Submit'}}
											</button>
										</div>
									</form>
								</div>
								<div class="col-md-6">
									<form method="POST" action="{{ route('submit_topup') }}" id="topup-form" enctype="multipart/form-data">
										@csrf
										<div class="form-group container-box">
											<h5>
												{{ isset($data['lang']['lang']['topup_wallet_balance']) ? $data['lang']['lang']['topup_wallet_balance'] :'充值钱包余额'}}: RM {{ number_format($get_topup_wallet_balance, 2) }}
											</h5>
										</div>
										<div class="">
											<div class="row">
												<div class="col-4" align="center">
													<div class="form-group">
														<a href="#" class="topup_select_button" data-val="100">
															RM 100.00
														</a>
													</div>
												</div>
												<div class="col-4" align="center">
													<div class="form-group">
														<a href="#" class="topup_select_button" data-val="200">
															RM 200.00
														</a>
													</div>
												</div>
												<div class="col-4" align="center">
													<div class="form-group">
														<a href="#" class="topup_select_button" data-val="300">
															RM 300.00
														</a>
													</div>
												</div>
												<div class="col-4" align="center">
													<div class="form-group">
														<a href="#" class="topup_select_button" data-val="500">
															RM 500.00
														</a>
													</div>
												</div>
												<div class="col-4" align="center">
													<div class="form-group">
														<a href="#" class="topup_select_button" data-val="1000">
															RM 1,000.00
														</a>
													</div>
												</div>
												<div class="col-4" align="center">
													<div class="form-group">
														<a href="#" class="topup_select_button" data-val="other">
															{{ isset($data['lang']['lang']['other']) ? $data['lang']['lang']['other'] :'Other'}}
														</a>
													</div>
												</div>
											</div>
										</div>
										<div class="form-group">
											<label>
												{{ isset($data['lang']['lang']['topup_amount']) ? $data['lang']['lang']['topup_amount'] : '充值金额'}} 
												<span class="important-text">*</span>
											</label>
											<input type="number" class="form-control required-field" name="topup_amount" value="{{ old('topup_amount') }}" placeholder="{{ isset($data['lang']['lang']['amount']) ? $data['lang']['lang']['amount'] :'Amount'}}">
										</div>
										<div class="form-group" align="center">
											<button class="btn btn-primary btn-sm topup-btn btn-block set_button set_text" type="button" data-toggle="modal" data-target="#topup-box">
												{{ isset($data['lang']['lang']['topup']) ? $data['lang']['lang']['topup'] :'充值'}}
											</button>
											<div class="modal fade" id="topup-box" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
							                  	<div class="modal-dialog">
							                    	<div class="modal-content">
							                      		<div class="modal-body" align="left">
							                          		<h4>{{ isset($data['lang']['lang']['Top_up_1']) ? $data['lang']['lang']['Top_up_1'] :'Top-up'}}: RM 
							                          			<span class="topup_amount_display">
							                          			</span>
							                          			<input type="hidden" class="topup_amount" name='topup_amount'>
							                          		</h4>
							                          		<hr>
															<div class="form-group product-description">
																<div class="widget-box transparent" id="recent-box">
																	<div class="widget-header">
																		<h4 class="widget-title lighter smaller">
																			<i class="fa fa-credit-card-alt" aria-hidden="true"></i> {{ isset($data['lang']['lang']['select_a_payment']) ? $data['lang']['lang']['select_a_payment'] :'Select a payment'}}
																		</h4>
																		<div class="widget-toolbar no-border">
																			<ul class="nav nav-tabs" id="recent-tab">
																				<li class="parent_payment_method active">
																					<a data-toggle="tab" class="payment_method f-15" data-id="2" href="#cdm-tab">
																						{{ isset($data['lang']['lang']['bank_transfer']) ? $data['lang']['lang']['bank_transfer'] :'Bank Transfer'}}
																					</a>
																				</li>
																			</ul>
																		</div>
																		<input type="hidden" name="selected_payment_method" class="selected_payment_method" value="2">
																	</div>

																	<div class="widget-body">
																		<div class="widget-main padding-4">
																			<div class="tab-content padding-8">
																				<div id="online-tab" class="tab-pane">
																					<div class="form-group">
																						<h4>{{ isset($data['lang']['lang']['select_banks']) ? $data['lang']['lang']['select_banks'] :'Select Banks'}} </h4>
																					</div>
																					<div class="form-group">
																						<div class="row">
																							<div class="col-4" align="center">
																								<label>
																									<input type="radio" name="bank_id" value="1">
																									<img src="{{ asset('images/banks/maybank.jpg') }}">
																								</label>
																							</div>
																							<div class="col-4" align="center">
																								<label>
																									<input type="radio" name="bank_id" value="2">
																									<img src="{{ asset('images/banks/cimb.jpg') }}">
																								</label>
																							</div>
																							<div class="col-4" align="center">
																								<label>
																									<input type="radio" name="bank_id" value="4">
																									<img src="{{ asset('images/banks/rhb.jpg') }}">
																								</label>
																							</div>
																						</div>
																					</div>
																					<br>
																					<div class="form-group">
																						<div class="row">
																							<div class="col-4" align="center">
																							<label>
																								<input type="radio" name="bank_id" value="5">
																								<img src="{{ asset('images/banks/hongleong.jpg') }}">
																							</label>
																							</div>
																							<div class="col-4" align="center">
																								<label>
																									<input type="radio" name="bank_id" value="3">
																									<img src="{{ asset('images/banks/pbe.jpg') }}">
																								</label>
																							</div>
																						</div>
																					</div>

																					<div class="form-group">
																						<b id="error-message-banks" class="important-text"></b>
																					</div>
																				</div>

																				<div id="cdm-tab" class="tab-pane active" align="center">
																					<div class="form-group">
																						<input type="hidden" name="cdm_bank_id" value="10000743">
																						<div class="card border-danger mb-3" style="max-width: 18rem;" align="center">
																							<div class="card-body text-danger">
																								<h5 class="card-title">{{!empty($data['web_setting']->bank_holder_name)?$data['web_setting']->bank_holder_name:'Bank Holder Name'}}</h5>
																								<h5 class="card-title">{{!empty($data['web_setting']->bank_name)?$data['web_setting']->bank_name:'Bank Name'}}</h5>
																								<p class="card-text">{{!empty($data['web_setting']->bank_account_number)?$data['web_setting']->bank_account_number:'Bank Account'}}</p>
																							</div>
																						</div>
																					</div>
																					<div class="form-group bank_details" align="center">
																						
																					</div>
																					<div class="form-group">
																						<input type="file" name="bank_slip" class="form-control" accept="image/*">
																					</div>
																				</div>

																				<div id="wallet-tab" class="tab-pane" align="center">
																					<div class="form-group">
																						<b id="error-balance" class="important-text"></b>
																					</div>
																					<div class="form-group">
																						<h4>
																							<b>
																								{{ isset($data['lang']['lang']['your_cash_wallet_balance']) ? $data['lang']['lang']['your_cash_wallet_balance'] :'Your cash wallet balance'}}: RM {{ number_format($CashWallet, 2) }}
																							</b>
																							<br>
																							@if(!empty($setting_charges->transfer_wallet_charges_amount))
																								@if($setting_charges->transfer_wallet_charges_type == 'Percentage')
																									<small>
																										{{ $setting_charges->transfer_wallet_charges_amount }}% {{ isset($data['lang']['lang']['will_be_charge']) ? $data['lang']['lang']['will_be_charge'] :'will be charge.'}}
																									</small>
																								@else
																									<small>
																										RM {{ $setting_charges->transfer_wallet_charges_amount }} {{ isset($data['lang']['lang']['will_be_charge']) ? $data['lang']['lang']['will_be_charge'] :'will be charge.'}}
																									</small>
																								@endif
																							@endif
																						</h4>
																					</div>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
								                      		<div class="modal-footer">
								                        		<button type="button" class="btn btn-secondary set_button set_text" data-dismiss="modal">
																	Close
																</button>
									                        	<button class="btn btn-primary topup-submit-btn set_button set_text">
									                            	{{ isset($data['lang']['lang']['submit']) ? $data['lang']['lang']['submit'] :'Submit'}}
									                        	</button>
								                      		</div>
							                    		</div>
							                  		</div>
							                  	</div>
							                </div>
										</div>
									</form>
								</div>
								@endif
								<div class="col-md-6 pt-4">
									<div class="form-group container-box">
										<h5>
											{{ isset($data['lang']['lang']['point_wallet_balance']) ? $data['lang']['lang']['point_wallet_balance'] :'Point Wallet Balance'}}: {{ number_format($GetPVWallet, 2) }} {{ isset($data['lang']['lang']['point']) ? $data['lang']['lang']['point'] :'Point'}}
										</h5>
									</div>
								</div>
							</div>
						</div>
						
						
						@if(Auth::guard('agent')->check() && $data['web_setting']->bonus_agent_enable == 1 || 
							Auth::guard('web')->check() && $data['web_setting']->bonus_member_enable == 1 || 
							Auth::guard('admin')->check())
						<hr>
						<h3>{{ isset($data['lang']['lang']['bank_account']) ? $data['lang']['lang']['bank_account'] :'银行户口'}}</h3>
						<div class="form-group" style="overflow: auto;">
							<a href="{{ route('bank_account') }}" class="btn btn-primary btn-sm set_button set_text">
								<i class="fa fa-plus"></i> {{ isset($data['lang']['lang']['add_new_bank_acc']) ? $data['lang']['lang']['add_new_bank_acc'] :'添加新银行'}}
							</a>
							<br>
							<br>
							<table class="table table-bordered">
								<tr>
									<td>{{ isset($data['lang']['lang']['bank_name']) ? $data['lang']['lang']['bank_name'] :'银行名称'}}</td>
									<td>{{ isset($data['lang']['lang']['bank_holder_name']) ? $data['lang']['lang']['bank_holder_name'] :'银行户口持有人'}}</td>
									<td>{{ isset($data['lang']['lang']['bank_acc_no']) ? $data['lang']['lang']['bank_acc_no'] :'银行户口号码'}}</td>
									<td>{{ isset($data['lang']['lang']['default']) ? $data['lang']['lang']['default'] :'默认'}}</td>
									<td></td>
								</tr>
								@if(!$banks->isEmpty())
									@foreach($banks as $bank)
									<tr>
										<td>
											<input type="hidden" name="bid" value="{{ $bank->id }}">
											{{ $bank->bank_name }}
										</td>
										<td>{{ $bank->bank_holder_name }}</td>
										<td>{{ $bank->bank_account }}</td>
										<td>
											<input type="radio" name="default_banks" value="1" {{ ($bank->default_banks == 1) ? 'checked' : '' }}>
										</td>
										<td>
											<a href="{{ route('bank_account_edit', $bank->id) }}">
												<i class="fa fa-pencil"></i> {{ isset($data['lang']['lang']['edit']) ? $data['lang']['lang']['edit'] :'编辑'}}
											</a>
											&nbsp;
											<a href="{{ route('bank_account_delete', $bank->id) }}" style="color: red;">
												<i class="fa fa-pencil"></i> {{ isset($data['lang']['lang']['delete']) ? $data['lang']['lang']['delete'] :'删除'}}
											</a>
										</td>
									</tr>
									@endforeach
								@else
									<tr>
										<td colspan="5">{{ isset($data['lang']['lang']['no_result']) ? $data['lang']['lang']['no_result'] :'没有结果'}}</td>
									</tr>
								@endif
							</table>
						</div>
						@endif
						<hr>
						<h3>{{ isset($data['lang']['lang']['wallet_history']) ? $data['lang']['lang']['wallet_history'] :'钱包历史'}}</h3>
						<form action="{{ route('wallet') }}" method="GET">
							<div class="form-group" style="margin-bottom: 1rem;">
								<div class="row">
									<div class="col-sm-3">
										<div class="form-group">
											<input type="text" class="form-control" name="dates" value="{{ !empty(request('dates')) ? request('dates') : $startDate.' - '.$endDate }}">
										</div>
									</div>
								</div>
							</div>
							<div class="form-group" style="margin-bottom: 1rem;">
								<div class="row">
									<div class="col-sm-4">
										<div class="form-group">
											<button class="btn btn-primary btn-sm set_button set_text">
												<i class="fa fa-search"></i> {{ isset($data['lang']['lang']['search']) ? $data['lang']['lang']['search'] :'Search'}}
											</button>
											<a href="{{ route('wallet') }}" class="btn btn-warning btn-sm set_button set_text">
												<i class="fa fa-refresh"></i> {{ isset($data['lang']['lang']['clear_search']) ? $data['lang']['lang']['clear_search'] :'Clear Search'}}
											</a>
										</div>
									</div>
								</div>
							</div>
							<div class="form-group" style="margin-bottom: 1rem;">
								<div class="row">
									@php
										$dailyDate = date('Y-m-d');
										$MonthlyDate = date('Y-m');
										$YearlyDate = date('Y');
									@endphp
									<div class="col-sm-4 mt-2">
										<div class="form-group">
											<a href="{{ route('wallet', ['today='.$dailyDate]) }}" 
											   class="btn btn-primary btn-primary btn-filter set_button set_text {{ !empty(request('today')) ? 'selected' : '' }}" style="text-transform: none; width: 100%;">
												{{ isset($data['lang']['lang']['daily_record']) ? $data['lang']['lang']['daily_record'] :'Daily Record'}}
												<input type="hidden" name="filter_data" value="0">
												<br>
											</a>
										</div>
									</div>
									<div class="col-sm-4 mt-2">
										<div class="form-group">
											<a href="{{ route('wallet', ['this_month='.$MonthlyDate]) }}" 
											   class="btn btn-primary btn-primary btn-filter set_button set_text {{ !empty(request('this_month')) ? 'selected' : '' }}" style="text-transform: none; width: 100%;">
												{{ isset($data['lang']['lang']['monthly_record']) ? $data['lang']['lang']['monthly_record'] :'Monthly Record'}}
												<input type="hidden" name="filter_data" value="1">
												<br>
											</a>
										</div>
									</div>
									<div class="col-sm-4 mt-2">
										<div class="form-group">
											<a href="{{ route('wallet', ['this_year='.$YearlyDate]) }}" 
											   class="btn btn-primary btn-primary btn-filter set_button set_text {{ !empty(request('this_year')) ? 'selected' : '' }}" style="text-transform: none; width: 100%;">
												{{ isset($data['lang']['lang']['yearly_record']) ? $data['lang']['lang']['yearly_record'] :'Yearly Record'}}
												<input type="hidden" name="filter_data" value="2">
												<br>
											</a>
										</div>
									</div>
								</div>
							</div>
						</form>
						<div class="widget-box transparent" id="recent-box">
							<div class="widget-header">
								<h4 class="widget-title lighter smaller">
									History
								</h4>

								<div class="widget-toolbar no-border">
									<ul class="nav nav-tabs" id="recent-tab">

										@if(Auth::guard('agent')->check() || Auth::guard('admin')->check())
										<li class="parent_payment_method active">
											<a data-toggle="tab" class="payment_method f-15" data-id="2" href="#cash_wallet-tab">
												{{ isset($data['lang']['lang']['cash_wallet']) ? $data['lang']['lang']['cash_wallet'] :'现金钱包'}}
											</a>
										</li>
										@endif
										<li class="parent_payment_method">
											<a data-toggle="tab" class="payment_method f-15" data-id="4" href="#topup_wallet-tab">
												Topup Wallet
											</a>
										</li>
										<li class="parent_payment_method">
											<a data-toggle="tab" class="payment_method f-15" data-id="3" href="#pv-tab">
												Point
											</a>
										</li>
									</ul>
								</div>
							</div>

						<div class="widget-body" style="overflow: auto;">
						<div class="widget-main padding-4">
						<div class="tab-content padding-8">
						@if(Auth::guard('agent')->check() || Auth::guard('admin')->check())
						<div id="cash_wallet-tab" class="tab-pane active">
						<div class="form-group wallet_history" style="overflow: auto;">
							@if(!empty($all))
							<table class="table table-bordered">
								<thead>
									<tr class="info">
										<th>{{ isset($data['lang']['lang']['date_time']) ? $data['lang']['lang']['date_time'] :'Date Time'}}</th>
										<th>{{ isset($data['lang']['lang']['description']) ? $data['lang']['lang']['description'] :'Description'}}</th>
										<th>{{ isset($data['lang']['lang']['in']) ? $data['lang']['lang']['in'] :'In'}}</th>
										<th>{{ isset($data['lang']['lang']['out']) ? $data['lang']['lang']['out'] :'Out'}}</th>
									</tr>
								</thead>
								<tbody>
									@php
										$totalIn = 0;
										$totalOut = 0;
									@endphp
									@foreach($all as $d1 => $d2)
									@php
										$langFlag = $_COOKIE['global_language'] ?? ($_COOKIE['global_language'] ?? '0');

										if($langFlag == 1){
											$desc = $d2['comm_desc_cn'];
										}else{
											$desc = $d2['comm_desc'];
										}
									@endphp
									<tr>
										<td>
											{{ $d2['created_at'] }}
										</td>
										<td>
											@if(!empty($d2['withdrawal_no']))
												<p>
													<b>
														{{ isset($data['lang']['lang']['withdrawal']) ? $data['lang']['lang']['withdrawal'] :'Withdrawal'}}
													</b>
												</p>

												<table class="table">
													<tr>
														<td>
															{{ isset($data['lang']['lang']['withdrawal_amount']) ? $data['lang']['lang']['withdrawal_amount'] :'Withdrawal Amount'}}
														</td>
														<td>
															RM {{ number_format($d2['amount'], 2) }}
														</td>
													</tr>
													@if(!empty($d2['withdrawal_no']))
													<tr>
														<td width="50%">
															{{ isset($data['lang']['lang']['withdrawal_no']) ? $data['lang']['lang']['withdrawal_no'] :'Withdrawal No.'}}
														</td>
														<td width="50%">
															<p href="{{ route('order_detail', $d2['withdrawal_no']) }}" target="_blank">
																{{ $d2['withdrawal_no'] }}
															</p>
														</td>
													</tr>
													@endif
													@if(!empty($d2['product_name']))
													<tr>
														<td>
															{{ isset($data['lang']['lang']['product_name']) ? $data['lang']['lang']['product_name'] :'Product'}}
														</td>
														<td>
															{{ $d2['product_name'] }}
														</td>
													</tr>
													@endif
													<tr>
														<td>
															{{ isset($data['lang']['lang']['total_amount']) ? $data['lang']['lang']['total_amount'] :'Total Amount'}}
														</td>
														<td>
															RM {{ number_format($d2['amount'], 2) }}
														</td>
													</tr>
													<tr>
														<td>
															{{ isset($data['lang']['lang']['bank_slip']) ? $data['lang']['lang']['bank_slip'] :'Bank Slip'}}
														</td>
														<td>
															@if(!empty($d2['withdrawal_slip']))
																<a href="#" data-toggle="modal" data-target="#{{ $d2['withdrawal_no'] }}">
																	<div style="background-image: url({{ asset($d2['withdrawal_slip']) }});" class="h-101">

																	</div>
																</a>
																<div class="modal fade" id="{{ $d2['withdrawal_no'] }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index: 9999;">
																	<div class="modal-dialog">
																		<div class="modal-content">
																			<div class="modal-body">
																				<img src="{{ asset($d2['withdrawal_slip']) }}" width="100%" alt="Bank Slip">
																			</div>
																		</div>
																	</div>
																</div>
															@else
																{{ isset($data['lang']['lang']['no_bank_slip']) ? $data['lang']['lang']['no_bank_slip'] :'没有银行发票'}}
															@endif
														</td>
													</tr>
													<tr>
														<td>
															{{ isset($data['lang']['lang']['status']) ? $data['lang']['lang']['status'] :'Status'}}
														</td>
														<td>
															@if($d2['status'] == '1')
																<span class="badge bg-success"> {{ isset($data['lang']['lang']['approved']) ? $data['lang']['lang']['approved'] :'Approved'}} </span>
															@elseif($d2['status'] == '99')
																<span class="badge badge-info"> {{ isset($data['lang']['lang']['pending']) ? $data['lang']['lang']['pending'] :'Pending'}} </span>
															@elseif($d2['status'] == '98')
																<span class="badge bg-danger"> {{ isset($data['lang']['lang']['cancelled']) ? $data['lang']['lang']['cancelled'] :'Cancelled'}} </span>
															@endif
														</td>
													</tr>
												</table>
											@elseif(!empty($d2['topup_no']))
												<p>
													<b>{{ isset($data['lang']['lang']['topup']) ? $data['lang']['lang']['topup'] :'充值'}}</b>
												</p>
												<table class="table">
													<tr>
														<td>
															{{ isset($data['lang']['lang']['topup_no']) ? $data['lang']['lang']['topup_no'] :'Topup No.'}}
														</td>
														<td>
															{{ $d2['topup_no'] }}
														</td>
													</tr>
													<tr>
														<td>
															{{ isset($data['lang']['lang']['bank_slip']) ? $data['lang']['lang']['bank_slip'] :'Bank Slip'}}
														</td>
														<td>
															@if(!empty($d2['bank_slip']))
																<a href="#" data-toggle="modal" data-target="#{{ $d2['topup_no'] }}">
																	<div style="background-image: url({{ asset($d2['bank_slip']) }});" class="h-101">

																	</div>
																</a>
																<div class="modal fade" id="{{ $d2['topup_no'] }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index: 9999;">
																	<div class="modal-dialog">
																		<div class="modal-content">
																			<div class="modal-body">
																				<img src="{{ asset($d2['bank_slip']) }}" width="100%" alt="Bank Slip">
																			</div>
																		</div>
																	</div>
																</div>
															@else
																{{ isset($data['lang']['lang']['no_bank_slip']) ? $data['lang']['lang']['no_bank_slip'] :'没有银行发票'}}
															@endif
														</td>
													</tr>
													<tr>
														<td>
															{{ isset($data['lang']['lang']['status']) ? $data['lang']['lang']['status'] :'Status'}}
														</td>
														<td>
															@if($d2['status'] == '1')
																<span class="badge bg-success"> {{ isset($data['lang']['lang']['approved']) ? $data['lang']['lang']['approved'] :'Approved'}} </span>
															@elseif($d2['status'] == '99')
																<span class="badge badge-info"> {{ isset($data['lang']['lang']['pending']) ? $data['lang']['lang']['pending'] :'Pending'}} </span>
															@elseif($d2['status'] == '98')
																<span class="badge bg-danger"> {{ isset($data['lang']['lang']['rejected']) ? $data['lang']['lang']['rejected'] :'Rejected'}} </span>
															@endif
														</td>
													</tr>
												</table>
											@elseif(!empty($d2['Tid']))
												{{ isset($data['lang']['lang']['purchase']) ? $data['lang']['lang']['purchase'] :'购买'}}
											@elseif(!empty($d2['get_point_transaction']))
												<p>
													<b>
														{{ isset($data['lang']['lang']['get_point']) ? $data['lang']['lang']['get_point'] :'Get Point'}}
													</b>
												</p>
												<table class="table">
													<tr>
														<td width="50%">
															{{ isset($data['lang']['lang']['transaction_no']) ? $data['lang']['lang']['transaction_no'] :'Transaction No.'}}
														</td>
														<td width="50%">
															@if(!empty($d2['transaction_no']))
															<a href="{{ route('order_detail', $d2['transaction_no']) }}" target="_blank">
																{{ $d2['transaction_no'] }}
															</a>
															@else
															@endif
														</td>
													</tr>
													<tr>
														<td>
															{{ isset($data['lang']['lang']['status']) ? $data['lang']['lang']['status'] :'Status'}}
														</td>
														<td>
															@if($d2['status'] == '1')
																<span class="badge bg-success"> {{ isset($data['lang']['lang']['approved']) ? $data['lang']['lang']['approved'] :'Approved'}} </span>
															@elseif($d2['status'] == '99')
																<span class="badge badge-info"> {{ isset($data['lang']['lang']['pending']) ? $data['lang']['lang']['pending'] :'Pending'}} </span>
															@elseif($d2['status'] == '2')
																<span class="badge bg-danger"> Burned </span>
															@endif
														</td>
													</tr>
												</table>
											@elseif(!empty($d2['used_point']))
												<p>
													<b>
														{{ isset($data['lang']['lang']['point_mall_purchase']) ? $data['lang']['lang']['point_mall_purchase'] :'Point Mall Purchase'}}
													</b>
												</p>
												
												<table class="table">
													<tr>
														<td width="50%">{{ isset($data['lang']['lang']['transaction_no']) ? $data['lang']['lang']['transaction_no'] :'Transaction No'}}</td>
														<td width="50%">{{ $d2['transaction_no'] }}</td>
													</tr>
													<tr>
														<td>
															{{ isset($data['lang']['lang']['status']) ? $data['lang']['lang']['status'] :'Status'}}
														</td>
														<td>
															@if($d2['status'] == '1')
																<span class="badge bg-success"> {{ isset($data['lang']['lang']['approved']) ? $data['lang']['lang']['approved'] :'Approved'}} </span>
															@elseif($d2['status'] == '99')
																<span class="badge badge-info"> {{ isset($data['lang']['lang']['pending']) ? $data['lang']['lang']['pending'] :'Pending'}} </span>
															@elseif($d2['status'] == '2')
																<span class="badge bg-danger"> Burned </span>
															@endif
														</td>
													</tr>
												</table>
											@elseif(!empty($d2['joining_fee_amount']))
												<p>
													<b>
														{{ isset($data['lang']['lang']['joining_fee']) ? $data['lang']['lang']['joining_fee'] :'Joining Fee'}}
													</b>
												</p>
												
												<table class="table">
													<tr>
														<td>{{ isset($data['lang']['lang']['joining_fee_amount']) ? $data['lang']['lang']['joining_fee_amount'] :'Joining Amount'}}</td>
														<td>RM {{ number_format($d2['joining_fee_amount'], 2) }}</td>
													</tr>
													@if(!empty($d2['bonus_amount']))
													<tr>
														<td>{{ isset($data['lang']['lang']['bonus_amount']) ? $data['lang']['lang']['bonus_amount'] :'Bonus Amount'}}</td>
														<td>RM {{ number_format($d2['bonus_amount'], 2) }}</td>
													</tr>
													@endif
													<tr>
														<td>{{ isset($data['lang']['lang']['transaction_no']) ? $data['lang']['lang']['transaction_no'] :'Transaction No'}}</td>
														<td>{{ $d2['transaction_no'] }}</td>
													</tr>
												</table>
											@elseif(!empty($d2['adjust_cash_type']))
												
												<p>
													<b>
														{{ isset($data['lang']['lang']['cash_wallet_adjustment']) ? $data['lang']['lang']['cash_wallet_adjustment'] :'Cash Wallet Adjustment'}}
													</b>
												</p>
												
												<table class="table">
													<tr>
														<td>{{ isset($data['lang']['lang']['created_by']) ? $data['lang']['lang']['created_by'] :'Created By'}}</td>
														<td>{{ $d2['created_by_name'] }}</td>
													</tr>
													<tr>
														<td>{{ isset($data['lang']['lang']['amount']) ? $data['lang']['lang']['amount'] :'Amount'}}</td>
														<td>RM {{ number_format($d2['amount'], 2) }}</td>
													</tr>
													@if($d2['adjust_cash_type'] == 1)
													<tr>
														<td>{{ isset($data['lang']['lang']['type']) ? $data['lang']['lang']['type'] :'Type'}}</td>
														<td>{{ isset($data['lang']['lang']['increase']) ? $data['lang']['lang']['increase'] :'Increase'}}</td>
													</tr>
													@else
													<tr>
														<td>{{ isset($data['lang']['lang']['type']) ? $data['lang']['lang']['type'] :'Type'}}</td>
														<td>{{ isset($data['lang']['lang']['decrease']) ? $data['lang']['lang']['decrease'] :'Decrease'}}</td>
													</tr>
													@endif
													<tr>
														<td>{{ isset($data['lang']['lang']['Remark']) ? $data['lang']['lang']['Remark'] :''}}</td>
														<td>{{ $d2['remark'] }}</td>
													</tr>
												</table>
											@elseif(!empty($d2['adjust_topup_type']))
												
												<p>
													<b>
														{{ isset($data['lang']['lang']['topup_wallet_adjustment']) ? $data['lang']['lang']['topup_wallet_adjustment'] :'Topup Wallet Adjustment'}}
													</b>
												</p>
												
												<table class="table">
													<tr>
														<td>{{ isset($data['lang']['lang']['created_by']) ? $data['lang']['lang']['created_by'] :'Created By'}}</td>
														<td>{{ $d2['created_by_name'] }}</td>
													</tr>
													<tr>
														<td>{{ isset($data['lang']['lang']['amount']) ? $data['lang']['lang']['amount'] :'Amount'}}</td>
														<td>RM {{ number_format($d2['amount'], 2) }}</td>
													</tr>
													@if($d2['adjust_topup_type'] == 1)
													<tr>
														<td>{{ isset($data['lang']['lang']['type']) ? $data['lang']['lang']['type'] :'Type'}}</td>
														<td>{{ isset($data['lang']['lang']['increase']) ? $data['lang']['lang']['increase'] :'Increase'}}</td>
													</tr>
													@else
													<tr>
														<td>{{ isset($data['lang']['lang']['type']) ? $data['lang']['lang']['type'] :'Type'}}</td>
														<td>{{ isset($data['lang']['lang']['decrease']) ? $data['lang']['lang']['decrease'] :'Decrease'}}</td>
													</tr>
													@endif
													<tr>
														<td>{{ isset($data['lang']['lang']['Remark']) ? $data['lang']['lang']['Remark'] :''}}</td>
														<td>{{ $d2['remark'] }}</td>
													</tr>
												</table>
											@elseif(!empty($d2['comm_pa_type']))
												<p>
													<b>
														{{ $desc }}
														@if($d2['comm_pa_type'] == 'Percentage')
															({{ $d2['comm_pa'] }}%)
														@else
															(RM {{ $d2['comm_pa'] }})
														@endif
													</b>
												</p>
												
												<table class="table">
													<tr>
														<td width="50%">
															{{ isset($data['lang']['lang']['amount']) ? $data['lang']['lang']['amount'] :'Amount'}}
														</td>
														<td width="50%">
															RM {{ number_format($d2['product_amount'], 2) }}
														</td>
													</tr>
													@if(!empty($d2['transaction_no']))
													<tr>
														<td width="50%">
															{{ isset($data['lang']['lang']['transaction_no']) ? $data['lang']['lang']['transaction_no'] :'Transaction No.'}}
														</td>
														<td width="50%">
															<a href="{{ route('order_detail', $d2['transaction_no']) }}" target="_blank">
																{{ $d2['transaction_no'] }}
															</a>
														</td>
													</tr>
													@endif
													@if(!empty($d2['product_name']))
													<tr>
														<td>
															{{ isset($data['lang']['lang']['product_name']) ? $data['lang']['lang']['product_name'] :'Product'}}
														</td>
														<td>
															{{ $d2['product_name'] }}
														</td>
													</tr>
													@endif
													
													@if(!empty($d2['user_by']))
													<tr>
														<td>
															{{ isset($data['lang']['lang']['downline']) ? $data['lang']['lang']['downline'] :'Downline'}}
														</td>
														<td>
															{{ $d2['downline_by'] }} ({{ $d2['user_by'] }})
														</td>
													</tr>
													@endif
													<tr>
														<td>
															{{ isset($data['lang']['lang']['status']) ? $data['lang']['lang']['status'] :'Status'}}
														</td>
														<td>
															@if($d2['status'] == '1')
																<span class="badge bg-success"> {{ isset($data['lang']['lang']['approved']) ? $data['lang']['lang']['approved'] :'Approved'}} </span>
															@elseif($d2['status'] == '99')
																<span class="badge badge-info"> {{ isset($data['lang']['lang']['pending']) ? $data['lang']['lang']['pending'] :'Pending'}} </span>
															@elseif($d2['status'] == '2')
																@if(!empty($d2['burned']) && $d2['burned'] == 1)
																	<span class="badge bg-danger"> Burned </span>
																@else
																	<span class="badge bg-danger"> {{ isset($data['lang']['lang']['cancelled']) ? $data['lang']['lang']['cancelled'] :'Cancelled'}} </span>
																@endif
															@endif
														</td>
													</tr>
												</table>
											@elseif(!empty($d2['transfer_amount']))
												<p>
													<b>
														{{ isset($data['lang']['lang']['transfer_cash_wallet_to_topup_wallet']) ? $data['lang']['lang']['transfer_cash_wallet_to_topup_wallet'] :'Transfer Cash Wallet To Topup Wallet'}}
													</b>
												</p>
												
												<table class="table">
													<tr>
														<td>{{ isset($data['lang']['lang']['amount']) ? $data['lang']['lang']['amount'] :'Amount'}}</td>
														<td>RM {{ number_format($d2['transfer_amount'], 2) }}</td>
													</tr>
													<tr>
														<td>{{ isset($data['lang']['lang']['remark']) ? $data['lang']['lang']['remark'] :'Remark'}}</td>
														<td>{{ $d2['remark'] }}</td>
													</tr>
													@if (!empty($d2['transfer_to_agent_name']))
														<tr>
															<td>{{ isset($data['lang']['lang']['transfer_to']) ? $data['lang']['lang']['transfer_to'] :'Transfer To'}}</td>
															<td>
																{{ $d2['transfer_to_agent_name'] }} ({{ $d2['user_id'] }})
															</td>
														</tr>
													@endif
													@if (!empty($d2['transfer_from_agent_name']))
														<tr>
															<td>{{ isset($data['lang']['lang']['transfer_from']) ? $data['lang']['lang']['transfer_from'] :'Transfer From'}}</td>
															<td>
																{{ $d2['transfer_from_agent_name'] }} ({{ $d2['user_by'] }})
															</td>
														</tr>
													@endif
													<tr>
														<td>
															{{ isset($data['lang']['lang']['status']) ? $data['lang']['lang']['status'] :'Status'}}
														</td>
														<td>
															@if($d2['status'] == '1')
																<span class="badge bg-success"> {{ isset($data['lang']['lang']['approved']) ? $data['lang']['lang']['approved'] :'Approved'}} </span>
															@elseif($d2['status'] == '99')
																<span class="badge badge-info"> {{ isset($data['lang']['lang']['pending']) ? $data['lang']['lang']['pending'] :'Pending'}} </span>
															@elseif($d2['status'] == '2')
																<span class="badge bg-danger"> {{ isset($data['lang']['lang']['cancelled']) ? $data['lang']['lang']['cancelled'] :'Cancelled'}} </span>
															@endif
														</td>
													</tr>
												</table>
											@endif
										</td>
										<td>
											@if(!empty($d2['withdrawal_no']))
												0.00
											@elseif(!empty($d2['Tid']))
												0.00
											@elseif(!empty($d2['used_point']))
												0.00
											@elseif(!empty($d2['get_point_transaction']))
												{{ number_format($d2['totalPoint'], 2) }} {{ isset($data['lang']['lang']['point']) ? $data['lang']['lang']['point'] :'Point'}}
											@elseif(!empty($d2['topup_no']))
												RM {{ number_format($d2['amount'], 2) }}
											@elseif(!empty($d2['joining_fee_amount']))
												RM {{ number_format($d2['joining_fee_amount'] + $d2['bonus_amount'], 2) }}
											@elseif(!empty($d2['adjust_cash_type']))
												@if($d2['adjust_cash_type'] == 1)
													RM {{ number_format($d2['amount'], 2) }}
												@else
													0.00
												@endif
											@elseif(!empty($d2['adjust_topup_type']))
												@if($d2['adjust_topup_type'] == 1)
													RM {{ number_format($d2['amount'], 2) }}
												@else
													0.00
												@endif
											@elseif(!empty($d2['adjust_point_type']))
												@if($d2['adjust_point_type'] == 1)
													{{ number_format($d2['amount'], 2) }} {{ isset($data['lang']['lang']['point']) ? $data['lang']['lang']['point'] :'Point'}}
												@else
													0.00
												@endif
											@else
												RM {{ number_format($d2['comm_amount'], 2) }}
											@endif
										</td>
										<td>
											@if(!empty($d2['withdrawal_no']))
												RM {{ number_format($d2['amount'], 2) }}
											@elseif(!empty($d2['Tid']))
												RM{{ number_format($d2['grand_total'], 2) }}
											@elseif(!empty($d2['transfer_amount']))
    											RM {{ number_format($d2['transfer_amount'], 2) }}	
											@elseif(!empty($d2['used_point']))
												{{ number_format($d2['used_point'], 2) }} {{ isset($data['lang']['lang']['point']) ? $data['lang']['lang']['point'] :'Point'}}
											@elseif(!empty($d2['get_point_transaction']))
												0.00
											@elseif(!empty($d2['topup_no']))
												0.00
											@elseif(!empty($d2['joining_fee_amount']))
												0.00
											@elseif(!empty($d2['adjust_cash_type']))
												@if($d2['adjust_cash_type'] == 1)
													0.00
												@else
													RM {{ number_format($d2['amount'], 2) }}
												@endif
											@elseif(!empty($d2['adjust_topup_type']))
												@if($d2['adjust_topup_type'] == 1)
													RM {{ number_format($d2['amount'], 2) }}
												@else
													0.00
												@endif
											@else
												0.00
											@endif
										</td>
									</tr>
									@php
									// if(!empty($d2['topup_no'])){
									// 		$totalIn += $d2['amount'];
									// 	}elseif(!empty($d2['comm_amount'])){
									// 		$totalIn += $d2['comm_amount'];
									// 	}elseif(!empty($d2['get_point_transaction'])){
									// 		$totalIn += $d2['totalPoint'];
									// 	}
										if(!empty($d2['comm_amount'])){
											$totalIn += $d2['comm_amount'];
										}
										elseif(!empty($d2['adjust_cash_type']) && $d2['adjust_cash_type'] == 1){
											$totalIn += $d2['amount'];
										}

										if(!empty($d2['withdrawal_no'])){
											$totalOut += $d2['amount'];
										}elseif(!empty($d2['Tid'])){
											$totalOut += $d2['grand_total'];
										}elseif(!empty($d2['pv_purchase_id'])){
											$totalOut += $d2['grand_total'];
										}elseif(!empty($d2['adjust_cash_type']) && $d2['adjust_cash_type'] == 2){
											$totalOut += $d2['amount'];
										}
										elseif(!empty($d2['transfer_amount'])){
											$totalOut += $d2['transfer_amount'];
										}
										
									@endphp
									@endforeach
									<!-- <tr class="warning">
										<td colspan="2">
											<b>Summary</b>
										</td>
										<td>
											{{ number_format($totalIn, 2) }}
										</td>
										<td>
											RM {{ number_format($totalOut, 2) }}
										</td>
									</tr> -->
								</tbody>
							</table>
							@else
								<div class="container-small-box" align="center">

								
								{{ isset($data['lang']['lang']['no_result']) ? $data['lang']['lang']['no_result'] :'没有结果'}}
								</div>
							@endif
						</div>
					</div>
					<div id="topup_wallet-tab" class="tab-pane">
						<div class="form-group wallet_history" style="overflow: auto;">
							@if(!empty($all_topup))
							<table class="table table-bordered">
								<thead>
									<tr class="info">
										<th>{{ isset($data['lang']['lang']['date_time']) ? $data['lang']['lang']['date_time'] :'Date Time'}}</th>
										<th>{{ isset($data['lang']['lang']['description']) ? $data['lang']['lang']['description'] :'Description'}}</th>
										<th>{{ isset($data['lang']['lang']['in']) ? $data['lang']['lang']['in'] :'In'}}</th>
										<th>{{ isset($data['lang']['lang']['out']) ? $data['lang']['lang']['out'] :'Out'}}</th>
									</tr>
								</thead>
								<tbody>
									@php
										$totalIn = 0;
										$totalOut = 0;
									@endphp
									@foreach($all_topup as $d1 => $d2)
									@php
										$desc = $d2['comm_desc'];
									@endphp
									<tr>
										<td>
											{{ $d2['created_at'] }}
										</td>
										<td>
											@if(!empty($d2['withdrawal_no']))
												<p>
													<b>
														{{ isset($data['lang']['lang']['withdrawal']) ? $data['lang']['lang']['withdrawal'] :'Withdrawal'}}
													</b>
												</p>

												<table class="table">
													<tr>
														<td>
															{{ isset($data['lang']['lang']['withdrawal_amount']) ? $data['lang']['lang']['withdrawal_amount'] :'Withdrawal Amount'}}
														</td>
														<td>
															RM {{ number_format($d2['amount'], 2) }}
														</td>
													</tr>
													@if(!empty($d2['withdrawal_no']))
													<tr>
														<td width="50%">
															{{ isset($data['lang']['lang']['withdrawal_no']) ? $data['lang']['lang']['withdrawal_no'] :'Withdrawal No.'}}
														</td>
														<td width="50%">
															<p href="{{ route('order_detail', $d2['withdrawal_no']) }}" target="_blank">
																{{ $d2['withdrawal_no'] }}
															</p>
														</td>
													</tr>
													@endif
													@if(!empty($d2['product_name']))
													<tr>
														<td>
															{{ isset($data['lang']['lang']['product_name']) ? $data['lang']['lang']['product_name'] :'Product'}}
														</td>
														<td>
															{{ $d2['product_name'] }}
														</td>
													</tr>
													@endif
													<tr>
														<td>
															{{ isset($data['lang']['lang']['total_amount']) ? $data['lang']['lang']['total_amount'] :'Total Amount'}}
														</td>
														<td>
															RM {{ number_format($d2['amount'], 2) }}
														</td>
													</tr>
													<tr>
														<td>
															{{ isset($data['lang']['lang']['bank_slip']) ? $data['lang']['lang']['bank_slip'] :'Bank Slip'}}
														</td>
														<td>
															@if(!empty($d2['withdrawal_slip']))
																<a href="#" data-toggle="modal" data-target="#{{ $d2['withdrawal_no'] }}">
																	<div style="background-image: url({{ asset($d2['withdrawal_slip']) }});" class="h-101">

																	</div>
																</a>
																<div class="modal fade" id="{{ $d2['withdrawal_no'] }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index: 9999;">
																	<div class="modal-dialog">
																		<div class="modal-content">
																			<div class="modal-body">
																				<img src="{{ asset($d2['withdrawal_slip']) }}" width="100%" alt="Bank Slip">
																			</div>
																		</div>
																	</div>
																</div>
															@else
																{{ isset($data['lang']['lang']['no_bank_slip']) ? $data['lang']['lang']['no_bank_slip'] :'没有银行发票'}}
															@endif
														</td>
													</tr>
													<tr>
														<td>
															{{ isset($data['lang']['lang']['status']) ? $data['lang']['lang']['status'] :'Status'}}
														</td>
														<td>
															@if($d2['status'] == '1')
																<span class="badge bg-success"> {{ isset($data['lang']['lang']['approved']) ? $data['lang']['lang']['approved'] :'Approved'}} </span>
															@elseif($d2['status'] == '99')
																<span class="badge badge-info"> {{ isset($data['lang']['lang']['pending']) ? $data['lang']['lang']['pending'] :'Pending'}} </span>
															@elseif($d2['status'] == '98')
																<span class="badge bg-danger"> {{ isset($data['lang']['lang']['cancelled']) ? $data['lang']['lang']['cancelled'] :'Cancelled'}} </span>
															@endif
														</td>
													</tr>
												</table>
											@elseif(!empty($d2['topup_no']))
												<p>
													<b>{{ isset($data['lang']['lang']['topup']) ? $data['lang']['lang']['topup'] :'充值'}}</b>
												</p>
												<table class="table">
													<tr>
														<td>
															{{ isset($data['lang']['lang']['topup_no']) ? $data['lang']['lang']['topup_no'] :'Topup No.'}}
														</td>
														<td>
															{{ $d2['topup_no'] }}
														</td>
													</tr>
													<tr>
														<td>
															{{ isset($data['lang']['lang']['bank_slip']) ? $data['lang']['lang']['bank_slip'] :'Bank Slip'}}
														</td>
														<td>
															@if(!empty($d2['bank_slip']))
																<a href="#" data-toggle="modal" data-target="#{{ $d2['topup_no'] }}">
																	<div style="background-image: url({{ asset($d2['bank_slip']) }});" class="h-101">

																	</div>
																</a>
																<div class="modal fade" id="{{ $d2['topup_no'] }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index: 9999;">
																	<div class="modal-dialog">
																		<div class="modal-content">
																			<div class="modal-body">
																				<img src="{{ asset($d2['bank_slip']) }}" width="100%" alt="Bank Slip">
																			</div>
																		</div>
																	</div>
																</div>
															@else
																{{ isset($data['lang']['lang']['no_bank_slip']) ? $data['lang']['lang']['no_bank_slip'] :'没有银行发票'}}
															@endif
														</td>
													</tr>
													<tr>
														<td>
															{{ isset($data['lang']['lang']['status']) ? $data['lang']['lang']['status'] :'Status'}}
														</td>
														<td>
															@if($d2['status'] == '1')
																<span class="badge bg-success"> {{ isset($data['lang']['lang']['approved']) ? $data['lang']['lang']['approved'] :'Approved'}} </span>
															@elseif($d2['status'] == '99')
																<span class="badge badge-info"> {{ isset($data['lang']['lang']['pending']) ? $data['lang']['lang']['pending'] :'Pending'}} </span>
															@elseif($d2['status'] == '98')
																<span class="badge bg-danger"> {{ isset($data['lang']['lang']['rejected']) ? $data['lang']['lang']['rejected'] :'Rejected'}} </span>
															@endif
														</td>
													</tr>
												</table>
											@elseif(!empty($d2['Tid']))
												{{ isset($data['lang']['lang']['purchase']) ? $data['lang']['lang']['purchase'] :'购买'}}
											@elseif(!empty($d2['get_point_transaction']))
												<p>
													<b>
														{{ isset($data['lang']['lang']['get_point']) ? $data['lang']['lang']['get_point'] :'Get Point'}}
													</b>
												</p>
												<table class="table">
													<tr>
														<td width="50%">
															{{ isset($data['lang']['lang']['transaction_no']) ? $data['lang']['lang']['transaction_no'] :'Transaction No.'}}
														</td>
														<td width="50%">
															@if(!empty($d2['transaction_no']))
															<a href="{{ route('order_detail', $d2['transaction_no']) }}" target="_blank">
																{{ $d2['transaction_no'] }}
															</a>
															@else
															@endif
														</td>
													</tr>
													<tr>
														<td>
															{{ isset($data['lang']['lang']['status']) ? $data['lang']['lang']['status'] :'Status'}}
														</td>
														<td>
															@if($d2['status'] == '1')
																<span class="badge bg-success"> {{ isset($data['lang']['lang']['approved']) ? $data['lang']['lang']['approved'] :'Approved'}} </span>
															@elseif($d2['status'] == '99')
																<span class="badge badge-info"> {{ isset($data['lang']['lang']['pending']) ? $data['lang']['lang']['pending'] :'Pending'}} </span>
															@elseif($d2['status'] == '2')
																<span class="badge bg-danger"> {{ isset($data['lang']['lang']['cancelled']) ? $data['lang']['lang']['cancelled'] :'Cancelled'}} </span>
															@endif
														</td>
													</tr>
												</table>
											@elseif(!empty($d2['used_point']))
												<p>
													<b>
														{{ isset($data['lang']['lang']['point_mall_purchase']) ? $data['lang']['lang']['point_mall_purchase'] :'Point Mall Purchase'}}
													</b>
												</p>

												<table class="table">
													<tr>
														<td width="50%">
															{{ isset($data['lang']['lang']['transaction_no']) ? $data['lang']['lang']['transaction_no'] :'Transaction No'}}
														</td>
														<td width="50%">
															{{ $d2['transaction_no'] }}
														</td>
													</tr>
												</table>
											@elseif(!empty($d2['joining_fee_amount']))
												<p>
													<b>
														{{ isset($data['lang']['lang']['joining_fee']) ? $data['lang']['lang']['joining_fee'] :'Joining Fee'}}
													</b>
												</p>
												
												<table class="table">
													<tr>
														<td>{{ isset($data['lang']['lang']['joining_fee_amount']) ? $data['lang']['lang']['joining_fee_amount'] :'Joining Amount'}}</td>
														<td>RM {{ number_format($d2['joining_fee_amount'], 2) }}</td>
													</tr>
													@if(!empty($d2['bonus_amount']))
													<tr>
														<td>{{ isset($data['lang']['lang']['bonus_amount']) ? $data['lang']['lang']['bonus_amount'] :'Bonus Amount'}}</td>
														<td>RM {{ number_format($d2['bonus_amount'], 2) }}</td>
													</tr>
													@endif
													<tr>
														<td>{{ isset($data['lang']['lang']['transaction_no']) ? $data['lang']['lang']['transaction_no'] :'Transaction No'}}</td>
														<td>{{ $d2['transaction_no'] }}</td>
													</tr>
												</table>
											@elseif(!empty($d2['adjust_cash_type']))
												
												<p>
													<b>
														{{ isset($data['lang']['lang']['cash_wallet_adjustment']) ? $data['lang']['lang']['cash_wallet_adjustment'] :'Cash Wallet Adjustment'}}
													</b>
												</p>
												
												<table class="table">
													<tr>
														<td>{{ isset($data['lang']['lang']['created_by']) ? $data['lang']['lang']['created_by'] :'Created By'}}</td>
														<td>{{ $d2['created_by_name'] }}</td>
													</tr>
													<tr>
														<td>{{ isset($data['lang']['lang']['amount']) ? $data['lang']['lang']['amount'] :'Amount'}}</td>
														<td>RM {{ number_format($d2['amount'], 2) }}</td>
													</tr>
													@if($d2['adjust_cash_type'] == 1)
													<tr>
														<td>{{ isset($data['lang']['lang']['type']) ? $data['lang']['lang']['type'] :'Type'}}</td>
														<td>{{ isset($data['lang']['lang']['increase']) ? $data['lang']['lang']['increase'] :'Increase'}}</td>
													</tr>
													@else
													<tr>
														<td>{{ isset($data['lang']['lang']['type']) ? $data['lang']['lang']['type'] :'Type'}}</td>
														<td>{{ isset($data['lang']['lang']['decrease']) ? $data['lang']['lang']['decrease'] :'Decrease'}}</td>
													</tr>
													@endif
													<tr>
														<td>{{ isset($data['lang']['lang']['Remark']) ? $data['lang']['lang']['Remark'] :''}}</td>
														<td>{{ $d2['remark'] }}</td>
													</tr>
												</table>
											@elseif(!empty($d2['adjust_topup_type']))
												
												<p>
													<b>
														{{ isset($data['lang']['lang']['topup_wallet_adjustment']) ? $data['lang']['lang']['topup_wallet_adjustment'] :'Topup Wallet Adjustment'}}
													</b>
												</p>
												
												<table class="table">
													<tr>
														<td>{{ isset($data['lang']['lang']['created_by']) ? $data['lang']['lang']['created_by'] :'Created By'}}</td>
														<td>{{ $d2['created_by_name'] }}</td>
													</tr>
													<tr>
														<td>{{ isset($data['lang']['lang']['amount']) ? $data['lang']['lang']['amount'] :'Amount'}}</td>
														<td>RM {{ number_format($d2['amount'], 2) }}</td>
													</tr>
													@if($d2['adjust_topup_type'] == 1)
													<tr>
														<td>{{ isset($data['lang']['lang']['type']) ? $data['lang']['lang']['type'] :'Type'}}</td>
														<td>{{ isset($data['lang']['lang']['increase']) ? $data['lang']['lang']['increase'] :'Increase'}}</td>
													</tr>
													@else
													<tr>
														<td>{{ isset($data['lang']['lang']['type']) ? $data['lang']['lang']['type'] :'Type'}}</td>
														<td>{{ isset($data['lang']['lang']['decrease']) ? $data['lang']['lang']['decrease'] :'Decrease'}}</td>
													</tr>
													@endif
													<tr>
														<td>{{ isset($data['lang']['lang']['Remark']) ? $data['lang']['lang']['Remark'] :''}}</td>
														<td>{{ $d2['remark'] }}</td>
													</tr>
												</table>
											@elseif(!empty($d2['comm_pa_type']))
												<p>
													<b>
														{{ $desc }}
														@if($d2['comm_pa_type'] == 'Percentage')
															({{ $d2['comm_pa'] }}%)
														@else
															(RM {{ $d2['comm_pa'] }})
														@endif
													</b>
												</p>
												
												<table class="table">
													<tr>
														<td width="50%">
															{{ isset($data['lang']['lang']['amount']) ? $data['lang']['lang']['amount'] :'Amount'}}
														</td>
														<td width="50%">
															RM {{ number_format($d2['product_amount'], 2) }}
														</td>
													</tr>
													@if(!empty($d2['transaction_no']))
													<tr>
														<td width="50%">
															{{ isset($data['lang']['lang']['transaction_no']) ? $data['lang']['lang']['transaction_no'] :'Transaction No.'}}
														</td>
														<td width="50%">
															<a href="{{ route('order_detail', $d2['transaction_no']) }}" target="_blank">
																{{ $d2['transaction_no'] }}
															</a>
														</td>
													</tr>
													@endif
													@if(!empty($d2['product_name']))
													<tr>
														<td>
															{{ isset($data['lang']['lang']['product_name']) ? $data['lang']['lang']['product_name'] :'Product'}}
														</td>
														<td>
															{{ $d2['product_name'] }}
														</td>
													</tr>
													@endif
													
													@if(!empty($d2['user_by']))
													<tr>
														<td>
															{{ isset($data['lang']['lang']['downline']) ? $data['lang']['lang']['downline'] :'Downline'}}
														</td>
														<td>
															{{ $d2['downline_by'] }} ({{ $d2['user_by'] }})
														</td>
													</tr>
													@endif
													<tr>
														<td>
															{{ isset($data['lang']['lang']['status']) ? $data['lang']['lang']['status'] :'Status'}}
														</td>
														<td>
															@if($d2['status'] == '1')
																<span class="badge bg-success"> {{ isset($data['lang']['lang']['approved']) ? $data['lang']['lang']['approved'] :'Approved'}} </span>
															@elseif($d2['status'] == '99')
																<span class="badge badge-info"> {{ isset($data['lang']['lang']['pending']) ? $data['lang']['lang']['pending'] :'Pending'}} </span>
															@elseif($d2['status'] == '2')
																<span class="badge bg-danger"> {{ isset($data['lang']['lang']['cancelled']) ? $data['lang']['lang']['cancelled'] :'Cancelled'}} </span>
															@endif
														</td>
													</tr>
												</table>
											@elseif(!empty($d2['transfer_amount']))
												<p>
													<b>
														{{ isset($data['lang']['lang']['transfer_cash_wallet_to_topup_wallet']) ? $data['lang']['lang']['transfer_cash_wallet_to_topup_wallet'] :'Transfer Cash Wallet To Topup Wallet'}}
													</b>
												</p>
												
												<table class="table">
													<tr>
														<td>{{ isset($data['lang']['lang']['amount']) ? $data['lang']['lang']['amount'] :'Amount'}}</td>
														<td>RM {{ number_format($d2['transfer_amount'], 2) }}</td>
													</tr>
													<tr>
														<td>{{ isset($data['lang']['lang']['remark']) ? $data['lang']['lang']['remark'] :'Remark'}}</td>
														<td>{{$d2['remark'] }}</td>
													</tr>
													@if (!empty($d2['transfer_to_agent_name']))
														<tr>
															<td>{{ isset($data['lang']['lang']['transfer_to']) ? $data['lang']['lang']['transfer_to'] :'Transfer To'}}</td>
															<td>
																{{ $d2['transfer_to_agent_name'] }} ({{ $d2['user_id'] }})
															</td>
														</tr>
													@endif
													@if (!empty($d2['transfer_from_agent_name']))
														<tr>
															<td>{{ isset($data['lang']['lang']['transfer_from']) ? $data['lang']['lang']['transfer_from'] :'Transfer From'}}</td>
															<td>
																{{ $d2['transfer_from_agent_name'] }} ({{ $d2['user_by'] }})
															</td>
														</tr>
													@endif
													<tr>
														<td>
															{{ isset($data['lang']['lang']['status']) ? $data['lang']['lang']['status'] :'Status'}}
														</td>
														<td>
															@if($d2['status'] == '1')
																<span class="badge bg-success"> {{ isset($data['lang']['lang']['approved']) ? $data['lang']['lang']['approved'] :'Approved'}} </span>
															@elseif($d2['status'] == '99')
																<span class="badge badge-info"> {{ isset($data['lang']['lang']['pending']) ? $data['lang']['lang']['pending'] :'Pending'}} </span>
															@elseif($d2['status'] == '2')
																<span class="badge bg-danger"> {{ isset($data['lang']['lang']['cancelled']) ? $data['lang']['lang']['cancelled'] :'Cancelled'}} </span>
															@endif
														</td>
													</tr>
												</table>
											@endif
										</td>
										<td>
											@if(!empty($d2['withdrawal_no']))
												0.00
											@elseif(!empty($d2['Tid']))
												0.00
											@elseif(!empty($d2['used_point']))
												0.00
											@elseif(!empty($d2['get_point_transaction']))
												{{ number_format($d2['totalPoint'], 2) }} {{ isset($data['lang']['lang']['point']) ? $data['lang']['lang']['point'] :'Point'}}
											@elseif(!empty($d2['topup_no']))
												RM {{ number_format($d2['amount'], 2) }}
											@elseif(!empty($d2['joining_fee_amount']))
												RM {{ number_format($d2['joining_fee_amount'] + $d2['bonus_amount'], 2) }}
											@elseif(!empty($d2['adjust_cash_type']))
												@if($d2['adjust_cash_type'] == 1)
													RM {{ number_format($d2['amount'], 2) }}
												@else
													0.00
												@endif
											@elseif(!empty($d2['adjust_topup_type']))
												@if($d2['adjust_topup_type'] == 1)
													RM {{ number_format($d2['amount'], 2) }}
												@else
													0.00
												@endif
											@elseif(!empty($d2['transfer_amount']))
											    RM {{ number_format($d2['transfer_amount'], 2) }}	
											@else
												RM {{ number_format($d2['comm_amount'], 2) }}
											@endif
										</td>
										<td>
											@if(!empty($d2['withdrawal_no']))
												RM {{ number_format($d2['amount'], 2) }}
											@elseif(!empty($d2['Tid']))
												RM{{ number_format($d2['grand_total'], 2) }}
											@elseif(!empty($d2['used_point']))
												{{ number_format($d2['used_point'], 2) }} {{ isset($data['lang']['lang']['point']) ? $data['lang']['lang']['point'] :'Point'}}
											@elseif(!empty($d2['get_point_transaction']))
												0.00
											@elseif(!empty($d2['topup_no']))
												0.00
											@elseif(!empty($d2['joining_fee_amount']))
												0.00
											@elseif(!empty($d2['adjust_cash_type']))
												@if($d2['adjust_cash_type'] == 1)
													0.00
												@else
													RM {{ number_format($d2['amount'], 2) }}
												@endif
											@elseif(!empty($d2['adjust_topup_type']))
												@if($d2['adjust_topup_type'] == 1)
													0.00
												@else
													RM {{ number_format($d2['amount'], 2) }}
												@endif
											@else
												0.00
											@endif
										</td>
									</tr>
									@php
										if(!empty($d2['topup_no'])){
											$totalIn += $d2['amount'];
										}elseif(!empty($d2['transfer_amount'])){
											$totalIn += $d2['transfer_amount'];   
										}elseif(!empty($d2['comm_amount'])){
											$totalIn += $d2['comm_amount'];
										}elseif(!empty($d2['get_point_transaction'])){
											$totalIn += $d2['totalPoint'];
										}

										if(!empty($d2['withdrawal_no'])){
											$totalOut += $d2['amount'];
										}elseif(!empty($d2['Tid'])){
											$totalOut += $d2['grand_total'];
										}elseif(!empty($d2['pv_purchase_id'])){
											$totalOut += $d2['grand_total'];
										}
										
									@endphp
									@endforeach
									<!-- <tr class="warning">
										<td colspan="2">
											<b>Summary</b>
										</td>
										<td>
											{{ number_format($totalIn, 2) }}
										</td>
										<td>
											RM {{ number_format($totalOut, 2) }}
										</td>
									</tr> -->
								</tbody>
							</table>
							@else
								<div class="container-small-box" align="center">
								
								{{ isset($data['lang']['lang']['no_result']) ? $data['lang']['lang']['no_result'] :'没有结果'}}
								</div>
							@endif
						</div>
					</div>
					<div id="pv-tab" class="tab-pane">
						<table class="table table-bordered">
							<thead>
								<tr class="info">
									<th>{{ isset($data['lang']['lang']['date_time']) ? $data['lang']['lang']['date_time'] :'Date Time'}}</th>
									<th>{{ isset($data['lang']['lang']['description']) ? $data['lang']['lang']['description'] :'Description'}}</th>
								</tr>
							</thead>
							<tbody>
								@foreach($all_pv as $pv_det)
								<tr>
									<td>
										{{ $pv_det->created_at }}
									</td>
									<td>
										@if($pv_det->personal_pv > 0)
										<p>
											<b>
												Personal PV
											</b>
										</p>
										@elseif($pv_det->team_pv > 0)
										<p>
											<b>
												Team PV
											</b>
										</p>
										@elseif($pv_det->team_cus_pv > 0)

										<p>
											<b>
												Team's Customer PV
											</b>
										</p>
										@elseif($pv_det->team_cus_pv > 0)

										<p>
											<b>
												My Customer PV
											</b>
										</p>
										@endif
										<table class="table table-bordered">
											@if(!empty($pv_det->buyer_name))
											<tr>
												<td>
													Buyer
												</td>
												<td>
													{{ $pv_det->buyer_name }} ({{ $pv_det->buyer_code }})
												</td>
											</tr>
											@endif
											@if(!empty($pv_det->upline_name))
											<tr>
												<td>
													Upline
												</td>
												<td>
													{{ $pv_det->upline_name }} ({{ $pv_det->upline_code }})
												</td>
											</tr>
											@endif
											@if(!empty($pv_det->product_image))
											<tr>
												<td colspan="2">
													<img src="{{ asset($pv_det->product_image) }}" width="100px">
												</td>
											</tr>
											@endif
											@if(!empty($pv_det['product_name']))
											<tr>
												<td>
													{{ isset($data['lang']['lang']['product_name']) ? $data['lang']['lang']['product_name'] :'Product'}}
												</td>
												<td>
													{{ $pv_det['product_name'] }}
												</td>
											</tr>
											@endif
											@if(!empty($pv_det['quantity']))
											<tr>
												<td>
													{{ isset($data['lang']['lang']['quantity']) ? $data['lang']['lang']['quantity'] :'Product'}}
												</td>
												<td>
													{{ $pv_det['quantity'] }}
												</td>
											</tr>
											@endif
											@if(!empty($pv_det['pv_amount']))
												<p>
													<b>
														Topup Bonus
													</b>
												</p>
												<tr>
													<td>{{ isset($data['lang']['lang']['amount']) ? $data['lang']['lang']['amount'] :'Amount'}}</td>
													<td>{{ number_format($pv_det['pv_amount'], 2) }} Point</td>
												</tr>
												<tr>
													<td>{{ isset($data['lang']['lang']['type']) ? $data['lang']['lang']['type'] :'Type'}}</td>
													<td>{{ isset($data['lang']['lang']['increase']) ? $data['lang']['lang']['increase'] :'Increase'}}</td>
												</tr>
											@endif
											@if(!empty($pv_det['amount']))
												<p>
													<b>
														{{ isset($data['lang']['lang']['point_wallet_adjustment']) ? $data['lang']['lang']['point_wallet_adjustment'] :'Point Wallet Adjustment'}}
													</b>
												</p>
												<tr>
													<td>{{ isset($data['lang']['lang']['created_by']) ? $data['lang']['lang']['created_by'] :'Created By'}}</td>
													<td>{{ $pv_det['created_by_name'] }}</td>
												</tr>
												<tr>
													<td>{{ isset($data['lang']['lang']['amount']) ? $data['lang']['lang']['amount'] :'Amount'}}</td>
													<td>{{ number_format($pv_det['amount'], 2) }} Point</td>
												</tr>
												@if($pv_det['adjust_point_type'] == 1)
												<tr>
													<td>{{ isset($data['lang']['lang']['type']) ? $data['lang']['lang']['type'] :'Type'}}</td>
													<td>{{ isset($data['lang']['lang']['increase']) ? $data['lang']['lang']['increase'] :'Increase'}}</td>
												</tr>
												@else
												<tr>
													<td>{{ isset($data['lang']['lang']['type']) ? $data['lang']['lang']['type'] :'Type'}}</td>
													<td>{{ isset($data['lang']['lang']['decrease']) ? $data['lang']['lang']['decrease'] :'Decrease'}}</td>
												</tr>
												@endif
												<tr>
													<td>{{ isset($data['lang']['lang']['Remark']) ? $data['lang']['lang']['Remark'] :''}}</td>
													<td>{{ $pv_det['remark'] }}</td>
												</tr>
											@endif
											@if(!empty($pv_det['get_point_transaction']))
												<p>
													<b>
														Get Point From Product
													</b>
												</p>
												<tr>
													<td>{{ isset($data['lang']['lang']['transaction_no']) ? $data['lang']['lang']['transaction_no'] :'Transaction No.'}}</td>
													<td>
														<a href="{{ route('order_detail', $pv_det['transaction_no']) }}" target="_blank">
															{{ $pv_det['transaction_no'] }}
														</a>
													</td>
												</tr>
												<tr>
													<td>{{ isset($data['lang']['lang']['amount']) ? $data['lang']['lang']['amount'] :'Amount'}}</td>
													<td>{{ number_format($pv_det['totalPoint'], 2) }} Point</td>
												</tr>
												<tr>
													<td>{{ isset($data['lang']['lang']['type']) ? $data['lang']['lang']['type'] :'Type'}}</td>
													<td>{{ isset($data['lang']['lang']['increase']) ? $data['lang']['lang']['increase'] :'Increase'}}</td>
												</tr>
											@endif
											@if(!empty($pv_det['pv_purchase']))
												<p>
													<b>
														Point Mall Purchase
													</b>
												</p>
												<tr>
													<td>{{ isset($data['lang']['lang']['transaction_no']) ? $data['lang']['lang']['transaction_no'] :'Transaction No.'}}</td>
													<td>
														<a href="{{ route('order_detail', $pv_det['transaction_no']) }}" target="_blank">
															{{ $pv_det['transaction_no'] }}
														</a>
													</td>
												</tr>
												<tr>
													<td>{{ isset($data['lang']['lang']['amount']) ? $data['lang']['lang']['amount'] :'Amount'}}</td>
													<td>{{ number_format($pv_det['grand_total'], 2) }} Point</td>
												</tr>
												<tr>
													<td>{{ isset($data['lang']['lang']['type']) ? $data['lang']['lang']['type'] :'Type'}}</td>
													<td>{{ isset($data['lang']['lang']['decrease']) ? $data['lang']['lang']['decrease'] :'Decrease'}}</td>
												</tr>
											@endif
											<tr>
												<td>
													{{ isset($data['lang']['lang']['status']) ? $data['lang']['lang']['status'] :'Status'}}
												</td>
												<td>
													@if($pv_det['status'] == '1')
														<span class="badge bg-success"> {{ isset($data['lang']['lang']['approved']) ? $data['lang']['lang']['approved'] :'Approved'}} </span>
													@elseif($pv_det['status'] == '99')
														<span class="badge badge-info"> {{ isset($data['lang']['lang']['pending']) ? $data['lang']['lang']['pending'] :'Pending'}} </span>
													@elseif($pv_det['status'] == '2')
														<span class="badge bg-danger"> {{ isset($data['lang']['lang']['cancelled']) ? $data['lang']['lang']['cancelled'] :'Cancelled'}} </span>
													@endif
												</td>
											</tr>
										</table>
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
						</div>
						</div>
						@endif
						</div>
					</div>
				</div>
			</div>
		</div>
		</div>
	</div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/moment@2.22.2/moment.min.js"></script>
<script src="{{ asset('assets/js/daterangepicker.min.js') }}"></script>
<script type="text/javascript">
	$('input[name=dates]').daterangepicker({
		'applyClass' : 'btn-sm btn-success',
		'cancelClass' : 'btn-sm btn-default',
		locale: {
			applyLabel: 'Apply',
			cancelLabel: 'Cancel',
		}
	});

	$('.topup-btn').click(function(e){
		e.preventDefault();

		var topupPackages = $('input[name="topup_amount"]').val();

		topupPackages = topupPackages.replace(/[^\d.-]/g,'');

		if(topupPackages < 1){
			toastr.error('Please key in valid amount');
			return false;
		}

		if(topupPackages < 50){
			toastr.error('MINIMUM TOPUP RM 50.00');
			return false;
		}

		$('.topup_amount_display').html(parseFloat(topupPackages).toFixed(2));
		$('.topup_amount').val(parseFloat(topupPackages).toFixed(2));
	});

	$('.topup-submit-btn').click(function(e){
		e.preventDefault();

		var topup_amount = $('.topup_amount').val();
			topup_amount = topup_amount.match(/\d+/);

		$('#topup-form').submit();

	});

	$('#withdrawal-form .required-field').change( function(){
    	if($(this).val()){
    		$(this).removeClass('required-feild-error');
    	}
    });

    $('input[name="default_banks"]').click( function(){

		$('.loading-gif').show();

		var ele = $(this);
		var bid = ele.closest('tr').find('input[name="bid"]').val();
		var fd = new FormData();
		  	fd.append('bid', bid);

	  	$.ajax({
	        url: '{{ route("setBankDefault") }}',
	        type: 'post',
	        data: fd,
	        contentType: false,
	        processData: false,
	        success: function(response){
	        	$('.loading-gif').hide();

	        	$('#bank_name').html('Bank Name: '+response[0]);
	        	$('#bank_holder_name').html('Bank Holder Name: '+response[1]);
	        	$('#bank_account').html('Bank Account: '+response[2]);
				
				

        		toastr.success('设定默认银行户口成功');		        	
	        }
	    });
	});

	$('.submit-withdrawal').click(function (e){
		e.preventDefault();
		$('.loading-gif').show();
		var empty_fill = 0;
		var balance = '{{ $CashWallet }}';
		var amount = $('input[name="amount"]').val();
		var default_banks = '{{ !empty($banksDefault->id) ? $banksDefault->id : 0 }}';

		if(default_banks == 0){
			alert('Please create banks account for withdrawal');
			$('.loading-gif').hide();
			return false;
		}

		if(amount == 0){
			$(this).addClass('required-feild-error');
			$('#error-message').html('Amount At Least > 0');
			$('.loading-gif').hide();
			return false;
		}
		$('#withdrawal-form .required-field').each( function(){
	    	if(!$(this).val()){
	    		$(this).addClass('required-feild-error');
	    		empty_fill = 1;
	    	}
	    });
	    if(empty_fill == 1){
	    	$('#error-message').html('Please fill in all required field.');
	    	$('.loading-gif').hide();
	    	return false;
	    }
		var minLimit = {{ !empty($website_setting->min_withdrawal_amount) ? $website_setting->min_withdrawal_amount : 0 }};
		if(minLimit > 0 && parseFloat(amount) < minLimit){
			$('#error-message').html('Minimum withdrawal is RM '+minLimit.toFixed(2));
			$('.loading-gif').hide();
			return false; 
		}
    
		if(parseFloat(amount) > parseFloat(balance)){
	    	$('#error-message').html('Balance Not Enough.');
	    	$('.loading-gif').hide();
	    	return false;	
	    }else{
			$('#withdrawal-form').submit();
	    }
		
	});

	$('.payment_method').click(function(e){
      var ele = $(this);
      var id = ele.data('id');
      $('.parent_payment_method').removeClass('active');
      ele.parent().addClass('active');
      $('.selected_payment_method').val(id);
    });

    $('.topup_select_button').click(function(e){
    	e.preventDefault();
    	var ele = $(this);

    	$('.topup_select_button').removeClass('active');
    	ele.addClass('active');

    	var val = ele.data('val');
    	$('input[name="topup_amount"]').val("");
    	if(val == 'other'){
    		$('input[name="topup_amount"]').focus();
    	}else{
    		$('input[name="topup_amount"]').val(parseFloat(val).toFixed(2));
    	}
    });


    $('.topup_select_button').click(function(e){
    	e.preventDefault();
    	var ele = $(this);

    	$('.topup_select_button').removeClass('active');
    	ele.addClass('active');

    	var val = ele.data('val');
    	$('input[name="topup_amount"]').val("");
    	if(val == 'other'){
    		$('input[name="topup_amount"]').focus();
    	}else{
    		$('input[name="topup_amount"]').val(parseFloat(val).toFixed(2));
    	}
    });
	
	$('.withdrawal_type').click(function(e) {
		$('.loading-gif').show();

		if ($(this).is(':checked')) {
			var withdrawal_type = 1;
		} else {
			var withdrawal_type = 0;
		}

		var fd = new FormData();
		  	fd.append('withdrawal_type', withdrawal_type);

	  	$.ajax({
	        url: '{{ route("SetWithdrawalType") }}',
	        type: 'post',
	        data: fd,
	        contentType: false,
	        processData: false,
	        success: function(response){
	        	location.reload();	        	
	        }
	    });
	});
</script>
@endsection