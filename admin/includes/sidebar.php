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
            <img src="../assets/img/logo1.png" alt="Logo" style="height: 120px; width: auto;">
        </a>

        <!-- User Profile Section -->
        <div class="d-flex align-items-center ms-4 mb-4">
            <div class="position-relative">
                <img class="rounded-circle" src="img/user.jpg" alt="User" style="width: 45px; height: 45px;">
                <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
            </div>
            <div class="ms-3 text-light">
                <?php
                $uid = $_SESSION['ocasaid'];  // Corrected session key to match the backend of file2
                $sql = "SELECT * from tbluser WHERE ID = :uid";
                $query = $dbh->prepare($sql);
                $query->bindParam(':uid', $uid, PDO::PARAM_STR);
                $query->execute();
                $results = $query->fetchAll(PDO::FETCH_OBJ);
                if ($query->rowCount() > 0) {
                    foreach ($results as $row) {
                ?>
                <h6 class="mb-0"><?php echo $row->FullName; ?></h6>
                <small><?php echo $row->Email; ?></small>
                <?php }} ?>
            </div>
        </div>

        <!-- Navigation Menu -->
        <div class="navbar-nav w-100 text-light">
            <!-- Dashboard -->
            <a href="dashboard.php" class="nav-item nav-link text-light"><i class="fa fa-tachometer-alt me-2 text-dark"></i>Dashboard</a>

            <!-- New Applications -->
            <div class="nav-item">
                <a href="newapplication.php" class="nav-item nav-link text-light"><i class="fa fa-file-alt me-2 text-dark"></i>New Applications</a>
            </div>

            <!-- Old Applications -->
            <div class="nav-item">
                <a href="manageapplication.php" class="nav-item nav-link text-light"><i class="fa fa-folder-open me-2 text-dark"></i>All Applications</a>
            </div>

            <!-- Send Notifications -->
            <div class="nav-item">
                <a href="send_notifications.php" class="nav-item nav-link text-light"><i class="fas fa-bell me-2 text-dark"></i>Send Notifications</a>
            </div>

            <!-- Admin Management -->
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle text-light" data-bs-toggle="dropdown"><i class="fa fa-cogs me-2 text-dark"></i>Management</a>
                <div class="dropdown-menu bg-secondary border-0">
                    <a href="mnguaccount.php" class="dropdown-item"><i class="fa fa-users me-2"></i>Tax Payer Accounts</a>
                    <a href="edit_rates.php" class="dropdown-item"><i class="fa fa-percentage me-2"></i>Tax Rates</a>
                    <a href="admin_payments.php" class="dropdown-item"><i class="fa fa-wallet me-2"></i>Tax Returns</a>
                    <a href="manage_OldNotifications.php" class="dropdown-item"><i class="fa fa-bell me-2"></i>Custom Notifications</a>
                    <a href="manage_alerts.php" class="dropdown-item"><i class="fa fa-percentage me-2"></i>System Alerts</a>
                    <a href="user_quries.php" class="dropdown-item"><i class="fas fa-question-circle"></i>User Quries</a>

                </div>
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
