<?php 

$knbn_uid = $_GET['knbn_uid'];
$ext_url = $_GET['ext_url'];

$retrieved_id;

// Connects to WP Database
include '../knbn_wp_connection.php';

// Retrieves Kanban Post ID from unique value located inside QR
$knbn_post_id = "SELECT post_id FROM wp_postmeta WHERE meta_value='" . $knbn_uid . "'";
$knbn_post_id_result = $conn->query($knbn_post_id);

while ($row = $knbn_post_id_result->fetch_assoc()) {
    $retrieved_id = $row['post_id'];
}


$knbn_set_reorder_quan = "UPDATE wp_postmeta SET meta_value='$ext_url' WHERE meta_key='external_product_url' AND post_id=$retrieved_id";

if ($conn->query($knbn_set_reorder_quan) === TRUE) {
    echo "External URL updated";
} else {
    echo "Error updating external URL: " . $conn->error;
}

// Closes connection to WP Database
$conn->close();