<?php 

include_once '../connections/connect.php';
$con = $lib->openConnection();

$items = $con->prepare("SELECT * FROM cart");
$items->execute();

echo $items->rowCount();





?>