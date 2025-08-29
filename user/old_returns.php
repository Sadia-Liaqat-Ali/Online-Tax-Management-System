<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['ocasuid']) == 0) {
    header('location:logout.php');
}

$userId = $_SESSION['ocasuid'];

// Delete tax return application
if (isset($_GET['delid'])) {
    $delid = intval($_GET['delid']);
    $sql = "DELETE FROM tblpayments WHERE ID = :delid AND UserID = :userId AND PaymentStatus = 'Pending'";
    $query = $dbh->prepare($sql);
    $query->bindParam(':delid', $delid, PDO::PARAM_INT);
    $query->bindParam(':userId', $userId, PDO::PARAM_INT);
    $query->execute();
    if ($query->rowCount() > 0) {
        echo "<script>alert('Tax return application deleted successfully');</script>";
        echo "<script>window.location.href = 'user_old_applications.php';</script>";
    } else {
        echo "<script>alert('Failed to delete application or status is not pending');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User || Old Tax Return Applications</title>
    <!-- Include CSS & JS -->
    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />
    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
      <div class="container-fluid position-relative bg-white d-flex p-0">
        <?php include_once('includes/sidebar.php'); ?>

        <div class="content">
            <?php include_once('includes/header.php'); ?>
    <div class="container-fluid pt-4 px-4">
        <div class="bg-light rounded p-4">
            <h4 class="mb-4">Your Old Tax Return Applications</h4>
            <table class="table table-striped table-bordered">
                <thead class="text-danger">
                    <tr>
                        <th>Tax Category</th>
                        <th>Payment Method</th>
                        <th>Amount</th>
                        <th>Payment Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                  <?php
$sql = "SELECT * FROM tblpayments WHERE UserID = :userId ORDER BY PaymentDate DESC";
$query = $dbh->prepare($sql);
$query->bindParam(':userId', $userId, PDO::PARAM_INT);
$query->execute();
$results = $query->fetchAll(PDO::FETCH_OBJ);

if ($query->rowCount() > 0) {
    foreach ($results as $row) {
        echo "<tr>
                <td>{$row->TaxCategory}</td>
                <td>{$row->PaymentMethod}</td>
                <td>{$row->Amount}</td>
                <td>{$row->PaymentStatus}</td>
                <td>{$row->PaymentDate}</td>
                <td>";

        // Check PaymentStatus for conditional rendering of actions
        if ($row->PaymentStatus == 'Pending') {
            // Display Edit and Delete buttons
            echo "<a href='edit_payment.php?ID={$row->ID}' class='btn btn-primary btn-sm'>Edit</a>
                  <a href='old_returns.php?delid={$row->ID}' onclick=\"return confirm('Are you sure you want to delete this application?');\" class='btn btn-danger btn-sm'>Delete</a>";
        } elseif (in_array($row->PaymentStatus, ['Completed', 'Refunded', 'Failed'])) {
            // Display "Only viewable" for specific statuses
            echo "<span style='color: red;'>Only viewable</span>";
        }

        echo "</td></tr>";
    }
} else {
    echo "<tr><td colspan='6'>No tax return applications found</td></tr>";
}
?>

                </tbody>
            </table>
         <?php include_once('includes/footer.php'); ?>
        </div>

        <?php include_once('includes/back-totop.php'); ?>
    </div></div></div>
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
</body>
</html>
<?php  ?>

