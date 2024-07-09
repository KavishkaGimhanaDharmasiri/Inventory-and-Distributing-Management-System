<?php
include('header.php'); // Include header file if it exists
include('connection.php'); // Include database connection file

$Product_GRN_List = "";

$query = "SELECT pg.productId, p.main_cat, p.sub_cat AS productName, pg.Qty
          FROM product_grn pg
          INNER JOIN product p ON pg.productId = p.product_id";
$Products_GRN = mysqli_query($conn, $query);

if ($Products_GRN) {
    while ($Product_GRN = mysqli_fetch_assoc($Products_GRN)) {
        $Product_GRN_List .= "<tr>";
        // No need to show ProductGrnNo
        // Display Product ID
        $Product_GRN_List .= "<td>{$Product_GRN['productId']}</td>";
        // Display Product Name (main_cat - sub_cat)
        $Product_GRN_List .= "<td>{$Product_GRN['main_cat']} - {$Product_GRN['productName']}</td>";
        $Product_GRN_List .= "<td>{$Product_GRN['Qty']}</td>";
        $Product_GRN_List .= "</tr>";
    }
} else {
    echo "Database Query Failed.";
}
?>

<div class="container">
    <h1>View Product GRN</h1>
</div>

<div class="container min-vh-100 d-flex justify-content-center align-items-center">
    <table class="table table-striped table-dark">
        <tr>
            <th>Product ID</th>
            <th>Product Name</th>
            <th>Quantity</th>
        </tr>

        <?php echo $Product_GRN_List; ?>

    </table>
</div>

<?php include('footer.php'); // Include footer file if it exists ?>
