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
    // if (isset($_POST['insert'])) {

    //     // personal information
    //     $name = $_POST['name'];
    //     $ref = $_POST['ref'];
    //     $nic = $_POST['nic'];
    //     $phone = $_POST['phone'];
    //     $email = $_POST['email'];
    //     $loc = $_POST['loc'];

    //     $price = $_POST['price'];
    //     $com = $_POST['com'];
    //     $totalk = $com + $price;
    //     $totalcc = $price - $com;
    //     $Paid = $_POST['Paid'];
    //     $Paidc = $_POST['Paidc'];
    //     $in = $_POST['in'];

    //     $cus = "INSERT INTO `customer`(`name`, `phone`, `email`, `nic`, `ref`, `loc`, `role`) VALUES ('$name','$phone','$email','$nic','$ref','$loc', 'Money Supplier')";
    //     $res = mysqli_query($conn, $cus);
    //     if ($res === false) {
    //         echo "Error: ";
    //     } else {

    //         $cuss = "SELECT * FROM customer WHERE `name`='$name' And `role`='Money Supplier'";
    //         $ress = mysqli_query($conn, $cuss);
    //         $userc = mysqli_fetch_assoc($ress);
    //         $id = $userc['id'];

    //         if ($in == "Credit") {
    //             if ($Paidc == "Cash") {

    //                 $cress = "INSERT INTO `account`(`cus_id`, `ref`, `amount`, `amo_cre`, `gold`, `role`, `state`, `type`, `com`) VALUES ('$id','$ref','$price','Money Supplier','$Paid')";
    //                 $resss = mysqli_query($conn, $cress);

    //                 $debsss = "INSERT INTO `storage`(`st_cus`, `st_price`,`type`,`method`) VALUES ('$id','$totalk','$Paid','Credit')";
    //                 mysqli_query($conn, $debsss);

    //                 $stass = "INSERT INTO `statement`(`se_cus`,`ref`,`se_tbill`, `se_type`, `se_method`, `dis`) VALUES ('$id','$ref','$price','Credit','$Paid', 'Paid <br> ($price$ BY Message NO. $ref )')";
    //                 mysqli_query($conn, $stass);

    //                 $cress = "INSERT INTO `credit`(`ref`, `cre_cus` , `cre_tbill`,`sate`,`typec`) VALUES ('$ref','$id','$price','Credit','$Paidc')";
    //                 mysqli_query($conn, $cress);
    //             }
    //             if ($Paidc == "in Account") {

    //                 $crev = "INSERT INTO `account`(`cus_id`, `ref`,`amount`,`role`,`state`) VALUES ('$id','$ref','$totalcc','Money Supplier','$Paid')";
    //                 $resv = mysqli_query($conn, $crev);

    //                 $crevv = "INSERT INTO `credit`(`ref`, `cre_cus` , `cre_tbill`, `ujorat`, `com`,`sate`,`typec`) VALUES ('$ref','$id','$price','0','$com','Credit Commission','$Paidc')";
    //                 mysqli_query($conn, $crevv);

    //                 $debsv = "INSERT INTO `storage`(`st_cus`, `st_price`,`type`,`method`) VALUES ('$id','$price','$Paid','Credit')";
    //                 mysqli_query($conn, $debsv);


    //                 $stav = "INSERT INTO `statement`(`se_cus`,`ref`,`se_tbill`, `se_type`, `se_method`, `dis`) VALUES ('$id','$ref','$totalcc','Credit','$Paid', 'Recepit (CASH From $name $price$)')";
    //                 mysqli_query($conn, $stav);
    //             }
    //         }
    //         if ($in == "Debit") {


    //             if ($Paidc == "Cash") {

    //                 $crec = "INSERT INTO `account`(`cus_id`, `ref`,`amount`,`role`,`state`) VALUES ('$id','$ref','$price','Money Supplier','$Paid')";
    //                 $resc = mysqli_query($conn, $crec);

    //                 $debsc = "INSERT INTO `storage`(`st_cus`, `st_price`,`type`,`method`) VALUES ('$id','$totalk','$Paid','Debit')";
    //                 mysqli_query($conn, $debsc);

    //                 $stac = "INSERT INTO `statement`(`se_cus`,`ref`,`se_tbill`, `se_type`, `se_method`, `dis`) VALUES ('$id','$ref','$price','Debit','$Paid', 'Recepit (Received CASH From $name $price$ )')";
    //                 mysqli_query($conn, $stac);

    //                 $crecc = "INSERT INTO `Debit`(`ref`, `dep_cus` , `dep_tbill`,`sate`,`typec`) VALUES ('$ref','$id','$price','Debit','$Paidc')";
    //                 mysqli_query($conn, $crecc);
    //             }
    //             if ($Paidc == "in Account") {

    //                 $cre = "INSERT INTO `account`(`cus_id`, `ref`,`amount`,`role`,`state`) VALUES ('$id','$ref','$totalcc','Money Supplier','$Paid')";
    //                 $res = mysqli_query($conn, $cre);

    //                 $cre = "INSERT INTO `credit`(`ref`, `cre_cus` , `cre_tbill`, `ujorat`, `com`,`sate`,`typec`) VALUES ('$ref','$id','$price','0','$com','Credit Commission','$Paidc')";
    //                 mysqli_query($conn, $cre);

    //                 $debs = "INSERT INTO `storage`(`st_cus`, `st_price`,`type`,`method`) VALUES ('$id','$price','$Paid','Debit')";
    //                 mysqli_query($conn, $debs);


    //                 if ($res == false) {
    //                     echo "Error";
    //                 } else {

    //                     $sta = "INSERT INTO `statement`(`se_cus`,`ref`,`se_tbill`, `se_type`, `se_method`, `dis`) VALUES ('$id','$ref','$totalcc','Debit','$Paid', 'Payment (Piad CASH To $name $price$ COM In-Account $com$)')";
    //                     mysqli_query($conn, $sta);
    //                 }
    //             }
    //         }
    //     }
    // }
    if (isset($_POST['insertn'])) {

        // invoice inserteration

        $id = $_POST['ID'];
        $moa = $_POST['moa'];
        $gold = $_POST['gold'];

        $cs = "SELECT * FROM customer WHERE `id`= '$gold'";
        $re = mysqli_query($conn, $cs);
        $use = mysqli_fetch_assoc($re);
        $gname = $use['name'];

        $name = $_POST['name'];
        $ttype = $_POST['ttype'];
        $com = $_POST['com'];
        $moac = $moa + $com;
        $Paid = $_POST['Paid'];
        $ref = $_POST['ref'];
        
        // money Supplier

            $dec = "INSERT INTO `credit`(`ref`, `cre_cus`, `cre_tgold`, `cre_tbill`, `cre_tpurity`, `ujorat`, `com`, `sate`, `fix`, `role`, `typec`) VALUES ('$ref','$id','0','$moa','0','0','0','Credit','Not','Money Supplier','$Paid')";
            mysqli_query($conn, $dec);

            $amc = "INSERT INTO `account`(`cus_id`, `ref`, `amount`, `amo_cre`, `gold`, `role`, `state`, `type`, `com`) VALUES ('$id','$ref','0','$moac','0','Money Supplier','$Paid','Credit','0')";
            mysqli_query($conn, $amc);
            
            $stacc = "INSERT INTO `statement`(`se_cus`, `ref`, `se_tgolg`, `se_tpurity`, `se_tbill`, `se_type`, `se_method`, `dis`) VALUES ('$id','$ref','0','0','$moac','Credit','$Paid', 'Paid <br> ($moa$ TO REFERENCE $ref With COM $com)')";
            mysqli_query($conn, $stacc);
            
            
             // Gold Supplier

            $deb = "INSERT INTO `debit`(`dep_cus`, `ref`, `dep_tgold`, `dep_tbill`, `dep_tpurity`, `sate`, `com`, `typec`) VALUES ('$gold','$ref','0','$moa','0','Debit','$com','$Paid')";
            mysqli_query($conn, $deb);
            
            $sta = "INSERT INTO `statement`(`se_cus`, `ref`, `se_tgolg`, `se_tpurity`, `se_tbill`, `se_type`, `se_method`, `dis`) VALUES ('$gold','$ref','0','0','$moa','Debit','$Paid', 'Recepit <br> ($moa$ FROM REFERENCE $ref )')";
            mysqli_query($conn, $sta);

            $amo = "INSERT INTO `account`(`cus_id`, `ref`, `amount`, `amo_cre`, `gold`, `role`, `state`, `type`, `com`) VALUES ('$gold','$ref','$moa','0','0','Silver Customer','$Paid','Debit','0')";
            mysqli_query($conn, $amo);

    }
    ?>

    <?php
    $id = $_GET['id'];
    $sqlcus = "SELECT * FROM `customer` where id =" . $_GET['id'];
    $rescus = mysqli_query($conn, $sqlcus);
    $empp = mysqli_fetch_assoc($rescus);

    $sqli = "SELECT SUM(amount) as total FROM `account` where cus_id =" . $_GET['id'];
    $resi = mysqli_query($conn, $sqli);
    $cusi = mysqli_fetch_assoc($resi);
    $sql = "SELECT SUM(amo_cre) as totalc FROM `account` where cus_id =" . $_GET['id'];
    $res = mysqli_query($conn, $sql);
    $cus = mysqli_fetch_assoc($res);
    $sqlcc = "SELECT SUM(cre_tpurity) as totalcc FROM `credit` where cre_cus=" . $_GET['id'];
    $resc = mysqli_query($conn, $sqlcc);
    $cusc = mysqli_fetch_assoc($resc);

    $sqlg = "SELECT SUM(dep_tpurity) as totalg FROM `debit` where dep_cus=" . $_GET['id'];
    $resg = mysqli_query($conn, $sqlg);
    $cusg = mysqli_fetch_assoc($resg);

    $sqlkg = "SELECT SUM(gold) as totalkg FROM `account` where `type`='Debit' and cus_id=" . $_GET['id'];
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
                    <div class="col-md-5 bg-dark rounded m-3 ">
                        <h5 class="bg-white p-2 text-primary text-center">Personnel Information</h5>
                        <h6 class="text-white px-4 ">Role: <span> <?= $empp['role']; ?></span> </h6>
                        <h6 class="text-white px-4 ">Name: <span> <?= $empp['name']; ?></span> </h6>
                        <h6 class="text-white px-4 ">Location: <span> <?= $empp['loc']; ?></span> </h6>
                    </div>
                    <div class="col-md-5 bg-dark rounded m-3">
                        <h5 class="bg-white p-2 text-primary text-center">Total Money In Account</h5>
                        <h5 class="text-white px-4 ">Debit Money: <span> <?= number_format(@$cusi['total'], 3) ?></span> </h5>
                        <h5 class="text-white px-4 ">Credit Money: <span> <?= number_format(@$cus['totalc'], 3) ?></span></h5>
                        <h5 class="text-white ">
                            <?php

                            if ($cusi['total'] > $cus['totalc']) {
                                echo "<h5 class='text-white px-4 '>Total Debit: <span>", number_format(@$cusi['total'] - $cus['totalc'], 3);
                                # code...
                            } else {
                                echo "<h5 class='text-white px-4 '>Total Credit: <span>",  number_format(@$cus['totalc'] - $cusi['total'], 3);
                                # code...
                            }
                            ?></h5>
                    </div>
                   
                    <a class="text-center btn btn-success my-3 w-25" href="../monthly_journal/mjournal.php"><i class="fa fa-circle-arrow-left w-100"></i><b> Monthly Journal</b></a>
                    <a class="text-center btn btn-outline-dark m-3 w-25" href="../index.php"><i class="fa fa-circle-arrow-left w-100"></i><b> Dashboard</b></a>

                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="card">
                            <div class="card-header text-center bg-primary">
                                <h3 class="text-white mt-2">Add New trasiction of Money Supplier</h3>
                            </div>
                            <div class="card-body">
                                <!-- Personal Information Fields... -->
                                <div class="col-md-12">
                                    <div class="row">
                                        <h5 class="bg-primary text-white rounded-pill text-center">Personal Information</h5>
                                        <div class="col-md-4 my-2">
                                            <div class="form-group">
                                                <label class="text-primary" for="Name">ID</label>
                                                <input type="text" class="form-control" id="id" value="<?= $_GET['id'] ?>" name="ID" placeholder="Write Name Here">
                                            </div>
                                        </div>
                                                                                <div class="col-md-4 my-2">
                                            <div class="form-group">
                                                <label class="text-primary" for="Name">Name</label>
                                                <input type="text" class="form-control" id="cus_name" value="<?= $empp['name'] ?>" name="name" placeholder="Write Name Here">
                                            </div>
                                        </div>
                                        <div class="col-md-4 my-2">
                                            <div class="form-group">
                                                <label class="text-primary" for="Hawala Number">Hawala Number</label>
                                                <input type="text" class="form-control" id="Hawala Number" value="" name="ref" placeholder="Write Hawala Number Here">
                                            </div>
                                        </div>
                                        <h5 class="bg-primary text-white rounded-pill text-center">Money Information</h5>
                                        <div class="col-md-4 my-2">
                                            <div class="form-group">
                                                <label for="gold" class="text-primary">Silver Customer Name:</label>
                                                <select id="gold" name="gold" class="form-select">
                                                    <option value="------">------</option>
                                                    <?php $sql = "SELECT * FROM `customer` where `role` = 'Silver Customer'";
                                                    $res = mysqli_query($conn, $sql);
                                                    if (mysqli_num_rows($res) > 0) {
                                                        while ($cus = mysqli_fetch_assoc($res)) {
                                                    ?>
                                                            <option value="<?= $cus['id'] ?>"><?= $cus['name'] ?></option>
                                                    <?php
                                                        }
                                                    }

                                                    ?>


                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4 my-2">
                                            <div class="form-group">
                                                <label for="moa" class="text-primary">Money Amount:</label>
                                                <input type="number" name="moa" class="form-control" id="moa" step="any">
                                            </div>
                                        </div>

                                        <div class="col-md-4 my-2">
                                            <div class="form-group">
                                                <label for="ttype" class="text-primary">Transfer Method:</label>
                                                <select id="ttype" name="ttype" class="form-select">
                                                    <option value="------">------</option>
                                                    <option value="Hawala">Hawala</option>
                                                    <option value="Bank">Bank</option>
                                                    <option value="ByHand">By hand</option>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4 my-2">
                                            <div class="form-group">
                                                <label for="com" class="text-primary">Commission: </label>
                                                <input type="text" name="com" class="form-control" id="com">
                                            </div>
                                        </div>

                                        <div class="col-md-4 my-2">
                                            <div class="form-group">
                                                <label for="Paid" class="text-primary">Money Method:</label>
                                                <select id="Paid" name="Paid" class="form-select">
                                                    <option value="------">------</option>
                                                    <option value="Cash">Cash</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12 my-2">
                                            <div class="form-group text-center my-2">
                                                <button type="submit" name="insertn" class="btn btn-primary"><i class="fa fa-plus rounded-circle bg-white p-1 text-primary mx-1"></i> Insert</button>
                                                <button type="reset" name="clear" class="btn btn-danger"><i class="fa fa-times rounded-circle bg-white p-1 text-primary mx-1"></i> Clear</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Template Javascript -->
    <script src="./js/main.js"></script>
</body>

</html>