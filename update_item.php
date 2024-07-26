<?php
include 'db_connect.php';

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'];
$item_code = $data['item_code'];
$name = $data['name'];
$description = $data['description'];
$price = $data['price'];

if ($id && $item_code && $name && $description && $price) {
	$stmt = $conn->prepare("UPDATE items SET item_code = ?, name = ?, description = ?, price = ? WHERE id = ?");
	$stmt->bind_param("sssdi", $item_code, $name, $description, $price, $id);

	if ($stmt->execute()) {
		echo json_encode(['status' => 'success']);
	} else {
		echo json_encode(['status' => 'error']);
	}

	$stmt->close();
} else {
	echo json_encode(['status' => 'error']);
}

$conn->close();
?>
