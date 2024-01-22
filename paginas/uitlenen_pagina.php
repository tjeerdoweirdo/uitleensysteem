<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once('../includes/db_connection.php');

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve items from the database
$selectQuery = "SELECT * FROM items";
$result = $conn->query($selectQuery);

include '../includes/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Item Overzicht</title>

    <!-- Add Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">

    <!-- Add custom CSS for fade-in animation -->
    <style>
        .fade-in {
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2 class="fade-in">Item Overzicht</h2>

    <?php
    if ($result->num_rows > 0) {
        // Initialize arrays to store items based on availability
        $beschikbaarItems = [];
        $uitgeleendItems = [];

        while ($row = $result->fetch_assoc()) {
            // Check if the "id" key exists in the array
            $itemId = isset($row["id"]) ? $row["id"] : '';

            // Categorize items based on availability
            if ($row["itemState"] == 'Beschikbaar') {
                $beschikbaarItems[] = $row;
            } elseif ($row["itemState"] == 'Uitgeleend') {
                $uitgeleendItems[] = $row;
            }
        }

        // Display "Beschikbaar" table
        if (!empty($beschikbaarItems)) {
            echo '<h3 class="fade-in">Beschikbaar</h3>';
            echo '<table class="table mt-4 fade-in">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Item Naam</th>
                            <th>Item Nummer</th>
                            <th>Datum Inleveren</th>
                            <th>Datum Terugbrengen</th>
                            <th>Omschrijving</th>
                            <th>Status</th>
                            <th>Afbeelding</th>
                            <th>Actie</th>
                        </tr>
                    </thead>
                    <tbody>';

            foreach ($beschikbaarItems as $row) {
                // Display rows for "Beschikbaar" items
                displayItemRow($row);
            }

            echo '</tbody></table>';
        }

        // Display "Uitgeleend" table
        if (!empty($uitgeleendItems)) {
            echo '<h3 class="fade-in">Uitgeleend</h3>';
            echo '<table class="table mt-4 fade-in">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Item Naam</th>
                            <th>Item Nummer</th>
                            <th>Datum Inleveren</th>
                            <th>Datum Terugbrengen</th>
                            <th>Omschrijving</th>
                            <th>Status</th>
                            <th>Afbeelding</th>
                            <th>Actie</th>
                        </tr>
                    </thead>
                    <tbody>';

            foreach ($uitgeleendItems as $row) {
                // Display rows for "Uitgeleend" items
                displayItemRow($row);
            }

            echo '</tbody></table>';
        }
    } else {
        echo '<p class="fade-in">Geen items beschikbaar.</p>';
    }

    $conn->close();

    function displayItemRow($row) {
        echo '<tr>
                <td>' . $row["id"] . '</td>
                <td>' . $row["itemName"] . '</td>
                <td>' . $row["itemNumber"] . '</td>
                <td>' . $row["itemDin"] . '</td>
                <td>' . $row["itemDout"] . '</td>
                <td>' . $row["itemDescription"] . '</td>
                <td>' . $row["itemState"] . '</td>
                <td><img src="' . $row["itemPicture"] . '" alt="Item Image" style="max-width: 100px;"></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="itemId" value="' . $row["id"] . '">
                        <select name="newAvailability" class="form-control">
                            <option value="Beschikbaar">Beschikbaar</option>
                            <option value="Uitgeleend">Uitgeleend</option>
                        </select>
                        <button type="submit" name="updateAvailability" class="btn btn-primary btn-sm mt-2">Update</button>
                    </form>
                </td>
            </tr>';
    }
    ?>
</div>

<!-- Add your other HTML content or scripts here -->

</body>
</html>
