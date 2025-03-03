<?php
$apiUrl = 'https://logistic2.paradisehoteltomasmorato.com/api/budget-approval/post_budget_approval.php';
$token = 'eyJhbGciOiJIUzI1NiJ9.e30.LxDVqhJ-ntuokyvtD5I1IZfK9Xl5DXp2wAGm7q1FPBw';  // The token you've received after login

// Prepare data for the API request
$data = [
    'requisition_id' => 151051,
    'amount' => 20000,
    'status' => 'Approved',
    'approved_by' => 'Finance',
    'approval_date' => '2024-02-10 14:30:00',
    'remarks' => 'Approved Budget'
];

// Convert the data to JSON format
$jsonData = json_encode($data);

// Set the headers including the Authorization Bearer token
$options = [
    'http' => [
        'method'  => 'POST',
        'header'  => "Content-Type: application/json\r\n" .
            "Authorization: Bearer $token\r\n",  // Pass the token here
        'content' => $jsonData  // Add the data in the request body
    ]
];

// Create the context for the request
$context = stream_context_create($options);

// Send the POST request and get the response
$response = file_get_contents($apiUrl, false, $context);

// Check if the request was successful
if ($response === FALSE) {
    echo 'Error sending update data to API.';
    exit;
}

// Decode and display the response
echo $response;
?>

// Eto lang dapat mauupdate mo
<!-- {
            "requisition_id" : 151051,
            "amount" : 20000,
            "status" : "Approved",
            "approved_by" : "Finance",
            "approval_date": "2024-02-10 14:30:00",
            "remarks" : "Approved Budget"

            } {
            "requisition_id" : 151051,
            "amount" : 20000,
            "status" : "Rejected",
            "approved_by" : "Finance",
            "approval_date": "2024-02-10 14:30:00",
            "remarks" : "Approved Budget"

        } -->
