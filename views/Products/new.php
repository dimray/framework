<?php require ROOT_PATH . "views/shared/header.php"; ?>

<h1>New Product</h1>

<?php if (isset($errors)): ?>
<?php foreach ($errors as $error): ?>
<p><?= $error ?></p>
<?php endforeach; ?>
<?php endif; ?>

<form action="/products/create" method="POST">

    <label for="name">Name</label>
    <input type="text" name="name" id="name" value="<?= esc($product['name'] ?? '') ?>">

    <label for="description">Description</label>
    <input type="text" name="description" id="description" value="<?= esc($product['description'] ?? '') ?>">

    <button>Create</button>

</form>

<p><a href="/products/index">cancel</a></p>

<?php require ROOT_PATH . "views/shared/footer.php"; ?>