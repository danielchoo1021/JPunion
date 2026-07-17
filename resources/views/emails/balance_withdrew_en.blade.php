<!DOCTYPE html>

<html>

<head>

	<title>Kimcafe</title>
	<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
</head>

<body>
		<img src="{{ asset($data['website_logo']) }}" width="200px">
		<p><b>Dear {{ $f_name }},</b></p>
		<p><b>You have earned a total of RM {{ $balance }} this month</b></p>

		<p>
			Our company will transfer this amount to your bank account within 2 weeks.
		</p>
		<p>
			Thank you
		</p>
		<p>
			Website: <a href="https://www.kimcafe.com.my">www.kimcafe.com.my</a>
		</p>
</body>

</html>