<?php

$new_PORet_TxnLineID;

function purchaseorderlineret_update($qbdb_items_request_array, $new_PO_TxnID)
{

    global $new_PORet_TxnLineID;

    $temp_TxnID_array = array();

    // Quickbooks database connection
    include 'qb_data_connection.php';

    // Selects data from purchaseorderlineret table for incremented values
    $purchaseorderlineret_table_query = "SELECT TxnLineID FROM purchaseorderlineret";
    $purchaseorderlineret_table_query_result = $conn->query($purchaseorderlineret_table_query);

    // Inserts selected data from purchaseorderlineret table into php arrays.
    if ($purchaseorderlineret_table_query_result->num_rows > 0) {
        while ($row = $purchaseorderlineret_table_query_result->fetch_assoc()) {
            array_push($temp_TxnID_array, $row['TxnLineID']);
        }
    }

    function generate_Ret_TxnID_check($x)
    {
        global $temp_TxnID_array;
        if (!in_array($x, $temp_TxnID_array)) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function generate_Ret_TxnID()
    {
        global $new_PORet_TxnLineID;
        $new_PORet_TxnLineID = 'PORET-GEN-' . rand(1000000000, 9999999999);
    }

    // Builds purchaseorder table insertion
    for ($i = 0; count($qbdb_items_request_array) > $i; $i++) {

        // Console Logs Content
        // foreach ($qbdb_items_request_array[$i] as $key => $value) {
        //     if (!$value) {
        //         echo $key . ' : NULL ; ';
        //     } else {
        //         echo $key . ' : ' . $value . ' ; ';
        //     }
        // }

        // Generates new random TxnID
        generate_Ret_TxnID();

        while (generate_Ret_TxnId_check($new_PORet_TxnLineID)) {
            generate_Ret_TxnID();
        }

        echo $new_PORet_TxnLineID;

        // This statement inserts all of our collected data into the purchaseorder table.
        $purchaseorderlineret_table_insertion = "INSERT INTO purchaseorderlineret (
            TxnLineID,
            ItemRef_ListID,
            ItemRef_FullName,
            Description,
            Quantity,
            Rate,
            Amount,
            ReceivedQuantity,
            isBilled,
            isManuallyClosed,
            PARENT_IDKEY,
            SeqNum
        )
        VALUES (
            '$new_PORet_TxnLineID',
            '" . $qbdb_items_request_array[$i]['Item_ListID'] . "',
            '" . $qbdb_items_request_array[$i]['Item_Name'] . "',
            '" . $qbdb_items_request_array[$i]['Item_Description'] . "',
            '" . $qbdb_items_request_array[$i]['Item_Reorder_Amount'] . "',
            '" . $qbdb_items_request_array[$i]['Item_Price'] . "',
            '" . intval($qbdb_items_request_array[$i]['Item_Price']) * intval($qbdb_items_request_array[$i]['Item_Reorder_Amount']) . "',
            0.000000,
            0,
            0,
            '$new_PO_TxnID',
            " . $i + 1 . "
        )";

        if ($conn->query($purchaseorderlineret_table_insertion) === TRUE) {
            echo 'New list items inserted into purchaseorderlineret table';
        } else {
            echo 'Error: ' . $purchaseorderlineret_table_insertion . ' ' . $conn->error;
        }
    }

    // Closes Quickbooks database connection
    $conn->close();
}
