<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . "/common/db_connection.php");

function getSubcategories($mainCategory, $connection)
{
    $query = "";
    if ($_SESSION["state"] === 'seller') {
        $route_id = $_SESSION['route_id'];
        $query = "SELECT distinct(sub_cat), sum(f.count) as count FROM feed_item f LEFT JOIN feed e ON f.feed_id=e.feed_id WHERE main_cat ='$mainCategory' AND  f.count > 0 AND e.route_id = $route_id group by sub_cat";
    }
    if ($_SESSION["state"] === 'admin' || $_SESSION["state"] === 'wholeseller') {
        $query = "SELECT distinct(sub_cat), sum(count) as count FROM product WHERE main_cat = '$mainCategory' group by sub_cat"; //getting subcategories according to main category
    }

    $result = mysqli_query($connection, $query);

    if (!$result) {
        die("Database query failed: " . mysqli_error($connection));
    }

    $subcategories = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $subcategories[] = [
            'sub_cat' => $row['sub_cat'],
            'count' => $row['count']
        ];
    }

    return $subcategories;
}

if (isset($_GET['main_category'])) {
    $selectedMainCategory = $_GET['main_category'];
    $subcategories = getSubcategories($selectedMainCategory, $connection);
    echo "<label for='' style='color:indianred;text-align:center;'>Products of $selectedMainCategory</label>";
    echo "<br>";
    foreach ($subcategories as $subcategory) {
        echo "<div>";
        echo "<label for='count[{$subcategory['sub_cat']}]'>◼&nbsp;{$subcategory['sub_cat']}</label>";
        echo "<span id='error_{$subcategory['sub_cat']}' style='color:red;font-size:12px;'></span>";
        echo "<input type='text' name='counts[]' id='count[{$subcategory['sub_cat']}]' required pattern='\\d+' oninput='validateNumber(this, {$subcategory['count']})' required placeholder='count'>";
        echo "<input type='hidden' name='subcategories[]'>";
        echo "<span id='error_{$subcategory['sub_cat']}' style='color:indianred;'></span>";
        echo "</div>";
    }
}


mysqli_close($connection);
