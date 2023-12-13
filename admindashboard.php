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
        $category = ($_POST["category"] === "custom") ? $_POST["custom_category"] : $_POST["category"];

        // Check if the "foto" key is set in $_FILES
        if (isset($_FILES["foto"]["name"])) {
            $foto = $_FILES["foto"]["name"];

            // Map waar de geüploade foto's worden opgeslagen
            $upload_dir = "uploads/";

            // Upload de foto naar de map
            move_uploaded_file($_FILES["foto"]["tmp_name"], $upload_dir . $foto);

            $sql = "INSERT INTO items (naam, category, foto) VALUES ('$naam', '$category', '$foto')";

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

// Haal de items op uit de database met zoekfilter
$search = isset($_GET['search']) ? $_GET['search'] : '';
$sql = "SELECT id, naam, category, foto FROM items WHERE naam LIKE '%$search%'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Admin Dashboard</title>
</head>

<body>
    <div class="container">
        <h1>Admin Dashboard</h1>

        <!-- Searchbar for the 'name' column -->
        <form method="get" action="">
            Zoek op naam: <input type="text" name="search" placeholder="Voer naam in">
            <input type="submit" value="Zoek">
        </form>

        <!-- Formulier voor het toevoegen van een nieuw item -->
        <form method="post" action="" enctype="multipart/form-data">
            Naam: <input type="text" name="naam" required><br>
            Categorie: <!-- Updated category dropdown with organized options -->
            <select name="category">
                <option value="keyboard">Toetsenbord</option>
                <option value="mouse">Muis</option>
                <option value="pc">Computer</option>
                <option value="headphones">Koptelefoon</option>
                <option value="tablet">Tablet</option>
                <option value="camera">Camera</option>
                <option value="printer">Printer</option>
                <option value="custom">Aangepast</option>
            </select><br>
            <!-- Allow entering a custom category -->
            Of voer een aangepaste categorie in: <input type="text" name="custom_category"><br>
            Foto: <input type="file" name="foto" accept="image/*" required><br>
            <input type="submit" name="submit" value="Voeg toe">
        </form>

        <!-- Tabel om de items weer te geven -->
        <table border="1">
            <tr>
                <th>Naam</th>
                <th>Categorie</th>
                <th>Foto</th>
                <th>Acties</th>
            </tr>
            <?php
            // Toon de items in de tabel
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . $row["naam"] . "</td>
                            <td>" . $row["category"] . "</td>
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
    </div>

    <?php
    // Sluit de databaseverbinding
    $conn->close();
    ?>
</body>

</html>
