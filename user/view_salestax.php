<?php
session_start(); 
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['ocasuid']) == 0) {
    header('location:logout.php');
} else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>OTMS || View Sales Tax Application</title>

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
                <div class="row g-4">
                    <div class="col-sm-12">
                        <div class="bg-light rounded h-100 p-4">
                            <h4 class="mb-4 text-danger">Check Sales Tax Application</h4>
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
                            <div class="row">
                                <!-- Personal Information -->
                                <div class="col-md-6">
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <h5 class="card-title text-primary">Personal Information</h5>
                                            <p><strong>Name:</strong> <?php echo htmlentities($row->name); ?></p>
                                            <p><strong>CNIC:</strong> <?php echo htmlentities($row->cnic); ?></p>
                                            <p><strong>Sales Amount:</strong> <?php echo htmlentities($row->sales_amount); ?></p>
                                            <p><strong>Tax Year:</strong> <?php echo htmlentities($row->tax_year); ?></p>
                                            <p><strong>Contact:</strong> <?php echo htmlentities($row->contact); ?></p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Document Information -->
                                <div class="col-md-6">
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <h5 class="card-title text-primary">View Documents</h5>
                                            <p><strong>View CNIC:</strong> <a href="../user/folder1/<?php echo $row->File1; ?>" target="_blank" class="text-danger"><strong>View</strong></a></p>
                                            <p><strong>Sales Invoice:</strong> <a href="../user/folder2/<?php echo $row->File2; ?>" target="_blank" class="text-danger"><strong>View</strong></a></p>
                                            <p><strong>Other Sales Document:</strong> <a href="../user/folder3/<?php echo $row->File3; ?>" target="_blank" class="text-danger"><strong>View</strong></a></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <a class="btn btn-sm btn-warning" href="manageincometax.php?editid=<?php echo htmlentities($row->ID); ?>">Back</a>
                            <?php 
                                } 
                            } else {
                                echo "<p class='text-danger'>No record found.</p>";
                            }
                            ?>
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
<?php } ?> 
