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

// Update item availability
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["updateAvailability"])) {
    $itemId = isset($_POST["itemId"]) ? $_POST["itemId"] : '';
    $newAvailability = isset($_POST["newAvailability"]) ? $_POST["newAvailability"] : '';

    // Check if both itemId and newAvailability are not empty
    if ($itemId !== '' && $newAvailability !== '') {
        // Update the item availability in the database
        $updateQuery = "UPDATE items SET itemState = '$newAvailability' WHERE id = $itemId";

        if ($conn->query($updateQuery) === TRUE) {
            $successMessage = "Item availability updated successfully!";
            echo "<script>setTimeout(function() { document.getElementById('successMessage').style.display='none'; }, 3000);</script>";
        } else {
            $errorMessage = "Error updating availability: " . $conn->error;
            echo "<script>setTimeout(function() { document.getElementById('errorMessage').style.display='none'; }, 3000);</script>";
        }
        
    }
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
</head>
<body>

    <div class="container mt-5">
        <h2>Item Overzicht</h2>

        <?php
        if (isset($successMessage)) {
            echo '<div id="successMessage" class="alert alert-success">' . $successMessage . '</div>';
        } elseif (isset($errorMessage)) {
            echo '<div id="errorMessage" class="alert alert-danger">' . $errorMessage . '</div>';
        }

        if ($result->num_rows > 0) {
            echo '<table class="table mt-4">
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

            while ($row = $result->fetch_assoc()) {
                // Check if the "id" key exists in the array
                $itemId = isset($row["id"]) ? $row["id"] : '';

                echo '<tr>
                        <td>' . $itemId . '</td>
                        <td>' . $row["itemName"] . '</td>
                        <td>' . $row["itemNumber"] . '</td>
                        <td>' . $row["itemDin"] . '</td>
                        <td>' . $row["itemDout"] . '</td>
                        <td>' . $row["itemDescription"] . '</td>
                        <td>' . $row["itemState"] . '</td>
                        <td><img src="' . $row["itemPicture"] . '" alt="Item Image" style="max-width: 100px;"></td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="itemId" value="' . $itemId . '">
                                <select name="newAvailability" class="form-control">
                                    <option value="Beschikbaar">Beschikbaar</option>
                                    <option value="Uitgeleend">Uitgeleend</option>
                                </select>
                                <button type="submit" name="updateAvailability" class="btn btn-primary btn-sm mt-2">Update</button>
                            </form>
                        </td>
                    </tr>';
            }

            echo '</tbody></table>';
        } else {
            echo '<p>Geen items beschikbaar.</p>';
        }

        $conn->close();
        ?>
    </div>

    <!-- Add your other HTML content or scripts here -->

</body>
</html>
