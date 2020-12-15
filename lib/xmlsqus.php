<?php
// This file mimics integration with external comments service (e.g. Disqus, Facebook/VK comments and so on).
// See reports/comments.php for further details
require_once("common.php");

function getCommentsXml($entry_id) {
    global $CN;

    $dom = createDom();
    $root = $dom->createElement("comments");

    $stmt = $CN->prepare("SELECT comment text, created_at FROM comment_order WHERE order_id = ? ORDER BY created_at");
    $stmt->bind_param("i", $entry_id);
    $result = $stmt->execute();
    if ($result) {
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $root->appendChild(xmlFromArray($dom, "comment", $row));
        }
    }
    $dom->appendChild($root);
    return $dom->saveXML();
}
