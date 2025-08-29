<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $newpassword = md5($_POST['newpassword']);
    $sql = "SELECT Email FROM tbladmin WHERE Email=:email and MobileNumber=:mobile";
    $query = $dbh->prepare($sql);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':mobile', $mobile, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);
    if ($query->rowCount() > 0) {
        $con = "update tbladmin set Password=:newpassword where Email=:email and MobileNumber=:mobile";
        $chngpwd1 = $dbh->prepare($con);
        $chngpwd1->bindParam(':email', $email, PDO::PARAM_STR);
        $chngpwd1->bindParam(':mobile', $mobile, PDO::PARAM_STR);
        $chngpwd1->bindParam(':newpassword', $newpassword, PDO::PARAM_STR);
        $chngpwd1->execute();
        echo "<script>alert('Your Password has been successfully changed');</script>";
    } else {
        echo "<script>alert('Email ID or Mobile number is invalid');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTMS || Forgot Password</title>

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style type="text/css">
        /* Background setup */
        body {
            background-image: url('../assets/img/hero/h1_hero.png');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            font-family: 'Heebo', sans-serif;
            margin: 0;
            padding: 0;
        }

        /* Overlay for the background */
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
        }

        /* Centering the container */
        .container-fluid {
            position: relative;
            min-height: 100vh;
        }

        .form-container {
            position: relative;
            z-index: 2;
            max-width: 400px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .welcome-slogan h3 {
            font-size: 24px;
            font-weight: 600;
            color: #333;
            text-align: center;
        }

        .form-floating input {
            border-radius: 8px;
        }

        .form-floating label {
            font-weight: 500;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }

        .d-flex a {
            color: #007bff;
            font-size: 14px;
            text-decoration: none;
        }

        .d-flex a:hover {
            text-decoration: underline;
        }

        /* Responsive Styling */
        @media (max-width: 768px) {
            .form-container {
                padding: 20px;
            }

            .welcome-slogan h3 {
                font-size: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="overlay"></div> <!-- Background overlay -->

    <div class="container-fluid d-flex align-items-center justify-content-center">
        <div class="form-container">
            <div class="mb-3 text-center">
                <a href="index.html">
                    <img src="../assets/img/logo1.png" alt="Logo" height="90" width="90">
                </a>
                <h3 class="welcome-slogan text-primary"><strong>Reset Password</strong></h3>
            </div>

            <b><p class="text-center text-muted mb-4">Enter your email and mobile number to reset your password.</p></b>

            <form method="post" name="chngpwd" onSubmit="return valid();">
                <div class="form-floating mb-3">
                    <input type="email" class="form-control" placeholder="Email Address" required="true" name="email">
                    <label for="floatingInput">Email Address</label>
                </div>
                <div class="form-floating mb-4">
                    <input type="text" class="form-control" placeholder="Mobile Number" required="true" name="mobile" maxlength="10" pattern="[0-9]+">
                    <label for="floatingPassword">Mobile Number</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" name="newpassword" class="form-control" placeholder="New Password" required="true">
                    <label for="floatingInput">New Password</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" name="confirmpassword" class="form-control" placeholder="Confirm Password" required="true">
                    <label for="floatingInput">Confirm Password</label>
                </div>

                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="form-check">
                        <label class="pull-right">
                            <a href="signin.php">Already have an account? Login</a>
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary py-3 w-100 mb-4" name="submit">Reset Password</button>
            </form>
        </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
