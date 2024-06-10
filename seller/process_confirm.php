<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . "/common/db_connection.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/common/den_fun.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/common/email_sms.php");

if (!isset($_SESSION['option_visit']) || !isset($_SESSION['index_visit']) || !isset($_SESSION['route_id']) || !isset($_SESSION["state"]) || !isset($_SESSION['payment_visit']) || $_SESSION["state"] != 'seller') {
    acess_denie();
    exit();
} else {
    $_SESSION['add_customer_visit'] = true;
}


$orderDetails = $_SESSION['order_details'] ?? [];



if (!isset($_SESSION['process_payment'])) {
    try {
        $pdo->beginTransaction();

        // Extract values from the form
        $route_id = $_SESSION["route_id"];
        $store_name = $_SESSION['selected_store'];
        $total = $_SESSION['totalAmount'];
        $payment_date = date('Y-m-d');
        $payment_method = $_SESSION['selected_payment_method'];
        $payment_amount = $_SESSION['paymentAmount'];
        $pay_period = ($payment_method == 'credit') ?  $_SESSION['pay_period'] : null;
        $balance = $_SESSION['balance'];

        /*foreach ($orderDetails as $order) {
        $mainCategory = $order['main_category'];
        $subCategory = $order['sub_category'];
        $count = $order['count'];

        // Update feed_item table
        $query = "UPDATE feed_item SET count = count - :count WHERE main_cat = :main_cat AND sub_cat = :sub_cat";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':count', $count);
        $stmt->bindParam(':main_cat', $mainCategory);
        $stmt->bindParam(':sub_cat', $subCategory);

        if ($stmt->execute()) {
            // Successful update
        } else {
            // Error occurred while updating the database
            echo '<script>alert("Error: Unable to update product quantity.\n\nContact Adminstrator");</script>';
            return; // Exit function
        }
    }*/

        // Insert into primary_orders table
        $ord_type = "sale";
        $ord_state = "complete";
        $ord_date = date('Y-m-d');
        $query3 = "INSERT INTO primary_orders (route_id, ord_date, store_name, order_type, order_state)
       VALUES (:route_id, :ord_date, :store_name, :order_type, :order_state)";

        $stmt1 = $pdo->prepare($query3);
        $stmt1->bindParam(':route_id', $route_id);
        $stmt1->bindParam(':ord_date', $ord_date);
        $stmt1->bindParam(':store_name', $store_name);
        $stmt1->bindParam(':order_type', $ord_type);
        $stmt1->bindParam(':order_state', $ord_state);
        $stmt1->execute();
        $ord_id = $pdo->lastInsertId();

        $_SESSION['ord_id'] = $ord_id;

        // Insert into orders table
        foreach ($orderDetails as $orderDetail) {
            $mainCategory = $orderDetail['main_category'];
            $subCategory = $orderDetail['sub_category'];
            $count = $orderDetail['count'];

            $query2 = "INSERT INTO orders (ord_id, main_cat, sub_cat, order_count) 
           VALUES (:ord_id, :main_cat, :sub_cat, :order_count)";
            $stmt2 = $pdo->prepare($query2);
            $stmt2->bindParam(':ord_id', $ord_id);
            $stmt2->bindParam(':main_cat', $mainCategory);
            $stmt2->bindParam(':sub_cat', $subCategory);
            $stmt2->bindParam(':order_count', $count);
            $stmt2->execute();
        }

        // Insert into payment table
        $query1 = "INSERT INTO payment (ord_id, route_id, store_name, total, payment_date, payment_method, pay_period, payment_amout, balance) VALUES (:ord_id, :route_id, :store_name, :total, :payment_date, :payment_method, :pay_period, :payment_amount, :balance)";
        $stmt = $pdo->prepare($query1);
        $stmt->bindParam(':ord_id', $ord_id);
        $stmt->bindParam(':route_id', $route_id);
        $stmt->bindParam(':store_name', $store_name);
        $stmt->bindParam(':total', $total);
        $stmt->bindParam(':payment_date', $payment_date);
        $stmt->bindParam(':payment_method', $payment_method);
        $stmt->bindParam(':pay_period', $pay_period);
        $stmt->bindParam(':payment_amount', $payment_amount);
        $stmt->bindParam(':balance', $balance);
        $stmt->execute();

        // Commit the transaction
        $pdo->commit();
    } catch (Exception $e) {
        // Rollback the transaction in case of an error
        $pdo->rollBack();
        echo '<script>alert(' . $e->getMessage() . ');</script>';
    }


    $telephone = $_SESSION['telephone'];
    $modifiedNumber = '94' . substr($telephone, 0);
    $email = $_SESSION['email'];
    $totalAmount = $_SESSION['totalAmount'];
    $paymentAmout = $_SESSION['paymentAmount'];
    $balance = $_SESSION['balance'];
    $select_store = $_SESSION['selected_store'];
    $selectedPaymentMethod = $_SESSION['selected_payment_method'];
    $localTime = date("Y-m-d H:i:s");
    $Subject = "Order Details";

    // Email body
    $body = "\n\nDear Customer,\n\nThe Purchase that " . $select_store . " make on " . $localTime . " is Total Amount is : Rs." . $totalAmount . " And You have Paid Rs." . $paymentAmout . " And Your Outstanding Balance is : Rs. " . $balance . "\n\nThank You!...\n\nRegards,\nLotus Electicals (PVT)LTD.";

    // Send email
    // sendmail($Subject, $body, $_SESSION['email'], $_SESSION['firstname']);

    // Prepare SMS body
    $smsbody = urlencode($body);

    // Send SMS
    //sendsms($modifiedNumber, $smsbody);
    $_SESSION['process_payment'] = true;
}
header('Location:test_pdf.php');
