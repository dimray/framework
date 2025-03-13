<?php require ROOT_PATH . "views/shared/header.php"; ?>

<h1>Delete Product</h1>

<form action="/products/<?= $product['id'] ?>/destroy" method="POST">

    <p>Are you sure?</p>

    <button>Delete</button>

</form>

<p><a href="/products/<?= $product['id'] ?>/show">cancel</a></p>

<?php require ROOT_PATH . "views/shared/footer.php"; ?>