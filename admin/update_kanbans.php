<?php

// Kanbanotron Update Kanbans Admin Page

?>

<div class="update-kanbans-app-container">
    <div class="inner-container">
        <div>
            <h1 style="color:#2271b1;">Kanbanotron - Update Kanbans</h1>
        </div>
        <?php if (array_key_exists('csv_file', $_FILES)) {
            include WP_CONTENT_DIR . '/plugins/kanbanotron/admin/components/csv_update.php';
        } else {
            include WP_CONTENT_DIR . '/plugins/kanbanotron/admin/components/admin_dashboard.php';
        }
        ?>
    </div>
</div>