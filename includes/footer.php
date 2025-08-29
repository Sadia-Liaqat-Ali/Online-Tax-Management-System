<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>footer</title>
    <style type="text/css">
        .footer-container {
            display: flex;
            justify-content: space-between;
            background-color: #141619;
            padding: 20px 50px;
        }

        .footer-section {
            flex: 1;
            margin-right: 20px;
        }

        .footer-section h2 {
            font-size: 20px;
            margin-bottom: 10px;
            color: yellow;
        }

        .footer-section ul {
            list-style: none;
            padding: 0;
        }

        .footer-section ul li {
            margin-bottom: 10px;
        }

        .footer-section ul li a {
            color: #fff;
            text-decoration: none;
        }

        .footer-section.social a {
            margin-right: 20px;
            color: #fff;
            font-size: 20px;
        }

        .footer-bottom {
            text-align: center;
            padding: 10px;
            background-color: #B3B4BD;
        }

        .footer-bottom p {
            margin: 0;
            color: black;
        }

        p {
            color: white;
            font-size: 14px;
        }

        a {
            font-size: 14px;
        }

        .social-icon {
            font-size: 60px; /* Adjust the size as needed */
            color: whitesmoke; /* Optional: change the color of the icons */
            margin: 10px; /* Optional: adds spacing around the icons */
        }

        /* Ensure social icons are properly styled */
        .social-icon {
            color: #fff;
        }
    </style>
</head>
<body>
<footer>
    <div class="footer-container footer-wrappper footer-bg">
        <!-- Unique About Us Section -->
        <div class="footer-section about">
            <h2>Our Vision</h2>
            <p>The Online Tax Management System is a comprehensive solution designed to simplify tax processes for everyone. Its user-friendly design, security features, and automation make it a great tool for improving tax compliance and building trust.For taxpayers, it eliminates the complexities of manual filing by automating key tasks, reducing errors, and saving time. For tax authorities, it provides powerful tools to efficiently manage data, ensure compliance, and improve decision-making. By streamlining tax management for both sides, the system fosters a smoother, more reliable experience while enhancing overall efficiency and accuracy.</p>
        </div>

        <div class="footer-section services">
            <h2>Our Services</h2>
            <ul>
                <li><p>Smart Tax Applications</p></li>
                <li><p>Efficient Document Processing</p></li>
                <li><p>Automated Tax Calculations</p></li>
                <li><p>Secure Payments</p></li>
                <li><p>Insightful Reporting</p></li>
                <li><p>Timely Notifications & Alerts</p></li>
                <li><p>Admin Control Hub</p></li>
            </ul>
        </div>

        <div class="footer-section links">
            <h2>Quick Links</h2>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="includes/contact_us.php">Contact Us</a></li>
                <li><a href="includes/About_us.php">About Us</a></li>
                <li><a href="signup.php">Register Now</a></li>
                <li><a href="user/signin.php">User Login</a></li>
                <li><a href="#">FAQs</a></li>
            </ul>
        </div>

        <div class="footer-section social">
            <h2>Follow Us</h2>
            <a href="https://www.facebook.com/taxmanagement"><i class="fab fa-facebook social-icon"></i></a>
            <a href="https://twitter.com/taxmanagement"><i class="fab fa-twitter social-icon"></i></a>
            <a href="https://www.linkedin.com/company/taxmanagement"><i class="fab fa-linkedin social-icon"></i></a>
        </div>
    </div>
    <div class="footer-bottom">
        <B><p>&copy; 2024 Online Tax Management System. All rights reserved.</p></B>
    </div>
</footer>
</body>
</html>
