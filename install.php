<?php
require_once("lib/const.php");
define(WO_INSTALL_CONST_NAME, "install");
require_once("lib/common.php");

global $CN;

// Prevent re-installation
if (woInstalled() && !key_exists('force', $_GET)) {
    die(header("Location: index.php"));
}

function createClientTable() {
    global $CN;
    $CN->query("create table client (
        id int auto_increment
            primary key,
        first_name varchar(255) not null,
        middle_name varchar(255) null,
        last_name varchar(255) not null,
        phone varchar(100) null,
        email varchar(255) null
    )");
}

function createWorkerTable() {
    global $CN;
    $CN->query("create table worker (
        id int auto_increment
            primary key,
        first_name varchar(255) not null,
        middle_name varchar(255) null,
        last_name varchar(255) not null
    )");
}

function createOrderTypeTable() {
    global $CN;
    $CN->query("create table order_type (
        id int auto_increment
            primary key,
        name varchar(100) not null,
        constraint order_type_name_uindex
            unique (name)
    )");
    insertOrderTypes();
}

function createOrderTable() {
    global $CN;
    $CN->query("create table `order` (
        id int auto_increment
            primary key,
        worker_id int not null,
        client_id int not null,
        device varchar(255) not null,
        order_type_id int not null,
        ordered_at timestamp default current_timestamp() null,
        order_status int default 0 not null,
        price int default 0 not null,
        constraint order_client__fk
            foreign key (client_id) references client (id),
        constraint order_type__fk
            foreign key (order_type_id) references order_type (id),
        constraint order_worker__fk
            foreign key (worker_id) references worker (id)
    )");
}

function createCommentOrderTable() {
    global $CN;
    $CN->query("create table comment_order (
        order_id int null,
        created_at timestamp default current_timestamp() not null,
        comment text not null,
        constraint comment_order_pk
            unique (order_id, created_at),
        constraint comment_order__fk
            foreign key (order_id) references order (id)
    )");
}

function createSettingsTable() {
    global $CN;
    $CN->query("create table settings (
        name varchar(255) not null
            primary key,
        value varchar(255) null
    )");
    $CN->query("insert into settings (name, value) values ('installed', 'yes')");
}

function insertOrderTypes() {
    global $CN;
    $types = [
        "Diagnosis", "Cleanup", "Warranty repair", "Non-warranty repair", "Forensics", "Consulting"
    ];
    foreach ($types as $t) {
        $t = $CN->real_escape_string($t);
        $CN->query("INSERT INTO order_type (name) VALUES ('$t')");
    }
}

function createSchema() {
    createClientTable();
    createWorkerTable();
    createOrderTypeTable();
    createOrderTable();
    createCommentOrderTable();
    createSettingsTable();
}

function addSampleData() {

}

if (isPost()) {
    $needSampleData = key_exists("sample", $_POST);
    createSchema();
    if ($needSampleData) {
        addSampleData();
    }
    die(header("Location: index.php"));
} else {
    // Check if all required extensions are present
    $required_extensions = ["xml", "xsl", "mysqli"];
    $missing_extensions = [];
    foreach ($required_extensions as $e) {
        if (!extension_loaded($e)) {
            $missing_extensions[] = $e;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workshop Overseer Installation</title>
    <link href="./styles/master.css" rel="stylesheet">
</head>

<body class="page">
<header class="header">
    <h1 class="header__title">Workshop Overseer Installation</h1>
</header>
<main class="content">
    <span class="description">
        <?php if (empty($missing_extensions)) {
            echo 'This wizard will install Workshop Overseer to your server. Please, choose whether you want sample data installed or not.';
        } else {
            echo 'Installation aborted: required PHP extensions missing';
        }?>
    </span>
    <?php if (empty($missing_extensions)) {
        echo '<form class="main-actions" method="POST">
            <button name="sample" class="main-actions__action">
                Install sample data
            </button>
            <button name="nosample" class="main-actions__action">
                Don\'t install sample data
            </button>
        </form>';
    } else {
        echo '<ul>';
        foreach ($missing_extensions as $ex) {
            echo "<li>$ex</li>";
        }
        echo '</ul>';
    } ?>
</main>
<footer class="footer"></footer>
</body>

</html>
