<?php
$host="localhost";
$username="root";
$password="";
$database="webdev_midterm_b";

$connection = new mysqli($host, $username, $password, $database);
if ($connection->connect_error) {
  die("Connection failed: " . $connection->connect_error);
}
		

?>