<?php

session_start();
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://logistic2.paradisehoteltomasmorato.com/api/budget-approval/process_update.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer eyJhbGciOiJIUzI1NiJ9.e30.Wn2PDfdI1zpEGcXool1YdXhVyCJVv7Ea07doKnxB4Hw'
]);

// Security warning: Remove these in production!
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

$response = curl_exec($ch);
curl_close($ch);

// Process response
$data = json_decode($response, true);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budget Approvals</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables Bootstrap 5 CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #dee2e6 !important;
            border-radius: 4px;
            padding: 4px 8px;
        }

        .dataTables_length select {
            border: 1px solid #dee2e6;
            border-radius: 4px;
        }

        .status-pending {
            background-color: #ffc107; /* Yellow background for pending status */
            color: black;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4">Budget Approvals</h2>

        <!-- Display Table -->
        <table id="budgetTable" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th>Action</th>
                    <th>Req ID</th>
                    <th>Item Name</th>
                    <th>Item SKU</th>
                    <th>Category</th>
                    <th>Total Qty</th>
                    <th>Est. Cost</th>
                    <th>Total Cost</th>
                    <th>Status</th>
                    <th>Amount</th>
                    <th>Approved By</th>
                    <th>Approval Date</th>
                    <th>Request Date</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($data['success']) && $data['success']): ?>
                    <?php foreach ($data['data'] as $item): ?>
                        <tr>
                            <td>
                                <?php if ($item['status'] == 'Pending') { ?>
                                    <button class="btn btn-primary btnApprove" data-req_id="<?= $item['requisition_id'] ?>">Approve</button>
                                    <button class="btn btn-danger btnReject" data-req_id="<?= $item['requisition_id'] ?>">Reject</button>
                                <?php } else { ?>
                                    <button class="btn btn-primary btnApprove" data-req_id="<?= $item['requisition_id'] ?>" disabled>Approve</button>
                                    <button class="btn btn-danger btnReject" data-req_id="<?= $item['requisition_id'] ?>" disabled>Reject</button>
                                <?php } ?>
                            </td>
                            <td><?= htmlspecialchars($item['requisition_id'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($item['item_name'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($item['item_sku'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($item['category'] ?? 'N/A') ?></td>
                            <td><?= number_format($item['total_quantity'] ?? 0, 2) ?></td>
                            <td><?= number_format($item['estimated_cost'] ?? 0, 2) ?></td>
                            <td><?= number_format($item['total_cost'] ?? 0, 2) ?></td>
                            <td>
                                <?php if ($item['status'] == 'Pending') { ?>
                                    <span class="badge status-pending"><?= htmlspecialchars($item['status'] ?? 'N/A') ?></span>
                                <?php } else { ?>
                                    <span class="badge <?= ($item['status'] ?? '') === 'Approved' ? 'bg-success' : 'bg-danger' ?>">
                                        <?= htmlspecialchars($item['status'] ?? 'N/A') ?>
                                    </span>
                                <?php } ?>
                            </td>
                            <td data-order="<?= $item['amount'] ?? 0 ?>">
                                <?= isset($item['amount']) ? number_format($item['amount'], 2) : 'N/A' ?>
                            </td>
                            <td><?= htmlspecialchars($item['approved_by'] ?? 'N/A') ?></td>
                            <td data-order="<?= strtotime($item['approval_date'] ?? '') ?>">
                                <?= isset($item['approval_date']) ? date('m/d/Y H:i', strtotime($item['approval_date'])) : 'N/A' ?>
                            </td>
                            <td data-order="<?= strtotime($item['request_date'] ?? '') ?>">
                                <?= isset($item['request_date']) ? date('m/d/Y', strtotime($item['request_date'])) : 'N/A' ?>
                            </td>
                            <td data-order="<?= strtotime($item['created_at'] ?? '') ?>">
                                <?= isset($item['created_at']) ? date('m/d/Y H:i', strtotime($item['created_at'])) : 'N/A' ?>
                            </td>
                            <td data-order="<?= strtotime($item['updated_at'] ?? '') ?>">
                                <?= isset($item['updated_at']) ? date('m/d/Y H:i', strtotime($item['updated_at'])) : 'N/A' ?>
                            </td>
                            <td><?= htmlspecialchars($item['remarks'] ?? 'N/A') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="15">No data found or error loading data</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal for Approval/Rejection -->
    <div class="modal fade" id="approvalModal" tabindex="-1" aria-labelledby="approvalModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="approvalModalLabel">Approve or Reject Budget</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="approvalForm">
                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount</label>
                            <input type="number" class="form-control" id="amount" name="amount" required>
                        </div>
                        <div class="mb-3">
                            <label for="remarks" class="form-label">Remarks</label>
                            <textarea class="form-control" id="remarks" name="remarks" required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="submitApproval">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Required Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#budgetTable').DataTable({
                "order": [
                    [10, "desc"]
                ], // Order by approval date
                "columnDefs": [{
                        "orderable": false,
                        "targets": [0, 1, 2, 3, 7, 9, 14]
                    }, // Non-orderable columns
                    {
                        "type": "num-fmt",
                        "targets": [4, 5, 6, 8]
                    }, // Numeric columns
                    {
                        "type": "date",
                        "targets": [10, 11, 12, 13]
                    } // Date columns
                ],
                "responsive": true,
                "pageLength": 25,
                "language": {
                    "search": "Filter records:",
                    "lengthMenu": "Show _MENU_ entries",
                    "paginate": {
                        "previous": "<i class='bi bi-chevron-left'></i>",
                        "next": "<i class='bi bi-chevron-right'></i>"
                    }
                }
            });
        });

        $('.btnApprove, .btnReject').click(function() {
            const req_id = $(this).data('req_id');
            const action = $(this).hasClass('btnApprove') ? 'approve' : 'reject';
            const statusText = action === 'approve' ? 'Approve' : 'Reject';
            
            // Set modal content based on the action
            $('#approvalModalLabel').text(`${statusText} Budget`);
            $('#submitApproval').text(statusText);

            $('#approvalModal').modal('show'); // Show the modal

            // When Submit is clicked
            $('#submitApproval').off('click').on('click', function() {
                const amount = $('#amount').val();
                const remarks = $('#remarks').val();
                
                if(amount != "" && remarks != "") {
                    $.ajax({
                        url: 'https://logistic2.paradisehoteltomasmorato.com/api/budget-approval/process_update.php',
                        type: 'PUT',
                        headers: {
                            'Authorization': 'Bearer eyJhbGciOiJIUzI1NiJ9.e30.Wn2PDfdI1zpEGcXool1YdXhVyCJVv7Ea07doKnxB4Hw',
                            'Content-Type': 'application/json'
                        },
                        data: JSON.stringify({
                            requisition_id: req_id,
                            remarks: remarks,
                            amount: amount,
                            status: action === 'approve' ? 'Approved' : 'Rejected'
                        }),
                        success: function(response) {
                            try {
                                const data = JSON.parse(response);
                                if (data.success) {
                                    alert(data.message || 'Action successful');
                                } else {
                                    alert(data.message || 'Response failed');
                                }
                                location.reload();
                            } catch (e) {
                                alert('Invalid JSON response: ' + response);
                            }
                        },
                        error: function(xhr, status, error) {
                            alert('Server error: ' + xhr.status + ' ' + error);
                            console.error('AJAX Error:', xhr, status, error);
                        }
                    });
                } else {
                    alert("Please fill in both Amount and Remarks");
                }
            });
        });
    </script>
</body>

</html>