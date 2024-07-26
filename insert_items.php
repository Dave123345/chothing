<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_code = $conn->real_escape_string($_POST['item_code']);
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = $conn->real_escape_string($_POST['price']);
    $barcode_image = $_POST['barcode']; // Base64 encoded PNG image data
    $item_id = isset($_POST['id']) ? $_POST['id'] : null; // Check if ID is set for update operation

    // Decode Base64 image data
    $barcode_image_data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $barcode_image));

    if ($item_id) {
        // Update existing item
        $sql = "UPDATE items SET item_code = '$item_code', name = '$name', description = '$description', price = '$price' WHERE id = '$item_id'";
        $message = 'Item updated successfully';
    } else {
        // Insert data into database
        $sql = "INSERT INTO items (item_code, name, description, price, barcode_image) VALUES ('$item_code', '$name', '$description', '$price', '$barcode_image_path')";
        $message = 'New record created successfully';
    }

    if ($conn->query($sql) === TRUE) {
        $_SESSION['status'] = "success";
        $_SESSION['message'] = $message;

        // Save barcode image to a folder
        $barcode_image_path = "barcode_images/" . $item_code . ".png"; // Ensure this folder exists and is writable
        if (file_put_contents($barcode_image_path, $barcode_image_data)) {
            // Update the database with the path to the saved image
            $sql_update_path = "UPDATE items SET barcode_image = '$barcode_image_path' WHERE id = '$item_id'";
            $conn->query($sql_update_path);
        } else {
            $_SESSION['status'] = "error";
            $_SESSION['message'] = "Error: Failed to save barcode image";
        }
    } else {
        $_SESSION['status'] = "error";
        $_SESSION['message'] = "Error: Failed to execute query";
    }

    header("Location: index.php?page=products"); // Redirect back to the form page (adjust the path as needed)
    exit();
}

$conn->close();
?>
