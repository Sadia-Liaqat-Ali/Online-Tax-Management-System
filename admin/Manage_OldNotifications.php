<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

// Check if the admin is logged in
if (strlen($_SESSION['ocasaid'] == 0)) {
    header('location:logout.php');
    exit;
}

// Handle notification deletion
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Prepare and execute delete query
    $query = $dbh->prepare("DELETE FROM tblnotifications WHERE id = :id");
    $query->bindParam(':id', $delete_id, PDO::PARAM_INT);
    if ($query->execute()) {
        // Redirect to the same page after deletion
        header('Location: Manage_OldNotifications.php');
        exit;
    } else {
        echo "<script>alert('Error in deleting notification.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en"> 
<head>
    <title>Manage Notifications</title>
    
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
                <div class="bg-light text-start rounded p-4">
                    <h4 class=" font-awesome mb-4">Existing Notifications</h4>
                    <div class="table-responsive">
                        <table class="table text-start align-middle table-bordered table-hover mb-0">
                            <thead>
                                <tr class="text-danger">
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Type</th>
                                    <th>Recipient</th>
                                    <th>Date Created</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Fetch notifications using PDO
                                $notif_query = $dbh->query("SELECT * FROM tblnotifications WHERE type = 'custom' ORDER BY created_at DESC");
                                while ($notif = $notif_query->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<tr>";
                                    echo "<td>" . $notif['id'] . "</td>";
                                    echo "<td>" . htmlentities($notif['title']) . "</td>";
                                    echo "<td>" . htmlentities($notif['type']) . "</td>";
                                    echo "<td>" . ($notif['recipient_id'] ? htmlentities(getUserName($notif['recipient_id'], $dbh)) : 'All Users') . "</td>";
                                    echo "<td>" . htmlentities($notif['created_at']) . "</td>";
                                    echo "<td>" . htmlentities($notif['status']) . "</td>";
                                    
                                    // Bootstrap-styled Edit and Delete buttons
                                    echo "<td>
                                            <a href='edit_notification.php?id=" . $notif['id'] . "' class='btn btn-sm btn-primary'> Edit</a>
                                            <a href='?delete_id=" . $notif['id'] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure you want to delete this notification?\");'> Delete</a>
                                          </td>";
                                    echo "</tr>";
                                }

                                // Function to get user name based on recipient ID
                                function getUserName($userID, $dbh)
                                {
                                    $query = $dbh->prepare("SELECT FullName FROM tbluser WHERE ID = :id");
                                    $query->bindParam(':id', $userID, PDO::PARAM_INT);
                                    $query->execute();
                                    $row = $query->fetch(PDO::FETCH_ASSOC);
                                    return $row['FullName'];
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <?php include('includes/footer.php'); ?>
            </div>
        </div>
    </div>

    <!-- jQuery, Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>
