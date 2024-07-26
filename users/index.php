<?php 

session_start();


?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>My Cart - CartCulator</title>
	<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	
	<style>
		@import url('https://fonts.googleapis.com/css2?family=Manrope:wght@200&display=swap');

body {
  font-family: 'Manrope', sans-serif;
  background:#eee;
}

.size span {
  font-size: 11px;
}

.color span {
  font-size: 11px;
}

.product-deta {
  margin-right: 70px;
}

.gift-card:focus {
  box-shadow: none;
}

.pay-button {
  color: #fff;
}

.pay-button:hover {
  color: #fff;
}

.pay-button:focus {
  color: #fff;
  box-shadow: none;
}

.text-grey {
  color: #a39f9f;
}

.qty i {
  font-size: 11px;
}
	</style>
</head>
<body>

		<div class="container mt-5 mb-5">
            <div class="d-flex justify-content-center row">
                <div class="col-md-8">


                    <div class="p-2">
                        <h4>My cart</h4>
                    </div>

<table class="table table-striped ">
  <thead>
    <tr>
      <th style="width: 10px">#</th>
      <th scope="col">Item Code</th>
      <th scope="col">Item Name</th>
      <th scope="col">QTY</th>
      <th scope="col">Amount</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody id="content">

  </tbody>
</table>


<div id="sub">
<div class="d-flex flex-row align-items-center mt-3 p-2 bg-white rounded"><input type="text" class="form-control border-0 gift-card" placeholder="Grand Total"><button style="width: 150px" class="btn btn-outline-warning ml-2" type="button">
<?php if(isset($_SESSION['gt'])){ ?>
	&#8369; <?= number_format($_SESSION['gt'], 2); ?>
<?php }else{ ?>
	&#8369; 0
<?php } ?></button></div>

<div class="d-flex flex-row align-items-center mt-3 p-2 bg-white rounded"><button class="btn btn-warning btn-block btn-lg ml-2 pay-button" type="button">Proceed to Pay</button></div>
</div>

            </div>
        </div>


	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>

	<script>
		$(document).ready(function(){
			$("#content").load("fetch.php");
		})
	</script>
</body>
</html>