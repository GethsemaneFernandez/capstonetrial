<?php
session_start();
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://logistic2.paradisehoteltomasmorato.com/api/invoice/process_invoice.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer eyJhbGciOiJIUzI1NiJ9.e30.N1zbjIM1soge9NU9H842bRVL8hfSECOfGtzgggsH648'
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
    <title>Invoice Management</title>
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
            background-color: #ffc107;
            /* Yellow background for pending status */
            color: black;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4">Invoice Management</h2>

        <!-- Display Table -->
        <table id="invoiceTable" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th>Action</th>
                    <th>Invoice ID</th>
                    <th>PO ID</th>
                    <th>Vendor ID</th>
                    <th>Invoice Date</th>
                    <th>Due Date</th>
                    <th>Total Amount</th>
                    <th>Additional Fees</th>
                    <th>Discounts</th>
                    <th>Payment Status</th>
                    <th>Remarks</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($data['success']) && $data['success']): ?>
                    <?php foreach ($data['data'] as $item): ?>
                        <tr>
                            <td>
                                <?php if ($item['payment_status'] == 'Approved') { ?>
                                    <button class="btn btn-primary btnMarkPaid" data-invoice_id="<?= $item['invoice_id'] ?>">Mark as Paid</button>
                                <?php } else { ?>
                                    <button class="btn btn-primary btnMarkPaid" data-invoice_id="<?= $item['invoice_id'] ?>" disabled>Mark as Paid</button>
                                <?php } ?>
                            </td>
                            <td><?= htmlspecialchars($item['invoice_id'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($item['po_id'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($item['vendor_id'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($item['invoice_date'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($item['due_date'] ?? 'N/A') ?></td>
                            <td><?= number_format($item['total_amount'] ?? 0, 2) ?></td>
                            <td><?= number_format($item['additional_fees'] ?? 0, 2) ?></td>
                            <td><?= number_format($item['discounts'] ?? 0, 2) ?></td>
                            <td>
                                <?php if ($item['payment_status'] == 'Pending') { ?>
                                    <span class="badge status-pending"><?= htmlspecialchars($item['payment_status'] ?? 'N/A') ?></span>
                                <?php } else { ?>
                                    <span class="badge bg-success"><?= htmlspecialchars($item['payment_status'] ?? 'N/A') ?></span>
                                <?php } ?>
                            </td>
                            <td><?= htmlspecialchars($item['remarks'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($item['created_at'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($item['updated_at'] ?? 'N/A') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="13">No data found or error loading data</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal for Marking as Paid -->
    <div class="modal fade" id="markPaidModal" tabindex="-1" aria-labelledby="markPaidModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="markPaidModalLabel">Mark Invoice as Paid</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="markPaidForm">
                        <div class="mb-3">
                            <label for="remarks" class="form-label">Remarks</label>
                            <textarea class="form-control" id="remarks" name="remarks" required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="submitMarkPaid">Submit</button>
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
            $('#invoiceTable').DataTable({
                "order": [
                    [4, "desc"]
                ], // Order by invoice date
                "columnDefs": [{
                        "orderable": false,
                        "targets": [0]
                    }, // Non-orderable columns
                    {
                        "type": "num-fmt",
                        "targets": [6, 7, 8]
                    }, // Numeric columns
                    {
                        "type": "date",
                        "targets": [4, 5, 11, 12]
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

        $('.btnMarkPaid').click(function() {
            const invoice_id = $(this).data('invoice_id');
            $('#markPaidModal').modal('show'); // Show the modal

            // When Submit is clicked
            $('#submitMarkPaid').off('click').on('click', function() {
                const remarks = $('#remarks').val();

                if (remarks != "") {
                    $.ajax({
                        url: 'https://logistic2.paradisehoteltomasmorato.com/api/invoice/process_invoice.php',
                        type: 'PUT',
                        headers: {
                            'Authorization': 'Bearer eyJhbGciOiJIUzI1NiJ9.e30.N1zbjIM1soge9NU9H842bRVL8hfSECOfGtzgggsH648',
                            'Content-Type': 'application/json'
                        },
                        data: JSON.stringify({
                            invoice_id: invoice_id,
                            payment_status: 'Paid',
                            remarks: remarks
                        }),
                        success: function(response) {
                            try {
                                const data = JSON.parse(response);
                                if (data.success) {
                                    alert(data.message || 'Invoice marked as paid successfully');
                                } else {
                                    alert(data.message || 'Failed to mark invoice as paid');
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
                    alert("Please fill in the Remarks field");
                }
            });
        });
    </script>
</body>

</html>