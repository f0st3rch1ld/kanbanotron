<?php

$order_total = 0;

function calculate_order_total ($items_array) {

    global $order_total;

    for ($i = 0; count($items_array) > $i; $i++) {
        $order_total = $order_total + (intval($items_array[$i]['Item_Price']) * intval($items_array[$i]['Item_Reorder_Amount']));
    }
}

echo 'Order Total: ' . $order_total . '; ';