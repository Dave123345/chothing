<?php include '../db_connect.php' ?>
<style>
    span.float-right.summary_icon {
        font-size: 3rem;
        position: absolute;
        right: 1rem;
        top: 0;
    }

    .bg-gradient-primary {
        background: rgb(119, 172, 233);
        background: linear-gradient(149deg, rgba(119, 172, 233, 1) 5%, rgba(83, 163, 255, 1) 10%, rgba(46, 51, 227, 1) 41%, rgba(40, 51, 218, 1) 61%, rgba(75, 158, 255, 1) 93%, rgba(124, 172, 227, 1) 98%);
    }

    .btn-primary-gradient {
        background: linear-gradient(to right, #1e85ff 0%, #00a5fa 80%, #00e2fa 100%);
    }

    .btn-danger-gradient {
        background: linear-gradient(to right, #f25858 7%, #ff7840 50%, #ff5140 105%);
    }

    main .card {
        height: calc(100%);
    }

    main .card-body {
        height: calc(100%);
        overflow: auto;
        padding: 5px;
        position: relative;
    }

    main .container-fluid,
    main .container-fluid>.row,
    main .container-fluid>.row>div {
        height: calc(100%);
    }

    #o-list {
        height: calc(87%);
        overflow: auto;
    }

    #calc {
        position: absolute;
        bottom: 1rem;
        height: calc(10%);
        width: calc(98%);
    }

    .prod-item {
        min-height: 12vh;
        cursor: pointer;
    }

    .prod-item:hover {
        opacity: .8;
    }

    .prod-item .card-body {
        display: flex;
        justify-content: center;
        align-items: center;

    }

    input[name="qty[]"] {
        width: 30px;
        text-align: center
    }

    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    #cat-list {
        height: calc(100%)
    }

    .cat-item {
        cursor: pointer;
    }

    .cat-item:hover {
        opacity: .8;
    }

    #p-list tbody tr {
        cursor: pointer
    }
</style>
<?php
if (isset($_GET['id'])) :
    $sale = $conn->query("SELECT * FROM sales where id = {$_GET['id']}");
    foreach ($sale->fetch_array() as $k => $v) {
        $$k = $v;
    }
    $items = $conn->query("SELECT s.*,i.name FROM stocks s inner join items i on i.id=s.item_id where s.id in ($inventory_ids)");
