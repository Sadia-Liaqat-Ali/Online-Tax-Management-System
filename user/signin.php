<?php
session_start();
//error_reporting(0);
include('includes/dbconnection.php');

if(isset($_POST['login'])) 
  {
    $emailormobnum=$_POST['emailormobnum'];
    $password=md5($_POST['password']);
    $sql ="SELECT Email,MobileNumber,Password,ID FROM tbluser WHERE (Email=:emailormobnum || MobileNumber=:emailormobnum) and Password=:password";
    $query=$dbh->prepare($sql);
    $query->bindParam(':emailormobnum',$emailormobnum,PDO::PARAM_STR);
    $query-> bindParam(':password', $password, PDO::PARAM_STR);
    $query-> execute();
    $results=$query->fetchAll(PDO::FETCH_OBJ);
    if($query->rowCount() > 0)
{
foreach ($results as $result) {
$_SESSION['ocasuid']=$result->ID;

}
$_SESSION['login']=$_POST['emailormobnum'];

echo "<script type='text/javascript'> document.location ='dashboard.php'; </script>";
} else{
echo "<script>alert('Invalid Details');</script>";
}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTMS || Signin</title>
    
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

    <style type="text/css">
        /* Background setup */ 
        body {
            background-image: url('../assets/img/hero/h1_hero.PNG');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            font-family: 'Heebo', sans-serif;
        }

        /* Overlay for the background */
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5); /* Dark overlay */
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
            background: rgba(255, 255, 255, 0.9); /* Semi-transparent white background */
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
                    <img src="../assets/img/logo1.png" alt="Logo" height="110" width="130">
                </a>
                <h3 class="welcome-slogan text-primary"><strong>Welcome Back to OTMS!</strong></h3>
            </div>

            <!-- Welcome Slogan Section -->
            <b><p class="text-center text-muted mb-4">Simplify your tax journey in an easy, secure & stress-free way!</p></b>

            <form method="post">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" placeholder="Email or Mobile Number" required="true" name="emailormobnum">
                    <label for="floatingInput">Email or Mobile Number</label>
                </div>
                <div class="form-floating mb-4">
                    <input type="password" class="form-control" placeholder="Password" name="password" required="true">
                    <label for="floatingPassword">Password</label>
                </div>
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <a href="forgot-password.php">Forgot Password?</a>
                </div>
                <button type="submit" class="btn btn-primary py-3 w-100 mb-4" name="login">Log In</button>
            </form>

            <div class="d-flex justify-content-between">
                <a href="../index.php">Home Page</a>
                <a href="../signup.php">Create an Account</a>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/chart/chart.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>
</html>
