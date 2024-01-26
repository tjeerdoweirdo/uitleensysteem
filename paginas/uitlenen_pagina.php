<?php
session_start();

// Redirect to login if user is not authenticated
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Include header and database connection
include '../includes/header.php';
require_once('../includes/db_connection.php');

// Handle database connection error
if ($conn->connect_error) {
    die("Verbinding mislukt: " . $conn->connect_error);
}

// Get current date
$currentDate = date('Y-m-d');

// Handle status change form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['changeStatus'])) {
    $itemId = htmlspecialchars($_POST['itemId']);
    $newStatus = htmlspecialchars($_POST['newStatus']);

    // Perform actions based on $newStatus
    if ($newStatus == 'Ingeleverd') {
        // Perform turn-in
        $turnInQuery = "UPDATE items 
                        SET itemDin = NULL, itemDout = NULL, itemState = 'Teruggebracht' 
                        WHERE itemId = $itemId";

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
    // Add more conditions if needed for different statuses
}
?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status en Datum Beheer</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f8f8;
            margin: 0;
            padding: 0;
            overflow: hidden; /* Prevent scroll bars */
        }

        /* General styling for sections */
        .section {
            margin: 20px;
            text-align: center;
            overflow-x: auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        /* Styling for tables */
        table {
            margin: auto;
            border-collapse: collapse;
            width: 100%;
            overflow-x: auto;
            white-space: nowrap;
        }

        th,
        td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        /* Style for late items */
        .late-item {
            color: #ff5252;
        }

        /* Fade-in animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        tr.fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }

        /* Button style */
        button {
            padding: 8px 16px;
            background-color: #4caf50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #45a049;
        }

        /* Responsive styles */
        @media screen and (max-width: 600px) {
            .section {
                margin: 10px;
                padding: 10px;
            }

            table {
                font-size: 12px;
            }

            th,
            td {
                padding: 8px;
            }

            button {
                padding: 6px 12px;
                font-size: 12px;
            }
        }
    </style>
</head>

<body>
    <!-- Table 2: Borrowed Items -->
    <div class="section">
        <h2>Uitgeleende items</h2>
        <table>
            <!-- Table header -->
            <tr>
                <th>Item Naam</th>
                <th>Item Nummer</th>
                <th>uitgeleend op</th>
                <th>inlevern op</th>
                <th>Item Omschrijving</th>
                <th>Item Status</th>
                <th>Actie</th>
            </tr>
            <!-- Table rows for borrowed items -->
            <?php
            $borrowedItemsQuery = "SELECT * FROM items WHERE itemState = 'Geleend'";
            $borrowedItemsResult = $conn->query($borrowedItemsQuery);

            if ($borrowedItemsResult->num_rows > 0) {
                while ($row = $borrowedItemsResult->fetch_assoc()) {
                    $returnDate = $row['itemDout'];
                    $isLate = ($returnDate != null && strtotime($returnDate) < strtotime($currentDate));

                    // Apply style for late items
                    $style = $isLate ? 'class="late-item fade-in"' : 'class="fade-in"';

                    // Output table row
                    echo "<tr {$style}>
                        <td>{$row['itemName']}</td>
                        <td>{$row['itemNumber']}</td>
                        <td>{$row['itemDin']}</td>
                        <td>{$row['itemDout']}</td>
                        <td>{$row['itemDescription']}</td>
                        <td>{$row['itemState']}</td>
                        <td>
                            <form method='post' action='{$_SERVER["PHP_SELF"]}'>
                                <input type='hidden' name='itemId' value='{$row['itemId']}'>
                                <input type='hidden' name='newStatus' value='Ingeleverd'>
                                <button type='submit' name='changeStatus'>Inleveren</button>
                            </form>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='8'>Geen uitgeleende items gevonden</td></tr>";
            }
            ?>
        </table>
    </div>

    <!-- Table 3: Returned Items -->
    <div class="section">
        <h2>Teruggebrachte items</h2>
        <table>
            <!-- Table header -->
            <tr>
                <th>Item Naam</th>
                <th>Item Nummer</th>
                <th>uitgeleend op</th>
                <th>inlevern op</th>
                <th>Item Omschrijving</th>
                <th>Item Status</th>
                <th>Actie</th>
            </tr>
            <!-- Table rows for returned items -->
            <?php
            $returnedItemsQuery = "SELECT * FROM items WHERE itemState = 'Teruggebracht'";
            $returnedItemsResult = $conn->query($returnedItemsQuery);

            if ($returnedItemsResult->num_rows > 0) {
                while ($row = $returnedItemsResult->fetch_assoc()) {
                    // Apply the 'fade-in' class to apply the animation
                    echo "<tr class='fade-in'>
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
                                <button type='submit' name='changeStatus'>Uitlenen</button>
                            </form>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='8'>Geen teruggebrachte items gevonden</td></tr>";
            }
            ?>
        </table>
    </div>
</body>

</html>

<?php
// Close the database connection
$conn->close();
?>
