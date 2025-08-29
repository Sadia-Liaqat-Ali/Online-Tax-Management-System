<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['ocasaid']) == 0) {
    header('location:logout.php');
} else {
    if (isset($_POST['submit'])) {
        $status = $_POST['status'];
        $eid = $_GET['editid'];

        // Fetch UserID related to the application
        $sql_get_user = "SELECT UserID FROM tblsalestax WHERE ID=:eid";
        $query_get_user = $dbh->prepare($sql_get_user);
        $query_get_user->bindParam(':eid', $eid, PDO::PARAM_STR);
        $query_get_user->execute();
        $user_result = $query_get_user->fetch(PDO::FETCH_OBJ);

        if ($user_result) {
            $user_id = $user_result->UserID;

            // Update the application status
            $sql_update = "UPDATE tblsalestax SET status=:status WHERE ID=:eid";
            $query_update = $dbh->prepare($sql_update);
            $query_update->bindParam(':status', $status, PDO::PARAM_STR);
            $query_update->bindParam(':eid', $eid, PDO::PARAM_STR);
            $query_update->execute();

            // Add notification for verified or rejected status
            $admin_id = $_SESSION['ocasaid'];
            $type = "automated"; // Notification type: automated
            $title = "";
            $message = "";

            if ($status === 'verified') {
                $title = "Sales Tax Application Verified";
                $message = "Your Sales Tax application has been verified successfully.";
            } elseif ($status === 'rejected') {
                $title = "Sales Tax Application Rejected";
                $message = "Your Sales Tax application has been rejected as the submitted documents do not meet the required authenticity and accuracy standards. Please ensure your documents are correct and resubmit them for further processing.";
            }

            // Insert notification only for verified or rejected cases
            if (!empty($title) && !empty($message)) {
                $sql_alert = "INSERT INTO tblnotifications (title, message, recipient_id, admin_id, type, status) 
                              VALUES (:title, :message, :recipient_id, :admin_id, :type, 'active')";
                $stmt_alert = $dbh->prepare($sql_alert);
                $stmt_alert->bindParam(':title', $title);
                $stmt_alert->bindParam(':message', $message);
                $stmt_alert->bindParam(':recipient_id', $user_id);
                $stmt_alert->bindParam(':admin_id', $admin_id);
                $stmt_alert->bindParam(':type', $type);
                $stmt_alert->execute();
            }

            echo '<script>alert("Status has been updated successfully.")</script>';
            echo "<script>window.location.href ='manageapplication.php'</script>";
        } else {
            echo '<script>alert("User associated with the application not found.")</script>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>OTMS || Update Sales Tax Application</title>
    <!-- Include required CSS files -->
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
                <div class="row g-4">
                    <div class="col-sm-12 col-xl-6">
                        <div class="bg-light rounded h-100 p-4">
                            <h5 class="mb-4 text-danger">Check Sales Tax Applications</h5>
                            <?php
                            $eid = $_GET['editid'];
                            $sql = "SELECT * FROM tblsalestax WHERE ID=:eid";
                            $query = $dbh->prepare($sql);
                            $query->bindParam(':eid', $eid, PDO::PARAM_STR);
                            $query->execute();
                            $results = $query->fetchAll(PDO::FETCH_OBJ);

                            if ($query->rowCount() > 0) {
                                foreach ($results as $row) {
                            ?>
                           <div class="card mb-3">
    <div class="card-body">
        <h5 class="card-title text-primary">User Details</h5>
        <p><strong>Name:</strong> <?php echo htmlentities($row->name); ?></p>
        <p><strong>CNIC:</strong> <?php echo htmlentities($row->cnic); ?></p>
        <p><strong>Address:</strong> <?php echo htmlentities($row->address); ?></p>
        <p><strong>Contact:</strong> <?php echo htmlentities($row->contact); ?></p>
        <p><strong>Tax Year:</strong> <?php echo htmlentities($row->tax_year); ?></p>
        <p><strong>Sales Amount:</strong> <?php echo htmlentities($row->sales_amount); ?></p>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body">
        <h5 class="card-title text-primary">Verify Documents</h5>
        <p><strong>View CNIC:</strong> <a href="../user/folder1/<?php echo $row->File1; ?>" target="_blank" class="text-danger"><strong>View</strong></a></p>
        <p><strong>Sales Invoice:</strong> <a href="../user/folder2/<?php echo $row->File2; ?>" target="_blank" class="text-danger"><strong>View</strong></a></p>
        <p><strong>Other Sales Document:</strong> <a href="../user/folder3/<?php echo $row->File3; ?>" target="_blank" class="text-danger"><strong>View</strong></a></p>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body">
        <h5 class="card-title text-primary">Update Status</h5>
        <form method="post">
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-control" name="status" required='true'>
                    <option value="pending" <?php if($row->status == 'pending') echo 'selected'; ?>>Pending</option>
                    <option value="rejected" <?php if($row->status == 'rejected') echo 'selected'; ?>>Rejected</option>
                    <option value="verified" <?php if($row->status == 'verified') echo 'selected'; ?>>Verified</option>
                </select>
            </div>

            <button type="submit" name="submit" class="btn btn-primary">Update Status</button>
     
                            </form>
                            <?php }} ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php include_once('includes/footer.php'); ?>
        </div>

        <?php include_once('includes/back-totop.php'); ?>
    </div>

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
