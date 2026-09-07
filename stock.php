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

        include "Dbconn.php";
        if (isset($_POST['insertc'])) {
            @$name = $_POST['name'];
            @$role = $_POST['role'];
            if (empty($name) && empty($NIC) && empty($phone) && empty($email) && empty($loc) && empty($role)) {
                $err = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
<i class="fa fa-exclamation-circle me-2"></i>Please fill the blanks!
<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>';
            } else {
                $cuss = "SELECT * FROM customer WHERE `name`='$name' and `role`='$role'";
                $ress = mysqli_query($conn, $cuss);
                $userc = mysqli_fetch_assoc($ress);
                $id = $userc['id'];

                $name = $userc['name'];
                $type = $_POST['type'];
                $typec = $_POST['typec'];
                $ref = $_POST['ref'];
                $bill = $_POST['bill'];
                $totalg = $_POST['totalg'];
                $Paid = $_POST['Paid'];
                @$totalpure = $_POST['totalpure'];


                if ($type == "Debit") {

                    $debs = "INSERT INTO `storage`(`st_cus`, `st_gold`, `type`,`method`) VALUES ('$id','$totalg','$Paid','Debit')";
                    mysqli_query($conn, $debs);
                    if ($typec == "Debit") {

                        $debsd = "INSERT INTO `storage`(`st_cus`, `st_price`, `type`,`method`) VALUES ('$id','$bill','$Paid','Debit')";
                        mysqli_query($conn, $debsd);
    
                        $suc = '
                                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                                        <i class="fa fa-exclamation-circle me-2"></i>price Supplier With Debit price Added!
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                ';
                    }
                    if ($typec == "Credit") {
                        $debsc = "INSERT INTO `storage`(`st_cus`, `st_price`, `type`,`method`) VALUES ('$id','$bill','$Paid','Credit')";
                        mysqli_query($conn, $debsc);
                        $suc = '
                                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                                        <i class="fa fa-exclamation-circle me-2"></i> price Supplier With Credit price Added!
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                ';
                    }
                    $suc = '
                                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                                            <i class="fa fa-exclamation-circle me-2"></i>Gold Supplier With Debit Gold Added!
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    ';
                }
                if ($type == "Credit") {
                    $debs = "INSERT INTO `storage`(`st_cus`, `st_gold`, `type`,`method`) VALUES ('$id','$totalg','$Paid','Credit')";
                    mysqli_query($conn, $debs);

                    if ($typec == "Debit") {

                        $debsd = "INSERT INTO `storage`(`st_cus`, `st_price`, `type`,`method`) VALUES ('$id','$bill','$Paid','Debit')";
                        mysqli_query($conn, $debsd);
    
                        $suc = '
                                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                                        <i class="fa fa-exclamation-circle me-2"></i>price Supplier With Debit price Added!
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                ';
                    }
                    if ($typec == "Credit") {
                        $debsc = "INSERT INTO `storage`(`st_cus`, `st_price`, `type`,`method`) VALUES ('$id','$bill','$Paid','Credit')";
                        mysqli_query($conn, $debsc);
                        $suc = '
                                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                                        <i class="fa fa-exclamation-circle me-2"></i> price Supplier With Credit price Added!
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                ';
                    }
                    $suc = '
                                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                                            <i class="fa fa-exclamation-circle me-2"></i> Gold Supplier With Credit Gold Added!
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    ';
                }
                
            }
        }


        ?>
        <!-- slider Start -->
        <div class="container-fluid ">
            <div class="rounded align-items-center bg-light justify-content-center mx-0">
                <div class="container py-2">
                    <div class="row">
                        <?php
                        if (isset($_GET['id'])) {
                            $stock = "SELECT * FROM customer WHERE `id`=" . $_GET['id'];
                            $ressk = mysqli_query($conn, $stock);
                            $userck = mysqli_fetch_assoc($ressk);
                        ?>
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
                                        <h3 class="text-white mt-2">Data Entry form of Stock</h3>
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
                                                    <input type="text" class="form-control" id="cus_name" value="<?= $userck['name'] ?>" name="name" placeholder="Write Name Here">
                                                </div>
                                            </div>
                                            <div class="col-md-4 my-2">
                                                <label class="text-primary" for="Paid Method">Role</label>
                                                <div class="select-control">
                                                    <select name="role" id="" class="form-select">
                                                        <option value="<?= $userck['role'] ?>" selected><?= $userck['role'] ?></option>
                                                        <option value="stock">stock</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4 my-2">
                                                <div class="form-group">
                                                    <label class="text-primary" for="Referance">Referance</label>
                                                    <input type="text" class="form-control" id="Referance" value="<?= $userck['ref'] ?>" name="ref" placeholder="Write Here">
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
<?php
                        } else {
?>
    <table class="table text-start align-middle table-bordered table-hover mb-0">
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
            <h5 class="text-center my-3 text-primary rounded p-2 bg-white">Stock</h5>
            <?php
                            $sql = "SELECT * FROM `customer` where `role`='Stock'";
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
                        <td class="text-center"><a class=" btn btn-sm btn-success" href="stock.php?id=<?= $emp['id']; ?>"><i class="far fa-eye"></i></a></td>
                    </tr>
        <?php
                                }
                            } else {
                                echo '<th class="text-dark text-center" colspan="11">No Data Exist</th>';
                            }
                        }
        ?>
        <!-- Footer Start Strat -->
        <?php
        require_once "footer.php";
        ?>
        <!-- Footer Start Strat END -->