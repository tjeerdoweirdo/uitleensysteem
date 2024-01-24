<?php
require_once 'db_connection.php';

if (isset($_POST['itemId'])) {
    $itemId = $_POST['itemId'];
    
    $logSql = "SELECT * FROM log WHERE itemId = $itemId";
    $logResult = mysqli_query($conn, $logSql);

    if ($logResult) {
        while ($logRow = mysqli_fetch_assoc($logResult)) {
            echo '<p>' . $logRow['logEntry'] . '</p>';
        }
    } 
}
