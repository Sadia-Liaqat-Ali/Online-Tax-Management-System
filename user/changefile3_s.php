<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['ocasuid']) == 0) {
    header('location:logout.php');
} else {
    if (isset($_POST['submit'])) {
        $eid = $_GET['editid'];
        $file3 = $_FILES["file3"]["name"];
        $extension3 = strtolower(substr($file3, strrpos($file3, '.')));
        $allowed_extensions3 = array(".pdf");

        if (!in_array($extension3, $allowed_extensions3)) {
            echo "<script>alert('Other Sales Document has Invalid format. Only PDF format allowed');</script>";
        } else {
            // Generate unique file name
            $file3_new = md5($file3) . time() . $extension3;
            move_uploaded_file($_FILES["file3"]["tmp_name"], "folder3/" . $file3_new);

            // Update query
            $sql = "UPDATE tblsalestax SET File3 = :file3 WHERE ID = :eid";
            $query = $dbh->prepare($sql);
            $query->bindParam(':file3', $file3_new, PDO::PARAM_STR);
            $query->bindParam(':eid', $eid, PDO::PARAM_STR);
            $query->execute();

            echo '<script>alert("Other Sales Document has been updated successfully.")</script>';
            echo "<script>window.location.href ='editsalestax.php'</script>";
        }
    }

    // Fetch existing data
    if (isset($_GET['editid'])) {
        $eid = $_GET['editid'];
        $sql = "SELECT * FROM tblsalestax WHERE ID = :eid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':eid', $eid, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>OTM || Update Other Sales Document</title>
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
        <?php include_once('includes/sidebar.php');?>

        <!-- Content Start -->
        <div class="content">
            <?php include_once('includes/header.php');?>

            <!-- Form Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="row g-4">
                    <div class="col-sm-12 col-xl-6">
                        <div class="bg-light rounded h-100 p-4">
                            <h6 class="mb-4">Update Other Sales Document</h6>
                            <form method="post" enctype="multipart/form-data">
                                
                                <div class="mb-3">
                                    <label for="file3" class="form-label">Upload New Document (PDF)</label>
                                    <input type="file" class="form-control" name="file3" id="file3" accept=".pdf">
                                </div>
                                <button type="submit" name="submit" class="btn btn-success">Update</button>
                                
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Form End -->

            <?php include_once('includes/footer.php');?>
        </div>
        <!-- Content End -->

        <?php include_once('includes/back-totop.php');?>
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
<?php  ?>
