<?php
include('header.php'); // Include header file if it exists
include('connection.php'); // Include database connection file

// Function to calculate maximum number of pieces that can be made
function calculateMaxPieces($productId, $outsourcerId, $conn) {
    // Get the raw material requirements for the given product
    $requirementsQuery = "SELECT pr.raw_material_id, pr.quantity_needed
                          FROM product_requirements pr
                          WHERE pr.product_id = ?";
    $stmt = mysqli_prepare($conn, $requirementsQuery);
    mysqli_stmt_bind_param($stmt, 'i', $productId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    $maxPieces = PHP_INT_MAX; // Start with a very large number

    while ($row = mysqli_fetch_assoc($result)) {
        $rawMaterialId = $row['raw_material_id'];
        $quantityNeeded = $row['quantity_needed'];

        // Get the total available quantity from both rawgrn and rawgrnout tables
        $totalQtyQuery = "SELECT 
                            COALESCE((SELECT SUM(rawQty) FROM rawgrn WHERE RawMaterialsID = ?), 0) +
                            COALESCE((SELECT SUM(rawQty) FROM rawgrnout WHERE RawMaterialsID = ? AND OutsourcerId = ?), 0) AS totalQty";
        $stmtQty = mysqli_prepare($conn, $totalQtyQuery);
        mysqli_stmt_bind_param($stmtQty, 'iii', $rawMaterialId, $rawMaterialId, $outsourcerId);
        mysqli_stmt_execute($stmtQty);
        mysqli_stmt_bind_result($stmtQty, $totalQty);
        mysqli_stmt_fetch($stmtQty);
        mysqli_stmt_close($stmtQty);

        // Calculate how many pieces can be made with the available raw material
        $pieces = intdiv($totalQty, $quantityNeeded);
        
        // Find the limiting factor
        if ($pieces < $maxPieces) {
            $maxPieces = $pieces;
        }
    }

    mysqli_stmt_close($stmt);
    return $maxPieces;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productId = $_POST['productId'];
    $outsourcerId = $_POST['outsourcerId'];
    $maxPieces = calculateMaxPieces($productId, $outsourcerId, $conn);
}

// Fetch products and outsourcers
$productsQuery = "SELECT product_id, CONCAT(main_cat, ' - ', sub_cat) AS productName 
                  FROM product 
                  WHERE productType = 'Outsourcer Product' AND is_deleted = 0";
$productsResult = mysqli_query($conn, $productsQuery);

$outsourcersQuery = "SELECT OutsourcerID, OutsourcerName FROM outsourcer WHERE is_deleted = 0";
$outsourcersResult = mysqli_query($conn, $outsourcersQuery);

mysqli_close($conn);
?>

<div class="container">
    <h1>Calculate Maximum Pieces</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="row g-3">
            <div class="col-md-6">
                <label for="productId" class="form-label">Product</label>
                <select id="productId" name="productId" class="form-select" required>
                    <option value="">Select Product</option>
                    <?php while ($product = mysqli_fetch_assoc($productsResult)) { ?>
                        <option value="<?php echo $product['product_id']; ?>"><?php echo $product['productName']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-md-6">
                <label for="outsourcerId" class="form-label">Outsourcer</label>
                <select id="outsourcerId" name="outsourcerId" class="form-select" required>
                    <option value="">Select Outsourcer</option>
                    <?php while ($outsourcer = mysqli_fetch_assoc($outsourcersResult)) { ?>
                        <option value="<?php echo $outsourcer['OutsourcerID']; ?>"><?php echo $outsourcer['OutsourcerName']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-md-6">
                <button type="submit" class="btn btn-primary">Calculate</button>
            </div>
        </div>
    </form>
    <?php if ($_SERVER["REQUEST_METHOD"] == "POST") { ?>
        <div class="alert alert-success mt-3" role="alert">
            Maximum pieces that can be made: <?php echo $maxPieces; ?>
        </div>
    <?php } ?>
</div>

<?php include('footer.php'); // Include footer file if it exists ?>
