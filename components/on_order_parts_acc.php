<table class="tablesorter" id="on-order-parts-container">
    <thead>
        <tr>
            <th>PN</th>
            <th>Description</th>
            <th>QTY</th>
            <th>Date Ordered</th>
            <th>PO</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($value as $ind_purchase_order) : ?>
            <?php foreach ($purchaseorderlineret_table_data_array as $ind_products) : ?>
                <?php if ($ind_purchase_order === $ind_products['PARENT_IDKEY']) : ?>
                    <tr>
                        <td class="full-name"><?php echo $ind_products['ItemRef_FullName']; ?></td>
                        <td class="description"><?php echo $ind_products['Description']; ?></td>
                        <td class="quantity"><?php echo number_format($ind_products['Quantity'], 0); ?></td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </tbody>
</table>