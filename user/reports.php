<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['ocasuid']==0)) {
    header('location:logout.php');
}

// Get logged-in user ID
$ocasuid = $_SESSION['ocasuid'];

// Check if a tax year is passed, else use the current tax year (e.g., 2023)
$tax_year = isset($_GET['tax_year']) ? $_GET['tax_year'] : date('Y'); // Default to current year if not provided

// Fetch total tax amounts for each category
$income_tax_total = 0;
$sales_tax_total = 0;
$property_tax_total = 0;

// Income Tax Query
$sql_income_tax = "SELECT SUM(tax_amount) AS total_tax FROM tblincometax WHERE UserID=:ocasuid AND status='verified' AND tax_year=:tax_year";
$query = $dbh->prepare($sql_income_tax);
$query->bindParam(':ocasuid', $ocasuid, PDO::PARAM_STR);
$query->bindParam(':tax_year', $tax_year, PDO::PARAM_STR);
$query->execute();
$result_income_tax = $query->fetch(PDO::FETCH_ASSOC);
$income_tax_total = $result_income_tax['total_tax'];

// Sales Tax Query
$sql_sales_tax = "SELECT SUM(tax_amount) AS total_tax FROM tblsalestax WHERE UserID=:ocasuid AND status='verified' AND tax_year=:tax_year";
$query = $dbh->prepare($sql_sales_tax);
$query->bindParam(':ocasuid', $ocasuid, PDO::PARAM_STR);
$query->bindParam(':tax_year', $tax_year, PDO::PARAM_STR);
$query->execute();
$result_sales_tax = $query->fetch(PDO::FETCH_ASSOC);
$sales_tax_total = $result_sales_tax['total_tax'];

// Property Tax Query
$sql_property_tax = "SELECT SUM(tax_amount) AS total_tax FROM tblpropertytax WHERE UserID=:ocasuid AND status='verified' AND tax_year=:tax_year";
$query = $dbh->prepare($sql_property_tax);
$query->bindParam(':ocasuid', $ocasuid, PDO::PARAM_STR);
$query->bindParam(':tax_year', $tax_year, PDO::PARAM_STR);
$query->execute();
$result_property_tax = $query->fetch(PDO::FETCH_ASSOC);
$property_tax_total = $result_property_tax['total_tax'];
?>

