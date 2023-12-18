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

        th, td {
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

        // Haal items op die te laat zijn ingeleverd
        $teLaatQuery = "SELECT * FROM uitleningen WHERE datum_inleveren < CURRENT_DATE";
        $teLaatResult = $conn->query($teLaatQuery);

        // Haal items op die vandaag moeten worden ingeleverd
        $vandaagQuery = "SELECT * FROM uitleningen WHERE datum_inleveren = CURRENT_DATE";
        $vandaagResult = $conn->query($vandaagQuery);

        // Haal alles op wat nu wordt uitgeleend
        $nuUitgeleendQuery = "SELECT * FROM uitleningen WHERE datum_inleveren > CURRENT_DATE";
        $nuUitgeleendResult = $conn->query($nuUitgeleendQuery);
        ?>

        <section>
            <h3>Te Laat</h3>
            <table>
                <tr>
                    <th>Voorwerp Naam</th>
                    <th>Datum van Inleveren</th>
                    <th>Student ID</th>
                    <th>category<th>
                </tr>
                <?php
                while ($row = $teLaatResult->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['voorwerp_naam']}</td>
                            <td>{$row['datum_inleveren']}</td>
                            <td>{$row['student_id']}</td>
                            <td>{$row['category']}</td>
                          </tr>";
                }
                ?>
            </table>
        </section>

        <section>
            <h3>Vandaag Inleveren</h3>
            <table>
                <tr>
                    <th>Voorwerp Naam</th>
                    <th>Datum van Inleveren</th>
                    <th>Student ID</th>
                    <th>category<th>
                </tr>
                <?php
                while ($row = $vandaagResult->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['voorwerp_naam']}</td>
                            <td>{$row['datum_inleveren']}</td>
                            <td>{$row['student_id']}</td>
                            <td>{$row['category']}</td>
                          </tr>";
                }
                ?>
            </table>
        </section>

        <section>
            <h3>Nu Uitgeleend</h3>
            <table>
                <tr>
                    <th>Voorwerp Naam</th>
                    <th>Datum van Inleveren</th>
                    <th>Student ID</th>
                    <th>category<th>
                </tr>
                <?php
                while ($row = $nuUitgeleendResult->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['voorwerp_naam']}</td>
                            <td>{$row['datum_inleveren']}</td>
                            <td>{$row['student_id']}</td>
                            <td>{$row['category']}</td>

                          </tr>";
                }
                ?>
            </table>
        </section>

        <?php
        // Sluit de databaseverbinding
        $conn->close();
        ?>
    </section>
</body>
</html>
