<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTMS - Simplifying Your Tax Journey</title>
    <style type="text/css">
        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
        }
        .logo {
            font-family: Arial, sans-serif;
            color: white;
            font-weight: bold;
            display: inline-flex;
            align-items: start;
           margin-left: 20px;
            font-size: 2em;
            line-height: 1.2;
            color: orange;
            padding-left: 0;
        }
        .main-header {
            background-color: #050A44;
            width: 100%;
    position: fixed;
    top: 0;
    width: 100%;
    z-index: 1000;
}

        }
        .nav {
            width: 100%;
        }
        .container-fluid {
            padding: 0; /* Remove padding to ensure full width */
            margin: 0 auto;
            max-width: 100%; /* Ensure container spans the full width */
        }
        ul {
            margin: 0;
            padding: 0;
            list-style: none;
        }
        .main-menu ul {
            display: flex;
            justify-content: flex-end;
        }
        .btn:hover {
    background-color: orange; /* Background color change on hover */
    color: white; /* Text color change on hover */
    border: 2px solid green; /* Optional: Border color change */
    transition: all 0.3s ease-in-out; /* Smooth transition effect */
    font-size: 1.5em;
        }

        .main-menu ul li {
            margin-right: 15px;
        }
        .button-header a {
            display: inline-block;
            margin: 5px;
            border: 2px solid white;
            padding: 10px 15px;
            text-decoration: none;
            color: white;
        }
    </style>
</head>
<body>
    <header>
        <!-- Header Start -->
        <div class="nav">
            <div class="header-area header-transparent">
                <div class="main-header">
                    <div class="header-bottom header-sticky">
                        <div class="container-fluid">
                            <div class="row align-items-center">
                                <!-- Logo -->
                                <div class="col-xl-2 col-lg-2">
                                    <div class="logo">
                                        <span class="logo-icon">
                                            <img src="assets/img/logo1.png" alt="Hero section image showing tax management system" width="100" height="90">
                                        </span>
                                    </div>
                                </div>
                                <div class="col-xl-10 col-lg-10">
                                    <div class="menu-wrapper d-flex align-items-center justify-content-end">
                                        <!-- Main-menu -->
                                        <div class="main-menu d-none d-lg-block">
                                            <nav>
                                                <ul id="navigation">                                          
                                                    <li class="button-header">
                                                        <a href="index.php" class="btn btn2">Home</a>
                                                    </li>                                                    <li class="button-header">
                                                        <a href="user/signin.php" class="btn btn2">User Log in</a>
                                                    </li>
                                                    <li class="button-header">
                                                        <a href="admin/signin.php" class="btn btn2">Admin Log in</a>
                                                    </li>
                                                    <li class="button-header">
                                                        <a href="includes/About_us.php" class="btn btn2">About Us</a>
                                                    </li>
                                                    <li class="button-header">
                                                        <a href="includes/contact_us.php" class="btn btn2">Contact Us</a>
                                                    </li>
                                                </ul>
                                            </nav>
                                        </div>
                                    </div>
                                </div> 
                                <!-- Mobile Menu -->
                                <div class="col-12">
                                    <div class="mobile_menu d-block d-lg-none"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Header End -->
    </header>
</body>
</html>
