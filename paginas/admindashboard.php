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

// Verwijder een item
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["delete"])) {
        $delete_id = $_POST["delete"];

        $delete_sql = "DELETE FROM items WHERE id = $delete_id";

        if ($conn->query($delete_sql) === TRUE) {
            echo "Item verwijderd!";
        } else {
            echo "Fout bij verwijderen item: " . $conn->error;
        }
    }
}

// Voeg een nieuw item toe
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["submit"])) {
        $naam = $_POST["naam"];
        $conditie = $_POST["conditie"];

        // Check if the "foto" key is set in $_FILES
        if (isset($_FILES["foto"]["name"])) {
            $foto = $_FILES["foto"]["name"];

            // Map waar de geüploade foto's worden opgeslagen
            $upload_dir = "uploads/";

            // Upload de foto naar de map
            move_uploaded_file($_FILES["foto"]["tmp_name"], $upload_dir . $foto);

            $sql = "INSERT INTO items (naam, conditie, foto) VALUES ('$naam', '$conditie', '$foto')";

            if ($conn->query($sql) === TRUE) {
                echo "Nieuw item toegevoegd!";
            } else {
                echo "Fout bij toevoegen item: " . $conn->error;
            }
        } else {
            // Handle the case where no file is uploaded if needed
            echo "Geen foto geüpload.";
        }
    }
}

// Haal de items op uit de database
$sql = "SELECT id, naam, conditie, foto FROM items";
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
    <form method="post" action="" enctype="multipart/form-data">
        Naam: <input type="text" name="naam" required><br>
        Conditie:
        <select name="conditie">
            <option value="goed">Goed</option>
            <option value="defect">Defect</option>
            <option value="kapot">Kapot</option>
        </select><br>
        Foto: <input type="file" name="foto" accept="image/*" required><br>
        <input type="submit" name="submit" value="Voeg toe">
    </form>

    <!-- Tabel om de items weer te geven -->
    <table border="1">
        <tr>
            <th>Naam</th>
            <th>Conditie</th>
            <th>Foto</th>
            <th>Acties</th>
        </tr>
        <?php
        // Toon de items in de tabel
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row["naam"] . "</td>
                        <td>" . $row["conditie"] . "</td>
                        <td><img src='uploads/" . $row["foto"] . "' alt='Item Foto' style='max-width: 100px;'></td>
                        <td>
                            <form method='post' action=''>
                                <input type='hidden' name='delete' value='" . $row["id"] . "'>
                                <input type='submit' value='Verwijder'>
                            </form>
                        </td>
                    </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>Geen items gevonden</td></tr>";
        }
        ?>
    </table>

    <?php
    // Sluit de databaseverbinding
    $conn->close();
    ?>
</body>

</html>
