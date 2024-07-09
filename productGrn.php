<?php
include('header.php'); // Include header file if it exists
include('connection.php'); // Include database connection file

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $productId = $_POST['productId'];
    $qty = $_POST['qty'];

    // Check if product already exists in product_grn
    $queryCheck = "SELECT COUNT(*) AS count FROM product_grn WHERE productId = ?";
    $stmtCheck = mysqli_prepare($conn, $queryCheck);

    if ($stmtCheck) {
        mysqli_stmt_bind_param($stmtCheck, 'i', $productId);
        mysqli_stmt_execute($stmtCheck);
        mysqli_stmt_bind_result($stmtCheck, $count);
        mysqli_stmt_fetch($stmtCheck);
        mysqli_stmt_close($stmtCheck);

        if ($count > 0) {
            // Update existing product quantity
            $queryUpdate = "UPDATE product_grn SET Qty = Qty + ? WHERE productId = ?";
            $stmtUpdate = mysqli_prepare($conn, $queryUpdate);

            if ($stmtUpdate) {
                mysqli_stmt_bind_param($stmtUpdate, 'ii', $qty, $productId);
                if (mysqli_stmt_execute($stmtUpdate)) {
                    echo "Product quantity updated in GRN successfully.";
                } else {
                    echo "Error updating product quantity: " . mysqli_error($conn);
                }
                mysqli_stmt_close($stmtUpdate);
            } else {
                echo "Error preparing update statement: " . mysqli_error($conn);
            }
        } else {
            // Insert new product entry
            $queryInsert = "INSERT INTO product_grn (productId, Qty) VALUES (?, ?)";
            $stmtInsert = mysqli_prepare($conn, $queryInsert);

            if ($stmtInsert) {
                mysqli_stmt_bind_param($stmtInsert, 'ii', $productId, $qty);
                if (mysqli_stmt_execute($stmtInsert)) {
                    echo "Product added to GRN successfully.";
                } else {
                    echo "Error adding product to GRN: " . mysqli_error($conn);
                }
                mysqli_stmt_close($stmtInsert);
            } else {
                echo "Error preparing insert statement: " . mysqli_error($conn);
            }
        }
    } else {
        echo "Error checking product existence: " . mysqli_error($conn);
    }
}

// Fetch product names from product table
$productNames = array();
$queryProducts = "SELECT product_id, CONCAT(main_cat, ' - ', sub_cat) AS productName FROM product WHERE is_deleted = 0";
$resultProducts = mysqli_query($conn, $queryProducts);

if ($resultProducts) {
    while ($row = mysqli_fetch_assoc($resultProducts)) {
        $productNames[$row['product_id']] = $row['productName'];
    }
} else {
    echo "Error fetching products: " . mysqli_error($conn);
}

// Close connection
mysqli_close($conn);
?>

<div class="container">
    <h1>Add Product to GRN</h1>
</div>

<div class="container min-vh-100 d-flex justify-content-center align-items-center">
    <form class="row g-3" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="col-md-6">
            <label for="productId" class="form-label">Product Name</label>
            <select id="productId" name="productId" class="form-select" required>
                <option value="">Select Product</option>
                <?php foreach ($productNames as $productId => $productName) { ?>
                    <option value="<?php echo $productId; ?>"><?php echo $productName; ?></option>
                <?php } ?>
            </select>
        </div>

        <div class="col-md-6">
            <label for="qty" class="form-label">Quantity</label>
            <input type="number" name="qty" class="form-control" id="qty" placeholder="Enter Quantity" required>
        </div>

        <div class="col-md-6">
            <button type="submit" class="btn btn-primary">Save</button>
            <button type="reset" class="btn btn-danger">Clear</button>
        </div>
    </form>
</div>

<?php include('footer.php'); // Include footer file if it exists ?>
