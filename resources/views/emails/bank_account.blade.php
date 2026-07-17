<!DOCTYPE html>

<html>

<head>

	<title>Kimcafe</title>
	<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
</head>

<body>
		<img src="{{ asset($data['website_logo']) }}" width="200px">
		<p><b>你好 {{ $f_name }},</b></p>
		<p><b>请登入您的账户，更新您的银行户口.</b></p>
		<p><b>否则 佣金不会颁发出去您的银行户口.</b></p>
		<p>
			谢谢
		</p>
		<p>
			Website: <a href="https://www.kimcafe.com.my">www.kimcafe.com.my</a>
		</p>
</body>

</html>