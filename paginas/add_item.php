<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once('../includes/db_connection.php');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$categoryQuery = "SELECT catId, catName FROM categories";
$categoryResult = $conn->query($categoryQuery);

if ($categoryResult->num_rows > 0) {
    while ($row = $categoryResult->fetch_assoc()) {
        $categories[] = $row;
    }
}

$successMessage = $errorMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uploadOk = 1;

    $voorwerp_naam = $_POST["itemName"];
    $item_number = $_POST["itemNumber"];
    $item_description = $_POST["itemDescription"];
    $item_category = $_POST["itemCategory"];

    // Item status
    $item_status = 'teruggebracht';

    $targetDirectory = "uploads/";
    $targetFile = $targetDirectory . basename($_FILES["itemPicture"]["name"]);
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // File upload validation
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

    if (file_exists($targetFile)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    if ($_FILES["itemPicture"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
        echo "<script>setTimeout(function() { document.getElementById('errorMessage').style.display='none'; }, 10000);</script>";
    } else {
        if (move_uploaded_file($_FILES["itemPicture"]["tmp_name"], $targetFile)) {
            echo "The file " . htmlspecialchars(basename($_FILES["itemPicture"]["name"])) . " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }

    // Update SQL query to include item status
    $insertQuery = "INSERT INTO items (itemName, itemNumber, itemDescription, itemPicture, catId, itemState) 
                    VALUES ('$voorwerp_naam', '$item_number', '$item_description', '$targetFile', '$item_category', '$item_state')";

    if ($conn->query($insertQuery) === TRUE) {
        $successMessage = "Item successfully added!";
    } else {
        $errorMessage = "Error: " . $insertQuery . "<br>" . $conn->error;
    }

    header("Location: add_item.php");
    exit();
}

include '../includes/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Item Toevoegen</title>

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <style>
        .animated {
            animation-duration: 1s;
        }

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
                <label for="itemDescription">Item Omschrijving:</label>
                <input type="text" class="form-control" name="itemDescription">
            </div>

            <div class="form-group">
                <label for="itemCategory">Item Category:</label>
                <select class="form-control" name="itemCategory">
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['catId']; ?>"><?php echo $category['catName']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="itemPicture">Item Afbeelding:</label>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="itemPicture" name="itemPicture" onchange="updateFileNameLabel(this)">
                    <label class="custom-file-label" id="itemPictureLabel" for="itemPicture">Choose file</label>
                </div>
            </div>
            
            <!-- Display item status -->
            <div class="form-group">
                <label for="itemStatus">Item Status:</label>
                <input type="text" class="form-control" name="itemStatus" value="teruggebracht" disabled>
            </div>

            <button type="submit" class="btn btn-primary">Voeg Item Toe</button>
        </form>
    </div>
    <script>
        function updateFileNameLabel(input) {
            var fileName = input.files[0].name;
            document.getElementById("itemPictureLabel").innerText = fileName;
        }
    </script>

    <?php
    $conn->close();
    ?>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
