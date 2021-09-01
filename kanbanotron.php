 <?php
    /**
     * Plugin Name: Kanbanotron
     * Description: A plugin specifically designed for use by Inventive-Group, that creates a fully automated system of re-ordering kanbans.
     * Version: 1.0
     * Requires at least: 5.7
     * Requires PHP: 8.0
     * Author: Andrew Foster
     */

    define('WPFP_PATH', plugin_dir_path(__FILE__));

    // Registers a new custom post type for Kanbans.
    function knbn_custom_post_type()
    {
        register_post_type('knbn_action', array(
            'labels' => array(
                'name' => __('Kanbans', 'textdomain'),
                'singular_name' => __('Kanban', 'textdomain'),
                'add_new' => __('Create New', 'textdomain'),
                'add_new_item' => __('Create New Kanban', 'textdomain'),
                'edit_item' => __('Edit Kanban', 'textdomain'),
                'new_item' => __('New Kanban', 'textdomain'),
                'view_item' => __('View Kanban', 'textdomain'),
                'view_items' => __('View Kanbans', 'textdomain'),
                'search_items' => __('Search Kanbans', 'textdomain'),
                'not_found' => __('No Kanbans Found', 'textdomain'),
                'not_found_in_trash' => __('No Kanbans Found in Trash', 'textdomain'),
                'parent_item_colon' => __('Parent Kanban', 'textdomain'),
                'all_items' => __('All Kanbans', 'textdomain'),
                'archives' => __('Kanban Archives', 'textdomain'),
                'attributes' => __('Kanban Attributes', 'textdomain'),
                'insert_into_item' => __('Insert Into Kanban', 'textdomain'),
                'uploaded_to_this_item' => __('Uploaded to this Kanban', 'textdomain'),
                'filter_items_list' => __('Filter Kanbans List', 'textdomain'),
                'item_published' => __('Kanban Published', 'textdomain'),
                'item_published_privately' => __('Kanban Published Privately', 'textdomain'),
                'item_reverted_to_draft' => __('Kanban Reverted to draft', 'textdomain'),
                'item_scheduled' => __('Kanban Scheduled', 'textdomain'),
                'item_updated' => __('Kanban Updated', 'textdomain'),
            ),
            'public' => true,
            'has_archive' => true,
        ));
    }
    add_action('init', 'knbn_custom_post_type');

    // adds extra columns to kanbans post type

    // Custom Columns
    add_filter('manage_knbn_action_posts_columns', function ($columns) {
        return array_merge($columns, [
            'knbn_uid' => __('Kanban Unique ID', 'textdomain'),
            'vendor' => __('Vendor', 'textdomain'),
            'part_number' => __('Part Number', 'textdomain'),
            'vendor_part_number' => __('Vendor Part Number', 'textdomain'),
            'product_type' => __('Product Type', 'textdomain'),
            // 'download_kanban_labels' => __('Download Kanban Labels', 'textdomain')
        ]);
    });

    add_action('manage_knbn_action_posts_custom_column', function ($column_key, $post_id) {
        if ($column_key == 'vendor') {
            $vendor = get_post_meta($post_id, 'kanban_information_vendor', true);
            echo '<span>';
            if ($vendor) {
                echo $vendor;
            } else {
                echo 'No Vendor';
            }
            echo '</span>';
        } elseif ($column_key == 'product_type') {
            $product_type = get_post_meta($post_id, 'product_setup_product_type', true);
            echo '<span>';
            if ($product_type) {
                echo $product_type;
            } else {
                echo 'Error Getting Product Type';
            }
            echo '</span>';
        } elseif ($column_key == 'knbn_uid') {
            $knbn_uid = get_post_meta($post_id, 'product_setup_knbn_uid', true);
            echo '<span>';
            if ($knbn_uid) {
                echo $knbn_uid;
            } else {
                echo 'Error Getting Kanban Unique ID';
            }
            echo '</span>';
        } elseif ($column_key == 'part_number') {
            $part_number = get_post_meta($post_id, 'kanban_information_part_number_group_part_number', true);
            if ($part_number) {
                echo $part_number;
            } else {
                echo 'No Part Number';
            }
        } elseif ($column_key == 'vendor_part_number') {
            $vendor_part_number = get_post_meta($post_id, 'kanban_information_part_number_group_vendor_part_number', true);
            if ($vendor_part_number) {
                echo $vendor_part_number;
            } else {
                echo 'No Vendor Part Number';
            }
        }
        // elseif ($column_key == 'download_kanban_labels') {
        //     echo '<a href="/wp-admin/edit.php?post_type=knbn_action&page=download_kanban_labels&knbn_uids=' . get_post_meta($post_id, 'product_setup_knbn_uid', true) . '" target="blank">Download Kanban Labels</a>';
        // }
    }, 10, 2);

    // // Adds an option for bulk downloading kanban labels
    // add_filter('bulk_actions-edit-knbn_action', function($bulk_actions) {
    //     $bulk_actions['bulk_download_kanban_labels'] = __('Download Selected Kanban Labels', 'txtdomain');
    //     return $bulk_actions;
    // });

    // // Bulk Download Functionality
    // add_filter('handle_bulk_actions-edit-knbn_action', function($redirect_url, $action, $post_ids) {
    //     if ($action == 'bulk_download_kanban_labels') {
    //         // empty array to store uid's we need to download
    //         $knbn_uid_to_dwnld = array();

    //         // gotta include the bulk downloader
    //         include 'admin/components/bulk_download_kanban_labels.php';

    //         // lets add all the uids to the previous array
    //         foreach ($post_ids as $post_id) {
    //             $bulk_knbn_uid = get_post_meta($post_id, 'product_setup_knbn_uid', true);
    //             array_push($knbn_uid_to_dwnld, $bulk_knbn_uid);
    //         }

    //         // now that we have all the uids, time to generate/download some lábels
    //         blk_dwnld_lbls($knbn_uid_to_dwnld);

    //         $redirect_url = add_query_arg('bulk_download_kanban_labels', count($post_ids), $redirect_url);
    //     }
    //     return $redirect_url;
    // }, 10, 3);

    // // Gotta tell people that the bulk action has completed
    // add_action('admin_notices', function() {
    //     if (!empty($_REQUEST['bulk_download_kanban_labels'])) {
    //         $num_downloaded = (int) $_REQUEST['bulk_download_kanban_labels'];
    //         printf('<div id="message" class="updated notice is-dismissable"><p>' . __('Generated and Downloaded %d Kanban Labels.', 'txtdomain') . '</p></div>', $num_downloaded);
    //     }
    // });

    // Adds an option page for importing kanbans

    // create custom plugin settings menu
    add_action('admin_menu', 'kanbanotron_create_menu');

    // Hides download kanbans page from backend of wordpress
    add_action('admin_head', function () {
        remove_submenu_page('edit.php?post_type=knbn_action', 'download_kanban_labels');
    });

    function kanbanotron_create_menu()
    {
        //create new sub menu
        add_submenu_page('edit.php?post_type=knbn_action', 'Update Kanbans', 'Update Kanbans', 'administrator', 'update_kanbans', 'kanbanotron_import_kanbans_page');

        //create new sub menu that will be hidden for updating csv's
        add_submenu_page('edit.php?post_type=knbn_action', 'download_kanban_labels', 'download_kanban_labels', 'administrator', 'download_kanban_labels', 'kanbanotron_import_download_kanban_labels_page');
    }

    function kanbanotron_import_kanbans_page()
    {
        include 'admin/update_kanbans.php';
    }

    function kanbanotron_import_download_kanban_labels_page()
    {
        include 'admin/components/download_kanban_labels.php';
    }

    add_filter('mime_types', 'wpse_mime_types');
    function wpse_mime_types($existing_mimes)
    {
        // Add csv to the list of allowed mime types
        $existing_mimes['csv'] = 'text/csv';

        return $existing_mimes;
    }

    /**
     * Updates post meta for a post. It also automatically deletes or adds the value to field_name if specified
     *
     * @access     protected
     * @param      integer     The post ID for the post we're updating
     * @param      string      The field we're updating/adding/deleting
     * @param      string      [Optional] The value to update/add for field_name. If left blank, data will be deleted.
     * @return     void
     */

    function __update_post_meta($post_id, $field_name, $value = '')
    {
        if (empty($value) or !$value) {
            delete_post_meta($post_id, $field_name);
        } elseif (!get_post_meta($post_id, $field_name)) {
            add_post_meta($post_id, $field_name, $value);
        } else {
            update_post_meta($post_id, $field_name, $value);
        }
    }

    // Custom Scripts
    add_action('wp_enqueue_scripts', 'custom_scripts');
    function custom_scripts()
    {
        wp_enqueue_script('kanbanotron', plugin_dir_url(__FILE__) . 'js/kanbanotron.js', array('jquery'));
    }

    // Custom Admin Scripts
    add_action('admin_enqueue_scripts', 'custom_admin_scripts');
    function custom_admin_scripts()
    {
        wp_enqueue_script('knbn_admin.js', plugin_dir_url(__FILE__) . 'admin/js/knbn_admin.js', array('jquery'), false, true);

        wp_enqueue_style('admin.css', plugin_dir_url(__FILE__) . 'admin/admin.css', false, false);
    }
