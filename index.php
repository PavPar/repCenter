<?php
require_once("lib/common.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workshop Overseer</title>
    <link href="./styles/master.css" rel="stylesheet">
</head>

<body class="page">
<header class="header">
    <h1 class="header__title">Workshop Overseer v1.0</h1>
</header>
<main class="content">
    <div class="description">
        <h2>Welcome, <b>Overseer</b>!</h2>
        <p>Here you can place a new order, as well as see the reports on existing orders.</p>
    </div>
    <div class="main-actions">
        <a href="create.php" class="main-actions__action">
            Place an order
        </a>
        <a href="reports/" class="main-actions__action">
            Reports
        </a>
    </div>
</main>
<footer class="footer"></footer>
</body>

</html>
