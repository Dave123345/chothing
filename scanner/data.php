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
                    <!-- Table rows will be dynamically populated here -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Fetch scanned data from display.php
            // Fetch scanned data from display.php
            $.ajax({
                url: 'display.php',
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    // Check if response status is success
                    if (response && response.status === 'success') {
                        var data = response.data; // Extract the data from the response
                        // Check if data is received and not empty
                        if (data && data.length > 0) {
                            // Clear existing table rows
                            $('#order-items').empty();
                            // Populate table rows with received data
                            data.forEach(function(item) {
                                var row = '<tr>';
                                row += '<td>' + item.name + '</td>';
                                row += '<td>' + item.price + '</td>';
                                row += '<td></td>'; // Add additional columns if needed
                                row += '</tr>';
                                $('#order-items').append(row);
                            });
                        } else {
                            // If no data received, display a message
                            $('#order-items').html('<tr><td colspan="3">No data received.</td></tr>');
                        }
                    } else {
                        // If response status is not success, display an error message
                        $('#order-items').html('<tr><td colspan="3">Error: ' + response.message + '</td></tr>');
                    }
                },
                error: function(xhr, status, error) {
                    // Handle AJAX errors
                    console.error('Error fetching scanned data:', error);
                    $('#order-items').html('<tr><td colspan="3">Error fetching data.</td></tr>');
                }
            });

        });
    </script>
</body>

</html>