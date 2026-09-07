<style>
    /* === Base Navbar === */
    .navbar {
        background-color: #1e1e2f;
        padding: 0.8rem 1.5rem;
    }

    .navbar h6 {
        color: #fff;
        margin: 0;
        font-weight: bold;
    }

    .navbar-nav .nav-link {
        color: #fff;
        font-weight: 500;
        margin-right: 1rem;
        transition: color 0.3s ease;
    }

    .navbar-nav .nav-link:hover {
        color: #ffd700;
    }

    /* === Full-width Dropdown === */
    .dropdown-full {
        position: static !important;
    }

    .dropdown-menu.fullwidth {
        width: 100%;
        left: 0;
        right: 0;
        top: 100%;
        border: none;
        border-radius: 0;
        background: #f8f8f8;
        margin-top: 0;
        box-shadow: 0 4px 25px rgba(0, 0, 0, 0.15);
        padding: 0;
        animation: fadeIn 0.3s ease-in-out;
    }

    .dropdown-content {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        width: 100%;
    }

    /* === Left Description Section === */
    .dropdown-left {
        background-color: #f1f1f1;
        padding: 2rem;
        border-right: 1px solid #ddd;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .dropdown-left img {
        width: 100%;
        border-radius: 8px;
        margin-bottom: 1rem;
    }

    .dropdown-left p {
        font-size: 1rem;
        color: #333;
        margin-bottom: 1rem;
    }

    .dropdown-left a.btn {
        color: #ff6600;
        font-weight: bold;
        text-decoration: none;
    }

    .dropdown-left a.btn:hover {
        color: #cc5200;
    }

    /* === Middle and Right Columns === */
    .dropdown-col {
        padding: 2rem;
        background: #fff;
    }

    .dropdown-col h5 {
        font-size: 1.2rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: #333;
        border-bottom: 2px solid #ff6600;
        display: inline-block;
        padding-bottom: 0.3rem;
    }

    .dropdown-col a {
        display: block;
        color: #333;
        font-size: 0.95rem;
        text-decoration: none;
        padding: 0.4rem 0;
        transition: all 0.3s ease;
    }

    .dropdown-col a i {
        color: #ff6600;
        margin-right: 8px;
    }

    .dropdown-col a:hover {
        color: #ff6600;
        transform: translateX(5px);
    }

    /* Animation */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>


