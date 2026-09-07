<?php
require "config/Dbconn.php";
include "header.php";

// Generate unique serial number
function generateSerial($conn){
    $prefix = "GJ";
    $last = mysqli_query($conn,"SELECT serial_number FROM gold_jewelry ORDER BY id DESC LIMIT 1");
    $lastRow = mysqli_fetch_assoc($last);
    if($lastRow){
        $num = intval(substr($lastRow['serial_number'], 2)) + 1;
    } else {
        $num = 1;
    }
    return $prefix.str_pad($num,6,"0",STR_PAD_LEFT);
}

// Handle form submission
if(isset($_POST['add'])){
    $name = $_POST['name'];
    $karat = $_POST['karat'];
    $weight = $_POST['weight'];
    $purity = $_POST['purity'];
    $price_per_gram = $_POST['price_per_gram'];
    $owner_rate = $_POST['owner_rate'];

    $pure_weight = $weight * floatval($purity);
    $serial = generateSerial($conn);

    $img_name = "";
    if(isset($_FILES['image'])){
        $img_name = time()."_".$_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "uploads/".$img_name);
    }

    mysqli_query($conn,"INSERT INTO gold_jewelry 
        (serial_number,jewelry_name,karat,weight,pure_weight,price_per_gram,owner_rate,image) 
        VALUES ('$serial','$name','$karat','$weight','$pure_weight','$price_per_gram','$owner_rate','$img_name')");

    echo "<div class='alert alert-success'>Jewelry added successfully! Serial: <strong>$serial</strong></div>";
}
?>

<h2 class="mb-4">Add New Jewelry</h2>
<div class="card p-4">
<form method="post" enctype="multipart/form-data" class="row g-3" id="jewelryForm">
    <div class="col-md-6">
        <label>Name</label>
        <input type="text" name="name" class="form-control" required>
    </div>
    <div class="col-md-6">
        <label>Serial (Auto-generated)</label>
        <input type="text" name="serial_display" class="form-control" value="<?= generateSerial($conn) ?>" readonly>
    </div>
    <div class="col-md-6">
        <label>Karat</label>
        <input type="number" step="0.001" name="karat" class="form-control" required>
    </div>
    <div class="col-md-6">
        <label>Gross Weight (g)</label>
        <input type="number" step="0.01" name="weight" class="form-control" id="weight" required>
    </div>
    <div class="col-md-6">
        <label>Purity</label>
        <select id="purity" name="purity" class="form-select" onchange="calculatePureWeight()" required>
            <option value="">--Select--</option>
            <option value="0.585">14 Karat (0.585)</option>
            <option value="0.750">18 Karat (0.750)</option>
            <option value="0.875">21 Karat (0.875)</option>
            <option value="0.880">21.12 Karat (0.880)</option>
            <option value="0.920">22 Karat (0.920)</option>
            <option value="0.995">23.88 Karat (0.995)</option>
            <option value="0.999">23.97 Karat (0.999)</option>
            <option value="0.9999">23.99 Karat (0.9999)</option>
        </select>
    </div>
    <div class="col-md-6">
        <label>Pure Weight (Auto)</label>
        <input type="text" class="form-control" id="pure_weight" readonly>
    </div>
    <div class="col-md-6">
        <label>Price per Gram (Editable)</label>
        <input type="number" step="0.01" name="price_per_gram" class="form-control" value="0" required>
    </div>
    <div class="col-md-6">
        <label>Owner's Rate / Making Charge</label>
        <input type="number" step="0.01" name="owner_rate" class="form-control" value="0" required>
    </div>
    <div class="col-md-6">
        <label>Image</label>
        <input type="file" name="image" class="form-control">
    </div>
    <div class="col-12">
        <button type="submit" name="add" class="btn btn-primary"><i class="fa fa-plus"></i> Add Jewelry</button>
    </div>
</form>
</div>

<script>
function calculatePureWeight(){
    var weight = parseFloat(document.getElementById('weight').value) || 0;
    var purity = parseFloat(document.getElementById('purity').value) || 0;
    var pure = weight * purity;
    document.getElementById('pure_weight').value = pure.toFixed(3);
}

// Recalculate if weight changes
document.getElementById('weight').addEventListener('input', calculatePureWeight);
</script>

<?php include "footer.php"; ?>
