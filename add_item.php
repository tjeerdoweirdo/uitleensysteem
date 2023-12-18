<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elektronica Uitleen Admin</title>
    <style>
        body {
            display: flex;
            flex-wrap: wrap;
        }

        section {
            flex: 1;
            padding: 20px;
            box-sizing: border-box;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <section>
        <h2>Elektronica Uitleen Admin</h2>

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

        // Handle form submission for adding items
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $voorwerpNaam = $_POST["voorwerp_naam"];
            $datumInleveren = $_POST["datum_inleveren"];
            $studentID = $_POST["student_id"];

            $insertQuery = "INSERT INTO uitleningen (voorwerp_naam, datum_inleveren, student_id) 
                            VALUES ('$voorwerpNaam', '$datumInleveren', '$studentID')";

            if ($conn->query($insertQuery) === TRUE) {
                echo "<p>Item successfully added to the database.</p>";
            } else {
                echo "Error: " . $insertQuery . "<br>" . $conn->error;
            }
        }
        ?>

        <h2>Add Item to Elektronica Uitleen</h2>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <label for="voorwerp_naam">Voorwerp Naam:</label>
            <input type="text" name="voorwerp_naam" required><br>

            <label for="datum_inleveren">Datum van Inleveren:</label>
            <input type="date" name="datum_inleveren" required><br>

            <label for="student_id">Student ID:</label>
            <input type="text" name="student_id" required><br>

            <input type="submit" value="Add Item">
        </form>
</body>
</html>