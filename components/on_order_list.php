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

<!-- Page Refresh Script -->
<script>
    // AJAX request to refresh On-Order Overview
    function refreshOverview() {
        const xhttp = new XMLHttpRequest();
        xhttp.onload = function() {
            document.getElementById('on-order-list-container').innerHTML = this.responseText;
        }
        xhttp.open(
            "GET",
            `../../wp-content/plugins/kanbanotron/components/load_on_order_list.php/?xhttp=1`
        );
        xhttp.send();
    }

    setInterval(refreshOverview, 10000);
</script>
<!-- /Page Refresh Script -->