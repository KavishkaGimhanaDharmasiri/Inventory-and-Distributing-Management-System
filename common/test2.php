<?php
$dsn = 'mysql:host=localhost;dbname=lotus';
$username = 'root';
$password = '';
$options = [];
try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    die('Connection failed: ' . $e->getMessage());
}

$year1 = 2023;
$year2 = 2024;

$sql = "
SELECT 
    YEAR(p.payment_date) AS year,
    r.route,
    SUM(p.total) AS total_sales
FROM 
    payment p
INNER JOIN 
    primary_orders po ON p.ord_id = po.ord_id
INNER JOIN 
    orders o ON po.ord_id = o.ord_id
INNER JOIN 
    route r ON p.route_id = r.route_id
WHERE 
    YEAR(p.payment_date) IN (?, ?)
GROUP BY 
    r.route, YEAR(p.payment_date)
ORDER BY 
    r.route, YEAR(p.payment_date);
";

$stmt = $pdo->prepare($sql);
$stmt->execute([$year1, $year2]);
$sales_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Encode data as JSON
$jsonData = json_encode($sales_data);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Sales Revenue Chart</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <canvas id="salesChart" width="400" height="200"></canvas>
    <script>
        // Step 3: Use JavaScript to Render the Chart
        const ctx = document.getElementById('salesChart').getContext('2d');
        const chartData = <?php echo $jsonData; ?>;

        // Extract unique route-year combinations
        const labels = chartData.map(item => `${item.route} (${item.year})`);

        // Extract total sales for each route-year
        const totalSales = chartData.map(item => item.total_sales);

        const salesChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Total Sales Revenue',
                    data: totalSales,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Routes (Year)'
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
                        text: 'Sales Revenue by Route and Year'
                    }
                }
            }
        });
    </script>
</body>

</html>