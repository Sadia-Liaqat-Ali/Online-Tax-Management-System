<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['ocasuid']) == 0) {
    header('location:logout.php');
} else {
    if (isset($_POST['submit'])) {

        // Retrieve form data
        $cnic = $_POST['cnic'];
        $name = $_POST['name'];
        $income = $_POST['income'];
        $address = $_POST['address'];
        $contact = $_POST['contact'];
        $status = $_POST['status'];
        $tax_year = $_POST['tax_year'];  // Get tax year from the form
        $eid = $_GET['editid'];

        /**
         * Function to calculate tax based on income
         *
         * @param float $income
         * @return float 
         */
        function calculateTax($income) {
            $tax = 0;

            // Tax slabs as per FBR guidelines
            if ($income <= 600000) {
                $tax = 0; // No tax
            } elseif ($income <= 1200000) { // Implicitly $income > 600000
                $tax = ($income - 600000) * 0.025; // 2.5% on exceeding amount
            } elseif ($income <= 2400000) { // Implicitly $income > 1200000
                $tax = 15000 + ($income - 1200000) * 0.125; // Rs 15,000 + 12.5% on exceeding amount
            } elseif ($income <= 3600000) { // Implicitly $income > 2400000
                $tax = 165000 + ($income - 2400000) * 0.225; // Rs 165,000 + 22.5% on exceeding amount
            } elseif ($income <= 6000000) { // Implicitly $income > 3600000
                $tax = 435000 + ($income - 3600000) * 0.275; // Rs 435,000 + 27.5% on exceeding amount
            } else { // Implicitly $income > 6000000
                $tax = 1095000 + ($income - 6000000) * 0.35; // Rs 1,095,000 + 35% on exceeding amount
            }

            return $tax;
        }

        // Calculate tax amount based on income
        $tax_amount = calculateTax($income);

        // Prepare SQL statement to update the record
        $sql = "UPDATE tblincometax 
                SET name = :name, 
                    cnic = :cnic, 
                    income = :income, 
                    tax_amount = :tax_amount, 
                    address = :address, 
                    contact = :contact, 
                    status = :status, 
                    tax_year = :tax_year 
                WHERE ID = :eid";

        $query = $dbh->prepare($sql);

        // Bind parameters
        $query->bindParam(':name', $name, PDO::PARAM_STR);
        $query->bindParam(':cnic', $cnic, PDO::PARAM_STR);
        $query->bindParam(':income', $income, PDO::PARAM_STR);
        $query->bindParam(':tax_amount', $tax_amount, PDO::PARAM_STR);
        $query->bindParam(':address', $address, PDO::PARAM_STR);
        $query->bindParam(':contact', $contact, PDO::PARAM_STR);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->bindParam(':tax_year', $tax_year, PDO::PARAM_STR);  // Bind tax_year
        $query->bindParam(':eid', $eid, PDO::PARAM_STR);

        // Execute the query
        $query->execute();

        // Redirect with success or error message
        if ($query->rowCount() > 0) {
            echo '<script>alert("income Tax Application have been updated successfully.")</script>';
            echo "<script>window.location.href ='manageincometax.php'</script>";
        } else {
            echo '<script>alert("No changes were made or something went wrong. Please try again.")</script>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>OTMS || Update Application</title>
  
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
                            <h4 class="mb-4">Update Income Tax  Application</h4>
                            <form method="post">
                                <?php 
                                $eid = $_GET['editid'];
                                $sql = "SELECT * FROM tblincometax WHERE ID = :eid";
                                $query = $dbh->prepare($sql);
                                $query->bindParam(':eid', $eid, PDO::PARAM_STR);
                                $query->execute();
                                $results = $query->fetchAll(PDO::FETCH_OBJ);

                                if ($query->rowCount() > 0) {
                                    foreach ($results as $row) {
                                ?>
                                <br />
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control" name="name" value="<?php echo htmlentities($row->name); ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="cnic" class="form-label">CNIC</label>
                                    <input type="text" class="form-control" name="cnic" value="<?php echo htmlentities($row->cnic); ?>" required>
                                </div>
                              
                               
                                <div class="mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <input type="text" class="form-control" name="address" value="<?php echo htmlentities($row->address); ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="contact" class="form-label">Contact</label>
                                    <input type="text" class="form-control" name="contact" value="<?php echo htmlentities($row->contact); ?>" required>
                                </div>

                                 <div class="mb-3">
                                    <label for="tax_year" class="form-label">Tax Year</label>
                                    <input type="text" class="form-control" name="tax_year" value="<?php echo htmlentities($row->tax_year); ?>" required>
                                </div>

                                 <div class="mb-3">
                                    <label for="income" class="form-label">Income</label>
                                    <input type="number" class="form-control" name="income" value="<?php echo htmlentities($row->income); ?>" required min="0">
                                </div>



                                <!-- File Views -->
                                <div class="mb-3">
                                    <label class="form-label">View CNIC</label>
                                    <a href="folder1/<?php echo $row->File1; ?>" target="_blank">
                                        <strong style="color: red">View</strong>
                                    </a> |
                                    <a href="changefile1.php?editid=<?php echo $row->ID; ?>">
                                        <strong style="color: red">Edit</strong>
                                    </a>
                                </div>

                                <?php if ($row->File2 == "") { ?>
                                    <div class="mb-3">
                                        <label class="form-label">View Salary Slip</label>
                                        <strong style="color: red">File is not available</strong>
                                    </div>
                                <?php } else { ?>
                                    <div class="mb-3">
                                        <label class="form-label">View Salary Slip</label>
                                        <a href="folder2/<?php echo $row->File2; ?>" target="_blank">
                                            <strong style="color: red">View</strong>
                                        </a> |
                                        <a href="changefile2.php?editid=<?php echo $row->ID; ?>">
                                            <strong style="color: red">Edit</strong>
                                        </a>
                                    </div>
                                <?php } ?>

                                <?php if ($row->File3 == "") { ?>
                                    <div class="mb-3">
                                        <label class="form-label">View Electricity Bill</label>
                                        <strong style="color: red">File is not available</strong>
                                    </div>
                                <?php } else { ?>
                                    <div class="mb-3">
                                        <label class="form-label">View Electricity Bill</label>
                                        <a href="folder3/<?php echo $row->File3; ?>" target="_blank">
                                            <strong style="color: red">View</strong>
                                        </a> |
                                        <a href="changefile3.php?editid=<?php echo $row->ID; ?>">
                                            <strong style="color: red">Edit</strong>
                                        </a>
                                    </div>
                                <?php } ?>

                                <?php if ($row->File4 == "") { ?>
                                    <div class="mb-3">
                                        <label class="form-label">View Gas Bill</label>
                                        <strong style="color: red">File is not available</strong>
                                    </div>
                                <?php } else { ?>
                                    <div class="mb-3">
                                        <label class="form-label">View Gas Bill</label>
                                        <a href="folder4/<?php echo $row->File4; ?>" target="_blank">
                                            <strong style="color: red">View</strong>
                                        </a> |
                                        <a href="changefile4.php?editid=<?php echo $row->ID; ?>">
                                            <strong style="color: red">Edit</strong>
                                        </a>
                                    </div>
                                <?php } ?>

                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <input type="text" class="form-control" name="status" value="<?php echo htmlentities($row->status); ?>" required readonly>
                                </div>

                                <?php } } ?>
                                <button type="submit" name="submit" class="btn btn-success">Update Application</button>
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
<?php ?>
