<?php

if (array_key_exists('xhttp', $_REQUEST)) {
    // includes
    include '../db/qb_db/purchaseorder_request.php';
} else {
    // includes
    include plugin_dir_path(__FILE__) . '../db/qb_db/purchaseorder_request.php';
}

retrieve_po_data();

// purchaseorder_request referance

// --$purchaseorder_table_data_array;
// ----TxnID
// ----TimeCreated
// ----VendorRef_FullName
// ----Memo
// ----IsFullyReceived

// --$purchaseorderlineret_table_data_array;
// ----ItemRef_FullName
// ----Description
// ----Quantity
// ----PARENT_IDKEY

// order_txnid_array array holds which vendors have already been processed inside this loop
$order_txnid_array = array();

// Loops through all purchaseorder data, and groups / pushes vendors and vendor ids to array
for ($i = 0; count($purchaseorder_table_data_array) > $i; $i++) {
    if (!array_key_exists($purchaseorder_table_data_array[$i]['VendorRef_FullName'], $order_txnid_array)) {
        $vendorName = $purchaseorder_table_data_array[$i]['VendorRef_FullName'];
        $order_txnid_array[$vendorName] = array($purchaseorder_table_data_array[$i]['TxnID']);
    } else {
        array_push($order_txnid_array[$purchaseorder_table_data_array[$i]['VendorRef_FullName']], $purchaseorder_table_data_array[$i]['TxnID']);
    }
}

?>

<?php foreach ($order_txnid_array as $key => $value) : ?>
    <!-- Generated PO Table -->
    <div class="purchase-order-container">
        <div class="po-title-container">
            <h5 class="vendor-name">
                <?php echo $key; ?>
            </h5>
        </div>
        <table class="tablesorter">
            <thead>
                <tr>
                    <th>PN</th>
                    <th>Description</th>
                    <th>QTY</th>
                    <th>Date Ordered</th>
                    <th>PO</th>
                </tr>
            </thead>
            <tbody>
                <?php for ($i = 0; count($value) > $i; $i++) : ?>
                    <?php for ($y = 0; count($purchaseorderlineret_table_data_array) > $y; $y++) : ?>
                        <?php if ($purchaseorderlineret_table_data_array[$y]['PARENT_IDKEY'] == $value[$i]) : ?>
                            <tr>
                                <td><?php echo $purchaseorderlineret_table_data_array[$y]['ItemRef_FullName']; ?></td>
                                <td><?php echo $purchaseorderlineret_table_data_array[$y]['Description']; ?></td>
                                <td><?php echo number_format($purchaseorderlineret_table_data_array[$y]['Quantity'], 0); ?></td>

                                <?php
                                for ($x = 0; count($purchaseorder_table_data_array) > $x; $x++) {
                                    if ($purchaseorder_table_data_array[$x]['TxnID'] == $purchaseorderlineret_table_data_array[$y]['PARENT_IDKEY']) : ?>
                                        <td><?php echo $purchaseorder_table_data_array[$x]['TimeCreated']; ?></td>
                                        <td><?php echo $purchaseorder_table_data_array[$x]['Memo']; ?></td>
                                <?php endif;
                                }
                                ?>
                            </tr>
                        <?php endif; ?>
                    <?php endfor; ?>
                <?php endfor; ?>
            </tbody>
        </table>
    </div>
    <!-- /Generated PO Table -->
<?php endforeach; ?>