<?php
// On Order Overview
?>

<h3 style="font-weight:600;">On-Order Overview</h3>

<form name="knbn_uid-enter" id="knbn_uid-form" action="/kanbanotron/" method="get">
    <input type="text" id="knbn_uid" name="knbn_uid" placeholder="Scan your QR Code" autofocus />
    <input type="submit" value="Search for Kanban to add to order" />
</form>

<!-- On Order List Container -->
<div id="on-order-list-container">
    <?php include 'load_on_order_list.php'; ?>
</div>
<!-- /On Order List Container -->