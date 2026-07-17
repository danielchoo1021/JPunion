@extends('layouts.app')

@section('content')
<div class="container imag">
	<div class="form-group">
		<table class="table table-bordered">
			<tr>
				<th width="20%">Model</th>
				<th style="text-align: center;">EPLAY 3R</th>
				<th style="text-align: center;">EVPAD 3S MY</th>
			</tr>
			<tr>
				<td width="20%">Product</td>
				<td>
					<img src="{{ asset('frontend/img/21513772101864_809.jpg')  }}" width="100%">
				</td>
				<td>
					<img src="{{ asset('frontend/img/ad547ad4796ce72f0d8784674665c00f.jpg') }}" width="100%">
				</td>
			</tr>
			<tr>
				<td width="20%">Price</td>
				<td align="center">399</td>
				<td align="center">499</td>
			</tr>
			<tr>
				<td width="20%">RAM + ROM</td>
				<td align="center">2+8GB</td>
				<td align="center">2+16GB</td>
			</tr>
			<tr>
				<td width="20%">WIFI</td>
				<td align="center">2.4Ghz</td>
				<td align="center">2.4Ghz</td>
			</tr>
			<tr>
				<td width="20%">Bluetooth</td>
				<td align="center">BT 4.2</td>
				<td align="center">BT 4.2</td>
			</tr>
			<tr>
				<td width="20%">Main Chip</td>
				<td align="center">H6</td>
				<td align="center">H6</td>
			</tr>
			<tr>
				<td width="20%">Support Maximum</td>
				<td align="center">6K</td>
				<td align="center">6K</td>
			</tr>
			<tr>
				<td width="20%">Android</td>
				<td align="center">7.0</td>
				<td align="center">7.0</td>
			</tr>
			<tr>
				<td width="20%">Additional Drama</td>
				<td align="center"><i class="fa fa-times fa-2x" style="color: red;"></i></td>
				<td align="center">
					<li style="width: 100%;">KOREA&nbsp;&nbsp;&nbsp;&nbsp;</li>
					<li style="width: 100%;">US&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>
					<li style="width: 100%;">CANADA</li>
					<li style="width: 100%;">Thai&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>
				</td>
			</tr>
			<tr>
				<td width="20%">Live Channel</td>
				<td align="center"><i class="fa fa-times fa-2x" style="color: red;"></i></td>
				<td align="center"><i class="fa fa-check fa-2x" style="color: green;"></i></td>
			</tr>
			<tr>
				<td width="20%">Sirim & MCMC</td>
				<td align="center"><i class="fa fa-check fa-2x" style="color: green;"></i></td>
				<td align="center"><i class="fa fa-check fa-2x" style="color: green;"></i></td>
			</tr>
			<tr>
				<td width="20%">USB 3.0</td>
				<td align="center"><i class="fa fa-check fa-2x" style="color: green;"></i></td>
				<td align="center"><i class="fa fa-check fa-2x" style="color: green;"></i></td>
			</tr>
			<tr>
				<td width="20%">Dolby Sound</td>
				<td align="center"><i class="fa fa-check fa-2x" style="color: green;"></i></td>
				<td align="center"><i class="fa fa-check fa-2x" style="color: green;"></i></td>
			</tr>
			<tr>
				<td width="20%">IR</td>
				<td align="center"><i class="fa fa-check fa-2x" style="color: green;"></i></td>
				<td align="center"><i class="fa fa-check fa-2x" style="color: green;"></i></td>
			</tr>
			<tr>
				<td width="20%">LED Digital Display</td>
				<td align="center"><i class="fa fa-check fa-2x" style="color: green;"></i></td>
				<td align="center"><i class="fa fa-check fa-2x" style="color: green;"></i></td>
			</tr>
		</table>
	</div>
</div>
@endsection