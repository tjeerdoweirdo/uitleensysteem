<?php

session_start();

if (!isset($_SESSION['user_id']) ) {
    header('Location: login.php');
    exit();
}

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

// Handle item addition form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['addItem'])) {
    $itemName = htmlspecialchars($_POST['itemName']);
    $itemNumber = htmlspecialchars($_POST['itemNumber']);
    $itemDin = htmlspecialchars($_POST['itemDin']);
    $itemDout = htmlspecialchars($_POST['itemDout']);
    $itemDescription = htmlspecialchars($_POST['itemDescription']);
    $itemState = htmlspecialchars($_POST['itemState']);

    $addItemQuery = "INSERT INTO items (itemName, itemNumber, itemDin, itemDout, itemDescription, itemState)
                     VALUES ('$itemName', '$itemNumber', '$itemDin', '$itemDout', '$itemDescription', '$itemState')";

    if ($conn->query($addItemQuery) === TRUE) {
        echo "Item added successfully!";
    } else {
        echo "Error adding item: " . $conn->error;
    }
}

// Handle item deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['deleteItemId'])) {
    $deleteItemId = htmlspecialchars($_POST['deleteItemId']);

    $deleteItemQuery = "DELETE FROM items WHERE itemId = '$deleteItemId'";

    if ($conn->query($deleteItemQuery) === TRUE) {
        echo "<div id='deleteMessage'>Item deleted successfully!</div>";
        echo "<script>
                setTimeout(function() {
                    document.getElementById('deleteMessage').style.display = 'none';
                }, 2000); // Hide the message after 2 seconds
                setTimeout(function() {
                    window.location.reload();
                }, 300000); // Reload the page after 5 minutes (300,000 milliseconds)
              </script>";
    } else {
        echo "Error deleting item: " . $conn->error;
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
            animation: fadeIn 1s ease-in-out;
        }

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
            animation: fadeIn 1s ease-in-out;
        }

        h2 {
            text-align: center;
            animation: fadeIn 1s ease-in-out;
        }

        form {
            display: inline; /* Keep forms in the same line */
            animation: fadeIn 1s ease-in-out;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
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
                            <td>{$row['itemDin']}</td>
                            <td>{$row['itemDout']}</td>
                            <td>{$row['itemDescription']}</td>
                            <td>{$row['itemState']}</td>
                            <td>
                                <form method='post' action='{$_SERVER["PHP_SELF"]}'>
                                    <input type='hidden' name='deleteItemId' value='{$row['itemId']}'>
                                    <input type='submit' value='Delete'>
                                </form>
                            </td>
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
                            <td>{$row['itemDin']}</td>
                            <td>{$row['itemDout']}</td>
                            <td>{$row['itemDescription']}</td>
                            <td>{$row['itemState']}</td>
                            <td>
                                <form method='post' action='{$_SERVER["PHP_SELF"]}'>
                                    <input type='hidden' name='deleteItemId' value='{$row['itemId']}'>
                                    <input type='submit' value='Delete'>
                                </form>
                            </td>
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
            <th>Action</th>
        </tr>
        <?php
        // Query for items due from 2 years before today
        $currentItemsQuery = "SELECT * FROM items WHERE itemDout IS NOT NULL AND DATE(itemDout) < DATE_SUB(CURDATE(), INTERVAL 2 YEAR)";
        $currentItemsResult = $conn->query($currentItemsQuery);

        // Check if there are results
        if ($currentItemsResult->num_rows > 0) {
            while ($row = $currentItemsResult->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['itemId']}</td>
                        <td>{$row['itemName']}</td>
                        <td>{$row['itemNumber']}</td>
                        <td>" . ($row['itemDin'] ? $row['itemDin'] : 'N/A') . "</td>
                        <td>" . ($row['itemDout'] ? $row['itemDout'] : 'N/A') . "</td>
                        <td>{$row['itemDescription']}</td>
                        <td>{$row['itemState']}</td>
                        <td>
                            <form method='post' action='{$_SERVER["PHP_SELF"]}'>
                                <input type='hidden' name='deleteItemId' value='{$row['itemId']}'>
                                <input type='submit' value='Delete'>
                            </form>
                        </td>
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
