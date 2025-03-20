<!-- Modal for Approval/Rejection -->

<div class="modal fade" id="approvalModal" tabindex="-1" aria-labelledby="approvalModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approvalModalLabel">Approve or Reject Budget</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                
                <input type="hidden" name="req_id" value="<?= $_POST['req_id'] ?>" class="form-control">
                <?php if($_POST['action'] == "Approve"){ ?>
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount</label>
                        <input type="number" class="form-control" id="amount" name="amount" required>
                    </div>
                <?php } ?>
                <div class="mb-3">
                    <label for="remarks" class="form-label">Remarks</label>
                    <textarea class="form-control" id="remarks" name="remarks" required></textarea>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="submitApproval">Submit</button>
            </div>
        </div>
    </div>
</div>


<script>
    $('#submitApproval').click(function() {
        const req_id = '<?= $_POST['req_id'] ?>';
        const action = '<?= $_POST['action'] ?>';
        const amount = $('#amount').val();
        const remarks = $('#remarks').val();
        if (amount !== "" && remarks !== "") {
            $.ajax({
                url: 'https://logistic2.paradisehoteltomasmorato.com/api/budget-approval/process_update.php',
                type: 'POST',
                headers: {
                    'Authorization': 'Bearer eyJhbGciOiJIUzI1NiJ9.e30.Wn2PDfdI1zpEGcXool1YdXhVyCJVv7Ea07doKnxB4Hw',
                    'Content-Type': 'application/json'
                },
                data: JSON.stringify({
                    requisition_id: req_id,
                    remarks: remarks,
                    amount: amount,
                    action: action
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
    })
</script>