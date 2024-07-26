<?php 
include_once '../connections/connect.php';
$con = $lib->openConnection();

$total_amount = $_GET['total_amount'];
$amount_tendered = $_GET['total_amount'];
$t_id = $_GET['t_id'];
// $items = $_GET['items'];

$check_cart1 = $con->prepare("SELECT * FROM cart
INNER JOIN items 
ON cart.item_id = items.id");
$check_cart1->execute();

$today = date("Y-m-d, H:i:s");



if($check_cart1->rowCount() > 0){


while($val1 = $check_cart1->fetch()){

	$insert2 = $con->prepare("INSERT INTO stocks (`item_id`,`type`,`qty`,`price`) VALUES (?,?,?,?)");
	$insert2->execute([$val1['item_id'], 2, 1, $val1['price']]);
	$last_id = $con->lastInsertId();

	$insert = $con->prepare("INSERT INTO sales (`user_id`,`total_amount`,`amount_tendered`,`t_id`, `payment_method`,`item_ids`, `inventory_ids`) VALUES (?,?,?,?,?,?,?)");
	$done = $insert->execute([1, $total_amount, $amount_tendered, $t_id, 'Paypal',$val1['item_id'], $last_id]);

	if($done){

		$delete_cart_item = $con->prepare("DELETE FROM cart");
		$delete_cart_item->execute();
	}

}

}




	?>
	<script>window.alert('Payment Successful!');</script>

	
	<!DOCTYPE html>
	<head>
		<title>Receipt | CartCulator</title>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
		<style>
			@media print {
				#print {
					display: none;
				}
			}
			.receipt-content .logo a:hover {
			text-decoration: none;
			color: #7793C4; 
			}

			.receipt-content .invoice-wrapper {
			background: #FFF;
			border: 1px solid #CDD3E2;
			box-shadow: 0px 0px 1px #CCC;
			padding: 40px 40px 60px;
			margin-top: 40px;
			border-radius: 4px; 
			}

			.receipt-content .invoice-wrapper .payment-details span {
			color: #A9B0BB;
			display: block; 
			}
			.receipt-content .invoice-wrapper .payment-details a {
			display: inline-block;
			margin-top: 5px; 
			}

			.receipt-content .invoice-wrapper .line-items .print a {
			display: inline-block;
			border: 1px solid #9CB5D6;
			padding: 13px 13px;
			border-radius: 5px;
			color: #708DC0;
			font-size: 13px;
			-webkit-transition: all 0.2s linear;
			-moz-transition: all 0.2s linear;
			-ms-transition: all 0.2s linear;
			-o-transition: all 0.2s linear;
			transition: all 0.2s linear; 
			}

			.receipt-content .invoice-wrapper .line-items .print a:hover {
			text-decoration: none;
			border-color: #333;
			color: #333; 
			}

			.receipt-content {
			background: #ECEEF4; 
			}
			@media (min-width: 1200px) {
			.receipt-content .container {width: 900px; } 
			}

			.receipt-content .logo {
			text-align: center;
			margin-top: 50px; 
			}

			.receipt-content .logo a {
			font-family: Myriad Pro, Lato, Helvetica Neue, Arial;
			font-size: 36px;
			letter-spacing: .1px;
			color: #555;
			font-weight: 300;
			-webkit-transition: all 0.2s linear;
			-moz-transition: all 0.2s linear;
			-ms-transition: all 0.2s linear;
			-o-transition: all 0.2s linear;
			transition: all 0.2s linear; 
			}

			.receipt-content .invoice-wrapper .intro {
			line-height: 25px;
			color: #444; 
			}

			.receipt-content .invoice-wrapper .payment-info {
			margin-top: 25px;
			padding-top: 15px; 
			}

			.receipt-content .invoice-wrapper .payment-info span {
			color: #A9B0BB; 
			}

			.receipt-content .invoice-wrapper .payment-info strong {
			display: block;
			color: #444;
			margin-top: 3px; 
			}

			@media (max-width: 767px) {
			.receipt-content .invoice-wrapper .payment-info .text-right {
			text-align: left;
			margin-top: 20px; } 
			}
			.receipt-content .invoice-wrapper .payment-details {
			border-top: 2px solid #EBECEE;
			margin-top: 30px;
			padding-top: 20px;
			line-height: 22px; 
			}


			@media (max-width: 767px) {
			.receipt-content .invoice-wrapper .payment-details .text-right {
			text-align: left;
			margin-top: 20px; } 
			}
			.receipt-content .invoice-wrapper .line-items {
			margin-top: 40px; 
			}
			.receipt-content .invoice-wrapper .line-items .headers {
			color: #A9B0BB;
			font-size: 13px;
			letter-spacing: .3px;
			border-bottom: 2px solid #EBECEE;
			padding-bottom: 4px; 
			}
			.receipt-content .invoice-wrapper .line-items .items {
			margin-top: 8px;
			border-bottom: 2px solid #EBECEE;
			padding-bottom: 8px; 
			}
			.receipt-content .invoice-wrapper .line-items .items .item {
			padding: 10px 0;
			color: #696969;
			font-size: 15px; 
			}
			@media (max-width: 767px) {
			.receipt-content .invoice-wrapper .line-items .items .item {
			font-size: 13px; } 
			}
			.receipt-content .invoice-wrapper .line-items .items .item .amount {
			letter-spacing: 0.1px;
			color: #84868A;
			font-size: 16px;
			}
			@media (max-width: 767px) {
			.receipt-content .invoice-wrapper .line-items .items .item .amount {
			font-size: 13px; } 
			}

			.receipt-content .invoice-wrapper .line-items .total {
			margin-top: 30px; 
			}

			.receipt-content .invoice-wrapper .line-items .total .extra-notes {
			float: left;
			width: 40%;
			text-align: left;
			font-size: 13px;
			color: #7A7A7A;
			line-height: 20px; 
			}

			@media (max-width: 767px) {
			.receipt-content .invoice-wrapper .line-items .total .extra-notes {
			width: 100%;
			margin-bottom: 30px;
			float: none; } 
			}

			.receipt-content .invoice-wrapper .line-items .total .extra-notes strong {
			display: block;
			margin-bottom: 5px;
			color: #454545; 
			}

			.receipt-content .invoice-wrapper .line-items .total .field {
			margin-bottom: 7px;
			font-size: 14px;
			color: #555; 
			}

			.receipt-content .invoice-wrapper .line-items .total .field.grand-total {
			margin-top: 10px;
			font-size: 16px;
			font-weight: 500; 
			}

			.receipt-content .invoice-wrapper .line-items .total .field.grand-total span {
			color: #20A720;
			font-size: 16px; 
			}

			.receipt-content .invoice-wrapper .line-items .total .field span {
			display: inline-block;
			margin-left: 20px;
			min-width: 85px;
			color: #84868A;
			font-size: 15px; 
			}

			.receipt-content .invoice-wrapper .line-items .print {
			margin-top: 50px;
			text-align: center; 
			}



			.receipt-content .invoice-wrapper .line-items .print a i {
			margin-right: 3px;
			font-size: 14px; 
			}

			.receipt-content .footer {
			margin-top: 40px;
			margin-bottom: 110px;
			text-align: center;
			font-size: 12px;
			color: #969CAD; 
			}  
		</style>
	</head>
	<body>
	<div class="receipt-content" id="receipt-content" style="font-family: monospace">
    <div class="container bootstrap snippets bootdey">
		<div class="row">
				<div class="col-md-12">
					<div class="invoice-wrapper">

						<div class="intro">
							Hi <strong>CartCulator User</strong>, 
							<br>
							This is the receipt for a payment of <strong>&#8369;<?= number_format($total_amount, 2); ?></strong>.
						</div>

						<div class="payment-info">
							<div class="row">
								<div class="col-sm-6">
									<span>Payment No.</span>
									<strong><?= $t_id; ?></strong>
								</div>
								<div class="col-sm-6 text-right">
									<span>Payment Date</span>
									<strong><?= date("M d, Y h:i", strtotime($today)); ?></strong>
								</div>
							</div>
						</div>

						<div class="line-items">


							<div class="items">

								<?php 

								$check_cart = $con->prepare("SELECT * FROM cart
								INNER JOIN items 
								ON cart.item_id = items.id");
								$check_cart->execute();

								if($check_cart->rowCount() > 0){

									while($item = $check_cart->fetch()){

								
								
								?>
								<div class="row item">
									<div class="col-xs-4 desc">
										<?= $item['name']; ?>
									</div>
									<div class="col-xs-5 amount text-right">
										<b>&#8369;<?= number_format($item['price'], 2); ?></b>
									</div>
								</div>
								<?php } } ?>
							
							</div>


							<div class="total text-right">
							
								<div class="field grand-total">
									Total <span>&#8369;<?= number_format($total_amount, 2); ?></span>
								</div>
								
							</div>

							<div class="print">
								<button type="button" id="print" onclick="doPrint()">
									<i class="fa fa-print"></i>
									Print this receipt
								</button>
							</div>
						</div>
					</div>

					<div class="footer">
						CartCulator
					</div>
				</div>
			</div>
		</div>
	</div>   
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
	<script>
		
		function doPrint(){
			window.print();
			window.location.href = "index.php";
		}
	</script>
	</body>
	</html>


