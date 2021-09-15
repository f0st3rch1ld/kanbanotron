<?php
include plugin_dir_path(__FILE__) . '../db/kanbanotron_connection.php';

$active_order_id;

$active_order_id_query = "SELECT order_id FROM purchase_orders WHERE is_active='active'";
$active_order_id_result = $conn->query($active_order_id_query);

$conn->close();

function order_id_check($x)
{
    include plugin_dir_path(__FILE__) . '../db/kanbanotron_connection.php';
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
?>

<!-- Active Order Controls Container -->
<div id="active-order-controls-container" style="display:none;">

    <?php

    if ($active_order_id_result->num_rows == 0) {
        // if there was not an active order, generates an order id, checks to make sure it doesn't exist, then assigns it to the $active_order_id variable, then inserts that new order into the database, ready to be edited.

        generate_order_id();

        // as long as the check returns true, generates new order number
        while (order_id_check($active_order_id)) {
            generate_order_id();
        }

        echo "<script>updateActiveOrder('" . $active_order_id . "');</script>";

        include plugin_dir_path(__FILE__) . '../db/kanbanotron_connection.php';

        $new_active_order = "INSERT INTO purchase_orders (order_id, is_active) VALUES ('$active_order_id', 'active')";

        if ($conn->query($new_active_order) === TRUE) {
            echo "New order created, and set to active.&nbsp;";
        } else {
            echo "There was a problem creating a new order.&nbsp;";
        }

        $conn->close();

        echo '<input type="hidden" id="order-selection" value="' . $active_order_id . '" />';
    } else {

        if ($active_order_id_result->num_rows > 1) : ?>
            <form method="post" action="" name="active_order_selection" class="active-order-form">
                <select name="order_selection" id="order-selection" onchange="updateActiveOrder(this.value); document.getElementById('active-order-submit').click();">
                    <?php $i; ?>
                    <?php while ($row = mysqli_fetch_assoc($active_order_id_result)) : ?>
                        <?php $active_order_id = $row['order_id']; ?>
                        <?php $i++; ?>
                        <option value="<?php echo $row['order_id']; ?>" <?php if (array_key_exists('working_purchase_order', $_COOKIE)) : ?><?php if ($_COOKIE['working_purchase_order'] == $row['order_id']) : ?>selected<?php endif; ?><?php endif; ?>><?php echo 'Order #' . $i . ' - Order_ID: ' . $row['order_id']; ?></option>
                    <?php endwhile; ?>
                </select>
                <input type="submit" id="active-order-submit" />
            </form>

        <?php else : ?>

    <?php while ($row = $active_order_id_result->fetch_assoc()) {
                $active_order_id = $row['order_id'];
            }
            echo '
        <input type="hidden" id="order-selection" value="' . $active_order_id . '" />';
        endif;
    }
    ?>

    <!-- <button onclick="newPO()">New Chocolate Chip <i class="fas fa-cookie-bite"></i></button> -->

</div>
<!-- /Active Order Controls Container -->