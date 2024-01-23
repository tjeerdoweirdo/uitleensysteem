<?php
session_start();

if (!isset($_SESSION['user_id'])) {
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

// Handle item edit form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['editItemId'])) {
    $editItemId = htmlspecialchars($_POST['editItemId']);

    // Fetch the current details of the item
    $editItemQuery = "SELECT * FROM items WHERE itemId = '$editItemId'";
    $editItemResult = $conn->query($editItemQuery);

    if ($editItemResult->num_rows > 0) {
        $editItemRow = $editItemResult->fetch_assoc();

        // Display a form with the current details for editing
        echo "<form method='post' action='{$_SERVER["PHP_SELF"]}'>
                <input type='hidden' name='itemId' value='{$editItemRow['itemId']}'>
                Item Name: <input type='text' name='editItemName' value='{$editItemRow['itemName']}'><br>
                Item Number: <input type='text' name='editItemNumber' value='{$editItemRow['itemNumber']}'><br>
                <!-- Add other input fields for editing other details -->
                <input type='submit' name='updateItem' value='Update'>
            </form>";
    }
}

// Handle item update form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['updateItem'])) {
    $itemId = htmlspecialchars($_POST['itemId']);
    $editItemName = htmlspecialchars($_POST['editItemName']);
    $editItemNumber = htmlspecialchars($_POST['editItemNumber']);
    // Add similar lines for other edited fields

    // Update the item details in the database
    $updateItemQuery = "UPDATE items 
                        SET itemName = '$editItemName', itemNumber = '$editItemNumber' 
                        WHERE itemId = '$itemId'";

    if ($conn->query($updateItemQuery) === TRUE) {
        echo "Item updated successfully!";
    } else {
        echo "Error updating item: " . $conn->error;
    }
}

// Query for delayed items
$delayedItemsQuery = "SELECT * FROM items WHERE itemDout < '$currentDate' AND itemDout IS NOT NULL";
$delayedItemsResult = $conn->query($delayedItemsQuery);

// Query for items due today
$todayItemsQuery = "SELECT * FROM items WHERE itemDout = '$currentDate'";
$todayItemsResult = $conn->query($todayItemsQuery);

// Query for currently borrowed items
$currentItemsQuery = "SELECT * FROM items WHERE itemDout IS NOT NULL AND DATE(itemDout) < DATE_SUB(CURDATE(), INTERVAL 2 YEAR)";
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
                                <form method='post' action='{$_SERVER["PHP_SELF"]}'>
                                    <input type='hidden' name='editItemId' value='{$row['itemId']}'>
                                    <input type='submit' value='Edit'>
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
                                <form method='post' action='{$_SERVER["PHP_SELF"]}'>
                                    <input type='hidden' name='editItemId' value='{$row['itemId']}'>
                                    <input type='submit' value='Edit'>
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
                                <form method='post' action='{$_SERVER["PHP_SELF"]}'>
                                    <input type='hidden' name='editItemId' value='{$row['itemId']}'>
                                    <input type='submit' value='Edit'>
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
