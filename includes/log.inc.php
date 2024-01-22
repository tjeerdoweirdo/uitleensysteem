<?php

require_once 'db_connection.php';

$sql = "SELECT items *, GROUP_CONCAT(log.logEntry ORDER BY log.logDate DESC) AS itemLogs
        FROM items
        LEFT JOIN log ON items.itemId = log.itemId
        GROUP BY items.itemId;";
        
$result = mysqli_query($conn, $sql);

if (isset($_POST)['addlog']){

        $sqladdlog = 
        $resultaddlog = mysqli_query($conn, $sqladdlog)
}