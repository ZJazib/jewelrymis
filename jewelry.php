<?php
require "../config/Dbconn.php";

// Generate next serial number
$q = mysqli_query($conn, "SELECT id FROM gold_jewelry ORDER BY id DESC LIMIT 1");
$r = mysqli_fetch_assoc($q);
$nextId = $r ? $r['id'] + 1 : 1;

// Handle form submission
if(isset($_POST['submit'])){
    $count = count($_POST['jewelry_name']);
    for($i=0; $i<$count; $i++){
        $serial = "GOLD-" . date("Y") . "-" . str_pad($nextId++, 4, "0", STR_PAD_LEFT);
        $name       = $_POST['jewelry_name'][$i];
        $karat      = $_POST['karat'][$i];
        $gross      = $_POST['gross_weight'][$i];
        $stone      = $_POST['stone_weight'][$i];
        $pure       = $_POST['pure_weight'][$i];
        $price      = $_POST['price_per_gram'][$i];
        $making     = $_POST['making_charge'][$i];

        $image_name = '';
        if(isset($_FILES['image']['name'][$i]) && $_FILES['image']['name'][$i] != ''){
            $image_name = time() . '_' . $_FILES['image']['name'][$i];
            move_uploaded_file($_FILES['image']['tmp_name'][$i], 'uploads/' . $image_name);
        }

        $insert = "INSERT INTO gold_jewelry
                   (serial_number, jewelry_name, karat, gross_weight, stone_weight, pure_weight, making_charge, price_per_gram, image)
                   VALUES
                   ('$serial', '$name', '$karat', '$gross', '$stone', '$pure', '$making', '$price', '$image_name')";

        mysqli_query($conn, $insert);
    }
    echo "<div class='alert alert-success'>All jewelry items added successfully!</div>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Multiple Gold Jewelry</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">

<h2 class="mb-4">Add Multiple Gold Jewelry Items</h2>

<form method="POST" enctype="multipart/form-data" id="jewelryForm">

    <div id="goldItemsContainer">
        <!-- First Row -->
        <div class="gold-item row mb-3 border p-3 rounded">
            <div class="col-md-2">
                <label>Jewelry Name</label>
                <input type="text" name="jewelry_name[]" class="form-control" required>
            </div>

            <div class="col-md-2">
                <label>Gross Weight (g)</label>
                <input type="number" name="gross_weight[]" class="form-control gross" step="any" required>
            </div>

            <div class="col-md-2">
                <label>Karat</label>
                <select name="karat[]" class="form-select karat" required>
                    <option value="0.585">14K</option>
                    <option value="0.750">18K</option>
                    <option value="0.875">21K</option>
                    <option value="0.880">21.12K</option>
                    <option value="0.920">22K</option>
                    <option value="0.995">23.88K</option>
                    <option value="0.999">23.97K</option>
                    <option value="0.9999">23.99K</option>
                </select>
            </div>

            <div class="col-md-2">
                <label>Pure Weight (g)</label>
                <input type="number" name="pure_weight[]" class="form-control pure" step="any" readonly required>
            </div>

            <div class="col-md-2">
                <label>Stone Weight (g)</label>
                <input type="number" name="stone_weight[]" class="form-control" step="any">
            </div>

            <div class="col-md-2">
                <label>Price per Gram</label>
                <input type="number" name="price_per_gram[]" class="form-control" step="any">
            </div>

            <div class="col-md-2 mt-2">
                <label>Making Charge</label>
                <input type="number" name="making_charge[]" class="form-control" step="any">
            </div>

            <div class="col-md-2 mt-2">
                <label>Image</label>
                <input type="file" name="image[]" class="form-control">
            </div>

            <div class="col-md-2 mt-2 d-flex align-items-end">
                <button type="button" class="btn btn-danger remove-item">Remove</button>
            </div>
        </div>
    </div>

    <button type="button" class="btn btn-secondary mb-3" id="addMore">Add More Jewelry</button>
    <button type="submit" name="submit" class="btn btn-primary mb-3">Submit All</button>
</form>

</div>

<script>
// Auto calculate pure weight
function calculatePure(input){
    const row = input.closest('.gold-item');
    const gross = parseFloat(row.querySelector('.gross').value) || 0;
    const karat = parseFloat(row.querySelector('.karat').value) || 0;
    row.querySelector('.pure').value = (gross * karat).toFixed(3);
}

// Attach events
document.querySelectorAll('.gross, .karat').forEach(input=>{
    input.addEventListener('input', ()=>calculatePure(input));
    input.addEventListener('change', ()=>calculatePure(input));
});

// Add more dynamic rows
document.getElementById('addMore').addEventListener('click', ()=>{
    const container = document.getElementById('goldItemsContainer');
    const firstRow = container.querySelector('.gold-item');
    const newRow = firstRow.cloneNode(true);
    newRow.querySelectorAll('input').forEach(i=>i.value=''); // clear values
    container.appendChild(newRow);

    // Attach remove button
    newRow.querySelector('.remove-item').addEventListener('click', ()=>{
        newRow.remove();
    });

    // Attach events for auto calculation
    newRow.querySelectorAll('.gross, .karat').forEach(input=>{
        input.addEventListener('input', ()=>calculatePure(input));
        input.addEventListener('change', ()=>calculatePure(input));
    });
});

// Remove button for first row
document.querySelectorAll('.remove-item').forEach(btn=>{
    btn.addEventListener('click', ()=>{
        btn.closest('.gold-item').remove();
    });
});
</script>

</body>
</html>
