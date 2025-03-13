<?php require ROOT_PATH . "views/shared/header.php"; ?>

<h1>Register</h1>

<?php $start_time = time(); ?>

<?php if (isset($errors)): ?>
    <?php foreach ($errors as $error): ?>
        <p><?= $error ?></p>
    <?php endforeach; ?>
<?php endif; ?>

<form action="/register/create" method="POST">

    <input type="hidden" name="start_time" value="<?= $start_time ?>">

    <label for="name">Name</label>
    <input type="text" name="name" id="name" value="<?= esc($data['name'] ?? '') ?>">

    <label for="email">Email</label>
    <input type="text" name="email" id="email" value="<?= esc($data['email'] ?? '') ?>">

    <label for="phone" class="phone" aria-hidden="true">Phone</label>
    <input type="text" name="phone" id="phone" class="phone" autocomplete="off">

    <label for="password">Password</label>
    <input type="password" name="password" id="password">

    <label for="confirm_password">Confirm Password</label>
    <input type="password" name="confirm_password" id="confirm_password">

    <button>Register</button>

</form>

<p><a href="/">cancel</a></p>

<?php require ROOT_PATH . "views/shared/footer.php"; ?>