<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['ocasuid']) == 0) {
    header('location:logout.php');
} else {
    if (isset($_POST['submit'])) {
        $cnic = $_POST['cnic']; 
        $name = $_POST['name'];
        $sales_amount = $_POST['sales_amount'];
        $address = $_POST['address'];
        $contact = $_POST['contact'];
        $status = $_POST['status'];
       $tax_year = $_POST['tax_year']; // New field for tax year

        $eid = $_GET['editid'];

        // Current Sales Tax Rate in Pakistan
        $tax_rate = 0.18; // Update this as per current rate
        $tax_amount = $sales_amount * $tax_rate;

        $sql = "UPDATE tblsalestax SET name=:name, cnic=:cnic, sales_amount=:sales_amount, tax_amount=:tax_amount, address=:address, tax_year=:tax_year, contact=:contact WHERE ID=:eid";
        $query = $dbh->prepare($sql);

        // Bind parameters
        $query->bindParam(':cnic', $cnic, PDO::PARAM_STR);
        $query->bindParam(':name', $name, PDO::PARAM_STR);
        $query->bindParam(':sales_amount', $sales_amount, PDO::PARAM_STR);
        $query->bindParam(':tax_amount', $tax_amount, PDO::PARAM_STR);
        $query->bindParam(':address', $address, PDO::PARAM_STR);
                $query->bindParam(':tax_year', $tax_year, PDO::PARAM_STR); // Bind tax year parameter

        $query->bindParam(':contact', $contact, PDO::PARAM_STR);
        $query->bindParam(':eid', $eid, PDO::PARAM_STR);
        
        if ($query->execute()) {
            echo '<script>alert("Details have been updated")</script>';
            echo "<script>window.location.href ='manageincometax.php'</script>";
        } else {
            echo '<script>alert("Error updating details")</script>';
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>OTMS || Update Sales Tax Application</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
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
                            <h4 class="mb-4">Update Sales Tax  Application</h4>
                            <form method="post">
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
                                <br />
                                <div class="mb-3">
                                    <label for="exampleInputEmail2" class="form-label">Name</label>
                                    <input type="text" class="form-control" name="name" value="<?php echo htmlentities($row->name); ?>" required='true'>
                                </div>
                                <div class="mb-3">
                                    <label for="exampleInputEmail2" class="form-label">CNIC</label>
                                    <input type="text" class="form-control" name="cnic" value="<?php echo htmlentities($row->cnic); ?>" required='true'>
                                </div>
                                <div class="mb-3">
                                    <label for="exampleInputEmail2" class="form-label">Address</label>
                                    <input type="text" class="form-control" name="address" value="<?php echo htmlentities($row->address); ?>" required='true'>
                                </div>
                                <div class="mb-3">
                                    <label for="exampleInputEmail2" class="form-label">Contact</label>
                                    <input type="text" class="form-control" name="contact" value="<?php echo htmlentities($row->contact); ?>" required='true'>
                                </div>

                                 <div class="mb-3">
                                    <label for="exampleInputEmail2" class="form-label">Sales Amount</label>
                                    <input type="text" class="form-control" name="sales_amount" value="<?php echo htmlentities($row->sales_amount); ?>" required='true'>
                                </div>


                                 <div class="mb-3">
                                    <label for="tax_year" class="form-label">Tax Year</label>
                                    <input type="text" class="form-control" name="tax_year" value="<?php echo htmlentities($row->tax_year); ?>" required>
                                </div>
                                <?php }} ?>
                                <!-- File Views -->
                                <div class="mb-3">
                                    <label class="form-label">View CNIC</label>
                                    <a href="folder1/<?php echo $row->File1; ?>" target="_blank">
                                        <strong style="color: red">View</strong>
                                    </a> |
                                    <a href="changefile1_s.php?editid=<?php echo $row->ID; ?>">
                                        <strong style="color: red">Edit</strong>
                                    </a>
                                </div>

                                <?php if ($row->File2 == "") { ?>
                                    <div class="mb-3">
                                        <label class="form-label">View Sales Invoice</label>
                                        <strong style="color: red">File is not available</strong>
                                    </div>
                                <?php } else { ?>
                                    <div class="mb-3">
                                        <label class="form-label">View Sales Invoice</label>
                                        <a href="folder2/<?php echo $row->File2; ?>" target="_blank">
                                            <strong style="color: red">View</strong>
                                        </a> |
                                        <a href="changefile2_s.php?editid=<?php echo $row->ID; ?>">
                                            <strong style="color: red">Edit</strong>
                                        </a>
                                    </div>
                                <?php } ?>

                                <?php if ($row->File3 == "") { ?>
                                    <div class="mb-3">
                                        <label class="form-label">View Other SalesDocument</label>
                                        <strong style="color: red">File is not available</strong>
                                    </div>
                                <?php } else { ?>
                                    <div class="mb-3">
                                        <label class="form-label">View Other SalesDocument</label>
                                        <a href="folder3/<?php echo $row->File3; ?>" target="_blank">
                                            <strong style="color: red">View</strong>
                                        </a> |
                                        <a href="changefile3_s.php?editid=<?php echo $row->ID; ?>">
                                            <strong style="color: red">Edit</strong>
                                        </a>
                                    </div>
                                <?php } ?>

                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <input type="text" class="form-control" name="status" value="<?php echo htmlentities($row->status); ?>" required readonly>
                                </div>

                                <?php } ?>
                                <button type="submit" name="submit" class="btn btn-success">Update Application</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php include_once('includes/footer.php'); ?>
        </div>
        <?php include_once('includes/back-totop.php'); ?>
    </div>
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
