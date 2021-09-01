<!-- custom-content-container -->
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
        <a href="/kanbanotron/?on_order_ov=1">View On Order List</a>
    <?php endif; ?>

</div>
<!-- /custom-content-container -->