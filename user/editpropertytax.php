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
        $market_value = $_POST['market_value'];
        $address = $_POST['address'];
        $status = $_POST['status'];
        $contact = $_POST['contact']; // New field for contact
        $tax_year = $_POST['tax_year']; // New field for tax year
        $property_type = $_POST['property_type']; // New field for property type
        $eid = $_GET['editid'];

        // Tax calculation based on market value
        if ($market_value <= 50000000) {
            $tax_rate = 0.03; // 3% for properties up to Rs50 million
        } elseif ($market_value <= 100000000) {
            $tax_rate = 0.035; // 3.5% for properties between Rs50 million and Rs100 million
        } else {
            $tax_rate = 0.04; // 4% for properties above Rs100 million
        }
        $tax_amount = $market_value * $tax_rate;

        // Prepare SQL statement to update the record
        $sql = "UPDATE tblpropertytax 
                SET name = :name, 
                    cnic = :cnic, 
                    market_value = :market_value, 
                    address = :address, 
                    status = :status,
                    contact = :contact, 
                    tax_year = :tax_year,
                    property_type = :property_type,
                    tax_amount = :tax_amount
                WHERE ID = :eid";

        $query = $dbh->prepare($sql);

        // Bind parameters
        $query->bindParam(':name', $name, PDO::PARAM_STR);
        $query->bindParam(':cnic', $cnic, PDO::PARAM_STR);
        $query->bindParam(':market_value', $market_value, PDO::PARAM_STR);
        $query->bindParam(':address', $address, PDO::PARAM_STR);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->bindParam(':contact', $contact, PDO::PARAM_STR); // Bind contact parameter
        $query->bindParam(':tax_year', $tax_year, PDO::PARAM_STR); // Bind tax year parameter
        $query->bindParam(':property_type', $property_type, PDO::PARAM_STR); // Bind property type parameter
        $query->bindParam(':tax_amount', $tax_amount, PDO::PARAM_STR); // Bind tax amount
        $query->bindParam(':eid', $eid, PDO::PARAM_STR);

        // Execute the query
        $query->execute();

        // Redirect with success or error message
        if ($query->rowCount() > 0) {
            echo '<script>alert("Details have been updated successfully.")</script>';
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
    <title>OTMS || Update Property Tax Application</title>
    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
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
                            <h4 class="mb-4">Update Property Tax  Application</h4>
                            <form method="post">
                                <?php
                                $eid = $_GET['editid'];
                                $sql = "SELECT * FROM tblpropertytax WHERE ID = :eid";
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
    <label for="property_type" class="form-label">Property Type</label>
    <select class="form-select" name="property_type" required>
        <option value="">Select Property Type</option>
        <option value="Residential" <?php echo ($row->property_type == 'Residential') ? 'selected' : ''; ?>>Residential</option>
        <option value="Commercial" <?php echo ($row->property_type == 'Commercial') ? 'selected' : ''; ?>>Commercial</option>
        <option value="Agricultural" <?php echo ($row->property_type == 'Agricultural') ? 'selected' : ''; ?>>Agricultural</option>
    </select>
</div>

                                <div class="mb-3">
                                    <label for="market_value" class="form-label">Market Value</label>
                                    <input type="number" class="form-control" name="market_value" value="<?php echo htmlentities($row->market_value); ?>" required min="0">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">View Property Documents</label>
                                </div>

                                <!-- File 1 -->
                                <div class="mb-3">
                                    <label class="form-label">View CNIC (PDF)</label>
                                    <a href="folder1/<?php echo $row->File1; ?>" target="_blank">
                                        <strong style="color: red">View</strong>
                                    </a> |
                                    <a href="changefile1_p.php?editid=<?php echo $row->ID; ?>">
                                        <strong style="color: red">Edit</strong>
                                    </a>
                                </div>

                                <!-- File 2 -->
                                <?php if ($row->File2 == "") { ?>
                                    <div class="mb-3">
                                        <label class="form-label">View Property Ownership Document (PDF)</label>
                                        <strong style="color: red">File is not available</strong>
                                    </div>
                                <?php } else { ?>
                                    <div class="mb-3">
                                        <label class="form-label">View Property Ownership Document</label>
                                        <a href="folder2/<?php echo $row->File2; ?>" target="_blank">
                                            <strong style="color: red">View</strong>
                                        </a> |
                                        <a href="changefile2_p.php?editid=<?php echo $row->ID; ?>">
                                            <strong style="color: red">Edit</strong>
                                        </a>
                                    </div>
                                <?php } ?>

                                <!-- File 3 -->
                                <?php if ($row->File3 == "") { ?>
                                    <div class="mb-3">
                                        <label class="form-label">View Property Tax Receipt (PDF)</label>
                                        <strong style="color: red">File is not available</strong>
                                    </div>
                                <?php } else { ?>
                                    <div class="mb-3">
                                        <label class="form-label">View Property Tax Receipt</label>
                                        <a href="folder3/<?php echo $row->File3; ?>" target="_blank">
                                            <strong style="color: red">View</strong>
                                        </a> |
                                        <a href="changefile3_p.php?editid=<?php echo $row->ID; ?>">
                                            <strong style="color: red">Edit</strong>
                                        </a>
                                    </div>
                                <?php } ?>

                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <input type="text" class="form-control" name="status" value="<?php echo htmlentities($row->status); ?>" required readonly>
                                </div>

                                <button type="submit" name="submit" class="btn btn-primary">Update</button>
                                <?php }} ?>
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
    <script src="js/main.js"></script><!-- Content End -->

    </div>
</body>
</html>
