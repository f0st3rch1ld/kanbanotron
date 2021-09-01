<?php

$purchaseorder_table_data_array;
$purchaseorderlineret_table_data_array;

function retrieve_po_data()
{

    global $purchaseorder_table_data_array;
    global $purchaseorderlineret_table_data_array;

    $purchaseorder_table_data_array = array();
    $purchaseorderlineret_table_data_array = array();

    // QuickBooks Database Connection
    include 'qb_data_connection.php';

    // Request from purchaseorder table
    $purchaseorder_request = "SELECT TxnID, TimeCreated, VendorRef_FullName, Memo, IsFullyReceived FROM purchaseorder WHERE IsFullyReceived=0 AND YEAR(TimeCreated) >= '2021'";
    $purchaseorder_request_result = $conn->query($purchaseorder_request);

    // Assigns request data to an array
    if ($purchaseorder_request_result->num_rows > 0) {
        while ($row = $purchaseorder_request_result->fetch_assoc()) {
            $temp_po_array = array(
                'TxnID' => $row['TxnID'],
                'TimeCreated' => $row['TimeCreated'],
                'VendorRef_FullName' => $row['VendorRef_FullName'],
                'Memo' => $row['Memo'],
                'IsFullyReceived' => $row['IsFullyReceived']
            );
            array_push($purchaseorder_table_data_array, $temp_po_array);
        }
    }

    // echo var_dump($purchaseorder_table_data_array);

    // Request from purchaseorderlineret table
    $purchaseorderlineret_request = "SELECT ItemRef_ListID, ItemRef_FullName, Description, Quantity, PARENT_IDKEY FROM purchaseorderlineret";
    $purchaseorderlineret_request_result = $conn->query($purchaseorderlineret_request);

    // Assigns request data to an array
    if ($purchaseorder_request_result->num_rows > 0) {
        while ($row = $purchaseorderlineret_request_result->fetch_assoc()) {
            $temp_po_items_array = array(
                'ItemRef_ListID' => $row['ItemRef_ListID'],
                'ItemRef_FullName' => $row['ItemRef_FullName'],
                'Description' => $row['Description'],
                'Quantity' => $row['Quantity'],
                'PARENT_IDKEY' => $row['PARENT_IDKEY']
            );
            array_push($purchaseorderlineret_table_data_array, $temp_po_items_array);
        }
    }

    // echo var_dump($purchaseorderlineret_table_data_array);

    $conn->close();
}
