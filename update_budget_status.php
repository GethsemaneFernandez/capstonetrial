<?php
session_start();

// Assuming the database connection is already available in $DB
$requisition_id = $_POST['requisition_id'];
$status = $_POST['status'];
$remarks = $_POST['remarks'];
$approved_by = $_SESSION['user_id']; // User from session
$approval_date = date('Y-m-d H:i:s');

// Validate required data
if (empty($requisition_id) || empty($status)) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

// Update budget status
$updateData = [
    'status' => strtoupper($status),
    'approved_by' => $approved_by,
    'approval_date' => $approval_date,
    'remarks' => $remarks
];

// Perform the database update
$result = $DB->UPDATE('budget_approvals', $updateData, ['requisition_id' => $requisition_id]);

if ($result) {
    echo json_encode([
        'success' => true,
        'updated_data' => [
            'status' => strtoupper($status),
            'approved_by' => $approved_by,
            'approval_date' => $approval_date,
            'remarks' => $remarks
        ]
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Update failed']);
}
