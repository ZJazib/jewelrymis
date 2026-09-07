<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>ZMIS</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Admin Penal" name="keywords">
    <meta content="Admin Penal" name="description">

    <!-- Favicon -->
    <link href="favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/vfs_fonts.js"></script>

    <!-- Template Stylesheet -->
    <script src="../ckeditor/ckeditor.js"></script>
    <link href="../css/style.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="bg-light">
    <!-- Sidebar Start -->
    <?php
    require_once "../config/Dbconn.php";
    ?>

    <?php

    // New Silver Customer fixing
    if (isset($_POST['innew'])) {
        $name = $_POST['name'];
        $nic = $_POST['nic'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $loc = $_POST['loc'];
        $ref = $_POST['ref'];


        $cus = "INSERT INTO `customer`(`name`, `phone`, `email`, `nic`, `ref`, `loc`, `role`) VALUES ('$name','$phone','$email','$nic','$ref','$loc','Silver Customer')";
        $res = mysqli_query($conn, $cus);

        if (!$res) {
            echo "Error: " . mysqli_error($conn);
        } else {
            $cuss = "SELECT * FROM customer WHERE `name`='$name' And `phone`='$phone'";
            $ress = mysqli_query($conn, $cuss);
            $userc = mysqli_fetch_assoc($ress);
            $id = $userc['id'];

            $name = $userc['name'];
            $ref = $_POST['ref'];
            $price = 3.67;
            $gold = $_POST['gold'];
            $own = $_POST['own'];
            $total = $_POST['total'];
            $type = $_POST['type'];

            if ($type == "Buy") {
                if ($userc['role'] == "Silver Customer") {
                    $cre = "INSERT INTO `fixing`(`fi_cus`, `ref`, `fi_gold`, `fi_price`, `fi_owns`, `drrate`, `total`,`money_state` ,`gold_state`,`typec`) VALUES ('$id','$ref','$gold','$price','$own','$price','$total','Debit','Credit','Buy')";
                    mysqli_query($conn, $cre);

                    $amoc = "INSERT INTO `account`(`cus_id`, `ref`, `amount`, `amo_cre`, `gold`, `role`, `state`, `type`, `com`) VALUES ('$id','$ref','0','0','$gold','Silver Customer','Cash','Debit','0')";
                    mysqli_query($conn, $amoc);
                    $amo = "INSERT INTO `account`(`cus_id`, `ref`, `amount`, `amo_cre`, `gold`, `role`, `state`, `type`, `com`) VALUES ('$id','$ref','0','$total','0','Silver Customer','Cash','Credit','0')";
                    mysqli_query($conn, $amo);

                    $sta = "INSERT INTO `statement`(`se_cus`,`ref`, `se_tgolg`, `se_tpurity`, `se_tbill`, `se_type`, `se_method`, `dis`) VALUES ('$id','$ref',0,'$gold','0','Credit','Cash', 'Fixed GOLD ISSUED TO $ref FOR $name WT- $gold GMS IN OUNCE $own')";
                    mysqli_query($conn, $sta);

                    $staf = "INSERT INTO `statement`(`se_cus`,`ref`, `se_tgolg`, `se_tpurity`, `se_tbill`, `se_type`, `se_method`, `dis`) VALUES ('$id','$ref',0,'0','$total','Debit','Cash', 'Fixed GOLD ISSUED TO $ref FOR $name WT- $gold GMS IN OUNCE $own')";
                    mysqli_query($conn, $staf);
                }
                if ($userc['role'] == "Silver Supplier") {
                    $cre = "INSERT INTO `fixing`(`fi_cus`, `ref`, `fi_gold`, `fi_price`, `fi_owns`, `drrate`, `total`,`money_state` ,`gold_state`,`typec`) VALUES ('$id','$ref','$gold','$price','$own','$price','$total','Credit','Debit','Buy')";
                    mysqli_query($conn, $cre);

                    $amoc = "INSERT INTO `account`(`cus_id`, `ref`, `amount`, `amo_cre`, `gold`, `role`, `state`, `type`, `com`) VALUES ('$id','$ref','0','$total','0','Silver Supplier','Cash','Credit','0')";
                    mysqli_query($conn, $amoc);

                    $amo = "INSERT INTO `account`(`cus_id`, `ref`, `amount`, `amo_cre`, `gold`, `role`, `state`, `type`, `com`) VALUES ('$id','$ref','0','0','$gold','Silver Supplier','Cash','Debit','0')";
                    mysqli_query($conn, $amo);
                    $sta = "INSERT INTO `statement`(`se_cus`,`ref`, `se_tgolg`, `se_tpurity`, `se_tbill`, `se_type`, `se_method`, `dis`) VALUES ('$id','$ref',0,'$gold','0','Debit','Cash', 'Fixed GOLD ISSUED TO $ref FOR $name WT- $gold GMS IN OUNCE $own')";
                    mysqli_query($conn, $sta);

                    $staf = "INSERT INTO `statement`(`se_cus`,`ref`, `se_tgolg`, `se_tpurity`, `se_tbill`, `se_type`, `se_method`, `dis`) VALUES ('$id','$ref',0,'0','$total','Credit','Cash', 'Fixed GOLD ISSUED TO $ref FOR $name WT- $gold GMS IN OUNCE $own')";
                    mysqli_query($conn, $staf);
                }
            }

            if ($type == "Sell") {
                if ($userc['role'] == "Silver Customer") {
                    $crec = "INSERT INTO `fixing`(`fi_cus`, `ref`, `fi_gold`, `fi_price`, `fi_owns`, `drrate`, `total`,`money_state` ,`gold_state`,`typec`) VALUES ('$id','$ref','$gold','$price','$own','$price','$total','Credit','Debit','Sell')";
                    mysqli_query($conn, $crec);

                    $amocc = "INSERT INTO `account`(`cus_id`, `ref`, `amount`, `amo_cre`, `gold`, `role`, `state`, `type`, `com`) VALUES ('$id','$ref','0','0','$gold','Silver Customer','Cash','Credit','0')";
                    mysqli_query($conn, $amocc);
                    $amoccc = "INSERT INTO `account`(`cus_id`, `ref`, `amount`, `amo_cre`, `gold`, `role`, `state`, `type`, `com`) VALUES ('$id','$ref','0','$total','0','Silver Customer','Cash','Debit','0')";
                    mysqli_query($conn, $amoccc);

                    $stac = "INSERT INTO `statement`(`se_cus`,`ref`, `se_tgolg`, `se_tpurity`, `se_tbill`, `se_type`, `se_method`, `dis`) VALUES ('$id','$ref',0,'$gold','0','Debit','Cash', 'Fixed GOLD ISSUED TO $ref FOR $name WT- $gold GMS IN OUNCE $own')";
                    mysqli_query($conn, $stac);

                    $staf = "INSERT INTO `statement`(`se_cus`,`ref`, `se_tgolg`, `se_tpurity`, `se_tbill`, `se_type`, `se_method`, `dis`) VALUES ('$id','$ref',0,'0','$total','Credit','Cash', 'Fixed GOLD ISSUED TO $ref FOR $name WT- $gold GMS IN OUNCE $own')";
                    mysqli_query($conn, $staf);
                }
                if ($userc['role'] == "Silver Supplier") {
                    $cres = "INSERT INTO `fixing`(`fi_cus`, `ref`, `fi_gold`, `fi_price`, `fi_owns`, `drrate`, `total`,`money_state` ,`gold_state`,`typec`) VALUES ('$id','$ref','$gold','$price','$own','$price','$total','Debit','Credit','Sell')";
                    mysqli_query($conn, $cres);

                    $amocs = "INSERT INTO `account`(`cus_id`, `ref`, `amount`, `amo_cre`, `gold`, `role`, `state`, `type`, `com`) VALUES ('$id','$ref','0','$total','0','Silver Supplier','Cash','Debit','0')";
                    mysqli_query($conn, $amocs);

                    $amos = "INSERT INTO `account`(`cus_id`, `ref`, `amount`, `amo_cre`, `gold`, `role`, `state`, `type`, `com`) VALUES ('$id','$ref','0','0','$gold','Silver Supplier','Cash','Credit','0')";
                    mysqli_query($conn, $amos);
                    $stas = "INSERT INTO `statement`(`se_cus`,`ref`, `se_tgolg`, `se_tpurity`, `se_tbill`, `se_type`, `se_method`, `dis`) VALUES ('$id','$ref',0,'$gold','0','Debit','Cash', 'Fixed GOLD ISSUED TO $ref FOR $name WT- $gold GMS IN OUNCE $own')";
                    mysqli_query($conn, $stas);

                    $stasf = "INSERT INTO `statement`(`se_cus`,`ref`, `se_tgolg`, `se_tpurity`, `se_tbill`, `se_type`, `se_method`, `dis`) VALUES ('$id','$ref',0,'0','$total','Credit','Cash', 'Fixed GOLD ISSUED TO $ref FOR $name WT- $gold GMS IN OUNCE $own')";
                    mysqli_query($conn, $stasf);
                }
            }
        }
    }


    if (isset($_POST['in'])) {
        $ref = $_POST['ref'];
        $price = 3.67;
        $gold = $_POST['gold'];
        $own = $_POST['own'];
        $total = $_POST['total'];
        $id = isset($_GET['t']) ? intval($_GET['t']) : 0; // Validating 't' parameter as integer
        $type = $_POST['type'];

        // Ensure the connection and 'id' value is valid
        if ($id > 0) {
            $sql = "SELECT * FROM `customer` WHERE id = $id";
            $res = mysqli_query($conn, $sql);
            $cus = mysqli_fetch_assoc($res);

            if ($cus) {
                $role = $cus['role'];

                // Common insert for fixing table
                $insertFixing = function ($Money_state, $gold_state, $typec) use ($id, $ref, $gold, $price, $own, $total, $conn) {
                    $cre = "INSERT INTO `fixing`(`fi_cus`, `ref`, `fi_gold`, `fi_price`, `fi_owns`, `drrate`, `total`, `money_state`,  `gold_state`, `typec`)
                        VALUES ('$id', '$ref', '$gold', '$price', '$own', '$price', '$total', '$Money_state','$Money_state,', '$typec')";
                    return mysqli_query($conn, $cre);
                };

                // Common insert for account table
                $insertAccount = function ($amount, $amo_cre, $goldVal, $role, $state, $type) use ($id, $ref, $conn) {
                    $amo = "INSERT INTO `account`(`cus_id`, `ref`, `amount`, `amo_cre`, `gold`, `role`, `state`, `type`, `com`)
                        VALUES ('$id', '$ref', '$amount', '$amo_cre', '$goldVal', '$role', '$state', '$type', '0')";
                    return mysqli_query($conn, $amo);
                };

                // Common insert for statement table
                $insertStatement = function ($goldVal, $bill, $type, $dis) use ($id, $ref, $conn) {
                    $sta = "INSERT INTO `statement`(`se_cus`, `ref`, `se_tgolg`, `se_tpurity`, `se_tbill`, `se_type`, `se_method`, `dis`)
                        VALUES ('$id', '$ref', NULL, '$goldVal', '$bill', '$type', 'Cash', '$dis')";
                    return mysqli_query($conn, $sta);
                };

                // Handling received or sent transactions based on type
                if ($type == "Buy") {
                    if ($role == "Silver Customer") {
                        $insertFixing('Debit', 'Credit', 'Buy');
                        $insertAccount(0, 0, $gold, 'Silver Customer', 'Cash', 'Credit');
                        $insertAccount($total, 0, 0, 'Silver Customer', 'Cash', 'Debit');
                        $insertStatement($gold, 0, 'Credit', "Purchase Fixing ($gold) @$$own GOLD");
                        $insertStatement(0, $total, 'Debit', "Purchase Fixing ($gold) @$$own MONEY");
                    } elseif ($role == "Silver Supplier") {
                        $insertFixing('Credit', 'Debit', 'Buy');
                        $insertAccount(0, $total, 0, 'Silver Supplier', 'Cash', 'Credit');
                        $insertAccount(0, 0, $gold, 'Silver Supplier', 'Cash', 'Debit');
                        $insertStatement($gold, 0, 'Debit', "Purchase Fixing ($gold) @$$own GOLD");
                        $insertStatement(0, $total, 'Credit', "Purchase Fixing ($gold) @$$own MONEY");
                    }
                } elseif ($type == "Sell") {
                    if ($role == "Silver Customer") {
                        $insertFixing('Credit', 'Debit', 'Sell');
                        $insertAccount(0, 0, $gold, 'Silver Customer', 'Cash', 'Debit');
                        $insertAccount(0, $total, 0, 'Silver Customer', 'Cash', 'Credit');
                        $insertStatement($gold, 0, 'Debit', "Sales Fixing ($gold) @$$own GOLD");
                        $insertStatement(0, $total, 'Credit', "Sales Fixing ($gold) @$$own MONEY");
                    } elseif ($role == "Silver Supplier") {
                        $insertFixing('Debit', 'Credit', 'Sell');
                        $insertAccount(0, $total, 0, 'Silver Supplier', 'Cash', 'Debit');
                        $insertAccount(0, 0, $gold, 'Silver Supplier', 'Cash', 'Credit');
                        $insertStatement($gold, 0, 'Credit', "Sales Fixing ($gold) @$$own GOLD");
                        $insertStatement(0, $total, 'Debit', "Sales Fixing ($gold) @$$own MONEY");
                    }
                }
            } else {
                // Handle customer not found case
                error_log("Silver Customer with ID $id not found.");
            }
        } else {
            // Handle invalid ID case
            error_log("Invalid customer ID: $id.");
        }
    }
    ?>


    <?php
    if (isset($_GET['t'])) {
        $id = $_GET['t'];
        $sqlcus = "SELECT * FROM `customer` where id =" . $_GET['t'];
        $rescus = mysqli_query($conn, $sqlcus);
        $empp = mysqli_fetch_assoc($rescus);

        $sqli = "SELECT SUM(se_tbill) as total FROM `statement` where `se_type`='Credit' and  se_cus =" . $_GET['t'];
        $resi = mysqli_query($conn, $sqli);
        $cusi = mysqli_fetch_assoc($resi);
        $sql = "SELECT SUM(se_tbill) as totalc FROM `statement` where `se_type`='Debit' and se_cus =" . $_GET['t'];
        $res = mysqli_query($conn, $sql);
        $cus = mysqli_fetch_assoc($res);
        $sqlcc = "SELECT SUM(se_tpurity) as totalcc FROM `statement` where `se_type`='Credit' and  se_cus=" . $_GET['t'];
        $resc = mysqli_query($conn, $sqlcc);
        $cusc = mysqli_fetch_assoc($resc);


        $sqlkg = "SELECT SUM(se_tpurity) as totalkg FROM `statement` where `se_type`='Debit' and `se_cus`=" . $_GET['t'];
        $reskg = mysqli_query($conn, $sqlkg);
        $cuskg = mysqli_fetch_assoc($reskg);

    ?>
        <div class="container-fluid ">
            <div class="rounded align-items-center bg-light justify-content-center mx-0">
                <div class="container py-5 ">
                    <div class="row">
                        <?php if (isset($ero)) {
                            @$ero;
                        }
                        if (isset($er)) {
                            @$er;
                        }
                        ?>
                        <div class="col-md-3 bg-dark rounded m-3 ">
                            <h5 class="bg-white p-2 text-primary text-center">Personnel Information</h5>
                            <h6 class="text-white px-4 ">Role: <span> <?= $empp['role']; ?></span> </h6>
                            <h6 class="text-white px-4 ">Name: <span> <?= $empp['name']; ?></span> </h6>
                            <h6 class="text-white px-4 ">Location: <span> <?= $empp['loc']; ?></span> </h6>
                        </div>
                        <div class="col-md-4 bg-dark rounded m-3">
                            <h5 class="bg-white p-2 text-primary text-center">Total Money In Account</h5>
                            <h5 class="text-white px-4 ">Debit Money: <span> <?= number_format(@$cusi['total'], 3) ?> </span> USD ($)</h5>
                            <h5 class="text-white px-4 ">Credit Money: <span> <?= number_format(@$cus['totalc'], 3) ?> </span> USD ($)</h5>
                            <h5 class="text-white ">
                                <?php

                                if ($cusi['total'] > $cus['totalc']) {
                                    echo "<h5 class='text-white px-4 '>Total Debit: <span>", number_format(@$cusi['total'] - $cus['totalc'], 3);
                                    # code...
                                } else {
                                    echo "<h5 class='text-white px-4 '>Total Credit: <span>",  number_format(@$cus['totalc'] - $cusi['total'], 3);
                                    # code...
                                }
                                ?> USD ($)</h5>
                        </div>
                        <div class="col-md-4 bg-dark rounded m-3">
                            <h5 class="bg-white p-2 text-primary text-center">Total Silver In Account</h5>
                            <?php
                            $totalcc = floatval($cusc['totalcc']);
                            $totalkg = floatval($cuskg['totalkg']);
                            ?>
                            <h5 class="text-white px-4">Debit Silver: <span> <?= number_format($totalkg, 3) ?> </span> (KGS)</h5>
                            <h5 class="text-white px-4">Credit Silver: <span> <?= number_format($totalcc, 3) ?> </span> (KGS)</h5>
                            <h5 class="text-white ">
                                <?php

                                if ($totalkg > $totalcc) {
                                    echo "<span class='text-white mx-4'>Total Debit: ", number_format($totalkg - $totalcc, 3), "</span>";
                                    # code...
                                } else {
                                    echo "<span class='px-4 text-white p-2 text-center w-100'>Total Credit: ", number_format($totalcc - $totalkg, 3), "</span>";
                                    # code...
                                } ?>(KGS)</h5>


                        </div>
                    </div>
                    <div class="container-fluid pt-4 px-5">
                        <div class=" rounded align-items-center bg-light justify-content-center mx-0">
                            <div class="container py-2">

                                <div class="row">
                                    <div class="col-md-12">
                                        <form action="" method="post" enctype="multipart/form-data">
                                            <div class="card">
                                                <div class="card-header text-center bg-primary ">
                                                    <h3 class="text-white mt-2">Fixing</h3>
                                                </div>
                                                <script>
                                                    function calculateTotals() {
                                                        var weight = parseFloat(document.getElementById("gold").value);
                                                        var own = parseFloat(document.getElementById("own").value);
                                                        var total = own / 31.1035 * 3.674 / 3.67 * 1000 * weight;

                                                        document.getElementById("total").value = total.toFixed(2);

                                                    }
                                                </script>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-4 my-2">
                                                            <div class="form-group">
                                                                <label class="text-primary" for="Name">Silver Customer Name</label>
                                                                <input type="text" class="form-control" id="Name" value="<?= $empp['name'] ?>" name="name" placeholder="Write Name Here">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 my-2">
                                                            <div class="form-group">
                                                                <label class="text-primary" for="Reference">Reference</label>
                                                                <input type="text" class="form-control" id="Reference" value="" name="ref" placeholder="Write Reference Name Here">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 my-2">
                                                            <div class="form-group">
                                                                <label class="text-primary" for="gold">Gold Weight Grams وزن</label>
                                                                <input type="text" class="form-control" id="gold" value="" name="gold" oninput="calculateTotals()" placeholder="Write Gold Weight Grams Here">
                                                            </div>
                                                        </div>


                                                        <div class="col-md-4 my-2">
                                                            <div class="form-group">
                                                                <label class="text-primary" for="own">Owns اونس</label>
                                                                <input type="text" class="form-control" id="own" value="" oninput="calculateTotals()" name="own" placeholder="Write Owns Here">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 my-2">
                                                            <div class="form-group">
                                                                <label class="text-primary" for="total">Total Fixed Price</label>
                                                                <input type="text" class="form-control" id="total" name="total" placeholder="See Total Money Here">
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4 my-2">
                                                            <label class="text-primary" for="Paid Method">Fixing Method</label>
                                                            <div class="select-control">
                                                                <select name="type" id="" class="form-select">
                                                                    <option value="" selected>---</option>
                                                                    <option value="Sell">Sell</option>
                                                                    <option value="Buy">Buy</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group text-center my-2">
                                                            <button type="submit" name="in" class="btn btn-primary "><i class="fa fa-plus rounded-circle bg-white p-1 text-primary mx-1"></i>Insert</button>
                                                            <button type="reset" name="clear" class="btn btn-danger "><i class="fa fa-xmark rounded-circle bg-white p-1 text-primary mx-1"></i>Clear</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php
            } else if (isset($_GET['new'])) {
                ?>
                    <div class="container-fluid my-5">
                        <div class="rounded align-items-center bg-light justify-content-center mx-0">
                            <div class="container py-2">

                                <div class="container-fluid pt-4 px-5">
                                    <div class=" rounded align-items-center bg-light justify-content-center mx-0">
                                        <div class="container py-2">

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <form action="" method="post" enctype="multipart/form-data">
                                                        <div class="card">
                                                            <div class="card-header text-center bg-primary ">
                                                                <h3 class="text-white mt-2">Fixing</h3>
                                                            </div>
                                                            <script>
                                                                function calculateTotals() {
                                                                    var weight = parseFloat(document.getElementById("gold").value);
                                                                    var own = parseFloat(document.getElementById("own").value);
                                                                    var total = own / 31.1035 * 3.674 / 3.67 * 1000 * weight;

                                                                    document.getElementById("total").value = total.toFixed(2);

                                                                }
                                                            </script>
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <h5 class="bg-primary text-white rounded-pill text-center">Personal Information</h5>
                                                                    <div class="col-md-4 my-2">
                                                                        <div class="form-group">
                                                                            <label class="text-primary" for="Name">Name</label>
                                                                            <input type="text" class="form-control" id="cus_name" name="name" placeholder="Write Name Here">
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-4 my-2">
                                                                        <div class="form-group">
                                                                            <label class="text-primary" for="NIC">NIC</label>
                                                                            <input type="text" class="form-control" id="NIC" name="nic" placeholder="Write NIC Here">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4 my-2">
                                                                        <div class="form-group">
                                                                            <label class="text-primary" for="Phone">Phone</label>
                                                                            <input type="tel" class="form-control" id="Phone" name="phone" placeholder="Write Phone Here">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4 my-2">
                                                                        <div class="form-group">
                                                                            <label class="text-primary" for="Email">Email</label>
                                                                            <input type="email" class="form-control" id="Email" name="email" placeholder="Write Email Here">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4 my-2">
                                                                        <div class="form-group">
                                                                            <label class="text-primary" for="Location">Location</label>
                                                                            <input type="text" class="form-control" id="Location" name="loc" placeholder="Write Nation or Province/Country Here">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4 my-2">
                                                                        <div class="form-group">
                                                                            <label class="text-primary" for="Reference">Reference</label>
                                                                            <input type="text" class="form-control" id="Reference" value="" name="ref" placeholder="Write Reference Name Here">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4 my-2">
                                                                        <div class="form-group">
                                                                            <label class="text-primary" for="gold">Gold Weight Grams وزن</label>
                                                                            <input type="text" class="form-control" id="gold" value="" name="gold" oninput="calculateTotals()" placeholder="Write Gold Weight Grams Here">
                                                                        </div>
                                                                    </div>


                                                                    <div class="col-md-4 my-2">
                                                                        <div class="form-group">
                                                                            <label class="text-primary" for="own">Owns اونس</label>
                                                                            <input type="text" class="form-control" id="own" value="" oninput="calculateTotals()" name="own" placeholder="Write Owns Here">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4 my-2">
                                                                        <div class="form-group">
                                                                            <label class="text-primary" for="total">Total Fixed Price</label>
                                                                            <input type="text" class="form-control" id="total" name="total" placeholder="See Total Money Here">
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-4 my-2">
                                                                        <label class="text-primary" for="Paid Method">Fixing Method</label>
                                                                        <div class="select-control">
                                                                            <select name="type" id="" class="form-select">
                                                                                <option value="" selected>---</option>
                                                                                <option value="Sell">Sell</option>
                                                                                <option value="Buy">Buy</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group text-center my-2">
                                                                        <button type="submit" name="innew" class="btn btn-primary "><i class="fa fa-plus rounded-circle bg-white p-1 text-primary mx-1"></i>Insert</button>
                                                                        <button type="reset" name="clear" class="btn btn-danger "><i class="fa fa-xmark rounded-circle bg-white p-1 text-primary mx-1"></i>Clear</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php


                        } else {
                            ?>
                                <div class="container-fluid pt-4 px-5">
                                    <div class="rounded align-items-center bg-light justify-content-center mx-0">
                                        <div class="container py-2 pb-5">
                                            <h4 class="text-center my-4 bg-primary rounded-pill p-2 text-white">Fixing</h4>

                                            <div id="search-results">
                                                <table class="table text-start align-middle table-bordered mb-0">
                                                    <thead>
                                                        <tr class="text-white bg-primary text-center">
                                                            <th scope="col">Role</th>
                                                            <th scope="col">Name</th>
                                                            <th scope="col">NIC</th>
                                                            <th scope="col">Phone</th>
                                                            <th scope="col">Email</th>
                                                            <th scope="col">Location</th>
                                                            <th scope="col">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <h5 class="text-center my-3 text-primary rounded p-2 bg-white">Silver Customers</h5>
                                                        <?php
                                                        $sql = "SELECT * FROM `customer` where `role`='Silver Customer'";
                                                        $res = mysqli_query($conn, $sql);
                                                        if (mysqli_num_rows($res) > 0) {
                                                            while ($emp = mysqli_fetch_assoc($res)) {

                                                        ?>
                                                                <tr class="text-white bg-dark text-center">
                                                                    <td><?= $emp['role'] ?></td>
                                                                    <td><?= $emp['name'] ?></td>
                                                                    <td><?= @$emp['nic'] ?></td>
                                                                    <td><?= $emp['phone'] ?></td>
                                                                    <td><?= $emp['email'] ?></td>
                                                                    <td><?= $emp['loc'] ?></td>
                                                                    <td class="text-center"><a class=" btn btn-sm btn-success" href="fix.php?t=<?= $emp['id']; ?>"><i class="far fa-eye"></i></a></td>
                                                                </tr>
                                                        <?php
                                                            }
                                                        } else {
                                                            echo '<th class="text-dark text-center" colspan="11">No Data Exist</th>';
                                                        }
                                                        ?>
                                                        <thead>

                                                            <tr>
                                                                <th colspan="7">
                                                                    <h5 class="text-center my-3 text-primary rounded p-2 bg-white">Silver Suppliers</h5>
                                                                </th>
                                                            </tr>
                                                            <tr class="text-white bg-primary text-center">
                                                                <th scope="col">Role</th>
                                                                <th scope="col">Name</th>
                                                                <th scope="col">NIC</th>
                                                                <th scope="col">Phone</th>
                                                                <th scope="col">Email</th>
                                                                <th scope="col">Location</th>
                                                                <th scope="col">Action</th>
                                                            </tr>
                                                        </thead>
                                                    <tbody>
                                                        <?php
                                                        $sql = "SELECT * FROM `customer` where `role`='Silver Supplier'";
                                                        $res = mysqli_query($conn, $sql);
                                                        if (mysqli_num_rows($res) > 0) {
                                                            while ($emp = mysqli_fetch_assoc($res)) {

                                                        ?>
                                                                <tr class="text-white bg-dark text-center">
                                                                    <td><?= $emp['role'] ?></td>
                                                                    <td><?= $emp['name'] ?></td>
                                                                    <td><?= @$emp['nic'] ?></td>
                                                                    <td><?= $emp['phone'] ?></td>
                                                                    <td><?= $emp['email'] ?></td>
                                                                    <td><?= $emp['loc'] ?></td>
                                                                    <td class="text-center"><a class=" btn btn-sm btn-success" href="fix.php?t=<?= $emp['id']; ?>"><i class="far fa-eye"></i></a></td>
                                                                </tr>
                                                        <?php
                                                            }
                                                        } else {
                                                            echo '<th class="text-dark text-center" colspan="11">No Data Exist</th>';
                                                        }
                                                        ?>
                                                        <thead>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php
                        }
    ?>
    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Template Javascript -->
    <script src="../js/main.js"></script>
</body>

</html>