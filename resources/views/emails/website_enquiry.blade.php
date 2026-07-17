<!DOCTYPE html>

<html>

<head>

	<title>{{ $data['website_name'] }}</title>
</head>

<body>
		<p><b>New Enquiry Filled In Website</b></p>
		<p><b>Name: {{ $name }}</b></p>
		<p><b>Email Address: {{ $from_email }}</b></p>
		<p><b>Phone Number: ({{ $country_code }}){{ $phone }}</b></p>
		<p><b>Message: {{ $message_content }}</b></p>
</body>

</html>