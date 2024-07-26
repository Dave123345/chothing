<?php
include '../db_connect.php';

$totalAmount = '';
$orderItems = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the total amount from the form data
    $totalAmount = $_POST['amount'];

    // Retrieve the order items from the form data
    $itemIndex = 1;
    while (isset($_POST['item' . $itemIndex])) {
        $itemName = $_POST['item' . $itemIndex];
        $itemCode = $_POST['code' . $itemIndex];
        $itemQty = $_POST['qty' . $itemIndex];
        $itemPrice = $_POST['price' . $itemIndex];

        // Prepare the data for insertion into the database
        $dateCreated = date("Y-m-d H:i:s");
        $date = date("Y-m-d");
        $month = date("m");
        $paymentMethod = 'Cash';
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

    echo "<script>
    window.onload = function() {
        var printContents = document.getElementById('receipt').innerHTML;
        var printWindow = window.open('', '', 'height=500, width=500');
        printWindow.document.write('<html><head><title>Receipt</title>');
        printWindow.document.write('</head><body>');
        printWindow.document.write(printContents);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
        setTimeout(function() {
            window.location.href = 'index.php';
        }, 1000);
    }
</script>
";
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Retrieve the total amount from the URL parameter
    if (isset($_GET['amount'])) {
        $totalAmount = $_GET['amount'];
    }

    // Retrieve the order items from the URL parameters
    $itemIndex = 1;
    while (isset($_GET['item' . $itemIndex])) {
        $itemName = $_GET['item' . $itemIndex];
        $itemCode = $_GET['code' . $itemIndex];
        $itemQty = $_GET['qty' . $itemIndex];
        $itemPrice = $_GET['price' . $itemIndex];

        $orderItems[] = [
            'name' => htmlspecialchars($itemName),
            'code' => htmlspecialchars($itemCode),
            'quantity' => htmlspecialchars($itemQty),
            'price' => htmlspecialchars($itemPrice)
        ];
        $itemIndex++;
    }
}
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
            <!-- Receipt Section -->
            <section class="col-md-8 col-lg-6 col-xl-5 pb-4" style="margin-top: 200px;">
                <div class="card rounded-3">
                    <div class="card-body mx-1 my-2" id="receipt">
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
                                        <th>Name</th>
                                        <th>Qty</th>
                                        <th>Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Loop through each order item and display its details
                                    foreach ($orderItems as $item) {
                                        echo "<tr>";
                                        echo "<td>" . htmlspecialchars($item['name']) . "</td>";
                                        echo "<td>" . htmlspecialchars($item['quantity']) . "</td>";
                                        echo "<td>₱ " . htmlspecialchars($item['price']) . "</td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <div class="d-flex flex-row align-items-center">
                                <h6 class="mb-0 text-primary">Total amount </h6>
                                <h6 class="mb-0 text-primary m-3">₱ <?php echo htmlspecialchars($totalAmount); ?></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Payment Form Section -->
            <section class="col-md-8 col-lg-6 col-xl-5 pb-4" style="margin-top: 200px;">
                <div class="card rounded-3">
                    <form method="post" action="">
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
                                                <input type="text" class="form-control form-control-sm" name="amount" id="amount" style="width: 100px;" value="<?php echo htmlspecialchars($totalAmount); ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input hidden type="text" class="form-control" id="description" name="description" value="SELFCART KIOSK" placeholder="Enter description" required>

                                <!-- Include hidden fields to pass the order items to the form submission -->
                                <?php
                                foreach ($orderItems as $index => $item) {
                                    echo "<input type='hidden' name='item" . ($index + 1) . "' value='" . htmlspecialchars($item['name']) . "'>";
                                    echo "<input type='hidden' name='code" . ($index + 1) . "' value='" . htmlspecialchars($item['code']) . "'>";
                                    echo "<input type='hidden' name='qty" . ($index + 1) . "' value='" . htmlspecialchars($item['quantity']) . "'>";
                                    echo "<input type='hidden' name='price" . ($index + 1) . "' value='" . htmlspecialchars($item['price']) . "'>";
                                }
                                ?>

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
</body>

</html>