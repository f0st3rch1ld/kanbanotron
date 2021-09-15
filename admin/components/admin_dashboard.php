<hr style="margin:0px" />

<?php //if (!wp_is_mobile()) : ?>
    <!-- knbn settings tabs -->
    <!-- <div style="flex-direction:row; flex-wrap:wrap; justify-content:flex-start; margin:0px">
        <div class="knbn-admin-tab active" id="knbn-auto-sync">
            <h4>QuickBooks Sync</h4>
        </div>
        <div class="knbn-admin-tab" id="knbn-csv-update">
            <h4>CSV Update</h4>
        </div>
    </div> -->
    <!-- /knbn settings tabs -->
<?php //endif; ?>

<!-- qb sync container -->
<!-- <div class="knbn-admin-container" id="knbn-auto-sync-container" style="display:flex;">
    <div>
        <h3>QuickBooks Sync</h3>
        <p>Use the QuickBooks Sync if you would like to sync/update kanbans inside the database with new information straight to and from QuickBooks. Kanbans generated using the Kanbanotron will be sent directly to the QB database, and any records changed inside QuickBooks will be updated inside the Kanbanotron database.</p>
    </div> -->

    <!-- <div style="flex-direction:row;">
                <label>
                    <input type="radio" name="sync-type" value="Manual Sync" checked="checked" />
                    Manual Sync
                </label>
                <label>
                    <input type="radio" name="sync-type" value="Automatic Sync" />
                    Automatic Sync
                </label>
            </div> -->

    <!-- <div>
                <label>
                    <input type="number" name="sync-frequency" />
                    Sync Frequency (In Minutes)
                </label>
            </div> -->

    <!-- <div id="sync-databases">
        <button>Sync Databases</button>
    </div> -->

    <!-- <div id="auto-sync-databases">
                <button>Save Changes</button>
            </div>
</div> -->
<!-- /qb sync container -->

<?php //if (!wp_is_mobile()) : ?>
    <!-- csv update container -->
    <div class="knbn-admin-container" id="knbn-csv-update-container" >
        <h3>CSV Update</h3>
        <p>
            Use the CSV updater when you want to update kanbanotron with new kanbans. It will read your .csv importing it into both the kanbanotron database, creating new records, or updating ones that already exist.
            <br />
            <strong>Here is an example table of how the .csv needs to be formatted:</strong>
        </p>

        <table id="knbn-example-table">
            <thead>
                <tr>
                    <th>vendor</th>
                    <th>itd_part_number</th>
                    <th>location</th>
                    <th>man_part_number</th>
                    <th>description</th>
                    <th>Purchasing U/M</th>
                    <th>Lead Time</th>
                    <th>Kanban Blue/Red</th>
                    <th>Type</th>
                    <th>Price</th>
                    <th>Size</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>McMaster-Carr</td>
                    <td>ITD7104</td>
                    <td>Hardware</td>
                    <td>9283K14</td>
                    <td>1" Internal Poly Plug</td>
                    <td>1 Pack (10 per pack)</td>
                    <td>10 Days</td>
                    <td>100/100</td>
                    <td>Service</td>
                    <td>100.00</td>
                    <td>large</td>
                </tr>
            </tbody>
        </table>

        <form method="post" enctype="multipart/form-data">
            Upload your .csv
            <input type="file" name="csv_file" id="csv_file" accept=".csv" required />
            <input type="submit" value="import kanbans" />
        </form>

    </div>
    <!-- /csv update container -->
<?php //endif; ?>

<!-- <hr /> -->

<!-- manual update container -->
<!-- <div id="manual-knbn-update-container">
    <div>
        <h3>Manual Kanban Update</h3>
        <p>If you need to manually update a kanbans info, or add a new kanban, you can do so here. This will pull a list of all the kanbans currently synced between QuickBooks & Kanbanotron. You can select which kanban you would like to edit, or you can create a new kanban if you can't find the one you're trying to work on. After you Update the databases, changes made will automatically be synced with quickbooks. No more importation!</p>
    </div>

    <div class="mku-form-container">

        <form name="manual-knbn-update" action=""></form>

        <label style="width:100%; max-width:none;">
            Select a kanban to edit - Or add a new kanban
            <select id="kanban-selection" style="width:100%; max-width:none;"> -->
                <!-- Default Option -->
                <!-- <option value selected></option> -->

                <!-- Add New Option -->
                <!-- <optgroup label="Can't find your kanban? Add a new kanban.">
                    <option value="add-new-knbn">Add a New Kanban</option>
                </optgroup> -->

                <!-- Generated Kanbans List -->
                <!-- <optgroup label="Currently Available Kanbans">
                    <?php
                    // Connection to Wordpress Database
                    // include plugin_dir_path(__FILE__) . '../../db/knbn_wp_connection.php';

                    // $wp_knbn_post_list = array();

                    // $wp_knbn_posts_query = "SELECT ID, post_title FROM wp_posts WHERE post_status='publish' AND post_type='knbn_action'";
                    // $wp_knbn_posts_query_result = $conn->query($wp_knbn_posts_query);

                    // if ($wp_knbn_posts_query_result->num_rows > 0) {
                    //     while ($row = $wp_knbn_posts_query_result->fetch_assoc()) {
                    //         $temp_array = array(
                    //             'ID' => $row['ID'],
                    //             'post_title' => $row['post_title']
                    //         );
                    //         array_push($wp_knbn_post_list, $temp_array);
                    //     }
                    // } else {
                    //     echo 'Error retrieving data: ' . $conn->error;
                    // }

                    // post_list test
                    //echo var_dump($wp_knbn_post_list);

                    // asort($wp_knbn_post_list);

                    // for ($i = 0; count($wp_knbn_post_list) > $i; $i++) : ?>
                        <option value="<?php //echo $wp_knbn_post_list[$i]['ID']; ?>"><?php //echo $wp_knbn_post_list[$i]['post_title']; ?></option>
                    <?php //endfor;

                    // closes connection to Wordpress Database
                    //$conn->close();
                    ?>
                </optgroup> -->

            <!-- </select>
        </label>
    </div>

    <div class="mku-form-container" id="mku-form-fields">
        <?php //include plugin_dir_path(__FILE__) . '../../admin/components/load_mku_form_fields.php'; ?>
    </div>

    <div id="sync-databases">
        <button>Update Kanban</button>
    </div>
</div> -->
<!-- /manual update container -->

<!-- <script>
    document
        .getElementById("kanban-selection")
        .addEventListener("change", function() {
            if (this.value != "add-new-knbn") {
                upManKnbnFields(this.value);
            }
        });
</script> -->