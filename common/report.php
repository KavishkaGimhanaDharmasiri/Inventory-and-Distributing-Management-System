<?php
session_start();
// Include your database connection file
include($_SERVER['DOCUMENT_ROOT'] . "/common/db_connection.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/common/den_fun.php");

if (!isset($_SESSION['option_visit']) || !isset($_SESSION['index_visit']) || $_SESSION["state"] === 'wholeseller') {
    acess_denie();
    exit();
} else {
    $_SESSION['report_visit'] = true;
}

$customerQuery = "SELECT sto_name FROM customers";
$customerResult = mysqli_query($connection, $customerQuery);
$route_id = $_SESSION['route_id'];

$dateQuery = "SELECT DISTINCT DATE_FORMAT(payment_date, '%Y') AS formatted_date FROM payment";
$stmt = $connection->prepare($dateQuery);
$stmt->execute();
$dateResult = $stmt->get_result();

$years = array();

// Iterate over the results and store them in the array
foreach ($dateResult as $row) {
    $years[] = $row['formatted_date'];
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1">
    <title>Report</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="icon" href="/images/tab_icon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/style/style.css">
    <link rel="stylesheet" href="/style/mobile.css">
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
    <style>
        .mobile-container {
            color: black;
        }

        .order-container h3 {
            color: #333;
        }

        .order-container p {
            margin: 12px;
        }

        .toggle-link {
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 100%;
            text-align: center;
            transform: translateX(-50%);
            backdrop-filter: blur(10px);
            height: 25px;
            padding-bottom: 0px;

        }

        .order-container {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 20px;
            width: 250px;
            background-color: #f9f9f9;
            border-radius: 15px;
            font-size: 10pt;
            overflow: hidden;
            /* Add overflow property */
            height: 23%;
            /* Set max-height to reveal 60% initially */
            position: relative;
            /* Set position to relative */
            backdrop-filter: blur(15%);
            margin-left: 15%;
        }

        .order-container a {
            color: #0066cc;
            text-decoration: none;
        }

        .order-container a:hover {
            text-decoration: underline;
        }

        th,
        tr,
        th {
            background-color: transparent;
            color: black;
            text-align: center;
        }

        tr {
            padding-top: 0px;
        }

        tr:nth-child(even) {
            background-color: white;
        }

        th {
            padding: 5px;
        }

        table {

            border-spacing: 0;
            width: auto;
        }

        tr :hover {
            background-color: none;
        }

        td {
            padding: 5px;
        }

        #left {
            text-align: left;
        }


        ul,
        li {
            color: gray;
            font-size: 13px;

        }

        h5 {
            text-decoration: underline;
        }

        tr:hover,
        td:hover,
        th:hover {
            background-color: white;
        }
    </style>
</head>

