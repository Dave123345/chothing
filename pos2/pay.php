<?php 
session_start();
include_once '../connections/connect.php';
$con = $lib->openConnection();

if(isset($_POST['pay'])){
    $user_id = $_SESSION['login_id'];
    $itemcode = $_POST['item_code'];
    $name = $_POST['name'];
    $price = $_POST['pricetot'];

    $ins = $con->prepare("INSERT INTO sales (`user_id`,`total_amount`,`amount_tendered`, `inventory_ids`) VALUES (?,?,?,?)");

    foreach($itemcode as $code){

        $ins->execute([$user_id, $price, $price, '']);

    }
    
    echo "<script>alert('Done');</script>";

}

?>