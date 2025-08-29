<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

// Redirect if not logged in
if (strlen($_SESSION['ocasuid']) == 0) {
    header('location:logout.php');
    exit();
}

// Handle form submission
if (isset($_POST['pay'])) {
    try {
        $ocasuid = $_SESSION['ocasuid'];
        $taxCategory = $_POST['taxCategory'];
        $amount = $_POST['amount'];
        $paymentMethod = $_POST['paymentMethod'];
        $paymentStatus = 'Pending';

        // Prepare default values
        $transactionId = 'No';
        $cardNumber = 'No';
        $cardExpiry = 'No';
        $cardCVC = 'No';
        $cashDetails = 'No';

        // Assign values based on payment method
        if ($paymentMethod === 'Bank Transfer') {
            $transactionId = $_POST['transactionId'];
        } elseif ($paymentMethod === 'Card') {
            $cardNumber = $_POST['cardNumber'];
            $cardExpiry = $_POST['cardExpiry'];
            $cardCVC = $_POST['cardCVC'];
        } elseif ($paymentMethod === 'Cash') {
            $cashDetails = $_POST['cashDetails'];
        }

        // Handle file upload with validation
        if (!empty($_FILES["file1"]["name"])) {
            // Get the file details and generate a unique name
            $file1 = uniqid() . '_' . basename($_FILES["file1"]["name"]);
            $uploadDir = 'img/'; // Directory for storing images
            $uploadFilePath = $uploadDir . $file1;

            // Move the uploaded file to the designated directory
            if (move_uploaded_file($_FILES["file1"]["tmp_name"], $uploadFilePath)) {
                echo '<script>alert("File uploaded successfully.")</script>';
            } else {
                echo '<script>alert("Failed to upload the file. Please check file permissions or try again.")</script>';
                exit();
            }
        } else {
            echo '<script>alert("Please upload a proof document.")</script>';
            exit();
        }

        // Insert data into tblpayments
        $sql = "INSERT INTO tblpayments 
                (UserID, TaxCategory, PaymentMethod, ProofDocument, Amount, PaymentStatus, TransactionID, CardNumber, CardExpiry, CardCVC, CashDetails) 
                VALUES 
                (:UserID, :TaxCategory, :PaymentMethod, :ProofDocument, :Amount, :PaymentStatus, :TransactionID, :CardNumber, :CardExpiry, :CardCVC, :CashDetails)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':UserID', $ocasuid, PDO::PARAM_INT);
        $query->bindParam(':TaxCategory', $taxCategory, PDO::PARAM_STR);
        $query->bindParam(':PaymentMethod', $paymentMethod, PDO::PARAM_STR);
        $query->bindParam(':ProofDocument', $file1, PDO::PARAM_STR);
        $query->bindParam(':Amount', $amount, PDO::PARAM_STR);
        $query->bindParam(':PaymentStatus', $paymentStatus, PDO::PARAM_STR);
        $query->bindParam(':TransactionID', $transactionId, PDO::PARAM_STR);
        $query->bindParam(':CardNumber', $cardNumber, PDO::PARAM_STR);
        $query->bindParam(':CardExpiry', $cardExpiry, PDO::PARAM_STR);
        $query->bindParam(':CardCVC', $cardCVC, PDO::PARAM_STR);
        $query->bindParam(':CashDetails', $cashDetails, PDO::PARAM_STR);

        if ($query->execute()) {
            echo '<script>alert("Tax Returns Filed Successfully.");</script>';
           echo "<script>window.location.href = 'old_returns.php';</script>";

        } else {
            echo '<script>alert("Failed to file Tax Returns. Please try again.");</script>';
        }
    } catch (PDOException $e) {
        echo '<script>alert("Error: ' . htmlspecialchars($e->getMessage()) . '");</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTM || Payment</title>
    <!-- Include CSS and Libraries -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid position-relative bg-white d-flex p-0">
        <?php include_once('includes/sidebar.php'); ?>
        <div class="content">
            <?php include_once('includes/header.php'); ?>
            <div class="container-fluid pt-4 px-4">
                <div class="col-sm-12 col-xl-6">
                    <div class="bg-light text-start rounded p-4">
                        <h4 class="mb-4">File Your Tax Returns</h4>
                        <form method="post" action="" id="paymentForm" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="taxCategory" class="form-label">Tax Category</label>
                                <select class="form-select" id="taxCategory" name="taxCategory" required>
                                    <option value="">Select Tax Category</option>
                                    <option value="Income Tax">Income Tax</option>
                                    <option value="Sales Tax">Sales Tax</option>
                                    <option value="Property Tax">Property Tax</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="amount" class="form-label">Amount</label>
                                <input type="number" class="form-control" id="amount" name="amount" required>
                            </div>
                            <div class="mb-3">
                                <label for="file1" class="form-label">Upload Proof (PNG, JPG allowed)</label>
                                <input type="file" class="form-control" name="file1" accept=".pdf, .png, .jpg" required>
                            </div>

                            <div class="mb-3">
                                <label for="paymentMethod" class="form-label">Payment Method</label>
                                <select class="form-select" id="paymentMethod" name="paymentMethod" required onchange="togglePaymentFields()">
                                    <option value="">Select Payment Method</option>
                                    <option value="Bank Transfer">Bank Transfer</option>
                                    <option value="Card">Card</option>
                                    <option value="Cash">Cash by Hand</option>
                                </select>
                            </div>
                            <!-- Bank Transfer Fields -->
                            <div class="mb-3" id="bankTransferFields" style="display: none;">
                                <label for="transactionId" class="form-label">Bank Transaction ID</label>
                                <input type="text" class="form-control" id="transactionId" name="transactionId">
                            </div>
                            <!-- Card Payment Fields -->
                            <div id="cardFields" style="display: none;">
                                <div class="mb-3">
                                    <label for="cardNumber" class="form-label">Card Number</label>
                                    <input type="text" class="form-control" id="cardNumber" name="cardNumber">
                                </div>
                                <div class="mb-3">
                                    <label for="cardExpiry" class="form-label">Card Expiry (MM/YY)</label>
                                    <input type="text" class="form-control" id="cardExpiry" name="cardExpiry">
                                </div>
                                <div class="mb-3">
                                    <label for="cardCVC" class="form-label">CVC</label>
                                    <input type="text" class="form-control" id="cardCVC" name="cardCVC">
                                </div>
                            </div>
                            <!-- Cash by Hand Fields -->
                            <div id="cashFields" style="display: none;">
                                <div class="mb-3">
                                    <label for="cashDetails" class="form-label">Cash Details</label>
                                    <textarea class="form-control" id="cashDetails" name="cashDetails" rows="3"></textarea>
                                </div>
                            </div>
                            <button type="submit" name="pay" class="btn btn-primary">Submit Request</button>
                        </form>
                    </div>
                </div>
            </div>
            <?php include_once('includes/footer.php'); ?>
        </div>
    </div>
    <script>
        function togglePaymentFields() {
            var paymentMethod = document.getElementById('paymentMethod').value;
            document.getElementById('bankTransferFields').style.display = (paymentMethod === 'Bank Transfer') ? 'block' : 'none';
            document.getElementById('cardFields').style.display = (paymentMethod === 'Card') ? 'block' : 'none';
            document.getElementById('cashFields').style.display = (paymentMethod === 'Cash') ? 'block' : 'none';
        }
    </script>
</body>
</html>

    </script>
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
    <script src="js/main.js"></script>
</body>
</html>


