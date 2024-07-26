<!doctype html>
<head>
	<?php include('header.php'); ?>
</head>
<body>

<div class="container">

<table class="table table-bordered table-hover ">
		<thead>
			<tr>
				<th></th>
				<th>Name</th>
				<th>Item Code</th>
				<th>Price</th>
				<th>Quantity</th>
			</tr>
		</thead>
		<tbody id="tbody">

		</tbody>
	</table>


</div>


<div id="content"></div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

<script>
$(document).ready(function(){


	$("#tbody").load("other.php");


});



</script>

</body>
</html>