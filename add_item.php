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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Item Toevoegen</title>
</head>
<body>
    <h2>Item Toevoegen</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
        <label for="voorwerp_naam">Voorwerp Naam:</label>
        <input type="text" name="voorwerp_naam" required><br>

        <label for="category">Categorie:</label>
        <input type="text" name="category" required><br>

        <label for="datum_inleveren">Datum van Inleveren:</label>
        <input type="date" name="datum_inleveren" required><br>

        <label for="student_id">Student ID:</label>
        <input type="text" name="student_id" required><br>

        <input type="submit" value="Voeg Item Toe">
    </form>

    <?php
    // Sluit de databaseverbinding
    $conn->close();
    ?>
</body>
</html>
