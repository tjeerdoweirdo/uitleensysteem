<?php
require_once('../includes/db_connection.php');

// Controleer de verbinding
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Current date
$currentDate = date('Y-m-d');

// Query for delayed items
$delayedItemsQuery = "SELECT itemName, itemDin FROM items WHERE itemDout < '$currentDate' AND itemDout IS NOT NULL";
$delayedItemsResult = $conn->query($delayedItemsQuery);

// Query for items due today
$todayItemsQuery = "SELECT itemName, itemDin FROM items WHERE itemDout = '$currentDate'";
$todayItemsResult = $conn->query($todayItemsQuery);

// Query for currently borrowed items
$currentItemsQuery = "SELECT itemName, itemDin FROM items WHERE itemDout IS NULL";
$currentItemsResult = $conn->query($currentItemsQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include '../includes/header.php'; ?>
    <title>Elektronica Uitleen Admin</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .section {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="section">
        <h2>Telaat ingeleverde items</h2>
        <table border="1">
            <tr>
                <th>Voorwerp Naam</th>
                <th>Datum van Inleveren</th>
            </tr>
            <?php
            while ($row = $delayedItemsResult->fetch_assoc()) {
                echo "<tr><td>{$row['itemName']}</td><td>{$row['itemDin']}</td></tr>";
            }
            ?>
        </table>
    </div>

    <div class="section">
        <h2>Items die vandaag moeten worden ingeleverd</h2>
        <table border="1">
            <tr>
                <th>Voorwerp Naam</th>
                <th>Datum van Inleveren</th>
            </tr>
            <?php
            while ($row = $todayItemsResult->fetch_assoc()) {
                echo "<tr><td>{$row['itemName']}</td><td>{$row['itemDin']}</td></tr>";
            }
            ?>
        </table>
    </div>

    <div class="section">
        <h2>Momenteel Uitgeleende Items</h2>
        <table border="1">
            <tr>
                <th>Voorwerp Naam</th>
                <th>Datum van Inleveren</th>
            </tr>
            <?php
            while ($row = $currentItemsResult->fetch_assoc()) {
                echo "<tr><td>{$row['itemName']}</td><td>{$row['itemDin']}</td></tr>";
            }
            ?>
        </table>
    </div>

    <?php
    // Sluit de databaseverbinding
    $conn->close();
    ?>
</body>
</html>
