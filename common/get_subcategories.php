<?php
include($_SERVER['DOCUMENT_ROOT'] . "/common/db_connection.php");

function getSubcategories($mainCategory, $connection)
{
    $query = "SELECT sub_cat FROM product WHERE main_cat = '$mainCategory'"; //getting subcategories according to main category
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
    echo "<label for='' style='color:red;text-align:center;'>Products of $selectedMainCategory</label>";
    echo "<br>";
    foreach ($subcategories as $subcategory) {
        echo "<div>";

        echo "<label for='count[$subcategory]'>$subcategory</label>";
        echo "<input type='text' name='counts[]' id='count[$subcategory]' required pattern='\\d+' oninput='validateNumber(this)' required >";
        echo "<input type='hidden' name='subcategories[]'>";
        echo "</div>";
    }
}

mysqli_close($connection);
?>
<script>
    function validateNumber(input) {
        input.value = input.value.replace(/\D/g, ''); // Remove any non-numeric characters
    }

    document.addEventListener('DOMContentLoaded', function() {
        var form = document.querySelector('form'); // Adjust the selector if you have multiple forms

        form.addEventListener('submit', function(event) {
            var inputs = form.querySelectorAll('input[name="counts[]"]');
            for (var i = 0; i < inputs.length; i++) {
                if (!/^\d+$/.test(inputs[i].value)) {
                    alert('Please enter a valid integer for all count fields.');
                    event.preventDefault();
                    return false;
                }
            }
            return true;
        });
    });
</script>