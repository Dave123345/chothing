<?php

include('db_connect.php');
?>

<style>
	.barcode-container {
		display: flex;
		align-items: center;
		margin-top: 10px;
	}
</style>

<div class="container-fluid">

	<div class="col-lg-12">
		<div class="row">
			<!-- FORM Panel -->
			<div class="col-md-4">
				<!-- Include canvg and JsBarcode libraries -->
				<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
				<script src="https://cdnjs.cloudflare.com/ajax/libs/canvg/3.0.7/canvg.min.js"></script>
				<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
				<form action="insert_items.php" method="post">
					<!-- Form fields as before -->
					<!-- Add a hidden input field to hold the barcode data -->
					<input type="hidden" name="barcode" id="barcode-data">
					<div class="card">
						<div class="card-header">Product Form</div>
						<div class="card-body">
							<input type="hidden" name="id" id="manage-product">
							<div class="form-group">
								<label class="control-label">Item Code</label>
								<div style="display: flex;">
									<input type="text" class="form-control form-control-sm" name="item_code" value="">
									<button type="button" class="btn btn-sm btn-primary" onclick="generateCode()">Generate</button>
								</div>
							</div>
							<div class="barcode-container">
								<svg name="barcode" id="barcode"></svg>
							</div>
							<div class="form-group">
								<label class="control-label">Name</label>
								<input type="text" class="form-control form-control-sm" name="name">
							</div>
							<div class="form-group">
								<label class="control-label">Description</label>
								<textarea name="description" id="description" cols="30" rows="4" class="form-control form-control-sm"></textarea>
							</div>
							<div class="form-group">
								<label class="control-label">Price</label>
								<input type="number" class="form-control form-control-sm text-right" name="price">
							</div>
						</div>
						<div class="card-footer">
							<div class="row">
								<div class="col-md-12">
									<button class="btn btn-sm btn-primary col-sm-3 offset-md-3" type="submit">Save</button>
									<button class="btn btn-sm btn-default col-sm-3" type="button" onclick="document.getElementById('manage-product').reset()">Cancel</button>
								</div>
							</div>
						</div>
					</div>
				</form>

				<script>
					function generateCode() {
						const code = Math.floor(Math.random() * 1000000).toString().padStart(6, '0');
						const itemCodeInput = document.querySelector('input[name="item_code"]');
						itemCodeInput.value = code;
						generateBarcode(code);
					}

					function generateBarcode(code) {
						JsBarcode("#barcode", code, {
							format: "CODE128",
							displayValue: true,
							lineColor: "#0aa",
							width: 2,
							height: 40,
							fontSize: 16
						});

						const svg = document.getElementById('barcode');
						const canvas = document.createElement('canvas');
						const context = canvas.getContext('2d');
						const svgData = new XMLSerializer().serializeToString(svg);
						const svgBlob = new Blob([svgData], {
							type: 'image/svg+xml;charset=utf-8'
						});
						const DOMURL = self.URL || self.webkitURL || self;
						const url = DOMURL.createObjectURL(svgBlob);
						const img = new Image();

						img.onload = function() {
							canvas.width = img.width;
							canvas.height = img.height;
							context.drawImage(img, 0, 0);
							const pngData = canvas.toDataURL('image/png');
							document.getElementById('barcode-data').value = pngData;
							DOMURL.revokeObjectURL(url);
						};
						img.src = url;
					}

					document.querySelector('input[name="item_code"]').addEventListener('input', function() {
						const code = this.value;
						if (code) {
							generateBarcode(code);
						} else {
							document.getElementById('barcode').innerHTML = ''; // Clear the barcode if the code is empty
						}
					});

					// Check session variable for notification
					<?php if (isset($_SESSION['status'])) : ?>
						Swal.fire({
							icon: '<?php echo $_SESSION['status']; ?>',
							title: '<?php echo $_SESSION['status'] === "success" ? "Success" : "Error"; ?>',
							text: '<?php echo $_SESSION['message']; ?>'
						});
						<?php
						unset($_SESSION['status']);
						unset($_SESSION['message']);
						?>
					<?php endif; ?>
				</script>
			</div>
			<!-- FORM Panel -->

			<!-- Table Panel -->
			<div class="col-md-8">
				<div class="card">
					<div class="card-header">
						<b>Product List</b>
					</div>
					<div class="card-body">
						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<th class="text-center">#</th>
									<th class="text-center">Item Code</th>
									<th class="text-center">Product Info.</th>
									<th class="text-center">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$i = 1;
								$product = $conn->query("SELECT * FROM items order by id asc");
								while ($row = $product->fetch_assoc()) :
								?>
									<tr>
										<td class="text-center"><?php echo $i++ ?></td>
										<td class="">
											<p><b><?php echo $row['item_code'] ?></b></p>
										</td>
										<td class="">
											<p>Name: <b><?php echo $row['name'] ?></b></p>
											<p><small>Price: <b><?php echo number_format($row['price'], 2) ?></b></small></p>
											<p><small>Description: <b><?php echo $row['description'] ?></b></small></p>
										</td>
										<td class="text-center">
											<button class="btn btn-sm btn-primary edit_product" type="button" data-json='<?php echo json_encode($row) ?>'>Edit</button>
											<button class="btn btn-sm btn-danger delete_product" type="button" data-id="<?php echo $row['id'] ?>">Delete</button>
										</td>
									</tr>
								<?php endwhile; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<!-- Table Panel -->
		</div>

		<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editButtons = document.querySelectorAll('.edit_product');

        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const itemData = JSON.parse(this.getAttribute('data-json'));
                populateForm(itemData);
            });
        });

        function populateForm(itemData) {
            const form = document.querySelector('form');
            form.querySelector('input[name="id"]').value = itemData.id;
            form.querySelector('input[name="item_code"]').value = itemData.item_code;
            form.querySelector('input[name="name"]').value = itemData.name;
            form.querySelector('textarea[name="description"]').value = itemData.description;
            form.querySelector('input[name="price"]').value = itemData.price;
            generateBarcode(itemData.item_code);
        }
    });
