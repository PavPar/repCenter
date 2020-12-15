<?php
// Simple demonstration of mad MySQL skills
require_once("../lib/common.php");
global $CN;

function lastMonth() {
    return (new DateTime('first day of last month'))->format('Y-m-d 00:00:00');
}

function buildTable($lower_bound) {
    global $CN;
    $stmt = $CN->prepare("
        SELECT w.*, COALESCE(rl.revenue, 0) revenue
        FROM (
        SELECT w.id worker_id, sum(COALESCE(o.price, 0)) revenue
        FROM worker w
        LEFT JOIN (
            SELECT * FROM `order`
            WHERE ordered_at >= TIMESTAMP(?)
        ) o ON w.id = o.worker_id
        GROUP BY w.id
        HAVING sum(COALESCE(o.price, 0)) < ?
        ) rl
        JOIN worker w ON rl.worker_id = w.id
        ORDER BY rl.revenue;");
    $last_month = lastMonth();
    $stmt->bind_param("si", $last_month, $lower_bound);
    $result = $stmt->execute();

    if (!$result) return "";
    $result = "";
    $st_result = $stmt->get_result();
    while($row = $st_result->fetch_assoc()) {
        $result .= "<tr><td><strong>{$row["last_name"]}</strong>,&#xA0;{$row["first_name"]}&#xA0;{$row["middle_name"]}</td>";
        $result .= "<td>${$row["revenue"]}</td></tr>";
    }
    return $result;
}

lastMonth();
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Underperforming Workers</title>
    <link href="../styles/master.css" rel="stylesheet">
</head>
<body>
    <main class="low-revenue">
        <h1>Workers underperforming since <?php echo lastMonth() ?></h1>
        <table class="low-revenuetbl">
            <thead>
                <th>Worker</th>
                <th>Revenue</th>
            </thead>
            <tbody>
                <?php echo buildTable(40000) ?>
            </tbody>
        </table>
    </main>
</body>
</html>