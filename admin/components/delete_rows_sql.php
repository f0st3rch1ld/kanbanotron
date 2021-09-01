<!-- 
These are the SQL queries you must run to delete all posts that are a part of kanbanotron. (to clear the database).


DELETE FROM wp_postmeta 
WHERE meta_key='kanban_information_vendor' 
OR meta_key='kanban_information_part_number_group_part_number' 
OR meta_key='kanban_information_part_number_group_vendor_part_number' 
OR meta_key='kanban_information_description' 
OR meta_key='kanban_information_quantities_kanban_quantity' 
OR meta_key='kanban_information_quantities_package_quantity'
OR meta_key='kanban_information_quantities_reorder_quantity'
OR meta_key='kanban_information_lead_time'
OR meta_key='product_setup_product_type'
OR meta_key='product_setup_order_method'
OR meta_key='kanban_information_location'
OR meta_key='kanban_information_vendor'
OR meta_key='product_setup_knbn_uid';

DELETE FROM wp_posts
WHERE post_type='knbn_action';

DELETE FROM wp_term_relationships
WHERE object_id NOT IN (SELECT id FROM wp_posts);
 -->

