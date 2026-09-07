<?php require_once "config/Dbconn.php"; ?>

<!-- Custom CSS -->
<style>
    .sidebar .nav-link {
        transition: 0.3s;
        font-size: 15px;
    }
    .sidebar .nav-link.active,
    .sidebar .nav-link:hover {
        background: #0d6efd;
        color: #fff !important;
    }
    .hover-effect:hover {
        transform: translateX(5px);
    }
</style>
<div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar vh-100 p-3 shadow-lg text-white" style="width:250px;">
        <!-- Logo -->
        <div class="text-center mb-4">
            <a href="index.php" class="d-block">
                <img src="img/Capture.png" class="rounded-circle img-fluid border border-light p-1" style="width:100px;" alt="Logo">
            </a>
            <h5 class="mt-2">My Dashboard</h5>
        </div>

        <!-- Navigation -->
        <nav class="nav flex-column">
            <a href="index.php" class="nav-link  py-2 px-3 rounded hover-effect">
                <i class="fa fa-home me-2"></i> Dashboard
            </a>
            <a href="daily_journal/journal.php" class="nav-link  py-2 px-3 rounded hover-effect">
                <i class="far fa-file-alt me-2"></i> Daily Journal
            </a>
            <a href="monthly_journal/mjournal.php" class="nav-link py-2 px-3 rounded hover-effect">
                <i class="far fa-calendar-alt me-2"></i> Monthly Journal
            </a>
            <a href="balance/mstatement.php" class="nav-link  py-2 px-3 rounded hover-effect">
                <i class="fas fa-balance-scale me-2"></i> GOLD Balance
            </a>
            <a href="storageblance.php" class="nav-link  py-2 px-3 rounded hover-effect">
                <i class="fas fa-warehouse me-2"></i> Storage GOLD Balance
            </a>
            <a href="silver_balance/mstatement.php" class="nav-link  py-2 px-3 rounded hover-effect">
                <i class="fas fa-balance-scale me-2"></i> Silver Balance
            </a>
            <a href="fix_profit.php" class="nav-link  py-2 px-3 rounded hover-effect">
                <i class="fas fa-chart-line me-2"></i> Fixing Profit
            </a>
            <a href="cus_qarz.php" class="nav-link  py-2 px-3 rounded hover-effect">
                <i class="fas fa-user-tag me-2"></i> Customer Liability
            </a>
        </nav>
    </div>

    <!-- Page Content -->
    <div class="flex-grow-1">
        <!-- Add your page content here -->


