<!DOCTYPE html>

<html>

<head>

	<title>欢迎来到 Kim Cafe 线上商城！</title>
	<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
</head>

<body>
		<img src="{{ asset($data['website_logo']) }}" width="200px">
		<p><b>欢迎来到 Kim Cafe 线上商城！</b></p>
		<p><b>亲爱的 {{ $f_name }}</b></p>
		<p><b>您的户口在 {{ $upgradeDate }} 已被升职为 {{ $lvlCN }}</b></p>
		<p>
			登陆网址: <a href="https://www.kimcafe.com.my">www.kimcafe.com.my</a>
		</p>
</body>

</html>