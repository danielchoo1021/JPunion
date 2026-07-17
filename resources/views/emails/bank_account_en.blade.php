<!DOCTYPE html>

<html>

<head>

	<title>Kimcafe</title>
	<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
</head>

<body>
		<img src="{{ asset($data['website_logo']) }}" width="200px">
		<p><b>Dear {{ $f_name }},</b></p>
		<p><b>Please login to your account and update your bank account.</b></p>
		<p><b>Otherwise your commission cannot be sent to your bank account.</b></p>
		<p>
			Thank you
		</p>
		<p>
			Website: <a href="https://www.kimcafe.com.my">www.kimcafe.com.my</a>
		</p>
</body>

</html>