<?php
// Database connection configuration
include '../db_connect.php';

// Fetch item details based on scanned barcode
if(isset($_GET['barcode'])) {
    $barcode = $_GET['barcode'];

    // Prepare and execute SQL query
    $stmt = $conn->prepare("SELECT id, name, price FROM items WHERE item_code = ?");
    $stmt->bind_param("s", $barcode);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if item found
    if ($result->num_rows > 0) {
        // Fetch item details
        $row = $result->fetch_assoc();
        $item = array(
            'id' => $row['id'],
            'name' => $row['name'],
            'price' => $row['price']
        );

        // Return item details as JSON
        echo json_encode($item);
    } else {
        // Item not found
        http_response_code(404);
        echo json_encode(array('error' => 'Item not found'));
    }

    // Close statement and connection
    $stmt->close();
}

// Close database connection
$conn->close();
?>
