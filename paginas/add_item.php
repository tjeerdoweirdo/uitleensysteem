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

// Add a new item
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $voorwerp_naam = $_POST["itemName"];
    $item_number = $_POST["itemNumber"];
    $datum_inleveren = $_POST["itemDin"];
    $datum_terugbrengen = $_POST["itemDout"];
    $item_description = $_POST["itemDescription"];
    $item_state = $_POST["itemState"];

    $targetDirectory = "uploads/";  // Change this to the directory where you want to store the uploaded files
    $targetFile = $targetDirectory . basename($_FILES["itemPicture"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if the file is an actual image or a fake image
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["itemPicture"]["tmp_name"]);
        if ($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }

    // Check if file already exists
    if (file_exists($targetFile)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["itemPicture"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
        echo "<script>setTimeout(function() { document.getElementById('errorMessage').style.display='none'; }, 10000);</script>";
    } else {
        // if everything is ok, try to upload file
        if (move_uploaded_file($_FILES["itemPicture"]["tmp_name"], $targetFile)) {
            echo "The file " . htmlspecialchars(basename($_FILES["itemPicture"]["name"])) . " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }

    $insertQuery = "INSERT INTO items (itemName, itemNumber, itemDin, itemDout, itemDescription, itemState, itemPicture) 
                    VALUES ('$voorwerp_naam', '$item_number', '$datum_inleveren', '$datum_terugbrengen', '$item_description', '$item_state', '$targetFile')";

    if ($conn->query($insertQuery) === TRUE) {
        $successMessage = "Item successfully added!";
        echo "<script>setTimeout(function() { document.getElementById('successMessage').style.display='none'; }, 3000);</script>";
    } else {
        $errorMessage = "Error: " . $insertQuery . "<br>" . $conn->error;
        echo "<script>setTimeout(function() { document.getElementById('errorMessage').style.display='none'; }, 3000);</script>";
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
        <div id="successMessage" class="alert alert-success animate__animated animate__fadeIn" style="display:none;">
            <strong>Success!</strong> <?php echo $successMessage; ?>
        </div>
        <div id="errorMessage" class="alert alert-danger animate__animated animate__fadeIn" style="display:none;">
            <strong>Error!</strong> <?php echo $errorMessage; ?>
        </div>
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
                    <input type="file" class="custom-file-input" id="itemPicture" name="itemPicture" onchange="updateFileNameLabel(this)">
                    <label class="custom-file-label" id="itemPictureLabel" for="itemPicture">Choose file</label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Voeg Item Toe</button>
        </form>
    </div>

    <?php
    // Close the database connection
    $conn->close();
    ?>

    <!-- Add Bootstrap JS and Popper.js -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

    <script>
        function updateFileNameLabel(input) {
            var fileName = input.files[0].name;
            document.getElementById("itemPictureLabel").innerText = fileName;
        }
    </script>
</body>
</html>
