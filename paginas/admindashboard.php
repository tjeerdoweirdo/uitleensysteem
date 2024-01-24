<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include '../includes/header.php';
require_once('../includes/db_connection.php');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$currentDate = date('Y-m-d');

// Handle item edit form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['editItemId'])) {
    $editItemId = htmlspecialchars($_POST['editItemId']);
    $editItemQuery = "SELECT * FROM items WHERE itemId = '$editItemId'";
    $editItemResult = $conn->query($editItemQuery);

    if ($editItemResult->num_rows > 0) {
        $editItemRow = $editItemResult->fetch_assoc();

        echo "<form method='post' action='{$_SERVER["PHP_SELF"]}'>
                <input type='hidden' name='itemId' value='{$editItemRow['itemId']}'>
                Item Name: <input type='text' name='editItemName' value='{$editItemRow['itemName']}'><br>
                Item Number: <input type='text' name='editItemNumber' value='{$editItemRow['itemNumber']}'><br>
                <input type='submit' name='updateItem' value='Update'>
            </form>";
    }
}

// Handle item update form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['updateItem'])) {
    $itemId = htmlspecialchars($_POST['itemId']);
    $editItemName = htmlspecialchars($_POST['editItemName']);
    $editItemNumber = htmlspecialchars($_POST['editItemNumber']);

    $updateItemQuery = "UPDATE items 
                        SET itemName = '$editItemName', itemNumber = '$editItemNumber' 
                        WHERE itemId = '$itemId'";

    if ($conn->query($updateItemQuery) === TRUE) {
        echo "Item updated successfully!";
    } else {
        echo "Error updating item: " . $conn->error;
    }
}

// Handle item deletion form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['deleteItemId'])) {
    $deleteItemId = htmlspecialchars($_POST['deleteItemId']);
    $deleteItemQuery = "DELETE FROM items WHERE itemId = '$deleteItemId'";

    if ($conn->query($deleteItemQuery) === TRUE) {
        echo "Item deleted successfully!";
        header("Location: {$_SERVER['PHP_SELF']}");
        exit();
    } else {
        echo "Error deleting item: " . $conn->error;
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
    <title>Elektronica Uitleen Admin</title>
    <style>


    .section {
        margin: 20px;
        text-align: center;
        overflow-x: auto;
    }

    table {
        margin: auto;
        border-collapse: collapse;
        width: 100%;
        overflow-x: auto;
        white-space: nowrap;
    }

    th, td {
        padding: 10px;
        border: 1px solid black;
    }

    th {
        background-color: #f2f2f2;
    }
</style>
</head>

<body>
    <div class="section">
        <h2>Telaat ingeleverde items</h2>
        <table border="1">
            <tr>
                <th>Item Naam</th>
                <th>Item Nummer</th>
                <th>Datum van Inleveren</th>
                <th>Datum van Terugbrengen</th>
                <th>Item Omschrijving</th>
                <th>Item Status</th>
                <th>Action</th>
            </tr>
            <?php
            if ($delayedItemsResult->num_rows > 0) {
                while ($row = $delayedItemsResult->fetch_assoc()) {
                    echo "<tr>
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
                <th>Item Naam</th>
                <th>Item Nummer</th>
                <th>Datum van Inleveren</th>
                <th>Datum van Terugbrengen</th>
                <th>Item Omschrijving</th>
                <th>Item Status</th>
                <th>Action</th>
            </tr>
            <?php
            if ($todayItemsResult->num_rows > 0) {
                while ($row = $todayItemsResult->fetch_assoc()) {
                    echo "<tr>
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
                <th>Item Naam</th>
                <th>Item Nummer</th>
                <th>Datum van Inleveren</th>
                <th>Datum van Terugbrengen</th>
                <th>Item Omschrijving</th>
                <th>Item Status</th>
                <th>Action</th>
            </tr>
            <?php
            if ($currentItemsResult->num_rows > 0) {
                while ($row = $currentItemsResult->fetch_assoc()) {
                    echo "<tr>
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
    <div class="section">
    <h2>Items Currently in Storage</h2>
    <table border="1">
        <tr>
            <th>Item ID</th>
            <th>Item Name</th>
            <th>Item Number</th>
            <th>Item Description</th>
            <th>Action</th>
        </tr>
        <?php
        // Query for items currently in storage
        $inStorageItemsQuery = "SELECT * FROM items WHERE itemDout IS NULL";
        $inStorageItemsResult = $conn->query($inStorageItemsQuery);

        if ($inStorageItemsResult->num_rows > 0) {
            while ($row = $inStorageItemsResult->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['itemId']}</td>
                        <td>{$row['itemName']}</td>
                        <td>{$row['itemNumber']}</td>
                        <td>{$row['itemDescription']}</td>
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
            echo "<tr><td colspan='5'>No items currently in storage</td></tr>";
        }
        ?>
    </table>
</div>

    <?php
    $conn->close();
    ?>
</body>

</html>