<body>

    <!-- Simulate a smartphone / tablet -->
    <div class="mobile-container">

        <!-- Top Navigation Menu -->
        <div class="topnav">

            <a href="javascript:void(0)" class="back-link" style="font-size: 20px;"><i class="fa fa-angle-left" onclick="back()" style="float:left;font-size:25px;"></i><b>&nbsp;&nbsp;&nbsp;<span style="font-size: 17px;">report</span></a>
        </div>
        <div class="container">
            <h3 style="text-align: center;">Report Generation</h3>

            <?php
            // Display error message if set
            if (isset($error_message)) {
                echo '<div class="alert alert-danger">' . $error_message . '</div>';
            }
            ?>

            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

                <div class="form-group">
                    <h4 style="text-align: left; color:#4caf50;">Filter By</h4>
                    <div class="form-group">
                        <label for="report_type">Report Type</label>
                        <select name="receiptType" id="receiptType">
                            <option value="">Select Report Type</option>
                            <option value="salessummery">Sales Summary</option>
                            <option value="customerdata">Customer Data</option>
                            <option value="salecompare">Sales Comparison</option>
                            <option value="product">product Details</option>
                        </select>
                    </div>

                    <label id="dur" for="duration" style="display: none;">Select Duration</label>
                    <table id="dura" style="display: none;">
                        <tr>
                            <th colspan="4">
                                <select id="duration" name="duration" style="width:100%;">
                                    <option value="yearly" selected>Select Duration</option>
                                    <option value="yearly">Yearly</option>
                                    <option value="monthly">Monthly</option>
                                    <option value="daily">Daily</option>
                                </select>
                            </th>

                        </tr>
                        <tr>
                            <th><label id="date_from" style="display: none;color:indianred;">Date From</label></th>
                        </tr>
                        <tr>

                            <th>
                                <div id="yearDropdown" class="dropdown" style="display: none;">
                                    <label for="year">Select Year</label>
                                    <select id="year" name="year">
                                        <!-- Populate with years dynamically -->
                                        <?php foreach ($years as $year) {
                                            // $select = ($_SESSION['paydate_date'] == $dateRow['formatted_date']) ? 'selected' : '';
                                            echo "<option value='$year'>$year</option>";
                                        } ?>
                                    </select>
                                </div>
                            </th>

                            <th>
                                <div id="monthDropdown" class="dropdown" style="display: none;">
                                    <label for="month">Select Month</label>
                                    <select id="month" name="month">
                                        <?php
                                        for ($i = 1; $i <= 12; $i++) {
                                            $monthName = date('F', mktime(0, 0, 0, $i, 1));
                                            echo "<option value='$i'>$monthName</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </th>

                            <th>
                                <div id="dayDropdown" class="dropdown" style="display: none;">
                                    <label for="day">Select Day</label>
                                    <select id="day" name="day">
                                        <?php
                                        for ($i = 1; $i <= 31; $i++) {
                                            echo "<option value='$i'>$i</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </th>
                        </tr>
                        <tr>
                            <th><label id="date_to" style="display: none;color:indianred;">Date to</label></th>
                        </tr>

                        <tr>
                            <th>
                                <div id="yearDropdown1" class="dropdown1" style="display: none;">
                                    <label for="year1">Select Year</label>
                                    <select id="year1" name="year1">
                                        <!-- Populate with years dynamically -->
                                        <?php foreach ($years as $year) {
                                            // $select = ($_SESSION['paydate_date'] == $dateRow['formatted_date']) ? 'selected' : '';
                                            echo "<option value='$year'>$year</option>";
                                        } ?>
                                    </select>
                                </div>
                            </th>

                            <th>
                                <div id="monthDropdown1" class="dropdown1" style="display: none;">
                                    <label for="month1">Select Month</label>
                                    <select id="month1" name="month1">
                                        <?php
                                        for ($i = 1; $i <= 12; $i++) {
                                            $monthName = date('F', mktime(0, 0, 0, $i, 1));
                                            echo "<option value='$i'>$monthName</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </th>

                            <th>
                                <div id="dayDropdown1" class="dropdown1" style="display: none;">
                                    <label for="day1">Select Day</label>
                                    <select id="day1" name="day1">
                                        <?php
                                        for ($i = 1; $i <= 31; $i++) {
                                            echo "<option value='$i'>$i</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </th>
                        </tr>

                    </table>

                    <div class="form-group">
                        <label for="sto_name" style="display: none;" id="sto">Store Customer<lable style="color: red; font-size: 14pt;">&nbsp;*</label></label>
                        <select name="customer" id="customer" style="display: none;">
                            <option value="">Select Customer</option>
                            <?php
                            while ($customerRow = mysqli_fetch_assoc($customerResult)) {
                                $selectedCustomer = (isset($_POST['customer']) && $_POST['customer'] == $customerRow['sto_name']) ? 'selected' : '';
                                echo "<option value='{$customerRow['sto_name']}' $selectedCustomer>{$customerRow['sto_name']}</option>";
                            }
                            ?>
                            <option value="all" selected>All Customer</option>
                        </select>
                    </div>



                    <button type="submit"><i class="fa fa-file-text"></i> &nbsp;Generate Report</button>
                    <button type="reset" style="background-color: transparent;color:green;">Clear</button>

                    <?php
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        $duration = $_POST['duration'];
                        $year = $_POST['year'];
                        $month = $_POST['month'];
                        $day = $_POST['day'];
                        $sql2 = "";

                        // Corrected SQL query with single quotes around variables

                        $customer = $_POST['customer'];
                        $_SESSION['customer'] = $customer;
                        $report = $_POST['receiptType'];
                        $_SESSION['receiptType'] = $report;
                        $route_id = $_SESSION['route_id'];



                        if (isset($_POST["receiptType"]) && $_POST["receiptType"] == "customerdata") {
                            $customerdataQuery = "";
                            if ($_SESSION["state"] === "seller") {
                                if ($customer === "all") {
                                    $customerdataQuery = "SELECT u.firstName,u.LastName,u.email,c.sto_name,c.sto_tep_number,c.sto_reg_no,c.sto_name,c.sto_loc from customers c left join users u on c.user_id=u.user_id WHERE c.route_id=$route_id";
                                } else {
                                    $customerdataQuery = "SELECT u.firstName,u.LastName,u.email,c.sto_name,c.sto_tep_number,c.sto_reg_no,c.sto_name,c.sto_loc from customers c left join users u on c.user_id=u.user_id WHERE sto_name='$customer' AND route_id=$route_id";
                                }
                            }
                            if ($_SESSION["state"] === "admin") {
                                if ($customer === "all") {
                                    $customerdataQuery = "SELECT u.firstName,u.LastName,u.email,c.sto_name,c.sto_tep_number,c.sto_reg_no,c.sto_name,c.sto_loc from customers c left join users u on c.user_id=u.user_id ";
                                } else {
                                    $customerdataQuery = "SELECT u.firstName,u.LastName,u.email,c.sto_name,c.sto_tep_number,c.sto_reg_no,c.sto_name,c.sto_loc from customers c left join users u on c.user_id=u.user_id WHERE sto_name='$customer'";
                                }
                            }
                            $customerdataresult = mysqli_query($connection, $customerdataQuery);

                            if (mysqli_num_rows($customerdataresult) > 0) {
                                while ($row = mysqli_fetch_assoc($customerdataresult)) {
                                    echo "<br>";
                                    echo "<label>Owners' Name :" . $row['firstName'] . " " . $row['LastName'] . "</label>";
                                    echo "<label>Store Name :" . $row['sto_name'] . "</label>";
                                    echo "<label>Registration No. :" . $row['sto_reg_no'] . "</label>";
                                    echo "<label>Contact No. :" . "+94" . $row['sto_tep_number'] . "</label>";
                                    echo "<label>Email Address:" . $row['email'] . "</label>";
                                    echo "<label>Location :" . $row['sto_loc'] . "</label>";
                                    echo "<hr>";
                                }

                                echo "<a href='/common/customer_data.php' style='color:blue;'>Download as PDF</a>";
                            } else {
                                echo "No Customer Details Found";
                            }
                        } else if (isset($_POST["receiptType"]) && $_POST["receiptType"] == "salessummery") {
                            echo '<hr>';
                            echo '<h4 style="text-decoration:underline;color:indianred;text-align:center;">Comprehensive Sales Report</h4>';


                            $route_id = $_SESSION['route_id'];
                            $sql = "";
                            if ($_SESSION["state"] === "seller") {
                                if ($_POST["duration"] == "yearly" && isset($_POST["year"]) && isset($_POST["year1"])) {
                                    $start = intval($_POST["year"]);
                                    $end = intval($_POST["year1"]);

                                    $sql1 = "SELECT  DATE_FORMAT(p.payment_date, '%Y-%m') AS month, SUM(p.total) AS sales_revenue
                FROM payment p
                INNER JOIN primary_orders po ON p.ord_id = po.ord_id
                INNER JOIN orders o ON po.ord_id = o.ord_id
                WHERE  YEAR(p.payment_date) IN (:year1, :year2) AND p.route_id = :route_id
                GROUP BY  DATE_FORMAT(p.payment_date, '%Y-%m')
                ORDER BY  DATE_FORMAT(p.payment_date, '%Y-%m');";

                                    $stmt1 = $pdo->prepare($sql1);
                                    $stmt1->execute(['year1' => $start, 'year2' => $end, 'route_id' => $route_id]);
                                    $data = $stmt1->fetchAll(PDO::FETCH_ASSOC);

                                    // Encode data as JSON
                                    $jsonData = json_encode($data);


                                    echo ' <canvas id="salesChart" width="400" height="200"></canvas>';


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

                                    $begin_date = sprintf("%04d-%02d", $start, $start_mon);
                                    $end_date = sprintf("%04d-%02d", $end, $end_mon);

                                    $sql1 = "SELECT  DATE_FORMAT(p.payment_date, '%Y-%m') AS month, SUM(p.total) AS sales_revenue
                FROM payment p
                INNER JOIN primary_orders po ON p.ord_id = po.ord_id
                INNER JOIN orders o ON po.ord_id = o.ord_id
                WHERE  DATE_FORMAT(p.payment_date, '%Y-%m') BETWEEN :year1 AND :year2 AND p.route_id = :route_id
                GROUP BY  DATE_FORMAT(p.payment_date, '%Y-%m')
                ORDER BY  DATE_FORMAT(p.payment_date, '%Y-%m');";

                                    $stmt1 = $pdo->prepare($sql1);
                                    $stmt1->execute(['year1' => $begin_date, 'year2' => $end_date, 'route_id' => $route_id]);
                                    $data = $stmt1->fetchAll(PDO::FETCH_ASSOC);

                                    // Encode data as JSON
                                    $jsonData = json_encode($data);


                                    echo ' <canvas id="salesChart" width="400" height="200"></canvas>';


                                    $sql = "SELECT p.payment_date, p.total, p.payment_method, p.balance, o.main_cat, o.sub_cat, o.order_count
                    FROM payment p
                    INNER JOIN primary_orders po ON p.ord_id = po.ord_id
                    INNER JOIN orders o ON po.ord_id = o.ord_id
                    WHERE DATE_FORMAT(p.payment_date, '%Y-%m') BETWEEN ? AND ? AND p.route_id = ?";

                                    $stmt = $connection->prepare($sql);
                                    $stmt->bind_param("ssi", $begin_date, $end_date, $route_id);
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


                                    $sql1 = "SELECT DATE_FORMAT(p.payment_date, '%Y-%m') AS month, SUM(p.total) AS sales_revenue
                FROM payment p
                INNER JOIN primary_orders po ON p.ord_id = po.ord_id
                INNER JOIN orders o ON po.ord_id = o.ord_id
                WHERE YEAR(p.payment_date) IN (:year1, :year2)
                GROUP BY DATE_FORMAT(p.payment_date, '%Y-%m')
                ORDER BY DATE_FORMAT(p.payment_date, '%Y-%m');";

                                    $stmt1 = $pdo->prepare($sql1);
                                    $stmt1->execute(['year1' => $start, 'year2' => $end]);
                                    $data = $stmt1->fetchAll(PDO::FETCH_ASSOC);

                                    // Encode data as JSON
                                    $jsonData = json_encode($data);


                                    echo ' <canvas id="salesChart" width="400" height="200"></canvas>';



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

                                    $begin_date = sprintf("%04d-%02d", $start, $start_mon);
                                    $end_date = sprintf("%04d-%02d", $end, $end_mon);



                                    $sql1 = "SELECT  DATE_FORMAT(p.payment_date, '%Y-%m') AS month, SUM(p.total) AS sales_revenue
                FROM payment p
                INNER JOIN primary_orders po ON p.ord_id = po.ord_id
                INNER JOIN orders o ON po.ord_id = o.ord_id
                WHERE  DATE_FORMAT(p.payment_date, '%Y-%m') BETWEEN :year1 AND :year2
                GROUP BY  DATE_FORMAT(p.payment_date, '%Y-%m')
                ORDER BY  DATE_FORMAT(p.payment_date, '%Y-%m');";

                                    $stmt1 = $pdo->prepare($sql1);
                                    $stmt1->execute(['year1' => $begin_date, 'year2' => $end_date]);
                                    $data = $stmt1->fetchAll(PDO::FETCH_ASSOC);

                                    // Encode data as JSON
                                    $jsonData = json_encode($data);


                                    echo ' <canvas id="salesChart" width="400" height="200"></canvas>';

                                    $sql = "SELECT p.payment_date, p.total, p.payment_method, p.balance, o.main_cat, o.sub_cat, o.order_count
                    FROM payment p
                    INNER JOIN primary_orders po ON p.ord_id = po.ord_id
                    INNER JOIN orders o ON po.ord_id = o.ord_id
                    WHERE DATE_FORMAT(p.payment_date, '%Y-%m') BETWEEN ? AND ?";

                                    $stmt = $connection->prepare($sql);
                                    $stmt->bind_param("ss", $begin_date, $end_date);
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
                            if ($_SESSION["state"] === "seller") {
                                echo '<hr>';
                                echo '<h5 style="color:gray;">Total Revenue : LKR.' . $total_revenue . '</h5>';
                                echo '<h5 style="color:gray;">Total Outstanding : LKR.' . $total_balance . '</h5>';
                                echo '<h5 style="color:gray;">Total Quantity Sold :' . $total_quantity_sold . ' items</h5>';
                                echo '<hr>';
                            }
                            if ($_SESSION["state"] === "admin") {
                                $sales_datar = $stmt2->fetchAll(PDO::FETCH_ASSOC);
                                $jsonDatas = json_encode($sales_datar);
                                echo ' <canvas id="salesChartr" width="400" height="200"></canvas>';
                                foreach ($sales_datar as $sale) {
                                    echo '<h5 style="color:indianred;">Route: ' . $sale['route'] . '</h5>';
                                    echo '<h5 style="color:gray;">Total Revenue: LKR ' . number_format($sale['total_sales'], 2) . '</h5>';
                                    echo '<h5 style="color:gray;">Total Outstanding: LKR ' . number_format($sale['total_balance'], 2) . '</h5>';
                                    echo '<h5 style="color:gray;">Total Quantity Sold: ' . $sale['total_quantity_sold'] . ' items</h5>';
                                    echo '<hr>';
                                }
                            }
                            //<!-- Chart for Sales by Category -->
                            echo ' <div style="height:300px; align-items: center; justify-content: center; ">
                                    <h5 style="color:gray;text-align:center;">Sales By Category</h5>
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

                            echo '<h5 style="color:gray;text-align:center;">Items Sold of Top Three Subcategories by Each Main Category</h5>';
                            echo '<ul >';
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
                            echo '<h5 style="color:gray;text-align:center;">Percentage of Payment Method Used</h5>';
                            echo '<div style="width:210px; margin-left:22%; ">

                            <canvas id="paymentMethodsChart" width="100px" height="150px"></canvas>

                            </div>';
                            echo '<br>';
                        } else if ($_POST["receiptType"] === "product") {

                            $productQuery = "SELECT * from product";

                            $productresult = mysqli_query($connection, $productQuery);
                            $mainCategories = array();

                            while ($row2 = mysqli_fetch_assoc($productresult)) {
                                $mainCat = $row2["main_cat"];
                                $subCat = $row2["sub_cat"];
                                $credit = $row2["creditPrice"];
                                $check = $row2["checkPrice"];
                                $cash = $row2["cashPrice"];


                                // If main category already exists in the array, append the sub product
                                if (array_key_exists($mainCat, $mainCategories)) {
                                    // Check if the subcategory already exists, if yes, add the count
                                    $found = false;
                                    foreach ($mainCategories[$mainCat] as &$subProduct) {
                                        if ($subProduct['sub_cat'] === $subCat) {
                                            $subProduct['count'] += $count;
                                            $found = true;
                                            break;
                                        }
                                    }
                                    // If subcategory not found, add it to the array
                                    if (!$found) {
                                        $mainCategories[$mainCat][] = array("sub_cat" => $subCat, "cashPrice" => $cash, "checkPrice" => $check, "creditPrice" => $credit);
                                    }
                                } else {
                                    // If main category doesn't exist, create a new entry in the array
                                    $mainCategories[$mainCat] = array(array("sub_cat" => $subCat, "cashPrice" => $cash, "checkPrice" => $check, "creditPrice" => $credit));
                                }
                            }

                            // Loop through main categories and display them with clickable functionality
                            echo '<table>';
                            foreach ($mainCategories as $mainCat => $subProducts) {
                                echo '<tr><th colspan="4" style="height:25px;" class="empty"></tr>';
                                echo '<tr class="empty1"><th colspan="4" style="text-align:left; ">' . $mainCat . '</th><tr>';
                                echo '<tr><th >Product Name</th><th>Cash Price</th><th>Check Price</th><th>Credit Price</th><tr>';

                                foreach ($subProducts as $subProduct) {
                                    echo "<tr><td id='left'>{$subProduct['sub_cat']} </td><td>{$subProduct['cashPrice']}</td> <td>{$subProduct['checkPrice']}</td> <td>{$subProduct['creditPrice']}</td></tr>";
                                }
                            }
                            echo "</table>";
                            echo '<br>';
                            echo '<br>';
                            echo "<a href='/common/product_data.php' style='color:blue;'>Download as PDF</a>";
                        }

                        if ($_POST["receiptType"] === "salecompare" && isset($_POST["year"]) && isset($_POST["year1"])) {
                            $start = $_POST["year"];
                            $end = $_POST["year1"];

                            $currentYearQuery = "";
                            $previousYearQuery = "";
                            if ($_SESSION["state"] === "seller") {
                                $currentYearQuery = "SELECT MONTH(payment_date) AS month, SUM(total) AS total_sales FROM payment WHERE YEAR(payment_date) = '$start' AND route_id=  $route_id GROUP BY MONTH(payment_date)";

                                // Fetch total sales amounts for each month of the previous year
                                $previousYearQuery = "SELECT MONTH(payment_date) AS month, SUM(total) AS total_sales FROM payment WHERE YEAR(payment_date) = '$end' AND route_id=  $route_id GROUP BY MONTH(payment_date)";
                            }
                            if ($_SESSION["state"] === "admin") {
                                $currentYearQuery = "SELECT MONTH(payment_date) AS month, SUM(total) AS total_sales FROM payment WHERE YEAR(payment_date) = '$start' GROUP BY MONTH(payment_date)";
                                // Fetch total sales amounts for each month of the previous year
                                $previousYearQuery = "SELECT MONTH(payment_date) AS month, SUM(total) AS total_sales FROM payment WHERE YEAR(payment_date) = '$end' GROUP BY MONTH(payment_date)";
                            }
                            $currentYearResult = mysqli_query($connection, $currentYearQuery);
                            $previousYearResult = mysqli_query($connection, $previousYearQuery);
                            // Initialize arrays to store monthly sales amounts for the current and previous years
                            $currentYearSales = array_fill(1, 12, 0); // Initialize with 0 for each month
                            $previousYearSales = array_fill(1, 12, 0);
                            if ($currentYearResult) {
                                while ($row = mysqli_fetch_assoc($currentYearResult)) {
                                    $currentYearSales[$row['month']] = $row['total_sales'];
                                }
                            }
                            // Populate arrays with fetched data
                            if ($currentYearResult) {
                                while ($row = mysqli_fetch_assoc($currentYearResult)) {
                                    $previousYearSales[$row['month']] = $row['total_sales'];
                                }
                            }


                            // Calculate percentage increase/decrease for each month compared to the previous year
                            $percentageChanges = array();
                            foreach ($currentYearSales as $month => $currentSales) {
                                $previousSales = $previousYearSales[$month];
                                if ($previousSales != 0) {
                                    $percentageChange = (($currentSales - $previousSales) / $previousSales) * 100;
                                } else {
                                    $percentageChange = ($currentSales != 0) ? 100 : 0;
                                }
                                $percentageChanges[$month] = $percentageChange;
                            }

                            // Display the comparative sales report
                            echo '<h3 style="text-align:center;">Sales Comparison of years</h3>';
                            echo '<table border="1">';
                            echo ' <tr style="background-color:lightgreen;">';
                            echo '<th id="leftth">Month</th>';
                            echo '<th>' . $start . "" . ' Sales</th><th>Percentage of change</th>';
                            echo '<th id="rightth">' . $end . "" . ' Sales</th>
                            
        
    </tr>';

                            // Loop through months
                            for ($i = 1; $i <= 12; $i++) {
                                // Calculate month name
                                $monthName = date('F', mktime(0, 0, 0, $i, 1));
                                // Display row for each month
                                echo "<tr>";
                                echo "<td><b>$monthName</td>";
                                echo "<td><b>{$currentYearSales[$i]}.00</td>";
                                if ($currentYearSales[$i] > $previousYearSales[$i]) {
                                    echo "<td><b>" . number_format($percentageChanges[$i], 2) . "%&nbsp;&nbsp;<i class='fa fa-arrow-up' style='color:green;'></i></td>";
                                } else {
                                    echo "<td><b>" . number_format($percentageChanges[$i], 2) . "%&nbsp;&nbsp;<i class='fa fa-arrow-down' style='color:red;'></i></td>";
                                }


                                echo "<td><b>{$previousYearSales[$i]}.00</td>";

                                echo "</tr>";
                            }
                            echo '</table>';
                            echo "<br>";
                            echo '<label style="color:red;font-size:13px;">*Remember All sales Amounts in LKR.</label>';
                            echo '<a href="sale_compare_report.php" style="color:blue;cursor:pointer;float:right;">Download as Pdf</a>';
                        }
                    }


                    ?>
            </form>
        </div>

    </div>
    <script>
        // Step 3: Use JavaScript to Render the Chart
        const ctxt = document.getElementById('salesChart').getContext('2d');
        const chartData = <?php echo $jsonData; ?>;

        const labels = chartData.map(item => item.month);
        const data = chartData.map(item => item.sales_revenue);

        const salesChartr = new Chart(ctxt, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Sales Revenue',
                    data: data,
                    borderColor: 'green',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Month'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Sales Revenue'
                        }
                    }
                },
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Sales Revenue by Month'
                    }
                }
            }
        });
    </script>
    <script>
        function back() {
            window.history.back();
        }
    </script>

    <script>
        // Step 3: Use JavaScript to Render the Chart
        const ctxz = document.getElementById('salesChartr').getContext('2d');
        const chartData = <?php echo $jsonDatas; ?>;

        const routes = [...new Set(chartData.map(item => item.route))];
        const labelsr = [...new Set(chartData.map(item => item.month))];

        const datasets = routes.map(route => {
            return {
                label: route,
                data: labelsr.map(label => {
                    const item = chartData.find(d => d.route === route && d.month === label);
                    return item ? item.total_sales : 0;
                }),
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderWidth: 1
            };
        });

        const salesChart = new Chart(ctxz, {
            type: 'line',
            data: {
                labels: labels,
                datasets: datasets
            },
            options: {
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Month'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Sales Revenue'
                        }
                    }
                },
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Sales Revenue by Route and Month'
                    }
                }
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const receiptSelect = document.getElementById("receiptType");
            const duration = document.getElementById('dura');
            const durationSelect = document.getElementById("duration");
            const dur = document.getElementById('dur');
            const customer = document.getElementById('customer');
            const cus = document.getElementById('sto');
            const yearDropdown = document.getElementById("yearDropdown");
            const yearDropdowni = document.getElementById("yearDropdown1");
            const datefrom = document.getElementById("date_from");
            const dateto = document.getElementById("date_to");




            receiptSelect.addEventListener("change", function() {
                const selectedValue = this.value;
                if (selectedValue === 'salessummery') {
                    dur.style.display = 'block';
                    duration.style.display = 'block';

                } else if (selectedValue === 'customerdata') {
                    dur.style.display = 'none';
                    duration.style.display = 'none';
                    cus.style.display = 'block';
                    customer.style.display = 'block';

                } else if (selectedValue === 'product') {
                    dur.style.display = 'none';
                    duration.style.display = 'none';
                    cus.style.display = 'none';
                    customer.style.display = 'none';

                } else if (selectedValue === 'salecompare') {
                    duration.style.display = 'block';
                    yearDropdown.style.display = 'block';
                    yearDropdowni.style.display = 'block';
                    datefrom.style.display = "block";
                    dateto.style.display = "block";
                    durationSelect.style.display = 'none';
                    cus.style.display = 'none';
                    customer.style.display = 'none';

                }

            });
        });
        document.addEventListener("DOMContentLoaded", function() {
            const durationSelect = document.getElementById("duration");
            const yearDropdown = document.getElementById("yearDropdown");
            const monthDropdown = document.getElementById("monthDropdown");
            const dayDropdown = document.getElementById("dayDropdown");

            const yearDropdown1 = document.getElementById("yearDropdown1");
            const monthDropdown1 = document.getElementById("monthDropdown1");
            const dayDropdown1 = document.getElementById("dayDropdown1");

            const datefrom = document.getElementById("date_from");
            const dateto = document.getElementById("date_to");



            durationSelect.addEventListener("change", function() {

                const selectedValue = this.value;
                if (selectedValue === "yearly") {
                    yearDropdown.style.display = "block";
                    yearDropdown1.style.display = "block";
                    datefrom.style.display = "block";
                    dateto.style.display = "block";
                    monthDropdown.style.display = "none";
                    dayDropdown.style.display = "none";
                    monthDropdown1.style.display = "none";
                    dayDropdown1.style.display = "none";
                } else if (selectedValue === "monthly") {
                    yearDropdown.style.display = "block";
                    monthDropdown.style.display = "block";
                    yearDropdown1.style.display = "block";
                    monthDropdown1.style.display = "block";
                    datefrom.style.display = "block";
                    dateto.style.display = "block";
                    dayDropdown.style.display = "none";
                    dayDropdown1.style.display = "none";
                } else if (selectedValue === "daily") {
                    yearDropdown.style.display = "block";
                    monthDropdown.style.display = "block";
                    dayDropdown.style.display = "block";
                    yearDropdown1.style.display = "block";
                    monthDropdown1.style.display = "block";
                    dayDropdown1.style.display = "block";
                    datefrom.style.display = "block";
                    dateto.style.display = "block";
                }
            });
        });
    </script>
    <script>
        // Data for Sales by Category chart
        var mainCategories = <?php echo json_encode(array_keys($main_category_counts)); ?>;
        var mainCategoryCounts = <?php echo json_encode(array_values($main_category_counts)); ?>;

        // Create Sales by Category chart
        var ctx = document.getElementById('salesByCategoryChart').getContext('2d');
        var salesByCategoryChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: mainCategories,
                datasets: [{
                    label: 'Sales by Category',
                    data: mainCategoryCounts,
                    backgroundColor: '#4caf50',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            },

        });
    </script>
    <script>
        // Data for Distribution of Payment Methods chart
        var paymentMethods = <?php echo json_encode(array_keys($payment_method_percentages)); ?>;
        var paymentMethodPercentages = <?php echo json_encode(array_values($payment_method_percentages)); ?>;

        // Create Distribution of Payment Methods chart
        var ctx2 = document.getElementById('paymentMethodsChart').getContext('2d');
        var paymentMethodsChart = new Chart(ctx2, {
            type: 'pie',
            data: {
                labels: paymentMethods,
                datasets: [{
                    data: paymentMethodPercentages,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.5)',
                        'rgba(54, 162, 235, 0.5)',
                        'rgba(255, 206, 86, 0.5)',
                        'rgba(75, 192, 192, 0.5)',
                        'rgba(153, 102, 255, 0.5)',
                        'rgba(255, 159, 64, 0.5)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                legend: {
                    position: 'right'
                }
            }
        });
    </script>

</body>

</html>