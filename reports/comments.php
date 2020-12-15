<?php
// This report demonstrates server-side XSLT application. In order to do that, let's imagine the following situation.
//
// When designing the service, the architect felt that external comments services (such as Disqus) are a really good
// idea and he/she should use one in the project. Among other alternatives, she/he preferred something called "XMLsqus":
// the hypothetical XML-powered comments service.
//
// Let's pretend that this hypothetical service returns XML that contains all the comments of a single entry.
// Unfortunately, XMLsqus does not provide the official PHP API as anyone can handle XML these days (according to them).
// Thinking that no server-side handling for the comments themselves is needed, the developer just appends order info to
// the fetched XML, applies XSL to it on a server and calls it a day. The client receives nice HTML, after all!
require_once("../lib/common.php");
require_once("../lib/xmlsqus.php");

// Reuse order rendering XML method
define(NO_XML_RENDER_CONST, 1);
require_once("../lib/xml_orders.php");

global $CN;

$dom = createDom();

function renderFromXml($dom, $root) {
    // Load XSL
    $xsl_dom = new DOMDocument();
    $xsl_dom->load("comments.xsl");

    // Prepare XSLT Processor
    $processor = new XSLTProcessor();
    $processor->importStylesheet($xsl_dom);

    // Append root to DOM
    $dom->appendChild($root);

    // Transform DOM
    $result = $processor->transformToDoc($dom)->saveHTML();

    // Append HTML5 Doctype
    $result = "<!DOCTYPE html>" . $result;
    return $result;
}

function xmlDieWith($error) {
    global $dom;
    $error = $dom->createElement("error", $error);
    die(renderFromXml($dom, $error));
}

if (!array_key_exists("id", $_GET)) {
    xmlDieWith("");
}

$id = $_GET["id"];
if (!is_numeric($id)) {
    xmlDieWith("ID must be a number");
}

if (!recordExists("order", $id)) {
    http_response_code(404);
    xmlDieWith("Order not found");
}

// Fetch order information
$stmt = $CN->prepare(ordersQuery() . " WHERE o.id = ?");
$stmt->bind_param("i", $id);
$result = $stmt->execute();
if (!$result) {
    http_response_code(500);
    xmlDieWith("Unable to fetch order information");
}
$result = $stmt->get_result();

// Render order details to current DOM
$order = orderElement($dom, inflateData($result->fetch_assoc()));

// Merge two DOMs together
$comments_dom = new DOMDocument();
$comments_dom->loadXML(getCommentsXml($id));
$import = $dom->importNode($comments_dom->getElementsByTagName("comments")[0], true);
$order->appendChild($import);

// Render merged XML
echo renderFromXml($dom, $order);