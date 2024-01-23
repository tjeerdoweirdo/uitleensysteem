<?php

require_once 'db_connection.php';

$sql = "SELECT items.itemId, GROUP_CONCAT(log.logEntry ORDER BY log.logDate DESC) AS itemLogs
FROM items
LEFT JOIN log ON items.itemId = log.itemId
GROUP BY items.itemId;";
        
$result = mysqli_query($conn, $sql);

if (isset($_POST['log_id'])) {
    $itemId = $_POST['log_id'];
    $logEntry = mysqli_real_escape_string($conn, $_POST['addlog']);

    $sqladdlog = "INSERT INTO log (itemId, logEntry) VALUES ('$itemId', '$logEntry')";

    $resultaddlog = mysqli_query($conn, $sqladdlog);

    if ($resultaddlog) {
        header("Location:../popuplog.php");
    }
}