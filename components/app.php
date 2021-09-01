<?php
$knbn_uid = $_GET['knbn_uid'];
$knbn_uid = str_replace(
    'http://internalweb/kanbanotron/?knbn_uid=',
    '',
    $knbn_uid
);

// Initializes the application on page load
include 'initialize.php';

//echo do_shortcode('[qrcodescanner width="100%" height="300px;"]'); 
?>

<!-- kanban-information-container -->
<div class="kanban-information-container" id="knbn-info-container">
    <?php include 'load_kanban.php'; ?>
</div>
<!-- /kanban-information-container -->

<!-- purchase order overview container -->
<div class="purchase-order-overview-container" id="po-overview-container">
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            loadPOPreview('<?php if (array_key_exists('working_purchase_order', $_COOKIE)) { echo $_COOKIE['working_purchase_order']; } else { echo $active_order_id; } ?>');
        }, false);
    </script>
</div>
<!-- /purchase order overview container -->