<?php

/**
 * Updated purchaseorder table rows need operation column set to 'add' in order to be pushed back to the QBD database.
 * 
 * Needed tables & columns from QB Database to create purchase orders
 */

 // Some variables we need to set in order to insert the new data into the database

$temp_TxnID_array;

$new_PO_TxnID;
$new_PO_TxnNumber;
$new_PO_RefNumber;

function purchaseorder_update($qbdb_items_request_array, $vendor, $order_total)
{

    global $temp_TxnID_array;

    global $new_PO_TxnID;
    global $new_PO_TxnNumber;
    global $new_PO_RefNumber;

    $temp_TxnID_array = array();
    $temp_TxnNumber_array = array();
    $temp_RefNumber_array = array();

    // Quickbooks database connection
    include 'qb_data_connection.php';

    // Selects data from purchaseorder table for incremented values
    $purchaseorder_table_query = "SELECT TxnID, TxnNumber, RefNumber FROM purchaseorder";
    $purchaseorder_table_query_result = $conn->query($purchaseorder_table_query);

    // Inserts selected data from purchaseorder table into php arrays.
    if ($purchaseorder_table_query_result->num_rows > 0) {
        while ($row = $purchaseorder_table_query_result->fetch_assoc()) {
            array_push($temp_TxnID_array, $row['TxnID']);
            array_push($temp_TxnNumber_array, $row['TxnNumber']);
            array_push($temp_RefNumber_array, $row['RefNumber']);
        }
    }

    // Generates new random TxnID
    function generate_TxnID_check($x)
    {
        global $temp_TxnID_array;
        if (!in_array($x, $temp_TxnID_array)) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function generate_TxnID()
    {
        global $new_PO_TxnID;
        $new_PO_TxnID = 'PO-GEN-' . rand(1000000000, 9999999999);
    }

    generate_TxnID();

    while (generate_TxnId_check($new_PO_TxnID)) {
        generate_TxnID();
    }

    // Generates new TxnNumber
    $new_PO_TxnNumber = max($temp_TxnNumber_array) + 1;

    // Generates next RefNumber
    $new_PO_RefNumber = max($temp_RefNumber_array) + 1;

    // This statement inserts all of our collected data into the purchaseorder table.
    $purchaseorder_table_insertion = "INSERT INTO purchaseorder (
        TxnID,
        TxnNumber,
        VendorRef_ListID,
        VendorRef_FullName,
        TemplateRef_ListID,
        TemplateRef_Fullname,
        RefNumber,
        VendorAddress_Addr1,
        VendorAddress_Addr2,
        VendorAddress_Addr3,
        VendorAddress_Addr4,
        VendorAddress_Addr5,
        VendorAddress_City,
        VendorAddress_State,
        VendorAddress_PostalCode,
        VendorAddress_Country,
        ShipAddress_Addr1,
        ShipAddress_Addr2,
        ShipAddress_Addr3,
        ShipAddress_Addr4,
        ShipAddress_Addr5,
        ShipAddress_City,
        ShipAddress_State,
        ShipAddress_PostalCode,
        ShipAddress_Country,
        TermsRef_ListID,
        TermsRef_FullName,
        TotalAmount,
        IsToBePrinted,
        IsToBeEmailed,
        IsManuallyClosed,
        IsFullyReceived,
        Operation
    )
    VALUES (
        '$new_PO_TxnID',
        $new_PO_TxnNumber,
        '" . $qbdb_items_request_array[0]['Vendor_ListID'] . "',
        '$vendor',
        '8000000F-1626707508',
        'Custom Purchase Order',
        $new_PO_RefNumber,
        '" . $qbdb_items_request_array[0]['VendorAddress_Addr1'] . "',
        '" . $qbdb_items_request_array[0]['VendorAddress_Addr2'] . "',
        '" . $qbdb_items_request_array[0]['VendorAddress_Addr3'] . "',
        '" . $qbdb_items_request_array[0]['VendorAddress_Addr4'] . "',
        '" . $qbdb_items_request_array[0]['VendorAddress_Addr5'] . "',
        '" . $qbdb_items_request_array[0]['VendorAddress_City'] . "',
        '" . $qbdb_items_request_array[0]['VendorAddress_State'] . "',
        '" . $qbdb_items_request_array[0]['VendorAddress_PostalCode'] . "',
        '" . $qbdb_items_request_array[0]['VendorAddress_Country'] . "',
        '" . $qbdb_items_request_array[0]['ShipAddress_Addr1'] . "',
        '" . $qbdb_items_request_array[0]['ShipAddress_Addr2'] . "',
        '" . $qbdb_items_request_array[0]['ShipAddress_Addr3'] . "',
        '" . $qbdb_items_request_array[0]['ShipAddress_Addr4'] . "',
        '" . $qbdb_items_request_array[0]['ShipAddress_Addr5'] . "',
        '" . $qbdb_items_request_array[0]['ShipAddress_City'] . "',
        '" . $qbdb_items_request_array[0]['ShipAddress_State'] . "',
        '" . $qbdb_items_request_array[0]['ShipAddress_PostalCode'] . "',
        '" . $qbdb_items_request_array[0]['ShipAddress_Country'] . "',
        '" . $qbdb_items_request_array[0]['TermsRef_ListID'] . "',
        '" . $qbdb_items_request_array[0]['TermsRef_FullName'] . "',
        $order_total,
        1,
        0,
        0,
        0,
        'add'
    )";

    if ($conn->query($purchaseorder_table_insertion) === TRUE) {
        echo 'New purchase order inserted into purchaseorder table';
    } else {
        echo 'Error: ' . $purchaseorder_table_insertion . ' ' . $conn->error;
    }

    // Closes Quickbooks database connection
    $conn->close();
}
