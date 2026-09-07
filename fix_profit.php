<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>ZMIS</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Admin Panel" name="keywords">
    <meta content="Admin Panel" name="description">

    <!-- Favicon -->
    <link href="../favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body class="bg-light">
    <?php
    require "config/Dbconn.php";

    // Function to fetch balances based on role and date range
    function getAllBalances($conn, $role, $startDate, $endDate)
    {
        $sql = "
            SELECT 
                c.id AS customer_id, 
                c.name AS customer_name,
                SUM(CASE WHEN s.gold_state = 'Debit' THEN s.fi_gold ELSE 0 END) AS total_debit_gold,
                SUM(CASE WHEN s.gold_state = 'Credit' THEN s.fi_gold ELSE 0 END) AS total_credit_gold,
                (SUM(CASE WHEN s.gold_state = 'Credit' THEN s.fi_gold ELSE 0 END) - 
                 SUM(CASE WHEN s.gold_state = 'Debit' THEN s.fi_gold ELSE 0 END)) AS total_gold_balance,
                SUM(CASE WHEN s.money_state = 'Debit' THEN s.total ELSE 0 END) AS total_debit_money,
                SUM(CASE WHEN s.money_state = 'Credit' THEN s.total ELSE 0 END) AS total_credit_money,
                (SUM(CASE WHEN s.money_state = 'Credit' THEN s.total ELSE 0 END) - 
                 SUM(CASE WHEN s.money_state = 'Debit' THEN s.total ELSE 0 END)) AS total_money_balance
            FROM 
                customer c
            LEFT JOIN 
                fixing s ON c.id = s.fi_cus
            WHERE 
                c.role = ? AND s.fi_cus = c.id AND s.fi_date BETWEEN ? AND ?
            GROUP BY 
                c.id";
      
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $role, $startDate, $endDate);  // Bind date range
        $stmt->execute();
        return $stmt->get_result();
    }

    // Get selected date range from user input
    $startDate = isset($_POST['start_date']) ? $_POST['start_date'] : date('Y-m-01');  // Default to the start of the current month
    $endDate = isset($_POST['end_date']) ? $_POST['end_date'] : date('Y-m-t');  // Default to the end of the current month

    // Retrieve customer and supplier balances within the selected date range
    $customers = getAllBalances($conn, 'Customer', $startDate, $endDate);
    $suppliers = getAllBalances($conn, 'Supplier', $startDate, $endDate);

    // Initialize totals
    $totalCustomerDebitMoney = $totalCustomerCreditMoney = $totalCustomerDebitGold = $totalCustomerCreditGold = 0;
    $totalSupplierDebitMoney = $totalSupplierCreditMoney = $totalSupplierDebitGold = $totalSupplierCreditGold = 0;

    // Function to accumulate totals
    function calculateTotals($result, &$totalDebitMoney, &$totalCreditMoney, &$totalDebitGold, &$totalCreditGold) {
        while ($row = $result->fetch_assoc()) {
            $totalDebitMoney += $row['total_debit_money'];
            $totalCreditMoney += $row['total_credit_money'];
            $totalDebitGold += $row['total_debit_gold'];
            $totalCreditGold += $row['total_credit_gold'];
        }
    }

    // Calculate totals for customers and suppliers
    calculateTotals($customers, $totalCustomerDebitMoney, $totalCustomerCreditMoney, $totalCustomerDebitGold, $totalCustomerCreditGold);
    calculateTotals($suppliers, $totalSupplierDebitMoney, $totalSupplierCreditMoney, $totalSupplierDebitGold, $totalSupplierCreditGold);

    // Calculate net balances and total profit
    $netCustomerMoneyBalance = abs($totalCustomerCreditMoney - $totalCustomerDebitMoney);
    $netCustomerGoldBalance = abs($totalCustomerCreditGold - $totalCustomerDebitGold);
    $netSupplierMoneyBalance = abs($totalSupplierCreditMoney - $totalSupplierDebitMoney);
    $netSupplierGoldBalance = abs($totalSupplierCreditGold - $totalSupplierDebitGold);
    $totalProfit = abs($netSupplierMoneyBalance) - abs($netCustomerMoneyBalance);
    
    // Helper function to replace 0 with an empty string
    function formatValue($value) {
        return $value == 0 ? '' : number_format($value, 3);
    }
    ?>

    <div class="container-fluid my-5">
        <h3 class="text-center">Fixing Balance Statement</h3>

        <!-- Date Range Filter -->
        <form method="POST" class="my-4">
            <div class="row">
                <div class="col-md-4">
                    <label for="start_date">Start Date</label>
                    <input type="date" name="start_date" id="start_date" class="form-control" value="<?= $startDate ?>">
                </div>
                <div class="col-md-4">
                    <label for="end_date">End Date</label>
                    <input type="date" name="end_date" id="end_date" class="form-control" value="<?= $endDate ?>">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary mt-4">Filter</button>
                </div>
            </div>
        </form>

        <!-- Customer Balances Section -->
        <h5 class="mt-4">Customer Balances</h5>
        <table class="table table-bordered my-4">
            <thead>
                <tr class="bg-info text-white">
                    <th>Customer Name</th>
                    <th>Total Debit Money</th>
                    <th>Total Credit Money</th>
                    <th>Net Money Balance</th>
                    <th>Total Debit Gold</th>
                    <th>Total Credit Gold</th>
                    <th>Net Gold Balance</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Reset the query result to fetch each customer's individual details
                $customers = getAllBalances($conn, 'Customer', $startDate, $endDate);
                while ($row = $customers->fetch_assoc()) {
                    $netMoneyBalance = $row['total_credit_money'] - $row['total_debit_money'];
                    $netGoldBalance = $row['total_credit_gold'] - $row['total_debit_gold'];
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($row['customer_name']) ?></td>
                        <td><?= formatValue($row['total_debit_money']) ?></td>
                        <td><?= formatValue($row['total_credit_money']) ?></td>
                        <td class="<?= $netMoneyBalance < 0 ? 'debit-class' : 'credit-class' ?>">
                            <?= $netMoneyBalance < 0 ? "Debit: " . formatValue(abs($netMoneyBalance)) . " USD" : "Credit: " . formatValue($netMoneyBalance) . " USD" ?>
                        </td>
                        <td><?= formatValue($row['total_debit_gold']) ?> </td>
                        <td><?= formatValue($row['total_credit_gold']) ?></td>
                        <td class="<?= $netGoldBalance < 0 ? 'debit-class' : 'credit-class' ?>">
                            <?= $netGoldBalance < 0 ? "Debit: " . formatValue(abs($netGoldBalance)) . " gm" : "Credit: " . formatValue($netGoldBalance) . " gm" ?>
                        </td>
                    </tr>
                <?php } ?>
                <tr class="bg-warning">
                    <th>Total Customer Balances</th>
                    <td><?= formatValue($totalCustomerDebitMoney) ?></td>
                    <td><?= formatValue($totalCustomerCreditMoney) ?></td>
                    <td><?= formatValue($netCustomerMoneyBalance) ?></td>
                    <td><?= formatValue($totalCustomerDebitGold) ?></td>
                    <td><?= formatValue($totalCustomerCreditGold) ?></td>
                    <td><?= formatValue($netCustomerGoldBalance) ?></td>
                </tr>
            </tbody>
        </table>

        <!-- Supplier Balances Section -->
        <h5 class="mt-4">Supplier Balances</h5>
        <table class="table table-bordered">
            <thead>
                <tr class="bg-info text-white">
                    <th>Supplier Name</th>
                    <th>Total Debit Money</th>
                    <th>Total Credit Money</th>
                    <th>Net Money Balance</th>
                    <th>Total Debit Gold</th>
                    <th>Total Credit Gold</th>
                    <th>Net Gold Balance</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Reset the query result to fetch each supplier's individual details
                $suppliers = getAllBalances($conn, 'Supplier', $startDate, $endDate);
                while ($row = $suppliers->fetch_assoc()) {
                    $netMoneyBalance = $row['total_credit_money'] - $row['total_debit_money'];
                    $netGoldBalance = $row['total_credit_gold'] - $row['total_debit_gold'];
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($row['customer_name']) ?></td>
                        <td><?= formatValue($row['total_debit_money']) ?></td>
                        <td><?= formatValue($row['total_credit_money']) ?></td>
                        <td class="<?= $netMoneyBalance < 0 ? 'debit-class' : 'credit-class' ?>">
                            <?= $netMoneyBalance < 0 ? "Debit: " . formatValue(abs($netMoneyBalance)) . " USD" : "Credit: " . formatValue($netMoneyBalance) . " USD" ?>
                        </td>
                        <td><?= formatValue($row['total_debit_gold']) ?> </td>
                        <td><?= formatValue($row['total_credit_gold']) ?></td>
                        <td class="<?= $netGoldBalance < 0 ? 'debit-class' : 'credit-class' ?>">
                            <?= $netGoldBalance < 0 ? "Debit: " . formatValue(abs($netGoldBalance)) . " gm" : "Credit: " . formatValue($netGoldBalance) . " gm" ?>
                        </td>
                    </tr>
                <?php } ?>
                <tr class="bg-warning">
                    <th>Total Supplier Balances</th>
                    <td><?= formatValue($totalSupplierDebitMoney) ?></td>
                    <td><?= formatValue($totalSupplierCreditMoney) ?></td>
                    <td><?= formatValue($netSupplierMoneyBalance) ?></td>
                    <td><?= formatValue($totalSupplierDebitGold) ?></td>
                    <td><?= formatValue($totalSupplierCreditGold) ?></td>
                    <td><?= formatValue($netSupplierGoldBalance) ?></td>
                </tr>
            </tbody>
        </table>

        <!-- Profit Statement -->
        <h5 class="mt-4">Profit Statement</h5>
        <table class="table table-bordered">
            <thead>
                <tr class="bg-primary text-white">
                    <th>Total Profit</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?= formatValue($totalProfit) ?></td>
                </tr>
            </tbody>
        </table>

    </div>

</body>

</html>
