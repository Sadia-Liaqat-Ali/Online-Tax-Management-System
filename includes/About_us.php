<?php 
session_start();
error_reporting(0);
?>
<html lang="en">
<head>
    <title>About Us - Online Tax Management System</title>

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

    <!-- Additional Styles -->
    <style>
        body {
            font-family: 'Heebo', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        .main-content {
            display: flex;
            flex-direction: row;
            min-height: 100vh;
        }

        .text-content {
            flex: 1;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background-color: #fff;
        }

        .bg-image {
            flex: 1;
            background-image: url('../assets/img/hero/contact.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .heading {
            font-size: 60px;
            font-weight: 700;
            color: orange;
            margin-bottom: 20px;
        }

        p {
            font-size: 18px;
            line-height: 1.6;
            color: #333;
        }

        .read-more-btn {
            background-color: orange;
            color: #fff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            margin-top: 20px;
            border-radius: 5px;
            font-size: 16px;
        }

        .read-more-btn:hover {
            background-color: #be2ed6;
            color: white;
        }

        .hidden-text {
            display: none;
        }
    </style>
</head>

<body>
    <div class="main-content">
        <div class="text-content">
            <h1 class="heading">About Us</h1>
            <p id="text">
                Welcome to the Online Tax Management System, a comprehensive platform designed to automate the tax collection and management process for both taxpayers and tax authorities. Our goal is to streamline the tax process, reduce errors, and provide a user-friendly system that offers efficiency, accuracy, and security in tax filing and management.
                <span id="extra-text" class="hidden-text">
                    This web-based solution helps taxpayers manage tasks such as filing, paying, and retrieving reports and documents. It also enables tax authorities to manage taxpayer information, process payments, and generate reports efficiently. The system reduces complexity, eliminates paperwork, and ensures a seamless and transparent process for everyone involved.
                    Our platform is equipped with advanced features such as automated tax calculations, secure payment options, and instant access to detailed reports. By leveraging modern technologies, we aim to make tax management a hassle-free experience for both individual taxpayers and organizations.
                </span>
            </p>
            <button class="read-more-btn" id="read-more-btn">Read More</button>
        </div>
        <div class="bg-image"></div>
    </div>

    <!-- JavaScript to Handle Read More -->
    <script>
        const readMoreBtn = document.getElementById('read-more-btn');
        const extraText = document.getElementById('extra-text');

        readMoreBtn.addEventListener('click', () => {
            extraText.style.display = 'inline';
            readMoreBtn.style.display = 'none';
        });
    </script>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
