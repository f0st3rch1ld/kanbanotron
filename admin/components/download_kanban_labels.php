<?php

$post_ids = json_decode($_GET['post_ids']);

// empty array to store uid's we need to download
$knbn_uid_to_dwnld = array();

// lets add all the uids to the previous array
foreach ($post_ids as $post_id) {
    $bulk_knbn_uid = get_post_meta($post_id, 'product_setup_knbn_uid', true);
    array_push($knbn_uid_to_dwnld, $bulk_knbn_uid);
}

// Now we need to include the wp database connection
include plugin_dir_path(__FILE__) . '../../db/knbn_wp_connection.php';
include plugin_dir_path(__FILE__) . '../../db/request.php';

// request.php reference
// $knbn_external_yn;
// $knbn_order_method;
// $knbn_external_url;
// $knbn_location;
// $knbn_vendor;
// $knbn_part_number;
// $knbn_vendor_part_number;
// $knbn_description;
// $knbn_package_quantity;
// $knbn_reorder_quantity;
// $knbn_quantity;
// $knbn_lead_time;
// $knbn_notes;

?>

<div class="knbn-loading-modal" id="loading-screen">
    <h2 id="loading-text">Generating Kanban Labels, Please Wait...</h2>
</div>

<!-- Kanban Label Grid Container -->
<div class="knbn-lbl-grid-container">
    <?php foreach ($knbn_uid_to_dwnld as $knbn_uid) :

        global $knbn_external_yn;
        global $knbn_order_method;
        global $knbn_external_url;
        global $knbn_location;
        global $knbn_vendor;
        global $knbn_part_number;
        global $knbn_vendor_part_number;
        global $knbn_description;
        global $knbn_package_quantity;
        global $knbn_reorder_quantity;
        global $knbn_quantity;
        global $knbn_lead_time;
        global $knbn_notes;

        knbn_info_request($knbn_uid);

        $blue_knbn_qty = 0;
        $red_knbn_qty = 0;

        if ($knbn_quantity) {

            global $blue_knbn_qty;
            global $red_knbn_qty;

            $qty_explode = explode('/', $knbn_quantity);
            $blue_knbn_qty = $qty_explode[0];
            $red_knbn_qty = $qty_explode[1];
        }
    ?>

        <!-- <?php echo $knbn_uid; ?> Kanban Label -->
        <div class="knbn-lbl" data="<?php echo str_replace(' ', '-', $knbn_vendor . "-" . $knbn_vendor_part_number); ?>">

            <!-- Blue Label -->
            <div class="blue-label" style="background-image:url('<?php echo WP_CONTENT_URL . '/plugins/kanbanotron/admin/images/Cogs.png' ?>');">
                <div class="title-container">
                    <h4><?php echo $knbn_vendor_part_number; ?></h4>
                </div>
                <div class="lower-label-container">
                    <h3>Description</h3>
                    <p class="blue-label-description">
                        <?php echo $knbn_description; ?>
                    </p>
                    <div class="blue-knbn-qty">
                        <h3>QTY:</h3>
                        <p>
                            <?php if ($blue_knbn_qty != 0) {
                                echo $blue_knbn_qty;
                            } ?>
                        </p>
                    </div>
                </div>
            </div>
            <!-- /Blue Label -->

            <!-- Red Label -->
            <div class="red-label" style="background-image:url('<?php echo WP_CONTENT_URL . '/plugins/kanbanotron/admin/images/Cogs.png' ?>');">
                <div class="title-container">
                    <h4><?php echo $knbn_vendor_part_number; ?></h4>
                </div>
                <div class="lower-label-container">
                    <div class="lower-left">
                        <div class="red-knbn-qty">
                            <h3>QTY:</h3>
                            <p>
                                <?php if ($red_knbn_qty != 0) {
                                    echo $red_knbn_qty;
                                } ?>
                            </p>
                        </div>
                        <div class="eta">
                            <h3>ETA:</h3>
                        </div>
                        <div class="purchase-order-number">
                            <h3>PO:</h3>
                        </div>
                    </div>
                    <div class="lower-right">
                        <div class="qrcode-container" id="<?php echo $knbn_uid; ?>-qrcode" style="width:195px; height:195px; margin-top:30px;" data="<?php echo $knbn_uid; ?>"></div>
                    </div>
                </div>
            </div>
            <!-- /Red Label -->

        </div>
        <!-- /<?php echo $knbn_uid; ?> Kanban Label -->

    <?php endforeach; ?>
</div>
<!-- /Kanban Label Grid Container -->

<?php $conn->close(); ?>

<!-- This is where the magic happens -->
<script>
    let allDaCodez = document.getElementsByClassName('qrcode-container');
    let allKnbns = document.getElementsByClassName('knbn-lbl');

    let initDownload = () => {
        let zip = new JSZip();
        for (let i = 0; allKnbns.length > i; i++) {
            let fileName = allKnbns[i].getAttribute('data') + '.png';
            domtoimage.toBlob(allKnbns[i]).then(function(blob) {
                zip.file(fileName, blob);
            });
        }
        setTimeout(function() {
            zip.generateAsync({
                type: "blob"
            }).then(function(blob) {
                saveAs(blob, 'generated_kanban_labels.zip');
            });

            document.getElementById('loading-text').innerHTML = "Labels Zipped, Downloading";

            setTimeout(function() {
                window.location.replace("<?php echo admin_url() . 'edit.php?post_type=knbn_action&bulk_download_kanban_labels=' . count($knbn_uid_to_dwnld); ?>");
            }, 10000);
        }, allKnbns.length * 250);
    }

    window.addEventListener('load', function() {
        for (let i = 0; allDaCodez.length > i; i++) {
            let uid = allDaCodez[i].getAttribute('data');
            let newCode = `http://internalweb/kanbanotron/?knbn_uid=${uid}`;
            let qrcode = new QRCode(document.getElementById(`${uid}-qrcode`), {
                width: 195,
                height: 195,
            });
            qrcode.makeCode(newCode);
        }

        // Image Save Functionality
        document.getElementById('loading-text').innerHTML = "Zipping Labels, Almost Done...";

        initDownload();
    });
</script>
<!-- /End of magic... there is no more magic, go home. -->