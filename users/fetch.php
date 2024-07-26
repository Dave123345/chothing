<?php 
session_start();
include_once '../connections/connect.php';
$con = $lib->openConnection();

$sel_cart = $con->prepare("SELECT * FROM cart AS c INNER JOIN items AS I ON c.item_id = i.id");
$sel_cart->execute();


if($sel_cart->rowCount() > 0 ){

    $i = 1;
    $gt = 0;

    while($val = $sel_cart->fetch()){

        ?>
    <tr>
      <th scope="row"><?= $i; ?></th>
      <td><?= $val['item_code']; ?></td>
      <td><?= $val['name']; ?></td>
      <td>
        <button id="sub" class="btn"><i class="fa fa-minus mb-1 text-danger"></i></button>
            <input type="text" min="1" value="1" id="qty" style="width: 60px; text-align: center">
        <button id="add" class="btn"><i class="fa fa-plus mb-1 text-success"></i></button></td>
      <td>&#8369; <input type="number" min="1" value="<?= number_format($val['price'], 2); ?>" id="amount" style="width: 100px; border: none; background: none"></td>
      <td><i class="fa fa-trash mb-1 text-danger"></i></td>
    </tr>
        <?php
        $gt += $val['price'];
        $_SESSION['gt'] = $gt;
        $i++;

    }

}



?>

    <script>
		$(document).ready(function(){

            
            var x = $('#qty').val();


			$('#add').on('click', function(){
                $('#qty').val(++x); 
			})

            
			$('#sub').on('click', function(){


                if($('#qty').val() > 1){
                    $('#qty').val(--x); 
                }
                
			})
		})
	</script>



