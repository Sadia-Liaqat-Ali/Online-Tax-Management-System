<?php
session_start();
//error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['ocasuid']) == 0) {
    header('location:logout.php');
    exit();
} else {
    if (isset($_POST['submit'])) {

        $ocasuid = $_SESSION['ocasuid'];
        $cnic = $_POST['cnic'];
        $name = $_POST['name'];
        $sales_amount = $_POST['sales_amount'];
        $address = $_POST['address'];
        $contact = $_POST['contact'];
        $status = $_POST['status'];
        $tax_year = intval($_POST['tax_year']);  // New tax year field

        // Check if the user has already submitted an application for this tax year
        $checkQuery = "SELECT * FROM tblsalestax WHERE UserID = :ocasuid AND tax_year = :tax_year";
        $checkStmt = $dbh->prepare($checkQuery);
        $checkStmt->bindParam(':ocasuid', $ocasuid, PDO::PARAM_STR);
        $checkStmt->bindParam(':tax_year', $tax_year, PDO::PARAM_INT);
        $checkStmt->execute();

        if ($checkStmt->rowCount() > 0) {
            echo "<script>alert('You have already submitted an application for this tax year.');</script>";
                                echo "<script>window.location.href ='manageincometax.php'</script>";

            exit();
        }

        // File uploads
        $file1 = $_FILES["file1"]["name"];
        $extension1 = strtolower(substr($file1, strrpos($file1, '.')));

        $file2 = $_FILES["file2"]["name"];
        $extension2 = strtolower(substr($file2, strrpos($file2, '.')));

        $file3 = $_FILES["file3"]["name"];
        $extension3 = strtolower(substr($file3, strrpos($file3, '.')));

        // Define allowed extensions once
        $allowed_extensions = array(".pdf");

        // Validate file1 extension
        if (!in_array($extension1, $allowed_extensions)) {
            echo "<script>alert('File1 has invalid format. Only PDF format allowed.');</script>";
            exit();
        }

        // Validate file2 if uploaded
        if (!empty($file2) && !in_array($extension2, $allowed_extensions)) {
            echo "<script>alert('File2 has invalid format. Only PDF format allowed.');</script>";
            exit();
        }

        // Validate file3 if uploaded
        if (!empty($file3) && !in_array($extension3, $allowed_extensions)) {
            echo "<script>alert('File3 has invalid format. Only PDF format allowed.');</script>";
            exit();
        }

        // Generate unique file names
        $file1 = md5($file1) . time() . $extension1;
        $file2 = !empty($file2) ? md5($file2) . time() . $extension2 : NULL;
        $file3 = !empty($file3) ? md5($file3) . time() . $extension3 : NULL;

        // Move uploaded files
        if (!move_uploaded_file($_FILES["file1"]["tmp_name"], "folder1/" . $file1)) {
            echo "<script>alert('Failed to upload File1.');</script>";
            exit();
        }
        if ($file2 && !move_uploaded_file($_FILES["file2"]["tmp_name"], "folder2/" . $file2)) {
            echo "<script>alert('Failed to upload File2.');</script>";
            exit();
        }
        if ($file3 && !move_uploaded_file($_FILES["file3"]["tmp_name"], "folder3/" . $file3)) {
            echo "<script>alert('Failed to upload File3.');</script>";
            exit();
        }

        // Set tax ratio and calculate tax amount
        $tax_ratio = 0.18;
        $tax_amount = $sales_amount * $tax_ratio;

        // Prepare SQL without trailing commas
        $sql = "INSERT INTO tblsalestax(UserID, cnic, name, sales_amount, tax_amount, address, contact, status, tax_year, File1, File2, File3)
                VALUES (:ocasuid, :cnic, :name, :sales_amount, :tax_amount, :address, :contact, :status, :tax_year, :file1, :file2, :file3)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':ocasuid', $ocasuid, PDO::PARAM_STR);
        $query->bindParam(':cnic', $cnic, PDO::PARAM_STR);
        $query->bindParam(':name', $name, PDO::PARAM_STR);
        $query->bindParam(':sales_amount', $sales_amount, PDO::PARAM_STR);
        $query->bindParam(':tax_amount', $tax_amount, PDO::PARAM_STR);
        $query->bindParam(':address', $address, PDO::PARAM_STR);
        $query->bindParam(':contact', $contact, PDO::PARAM_STR);
        $query->bindParam(':file1', $file1, PDO::PARAM_STR);
        $query->bindParam(':file2', $file2, PDO::PARAM_STR);
        $query->bindParam(':file3', $file3, PDO::PARAM_STR);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->bindParam(':tax_year', $tax_year, PDO::PARAM_INT);  // Bind tax year

        // Execute the query
        try {
            $query->execute();
            $LastInsertId = $dbh->lastInsertId();
            if ($LastInsertId > 0) {
                echo '<script>alert("Sales Tax Application has been submitted successfully.");</script>';
                echo "<script>window.location.href ='manageincometax.php'</script>";
            } else {
                echo '<script>alert("Something went wrong. Please try again.");</script>';
            }
        } catch (PDOException $e) {
            // Log error or handle accordingly
            echo '<script>alert("An error occurred while saving the data.");</script>';
            // Optionally, log the error message: error_log($e->getMessage());
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>OTM || Add Sales Tax Applications</title>
    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
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
                            <h4 class="mb-4">Add Sales Tax Applications</h4>
                           <form method="post" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="cnic" class="form-label">Tax Payer CNIC</label>
        <input type="text" class="form-control" name="cnic" placeholder="Enter Tax Payer CNIC" required>
    </div>
    <div class="mb-3">
        <label for="name" class="form-label">Tax Payer Name</label>
        <input type="text" class="form-control" name="name" placeholder="Enter Tax Payer Name" required>
    </div>
    <div class="mb-3">
        <label for="address" class="form-label">Tax Payer Address</label>
        <input type="text" class="form-control" name="address" placeholder="Enter Tax Payer Address" required>
    </div>
    <div class="mb-3">
        <label for="contact" class="form-label">Contact No</label>
        <input type="text" class="form-control" name="contact" placeholder="Enter Contact Number" required>
    </div>

    <div class="mb-3">
        <label for="tax_year" class="form-label">Tax Year</label>
        <input type="number" class="form-control" name="tax_year" placeholder="Enter Tax Year" required>
    </div>
    <div class="mb-3">
        <label for="sales_amount" class="form-label">Total Sales Amount</label>
        <input type="text" class="form-control" name="sales_amount" placeholder="Enter Total Sales Amount" required>
    </div>

    <div class="mb-3">
        <label for="file1" class="form-label">Upload CNIC in PDF</label>
        <input type="file" class="form-control" name="file1" accept=".pdf" required>
    </div>
    <div class="mb-3">
        <label for="file2" class="form-label">Sales Invoice</label>
        <input type="file" class="form-control" name="file2" accept=".pdf">
    </div>
    <div class="mb-3">
        <label for="file3" class="form-label">Other Sales Document</label>
        <input type="file" class="form-control" name="file3" accept=".pdf">
    </div>
    <div class="mb-3">
        <label for="status" class="form-label">Status</label>
        <input type="text" class="form-control" name="status" value="Pending" readonly>
    </div>

    <button type="submit" name="submit" class="btn btn-primary">Submit Application</button>
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
    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>
</html>
