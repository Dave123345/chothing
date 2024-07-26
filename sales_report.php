<?php
include_once './connections/connect.php';
$con = $lib->openConnection();

include 'db_connect.php';

$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

// Fetch sales data based on the selected date
$salesQuery = "SELECT item_code, payment_method, total_amount, date_created FROM sales WHERE DATE(date_created) = ?";
$stmt = $conn->prepare($salesQuery);
$stmt->bind_param("s", $date);
$stmt->execute();
$salesResult = $stmt->get_result();

// Initialize variables
$total = 0;
?>
<div class="container-fluid">
    <div class="col-lg-12">
        <div class="card">
            <div class="card_body">
                <div class="row justify-content-center pt-4">
                    <label for="" class="mt-2">Date</label>
                    <div class="col-sm-3">
                        <input type="date" name="date" id="date" value="<?php echo $date ?>" class="form-control">
                    </div>
                </div>
                
                <hr>
                <div class="col-md-12">
                    <table class="table table-bordered" id='report-list'>
                        <!-- Table Header -->
                        <thead style="background: #222;">
                            <tr>
                                <th class="text-white text-center">#</th>
                                <th class="text-white">Date</th>
                                <th class="text-white">Item Codes</th>
                                <th class="text-white">Item Names</th>
                                <th class="text-white">Payment Method</th>
                                <th class="text-white">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($salesResult && $salesResult->num_rows > 0):
                                $i = 1;
                                while ($row = $salesResult->fetch_assoc()):
                                    $item_code = explode(',', $row['item_code']);
                                    $item_names = array();
                                    $subtotal = 0; // Initialize subtotal for each row

                                    // Fetch item names from the items table
                                    foreach ($item_code as $item_id) {
                                        $itemQuery = "SELECT name FROM items WHERE item_code = ?";
                                        $stmt = $conn->prepare($itemQuery);
                                        $stmt->bind_param("i", $item_id);
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        if ($result && $itemRow = $result->fetch_assoc()) {
                                            $item_names[] = $itemRow['name'];
                                        }
                                        $stmt->close();
                                    }
                                    $subtotal += $row['total_amount']; // Add subtotal for this row to total
                                    $total += $subtotal; // Add subtotal to total
                            ?>
                                    <tr>
                                        <td class="text-center"><?php echo $i++ ?></td>
                                        <td >
                                            <p><b><?php echo date("M d, Y", strtotime($row['date_created'])) ?></b></p>
                                        </td>
                                        <td>
                                            <p class="text-center"><b><?php echo implode(', ', $item_code) ?></b></p>
                                        </td>
                                        <td>
                                            <p class="text-center"><b><?php echo implode(', ', $item_names) ?></b></p>
                                        </td>
                                        <td>
                                            <p class="text-center"><b><?php echo $row['payment_method']; ?></b></p>
                                        </td>
                                        <td>
                                            <p class="text-center"><b>&#8369;<?php echo number_format($subtotal, 2) ?></b></p>
                                        </td>
                                    </tr>
                            <?php
                                endwhile;
                            else :
                            ?>
                                <tr>
                                    <th class="text-center" colspan="6">No Data.</th>
                                </tr>
                            <?php
                            endif;
                            ?>
                        </tbody>
                        <!-- Table Footer -->
                        <tfoot>
                            <tr>
                                <th colspan="5" class="text-right">Total</th>
                                <th class="text-right">&#8369;<?php echo number_format($total, 2) ?></th>
                            </tr>
                        </tfoot>
                    </table>
                    <hr>
                    <div class="col-md-12 mb-4">
                        <center>
                            <button class="btn btn-success btn-sm col-sm-3" type="button" id="print"><i class="fa fa-print"></i> Print</button>
                        </center>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#date').change(function() {
            updateReport();
        });

        $('#report-list').dataTable();


        $('#print').click(function() {
            var printContents = document.getElementById('report-list').outerHTML;
            var originalContents = document.body.innerHTML;
            var nw = window.open('', '_blank', 'width=900,height=600');
            nw.document.write('<html><head><title>Sales Report for <?php echo $date ?></title>');
            // Add CSS styles for printing
            nw.document.write('<style>');
            nw.document.write('@media print {');
            nw.document.write('table { width: 100%; border-collapse: collapse; }');
            nw.document.write('th, td { border: 1px solid #ddd; padding: 8px; }');
            nw.document.write('th { background-color: #f2f2f2; }');
            nw.document.write('p { margin: 0; }');
            nw.document.write('}');
            nw.document.write('</style>');
            nw.document.write('</head><body>');
            nw.document.write('<p class="text-center"><b>Sales Report for <?php echo $date ?></b></p>');
            nw.document.write(printContents);
            nw.document.write('</body></html>');
            nw.document.close();
            nw.print();
            setTimeout(() => {
                nw.close();
            }, 500);
        });
    });

    function updateReport() {
        var date = $('#date').val();
        location.replace('index.php?page=sales_report&date=' + date);
    }
</script>

