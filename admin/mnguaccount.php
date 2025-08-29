<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

// Check if the admin is logged in
if (strlen($_SESSION['ocasaid'] == 0)) {
    header('location:logout.php');
}

// Delete user account
if (isset($_GET['delid'])) {
    $delid = intval($_GET['delid']);

    // Directly delete from tbluser (removed tblTP_accounts deletion)
    $sql = "DELETE FROM tbluser WHERE ID = :delid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':delid', $delid, PDO::PARAM_INT);
    $query->execute();

    if ($query->rowCount() > 0) {
        echo "<script>alert('User Account Deleted Successfully.');</script>";
        echo "<script>window.location.href = 'mnguaccount.php';</script>";
    } else {
        echo "<script>alert('Unable to delete user account.');</script>";
    }
}

// Removed function addNewTaxpayerAccount as it's no longer needed
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>OTM | Manage User Accounts</title>
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
        <!-- Sidebar inclusion -->
        <?php include_once('includes/sidebar.php'); ?>

        <div class="content">
            <!-- Header inclusion -->
            <?php include_once('includes/header.php'); ?>

            <!-- Manage User Accounts Section -->
            <div class="container-fluid pt-4 px-4">
                <div class="bg-light text-center rounded p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h5 class="mb-0">Manage User Accounts</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table text-start align-middle table-bordered table-hover mb-0">
                            <thead>
                                <tr class="text-danger">
                                    <th>#</th>
                                    <th>Full Name</th>
                                    <th>Mobile Number</th>
                                    <th>Email</th>
                                    <th>Registration Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT * FROM tbluser";
                                $query = $dbh->prepare($sql);
                                $query->execute();
                                $results = $query->fetchAll(PDO::FETCH_OBJ);
                                $cnt = 1;

                                if ($query->rowCount() > 0) {
                                    foreach ($results as $row) {
                                ?>
                                        <tr>
                                            <td><?php echo htmlentities($cnt); ?></td>
                                            <td><?php echo htmlentities($row->FullName); ?></td>
                                            <td><?php echo htmlentities($row->MobileNumber); ?></td>
                                            <td><?php echo htmlentities($row->Email); ?></td>
                                            <td><?php echo htmlentities($row->RegDate); ?></td>
                                            <td>
                                                <a href="edituseraccounts.php?id=<?php echo htmlentities($row->ID); ?>" class="btn btn-sm btn-primary">Edit</a>
                                                <a href="mnguaccount.php?delid=<?php echo htmlentities($row->ID); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Do you really want to delete this account?');">Delete</a>
                                            </td>
                                        </tr>
                                <?php
                                        $cnt = $cnt + 1;
                                    }
                                } else {
                                    echo "<tr><td colspan='6'>No user accounts found.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Footer inclusion -->
            <?php include_once('includes/footer.php'); ?>
        </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/chart/chart.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>
    <script src="js/main.js"></script>
</body>

</html>
