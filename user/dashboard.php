<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['ocasuid'] == 0)) {
    header('location:logout.php');
} else {
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>OTMS || Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <style>
        /* Card Styles */
        .card {
            min-width: 250px;
            background-color: #f8f9fa;
            margin-bottom: 20px;
            border: none;
            border-radius: 8px;
        }
        .card .card-body {
            padding: 1.25rem;
            color: #fff;
        }
        .card.bg-primary {background-color: #ff5678 !important;}
.card.bg-primary:hover {background-color: #ff3b5c !important; /* Darker shade on hover */}
.card.bg-success {background-color: #28a745 !important;}
.card.bg-success:hover {background-color: #218838 !important; /* Darker shade on hover */}
.card.bg-info {background-color: #17a2b8 !important;}
.card.bg-info:hover {background-color: #138496 !important; /* Darker shade on hover */}

        /* Section Separation */
        .separator {
            border-top: 2px solid #ccc;
            margin: 20px 0;
        }

        /* Table Styles */
        .table th, .table td {
            text-align: center;
        }

        .container-fluid {
            padding-left: 3rem;
            padding-right: 3rem;
        }
    </style>
</head>

<body>
    <div class="container-fluid position-relative bg-white d-flex p-0">
        <?php include_once('includes/sidebar.php'); ?>
        <div class="content">
            <?php include_once('includes/header.php'); ?>

            <!-- Greeting Section -->
            <div class="container-fluid pt-4 px-4">
                <div class="bg-light text-start rounded p-4">
                    <h1>Hello, 
                        <?php 
                        $uid = $_SESSION['ocasuid'];
                        $sql = "SELECT FullName FROM tbluser WHERE ID=:uid";
                        $query = $dbh->prepare($sql);
                        $query->bindParam(':uid', $uid, PDO::PARAM_STR);
                        $query->execute();
                        $user = $query->fetch(PDO::FETCH_ASSOC);
                        echo $user['FullName']; 
                        ?> 
                        <span>Welcome to your panel!</span>
                    </h1>
                </div>
            </div>

            <!-- Cards Section -->
            <div class="container-fluid pt-4 px-4">
                <div class="row">
                    <?php 
                    // Queries for counting total and pending applications for each tax type for the user
                    $queries = [
                        'total_income' => "SELECT COUNT(*) AS total FROM tblincometax WHERE UserID=:uid",
                        'total_property' => "SELECT COUNT(*) AS total FROM tblpropertytax WHERE UserID=:uid",
                        'total_sales' => "SELECT COUNT(*) AS total FROM tblsalestax WHERE UserID=:uid",
                        'pending_income' => "SELECT COUNT(*) AS pending FROM tblincometax WHERE UserID=:uid AND status='Pending'",
                        'pending_property' => "SELECT COUNT(*) AS pending FROM tblpropertytax WHERE UserID=:uid AND status='Pending'",
                        'pending_sales' => "SELECT COUNT(*) AS pending FROM tblsalestax WHERE UserID=:uid AND status='Pending'"
                    ];

                    $results = [];
                    foreach ($queries as $key => $sql) {
                        $query = $dbh->prepare($sql);
                        $query->bindParam(':uid', $uid, PDO::PARAM_STR);
                        $query->execute();
                        $results[$key] = $query->fetch(PDO::FETCH_ASSOC);
                    }

                    // Array of cards to display
                    $cards = [
                        ['title' => 'Income Tax Applications', 'total' => $results['total_income']['total'], 'pending' => $results['pending_income']['pending'], 'color' => 'bg-primary', 'icon' => 'fas fa-file-alt'],
                        ['title' => 'Sales Tax Applications', 'total' => $results['total_sales']['total'], 'pending' => $results['pending_sales']['pending'], 'color' => 'bg-info', 'icon' => 'fas fa-chart-line'],
                        ['title' => 'Property Tax Applications', 'total' => $results['total_property']['total'], 'pending' => $results['pending_property']['pending'], 'color' => 'bg-success', 'icon' => 'fas fa-home']
                    ];

                    foreach ($cards as $card) {
                        echo '
                        <div class="col-md-4">
                            <div class="card '.$card['color'].' shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-uppercase mb-1">
                                                '.$card['title'].' 
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold">Total: '.$card['total'].'</div>
                                            <div class="h6 font-weight-bold mt-2">Pending: '.$card['pending'].'</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="'.$card['icon'].' fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>';
                    }
                    ?>
                </div>
            </div>

            <div class="separator"></div>

            <!-- Yearly Report Section -->
            <div class="container-fluid pt-4 px-4">
                <div class="row">
                    <div class="col-12">
                        <div class="bg-light text-center rounded p-4">
                            <h4 class="mb-0">Grand(Yearly) Tax Report</h4>
                        </div>
                        <div class="table-responsive mt-4">
                            <table class="table text-start align-middle table-bordered table-hover mb-0">
                                <thead class="text-danger">
                                    <tr>
                                        <th>s.no</th>
                                        <th>Tax Year</th>
                                        <th>Income Amount</th>
                                        <th>Sales Amount</th>
                                        <th>Property Value</th>
                                        <th>Total Amount</th>
                                        <th>Total Tax</th>
                                         <th>Action</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT tax_year, income_amount, sales_amount, property_value, total_taxable_amount, total_tax_amount FROM tbl_reports WHERE UserID=:uid";
                                    $query = $dbh->prepare($sql);
                                    $query->bindParam(':uid', $uid, PDO::PARAM_INT);
                                    $query->execute();
                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                    $sno;
                                    if($query->rowCount() > 0) {
                                        foreach($results as $row) {
                                            echo "<tr>
                                                <td>$sno</td>
                                                <td>{$row->tax_year}</td>
                                                <td>{$row->income_amount}</td>
                                                <td>{$row->sales_amount}</td>
                                                <td>{$row->property_value}</td>
                                                <td>{$row->total_taxable_amount}</td>
                                                <td>{$row->total_tax_amount}</td>
                                                <td><a class='btn btn-lg btn-warning rounded-pill font-weight-bold' href='reports.php?tax_year={$row->tax_year}'>View Reports</a></td>


                                            </tr>";
                                            $sno++;
                                        }
                                    } else {
                                        echo "<tr><td colspan='7'>No records found</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
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
    <script src="js/main.js"></script>
</body>
</html>

<?php } ?>
