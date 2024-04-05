if (isset($_POST['generate_pdf'])) {

    try {
    $pdo->beginTransaction();

    // Extract values from the form
    $route_id = $_SESSION["route_id"];
    $store_name = $_SESSION['selected_store'];
    $total = $_SESSION['totalAmount'];
    $payment_date = date('Y-m-d H:i:s'); // Adjusted the date format
    $payment_method = $_SESSION['selected_payment_method'];
    $payment_amout = $_SESSION['paymentAmount']; // Added a missing semicolon
    $pay_period = ($payment_method == 'credit') ? $_POST['credit_period'] : null;
    $balance = $_SESSION['balance'];

    // Your INSERT query for the 'payment' table
    $query1 = "INSERT INTO payment(route_id, store_name, total, payment_date, payment_method, pay_period, payment_amout, balance) 
               VALUES (:route_id, :store_name, :total, :payment_date, :payment_method, :pay_period, :payment_amout, :balance)";

    // Prepare and execute the query
    $stmt = $pdo->prepare($query1);
    $stmt->bindParam(':route_id', $route_id);
    $stmt->bindParam(':store_name', $store_name);
    $stmt->bindParam(':total', $total);
    $stmt->bindParam(':payment_date', $payment_date);
    $stmt->bindParam(':payment_method', $payment_method);
    $stmt->bindParam(':pay_period', $pay_period);
    $stmt->bindParam(':payment_amout', $payment_amout);
    $stmt->bindParam(':balance', $balance); // Assuming balance starts at 0, adjust as needed
    $stmt->execute();

    // Get the last inserted ID (ord_id) from the 'payment' table
    $ord_id = $pdo->lastInsertId();

    foreach ($orderDetails as $orderDetail) {
        $mainCategory = $orderDetail['main_category'];
        $subCategory = $orderDetail['sub_category'];
        $count = $orderDetail['count'];

        // Your INSERT query for the 'orders' table
        $query2 = "INSERT INTO orders (ord_id, route_id, store_name, main_cat, sub_cat, order_count) 
                   VALUES (:ord_id, :route_id, :store_name, :main_cat, :sub_cat, :order_count)";

        $stmt2 = $pdo->prepare($query2);
        $stmt2->bindParam(':ord_id', $ord_id);
        $stmt2->bindParam(':route_id', $route_id);
        $stmt2->bindParam(':store_name', $store_name);
        $stmt2->bindParam(':main_cat', $mainCategory);
        $stmt2->bindParam(':sub_cat', $subCategory);
        $stmt2->bindParam(':order_count', $count);
        $stmt2->execute();
    }

    // Commit the transaction
    $pdo->commit();

    // Continue with any other logic or redirect
    // ...

} catch (Exception $e) {
    // Rollback the transaction in case of an error
    $pdo->rollBack();
    echo "Failed: " . $e->getMessage();
}



    $email=$_SESSION['email'];
    $totalAmount = $_SESSION['totalAmount'];
    $paymentAmout=$_SESSION['paymentAmount'] ;
    $balance=$_SESSION['balance'];
    $select_store=$_SESSION['selected_store'];
    $Subject="Order Details";
    $body="\n\nDear Customer,\n\nThe Purchase that ".$select_store." make on ".$localTime." is Total Amount is : Rs." .$totalAmount. " And You have Paid Rs.". $paymentAmout." And Your Outstanding Balance is : Rs. ".$balance."\n\nThank You!...\n\nRegards,\nLotus Electicals (PVT)LTD.";
      //  sendmail($Subject,$body,$_SESSION['email'],$_SESSION['firstname']);
       // sendsms($_SESSION['telephone'],$body);
        // Redirect to print.php*/
        header('Location: generatPdf.php');


    exit(); // Make sure to exit to prevent further execution of the script
    }