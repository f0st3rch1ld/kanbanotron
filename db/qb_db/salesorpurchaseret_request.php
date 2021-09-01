<?php

$qbdb_item_price;
$qbdb_item_description;

function qbdb_salesorpurchaseret_request($parent_idkey)
{

    echo 'Trying to find a record that matches this idkey inside salesorpurchaseret table: ' . $parent_idkey;

    // Quickbooks database connection
    include 'qb_data_connection.php';

    $qbdb_data_request = "SELECT Price, Description FROM salesorpurchaseret WHERE PARENT_IDKEY='" . $parent_idkey . "'";
    $qbdb_data_result = $conn->query($qbdb_data_request);

    if ($qbdb_data_result->num_rows > 0) {
        while ($row = $qbdb_data_result->fetch_assoc()) {
            // globals - so we can set their values
            global $qbdb_item_price;
            global $qbdb_item_description;
            $qbdb_item_price = $row['Price'];
            $qbdb_item_description = $row['Description'];
            echo $qbdb_item_price;
            echo $qbdb_item_description;
        }
    } else {
        echo "No matching records were found inside the salesorpurchaseret table.";
    }

    // Closes Quickbooks database connection
    $conn->close();
}