<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once "config.inc.php";

$db = new mysqli(
    $dbconfig["db_server"],
    $dbconfig["db_username"],
    $dbconfig["db_password"],
    $dbconfig["db_name"]
);

if ($db->connect_error) {
    die("DB ERROR: " . $db->connect_error);
}

// Function to convert timestamps
function toZagreb($ts) {
    if (!$ts || $ts == 0) return "-";
    $dt = new DateTime("@$ts");
    $dt->setTimezone(new DateTimeZone("Europe/Zagreb"));
    return $dt->format("Y-m-d H:i:s");
}

// Handle RESET action
if (isset($_GET["reset"])) {
    $id = intval($_GET["reset"]);
    $now = time();
    $db->query("UPDATE vtiger_cron_task SET status = 1, lastend = $now WHERE id = $id");
    echo "<script>alert('Task $id reset successfully!'); window.location='cron_monitor.php';</script>";
    exit;
}

$res = $db->query("SELECT * FROM vtiger_cron_task ORDER BY id");
?>

<html>
<head>
<title>Vtiger Cron Monitor PRO</title>

<meta http-equiv="refresh" content="5">

<style>
body {
    font-family: Arial;
    background: #f4f4f4;
    padding: 20px;
}

table {
    width: 100%;
    background: white;
    border-collapse: collapse;
    box-shadow: 0 0 10px #ccc;
}

th, td {
    padding: 10px;
    border-bottom: 1px solid #ddd;
}

th {
    background: #333;
    color: white;
}

.status-ok { color: green; font-weight: bold; }
.status-running { color: blue; font-weight: bold; }
.status-stuck { color: red; font-weight: bold; }
.status-disabled { color: gray; }

.btn-reset {
    background: red;
    color: white;
    padding: 5px 10px;
    text-decoration: none;
    border-radius: 5px;
    font-size: 12px;
}
</style>
</head>

<body>

<h2>⚙️ Vtiger Cron Monitor — PRO Version</h2>
<p>Auto-refresh: every 5 seconds</p>

<table>
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Status</th>
    <th>Last Start (Zagreb)</th>
    <th>Last End (Zagreb)</th>
    <th>Duration</th>
    <th>State</th>
    <th>Action</th>
</tr>

<?php
while ($row = $res->fetch_assoc()) {

    $id = $row["id"];
    $start = $row["laststart"];
    $end = $row["lastend"];

    $startZ = toZagreb($start);
    $endZ   = toZagreb($end);

    // Duration
    $duration = ($start > 0 && $end > 0) ? ($end - $start) : 0;

    // Determine state
    $state = "OK";
    $class = "status-ok";

    if ($row["status"] == 0) {
        $state = "DISABLED";
        $class = "status-disabled";
    } 
    elseif ($start > 0 && $end == 0) {
        // Running or stuck
        if (time() - $start > 60) {
            $state = "STUCK (>60s)";
            $class = "status-stuck";
        } else {
            $state = "RUNNING";
            $class = "status-running";
        }
    }

    echo "
    <tr>
        <td>{$row["id"]}</td>
        <td>{$row["name"]}</td>
        <td>{$row["status"]}</td>
        <td>$startZ</td>
        <td>$endZ</td>
        <td>{$duration}s</td>
        <td class='$class'>$state</td>
        <td>
            <a class='btn-reset' 
               href='cron_monitor.php?reset=$id' 
               onclick='return confirm(\"Reset task $id?\");'>
               Reset
            </a>
        </td>
    </tr>";
}
?>

</table>

</body>
</html>
