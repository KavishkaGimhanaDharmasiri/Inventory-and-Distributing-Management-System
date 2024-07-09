<?php
include('header.php'); // Include header file if it exists
include('connection.php'); // Include database connection file

// Function to calculate maximum number of pieces that can be made
function calculateMaxPieces($productId, $conn) {
    // Get the raw material requirements for the given product
    $requirementsQuery = "SELECT pr.raw_material_id, pr.quantity_needed, rg.rawQty
                          FROM product_requirements pr
                          JOIN rawgrn rg ON pr.raw_material_id = rg.RawMaterialsID
                          JOIN product p ON pr.product_id = p.product_id
                          WHERE pr.product_id = ? AND p.productType = 'Inhouse Product'";
    $stmt = mysqli_prepare($conn, $requirementsQuery);
    mysqli_stmt_bind_param($stmt, 'i', $productId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    $maxPieces = PHP_INT_MAX; // Start with a very large number

    while ($row = mysqli_fetch_assoc($result)) {
        $rawMaterialId = $row['raw_material_id'];
        $quantityNeeded = $row['quantity_needed'];
        $availableQty = $row['rawQty'];

        // Calculate how many pieces can be made with the available raw material
        $pieces = intdiv($availableQty, $quantityNeeded);
        
        // Find the limiting factor
        if ($pieces < $maxPieces) {
            $maxPieces = $pieces;
        }
    }

    mysqli_stmt_close($stmt);
    return $maxPieces;
}

// Fetch products that are 'Inhouse Product'
$productsQuery = "SELECT product_id, CONCAT(main_cat, ' - ', sub_cat) AS productName FROM product WHERE productType = 'Inhouse Product' AND is_deleted = 0";
$productsResult = mysqli_query($conn, $productsQuery);

$productId = 1; // For example, calculate for product 1 if not provided by a form
$maxPieces = null;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productId = $_POST['productId'];
    $maxPieces = calculateMaxPieces($productId, $conn);
}

mysqli_close($conn);
?>

<div class="container">
    <h1>Calculate Maximum Pieces</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="mb-3">
            <label for="productId" class="form-label">Product</label>
            <select id="productId" name="productId" class="form-select" required>
                <option value="">Select Product</option>
                <?php while ($product = mysqli_fetch_assoc($productsResult)) { ?>
                    <option value="<?php echo $product['product_id']; ?>"><?php echo $product['productName']; ?></option>
                <?php } ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Calculate</button>
    </form>
    <?php if ($maxPieces !== null) { ?>
        <div class="alert alert-success mt-3" role="alert">
            Maximum pieces that can be made: <?php echo $maxPieces; ?>
        </div>
    <?php } ?>
</div>

<?php include('footer.php'); // Include footer file if it exists ?>
