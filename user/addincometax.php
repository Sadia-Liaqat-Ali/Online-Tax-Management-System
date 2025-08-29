<?php
session_start();
//error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['ocasuid'] == 0)) {
    header('location:logout.php');
} else { 
    if (isset($_POST['submit'])) {

        $ocasuid = $_SESSION['ocasuid'];
        $cnic = $_POST['cnic'];
        $name = $_POST['name'];
        $income = $_POST['income'];
        $address = $_POST['address'];
        $contact = $_POST['contact'];
        $status = $_POST['status'];
        $tax_year = $_POST['tax_year'];  // Added tax_year to get input from form

        // Check if the user has already submitted tax information for the given year
        $checkQuery = "SELECT * FROM tblincometax WHERE UserID = :ocasuid AND tax_year = :tax_year";
        $checkStmt = $dbh->prepare($checkQuery);
        $checkStmt->bindParam(':ocasuid', $ocasuid, PDO::PARAM_STR);
        $checkStmt->bindParam(':tax_year', $tax_year, PDO::PARAM_STR);
        $checkStmt->execute();
        $existingRecord = $checkStmt->fetch(PDO::FETCH_ASSOC);

        if ($existingRecord) {
            // If a record exists, show an error message
            echo '<script>alert("You have already submitted tax information for this year.")</script>';
            echo "<script>window.location.href ='manageincometax.php'</script>";

        } else {
            // Proceed with file uploads and insertion if no existing record is found
            $file1 = $_FILES["file1"]["name"];
            $extension1 = substr($file1, strlen($file1) - 4, strlen($file1));
            $file2 = $_FILES["file2"]["name"];
            $extension2 = substr($file2, strlen($file2) - 4, strlen($file2));
            $file3 = $_FILES["file3"]["name"];
            $extension3 = substr($file3, strlen($file3) - 4, strlen($file3));
            $file4 = $_FILES["file4"]["name"];
            $extension4 = substr($file4, strlen($file4) - 4, strlen($file4));
            $allowed_extensions = array(".pdf");

            if (!in_array($extension1, $allowed_extensions)) {
                echo "<script>alert('File has Invalid format. Only PDF format allowed');</script>";
            } else {
                // Process the file uploads
                $file1 = md5($file1) . time() . $extension1;
                if ($file2 != '') :
                    $file2 = md5($file2) . time() . $extension2;
                endif;
                if ($file3 != '') :
                    $file3 = md5($file3) . time() . $extension3;
                endif;
                if ($file4 != '') :
                    $file4 = md5($file4) . time() . $extension4;
                endif;
                move_uploaded_file($_FILES["file1"]["tmp_name"], "folder1/" . $file1);
                if ($file2 != '') {
                    move_uploaded_file($_FILES["file2"]["tmp_name"], "folder2/" . $file2);
                }
                if ($file3 != '') {
                    move_uploaded_file($_FILES["file3"]["tmp_name"], "folder3/" . $file3);
                }
                if ($file4 != '') {
                    move_uploaded_file($_FILES["file4"]["tmp_name"], "folder4/" . $file4);
                }

                // Updated Tax calculation logic based on specified slabs
                if ($income <= 600000) {
                    $tax = 0; // No tax
                } elseif ($income > 600000 && $income <= 1200000) {
                    $tax = ($income - 600000) * 0.025; // 2.5% on exceeding amount
                } elseif ($income > 1200000 && $income <= 2400000) {
                    $tax = 15000 + ($income - 1200000) * 0.125; // Rs 15,000 + 12.5% on exceeding amount
                } elseif ($income > 2400000 && $income <= 3600000) {
                    $tax = 165000 + ($income - 2400000) * 0.225; // Rs 165,000 + 22.5% on exceeding amount
                } elseif ($income > 3600000 && $income <= 6000000) {
                    $tax = 435000 + ($income - 3600000) * 0.275; // Rs 435,000 + 27.5% on exceeding amount
                } else {
                    $tax = 1095000 + ($income - 6000000) * 0.35; // Rs 1,095,000 + 35% on exceeding amount
                }

                $tax_amount = $tax;

                // Insert data into the database including the tax_year
                $sql = "INSERT INTO tblincometax(UserID, cnic, name, income, tax_amount, address, contact, status, File1, File2, File3, File4, tax_year) 
                        VALUES (:ocasuid, :cnic, :name, :income, :tax_amount, :address, :contact, :status, :file1, :file2, :file3, :file4, :tax_year)";
                $query = $dbh->prepare($sql);
                $query->bindParam(':ocasuid', $ocasuid, PDO::PARAM_STR);
                $query->bindParam(':cnic', $cnic, PDO::PARAM_STR);
                $query->bindParam(':name', $name, PDO::PARAM_STR);
                $query->bindParam(':income', $income, PDO::PARAM_STR);
                $query->bindParam(':tax_amount', $tax_amount, PDO::PARAM_STR);
                $query->bindParam(':address', $address, PDO::PARAM_STR);
                $query->bindParam(':contact', $contact, PDO::PARAM_STR);
                $query->bindParam(':file1', $file1, PDO::PARAM_STR);
                $query->bindParam(':file2', $file2, PDO::PARAM_STR);
                $query->bindParam(':file3', $file3, PDO::PARAM_STR);
                $query->bindParam(':file4', $file4, PDO::PARAM_STR);
                $query->bindParam(':status', $status, PDO::PARAM_STR);
                $query->bindParam(':tax_year', $tax_year, PDO::PARAM_STR);  // Bind the tax_year field

                $query->execute();

                $LastInsertId = $dbh->lastInsertId();
                if ($LastInsertId > 0) {
                    echo '<script>alert("Income Tax Application have been Submitted successfully.")</script>';
                    echo "<script>window.location.href ='manageincometax.php'</script>";
                } else {
                    echo '<script>alert("Something Went Wrong. Please try again.")</script>';
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>OTM || Add Income Tax Details</title>

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
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script>
        function getSubject(val) { 
            //alert(val);
            $.ajax({
                type: "POST",
                url: "get-subject.php",
                data: 'subid=' + val,
                success: function(data){
                    $("#subject").html(data);
                }
            });
        }
    </script>
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
                            <h4 class="mb-4">Add Income Tax Application</h4>
                            <form method="post" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="cnic" class="form-label">Tax Payer CNIC</label>
        <input type="text" class="form-control" name="cnic" required='true' placeholder="Enter CNIC">
    </div>
    <div class="mb-3">
        <label for="name" class="form-label">Tax Payer Name</label>
        <input type="text" class="form-control" name="name" required='true' placeholder="Enter Full Name">
    </div>
    
    <div class="mb-3">
        <label for="address" class="form-label">Tax Payer Address</label>
        <input type="text" class="form-control" name="address" required='true' placeholder="Enter Full Address">
    </div>

    <div class="mb-3">
        <label for="contact" class="form-label">Contact No</label>
        <input type="text" class="form-control" name="contact" required='true' placeholder="Enter Contact Number">
    </div>

    <div class="mb-3">
        <label for="tax_year" class="form-label">Tax Year</label>
        <input type="number" class="form-control" name="tax_year" required='true' min="1900" max="2100" placeholder="Enter Tax Year">
    </div>

    <div class="mb-3">
        <label for="income" class="form-label">Tax Payer Yearly Income</label>
        <input type="number" class="form-control" name="income" required='true' min="0" placeholder="Enter Yearly Income">
    </div>

    <div class="mb-3">
        <label for="file1" class="form-label">Upload CNIC in PDF</label>
        <input type="file" class="form-control" name="file1" accept=".pdf" required='true' placeholder="Upload CNIC Document">
    </div>
    <div class="mb-3">
        <label for="file2" class="form-label">Salary Slip</label>
        <input type="file" class="form-control" name="file2" accept=".pdf" placeholder="Upload Salary Slip">
    </div>
    <div class="mb-3">
        <label for="file3" class="form-label">Electricity Bill</label>
        <input type="file" class="form-control" name="file3" accept=".pdf" placeholder="Upload Electricity Bill">
    </div>
    <div class="mb-3">
        <label for="file4" class="form-label">Gas Bill</label>
        <input type="file" class="form-control" name="file4" accept=".pdf" placeholder="Upload Gas Bill">
    </div>
    <div class="mb-3">
        <label for="status" class="form-label">Status</label>
        <input type="text" class="form-control" name="status" value="Pending" readonly placeholder="Status">
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
