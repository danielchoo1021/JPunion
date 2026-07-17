@extends('layouts.admin_app')
<link rel="stylesheet" href="{{ asset('frontend/tree/style.css') }}">
@section('css')
<style type="text/css">
	.tree:before {
		border: none;
	}

	.tree code, .tree span{
		border: none;
	}
</style>
@endsection
@section('content')
<ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
        <a
          class="nav-link active"
          id="home-tab"
          data-bs-toggle="tab"
          href="#home"
          role="tab"
          aria-controls="home"
          aria-selected="true"
          >
          	{{ isset($data['backendlang']['backendlang']['Tab_View']) ? $data['backendlang']['backendlang']['Tab_View'] :'' }}
        </a>
    </li>
    <li class="nav-item" role="presentation">
        <a
          class="nav-link"
          id="profile-tab"
          data-bs-toggle="tab"
          href="#profile"
          role="tab"
          aria-controls="profile"
          aria-selected="false"
          >
          	{{ isset($data['backendlang']['backendlang']['Tree_View']) ? $data['backendlang']['backendlang']['Tree_View'] :'' }}
          </a
        >
    </li>
    <!-- <li class="nav-item" role="presentation">
        <a
          class="nav-link"
          id="contact-tab"
          data-bs-toggle="tab"
          href="#contact"
          role="tab"
          aria-controls="contact"
          aria-selected="false"
          >
          	Vertical View
          </a
        >
    </li> -->
</ul>
<div class="tab-content" id="myTabContent">
    <div
        class="tab-pane fade show active pt-3"
        id="home"
        role="tabpanel"
        aria-labelledby="home-tab">
        <div class="row">
			<div class="col-md-4">
				<div class="form-group">
					<a href="{{ route('tree_details', [$agent->code, '1']) }}">
						<div class="bs-callout bs-callout-info" id="callout-alerts-dismiss-plugin" style="background-color: #fff;
																									      padding: 20px;
																									      border-radius: 10px;">
				    		<div class="form-group">
				    			{{ isset($data['backendlang']['backendlang']['Level_1_Agent']) ? $data['backendlang']['backendlang']['Level_1_Agent'] :'' }}
				    		</div>
						    <div class="row">
						    	<div class="col-6">
						    		<div class="form-group">
						    			{{ isset($data['backendlang']['backendlang']['Quantity']) ? $data['backendlang']['backendlang']['Quantity'] :'' }}
						    		</div>
						    	</div>
						    	<div class="col-6" align="right">
						    		<div class="form-group">
						    			{{ $fg }}
						    		</div>
						    	</div>
						    </div>
						    <div class="row">
						    	<div class="col-md-12" align="right">
							    	<div class="progress progress-mini">
										<div class="progress-bar progress-danger" style="width: {{ $fgp }}%;"></div>
									</div>
						    	</div>

								<div class="col-6">
									<small>{{ isset($data['backendlang']['backendlang']['Percentage']) ? $data['backendlang']['backendlang']['Percentage'] :'' }}</small>
								</div>
								<div class="col-6" align="right">
									<small>{{ $fgp }}%</small>
								</div>
						    </div>
						</div>									
					</a>
				</div>	
			</div>

			<div class="col-md-4">
				<div class="form-group">
					<a href="{{ route('tree_details', [$agent->code, '2']) }}">
						<div class="bs-callout bs-callout-danger" id="callout-alerts-dismiss-plugin" style="background-color: #fff;
padding: 20px;
border-radius: 10px;">
				    		<div class="form-group">
				    			{{ isset($data['backendlang']['backendlang']['Level_2_Agent']) ? $data['backendlang']['backendlang']['Level_2_Agent'] :'' }}
				    		</div>
						    <div class="row">
						    	<div class="col-6">
						    		<div class="form-group">
						    			{{ isset($data['backendlang']['backendlang']['Quantity']) ? $data['backendlang']['backendlang']['Quantity'] :'' }}
						    		</div>
						    	</div>
						    	<div class="col-6" align="right">
						    		<div class="form-group">
						    			{{ $sg }}
						    		</div>
						    	</div>
						    </div>
						    <div class="row">
						    	<div class="col-md-12" align="right">
							    	<div class="progress progress-mini">
										<div class="progress-bar progress-danger" style="width: {{ $sgp }}%;"></div>
									</div>
						    	</div>
						    	
								<div class="col-6">
									<small>{{ isset($data['backendlang']['backendlang']['Percentage']) ? $data['backendlang']['backendlang']['Percentage'] :'' }}</small>
								</div>
								<div class="col-6" align="right">
									<small>{{ $sgp }}%</small>
								</div>
						    </div>
						</div>
					</a>
				</div>	
			</div>

			<div class="col-md-4">
				<div class="form-group">
					<a href="{{ route('tree_details', [$agent->code, '3']) }}">
						<div class="bs-callout bs-callout-warning" id="callout-alerts-dismiss-plugin" style="background-color: #fff;
