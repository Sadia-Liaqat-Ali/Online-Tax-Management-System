<?php
session_start();
error_reporting(E_ALL); // Show all errors for debugging
include('includes/dbconnection.php');

// Check if the admin is logged in
if (strlen($_SESSION['ocasaid'] == 0)) {
    header('location:logout.php');
    exit;
}

// Fetch report details based on ReportID from the URL
if (isset($_GET['ReportID']) && is_numeric($_GET['ReportID'])) {  // Use ReportID as the parameter key
    $reportid = intval($_GET['ReportID']); // Ensure reportid is an integer

    // Query to fetch the report based on ReportID
    $sql = "SELECT * FROM tbl_reports WHERE ReportID = :reportid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':reportid', $reportid, PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        // If no result found, display an error and redirect
        echo "<script>alert('No report found with this ID'); window.location.href='dashboard.php';</script>";
        exit();
    }

    // Store report details in variables
    $tax_year = $result['tax_year'];
    $income_amount = $result['income_amount'];
    $sales_amount = $result['sales_amount'];
    $property_value = $result['property_value'];
    $total_taxable_amount = $result['total_taxable_amount']; // This is auto-calculated
    $total_tax_amount = $result['total_tax_amount'];
    $user_id = $result['UserID'];
} else {
    // If ReportID is not provided or is invalid, redirect to dashboard
    echo "<script>alert('Invalid or missing Report ID'); window.location.href='dashboard.php';</script>";
    exit();
}

// Handle the form submission for updating the report
if (isset($_POST['submit'])) {
    // Get form inputs
    $tax_year = $_POST['tax_year'];
    $income_amount = $_POST['income_amount'];
    $sales_amount = $_POST['sales_amount'];
    $property_value = $_POST['property_value'];
    $total_taxable_amount = $income_amount + $sales_amount + $property_value;  // Auto-calculated
    $total_tax_amount = $_POST['total_tax_amount'];

    // Update the report in the database
    $sql = "UPDATE tbl_reports SET 
                tax_year = :tax_year, 
                income_amount = :income_amount, 
                sales_amount = :sales_amount,
                property_value = :property_value, 
                total_taxable_amount = :total_taxable_amount, 
                total_tax_amount = :total_tax_amount
            WHERE ReportID = :reportid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':tax_year', $tax_year, PDO::PARAM_STR);
    $query->bindParam(':income_amount', $income_amount, PDO::PARAM_STR);
    $query->bindParam(':sales_amount', $sales_amount, PDO::PARAM_STR);
    $query->bindParam(':property_value', $property_value, PDO::PARAM_STR);
    $query->bindParam(':total_taxable_amount', $total_taxable_amount, PDO::PARAM_STR);
    $query->bindParam(':total_tax_amount', $total_tax_amount, PDO::PARAM_STR);
    $query->bindParam(':reportid', $reportid, PDO::PARAM_INT);
    $query->execute();

    if ($query->rowCount() > 0) {
        // If updated successfully, redirect back to the dashboard
        echo "<script>alert('Report updated successfully'); window.location.href = 'dashboard.php';</script>";
    } else {
        echo "<script>alert('Error updating report');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>OTMS || Edit Report</title>
 <!-- Include CSS & JS -->
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet"></head>
<body>
    <div class="container-fluid position-relative bg-white d-flex p-0">
        <?php include_once('includes/sidebar.php'); ?>
        <div class="content">
            <?php include_once('includes/header.php'); ?>
            <div class="container-fluid pt-4 px-4">
                <div class="row g-4">
                    <div class="col-sm-12 col-xl-6">
                        <div class="bg-light rounded h-100 p-4">
                            <h5 class="mb-4 text-danger">Edit Report (Tax Year: <?php echo $tax_year; ?>)</h5>
                            <form method="post">
                                <div class="form-group mb-3">
                                    <label for="tax_year">Tax Year</label>
                                    <input type="text" class="form-control" name="tax_year" value="<?php echo htmlentities($tax_year); ?>" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="income_amount">Income Amount</label>
                                    <input type="number" class="form-control" name="income_amount" value="<?php echo htmlentities($income_amount); ?>" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="sales_amount">Sales Amount</label>
                                    <input type="number" class="form-control" name="sales_amount" value="<?php echo htmlentities($sales_amount); ?>" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="property_value">Property Value</label>
                                    <input type="number" class="form-control" name="property_value" value="<?php echo htmlentities($property_value); ?>" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="total_tax_amount">Total Tax Amount</label>
                                    <input type="number" class="form-control" name="total_tax_amount" value="<?php echo htmlentities($total_tax_amount); ?>" required>
                                </div>
                                <button type="submit" name="submit" class="btn btn-success">Update Report</button>
                            </form>
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
