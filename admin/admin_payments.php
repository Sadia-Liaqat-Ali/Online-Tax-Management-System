<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['ocasaid']) == 0) {
    header('location:logout.php');
}

// Delete payment record
if (isset($_GET['delid'])) {
    $delid = intval($_GET['delid']);
    $sql = "DELETE FROM tblpayments WHERE ID = :delid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':delid', $delid, PDO::PARAM_INT);
    $query->execute();
    if ($query->rowCount() > 0) {
        echo "<script>alert('Payment record deleted successfully');</script>";
        echo "<script>window.location.href = 'admin_payments.php';</script>";
    } else {
        echo "<script>alert('Failed to delete payment record');</script>";
    }
}

// Update payment status
if (isset($_POST['updateStatus'])) {
    $paymentId = intval($_POST['paymentId']);
    $newStatus = $_POST['paymentStatus'];
    
    // Update the payment status
    $sql = "UPDATE tblpayments SET PaymentStatus = :newStatus WHERE ID = :paymentId";
    $query = $dbh->prepare($sql);
    $query->bindParam(':newStatus', $newStatus, PDO::PARAM_STR);
    $query->bindParam(':paymentId', $paymentId, PDO::PARAM_INT);
    $query->execute();
    
    if ($query->rowCount() > 0) {
        // Fetch the user ID associated with the payment
        $sql_fetch_user = "SELECT UserID FROM tblpayments WHERE ID = :paymentId";
        $stmt_fetch_user = $dbh->prepare($sql_fetch_user);
        $stmt_fetch_user->bindParam(':paymentId', $paymentId, PDO::PARAM_INT);
        $stmt_fetch_user->execute();
        $user_result = $stmt_fetch_user->fetch(PDO::FETCH_ASSOC);

        if ($user_result) {
            $recipient_id = $user_result['UserID'];
            $admin_id = $_SESSION['ocasaid'];
            $title = "Tax Returns Proccessing Update";
            $message = "";

            // Set different messages for different payment statuses
            if ($newStatus === 'Completed') {
                $message = "Your payment has been successfully completed.";
            } elseif ($newStatus === 'Failed') {
                $message = "Your payment attempt has failed. Please check your payment details and try again.";
            } elseif ($newStatus === 'Refunded') {
                $message = "Your payment has been refunded. Please check your account for the refunded amount.";
            }

            $type = "automated";

            // Insert the notification into tblnotifications
            $sql_alert = "INSERT INTO tblnotifications (title, message, recipient_id, admin_id, type, status) 
                          VALUES (:title, :message, :recipient_id, :admin_id, :type, 'active')";
            $stmt_alert = $dbh->prepare($sql_alert);
            $stmt_alert->bindParam(':title', $title);
            $stmt_alert->bindParam(':message', $message);
            $stmt_alert->bindParam(':recipient_id', $recipient_id, PDO::PARAM_INT);
            $stmt_alert->bindParam(':admin_id', $admin_id, PDO::PARAM_INT);
            $stmt_alert->bindParam(':type', $type);
            $stmt_alert->execute();

        }

        echo "<script>alert('Payment status updated successfully');</script>";
        echo "<script>window.location.href = 'admin_payments.php';</script>";
    } else {
        echo "<script>alert('Failed to update payment status');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin || Payment Management</title>
    <!-- Include CSS & JS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid position-relative bg-white d-flex p-0">
        <?php include_once('includes/sidebar.php'); ?>
        <div class="content">
            <?php include_once('includes/header.php'); ?>
            <div class="container-fluid pt-4 px-4">
                <div class="bg-light text-start rounded p-4">
                    <h4 class="mb-4">Manage Payments</h4>
                    <table class="table table-striped table-bordered">
                        <thead class="text-danger">
                            <tr>
                                <th>User ID</th>
                                <th>Tax Category</th>
                                <th>Payment Method</th>
                                <th>Amount</th>
                                <th>Check proof</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                           
                         <tbody>
    <?php
    $sql = "SELECT * FROM tblpayments ORDER BY PaymentDate DESC";
    $query = $dbh->prepare($sql);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);
    if ($query->rowCount() > 0) {
        foreach ($results as $row) {
            // Generate the proof link only if the file exists
            $proofLink = !empty($row->ProofDocument) ? "<a href='../user/img/{$row->ProofDocument}' target='_blank'>View Proof</a>" : "No proof uploaded";

            echo "<tr>
                    <td>{$row->UserID}</td>
                    <td>{$row->TaxCategory}</td>
                    <td>{$row->PaymentMethod}</td>
                    <td>{$row->Amount}</td>
                    <td>{$proofLink}</td>
                    <td>{$row->PaymentStatus}</td>
                    <td>{$row->PaymentDate}</td>
                    <td>
                        <a href='#' onclick=\"editPaymentStatus({$row->ID}, '{$row->PaymentStatus}')\" class='btn btn-primary btn-sm'>Edit</a>
                        <a href='admin_payments.php?delid={$row->ID}' onclick=\"return confirm('Are you sure you want to delete this payment?');\" class='btn btn-danger btn-sm'>Delete</a>
                    </td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='8'>No payments found</td></tr>";
    }
    ?>
</tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Payment Status Modal -->
    <div class="modal fade" id="editStatusModal" tabindex="-1" aria-labelledby="editStatusModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" action="">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editStatusModalLabel">Edit Payment Status</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="paymentId" id="paymentId">
                        <div class="mb-3">
                            <label for="paymentStatus" class="form-label">Payment Status</label>
                            <select class="form-select" name="paymentStatus" id="paymentStatus" required>
                                <option value="Pending">Pending</option>
                                <option value="Completed">Completed</option>
                                <option value="Failed">Failed</option>
                                <option value="Refunded">Refunded</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="updateStatus" class="btn btn-success">Update Status</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
         <?php include_once('includes/footer.php'); ?>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/chart/chart.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>
    <script src="js/main.js"></script>
    <script>
        function editPaymentStatus(paymentId, currentStatus) {
            $('#paymentId').val(paymentId);
            $('#paymentStatus').val(currentStatus);
            $('#editStatusModal').modal('show');
        }
    </script>
</body>
</html>
