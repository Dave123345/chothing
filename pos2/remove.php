<?php 
$pid = $_GET['pid'];
include_once '../connections/connect.php';
$con = $lib->openConnection();

$remove = $con->prepare("DELETE FROM cart WHERE id = ?");
$remove->execute([$pid]);

header("Location: index.php");


?>