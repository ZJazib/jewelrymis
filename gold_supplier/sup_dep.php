    <!-- Sidebar Start -->
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
    <script src="ckeditor/ckeditor.js"></script>
    <link href="css/style.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="bg-primary">
    <?php include "Seibar.php"; ?>
    <!-- Sidebar End -->

    <!-- Content Start -->
    <div class="content">
        <!-- Navbar Start -->
        <?php include "navbar.php"; ?>
        <!-- Navbar End -->

        <?php
        if (isset($_POST['in'])) {
            $name = $_POST['name'];
            $ref = $_POST['ref'];
            $id = $_GET['t'];
            $price = $_POST['price'];
             $amo = "INSERT INTO `account`(`cus_id`, `ref`, `amount`, `amo_cre`, `gold`, `role`, `state`, `type`, `com`) VALUES ('$id','$ref','$mc','0','$goldg','Supplier','Cash','Credit','0')";
            mysqli_query($conn, $amo);
        }
        ?>

        <?php
        if (isset($_GET['t'])) {
            $sql = "SELECT * FROM `customer` where `role`='Supplier' and id =" . $_GET['t'];
            $res = mysqli_query($conn, $sql);
            $cus = mysqli_fetch_assoc($res);

            $sqli = "SELECT SUM(amount) as total FROM `account` where `role`='Supplier' and cus_id =" . $_GET['t'];
            $resi = mysqli_query($conn, $sqli);
            $cusi = mysqli_fetch_assoc($resi);
        ?>
            <div class="container-fluid pt-4 px-5">
                <div class=" rounded align-items-center bg-light justify-content-center mx-0">
                    <div class="container py-2">
                        <div class="row">
                            <div class="col-md-12">
                                <h5>Total Debit Money: <?= $cusi['total'] ?></h5>
                                <form action="" method="post" enctype="multipart/form-data">
                                    <div class="card">
                                        <div class="card-header text-center bg-primary ">
                                            <h3 class="text-white mt-2">Add New Supplier Transiction</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4 my-2">
                                                    <div class="form-group">
                                                        <label class="text-primary" for="Name">Name</label>
                                                        <input type="text" class="form-control" id="Name" value="<?= $cus['name'] ?>" name="name" placeholder="Write Name Here">
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
                                                        <label for="Paid" class="text-primary">Paid Method</label>
                                                        <select id="Paid" name="Paid" class="form-select">
                                                            <option value="------">------</option>
                                                            <option value="Cash">Cash</option>
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
        } else {
        ?>
            <div class="container-fluid pt-4 px-5">
                <div class=" rounded align-items-center bg-light justify-content-center mx-0">
                    <div class="container py-2">

                        <h4 class="text-center my-4 bg-primary rounded p-2 text-white">Add Credit Supplier Money</h4>

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
                                <h5 class="text-center my-3 text-primary rounded p-2 bg-white">Suppliers list</h5>
                                <?php
                                $sql = "SELECT * FROM `customer` where `role`='Supplier'";
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
                                            <td class="text-center"><a class=" btn btn-sm btn-success" href="?t=<?= $emp['id']; ?>"><i class="far fa-plus"></i></a></td>
                                        </tr>
                                <?php
                                    }
                                } else {
                                    echo '<th class="text-dark text-center" colspan="11">No Data Exist</th>';
                                }
                                ?>
                            </tbody>
                        </table>
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
    <script src="js/main.js"></script>
    </body>

    </html>