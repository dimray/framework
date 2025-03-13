<?php require ROOT_PATH . "views/shared/header.php"; ?>

<h1>Home</h1>

<?php if (isset($_SESSION['user_id'])): ?>

    <p>hello <?= $_SESSION['user_name'] ?></p>

    <p><a href="/logout">Log out</a></p>

<?php else: ?>

    <p><a href="/register">Register</a> or <a href="/login">Log in</a></p>

<?php endif; ?>




<?php require ROOT_PATH . "views/shared/footer.php"; ?>