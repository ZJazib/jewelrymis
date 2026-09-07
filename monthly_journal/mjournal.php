<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>ZMIS</title>
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta content="Admin Penal" name="keywords">
  <meta content="Admin Penal" name="description">

  <!-- Favicon -->
  <link href="../favicon.ico" rel="icon">

  <!-- Google Web Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- Icon Font Stylesheet -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <!-- Libraries Stylesheet -->
  <link href="../lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
  <link href="../lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

  <!-- Customized Bootstrap Stylesheet -->
  <link href="../css/bootstrap.min.css" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/vfs_fonts.js"></script>

  <!-- Template Stylesheet -->

  <link href="../css/style.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="bg-light">
  <!-- Sidebar Start -->


  <!-- Content Start -->



    <?php
    require "../config/Dbconn.php";
    ?>
    <!-- Blank Start -->

    <!-- slider Start -->
    <?php
    if (isset($_GET['id']) and isset($_GET['find'])) {
      $id = $_GET['id'];
      @$to = mysqli_escape_string($conn, $_GET['to']);
      @$from = mysqli_escape_string($conn, $_GET['from']);

      $sqli = "SELECT SUM(amount) as total FROM `account` where `date` between '$from' and '$to' and cus_id =" . $_GET['id'];
      $resi = mysqli_query($conn, $sqli);
      $cusi = mysqli_fetch_assoc($resi);
      $sql = "SELECT SUM(amo_cre) as totalc FROM `account` where `date` between '$from' and '$to' and cus_id =" . $_GET['id'];
      $res = mysqli_query($conn, $sql);
      $cus = mysqli_fetch_assoc($res);


      $sqlkg = "SELECT SUM(gold) as totalkg FROM `account` where `type`='Debit' and `date` between '$from' and '$to' and cus_id=" . $_GET['id'];
      $reskg = mysqli_query($conn, $sqlkg);
      $cuskg = mysqli_fetch_assoc($reskg);

      $sqli = "SELECT * FROM `customer` where id ='$id'";
      $ress = mysqli_query($conn, $sqli);
      $empp = mysqli_fetch_assoc($ress);
    ?>

      <div class="container-xxl my-5">
        <div class="rounded align-items-center bg-light justify-content-center mx-0">
          <div class="container py-2">
            <h4 class="text-center my-4 bg-primary rounded p-2 text-white">Monthly Journal کهاته</h4>
            <div class="row">
              <div class="col-md-3 bg-dark rounded mx-3">
                <h5 class="bg-white p-2 text-primary text-center">Personnel Information</h5>
                <h6 class="text-white px-4 ">Role: <span> <?= $empp['role']; ?></span> </h6>
                <h6 class="text-white px-4 ">Name: <span> <?= $empp['name']; ?></span> </h6>
                <h6 class="text-white px-4 ">Location: <span> <?= $empp['loc']; ?></span> </h6>
              </div>
              <div class="col-md-4 bg-dark rounded mx-3">
                <h5 class="bg-white p-2 text-primary text-center">Total Money In Account</h5>
                <h5 class="text-white px-4 ">Credit Money: <span> <?= number_format(@$cus['totalc'], 3) ?></span></h5>
                <h5 class="text-white px-4 ">Debit Money: <span> <?= number_format(@$cusi['total'], 3) ?></span> </h5>
                <h5 class="text-white ">
                  <?php

                  if ($cusi['total'] > $cus['totalc']) {
                    echo "<h5 class='text-white px-4 '>Total Debit: <span>", number_format($cusi['total'] - $cus['totalc'], 3);
                    # code...
                  } else if ($cusi['total'] < $cus['totalc']) {
                    echo "<h5 class='text-white px-4 '>Total Credit: <span>",  number_format($cus['totalc'] - $cusi['total'], 3);
                    # code...
                  }
                  ?></h5>
              </div>
              <div class="col-md-4 bg-dark rounded">
                <h5 class="bg-white p-2 text-primary text-center">Total Gold In Account</h5>
                <?php
                @$totalkg = floatval($cuskg['totalkg']);
                @$totalckg = floatval($cusckg['totalckg']);
                ?>
                <h5 class="text-white px-4">Credit Gold: <span> <?= number_format($totalckg, 3) ?></span></h5>

                <h5 class="text-white px-4">Debit Gold: <span> <?= number_format($totalkg, 3) ?> </span> </h5>
                <h5 class="text-white ">
                  <?php

                  if (@$totalkg > $totalckg) {
                    echo "<span class='text-white mx-4'>Total Debit: ", number_format(@$totalkg - $totalckg, 3), "</span>";
                    # code...
                  } elseif (@$totalkg < $totalckg) {
                    echo "<span class='px-4 text-white p-2 text-center w-100'>Total Credit: ", number_format($totalckg - @$totalkg, 3), "</span>";
                    # code...
                  }
                  ?></h5>
              </div>
            </div>
            <form action="">
              <input class="text-light bg-light border-0 m-0 p-0" type="text" name="id" value="<?= $id ?>" id="">
              <div class="row my-3">
                <div class="col-md-4">

                  <label for="" class="h6">From:</label>
                  <input class="form-control" type="date" name="from" id="from">
                </div>
                <div class="col-md-4">
                  <label for="" class="h6">To:</label>
                  <input class="form-control" type="date" name="to" id="to">

                </div>
                <div class="col-md-4">
                  <input class="btn btn-primary mt-4 w-100" type="submit" name="find" id="" value="Find">
                </div>
              </div>
            </form>
            <a href="statment.php?id=<?= $id ?>&from=<?= $from ?>&to=<?= $to ?>" class="btn btn-success w-25 m-2"><i class="far fa-file"></i> Generate Statement</a>

            <div class="table-responsive ">
              <table class="table text-start align-middle table-bordered table-hover mb-0">
                <thead>
                  <tr class="text-white bg-primary text-center">
                    <th colspan="8" class="bg-dark text-white">
                      <h5 class="text-center bg-dark text-white">Debit Gold (بردگی طلا) </h5>
                    </th>
                  </tr>
                  <tr class="text-white bg-primary text-center">
                    <th scope="col">Role</th>
                    <th scope="col">Name</th>
                    <th scope="col">Reference</th>
                    <th scope="col">Total Gross Weight Grams</th>
                    <th scope="col">Total Price</th>
                    <th scope="col">Pure Weight/ Comission</th>
                    <th scope="col">Paid Method</th>
                    <th scope="col">Date</th>
                  </tr>
                </thead>

                <tbody>

                  <?php
                  $sql = "SELECT * FROM debit WHERE dep_tgold !='0' and dep_cus = '$id' and `date` between '$from' and '$to'";
                  $res = mysqli_query($conn, $sql);
                  if (mysqli_num_rows($res) > 0) {
                    while ($emp = mysqli_fetch_assoc($res)) {
                      $sqli = "SELECT * FROM `customer` where id = " . $emp['dep_cus'];
                      $ress = mysqli_query($conn, $sqli);
                      $empp = mysqli_fetch_assoc($ress);


                  ?>

                      <tr class="text-white bg-dark text-center">
                        <td><?= $empp['role'] ?></td>

                        <td><?= $emp['sate'] . " " . $empp['name'] ?></td>
                        <td><?= $emp['ref'] ?></td>
                        <td><?= number_format(@$emp['dep_tgold'], 3) ?></td>
                        <td><?= number_format(@$emp['dep_tbill'], 3) ?></td>
                        <td><?php if ($emp['dep_tpurity'] == 0) {
                              echo $emp['com'];
                            } else {
                              echo number_format(@$emp['dep_tpurity'], 3);
                            }


                            ?></td>

                        <td><?= @$empc['typec'] ?></td>

                        <td><?php @$timestamp = $emp['date'];
                            $am_pm_time = date("F j, Y h:i:s A", strtotime($timestamp));
                            echo $am_pm_time;
                            ?></td>

                      </tr>
                  <?php
                    }
                  }
                  ?>
                  <tr class="text-white bg-primary text-center">
                    <th colspan="8" class="bg-dark text-white">
                      <h5 class="text-center bg-dark text-white">Debit Money (بردگی پول) </h5>
                    </th>

                  </tr>
                  <tr class="text-white bg-primary text-center">
                    <th scope="col">Role</th>
                    <th scope="col">Name</th>
                    <th scope="col">Reference</th>
                    <th scope="col" colspan="2">Total Price</th>
                    <th scope="col">Transfer Type</th>
                    <th scope="col">Paid Method</th>
                    <th scope="col">Date</th>
                  </tr>
                  <?php
                  $sql = "SELECT * FROM `account` where cus_id = '$id' and `date` between '$from' and '$to'";
                  $res = mysqli_query($conn, $sql);
                  if (mysqli_num_rows($res) > 0) {
                    while ($empa = mysqli_fetch_assoc($res)) {
                      $sqli = "SELECT * FROM `customer` where id = " . $empa['cus_id'];
                      $ress = mysqli_query($conn, $sqli);
                      $empp = mysqli_fetch_assoc($ress);
                  ?>
                      <?php
                      if ($empa['amount'] != 0) {


                      ?>
                        <tr class="text-white bg-dark text-center">
                          <td><?= $empp['role'] ?></td>

                          <td>Debit Money <?= $empp['name'] ?></td>
                          <td><?= $empa['ref'] ?></td>

                          <td colspan="2">
                            <?= number_format($empa['amount'], 3) ?></td>
                          <td>Debit</td>
                          <td><?= $empa['state'] ?></td>
                          <td><?php @$timestamp = $empa['date'];
                              $am_pm_time = date("F j, Y h:i:s A", strtotime($timestamp));
                              echo $am_pm_time;
                              ?></td>
                        </tr>
                  <?php
                      }
                    }
                  }
                  ?>
                  <?php
                  $sql = "SELECT * FROM debit WHERE dep_tgold ='0' and dep_cus = '$id' and `date` between '$from' and '$to'";
                  $res = mysqli_query($conn, $sql);
                  if (mysqli_num_rows($res) > 0) {
                    while ($emp = mysqli_fetch_assoc($res)) {
                      $sqli = "SELECT * FROM `customer` where id = " . $emp['dep_cus'];
                      $ress = mysqli_query($conn, $sqli);
                      $empp = mysqli_fetch_assoc($ress);


                  ?>

                      <tr class="text-white bg-dark text-center">
                        <td><?= $empp['role'] ?></td>

                        <td><?= $emp['sate'] . " " . $empp['name'] ?></td>
                        <td><?= $emp['ref'] ?></td>
                        <td colspan="2"><?= number_format(@$emp['dep_tbill'], 3), " Commission ", $emp['com'] ?></td>
                        <td><?php
                            echo $emp['sate'];
                            ?></td>

                        <td><?= @$emp['typec'] ?></td>

                        <td><?php @$timestamp = $emp['date'];
                            $am_pm_time = date("F j, Y h:i:s A", strtotime($timestamp));
                            echo $am_pm_time;
                            ?></td>

                      </tr>
                  <?php
                    }
                  }
                  ?>
                  <tr class="text-white bg-primary text-center">
                    <th colspan="8" class="bg-dark text-white">
                      <h5 class="text-center bg-dark text-white">Credit Gold (رسیدگی طلا)</h5>
                    </th>
                  </tr>
                  <tr class="text-white bg-primary text-center">
                    <th scope="col">Role</th>
                    <th scope="col">Name</th>
                    <th scope="col">Reference</th>
                    <th scope="col">Total Gold Grams</th>
                    <th scope="col">Total Price</th>
                    <th scope="col">Pure Weight/Commission</th>
                    <th scope="col">Paid Method</th>
                    <th scope="col">Date</th>
                  </tr>

                  <?php
                  $sql = "SELECT * FROM credit where cre_cus ='$id' and `date` between '$from' and '$to'";
                  $res = mysqli_query($conn, $sql);
                  if (mysqli_num_rows($res) > 0) {
                    while ($empc = mysqli_fetch_assoc($res)) {
                      $sqli = "SELECT * FROM `customer` where id = " . $empc['cre_cus'];
                      $ress = mysqli_query($conn, $sqli);
                      $empp = mysqli_fetch_assoc($ress);
                  ?>
                      <tr class="text-white bg-dark text-center">
                        <td><?= $empp['role'] ?></td>
                        <td><?= $empc['sate'] . " " . " " . $empp['name'] ?></td>
                        <td><?= $empc['ref'] ?></td>
                        <td><?= number_format($empc['cre_tgold'], 3) ?></td>
                        <td> <?php
                              echo $empc['cre_tbill'];

                              ?> </td>
                        <td>
                          <?php
                          if ($empc['com'] == 0) {
                            echo number_format(@$empc['cre_tpurity'], 3);
                          } else {
                            echo number_format($empc['com'], 3);
                          }

                          ?>
                        <td><?= @$empc['typec'] ?></td>
                        <td><?php @$timestamp = $empc['date'];
                            $am_pm_time = date("F j, Y h:i:s A", strtotime($timestamp));
                            echo $am_pm_time;
                            ?></td>

                      </tr>
                  <?php
                    }
                  }
                  ?>
                  <tr class="text-white bg-primary text-center">
                    <th colspan="8" class="bg-dark text-white">
                      <h5 class="text-center bg-dark text-white">Credit Money (رسیدگی پول)</h5>
                    </th>
                  </tr>
                  <tr class="text-white bg-primary text-center">
                    <th scope="col">Role</th>
                    <th scope="col">Name</th>
                    <th scope="col">Reference</th>
                    <th scope="col" colspan="2">Total Price</th>
                    <th scope="col">Transfer Type</th>
                    <th scope="col">Paid Method</th>
                    <th scope="col">Date</th>
                  </tr>
                  <?php
                  $sql = "SELECT * FROM `account` where cus_id ='$id' and `date` between '$from' and '$to'";
                  $res = mysqli_query($conn, $sql);
                  if (mysqli_num_rows($res) > 0) {
                    while ($empas = mysqli_fetch_assoc($res)) {
                      $sqli = "SELECT * FROM `customer` where id = " . $empas['cus_id'];
                      $ress = mysqli_query($conn, $sqli);
                      $empp = mysqli_fetch_assoc($ress);
                  ?>
                      <?php
                      if ($empas['amo_cre'] != 0) {


                      ?>
                        <tr class="text-white bg-dark text-center">
                          <td><?= $empp['role'] ?></td>

                          <td>Credit Money <?= $empp['name'] ?></td>
                          <td><?= $empas['ref'] ?></td>

                          <td colspan="2">
                            <?= number_format($empas['amo_cre'], 3) ?></td>
                          <td>Credit</td>
                          <td><?= $empas['state'] ?></td>
                          <td><?php @$timestamp = $empas['date'];
                              $am_pm_time = date("F j, Y h:i:s A", strtotime($timestamp));
                              echo $am_pm_time;
                              ?></td>
                        </tr>
                  <?php
                      }
                    }
                  }
                  ?>

                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
  </div>
<?php
    }
    if (isset($_GET['id'])) {
      $id = $_GET['id'];

      // Query for the sum of gold and money transactions
      $sqli = "SELECT 
        se_cus,
        SUM(CASE WHEN se_type = 'Debit' THEN se_tpurity ELSE 0 END) AS total_debit_gold,
        SUM(CASE WHEN se_type = 'Credit' THEN se_tpurity ELSE 0 END) AS total_credit_gold,
        (SUM(CASE WHEN se_type = 'Credit' THEN se_tpurity ELSE 0 END) - 
         SUM(CASE WHEN se_type = 'Debit' THEN se_tpurity ELSE 0 END)) AS total_gold_in_account,
         
        SUM(CASE WHEN se_type = 'Debit' THEN se_tbill ELSE 0 END) AS total_debit_money,
        SUM(CASE WHEN se_type = 'Credit' THEN se_tbill ELSE 0 END) AS total_credit_money,
        (SUM(CASE WHEN se_type = 'Credit' THEN se_tbill ELSE 0 END) - 
         SUM(CASE WHEN se_type = 'Debit' THEN se_tbill ELSE 0 END)) AS total_money_in_account
    FROM 
        statement
    WHERE 
        se_cus = ?
    GROUP BY 
        se_cus";

      // Prepare and execute the query
      $stmt = $conn->prepare($sqli);
      $stmt->bind_param("i", $id);
      $stmt->execute();
      $resi = $stmt->get_result();
      $cusi = $resi->fetch_assoc();

      // Query for customer information
      $sqli = "SELECT * FROM `customer` WHERE id = ?";
      $stmt = $conn->prepare($sqli);
      $stmt->bind_param("i", $id);
      $stmt->execute();
      $ress = $stmt->get_result();
      $empp = $ress->fetch_assoc();
?>
  <div class="container-fluid my-5">
    <div class="rounded align-items-center bg-light justify-content-center mx-0">
      <div class="container py-2">
        <h4 class="text-center my-4 bg-primary rounded p-2 text-white">Monthly Journal کهاته</h4>
        <div class="row">
          <div class="col-md-3 bg-dark rounded mx-3">
            <h5 class="bg-white p-2 text-primary text-center">Personnel Information</h5>
            <h6 class="text-white px-4">Role: <span><?= $empp['role']; ?></span></h6>
            <h6 class="text-white px-4">Name: <span><?= $empp['name']; ?></span></h6>
            <h6 class="text-white px-4">Location: <span><?= $empp['loc']; ?></span></h6>
          </div>
          <div class="col-md-4 bg-dark rounded mx-3">
            <h5 class="bg-white p-2 text-primary text-center">Total Money In Account</h5>
            <h5 class="text-white px-4">Debit Money: <span><?= number_format(@$cusi['total_debit_money'], 3); ?></span></h5>
            <h5 class="text-white px-4">Credit Money: <span><?= number_format(@$cusi['total_credit_money'], 3); ?></span></h5>
            <h5 class="text-white px-4">
              <?php
              $money_balance = $cusi['total_credit_money'] - $cusi['total_debit_money'];
              if ($money_balance > 0) {
                echo "<span>Total Credit: " . number_format($money_balance, 3) . "</span>";
              } else {
                echo "<span>Total Debit: " . number_format(abs($money_balance), 3) . "</span>";
              }
              ?>
            </h5>
          </div>
          <div class="col-md-4 bg-dark rounded">
            <h5 class="bg-white p-2 text-primary text-center">Total Gold In Account</h5>
            <?php
            $total_debit_gold = floatval($cusi['total_debit_gold']);
            $total_credit_gold = floatval($cusi['total_credit_gold']);
            ?>
            <h5 class="text-white px-4">Credit Gold: <span><?= number_format($total_credit_gold, 3); ?></span></h5>
            <h5 class="text-white px-4">Debit Gold: <span><?= number_format($total_debit_gold, 3); ?></span></h5>
            <h5 class="text-white px-4">
              <?php
              $gold_balance = $total_credit_gold - $total_debit_gold;
              if ($gold_balance > 0) {
                echo "<span>Total Credit: " . number_format($gold_balance, 3) . "</span>";
              } else {
                echo "<span>Total Debit: " . number_format(abs($gold_balance), 3) . "</span>";
              }
              ?>
            </h5>
          </div>
        </div>
        <form action="">
          <input class="text-light bg-light border-0 m-0 p-0" type="text" name="id" value="<?= $id ?>" id="">
          <div class="row my-3">
            <div class="col-md-4">

              <label for="" class="h6">From:</label>
              <input class="form-control" type="date" name="from" id="from">
            </div>
            <div class="col-md-4">
              <label for="" class="h6">To:</label>
              <input class="form-control" type="date" name="to" id="to">

            </div>
            <div class="col-md-4">
              <input class="btn btn-primary mt-4 w-100" type="submit" name="find" id="" value="Find">
            </div>
          </div>
        </form>
        <?php
// Determine the correct page based on role
$statementPage = "statment.php?id=$id"; // default
if (isset($emp['role']) && (stripos($emp['role'], 'Silver Customer') !== false || stripos($emp['role'], 'Silver Supplier') !== false)) {
    $statementPage = "Silver_statment.php?id=$id";
}
?>

<a href="<?= $statementPage ?>" class="btn btn-success w-25 m-2">
    <i class="far fa-file"></i> Generate Statement
</a>


        <?php
        if (!empty($_GET['from'])) {
          @$id = $_GET['id'];
          @$to = mysqli_escape_string($conn, $_GET['to']);
          @$from = mysqli_escape_string($conn, $_GET['from']);
          if (empty($to) and empty($from)) {
            $to = date("Y-m-d");
          } ?>
          <div class="table-responsive ">
            <table class="table text-start align-middle table-bordered table-hover mb-0">
              <thead>
                <tr class="text-white bg-primary text-center">
                  <th scope="col" rowspan="2">Date</th>
                  <th scope="col" rowspan="2">Reference</th>
                  <th scope="col" rowspan="2">Description</th>
                  <th colspan="3">Base Currecny Amount</th>
                  <th colspan="3">Gold Qty. In CT</th>

                <tr class="text-white bg-primary text-center">
                  <th>debit</th>
                  <th>credit</th>
                  <th>Balance</th>
                  <th>debit</th>
                  <th>credit</th>
                  <th>Balance</th>
                </tr>
                </tr>
              </thead>

              <tbody>
                <?php
                $sql = "SELECT * FROM `statement` where se_cus='$id' and se_date between '$from' and '$to'";
                $res = mysqli_query($conn, $sql);

                $debitTotal = 0;
                $creditTotal = 0;
                $goldDebitTotal = 0;
                $goldCreditTotal = 0;

                while ($row = mysqli_fetch_assoc($res)) {
                  $type = mysqli_real_escape_string($conn, $row['se_type']);
                  $date = date("F j, Y", strtotime($row['se_date']));

                  $se_tbill = floatval($row['se_tbill']); // Base currency amount
                  $se_tpurity = floatval($row['se_tpurity']); // Gold quantity

                  if ($type == "Debit") {
                    $debitTotal += $se_tbill;
                    $goldDebitTotal += $se_tpurity;
                  } elseif ($type == "Credit") {
                    $creditTotal += $se_tbill;
                    $goldCreditTotal += $se_tpurity;
                  }

                  // Calculate running balances
                  $balance = $debitTotal - $creditTotal;
                  $goldBalance = $goldDebitTotal - $goldCreditTotal;

                  // Display values, showing empty string if 0 or 0.000
                  $debitValue = ($type == "Debit" && $se_tbill > 0) ? number_format($se_tbill, 3) : '';
                  $creditValue = ($type == "Credit" && $se_tbill > 0) ? number_format($se_tbill, 3) : '';

                  $goldDebitValue = ($type == "Debit" && $se_tpurity > 0) ? number_format($se_tpurity, 3) : '';
                  $goldCreditValue = ($type == "Credit" && $se_tpurity > 0) ? number_format($se_tpurity, 3) : '';

                  // Format balances
                  $balanceValue = ($balance < 0) ? number_format(abs($balance), 3) : number_format($balance, 3);
                  $goldBalanceValue = ($goldBalance < 0) ? number_format(abs($goldBalance), 3) : number_format($goldBalance, 3);
                ?>
                  <tr class="text-white bg-primary text-center">
                    <td><?= htmlspecialchars($date) ?></td>
                    <td><?= $type == "Debit" ? 'DE00' . htmlspecialchars($row['se_id']) : 'CR00' . htmlspecialchars($row['se_id']) ?></td>
                    <td class="left"><?= $row['dis'] ?></td>
                    <td><?= $debitValue ? "($debitValue)" : '' ?></td>
                    <td><?= $creditValue ?></td>
                    <td><?= $balanceValue ?></td>
                    <td><?= $goldDebitValue ? "($goldDebitValue)" : '' ?></td>
                    <td><?= $goldCreditValue ?></td>
                    <td><?= $goldBalanceValue ?></td>
                  </tr>
                <?php } ?>

                <!-- Final total row -->
                <tr class="text-dark">
                  <td colspan="3">Total</td>
                  <td><?= ($debitTotal != 0) ? "($" . number_format($debitTotal, 3) . ")" : '' ?></td>
                  <td><?= ($creditTotal != 0) ? number_format($creditTotal, 3) : '' ?></td>
                  <td><?= ($balance != 0) ? ($balance < 0 ? "($" . number_format(abs($balance), 3) . ")" : number_format($balance, 3)) : '' ?></td>
                  <td><?= ($goldDebitTotal != 0) ? "($" . number_format($goldDebitTotal, 3) . ")" : '' ?></td>
                  <td><?= ($goldCreditTotal != 0) ? number_format($goldCreditTotal, 3) : '' ?></td>
                  <td><?= ($goldBalance != 0) ? ($goldBalance < 0 ? number_format(abs($goldBalance), 3) : number_format($goldBalance, 3)) : '' ?></td>
                </tr>
              </tbody>
            </table>

            <!-- Summary of Total Credit/Debit -->
            <div style="margin-top: 20px;">
              <strong>Total Balance Amount (USD): </strong>
              <?= ($balance != 0) ? ($balance < 0 ? number_format(abs($balance), 3) : number_format($balance, 3)) : '' ?>
              <?php
              echo ($creditTotal > $debitTotal) ? "Credit" : "Debit";
              ?>
            </div>
            <div>
              <strong>Total Gold Balance: </strong>
              <?= ($goldBalance != 0) ? ($goldBalance < 0 ? number_format(abs($goldBalance), 3) : number_format($goldBalance, 3)) : '' ?>
              <?php
              echo ($goldCreditTotal > $goldDebitTotal) ? "Credit" : "Debit";
              ?>
            </div>

          </div>

        <?php

        } else {
          $id = $_GET['id'];

          $to = date("Y-m-d");
        }
        if ($id) { ?>

          <div class="table-responsive">
            <table class="table text-start align-middle table-bordered table-hover mb-0">
              <thead>
                <tr class="text-white bg-primary text-center">
                  <th rowspan="2">Date</th>
                  <th rowspan="2">Reference</th>
                  <th rowspan="2" class="left">Description</th>
                  <th colspan="3">Base Currency Amount</th>
                  <th colspan="3">Gold Quantity (CT)</th>
                  <th colspan="2" rowspan="2">Action</th>
                </tr>
                <tr class="text-primary bg-light text-center">
                  <th>Debit</th>
                  <th>Credit</th>
                  <th>Balance</th>
                  <th>Debit</th>
                  <th>Credit</th>
                  <th>Balance</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $sql = "SELECT * FROM `statement` WHERE se_cus='$id' ";
                $res = mysqli_query($conn, $sql);

                $debitTotal = 0;
                $creditTotal = 0;
                $goldDebitTotal = 0;
                $goldCreditTotal = 0;

                while ($row = mysqli_fetch_assoc($res)) {
                  $type = mysqli_real_escape_string($conn, $row['se_type']);
                  $date = date("F j, Y", strtotime($row['se_date']));

                  $se_tbill = floatval($row['se_tbill']); // Base currency amount
                  $se_tpurity = floatval($row['se_tpurity']); // Gold quantity

                  if ($type == "Debit") {
                    $debitTotal += $se_tbill;
                    $goldDebitTotal += $se_tpurity;
                  } elseif ($type == "Credit") {
                    $creditTotal += $se_tbill;
                    $goldCreditTotal += $se_tpurity;
                  }

                  // Calculate running balances
                  $balance = $debitTotal - $creditTotal;
                  $goldBalance = $goldDebitTotal - $goldCreditTotal;

                  // Display values, showing empty string if 0 or 0.000
                  $debitValue = ($type == "Debit" && $se_tbill > 0) ? number_format($se_tbill, 3) : '';
                  $creditValue = ($type == "Credit" && $se_tbill > 0) ? number_format($se_tbill, 3) : '';

                  $goldDebitValue = ($type == "Debit" && $se_tpurity > 0) ? number_format($se_tpurity, 3) : '';
                  $goldCreditValue = ($type == "Credit" && $se_tpurity > 0) ? number_format($se_tpurity, 3) : '';

                  // Format balances
                  $balanceValue = ($balance < 0) ? number_format(abs($balance), 3) : number_format($balance, 3);
                  $goldBalanceValue = ($goldBalance < 0) ? number_format(abs($goldBalance), 3) : number_format($goldBalance, 3);
                ?>
                  <tr class="">
                    <td><?= htmlspecialchars($date) ?></td>
                    <td class="left"><?= $row['ref'] ?></td>
                    <td class="left"><?= $row['dis'] ?></td>
                    <td><?= $debitValue ? "($debitValue)" : '' ?></td>
                    <td><?= $creditValue ?></td>
                    <td><?= $balanceValue ?></td>
                    <td><?= $goldDebitValue ? "($goldDebitValue)" : '' ?></td>
                    <td><?= $goldCreditValue ?></td>
                    <td><?= $goldBalanceValue ?></td>
                    <td>
                      <a href="edit_record.php?id=<?= $row['se_id'] ?>&cid=<?= $row['se_cus'] ?>" class="btn btn-warning btn-sm">Edit</a>
                      <a href="delete_record.php?id=<?= $row['se_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
                    </td>
                  </tr>
                <?php } ?>

                <!-- Final total row -->
                <tr class="">
                  <td colspan="3">Total</td>
                  <td><?= ($debitTotal != 0) ? "($" . number_format($debitTotal, 3) . ")" : '' ?></td>
                  <td><?= ($creditTotal != 0) ? number_format($creditTotal, 3) : '' ?></td>
                  <td><?= ($balance != 0) ? ($balance < 0 ? "($" . number_format(abs($balance), 3) . ")" : number_format($balance, 3)) : '' ?></td>
                  <td><?= ($goldDebitTotal != 0) ? "($" . number_format($goldDebitTotal, 3) . ")" : '' ?></td>
                  <td><?= ($goldCreditTotal != 0) ? number_format($goldCreditTotal, 3) : '' ?></td>
                  <td><?= ($goldBalance != 0) ? ($goldBalance < 0 ? number_format(abs($goldBalance), 3) : number_format($goldBalance, 3)) : '' ?></td>
                </tr>
              </tbody>
            </table>

            <!-- Summary of Total Credit/Debit -->
            <div style="margin-top: 20px;">
              <strong>Total Balance Amount (USD): </strong>
              <?= ($balance != 0) ? ($balance < 0 ? number_format(abs($balance), 3) : number_format($balance, 3)) : '0.000' ?>
              <?php
              echo ($creditTotal > $debitTotal) ? "Credit" : "Debit";
              ?>
            </div>
            <div>
              <strong>Total Gold Balance: </strong>
              <?= ($goldBalance != 0) ? ($goldBalance < 0 ? number_format(abs($goldBalance), 3) : number_format($goldBalance, 3)) : '0.000' ?>
              <?php
              echo ($goldCreditTotal > $goldDebitTotal) ? "Credit" : "Debit";
              ?>
            </div>

          </div>

        <?php
        }
        ?>
      </div>
    </div>
  </div>

<?php

    } else {
?>
  <div class="container-fluid pt-4 px-5">
    <div class="rounded align-items-center bg-light justify-content-center mx-0">

      <div class="container py-2 pb-5">
        <h4 class="text-center my-4 bg-primary rounded p-2 text-white">Monthly Journal</h4>
        <a href="../index.php" class="btn btn-secondary">Dashboard</a>
        <form id="search-form" action="search.php" method="get">
          <div class="input-group my-4">
            <i class="fa fa-search bg-primary text-white p-3"></i>
            <input type="text" class="form-control" name="jbm" id="search-input"
              placeholder="Search for Customer Name, NIC, and Location"
              aria-label="Search for Customer Name, NIC, and Location"
              aria-describedby="search-btn">
          </div>
        </form>
        <div id="search-results">
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
              <h5 class="text-center my-3 text-primary rounded p-2 bg-white">Customers</h5>
              <?php
              $sql = "SELECT * FROM customer where role='Customer'";
              $res = mysqli_query($conn, $sql);
              if (mysqli_num_rows($res) > 0) {
                while ($emp = mysqli_fetch_assoc($res)) {

              ?>
                  <tr class="text-center">
                    <td><?= $emp['role'] ?></td>
                    <td><?= $emp['name'] ?></td>
                    <td><?= @$emp['nic'] ?></td>
                    <td><?= $emp['phone'] ?></td>
                    <td><?= $emp['email'] ?></td>
                    <td><?= $emp['loc'] ?></td>
                    <td class="text-center"><a class=" btn btn-sm btn-success" href="mjournal.php?id=<?= $emp['id']; ?>"><i class="far fa-eye"></i></a> <a " btn btn-sm btn-danger" href="update_customer.php?customer_id=<?= $emp['id']; ?>">Edit</a></td>
                  </tr>
              <?php
                }
              } else {
                echo '<th class="text-dark text-center" colspan="11">No Data Exist</th>';
              }
              ?>
              <thead>

                <tr class="text-white bg-primary text-center">
                  <th colspan="7">
                    <h5 class="text-center my-3 text-primary rounded p-2 bg-white">Suppliers</h5>
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
              $sql = "SELECT * FROM customer where role='Supplier'";
              $res = mysqli_query($conn, $sql);
              if (mysqli_num_rows($res) > 0) {
                while ($emp = mysqli_fetch_assoc($res)) {

              ?>
                  <tr class="text-center">
                    <td><?= $emp['role'] ?></td>
                    <td><?= $emp['name'] ?></td>
                    <td><?= @$emp['nic'] ?></td>
                    <td><?= $emp['phone'] ?></td>
                    <td><?= $emp['email'] ?></td>
                    <td><?= $emp['loc'] ?></td>
                    <td class="text-center"><a class=" btn btn-sm btn-success" href="mjournal.php?id=<?= $emp['id']; ?>"><i class="far fa-eye"></i></a> <a " btn btn-sm btn-danger" href="update_customer.php?customer_id=<?= $emp['id']; ?>">Edit</a></td>
                  </tr>
              <?php
                }
              } else {
                echo '<th class="text-dark text-center" colspan="11">No Data Exist</th>';
              }
              ?>
              <thead>

                <tr class="text-white bg-primary text-center">
                  <th colspan="7">
                    <h5 class="text-center my-3 text-primary rounded p-2 bg-white">Money Suppliers</h5>
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
              $sql = "SELECT * FROM customer where role='Money Supplier'";
              $res = mysqli_query($conn, $sql);
              if (mysqli_num_rows($res) > 0) {
                while ($emp = mysqli_fetch_assoc($res)) {

              ?>
                  <tr class="text-center">
                    <td><?= $emp['role'] ?></td>
                    <td><?= $emp['name'] ?></td>
                    <td><?= @$emp['nic'] ?></td>
                    <td><?= $emp['phone'] ?></td>
                    <td><?= $emp['email'] ?></td>
                    <td><?= $emp['loc'] ?></td>
                    <td class="text-center"><a class=" btn btn-sm btn-success" href="mjournal.php?id=<?= $emp['id']; ?>"><i class="far fa-eye"></i></a> <a " btn btn-sm btn-danger" href="update_customer.php?customer_id=<?= $emp['id']; ?>">Edit</a></td>
                  </tr>
              <?php
                }
              } else {
                echo '<th class="text-dark text-center" colspan="11">No Data Exist</th>';
              }
              ?>
            </tbody>
            <thead>
                <thead>

                <tr class="text-white bg-primary text-center">
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
              $sql = "SELECT * FROM customer where role='Silver Supplier'";
              $res = mysqli_query($conn, $sql);
              if (mysqli_num_rows($res) > 0) {
                while ($emp = mysqli_fetch_assoc($res)) {

              ?>
                  <tr class="text-center">
                    <td><?= $emp['role'] ?></td>
                    <td><?= $emp['name'] ?></td>
                    <td><?= @$emp['nic'] ?></td>
                    <td><?= $emp['phone'] ?></td>
                    <td><?= $emp['email'] ?></td>
                    <td><?= $emp['loc'] ?></td>
                    <td class="text-center"><a class=" btn btn-sm btn-success" href="mjournal_silver.php?id=<?= $emp['id']; ?>"><i class="far fa-eye"></i></a> <a " btn btn-sm btn-danger" href="update_customer.php?customer_id=<?= $emp['id']; ?>">Edit</a></td>
                  </tr>
              <?php
                }
              } else {
                echo '<th class="text-dark text-center" colspan="11">No Data Exist</th>';
              }
              ?>
            </tbody>
            <thead>
                <thead>

                <tr class="text-white bg-primary text-center">
                  <th colspan="7">
                    <h5 class="text-center my-3 text-primary rounded p-2 bg-white">Silver Customer</h5>
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
              $sql = "SELECT * FROM customer where role='Silver Customer'";
              $res = mysqli_query($conn, $sql);
              if (mysqli_num_rows($res) > 0) {
                while ($emp = mysqli_fetch_assoc($res)) {

              ?>
                  <tr class="text-center">
                    <td><?= $emp['role'] ?></td>
                    <td><?= $emp['name'] ?></td>
                    <td><?= @$emp['nic'] ?></td>
                    <td><?= $emp['phone'] ?></td>
                    <td><?= $emp['email'] ?></td>
                    <td><?= $emp['loc'] ?></td>
                    <td class="text-center"><a class=" btn btn-sm btn-success" href="mjournal_silver.php?id=<?= $emp['id']; ?>"><i class="far fa-eye"></i></a> <a " btn btn-sm btn-danger" href="update_customer.php?customer_id=<?= $emp['id']; ?>">Edit</a></td>
                  </tr>
              <?php
                }
              } else {
                echo '<th class="text-dark text-center" colspan="11">No Data Exist</th>';
              }
              ?>
            </tbody>
            <thead>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- JavaScript code -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $(document).ready(function() {
      $('#search-input').on('input', function() {
        var query = $(this).val().trim();
        if (query !== '') {
          $.ajax({
            url: 'search.php',
            method: 'GET',
            data: {
              jbm: query
            },
            success: function(response) {
              $('#search-results').html(response);
            },
            error: function() {
              $('#search-results').html('<p class="text-danger">Error fetching results.</p>');
            }
          });
        } else {
          $('#search-results').html(''); // Clear results when input is empty
        }
      });
    });
  </script>

<?php
    }
?>
<!-- Slider End -->

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