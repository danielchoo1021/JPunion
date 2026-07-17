@extends('layouts.app')
@section('css')
<style type="text/css">
	.profile-own-bg{
		padding: 10px 0 120px 0;
	}

	.sl-personal-header{
		background-image: linear-gradient(180deg, transparent, #82ADA6);
	}

	p{
		margin-bottom: 0px;
	}

	.card{
	  min-height: 475px;
	  margin: 2rem;
	  border-radius: 15px;
	  border: none;
	}

	.card .header{
	  display: flex;
	  justify-content: space-between;
	  align-items: center;
	  padding: 2rem;
	  color: #ddd;
	}

	.card .header .title{
	  font-weight: 300;
	}

	.one .sort{
	  display: flex;
	  justify-content: center;
	  align-items: center;
	  color: #fff;
	  font-size: 14.1px;
	}

	.one .sort .day{
	  padding: 0.4rem 1.2rem;
	  margin: 0 0.1rem;
	  cursor: pointer;
	}

	.one .sort .day.active,
	.one .sort .day:active{
	  background: rgba(210, 255, 213, 0.3);
	  border-radius: 25px;
	}

	::selection{
	  background: rgba(210, 255, 213, 0.3);
	}

	.photo{
	  width: 75px;
	  background: #fff;
	  border-radius: 50%;
	  border: 5px solid #82ada6;
	  box-shadow: 0 0 20px #82ada6;
	  margin: 1rem 0;
	}

	.main{
	  width: 85px;
	}

	.profile{
	  display: flex;
	  justify-content: center;
	  align-items: center;
	  margin-top: 1rem;
	}

	.profile .person{
	  display: flex;
	  margin: 1rem 10%;
	  justify-content: center;
	  align-items: center;
	  flex-direction: column;
	}

	.profile .person.first{
	  z-index: 10;
	  transform: translateY(-10%);
	}

	.first .fa-crown{
	  color: gold;
	  /*filter: drop-shadow(0px 0px 5px gold);*/
	}

	.second .fa-crown{
	  color: silver;
	  /*filter: drop-shadow(0px 0px 5px silver);*/
	}

	.third .fa-crown{
	  color: #CD7F32;
	  /*filter: drop-shadow(0px 0px 5px #CD7F32);*/
	  font-size: 1.3rem;
	}

	.num{
	  color: black;
	}

	.fa-caret-up{
	  color: #82ada6;
	  font-size: 21px;
	}

	.link{
	  margin: 0.2rem 0;
	  color: #000;
	  margin-top: -0.3rem;
	  font-size: 13px;
	}

	.points{
	  color: #000;
	  font-size: 17px;
	}

	.second{
	  margin-right: -0.7rem !important;
	}

	.third{
	  margin-left: -0.7rem !important;
	}

	.p_img{
	  width: 50px;
	  background: #fff;
	  border-radius: 50%;
	}

	.flex{
	  display: flex;
	  align-items: center;
	}

	.others{
	  display: flex;
	  width: 100%;
	  margin-top: 1rem;
	  align-items: center;
	  justify-content: center;
	}

	.info{
	  display: flex;
	  justify-content: space-between;
	  align-items: center;
	  border-radius: 30px;
	  background: #ffd907;
	  min-width: 320px;
	  max-width: 450px;
	  width: 100%;
	}

	.info .points{
	  margin-left: 0.2rem;
	  margin-right: 1.2rem;
	}

	.info .link{
	  margin: 0 1rem;
	}

	.rank{
	  display: flex;
	  align-items: center;
	  margin: 0 1rem;
	  flex-direction: column-reverse;
	}

	.rank i{
	  margin-top: -5px !important;
	}

	.rank .num{
	  margin: 0 !important; 
	}

	.link{
		white-space: nowrap;
	  overflow: hidden;
	  text-overflow: ellipsis;
	  width: 200px;
	}

	.first .link, .second .link, .third .link{
			
		  width: 100px;
	}

	.blink_me {
	  animation: breathing 3s ease-out;
	  -webkit-font-smoothing: antialiased;
	}

	@-webkit-keyframes breathing {
	  0% {
	    -webkit-transform: scale(1.1);
	    transform: scale(1.1);
	  }

	  25% {
	    -webkit-transform: scale(1);
	    transform: scale(1);
	  }

	  60% {
	    -webkit-transform: scale(1.1);
	    transform: scale(1.1);
	  }

	  100% {
	    -webkit-transform: scale(1);
	    transform: scale(1);
	  }
	}
</style>
@endsection
@section('content')
<div class="">
	<div class="personal-header-info"  style="position: relative; padding-top: 10px;">
			<div class="container">
				<div class="row">
					<div class="col-4" align="left">
						<a href="{{ route('profile') }}">
							<p style="color: black;"><i class="fa fa-chevron-left"></i> {{ isset($data['lang']['lang']['back']) ? $data['lang']['lang']['back'] :'Back'}}</p>
						</a>
					</div>
					<div class="col-4" align="left">
						<p align="center" class="header-title">
							<!-- {{ isset($data['lang']['lang']['my_qrcode']) ? $data['lang']['lang']['my_qrcode'] :'我的二维码'}} -->
							{{ isset($data['lang']['lang']['my_ranking']) ? $data['lang']['lang']['my_ranking'] :'My Ranking'}}
						</p>
					</div>
					<div class="col-4" align="right">
						<a href="{{ route('my_setting') }}" class="setting-btn">
							<i class="fa fa-cog" style="font-size: 20px;"></i>
						</a>
					</div>
				</div>
			</div>

		<div class="container mb-5">
			<br>
			<br>
			<br>
			<!-- <div class="form-group" align="center">
				<form method="GET" action="">
					<select class="form-control" name="filter_sales" style="height: auto;" onchange="this.form.submit()">
						<option {{ (request('filter_sales') == '1') ? 'selected' : '' }} value="1">Top Personal Sales (Monthly)</option>
						<option {{ (request('filter_sales') == '2') ? 'selected' : '' }}  value="2">Top Group Sales (Monthly)</option>
						<option {{ (request('filter_sales') == '3') ? 'selected' : '' }}  value="3">Top Daily Sales (Daily)</option>
						<option {{ (request('filter_sales') == '4') ? 'selected' : '' }}  value="4">Top Rookie Sales (Monthly)</option>
					</select>					
				</form>
			</div> -->

			<div class="form-group" align="center">
						@if(!empty($ownRank[Auth::user()->code][0]))
							<div class="rest own-ranking" align="center">
								My Ranking
						    	<div class="others flex">
						      		<div class="rank">
						        		<i class="fas fa-caret-up"></i>
						        		<p class="num">{{ $ownRank[Auth::user()->code][5] }}</p>
						      		</div>
						      		<div class="info flex">
						        		<!-- <img src="" alt="" class="p_img"> -->
						        		<div style="background-image: url('{{ !empty(Auth::user()->profile_logo) ? asset(Auth::user()->profile_logo) : asset('images/images.png') }}');
						      					background-repeat: no-repeat;
						      					background-size: cover;
						      					background-position: center;
						      					width: 50px;
						      					height: 50px;
						      					border-radius: 100%;" class="p_img">
						      			</div>
						        		<p class="link">{{ Auth::user()->f_name }}</p>
						        		<p class="points">RM {{ number_format($ownRank[Auth::user()->code][0], 2) }}</p>
						      		</div>
						    	</div>
						  	</div>
					  	@endif
					  	<hr>
					  	<div class="profile">
					  		@php
					    		$s_profile_sales = !empty($getSecond[0]) ? $getSecond[0] : 0;
					    		$s_profile_code = !empty($getSecond[1]) ? $getSecond[1] : "-";
					    		$s_profile_name = !empty($getSecond[2]) ? $getSecond[2] : "User";
					    		$s_profile_logo = !empty($getSecond[3]) ? $getSecond[3] : "images/images.png";
					    		$s_profile_lvl = !empty($getSecond[4]) ? $getSecond[4] : "-";
					    	@endphp
					    	<div class="person second" id="{{ ($s_profile_code == Auth::user()->code) ? 'own' : '' }}">
					      		<div class="num">2</div>
					      		<i class="fas fa-crown fa-2x"></i>
					      		<div style="background-image: url('{{ asset($s_profile_logo) }}');
					      					background-repeat: no-repeat;
					      					background-size: cover;
					      					background-position: center;
					      					width: 80px;
					      					height: 80px;
					      					border-radius: 100%;" class="photo">
					      		</div>
					      		<!-- <img src="{{ asset($s_profile_logo) }}" alt="" class="photo"> -->
				      			<p class="link">{{ $s_profile_name }}</p>
					      		<p class="points">RM {{ number_format($s_profile_sales, 2) }}</p>
					    	</div>
					    	@php
					    		$f_profile_sales = !empty($getFirst[0]) ? $getFirst[0] : 0;
					    		$f_profile_code = !empty($getFirst[1]) ? $getFirst[1] : "-";
					    		$f_profile_name = !empty($getFirst[2]) ? $getFirst[2] : "User";
					    		$f_profile_logo = !empty($getFirst[3]) ? $getFirst[3] : "images/images.png";
					    		$f_profile_lvl = !empty($getFirst[4]) ? $getFirst[4] : "-";
					    	@endphp
					    	<div class="person first" id="{{ ($f_profile_code == Auth::user()->code) ? 'own' : '' }}">
					      		<div class="num">1</div>
					      		<i class="fas fa-crown fa-3x"></i>
					      		<div style="background-image: url('{{ asset($f_profile_logo) }}');
					      					background-repeat: no-repeat;
					      					background-size: cover;
					      					background-position: center;
					      					width: 100px;
					      					height: 100px;
					      					border-radius: 100%;" class="photo main">
					      		</div>
					      		<!-- <img src="{{ asset($f_profile_logo) }}" alt="" class="photo main"> -->
					      		<p class="link">{{ $f_profile_name }}</p>
					      		<p class="points">RM {{ number_format($f_profile_sales, 2) }}</p>
					    	</div>
					    	@php
					    		$t_profile_sales = !empty($getThird[0]) ? $getThird[0] : 0;
					    		$t_profile_code = !empty($getThird[1]) ? $getThird[1] : "-";
					    		$t_profile_name = !empty($getThird[2]) ? $getThird[2] : "User";
					    		$t_profile_logo = !empty($getThird[3]) ? $getThird[3] : "images/images.png";
					    		$t_profile_lvl = !empty($getThird[4]) ? $getThird[4] : "-";
					    	@endphp
					    	<div class="person third" id="{{ ($t_profile_code == Auth::user()->code) ? 'own' : '' }}">
					      		<div class="num">3</div>
					      		<i class="fas fa-crown"></i>
					      		<div style="background-image: url('{{ asset($t_profile_logo) }}');
					      					background-repeat: no-repeat;
					      					background-size: cover;
					      					background-position: center;
					      					width: 80px;
					      					height: 80px;
					      					border-radius: 100%;" class="photo">
					      		</div>
					      		<!-- <img src="{{ asset($t_profile_logo) }}" alt="" class="photo"> -->
					      		<p class="link">{{ $t_profile_name }}</p>
					      		<p class="points">RM {{ number_format($t_profile_sales, 2) }}</p>
					    	</div>
					  	</div>
						@foreach($top_agent_sales_rankings as $key => $top_agent_sales_ranking)
							@if($key > 2)
							  	<div class="rest" id="{{ ($top_agent_sales_ranking->code == Auth::user()->code) ? 'own' : '' }}">
							    	<div class="others flex">
							      		<div class="rank">
							        		<i class="fa fa-caret-up"></i>
							        		<p class="num">{{ $key+1 }}</p>
							      		</div>
							      		<div class="info flex">
							        		<!-- <img src="" alt="" class="p_img"> -->
							        		<div style="background-image: url('{{ !empty($top_agent_sales_ranking->profile_logo) ? asset($top_agent_sales_ranking->profile_logo) : asset('images/images.png') }}');
								      					background-repeat: no-repeat;
								      					background-size: cover;
								      					background-position: center;
								      					width: 50px;
								      					height: 50px;
								      					border-radius: 100%;" class="p_img">
								      		</div>
							        		<p class="link">{{ $top_agent_sales_ranking->f_name }}</p>
							        		<p class="points">RM {{ number_format($top_agent_sales_ranking->totalSales, 2) }}</p>
							      		</div>
							    	</div>
							  	</div>
							@endif
						@endforeach	
			</div>
		</div>
	</div>
</div>
@endsection

@section('js')
<script type="text/javascript">
    $('.own-ranking').click(function(){
    		var ele = $(this);

    		var el = $('#own');
		    var elOffset = el.offset().top;
		    var elHeight = el.height();
		    var windowHeight = $(window).height();
		    var offset;

		    if (elHeight < windowHeight) {
		        offset = elOffset - ((windowHeight / 2) - (elHeight / 2));
		    }else{
		        offset = elOffset;
		    }
		    var speed = 700;
		    $('html, body').animate({scrollTop:offset}, speed, function(){
		    		$('#own').addClass('blink_me');

	    			setInterval(function(){ $('#own').removeClass('blink_me'); }, 3000);	
		    });
    });
</script>
@endsection