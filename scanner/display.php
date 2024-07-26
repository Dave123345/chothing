



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scanned Data</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1>Scanned Data</h1>
        <div id="scanned-data-container">
            <table class="table table-bordered bg-light">
                <colgroup>
                    <col width="60%">
                    <col width="35%">
                    <col width="5%">
                </colgroup>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Price</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="order-items">
                <?php
// display.php

// Check if data is received
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the JSON data sent from the scanning page
    $json_data = file_get_contents("php://input");

    // Decode the JSON data
    $scanned_data = json_decode($json_data);

    // Display the scanned data in table rows
    if ($scanned_data && is_array($scanned_data) && !empty($scanned_data)) {
        foreach ($scanned_data as $item) {
            echo "<tr>";
            echo "<td>{$item->name}</td>";
            echo "<td>{$item->price}</td>";
            echo "<td></td>"; // You can add additional columns if needed
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='3'>No data received.</td></tr>";
    }
} else {
    header("HTTP/1.1 405 Method Not Allowed");
    header("Allow: POST");
    echo "<tr><td colspan='3'>Invalid request method.</td></tr>";
}
?>


                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
