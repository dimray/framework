<?php require ROOT_PATH . "views/shared/header.php"; ?>

<h1>Registration Failed</h1>

<p>The registration attempt has been flagged as spam. This is because you have either filled in a hidden field, or
    submitted the form too quickly. If you are a genuine user, don't worry - there is no ongoing tracking marking your
    computer as a spambot, just try again but avoid filling out the phone field if you can see it, and make sure there
    are more than 5 seconds between when you load the page and when you submit the form.
</p>

<p><a href="/register">Register</a></p>

<?php require ROOT_PATH . "views/shared/footer.php"; ?>