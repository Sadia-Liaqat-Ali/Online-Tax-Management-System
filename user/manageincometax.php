<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['ocasuid'] == 0)) {
    header('location:logout.php');
} else {
    // Delete logic
    if (isset($_GET['delid'])) {
        $delid = $_GET['delid'];
        $sql = "DELETE FROM tblincometax WHERE ID=:delid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':delid', $delid, PDO::PARAM_STR);
        $query->execute();

        $sql = "DELETE FROM tblsalestax WHERE ID=:delid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':delid', $delid, PDO::PARAM_STR);
        $query->execute();

        $sql = "DELETE FROM tblpropertytax WHERE ID=:delid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':delid', $delid, PDO::PARAM_STR);
        $query->execute();

        // Redirect to the same page after deletion
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>OTM || View Applications</title>
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
        <!-- Content Start -->
        <div class="content">
            <?php include_once('includes/header.php'); ?>

            <div class="container-fluid pt-4 px-4">
                <div class="bg-light text-center rounded p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h4 class="mb-0 text-danger">Manage Applications</h4>
                    </div>
                    <div class="table-responsive">
                        <table class="table text-start align-middle table-bordered table-hover mb-0">
                            <thead>
                                <tr class="text-danger">
                                    <th scope="col">Serial No</th>
                                   <th scope="col">Type</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">CNIC</th>
                                    <th scope="col">Tax Year</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $ocasuid = $_SESSION['ocasuid'];
                                $queries = [
                                    "SELECT ID, name, cnic, tax_year, 'Income Tax' AS type, status FROM tblincometax WHERE UserID=:ocasuid",
                                    "SELECT ID, name, cnic, tax_year, 'Sales Tax' AS type, status FROM tblsalestax WHERE UserID=:ocasuid",
                                    "SELECT ID, name, cnic, tax_year, 'Property Tax' AS type, status FROM tblpropertytax WHERE UserID=:ocasuid"
                                ];

                                $cnt = 1;
                                foreach ($queries as $sql) {
                                    $query = $dbh->prepare($sql);
                                    $query->bindParam(':ocasuid', $ocasuid, PDO::PARAM_STR);
                                    $query->execute();
                                    $results = $query->fetchAll(PDO::FETCH_OBJ);

                                    if ($query->rowCount() > 0) {
                                        foreach ($results as $row) {
                                            ?>
                                            <tr>
                                                <td><?php echo htmlentities($cnt); ?></td>
                                                <td class="text-primary"><?php echo htmlentities($row->type); ?></td>

                                                <td><?php echo htmlentities($row->name); ?></td>
                                                <td><?php echo htmlentities($row->cnic); ?></td>
                                                <td><?php echo htmlentities($row->tax_year); ?></td>
                                                <td><?php echo htmlentities($row->status); ?></td>
                                                <td>
                                                    <?php if ($row->status == "Pending") { ?>
                                                        <a class="btn btn-sm btn-primary" href="edit<?php echo strtolower(str_replace(' ', '', $row->type)); ?>.php?editid=<?php echo htmlentities($row->ID); ?>">Edit</a>
                                                        <a class="btn btn-sm btn-danger" href="manageincometax.php?delid=<?php echo htmlentities($row->ID); ?>" onclick="return confirm('Do you really want to delete?');">Delete</a>
                                                    <?php } else { ?>
                                                        <a class="btn btn-sm btn-secondary" href="view_<?php echo strtolower(str_replace(' ', '', $row->type)); ?>.php?editid=<?php echo htmlentities($row->ID); ?>">View</a>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <?php
                                            $cnt++;
                                        }
                                    }
                                }

                                if ($cnt == 1) { ?>
                                    <tr>
                                        <td colspan="7" class="text-center">No application Record found for you.</td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
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
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>
    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>
</html>
<?php } ?>
