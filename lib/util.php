<?php

function isPost()
{
    return $_SERVER["REQUEST_METHOD"] === "POST";
}

function optionsFromArray($arr) {
    $result = "";
    foreach ($arr as $key => $value) {
        $filteredKey = htmlspecialchars($key);
        $filteredValue = htmlspecialchars($value);
        $result .= "<option value='$filteredKey'>$filteredValue</option>";
    }
    return $result;
}

function extractNames($mysqli_result) {
    $names = [];
    if ($mysqli_result) {
        while ($line = $mysqli_result->fetch_assoc()) {
            $names[$line["id"]] = "{$line["last_name"]} {$line["first_name"]} {$line["middle_name"]}";
        }
    }
    return $names;
}

function getPostVal($key) {
    return key_exists($key, $_POST) ? $_POST[$key] : false;
}

function reqPostVals($required_keys) {
    $errors = [];
    foreach ($required_keys as $key) {
        if (!key_exists($key, $_POST)) {
            $errors[] = $key;
        }
    }
    return $errors;
}

function recordExists($table_name, $id) {
    global $CN;
    $stmt = $CN->prepare("SELECT id FROM `" . $CN->real_escape_string($table_name) . "` WHERE id = ?");
    $stmt->bind_param("i", $id);
    $result = $stmt->execute();
    return ($result && $stmt->get_result()->num_rows === 1);
}

function snakeToCamelCase($str) {
    $expl = explode('_', $str, 2);
    return sizeof($expl) > 1 ? $expl[0] . ucwords($expl[1], "_") : $str;
}

function xmlFromArray($dom, $root, $arr) {
    if (is_string($root)) {
        $root = $dom->createElement(snakeToCamelCase($root));
    }

    foreach ($arr as $key => $value) {
        $el = $dom->createElement(snakeToCamelCase($key), $value);
        $root->appendChild($el);
    }
    return $root;
}

function createDom() {
    $dom = new DOMDocument();
    $dom->encoding = "utf-8";
    $dom->xmlVersion = "1.0";
    $dom->formatOutput = true;
    return $dom;
}