<?php require ROOT_PATH . "views/shared/header.php"; ?>


<h1>Products</h1>

<?php foreach ($products as $product): ?>

<h2><a href="/products/<?= $product['id'] ?>/show"><?= esc($product['name'] ?? '') ?></a></h2>

<?php endforeach; ?>

<p><a href="/products/new">New</a></p>

<?php require ROOT_PATH . "views/shared/footer.php"; ?>