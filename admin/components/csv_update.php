<?php

// CSV Update Page

$knbn_uid;

// Function for generating knbn_uid cannot be held inside of next for loop
function generate_knbn_uid()
{
    global $knbn_uid;
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $generated_number = '';
    for ($i = 0; $i < 20; $i++) {
        $generated_number .= $characters[rand(0, strlen($characters) - 1)];
    }
    $knbn_uid = $generated_number;
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-admin/includes/file.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-admin/includes/media.php');

$attachment_id = media_handle_upload('csv_file', 0, array(), array(
    'test_form' => false,
    'mimes'     => array(
        'csv'   => 'text/csv',
    ),
));

$csv_loc =  WP_CONTENT_DIR . '/uploads/' . htmlspecialchars(basename($_FILES["csv_file"]["name"]));

if (file_exists($csv_loc)) {
    echo 'File Uploaded<br /><p>-----------------------------------<p><br />';

    $file = fopen($csv_loc, "r");

    // converts csv data into a php array
    if (($handle = $file) !== FALSE) {
        $all_data = array();

        while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {

            $all_data[] = array(
                'vendor' => $data[0],
                'part_number' => $data[3],
                'man_part_number' => $data[1],
                'location' => $data[2],
                'description' => $data[4],
                'knbn_qty' => $data[7],
                'package_qty' => $data[5],
                'lead_time' => $data[6],
                'kanban_size' => $data[10],
            );
        }
    }

    fclose($file);

    if (unlink($csv_loc)) {
        echo '<strong>Syncing CSV Data...</strong><br />';
    } else {
        echo 'There has been an error with the upload, please try again later.<br />';
    }

    include plugin_dir_path(__FILE__) . '../../db/knbn_wp_connection.php';

    for ($i = 0; count($all_data) > $i; $i++) {

        global $knbn_uid;

        // skip first row
        if ($i != 0) {

            // cross referencing existing posts to check and see if one already exists using either the part number or vendor part number so we don't make any extra posts we don't need.
            $knbn_post_id = 0;

            $knbn_vendor_part_number_query = "SELECT post_id FROM wp_postmeta WHERE meta_value='" . $all_data[$i]['man_part_number'] . "'";

            $knbn_vendor_part_number_result = $conn->query($knbn_vendor_part_number_query);

            if ($knbn_vendor_part_number_result->num_rows > 0) {
                while ($row = $knbn_vendor_part_number_result->fetch_assoc()) {
                    $knbn_post_id = $row['post_id'];
                }
            }

            // Generates a new 20 char random alphanumeric Unique Kanban Identifier
            if ($knbn_post_id == 0) {
                generate_knbn_uid();
            } else {
                $knbn_uid_query = "SELECT meta_value FROM wp_postmeta WHERE post_id='" . $knbn_post_id . "' AND meta_key='product_setup_knbn_uid'";
                $knbn_uid_query_result = $conn->query($knbn_uid_query);
                if ($knbn_uid_query_result->num_rows > 0) {
                    while ($row = $knbn_uid_query_result->fetch_assoc()) {
                        $knbn_uid = $row['meta_value'];
                    }
                } else {
                    generate_knbn_uid();
                }
            }

            // Product Type Determination
            $product_type_determination;

            $internal_vendors_array = array(
                'IWS',
                'ITD',
                'RBO',
                'FFP',
                'Razorback Offroad',
                'Fish Fighter Products',
                'Fish Fighter'
            );

            if (in_array($all_data[$i]['vendor'], $internal_vendors_array)) {
                $product_type_determination = 'internal';
            } else {
                $product_type_determination = 'external';
            }

            // Order Method Determination
            $order_method_determination = '';
            $online_external_vendors_array = array(
                'Amazon',
                'McMaster',
                'Uline',
                'Staples'
            );
            if ($product_type_determination == 'external' && in_array($all_data[$i]['vendor'], $online_external_vendors_array)) {
                $order_method_determination = 'website';
            } elseif ($product_type_determination == 'external') {
                $order_method_determination = 'generated-po';
            }

            // Reorder Quantity Determination
            $reorder_qty_determination = NULL;

            if ($all_data[$i]['knbn_qty']) {
                $exploded_knbn_qty = explode('/', $all_data[$i]['knbn_qty']);
                $reorder_qty_determination = $exploded_knbn_qty[0];
            }

            // Lead Time Conversion
            $converted_lead_time;
            $lowercase_lead_time = strtolower($all_data[$i]['lead_time']);

            if (strpos($lowercase_lead_time, 'weeks') != false || strpos($lowercase_lead_time, 'week') != false || strpos($lowercase_lead_time, 'days') != false || strpos($lowercase_lead_time, 'day') != false || strpos($lowercase_lead_time, '-') == false || strpos($lowercase_lead_time, 'next day') != false || strpos($lowercase_lead_time, 'next day delivery') != false) {
                if (strpos($lowercase_lead_time, 'weeks') != false || strpos($lowercase_lead_time, 'week') != false) {
                    $converted_lead_time = intval($lowercase_lead_time) * 7;
                } elseif (strpos($lowercase_lead_time, 'next day') != false || strpos($lowercase_lead_time, 'next day delivery') != false) {
                    $converted_lead_time = 1;
                } else {
                    $converted_lead_time = intval($lowercase_lead_time);
                }
            } else {
                $converted_lead_time = NULL;
            }

            $my_post = array(
                'post_author' => get_current_user_id(),
                'ID' => $knbn_post_id,
                'post_title' => ucwords(str_replace(' ', '-', $all_data[$i]['vendor']) . '-' . $all_data[$i]['part_number'] . str_replace(' ', '-', $all_data[$i]['description'])),
                'post_content' => '',
                'post_status' => 'publish',
                'post_type' => 'knbn_action',
                'meta_input' => array(
                    'product_setup_product_type' => $product_type_determination,
                    'product_setup_order_method' => $order_method_determination,
                    'product_setup_knbn_uid' => $knbn_uid,
                    'kanban_information_kanban_size' => $all_data[$i]['kanban_size'],
                    'kanban_information_location' => $all_data[$i]['location'],
                    'kanban_information_vendor' => $all_data[$i]['vendor'],
                    'kanban_information_part_number_group_part_number' => $all_data[$i]['part_number'],
                    'kanban_information_part_number_group_vendor_part_number' => $all_data[$i]['man_part_number'],
                    'kanban_information_description' => $all_data[$i]['description'],
                    'kanban_information_quantities_kanban_quantity' => $all_data[$i]['knbn_qty'],
                    'kanban_information_quantities_package_quantity' => $all_data[$i]['package_qty'],
                    'kanban_information_quantities_reorder_quantity' => $reorder_qty_determination,
                    'kanban_information_lead_time' =>  $converted_lead_time
                )
            );

            $post_id = wp_insert_post($my_post, true);

            echo '<div style="align-items:flex-start; margin:0px 20px;">';

            if (is_wp_error($post_id)) {
                echo '<p style="color:red; margin:0px;">';
                $errors = $post_id->get_error_messages();
                foreach ($errors as $error) {
                    echo "- " . $error . "<br />";
                }
                echo '</p>';
            } else {
                if ($knbn_post_id != 0) {
                    echo '<p style="color:#ffc800; margin:0px;">' . $i . '. ' . $all_data[$i]['vendor'] . "-" . $all_data[$i]['man_part_number'] . "-" . $all_data[$i]['description'] . ": Kanban successfully updated!</p><br />";
                } else {
                    echo '<p style="color:green; margin:0px;">' . $i . '. ' . $all_data[$i]['vendor'] . "-" . $all_data[$i]['man_part_number'] . "-" . $all_data[$i]['description'] . ": Kanban successfully added!</p><br />";
                }
            }

            echo '</div>';
        }
    }

    $conn->close();
} else {
    echo 'There was an error uploading your file.';
}
