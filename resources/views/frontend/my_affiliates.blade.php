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
							<h5 class="mb-2">{{ $OwnAffiliate[0] }}</h5>
							<h6>{{ isset($data['lang']['lang']['first_gen_agent_amount']) ? $data['lang']['lang']['first_gen_agent_amount'] :'第 1 级代理总数'}}</h6>
						</div>
					</div>
					<div class="col-lg-2 col-md-2 col-sm-3 col-3" align="center">
						<div class="form-group">
							<h5 class="mb-2">{{ $OwnAffiliate[1] }}</h5>
							<h6>{{ isset($data['lang']['lang']['sec_gen_agent_amount']) ? $data['lang']['lang']['sec_gen_agent_amount'] :'第 2 级代理总数'}}</h6>
						</div>
					</div>
					<div class="col-lg-2 col-md-2 col-sm-3 col-3" align="center">
						<div class="form-group">
							<h5 class="mb-2">{{ $OwnAffiliate[2] }}</h5>
							<h6>{{ isset($data['lang']['lang']['third_gen_agent_amount']) ? $data['lang']['lang']['third_gen_agent_amount'] :'第 3 级代理总数'}}</h6>
						</div>
					</div>
					<div class="col-lg-2 col-md-2 col-sm-3 col-3" align="center">
						<div class="form-group">
							<h5 class="mb-2">{{ $totalAgent[0] }}</h5>
							<h6>{{ isset($data['lang']['lang']['all_gen_agent_amount']) ? $data['lang']['lang']['all_gen_agent_amount'] :'代理总数'}}</h6>
						</div>
					</div>
				</div>
			</div>
			<div class="widget-box transparent pb-4" id="recent-box">
				<div class="widget-header">
					<h3 style="text-align: center; font-size: 2rem;">{{ isset($data['lang']['lang']['my_team']) ? $data['lang']['lang']['my_team'] :'我的团队'}}</h3>
					<div class="widget-toolbar no-border">
						<ul class="nav nav-tabs" id="recent-tab">
							<li class="active">
								<a data-toggle="tab" class="payment_method" data-id="1" href="#tab-list-tab">{{ isset($data['lang']['lang']['tab_view']) ? $data['lang']['lang']['tab_view'] :'Tab View'}}</a>
							</li>

							<li>
								<a data-toggle="tab" class="payment_method" data-id="2" href="#tree-tab">{{ isset($data['lang']['lang']['tree_view']) ? $data['lang']['lang']['tree_view'] :'Tree View'}}</a>
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
								<div class="container">
									<div class="row">
										<div class="col-sm-3 mt-2">
											<a href="{{ route('MyAffiliate', [$code]) }}"
											   class="btn btn-shadow btn-block set_button set_text {{ (!empty(request('generation')) && request('generation') == 4) ? 'active' : '' }} search-generation">
												{{ isset($data['lang']['lang']['clear_search']) ? $data['lang']['lang']['clear_search'] :'清除搜索'}}
											</a>
										</div>
										<div class="col-sm-3 mt-2">
											<a href="{{ route('MyAffiliate', [$code, 'generation=1']) }}"
											   class="btn btn-shadow btn-block set_button set_text {{ (!empty(request('generation')) && request('generation') == 1) ? 'active' : '' }} search-generation">
												{{ isset($data['lang']['lang']['first_gen']) ? $data['lang']['lang']['first_gen'] :'第 1 级'}}
											</a>
										</div>
										<div class="col-sm-3 mt-2">
											<a href="{{ route('MyAffiliate', [$code, 'generation=2']) }}"
											   class="btn btn-shadow btn-block set_button set_text {{ (!empty(request('generation')) && request('generation') == 2) ? 'active' : '' }} search-generation">
												{{ isset($data['lang']['lang']['sec_gen']) ? $data['lang']['lang']['sec_gen'] :'第 2 级'}}
											</a>
										</div>
										<div class="col-sm-3 mt-2">
											<a href="{{ route('MyAffiliate', [$code, 'generation=3']) }}"
											   class="btn btn-shadow btn-block set_button set_text {{ (!empty(request('generation')) && request('generation') == 3) ? 'active' : '' }} search-generation">
												{{ isset($data['lang']['lang']['third_gen']) ? $data['lang']['lang']['third_gen'] :'第 3 级'}}
											</a>
										</div>
									</div>
								</div>
								<hr>
								<div class="form-group affiliate-list-area">
									<ul>
										@if(!$affiliates->isEmpty())
											@foreach($affiliates as $affiliate)
												<li>
													<a href="{{ route('MyAffiliate', $affiliate->downline_code) }}">
														<div class="users-details-box" style="margin-bottom: 10px;">
															@if(!empty($affiliate->profile_logo))
																<div class="users-img" style="background-image: url({{ asset($affiliate->profile_logo) }})"></div>
															@else
																<div class="users-img" style="background-image: url({{ asset('images/images.png') }})"></div>
															@endif
														</div>
														<div class="users-details-box">
															<i class="fa fa-user w-29"></i>{{ isset($data['lang']['lang']['full_name']) ? $data['lang']['lang']['full_name'] :'名字'}}: {{ $affiliate->downline_name }}<br>
															<i class="fa fa-user w-29"></i>{{ isset($data['lang']['lang']['agent_code']) ? $data['lang']['lang']['agent_code'] :'代码'}}: 
															{{ $affiliate->downline_code }}
															<br>
															<i class="fa fa-user w-29"></i>
															@if(!empty($affiliate->l_agent_lvl))
																{{ isset($data['lang']['lang']['level']) ? $data['lang']['lang']['level'] :'等级'}}: {{ $affiliate->l_agent_lvl }}
															@else
																{{ isset($data['lang']['lang']['level']) ? $data['lang']['lang']['level'] :'等级'}}: <i class="fa fa-minus"></i>
															@endif
															<br>
															<i class="fa fa-envelope w-29"></i>{{ isset($data['lang']['lang']['email_address']) ? $data['lang']['lang']['email_address'] :'电子邮件'}}: {{ $affiliate->downline_email }}<br>
															<i class="fa fa-phone w-29"></i>{{ isset($data['lang']['lang']['phone_number']) ? $data['lang']['lang']['phone_number'] :'手机号'}}: +({{ $affiliate->downline_country_code }}){{ $affiliate->downline_phone }}
															<br>
															<i class="fa fa-venus-mars w-29"></i>{{ isset($data['lang']['lang']['gender']) ? $data['lang']['lang']['gender'] :'性别'}}:
															@if(!empty($affiliate->downline_gender))
																<!-- {{ ($affiliate->downline_gender == 'Male') ? 'Male' : 'Female' }} -->
																@if($affiliate->downline_gender == 'Male')
																	{{ isset($data['lang']['lang']['male']) ? $data['lang']['lang']['male'] :'Male'}}
																@else
																	{{ isset($data['lang']['lang']['female']) ? $data['lang']['lang']['female'] :'Female'}}
																@endif
															@else
																-
															@endif
															<br>
															<i class="fa fa-calendar w-29"></i>{{ isset($data['lang']['lang']['join_date']) ? $data['lang']['lang']['join_date'] :'Join Date'}}: 
															
															{{ $affiliate->downline_created_at }}
															<br>
															<i class="fa fa-users w-29"></i><!-- 第 {{ $affiliate->sort_level }} 级 -->
															@if($affiliate->sort_level == '1')
																<!-- {{ $affiliate->sort_level }}st Generation -->
																{{ isset($data['lang']['lang']['first_gen']) ? $data['lang']['lang']['first_gen'] :'第 1 级'}}
															@elseif($affiliate->sort_level == '2')
																<!-- {{ $affiliate->sort_level }}nd Generation -->
																{{ isset($data['lang']['lang']['sec_gen']) ? $data['lang']['lang']['sec_gen'] :'第 2 级'}}
															@elseif($affiliate->sort_level == '3')
																<!-- {{ $affiliate->sort_level }}rd Generation -->
																{{ isset($data['lang']['lang']['third_gen']) ? $data['lang']['lang']['third_gen'] :'第 3 级'}}
															@else
																{{ $affiliate->sort_level }}th Generation
															@endif
															<br>
															<i class="fa fa-users w-29"></i>{{ isset($data['lang']['lang']['upline_name']) ? $data['lang']['lang']['upline_name'] :'推荐人名字'}}: 
															{{ $AffiliateDirectUpline[$affiliate->downline_code]->f_name }} {{ $AffiliateDirectUpline[$affiliate->downline_code]->l_name }}<br>
															<i class="fa fa-users w-29"></i>{{ isset($data['lang']['lang']['upline_code']) ? $data['lang']['lang']['upline_code'] :'推荐人代码'}}: 
															{{ $affiliate->downline_master_id }}
															<br>
															<i class="fas fa-chart-line w-29"></i>This Month Sales: RM {{ !empty($get_monthly_user_accumulated_sales[$affiliate->code]) ? number_format($get_monthly_user_accumulated_sales[$affiliate->code], 2) : '0.00' }}
															<br>
															<i class="fas fa-chart-bar w-29"></i>Accumulate Sales: RM {{ !empty($get_user_accumulated_sales[$affiliate->code]) ? number_format($get_user_accumulated_sales[$affiliate->code], 2) : '0.00' }}
															<br>
															<a href="{{ route('MyCustomerTransaction', $affiliate->downline_code) }}">
															<i class="fa fa-eye w-29"></i>{{ isset($data['lang']['lang']['view_trans']) ? $data['lang']['lang']['view_trans'] :'查看单号'}}
															</a>
														</div>
														<div style="clear: both;"></div>
													</a>
												</li>
												<hr>
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
										    			<img src="{{ !empty($profile_logo) ? asset($profile_logo) : asset('images/images.png') }}" class="w-101" style="border-radius: 100%;">
										    			<br>
										    			{{ $user->f_name }} {{ $user->l_name }} ({{ $user->code }})
										    		</span>

										      		<ul>
										      			@foreach($merchantD as $merchantDv)
										        		<li>
										        			<span>

										        				<img src="{{ !empty($merchantDv->profile_logo) ? asset($merchantDv->profile_logo) : asset('images/images.png') }}" class="w-101" style="border-radius: 100%;">
										        				<br>
										        				{{ $merchantDv->f_name }} {{ $merchantDv->l_name }} ({{ $merchantDv->code }})
										        			</span>
										        			@if(!$mdd[$merchantDv->code]->isEmpty())
											        			<ul>

											        				@foreach($mdd[$merchantDv->code] as $mddv)
											        				<li>
											        					<span>
											        						<img src="{{ !empty($mddv->profile_logo) ? asset($mddv->profile_logo) : asset('images/images.png') }}" class="w-101" style="border-radius: 100%;">
										        							<br>
											        						{{ $mddv->f_name }} {{ $mddv->l_name }} ({{ $mddv->code }})</span>

											        					@if(!$mddd[$mddv->code]->isEmpty())
											        					<ul>
											        						@foreach($mddd[$mddv->code] as $mdddv)
												        						<li>
												        							<span>
												        								<img src="{{ !empty($mdddv->profile_logo) ? asset($mdddv->profile_logo) : asset('images/images.png') }}" class="w-101" style="border-radius: 100%;">
										        										<br>
												        								{{ $mdddv->f_name }} {{ $mdddv->l_name }} ({{ $mdddv->code }})
												        							</span>
												        							<!-- @if(!$mdddd[$mdddv->code]->isEmpty())
												        							<ul>
												        								@foreach($mdddd[$mdddv->code] as $mddddv)
												        								<li>
												        									<span>
												        										<img src="{{ !empty($mddddv->profile_logo) ? asset($mddddv->profile_logo) : asset('images/images.png') }}" class="w-101" style="border-radius: 100%;">
										        												<br>
												        										{{ $mddddv->f_name }} {{ $mddddv->l_name }} ({{ $mddddv->code }})
												        									</span>
												        								</li>
												        								@endforeach
												        							</ul>
												        							@endif -->
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

@section('js')
	<script type="text/javascript">
		$('.payment_method').click(function(){
			$(this).closest('.nav-tabs').find('.active').removeClass("active");
			$(this).closest('li').addClass("active");
		});
	</script>
@endsection