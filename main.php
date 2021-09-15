<?php
$current_user = wp_get_current_user();

if (!is_user_logged_in()) : ?>

    <p>In order to use this tool, you must first log into the website!</p>
    <hr />
    <p>You must also have an account that has been granted access to this tool to use it.</p>
    <hr />
    <?php wp_login_form(); ?>

<?php elseif ($current_user->check_kanbanotron_access == 'enabled') : ?>

    <!-- Kanbanotron Container -->
    <div class="kanbanotron-main-container">

        <?php if (isset($_GET['knbn_uid'])) : ?>
            <?php include 'components/app.php'; ?>
        <?php elseif (isset($_GET['on_order_ov'])) : ?>
            <?php include 'components/on_order_list.php'; ?>
        <?php else : ?>
            <form name="knbn_uid-enter" id="knbn_uid-form" action="" method="get">
                <input type="text" id="knbn_uid" name="knbn_uid" placeholder="Scan your QR Code" autofocus />
                <input type="submit" value="Search for kanban to add to order" />
            </form>
            <!-- <a href="/kanbanotron/?on_order_ov=1">View On Order List</a> -->
        <?php endif; ?>

    </div>
    <!-- /Kanbanotron Container -->

    <script>
        document.cookie = `wp_current_user=<?php echo $current_user->ID; ?>`;
    </script>

<?php else : ?>

    <p>Unfortunately, you don't seem to have access to this part of the website. If you think this is a mistake, please send a ticket to IT! Thank you!</p>

<?php endif; ?>