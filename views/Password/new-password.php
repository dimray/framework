<?php require ROOT_PATH . "views/shared/header.php"; ?>

<h1>Reset Password</h1>

<?php if (isset($errors)): ?>
    <?php foreach ($errors as $error): ?>
        <p><?= $error ?></p>
    <?php endforeach; ?>
<?php endif; ?>

<form action="/password/complete" method="POST">

    <input type="hidden" name="token" value="<?= esc($token ?? '') ?>">

    <label for="password">New Password</label>
    <input type="password" name="password" id="password">

    <label for="confirm_password">Confirm New Password</label>
    <input type="password" name="confirm_password" id="confirm_password">

    <button>Submit</button>

</form>

<p><a href="/login">cancel</a></p>

<?php require ROOT_PATH . "views/shared/footer.php"; ?>