<?php

// Define the API URL
$apiUrl = 'https://logistic2.paradisehoteltomasmorato.com/api/requisition/get_purchase_requisition.php';

// The token to be sent for authorization
$token = 'eyJhbGciOiJIUzI1NiJ9.e30.5AoO0KlVplGgR4BYzM0U6VLf-gv8E8eKcYSilyn6Grk';

// Set the HTTP headers for authorization
$options = [
    'http' => [
        'header' => "Content-Type: application/json\r\n" .
            "Authorization: Bearer $token\r\n"
    ]
];

// Create the context with the headers
$context = stream_context_create($options);

// Fetch the data from the API using file_get_contents
$response = file_get_contents($apiUrl, false, $context);

// Check if the request was successful
if ($response === FALSE) {
    echo 'Error fetching data from API.';
    exit;
}

// Output the raw JSON response
echo $response;

// eto yung collumn na kukunin mo 
//         {
//             "requisition_id": "151051",
//             "request_date": "2025-07-02 18:00:00",
//             "requested_by": "Logistic-1",
//             "item_name": "Towel",
//             "item_sku": "Towel",
//             "category": "Linen",
//             "total_quantity": "12.00",
//             "estimated_cost": "123.00",
//             "total_cost": "1476.00",
//         }

