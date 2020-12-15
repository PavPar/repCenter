<?php
// Render all orders as xml for system interoperability
require_once("common.php");

function inflateData($data) {
    $result = [];
    foreach ($data as $key => $value) {
        if (strpos($key, '__') !== false) {
            $expl = explode('__', $key);
            $root_key = $expl[0];
            if (!array_key_exists($root_key, $result)) {
                $result[$root_key] = [];
            }
            $result[$root_key][$expl[1]] = $value;
        } else {
            $result[$key] = $value;
        }
    }
    return $result;
}

function ordersQuery() {
    return "
    SELECT o.id id,
    
           w.id worker__id,
           w.first_name worker__first_name,
           w.middle_name worker__middle_name,
           w.last_name worker__last_name,
    
           c.id client__id,
           c.first_name client__first_name,
           c.middle_name client__middle_name,
           c.last_name client__last_name,
           c.phone client__phone,
           c.email client__email,
    
           ot.name order_type,
           o.order_status status,
           o.device device_info,
           o.price price
    FROM `order` o
        JOIN client c ON o.client_id = c.id
        JOIN worker w ON w.id = o.worker_id
        JOIN order_type ot ON o.order_type_id = ot.id";
}

function fetchData() {
    global $CN;
    $q = $CN->query(ordersQuery() . " ORDER BY o.id");
    $orders = [];
    while ($row = $q->fetch_assoc()) {
        $orders[] = $row;
    }
    return $orders;
}

function orderElement($dom, $data) {
    $result = $dom->createElement("order");
    $result->appendChild(xmlFromArray($dom, "worker", $data["worker"]));
    $result->appendChild(xmlFromArray($dom, "client", $data["client"]));
    unset($data["worker"]);
    unset($data["client"]);
    xmlFromArray($dom, $result, $data);
    return $result;
}

function buildRoot($dom, $orders) {
    $root = $dom->createElement("orders");
    foreach ($orders as $order) {
        $root->appendChild(orderElement($dom, inflateData($order)));
    }
    return $root;
}

function buildXml($orders) {
    $dom = createDom();
    $dom->appendChild(buildRoot($dom, $orders));
    return $dom->saveXML();
}

// In order to reuse methods, don't render if necessary.
if (!defined(NO_XML_RENDER_CONST)) {
    header('Content-Type: text/xml');
    echo buildXml(fetchData());
}
