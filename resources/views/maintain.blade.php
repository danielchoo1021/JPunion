<!--
Author: W3layouts
Author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->
<!DOCTYPE html>
<html>
<head>
<title>Site Under Maintenance </title>
<!-- custom-theme -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Site Under Maintenance Responsive web template, Bootstrap Web Templates, Flat Web Templates, Android Compatible web template, Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyEricsson, Motorola web design" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false);
		function hideURLbar(){ window.scrollTo(0,1); } </script>
<!-- //custom-theme -->
<link href="{{ asset('maintainence/css/font-awesome.css') }}" rel="stylesheet">
<link href="{{ asset('maintainence/css/style.css') }}" rel="stylesheet" type="text/css" media="all" />
<link href="//fonts.googleapis.com/css?family=Hind+Siliguri:300,400,500,600,700" rel="stylesheet">
</head>
<body class="bg-agileinfo">
   <h1 class="agile-head text-center">site Under maintenance</h1>
	<div class="container-w3">
		<div class="content1-w3layouts"> 
			<img src="{{ asset('maintainence/images/2.png') }}" alt="under-construction">
			<p class="text-center">Sorry for the inconvenience.To improve our services, we have momentarily shutdown our site.</p>
		</div>
		<div class="demo2"></div>
		<!-- <div class="content2-w3-agileits">
		   <form action="#" method="post" class="agile-info-form">
				<input type="email" class="email" placeholder="Enter your email address" required="">
				<input type="submit" value="get notified!">
				<div class="clear"> </div> 
			</form>	
		</div> -->
	</div>	
	<script type="text/javascript" src="{{ asset('maintainence/js/jquery-1.11.1.min.js') }}"></script>
	<link rel="stylesheet" href='{{ asset("maintainence/css/dscountdown.css") }}' type='text/css' media='all' />
	<!-- Counter required files -->
		<script type="text/javascript" src="{{ asset('maintainence/js/dscountdown.min.js') }}"></script>
		<script>
			jQuery(document).ready(function($){						
				$('.demo2').dsCountDown({
					endDate: new Date("december 31, 2019 23:59:00"),
					theme: 'black'
				});								
			});
		</script>
	<!-- //Counter required files -->
</body>
</html>