<nav class="navbar navbar-expand-lg sticky-top shadow-sm bg-primary">
    <a href="#" class="sidebar-toggler flex-shrink-0">
        <i class="fa fa-bars"></i>
    </a>
    <h6> Jewellery MIS</h6>

    <div class="collapse navbar-collapse">
        <ul class="navbar-nav me-auto">

            <!-- ================= GOLD FULL-WIDTH MENU ================= -->
            <li class="nav-item dropdown dropdown-full">
                <a class="nav-link dropdown-toggle text-white" href="#" data-bs-toggle="dropdown">
                    <i class="fa fa-gem me-2 text-primary"></i> Gold
                </a>
                <div class="dropdown-menu fullwidth">
                    <div class="dropdown-content">
                        <!-- MIDDLE: CUSTOMER -->
                        <div class="dropdown-col">
                            <h5><i class="fa fa-users me-2"></i>Gold Customer</h5>
                            <a href="./customer/cus.php?new=new" class="dropdown-item"><i class="fa fa-user-plus me-2"></i>Add new Customer with New Transiction</a>
                            <a href="./customer/select.php" class="dropdown-item"><i class="fa fa-plus me-2"></i>Add Gold Transiction of Exist Customer</a>
                            <a href="./customer/select_cus.php" class="dropdown-item"><i class="fa fa-plus me-2"></i>Add Money Transiction of Exist Customer</a>
                            <a href="./customer/des_select.php" class="dropdown-item"><i class="fa fa-plus me-2"></i>Add Discount Money Transiction of Exist Customer</a>
                            <a href="./customer/custocus.php" class="dropdown-item"><i class="fa fa-plus me-2"></i>Transiction of Money From Customer To Customer</a>
                            <a href="./customer/fromcus.php" class="dropdown-item"><i class="fa fa-plus me-2"></i>Transiction of Money From Customer To Supplier (Money Supplier, Silver Supplier, Gold Supplier)</a>
                        </div>

                        <!-- RIGHT: SUPPLIER -->
                        <div class="dropdown-col">
                            <h5><i class="fa fa-sun me-2"></i>Gold Supplier</h5>
                            <a href="./gold_supplier/sup.php" class="dropdown-item"><i class="fa fa-user-plus me-2"></i>Add New Gold Supplier with New Transiction</a>
                            <!-- <a href="sup_dep.php" class="btn btn-dark my-2 w-100"><i class="fa fa-plus me-2"></i>Add Credit Supplier Money نام پول </a> -->
                            <a href="./gold_supplier/select.php" class="dropdown-item"><i class="fa fa-plus me-2"></i>Add Gold Transition Of Exist Gold Supplier</a>
                            <a href="./gold_supplier/select_cus.php" class="dropdown-item"><i class="fa fa-plus me-2"></i>Add Premium Money Transiction of Exist Gold Supplier</a>
                            <a href="./gold_supplier/tosup.php" class="dropdown-item"><i class="fa fa-plus me-2"></i>Gold Transiction From Gold Supplier to Gold Supplier</a>
                            <a href="./gold_supplier/fromcus.php" class="dropdown-item"><i class="fa fa-plus me-2"></i>Money Transiction From Gold Supplier To Gold Supplier</a>
                        </div>
                        <div class="dropdown-col">
                            <h5><i class="fa fa-file me-2"></i>Fixing</h5>
                            <a href="./fixing/fix.php?new=new" class="dropdown-item"><i class="fa fa-user-plus me-2"></i>Add Fixing of New Customer</a>
                            <a href="./fixing/fix.php" class="dropdown-item"><i class="fa fa-plus me-2"></i>Add Fixing of Exist Customer Or Gold Supplier</a>
                        </div>
                    </div>
                </div>
            </li>

            <li class="nav-item dropdown dropdown-full">
                <a class="nav-link dropdown-toggle text-white" href="#" data-bs-toggle="dropdown">
                    <i class="fa fa-gem me-2 text-primary"></i> Silver
                </a>

                <div class="dropdown-menu fullwidth">
                    <div class="dropdown-content">
                        <!-- MIDDLE: CUSTOMER -->
                        <div class="dropdown-col">
                            <h5><i class="fa fa-users me-2"></i>Silver Customer</h5>
                            <a href="./silver_customer/cus.php?new=new"><i class="fa fa-user-plus me-2"></i>Add new Customer with New Transaction</a>
                            <a href="./silver_customer/select.php"><i class="fa fa-plus me-2"></i>Add Silver Transaction of Existing Customer</a>
                            <a href="./silver_customer/custocussilver.php"><i class="fa fa-plus me-2"></i>Transaction of Silver From Customer → Customer</a>
                            <a href="./silver_customer/select_cus.php"><i class="fa fa-plus me-2"></i>Add Money Transaction of Existing Customer</a>
                            <a href="./silver_customer/des_select.php"><i class="fa fa-plus me-2"></i>Add Discount Money Transaction</a>
                            <a href="./silver_customer/custocus.php"><i class="fa fa-plus me-2"></i>Transaction of Money From Customer → Customer</a>
                            <a href="./silver_customer/fromcus.php"><i class="fa fa-plus me-2"></i>Transaction of Money From Customer → Supplier (Money Supplier, Silver Supplier, Gold Supplier)</a>
                        </div>

                        <!-- RIGHT: SUPPLIER -->
                        <div class="dropdown-col">
                            <h5><i class="fa fa-sun me-2"></i>Silver Supplier</h5>
                            <a href="./silver_supplier/cus.php?new=new"><i class="fa fa-user-plus me-2"></i>Add new Silver with New Transaction</a>
                            <a href="./silver_supplier/select.php"><i class="fa fa-plus me-2"></i>Add Silver Transaction of Existing Silver</a>
                            <a href="./silver_supplier/select_cus.php"><i class="fa fa-plus me-2"></i>Add Money Transaction of Existing Silver</a>
                            <a href="./silver_supplier/des_select.php"><i class="fa fa-plus me-2"></i>Add Premium Money Transaction</a>
                            <a href="./silver_supplier/fromcus.php"><i class="fa fa-plus me-2"></i>Transaction of Money From Silver Supplier → Supplier</a>
                        </div>
                        <div class="dropdown-col">
                            <h5><i class="fa fa-file me-2"></i>Silver Fixing</h5>
                            <a href="./silver_fixing/fix.php?new=new" class="dropdown-item"><i class="fa fa-user-plus me-2"></i>Add Fixing of New Customer</a>
                            <a href="./silver_fixing/fix.php" class="dropdown-item"><i class="fa fa-plus me-2"></i>Add Fixing of Exist Customer Or silver Supplier</a>
                        </div>

                    </div>
                </div>
            </li>
 <!-- OFFICE MENU -->
            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle text-white" data-bs-toggle="dropdown">
                    <i class="fa-solid fa-wallet me-2 text-primary"></i> Money Supplier
                </a>
                <ul class="dropdown-menu shadow-lg border-0 rounded-3">
                    <li><a href="./money_supplier/moneysup.php" class="dropdown-item"><i class="fa fa-circle-plus me-2"></i>Add New Money Supplier</a></li>
                    <li><a href="./money_supplier/select.php" class="dropdown-item"><i class="fa fa-circle-minus me-2"></i>Add Transaction</a></li>
                     <li><a href="./money_supplier/exmsup.php" class="dropdown-item"><i class="fa fa-circle-minus me-2"></i>Add Extra Supplier Transaction</a></li>
                </ul>
            </li>
            <!-- OFFICE MENU -->
            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle text-white" data-bs-toggle="dropdown">
                    <i class="fa fa-building me-2 text-primary"></i> Office
                </a>
                <ul class="dropdown-menu shadow-lg border-0 rounded-3">
                    <li><a href="./Office/office.php" class="dropdown-item"><i class="fa fa-circle-plus me-2"></i>Add Credit</a></li>
                    <li><a href="./Office/office_deb.php" class="dropdown-item"><i class="fa fa-circle-minus me-2"></i>Add Debit</a></li>
                </ul>
            </li>

            <!-- STORAGE MENU -->
            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle text-white" data-bs-toggle="dropdown">
                    <i class="fa fa-chart-bar me-2 text-success"></i> Storage
                </a>
                <ul class="dropdown-menu shadow-lg border-0 rounded-3">
                    <li><a href="./storage/office.php" class="dropdown-item"><i class="fa fa-circle-plus me-2"></i>Add Credit</a></li>
                    <li><a href="./storage/office_deb.php" class="dropdown-item"><i class="fa fa-circle-minus me-2"></i>Add Debit</a></li>
                </ul>
            </li>
        </ul>
    </div>
</nav>