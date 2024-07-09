<?php
include('header.php'); // Include header file if it exists
include('connection.php'); // Include database connection file

// Handle form submission for deducting product quantity
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $productId = $_POST['productId'];
    $qtyToDeduct = $_POST['qtyToDeduct'];

    // Validate quantity
    if ($qtyToDeduct <= 0) {
        echo '<div class="alert alert-danger" role="alert">Quantity to deduct must be greater than zero.</div>';
    } else {
        // Check current quantity in product_grn table
        $query = "SELECT Qty FROM product_grn WHERE productId = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 's', $productId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $currentQty);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        // Calculate new quantity after deduction
        $newQty = $currentQty - $qtyToDeduct;

        if ($newQty < 0) {
            echo '<div class="alert alert-danger" role="alert">Cannot deduct more than available quantity.</div>';
        } else {
            // Update product_grn table
            $updateQuery = "UPDATE product_grn SET Qty = ? WHERE productId = ?";
            $updateStmt = mysqli_prepare($conn, $updateQuery);
            mysqli_stmt_bind_param($updateStmt, 'is', $newQty, $productId);
            
            if (mysqli_stmt_execute($updateStmt)) {
                echo '<div class="alert alert-success" role="alert">Quantity deducted successfully.</div>';
            } else {
                echo '<div class="alert alert-danger" role="alert">Error deducting quantity: ' . mysqli_error($conn) . '</div>';
            }

            mysqli_stmt_close($updateStmt);
        }
    }

    mysqli_close($conn);
}
?>

<div class="container">
    <h1>Deduct Product Quantity</h1>
</div>

<div class="container min-vh-100 d-flex justify-content-center align-items-center">
    <form class="row g-3" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="col-md-6">
            <label for="productId" class="form-label">Product Name</label>
            <select id="productId" name="productId" class="form-select" required>
                <option value="">Select Product</option>
                <?php
                $products = mysqli_query($conn, "SELECT product_id, CONCAT(main_cat, ' - ', sub_cat) AS productName FROM product WHERE is_deleted = 0");
                while ($row = mysqli_fetch_assoc($products)) {
                    echo "<option value='" . $row['product_id'] . "'>" . $row['productName'] . "</option>";
                }
                ?>
            </select>
        </div>

        <div class="col-md-6">
            <label for="qtyToDeduct" class="form-label">Quantity to Deduct</label>
            <input type="number" name="qtyToDeduct" class="form-control" id="qtyToDeduct" placeholder="Enter Quantity" required>
        </div>

        <div class="col-md-6">
            <button type="submit" class="btn btn-primary">Deduct Quantity</button>
            <button type="reset" class="btn btn-danger">Clear</button>
        </div>
    </form>
</div>

<?php include('footer.php'); // Include footer file if it exists ?>
