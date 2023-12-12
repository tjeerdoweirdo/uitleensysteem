<?php
// Verbinding maken met de database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "uitleensysteem";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Voeg een nieuw item toe
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["submit"])) {
        $naam = $_POST["naam"];
        $conditie = $_POST["conditie"];

        $sql = "INSERT INTO items (naam, conditie) VALUES ('$naam', '$conditie')";

        if ($conn->query($sql) === TRUE) {
            echo "Nieuw item toegevoegd!";
        } else {
            echo "Fout bij toevoegen item: " . $conn->error;
        }
    }
}

// Haal de items op uit de database
$sql = "SELECT * FROM items";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
</head>
<body>
    <h1>Admin Dashboard</h1>

    <!-- Formulier voor het toevoegen van een nieuw item -->
    <form method="post" action="">
        Naam: <input type="text" name="naam" required><br>
        Conditie: <input type="text" name="conditie" required><br>
        <input type="submit" name="submit" value="Voeg toe">
    </form>

    <!-- Tabel om de items weer te geven -->
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Naam</th>
            <th>Conditie</th>
        </tr>
        <?php
        // Toon de items in de tabel
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td>" . $row["id"] . "</td><td>" . $row["naam"] . "</td><td>" . $row["conditie"] . "</td></tr>";
            }
        } else {
            echo "<tr><td colspan='3'>Geen items gevonden</td></tr>";
        }
        ?>
    </table>

    <?php
    // Sluit de databaseverbinding
    $conn->close();
    ?>
</body>
</html>
