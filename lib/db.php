<?php
require_once("config.php");

global $CN, $CFG;

$CN = new mysqli(
    $CFG->db_server,
    $CFG->db_username,
    $CFG->db_password,
    $CFG->db_name
);

if ($CN->connect_error) {
    error_log("Failed to connect to database: $CN->connect_error");
    die("Database connection failed. Check logs for further info.");
}

function woInstalled() {
    global $CN;
    $result = $CN->query("SELECT value FROM settings WHERE name = 'installed'");
    return $result !== FALSE && $result->num_rows == 1;
}

// Redirect to installation script if schema was not initialized (and not in install script)
if (!defined(WO_INSTALL_CONST_NAME) && !woInstalled()) {
    die(header("Location: " . $CFG->root_path . "/install.php"));
}

