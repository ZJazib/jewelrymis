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
    if (isset($_POST['insert'])) {
        $name = $_POST['name'];
        $ref = $_POST['ref'];
        $id = $_POST['ids'];
        $price = $_POST['price'];
        $com = $_POST['com'];
        $totalk = $com + $price;
        $totalcc = $price - $com;
        $Paid = $_POST['Paid'];
        $pas = $_POST['pas'];
        if ($pas == "Cash") {

            $crea = "INSERT INTO `account`(`cus_id`, `ref`, `amount`, `amo_cre`, `gold`, `role`, `state`, `type`, `com`) VALUES ('$id','$ref','0','$price','0','Silver Customer','$Paid','Credit','$com')";
            mysqli_query($conn, $crea);

            $debsh = "INSERT INTO `storage`(`st_cus`, `st_price`, `st_gold`, `type`, `method`, `name`) VALUES ('$id','$totalk','0','Cash','Credit','Money')";
            mysqli_query($conn, $debsh);

            $stah = "INSERT INTO `statement`(`se_cus`, `ref`, `se_tgolg`, `se_tpurity`, `se_tbill`, `se_type`, `se_method`, `dis`) VALUES ('$id','$ref','0','0','$price','Credit','$Paid', 'Recepit <br> ($$price With COM CASH $com$)')";
            mysqli_query($conn, $stah);

            $msg = '
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="fa fa-exclamation-circle me-2"></i>Customer Credit Money Added!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        ';
        } 
       if ($pas == "inaccount") {

            $cre = "INSERT INTO `account`(`cus_id`, `ref`, `amount`, `amo_cre`, `gold`, `role`, `state`, `type`, `com`) VALUES ('$id','$ref','0','$totalcc','0','Customer','$Paid','Credit','$com')";
            $res = mysqli_query($conn, $cre);
 if ($res == false){
 $msg = '
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fa fa-exclamation-circle me-2"></i>Customer Credit Money Not Added!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            ';
            }
            $cred = "INSERT INTO `debit`(`dep_cus`, `ref`, `dep_tgold`, `dep_tbill`, `dep_tpurity`, `sate`, `com`, `typec`) VALUES ('$id','$ref','0','$price','0','Debit Commission','$com','$pas')";
            $red = mysqli_query($conn, $cred);
             if ($red == false){
 $msg = '
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fa fa-exclamation-circle me-2"></i>Customer Credit Money Not Added!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            ';
            }

           $debshd = "INSERT INTO `storage`(`st_cus`, `st_price`, `st_gold`,`type`, `method`, `name`) VALUES ('$id','$price','0','Cash','Credit','Money')";
            $ret = mysqli_query($conn, $debshd);
            if ($ret == false){
 $msg = '
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fa fa-exclamation-circle me-2"></i>Customer Credit Money Not Added!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            ';
            }

            $stas = "INSERT INTO `statement`(`se_cus`, `ref`, `se_tgolg`, `se_tpurity`, `se_tbill`, `se_type`, `se_method`, `dis`) VALUES ('$id','$ref','0','0','$totalcc','Credit','$Paid', 'Recepit <br> ($$price With COM CASH $com$)')";
            mysqli_query($conn, $stas);
            $msg = '
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="fa fa-exclamation-circle me-2"></i>Customer Credit Money Added!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            ';
        } else {
            $msg = '
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fa fa-exclamation-circle me-2"></i>Customer Credit Money Added!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            ';
            echo mysqli_error($conn);
        }
    }
    ?>

    <?php

