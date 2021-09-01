<?php 

$knbn_uid = $_GET['knbn_uid'];
$quan = $_GET['quan'];

$retrieved_id;

// Connects to WP Database
include '../knbn_wp_connection.php';

// Retrieves Kanban Post ID from unique value located inside QR
$knbn_post_id = "SELECT post_id FROM wp_postmeta WHERE meta_value='" . $knbn_uid . "'";
$knbn_post_id_result = $conn->query($knbn_post_id);

if ($knbn_post_id_result->num_rows > 0) {
    while ($row = $knbn_post_id_result->fetch_assoc()) {
        $retrieved_id = $row['post_id'];
    }
} else {
    echo 'No matching record found - update_default_reorder_quan.php';
}



$knbn_set_reorder_quan = "UPDATE wp_postmeta SET meta_value=$quan WHERE meta_key='kanban_information_quantities_reorder_quantity' AND post_id='$retrieved_id'";

if ($conn->query($knbn_set_reorder_quan) === TRUE) {
    echo "Default quantity updated";
} else {
    echo "Error setting default quantity: " . $conn->error;
}

// Closes connection to WP Database
$conn->close();