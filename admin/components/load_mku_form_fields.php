<?php

$wp_knbn_pid;
$knbn_uid;
$update = FALSE;

if (array_key_exists('xhttp', $_REQUEST)) {
    // includes
    include '../../db/knbn_wp_connection.php';
    include '../../db/request.php';

    $wp_knbn_pid = $_GET['wpknbnpid'];
    $update = TRUE;

    $knbn_uid_request = "SELECT meta_value FROM wp_postmeta WHERE post_id='" . $wp_knbn_pid . "' AND meta_key='product_setup_knbn_uid'";
    $knbn_uid_request_result = $conn->query($knbn_uid_request);

    if ($knbn_uid_request_result->num_rows > 0) {
        while ($row = $knbn_uid_request_result->fetch_assoc()) {
            $knbn_uid = $row['meta_value'];
        }
    } else {
        echo 'There was an error trying to recieve this kanbans uid: ' . $conn->error;
    }

    $conn->close();

    // knbn_info_request reference

    // $knbn_external_yn;
    // $knbn_order_method;
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

    knbn_info_request($knbn_uid);
}

?>

<label>Vendor
    <input form="manual-knbn-update" type="text" name="vendor" id="vendor" <?php if ($update) : ?>value="<?php echo $knbn_vendor; ?>" <?php endif; ?> />
</label>
<label>ITD Part Number
    <input form="manual-knbn-update" type="text" name="itd_part_number" id="itd_part_number" <?php if ($update) : ?>value="<?php echo $knbn_part_number; ?>" <?php endif; ?> />
</label>
<label>Location
    <input form="manual-knbn-update" type="text" name="Location" id="Location" <?php if ($update) : ?>value="<?php echo $knbn_dept_location; ?>" <?php endif; ?> />
</label>
<label>Manufacturer's Part Number
    <input type="text" name="man_part_number" id="man_part_number" <?php if ($update) : ?>value="<?php echo $knbn_vendor_part_number; ?>" <?php endif; ?> />
</label>
<label>Description
    <input form="manual-knbn-update" type="text" name="description" id="description" <?php if ($update) : ?>value="<?php echo $knbn_description; ?>" <?php endif; ?> />
</label>
<label>Kanban Quantities
    <input form="manual-knbn-update" type="text" name="knbn_qty" id="knbn_qty" <?php if ($update) : ?>value="<?php echo $knbn_qty; ?>" <?php endif; ?> />
</label>
<label>Freight Policy
    <input form="manual-knbn-update" type="text" name="freight_policy" id="freight_policy" <?php if ($update) : ?>value="" <?php endif; ?> />
</label>
<label>Package Quantity
    <input form="manual-knbn-update" type="text" name="package_qty" id="package_qty" <?php if ($update) : ?>value="<?php echo $knbn_package_quantity; ?>" <?php endif; ?> />
</label>
<label>Minimum Reorder Quantity
    <input form="manual-knbn-update" type="number" name="min_reorder_qty" id="min_reorder_qty" <?php if ($update) : ?>value="<?php echo $knbn_reorder_quantity; ?>" <?php endif; ?> />
</label>
<label>Lead Time (In Days)
    <input form="manual-knbn-update" type="number" name="lead_time" id="lead_time" <?php if ($update) : ?>value="<?php echo $knbn_lead_time; ?>" <?php endif; ?> />
</label>