padding: 20px;
border-radius: 10px;">
				    		<div class="form-group">
				    			{{ isset($data['backendlang']['backendlang']['Level_3_Agent']) ? $data['backendlang']['backendlang']['Level_3_Agent'] :'' }}
				    		</div>
						    <div class="row">
						    	<div class="col-6">
						    		<div class="form-group">
						    			{{ isset($data['backendlang']['backendlang']['Quantity']) ? $data['backendlang']['backendlang']['Quantity'] :'' }}
						    		</div>
						    	</div>
						    	<div class="col-6" align="right">
						    		<div class="form-group">
						    			{{ $tg }}
						    		</div>
						    	</div>
						    </div>
						    <div class="row">
						    	<div class="col-md-12" align="right">
							    	<div class="progress progress-mini">
										<div class="progress-bar progress-danger" style="width: {{ $tgp }}%;"></div>
									</div>
						    	</div>
						    	
								<div class="col-6">
									<small>{{ isset($data['backendlang']['backendlang']['Percentage']) ? $data['backendlang']['backendlang']['Percentage'] :'' }}</small>
								</div>
								<div class="col-6" align="right">
									<small>{{ $tgp }}%</small>
								</div>
						    </div>
						</div>
					</a>
				</div>	
			</div>

			<div class="col-md-4">
				<div class="form-group">
					<a href="{{ route('tree_details', [$agent->code, '3']) }}">
						<div class="bs-callout bs-callout-warning" id="callout-alerts-dismiss-plugin" style="background-color: #fff;
padding: 20px;
border-radius: 10px;">
				    		<div class="form-group">
				    			{{ isset($data['backendlang']['backendlang']['Level_4_Agent']) ? $data['backendlang']['backendlang']['Level_4_Agent'] :'' }}
				    		</div>
						    <div class="row">
						    	<div class="col-6">
						    		<div class="form-group">
						    			{{ isset($data['backendlang']['backendlang']['Quantity']) ? $data['backendlang']['backendlang']['Quantity'] :'' }}
						    		</div>
						    	</div>
						    	<div class="col-6" align="right">
						    		<div class="form-group">
						    			{{ $tfg }}
						    		</div>
						    	</div>
						    </div>
						    <div class="row">
						    	<div class="col-md-12" align="right">
							    	<div class="progress progress-mini">
										<div class="progress-bar progress-danger" style="width: {{ $tgp }}%;"></div>
									</div>
						    	</div>
						    	
								<div class="col-6">
									<small>{{ isset($data['backendlang']['backendlang']['Percentage']) ? $data['backendlang']['backendlang']['Percentage'] :'' }}</small>
								</div>
								<div class="col-6" align="right">
									<small>{{ $tfgp }}%</small>
								</div>
						    </div>
						</div>
					</a>
				</div>	
			</div>
		</div>
    </div>
    <div 
        class="tab-pane fade pt-3"
        id="profile"
        role="tabpanel"
        aria-labelledby="profile-tab" align="center">
		<figure>
		  <ul class="tree">
		    	<li>
		    		<span>
		    			<img src="{{ !empty($agent->profile_logo) ? asset($agent->profile_logo) : asset('images/images.png') }}" width="100px" height="100px" style="border-radius: 100%;">
		    			<br>
		    			<a href="{{ route('merchant.merchants.edit', $agent->id) }}" target="_blank">
		    				{{ $agent->f_name }} {{ $agent->l_name }} ({{ $agent->code }})
		    			</a>
		    		</span>

		      		<ul>
		      			@foreach($merchantD as $merchantDv)
		        		<li>
		        			<span>

		        				<img src="{{ !empty($merchantDv->profile_logo) ? asset($merchantDv->profile_logo) : asset('images/images.png') }}" width="100px" height="100px" style="border-radius: 100%;">
		        				<br>
		        				<a href="{{ route('merchant.merchants.edit', $merchantDv->id) }}" target="_blank">
		        					{{ $merchantDv->f_name }} {{ $merchantDv->l_name }} ({{ $merchantDv->code }})
		        				</a>
		        			</span>
		        			@if(!$mdd[$merchantDv->code]->isEmpty())
			        			<ul>

			        				@foreach($mdd[$merchantDv->code] as $mddv)
			        				<li>
			        					<span>
			        						<img src="{{ !empty($mddv->profile_logo) ? asset($mddv->profile_logo) : asset('images/images.png') }}" width="100px" height="100px" style="border-radius: 100%;">
		        							<br>
		        							<a href="{{ route('merchant.merchants.edit', $mddv->id) }}" target="_blank">
			        							{{ $mddv->f_name }} {{ $mddv->l_name }} ({{ $mddv->code }})</span>
		        							</a>

			        					@if(!$mddd[$mddv->code]->isEmpty())
			        					<ul>
			        						@foreach($mddd[$mddv->code] as $mdddv)
				        						<li>
				        							<span>
				        								<img src="{{ !empty($mdddv->profile_logo) ? asset($mdddv->profile_logo) : asset('images/images.png') }}" width="100px" height="100px" style="border-radius: 100%;">
		        										<br>
		        										<a href="{{ route('merchant.merchants.edit', $mdddv->id) }}" target="_blank">
				        									{{ $mdddv->f_name }} {{ $mdddv->l_name }} ({{ $mdddv->code }})
		        										</a>
				        							</span>

				        							@if(!$mdddd[$mdddv->code]->isEmpty())
				        							<ul>
				        								@foreach($mdddd[$mdddv->code] as $mddddv)
				        								<li>
				        									<span>
				        										<img src="{{ !empty($mddddv->profile_logo) ? asset($mddddv->profile_logo) : asset('images/images.png') }}" width="100px" height="100px" style="border-radius: 100%;">
		        												<br>
		        												<a href="{{ route('merchant.merchants.edit', $mddddv->id) }}" target="_blank">
				        											{{ $mddddv->f_name }} {{ $mddddv->l_name }} ({{ $mddddv->code }})
		        												</a>
				        									</span>
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
		    	</li>
		  </ul>
		</figure>
    </div>
</div>

@endsection
