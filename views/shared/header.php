<?php require "functions.php"; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Framework</title>
    <link rel="stylesheet" href="/styles.css">
    <link rel="icon" href="data:image/png;base64,iVBORw0KGgo=">
</head>

<body>

    <?php if (isset($_SESSION['flash_notifications'])): ?>

        <?php foreach (\App\Flash::getMessages() as $message): ?>

            <p><?= $message['body'] ?></p>

        <?php endforeach; ?>

    <?php endif; ?>