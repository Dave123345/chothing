<?php 
include_once '../connections/connect.php';
$con = $lib->openConnection();

$check_code = $con->prepare("SELECT * FROM added_prod");
$check_code->execute();

if($check_code->rowCount() > 0){

    $val = $check_code->fetch();

    echo $val['code'];

}




?>