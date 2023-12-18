<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toevoegen - Elektronica Uitleen Admin</title>
    <style>
        body {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 8px;
        }

        input, select {
            margin-bottom: 16px;
            padding: 8px;
        }

        input[type="submit"] {
            background-color: #4caf50;
            color: white;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h2>Toevoegen - Elektronica Uitleen Admin</h2>

    <?php
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

    // Verwerk het toevoegen van een nieuw item
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Check if "category" is set in the $_POST array
        if (isset($_POST["category"])) {
            $voorwerp_naam = $_POST["voorwerp_naam"];
            $datum_inleveren = $_POST["datum_inleveren"];
            $student_id = $_POST["student_id"];
            $category = $_POST["category"];

            $toevoegenQuery = "INSERT INTO uitleningen (voorwerp_naam, datum_inleveren, student_id, category) VALUES ('$voorwerp_naam', '$datum_inleveren', '$student_id', '$category')";

            if ($conn->query($toevoegenQuery) === TRUE) {
                echo "Item succesvol toegevoegd.";
            } else {
                echo "Error: " . $toevoegenQuery . "<br>" . $conn->error;
            }
        } else {
            echo "Error: Category not set in the form.";
        }
    }

    // Sluit de databaseverbinding
    $conn->close();
    ?>

    <form action="toevoegen_verwerk.php" method="post">
        <label for="voorwerp_naam">Voorwerp Naam:</label>
        <input type="text" id="voorwerp_naam" name="voorwerp_naam" required>

        <label for="datum_inleveren">Datum van Inleveren:</label>
        <input type="date" id="datum_inleveren" name="datum_inleveren" required>

        <label for="student_id">Student ID:</label>
        <input type="text" id="student_id" name="student_id" required>

        <label for="category">Category:</label>
        <!-- Change the input field to a select element -->
        <select id="category" name="category" required>
            <option value="hardware">hardware</option>
            <option value="accessory">accessory</option>
            <!-- Add more options as needed -->
        </select>

        <input type="submit" value="Toevoegen">
    </form>
</body>
</html>
