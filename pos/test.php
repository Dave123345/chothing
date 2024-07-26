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
                parseFloat(data.price).toLocaleString("en-US", { style: 'decimal', minimumFractionDigits: 2, maximumFractionDigits: 2 }) +
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

                $('#p-list .p-item').each(function() {
                    var data = $(this).data('json');
                    if (data.item_code == barcode) {
                        addItemToOrderList(data);
                        return false; // Exit loop early if item found
                    }
                });
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
                $(this).find('.amount').text(amount.toLocaleString("en-US", { style: 'decimal', minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                $(this).find('[name="amount[]"]').val(amount);
                total += amount;
            });
            $('[name="total_amount"]').val(total.toFixed(2));
            $('#total_amount').text(total.toLocaleString("en-US", { style: 'decimal', minimumFractionDigits: 2, maximumFractionDigits: 2 }));
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
