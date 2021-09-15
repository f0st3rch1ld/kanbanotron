<?php
if (array_key_exists('xhttp', $_REQUEST)) {
    // grabs kanban unique id from request
    $knbn_uid = $_REQUEST['knbn_uid'];

    // includes
    include '../db/request.php';
    include '../db/qb_db/items_request.php';
    include '../db/qb_db/purchaseorder_request.php';
} else {
    // includes
    include plugin_dir_path(__FILE__) . '../db/request.php';
    include plugin_dir_path(__FILE__) . '../db/qb_db/items_request.php';
    include plugin_dir_path(__FILE__) . '../db/qb_db/purchaseorder_request.php';
}

knbn_info_request($knbn_uid);

qbdb_item_request($knbn_vendor_part_number);
// qbdb_item_request reference

// $qbdb_ListID;
// $qbdb_TableName;
// $qbdb_BarCodeValue;

$knbn_on_order = FALSE;

?>

<!-- kanban search container -->
<div id="knbn_uid-form">
    <input type="text" id="knbn_uid" placeholder="Load a new kanban (Scan QR Code)" onchange="loadKanban(this.value)" />
</div>
<!-- /kanban search container -->

<h4>Kanban Information</h4>
<table id="kanban-table">
    <tbody>
        <?php if ($knbn_part_number) : ?>
            <tr>
                <th>Part Number</th>
                <td><?php echo $knbn_part_number; ?></td>
            </tr>
        <?php endif; ?>
        <?php if ($knbn_vendor) : ?><tr>
                <th>Vendor</th>
                <td><?php echo $knbn_vendor; ?></td>
            </tr>
        <?php endif; ?>
        <tr>
            <th>Vendor Part Number</th>
            <td><?php echo $knbn_vendor_part_number; ?></td>
        </tr>
        <?php if ($knbn_description) : ?>
            <tr>
                <th>Description</th>
                <td><?php echo $knbn_description; ?></td>
            </tr>
        <?php endif; ?>
        <?php if ($knbn_quantity) : ?>
            <tr>
                <th>Kanban Quantity (Blue / Red Bins)</th>
                <td><?php echo $knbn_quantity; ?></td>
            </tr>
        <?php endif; ?>
        <?php if ($knbn_package_quantity) : ?>
            <tr>
                <th>Package Quantity</th>
                <td><?php echo $knbn_package_quantity; ?></td>
            </tr>
        <?php endif; ?>
        <?php if ($knbn_reorder_quantity) : ?>
            <tr>
                <th>Reorder Quantity</th>
                <td><?php echo $knbn_reorder_quantity; ?></td>
            </tr>
        <?php endif; ?>
        <tr>
            <th>Lead Time</th>
            <td>
                <?php if ($knbn_lead_time > 1) {
                    echo $knbn_lead_time . " Days";
                } else {
                    echo $knbn_lead_time . "Day";
                } ?>
            </td>
        </tr>
        <?php if ($knbn_lead_time) : ?>
            <tr>
                <th>ETA</th>
                <td>
                    <?php
                    $date = date('m/d/Y');
                    echo date('m/d/Y', strtotime($date . ' +' . $knbn_lead_time . ' days'));
                    ?>
                </td>
            </tr>
        <?php endif; ?>
        <?php if ($knbn_location) : ?>
            <tr>
                <th>Location</th>
                <td><?php echo $knbn_location; ?></td>
            </tr>
        <?php endif; ?>
        <?php if ($knbn_notes) : ?>
            <tr>
                <th>Notes</th>
                <td><?php echo $knbn_notes; ?></td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<div class="knbn-order-form-container">
    <?php if ($knbn_on_order) : ?>
        <!-- On Order Warning Text -->
        <p id="warning-text">This item is already on a purchase order.</p>
    <?php endif; ?>

    <?php if (!$knbn_reorder_quantity) : ?>
        <p>This product does not appear to have a default reorder quantity set. Please set one by adjusting the field below. Keep in mind that the amount to reorder is dependant on the package quantity. (Ex. 1ea., 100-per/box, etc.)</p>
        <!-- Set Default Reorder Quantity Group -->
        <div class="group">
            <input type="number" name="default-reorder-quantity" id="default-reorder-quantity" placeholder="Set Default Reorder Quantity" />
            <button onclick="setDefaultReorderQuan('<?php echo $knbn_uid; ?>', document.getElementById('default-reorder-quantity').value)">Set</button>
        </div>
        <!-- /Set Default Reorder Quantity Group -->
    <?php endif; ?>

    <?php if ($knbn_external_yn == 'external' && $knbn_order_method == 'website' && !$knbn_external_url) : ?>
        <p>This kanban is marked to be ordered online, but doesn't seem to have a url link to the product. Please set one by copying the url into the input below.</p>
        <!-- Set External URL Group -->
        <div class="group">
            <input type="text" name="default-external-url" id="default-external-url" placeholder="Copy URL of product here." />
            <button onclick="setExternalURL('<?php echo $knbn_uid; ?>', document.getElementById('default-external-url').value)">Set</button>
        </div>
        <!-- /Set External URL Group -->
    <?php endif; ?>

    <?php if ($knbn_external_url) : ?>
        <a href="<?php echo $knbn_external_url; ?>" target="_blank">Click to order from <?php echo $knbn_vendor; ?> <i class="far fa-plus-square"></i></a>
    <?php elseif ($knbn_external_yn == 'external' && $knbn_order_method == 'generated-po' && $knbn_reorder_quantity) : ?>
        <div class="group">
            <!-- <a href="/kanbanotron/?on_order_ov=1">View On-Order List</a> -->
            <button onclick="addToPO('<?php echo $knbn_uid; ?>', document.getElementById('order-selection').value)">Add to PO <i class="far fa-plus-square"></i></button>
        </div>
    <?php elseif ($knbn_external_yn == 'internal') : ?>
        <div class="group">
            <!-- <a href="/kanbanotron/?on_order_ov=1">View On-Order List</a> -->
            <button onclick="">Send to Scheduler <i class="far fa-plus-square"></i></button>
        </div>
    <?php endif; ?>
</div>