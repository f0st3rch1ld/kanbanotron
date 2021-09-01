<?php

$active_po = $_GET['active_po'];
$vndr = $_GET['vndr'];
$order_data;

// this connects to the kanbanotron database for the order_data
include '../db/kanbanotron_connection.php';

$remove_knbn_query = "SELECT order_data FROM purchase_orders WHERE order_id='" . $active_po . "'";
$remove_knbn_result = $conn->query($remove_knbn_query);

// assigns array to $order_data variable
while ($row = $remove_knbn_result->fetch_assoc()) {
    $order_data = json_decode($row['order_data']);
}

$conn->close();

include '../db/knbn_wp_connection.php';
include '../db/request.php';

// knbn_info_request variables

// $knbn_external_url
// $knbn_vendor
// $knbn_part_number
// $knbn_vendor_part_number
// $knbn_description
// $knbn_package_quantity
// $knbn_reorder_quantity
// $knbn_blue_bin_quantity
// $knbn_red_bin_quantity
// $knbn_lead_time
// $knbn_notes

$order_data_index = 0;
$amount_of_deletions = 0;
$deletion_locations = 0;

function po_check($x)
{
    global $order_data;
    global $order_data_index;
    global $amount_of_deletions;
    global $vndr;
    if ($x == $vndr) {
        array_splice($order_data, $order_data_index, 1);
        $amount_of_deletions++;
    } else {
        $order_data_index++;
    }
}



foreach ($order_data as $knbn) {
    knbn_info_request($knbn);
    if ($knbn_vendor == $vndr) {
        $deletion_locations++;
    }
}

while ($deletion_locations > $amount_of_deletions) {
    knbn_info_request($order_data[$order_data_index]);
    po_check($knbn_vendor);
}

$conn->close();

// reconnects to the original kanbanotron database to update the order.
include '../db/kanbanotron_connection.php';

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