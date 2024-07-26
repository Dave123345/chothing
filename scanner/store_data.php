<?php
// Check if data is received
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the JSON data sent from the scanning page
    $json_data = file_get_contents("php://input");
    
    // Define the file path to store the data
    $file_path = "received_data.json";
    
    // Write the received data to the file
    if (file_put_contents($file_path, $json_data) !== false) {
        echo "Data stored successfully in $file_path";
    } else {
        $error_message = "Error storing data in $file_path";
        echo $error_message;
        // Log the error for further investigation
        error_log($error_message);
    }
} else {
    echo "No data received";
}
?>
