<?php
// An example of server-side XSLT.
require_once("../lib/common.php");

// Require XML building logic
define(NO_XML_RENDER_CONST, 1);
require_once("../lib/xml_orders.php");

// This script must always respond with xml
header('Content-Type: text/xml');

function renderFromXml($dom, $root) {
    $pi = $dom->createProcessingInstruction("xml-stylesheet", 'type="text/xsl" href="all.xsl"');
    $dom->appendChild($pi);
    $dom->appendChild($root);
    return $dom->saveXML();
}

// Well, we only need to render the XML with a special directive. Let's do this.
$dom = createDom();
$root = buildRoot($dom, fetchData());
echo renderFromXml($dom, $root);