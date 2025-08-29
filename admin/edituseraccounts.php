<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

// Check if the admin is logged in
if (strlen($_SESSION['ocasaid'] == 0)) {
    header('location:logout.php');
    exit;
}

// Fetch the user account details from tbluser
if (isset($_GET['id'])) {
    $user_id = intval($_GET['id']);
    $sql = "SELECT * FROM tbluser WHERE ID = :user_id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':user_id', $user_id, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_OBJ);

    if (!$result) {
        echo "<script>alert('Invalid User ID'); window.location.href='mnguaccount.php';</script>";
        exit();
    }
}

// Update the user account details in tbluser
if (isset($_POST['update'])) {
    $FullName = $_POST['FullName'];
    $Email = $_POST['Email'];
    $MobileNumber = $_POST['MobileNumber'];
    $RegDate = $_POST['RegDate'];

    $sql = "UPDATE tbluser SET FullName = :FullName, Email = :Email, MobileNumber = :MobileNumber, RegDate = :RegDate WHERE ID = :user_id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':FullName', $FullName, PDO::PARAM_STR);
    $query->bindParam(':Email', $Email, PDO::PARAM_STR);
    $query->bindParam(':MobileNumber', $MobileNumber, PDO::PARAM_STR);
    $query->bindParam(':RegDate', $RegDate, PDO::PARAM_STR);
    $query->bindParam(':user_id', $user_id, PDO::PARAM_STR);

    if ($query->execute()) {
        echo "<script>alert('User account updated successfully'); window.location.href='mnguaccount.php';</script>";
    } else {
        echo "<script>alert('Something went wrong, please try again later');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>OTM || Edit User Account</title>
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
        <?php include_once('includes/sidebar.php'); ?>
        <div class="content">
            <?php include_once('includes/header.php'); ?>
            <div class="container-fluid pt-4 px-4">
                <div class="bg-light text-start rounded p-4">
                    <div class="col-sm-12 col-xl-8">
                        <div class="bg-light rounded h-100 p-4">
                            <h4 class="mb-4 text-danger">Edit User Account</h4>
                            <form method="post">
                                <div class="form-group mb-3">
                                    <label>Full Name</label>
                                    <input type="text" class="form-control" name="FullName" value="<?php echo htmlentities($result->FullName); ?>" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label>Email</label>
                                    <input type="email" class="form-control" name="Email" value="<?php echo htmlentities($result->Email); ?>" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label>Mobile Number</label>
                                    <input type="text" class="form-control" name="MobileNumber" value="<?php echo htmlentities($result->MobileNumber); ?>" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label>Registration Date</label>
                                    <input type="text" class="form-control" name="RegDate" value="<?php echo htmlentities($result->RegDate); ?>" readonly>
                                </div>
                                <button type="submit" name="update" class="btn btn-success">Update</button>
                                <a href="mnguaccount.php" class="btn btn-danger">Cancel</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php include_once('includes/footer.php'); ?>
        </div>
    </div>

    <!-- Include JS libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>
