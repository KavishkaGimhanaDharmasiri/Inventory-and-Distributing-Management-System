<?php 
include('header.php');
include('connection.php');

$Product_List = "";

// Query to select all products that are not deleted
$query = "SELECT * FROM Product WHERE is_deleted = FALSE";
$Products = mysqli_query($conn, $query);

if($Products){
    while($Product = mysqli_fetch_assoc($Products)){
        $Product_List .= "<tr>";
        $Product_List .= "<td>{$Product['product_id']}</td>";
        $Product_List .= "<td>{$Product['main_cat']}</td>";
        $Product_List .= "<td>{$Product['sub_cat']}</td>";
        $Product_List .= "<td>{$Product['costPrice']}</td>";
        $Product_List .= "<td>{$Product['creditPrice']}</td>";
        $Product_List .= "<td>{$Product['checkPrice']}</td>";
        $Product_List .= "<td>{$Product['cashPrice']}</td>";
        $Product_List .= "<td>{$Product['productType']}</td>";
        $Product_List .= "<td>{$Product['supplierId']}</td>";
        $Product_List .= "<td>
            <form action='delete_product.php' method='post' style='display:inline-block;'>
                <input type='hidden' name='product_id' value='{$Product['product_id']}'>
                <button type='submit' class='btn btn-danger btn-sm'>Delete</button>
            </form>
        </td>";
        $Product_List .= "</tr>";
    }
} else {
    echo "Database Query Failed: " . mysqli_error($conn);
}
?>

<div class="container">
    <h1>View Products</h1>
</div>

<div class="container min-vh-100 d-flex justify-content-center align-items-center">
    <table class="table table-striped table-dark">
        <tr>
            <th>Product ID</th>
            <th>Main Category</th>
            <th>Product Name</th>
            <th>Cost Price</th>
            <th>Credit Price</th>
            <th>Cheque Price</th>
            <th>Cash Price</th>
            <th>Product Type</th>
            <th>Supplier ID</th>
            <th>Action</th>
        </tr>
        <?php echo $Product_List; ?>
    </table>
</div>

<?php include('footer.php'); ?>
