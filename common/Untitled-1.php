<?php
if (isset($_POST["receiptType"]) && $_POST["receiptType"] == "salessummery") {

    $route_id = $_SESSION['route_id'];
    $sql = "";
    if ($_SESSION["state"] === "seller") {
        if ($_POST["duration"] == "yearly" && isset($_POST["year"]) && isset($_POST["year1"])) {
            $start = intval($_POST["year"]);
            $end = intval($_POST["year1"]);
            $sql = "SELECT p.payment_date, p.total, p.payment_method, p.balance, o.main_cat, o.sub_cat, o.order_count
                    FROM payment p
                    INNER JOIN primary_orders po ON p.ord_id = po.ord_id
                    INNER JOIN orders o ON po.ord_id = o.ord_id
                    WHERE (YEAR(p.payment_date) IN (?, ?)) AND p.route_id = ?";

            $stmt = $connection->prepare($sql);
            $stmt->bind_param("iii", $start, $end, $route_id);
            $stmt->execute();
        }
        if ($_POST["duration"] == "monthly" && isset($_POST["year"]) && isset($_POST["year1"]) && isset($_POST["month"]) && isset($_POST["month1"])) {
            $start = intval($_POST["year"]);
            $end = intval($_POST["year1"]);
            $start_mon = intval($_POST["month"]);
            $end_mon = intval($_POST["month1"]);

            $sql = "SELECT p.payment_date, p.total, p.payment_method, p.balance, o.main_cat, o.sub_cat, o.order_count
                    FROM payment p
                    INNER JOIN primary_orders po ON p.ord_id = po.ord_id
                    INNER JOIN orders o ON po.ord_id = o.ord_id
                    WHERE (YEAR(p.payment_date) IN (?, ?) AND MONTH(p.payment_date) IN (?, ?)) AND p.route_id = ?";

            $stmt = $connection->prepare($sql);
            $stmt->bind_param("iiiiii", $start, $end, $start_mon, $end_mon, $route_id);
            $stmt->execute();
        }

        if ($_POST["duration"] == "daily" && isset($_POST["year"]) && isset($_POST["year1"]) && isset($_POST["month"]) && isset($_POST["month1"]) && isset($_POST["day"]) && isset($_POST["day1"])) {
            $start = intval($_POST["year"]);
            $end = intval($_POST["year1"]);
            $start_mon = intval($_POST["month"]);
            $end_mon = intval($_POST["month1"]);
            $start_date = intval($_POST["day"]);
            $end_date = intval($_POST["day1"]);

            // Format the dates
            $begin_date = sprintf("%04d-%02d-%02d", $start, $start_mon, $start_date);
            $end_date = sprintf("%04d-%02d-%02d", $end, $end_mon, $end_date);

            // Prepare the SQL query using prepared statements to prevent SQL injection

            $sql = "SELECT p.payment_date, p.total, p.payment_method, p.balance, o.main_cat, o.sub_cat, o.order_count
                    FROM payment p
                    INNER JOIN primary_orders po ON p.ord_id = po.ord_id
                    INNER JOIN orders o ON po.ord_id = o.ord_id
                    WHERE p.payment_date BETWEEN ? AND ? AND p.route_id = ?";

            $stmt = $connection->prepare($sql);
            $stmt->bind_param("ssi", $begin_date, $end_date, $route_id);
            $stmt->execute();
        }
    }
    if ($_SESSION["state"] === "admin") {
        if ($_POST["duration"] == "yearly" && isset($_POST["year"]) && isset($_POST["year1"])) {
            $start = intval($_POST["year"]);
            $end = intval($_POST["year1"]);
            $sql = "SELECT p.payment_date, p.total, p.payment_method, p.balance, o.main_cat, o.sub_cat, o.order_count
                    FROM payment p
                    INNER JOIN primary_orders po ON p.ord_id = po.ord_id
                    INNER JOIN orders o ON po.ord_id = o.ord_id
                    WHERE (YEAR(p.payment_date) IN (?, ?))";

            $stmt = $connection->prepare($sql);
            $stmt->bind_param("ii", $start, $end);
            $stmt->execute();
        }
        if ($_POST["duration"] == "monthly" && isset($_POST["year"]) && isset($_POST["year1"]) && isset($_POST["month"]) && isset($_POST["month1"])) {
            $start = intval($_POST["year"]);
            $end = intval($_POST["year1"]);
            $start_mon = intval($_POST["month"]);
            $end_mon = intval($_POST["month1"]);

            $sql = "SELECT p.payment_date, p.total, p.payment_method, p.balance, o.main_cat, o.sub_cat, o.order_count
                    FROM payment p
                    INNER JOIN primary_orders po ON p.ord_id = po.ord_id
                    INNER JOIN orders o ON po.ord_id = o.ord_id
                    WHERE (YEAR(p.payment_date) IN (?, ?) AND MONTH(p.payment_date) IN (?, ?))";

            $stmt = $connection->prepare($sql);
            $stmt->bind_param("iiiii", $start, $end, $start_mon, $end_mon);
            $stmt->execute();
        }

        if ($_POST["duration"] == "daily" && isset($_POST["year"]) && isset($_POST["year1"]) && isset($_POST["month"]) && isset($_POST["month1"]) && isset($_POST["day"]) && isset($_POST["day1"])) {
            $start = intval($_POST["year"]);
            $end = intval($_POST["year1"]);
            $start_mon = intval($_POST["month"]);
            $end_mon = intval($_POST["month1"]);
            $start_date = intval($_POST["day"]);
            $end_date = intval($_POST["day1"]);

            // Format the dates
            $begin_date = sprintf("%04d-%02d-%02d", $start, $start_mon, $start_date);
            $end_date = sprintf("%04d-%02d-%02d", $end, $end_mon, $end_date);

            // Prepare the SQL query using prepared statements to prevent SQL injection

            $sql = "SELECT p.payment_date, p.total, p.payment_method, p.balance, o.main_cat, o.sub_cat, o.order_count
                    FROM payment p
                    INNER JOIN primary_orders po ON p.ord_id = po.ord_id
                    INNER JOIN orders o ON po.ord_id = o.ord_id
                    WHERE p.payment_date BETWEEN ? AND ? ";

            $stmt = $connection->prepare($sql);
            $stmt->bind_param("ss", $begin_date, $end_date);
            $stmt->execute();
        }
    }
    $result = $stmt->get_result();

    // Process the data
    $sales_data = array();
    while ($row = $result->fetch_assoc()) {
        $sales_data[] = $row;
    }

    // Calculate total revenue, quantity sold, and other metrics
    $total_revenue = 0;
    $total_balance = 0;
    $total_quantity_sold = 0;
    $categories_sales = array();
    $payment_methods = array();
    foreach ($sales_data as $sale) {
        // Total revenue and quantity sold
        $total_revenue += $sale['total'];
        $total_balance += $sale['balance'];
        $total_quantity_sold += $sale['order_count'];
    }

    foreach ($sales_data as $sale) {
        // Sales by main category
        $main_category = $sale['main_cat'];
        if (!isset($main_category_counts[$main_category])) {
            $main_category_counts[$main_category] = 0;
        }
        // Increase count by the order count
        $main_category_counts[$main_category] += $sale['order_count'];
    }


    $total_transactions = count($sales_data);
    $payment_method_percentages = array();
    foreach ($sales_data as $sale) {
        // Distribution of payment methods
        $payment_method = strtolower($sale['payment_method']);
        if (!isset($payment_method_percentages[$payment_method])) {
            $payment_method_percentages[$payment_method] = 0;
        }
        $payment_method_percentages[$payment_method]++;
    }


    // Calculate percentages
    foreach ($payment_method_percentages as &$percentage) {
        $percentage = ($percentage / $total_transactions) * 100;
    }

    echo '<h3 style="text-decoration:underline;color:indianred;text-align:center;">Comprehensive Sales Report</h3>';
    echo '<label>Total Revenue : LKR.' . $total_revenue . '</label><br>';
    echo '<label>Total Outstanding : LKR.' . $total_balance . '</label><br>';
    echo '<label>Total Quantity Sold :' . $total_quantity_sold . ' items</label>';
    echo '<hr>';
    //<!-- Chart for Sales by Category -->
    echo ' <div style="height:300px; align-items: center; justify-content: center; ">
<h4>Sales By Category</h4>
<canvas id="salesByCategoryChart" width="400" height="250"></canvas></div>';
    echo '<hr>';

    $subcategories_max_count = array();

    foreach ($sales_data as $sale) {
        // Sales by main category and subcategory
        $main_category = $sale['main_cat'];
        $subcategory = $sale['sub_cat'];
        $order_count = $sale['order_count'];

        // Initialize subcategory array for main category if not exists
        if (!isset($subcategories_max_count[$main_category])) {
            $subcategories_max_count[$main_category] = array();
        }

        // Update or initialize counts for subcategory
        if (!isset($subcategories_max_count[$main_category][$subcategory])) {
            $subcategories_max_count[$main_category][$subcategory] = 0;
        }
        $subcategories_max_count[$main_category][$subcategory] += $order_count;
    }

    // Sort subcategories by count in descending order
    foreach ($subcategories_max_count as &$subcategories) {
        arsort($subcategories);
        // Keep only top three subcategories
        $subcategories = array_slice($subcategories, 0, 3);
    }

    echo '<h4>Items Sold of Top Three Subcategories by Each Main Category</h4>';
    echo '<ul>';
    foreach ($subcategories_max_count as $main_category => $subcategories) :
        echo '<li><b>' . $main_category;
        echo '<ul>';
        foreach ($subcategories as $subcategory => $count) :
            echo '<li>' . $subcategory . ' - ' . $count . '</li>';
        endforeach;
        echo '</ul>';
        echo '</li>';
    endforeach;
    echo '</ul>';
    echo '<hr>';
    echo '<h4>Percentage of Payment Method Used</h4>';
    echo '<div style="width:210px; margin-left:20%; ">

<canvas id="paymentMethodsChart" width="100px" height="150px"></canvas>

</div>';
    echo '<br>';
}
