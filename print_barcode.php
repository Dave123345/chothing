<?php
include_once './connections/connect.php';
$con = $lib->openConnection();

include 'db_connect.php';

?>

<div class="container-fluid">
    <div class="col-lg-12">
        <div class="card">
            <div class="card_body">

                <hr>
                <div class="col-md-12">
                    <table class="table table-bordered" id='report-list'>
                        <thead style="background: #222;">
                            <tr>
                                <th class="text-white text-center"style="text-align:center;">#</th>
                                <th class="text-white"style="text-align:center;">Item Code</th>
                                <th class="text-white"style="text-align:center;">Item Name</th>
                                <th class="text-white"style="text-align:center;">Description</th>
                                <th class="text-white"style="text-align:center;">Price</th>
                                <th class="text-white" style="text-align:center;">Barcode Images</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            $result = $conn->query("SELECT * FROM items");
                            while ($row = $result->fetch_assoc()) :
                            ?>
                                <tr><center>
                                    <td class="text-center"><?php echo $i++; ?></td>
                                    <td><?php echo $row['item_code']; ?></td>
                                    <td><?php echo $row['name']; ?></td>
                                    <td><?php echo $row['description']; ?></td>
                                    <td><?php echo $row['price']; ?></td>
                                    <td>
                                        <!-- Display barcode image -->
                                        <center><img src="barcode_images/<?php echo $row['item_code']; ?>.png" alt="Barcode Image" style="max-width: 200px;"></center>
                                    </td>
                                    </center>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
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
    function printTable() {
        var printContents = document.getElementById("report-list").outerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }

    document.getElementById("print").addEventListener("click", function() {
        printTable();
    });
</script>

