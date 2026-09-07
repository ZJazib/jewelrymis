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

    
    <!-- select the Silver Supplier -->
    <div class="container-fluid my-5">
        <div class="rounded align-items-center bg-light justify-content-center mx-0">
            <div class="container py-2">
            <a class="text-center btn btn-success my-3 w-25" href="../monthly_journal/mjournal.php"><i class="fa fa-circle-arrow-left w-100"></i><b>  Monthly Journal</b></a>
                        <a class="text-center btn btn-outline-dark m-3 w-25" href="../index.php"><i class="fa fa-circle-arrow-left w-100"></i><b> Dashboard</b></a>
            <form id="search-form" action="search.php" method="get">
                <div class="input-group my-4">
                    <i class="fa fa-search bg-primary text-white p-3"></i>
                    <input type="text" class="form-control" name="jbm" id="search-input" 
                           placeholder="Search for Silver Supplier Name, NIC, and Location" 
                           aria-label="Search for Silver Supplier Name, NIC, and Location" 
                           aria-describedby="search-btn">
                </div>
            </form>
            <div id="search-results">
                <?php if (isset($_GET['msg'])) {
        echo   $msg = '
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="fa fa-exclamation-circle me-2"></i>New Silver Supplier Added!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                ';
    } ?>


                <div id="search-results">
                    <table class="table text-start align-middle table-bordered table-hover mb-0">
                        <thead>
                            <tr class="text-white bg-primary text-center">
                                <th scope="col">SRN</th>
                                <th scope="col">Name</th>
                                <th scope="col">Phone</th>
                                <th scope="col">Email</th>
                                <th scope="col">Location</th>
                                <th scope="col" colspan="2">Action Gold</th>
                            </tr>
                        </thead>
                        <tbody>
                            <h5 class="text-center my-3 text-primary rounded p-2 bg-white">Silver Suppliers List</h5>
                            <?php
                            $sql = "SELECT * FROM `customer` where `role`= 'Silver Supplier' order by customer.date DESC";
                            $res = mysqli_query($conn, $sql);
                            if (mysqli_num_rows($res) > 0) {
                                while ($emp = mysqli_fetch_assoc($res)) {

                            ?>
                                    <tr class="bg-white text-center">
                                        <td><?= @$emp['nic'] ?></td>
                                        <td><?= $emp['name'] ?></td>
                                        <td><?= $emp['phone'] ?></td>
                                        <td><?= $emp['email'] ?></td>
                                        <td><?= $emp['loc'] ?></td>
                                        <td class="text-center"><a class=" btn btn-sm btn-success" href="newsell.php?id=<?= $emp['id']; ?>"><i class="far fa-plus"></i> Debit (بردگی)</a></td>
                                        <td class="text-center"><a class=" btn btn-sm btn-light" href="newbuy.php?id=<?= $emp['id']; ?>"><i class="far fa-plus"></i>  Credit (رسیدگی)</a></td>
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
        <!-- JavaScript code -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $('#search-input').on('input', function () {
            var query = $(this).val().trim();
            if (query !== '') {
                $.ajax({
                    url: 'search.php',
                    method: 'GET',
                    data: { jbm: query },
                    success: function (response) {
                        $('#search-results').html(response);
                    },
                    error: function () {
                        $('#search-results').html('<p class="text-danger">Error fetching results.</p>');
                    }
                });
            } else {
                $('#search-results').html(''); // Clear results when input is empty
            }
        });
    });
</script>
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