<!DOCTYPE html>

<html>

<head>

	<title>Kimcafe</title>
	<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
</head>

<body>
		<img src="{{ asset($data['website_logo']) }}" width="200px">
		<p><b>你好 {{ $f_name }},</b></p>
		<p><b>上个月您没有更新银行户口资料，这个月您已赚取 RM {{ $balance }} ，将会保留至下个月的奖金颁发时间，请到您的户口更新银行户口以避免拿不到奖金。</b></p>
		<p>
			谢谢
		</p>
		<p>
			Website: <a href="https://www.kimcafe.com.my">www.kimcafe.com.my</a>
		</p>
</body>

</html>