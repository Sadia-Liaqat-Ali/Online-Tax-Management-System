<?php 
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['ocasaid'] == 0)) {
    header('location:logout.php');
} else {
    // Deletion logic
    if (isset($_GET['delid']) && isset($_GET['type'])) {
        $rid = intval($_GET['delid']);
        $type = $_GET['type'];

        if ($type == 'income') {
            $sql = "DELETE FROM tblincometax WHERE ID=:rid";
        } elseif ($type == 'sales') {
            $sql = "DELETE FROM tblsalestax WHERE ID=:rid";
        } elseif ($type == 'property') {
            $sql = "DELETE FROM tblpropertytax WHERE ID=:rid";
        } else {
            echo "<script>alert('Invalid type!');</script>";
            echo "<script>window.location.href = 'manageapplication.php'</script>";
            exit();
        }

        $query = $dbh->prepare($sql);
        $query->bindParam(':rid', $rid, PDO::PARAM_STR);
        $query->execute();
        echo "<script>alert('Data deleted');</script>"; 
        echo "<script>window.location.href = 'manageapplication.php'</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>OTM || Manage Applications</title>
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
                <div class="bg-light text-center rounded p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h4 class="mb-0">Manage All Applications</h4>
                    </div>
                    <div class="table-responsive">
                        <table class="table text-start align-middle table-bordered table-hover mb-0">
                            <thead>
                                <tr class="text-danger">
                                    <th scope="col">#</th>
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
                                $sql = "
                                    SELECT 'Income' AS type, ID, name, cnic, tax_year, status 
                                    FROM tblincometax
                                    UNION ALL
                                    SELECT 'Sales' AS type, ID, name, cnic, tax_year, status 
                                    FROM tblsalestax
                                    UNION ALL
                                    SELECT 'Property' AS type, ID, name, cnic, tax_year, status 
                                    FROM tblpropertytax
                                ";
                                $query = $dbh->prepare($sql);
                                $query->execute();
                                $results = $query->fetchAll(PDO::FETCH_OBJ);

                                $cnt = 1;
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
                                                <a class="btn btn-sm btn-primary" href="edit<?php echo strtolower($row->type); ?>tax.php?editid=<?php echo htmlentities($row->ID); ?>">Edit</a>
                                                <a class="btn btn-sm btn-danger" href="manageapplication.php?delid=<?php echo htmlentities($row->ID); ?>&type=<?php echo strtolower($row->type); ?>" onclick="return confirm('Do you really want to delete?');">Delete</a>
                                            </td>
                                        </tr>
                                        <?php 
                                        $cnt++;
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <?php include_once('includes/footer.php'); ?>
        </div>
        <?php include_once('includes/footer.php'); ?>

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
