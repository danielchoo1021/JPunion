<!DOCTYPE html>

<html>

<head>

	<title>Kimcafe</title>
	<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
</head>

<body>
		<img src="{{ asset($data['website_logo']) }}" width="200px">
		<p><b>你好 {{ $f_name }},</b></p>
		<p><b>这个月您已赚取 RM {{ $balance }}</b></p>

		<p>
			公司将会在2个星期之内转发进入您的银行户口。
		</p>
		<p>
			谢谢
		</p>
		<p>
			Website: <a href="https://www.kimcafe.com.my">www.kimcafe.com.my</a>
		</p>
</body>

</html>