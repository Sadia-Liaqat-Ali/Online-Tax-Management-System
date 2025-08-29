<!-- Spinner Start -->
<div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
        <span class="sr-only">Loading...</span>
    </div>
</div>
<!-- Spinner End -->

<!-- Sidebar Start -->
<div class="sidebar pe-4 pb-3" style="background-color: #050A44;">
    <nav class="navbar bg-transparent navbar-light">
        <!-- Logo Section -->
        <a href="dashboard.php" class="navbar-brand mx-4 mb-3">
            <img src="../assets/img/logo1.png" alt="Logo" style="height: 120px; width: 130px;">
        </a>

        <!-- User Profile Section -->
        <div class="d-flex align-items-center ms-4 mb-4">
            <div class="position-relative">
                <img class="rounded-circle" src="img/user.jpg" alt="User" style="width: 45px; height: 45px;">
                <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
            </div>
            <div class="ms-3 text-light">
                <?php
                $uid=$_SESSION['ocasuid'];
                $sql="SELECT * FROM tbluser WHERE ID=:uid";
                $query = $dbh->prepare($sql);
                $query->bindParam(':uid', $uid, PDO::PARAM_STR);
                $query->execute();
                $results=$query->fetchAll(PDO::FETCH_OBJ);
                if ($query->rowCount() > 0) {
                    foreach ($results as $row) {
                ?>
                <h6 class="mb-0 text-light"><?php echo $row->FullName; ?></h6>
                <small><?php echo $row->Email; ?></small>
                <?php }} ?>
            </div>
        </div>

        <!-- Navigation Menu -->
        <div class="navbar-nav w-100 text-light">
            <!-- Dashboard -->
            <a href="dashboard.php" class="nav-item nav-link text-light"><i class="fa fa-tachometer-alt me-2 text-dark"></i>Dashboard</a>


            <!-- Add Applications -->
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle text-light" data-bs-toggle="dropdown"><i class="fas fa-plus-square me-2 text-dark"></i>Add Applications</a>
                <div class="dropdown-menu bg-secondary border-0">
                    <a href="addincometax.php" class="dropdown-item"><i class="fas fa-file-invoice-dollar me-2"></i>Add Income Tax</a>
                    <a href="addsalestax.php" class="dropdown-item"><i class="fas fa-file-invoice me-2"></i>Add Sales Tax</a>
                    <a href="addpropertytax.php" class="dropdown-item"><i class="fas fa-home me-2"></i>Add Property Tax</a>
                </div>
            </div>


                <!-- Tax Returns -->
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle text-light" data-bs-toggle="dropdown"><i class="fa fa-money-bill me-2 text-dark"></i>Tax Returns</a>
                <div class="dropdown-menu bg-secondary border-0">
                    <a href="payment.php" class="dropdown-item"><i class="fas fa-wallet me-2 text-dark"></i>File Tax Returns</a>
                    <a href="old_returns.php" class="dropdown-item"><i class="fas fa-history me-2 text-dark"></i>Check Old Returns</a>
                </div>
            </div>

            <!-- Manage Applications -->
            <div class="nav-item dropdown">
                <a href="manageincometax.php" class="nav-link text-light"><i class="fas fa-tasks me-2 text-dark"></i>Old Applications</a>
                
            </div>


            <!-- Notifications -->
            <div class="nav-item">
                <a href="viewnotifications.php" class="nav-item nav-link text-light"><i class="fas fa-bell me-2 text-dark"></i>Notifications</a>
            </div>

             <!-- Alerts -->
            <div class="nav-item">
                <a href="view_Alerts.php" class="nav-item nav-link text-light"><i class="bi bi-alarm me-2 text-dark"></i>Reminders</a>
            </div>
        </div>
    </nav>
</div>
<!-- Sidebar End -->

<!-- Custom Styles -->
<style>
    .sidebar {
        min-height: 100vh;
    }

    .nav-item:hover .nav-link,
    .dropdown-item:hover {
        color: #f1c40f !important;
    }

    .nav-item .dropdown-menu {
        border-radius: 8px;
    }

</style>
