<?php
// Assume $cartItems is an array containing the items in the cart fetched from the database
foreach ($cartItems as $item) {
    echo "<div>";
    echo "Item: " . $item['name'] . " | Price: $" . $item['price'];
    echo "<form action='removecartitems.php' method='post'>";
    echo "<input type='hidden' name='item_id' value='" . $item['id'] . "'>";
    echo "<input type='submit' value='Remove'>";
    echo "</form>";
    echo "</div>";
}
?>