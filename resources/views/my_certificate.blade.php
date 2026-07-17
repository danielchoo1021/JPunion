<style type="text/css">
	/*td{
		display: table-cell;
		vertical-align: inherit;
	}

	table{
		border-collapse: collapse;
	}

	table{
		text-indent: initial;
		border-spacing: 2px;
	}

	h1, h2, h3, h4, h5, h6, b, div{
		color: #000;
	}

	body{
		font-family: "PT Serif", serif;
		font-size: 14px;
		font-weight: 400;
		line-height: 1.5;
	}

	*{
		margin: 0;
		padding: 0;
		box-sizing: border-box;
	}

	tr{
		display: table-row;
		vertical-align: inherit;
		border-color: inherit;
	}

	tbody{
		display: table-row-group;
		vertical-align: middle;
		border-color: inherit;
	}

	div{
		display: block;
	}

	h3, .h3{
		font-size: 2rem;
		display: block;
	}

	h4, .h4{
		font-size: 1.4rem;
		display: block;
	}

	h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6{
		margin-bottom: 0.5rem;
		font-family: "PT Serif", serif;
		font-weight: 700;
		line-height: 1.2;
		margin-top: 0;
	}

	hr {
    	border-top: 1px solid rgba(0, 0, 0, 0.1);
    	margin-top: 1rem;
    	margin-bottom: 1rem;
    	border: 0;
    	box-sizing: content-box;
    	height: 0;
    	overflow: visible;
    	display: block;
    	unicode-bidi: isolate;
	}

	.table-bordered {
    	border: 1px solid #dee2e6 !important;
	}

	.table{
		width: 100%;
		max-width: 100%;
		background-color: transparent;
	}

	.table-bordered td {
    	border: 1px solid #dee2e6;
    	border-right: 1px solid #dee2e6;
    	border-left: 1px solid #dee2e6;
    	padding: .5rem;
	}

	.table-bordered th {
		border: 1px solid #dee2e6;
		border-right: 1px solid #dee2e6;
		border-left: 1px solid #dee2e6;
		padding: .5rem;
	}

	.table td, .table th {
    	
    	vertical-align: top;
    	border-top: 1px solid #dee2e6;
	}

	img{
		vertical-align: middle;
		border-style: none;
	}

	@media (min-width: 1200px){
		.container {
	    	max-width: 1140px;
		}
	}

	@media (min-width: 992px){
		.container {
		    max-width: 960px;
		}
	}

	@media (min-width: 576px){
		.container {
		    max-width: 540px;
		}
	}

	.container {
	    width: 100%;
	    padding-right: 15px;
	    padding-left: 15px;
	    margin-right: auto;
	    margin-left: auto;
	}*/





	td, th{
		padding: 0;

	}

	.table-bordered, td, th{
		border-radius: 0;
	}

	*{
		box-sizing: border-box;
	}

	td{
		display: table-cell;
		vertical-align: inherit;
	}

	h3{
		font-size: 22px;
		display: block;
	}

	h1{
		font-size: 32px;
		margin: .67em 0;
		display: block;
	}

	h4{
		font-size: 18px;
	}

	.h4, .h5, .h6, h4, h5, h6 {
	    margin-top: 10px;
	    margin-bottom: 10px;
	}

	h1, h2, h3, h4, h5, h6{
		font-weight: 400;
		font-family: "Open Sans","Helvetica Neue",Helvetica,Arial,sans-serif;
	}

	.h1, .h2, .h3, h1, h2, h3 {
	    margin-top: 20px;
	    margin-bottom: 10px;
	}

	.h1, .h2, .h3, .h4, .h5, .h6, h1, h2, h3, h4, h5, h6 {
	    line-height: 1.1;
	    color: inherit;
	}

	table{
		border-collapse: collapse;
		border-spacing: 0;
		text-indent: initial;
		background-color: transparent;
		width: 100%;
		display: table;
		max-width: 100%;
		margin-bottom: 20px;
	}

	.main-content{
		margin-left: 0;
		padding: 0;
	}

	.main-content, body, html{
		min-height: 100%;
	}

	div{
		display: block;
	}

	.main-content-inner{
		float: left;
		width: 100%;
	}

	.page-content{
		padding: 8px 20px 80px 24px;
	}

	.page-content{
		background-color: #FFF;
		position: relative;
		margin: 0;
	}

	tbody{
		display: table-row-group;
		vertical-align: middle;
		border-color: inherit;
	}

	tr{
		display: table-row;
		vertical-align: inherit;
		border-color: inherit;
	}

	.table-bordered, .table-bordered>tbody>tr>td, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>td, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>thead>tr>th {
	    border: 1px solid #ddd;
	}

	.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
	    padding: 8px;
	    line-height: 1.42857143;
	    vertical-align: top;
	    border-top: 1px solid #ddd;
	}

	body {
		font-family: 'Open Sans' !important;
	    font-size: 13px;
	    color: #393939;
	    line-height: 1.5;
	}

	b, optgroup, strong{
		font-weight: 700;
	}

</style>
<div class="main-content">
	<img src="{{ asset('images/Agent_Certificate.jpg') }}" width="100%">
	<div style="position: absolute;
				top: 25%;
				left: 45%;
				transform: translate(-25%, -45%);
				font-size: 45px;">
		{{ Auth::user()->f_name }}
	</div>

	<div style="position: absolute;
				top: 38%;
				left: 27%;
				transform: translate(-38%, -27%);">
		{{ Auth::user()->created_at }}
	</div>
</div>