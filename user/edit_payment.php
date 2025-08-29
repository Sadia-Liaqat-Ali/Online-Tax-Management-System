<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

// Redirect if not logged in
if (strlen($_SESSION['ocasuid']) == 0) {
    header('location:logout.php');
    exit();
}

// Fetch the payment details
if (isset($_GET['ID'])) {
    $paymentID = $_GET['ID'];

    // Fetch payment details from the database
    $sql = "SELECT * FROM tblpayments WHERE ID = :paymentID";
    $query = $dbh->prepare($sql);
    $query->bindParam(':paymentID', $paymentID, PDO::PARAM_INT);
    $query->execute();
    $payment = $query->fetch(PDO::FETCH_ASSOC);

    if (!$payment) {
        echo '<script>alert("Payment record not found!"); window.location.href = "payments.php";</script>';
        exit();
    }
} else {
    echo '<script>alert("Invalid payment ID!"); window.location.href = "payments.php";</script>';
    exit();
}

// Update payment details
if (isset($_POST['update'])) {
    try {
        $taxCategory = $_POST['taxCategory'];
        $amount = $_POST['amount'];
        $paymentMethod = $_POST['paymentMethod'];
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

        // Handle file upload
        if (!empty($_FILES["file1"]["name"])) {
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
            // If no file uploaded, retain the existing proof document
            $file1 = $payment['ProofDocument'];
        }

        // Update data in tblpayments
        $sql = "UPDATE tblpayments 
                SET TaxCategory = :TaxCategory, 
                    Amount = :Amount, 
                    PaymentMethod = :PaymentMethod, 
                    ProofDocument = :ProofDocument, 
                    TransactionID = :TransactionID, 
                    CardNumber = :CardNumber, 
                    CardExpiry = :CardExpiry, 
                    CardCVC = :CardCVC, 
                    CashDetails = :CashDetails 
                WHERE ID = :PaymentID";
        $query = $dbh->prepare($sql);
        $query->bindParam(':TaxCategory', $taxCategory, PDO::PARAM_STR);
        $query->bindParam(':Amount', $amount, PDO::PARAM_STR);
        $query->bindParam(':PaymentMethod', $paymentMethod, PDO::PARAM_STR);
        $query->bindParam(':ProofDocument', $file1, PDO::PARAM_STR);
        $query->bindParam(':TransactionID', $transactionId, PDO::PARAM_STR);
        $query->bindParam(':CardNumber', $cardNumber, PDO::PARAM_STR);
        $query->bindParam(':CardExpiry', $cardExpiry, PDO::PARAM_STR);
        $query->bindParam(':CardCVC', $cardCVC, PDO::PARAM_STR);
        $query->bindParam(':CashDetails', $cashDetails, PDO::PARAM_STR);
        $query->bindParam(':PaymentID', $paymentID, PDO::PARAM_INT);

        if ($query->execute()) {
            echo '<script>alert("Payment details updated successfully."); window.location.href = "old_returns.php";</script>';
        } else {
            echo '<script>alert("Failed to update payment details. Please try again.");</script>';
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
    <title>Edit Payment | OTM</title>
    <!-- Include CSS & JS -->
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
</head>
<body>
    <div class="container-fluid position-relative bg-white d-flex p-0">
        <?php include_once('includes/sidebar.php'); ?>
        <div class="content">
            <?php include_once('includes/header.php'); ?>
            <div class="container-fluid pt-4 px-4">
                <div class="col-sm-12 col-xl-6">
                    <div class="bg-light text-start rounded p-4">
                        <h4 class="mb-4">Edit Payment</h4>
                        <form method="post" action="" id="paymentForm" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="taxCategory" class="form-label">Tax Category</label>
                                <select class="form-select" id="taxCategory" name="taxCategory" required>
                                    <option value="Income Tax" <?php echo ($payment['TaxCategory'] == 'Income Tax') ? 'selected' : ''; ?>>Income Tax</option>
                                    <option value="Sales Tax" <?php echo ($payment['TaxCategory'] == 'Sales Tax') ? 'selected' : ''; ?>>Sales Tax</option>
                                    <option value="Property Tax" <?php echo ($payment['TaxCategory'] == 'Property Tax') ? 'selected' : ''; ?>>Property Tax</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="amount" class="form-label">Amount</label>
                                <input type="number" class="form-control" id="amount" name="amount" value="<?php echo $payment['Amount']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="file1" class="form-label">Upload Proof (PNG, JPG allowed)</label>
                                <input type="file" class="form-control" name="file1" accept=".pdf, .png, .jpg">
                                <p>Current File: <a href="img/<?php echo $payment['ProofDocument']; ?>" target="_blank"><?php echo $payment['ProofDocument']; ?></a></p>
                            </div>

                            <div class="mb-3">
                                <label for="paymentMethod" class="form-label">Payment Method</label>
                                <select class="form-select" id="paymentMethod" name="paymentMethod" required onchange="togglePaymentFields()">
                                    <option value="Bank Transfer" <?php echo ($payment['PaymentMethod'] == 'Bank Transfer') ? 'selected' : ''; ?>>Bank Transfer</option>
                                    <option value="Card" <?php echo ($payment['PaymentMethod'] == 'Card') ? 'selected' : ''; ?>>Card</option>
                                    <option value="Cash" <?php echo ($payment['PaymentMethod'] == 'Cash') ? 'selected' : ''; ?>>Cash by Hand</option>
                                </select>
                            </div>
                            <!-- Bank Transfer Fields -->
                            <div class="mb-3" id="bankTransferFields" style="display: <?php echo ($payment['PaymentMethod'] == 'Bank Transfer') ? 'block' : 'none'; ?>;">
                                <label for="transactionId" class="form-label">Bank Transaction ID</label>
                                <input type="text" class="form-control" id="transactionId" name="transactionId" value="<?php echo $payment['TransactionID']; ?>">
                            </div>
                            <!-- Card Payment Fields -->
                            <div id="cardFields" style="display: <?php echo ($payment['PaymentMethod'] == 'Card') ? 'block' : 'none'; ?>;">
                                <div class="mb-3">
                                    <label for="cardNumber" class="form-label">Card Number</label>
                                    <input type="text" class="form-control" id="cardNumber" name="cardNumber" value="<?php echo $payment['CardNumber']; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="cardExpiry" class="form-label">Card Expiry Date</label>
                                    <input type="text" class="form-control" id="cardExpiry" name="cardExpiry" value="<?php echo $payment['CardExpiry']; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="cardCVC" class="form-label">Card CVC</label>
                                    <input type="text" class="form-control" id="cardCVC" name="cardCVC" value="<?php echo $payment['CardCVC']; ?>" required>
                                </div>
                            </div>
                            <!-- Cash Payment Fields -->
                            <div class="mb-3" id="cashFields" style="display: <?php echo ($payment['PaymentMethod'] == 'Cash') ? 'block' : 'none'; ?>;">
                                <label for="cashDetails" class="form-label">Cash Payment Details</label>
                                <textarea class="form-control" id="cashDetails" name="cashDetails"><?php echo $payment['CashDetails']; ?></textarea>
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary" name="update">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php include_once('includes/footer.php'); ?>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/main.js"></script>
    <script>
        function togglePaymentFields() {
            const paymentMethod = document.getElementById('paymentMethod').value;
            document.getElementById('bankTransferFields').style.display = (paymentMethod === 'Bank Transfer') ? 'block' : 'none';
            document.getElementById('cardFields').style.display = (paymentMethod === 'Card') ? 'block' : 'none';
            document.getElementById('cashFields').style.display = (paymentMethod === 'Cash') ? 'block' : 'none';
        }
    </script>
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
