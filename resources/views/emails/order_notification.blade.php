<!DOCTYPE html>

<html>

<head>

	<title>Dot Com Dot Eye</title>
	<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
</head>

<body>
	@if($type == 1)
		<p>Dear Admin,</p>
		<p>You have one new agent register</p>
	@endif

	@if($type == 2)
		<p>Dear {{ $detail->f_name }} {{ $detail->l_name }},</p>
		<p>Your account(Dot com dot eye) has been approved by admin</p>
	@endif
</body>

</html>