</script>


		<script>
			document.addEventListener('DOMContentLoaded', function() {
				const deleteButtons = document.querySelectorAll('.delete_product');

				deleteButtons.forEach(button => {
					button.addEventListener('click', function() {
						const itemId = this.getAttribute('data-id');

						Swal.fire({
							title: 'Are you sure?',
							text: "You won't be able to revert this!",
							icon: 'warning',
							showCancelButton: true,
							confirmButtonColor: '#3085d6',
							cancelButtonColor: '#d33',
							confirmButtonText: 'Yes, delete it!'
						}).then((result) => {
							if (result.isConfirmed) {
								// Send AJAX request to delete the item
								fetch('delete_item.php', {
										method: 'POST',
										headers: {
											'Content-Type': 'application/json'
										},
										body: JSON.stringify({
											id: itemId
										})
									})
									.then(response => response.json())
									.then(data => {
										if (data.status === 'success') {
											Swal.fire(
												'Deleted!',
												'Your item has been deleted.',
												'success'
											).then(() => {
												location.reload();
											});
										} else {
											Swal.fire(
												'Error!',
												'There was an error deleting the item.',
												'error'
											);
										}
									});
							}
						});
					});
				});
			});
		</script>
	</div>



	<style>
		td {
			vertical-align: middle !important;
		}

		td p {
			margin: unset;
		}

		.custom-switch {
			cursor: pointer;
		}

		.custom-switch * {
			cursor: pointer;
		}
	</style>
	<script>
		// $('#manage-product').on('reset', function() {
		// 	$('input:hidden').val('')
		// 	$('.select2').val('').trigger('change')
		// })

		// $('#manage-products').submit(function(e) {
		// 	e.preventDefault()
		// 	start_load()
		// 	$.ajax({
		// 		url: 'ajax.php?action=save_product',
		// 		data: new FormData($(this)[0]),
		// 		cache: false,
		// 		contentType: false,
		// 		processData: false,
		// 		method: 'POST',
		// 		type: 'POST',
		// 		success: function(resp) {
		// 			if (resp == 1) {
		// 				alert_toast("Data successfully added", 'success')
		// 				setTimeout(function() {
		// 					location.reload()
		// 				}, 1500)

		// 			} else if (resp == 2) {
		// 				alert_toast("Data successfully updated", 'success')
		// 				setTimeout(function() {
		// 					location.reload()
		// 				}, 1500)

		// 			}
		// 		}
		// 	})
		// })
		// $('.edit_product').click(function() {
		// 	start_load()
		// 	var data = $(this).attr('data-json');
		// 	data = JSON.parse(data)
		// 	var cat = $('#manage-product')
		// 	cat.get(0).reset()
		// 	cat.find("[name='id']").val(data.id)
		// 	cat.find("[name='item_code']").val(data.item_code)
		// 	cat.find("[name='barcode']").val(data.barcode)
		// 	cat.find("[name='name']").val(data.name)
		// 	cat.find("[name='description']").val(data.description)
		// 	cat.find("[name='price']").val(data.price)
		// 	cat.find("[name='size']").val(data.size)
		// 	end_load()
		// })
		// $('.delete_product').click(function() {
		// 	_conf("Are you sure to delete this product?", "delete_product", [$(this).attr('data-id')])
		// })

		// function delete_product($id) {
		// 	start_load()
		// 	$.ajax({
		// 		url: 'ajax.php?action=delete_product',
		// 		method: 'POST',
		// 		data: {
		// 			id: $id
		// 		},
		// 		success: function(resp) {
		// 			if (resp == 1) {
		// 				alert_toast("Data successfully deleted", 'success')
		// 				setTimeout(function() {
		// 					location.reload()
		// 				}, 1500)

		// 			}
		// 		}
		// 	})
		// }
		// $('table').dataTable();
	</script>
	<!-- <script>
	$(document).ready(function() {
		//   $(".arrow").click(function () {
		$.ajax({
			type: "GET",
			url: "users/fetch02.php", // This is the correct path, but do jquery recognize "../db/" etc?
			dataType: "text",
			success: function(response) { // Here is where i'm lost, what do i need to write to update my innerHTML with the returned value from the db_getvalues.php-file?
				$("#item_code").val(response);
			}
		});
	});
	// });
</script> -->