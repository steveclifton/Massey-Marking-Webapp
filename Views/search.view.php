<div id="wrapper1">
    <nav>
        <ul class="menu">
            <li><a href="browse">Browse</a></li>
            <li><a href="search">Search</a></li>
            <li><a href="logout">Logout</a></li>
        </ul>
        <div id="left" >
            <p class="name">Welcome <?= $viewData['first_name'] . " " . $viewData['last_name'] ?> !</p>
        </div>
    </nav>
</div>
<div class = "boxed">
    <nav>
        <form action="search" method="get">
            <input type="text" name="search" id="searchfield" placeholder="Search Products">
        </form>
        <table class="fixed">
            <tr>
                <td><p class="listdata">SKU</p></td>
                <td><p class="listdata">Product Name</p></td>
                <td><p class="listdata">Category</p></td>
                <td><p class="listdata">Price</p></td>
                <td><p class="listdata">Qty</p></td>
            </tr>
        <?php
        foreach ($viewData['products'] as $product) { ?>
            <tr>
                <td><?= $product['sku'] ?> </td>
                <td><?= $product['product_name'] ?> </td>
                <td><?= $product['category_name'] ?> </td>
                <td>$<?= $product['price'] ?> </td>
                <td><?= $product['qty'] ?> </td>
            </tr>
        <?php } ?>
        </table>
    </nav>
</div>
