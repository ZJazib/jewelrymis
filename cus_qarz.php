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

<body class="bg-light">
    <?php
    require "config/Dbconn.php";

    $fromDate = $_GET['from_date'] ?? null;
    $toDate = $_GET['to_date'] ?? null;

    function getAllCustomerBalances($conn, $fromDate, $toDate)
    {
        $sql = "
            SELECT 
                c.id AS customer_id, 
                c.name AS customer_name,
                c.role AS customer_role,
                SUM(CASE WHEN s.se_type = 'Debit' THEN s.se_tpurity ELSE 0 END) AS total_debit_gold,
                SUM(CASE WHEN s.se_type = 'Credit' THEN s.se_tpurity ELSE 0 END) AS total_credit_gold,
                (SUM(CASE WHEN s.se_type = 'Credit' THEN s.se_tpurity ELSE 0 END) - 
                 SUM(CASE WHEN s.se_type = 'Debit' THEN s.se_tpurity ELSE 0 END)) AS total_gold_balance,
                SUM(CASE WHEN s.se_type = 'Debit' THEN s.se_tbill ELSE 0 END) AS total_debit_money,
                SUM(CASE WHEN s.se_type = 'Credit' THEN s.se_tbill ELSE 0 END) AS total_credit_money,
                (SUM(CASE WHEN s.se_type = 'Credit' THEN s.se_tbill ELSE 0 END) - 
                 SUM(CASE WHEN s.se_type = 'Debit' THEN s.se_tbill ELSE 0 END)) AS total_money_balance
            FROM 
                customer c
            LEFT JOIN 
                statement s ON c.id = s.se_cus
            WHERE 
                s.se_cus > 2";

        if ($fromDate && $toDate) {
            $sql .= " AND s.se_date BETWEEN ? AND ?"; 
        } elseif ($fromDate) {
            $sql .= " AND s.se_date >= ?"; 
        } elseif ($toDate) {
            $sql .= " AND s.se_date <= ?"; 
        }

        $sql .= " GROUP BY c.id ORDER BY c.role";

        $stmt = $conn->prepare($sql);
        
        if ($fromDate && $toDate) {
            $stmt->bind_param("ss", $fromDate, $toDate);
        } elseif ($fromDate) {
            $stmt->bind_param("s", $fromDate);
        } elseif ($toDate) {
            $stmt->bind_param("s", $toDate);
        }

        $stmt->execute();
        return $stmt->get_result();
    }

    $customers = getAllCustomerBalances($conn, $fromDate, $toDate);
    $totalDebitMoney = 0;
    $totalCreditMoney = 0;
    $totalDebitGold = 0;
    $totalCreditGold = 0;
    $totalMoneyBalance = 0;
    $totalGoldBalance = 0;

    $customerGoldBalances = [];
    $customerMoneyBalances = [];

    ?>

    <div class="container-fluid my-5">
        <h3 class="text-center">Balance Statement</h3>
        <div class="mb-3">
            <label for="liabilityMultiplier" class="form-label">Liability Multiplier</label>
            <input type="number" id="own" name="own" class="form-control" value="1" oninput="calculateTotals()">
        </div>
        <table class="table table-bordered my-4" id="balanceTable">
            <thead>
                <tr class="bg-primary text-white">
              
                     <th>Role</th>
                    <th>Name</th>
                    <th>Debit Money</th>
                    <th>Credit Money</th>
                    <th>Debit Gold</th>
                    <th>Credit Gold</th>
                    <th>Total Liability</th>
                    <th>Liability Type</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($customer = $customers->fetch_assoc()) : ?>
                    <?php
                        $totalMoneyBalance = $customer['total_money_balance'];
                        $totalGoldBalance = $customer['total_gold_balance'];
                        $totalLiability = ((float)($totalGoldBalance) - (float)($totalMoneyBalance));

                        // Add balances to arrays for JavaScript
                        $customerGoldBalances[] = $customer['total_gold_balance'];
                        $customerMoneyBalances[] = $customer['total_money_balance'];
                    ?>
                    <tr>
                        
                       
                         <td><?= $customer['customer_role'] ?></td>
                        <td><?= $customer['customer_name'] ?></td>
                        <td class="<?= $totalMoneyBalance < 0 ? 'debit-class' : '' ?>">
                            <?= $totalMoneyBalance < 0 ? number_format(abs($totalMoneyBalance), 3) . ' $' : '' ?>
                        </td>
                        <td class="<?= $totalMoneyBalance > 0 ? 'credit-class' : '' ?>">
                            <?= $totalMoneyBalance > 0 ? number_format($totalMoneyBalance, 3) . ' $' : '' ?>
                        </td>
                        <td class="<?= $totalGoldBalance < 0 ? 'debit-class' : '' ?>">
                            <?= $totalGoldBalance < 0 ? number_format(abs($totalGoldBalance), 3) : '' ?>
                        </td>
                        <td class="<?= $totalGoldBalance > 0 ? 'credit-class' : '' ?>">
                            <?= $totalGoldBalance > 0 ? number_format($totalGoldBalance, 3) : '' ?>
                        </td>
                        <td class="total-liability">
                            <input type="text" class="form-control" readonly>
                        </td>
                        <td class="liability-type">
                            <?= $totalLiability < 0 ? 'Debit' : 'Credit' ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
            
        </table>
    </div>

    <script>
        const customerGoldBalances = <?= json_encode($customerGoldBalances) ?>;
        const customerMoneyBalances = <?= json_encode($customerMoneyBalances) ?>;

        function calculateTotals() {
            let own = parseFloat(document.getElementById("own").value) || 1;
            document.querySelectorAll(".total-liability input").forEach((input, index) => {
                let goldBalance = customerGoldBalances[index];
                let moneyBalance = customerMoneyBalances[index];
                let total = ((own /  31.1035 * 3.674 / 3.67  * goldBalance) + moneyBalance).toFixed(3);
                input.value = Math.abs(total);

                // Change the liability type based on the value of the liability
                let liabilityType = total < 0 ? 'Debit' : 'Credit';
                document.querySelectorAll(".liability-type")[index].innerText = liabilityType;
            });
        }
    </script>
  <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
    </body>

    </html>