<?php

// add current kanban to purchase order

// retrieves data passed through url
$knbn_uid = $_GET['knbn_uid'];
$active_po = $_GET['active_po'];
$order_data;

// opens new connection to database
include '../db/kanbanotron_connection.php';

$current_po_order_data_query = "SELECT order_data FROM purchase_orders WHERE order_id='" . $active_po . "'";
$current_po_order_data_result = $conn->query($current_po_order_data_query);

while ($row = $current_po_order_data_result->fetch_assoc()) {
    $order_data = json_decode($row['order_data']);

    // checks to see if any order data has been assigned yet?
    if ($order_data) {
        if (!in_array($knbn_uid, $order_data)) {
            array_push($order_data, $knbn_uid);
        } else {
            echo 'This Kanban is already on order';
        }
        
    } else {
        $order_data = array(
            $knbn_uid
        );
        echo 'Order Data Successfully Created';
    }
    
}

// encodes new array back into a string ready to be pushed to database
$order_data = json_encode($order_data);

echo var_dump($order_data);

// Update Database with new array
$new_po_order_data_update = "UPDATE purchase_orders SET order_data='" . $order_data . "' WHERE order_id='" . $active_po . "'";

if ($conn->query($new_po_order_data_update) === TRUE) {
    echo 'Record updated successfully';
} else {
    echo "Error updating record: " . $conn->error;
}

// close database connection
$conn->close();