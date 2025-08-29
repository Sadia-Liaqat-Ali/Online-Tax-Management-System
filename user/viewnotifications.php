<?php
// user/viewnotifications.php
session_start();
include('includes/dbconnection.php');

if (strlen($_SESSION['ocasuid'] == 0)) {
    header('location:logout.php');
    exit;
}

$userid = $_SESSION['ocasuid']; // Assuming user ID is stored in session

// Mark notifications as read when the page is loaded
$sql = "UPDATE tblnotifications SET status = 'read' WHERE (recipient_id = :userid OR recipient_id IS NULL) AND status = 'unread'";
$query = $dbh->prepare($sql);
$query->bindParam(':userid', $userid, PDO::PARAM_STR);
$query->execute();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>My Notifications</title>
    
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

    <style>
        body {
            font-family: 'Heebo', sans-serif;
        }

        .notifications-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .notification-card {
            background-color: white;
            border: 3px solid #ddd;
            border-radius: 10px;
            padding: 15px;
            display: flex;
            flex-direction: column;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .notification-card:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 2);
        }

        .notification-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #007bff;
        }

        .notification-date {
            font-size: 0.9rem;
            color: red;
            margin-top: 15px;
            align-self: flex-end;
        }
        .no-notifications {
            text-align: center;
            padding: 20px;
            font-size: 18px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container-fluid position-relative bg-white d-flex p-0">
        <?php include_once('includes/sidebar.php'); ?>
        
        <div class="content">
            <?php include_once('includes/header.php'); ?>

            <div class="container-fluid pt-4 px-4">
                <div class="bg-light rounded p-4">
                    <h4 class="mb-0 text-danger">Your Notifications</h4>
                </div>
                <div class="notifications-container">
                    <?php
                    // Fetch only custom notifications for the user
                    $sql = "SELECT * FROM tblnotifications WHERE (recipient_id = :userid OR recipient_id is NULL) AND type = 'custom' ORDER BY created_at DESC";
                    $query = $dbh->prepare($sql);
                    $query->bindParam(':userid', $userid, PDO::PARAM_STR);
                    $query->execute();
                    $results = $query->fetchAll(PDO::FETCH_OBJ);

                    if ($query->rowCount() > 0) {
                        foreach ($results as $notif) {
                            echo '<div class="notification-card">';
                            echo '<div class="notification-title">' . htmlentities($notif->title) . '</div>';
                            echo '<div class="notification-message">' . htmlentities($notif->message) . '</div>';
                            echo '<div class="notification-date">' . htmlentities($notif->created_at) . '</div>';
                            echo '</div>';
                        }
                    } else {
                        echo "<div class='no-notifications'>No custom notifications found.</div>";
                    }
                    ?>
                </div>
            </div>

            <!-- Footer -->
            <?php include('includes/footer.php'); ?>

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

            <!-- Template Javascript -->
            <script src="js/main.js"></script>
        </body>
</html>
