<?php

$knbn_external_yn;
$knbn_order_method;
$knbn_external_url;
$knbn_location;
$knbn_vendor;
$knbn_part_number;
$knbn_vendor_part_number;
$knbn_description;
$knbn_package_quantity;
$knbn_reorder_quantity;
$knbn_quantity;
$knbn_lead_time;
$knbn_notes;

function knbn_info_request($passed_knbn_uid)
{
    // Global variables that we will set later
    global $knbn_external_yn;
    global $knbn_order_method;
    global $knbn_external_url;
    global $knbn_location;
    global $knbn_vendor;
    global $knbn_part_number;
    global $knbn_vendor_part_number;
    global $knbn_description;
    global $knbn_package_quantity;
    global $knbn_reorder_quantity;
    global $knbn_quantity;
    global $knbn_lead_time;
    global $knbn_notes;

    // Array of meta keys. These keys are what determine how the program parses the wordpress database, and what variables values get assigned.
    $meta_key_array = array(
        'product_setup_product_type',
        'product_setup_order_method',
        'external_product_url',
        'kanban_information_location',
        'kanban_information_vendor',
        'kanban_information_part_number_group_part_number',
        'kanban_information_part_number_group_vendor_part_number',
        'kanban_information_description',
        'kanban_information_quantities_kanban_quantity',
        'kanban_information_quantities_package_quantity',
        'kanban_information_quantities_reorder_quantity',
        'kanban_information_lead_time',
        'kanban_notes'
    );

    // Wordpress database connection
    include 'knbn_wp_connection.php';

    // Retrieves Kanban Post ID from unique value located inside QR
    $knbn_post_id = "SELECT post_id FROM wp_postmeta WHERE meta_value='" . $passed_knbn_uid . "'";
    $knbn_post_id_result = $conn->query($knbn_post_id);

    // If retrieval process was succesful, assigns correct meta_values to variables to be used on main.php
    if ($knbn_post_id_result->num_rows > 0) {

        // Converts fetched result into a useable format
        $knbn_post_id_return = $knbn_post_id_result->fetch_array(MYSQLI_NUM);
        $knbn_post_id_val = $knbn_post_id_return[0];

        // Foreach loops through each item in meta_key_array, parses the database, then assigns the return value to the global variables above.
        foreach ($meta_key_array as $value) {
            $db_query = "SELECT meta_value FROM wp_postmeta WHERE post_id='" . $knbn_post_id_val . "' AND meta_key='" . $value . "'";
            $db_query_result = $conn->query($db_query);
            if ($db_query_result->num_rows > 0) {
                while ($row = $db_query_result->fetch_assoc()) {
                    switch ($value) {
                        case 'product_setup_product_type':
                            $knbn_external_yn = $row['meta_value'];
                            break;
                        case 'product_setup_order_method':
                            $knbn_order_method = $row['meta_value'];
                            break;
                        case 'external_product_url':
                            $knbn_external_url = $row['meta_value'];
                            break;
                        case 'kanban_information_location':
                            $knbn_location = $row['meta_value'];
                            break;
                        case 'kanban_information_vendor':
                            $knbn_vendor = $row['meta_value'];
                            break;
                        case 'kanban_information_part_number_group_part_number':
                            $knbn_part_number = $row['meta_value'];
                            break;
                        case 'kanban_information_part_number_group_vendor_part_number'
                        :
                        $knbn_vendor_part_number = $row['meta_value'];
                            break;
                        case 'kanban_information_description':
                            $knbn_description = $row['meta_value'];
                            break;
                        case 'kanban_information_quantities_kanban_quantity':
                            $knbn_quantity = $row['meta_value'];
                            break;
                        case 'kanban_information_quantities_package_quantity':
                            $knbn_package_quantity = $row['meta_value'];
                            break;
                        case 'kanban_information_quantities_reorder_quantity':
                            $knbn_reorder_quantity = $row['meta_value'];
                            break;
                        case 'kanban_information_lead_time':
                            $knbn_lead_time = $row['meta_value'];
                            break;
                        case 'kanban_notes':
                            $knbn_notes = $row['meta_value'];
                            break;
                        default:
                            echo 'Error: ' . $conn->error;
                    }
                }
            }
        }
    } else {
        echo 'No matching kanban found.';
    }

    $conn->close();
}
