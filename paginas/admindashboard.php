<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include '../includes/header.php';
require_once('../includes/db_connection.php');

if ($conn->connect_error) {
    die("Verbinding mislukt: " . $conn->connect_error);
}

$currentDate = date('Y-m-d');

// Handle item edit form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['editItemId'])) {
    $editItemId = htmlspecialchars($_POST['editItemId']);
    $editItemQuery = "SELECT * FROM items WHERE itemId = '$editItemId'";
    $editItemResult = $conn->query($editItemQuery);

    if ($editItemResult->num_rows > 0) {
        $editItemRow = $editItemResult->fetch_assoc();
    }
}

// Handle item update form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['updateItem'])) {
    $itemId = htmlspecialchars($_POST['itemId']);
    $editItemName = htmlspecialchars($_POST['editItemName']);
    $editItemNumber = htmlspecialchars($_POST['editItemNumber']);
    $editItemDescription = htmlspecialchars($_POST['editItemDescription']);
    $editItemDin = htmlspecialchars($_POST['editItemDin']);
    $editItemDout = htmlspecialchars($_POST['editItemDout']);

    $updateItemQuery = "UPDATE items 
                        SET itemName = '$editItemName', itemNumber = '$editItemNumber', 
                        itemDescription = '$editItemDescription', itemDin = '$editItemDin', itemDout = '$editItemDout'
                        WHERE itemId = '$itemId'";

    if ($conn->query($updateItemQuery) === TRUE) {
        echo "Item succesvol bijgewerkt!";
    } else {
        echo "Fout bij het bijwerken van het item: " . $conn->error;
    }
}

// Handle item deletion form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['deleteItemId'])) {
    $deleteItemId = htmlspecialchars($_POST['deleteItemId']);
    $deleteItemQuery = "DELETE FROM items WHERE itemId = '$deleteItemId'";

    if ($conn->query($deleteItemQuery) === TRUE) {
        echo "Item succesvol verwijderd!";
        header("Location: {$_SERVER['PHP_SELF']}");
        exit();
    } else {
        echo "Fout bij het verwijderen van het item: " . $conn->error;
    }
}

// Query for all items
$allItemsQuery = "SELECT * FROM items";
$allItemsResult = $conn->query($allItemsQuery);

if (!$allItemsResult) {
    die("Error fetching items: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elektronica Uitleen Beheerder</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
        }

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

        th,
        td {
            padding: 10px;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        /* Style for the popup container */
        .popup-container {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 20px;
            border: 2px solid #ccc;
            background-color: #fff;
            z-index: 1000;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            text-align: left;
        }

        /* Style for the overlay background when the popup is open */
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        /* Style for the close button inside the popup */
        .popup-close {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
            font-size: 18px;
            color: #333;
        }

        /* Style for buttons */
        input[type="submit"],
        input[type="button"] {
            padding: 8px;
            margin: 4px;
            cursor: pointer;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 4px;
        }

        input[type="submit"]:hover,
        input[type="button"]:hover {
            background-color: #45a049;
        }
    </style>
</head>

<!-- ... (existing code) ... -->

</html>


<body>
    <div class="section">
        <h2>Alle Items</h2>
        <table border="1">
            <tr>
                <th>Item Naam</th>
                <th>Item Nummer</th>
                <th>uitgeleend op</th>
                <th>inlevern op</th>
                <th>Item Omschrijving</th>
                <th>Item Status</th>
                <th>Actie</th>
            </tr>
            <?php
            if ($allItemsResult->num_rows > 0) {
                while ($row = $allItemsResult->fetch_assoc()) {
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
                                <input type='submit' value='Verwijderen'>
                            </form>
                            <input type='button' value='Bewerken' onclick='openPopup(\"{$row['itemId']}\", \"{$row['itemName']}\", \"{$row['itemNumber']}\", \"{$row['itemDescription']}\", \"{$row['itemDin']}\", \"{$row['itemDout']}\")'>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='8'>Geen items gevonden</td></tr>";
            }
            ?>
        </table>
    </div>

    <!-- Popup container for item editing -->
    <div class="popup-container" id="itemEditPopup">
        <span class="popup-close" onclick="closePopup()">&times;</span>
        <h2>Edit Item</h2>
        <form method='post' action='<?php echo $_SERVER["PHP_SELF"]; ?>'>
            <input type='hidden' id='editItemId' name='itemId' value=''>
            Item Naam: <input type='text' id='editItemName' name='editItemName' value=''><br>
            Item Nummer: <input type='text' id='editItemNumber' name='editItemNumber' value=''><br>
            Item Omschrijving: <textarea id='editItemDescription' name='editItemDescription'></textarea><br>
            Datum uitlenen: <input type='date' id='editItemDin' name='editItemDin' value=''><br>
            Datum Terugbrengen: <input type='date' id='editItemDout' name='editItemDout' value=''><br>
            <input type='submit' name='updateItem' value='Bijwerken'>
        </form>
    </div>

    <!-- Overlay background when the popup is open -->
    <div class="overlay" id="overlay" onclick="closePopup()"></div>

    <script>
        function openPopup(itemId, itemName, itemNumber, itemDescription, itemDin, itemDout) {
            document.getElementById('editItemId').value = itemId;
            document.getElementById('editItemName').value = itemName;
            document.getElementById('editItemNumber').value = itemNumber;
            document.getElementById('editItemDescription').value = itemDescription;
            document.getElementById('editItemDin').value = itemDin;
            document.getElementById('editItemDout').value = itemDout;
            document.getElementById('itemEditPopup').style.display = 'block';
            document.getElementById('overlay').style.display = 'block';
        }

        function closePopup() {
            document.getElementById('editItemId').value = '';
            document.getElementById('editItemName').value = '';
            document.getElementById('editItemNumber').value = '';
            document.getElementById('editItemDescription').value = '';
            document.getElementById('editItemDin').value = '';
            document.getElementById('editItemDout').value = '';
            document.getElementById('itemEditPopup').style.display = 'none';
            document.getElementById('overlay').style.display = 'none';
        }
    </script>

    <?php
    $conn->close();
    ?>
</body>

</html>
