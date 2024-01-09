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

// Check for errors in query execution
if (!$delayedItemsResult) {
    die("Query failed: " . $conn->error);
}

// Query for items due today
$todayItemsQuery = "SELECT * FROM items WHERE itemDout = '$currentDate'";
$todayItemsResult = $conn->query($todayItemsQuery);

// Check for errors in query execution
if (!$todayItemsResult) {
    die("Query failed: " . $conn->error);
}

// Query for currently borrowed items
$currentItemsQuery = "SELECT * FROM items WHERE itemDout IS NULL";
$currentItemsResult = $conn->query($currentItemsQuery);

// Check for errors in query execution
if (!$currentItemsResult) {
    die("Query failed: " . $conn->error);
}

// Function to handle item deletion
function deleteItem($itemId) {
    global $conn;
    $deleteQuery = "DELETE FROM items WHERE itemId = '$itemId'";
    $deleteResult = $conn->query($deleteQuery);

    if (!$deleteResult) {
        die("Deletion failed: " . $conn->error);
    }
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

        th,
        td {
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
                <th>Item ID</th>
                <th>Item Naam</th>
                <th>Item Nummer</th>
                <th>Datum van Terugbrengen</th>
                <th>Item Omschrijving</th>
                <th>Item Status</th>
                <th>Action</th>
            </tr>
            <?php
            // Check if there are results
            if ($delayedItemsResult->num_rows > 0) {
                while ($row = $delayedItemsResult->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['itemId']}</td>
                            <td>{$row['itemName']}</td>
                            <td>{$row['itemNumber']}</td>
                            <td>{$row['itemDout']}</td>
                            <td>{$row['itemDescription']}</td>
                            <td>{$row['itemState']}</td>
                            <td><button onclick=\"deleteItem('{$row['itemId']}')\">Delete</button></td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No delayed items found</td></tr>";
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
                <th>Datum van Terugbrengen</th>
                <th>Item Omschrijving</th>
                <th>Item Status</th>
                <th>Action</th>
            </tr>
            <?php
            // Check if there are results
            if ($todayItemsResult->num_rows > 0) {
                while ($row = $todayItemsResult->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['itemId']}</td>
                            <td>{$row['itemName']}</td>
                            <td>{$row['itemNumber']}</td>
                            <td>{$row['itemDout']}</td>
                            <td>{$row['itemDescription']}</td>
                            <td>{$row['itemState']}</td>
                            <td><button onclick=\"deleteItem('{$row['itemId']}')\">Delete</button></td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No items due today</td></tr>";
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
                <th>Datum van Terugbrengen</th>
                <th>Item Omschrijving</th>
                <th>Item Status</th>
                <th>Action</th>
            </tr>
            <?php
            // Check if there are results
            if ($currentItemsResult->num_rows > 0) {
                while ($row = $currentItemsResult->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['itemId']}</td>
                            <td>{$row['itemName']}</td>
                            <td>{$row['itemNumber']}</td>
                            <td>{$row['itemDout']}</td>
                            <td>{$row['itemDescription']}</td>
                            <td>{$row['itemState']}</td>
                            <td><button onclick=\"deleteItem('{$row['itemId']}')\">Delete</button></td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No currently borrowed items</td></tr>";
            }
            ?>
        </table>
    </div>

    <script>
        // JavaScript function to confirm item deletion and send request to the server
        function deleteItem(itemId) {
            var confirmDelete = confirm("Are you sure you want to delete this item?");
            if (confirmDelete) {
                // Send an AJAX request to the server to delete the item
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        // Reload the page or update the table as needed
                        location.reload();
                    }
                };
                xmlhttp.open("GET", "delete_item.php?itemId=" + itemId, true);
                xmlhttp.send();
            }
        }
    </script>

    <?php
    // Sluit de databaseverbinding
    $conn->close();
    ?>
</body>

</html>
