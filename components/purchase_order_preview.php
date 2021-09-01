<?php

$active_po;
$order_data;

// these conditional includes are for contacting the kanbanotron database of active purchase orders
if (array_key_exists('xhttp', $_REQUEST)) {
    $active_po = $_GET['active_po'];
    include '../db/kanbanotron_connection.php';
} else {
    $active_po = $_COOKIE['working_purchase_order'];
    include plugin_dir_path(__FILE__) . '../db/kanbanotron_connection.php';
}

$po_preview_query = "SELECT order_data FROM purchase_orders WHERE order_id='" . $active_po . "'";
$po_preview_result = $conn->query($po_preview_query);

while ($row = $po_preview_result->fetch_assoc()) {
    $order_data = json_decode($row['order_data']);
}

$conn->close();

// these conditional includes are for contacting the wordpress database of kanbans
if (array_key_exists('xhttp', $_REQUEST)) :
    include '../db/knbn_wp_connection.php';
    include '../db/request.php';
else :
    include plugin_dir_path(__FILE__) . '../db/knbn_wp_connection.php';
    include plugin_dir_path(__FILE__) . '../db/request.php';
endif;

?>

<h4>Purchase Order Overview</h4>

<?php

// begin purchase order preview loop

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

// vendors array holds which vendors are included inside this loop.
$vendors = array();

if (!$order_data) {
    $order_data = array();
}

// This for loop generates the $vendors array
for ($i = 0; count($order_data) > $i; $i++) :

    knbn_info_request($order_data[$i]);

    if (!in_array($knbn_vendor, $vendors)) :
        global $vendors;
        array_push($vendors, $knbn_vendor);
    endif;

endfor;

sort($vendors);

// this foreach loop generates the purchase order containers, and sorts the ordered kanbans into them.
foreach ($vendors as $vendor) : ?>

    <!-- GeneratedPO -->
    <div class="purchase-order-container">
        <div class="po-title-container">
            <h5 class="vendor-name"><?php echo $vendor; ?></h5>
        </div>
        <table>
            <thead>
                <tr>
                    <th>PN</th>
                    <th>Vendor PN</th>
                    <th>QTY</th>
                    <th>Date Stamp</th>
                    <th>ETA</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php for ($i = 0; count($order_data) > $i; $i++) :
                    knbn_info_request($order_data[$i]);
                    if ($knbn_vendor == $vendor) : ?>

                        <!-- order_data item index <?php echo $i; ?> -->
                        <tr>
                            <td class="tooltip"><?php if ($knbn_part_number) : echo $knbn_part_number; else : echo '-'; endif; ?>
                                <p class="tooltiptext"><?php echo $knbn_description ?></p>
                            </td>
                            <td class="tooltip"><?php echo $knbn_vendor_part_number; ?>
                                <p class="tooltiptext"><?php echo $knbn_description ?></p>
                            </td>
                            <td><?php echo $knbn_reorder_quantity; ?></td>
                            <?php $date = date('m/d/Y'); ?>
                            <td><?php echo $date; ?></td>
                            <td><?php echo date('m/d/Y', strtotime($date . ' +' . $knbn_lead_time . ' days')); ?></td>
                            <td class="remove-product" onmouseup="removeFromPO('<?php echo $active_po; ?>', '<?php echo $i; ?>')"><i class="fas fa-trash-alt"></i></td>
                        </tr>
                        <!-- /order_data item index <?php echo $i; ?> -->

                <?php endif;
                endfor; ?>

            </tbody>
        </table>

        <!-- PO Control Container -->
        <div class="po-control-container">
            <!-- ind submit -->
            <button class="ind-po-submit" onmouseup="submitPurchaseOrder('<?php echo $active_po; ?>', '<?php echo $vendor; ?>')"><i class="fas fa-paper-plane"></i></button>

            <!-- ind delete -->
            <button class="ind-po-delete" onmouseup="removeAllFromPO('<?php echo $active_po; ?>', '<?php echo $vendor; ?>')"><i class="fas fa-trash-alt"></i></button>
        </div>
        <!-- /PO Control Container -->
    </div>
    <!-- /GeneratedPO -->

<?php endforeach; ?>

<input type="submit" name="knbn-all-po-submit" id="knbn-all-po-submit" value="Submit All Purchase Orders" form="" />

<?php $conn->close(); ?>