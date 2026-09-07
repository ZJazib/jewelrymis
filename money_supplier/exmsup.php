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
        // personal information
        $name = $_POST['name'];
       
       
        // Gold Information
        $moa = $_POST['moa'];
        $gold = $_POST['gold'];
        $ttype = $_POST['ttype'];
        $com = $_POST['com'];
        $moac = $moa + $com;
        $name = $_POST['name'];
        $ref = $_POST['ref'];
        
        $cs = "SELECT * FROM customer WHERE `id`= '$gold'";
        $re = mysqli_query($conn, $cs);
        $use = mysqli_fetch_assoc($re);
        $gname = $use['name'];
        $Paid = $_POST['Paid'];
        
            $deb = "INSERT INTO `debit`(`dep_cus`, `ref`, `dep_tgold`, `dep_tbill`, `dep_tpurity`, `sate`, `com`, `typec`) VALUES ('$gold','$ref','0','$moa','0','Debit','$com','$Paid')";
            mysqli_query($conn, $deb);
            
            $sta = "INSERT INTO `statement`(`se_cus`, `ref`, `se_tgolg`, `se_tpurity`, `se_tbill`, `se_type`, `se_method`, `dis`) VALUES ('$gold','$ref','0','0','$moa','Debit','$Paid', 'Recepit <br> ($moa$ BY Message NO. $ref )')";
            mysqli_query($conn, $sta);

            $amo = "INSERT INTO `account`(`cus_id`, `ref`, `amount`, `amo_cre`, `gold`, `role`, `state`, `type`, `com`) VALUES ('$gold','$ref','$moa','0','0','Supplier','$Paid','Debit','0')";
            mysqli_query($conn, $amo);

        $debs = "INSERT INTO `storage`(`st_cus`, `st_price`, `st_gold`, `type`, `method`, `name`) VALUES ('$gold','$moac','0','$Paid','Debit','Money')";
        mysqli_query($conn, $debs);
    }
    ?>


    <div class="container-fluid pt-4 px-5">
        <div class="rounded align-items-center bg-light justify-content-center mx-0">
            <div class="container py-2">
                <div class="row">
                    <a class="text-center btn btn-success my-3 w-25" href="../monthly_journal/mjournal.php"><i class="fa fa-circle-arrow-left w-100"></i><b> Monthly Journal</b></a>
                    <a class="text-center btn btn-outline-dark m-3 w-25" href="../index.php"><i class="fa fa-circle-arrow-left w-100"></i><b> Dashboard</b></a>
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="card">
                            <div class="card-header text-center bg-primary">
                                <h3 class="text-white mt-2">Add New Money Supplier</h3>
                            </div>
                            <div class="card-body">
                                <!-- Personal Information Fields... -->
                                <div class="col-md-12">
                                    <div class="row">
                                        <h5 class="bg-primary text-white rounded-pill text-center">Personal Information</h5>
                                        <div class="col-md-4 my-2">
                                            <div class="form-group">
                                                <label class="text-primary" for="Name">Name</label>
                                                <input type="text" class="form-control" id="cus_name" value="" name="name" placeholder="Write Name Here">
                                            </div>
                                        </div>
                                        <div class="col-md-4 my-2">
                                            <div class="form-group">
                                                <label class="text-primary" for="Reference">Hawala Number</label>
                                                <input type="text" class="form-control" id="Hawala Number" value="" name="ref" placeholder="Write Hawala Number Here">
                                            </div>
                                        </div>

                                        <h5 class="bg-primary text-white rounded-pill text-center">Money Information</h5>
                                        <div class="col-md-4 my-2">
                                            <div class="form-group">
                                                <label for="gold" class="text-primary">Gold Supplier Name:</label>
                                                <select id="gold" name="gold" class="form-select">
                                                    <option value="------">------</option>
                                                    <?php $sql = "SELECT * FROM `customer` where `role` = 'Supplier' or `role` = 'Silver Supplier'";
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
                                                <label for="ttype" class="text-primary">Transfer Type:</label>
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
                                                <label for="Paid" class="text-primary">Paid Method</label>
                                                <select id="Paid" name="Paid" class="form-select">
                                                    <option value="------">------</option>
                                                    <option value="Cash">Cash</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12 my-2">
                                            <div class="form-group text-center my-2">
                                                <button type="submit" name="insert" class="btn btn-primary"><i class="fa fa-plus rounded-circle bg-white p-1 text-primary mx-1"></i> Insert</button>
                                                <button type="reset" name="clear" class="btn btn-danger"><i class="fa fa-times rounded-circle bg-white p-1 text-primary mx-1"></i> Clear</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
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