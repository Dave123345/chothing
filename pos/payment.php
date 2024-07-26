<?php
include '../db_connect.php';

// Retrieve the total amount from the URL parameter
$totalAmount = $_GET['amount'];

// Retrieve the order items from the URL parameters
$orderItems = [];
$itemIndex = 1;
while (isset($_GET['item' . $itemIndex])) {
    $itemName = $_GET['item' . $itemIndex];
    $itemCode = $_GET['code' . $itemIndex];
    $itemQty = $_GET['qty' . $itemIndex];
    $itemPrice = $_GET['price' . $itemIndex];

    // Prepare the data for insertion into the database
    $dateCreated = date("Y-m-d H:i:s");
    $date = date("Y-m-d");
    $month = date("m");
    $paymentMethod = 'Gcash';
    $itemIds = $itemCode;

    // Prepare and execute INSERT statement
    $sql = "INSERT INTO sales (date_created, total_amount, date, month, payment_method, item_code) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $dateCreated, $itemPrice, $date, $month, $paymentMethod, $itemIds);
    $stmt->execute();

    // Duplicate entries for items with multiple quantities
    $quantity = intval($itemQty);
    while ($quantity > 1) {
        $stmt->execute();
        $quantity--;
    }

    $stmt->close();

    $orderItems[] = [
        'name' => htmlspecialchars($itemName),
        'code' => htmlspecialchars($itemCode),
        'quantity' => htmlspecialchars($itemQty),
        'price' => htmlspecialchars($itemPrice)
    ];
    $itemIndex++;
}

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Payment Link</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <section class="col-md-8 col-lg-6 col-xl-5 pb-4" style="margin-top: 200px;">
                <div class="card rounded-3">
                    <div class="card-body mx-1 my-2">
                        <div class="align-items-center">
                            <div>
                                <i class="fab fa-cc-visa fa-4x text-body pe-3"></i>
                            </div>
                            <div>
                                <p class="d-flex flex-column mb-0">
                                    <b>RECEIPT</b><span class="small text-muted"></span>
                                    <br>
                                </p>
                            </div>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th hidden>Code</th>
                                        <th>Item</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Loop through each order item and display its details
                                    foreach ($orderItems as $item) {
                                        echo "<tr>";
                                        echo "<td hidden>" . htmlspecialchars($item['code']) . "</td>";
                                        echo "<td>" . htmlspecialchars($item['name']) . "</td>";
                                        echo "<td>" . htmlspecialchars($item['quantity']) . "</td>";
                                        echo "<td>" . htmlspecialchars($item['price']) . "</td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <div class="d-flex flex-column py-1">
                                <p class="mb-1 small text-primary">Total amount</p>
                                <div class="d-flex flex-row align-items-center">
                                    <h6 class="mb-0 text-primary pe-1">₱</h6>
                                    <input type="text" class="form-control form-control-sm" name="amount" id="amount" style="width: 100px;" value="<?php echo $totalAmount; ?>" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="col-md-8 col-lg-6 col-xl-5 pb-4" style="margin-top: 200px;">
                <div class="card rounded-3">
                    <form id="payment_form">
                        <div class="card-body mx-1 my-2">
                            <div class="d-flex align-items-center">
                                <div>
                                    <i class="fab fa-cc-visa fa-4x text-body pe-3"></i>
                                </div>
                                <div>
                                    <p class="d-flex flex-column mb-0">
                                        <b>SELFCART KIOSK</b><span class="small text-muted"></span>
                                    </p>
                                </div>
                            </div>
                            <div class="pt-3">
                                <div class="d-flex flex-row pb-3">
                                    <div class="rounded border d-flex w-100 px-3 py-2 align-items-center">
                                        <div class="d-flex align-items-center pe-3">
                                            <input class="form-check-input" type="radio" name="radioNoLabelX" id="radioNoLabel22" value="" aria-label="...">
                                        </div>
                                        <div class="d-flex flex-column py-1">
                                            <p class="mb-1 small text-primary">Total amount</p>
                                            <div class="d-flex flex-row align-items-center">
                                                <h6 class="mb-0 text-primary pe-1">₱</h6>
                                                <input type="text" class="form-control form-control-sm" name="amount" id="amount" style="width: 100px;" value="<?php echo $totalAmount; ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input hidden type="text" class="form-control" id="description" name="description" value="SELFCART KIOSK" placeholder="Enter description" required>
                            </div>
                            <div class="d-flex justify-content-between align-items-center pb-1">
                                <a href="index.php" class="text-muted">Go back</a>
                                <button type="submit" class="btn btn-primary btn-lg">Pay amount</button>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#payment_form').submit(function(event) {
                event.preventDefault(); // Prevent default form submission

                // Remove commas and periods from amount input
                var amountValue = $('#amount').val().replace(/[,\.]/g, '');

                // Get form data
                var formData = {
                    amount: parseFloat(amountValue), // Convert amount to float
                    description: $('#description').val()
                };

                // Check if amount is a number
                if (isNaN(formData.amount)) {
                    alert('Amount should be a number');
                    return;
                }

                // Send form data to payment_link.php using AJAX
                $.ajax({
                    type: 'POST',
                    url: 'payment_link.php',
                    data: formData, // Send data as form data
                    success: function(response) {
                        // Handle success response
                        console.log(response);
                        var jsonResponse = JSON.parse(response);
                        if (jsonResponse.success) {
                            $('#payment_link_container').show();
                            $('#payment_link').html('<a href="' + jsonResponse.paymentLink + '" target="_blank">' + jsonResponse.paymentLink + '</a>');
                            window.open(jsonResponse.paymentLink, '_blank'); // Open payment link in a new tab

                            // Append button to go back to pos.php
                            var backButton = $('<button>', {
                                type: 'button',
                                class: 'btn btn-primary btn-lg mt-3',
                                text: 'Go back to POS',
                                click: function() {
                                    window.location.href = 'pos.php'; // Redirect to pos.php
                                }
                            });
                            $('#payment_link_container').append(backButton);

                            // Download receipt as PDF
                            downloadReceipt();
                        } else {
                            alert('Error creating payment link: ' + jsonResponse.error);
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle error response
                        console.error('Error creating payment link:', xhr.responseText);
                        alert('Error creating payment link. Please try again.');
                    }
                });
            });

            function downloadReceipt() {
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF();

                doc.text("Receipt", 10, 10);
                let yOffset = 20;

                <?php
                foreach ($orderItems as $item) {
                    echo "doc.text('Item: " . $item['name'] . "', 10, yOffset);";
                    echo "doc.text('Quantity: " . $item['quantity'] . "', 100, yOffset);";
                    echo "doc.text('Price: ₱" . $item['price'] . "', 150, yOffset);";
                    echo "yOffset += 10;";
                }
                ?>

                doc.text("Total: ₱<?php echo $totalAmount; ?>", 10, yOffset + 10);

                // Save the PDF
                doc.save('receipt.pdf');
            }
        });
    </script>
</body>

</html>
