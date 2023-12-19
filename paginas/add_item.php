<?php
// Verbindingsparameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "uitleensysteem";

// Maak verbinding met de database
$conn = new mysqli($servername, $username, $password, $dbname);

// Controleer de verbinding
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Voeg een nieuw item toe
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $voorwerp_naam = $_POST["voorwerp_naam"];
    $category = $_POST["category"];
    $datum_inleveren = $_POST["datum_inleveren"];
    $student_id = $_POST["student_id"];

    $insertQuery = "INSERT INTO uitleningen (voorwerp_naam, category, datum_inleveren, student_id) VALUES ('$voorwerp_naam', '$category', '$datum_inleveren', '$student_id')";

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
</head>
<body>
    <div class="container">
        <h2>Item Toevoegen</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
            <div class="form-group">
                <label for="voorwerp_naam">Voorwerp Naam:</label>
                <input type="text" class="form-control" name="voorwerp_naam" required>
            </div>

            <div class="form-group">
                <label for="category">Categorie:</label>
                <input type="text" class="form-control" name="category" required>
            </div>

            <div class="form-group">
                <label for="datum_inleveren">Datum van Inleveren:</label>
                <input type="date" class="form-control" name="datum_inleveren" required>
            </div>

            <div class="form-group">
                <label for="student_id">Student ID:</label>
                <input type="text" class="form-control" name="student_id" required>
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
