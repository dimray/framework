<?php require ROOT_PATH . "views/shared/header.php"; ?>

<h1>Log in</h1>

<?php if (isset($errors)): ?>
<?php foreach ($errors as $error): ?>
<p><?= $error ?></p>
<?php endforeach; ?>
<?php endif; ?>

<form action="/session/create" method="POST">

    <label for="email">Email</label>
    <input type="text" name="email" id="email" value="<?= esc($data['email'] ?? '') ?>">

    <label for="password">Password</label>
    <input type="password" name="password" id="password">

    <button>Log In</button>

</form>

<p><a href="/">cancel</a></p>

<p><a href="/password/forgot">Reset password</a></p>

<?php require ROOT_PATH . "views/shared/footer.php"; ?>