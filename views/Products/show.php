<?php require ROOT_PATH . "views/shared/header.php"; ?>

<h1>Product</h1>

<h2><?= $product['name'] ?></h2>

<p><?= $product['description'] ?></p>

<p><a href="/products/<?= $product['id'] ?>/edit">Edit</a></p>

<p><a href="/products/<?= $product['id'] ?>/delete">Delete</a></p>

<p><a href="/products/index">index</a></p>

<?php require ROOT_PATH . "views/shared/footer.php"; ?>