$id = $_GET['t'];

        $sqlk = "SELECT * FROM `customer` where id =" . $_GET['t'];
        $resk = mysqli_query($conn, $sqlk);
        $empp = mysqli_fetch_assoc($resk);

        
        $sqli = "SELECT SUM(amount) as total FROM `account` where cus_id =" . $_GET['t'];
        $resi = mysqli_query($conn, $sqli);
        $cusi = mysqli_fetch_assoc($resi);
        $sql = "SELECT SUM(amo_cre) as totalc FROM `account` where cus_id =" . $_GET['t'];
        $res = mysqli_query($conn, $sql);
        $cus = mysqli_fetch_assoc($res);
        $sqlcc = "SELECT SUM(cre_tpurity) as totalcc FROM `credit` where cre_cus=" . $_GET['t'];
        $resc = mysqli_query($conn, $sqlcc);
        $cusc = mysqli_fetch_assoc($resc);

        $sqlg = "SELECT SUM(dep_tpurity) as totalg FROM `debit` where dep_cus=" . $_GET['t'];
        $resg = mysqli_query($conn, $sqlg);
        $cusg = mysqli_fetch_assoc($resg);

        $sqlkg = "SELECT SUM(gold) as totalkg FROM `account` where `type`='Debit' and cus_id=" . $_GET['t'];
        $reskg = mysqli_query($conn, $sqlkg);
        $cuskg = mysqli_fetch_assoc($reskg);
    ?>
        <div class="container-fluid pt-4 px-5">
            <div class=" rounded align-items-center bg-light justify-content-center mx-0">
                <div class="container py-2">
                    <?php
                    if (isset($msg)) {
                        echo $msg;
                    }
                    ?>
                    
                    <div class="row">
                    <div class="col-md-3 bg-dark rounded m-3 ">
                            <h5 class="bg-white p-2 text-primary text-center">Personnel Information</h5>
                            <h6 class="text-white px-4 ">Role: <span> <?= $empp['role']; ?></span> </h6>
                            <h6 class="text-white px-4 ">Name: <span> <?= $empp['name']; ?></span> </h6>
                            <h6 class="text-white px-4 ">Location: <span> <?= $empp['loc']; ?></span> </h6>
                        </div>
                        <div class="col-md-4 bg-dark rounded m-3">
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
                        <div class="col-md-4 bg-dark rounded m-3">
                            <h5 class="bg-white p-2 text-primary text-center">Total Silver In Account</h5>
                            <?php
                            $totalcc = floatval($cusc['totalcc']);
                            $totalg = floatval($cusg['totalg']);
                            $totalkg = floatval($cuskg['totalkg']);
                            ?>
                            <h5 class="text-white px-4">Debit Silver: <span> <?= number_format($totalf = $totalg + $totalkg, 3) ?> </span> </h5>
                            <h5 class="text-white px-4">Credit Silver: <span> <?= number_format($totalcc, 3) ?></span></h5>
                            <h5 class="text-white ">
                                <?php

                                if ($totalf > $totalcc) {
                                    echo "<span class='text-white mx-4'>Total Debit: ", number_format($totalf - $totalcc, 3), "</span>";
                                    # code...
                                } else {
                                    echo "<span class='px-4 text-white p-2 text-center w-100'>Total Credit: ", number_format($totalcc - $totalf, 3), "</span>";
                                    # code...
                                }
                                ?></h5>
                        </div>
                        <div class="col-md-12">
                        <a class="text-center btn btn-success my-3 w-25" href="../monthly_journal/mjournal.php"><i class="fa fa-circle-arrow-left w-100"></i><b>  Monthly Journal</b></a>
                        <a class="text-center btn btn-outline-dark m-3 w-25" href="../index.php"><i class="fa fa-circle-arrow-left w-100"></i><b> Dashboard</b></a>
                            <form action="" method="post" enctype="multipart/form-data">
                                <div class="card">
                                    <div class="card-header text-center bg-primary ">
                                        <h3 class="text-white mt-2">Add Customer Credit Money</h3>
                                    </div>
                                   
                                    <div class="card-body">
                                        <div class="row">
                                            
                                          
                                            
                                             <div class="col-md-4 my-2">
                                                <div class="form-group">
                                                    <label class="text-primary" for="ids">ID</label>
                                                    <input type="text" class="form-control" id="ids" value="<?=  $_GET['t']?>" name="ids" placeholder="Write Name Here">
                                                </div>
                                            </div>
                                            <div class="col-md-4 my-2">
                                                <div class="form-group">
                                                    <label class="text-primary" for="Name">Name</label>
                                                    <input type="text" class="form-control" id="Name" value="<?= $empp['name'] ?>" name="name" placeholder="Write Name Here">
                                                </div>
                                            </div>
                                            <div class="col-md-4 my-2">
                                                <div class="form-group">
                                                    <label class="text-primary" for="Reference">Reference</label>
                                                    <input type="text" class="form-control" id="Reference" value="" name="ref" placeholder="Write Reference Here">
                                                </div>
                                            </div>
                                            <div class="col-md-4 my-2">
                                                <div class="form-group">
                                                    <label class="text-primary" for="price">Amount</label>
                                                    <input type="text" class="form-control" id="price" value="" name="price" placeholder="Write Reference Here">
                                                </div>
                                            </div>
                                            <div class="col-md-4 my-2">
                                                <div class="form-group">
                                                    <label for="Paid" class="text-primary">Piad Method Amount</label>
                                                    <select id="Paid" name="Paid" class="form-select">
                                                        <option value="------">------</option>
                                                        <option value="Cash">Cash</option>
                                                        <option value="in Account">in Account</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4 my-2">
                                                <div class="form-group">
                                                    <label class="text-primary" for="com">Commission</label>
                                                    <input type="text" class="form-control" id="com" value="" name="com" placeholder="Write Reference Here">
                                                </div>
                                            </div>
                                            <div class="col-md-4 my-2">
                                                <div class="form-group">
                                                    <label for="pas" class="text-primary">Piad Method Commission</label>
                                                    <select id="pas" name="pas" class="form-select">
                                                        <option value="------">------</option>
                                                        <option value="Cash">Cash</option>
                                                        <option value="inaccount">in Account</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group text-center my-2">
                                                <button type="submit" name="insert" class="btn btn-primary "><i class="fa fa-plus rounded-circle bg-white p-1 text-primary mx-1"></i>Insert</button>
                                                <button type="reset" name="clear" class="btn btn-danger "><i class="fa fa-xmark rounded-circle bg-white p-1 text-primary mx-1"></i>Clear</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                </form>
            </div>
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
    <script src="../js/main.js"></script>
</body>

</html>