endif;
?>
<div class="container-fluid o-field">
    <div class="row mt-3 ml-3 mr-3">
        <div class="col-lg-6">
            <div class="card bg-dark border-primary">
                <div class="card-header text-white  border-primary">
                    <b>List</b>
                    <span class="float:right"><a class="btn btn-primary btn-sm col-sm-3 float-right" href="../index.php" id="">
                            <i class="fa fa-home"></i> Home
                        </a></span>
                </div>

                <div class="card-body">
                    <form action="" id="manage-order">
                        <input type="hidden" name="id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : '' ?>">
                        <div class="bg-white" id='o-list'>
                            <table class="table table-bordered bg-light">
                                <colgroup>
                                    <col width="60%">
                                    <col width="35%">
                                    <col width="5%">
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="order-items">


                                </tbody>
                            </table>
                        </div>
                        <div class="d-block bg-white" id="calc">
                            <table class="" width="100%">
                                <tbody>
                                    <tr>
                                        <td><b>
                                                <h4>Total</h4>
                                            </b></td>
                                        <td class="text-right">
                                            <input type="hidden" name="total_amount" value="0">
                                            <input type="hidden" name="total_tendered" value="0">
                                            <span class="">
                                                <h4><b id="total_amount">0.00</b></h4>
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </form>

                </div>

            </div>
        </div>
        <div class="col-lg-6  p-field">
            <div class="card border-primary">
                <div class="card-header bg-dark text-white  border-primary">
                    <b>Products</b>
                </div>
                <div class="card-body bg-dark d-flex" id='prod-list'>
                    <div class="col-md-12 h-100 bg-white" style="overflow:auto">
                        <hr>
                        <div class="d-flex w-100 mb-2">
                            <label for="" class="text-dark col-sm-2"><b>Search</b></label>
                            <div class="input-group col-sm-10">
                                <input type="text" class="form-control form-control-sm" id="filter">
                                <div class="input-group-append">
                                    <button class="input-group-text">
                                        <a class="fas fa-times-circle" type="button" id="clear-search"></a>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <table class="table table-bordered table-hover bg-white" id="p-list">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Item Code</th>
                                    <th class="text-center">Item Name</th>
                                    <!-- <th class="text-center">Item Size</th> -->
                                    <th class="text-center">Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Check if a search query is submitted
                                if (isset($_GET['search'])) {
                                    $search = $_GET['search'];
                                    $query = "SELECT * FROM items WHERE name LIKE '%$search%' ORDER BY name ASC";
                                    $items = $conn->query($query);
                                } else {
                                    // If no search query is submitted, fetch all items
                                    $items = $conn->query("SELECT * FROM items ORDER BY name ASC");
                                }
                                $i = 1;
                                while ($row = $items->fetch_assoc()) :
                                ?>
                                    <tr data-id="<?php echo $row['id'] ?>" class="p-item" data-json='<?php echo json_encode($row) ?>'>
                                        <td class="text-center"><?php echo $i++; ?></td>
                                        <td><b><?php echo $row['item_code'] ?></b></td>
                                        <td><b><?php echo ucwords($row['name']) ?></b></td>
                                        <!-- <td><b><?php echo $row['size'] ?></b></td> -->
                                        <td class="text-right"><b>&#8369;<?php echo number_format($row['price'], 2) ?></b></td>
                                    </tr>
                                <?php endwhile; ?>

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-dark  border-primary">
                    <div class="row justify-content-center">
                        <div class="btn btn btn-sm col-sm-3 btn-primary mr-2" type="button" id="pay" form="manage-order">Gcash</div>
                        <div class="btn btn btn-sm col-sm-3 btn-primary mr-2" type="button" id="cash" form="manage-order">Cash</div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<script>
    $(document).ready(function() {
        $('#payment_form').submit(function(event) {
            event.preventDefault(); // Prevent default form submission

            // Get form data
            var formData = {
                amount: parseFloat($('#amount').val()), // Convert amount to float
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
    });
</script>

<script>
    $(document).ready(function() {
        // Function to handle adding items to the order list
        function addItemToOrderList(data) {
            if ($('#o-list').find('tr[data-id="' + data.id + '"]').length > 0) {
                var existingItem = $('#o-list').find('tr[data-id="' + data.id + '"]');
                var qtyInput = existingItem.find('[name="qty[]"]');
                var currentQty = parseInt(qtyInput.val());
                qtyInput.val(currentQty + 1).trigger('change');
                calcTotal();
                return;
            }

            var tr = $('<tr class="o-item"></tr>');
            tr.attr('data-id', data.id);
            tr.append('<td><input type="hidden" name="item_code[]" value="' + data.item_code + '">' +
                '<input type="hidden" name="qty[]" value="1"><input type="hidden" name="inv_id[]" value="">' +
                '<input type="hidden" name="item_id[]" value="' + data.id + '">' + data.name + '</td>');
            tr.append('<td><button class="btn btn-sm btn-primary btn-minus" style="width:30px;">-</button>' +
                '<input style="border:none; background:#f8f9fa;" type="text" name="qty[]" value="1" readonly class="qty-input">' +
                '<button class="btn btn-sm btn-primary btn-plus" style="width:30px;">+</button></td>');
            tr.append('<td class="text-center"><input type="hidden" name="price[]" value="' + data.price + '">' +
                '<input type="hidden" name="amount[]" value="' + data.price + '">&#8369;<span class="amount">' +
                parseFloat(data.price).toLocaleString("en-US", {
                    style: 'decimal',
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }) +
                '</span></td>');
            tr.append('<td><span class="btn btn-sm btn-danger btn-rem"><b><i class="fa fa-times text-white"></i></b></span></td>');
            $('#o-list tbody').append(tr);
            qty_func();
            calcTotal();
        }

        // Function to handle scanning barcode
        $(document).on('keypress', function(e) {
            if (e.which == 13) { // Enter key pressed
                e.preventDefault();
                var barcode = $('#filter').val().trim();
                $('#filter').val('');

                var itemFound = false;

                $('#p-list .p-item').each(function() {
                    var data = $(this).data('json');
                    if (data.item_code == barcode) {
                        addItemToOrderList(data);
                        itemFound = true;
                        return false; // Exit loop early if item found
                    }
                });

                // Check if itemFound is false to display "No data found" message
                if (!itemFound) {
                    alert('No data found for barcode: ' + barcode);
                } 
            }
        });


        // Quantity handling functions
        function qty_func() {
            $('#o-list').on('click', '.btn-plus', function() {
                var qtyInput = $(this).siblings('.qty-input');
                var qty = parseInt(qtyInput.val()) + 1;
                qtyInput.val(qty).trigger('change');
            });

            $('#o-list').on('click', '.btn-minus', function() {
                var qtyInput = $(this).siblings('.qty-input');
                var qty = parseInt(qtyInput.val()) - 1;
                if (qty > 0) {
                    qtyInput.val(qty).trigger('change');
                }
            });

            $('#o-list').on('click', '.btn-rem', function() {
                $(this).closest('tr').remove();
                calcTotal();
            });

            $('#o-list').on('change', '[name="qty[]"]', function() {
                calcTotal();
            });
        }

        // Function to calculate total amount
        function calcTotal() {
            var total = 0;
            $('#o-list tbody tr').each(function() {
                var qty = parseInt($(this).find('[name="qty[]"]').val());
                var price = parseFloat($(this).find('[name="price[]"]').val());
                var amount = qty * price;
                $(this).find('.amount').text(amount.toLocaleString("en-US", {
                    style: 'decimal',
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }));
                $(this).find('[name="amount[]"]').val(amount);
                total += amount;
            });
            $('[name="total_amount"]').val(total.toFixed(2));
            $('#total_amount').text(total.toLocaleString("en-US", {
                style: 'decimal',
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }));
        }

        // Event handler for clicking on product items
        $('#p-list .p-item').click(function() {
            var data = $(this).data('json');
            addItemToOrderList(data);
        });

        // Event handler for search filter
        $('#filter').keyup(function() {
            var filter = $(this).val().toLowerCase();
            $('#p-list .p-item').each(function() {
                var txt = $(this).text().toLowerCase();
                $(this).toggle(txt.includes(filter));
            });
        });

        // Clear search filter
        $('#clear-search').click(function() {
            $('#filter').val('');
            $('#p-list .p-item').show();
        });

        // Payment handling
        $("#pay").click(function() {
            processPayment('gcash');
        });

        $("#cash").click(function() {
            processPayment('cash');
        });

        function processPayment(paymentType) {
            var totalAmount = $('[name="total_amount"]').val();
            var orderItemsQueryString = '';

            $('#o-list tbody tr').each(function(index) {
                var item_code = $(this).find("input[name='item_code[]']").val();
                var quantity = $(this).find("[name='qty[]']").val();
                var price = $(this).find("[name='price[]']").val();
                var name = $(this).find("td:first-child").text().trim();

                // Append the item details to the orderItemsQueryString
                orderItemsQueryString += "item" + (index + 1) + "=" + encodeURIComponent(name) + "&";
                orderItemsQueryString += "code" + (index + 1) + "=" + encodeURIComponent(item_code) + "&";
                orderItemsQueryString += "qty" + (index + 1) + "=" + encodeURIComponent(quantity) + "&";
                orderItemsQueryString += "price" + (index + 1) + "=" + encodeURIComponent(price) + "&";
            });

            var url = '';
            if (paymentType === 'gcash') {
                url = 'payment.php';
            } else if (paymentType === 'cash') {
                url = 'cash-payment.php';
            }

            if (url) {
                url += '?amount=' + totalAmount + '&' + orderItemsQueryString;
                window.location.href = url;
            }
        }

        // Initialize quantity functions
        qty_func();


    });


    $(document).ready(function() {
        // Hide all table rows by default
        $('.p-item').hide();
        $('#filter').focus();

        // Filter function
        $('#filter').keyup(function() {
            var filter = $(this).val().toLowerCase();
            $('.p-item').each(function() {
                var txt = $(this).text().toLowerCase();
                if (txt.includes(filter)) {
                    $(this).show(); // Show the row if it matches the filter
                } else {
                    $(this).hide(); // Hide the row if it doesn't match the filter
                }
            });

            // If the filter is empty, hide all rows
            if (filter === '') {
                $('.p-item').hide();
            }
        });



        // Clear icon function
        $('#clear-search').click(function() {
            $('#filter').val(''); // Clear the search input
            $('.p-item').hide(); // Hide all table rows
        });

    });
</script>