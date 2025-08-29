<?php
// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include('dbconnection.php');
    $fullname = htmlspecialchars($_POST['fullname']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $subject = htmlspecialchars($_POST['subject']);
    $message = htmlspecialchars($_POST['message']);
    
    $sql = "INSERT INTO tblcontact (fullname, email, phone, subject, message) 
            VALUES (:fullname, :email, :phone, :subject, :message)";
    
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':fullname', $fullname);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':subject', $subject);
    $stmt->bindParam(':message', $message);
    
    if ($stmt->execute()) {
        echo "<script>window.onload = function() { alert('Thank you for reaching out! We\'ll get back to you soon.'); }</script>";
    } else {
        echo "<script>window.onload = function() { alert('Something went wrong. Please try again.'); }</script>";
    }
}
?>

   <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            color: white;
            font-family: 'Heebo', sans-serif;
        }
        .container-fluid {
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
        }
        .form-section {
            width: 50%;
            padding: 30px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            color: white;
        }
        .form-section h3 {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 20px;
            text-align: center;
            color: #ffdd40;
        }
        .btn-primary {
            background-color:  #6f42c1;
            border: none;
            color: white;
            font-weight: bold;
            padding: 10px 15px;
            font-size: 1rem;
            border-radius: 6px;
            width: 100%;
        }
        .btn-primary:hover {
            background-color: #fd7e14;
            color: white;
        }
        .image-section {
            background-image: url('../assets/img/hero/aboutus.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            width: 50%;
            position: relative;
        }
        .reach-us {
            position: absolute;
            bottom: 5%;
            left: 10%;
            width: 80%;
            background-color: rgba(0, 0, 0, 0.5);
            padding: 15px;
            border-radius: 8px;
            color: white;
            text-align: center;
        }
        .reach-us h2 {
            margin-bottom: 15px;
            font-weight: 600;
            color: purple;
        }
        .click{
            font-size: 60px;
            font-weight: 700;
            color: orange;
            margin-bottom: 20px;
        }
        .reach-us ul {
            list-style: none;
            padding: 0;
        }
        .reach-us li {
            margin-bottom: 10px;
            font-size: 18px;
        }
        .reach-us a {
            color: white;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <!-- Image Section with Reach Us Info -->
        <div class="image-section">
            <div class="reach-us">
                <h2>Reach Us</h2>
                <ul>
                    <li><a href="#"><i class="bi bi-telephone-fill"></i> +123 456 7890</a></li>
                    <li><a href="mailto:support@taxmanagement.com"><i class="bi bi-envelope-fill"></i> support@taxmanagement.com</a></li>
                    <li><a href="#"><i class="bi bi-geo-alt-fill"></i> Hameed Hazro, District Attock, Pakistan, Postal Code: 43440</a></li>
                </ul>
            </div>
        </div>

        <!-- Form Section -->
        <div class="form-section">
            <h1 class="click">Contact Us</h1>
            <form action="" method="POST">
                <div class="mb-3">
                    <input type="text" name="fullname" id="fullname" class="form-control" placeholder="Enter Your Name" required>
                </div>
                <div class="mb-3">
                    <input type="email" name="email" id="email" class="form-control" placeholder="Enter Your Email Address" required>
                </div>
                <div class="mb-3">
                    <input type="text" name="phone" id="phone" class="form-control" placeholder="Enter Your Phone No" required>
                </div>
                <div class="mb-3">
                    <input type="text" name="subject" id="subject" class="form-control" placeholder="Enter Your Subject" required>
                </div>
                <div class="mb-3">
                    <textarea name="message" id="message" rows="4" class="form-control" placeholder="Type Your Message Here" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Send Message</button>
            </form>
        </div>
    </div>
</body>
</html>
