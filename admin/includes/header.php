<!-- Navbar Start -->
<nav class="navbar navbar-expand sticky-top px-4 py-0" style="background-color: #050A44">
    <!-- Sidebar Toggler (placed at the start of navbar) -->
    <a href="#" class="sidebar-toggler flex-shrink-0 me-4"> <!-- Sidebar toggle at the start with margin -->
        <i class="fa fa-bars"></i>
    </a>

    <!-- Title Section (space between sidebar toggle and text) -->
    <h2 class="text-light ms-4">Online Tax Management System</h2> <!-- Added ms-4 class for space between title and toggler -->

    <div class="navbar-nav align-items-center ms-auto">
        <!-- New "Manage Tax Rates" item with icon -->
        <div class="nav-item">
            <a href="edit_rates.php" class="nav-link fw-bold text-light">
                Manage Tax Rates
            </a>
        </div>

        <div class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                <img class="rounded-circle me-lg-2" src="img/user.jpg" alt="" style="width: 40px; height: 40px;">
                <?php
                $uid = $_SESSION['ocasaid'];
                $sql = "SELECT * from tbluser where ID=:uid";
                $query = $dbh->prepare($sql);
                $query->bindParam(':uid', $uid, PDO::PARAM_STR);
                $query->execute();
                $results = $query->fetchAll(PDO::FETCH_OBJ);
                $cnt = 1;
                if ($query->rowCount() > 0) {
                    foreach ($results as $row) {
                ?>
                <span class="d-none d-lg-inline-flex"><?php echo $row->FullName; ?></span>
                <?php $cnt = $cnt + 1; } } ?>
            </a>
            <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                <a href="profile.php" class="dropdown-item">My Profile</a>
                <a href="setting.php" class="dropdown-item">Change Password</a>
                <a href="logout.php" class="dropdown-item">Log Out</a>
            </div>
        </div>
    </div>
</nav>
<!-- Navbar End -->
