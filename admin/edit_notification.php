<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

// Check if the admin is logged in
if (strlen($_SESSION['ocasaid'] == 0)) {
    header('location:logout.php');
    exit;
}

// Check if notification ID is set in the URL
if (isset($_GET['id'])) {
    $notif_id = intval($_GET['id']);

    // Fetch the current notification data
    $query = $dbh->prepare("SELECT * FROM tblnotifications WHERE id = :id");
    $query->bindParam(':id', $notif_id, PDO::PARAM_INT);
    $query->execute();
    $notif = $query->fetch(PDO::FETCH_ASSOC);

    if (!$notif) {
        echo "<script>alert('Notification not found');</script>";
        echo "<script>window.location.href = 'managenotifications.php'</script>";
        exit;
    }
}

// Handle form submission for updating notification
if (isset($_POST['update'])) {
    $title = $_POST['title'];
    $message = $_POST['message'];
    $type = 'custom'; // Always set type to 'custom'
    $recipient_id = !empty($_POST['recipient']) ? $_POST['recipient'] : NULL;

    // Update the notification
    $sql = "UPDATE tblnotifications SET title = :title, message = :message, type = :type, recipient_id = :recipient_id WHERE id = :id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':title', $title, PDO::PARAM_STR);
    $query->bindParam(':message', $message, PDO::PARAM_STR);
    $query->bindParam(':type', $type, PDO::PARAM_STR); // Always 'custom'
    $query->bindParam(':recipient_id', $recipient_id, PDO::PARAM_INT);
    $query->bindParam(':id', $notif_id, PDO::PARAM_INT);
    
    if ($query->execute()) {
        echo "<script>alert('Notification updated successfully');</script>";
        echo "<script>window.location.href = 'Manage_OldNotifications.php'</script>";
    } else {
        echo "<script>alert('Error updating notification');</script>";
    }
}

// Handle resending notification
if (isset($_POST['resend'])) {
    $admin_id = $_SESSION['ocasaid']; // Assuming admin ID is stored in session

    // Resend the same notification as a new entry in the database
    $sql = "INSERT INTO tblnotifications (title, message, type, recipient_id, admin_id) VALUES (:title, :message, :type, :recipient_id, :admin_id)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':title', $notif['title'], PDO::PARAM_STR);
    $query->bindParam(':message', $notif['message'], PDO::PARAM_STR);
    $query->bindParam(':type', $notif['type'], PDO::PARAM_STR);
    $query->bindParam(':recipient_id', $notif['recipient_id'], PDO::PARAM_INT);
    $query->bindParam(':admin_id', $admin_id, PDO::PARAM_INT);

    if ($query->execute()) {
        echo "<script>alert('Notification resent successfully');</script>";
        echo "<script>window.location.href = 'Manage_OldNotifications.php'</script>";
    } else {
        echo "<script>alert('Error resending notification');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Notification</title>

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
                <div class="row g-4">
                    <div class="col-sm-12 col-xl-8">
                        <div class="bg-light rounded h-100 p-4">
                    <h4 class="mb-4">Edit Notification</h4>
                    <form method="post" action="">
                        <div class="form-group">
                            <label>Title:</label>
                            <input type="text" name="title" class="form-control" value="<?php echo htmlentities($notif['title']); ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Message:</label>
                            <textarea name="message" class="form-control" required><?php echo htmlentities($notif['message']); ?></textarea>
                        </div>

                        <div class="form-group">
                            <label>Type:</label>
                            <input type="text" name="type" class="form-control" value="custom" readonly>
                        </div>

                        <div class="form-group">
                            <label>Recipient:</label>
                            <select name="recipient" class="form-control">
                                <option value="">All Users</option>
                                <?php
                                // Fetch users from tbluser
                                $user_query = $dbh->query("SELECT ID, FullName FROM tbluser");
                                while ($user = $user_query->fetch(PDO::FETCH_ASSOC)) {
                                    $selected = ($notif['recipient_id'] == $user['ID']) ? 'selected' : '';
                                    echo "<option value='" . $user['ID'] . "' $selected>" . $user['FullName'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <br>

                        <!-- Update Button -->
                        <input type="submit" name="update" class="btn btn-success" value="Update">
                        
                        <!-- Resend Button -->
                        <input type="submit" name="resend" class="btn btn-warning" value="Resend">

                    </form><br>

                </div>
            </div></div></div>

            <?php include('includes/footer.php'); ?>
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
    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>
</html>
