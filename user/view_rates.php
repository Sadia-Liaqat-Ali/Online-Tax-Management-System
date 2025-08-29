<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['ocasuid']) == 0) {
    header('location:logout.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>OTM || View Rates</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <style>
        .card.bg-primary { background-color: #ff5678 !important; }
        .card.bg-success { background-color: #28a745 !important; }
        .card.bg-info { background-color: #17a2b8 !important; }
        .rate-text { color: red; font-weight: bold; font-size: 1em;
; }
    </style>
</head>
<body>
    <div class="container-fluid position-relative bg-white d-flex p-0">
        <?php include_once('includes/sidebar.php'); ?>
        <div class="content">
            <?php include_once('includes/header.php'); ?>
            <div class="container-fluid pt-4 px-4">
                <h2 class="text-center text-danger mb-4">View Current Tax Rates (<?php echo date("Y"); ?>)</h2>
                <div class="row">
                    <!-- Income Tax Rates -->
                    <div class="col-md-4">
                        <h4 class="text-center">Income Tax Rates</h4>
                        <?php
                        $stmt = $dbh->prepare("SELECT * FROM tbltaxrates WHERE tax_category = 'Income Tax'");
                        $stmt->execute();
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<div class='card bg-primary mb-3 text-light'>
                                    <div class='card-body'>
                                        <p><strong>Slab:</strong> {$row['slab_description']}</p>
                                        <p class='rate-text badge bg-warning'><strong>Tax Rate: " . ($row['rate'] * 100) . "%</strong></p>
                                        <p><strong>Fixed Amount:</strong> Rs " . number_format($row['fixed_amount'], 2) . "</p>
                                    </div>
                                  </div>";
                        }
                        ?>
                    </div>

                    <!-- Property Tax Rates -->
                    <div class="col-md-4">
                        <h4 class="text-center">Property Tax Rates</h4>
                        <?php
                        $stmt = $dbh->prepare("SELECT * FROM tbltaxrates WHERE tax_category = 'Property Tax'");
                        $stmt->execute();
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<div class='card bg-success mb-3 text-light'>
                                    <div class='card-body'>
                                        <p><strong>Slab:</strong> {$row['slab_description']}</p>
                                        <p class='rate-text badge bg-warning'><strong>Tax Rate:</strong> " . ($row['rate'] * 100) . "%</p>
                                        <p><strong>Fixed Amount: Rs " . number_format($row['fixed_amount'], 2) . "</strong></p>
                                    </div>
                                  </div>";
                        }
                        ?>
                    </div>

                    <!-- Sales Tax Rates -->
                    <div class="col-md-4">
                        <h4 class="text-center">Sales Tax Rates</h4>
                        <?php
                        $stmt = $dbh->prepare("SELECT * FROM tbltaxrates WHERE tax_category = 'Sales Tax'");
                        $stmt->execute();
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<div class='card bg-info mb-3 text-light'>
                                    <div class='card-body'>
                                        <p><strong>Slab:</strong> {$row['slab_description']}</p>
                                        <p class='rate-text badge bg-warning'><strong>Tax Rate:</strong> " . ($row['rate'] * 100) . "%</p>
                                        <p><strong>Fixed Amount: Rs " . number_format($row['fixed_amount'], 2) . "</strong></p>
                                    </div>
                                  </div>";
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php include_once('includes/footer.php'); ?>
        </div>
    </div>
     <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>
