<!DOCTYPE html>

<html>

<head>

	<title>Kireina</title>
	<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
</head>

<body>
	<div class="container mt-5">
		<h3>New Order Received</h3>
		<span>#{{ $t_no }}</span>
		<hr>
		<table class="table table-bordered">
			<thead style="border:1px solid #ddd">
				<th style="border:1px solid #ddd">Product Image</th>
				<th style="border:1px solid #ddd">Product Details</th>
				<th style="border:1px solid #ddd">Unit Price</th>
				<th style="border:1px solid #ddd">Quantity</th>
				<th style="border:1px solid #ddd">Total Amount</th>
			</thead>
			<tbody>
				@foreach ($t_details as $item)
				<tr style="border:1px solid #ddd">
					<td style="border:1px solid #ddd">
						<img src="{{asset($item->product_image)}}" width="80" height="80" class="img-thumbnail" alt="">
					</td>
					<td style="border:1px solid #ddd">{{ $item->product_name }}</td>
					<td style="border:1px solid #ddd">RM {{ number_format($item->unit_price,2) }}</td>
					<td style="border:1px solid #ddd">{{ $item->quantity }}</td>
					<td style="border:1px solid #ddd">RM {{ number_format($item->unit_price * $item->quantity,2) }}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</body>

</html>