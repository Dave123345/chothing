<?php
include 'db_connect.php';

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'];

if ($id) {
	$stmt = $conn->prepare("DELETE FROM items WHERE id = ?");
	$stmt->bind_param("i", $id);

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