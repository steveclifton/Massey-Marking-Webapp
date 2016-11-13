    <tr>
        <td><p class="listdata">SKU</p></td>
        <td><p class="listdata">Product Name</p></td>
        <td><p class="listdata">Category</p></td>
        <td><p class="listdata">Price</p></td>
        <td><p class="listdata">Qty</p></td>
    </tr>
    <?php
    foreach ($viewData as $product) { ?>
        <tr>
            <td><?= $product['sku'] ?> </td>
            <td><?= $product['product_name'] ?> </td>
            <td><?= $product['category_name'] ?> </td>
            <td>$<?= $product['price'] ?> </td>
            <td><?= $product['qty'] ?> </td>
        </tr>
    <?php } ?>


