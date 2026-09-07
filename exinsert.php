<?php
require "config/Dbconn.php";
?>
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
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/vfs_fonts.js"></script>

    <!-- Template Stylesheet -->

    <link href="css/style.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="bg-primary">
    <!-- Sidebar End -->

    <!-- Content Start -->
    <div class="content">
        <?php
        include "navbar.php";
        ?>
        <!-- Blank Start -->
        <?php
        // Check if form was submitted

        include "config/Dbconn.php";
if (isset($_POST['insertc'])) {
    // Sanitize inputs
    $name = isset($_POST['name']) ? mysqli_real_escape_string($conn, $_POST['name']) : '';
    $nic = isset($_POST['nic']) ? mysqli_real_escape_string($conn, $_POST['nic']) : '';
    $phone = isset($_POST['phone']) ? mysqli_real_escape_string($conn, $_POST['phone']) : '';
    $email = isset($_POST['email']) ? mysqli_real_escape_string($conn, $_POST['email']) : '';
    $ref = "";
    $loc = isset($_POST['loc']) ? mysqli_real_escape_string($conn, $_POST['loc']) : '';
    $role = isset($_POST['role']) ? mysqli_real_escape_string($conn, $_POST['role']) : '';

    if (empty($name) && empty($nic) && empty($phone) && empty($email) && empty($loc) && empty($role)) {
        $err = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fa fa-exclamation-circle me-2"></i>Please fill the blanks!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
    } else {
        // Insert customer data
        $cus = "INSERT INTO `customer`(`name`, `phone`, `email`, `nic`, `ref`, `loc`, `role`) VALUES ('$name','$phone','$email','$nic','$ref','$loc','$role')";
        $res = mysqli_query($conn, $cus);

        if ($res == false) {
            $err = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fa fa-exclamation-circle me-2"></i>Customer Not Inserted!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
        } else {
            // Retrieve the inserted customer's ID
            $cuss = "SELECT * FROM customer WHERE `name`='$name' AND `phone`='$phone'";
            $ressd = mysqli_query($conn, $cuss);
            $userc = mysqli_fetch_assoc($ressd);
            $id = $userc['id'];

            // Retrieve other form inputs
            $type = isset($_POST['type']) ? mysqli_real_escape_string($conn, $_POST['type']) : '';
            $typec = isset($_POST['typec']) ? mysqli_real_escape_string($conn, $_POST['typec']) : '';
            $ref = isset($_POST['ref']) ? mysqli_real_escape_string($conn, $_POST['ref']) : '';
            $bill = isset($_POST['bill']) ? mysqli_real_escape_string($conn, $_POST['bill']) : '';
            $totalg = isset($_POST['totalg']) ? mysqli_real_escape_string($conn, $_POST['totalg']) : '';
            $Paid = isset($_POST['Paid']) ? mysqli_real_escape_string($conn, $_POST['Paid']) : '';
            $totalpure = isset($_POST['totalpure']) ? mysqli_real_escape_string($conn, $_POST['totalpure']) : '';

            if ($role == "Customer") {
                if ($type == "Credit") {
                    // Insert data into the 'account' table
                    $amota = "INSERT INTO `account`(`cus_id`, `ref`, `amount`, `amo_cre`, `gold`, `role`, `state`, `type`, `com`) VALUES ('$id','$ref','0','0','$totalg','Customer','$Paid','Debit','0')";
                    mysqli_query($conn, $amota);

                    if ($typec == "Debit") {
                        $amog = "INSERT INTO `account`(`cus_id`, `ref`, `amount`, `amo_cre`, `gold`, `role`, `state`, `type`, `com`) VALUES ('$id','$ref','$bill','0','0','Customer','$Paid','$typec','0')";
                        mysqli_query($conn, $amog);
                    }

                    if ($typec == "Credit") {
                        $amokk = "INSERT INTO `account`(`cus_id`, `ref`, `amount`, `amo_cre`, `gold`, `role`, `state`, `type`, `com`) VALUES ('$id','$ref','0','$bill','0','Customer','$Paid','$typec','0')";
                        mysqli_query($conn, $amokk);
                    }

                  

                    $suc = '<div class="alert alert-info alert-dismissible fade show" role="alert">
                                <i class="fa fa-exclamation-circle me-2"></i>Customer Inserted And Credit data Added!
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>';
                } elseif ($type == "Debit") {
                    // Insert data into the 'account' table
                    $debg = "INSERT INTO `account`(`cus_id`, `ref`, `amount`, `amo_cre`, `gold`, `role`, `state`, `type`, `com`) VALUES ('$id','$ref','0','0','$totalg','Customer','$Paid','$type','0')";
                    mysqli_query($conn, $debg);

                    if ($typec == "Debit") {
                        $amod = "INSERT INTO `account`(`cus_id`, `ref`, `amount`, `amo_cre`, `gold`, `role`, `state`, `type`, `com`) VALUES ('$id','$ref','$bill','0','0','Customer','$Paid','$typec','0')";
                        mysqli_query($conn, $amod);

                    } elseif ($typec == "Credit") {
                        $amodc = "INSERT INTO `account`(`cus_id`, `ref`, `amount`, `amo_cre`, `gold`, `role`, `state`, `type`, `com`) VALUES ('$id','$ref','0','$bill','0','Customer','$Paid','$typec','0')";
                        mysqli_query($conn, $amodc);

                    }

                   

                    $suc = '<div class="alert alert-info alert-dismissible fade show" role="alert">
                                <i class="fa fa-exclamation-circle me-2"></i>Customer Inserted And Debit data Added!
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>';
                }
                
             $statg = "INSERT INTO `statement`(`se_cus`, `ref`, `se_tgolg`, `se_tpurity`, `se_tbill`, `se_type`, `se_method`, `dis`) VALUES ('$id','$ref','0','0','$bill','$typec','$Paid', 'Data Entry of $name Momey amount $bill')";
                    mysqli_query($conn, $statg);
                    
                    $statgg = "INSERT INTO `statement`(`se_cus`, `ref`, `se_tgolg`, `se_tpurity`, `se_tbill`, `se_type`, `se_method`, `dis`) VALUES ('$id','$ref','0','$totalg','0','$type','$Paid', 'Data Entry of $name Pure WT- $totalg GMS')";
                    mysqli_query($conn, $statgg);
                        
            } 
            if ($role == "Supplier") {
                if ($type == "Credit") {
                    // Insert data into the 'account' table
                    $amota = "INSERT INTO `account`(`cus_id`, `ref`, `amount`, `amo_cre`, `gold`, `role`, `state`, `type`, `com`) VALUES ('$id','$ref','0','0','$totalg','Customer','$Paid','Debit','0')";
                    mysqli_query($conn, $amota);

                    if ($typec == "Debit") {
                        $amog = "INSERT INTO `account`(`cus_id`, `ref`, `amount`, `amo_cre`, `gold`, `role`, `state`, `type`, `com`) VALUES ('$id','$ref','$bill','0','0','Customer','$Paid','$typec','0')";
                        mysqli_query($conn, $amog);
                    }

                    if ($typec == "Credit") {
                        $amokk = "INSERT INTO `account`(`cus_id`, `ref`, `amount`, `amo_cre`, `gold`, `role`, `state`, `type`, `com`) VALUES ('$id','$ref','0','$bill','0','Customer','$Paid','$typec','0')";
                        mysqli_query($conn, $amokk);
                    }

                  

                    $suc = '<div class="alert alert-info alert-dismissible fade show" role="alert">
                                <i class="fa fa-exclamation-circle me-2"></i>Customer Inserted And Credit data Added!
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>';
                } elseif ($type == "Debit") {
                    // Insert data into the 'account' table
                    $debg = "INSERT INTO `account`(`cus_id`, `ref`, `amount`, `amo_cre`, `gold`, `role`, `state`, `type`, `com`) VALUES ('$id','$ref','0','0','$totalg','Customer','$Paid','$type','0')";
                    mysqli_query($conn, $debg);

                    if ($typec == "Debit") {
                        $amod = "INSERT INTO `account`(`cus_id`, `ref`, `amount`, `amo_cre`, `gold`, `role`, `state`, `type`, `com`) VALUES ('$id','$ref','$bill','0','0','Customer','$Paid','$typec','0')";
                        mysqli_query($conn, $amod);

                    } elseif ($typec == "Credit") {
                        $amodc = "INSERT INTO `account`(`cus_id`, `ref`, `amount`, `amo_cre`, `gold`, `role`, `state`, `type`, `com`) VALUES ('$id','$ref','0','$bill','0','Customer','$Paid','$typec','0')";
                        mysqli_query($conn, $amodc);

                    }

                   

                    $suc = '<div class="alert alert-info alert-dismissible fade show" role="alert">
                                <i class="fa fa-exclamation-circle me-2"></i>Customer Inserted And Debit data Added!
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>';
                }
                
             $statg = "INSERT INTO `statement`(`se_cus`, `ref`, `se_tgolg`, `se_tpurity`, `se_tbill`, `se_type`, `se_method`, `dis`) VALUES ('$id','$ref','0','0','$bill','$typec','$Paid', 'Data Entry of $name Momey amount $bill')";
                    mysqli_query($conn, $statg);
                    
                    $statgg = "INSERT INTO `statement`(`se_cus`, `ref`, `se_tgolg`, `se_tpurity`, `se_tbill`, `se_type`, `se_method`, `dis`) VALUES ('$id','$ref','0','$totalg','0','$type','$Paid', 'Data Entry of $name Pure WT- $totalg GMS')";
                    mysqli_query($conn, $statgg);
                           $suc = '
                                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                                            <i class="fa fa-exclamation-circle me-2"></i> Gold Supplier With Credit Gold Added!
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    ';
                        
                    }

                    else if ($role == "Money Supplier") {

                if ($type == "Credit") {
                    // Insert data into the 'account' table
                    $amota = "INSERT INTO `account`(`cus_id`, `ref`, `amount`, `amo_cre`, `gold`, `role`, `state`, `type`, `com`) VALUES ('$id','$ref','0','0','$totalg','Customer','$Paid','Debit','0')";
                    mysqli_query($conn, $amota);

                    if ($typec == "Debit") {
                        $amog = "INSERT INTO `account`(`cus_id`, `ref`, `amount`, `amo_cre`, `gold`, `role`, `state`, `type`, `com`) VALUES ('$id','$ref','$bill','0','0','Customer','$Paid','$typec','0')";
                        mysqli_query($conn, $amog);
                    }

                    if ($typec == "Credit") {
                        $amokk = "INSERT INTO `account`(`cus_id`, `ref`, `amount`, `amo_cre`, `gold`, `role`, `state`, `type`, `com`) VALUES ('$id','$ref','0','$bill','0','Customer','$Paid','$typec','0')";
                        mysqli_query($conn, $amokk);
                    }

                  

                    $suc = '<div class="alert alert-info alert-dismissible fade show" role="alert">
                                <i class="fa fa-exclamation-circle me-2"></i>Customer Inserted And Credit data Added!
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>';
                } elseif ($type == "Debit") {
                    // Insert data into the 'account' table
                    $debg = "INSERT INTO `account`(`cus_id`, `ref`, `amount`, `amo_cre`, `gold`, `role`, `state`, `type`, `com`) VALUES ('$id','$ref','0','0','$totalg','Customer','$Paid','$type','0')";
                    mysqli_query($conn, $debg);

                    if ($typec == "Debit") {
                        $amod = "INSERT INTO `account`(`cus_id`, `ref`, `amount`, `amo_cre`, `gold`, `role`, `state`, `type`, `com`) VALUES ('$id','$ref','$bill','0','0','Customer','$Paid','$typec','0')";
                        mysqli_query($conn, $amod);

                    } elseif ($typec == "Credit") {
                        $amodc = "INSERT INTO `account`(`cus_id`, `ref`, `amount`, `amo_cre`, `gold`, `role`, `state`, `type`, `com`) VALUES ('$id','$ref','0','$bill','0','Customer','$Paid','$typec','0')";
                        mysqli_query($conn, $amodc);

                    }

                   

                    $suc = '<div class="alert alert-info alert-dismissible fade show" role="alert">
                                <i class="fa fa-exclamation-circle me-2"></i>Customer Inserted And Debit data Added!
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>';
                }
                
             $statg = "INSERT INTO `statement`(`se_cus`, `ref`, `se_tgolg`, `se_tpurity`, `se_tbill`, `se_type`, `se_method`, `dis`) VALUES ('$id','$ref','0','0','$bill','$typec','$Paid', 'Data Entry of $name Momey amount $bill')";
                    mysqli_query($conn, $statg);
                    
                    $statgg = "INSERT INTO `statement`(`se_cus`, `ref`, `se_tgolg`, `se_tpurity`, `se_tbill`, `se_type`, `se_method`, `dis`) VALUES ('$id','$ref','0','$totalg','0','$type','$Paid', 'Data Entry of $name Pure WT- $totalg GMS')";
                    mysqli_query($conn, $statgg);

                        $suc = '<div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="fa fa-exclamation-circle me-2"></i>Money Supplier Inserted And Debit data Added!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>';
                    }
                }
            }
}
        ?>
        <!-- slider Start -->
        <div class="container-fluid ">
            <div class="rounded align-items-center bg-light justify-content-center mx-0">
                <div class="container py-2">
                    <div class="row">
                        <form method="post" enctype="multipart/form-data">
                            <?php if (isset($ero)) {
                                @$ero;
                            }
                            if (isset($er)) {
                                @$er;
                            }
                            ?>
                            <div class="card">
                                <div class="card-header text-center bg-primary">
                                    <h3 class="text-white mt-2">Data Entry form</h3>
                                </div>
                                <?php if (isset($suc)) {
                                    echo $suc;
                                    # code...
                                }
                                if (isset($err)) {
                                    echo $err;
                                ?>
                                    <style>
                                        input[type="text"],
                                        input[type="email"],
                                        div.select-control {
                                            border: 1px solid red;
                                        }
                                    </style>
                                <?php
                                }

                                ?>
                                <div class="card-body">
                                    <div class="row">
                                        <h5 class="bg-primary text-white rounded-pill text-center">Persoanl Information</h5>
                                        <div class="col-md-4 my-2">
                                            <div class="form-group">
                                                <label class="text-primary" for="Name">Name</label>
                                                <input type="text" class="form-control" id="cus_name" value="<?= @$name ?>" name="name" placeholder="Write Name Here">
                                            </div>
                                        </div>
                                        <div class="col-md-4 my-2">
                                            <div class="form-group">
                                                <label class="text-primary" for="NIC">NIC</label>
                                                <input type="text" class="form-control" value="<?= @$NIC ?>" id="NIC" name="nic" placeholder="Write NIC Here">
                                            </div>
                                        </div>
                                        <div class="col-md-4 my-2">
                                            <div class="form-group">
                                                <label class="text-primary" for="Phone">Phone</label>
                                                <input type="text" class="form-control" id="Phone" value="<?= @$phone ?>" name="phone" placeholder="Write Phone Here">
                                            </div>
                                        </div>
                                        <div class="col-md-4 my-2">
                                            <div class="form-group">
                                                <label class="text-primary" for="Email">Email</label>
                                                <input type="email" class="form-control" id="Email" value="<?= @$email ?>" name="email" placeholder="Write Email Here">
                                            </div>
                                        </div>
                                        <div class="col-md-4 my-2">
                                            <div class="form-group">
                                                <label class="text-primary" for="Location">Location</label>
                                                <input type="text" class="form-control" id="Location" value="<?= @$loc ?>" name="loc" placeholder="Write Nation or Province/Country Here">
                                            </div>
                                        </div>
                                        <div class="col-md-4 my-2">
                                            <label class="text-primary" for="Paid Method">Role</label>
                                            <div class="select-control">
                                                <select name="role" id="" class="form-select">
                                                    <option value="<?= @$role ?>" selected><?= @$role ?></option>
                                                    <option value="Supplier">Supplier</option>
                                                    <option value="Customer">Customer</option>
                                                    <option value="Money Supplier">Money Supplier</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4 my-2">
                                            <div class="form-group">
                                                <label class="text-primary" for="Referance">Referance</label>
                                                <input type="text" class="form-control" id="Referance" value="<?= @$ref ?>" name="ref" placeholder="Write Here">
                                            </div>
                                        </div>
                                        <h5 class="bg-primary text-white rounded-pill text-center">Gold Information</h5>

                                        <div class="col-md-4 my-2">
                                            <div class="form-group">
                                                <label class="text-primary" for="dis">Discription</label>
                                                <input type="text" value="" class="form-control" id="dis" name="dis">
                                            </div>
                                        </div>
                                        <div class="col-md-4 my-2">
                                            <div class="form-group">
                                                <label for="totalValue" class="text-primary">Total Gross Weight:</label>
                                                <input type="text" name="totalg" class="form-control" id="totalGrossWeight">
                                            </div>
                                        </div>
                                        <div class="col-md-4 my-2">
                                            <label class="text-primary" for="Paid Method">Transfer Method</label>
                                            <div class="select-control">
                                                <select name="type" id="" class="form-select">
                                                    <option value="" selected>---</option>
                                                    <option value="Debit">Debit (بــــــردگـــــــــی)</option>
                                                    <option value="Credit">Credit (رســــــــــــــیدگــــــی)</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4 my-2">
                                            <div class="form-group">
                                                <label for="totalpay" class="text-primary">Total Money $</label>
                                                <input type="text" name="bill" class="form-control" id="bill">
                                            </div>
                                        </div>
                                        <div class="col-md-4 my-2">
                                            <label class="text-primary" for="Paid Method">Transfer Method</label>
                                            <div class="select-control">
                                                <select name="typec" id="" class="form-select">
                                                    <option value="" selected>---</option>
                                                    <option value="Debit">Debit (نـــــــــــــام)</option>
                                                    <option value="Credit">Credit (جــــــــــمع)</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4 my-2">
                                            <div class="form-group">
                                                <label for="Paid" class="text-primary">Paid Method</label>
                                                <select id="Paid" name="Paid" class="form-select">
                                                    <option value="------">------</option>
                                                    <option value="Cash">Cash</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12 my-2">
                                            <div class="form-group text-center my-2">
                                                <button type="submit" name="insertc" class="btn btn-primary"><i class="fa fa-plus rounded-circle bg-white p-1 text-primary mx-1"></i> Insert</button>
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
    </div>

    <!-- Footer Start Strat -->
    <?php
    require_once "footer.php";
    ?>
    <!-- Footer Start Strat END -->