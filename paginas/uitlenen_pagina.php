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

// Handle status change form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['changeStatus'])) {
    $itemId = htmlspecialchars($_POST['itemId']);
    $newStatus = htmlspecialchars($_POST['newStatus']);

    if ($newStatus == 'Ingeleverd') {
        // Perform turn-in
        $turnInQuery = "UPDATE items 
                        SET itemDin = NULL, itemDout = NULL, itemState = 'Teruggebracht' 
                        WHERE itemId = '$itemId'";

        if ($conn->query($turnInQuery) === TRUE) {
            echo "Item succesvol ingeleverd!";
        } else {
            echo "Fout bij inleveren item: " . $conn->error;
        }
    } elseif ($newStatus == 'Geleend') {
        // Perform borrowing
        $borrowedDate = date('Y-m-d');
        $returnDate = date('Y-m-d', strtotime($borrowedDate . ' + 7 days'));

        $borrowQuery = "UPDATE items 
                        SET itemDin = '$borrowedDate', itemDout = '$returnDate', itemState = 'Geleend' 
                        WHERE itemId = '$itemId'";

        if ($conn->query($borrowQuery) === TRUE) {
            echo "Item succesvol geleend!";
        } else {
            echo "Fout bij lenen item: " . $conn->error;
        }
    } else {
        // Perform status change
        $changeStatusQuery = "UPDATE items SET itemState = '$newStatus' WHERE itemId = '$itemId'";

        if ($conn->query($changeStatusQuery) === TRUE) {
            echo "Status succesvol bijgewerkt!";
        } else {
            echo "Fout bij bijwerken status: " . $conn->error;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status en Datum Beheer</title>
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
        <h2>Status en Datum Beheer</h2>
        <table border="1">
            <tr>
                <th>Item Naam</th>
                <th>Item Nummer</th>
                <th>Datum van Inleveren</th>
                <th>Datum van Terugbrengen</th>
                <th>Item Omschrijving</th>
                <th>Item Status</th>
                <th>Actie</th>
            </tr>
            <?php
            $statusItemsQuery = "SELECT * FROM items";
            $statusItemsResult = $conn->query($statusItemsQuery);

            if ($statusItemsResult->num_rows > 0) {
                while ($row = $statusItemsResult->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['itemName']}</td>
                            <td>{$row['itemNumber']}</td>
                            <td>{$row['itemDin']}</td>
                            <td>{$row['itemDout']}</td>
                            <td>{$row['itemDescription']}</td>
                            <td>{$row['itemState']}</td>
                            <td>
                                <form method='post' action='{$_SERVER["PHP_SELF"]}'>
                                    <input type='hidden' name='itemId' value='{$row['itemId']}'>
                                    <input type='hidden' name='newStatus' value='Geleend'>
                                    <button type='submit' name='changeStatus'>Geleend</button>
                                </form>
                                <form method='post' action='{$_SERVER["PHP_SELF"]}'>
                                    <input type='hidden' name='itemId' value='{$row['itemId']}'>
                                    <input type='hidden' name='newStatus' value='Ingeleverd'>
                                    <button type='submit' name='changeStatus'>Inleveren</button>
                                </form>
                                <form method='post' action='{$_SERVER["PHP_SELF"]}'>
                                    <input type='hidden' name='editItemId' value='{$row['itemId']}'>
                                    <button type='submit'>Bewerken</button>
                                </form>
                            </td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='8'>Geen items gevonden</td></tr>";
            }
            ?>
        </table>
    </div>

    <?php
    $conn->close();
    ?>
</body>

</html>
