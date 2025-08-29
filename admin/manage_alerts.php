<?php
session_start();
include('includes/dbconnection.php');

// Check if the admin is logged in
if (!isset($_SESSION['ocasaid']) || strlen($_SESSION['ocasaid']) == 0) {
    header('location:logout.php');
    exit;
}

// Turn on error reporting for debugging (Remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Delete alert if requested
if (isset($_GET['del'])) {
    $delid = intval($_GET['del']);
    try {
        $sql = "DELETE FROM tblnotifications WHERE id=:delid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':delid', $delid, PDO::PARAM_INT);
        $query->execute();
        $_SESSION['msg'] = "Alert deleted successfully!";
        header('location:manage_alerts.php');
        exit;
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error deleting alert: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin - View Alerts</title>
    <!-- Stylesheets -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <style>
        .alert-container {
            margin: 20px auto;
            max-width: 900px;
        }
        body {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="container-fluid position-relative bg-white d-flex p-0">

        <?php include_once('includes/sidebar.php'); ?>
        <!-- Content Start -->
        <div class="content">
            <?php include_once('includes/header.php'); ?>
            <div class="container-fluid bg-light pt-4 px-4">
                <div class="bg-light text-start rounded p-4">
                    <h4 class="mb-0">Manage All Alerts</h4>
                </div>
                <div class="table-responsive">
                    <table class="table text-start align-middle table-bordered table-hover mb-0">
                        <thead>
                            <tr class="text-danger">
                                <th>Title</th>
                                <th>Message</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            try {
                                // Fetch all notifications for admin
                                $sql = "SELECT * FROM tblnotifications where type = 'automated'";
                                $query = $dbh->prepare($sql);
                                $query->execute();
                                $results = $query->fetchAll(PDO::FETCH_OBJ);

                                if ($query->rowCount() > 0) {
                                    foreach ($results as $notif) {
                                        echo "<tr>";
                                        echo "<td>" . htmlentities($notif->title) . "</td>";
                                        echo "<td>" . htmlentities($notif->message) . "</td>";
                                        echo "<td>" . htmlentities($notif->created_at) . "</td>";
                                        echo "<td>
                                        <a href='manage_alerts.php?del=" . intval($notif->id) . "' onclick='return confirm(\"Are you sure you want to delete this alert?\");' class='btn btn-danger btn-sm'>Delete</a></td>";
                                    }
                                } else {
                                    echo "<tr><td colspan='4' class='text-center'>No alerts found.</td></tr>";
                                }
                            } catch (PDOException $e) {
                                echo "<tr><td colspan='4' class='text-center text-danger'>Error fetching alerts: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <?php include('includes/footer.php'); ?>
            <!-- Bootstrap JS and dependencies -->
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
