<?php 
session_start();
error_reporting(0);
include('includes/dbconnection.php');

// Check if admin is logged in
if (strlen($_SESSION['ocasaid']) == 0) {
    header('location:logout.php');
    exit;
}

// Handle form submission to update tax rates
if (isset($_POST['update'])) {
    try {
        // Begin a transaction to ensure data integrity
        $dbh->beginTransaction();

        // Loop through the posted data and update the database
        foreach ($_POST['tax_category'] as $id => $tax_category) {
            $slab_description = $_POST['slab_description'][$id];
            $rate = $_POST['rate'][$id] / 100; // Convert percentage back to decimal
            $fixed_amount = $_POST['fixed_amount'][$id];

            // Update the database
            $sql = "UPDATE tbltaxrates 
                    SET tax_category = :tax_category, 
                        slab_description = :slab_description, 
                        rate = :rate, 
                        fixed_amount = :fixed_amount 
                    WHERE id = :id";
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':tax_category', $tax_category);
            $stmt->bindParam(':slab_description', $slab_description);
            $stmt->bindParam(':rate', $rate);
            $stmt->bindParam(':fixed_amount', $fixed_amount);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
        }

        // Insert a notification for all users with type 'automated'
        $admin_id = $_SESSION['ocasaid'];
        $title = "Tax Rates Updated";
        $message = "The admin has updated the tax rates. Please review the latest tax rates.";
        $type = 'automated'; // Define the notification type

        $sql_alert = "INSERT INTO tblnotifications (title, message, recipient_id, admin_id, status, type) 
                      VALUES (:title, :message, NULL, :admin_id, 'active', :type)";
        $stmt_alert = $dbh->prepare($sql_alert);
        $stmt_alert->bindParam(':title', $title);
        $stmt_alert->bindParam(':message', $message);
        $stmt_alert->bindParam(':admin_id', $admin_id);
        $stmt_alert->bindParam(':type', $type); // Bind the type parameter
        $stmt_alert->execute();

        // Commit the transaction
        $dbh->commit();

        echo "<script>alert('Tax rates updated and an automated Alert is sent to all users');</script>";
        echo "<script>window.location.href='edit_rates.php';</script>";
    } catch (Exception $e) {
        // Rollback the transaction on error
        $dbh->rollBack();
        echo "<script>alert('Failed to update tax rates: " . htmlspecialchars($e->getMessage()) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>OTM || Edit Tax Rates</title>
    <!-- Stylesheets -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <style>
        .table-container {
            margin: 20px auto;
            max-width: 900px;
        }
        body {
            background-color: #8bf0ba;
        }
    </style>
</head>
<body>
    <div class="container-fluid position-relative bg-white d-flex p-0">
        <?php include_once('includes/sidebar.php'); ?>
        <div class="content">
            <?php include_once('includes/header.php'); ?>
            <div class="container-fluid pt-4 px-4">
                <div class="bg-light text-center rounded p-4">
                    <div class="d-flex align-items-center justify-content-center mb-4">
    <h2 class="text-center">Edit Tax Rates (<?php echo date("Y"); ?>)</h2>
</div>

                    <div class="table-responsive">
                        <form method="POST">
                            <div class="container table-container">
                                <table class="table table-bordered table-striped" style="background-color: lightblue;">
                                    <thead class="text-danger">
                                        <tr>
                                            <th>Tax Category</th>
                                            <th>Slab Description</th>
                                            <th>Rate (%)</th>
                                            <th>Fixed Amount (Rs)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        try {
                                            // Fetch current tax rates
                                            $sql = "SELECT * FROM tbltaxrates ORDER BY tax_category, id ASC";
                                            $stmt = $dbh->prepare($sql);
                                            $stmt->execute();
                                            $taxRates = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                            if ($stmt->rowCount() > 0) {
                                                foreach ($taxRates as $row) {
                                                    $rate_percentage = $row['rate'] * 100;
                                                    $fixed_amount_value = $row['fixed_amount'];

                                                    echo "<tr>
                                    <td>
                                    <input type='text' name='tax_category[{$row['id']}]' 
                                    value='" . htmlspecialchars($row['tax_category'], ENT_QUOTES, 'UTF-8') . "' 
                                    class='form-control' required>
                                     </td>
                                     <td>
                                    <input type='text' name='slab_description[{$row['id']}]' 
                                     value='" . htmlspecialchars($row['slab_description'], ENT_QUOTES, 'UTF-8') . "' 
                                     class='form-control' required>
                                      </td>
                                     <td>
                                     <input type='number' step='0.01' name='rate[{$row['id']}]' 
                                     value='" . htmlspecialchars($rate_percentage, ENT_QUOTES, 'UTF-8') . "' 
                                     class='form-control' required>
                                      </td>
                                    <td>
                                    <input type='number' step='0.01' name='fixed_amount[{$row['id']}]' 
                                    value='" . htmlspecialchars($fixed_amount_value, ENT_QUOTES, 'UTF-8') . "' 
                                     class='form-control' required>
                                     </td>
                                      </tr>";
                                                }
                                     } else {
                                     echo "<tr><td colspan='4' class='text-center'>No tax rates found</td></tr>";
                                            }
                                    } catch (PDOException $e) {
                                    echo "<tr><td colspan='4' class='text-center text-danger'>Error fetching tax rates: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</td></tr>";
                                    }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-center">
                                <button type="submit" name="update" class="btn btn-success">Update Tax Rates</button>
                            </div>
                        </form>
                    </div>
                </div>
             </div>

            <?php include_once('includes/footer.php'); ?>
        </div>

        <?php include_once('includes/back-totop.php'); ?>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/chart/chart.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>
