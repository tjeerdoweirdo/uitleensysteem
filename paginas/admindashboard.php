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

// Query voor vertraagde items
$delayedItemsQuery = "SELECT voorwerp_naam, category, datum_inleveren, student_id FROM uitleningen WHERE datum_inleveren < CURRENT_DATE";
$delayedItemsResult = $conn->query($delayedItemsQuery);

// Query voor items die vandaag moeten worden ingeleverd
$todayItemsQuery = "SELECT voorwerp_naam, category, datum_inleveren, student_id FROM uitleningen WHERE datum_inleveren = CURRENT_DATE";
$todayItemsResult = $conn->query($todayItemsQuery);

// Query voor momenteel uitgeleende items
$currentItemsQuery = "SELECT voorwerp_naam, category, datum_inleveren, student_id FROM uitleningen WHERE datum_inleveren > CURRENT_DATE";
$currentItemsResult = $conn->query($currentItemsQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elektronica Uitleen Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h2 {
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Vertraagde Items</h2>
    <table border="1">
        <tr>
            <th>Voorwerp Naam</th>
            <th>Categorie</th>
            <th>Datum van Inleveren</th>
            <th>Student ID</th>
        </tr>
        <?php
        while ($row = $delayedItemsResult->fetch_assoc()) {
            echo "<tr><td>{$row['voorwerp_naam']}</td><td>{$row['category']}</td><td>{$row['datum_inleveren']}</td><td>{$row['student_id']}</td></tr>";
        }
        ?>
    </table>

    <h2>Items die vandaag moeten worden ingeleverd</h2>
    <table border="1">
        <tr>
            <th>Voorwerp Naam</th>
            <th>Categorie</th>
            <th>Datum van Inleveren</th>
            <th>Student ID</th>
        </tr>
        <?php
        while ($row = $todayItemsResult->fetch_assoc()) {
            echo "<tr><td>{$row['voorwerp_naam']}</td><td>{$row['category']}</td><td>{$row['datum_inleveren']}</td><td>{$row['student_id']}</td></tr>";
        }
        ?>
    </table>

    <h2>Momenteel Uitgeleende Items</h2>
    <table border="1">
        <tr>
            <th>Voorwerp Naam</th>
            <th>Categorie</th>
            <th>Datum van Inleveren</th>
            <th>Student ID</th>
        </tr>
        <?php
        while ($row = $currentItemsResult->fetch_assoc()) {
            echo "<tr><td>{$row['voorwerp_naam']}</td><td>{$row['category']}</td><td>{$row['datum_inleveren']}</td><td>{$row['student_id']}</td></tr>";
        }
        ?>
    </table>

    <?php
    // Sluit de databaseverbinding
    $conn->close();
    ?>
</body>
</html>
