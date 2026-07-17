@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('frontend/tree/style.css') }}">
<script src="{{ asset('assets/js/tree.min.js') }}"></script>
@section('css')
<style type="text/css">
	.tree:before {
		border: none;
	}

	.tree code, .tree span{
		border: none;
	}

	.affiliate_list ul li {
		border-bottom: 0px;
	}

	.nav-tabs>li>a {
		margin-right: 0px !important;
	}

	.affiliate-list-area a:hover{
		color: black;
	}

	.affiliate-list-area a{
		color: black;
	}
</style>
@endsection
@section('content')
@include('partial.frontend.profile_header')
<div class="shop_inner_inf">
	<div class="col-md-12" style="padding: 0px">
		<div class="affiliate_list">
			<div class="form-group pt-1 pb-4 overflow-hidden">
				<div class="row d-flex justify-content-center g-10">
					<div class="col-lg-2 col-md-2 col-sm-3 col-3" align="center">
						<div class="form-group">
							<h5 class="mb-2">{{ $TodayNewCustomer }}</h5>
							<h6>{{ isset($data['lang']['lang']['daily_new_customer_amount']) ? $data['lang']['lang']['daily_new_customer_amount'] :'今天新增顾客总数'}}</h6>
						</div>
					</div>
					<div class="col-lg-2 col-md-2 col-sm-3 col-3" align="center">
						<div class="form-group">
							<h5 class="mb-2">{{ $monthTotalCustomer }}</h5>
							<h6>{{ isset($data['lang']['lang']['monthly_new_customer_amount']) ? $data['lang']['lang']['monthly_new_customer_amount'] :'这个月新增顾客总数'}}</h6>
						</div>
					</div>
					<div class="col-lg-2 col-md-2 col-sm-3 col-3" align="center">
						<div class="form-group">
							<h5 class="mb-2">{{ $yearTotalCustomer }}</h5>
							<h6>{{ isset($data['lang']['lang']['yearly_new_customer_amount']) ? $data['lang']['lang']['yearly_new_customer_amount'] :'今年新增顾客总数'}}</h6>
						</div>
					</div>
					<div class="col-lg-2 col-md-2 col-sm-3 col-3" align="center">
						<div class="form-group">
							<h5 class="mb-2">{{ $totalCustomer + $totalCustomers[0] + $totalCustomers[1] + $totalCustomers[2] }}</h5>
							<h6>{{ isset($data['lang']['lang']['all_gen_customer_amount']) ? $data['lang']['lang']['all_gen_customer_amount'] :'顾客总数'}}</h6>
						</div>
					</div>
				</div>
			</div>
				
			<div class="widget-box transparent pb-4" id="recent-box">
				<div class="widget-header">
					<h3 style="text-align: center; font-size: 2rem;">{{ isset($data['lang']['lang']['my_customer']) ? $data['lang']['lang']['my_customer'] :'我的顾客'}}</h3>
					<div class="widget-toolbar no-border">
						<ul class="nav nav-tabs" id="recent-tab">
							<li class="active">
								<a data-toggle="tab" class="payment_method" data-id="1" href="#tab-list-tab" aria-expanded="true">{{ isset($data['lang']['lang']['tab_view']) ? $data['lang']['lang']['tab_view'] :'Tab View'}}</a>
							</li>

							<li>
								<a data-toggle="tab" class="payment_method" data-id="2" href="#tree-tab" aria-expanded="true">{{ isset($data['lang']['lang']['tree_view']) ? $data['lang']['lang']['tree_view'] :'Tree View'}}</a>
							</li>
						</ul>
					</div>
				</div>
				<div class="widget-body">
					<div class="widget-main">
						<div class="tab-content">
							<div id="tab-list-tab" class="tab-pane active">
								<div class="affiliate-search-area">
									<form method="GET" action="">
										<div class="input-group">
								            <input type="text" name="name" class="form-control search-query" placeholder="{{ isset($data['lang']['lang']['search']) ? $data['lang']['lang']['search'] :'搜索'}}" value="{{ !empty(request('name')) ? request('name') : '' }}" style="border: 1px solid #ced4da;">
								            <span class="input-group-btn" style="width: auto;">
								                <button type="submit" class="btn btn-shadow btn-white search-button set_button set_text" style="outline: none; height: 100%; border-top-right-radius: 25px !important; border-bottom-right-radius: 25px !important; border-top-left-radius: 0px; border-bottom-left-radius: 0px; padding: 8px 15px;">
								                    <span class="ace-icon fa fa-search icon-on-right bigger-110" style="display: none;"></span>
								                    {{ isset($data['lang']['lang']['search']) ? $data['lang']['lang']['search'] :'搜索'}}
								                </button>
								            </span>
								        </div>
								    </form>
								</div>
								<hr>
								{{-- Generation selection buttons commented out - only showing first generation --}}
								{{-- <div class="container">
									<div class="row">
										<div class="col-4">
											<a href="{{ route('MyCustomer', [$code, 'generation=1']) }}"
											   class="btn btn-shadow btn-block {{ (!empty(request('generation')) && request('generation') == 1) ? 'active' : '' }} search-generation">
												{{ isset($data['lang']['lang']['first_gen']) ? $data['lang']['lang']['first_gen'] :'第 1 级'}}
											</a>
										</div>
										<div class="col-4">
											<a href="{{ route('MyCustomer', [$code, 'generation=2']) }}"
											   class="btn btn-shadow btn-block {{ (!empty(request('generation')) && request('generation') == 2) ? 'active' : '' }} search-generation">
												{{ isset($data['lang']['lang']['sec_gen']) ? $data['lang']['lang']['sec_gen'] :'第 2 级'}}
											</a>
										</div>
										<div class="col-4">
											<a href="{{ route('MyCustomer', [$code, 'generation=3']) }}"
											   class="btn btn-shadow btn-block {{ (!empty(request('generation')) && request('generation') == 3) ? 'active' : '' }} search-generation">
												{{ isset($data['lang']['lang']['third_gen']) ? $data['lang']['lang']['third_gen'] :'第 3 级'}}
											</a>
										</div>
									</div>
								</div>
								<hr> --}}
								<div class="form-group affiliate-list-area mb-5">
									<ul>
										@if(!$affiliates->isEmpty())
											@foreach($affiliates->where('sort_level', 0) as $affiliate)
												<li>
													<a>
														<div class="users-details-box" style="margin-bottom: 10px;">
															@if(!empty($affiliate->profile_logo))
																<div class="users-img" style="background-image: url({{ asset($affiliate->profile_logo) }})"></div>
															@else
																<div class="users-img" style="background-image: url({{ asset('images/images.png') }})"></div>
															@endif
														</div>
														<div class="users-details-box">
																<i class="fa fa-user w-29"></i>{{ isset($data['lang']['lang']['full_name']) ? $data['lang']['lang']['full_name'] :'名字'}}: {{ $affiliate->f_name }}<br>
																<i class="fa fa-user w-29"></i>{{ isset($data['lang']['lang']['agent_code']) ? $data['lang']['lang']['agent_code'] :'代码'}}: {{ $affiliate->display_code }}{{ $affiliate->display_running_no }}<br>
																<i class="fa fa-envelope w-29"></i>{{ isset($data['lang']['lang']['email_address']) ? $data['lang']['lang']['email_address'] :'电子邮件'}}: {{ $affiliate->email }}<br>
																<i class="fa fa-phone w-29"></i>{{ isset($data['lang']['lang']['phone_number']) ? $data['lang']['lang']['phone_number'] :'手机号'}}: +({{ $affiliate->country_code }}){{ $affiliate->phone }}<br>
																@if($affiliate->company != 1)
																	<i class="fa fa-venus-mars w-29"></i>{{ isset($data['lang']['lang']['gender']) ? $data['lang']['lang']['gender'] :'性别'}}:
																	@if(!empty($affiliate->gender))
																		<!-- {{ ($affiliate->gender == 'Male') ? 'Male' : 'Female' }} -->
																		@if($affiliate->gender == 'Male')
																			{{ isset($data['lang']['lang']['male']) ? $data['lang']['lang']['male'] :'Male'}}
																		@else
																			{{ isset($data['lang']['lang']['female']) ? $data['lang']['lang']['female'] :'Female'}}
																		@endif
																	@else
																		-
																	@endif
																	<br>
																@endif
																<i class="fa fa-calendar w-29"></i>{{ isset($data['lang']['lang']['date_time_customer']) ? $data['lang']['lang']['date_time_customer'] :'成为顾客的日期与时间'}}: {{ $affiliate->created_at }}<br>
																<i class="fa fa-users w-29"></i>
																<!-- 第 {{ $affiliate->sort_level + 1}} 级 -->
																@php
																	$lvl = $affiliate->sort_level + 1;
																@endphp
																@if($lvl == '1')
																	{{ isset($data['lang']['lang']['first_gen']) ? $data['lang']['lang']['first_gen'] :'第 1 级'}}
																@elseif($lvl == '2')
																	{{ isset($data['lang']['lang']['sec_gen']) ? $data['lang']['lang']['sec_gen'] :'第 2 级'}}
																@else
																	{{ isset($data['lang']['lang']['third_gen']) ? $data['lang']['lang']['third_gen'] :'第 3 级'}}
																@endif
																<br>
																<i class="fa fa-users w-29"></i>{{ isset($data['lang']['lang']['upline_name']) ? $data['lang']['lang']['upline_name'] :'推荐人名字'}}:
																@if(isset($affiliate->m_name))
																{{ $affiliate->m_name }}
																@else
																{{ $name }}
																@endif
																<br>
																<i class="fa fa-users w-29"></i>{{ isset($data['lang']['lang']['upline_code']) ? $data['lang']['lang']['upline_code'] :'推荐人代码'}}: {{ $affiliate->upline_code }}<br>
																<a href="{{ route('MyCustomerTransaction', $affiliate->code) }}" style="color: #000;">
																<i class="fa fa-eye w-29"></i>{{ isset($data['lang']['lang']['view_trans']) ? $data['lang']['lang']['view_trans'] :'查看单号'}}
																</a>
																<hr>
														</div>
														<div style="clear:both;"></div>
													</a>
												</li>
											@endforeach
										@else
											<li align="center">
												<h6><i class="fa fa-search"></i> No Result</h6>
											</li>
										@endif
									</ul>
								</div>
							</div>

							<div id="tree-tab" class="tab-pane">
								<div class="row" style="overflow: auto;">
									<div class="col-xs-12">
										<figure>
										  	<ul class="tree">
										    	<li>
										    		<span>
										    			<img src="{{ !empty($user->profile_logo) ? asset($user->profile_logo) : asset('images/images.png') }}" class="w-101" style="border-radius: 100%;">
										    			<br>
										    			{{ $user->f_name }} {{ $user->l_name }}
										    		</span>

										      		<ul>
										      			@foreach($userD as $userDv)
										        		<li>
										        			<span>

										        				<img src="{{ !empty($userDv->profile_logo) ? asset($userDv->profile_logo) : asset('images/images.png') }}" class="w-101" style="border-radius: 100%;">
										        				<br>
										        				{{ $userDv->f_name }} {{ $userDv->l_name }}
										        			</span>
										        			{{-- Other generations (2nd, 3rd, 4th, 5th, 6th) commented out - only showing first generation --}}
										        			{{-- @if(!$mdd[$userDv->code]->isEmpty())
											        			<ul>

											        				@foreach($mdd[$userDv->code] as $mddv)
											        				<li>
											        					<span>
											        						<img src="{{ !empty($mddv->profile_logo) ? asset($mddv->profile_logo) : asset('images/images.png') }}" class="w-101" style="border-radius: 100%;">
										        							<br>
											        						{{ $mddv->f_name }} {{ $mddv->l_name }}</span>

											        					@if(!$mddd[$mddv->code]->isEmpty())
											        					<ul>
											        						@foreach($mddd[$mddv->code] as $mdddv)
												        						<li>
												        							<span>
												        								<img src="{{ !empty($mdddv->profile_logo) ? asset($mdddv->profile_logo) : asset('images/images.png') }}" class="w-101" style="border-radius: 100%;">
										        										<br>
												        								{{ $mdddv->f_name }} {{ $mdddv->l_name }}
												        							</span>
												        							@if(!$mddd1[$mdddv->code]->isEmpty())
												        							<ul>
												        								@foreach($mddd1[$mdddv->code] as $mdddv1)
												        								<li>
												        									<span>
														        								<img src="{{ !empty($mdddv1->profile_logo) ? asset($mdddv1->profile_logo) : asset('images/images.png') }}" class="w-101" style="border-radius: 100%;">
												        										<br>
														        								{{ $mdddv1->f_name }} {{ $mdddv1->l_name }}
														        							</span>
														        							@if(!$mddd2[$mdddv1->code]->isEmpty())
														        								<ul>
														        									@foreach($mddd2[$mdddv1->code] as $mdddv2)
														        									<li>
														        										<span>
																	        								<img src="{{ !empty($mdddv2->profile_logo) ? asset($mdddv2->profile_logo) : asset('images/images.png') }}" class="w-101" style="border-radius: 100%;">
															        										<br>
																	        								{{ $mdddv2->f_name }} {{ $mdddv2->l_name }}
																	        							</span>
																	        							@if(!$mddd3[$mdddv2->code]->isEmpty())
																	        								<ul>
																	        									@foreach($mddd3[$mdddv2->code] as $mdddv3)
																	        										<span>
																				        								<img src="{{ !empty($mdddv3->profile_logo) ? asset($mdddv3->profile_logo) : asset('images/images.png') }}" class="w-101" style="border-radius: 100%;">
																		        										<br>
																				        								{{ $mdddv3->f_name }} {{ $mdddv3->l_name }}
																				        							</span>
																	        									@endforeach
																	        								</ul>
																	        							@endif
														        									</li>
														        									@endforeach
														        								</ul>
														        							@endif
												        								</li>
												        								@endforeach
												        							</ul>
												        							@endif
												        						</li>
												        					@endforeach
											        					</ul>
											        					@endif
											        				</li>
											        				@endforeach
											        			</ul>
											        		@endif --}}
										        		</li>
										        		@endforeach
										      		</ul>
										    	</li>
										  	</ul>
										</figure>
									</div>
								</div>				
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div style="clear: both;"></div>
@endsection