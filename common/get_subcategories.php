<?php
include($_SERVER['DOCUMENT_ROOT'] . "/common/db_connection.php");

function getSubcategories($mainCategory, $connection)
{
    $query = "SELECT sub_cat FROM product WHERE main_cat = '$mainCategory'";
    $result = mysqli_query($connection, $query);

    if (!$result) {
        die("Database query failed: " . mysqli_error($connection));
    }

    $subcategories = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $subcategories[] = $row['sub_cat'];
    }

    return $subcategories;
}


if (isset($_GET['main_category'])) {
    $selectedMainCategory = $_GET['main_category'];
    $subcategories = getSubcategories($selectedMainCategory, $connection);

    foreach ($subcategories as $subcategory) {
        echo "<div>";
        echo "<label for='count[$subcategory]'>$subcategory:</label>";
        echo "<input type='number' name='counts[]' id='count[$subcategory]' required>";
        echo "<input type='hidden' name='subcategories[]' value='$subcategory'>";
        echo "</div>";
    }
}

mysqli_close($connection);
