<?php

// Define the API URL
$apiUrl = 'https://logistic2.paradisehoteltomasmorato.com/api/budget-approval/get_budget_approval.php';

// The token to be sent for authorization
$token = 'eyJhbGciOiJIUzI1NiJ9.e30.LxDVqhJ-ntuokyvtD5I1IZfK9Xl5DXp2wAGm7q1FPBw';

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
