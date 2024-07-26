<?php
// Retrieve JSON data from POST request
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON);

// Log received barcode data
file_put_contents('barcode.log', $inputJSON . PHP_EOL, FILE_APPEND);

// Process barcode (e.g., query database)
$barcode = $input->barcode;

// Log processed barcode
file_put_contents('barcode.log', 'Processed Barcode: ' . $barcode . PHP_EOL, FILE_APPEND);

// Here you can perform further processing such as querying a database for product information
// For demonstration purposes, let's simply echo back the barcode
echo json_encode(['barcode' => $barcode]);
?>
