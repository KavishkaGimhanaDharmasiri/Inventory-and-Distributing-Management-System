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

// Close the database connection
?>
<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1">
    <title>Report</title>
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

        h4,
        ul,
        li {
            color: black;
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

            <a href="javascript:void(0);" onclick="back()" class="back-link" style="float:left;font-size:25px; "><i class="fa fa-angle-left"></i></a>
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
                            <option value="return">Return summery</option>
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
                            <th>
                                <div id="yearDropdown" class="dropdown" style="display: none;">
                                    <label for="year">Select Year</label>
                                    <select id="year" name="year">
                                        <!-- Populate with years dynamically -->
                                        <?php
                                        $currentYear = date('Y');
                                        for ($i = $currentYear; $i >= $currentYear - 10; $i--) {
                                            echo "<option value='$i'>$i</option>";
                                        }
                                        ?>
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
                            <option value="all">All Customer</option>
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

                        // Corrected SQL query with single quotes around variables

                        $customer = $_POST['customer'];
                        $_SESSION['customer'] = $customer;
                        $report = $_POST['receiptType'];
                        $_SESSION['receiptType'] = $report;
                        $route_id = $_SESSION['route_id'];



                        if (isset($_POST["receiptType"]) && $_POST["receiptType"] == "customerdata") {

                            if ($customer === "all") {
                                $customerdataQuery = "SELECT u.firstName,u.LastName,u.email,c.sto_name,c.sto_tep_number,c.sto_reg_no,c.sto_name,c.sto_loc from customers c left join users u on c.user_id=u.user_id WHERE c.route_id=$route_id";
                            } else {
                                $customerdataQuery = "SELECT u.firstName,u.LastName,u.email,c.sto_name,c.sto_tep_number,c.sto_reg_no,c.sto_name,c.sto_loc from customers c left join users u on c.user_id=u.user_id WHERE sto_name='$customer' AND route_id=$route_id";
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

                            $route_id = $_SESSION['route_id']; // Replace with the specific route_id you want to generate the report for
                            $sql = "SELECT p.payment_date, p.total, p.payment_method,p.balance, o.main_cat, o.sub_cat, o.order_count FROM payment p
        INNER JOIN primary_orders po ON p.ord_id = po.ord_id
        INNER JOIN orders o ON po.ord_id = o.ord_id
        WHERE p.route_id = $route_id";

                            $result = mysqli_query($connection, $sql);

                            // Process the data
                            $sales_data = array();
                            while ($row = mysqli_fetch_assoc($result)) {
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



                        if ($_POST["receiptType"] === "product") {

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

                        if ($_POST["receiptType"] === "salecompare") {

                            $currentYear = date('Y');
                            $currentYearQuery = "SELECT MONTH(payment_date) AS month, SUM(total) AS total_sales 
                     FROM payment 
                     WHERE YEAR(payment_date) = $currentYear AND route_id=  $route_id
                     GROUP BY MONTH(payment_date)";
                            $currentYearResult = mysqli_query($connection, $currentYearQuery);

                            // Fetch total sales amounts for each month of the previous year
                            $previousYear = $currentYear - 1;
                            $previousYearQuery = "SELECT MONTH(payment_date) AS month, SUM(total) AS total_sales 
                      FROM payment 
                      WHERE YEAR(payment_date) = $previousYear AND route_id=  $route_id
                      GROUP BY MONTH(payment_date)";
                            $previousYearResult = mysqli_query($connection, $previousYearQuery);

                            // Initialize arrays to store monthly sales amounts for the current and previous years
                            $currentYearSales = array_fill(1, 12, 0); // Initialize with 0 for each month
                            $previousYearSales = array_fill(1, 12, 0);

                            // Populate arrays with fetched data
                            while ($row = mysqli_fetch_assoc($currentYearResult)) {
                                $currentYearSales[$row['month']] = $row['total_sales'];
                            }
                            while ($row = mysqli_fetch_assoc($previousYearResult)) {
                                $previousYearSales[$row['month']] = $row['total_sales'];
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
                            echo '<th>' . $currentYear . "" . ' Sales</th><th>Percentage of change</th>';
                            echo '<th id="rightth">' . $previousYear . "" . ' Sales</th>
                            
        
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
        function back() {
            window.history.back();
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const durationSelect = document.getElementById("duration");
            const yearDropdown = document.getElementById("yearDropdown");
            const monthDropdown = document.getElementById("monthDropdown");
            const dayDropdown = document.getElementById("dayDropdown");

            durationSelect.addEventListener("change", function() {
                const selectedValue = this.value;
                if (selectedValue === "yearly") {
                    yearDropdown.style.display = "block";
                    monthDropdown.style.display = "none";
                    dayDropdown.style.display = "none";
                } else if (selectedValue === "monthly") {
                    yearDropdown.style.display = "block";
                    monthDropdown.style.display = "block";
                    dayDropdown.style.display = "none";
                } else if (selectedValue === "daily") {
                    yearDropdown.style.display = "block";
                    monthDropdown.style.display = "block";
                    dayDropdown.style.display = "block";
                }
            });
        });

        document.addEventListener("DOMContentLoaded", function() {
            const receiptSelect = document.getElementById("receiptType");
            const duration = document.getElementById('dura');
            const dur = document.getElementById('dur');
            const customer = document.getElementById('customer');
            const cus = document.getElementById('sto');


            receiptSelect.addEventListener("change", function() {
                const selectedValue = this.value;
                if (selectedValue === "salesreceipt") {
                    cus.style.display = 'block';
                    customer.style.display = 'block';
                    dur.style.display = 'block';
                    duration.style.display = 'block';
                } else if (selectedValue === 'salessummery') {
                    cus.style.display = 'block';
                    customer.style.display = 'block';
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
                    dur.style.display = 'none';
                    duration.style.display = 'none';
                    cus.style.display = 'none';
                    customer.style.display = 'none';

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
            }
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