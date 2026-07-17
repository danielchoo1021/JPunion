<!DOCTYPE html>

<html>

<head>

	<title>Kireina</title>
	<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
</head>

<body>
	<p>Thank you for registering with us!</p>
	<p>Your account details is stated below:</p>
	<p><b>Name</b>: {{ $user->f_name }}</p>
	<p><b>IC Number</b>: {{ $user->ic }}</p>
	<p><b>Code</b>: {{ $user->display_code }}{{ $user->display_running_no }}</p>
</body>

</html>