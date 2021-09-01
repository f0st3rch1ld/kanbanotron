<?php

$active_po = $_GET['active_po'];
$knbn_index = $_GET['knbn_index'];
$order_data;

include '../db/kanbanotron_connection.php';

$remove_knbn_query = "SELECT order_data FROM purchase_orders WHERE order_id='" . $active_po . "'";
$remove_knbn_result = $conn->query($remove_knbn_query);

// if there was an order found, removes selected element by it's index then re-encodes array into json string
if ($remove_knbn_result->num_rows > 0) {
    while ($row = $remove_knbn_result->fetch_assoc()) {
        $order_data = json_decode($row['order_data']);
        array_splice($order_data, $knbn_index, 1);
    }
} else {
    echo 'There was a problem updating the purchase order.';
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
