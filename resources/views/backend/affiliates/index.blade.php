@extends('layouts.admin_app')

@section('content')
<div class="affiliate_list">
	<div class="affliate-details-background">
		<div class="users-details-box">
			@if(!empty($profile_logo))
				<div class="user-details-img" style="background-image: url({{ asset($profile_logo) }})"></div>
			@else
				<div class="user-details-img" style="background-image: url({{ asset('images/images.png') }})"></div>
			@endif
		</div>
		<div class="users-details-box white user-name">
			Vesson - {{ $name }}
		</div>
		<div style="clear: both;"></div>

		<div class="row totalResult">
			<div class="col-4 white" align="center">
				<div class="form-group">
					<b>{{ $OwnTotalAffiliate }}</b><br>
					<b>{{ isset($data['backendlang']['backendlang']['Total_Cumulative_Number']) ? $data['backendlang']['backendlang']['Total_Cumulative_Number'] :'' }}</b>
				</div>
			</div>
			<div class="col-4 white" align="center">
				<div class="form-group">
					<b>{{ $OwnMonthlyTotalAffiliate }}</b><br>
					<b>{{ isset($data['backendlang']['backendlang']['This_Month_New']) ? $data['backendlang']['backendlang']['This_Month_New'] :'' }}</b>
				</div>
			</div>
			<div class="col-4 white" align="center">
				<div class="form-group">
					<b>{{ $GetSelectedUserDailyTotalAffiliates }}</b><br>
					<b>{{ isset($data['backendlang']['backendlang']['Today_New']) ? $data['backendlang']['backendlang']['Today_New'] :'' }}</b>
				</div>
			</div>
		</div>
	</div>
	<div class="affiliate-search-area">
		<form method="GET" action="{{ route('affiliates', $code)}}">
			<div class="input-group">
	            <input type="text" name="name" class="form-control search-query" placeholder="{{ isset($data['backendlang']['backendlang']['Search_Affiliates']) ? $data['backendlang']['backendlang']['Search_Affiliates'] :'' }}">
	            <span class="input-group-btn">
	                <button type="submit" class="btn btn-inverse btn-white search-button" style="outline: none;">
	                    <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>
	                    {{ isset($data['backendlang']['backendlang']['Search']) ? $data['backendlang']['backendlang']['Search'] :'' }}
	                </button>
	            </span>
	        </div>
	    </form>
	</div>
	<div class="affiliate-search-area">
		<form method="GET" action="{{ route('affiliates', $code)}}">
			<div class="input-group">
	            <input type="text" name="aff_code" class="form-control search-query" placeholder="{{ isset($data['backendlang']['backendlang']['Search_By_Agent_Code']) ? $data['backendlang']['backendlang']['Search_By_Agent_Code'] :'' }}">
	            <span class="input-group-btn">
	                <button type="submit" class="btn btn-inverse btn-white search-button" style="outline: none;">
	                    <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>
	                    {{ isset($data['backendlang']['backendlang']['Search']) ? $data['backendlang']['backendlang']['Search'] :'' }}
	                </button>
	            </span>
	        </div>
	    </form>
	</div>
	<div class="form-group affiliate-list-area">
		<ul>
			@if(!$affiliates->isEmpty())
			@foreach($affiliates as $affiliate)
			<li>
				<a href="{{ route('affiliates', $affiliate->code) }}">
					<div class="users-details-box">
						@if(!empty($affiliate->profile_logo))
							<div class="users-img" style="background-image: url({{ asset($affiliate->profile_logo) }})"></div>
						@else
							<div class="users-img" style="background-image: url({{ asset('images/images.png') }})"></div>
						@endif
					</div>
					<div class="users-details-box">
							{{ $affiliate->l_name }}{{ $affiliate->f_name }} ({{ $affiliate->code }})<br>
							{{ $affiliate->created_at }}<br>
							{{ isset($data['backendlang']['backendlang']['Today_New']) ? $data['backendlang']['backendlang']['Today_New'] :'' }}: {{ $TodayTotalAffiliates[$affiliate->code] }}
					</div>
					<div class="users-details-box view-affiliate">
						{{ $TotalAffiliates[$affiliate->code] }} {{ isset($data['backendlang']['backendlang']['People']) ? $data['backendlang']['backendlang']['People'] :'' }} <i class="fa fa-chevron-right"></i>
					</div>
					<div style="clear: both;"></div>
				</a>
			</li>
			@endforeach
			@else
			<li>
				{{ isset($data['backendlang']['backendlang']['No_Affiliate_Yet']) ? $data['backendlang']['backendlang']['No_Affiliate_Yet'] :'' }}!
			</li>
			@endif
		</ul>
	</div>
</div>
@endsection