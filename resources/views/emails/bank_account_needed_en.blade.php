<!DOCTYPE html>

<html>

<head>

	<title>Kimcafe</title>
	<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
</head>

<body>
		<img src="{{ asset($data['website_logo']) }}" width="200px">
		<p><b>Dear {{ $f_name }},</b></p>
		<p><b>You have not updated your bank account details, you have earned a total of RM {{ $balance }} this month, the balance will be accumulated until next month's commission payment, please login to your account to update your bank account details to ensure you receive your commission.</b></p>
		<p>
			Thank you
		</p>
		<p>
			Website: <a href="https://www.kimcafe.com.my">www.kimcafe.com.my</a>
		</p>
</body>

</html>