<?php

$active_po = $_GET['active_po'];
$vendor = $_GET['vndr'];

$order_data;

$order_data_to_send = array();


// Retreives order data from the kanbanotron database
include '../db/kanbanotron_connection.php';

$order_data_query = "SELECT order_data FROM Purchase_orders WHERE order_id='" . $active_po . "'";
$order_data_result = $conn->query($order_data_query);

while ($row = $order_data_result->fetch_assoc()) {
    $order_data = json_decode($row['order_data']);
}

// closes kanbanotron_connection
$conn->close();

include '../db/request.php';

// request reference

// $knbn_external_url;
// $knbn_dept_location;
// $knbn_dept_cell;
// $knbn_vendor;
// $knbn_part_number;
// $knbn_vendor_part_number;
// $knbn_description;
// $knbn_package_quantity;
// $knbn_reorder_quantity;
// $knbn_blue_bin_quantity;
// $knbn_red_bin_quantity;
// $knbn_lead_time;
// $knbn_notes;

// Queries wordpress database to check to see what vendor specific product belongs to.
function vendor_check($x)
{
    global $order_data_to_send;
    global $knbn_vendor;
    global $knbn_vendor_part_number;
    global $knbn_reorder_quantity;
    global $vendor;

    knbn_info_request($x);

    if ($knbn_vendor == $vendor) {
        array_push($order_data_to_send, array($knbn_vendor_part_number, $knbn_reorder_quantity));
    }
}

// Loops through all items in order data array pulled from database, and runs vendor_check function on them.
for ($i = 0; count($order_data) > $i; $i++) {
    vendor_check($order_data[$i]);
}

include '../db/qb_db/items_request.php';
// items_request reference

// $qbdb_ListID;
// $qbdb_TableName;
// $qbdb_BarCodeValue;

include '../db/qb_db/vendor_request.php';
// vendor_request reference

// $qbdb_vendor_ListID;
// $qbdb_vendor_isActive;
// $qbdb_vendor_CompanyName;
// $qbdb_vendor_FirstName;
// $qbdb_vendor_MiddleName;
// $qbdb_vendor_LastName;
// $qbdb_vendor_VendorAddress_Addr1;
// $qbdb_vendor_VendorAddress_Addr2;
// $qbdb_vendor_VendorAddress_Addr3;
// $qbdb_vendor_VendorAddress_Addr4;
// $qbdb_vendor_VendorAddress_Addr5;
// $qbdb_vendor_VendorAddress_City;
// $qbdb_vendor_VendorAddress_State;
// $qbdb_vendor_VendorAddress_PostalCode;
// $qbdb_vendor_VendorAddress_Country;
// $qbdb_vendor_VendorAddress_Note;
// $qbdb_vendor_ShipAddress_Addr1;
// $qbdb_vendor_ShipAddress_Addr2;
// $qbdb_vendor_ShipAddress_Addr3;
// $qbdb_vendor_ShipAddress_Addr4;
// $qbdb_vendor_ShipAddress_Addr5;
// $qbdb_vendor_ShipAddress_City;
// $qbdb_vendor_ShipAddress_State;
// $qbdb_vendor_ShipAddress_PostalCode;
// $qbdb_vendor_ShipAddress_Country;
// $qbdb_vendor_ShipAddress_Note;
// $qbdb_vendor_TermsRef_ListID;
// $qbdb_vendor_TermsRef_FullName;

include '../db/qb_db/salesorpurchaseret_request.php';
// salesorpurchaseret_request reference

// $qbdb_item_price
// $qbdb_item_description

$qbdb_items_request_array = array();

for ($i = 0; count($order_data_to_send) > $i; $i++) {
    qbdb_item_request($order_data_to_send[$i][0]);
    qbdb_vendor_request($vendor);
    qbdb_salesorpurchaseret_request($qbdb_ListID);

    $temp_array = array(
        'Item_ListID' => $qbdb_ListID,
        'Item_Price' => $qbdb_item_price,
        'Item_Reorder_Amount' => $order_data_to_send[$i][1],
        'Item_Name' => $order_data_to_send[$i][0],
        'Item_Description' => $qbdb_item_description,
        'Table_Name' => $qbdb_TableName,
        'BarCodeValue' => $qbdb_BarCodeValue,
        'Vendor_ListID' => $qbdb_vendor_ListID,
        'Vendor' => $vendor,
        'vendor_isActive' => $qbdb_vendor_isActive,
        'vendor_CompanyName' => $qbdb_vendor_CompanyName,
        'vendor_FirstName' => $qbdb_vendor_FirstName,
        'vendor_MiddleName' => $qbdb_vendor_MiddleName,
        'vendor_LastName' => $qbdb_vendor_LastName,
        'VendorAddress_Addr1' => $qbdb_vendor_VendorAddress_Addr1,
        'VendorAddress_Addr2' => $qbdb_vendor_VendorAddress_Addr2,
        'VendorAddress_Addr3' => $qbdb_vendor_VendorAddress_Addr3,
        'VendorAddress_Addr4' => $qbdb_vendor_VendorAddress_Addr4,
        'VendorAddress_Addr5' => $qbdb_vendor_VendorAddress_Addr5,
        'VendorAddress_City' => $qbdb_vendor_VendorAddress_City,
        'VendorAddress_State' => $qbdb_vendor_VendorAddress_State,
        'VendorAddress_PostalCode' => $qbdb_vendor_VendorAddress_PostalCode,
        'VendorAddress_Country' => $qbdb_vendor_VendorAddress_Country,
        'VendorAddress_Note' => $qbdb_vendor_VendorAddress_Note,
        'ShipAddress_Addr1' => $qbdb_vendor_ShipAddress_Addr1,
        'ShipAddress_Addr2' => $qbdb_vendor_ShipAddress_Addr2,
        'ShipAddress_Addr3' => $qbdb_vendor_ShipAddress_Addr3,
        'ShipAddress_Addr4' => $qbdb_vendor_ShipAddress_Addr4,
        'ShipAddress_Addr5' => $qbdb_vendor_ShipAddress_Addr5,
        'ShipAddress_City' => $qbdb_vendor_ShipAddress_City,
        'ShipAddress_State' => $qbdb_vendor_ShipAddress_State,
        'ShipAddress_PostalCode' => $qbdb_vendor_ShipAddress_PostalCode,
        'ShipAddress_Country' => $qbdb_vendor_ShipAddress_Country,
        'ShipAddress_Note' => $qbdb_vendor_ShipAddress_Note,
        'TermsRef_ListID' => $qbdb_vendor_TermsRef_ListID,
        'TermsRef_FullName' => $qbdb_vendor_TermsRef_FullName
    );

    array_push($qbdb_items_request_array, $temp_array);
}

// Calculates total order price
include '../db/qb_db/salesorder_price_calculation.php';
// Price Calculation Reference

// $order_total;

calculate_order_total($qbdb_items_request_array);

// Sends data to purchaseorder table
include '../db/qb_db/purchaseorder_update.php';
// PO Data Reference

// $new_PO_TxnID;
// $new_PO_TxnNumber;
// $new_PO_RefNumber;

purchaseorder_update($qbdb_items_request_array, $vendor, $order_total);

// Sends data to purchaseorderlineret table
include '../db/qb_db/purchaseorderlineret_update.php';

purchaseorderlineret_update($qbdb_items_request_array, $new_PO_TxnID);



//echo var_dump($qbdb_items_request_array);

/**
 * NOTE: May need to also reference itemservice or itemoninventory tables depending on how we use the items table. We would probably pull the same columns out of those tables as well.. unsure where all the item descriptions come from? straight from Quickbooks?
 */
