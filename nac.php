<!DOCTYPE html>
<html>
<head>
    <title>Navigation Bar</title>
    <style>
        /* CSS for navigation bar */
        ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            overflow: hidden;
            background-color: #333;
        }

        li {
            float: left;
        }

        li a {
            display: block;
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }

        /* Change the link color of the active page */
        li.active a {
            background-color: #4CAF50;
        }
    </style>
</head>
<body>

<?php
// Get the current page filename
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!-- Navigation Bar -->
<ul>
    <li <?php if ($current_page == 'option.php') echo 'class="active"'; ?>><a href="option.php">Options</a></li>
    <li <?php if ($current_page == 'new_order.php') echo 'class="active"'; ?>><a href="new_order.php">New Order</a></li>
    
</ul>

</body>
</html>
