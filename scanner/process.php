<?php
//process.php
include('../db_connect.php');

$barcode_record = $_GET['barcode_record'];

// Prepare SQL query to fetch data based on barcode
$sql = "SELECT * FROM items WHERE item_code = '$barcode_record'";

$result = $conn->query($sql);

if ($result === false) {
    // SQL query error
    $error_message = "SQL Error: " . $conn->error;
    $response = array(
        'status' => 'error',
        'message' => $error_message
    );
    echo json_encode($response);
} elseif ($result->num_rows > 0) {
    // Data found for the given barcode
    $data = array();
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    $response = array(
        'status' => 'success',
        'message' => 'Data found for the given barcode',
        'data' => $data
    );
    echo json_encode($response);
    // Include additional PHP logic here if needed, e.g., updating the database with the scanned item
} else {
    // No data found for the given barcode
    $response = array(
        'status' => 'error',
        'message' => 'No data found for the given barcode'
    );
    echo json_encode($response);
}

$conn->close();
?>
