<?php
require_once("../lib/common.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workshop Overseer</title>
    <link href="../styles/master.css" rel="stylesheet">
</head>

<body class="page">
<header class="header">
    <h1 class="header__title">Отчёты</h1>
</header>
<main class="content">
    <span class="description">
        Choose the report to display, <b>Overseer</b>.
    </span>
    <div class="report-actions">
        <a href="all.php" class="report-actions__action" target="_blank">
            All orders
        </a>
        <a href="comments.php" class="report-actions__action" target="_blank">
            Order comments
        </a>
        <a href="low_revenue.php" class="report-actions__action" target="_blank">
            Underperforming Workers
        </a>
        <a href="../" class="report-actions__action">
            Back
        </a>
    </div>
</main>
<footer class="footer"></footer>
</body>

</html>