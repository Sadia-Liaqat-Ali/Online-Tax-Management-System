
    <?php
session_start();
include('includes/dbconnection.php');
if (strlen($_SESSION['ocasuid']) == 0) {
    header('location:logout.php');
} else {
    if (isset($_POST['submit'])) {

        $ocasuid = $_SESSION['ocasuid'];
        $cnic = $_POST['cnic'];
        $name = $_POST['name'];
        $contact = $_POST['contact']; // New contact field
        $property_type = $_POST['property_type'];
        $market_value = $_POST['market_value'];
        $address = $_POST['address'];
        $status = $_POST['status'];
        $tax_year = $_POST['tax_year'];  // New field for tax year

        // Check if the user already has a property tax application for the same tax year
        $sql_check = "SELECT * FROM tblpropertytax WHERE UserID = :ocasuid AND tax_year = :tax_year";
        $query_check = $dbh->prepare($sql_check);
        $query_check->bindParam(':ocasuid', $ocasuid, PDO::PARAM_STR);
        $query_check->bindParam(':tax_year', $tax_year, PDO::PARAM_STR);
        $query_check->execute();

        if ($query_check->rowCount() > 0) {
            // If record exists, show error message
            echo "<script>alert('You have already submitted an application for this tax year.');</script>";
                                echo "<script>window.location.href ='manageincometax.php'</script>";

        } else {
            // Proceed with the form submission if no existing record
            $file1 = $_FILES["file1"]["name"];
            $file2 = $_FILES["file2"]["name"];
            $file3 = $_FILES["file3"]["name"];

            $extension1 = substr($file1, strlen($file1) - 4, strlen($file1));
            $extension2 = substr($file2, strlen($file2) - 4, strlen($file2));
            $extension3 = substr($file3, strlen($file3) - 4, strlen($file3));

            $allowed_extensions = array(".pdf");

            if (!in_array($extension1, $allowed_extensions) || !in_array($extension2, $allowed_extensions) || !in_array($extension3, $allowed_extensions)) {
                echo "<script>alert('File has Invalid format. Only pdf format allowed');</script>";
            } else {

                $file1 = md5($file1) . time() . $extension1;
                $file2 = md5($file2) . time() . $extension2;
                $file3 = md5($file3) . time() . $extension3;

                move_uploaded_file($_FILES["file1"]["tmp_name"], "folder1/" . $file1);
                move_uploaded_file($_FILES["file2"]["tmp_name"], "folder2/" . $file2);
                move_uploaded_file($_FILES["file3"]["tmp_name"], "folder3/" . $file3);

                if ($market_value <= 50000000) {
                    $tax_rate = 0.03;
                } elseif ($market_value <= 100000000) {
                    $tax_rate = 0.035;
                } else {
                    $tax_rate = 0.04;
                }

                $tax_amount = $market_value * $tax_rate;

                $sql = "INSERT INTO tblpropertytax(UserID, cnic, name, contact, property_type, market_value, tax_amount, address, status, File1, File2, File3, tax_year)
                        VALUES (:ocasuid, :cnic, :name, :contact, :property_type, :market_value, :tax_amount, :address, :status, :file1, :file2, :file3, :tax_year)";
                $query = $dbh->prepare($sql);
                $query->bindParam(':ocasuid', $ocasuid, PDO::PARAM_STR);
                $query->bindParam(':cnic', $cnic, PDO::PARAM_STR);
                $query->bindParam(':name', $name, PDO::PARAM_STR);
                $query->bindParam(':contact', $contact, PDO::PARAM_STR); // Bind the contact
                $query->bindParam(':property_type', $property_type, PDO::PARAM_STR);
                $query->bindParam(':market_value', $market_value, PDO::PARAM_STR);
                $query->bindParam(':tax_amount', $tax_amount, PDO::PARAM_STR);
                $query->bindParam(':address', $address, PDO::PARAM_STR);
                $query->bindParam(':file1', $file1, PDO::PARAM_STR);
                $query->bindParam(':file2', $file2, PDO::PARAM_STR);
                $query->bindParam(':file3', $file3, PDO::PARAM_STR);
                $query->bindParam(':status', $status, PDO::PARAM_STR);
                $query->bindParam(':tax_year', $tax_year, PDO::PARAM_STR); // Bind the tax year

                $query->execute();

                $LastInsertId = $dbh->lastInsertId();
                if ($LastInsertId > 0) {
                    echo '<script>alert("Property Tax Application have been Submitted successfully.")</script>';
                    echo "<script>window.location.href ='manageincometax.php'</script>";
                } else {
                    echo '<script>alert("Something Went Wrong. Please try again")</script>';
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>OTM || Add Property Tax Applications</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    <div class="container-fluid position-relative bg-white d-flex p-0">
        <?php include_once('includes/sidebar.php');?>
        <div class="content">
            <?php include_once('includes/header.php');?>
            <div class="container-fluid pt-4 px-4">
                <div class="row g-4">
                    <div class="col-sm-12 col-xl-6">
                        <div class="bg-light rounded h-100 p-4">
                            <h4 class="mb-4">Add Property Tax Applications</h4>
                           <form method="post" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="cnic" class="form-label">Tax Payer CNIC</label>
        <input type="text" class="form-control" name="cnic" placeholder="Enter Tax Payer CNIC" required='true'>
    </div>
    <div class="mb-3">
        <label for="name" class="form-label">Tax Payer Name</label>
        <input type="text" class="form-control" name="name" placeholder="Enter Tax Payer Name" required='true'>
    </div>
    <div class="mb-3">
        <label for="contact" class="form-label">Contact</label>
        <input type="text" class="form-control" name="contact" placeholder="Enter Contact Number" required='true'>
    </div>
    <div class="mb-3">
        <label for="address" class="form-label">Tax Payer Address</label>
        <input type="text" class="form-control" name="address" placeholder="Enter Tax Payer Address" required='true'>
    </div>
    <div class="mb-3">
        <label for="tax_year" class="form-label">Tax Year</label>
        <input type="number" class="form-control" name="tax_year" placeholder="Enter Tax Year" required='true'>
    </div>
    <div class="mb-3">
        <label for="property_type" class="form-label">Property Type</label>
        <select class="form-select" name="property_type" required>
            <option value="">Select Property Type</option>
            <option value="Residential">Residential</option>
            <option value="Commercial">Commercial</option>
            <option value="Agricultural">Agricultural</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="market_value" class="form-label">Market Value</label>
        <input type="number" class="form-control" name="market_value" placeholder="Enter Market Value" required='true'>
    </div>
    <div class="mb-3">
        <label for="file1" class="form-label">Upload CNIC (PDF)</label>
        <input type="file" class="form-control" name="file1" required='true'>
    </div>
    <div class="mb-3">
        <label for="file2" class="form-label">Upload Property Ownership Document (PDF)</label>
        <input type="file" class="form-control" name="file2" required='true'>
    </div>
    <div class="mb-3">
        <label for="file3" class="form-label">Upload Property Valuation Document (PDF)</label>
        <input type="file" class="form-control" name="file3">
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
            <?php include_once('includes/footer.php');?>
        </div>
        <?php include_once('includes/back-totop.php');?>
    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>
