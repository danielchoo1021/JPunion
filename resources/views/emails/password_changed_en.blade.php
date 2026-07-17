<!DOCTYPE html>

<html>

<head>

	<title>Welcome to Kim Cafe</title>
	<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
</head>

<body>
		<img src="{{ asset($data['website_logo']) }}" width="200px">
		<p><b>Welcome to Kim Cafe</b></p>
		<p><b>Dear {{ $f_name }}</b></p>
		<p><b>Your account password has been reset, your new password is displayed below. Please refer to it when logging in, thank you</b></p>

		<p>
			New password : {{ $password }}
		</p>
		<p>
			Website: <a href="https://www.kimcafe.com.my">www.kimcafe.com.my</a>
		</p>
</body>

</html>