<?php
session_start();
include('includes/dbconnection.php');

// Check if the user is logged in
if (!isset($_SESSION['ocasuid']) || strlen($_SESSION['ocasuid']) == 0) {
    header('location:logout.php');
    exit;
}

$userid = $_SESSION['ocasuid']; // Get logged-in user's ID
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>View Alerts</title>
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
            <div class="container-fluid pt-4 px-4">
                <div class="bg-light rounded p-4">
                    <h4 class="mb-0 text-danger">Your Reminders(System Alerts)</h4>
                </div>
                <div class="table-responsive">
                    <table class="table text-start align-middle table-bordered table-hover mb-0">
                        <thead>
                            <tr class="text-danger">
                              <th>Serial No</th>
                              <th>Title</th>
                                <th>Message</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                // Fetch only automated notifications for the logged-in user
                                $sql = "SELECT * FROM tblnotifications WHERE type = 'automated' AND (recipient_id = :userid OR recipient_id IS NULL) ORDER BY created_at DESC";
                                $query = $dbh->prepare($sql);
                                $query->bindParam(':userid', $userid, PDO::PARAM_INT);  // Bind the logged-in user's ID
                                $query->execute();
                                $results = $query->fetchAll(PDO::FETCH_OBJ);
                                $c=1;
                                if (count($results) > 0) {
                                    foreach ($results as $notif) {
                                        echo "<tr>";
                                         echo "<td>$c</td>";
                                        echo "<td>" . htmlentities($notif->title) . "</td>";
                                        echo "<td>" . htmlentities($notif->message) . "</td>";
                                        echo "<td style='color: red';>" . htmlentities($notif->created_at) . "</td>"; // Regular date style
                                        echo "</tr>";
                                        $c++; // Increment count
                                    }
                                } else {
                                    echo "<tr><td colspan='3' class='text-center'>No alerts found.</td></tr>";
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
