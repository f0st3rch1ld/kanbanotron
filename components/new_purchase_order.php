<?php

$active_order_id;

function order_id_check($x)
{
    include '../db/kanbanotron_connection.php';
    $order_id_check_query = "SELECT order_id FROM purchase_orders WHERE order_id='" . $x . "'";
    $order_id_check_result = $conn->query($order_id_check_query);
    if ($order_id_check_result->num_rows > 0) {
        return true; //order_id already exists, generate another one
    } else {
        return false; //order_id doesn't already exist, you're good to go!
    }
    $conn->close();
}

function generate_order_id()
{
    // Generates a new 25 char random alphanumeric order number for the current order.
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $generated_number = '';
    for ($i = 0; $i < 25; $i++) {
        $generated_number .= $characters[rand(0, strlen($characters) - 1)];
    }
    global $active_order_id;
    $active_order_id = $generated_number;
}

generate_order_id();

// as long as the check returns true, generates new order number
while (order_id_check($active_order_id)) {
    generate_order_id();
}

include '../db/kanbanotron_connection.php';

$new_active_order = "INSERT INTO purchase_orders (order_id, is_active) VALUES ('$active_order_id', 'active')";

if ($conn->query($new_active_order) === TRUE) {
    echo "New order created, and set to active.&nbsp;";
} else {
    echo "There was a problem creating a new order.&nbsp;";
}

$conn->close();

?>

<form method="post" action="" name="active_order_selection">
    <input type="submit" id="active-order-submit" onclick="updateActiveOrder('<?php echo $active_order_id; ?>')" style="display:none;" />
</form>