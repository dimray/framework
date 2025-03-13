<?php require ROOT_PATH . "views/shared/header.php"; ?>

<h1>Reset Password</h1>

<?php if (isset($errors)): ?>
    <?php foreach ($errors as $error): ?>
        <p><?= $error ?></p>
    <?php endforeach; ?>
<?php endif; ?>

<form action="/password/start" method="POST">

    <label for="email">Email</label>
    <input type="text" name="email" id="email" value="<?= esc($data['email'] ?? '') ?>">

    <button>Submit</button>

</form>

<p><a href="/login">Cancel</a></p>




<?php require ROOT_PATH . "views/shared/footer.php"; ?>