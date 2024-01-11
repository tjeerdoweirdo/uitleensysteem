<?php
require_once('../includes/db_connection.php');

// Controleer de verbinding
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Current date
$currentDate = date('Y-m-d');

// Query for delayed items
$delayedItemsQuery = "SELECT * FROM items WHERE itemDout < '$currentDate' AND itemDout IS NOT NULL";
$delayedItemsResult = $conn->query($delayedItemsQuery);

// Query for items due today
$todayItemsQuery = "SELECT * FROM items WHERE itemDout = '$currentDate'";
$todayItemsResult = $conn->query($todayItemsQuery);


$currentItemsQuery = "SELECT * FROM items WHERE itemDout IS NULL AND itemDin IS NOT NULL";
$currentItemsResult = $conn->query($currentItemsQuery);


// Check for errors in query execution
if (!$delayedItemsResult || !$todayItemsResult || !$currentItemsResult) {
    die("Query failed: " . $conn->error);
}
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
        /* Add this style in the <style> section of your HTML file or in an external CSS file */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

table tr {
    animation: fadeIn 0.5s ease-out; /* Adjust the duration and easing as needed */
}

    </style>
</head>
<body>
    <div class="section">
        <h2>Telaat ingeleverde items</h2>
        <table border="1">
            <tr>
                <th>Item ID</th>
                <th>Item Naam</th>
                <th>Item Nummer</th>
                <th>Datum van Inleveren</th>
                <th>Datum van Terugbrengen</th>
                <th>Item Omschrijving</th>
                <th>Item Status</th>
                <!-- Add more columns as needed -->
            </tr>
            <?php
            // Check if there are results
            if ($delayedItemsResult->num_rows > 0) {
                while ($row = $delayedItemsResult->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['itemId']}</td>
                            <td>{$row['itemName']}</td>
                            <td>{$row['itemNumber']}</td>
                            <td>{$row['itemDin']}</td>
                            <td>{$row['itemDout']}</td>
                            <td>{$row['itemDescription']}</td>
                            <td>{$row['itemState']}</td>
                            <!-- Add more columns as needed -->
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No delayed items found</td></tr>";
            }
            ?>
        </table>
    </div>

    <div class="section">
        <h2>Items die vandaag moeten worden ingeleverd</h2>
        <table border="1">
            <tr>
                <th>Item ID</th>
                <th>Item Naam</th>
                <th>Item Nummer</th>
                <th>Datum van Inleveren</th>
                <th>Datum van Terugbrengen</th>
                <th>Item Omschrijving</th>
                <th>Item Status</th>
                <!-- Add more columns as needed -->
            </tr>
            <?php
            // Check if there are results
            if ($todayItemsResult->num_rows > 0) {
                while ($row = $todayItemsResult->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['itemId']}</td>
                            <td>{$row['itemName']}</td>
                            <td>{$row['itemNumber']}</td>
                            <td>{$row['itemDin']}</td>
                            <td>{$row['itemDout']}</td>
                            <td>{$row['itemDescription']}</td>
                            <td>{$row['itemState']}</td>
                            <!-- Add more columns as needed -->
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No items due today</td></tr>";
            }
            ?>
        </table>
    </div>

    <div class="section">
        <h2>Momenteel Uitgeleende Items</h2>
        <table border="1">
            <tr>
                <th>Item ID</th>
                <th>Item Naam</th>
                <th>Item Nummer</th>
                <th>Datum van Inleveren</th>
                <th>Datum van Terugbrengen</th>
                <th>Item Omschrijving</th>
                <th>Item Status</th>
                <!-- Add more columns as needed -->
            </tr>
            <?php
            // Check if there are results
            if ($currentItemsResult->num_rows > 0) {
                while ($row = $currentItemsResult->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['itemId']}</td>
                            <td>{$row['itemName']}</td>
                            <td>{$row['itemNumber']}</td>
                            <td>{$row['itemDin']}</td>
                            <td>{$row['itemDout']}</td>
                            <td>{$row['itemDescription']}</td>
                            <td>{$row['itemState']}</td>
                            <!-- Add more columns as needed -->
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No currently borrowed items</td></tr>";
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