<html lang="en">
<head>
    <title>OTMS || View Tax Report</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div class="container-fluid position-relative bg-white d-flex p-0">
        <?php include_once('includes/sidebar.php'); ?>

        <div class="content">
            <?php include_once('includes/header.php'); ?>

            <div class="container-fluid pt-4 px-4">
                <div class="bg-light text-center rounded p-4">
    <div class="bg-primary text-dark text-center rounded p-4 mb-4 shadow-sm">
                        <h4 class="mb-0 text-white">Yearly Tax Report for Tax Year <?php echo htmlentities($tax_year); ?></h4>
                    </div>

                    <div class="row">
                        <!-- Income Tax Report -->
                        <div class="col-md-4 mb-4">
                            <div class="bg-white text-center rounded p-4">
                                <h5>Income Tax Report</h5>
                                <table class="table text-start align-middle table-bordered table-hover mb-0">
                                    <thead>
                                        <tr class="text-danger">
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>CNIC</th>
                                            <th>Income</th>
                                            <th>Tax</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Query for Income Tax
                                        $sql = "SELECT * from tblincometax WHERE UserID=:ocasuid AND status='verified' AND tax_year=:tax_year";
                                        $query = $dbh->prepare($sql);
                                        $query->bindParam(':ocasuid', $ocasuid, PDO::PARAM_STR);
                                        $query->bindParam(':tax_year', $tax_year, PDO::PARAM_STR);
                                        $query->execute();
                                        $results = $query->fetchAll(PDO::FETCH_OBJ);

                                        $cnt = 1;
                                        if ($query->rowCount() > 0) {
                                            foreach ($results as $row) {
                                        ?>
                                            <tr>
                                                <td><?php echo htmlentities($cnt);?></td>
                                                <td><?php echo htmlentities($row->name);?></td>
                                                <td><?php echo htmlentities($row->cnic);?></td>
                                                <td><?php echo htmlentities($row->income);?></td>
                                                <td class="text-primary"><?php echo htmlentities($row->tax_amount);?></td>
                                            </tr>
                                        <?php 
                                            $cnt++;
                                            }
                                        } else {
                                            echo "<tr><td colspan='5'>No data found for Income Tax in Tax Year $tax_year.</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Sales Tax Report -->
                        <div class="col-md-4 mb-4">
                            <div class="bg-white text-center rounded p-4">
                                <h5>Sales Tax Report</h5>
                                <table class="table text-start align-middle table-bordered table-hover mb-0">
                                    <thead>
                                        <tr class="text-danger">
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>CNIC</th>
                                            <th>Sales Amount</th>
                                            <th>Tax</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Query for Sales Tax
                                        $sql = "SELECT * from tblsalestax WHERE UserID=:ocasuid AND status='verified' AND tax_year=:tax_year";
                                        $query = $dbh->prepare($sql);
                                        $query->bindParam(':ocasuid', $ocasuid, PDO::PARAM_STR);
                                        $query->bindParam(':tax_year', $tax_year, PDO::PARAM_STR);
                                        $query->execute();
                                        $results = $query->fetchAll(PDO::FETCH_OBJ);

                                        $cnt = 1;
                                        if ($query->rowCount() > 0) {
                                            foreach ($results as $row) {
                                        ?>
                                            <tr>
                                                <td><?php echo htmlentities($cnt);?></td>
                                                <td><?php echo htmlentities($row->name);?></td>
                                                <td><?php echo htmlentities($row->cnic);?></td>
                                                <td><?php echo htmlentities($row->sales_amount);?></td>
                                                <td class="text-primary"><?php echo htmlentities($row->tax_amount);?></td>
                                            </tr>
                                        <?php 
                                            $cnt++;
                                            }
                                        } else {
                                            echo "<tr><td colspan='5'>No data found for Sales Tax in Tax Year $tax_year.</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Property Tax Report -->
                        <div class="col-md-4 mb-4">
                            <div class="bg-white text-center rounded p-4">
                                <h5>Property Tax Report</h5>
                                <table class="table text-start align-middle table-bordered table-hover mb-0">
                                    <thead>
                                        <tr class="text-danger">
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>CNIC</th>
                                            <th>Market Value</th>
                                            <th>Tax</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Query for Property Tax
                                        $sql = "SELECT * from tblpropertytax WHERE UserID=:ocasuid AND status='verified' AND tax_year=:tax_year";
                                        $query = $dbh->prepare($sql);
                                        $query->bindParam(':ocasuid', $ocasuid, PDO::PARAM_STR);
                                        $query->bindParam(':tax_year', $tax_year, PDO::PARAM_STR);
                                        $query->execute();
                                        $results = $query->fetchAll(PDO::FETCH_OBJ);

                                        $cnt = 1;
                                        if ($query->rowCount() > 0) {
                                            foreach ($results as $row) {
                                        ?>
                                            <tr>
                                                <td><?php echo htmlentities($cnt);?></td>
                                                <td><?php echo htmlentities($row->name);?></td>
                                                <td><?php echo htmlentities($row->cnic);?></td>
                                                <td><?php echo htmlentities($row->market_value);?></td>
                                                <td class="text-primary"><?php echo htmlentities($row->tax_amount);?></td>
                                            </tr>
                                        <?php 
                                            $cnt++;
                                            }
                                        } else {
                                            echo "<tr><td colspan='5'>No data found for Property Tax in Tax Year $tax_year.</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


<?php

$UserID = $_SESSION['ocasuid'];
$tax_year = $_GET['tax_year'] ?? date('Y');

// Query to calculate total taxable amount directly
$sql = "SELECT 
            COALESCE(SUM(it.income), 0) AS total_income,
            COALESCE(SUM(st.sales_amount), 0) AS total_sales,
            COALESCE(SUM(pt.market_value), 0) AS total_property
        FROM tblincometax it
        LEFT JOIN tblsalestax st ON it.UserID = st.UserID AND it.tax_year = st.tax_year
        LEFT JOIN tblpropertytax pt ON it.UserID = pt.UserID AND it.tax_year = pt.tax_year
        WHERE it.UserID = :UserID AND it.tax_year = :tax_year";

$query = $dbh->prepare($sql);
$query->bindParam(':UserID', $UserID, PDO::PARAM_STR);
$query->bindParam(':tax_year', $tax_year, PDO::PARAM_STR);
$query->execute();
$result = $query->fetch(PDO::FETCH_ASSOC);

// Calculate total taxable amount
$total_taxable_amount = $result['total_income'] + $result['total_sales'] + $result['total_property'];
?>
<div class="bg-secondary text-dark text-center rounded p-4 mb-4 shadow-sm">
    <h5 class="text-light mb-3">Total Taxable Amount</h5>
    <p class="display-7 fw-bold text-white">
        RS<?= number_format($total_taxable_amount, 2); ?>
    </p></div>







                    <!-- Chart Section -->
                    <div class="container">
                        <h4 class="text-danger text-start ms-3">Your Tax Breakdown for the Year <?php echo htmlentities($tax_year); ?></h4>

<canvas id="taxChart" style="max-width: 350px; max-height: 350px;"></canvas>
                    </div>

                    <script>
                        var ctx = document.getElementById('taxChart').getContext('2d');
                        var taxChart = new Chart(ctx, {
                            type: 'pie',
                            data: {
                                labels: ['Income Tax', 'Sales Tax', 'Property Tax'],
                                datasets: [{
                                    label: 'Tax Amounts',
                                    data: [<?php echo $income_tax_total; ?>, <?php echo $sales_tax_total; ?>, <?php echo $property_tax_total; ?>],
                                    backgroundColor: ['#ff5678', '#28a745', '#17a2b8'],
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: {
                                        position: 'top',
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: function(tooltipItem) {
                                                return tooltipItem.raw + 'RS'.toLocaleString();
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    </script>

                </div>
            </div>
            <?php include_once('includes/footer.php');?>
        </div>
        <!-- Content End -->
        <?php include_once('includes/back-totop.php');?>
    </div>




        </div>
    </div>
 <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>
