<!DOCTYPE html>

<html>

<head>

	<title>Welcome to Kimcafe</title>
	<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
</head>

<body>
		<img src="{{ asset($data['website_logo']) }}" width="200px">
		<p><b>Welcome to Kimcafe</b></p>
		<p><b>Dear {{ $f_name }}</b></p>
		<p><b>Your ic has been changed by admin, please use the IC below to login to our website!</b></p>

		<p>
			This is your new IC: {{ $ic }}
		</p>
		<p>
			Website: <a href="https://www.kimcafe.com.my">www.kimcafe.com.my</a>
		</p>
		<p>
			Thank you for being a part of kimcafe.
		</p>
</body>

</html>