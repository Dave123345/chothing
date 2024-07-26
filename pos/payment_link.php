<?php
// Ensure required libraries are loaded
require_once('../vendor/autoload.php');

// Check if 'amount' and 'description' parameters are present in the POST request
if (isset($_POST['amount']) && isset($_POST['description'])) {
    $amount = $_POST['amount'];
    $description = $_POST['description'];

    // Check if amount is a number
    if (!is_numeric($amount)) {
        http_response_code(400); // Bad Request
        echo json_encode(['error' => 'Amount should be a number']);
        exit;
    }

    // Initialize cURL
    $curl = curl_init();

    // Set cURL options
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.paymongo.com/v1/links",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode([
            'data' => [
                'attributes' => [
                    'amount' => (int)$amount,
                    'description' => $description,
                    'remarks' => 'Remarks' // Optional: Add dynamic remarks if needed
                ]
            ]
        ]),
        CURLOPT_HTTPHEADER => [
            "accept: application/json",
            "authorization: Basic " . base64_encode("sk_test_aQmtb8KZKM4BWG3QX9rDpNi8:"),
            "content-type: application/json"
        ],
    ]);

    // Execute cURL request and capture response
    $response = curl_exec($curl);
    $err = curl_error($curl);

    // Close cURL
    curl_close($curl);

    // Handle response or error
    if ($err) {
        http_response_code(500); // Internal Server Error
        echo json_encode(['error' => "cURL Error #: " . $err]);
    } else {
        // Convert JSON response to array for easier handling
        $responseArray = json_decode($response, true);

        // Check if there was an error in the response
        if (isset($responseArray['errors'])) {
            http_response_code(400); // Bad Request
            echo json_encode(['error' => $responseArray['errors'][0]['detail']]);
        } else {
            $paymentLink = $responseArray['data']['attributes']['checkout_url']; // Adjust based on actual response
            echo json_encode(['success' => true, 'message' => 'Payment link created successfully', 'paymentLink' => $paymentLink]);
        }
    }
} else {
    // Return a 400 Bad Request response if 'amount' or 'description' parameters are missing
    http_response_code(400);
    echo json_encode(['error' => 'Amount and description are required']);
}
?>
