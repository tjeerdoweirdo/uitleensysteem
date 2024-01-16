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

// Voeg een nieuw item toe
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $voorwerp_naam = $_POST["itemName"];
    $item_number = $_POST["itemNumber"];
    $datum_inleveren = $_POST["itemDin"];
    $datum_terugbrengen = $_POST["itemDout"];
    $item_description = $_POST["itemDescription"];
    $item_state = $_POST["itemState"];
    // Assume 'itemPicture' is a file upload, handle it accordingly

    $insertQuery = "INSERT INTO items (itemName, itemNumber, itemDin, itemDout, itemDescription, itemState, itemPicture) 
                    VALUES ('$voorwerp_naam', '$item_number', '$datum_inleveren', '$datum_terugbrengen', '$item_description', '$item_state', '')";
    // If 'itemPicture' is a file upload, handle it accordingly by moving the uploaded file to a specified directory and storing the path in the database.

    if ($conn->query($insertQuery) === TRUE) {
        echo "Item succesvol toegevoegd!";
    } else {
        echo "Error: " . $insertQuery . "<br>" . $conn->error;
    }
}
include '../includes/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Item Toevoegen</title>

    <!-- Add Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Add Animate.css for animations -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <style>
        /* Optional: Add custom styles for animations */
        .animated {
            animation-duration: 1s;
        }

        /* Optional: Add custom animation for form elements */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .fadeIn {
            animation-name: fadeIn;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="animate__animated animate__fadeIn">Item Toevoegen</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data" class="animate__animated animate__fadeIn">
            <div class="form-group">
                <label for="itemName">Item Naam:</label>
                <input type="text" class="form-control" name="itemName" required>
            </div>

            <div class="form-group">
                <label for="itemNumber">Item Nummer:</label>
                <input type="text" class="form-control" name="itemNumber">
            </div>

            <div class="form-group">
                <label for="itemDin">Datum van Inleveren:</label>
                <input type="date" class="form-control" name="itemDin">
            </div>

            <div class="form-group">
                <label for="itemDout">Datum van Terugbrengen:</label>
                <input type="date" class="form-control" name="itemDout">
            </div>

            <div class="form-group">
                <label for="itemDescription">Item Omschrijving:</label>
                <input type="text" class="form-control" name="itemDescription">
            </div>

            <div class="form-group">
                <label for="itemState">Item Status:</label>
                <input type="text" class="form-control" name="itemState">
            </div>

            <div class="form-group">
                <label for="itemPicture">Item Afbeelding:</label>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="itemPicture" name="itemPicture">
                    <label class="custom-file-label" for="itemPicture">Kies bestand</label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Voeg Item Toe</button>
        </form>
    </div>

    <?php
    // Sluit de databaseverbinding
    $conn->close();
    ?>

    <!-- Add Bootstrap JS and Popper.js -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
