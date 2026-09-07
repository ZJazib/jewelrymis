<?php
require "config/Dbconn.php";
include "header.php";

// Generate Bill Number
$bill_number = "BILL".date("YmdHis");

// Fetch available jewelry
$jewelrys = mysqli_query($conn,"SELECT * FROM gold_jewelry WHERE status='Available' ORDER BY id ASC");

// Handle sale submission
if(isset($_POST['sell'])){
    $customer_name = $_POST['customer_name'];
    $customer_phone = $_POST['customer_phone'];
    $sale_date = $_POST['sale_date'];
    $jewelry_id = $_POST['jewelry_id'];
    $sold_rate = $_POST['sold_rate'];
    $tax = $_POST['tax'];

    // Get jewelry info
    $jewelry = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM gold_jewelry WHERE id=$jewelry_id"));

    $gross = $jewelry['weight'];
    $purity = $jewelry['karat'];
    $pure_weight = $jewelry['pure_weight'];
    $price_per_gram = $jewelry['price_per_gram'];
    $owner_rate = $jewelry['owner_rate'];

    $total_price = $pure_weight * $sold_rate + $owner_rate;
    $total_price += $total_price * ($tax/100);

    // Insert into sales table
    mysqli_query($conn,"INSERT INTO gold_sales 
        (customer_name, customer_phone, sale_date, jewelry_id, gross_weight, purity, pure_weight, price_per_gram, owner_rate, tax_percentage, sold_rate, total_price, bill_number) 
        VALUES ('$customer_name','$customer_phone','$sale_date','$jewelry_id','$gross','$purity','$pure_weight','$price_per_gram','$owner_rate','$tax','$sold_rate','$total_price','$bill_number')");

    // Update jewelry status
    mysqli_query($conn,"UPDATE gold_jewelry SET status='Sold' WHERE id='$jewelry_id'");

    echo "<script>window.open('print_bill.php?bill_number=$bill_number','_blank');</script>";
}
?>

<h2>Sell Jewelry</h2>
<form method="post" class="row g-3">
    <div class="col-md-6">
        <label>Customer Name</label>
        <input type="text" name="customer_name" class="form-control" required>
    </div>
    <div class="col-md-6">
        <label>Customer Phone</label>
        <input type="text" name="customer_phone" class="form-control" required>
    </div>
    <div class="col-md-6">
        <label>Sale Date</label>
        <input type="date" name="sale_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
    </div>
    <div class="col-md-6">
        <label>Select Jewelry</label>
        <select name="jewelry_id" class="form-select" id="jewelrySelect" onchange="fillJewelryDetails()" required>
            <option value="">--Select--</option>
            <?php while($row = mysqli_fetch_assoc($jewelrys)): ?>
                <option value="<?= $row['id'] ?>" 
                    data-weight="<?= $row['weight'] ?>" 
                    data-karat="<?= $row['karat'] ?>" 
                    data-pure="<?= $row['pure_weight'] ?>" 
                    data-price="<?= $row['price_per_gram'] ?>" 
                    data-owner="<?= $row['owner_rate'] ?>">
                    <?= $row['jewelry_name']." - ".$row['serial_number'] ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>

    <div class="col-md-6">
        <label>Gross Weight (g)</label>
        <input type="text" id="gross" class="form-control" readonly>
    </div>
    <div class="col-md-6">
        <label>Purity</label>
        <input type="text" id="karat" class="form-control" readonly>
    </div>
    <div class="col-md-6">
        <label>Pure Weight (g)</label>
        <input type="text" id="pure" class="form-control" readonly>
    </div>
    <div class="col-md-6">
        <label>Price per Gram</label>
        <input type="number" step="0.01" name="price_per_gram" id="price" class="form-control" required>
    </div>
    <div class="col-md-6">
        <label>Owner's Rate</label>
        <input type="number" step="0.01" name="owner_rate" id="owner" class="form-control" required>
    </div>
    <div class="col-md-6">
        <label>Sold Rate (per gram)</label>
        <input type="number" step="0.01" name="sold_rate" class="form-control" required>
    </div>
    <div class="col-md-6">
        <label>Tax %</label>
        <input type="number" step="0.01" name="tax" class="form-control" value="0">
    </div>

    <div class="col-12">
        <button type="submit" name="sell" class="btn btn-success">Sell Jewelry & Print Bill</button>
    </div>
</form>

<script>
function fillJewelryDetails(){
    var sel = document.getElementById('jewelrySelect');
    var option = sel.options[sel.selectedIndex];

    document.getElementById('gross').value = option.getAttribute('data-weight');
    document.getElementById('karat').value = option.getAttribute('data-karat');
    document.getElementById('pure').value = option.getAttribute('data-pure');
    document.getElementById('price').value = option.getAttribute('data-price');
    document.getElementById('owner').value = option.getAttribute('data-owner');
}
</script>

<?php include "footer.php"; ?>
